<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'post_class', 'listings_wp_listing_post_class', 20, 3 );

/**
 * Content Wrappers.
 *
 */
add_action( 'listings_wp_before_main_content', 'listings_wp_output_content_wrapper', 10 );

add_action( 'listings_wp_after_main_content', 'listings_wp_output_content_wrapper_end', 10 );

/**
 * Sidebar.
 *
 */
add_action( 'listings_wp_sidebar', 'listings_wp_get_sidebar', 10 );

/**
 * Before listings
 *
 */
add_action( 'listings_wp_archive_page_content', 'listings_wp_listing_archive_title', 10 );
add_action( 'listings_wp_archive_page_content', 'listings_wp_listing_archive_content', 20 );

add_action( 'listings_wp_before_listings_loop', 'listings_wp_ordering', 10 );
add_action( 'listings_wp_before_listings_loop', 'listings_wp_view_switcher', 20 );
add_action( 'listings_wp_before_listings_loop', 'listings_wp_pagination', 30 );

add_action( 'listings_wp_after_listings_loop', 'listings_wp_pagination', 10 );


/**
 * Listing Loop Items.
 *
 */
add_action( 'listings_wp_before_listings_loop_item_summary', 'listings_wp_template_loop_image', 10 );

add_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_title', 10 );
add_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_price', 20 );
add_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_address', 30 );
add_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_at_a_glance', 40 );
add_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_description', 50 );


/**
 * Single Listing
 *
 */
add_action( 'listings_wp_single_listing_gallery', 'listings_wp_template_single_gallery', 10 );

add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_title', 10 );
add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_price', 20 );
add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_address', 30 );
add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_at_a_glance', 40 );
add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_sizes', 50 );
add_action( 'listings_wp_single_listing_summary', 'listings_wp_template_single_open_for_inspection', 60 );

add_action( 'listings_wp_single_listing_content', 'listings_wp_template_single_tagline', 10 );
add_action( 'listings_wp_single_listing_content', 'listings_wp_template_single_description', 20 );
add_action( 'listings_wp_single_listing_content', 'listings_wp_template_single_internal_features', 30 );
add_action( 'listings_wp_single_listing_content', 'listings_wp_template_single_external_features', 40 );

add_action( 'listings_wp_single_listing_sidebar', 'listings_wp_template_single_map', 10 );
add_action( 'listings_wp_single_listing_sidebar', 'listings_wp_template_single_agent_details', 20 );
add_action( 'listings_wp_single_listing_sidebar', 'listings_wp_template_single_contact_form', 30 );


/**
 * Single Agent
 *
 */
add_action( 'listings_wp_single_agent_summary', 'listings_wp_template_agent_avatar',10 );
add_action( 'listings_wp_single_agent_summary', 'listings_wp_template_agent_name', 20 );
add_action( 'listings_wp_single_agent_summary', 'listings_wp_template_agent_title_position', 30 );
add_action( 'listings_wp_single_agent_summary', 'listings_wp_template_agent_contact', 40 );
add_action( 'listings_wp_single_agent_summary', 'listings_wp_template_agent_social', 50 );

add_action( 'listings_wp_single_agent_content', 'listings_wp_template_agent_description', 10 );
add_action( 'listings_wp_single_agent_content', 'listings_wp_template_agent_specialties', 20 );
add_action( 'listings_wp_single_agent_content', 'listings_wp_template_agent_awards', 30 );

add_action( 'listings_wp_single_agent_sidebar', 'listings_wp_template_agent_listings', 10 );