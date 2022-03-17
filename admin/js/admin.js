function fts_ClearCache() {
    console.log('Clicked Clear Cache Function');

    jQuery.ajax({
        data: {action: "fts_clear_cache_ajax"},
        type: 'POST',
        url: ftsAjax.ajaxurl,
        success: function (response) {
            //	jQuery('body').hide();
            console.log('Well Done and got this from sever: ' + response);
            // alert and upon clicking refresh the page
            if (!alert('Cache for all FTS Feeds cleared!')) {
                window.location.reload();
            }

            return false;
        }
    }); // end of ajax()
    return false;
}

jQuery(document).ready(function ($) {

    // Grab the url so we can do stuff.
    var url_string = window.location.href;
    var url = new URL(url_string);

    // Encrypt: Instagram Basic
    if ( $('#fts_instagram_custom_api_token').length !== 0 ) {
        // Do NOT run this if we are getting a token for Instagram, because we save the token via ajax. The Instagram Business and Facebook Business do not.
        if (url_string.indexOf("code") === -1 && url_string.indexOf("feed_type") === -1) {

            if ('' === $('#fts_instagram_custom_api_token').data('token')) {

                if ('' === $('#fts_instagram_custom_api_token').val()) {
                    console.log('Instagram Basic: No token has been set.');
                } else {
                    // User clicked enter or submit button.
                    console.log('Instagram Basic: Token set, now encrypting.');
                    fts_encrypt_token_ajax($('#fts_instagram_custom_api_token').val(), 'basic', '#fts_instagram_custom_api_token');
                }
            } else {
                console.log('Instagram Basic: Token is already set & encrypted.');
            }
        }
    }

    // Encrypt: Instagram Business
    if ( $('#fts_instagram_custom_api_token').length !== 0 ) {

        if ('' === $('#fts_facebook_instagram_custom_api_token').data('token')) {

            // If no value set then return message.
            if ('' === $('#fts_facebook_instagram_custom_api_token').val()) {
                console.log('Instagram Business: No token has been set.');
            } else {
                // User clicked enter or submit button.
                console.log('Instagram Business: Token set, now encrypting.');
                fts_encrypt_token_ajax($('#fts_facebook_instagram_custom_api_token').val(), 'business', '#fts_facebook_instagram_custom_api_token');
            }
        } else {
            console.log('Instagram Business: Token is already set & encrypted.');
        }
    }

    // Encrypt: Facebook Business
    if ( $('#fts_facebook_custom_api_token').length !== 0 ) {

        if ('' === $('#fts_facebook_custom_api_token').data('token')) {

            // If no value set then return message.
            if ('' === $('#fts_facebook_custom_api_token').val()) {
                console.log('Facebook Business: No token has been set.');
            } else {
                // User clicked enter or submit button.
                console.log('Facebook Business: Token set, now encrypting.');
                fts_encrypt_token_ajax($('#fts_facebook_custom_api_token').val(), 'fbBusiness', '#fts_facebook_custom_api_token');
            }
        } else {
            console.log('Facebook Business: Token is already set & encrypted.');
        }
    }

    // Encrypt: Facebook Business Reviews
    if ( $('#fts_facebook_custom_api_token_biz').length !== 0 ) {

        if ('' === $('#fts_facebook_custom_api_token_biz').data('token')) {

            // If no value set then return message.
            if ('' === $('#fts_facebook_custom_api_token_biz').val()) {
                console.log('Facebook Business: No token has been set.');
            } else {
                // User clicked enter or submit button.
                console.log('Facebook Business Reviews: Token set, now encrypting.');
                fts_encrypt_token_ajax($('#fts_facebook_custom_api_token_biz').val(), 'fbBusinessReviews', '#fts_facebook_custom_api_token_biz');
            }
        } else {
            console.log('Facebook Business Reviews: Token is already set & encrypted.');
        }
    }

    function fts_encrypt_token_ajax( access_token, token_type , id ) {

        var token = access_token;

        $.ajax({
            data: {
                action: 'fts_encrypt_token_ajax',
                access_token: token,
                token_type: token_type,
            },
            type: 'POST',
            url: ftsAjax.ajaxurl,
            success: function ( response ) {

                var data = JSON.parse( response );
                // Add the OG token to the input value and add the encrypted token to the data-attribute.
                $( id ).attr('value', data.token).attr('data-token', 'encrypted');
                console.log( id + ': OG Token and Encrypted Response: ' + response );
            }

        }); // end of ajax()
        return false;
    }
});