<?php
/**
 * Single agent avatar
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/avatar.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$agent_id 	= listings_wp_agent_ID();
$phone 		= get_the_author_meta( 'phone', $agent_id );
$mobile 	= get_the_author_meta( 'mobile', $agent_id );
$email 		= get_the_author_meta( 'email', $agent_id );
$website 	= get_the_author_meta( 'url', $agent_id );
?>

<ul class="contact">
	<?php if( $website ) { ?>
	<li class="website"><i class="lwp-icon-website"></i><?php esc_html_e( $website ); ?></li>
	<?php } ?>
	<?php if( $email ) { ?>
	<li class="email"><i class="lwp-icon-email"></i><?php esc_html_e( $email ); ?></li>
	<?php } ?>
	<?php if( $phone ) { ?>
	<li class="phone"><i class="lwp-icon-old-phone"></i><?php esc_html_e( $phone ); ?></li>
	<?php } ?>
	<?php if( $mobile ) { ?>
	<li class="mobile"><i class="lwp-icon-phone"></i><?php esc_html_e( $mobile ); ?></li>
	<?php } ?>
</ul>