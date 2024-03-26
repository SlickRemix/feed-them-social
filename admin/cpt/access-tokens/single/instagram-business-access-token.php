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

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Instagram_Business_Access_Functions
 *
 * @package feedthemsocial
 * @since 4.0.0
 */
class Instagram_Business_Access_Functions {

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
                    jQuery('.combine-instagram-business-access-token-placeholder div.fts-token-save, .instagram-facebook-access-token-placeholder div.fts-token-save, .instagram-facebook-access-token-placeholder span.fts-token-manual-save, .combine-instagram-business-access-token-placeholder span.fts-token-manual-save').click( function (e) {
                        e.preventDefault();

                        let myString = jQuery('#fts_facebook_instagram_custom_api_token').val();
                        let length = myString.length;
                        if(length > 300) {
                            alert('<?php echo esc_html__('You must add a valid Facebook Access Token to use the Save Token Manual feature. If there is already a value in the field, please remove it, update the page and try again.', 'feed-them-social'); ?>');
                            if(!jQuery('.fts-admin-token-settings-open').length ) {
                                jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
                                jQuery( this ).toggleClass( 'fts-feed-type-active' );
                                jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
                                jQuery( '#fts-feed-type h3' ).toggleClass( 'fts-admin-token-settings-open' );
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
                            fts_encrypt_token_ajax( codeArray, 'business', '#fts_facebook_instagram_custom_api_token', 'firstRequest');
                        }
                        else {
                            if( jQuery('.combine-instagram-business-access-token-placeholder').length !== 0 ){
                                fts_reload_toggle_click()
                            }
                            else {
                                jQuery( '.fts-token-wrap .feed-them-social-admin-input-label, .fts-token-wrap input, .fts-decrypted-view' ).toggle();
                                jQuery( this ).toggleClass( 'fts-feed-type-active' );
                                jQuery( '.fts-admin-token-settings' ).toggleClass( 'fts-admin-token-settings-open' );
                                jQuery( '#fts-feed-type h3' ).toggleClass( 'fts-admin-token-settings-open' );
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
                echo sprintf(
                    esc_html__( '%1$sLogin and Get my Access Token%2$s', 'feed-them-social' ),
                    '<div class="fts-clear fts-token-spacer"></div><a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/instagram-token/&state=' . urlencode( $post_url ) . '&scope=pages_show_list,pages_read_engagement,instagram_basic,business_management' ) . '" class="fts-facebook-get-access-token">',
                    '</a>'
                );
                ?>

            <div class="fts-settings-does-not-work-wrap">
                <span class="fts-token-manual-save"><?php esc_html_e( 'Save Token Manually', 'feed-them-social' ); ?></span>
                <span class="fts-admin-token-settings"><?php esc_html_e( 'Settings', 'feed-them-social' ); ?></span>
                <a href="javascript:;" class="fts-admin-button-no-work" onclick="fts_beacon_support_click()"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></a>
            </div>

            <?php
		        // Saved Feed Options!
                $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_cpt_id );

                $page_id            = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] : '';
                $access_token       = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token'] : '';
                $instagram_name     = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token_user_name'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token_user_name'] : '';
                $fb_name            = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token_fb_user_name'] )? $saved_feed_options['fts_facebook_instagram_custom_api_token_fb_user_name'] : '';

                // Decrypt Access Token?
                $decrypted_access_token = false !== $this->data_protection->decrypt( $access_token ) ?  $this->data_protection->decrypt( $access_token ) : $access_token;

                if ( ! empty( $page_id ) || ! empty( $access_token ) ) {

                    $test_app_token_url = array(
                        'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $decrypted_access_token .' &access_token=' . $decrypted_access_token,
                    );

                    // Check to see what the response is.
                    $response = $this->feed_functions->fts_get_feed_json( $test_app_token_url );
                    $data = json_decode( $response['app_token_id'] );

                    /*echo '<pre>';
                    print_r($data);
                    echo '</pre>';*/
                }
                ?>
                <div class="clear"></div>
                <div class="fts-fb-token-wrap fts-token-wrap" id="fts-fb-token-wrap">
                     <?php
                     if( !isset( $_GET['feed_type'] ) ) {
                         if ( !empty( $data ) ) {

                             if ( isset( $data->data->is_valid ) || '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' === $data->error->message ) {


                                 $insta_fb_text = '<a href="' . esc_url( 'https://www.facebook.com/' . $page_id ) . '" target="_blank"><span class="fts-insta-icon"></span>' . esc_html( $instagram_name ) . '<span class="fts-arrow-icon"></span><span class="fts-fb-icon"></span>' . esc_html( $fb_name ) . '</a>';

                                 if( 'combine-streams-feed-type' === $this->feed_functions->get_feed_option( $feed_cpt_id, 'feed_type' ) ){
                                     echo sprintf(
                                         esc_html__( '%1$s%2$sCreate Combined Feed%3$s', 'feed-them-social' ),
                                         '<div class="fts-successful-api-token fts-special-working-wrap">',
                                         '<a class="fts-instagram-business-combine-successful-api-token fts-success-token-content fts-combine-successful-api-token" href="#combine_streams_feed">',
                                         '</a></div>'
                                     );

                                 }
                                 else{
                                     echo sprintf(
                                         esc_html__( '%1$s%2$sCreate Instagram Feed%3$s', 'feed-them-social' ),
                                         '<div class="fts-successful-api-token fts-special-working-wrap">',
                                         '<a class="fts-instagram-business-successful-api-token fts-success-token-content" href="#instagram_feed">',
                                         '</a></div>'
                                     );
                                 }
                             }
                             if ( isset( $data->data->error->message ) && !empty( $data ) || isset( $data->error->message ) && !empty( $data ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $data->error->message ) {
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
                             }
                         }
                     }
                    ?>
                    <div class="clear"></div>

                    <?php

                    if ( isset( $_GET['return_long_lived_token'], $_GET['feed_type'] ) && 'instagram' === $_GET['feed_type'] ) {

                        if ( ! isset( $_GET['fts_oauth_nonce'] ) || 1 !== wp_verify_nonce( $_GET['fts_oauth_nonce'], 'fts_oauth_instagram_business' ) ) {
                            wp_die( __( 'Invalid instagram business oauth nonce', 'feed-them-social' ) );
                        }

                        // Echo our shortcode for the page token list with  Load More
                        // These functions are on feed-functions.php!
                        echo do_shortcode( '[fts_fb_page_token]' );

                    }
                  //  echo do_shortcode( '[feed_them_social cpt_id=' . esc_html( $_GET['post'] ) . ']' );
                    ?>
                </div>

                <div class="clear"></div>

<?php
    }
}//end class