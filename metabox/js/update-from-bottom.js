// Made this into a function because there are 2 states.
// 1: When the page loads and instagram is the saved option.
// 2: When an item in the .fts-select-social-network-menu has been clicked on.
// So we check to make sure the instagram option has an active state and if so do stuff.
function fts_instagram_basic_business_buttons() {

    jQuery('.fts-select-social-network-menu').append('<div class="fts-instagram-basic-business-wrap">' +
        '<div class="fts-instagram-basic-token-button" data-fts-feed-type="instagram-feed-type">Instagram Basic<br/><small>Your Personal Account</small><div class="fts-instagram-down-arrow fts-instagram-basic-down-arrow"></div></div>' +
        '<div class="fts-instagram-business-token-button" data-fts-feed-type="instagram-business-feed-type">Instagram Business<br/><small>Account connected to Facebook</small><div class="fts-instagram-down-arrow fts-instagram-business-arrow"></div></div>' +
        '</div>');

    if( 'instagram-business-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.fts-instagram-business-token-button').addClass('fts-social-icon-wrap-active');
    }
    else if( 'instagram-feed-type' === jQuery("#feed_type option:selected").val() ){
        jQuery('.fts-instagram-basic-token-button').addClass('fts-social-icon-wrap-active');
    }

}

// Use our buttons to change our #feed_type select option.
function fts_social_icons_wrap_click() {
    jQuery('.fts-social-icon-wrap, .fts-instagram-basic-token-button, .fts-instagram-business-token-button').click(function () {

            // Don't repeat the process if the user clicks and active icon.
            if( !jQuery( this ).hasClass('fts-social-icon-wrap-active') ) {
                const url_string = window.location.href;
                const url = new URL(url_string);
                const cpt_id = url.searchParams.get("post");
                const fts_type = jQuery(this).data('fts-feed-type');
                jQuery('#feed_type').val(fts_type).trigger('change').attr('selected', 'selected');
                jQuery('.fts-social-icon-wrap').removeClass('fts-social-icon-wrap-active');
                jQuery(this).addClass('fts-social-icon-wrap-active');

                // Load up the proper access token inputs.
                fts_access_token_type_ajax(fts_type, cpt_id);
            }
        return false;
   });
}

