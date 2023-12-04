jQuery(document).ready(function ($) {
    const ftg_date_format = $('.fts_date_time_format');
    if ( ftg_date_format.length > 0 )  {
        $( document.body ).on('change', $('.fts_date_time_format'), function()	{
            if ( 'one-day-ago' === $('.fts_date_time_format').val() ) {
                $('.custom_time_ago_wrap').show();
            } else  {
                $('.custom_time_ago_wrap').hide();
            }
            if ( 'fts-custom-date' === $('.fts_date_time_format').val() ) {
                $('.custom_date_time_wrap').show();
            } else  {
                $('.custom_date_time_wrap').hide();
            }
        });
    }
});

// Used on the Settings page of the plugin and shows the description in the small question mark next to inputs, checkboxes, selects etc..
jQuery( function() {
    if( jQuery('body').hasClass( 'fts_page_fts-settings-page' ) ){
        jQuery( document ).tooltip();
    }
} );
