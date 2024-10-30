<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Listings_Wp_Admin_Listing_Columns' ) ) :

/**
 * Admin columns
 * @version 0.1.0
 */
class Listings_Wp_Admin_Listing_Columns {

    /**
     * fields used for the filter dropdowns
     */
    public $filter_fields = array(
        'status'    => 'statuses',
        'type'      => 'types',
        'agent'     => 'agents',
        'purpose'   => 'purposes',
    );

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
        return $this->hooks();
    }


    public function hooks() {
        add_filter( 'manage_listing_posts_columns', array( $this, 'listing_columns' ) );
        add_action( 'manage_listing_posts_custom_column', array( $this, 'listing_data' ), 10, 2 );
        
        // sorting
        add_filter( 'manage_edit-listing_sortable_columns', array( $this, 'table_sorting' ) );
        add_filter( 'request', array( $this, 'listing_orderby_status' ) );
        add_filter( 'request', array( $this, 'listing_orderby_type' ) );
        add_filter( 'request', array( $this, 'listing_orderby_agent' ) );
        add_filter( 'request', array( $this, 'listing_orderby_purpose' ) );
        add_filter( 'request', array( $this, 'listing_orderby_price' ) );

        // filtering
        add_action( 'restrict_manage_posts', array( $this, 'table_filtering' ) );
        add_action( 'parse_query', array( $this, 'filter' ) );
    }

    /**
     * Set columns for listing
     */
    public function listing_columns( $defaults ) {

        $post_type  = $_GET['post_type'];

        $columns    = array();
        $taxonomies = array();
        
        /* Get taxonomies that should appear in the manage posts table. */
        $taxonomies = get_object_taxonomies( $post_type, 'objects');
        $taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name');

        /* Allow devs to filter the taxonomy columns. */
        $taxonomies = apply_filters("manage_taxonomies_for_listings_wp_{$post_type}_columns", $taxonomies, $post_type);
        $taxonomies = array_filter($taxonomies, 'taxonomy_exists');

        /* Loop through each taxonomy and add it as a column. */
        foreach ( $taxonomies as $taxonomy ) {
            $columns[ 'taxonomy-' . $taxonomy ] = get_taxonomy($taxonomy)->labels->name;
        }
        $defaults['status']     = __( 'Status', 'listings-wp' );
        $defaults['type']       = __( 'Type', 'listings-wp' );
        $defaults['agent']      = __( 'Agent', 'listings-wp' );
        $defaults['purpose']    = __( 'Purpose', 'listings-wp' );
        $defaults['price']      = __( 'Price', 'listings-wp' );
        $defaults['address']    = __( 'Address', 'listings-wp' );
        // $defaults['featured']   = __( 'Featured', 'listings-wp' );
        $defaults['image']      = '<span class="dashicons dashicons-images-alt2"></span>';
        $defaults['enquiries']  = __( 'Enquiries', 'listings-wp' );
        $defaults['id']         = __( 'ID', 'listings-wp' );

        return $defaults;
    }

    public function listing_data( $column_name, $post_id ) {
        
        if ( $column_name == 'id' ) {
            echo '<span class="id">#' . esc_html( $post_id ) . '</div>'; 

        }

        if ( $column_name == 'status' ) {
            $status = listings_wp_meta( 'status', $post_id );            
            if( ! $status )
                return;

            echo '<span class="btn status ' . esc_attr( strtolower( $status ) ) . '">' . esc_html( $status ) . '</div>'; 

        }

        if ( $column_name == 'type' ) {
            $type = listings_wp_meta( 'type', $post_id );            
            if( ! $type )
                return;

            echo '<span class="btn type ' . esc_attr( strtolower( $type ) ) . '">' . esc_html( $type ) . '</div>'; 
        }

        if ( $column_name == 'agent' ) {
            $agent_id   = listings_wp_meta( 'agent', $post_id );            
            $agent      = get_the_author_meta( 'display_name', $agent_id );            
            if( ! $agent || ! $agent_id )
                return;
            echo esc_html( $agent ); 
        }

        if ( $column_name == 'purpose' ) {
            $purpose = listings_wp_meta( 'purpose', $post_id );            
            if( ! $purpose )
                return;

            echo esc_html( $purpose ); 
        }

        if ( $column_name == 'price' ) {
            $price = listings_wp_meta( 'price', $post_id );            
            if( ! $price )
                return;

            echo listings_wp_price( $price ); 
        }

        if ( $column_name == 'address' ) {
            $address = listings_wp_meta( 'displayed_address', $post_id );            
            if( ! $address )
                return;

            echo esc_html( $address ); 
        }

        if ( $column_name == 'image' ) {
            $images = listings_wp_meta( 'image_gallery', $post_id );            
            if( ! $images )
                return;
            $image  = array_keys( $images );
            $img    = wp_get_attachment_image_src( $image[0], 'thumbnail' );
            if ( $img ) :
                echo '<img src="' . esc_url( $img[0] ) . '" width="50" height="50" />';
            endif; 
        }

        if ( $column_name == 'enquiries' ) {
            $enquiries = listings_wp_meta( 'enquiries', $post_id );  
            $count = ! empty( $enquiries ) ? count( $enquiries ) : 0;
            if( $count > 0 ) {
                echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=listing-enquiry' ) ) . '"><span>' . esc_html( $count ) . '</a></span>';
            } else {
                echo '—';
            }

        }


    }


    /*
     * Sorting the table
     */
    function table_sorting( $columns ) {
        $columns['status']     = 'status';
        $columns['type']       = 'type';
        $columns['agent']      = 'agent';
        $columns['purpose']    = 'purpose';
        $columns['price']      = 'price';
        return $columns;
    }


    function listing_orderby_status( $vars ) {
        if ( isset( $vars['orderby'] ) && 'status' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_listing_status',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function listing_orderby_type( $vars ) {
        if ( isset( $vars['orderby'] ) && 'type' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_listing_type',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function listing_orderby_agent( $vars ) {
        if ( isset( $vars['orderby'] ) && 'agent' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_listing_agent',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function listing_orderby_purpose( $vars ) {
        if ( isset( $vars['orderby'] ) && 'purpose' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_listing_purpose',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function listing_orderby_price( $vars ) {
        if ( isset( $vars['orderby'] ) && 'price' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_listing_price',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }




    function table_filtering() {
        global $pagenow;
        $type = get_post_type() ? get_post_type() : 'listing';
        if ( isset( $_GET['post_type'] ) ) {
            $type = $_GET['post_type'];
        }

        //only add filter to post type you want
        if ( 'listing' == $type && is_admin() && $pagenow == 'edit.php' ) {

            $fields = $this->build_fields();

            if( $fields ) {

                foreach ( $fields as $field => $values ) {
                    asort( $values ); // sort our values
                    $values = array_unique( $values ); // make them unique
                    
                    ?>
                        <select name='<?php echo esc_attr( $field ); ?>' id='<?php echo esc_attr( $field ); ?>' class='postform'>
                            
                            <option value=''><?php printf( __( 'Show all %s', 'listings-wp' ), $field ) ?></option>

                            <?php foreach ( $values as $val => $text ) { 

                                $text = $field == 'agents' ? get_the_author_meta( 'display_name', $val ) : $text; ?>
                                <option value="<?php echo esc_attr( $val ) ?>" <?php isset( $_GET[$field] ) ? selected( $_GET[$field], $val ) : ''; ?>><?php echo esc_html( $text ) ?></option>

                            <?php } ?>

                        </select>

                    <?php 
                    
                    reset( $values );
                }

            }

        }

    }


    /**
     * Build the dropdown field values for the filtering
     *
     */
    private function build_fields(){

        $fields = '';
        $output = '';

        // The Query args
        $args = array( 
            'post_type'         => 'listing', 
            'posts_per_page'    => '-1', 
            'post_status'       => 'publish',
        );

        $listings = query_posts( $args );

        // The Loop
        if ( $listings ) {

            $fields = array();

            foreach ( $listings as $listing ) {
                foreach ( $this->filter_fields as $field => $text ) {
                    
                    $val = listings_wp_meta( $field, $listing->ID );
                    $fields[$text][$val] = $val;    

                }

            }
        }

        /* Restore original Post Data */
        wp_reset_query();

        return $fields;

    }



    function filter( $query ){
        global $pagenow;
        $type = get_post_type() ? get_post_type() : 'listing';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if ( 'listing' == $type && is_admin() && $pagenow == 'edit.php' ) {
            
            foreach ( $this->filter_fields as $field => $text ) {

                if( isset( $_GET[$text] ) && $_GET[$text] != '' ) {
                    $query->query_vars['meta_key'] = '_lwp_listing_' . $field;
                    $query->query_vars['meta_value'] = $_GET[$text];
                }

            }

        }
    }


}

new Listings_Wp_Admin_Listing_Columns;

endif;