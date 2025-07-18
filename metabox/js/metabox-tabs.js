// Grab the url so we can do stuff.
const url_string = window.location.href;
let url = new URL( url_string );
let cpt_id = url.searchParams.get("post");
let feed_type = url.searchParams.get("feed_type");

jQuery(document).ready(ftg_admin_gallery_tabs);

function ftg_admin_gallery_tabs() {

    jQuery('.post-type-fts.post-php').find('.page-title-action').after('<a href="javascript:void(0);" class="page-title-action fts-enter-full-screen-editing-more">Full Screen Editing</a><a href="#feed_setup" class="page-title-action fts-setup-instructions-button fts-info-icon">Setup Instructions</a>');

    // Check if the #collapse-button is already collapsed
    if (jQuery('#collapse-button').attr('aria-expanded') === 'false') {
        // Add a class to indicate it's collapsed
        jQuery('#collapse-button').addClass('already-collapsed');
    }

    jQuery('.fts-enter-full-screen-editing-more').click(function () {
        jQuery('body').toggleClass('fts-full-screen-editing-wrap-active');
        jQuery(this).toggleClass('active');

        if (jQuery(this).hasClass('active')) {
            jQuery(this).text('Exit Full Screen Editing Mode');
        } else {
            jQuery(this).text('Full Screen Editing');
        }

        const should_we_empty_cache = 'do-not-empty-cache';
        refresh_feed_ajax(should_we_empty_cache);

        // Only toggle the #collapse-button if it doesn't have the 'already-collapsed' class
        if (!jQuery('#collapse-button').hasClass('already-collapsed')) {
            jQuery('#collapse-button').click();
        }
    });

    jQuery('.post-type-fts #collapse-menu').click(function () {
        const should_we_empty_cache = 'do-not-empty-cache';
        refresh_feed_ajax(should_we_empty_cache);
    });

    jQuery('ul.nav-tabs').each(function () {
        // For each set of tabs, we want to keep track of
        // which tab is active and its associated content
        let $active, $content, $links = jQuery(this).find('a');

        // If the location.hash matches the data-key of one of the links, use that as the active tab.
        // If no match is found, use the first link as the initial active tab.
        const hashKey = location.hash.substring(1); // Remove the '#' from the hash
        $active = jQuery($links.filter('[data-key="' + hashKey + '"]')[0] || $links[0]);
        $active.addClass('active');

        $content = jQuery('#' + $active.data('key')); // Use the data-key attribute to find the content

        // Hide the remaining content
        $links.not($active).each(function () {
            jQuery('#' + jQuery(this).data('key')).hide();
        });

        // Bind the click event handler
        jQuery(this).on('click', 'a', function (e) {
            // Prevent the anchor's default click action
            e.preventDefault();

            // Make the old tab inactive.
            $active.removeClass('active');
            $content.hide();

            // Update the variables with the new link and content
            $active = jQuery(this);
            $content = jQuery('#' + $active.data('key'));

            // Make the tab active.
            $active.addClass('active');
            $content.show();
        });
    });
}

function fts_ajax_cpt_save_token() {

    const newUrl = ftg_mb_tabs.submit_msgs.fts_post;
    window.location.replace( newUrl );
    // Testing removing this id .fts-token-wrap because it just makes the process so damn jumpy
    // window.location.replace( newUrl + '.fts-token-wrap' );


    jQuery( '.post-type-fts .wrap form#post' ).ajaxSubmit({
        beforeSend: function () {

            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-saving-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "visible");

            // Note: The success message below does not run for the instagram basic, twitter or youtube feed.
            // It only runs when the save token for the facebook business feed is clicked.
            // Need to debug. Probably because there is no ( response )
        },
        success: function ( response ) {
            console.log( 'Token Saved Successfully' );
            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-success-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "hidden");

            setTimeout("jQuery('.ftg-overlay-background').hide();", 400);
            //  alert('test3');
            //  location.reload();
            // We change the text from Updating... at the bottom of a long page to Update.
            jQuery('.updatefrombottom a.button-primary').html("Update");
        }
    });
    return false;
}

