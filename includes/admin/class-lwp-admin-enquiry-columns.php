<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Listings_Wp_Admin_Enquiry_Columns' ) ) :

/**
 * Admin columns
 * @version 0.1.0
 */
class Listings_Wp_Admin_Enquiry_Columns {

    /**
     * fields used for the filter dropdowns
     */
    public $filter_fields = array(
        'listing_title'     => 'listings',
        'listing_agent'     => 'agents',
        'name'              => 'names',
        'email'             => 'emails',
    );

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
        return $this->hooks();
    }


    public function hooks() {
        add_filter( 'manage_listing-enquiry_posts_columns', array( $this, 'enquiry_columns' ) );
        add_action( 'manage_listing-enquiry_posts_custom_column', array( $this, 'enquiry_data' ), 10, 2 );
        
        // sorting
        add_filter( 'manage_edit-listing-enquiry_sortable_columns', array( $this, 'table_sorting' ) );
        add_filter( 'request', array( $this, 'enquiry_orderby_listing' ) );
        add_filter( 'request', array( $this, 'enquiry_orderby_agent' ) );
        add_filter( 'request', array( $this, 'enquiry_orderby_name' ) );
        add_filter( 'request', array( $this, 'enquiry_orderby_email' ) );

        // filtering
        add_action( 'restrict_manage_posts', array( $this, 'table_filtering' ) );
        add_action( 'parse_query', array( $this, 'filter' ) );
    }

    /**
     * Set columns for listing
     */
    public function enquiry_columns( $defaults ) {

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

        $defaults['listing']    = __( 'Listing', 'listings-wp' );
        $defaults['agent']      = __( 'Agent', 'listings-wp' );
        $defaults['name']       = __( 'From Name', 'listings-wp' );
        $defaults['email']      = __( 'From Email', 'listings-wp' );

        return $defaults;
    }

    public function enquiry_data( $column_name, $post_id ) {
        
        $listing_id = listings_wp_enquiry_meta( 'listing_id', $post_id );    

        if ( $column_name == 'listing' ) {
                    
            if( ! $listing_id )
                return;

            echo '<a title="' . __( 'Edit Listing', 'listings-wp' ) . '" target="_blank" href="' . esc_url( get_edit_post_link( $listing_id ) ) . '">' . esc_html( get_the_title( $listing_id ) ) . ' <span class="dashicons dashicons-external"></span></a><br>'; 
            echo esc_html( listings_wp_meta( 'displayed_address', $listing_id ) ); 

        }

        if ( $column_name == 'agent' ) {
            $agent = listings_wp_enquiry_meta( 'listing_agent', $post_id );            
            if( ! $agent )
                return;

            echo esc_html( get_the_author_meta( 'display_name', $agent ) ); 
        }

        if ( $column_name == 'name' ) {
            $name = listings_wp_enquiry_meta( 'name', $post_id );            
            if( ! $name )
                return;

            echo esc_html( $name ); 
        }

        if ( $column_name == 'email' ) {
            $email = listings_wp_enquiry_meta( 'email', $post_id );            
            if( ! $email )
                return;

            echo esc_html( $email ); 
        }

    }


    /*
     * Sorting the table
     */
    function table_sorting( $columns ) {
        $columns['listing']     = 'listing_title';
        $columns['agent']       = 'listing_agent';
        $columns['name']        = 'name';
        $columns['email']       = 'email';
        return $columns;
    }


    function enquiry_orderby_listing( $vars ) {
        if ( isset( $vars['orderby'] ) && 'listing' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_enquiry_listing_title',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function enquiry_orderby_agent( $vars ) {
        if ( isset( $vars['orderby'] ) && 'agent' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_enquiry_listing_agent',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function enquiry_orderby_name( $vars ) {
        if ( isset( $vars['orderby'] ) && 'name' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_enquiry_name',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    function enquiry_orderby_email( $vars ) {
        if ( isset( $vars['orderby'] ) && 'email' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => '_lwp_enquiry_email',
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }
    


    function table_filtering() {
        global $pagenow;
        $type = get_post_type() ? get_post_type() : 'listing-enquiry';
        if ( isset( $_GET['post_type'] ) ) {
            $type = $_GET['post_type'];
        }

        //only add filter to post type you want
        if ( 'listing-enquiry' == $type && is_admin() && $pagenow == 'edit.php' ) {

            $fields = $this->build_fields();

            if( $fields ) {

                foreach ( $fields as $field => $values ) {
                    asort( $values ); // sort our values
                    $values = array_unique( $values ); // make them unique
                    
                    ?>
                        <select name='<?php echo esc_attr( $field ); ?>' id='<?php echo esc_attr( $field ); ?>' class='postform'>
                            
                            <option value=''><?php printf( __( 'Show all %s', 'listings-wp' ), $field ) ?></option>

                            <?php foreach ( $values as $val => $text ) { 
                                $text = $field == 'agents' ? get_the_author_meta( 'display_name', $val ) : $text;
                                if( empty( $val ) ) 
                                    continue;
                                ?>
                                    <option value="<?php echo esc_attr( $val ) ?>" <?php isset( $_GET[$field] ) ? selected( $_GET[$field], $val ) : ''; ?>><?php echo esc_html( $text ) ?></option>

                            <?php
                            } ?>

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
            'post_type'         => 'listing-enquiry', 
            'posts_per_page'    => '-1', 
            'post_status'       => 'publish',
        );

        $listings = query_posts( $args );

        // The Loop
        if ( $listings ) {

            $fields = array();

            foreach ( $listings as $listing ) {
                foreach ( $this->filter_fields as $field => $text ) {
                    
                    $val = listings_wp_enquiry_meta( $field, $listing->ID );
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
        $type = get_post_type() ? get_post_type() : 'listing-enquiry';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if ( 'listing-enquiry' == $type && is_admin() && $pagenow == 'edit.php' ) {
            
            foreach ( $this->filter_fields as $field => $text ) {

                if( isset( $_GET[$text] ) && $_GET[$text] != '' ) {
                    $query->query_vars['meta_key']      = '_lwp_enquiry_' . $field;
                    $query->query_vars['meta_value']    = $_GET[$text];
                }
            }

        }

    }


}

new Listings_Wp_Admin_Enquiry_Columns;

endif;