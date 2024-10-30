<?php
/**
 * Single listing contact-form
 *
 * This template can be overridden by copying it to yourtheme/listings/single-listing/contact-form.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<h3><?php echo __( 'Quick Contact', 'listings-wp' ); ?></h3>
<div class="contact-form" id="listings-wp-contact"><?php echo do_shortcode( '[listings_wp_contact_form]' ) ?></div>