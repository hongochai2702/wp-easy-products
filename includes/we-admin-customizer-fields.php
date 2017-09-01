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
		'title' => __( 'WE PRODUCTS PANEL', 'tp-framework' ),
		'description' => __( 'My Description', 'tp-framework' ),
			) );
			
			
	/**
	 * Use Panel to add single section
	 */
	$panel->add_section( array(
		'id' => 'we_section_catalog_options',
		'heading' => esc_attr__( 'Category Layout', 'tp-framework' ),
		'fields' => array( //Fields in section
			array(
				'name' => 'we_catalog_layout',
				'type' => 'image_select',
				'inline' => 1, //Set 0 to display image vertical
				'heading' => __( 'Bố cục trang danh mục:', 'tp-framework' ),
				'desc' => __( 'This is a demo for sidebar layout.', 'tp-framework' ),
				'options' => array(
					'left' => TPFW_URL . 'sample/assets/sidebar-left.jpg',
					'none' => TPFW_URL . 'sample/assets/sidebar-none.jpg',
					'right' => TPFW_URL . 'sample/assets/sidebar-right.jpg',
				),
				'value' => 'right'//default
			),
			array(
				'name' => 'we_catalog_display_type',
				'type' => 'select',
				'heading' => __( 'Chọn kiểu hiện thị:', 'tp-framework' ),
				'value' => 'pagination',
				'desc' => __( 'A short description for Select box', 'tp-framework' ),
				'transport' => 'postMessage',
				'options' => array(
					'pagination' 		=> __( 'Phân theo trang', 'tp-framework' ),
					'infinite-scroll' 	=> __( 'Cuộn chuột và hiện thị nội dung', 'tp-framework' ),
					'load-more' 		=> __( 'Có nút bấm tải thêm', 'tp-framework' )
				)
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
			'heading' => esc_attr__( 'Product Layout', 'tp-framework' ),
			'fields' => array(
				array(
					'name' => 'we_product_layout',
					'type' => 'image_select',
					'inline' => 1, //Set 0 to display image vertical
					'heading' => __( 'Bố cục trang sản phẩm:', 'tp-framework' ),
					'desc' => __( 'This is a demo for sidebar layout.', 'tp-framework' ),
					'options' => array(
						'left' => TPFW_URL . 'sample/assets/sidebar-left.jpg',
						'none' => TPFW_URL . 'sample/assets/sidebar-none.jpg',
						'right' => TPFW_URL . 'sample/assets/sidebar-right.jpg',
					),
					'value' => 'right'//default
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
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'we_customize_register', 11 );