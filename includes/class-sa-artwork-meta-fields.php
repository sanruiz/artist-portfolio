<?php
/**
 * Artwork Meta Fields.
 *
 * @package ArtistPortfolio
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SA_Artwork_Meta_Fields
 */
class SA_Artwork_Meta_Fields {

	/**
	 * Instance of this class.
	 *
	 * @var SA_Artwork_Meta_Fields
	 */
	private static $instance = null;

	/**
	 * Meta field keys.
	 *
	 * @var array
	 */
	const META_FIELDS = array(
		'size'    => '_sa_artwork_size',
		'medium'  => '_sa_artwork_medium',
		'price'   => '_sa_artwork_price',
		'date'    => '_sa_artwork_date',
		'gallery' => '_sa_artwork_gallery',
	);

	/**
	 * Get instance of this class.
	 *
	 * @return SA_Artwork_Meta_Fields
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
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Add meta boxes for artwork.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'sa_artwork_details',
			__( 'Artwork Details', 'artist-portfolio' ),
			array( $this, 'render_artwork_details_meta_box' ),
			SA_Artwork_Post_Type::get_post_type(),
			'normal',
			'high'
		);

		add_meta_box(
			'sa_artwork_gallery',
			__( 'Artwork Gallery', 'artist-portfolio' ),
			array( $this, 'render_artwork_gallery_meta_box' ),
			SA_Artwork_Post_Type::get_post_type(),
			'normal',
			'high'
		);
	}

	/**
	 * Render artwork details meta box.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_artwork_details_meta_box( $post ) {
		// Add nonce field.
		wp_nonce_field( 'sa_artwork_meta_nonce', 'sa_artwork_meta_nonce_field' );

		// Get current values.
		$size   = get_post_meta( $post->ID, self::META_FIELDS['size'], true );
		$medium = get_post_meta( $post->ID, self::META_FIELDS['medium'], true );
		$price  = get_post_meta( $post->ID, self::META_FIELDS['price'], true );
		$date   = get_post_meta( $post->ID, self::META_FIELDS['date'], true );

		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="sa_artwork_size"><?php esc_html_e( 'Size', 'artist-portfolio' ); ?></label>
				</th>
				<td>
					<input type="text" id="sa_artwork_size" name="sa_artwork_size" value="<?php echo esc_attr( $size ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'e.g., 24" x 36" or 61cm x 91cm', 'artist-portfolio' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="sa_artwork_medium"><?php esc_html_e( 'Medium', 'artist-portfolio' ); ?></label>
				</th>
				<td>
					<input type="text" id="sa_artwork_medium" name="sa_artwork_medium" value="<?php echo esc_attr( $medium ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'e.g., Oil on canvas, Digital, Watercolor', 'artist-portfolio' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="sa_artwork_price"><?php esc_html_e( 'Price', 'artist-portfolio' ); ?></label>
				</th>
				<td>
					<input type="text" id="sa_artwork_price" name="sa_artwork_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'e.g., $500.00 or Not for sale', 'artist-portfolio' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="sa_artwork_date"><?php esc_html_e( 'Creation Date', 'artist-portfolio' ); ?></label>
				</th>
				<td>
					<input type="date" id="sa_artwork_date" name="sa_artwork_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Date when the artwork was created', 'artist-portfolio' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render artwork gallery meta box.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_artwork_gallery_meta_box( $post ) {
		$gallery_ids = get_post_meta( $post->ID, self::META_FIELDS['gallery'], true );
		if ( ! is_array( $gallery_ids ) ) {
			$gallery_ids = array();
		}
		?>
		<div id="sa-artwork-gallery-container">
			<input type="hidden" id="sa_artwork_gallery" name="sa_artwork_gallery" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>" />
			
			<div class="sa-gallery-images" data-empty-text="<?php esc_attr_e( 'No images in gallery. Click "Add Images to Gallery" to get started.', 'artist-portfolio' ); ?>">
				<?php foreach ( $gallery_ids as $image_id ) : ?>
					<?php if ( wp_attachment_is_image( $image_id ) ) : ?>
						<div class="sa-gallery-image" data-id="<?php echo esc_attr( $image_id ); ?>">
							<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
							<button type="button" class="sa-remove-image" title="<?php esc_attr_e( 'Remove image', 'artist-portfolio' ); ?>">×</button>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<p>
				<button type="button" id="sa-add-gallery-images" class="button button-secondary">
					<?php esc_html_e( 'Add Images to Gallery', 'artist-portfolio' ); ?>
				</button>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_scripts( $hook ) {
		global $post;

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		if ( ! $post || SA_Artwork_Post_Type::get_post_type() !== $post->post_type ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script(
			'sa-artwork-admin',
			SA_ARTWORK_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			SA_ARTWORK_PLUGIN_VERSION,
			true
		);
		wp_enqueue_style(
			'sa-artwork-admin',
			SA_ARTWORK_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			SA_ARTWORK_PLUGIN_VERSION
		);
	}

	/**
	 * Save meta fields.
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_fields( $post_id ) {
		// Check if this is an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check post type.
		if ( SA_Artwork_Post_Type::get_post_type() !== get_post_type( $post_id ) ) {
			return;
		}

		// Check nonce.
		if ( ! isset( $_POST['sa_artwork_meta_nonce_field'] ) || 
			 ! wp_verify_nonce( sanitize_key( $_POST['sa_artwork_meta_nonce_field'] ), 'sa_artwork_meta_nonce' ) ) {
			return;
		}

		// Check user capabilities.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save fields.
		$this->save_field( $post_id, 'sa_artwork_size', self::META_FIELDS['size'], 'sanitize_text_field' );
		$this->save_field( $post_id, 'sa_artwork_medium', self::META_FIELDS['medium'], 'sanitize_text_field' );
		$this->save_field( $post_id, 'sa_artwork_price', self::META_FIELDS['price'], 'sanitize_text_field' );
		$this->save_field( $post_id, 'sa_artwork_date', self::META_FIELDS['date'], 'sanitize_text_field' );
		$this->save_gallery_field( $post_id );
	}

	/**
	 * Save individual field.
	 *
	 * @param int    $post_id       Post ID.
	 * @param string $field_name    Field name in $_POST.
	 * @param string $meta_key      Meta key to save to.
	 * @param string $sanitize_func Sanitization function.
	 */
	private function save_field( $post_id, $field_name, $meta_key, $sanitize_func ) {
		if ( isset( $_POST[ $field_name ] ) ) {
			$value = call_user_func( $sanitize_func, wp_unslash( $_POST[ $field_name ] ) );
			update_post_meta( $post_id, $meta_key, $value );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	/**
	 * Save gallery field.
	 *
	 * @param int $post_id Post ID.
	 */
	private function save_gallery_field( $post_id ) {
		if ( isset( $_POST['sa_artwork_gallery'] ) ) {
			$gallery_string = sanitize_text_field( wp_unslash( $_POST['sa_artwork_gallery'] ) );
			$gallery_ids    = array_filter( array_map( 'intval', explode( ',', $gallery_string ) ) );
			
			// Validate that all IDs are valid attachments.
			$valid_ids = array();
			foreach ( $gallery_ids as $id ) {
				if ( wp_attachment_is_image( $id ) ) {
					$valid_ids[] = $id;
				}
			}
			
			if ( ! empty( $valid_ids ) ) {
				update_post_meta( $post_id, self::META_FIELDS['gallery'], $valid_ids );
			} else {
				delete_post_meta( $post_id, self::META_FIELDS['gallery'] );
			}
		} else {
			delete_post_meta( $post_id, self::META_FIELDS['gallery'] );
		}
	}

	/**
	 * Get meta field keys.
	 *
	 * @return array
	 */
	public static function get_meta_fields() {
		return self::META_FIELDS;
	}
}
