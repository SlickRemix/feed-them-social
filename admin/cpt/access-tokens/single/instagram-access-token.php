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
 * @since 3.0.0
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
	 * @since 3.0.0
	 */
	public function get_access_token_button( $feed_cpt_id ) {

        $post_url = add_query_arg( array(
            'post' => $feed_cpt_id,
        ), admin_url( 'post.php' ) );

        $fts_instagram_custom_id                = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_instagram_custom_id' );
        $fts_instagram_access_token             = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_instagram_custom_api_token' ) ;
        $fts_instagram_access_token_expires_in  = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_instagram_custom_api_token_expires_in' );
        $user_id_basic                          = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['user_id'] ) : $fts_instagram_custom_id;
        $access_token                           = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['code'] ) : $fts_instagram_access_token;
        $access_token_expires_in                = isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field(  $_GET['expires_in'] - 4579200 ) : $fts_instagram_access_token_expires_in;
        $encrypted_access_token                 = false !== $this->data_protection->encrypt( $access_token ) ? $this->data_protection->encrypt( $access_token ) : $access_token;
        $decrypted_access_token                 = false !== $this->data_protection->decrypt( $access_token ) ? $this->data_protection->decrypt( $access_token ) : $access_token;

        ?>
                <script>

                    jQuery(document).ready(function ($) {

                        <?php if ( isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ) {?>
                        $('#fts_instagram_custom_api_token').val('');
                        $('#fts_instagram_custom_api_token').val($('#fts_instagram_custom_api_token').val() + '<?php echo sanitize_text_field( $encrypted_access_token ); ?>');
                        $('#fts_instagram_custom_id').val('');
                        $('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + '<?php echo esc_js( $user_id_basic ); ?>');
                        $('#fts_instagram_custom_api_token_expires_in').val('');
                        $('#fts_instagram_custom_api_token_expires_in').val($('#fts_instagram_custom_api_token_expires_in').val() + '<?php echo esc_js( strtotime( '+' . $access_token_expires_in . ' seconds' ) ); ?>');

                        fts_ajax_cpt_save_token();


                        <?php }
                        else { ?>

                    <?php } ?>

                        $('#fts_instagram_custom_api_token').attr( 'value', '<?php echo $decrypted_access_token ?>' );


                    });
                </script>
                <?php

                // $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $fts_instagram_access_token );

                if( !empty( $decrypted_access_token ) ) {
                    $insta_url = esc_url_raw( 'https://graph.instagram.com/me?fields=id,username&access_token=' . $decrypted_access_token );

                    // Get Data for Instagram!
                    $response = wp_remote_fopen( $insta_url );

                    // Error Check!
                    $data = json_decode( $response );

                }

                echo sprintf(
                    esc_html__( '%1$sLogin and get my Access Token%2$s %3$sNot working?%4$s', 'feed-them-social' ),
                    '<a href="' . esc_url( 'https://api.instagram.com/oauth/authorize?app_id=206360940619297&redirect_uri=https://www.slickremix.com/instagram-basic-token/&response_type=code&scope=user_profile,user_media&state=' . $post_url ) . '" class="fts-instagram-get-access-token">',
                    '</a>',
                    '<a href="mailto:support@slickremix.com" class="fts-admin-button-no-work">',
                    '</a>'
                );
                 ?>
        <div class="fts-clear"></div>
        <div class="feed-them-social-admin-input-wrap fts-instagram-token-wrap fts-token-wrap" id="fts-instagram-token-wrap">
            <?php

        $instagram_generic_response = 'Sorry, this content isn\'t available right now';

        if ( ! empty( $data ) || $instagram_generic_response === $response && !empty( $fts_instagram_access_token ) ) {

            if( ! isset( $data->meta->error_message ) && ! isset( $data->error_message ) && $instagram_generic_response !== $response || isset( $data->meta->error_message ) && 'This client has not been approved to access this resource.' === $data->meta->error_message ){
                 echo sprintf(
                    esc_html__( '%1$s%2$sCreate Instagram Feed%3$s', 'feed-them-social' ),
                    '<div class="fts-successful-api-token fts-special-working-wrap">',
                    '<a class="fts-instagram-successful-api-token fts-success-token-content" href="#instagram_feed">',
                    '</a></div>'
                );
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
        $expiration_time = $fts_instagram_access_token_expires_in;

            ?>
            <script>
                    // This script is to display the countdown timer for the access token expiration.
                    // SetTimout so we can get the latest time saved to DB from previous ajax call.
                    // This is a problem I can see because of all the different ajax calls happening.
                    // In the future consolidating all ajax calls in needed.
                    setTimeout(function () {

                        var check_exp_time = jQuery('#fts_instagram_custom_api_token_expires_in').val();
                        // Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
                        var countDownDate = new Date( check_exp_time * 1000 );
                        // console.log(countDownDate);

                        // Get todays date and time
                        var right_now = new Date().getTime();


                        if ( '' !== check_exp_time && right_now > check_exp_time ) {

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

                                jQuery('.fts-exp-time-wrapper .feed_them_social-admin-input-label').append('<br/><span id="fts-timer"></span>');
                                jQuery('#fts-timer').html( days + "d " + hours + "h " + minutes + "m " + seconds + "s " );

                                // If the count down is finished, write some text
                                if (distance < 0) {
                                    clearInterval(x);
                                    jQuery('.fts-success').fadeIn();
                                    jQuery('#fts-timer').html( "Token Expired, refresh page to get new a token." );
                                }
                            }, 1000);
                        }

                    }, 600);
            </script>
            <?php

        // YO! making it be time() < $expiration_time to test ajax, otherwise it should be time() > $expiration_time
        if (  ! empty( $fts_instagram_access_token ) && time() > $expiration_time ) {
            // Refresh token action!
            $this->feed_functions->feed_them_instagram_refresh_token( $feed_cpt_id );
        }
       ?>
     </div>
    <?php
   }
}//end class