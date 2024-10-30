<?php
/**
 * Single agent name
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/name.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<h3 class="name"><?php echo get_the_author_meta( 'display_name', listings_wp_agent_ID() ); ?></h3>