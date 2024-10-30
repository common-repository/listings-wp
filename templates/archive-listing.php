<?php
/**
 * The Template for displaying the listings archive
 *
 * This template can be overridden by copying it to yourtheme/listings/archive-listing.php.
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

        /**
         * @hooked listings_wp_listing_archive_description (displays any content, including shortcodes, within the main content editor of your chosen listing archive page)
         * 
         */
        do_action( 'listings_wp_archive_page_content' );

    if ( have_posts() ) : 
        
        /**
         * @hooked listings_wp_ordering (the ordering dropdown)
         * @hooked listings_wp_pagination (the pagination)
         * 
         */
        do_action( 'listings_wp_before_listings_loop' ); 

        $columns = listings_wp_option( 'grid_columns' );

            $count = 1;
            while ( have_posts() ) : the_post(); 

                // wrapper for our columns
                if ( $count % $columns == 1 ) 
                     echo '<ul class="listings-wp-items">';

                    listings_wp_get_part( 'content-listing.php' );

                // wrapper for our columns
                if ( $count % $columns == 0 )
                    echo '</ul>';

            $count++;
            endwhile; 
            
            if ( $count % $columns != 1 ) echo '</ul>';
            
        /**
         * @hooked listings_wp_pagination (the pagination)
         * 
         */
        do_action( 'listings_wp_after_listings_loop' );  

    else : ?>

        <p class="alert listings-wp-no-results"><?php _e( 'Sorry, no listings were found.', 'listings-wp' ); ?></p>

    <?php endif; 

    /**
     * @hooked listings_wp_output_content_wrapper_end (outputs closing divs for the content)
     *
     */
    do_action( 'listings_wp_after_main_content' );


get_footer( 'listings' ); ?>