function fts_ajax_cpt_save( shortcodeConverted, should_we_empty_cache ) {

    jQuery( '.post-type-fts .wrap form#post' ).ajaxSubmit({
        beforeSend: function () {
            if( 'no-save-message' !== shortcodeConverted ) {
                jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-saving-form'></div></div></div>");
                jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
                jQuery('#publishing-action .spinner').css("visibility", "visible");
            }
            else if( jQuery('.fts-cache-pre-loading').length > 0 && !jQuery('.fts-empty-access-token').length > 0 ) {

                // This adds a loading message under the feed so the user knows what is going on.
                // First hide the message in case the user is making changes to options quickly. This way multiple messages do not appear.
                jQuery( '.fts-loading-feed-admin' ).hide();
                // Now append the loading message.
                jQuery( '.fts-shortcode-content' ).append( '<div class="fts-loading-feed-admin fts-cache-loading">' + ftg_mb_tabs.submit_msgs.fts_loading_message + '</div>' );

            }

        },
        success: function ( response ) {
            // Lets us know the options were saved ok.
            console.log( 'Saved Successfully' );

            if( 'no-save-message' !== shortcodeConverted ) {

                jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-success-form'></div></div></div>");
                jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
                jQuery('#publishing-action .spinner').css("visibility", "hidden");

                setTimeout("jQuery('.ftg-overlay-background').hide();", 400);
            }

            // Change the text from Updating... at the bottom of a long page to Update.
            jQuery('.updatefrombottom a.button-primary').html("Update");

            // Remove any hashtag data from the url
            let hash2 = window.location.hash.replace('#', '');

            // #fts-feed-type: comes from the url populated by slickremix where we get the access token from.
            // #feed_setup: comes from clicking on the Feed Setup tab
            // if no hash then it's a new feed or possibly the #feed_setup hash was removed from the url.
            if ( hash2 === 'fts-convert-old-shortcode' ||
                hash2 === 'fts-feed-type' ||
                hash2 === 'feed_setup' ||
                hash2 === '') {
                jQuery(window).off('beforeunload');
                location.reload();
            }
            else {
                // Refresh the Preview Feed.
                refresh_feed_ajax(should_we_empty_cache);
            }
        }
    });
    return false;
}


function refresh_feed_ajax(should_we_empty_cache) {

    if( 'empty-cache' === should_we_empty_cache){
        fts_ClearCache();
    }

    jQuery.ajax({
        data: {
            action: 'ftsRefreshFeedAjax',
            cpt_id: cpt_id,
            _wpnonce: ftg_mb_tabs.ajaxRefreshFeedNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            jQuery( '.fts-loading-feed-admin' ).fadeOut();

            // This happens if the shortcode returns an error
            // 'Feed Them Social:' is text I added to the start of the error messages now in all feeds.
            // This way we can also detect it on the back end.
            if( response.indexOf('Feed Them Social:') > -1 ){
                jQuery( '.fts-shortcode-content' ).html( response );
                jQuery( '.fts-loading-feed-admin' ).remove();
            }
            else {
                //IF no error detected then we return the shortcode contents.
                jQuery( '.fts-shortcode-content' ).removeClass( 'fts-shortcode-content-no-feed' );
                setTimeout(function () {

                    jQuery( '.fts-shortcode-content' ).html( response );

                    jQuery( '.fts-loading-feed-admin' ).remove();

                    fts_show_hide_shortcode_feed();

                    if (jQuery.fn.masonry) {

                        if( 'yes' === jQuery('#facebook_grid').val() ){

                            jQuery(".fts-slicker-facebook-posts").masonry({
                                itemSelector: ".fts-jal-single-fb-post"
                            });
                            jQuery(".masonry").masonry("reloadItems");
                            jQuery(".masonry").masonry("layout");
                        }

                        if( 'yes' === jQuery('#tiktok-grid-option').val() ){

                            jQuery(".fts-slicker-twitter-posts").masonry({
                                itemSelector: ".fts-tweeter-wrap"
                            });

                            jQuery(".masonry").masonry("reloadItems");
                            jQuery(".masonry").masonry("layout");
                        }

                        if( 'yes' === jQuery('#combine_grid_option').val() ){

                            jQuery(".fts-mashup").masonry({
                                itemSelector: ".fts-mashup-post-wrap"
                            });

                            jQuery(".masonry").masonry("reloadItems");
                            jQuery(".masonry").masonry("layout");
                        }

                    }

                    // If the Carousel plugin is installed we recall it otherwise carousel/slideshow
                    // it will not display properly when live editing options.
                    if( jQuery.isFunction( jQuery.fn.ftsCycle2 ) ){
                        jQuery( '.fts-fb-slideshow' ).cycle();
                    }

                    // If the Instagram Slideshow plugin is installed we recall it otherwise
                    // it will not display properly when live editing options.
                    if (typeof ftsi_slider === 'function') {
                        ftsi_slider();
                    }

                }, 500);

            }
            console.log( 'Feed Refreshed: ' + response );
        },
        error: function ( response ) {
            console.log( 'Something is not working w/feed refresh: ' + response );
        }

    }); // end of ajax()
    return false;
}

