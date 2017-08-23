<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( !class_exists( 'Tpfw_Importer' ) ) {

	/**
	 * Main class for importing demo content
	 */
	class Tpfw_Importer
	{

		/**
		 * Holds the verified import files.
		 *
		 * @var array
		 */
		private $import_files;

		/**
		 * The index of the `import_files` array (which import files was selected).
		 *
		 * @var int
		 */
		private $selected_index;

		/**
		 * The paths of the actual import files to be used in the import.
		 *
		 * @var array
		 */
		private $selected_import_files;

		private $image_id = 0;

		private $image_url;

		public function __construct()
		{
			if ( !is_admin() ) {
				return;
			}
			$this->includes();
			$this->hooks();
			$this->register();

			$this->image_url = apply_filters( 'tpfw_importer_default_image', TPFW_ADDONS_URL . '/importer/assets/img/default-image.png' );
		}

		/**
		 * Requires files
		 * @since 1.0
		 */
		public function includes()
		{
			include TPFW_ADDONS_DIR . '/importer/includes/class-importer-downloader.php';
			include TPFW_ADDONS_DIR . '/importer/includes/helper-functions.php';
		}

		/**
		 * Hook events
		 * @since 1.0
		 */
		public function hooks()
		{
			add_action( 'wp_ajax_tpfw_importer', array( $this, 'prepareFile' ) );
			add_action( 'wp_ajax_tpfw_import_demo', array( $this, 'ajax_import' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		/**
		 * Register to importer list
		 * @since 1.0
		 */
		public function register()
		{
			// Get info of import data files and filter it.
			$this->import_files = Tpfw_Import_Helpers::validate_import_file_info( $this->get_import_files() );

			register_importer(
				'tpfw_importer', esc_attr__( 'TP Importer', 'tp-framework' ), wp_kses_post( __( 'Import customizer from <a target="_blank" href="//wordpress.org/plugins/customizer-export-import/">Customizer Export/Import</a>, import widgets from <a target="_blank" href="//wordpress.org/plugins/widget-importer-exporter/">Widget Importer & Exporter</a> and import posts, pages, comments, custom fields, categories, and tags from a <a href="%s" target="_blank">WordPress export</a>.', 'tp-framework' ), admin_url( 'export.php' ) ), array( $this, 'display_plugin_page' )
			);
		}

		/**
		 * Plugin page display.
		 * Output (HTML) is in another file.
		 */
		public function display_plugin_page()
		{
			if ( isset( $_GET[ 'type' ] ) && $_GET[ 'type' ] == 'upload' ) {
				require_once TPFW_ADDONS_DIR . '/importer/templates/upload.php';
			} else {
				require_once TPFW_ADDONS_DIR . '/importer/templates/available.php';
			}
		}

		/**
		 * Enqueue admin scripts (JS and CSS)
		 *
		 * @param string $hook holds info on which admin page you are currently loading.
		 */
		public function admin_enqueue_scripts( $hook )
		{

			if ( isset( $_GET[ 'import' ] ) && $_GET[ 'import' ] == 'tpfw_importer' ) {

				wp_enqueue_script( 'tpfw-importer', TPFW_ADDONS_URL . '/importer/assets/js/main.js', array( 'jquery', 'jquery-ui-dialog' ), '1.0.0' );

				wp_localize_script( 'tpfw-importer', 'tpfw_importer', array(
						'ajax_url'      => admin_url( 'admin-ajax.php' ),
						'ajax_nonce'    => wp_create_nonce( 'importer-ajax-verification' ),
						'import_text'   => esc_html__( 'Import', 'tp-framework' ),
						'reimport_text' => esc_html__( 'Re-Import', 'tp-framework' )
					)
				);

				wp_enqueue_style( 'tpfw-importer', TPFW_ADDONS_URL . '/importer/assets/css/style.css', array(), '1.0.0' );
			}
		}

		/**
		 * Main AJAX callback function
		 * Prepare import file (uploaded file)
		 */
		public function prepareFile()
		{
			// Try to update PHP memory limit (so that it does not run out of it).
			ini_set( 'memory_limit', apply_filters( 'tpfw_importer_memory_limit', '350M' ) );

			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			Tpfw_Import_Helpers::verify_ajax_call();

			// Create a date and time string to use for demo and log file names.
			Tpfw_Import_Helpers::set_demo_import_start_time();

			// Get selected file index or set it to 0.
			$this->selected_index = !isset( $_POST[ 'selected' ] ) ? '' : $_POST[ 'selected' ];

			/**
			 * 1). Prepare import files
			 * Manually uploaded import files.
			 */
			if ( !empty( $_FILES ) ) {
				// Get paths for the uploaded files
				$this->selected_import_files = Tpfw_Import_Helpers::process_uploaded_files( $_FILES );
			} elseif ( !empty( $this->import_files[ $this->selected_index ] ) ) {
				// Use predefined import files from wp filter: tpfw_importer_files.
				// Download the import files from wp filter: tpfw_importer_files
				$this->selected_import_files = Tpfw_Import_Helpers::download_import_files( $this->import_files[ $this->selected_index ], $this->selected_index );

				// Check Errors
				if ( is_wp_error( $this->selected_import_files ) ) {

					$error_text = $this->selected_import_files->get_error_message();
					// Send JSON Error response to the AJAX call.
					wp_send_json( $error_text );
				} else if ( $this->selected_index >= 0 ) {
					update_option( 'tpfw_imported_id', $this->selected_index );
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'tp-framework' ) );
			}


			$files_import = array_map( array( $this, 'send_url_import' ), $this->selected_import_files );

			wp_send_json_success( $files_import );

			die();
		}

		/**
		 * Protect url before send to ajax response.
		 * Replace upload folder in url by spacing, only send file name via ajax.
		 */
		public function send_url_import( $value )
		{

			$updir = wp_upload_dir();

			if ( strpos( $value, $updir[ 'path' ] ) !== false ) {
				return str_replace( trailingslashit( $updir[ 'path' ] ), '', $value );
			}

			return $value;
		}

		public function ajax_import()
		{
			// Increase PHP max execution time. Just in case, even though the AJAX calls are only 25 sec long.
			set_time_limit( apply_filters( 'tpfw_importer_set_time_limit', 300 ) );

			header( 'Content-Type: text/event-stream' );
			header( 'Cache-Control: no-cache' );

			// Ensure we're not buffered.
			wp_ob_end_flush_all();
			flush();

			Tpfw_Import_Helpers::tpfw_importer_sse( 'connected', 'Connected', 0 );

			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			Tpfw_Import_Helpers::verify_ajax_call();

			$list_demo_import = Tpfw_Import_Helpers::validate_import_file_info( $this->get_import_files() );


			/**
			 * 2). Import Content
			 */
			if ( isset( $_GET[ 'content' ] ) ) {

				$file_content = $_GET[ 'content' ];
				$updir = wp_upload_dir();

				// Check enable placeholder image
				if( $_GET['placeholder_img'] == 'true' ) {
					add_filter( 'wp_import_post_data_raw', array( $this, 'import_post_data_raw' ), 10, 1 );
					add_filter( 'wp_import_post_data_processed', array( $this, 'import_post_data_processed' ), 10, 2 );
					add_filter( 'wp_import_post_meta', array( $this, 'import_post_meta' ), 10, 3 );
				}

				if ( file_exists( trailingslashit( $updir[ 'path' ] ) . $file_content ) ) {
					$file_content = trailingslashit( $updir[ 'path' ] ) . $file_content;
				} else {
					$file_content = $list_demo_import[ $file_content ][ 'content_url' ];
				}

				Tpfw_Import_Helpers::tpfw_importer_sse( 'installing', __( 'Installing Content...', 'tp-framework' ), 20 );

				try {
					ob_start();
					$this->import_content( $file_content );
					$res = ob_get_clean();
					Tpfw_Import_Helpers::tpfw_importer_sse( 'print', $res );
				} catch ( Exception $ex ) {
					Tpfw_Import_Helpers::tpfw_importer_sse( 'error', $ex->getMessage() );
				}
			}

			/**
			 * 3). Import Widget
			 */
			if ( isset( $_GET[ 'widgets' ] ) ) {

				$file_widgets = $_GET[ 'widgets' ];
				$updir = wp_upload_dir();

				if ( file_exists( trailingslashit( $updir[ 'path' ] ) . $file_widgets ) ) {
					$file_widgets = trailingslashit( $updir[ 'path' ] ) . $file_widgets;
				} else {
					$file_widgets = $list_demo_import[ $file_widgets ][ 'widget_url' ];
				}

				Tpfw_Import_Helpers::tpfw_importer_sse( 'installing', __( 'Installing Widgets...', 'tp-framework' ), 50 );
				$this->import_widget( $file_widgets );
			}

			/**
			 * 3). Import Customizer
			 */
			// Request the customizer import AJAX call.
			if ( isset( $_GET[ 'customizer' ] ) ) {
				$file_customizer = $_GET[ 'customizer' ];
				$updir = wp_upload_dir();

				if( $_GET['placeholder_img'] == 'true' ) {
					add_filter( 'tpfw_importer_customizer_enable_default_image', function ( $value ) { return true; }, 10, 1 );
					add_filter( 'tpfw_importer_customzer_default_image_url', function ( $value ) { return $this->image_url; }, 10, 1 );
				}

				if ( file_exists( trailingslashit( $updir[ 'path' ] ) . $file_customizer ) ) {
					$file_customizer = trailingslashit( $updir[ 'path' ] ) . $file_customizer;
				} else {
					$file_customizer = $list_demo_import[ $file_customizer ][ 'customizer_url' ];
				}

				Tpfw_Import_Helpers::tpfw_importer_sse( 'installing', __( 'Installing Customizer...', 'tp-framework' ), 60 );
				$this->import_customizer( $file_customizer );
			}

			Tpfw_Import_Helpers::tpfw_importer_sse( 'installing', __( 'Config Data...', 'tp-framework' ), 80 );

			/**
			 * For third party or theme hooks after import end.
			 */
			do_action( 'tpfw_importer_end' );

			Tpfw_Import_Helpers::tpfw_importer_sse( 'installing', __( 'Finish', 'tp-framework' ), 100 );

			Tpfw_Import_Helpers::tpfw_importer_sse( 'done', __( 'Imported Successfully', 'tp-framework' ) );

			die();
		}

		/**
		 * Import content
		 * Import post, pages, menus, custom post types
		 *
		 * @param string $file The exported file's name
		 */
		public function import_content( $file )
		{
			if ( !file_exists( $file ) ) {
				throw new Exception( esc_html__( "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the Wordpress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ", 'tp-framework' ) );
			} else {
				require_once TPFW_ADDONS_DIR . '/importer/wordpress-importer/wordpress-importer.php';
				$wp_import = new WP_Import();
				$wp_import->fetch_attachments = true;
				$wp_import->import( $file );
			}
		}

		/**
		 * Import Wiget
		 *
		 * @param string $file The exported file's name
		 */
		public function import_widget( $file )
		{
			if ( !file_exists( $file ) ) {
				throw new Exception( esc_html__( "The WIE file containing the dummy content is not available or could not be read ...<br/>", 'tp-framework' ) );
			} else {
				require_once TPFW_ADDONS_DIR . '/importer/includes/class-importer-widgets.php';
				$widget_import = new Tpfw_Importer_Widgets( $file );
				$widget_import->import();
			}
		}

		/**
		 * Import Customizer
		 */
		public function import_customizer( $file )
		{

			if ( !file_exists( $file ) ) {
				throw new Exception( esc_html__( "The DAT file containing the dummy content is not available or could not be read ...<br/>", 'tp-framework' ) );
			} else {
				require_once TPFW_ADDONS_DIR . '/importer/includes/class-importer-customize-setting.php';
				require_once TPFW_ADDONS_DIR . '/importer/includes/class-importer-customizer.php';

				$fetch_attachments = apply_filters( 'tpfw_importer_customizer_fetch_image', true );
				$customizer_import = new Tpfw_Importer_Customizer( $file, $fetch_attachments );
				$customizer_import->import();
			}
		}


		/**
		 * Register import dummy-data
		 * It may registers in theme or plugin
		 * Push local dir or online url of the file import file to array
		 */
		public function get_import_files()
		{

			/**
			 * Default supported dummy data
			 */
			$arr = array(
				'demo_import_1' => array(
					'name'           => esc_html__( 'Theme Unit Test', 'tp-framework' ),
					'content_url'    => TPFW_ADDONS_DIR . '/importer/dummy-data/theme-unit-test/theme-unit-test-data.xml',
					'widget_url'     => TPFW_ADDONS_DIR . '/importer/dummy-data/theme-unit-test/widgets-monster.wie',
					'customizer_url' => '',
					'preview_image'  => TPFW_ADDONS_URL . '/importer/dummy-data/theme-unit-test/screenshot.png',
					'preview_url'    => 'https://themespond.com',
				),
				'demo_import_2' => array(
					'name'           => esc_html__( 'WooCommerce Data', 'tp-framework' ),
					'content_url'    => TPFW_ADDONS_DIR . '/importer/dummy-data/woocommerce/dummy-data.xml',
					'widget_url'     => TPFW_ADDONS_DIR . '/importer/dummy-data/woocommerce/widgets.wie',
					'customizer_url' => '',
					'preview_image'  => TPFW_ADDONS_URL . '/importer/dummy-data/woocommerce/screenshot.png',
					'preview_url'    => 'https://themespond.com',
				)
			);

			return apply_filters( 'tpfw_importer_files', $arr );
		}


		public function import_customizer_default_img( $val )
		{
			return $this->image_url;
		}

		// Raw attachment
		public function import_post_data_raw( $post )
		{
			if ( $post[ 'post_type' ] == 'attachment' ) {
				if ( $this->image_id === 0 ) {
					$this->image_id = $post[ 'post_id' ];
					$post[ 'attachment_url' ] = $this->image_url;
					$post[ 'guid' ] = $this->image_url;
				} else {
					$post = array();
				}
			}
			return $post;
		}

		// Replace image in visual page
		public function import_post_data_processed( $postdata, $post )
		{

			$postdata[ 'post_content' ] = preg_replace( '/image=\"(\d+)\"/U', 'image="' . $this->image_id . '"', $postdata[ 'post_content' ] );
			$postdata[ 'post_content' ] = preg_replace( '/background-image: url\(.*?\)/U', 'background-image: url(' . $this->image_url . '?id=' . $this->image_id . ')', $postdata[ 'post_content' ] );
			return $postdata;

		}

		public function import_post_meta( $postmeta, $post_id, $post )
		{

			if ( !empty( $post[ 'postmeta' ] ) ) {
				$count = 0;

				foreach ( $postmeta as $meta ) {

					if ( '_thumbnail_id' == $meta[ 'key' ] ) {
						$postmeta[ $count ][ 'value' ] = $this->image_id;
					}
					$count++;
				}
			}

			return $postmeta;
		}

	}

	/* end class */
}

add_action( 'admin_init', function () {
	new Tpfw_Importer();
} );


