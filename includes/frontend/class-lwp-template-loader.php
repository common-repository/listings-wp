<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class Listings_Wp_Template_Loader {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. 
	 * listings_wp looks for theme overrides in /theme/listings/ by default.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {

		$file = '';	

		// only show on agents page
		if ( is_author() ) {
			$user = new WP_User( listings_wp_agent_ID() );
			$user_roles = $user->roles;

			$listings_count = listings_wp_agent_listings_count( listings_wp_agent_ID() );

			if ( in_array( 'listings_wp_agent', $user_roles ) || $listings_count > 0 ) {
				$file = 'agent.php';
			}

		}

		if ( is_single() && get_post_type() == 'listing' ) {
			$file = 'single-listing.php';
		} 
			
		if ( ( is_archive() && get_post_type() == 'listing' ) || is_listing_search() ) {
			$file = 'archive-listing.php';
		}

		$file = apply_filters( 'listings_wp_template_file', $file );
		
		if( ! $file ) {
			return $template;
		}

		$template = listings_wp_get_part( $file );

		return $template;
	}


}

new Listings_Wp_Template_Loader();