


function fts_image_to_woo(gallery_id){


    var $myListItemsCheck = jQuery('#ftg-tab-content1 li div.ft-gallery-select-thumbn input[type=checkbox]:checked');
    var myLengthCheck = $myListItemsCheck.length;

    //set a global var so we can check it again in our second each statement
    var already_created_text = false;

    jQuery($myListItemsCheck).each(function (i) {

        if(jQuery(this).parent().parent().parent().hasClass('ftg-product-exists')) {
            already_created_text = true
        }

        if (jQuery(this).hasClass('ftg-no-product')) {
            return true
        }
        else {
            jQuery('#ftg-tab-content1 .ft-gallery-notice').addClass('ftg-block');
            jQuery('#ftg-tab-content1 .ft-gallery-notice').addClass('updated');
            jQuery('#ftg-tab-content1 .ft-gallery-notice').html(ftg_woo.images_products_already_created + '<div class="ft-gallery-notice-close"></div>');
            return false
        }

    });

    if(already_created_text) {
        $already_created_text = '<div class="ftg-already-created-text">' + ftg_woo.images_products_already_complete_using_button + '</div>';
    }
    else {
        $already_created_text = '';
    }
    //Selected Media
    var selectedmedia = [];
    var $myListItems = jQuery('#ftg-tab-content1 li.ftg-no-product div.ft-gallery-select-thumbn input[type=checkbox]:checked');

    var myLength = $myListItems.length;

    jQuery('#uploadContainer').append('<div class="ft-gallery-checked-count-down-wrapper"></div>');

    console.log($myListItems);

    jQuery($myListItems).each(function (i) {
        var selectedmediacheck = jQuery(this).attr('rel');
        var selectedmedia = JSON.stringify(selectedmediacheck);


        var ftg_percentage_count = 100 / myLength * i + "%";
        var ftg_percentage_count_minus_one_count = 100 / myLength;
        var ftg_percentage_count_minus_one_count_check = 100 / myLength * i;
        var ftg_percentage_count_minus_one_count_final = ftg_percentage_count_minus_one_count_check - ftg_percentage_count_minus_one_count +'%';
        console.log(ftg_percentage_count);
        console.log(ftg_percentage_count_minus_one_count);
        console.log(ftg_percentage_count_minus_one_count_final);


        console.log(myLength);

        // We use this special ajaxQueue to make sure the create product function is not run again until the previous one sent is complete.
        // https://github.com/gnarf/jquery-ajaxQueue
        jQuery.ajaxQueue({
            data: {
                action: "fts_image_to_woo_prod",
                GalleryID: gallery_id,
                selectedMedia: selectedmedia
            },
            type: 'POST',
            url: ftgallerytoWooAjax.ajaxurl,
            beforeSend: function () {

                jQuery('#list_item_' + selectedmediacheck).addClass('ftg-load-pulse');
                jQuery('#list_item_' + selectedmediacheck).append('<div class="ftg-loading-overlay"><div class="ftg-loading-overlay-loader"></div></div>');

                jQuery('#ftg-tab-content1 .ft-gallery-notice').empty().removeClass('ftg-block');
                jQuery('#ftg-tab-content1 .ft-gallery-notice').removeClass('updated').addClass('ftg-block');

                // removing the spinning loader for now, I think it's overkill on the visual processing of everything since



                jQuery('#ftg-tab-content1 .ft-gallery-notice').append('<div class="ftg-loading-percentage-wrap"><div class="ftg-loading-percentage-bar" style="min-width:'+ftg_percentage_count_minus_one_count_final+'"></div></div>');
                jQuery('#ftg-tab-content1 .ft-gallery-notice').append('<div class="ftg-each-count">' + i + '</div>/<div class="ftg-total-count">' + myLength + '</div>');
                // setTimeout(function() {
                jQuery(".ftg-loading-percentage-bar").animate({
                    width: ftg_percentage_count
                });
                // }, 0);

            },
            success: function (response) {
                // the response will be a url to the edit woo product that we have constructed through the create woo product function.
                console.log('Well Done and got this from sever: ' + response);
                console.log(selectedmedia);
                jQuery('#list_item_' + selectedmediacheck).removeClass('ftg-load-pulse');
                jQuery('#list_item_' + selectedmediacheck +' .ftg-loading-overlay').fadeOut();

                jQuery('#list_item_' + selectedmediacheck).append('<div class="ft-gallery-woo-edit-thumb-btn" style="display: none"><a class="fts_create_woo_prod_button" target="_blank" href="'+response+'"></a></div>');
                // we hide the .ft-gallery-woo-edit-thumb-btn button above with display:none and then fade it in
                jQuery('.ft-gallery-woo-edit-thumb-btn').fadeIn();

                jQuery('#list_item_' + selectedmediacheck).addClass('ftg-product-exists');


                jQuery('#list_item_' + selectedmediacheck).removeClass('ftg-no-product');


                //'Woocommerce Product created from Image(s)! '
                if (i + 1 === myLength) {
                    jQuery('.ftg-loading-percentage-wrap').html('<div class="ftg-loading-percentage-bar" style="width:'+ftg_percentage_count+'">');
                    jQuery(".ftg-loading-percentage-bar").animate({
                        width: '100%'
                    });
                    setTimeout(function(){
                        jQuery('#ftg-tab-content1 .ft-gallery-notice').addClass('updated');
                        var ftg_final_count = i+1;
                        jQuery('#ftg-tab-content1 .ft-gallery-notice').html('<div class="ftg-each-count">'+ ftg_final_count +'</div>' + ftg_woo.images_products_complete_using_button  + $already_created_text + '<div class="ft-gallery-notice-close"></div>');
                        // we remove all these overlays with animation loaders because we are done with them
                        jQuery('#ftg-tab-content1 .ftg-loading-overlay').remove();

                    }, 500);
                }
                jQuery('.fts_download_button').removeAttr('disabled').removeClass('fts_download_button_loading');

                return false;
            }
        });

    });



    return false;

} // end fts_image_to_woo


