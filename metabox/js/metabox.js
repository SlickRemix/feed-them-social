jQuery( document ).ready(
    function ($) {

        // Media Library button hook (WP >= 3.5):
        $( 'a#dgd_library_button' ).click(
            function (e) {

                // create a new Library, base on defaults
                // you can put your attributes in
                var insertImage = wp.media.controller.Library.extend(
                    {
                        defaults :  _.defaults(
                            {
                                id:        'insert-image',
                                title:      'Insert Image Url',
                                allowLocalEdits: true,
                                displaySettings: true,
                                displayUserSettings: true,
                                multiple : true,
                                type : 'image'// audio, video, application/pdf, ... etc
                            },
                            wp.media.controller.Library.prototype.defaults
                        )
                    }
                );

                // Setup media frame
                var frame = wp.media(
                    {
                        button : { text : 'Select' },
                        state : 'insert-image',
                        states : [
                            new insertImage()
                        ]
                    }
                );

                // on close, if there is no select files, remove all the files already selected in your main frame
                frame.on(
                    'close',
                    function() {
                        var selection = frame.state( 'insert-image' ).get( 'selection' );
                        if ( ! selection.length) {
                            // #remove file nodes
                            // #such as: jq("#my_file_group_field").children('div.image_group_row').remove();
                            // #...
                        }
                    }
                );

                frame.on(
                    'select',
                    function() {
                        var state      = frame.state( 'insert-image' );
                        var selection  = state.get( 'selection' );
                        var imageArray = [];
                        console.log( selection );
                        if ( ! selection ) {
                            return;
                        }

                        // #remove file nodes
                        // #such as: jq("#my_file_group_field").children('div.image_group_row').remove();
                        // #...
                        // to get right size attachment UI info, such as: size and alignments
                        // org code from /wp-includes/js/media-editor.js, arround `line 603 -- send: { ... attachment: function( props, attachment ) { ... `
                        selection.each(
                            function(attachment) {
                                var display        = state.display( attachment ).toJSON();
                                var obj_attachment = attachment.toJSON();
                                var caption        = obj_attachment.caption, options, html;

                                // If captions are disabled, clear the caption.
                                if ( ! wp.media.view.settings.captions ) {
                                    delete obj_attachment.caption;
                                }

                                display = wp.media.string.props( display, obj_attachment );

                                options = {
                                    id:        obj_attachment.id,
                                    post_content: obj_attachment.description,
                                    post_excerpt: caption
                                };

                                if ( display.linkUrl ) {
                                    options.url = display.linkUrl;
                                }

                                if ( 'image' === obj_attachment.type ) {
                                    html = wp.media.string.image( display );
                                    _.each(
                                        {
                                            align: 'align',
                                            size:  'image-size',
                                            alt:   'image_alt'
                                        },
                                        function( option, prop ) {
                                            if ( display[ prop ] ) {
                                                options[ option ] = display[ prop ];
                                            }
                                        }
                                    );
                                } else if ( 'video' === obj_attachment.type ) {
                                    html = wp.media.string.video( display, obj_attachment );
                                } else if ( 'audio' === obj_attachment.type ) {
                                    html = wp.media.string.audio( display, obj_attachment );
                                } else {
                                    html               = wp.media.string.link( display );
                                    options.post_title = display.title;
                                }

                                // attach info to attachment.attributes object
                                attachment.attributes['nonce']      = wp.media.view.settings.nonce.sendToEditor;
                                attachment.attributes['attachment'] = options;
                                attachment.attributes['html']       = html;
                                attachment.attributes['post_id']    = wp.media.view.settings.post.id;
                                // var src_str = attachment.url;
                                if (attachment.attributes['sizes']['fts_thumb']) {
                                    var ftgImageThumb = attachment.attributes['sizes']['fts_thumb']['url'];
                                    // alert('fts_thumb')
                                    // console.log('fts_thumb');
                                }
                                // the fts_thumb image size was not created because it was added before our plugin was activated
                                else {
                                    var ftgImageThumb = attachment.attributes['sizes']['thumbnail']['url'];
                                    // alert('wp thumbnail NOT an fts_thumb')
                                    // console.log('wp thumbnail NOT an fts_thumb');
                                }

                                jQuery( '#img1plupload-thumbs' ).prepend( '<li class="thumb" id="list_item_' + attachment.id + '" data-image-id="' + attachment.id + '"><img src="' + ftgImageThumb + '" alt="" /><div class="clear"></div></li>' );

                                // do what ever you like to use it
                                // console.log(attachment.attributes);
                                // console.log(attachment.attributes['attachment']);
                                // console.log(attachment.attributes['html']);
                                // console.log(attachment.attributes['sizes']['fts_thumb']);
                                jQuery.ajax(
                                    {
                                        data: {
                                            'action': "fts_edit_image_ajax",
                                            // submit our values to function simple_das_fep_add_post
                                            'id': attachment.id ,
                                            'postID': jQuery( '#img1plupload-thumbs' ).attr( 'data-post-id' ) ,
                                            'nonce': 'attach_image'
                                        },
                                        type: 'post',
                                        url: ssAjax.ajaxurl,
                                        success: function (response) {
                                            // Complete Sucess
                                            // var jsArray = JSON.parse(response)
                                            console.log( 'Well Done and got this from sever: ' + response );
                                        },
                                        error: function () {
                                            alert( 'Error, please contact us at https://slickremix.com/ for help.' )
                                        }
                                    }
                                ); // end of ajax()
                                // return false;
                            }
                        );
                    }
                );

                // reset selection in popup, when open the popup
                frame.on(
                    'open',
                    function() {
                        var selection = frame.state( 'insert-image' ).get( 'selection' );

                        // remove all the selection first
                        selection.each(
                            function(image) {
                                var attachment = wp.media.attachment( image.attributes.id );
                                attachment.fetch();
                                selection.remove( attachment ? [ attachment ] : [] );
                            }
                        );

                    }
                );

                // now open the popup
                frame.open();

            }
        );


        if ( jQuery('div.ft-gallery-popup-form').hasClass('ftg-premium-active')  ) {
            jQuery('<div id="ftg-watermark-logo-wrap"></div>').insertAfter('#ft-watermark-image');
        }

        // Media Library button hook (WP >= 3.5):
        $( 'input#ft-watermark-image' ).click(
            function (e) {

                // Prevent default:
                e.preventDefault();

                // Set frame object:
                var frame = wp.media(
                    {
                        id: 'dgd_featured_image',
                        title: dgd_strings.panel.title,
                        multiple: false,
                        library: {type: 'image'},
                        button: {text: dgd_strings.panel.button}
                    }
                );

                // On select image:
                frame.on(
                    'select',
                    function () {
                        var attachment = frame.state().get( 'selection' ).first().toJSON();
                        // doSetFeaturedImage();
                        jQuery( '#ft_watermark_image_input' ).val( attachment.url );
                        jQuery( '#ft_watermark_image_id' ).val( attachment.id );

                        jQuery( '#ftg-watermark-logo-wrap' ).html( '<img src="' + attachment.url + '" class="ft-watermark-image-thumb" /><br/>' );

                        // FOR Now we detach the watermark logos from the media library so they do not show up in the gallery list
                        // So we add in this little bit to override any themes trying to set this attachment.
                        jQuery.ajax(
                            {
                                data: {
                                    'action': "fts_update_image_ajax",
                                    // submit our values to function simple_das_fep_add_post
                                    'id': attachment.id,
                                    'nonce': attachment.id,
                                    'fts_img_remove': 'true'
                                },
                                type: 'POST',
                                url: ssAjax.ajaxurl,
                                beforeSend: function () {
                                    // alert('Are sure you want to do this? You cannot undo this operation.')
                                    // jQuery('#new_post .sidebar-sup-submit-wrap').append('<div class="fa fa-cog fa-spin fa-3x fa-fw sidebar-sup-loader"></div>');
                                    // jQuery("#new_post .sidebar-sup-success").remove();
                                },
                                success: function (response) {
                                    // Complete Sucess
                                    console.log( 'Well Done and got this from sever: ' + response );

                                },
                                error: function () {
                                    alert( 'Error, please contact us at https://www.slickremix.com/support for help.' )
                                }
                            }
                        ); // end of ajax()
                        return false;

                    }
                );

                // Display:
                frame.open();

            }
        );

        if (jQuery( '#ft-watermark-image' ).val() !== '') {

            jQuery( '#ftg-watermark-logo-wrap' ).html( '<img src="' + jQuery( '#ft_watermark_image_input' ).val() + '" class="ft-watermark-image-thumb ft-watermark-existing" /><br/>' );
        }

        // Set as featured image hook (WP < 3.5):
        $( 'a.wp-post-thumbnail' ).live(
            'click',
            function (e) {
                parent.tb_remove();
                parent.location.reload( 1 );
            }
        );

        // Set as featured image handler (WP >= 3.5):
        $( 'a#insert-media-button' ).live(
            'click',
            function () {
                if (typeof wp !== 'undefined') {
                    var editor_id = $( '.wp-media-buttons:eq(0) .add_media' ).attr( 'data-editor' );
                    var frame     = wp.media.editor.get( editor_id );
                    frame         = 'undefined' != typeof(frame) ? frame : wp.media.editor.add( editor_id );
                    if (frame) {
                        frame.on(
                            'select',
                            function () {
                                var currentState = frame.state();
                                if (currentState.id === 'featured-image') {
                                    doFetchFeaturedImage();
                                }
                            }
                        );
                    }
                }
            }
        );

        jQuery("#img1plupload-thumbs").sortable({

            items: 'li',
            opacity: 1,
            cursor: 'move',
            update: function() {
                var id = jQuery(this).data('post-id');
                var ordr = jQuery(this).sortable("serialize") + '&post_id=' + id + '&action=list_update_order';
                jQuery.post(ajaxurl, ordr, function (response) {
                    console.log(response);
                    console.log(id);
                    console.log(ordr);

                    //   plu_show_thumbs(imgId);
                });
            }
        });

    }
);

