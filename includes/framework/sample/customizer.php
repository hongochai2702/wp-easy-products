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
function tpfw_customize_register( $wp_customize ) {

	/**
	 * Init a Panel
	 */
	$panel = new Tpfw_Customize_Panel( $wp_customize, array(
		'id' => 'tpfw_panel',
		'title' => __( 'TPFW PANEL', 'tp-framework' ),
		'description' => __( 'My Description', 'tp-framework' ),
			) );

	/**
	 * Use Panel to add single section
	 */
	$panel->add_section( array(
		'id' => 'tpfw_section_1',
		'heading' => esc_attr__( 'SECTION I', 'tp-framework' ),
		'fields' => array( //Fields in section
			array(
				'name' => 'tpfw_text_11',
				'type' => 'text',
				'heading' => 'Text Field',
			),
			array(
				'name' => 'tpfw_textarea_11',
				'type' => 'textarea',
				'heading' => __( 'Text Area', 'tp-framework' ),
			)
		)
	) );

	/**
	 * Use Panel to add a list of sections
	 */
	$panel->add_sections( array(
		//Section 2
		array(
			'id' => 'tpfw_section_2',
			'heading' => esc_attr__( 'SECTION II', 'tp-framework' ),
			'fields' => array(
				array(
					'name' => 'tpfw_text_21',
					'type' => 'text',
					'heading' => 'Text Field',
				),
				array(
					'name' => 'tpfw_textarea_22',
					'type' => 'textarea',
					'heading' => __( 'Text Area', 'tp-framework' ),
				),
			)
		),
		//Section 3
		array(
			'id' => 'tpfw_section_3',
			'heading' => esc_attr__( 'SECTION III', 'tp-framework' ),
			'fields' => array(
				array(
					'name' => 'tpfw_text_31',
					'type' => 'text',
					'heading' => 'Text Field',
				),
				array(
					'name' => 'tpfw_textarea_32',
					'type' => 'textarea',
					'heading' => __( 'Text Area', 'tp-framework' ),
				),
			)
		)
	) );

	/**
	 * Init section and addto panel
	 */
	$section4 = new Tpfw_Customize_Section( $wp_customize, array(
		'id' => 'tpfw_section_4',
		'panel' => $panel, //Add panel
		'heading' => esc_attr__( 'SECTION IV', 'tp-framework' ),
		'fields' => array(
			array(
				'name' => 'tpfw_text_41',
				'type' => 'text',
				'heading' => 'Text Field',
			),
		) )
	);

	/**
	 * Add fields to section
	 */
	$section4->add_fields( array(
		array(
			'name' => 'tpfw_textarea_42',
			'type' => 'textarea',
			'heading' => __( 'Text Area', 'tp-framework' ),
		),
		array(
			'name' => 'tpfw_checkbox_41',
			'type' => 'checkbox',
			'value' => 1,
			'heading' => __( 'Single Checkbox', 'tp-framework' )
		)
	) );

	/**
	 * Add single field to section
	 */
	$section4->add_field( array(
		'name' => 'tpfw_checkbox_42',
		'type' => 'checkbox',
		'heading' => __( 'Check list', 'tp-framework' ),
		'multiple' => 1,
		'value' => 'eric',
		'options' => array(
			'donna' => __( 'Donna Delgado', 'tp-framework' ),
			'eric' => __( 'Eric Austin', 'tp-framework' ),
			'charles' => __( 'Charles Wheeler', 'tp-framework' ),
			'anthony' => __( 'Anthony Perkins', 'tp-framework' )
		),
	) );

	/**
	 * Init field and push to section
	 */
	new Tpfw_Customize_Field( $wp_customize, array(
		'name' => 'tpfw_radio',
		'type' => 'radio',
		'heading' => 'Radio',
		'transport' => 'refresh',
		'value' => '',
		'section' => $section4, //Push to section
		'options' => array(
			'' => 'None',
			1 => 'Hello World',
			2 => 'Hello Php',
			3 => 'Hello WordPress'
		) ) );


	/**
	 * Full Demo
	 */
	new Tpfw_Customize_Section( $wp_customize, array(
		'id' => 'tpfw_section_demo',
		'heading' => esc_attr__( 'TPFW CONTROLS', 'tp-framework' ),
		'fields' => array(
			array(
				'name' => 'autocomplete_3',
				'type' => 'autocomplete',
				'heading' => __( 'Autocomplete', 'tp-framework' ),
				'value' => '',
				'desc' => __( 'Ajax select', 'tp-framework' ),
				'data' => array( 'post_type' => array( 'post' ) ),
				'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
				'min_length' => 3
			),
			array(
				'name' => 'typography',
				'type' => 'typography',
				'heading' => __( 'Typography:', 'tp-framework' ),
				'value' => array(
					'font-family' => 'Roboto',
					'variants' => array( 'regular', 'italic' ),
					'subsets' => array( 'latin-ext' ),
					'font-size' => '14px',
					'line-height' => '1.5em',
					'letter-spacing' => '0',
				),
				'desc' => __( 'A short description for Select box', 'tp-framework' ),
			),
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
			),
			array(
				'name' => 'tpfw_select',
				'type' => 'select',
				'heading' => __( 'Select:', 'tp-framework' ),
				'value' => 'eric',
				'desc' => __( 'A short description for Select box', 'tp-framework' ),
				'transport' => 'postMessage',
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				)
			),
			array(
				'name' => 'tpfw_select_multiple',
				'type' => 'select',
				'heading' => __( 'Select multiple:', 'tp-framework' ),
				'desc' => __( 'A short description for Select Multiple', 'tp-framework' ),
				'multiple' => true,
				'value' => array( 'eric', 'charles' ),
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				),
				'dependency' => array(
					'tpfw_select' => array( 'values' => array( 'eric' ) ),
				)
			),
			array(
				'name' => 'tpfw_checkbox',
				'type' => 'checkbox',
				'heading' => __( 'Checkbox', 'tp-framework' ),
				'value' => 0,
				'dependency' => array(
					'tpfw_select' => array( 'values' => array( 'eric' ) ),
				)
			),
			array(
				'name' => 'tpfw_checkbox_multiple',
				'type' => 'checkbox',
				'multiple' => true,
				'heading' => __( 'Checkbox multiple:', 'tp-framework' ),
				'value' => array(
					'donna', 'charles'
				),
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				),
				'dependency' => array(
					'tpfw_select' => array( 'values' => array( 'eric' ) ),
				)
			),
			array(
				'name' => 'tpfw_checkbox_radio',
				'type' => 'radio',
				'heading' => __( 'Radio multiple:', 'tp-framework' ),
				'value' => 'eric',
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				),
				'dependency' => array(
					'tpfw_select' => array( 'values' => array( 'charles' ) ),
				)
			),
			array(
				'name' => 'tpfw_image_select',
				'type' => 'image_select',
				'inline' => 1, //Set 0 to display image vertical
				'heading' => __( 'Image Inline:', 'tp-framework' ),
				'desc' => __( 'This is a demo for sidebar layout.', 'tp-framework' ),
				'options' => array(
					'left' => TPFW_URL . 'sample/assets/sidebar-left.jpg',
					'none' => TPFW_URL . 'sample/assets/sidebar-none.jpg',
					'right' => TPFW_URL . 'sample/assets/sidebar-right.jpg',
				),
				'value' => 'right'//default
			),
			array(
				'name' => 'tpfw_image_select_vertical',
				'type' => 'image_select',
				'inline' => 0, //Vertical
				'heading' => __( 'Image Vertical:', 'tp-framework' ),
				'desc' => __( 'This is a demo for vertical image options.', 'tp-framework' ),
				'options' => array(
					'opt-1' => TPFW_URL . 'sample/assets/opt-1.jpg',
					'opt-2' => TPFW_URL . 'sample/assets/opt-2.jpg',
					'opt-3' => TPFW_URL . 'sample/assets/opt-3.jpg',
				),
				'value' => 'opt-1'//default
			),
			array(
				'name' => 'tpfw_color',
				'type' => 'color_picker',
				'heading' => __( 'Color Picker:', 'tp-framework' ),
				'value' => '#cccccc',
			),
			array(
				'name' => 'tpfw_icon_picker',
				'type' => 'icon_picker',
				'heading' => __( 'Icon Picker', 'tp-framework' ),
				'value' => ''
			),
			array(
				'name' => 'tpfw_image',
				'type' => 'image_picker',
				'heading' => __( 'Single Image:', 'tp-framework' ),
				'desc' => __( 'A short description for Image Picker', 'tp-framework' )
			),
			array(
				'name' => 'tpfw_cropped_image',
				'type' => 'cropped_image',
				'heading' => __( 'Cropped Image:', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_upload',
				'type' => 'upload',
				'heading' => __( 'Upload Field:', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_map',
				'type' => 'map',
				'heading' => __( 'Google map:', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_link',
				'type' => 'link',
				'heading' => __( 'Enter a link:', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_datetime',
				'type' => 'datetime',
				'heading' => __( 'Datetime:', 'tp-framework' ),
				'options' => array(
				)
			),
		//Update later
		) )
	);

	$repeater_section = new Tpfw_Customize_Section( $wp_customize, array(
		'id' => 'tpfw_repeater_section',
		'heading' => esc_attr__( 'Repeater', 'tp-framework' ),
		'fields' => array(
			array(
				'name' => 'tpfw_repeater',
				'type' => 'repeater',
				'heading' => 'Demo Repeater',
				'fields' => tpfw_example_fields()
			)
		)
			) );
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'tpfw_customize_register', 11 );
