<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Work out which theme the user has active
 */
function listings_wp_get_theme() {

    if( function_exists('et_divi_fonts_url') ) {
		$theme = 'divi'; 
	} else if( function_exists('genesis_constants') ) {
		$theme = 'genesis';
	} else {
		$theme = get_option( 'template' );
	}
	return $theme;
	
}

/*=================================== Global ===================================
*/

/**
 * Output the start of the page wrapper.
 */
if ( ! function_exists( 'listings_wp_output_content_wrapper' ) ) {
	function listings_wp_output_content_wrapper() {
		listings_wp_get_part( 'global/wrapper-start.php' );
	}
}

/**
 * Output the end of the page wrapper.
 */
if ( ! function_exists( 'listings_wp_output_content_wrapper_end' ) ) {
	function listings_wp_output_content_wrapper_end() {
		listings_wp_get_part( 'global/wrapper-end.php' );
	}
}

/**
 * Output the end of the page wrapper.
 */
if ( ! function_exists( 'listings_wp_get_sidebar' ) ) {
	function listings_wp_get_sidebar() {
		listings_wp_get_part( 'global/sidebar.php' );
	}
}



/*=================================== Single Listing ===================================
*/

/**
 * Output the title.
 */
if ( ! function_exists( 'listings_wp_template_single_title' ) ) {
	function listings_wp_template_single_title() {
		listings_wp_get_part( 'single-listing/title.php' );
	}
}

/**
 * Output the address.
 */
if ( ! function_exists( 'listings_wp_template_single_address' ) ) {
	function listings_wp_template_single_address() {
		if( listings_wp_hide_item( 'address' ) )
			return;
		listings_wp_get_part( 'single-listing/address.php' );
	}
}

/**
 * Output the price.
 */
if ( ! function_exists( 'listings_wp_template_single_price' ) ) {
	function listings_wp_template_single_price() {
		if( listings_wp_hide_item( 'price' ) )
			return;
		listings_wp_get_part( 'single-listing/price.php' );
	}
}

/**
 * Output the at a glance.
 */
if ( ! function_exists( 'listings_wp_template_single_at_a_glance' ) ) {
	function listings_wp_template_single_at_a_glance() {
		listings_wp_get_part( 'single-listing/at-a-glance.php' );
	}
}

/**
 * Output the sizes.
 */
if ( ! function_exists( 'listings_wp_template_single_sizes' ) ) {
	function listings_wp_template_single_sizes() {
		listings_wp_get_part( 'single-listing/sizes.php' );
	}
}

/**
 * Output the gallery.
 */
if ( ! function_exists( 'listings_wp_template_single_gallery' ) ) {
	function listings_wp_template_single_gallery() {
		$images = listings_wp_meta( 'image_gallery' );
		if( ! $images )
			return;
		listings_wp_get_part( 'single-listing/gallery.php' );
	}
}
/**
 * Output the map.
 */
if ( ! function_exists( 'listings_wp_template_single_map' ) ) {
	function listings_wp_template_single_map() {
		$key = listings_wp_option( 'maps_api_key' );
		if( listings_wp_hide_item( 'map' ) || ! $key )
			return;
		listings_wp_get_part( 'single-listing/map.php' );
	}
}

/**
 * Output the tagline.
 */
if ( ! function_exists( 'listings_wp_template_single_tagline' ) ) {
	function listings_wp_template_single_tagline() {
		listings_wp_get_part( 'single-listing/tagline.php' );
	}
}

/**
 * Output the description.
 */
if ( ! function_exists( 'listings_wp_template_single_description' ) ) {
	function listings_wp_template_single_description() {
		listings_wp_get_part( 'single-listing/description.php' );
	}
}

/**
 * Output the open_for_inspection.
 */
if ( ! function_exists( 'listings_wp_template_single_open_for_inspection' ) ) {
	function listings_wp_template_single_open_for_inspection() {
		listings_wp_get_part( 'single-listing/open-for-inspection.php' );
	}
}

/**
 * Output the internal_features.
 */
if ( ! function_exists( 'listings_wp_template_single_internal_features' ) ) {
	function listings_wp_template_single_internal_features() {
		listings_wp_get_part( 'single-listing/internal-features.php' );
	}
}

/**
 * Output the external_features.
 */
if ( ! function_exists( 'listings_wp_template_single_external_features' ) ) {
	function listings_wp_template_single_external_features() {
		listings_wp_get_part( 'single-listing/external-features.php' );
	}
}

