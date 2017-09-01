<?php
class WE_Customizer_Options {
	
	private $_SettingOptions;
	
	public function __construct() {
		//echo "<br />" . __METHOD__;
		$this->_SettingOptions = array(
			'we_catalog_layout' 		=> get_theme_mod('we_catalog_layout'),
			'we_catalog_display_type' 	=> get_theme_mod('we_catalog_display_type'),
			'we_product_layout' 		=> get_theme_mod('we_product_layout')
			
		);
		$this->getSettings();
		
	}
	
	public function getSettings() {
		return $this->_SettingOptions;
	}
	
}