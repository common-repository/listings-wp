<?php
/**
 * Loop single image
 *
 * This template can be overridden by copying it to yourtheme/listings/loop/image.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$image 	= listings_wp_get_first_image();
$status = listings_wp_get_status();
?>

<div class="image">
	<a href="<?php esc_url( the_permalink() ); ?>" title="<?php esc_attr( the_title() ); ?>">
		<?php if( $status ) { ?>
		<span style="background:<?php echo esc_attr( $status['bg_color'] ); ?>;color:<?php echo esc_attr( $status['text_color'] ); ?>" class="status <?php echo esc_attr( $status['status'] ); ?>">
			<?php if( $status['icon'] ) { ?>
			<i class="<?php echo esc_attr( $status['icon'] ); ?>"></i>
			<?php } ?>
			<?php echo esc_html( $status['status'] ); ?>
		</span>
		<?php } ?>
		<img alt="<?php echo esc_attr( $image['alt'] ); ?>" src="<?php echo esc_url( $image['sml'] ); ?>" />
	</a>
</div>