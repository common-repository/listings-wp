<?php
/**
 * Loop title
 *
 * This template can be overridden by copying it to yourtheme/listings/loop/title.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<h3 class="title entry-title">
	<a href="<?php esc_url( the_permalink() ); ?>" title="<?php esc_attr( the_title() ); ?>">
		<?php the_title(); ?>
	</a>
</h3>