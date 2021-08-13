(function ($) {
    "use strict";

    $( document ).ready(function() {

       // const name = "[fts_twitter twitter_name=gopro tweets_count=6 search=from:user_name%#YourHashtag twitter_height=240px cover_photo=yes stats_bar=yes show_retweets=yes show_replies=yes popup=yes loadmore=button loadmore_btn_margin='10px 5px 3px' loadmore_btn_maxwidth=20px loadmore_count=7 grid=yes colmn_width=23px space_between_posts='4px 10px']"
       // console.log( name.replace(/\'/g, '"').replace(/\s+(?=(?:[^"]*"[^"]*")*[^"]*"[^"]*$)/gm, '*').replace(/\"/g, "") );

        $( '#fts-convert-old-shortcode' ).click(
            function () {

                let fts_shortcode = $( '#ft-galleries-old-shortcode-side-mb input' ).val();
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
                            $( id + '.twitter-hashtag-etc-wrap' ).show();
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

                        var id = '#ftg-tab-content6 ';

                        if ( 'type' == attribute ) {
                            $( id + '#facebook-messages-selector option[value='+ value +']' ).attr('selected','selected');
                        }
                        else if (  'id' == attribute ) {
                            $( id + '#facebook-messages-selector option[value=page]' ).attr('selected','selected');
                            // $( id + '.twitter-hashtag-etc-wrap' ).show();
                            $( '#fb_page_id' ).val( value );
                        }
                        else if ( 'posts_displayed' == attribute ){
                            if ( 'page_and_others' == value ){
                                $( id + '#fb_page_posts_displayed option[value=page_and_others]' ).attr('selected','selected');
                            }
                            else {
                                $( id + '#fb_page_posts_displayed option[value=page_only]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'posts' == attribute ){
                            $( '#fb_page_post_count' ).val( value );
                        }
                        else if( 'yes' == value ){
                            if ( 'popup' == attribute ){
                                //alert( 'test' );
                                $( id + '#facebook_popup option[value=yes]' ).attr('selected','selected');
                                $( id + '.display-comments-wrap' ).show();
                            }
                            else if ( 'grid' == attribute ){
                                //alert( 'test' );
                                $( id + '#fb-grid-option option[value=yes]' ).attr('selected','selected');
                                $( id + '.fts-facebook-grid-options-wrap' ).show();
                            }
                            else if ( 'hide_comments_popup' == attribute ){
                                //alert( 'test' );
                                $( id + '#facebook_popup_comments option[value=yes]' ).attr('selected','selected');
                            }
                            else if ( 'hide_date_likes_comments' == attribute ){
                                $( id + '#fts-slicker-facebook-container-hide-date-likes-comments option[value=yes]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'height' == attribute ){
                            $( id + '#facebook_page_height' ).val( value );
                        }
                        else if ( 'title' == attribute && 'no' == value ){
                            $( id + '#fb_page_title_option option[value=no]' ).attr('selected','selected');
                        }
                        else if ( 'description' == attribute && 'no' == value ){
                            $( id + '#fb_page_description_option option[value=no]' ).attr('selected','selected');
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
                            $( id + '#fb_hide_like_box_button option[value=no]' ).attr('selected','selected');
                            $( id + '.like-box-wrap' ).show();
                        }
                        else if ( 'words' == attribute ){
                            $( id + '#fb_page_word_count_option' ).val( value );
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
                            $( id + '#fb_album_id' ).val( value );
                        }
                        else if ( 'image_width' == attribute ){
                            $( id + '#fts-slicker-facebook-container-image-width' ).val( value );
                        }
                        else if ( 'image_height' == attribute ){
                            $( id + '#fts-slicker-facebook-container-image-height' ).val( value );
                        }
                        else if ( 'space_between_photos' == attribute ){
                            $( id + '#fts-slicker-facebook-container-margin' ).val( value );
                        }

                        else if ( 'center_container' == attribute && 'yes' == value  ){
                            $( id + '#fts-slicker-facebook-container-position option[value=yes]' ).attr('selected','selected');
                        }

                        else if (  'show_follow_btn_where' == attribute ) {
                            if ( 'above_title' == value ){
                                $( id + '#fb_position_likebox option[value=above_title]' ).attr('selected','selected');
                            }
                            else if ( 'bottom' == value ) {
                                $( id + '#fb_position_likebox option[value=bottom]' ).attr('selected','selected');
                            }
                            else if ( 'below_title' == value ) {
                                $( id + '#fb_position_likebox option[value=below_title]' ).attr('selected','selected');
                            }

                        }
                        else if (  'like_option_align' == attribute ) {
                            if ( 'left' == value ){
                                $( id + '#fb_align_likebox option[value=left]' ).attr('selected','selected');
                            }
                            else if ( 'center' == value ) {
                                $( id + '#fb_align_likebox option[value=center]' ).attr('selected','selected');
                            }
                            else if ( 'right' == value ) {
                                $( id + '#fb_align_likebox option[value=right]' ).attr('selected','selected');
                            }
                        }
                        else if ( 'loadmore' == attribute ){
                            // alert( 'test' );
                            if ( 'autoscroll' == value ){
                                $( id + '#fb_load_more_style option[value=autoscroll]' ).attr('selected','selected');
                            }
                            $( id + '#fb_load_more_option option[value=yes]' ).attr('selected','selected');
                            $( id + '.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap' ).show();
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
                }
            }
        );


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