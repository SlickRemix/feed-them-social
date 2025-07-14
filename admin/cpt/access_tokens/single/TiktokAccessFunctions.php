<?php
/**
 * Feed Them Social - TikTok Access Functions
 *
 * This page is used to retrieve and set access tokens for TikTok.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\admin\cpt\access_tokens\single;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class TiktokAccessFunctions
 *
 * @package feedthemsocial
 * @since 4.0.0
 */
class TiktokAccessFunctions {

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
     * Twitter Style Options Page constructor.
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
     * @param $feedCptId integer Feed CPT ID
     * @since 4.0.0
     */
    public function getAccessTokenButton( $feedCptId ) {

        $post_url = add_query_arg( array(
            'post' => $feedCptId,
            'fts_oauth_nonce' => wp_create_nonce( 'fts_oauth_tiktok' )
        ), admin_url( 'post.php' ) );


        $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feedCptId );

        $access_token       = !empty( $saved_feed_options['fts_tiktok_access_token'] ) ? $saved_feed_options['fts_tiktok_access_token'] : '';
        // Tokens expire every 24 hours. Hence expires_in from tiktok array is 86400 seconds.
        $refresh_token      = !empty( $saved_feed_options['fts_tiktok_refresh_token'] ) ? $saved_feed_options['fts_tiktok_refresh_token'] :  '';

        // Decrypt Access Token? Turning this off for TikTok Feed because Tokens refresh every 24hrs.
        // $decrypted_access_token  = false !== $this->dataProtection->decrypt( $access_token ) ?  $this->dataProtection->decrypt( $access_token ) : $access_token;

        if ( isset( $_GET['revoke_token'], $_GET['feed_type'] ) && 'tiktok' === $_GET['feed_type'] ) {
                ?>
            <script>
                jQuery(document).ready(function ($) {

                    setTimeout(function () {

                        const codeArray = {
                            "feed_type": 'tiktok',
                            "revoke_token": 'yes'
                        };

                        if (jQuery('#fts_tiktok_access_token').length !== 0) {
                            // Not actually encrypting token but other processes need to run so this function must stay intact.
                            ftsEncryptTokenAjax(codeArray, 'tiktok', '#fts_tiktok_access_token', 'firstRequest');
                        }

                        alert('TikTok Access Token Revoked Successfully!');

                    }, 500);

                });
            </script>
            <?php
        }

        elseif ( isset( $_GET['access_token'], $_GET['feed_type'] ) && 'tiktok' === $_GET['feed_type'] ) {


            if ( isset( $_GET['feed_type'] ) && $_GET['feed_type'] === 'tiktok' && 1 !== wp_verify_nonce( $_GET['fts_oauth_nonce'], 'fts_oauth_tiktok' ) ) {
                wp_die( __( 'Invalid twitter oauth nonce', 'feed-them-social' ) );
            }

            ?>
            <script>
                jQuery(document).ready(function ($) {

                    setTimeout(function () {
                        // Grab the url so we can do stuff.
                        var url_string         = window.location.href;
                        var url                = new URL( url_string );
                        var cpt_id             = url.searchParams.get("post");
                        var code               = url.searchParams.get("access_token");
                        var expires_in         = url.searchParams.get("expires_in");
                        var refresh_token      = url.searchParams.get("refresh_token");
                        var refresh_expires_in = url.searchParams.get("refresh_expires_in");
                        var feed_type          = url.searchParams.get("feed_type");
                        var user_id            = url.searchParams.get("user_id");

                        // Get today's date and time
                        //var now = new Date().getTime();

                        // Get the expires_in value (in seconds) // We just get this from the url instead of below.
                        // leaving for reference though on the amount of seconds in a day that Tiktok gives us from the API.
                        // var expires_in = 86400; // replace this with the actual value

                        // Convert expires_in to milliseconds (since JavaScript Date object works with milliseconds)
                         var expires_in_ms = expires_in * 1000;

                         // alert(expires_in_ms);

                        // Calculate the expiration date and time
                        // var expiryDate = now + expires_in_ms;


                        if( undefined!== cpt_id && undefined!== feed_type && 'tiktok' === feed_type ) {

                            $('#fts_tiktok_user_id').val('');
                            $('#fts_tiktok_user_id').val($('#fts_tiktok_user_id').val() + user_id);

                            $('#fts_tiktok_access_token').val('');
                            $('#fts_tiktok_access_token').val($('#fts_tiktok_access_token').val() + code);

                            /*  $('#fts_tiktok_saved_time_expires_in').val('');
                            $('#fts_tiktok_saved_time_expires_in').val($('#fts_tiktok_saved_time_expires_in').val() + now);
                            */

                            $('#fts_tiktok_expires_in').val('');
                            $('#fts_tiktok_expires_in').val($('#fts_tiktok_expires_in').val() + expires_in_ms );

                            $('#fts_tiktok_refresh_token').val('');
                            $('#fts_tiktok_refresh_token').val($('#fts_tiktok_refresh_token').val() + refresh_token);

                            $('#fts_tiktok_refresh_expires_in').val('');
                            $('#fts_tiktok_refresh_expires_in').val($('#fts_tiktok_refresh_expires_in').val() + refresh_expires_in);

                            const codeArray = {
                                "feed_type": 'tiktok',
                                "user_id": user_id,
                                "token": code,
                                "expires_in": expires_in_ms,
                                // going to update the time with php before updating the db.
                                // "current_date": now,
                                "refresh_token": refresh_token,
                                "refresh_expires_in": refresh_expires_in,
                            };

                            // Encrypt: Facebook Business
                            if (jQuery('#fts_tiktok_access_token').length !== 0) {
                                console.log('TikTok: Token set, now encrypting.');
                                // Not actually encrypting token but other processes need to run so this function must stay intact.
                                ftsEncryptTokenAjax(codeArray, 'tiktok', '#fts_tiktok_access_token', 'firstRequest');
                            }
                        }

                    }, 500);

                });
            </script>
        <?php }

