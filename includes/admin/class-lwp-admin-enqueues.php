<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Enqueues the required admin scripts.
 *
 */
function listings_wp_load_admin_scripts( $hook ) {
	
	$css_dir = LISTINGSWP_PLUGIN_URL . 'includes/admin/assets/css/';
	$js_dir  = LISTINGSWP_PLUGIN_URL . 'includes/admin/assets/js/';
	
	// our settings page
	if ( $hook == 'settings_page_listings_wp_options' ) {
		wp_enqueue_style( 'lwp-icons', LISTINGSWP_PLUGIN_URL . 'assets/css/listings-wp-icons.css', LISTINGSWP_VERSION );
	}

	if ( $hook == 'profile.php' || $hook == 'user-edit.php' || is_listings_wp_admin() == true ) {
		wp_enqueue_style( 'lwp-admin', $css_dir . 'listings-wp.css', LISTINGSWP_VERSION );
		
		/*
		 * Google map scripts
		 */
		$api_url = 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places';
		$key     = listings_wp_option( 'maps_api_key' );
		if ( !empty( $key ) ) {
			$api_url = $api_url . '&key=' . listings_wp_option( 'maps_api_key' );
		}
		
		if ( !listings_wp_hide_item( 'map' ) ) {
			wp_enqueue_script( 'lwp-google-maps', $api_url, array(), true );
		}
		
		
		wp_enqueue_script( 'lwp-geocomplete', $js_dir . 'jquery.geocomplete.min.js', array(), LISTINGSWP_VERSION, true );
		wp_enqueue_script( 'lwp-admin', $js_dir . 'listings-wp.js', array(), LISTINGSWP_VERSION, true );
	}
	
}
add_action( 'admin_enqueue_scripts', 'listings_wp_load_admin_scripts', 100 );
