<?php
class WE_Customizer_Options {
	
	private $_SettingOptions;
	
	public function __construct() {
		//echo "<br />" . __METHOD__;
	    
		$this->_SettingOptions = array(
		    //Catalog//
			'we_catalog_layout' 		         => get_theme_mod('we_catalog_layout'),
			'we_catalog_sidebar_layout'          => get_theme_mod('we_catalog_sidebar_layout'),
			'we_catalog_paging_display_type' 	 => get_theme_mod('we_catalog_paging_display_type'),
		    'we_catalog_display_column' 	     => get_theme_mod('we_catalog_display_column'),
		    'we_catalog_display_number' 		 => get_theme_mod('we_catalog_display_number'),
		    'we_catalog_product_order' 		     => get_theme_mod('we_catalog_product_order'),
		    //Product//
		    'we_product_layout' 		         => get_theme_mod('we_product_layout'),
		    'we_product_layout_sidebar' 		 => get_theme_mod('we_product_layout_sidebar'),
		    //Setting//
		    'we_setting_display_options' 		 => get_theme_mod('we_setting_display_options')
			
		);
		
		
	}
	
	public function getSettings() {
	    //$sizes = $this->ajax_thumbnail_rebuild_get_sizes();
		return $this->_SettingOptions;
	}
	
	public function get_catalog_layout() {
	    $layout = '';
	    
	    switch ( $this->_SettingOptions['we_catalog_layout'] ) {
	        case 'list': $layout = 'list';
	        break;
	        
	        case 'grid': $layout = 'grid';
            break;
	        
	        case 'masory': $layout = 'masory';
	        break;
	        
	        case 'overlay': $layout = 'overlay';
	        break;
	        
	        default:
	            $layout = 'list';
	        break;
	    }
	    
	    return $layout;
	}
	
	public function get_column_items() {
		$classes = '';
		if ( !isset($this->_SettingOptions['we_catalog_display_column']) || empty($this->_SettingOptions['we_catalog_display_column']) ) return $classes = 'span-4';
		if ( isset($this->_SettingOptions['we_catalog_layout']) && !empty($this->_SettingOptions['we_catalog_layout']) && ($this->_SettingOptions['we_catalog_layout'] == 'list')  ) return $classes = 'span-12';
		// Check column display product.
		if ( 1 == $this->_SettingOptions['we_catalog_display_column'] ) {
			$classes = $classes = 'span-12';
		} else if ( 2 == $this->_SettingOptions['we_catalog_display_column'] ) {
			$classes = $classes = 'span-6';
		} else if ( 3 == $this->_SettingOptions['we_catalog_display_column'] ) {
			$classes = $classes = 'span-4';
		} else if ( 4 == $this->_SettingOptions['we_catalog_display_column'] ) {
			$classes = $classes = 'span-3';
		}  else if ( 6 == $this->_SettingOptions['we_catalog_display_column'] ) {
			$classes = $classes = 'span-2';
		}
		
		return $classes;
	}
	public function ajax_thumbnail_rebuild_get_sizes() {
    	global $_wp_additional_image_sizes;
    
    	foreach ( get_intermediate_image_sizes() as $s ) {
    		$sizes[$s] = array( 'name' => '', 'width' => '', 'height' => '', 'crop' => FALSE );
    
    		/* Read theme added sizes or fall back to default sizes set in options... */
    
    		$sizes[$s]['name'] = $s;
    
    		if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
    			$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); 
    		else
    			$sizes[$s]['width'] = get_option( "{$s}_size_w" );
    
    		if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
    			$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] );
    		else
    			$sizes[$s]['height'] = get_option( "{$s}_size_h" );
    
    		if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
    			$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] );
    		else
    			$sizes[$s]['crop'] = get_option( "{$s}_crop" );
    	}
    
    	return $sizes;
    }
	
}