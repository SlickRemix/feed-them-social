function fts_create_zip(postID, activate_download, create_woo_prod, download_newest_zip) {

    var clicked_button = jQuery(this);

    jQuery('.fts_download_button').attr('disabled', '').addClass('fts_download_button_loading');

    var selectedmedia = [];
    jQuery('#ftg-tab-content1 input[type=checkbox]').each(function () {
        if (jQuery(this).attr('checked')) {
            selectedmedia.push(jQuery(this).attr('rel'));
        }
    });

    if (selectedmedia.length) {
        selectedmedia = JSON.stringify(selectedmedia);
    }
    jQuery.ajax({
        data: {
            action: "fts_create_zip_ajax",
            postId: postID,
            ActivateDownload: activate_download,
            CreateWooProd: create_woo_prod,
            DownloadNewestZIP: download_newest_zip

            //selectedMedia: selectedmedia

        },
        type: 'POST',
        async: true,
        url: ftgalleryAjax.ajaxurl,
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
            alert(thrownError);
        },
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            //Download progress
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    jQuery('.ft-gallery-notice').html(Math.round(percentComplete * 100) + "%");
                }
            }, false);
            return xhr;
        },
        beforeSend: function () {
            jQuery('.ft-gallery-notice').empty().removeClass('ftg-block');
            jQuery('.ft-gallery-notice').removeClass('updated').addClass('ftg-block');
            jQuery('.ft-gallery-notice').prepend('<div class="fa fa-cog fa-spin fa-3x fa-fw ft-gallery-loader"></div><div>This may take a few minutes based on your gallery size and server speed.</div>');

            // jQuery('#loading').show();
        },
        success: function (data) {

            jQuery("#loading").hide();
            jQuery('.ft-gallery-notice').removeClass('ftg-block');
            console.log('Well Done and got this from sever: ' + data);
            //Download & Zip
            if (data != 'false' && (activate_download == 'yes' || download_newest_zip == 'yes')) {
                window.location.assign(data);
            }
            else if(activate_download !== 'yes' && download_newest_zip !== 'yes' && create_woo_prod == 'yes'){
                jQuery('.ft-gallery-notice').html('ZIP Created! You can view it in the <a href="' + window.location.href + '&tab=ft_zip_gallery">ZIPs tab</a>. The Woocommerce product was also created you view it on the <a href="edit.php?post_status=publish&post_type=product&orderby=menu_order+title&order=ASC" target="_blank">Products Page</a>.');
                jQuery('.ft-gallery-notice').prepend('<div class="fa fa-check-circle fa-3x fa-fw ft-gallery-success" ></div>');
                jQuery('.ft-gallery-notice').addClass('updated');
                jQuery('.ft-gallery-notice').append('<div class="ft-gallery-notice-close"></div>');
            }
            else{
                jQuery('.ft-gallery-notice').html('ZIP Created! You can view it in the <a href="' + window.location.href + '&tab=ft_zip_gallery">ZIPs tab</a>.');
                jQuery('.ft-gallery-notice').prepend('<div class="fa fa-check-circle fa-3x fa-fw ft-gallery-success" ></div>');
                jQuery('.ft-gallery-notice').addClass('updated');
                jQuery('.ft-gallery-notice').append('<div class="ft-gallery-notice-close"></div>');
            }

            return false;
        }
    }); // end of ajax()
    return false;
} // end of form.submit


function fts_view_zip_contents(postID, zipID, zipname) {
    jQuery.ajax({
        data: {action: "fts_view_zip_ajax", postId: postID, ZIP_name: zipname, ZIP_ID: zipID},
        type: 'POST',
        async: false,
        url: ftgalleryAjax.ajaxurl,
        beforeSend: function () {

            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' ol.zipcontents_list').empty();
            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' ol.zipcontents_list').append('<div class="fa fa-cog fa-spin fa-3x fa-fw ft-gallery-loader"></div>');

        },
        success: function (data) {
            // console.log('Well Done and got this from sever: ' + data);
            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' .fts_hide_zip_list').show();
            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' .zipcontents_list').show();
            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' .fts_view_zip_button').hide();
            jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' ol.zipcontents_list').html(data);

            return false;
        },
        error: function (data) {
            alert("There was an error. Try again please!");
        }

    }); // end of ajax()
    return false;
} // end of form.submit

// Hide the zip ul list after it has been opened
function fts_hide_zip_contents(postID, zipID) {

    jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' ol').hide();
    jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' .fts_hide_zip_list').hide();
    jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID + ' .fts_view_zip_button').show();

    return false;
} // end of form.submit


function fts_delete_zip(zipID, zipname) {
    if (confirm('Are you sure you want to delete this ZIP? This cannot be undone!')) {
        jQuery.ajax({
            data: {action: "fts_delete_zip_ajax", ZIP_ID: zipID},
            type: 'POST',
            async: false,
            url: ftgalleryAjax.ajaxurl,
            beforeSend: function () {
                jQuery('.ft-gallery-notice').empty();
                jQuery('.ft-gallery-notice').removeClass('updated');
                jQuery('.ft-gallery-notice').prepend('<div class="fa fa-cog fa-spin fa-3x fa-fw ft-gallery-loader"></div>');
            },
            success: function (data) {
                jQuery('.ft-gallery-file-delete').removeAttr('disabled').removeClass('fts_download_button_loading');
                console.log('Well Done and got this from sever: ' + data);

                jQuery("#ft-gallery-zip-list li.zip-list-item-" + zipID).remove();

                jQuery('.ft-gallery-notice').html('ZIP Deleted! ');
                jQuery('.ft-gallery-notice').append('<div class="fa fa-check-circle fa-3x fa-fw ft-gallery-success" ></div>');

                if (jQuery('.ft-gallery-notice').is(':empty')){
                    jQuery('.ft-gallery-notice').html('You have not created any ZIPs yet. You can do so from the <a href="#images" class="ftg-images-tab">Images tab</a>. Please reload this page if you have already created a ZIP from the Images tab.');
                }
                jQuery('.ft-gallery-notice').addClass('updated');
                jQuery('.ft-gallery-notice').append('<div class="ft-gallery-notice-close"></div>');

                return false;
            },
            error: function (data) {
                alert("There was an error. Try again please!");
            }

        }); // end of ajax()
        return false;
    }
} // end of form.submit