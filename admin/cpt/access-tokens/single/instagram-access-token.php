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
		?>

        <div class="feed-them-social-admin-wrap">
        <h1>
			<?php esc_html_e( 'Facebook Feed Options', 'feed-them-social' ); ?>
        </h1>
        <!-- custom option for padding -->
        <form method="post" class="fts-facebook-feed-options-form" action="options.php" id="fts-facebook-feed-options-form">
        <br/>
		<?php

		$fts_fb_options_nonce = wp_create_nonce( 'fts-facebook-options-page-nonce' );

		if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-facebook-options-page-nonce' ) ) {

			// get our registered settings from the fts functions!
			settings_fields( 'fts-facebook-feed-style-options' );
			?>
            <div id="fb-token-master-wrap" class="feed-them-social-admin-input-wrap" style="padding-bottom:0px;">
                <div class="fts-title-description-settings-page" style="padding-top:0; border:none; margin-bottom:0px;">
                    <h3>
						<?php esc_html_e( 'Facebook API Token', 'feed-them-social' ); ?>
                    </h3>
					<?php esc_html_e( 'This Facebook Access Token is for Business Pages, Photos and Videos only and is simply used to display the feed. You must be an admin of the business page to get your token. This will NOT work for personal profiles or groups. ', 'feed-them-social' ); ?>
                    <p>
						<?php
						echo sprintf(
							esc_html__( '%1$sLogin and get my Access Token%2$s', 'feed-them-social' ),
							'<a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/facebook-token/&state=' . admin_url( 'admin.php?page=fts-facebook-feed-styles-submenu-page' ) . '&scope=pages_show_list,pages_read_engagement' ) . '" class="fts-facebook-get-access-token">',
							'</a>'
						);
						?>
                    </p>
                </div>
                <a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" target="_blank" class="fts-admin-button-no-work"><?php esc_html_e( 'Button not working?', 'feed-them-social' ); ?></a>
				<?php
				$test_app_token_id     = get_option( 'fts_facebook_custom_api_token' );
				$test_app_token_id_biz = get_option( 'fts_facebook_custom_api_token_biz' );
				if ( ! empty( $test_app_token_id ) || ! empty( $test_app_token_id_biz ) ) {
					$fts_fb_access_token    = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
					$test_app_token_url     = array(
						'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $test_app_token_id . '&access_token=' . $test_app_token_id,
					);
					$test_app_token_url_biz = array(
						'app_token_id_biz' => 'https://graph.facebook.com/debug_token?input_token=' . $test_app_token_id_biz . '&access_token=' . $test_app_token_id_biz . '&',
						/*'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=7054444020102908771&client_secret=7016612gg8c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjL3G2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'*/
					);
					// Test App ID
					// Leave these for reference:
					// App token for FTS APP2: 358962200939086|lyXQ5-zqXjvYSIgEf8mEhE9gZ_M
					// App token for FTS APP3: 705020102908771|rdaGxW9NK2caHCtFrulCZwJNPyY!
					$test_app_token_response     = $this->feed_functions->fts_get_feed_json( $test_app_token_url );
					$test_app_token_response     = json_decode( $test_app_token_response['app_token_id'] );
					$test_app_token_response_biz = $this->feed_functions->fts_get_feed_json( $test_app_token_url_biz );
					$test_app_token_response_biz = json_decode( $test_app_token_response_biz['app_token_id_biz'] );
				}
				?>
                <div class="clear"></div>
                <div class="feed-them-social-admin-input-wrap fts-fb-token-wrap" id="fts-fb-token-wrap" style="margin-bottom:0px;">
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
						<?php esc_html_e( 'Page ID', 'feed-them-social' ); ?>
                    </div>

                    <input type="text" name="fts_facebook_custom_api_token_user_id" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_id" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>"/>
                    <div class="clear" style="margin-bottom:10px;"></div>
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
						<?php esc_html_e( 'Access Token Required', 'feed-them-social' ); ?>
                    </div>

                    <input type="text" name="fts_facebook_custom_api_token" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token' ) ); ?>"/>
                    <div class="clear"></div>

                    <input type="text" hidden name="fts_facebook_custom_api_token_user_name" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_name' ) ); ?>"/>
                    <input type="text" hidden name="fts_facebook_custom_api_token_profile_image" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_profile_image" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_profile_image' ) ); ?>"/>

                    <div class="clear"></div>
					<?php
					if ( ! empty( $test_app_token_response ) && ! empty( $test_app_token_id ) ) {
						if ( isset( $test_app_token_response->data->is_valid ) || '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' === $test_app_token_response->error->message ) {
							$fb_id   = get_option( 'fts_facebook_custom_api_token_user_id' );
							$fb_name = get_option( 'fts_facebook_custom_api_token_user_name' );
							echo '<div class="fts-successful-api-token fts-special-working-wrap">';

							if ( ! empty( $fb_id ) && ! empty( $fb_name ) && ! empty( $test_app_token_id ) ) {
								echo '<a href="' . esc_url( 'https://www.facebook.com/' . get_option( 'fts_facebook_custom_api_token_user_id' ) ) . '" target="_blank"><img border="0" height="50" width="50" class="fts-fb-page-thumb" src="' . get_option( 'fts_facebook_custom_api_token_profile_image' ) . '"/></a><h3><a href="' . esc_url( 'https://www.facebook.com/' . get_option( 'fts_facebook_custom_api_token_user_id' ) ) . '" target="_blank">' . esc_html( $fb_name ) . '</a></h3>';
							}

							echo sprintf(
								esc_html__( 'Your Access Token is now working! Generate your shortcode on the %1$sSettings Page%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page#feed_type=facebook' ) . '">',
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

					if ( isset( $_GET['return_long_lived_token'] ) && ! isset( $_GET['reviews_token'] ) ) {
						// Echo our shortcode for the page token list with loadmore button
						// These functions are on feed-them-functions.php!
						echo do_shortcode( '[fts_fb_page_token]' );

					}
					?>
                </div>

                <div class="clear"></div>
            </div>
            <!--/fts-facebook-feed-styles-input-wrap-->

			<?php if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
                <!--  style="padding-top:0; border:none; margin-bottom:0px; -->
                <div id="fts-fb-reviews-wrap" class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page" style="margin-bottom:0px;">
                        <h3>
							<?php esc_html_e( 'Facebook Page Reviews Access Token', 'feed-them-social' ); ?>
                        </h3>
						<?php esc_html_e( 'This Facebook Access Token works for the Reviews feed only and is simply used to display the feed. You must be an admin of the page to get your token.', 'feed-them-social' ); ?>
                        <p>
							<?php
							echo sprintf(
								esc_html__( '%1$sLogin and get my Reviews Access Token%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/facebook-token/&state=' . admin_url( 'admin.php?page=fts-facebook-feed-styles-submenu-page' ) . '%26reviews_token=yes&scope=pages_show_list,pages_read_engagement' ) . '" class="fts-facebook-get-access-token">',
								'</a>'
							);
							?>
                        </p>

                    </div>

                    <a href="mailto:support@slickremix.com" target="_blank" class="fts-admin-button-no-work"><?php esc_html_e( 'Button not working?', 'feed-them-social' ); ?></a>

                    <div class="clear"></div>
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
						<?php esc_html_e( 'Page Reviews ID', 'feed-them-social' ); ?>
                    </div>
                    <input type="text" name="fts_facebook_custom_api_token_user_id_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_id_biz" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>"/>

                    <div class="clear" style="margin-bottom:10px;"></div>
                    <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
						<?php esc_html_e( 'Page Reviews Access Token', 'feed-them-social' ); ?>
                    </div>
                    <input type="text" name="fts_facebook_custom_api_token_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_biz" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>"/>
                    <input type="text" hidden name="fts_facebook_custom_api_token_user_name_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name_biz" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_name_biz' ) ); ?>"/>
                    <input type="text" hidden name="fts_facebook_custom_api_token_biz_profile_image" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_biz_profile_image" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_biz_profile_image' ) ); ?>"/>
                    <div class="clear"></div>

					<?php
					if ( ! empty( $test_app_token_response_biz ) && ! empty( $test_app_token_id_biz ) ) {
						$fb_name_biz = get_option( 'fts_facebook_custom_api_token_user_name_biz' );
						$fb_id_biz   = get_option( 'fts_facebook_custom_api_token_user_id_biz' );
						if ( isset( $test_app_token_response_biz->data->is_valid ) || $test_app_token_response_biz->error->message == '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {
							echo '<div class="fts-successful-api-token fts-special-working-wrap">';

							// https://graph.facebook.com/' . $fb_id_biz . '/picture
							if ( ! empty( $fb_id_biz ) && ! empty( $fb_name_biz ) && ! empty( $test_app_token_id_biz ) ) {
								echo '<img border="0" height="50" width="50" class="fts-fb-page-thumb" src="' . get_option( 'fts_facebook_custom_api_token_biz_profile_image' ) . '"/><h3><a href="https://facebook.com/' . $test_app_token_id_biz . '" target="_blank">' . $fb_name_biz . '</a></h3>';
							}
							echo __( 'Your Page Reviews Access Token is now working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page#feed_type=facebook_reviews">settings page</a>.', 'feed-them-social' ) . '</div>';

						}

						if ( isset( $test_app_token_response_biz->data->error->message ) && ! empty( $test_app_token_id_biz ) || isset( $test_app_token_response_biz->error->message ) && ! empty( $test_app_token_id ) && $test_app_token_response_biz->error->message !== '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {
							if ( isset( $test_app_token_response_biz->data->error->message ) ) {
								echo '<div class="fts-failed-api-token">' . __( 'Oh No something\'s wrong.', 'feed-them-social' ) . ' ' . $test_app_token_response_biz->data->error->message . ' ' . __( 'Please click the button above to retrieve a new Access Token.', 'feed-them-social' ) . '</div>';
							}
							if ( isset( $test_app_token_response_biz->error->message ) && ! empty( $test_app_token_id_biz ) && ! isset( $_GET['return_long_lived_token'] ) ) {
								echo '<div class="fts-failed-api-token">' . __( 'Oh No something\'s wrong.', 'feed-them-social' ) . ' ' . $test_app_token_response_biz->error->message . ' ' . __( 'Please click the button above to retrieve a new Access Token.', 'feed-them-social' ) . '</div>';
							}
						}
					}
					if ( ! isset( $_GET['reviews_token'] ) && empty( $test_app_token_id_biz ) ) {
						echo '<div class="fts-failed-api-token get-started-message">' . __( 'To get started, please click the button above to retrieve your Page Reviews Access Token.', 'feed-them-social' ) . '</div>';
					}

					if ( isset( $_GET['return_long_lived_token'] ) && isset( $_GET['reviews_token'] ) ) {
						// Echo our shortcode for the page token list with loadmore button
						// These functions are on feed-them-functions.php
						echo do_shortcode( '[fts_fb_page_token]' );

					}
					?>

                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="clear"></div>
                <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php esc_html_e( 'Save All Changes' ); ?>"/>

			<?php } ?>
            </form>
            <div class="clear"></div>
            <a class="feed-them-social-admin-slick-logo" href="https://www.slickremix.com" target="_blank"></a></div>
            <!--/feed-them-social-admin-wrap-->
			<?php
		}
	}
}//end class