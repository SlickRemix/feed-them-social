// Made this into a function because there are 2 states.
// 1: When the page loads and instagram is the saved option.
// 2: When an item in the .fts-select-social-network-menu has been clicked on.
// So we check to make sure the instagram option has an active state and if so do stuff.
function fts_instagram_basic_business_buttons() {

    if( 'combine-streams-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.combine-instagram-access-token-placeholder').prepend('<div class="fts-instagram-basic-business-wrap">' +
            '<div class="fts-combine-instagram-basic-token-button" data-fts-feed-type="instagram-feed-type">Instagram Basic<br/><small>Your Personal Account</small><div class="fts-instagram-down-arrow fts-instagram-basic-down-arrow"></div></div>' +
            '<div class="fts-combine-instagram-business-token-button" data-fts-feed-type="instagram-business-feed-type">Instagram Business<br/><small>Your Account on Facebook</small><div class="fts-instagram-down-arrow fts-instagram-business-arrow"></div></div>' +
            '</div><div class="fts-clear"></div>');
    }
    else {
        jQuery('.fts-select-social-network-menu').append('<div class="fts-instagram-basic-business-wrap">' +
            '<div id="fts-instagram-basic-token-button" class="fts-instagram-basic-token-button" data-fts-feed-type="instagram-feed-type">Instagram Basic<br/><small>Your Personal Account</small><div class="fts-instagram-down-arrow fts-instagram-basic-down-arrow"></div></div>' +
            '<div id="fts-instagram-business-token-button"  class="fts-instagram-business-token-button" data-fts-feed-type="instagram-business-feed-type">Instagram Business<br/><small>Your Account on Facebook</small><div class="fts-instagram-down-arrow fts-instagram-business-arrow"></div></div>' +
            '</div><div class="fts-clear"></div>');
    }

    if( 'instagram-business-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.fts-instagram-business-token-button').addClass('fts-social-icon-wrap-active');
    }
    else if( 'instagram-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.fts-instagram-basic-token-button').addClass('fts-social-icon-wrap-active');
    }

    if( 'business' === jQuery("#combine_instagram_type option:selected").val() ){
        jQuery('.fts-combine-instagram-business-token-button').addClass('fts-social-icon-wrap-active');

        // Only display the hashtag select yes/no if the token is valid.
        if( jQuery('.fts-instagram-business-combine-successful-api-token.fts-success-token-content.fts-combine-successful-api-token').length > 0 ) {
            jQuery('.combine_instagram_hashtag_select').show();
        }

    }
    else if( 'basic' === jQuery("#combine_instagram_type option:selected").val()){
        jQuery('.fts-combine-instagram-basic-token-button').addClass('fts-social-icon-wrap-active');
        jQuery('.combine_instagram_hashtag_select').hide();
    }

}

// Use our buttons to change our #feed_type select option.
function fts_social_icons_wrap_click() {

    jQuery('.fts-social-icon-wrap, ' +
        '.fts-instagram-basic-token-button, ' +
        '.fts-instagram-business-token-button' ).click(function () {

        if( jQuery('#ftg-tab-content1').hasClass( 'pane-active' ) ) {
            // Don't repeat the process if the user clicks an active icon.
            if (!jQuery(this).hasClass('fts-social-icon-wrap-active')) {

                const url_string = window.location.href;
                const url = new URL(url_string);
                const cpt_id = url.searchParams.get("post");
                const fts_type = jQuery(this).data('fts-feed-type');

                jQuery('#feed_type').val( fts_type ).trigger('change').attr('selected', 'selected');

                jQuery('.fts-social-icon-wrap').removeClass('fts-social-icon-wrap-active');

                // Special case because instagram basic and business tokens are under one tab. So we make sure
                // the instagram token tab stays active while clicking the business or basic menu options.
                if( 'instagram-feed-type' === fts_type ||
                    'instagram-business-feed-type' === fts_type ){
                    jQuery('.instagram-feed-type').addClass('fts-social-icon-wrap-active');
                }
                jQuery(this).addClass('fts-social-icon-wrap-active');

                // Load up the proper access token inputs.
                fts_access_token_type_ajax(fts_type, cpt_id, false);

            }

        }
        return false;
    });
}

// Use our buttons to change our #feed_type select option.
function fts_combine_social_icons_wrap_click() {
    jQuery( '.fts-combine-instagram-basic-token-button,' +
        '.fts-combine-instagram-business-token-button').click( function () {

        var fts_type = jQuery( this ).data( 'fts-feed-type' );
        // todo SRL: line below needs to be re-written to be more understandable but for launch sake we are rolling with it. Mainly not fixing now because for ease of backward compatibility too.
        var fts_type_value = 'instagram-feed-type' === fts_type ? 'basic' : 'business';
        const url_string = window.location.href;
        const url = new URL( url_string );
        const cpt_id = url.searchParams.get( 'post' );
        jQuery('#combine_instagram_type').val( fts_type_value ).trigger( 'change' ).attr( 'selected', 'selected' );
        jQuery(this).addClass( 'fts-social-icon-wrap-active' );

        // Load up the proper access token inputs.
        fts_access_token_type_ajax( fts_type, cpt_id, fts_type_value );

        return false;
    });
}




function combine_streams_js(){

    if (  'combine-streams-feed-type' === jQuery("#feed_type option:selected").val() ) {

        jQuery('.fts-instagram-basic-business-wrap').remove();
        fts_instagram_basic_business_buttons();

        if( jQuery('.fts-social-icon-wrap-active').hasClass('fts-combine-instagram-basic-token-button') ) {
            // Load up the proper access token inputs.
            jQuery('.combine-instagram-basic-access-token-placeholder').fadeIn();
        }
        else if( jQuery('.fts-social-icon-wrap-active').hasClass('fts-combine-instagram-business-token-button')) {
            // Load up the proper access token inputs.
            jQuery('.combine-instagram-business-access-token-placeholder').fadeIn();
        }
        fts_combine_social_icons_wrap_click();
    }
}

function combine_instagram_token_js() {

    if (jQuery('#combine_instagram').val() == 'yes') {
        jQuery('.combine-instagram-wrap, .combine-instagram-access-token-placeholder').show();
        // Load up the proper access token inputs.
        combine_streams_js();
        fts_combine_social_icons_wrap_click();
    }
    else{
        jQuery('.combine-instagram-wrap, .combine-instagram-access-token-placeholder').hide();
    }

    //Combine Instagram change option
    jQuery('#combine_instagram').bind('change', function (e) {

        if (jQuery('#combine_instagram').val() == 'yes') {

            jQuery('.combine-instagram-wrap, .combine-instagram-access-token-placeholder').show();

            setTimeout(function () {

                if( true === jQuery( '.fts-combine-instagram-basic-token-button' ).hasClass('fts-social-icon-wrap-active') ) {

                    const url_string = window.location.href;
                    const url = new URL(url_string);
                    const cpt_id = url.searchParams.get('post');

                    // Load up the proper access token inputs.
                    fts_access_token_type_ajax('instagram-feed-type', cpt_id, 'basic');
                }

            }, 10);

        }
        else {
            jQuery('.combine-instagram-wrap, .combine-instagram-access-token-placeholder').hide();
        }
        combine_streams_js();
    });
}


