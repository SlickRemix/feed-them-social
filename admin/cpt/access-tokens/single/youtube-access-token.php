<?php
 /**
 * Feed Them Social - Youtube Access Functions
 *
 * This page is used to retrieve and set access tokens for Youtube.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Youtube_Access_Functions
 *
 * @package feedthemsocial
 * @since 3.0.0
 */
class Youtube_Access_Functions {

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
	 * Youtube Style Options Page constructor.
	 *
	 * @since 3.0.0
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
     * @param $feed_cpt_id integer Feed CPT ID
	 * @since 3.0.0
	 */
	public function get_access_token_button( $feed_cpt_id ) {

        $post_url = add_query_arg( array(
            'post' => $feed_cpt_id,
        ), admin_url( 'post.php' ) );

        $youtube_api_key        = $this->feed_functions->get_feed_option( $feed_cpt_id, 'youtube_custom_api_token' );
        $youtube_refresh_token  = isset( $_GET['code'], $_GET['feed_type']  ) && 'youtube' === $_GET['feed_type'] ? sanitize_text_field( $_GET['refresh_token'] ) : $this->feed_functions->get_feed_option( $feed_cpt_id, 'youtube_custom_refresh_token' );
        $youtube_access_token   = isset( $_GET['code'], $_GET['feed_type']  ) && 'youtube' === $_GET['feed_type'] ? sanitize_text_field( $_GET['code'] ) : $this->feed_functions->get_feed_option( $feed_cpt_id, 'youtube_custom_access_token' );
        $expiration_time        = isset( $_GET['code'], $_GET['feed_type']  ) && 'youtube' === $_GET['feed_type'] ? sanitize_text_field( $_GET['expires_in'] ) : $this->feed_functions->get_feed_option( $feed_cpt_id, 'youtube_custom_token_exp_time' );

        ?>
        <script>
            jQuery(document).ready(function ($) {

                <?php if ( isset( $_GET['code'], $_GET['feed_type'] ) && 'youtube' === $_GET['feed_type'] ) {?>
                    $('#youtube_custom_refresh_token').val('');
                    $('#youtube_custom_refresh_token').val($('#youtube_custom_refresh_token').val() + '<?php echo esc_js( $youtube_refresh_token ); ?>');

                    $('#youtube_custom_access_token').val('');
                    $('#youtube_custom_access_token').val($('#youtube_custom_access_token').val() + '<?php echo sanitize_text_field( $youtube_access_token ); ?>');

                    $('#youtube_custom_token_exp_time').val('');
                    $('#youtube_custom_token_exp_time').val($('#youtube_custom_token_exp_time').val() + '<?php echo esc_js( $expiration_time ); ?>');

                    fts_ajax_cpt_save_token();

                <?php } ?>
            });
        </script>
        <?php

        if ( isset( $youtube_api_key ) && ! empty( $youtube_api_key ) ) {
            $youtube_api_key_or_token = 'key=' . $youtube_api_key . '';
        } elseif ( isset( $youtube_api_key ) && empty( $youtube_api_key ) && isset( $youtube_access_token ) && ! empty( $youtube_access_token ) ) {
            $youtube_api_key_or_token = 'access_token=' . $youtube_access_token . '';
        } else {
            $youtube_api_key_or_token = '';
        }

        $youtube_user_id_data = esc_url_raw( 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=slickremix&' . $youtube_api_key_or_token );
        // echo '$youtube_user_id_data';
        // echo $youtube_user_id_data;

        // Get Data for Youtube!
        $response = wp_remote_fopen( $youtube_user_id_data );
        // Error Check!
        $test_app_token_response = json_decode( $response );

        echo sprintf(
            esc_html__( '%1$sLogin and get my Access Token %2$s', 'feed-them-social' ),
            '<a href="' . esc_url( 'https://www.slickremix.com/youtube-token/?redirect_url=' . $post_url ) . '" class="fts-youtube-get-access-token">',
            '</a>'
        );
        ?>

        <a href="https://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank" class="fts-admin-button-no-work">Not working?</a>

        <?php

        if ( isset( $_GET['refresh_token'] ) && isset( $_GET['code'] ) && isset( $_GET['expires_in'] ) ) {
            // START AJAX TO SAVE TOKEN TO DB RIGHT AWAY SO WE CAN DO OUR NEXT SET OF CHECKS
            // new token action!
            $this->feed_functions->feed_them_youtube_refresh_token();
        }

        $expiration_time = '' !== $this->feed_functions->get_feed_option( $feed_cpt_id, 'youtube_custom_token_exp_time' ) ? get_option( 'youtube_custom_token_exp_time' ) : 500;

        // Give the access token a 5 minute buffer (300 seconds) before getting a new one.
        $expiration_time = $expiration_time - 300;
        // Test Liner!
        if ( time() < $expiration_time && empty( $youtube_api_key ) ) {
            ?>
            <script>
                // Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
                var countDownDate = new Date( <?php echo esc_js( $expiration_time ); ?> * 1000 ); // <--phpStorm shows error but it's false.

                // console.log(countDownDate);

                // Update the count down every 1 second
                var x = setInterval(function () {

                    // Get todays date and time
                    var now = new Date().getTime();

                    // console.log(now);

                    // Find the distance between now an the count down date
                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    jQuery('.fts-exp-time-wrapper .feed_them_social-admin-input-label').append('<br/><span id="fts-timer"></span>');
                    document.getElementById("fts-timer").innerHTML = minutes + "m " + seconds + "s ";

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        jQuery('.fts-success').fadeIn();
                        document.getElementById("fts-timer").innerHTML = "Token Expired, refresh page to get new a token.";
                    }
                }, 1000);
            </script>
            <?php
        }
        ?>
        <div class="clear"></div>
        <div class="feed-them-social-admin-input-wrap fts-youtube-token-wrap" id="fts-youtube-token-wrap" style="margin-bottom:0px;"><?php

        // YO! making it be time() < $expiration_time to test ajax, otherwise it should be time() > $expiration_time
        if ( empty( $youtube_api_key ) && ! empty( $youtube_access_token ) && time() > $expiration_time ) {
            // refresh token action!
            $this->feed_functions->feed_them_youtube_refresh_token();
        }

        $user_id = $test_app_token_response;
        $error_response = $test_app_token_response->error->errors[0]->message ? 'true' : 'false';
        // print_r( $error_response );

        // Error Check!
        if ( 'false' === $error_response && ! empty( $youtube_api_key ) || 'false' === $error_response && ! empty( $youtube_access_token ) && empty( $youtube_api_key ) ) {
            echo '<div class="fts-successful-api-token fts-special-working-wrap">';
            echo sprintf(
                esc_html__( 'Your access token is working! Now you can create your %1$sYouTube Feed%2$s', 'feed-them-social' ),
                '<a class="fts-youtube-successful-api-token" href="#youtube_feed">',
                '</a>.'
            );
            echo '</div>';

        }
        elseif ( empty( $youtube_api_key ) && 'true' === $error_response && ! empty( $youtube_access_token ) ) {
            echo sprintf(
                esc_html__( '%1$sYouTube responded with: %2$s %3$s ', 'feed-them-social' ),
                '<div class="fts-failed-api-token">',
                esc_html( 'The request is missing a valid Access Token.' ),
                '</div>'
            );
        }
        elseif ( 'true' === $error_response && ! empty( $youtube_api_key ) ) {
            echo sprintf(
                esc_html__( '%1$sYouTube responded with: %2$s %3$s ', 'feed-them-social' ),
                '<div class="fts-failed-api-token">',
                esc_html( $user_id->error->errors[0]->message ),
                '</div>'
            );
        }
        if ( empty( $youtube_api_key ) && empty( $youtube_access_token ) ) {
            echo sprintf(
                esc_html__( '%1$s Click the button above or register for an API Key to use the YouTube feed.%2$s', 'feed-them-social' ),
                '<div class="fts-failed-api-token">',
                '</div>'
            );
        }
        ?>
        </div>
        <?php
    }
}//end class