<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class Listings_Wp_Search {
	
	/**
	 * Get things going
	 *
	 */
	public function __construct() {
		add_shortcode( 'listings_wp_search', array( $this, 'search_form' ) );
		add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 999 );
	}

	/**
	 * Register custom query vars, based on our registered fields
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
	 */
	public function register_query_vars( $vars ) {
		foreach ( $this->register_meta_fields() as $key => $field ) {
			$vars[] = strtolower( $key );
		}
		$vars[] = 'purpose'; // always add the purpose (buy/sell)
		return $vars;
	} 

	/**
	 * Register our search fields
	 *
	 */
	public function register_meta_fields() {
		$fields = array(
			'type' => array( // the key is the main ID
				'name' 		=> 'type',
				'meta_key' 	=> 'type',
				'format' 	=> 'select', 
				'class' 	=> '',
			),
			'min_price' => array(
				'name' 		=> 'min_price',
				'label' 	=> __( 'Min Price', 'listings-wp' ),
				'meta_key' 	=> 'price',
				'format' 	=> 'select', 
				'class' 	=> '',
			),
			'max_price' => array(
				'name' 		=> 'max_price',
				'label' 	=> __( 'Max Price', 'listings-wp' ),
				'meta_key' 	=> 'price',
				'format' 	=> 'select', 
				'class' 	=> '',
			),
			'min_beds' => array( 
				'name' 		=> 'min_beds',
				'label' 	=> __( 'Min Beds', 'listings-wp' ),
				'meta_key' 	=> 'bedrooms',
				'format' 	=> 'select', 
				'class' 	=> '',
			),
			'max_beds' => array( 
				'name' 		=> 'max_beds',
				'label' 	=> __( 'Max Beds', 'listings-wp' ),
				'meta_key' 	=> 'bedrooms',
				'format' 	=> 'select', 
				'class' 	=> '',
			),
		);
		return apply_filters( 'listings_wp_search_fields', $fields );
	} 


	/**
	 * Build the form
	 *
	 */
	public function search_form( $atts ){

		$atts = shortcode_atts( array(
			'placeholder' 	=> __( 'Address, Suburb, Region, Zip or Landmark', 'listings-wp' ),
			'submit_btn' 	=> __( 'Search', 'listings-wp' ),
			'exclude' 		=> array(),
		), $atts );

		$output 	= '';
		$fields 	= $this->build_fields( $atts );
		$purpose 	= listings_wp_option( 'display_purpose' );
		ob_start();

		?>

		<form 
			id="listings-wp-search-form" 
			class="listings-wp-search-form fields-<?php echo absint( $fields['count'] ) ?> display-<?php esc_attr_e( $purpose ) ?>" autocomplete="off" 
			action="<?php echo esc_url( get_permalink( listings_wp_option( 'archives_page' ) ) ) ?>" 
			method="GET" 
			role="search">
	
			<div class="form-group">

			<?php		
			// include the Buy/Rent dropdown if we want to display both
			// or a hidden field with the purpose if only displaying one
			if( $purpose == 'both' ){ 

				$rent 	= isset( $_GET['purpose'] ) && $_GET['purpose'] == 'rent' ? ' selected="selected"' : '';
				$buy 	= isset( $_GET['purpose'] ) && $_GET['purpose'] == 'buy' ? ' selected="selected"' : '';
			?>
				
				<div class="purpose-wrap">
					<div class="select-wrap">
						<select class="form-control purpose" name="purpose">
							<option value="buy" <?php esc_html_e( $buy ) ?>><?php _e( 'Buy', 'listings-wp' ) ?></option>
							<option value="rent" <?php esc_html_e( $rent ); ?>><?php _e( 'Rent', 'listings-wp' ) ?></option>
						</select>
					</div>
				</div>

			<?php } else { ?>

				<input type="hidden" name="purpose" value="<?php esc_html_e( $purpose ) ?>"/>

			<?php } ?>

				<input class="form-control search-input" type="text" name="s" placeholder="<?php esc_html_e( $atts['placeholder'] ) ?>" value="<?php esc_html_e( get_search_query() ) ?>" />

				<input class="button btn btn-default search-button" type="submit" value="<?php esc_html_e( $atts['submit_btn'] ) ?>" />

			</div>

		<?php 	
		// add our registered and built fields
		echo $fields['output'];
		?>

		</form>

		<?php

	    $output = ob_get_contents();
	    ob_end_clean();

	    return apply_filters( 'listings_wp_search_form_output', $output, $atts );

	}



	public function select_field( $field, $array ){	

		// get the label (or the formatted field key if no label)
		$fields = $this->register_meta_fields();
		$label 	= isset( $fields[$field]['label'] ) && ! empty( $fields[$field]['label'] ) ? $fields[$field]['label'] : ucwords( str_replace( '_', ' ', $field));
		$class 	= isset( $fields[$field]['class'] ) && ! empty( $fields[$field]['class'] ) ? ' ' . $fields[$field]['class'] : '';
		$name 	= strtolower( $field );

		$output = '';
		ob_start();

		?>
		<div class="<?php esc_attr_e( $class ) ?>">
			<div class="select-wrap">
				
				<select class="form-control <?php esc_attr_e( $name ) ?>" name="<?php esc_attr_e( $name ) ?>">
					
					<option value=""><?php esc_attr_e( $label ) ?></option>

					<?php if( ! empty( $array ) ) {

						foreach ( $array as $val => $text ) {

							$selected = isset( $_GET[$field] ) && $_GET[$field] == $val ? ' selected="selected"' : '';

							if( ! empty( $val ) ) { ?>
								<option value="<?php esc_attr_e( $val ); ?>" <?php esc_html_e($selected ); ?> ><?php esc_html_e( ucwords( $text ) ) ?></option>
							<?php } 
						}
					}
					?>

				</select>

			</div>
		</div>

		<?php

	    $output = ob_get_contents();
	    ob_end_clean();

	    return apply_filters( 'listings_wp_search_form_fields_output', $output );

	}


	/**
	 * Build the fields for output
	 *
	 */
	private function build_fields( $atts ){

		$registered_fields 	= $this->register_meta_fields();
		$exclude 			= isset( $atts['exclude'] ) && ! empty( $atts['exclude'] ) ? $atts['exclude'] : null;
		$exclude_fields 	= ! empty( $exclude ) ? array_map('trim', explode( ',', $exclude ) ) : array();
		$output = '';

		// The Query
		$args = array( 
			'post_type' 		=> 'listing', 
			'posts_per_page' 	=> '-1',
			'post_status' 		=> 'publish',
		);

		// modify the query if we only want rent or only want sell
		$args['meta_key'] 		= '_lwp_listing_purpose'; 
		$args['meta_value'] 	= listings_wp_display();
		$args['meta_compare'] 	= 'LIKE';


		$listings = query_posts( apply_filters( 'listings_wp_search_populate_dropdown_args', $args ) );

		// The Loop
		$fields = array();
		if ( $listings ) {

			foreach ( $listings as $listing ) {

				foreach ( $registered_fields as $key => $field ) {
					
					if( in_array( $key, $exclude_fields ) )
						continue;

					$val = listings_wp_meta( $field['meta_key'], $listing->ID );

					if( $val && $val !='' ) {
						$fields[$key][$val] = $val;
					}

				}

			}
		}

		/* Restore original Post Data */
		wp_reset_query();

		$field_count = count( $fields );

		// loop through our registered fields
		foreach ( $fields as $field => $values ) {
			
			$values = $this->get_min_max_val( $field, $values ); // work out min/max values for inputs
			asort( $values ); // sort our values
			$values = array_unique( $values ); // make them unique

			switch ( $registered_fields[$field]['format'] ) {
				case 'select':
					$output .= $this->select_field( $field, $values ); // add the select field to the output
					break;
				
				default:
					# code...
					break;
			}
			
			reset( $values );

		}

		return array( 
			'output' => $output,
			'count' => $field_count
		);	

	}

	/**
	 * Calculates our min and max values for the output of the fields
	 *
	 */
	public function get_min_max_val( $field, $values ){	
		
		if ( $field == 'min_price' || $field == 'max_price' ) {
		    
		    $min = min( $values );
		    $min = round( $min ); // get lowest value

		    $max = max( $values );
		    $max = round( $max ); // get highest value

		    $diff = $max - $min; // get the difference
		    $interval = round( $diff / 8, -4 ); // get the interval amounts

		    $int_value = 0;
		    $values = array(); // reset the values array
		    for ($i=1; $i < 8 ; $i++) {

		    	$values[ $min + $int_value ] = listings_wp_raw_price( $min + $int_value );
		    	$int_value = $interval * $i;
		    }
		    $values[ $max ] = listings_wp_raw_price( $max );
		    
		} 	

		if ( $field == 'min_beds' || $field == 'max_beds' ) {
		    
		    $min = min( $values ); // get lowest value
		    $max = max( $values ); // get highest value
		    $values = array(); // reset the values array
		    $values = range( $min, $max, 1 );
		    foreach($values as $key => $val) {
			    $new_array[$val] = $val;
			}
		    $values = $new_array;

		} 

		return array_unique( $values );
	}


	/**
	 * Build a custom query based on several conditions
	 * The pre_get_posts action gives developers access to the $query object by reference
	 * any changes you make to $query are made directly to the original object - no return value is requested
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
	 *
	 */
	public function pre_get_posts( $q ) {
		
		// check if the user is requesting an admin page 
		// or current query is not the main query
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}

		if ( ! is_post_type_archive( 'listing' ) ) {
			return;
		}

		$meta_query = array();

		// start our separate queries
		// they are all merged together later
		$purpose_query[] = array(
			'key' 		=> '_lwp_listing_purpose',
			'value' 	=> listings_wp_display(),
			'compare' 	=> 'LIKE',
		);
		$beds_query[] 		= $this->beds_meta_query();
		$price_query[] 		= $this->price_meta_query();
		$type_query[] 		= $this->type_meta_query();
		$radius_query[] 	= $this->radius_query( $q );
		$keyword_query[] 	= $this->keyword_query( $q );
		

		// this should be always set
		// purpose AND Bedrooms AND price AND type
		$query_1 = array_merge( $purpose_query, $beds_query, $price_query, $type_query );
		
		// within radius AND purpose AND Bedrooms AND price AND type
		$query_2 = array_merge( $radius_query, $keyword_query );
			
		// if no keyword
		if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
			$query_1['relation'] = 'AND';
			$meta_query[] = $query_1;
		}

		// if keyword
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
			$query_2['relation'] = 'OR';
			$meta_query[] = $query_1;
			$meta_query[] = $query_2;
			$meta_query['relation'] = 'AND';
		}
		
		$q->set( 'meta_query', $meta_query );

	}


	/**
	 * Run the radius query
	 *
	 * @access public
	 * @return array
	 */
	public function radius_query( $q ) {
		
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {

			$searchterm = isset( $q->query_vars['s'] ) ? $q->query_vars['s'] : '';

		    // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
		    $q->query_vars['s'] = '';

		    if ( $searchterm != '' ) {

				$radius = $this->geocode( $searchterm );

				$lat = $radius['lat']; // get the lat of the requested address
				$lng = $radius['lng']; // get the lng of the requested address

				// we'll want everything within, say, 30km distance
				$distance = listings_wp_search_radius();

				// earth's radius in km = ~6371
				$measurement = listings_wp_distance_measurement();
				$radius = $measurement == 'kilometers' ? 6371 : 3950;

				// latitude boundaries
				$maxlat = $lat + rad2deg($distance / $radius);
				$minlat = $lat - rad2deg($distance / $radius);

				// longitude boundaries (longitude gets smaller when latitude increases)
				$maxlng = $lng + rad2deg($distance / $radius / cos(deg2rad($lat)));
				$minlng = $lng - rad2deg($distance / $radius / cos(deg2rad($lat)));

				// build the meta query array
				$radius_array = array( 
					'relation' => 'AND',
				);
				
				$radius_array[] = array(
					'key'     => '_lwp_listing_lat',
					'value'   => array( $minlat, $maxlat ),
					'type'    => 'DECIMAL(10,5)',
					'compare' => 'BETWEEN',
				);
				$radius_array[] = array(
					'key'     => '_lwp_listing_lng',
					'value'   => array( $minlng, $maxlng ),
					'type'    => 'DECIMAL(10,5)',
					'compare' => 'BETWEEN',
				);

				return apply_filters( 'listings_wp_search_radius_args', $radius_array );

			}

		}

	}


	// function to geocode address, it will return false if unable to geocode address
	private function geocode( $address ){

	    // url encode the address
	    $address = urlencode( esc_html( $address ) );
	     
	    // google map geocode api url
	    $url = listings_wp_google_geocode_maps_url( $address );
	 	
	 	$arrContextOptions = array(
		    "ssl" => array(
		        "verify_peer" => false,
		        "verify_peer_name" => false,
		    ),
		); 

	    // get the json response
	    $resp_json = file_get_contents( $url, false, stream_context_create($arrContextOptions) );
	     
	    // decode the json
	    $resp = json_decode( $resp_json, true );

	    //pp( $resp );

	    // response status will be 'OK', if able to geocode given address 
	    if( $resp['status'] == 'OK' ){
	 
	        // get the lat and lng
	        $lat = $resp['results'][0]['geometry']['location']['lat'];
	        $lng = $resp['results'][0]['geometry']['location']['lng'];
	         
	        // verify if data is complete
	        if( $lat && $lng ){
	         
	            return array(
	            	'lat' => $lat,
	            	'lng' => $lng,
	            );
	             
	        } else {
	            return false;
	        }
	         
	    } else {
	        return false;
	    }

	}


	/**
	 * Searches through our custom fields using a keyword match
	 * @return array
	 */
	private function keyword_query( $q ) {
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
			
			$custom_fields = apply_filters( 'listings_wp_keyword_search_fields', array(
		        // put all the meta fields you want to search for here
		        '_lwp_listing_city',
		        '_lwp_listing_zip',
		        '_lwp_listing_state',
		        '_lwp_listing_route',
		        '_lwp_listing_displayed_address',
		    ) );
		    $searchterm = esc_html( $_GET['s'] );

		    // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
		    $q->query_vars['s'] = '';

		    if ( $searchterm != '' ) {

		        $meta_query = array('relation' => 'OR');
		        foreach($custom_fields as $cf) {
		            array_push( $meta_query, array(
		                'key' 		=> $cf,
		                'value' 	=> $searchterm,
		                'compare' 	=> 'LIKE'
		            ));
		        }
		        return $meta_query;
		    };
		}
		return array();
	}

	/**
	 * Return a meta query for filtering by type.
	 * @return array
	 */
	private function type_meta_query() {
		if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) {
			return array(
				'key' 		=> '_lwp_listing_type', 
				'value' 	=> get_query_var( $_GET['type'] ), 
				'compare' 	=> 'LIKE'
			);
		}
		return array();
	}

	/**
	 * Return a meta query for filtering by price.
	 * @return array
	 */
	private function price_meta_query() {
		if ( isset( $_GET['max_price'] ) && ! empty( $_GET['max_price'] ) || isset( $_GET['min_price'] ) && ! empty( $_GET['min_price'] ) ) {
			$min = floatval( $_GET['min_price'] );
			$max = floatval( $_GET['max_price'] );

			return array(
				'key'          => '_lwp_listing_price',
				'value'        => array( $min, $max ),
				'compare'      => 'BETWEEN',
				'type'         => 'DECIMAL',
				'price_filter' => true,
			);
		}
		return array();
	}

	/**
	 * Return a meta query for filtering by number of beds.
	 * @return array
	 */
	private function beds_meta_query() {
		if ( isset( $_GET['max_beds'] ) && ! empty( $_GET['max_beds'] ) || isset( $_GET['min_beds'] ) && ! empty( $_GET['min_beds'] ) ) {
			$min = floatval( $_GET['min_beds'] );
			$max = floatval( $_GET['max_beds'] );

			return array(
				'key'          => '_lwp_listing_bedrooms',
				'value'        => array( $min, $max ),
				'compare'      => 'BETWEEN',
				'type'         => 'DECIMAL',
			);
		}
		return array();
	}
	

}

return new Listings_Wp_Search();