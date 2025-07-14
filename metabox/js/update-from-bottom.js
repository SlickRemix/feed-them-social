// Made this into a function because there are 2 states.
// 1: When the page loads and instagram is the saved option.
// 2: When an item in the .fts-select-social-network-menu has been clicked on.
// So we check to make sure the instagram option has an active state and if so do stuff.
function fts_instagram_basic_business_buttons() {

    if( 'combine-streams-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.combine-instagram-access-token-placeholder').prepend('<div class="fts-instagram-basic-business-wrap">' +
            '<div class="fts-combine-instagram-basic-token-button" data-fts-feed-type="instagram-feed-type">Instagram Basic<br/><small>Your Account on Instagram</small><div class="fts-instagram-down-arrow fts-instagram-basic-down-arrow"></div></div>' +
            '<div class="fts-combine-instagram-business-token-button" data-fts-feed-type="instagram-business-feed-type">Instagram Business<br/><small>Your Account on Facebook</small><div class="fts-instagram-down-arrow fts-instagram-business-arrow"></div></div>' +
            '</div><div class="fts-clear"></div>');
    }
    else {
        jQuery('.fts-select-social-network-menu').append('<div class="fts-instagram-basic-business-wrap">' +
            '<div id="fts-instagram-basic-token-button" class="fts-instagram-basic-token-button" data-fts-feed-type="instagram-feed-type">Instagram<br/><small>Your Account on Instagram</small><div class="fts-instagram-down-arrow fts-instagram-basic-down-arrow"></div></div>' +
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
                ftsAccessTokenTypeAjax(fts_type, cpt_id, false);

            }

        }
        return false;
    });
}

