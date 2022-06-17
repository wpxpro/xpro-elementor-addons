<?php

use Elementor\Plugin;
use ElementorPro\Modules\ThemeBuilder\Module;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

/**
 * @param $attachment_id
 *
 * @return array
 */

function xpro_elementor_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );

	return array(
		'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption'     => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href'        => get_permalink( $attachment->ID ),
		'src'         => $attachment->guid,
		'title'       => $attachment->post_title,
	);
}

/**
 * String Replace function
 */

function xpro_elementor_friendly_str_replace( $string ) {
	$string = str_replace( array( '[\', \']' ), '', $string );
	$string = preg_replace( '/\[.*\]/U', '', $string );
	$string = preg_replace( '/&(amp;)?#?[a-z0-9]+;/i', '-', $string );
	$string = htmlentities( $string, ENT_COMPAT, 'utf-8' );
	$string = preg_replace(
		'/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i',
		'\\1',
		$string
	);
	$string = preg_replace( array( '/[^a-z0-9]/i', '/[-]+/' ), '-', $string );

	return strtolower( trim( $string, '-' ) );
}

/**
 * Contain masking shape list
 *
 * @param $element
 *
 * @return array
 */
function xpro_elementor_masking_shape_list( $element ) {
	$dir        = XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/masking-shape/';
	$shape_name = 'shape';
	$extension  = '.svg';
	$list       = array();
	if ( 'list' === $element ) {
		for ( $i = 1; $i <= 57; $i ++ ) {
			$list[ $shape_name . $i ] = array(
				'title' => ucwords( $shape_name . ' ' . $i ),
				'url'   => $dir . $shape_name . $i . $extension,
			);
		}
	} elseif ( 'url' === $element ) {
		for ( $i = 1; $i <= 57; $i ++ ) {
			$list[ $shape_name . $i ] = $dir . $shape_name . $i . $extension;
		}
	}

	return array_merge( $list );
}

/**
 * Get the list of all section templates
 *
 * @return array
 */
function xpro_elementor_get_section_templates() {
	$items = Plugin::instance()->templates_manager->get_source( 'local' )->get_items( array( 'type' => 'section' ) );

	if ( ! empty( $items ) ) {
		$items = wp_list_pluck( $items, 'title', 'template_id' );

		return $items;
	}

	return array();
}


function xpro_elementor_render_tab_content( $content, $id ) {
	return str_replace( '.elementor-' . $id . ' ', '#elementor .elementor-' . $id . ' ', $content );
}

function xpro_elementor_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;
	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Get a list of all WPForms
 *
 * @return array
 */
