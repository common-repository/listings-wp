<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 *
 * @since 1.0.0
 */
class Listings_Wp_Post_Types {

	/**
	 * Main constructor
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {

		// Hook into actions & filters
		$this->hooks();

	}

	/**
	 * Hook in to actions & filters
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Registers and sets up the custom post types
	 *
	 * @since 1.0
	 * @return void
	 */
	public function register_post_type() {

		// get the slug for a single listing
		$listing_slug = listings_wp_option( 'single_url' ) ? listings_wp_option( 'single_url' ) : 'listing';

		$listing_labels = apply_filters( 'listings_wp_listing_labels', array(
			'name'                  => _x( '%2$s', 'listing post type name', 'listings-wp' ),
			'singular_name'         => _x( '%1$s', 'singular listing post type name', 'listings-wp' ),
			'add_new'               => __( 'New %1s', 'listings-wp' ),
			'add_new_item'          => __( 'Add New %1$s', 'listings-wp' ),
			'edit_item'             => __( 'Edit %1$s', 'listings-wp' ),
			'new_item'              => __( 'New %1$s', 'listings-wp' ),
			'all_items'             => __( '%2$s', 'listings-wp' ),
			'view_item'             => __( 'View %1$s', 'listings-wp' ),
			'search_items'          => __( 'Search %2$s', 'listings-wp' ),
			'not_found'             => __( 'No %2$s found', 'listings-wp' ),
			'not_found_in_trash'    => __( 'No %2$s found in Trash', 'listings-wp' ),
			'parent_item_colon'     => '',
			'menu_name'             => _x( '%2$s', 'listing post type menu name', 'listings-wp' ),
			'filter_items_list'     => __( 'Filter %2$s list', 'listings-wp' ),
			'items_list_navigation' => __( '%2$s list navigation', 'listings-wp' ),
			'items_list'            => __( '%2$s list', 'listings-wp' ),
		) );

		foreach ( $listing_labels as $key => $value ) {
			$listing_labels[ $key ] = sprintf( $value, __( 'Listing', 'listings-wp' ), __( 'Listings', 'listings-wp' ) );
		}

		$listing_args = array(
			'labels'             => $listing_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'       	 => 'dashicons-admin-multisite',
			'menu_position'      => 56,
			'query_var'          => true,
			'rewrite'            => array('slug' => untrailingslashit( $listing_slug ), 'with_front' => false, 'feeds' => true ),
			'capability_type'    => 'listing',
			'map_meta_cap' 		 => true,
			'has_archive'        => ( $archive_page = listings_wp_option( 'archives_page' ) ) && get_post( $archive_page ) ? get_page_uri( $archive_page ) : 'listings',
			'hierarchical'       => false,
			'supports'           => apply_filters( 'listings_wp_listing_supports', array( 'title', 'revisions', 'author' ) ),
		);
		register_post_type( 'listing', apply_filters( 'listings_wp_listing_post_type_args', $listing_args ) );


		$enquiry_labels = apply_filters( 'listings_wp_enquiry_labels', array(
			'name'                  => _x( '%2$s', 'enquiry post type name', 'listings-wp' ),
			'singular_name'         => _x( '%1$s', 'singular enquiry post type name', 'listings-wp' ),
			'add_new'               => __( 'New %1s', 'listings-wp' ),
			'add_new_item'          => __( 'Add New %1$s', 'listings-wp' ),
			'edit_item'             => __( 'Edit %1$s', 'listings-wp' ),
			'new_item'              => __( 'New %1$s', 'listings-wp' ),
			'all_items'             => __( '%2$s', 'listings-wp' ),
			'view_item'             => __( 'View %1$s', 'listings-wp' ),
			'search_items'          => __( 'Search %2$s', 'listings-wp' ),
			'not_found'             => __( 'No %2$s found', 'listings-wp' ),
			'not_found_in_trash'    => __( 'No %2$s found in Trash', 'listings-wp' ),
			'parent_item_colon'     => '',
			'menu_name'             => _x( '%2$s', 'enquiry post type menu name', 'listings-wp' ),
			'filter_items_list'     => __( 'Filter %2$s list', 'listings-wp' ),
			'items_list_navigation' => __( '%2$s list navigation', 'listings-wp' ),
			'items_list'            => __( '%2$s list', 'listings-wp' ),
		) );

		foreach ( $enquiry_labels as $key => $value ) {
			$enquiry_labels[ $key ] = sprintf( $value, __( 'Enquiry', 'listings-wp' ), __( 'Enquiries', 'listings-wp' ) );
		}

		$enquiry_args = array(
			'labels'             => $enquiry_labels,
			'public'             => false,
			'publicly_queryable' => false,
			'exclude_from_search'=> true,
			'show_in_nav_menus'	 => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=listing',
			'show_in_admin_bar'  => false,
			'menu_icon'       	 => 'dashicons-email',
			'menu_position'      => 56,
			'query_var'          => true,
			//'rewrite'            => array('slug' => 'listings-enquiry', 'with_front' => false),
			'capability_type' 	 => 'post',
			'capabilities' 		 => array(
			    'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			  ),
			'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			//'has_archive'        => '',
			'hierarchical'       => false,
			'supports'           => apply_filters( 'listings_wp_enquiry_supports', array( 'title', 'revisions' ) ),
		);
		register_post_type( 'listing-enquiry', apply_filters( 'listings_wp_enquiry_post_type_args', $enquiry_args ) );

	}

}

return new Listings_Wp_Post_Types();