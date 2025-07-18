jQuery(document).ready(function($) {

    jQuery('.fts-youtube-scrollable, .youtube-comments-wrap-premium, .youtube-comments-thumbs').hover(function () {
        jQuery("body").css("overflow", "hidden");
    }, function () {
        jQuery("body").css("overflow", "auto");
    });

    jQuery(document).on('keydown', function (e) {
        if (e.keyCode === 27) { // ESC
            jQuery(".fts-youtube-scrollable").removeClass("fts-scrollable-function");
            jQuery('.youtube-comments-thumbs').hide();
            jQuery('.fts-youtube-scrollable, .fts-fb-autoscroll-loader').show();
            jQuery('.fts-youtube-thumbs-gallery-master .youtube-comments-thumbs').html('');
            slickremixImageResizing();
        }
    });

    if (navigator.userAgent.indexOf("Firefox") > 0) {} else {
        jQuery(".fts-instagram-popup-half video, .fts-simple-fb-wrapper video, .fts-slicker-facebook-posts video, .fts-fluid-videoWrapper-html5 video").click(function() {
            jQuery(this).trigger(this.paused ? "play" : "pause");
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

function ftsShare(){

    jQuery('.fts-share-wrap').each(function () {
        let $self = jQuery(this);
        //Share tooltip function
        $self.find('.ft-gallery-link-popup').unbind().bind('click', function () {
            $self.find('.ft-gallery-share-wrap').toggle();
        });
    });
}
// return our share function after page has loaded to speed things up. Plus this way we can recall it in the loadmore areas of each feed instead of duplicating all the js.
jQuery(document).ready(ftsShare);

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/

// commenting this out because it needs to load at the bottom of the instagram feed for Elementor Preview
// And some types of tabs that load with js.
// jQuery(document).ready(slickremixImageResizing);
jQuery(window).on('resize',slickremixImageResizing);

function slickremixImageResizing() {

    jQuery('.fts-instagram-inline-block-centered').each(function () {
        // This is the container for our instagram images
        const ftsBlockCenteredAttr = jQuery(this).attr('id');
        let uniqueID = jQuery('#' + ftsBlockCenteredAttr);
        // Get the Instagram container .width() so we can keep track of the container size
        let ftsContainerWidth = uniqueID.width();

        // This is the container for the instagram image post
        let ftsImageSize = jQuery('#' + ftsBlockCenteredAttr + ' .slicker-instagram-placeholder');

        // How many columns do we want to show
        let ftsInstagramColumns;

        if (ftsContainerWidth <= '376' && uniqueID.attr('data-ftsi-columns-mobile') !== undefined) {
            ftsInstagramColumns = uniqueID.attr('data-ftsi-columns-mobile');
        }
        // if the container is 736px or less we force the image size to be 50%
        else if (ftsContainerWidth <= '736' && uniqueID.attr('data-ftsi-columns-tablet') !== undefined) {
            ftsInstagramColumns = uniqueID.attr('data-ftsi-columns-tablet');
        } else {
            ftsInstagramColumns = uniqueID.attr('data-ftsi-columns');
        }

        // For Instagram & TikTok lets let the user choose the height of the photo holder
        let ftsInstagramHeight = uniqueID.attr('data-ftsi-height') && uniqueID.attr('data-ftsi-height') !== '' ? uniqueID.attr('data-ftsi-height') : 0;
        // The margin in between photos so we can subtract that from the total %
        let ftsInstagramMargin = uniqueID.attr('data-ftsi-margin');
        // The margin without the px and we multiply it by 2 because the margin is on the left and right
        let ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 2;
        // Force columns so the images to not scale up from 376px-736px.
        // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
        // commenting out for now since we are introducing the responsive modes for desktop, tablet and mobile.
        // let ftsForceColumns = ftsBlockCenteredAttr.attr('data-ftsi-force-columns');
        // we or each option so if someone tries something other than that it will go to else statement
        let og_size;
        if (ftsInstagramColumns === '1' ||
            ftsInstagramColumns === '2' ||
            ftsInstagramColumns === '3' ||
            ftsInstagramColumns === '4' ||
            ftsInstagramColumns === '5' ||
            ftsInstagramColumns === '6' ||
            ftsInstagramColumns === '7' ||
            ftsInstagramColumns === '8') {

            if (ftsInstagramColumns === '8') {
                og_size = 'calc(12.5% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '7') {
                og_size = 'calc(14.28571428571429% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '6') {
                og_size = 'calc(16.66666666666667% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '5') {
                og_size = 'calc(20% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '4') {
                og_size = 'calc(25% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '3') {
                og_size = 'calc(33.33333333333333% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '2') {
                og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '1') {
                og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
            }

            ftsImageSize.css({'width': og_size});

            const ftsImageHeight = ftsImageSize.width() + parseFloat(ftsInstagramHeight);
            ftsImageSize.css({
                'width': og_size,
                'height': ftsImageHeight,
                'margin': ftsInstagramMargin
            });
        } else {
            const ftsImageWidth = uniqueID.attr('data-ftsi-width') ? uniqueID.attr('data-ftsi-width') : '325px';
            // alert(ftsImageSize.width());
            ftsImageSize.css({
                'width': ftsImageWidth,
                'height': ftsImageWidth,
                'margin': ftsInstagramMargin
            });
        }

        // If our image square is less than 180px then we hide the date, share option, hearts and comments count and icon and make the whole area clickable.
        if (ftsImageSize.width() < 180) {
            uniqueID.find('.slicker-date, .fts-insta-likes-comments-grab-popup, .fts-instagram-video-image-wrapper, .fts-carousel-image-wrapper').hide();
            uniqueID.find('.slicker-instagram-placeholder').addClass('fts-smallerthan-180');

        } else {
            uniqueID.find('.slicker-date, .fts-insta-likes-comments-grab-popup, .fts-instagram-video-image-wrapper, .fts-carousel-image-wrapper').show();
            uniqueID.find('.slicker-instagram-placeholder').removeClass('fts-smallerthan-180');

        }
    });
}

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/

jQuery(document).ready(slickremixImageResizingFacebook, slickremixImageResizingFacebook2, slickremixImageResizingFacebook3);
jQuery(window).on('resize',slickremixImageResizingFacebook, slickremixImageResizingFacebook2, slickremixImageResizingFacebook3);

function slickremixImageResizingFacebook() {

    jQuery('.fts-facebook-inline-block-centered').each(function () {

        const ftsBlockCenteredAttr = jQuery(this).attr('id');
        let uniqueID = jQuery('#' + ftsBlockCenteredAttr);

        // This is the container for the instagram image post
        let ftsImageSize = jQuery('#' + ftsBlockCenteredAttr + ' .slicker-facebook-placeholder');

        // How many do we want to show
        let ftsInstagramColumns = uniqueID.attr('data-ftsi-columns');
        // The margin in between photos so we can subtract that from the total %
        let ftsInstagramMargin = uniqueID.attr('data-ftsi-margin');
        // The margin without the px and we multiply it by 2 because the margin is on the left and right
        let ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 1;
        // Get the Instagram container .width() so we can keep track of the container size
        // let ftsContainerWidth = uniqueID.width();
        // Force columns so the images to not scale up from 376px-736px.
        // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
        // let ftsForceColumns = 'yes';
        // we or each option so if someone tries something other than that it will go to else statement
        if (ftsInstagramColumns === '2' ||
            ftsInstagramColumns === '3') {

            let og_size;
            if (ftsInstagramColumns === '3') {
                og_size = 'calc(33.0777777% - ' + ftsInstagramMarginfinal + 'px)';
            } else if (ftsInstagramColumns === '2') {
                og_size = 'calc(49.777777% - ' + ftsInstagramMarginfinal + 'px)';
            }

            ftsImageSize.css({'width': og_size});

            const ftsImageHeight = ftsImageSize.width();
            ftsImageSize.css({
                'width': og_size,
                'height': ftsImageHeight,
                'margin': ftsInstagramMargin
            });
        } else {
            let ftsImageWidth = uniqueID.attr('data-ftsi-width') ? uniqueID.attr('data-ftsi-width') : '325px';
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

        } else {
            jQuery('.fts-facebook-inline-block-centered .slicker-date, .fts-facebook-inline-block-centered .fts-insta-likes-comments-grab-popup').show();
            jQuery('.slicker-facebook-placeholder, .slicker-youtube-placeholder').removeClass('fts-smallerthan-180');
        }
    });
}

function slickremixImageResizingFacebook2() {
    let e = jQuery(".fts-more-photos-2-or-3-photos a"),
        t = "calc(49.88888888% - 1px)";
    e.css({
        width: t
    });
    const s = e.width();
    e.css({
        width: t,
        height: s,
        margin: "1px"
    })
}

function slickremixImageResizingFacebook3() {
    let e = jQuery(".fts-more-photos-4-photos a"),
        t = "calc(33.192222222% - 1px)";
    e.css({
        width: t
    });
    const s = e.width();
    e.css({
        width: t,
        height: s,
        margin: "1px"
    })
}

jQuery(document).ready(slickremixImageResizingYouTube);
jQuery(window).on('resize',slickremixImageResizingYouTube);
function slickremixImageResizingYouTube() {
    // This is the container for our instagram images
    let ftsBlockCenteredAttr = jQuery('.fts-youtube-inline-block-centered');

    // This is the container for the instagram image post
    let ftsYoutubeImageSize = jQuery('.slicker-youtube-placeholder');
    let ftsYoutubeThumbsContainer = jQuery('.fts-youtube-popup-gallery');

    let ftsYoutubeLarge = jQuery('.fts-yt-large');
    let ftsYoutubeThumbsWrap = jQuery('.fts-youtube-scrollable.fts-youtube-thumbs-wrap, .fts-youtube-scrollable.fts-youtube-thumbs-wrap-left, .youtube-comments-wrap-premium, .youtube-comments-wrap.fts-youtube-thumbs-wrap-right, .youtube-comments-wrap.fts-youtube-thumbs-wrap-left');

    // Get the Instagram container .width() so we can keep track of the container size
    let ftsContainerWidth = ftsBlockCenteredAttr.width();

    // How many columns do we want to show
    let ftsInstagramColumns;

    if (ftsContainerWidth <= '376' && ftsBlockCenteredAttr.attr('data-ftsi-columns-mobile') !== undefined ) {
        ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns-mobile');
    }
    else if (ftsContainerWidth <= '736' && ftsBlockCenteredAttr.attr('data-ftsi-columns-tablet') !== undefined) {
        ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns-tablet');
    }
    else {
        ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftsi-columns');
    }

    // The margin in between photos so we can subtract that from the total %
    let ftsInstagramMargin = ftsBlockCenteredAttr.attr('data-ftsi-margin');
    // The margin without the px and we multiply it by 2 because the margin is on the left and right
    let ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 2;
    // Force columns so the images to not scale up from 376px-736px.
    // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
    // commenting out for now since we are introducing the responsive modes for desktop, tablet and mobile.
    // let ftsForceColumns = ftsBlockCenteredAttr.attr('data-ftsi-force-columns');
    // we or each option so if someone tries something other than that it will go to else statement
    let og_size;
    if (ftsInstagramColumns === '1' ||
        ftsInstagramColumns === '2' ||
        ftsInstagramColumns === '3' ||
        ftsInstagramColumns === '4' ||
        ftsInstagramColumns === '5' ||
        ftsInstagramColumns === '6') {
            if (ftsInstagramColumns === '6') {
                og_size = 'calc(16.66666666666667% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '5') {
                og_size = 'calc(20% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '4') {
                og_size = 'calc(25% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '3') {
                og_size = 'calc(33.33333333333333% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '2') {
                og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '1') {
                og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
            }
        let ftsYoutubeLargeHeight = ftsYoutubeLarge.height();

        ftsYoutubeThumbsWrap.css({'height': ftsYoutubeLargeHeight + 'px'});

        ftsYoutubeImageSize.css({'width': og_size});

        ftsYoutubeThumbsContainer.css({
            'padding': ftsInstagramMargin
        });
        const ftsImageHeightYoutube = ftsYoutubeImageSize.width() - '150';
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

    const ftsYoutubeContainer = jQuery('.fts-master-youtube-wrap');
    // If our image square is less than 180px then we hide the play button for the youtube feed
    if (ftsYoutubeContainer.width() < 550) {
        jQuery('.fts-yt-large, .fts-youtube-scrollable, .youtube-comments-wrap').addClass('fts-youtube-smallerthan-550-stack');

    }
    else {
        jQuery('.fts-yt-large, .fts-youtube-scrollable, .youtube-comments-wrap').removeClass('fts-youtube-smallerthan-550-stack');
    }
}

// Find the height of the external link text wrapper and adjust the background image height
// so it always matches and the image and fits perfectly all the time.
function ftsRetweetHeight() {
    if( jQuery('div').hasClass( 'fts-tweeter-wrap' ) ) {

        let twitter_wrap = jQuery('.fts-tweeter-wrap');
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
            const retweet_height = jQuery(this).height() + 20;
            jQuery(this).parent().find('.fts-twitter-external-backg-image').css({'height': retweet_height + 'px'});
            //alert( jQuery(this).find( '.fts-twitter-quoted-text' ).height() );
        });
    }
}
// Return our ftsRetweetHeight function after page has loaded to speed things up. Plus this way we can recall it in the loadmore areas of each feed instead of duplicating all the js.
jQuery(document).ready(ftsRetweetHeight);
jQuery(window).on('resize', ftsRetweetHeight);