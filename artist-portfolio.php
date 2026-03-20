<?php
/**
 * Plugin Name: Artist Portfolio
 * Plugin URI: https://github.com/sanruiz/artist-portfolio
 * Description: A custom post type for artist portfolio with WPGraphQL integration. Uses ACF for meta fields.
 * Version: 1.3.0
 * Author: Santiago Ramirez
 * Text Domain: artist-portfolio
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define('SA_ARTWORK_PLUGIN_VERSION', '1.3.0');
define( 'SA_ARTWORK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SA_ARTWORK_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Register the artwork post type.
 */
function sa_register_artwork_post_type() {
	$labels = array(
		'name'                  => _x( 'Artworks', 'Post type general name', 'artist-portfolio' ),
		'singular_name'         => _x( 'Artwork', 'Post type singular name', 'artist-portfolio' ),
		'menu_name'             => _x( 'Artworks', 'Admin Menu text', 'artist-portfolio' ),
		'name_admin_bar'        => _x( 'Artwork', 'Add New on Toolbar', 'artist-portfolio' ),
		'add_new'               => __( 'Add New', 'artist-portfolio' ),
		'add_new_item'          => __( 'Add New Artwork', 'artist-portfolio' ),
		'new_item'              => __( 'New Artwork', 'artist-portfolio' ),
		'edit_item'             => __( 'Edit Artwork', 'artist-portfolio' ),
		'view_item'             => __( 'View Artwork', 'artist-portfolio' ),
		'all_items'             => __( 'All Artworks', 'artist-portfolio' ),
		'search_items'          => __( 'Search Artworks', 'artist-portfolio' ),
		'parent_item_colon'     => __( 'Parent Artworks:', 'artist-portfolio' ),
		'not_found'             => __( 'No artworks found.', 'artist-portfolio' ),
		'not_found_in_trash'    => __( 'No artworks found in Trash.', 'artist-portfolio' ),
		'featured_image'        => _x( 'Artwork Featured Image', 'Overrides the "Featured Image" phrase', 'artist-portfolio' ),
		'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'artist-portfolio' ),
		'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'artist-portfolio' ),
		'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'artist-portfolio' ),
		'archives'              => _x( 'Artwork archives', 'The post type archive label used in nav menus', 'artist-portfolio' ),
		'insert_into_item'      => _x( 'Insert into artwork', 'Overrides the "Insert into post" phrase', 'artist-portfolio' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this artwork', 'Overrides the "Uploaded to this post" phrase', 'artist-portfolio' ),
		'filter_items_list' => _x('Filter artworks list', 'Screen reader text', 'artist-portfolio'),
		'items_list_navigation' => _x('Artworks list navigation', 'Screen reader text', 'artist-portfolio'),
		'items_list' => _x('Artworks list', 'Screen reader text', 'artist-portfolio'),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'artwork' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-art',
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest'       => true,
		'rest_base'          => 'artworks',
		'show_in_graphql'    => true,
		'graphql_single_name' => 'Artwork',
		'graphql_plural_name' => 'Artworks',
	);

	register_post_type( 'artwork', $args );
}
add_action( 'init', 'sa_register_artwork_post_type' );

/**
 * Register taxonomies.
 */
function sa_register_artwork_taxonomies() {
	// Artwork Category
	$category_labels = array(
		'name'              => _x( 'Artwork Categories', 'taxonomy general name', 'artist-portfolio' ),
		'singular_name'     => _x( 'Artwork Category', 'taxonomy singular name', 'artist-portfolio' ),
		'search_items'      => __( 'Search Artwork Categories', 'artist-portfolio' ),
		'all_items'         => __( 'All Artwork Categories', 'artist-portfolio' ),
		'parent_item'       => __( 'Parent Artwork Category', 'artist-portfolio' ),
		'parent_item_colon' => __( 'Parent Artwork Category:', 'artist-portfolio' ),
		'edit_item'         => __( 'Edit Artwork Category', 'artist-portfolio' ),
		'update_item'       => __( 'Update Artwork Category', 'artist-portfolio' ),
		'add_new_item'      => __( 'Add New Artwork Category', 'artist-portfolio' ),
		'new_item_name'     => __( 'New Artwork Category Name', 'artist-portfolio' ),
		'menu_name'         => __( 'Categories', 'artist-portfolio' ),
	);

	register_taxonomy( 'artwork_category', 'artwork', array(
		'hierarchical'      => true,
		'labels'            => $category_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'artwork-category' ),
		'show_in_rest'      => true,
		'show_in_graphql'   => true,
		'graphql_single_name' => 'ArtworkCategory',
		'graphql_plural_name' => 'ArtworkCategories',
	));

	// Series taxonomy
	$series_labels = array(
		'name'              => _x( 'Series', 'taxonomy general name', 'artist-portfolio' ),
		'singular_name'     => _x( 'Series', 'taxonomy singular name', 'artist-portfolio' ),
		'search_items'      => __( 'Search Series', 'artist-portfolio' ),
		'all_items'         => __( 'All Series', 'artist-portfolio' ),
		'parent_item'       => __( 'Parent Series', 'artist-portfolio' ),
		'parent_item_colon' => __( 'Parent Series:', 'artist-portfolio' ),
		'edit_item'         => __( 'Edit Series', 'artist-portfolio' ),
		'update_item'       => __( 'Update Series', 'artist-portfolio' ),
		'add_new_item'      => __( 'Add New Series', 'artist-portfolio' ),
		'new_item_name'     => __( 'New Series Name', 'artist-portfolio' ),
		'menu_name'         => __( 'Series', 'artist-portfolio' ),
	);

	register_taxonomy('artwork_series', 'artwork', array(
		'hierarchical'      => true,
		'labels'            => $series_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite' => array('slug' => 'artwork-series'),
		'show_in_rest'      => true,
		'show_in_graphql'   => true,
		'graphql_single_name' => 'ArtworkSeries',
		'graphql_plural_name' => 'AllArtworkSeries',
	));
}
add_action( 'init', 'sa_register_artwork_taxonomies' );