function fts_image_to_woo_on_upload(imgId, postID){

    // We use this special ajaxQueue to make sure the create product function is not run again until the previous one sent is complete.
    // https://github.com/gnarf/jquery-ajaxQueue
    jQuery.ajaxQueue({
        data: {
            action: "fts_image_to_woo_prod",
            GalleryID: postID,
            selectedMedia: imgId
        },
        type: 'POST',
        url: ftgallerytoWooAjax.ajaxurl,
        beforeSend: function () {
            // jQuery('#list_item_' + imgId).addClass('ftg-load-pulse');
            jQuery('#list_item_' + imgId).append('<div class="ftg-loading-overlay"><div class="ftg-loading-overlay-loader"></div></div>');
            jQuery('#ftg-tab-content1 .ft-gallery-notice').empty().removeClass('ftg-block');
            jQuery('#ftg-tab-content1 .ft-gallery-notice').removeClass('updated').addClass('ftg-block');
        },
        success: function (response) {
            console.log('Well Done and got this from sever: ' + response);
            jQuery('#list_item_' + imgId).removeClass('ftg-load-pulse');
            jQuery('#list_item_' + imgId +' .ftg-loading-overlay').fadeOut();
            //  console.log(selectedmedia);



            var allDivs = jQuery('.file');
            var classedDivs = jQuery('.file.ftg-upload-complete');

            var allDivsHaveClass = (allDivs.length === classedDivs.length);
            if (allDivsHaveClass) {
                // we setTimout because sometimes the last image will lag and want to show this message twice
                // so we try to stop that by holing out a second more before showing the message
                setTimeout(function(){
                    jQuery('#ftg-tab-content1 .ft-gallery-notice').addClass('updated');
                    jQuery('#ftg-tab-content1 .ft-gallery-notice').addClass('ftg-block');
                    jQuery('#ftg-tab-content1 .ft-gallery-notice').html(ftg_woo.images_products_complete_on_auto_upload);
                    jQuery('#ftg-tab-content1 .ft-gallery-notice').append('<div class="ft-gallery-notice-close"></div>');
                    console.log('made it');
                }, 500);
                // alert('made it');
            }







            jQuery('#list_item_'+imgId).append('<div class="ft-gallery-woo-edit-thumb-btn" style="display: none"><a class="fts_create_woo_prod_button" target="_blank" href="'+response+'"></a></div>');
            // we hide the .ft-gallery-woo-edit-thumb-btn button above with display:none and then fade it in
            jQuery('.ft-gallery-woo-edit-thumb-btn').fadeIn();

            jQuery('#list_item_'+imgId).addClass('ftg-product-exists');

            jQuery('.fts_download_button').removeAttr('disabled').removeClass('fts_download_button_loading');

            return false;
        }
    });
    return false;
} // end fts_image_to_woo_on_upload

