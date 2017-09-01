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

// Load Class.
require_once( WPEASY_INCLUDE_LOCAL . '/we-init.php' );
//require_once( WPEASY_SHORTCODE_LOCAL. '/shortcode-generator.php' );

add_action( 'wp_enqueue_scripts', 'wpeasy_enqueue_script' );
if( !function_exists('wpeasy_enqueue_script') ){
	function wpeasy_enqueue_script(){
		wp_register_style('wpeasy-front', WPEASY_ASSETS_URL . '/css/wpeasy-product-front.css');
		wp_register_script('wpeasy-main', WPEASY_ASSETS_URL . '/js/main.js');
		wp_register_script('wpeasy-layouts', WPEASY_ASSETS_URL . '/js/layouts.js');
		wp_register_script('wpeasy-lazyload', WPEASY_ASSETS_URL . '/js/lazyload.min.js');
		
		wp_register_script('wpeasy-angular-core', WPEASY_ASSETS_URL . '/js/angular/angular.min.js');
		wp_register_script('wpeasy-underscore', WPEASY_ASSETS_URL . '/js/underscore-min.js');
		wp_register_script('wpeasy-isotope', WPEASY_ASSETS_URL . '/js/isotope.pkgd.min.js');
		
		//wp_register_script('wpeasy-isotope-infinite-scroll', WPEASY_ASSETS_URL . '/js/infinite-scroll.pkgd.min.js');
		//wp_enqueue_script('wpeasy-underscore');
		//wp_enqueue_script('wpeasy-angular-core');
		wp_enqueue_script('wpeasy-isotope');
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

// add WPEASY to body class
add_filter('body_class', 'wpeasy_body_class');
if( !function_exists('wpeasy_body_class') ){
	function wpeasy_body_class( $classes ){
		$classes[] = 'wpeasy-body';
		return $classes;
	}
}

if ( ! function_exists( 'is_version' ) ) {
	function is_version( $version = '3.1' ) {
		global $wp_version;
		
		if ( version_compare( $wp_version, $version, '>=' ) ) {
			return false;
		}
		return true;
	}
}