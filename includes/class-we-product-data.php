<?php
class WE_Product_Data {
	
	public function __construct() {
		//echo "<br />" . __METHOD__;
		
		add_action( 'wp_ajax_we_get_products', array( $this, 'we_get_products' ) );
		add_action( 'wp_ajax_nopriv_we_get_products', array( $this, 'we_get_products' ) );
		
		add_action( 'wp_ajax_we_get_products_paging', array( $this, 'we_get_products_paging' ) );
		add_action( 'wp_ajax_nopriv_we_get_products_paging', array( $this, 'we_get_products_paging' ) );
	}
	
	public function we_get_products_paging() {
		// Global variable.
		global $weCustomizerOptions;
		$weSetting 		= $weCustomizerOptions->getSettings();
		$orderProducts 	= json_decode($weSetting['we_catalog_product_order'] , TRUE); // Order +  Order by.
		$per_pages = ( !empty($_GET['per_pages']) ) ? sanitize_text_field($_GET['per_pages']): 6;
		$paged = ( !empty($_GET['paged']) ) ? sanitize_text_field($_GET['paged']): 1;
		
		$posts = get_posts(array(
				'posts_per_page' 	=> $per_pages,
				'post_type' 		=> 'product',
				'paged'				=> $paged,
				'post_status' 		=> 'publish',
				'suppress_filters'	=> true,
				'orderby' 			=> $orderProducts['orderby'],
    			'order'   			=> $orderProducts['order']
			));
		$result = array();
		
		require_once WPEASY_VIEWS_LOCAL . '/product-paging.php';
		die();
		
	}
	
	
	public function we_get_products() {
		
		$per_pages = ( !empty($_GET['per_pages']) ) ? sanitize_text_field($_GET['per_pages']): 6;
		
		$posts = get_posts(array(
				'posts_per_page' => $per_pages,
				'post_type' => 'product',
				'post_status' => 'publish',
				'suppress_filters'	=> true
			));

		$result = array();
		
		foreach ( $posts as $post ) {
			$result[] = array(
				'ID' 	=> $post->ID,
				'Name' 	=> $post->post_title,
				'Link' 	=> get_the_permalink($post->ID),
				'Image'	=> $this->get_the_post_thumbnail_url($post->ID)
			);
		}
	
		wp_send_json( $result );
		
	}
	
	public function get_the_post_thumbnail_url( $post = null, $size = 'post-thumbnail' ) {
	    $post_thumbnail_id = get_post_thumbnail_id( $post );
	    if ( ! $post_thumbnail_id ) {
	        return false;
	    }
	    return $this->wp_get_attachment_image_url( $post_thumbnail_id, $size );
	}
	
	public function wp_get_attachment_image_url( $attachment_id, $size = 'thumbnail', $icon = false ) {
	    $image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	    return isset( $image['0'] ) ? $image['0'] : false;
	}
}