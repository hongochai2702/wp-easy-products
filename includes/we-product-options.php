<?php

	/*	
	*	WPEASY Product Option File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		CloudFone
	* 	@link		http://CloudFone.vn
	*	---------------------------------------------------------------------
	*	This file create and contains the sermon post_type meta elements
	*	---------------------------------------------------------------------
	*/
	
	add_action( 'init', 'create_wpeasy' );
	function create_wpeasy() {

		$labels = array(
			'name' => __('Products', 'wpeasy'),
			'singular_name' => __('Products', 'wpeasy'),
			'add_new' => __('Add New', 'wpeasy'),
			'add_new_item' => __('Add New Product', 'wpeasy'),
			'edit_item' => __('Edit Product', 'wpeasy'),
			'new_item' => __('New Product', 'wpeasy'),
			'view_item' => __('View Product', 'wpeasy'),
			'search_items' => __('Search Product', 'wpeasy'),
			'not_found' =>  __('Nothing found', 'wpeasy'),
			'not_found_in_trash' => __('Nothing found in Trash', 'wpeasy'),
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-fields'),
		  ); 
		  
		register_post_type( 'product' , $args);
		
	
		
	}
	// hook into the init action and call create_product_taxonomies when it fires
	add_action( 'init', 'create_product_taxonomies');

	// Add new taxonomy, make it hierarchical (like categories)
	function create_product_taxonomies() {

	  $label_singular = 'Categories';
	  $label_plural = 'Categories';

	  $labels = array(
	    'name' => $label_plural,
	    'singular_name' => $label_singular,
	    'menu_name' => $label_plural,
	    'search_items' => __( 'Search '. $label_plural, 'wpeasy'),
	    'all_items' => __( 'All '. $label_plural, 'wpeasy'),
	    'parent_item' => __( 'Parent '. $label_singular, 'wpeasy'),
	    'parent_item_colon' => __( 'Parent '. $label_singular, 'wpeasy'),
	    'edit_item' => __( 'Edit '. $label_plural, 'wpeasy'),
	    'update_item' => __( 'Update '. $label_singular, 'wpeasy'),
	    'add_new_item' => __( 'Add New '. $label_singular, 'wpeasy'),
	    'new_item_name' => __( 'New '. $label_singular, 'wpeasy'),
	  );

	  $args = array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'show_admin_column' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => __('product_cate', 'wpeasy') ),
	  );

	  register_taxonomy( 'product_cate', array( 'product' ), $args );

	}
	// filter for sermon first page
	add_filter("manage_edit-product_columns", "show_product_column");	
	function show_product_column($columns){
		$columns = array(
			"cb" 					=> "<input type=\"checkbox\" />",
			"product-image" 		=> __('Image', 'wpeasy'),
			"title" 				=> __('Title', 'wpeasy'),
			"product-price" 		=> __('Price', 'wpeasy'),
			"product-category" 		=> __('Categories', 'wpeasy'),
			"author" 				=> __('Author', 'wpeasy'),
			"date" 					=> __('Date', 'wpeasy'));
		return $columns;
	}
	add_action("manage_posts_custom_column","product_custom_columns");
	function product_custom_columns($column){
		global $post;

		switch ($column) {
			case "product-image":
			the_post_thumbnail( array(50, 50) , array( 'class' => 'thumbail-wpeasy-product' ) );
			break;
			case "product-category":
			echo get_the_term_list($post->ID, 'product_cate', '', ', ','');
			break;
			case "product-price":
			echo number_format( get_post_meta( $post->ID, 'we_product_price', TRUE ), 0 ) . ' <sup>đ</sup>';
			break;
			
		}
	}
	
	// Register sidebar for product.
	register_sidebar(array(
	    'name' => __( 'WE Left sidebar', 'weasy' ),
	    'id' => 'we-sidebar-archive-left',
	    'description' => __( 'Sidebar description', 'weasy' ),
	    'before_widget' => '<section id="%1$s" class="widget %2$s">',
	    'after_widget' => '</section>',
	    'before_title' => '<h4 class="widget-title">',
	    'after_title' => '</h4>'
	));
	
	register_sidebar(array(
	    'name' => __( 'WE Right sidebar', 'weasy' ),
	    'id' => 'we-sidebar-archive-right',
	    'description' => __( 'Sidebar description', 'weasy' ),
	    'before_widget' => '<section id="%1$s" class="widget %2$s">',
	    'after_widget' => '</section>',
	    'before_title' => '<h4 class="widget-title">',
	    'after_title' => '</h4>'
	));
	
	// add image size.
	add_image_size('weasy_image_size_thumb', 400, 400, false);

	// Add product admin submenu.
	add_action('admin_menu', 'we_product_customize_page');
	function we_product_customize_page() {
	    add_submenu_page(
	        'edit.php?post_type=product',
	        __( 'WE Products Customize', 'weasy' ),
	        __( 'WE Products Customize', 'weasy' ),
	        'manage_options',
	        'customize.php'
	    );
	}
	