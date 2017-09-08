<?php
	/*
	 * Include script
	 * */
	add_action( 'wp_enqueue_scripts', 'wpeasy_enqueue_scripts' );
	if( !function_exists('wpeasy_enqueue_scripts') ){
		function wpeasy_enqueue_scripts(){
			wp_register_style('wpeasy-front', WPEASY_ASSETS_URL . '/css/wpeasy-product-front.css');
			wp_register_script('wpeasy-main', WPEASY_ASSETS_URL . '/js/main.js');
			wp_register_script('wpeasy-layouts', WPEASY_ASSETS_URL . '/js/layouts.js');
			wp_register_script('wpeasy-lazyload', WPEASY_ASSETS_URL . '/js/lazyload.min.js');
			
			wp_register_script('wpeasy-angular-core', WPEASY_ASSETS_URL . '/js/angular/angular.min.js');
			wp_register_script('wpeasy-underscore', WPEASY_ASSETS_URL . '/js/underscore-min.js');
			//wp_register_script('wpeasy-isotope', WPEASY_ASSETS_URL . '/js/isotope.pkgd.min.js');
			
			//wp_enqueue_script('wpeasy-underscore');
			//wp_enqueue_script('wpeasy-angular-core');
			//wp_enqueue_script('wpeasy-isotope');
			wp_enqueue_script('wpeasy-lazyload');
			
			if ( is_tax( 'product_cate' ) ) {
				wp_enqueue_script('wpeasy-layouts');	
				//wp_enqueue_script('wpeasy-main');
			}
			
			if ( is_singular('product') || is_tax( 'product_cate' )  ) {
				wp_enqueue_style('wpeasy-front');
			}
			
		}
	}
	
	/*
	 * Include admin script
	 * */
	add_action( 'admin_enqueue_scripts', 'wpeasy_admin_enqueue_scripts' );
	if( !function_exists('wpeasy_admin_enqueue_scripts') ){
	    function wpeasy_admin_enqueue_scripts() {
	        global $post_type;
	        if( 'product' == $post_type || is_customize_preview() ) {
	            wp_enqueue_style('wpeasy-admin', WPEASY_ASSETS_URL . '/css/wpeasy-product-admin.css');
	        }
	    }
	}
