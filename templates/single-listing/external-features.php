<?php
/**
 * Single listing external features
 *
 * This template can be overridden by copying it to yourtheme/listings/single-listing/external-features.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$ext_features = listings_wp_meta( 'external_features' );

if( empty( $ext_features ) )
	return;
?>	
<div class="ext-features">
	<h3><?php esc_html_e( 'External Features', 'listings-wp' ); ?></h3>
	<ul>

		<?php
			foreach ( $ext_features as $index => $feature ) {  ?>
				<li class="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $feature ) ) ); ?>">
			        <?php echo listings_wp_tick(); ?> <?php echo esc_html( $feature ); ?>
			    </li>
				<?php
			} 
		?>

	</ul>
</div>