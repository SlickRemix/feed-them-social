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