<?php
define('WE_SIDEBAR', 'we-sidebar');

/*
 * Include class process data
 * */

include_once ( WPEASY_INCLUDE_LOCAL . '/class-we-product-data.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/class-we-customizer-options.php' );

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ( WPEASY_INCLUDE_LOCAL . '/framework/tp-framework.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-product-fields.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-admin-customizer-fields.php' );

include_once ( WPEASY_INCLUDE_LOCAL . '/we-core-functions.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-includes-script.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-product-options.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-template-settings.php' );


global $weProductData, $weCustomizerOptions;
$weProductData 			= new WE_Product_Data();
$weCustomizerOptions 	= new WE_Customizer_Options();