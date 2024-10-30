<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'cmb2_admin_init', 'listings_wp_options_page' );

function listings_wp_options_page() {
	
	$listing_label     = __( 'Listing', 'listings-wp' );
	$listings_label    = __( 'Listings', 'listings-wp' );
    // the options key fields will be saved under
    $opt_key = 'listings_wp_options';

    // the show_on parameter for configuring the CMB2 box, this is critical!
    $show_on = array( 'key' => 'options-page', 'value' => array( $opt_key ) );

    // an array to hold our boxes
    $boxes = array();

    // an array to hold some tabs
    $tabs = array();

    /* 
     * Tabs - an array of configuration arrays.
     */
    $tabs[] = array(
        'id'    => 'general',
        'title' => 'General',
        'desc'  => '',
        'boxes' => array(
            'price_format',
            'google_maps',
            'search',
        ),
    );

    $tabs[] = array(
        'id'    => 'listings',
        'title' => sprintf( __( '%s', 'listings-wp' ), $listings_label ),
        'desc'  => '',
        'boxes' => array(
            'listing_setup',
            'listing_attributes',
            'listing_types',
            'listing_features',
            'listing_statuses',
         ),
    );

    $tabs[] = array(
        'id'    => 'display',
        'title' => 'Display',
        'desc'  => '',
        'boxes' => array(
            'columns',
            'gallery_settings',
            'icons',
        ),
    );

    $tabs[] = array(
        'id'    => 'contact',
        'title' => 'Contact Form',
        'desc'  => '',
        'boxes' => array(
            'contact_form',
            'contact_form_email',
            'contact_form_messages',
        ),
    );

    $tabs[] = array(
        'id'    => 'advanced',
        'title' => 'Advanced',
        'desc'  => '',
        'boxes' => array(
            'template_html',
            'uninstall',
        ),
    );

    $tabs[] = array(
        'id'    => 'extensions',
        'title' => 'Extensions',
        'desc'  => '',
        'boxes' => array(
             'extensions',
        ),
    );

/* ======================================================================================
                                        General Options
   ====================================================================================== */

    // price_format
    $cmb = new_cmb2_box( array(
        'id'        => 'price_format',
        'title'     => __( 'Price Format', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'Currency Position', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'currency_position',
        'type'       => 'select',
        'options' => array(
            'left'          => __( 'Left ($100)', 'listings-wp' ),
            'right'         => __( 'Right (100$)', 'listings-wp' ),
            'left_space'    => __( 'Left with space ($ 100)', 'listings-wp' ),
            'right_space'   => __( 'Right with space (100 $)', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Currency Symbol', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'currency_symbol',
        'type'       => 'text',
        'default'    => '$',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Thousand Separator', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'thousand_separator',
        'type'       => 'text',
        'default'    => ',',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Include Decimals', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'include_decimals',
        'type'       => 'select',
        'options' => array(
            'no'      => __( 'No, do not include decimals in price', 'listings-wp' ),
            'yes'     => __( 'Yes, include decimals in price', 'listings-wp' ),
        ),
        'default'    => 'no',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Decimal Separator', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'decimal_separator',
        'type'       => 'text',
        'default'    => '.',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Number of Decimals', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'decimals',
        'type'       => 'text',
        'default'    => '2',
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // maps
    $cmb = new_cmb2_box( array(
        'id'        => 'google_maps',
        'title'     => __( 'Google Maps', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'API Key', 'listings-wp' ),
        'before_row' => sprintf( __( 'A Google Maps API Key is required to be able to show the maps. It\'s free and you can get yours %s.', 'listings-wp' ), '<strong><a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">here</a></strong>' ),
        'id'         => 'maps_api_key',
        'type'       => 'text',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Map Zoom', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'map_zoom',
        'type'       => 'text',
        'default'    => '14',
        'attributes'    => array(
            'type' => 'number',
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Map Height', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'map_height',
        'type'       => 'text',
        'default'    => '300',
        'attributes'    => array(
            'type' => 'number',
        ),
    ));
    
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // maps
    $cmb = new_cmb2_box( array(
        'id'        => 'search',
        'title'     => __( 'Search', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'Distance Measurement', 'listings-wp-related' ),
        'before_row' => __( 'These settings relate to the [listings_wp_search] shortcode.', 'listings-wp' ),
        'desc'       => __( 'Choose miles or kilometers for the radius.', 'listings-wp-related' ),
        'id'         => 'distance_measurement',
        'type'       => 'select',
        'options' => array(
            'miles'         => __( 'Miles', 'listings-wp-related' ),
            'kilometers'    => __( 'Kilometers', 'listings-wp-related' ),
        ),
    ));

    $cmb->add_field( array(
        'name'       => __( 'Radius', 'listings-wp-related' ),
        'desc'       => __( 'Show listings that are within this distance (mi or km as selected above).', 'listings-wp-related' ),
        'id'         => 'search_radius',
        'type'       => 'text',
        'default'    => '20',
        'atributes' => array(
            'type'   => 'number',
            'placeholder'   => '20',
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Country', 'listings-wp' ),
        'desc'       => sprintf( __( 'Country name or two letter %s country code.', 'listings-wp' ), '<a target="_blank" href="https://en.wikipedia.org/wiki/ISO_3166-1">ISO 3166-1</a>' ),
        'id'         => 'search_country',
        'type'       => 'text',
    ));


    
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

/* ======================================================================================
                                        Listing Options
   ====================================================================================== */
    
    // listings setup
    $cmb = new_cmb2_box( array(
        'id'        => 'listing_setup',
        'title'     => __( 'Listing Setup', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'Rent / Sell', 'listings-wp' ),
        'desc'       => __( 'Are your listings only for rent or only for sale? Or both?', 'listings-wp' ),
        'id'         => 'display_purpose',
        'type'       => 'select',
        'default'    => 'both',
        'options' => array(
            'both'    => __( 'Both', 'listings-wp' ),
            'rent'    => __( 'Rent Only', 'listings-wp' ),
            'sell'    => __( 'Sell Only', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Default Listing Type', 'listings-wp' ),
        'desc'       => __( 'If the above is set to "Both", which type would you like displayed as the default on the listings page?', 'listings-wp' ),
        'id'         => 'display_default',
        'type'       => 'select',
        'options' => array(
            'Sell'    => __( 'Sell', 'listings-wp' ),
            'Rent'    => __( 'Rent', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Listings Page', 'listings-wp' ),
        'desc'       => __( 'The main page to display your listings.', 'listings-wp' ) . '<br>' . 
        sprintf( __( 'Please visit %s if this options is changed.', 'listings-wp' ), '<a target="_blank" href="' . admin_url( 'options-permalink.php' ) . '">Settings -> Permalinks</a>' ),
        'id'         => 'archives_page',
        'type'       => 'select',
        'options_cb'    => 'listings_wp_get_pages',
    ));

    $cmb->add_field( array(
        'name'       => __( 'Force Listings Page Title', 'listings-wp-related' ),
        'desc'       => __( 'If your page title is not displaying correctly, you can force the page title here.', 'listings-wp-related' )  . '<br>' . __( '(Some themes may be using incorrect template tags to display the archive title. This forces the title within the page)', 'listings-wp-related' ),
        'id'         => 'archives_page_title',
        'type'       => 'select',
        'default'    => 'no',
        'options'    => array(
            'no'    => __( 'No', 'listings-wp' ),
            'yes'   => __( 'Yes', 'listings-wp' ),
        ),
    ));

    $cmb->add_field( array(
        'name'       => __( 'Single Listing URL', 'listings-wp' ),
        'desc'       => __( 'The single listing URL (or slug).', 'listings-wp' ) . '<br>' . 
        sprintf( __( 'Please visit %s if this options is changed.', 'listings-wp' ), '<a target="_blank" href="' . admin_url( 'options-permalink.php' ) . '">Settings -> Permalinks</a>' ),
        'id'         => 'single_url',
        'type'       => 'text',
        'default'    => 'listing',
    ));




    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;


    // listing types
    $cmb = new_cmb2_box( array(
        'id'        => 'listing_types',
        'title'     => sprintf( __( '%s Types', 'listings-wp' ), $listing_label ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'Type', 'listings-wp' ),
        'before_row' => __( 'Once Types have been added here, they are then available in the Type dropdown field when adding or editing a listing.', 'listings-wp' ),
        'after'  	 => '<p class="cmb2-metabox-description">' . __( 'Users can search by different Types when using the advanced search box.', 'listings-wp' ) . '</p>',
        'id'         => 'listing_type',
        'type'       => 'text',
        'repeatable' => true,
        'text' => array(
	        'add_row_text' => __( 'Add Type', 'listings-wp' ),
	    ),
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // listing types
    $cmb = new_cmb2_box( array(
        'id'        => 'listing_features',
        'title'     => sprintf( __( '%s Features', 'listings-wp' ), $listing_label ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( 'Internal Features', 'listings-wp' ),
        'before_row' => __( 'Once Features have been added here, they are then available as checkboxes when adding or editing a listing.', 'listings-wp' ),
        'after'  	 => '<p class="cmb2-metabox-description">' . sprintf( __( 'Internal Features such as Open Fireplace, Gas Heating, Dishwasher etc.', 'listings-wp' ), $listing_label ) . '</p>',
        'id'         => 'internal_feature',
        'type'       => 'text',
        'repeatable' => true,
        'text' => array(
	        'add_row_text' => __( 'Add Feature', 'listings-wp' ),
	    ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'External Features', 'listings-wp' ),
        'after'         => '<p class="cmb2-metabox-description">' . sprintf( __( 'External Features such as Balcony, Shed, Outdoor Entertaining etc.', 'listings-wp' ), $listing_label ) . '</p>',
        'id'         	=> 'external_feature',
        'type'       	=> 'text',
        'repeatable' 	=> true,
        'text' => array(
	        'add_row_text' => __( 'Add Feature', 'listings-wp' ),
	    ),
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;


    $cmb = new_cmb2_box( array(
        'id'        => 'listing_statuses',
        'title'     => sprintf( __( '%s Statuses', 'listings-wp' ), $listing_label ),
        'show_on'   => $show_on,
    ));

    // listing statuses
    $group_field_id = $cmb->add_field( array(
        'type'        => 'group',
        'name'        => __( '', 'listings-wp' ),
        'before_group'  => __( 'Once Statuses have been added here, they are then available in the Status dropdown field when adding or editing a listing.', 'listings-wp' ) . '<br>' . __( 'Statuses appear in a styled box over the listing\'s image.', 'listings-wp' ),
        'id'          => 'listing_status',
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Status {#}', 'listings-wp' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Status', 'listings-wp' ),
            'remove_button' => __( 'Remove Status', 'listings-wp' ),
            'sortable'      => false, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    $cmb->add_group_field( $group_field_id, array(
        'name'          => __( 'Status', 'listings-wp' ),
        'id'            => 'status',
        'type'          => 'text',
    ));
    $cmb->add_group_field( $group_field_id, array(
        'name'          => __( 'Background Color', 'listings-wp' ),
        'id'            => 'bg_color',
        'type'          => 'colorpicker',
    ));
    $cmb->add_group_field( $group_field_id, array(
        'name'      	=> __( 'Text Color', 'listings-wp' ),
        'id'         	=> 'text_color',
        'type'       	=> 'colorpicker',
    ));
    $cmb->add_group_field( $group_field_id, array(
        'name'          => __( 'Icon Class', 'listings-wp' ),
        'id'            => 'icon',
        'type'          => 'text',
        'attributes'    => array( 
            'placeholder' => 'lwp-icon-tick-1',
        ),
        'desc'          => sprintf( __( 'Add icon class to display an icon. See %s for available icons.', 'listings-wp' ), '<a target="_blank" href="http://www.listings-wp.com/docs/icons?utm_source=plugin&utm_medium=settings_page&utm_content=icon_docs">icon docs</a>' ) . '<br>' . __( 'Can also use others such as FontAwesome if they are already installed on your site.', 'listings-wp' ),
    ));


    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;


/* ======================================================================================
                                        Display Options
   ====================================================================================== */

    // columns
    $cmb = new_cmb2_box( array(
        'id'        => 'columns',
        'title'     => __( 'Columns', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'          => __( 'Number of Columns', 'listings-wp' ),
        'desc'          => __( 'The number of columns to display on the archive page, when viewing listings in grid mode.', 'listings-wp' ),
        'id'            => 'grid_columns',
        'type'          => 'select',
        'default'       => '3',
        'options' => array(
            '2'     => __( '2 Column', 'listings-wp' ),
            '3'     => __( '3 Column', 'listings-wp' ),
            '4'     => __( '4 Column', 'listings-wp' ),
        ),
    ));

    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // gallery_settings
    $cmb = new_cmb2_box( array(
        'id'        => 'gallery_settings',
        'title'     => __( 'Gallery Settings', 'listings-wp' ),
        'show_on'   => $show_on,
    ));

    $cmb->add_field( array(
        'name'          => __( 'Auto Slide Images', 'listings-wp' ),
        'desc'          => __( 'Should images start to slide automatically?', 'listings-wp' ),
        'id'            => 'auto_slide',
        'type'          => 'select',
        'default'       => 'yes',
        'options' => array(
            'yes'     => 'Yes',
            'no'      => 'No',
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Transition Delay', 'listings-wp' ),
        'desc'          => __( 'The time (in ms) between each auto transition', 'listings-wp' ),
        'id'            => 'slide_delay',
        'type'          => 'text',
        'default'       => '2000',
        'attributes' => array(
            'type'          => 'number',
            'placeholder'   => '2000',
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Transition Duration', 'listings-wp' ),
        'desc'          => __( 'Transition duration (in ms)', 'listings-wp' ),
        'id'            => 'slide_duration',
        'type'          => 'text',
        'default'       => '1000',
        'attributes' => array(
            'type'          => 'number',
            'placeholder'   => '1000',
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Thumbnails Shown', 'listings-wp' ),
        'desc'          => sprintf( __( 'Number of thumbnails shown below main image', 'listings-wp' ), $listing_label ),
        'id'            => 'thumbs_shown',
        'type'          => 'text',
        'default'       => '6',
        'attributes' => array(
            'type'          => 'number',
            'placeholder'   => '6',
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Transition Type', 'listings-wp' ),
        'desc'          => __( 'Should images slide or fade?', 'listings-wp' ),
        'id'            => 'gallery_mode',
        'type'          => 'select',
        'default'       => 'fade',
        'options' => array(
            'fade'     => 'Fade',
            'slide'    => 'Slide',
        ),
    ));

    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    
    // icons
    $cmb = new_cmb2_box( array(
        'id'        => 'icons',
        'title'     => __( 'Icons', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'          => __( 'Tick Icon', 'listings-wp' ),
        'desc'          => sprintf( __( 'The icon to show next to each feature on the External & Internal Feature list.', 'listings-wp' ), $listing_label ),
        'id'            => 'tick_icon',
        'type'          => 'radio',
        'default'       => 'tick-7',
        'options' => array(
            'tick-1'     => '<i class="lwp-icon-tick-1"></i>',
            'tick-2'     => '<i class="lwp-icon-tick-2"></i>',
            'tick-3'     => '<i class="lwp-icon-tick-3"></i>',
            'tick-4'     => '<i class="lwp-icon-tick-4"></i>',
            'tick-5'     => '<i class="lwp-icon-tick-5"></i>',
            'tick-6'     => '<i class="lwp-icon-tick-6"></i>',
            'tick-7'     => '<i class="lwp-icon-tick-7"></i>',
            'none'       => __( 'No Icon', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Arrow Icon', 'listings-wp' ),
        'desc'          => sprintf( __( 'The icon for the prev & next arrows on the gallery.', 'listings-wp' ), $listing_label ),
        'id'            => 'arrow_icon',
        'type'          => 'radio',
        'default'       => 'arrow-2',
        'options' => array(
            'arrow-1'     => '<i class="lwp-icon-arrow-1"></i>',
            'arrow-2'     => '<i class="lwp-icon-arrow-2"></i>',
            'arrow-3'     => '<i class="lwp-icon-arrow-3"></i>',
            'arrow-4'     => '<i class="lwp-icon-arrow-4"></i>',
            'arrow-5'     => '<i class="lwp-icon-arrow-5"></i>',
            'arrow-6'     => '<i class="lwp-icon-arrow-6"></i>',
            'arrow-7'     => '<i class="lwp-icon-arrow-7"></i>',
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Bedroom Icon', 'listings-wp' ),
        'desc'          => sprintf( __( 'The icon to show number of bedrooms.', 'listings-wp' ), $listing_label ),
        'id'            => 'bed_icon',
        'type'          => 'radio',
        'default'       => 'bed-2',
        'options' => array(
            'bed-1'     => '<i class="lwp-icon-bed-1"></i>',
            'bed-2'     => '<i class="lwp-icon-bed-2"></i>',
            'bed-3'     => '<i class="lwp-icon-bed-3"></i>',
            'bed-4'     => '<i class="lwp-icon-bed-4"></i>',
            'bed-5'     => '<i class="lwp-icon-bed-5"></i>',
            'bed-6'     => '<i class="lwp-icon-bed-6"></i>',
            'bed-7'     => '<i class="lwp-icon-bed-7"></i>',
            'none'      => __( '"Bedrooms"', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Bathroom Icon', 'listings-wp' ),
        'desc'          => sprintf( __( 'The icon to show number of bathrooms.', 'listings-wp' ), $listing_label ),
        'id'            => 'bath_icon',
        'type'          => 'radio',
        'default'       => 'bath-2',
        'options' => array(
            'bath-1'     => '<i class="lwp-icon-bath-1"></i>',
            'bath-2'     => '<i class="lwp-icon-bath-2"></i>',
            'bath-3'     => '<i class="lwp-icon-bath-3"></i>',
            'bath-4'     => '<i class="lwp-icon-bath-4"></i>',
            'bath-5'     => '<i class="lwp-icon-bath-5"></i>',
            'bath-6'     => '<i class="lwp-icon-bath-6"></i>',
            'bath-7'     => '<i class="lwp-icon-bath-7"></i>',
            'none'       => __( '"Bathrooms"', 'listings-wp' ),
        ),
    ));
    $cmb->add_field( array(
        'name'          => __( 'Car Spaces Icon', 'listings-wp' ),
        'desc'          => sprintf( __( 'The icon to show number of car spaces.', 'listings-wp' ), $listing_label ),
        'id'            => 'car_icon',
        'type'          => 'radio',
        'default'       => 'car-4',
        'options' => array(
            'car-1'     => '<i class="lwp-icon-car-1"></i>',
            'car-2'     => '<i class="lwp-icon-car-2"></i>',
            'car-3'     => '<i class="lwp-icon-car-3"></i>',
            'car-4'     => '<i class="lwp-icon-car-4"></i>',
            'car-5'     => '<i class="lwp-icon-car-5"></i>',
            'car-6'     => '<i class="lwp-icon-car-6"></i>',
            'car-7'     => '<i class="lwp-icon-car-7"></i>',
            'none'      => __( '"Car Spaces"', 'listings-wp' ),
        ),
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;




/* ======================================================================================
                                        Contact Form
   ====================================================================================== */

    // contact form
    $cmb = new_cmb2_box( array(
        'id'        => 'contact_form',
        'title'     => __( 'Contact Form Settings', 'listings-wp' ),
        'show_on'   => $show_on,
        
    ));
    $cmb->add_field( array(
        'name'       => __( 'Email From', 'listings-wp' ),
        'desc'       => __( 'The "from" address for all enquiry emails that are sent to Agents.', 'listings-wp' ),
        'id'         => 'email_from',
        'type'       => 'text_email',
        'default'   => get_bloginfo( 'admin_email' ),
        'before_row'      => '<p class="cmb2-metabox-description">' . __( 'Contact form enquiries are sent directly to the selected Agent on that listing.', 'listings-wp' ) . '</p>',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Email From Name', 'listings-wp' ),
        'desc'       => __( 'The "from" name for all enquiry emails that are sent to Agents.', 'listings-wp' ),
        'id'         => 'email_from_name',
        'type'       => 'text',
        'default'   => get_bloginfo( 'name' ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'CC', 'listings-wp' ),
        'desc'       => __( 'Extra email addresses that are CC\'d on every enquiry (comma separated).', 'listings-wp' ),
        'id'         => 'contact_form_cc',
        'type'       => 'text',
        'attributes' => array( 
            'placeholder' => 'somebody@somewhere.com',
        ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'BCC', 'listings-wp' ),
        'desc'       => __( 'Extra email addresses that are BCC\'d on every enquiry (comma separated).', 'listings-wp' ),
        'id'         => 'contact_form_bcc',
        'type'       => 'text',
        'attributes' => array( 
            'placeholder' => 'somebody@somewhere.com',
        ),
    ));
    
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;



    // contact form email
    $cmb = new_cmb2_box( array(
        'id'        => 'contact_form_email',
        'title'     => __( 'Contact Form Email', 'listings-wp' ),
        'show_on'   => $show_on,
        'desc'      => __( '', 'listings-wp' ),
    ));
    
    $cmb->add_field( array(
        'name'       => __( 'Email Type', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'contact_form_email_type',
        'type'       => 'select',
        'options' => array(
            'html_email'     => __( 'HTML', 'listings-wp' ),
            'text_email'     => __( 'Plain Text', 'listings-wp' ),
        ),
        'default'    => 'html_email',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Email Subject', 'listings-wp' ),
        'desc'       => __( '', 'listings-wp' ),
        'id'         => 'contact_form_subject',
        'type'       => 'text',
        'default'    => __( 'New enquiry on listing #{listing_id}', 'listings-wp' ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Email Message', 'listings-wp' ),
        'desc'       => __( 'Content of the email that is sent to the agent (and other email addresses above). ' .
            'Available tags are:<br>' . 
            '{agent_name}<br>' . 
            '{listing_title}<br>' . 
            '{listing_id}<br>' . 
            '{enquiry_name}<br>' . 
            '{enquiry_email}<br>' . 
            '{enquiry_phone}<br>' . 
            '{enquiry_message}<br>'
            , 'listings-wp' ),
        'default'    => __( 'Hi {agent_name},', 'listings-wp' ) . "\r\n" .
                        __( 'There has been a new enquiry on <strong>{listing_title}</strong>', 'listings-wp' ) . "\r\n" .
                        '<hr>' . "\r\n" .
                        __( 'Name: {enquiry_name}', 'listings-wp' ) . "\r\n" .
                        __( 'Email: {enquiry_email}', 'listings-wp' ) . "\r\n" .
                        __( 'Phone: {enquiry_phone}', 'listings-wp' ) . "\r\n" .
                        __( 'Message: {enquiry_message}', 'listings-wp' ) . "\r\n" .
                        '<hr>',
        'id'         => 'contact_form_message',
        'type'       => 'textarea',
    ));
    
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;


    // contact form messages
    $cmb = new_cmb2_box( array(
        'id'        => 'contact_form_messages',
        'title'     => __( 'Contact Form Messages', 'listings-wp' ),
        'show_on'   => $show_on,
        'desc'      => __( '', 'listings-wp' ),
    ));
    
    $cmb->add_field( array(
        'name'       => __( 'Success Message', 'listings-wp' ),
        'desc'       => __( 'The message that is displayed to users upon successfully sending a message.', 'listings-wp' ),
        'id'         => 'contact_form_success',
        'type'       => 'text',
        'default'    => __( 'Thank you, the agent will be in touch with you soon.', 'listings-wp' ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Error Message', 'listings-wp' ),
        'desc'       => __( 'The message that is displayed if there is an error sending the message.', 'listings-wp' ),
        'id'         => 'contact_form_error',
        'type'       => 'text',
        'default'    => __( 'There was an error. Please try again.', 'listings-wp' ),
    ));
    $cmb->add_field( array(
        'name'       => __( 'Include Error Code', 'listings-wp' ),
        'desc'       => __( 'Should the error code be shown with the error. Can be helpful for troubleshooting.', 'listings-wp' ),
        'id'         => 'contact_form_include_error',
        'type'       => 'select',
        'options' => array(
            'yes'    => __( 'Yes', 'listings-wp' ),
            'no'     => __( 'No', 'listings-wp' ),
        ),
        'default'    => 'yes',
    ));

    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;


/* ======================================================================================
                                        Advanced Options
   ====================================================================================== */
    // template html
    $cmb = new_cmb2_box( array(
        'id'        => 'template_html',
        'title'     => __( 'Template HTML', 'listings-wp' ),
        
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'          => __( 'Opening HTML Tag(s)', 'listings-wp' ),
        'desc'          => __( 'Used for theme compatability, this option will override the opening HTML for all Listings pages.', 'listings-wp' ) . '<br>' .
        __( 'This can help you to match the HTML with your current theme.', 'listings-wp' ),
        'id'            => 'opening_html',
        'type'          => 'textarea',
        'attributes' => array(
            'placeholder'   => '<div class=&quot;container&quot;><div class=&quot;main-content&quot;>',
            'rows'   => 2,
        ),
        'before_row'     => '<p class="cmb2-metabox-description">' . __( '', 'listings-wp' ) . '</p>',
    ));
    $cmb->add_field( array(
        'name'          => __( 'Closing HTML Tag(s)', 'listings-wp' ),
        'desc'          => __( 'Used for theme compatability, this option will override the closing HTML for all Listings pages.', 'listings-wp' ) . '<br>' .
        __( 'This can help you to match the HTML with your current theme.', 'listings-wp' ),
        'id'            => 'closing_html',
        'type'          => 'textarea',
        'attributes' => array(
            'placeholder'   => '</div></div>',
            'rows'   => 2,
        ),
    )); 

    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // uninstall
    $cmb = new_cmb2_box( array(
        'id'        => 'uninstall',
        'title'     => __( 'Uninstall', 'listings-wp' ),
        
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'          => __( 'Delete Data', 'listings-wp' ),
        'desc'          => __( 'Should all plugin data be deleted upon uninstalling this plugin?', 'listings-wp' ),
        'id'            => 'delete_data',
        'type'          => 'select',
        'default'       => 'yes',
        'options' => array(
            'yes'   => __( 'Yes', 'listings-wp' ),
            'no'    => __( 'No', 'listings-wp' ),
        ),
    ));


    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;



    // extensions
    $cmb = new_cmb2_box( array(
        'id'        => 'extensions',
        'title'     => __( 'Extensions', 'listings-wp' ),
        'show_on'   => $show_on,
    ));
    $cmb->add_field( array(
        'name'       => __( '', 'listings-wp' ),
        'after'      => '<p class="">' . __( 'There are a number of premium extensions available at <a href="http://listings-wp.com/documentation?utm_source=plugin&utm_medium=settings_page&utm_content=extensions" target="_blank">www.listings-wp.com</a> that will take your real estate website to the next level.', 'listings-wp' ) . '</p>',
        'id'         => 'intro',
        'type'       => 'title',
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;

    // box 3, in sidebar of our two-column layout
    $cmb = new_cmb2_box( array(
        'id'        => 'side_metabox',
        'title'     => __( 'Save Options', 'listings-wp' ),
        'show_on'   => $show_on,
        'context'    => 'side',
    ));
    $cmb->add_field( array(
        'name'       => __( 'Publish?', 'listings-wp' ),
        'desc'       => __( 'Save These Options', 'listings-wp' ),
        'id'         => 'my_save_button',
        'type'       => 'options_save_button',
        'show_names' => false,
    ));
    $cmb->object_type( 'options-page' );
    $boxes[] = $cmb;




    // Arguments array. See the arguments page for more detail
    $args = array(
        'key'        => $opt_key,
        'title'      => __( 'Listings WP', 'listings-wp' ),
        'topmenu'    => 'options-general.php',
        'boxes'      => $boxes,
        'tabs'       => $tabs,
        'cols'       => 2,
        'savetxt'    => '',
    );

    new Cmb2_Metatabs_Options( apply_filters( 'listings_wp_admin_options', $args, $cmb ) );
}