// Use our buttons to change our #feed_type select option.
function fts_combine_social_icons_wrap_click() {
    jQuery( '.fts-combine-instagram-basic-token-button,' +
        '.fts-combine-instagram-business-token-button').click( function () {

        let fts_type = jQuery( this ).data( 'fts-feed-type' );
        // todo SRL: line below needs to be re-written to be more understandable but for launch sake we are rolling with it. Mainly not fixing now because for ease of backward compatibility too.
        let fts_type_value = 'instagram-feed-type' === fts_type ? 'basic' : 'business';
        const url_string = window.location.href;
        const url = new URL( url_string );
        const cpt_id = url.searchParams.get( 'post' );
        jQuery('#combine_instagram_type').val( fts_type_value ).trigger( 'change' ).attr( 'selected', 'selected' );
        jQuery(this).addClass( 'fts-social-icon-wrap-active' );

        // Load up the proper access token inputs.
        ftsAccessTokenTypeAjax( fts_type, cpt_id, fts_type_value );

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
                    ftsAccessTokenTypeAjax('instagram-feed-type', cpt_id, 'basic');
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
                ftsAccessTokenTypeAjax('facebook-feed-type', cpt_id, 'combined-facebook');
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
                ftsAccessTokenTypeAjax('twitter-feed-type', cpt_id, 'combined-twitter');
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
                ftsAccessTokenTypeAjax('youtube-feed-type', cpt_id, 'combined-youtube');
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
function ftsAccessTokenTypeAjax( feed_type, cpt_id, combined ) {

    const combined_check = false !== combined ? combined : false;

    jQuery.ajax({
        data: {
            action: 'ftsAccessTokenTypeAjax',
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
                combineJs();
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

    jQuery('.fts-token-wrap h3, .fts-token-wrap span, .fts-settings-does-not-work-wrap .fts-admin-token-settings').click(function () {

        jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
        jQuery( this ).toggleClass( 'fts-feed-type-active' );
        jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
        jQuery( '.fts-token-wrap h3' ).toggleClass( 'fts-admin-token-settings-open' );

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

        }, 100);

        // This is for the select a social network tab and controls what tab is selected and visible in the tabs menu,
        $( '#feed_type' ).on(
            'change',
            function (e) {
                // Grab the url so we can do stuff.
                const url_string = window.location.href;
                let url = new URL( url_string );
                let cpt_id = url.searchParams.get("post");
                // Leaving this to test for later let feed_type = url.searchParams.get("feed_type");
                console.log( cpt_id );

                let ftsGlobalValue = jQuery( this ).val();

                // Use for testing console.log(ftgGlobalValue);
                // I know we can figure a way to condense this but for time sake just rolling with this.
                if ( 'facebook-feed-type' === ftsGlobalValue ) {
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

                if ( 'instagram-feed-type' === ftsGlobalValue || 'instagram-business-feed-type' === ftsGlobalValue ) {
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

                if ( 'twitter-feed-type' === ftsGlobalValue ) {
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

                if ( 'youtube-feed-type' === ftsGlobalValue ) {
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
                if ( 'combine-streams-feed-type' === ftsGlobalValue ) {
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
        const fts_sub_tabs = '<div class="fts-feed-settings-tabs-wrap"><div class="fts-feed-tab fts-sub-tab-active"><span>'+ updatefrombottomParams.mainoptions + '</span></div><div class="fts-settings-tab"><span>'+ updatefrombottomParams.additionaloptions + '</span></div></div>';
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
                else if( $( this ).hasClass( 'fts-settings-tab' ) ){

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

        // Display additional Settings link.
        $('<div class="fts-note fts-note-footer">' + updatefrombottomParams.additionalSettings + '</div>').appendTo(".tab-content");

        // Button markup depending on post/page status
        if($('#publish, #publishing-action input[type=submit]').val() == updatefrombottomParams.publish) {
            $('<div class="updatefrombottom" ><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.publish+'</a></div>').appendTo(".tab-content");
        } else {
            $('<div class="updatefrombottom"><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.update+'</a></div>').appendTo(".tab-content");
        }

        // DOM Caching
        let elements =  {
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
            let elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();

            let viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();

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
        $('#fts-import-feed-options').click(function (event) {
            event.preventDefault();

            // Grab the url so we can do stuff.
            const url_string = window.location.href;
            const url = new URL(url_string);
            const cpt_id = url.searchParams.get("post");
            const cpt_import_val = $('#fts-import-export-feed-options-side-mb .fts-import-feed-widget-wrap input').val();

            try {
                // Make sure this is valid JSON before proceeding.
                JSON.parse(cpt_import_val);
                console.log('JSON is valid');
            } catch (e) {
                alert('JSON is not valid');
                console.log('JSON is not valid: ' + e);
                return;
            }

            jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-saving-form'></div></div></div>");
            jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
            jQuery('#publishing-action .spinner').css("visibility", "visible");

            jQuery.ajax({
                data: {
                    action: "ftsImportFeedOptionsAjax",
                    cpt_id: cpt_id,
                    cpt_import: cpt_import_val,
                    _wpnonce: ftg_mb_tabs.ajaxImportFeedOptionsNonce
                },
                type: 'POST',
                url: ftsAjax.ajaxurl,
                success: function (response) {
                    console.log('Well Done and got this from sever: ' + response);
                    $('#fts-import-export-feed-options-side-mb .fts-import-feed-widget-wrap input').val('');
                    $('#fts-import-export-feed-options-side-mb .fts-import-feed-widget-wrap .publishing-action').append('<div class="fts-import-feed-success">Import Success</div>');

                    location.reload();
                    // 3. The 'return false' here was not necessary.
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // It's good practice to handle potential AJAX errors.
                    console.error('AJAX Error: ' + textStatus, errorThrown);
                    alert('An error occurred while importing. Please try again.');
                }
            }); // end of ajax()
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
    const url_string = window.location.href;
    const url = new URL( url_string );
    const cpt_id = url.searchParams.get("post");

    jQuery.ajax({
        data: {
            action: "ftsExportFeedOptionsAjax",
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
            let e = t.getElementsByTagName("script")[0], n = t.createElement("script");
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

        //Use this for testing console.log( JSON.parse( response ) );

        let data = JSON.parse( response );

        const systemInfo = data.system_info ?? '';
        const importExportOptions = data.feed_options ?? '';

        Beacon('prefill', {
            // Uncomment to test fields quickly.
            // name: 'Spencer Labadie',
            // email: 'spencer@test.com',
            // subject: 'Testing System Info & JSON Feed Options Auto Fill',
            // text: 'Testy Test was quite the Tester',
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