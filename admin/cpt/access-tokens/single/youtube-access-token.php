<?php
 /**
 * Feed Them Social - Youtube Access Options
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
 * Class Youtube_Access_Options
 *
 * @package feedthemsocial
 * @since 3.0.0
 */
class Youtube_Access_Options {

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
	 * Access Token Options
	 *
	 * @since 3.0.0
	 */
	public function access_options() {

        $post_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : '';
        $post_url = add_query_arg( array(
            'post' => $post_id,
        ), admin_url( 'post.php' ) );

       ?>
        <div id="youtube-token-master-wrap" class="feed-them-social-admin-wrap">
         <?php
                $youtube_api_key      = get_option( 'youtube_custom_api_token' );
                $youtube_access_token = get_option( 'youtube_custom_access_token' );
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

                ?>

                <div class="feed-them-social-admin-input-wrap" style="padding-top: 0">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php echo esc_html__( 'YouTube API Key', 'feed-them-social' ); ?>
                        </h3>
                        <p><?php echo esc_html__( 'Click the button below to get an access token. This gives us read-only access to your YouTube videos.', 'feed-them-social' ); ?>
                        </p>
                        <p>
                            <?php
                            echo sprintf(
                                esc_html__( '%1$sLogin and get my Access Token %2$s', 'feed-them-social' ),
                                '<a href="' . esc_url( 'https://www.slickremix.com/youtube-token/?redirect_url=' . $post_url ) . '" class="fts-youtube-get-access-token">',
                                '</a>'
                            );
                            ?>
                        </p>

                    </div>

                    <a href="https://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank" class="fts-admin-button-no-work">Not working?</a>
                </div>


                <div class="fts-clear"></div>
                <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0;">

                    <?php
                    $extra_keys    = '' === get_option( 'youtube_custom_api_token' ) ? 'display:none' : '';
                    $extra_keys_no = get_option( 'youtube_custom_api_token' );
                    if ( ! empty( $extra_keys_no ) ) {
                        $extra_keys_no = 'display:none';
                    }
                    ?>
                    <div class="fts-youtube-add-all-keys-click-option"><label for="fts-custom-tokens-youtube"><input type="checkbox" id="fts-custom-tokens-youtube" name="fts_youtube_custom_tokens" value="1" <?php echo checked( '1', '' === $extra_keys ); ?>> Add your own API
                            Key?</label></div>

                    <div class="fts-clear"></div>

                    <div class="youtube-extra-keys" style="<?php echo esc_attr( $extra_keys ); ?>">
                        <div class="youtube-extra-keys-text" style="<?php echo esc_attr( $extra_keys_no ); ?>">
                            <a href="<?php echo esc_url( 'https://www.slickremix.com/docs/get-api-key-for-youtube/' ); ?>" target="_blank"><?php echo esc_html__( 'Manually create YouTube API Key', 'feed-them-social' ); ?></a>
                        </div>

                        <div class="feed-them-social-admin-input-label fts-youtube-border-bottom-color-label">
                            <?php echo esc_html__( 'API Key Required', 'feed-them-social' ); ?> <?php echo get_option('fts_youtube_custom_tokens'); ?>
                        </div>

                        <input type="text" name="youtube_custom_api_token" class="feed-them-social-admin-input" id="youtube_custom_api_token" value="<?php echo esc_attr( get_option( 'youtube_custom_api_token' ) ); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                </div>
                <?php
                // Add yes to show the expiration time and js that runs it below!
                $debug = 'yes';
                ?>
                <div class="hide-button-tokens-options" style="<?php echo esc_attr( $extra_keys_no ); ?>;">
                    <div class="feed-them-social-admin-input-wrap" style="<?php
                    if ( 'no' === $debug ) {
                        ?>
                        display:none<?php } ?>">
                        <div class="feed-them-social-admin-input-label">
                            <?php echo esc_html__( 'Refresh Token', 'feed-them-social' ); ?>
                        </div>
                        <input type="text" name="youtube_custom_refresh_token" readonly class="feed-them-social-admin-input" id="youtube_custom_refresh_token" value="<?php echo esc_attr( get_option( 'youtube_custom_refresh_token' ) ); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0;">
                        <div class="feed-them-social-admin-input-label">
                            <?php echo esc_html__( 'Access Token Required', 'feed-them-social' ); ?>
                        </div>
                        <input type="text" name="youtube_custom_access_token" class="feed-them-social-admin-input" id="youtube_custom_access_token" value="<?php echo esc_attr( get_option( 'youtube_custom_access_token' ) ); ?>"/>
                        <div class="fts-clear"></div>
                    </div>

                    <div class="feed-them-social-admin-input-wrap fts-exp-time-wrapper" style="margin-top:10px;<?php
                    if ( 'no' === $debug ) {
                        ?>display:none<?php } ?>">
                        <div class="feed-them-social-admin-input-label">
                            <?php echo esc_html__( 'Expiration Time for Access Token', 'feed-them-social' ); ?>
                        </div>
                        <input type="text" name="youtube_custom_token_exp_time"  class="feed-them-social-admin-input" id="youtube_custom_token_exp_time" value="<?php echo esc_attr( get_option( 'youtube_custom_token_exp_time' ) ); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                </div>

                <div class="feed-them-social-admin-input-wrap  fts-youtube-last-row" style="margin-top:0;">
                    <script>
                        jQuery(document).ready(function ($) {
                            jQuery('#fts-custom-tokens-youtube').click(function () {
                                jQuery(".youtube-extra-keys, .hide-button-tokens-options").toggle();
                            });
                        });
                    </script>
                    <?php
                    if ( isset( $_GET['refresh_token'] ) && isset( $_GET['code'] ) && isset( $_GET['expires_in'] ) ) {
                        // START AJAX TO SAVE TOKEN TO DB RIGHT AWAY SO WE CAN DO OUR NEXT SET OF CHECKS
                        // new token action!
                        $this->feed_functions->feed_them_youtube_refresh_token();
                    }

                    $expiration_time = '' !== get_option( 'youtube_custom_token_exp_time' ) ? get_option( 'youtube_custom_token_exp_time' ) : 500;

                    // Give the access token a 5 minute buffer (300 seconds) before getting a new one.
                    $expiration_time = $expiration_time - 300;
                    // Test Liner!
                    if ( time() < $expiration_time && empty( $youtube_api_key ) && 'yes' === $debug ) {
                        ?>
                        <script>
                            // Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
                            var countDownDate = new Date( <?php echo esc_js( $expiration_time ); ?> * 1000 ); // <--phpStorm shows error but it's false.

                            // Update the count down every 1 second
                            var x = setInterval(function () {

                                // Get todays date and time
                                var now = new Date().getTime();

                                // Find the distance between now an the count down date
                                var distance = countDownDate - now;

                                // Time calculations for days, hours, minutes and seconds
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Display the result in the element with id="demo"
                                jQuery('<span id="fts-timer"></span>').insertBefore('.hide-button-tokens-options .fts-exp-time-wrapper .fts-clear');
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

                    // YO! making it be time() < $expiration_time to test ajax, otherwise it should be time() > $expiration_time
                    if ( empty( $youtube_api_key ) && ! empty( $youtube_access_token ) && time() > $expiration_time ) {
                        // refresh token action!
                        $this->feed_functions->feed_them_youtube_refresh_token();
                    }

                    $user_id = $test_app_token_response;
                    $error_response = $test_app_token_response->error->errors[0]->message ? 'true' : 'false';
                    // print_r( $error_response );

                    if ( 'false' === $error_response && ! empty( $youtube_api_key ) ) {
                        $type_of_key = __( 'API key', 'feed-them-social' );
                    } elseif ( 'false' === $error_response && ! empty( $youtube_access_token ) ) {
                        $type_of_key = __( 'Access Token', 'feed-them-social' );
                    }

                    // Error Check!
                    if ( 'false' === $error_response && ! empty( $youtube_api_key ) || 'false' === $error_response && ! empty( $youtube_access_token ) && empty( $youtube_api_key ) ) {
                        echo sprintf(
                            esc_html__( '%1$s Your %2$s is working, time to customize your %3$s options.%4$s %5$s', 'feed-them-social' ),
                            '<div class="fts-successful-api-token">',
                            esc_html( $type_of_key ),
                            '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
                            '</a>',
                            '</div>'
                        );
                    } elseif ( 'true' === $error_response && ! empty( $youtube_api_key ) || 'true' === $error_response && ! empty( $youtube_access_token ) ) {
                        echo sprintf(
                            esc_html__( '%1$s This %2$s does not appear to be valid. YouTube responded with: %3$s %4$s ', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            esc_html( $type_of_key ),
                            esc_html( $user_id->error->errors[0]->message ),
                            '</div>'
                        );
                    }
                    if ( empty( $youtube_api_key ) && empty( $youtube_access_token ) ) {
                        echo sprintf(
                            esc_html__( '%1$s Click the button above or register for an API token to use the YouTube feed.%2$s', 'feed-them-social' ),
                            '<div class="fts-failed-api-token">',
                            '</div>'
                        );
                    }
                    ?>

                    <div class="fts-clear"></div>
                </div>

        </div>
        <!--/feed-them-social-admin-wrap-->
        <?php
	}
}//end class