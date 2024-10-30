<?php
/**
 * Uninstall Listings_Wp
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load Listings_Wp file.
include_once( 'listings-wp.php' );

global $wpdb, $wp_roles;

$remove = listings_wp_option( 'delete_data' );

if( $remove == 'yes' ) {

	/** Delete All the Custom Post Types */
	$listings_wp_post_types = array( 'listing', 'listing-enquiry' );
	foreach ( $listings_wp_post_types as $post_type ) {

		$items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );

		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item, true);
			}
		}
	}


	/** Delete all the Plugin Options */
	delete_option( 'listings_wp_options' );
	delete_option( 'listings_wp_version' );
	delete_option( 'listings_wp_version_upgraded_from' );

	/** Delete Capabilities */
	$roles = new Listings_Wp_Roles;
	$roles->remove_caps();

	/** Delete the Roles */
	$listings_wp_roles = array( 'listings_wp_agent' );
	foreach ( $listings_wp_roles as $role ) {
		remove_role( $role );
	}

}
