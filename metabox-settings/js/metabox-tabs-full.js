jQuery(document).ready(ftg_admin_gallery_tabs);

function ftg_admin_gallery_tabs() {
    // enable link to tab
    jQuery('ul.nav-tabs').each(function () {
        // For each set of tabs, we want to keep track of
        // which tab is active and its associated content
        var $active, $content, $links = jQuery(this).find('a');

        // If the location.hash matches one of the links, use that as the active tab.
        // If no match is found, use the first link as the initial active tab.
        $active = jQuery($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
        $active.addClass('active');

        $content = jQuery($active[0].hash);

        // Hide the remaining content
        $links.not($active).each(function () {
            jQuery(this.hash).hide();
        });

        // Bind the click event handler
        jQuery(this).on('click', 'a', function (e) {
            // Make the old tab inactive.
            $active.removeClass('active');
            $content.hide();

            // Update the variables with the new link and content
            $active = jQuery(this);
            $content = jQuery(this.hash);

            // Make the tab active.
            $active.addClass('active');
            $content.show();

            // Prevent the anchor's default click action
            e.preventDefault();
        });
    });
}

jQuery(document).ready(function ($) {
    jQuery('.ft-gallery-notice').on('click', '.ft-gallery-notice-close', function () {
        jQuery('.ft-gallery-notice').html('');
        jQuery('.ft-gallery-notice').removeClass('updated, ftg-block')
    });

    // Show the proper tab if this link type is clicked on any tab of ours
    jQuery('.tab-content-wrap').on('click', '.ftg-zips-tab', function (e) {
        jQuery('.tab4 a').click();
        var clickedLink = $('.tab4 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.ftg-woo-tab', function (e) {
        jQuery('.tab5 a').click();
        var clickedLink = $('.tab5 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });


    jQuery('.tab-content-wrap').on('click', '.ftg-pagination-tab', function (e) {
        jQuery('.tab6 a').click();
        var clickedLink = $('.tab6 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    jQuery('.tab-content-wrap').on('click', '.ftg-images-tab', function (e) {
        jQuery('.tab1 a').click();
        var clickedLink = $('.tab1 a').attr('href');
        // push it into the url
        location.hash = clickedLink;
        // Prevent the anchor's default click action
        e.preventDefault();
    });

    var hash = window.location.hash.replace('#', '');
    if (hash) {
        document.getElementById(hash).style.display = 'block'
    }

    if (jQuery('#publish').attr('name') === 'publish') {
        var submitAjax = 'no';
    }
    // alert('no');
    else {
        var submitAjax = 'yes';
        // alert('yes');
    }

    if (submitAjax == 'yes') {
        jQuery('.post-type-fts .wrap form#post, .post-type-fts_albums .wrap form#post, .fts_page_template_settings_page .wrap form#post').submit(function (e) {
            e.preventDefault();
            jQuery(this).ajaxSubmit({
                beforeSend: function () {
                    jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-saving-form'></div></div></div>");
                    jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
                    jQuery('#publishing-action .spinner').css("visibility", "visible");

                },
                success: function () {
                    jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage'    class='ftg-successModal ftg-success-form'></div></div></div>");
                    jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
                    jQuery('#publishing-action .spinner').css("visibility", "hidden");

                    setTimeout("jQuery('.ftg-overlay-background').hide();", 400);

                    var hash2 = window.location.hash.replace('#', '');
                    // alert(hash2);
                    if (hash2 === 'images' || hash2 === 'galleries' || jQuery('.post-type-fts_albums .tab1').hasClass('active') || hash2 === '' && !jQuery('.fts_page_template_settings_page')[0]) {
                        location.reload();
                    }
                    // We change the text from Updating... at the bottom of a long page to Update.
                    jQuery('.updatefrombottom a.button-primary').html("Update");
                }
            });
            return false;
        });
    }
    // click event listener
    $('.ft-gallery-settings-tabs-meta-wrap ul.nav-tabs a').click(function (event) {
        // get the id
        var clickedLink = $(this).attr('href');
        // push it into the url
        location.hash = clickedLink;
    });


    //create hash tag in url for tabs
    jQuery('.ft-gallery-settings-tabs-meta-wrap #tabs').on('click', "label.tabbed", function () {
        var myURL = document.location;
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

    if (jQuery("#fts_watermark").val() == 'imprint') {
        jQuery('.ft-watermark-hidden-options').show();
        jQuery('.ft-watermark-overlay-options, .ft-gallery-watermark-opacity').hide();
    }

    if (jQuery('#fts_watermark').val() == 'overlay') {
        jQuery('.ft-watermark-overlay-options, .ft-gallery-watermark-opacity').show();
        jQuery('.ft-watermark-hidden-options').hide();
    }

    // show load more options
    jQuery('#fts_watermark').bind('change', function (e) {
        if (jQuery('#fts_watermark').val() == 'imprint') {

            jQuery('.ft-watermark-hidden-options').show();
            jQuery('.ft-watermark-overlay-options, .ft-gallery-watermark-opacity').hide();
        }
        if (jQuery('#fts_watermark').val() == 'overlay') {
            jQuery('.ft-watermark-overlay-options, .ft-gallery-watermark-opacity').show();
            jQuery('.ft-watermark-hidden-options').hide();
        }
    });

    // show the duplicate image select box for those who want to duplicate the image before watermarking
    jQuery('#ft_watermark_image_-full').change(function () {
        this.checked ? jQuery('.ft-watermark-duplicate-image').show() : jQuery('.ft-watermark-duplicate-image').hide();
    });
    //if page is loaded and box is checked we show the select box otherwise it is hidden with CSS
    if (jQuery('input#ft_watermark_image_-full').is(':checked')) {
        jQuery('.ft-watermark-duplicate-image').show()
    }


    // show load more options
    jQuery('#ftg_sorting_options').bind('change', function (e) {
        if (jQuery('#ftg_sorting_options').val() == 'yes') {
            jQuery('.ftg-sorting-options-wrap').show();
        }
        else {
            jQuery('.ftg-sorting-options-wrap').hide();
        }
    });
    if (jQuery('#ftg_sorting_options').val() == 'no') {
        jQuery('.ftg-sorting-options-wrap').hide();
    }
    if (jQuery('#ftg_sorting_options').val() == 'yes') {
        jQuery('.ftg-sorting-options-wrap').show();
    }

    // show load more options
    jQuery('#fts_show_true_pagination').bind('change', function (e) {
        if (jQuery('#fts_show_true_pagination').val() == 'yes') {
            jQuery('.ftg-pagination-options-wrap').show();
            jQuery('#fts_load_more_option').attr('disabled', 'disabled');
            jQuery('.ftg-pagination-notice-colored').hide();
            jQuery('.ftg-loadmore-notice-colored').show();
        }
        else {
            jQuery('.ftg-pagination-options-wrap').hide();
            jQuery('#fts_load_more_option').removeAttr('disabled');
            jQuery('.ftg-loadmore-notice-colored').hide();
        }
    });
    if (jQuery('#fts_show_true_pagination').val() == 'no') {
        jQuery('.ftg-pagination-options-wrap').hide();
        jQuery('#fts_load_more_option').removeAttr('disabled');
    }
    if (jQuery('#fts_show_true_pagination').val() == 'yes') {
        jQuery('.ftg-pagination-options-wrap').show();
        jQuery('#fts_load_more_option').attr('disabled','disabled');
        jQuery('.ftg-loadmore-notice-colored').show();
    }


    // show load more options
    jQuery('#fts_load_more_option').bind('change', function (e) {
        if (jQuery('#fts_load_more_option').val() == 'yes') {

            if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
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
        jQuery(".feed_them_social-admin-input-label:contains('Center Facebook Container?')").parent('div').show();
    }

    // show title description placement
    jQuery('#fts_type').bind('change', function (e) {
        if(jQuery('#fts_type').val() == 'post-in-grid' || jQuery('#fts_type').val() == 'post'){
            jQuery('.ftg-page-title-description-placement-option-hide').show();
        }
        else {
            jQuery('.ftg-page-title-description-placement-option-hide').hide();
        }
    });

    if (jQuery('#fts_type').val() == 'post-in-grid' || jQuery('#fts_type').val() == 'post') {

        jQuery('.ftg-page-title-description-placement-option-hide').show();
        jQuery('.ft-gallery-hide-add-to-cart-over-image, .ft-gallery-hide-position-add-to-cart-over-image').hide();


    }
    else {
        jQuery('.ftg-page-title-description-placement-option-hide').hide();
    }


    // show cart icon placement if you chose the gallery (responsive) layout.
    jQuery('#fts_type').bind('change', function (e) {
        if(jQuery('#fts_type').val() == 'gallery'){
            jQuery('.ft-gallery-hide-add-to-cart-over-image, .ft-gallery-hide-position-add-to-cart-over-image, .ft-gallery-hide-popup-or-add-to-cart-link, .ft-gallery-hide-icon-background-color, .ft-gallery-hide-icon-color, .ft-gallery-hide-icon-hover-color').show();
        }
        else {
            jQuery('.ft-gallery-hide-add-to-cart-over-image, .ft-gallery-hide-position-add-to-cart-over-image, .ft-gallery-hide-popup-or-add-to-cart-link, .ft-gallery-hide-icon-background-color, .ft-gallery-hide-icon-color, .ft-gallery-hide-icon-hover-color').hide();
        }
    });


    if (jQuery('#fts_type').val() == 'gallery') {

        jQuery('.ft-gallery-hide-add-to-cart-over-image, .ft-gallery-hide-position-add-to-cart-over-image, .ft-gallery-hide-popup-or-add-to-cart-link, .ft-gallery-hide-icon-background-color, .ft-gallery-hide-icon-color, .ft-gallery-hide-icon-hover-color').show();

    }
    else {
        jQuery('.ft-gallery-hide-add-to-cart-over-image, .ft-gallery-hide-position-add-to-cart-over-image, .ft-gallery-hide-popup-or-add-to-cart-link, .ft-gallery-hide-icon-background-color, .ft-gallery-hide-icon-color, .ft-gallery-hide-icon-hover-color').hide();
    }

    if (jQuery('#fts_type').val() == 'post-in-grid' || jQuery('#fts_type').val() == 'gallery' || jQuery('#fts_type').val() == 'gallery-collage') {

        if (jQuery('#fts_type').val() == 'gallery') {
            jQuery('#fts_height').show();
            jQuery('.fb-page-columns-option-hide').show();
            jQuery('.ftg-hide-for-columns').hide();
            jQuery('.ftg-masonry-columns-option-hide').hide();
            jQuery('.fb-page-grid-option-hide').show();
        }
        else {
            jQuery('.fts_height').hide();
            jQuery('.fb-page-columns-option-hide').hide();
            jQuery('.ftg-hide-for-columns').show();
            jQuery('.ftg-masonry-columns-option-hide').show();
            jQuery('.fb-page-grid-option-hide').hide();
        }
    }
    else {
        jQuery('.fb-page-grid-option-hide, .fts_height').hide();
        jQuery('.ftg-masonry-columns-option-hide').hide();
    }

    // show grid options
    jQuery('#fts_type').bind('change', function (e) {
        if (jQuery('#fts_type').val() == 'post-in-grid' || jQuery('#fts_type').val() == 'gallery' || jQuery('#fts_type').val() == 'gallery-collage') {
            if (jQuery('#fts_type').val() == 'gallery') {
                jQuery('#fts_height').show();
                jQuery('.fb-page-columns-option-hide').show();
                jQuery('.ftg-hide-for-columns').hide();
                jQuery('.ftg-masonry-columns-option-hide').hide();
                jQuery('.fb-page-grid-option-hide').show();
            }
            else {
                jQuery('.fts_height').hide();
                jQuery('.fb-page-columns-option-hide').hide();
                jQuery('.ftg-hide-for-columns').show();
                jQuery('.ftg-masonry-columns-option-hide').show();
                jQuery('.fb-page-grid-option-hide').hide();
            }
        }
        else {
            jQuery('.fb-page-grid-option-hide').hide();
            jQuery('.ftg-masonry-columns-option-hide').hide();
        }
    });

});