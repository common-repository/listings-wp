<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Listings_Wp_Shortcodes {

	public function __construct() {
		add_filter( 'wp', array( $this, 'has_shortcode' ) );
		add_shortcode( 'listings_wp_listing', array( $this, 'listing' ) );
		add_shortcode( 'listings_wp_listings', array( $this, 'listings' ) );
		add_shortcode( 'listings_wp_agent', array( $this, 'agent' ) );
	}

	/**
	 * Check if we have the shortcode displayed
	 */
	public function has_shortcode() {
		global $post;
	    if ( is_a( $post, 'WP_Post' ) && 
	    	( has_shortcode( $post->post_content, 'listings_wp_listing') || 
	    	has_shortcode( $post->post_content, 'listings_wp_listings') || 
	    	has_shortcode( $post->post_content, 'listings_wp_agent') ) ) 
	    {
	        add_filter( 'is_listings_wp', array( $this, 'return_true' ) );
	    }

	    if ( is_a( $post, 'WP_Post' ) && 
	    	has_shortcode( $post->post_content, 'listings_wp_listing') )
	    {
	        add_filter( 'is_listing', array( $this, 'return_true' ) );
	    }
	}

	/**
	 * Add this as a listings_wp page
	 *
	 * @param bool $return
	 * @return bool
	 */
	public function return_true( $return ) {
		return true;
	}

	/**
	 * Loop over found listings.
	 * @param  array $query_args
	 * @param  array $atts
	 * @param  string $loop_name
	 * @return string
	 */
	private static function listing_loop( $query_args, $atts, $loop_name ) {

		$listings = new WP_Query( apply_filters( 'listings_wp_shortcode_listings_query', $query_args, $atts, $loop_name ) );

		ob_start();

		if ( $listings->have_posts() ) { ?>

			<?php do_action( "listings_wp_shortcode_before_{$loop_name}_loop" ); ?>

			<ul class="listings-wp-items">

				<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>

					<?php listings_wp_get_part( 'content-listing.php' ); ?>

				<?php endwhile; // end of the loop. ?>

			</ul>

			<?php do_action( "listings_wp_shortcode_after_{$loop_name}_loop" ); ?>

			<?php

		} else {
			do_action( "listings_wp_shortcode_{$loop_name}_loop_no_results" );
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * List multiple listings shortcode.
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function listings( $atts ) {
		$atts = shortcode_atts( array(
			'orderby' 		=> 'date',
			'order'   		=> 'asc',
			'number' 		=> '20',
			'agent' 		=> '', // id of the agent
			'ids'     		=> '',
			'compact'     	=> '',
		), $atts );

		$query_args = array(
			'post_type'           	=> 'listing',
			'post_status'         	=> 'publish',
			'ignore_sticky_posts' 	=> 1,
			'orderby'             	=> $atts['orderby'],
			'order'               	=> $atts['order'],
			'posts_per_page'      	=> $atts['number'],
		);

		if ( ! empty( $atts['ids'] ) ) {
			$query_args['post__in'] = array_map( 'trim', explode( ',', $atts['ids'] ) );
		}

		if ( ! empty( $atts['agent'] ) ) {
			$query_args['meta_key'] 	= '_lwp_listing_agent';
			$query_args['meta_value'] 	= absint( $atts['agent'] );
			$query_args['meta_compare'] = '=';
		}


		// if we are in compact mode
		if ( ! empty( $atts['compact'] ) && $atts['compact'] == 'true' ) {
			remove_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_at_a_glance', 40 );
			remove_action( 'listings_wp_listings_loop_item', 'listings_wp_template_loop_description', 50 );
			add_filter( 'post_class', array( __CLASS__, 'listings_compact_mode' ), 20, 3 );
		}

		return self::listing_loop( $query_args, $atts, 'listings' );
	}

	/**
	 * Add the compact class to the listings
	 */
	public static function listings_compact_mode( $classes, $class = '', $post_id = '' ) {
		$classes[] = 'compact';
		return $classes;
	}


	/**
	 * Display a single listing.
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function listing( $atts ) {
		if ( empty( $atts ) ) {
			return '';
		}

		$args = array(
			'post_type'      => 'listing',
			'posts_per_page' => 1,
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
		);

		if ( isset( $atts['id'] ) ) {
			$args['p'] = $atts['id'];
		}

		ob_start();

		$listings = new WP_Query( apply_filters( 'listings_wp_shortcode_listing_query', $args, $atts ) );

		if ( $listings->have_posts() ) : ?>

			<div id="listing-<?php the_ID(); ?>" class="listings-wp-single">

				<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>

					<?php listings_wp_get_part( 'content-single-listing.php' ); ?>

				<?php endwhile; // end of the loop. ?>

			</div>

		<?php endif;

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Display a single agent.
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function agent( $atts ) {
		if ( empty( $atts ) ) {
			return '';
		}

		if ( isset( $atts['id'] ) ) {
			$args['include'] = array( $atts['id'] );
		}

		ob_start();

		$agents = get_users( apply_filters( 'listings_wp_shortcode_agent_query', $args, $atts ) );
		if ( $agents ) :

			foreach ( $agents as $agent ) { 

				listings_wp_get_part( 'content-single-agent.php' );

			}

		endif;

		wp_reset_postdata();

		return ob_get_clean();
	}


}


return new Listings_Wp_Shortcodes();