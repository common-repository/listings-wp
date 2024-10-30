<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new 'downloads' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successful install, the user is redirected to the Listings_Wp Welcome
 * screen.
 *
 * @since 1.0
 */

function listings_wp_install( $network_wide = false ) {
	global $wpdb;

	if ( is_multisite() && $network_wide ) {

		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
			switch_to_blog( $blog_id );
			listings_wp_run_install();
			restore_current_blog();
		}

	} else {
		listings_wp_run_install();
	}

}
register_activation_hook( LISTINGSWP_PLUGIN_FILE, 'listings_wp_install' );


function listings_wp_install_listings_page() {

	global $wpdb;

	$page_content = '[listings_wp_search]';

	$page_data = array(
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'post_title'     => 'Listings',
		'post_content'   => $page_content,
		'comment_status' => 'closed',
	);

	$options = get_option( 'listings_wp_options' );

	if ( isset( $options['archives_page'] ) && ( $page_object = get_post( $options['archives_page'] ) ) ) {
		if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
			// Valid page is already in place
			return $page_object->ID;
		}
	}


	// Search for an existing page with the specified page content (typically a shortcode)
	$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );

	if ( $valid_page_found ) {
		$options['archives_page'] = $valid_page_found;
		update_option( 'listings_wp_options', $options );
		return $valid_page_found;
	}

	// Search for an existing page with the specified page content (typically a shortcode)
	$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'             => $page_id,
			'post_status'    => 'publish',
		);
	 	wp_update_post( $page_data );
	} else {
		$page_id = wp_insert_post( $page_data );
	}

}

function listings_wp_install_sample_listing() {

	global $wpdb;

	$listing_title = 'My Sample Listing';

	$listing_data = array(
		'post_status'    => 'publish',
		'post_type'      => 'listing',
		'post_title'     => $listing_title,
		'post_content'   => '',
		'comment_status' => 'closed',
	);

	// Search for an existing page with the specified page content (typically a shortcode)
	$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='listing' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_title LIKE %s LIMIT 1;", "%{$listing_title}%" ) );

	if ( $valid_page_found ) {
		return $valid_page_found;
	}

	// Search for an existing page with the specified page content (typically a shortcode)
	$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='listing' AND post_status = 'trash' AND post_title LIKE %s LIMIT 1;", "%{$listing_title}%" ) );

	if ( $trashed_page_found ) {
		$listing_id   = $trashed_page_found;
		$listing_data = array(
			'ID'             => $listing_id,
			'post_status'    => 'publish',
		);
	 	wp_update_post( $page_data );
	} else {
		$listing_id = wp_insert_post( $listing_data );
	}

	$prefix = '_lwp_listing_';
	$save_meta = array(
	  	$prefix . 'status' => 'Under Offer',
	  	$prefix . 'tagline' => 'Close to everything!',
	  	$prefix . 'main_description' => '<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p><p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.</p>',
	  	$prefix . 'price' => '420000',
	  	$prefix . 'price_suffix' => 'or near offer',
	  	$prefix . 'type' => 'House',
	  	$prefix . 'purpose' => 'Sell',
	  	$prefix . 'bedrooms' => '4',
	  	$prefix . 'bathrooms' => '2',
	  	$prefix . 'car_spaces' => '1',
	  	$prefix . 'building_size' => '24',
	  	$prefix . 'building_unit' => 'Squares',
	  	$prefix . 'land_size' => '3',
	  	$prefix . 'land_unit' => 'Acres',
	  	$prefix . 'displayed_address' => 'Miami Shore Parade, Miami QLD 4220',
	  	$prefix . 'route' => 'Miami Shore Parade',
	  	$prefix . 'city' => 'Miami',
	  	$prefix . 'state' => 'Queensland',
	  	$prefix . 'zip' => '4220',
	  	$prefix . 'country' => 'Australia',
	  	$prefix . 'lat' => '-28.0710877',
	  	$prefix . 'lng' => '153.44109830000002',
	  	$prefix . 'agent' => get_current_user_id(),
	);

	//Save values from created array into db
	foreach( $save_meta as $meta_key => $meta_value ) {
	   update_post_meta( $listing_id, $meta_key, $meta_value );
	}

}

