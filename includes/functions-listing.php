<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Type of listings to display (buy or rent). 
 */
function listings_wp_display() {
	$purpose = listings_wp_option( 'display_purpose' );
	$default = listings_wp_option( 'display_default' );
	
	$return = 'Sell'; // set default
	if( $purpose == 'both' ) {
		$return = $default ? $default : 'Sell';
	}
	if( $purpose == 'rent' ) {
		$return = 'Rent';
	}

	if( isset( $_GET['purpose'] ) && ! empty( $_GET['purpose'] ) ) {
		$return = $_GET['purpose'] == 'buy' ? 'Sell' : 'Rent';
	}

	return apply_filters( 'listings_wp_default_display', $return );
}


/**
 * Post classes for listings.
 */
function listings_wp_listing_post_class( $classes, $class = '', $post_id = '' ) {
	
	if ( ! $post_id || 'listing' !== get_post_type( $post_id ) ) {
		return $classes;
	}

	$listing = get_post( $post_id );

	if ( $listing ) {

		$classes[] = 'listing';
		$classes[] = 'listing-' . $listing->ID;

		if ( listings_wp_meta( 'type' ) ) {
			$classes[] = strtolower( listings_wp_meta( 'type' ) );
		}
		if ( listings_wp_meta( 'status' ) ) {
			$classes[] = strtolower( listings_wp_meta( 'status' ) );
		}
		if ( listings_wp_meta( 'purpose' ) ) {
			$classes[] = strtolower( listings_wp_meta( 'purpose' ) );
		}

		$images = listings_wp_meta( 'image_gallery' );
		if ( $images ) {
			foreach ( $images as $key => $url ) {
				if( ! empty( $url ) ) {
					$classes[] = strtolower( 'has-thumbnail' );
					break;
				}
			}
		}

		if ( listings_wp_meta( 'bedrooms' ) ) {
			$classes[] = 'beds-' . listings_wp_meta( 'bedrooms' );
		}
		if ( listings_wp_meta( 'bathrooms' ) ) {
			$classes[] = 'baths-' . listings_wp_meta( 'bathrooms' );
		}

	}

	if ( false !== ( $key = array_search( 'hentry', $classes ) ) ) {
		unset( $classes[ $key ] );
	}

	return $classes;
}

/*
 * Show Archive Page title within page content area
 */
function listings_wp_force_page_title() {
	$force = listings_wp_option( 'archives_page_title' ) ? listings_wp_option( 'archives_page_title' ) : 'no';
	return $force;
}


/*
 * Map height
 */
function listings_wp_map_height() {
	$height = listings_wp_option( 'map_height' ) ? listings_wp_option( 'map_height' ) : '300';
	return apply_filters( 'listings_wp_map_height', $height );
}

/*
 * Are we hiding an item
 */
function listings_wp_hide_item( $item ) {
	$hide = listings_wp_meta( 'hide' );
	if( ! $hide ) {
		return false;
	}
	return in_array( $item, $hide );
}

/*
 * Output the chosen tick
 */
function listings_wp_tick() {
	$icon = listings_wp_option( 'tick_icon' );
	$return = $icon != 'none' ? '<i class="lwp-icon-' . esc_attr( $icon ) . '"></i>' : '';
	return $return;
}

/*
 * Get the URL of the first image of a listing
 */
function listings_wp_get_first_image() {
	
	$gallery = listings_wp_meta( 'image_gallery' );

	if( empty( $gallery ) ) {
		$sml 	= apply_filters( 'listings_wp_default_no_image', LISTINGSWP_PLUGIN_URL . 'assets/images/no-image.jpg' );
		$alt 	= '';
	} else {
		$id 	= key( $gallery );
		$sml 	= wp_get_attachment_image_url( $id, 'lwp-sml' );
		$alt 	= get_post_meta( $id, '_wp_attachment_image_alt', true );
	}	

	return array(
		'alt' => $alt,
		'sml' => $sml,
	);
}

/*
 * Get the listing status
 */
function listings_wp_get_status() {
	
	$listing_status = listings_wp_meta( 'status' );
	$option_status 	= listings_wp_option( 'listing_status' );

	if( ! $listing_status )
		return;

	$status = null;
	foreach ($option_status as $key => $value) {
		if( in_array( $listing_status, $value ) ) {
			$status 	= isset( $value['status'] ) ? $value['status'] : null;
			$bg_color 	= isset( $value['bg_color'] ) ? $value['bg_color'] : null;
			$text_color = isset( $value['text_color'] ) ? $value['text_color'] : null;
			$icon 		= isset( $value['icon'] ) ? $value['icon'] : null;
		}
	}

	if( ! $status ){
		$status 	= $listing_status;
		$bg_color 	= '#ffffff';
		$text_color = '#444444';
		$icon 		= '';
	}

	return array(
		'status' 		=> $status,
		'bg_color' 		=> $bg_color,
		'text_color' 	=> $text_color,
		'icon' 	=> $icon,
	);
}