function fts_show_hide_shortcode_feed( feed ) {

    if( jQuery( '.account-tab-highlight.active' ).length ){
        jQuery( '.fts-shortcode-view' ).css('display', 'none');
        jQuery('.tab-options-content').removeAttr('style');
    }
    else {
        jQuery( '.fts-shortcode-view' ).css('display', 'flex');
        jQuery( '.tab-options-content' ).css('flex', '1');
    }

    ftsShare();
    slickremixImageResizing();
    slickremixImageResizingFacebook();
    slickremixImageResizingFacebook2();
    slickremixImageResizingFacebook3();
    slickremixImageResizingYouTube();

    if( jQuery.isFunction(jQuery.fn.slickInstagramPopUpFunction) ){
        jQuery.fn.slickInstagramPopUpFunction();
    }
    if( jQuery.isFunction(jQuery.fn.slickFacebookPopUpFunction) ){
        jQuery.fn.slickFacebookPopUpFunction();
    }
    if( jQuery.isFunction(jQuery.fn.slickTickTokPopUpFunction) ){
        jQuery.fn.slickTickTokPopUpFunction();
    }
    if( jQuery.isFunction(jQuery.fn.slickYoutubePopUpFunction) ){
        jQuery.fn.slickYoutubePopUpFunction();
    }
}

function checkAnyFormFieldEdited() {

    jQuery('#instagram_feed select, #instagram_feed input, ' +
        '#facebook_feed select, #facebook_feed input, ' +
        '#tiktok_feed select, #tiktok_feed input, ' +
        '#youtube_feed select, #youtube_feed input, ' +
        '#combine_streams_feed input').change(function(e) {

        // SRL Updated: 1-14-24 Check if its a post change or name change. Only then do we need to empty the cache
        // because we need to re-fetch data from an API otherwise we do not need to empty the cache
        // so we send a do-not-empty-cache message to the function.
        let should_we_empty_cache = '';
        if (jQuery(e.target).is('#tweets_count') ||
            jQuery(e.target).is('#instagram_pics_count') ||
            jQuery(e.target).is('#instagram_feed_type') ||
            jQuery(e.target).is('#instagram_profile_wrap') ||
            jQuery(e.target).is('#facebook_page_feed_type') ||
            jQuery(e.target).is('#facebook_page_post_count') ||
            jQuery(e.target).is('#facebook_page_posts_displayed') ||
            jQuery(e.target).is('#facebook_hide_like_box_button') ||
            jQuery(e.target).is('#youtube-messages-selector') ||
            jQuery(e.target).is('#youtube_channelID') ||
            jQuery(e.target).is('#youtube_channelID2') ||
            jQuery(e.target).is('#youtube_vid_count') ||
            jQuery(e.target).is('#youtube_playlistID') ||
            jQuery(e.target).is('#youtube_playlistID2') ||
            jQuery(e.target).is('#youtube_singleVideoID') ||
            jQuery(e.target).is('#youtube_name') ||
            jQuery(e.target).is('#youtube_name2') ||
            jQuery(e.target).is('#combine_post_count')||
            jQuery(e.target).is('#combine_social_network_post_count') ) {

            should_we_empty_cache = 'empty-cache';
        }
        else {
            should_we_empty_cache = 'do-not-empty-cache';
        }

        // Select or input element changed. Those are the only 2 items so far we are using in the options.
        fts_ajax_cpt_save( 'no-save-message', should_we_empty_cache );
    });
}

