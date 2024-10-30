<?php
/**
 * The Template for displaying listing content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/listings/content-single-listing.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$columns = listings_wp_option( 'grid_columns' );
?>

<li <?php post_class( 'col-' . $columns ); ?>>
<?php
/**
 */
do_action( 'listings_wp_before_listings_loop_item_summary' );

?>

<div class="summary">

<?php

do_action( 'listings_wp_before_listings_loop_item' );

/**
 */
do_action( 'listings_wp_listings_loop_item' );


/**
 */
do_action( 'listings_wp_after_listings_loop_item' );

?>

</div>

<?php

/**
 */
do_action( 'listings_wp_after_listings_loop_item_summary' );
?>
</li>