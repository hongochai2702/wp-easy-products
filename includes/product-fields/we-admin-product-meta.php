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
				'show_label' => true, //Work on repeater field
				'heading' => __( 'Name:', 'wpeasy' ),
				'placeholder' => __( 'Enter 3 or more characters to search...', 'wpeasy' ),
				'value' => "",
				'desc' => __( 'A short description for single Checkbox', 'wpeasy' )
			),
			array(
				'name' => 'we_product_atts_excrept',
				'show_label' => true, //Work on repeater field
				'type' => 'textarea',
				'heading' => __( 'Excrept', 'wpeasy' ),
				'desc' => __( 'A short description for single Checkbox', 'wpeasy' )
			)
		);
	}

	/**
	 * Post Metabox
	 */
	function we_product_metabox_data() {

		$fields = we_product_field_tab_attributes();

		$we_metabox_product_data = new Tpfw_Metabox( 
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
						'desc' => __( 'Regular price (₫)', 'wpeasy' ),
						'show_label' => true, //Work on repeater field
						'group' => 'General'
					),
					array(
						'name' => 'we_product_price_special',
						'type' => 'textfield',
						'heading' => __( 'Regular special (₫)', 'wpeasy' ),
						'value' => '',
						'desc' => __( 'Regular special (₫)', 'wpeasy' ),
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
						'desc' => __( 'Up-sells select', 'wpeasy' ),
						'data' => array( 'post_type' => array( 'product' ) ),
						'placeholder' => __( 'Enter 3 or more characters to search...', 'wpeasy' ),
						'min_length' => 3,
						'group' => 'Linked Products'
					),
					//Group Attributes
					array(
						'name' 		=> 'we_product_attributes',
						'type' 		=> 'repeater',
						'heading' 	=> __( 'Attributes', 'wpeasy' ),
						'desc' 		=> 'Attributes',
						'group' 	=> 'Attributes',
						'fields' 	=> $fields
					),
				)
		));

		$we_metabox_gallery_image = new Tpfw_Metabox(
			array(
				'id' => 'we_product_section_galleries',
				'screens' => array( 'product' ), //Display in product
				'heading' => __( 'Gallery Images', 'wpeasy' ),
				'context' => 'side', //side
				'priority' => 'low',
				'manage_box' => false,
				'fields' => array(
					array(
						'name' => 'we_product_gallery_thumb',
						'type' => 'image_picker',
						'multiple' => true,
						'heading' => __( 'Gallery images product:', 'weasy' ),
						'desc' => __( 'A short description for Image Picker with multiple is true', 'weasy' )
					),
				)
			));
	}
	add_action( 'tpfw_metabox_init', 'we_product_metabox_data' );

