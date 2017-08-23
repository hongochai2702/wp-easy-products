<?php

/**
 * Class Autocomplete Control
 *
 * @class     Tpfw_Customize_Autocomplete_Control
 * @package   Tpfw/Customize_Field
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Tpfw_Customize_Autocomplete_Control Class
	 */
	class Tpfw_Customize_Autocomplete_Control extends WP_Customize_Control {

		/**
		 * @var string Field type
		 */
		public $type = 'tpfw_autocomplete';

		/**
		 * @var string Placeholder field
		 */
		private $placeholder = '';

		/**
		 * @var array Setting field data
		 */
		private $data = array();

		/**
		 * @var int Min Length
		 */
		private $min_length;

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

			$this->data = isset( $args['data'] ) ? $args['data'] : array();

			$this->placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

			$this->min_length = isset( $args['min_length'] ) ? $args['min_length'] : 3;

			parent::__construct( $manager, $id, $args );
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
				'data' => $this->data,
				'placeholder' => $this->placeholder,
				'min_length' => $this->min_length
			);

			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}

			echo tpfw_form_autocomplete( $args, $this->value() );
		}

	}

	

	

	

	

	

	

	
	
endif;