// helpful pointers
// https://wordpress.stackexchange.com/questions/179618/add-inline-uploader-to-plugin-option-page/181524
// https://www.krishnakantsharma.com/image-uploads-on-wordpress-admin-screens-using-jquery-and-new-plupload/#.WYN5whPysUF
jQuery.fn.exists = function () {
    return jQuery( this ).length > 0;
};
jQuery( document ).ready(
    function ($) {

        if ($( ".plupload-upload-uic" ).exists()) {
            var pconfig = false;

            $( ".plupload-upload-uic" ).each(
                function () {

                    var $this = $( this );
                    var id1   = $this.attr( "id" );
                    var imgId = id1.replace( "plupload-upload-ui", "" );

                    plu_show_thumbs( imgId );

                    pconfig = JSON.parse( JSON.stringify( base_plupload_config ) );

                    pconfig["browse_button"]                   = imgId + pconfig["browse_button"];
                    pconfig["container"]                       = imgId + pconfig["container"];
                    pconfig["drop_element"]                    = pconfig["drop_element"];
                    pconfig["file_data_name"]                  = imgId + pconfig["file_data_name"];
                    pconfig["multipart_params"]["imgid"]       = imgId;
                    pconfig["multipart_params"]["_ajax_nonce"] = $this.find( ".ajaxnonceplu" ).attr( "id" ).replace( "ajaxnonceplu", "" );

                    if ($this.hasClass( "plupload-upload-uic-multiple" )) {
                        pconfig["multi_selection"] = true;
                    }

                    if ($this.find( ".plupload-resize" ).exists()) {
                        var w             = parseInt( $this.find( ".plupload-width" ).attr( "id" ).replace( "plupload-width", "" ) );
                        var h             = parseInt( $this.find( ".plupload-height" ).attr( "id" ).replace( "plupload-height", "" ) );
                        pconfig["resize"] = {
                            width: w,
                            height: h,
                            quality: 100
                        };
                    }

                    var uploader = new plupload.Uploader( pconfig );

                    uploader.bind(
                        'Init',
                        function (up) {

                            var uploaddiv = $( '#plupload-upload-ui' );

                            // Add classes and bind actions:
                            if (up.features.dragdrop) {
                                uploaddiv.addClass( 'drag-drop' );
                                $( '#drag-drop-area' )
                                    .bind(
                                        'dragover.wp-uploader',
                                        function () {
                                            uploaddiv.addClass( 'drag-over' );
                                        }
                                    )
                                    .bind(
                                        'dragleave.wp-uploader, drop.wp-uploader',
                                        function () {
                                            uploaddiv.removeClass( 'drag-over' );
                                        }
                                    );

                            } else {
                                uploaddiv.removeClass( 'drag-drop' );
                                $( '#drag-drop-area' ).unbind( '.wp-uploader' );
                            }

                        }
                    );

                    uploader.init();

                    // a file was added in the queue
                    uploader.bind(
                        'FilesAdded',
                        function (up, files) {
                            $.each(
                                files,
                                function (i, file) {
                                    $this.find( '.filelist' ).append(
                                        '<div class="file" id="' + file.id + '"><b>' +

                                        file.name + '</b> (<span class="ftg-file-size">' + plupload.formatSize( 0 ) + '</span>/' + plupload.formatSize( file.size ) + ') ' + ' <span class="ftg-finishing"></span><div class="fileprogress"></div></div>'
                                    );
                                }
                            );

                            up.refresh();
                            up.start();
                        }
                    );

                    uploader.bind(
                        'UploadProgress',
                        function (up, file) {

                            $( '#' + file.id + " .fileprogress" ).width( file.percent + "%" );
                            $( '#' + file.id + " span.ftg-file-size" ).html( plupload.formatSize( parseInt( file.size * file.percent / 100 ) ) );
                            // $('#' + file.id + " span").html(file.percent);
                            setTimeout(
                                function(){
                                    if (file.percent == 100) {
                                        $( '#' + file.id + " span.ftg-finishing" ).html( "<strong>Finishing up, please be patient</strong>" );
                                    }
                                },
                                5000
                            );

                        }
                    );

                    // a file was uploaded
                    uploader.bind(
                        'FileUploaded',
                        function (up, file, response) {

                            $( '#' + file.id ).fadeOut();
                            $( '#' + file.id ).addClass( 'ftg-upload-complete' );
                            response = JSON.parse( response["response"] );
                            //console.log( response['url'] );
                            // add url to the hidden field
                            if ($this.hasClass( "plupload-upload-uic-multiple" )) {
                                // multiple
                                var v1 = $.trim( $( "#" + imgId ).val() );
                                if (v1) {
                                    // v1 = v1 + "," + response['url'];
                                    v1 = response['url'];
                                } else {
                                    v1 = response['url'];
                                }
                                $( "#" + imgId ).val( v1 );
                            } else {
                                // single
                                $( "#" + imgId ).val( response['url'] + "" );
                            }

                            // show thumb
                            plu_show_thumbs( imgId, response['id'] );

                            // We add a class to the wrapper when the create woocommerce create on image upload is checked.
                            // So here we are checking to see if that class is on the wrapper and if so then run this function to create the product now that the image is about to be complete.
                            // I'm sure in the future there may be a quicker way to do this but If your server has decent speed you should be able to do over 400 images with products in under 30 minutes.
                            // The reason the process takes so long is because we are duplicating each product post and adding in the info, then doing that over and over. Servers with low memory or timelimits will suffer the most.
                            // One server I tested took over 3 hours to do 436 products. So if you really want to sell photos and get things done quickly then make sure your server is as nice as your Camera.
                            // We do this check with either the hassclass method or if the checkbox is checked because if you are using ajax to save the page the class may not have been applied yet.
                            var ftgGlobalValue           = jQuery( "select#fts_image_to_woo_model_prod" ).val();
                            var ftgLandscapeValue        = jQuery( "select#fts_landscape_to_woo_model_prod" ).val();
                            var ftgSquareValue           = jQuery( "select#fts_square_to_woo_model_prod" ).val();
                            var ftgPortraitValue         = jQuery( "select#fts_portrait_to_woo_model_prod" ).val();
                            var ftgorientationValueCheck = jQuery( "#fts_smart_image_orient_prod" ).is( ':checked' );

                            console.log( ftgGlobalValue );
                            if (jQuery( "#uploaderSection" ).hasClass( "ftg-auto-create-product-on-upload" ) || jQuery( "#fts_auto_image_woo_prod" ).is( ':checked' )) {

                                if (ftgGlobalValue || ftgLandscapeValue && ftgSquareValue && ftgPortraitValue && ftgorientationValueCheck) {
                                    fts_image_to_woo_on_upload( response['id'], jQuery( '#img1plupload-thumbs' ).attr( 'data-post-id' ) );
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).removeClass( 'error' );
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).removeClass( 'ftg-block' );
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).html( '' );
                                } else {
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).addClass( 'error' );
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).addClass( 'ftg-block' );
                                    jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).html( ftg_woo.must_have_option_selected_to_create_products + '<div class="ft-gallery-notice-close"></div>' );
                                    console.log( 'error' );
                                }
                            } else {
                                // we check to see if we are just uploading images by dragging them
                                // and when all the divs in the container have display none we then set our success message
                                var allDivs     = jQuery( '.file' );
                                var classedDivs = jQuery( '.file.ftg-upload-complete' );

                                var allDivsHaveClass = (allDivs.length === classedDivs.length);
                                if (allDivsHaveClass) {
                                    // we setTimout because sometimes the last image will lag and want to show this message twice
                                    // so we try to stop that by holing out a second more before showing the message
                                    setTimeout(
                                        function(){
                                            jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).addClass( 'updated' );
                                            jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).addClass( 'ftg-block' );
                                            jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).html( updatefrombottomParams.images_complete_on_auto_upload );
                                            jQuery( '#ftg-tab-content1 .ft-gallery-notice' ).append( '<div class="ft-gallery-notice-close"></div>' );
                                            console.log( 'made it' );
                                        },
                                        500
                                    );
                                    // alert('made it');
                                }
                            }

                        }
                    );
                }
            );
        }
    }
);

