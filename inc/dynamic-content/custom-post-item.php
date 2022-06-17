<?php

namespace XproElementorAddons\Control\Dynamic_Content;

use Xpro_Elementor_Addons;

defined( 'ABSPATH' ) || exit;


class Xpro_Elementor_Post_Item {


	public function __construct() {
		$this->post_type();

		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		register_activation_hook( __FILE__, array( $this, 'flush_rewrites' ) );

		add_action( 'admin_menu', array( $this, 'register_settings_submenus' ), 99 );

	}

	public function post_type() {

		$labels = array(
			'name'                  => _x( 'Templates', 'Post Type General Name', 'xpro-elementor-addons' ),
			'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'xpro-elementor-addons' ),
			'menu_name'             => esc_html__( 'Template', 'xpro-elementor-addons' ),
			'name_admin_bar'        => esc_html__( 'Xpro Templates', 'xpro-elementor-addons' ),
			'archives'              => esc_html__( 'Template Archives', 'xpro-elementor-addons' ),
			'attributes'            => esc_html__( 'Template Attributes', 'xpro-elementor-addons' ),
			'parent_item_colon'     => esc_html__( 'Parent Template:', 'xpro-elementor-addons' ),
			'all_items'             => esc_html__( 'All Templates', 'xpro-elementor-addons' ),
			'add_new_item'          => esc_html__( 'Add New Template', 'xpro-elementor-addons' ),
			'add_new'               => esc_html__( 'Add New', 'xpro-elementor-addons' ),
			'new_item'              => esc_html__( 'New Template', 'xpro-elementor-addons' ),
			'edit_item'             => esc_html__( 'Edit Template', 'xpro-elementor-addons' ),
			'update_item'           => esc_html__( 'Update Template', 'xpro-elementor-addons' ),
			'view_item'             => esc_html__( 'View Template', 'xpro-elementor-addons' ),
			'view_items'            => esc_html__( 'View Templates', 'xpro-elementor-addons' ),
			'search_items'          => esc_html__( 'Search Template', 'xpro-elementor-addons' ),
			'not_found'             => esc_html__( 'Not found', 'xpro-elementor-addons' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'xpro-elementor-addons' ),
			'featured_image'        => esc_html__( 'Featured Image', 'xpro-elementor-addons' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'xpro-elementor-addons' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'xpro-elementor-addons' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'xpro-elementor-addons' ),
			'insert_into_item'      => esc_html__( 'Insert into Template', 'xpro-elementor-addons' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Template', 'xpro-elementor-addons' ),
			'items_list'            => esc_html__( 'Items list', 'xpro-elementor-addons' ),
			'items_list_navigation' => esc_html__( 'Items list navigation', 'xpro-elementor-addons' ),
			'filter_items_list'     => esc_html__( 'Filter items list', 'xpro-elementor-addons' ),
		);
		$args   = array(
			'label'               => esc_html__( 'Templates', 'xpro-elementor-addons' ),
			'description'         => esc_html__( 'Xpro templates for dynamic content', 'xpro-elementor-addons' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'elementor' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'query_var'           => true,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rest_base'           => 'xpro-content',
		);
		register_post_type( 'xpro_content', $args );
	}

	public function flush_rewrites() {
		$this->post_type();
		flush_rewrite_rules();
	}

	public function register_settings_submenus() {
		add_submenu_page(
			Xpro_Elementor_Addons::PAGE_SLUG,
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			'edit_pages',
			'edit.php?post_type=xpro_content'
		);
	}

}

new Xpro_Elementor_Post_Item();
