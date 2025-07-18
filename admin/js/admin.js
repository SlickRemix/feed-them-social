function fts_ClearCache( notice ) {
    console.log('Clicked Clear Cache function');
    jQuery.ajax({
        data: {
            action: "ftsClearCacheAjax",
            _wpnonce: ftsAjax.clearCacheNonce
        },
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function (response) {
            console.log('Well Done and got this from sever: ' + response);
            if( 'alert' === notice ){
                alert('Cache for all FTS Feeds cleared!');
                window.location.reload();
            }

            return false;
        }
    });
    return false;
}

jQuery(document).ready(function ($) {

    // Set the styles on the admin bar Clear Cache.
    $('#wp-admin-bar-feed_them_social_admin_set_cache div').css('cursor', 'pointer').hover(
        function() {
            $(this).css('color', '#72aee6');
        },
        function() {
            $(this).css('color', '');
        }
    );

    jQuery("#fts-clear-cache").on('click', function () {
        console.log('Settings Click Clear Cache Function');
        jQuery('.fts-cache-messages').addClass( 'fts-cache-loading' ).css('display', 'inline-block' ).html( 'Please Wait... Clearing Cache' );

        jQuery.ajax({
            data: {
                action: "ftsClearCacheAjax",
                _wpnonce: ftsAjax.clearCacheNonce
            },
            type: 'POST',
            url: ftsAjax.ajaxurl,
            success: function (response) {
                console.log('Well Done and got this from sever: ' + response);
                jQuery('.fts-cache-messages').removeClass( 'fts-cache-loading' ).html( 'Success: Cache Cleared' );
                return false;
            }
        });
        return false;

    }); // end of form.submit
});