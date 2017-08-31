<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ( WPEASY_INCLUDE_LOCAL . '/framework/tp-framework.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-product-fields.php' );

include_once ( WPEASY_INCLUDE_LOCAL . '/we-core-functions.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-product-options.php' );
include_once ( WPEASY_INCLUDE_LOCAL . '/we-template-settings.php' );


/*
 * Include class process data
 * */
global $weProductData;
include_once ( WPEASY_INCLUDE_LOCAL . '/class-we-product-data.php' );
$weProductData = new WE_Product_Data();
if ( is_admin() ) {
	// Load file in admin.
} else {
	//Load file in front.
	
}
