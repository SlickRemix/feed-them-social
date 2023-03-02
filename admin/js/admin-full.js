function fts_ClearCache( notice ) {
    console.log('Clicked Clear Cache Function');

    jQuery.ajax({
        data: {
            action: "fts_clear_cache_ajax",
            _wpnonce: ftsAjax.clearCacheNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function (response) {
            //	jQuery('body').hide();
            console.log('Well Done and got this from sever: ' + response);
            // alert and upon clicking refresh the page
            if( 'alert' === notice ){
                alert('Cache for all FTS Feeds cleared!');
                window.location.reload();
            }

            return false;
        }
    }); // end of ajax()
    return false;
}

jQuery(document).ready(function ($) {

    // Find all text nodes on the page and filter for those that start with "[fts_"
    // Make sure this only runs on the front end. This will only run if you are logged in to WordPress.
    if( !$('body.wp-admin').length ){
        var $ftsTextNodes = $('body').find('*').contents().filter(function() {
            return this.nodeType === 3 && this.textContent.indexOf('[fts_') === 0;
        });

        // Loop through each matching text node and wrap it in a div with a specific style
        $ftsTextNodes.each(function() {
            var text = $(this).text();
            var $div = $('<div>').text(text).addClass('fts-legacy-shortcode').css({'cursor': 'pointer', 'color' : '#ff0000ed' });
            var $legacyDiv = $('<div>').addClass('fts-legacy-shortcode-wrap');
            var $legacySpan = $('<span class="fts-legacy-code-instruction">').text('Only visible to admins. This is a legacy shortcode, click on the shortcode below to start the conversion process.').css('font-weight', 'bold');
            var $successSpan = $('<span>').text('Success, shortcode copied to clipboard. ').addClass('success-message').css('font-weight', 'bold');;
            var $successSpan2 = $('<span>').text('After clicking the Next Step link a new Feed post should be created, now paste your old shortcode in the Convert Shortcode widget. Once complete you will replace your old shortcode with the new one. ').addClass('fts-convert-shortcode-message-success');

            var $link = $('<a>').text('Click here for Next Step.').attr('href', ftsAjax.createNewFeedUrl).attr('target', '_blank').addClass('fts-convert-shortcode-next-step-link').append('<br/>') ;
            var $link2 = $('<a>').text('Convert Shortcode Documentation Reference').attr('href', 'https://www.slickremix.com/documentation/convert-old-shortcode/').attr('target', '_blank') ;
            $legacyDiv.append($legacySpan).append($div);
            $(this).replaceWith($legacyDiv);

            // Add a click event handler to the div that copies the shortcode to clipboard and shows the success message
            $legacyDiv.click(function() {
                var el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                $div.css('display', 'none');
                $('.fts-legacy-code-instruction').hide();
                $legacyDiv.append($successSpan).append($link).append($successSpan2).append($link2);
            });
        });

        // Hide the fts-legacy-shortcode-wrap element when the body does not have the "logged-in" class
        if (!$('body').hasClass('logged-in')) {
            $('.fts-legacy-shortcode-wrap').css('display', 'none');
        }
    }


    // Used to clear the cache on the Settings page.
    // http://fts30.local/wp-admin/edit.php?post_type=fts&page=fts-settings-page
    jQuery("#fts-clear-cache").on('click', function () {

        console.log('Settings Click Clear Cache Function');
        jQuery('.fts-cache-messages').addClass( 'fts-cache-loading' ).css('display', 'inline-block' ).html( 'Please Wait... Clearing Cache' );

        jQuery.ajax({
            data: {
                action: "fts_clear_cache_ajax",
                _wpnonce: ftsAjax.clearCacheNonce
            },
            type: 'POST',
            url: ftsAjax.ajaxurl,
            success: function (response) {
                //	jQuery('body').hide();
                console.log('Well Done and got this from sever: ' + response);

                jQuery('.fts-cache-messages').removeClass( 'fts-cache-loading' ).html( 'Success: Cache Cleared' );

                return false;
            }
        }); // end of ajax()
        return false;

    }); // end of form.submit
});