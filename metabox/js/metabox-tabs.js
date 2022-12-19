jQuery(document).ready(ftg_admin_gallery_tabs);

function ftg_admin_gallery_tabs() {

     // enable link to tab
    jQuery('ul.nav-tabs').each(function () {
        // For each set of tabs, we want to keep track of
        // which tab is active and its associated content
        var $active, $content, $links = jQuery(this).find('a');

        // If the location.hash matches one of the links, use that as the active tab.
        // If no match is found, use the first link as the initial active tab.
        $active = jQuery($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
        $active.addClass('active');

        $content = jQuery($active[0].hash);

        // Hide the remaining content
        $links.not($active).each(function () {
            jQuery(this.hash).hide();
        });

        // Bind the click event handler
        jQuery(this).on('click', 'a', function (e) {
            // Make the old tab inactive.
            $active.removeClass('active');
            $content.hide();

            // Update the variables with the new link and content
            $active = jQuery(this);
            $content = jQuery(this.hash);

            // Make the tab active.
            $active.addClass('active');
            $content.show();

            // Prevent the anchor's default click action
            e.preventDefault();
        });
    });
}

function fts_ajax_cpt_save_token() {

    var newUrl = ftg_mb_tabs.submit_msgs.fts_post;
    window.location.replace( newUrl + '#fts-feed-type' );

    jQuery( '.post-type-fts .wrap form#post' ).ajaxSubmit({
        beforeSend: function () {
            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-saving-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "visible");

        },
        success: function ( response ) {
            console.log( 'Token Saved Successfully' );
            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-success-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "hidden");

            setTimeout("jQuery('.ftg-overlay-background').hide();", 400);

            location.reload();
            // We change the text from Updating... at the bottom of a long page to Update.
            jQuery('.updatefrombottom a.button-primary').html("Update");
        }
    });
    return false;
}

function fts_ajax_cpt_save( shortcodeConverted ) {

    jQuery( '.post-type-fts .wrap form#post' ).ajaxSubmit({
        beforeSend: function () {
            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-saving-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "visible");

        },
        success: function ( response ) {
            // Lets us know the options were saved ok.
            console.log( 'Saved Successfully' );
            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-success-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "hidden");

            setTimeout("jQuery('.ftg-overlay-background').hide();", 400);

            // Change the text from Updating... at the bottom of a long page to Update.
            jQuery('.updatefrombottom a.button-primary').html("Update");

            var hash2 = window.location.hash.replace('#', '');

            // #fts-feed-type: comes from the url populated by slickremix where we get the access token from.
            // #feed_setup: comes from clicking on the Feed Setup tab
            // if no hash then it's a new feed or possibly the #feed_setup hash was removed from the url.
            if ( hash2 === 'fts-convert-old-shortcode' ||
                 hash2 === 'fts-feed-type' ||
                 hash2 === 'feed_setup' ||
                 hash2 === '') {
                location.reload();
            }

        }
    });
    return false;
}

