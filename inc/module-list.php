<?php

namespace XproElementorAddons\Inc;

use XproElementorAddons\Libs\Xpro_Elementor_Dashboard;
use XproElementorAddonsPro\Inc\Xpro_Elementor_Module_Pro_List;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Module_List {


	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Xpro_Elementor_Widget_List The single instance of the class.
	 */

	private static $instance = null;
	private static $list     = array(
		'floating-effect'     => array(
			'slug'    => 'floating_effect',
			'title'   => 'Floating Effect',
			'package' => 'free',
		),
		'custom-css'          => array(
			'slug'    => 'custom_css',
			'title'   => 'Custom CSS',
			'package' => 'free',
		),
		'xpro-icons'          => array(
			'slug'    => 'xpro_elementor_icons',
			'title'   => 'Xpro Icons',
			'package' => 'free',
		),
		'entrance-animation'  => array(
			'slug'    => 'entrance_animation',
			'title'   => 'Entrance Animation',
			'package' => 'free',
		),
		'post-duplicator'     => array(
			'slug'    => 'post_duplicator',
			'title'   => 'Post Duplicator',
			'package' => 'free',
		),
		'backdrop-filter'     => array(
			'slug'    => 'backdrop_filter',
			'title'   => 'Backdrop Filter',
			'package' => 'free',
		),
		'swatches'            => array(
			'slug'         => 'swatches',
			'title'        => 'Woo Swatches',
			'package'      => 'free',
			'dependencies' => 'WooCommerce',
		),
		'theme-builder'       => array(
			'slug'    => 'theme-builder',
			'title'   => 'Theme Builder Panel',
			'package' => 'undefined',
		),
		'scroll-effect'       => array(
			'slug'    => 'scroll_effect',
			'title'   => 'Scroll Effect',
			'package' => 'pro-disabled',
		),
		'3d-tilt-parallax'    => array(
			'slug'    => '3d_tilt_parallax',
			'title'   => '3D Tilt Parallax',
			'package' => 'pro-disabled',
		),
		'mouse-effect'        => array(
			'slug'    => 'mouse_effect',
			'title'   => 'Mouse Effect',
			'package' => 'pro-disabled',
		),
		'background-parallax' => array(
			'slug'    => 'background_parallax',
			'title'   => 'Background Parallax',
			'package' => 'pro-disabled',
		),
		'live-copy'           => array(
			'slug'    => 'live_copy',
			'title'   => 'Cross Domain Copy/Paste',
			'package' => 'pro-disabled',
		),
		'display-conditions'  => array(
			'slug'    => 'display_conditions',
			'title'   => 'Display Conditions',
			'package' => 'pro-disabled',
		),
		'acf-dynamic'         => array(
			'slug'    => 'acf_dynamic',
			'title'   => 'ACF Dynamic',
			'package' => 'pro-disabled',
		),
	);

	/**
	 * Usage:
	 * get full list >> get_list() []
	 */
	public function get_list( $filtered = true, $module = '', $check_mathod = 'list' ) {
		$all_list = self::$list;

		if ( true === $filtered ) {
			$all_list = apply_filters( 'xpro_elementor_addons_modules_list', self::$list );
		}

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) && 'valid' === Xpro_Elementor_License::$license_activate ) {
			$module_pro = Xpro_Elementor_Module_Pro_List::instance()->get_list();
			$all_list   = array_merge( $all_list, $module_pro );
		}

		if ( 'active' === $check_mathod ) {
			$active_list = Xpro_Elementor_Dashboard::instance()->utils->get_option(
				'xpro_elementor_module_list',
				array_keys( $all_list )
			);

			// checking active widgets
			foreach ( $all_list as $widget_slug => $info ) {
				if ( ! in_array( $widget_slug, $active_list, true ) ) {
					unset( $all_list[ $widget_slug ] );
				}
			}
		}

		if ( '' !== $module ) {
			return ( ! isset( $all_list[ $module ] ) ? false : $all_list[ $module ] );
		}

		return $all_list;
	}


	/**
	 * Check if a module is active or not, pro-disabled package are considered inactive
	 *
	 *
	 * @param $module_slug
	 *
	 * @return bool
	 */
	public function is_active( $module_slug ) {

		$act = self::instance()->get_list( true, $module_slug, 'active' );

		return empty( $act['package'] ) ? false : ( ( 'free' === $act['package'] || 'pro-disabled' === $act['package'] ) );
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Elementor_Widget_List An instance of the class.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
