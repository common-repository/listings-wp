<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Listings_Wp_Admin_Metaboxes' ) ) :

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Listings_Wp_Admin_Metaboxes {

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'register_metaboxes' ) );
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	public function register_metaboxes() {
		
		/**
		 * Load the metaboxes for listing post type
		 */
		$listing_metaboxes = new Listings_Wp_Metaboxes_Listings();
		$listing_metaboxes->get_instance();

	}

}

new Listings_Wp_Admin_Metaboxes();

endif;