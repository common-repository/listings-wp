<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
    exit;

if ( !class_exists( 'Listings_Wp_Admin_Agent_Columns' ) ): /**
 * Admin columns
 * @version 0.1.0
 */ 
    class Listings_Wp_Admin_Agent_Columns {
        
        /**
         * Constructor
         * @since 0.1.0
         */
        public function __construct() {
            return $this->hooks();
        }
        
        
        public function hooks() {
            if ( isset( $_GET['role'] ) && ( $_GET['role'] == 'listings_wp_agent' || $_GET['role'] == 'administrator' ) ) {
                add_filter( 'manage_users_columns', array(
                     $this,
                    'modify_user_table' 
                ) );
                add_filter( 'manage_users_custom_column', array(
                     $this,
                    'modify_user_table_row' 
                ), 10, 3 );
            }
        }
        
        
        public function modify_user_table( $column ) {
            if ( isset( $_GET['role'] ) && $_GET['role'] == 'listings_wp_agent' ) {
                unset( $column['posts'] );
                unset( $column['role'] );
            }
            $column['listings']     = __( 'Listings', 'listings-wp' );
            $column['mobile']       = __( 'Mobile', 'listings-wp' );
            $column['office_phone'] = __( 'Office Phone', 'listings-wp' );
            return $column;
        }
        
        
        function modify_user_table_row( $val, $column_name, $user_id ) {
            
            switch ( $column_name ) {
                
                case 'listings':
                    return '<a href="' . esc_url( admin_url( 'edit.php?post_type=listing&agents=' . $user_id ) ) . '"><strong>' . listings_wp_agent_listings_count( $user_id ) . '</strong></a>';
                    break;
                
                case 'mobile':
                    return get_the_author_meta( 'mobile', $user_id );
                    break;
                
                case 'name':
                    return get_the_author_meta( 'display_name', $user_id ) . '<br>' . get_the_author_meta( 'title_position', $user_id );
                    break;
                
                case 'office_phone':
                    return get_the_author_meta( 'office_phone', $user_id );
                    break;
                
                default:
                    
            }
            
            return $val;
        }
        
        
        
        
    }
    
    new Listings_Wp_Admin_Agent_Columns;
endif;
