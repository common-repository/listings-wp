<?php
/**
 * Loop at a glance
 *
 * This template can be overridden by copying it to yourtheme/listings/loop/at-a-glance.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$bedrooms 	= listings_wp_meta( 'bedrooms' );
$bathrooms 	= listings_wp_meta( 'bathrooms' );
$cars 		= listings_wp_meta( 'car_spaces' );
$bed_icon 	= listings_wp_option( 'bed_icon' );
$bath_icon 	= listings_wp_option( 'bath_icon' );
$car_icon 	= listings_wp_option( 'car_icon' );

if( empty( $bedrooms ) &&  empty( $bathrooms ) &&  empty( $cars ) )
	return;
?>

<div class="glance">

	<?php if( $bedrooms ) { ?>
	<div class="beds">
		<span class="count"><?php echo esc_html( $bedrooms ); ?></span> <?php echo $bed_icon != 'none' ? '<i class="lwp-icon-' . esc_attr( $bed_icon ) . '"></i>' : __( 'Bedroom', 'listings-wp' ); ?>
	</div>
	<?php } ?>

	<?php if( $bathrooms ) { ?>
	<div class="baths">
		<span class="count"><?php echo esc_html( $bathrooms ); ?></span> <?php echo $bath_icon != 'none' ? '<i class="lwp-icon-' . esc_attr( $bath_icon ) . '"></i>' : __( 'Bathroom', 'listings-wp' ); ?>
	</div>
	<?php } ?>

	<?php if( $cars ) { ?>
	<div class="cars">
		<span class="count"><?php echo esc_html( $cars ); ?></span> <?php echo $car_icon != 'none' ? '<i class="lwp-icon-' . esc_attr( $car_icon ) . '"></i>' : __( 'Car Spaces', 'listings-wp' ); ?>
	</div>
	<?php } ?>

</div>