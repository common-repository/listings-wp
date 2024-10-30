<?php
/**
 * The Template for displaying all single listings
 *
 * This template can be overridden by copying it to yourtheme/listings/single-listing.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'listings' );
	
	/**
     * @hooked listings_wp_output_content_wrapper (outputs opening divs for the content)
     *
     */
	do_action( 'listings_wp_before_main_content' );

	while ( have_posts() ) : the_post();

		listings_wp_get_part( 'content-single-listing.php' );

	endwhile;

	/**
     * @hooked listings_wp_output_content_wrapper_end (outputs closing divs for the content)
     *
     */
	do_action( 'listings_wp_after_main_content' );


get_footer( 'listings' );