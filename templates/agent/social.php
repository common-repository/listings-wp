<?php
/**
 * Single agent name
 *
 * This template can be overridden by copying it to yourtheme/listings/agent/name.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$id 		= listings_wp_agent_ID();
$facebook 	= get_the_author_meta( 'facebook', $id );
$google 	= get_the_author_meta( 'google', $id );
$twitter 	= get_the_author_meta( 'twitter', $id );
$linkedin 	= get_the_author_meta( 'linkedin', $id );
$youtube 	= get_the_author_meta( 'youtube', $id );

if ( $facebook || $google || $twitter || $linkedin || $youtube  ) {
?>

	<ul class="social">
		<?php if( $facebook ) { ?>
		<li class="facebook"><a href="<?php echo esc_url( $facebook ); ?>"><i class="lwp-icon-facebook"></i></a></li>
		<?php } ?>
		<?php if( $google ) { ?>
		<li class="google"><a href="<?php echo esc_url( $google ); ?>"><i class="lwp-icon-google"></i></a></li>
		<?php } ?>
		<?php if( $twitter ) { ?>
		<li class="twitter"><a href="<?php echo esc_url( $twitter ); ?>"><i class="lwp-icon-twitter"></i></a></li>
		<?php } ?>
		<?php if( $linkedin ) { ?>
		<li class="linkedin"><a href="<?php echo esc_url( $linkedin ); ?>"><i class="lwp-icon-linkedin"></i></a></li>
		<?php } ?>
		<?php if( $youtube ) { ?>
		<li class="youtube"><a href="<?php echo esc_url( $youtube ); ?>"><i class="lwp-icon-youtube"></i></a></li>
		<?php } ?>
	</ul>

<?php } // endif