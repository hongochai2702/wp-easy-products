<?php

/**
 * Sample Customizer
 *
 * @package     ThemesPond
 * @category    Sample
 * @author      Tpfw
 * @license     GPLv3
 */

/**
 * Register customizer
 */
function we_customize_register( $wp_customize ) {

	/**
	 * Full Demo
	 */
	new Tpfw_Customize_Section( $wp_customize, array(
		'id' => 'tpfw_section_demo',
		'heading' => esc_attr__( 'WE PRODUCTS CONTROLS', 'tp-framework' ),
		'fields' => array(
			array(
				'name' => 'tpfw_textfield',
				'type' => 'textfield',
				'heading' => __( 'Text field:', 'tp-framework' ),
				'value' => 'A default text',
				'desc' => __( 'A short description for textfield', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_textarea',
				'type' => 'textarea',
				'heading' => __( 'Text Area:', 'tp-framework' ),
				'value' => 'A default textarea',
				'desc' => __( 'A short description for textarea', 'tp-framework' ),
			)
		//Update later
		) )
	);
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'we_customize_register', 11 );