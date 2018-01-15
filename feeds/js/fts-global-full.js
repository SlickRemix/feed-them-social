jQuery(document).ready(function() {
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
        jQuery(".fts-instagram-popup-half video, .fts-simple-fb-wrapper video, .fts-slicker-facebook-posts video").click(function() {
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
jQuery(window).load(function() {
    if (jQuery.fn.masonry) {
        setTimeout(function () {
            jQuery(".fts-pinterest-wrapper.masonry").masonry("layout");
        }, 200);
    }
});
var ftsMyMinSetWidth = '250';
jQuery(document).ready(function() {
    if(jQuery('.fts-fb-photo-post-wrap').width() < ftsMyMinSetWidth) {
        jQuery('.fts-fb-thumbs-wrap').css({'max-width': '100%', 'margin-bottom': '1px', 'float': 'left'});
    }
});
jQuery(window).resize(function() {
    if(jQuery('.fts-fb-photo-post-wrap').width() < ftsMyMinSetWidth) {
        jQuery('.fts-fb-thumbs-wrap').css({'max-width': '100%', 'margin-bottom': '1px', 'float': 'left'});
    }
    if(jQuery('.fts-fb-photo-post-wrap').width() > ftsMyMinSetWidth){
        jQuery('.fts-fb-thumbs-wrap').removeAttr( 'style' );
    }
});


var ftsMyMinSetWidthInstagram = '250';
jQuery(document).ready(function() {
    if(jQuery('.fts-instagram-inline-block-centered').width() < ftsMyMinSetWidthInstagram) {
        jQuery('.slicker-instagram-placeholder').css({'max-width': '100%', 'margin-bottom': '1px', 'float': 'left'});
    }
});
jQuery(window).resize(function() {
    if(jQuery('.fts-instagram-inline-block-centered').width() < ftsMyMinSetWidthInstagram) {
        jQuery('.slicker-instagram-placeholder').css({'max-width': '100%', 'margin-bottom': '1px', 'float': 'left'});
    }
    if(jQuery('.fts-instagram-inline-block-centered').width() > ftsMyMinSetWidthInstagram){
        jQuery('.slicker-instagram-placeholder').removeAttr( 'style' );
    }
});