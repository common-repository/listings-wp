<?php
/**
 * The Template for displaying agent content in the agent.php template
 *
 * This template can be overridden by copying it to yourtheme/listings/content-single-agent.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'listings_wp_before_single_agent' ); ?>

<div class="listings-wp-single agent">

	<div class="main-wrap">

		<div class="summary">
		
			<?php
				/**
				 */
				do_action( 'listings_wp_single_agent_summary' );
			?>

		</div>

		<div class="content">
			
			<?php
				/**
				 */
				do_action( 'listings_wp_single_agent_content' );
			?>

		</div>

	</div>

	<div class="sidebar">
		
		<?php
			/**
			 */
			do_action( 'listings_wp_single_agent_sidebar' );
		?>

	</div>

</div>

<?php do_action( 'listings_wp_after_single_agent' ); ?>
