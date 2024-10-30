<?php
/**
 * Single agent description
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/description.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! get_the_author_meta( 'description', listings_wp_agent_ID() ) )
	return;
?>

<div class="description"><?php echo wpautop( wp_kses_post( get_the_author_meta( 'description', listings_wp_agent_ID() ) ) ); ?></div>