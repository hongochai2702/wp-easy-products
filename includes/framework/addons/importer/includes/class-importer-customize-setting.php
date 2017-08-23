<?php

// Require modified customizer options class.
if ( !class_exists( 'WP_Customize_Setting' ) ) {
	require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
}

/**
 * Import customizer
 * @extend WP_Customize_Setting to use update function
 */
class Tpfw_Importer_Customize_Setting extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @since 1.0
	 * @param mixed $value The option value.
	 * @return void
	 */
	public function import( $value ) {
		$this->update( $value );
	}

}
