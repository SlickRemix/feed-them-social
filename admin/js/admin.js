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