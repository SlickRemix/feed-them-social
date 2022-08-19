jQuery(document).ready(function() {

    // Run our funtion after the page has finished loading to retrieve our external urls meta tag details.
    fts_external_link_meta_content();

    jQuery('.fts-youtube-scrollable, .youtube-comments-wrap-premium, .youtube-comments-thumbs').hover(function() {
        jQuery("body").css("overflow","hidden");
    }, function() {
        jQuery("body").css("overflow","auto");
    });


    jQuery( document ).on( 'keydown', function ( e ) {
        if ( e.keyCode === 27 ) { // ESC
            jQuery( ".fts-youtube-scrollable" ).removeClass( "fts-scrollable-function" );
            jQuery('.youtube-comments-thumbs').hide();
            jQuery('.fts-youtube-scrollable, .fts-fb-autoscroll-loader').show();
            jQuery('.fts-youtube-thumbs-gallery-master .youtube-comments-thumbs').html('');
            slickremixImageResizing();
        }
    });

    jQuery.fn.ftsShare = function() {
        jQuery('.fts-share-wrap').each(function() {
            var $self = jQuery(this);
            //Share toolip function
            $self.find('.ft-gallery-link-popup').unbind().bind('click', function() {
                $self.find('.ft-gallery-share-wrap').toggle();
            });
        });
    };
    // return our share function after page has loaded to speed things up. Plus this way we can recall it in the loadmore areas of each feed instead of duplicating all the js.
    if (jQuery.fn.ftsShare) {
        jQuery.fn.ftsShare();
    }

    if (navigator.userAgent.indexOf("Firefox") > 0) {} else {
        jQuery(".fts-instagram-popup-half video, .fts-simple-fb-wrapper video, .fts-slicker-facebook-posts video, .fts-fluid-videoWrapper-html5 video").click(function() {
            jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")
        })
    }
    if (jQuery.fn.masonry) {
        jQuery(".fts-slicker-instagram").masonry({
            itemSelector: ".fts-masonry-option"
        })
    }
});
if (!jQuery.trim(jQuery('.fts-jal-fb-group-display').html()).length) {
    jQuery('.fts-jal-fb-group-display').append('<div class="fts-facebook-add-more-posts-notice"><p>Please go to the <strong>Facebook Options</strong> page of our plugin and look for the "<strong>Change Post Limit</strong>" option and add the number <strong>7</strong> or more. You can also hide this notice on the Facebook Options page if you want.</p>If you are trying to add a Personal Facebook feed and you are seeing this message too, please note: <strong>Personal Facebook Accounts generally do not work with our plugin.</strong></div>')
}
jQuery(window).on('load', function() {
    if (jQuery.fn.masonry) {
        setTimeout(function () {
            jQuery(".fts-pinterest-wrapper.masonry").masonry("layout");
        }, 200);
    }
});

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/

jQuery(document).ready(slickremixImageResizing);
jQuery(window).on('resize',slickremixImageResizing);

function slickremixImageResizing() {
    // This is the container for our instagram images
    var ftsBlockCenteredAttr = jQuery('.fts-instagram-inline-block-centered');
    // var ftsname = arguments["0"]
    //  var ftsBlockCenteredAttr = jQuery(ftsname);


    // alert(ftsBlockCenteredAttr);

    // This is the container for the instagram image post
    var ftsImageSize = jQuery('.slicker-instagram-placeholder');

    // How many colums do we want to show
    var ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns');
    // The margin in between photos so we can subtract that from the total %
    var ftsInstagramMargin = ftsBlockCenteredAttr.attr('data-ftsi-margin');
    // The margin without the px and we multiply it by 2 because the margin is on the left and right
    var ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 2;
    // Get the Instagram container .width() so we can keep track of the container size
    var ftsContainerWidth = ftsBlockCenteredAttr.width();
    // Force columns so the images to not scale up from 376px-736px.
    // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
    var ftsForceColumns = ftsBlockCenteredAttr.attr('data-ftsi-force-columns');
    // we or each option so if someone tries something other than that it will go to else statement
    if (ftsInstagramColumns === '1' ||
        ftsInstagramColumns === '2' ||
        ftsInstagramColumns === '3' ||
        ftsInstagramColumns === '4' ||
        ftsInstagramColumns === '5' ||
        ftsInstagramColumns === '6' ||
        ftsInstagramColumns === '7' ||
        ftsInstagramColumns === '8') {
        //   alert('wtf');
        // if the container is 376px or less we force the image size to be 100%
        if (ftsContainerWidth <= '376' && ftsForceColumns === 'no') {
            var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
        }
        // if the container is 736px or less we force the image size to be 50%
        else if (ftsContainerWidth <= '736' && ftsForceColumns === 'no') {
            var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
        }
        else {
            if (ftsInstagramColumns === '8') {
                var og_size = 'calc(12.5% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '7') {
                var og_size = 'calc(14.28571428571429% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '6') {
                var og_size = 'calc(16.66666666666667% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '5') {
                var og_size = 'calc(20% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '4') {
                var og_size = 'calc(25% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '3') {
                var og_size = 'calc(33.33333333333333% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '2') {
                var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '1') {
                var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
            }
        }

        ftsImageSize.css({'width': og_size});

        var ftsImageHeight = ftsImageSize.width();
        ftsImageSize.css({
            'width': og_size,
            'height': ftsImageHeight,
            'margin': ftsInstagramMargin
        });
    }
    else {
        var ftsImageWidth = ftsBlockCenteredAttr.attr('data-ftsi-width') ? ftsBlockCenteredAttr.attr('data-ftsi-width') : '325px';
        // alert(ftsImageSize.width())
        ftsImageSize.css({
            'width': ftsImageWidth,
            'height': ftsImageWidth,
            'margin': ftsInstagramMargin
        });
    }

    // If our image square is less than 180px then we hide the date, share option, hearts and comments count and icon and make the whole area clickable.
    if (ftsImageSize.width() < 180) {
        jQuery('.fts-instagram-inline-block-centered .slicker-date, .fts-instagram-inline-block-centered .fts-insta-likes-comments-grab-popup').hide();
        jQuery('.slicker-instagram-placeholder').addClass('fts-smallerthan-180');

    }
    else {
        jQuery('.fts-instagram-inline-block-centered .slicker-date, .fts-instagram-inline-block-centered .fts-insta-likes-comments-grab-popup').show();
        jQuery('.slicker-instagram-placeholder, .slicker-youtube-placeholder').removeClass('fts-smallerthan-180');

    }
}

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/

