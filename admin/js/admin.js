jQuery( document ).ready(
    function () {
          
            // Used to clear the cache from the Admin menu bar at the top of the site, while logged in.
            // This allows you to be able to easily delete the cache from any webpage on the site. Front or backend.
            if( document.getElementById('wp-admin-bar-fts_admin_bar_set_cache') ) {
                jQuery("#wp-admin-bar-fts_admin_bar-default li:first-child a").on('click', function () {

                    console.log('Click Clear Cache Function');

                    jQuery.ajax({
                        data: {action: "fts_clear_cache_ajax"},
                        type: 'POST',
                        url: ftsAjax.ajaxurl,
                        success: function (response) {
                            //	jQuery('body').hide();
                            console.log('Well Done and got this from sever: ' + response);
                            // alert and upon clicking refresh the page
                            if (!alert('Cache Successfully Cleared')) {
                                window.location.reload();
                            }

                            return false;
                        }
                    }); // end of ajax()
                    return false;

                }); // end of form.submit
            }

            // Used to clear the cache on the Settings page.
            // https://dev.presgrouprealty.com/wp-admin/edit.php?post_type=fts_communities&page=pgr-settings-page&tab=general
            jQuery("#fts-clear-cache").on('click', function () {

                console.log('Settings Click Clear Cache Function');
                jQuery('.fts-cache-messages').addClass( 'fts-cache-loading' ).css('display', 'inline-block' ).html( 'Please Wait... Clearing Cache' );

                jQuery.ajax({
                    data: {action: "fts_clear_cache_ajax"},
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

}); // end of document.ready