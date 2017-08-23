<?php

/**
 * Class Link Control
 *
 * @class     Tpfw_Customize_Heading_Control
 * @package   Tpfw/Customize_Field
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Tpfw_Customize_Link_Control Class
	 */
	class Tpfw_Customize_Heading_Control extends WP_Customize_Control {

		/**
		 * @var string Field type
		 */
		public $type = 'tpfw_heading';

		/**
		 * @var array Heading default options
		 */
		public $default;

		/**
		 * Constructor.
		 * Supplied `$args` override class property defaults.
		 * If `$args['settings']` is not defined, use the $id as the setting ID.
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    {@see WP_Customize_Control::__construct}.
		 */
		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {
			/**
			 * Just show label
			 */
			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';
		}

	}
endif;