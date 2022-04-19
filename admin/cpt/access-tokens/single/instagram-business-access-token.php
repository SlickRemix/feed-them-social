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
 * Class Instagram_Access_Options
 *
 * @package feedthemsocial
 * @since 3.0.0
 */
class Instagram_Access_Options {

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
	 * Access Token Options
	 *
	 * @since 3.0.0
	 */
	public function access_options() {

        $post_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : '';
        $post_url = add_query_arg( array(
            'post' => $post_id,
        ), admin_url( 'post.php' ) );


    $fts_instagram_access_token             = get_option( 'fts_instagram_custom_api_token' );
    $fts_instagram_access_token_expires_in  = get_option( 'fts_instagram_custom_api_token_expires_in' );
    $fts_instagram_custom_id                = get_option( 'fts_instagram_custom_id' );
    $user_id_basic                          = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['user_id'] ) : $fts_instagram_custom_id;
    $access_token_basic                     = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['code'] ) : $fts_instagram_access_token;
    $access_token                           = isset( $_GET['code'], $_GET['feed_type'] ) && 'original_instagram' === $_GET['feed_type'] ? sanitize_text_field( $_GET['code'] ) : $access_token_basic;
    $access_token_expires_in                = isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field(  $_GET['expires_in'] ) : $fts_instagram_access_token_expires_in;



