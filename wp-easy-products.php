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
define('WPEASY_VIEWS_LOCAL', WPEASY_INCLUDE_LOCAL . '/views');
define('WPEASY_TEMPLATES_LOCAL', WPEASY_LOCAL . '/templates');

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

/**
 * Save the WEASYProducts class for later
 */
function wp_easy_product_option_init() {
	/*
	 * global $weProductData, $weCustomizerOptions;
   	$weProductData 			= new WE_Product_Data();
	$weCustomizerOptions 	= new WE_Customizer_Options();*/
}
add_action('init', 'wp_easy_product_option_init', 9);

/**
 * Save the WEASYProductsAdmin class for later
 */
function wp_easy_product_option_admin_init() {
	// Do some thing...//
	/*
	 * global $weProductData, $weCustomizerOptions;
   	$weProductData 			= new WE_Product_Data();
	$weCustomizerOptions 	= new WE_Customizer_Options();*/
}
add_action('admin_init', 'wp_easy_product_option_admin_init');

// INCLUDE INIT.
require_once( WPEASY_INCLUDE_LOCAL . '/we-init.php' );
//require_once( WPEASY_SHORTCODE_LOCAL. '/shortcode-generator.php' );