jQuery(document).ready(slickremixImageResizingFacebook, slickremixImageResizingFacebook2, slickremixImageResizingFacebook3);
jQuery(window).on('resize',slickremixImageResizingFacebook, slickremixImageResizingFacebook2, slickremixImageResizingFacebook3);

function slickremixImageResizingFacebook() {
    // This is the container for our instagram images
    var ftsBlockCenteredAttr = jQuery('.fts-facebook-inline-block-centered');
    // var ftsname = arguments["0"]
    //  var ftsBlockCenteredAttr = jQuery(ftsname);


    // alert(ftsBlockCenteredAttr);

    // This is the container for the instagram image post
    var ftsImageSize = jQuery('.slicker-facebook-placeholder');

    // How many colums do we want to show
    var ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns');
    // The margin in between photos so we can subtract that from the total %
    var ftsInstagramMargin = ftsBlockCenteredAttr.attr('data-ftsi-margin');
    // The margin without the px and we multiply it by 2 because the margin is on the left and right
    var ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 1;
    // Get the Instagram container .width() so we can keep track of the container size
    var ftsContainerWidth = ftsBlockCenteredAttr.width();
    // Force columns so the images to not scale up from 376px-736px.
    // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
    var ftsForceColumns = 'yes';
    // we or each option so if someone tries something other than that it will go to else statement
    if (ftsInstagramColumns === '2' ||
        ftsInstagramColumns === '3') {

        if (ftsInstagramColumns === '3') {
            var og_size = 'calc(33.0777777% - ' + ftsInstagramMarginfinal + 'px)';
        }
        else if (ftsInstagramColumns === '2') {
            var og_size = 'calc(49.777777% - ' + ftsInstagramMarginfinal + 'px)';
        }


        ftsImageSize.css({'width': og_size});

        var ftsImageHeight = ftsImageSize.width();
        ftsImageSize.css({
            'width': og_size,
            'height': ftsImageHeight,
            'margin': ftsInstagramMargin
        });
    }
    else {
        var ftsImageWidth = ftsBlockCenteredAttr.attr('data-ftsi-width') ? ftsBlockCenteredAttr.attr('data-ftsi-width') : '325px';
        // alert(ftsImageSize.width())
        ftsImageSize.css({
            'width': ftsImageWidth,
            'height': ftsImageWidth,
            'margin': ftsInstagramMargin
        });
    }

    // If our image square is less than 180px then we hide the date, share option, hearts and comments count and icon and make the whole area clickable.
    if (ftsImageSize.width() < 180) {
        jQuery('.fts-facebook-inline-block-centered .slicker-date, .fts-facebook-inline-block-centered .fts-insta-likes-comments-grab-popup').hide();
        jQuery('.slicker-facebook-placeholder').addClass('fts-smallerthan-180');

    }
    else {
        jQuery('.fts-facebook-inline-block-centered .slicker-date, .fts-facebook-inline-block-centered .fts-insta-likes-comments-grab-popup').show();
        jQuery('.slicker-facebook-placeholder, .slicker-youtube-placeholder').removeClass('fts-smallerthan-180');

    }
}

