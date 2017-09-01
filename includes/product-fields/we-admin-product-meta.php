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

	function we_product_field_tab_attributes() {
		return array(
			array(
				'name' => 'we_product_atts_title',
				'type' => 'textfield',
				'heading' => __( 'Name:', 'tp-framework' ),
				'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
				'value' => "",
				'desc' => __( 'A short description for single Checkbox', 'tp-framework' )
			),
			array(
				'name' => 'we_product_atts_excrept',
				'type' => 'textarea',
				'heading' => __( 'Excrept', 'tp-framework' ),
				'desc' => __( 'A short description for single Checkbox', 'tp-framework' )
			)
		);
	}

	/**
	 * Post Metabox
	 */
	function we_product_tab_general() {

		$fields = we_product_field_tab_attributes();

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
						'data' => array( 'post_type' => array( 'product' ) ),
						'placeholder' => __( 'Enter 3 or more characters to search...', 'tp-framework' ),
						'min_length' => 3,
						'group' => 'Linked Products'
					),
					//Group Attributes
					array(
						'name' 		=> 'we_product_attributes',
						'type' 		=> 'repeater',
						'heading' 	=> __( 'Attributes', 'tp-framework' ),
						'desc' 		=> 'Attributes',
						'group' 	=> 'Attributes',
						'fields' 	=> $fields
					),
				)
		));
	}


add_action( 'tpfw_metabox_init', 'we_product_tab_general' );