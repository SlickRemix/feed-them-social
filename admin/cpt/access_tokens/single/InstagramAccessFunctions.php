<?php
/**
 * Feed Them Social - Instagram Access Options
 *
 * This page is used to retrieve and set access tokens for Instagram.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.0
 */

namespace feedthemsocial\admin\cpt\access_tokens\single;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class InstagramAccessFunctions
 *
 * @package feedthemsocial
 * @since 4.0.0
 */
class InstagramAccessFunctions {

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
            'fts_oauth_nonce' => wp_create_nonce( 'fts_oauth_instagram' )
        ), admin_url( 'post.php' ) );

        // Saved Feed Options!
        $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feedCptId );

        $user_id_basic           = !empty( $saved_feed_options['fts_instagram_custom_id'] ) ? $saved_feed_options['fts_instagram_custom_id'] :  '';
        $access_token            = !empty( $saved_feed_options['fts_instagram_custom_api_token'] ) ? $saved_feed_options['fts_instagram_custom_api_token'] : '';

        // Decrypt Access Token?
        $decrypted_access_token  = $this->dataProtection->decrypt( $access_token ) !== false ?  $this->dataProtection->decrypt( $access_token ) : $access_token;
        
        if ( isset( $_GET['feed_type'] ) && $_GET['feed_type'] === 'instagram_basic' && wp_verify_nonce( $_GET['fts_oauth_nonce'], 'fts_oauth_instagram' ) !== 1 ) {

            wp_die( __('Invalid instagram oauth nonce.', 'feed-them-social' ) );
        }
        ?>
        <script>
            jQuery(document).ready(function ($) {

                // Required because we need to reload the function but only once under the combine streams since all the access tokens are loaded up at once on this tab.
                fts_show_decrypt_token_text();

                setTimeout(function () {

                    // Grab the url so we can do stuff.
                    var url_string        = window.location.href;
                    var url               = new URL( url_string );
                    var cpt_id            = url.searchParams.get("post");
                    var code              = url.searchParams.get("code");
                    var feed_type         = url.searchParams.get("feed_type");
                    var user_id           = url.searchParams.get("user_id");

                     /* Testing
                     1677574786979 get today's timestamp and add some seconds to it so we can test.
                     var expires_in_check  = 1677574786979 + 5173728; */

                     var expires_in_check  = url.searchParams.get("expires_in");

                    if( undefined!== cpt_id && undefined!== feed_type && 'instagram_basic' === feed_type ) {

                        var date_add_time     = expires_in_check * 1000;
                        var date_check = Date.now();
                        var date = date_check + date_add_time;
                        console.log( date );

                        $('#fts_instagram_custom_id').val('');
                        $('#fts_instagram_custom_id').val( $('#fts_instagram_custom_id').val() +  user_id );
                        $('#fts_instagram_custom_api_token_expires_in').val('');
                        $('#fts_instagram_custom_api_token_expires_in').val( $('#fts_instagram_custom_api_token_expires_in').val() + date );

                        // Take the code param from url and pass it to our encrypt function to sanitize and save to db then save all the options.
                        const codeArray = {
                            "feed_type" : 'instagram_basic',
                            "user_id" : user_id,
                            "token" : code,
                            "expires_in" : date
                        };

                        // I am passing the user id and expires in and saving too, this creates one less save function in the end.
                        // so instead I can just refresh the page instead of re-saving again which is not necessary.
                        ftsEncryptTokenAjax( codeArray, 'basic', '#fts_instagram_custom_api_token', 'firstRequest');
                    }

                }, 500);
            });
        </script>
        <?php

        if( !empty( $decrypted_access_token ) ) {
            $insta_url = esc_url_raw( 'https://graph.instagram.com/me?fields=id,username' . FTS_AND_ACCESS_TOKEN_EQUALS . $decrypted_access_token );

            // Get Data for Instagram.
            $response = wp_remote_fopen( $insta_url );

            // Error Check.
            $data = json_decode( $response );

        }
        // https://developers.facebook.com/docs/instagram-platform/instagram-api-with-instagram-login/business-login#step-2---exchange-the-code-for-a-token
        echo \sprintf(
            esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
            '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://api.instagram.com/oauth/authorize?client_id=523345500405663&redirect_uri=https://www.slickremix.com/instagram-business-basic-token-redirect/&response_type=code&scope=instagram_business_basic&state=' . urlencode( urlencode( urlencode( $post_url ) ) ) ) . '" class="fts-instagram-get-access-token">',
            '</a>'

           /* esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
            '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://api.instagram.com/oauth/authorize?app_id=206360940619297&redirect_uri=https://www.slickremix.com/instagram-basic-token/&response_type=code&scope=user_profile,user_media&state=' . urlencode( urlencode( urlencode( $post_url ) ) ) ) . '" class="fts-instagram-get-access-token">',
            '</a>'*/
        );

        ?>
        <div class="fts-settings-does-not-work-wrap">
            <button type="button" class="fts-admin-token-settings"><?php esc_html_e( 'Settings', 'feed-them-social' ); ?></button>
            <button type="button" class="fts-admin-button-no-work" onclick="fts_beacon_support_click()"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></button>
        </div>

        <div class="fts-clear"></div>
        <div class="fts-instagram-token-wrap fts-token-wrap" id="fts-instagram-token-wrap">
            <?php

            $instagram_generic_response = 'Sorry, this content isn\'t available right now';

            if ( ! empty( $data ) || ! empty( $response ) && $instagram_generic_response === $response && !empty( $decrypted_access_token ) ) {

                if( ! isset( $data->meta->error_message ) && ! isset( $data->error_message ) && $instagram_generic_response !== $response || isset( $data->meta->error_message ) && 'This client has not been approved to access this resource.' === $data->meta->error_message ){

                    if( $this->feedFunctions->getFeedOption( $feedCptId, 'feed_type' ) === 'combine-streams-feed-type' ){
                        echo \sprintf(
                            esc_html__( '%1$s%2$sCreate Combined Feed%3$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token fts-special-working-wrap">',
                            '<a class="fts-instagram-combine-successful-api-token fts-success-token-content fts-combine-successful-api-token" href="#combine_streams_feed">',
                            '</a></div>'
                        );

                    }
                    else {
                        echo \sprintf(
                            esc_html__( '%1$s%2$sCreate Instagram Feed%3$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token fts-special-working-wrap">',
                            '<a class="fts-instagram-successful-api-token fts-success-token-content" href="#instagram_feed">',
                            '</a></div>'
                        );
                    }
                }

                if ( $instagram_generic_response === $response || isset( $data->data->error->message ) && ! empty( $user_id_basic ) || isset( $data->error->message ) && ! empty( $user_id_basic ) && $data->error->message !== '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {
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
                    if ( $instagram_generic_response === $response ) {
                        echo \sprintf(
                            esc_html__( '%1$sOh No something\'s wrong. Instagram Responded with: %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            esc_html( $response ),
                            '</div>'
                        );
                    }
                }
            }

            ?>
            <script>
                // This script is to display the countdown timer for the access token expiration.
                // SetTimout so we can get the latest time saved to DB from previous ajax call.
                // This is a problem I can see because of all the different ajax calls happening.
                // In the future consolidating all ajax calls in needed.
                setTimeout(function () {

                    var check_exp_time = jQuery('#fts_instagram_custom_api_token_expires_in').val();
                    // Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
                    var countDownDate = check_exp_time;
                    console.log( check_exp_time );

                    // Get today's date and time
                    var right_now = new Date().getTime();


                    if ( '' !== check_exp_time && check_exp_time > right_now  ) {

                        // Update the count down every 1 second
                        var x = setInterval(function () {

                            // Get todays date and time
                            var now = new Date().getTime();

                            // Find the distance between now an the count down date
                            var distance = countDownDate - now;

                            // Time calculations for days, hours, minutes and seconds
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            jQuery('.fts-tab-content1-instagram.fts-token-wrap .fts-exp-time-wrapper .feed-them-social-admin-input-label').append('<br/><span id="fts-timer"></span>');
                            jQuery('.fts-tab-content1-instagram.fts-token-wrap #fts-timer').html( days + "d " + hours + "h " + minutes + "m " + seconds + "s " );

                            // If the count down is finished, write some text
                            if (distance < 0) {
                                clearInterval(x);
                                jQuery('.fts-tab-content1-instagram.fts-token-wrap .fts-success').fadeIn();
                                jQuery('.fts-tab-content1-instagram.fts-token-wrap #fts-timer').html( "Token Expired, click the Login and Get my Access Token button to get new a token." );
                            }
                        }, 1000);
                    }

                }, 600);
            </script>
        </div>
        <?php
    }
}//end class
