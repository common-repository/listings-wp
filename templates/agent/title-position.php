<?php
/**
 * Single agent title/position
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/title-position.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<p class="position"><?php echo get_the_author_meta( 'title_position', listings_wp_agent_ID() ); ?></p>