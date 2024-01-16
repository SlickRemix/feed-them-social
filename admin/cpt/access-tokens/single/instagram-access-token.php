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

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Instagram_Access_Functions
 *
 * @package feedthemsocial
 * @since 4.0.0
 */
class Instagram_Access_Functions {

    /**
     * Feed Functions
     *
     * The Feed Functions Class
     *
     * @var object
     */
    public $feed_functions;

    /**
     * Data Protection
     *
     * Data Protection Class for encryption.
     *
     * @var object
     */
    public $data_protection;

    /**
     * Construct
     *
     * Instagram Style Options Page constructor.
     *
     * @since 4.0.0
     */
    public function __construct( $feed_functions, $data_protection ) {
        // Feed Functions.
        $this->feed_functions = $feed_functions;

        // Data Protection.
        $this->data_protection = $data_protection;
    }

    /**
     *  Get Access Token Button
     *
     * @since 4.0.0
     */
    public function get_access_token_button( $feed_cpt_id ) {

        $post_url = add_query_arg( array(
            'post' => $feed_cpt_id,
            'fts_oauth_nonce' => wp_create_nonce( 'fts_oauth_instagram' )
        ), admin_url( 'post.php' ) );

	    // Saved Feed Options!
	    $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_cpt_id );

        $user_id_basic           = !empty( $saved_feed_options['fts_instagram_custom_id'] ) ? $saved_feed_options['fts_instagram_custom_id'] :  '';
        $access_token            = !empty( $saved_feed_options['fts_instagram_custom_api_token'] ) ? $saved_feed_options['fts_instagram_custom_api_token'] : '';
        $access_token_expires_in = !empty( $saved_feed_options['fts_instagram_custom_api_token_expires_in'] ) ? $saved_feed_options['fts_instagram_custom_api_token_expires_in'] : '';

	    // Decrypt Access Token?
        $decrypted_access_token  = false !== $this->data_protection->decrypt( $access_token ) ?  $this->data_protection->decrypt( $access_token ) : $access_token;
        
        if ( isset( $_GET['feed_type'] ) && $_GET['feed_type'] === 'instagram_basic' && 1 !== wp_verify_nonce( $_GET['fts_oauth_nonce'], 'fts_oauth_instagram' ) ) {

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

                     var expires_in_check  = url.searchParams.get("expires_in") - 432000;

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
                        fts_encrypt_token_ajax( codeArray, 'basic', '#fts_instagram_custom_api_token', 'firstRequest');

                       // alert('test');
                    }

                }, 500);
            });
        </script>
        <?php

        // $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $fts_instagram_access_token );

        if( !empty( $decrypted_access_token ) ) {
            $insta_url = esc_url_raw( 'https://graph.instagram.com/me?fields=id,username&access_token=' . $decrypted_access_token );

            // Get Data for Instagram.
            $response = wp_remote_fopen( $insta_url );

            // Error Check.
            $data = json_decode( $response );

        }

        echo sprintf(
            esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
            '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://api.instagram.com/oauth/authorize?app_id=206360940619297&redirect_uri=https://www.slickremix.com/instagram-basic-token/&response_type=code&scope=user_profile,user_media&state=' . urlencode( urlencode( urlencode( $post_url ) ) ) ) . '" class="fts-instagram-get-access-token">',
            '</a>'
        );

        ?>
        <div class="fts-settings-does-not-work-wrap">
            <span class="fts-admin-token-settings"><?php esc_html_e( 'Settings', 'feed-them-social' ); ?></span>
            <a href="javascript:;" class="fts-admin-button-no-work" onclick="fts_beacon_support_click()"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></a>
        </div>

        <div class="fts-clear"></div>
        <div class="fts-instagram-token-wrap fts-token-wrap" id="fts-instagram-token-wrap">
            <?php

            $instagram_generic_response = 'Sorry, this content isn\'t available right now';

            if ( ! empty( $data ) || ! empty( $response ) && $instagram_generic_response === $response && !empty( $fts_instagram_access_token ) ) {

                if( ! isset( $data->meta->error_message ) && ! isset( $data->error_message ) && $instagram_generic_response !== $response || isset( $data->meta->error_message ) && 'This client has not been approved to access this resource.' === $data->meta->error_message ){

                    if( 'combine-streams-feed-type' === $this->feed_functions->get_feed_option( $feed_cpt_id, 'feed_type' ) ){
                        echo sprintf(
                            esc_html__( '%1$s%2$sCreate Combined Feed%3$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token fts-special-working-wrap">',
                            '<a class="fts-instagram-combine-successful-api-token fts-success-token-content fts-combine-successful-api-token" href="#combine_streams_feed">',
                            '</a></div>'
                        );

                    }
                    else {
                        echo sprintf(
                            esc_html__( '%1$s%2$sCreate Instagram Feed%3$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token fts-special-working-wrap">',
                            '<a class="fts-instagram-successful-api-token fts-success-token-content" href="#instagram_feed">',
                            '</a></div>'
                        );
                    }
                }

                if ( $instagram_generic_response === $response || isset( $data->data->error->message ) && ! empty( $user_id_basic ) || isset( $data->error->message ) && ! empty( $user_id_basic ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $data->error->message ) {
                    if ( isset( $data->data->error->message ) ) {
                        echo sprintf(
                            esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            esc_html( $data->data->error->message ),
                            '</div>'
                        );
                    }
                    if ( isset( $data->error->message ) ) {
                        echo sprintf(
                            esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            esc_html( $data->error->message ),
                            '</div>'
                        );
                    }
                    if ( $instagram_generic_response === $response ) {
                        echo sprintf(
                            esc_html__( '%1$sOh No something\'s wrong. Instagram Responded with: %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            esc_html( $response ),
                            '</div>'
                        );
                    }
                }
            }

            // Take the time() + $expiration_time will equal the current date and time in seconds, then we add the 60 days worth of seconds to the time.
            // That gives us the time to compare, of 60 days to the current date and Time.
            // For now we are going to get a new token every 7 days just to be on the safe side.
            // That means we will negate 53 days from the seconds which is 4579200 <-- https://www.convertunits.com/from/60+days/to/seconds
            // We get 60 days to refresh the token, if it's not refreshed before then it will expire.
            $expiration_time = $access_token_expires_in;

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
                                jQuery('.fts-tab-content1-instagram.fts-token-wrap #fts-timer').html( "Token Expired, refresh page to get new a token." );
                            }
                        }, 1000);
                    }

                }, 600);
            </script>
            <?php

            // Making it be time() < $expiration_time to test ajax, otherwise it should be time() > $expiration_time
            if (  ! empty( $fts_instagram_access_token ) && time() > $expiration_time ) {
                // Refresh token action!
                $this->feed_functions->feed_them_instagram_refresh_token( $feed_cpt_id );
            }
            ?>
        </div>
        <?php
    }
}//end class
