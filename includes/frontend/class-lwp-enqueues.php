<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * All Stylesheets And Scripts
 * 
 * @return void
 */
function listings_wp_enqueue_styles_scripts(){
	
	global $post;

	$url = LISTINGSWP_PLUGIN_URL;
	$ver = LISTINGSWP_VERSION;

	$css_dir 	= 'assets/css/';
	$js_dir 	= 'assets/js/';

	if( is_listings_wp() || is_author() ) {

		/*
		 * Enqueue styles
		 */
		wp_enqueue_style( 'listings-wp-icons', LISTINGSWP_PLUGIN_URL . 'assets/css/listings-wp-icons.css', array(), $ver, 'all' );
		if( is_listing() ) {
			wp_enqueue_style( 'listings-wp-lightslider', $url . $css_dir . 'lightslider.css', array(), $ver, 'all' );
			wp_enqueue_style( 'listings-wp-lightgallery', $url . $css_dir . 'lightgallery.css', array(), $ver, 'all' );
		}
		wp_enqueue_style( 'listings-wp', $url . $css_dir . 'listings-wp.css', array(), $ver, 'all' );

		/*
		 * Enqueue scripts
		 */
		if( is_listing() ) {
			wp_enqueue_script( 'listings-wp-lightslider', $url . $js_dir . 'lightslider.js', array( 'jquery' ), $ver, true );
			wp_enqueue_script( 'listings-wp-lightgallery', $url . $js_dir . 'lightgallery.js', array( 'jquery' ), $ver, true );
		}
		
		wp_enqueue_script( 'listings-wp', $url . $js_dir . 'listings-wp.js', array( 'jquery' ), $ver, true );
		
		/*
		 * Localize our script
		 */
		$localized_array = array();
		if( is_listing() ) {
			$localized_array = array(
				'gallery_mode' 	=> listings_wp_option( 'gallery_mode' ) ? listings_wp_option( 'gallery_mode' ) : 'fade',
				'auto_slide' 	=> listings_wp_option( 'auto_slide' ) == 'no' ? false : true,
				'slide_delay' 	=> listings_wp_option( 'slide_delay' ) ? listings_wp_option( 'slide_delay' ) : 3000,
				'slide_duration'=> listings_wp_option( 'slide_duration' ) ? listings_wp_option( 'slide_duration' ) : 3000,
				'thumbs_shown' 	=> listings_wp_option( 'thumbs_shown' ) ? listings_wp_option( 'thumbs_shown' ) : 6,
				'gallery_prev' 	=> listings_wp_option( 'arrow_icon' ) ? '<i class="prev lwp-icon-' . listings_wp_option( 'arrow_icon' ) . '"></i>' : '',
				'gallery_next' 	=> listings_wp_option( 'arrow_icon' ) ? '<i class="next lwp-icon-' . listings_wp_option( 'arrow_icon' ) . '"></i>' : '',
				'map_width' 	=> listings_wp_option( 'map_width' ),
				'map_height' 	=> listings_wp_option( 'map_height' ),
				'map_zoom' 		=> listings_wp_option( 'map_zoom' ),
				'lat' 			=> listings_wp_meta( 'lat', listings_wp_get_ID() ),
				'lng' 			=> listings_wp_meta( 'lng', listings_wp_get_ID() ),
				'address' 		=> listings_wp_meta( 'displayed_address', listings_wp_get_ID() ),
			);
		}

		wp_localize_script( 'listings-wp', 'listings_wp', apply_filters( 'listings_wp_localized_script', $localized_array ) );


		/*
		 * Google map scripts
		 */
		$api_url = listings_wp_google_maps_url();
		if( ! listings_wp_hide_item( 'map' ) ) {
			wp_enqueue_script( 'lwp-google-maps', $api_url );
		}


	}


}
add_action( 'wp_enqueue_scripts', 'listings_wp_enqueue_styles_scripts', 10 );