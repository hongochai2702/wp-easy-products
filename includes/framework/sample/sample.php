<?php

/**
 * Include Sample List
 *
 * @package     Tpfw
 * @category    Sample
 * @author      ThemesPond
 * @license     GPLv3
 */
include TPFW_DIR . 'sample/post-meta.php';
include TPFW_DIR . 'sample/taxonomy.php';
include TPFW_DIR . 'sample/widget.php';
include TPFW_DIR . 'sample/customizer.php';
include TPFW_DIR . 'sample/menu.php';

function tpfw_example_gmap_key( $key ) {
	return 'AIzaSyBS5224HISbnpAiKW7mx6eyTrHxfGeCftk';
	//'AIzaSyDbHSt4ney__Avh8FBFmU7j3BJ7TEjsyR4';
}

add_action( 'tpfw_gmap_key', 'tpfw_example_gmap_key' );
