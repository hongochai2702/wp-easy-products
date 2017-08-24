<?php

/**
 * Single Product Metabox
 *
 * @package     Tpfw
 * @category    Product
 * @author      Harry Ho
 * @license     GPLv2 or later
 */

	/**
	 * Product fields
	 * @return array
	 */

	function we_product_field_tab_general() {
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
			)
		);
	}

	/**
	 * Post Metabox
	 */
	function we_product_tab_general() {

		$fields = we_product_field_tab_general();

		$we_metabox = new Tpfw_Metabox( 
			array(
				'id' => 'we_product_tab_general',
				'screens' => array( 'product' ), //Display in product
				'heading' => __( 'Product Data', 'wpeasy' ),
				'context' => 'advanced', //side
				'priority' => 'low',
				'manage_box' => false,
				'fields' => array(
					//Group General
					array(
						'name' => 'we_product_price',
						'type' => 'textfield',
						'heading' => __( 'Regular price (₫)', 'wpeasy' ),
						'value' => '',
						'desc' => __( 'Regular price (₫)', 'tp-framework' ),
						'show_label' => true, //Work on repeater field
						'group' => 'General'
					),

					//Group Linked Products
					array(
						'name' => 'we_product_linked_products',
						'type' => 'autocomplete',
						'multiple' => true,
						'heading' => __( 'Up-sells', 'wpeasy' ),
						'value' => '',
						'desc' => __( 'Ajax select', 'tp-framework' ),
						'data' => array( 'post_type' => 'product, post' ),
						'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
						'min_length' => 3,
						'group' => 'Linked Products'
					),
					//Group Attributes
					array(
						'name' 		=> 'we_product_attributes',
						'type' 		=> 'repeater',
						'heading' 	=> __( 'Attributes', 'tp-framework' ),
						'value' 	=> '',
						'desc' 		=> '',
						'group' 	=> 'Attributes',
						'fields' 	=> $fields
					),
				)
		));
	}


add_action( 'tpfw_metabox_init', 'we_product_tab_general' );