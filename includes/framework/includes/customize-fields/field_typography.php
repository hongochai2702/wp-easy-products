<?php

/**
 * Class Link Control
 *
 * @class     Tpfw_Customize_Typography_Control
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
	class Tpfw_Customize_Typography_Control extends WP_Customize_Control {

		/**
		 * @var string Field type
		 */
		public $type = 'tpfw_typography';

		/**
		 * @var array Typography default options
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

			if ( !empty( $args['value'] ) && is_array( $args['value'] ) ) {
				$this->default = $args['value'];
			} else {
				$this->default = array();
			}
		}

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'type' => $this->type,
				'customize_link' => $this->get_link(),
				'default' => $this->default
			);

			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}

			echo tpfw_form_typography( $args, $this->value() );
		}

	}
	
endif;