function combine_facebook_token_js() {

    if (jQuery('#combine_facebook').val() == 'yes') {
        jQuery('.combine-facebook-wrap, .combine-facebook-access-token-placeholder').show();
        // Load up the proper access token inputs.
        combine_streams_js();
        fts_combine_social_icons_wrap_click();
    }
    else{
        jQuery('.combine-facebook-wrap, .combine-facebook-access-token-placeholder').hide();
    }

    //Combine facebook change option
    jQuery('#combine_facebook').bind('change', function (e) {

        if (jQuery('#combine_facebook').val() == 'yes') {

            // Run check so it only saves once.
            if( 'none' === jQuery( '.combine-facebook-access-token-placeholder' ).css('display') ) {

                const url_string = window.location.href;
                const url = new URL(url_string);
                const cpt_id = url.searchParams.get('post');

                // Load up the proper access token inputs.
                fts_access_token_type_ajax('facebook-feed-type', cpt_id, 'combined-facebook');
            }
            jQuery('.combine-facebook-wrap, .combine-facebook-access-token-placeholder').show();
        }
        else {
            jQuery('.combine-facebook-wrap, .combine-facebook-access-token-placeholder').hide();
        }
        combine_streams_js();
    });
}

function combine_twitter_token_js() {

    if (jQuery('#combine_twitter').val() == 'yes') {
        jQuery('.combine-twitter-wrap, .combine-twitter-access-token-placeholder').show();

        // Load up the proper access token inputs.
        combine_streams_js();
        fts_combine_social_icons_wrap_click();
    }
    else{
        jQuery('.combine-twitter-wrap, .combine-twitter-access-token-placeholder').hide();
    }

    //Combine twitter change option
    jQuery('#combine_twitter').bind('change', function (e) {

        if (jQuery('#combine_twitter').val() == 'yes') {

            // Run check so it only saves once.
            if( 'none' === jQuery( '.combine-twitter-access-token-placeholder' ).css('display') ) {

                const url_string = window.location.href;
                const url = new URL(url_string);
                const cpt_id = url.searchParams.get('post');

                // Load up the proper access token inputs.
                fts_access_token_type_ajax('twitter-feed-type', cpt_id, 'combined-twitter');
            }

            jQuery('.combine-twitter-wrap, .combine-twitter-access-token-placeholder').show();
        }
        else {
            jQuery('.combine-twitter-wrap, .combine-twitter-access-token-placeholder').hide();
        }
        combine_streams_js();
    });
}

function combine_youtube_token_js() {

    if (jQuery('#combine_youtube').val() == 'yes') {
        jQuery('.combine-youtube-wrap, .combine-youtube-access-token-placeholder').show();

        // Load up the proper access token inputs.
        combine_streams_js();
        fts_combine_social_icons_wrap_click();
    }
    else{
        jQuery('.combine-youtube-wrap, .combine-youtube-access-token-placeholder').hide();
    }

    //Combine youtube change option
    jQuery('#combine_youtube').bind('change', function (e) {

        if (jQuery('#combine_youtube').val() == 'yes') {

            // Run check so it only saves once.
            if( 'none' === jQuery( '.combine-youtube-access-token-placeholder' ).css('display') ) {

                const url_string = window.location.href;
                const url = new URL(url_string);
                const cpt_id = url.searchParams.get('post');

                // Load up the proper access token inputs.
                fts_access_token_type_ajax('youtube-feed-type', cpt_id, 'combined-youtube');
            }

            jQuery('.combine-youtube-wrap, .combine-youtube-access-token-placeholder').show();
        }
        else {
            jQuery('.combine-youtube-wrap, .combine-youtube-access-token-placeholder').hide();
        }
        combine_streams_js();
    });
}

// This is used to load up the proper access token button and inputs
// when a social tab is clicked on the first tab which is Feed Setup.
function fts_access_token_type_ajax( feed_type, cpt_id, combined ) {

    var combined_check = false !== combined ? combined : false;

    jQuery.ajax({
        data: {
            action: 'fts_access_token_type_ajax',
            cpt_id: cpt_id,
            feed_combined: combined_check,
            feed_type: feed_type,
            _wpnonce: updatefrombottomParams.accessTokenUpdateNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            if( !jQuery('.combine-streams-feed-type').hasClass( 'fts-social-icon-wrap-active' ) ) {
                jQuery('.fts-access-token').hide();
            }

            // All these case(s) are related to combine except the default.
            switch( combined ) {
                case 'combined-instagram':
                case 'basic':
                    jQuery('.combine-instagram-basic-access-token-placeholder').fadeIn();
                    jQuery('.combine-instagram-business-access-token-placeholder').hide();
                    jQuery('.fts-access-token').fadeIn();

                    // Make sure when we reload our toggle click option otherwise users won't be able to
                    // see the token options if they so desire.
                    fts_reload_toggle_click();
                    break;
                case 'combined-instagram-business':
                case 'business':
                    jQuery('.combine-instagram-business-access-token-placeholder').fadeIn();
                    jQuery('.combine-instagram-basic-access-token-placeholder').hide();
                    jQuery('.fts-access-token').fadeIn();
                    // Make sure when we reload our toggle click option otherwise users won't be able to
                    // see the token options if they so desire.
                    fts_reload_toggle_click();
                    break;
                case 'combined-facebook':
                    console.log('Combined Facebook Name');
                    // Make sure when we reload our toggle click option otherwise users won't be able to
                    // see the token options if they so desire.
                    fts_reload_toggle_click();
                    break;
                case 'combined-twitter':
                    console.log('Combined Twitter Name');
                    // Make sure when we reload our toggle click option otherwise users won't be able to
                    // see the token options if they so desire.
                    fts_reload_toggle_click();
                    break;
                case 'combined-youtube':
                    console.log('Combined YouTube Name');
                    // Make sure when we reload our toggle click option otherwise users won't be able to
                    // see the token options if they so desire.
                    fts_reload_toggle_click();
                    break;
                default:
                    // This loads the Access Token button and inputs and fades it is nicely.
                    // This will only load if a Feed Setup Tab has been clicked too, just to not again.
                    jQuery('.fts-access-token').html(response);
                    jQuery('.fts-access-token').fadeIn();
            }

            // This is here to remove the instagram basic and business buttons when
            // the user clicks on any other button other than instagram to get an access token.
            if( 'instagram-feed-type' === jQuery("#feed_type option:selected").val() ||
                'instagram-business-feed-type' === jQuery("#feed_type option:selected").val() ) {
                jQuery('.fts-instagram-basic-business-wrap').remove();
                fts_instagram_basic_business_buttons();
            }
            else if( 'combine-streams-feed-type' === jQuery("#feed_type option:selected").val() ) {
                jQuery('.combine-instagram-wrap, .fts-instagram-basic-business-wrap').hide();
                combine_instagram_token_js();
                combine_facebook_token_js();
                combine_twitter_token_js();
                combine_youtube_token_js();
                combine_js();
            }
            else {
                jQuery('.fts-instagram-basic-business-wrap').remove();
                jQuery('.instagram-feed-type').removeClass('fts-instagram-sub-menu-active');
            }

            fts_social_icons_wrap_click();
            fts_reload_toggle_click();
            fts_check_valid();

            console.log( feed_type + ': Access Token Button/Options Success' );
        }

    }); // end of ajax()
    return false;
}

// Created a function out of this so we can reload it when the ajax fires to load the access token button and options.
// otherwise the click function will not fire.
function fts_reload_toggle_click(){

    jQuery('#fts-feed-type h3, #fts-feed-type span, .fts-settings-does-not-work-wrap .fts-admin-token-settings').click(function () {

        jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
        jQuery( this ).toggleClass( 'fts-feed-type-active' );
        jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
        jQuery( '#fts-feed-type h3' ).toggleClass( 'fts-admin-token-settings-open' );

        if( jQuery('.combine-instagram-business-access-token-placeholder').length !== 0 ){
            // If the input field is empty, set the cursor to it
            jQuery('#fts_facebook_instagram_custom_api_token_user_id').focus();
        }

    });
}

