<?php

namespace XproElementorAddons;

use Elementor\Plugin;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;
use XproElementorAddons\Control\Xpro_Elementor_Image_Selector;
use XproElementorAddons\Control\Xpro_Elementor_Select;
use XproElementorAddons\Control\Xpro_Elementor_Widget_Area;
use XproElementorAddons\Inc\Xpro_Elementor_Module_List;
use XproElementorAddons\Inc\Xpro_Elementor_Widget_List;
use XproElementorAddons\Libs\Dashboard\Classes\Xpro_Elementor_Dashboard_Utils;
use XproElementorAddons\Libs\Xpro_Elementor_Dashboard;

defined( 'ABSPATH' ) || die();

/**
 * Class Xpro_Elementor_Addons
 *
 * Main Plugin class
 */
class Xpro_Elementor_Addons {


	/**
	 * Instance
	 * @var Xpro_Elementor_Addons The single instance of the class.
	 */
	private static $instance = null;

	private $all_widgets;
	private $active_widgets;
	private $all_modules;
	private $active_modules;

	/**
	 *  Xpro_Elementor_Addons class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Elementor_Addons An instance of the class.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$this->include_files();

		//Plugin Init.
		add_action( 'init', array( $this, 'register_modules' ) );

		//Register Custom Post Type.
		add_action( 'init', array( $this, 'custom_item_post_type' ) );

		// Init Elementor
		add_action( 'elementor/init', array( $this, 'xpro_elementor_init' ) );

		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );

		// Register widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Register editor scripts.
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ) );

		// Register editor style.
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_enqueue_styles' ) );

		// Register editor script.
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_enqueue_script' ) );

		// Register control style.
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'control_enqueue_styles' ) );

		// Register control Script.
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'control_enqueue_scripts' ), 22 );

		// Register Preview Style.
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );

		//Register Control.
		add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );

		//Filter the single_template with our custom function.
		add_filter( 'single_template', array( $this, 'xpro_elementor_content_template' ) );

		//Footer Text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 22 );

		//Plugin Row Meta.
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
		add_filter( 'plugin_action_links_' . XPRO_ELEMENTOR_ADDONS_BASE, array( $this, 'plugin_action_links' ) );

		//WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'before_add_to_cart' ) );
			add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'after_add_to_cart' ) );
			add_action( 'wp_footer', array( $this, 'cart_quantity_plus_minus' ) );
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'mini_cart_fragments' ) );
		}

	}

	public function include_files() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/dashboard/dashboard.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/helper-functions.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/widget-area-utils.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/widget-list.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/module-list.php';

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-xpro-navwalker.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-library-manager.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-library-source.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'classes/class-ajax-handler.php';
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {

		//Widgets CSS
		wp_enqueue_style(
			'xpro-grid',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-grid.min.css',
			null,
			'1.0.0'
		);
		wp_enqueue_style(
			'xpro-elementor-addons-widgets',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-widgets.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'xpro-elementor-addons-responsive',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-responsive.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'font-awesome',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			null,
			'5.15.3'
		);
		wp_enqueue_style(
			'xpro-icons',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);

		//Widgets JS
		wp_enqueue_script(
			'xpro-elementor-addons-widgets',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'js/xpro-widgets.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		wp_script_add_data( 'xpro-elementor-addons-widgets', 'async', true );

		if ( class_exists( 'woocommerce' ) ) {
			wp_enqueue_style(
				'xpro-elementor-addons-woo',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-woo-widgets.css',
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
			wp_enqueue_script(
				'xpro-elementor-addons-woo',
				XPRO_ELEMENTOR_ADDONS_ASSETS . 'js/xpro-woo-widgets.js',
				array( 'jquery' ),
				XPRO_ELEMENTOR_ADDONS_VERSION,
				true
			);

			wp_script_add_data( 'xpro-elementor-addons-woo', 'async', true );

			$xpro_elementor_woo_localize = apply_filters(
				'xpro_elementor_woo_localize',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'xpro-woo-nonce' ),
				)
			);
			wp_localize_script(
				'xpro-elementor-addons-woo',
				'XproElementorAddonsWoo',
				$xpro_elementor_woo_localize
			);
		}

		//Vendors CSS
		wp_register_style(
			'cubeportfolio',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/cubeportfolio.min.css',
			null,
			'4.4.0'
		);
		wp_register_style(
			'owl-carousel',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/owl.carousel.min.css',
			null,
			'2.3.4'
		);
		wp_register_style(
			'lightgallery',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/lightgallery.min.css',
			null,
			'1.6.12'
		);
		wp_register_style(
			'slick',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/slick.min.css',
			null,
			'1.8.0'
		);
		wp_register_style(
			'animate',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/animate.min.css',
			null,
			'3.4.0'
		);
		wp_register_style(
			'xpro-compare',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/xpro-compare.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_register_style(
			'hover',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/hover.min.css',
			null,
			'2.3.2'
		);
		wp_register_style(
			'swiper',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/swiper-bundle.min.css',
			null,
			'7.3.3'
		);
		wp_register_style(
			'leaflet',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/leaflet.min.css',
			null,
			'16.0'
		);
		wp_register_style(
			'plyr',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/plyr.css',
			null,
			'3.6.12'
		);
		wp_register_style(
			'fancybox',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/fancybox.css',
			null,
			'4.0.22'
		);
		wp_register_style(
			'prism',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/prism.min.css',
			null,
			'1.16.0'
		);

		//Vendors JS
		wp_register_script(
			'cubeportfolio',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.cubeportfolio.min.js',
			array( 'jquery' ),
			'4.4.0',
			true
		);
		wp_register_script(
			'owl-carousel',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js',
			array( 'jquery' ),
			'2.3.4',
			true
		);
		wp_register_script(
			'lightgallery',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lightgallery-all.min.js',
			array( 'jquery' ),
			'1.6.12',
			true
		);
		wp_register_script(
			'gsap',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/gsap.min.js',
			array( 'jquery' ),
			'3.2.4',
			true
		);
		wp_register_script(
			'slick',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/slick.min.js',
			array( 'jquery' ),
			'1.8.0',
			true
		);
		wp_register_script(
			'morphext',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/morphext.min.js',
			array( 'jquery' ),
			'2.4.4',
			true
		);
		wp_register_script(
			'typed',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/typed.min.js',
			array( 'jquery' ),
			'2.0.12',
			true
		);
		wp_register_script(
			'anime',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/anime.min.js',
			array( 'jquery' ),
			'3.0.1',
			true
		);
		wp_register_script(
			'lax',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lax.min.js',
			array( 'jquery' ),
			'2.0.0',
			true
		);
		wp_register_script(
			'vanilla-tilt',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/vanilla-tilt.min.js',
			array( 'jquery' ),
			'1.7.0',
			true
		);
		wp_register_script(
			'lottie',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/lottie.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'easypiechart',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.easypiechart.min.js',
			array( 'jquery' ),
			'2.1.7',
			true
		);
		wp_register_script(
			'sliding-menu',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/sliding-menu.min.js',
			array( 'jquery' ),
			'1.8.0',
			true
		);
		wp_register_script(
			'spritespin',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/spritespin.min.js',
			array( 'jquery' ),
			'4.0.11',
			true
		);
		wp_register_script(
			'xpro-compare',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/compare.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'sharer',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/sharer.min.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'countdown',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/countdown.min.js',
			array( 'jquery' ),
			'0.1.2',
			true
		);
		wp_register_script(
			'isotope',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js',
			array( 'jquery' ),
			'3.0.6',
			true
		);
		wp_register_script(
			'drawsvg',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.drawsvg.min.js',
			array( 'jquery' ),
			'0.1.2',
			true
		);
		wp_register_script(
			'swiper',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/swiper-bundle.min.js',
			array( 'jquery' ),
			'7.3.3',
			true
		);
		wp_register_script(
			'leaflet',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/leaflet.min.js',
			array( 'jquery' ),
			'1.6.0',
			true
		);
		wp_register_script(
			'recaptcha',
			'https://www.google.com/recaptcha/api.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
		wp_register_script(
			'plyr',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/plyr.js',
			array( 'jquery' ),
			'3.6.12',
			true
		);
		wp_register_script(
			'fancybox',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/fancybox.umd.js',
			array( 'jquery' ),
			'4.0.22',
			true
		);
		wp_register_script(
			'prism',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/prism.min.js',
			array( 'jquery' ),
			'1.16.0',
			true
		);
		wp_register_script(
			'elevatezoom',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery.elevatezoom.min.js',
			array( 'jquery' ),
			'3.0.8',
			true
		);
		wp_register_script(
			'asRange',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/jquery-asRange.min.js',
			array( 'jquery' ),
			'0.3.4',
			true
		);

		$user_settings = Xpro_Elementor_Dashboard_Utils::instance()->get_option( 'xpro_elementor_user_data', array() );

		if ( $user_settings && ! empty( $user_settings['google_map']['api'] ) ) {
			wp_register_script(
				'gmap-api',
				'https://maps.googleapis.com/maps/api/js?key=' . $user_settings['google_map']['api'],
				array( 'jquery' ),
				XPRO_ELEMENTOR_ADDONS_VERSION,
				true
			);
		}

		wp_register_script(
			'gmap',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/google-map.min.js',
			array( 'jquery' ),
			'0.4.25',
			true
		);
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_preview_styles() {

		//Third Party Plugins
		if ( class_exists( '\WPForms\WPForms' ) && defined( 'WPFORMS_PLUGIN_SLUG' ) ) {
			wp_enqueue_style(
				'xpro-wpforms',
				plugins_url( '/' . WPFORMS_PLUGIN_SLUG . '/assets/css/wpforms-full.css', WPFORMS_PLUGIN_SLUG ),
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
		}

		if ( class_exists( '\GFForms' ) ) {
			wp_enqueue_style(
				'xpro-gravity-forms',
				plugins_url( '/gravityforms/css/theme.min.css', 'gravityforms' ),
				null,
				XPRO_ELEMENTOR_ADDONS_VERSION
			);
		}
	}

	/**
	 * editor_enqueue_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_styles() {
		wp_enqueue_style(
			'xpro-elementor-addons-editor',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-editor.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'xpro-icons',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
	}

	/**
	 * editor_enqueue_script
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_script() {
		wp_enqueue_script(
			'xpro-elementor-addons-editor',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'js/xpro-editor.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
	}

	/**
	 * control_enqueue_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function control_enqueue_styles() {
		wp_enqueue_style(
			'xpro-elementor-addons-control',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/css/widgetarea-editor.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
	}

	/**
	 * control_enqueue_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function control_enqueue_scripts() {
		wp_enqueue_script(
			'xpro-elementor-addons-control',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/js/widgetarea-editor.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'xpro-elementor-addons-control',
			'xpro_elementor_control_params',
			array(
				'rest_api_url' => get_rest_url(),
			)
		);
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_scripts() {
		add_filter( 'script_loader_tag', array( $this, 'editor_scripts_as_a_module' ), 10, 2 );
	}

	/**
	 * Force load editor script as a module
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 */
	public function editor_scripts_as_a_module( $tag, $handle ) {

		wp_enqueue_script(
			'xpro-elementor-addons-editor',
			XPRO_ELEMENTOR_ADDONS_ASSETS . '/js/xpro-editor.js',
			array( 'jquery' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		if ( 'xpro-elementor-addons-editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets( $widgets_manager ) {

		$this->all_widgets    = Xpro_Elementor_Widget_List::instance()->get_list();
		$this->active_widgets = Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_widget_list', array_keys( $this->all_widgets ) );

		foreach ( $this->active_widgets as $widget_slug ) {
			if ( array_key_exists( $widget_slug, $this->all_widgets ) ) {
				if ( 'pro-disabled' !== $this->all_widgets[ $widget_slug ]['package'] && 'pro' !== $this->all_widgets[ $widget_slug ]['package'] ) {
					require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'widgets/' . str_replace( '_', '-', $widget_slug ) . '/' . str_replace( '_', '-', $widget_slug ) . '.php';
					$class_name = '\XproElementorAddons\Widget\\' . $this->make_classname( $widget_slug );
					if ( class_exists( $class_name ) ) {
						$widgets_manager->register( new $class_name() );
					}
				}
			}
		}
	}

	/**
	 * Auto generate classname from path.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function make_classname( $dirname ) {
		$dirname    = pathinfo( $dirname, PATHINFO_FILENAME );
		$class_name = explode( '-', $dirname );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}

	/**
	 * Register Modules
	 *
	 * Register Modules Settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function register_modules() {

		$this->all_modules    = Xpro_Elementor_Module_List::instance()->get_list();
		$this->active_modules = Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_module_list', array_keys( $this->all_modules ) );

		foreach ( $this->active_modules as $module_slug ) {
			if ( array_key_exists( $module_slug, $this->all_modules ) ) {
				if ( 'pro-disabled' !== $this->all_modules[ $module_slug ]['package'] && 'pro' !== $this->all_modules[ $module_slug ]['package'] && 'undefined' !== $this->all_modules[ $module_slug ]['package'] ) {
					if ( isset( $this->all_modules[ $module_slug ]['dependencies'] ) && ! class_exists( $this->all_modules[ $module_slug ]['dependencies'] ) ) {
						continue;
					}
					include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/modules/' . str_replace( '_', '-', $module_slug ) . '/' . str_replace( '_', '-', $module_slug ) . '.php';
				}
			}
		}
	}

	/**
	 * Register Control
	 *
	 * Register new Elementor control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_controls( $control_manager ) {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/image-selector.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/foreground.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/widget-area.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/controls/select.php';

		$control_manager->register_control( Xpro_Elementor_Image_Selector::TYPE, new Xpro_Elementor_Image_Selector() );
		$control_manager->register_control( Xpro_Elementor_Select::TYPE, new Xpro_Elementor_Select() );
		$control_manager->register_control( Xpro_Elementor_Widget_Area::TYPE, new Xpro_Elementor_Widget_Area() );
		$control_manager->add_group_control( Xpro_Elementor_Group_Control_Foreground::get_type(), new Xpro_Elementor_Group_Control_Foreground() );
	}

	/**
	 * Elementor Init
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function xpro_elementor_init() {

		//Register Category
		Plugin::$instance->elements_manager->add_category(
			'xpro-widgets',
			array(
				'title' => esc_html__( 'Xpro Addons', 'xpro-elementor-addons' ),
				'icon'  => 'xi xi-xpro',
			)
		);

		Plugin::$instance->elements_manager->add_category(
			'xpro-themer',
			array(
				'title' => esc_html__( 'Xpro Theme Builder', 'xpro-elementor-addons' ),
				'icon'  => 'xi xi-xpro',
			)
		);
	}

	public function admin_footer_text( $footer_text ) {
		$current_screen      = get_current_screen();
		$is_elementor_screen = ( $current_screen && false !== strpos( $current_screen->id, 'xpro-elementor-addons' ) );

		if ( $is_elementor_screen ) {
			$footer_text = sprintf(
				/* translators: 1: Elementor, 2: Link to plugin review */
				__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'xpro-elementor-addons' ),
				'<strong>' . esc_html__( 'Xpro Elementor Addons', 'xpro-elementor-addons' ) . '</strong>',
				'<a href="https://wordpress.org/plugins/xpro-elementor-addons/#reviews" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		return $footer_text;
	}

	/**
	 * Content Template
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function xpro_elementor_content_template( $single ) {

		global $post;

		/* Checks for single template by post type */
		if ( 'xpro_content' === $post->post_type ) {
			if ( file_exists( ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php' ) ) {
				return ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
			}
		}

		return $single;
	}

	/**
	 * add increament to quantity
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function before_add_to_cart() {
		echo '<div class="xpro-quantity-wrap">';
		?>
		<button type="button" class="xpro-minus"><i class="fas fa-minus" aria-hidden="true"></i></button>
		<?php
	}

	public function after_add_to_cart() {
		?>
		<button type="button" class="xpro-plus"><i class="fas fa-plus" aria-hidden="true"></i></button>
		<?php
		echo '</div>';
	}

	/**
	 * Trigger update quantity
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function cart_quantity_plus_minus() {
		wc_enqueue_js(
			"$(document).on('click', 'button.xpro-plus, button.xpro-minus', function() {
				var qty = $(this).parent().find('.qty');
				var val = parseFloat(qty.val()) || 0;
				var max = parseFloat(qty.attr('max'));
				var min = parseFloat(qty.attr('min'));
				var step = parseFloat(qty.attr('step'));
			
				if ($(this).is('.xpro-plus')) {
					if (max && (max <= val)) {
						qty.val(max).change();
					} else {
						qty.val(val + step).change();
					}
				} else {
					if (min && (min >= val)) {
						qty.val(min).change();
					} else if (val > 1) {
						qty.val(val - step).change();
					}
				}
			});"
		);
	}

	/**
	 * mincart update
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function mini_cart_fragments( $fragments ) {
		$fragments['span.xpro-cart-btn-badge']       = '<span class="xpro-cart-btn-badge">' . WC()->cart->get_cart_contents_count() . '</span>';
		$fragments['span.xpro-mini-cart-item-count'] = '<span class="xpro-mini-cart-item-count">' . WC()->cart->get_cart_contents_count() . '</span>';
		$fragments['span.xpro-mc__btn-subtotal']     = '<span class="xpro-mc__btn-subtotal">' . WC()->cart->get_cart_subtotal() . '</span>';
		ob_start();
		?>
		<div class="xpro-mini-cart-items">
			<?php require XPRO_ELEMENTOR_ADDONS_PRO_WIDGET . 'woo-mini-cart/layout/mini-cart.php'; ?>
		</div>
		<?php
		$fragments['div.xpro-mini-cart-items'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @param array $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( XPRO_ELEMENTOR_ADDONS_BASE === $plugin_file ) {
			$row_meta    = array(
				'docs' => '<a href="https://elementor.wpxpro.com/docs/" aria-label="' . esc_attr( esc_html__( 'View Documentation', 'xpro-elementor-addons' ) ) . '" target="_blank">' . esc_html__( 'Documentation', 'xpro-elementor-addons' ) . '</a>',
			);
			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=xpro-elementor-addons' ), esc_html__( 'Settings', 'xpro-elementor-addons' ) );
		array_unshift( $links, $settings_link );

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) ) {
			$links['rate_us'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-elementor-addons-gopro">%2$s</a>', 'https://wordpress.org/plugins/xpro-elementor-addons/#reviews', esc_html__( 'Rate Us', 'xpro-elementor-addons' ) );
		} else {
			$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-elementor-addons-gopro">%2$s</a>', 'https://elementor.wpxpro.com/buy/', esc_html__( 'Go Pro', 'xpro-elementor-addons' ) );
		}

		return $links;
	}

	public function custom_item_post_type() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'core/handler-api.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/dynamic-content/custom-post-item.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/dynamic-content/custom-post-item-api.php';
	}
}

// Instantiate Xpro_Elementor_Addons Class.
Xpro_Elementor_Addons::instance();
