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

?>

<?php

	 do_action( 'listings_wp_before_single_listing' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="listing-<?php the_ID(); ?>" class="listings-wp-single listing">

	<div class="main-wrap">

		<div class="image-gallery">

			<?php
				/**
				 * @hooked listings_wp_template_single_gallery
				 */
				do_action( 'listings_wp_single_listing_gallery' );
			?>
		</div>

		<div class="summary">

			<?php
				/**
				 * @hooked listings_wp_template_single_title
				 * @hooked listings_wp_template_single_price
				 * @hooked listings_wp_template_single_at_a_glance
				 * @hooked listings_wp_template_single_address
				 * @hooked listings_wp_template_single_sizes
				 * @hooked listings_wp_template_single_open_for_inspection
				 */
				do_action( 'listings_wp_single_listing_summary' );
			?>

		</div>

		<div class="content">

			<?php
				/**
				 * @hooked listings_wp_template_single_tagline
				 * @hooked listings_wp_template_single_description
				 * @hooked listings_wp_template_single_internal_features
				 * @hooked listings_wp_template_single_external_features
				 */
				do_action( 'listings_wp_single_listing_content' );
			?>

		</div>

	</div>

	<div class="sidebar">

		<?php
			/**
			 * @hooked listings_wp_template_single_map
			 * @hooked listings_wp_template_single_agent_details
			 * @hooked listings_wp_template_single_contact_form
			 */
			do_action( 'listings_wp_single_listing_sidebar' );
		?>

	</div>

	<div class="bottom">

		<?php do_action( 'listings_wp_single_listing_bottom' ); ?>

	</div>

</div>

<?php do_action( 'listings_wp_after_single_listing' ); ?>