<?php
/**
 * Artwork Admin functionality.
 *
 * @package ArtistPortfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SA_Artwork_Admin
 */
class SA_Artwork_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var SA_Artwork_Admin
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return SA_Artwork_Admin
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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_filter( 'manage_' . SA_Artwork_Post_Type::get_post_type() . '_posts_columns', array( $this, 'add_admin_columns' ) );
		add_action( 'manage_' . SA_Artwork_Post_Type::get_post_type() . '_posts_custom_column', array( $this, 'render_admin_columns' ), 10, 2 );
		add_filter( 'manage_edit-' . SA_Artwork_Post_Type::get_post_type() . '_sortable_columns', array( $this, 'add_sortable_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'handle_admin_sorting' ) );
	}

	/**
	 * Add admin menu for plugin information.
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=' . SA_Artwork_Post_Type::get_post_type(),
			__( 'GraphQL Query Examples', 'artist-portfolio' ),
			__( 'GraphQL Examples', 'artist-portfolio' ),
			'manage_options',
			'sa-artwork-graphql',
			array( $this, 'render_graphql_examples_page' )
		);
	}

	/**
	 * Render GraphQL examples page.
	 */
	public function render_graphql_examples_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'GraphQL Query Examples', 'artist-portfolio' ); ?></h1>
			
			<div class="notice notice-info">
				<p>
					<?php
					printf(
						/* translators: %s: WPGraphQL plugin link */
						esc_html__( 'These examples require the %s plugin to be installed and activated.', 'artist-portfolio' ),
						'<a href="https://wordpress.org/plugins/wp-graphql/" target="_blank">WPGraphQL</a>'
					);
					?>
				</p>
			</div>

			<h2><?php esc_html_e( 'Basic Artwork Query', 'artist-portfolio' ); ?></h2>
			<pre><code>query GetArtworks {
  artworks {
    nodes {
      id
      title
      content
      size
      medium
      price
      formattedPrice
      date
      isAvailable
      artworkUrl
      featuredImage {
        node {
          sourceUrl
          altText
        }
      }
      artworkCategories {
        nodes {
          name
          slug
        }
      }
      seriesItems {
        nodes {
          name
          slug
        }
      }
      gallery {
        sourceUrl
        altText
        caption
      }
    }
  }
}</code></pre>

			<h2><?php esc_html_e( 'Artwork with Dimensions', 'artist-portfolio' ); ?></h2>
			<pre><code>query GetArtworkWithDimensions {
  artworks {
    nodes {
      title
      size
      dimensions {
        width
        height
        unit
        raw
      }
    }
  }
}</code></pre>

			<h2><?php esc_html_e( 'Filter by Category', 'artist-portfolio' ); ?></h2>
			<pre><code>query GetArtworksByCategory {
  artworks(where: { taxQuery: { taxArray: [{ taxonomy: ARTWORKCATEGORY, terms: ["digital-illustration"], field: SLUG }] } }) {
    nodes {
      title
      size
      medium
      price
      artworkCategories {
        nodes {
          name
        }
      }
    }
  }
}</code></pre>

			<h2><?php esc_html_e( 'Single Artwork', 'artist-portfolio' ); ?></h2>
			<pre><code>query GetSingleArtwork($id: ID!) {
  artwork(id: $id) {
    title
    content
    size
    medium
    price
    date
    isAvailable
    dimensions {
      width
      height
      unit
    }
    featuredImage {
      node {
        sourceUrl
        altText
      }
    }
    gallery {
      sourceUrl
      altText
      caption
    }
    artworkCategories {
      nodes {
        name
        slug
      }
    }
    seriesItems {
      nodes {
        name
        slug
      }
    }
  }
}</code></pre>

			<h2><?php esc_html_e( 'Available Artworks Only', 'artist-portfolio' ); ?></h2>
			<pre><code>query GetAvailableArtworks {
  artworks {
    nodes {
      title
      price
      isAvailable
      formattedPrice
    }
  }
}</code></pre>

			<div class="notice notice-warning">
				<p>
					<strong><?php esc_html_e( 'Note:', 'artist-portfolio' ); ?></strong>
					<?php esc_html_e( 'Replace taxonomy and field values with actual values from your site. Use the WPGraphQL IDE (usually available at /graphql-ide) to test these queries.', 'artist-portfolio' ); ?>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Add custom columns to artwork admin list.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_admin_columns( $columns ) {
		$new_columns = array();
		
		// Add thumbnail column after title.
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'title' === $key ) {
				$new_columns['artwork_thumbnail'] = __( 'Image', 'artist-portfolio' );
			}
		}

		// Add other custom columns.
		$new_columns['artwork_size']     = __( 'Size', 'artist-portfolio' );
		$new_columns['artwork_medium']   = __( 'Medium', 'artist-portfolio' );
		$new_columns['artwork_price']    = __( 'Price', 'artist-portfolio' );
		$new_columns['artwork_date']     = __( 'Created', 'artist-portfolio' );
		$new_columns['artwork_gallery']  = __( 'Gallery', 'artist-portfolio' );

		return $new_columns;
	}

	/**
	 * Render custom admin columns.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_admin_columns( $column, $post_id ) {
		$meta_fields = SA_Artwork_Meta_Fields::get_meta_fields();

		switch ( $column ) {
			case 'artwork_thumbnail':
				if ( has_post_thumbnail( $post_id ) ) {
					echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
				} else {
					echo '<span class="dashicons dashicons-format-image" style="color: #ccc; font-size: 60px;"></span>';
				}
				break;

			case 'artwork_size':
				$size = get_post_meta( $post_id, $meta_fields['size'], true );
				echo esc_html( $size ? $size : '—' );
				break;

			case 'artwork_medium':
				$medium = get_post_meta( $post_id, $meta_fields['medium'], true );
				echo esc_html( $medium ? $medium : '—' );
				break;

			case 'artwork_price':
				$price = get_post_meta( $post_id, $meta_fields['price'], true );
				if ( $price ) {
					echo esc_html( $price );
				} else {
					echo '<span style="color: #999;">' . esc_html__( 'Not set', 'artist-portfolio' ) . '</span>';
				}
				break;

			case 'artwork_date':
				$date = get_post_meta( $post_id, $meta_fields['date'], true );
				if ( $date ) {
					$formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
					echo esc_html( $formatted_date );
				} else {
					echo '—';
				}
				break;

			case 'artwork_gallery':
				$gallery_ids = get_post_meta( $post_id, $meta_fields['gallery'], true );
				if ( is_array( $gallery_ids ) && ! empty( $gallery_ids ) ) {
					$count = count( $gallery_ids );
					printf(
						/* translators: %d: number of images */
						esc_html( _n( '%d image', '%d images', $count, 'artist-portfolio' ) ),
						$count
					);
				} else {
					echo '—';
				}
				break;
		}
	}

	/**
	 * Add sortable columns.
	 *
	 * @param array $columns Sortable columns.
	 * @return array Modified sortable columns.
	 */
	public function add_sortable_columns( $columns ) {
		$columns['artwork_price'] = 'artwork_price';
		$columns['artwork_date']  = 'artwork_date';
		return $columns;
	}

	/**
	 * Handle admin sorting for custom columns.
	 *
	 * @param WP_Query $query Current query.
	 */
	public function handle_admin_sorting( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'artwork_price' === $orderby ) {
			$query->set( 'meta_key', SA_Artwork_Meta_Fields::get_meta_fields()['price'] );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( 'artwork_date' === $orderby ) {
			$query->set( 'meta_key', SA_Artwork_Meta_Fields::get_meta_fields()['date'] );
			$query->set( 'orderby', 'meta_value' );
		}
	}
}
