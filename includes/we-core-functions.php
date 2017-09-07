<?php

define('WC_TEMPLATE_DEBUG_MODE', FALSE);

/**
 * Check customizer and page template settings before displaying a sidebar
 *
 * @param   int     $sidebar                Sidebar slug to check
 * @param   string $container_class       Sidebar container class
 * @return  html    $sidebar                Sidebar template
 */
if( !function_exists( 'we_maybe_get_sidebar' ) ) {
	function we_maybe_get_sidebar( $sidebar = 'we-sidebar-archive-left', $container_class = 'column', $return = FALSE ) {

		global $post;

		$show_sidebar = we_can_show_sidebar( $sidebar );
		$sidebar_slug = WE_SIDEBAR . '-archive-';
		
		if( TRUE == $show_sidebar ) { ?>
			<?php if( is_active_sidebar( $sidebar_slug . $sidebar ) ) { ?>
				<div class="<?php echo esc_attr( $container_class ); ?>">
			<?php } ?>
				<?php dynamic_sidebar( $sidebar_slug . $sidebar ); ?>
			<?php if( is_active_sidebar( $sidebar_slug . $sidebar ) ) { ?>
				</div>
			<?php } ?>
		<?php }
	}
} // we_get_header_class

/**
 * Set posts per page for WE Product
 *
 * @access public
 * @param mixed $sidebar
 */
function we_can_show_sidebar( $sidebar = 'left' ) {
	global $weCustomizerOptions;
	$show_sidebar = FALSE;
	$weSetting = $weCustomizerOptions->getSettings();
	if ( $weSetting['we_catalog_sidebar_layout'] == $sidebar ) {
		$show_sidebar = TRUE;
	}
	return $show_sidebar;
}

/**
 * Set posts per page for WE Product
 *
 * @access public
 * @param mixed $query
 */
function set_posts_per_page_for_product( $query ) {
	global $weCustomizerOptions;
	
	// Customizer setting.
	$weSetting = $weCustomizerOptions->getSettings();
	$orderProducts 	= json_decode($weSetting['we_catalog_product_order'] , TRUE); // Order +  Order by.
	
	if ( !is_admin() && $query->is_main_query() && is_tax( 'product_cate' ) ) {
		$query->set( 'posts_per_page', $weSetting['we_catalog_display_number'] );
		$query->set( 'orderby', $orderProducts['orderby'] );
		$query->set( 'order', $orderProducts['order'] );
	}
}
add_action( 'pre_get_posts', 'set_posts_per_page_for_product' );

/**
 * Get template part (for templates like the shop-loop).
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function we_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
	/*if ( $name && ! WC_TEMPLATE_DEBUG_MODE ) {
	$template = locate_template( array( "{$slug}-{$name}.php", WC()->template_path() . "{$slug}-{$name}.php" ) );
	}*/

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( WPEASY_LOCAL . "/templates/{$slug}-{$name}.php" ) ) {
		$template = WPEASY_LOCAL . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
	/*if ( ! $template && ! WC_TEMPLATE_DEBUG_MODE ) {
	$template = locate_template( array( "{$slug}.php", WC()->template_path() . "{$slug}.php" ) );
	}*/

	// Allow 3rd party plugin filter template file from their plugin
	if ( ( ! $template && WC_TEMPLATE_DEBUG_MODE ) || $template ) {
		$template = apply_filters( 'we_get_template_part', $template, $slug, $name );
	}

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function we_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = we_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin
	$located = apply_filters( 'we_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'wpeasy_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'wpeasy_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function we_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = WPEASY_TEMPLATES_LOCAL;
	}

	if ( ! $default_path ) {
		$default_path = WPEASY_LOCAL . '/templates/';
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template(
	array(
	trailingslashit( $template_path ) . $template_name,
	$template_name
	)
	);

	// Get default template
	if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found
	return apply_filters( 'wpeasy_locate_template', $template, $template_name, $template_path );
}