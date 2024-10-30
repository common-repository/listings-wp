<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get the id af any item (used only to localize address for shortcodes)
 */
function listings_wp_get_ID() {
	$post_id = null;

	if( ! $post_id )
		$post_id = listings_wp_shortcode_att( 'id', 'listings_wp_listing' );

	if( ! $post_id )
		$post_id = listings_wp_shortcode_att( 'id', 'listings_wp_agent' );

	if( ! $post_id )
		$post_id = get_the_ID();

	return $post_id;
}

/**
 * Get the meta af any item
 */
function listings_wp_meta( $meta, $post_id = 0 ) {
	if( ! $post_id )
		$post_id = get_the_ID();
	$meta_key = '_lwp_listing_' . $meta;
	$data = get_post_meta( $post_id, $meta_key, true );
	return $data;
}

/**
 * Get any option
 */
function listings_wp_option( $option ) {
	$options = get_option( 'listings_wp_options' );
	$return = isset( $options[$option] ) ? $options[$option] : false;
	return $return;
}

/**
 * Return an attribute value from any shortcode
 */
function listings_wp_shortcode_att( $attribute, $shortcode ) {
	
	global $post;

	if( ! $attribute && ! $shortcode )
		return;

	if( has_shortcode( $post->post_content, $shortcode ) ) {
			
	    $pattern = get_shortcode_regex();
	    if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
	        && array_key_exists( 2, $matches )
	        && in_array( $shortcode, $matches[2] ) )
	    {
	        
	        $key = array_search( $shortcode, $matches[2], true );

	        if( $matches[3][$key] ) {
	        	$att = str_replace( $attribute . '="', "", trim( $matches[3][$key] ) );
	    		$att = str_replace( '"', '', $att );
	    		
	    		if ( isset ( $att ) ) {
		            return $att;
	    		}

	        }

	    }
	}

}


/**
 * is_listings_wp_admin - Returns true if on a listings page in the admin
 */
function is_listings_wp_admin() {
	$post_type 	= get_post_type();
	$screen 	= get_current_screen();
	$return = false;
	
	if( in_array( $post_type, array( 'listing', 'listing-enquiry' ) ) ) {
		$return = true;
	}

	if ( in_array( $screen->id, array( 'listing', 'edit-listing', 'listing-enquiry', 'edit-listing-enquiry', 'settings_page_listings_wp_options' ) ) ) {
		$return = true;
	}

	return apply_filters( 'is_listings_wp_admin', $return );
}

/**
 * is_listings_wp - Returns true if on a page which uses listings_wp templates
 */
function is_listings_wp() {

	// include on agents page
	$is_agent = false;
	if ( is_author() ) {
		$user = new WP_User( listings_wp_agent_ID() );
		$user_roles = $user->roles;
		if ( in_array( 'listings_wp_agent', $user_roles ) ) {
			$is_agent = true;
		}
	}

	$result = apply_filters( 'is_listings_wp', ( is_listing_archive() || is_listing() || is_listing_search() || $is_agent ) ? true : false );

	return $result;
}

/**
 * is_listing_archive - Returns true when viewing the listing type archive.
 */
if ( ! function_exists( 'is_listing_archive' ) ) {
	function is_listing_archive() {
		return ( is_post_type_archive( 'listing' ) );
	}
}

/**
 * is_lisitng - Returns true when viewing a single listing.
 */
if ( ! function_exists( 'is_listing' ) ) {
	function is_listing() {
		$result = false;
		if( is_singular( 'listing' ) ) {
			$result = true;
		}
		if( is_single() && get_post_type() == 'listing' ) {
			$result = true;
		}
		return apply_filters( 'is_listing', $result );
	}
}
/**
 * is_lisitng - Returns true when viewing listings search results page
 */
if ( ! function_exists( 'is_listing_search' ) ) {
	function is_listing_search() {
		if( ! is_search() )
			return false;
        $current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		return $current_page->name == 'listing';
	}
}


add_action( 'init', 'listings_wp_add_new_image_sizes', 11 );

function listings_wp_add_new_image_sizes() {
	add_theme_support( 'post-thumbnails' );
    add_image_size( 'lwp-lge', 1200, 900, array( 'center', 'center' ) ); //main
    add_image_size( 'lwp-sml', 400, 300, array( 'center', 'center' ) ); //thumb
    //pp( get_intermediate_image_sizes() );
}

/*
 * Run date formatting through here
 */
function listings_wp_format_date( $date ) {
	$timestamp = strtotime( $date );
	$date = date_i18n( get_option( 'date_format' ), $timestamp, false );
	return apply_filters( 'listings_wp_format_date', $date, $timestamp );
}


/*
 * Build Google maps URL
 */
function listings_wp_google_maps_url() {
	$api_url 	= 'https://maps.googleapis.com/maps/api/js?v=3.exp';
	$key 		= listings_wp_option( 'maps_api_key' );
	if( ! empty( $key ) ) {
		$api_url = $api_url . '&key=' . $key;
	}
	return $api_url;
}

/*
 * Build Google maps Geocode URL
 */
function listings_wp_google_geocode_maps_url( $address ) {
	$api_url 	= "https://maps.google.com/maps/api/geocode/json?address={$address}";
	$key 		= listings_wp_option( 'maps_api_key' );
	$country 	= listings_wp_search_country();
	if( ! empty( $key ) ) {
		$api_url = $api_url . '&key=' . $key;
	}
	if( ! empty( $country ) ) {
		$api_url = $api_url . '&components=country:' . $country;
	}
	return apply_filters( 'listings_wp_google_geocode_maps_url', $api_url );
}

/*
 * Get search country
 */
function listings_wp_search_country() {
	$country = listings_wp_option( 'search_country' ) ? listings_wp_option( 'search_country' ) : '';
	return apply_filters( 'listings_wp_search_country', $country );
}
/*
 * Get distance measurement
 */
function listings_wp_distance_measurement() {
	$measurement = listings_wp_option( 'distance_measurement' ) ? listings_wp_option( 'distance_measurement' ) : 'kilometers';
	return apply_filters( 'listings_wp_distance_measurement', $measurement );
}
/*
 * Get search radius
 */
function listings_wp_search_radius() {
	$search_radius = listings_wp_option( 'search_radius' ) ? listings_wp_option( 'search_radius' ) : 20;
	return apply_filters( 'listings_wp_search_radius', $search_radius );
}