function slickremixImageResizingFacebook2() {
    var e = jQuery(".fts-more-photos-2-or-3-photos a"),
        t = "calc(49.88888888% - 1px)";
    e.css({
        width: t
    });
    var s = e.width();
    e.css({
        width: t,
        height: s,
        margin: "1px"
    })
}

function slickremixImageResizingFacebook3() {
    var e = jQuery(".fts-more-photos-4-photos a"),
        t = "calc(33.192222222% - 1px)";
    e.css({
        width: t
    });
    var s = e.width();
    e.css({
        width: t,
        height: s,
        margin: "1px"
    })
}

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/

jQuery(document).ready(slickremixImageResizingYouTube);
jQuery(window).on('resize',slickremixImageResizingYouTube);
function slickremixImageResizingYouTube() {
    // This is the container for our instagram images
    var ftsBlockCenteredAttr = jQuery('.fts-youtube-inline-block-centered');

    // This is the container for the instagram image post
    var ftsYoutubeImageSize = jQuery('.slicker-youtube-placeholder');
    var ftsYoutubeThumbsContainer = jQuery('.fts-youtube-popup-gallery');

    var ftsYoutubeLarge = jQuery('.fts-yt-large');
    var ftsYoutubeThumbsWrap = jQuery('.fts-youtube-scrollable.fts-youtube-thumbs-wrap, .fts-youtube-scrollable.fts-youtube-thumbs-wrap-left, .youtube-comments-wrap-premium, .youtube-comments-wrap.fts-youtube-thumbs-wrap-right, .youtube-comments-wrap.fts-youtube-thumbs-wrap-left');

    // How many colums do we want to show
    var ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns');
    // The margin in between photos so we can subtract that from the total %
    var ftsInstagramMargin = ftsBlockCenteredAttr.attr('data-ftsi-margin');
    // The margin without the px and we multiply it by 2 because the margin is on the left and right
    var ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 2;
    // Get the Instagram container .width() so we can keep track of the container size
    var ftsContainerWidth = ftsBlockCenteredAttr.width();
    // Force columns so the images to not scale up from 376px-736px.
    // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
    var ftsForceColumns = ftsBlockCenteredAttr.attr('data-ftsi-force-columns');
    // we or each option so if someone tries something other than that it will go to else statement
    if (ftsInstagramColumns === '1' ||
        ftsInstagramColumns === '2' ||
        ftsInstagramColumns === '3' ||
        ftsInstagramColumns === '4' ||
        ftsInstagramColumns === '5' ||
        ftsInstagramColumns === '6') {
        //   alert('wtf');
        // if the container is 376px or less we force the image size to be 100%
        if (ftsContainerWidth <= '376' && ftsForceColumns === 'no') {
            var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
        }
        // if the container is 736px or less we force the image size to be 50%
        else if (ftsContainerWidth <= '736' && ftsForceColumns === 'no') {
            var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
        }
        else {
            if (ftsInstagramColumns === '6') {
                var og_size = 'calc(16.66666666666667% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '5') {
                var og_size = 'calc(20% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '4') {
                var og_size = 'calc(25% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '3') {
                var og_size = 'calc(33.33333333333333% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '2') {
                var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '1') {
                var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
            }
        }
        var ftsYoutubeLargeHeight = ftsYoutubeLarge.height();
        var ftsYoutubeLargeHeightFinal = 'calc(100% - ' + ftsYoutubeLargeHeight + 'px)';

        ftsYoutubeThumbsWrap.css({'height': ftsYoutubeLargeHeight + 'px'});

        ftsYoutubeImageSize.css({'width': og_size});

        ftsYoutubeThumbsContainer.css({
            'padding': ftsInstagramMargin
        });
        var ftsImageHeightYoutube = ftsYoutubeImageSize.width() - '150';
        ftsYoutubeImageSize.css({
            'width': og_size,
            'height': ftsImageHeightYoutube,
            'margin': ftsInstagramMargin
        });
    }

    // If our image square is less than 180px then we hide the play button for the youtube feed
    if (ftsYoutubeImageSize.width() < 180) {
        jQuery('.slicker-youtube-placeholder').addClass('fts-youtube-smallerthan-180');

        jQuery('.fts-yt-large, .fts-youtube-scrollable').css('width', '100% !important');

    }
    else {
        jQuery('.slicker-youtube-placeholder').removeClass('fts-youtube-smallerthan-180');

    }

    var ftsYoutubeContainer = jQuery('.fts-master-youtube-wrap');
    // If our image square is less than 180px then we hide the play button for the youtube feed
    if (ftsYoutubeContainer.width() < 550) {
        jQuery('.fts-yt-large, .fts-youtube-scrollable, .youtube-comments-wrap').addClass('fts-youtube-smallerthan-550-stack');

    }
    else {
        jQuery('.fts-yt-large, .fts-youtube-scrollable, .youtube-comments-wrap').removeClass('fts-youtube-smallerthan-550-stack');

    }
}

