<?php

/**
 * Taxonomy Framework
 *
 * @class     Tpfw_Metaboxes
 * @package   Tpfw/Classes
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( !class_exists( 'Tpfw_Taxonomy' ) ) {

	/**
	 * Tpfw_Taxonomy Class
	 */
	class Tpfw_Taxonomy {

		/**
		 * @access private
		 * @var array meta boxes settings
		 */
		private $settings = array();

		/**
		 * @access private
		 * @var string Container of the field in markup HTML
		 */
		private $field_wrapper;

		/**
		 * @access private
		 * @var string Html of all output field
		 */
		private $output;

		/**
		 * @access private
		 * @var array Group fields
		 */
		private $group_fields = array();

		/**
		 * @access private
		 * Template wrapper for form adding
		 */
		private $field_add_wrapper;

		/**
		 * @access private
		 * Template wrapper for form editing
		 */
		private $field_edit_wrapper;

		/**
		 * Init
		 */
		public function __construct( $args = array() ) {

			if ( !empty( $args ) ) {

				$defaults = array(
					'id' => 'tpfw_termbox',
					'pages' => array(),
					'title' => __( 'Tpfw Termbox', 'tp-framework' ),
					'fields' => array(
					)
				);

				$this->settings = wp_parse_args( $args, $defaults );

				$this->init();
			}
		}

		/**
		 * Register hook
		 * @return void
		 */
		public function init() {

			$term_id = isset( $_GET['tag_ID'] ) ? absint( $_GET['tag_ID'] ) : 0;

			$this->field_edit_wrapper = '<tr class="form-field term-group-wrap tpfw_form_row" %3$s><th scope="row">%1$s</th><td>%2$s</td></tr>';
			$this->field_add_wrapper = '<div class="tpfw_form_row" %3$s><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>';

			$this->output = $this->pre_output( $term_id );

			foreach ( $this->settings['pages'] as $page ) {

				add_action( 'created_' . $page, array( $this, 'add_term_meta' ), 10, 2 );
				add_action( 'edited_' . $page, array( $this, 'edit_term_meta' ), 10, 2 );

				add_action( $page . '_add_form_fields', array( $this, 'output_form_add' ), 10, 2 );
				add_action( $page . '_edit_form_fields', array( $this, 'output_form_edit' ), 10, 2 );
			}
		}

		/**
		 * Screen form add
		 */
		public function output_form_add( $taxonomy ) {
			echo $this->output;
		}

		/**
		 * Screen form edit
		 */
		public function output_form_edit( $term, $taxonomy ) {
			echo $this->output;
		}

		/**
		 * Save value in form add term meta
		 */
		public function add_term_meta( $term_id, $tt_id ) {
			$this->update_term_meta( $term_id, $tt_id, 'add' );
		}

		/**
		 * Save value in form edit term meta
		 */
		public function edit_term_meta( $term_id, $tt_id ) {
			$this->update_term_meta( $term_id, $tt_id, 'edit' );
		}

		/**
		 * Process field output
		 * 
		 * @global array $tpfw_registered_fields
		 * @param int $term_id
		 * @return string Html
		 */
		private function pre_output( $term_id = 0 ) {

			global $tpfw_registered_fields;

			if ( empty( $tpfw_registered_fields ) ) {
				$tpfw_registered_fields = array();
			}

			$output = '';

			$metabox = $this->settings;

			$output .= sprintf( '<input type="hidden" name="%s_nonce" value="%s" />', $metabox['id'], wp_create_nonce( $metabox['id'] ) );

			foreach ( $metabox['fields'] as $field ) {
				/**
				 * Field value
				 */
				$value = '';

				if ( $term_id ) {
					$value = get_term_meta( $term_id, $field['name'], false );
				} else {
					$value = null;
				}

				if ( isset( $value[0] ) ) {
					$value = $value[0];
				} elseif ( empty( $value[0] ) ) {
					$value = isset( $field['value'] ) ? $field['value'] : '';
				}

				$field['value'] = $value;

				/**
				 * Add field type to global array
				 */
				$tpfw_registered_fields[] = $field['type'];


				/**
				 * Before render field type
				 */
				do_action( 'tpfw_before_render_field_' . $field['type'], $field );

				/**
				 * Add field to group
				 * @since 1.0.0
				 */
				$group = !empty( $field['group'] ) ? $field['group'] : '';

				if ( empty( $this->group_fields[$group] ) ) {
					$this->group_fields[$group] = array();
				}
				$this->group_fields[$group][] = $field;
			}


			if ( count( $this->group_fields ) == 1 && !key( $this->group_fields ) ) {

				if ( $term_id ) {
					$this->field_wrapper = $this->field_edit_wrapper;
				} else {
					$this->field_wrapper = $this->field_add_wrapper;
				}

				$fields = $this->group_fields[''];
				foreach ( $fields as $key => $field ) {
					$output .= $this->field_render( $field );
				}
			} else {

				$nav = '';
				$content = '';
				$index = 0;

				//Use form add for all field in group
				$this->field_wrapper = $this->field_add_wrapper;

				foreach ( $this->group_fields as $name => $fields ) {
					$name = empty( $name ) ? __( 'General', 'tp-framework' ) : $name;
					$index ++;
					$active = $index == 1 ? 'active' : '';
					$id = $this->settings['id'] . '-group_' . $index;
					$nav .= sprintf( '<li><a href="#%s" class="%s">%s</a></li>', $id, $active, $name );

					$field_html = '';

					foreach ( $fields as $key => $field ) {
						$field_html .= $this->field_render( $field );
					}

					$content .= sprintf( '<div id="%s" class="group_item %s">%s</div>', $id, $active, $field_html );
				}

				$output .= '<div class="tpfw_group">';
				$output .= '<ul class="group_nav">' . $nav . '</ul>';
				$output .= '<div class="group_panel">' . $content . '</div>';
				$output .= '</div>';

				if ( $term_id ) {
					$this->field_wrapper = $this->field_edit_wrapper;
				} else {
					$this->field_wrapper = $this->field_add_wrapper;
				}

				$group_label = !empty( $metabox['heading'] ) ? $metabox['heading'] : '';

				if ( !empty( $metabox['manage_box'] ) ) {

					$value = '';

					if ( $term_id ) {
						$value = get_term_meta( $term_id, $metabox['id'], true );
					}

					$checkbox = '<input class="tpfw-manage_group" type="checkbox" name="' . $metabox['id'] . '" value="1" ' . checked( 1, $value, false ) . '/>';
					$group_label = $checkbox . esc_html__( 'Enable', 'tp-framework' ) . ' ' . $group_label;
				}

				$group_label = sprintf( '<label>%s</label>', $group_label );

				$output = sprintf( $this->field_wrapper, $group_label, $output, '' );
			}

			$tpfw_registered_fields = array_unique( $tpfw_registered_fields );

			return $output;
		}

		/**
		 * Process field
		 * @access private
		 * @return string Field Html
		 */
		private function field_render( $field ) {

			$field_output = '';

			$field['id'] = $field['name'];

			$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

			$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['name'], $field['heading'], $required ) : '';

			$desc = !empty( $field['desc'] ) ? sprintf( '<p class="desc description">%s</p>', $field['desc'] ) : '';

			$attrs = sprintf( 'data-param_name="%s" ', $field['name'] );

			$attrs .= !empty( $field['dependency'] ) && is_array( $field['dependency'] ) ? 'data-dependency="' . esc_attr( json_encode( $field['dependency'] ) ) . '"' : '';

			if ( function_exists( "tpfw_form_{$field['type']}" ) ) {

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$field['value'] = call_user_func( $field['sanitize_callback'], $field['value'] );
				}

				$field_output = sprintf( $this->field_wrapper, $lable, call_user_func( "tpfw_form_{$field['type']}", $field, $field['value'] ) . $desc, $attrs );
			} else if ( has_filter( "tpfw_form_{$field['type']}" ) ) {

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$field['value'] = call_user_func( $field['sanitize_callback'], $field['value'] );
				}

				$field_output = apply_filters( "tpfw_form_{$field['type']}", '', $field );
				$field_output = sprintf( $this->field_wrapper, $lable, $field_output . $desc, $attrs );
			}

			return $field_output;
		}

		/**
		 * Save term meta
		 * @access private
		 * @return void
		 */
		private function update_term_meta( $term_id, $tt_id, $cmd = 'add' ) {

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) )
				return $term_id;

			/* verify nonce */
			if ( !isset( $_POST[$this->settings['id'] . '_nonce'] ) || !wp_verify_nonce( $_POST[$this->settings['id'] . '_nonce'], $this->settings['id'] ) )
				return $term_id;

			$allow_save = true;

			if ( !empty( $this->settings['manage_box'] ) ) {

				$value = isset( $_POST[$this->settings['id']] ) ? sanitize_text_field( $_POST[$this->settings['id']] ) : '';

				if ( !$value ) {
					$allow_save = false;
				}

				if ( $cmd == 'add' ) {
					add_term_meta( $term_id, $this->settings['id'], $value );
				} else {
					update_term_meta( $term_id, $this->settings['id'], $value );
				}
			}

			if ( $allow_save ) {
				$fields = $this->settings['fields'];

				foreach ( $fields as $field ) {

					if ( !isset( $field['name'] ) ) {
						continue;
					}

					$value = '';

					if ( isset( $_POST[$field['name']] ) || $field['type'] == 'checkbox' ) {
						
						$input_value = isset( $_POST[$field['name']] ) ? $_POST[$field['name']] : '';

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
						$value = apply_filters( "tpfw_sanitize_field_{$field['type']}", $value, $_POST[$field['name']] );

						if ( $cmd === 'add' ) {
							add_term_meta( $term_id, $field['name'], $value, true );
						} else {
							update_term_meta( $term_id, $field['name'], $value );
						}
					}
				}
			}
		}

	}

}