function fts_check_valid() {

    if( jQuery( '.fts-instagram-token-wrap .fts-instagram-successful-api-token' ).length ) {
        jQuery( '.fts-tab-content1-instagram h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.fts-tab-content1-instagram h3').addClass('fts-active-success-token').css('color', '#1aae1f');
    }
    if( jQuery( '.fts-fb-token-wrap .fts-instagram-business-successful-api-token' ).length &&
        jQuery( '.fts-instagram-business-token-button.fts-social-icon-wrap-active' ).length ) {
        jQuery( '.fts-tab-content1-facebook-instagram h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.fts-tab-content1-facebook-instagram h3' ).addClass('fts-active-success-token').css('color', '#1aae1f');
    }
    if( jQuery( '.combine-instagram-business-access-token-placeholder .fts-instagram-business-combine-successful-api-token' ).length  ){
        jQuery( '.combine-instagram-business-access-token-placeholder .fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.combine-instagram-business-access-token-placeholder .fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');
    }

    if( jQuery( '.combine-instagram-basic-access-token-placeholder .fts-instagram-combine-successful-api-token' ).length  ){
        jQuery( '.combine-instagram-basic-access-token-placeholder .fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.combine-instagram-basic-access-token-placeholder .fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');
    }
    if( jQuery( '.combine-facebook-access-token-placeholder .fts-facebook-combine-successful-api-token' ).length  ){
        jQuery( '.combine-facebook-access-token-placeholder .fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.combine-facebook-access-token-placeholder .fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');
    }
    if( jQuery( '.combine-twitter-access-token-placeholder .fts-twitter-combine-successful-api-token' ).length  ){
        jQuery( '.combine-twitter-access-token-placeholder .fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.combine-twitter-access-token-placeholder .fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');

        jQuery( '#fts-combined-twitter-success' ).insertAfter( jQuery('.combine_twitter_name') );
        // Only display the user/hashtag select yes/no if the token is valid.
        jQuery('.fts-twitter-combine, #fts-combined-twitter-success').show();
    }
    if( jQuery( '.combine-youtube-access-token-placeholder .fts-youtube-combine-successful-api-token' ).length  ){
        jQuery( '.combine-youtube-access-token-placeholder .fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.combine-youtube-access-token-placeholder .fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');

        jQuery( '#fts-combined-youtube-success' ).insertAfter( jQuery('.combine_channel_id') );
        // Only display the user/channel etc select yes/no if the token is valid.
        jQuery('.fts-youtube-combine, #fts-combined-youtube-success').show();
    }
    else if( jQuery( '.fts-facebook-successful-api-token' ).length ||
        jQuery( '.fts-twitter-successful-api-token' ).length ||
        jQuery( '.fts-youtube-successful-api-token' ).length ) {

        jQuery( '.fts-token-wrap h3 .fts-valid-text' ).html(' - Valid');
        jQuery( '.fts-token-wrap h3').addClass('fts-active-success-token').css('color', '#1aae1f');

    }
}


