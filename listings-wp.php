<?php
/**
 * Plugin Name: Listings WP - Real Estate Listings
 * Description: A Real Estate Listings plugin for WordPress. Create a smart real estate website quickly and easily.
 * Author: Listings WP
 * Author URI: http://listings-wp.com
 * Plugin URI: http://listings-wp.com
 * Version: 1.2.1
 * Text Domain: listings-wp
 * Domain Path: languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Listings_Wp' ) ) :

/*
 * Helper function for quick debugging
 */
if (!function_exists('pp')) {
	function pp( $array ) {
		echo '<pre style="white-space:pre-wrap;">';
			print_r( $array );
		echo '</pre>';
	}
}

/**
 * Main Listings_Wp Class.
 *
 * @since 1.0.0
 */
final class Listings_Wp {

	/**
	 * @var Listings_Wp The one true Listings_Wp
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Query instance.
	 * @since 1.0.0 
	 */
	public $query = null;

	/**
	 * Main Listings_Wp Instance.
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'listings-wp' ), '1.0.0' );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'listings-wp' ), '1.0.0' );
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'listings_wp_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Define Constants.
	 * @since  1.0.0
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir();
		$this->define( 'LISTINGSWP_PLUGIN_FILE', __FILE__ );
		$this->define( 'LISTINGSWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'LISTINGSWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'LISTINGSWP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'LISTINGSWP_VERSION', '1.2.1' );
	}

	/**
	 * Define constant if not already set.
	 * @since  1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 * @since  1.0.0
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 * @since  1.0.0
	 */
	public function includes() {

		include_once( 'includes/libraries/cmb2/init.php' );
		include_once( 'includes/libraries/cmb2-grid/Cmb2GridPlugin.php' );
		include_once( 'includes/libraries/cmb2-metatabs/cmb2_metatabs_options.php' );

		include_once( 'includes/class-lwp-install.php' );
		include_once( 'includes/class-lwp-roles.php' );
		include_once( 'includes/class-lwp-post-types.php' );
		include_once( 'includes/class-lwp-post-status.php' );
		include_once( 'includes/class-lwp-shortcodes.php' );
		include_once( 'includes/class-lwp-query.php' );
		include_once( 'includes/class-lwp-search.php' );
		include_once( 'includes/class-lwp-contact-form.php' );
		
		include_once( 'includes/class-lwp-agent.php' );
		
		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-lwp-admin.php' );		
		}

		if ( $this->is_request( 'frontend' ) ) {
			include_once( 'includes/frontend/class-lwp-frontend.php' );
		}

		include_once( 'includes/functions-general.php' );
		include_once( 'includes/functions-listing.php' );
		include_once( 'includes/functions-enquiry.php' );
		include_once( 'includes/functions-agent.php' );
		
		
	}

	/**
	 * Init Listings_Wp when WordPress Initialises.
	 * @since 1.0.0
	 */
	public function init() {
		// Before init action.
		do_action( 'before_listings_wp_init' );
		// Set up localisation.
		$this->load_plugin_textdomain();

		// Load class instances.
		$this->query = new Listings_Wp_Query();
	
		// Init action.
		do_action( 'listings_wp_init' );
	}


	/**
	 * Load Localisation files.
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'listings-wp' );

		load_textdomain( 'listings-wp', WP_LANG_DIR . '/listings-wp-' . $locale . '.mo' );
		load_plugin_textdomain( 'listings-wp', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Show row meta on the plugin screen.
	 * @since 1.0.0
	 */
	public function plugin_row_meta( $links, $file ) {
		
		if ( $file == LISTINGSWP_PLUGIN_BASENAME ) {
			
			$row_meta = array(

				'docs' => '<a href="' . esc_url( 'http://listings-wp.com/documentation?utm_source=plugin&utm_medium=plugins_page&utm_content=docs' ) . '" title="' . esc_attr( __( 'View Documentation', 'listings-wp' ) ) . '">' . __( 'Help', 'listings-wp' ) . '</a>',

				// 'get-started' => '<a href="' . esc_url( 'http://listings-wp.com/get-started' ) . '" title="' . esc_attr( __( 'View Get Started Guide', 'listings-wp' ) ) . '">' . __( 'Get Started', 'listings-wp' ) . '</a>',

				//'extensions' => '<a href="' . esc_url( 'http://listings-wp.com/extensions' ) . '" title="' . esc_attr( __( 'View Extensions', 'listings-wp' ) ) . '">' . __( 'Extensions', 'listings-wp' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

}

endif;


/**
 * Main instance of Listings_Wp.
 *
 * @since  1.0.0
 * @return Listings_Wp
 */
function Listings_Wp() {
	return Listings_Wp::instance();
}

Listings_Wp();