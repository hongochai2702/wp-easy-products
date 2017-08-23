<?php

/**
 * Class Datetime Control
 *
 * @class     Tpfw_Customize_Datetime_Control
 * @package   Tpfw/Customize_Field
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Tpfw_Customize_Datetime_Control Class
	 */
	class Tpfw_Customize_Datetime_Control extends WP_Customize_Control {

		/**
		 * @var string Field type
		 */
		public $type = 'tpfw_datetime';

		/**
		 * @var array Datetimepicker options
		 */
		public $options = array();

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

			if ( empty( $args['options'] ) || !is_array( $args['options'] ) ) {
				$args['options'] = array();
			}

			$this->options = $args['options'];
		}

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'options' => $this->options,
				'type' => $this->type,
				'customize_link' => $this->get_link(),
			);

			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}

			echo tpfw_form_datetime( $args, $this->value() );
		}

	}
	
endif;