<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/listings/global/wrapper-end.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if( listings_wp_option( 'closing_html' ) ) {
	
	echo wp_kses_post( listings_wp_option( 'closing_html' ) );

} else {

	switch( listings_wp_get_theme() ) {
		case 'genesis' :
			echo '</div>';
			echo '</div>';
			break;
		case 'divi' :
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			break;
		case 'twentyeleven' :
			echo '</div>';
			echo '</div>';
			break;
		case 'twentytwelve' :
			echo '</div></div>';
			break;
		case 'twentythirteen' :
			echo '</div></div>';
			break;
		case 'twentyfourteen' :
			echo '</div></div></div>';
			break;
		case 'twentyfifteen' :
			echo '</div></div>';
			break;
		case 'twentysixteen' :
			echo '</main></div>';
			break;
		case 'twentyseventeen' :
			echo '</main></div>';
			echo '</div>';
			break;
		default :
			echo apply_filters( 'listings_wp_wrapper_end', '</div></div>' );
			break;
	}

}