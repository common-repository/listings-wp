<?php
/**
 * Single agent awards
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/awards.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! get_the_author_meta( 'awards', listings_wp_agent_ID() ) )
	return;
?>

<div class="awards">
	<h4><?php _e( 'Awards', 'listings-wp' ); ?></h4>
	<?php echo wpautop( wp_kses_post( get_the_author_meta( 'awards', listings_wp_agent_ID() ) ) ); ?>
</div>