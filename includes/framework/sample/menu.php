<?php

function tpfw_example_menu_meta($fields) {
	return tpfw_example_fields();
}

add_filter( 'tpfw_menu_fields', 'tpfw_example_menu_meta' );
