<?php
/**
 * Artwork Taxonomies.
 *
 * @package ArtistPortfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SA_Artwork_Taxonomies
 */
class SA_Artwork_Taxonomies {

	/**
	 * Instance of this class.
	 *
	 * @var SA_Artwork_Taxonomies
	 */
	private static $instance = null;

	/**
	 * Artwork category taxonomy slug.
	 *
	 * @var string
	 */
	const CATEGORY_TAXONOMY = 'artwork_category';

	/**
	 * Series taxonomy slug.
	 *
	 * @var string
	 */
	const SERIES_TAXONOMY = 'series';

	/**
	 * Get instance of this class.
	 *
	 * @return SA_Artwork_Taxonomies
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
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Register all taxonomies.
	 */
	public function register_taxonomies() {
		$this->register_artwork_category();
		$this->register_series();
	}

	/**
	 * Register artwork category taxonomy.
	 */
	private function register_artwork_category() {
		$labels = array(
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
			'menu_name'         => __( 'Artwork Categories', 'artist-portfolio' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'artwork-category' ),
			'show_in_rest'      => true,
			'rest_base'         => 'artwork_categories',
			'show_in_graphql'   => true,
			'graphql_single_name' => 'ArtworkCategory',
			'graphql_plural_name' => 'ArtworkCategories',
		);

		register_taxonomy(
			self::CATEGORY_TAXONOMY,
			SA_Artwork_Post_Type::get_post_type(),
			$args
		);
	}

	/**
	 * Register series taxonomy.
	 */
	private function register_series() {
		$labels = array(
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

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'series' ),
			'show_in_rest'      => true,
			'rest_base'         => 'series',
			'show_in_graphql'   => true,
			'graphql_single_name' => 'Series',
			'graphql_plural_name' => 'SeriesItems',
		);

		register_taxonomy(
			self::SERIES_TAXONOMY,
			SA_Artwork_Post_Type::get_post_type(),
			$args
		);
	}

	/**
	 * Get artwork category taxonomy slug.
	 *
	 * @return string
	 */
	public static function get_category_taxonomy() {
		return self::CATEGORY_TAXONOMY;
	}

	/**
	 * Get series taxonomy slug.
	 *
	 * @return string
	 */
	public static function get_series_taxonomy() {
		return self::SERIES_TAXONOMY;
	}
}
