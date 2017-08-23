<?php

/**
 * Abstract Widget Class
 *
 * @class     Tpfw_Widget
 * @extends	  WP_Widget
 * @package   Tpfw/Classes
 * @category  Abstract Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Tpfw_Widget abstract class
 */
abstract class Tpfw_Widget extends WP_Widget {

	/**
	 * CSS class.
	 *
	 * @var string
	 */
	public $widget_cssclass;

	/**
	 * Widget description.
	 *
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget ID.
	 *
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $widget_name;

	/**
	 * Settings.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * @access private
	 * @var string Container of the field in markup HTML
	 */
	private $field_wrapper;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname' => $this->widget_cssclass,
			'description' => $this->widget_description,
			'customize_selective_refresh' => true
		);



		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'registered_fields' ), 5 );
	}

	/**
	 * Get registered field in widget and hook to admin_enqueue_scripts
	 * @since 1.0
	 */
	public function registered_fields( $hook ) {
		if ( $hook == 'widgets.php' ) {
			global $tpfw_registered_fields;

			if ( empty( $tpfw_registered_fields ) ) {
				$tpfw_registered_fields = array();
			}

			foreach ( $this->fields as $key => $field ) {

				$tpfw_registered_fields[] = $field['type'];

				if ( !empty( $field['fields'] ) ) {
					foreach ( $field['fields'] as $k => $child ) {
						$tpfw_registered_fields[] = $child['type'];
					}
				}
			}
		}
	}

	/**
	 * Get cached widget.
	 *
	 * @param  array $args
	 * @return bool true if the widget is cached otherwise false
	 */
	public function get_cached_widget( $args ) {

		$cache = wp_cache_get( apply_filters( 'tpfw_cached_widget_id', $this->widget_id ), 'widget' );

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget.
	 *
	 * @param  array $args
	 * @param  string $content
	 * @return string the content that was cached
	 */
	public function cache_widget( $args, $content ) {
		wp_cache_set( apply_filters( 'tpfw_cached_widget_id', $this->widget_id ), array( $args['widget_id'] => $content ), 'widget' );

		return $content;
	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache() {
		wp_cache_delete( apply_filters( 'tpfw_cached_widget_id', $this->widget_id ), 'widget' );
	}

	/**
	 * Output the html at the start of a widget.
	 *
	 * @param  array $args
	 * @return string
	 */
	public function widget_start( $args, $instance ) {
		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
	}

	/**
	 * Output the html at the end of a widget.
	 *
	 * @param  array $args
	 * @return string
	 */
	public function widget_end( $args ) {
		echo $args['after_widget'];
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see    WP_Widget->update
	 * @param  array $new_instance
	 * @param  array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		if ( empty( $this->fields ) ) {
			return $instance;
		}

		// Loop fields and get values to save.
		foreach ( $this->fields as $field ) {

			if ( !isset( $field['type'] ) && !isset( $new_instance[$field['name']] ) ) {
				continue;
			}

			$value = '';
			
			$input_value = $new_instance[$field['name']];


			if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
				$input_value = call_user_func( $field['sanitize_callback'], $input_value );
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
					$value = strip_tags( $input_value );
				} else {
					$value = sanitize_text_field( $input_value );
				}
			}
			/**
			 * Allow third party filter value
			 */
			$value = apply_filters( "tpfw_sanitize_field_{$field['type']}", $value, $new_instance[$field['name']] );

			/**
			 * Sanitize the value of a field.
			 */
			$instance[$field['name']] = $value;
		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Outputs the fields update form.
	 *
	 * @see   WP_Widget->form
	 * @param array $instance
	 */
	public function form( $instance ) {

		if ( empty( $this->fields ) ) {
			return;
		}

		$this->field_wrapper = '<div class="tpfw_form_row" %3$s><div class="col-label">%1$s</div><div class="col-field">%2$s</div></div>';

		$output = '';

		foreach ( $this->fields as $field ) {
			/**
			 * @var $default_value Default Value
			 */
			$default_value = isset( $field['value'] ) ? $field['value'] : '';

			/**
			 * @var $default_value Default Value
			 * If you used to use `std` as a `default value`, please use it :)
			 */
			$default_value = isset( $field['std'] ) ? $field['std'] : $default_value;

			/**
			 * @var $value Field value
			 */
			$value = isset( $instance[$field['name']] ) ? $instance[$field['name']] : $default_value;

			//Base Name
			$field['_name'] = $field['name'];
			//Base ID
			$field['_id'] = $field['name'];

			$field['id'] = $this->get_field_id( $field['name'] );

			$field['name'] = $this->get_field_name( $field['name'] );

			$field['widget_support'] = true;

			/**
			 * Add field type to global array
			 */
			$tpfw_registered_fields[] = $field['type'];


			/**
			 * Before render field type
			 */
			do_action( 'tpfw_before_render_field_' . $field['type'], $field );

			/*
			 * Print field
			 */
			if ( function_exists( "tpfw_form_{$field['type']}" ) ) {

				$required = isset( $field['required'] ) && absint( $field['required'] ) ? '<span>*</span>' : '';

				$desc = !empty( $field['desc'] ) ? sprintf( '<p class="description">%s</p>', $field['desc'] ) : '';

				$attrs = sprintf( 'data-param_name="%s" ', $field['id'] );

				$attrs .= !empty( $field['dependency'] ) && is_array( $field['dependency'] ) ? 'data-dependency="' . esc_attr( json_encode( $field['dependency'] ) ) . '"' : '';

				$multiple = isset( $field['multiple'] ) ? $field['multiple'] : 0;

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$value = call_user_func( $field['sanitize_callback'], $value );
				}

				if ( $field['type'] == 'checkbox' && !$multiple ) {

					$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s</label>', $field['id'], $field['heading'] ) : '';

					$checkbox_wrapper = '<div class="tpfw_form_row" %4$s><div class="col-field">%2$s %1$s %3$s</div></div>';

					$output .= sprintf( $checkbox_wrapper, $lable, call_user_func( "tpfw_form_{$field['type']}", $field, $value ), $desc, $attrs );
				} else {

					$lable = !empty( $field['heading'] ) ? sprintf( '<label for="%1$s">%2$s %3$s</label>', $field['id'], $field['heading'], $required ) : '';

					$output .= sprintf( $this->field_wrapper, $lable, call_user_func( "tpfw_form_{$field['type']}", $field, $value ) . $desc, $attrs );
				}
			} else if ( has_filter( "tpfw_form_{$field['type']}" ) ) {

				$field['value'] = $value;

				if ( isset( $field['sanitize_callback'] ) && function_exists( $field['sanitize_callback'] ) ) {
					$field['value'] = call_user_func( $field['sanitize_callback'], $value );
				}

				$field_output = apply_filters( "tpfw_form_{$field['type']}", '', $field );
				$output .= sprintf( $this->field_wrapper, $lable, $field_output . $desc, $attrs );
			}
		}

		$tpfw_registered_fields = array_unique( $tpfw_registered_fields );

		echo $output;
	}

}
