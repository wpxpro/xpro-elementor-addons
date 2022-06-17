<?php
/**
 * Entrance extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddonsPro\Module;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Entrance_Animation {


	public static function init() {
		add_filter( 'elementor/controls/animations/additional_animations', array( __CLASS__, 'additional_animations' ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param array $list
	 *
	 * @return array
	 */
	public static function additional_animations( $list ) {

		$animations = array(
			'Xpro Masking'  => array(
				'xpro-anim-mask-from-up'    => 'Mask From Up',
				'xpro-anim-mask-from-down'  => 'Mask From Down',
				'xpro-anim-mask-from-left'  => 'Mask From Left',
				'xpro-anim-mask-from-right' => 'Mask From Right',
			),
			'Xpro Flipping' => array(
				'flipInX' => 'Flip Horizontal',
				'flipInY' => 'Flip Vertical',
			),
		);

		return array_merge( $animations, $list );
	}
}


Xpro_Elementor_Entrance_Animation::init();
