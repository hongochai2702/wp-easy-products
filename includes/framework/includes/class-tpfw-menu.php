<?php

/**
 * Menu Field
 *
 * @class     Tpfw_Metaboxes
 * @package   Tpfw/Classes
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'Tpfw_Menu_Field' ) ) {

	class Tpfw_Menu_Field {

		/**
		 * @access private
		 * @var string field wrapper
		 */
		private $field_wrapper;

		/**
		 * @access private
		 * @var array form fields
		 */
		private $fields;

		/**
		 * Construct
		 */
		public function __construct() {

			$this->fields = apply_filters( 'tpfw_menu_fields', array() );

			$this->field_wrapper = '<div class="tpfw_form_row" %3$s><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>';

			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_menu' ) );
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'form' ), 10, 4 );
			add_action( 'wp_update_nav_menu_item', array( $this, 'update' ), 10, 3 );
		}

		/**
		 * Output form field
		 */
		public function form( $id, $item, $depth, $args ) {

			if ( empty( $this->fields ) ) {
				return;
			}

			global $tpfw_registered_fields;

			foreach ( $this->fields as $field ) {
				/**
				 * Field value
				 */
				$field['value'] = isset( $field['value'] ) ? $field['value'] : '';

				if ( metadata_exists( 'post', $item->ID, $field['name'] ) ) {
					$field['value'] = get_post_meta( $item->ID, $field['name'], true );
				}

				/**
				 * Add field type to global array
				 */
				$tpfw_registered_fields[] = $field['type'];

				/**
				 * Render field
				 */
				if ( $field['type'] == 'repeater' ) {
					printf( '<p class="description "><i class="fa fa-warning"></i> %s</p>', __( '<strong>Repeater field</strong> was not supported in menu item.', 'tp-framework' ) );
				} else {
					echo $this->field_render( $field, $item->ID );
				}
			}
		}

		/**
		 * Process field
		 * @access private
		 * @return string Field Html
		 */
		private function field_render( $field, $post_id ) {

			$field_output = '';

			$field['id'] = sprintf( '%s-%s', $field['name'], $post_id );

			$field['name'] = sprintf( '%s[%s]', $field['name'], $post_id );

			$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

			$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['id'], $field['heading'], $required ) : '';

			$desc = !empty( $field['desc'] ) ? sprintf( '<span class="description">%s</span>', $field['desc'] ) : '';

			$attrs = sprintf( 'data-param_name="%s" data-menu_item="%s"', $field['id'], $post_id );

			$attrs .= !empty( $field['dependency'] ) && is_array( $field['dependency'] ) ? ' data-dependency="' . esc_attr( json_encode( $field['dependency'] ) ) . '"' : '';

			/**
			 * Before render field type
			 */
			do_action( 'tpfw_before_render_field_' . $field['type'], $field );

			/**
			 * Render field
			 */
			if ( function_exists( "tpfw_form_{$field['type']}" ) ) {

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$field['value'] = call_user_func( $field['sanitize_callback'], $field['value'] );
				}

				$callback = call_user_func( "tpfw_form_{$field['type']}", $field, $field['value'] );

				$multiple = isset( $field['multiple'] ) ? $field['multiple'] : 0;

				if ( $field['type'] == 'checkbox' && !$multiple ) {

					$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s</label>', $field['id'], $field['heading'] ) : '';

					$field_output = sprintf( '<div class="tpfw_form_row tpfw_form_row--checkbox" %4$s><div class="col-field">%2$s %1$s %3$s</div></div>', $lable, $callback, $desc, $attrs );
				} else {
					$field_output = sprintf( $this->field_wrapper, $lable, $callback . $desc, $attrs );
				}
			} else if ( has_filter( "tpfw_form_{$field['type']}" ) ) {

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$field['value'] = call_user_func( $field['sanitize_callback'], $field['value'] );
				}

				$field_output = apply_filters( "tpfw_form_{$field['type']}", '', $field ) . $desc;
				$field_output = sprintf( $this->field_wrapper, $lable, $field_output, $attrs );
			}

			return $field_output;
		}

		/**
		 * Save menu meta
		 */
		public function update( $menu_id, $post_id, $menu_item_args ) {

			if ( (defined( 'DOING_AJAX' ) && DOING_AJAX) || empty( $this->fields ) ) {
				return;
			}

			check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

			foreach ( $this->fields as $field ) {

				if ( !isset( $field['name'] ) ) {
					continue;
				}

				$value = '';

				if ( isset( $_POST[$field['name']][$post_id] ) ) {

					$input_value = $_POST[$field['name']][$post_id];

					if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
						$value = call_user_func( $field['sanitize_callback'], $input_value );
					} else {

						if ( isset( $field['multiple'] ) && $field['multiple'] ) {
							$value = maybe_unserialize( $input_value );
						} elseif ( $field['type'] == 'checkbox' ) {

							$value = !empty( $input_value ) ? 1 : 0;
						} elseif ( $field['type'] == 'link' ) {

							$value = strip_tags( $input_value );
						} elseif ( $field['type'] == 'textarea' ) {

							$value = wp_kses( trim( wp_unslash( $input_value ) ), wp_kses_allowed_html( 'post' ) );
						} elseif ( $field['type'] == 'repeater' && !empty( $input_value ) ) {
							$value = json_encode( $input_value, JSON_UNESCAPED_UNICODE );
						} else {
							$value = sanitize_text_field( $input_value );
						}
					}

					/**
					 * Allow third party filter value
					 */
					$value = apply_filters( "tpfw_sanitize_field_{$field['type']}", $value, $input_value );

					update_post_meta( $post_id, $field['name'], $value );
				} else {
					delete_post_meta( $post_id, $field['name'] );
				}
			}
		}

		public function edit_menu() {
			if ( class_exists( 'Tpfw_Walker_Nav_Menu_Edit' ) ) {
				return 'Tpfw_Walker_Nav_Menu_Edit';
			}
		}

	}

}
if ( !class_exists( 'Walker_Nav_Menu_Edit' ) ) {
	/** Walker_Nav_Menu_Edit class */
	require_once( ABSPATH . 'wp-admin/includes/class-walker-nav-menu-edit.php' );
}

if ( !class_exists( 'Tpfw_Walker_Nav_Menu_Edit' ) ) {

	class Tpfw_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$item_output = '';
			parent::start_el( $item_output, $item, $depth, $args, $id );
			$output .= preg_replace(
					// NOTE: Check this regex from time to time!
					'/(?=<p[^>]+class="[^"]*field-description)/', $this->get_fields( $item, $depth, $args, esc_attr( $item->ID ) ), $item_output
			);
		}

		protected function get_fields( $item, $depth, $args = array(), $id ) {
			ob_start();

			/**
			 * Get menu item custom fields from plugins/themes
			 *
			 * @since 0.1.0
			 *
			 * @param object $item  Menu item data object.
			 * @param int    $depth  Depth of menu item. Used for padding.
			 * @param array  $args  Menu item args.
			 * @param int    $id    Nav menu ID.
			 *
			 * @return string Custom fields
			 */
			do_action( 'wp_nav_menu_item_custom_fields', $id, $item, $depth, $args );
			return ob_get_clean();
		}

	}

}

new Tpfw_Menu_Field();