?>
<script>
    jQuery(document).ready(function ($) {
        <?php if ( isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ) {
        $code_token =  sanitize_text_field( $_GET['code'] );
        ?>
        $('#fts_instagram_custom_api_token').val('');
        $('#fts_instagram_custom_api_token').val($('#fts_instagram_custom_api_token').val() + '<?php echo esc_js( $code_token ); ?>');

        <?php if ( 'original_instagram' === $_GET['feed_type'] ){ ?>
        $('#fts_instagram_custom_id').val('');
        var str = '<?php echo esc_js( $code_token ); ?>';
        $('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + str.split('.', 1));
        <?php }
        elseif ( 'instagram_basic' === $_GET['feed_type'] ){ ?>

        $('#fts_instagram_custom_id').val('');
        $('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + '<?php echo esc_js( $user_id_basic ); ?>');
        <?php } ?>
        <?php } ?>
    });
</script>
                <?php

                // $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $fts_instagram_access_token );

                $insta_url = esc_url_raw( 'https://graph.instagram.com/me?fields=id,username&access_token=' . $fts_instagram_access_token );

                // Get Data for Instagram!
                $response = wp_remote_fopen( $insta_url );
                // Error Check!
                $test_app_token_response = json_decode( $response );

                //  echo '<pre>';
                //    print_r( $test_app_token_response );
                //   echo '</pre>';


                // echo '<pre>';
                // print_r($test_app_token_response);
                // echo '</pre>';

                echo sprintf(
                    esc_html__( '%1$sLogin and get my Access Token%2$s %3$sNot working?%4$s', 'feed-them-social' ),
                    '<a href="' . esc_url( 'https://api.instagram.com/oauth/authorize?app_id=206360940619297&redirect_uri=https://www.slickremix.com/instagram-basic-token/&response_type=code&scope=user_profile,user_media&state=' . $post_url ) . '" class="fts-instagram-get-access-token">',
                    '</a>',
                    '<a href="mailto:support@slickremix.com" class="fts-admin-button-no-work" style="margin-top: 14px; display: inline-block">',
                    '</a>'
                );
                ?>

                <div class="fts-clear"></div>



        <?php


        // Take the time() + $expiration_time will equal the current date and time in seconds, then we add the 60 days worth of seconds to the time.
        // That gives us the time to compare, of 60 days to the current date and Time.
        // For now we are going to get a new token every 7 days just to be on the safe side.
        // That means we will negate 53 days from the seconds which is 4579200 <-- https://www.convertunits.com/from/60+days/to/seconds
        // We get 60 days to refresh the token, if it's not refreshed before then it will expire.
        $expiration_time = '' !== get_option( 'fts_instagram_custom_api_token_expires_in' ) ? get_option( 'fts_instagram_custom_api_token_expires_in' ) : '';


        if ( time() < $expiration_time  && 'yes' === $debug ) {
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
                    var days    = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    jQuery('<span id="fts-timer"></span>').insertBefore('.fts-exp-time-wrapper .fts-clear');
                    document.getElementById("fts-timer").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

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
        if (  ! empty( $check_token ) && time() > $expiration_time ) {
            // refresh token action!
            // echo ' WTF ';
            $this->feed_functions->feed_them_instagram_refresh_token();
        } ?>

        <div class="feed-them-social-admin-input-wrap fts-instagram-last-row" style="margin-top: 0; padding-top: 0">
            <?php
            // Error Check
            // if the combined streams plugin is active we won't allow the settings page link to open up the Instagram Feed, instead we'll remove the #feed_type=instagram and just let the user manually select the combined streams or single instagram feed.
            if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
                $custom_instagram_link_hash = '';
            } else {
                $custom_instagram_link_hash = '#feed_type=instagram';
            }

            str_replace(".", ".", $fts_instagram_access_token, $count);

            if( ! empty( $fts_instagram_access_token ) && 0 !== $count ){
                echo sprintf(
                    esc_html__( '%1$sThe %2$sLegacy API will be depreciated as of March 31st, 2020%3$s in favor of the new Instagram Graph API and the Instagram Basic Display API. Please click the the button above to reconnect your account or you can connect as a Business account below. You must also generate a new shortcode and replace your existing one.%4$s', 'feed-them-social' ),
                    '<div class="fts-failed-api-token instagram-failed-message">',
                    '<a href="' . esc_url( 'https://www.instagram.com/developer/' ) . '" target="_blank">',
                    '</a>',
                    '</div>'
                );
            }
            elseif ( ! isset( $test_app_token_response->error ) && ! empty( $fts_instagram_access_token ) ) {
                echo sprintf(
                    esc_html__( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s', 'feed-them-social' ),
                    '<div class="fts-successful-api-token">',
                    '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' . $custom_instagram_link_hash ) . '">',
                    '</a></div>'
                );
            } elseif ( isset( $test_app_token_response->error ) && ! empty( $fts_instagram_access_token ) ) {
                $text = isset( $test_app_token_response->error->message ) ? $test_app_token_response->error->message : $test_app_token_response->error->message;
                echo sprintf(
                    esc_html__( '%1$sOh No something\'s wrong. %2$s Please try clicking the button again to get a new access token. If you need additional assistance please email us at support@slickremix.com %3$s', 'feed-them-social' ),
                    '<div class="fts-failed-api-token instagram-failed-message">',
                    esc_html( $text ),
                    '</div>'
                );
            }

            $feed_type = isset( $_GET['feed_type'] ) ? $_GET['feed_type'] : '';

            if ( empty( $fts_instagram_access_token ) && 'original_instagram' !== $feed_type ) {
                echo sprintf(
                    esc_html__( '%1$sYou are required to get an access token to view your photos.%2$s', 'feed-them-social' ),
                    '<div class="fts-failed-api-token instagram-failed-message">',
                    '</div>'
                );
            }
            ?>
            <div class="fts-clear"></div>






                    <?php

                    echo sprintf(
                        esc_html__( 'Click the button below to get an access token. This gives us read-only access to get your Instagram posts. Your Instagram must be linked to a Facebook Business Page for this option to work. %1$sRead Instructions%2$s.', 'feed-them-social' ),
                        '<a target="_blank" href="' . esc_url( 'https://www.slickremix.com/docs/link-instagram-account-to-facebook/' ) . '">',
                        '</a>'
                    );
                    ?>

                        <?php

                        // call to get instagram account attached to the facebook page
                        // 1844222799032692 = slicktest fb page (dev user)
                        // 1844222799032692?fields=instagram_business_account&access_token=
                        // This redirect url must have an &state= instead of a ?state= otherwise it will not work proper with the fb app. https://www.slickremix.com/instagram-token/&state=.
                        echo sprintf(
                            esc_html__( '%1$sLogin and get my Access Token%2$s', 'feed-them-social' ),
                            '<a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/instagram-token/&state=' . $post_url . '&scope=pages_show_list,pages_read_engagement,instagram_basic' ) . '" class="fts-facebook-get-access-token">',
                            '</a>'
                        );

                $token_expiration =  get_option( 'fts_facebook_instagram_custom_api_token_expiration' );
                $test_app_token_id_biz = get_option( 'fts_facebook_instagram_custom_api_token' );
                $check_biz_token_value = false !== $this->data_protection->decrypt( $test_app_token_id_biz ) ? $this->data_protection->decrypt( $test_app_token_id_biz ) : $test_app_token_id_biz;
                $check_biz_encrypted = false !== $this->data_protection->decrypt( $test_app_token_id_biz ) ? 'encrypted' : '';

                if ( ! empty( $test_app_token_id ) || ! empty( $test_app_token_id_biz ) ) {

                    $test_app_token_id = $check_biz_token_value;

                    $test_app_token_url = array(
                        'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $test_app_token_id . '&access_token=' . $test_app_token_id,
                    );


                    // Test App ID
                    $test_app_token_response = $this->feed_functions->fts_get_feed_json( $test_app_token_url );
                    $test_app_token_response = json_decode( $test_app_token_response['app_token_id'] );


                    /*echo '<pre>';
                    print_r($refresh_app_token_url_response);
                    echo '</pre>';*/
                }
                ?>
                <div class="clear"></div>
                <div class="feed-them-social-admin-input-wrap fts-fb-token-wrap" id="fts-fb-token-wrap" style="margin-bottom:0px;">
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                        <?php esc_html_e( 'Instagram ID', 'feed-them-social' ); ?>
                    </div>

                    <input type="text" name="fts_facebook_instagram_custom_api_token_user_id" class="feed-them-social-admin-input" id="fts_facebook_instagram_custom_api_token_user_id" value="<?php echo esc_attr( get_option( 'fts_facebook_instagram_custom_api_token_user_id' ) ); ?>"/>
                    <div class="clear" style="margin-bottom:10px;"></div>
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                        <?php esc_html_e( 'Access Token Required', 'feed-them-social' ); ?>
                    </div>

                    <input type="text" name="fts_facebook_instagram_custom_api_token" class="feed-them-social-admin-input" id="fts_facebook_instagram_custom_api_token" data-token="<?php echo $check_biz_encrypted ?>" value="<?php echo $check_biz_token_value ?>" />
                    <div class="clear"></div>

                    <input type="text" hidden name="fts_facebook_instagram_custom_api_token_user_name" class="feed-them-social-admin-input" id="fts_facebook_instagram_custom_api_token_user_name" value="<?php echo esc_attr( get_option( 'fts_facebook_instagram_custom_api_token_user_name' ) ); ?>"/>
                    <input type="text" hidden name="fts_facebook_instagram_custom_api_token_profile_image" class="feed-them-social-admin-input" id="fts_facebook_instagram_custom_api_token_profile_image" value="<?php echo esc_attr( get_option( 'fts_facebook_instagram_custom_api_token_profile_image' ) ); ?>"/>

                    <div class="clear"></div>
                    <?php
                    if ( ! empty( $test_app_token_response ) && ! empty( $test_app_token_id_biz ) ) {

                        if ( isset( $test_app_token_response->data->is_valid ) || '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' === $test_app_token_response->error->message ) {
                            $fb_id   = get_option( 'fts_facebook_instagram_custom_api_token_user_id' );
                            $fb_name = get_option( 'fts_facebook_instagram_custom_api_token_user_name' );
                            echo '<div class="fts-successful-api-token fts-special-working-wrap">';

                            if ( ! empty( $fb_id ) && ! empty( $fb_name ) && ! empty( $test_app_token_id_biz ) ) {
                                echo '<h3><a href="' . esc_url( 'https://www.facebook.com/' . get_option( 'fts_facebook_custom_api_token_user_id' ) ) . '" target="_blank">' . wp_kses(
                                        $fb_name,
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ) . '</a></h3>';
                            }

                            echo sprintf(
                                esc_html__( 'Your access token is working! Generate your shortcode on the %1$sSettings Page%2$s', 'feed-them-social' ),
                                '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' . $custom_instagram_link_hash ) . '">',
                                '</a>'
                            );

                            echo '</div>';
                        }
                        if ( isset( $test_app_token_response->data->error->message ) && ! empty( $test_app_token_id ) || isset( $test_app_token_response->error->message ) && ! empty( $test_app_token_id ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $test_app_token_response->error->message ) {
                            if ( isset( $test_app_token_response->data->error->message ) ) {
                                echo sprintf(
                                    esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                                    '<div class="fts-failed-api-token">',
                                    esc_html( $test_app_token_response->data->error->message ),
                                    '</div>'
                                );
                            }
                            if ( isset( $test_app_token_response->error->message ) ) {
                                echo sprintf(
                                    esc_html__( '%1$sOh No something\'s wrong. %2$s. Please click the button above to retrieve a new Access Token.%3$s', 'feed-them-social' ),
                                    '<div class="fts-failed-api-token">',
                                    esc_html( $test_app_token_response->error->message ),
                                    '</div>'
                                );
                            }

                            if ( isset( $test_app_token_response->data->error->message ) && empty( $test_app_token_id ) || isset( $test_app_token_response->error->message ) && empty( $test_app_token_id ) ) {
                                echo sprintf(
                                    esc_html__( '%1$sTo get started, please click the button above to retrieve your Access Token.%2$s', 'feed-them-social' ),
                                    '<div class="fts-failed-api-token get-started-message">',
                                    '</div>'
                                );
                            }
                        }
                    } else {
                        if ( ! isset( $_GET['return_long_lived_token'] ) || isset( $_GET['reviews_token'] ) ) {
                            echo sprintf(
                                esc_html__( '%1$sTo get started, please click the button above to retrieve your Access Token.%2$s', 'feed-them-social' ),
                                '<div class="fts-failed-api-token get-started-message">',
                                '</div>'
                            );
                        }
                    }
                    ?>
                    <div class="clear"></div>

                    <?php

                    if ( isset( $_GET['return_long_lived_token'], $_GET['feed_type'] ) && ! isset( $_GET['reviews_token'] ) && 'instagram_basic' !== $_GET['feed_type'] ) {
                        // Echo our shortcode for the page token list with loadmore button
                        // These functions are on feed-them-functions.php!
                        echo do_shortcode( '[fts_fb_page_token]' );

                    }
                    ?>
                </div>

                <div class="clear"></div>
            </div>
            <!--/fts-facebook-feed-styles-input-wrap-->

<?php
    }
}//end class