function listings_wp_install_data() {
	
	$options = array();
	$options['grid_columns'] = '3';
	$options['delete_data'] = 'no';
	$options['archives_page_title'] = 'no';
	$options['tick_icon'] = 'tick-7';
	$options['arrow_icon'] = 'arrow-2';
	$options['bed_icon'] = 'bed-2';
	$options['bath_icon'] = 'bath-7';
	$options['car_icon'] = 'car-4';
	$options['listing_type'] = array(
		'House',
		'Unit',
		'Land',
	);
	$options['internal_feature'] = array(
		'Dishwasher',
		'Open Fireplace',
	);
	$options['external_feature'] = array(
		'Balcony',
		'Tennis Court',
	);
	$options['listing_status'] = array(
		array(
			'status' => 'Under Offer',
			'bg_color' => '#1e73be',
			'text_color' => '#ffffff',
			'icon' => 'lwp-icon-house',
		),
		array(
			'status' => 'Sold',
			'bg_color' => '#dd3333',
			'text_color' => '#ffffff',
		)
	);

	update_option( 'listings_wp_options', $options );

}


/**
 * Run the Listings_Wp Instsall process
 *
 * @since  1.0
 * @return void
 */
function listings_wp_run_install() {
	
	global $wpdb, $wp_version;

	// Setup the Listings Custom Post Type
	$types = new Listings_Wp_Post_Types;
	$types->register_post_type();

	// install data
	listings_wp_install_data();
	listings_wp_install_listings_page();
	listings_wp_install_sample_listing();

	// Add Upgraded From Option
	$current_version = get_option( 'listings_wp_version' );
	if ( $current_version ) {
		update_option( 'listings_wp_version_upgraded_from', $current_version );
	}

	update_option( 'listings_wp_version', LISTINGSWP_VERSION );

	// Create Listings_Wp roles
	$roles = new Listings_Wp_Roles;
	$roles->add_roles();
	$roles->add_caps();

	// when upgrading
	// if ( ! $current_version ) {}

	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	// Add the transient to redirect
	set_transient( '_listings_wp_activation_redirect', true, 30 );

	// Clear the permalinks
	flush_rewrite_rules( false );

}



/**
 * When a new Blog is created in multisite, see if Listings_Wp is network activated, and run the installer
 *
 * @since  1.0.0
 * @param  int    $blog_id The Blog ID created
 * @param  int    $user_id The User ID set as the admin
 * @param  string $domain  The URL
 * @param  string $path    Site Path
 * @param  int    $site_id The Site ID
 * @param  array  $meta    Blog Meta
 * @return void
 */
function listings_wp_new_blog_created( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

	if ( is_plugin_active_for_network( plugin_basename( LISTINGSWP_PLUGIN_FILE ) ) ) {
		switch_to_blog( $blog_id );
		listings_wp_install();
		restore_current_blog();
	}

}
add_action( 'wpmu_new_blog', 'listings_wp_new_blog_created', 10, 6 );



/**
 * Post-installation
 *
 * Runs just after plugin installation and exposes the
 * listings_wp_after_install hook.
 *
 * @since 1.0.0
 * @return void
 */
function listings_wp_after_install() {

	if ( ! is_admin() ) {
		return;
	}

	$activated = get_transient( '_listings_wp_activation_redirect' );

	if ( false !== $activated ) {

		// add the default options
		//listings_wp_add_default_listing();
		delete_transient( '_listings_wp_activation_redirect' );

		if( ! isset( $_GET['activate-multi'] ) ) {
			set_transient( '_listings_wp_redirected', true, 60 );
	        wp_redirect( 'admin.php?page=listings_wp_options' );
	        exit;
	    }

	}

}
add_action( 'admin_init', 'listings_wp_after_install' );


function listings_wp_install_success_notice() {

	$redirected = get_transient( '_listings_wp_redirected' );

	if ( false !== $redirected && isset( $_GET['page'] ) && $_GET['page'] == 'listings_wp_options' ) {
		// Delete the transient
		//delete_transient( '_listings_wp_redirected' );

		$class = 'notice notice-info is-dismissible';
		$message = '<strong>' . __( 'Success!', 'listings-wp' ) . '</strong>' . __( ' A sample listing has been created: ', 'listings-wp' );
		$message .= '<a class="button button-small" target="_blank" href="' . home_url( '/listings' ) . '">' . __( 'View First Listing', 'listings-wp' ) . '</a><br><br>';
		$message .= __( 'Step 1. Please go through each tab below, configure the options and <strong>hit the save button</strong>.', 'listings-wp' ) . '<br>';
		$message .= __( 'Step 2. Add your first Listing by navigating to <strong>Listings > New Listing</strong>', 'listings-wp' ) . '<br>';

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
}
add_action( 'admin_notices', 'listings_wp_install_success_notice' );