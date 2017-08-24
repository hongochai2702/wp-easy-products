<?php

/**
 * Sample Post, Page Metabox
 *
 * @package     Tpfw
 * @category    Sample
 * @author      ThemesPond
 * @license     GPLv2 or later
 */

/**
 * Global fields
 * @return array
 */
function tpfw_example_fields() {
	return array(
		array(
			'name' => 'tpfw_checkbox',
			'type' => 'checkbox',
			'heading' => __( 'Checkbox:', 'tp-framework' ),
			'value' => 0,
			'desc' => __( 'A short description for single Checkbox', 'tp-framework' )
		),
		array(
			'name' => 'autocomplete_2',
			'type' => 'autocomplete',
			'heading' => __( 'Autocomplete', 'tp-framework' ),
			'value' => '',
			'desc' => __( 'Ajax select', 'tp-framework' ),
			'data' => array( 'post_type' => array( 'post' ) ),
			'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
			'min_length' => 3
		),
		array(
			'name' => 'tpfw_select',
			'type' => 'select',
			'heading' => __( 'Select:', 'tp-framework' ),
			'value' => 'eric',
			'desc' => __( 'A short description for Select box', 'tp-framework' ),
			'options' => array(
				'' => __( 'Select...', 'tp-framework' ),
				'donna' => __( 'Show Text field', 'tp-framework' ),
				'eric' => __( 'Show Textarea', 'tp-framework' ),
				'charles' => __( 'Charles Wheeler', 'tp-framework' ),
				'anthony' => __( 'Anthony Perkins', 'tp-framework' )
			)
		),
		array(
			'name' => 'tpfw_textfield',
			'type' => 'textfield',
			'heading' => __( 'Text field:', 'tp-framework' ),
			'value' => 'A default text',
			'desc' => __( 'A short description for Text Field', 'tp-framework' ),
			'show_label' => true, //Work on repeater field
			'dependency' => array(
				'tpfw_select' => array( 'values' => 'donna' )
			)
		),
		array(
			'name' => 'tpfw_textarea',
			'type' => 'textarea',
			'heading' => __( 'Text Area:', 'tp-framework' ),
			'value' => 'A default text',
			'desc' => __( 'A short description for Text Area', 'tp-framework' ),
			'dependency' => array(
				'tpfw_select' => array( 'values' => 'eric' )
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
			'show_label' => true//Work on repeater field
		),
		array(
			'name' => 'tpfw_checkbox_multiple',
			'type' => 'checkbox',
			'multiple' => true,
			'heading' => __( 'Checkbox multiple:', 'tp-framework' ),
			'value' => array(
				'donna', 'charles'
			),
			'inline' => 0,
			'options' => array(
				'donna' => __( 'Donna Delgado', 'tp-framework' ),
				'eric' => __( 'Eric Austin', 'tp-framework' ),
				'charles' => __( 'Charles Wheeler', 'tp-framework' ),
				'anthony' => __( 'Anthony Perkins', 'tp-framework' )
			),
			'desc' => __( 'A short description for Checkbox multiple', 'tp-framework' )
		),
		array(
			'name' => 'tpfw_checkbox_radio',
			'type' => 'radio',
			'heading' => __( 'Radio multiple:', 'tp-framework' ),
			'inline' => 1,
			'value' => 'eric',
			'options' => array(
				'donna' => __( 'Donna Delgado', 'tp-framework' ),
				'eric' => __( 'Eric Austin', 'tp-framework' ),
				'charles' => __( 'Charles Wheeler', 'tp-framework' ),
				'anthony' => __( 'Anthony Perkins', 'tp-framework' )
			),
			'description' => __( 'Checkbox multiple description', 'tp-framework' ),
			'show_label' => true//Work on repeater field
		),
		array(
			'name' => 'tpfw_color',
			'type' => 'color_picker',
			'heading' => __( 'Color:', 'tp-framework' ),
			'value' => '#cccccc',
			'desc' => __( 'A short description for Color Picker', 'tp-framework' )
		),
		array(
			'name' => 'tpfw_image',
			'type' => 'image_picker',
			'multiple' => false,
			'heading' => __( 'Single Image:', 'tp-framework' ),
			'desc' => __( 'A short description for Image Picker', 'tp-framework' )
		),
		array(
			'name' => 'tpfw_multiple_image',
			'type' => 'image_picker',
			'multiple' => true,
			'heading' => __( 'Multi Image:', 'tp-framework' ),
			'desc' => __( 'A short description for Image Picker with multiple is true', 'tp-framework' )
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
			'name' => 'tpfw_icon_picker',
			'type' => 'icon_picker',
			'heading' => __( 'Icon Picker', 'tp-framework' ),
			'desc' => __( 'A short description', 'tp-framework' ),
		),
		array(
			'name' => 'tpfw_link',
			'type' => 'link',
			'heading' => __( 'Custom Link', 'tp-framework' ),
			'desc' => __( 'We have a custom Link very friendly and easy to use.', 'tp-framework' ),
			'value' => ''//default
		),
		array(
			'name' => 'tpfw_datetime',
			'type' => 'datetime',
			'heading' => __( 'Datetime', 'tp-framework' ),
			'desc' => __( 'A cool datetime.', 'tp-framework' ),
			'value' => ''//default
		),
		array(
			'name' => 'tpfw_map',
			'type' => 'map',
			'heading' => __( 'Search map location', 'tp-framework' ),
			'desc' => __( 'Drag the pin to manually set listing coordinates. Now very easy to save a latlng and zoom settings from user. ', 'tp-framework' ),
			'value' => ''//default
		),
	);
}

/**
 * Post Metabox
 */
function tpfw_example_metabox() {

	$fields = tpfw_example_fields();

	$repeater = array(
		'name' => 'tpfw_repeater',
		'type' => 'repeater',
		'heading' => __( 'Repeater', 'tp-framework' ),
		'value' => '',
		'desc' => '',
		'fields' => $fields
	);
	$box2 = new Tpfw_Metabox( array(
		'id' => 'tpfw_metabox2',
		'screens' => array( 'post' ), //Display in post, page, front_page, posts_page
		'heading' => __( 'Metabox 2', 'tp-framework' ),
		'context' => 'advanced', //side
		'priority' => 'low',
		'manage_box' => false,
		'fields' => array(
			//Group Default
			array(
				'name' => 'autocomplete1',
				'type' => 'autocomplete',
				'heading' => __( 'Autocomplete', 'tp-framework' ),
				'value' => '',
				'desc' => __( 'Ajax select', 'tp-framework' ),
				'data' => array( 'taxonomy' => array( 'category', 'product_cate' ) ),
				'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
				'min_length' => 3
			),
		)
	));
	$box1 = new Tpfw_Metabox( array(
		'id' => 'tpfw_metabox',
		'screens' => array( 'page' ), //Display in post, page, front_page, posts_page
		'heading' => __( 'Metabox', 'tp-framework' ),
		'context' => 'advanced', //side
		'priority' => 'low',
		'manage_box' => true,
		'fields' => array(
			//Group Default
			array(
				'name' => 'autocomplete',
				'type' => 'autocomplete',
				'heading' => __( 'Autocomplete', 'tp-framework' ),
				'value' => '',
				'desc' => __( 'Ajax select', 'tp-framework' ),
				'data' => array( 'taxonomy' => array( 'category', 'post_tag' ) ),
				'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
				'min_length' => 3
			),
			array(
				'name' => 'tpfw_select_g',
				'type' => 'select',
				'heading' => __( 'Select:', 'tp-framework' ),
				'value' => 'eric',
				'desc' => __( 'A short description for Select box', 'tp-framework' ),
				'options' => array(
					'' => __( 'Select ...', 'tp-framework' ),
					'donna' => __( 'Show Text field', 'tp-framework' ),
					'eric' => __( 'Show Text area', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				)
			),
			array(
				'name' => 'tpfw_textfield_g',
				'type' => 'textfield',
				'heading' => __( 'Text field:', 'tp-framework' ),
				'value' => 'A default text',
				'desc' => __( 'A short description for Text Field', 'tp-framework' ),
				'show_label' => true, //Work on repeater field
				'dependency' => array(
					'tpfw_select_g' => array( 'values' => array( 'donna' ) )
				)
			),
			array(
				'name' => 'tpfw_textarea_g',
				'type' => 'textarea',
				'heading' => __( 'Text Area:', 'tp-framework' ),
				'value' => 'A default text',
				'desc' => __( 'A short description for Text Area', 'tp-framework' ),
				'dependency' => array(
					'tpfw_select_g' => array( 'values' => array( 'eric' ) )
				)
			),
			array(
				'name' => 'tpfw_select_multiple_g',
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
				'show_label' => true//Work on repeater field
			),
			array(
				'name' => 'tpfw_checkbox_g',
				'type' => 'checkbox',
				'heading' => __( 'Checkbox:', 'tp-framework' ),
				'value' => 0,
				'desc' => __( 'A short description for single Checkbox', 'tp-framework' ),
				'dependency' => array(
					'tpfw_select_multiple_g' => array(
						'values' => array( 'charles' )
					),
				)
			),
			array(
				'name' => 'tpfw_checkbox_multiple_g',
				'type' => 'checkbox',
				'multiple' => true,
				'heading' => __( 'Checkbox multiple:', 'tp-framework' ),
				'value' => array(
					'donna', 'charles'
				),
				'inline' => 0,
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'charles' => __( 'Charles Wheeler', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				),
				'desc' => __( 'A short description for Checkbox multiple', 'tp-framework' ),
				'dependency' => array(
					'tpfw_checkbox_g' => array( 'checked' => true )
				)
			),
			array(
				'name' => 'tpfw_checkbox_radio_g',
				'type' => 'radio',
				'heading' => __( 'Radio multiple:', 'tp-framework' ),
				'inline' => 1,
				'value' => 'eric',
				'options' => array(
					'donna' => __( 'Donna Delgado', 'tp-framework' ),
					'eric' => __( 'Eric Austin', 'tp-framework' ),
					'color' => __( 'Show Color picker', 'tp-framework' ),
					'anthony' => __( 'Anthony Perkins', 'tp-framework' )
				),
				'description' => __( 'Checkbox multiple description', 'tp-framework' ),
				'show_label' => true, //Work on repeater field
			),
			array(
				'name' => 'tpfw_color_g',
				'type' => 'color_picker',
				'heading' => __( 'Color:', 'tp-framework' ),
				'value' => '#cccccc',
				'desc' => __( 'A short description for Color Picker', 'tp-framework' ),
				'dependency' => array(
					'tpfw_checkbox_radio_g' => array(
						'values' => 'color'
					),
					'tpfw_checkbox_multiple_g' => array(
						'values' => 'charles,anthony'
					)
				)
			),
			array(
				'name' => 'tpfw_image_g',
				'type' => 'image_picker',
				'multiple' => false,
				'heading' => __( 'Single Image:', 'tp-framework' ),
				'desc' => __( 'A short description for Image Picker', 'tp-framework' )
			),
			array(
				'name' => 'tpfw_multiple_image_g',
				'type' => 'image_picker',
				'multiple' => true,
				'heading' => __( 'Multi Image:', 'tp-framework' ),
				'desc' => __( 'A short description for Image Picker with multiple is true', 'tp-framework' )
			),
			array(
				'name' => 'tpfw_image_select_g',
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
				'name' => 'tpfw_image_select_vertical_g',
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
				'name' => 'tpfw_icon_picker_g',
				'type' => 'icon_picker',
				'heading' => __( 'Icon Picker', 'tp-framework' ),
				'desc' => __( 'A short description', 'tp-framework' ),
			),
			array(
				'name' => 'tpfw_link_g',
				'type' => 'link',
				'heading' => __( 'Custom Link', 'tp-framework' ),
				'desc' => __( 'We have a custom Link very friendly and easy to use.', 'tp-framework' ),
				'value' => ''//default
			),
			array(
				'name' => 'tpfw_datetime_g',
				'type' => 'datetime',
				'heading' => __( 'Datetime', 'tp-framework' ),
				'desc' => __( 'A cool datetime.', 'tp-framework' ),
				'value' => ''//default
			),
			array(
				'name' => 'tpfw_map_g',
				'type' => 'map',
				'heading' => __( 'Search map location', 'tp-framework' ),
				'desc' => __( 'Drag the pin to manually set listing coordinates. Now very easy to save a latlng and zoom settings from user. ', 'tp-framework' ),
				'value' => ''//default
			),

			//Group
			array(
				'type' => 'repeater',
				'name' => 'test_ground',
				'heading' => __( 'TEST THOI', 'tp-framework' ),
				'group' => 'TEST GROUP',
				'fields' => $fields
			),

			//Group
			array(
				'type' => 'repeater',
				'name' => 'repeater_group',
				'heading' => __( 'Repeater', 'tp-framework' ),
				'group' => 'Repeater',
				'fields' => $fields
			),
			array(
				'type' => 'repeater',
				'name' => 'repeater_group_2',
				'heading' => __( 'Repeater 2', 'tp-framework' ),
				'group' => 'Repeater',
				'fields' => array(
					array(
						'name' => 'tpfw_select_g',
						'type' => 'select',
						'heading' => __( 'Select:', 'tp-framework' ),
						'value' => 'eric',
						'desc' => __( 'A short description for Select box', 'tp-framework' ),
						'options' => array(
							'' => __( 'Select ...','tp-framework' ),
							'donna' => __( 'Show Text field', 'tp-framework' ),
							'eric' => __( 'Show Text area', 'tp-framework' ),
							'charles' => __( 'Charles Wheeler', 'tp-framework' ),
							'anthony' => __( 'Anthony Perkins', 'tp-framework' )
						)
					),
					array(
						'name' => 'tpfw_textfield_g',
						'type' => 'textfield',
						'heading' => __( 'Text field:', 'tp-framework' ),
						'value' => 'A default text',
						'desc' => __( 'A short description for Text Field', 'tp-framework' ),
						'show_label' => true, //Work on repeater field
						'dependency' => array(
							'tpfw_select_g' => array( 'values' => array( 'donna' ) )
						)
					),
					array(
						'name' => 'tpfw_textarea_g',
						'type' => 'textarea',
						'heading' => __( 'Text Area:', 'tp-framework' ),
						'value' => 'A default text',
						'desc' => __( 'A short description for Text Area', 'tp-framework' ),
						'dependency' => array(
							'tpfw_select_g' => array( 'values' => array( 'eric' ) )
						)
					),
					array(
						'name' => 'tpfw_select_multiple_g',
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
						'show_label' => true//Work on repeater field
					),
					array(
						'name' => 'tpfw_image_g',
						'type' => 'image_picker',
						'multiple' => false,
						'heading' => __( 'Single Image:', 'tp-framework' ),
						'desc' => __( 'A short description for Image Picker', 'tp-framework' )
					),
					array(
						'name' => 'tpfw_multiple_image_g',
						'type' => 'image_picker',
						'multiple' => true,
						'heading' => __( 'Multi Image:', 'tp-framework' ),
						'desc' => __( 'A short description for Image Picker with multiple is true', 'tp-framework' )
					),
				)
			)
		)
			) );
}

add_action( 'tpfw_metabox_init', 'tpfw_example_metabox' );

