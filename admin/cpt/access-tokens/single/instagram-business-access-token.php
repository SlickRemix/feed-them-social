<?php
 /**
 * Feed Them Social - Instagram Business Access Functions
 *
 * This page is used to retrieve and set access tokens for Instagram Business.
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
 * Class Instagram_Business_Access_Functions
 *
 * @package feedthemsocial
 * @since 3.0.0
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

                ?>
            <script>
                jQuery(document).ready(function ($) {
                    fts_social_icons_wrap_click();
                });
            </script>
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
                ?>
                <a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" target="_blank" class="fts-admin-button-no-work"><?php esc_html_e( 'Not working?', 'feed-them-social' ); ?></a>
                <?php

                $page_id = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_facebook_instagram_custom_api_token_user_id' );
                $app_token = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_facebook_instagram_custom_api_token' );
                $instagram_name = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_facebook_instagram_custom_api_token_user_name' );
                $fb_name = $this->feed_functions->get_feed_option( $feed_cpt_id, 'fts_facebook_instagram_custom_api_token_fb_user_name' );

                // SRL 4-12-22. Holding off on this until talk with Justin on how we should deal with this.
                $app_token_value = false !== $this->data_protection->decrypt( $app_token ) ? $this->data_protection->decrypt( $app_token ) : $app_token;
                $app_token_encrypted = false !== $this->data_protection->decrypt( $app_token ) ? 'encrypted' : '';

                if ( ! empty( $page_id ) || ! empty( $app_token ) ) {

                    $test_app_token_url = array(
                        'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $app_token_value .' &access_token=' . $app_token,
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
                <div class="feed-them-social-admin-input-wrap fts-fb-token-wrap fts-token-wrap" id="fts-fb-token-wrap">
                     <?php
                     if( !isset( $_GET['feed_type'] ) ) {
                         if ( !empty( $data ) ) {

                             if ( isset( $data->data->is_valid ) || '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' === $data->error->message ) {

                                 echo '<div class="fts-successful-api-token fts-special-working-wrap">';

                                 if ( !empty( $page_id ) && !empty( $fb_name ) && !empty( $app_token ) ) {
                                     echo '<h3><a href="' . esc_url( 'https://www.facebook.com/' . $page_id ) . '" target="_blank"><span class="fts-insta-icon"></span>' . $instagram_name . '<span class="fts-arrow-icon"></span><span class="fts-fb-icon"></span>' . $fb_name . '</a></h3>';
                                 }

                                 echo sprintf(
                                     esc_html__( 'Your access token is working! Now you can create your %1$sInstagram Feed%2$s', 'feed-them-social' ),
                                     '<a class="fts-instagram-successful-api-token" href="#instagram_feed">',
                                     '</a>.'
                                 );

                                 echo '</div>';
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

                    if ( isset( $_GET['return_long_lived_token'], $_GET['feed_type'] ) ) {
                        // Echo our shortcode for the page token list with loadmore button
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