// Check each post for an external link and if so then run our function to get the image, title and
// description from the website and return it and format it nicely.
function fts_external_link_meta_content () {

    jQuery('.fts-tweeter-wrap').each(function () {

        var fts_url_wrap = jQuery( this ).find( '.fts-twitter-external-url-wrap' );

        if ( fts_url_wrap.length > 0 ) {

            // alert( fts_url_wrap );
            var fts_security = fts_url_wrap.attr('data-twitter-security');
            var fts_time = fts_url_wrap.attr('data-twitter-time');

            var fts_url = fts_url_wrap.attr('data-twitter-url');
            var fts_image_exists = fts_url_wrap.attr('data-image-exists-check');
            var fts_no_video_image = fts_url_wrap.attr('data-no-video-image-check');
            var fts_popup = fts_url_wrap.attr('data-twitter-popup');

            console.log('url: ' + fts_url + ' Image exists: ' + fts_image_exists + ' No video image exists: ' + fts_no_video_image);

            jQuery.ajax({
                    data: {
                        action: "fts_twitter_share_url_check",
                        fts_security: fts_security,
                        fts_time: fts_time,
                        fts_url: fts_url,
                        fts_image_exists: fts_image_exists,
                        fts_no_video_image: fts_no_video_image,
                        fts_popup: fts_popup,
                    },
                    type: 'POST',
                    url: fts_twitter_ajax.ajax_url,
                    success: function (data) {
                        fts_twitter = data;
                        fts_url_wrap.removeAttr( 'class data-twitter-security data-twitter-time' );

                        console.log("FTS Twitter external link success");
                        // console.log( data );

                        if( 'missing_info' === data ){
                            // Add a Error message to the attr data-error on the div. This way if people ask why the extra info is not
                            // showing we can look at the div and see if there is an error message :).
                            jQuery(fts_url_wrap).attr( 'data-error', 'Do not return any content, image, title or description missing' ).hide();
                        }
                        else {
                            jQuery(fts_url_wrap).html( data );
                        }

                        // Must be second to last so we can adjust the height of the image to the container if larger than our css min height.
                        ftsRetweetHeight();

                        // Lastly check to see if masonry is a function before we reloadItems and Layout.
                        // We need to do this when the grid is being used because the content takes a moment
                        // to load and sometimes the grid layout can overlap making the feed look like dukey.
                        // fts-slicker-twitter-posts is the class applied when grd=yes so we know masonry is loading.
                        if ( jQuery(".fts-slicker-twitter-posts")[0] ) {
                            jQuery(".fts-slicker-twitter-posts").masonry("reloadItems");
                            setTimeout(function () {
                                jQuery(".fts-slicker-twitter-posts").masonry("layout");
                            }, 500);
                        }
                    },
                    error: function (data, status, error) {
                        console.log(data);
                        console.log("AJAX errors: " + error);
                    },
                }
            );
        }
    });

    return true;
}

// Find the height of the external link text wrapper and adjust the background image height
// so it always matches and the image and fits perfectly all the time.
function ftsRetweetHeight() {
    if( jQuery('div').hasClass( 'fts-tweeter-wrap' ) ) {

        var twitter_wrap = jQuery('.fts-tweeter-wrap');
        if( '475' < twitter_wrap.width() ) {
            console.log( 'Wrap width: ' + twitter_wrap.width() );
            jQuery( '.fts-twitter-div' ).addClass( 'fts-twitter-wrap-below-width-450' );
            jQuery( 'span.fts-twitter-external-backg-image' ).css({ 'background-size' : 'cover' } );


        }
        else {
            jQuery( '.fts-twitter-div' ).removeClass( 'fts-twitter-wrap-below-width-450' );
            jQuery( 'span.fts-twitter-external-backg-image' ).css({ 'background-size' : '0' } );
        }

        jQuery('.fts-twitter-quoted-text').each(function () {
            var retweet_height = jQuery(this).height() + 20;
            jQuery(this).parent().find('.fts-twitter-external-backg-image').css({'height': retweet_height + 'px'});
            //alert( jQuery(this).find( '.fts-twitter-quoted-text' ).height() );
        });
    }
}
// Return our ftsRetweetHeight function after page has loaded to speed things up. Plus this way we can recall it in the loadmore areas of each feed instead of duplicating all the js.
jQuery(document).ready(ftsRetweetHeight);
jQuery(window).on('resize', ftsRetweetHeight);