function xpro_elementor_get_wpforms() {
	$forms = array();
	if ( class_exists( '\WPForms\WPForms' ) ) {
		$_forms = get_posts(
			array(
				'post_type'      => 'wpforms',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( ! empty( $_forms ) ) {
			$forms = wp_list_pluck( $_forms, 'post_title', 'ID' );
		}
	}

	return $forms;
}

/**
 * Get a list of all CF7 forms
 *
 * @return array
 */
function xpro_elementor_get_cf7_forms() {
	$forms = array();

	if ( class_exists( '\WPCF7' ) ) {
		$_forms = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( ! empty( $_forms ) ) {
			$forms = wp_list_pluck( $_forms, 'post_title', 'ID' );
		}
	}

	return $forms;
}

/**
 * Get a list of all Ninja Form
 *
 * @return array
 */
function xpor_get_ninja_forms() {
	$forms = array();

	if ( class_exists( '\Ninja_Forms' ) ) {
		$_forms = Ninja_Forms()->form()->get_forms();

		if ( ! empty( $_forms ) && ! is_wp_error( $_forms ) ) {
			foreach ( $_forms as $form ) {
				$forms[ $form->get_id() ] = $form->get_setting( 'title' );
			}
		}
	}

	return $forms;
}

/**
 * Get a list of all GravityForms
 *
 * @return array
 */
function xpro_elementor_get_gravity_forms() {
	$forms = array();

	if ( class_exists( '\GFForms' ) ) {
		$gravity_forms = RGFormsModel::get_forms( null, 'title' );

		if ( ! empty( $gravity_forms ) && ! is_wp_error( $gravity_forms ) ) {
			foreach ( $gravity_forms as $gravity_form ) {
				$forms[ $gravity_form->id ] = $gravity_form->title;
			}
		}
	}

	return $forms;
}

/**
 * Post types Options
 *
 * @return array
 */

function xpro_elementor_get_post_types() {
	$post_types = get_post_types(
		array(
			'public'            => true,
			'show_in_nav_menus' => true,
		),
		'objects'
	);
	$post_types = wp_list_pluck( $post_types, 'label', 'name' );

	return array_diff_key( $post_types, array( 'elementor_library', 'attachment' ) );
}

/**
 * Post list Options
 *
 * @return array
 */

function xpro_elementor_get_query_post_list( $post_type = 'post', $limit = - 1, $search = '' ) {

	global $wpdb;
	$where = '';
	$data  = array();

	if ( - 1 === $limit ) {
		$limit = '';
	} elseif ( 0 === $limit ) {
		$limit = 'limit 0,1';
	} else {
		$limit = $wpdb->prepare( ' limit 0,%d', esc_sql( $limit ) );
	}

	if ( 'any' === $post_type ) {
		$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );
		if ( empty( $in_search_post_types ) ) {
			$where .= ' AND 1=0 ';
		} else {
			$where .= " AND {$wpdb->posts}.post_type IN ('" . join( "', '", array_map( 'esc_sql', $in_search_post_types ) ) . "')";
		}
	} elseif ( ! empty( $post_type ) ) {
		$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
	}

	if ( ! empty( $search ) ) {
		$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
	}

	$results = $wpdb->get_results(
		sprintf( "select post_title,ID  from %s where post_status = 'publish' %s %s", $wpdb->posts, $where, $limit )  //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	);

	if ( ! empty( $results ) ) {
		foreach ( $results as $row ) {
			$data[ $row->ID ] = $row->post_title;
		}
	}

	return $data;
}

/**
 * Post authors Options
 *
 * @return array
 */

function xpro_elementor_get_authors_list() {
	$users = get_users(
		array(
			'fields' => array(
				'ID',
				'display_name',
			),
		)
	);

	if ( ! empty( $users ) ) {
		return wp_list_pluck( $users, 'display_name', 'ID' );
	}

	return array();
}

/**
 * Post Orderby Options
 *
 * @return array
 */
function xpro_elementor_get_post_orderby_options() {
	$orderby = array(
		'ID'            => 'Post ID',
		'author'        => 'Post Author',
		'title'         => 'Title',
		'date'          => 'Date',
		'modified'      => 'Last Modified Date',
		'parent'        => 'Parent Id',
		'rand'          => 'Random',
		'comment_count' => 'Comment Count',
		'menu_order'    => 'Menu Order',
	);

	return $orderby;
}

/**
 * Post args Options
 *
 * @return array
 */
function xpro_elementor_get_query_args( $settings = array(), $post_type = 'post' ) {

	$settings = wp_parse_args(
		$settings,
		array(
			'post_type'      => $post_type,
			'posts_ids'      => array(),
			'orderby'        => 'date',
			'order'          => 'desc',
			'posts_per_page' => 3,
			'offset'         => '',
			'post__not_in'   => array(),
		)
	);

	$meta_query = array();
	if ( 'yes' === $settings['post_only_image'] ) {
		$meta_query[] = array(
			'key'     => '_thumbnail_id',
			'compare' => 'EXISTS',
		);
	}

	$args = array(
		'orderby'             => $settings['orderby'],
		'order'               => $settings['order'],
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
		'posts_per_page'      => $settings['posts_per_page'],
		'offset'              => $settings['offset'],
		'meta_query'          => $meta_query,
	);

	if ( 'by_id' === $settings['post_type'] ) {
		$args['post_type'] = 'any';
		$args['post__in']  = empty( $settings['posts_ids'] ) ? array( 0 ) : $settings['posts_ids'];
	} elseif ( 'source_dynamic' === $settings['post_type'] ) {
		$args['post_type'] = get_post_type();
	} else {
		$args['post_type'] = $settings['post_type'];
		$args['tax_query'] = array();

		$taxonomies = get_object_taxonomies( $settings['post_type'], 'objects' );

		foreach ( $taxonomies as $object ) {
			$setting_key = $object->name . '_ids';

			if ( ! empty( $settings[ $setting_key ] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => $object->name,
					'field'    => 'term_id',
					'terms'    => $settings[ $setting_key ],
				);
			}
		}

		if ( ! empty( $args['tax_query'] ) ) {
			$args['tax_query']['relation'] = 'AND';
		}
	}

	if ( ! empty( $settings['authors'] ) ) {
		$args['author__in'] = $settings['authors'];
	}

	if ( ! empty( $settings['post__not_in'] ) ) {
		$args['post__not_in'] = $settings['post__not_in'];
	}

	return $args;
}

/**
 * Post Dynamic args Options
 *
 * @return array
 */

function xpro_elementor_get_dynamic_args( array $settings, array $args ) {

	if ( 'source_dynamic' === $settings['post_type'] ) {
		$data = get_queried_object();

		if ( isset( $data->post_type ) ) {
			$args['post_type']      = $data->post_type;
			$args['posts_per_page'] = get_option( 'posts_per_page' );
			$args['tax_query']      = array();
		} else {
			global $wp_query;
			$args['post_type']      = $wp_query->query_vars['post_type'];
			$args['posts_per_page'] = get_option( 'posts_per_page' );
			if ( ! empty( $wp_query->query_vars['s'] ) ) {
				$args['s']      = $wp_query->query_vars['s'];
				$args['offset'] = 0;
			}
		}

		if ( is_home() || is_archive() || is_author() || is_category() || is_search() || is_tag() ) {
			$args['post_type'] = 'post';
		}

		if ( class_exists( 'woocommerce' ) ) {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$args['post_type'] = 'product';
			}
		}

		if ( isset( $data->taxonomy ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $data->taxonomy,
				'field'    => 'term_id',
				'terms'    => $data->term_id,
			);
		}

		if ( isset( $data->taxonomy ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $data->taxonomy,
				'field'    => 'term_id',
				'terms'    => $data->term_id,
			);
		}

		if ( get_query_var( 'author' ) > 0 ) {
			$args['author__in'] = get_query_var( 'author' );
		}

		if ( '' !== get_query_var( 's' ) ) {
			$args['s'] = get_query_var( 's' );
		}

		if ( get_query_var( 'year' ) || get_query_var( 'monthnum' ) || get_query_var( 'day' ) ) {
			$args['date_query'] = array(
				'year'  => get_query_var( 'year' ) ? get_query_var( 'year' ) : null,
				'month' => get_query_var( 'monthnum' ) ? get_query_var( 'monthnum' ) : null,
				'day'   => get_query_var( 'day' ) ? get_query_var( 'day' ) : null,
			);
		}

		if ( ! empty( $args['tax_query'] ) ) {
			$args['tax_query']['relation'] = 'AND';
		}

		if ( '' !== get_query_var( 'filter_color' ) ) {
			$args['meta_query'][] = array(
				'taxonomy' => 'pa_color',
				'field'    => 'slug',
				'value'    => explode( ',', get_query_var( 'filter_color' ) ),
				'compare'  => 'IN',
			);
		}

		if ( '' !== get_query_var( 'filter_size' ) ) {
			$args['meta_query'][] = array(
				'key'     => 'pa_size',
				'field'   => 'slug',
				'value'   => explode( ',', get_query_var( 'filter_size' ) ),
				'compare' => 'IN',
			);
		}

		if ( '' !== get_query_var( 'sort' ) ) {
			$args['order'] = get_query_var( 'sort' );
		}

		if ( '' !== get_query_var( 'orderby' ) ) {

			$orderby_type = get_query_var( 'orderby' );

			switch ( get_query_var( 'orderby' ) ) {
				case 'popularity':
					$orderby_type = 'popularity';
					break;
				case 'rating':
					$orderby_type = 'rating';
					break;
				case 'date ID':
					$orderby_type = 'date';
					break;
				case 'price':
					$orderby_type = 'price';
					break;
				case 'price-desc':
					$orderby_type = 'price-desc';
					break;
			}

			$args['orderby'] = $orderby_type;

		}

		if ( '' !== get_query_var( 'min_price' ) && '' !== get_query_var( 'max_price' ) ) {
			$price                = array();
			$price[0]             = get_query_var( 'min_price' );
			$price[1]             = get_query_var( 'max_price' );
			$args['meta_query'][] = array(
				'key'     => '_regular_price',
				'value'   => $price,
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			);
		}

		if ( ! empty( $args['meta_query'] ) ) {
			$args['meta_query']['relation'] = 'AND';
		}
	}

	return $args;
}

/**
 * Get All Taxonomies
 *
 * @param array $args
 * @param string $output
 * @param bool $list
 * @param array $diff_key
 *
 * @return array|string[]|WP_Taxonomy[]
 */
function xpro_elementor_get_taxonomies( $args = array(), $output = 'object', $list = true, $diff_key = array() ) {

	$taxonomies = get_taxonomies( $args, $output );
	if ( 'object' === $output && $list ) {
		$taxonomies = wp_list_pluck( $taxonomies, 'label', 'name' );
	}

	if ( ! empty( $diff_key ) ) {
		$taxonomies = array_diff_key( $taxonomies, $diff_key );
	}

	return $taxonomies;
}

/**
 * Get Package Type
 *
 * @return string
 */

function xpro_elementor_get_package_type() {
	$type = 'free';

	if ( did_action( 'xpro_elementor_addons_pro_loaded' ) ) {
		$type = Xpro_Elementor_License::$license_activate;
	}

	return $type;
}

/**
 * Get Package Type
 */

function xpro_elementor_kses( $raw ) {
	$allowed_tags = array(
		'a'          => array(
			'class'  => array(),
			'href'   => array(),
			'rel'    => array(),
			'title'  => array(),
			'target' => array(),
		),
		'abbr'       => array(
			'title' => array(),
		),
		'b'          => array(),
		'blockquote' => array(
			'cite' => array(),
		),
		'cite'       => array(
			'title' => array(),
		),
		'code'       => array(),
		'pre'        => array(),
		'del'        => array(
			'datetime' => array(),
			'title'    => array(),
		),
		'dd'         => array(),
		'div'        => array(
			'class'                      => array(),
			'id'                         => array(),
			'title'                      => array(),
			'style'                      => array(),
			'data-template-source'       => array(),
			'data-xpro-widgetarea-key'   => array(),
			'data-xpro-widgetarea-index' => array(),
		),
		'dl'         => array(),
		'dt'         => array(),
		'em'         => array(),
		'strong'     => array(),
		'h1'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'h2'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'h3'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'h4'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'h5'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'h6'         => array(
			'id'    => array(),
			'class' => array(),
		),
		'i'          => array(
			'id'          => array(),
			'class'       => array(),
			'title'       => array(),
			'aria-hidden' => array(),
		),
		'img'        => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li'         => array(
			'class' => array(),
		),
		'ol'         => array(
			'class' => array(),
		),
		'p'          => array(
			'class' => array(),
		),
		'q'          => array(
			'cite'  => array(),
			'title' => array(),
		),
		'span'       => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'iframe'     => array(
			'width'       => array(),
			'height'      => array(),
			'scrolling'   => array(),
			'frameborder' => array(),
			'allow'       => array(),
			'src'         => array(),
			'id'          => array(),
			'class'       => array(),
		),
		'strike'     => array(),
		'br'         => array(),
		'table'      => array(),
		'thead'      => array(),
		'tbody'      => array(),
		'tfoot'      => array(),
		'tr'         => array(),
		'th'         => array(),
		'td'         => array(),
		'colgroup'   => array(),
		'col'        => array(),
		'ul'         => array(
			'class' => array(),
		),
		'svg'        => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
		),
		'g'          => array( 'fill' => true ),
		'title'      => array( 'title' => true ),
		'path'       => array(
			'd'    => true,
			'fill' => true,
		),
		'style'      => array(
			'type' => array(),
		),
	);

	echo wp_kses( $raw, $allowed_tags );
}

function get_demo_post_data() {
	$post_data = array();
	if ( ! isset( $GLOBALS['post'] ) ) {
		return $post_data;
	}
	if ( 'xpro-themer' === $GLOBALS['post']->post_type ) {
		$xpro_post_ID = $GLOBALS['post']->ID;

		$preview_post_id = get_post_meta( $xpro_post_ID, 'xpro_preview_post_id', true );
		if ( $preview_post_id ) :
			$post_data = get_post( $preview_post_id );
		else :
			$args      = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
			);
			$demo_data = get_posts( $args );
			$post_data = $demo_data[0];
		endif;
	} elseif ( 'elementor_library' === $GLOBALS['post']->post_type && class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		$document = Module::instance()->get_document( $GLOBALS['post']->ID );
		if ( $document ) {
			$preview_id = $document->get_settings( 'preview_id' );

			if ( empty( $preview_id ) ) {
				$post_data = get_post( 0 );

				return $post_data;
			}
			$post_data = get_post( $preview_id );
		}
	} else {
		$post_data = $GLOBALS['post'];
	}

	if ( empty( $post_data ) ) {
		$post_data = get_post( 0 );
	}

	return $post_data;
}