        if( !empty( $access_token ) ) {

            // Get Data for TikTok.
            if ( ! function_exists( 'curl_init' ) ) {
                echo esc_html( 'Feed Them Social: cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social' );
            }
            else {
                $response = wp_remote_get( 'https://open.tiktokapis.com/v2/user/info/?fields=open_id', array(
                    'headers' => array(
                        'Authorization' => 'Bearer '.$access_token.''
                    )
                ));

                $body = wp_remote_retrieve_body( $response );

                // Output the response
                $data = json_decode( $body );
            }
        }

        echo \sprintf(
            esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
            '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://www.slickremix.com/tiktok-token/?redirect_url=' . urlencode( $post_url )) . '" class="fts-twitter-get-access-token">',
            '</a>'
        );
        ?>

        <div class="fts-settings-does-not-work-wrap">
            <button type="button" class="fts-admin-token-settings"><?php esc_html_e( 'Settings', 'feed-them-social' ); ?></button>
            <?php if ( $access_token !== '' ){ ?>
            <button type="button" onclick="fts_revoke_tiktok_access_token()" class="fts-tiktok-revoke-token"><?php esc_html_e( 'Revoke Access Token', 'feed-them-social' ); ?></button>
                <script>
                    function fts_revoke_tiktok_access_token(){
                        const result = confirm("This action will revoke your current Access Token. You will need to obtain a new Access Token if you want to display your TikTok feed. Are you sure you want to continue?");
                        if (result) {
                            // If the user clicked "OK", redirect them
                            window.location.replace("<?php echo esc_url_raw( 'https://www.slickremix.com/tiktok-revoke-token/?token='. sanitize_text_field( $access_token ) . '&redirect_url=' . $post_url ) ?>");
                        } else {
                            // If the user clicked "Cancel", do nothing
                            console.log("User cancelled the action.");
                        }
                    }
                </script>
            <?php } ?>
            <button type="button" class="fts-admin-button-no-work" onclick="fts_beacon_support_click()"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></button>
        </div>

        <div class="fts-clear"></div>

        <div class="fts-fb-token-wrap" id="fts-twitter-token-wrap">
            <?php
            // && !empty($test_fts_tiktok_access_token) && !empty($test_fts_tiktok_refresh_token)!
            if ( ! empty( $access_token ) && ! empty( $refresh_token ) ) {
                if ( $data->error->code !== 'ok' ) {
                    echo \sprintf(
                        esc_html__( '%1$s%2$s Please click the Login and Get my Access Token button again.%3$s', 'feed-them-social' ),
                        '<div class="fts-failed-api-token">',
                        $data->error->message,
                            '</div>'
                    );
                }
                else {

                    if( 'combine-streams-feed-type' === $this->feedFunctions->getFeedOption( $feedCptId, 'feed_type' ) ){
                        echo \sprintf(
                            esc_html__( '%1$s%2$sCreate Combined Feed%3$s', 'feed-them-social' ),
                            '<div id="fts-combined-twitter-success" class="fts-successful-api-token fts-special-working-wrap" style="display: none">',
                            '<a class="fts-twitter-combine-successful-api-token fts-success-token-content fts-combine-successful-api-token" href="#combine_streams_feed">',
                            '</a></div>'
                        );
                    }
                    else {
                        echo \sprintf(
                            esc_html__( '%1$s%2$sCreate TikTok Feed%3$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token fts-special-working-wrap">',
                            '<a class="fts-twitter-successful-api-token fts-success-token-content" href="#tiktok_feed">',
                            '</a></div>'
                        );
                    }

                    ?>
                    <script>
                        // This script is to display the countdown timer for the access token expiration.
                        // SetTimout so we can get the latest time saved to DB from previous ajax call.
                        // This is a problem I can see because of all the different ajax calls happening.
                        // In the future consolidating all ajax calls in needed.
                        setTimeout(function () {

                            var check_exp_time = parseInt( jQuery('#fts_tiktok_saved_time_expires_in').val());
                            var saved_time = parseInt(jQuery('#fts_tiktok_expires_in').val());
                            var countDownDate; // This will hold the actual expiration date and time

                            jQuery('.fts-tab-content1-twitter.fts-token-wrap .fts-exp-time-wrapper .feed-them-social-admin-input-label').append('<br/><span id="fts-timer"></span>');

                            // Convert check_exp_time to milliseconds and add to current time
                            countDownDate = saved_time + check_exp_time;

                            // Update the count down every 1 second
                            var x = setInterval(function () {

                                // Get today's date and time
                                var now = new Date().getTime();

                                // Find the distance between now and the count down date
                                var distance = countDownDate - now;

                                // Time calculations for days, hours, minutes and seconds
                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Display the result
                                jQuery('.fts-tab-content1-twitter.fts-token-wrap #fts-timer').html( hours + "h " + minutes + "m " + seconds + "s " );

                                // If the count down is finished, write some text
                                if (distance < 0) {
                                    clearInterval(x);
                                    jQuery('.fts-tab-content1-twitter.fts-token-wrap .fts-success').fadeIn();
                                    jQuery('.fts-tab-content1-twitter.fts-token-wrap #fts-timer').html( "Token Expired, click the Login and Get my Access Token button to get new a token." );
                                }
                            }, 1000);

                        }, 600);
                    </script>
                    <?php
                }
            } ?>
        </div>
        <?php
    }
}//end class