/**
 * Output the agent details.
 */
if ( ! function_exists( 'listings_wp_template_single_agent_details' ) ) {
	function listings_wp_template_single_agent_details() {
		$agent = listings_wp_meta( 'agent' );
		if( listings_wp_hide_item( 'agent' ) || empty( $agent ) || $agent == '' )
			return;
		listings_wp_get_part( 'single-listing/agent-details.php' );
	}
}

/**
 * Output the contact form.
 */
if ( ! function_exists( 'listings_wp_template_single_contact_form' ) ) {
	function listings_wp_template_single_contact_form() {
		$agent = listings_wp_meta( 'agent' );
		if( listings_wp_hide_item( 'contact_form' ) || empty( $agent ) )
			return;
		listings_wp_get_part( 'single-listing/contact-form.php' );
	}
}



/*=================================== Archive page ===================================
*/
add_filter( 'get_the_archive_title', 'listings_wp_listing_display_theme_title' );
function listings_wp_listing_display_theme_title( $title ) {
	if( is_listing_archive() ){
		$title = listings_wp_listing_archive_get_title();
	}
	return $title;
}


if ( ! function_exists( 'listings_wp_listing_archive_title' ) ) {
	
	function listings_wp_listing_archive_title() {

		$force = listings_wp_force_page_title();

		if( $force != 'yes' )
			return;
		?>

	        <h1 class="page-title"><?php esc_html_e( listings_wp_listing_archive_get_title() ); ?></h1>

	    <?php

	}

}

function listings_wp_listing_archive_get_title() {

	// get the title we need (search page or not)
	if ( is_search() ) {
		
		$query = isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ? ' - ' . $_GET['s'] : '';
		$page_title = sprintf( __( 'Search Results %s', 'listings-wp' ), esc_html( $query ) );

		if ( get_query_var( 'paged' ) )
			$page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'listings-wp' ), get_query_var( 'paged' ) );

	} elseif ( is_listing_archive() ) {

		$page_id = listings_wp_option( 'archives_page' );
		$page_title = get_the_title( $page_id );

	} else {

		$page_title = get_the_title();
	}

	$page_title = apply_filters( 'listings_wp_archive_page_title', $page_title );

    return $page_title;

}

/**
 * Archive page title
 *
 */
if ( ! function_exists( 'listings_wp_page_title' ) ) {

	function listings_wp_page_title() {
	    $page_title = listings_wp_listing_archive_get_title();
	    return $page_title;
	}
	
}

/**
 * Show the description on listings archive page
 */
if ( ! function_exists( 'listings_wp_listing_archive_content' ) ) {
	function listings_wp_listing_archive_content() {
		if ( is_post_type_archive( 'listing' ) ) {
			$archive_page = get_post( listings_wp_option( 'archives_page' ) );
			if ( $archive_page ) {
				$description = apply_filters( 'listings_wp_format_archive_content', do_shortcode( shortcode_unautop( wpautop( $archive_page->post_content ) ) ), $archive_page->post_content );
				if ( $description ) {
					echo '<div class="page-description">' . $description . '</div>';
				}
			}
		}
	}
}

/*=================================== Loop ===================================
*/

/**
 * Output sorting options.
 */
if ( ! function_exists( 'listings_wp_ordering' ) ) {
	function listings_wp_ordering() {
		listings_wp_get_part( 'loop/orderby.php' );
	}
}

/**
 * View switcher.
 */
if ( ! function_exists( 'listings_wp_view_switcher' ) ) {
	function listings_wp_view_switcher() {
		listings_wp_get_part( 'loop/view-switcher.php' );
	}
}

/**
 * Output pagination.
 */
if ( ! function_exists( 'listings_wp_pagination' ) ) {
	function listings_wp_pagination() {
		listings_wp_get_part( 'loop/pagination.php' );
	}
}

/**
 * Output the title.
 */
if ( ! function_exists( 'listings_wp_template_loop_title' ) ) {
	function listings_wp_template_loop_title() {
		listings_wp_get_part( 'loop/title.php' );
	}
}

/**
 * Output the address.
 */
if ( ! function_exists( 'listings_wp_template_loop_address' ) ) {
	function listings_wp_template_loop_address() {
		if( listings_wp_hide_item( 'address' ) )
			return;
		listings_wp_get_part( 'loop/address.php' );
	}
}

