<?php
/**
 * Artwork Custom Post Type.
 *
 * @package ArtistPortfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SA_Artwork_Post_Type
 */
class SA_Artwork_Post_Type {

	/**
	 * Instance of this class.
	 *
	 * @var SA_Artwork_Post_Type
	 */
	private static $instance = null;

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'artwork';

	/**
	 * Get instance of this class.
	 *
	 * @return SA_Artwork_Post_Type
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register the artwork custom post type.
	 */
	public function register_post_type() {
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
			'filter_items_list'     => _x( 'Filter artworks list', 'Screen reader text for the filter links', 'artist-portfolio' ),
			'items_list_navigation' => _x( 'Artworks list navigation', 'Screen reader text for the pagination', 'artist-portfolio' ),
			'items_list'            => _x( 'Artworks list', 'Screen reader text for the items list', 'artist-portfolio' ),
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
			'menu_position'      => 20, // Below Pages
			'menu_icon'          => 'dashicons-art',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest'       => true,
			'rest_base'          => 'artworks',
			'show_in_graphql'    => true,
			'graphql_single_name' => 'Artwork',
			'graphql_plural_name' => 'Artworks',
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Get post type slug.
	 *
	 * @return string
	 */
	public static function get_post_type() {
		return self::POST_TYPE;
	}
}