(function ($) {
    "use strict";
    $( document ).ready(function() {

        setTimeout(function () {

            // This is in place for when the page reloads or user refreshes.
            if( !jQuery('div').hasClass('fts-instagram-basic-business-wrap') && jQuery('.fts-social-icon-wrap-active').hasClass('instagram-feed-type') ){
                fts_instagram_basic_business_buttons();
            }
            fts_social_icons_wrap_click();
            fts_reload_toggle_click();
            fts_check_valid();

            // alert('test');

        }, 100);

        // This is for the select a social network tab and controls what tab is selected and visible in the tabs menu,
        $( '#feed_type' ).on(
            'change',
            function (e) {
                // Grab the url so we can do stuff.
                var url_string = window.location.href;
                var url = new URL( url_string );
                var cpt_id = url.searchParams.get("post");
                var feed_type = url.searchParams.get("feed_type");
                console.log( cpt_id );

                var ftsGlobalValue = jQuery( this ).val();

                // console.log(ftgGlobalValue);
                // I know we can figure a way to condense this but for time sake just rolling with this.
                if ( 'facebook-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab5' ).addClass( 'fts-facebook-waiting-color' );
                    jQuery( '.tab5 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab5 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.facebook-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab5' ).removeClass( 'fts-facebook-waiting-color' );
                    jQuery( '.tab5 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab5 a .fts-click-cover' ).show();
                }

                if ( 'instagram-feed-type' == ftsGlobalValue || 'instagram-business-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab4' ).addClass( 'fts-instagram-waiting-color' );
                    jQuery( '.tab4 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab4 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.instagram-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab4' ).removeClass( 'fts-instagram-waiting-color' );
                    jQuery( '.tab4 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab4 a .fts-click-cover' ).show();
                }

                if ( 'twitter-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab6' ).addClass( 'fts-twitter-waiting-color' );
                    jQuery( '.tab6 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab6 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.twitter-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab6' ).removeClass( 'fts-twitter-waiting-color' );
                    jQuery( '.tab6 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab6 a .fts-click-cover' ).show();
                }

                if ( 'youtube-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab7' ).addClass( 'fts-youtube-waiting-color' );
                    jQuery( '.tab7 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab7 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.youtube-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab7' ).removeClass( 'fts-youtube-waiting-color' );
                    jQuery( '.tab7 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab7 a .fts-click-cover' ).show();

                }
                if ( 'combine-streams-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab8' ).addClass( 'fts-combine-waiting-color' );
                    jQuery( '.tab8 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab8 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.combine-streams-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                    // This needs to be here so if the page is refreshed the insta/fb tokens show if they are set to yes.
                    combine_instagram_token_js();
                    combine_facebook_token_js();
                    combine_twitter_token_js();
                    combine_youtube_token_js();
                }
                else  {
                    jQuery( '.tab8' ).removeClass( 'fts-combine-waiting-color' );
                    jQuery( '.tab8 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab8 a .fts-click-cover' ).show();
                }

                if( $('.fts-social-icon-wrap-active .fts-success-token-content').length ){
                    fts_check_valid();
                }
            }
        ).change(); // The .change is so when the page loads it fires this on change event.
        // This way the tab will be active based on the save feed_type option.

        //This is for the sub nav tabs under each social network and controls what is visible.
        var fts_sub_tabs = '<div class="fts-feed-settings-tabs-wrap"><div class="fts-feed-tab fts-sub-tab-active"><span>'+ updatefrombottomParams.mainoptions + '</span></div><div class="fts-settings-tab"><span>'+ updatefrombottomParams.additionaloptions + '</span></div></div>';
        // $( fts_sub_tabs ).insertBefore( '.fts-facebook_page-shortcode-form .fts-cpt-main-options, .fts-instagram-shortcode-form .instagram_feed_type, .fts-twitter-shortcode-form .twitter-messages-selector, .fts-youtube-shortcode-form .youtube-messages-selector, .fts-combine-streams-shortcode-form .combine_post_count' );
        $( fts_sub_tabs ).insertBefore( '.fts-cpt-main-options' );

        $( '.fts-feed-settings-tabs-wrap div' ).click(
            function () {

                if( $( this ).hasClass( 'fts-feed-tab' ) ){

                    $('.fts-cpt-extra-options').hide();
                    $('.fts-cpt-main-options').show();

                    if( $( this ).hasClass('fts-sub-tab-active') ){
                        $( this ).next( 'div').removeClass('fts-sub-tab-active');
                    }
                    else {
                        $( this ).addClass('fts-sub-tab-active').next( 'div').removeClass('fts-sub-tab-active');
                    }
                }
                else if( $( this ).hasClass( 'fts-settings-tab' ) || $( this ).hasClass( 'fts-settings-tab' ) ){

                    $('.fts-cpt-extra-options').show();
                    $('.fts-cpt-main-options').hide();

                    if( $( this ).hasClass('fts-sub-tab-active') ){
                        $( this ).prev( 'div').removeClass('fts-sub-tab-active');
                    }
                    else {
                        $( this ).addClass('fts-sub-tab-active').prev( 'div').removeClass('fts-sub-tab-active');
                    }
                }
            }
        );

        // Convert Old Shortcode click function
        $( '#fts-convert-old-shortcode' ).click(
            function () {

                console.log( 'Clicked Convert Shortcode Button' );

                window.location.hash.replace('#', '');

                let fts_shortcode = $( '#ft-galleries-old-shortcode-side-mb input' ).val();

                if( !fts_shortcode ){
                    alert( 'Please add a Feed Them Social Shortcode to Convert.');
                    return;
                }
                var result = '';
                var inQuotes = false;
                var quoteSegment = '';

                for (var i = 0; i < fts_shortcode.length; i++) {
                    var char = fts_shortcode[i];

                    if (char === '"' || char === "'") {
                        if (inQuotes) {
                            // Process the quote segment: replace spaces with asterisks
                            result += quoteSegment.replace(/ /g, '*');
                            quoteSegment = ''; // Reset the quote segment for next use
                        }
                        inQuotes = !inQuotes; // Toggle the inQuotes flag
                    } else if (inQuotes) {
                        quoteSegment += char; // Accumulate text inside quotes
                    } else {
                        result += char; // Add text outside quotes directly to result
                    }
                }

                var fts_shortcode_fix = result;
                //console.log( fts_shortcode_fix );
                // return;
                // take shortcode and extract any spaces that surround a shortcode value. ie padding="20px 10px" end result looks like 20px*10px

                var fts_final1 = fts_shortcode_fix.replace("fts_twitter", "").replace("[ ", "").replace("]", "").split( " " );
                var fts_final2 = Array.from( fts_final1 );
                let text = "";
                // text +=  item.substring(0, item.indexOf("=")) + '=' + item.substring(item.indexOf("=") + 1) + "<br>";

                // The Combined shortcode is a little more tricky because we load up all the access tokens with ajax at once.
                // This handy little statement will run the check to see if its a mashup shortcode, then click the combined tab to load the access tokens.
                // Then re-click the convert shortcode option so all the feed types get loaded up properly.
                /*    if( fts_shortcode_fix.indexOf("fts_mashup") ){
                        alert('test1111');
                      //  if( !$( '.combine-streams-feed-type' ).hasClass('fts-social-icon-wrap-active') ){
                      //  alert('test22222');
                            $( '.fts-social-icon-wrap.combine-streams-feed-type' ).click();
                            setTimeout(function () {
                                $( '#fts-convert-old-shortcode' ).click();
                                return true;
                            }, 0);
                      //  }

                    }*/

                if( fts_shortcode_fix.indexOf("fts_mashup") !== -1 ){

                    if( !$( '.combine-streams-feed-type' ).hasClass('fts-social-icon-wrap-active') ){

                        $( '.fts-social-icon-wrap.combine-streams-feed-type' ).click();
                        // This is a simple stupid way to make user wait a second so the combined access token options can load up.
                        // This is not a problem if you are already on the combined streams tab, but for the sake of users not clicking there first,
                        // this is a dirty work around.
                        setTimeout(function () {
                            // Now click the convert shortcode again so we can fill in the access token options.
                            $( '#fts-convert-old-shortcode' ).click();
                        }, 100);

                        alert('Thank You for using the Shortcode Converter to convert your Combined Streams. No more endless shortcode options. Now it will be easier than ever to change options and see the results.');

                        return true;
                    }

                }




                fts_final2.forEach(myFunction);

                function myFunction(item, index) {

                    var attribute_id = '#' + item.substring(0, item.indexOf("=") );
                    var attribute = item.substring(0, item.indexOf("=") );
                    var value =   item.substring(item.indexOf("=") + 1).replace(/\*/g, " ");

                    // alert( value );
                    // alert( '#' + item.substring(0, item.indexOf("=") ) );

                    if( fts_shortcode_fix.includes("fts_twitter") ){

                        $( '#feed_type option[value=twitter-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content7 ';

                        if( 'yes' == value ){
                            if ( 'popup' == attribute ){
                                //alert( 'test' );
                                $( id + '#tiktok_popup_option option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#tiktok-grid-option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-twitter-grid-options-wrap' ).show();
                            }
                            else if ( 'cover_photo' == attribute ){
                                //alert( 'test' );
                                $( id + '#twitter-cover-photo option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'stats_bar' == attribute ){
                                $( '#twitter-stats-bar' ).val( value );
                            }
                            else if ( 'show_retweets' == attribute ){
                                $( '#twitter-show-retweets' ).val( value );
                            }
                            else if ( 'show_replies' == attribute ){
                                $( '#twitter-show-replies' ).val( value );
                            }
                        }
                        else if (  'search' == attribute ) {
                            $( id + '#twitter-messages-selector option[value=hashtag]' ).attr('selected','selected');
                            $( id + '.twitter-hashtag-etc-wrap' ).css('display', 'inline-block');
                            $( '#twitter_hashtag_etc_name' ).val( value );
                        }
                        else if ( 'loadmore' == attribute ){
                            // alert( 'test' );
                            if ( 'autoscroll' == value ){
                                $( id + '#tiktok_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                            }
                            $( id + '#tiktok_load_more_option option[value=yes]' ).attr('selected','selected');
                            $( id + '.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap' ).show();
                        }
                        else {
                            if( 'loadmore_btn_margin' == attribute ){
                                $( '#tiktok_loadmore_button_margin' ).val( value );
                            }
                            else if ( 'loadmore_btn_maxwidth' == attribute ){
                                $( '#tiktok_loadmore_button_width' ).val( value );
                            }
                            else if ( 'loadmore_count' == attribute ){
                                $( '#twitter_loadmore_count' ).val( value );
                            }
                            else if ( 'colmn_width' == attribute ){
                                $( '#tiktok_grid_column_width' ).val( value );
                            }
                            else if ( 'space_between_posts' == attribute ){
                                $( '#tiktok_grid_space_between_posts' ).val( value );
                            }
                            else {
                                $(attribute_id).val(value);
                            }
                        }
                    }

                    if( fts_shortcode_fix.includes("fts_facebook") ){

                        $( '#feed_type option[value=facebook-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content6 ';

                        if ( 'type' == attribute ) {
                            $( id + '#facebook_page_feed_type option[value='+ value +']' ).attr('selected','selected');
                        }
                        else if (  'id' == attribute ) {
                            $( id + '#facebook_page_feed_type option[value=page]' ).attr('selected','selected');
                            // $( id + '.twitter-hashtag-etc-wrap' ).show();
                            $( '#facebook_page_id' ).val( value );
                        }
                        else if ( 'posts_displayed' == attribute ){
                            if ( 'page_and_others' == value ){
                                $( id + '#facebook_page_posts_displayed option[value=page_and_others]' ).attr('selected','selected');
                            }
                            else {
                                $( id + '#facebook_page_posts_displayed option[value=page_only]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'posts' == attribute ){
                            $( '#facebook_page_post_count' ).val( value );
                        }
                        else if( 'yes' == value ){
                            if ( 'popup' == attribute ){
                                //alert( 'test' );
                                $( id + '#facebook_popup option[value=yes]' ).attr('selected','selected');
                                $( id + '.display-comments-wrap' ).show();
                            }
                            else if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#facebook_grid option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-facebook-grid-options-wrap' ).show();
                            }
                            else if ( 'facebook_popup_comments' == attribute ){
                                //alert( 'test' );
                                $( id + '#facebook_popup_comments option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'hide_date_likes_comments' == attribute ){
                                $( id + '#facebook_hide_date_likes_comments option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'hide_see_more_reviews_link' == attribute ){
                                $( id + '#hide_see_more_reviews_link option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'remove_reviews_no_description' == attribute ){
                                $( id + '#remove_reviews_with_no_description option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'play_btn' == attribute ){
                                $( id + '#facebook_show_video_button option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'play_btn_visible' == attribute ){
                                $( id + '#facebook_show_video_button_in_front option[value=yes]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'height' == attribute ){
                            $( id + '#facebook_page_height' ).val( value );
                        }
                        else if ( 'title' == attribute && 'no' == value ){
                            $( id + '#fb_page_title_option option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'description' == attribute && 'no' == value ){
                            $( id + '#facebook_page_description option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'title_align' == attribute ){
                            if( 'left' == value ){
                                $( id + '#fb_page_title_align option[value=left]' ).attr('selected','selected');
                            }
                            else if( 'center' == value ){
                                $( id + '#fb_page_title_align option[value=center]' ).attr('selected','selected');
                            }
                            else if( 'right' == value ){
                                $( id + '#fb_page_title_align option[value=right]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'show_media' == attribute ){
                            if( 'top' == value ){
                                $( id + '#facebook_show_media option[value=top]' ).attr('selected','selected');
                            }
                            else if( 'bottom' == value ){
                                $( id + '#facebook_show_media option[value=bottom]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'show_thumbnail' == attribute && 'no' == value ){
                            $( id + '#facebook_hide_thumbnail option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'show_date' == attribute && 'no' == value ){
                            $( id + '#facebook_hide_date option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'show_name' == attribute && 'no' == value ){
                            $( id + '#facebook_hide_name option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'hide_like_option' == attribute && 'no' == value ){
                            //alert( 'test' );
                            $( id + '#facebook_hide_like_box_button option[value=no]' ).attr('selected','selected');
                            $( id + '.like-box-wrap' ).show();
                        }
                        else if ( 'words' == attribute ){
                            $( id + '#facebook_page_word_count' ).val( value );
                        }
                        else if ( 'images_align' == attribute ){
                            if( 'left' == value ){
                                $( id + '#facebook_align_images option[value=left]' ).attr('selected','selected');
                            }
                            else if( 'center' == value ){
                                $( id + '#facebook_align_images option[value=center]' ).attr('selected','selected');
                            }
                            else if( 'right' == value ){
                                $( id + '#facebook_align_images option[value=right]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'album_id' == attribute ){
                            $( id + '#facebook_album_id' ).val( value );
                        }
                        else if ( 'image_width' == attribute ){
                            $( id + '#facebook_image_width' ).val( value );
                        }
                        else if ( 'image_height' == attribute ){
                            $( id + '#facebook_image_height' ).val( value );
                        }
                        else if ( 'space_between_photos' == attribute ){
                            $( id + '#facebook_space_between_photos' ).val( value );
                        }

                        else if ( 'center_container' == attribute && 'yes' == value  ){
                            $( id + '#facebook_container_position option[value=yes]' ).attr('selected','selected');
                        }

                        else if (  'show_follow_btn_where' == attribute ) {
                            if ( 'above_title' == value ){
                                $( id + '#facebook_position_likebox option[value=above_title]' ).attr('selected','selected');
                            }
                            else if ( 'bottom' == value ) {
                                $( id + '#facebook_position_likebox option[value=bottom]' ).attr('selected','selected');
                            }
                            else if ( 'below_title' == value ) {
                                $( id + '#facebook_position_likebox option[value=below_title]' ).attr('selected','selected');
                            }

                        }
                        else if (  'like_option_align' == attribute ) {
                            if ( 'left' == value ){
                                $( id + '#facebook_align_likebox option[value=left]' ).attr('selected','selected');
                            }
                            else if ( 'center' == value ) {
                                $( id + '#facebook_align_likebox option[value=center]' ).attr('selected','selected');
                            }
                            else if ( 'right' == value ) {
                                $( id + '#facebook_align_likebox option[value=right]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'loadmore' == attribute ){
                            // alert( 'test' );
                            if ( 'autoscroll' == value ){
                                $( id + '#facebook_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                            }
                            $( id + '#facebook_load_more_option option[value=yes]' ).attr('selected','selected');
                            $( id + '.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap' ).show();
                        }
                        else if ( 'overall_rating' == attribute && 'no' == value ){
                            $( id + '#reviews_overall_rating_show option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'remove_reviews_no_description' == attribute && 'no' == value ){
                            $( id + '#remove_reviews_with_no_description option[value=yes]' ).attr('selected','selected');
                        }
                        else if ( 'facebook_play_btn_size' == attribute ){
                            $( id + '#facebook_size_video_play_btn' ).val( value );
                        }
                        else if ( 'scrollhorz_or_carousel' == attribute ){
                            $( id + '#fts-slider option[value=yes]' ).attr('selected','selected');
                        }
                        else {
                            if( 'loadmore_btn_margin' == attribute ){
                                $( '#loadmore_button_margin' ).val( value );
                            }
                            else if ( 'loadmore_btn_maxwidth' == attribute ){
                                $( '#loadmore_button_width' ).val( value );
                            }
                            else if ( 'loadmore_count' == attribute ){
                                $( '#twitter_loadmore_count' ).val( value );
                            }
                            else if ( 'colmn_width' == attribute ){
                                $( '#facebook_grid_column_width' ).val( value );
                            }
                            else if ( 'space_between_posts' == attribute ){
                                $( '#facebook_grid_space_between_posts' ).val( value );
                            }
                            else {
                                $(attribute_id).val(value);
                            }
                        }
                    }

                    if( fts_shortcode_fix.includes("fts_youtube") ){

                        $( '#feed_type option[value=youtube-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content8 ';

                        if( 'yes' == value ){
                            if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#tiktok-grid-option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-twitter-grid-options-wrap' ).show();
                            }
                            else if ( 'large_vid_title' == attribute ){
                                $( id + '#youtube_large_vid_title option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'large_vid_description' == attribute ){
                                $( id + '#youtube_large_vid_description option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'omit_first_thumbnail' == attribute ) {
                                $( id + '#youtube_omit_first_thumbnail option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'force_columns' == attribute ){
                                $( id + '#youtube_force_columns option[value=yes]' ).attr('selected','selected');
                            }
                        }
                        else {
                            if ( 'thumbs_play_in_iframe' == attribute ){
                                $( id + '#youtube_play_thumbs' ).val( value );
                            }
                            else if ( 'vids_in_row' == attribute ){
                                $( id + '#youtube_columns' ).val( value );
                            }
                            else if ( 'loadmore' == attribute ){
                                // alert( 'test' );
                                if ( 'autoscroll' == value ){
                                    $( id + '#youtube_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                                }
                                $( id + '#youtube_load_more_option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-youtube-load-more-options-wrap, .fts-youtube-load-more-options2-wrap' ).show();
                            }
                            else if ( 'large_vid' == attribute ){
                                $( id + '#youtube_first_video option[value=no]' ).attr('selected','selected');
                            }
                            else if ( 'maxres_thumbnail_images' == attribute ){
                                $( id + '#youtube_maxres_thumbnail_images option[value=no]' ).attr('selected','selected');
                            }
                            else if ( 'wrap' == attribute ){
                                $( id + '#youtube_thumbs_wrap' ).val( value );
                            }
                            else if ( 'wrap_single' == attribute ){
                                $( id + '#youtube_comments_wrap' ).val( value );
                            }
                            else if ( 'video_wrap_display' == attribute ){
                                $( id + '#youtube_video_thumbs_display' ).val( value );
                            }
                            else if ( 'video_wrap_display_single' == attribute ){
                                $( id + '#youtube_video_comments_display' ).val( value );
                            }
                            else if( 'loadmore_btn_margin' == attribute ){
                                $( '#youtube_loadmore_button_margin' ).val( value );
                            }
                            else if ( 'thumbs_wrap_color' == attribute ){
                                $( '#youtube_thumbs_wrap_color' ).val( value );
                            }
                            else if ( 'space_between_videos' == attribute ){
                                $( '#youtube_container_margin' ).val( value );
                            }
                            else if ( 'channel_id' == attribute ){
                                $( '#youtube_channelID' ).val( value );
                            }
                            else if ( 'vid_count' == attribute ){
                                $( '#youtube_vid_count' ).val( value );
                            }
                            else if ( 'loadmore_btn_maxwidth' == attribute ){
                                $( '#youtube_loadmore_button_width' ).val( value );
                            }
                            else if ( 'loadmore_count' == attribute ){
                                $( '#youtube_loadmore_count' ).val( value );
                            }
                            else if ( 'colmn_width' == attribute ){
                                $( '#youtube_grid_column_width' ).val( value );
                            }
                            else if ( 'space_between_posts' == attribute ){
                                $( '#youtube_grid_space_between_posts' ).val( value );
                            }
                            else if ( 'comments_count' == attribute ){
                                $( '#youtube_comments_count' ).val( value );
                            }
                            else {
                                $(attribute_id).val(value);
                            }
                        }
                    }

                    if( fts_shortcode_fix.includes("fts_instagram") ){

                        // $( '#feed_type option[value=instagram-feed-type], #feed_type option[value=instagram-business-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content5 ';

                        if( 'yes' == value ){
                            if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#tiktok-grid-option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-twitter-grid-options-wrap' ).show();
                            }
                            else if( 'popup' == attribute ){
                                $( '#instagram_popup_option option[value=yes]' ).attr('selected','selected');
                            }
                            else if( 'hide_date_likes_comments' == attribute ){
                                $( '#instagram_hide_date_likes_comments option[value=yes]' ).attr('selected','selected');
                            }
                            else if( 'force_columns' == attribute ){
                                $( '#instagram_force_columns option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_wrap' == attribute ){
                                $( '#instagram_profile_wrap option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_photo' == attribute ){
                                $( '#instagram_profile_photo option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_stats' == attribute ){
                                $( '#instagram_profile_stats option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_name' == attribute ){
                                $( '#instagram_profile_name option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_description' == attribute ){
                                $( '#instagram_profile_description option[value=yes]' ).attr('selected','selected');
                            }
                        }
                        else {
                            if ( 'loadmore' == attribute ){
                                // alert( 'test' );
                                if ( 'autoscroll' == value ){
                                    $( id + '#instagram_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                                }
                                $( id + '#instagram_load_more_option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-instagram-load-more-options-wrap, .fts-instagram-load-more-options2-wrap' ).show();
                            }
                            else if( 'type' == attribute ){
                                if ( 'basic' == value ){
                                    $( '#feed_type option[value=instagram-feed-type]' ).attr('selected','selected');
                                }
                                if ( 'business' == value || 'hashtag' == value ){
                                    $( '#feed_type option[value=instagram-business-feed-type]' ).attr('selected','selected');
                                }
                                if ( 'hashtag' == value ) {
                                    jQuery('#instagram_feed_type').find('[value="hashtag"]').attr('selected', 'selected');
                                }
                            }
                            else if ( 'instagram_id' == attribute ){
                                $( '#fts_instagram_custom_id' ).val( value );
                            }
                                // the refresh token is not something we can convert from shortcode unfortunately.
                                // Force users to get a new access token so the refresh token part is also included and is encrypted before adding to database.
                            /*else if ( 'access_token' == attribute ){
                                $( '#fts_instagram_custom_api_token' ).val( value );
                            }*/
                            else if ( 'hashtag' == attribute ){
                                $( '#instagram_hashtag' ).val( value );
                            }
                            else if ( 'pics_count' == attribute ){
                                $( '#instagram_pics_count' ).val( value );
                            }
                            else if( 'search' == attribute ){
                                $( '#instagram_hashtag_type' ).val( value );
                            }
                            else if( 'columns' == attribute ){
                                $( '#instagram_columns' ).val( value );
                            }
                            else if( 'loadmore_btn_margin' == attribute ){
                                $( '#instagram_loadmore_button_margin' ).val( value );
                            }
                            else if ( 'loadmore_btn_maxwidth' == attribute ){
                                $( '#instagram_loadmore_button_width' ).val( value );
                            }
                            else if ( 'loadmore_count' == attribute ){
                                $( '#instagram_loadmore_count' ).val( value );
                            }
                            else if ( 'colmn_width' == attribute ){
                                $( '#instagramgrid_column_width' ).val( value );
                            }
                            else if ( 'space_between_posts' == attribute ){
                                $( '#instagram_grid_space_between_posts' ).val( value );
                            }
                            else if ( 'width' == attribute ){
                                $( '#instagram_page_width' ).val( value );
                            }
                            else if ( 'height' == attribute ){
                                $( '#instagram_page_height' ).val( value );
                            }
                            else if ( 'icon_size' == attribute ){
                                $( '#instagram_icon_size' ).val( value );
                            }
                            else if ( 'space_between_photos' == attribute ){
                                $( '#instagram_space_between_photos' ).val( value );
                            }
                            else {
                                $(attribute_id).val(value);
                            }
                        }
                    }

                    if( fts_shortcode_fix.includes("fts_mashup") ){

                        $( '#feed_type option[value=combine-streams-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content9 ';

                        if( 'yes' == value ){
                            if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( '#combine_grid_option option[value=yes]' ).attr('selected','selected');
                                $( '.fts-combine-grid-options-wrap' ).show();
                            }
                            else if( 'popup' == attribute ){
                                $( '#instagram_popup_option option[value=yes]' ).attr('selected','selected');
                            }
                        }

                            //[fts_mashup posts=12
                            // social_network_posts=4
                            // words=55
                            // center_container=no
                            // height=450px
                            // background_color=#75a3ff
                            // show_social_icon=left
                            // show_media=top
                            // show_date=no
                            // show_name=no
                            // padding=20px
                            // facebook_name=1562664650673366
                            // twitter_name=twittername
                            // hashtag=tytytyty
                            // instagram_search=top-media
                            // grid=yes
                            // instagram_type=business
                            // hashtag=asdfasdfasdf
                            // instagram_name=17841400646076739
                            // channel_id=mnmnmnm
                            // playlist_id=vasdfbvbvb
                            // column_width=310px
                        // space_between_posts=10px]

                        else if ( 'posts' == attribute ){
                            $( '#combine_post_count' ).val( value );
                        }
                        else if( 'facebook_name' == attribute ){
                            $( '#combine_facebook option[value=yes]' ).attr('selected','selected');
                            $( '#combine_facebook_name' ).val( value );
                        }
                        else if( 'twitter_name' == attribute ){
                            $( '#combine_twitter option[value=yes]' ).attr('selected','selected');
                            $( '#combine_twitter_name' ).val( value );
                        }
                        else if( 'instagram_name' == attribute ){
                            $( '#combine_instagram option[value=yes]' ).attr('selected','selected');
                            $( '#combine_instagram_name' ).val( value );
                        }
                        else if( 'instagram_type' == attribute ){
                            $( '#combine_instagram_type' ).val( value );
                        }
                        else if( 'hashtag' == attribute ){
                            $( '#combine_instagram_hashtag' ).val( value );
                        }
                        else if ( 'social_network_posts' == attribute ){
                            $( '#combine_social_network_post_count' ).val( value );
                        }
                        else if ( 'instagram_search' == attribute ){
                            $( '#combine_instagram_hashtag_type' ).val( value );
                        }
                        else if ( 'words' == attribute ){
                            $( '#combine_word_count_option' ).val( value );
                        }
                        else if( 'center_container' == attribute ){
                            $( '#combine_container_position option[value=yes]' ).attr('selected','selected');
                        }
                        else if ( 'height' == attribute ){
                            $( '#combine_height' ).val( value );
                        }
                        else if ( 'column_width' == attribute ){
                            $( '#combine_grid_column_width' ).val( value );
                        }
                        else if ( 'space_between_posts' == attribute ){
                            $( '#combine_grid_space_between_posts' ).val( value );
                        }
                        else if ( 'background_color' == attribute ){
                            $( '#combine_background_color' ).val( value );
                        }
                        else if ( 'facebook_show_social_icon' == attribute ){
                            $( '#combine_show_social_icon' ).val( value );
                        }
                        else if ( 'show_media' == attribute ){
                            $( '#combine_show_media' ).val( value );
                        }
                        else if( 'show_date' == attribute ){
                            $( '#combine_hide_date option[value=no]' ).attr('selected','selected');
                        }
                        else if( 'show_name' == attribute ){
                            $( '#combine_hide_name option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'padding' == attribute ){
                            $( '#combine_padding' ).val( value );
                        }
                        else if ( 'youtube_name' == attribute ){
                            $( '#combine_youtube_name' ).val( value );
                            $( '#combine_youtube option[value=yes]' ).attr('selected','selected');
                        }
                        else if ( 'channel_id' == attribute ){
                            $( '#combine_channel_id' ).val( value );
                            $( '#combine_youtube option[value=yes]' ).attr('selected','selected');
                        }
                        else if ( 'playlist_id' == attribute ){
                            $( '#combine_playlist_id' ).val( value );
                            $( '#combine_youtube option[value=yes]' ).attr('selected','selected');
                        }
                        else {
                            $(attribute_id).val(value);
                        }
                    }
                }

                setTimeout(function () {
                    // This is outside the foreach loop because of it's complicated nature.
                    if ( $( '#combine_youtube_name').val() && ! $( '#combine_playlist_id' ).val() ) {
                        $( '#combine_youtube_type option[value=username]' ).attr('selected','selected');
                    }
                    else if ( $( '#combine_youtube_name').val() && $( '#combine_playlist_id' ).val() ) {
                        $( '#combine_youtube_type option[value=userPlaylist]' ).attr('selected','selected');
                    }
                    else if ( $( '#combine_channel_id').val() && ! $( '#combine_playlist_id' ).val() ) {
                        $( '#combine_youtube_type option[value=channelID]' ).attr('selected','selected');
                    }
                    else if ( $( '#combine_channel_id').val() && $( '#combine_playlist_id' ).val() ) {
                        $( '#combine_youtube_type option[value=playlistID]' ).attr('selected','selected');
                    }
                }, 10);

                fts_ajax_cpt_save('yes');
            }
        );

        // once the page reloads we show the Success, Shortcode Converted Message.
        var shortcodeConverted = window.location.hash.replace('#', '');

        if ( shortcodeConverted === 'fts-convert-old-shortcode' ) {
            // Save all the options and show the success message
            jQuery( "#ftg-tab-content1" ).prepend('<div class="fts-shortocde-success-message"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M150.6 201.4c-6.074-6.074-14.28-9.356-22.7-9.356c-2.234 0-4.482 .2269-6.711 .6996C110.6 195 101.8 202.6 98.03 212.7l-95.1 256c-4.406 11.75-1.531 25 7.344 33.88C15.47 508.7 23.66 512 32 512c3.781 0 7.594-.6563 11.25-2.031l255.1-96c10.19-3.812 17.72-12.56 20.03-23.19c2.281-10.62-.9684-21.72-8.656-29.41L150.6 201.4zM37.62 494.1c-1.777 .6699-3.666 1.008-5.617 1.008c-4.285 0-8.293-1.654-11.31-4.689c-4.432-4.43-5.875-11.08-3.678-16.94l34.37-91.67l77.91 77.91L37.62 494.1zM145.8 454.4l-88.2-88.2L86.3 289.6l136.1 136.1L145.8 454.4zM293.6 398.1l-54.79 20.54l-146.4-146.4L113 218.4c1.908-5.098 6.246-8.838 11.52-9.986c1.117-.2363 2.26-.3555 3.396-.3555c4.264 0 8.412 1.703 11.38 4.672l159.1 159.1c3.861 3.861 5.478 9.369 4.336 14.69C302.5 392.7 298.7 397.1 293.6 398.1zM503.1 272c4.406 0 7.1-3.594 7.1-7.1c0-4.406-3.594-7.1-7.1-7.1H491.7c-53.81 0-104.3 30.5-138.5 83.69c-2.406 3.687-1.312 8.656 2.406 11.03c1.312 .875 2.812 1.281 4.312 1.281c2.625 0 5.187-1.281 6.719-3.687c31.19-48.5 76.78-76.31 125-76.31H503.1zM168 160c1.5 0 2.1-.4062 4.312-1.281C225.5 124.5 256 74.07 256 20.25V8.005C256 3.599 252.4 .0051 248 .0051S240 3.599 240 8.005v12.25c0 48.25-27.81 93.84-76.31 125C159.1 147.7 158.9 152.6 161.3 156.3C162.8 158.7 165.4 160 168 160zM64 63.1c17.67 0 31.1-14.33 31.1-31.1S81.67 .0013 64 .0013S32 14.33 32 32S46.33 63.1 64 63.1zM64.01 16c8.82 0 15.1 7.176 15.1 15.1c0 8.82-7.176 15.1-15.1 15.1c-8.822 0-15.1-7.176-15.1-15.1C48.01 23.18 55.18 16 64.01 16zM263.1 224c2.281 0 4.562-.9687 6.156-2.875c14.66-17.59 29.78-18.97 47.31-20.56c18.37-1.656 39.22-3.562 58.12-26.22C394.4 151.8 393.1 130.4 391.9 111.6c-1.156-18.16-2.125-33.81 12.66-51.53c14.69-17.62 29.81-18.1 47.34-20.62c18.41-1.687 39.28-3.594 58.22-26.28c2.812-3.406 2.375-8.437-1.031-11.28c-3.375-2.812-8.406-2.375-11.28 1.031c-14.69 17.62-29.81 18.1-47.34 20.62c-18.41 1.687-39.28 3.594-58.22 26.28c-18.84 22.59-17.5 43.94-16.31 62.78c1.125 18.12 2.125 33.81-12.69 51.53c-14.66 17.59-29.78 18.97-47.28 20.56c-18.37 1.656-39.22 3.562-58.16 26.22C255 214.3 255.5 219.3 258.9 222.2C260.4 223.4 262.2 224 263.1 224zM479.1 416c-17.67 0-31.1 14.33-31.1 31.1s14.33 31.1 31.1 31.1s31.1-14.33 31.1-31.1S497.7 416 479.1 416zM479.1 463.1c-8.822 0-15.1-7.176-15.1-15.1c0-8.822 7.176-15.1 15.1-15.1c8.82 0 15.1 7.176 15.1 15.1C495.1 456.8 488.8 463.1 479.1 463.1zM479.1 128c-17.67 0-31.1 14.33-31.1 31.1s14.33 31.1 31.1 31.1s31.1-14.33 31.1-31.1S497.7 128 479.1 128zM479.1 176c-8.822 0-15.1-7.176-15.1-15.1c0-8.82 7.176-15.1 15.1-15.1c8.82 0 15.1 7.176 15.1 15.1C495.1 168.8 488.8 176 479.1 176z"/></svg><h1>Success, Shortcode Converted!</h1><span>Click the "Login and Get my Access Token" button to complete the process. Once complete replace your old shortcode with the new one. You can find your new Feed Shortcode in the right hand sidebar of this page.</span></div>');
            jQuery( '#fts-convert-old-shortcode' ).css('display', 'inline-block');
        }



        // Display additional Settings link.
        $('<div class="fts-note fts-note-footer">' + updatefrombottomParams.additionalSettings + '</div>').appendTo(".tab-content");


        // Button markup depending on post/page status
        if($('#publish, #publishing-action input[type=submit]').val() == updatefrombottomParams.publish) {
            $('<div class="updatefrombottom" ><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.publish+'</a></div>').appendTo(".tab-content");
        } else {
            $('<div class="updatefrombottom"><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.update+'</a></div>').appendTo(".tab-content");
        }

        // DOM Caching
        var elements =  {
            box    : $('.updatefrombottom'),
            heart  : $('#jsc-heart'),
            update  : $('.updatefrombottom .button-primary'),
            publish: $('#publish, #publishing-action input[type=submit]'),
            totop : $('.updatefrombottom .button-totop')
        };

        elements.box.hide();

        // Publish/Update content
        elements.update.on('click', function(e){

            if($(this).text() == updatefrombottomParams.publish) {
                $(this).text(updatefrombottomParams.publishing);

            } else {
                $(this).text(updatefrombottomParams.updating);
            }

            elements.publish.trigger('click');

            e.preventDefault();

        });
        // Scroll to top
        elements.totop.on('click', function(event){
            event.preventDefault();
            $('html, body').animate({scrollTop : 0}, 600);
        });

        $.fn.isInViewport = function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();

            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            return elementBottom > viewportTop && elementTop < viewportBottom;
        };

        if ( $('body.post-type-fts #feed_setup').length > 0 ) {
            $(window).on('resize scroll', function () {

                if ($('#publish, #publishing-action input[type=submit]').isInViewport()) {
                    elements.box.hide();
                } else {
                    // do something else
                    elements.box.show();
                }
            });
        }

        // Add active class to first tab
        $('#fts-import-export-feed-options-side-mb .fts-import-export-tab-nav li:first-child a').addClass('active');
        $('#fts-import-export-feed-options-side-mb .fts-import-export-tab-content > div:first-child').addClass('active');

        // Switch tabs
        $('#fts-import-export-feed-options-side-mb .fts-import-export-tab-nav li a').click(function(event) {
            event.preventDefault();

            // Remove active class from previous tab
            $('#fts-import-export-feed-options-side-mb .fts-import-export-tab-nav li a.active').removeClass('active');
            $('#fts-import-export-feed-options-side-mb .fts-import-export-tab-content > div.active').removeClass('active');

            // Add active class to clicked tab
            $(this).addClass('active');
            $($(this).attr('href')).addClass('active');
        });

        // Import Feed Options
        $( '#fts-import-feed-options' ).click( function () {

            // Grab the url so we can do stuff.
            var url_string = window.location.href;
            var url = new URL( url_string );
            var cpt_id = url.searchParams.get("post");
            var cpt_import =  $( '#fts-import-export-feed-options-side-mb  .fts-import-feed-widget-wrap input' ).val();

            try {
                // Make sure this is valid JSON before proceeding.
                var cpt_import = JSON.parse( cpt_import ) ? cpt_import : null;
                console.log('JSON is valid');
            } catch (e) {
                alert( 'JSON is not valid' );
                console.log('JSON is not valid: ' + e);
                return false;
            }

            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-saving-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "visible");
            jQuery.ajax({
                data: {
                    action: "fts_import_feed_options_ajax",
                    cpt_id: cpt_id,
                    cpt_import: cpt_import,
                    _wpnonce: ftg_mb_tabs.ajaxImportFeedOptionsNonce
                },
                type: 'POST',
                url: ftsAjax.ajaxurl,
                success: function (response) {
                    console.log('Well Done and got this from sever: ' + response);
                    $( '#fts-import-export-feed-options-side-mb  .fts-import-feed-widget-wrap input' ).val('');
                    $( '#fts-import-export-feed-options-side-mb  .fts-import-feed-widget-wrap .publishing-action' ).append('<div class="fts-import-feed-success">Import Success</div>');

                    location.reload();
                    return false;
                }
            }); // end of ajax()
            return false;

        });

        // Export Feed Options
        $( '#fts-export-feed-options' ).click( function () {
            const buttonClick = true;
            import_export_ajax_content( buttonClick );
        });
    });
}(jQuery));

function import_export_ajax_content( buttonClick ) {
    // Grab the url so we can do stuff.
    var url_string = window.location.href;
    var url = new URL( url_string );
    var cpt_id = url.searchParams.get("post");

    jQuery.ajax({
        data: {
            action: "fts_export_feed_options_ajax",
            cpt_id: cpt_id,
            _wpnonce: ftg_mb_tabs.ajaxExportFeedOptionsNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function (response) {
            let data = JSON.parse( response );
            //console.log('Well Done and got this from sever: ' + data.feed_options);
            if( buttonClick ) {
                jQuery('#fts-import-export-feed-options-side-mb  .fts-export-feed-widget-wrap input').val(data.feed_options);
            }
            else{
                fts_beacon_support_auto_fill_json_options( response );
            }
            return false;
        }
    }); // end of ajax()
    return false;
}

// Specific to the "Not Working?" button next to Settings button
// which is next to each Login and Get My Access Token button for each feed.
function fts_beacon_support_click() {
    Beacon('toggle');
    Beacon('search', 'Access Token Not Working');
}

if( jQuery('.post-type-fts').length ) {
    !function (e, t, n) {
        function a() {
            var e = t.getElementsByTagName("script")[0], n = t.createElement("script");
            n.type = "text/javascript", n.async = !0, n.src = "https://beacon-v2.helpscout.net", e.parentNode.insertBefore(n, e)
        }
        if (e.Beacon = n = function (t, n, a) {
            e.Beacon.readyQueue.push({method: t, options: n, data: a})
        }, n.readyQueue = [], "complete" === t.readyState) return a();
        e.attachEvent ? e.attachEvent("onload", a) : e.addEventListener("load", a, !1)
    }(window, document, window.Beacon || function () {
    });

    // Test Version.
    // window.Beacon('init', '79dd74cf-0b71-4291-8dbb-412523d95abb');
    // Live Version.
    window.Beacon('init', 'bf9f4457-217a-49e4-b027-dd6b784c6fc0');

    // For Testing Only.
    // Beacon('reset');
    // Beacon('config', {
    // https://developer.helpscout.com/beacon-2/web/javascript-api/#beacon-event-eventobject
    // leaving this for reference. The way it works now is this.
    // If the system info and or feed options are not passed to the
    // beacon then the input field will show. Otherwise they are hidden.
    // Less clutter and fields the user needs to look at.
    // showPrefilledCustomFields: true,
    // });

    Beacon('once', 'open', () => {
        // SRL: For now we are only loading this option on the feed edit pages.
        // Need to pass more ajax functions or something to the other pages.
        if( jQuery('.post-type-fts #fts-import-export-feed-options-side-mb').length ) {
            const buttonClick = false;
            import_export_ajax_content(buttonClick);
        }
    });

    function fts_beacon_support_auto_fill_json_options( response ){

        //console.log( JSON.parse( response ) );

        var data = JSON.parse( response );

        const systemInfo = data.system_info ?? '';
        const importExportOptions = data.feed_options ?? '';

        Beacon('prefill', {
            // Uncomment to test fields quickly.
            /* name: 'Spencer Labadie',
             email: 'spencer@test.com',
             subject: 'Testing System Info & JSON Feed Options Auto Fill',
             text: 'Testy Test was quite the Tester',*/
            fields: [
                {
                    id: 42859, //  field ID. System Info
                    value: systemInfo,
                },
                {
                    id: 42860, // field ID. Feed Options JSON
                    value: importExportOptions,
                },
            ],
        })
    }
}