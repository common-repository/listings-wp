<?php
/**
 * Single listing tagline
 *
 * This template can be overridden by copying it to yourtheme/listings/single-listing/tagline.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$address = listings_wp_meta( 'displayed_address' );
if( empty( $address ) )
	return;

?>

<div id="listings-wp-map" class="map" width="500" height="<?php echo esc_attr( listings_wp_map_height() ); ?>" style="height:<?php echo esc_attr( listings_wp_map_height() ); ?>px;"></div>