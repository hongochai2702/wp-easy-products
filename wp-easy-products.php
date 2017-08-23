<?php
/*
Plugin Name: WP Easy Products
Plugin URI: http://chili.vn
Description: Creates a Demo post type, Taxonomy, Settings Page, User fields, Dashbaord widget, Help tabs and Widget, with Field Examples.
Version: 1.0.0
Author: Harry Ho
Author URI: http://hongochai.xyz
Text Domain: wpeasy
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

// define necessary variable for the site.
define('WPEASY_URL', plugins_url('', __FILE__));
define('WPEASY_LOCAL', plugin_dir_path( __FILE__ ));

define('WPEASY_ASSETS_URL', WPEASY_URL . '/assets');
define('WPEASY_INCLUDE_LOCAL', WPEASY_LOCAL . '/includes');
define('WPEASY_TEMPLATES_LOCAL', WPEASY_LOCAL . '/templates');
define('WPEASY_SHORTCODE_LOCAL', WPEASY_LOCAL . '/shortcode');
define('WPEASY_ASSEST_LOCAL', WPEASY_LOCAL . '/assests');
define('WPEASY_SINGLE_LOCAL', WPEASY_LOCAL . '/single');

// add activation hook
register_activation_hook(__FILE__, 'wpeasy_plugin_activation');
register_deactivation_hook(__FILE__, 'wpeasy_plugin_deactivation');

// load text domain for localization
add_action('plugins_loaded', 'wpeasy_load_textdomain');
if( !function_exists('wpeasy_load_textdomain') ){
	function wpeasy_load_textdomain() {
	  load_plugin_textdomain('wpeasy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages'); 
	}
}

// Load Class.
require_once( WPEASY_INCLUDE_LOCAL . '/init.php' );
//require_once( WPEASY_SHORTCODE_LOCAL. '/shortcode-generator.php' );

add_action( 'wp_enqueue_scripts', 'wpeasy_enqueue_script' );
if( !function_exists('wpeasy_enqueue_script') ){
	function wpeasy_enqueue_script(){
		if ( is_singular('product') || is_tax( 'product_cate' )  ) {
			wp_enqueue_style('wpeasy-style', WPEASY_ASSETS_URL . '/css/front.css');
		}
        
	}
}

// add WPEASY to body class
add_filter('body_class', 'wpeasy_body_class');
if( !function_exists('wpeasy_body_class') ){
	function wpeasy_body_class( $classes ){
		$classes[] = 'wpeasy-body';
		return $classes;
	}
}

add_image_size( 'wpeasy-image-catalog-thumb', 264, 165 , true );
add_image_size( 'wpeasy-image-single-thumb', 355, 255 , true );