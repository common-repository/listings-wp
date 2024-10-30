<?php
/**
 * Single agent avatar
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/avatar.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="avatar-wrap">
	<?php echo get_avatar( listings_wp_agent_ID(), 200 ); ?>
</div>