(function($) {
    // We use this in the function above so the product create function does not get overloaded and cause server timeouts
    // jQuery on an empty object, we are going to use this as our Queue
    var ajaxQueue = $({});

    $.ajaxQueue = function( ajaxOpts ) {
        var jqXHR,
            dfd = $.Deferred(),
            promise = dfd.promise();

        // queue our ajax request
        ajaxQueue.queue( doRequest );

        // add the abort method
        promise.abort = function( statusText ) {

            // proxy abort to the jqXHR if it is active
            if ( jqXHR ) {
                return jqXHR.abort( statusText );
            }

            // if there wasn't already a jqXHR we need to remove from queue
            var queue = ajaxQueue.queue(),
                index = $.inArray( doRequest, queue );

            if ( index > -1 ) {
                queue.splice( index, 1 );
            }

            // and then reject the deferred
            dfd.rejectWith( ajaxOpts.context || ajaxOpts,
                [ promise, statusText, "" ] );

            return promise;
        };

        // run the actual query
        function doRequest( next ) {
            jqXHR = $.ajax( ajaxOpts )
                .then( next, next )
                .done( dfd.resolve )
                .fail( dfd.reject );
        }

        return promise;
    };
})(jQuery);




























function fts_zip_to_woo(gallery_id,zipID){
    //jQuery('.fts_download_button').attr('disabled', '').addClass('fts_download_button_loading');

    jQuery.ajax({
        data: {action: "fts_zip_to_woo_prod", GalleryID: gallery_id, ZIP_ID: zipID},
        type: 'POST',
        async: true,
        url: ftgallerytoWooAjax.ajaxurl,
        beforeSend: function () {
            jQuery('#ftg-tab-content6 .ft-gallery-notice').empty().removeClass('ftg-block');
            jQuery('#ftg-tab-content6 .ft-gallery-notice').removeClass('updated').addClass('ftg-block');
            jQuery('#ftg-tab-content6 .ft-gallery-notice').prepend('<div class="fa fa-cog fa-spin fa-3x fa-fw ft-gallery-loader"></div><div>This may take a few minutes based on your gallery size and server speed.</div>');


        },
        success: function (response) {
            console.log('Well Done and got this from sever: ' + response);

            //'Woocommerce Product created from Image(s)! '
            jQuery('#ftg-tab-content6 .ft-gallery-notice').html(response);
            jQuery('#ftg-tab-content6 .ft-gallery-notice').addClass('updated');
            jQuery('#ftg-tab-content6 .ft-gallery-notice').append('<div class="ft-gallery-notice-close"></div>');

            jQuery('.fts_download_button').removeAttr('disabled').removeClass('fts_download_button_loading');

            return false;
        }
    }); // end of ajax()
    return false;
} // end of form.submit