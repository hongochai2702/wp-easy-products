<?php

/*
  Plugin Name: TP Framework    
  Plugin URI: https://wordpress.org/plugins/tp-framework/    
  Description: TP Framework provides theme users with an advanced importer and a library including Taxonomy, Metabox, Customizer, Menu Meta, Widget Fields.    
  Author: themespond    
  Version: 1.0.2    
  Author URI: https://themespond.com    
  Text Domain: tp-framework    
  License: GPLv3    
  License URI: URI: https://www.gnu.org/licenses/gpl-3.0.html    
  Requires at least: 4.5    
  Tested up to: 4.8   
 */

final class TPFW {

	/**
	 * TP Framework version.
	 *
	 * @var string
	 */
	public $version = '1.0.2';

	/**
	 * The single instance of the class.
	 *
	 * @var TP Framework
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main TP Framework Instance.
	 *
	 * Ensures only one instance of TP Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see tpfw()
	 * @return TP Framework - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Init plugin
	 * @since 1.0
	 */
	public function __construct() {
		$this->defined();
		$this->includes();
		$this->hooks();

		do_action( 'tpfw_loaded' );
	}

	/**
	 * Main hook in plugin
	 * @since 1.0
	 */
	public function hooks() {

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'customize_register', array( $this, 'customize_fields' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_scripts' ) );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_init', array( $this, 'admin_fields' ) );

			add_action( 'current_screen', array( $this, 'termbox_init' ) );
			add_action( 'wp_ajax_add-tag', array( $this, 'termbox_ajax_init' ), 1 );

			add_action( 'load-post.php', array( $this, 'metabox_init' ) );
			add_action( 'load-post-new.php', array( $this, 'metabox_init' ) );

			add_action( 'load-nav-menus.php', array( $this, 'menu_init' ) );

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
		}
	}

	/**
	 * Create environment to init taxonomy meta
	 * @since 1.0.0
	 */
	public function termbox_init( $screen ) {
		do_action( 'tpfw_termbox_init' );
	}

	/**
	 * Create Ajax environment to add taxonomy
	 * @since 1.0.0
	 */
	public function termbox_ajax_init() {
		do_action( 'tpfw_termbox_init' );
	}

	/**
	 * Create environment to init post metabox
	 * @since 1.0.0
	 */
	public function metabox_init() {
		do_action( 'tpfw_metabox_init' );
	}

	public function menu_init() {
		include TPFW_DIR . 'includes/class-tpfw-menu.php';
	}

	public function customize_fields() {
		$this->register_customize_field( 'Tpfw_Customize_Select_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Multicheck_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Icon_Picker_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Repeater_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Map_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Link_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Datetime_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Typography_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Image_Select_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Autocomplete_Control' );
		$this->register_customize_field( 'Tpfw_Customize_Heading_Control' );
		$this->set_control_types();
	}

	private function set_control_types() {

		global $tpfw_control_types;

		$tpfw_control_types = apply_filters( 'tpfw_control_types', array(
			'image' => 'WP_Customize_Image_Control',
			'cropped_image' => 'WP_Customize_Cropped_Image_Control',
			'upload' => 'WP_Customize_Upload_Control',
			'color' => 'WP_Customize_Color_Control',
			'tpfw_select' => 'Tpfw_Customize_Select_Control',
			'tpfw_multicheck' => 'Tpfw_Customize_Multicheck_Control',
			'tpfw_icon_picker' => 'Tpfw_Customize_Icon_Picker_Control',
			'tpfw_repeater' => 'Tpfw_Customize_Repeater_Control',
			'tpfw_map' => 'Tpfw_Customize_Map_Control',
			'tpfw_link' => 'Tpfw_Customize_Link_Control',
			'tpfw_datetime' => 'Tpfw_Customize_Datetime_Control',
			'tpfw_typography' => 'Tpfw_Customize_Typography_Control',
			'tpfw_image_select' => 'Tpfw_Customize_Image_Select_Control',
			'tpfw_autocomplete' => 'Tpfw_Customize_Autocomplete_Control',
			'tpfw_heading' => 'Tpfw_Customize_Heading_Control'
				) );

		// Make sure the defined classes actually exist.
		foreach ( $tpfw_control_types as $key => $classname ) {

			if ( !class_exists( $classname ) ) {
				unset( $tpfw_control_types[$key] );
			}
		}
	}

	/**
	 * Load field in admin
	 * @since 1.0
	 */
	public function admin_fields() {
		include TPFW_DIR . 'includes/admin-fields/field_default.php';
		include TPFW_DIR . 'includes/admin-fields/field_color_picker.php';
		include TPFW_DIR . 'includes/admin-fields/field_datetime.php';
		include TPFW_DIR . 'includes/admin-fields/field_icon_picker.php';
		include TPFW_DIR . 'includes/admin-fields/field_image_picker.php';
		include TPFW_DIR . 'includes/admin-fields/field_image_select.php';
		include TPFW_DIR . 'includes/admin-fields/field_link.php';
		include TPFW_DIR . 'includes/admin-fields/field_map.php';
		include TPFW_DIR . 'includes/admin-fields/field_repeater.php';
		include TPFW_DIR . 'includes/admin-fields/field_typography.php';
		include TPFW_DIR . 'includes/admin-fields/field_autocomplete.php';
	}

	/**
	 * Include library
	 * @since 1.0
	 */
	public function includes() {
		
		include TPFW_DIR . 'includes/tpfw-sanitize-functions.php';
		include TPFW_DIR . 'includes/tpfw-helpers-functions.php';
		include TPFW_DIR . 'includes/class-tpfw-fonts.php';
		include TPFW_DIR . 'includes/class-tpfw-widget.php';
		include TPFW_DIR . 'includes/class-tpfw-customizer.php';
		include TPFW_DIR . 'includes/class-tpfw-metabox.php';
		include TPFW_DIR . 'includes/class-tpfw-taxonomy.php';

		/**
		 * Load addons
		 */
		include TPFW_ADDONS_DIR . '/importer/importer.php';
		
		do_action( 'tpfw_includes' );
	}

	/**
	 * Defined
	 * @since 1.0
	 */
	public function defined() {
		define( 'TPFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'TPFW_VERSION', $this->version );
		define( 'TPFW_DIR', plugin_dir_path( __FILE__ ) );
		define( 'TPFW_URL', plugin_dir_url( __FILE__ ) );
		define( 'TPFW_ADDONS_DIR', TPFW_DIR . 'addons' );
		define( 'TPFW_ADDONS_URL', TPFW_URL . 'addons' );

		global $tpfw_customizer_dependency;
		$tpfw_customizer_dependency = array();
	}

	/**
	 * Load Localisation files.
	 * @since 1.0
	 * @return void
	 */
	public function load_plugin_textdomain() {

		// Set filter for plugin's languages directory
		$tpfw_dir = TPFW_DIR . 'languages/';
		$tpfw_dir = apply_filters( 'tpfw_languages_directory', $tpfw_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'tp-framework' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'tp-framework', $locale );

		// Setup paths to current locale file
		$mofile_local = $tpfw_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/tp-framework/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/epl folder
			load_textdomain( 'tp-framework', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/tpfw/languages/ folder
			load_textdomain( 'tp-framework', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'tp-framework', false, $tpfw_dir );
		}
	}

	/**
	 * Register and load customize field
	 * @return void
	 */
	private function register_customize_field( $control_class ) {

		$path = str_replace( 'Tpfw_Customize_', 'field_', $control_class );
		$path = str_replace( '_Control', '.php', $path );
		$path = strtolower( $path );
		$path = TPFW_DIR . 'includes/customize-fields/' . $path;

		if ( is_readable( $path ) ) {
			include $path;
			global $wp_customize;
			$wp_customize->register_control_type( $control_class );
		}
	}

	/**
	 * Enqueue admin scripts
	 * @since 1.0
	 * @return void
	 */
	public function admin_scripts( $hook_suffix ) {

		$min = WP_DEBUG ? '' : '.min';

		global $tpfw_registered_fields;

		/**
		 * Init register nav menu meta item
		 */
		if ( !empty( $tpfw_registered_fields ) ) {

			$tpfw_registered_fields = array_unique( $tpfw_registered_fields );

			wp_enqueue_style( 'font-awesome', TPFW_URL . 'assets/css/font-awesome' . $min . '.css', null, '4.7.0' );
			wp_enqueue_style( 'tpfw-admin', TPFW_URL . 'assets/css/admin' . $min . '.css', null, TPFW_VERSION );

			wp_enqueue_script( 'tpfw-libs', TPFW_URL . 'assets/js/libs' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
			wp_enqueue_script( 'tpfw-admin', TPFW_URL . 'assets/js/admin_fields' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
			wp_enqueue_script( 'dependency', TPFW_URL . 'assets/vendors/dependency/dependency' . $min . '.js', array( 'jquery' ), TPFW_VERSION );

			$upload_dir = wp_upload_dir();

			$localize = array(
				'upload_url' => $upload_dir['baseurl']
			);

			foreach ( $tpfw_registered_fields as $type ) {
				switch ( $type ) {
					case 'color_picker':
						wp_enqueue_script( 'wp-color-picker' );
						wp_enqueue_style( 'wp-color-picker' );
						break;
					case 'image_picker';
						wp_enqueue_media();
						wp_enqueue_script( 'jquery-ui' );
						break;
					case 'icon_picker':
						wp_enqueue_script( 'font-iconpicker', TPFW_URL . 'assets/vendors/fonticonpicker/js/jquery.fonticonpicker' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
						wp_enqueue_style( 'font-iconpicker', TPFW_URL . 'assets/vendors/fonticonpicker/css/jquery.fonticonpicker' . $min . '.css', null, TPFW_VERSION );
						break;
					case 'map':
						$gmap_key = sanitize_text_field( apply_filters( 'tpfw_gmap_key', '' ) );
						wp_enqueue_script( 'google-map-v-3', "//maps.googleapis.com/maps/api/js?v=3&libraries=places&key={$gmap_key}", array( 'jquery' ), null, true );
						wp_enqueue_script( 'geocomplete', TPFW_URL . 'assets/vendors/geocomplete/jquery.geocomplete' . $min . '.js', null, TPFW_VERSION );
						break;
					case 'icon_picker':
						wp_enqueue_script( 'font-iconpicker', TPFW_URL . 'assets/vendors/fonticonpicker/js/jquery.fonticonpicker' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
						wp_enqueue_style( 'font-iconpicker', TPFW_URL . 'assets/vendors/fonticonpicker/css/jquery.fonticonpicker' . $min . '.css', null, TPFW_VERSION );
						break;
					case 'link':

						$screens = apply_filters( 'tpfw_link_on_screens', array( 'post.php', 'post-new.php' ) );
						if ( !in_array( $hook_suffix, $screens ) ) {
							wp_enqueue_style( 'editor-buttons' );
							wp_enqueue_script( 'wplink' );

							add_action( 'in_admin_header', 'tpfw_link_editor_hidden' );
							add_action( 'customize_controls_print_footer_scripts', 'tpfw_link_editor_hidden' );
						}
						break;
					case 'repeater':
						wp_enqueue_script( 'jquery-repeater', TPFW_URL . 'assets/js/repeater-libs' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
						break;
					case 'select':
					case 'typography':
					case 'autocomplete':

						wp_enqueue_script( 'selectize', TPFW_URL . 'assets/vendors/selectize/selectize' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
						wp_enqueue_style( 'selectize', TPFW_URL . 'assets/vendors/selectize/selectize' . $min . '.css', null, TPFW_VERSION );
						wp_enqueue_style( 'selectize-skin', TPFW_URL . 'assets/vendors/selectize/selectize.default' . $min . '.css', null, TPFW_VERSION );

						if ( $type == 'typography' ) {
							
							$localize['subsets'] = Tpfw_Fonts::get_google_font_subsets();

							$localize['variants'] = Tpfw_Fonts::get_all_variants();

							$localize['fonts'] = TPFW_Fonts::get_all_fonts_reordered();
						}

						break;
					case 'datetime':
						wp_enqueue_script( 'datetimepicker', TPFW_URL . 'assets/vendors/datetimepicker/jquery.datetimepicker.full' . $min . '.js', array( 'jquery' ), TPFW_VERSION );
						wp_enqueue_style( 'datetimepicker', TPFW_URL . 'assets/vendors/datetimepicker/jquery.datetimepicker' . $min . '.css', null, TPFW_VERSION );
						break;
					default :
						do_action( 'tpfw_admin_' . $type . '_scripts' );
						break;
				}
			}

			wp_localize_script( 'tpfw-admin', 'tpfw_var', apply_filters( 'tpfw_localize_var', $localize ) );
		}
	}

	/**
	 * Binds the JS listener to make Customizer control
	 *
	 * @since 1.0.0
	 */
	public function customize_scripts() {

		global $tpfw_customizer_dependency;

		$min = WP_DEBUG ? '' : '.min';
		wp_enqueue_script( 'tpfw-customize-field', TPFW_URL . 'assets/js/customize-fields' . $min . '.js', array( 'customize-controls' ), TPFW_VERSION, true );


		if ( !empty( $tpfw_customizer_dependency ) ) {
			wp_localize_script( 'tpfw-customize-field', 'tpfw_customizer_dependency', $tpfw_customizer_dependency );
		}
	}

	/**
	 * Show row meta on the plugin screen.
	 * @since 1.0.0
	 * 
	 * @param	mixed $links Plugin Row Meta
	 * @param	mixed $file  Plugin Base file
	 * @return	array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( TPFW_PLUGIN_BASENAME === $file ) {
			$row_meta = array(
				'page' => '<a href="https://themespond.com/docs" aria-label="' . esc_attr__( 'View Document', 'tp-framework' ) . '">' . esc_html__( 'Documents', 'tp-framework' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return $links;
	}

	/**
	 * Add links to Plugins page
	 * @since 1.0.0
	 * @return array
	 */
	function add_action_links( $links ) {

		$plugin_links = array(
			'page' => '<a href="' . esc_url( apply_filters( 'tpfw_page_url', admin_url( 'admin.php?import=tpfw_importer' ) ) ) . '" aria-label="' . esc_attr__( 'View Plugin Page', 'tp-framework' ) . '">' . esc_html__( 'Import page', 'tp-framework' ) . '</a>',
		);

		return array_merge( $links, $plugin_links );
	}

}

/**
 * Main instance of TP Framework.
 *
 * Returns the main instance of TP Framework to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return TP Framework
 */
function tpfw() {
	return TPFW::instance();
}

// Global for backwards compatibility.
$GLOBALS['TPFW'] = tpfw();


//require 'sample/sample.php';
