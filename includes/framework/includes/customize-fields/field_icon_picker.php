<?php

/**
 * Class Icon Picker Control
 *
 * @class     Tpfw_Customize_Icon_Picker_Control
 * @package   tpfw/Customize_Field
 * @category  Class
 * @author    ThemesPond
 * @license   GPLv3
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Tpfw_Customize_Icon_Picker_Control Class
	 */
	class Tpfw_Customize_Icon_Picker_Control extends WP_Customize_Control {

		public $type = 'tpfw_icon_picker';

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'type' => $this->type,
				'customize_link' => $this->get_link(),
			);
			
			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}
			echo tpfw_form_icon_picker( $args, $this->value() );
			
			
		}

	}
endif;