<?php

/**
 * Static functions used in the tpfw onclick import plugin
 */
class Tpfw_Import_Helpers {

	/**
	 * Holds the date and time string for demo import and log file.
	 *
	 * @var string
	 */
	public static $demo_import_start_time = '';

	/**
	 * Filter through the array of import files and get rid of those who do not comply.
	 *
	 * @param  array $import_files list of arrays with import file details.
	 * @return array list of filtered arrays.
	 */
	public static function validate_import_file_info( $import_files ) {
		$filtered_import_file_info = array();

		foreach ( $import_files as $key =>$import_file ) {
			if ( self::is_import_file_info_format_correct( $import_file ) ) {
				$filtered_import_file_info[$key] = $import_file;
			}
		}

		return $filtered_import_file_info;
	}

	/**
	 * Helper function: a simple check for valid import file format.
	 *
	 * @param  array $import_file_info array with import file details.
	 * @return boolean
	 */
	private static function is_import_file_info_format_correct( $import_file_info ) {
		if ( ( empty( $import_file_info['content_url'] ) ) || empty( $import_file_info['name'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Download import files. Content .xml and widgets .wie|.json files.
	 *
	 * @param  array $import_file_info array with import file details.
	 * @return array|WP_Error array of paths to the downloaded files or WP_Error object with error message.
	 */
	public static function download_import_files( $import_file_info, $import_index ) {
		$downloaded_files = array();
		$downloader = new Tpfw_Import_Downloader();

		// Set content file path
		// Check if 'content_url' is not defined. That would mean a local file.

		if ( !empty( $import_file_info['content_url'] ) ) {
			if ( file_exists( $import_file_info['content_url'] ) ) {
				$downloaded_files['content'] = $import_index;
			} else {
				// Set the filename string for content import file.
				$content_filename = apply_filters( 'tpfw_importer_downloaded_content_file_prefix', 'demo-content-import-file_' ) . self::$demo_import_start_time . apply_filters( 'tpfw_importer_downloaded_content_file_ext', '.xml' );

				// Download the content import file.
				$downloaded_files['content'] = $downloader->download_file( $import_file_info['content_url'], $content_filename );

				// Return from this function if there was an error.
				if ( is_wp_error( $downloaded_files['content'] ) ) {
					return $downloaded_files['content'];
				}
			}
		} else {
			return new WP_Error(
					'content_url_not_defined', sprintf(
							__( '"content_url" for %s%s%s are not defined!', 'tp-framework' ), '<strong>', $import_file_info['name'], '</strong>'
					)
			);
		}

		// Set widget file path
		// Get widgets file as well. If defined.
		if ( !empty( $import_file_info['widget_url'] ) ) {

			if ( file_exists( $import_file_info['widget_url'] ) ) {
				$downloaded_files['widgets'] = $import_index;
			} else {
				// Set the filename string for widgets import file.
				$widget_filename = apply_filters( 'tpfw_importer_downloaded_widgets_file_prefix', 'demo-widgets-import-file_' ) . self::$demo_import_start_time . apply_filters( 'tpfw_importer_downloaded_widgets_file_ext', '.wie' );

				// Download the widgets import file.
				$downloaded_files['widgets'] = $downloader->download_file( $import_file_info['widget_url'], $widget_filename );

				// Return from this function if there was an error.
				if ( is_wp_error( $downloaded_files['widgets'] ) ) {
					return $downloaded_files['widgets'];
				}
			}
		}

		// Set customizer file path
		// Get customizer import file as well. If defined!
		if ( !empty( $import_file_info['customizer_url'] ) ) {

			if ( file_exists( $import_file_info['customizer_url'] ) ) {
				$downloaded_files['customizer'] = $import_index;
			} else {
				// Setup filename path to save the customizer content.
				$customizer_filename = apply_filters( 'tpfw_importer_downloaded_customizer_file_prefix', 'demo-customizer-import-file_' ) . self::$demo_import_start_time . apply_filters( 'tpfw_importer_downloaded_customizer_file_ext', '.dat' );

				// Download the customizer import file.
				$downloaded_files['customizer'] = $downloader->download_file( $import_file_info['customizer_url'], $customizer_filename );

				// Return from this function if there was an error.
				if ( is_wp_error( $downloaded_files['customizer'] ) ) {
					return $downloaded_files['customizer'];
				}
			}
		}

		return $downloaded_files;
	}

	/**
	 * Write content to a file.
	 *
	 * @param string $content content to be saved to the file.
	 * @param string $file_path file path where the content should be saved.
	 * @return string|WP_Error path to the saved file or WP_Error object with error message.
	 */
	public static function write_to_file( $content, $file_path ) {

		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}


		// By this point, the $wp_filesystem global should be working, so let's use it to create a file.
		global $wp_filesystem;

		if ( !$wp_filesystem->put_contents( $file_path, $content ) ) {
			return new WP_Error(
					'failed_writing_file_to_server', sprintf(
							__( 'An error occurred while writing file to your server! Tried to write a file to: %s%s.', 'tp-framework' ), '<br>', $file_path
					)
			);
		}

		// Return the file path on successful file write.
		return $file_path;
	}

	/**
	 * Get the category list of all categories used in the predefined demo imports array.
	 *
	 * @param  array $demo_imports Array of demo import items (arrays).
	 * @return array|boolean       List of all the categories or false if there aren't any.
	 */
	public static function get_all_demo_import_categories( $demo_imports ) {
		$categories = array();

		foreach ( $demo_imports as $item ) {
			if ( !empty( $item['categories'] ) && is_array( $item['categories'] ) ) {
				foreach ( $item['categories'] as $category ) {
					$categories[sanitize_key( $category )] = $category;
				}
			}
		}

		if ( empty( $categories ) ) {
			return false;
		}

		return $categories;
	}

	/**
	 * Check if the AJAX call is valid
	 */
	public static function verify_ajax_call() {
		check_ajax_referer( 'importer-ajax-verification', 'security' );

		// Check if user has the WP capability to import data.
		if ( !current_user_can( 'import' ) ) {
			wp_die(
					sprintf(
							__( '%sYour user role isn\'t high enough. You don\'t have permission to import demo data.%s', 'tp-framework' ), '<div class="notice  notice-error"><p>', '</p></div>'
					)
			);
		}
	}

	/**
	 * Process uploaded files and return the paths to these files.
	 *
	 * @param array $uploaded_files $_FILES array form an AJAX request.
	 * @param string $log_file_path path to the log file.
	 * @return array of paths to the content import and widget import files.
	 */
	public static function process_uploaded_files( $uploaded_files ) {
		// Variable holding the paths to the uploaded files.
		$selected_import_files = array();

		// Upload settings to disable form and type testing for AJAX uploads.
		$upload_overrides = array(
			'test_form' => false,
			'test_type' => false,
		);

		// Handle demo content and widgets file upload.
		$content_file_info = '';
		$widget_file_info = '';
		$customizer_file_info = '';

		if ( isset( $_FILES['content_file'] ) ) {
			if ( self::file_validate( 'content_file', '.xml' ) ) {
				$content_file_info = wp_handle_upload( $_FILES['content_file'], $upload_overrides );
			} else {
				// Send an AJAX response with the error.
				$error_text = esc_html__( 'Please upload XML file for content import.', 'tp-framework' );
				// Send JSON Error response to the AJAX call.
				wp_send_json( $error_text );
			}
		}

		if ( isset( $_FILES['widget_file'] ) ) {
			if ( self::file_validate( 'widget_file', array( '.wie, .json' ) ) ) {
				$widget_file_info = wp_handle_upload( $_FILES['widget_file'], $upload_overrides );
			} else {
				// Send an AJAX response with the error.
				$error_text = esc_html__( 'Please upload WIE or JSON file for widget import.', 'tp-framework' );
				// Send JSON Error response to the AJAX call.
				wp_send_json( $error_text );
			}
		}

		if ( isset( $_FILES['customizer_file'] ) ) {
			if ( self::file_validate( 'customizer_file', '.dat' ) ) {
				$customizer_file_info = wp_handle_upload( $_FILES['customizer_file'], $upload_overrides );
			} else {
				// Send an AJAX response with the error.
				$error_text = esc_html__( 'Please upload DAT file for customizer import.', 'tp-framework' );
				// Send JSON Error response to the AJAX call.
				wp_send_json( $error_text );
			}
		}


		if ( $content_file_info && !isset( $content_file_info['error'] ) ) {
			// Set uploaded content file.
			$selected_import_files['content'] = $content_file_info['file'];
		}

		// Process widget import file.
		if ( $widget_file_info && !isset( $widget_file_info['error'] ) ) {
			// Set uploaded widget file.
			$selected_import_files['widgets'] = $widget_file_info['file'];
		}

		// Process Customizer import file.
		if ( $customizer_file_info && !isset( $customizer_file_info['error'] ) ) {
			// Set uploaded customizer file.
			$selected_import_files['customizer'] = $customizer_file_info['file'];
		}

		// Return array with paths of uploaded files.
		return $selected_import_files;
	}

	/**
	 * Return the concatenated string of demo import item categories.
	 * These should be separated by comma and sanitized properly.
	 *
	 * @param  array $item The predefined demo import item data.
	 * @return string       The concatenated string of categories.
	 */
	public static function get_demo_import_item_categories( $item ) {
		$sanitized_categories = array();

		if ( isset( $item['categories'] ) ) {
			foreach ( $item['categories'] as $category ) {
				$sanitized_categories[] = sanitize_key( $category );
			}
		}

		if ( !empty( $sanitized_categories ) ) {
			return implode( ',', $sanitized_categories );
		}

		return false;
	}

	/**
	 * Set the $demo_import_start_time class variable with the current date and time string.
	 */
	public static function set_demo_import_start_time() {
		self::$demo_import_start_time = date( apply_filters( 'tpfw_importer_date_format_for_file_names', 'Y-m-d__H-i-s' ) );
	}

	/**
	 * Helper function: check for WP file-system credentials needed for reading and writing to a file.
	 *
	 * @return boolean|WP_Error
	 */
	private static function check_wp_filesystem_credentials() {
		// Check if the file-system method is 'direct', if not display an error.
		if ( !( 'direct' === get_filesystem_method() ) ) {
			return new WP_Error(
					'no_direct_file_access', sprintf(
							__( 'This WordPress page does not have %sdirect%s write file access. This plugin needs it in order to save the demo import xml file to the upload directory of your site. You can change this setting with these instructions: %s.', 'tp-framework' ), '<strong>', '</strong>', '<a href="http://gregorcapuder.com/wordpress-how-to-set-direct-filesystem-method/" target="_blank">How to set <strong>direct</strong> filesystem method</a>'
					)
			);
		}

		// Get import page settings.
		$import_page_setup = apply_filters( 'tpfw_importer_page_setup', array(
			'parent_slug' => 'admin.php',
			'id' => 'tpfw_importer',
				)
		);

		// Get user credentials for WP file-system API.
		$demo_import_page_url = wp_nonce_url( $import_page_setup['parent_slug'] . '?import=' . $import_page_setup['id'], $import_page_setup['id'] );

		if ( false === ( $creds = request_filesystem_credentials( $demo_import_page_url, '', false, false, null ) ) ) {
			return new WP_error(
					'filesystem_credentials_could_not_be_retrieved', __( 'An error occurred while retrieving reading/writing permissions to your server (could not retrieve WP filesystem credentials)!', 'tp-framework' )
			);
		}

		// Now we have credentials, try to get the wp_filesystem running.
		if ( !WP_Filesystem( $creds ) ) {
			return new WP_Error(
					'wrong_login_credentials', __( 'Your WordPress login credentials don\'t allow to use WP_Filesystem!', 'tp-framework' )
			);
		}

		return true;
	}

	/**
	 * Check ext of a file
	 * @param string $file Name or path file
	 * @param string $ext Extend of the file
	 * @return bool
	 */
	private static function file_validate( $name, $ext = '' ) {

		if ( isset( $_FILES[$name] ) ) {

			$file = $_FILES[$name];

			if ( $file['name'] != '' && $file['error'] == 0 ) {

				if ( is_array( $ext ) ) {
					foreach ( $ext as $i ) {
						if ( strrpos( $file['name'], $i ) == ( strlen( $file['name'] ) - strlen( $i ) ) ) {
							return true;
						}
					}
				} else {
					if ( strrpos( $file['name'], $ext ) == ( strlen( $file['name'] ) - strlen( $ext ) ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Server send event
	 * Print message to client
	 * @since 1.0
	 */
	public static function tpfw_importer_sse( $id = 'print', $message = '', $progress = 0 ) {

		$arr = array( 'message' => $message );

		if ( $progress ) {
			$arr['progress'] = $progress;
		}

		$arr = json_encode( $arr );
		echo "id: {$id}\n";
		echo "data: {$arr}\n\n";

		flush();
		sleep( 1 );
	}

}
