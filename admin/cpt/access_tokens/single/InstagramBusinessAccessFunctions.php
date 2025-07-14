<?php
 /**
 * Feed Them Social - Instagram Business Access Functions
 *
 * This page is used to retrieve and set access tokens for Instagram Business.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.0
 */

namespace feedthemsocial\admin\cpt\access_tokens\single;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class InstagramBusinessAccessFunctions
 *
 * @package feedthemsocial
 * @since 4.0.0
 */
class InstagramBusinessAccessFunctions {

    /**
     * Feed Functions
     *
     * The Feed Functions Class
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Data Protection
     *
     * Data Protection Class for encryption.
     *
     * @var object
     */
    public $dataProtection;

    /**
     * Construct
     *
     * Instagram Style Options Page constructor.
     *
     * @since 4.0.0
     */
    public function __construct( $feedFunctions, $dataProtection ) {
        // Feed Functions.
        $this->feedFunctions = $feedFunctions;

        // Data Protection.
        $this->dataProtection = $dataProtection;
    }

    /**
     *  Get Access Token Button
     *
     * @since 4.0.0
     */
    public function getAccessTokenButton( $feedCptId ) {

        $post_url = add_query_arg( array(
            'post' => $feedCptId,
            'fts_oauth_nonce' => wp_create_nonce( 'fts_oauth_instagram_business' )
        ), admin_url( 'post.php' ) );

                ?>
            <script>
                jQuery(document).ready(function () {
                    fts_social_icons_wrap_click();

                    // Do not run this function if we are on the combined streams tab because we are loading it under the instagram access token option already and the function can only be loaded once or it fires double actions.
                    // We need to this function on each of the access token pages so that the function will fire properly again to decrypt the token.
                    if( !jQuery('.combine-streams-feed-wrap').length > 0 ) {
                        fts_show_decrypt_token_text();
                    }

                    // This click function is specific to fb and instagram fb when you click the green save button after
                    // clicking on a page in the list of facebook pages you manage.
                    jQuery('.combine-instagram-business-access-token-placeholder div.fts-token-save, .instagram-facebook-access-token-placeholder div.fts-token-save, .instagram-facebook-access-token-placeholder .fts-token-manual-save, .combine-instagram-business-access-token-placeholder span.fts-token-manual-save').click( function (e) {
                        e.preventDefault();

                        let myString = jQuery('#fts_facebook_instagram_custom_api_token').val();
                        let length = myString.length;
                        if(length > 300) {
                            alert('<?php echo esc_html__('You must add a valid Facebook Access Token to use the Save Token Manual feature. If there is already a value in the field, please remove it, update the page and try again.', 'feed-them-social'); ?>');
                            if(!jQuery('.fts-admin-token-settings-open').length ) {
                                jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
                                jQuery( this ).toggleClass( 'fts-feed-type-active' );
                                jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
                                jQuery( '.fts-token-wrap h3' ).toggleClass( 'fts-admin-token-settings-open' );
                                // If the input field is empty, set the cursor to it
                                jQuery('#fts_facebook_instagram_custom_api_token_user_id').focus();
                            }
                            return;
                        }


                        const codeArray = {
                            "feed_type" : 'instagram_business',
                            "token" : jQuery('#fts_facebook_instagram_custom_api_token').val(),
                            "user_id" : jQuery('#fts_facebook_instagram_custom_api_token_user_id').val(),
                            "instagram_user_name" : jQuery('#fts_facebook_instagram_custom_api_token_user_name').val(),
                            "facebook_user_name" : jQuery('#fts_facebook_instagram_custom_api_token_fb_user_name').val()
                        };

                        // Encrypt: Instagram Business
                        if( jQuery('#fts_facebook_instagram_custom_api_token').length !== 0 && jQuery('#fts_facebook_instagram_custom_api_token').val().trim() !== '' ) {
                            console.log('Instagram Business: Token set, now encrypting.');
                            ftsEncryptTokenAjax( codeArray, 'business', '#fts_facebook_instagram_custom_api_token', 'firstRequest');
                        }
                        else {
                            if( jQuery('.combine-instagram-business-access-token-placeholder').length !== 0 ){
                                fts_reload_toggle_click()
                            }
                            else {
                                jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
                                jQuery( this ).toggleClass( 'fts-feed-type-active' );
                                jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
                                jQuery( '.fts-token-wrap h3' ).toggleClass( 'fts-admin-token-settings-open' );
                                // If the input field is empty, set the cursor to it
                                jQuery('#fts_facebook_instagram_custom_api_token_user_id').focus();
                            }
                        }
                    });

                });
            </script>

            <?php
                // call to get instagram account attached to the facebook page
                // 1844222799032692 = slicktest fb page (dev user)
                // 1844222799032692?fields=instagram_business_account&access_token=
                // This redirect url must have an &state= instead of a ?state= otherwise it will not work proper with the fb app. https://www.slickremix.com/instagram-token/&state=.
                echo \sprintf(
                    esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
                    '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/instagram-token/&state=' . urlencode( $post_url ) . '&scope=pages_show_list,pages_read_engagement,instagram_basic,business_management' ) . '" class="fts-facebook-get-access-token">',
                    '</a>'
                );
                ?>