/**
 * Register ACF fields programmatically.
 */
function sa_register_acf_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_artwork_details',
		'title' => 'Artwork Details',
		'fields' => array(
			array(
				'key' => 'field_artwork_size',
				'label' => 'Size',
				'name' => 'artwork_size',
				'type' => 'text',
				'instructions' => 'Enter the dimensions (e.g., 24" x 36")',
				'wrapper' => array('width' => '50'),
				'show_in_graphql' => 1,
			),
			array(
				'key' => 'field_artwork_medium',
				'label' => 'Medium',
				'name' => 'artwork_medium',
				'type' => 'text',
				'instructions' => 'Enter the medium (e.g., Oil on canvas)',
				'wrapper' => array('width' => '50'),
				'show_in_graphql' => 1,
			),
			array(
				'key' => 'field_artwork_price',
				'label' => 'Price',
				'name' => 'artwork_price',
				'type' => 'text',
				'instructions' => 'Enter the price',
				'wrapper' => array('width' => '50'),
				'show_in_graphql' => 1,
			),
			array(
				'key' => 'field_artwork_date',
				'label' => 'Creation Date',
				'name' => 'artwork_date',
				'type' => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format' => 'Y-m-d',
				'wrapper' => array('width' => '50'),
				'show_in_graphql' => 1,
			),
			array(
				'key' => 'field_artwork_gallery',
				'label' => 'Gallery Images',
				'name' => 'artwork_gallery',
				'type' => 'gallery',
				'instructions' => 'Add multiple images. Drag to reorder.',
				'return_format' => 'id',
				'preview_size' => 'medium',
				'insert' => 'append',
				'library' => 'all',
				'show_in_graphql' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'artwork',
				),
			),
		),
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'show_in_graphql' => 1,
		'graphql_field_name' => 'artworkDetails',
	));
}
add_action('acf/init', 'sa_register_acf_fields');

/**
 * Add WPGraphQL support for ACF fields.
 */
