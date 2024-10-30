<?php
/**
 * Single listing address
 *
 * This template can be overridden by copying it to yourtheme/listings/single-listing/address.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$agent_id = listings_wp_meta( 'agent' );

if( empty( $agent_id ) )
	return;
$phone 		= get_the_author_meta( 'phone', $agent_id );
$mobile 	= get_the_author_meta( 'mobile', $agent_id );
$email 		= get_the_author_meta( 'email', $agent_id );
$website 	= get_the_author_meta( 'url', $agent_id );
?>

<div class="agent">

	<h3><?php esc_html_e( 'Agent Details', 'listings-wp' ); ?></h3>

	<div class="avatar-wrap">
		<a href="<?php echo esc_url( get_author_posts_url( $agent_id ) ); ?>">
			<?php echo get_avatar( $agent_id, 100 ); ?>
		</a>
	</div>

	<h4 class="name">
		<a href="<?php echo esc_url( get_author_posts_url( $agent_id ) ); ?>">
			<?php esc_html( the_author_meta( 'display_name', $agent_id ) ); ?>
		</a>
	</h4>

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
	
</div>