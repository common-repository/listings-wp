<?php
/**
 * Single agent specialties
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/specialties.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! get_the_author_meta( 'specialties', listings_wp_agent_ID() ) )
	return;
?>

<div class="specialties">
	<h4><?php _e( 'Specialties', 'listings-wp' ); ?></h4>
	<?php echo wpautop( wp_kses_post( get_the_author_meta( 'specialties', listings_wp_agent_ID() ) ) ); ?>
</div>