/**
 * Output the price.
 */
if ( ! function_exists( 'listings_wp_template_loop_price' ) ) {
	function listings_wp_template_loop_price() {
		if( listings_wp_hide_item( 'price' ) )
			return;
		listings_wp_get_part( 'loop/price.php' );
	}
}

/**
 * Output the at a glance.
 */
if ( ! function_exists( 'listings_wp_template_loop_at_a_glance' ) ) {
	function listings_wp_template_loop_at_a_glance() {
		listings_wp_get_part( 'loop/at-a-glance.php' );
	}
}


/**
 * Output the sizes.
 */
if ( ! function_exists( 'listings_wp_template_loop_sizes' ) ) {
	function listings_wp_template_loop_sizes() {
		listings_wp_get_part( 'loop/sizes.php' );
	}
}

/**
 * Output the tagline.
 */
if ( ! function_exists( 'listings_wp_template_loop_tagline' ) ) {
	function listings_wp_template_loop_tagline() {
		listings_wp_get_part( 'loop/tagline.php' );
	}
}

/**
 * Output the description.
 */
if ( ! function_exists( 'listings_wp_template_loop_description' ) ) {
	function listings_wp_template_loop_description() {
		listings_wp_get_part( 'loop/description.php' );
	}
}

/**
 * Output the image.
 */
if ( ! function_exists( 'listings_wp_template_loop_image' ) ) {
	function listings_wp_template_loop_image() {
		listings_wp_get_part( 'loop/image.php' );
	}
}




/*=================================== Single Agent ===================================
*/

/**
 * Output agent avatar
 */
if ( ! function_exists( 'listings_wp_template_agent_avatar' ) ) {
	function listings_wp_template_agent_avatar() {
		listings_wp_get_part( 'agent/avatar.php' );
	}
}
/**
 * Output agent name
 */
if ( ! function_exists( 'listings_wp_template_agent_name' ) ) {
	function listings_wp_template_agent_name() {
		listings_wp_get_part( 'agent/name.php' );
	}
}
/**
 * Output agent position
 */
if ( ! function_exists( 'listings_wp_template_agent_title_position' ) ) {
	function listings_wp_template_agent_title_position() {
		listings_wp_get_part( 'agent/title-position.php' );
	}
}
/**
 * Output agent mobile
 */
if ( ! function_exists( 'listings_wp_template_agent_contact' ) ) {
	function listings_wp_template_agent_contact() {
		listings_wp_get_part( 'agent/contact.php' );
	}
}

/**
 * Output agent social
 */
if ( ! function_exists( 'listings_wp_template_agent_social' ) ) {
	function listings_wp_template_agent_social() {
		listings_wp_get_part( 'agent/social.php' );
	}
}
/**
 * Output agent specialties
 */
if ( ! function_exists( 'listings_wp_template_agent_specialties' ) ) {
	function listings_wp_template_agent_specialties() {
		listings_wp_get_part( 'agent/specialties.php' );
	}
}
/**
 * Output agent awards
 */
if ( ! function_exists( 'listings_wp_template_agent_awards' ) ) {
	function listings_wp_template_agent_awards() {
		listings_wp_get_part( 'agent/awards.php' );
	}
}

/**
 * Output agent description
 */
if ( ! function_exists( 'listings_wp_template_agent_description' ) ) {
	function listings_wp_template_agent_description() {
		listings_wp_get_part( 'agent/description.php' );
	}
}

/**
 * Output agent listings
 */
if ( ! function_exists( 'listings_wp_template_agent_listings' ) ) {
	function listings_wp_template_agent_listings() {
		listings_wp_get_part( 'agent/listings.php' );
	}
}



/*
 * Set the path to be used in the theme folder.
 * Templates in this folder will override the plugins frontend templates.
 */
function listings_wp_template_path() {
	return apply_filters( 'listings_wp_template_path', 'listings/' );
}


function listings_wp_get_part( $part, $id = null ) {
	
	if ( $part ) {

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( listings_wp_template_path() ) . $part,
				$part,
			)
		);

		// Get template from plugin directory
		if ( ! $template ) {

			$check_dirs = apply_filters( 'listings_wp_template_directory', array(
				LISTINGSWP_PLUGIN_DIR . 'templates/',
			));
			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . $part ) ) {
					$template = $dir . $part;
				}
			}

		}

		include( $template );

	}	

}