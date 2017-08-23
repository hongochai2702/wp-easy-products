<?php

/**
 * Sample Widget
 *
 * @package     Tpfw
 * @category    Sample
 * @author      ThemesPond
 * @license     GPLv3
 */

/**
 * Widget
 * Display in wp-admin/widgets.php
 */
class Tpfw_Example_Widget extends Tpfw_Widget {

	public function __construct() {

		$fields = tpfw_example_fields();

		$repeater = array(
			'name' => 'tpfw_repeater',
			'type' => 'repeater',
			'heading' => __( 'Repeater', 'tp-framework' ),
			'value' => '',
			'desc' => '',
			'fields' => $fields
		);
		
		$all = $fields;
		$all[] = $repeater;

		$this->widget_cssclass = 'tpfw_example_widget';
		$this->widget_description = __( "Display the sample fields in the sidebar.", 'tp-framework' );
		$this->widget_id = 'tpfw_example_widget';
		$this->widget_name = __( 'Tpfw Example Fields', 'tp-framework' );
		$this->fields = $all;
		parent::__construct();
	}
	
	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$this->widget_start( $args, $instance );
		//Your Content widget
		$this->widget_end( $args );
	}

}

/**
 * Init widget
 */
function tpfw_example_widget_init() {
	register_widget( 'Tpfw_Example_Widget' );
}

/**
 * Hook to widgets_init
 */
add_action( 'widgets_init', 'tpfw_example_widget_init' );
