<?php
/**
 * Plugin Name: Artist Portfolio
 * Plugin URI: https://example.com/artist-portfolio
 * Description: A custom post type for artist portfolio with WPGraphQL integration.
 * Version: 1.1.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: artist-portfolio
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package ArtistPortfolio
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
 * Main plugin class.
 */
class SA_Artwork_Plugin {

	/**
	 * Plugin instance.
	 *
	 * @var SA_Artwork_Plugin
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return SA_Artwork_Plugin
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
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load plugin dependencies.
	 */
	private function load_dependencies() {
		require_once SA_ARTWORK_PLUGIN_PATH . 'includes/class-sa-artwork-post-type.php';
		require_once SA_ARTWORK_PLUGIN_PATH . 'includes/class-sa-artwork-taxonomies.php';
		require_once SA_ARTWORK_PLUGIN_PATH . 'includes/class-sa-artwork-meta-fields.php';
		require_once SA_ARTWORK_PLUGIN_PATH . 'includes/class-sa-artwork-graphql.php';
		require_once SA_ARTWORK_PLUGIN_PATH . 'includes/class-sa-artwork-admin.php';
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		
		// Check for WPGraphQL dependency.
		add_action( 'admin_notices', array( $this, 'check_dependencies' ) );
	}

	/**
	 * Initialize plugin.
	 */
	public function init() {
		// Initialize components.
		SA_Artwork_Post_Type::get_instance();
		SA_Artwork_Taxonomies::get_instance();
		SA_Artwork_Meta_Fields::get_instance();
		SA_Artwork_GraphQL::get_instance();
		SA_Artwork_Admin::get_instance();
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'artist-portfolio',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Check for required dependencies.
	 */
	public function check_dependencies() {
		if ( ! class_exists( 'WPGraphQL' ) ) {
			?>
			<div class="notice notice-warning">
				<p>
					<?php
					esc_html_e(
						'Artist Portfolio plugin requires WPGraphQL to be installed and activated for full functionality.',
						'artist-portfolio'
					);
					?>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Initialize the plugin.
 */
function sa_artwork_init() {
	return SA_Artwork_Plugin::get_instance();
}

/**
 * Plugin activation hook.
 */
function sa_artwork_activate() {
	// Initialize the plugin to register post types and taxonomies.
	sa_artwork_init();
	
	// Flush rewrite rules to ensure custom post type URLs work.
	flush_rewrite_rules();
}

/**
 * Plugin deactivation hook.
 */
function sa_artwork_deactivate() {
	// Flush rewrite rules to clean up custom post type URLs.
	flush_rewrite_rules();
}

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, 'sa_artwork_activate' );
register_deactivation_hook( __FILE__, 'sa_artwork_deactivate' );

// Start the plugin.
sa_artwork_init();
