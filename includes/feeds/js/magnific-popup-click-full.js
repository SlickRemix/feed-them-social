function loadFTGGalleryDeepLink()
{
    // https://gist.github.com/sadortun/69a9fa854bf0ca3a4891/#gistcomment-1713643
    var prefix = "#ftg-image-";
    var h      = location.hash;

    if (document.g_magnific_hash_loaded === undefined && h.indexOf(prefix) === 0) {
        h = h.substr(prefix.length);
        var $img = jQuery('*[data-image_id="' + h + '"]');

        if ($img.length) {
            document.g_magnific_hash_loaded = true;
            $img.get(0).click();
        }
    }
}


jQuery( document ).ready(
    function() {
        var e                                    = jQuery.magnificPopup.instance;
        jQuery( "body" ).on(
            "click",
            "#ftg-photo-prev",
            function() {
                e.prev(), jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() )
            }
        ), jQuery( "body" ).on(
            "click",
            "#ftg-photo-next",
            function() {
                e.next(), jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() )
            }
        ), jQuery( "body" ).on(
            "click",
            ".fts-facebook-popup .mfp-image-holder .fts-popup-image-position",
            function() {
                e.next(), jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() )
            }
        ), jQuery( "body" ).on(
            "click",
            "#ftg-photo-prev, #ftg-photo-next, .fts-facebook-popup .mfp-image-holder .fts-popup-image-position",
            function(e) {
                jQuery( "body" ).addClass( "fts-using-arrows" ), setTimeout(
                    function() {
                        jQuery.fn.ftsShare(), /fbcdn.net/i.test( jQuery( ".fts-iframe-popup-element" ).attr( "src" ) ) || /scontent.cdninstagram.com/i.test( jQuery( ".fts-iframe-popup-element" ).attr( "src" ) ) ? (jQuery( "body" ).addClass( "fts-video-iframe-choice" ), jQuery( ".fts-video-popup-element" ).show(), jQuery( ".fts-iframe-popup-element" ).attr( "src", "" ).hide()) : (jQuery( "body" ).removeClass( "fts-video-iframe-choice, .fts-using-arrows" ), jQuery( ".fts-video-popup-element" ).attr( "src", "" ).hide(), jQuery( ".fts-iframe-popup-element" ).show())
                    },
                    10
                )
            }
        ), jQuery.fn.slickWordpressPopUpFunction = function() {
            jQuery( ".ft-wp-gallery" ).each(
                function() {
                    var e = jQuery( this ).find( ".ft-gallery-link-popup-click-action" ),
                        t     = [];
                    e.each(
                        function() {
                            var e = jQuery( this ),
                                p     = "image";
                            e.hasClass( "fts-jal-fb-vid-image" ) && (p = "iframe");
                            var o                                      = {
                                src: e.attr( "href" ),
                                type: p
                            };
                            if (jQuery( e ).find( "div" ).hasClass( "ft-image-overlay" )) {
                                var s = jQuery( this ).parents( ".fts-feed-type-wp_gallery" ).find( ".ft-image-overlay" ).html();
                            } else {
                                s = "";
                            }
                            if (jQuery( "div" ).hasClass( "fts-mashup-count-wrap" )) {
                                var i = jQuery( this ).parents( ".fts-feed-type-wp_gallery" ).find( ".fts-mashup-count-wrap" ).html();
                            } else {
                                i = "";
                            }
                            if (jQuery( "div" ).hasClass( "ftg-varation-for-popup" )) {
                                var r = jQuery( this ).parents( ".fts-feed-type-wp_gallery" ).find( ".ftg-varation-for-popup" ).html();
                            } else {
                                r = "";
                            }
                            o.title = jQuery( this ).parents( ".fts-feed-type-wp_gallery" ).find( ".ft-text-for-popup" ).html() + r + i + s, t.push( o )
                        }
                    ), e.magnificPopup(
                        {
                            mainClass: "ft-gallery-popup ft-gallery-styles-popup ft-wp-gallery",
                            items: t,
                            removalDelay: 150,
                            preloader: ! 1,
                            closeOnContentClick: ! 1,
                            closeOnBgClick: ! 0,
                            closeBtnInside: ! 0,
                            showCloseBtn: ! 1,
                            enableEscapeKey: ! 0,
                            autoFocusLast: ! 1,
                            gallery: {
                                enabled: ! 0,
                                navigateByImgClick: ! 0,
                                tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
                                preload: [0, 1],
                                arrowMarkup: ""
                            },
                            type: "image",
                            callbacks: {
                                beforeOpen: function() {
                                    var t = e.index( this.st.el ); - 1 !== t && this.goTo( t )
                                },
                                open: function() {
                                    console.log( 'Popup is opened' );
                                    // we are loading logo in the right panel, but need it in the image panel so we clone it and append it where we want it.
                                    jQuery( '.fts-popup-second-half .fts-watermark-inside' ).clone().appendTo( '.fts-popup-half' );

                                    // make it so you can't just drag the image to your desktop.
                                    window.ondragstart = function() { return false; };

                                    if (jQuery( ".fts-popup-half .mfp-iframe-scaler" )[0]) {
                                        jQuery( ".fts-popup-image-position" ).css( "height", '591px' );
                                    }
                                    if ( ! jQuery( '.ft-gallery-variations-text' ).hasClass( 'ft-gallery-js-load' )) {
                                        jQuery( window ).resize(
                                            function () {

                                                jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() );

                                                jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() );
                                            }
                                        );

                                        jQuery( window ).trigger( 'resize' );
                                    }

                                },
                                change: function() {
                                    console.log( "Content changed" ), console.log( this.content ), jQuery( "body" ).hasClass( "fts-using-arrows" ) && (jQuery( ".fts-popup-half .mfp-iframe-scaler" )[0] ? jQuery( ".fts-popup-image-position" ).css( "height", "591px" ) : "auto" == jQuery( ".fts-popup-image-position" ).css( "height" ) && (jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ), alert( "image" )));

                                },
                                imageLoadComplete: function() {

                                    if (typeof wcpaInit == 'function' ) {
                                        jQuery.wcpaInit();
                                    }

                                    if (jQuery( '.ft-gallery-popup .ft-gallery-variations-text' ).hasClass( 'ft-gallery-js-load' )) {
                                        jQuery( '.ft-gallery-popup .single_add_to_cart_button' ).addClass( 'disabled' );
                                    }
                                    jQuery( ".ft-gallery-js-load" ).hover(
                                        function() {
                                            if (jQuery( '.ft-gallery-popup .ft-gallery-variations-text' ).hasClass( 'ft-gallery-js-load' )) {
                                                jQuery.getScript( "/wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart-variation.min.js" );
                                                jQuery( '.ft-gallery-popup .ft-gallery-variations-text' ).removeClass( 'ft-gallery-js-load' );
                                            }
                                        }
                                    );
                                    jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).height() < jQuery( ".mfp-img" ).height() ? jQuery( ".fts-popup-image-position, .fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".mfp-img" ).height() ) : jQuery( ".fts-popup-second-half .mfp-bottom-bar" ).css( "height", jQuery( ".fts-popup-image-position" ).height() ), jQuery( ".mfp-title .ft-gallery-link-popup" ).on(
                                        "click",
                                        function() {
                                            jQuery( ".mfp-title .ft-gallery-share-wrap" ).toggle()
                                        }
                                    ),

                                        jQuery( ".ft-gallery-popup .plus, .ft-gallery-popup .minus" ).remove();
                                    var e = jQuery( ".ft-gallery-popup .quantity input" ),
                                        t     = parseFloat( e.attr( "max" ) ),
                                        p     = parseFloat( e.attr( "min" ) ),
                                        o     = parseInt( e.attr( "step" ), 10 ),
                                        s     = jQuery( jQuery( "<div />" ).append( e.clone( ! 0 ) ).html().replace( "number", "text" ) ).insertAfter( e );
                                    e.remove();
                                    var i = jQuery( '<input type="button" value="-" class="minus">' ).insertBefore( s ),
                                        r     = jQuery( '<input type="button" value="+" class="plus">' ).insertAfter( s );
                                    i.on(
                                        "click",
                                        function() {
                                            var e = parseInt( s.val(), 10 ) - o;
                                            e     = (e = e < 0 ? 0 : e) < p ? p : e, s.val( e ).trigger( "change" )
                                        }
                                    ), r.on(
                                        "click",
                                        function() {
                                            var e = parseInt( s.val(), 10 ) + o;
                                            e     = e > t ? t : e, s.val( e ).trigger( "change" )
                                        }
                                    )
                                },
                                markupParse: function(e, t, p) {
                                    if (console.log( "Parsing:", e, t, p ), ! jQuery( "body" ).hasClass( "fts-using-arrows" )) {
                                        var o = p.src;
                                        /fbcdn.net/i.test( o ) && "image" !== p.type ? jQuery( "body" ).addClass( "fts-video-iframe-choice" ) : jQuery( "body" ).hasClass( "fts-using-arrows" ) || jQuery( "body" ).removeClass( "fts-video-iframe-choice" )
                                    }
                                },
                                afterClose: function() {
                                    jQuery( "body" ).removeClass( "fts-using-arrows" ), console.log( "Popup is completely closed" )
                                }
                            },
                            image: {
                                markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half ">               <button title="previous" type="button" id="ftg-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position" style="height:591px;">                   <span class="fts-position-helper"></span><div class="mfp-img"></div>       </div>               <button title="next" type="button" id="ftg-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button>    </div><div class="fts-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://www.slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
                            },
                            iframe: {
                                markup: '<div class="mfp-figure"><div class="mfp-close">X</div><div class="fts-popup-wrap">    <div class="fts-popup-half ">               <button title="previous" type="button" id="ftg-photo-prev" class="mfp-arrow mfp-arrow-left mfp-prevent-close"></button>           <div class="fts-popup-image-position">                           <div class="mfp-iframe-scaler"><iframe class="mfp-iframe fts-iframe-popup-element" frameborder="0" allowfullscreen></iframe><video class="mfp-iframe fts-video-popup-element" allowfullscreen autoplay controls></video>                           </div>               <button title="next" type="button" id="ftg-photo-next" class="mfp-arrow mfp-arrow-right mfp-prevent-close"></button><script>if(jQuery("body").hasClass("fts-video-iframe-choice")){jQuery(".fts-iframe-popup-element").attr("src", "").hide(); } else if(!jQuery("body").hasClass("fts-using-arrows")){jQuery(".fts-video-popup-element").attr("src", "").hide(); };  jQuery(".ft-gallery-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")});<\/script>       </div>    </div><div class="fts-popup-second-half"><div class="mfp-bottom-bar"><div class="mfp-title"></div><a class="fts-powered-by-text" href="https://www.slickremix.com" target="_blank">Powered by Feed Them Social</a><div class="mfp-counter"></div></div></div></div></div>',
                                srcAction: "iframe_src"
                            }
                        }
                    )
                }
            )
        }, jQuery.fn.slickWordpressPopUpFunction();

        loadFTGGalleryDeepLink();
    }
);
