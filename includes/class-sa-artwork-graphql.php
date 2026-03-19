<?php
/**
 * Artwork GraphQL Integration.
 *
 * @package ArtistPortfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SA_Artwork_GraphQL
 */
class SA_Artwork_GraphQL {

	/**
	 * Instance of this class.
	 *
	 * @var SA_Artwork_GraphQL
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return SA_Artwork_GraphQL
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
		add_action( 'graphql_register_types', array( $this, 'register_graphql_fields' ) );
	}

	/**
	 * Register GraphQL fields for artwork.
	 */
	public function register_graphql_fields() {
		// Only register if WPGraphQL is active.
		if ( ! class_exists( 'WPGraphQL' ) ) {
			return;
		}

		$meta_fields = SA_Artwork_Meta_Fields::get_meta_fields();

		// Register size field.
		register_graphql_field(
			'Artwork',
			'size',
			array(
				'type'        => 'String',
				'description' => __( 'The size/dimensions of the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) use ( $meta_fields ) {
					return get_post_meta( $post->ID, $meta_fields['size'], true );
				},
			)
		);

		// Register medium field.
		register_graphql_field(
			'Artwork',
			'medium',
			array(
				'type'        => 'String',
				'description' => __( 'The medium used to create the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) use ( $meta_fields ) {
					return get_post_meta( $post->ID, $meta_fields['medium'], true );
				},
			)
		);

		// Register price field.
		register_graphql_field(
			'Artwork',
			'price',
			array(
				'type'        => 'String',
				'description' => __( 'The price of the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) use ( $meta_fields ) {
					return get_post_meta( $post->ID, $meta_fields['price'], true );
				},
			)
		);

		// Register date field.
		register_graphql_field(
			'Artwork',
			'date',
			array(
				'type'        => 'String',
				'description' => __( 'The date when the artwork was created', 'artist-portfolio' ),
				'resolve'     => function( $post ) use ( $meta_fields ) {
					$date = get_post_meta( $post->ID, $meta_fields['date'], true );
					if ( $date ) {
						// Convert to ISO 8601 format if it's a valid date.
						$timestamp = strtotime( $date );
						if ( $timestamp ) {
							return gmdate( 'c', $timestamp );
						}
					}
					return $date;
				},
			)
		);

		// Register gallery field.
		register_graphql_field(
			'Artwork',
			'gallery',
			array(
				'type'        => array( 'list_of' => 'MediaItem' ),
				'description' => __( 'Gallery images for the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post, $args, $context, $info ) use ( $meta_fields ) {
					$gallery_ids = get_post_meta( $post->ID, $meta_fields['gallery'], true );
					
					if ( ! is_array( $gallery_ids ) || empty( $gallery_ids ) ) {
						return null;
					}

					$media_items = array();
					foreach ( $gallery_ids as $image_id ) {
						if ( wp_attachment_is_image( $image_id ) ) {
							$media_items[] = $context->get_loader( 'post' )->load_deferred( $image_id );
						}
					}

					return $media_items;
				},
			)
		);

		// Register additional fields for better GraphQL experience.
		$this->register_additional_fields();
	}

	/**
	 * Register additional helpful GraphQL fields.
	 */
	private function register_additional_fields() {
		// Register artwork URL field.
		register_graphql_field(
			'Artwork',
			'artworkUrl',
			array(
				'type'        => 'String',
				'description' => __( 'The permalink URL of the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) {
					return get_permalink( $post->ID );
				},
			)
		);

		// Register artwork status field.
		register_graphql_field(
			'Artwork',
			'isAvailable',
			array(
				'type'        => 'Boolean',
				'description' => __( 'Whether the artwork is available for purchase', 'artist-portfolio' ),
				'resolve'     => function( $post ) {
					$price = get_post_meta( $post->ID, SA_Artwork_Meta_Fields::get_meta_fields()['price'], true );
					// Simple logic: if price is empty or contains "not for sale", it's not available.
					if ( empty( $price ) || false !== stripos( $price, 'not for sale' ) || false !== stripos( $price, 'sold' ) ) {
						return false;
					}
					return true;
				},
			)
		);

		// Register formatted price field.
		register_graphql_field(
			'Artwork',
			'formattedPrice',
			array(
				'type'        => 'String',
				'description' => __( 'The formatted price of the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) {
					$price = get_post_meta( $post->ID, SA_Artwork_Meta_Fields::get_meta_fields()['price'], true );
					if ( empty( $price ) ) {
						return __( 'Price on request', 'artist-portfolio' );
					}
					return $price;
				},
			)
		);

		// Register artwork dimensions as separate width/height if size follows pattern.
		register_graphql_field(
			'Artwork',
			'dimensions',
			array(
				'type'        => 'ArtworkDimensions',
				'description' => __( 'Parsed dimensions of the artwork', 'artist-portfolio' ),
				'resolve'     => function( $post ) {
					$size = get_post_meta( $post->ID, SA_Artwork_Meta_Fields::get_meta_fields()['size'], true );
					return $this->parse_dimensions( $size );
				},
			)
		);

		// Register custom dimensions type.
		register_graphql_object_type(
			'ArtworkDimensions',
			array(
				'description' => __( 'Artwork dimensions', 'artist-portfolio' ),
				'fields'      => array(
					'width'  => array(
						'type'        => 'String',
						'description' => __( 'Width of the artwork', 'artist-portfolio' ),
					),
					'height' => array(
						'type'        => 'String',
						'description' => __( 'Height of the artwork', 'artist-portfolio' ),
					),
					'unit'   => array(
						'type'        => 'String',
						'description' => __( 'Unit of measurement', 'artist-portfolio' ),
					),
					'raw'    => array(
						'type'        => 'String',
						'description' => __( 'Raw size string', 'artist-portfolio' ),
					),
				),
			)
		);
	}

	/**
	 * Parse dimensions from size string.
	 *
	 * @param string $size Size string like "24\" x 36\"" or "61cm x 91cm".
	 * @return array
	 */
	private function parse_dimensions( $size ) {
		if ( empty( $size ) ) {
			return array(
				'width'  => null,
				'height' => null,
				'unit'   => null,
				'raw'    => null,
			);
		}

		// Try to match patterns like "24\" x 36\"", "24" x 36"", "61cm x 91cm", etc.
		$patterns = array(
			'/(\d+(?:\.\d+)?)\s*["\u201D]?\s*[x×]\s*(\d+(?:\.\d+)?)\s*["\u201D]?\s*$/i', // inches with quotes.
			'/(\d+(?:\.\d+)?)\s*(cm|mm|in|inches?)\s*[x×]\s*(\d+(?:\.\d+)?)\s*(cm|mm|in|inches?)\s*$/i', // with units.
		);

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $size, $matches ) ) {
				if ( 3 === count( $matches ) ) {
					// Pattern 1: dimensions with quotes (inches assumed).
					return array(
						'width'  => $matches[1],
						'height' => $matches[2],
						'unit'   => 'inches',
						'raw'    => $size,
					);
				} elseif ( 5 === count( $matches ) ) {
					// Pattern 2: dimensions with explicit units.
					return array(
						'width'  => $matches[1],
						'height' => $matches[3],
						'unit'   => $matches[2], // Assuming both dimensions use the same unit.
						'raw'    => $size,
					);
				}
			}
		}

		// If no pattern matches, return raw value.
		return array(
			'width'  => null,
			'height' => null,
			'unit'   => null,
			'raw'    => $size,
		);
	}
}
