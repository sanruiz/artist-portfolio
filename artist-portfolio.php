<?php
/**
 * Plugin Name: Artist Portfolio
 * Plugin URI: https://github.com/sanruiz/artist-portfolio
 * Description: A custom post type for artist portfolio with WPGraphQL integration.
 * Version: 1.1.0
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
define( 'SA_ARTWORK_PLUGIN_VERSION', '1.1.0' );
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

	// Series
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

	register_taxonomy( 'series', 'artwork', array(
		'hierarchical'      => true,
		'labels'            => $series_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'series' ),
		'show_in_rest'      => true,
		'show_in_graphql'   => true,
		'graphql_single_name' => 'Series',
		'graphql_plural_name' => 'SeriesItems',
	));
}
add_action( 'init', 'sa_register_artwork_taxonomies' );

/**
 * Add meta boxes.
 */
function sa_artwork_add_meta_boxes() {
	add_meta_box(
		'sa_artwork_details',
		__( 'Artwork Details', 'artist-portfolio' ),
		'sa_artwork_details_meta_box_callback',
		'artwork',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sa_artwork_add_meta_boxes' );

/**
 * Meta box callback function.
 */
function sa_artwork_details_meta_box_callback( $post ) {
	wp_nonce_field( 'sa_artwork_meta_nonce', 'sa_artwork_meta_nonce_field' );

	$size = get_post_meta( $post->ID, '_sa_artwork_size', true );
	$medium = get_post_meta( $post->ID, '_sa_artwork_medium', true );
	$price = get_post_meta( $post->ID, '_sa_artwork_price', true );
	$date = get_post_meta( $post->ID, '_sa_artwork_date', true );

	?>
	<table class="form-table">
		<tr>
			<th><label for="sa_artwork_size"><?php _e( 'Size', 'artist-portfolio' ); ?></label></th>
			<td><input type="text" id="sa_artwork_size" name="sa_artwork_size" value="<?php echo esc_attr( $size ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="sa_artwork_medium"><?php _e( 'Medium', 'artist-portfolio' ); ?></label></th>
			<td><input type="text" id="sa_artwork_medium" name="sa_artwork_medium" value="<?php echo esc_attr( $medium ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="sa_artwork_price"><?php _e( 'Price', 'artist-portfolio' ); ?></label></th>
			<td><input type="text" id="sa_artwork_price" name="sa_artwork_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="sa_artwork_date"><?php _e( 'Creation Date', 'artist-portfolio' ); ?></label></th>
			<td><input type="date" id="sa_artwork_date" name="sa_artwork_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text" /></td>
		</tr>
	</table>
	<?php
}

/**
 * Save meta box data.
 */
function sa_artwork_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['sa_artwork_meta_nonce_field'] ) || 
		 ! wp_verify_nonce( $_POST['sa_artwork_meta_nonce_field'], 'sa_artwork_meta_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['sa_artwork_size'] ) ) {
		update_post_meta( $post_id, '_sa_artwork_size', sanitize_text_field( $_POST['sa_artwork_size'] ) );
	}

	if ( isset( $_POST['sa_artwork_medium'] ) ) {
		update_post_meta( $post_id, '_sa_artwork_medium', sanitize_text_field( $_POST['sa_artwork_medium'] ) );
	}

	if ( isset( $_POST['sa_artwork_price'] ) ) {
		update_post_meta( $post_id, '_sa_artwork_price', sanitize_text_field( $_POST['sa_artwork_price'] ) );
	}

	if ( isset( $_POST['sa_artwork_date'] ) ) {
		update_post_meta( $post_id, '_sa_artwork_date', sanitize_text_field( $_POST['sa_artwork_date'] ) );
	}
}
add_action( 'save_post', 'sa_artwork_save_meta_box_data' );

/**
 * Add WPGraphQL fields.
 */
function sa_artwork_register_graphql_fields() {
	if ( ! class_exists( 'WPGraphQL' ) ) {
		return;
	}

	register_graphql_field( 'Artwork', 'size', array(
		'type' => 'String',
		'description' => __( 'The size/dimensions of the artwork', 'artist-portfolio' ),
		'resolve' => function( $post ) {
			return get_post_meta( $post->ID, '_sa_artwork_size', true );
		}
	));

	register_graphql_field( 'Artwork', 'medium', array(
		'type' => 'String',
		'description' => __( 'The medium used to create the artwork', 'artist-portfolio' ),
		'resolve' => function( $post ) {
			return get_post_meta( $post->ID, '_sa_artwork_medium', true );
		}
	));

	register_graphql_field( 'Artwork', 'price', array(
		'type' => 'String',
		'description' => __( 'The price of the artwork', 'artist-portfolio' ),
		'resolve' => function( $post ) {
			return get_post_meta( $post->ID, '_sa_artwork_price', true );
		}
	));

	register_graphql_field( 'Artwork', 'date', array(
		'type' => 'String',
		'description' => __( 'The date when the artwork was created', 'artist-portfolio' ),
		'resolve' => function( $post ) {
			$date = get_post_meta( $post->ID, '_sa_artwork_date', true );
			if ( $date ) {
				$timestamp = strtotime( $date );
				if ( $timestamp ) {
					return gmdate( 'c', $timestamp );
				}
			}
			return $date;
		}
	));
}
add_action( 'graphql_register_types', 'sa_artwork_register_graphql_fields' );

/**
 * Check dependencies.
 */
function sa_artwork_check_dependencies() {
	if ( ! class_exists( 'WPGraphQL' ) ) {
		?>
		<div class="notice notice-warning">
			<p>
				<?php _e( 'Artist Portfolio plugin requires WPGraphQL to be installed and activated for full functionality.', 'artist-portfolio' ); ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'sa_artwork_check_dependencies' );

/**
 * Plugin activation.
 */
function sa_artwork_activate() {
	sa_register_artwork_post_type();
	sa_register_artwork_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sa_artwork_activate' );

/**
 * Plugin deactivation.
 */
function sa_artwork_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'sa_artwork_deactivate' );