            <div class="fts-settings-does-not-work-wrap">
                <button type="button" class="fts-token-manual-save"><?php esc_html_e( 'Save Token Manually', 'feed-them-social' ); ?></button>
                <button type="button" class="fts-admin-token-settings"><?php esc_html_e( 'Settings', 'feed-them-social' ); ?></button>
                <button type="button" class="fts-admin-button-no-work" onclick="fts_beacon_support_click()"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></button>
            </div>

            <?php
                // Saved Feed Options!
                $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feedCptId );

                $page_id            = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] : '';
                $access_token       = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token'] : '';

                // Decrypt Access Token?
                $decrypted_access_token = $this->dataProtection->decrypt( $access_token ) !== false ?  $this->dataProtection->decrypt( $access_token ) : $access_token;

                if ( ! empty( $page_id ) || ! empty( $access_token ) ) {

                    $test_app_token_url = array(
                        'app_token_id' => FTS_FACEBOOK_GRAPH_URL . 'debug_token?input_token=' . $decrypted_access_token . FTS_AND_ACCESS_TOKEN_EQUALS . $decrypted_access_token,
                    );

                    // Check to see what the response is.
                    $response = $this->feedFunctions->ftsGetFeedJson( $test_app_token_url );
                    $data = json_decode( $response['app_token_id'] );
                }
                ?>
                <div class="clear"></div>
                <div class="fts-fb-token-wrap" id="fts-fb-token-wrap">
                     <?php
                     if( !isset( $_GET['feed_type'] ) && !empty( $data ) ) {

                         if ( isset( $data->data->is_valid ) || $data->error->message === '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {

                             if( $this->feedFunctions->getFeedOption( $feedCptId, 'feed_type' ) === 'combine-streams-feed-type' ){
                                 echo \sprintf(
                                     esc_html__( '%1$s%2$sCreate Combined Feed%3$s', 'feed-them-social' ),
                                     '<div class="fts-successful-api-token fts-special-working-wrap">',
                                     '<a class="fts-instagram-business-combine-successful-api-token fts-success-token-content fts-combine-successful-api-token" href="#combine_streams_feed">',
                                     '</a></div>'
                                 );

                             }
                             else{
                                 echo \sprintf(
                                     esc_html__( '%1$s%2$sCreate Instagram Feed%3$s', 'feed-them-social' ),
                                     '<div class="fts-successful-api-token fts-special-working-wrap">',
                                     '<a class="fts-instagram-business-successful-api-token fts-success-token-content" href="#instagram_feed">',
                                     '</a></div>'
                                 );
                             }
                         }
                         if ( isset( $data->data->error->message ) && !empty( $data ) || isset( $data->error->message ) && !empty( $data ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $data->error->message ) {
                             if ( isset( $data->data->error->message ) ) {
                                 echo \sprintf(
                                     esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                                     '<div class="fts-failed-api-token">',
                                     esc_html( $data->data->error->message ),
                                     '</div>'
                                 );
                             }
                             if ( isset( $data->error->message ) ) {
                                 echo \sprintf(
                                     esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                                     '<div class="fts-failed-api-token">',
                                     esc_html( $data->error->message ),
                                     '</div>'
                                 );
                             }
                         }
                     }
                    ?>
                    <div class="clear"></div>

                    <?php

                    if ( isset( $_GET['return_long_lived_token'], $_GET['feed_type'] ) && 'instagram' === $_GET['feed_type'] ) {

                        if ( ! isset( $_GET['fts_oauth_nonce'] ) || wp_verify_nonce( $_GET['fts_oauth_nonce'], 'fts_oauth_instagram_business' ) !== 1 ) {
                            wp_die( __( 'Invalid instagram business oauth nonce', 'feed-them-social' ) );
                        }

                        // Echo our shortcode for the page token list with  Load More
                        // These functions are on feed-functions.php!
                        echo do_shortcode( '[fts_fb_page_token]' );

                    }
                    ?>
                </div>

                <div class="clear"></div>

<?php
    }
}//end class
