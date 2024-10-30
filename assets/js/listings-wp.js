/**
 */
(function($){

    /**
     * Archive page
     */
    if( $( 'body.listings-wp' ).hasClass( 'post-type-archive' ) ) {
        listings_wp_view_switcher();
        listings_wp_ordering();
        listings_wp_buy_sell();
    }

    /**
     * Single listing
     */
    if( $( 'body' ).hasClass( 'listings-wp' ) ) {
        listings_wp_google_map();
        listings_wp_slider();
    }


/**
 * ================================= FUNCTIONS =======================================
 */

    /**
     * Ordering
     */
    function listings_wp_ordering() {
        $( '.listings-wp-ordering' ).on( 'change', 'select.orderby', function() {
            $( this ).closest( 'form' ).submit();
        });
    }

    /**
     * Buy/Sell option
     */
    function listings_wp_buy_sell() {
        $( '.listings-wp-search-form' ).on( 'change', 'select.purpose', function() {
            $( this ).closest( 'form' ).submit();
        });
    }


    /**
     * View switcher
     */
    function listings_wp_view_switcher() {

        $( '.listings-wp-view-switcher div' ).click( function() {   
            var view = $( this ).attr( 'id' );
            set_cookie( view );  
            switch_view( view );
        });
     
        if( get_cookie( 'view' ) == 'grid') { switch_view( 'grid' ); }

        function switch_view( to ) {

            var from = ( to == 'list' ) ? 'grid' : 'list';

            var listings = $('.listings-wp-items li');
             $.each( listings, function( index, listing ) {
                $( '.listings-wp-items' ).removeClass( from + '-view' );
                $( '.listings-wp-items' ).addClass( to + '-view' );
            });
        }

        function set_cookie( value ) {
            var days = 30; // set cookie duration
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else var expires = "";
            document.cookie = "view="+value+expires+"; path=/";
        }

        function get_cookie( name ) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

    }

    /**
     * Slider
     */
    function listings_wp_slider() {

        if ( $("#image-gallery").length > 0) {

            $('#image-gallery').lightSlider({

                thumbItem: parseInt( listings_wp.thumbs_shown ),
                mode: listings_wp.gallery_mode,
                auto: listings_wp.auto_slide,
                pause: parseInt( listings_wp.slide_delay ),
                speed: parseInt( listings_wp.slide_duration ),
                prevHtml: listings_wp.gallery_prev,
                nextHtml: listings_wp.gallery_next,
                pager: true,
                controls: true,

                addClass: 'listing-gallery',
                gallery: true,
                item: 1,
                autoWidth: false,
                loop: true,
                slideMargin: 0,
                galleryMargin: 10,
                thumbMargin: 10,
                enableDrag: false,
                currentPagerPosition: 'left',

                onSliderLoad: function(el) {
                    el.lightGallery({
                        selector: '#image-gallery .lslide'
                    });
                }   
            }); 

        }

    }

    /**
     * Google map
     */
    function listings_wp_google_map() {

        var lat = listings_wp.lat;
        var lng = listings_wp.lng;
        
        var options = {
            center: new google.maps.LatLng( lat, lng ),
            zoom: parseInt( listings_wp.map_zoom ),
        }

        lwp_map = new google.maps.Map( document.getElementById( 'listings-wp-map' ), options );
        
        var position = new google.maps.LatLng( lat, lng );

        var set_marker = new google.maps.Marker({
            map: lwp_map,
            position: position
        });


    }

})(jQuery);
