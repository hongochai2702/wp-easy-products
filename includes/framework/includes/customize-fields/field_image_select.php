<?php

/**
 * Class Image_Select Control
 *
 * @class     Tpfw_Customize_Image_Select_Control
 * @package   Tpfw/Customize_Field
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Tpfw_Customize_Image_Select_Control Class
	 */
	class Tpfw_Customize_Image_Select_Control extends WP_Customize_Control {

		/**
		 * @var string Field type
		 */
		public $type = 'tpfw_image_select';

		/**
		 * @var array Image Size
		 */
		public $image_size = array();

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

			if ( empty( $args['choices'] ) || !is_array( $args['choices'] ) ) {
				$args['choices'] = array();
			}

			if ( isset( $args['image_size'] ) && is_array( $args['image_size'] ) ) {
				$this->image_size = $args['image_size'];
			}

			$this->choices = $args['choices'];
		}

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'options' => $this->choices,
				'type' => $this->type,
				'customize_link' => $this->get_link(),
				'name' => '_customize-image_select-' . $this->id,
				'image_size' => $this->image_size
			);

			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}

			echo tpfw_form_image_select( $args, $this->value() );
		}

	}

	

	
	
endif;