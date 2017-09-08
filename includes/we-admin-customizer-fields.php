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
	 * Init a Panel
	 */
	$panel = new Tpfw_Customize_Panel( $wp_customize, array(
		'id' => 'tpfw_panel',
		'title' => __( 'WE PRODUCTS PANEL', 'wpeasy' ),
		'description' => __( 'My Description', 'wpeasy' ),
			) );
			
			
	/**
	 * Use Panel to add single section
	 */
	$panel->add_section( array(
		'id' => 'we_section_catalog_options',
		'heading' => esc_attr__( 'Category Layout', 'wpeasy' ),
		'fields' => array( //Fields in section
		    array(
		        'name' => 'we_catalog_layout',
		        'type' => 'image_select',
		        'inline' => 1, //Set 0 to display image vertical
		        'transport' => 'refresh',
		        'heading' => __( 'Layout catalog:', 'wpeasy' ),
		        'desc' => __( 'This is a demo for sidebar layout.', 'wpeasy' ),
		        'options' => array(
		            'list' => TPFW_URL . 'sample/assets/opt-1.jpg',
		            'grid' => TPFW_URL . 'sample/assets/opt-2.jpg'
		        ),
		        'value' => 'grid'//default
		    ),
			array(
				'name' => 'we_catalog_sidebar_layout',
				'type' => 'image_select',
				'inline' => 1, //Set 0 to display image vertical
			    'transport' => 'postMessage',
				'heading' => __( 'Layout sidebar:', 'wpeasy' ),
				'desc' => __( 'This is a demo for sidebar layout.', 'wpeasy' ),
				'options' => array(
					'left' => TPFW_URL . 'sample/assets/sidebar-left.jpg',
					'none' => TPFW_URL . 'sample/assets/sidebar-none.jpg',
					'right' => TPFW_URL . 'sample/assets/sidebar-right.jpg',
				),
				'value' => 'right'//default
			),
			array(
				'name' => 'we_catalog_paging_display_type',
				'type' => 'select',
				'heading' => __( 'Display pagination type:', 'wpeasy' ),
				'value' => 'pagination',
				'desc' => __( 'A short description for Select box', 'wpeasy' ),
				'transport' => 'postMessage',
				'options' => array(
					'pagination' 		=> __( 'Pagination', 'wpeasy' ),
					'infinite-scroll' 	=> __( 'Infinite Scroll', 'wpeasy' ),
					'load-more' 		=> __( 'Load more button', 'wpeasy' )
				)
			),
		    array(
		        'name' => 'we_catalog_display_column',
		        'type' => 'select',
		        'heading' => __( 'Display column:', 'wpeasy' ),
		        'value' => '4',
		        'desc' => __( 'A short description for Select box', 'wpeasy' ),
		        'transport' => 'postMessage',
		        'options' => array(
		            '2'   => __( '2 column', 'wpeasy' ),
		            '3'   => __( '3 column', 'wpeasy' ),
		            '4'   => __( '4 column', 'wpeasy' ),
		            '6'   => __( '6 column', 'wpeasy' )
		        ),
		        'dependency' => array(
		            'we_catalog_layout' => array( 'values' => array( 'grid' ) ),
		        )
		    ),
		    array(
		        'name' => 'we_catalog_display_number',
		        'type' => 'text',
		        'transport' => 'postMessage',
		        'heading' => __( 'Display number:', 'wpeasy' ),
		        'value' => '6',
		        'desc' => __( 'A short description for Select box', 'wpeasy' ),
		        'transport' => 'postMessage'
		    ),
		    array(
		        'name'        => 'we_catalog_product_order',
		        'type'        => 'select',
		        'value'       => '{"orderby":"date","order":"desc"}',
		        'transport'   => 'postMessage',
		        'heading'     => __( 'Sort Order:', 'wpeasy' ),
		        'desc'        => __( 'A short description for Select box', 'wpeasy' ),
		        'transport'   => 'postMessage',
		        'options' => array(
		            '{"orderby":"date","order":"desc"}'       => __( 'Từ mới tới củ', 'wpeasy' ),
		            '{"orderby":"date","order":"asc"}'        => __( 'Từ củ tới mới', 'wpeasy' ),
		            '{"orderby":"rand","order":"desc"}'       => __( 'Sắp xếp ngẫu nhiên', 'wpeasy' ),
		            '{"orderby":"title","order":"asc"}'       => __( 'Theo tiêu đề từ A-Z', 'wpeasy' ),
		            '{"orderby":"menu_order","order":"desc"}' => __( 'Tùy chỉnh thứ tự', 'wpeasy' )
		        ),
		    ),
		)
	) );

	/**
	 * Use Panel to add a list of sections
	 */
	$panel->add_sections( array(
		//Section 2
		array(
			'id' => 'we_section_product_options',
			'heading' => esc_attr__( 'Product Layout', 'wpeasy' ),
			'fields' => array(
			    array(
			        'name' => 'we_product_layout',
			        'type' => 'image_select',
			        'inline' => 1, //Set 0 to display image vertical
			        'transport' => 'postMessage',
			        'heading' => __( 'Layout product:', 'wpeasy' ),
			        'desc' => __( 'This is a demo for sidebar layout.', 'wpeasy' ),
			        'options' => array(
			            'default'            => WPEASY_ASSETS_URL . '/images/products-1.svg',
			            'left-thumb-image'   => WPEASY_ASSETS_URL . '/images/products-1.svg'
			        ),
			        'value' => 'default'//default
			    ),
			    array(
			        'name' => 'we_product_layout_sidebar',
			        'type' => 'image_select',
			        'inline' => 1, //Set 0 to display image vertical
			        'transport' => 'postMessage',
			        'heading' => __( 'Layout sidebar:', 'wpeasy' ),
			        'desc' => __( 'This is a demo for sidebar layout.', 'wpeasy' ),
			        'options' => array(
			            'left' => TPFW_URL . 'sample/assets/sidebar-left.jpg',
			            'none' => TPFW_URL . 'sample/assets/sidebar-none.jpg',
			            'right' => TPFW_URL . 'sample/assets/sidebar-right.jpg'
			        ),
			        'value' => 'left'//default
			    ),
			    
			)
		),
		//Section 3
		array(
			'id' => 'we_section_product_settings',
			'heading' => esc_attr__( 'Product Setting', 'wpeasy' ),
		    'fields'=> array(
		        array(
		            'name' => 'we_setting_display_options',
		            'type' => 'checkbox',
		            'heading' => __( 'Options Display', 'tp-framework' ),
		            'desc' => __( 'This is a demo for sidebar layout.', 'wpeasy' ),
		            'multiple' => 1,
		            'value' => array( 'price','short_description','variable','product_cate' ),
		            'options' => array(
		                'price'               => __( 'Display price', 'tp-framework' ),
		                'short_description'   => __( 'Display short description ', 'tp-framework' ),
		                'variable'            => __( 'Display variable', 'tp-framework' ),
		                'product_cate'        => __( 'Display Product Category', 'tp-framework' )
		            )
		        ),
		    )
		)
	) );
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'we_customize_register', 11 );