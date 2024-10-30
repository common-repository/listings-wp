<?php
/**
 * Setup menus in WP admin.
 *
 * @author   Listings_Wp
 * @category Admin
 * @package  Listings_Wp/Admin
 * @version  1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Listings_Wp_Admin_Listings_Menu' ) ) :

/**
 * Listings_Wp_Admin_Menus Class.
 */
class Listings_Wp_Admin_Listings_Menu {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'listings_menu' ), 9 );
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
	}

	/**
	 * Add menu item.
	 */
	public function listings_menu() {
		add_submenu_page( 'edit.php?post_type=listing', __( 'Agents', 'listings-wp' ), __( 'Agents', 'listings-wp' ), 'publish_listings', 'users.php?role=listings_wp_agent' );
	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the wanted admin (sub-) menu items for the CPT.
	 */
	function menu_highlight() {
		global $parent_file, $submenu_file;
		if( isset( $_GET['role'] ) && $_GET['role'] == 'listings_wp_agent' ) {
			$parent_file 	= 'edit.php?post_type=listing';
			$submenu_file 	= 'users.php?role=listings_wp_agent';
		}
	}


}

endif;

return new Listings_Wp_Admin_Listings_Menu();