jQuery(document).ready(function ($) {

    $('.fts-responsive-options').each(function() {
    $(this).append('<div class="fts-clear"></div>\n' +
        '                    <div class="fts-responsive-options-wrap fts-responsive-click">\n' +
        '                        <span class="fts-responsive-desktop fts-responsive-tab-active" data-target=".responsive-columns-desktop-wrap"></span><span class="fts-responsive-tablet" data-target=".responsive-columns-tablet-wrap"></span><span class="fts-responsive-mobile" data-target=".responsive-columns-mobile-wrap"></span>\n' +
        '                    </div>');
    });

    // This is for the responsive options tabs
    $('.fts-responsive-options-wrap span').click(function() {
        let parentWrap = $(this).closest('.fts-responsive-wrap');
        const target = $(this).data('target');

        // Remove active class from all spans within the same .fts-responsive-options-wrap
        parentWrap.find('.fts-responsive-options-wrap span').removeClass('fts-responsive-tab-active');
        // Add active class to the clicked span
        $(this).addClass('fts-responsive-tab-active');

        // Hide all target elements within the same .fts-responsive-wrap
        parentWrap.find('.responsive-columns-desktop-wrap, .responsive-columns-tablet-wrap, .responsive-columns-mobile-wrap, .responsive-columns-desktop-wrap-2, .responsive-columns-tablet-wrap-2, .responsive-columns-mobile-wrap-2').hide();
        // Show the target element
        parentWrap.find(target).show();
    });

    fts_show_hide_shortcode_feed();
    checkAnyFormFieldEdited();

    // Do this so if the users moves quickly so only loading messages displays.
    jQuery('.fts-shortcode-content').addClass('fts-cache-pre-loading');

    $('.fts-info-icon').click(function () {// get the id
        $(".tab1 a").click();
        $('.fts-select-social-network-menu-instructions').slideToggle();
    });

    jQuery('.ft-gallery-notice').on('click', '.ft-gallery-notice-close', function () {
        jQuery('.ft-gallery-notice').html('');
        jQuery('.ft-gallery-notice').removeClass('updated, ftg-block')
    });

    // Show the proper tab if this link type is clicked on any tab of ours
    jQuery('.tab-content-wrap').on('click', '.fts-facebook-successful-api-token', function (e) {
        jQuery('.tab5 a').click();
        const clickedLink = $('.tab5 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed( 'facebook');
        // Prevent the anchor's default click action
        e.preventDefault();

    });

    jQuery('.tab-content-wrap').on('click', '.fts-instagram-successful-api-token', function (e) {
        jQuery('.tab4 a').click();
        const clickedLink = $('.tab4 a').attr('href');
        jQuery('#instagram_feed_type').bind('change', function (e) {
            jQuery(this).val('basic');
        }).change();
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed('instagram');
        // Prevent the anchor's default click action
        e.preventDefault();
    });


    jQuery('.tab-content-wrap').on('click', '.fts-instagram-business-successful-api-token', function (e) {
        jQuery('.tab4 a').click();
        const clickedLink = $('.tab4 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed('instagram');
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-twitter-successful-api-token', function (e) {
        jQuery('.tab6 a').click();
        const clickedLink = $('.tab6 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed('twitter');
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-youtube-successful-api-token', function (e) {
        jQuery('.tab7 a').click();
        const clickedLink = $('.tab7 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed('youtube');
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.fts-combine-successful-api-token', function (e) {
        jQuery('.tab8 a').click();
        const clickedLink = $('.tab8 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        fts_show_hide_shortcode_feed('combined');
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    let hash = window.location.hash.replace('#', '');
    if (hash) {
        document.getElementById(hash).style.display = 'block'
    }

    // This runs when you click on the WP update button
    jQuery('.post-type-fts .wrap form#post').submit( function (e) {
        e.preventDefault();
        fts_ajax_cpt_save();
    });

    if( location.hash === '#instagram_feed' ||
        location.hash === '#facebook_feed' ||
        location.hash === '#tiktok_feed' ||
        location.hash === '#youtube_feed' ||
        location.hash === '#combine_streams_feed' ){
        // This happens if the user refreshes the page with one of the hash tabs noted above with the hash.
        // This way the options and feed are side by side.
        jQuery( '.fts-shortcode-view' ).css('display', 'flex');
        jQuery( '.tab-options-content' ).css('flex', '1');
    }

    // click event listener
    $('.ft-gallery-settings-tabs-meta-wrap ul.nav-tabs a').click(function (e) {

        // Prevent the anchor's default click action
        e.preventDefault();

        // get the id
        let clickedLink = $(this).data('key');

        if( 'feed_setup' === clickedLink ){
            jQuery( '.fts-shortcode-view' ).css('display', 'none');
            jQuery('.tab-options-content').removeAttr('style');
        }
        else {
            jQuery( '.fts-shortcode-view' ).css('display', 'flex');
            jQuery( '.tab-options-content' ).css('flex', '1');
            fts_ajax_cpt_save( 'no-save-message' );
        }
        ftsShare();
        slickremixImageResizing();
        slickremixImageResizingFacebook();
        slickremixImageResizingFacebook2();
        slickremixImageResizingFacebook3();
        slickremixImageResizingYouTube();

        if( jQuery.isFunction(jQuery.fn.slickInstagramPopUpFunction) ){
            jQuery.fn.slickInstagramPopUpFunction();
        }
        if( jQuery.isFunction(jQuery.fn.slickFacebookPopUpFunction) ){
            jQuery.fn.slickFacebookPopUpFunction();
        }
        if( jQuery.isFunction(jQuery.fn.slickTickTokPopUpFunction) ){
            jQuery.fn.slickTickTokPopUpFunction();
        }
        if( jQuery.isFunction(jQuery.fn.slickYoutubePopUpFunction) ){
            jQuery.fn.slickYoutubePopUpFunction();
        }

        // Store the current scroll position
        const scrollPos = $(window).scrollTop();

        // Push the hash to the URL without causing the scroll
        location.hash = clickedLink;

        // Restore the scroll position
        $(window).scrollTop(scrollPos);
    });


    //create hash tag in url for tabs
    jQuery('.ft-gallery-settings-tabs-meta-wrap #tabs').on('click', "label.tabbed", function (e) {

        // Prevent the anchor's default click action
        e.preventDefault();

        const myURL = document.location;
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

function ftsEncryptTokenAjax( access_token, token_type , id, firstRequest ) {

    console.log( 'access_token: ' + JSON.stringify(access_token) );
    console.log( 'token_type: ' + token_type );
    console.log( 'id: ' + id );


    jQuery.ajax({
        data: {
            action: 'ftsEncryptTokenAjax',
            cpt_id: cpt_id,
            access_token: JSON.stringify( access_token ),
            token_type: token_type,
            _wpnonce: ftg_mb_tabs.ajaxEncryptNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            console.log( response );

            let data = JSON.parse( response );

            console.log( data );

            // Add the OG token to the input value and add the encrypted token to the data-attribute.
            if( 'firstRequest' === firstRequest) {

                jQuery( id ).val('');
                jQuery( id ).val( jQuery( id ).val() + data.encrypted );
                jQuery( id ).attr('data-token', 'encrypted').attr( 'value', data.encrypted ) ;

                console.log('first request ' + id);

                // Now that we've successfully saved the encrypted token to the db \
                // we are going to try and refresh the page.
                if( 'instagram_business' === data.feed_type ||
                    'facebook_business' === data.feed_type ){

                    location.reload();
                }
                if( 'instagram_basic' === data.feed_type ||
                    'tiktok' === data.feed_type ||
                    'youtube' === data.feed_type ){

                    // alert( data.feed_type );
                    const newUrl = ftg_mb_tabs.submit_msgs.fts_post;
                    window.location.replace( newUrl );
                }
            }

            console.log( id + ': OG Token and Encrypted Response........: ' + response );
        },
        error: function ( response ) {
            console.log( 'Error with encryption process: ' + response );
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
            const encrypted_token = jQuery(this).parent().parent().find('input').attr('value');
            const id              = jQuery(this).parent().parent().find('input').attr('id');
            // Decrypt the token for debugging.
            ftsDecryptTokenAjax( encrypted_token, id );
        }
    });
}

function ftsDecryptTokenAjax( encrypted_token, id ) {

    console.log( 'access_token: ' + encrypted_token );
    console.log( 'id: ' + id );

    jQuery.ajax({
        data: {
            action: 'ftsDecryptTokenAjax',
            encrypted_token: encrypted_token,
            _wpnonce: ftg_mb_tabs.ajaxDecryptNonce
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