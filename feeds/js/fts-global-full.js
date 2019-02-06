jQuery(document).ready(function() {


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