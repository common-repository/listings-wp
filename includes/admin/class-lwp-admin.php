<?php
/**
 * Listings_Wp Admin
 *
 * @class    Listings_Wp_Admin
 * @author   Listings_Wp
 * @category Admin
 * @package  Listings_Wp/Admin
 * @version  1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Listings_Wp_Admin class.
 */
class Listings_Wp_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {

		// option pages
		include_once( 'class-lwp-admin-options.php' );

		// metaboxes
		include_once( 'class-lwp-admin-metaboxes.php' );
		include_once( 'metaboxes/class-lwp-metaboxes-listing.php' );
		include_once( 'metaboxes/functions.php' );
		
		include_once( 'class-lwp-admin-enqueues.php' );
		include_once( 'class-lwp-admin-listing-menu.php' );
		include_once( 'class-lwp-admin-listing-columns.php' );
		include_once( 'class-lwp-admin-enquiry-columns.php' );
		include_once( 'class-lwp-admin-agent-columns.php' );
	}


	/**
	 * Adds one or more classes to the body tag in the dashboard.
	 *
	 * @param  String $classes Current body classes.
	 * @return String          Altered body classes.
	 */
	public function admin_body_class( $classes ) {
		
		if ( is_listings_wp_admin() ) {
			return "$classes listings_wp";
		}

	}



}

return new Listings_Wp_Admin();