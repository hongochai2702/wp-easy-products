s<?php

	/*	
	*	Tourmaster Plugin
	*	---------------------------------------------------------------------
	*	choosing template
	*	---------------------------------------------------------------------
	*/

	add_filter('pre_get_posts', 'modify_pre_query_request');
	function modify_pre_query_request($query){
	    if ($query->is_main_query()){
	        if ($query->is_tax){
	            $post_type = get_query_var('post_type');
	            if (!$post_type){
	                $post_type = array( 'post', 'wpeasy-product' );
	                $query->set('post_type', $post_type);
	            }
	        }
	    }
	}


	add_filter( 'template_include', 'wpeasy_template_registration', 9999 );
	if ( !function_exists('wpeasy_template_registration') ) {
		function wpeasy_template_registration( $template ){
			global $wpeasy_template;

			$wpeasy_template = false;

			// archive template.
			if ( is_tax( 'product_category' ) || is_post_type_archive( 'wpeasy-product' ) ) {
				
				$wpeasy_template = 'archive';
				$template = WPEASY_SINGLE_LOCAL . '/archive-wpeasy-product.php';
			} else if ( isset( $_GET['product-search'] ) ) {
				$wpeasy_template = 'search';
				$template = WPEASY_SINGLE_LOCAL . '/archive-wpeasy-product.php';
			}else {
				
			}
			
			return $template;
		} // wpeasy_template_registration
	} // function_exists


	// apply single template filter
	add_filter('single_template', 'wpeasy_tour_template', 11);
	if( !function_exists('wpeasy_tour_template') ){
		function wpeasy_tour_template( $template ){

			if( get_post_type() == 'wpeasy-product' ){
				$template = WPEASY_SINGLE_LOCAL . '/single-wpeasy-product.php';
			}

			return $template;
		}
	}