jQuery(document).ready(function ($) {

    jQuery('.ft-gallery-notice').on('click', '.ft-gallery-notice-close', function () {
        jQuery('.ft-gallery-notice').html('');
        jQuery('.ft-gallery-notice').removeClass('updated, ftg-block')
    });

    // Show the proper tab if this link type is clicked on any tab of ours
    jQuery('.tab-content-wrap').on('click', '.fts-facebook-successful-api-token', function (e) {
        jQuery('.tab5 a').click();
        var clickedLink = $('.tab5 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-instagram-successful-api-token, .fts-instagram-business-successful-api-token', function (e) {
        jQuery('.tab4 a').click();
        var clickedLink = $('.tab4 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-twitter-successful-api-token', function (e) {
        jQuery('.tab6 a').click();
        var clickedLink = $('.tab6 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-youtube-successful-api-token', function (e) {
        jQuery('.tab7 a').click();
        var clickedLink = $('.tab7 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-combine-successful-api-token', function (e) {
        jQuery('.tab8 a').click();
        var clickedLink = $('.tab8 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    var hash = window.location.hash.replace('#', '');
    if (hash) {
        document.getElementById(hash).style.display = 'block'
    }

    // This runs when you click on the WP update button
    jQuery('.post-type-fts .wrap form#post').submit( function (e) {
        e.preventDefault();
        fts_ajax_cpt_save();
       //  alert('yes');
    });


    // click event listener
    $('.ft-gallery-settings-tabs-meta-wrap ul.nav-tabs a').click(function (event) {
        // get the id
        var clickedLink = $(this).attr('href');
        // push it into the url
        location.hash = clickedLink;
    });


    //create hash tag in url for tabs
    jQuery('.ft-gallery-settings-tabs-meta-wrap #tabs').on('click', "label.tabbed", function () {
        var myURL = document.location;
        document.location = myURL + "&tab=" + jQuery(this).attr('id');

    });

    // Super Gallery option
    jQuery('#facebook-custom-gallery').bind('change', function (e) {
        if (jQuery('#facebook-custom-gallery').val() == 'yes') {
            jQuery('.fts-super-facebook-options-wrap').show();
        }
        else {
            jQuery('.fts-super-facebook-options-wrap').hide();
        }
    });

    if (jQuery('#fts_popup').val() == 'no') {
        jQuery('.ft-images-sizes-popup').hide();
        // jQuery('.display-comments-wrap').show();

    }
    // Display Popup option
    jQuery('#fts_popup').bind('change', function (e) {
        if (jQuery('#fts_popup').val() == 'yes') {
            jQuery('.ft-images-sizes-popup').show();
            // jQuery('.display-comments-wrap').show();

        }
        else {
            jQuery('.ft-images-sizes-popup').hide();
            //  jQuery('.display-comments-wrap').hide();
        }
    });

    // show load more options
    jQuery('#fts_load_more_option').bind('change', function (e) {
        if (jQuery('#fts_load_more_option').val() == 'yes') {

            if (jQuery('#facebook_page_feed_type').val() !== 'album_videos') {
                jQuery('.fts-facebook-load-more-options-wrap').show();
            }
            jQuery('.fts-facebook-load-more-options2-wrap').show();

            jQuery('#fts_show_true_pagination').attr('disabled', 'disabled');
            jQuery('.ftg-pagination-notice-colored').show();
        }

        else {
            jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
            jQuery('#fts_show_true_pagination').removeAttr('disabled');
            jQuery('.ftg-pagination-notice-colored').hide();
        }
    });

    if (jQuery('#fts_load_more_option').val() == 'yes') {
        jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').show();
        jQuery('.fts-facebook-grid-options-wrap').show();
        jQuery('#fts_show_true_pagination').attr('disabled', 'disabled');
        jQuery('.ftg-pagination-notice-colored').show();

    }
    if (jQuery('#fts_grid_option').val() == 'yes') {
        jQuery('.fts-facebook-grid-options-wrap').show();
        jQuery(".feed-them-social-admin-input-label:contains('Center Facebook Container?')").parent('div').show();
    }


});


// Grab the url so we can do stuff.
var url_string = window.location.href;
var url = new URL( url_string );
var cpt_id = url.searchParams.get("post");
var feed_type = url.searchParams.get("feed_type");

jQuery(document).ready(function ($) {

    function fts_select_social_network_menu() {

        $('.ft-wp-gallery-type').append('<div class="fts-select-social-network-menu">' +
            '<div class="fts-social-icon-wrap instagram-feed-type" data-fts-feed-type="instagram-feed-type"><img src="/wp-content/plugins/feed-them-social/metabox/images/instagram-logo-admin.png" class="instagram-feed-type-image" /><span class="fts-instagram"></span><div>Instagram</div></div>' +
            '<div class="fts-social-icon-wrap facebook-feed-type" data-fts-feed-type="facebook-feed-type"><span class="fts-facebook"></span><div>Facebook</div></div>' +
            '<div class="fts-social-icon-wrap twitter-feed-type" data-fts-feed-type="twitter-feed-type"><span class="fts-twitter"></span><div>Twitter</div></div>' +
            '<div class="fts-social-icon-wrap youtube-feed-type" data-fts-feed-type="youtube-feed-type"><span class="fts-youtube"></span><div>YouTube</div></div>' +
            '<div class="fts-social-icon-wrap combine-streams-feed-type" data-fts-feed-type="combine-streams-feed-type"><span class="fts-combined"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 96C0 78.33 14.33 64 32 64H144.6C164.1 64 182.4 72.84 194.6 88.02L303.4 224H384V176C384 166.3 389.8 157.5 398.8 153.8C407.8 150.1 418.1 152.2 424.1 159L504.1 239C514.3 248.4 514.3 263.6 504.1 272.1L424.1 352.1C418.1 359.8 407.8 361.9 398.8 358.2C389.8 354.5 384 345.7 384 336V288H303.4L194.6 423.1C182.5 439.2 164.1 448 144.6 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H144.6L247 256L144.6 128H32C14.33 128 0 113.7 0 96V96z"/></svg></span><div>Combined</div></div>' +
            '</div>')
    }
    fts_select_social_network_menu();

});

function fts_encrypt_token_ajax( access_token, token_type , id, firstRequest ) {

    console.log( 'access_token: ' + access_token );
    console.log( 'token_type: ' + token_type );
    console.log( 'id: ' + id );

    jQuery.ajax({
        data: {
            action: 'fts_encrypt_token_ajax',
            cpt_id: cpt_id,
            access_token: access_token,
            token_type: token_type,
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            var data = JSON.parse( response );
            // Add the OG token to the input value and add the encrypted token to the data-attribute.
            if( 'firstRequest' === firstRequest) {

                jQuery( id ).val('');
                jQuery( id ).val( jQuery( id ).val() + data.encrypted );
                jQuery( id ).attr('data-token', 'encrypted').attr( 'value', data.encrypted ) ;

                console.log('first request ' + id);

                // Now that we've successfully saved the encrypted token to the db we save all the options again.
                fts_ajax_cpt_save_token();

            }
            console.log( id + ': OG Token and Encrypted Response........: ' + response );
        },
        error: function ( response ) {
            console.log( 'Something is not working with encyption: ' + response );
        }

    }); // end of ajax()
    return false;
}

function fts_show_decrypt_token_text(){

    if( '' !== jQuery( '.fts-instagram-access-token  .fts-decrypted-token' ).parent().parent().find('input').attr('value') ){
        jQuery( '.fts-instagram-access-token .fts-decrypted-token' ).show();
    }

    if( '' !== jQuery( '.fts-facebook-instagram-access-token  .fts-decrypted-token' ).parent().parent().find('input').attr('value') ){
        jQuery( '.fts-facebook-instagram-access-token .fts-decrypted-token' ).show();
    }

    if( '' !== jQuery( '.fts-facebook-access-token  .fts-decrypted-token' ).parent().parent().find('input').attr('value') ){
        jQuery( '.fts-facebook-access-token .fts-decrypted-token' ).show();
    }

    // Decrypt Token Click Action.
    jQuery('.fts-decrypted-token').click(function () {

        if( jQuery( this ).hasClass( 'fts-remove-decrypted-token') ){

            jQuery( this ).parent().parent().find('.fts-decrypted-view').remove();
            jQuery( '.fts-show-token', this ).show();
            jQuery( '.fts-hide-token', this ).hide();
            jQuery( this ).addClass('fts-copy-decrypted-token').removeClass('fts-remove-decrypted-token');
        }
        else {
            var encrypted_token = jQuery(this).parent().parent().find('input').attr('value');
            var id              = jQuery(this).parent().parent().find('input').attr('id');
            // Decrypt the token for debugging.
            fts_decrypt_token_ajax( encrypted_token, id );
        }
    });
}

function fts_decrypt_token_ajax( encrypted_token, id ) {

    console.log( 'access_token: ' + encrypted_token );
    console.log( 'id: ' + id );

    jQuery.ajax({
        data: {
            action: 'fts_decrypt_token_ajax',
            encrypted_token: encrypted_token,
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            jQuery( '#' + id ).parent().find('.fts-decrypted-view').remove();
            jQuery( '#' + id ).parent().find('.fts-show-token').hide();
            jQuery( '#' + id ).parent().find('.fts-hide-token').show();
            jQuery( '#' + id ).parent().find( '.fts-decrypted-token' ).removeClass('fts-copy-decrypted-token').addClass( 'fts-remove-decrypted-token');
            jQuery( '#' + id ).parent().append('<div class="fts-decrypted-view">' + response + '</div>');
            console.log( id + ': OG Token and Decrypted Response........: ' + response );

        },
        error: function ( response ) {
            console.log( 'Something is not working with decryption: ' + response );
        }

    }); // end of ajax()
    return false;
}