function plu_show_thumbs(imgId, attachmentId) {
    // alert(attachmentId);
    var $       = jQuery;
    var thumbsC = $( ".thumb" );
    // thumbsC.hide();
    // $(".thumb-new");
    // get urls
    var attachID = 'list_item_' + attachmentId;
    var imagesS  = $( "#" + imgId ).val();
    var images   = imagesS.split( "," );

    for (var i = 0; i < images.length; i++) {
        //console.log( "#" + attachmentId + ":visible" );
        //console.log( images );
        if (images[i]) {

            // if(jQuery("#" + attachID).length === 0) {
            // alert(images[i]);
            var thumb = $( '<li class="thumb thumb-new" id="' + attachID + '"><img src="' + images[i] + '" alt="" /><div class="thumbi"><a id="thumbremovelink' + imgId + i + '" href="#" style="display: none">Delete</a></div> <div class="clear"></div></li>' );

            $( "#img1plupload-thumbs" ).prepend( thumb );
            // }
            thumb.find( "a" ).click(
                function () {
                    var ki      = $( this ).attr( "id" ).replace( "thumbremovelink" + attachID, "" );
                    ki          = parseInt( ki );
                    var kimages = [];
                    imagesS     = $( "#" + attachID ).val();
                    images      = imagesS.split( "," );
                    for (var j = 0; j < images.length; j++) {
                        if (j != ki) {
                            kimages[kimages.length] = images[j];
                        }
                    }
                    // $("#" + imgId).val(kimages.join());
                    // plu_show_thumbs(imgId);
                    return false;
                }
            );

        }
    }
    // if (images.length > 1) {
    jQuery( "#img1plupload-thumbs" ).sortable(
        {
            items: 'li',
            opacity: 1,
            cursor: 'move',
            update: function () {
                var id   = jQuery( this ).data( 'post-id' );
                var ordr = jQuery( this ).sortable( "serialize" ) + '&action=list_update_order';
                jQuery.post(
                    ajaxurl,
                    ordr,
                    function (response) {
                        console.log( response );
                        console.log( id );
                        // console.log(ordr);
                        // plu_show_thumbs(imgId);
                    }
                );

            }
        }
    );

    // jQuery('#publish').click(function(){
    // jQuery('.gallery-edit-question-individual-image-product').toggle();
    // jQuery('.gallery-edit-question-download-gallery, .gallery-edit-question-digital-gallery-product').hide();
    // });
    thumbsC.disableSelection();
    // }
}