function fts_access_token_type_ajax( feed_type, cpt_id ) {

    jQuery.ajax({
        data: {
            action: 'fts_access_token_type_ajax',
            cpt_id: cpt_id,
            feed_type: feed_type,
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function ( response ) {

            jQuery('.fts-access-token').hide();
            jQuery('.fts-access-token').html( response );
            jQuery('.fts-access-token').fadeIn();
            // ok next thing I need to do here is add a class to the main wrapper that lets me know when social icon is clicked which option to have active
            // either the instagram basic or the business tab.

            // This is here to remove the instagram basic and business buttons when
            // the user clicks on any other button other than instagram to get an access token.
            if( 'instagram-feed-type' !== feed_type && 'instagram-business-feed-type' !== feed_type ) {

                jQuery('.fts-instagram-basic-business-wrap').remove();
                jQuery('.instagram-feed-type').removeClass('fts-instagram-sub-menu-active');
            }
            else {
                jQuery('.instagram-feed-type').addClass('fts-instagram-sub-menu-active');
                jQuery('.fts-instagram-basic-business-wrap').remove();
                fts_instagram_basic_business_buttons();
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
    jQuery('#fts-feed-type h3, #fts-feed-type span').click(function () {
        jQuery(".fts-token-wrap .feed_them_social-admin-input-label, .fts-token-wrap input").toggle();
        jQuery( this ).toggleClass( 'fts-feed-type-active' );
    });
}

function fts_check_valid() {
    if( jQuery('.fts-success-token-content').length ){
        jQuery('#fts-feed-type h3').append(' Valid').addClass('fts-active-success-token');
        jQuery('#fts-feed-type h3').css('color', '#1aae1f');
    }
}

(function ($) {
    "use strict";
    $( document ).ready(function() {

        setTimeout(function () {

            if( !jQuery('div').hasClass('fts-instagram-basic-business-wrap') && jQuery('.fts-social-icon-wrap-active').hasClass('instagram-feed-type') ){
                fts_instagram_basic_business_buttons();
            }
            fts_reload_toggle_click();
            fts_check_valid();
            fts_social_icons_wrap_click();

        }, 0);

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
                    jQuery( '.tab4' ).addClass( 'fts-facebook-waiting-color' );
                    jQuery( '.tab4 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab4 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.facebook-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab4' ).removeClass( 'fts-facebook-waiting-color' );
                    jQuery( '.tab4 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab4 a .fts-click-cover' ).show();
                }

                if ( 'instagram-feed-type' == ftsGlobalValue || 'instagram-business-feed-type' == ftsGlobalValue ) {
                    jQuery( '.tab5' ).addClass( 'fts-instagram-waiting-color' );
                    jQuery( '.tab5 a' ).css( { 'pointer-events' : 'all' } );
                    jQuery( '.tab5 a .fts-click-cover' ).hide();
                    $( '.fts-social-icon-wrap.instagram-feed-type' ).addClass( 'fts-social-icon-wrap-active' );
                }
                else  {
                    jQuery( '.tab5' ).removeClass( 'fts-instagram-waiting-color' );
                    jQuery( '.tab5 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab5 a .fts-click-cover' ).show();
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
                }
                else  {
                    jQuery( '.tab8' ).removeClass( 'fts-combine-waiting-color' );
                    jQuery( '.tab8 a' ).attr( 'style',  'pointer-events: none !important' );
                    jQuery( '.tab8 a .fts-click-cover' ).show();
                }

                // LEAVING OFF HERE WITH THIS: NEED TO GET fts_check_valid TO ONLY WORK IF THE PAGE IS ACTIVE AND DB IS SAVED TO THIS FEED TYPE.
                // Might not need this anymore.
                if( $('.fts-social-icon-wrap-active .fts-success-token-content').length ){
                    fts_check_valid();
                }
            }
        ).change(); // The .change is so when the page loads it fires this on change event.
        // This way the tab will be active based on the save feed_type option.


        //This is for the sub nav tabs under each social network and controls what is visible.
        var fts_sub_tabs = '<div class="fts-feed-settings-tabs-wrap"><div class="fts-feed-tab fts-sub-tab-active">'+ updatefrombottomParams.mainoptions + '</div><div class="fts-settings-tab">'+ updatefrombottomParams.additionaloptions + '</div></div>';
        // $( fts_sub_tabs ).insertBefore( '.fts-facebook_page-shortcode-form .fts-cpt-main-options, .fts-instagram-shortcode-form .instagram-messages-selector, .fts-twitter-shortcode-form .twitter-messages-selector, .fts-youtube-shortcode-form .youtube-messages-selector, .fts-combine-streams-shortcode-form .combine_post_count' );
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
                /*$('.fts-feed-settings-tabs-wrap div').removeClass('fts-sub-tab-active');
                $(this).addClass('fts-sub-tab-active');
                if( $('.fts-settings-tab').hasClass('fts-sub-tab-active') ){
                    $('.fts-cpt-main-options').hide();
                    $('.fts-cpt-extra-options').show();
                }
                else {
                    $('.fts-cpt-extra-options').hide();
                    $('.fts-cpt-main-options').show();
                }*/
            }
        );
        // const name = "[fts_twitter twitter_name=gopro tweets_count=6 search=from:user_name%#YourHashtag twitter_height=240px cover_photo=yes stats_bar=yes show_retweets=yes show_replies=yes popup=yes loadmore=button loadmore_btn_margin='10px 5px 3px' loadmore_btn_maxwidth=20px loadmore_count=7 grid=yes colmn_width=23px space_between_posts='4px 10px']"
        // console.log( name.replace(/\'/g, '"').replace(/\s+(?=(?:[^"]*"[^"]*")*[^"]*"[^"]*$)/gm, '*').replace(/\"/g, "") );

        // Convert Old Shortcode click function
        $( '#fts-convert-old-shortcode' ).click(
            function () {

                console.log( 'Clicked Convert Shortcode Button' );

                window.location.hash.replace('#', '');

                let fts_shortcode = $( '#ft-galleries-old-shortcode-side-mb input' ).val();

                if( !fts_shortcode ){
                    alert( 'Please add a Feed Them Social shortode to convert.');
                    return;
                }
                var fts_shortcode_fix = fts_shortcode.replace(/\'/g, '"').replace(/\s+(?=(?:[^"]*"[^"]*")*[^"]*"[^"]*$)/gm, '*').replace(/\"/g, "");
                  // take shortcode and extract any spaces that suround a shortcode value. ie padding="20px 10px"

                var fts_final1 = fts_shortcode_fix.replace("fts_twitter", "").replace("[ ", "").replace("]", "").split( " " );
                var fts_final2 = Array.from( fts_final1 );
                let text = "";
                // text +=  item.substring(0, item.indexOf("=")) + '=' + item.substring(item.indexOf("=") + 1) + "<br>";

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
                                $( id + '#twitter-popup-option option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#twitter-grid-option option[value=yes]' ).attr('selected','selected');
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
                                $( id + '#twitter_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                            }
                            $( id + '#twitter_load_more_option option[value=yes]' ).attr('selected','selected');
                            $( id + '.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap' ).show();
                        }
                        else {
                            if( 'loadmore_btn_margin' == attribute ){
                                $( '#twitter_loadmore_button_margin' ).val( value );
                            }
                            else if ( 'loadmore_btn_maxwidth' == attribute ){
                                $( '#twitter_loadmore_button_width' ).val( value );
                            }
                            else if ( 'loadmore_count' == attribute ){
                                $( '#twitter_loadmore_count' ).val( value );
                            }
                            else if ( 'colmn_width' == attribute ){
                                $( '#twitter_grid_column_width' ).val( value );
                            }
                            else if ( 'space_between_posts' == attribute ){
                                $( '#twitter_grid_space_between_posts' ).val( value );
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
                                $( id + '#reviews_with_no_text option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'play_btn' == attribute ){
                                $( id + '#facebook_show_video_button option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'facebook_play_btn_visible' == attribute ){
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
                            $( id + '#reviews_with_no_text option[value=yes]' ).attr('selected','selected');
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
                                $( id + '#twitter-grid-option option[value=yes]' ).attr('selected','selected');
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
                                $( id + '#fts_youtube_force_columns option[value=yes]' ).attr('selected','selected');
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
                                $( '#fts-slicker-youtube-container-margin' ).val( value );
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

                        $( '#feed_type option[value=instagram-feed-type], #feed_type option[value=instagram-business-feed-type]' ).attr('selected','selected');

                        var id = '#ftg-tab-content5 ';

                        if( 'yes' == value ){
                            if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#twitter-grid-option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-twitter-grid-options-wrap' ).show();
                            }
                            else if( 'popup' == attribute ){
                                $( '#instagram_popup_option option[value=yes]' ).attr('selected','selected');
                            }
                            else if( 'hide_date_likes_comments' == attribute ){
                                $( '#fts-slicker-instagram-container-hide-date-likes-comments option[value=yes]' ).attr('selected','selected');
                            }
                            else if( 'force_columns' == attribute ){
                                $( '#fts_instagram_force_columns option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_wrap' == attribute ){
                                $( '#instagram-profile-wrap option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_photo' == attribute ){
                                $( '#instagram-profile-photo option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_stats' == attribute ){
                                $( '#instagram-profile-stats option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_name' == attribute ){
                                $( '#instagram-profile-name option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'profile_description' == attribute ){
                                $( '#instagram-profile-description option[value=yes]' ).attr('selected','selected');
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
                            else if ( 'hashtag' == attribute ){
                                $( '#instagram_hashtag' ).val( value );
                            }
                            else if( 'search' == attribute ){
                                $( '#instagram_hashtag_type' ).val( value );
                            }
                            else if( 'columns' == attribute ){
                                $( '#fts_instagram_columns' ).val( value );
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
                                $( id + '#combine_grid_option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-combine-grid-options-wrap' ).show();
                            }
                            else if( 'popup' == attribute ){
                                $( '#instagram_popup_option option[value=yes]' ).attr('selected','selected');
                            }
                        }
                        else {
                            if ( 'posts' == attribute ){
                                $( '#combine_post_count' ).val( value );
                            }
                            else if( 'facebook_name' == attribute ){
                                // alert('test');
                                $( id + '#combine_facebook option[value=yes]' ).attr('selected','selected');
                                $( id + '#combine_facebook_name' ).val( value );
                            }
                            else if( 'twitter_name' == attribute ){
                                $( id + '#combine_twitter option[value=yes]' ).attr('selected','selected');
                                $( id + '#combine_twitter_name' ).val( value );
                            }
                            else if( 'instagram_name' == attribute ){
                                $( id + '#combine_instagram option[value=yes]' ).attr('selected','selected');
                                $( id + '#combine_instagram_name' ).val( value );
                            }
                            else if( 'instagram_type' == attribute ){
                                $( id + '#combine_instagram_type' ).val( value );
                            }
                            else if( 'hashtag' == attribute ){
                                $( id + '#combine_instagram_hashtag' ).val( value );
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
                }
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

                $( "#publish" ).submit();


                alert( 'Success! Shortcode Converted. Double check your Access Token(s) are below in the Feed Setup menu tab.');
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

        $(window).on('resize scroll', function() {
            if ($('#publish, #publishing-action input[type=submit]').isInViewport()) {
                elements.box.hide();
            } else {
                // do something else
                elements.box.show();
            }
        });

    });
}(jQuery));