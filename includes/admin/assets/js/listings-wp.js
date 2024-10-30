(function($){

    $( document ).ready(function() {
        $("#lwp-geocomplete").trigger( "geocode" );
    });
    
    var lat = $("input[name=_lwp_listing_lat]").val();
    var lng = $("input[name=_lwp_listing_lng]").val();

    var location = [lat,lng];
    $("#lwp-geocomplete").geocomplete({
        map: ".lwp-admin-map",
        details: "#post", // form id
        detailsAttribute: "data-geo",
        types: ["geocode", "establishment"],
        location: location,
        markerOptions: {
            draggable: true
        }
    });

    $("#lwp-geocomplete").bind("geocode:dragged", function(event, latLng){
        $("input[name=_lwp_listing_lat]").val(latLng.lat());
        $("input[name=_lwp_listing_lng]").val(latLng.lng());
    });

    $("#lwp-find").click(function(){
        $("#lwp-geocomplete").trigger("geocode");
    });
    
    $("#lwp-reset").click(function(){
        $("#lwp-geocomplete").geocomplete("resetMarker");
        return false;
    });
    
    /*
     * Adapted from: http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
     * Further modified from PippinsPlugins https://gist.github.com/pippinsplugins/29bebb740e09e395dc06
     */
    jQuery(document).ready(function($) {
        // Uploading files
        var file_frame;

        jQuery('.listings_wp_wpmu_button').on('click', function(event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery(this).data('uploader_title'),
                button: {
                    text: jQuery(this).data('uploader_button_text'),
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                // Do something with attachment.id and/or attachment.url here
                // write the selected image url to the value of the #listings_wp_meta text field
                jQuery('#listings_wp_meta').val('');
                jQuery('#listings_wp_upload_meta').val(attachment.url);
                jQuery('#listings_wp_upload_edit_meta').val('/wp-admin/post.php?post=' + attachment.id + '&action=edit&image-editor');
                jQuery('.listings-wp-current-img').attr('src', attachment.url).removeClass('placeholder');
            });

            // Finally, open the modal
            file_frame.open();
        });

        // Toggle Image Type
        jQuery('input[name=img_option]').on('click', function(event) {
            var imgOption = jQuery(this).val();

            if (imgOption == 'external') {
                jQuery('#listings_wp_upload').hide();
                jQuery('#listings_wp_external').show();
            } else if (imgOption == 'upload') {
                jQuery('#listings_wp_external').hide();
                jQuery('#listings_wp_upload').show();
            }

        });

        if ('' !== jQuery('#listings_wp_meta').val()) {
            jQuery('#external_option').attr('checked', 'checked');
            jQuery('#listings_wp_external').show();
            jQuery('#listings_wp_upload').hide();
        } else {
            jQuery('#upload_option').attr('checked', 'checked');
        }

        // Update hidden field meta when external option url is entered
        jQuery('#listings_wp_meta').blur(function(event) {
            if ('' !== $(this).val()) {
                jQuery('#listings_wp_upload_meta').val('');
                jQuery('.listings-wp-current-img').attr('src', $(this).val()).removeClass('placeholder');
            }
        });

        // Remove Image Function
        jQuery('.edit_options').hover(function() {
            jQuery(this).stop(true, true).animate({
                opacity: 1
            }, 100);
        }, function() {
            jQuery(this).stop(true, true).animate({
                opacity: 0
            }, 100);
        });

        jQuery('.remove_img').on('click', function(event) {
            var placeholder = jQuery('#listings_wp_placeholder_meta').val();

            jQuery(this).parent().fadeOut('fast', function() {
                jQuery(this).remove();
                jQuery('.listings-wp-current-img').addClass('placeholder').attr('src', placeholder);
            });
            jQuery('#listings_wp_upload_meta, #listings_wp_upload_edit_meta, #listings_wp_meta').val('');
        });

    });


})(jQuery);