function sa_register_graphql_acf_fields()
{
	if (!class_exists('WPGraphQL') || !function_exists('get_field')) {
		return;
	}

	register_graphql_field( 'Artwork', 'size', array(
		'type' => 'String',
		'description' => 'The size/dimensions of the artwork',
		'resolve' => function( $post ) {
			return get_field('artwork_size', $post->ID) ?: '';
		}
	));

	register_graphql_field( 'Artwork', 'medium', array(
		'type' => 'String',
		'description' => 'The medium used',
		'resolve' => function( $post ) {
			return get_field('artwork_medium', $post->ID) ?: '';
		}
	));

	register_graphql_field( 'Artwork', 'price', array(
		'type' => 'String',
		'description' => 'The price',
		'resolve' => function( $post ) {
			return get_field('artwork_price', $post->ID) ?: '';
		}
	));

	register_graphql_field('Artwork', 'artworkDate', array(
		'type' => 'String',
		'description' => 'Creation date',
		'resolve' => function( $post ) {
			return get_field('artwork_date', $post->ID) ?: '';
		}
	));

	// Register gallery as array of image data
	register_graphql_field('Artwork', 'galleryImages', array(
		'type' => array('list_of' => 'ArtworkGalleryImage'),
		'description' => 'Gallery images with full details',
		'resolve' => function ($post) {
			$gallery = get_field('artwork_gallery', $post->ID, false);
			if (empty($gallery) || !is_array($gallery)) {
				return array();
			}
			$images = array();
			foreach ($gallery as $image_id) {
				$id = is_array($image_id) ? $image_id['ID'] : intval($image_id);
				if ($id) {
					$attachment = get_post($id);
					if ($attachment) {
						$images[] = array(
							'id' => $id,
							'sourceUrl' => wp_get_attachment_url($id),
							'altText' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: '',
							'title' => $attachment->post_title,
							'caption' => $attachment->post_excerpt,
							'srcSet' => wp_get_attachment_image_srcset($id, 'full'),
							'sizes' => wp_get_attachment_image_sizes($id, 'full'),
						);
					}
				}
			}
			return $images;
		}
	));

	// Register the custom type for gallery images
	register_graphql_object_type('ArtworkGalleryImage', array(
		'description' => 'An image in the artwork gallery',
		'fields' => array(
			'id' => array('type' => 'Int'),
			'sourceUrl' => array('type' => 'String'),
			'altText' => array('type' => 'String'),
			'title' => array('type' => 'String'),
			'caption' => array('type' => 'String'),
			'srcSet' => array('type' => 'String'),
			'sizes' => array('type' => 'String'),
		),
	));
}
add_action('graphql_register_types', 'sa_register_graphql_acf_fields');

/**
 * Admin columns.
 */
function sa_artwork_admin_columns($columns)
{
	$new = array();
	foreach ($columns as $key => $value) {
		$new[$key] = $value;
		if ('title' === $key) {
			$new['artwork_thumbnail'] = 'Image';
		}
	}
	$new['artwork_medium'] = 'Medium';
	$new['artwork_price'] = 'Price';
	return $new;
}
add_filter('manage_artwork_posts_columns', 'sa_artwork_admin_columns');

function sa_artwork_admin_column_content($column, $post_id)
{
	switch ($column) {
		case 'artwork_thumbnail':
			echo has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, array(50, 50)) : '—';
			break;
		case 'artwork_medium':
			echo esc_html(function_exists('get_field') ? get_field('artwork_medium', $post_id) : '') ?: '—';
			break;
		case 'artwork_price':
			echo esc_html(function_exists('get_field') ? get_field('artwork_price', $post_id) : '') ?: '—';
			break;
	}
}
add_action('manage_artwork_posts_custom_column', 'sa_artwork_admin_column_content', 10, 2);

/**
 * Check dependencies.
 */
function sa_artwork_check_dependencies() {
	$missing = array();
	if (!class_exists('ACF') && !function_exists('get_field')) {
		$missing[] = 'Advanced Custom Fields (ACF)';
	}
	if ( ! class_exists( 'WPGraphQL' ) ) {
		$missing[] = 'WPGraphQL';
	}
	if (!empty($missing)) {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>Artist Portfolio:</strong> Please install: ' . implode(', ', $missing) . '</p></div>';
	}
}
add_action( 'admin_notices', 'sa_artwork_check_dependencies' );

/**
 * Activation/Deactivation.
 */
function sa_artwork_activate() {
	sa_register_artwork_post_type();
	sa_register_artwork_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sa_artwork_activate' );

function sa_artwork_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'sa_artwork_deactivate' );
