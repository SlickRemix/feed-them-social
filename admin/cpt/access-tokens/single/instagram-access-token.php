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


        $fts_instagram_access_token             = $this->feed_functions->get_feed_setting( $post_id, 'fts_instagram_custom_api_token' );
        $fts_instagram_access_token_expires_in  = $this->feed_functions->get_feed_setting( $post_id, 'fts_instagram_custom_api_token_expires_in' );
        $fts_instagram_custom_id                = $this->feed_functions->get_feed_setting( $post_id, 'fts_instagram_custom_id' );
        $user_id_basic                          = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['user_id'] ) : $fts_instagram_custom_id;
        $access_token                           = isset( $_GET['code'], $_GET['feed_type']  ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field( $_GET['code'] ) : $fts_instagram_access_token;
        $access_token_expires_in                = isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ? sanitize_text_field(  $_GET['expires_in'] - 4579200 ) : $fts_instagram_access_token_expires_in;
                    ?>
                <script>
                    jQuery(document).ready(function ($) {

                        jQuery('#instagram-token-master-wrap h3').click(function () {
                            jQuery(".fts-token-wrap .feed_them_social-admin-input-label, .fts-token-wrap input").toggle();
                        });

                        <?php if ( isset( $_GET['code'], $_GET['feed_type'] ) && 'instagram_basic' === $_GET['feed_type'] ) {?>
                        $('#fts_instagram_custom_api_token').val('');
                        $('#fts_instagram_custom_api_token').val($('#fts_instagram_custom_api_token').val() + '<?php echo sanitize_text_field( $access_token ); ?>');
                        $('#fts_instagram_custom_id').val('');
                        $('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + '<?php echo esc_js( $user_id_basic ); ?>');
                        $('#fts_instagram_custom_api_token_expires_in').val('');
                        $('#fts_instagram_custom_api_token_expires_in').val($('#fts_instagram_custom_api_token_expires_in').val() + '<?php echo esc_js( strtotime( '+' . $access_token_expires_in . ' seconds' ) ); ?>');

                        function fts_ajax_cpt_save() {

                            var newUrl = ftg_mb_tabs.submit_msgs.fts_post;
                            window.location.replace(newUrl + '#instagram-token-master-wrap');

                            jQuery( '.post-type-fts .wrap form#post' ).ajaxSubmit({
                                beforeSend: function () {
                                    jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-saving-form'></div></div></div>");
                                    jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.saving_msg).show();
                                    jQuery('#publishing-action .spinner').css("visibility", "visible");

                                },
                                success: function ( response ) {
                                    console.log( response );
                                    jQuery('#ftg-saveResult').html("<div class='ftg-overlay-background'><div class='ftg-relative-wrap-overlay'><div id='ftg-saveMessage' class='ftg-successModal ftg-success-form'></div></div></div>");
                                    jQuery('#ftg-saveMessage').append(ftg_mb_tabs.submit_msgs.success_msg).show();
                                    jQuery('#publishing-action .spinner').css("visibility", "hidden");

                                    setTimeout("jQuery('.ftg-overlay-background').hide();", 400);

                                        location.reload();
                                    // We change the text from Updating... at the bottom of a long page to Update.
                                    jQuery('.updatefrombottom a.button-primary').html("Update");
                                }
                            });
                            return false;
                        }
                        fts_ajax_cpt_save();


                        <?php } ?>
                    });
                </script>
                <?php

                // $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $fts_instagram_access_token );

                $insta_url = esc_url_raw( 'https://graph.instagram.com/me?fields=id,username&access_token=' . $fts_instagram_access_token );

                // Get Data for Instagram!
                $response = wp_remote_fopen( $insta_url );

                // Error Check!
                $data = json_decode( $response );

                /* echo '<pre>';
                  print_r( $data );
                 echo '</pre>';*/

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

        if ( ! empty( $data ) ) {

            if( ! isset( $data->meta->error_message ) && ! isset( $data->error_message ) || isset( $data->meta->error_message ) && 'This client has not been approved to access this resource.' === $data->meta->error_message ){
                 echo sprintf(
                    esc_html__( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s', 'feed-them-social' ),
                    '<div class="fts-successful-api-token">',
                    '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' . $custom_instagram_link_hash ) . '">',
                    '</a></div>'
                );
            }

            if ( isset( $data->data->error->message ) && ! empty( $user_id_basic ) || isset( $data->error->message ) && ! empty( $user_id_basic ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $data->error->message ) {
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

                if ( isset( $data->data->error->message ) && empty( $user_id_basic ) || isset( $data->error->message ) && empty( $user_id_basic ) ) {
                    echo sprintf(
                        esc_html__( '%1$sTo get started, please click the button above to retrieve your Access Token.%2$s', 'feed-them-social' ),
                        '<div class="fts-failed-api-token get-started-message">',
                        '</div>'
                    );
                }
            }
        }
        else {
                echo sprintf(
                    esc_html__( '%1$sTo get started, please click the button above to retrieve your Access Token.%2$s', 'feed-them-social' ),
                    '<div class="fts-failed-api-token get-started-message">',
                    '</div>'
                );
        }

        // Take the time() + $expiration_time will equal the current date and time in seconds, then we add the 60 days worth of seconds to the time.
        // That gives us the time to compare, of 60 days to the current date and Time.
        // For now we are going to get a new token every 7 days just to be on the safe side.
        // That means we will negate 53 days from the seconds which is 4579200 <-- https://www.convertunits.com/from/60+days/to/seconds
        // We get 60 days to refresh the token, if it's not refreshed before then it will expire.
        $expiration_time = $fts_instagram_access_token_expires_in;


        if ( time() < $expiration_time ) {
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

                    jQuery('.fts-exp-time-wrapper .feed_them_social-admin-input-label').append('<br/><span id="fts-timer"></span>');
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
            $this->feed_them_instagram_refresh_token();
        }
   }

    /**
     * FTS Check Instagram Token Validity
     *
     * @since 2.3.3
     */
    public function feed_them_instagram_refresh_token() {

        $fts_refresh_token_nonce = wp_create_nonce( 'fts_refresh_token_nonce' );

        if ( wp_verify_nonce( $fts_refresh_token_nonce, 'fts_refresh_token_nonce' ) ) {

            // Used some methods from this link http://ieg.wnet.org/2015/09/using-oauth-in-wordpress-plugins-part-2-persistence/
            // save all 3 get options: happens when clicking the get access token button on the instagram options page!
            if ( isset( $_GET['access_token'],  $_GET['expires_in'] ) ) {
                $button_pushed                     = 'yes';
                $clienttoken_post['access_token']  = sanitize_text_field( wp_unslash( $_GET['access_token'] ) );
                $auth_obj['access_token']          = sanitize_text_field( wp_unslash( $_GET['access_token'] ) );
                $auth_obj['expires_in']            = sanitize_key( wp_unslash( $_GET['expires_in'] ) );
            } else {
                // refresh token!
                $button_pushed    = 'no';
                $check_token =  get_option( 'fts_instagram_custom_api_token' );
                $check_basic_token_value = false !== $this->data_protection->decrypt( $check_token ) ? $this->data_protection->decrypt( $check_token ) : $check_token;
                $oauth2token_url  = esc_url_raw( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $check_basic_token_value );

                $response = wp_remote_get( $oauth2token_url );

                $auth_obj = json_decode( wp_remote_retrieve_body( $response  ), true );

                // print_r( $auth_obj['expires_in'] );

                // Take the time() + $expires_in will equal the current date and time in seconds plus 60 days in seconds.
                // For now we are going to get a new token every 7 days just to be on the safe side.
                // That means we will negate 53 days from the seconds which is 4579200 <-- https://www.convertunits.com/from/60+days/to/seconds
                // We get 60 days to refresh the token, if it's not refreshed before then it will expire.

                $time_minus_fiftythree_days = $auth_obj['expires_in'] - 4579200;
                $expires_in = $time_minus_fiftythree_days + time();

                // test.
                // echo ' asdfasdfasdfasdf ';
                // This is our refresh token response;
                // print_r($response['body']);
                // test.
                //$auth_obj['access_token'] = '';

                // Return if no access token queried from refresh token. This will stop error on front end feed if cached already.
                if( empty( $auth_obj['access_token'] ) ){
                    return;
                }

                $encrypted_token = $this->data_protection->encrypt( $auth_obj['access_token'] );

            }

            // use for testing in script below.
            //console.log( '<?php print_r($response['body']) ? >' );

            ?>
            <script>
                jQuery(document).ready(function () {

                    jQuery.ajax({
                        data: {
                            action: "fts_refresh_token_ajax",
                            access_token: '<?php echo esc_js( $encrypted_token ); ?>',
                            expires_in: '<?php echo esc_js( $expires_in ); ?>',
                            button_pushed: '<?php echo esc_js( $button_pushed ); ?>',
                            feed: 'instagram'
                        },
                        type: 'POST',
                        url: ftsAjax.ajaxurl,
                        success: function (response) {
                            console.log(response);
                            <?php
                            if ( isset( $_GET['page'] ) && 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] ) {

                            $user_id        = $auth_obj;
                            $error_response = 'Sorry, this content isn\'t available right now' ? 'true' : 'false';
                            $type_of_key = __( 'Access Token', 'feed-them-social' );

                            // Error Check!
                            if ( 'true' === $error_response ) {
                                $fts_instagram_message = sprintf(
                                    esc_html( '%1$s This %2$s does not appear to be a valid access token. instagram responded with: %3$s %4$s ', 'feed-them-social' ),
                                    '<div class="fts-failed-api-token">',
                                    esc_html( $type_of_key ),
                                    esc_html( $user_id->error->errors[0]->message ),
                                    '</div><div class="clear"></div>'
                                );
                            }
                            else {
                                $fts_instagram_message = sprintf(
                                    esc_html( '%1$s Your %2$s is working! Generate your shortcode on the %3$s settings page.%4$s %5$s', 'feed-them-social' ),
                                    '<div class="fts-successful-api-token">',
                                    esc_html( $type_of_key ),
                                    '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
                                    '</a>',
                                    '</div><div class="clear"></div>'
                                );
                            } ?>
                            jQuery('#fts_instagram_custom_api_token, #fts_instagram_custom_api_token_expires_in').val('');

                            <?php if ( isset( $_GET['access_token'], $_GET['expires_in'] ) ) { ?>
                            jQuery('#fts_instagram_custom_api_token').val(jQuery('#fts_instagram_custom_api_token').val() + '<?php echo esc_js( $clienttoken_post['access_token'] ); ?>');
                            jQuery('.fts-failed-api-token').hide();

                            if (!jQuery('.fts-successful-api-token').length) {
                                jQuery('.fts-instagram-last-row').append('<?php echo $fts_instagram_message; ?>');
                            }
                            <?php
                            } else {
                            ?>
                            if (jQuery('.fts-failed-api-token').length) {
                                jQuery('.fts-instagram-last-row').append('<?php echo $fts_instagram_message; ?>');
                                jQuery('.fts-failed-api-token').hide();
                            }
                            <?php } ?>
                            jQuery('#fts_instagram_custom_api_token').val(jQuery('#fts_instagram_custom_api_token').val() + '<?php echo esc_js( $auth_obj['access_token'] ); ?>');
                            jQuery('#fts_instagram_custom_api_token_expires_in').val(jQuery('#fts_instagram_custom_api_token_expires_in').val() + '<?php echo esc_js( strtotime( '+' . $auth_obj['expires_in'] . ' seconds' ) ); ?>');
                            jQuery('<div class="fa fa-check-circle fa-3x fa-fw fts-success"></div>').insertBefore('.feed-them-social-admin-input-wrap.fts-success-class .fts-clear');
                            jQuery('.fts-success').fadeIn('slow');
                            <?php } ?>
                            return false;
                        }
                    }); // end of ajax()
                    return false;
                }); // end of document.ready
            </script>
            <?php
            // return $auth_obj['access_token'];
        }
    }
}//end class