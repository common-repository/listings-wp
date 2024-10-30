<?php
/**
 * Agents Listings
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/listings.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<h3 class="listings"><?php _e( 'Agents Listings', 'listings-wp' ); ?></h3>
<?php echo do_shortcode( '[listings_wp_listings number="5" agent="' . listings_wp_agent_ID() . '" compact="true"]' ); ?>