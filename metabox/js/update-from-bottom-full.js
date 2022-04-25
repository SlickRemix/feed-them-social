(function ($) {
    "use strict";

    $( document ).ready(function() {
        // Button markup depending on post/page status
        if($('#publish, #publishing-action input[type=submit]').val() == updatefrombottomParams.publish) {
            $('<div class="updatefrombottom" ><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.publish+'</a></div>').appendTo(".tab-content");
        } else {
            $('<div class="updatefrombottom"><a class="button button-totop">'+updatefrombottomParams.totop+'</a><a class="button button-primary button-large">'+updatefrombottomParams.update+'</a></div>').appendTo(".tab-content");
        }

        // DOM Caching
        var elements =  {
            box    : $('.updatefrombottom'),
            heart  : $('#jsc-heart'),
            update  : $('.updatefrombottom .button-primary'),
            publish: $('#publish, #publishing-action input[type=submit]'),
            totop : $('.updatefrombottom .button-totop')
        };

        elements.box.hide();

        // Publish/Update content
        elements.update.on('click', function(e){

            if($(this).text() == updatefrombottomParams.publish) {
                $(this).text(updatefrombottomParams.publishing);

            } else {
                $(this).text(updatefrombottomParams.updating);
            }

            elements.publish.trigger('click');

            e.preventDefault();

        });
        // Scroll to top
        elements.totop.on('click', function(event){
            event.preventDefault();
            $('html, body').animate({scrollTop : 0}, 600);
        });


        $.fn.isInViewport = function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();

            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            return elementBottom > viewportTop && elementTop < viewportBottom;
        };

        $(window).on('resize scroll', function() {
            if ($('#publish, #publishing-action input[type=submit]').isInViewport()) {
                elements.box.hide();
            } else {
                // do something else
                elements.box.show();
            }
        });

    });
}(jQuery));