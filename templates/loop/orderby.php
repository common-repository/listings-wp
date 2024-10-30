<?php
/**
 * Ordering
 *
 * This template can be overridden by copying it to yourtheme/listings/loop/orderby.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( 1 === $wp_query->found_posts ) {
	return;
}

$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'date';
$orderby_options = apply_filters( 'listings_wp_listings_orderby', array(
	'date'     		=> __( 'Newest Listings', 'listings-wp' ),
	'date-old'  	=> __( 'Oldest Listings', 'listings-wp' ),
	'price'      	=> __( 'Price (Low to High)', 'listings-wp' ),
	'price-high' 	=> __( 'Price (High to Low)', 'listings-wp' )
) );

?>
<form class="listings-wp-ordering" method="get">

	<div class="select-wrap">
		<select name="orderby" class="orderby">
			<?php foreach ( $orderby_options as $id => $name ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {

			if ( 'orderby' === $key || 'submit' === $key ) {
				continue;
			}
			if ( is_array( $val ) ) {
				foreach( $val as $innerVal ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}

		}
	?>

</form>
