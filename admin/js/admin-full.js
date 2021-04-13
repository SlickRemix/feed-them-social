// SLICKREMIX START OUR CUSTOM POPUPS
jQuery( document ).ready(
    function () {

        jQuery( '#fts_smart_image_orient_prod' ).appendTo( '.fts_smart_image_orient_prod_holder' );

        // WooCommerce Tab where if the orientation checkbox is checked we show an overlay over the global
        // product tab so as not to confuse people of its purpose.
        var ftg_orientation_checkbox = jQuery( '#fts_smart_image_orient_prod' );
        // On click action
        jQuery( 'body' ).on(
            'click',
            '#fts_smart_image_orient_prod',
            function () {
                if (ftg_orientation_checkbox.is( ':checked' )) {
                    jQuery( ".ftg-global-model-product-wrap .ftg-settings-overlay" ).show();
                } else {
                    jQuery( ".ftg-global-model-product-wrap .ftg-settings-overlay" ).hide();
                }
            }
        );
        // On page loaded action
        if (ftg_orientation_checkbox.is( ':checked' )) {
            jQuery( ".ftg-global-model-product-wrap .ftg-settings-overlay" ).show();
        } else {
            jQuery( ".ftg-global-model-product-wrap .ftg-settings-overlay" ).hide();
        }

        jQuery( '#fts_image_to_woo_model_prod' ).on(
            'change',
            function (e) {

                if (jQuery( '#fts_smart_image_orient_prod' ).is( ":checked" )) {
                    jQuery( '#fts_smart_image_orient_prod' ).prop( 'checked', false )
                }

                var ftgGlobalValue = jQuery( "select#fts_image_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-settings-overlay-smart-images' ).show();
                } else {
                    jQuery( '.ftg-settings-overlay-smart-images' ).hide();
                }
            }
        );

        var ftgGlobalValue = jQuery( "select#fts_image_to_woo_model_prod" ).val();
        // console.log(ftgGlobalValue);
        if (ftgGlobalValue) {
            jQuery( '.ftg-settings-overlay-smart-images' ).show();
        } else {
            jQuery( '.ftg-settings-overlay-smart-images' ).hide();
        }

        jQuery( '#fts_zip_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_zip_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '#ftg-tab-content1 .ft-gallery-zip-gallery' ).attr( 'disabled', false );

                } else {
                    jQuery( '#ftg-tab-content1 .ft-gallery-zip-gallery' ).attr( 'disabled', true );
                }
            }
        );

        var ftgGlobalValue = jQuery( "select#fts_zip_to_woo_model_prod" ).val();
        // console.log(ftgGlobalValue);
        if (ftgGlobalValue) {
            jQuery( '#ftg-tab-content1 .ft-gallery-zip-gallery' ).attr( 'disabled', false );
        } else {
            jQuery( '#ftg-tab-content1 .ft-gallery-zip-gallery' ).attr( 'disabled', true );
        }

        jQuery( '#fts_image_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_image_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-global-model-product-wrap .ftg-hide-me' ).hide();
                    jQuery( '.ftg-js-edit-button-holder' ).html( '<div class="ft-gallery-edit-woo-model-prod ftg-fadein" style="display: none"><a href="' + ftg_woo.admin_url + 'post.php?post=' + ftgGlobalValue + '&action=edit" target="_blank">' + ftg_woo.global_product_option + '</a></div>' );
                    jQuery( '.ftg-global-model-product-wrap .ftg-fadein' ).fadeIn();

                } else {
                    jQuery( '.ftg-global-model-product-wrap .ftg-hide-me, .ftg-global-model-product-wrap .ftg-fadein' ).fadeOut();
                }
            }
        );

        jQuery( '#fts_landscape_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_landscape_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-landscape-option-wrapper .ftg-hide-me' ).hide();
                    jQuery( '.ftg-js-edit-button-holder-landscape' ).html( '<div class="ft-gallery-edit-woo-model-prod ftg-fadein" style="display: none"><a href="' + ftg_woo.admin_url + 'post.php?post=' + ftgGlobalValue + '&action=edit" target="_blank">' + ftg_woo.global_product_option + '</a></div>' );
                    jQuery( '.ftg-landscape-option-wrapper .ftg-fadein' ).fadeIn();
                } else {
                    jQuery( '.ftg-landscape-option-wrapper .ftg-hide-me, .ftg-landscape-option-wrapper .ftg-fadein' ).fadeOut();
                }
            }
        );

        jQuery( '#fts_square_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_square_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-square-option-wrapper .ftg-hide-me' ).hide();
                    jQuery( '.ftg-js-edit-button-holder-square' ).html( '<div class="ft-gallery-edit-woo-model-prod ftg-fadein" style="display: none"><a href="' + ftg_woo.admin_url + 'post.php?post=' + ftgGlobalValue + '&action=edit" target="_blank">' + ftg_woo.global_product_option + '</a></div>' );
                    jQuery( '.ftg-square-option-wrapper .ftg-fadein' ).fadeIn();
                } else {
                    jQuery( '.ftg-square-option-wrapper .ftg-hide-me, .ftg-square-option-wrapper .ftg-fadein' ).fadeOut();
                }
            }
        );
        jQuery( '#fts_portrait_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_portrait_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-portrait-option-wrapper .ftg-hide-me' ).hide();
                    jQuery( '.ftg-js-edit-button-holder-portrait' ).html( '<div class="ft-gallery-edit-woo-model-prod ftg-fadein" style="display: none"><a href="' + ftg_woo.admin_url + 'post.php?post=' + ftgGlobalValue + '&action=edit" target="_blank">' + ftg_woo.global_product_option + '</a></div>' );
                    jQuery( '.ftg-portrait-option-wrapper .ftg-fadein' ).fadeIn();
                } else {
                    jQuery( '.ftg-portrait-option-wrapper .ftg-hide-me, .ftg-portrait-option-wrapper .ftg-fadein' ).fadeOut();
                }
            }
        );

        jQuery( '#fts_zip_to_woo_model_prod' ).on(
            'change',
            function (e) {
                var ftgGlobalValue = jQuery( "select#fts_zip_to_woo_model_prod" ).val();
                // console.log(ftgGlobalValue);
                if (ftgGlobalValue) {
                    jQuery( '.ftg-zip-option-wrapper .ftg-hide-me' ).hide();
                    jQuery( '.ftg-js-edit-button-holder-zip' ).html( '<div class="ft-gallery-edit-woo-model-prod ftg-fadein" style="display: none"><a href="' + ftg_woo.admin_url + 'post.php?post=' + ftgGlobalValue + '&action=edit" target="_blank">' + ftg_woo.global_product_option + '</a></div>' );
                    jQuery( '.ftg-zip-option-wrapper .ftg-fadein' ).fadeIn();
                } else {
                    jQuery( '.ftg-zip-option-wrapper .ftg-hide-me, .ftg-zip-option-wrapper .ftg-fadein' ).fadeOut();
                }
            }
        );

        jQuery( '#fts-gallery-checkAll' ).toggle(
            function (event) {
                event.preventDefault(); // stop post action
                jQuery( '#img1plupload-thumbs input:checkbox' ).attr( 'checked', 'checked' );
                jQuery( this ).html( 'Clear All' )
                jQuery( ".wp-core-ui .button-primary.ft-gallery-download-selection-option" ).show();
                var ftgGlobalValue           = jQuery( "select#fts_image_to_woo_model_prod" ).val();
                var ftgLandscapeValue        = jQuery( "select#fts_landscape_to_woo_model_prod" ).val();
                var ftgSquareValue           = jQuery( "select#fts_square_to_woo_model_prod" ).val();
                var ftgPortraitValue         = jQuery( "select#fts_portrait_to_woo_model_prod" ).val();
                var ftgorientationValueCheck = jQuery( "#fts_smart_image_orient_prod" ).is( ':checked' );
                var ftgchechecked            = jQuery( ".ft-gallery-myCheckbox input" ).is( ':checked' );

                if (ftgGlobalValue && ftgchechecked === true || ftgLandscapeValue && ftgSquareValue && ftgPortraitValue && ftgorientationValueCheck && ftgchechecked === true) {
                    jQuery( '#ftg-tab-content1 .ft-gallery-create-woo' ).attr( 'disabled', false );
                }

            },
            function () {
                jQuery( '#img1plupload-thumbs input:checkbox' ).removeAttr( 'checked' );
                jQuery( ".wp-core-ui .button-primary.ft-gallery-download-selection-option" ).hide();
                jQuery( '#ftg-tab-content1 .ft-gallery-create-woo' ).attr( 'disabled', true );
                jQuery( this ).html( 'Select All' );
            }
        );

        jQuery( '#img1plupload-thumbs img, #img1plupload-thumbs .ft-gallery-myCheckbox' ).toggle(
            function (event) {
                event.preventDefault(); // stop post action
                if (jQuery( "#img1plupload-thumbs input" ).length > 0) {
                    jQuery( ".wp-core-ui .button-primary.ft-gallery-download-selection-option" ).show();
                }

                jQuery( this ).parents( '.thumb' ).find( 'input:checkbox' ).attr( 'checked', 'checked' );

                var ftgGlobalValue           = jQuery( "select#fts_image_to_woo_model_prod" ).val();
                var ftgLandscapeValue        = jQuery( "select#fts_landscape_to_woo_model_prod" ).val();
                var ftgSquareValue           = jQuery( "select#fts_square_to_woo_model_prod" ).val();
                var ftgPortraitValue         = jQuery( "select#fts_portrait_to_woo_model_prod" ).val();
                var ftgorientationValueCheck = jQuery( "#fts_smart_image_orient_prod" ).is( ':checked' );
                var ftgchechecked            = jQuery( ".ft-gallery-myCheckbox input" ).is( ':checked' );

                if (ftgGlobalValue && ftgchechecked === true || ftgLandscapeValue && ftgSquareValue && ftgPortraitValue && ftgorientationValueCheck && ftgchechecked === true) {
                    jQuery( '#ftg-tab-content1 .ft-gallery-create-woo' ).attr( 'disabled', false );
                }
            },
            function () {
                jQuery( this ).parents( '.thumb' ).find( 'input:checkbox' ).removeAttr( 'checked' );
                if ( ! jQuery( "#img1plupload-thumbs input" ).is( ":checked" )) {

                    jQuery( ".wp-core-ui .button-primary.ft-gallery-download-selection-option" ).hide();
                    jQuery( '#ftg-tab-content1 .ft-gallery-create-woo' ).attr( 'disabled', true );
                }
            }
        );

        // SLICKREMIX: MUST HAVE THIS IN PLACE TO BE ABLE TO CHECK WHAT KIND OF VIDEOS ARE BEING CLICKED ON!
        jQuery( 'body' ).on(
            'click',
            '#ftg-photo-prev, #ftg-photo-next, .ft-gallery-popup .mfp-image-holder .fts-popup-image-position',
            function (e) {
                // alert('test');
                jQuery( "body" ).addClass( "fts-using-arrows" );

                setTimeout(
                    function () {

                        if (/fbcdn.net/i.test( jQuery( ".fts-iframe-popup-element" ).attr( "src" ) ) || /scontent.cdninstagram.com/i.test( jQuery( ".fts-iframe-popup-element" ).attr( "src" ) )) {

                            // alert(jQuery(".fts-iframe-popup-element").attr("src"));
                            jQuery( "body" ).addClass( "fts-video-iframe-choice" );
                            jQuery( ".fts-video-popup-element" ).show();
                            jQuery( ".fts-iframe-popup-element" ).attr( "src", "" ).hide();

                        } else {
                            // alert('wtf');
                            jQuery( "body" ).removeClass( "fts-video-iframe-choice, .fts-using-arrows" );
                            jQuery( ".fts-video-popup-element" ).attr( "src", "" ).hide();
                            jQuery( ".fts-iframe-popup-element" ).show();
                        }
                    },
                    10
                );
            }
        );

        class FtgPopup {

            NextPrev(items_array, index, direction){

                var items_total = items_array.length;

                // If 'current' direction is requested!
                if ('current' === direction) {
                    var info = items_array[index];

                    return info;
                }

                // If 'previous' direction is requested!
                if ('previous' === direction) {
                    if ( '0' === index) {
                        index    = items_total;
                        var info = items_array[(items_total) % items_total];
                    } else {
                        var info = items_array[(index + items_total - 1) % items_total];
                    }

                    return info;
                }

                // If 'next' direction is requested!
                if ('next' === direction) {
                    let info = items_array[(index + 1) % items_total];

                    return info;
                }
            }

            // Create the Tags in Popup and add them to the UL!
            CreateTags(tags, imageid)
            {
                if ( jQuery( 'div.ft-gallery-popup-form' ).hasClass( 'ftg-premium-active' ) ) {
                    if (tags !== 'no tags') {
                        for (var tag of tags) {
                            jQuery( '.popup-ftg-tags ul.tagchecklist' ).show();
                            jQuery( ".popup-ftg-tags ul.tagchecklist" ).append( '<li class="ftg-term-li" data-termli="' + tag.term_id + '"><button type="button" id="delete-media-term-' + tag.term_id + '" data-termid="' + tag.term_id + '" data-imageid="' + imageid + '" class="delete-media-term ntdelbutton"><span class="remove-tag-icon" aria-hidden="true"></span><span class="screen-reader-text">Remove Tag: ' + tag.name + '</span></button>&nbsp; ' + tag.name + '</li>' );
                        }

                        // Hide No Tags Message!
                        jQuery( '.ftg-tags-none' ).hide();
                    } else {
                        // Hide No Tags Message!
                        jQuery( '.popup-ftg-tags ul.tagchecklist' ).hide();
                        jQuery( '.ftg-tags-none' ).show();
                    }
                }
            }

            // Set Attribute for Image ID!
            SetImageAttr(imageid) {
                if ( jQuery('div.ft-gallery-popup-form').hasClass('ftg-premium-active') ) {
                    document.querySelector('.popup-ftg-tags button.save-media-term').setAttribute('data-imageid', imageid);

                }
            }

            // Update information in popup!
            UpdatePopInfo(jsArray, save_btn = null) {
                jQuery( '.fts-gallery-title' ).val( jsArray['title'] );
                jQuery( '.fts-gallery-alttext' ).show().val( jsArray['alt'] );
                jQuery( '.fts-gallery-description' ).show().val( jsArray['description'] );

                if (true !== save_btn) {
                    jQuery( '.fts-gallery-tags-edit-wrap' ).val( jsArray['tags'] );
                }
            }

            // Update information in popup!
            PopAjax(action, id, nonce) {
                jQuery.ajax(
                    {
                        data: {
                            'action': action,
                            // submit our values to function simple_das_fep_add_post
                            'id': id,
                            'nonce': nonce
                        },
                        type: 'post',
                        url: ssAjax.ajaxurl,
                        success: (response) => {

                            // Complete Sucess
                            let jsArray = JSON.parse( response );

                            // Update Popup information for image!
                            this.UpdatePopInfo( jsArray );
                            if ( jQuery('div.ft-gallery-popup-form').hasClass('ftg-premium-active') ) {
                                let tags = jsArray['tags'];

                                // Create tags and append to tags list!
                                this.CreateTags(tags, id);
                            }
                        },
                        error: () => {
                            alert( 'Error, please contact us at https://www.slickremix.com/support/ for help.' )
                        }
                    }
                ); // end of ajax()
                return false;
            }
        }

        var FtgPopupClass = new FtgPopup();

        jQuery( '.plupload-thumbs' ).each(
            function () {
                var $container  = jQuery( this );
                var $imageLinks = $container.find( 'button.ft-gallery-edit-img-popup' );
                var $items      = [];

                for (var $item of $imageLinks) {

                    var magItem = {
                        imgid: $item.dataset.id,
                        src: $item.dataset.imageurl,
                        nonce: $item.dataset.nonce,
                        type: 'image',
                        delegate: '.thumb:not(.hidden)',
                        woo_option: '',
                    };

                    if ('fts-jal-fb-vid-image' === $item.className) {
                        magItem.type = 'iframe';
                    }

                    if (jQuery( "div" ).hasClass( "ft-gallery-woo-btns-wrap-for-popup" )) {
                        magItem.woo_option = document.querySelector( '.ft-gallery-woo-btns-wrap-for-popup' ).innerHTML;
                    }

                    // SLICKREMIX: THIS ADDS THE LIKES, COMMENTS, DESCRIPTION, DATES ETC TO THE POPUP
                    magItem.title = document.querySelector( '.ft-image-id-for-popup' ).innerHTML + document.querySelector( '.ft-gallery-popup-form' ).innerHTML + magItem.woo_option,

                        $items.push( magItem );
                }

                $imageLinks.magnificPopup(
                    {
                        mainClass: 'ft-gallery-popup ft-gallery-styles-popup',
                        items: $items,
                        removalDelay: 150,
                        preloader: false,
                        closeOnContentClick: false,
                        closeOnBgClick: true,
                        closeBtnInside: true,
                        showCloseBtn: false,
                        enableEscapeKey: true,
                        autoFocusLast: false,
                        gallery: {
                            enabled: true,
                            navigateByImgClick: false,
                            tCounter: '<span class="mfp-counter">%curr% of %total%</span>', // markup of counter
                            preload: [0, 1], // Will preload 0 - before current, and 1 after the current
                            arrowMarkup: '', // markup of an arrow button (slickremix = leave blank so we can show our custom buttons inside the framework)
                        },

                        callbacks: {
                            beforeOpen: function () {
                                var index = $imageLinks.index( this.st.el );
                                if (-1 !== index) {
                                    this.goTo( index );
                                }
                            },
                            open: function () {

                                jQuery( document ).off( '.mfp-gallery' );

                                if (jQuery( ".fts-popup-half .mfp-iframe-scaler" )[0]) {
                                    jQuery( ".fts-popup-image-position" ).css( "height", '591px' );
                                }
                                jQuery( window ).resize(
                                    function () {

                                        jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() );

                                        jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() );
                                    }
                                );
                                jQuery( window ).trigger( 'resize' );

                                // slickremix trick to get the poster url from a tag we are clicking and pass it to the video player.
                                // We only want to load the poster if the size is mobile because tablets and desktops can/will play video automatically on popup
                                if (matchMedia( 'only screen and (max-device-width: 736px)' ).matches) {
                                    var atagvideo   = event.target.id;
                                    var videoposter = jQuery( '#' + atagvideo ).data( 'poster' );
                                    var video       = jQuery( '.fts-fb-vid-popup video' );
                                    video.attr( 'poster', videoposter );
                                }
                                // SLICKREMIX: MUST HAVE THIS IN PLACE TO BE ABLE TO ADD THE CLASS TO BODY SO WE CAN DO ACTIONS ON OUR CUSTOM PREV AND NEXT BUTTONS
                                // alert('added fts-using-arrows class on popup open')
                                jQuery( "body" ).addClass( "fts-using-arrows" );

                            },
                            change: function () {
                                // Using Arrows!
                                if (jQuery( "body" ).hasClass( "fts-using-arrows" )) {

                                    if (jQuery( ".fts-popup-half .mfp-iframe-scaler" )[0]) {
                                        jQuery( ".fts-popup-image-position" ).css( "height", '591px' );
                                        // alert('iframe-scaler');
                                    } else {
                                        if (jQuery( ".fts-popup-image-position" ).css( "height" ) == "auto") {
                                            jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() );
                                            alert( 'image' );

                                        }
                                    }
                                }
                            },

                            imageLoadComplete: function () {
                                // fires when image in current popup finished loading
                                if (jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height()) {
                                    jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() );
                                } else {
                                    jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() );
                                }

                            },
                            markupParse: function (template, values, item) {
                                // Triggers each time when content of popup changes 
                                // console.log('Parsing:', template, values, item);
                                // SLICKREMIX: MUST HAVE THIS IN PLACE TO BE ABLE TO CHECK WHAT KIND OF VIDEOS ARE BEING CLICKED ON
                                if ( ! jQuery( "body" ).hasClass( "fts-using-arrows" )) {

                                    var ftsLinkCheck = item.src;

                                    if (/fbcdn.net/i.test( ftsLinkCheck ) && item.type !== 'image') {
                                        // alert('FB Video Change photo Trigger from MP');
                                        jQuery( "body" ).addClass( "fts-video-iframe-choice" );
                                    } else if ( ! jQuery( "body" ).hasClass( "fts-using-arrows" )) {
                                        // alert('Not using arrows open photo Trigger from MP');
                                        jQuery( "body" ).removeClass( "fts-video-iframe-choice" );
                                    }

                                }
                                // CLOSE SLICKREMIX
                            },
                            afterClose: function () {
                                jQuery( "body" ).removeClass( "fts-using-arrows" );
                                // console.log('Popup is completely closed');
                            },
                        },
                        image: {
                            markup: '' +
                                '<div class="mfp-figure"><div class="mfp-close">X</div>' +
                                '<div class="fts-popup-wrap">' +
                                '    <div class="fts-popup-half ">' +
                                '               <button title="previous" type="button" id="ftg-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>' +
                                '           <div class="fts-popup-image-position" style="height:591px;">' +
                                '                   <span class="fts-position-helper"></span><div class="mfp-img"></div>' +
                                '       </div>' +
                                '               <button title="next" type="button" id="ftg-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>' +
                                '    </div>' +
                                '<div class="fts-popup-second-half">' +
                                '<div class="mfp-bottom-bar">' +
                                '<div class="mfp-title"></div>' +
                                '<a class="fts-powered-by-text" href="https://slickremix.com" target="_blank">Powered by Feed Them Social</a>' +
                                '<div class="mfp-counter"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>', // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button

                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',

                        },
                        iframe: {
                            markup: '' +
                                '<div class="mfp-figure"><div class="mfp-close">X</div>' +
                                '<div class="fts-popup-wrap">' +
                                '    <div class="fts-popup-half ">' +
                                '               <button title="previous" type="button" id="ftg-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>' +
                                '           <div class="fts-popup-image-position">' +
                                '                           <div class="mfp-iframe-scaler"><iframe class="mfp-iframe fts-iframe-popup-element" frameborder="0" allowfullscreen></iframe><video class="mfp-iframe fts-video-popup-element" allowfullscreen autoplay controls></video>' +
                                '                           </div>' +
                                '               <button title="next" type="button" id="ftg-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>' +
                                '<script>' +
                                // SLICKREMIX: MUST HAVE THIS IN PLACE TO BE ABLE TO CHECK WHAT KIND OF VIDEOS ARE BEING CLICKED ON WHEN FIRST LOADED, AFTER THEY ARE LOADED REFER TO THE CLICK FUNCTION FOR THE ERRORS ABOVE
                                'if(jQuery("body").hasClass("fts-video-iframe-choice")){jQuery(".fts-iframe-popup-element").attr("src", "").hide(); } else if(!jQuery("body").hasClass("fts-using-arrows")){jQuery(".fts-video-popup-element").attr("src", "").hide(); };  jQuery(".ft-gallery-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")});</script>' +
                                '       </div>' +
                                '    </div>' +
                                '<div class="fts-popup-second-half">' +
                                '<div class="mfp-bottom-bar">' +
                                '<div class="mfp-title"></div>' +
                                '<a class="fts-powered-by-text" href="https://slickremix.com" target="_blank">Powered by Feed Them Social</a>' +
                                '<div class="mfp-counter"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>', // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button

                            srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".  
                        }
                    }
                );
            }
        );

        // Save Button is clicked in Popup!
        jQuery( document ).on(
            "click",
            "#ft-gallery-edit-img-ajax",
            function () {
                event.preventDefault(); // stop post action

                var inst_items = jQuery.magnificPopup.instance;

                var item_info = FtgPopupClass.NextPrev( inst_items.items, inst_items.index, 'current' ),
                    id        = item_info.data.imgid,
                    nonce     = item_info.data.nonce;

                var data = {
                    'action': "fts_edit_image_ajax",
                    // submit our values to function simple_das_fep_add_post
                    'id': id,
                    'nonce': nonce,
                    'title': jQuery( document ).find( '.fts-gallery-title' ).val(),
                    'alttext': jQuery( document ).find( '.fts-gallery-alttext' ).val(),
                    'description': jQuery( document ).find( '.fts-gallery-description' ).val()
                };

                jQuery.ajax(
                    {
                        data: data,
                        type: 'POST',
                        url: ssAjax.ajaxurl,
                        beforeSend: () => {
                            // alert('before');
                            jQuery( '.ft-submit-wrap' ).append( '<div class="fa fa-cog fa-spin fa-3x fa-fw ft-gallery-loader"></div>' );
                            jQuery( ".ft-gallery-success" ).remove();
                        },
                        success: (response) => {

                            // Complete Sucess
                            let jsArray = JSON.parse( response );

                            // Update Popup information for image!
                            FtgPopupClass.UpdatePopInfo( jsArray, true );

                            jQuery( '.ft-gallery-loader' ).remove();

                            jQuery( '.ft-submit-wrap' ).append( '<div class="fa fa-check-circle fa-3x fa-fw ft-gallery-success" ></div>' );

                            setTimeout( "jQuery('.ft-gallery-success').fadeOut();", 2000 );

                        },
                        error: () => {
                            alert( 'Error, please contact us at http://slickremix.com/support-forum for help.' )
                        }
                    }
                );
                return false;
            }
        ); // End Save Button!

        // Next Button is clicked in Popup!
        jQuery( document ).on(
            "click",
            ".fts-popup-image-position, #ftg-photo-next",
            () => {
                var inst_items = jQuery.magnificPopup.instance;
                let item_info  = FtgPopupClass.NextPrev( inst_items.items, inst_items.index, 'next' ),
                    id             = item_info.data.imgid,
                    nonce          = item_info.data.nonce;
                inst_items.next();
                jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() );
                // Set Attribute for Image ID!
                FtgPopupClass.SetImageAttr( id );
                FtgPopupClass.PopAjax( 'fts_update_image_information_ajax', id , nonce );
            }
        );// End Next Button!

        // Previous Button is clicked in Popup!
        jQuery( document ).on(
            "click",
            "#ftg-photo-prev",
            () => {
                var inst_items = jQuery.magnificPopup.instance;
                let item_info  = FtgPopupClass.NextPrev( inst_items.items, inst_items.index, 'previous' ),
                    id                 = item_info.data.imgid,
                    nonce              = item_info.data.nonce;
                inst_items.prev();
                jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() );
                // Set Attribute for Image ID!
                FtgPopupClass.SetImageAttr( id );
                FtgPopupClass.PopAjax( 'fts_update_image_information_ajax', id , nonce );
            }
        ); // End Previous Button!

        // Edit Thumb button is clicked!
        jQuery( ".ft-gallery-edit-thumb-btn" ).on(
            "click",
            ".ft-gallery-edit-img-popup",
            function () {

                let id    = this.dataset.id,
                    nonce = this.dataset.nonce;

                // Set Attribute for Image ID!
                FtgPopupClass.SetImageAttr( id );

                FtgPopupClass.PopAjax( 'fts_update_image_information_ajax', id , nonce );
            }
        );

        jQuery( ".ft-gallery-remove-thumb-btn" ).on(
            "click",
            ".ft-gallery-remove-img-ajax",
            function (event) {
                event.preventDefault(); // stop post action
                var id         = jQuery( this ).data( 'id' );
                var nonce      = jQuery( this ).data( 'nonce' );
                var remove     = jQuery( this ).data( 'ft-gallery-img-remove' );
                var thisDelete = jQuery( this );

                jQuery.ajax(
                    {
                        data: {
                            'action': "fts_update_image_ajax",
                            'id': id,
                            'nonce': nonce,
                            'fts_img_remove': remove
                        },
                        type: 'POST',
                        url: ssAjax.ajaxurl,

                        success: function (response) {
                            // Complete Sucess
                            jQuery( thisDelete ).parents( '.thumb' ).hide();
                        },
                        error: function () {
                            alert( 'Error, please contact us at https://www.slickremix.com/support/ for help.' )
                        }
                    }
                ); // end of ajax()
                return false;
            }
        ); // end of form.submit

        jQuery( ".ft-gallery-delete-thumb-btn" ).on(
            "click",
            ".ft-gallery-force-delete-img-ajax",
            function (event) {
                event.preventDefault(); // stop post action

                var r = confirm( 'You are about to permanently delete this item from your site.\nThis action cannot be undone.\n\n"Cancel" to stop, "OK" to delete.' );
                if (r == true) {
                    txt = "You pressed OK!";
                } else {
                    return false;
                }

                var id         = jQuery( this ).data( 'id' );
                var nonce      = jQuery( this ).data( 'nonce' );
                var thisDelete = jQuery( this );

                jQuery.ajax(
                    {
                        data: {
                            'action': "fts_delete_image_ajax",
                            // submit our values to function simple_das_fep_add_post
                            'id': id,
                            'nonce': nonce
                        },
                        type: 'POST',
                        url: ssAjax.ajaxurl,
                        success: function (response) {
                            // Complete Sucess
                            // console.log('Well Done and got this from sever: ' + JSON.parse(response));
                            jQuery( thisDelete ).parents( '.thumb' ).remove();

                        },
                        error: function () {
                            alert( 'Error, please contact us at http://slickremix.com/support-forum for help.' )
                        }
                    }
                ); // end of ajax()
                return false;
            }
        ); // end of form.submit

        function get_tinymce_content(id) {
            var content;
            var inputid  = 'editpost';
            var editor   = tinyMCE.get( inputid );
            var textArea = jQuery( 'textarea#' + inputid );
            if (textArea.length > 0 && textArea.is( ':visible' )) {
                content = textArea.val();
                if (content == null) {
                    return false;
                }
            } else {
                content = editor.getContent();
            }
            return content;
        }
    }
); // close document ready
