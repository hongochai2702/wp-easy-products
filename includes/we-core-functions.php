<?php

define('WC_TEMPLATE_DEBUG_MODE', FALSE);


/**
 * Get the posted data for a form
 *
 * @param  integer $post_id the form post ID
 *
 * @ printf
 */
if ( !function_exists('we_format_price') ) {
    function we_format_price( $price = array() ) {
        // Check value tranfer.
     
        $regular_price = number_format_i18n( $price['regular_price'] );
        $special_price = number_format_i18n( $price['special_price'] );
        $html .= "<div class='price'>";
        if ( !isset($regular_price) || empty($regular_price) ) {
            $html .= "<span class='amount regular-price'>". __("Contact us", "weasy") ."</span>";
        } else {
            $html .= "<span class='amount regular-price'>". $regular_price ." VNĐ</span>";
        }
        
        if ( $regular_price != 0 && $special_price != 0 ) {
            $html .= "<span class='amount regular-price'>". $regular_price ." VNĐ</span><span class='amount special-price'>". $special_price ." VNĐ</span>";
        }
        
        $html .= "</div>";
        return $html;                        
    }
}

/**
 * Get the posted data for a form
 *
 * @param  integer $post_id the form post ID
 *
 * @return array            the form values
 */
if ( !function_exists('get_gallery_images_meta') ) {
	function get_gallery_images_meta( $post_id ) {
		// Check value tranfer.
		if ( !isset($post_id) || empty($post_id) ) return;
		
		$gallery = array();
		$value = get_post_meta( $post_id, 'we_product_gallery_thumb', TRUE );
		$value = explode( ',', trim( $value ) );

		if ( !empty( $value[0] ) && sizeof( $value ) > 0 ) {
			foreach ( $value as $str ) {
				$arr = explode( '|', $str );
				if ( !empty( $arr[0] ) && sizeof( $arr ) > 0 ) {
					$id = $arr[0];
					$gallery[] = wp_get_attachment_image( $id, 'thumbnail' );
				}
			}
		}
		return $gallery;
	}
}



/**
 * Get the posted data for a form
 *
 * @param  integer $post_id the form post ID
 *
 * @return array            the form values
 */
function get_product_posted_fields($post_id = 0) {
    $posted = array();
    $post_meta = get_post_meta($post_id);
    $posted = array_intersect_key(
        $post_meta,
        array_flip(array_filter(array_keys($post_meta), function ($key) {
            return preg_match('/^we_product_/', $key);
        }))
    );

    return $posted;
}



/**
 * Check customizer and page template settings before displaying a sidebar
 *
 * @param   int     $sidebar                Sidebar slug to check
 * @param   string $container_class       Sidebar container class
 * @return  html    $sidebar                Sidebar template
 */
// add WPEASY to body class
add_filter('body_class', 'wpeasy_body_class');
if( !function_exists('wpeasy_body_class') ){
	function wpeasy_body_class( $classes ){
		$classes[] = 'wpeasy-body';
		return $classes;
	}
}


/**
 * Check customizer and page template settings before displaying a sidebar
 *
 * @param   int     $sidebar                Sidebar slug to check
 * @param   string $container_class       Sidebar container class
 * @return  html    $sidebar                Sidebar template
 */

if ( ! function_exists( 'is_version' ) ) {
	function is_version( $version = '3.1' ) {
		global $wp_version;
		
		if ( version_compare( $wp_version, $version, '>=' ) ) {
			return false;
		}
		return true;
	}
}

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