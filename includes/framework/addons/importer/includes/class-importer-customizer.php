<?php

/**
 * Import customizer
 */
class Tpfw_Importer_Customizer
{

	/**
	 * @var string Path file
	 */
	private $file;

	/**
	 * @var bool Allow user fetch attachments
	 */
	private $fetch_attachments;

	/**
	 * Init class
	 */
	public function __construct( $file, $fetch_attachments )
	{
		$this->file = $file;
		$this->fetch_attachments = $fetch_attachments;
	}

	/**
	 * Import customize file
	 * @since 1.0
	 * @param string $this ->file Customize file
	 * @return void
	 */
	public function import()
	{

		// File exists?
		if ( !file_exists( $this->file ) ) {
			throw new Exception( __( 'Customize file could not be found.', 'tp-framework' ) );
		}

		// Get file contents and decode
		$data = file_get_contents( $this->file );
		$data = maybe_unserialize( $data );
		$template = get_template();

		// Only if there is data
		if ( !empty( $data ) && is_array( $data ) ) {

			// Data checks.
			if ( 'array' != gettype( $data ) ) {
				$error = __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'tp-framework' );
				return;
			}
			if ( !isset( $data[ 'template' ] ) || !isset( $data[ 'mods' ] ) ) {
				$error = __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'tp-framework' );
				return;
			}
			if ( $data[ 'template' ] != $template ) {
				$error = __( 'Error importing settings! The settings you uploaded are not for the current theme.', 'tp-framework' );
				return;
			}

			// Import images.
			if ( $this->fetch_attachments ) {
				$data[ 'mods' ] = $this->import_images( $data[ 'mods' ] );

			}


			global $wp_customize;

			// Import custom options.
			if ( isset( $data[ 'options' ] ) ) {

				foreach ( $data[ 'options' ] as $option_key => $option_value ) {

					$customize_setting = new Tpfw_Importer_Customize_Setting( $wp_customize, $option_key, array(
						'default'    => '',
						'type'       => 'option', //option
						'capability' => 'edit_theme_options'
					) );

					$customize_setting->import( $option_value );
				}
			}

			// Call the customize_save action.
			do_action( 'customize_save', $wp_customize );

			// Loop through the mods.
			foreach ( $data[ 'mods' ] as $key => $val ) {
				// Call the customize_save_ dynamic action.
				do_action( 'customize_save_' . $key, $wp_customize );
				// Save the mod.
				set_theme_mod( $key, $val );
			}

		}

		do_action( 'tpfw_importer_customize_end', $data );
	}

	/**
	 * Imports images for settings saved as mods.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $mods An array of customizer mods.
	 * @return array The mods array with any new import data.
	 */
	private function import_images( $mods )
	{

		if ( !function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}

		// Filter hook to enable placeholder image
		$use_default_image = apply_filters( 'tpfw_importer_customizer_enable_default_image', false);
		$image_url = apply_filters( 'tpfw_importer_customzer_default_image_url', '' );

		foreach ( $mods as $key => $val ) {

			if ( $this->is_image( $val ) ) {

				if ( $use_default_image && !empty( $image_url ) ) {
					$val = $image_url;
				}

				$data = $this->upload_image( $val );



				if ( !is_wp_error( $data ) ) {

					$mods[ $key ] = $data->url;

					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	/**
	 * Taken from the core media upload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 1.0
	 * @access private
	 * @param string $file The image file path.
	 * @return array An array of image data.
	 */
	private function upload_image( $file )
	{
		$data = new stdClass();

		if ( !empty( $file ) ) {

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$this->file_array = array();
			$this->file_array[ 'name' ] = basename( $matches[ 0 ] );

			// Download file to temp location.
			$this->file_array[ 'tmp_name' ] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $this->file_array[ 'tmp_name' ] ) ) {
				return $this->file_array[ 'tmp_name' ];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $this->file_array, 0 );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $this->file_array[ 'tmp_name' ] );
				return $id;
			}

			// Build the object to return.
			$meta = wp_get_attachment_metadata( $id );
			$data->attachment_id = $id;
			$data->url = wp_get_attachment_url( $id );
			$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
			$data->height = $meta[ 'height' ];
			$data->width = $meta[ 'width' ];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 1.0
	 * @access private
	 * @param string $string The string to check.
	 * @return bool Whether the string is an image url or not.
	 */
	private function is_image( $string = '' )
	{
		if ( is_string( $string ) ) {

			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}

}
