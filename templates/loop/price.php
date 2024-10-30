<?php
/**
 * Loop price
 *
 * This template can be overridden by copying it to yourtheme/listings/loop/price.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$price = listings_wp_meta( 'price' );
?>

<div class="price"><?php echo listings_wp_price( $price ); ?></div>