/**
 * Do we include the decimals
 * @since  1.0.0
 * @return string
 */
function listings_wp_include_decimals() {
	$option = get_option( 'listings_wp_options' );
	$return = isset( $option['include_decimals'] ) ? stripslashes( $option['include_decimals'] ) : 'no';
	return $return;
}

/**
 * Get the price format depending on the currency position.
 *
 * @return string
 */
function listings_wp_format_price_format() {
	$option 		= get_option( 'listings_wp_options' );
	$currency_pos 	= isset( $option['currency_position'] ) ? $option['currency_position'] : 'left';
	$format 		= '%1$s%2$s';

	switch ( $currency_pos ) {
		case 'left' :
			$format = '%1$s%2$s';
		break;
		case 'right' :
			$format = '%2$s%1$s';
		break;
		case 'left_space' :
			$format = '%1$s&nbsp;%2$s';
		break;
		case 'right_space' :
			$format = '%2$s&nbsp;%1$s';
		break;
	}

	return apply_filters( 'listings_wp_format_price_format', $format, $currency_pos );
}

/**
 * Return the currency_symbol for prices.
 * @since  1.0.0
 * @return string
 */
function listings_wp_currency_symbol() {
	$option = get_option( 'listings_wp_options' );
	$return = isset( $option['currency_symbol'] ) ? stripslashes( $option['currency_symbol'] ) : '$';
	return $return;
}

/**
 * Return the thousand separator for prices.
 * @since  1.0.0
 * @return string
 */
function listings_wp_thousand_separator() {
	$option = get_option( 'listings_wp_options' );
	$return = isset( $option['thousand_separator'] ) ? stripslashes( $option['thousand_separator'] ) : ',';
	return $return;
}

/**
 * Return the decimal separator for prices.
 * @since  1.0.0
 * @return string
 */
function listings_wp_decimal_separator() {
	$option = get_option( 'listings_wp_options' );
	$return = isset( $option['decimal_separator'] ) ? stripslashes( $option['decimal_separator'] ) : '.';
	return $return;
}

/**
 * Return the number of decimals after the decimal point.
 * @since  1.0.0
 * @return int
 */
function listings_wp_decimals() {
	$option = get_option( 'listings_wp_options' );
	$return = isset( $option['decimals'] ) ? $option['decimals'] : 2;
	return absint( $return );
}

/**
 * Trim trailing zeros off prices.
 *
 * @param mixed $price
 * @return string
 */
function listings_wp_trim_zeros( $price ) {
	return preg_replace( '/' . preg_quote( listings_wp_decimal_separator(), '/' ) . '0++$/', '', $price );
}


/**
 * Format the price with a currency symbol.
 *
 * @param float $price
 * @param array $args (default: array())
 * @return string
 */
function listings_wp_format_price( $price, $args = array() ) {
	extract( apply_filters( 'listings_wp_format_price_args', wp_parse_args( $args, array(
		'currency_symbol'  	 	=> listings_wp_currency_symbol(),
		'decimal_separator'  	=> listings_wp_decimal_separator(),
		'thousand_separator' 	=> listings_wp_thousand_separator(),
		'decimals'           	=> listings_wp_decimals(),
		'price_format'       	=> listings_wp_format_price_format(),
		'include_decimals'      => listings_wp_include_decimals()
	) ) ) );

	$return = null;
	if( $price != 0 ) {
		$negative        = $price < 0;
		$price           = apply_filters( 'listings_wp_raw_price', floatval( $negative ? $price * -1 : $price ) );
		$price           = apply_filters( 'listings_wp_formatted_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( $include_decimals == 'no' ) {
			$price = listings_wp_trim_zeros( $price );
		}
		
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="currency-symbol">' . $currency_symbol . '</span>', $price );
		$return          = '<span class="price-amount">' . $formatted_price . '</span>';
	}

	return apply_filters( 'listings_wp_format_price', $return, $price, $args );
}

/**
 * Format the price with a currency symbol.
 *
 * @param float $price
 * @param array $args (default: array())
 * @return string
 */
function listings_wp_raw_price( $price ) {
	return strip_tags( listings_wp_format_price( $price ) );
}


/*
 * Outputs the price HTML
 */
function listings_wp_price( $price ) {
	$suffix = listings_wp_meta( 'price_suffix' );
	return listings_wp_format_price( $price ) . ' ' . $suffix;
}