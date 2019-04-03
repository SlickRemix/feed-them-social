<?php
/**
 * Feed Them Social - Instagram Options Page
 *
 * This page is used to create the general options for Instagram Feeds
 * including setting access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Instagram Options Page
 *
 * @package feedthemsocial
 */
class FTS_Instagram_Options_Page {

	/**
	 * Construct
	 *
	 * Instagram Style Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
	}


	/**
	 * Feed Them Instagram Options Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_instagram_options_page() {
        $fts_functions                     = new feed_them_social_functions();
		$fts_instagram_access_token          = get_option( 'fts_instagram_custom_api_token' );
		$fts_instagram_custom_id             = get_option( 'fts_instagram_custom_id' );
		$fts_instagram_show_follow_btn       = get_option( 'instagram_show_follow_btn' );
		$fts_instagram_show_follow_btn_where = get_option( 'instagram_show_follow_btn_where' );
		$access_token                        = isset( $_GET['access_token'] ) ? sanitize_text_field( $_GET['access_token'] ) : get_option( 'fts_instagram_custom_api_token' );

		if ( isset( $_GET['access_token'] ) ) { ?>
		<script>
			jQuery(document).ready(function ($) {

				$('#fts_instagram_custom_api_token').val('');
				$('#fts_instagram_custom_api_token').val($('#fts_instagram_custom_api_token').val() + '<?php echo esc_js( $access_token ); ?>');


				$('#fts_instagram_custom_id').val('');
				var str = '<?php echo esc_js( $access_token ); ?>';
				$('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + str.split('.', 1));
			});
		</script>
		<?php } ?>
		<div class="feed-them-social-admin-wrap">
			<h1>
				<?php esc_html_e( 'Instagram Feed Options', 'feed-them-social' ); ?>
			</h1>
			<div class="use-of-plugin">
				<?php esc_html_e( 'Get your Access Token and add a follow button and position it using the options below.', 'feed-them-social' ); ?>
			</div>
			<!-- custom option for padding -->
			<form method="post" class="fts-facebook-feed-options-form" action="options.php">
				<?php
				$fts_fb_options_nonce = wp_create_nonce( 'fts-instagram-options-page-nonce' );

				if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-instagram-options-page-nonce' ) ) {
					?>

				<div class="feed-them-social-admin-input-wrap" style="padding-top:0px; ">
					<div class="fts-title-description-settings-page">
					<?php
					// get our registered settings from the fts functions!
					settings_fields( 'fts-instagram-feed-style-options' );
					?>
						<h3>
						<?php esc_html_e( 'Instagram API Token', 'feed-them-social' ); ?>
						</h3>
						<?php


                        $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $fts_instagram_access_token );
						// Get Data for Instagram!
						$response = wp_remote_fopen( $insta_url );
						// Error Check!
						$test_app_token_response = json_decode( $response );
						?>
						<p>
						<?php
						echo esc_html( 'This is required to make the Instagram Feed work. Click the button below and it will connect to your Instagram Account to get an access token. It will then return to this page and save it in the inputs below. After it finishes you will be able to generate your Instagram feed.', 'feed-them-social' );
                        ?>
						</p>
						<p>
						<?php
                        // state=' . admin_url( 'admin.php?page-fts has a dash instead of equals otherwise instagram will chop our return url so on the return instagram page on our server we change it back to = so the token can be retrieved fts v2.6.7.
						echo sprintf(
							esc_html( '%1$sLogin and get my Access Token%2$s', 'feed-them-social' ),
							'<a href="' . esc_url( 'https://instagram.com/oauth/authorize/?client_id=da06fb6699f1497bb0d5d4234a50da75&hl=en&scope=public_content&redirect_uri=https://www.slickremix.com/instagram-token-plugin/?return_uri=' . admin_url( 'admin.php?page=fts-instagram-feed-styles-submenu-page' ) . '&response_type=token&state=' . admin_url( 'admin.php?page-fts-instagram-feed-styles-submenu-page' ) . '' ) . '" class="fts-instagram-get-access-token">',
							'</a>'
						);
						?>
						</p>
						<a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" class="fts-admin-button-no-work" style="margin-top: 14px; display: inline-block"><?php esc_html_e( 'Button not working?', 'feed-them-social' ); ?></a>
					</div>

					<div class="fts-clear"></div>

					<div class="feed-them-social-admin-input-wrap" style="margin-bottom:0">
						<div class="feed-them-social-admin-input-label fts-instagram-border-bottom-color-label">
							<?php esc_html_e( 'Instagram ID', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fts_instagram_custom_id" class="feed-them-social-admin-input" id="fts_instagram_custom_id" value="<?php echo esc_attr( $fts_instagram_custom_id ); ?>"/>
						<div class="fts-clear"></div>
					</div>

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-instagram-border-bottom-color-label">
							<?php esc_html_e( 'Access Token', 'feed-them-social' );


                            if ( isset( $_GET['access_token'] )  ) {
                                // START AJAX TO SAVE TOKEN TO DB
                                $fts_functions->feed_them_instagram_save_token();
                            }
                            ?>
						</div>

						<input type="text" name="fts_instagram_custom_api_token" class="feed-them-social-admin-input" id="fts_instagram_custom_api_token" value="<?php echo esc_attr( $access_token ); ?>"/>
						<div class="fts-clear"></div>
					</div>

                    <div class="feed-them-social-admin-input-wrap fts-instagram-last-row" style="margin-top: 0; padding-top: 0">
                        <?php
                        // Error Check
                        // if the combined streams plugin is active we won't allow the settings page link to open up the Instagram Feed, instead we'll remove the #feed_type=instagram and just let the user manually select the combined streams or single instagram feed.
                        if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
                            $custom_instagram_link_hash = '';
                        } else {
                            $custom_instagram_link_hash = '#feed_type=instagram';
                        }
                        if ( ! isset( $test_app_token_response->meta->error_message ) && ! isset( $test_app_token_response->error_message ) && ! empty( $fts_instagram_access_token ) || isset( $test_app_token_response->meta->error_message ) && 'This client has not been approved to access this resource.' === $test_app_token_response->meta->error_message ) {
                            echo sprintf(
                                esc_html( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s', 'feed-them-social' ),
                                '<div class="fts-successful-api-token">',
                                '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' . $custom_instagram_link_hash ) . '">',
                                '</a></div>'
                            );
                        } elseif ( isset( $test_app_token_response->meta->error_message ) && ! empty( $fts_instagram_access_token ) || isset( $test_app_token_response->error_message ) && ! empty( $fts_instagram_access_token ) ) {
                            $text = isset( $test_app_token_response->meta->error_message ) ? $test_app_token_response->meta->error_message : $test_app_token_response->error_message;
                            echo sprintf(
                                esc_html( '%1$sOh No something\'s wrong. %2$s. Please try clicking the button again to get a new access token. If you need additional assistance please email us at support@slickremix.com %3$s', 'feed-them-social' ),
                                '<div class="fts-failed-api-token">',
                                esc_html( $text ),
                                '</div>'
                            );
                        }
                        if ( empty( $fts_instagram_access_token ) && empty($_GET['access_token']) ) {
                            echo sprintf(
                                esc_html( '%1$sYou are required to get an access token to view your photos.%2$s', 'feed-them-social' ),
                                '<div class="fts-failed-api-token">',
                                '</div>'
                            );
                        }
                        ?>
                    </div>
					<div class="fts-clear"></div>
				</div>

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3>
						<?php esc_html_e( 'Follow Button Options', 'feed-them-social' ); ?>
						</h3>
					<?php esc_html_e( 'This will only show on regular feeds not combined feeds.', 'feed-them-social' ); ?>
					</div>
					<div class="feed-them-social-admin-input-label fts-instagram-text-color-label">
					<?php esc_html_e( 'Show Follow Button', 'feed-them-social' ); ?>
					</div>
					<select name="instagram_show_follow_btn" id="instagram-show-follow-btn" class="feed-them-social-admin-input">
						<option <?php echo selected( $fts_instagram_show_follow_btn, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
						<?php esc_html_e( 'No', 'feed-them-social' ); ?>
						</option>
						<option <?php echo selected( $fts_instagram_show_follow_btn, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
						<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
						</option>
					</select>
					<div class="fts-clear"></div>
				</div>
				<!--/fts-instagram-feed-styles-input-wrap-->

				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-instagram-text-color-label">
					<?php esc_html_e( 'Placement of the Buttons', 'feed-them-social' ); ?>
					</div>
					<select name="instagram_show_follow_btn_where" id="instagram-show-follow-btn-where" class="feed-them-social-admin-input">
						<option>
						<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
						</option>
						<option
						'<?php echo selected( $fts_instagram_show_follow_btn_where, 'instagram-follow-above', false ); ?>
						'
						value="<?php echo esc_attr( 'instagram-follow-above' ); ?>">
					<?php esc_html_e( 'Show Above Feed', 'feed-them-social' ); ?>
						</option>
						<option
						'<?php echo selected( $fts_instagram_show_follow_btn_where, 'instagram-follow-below', false ); ?>
						'
						value="<?php echo esc_attr( 'instagram-follow-below' ); ?>">
					<?php esc_html_e( 'Show Below Feed', 'feed-them-social' ); ?>
						</option>
					</select>
					<div class="fts-clear"></div>
				</div>
				<!--/fts-instagram-feed-styles-input-wrap-->
					<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php esc_html_e( 'Load More Button Styles & Options', 'feed-them-social' ); ?>
						</h3>
					</div>
					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
							<?php esc_html_e( 'Load More Button Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="instagram_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="instagram-loadmore-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'instagram_loadmore_background_color' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-instagram-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
							<?php esc_html_e( 'Load More Button Text Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="instagram_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="instagram-loadmore-text-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'instagram_loadmore_text_color' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-instagram-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label">
							<?php esc_html_e( '"Load More" Text', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="instagram_load_more_text" class="feed-them-social-admin-input" id="instagram_load_more_text" placeholder="Load More" value="<?php echo esc_attr( get_option( 'instagram_load_more_text' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-instagram-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label">
							<?php esc_html_e( '"No More Photos" Text', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="instagram_no_more_photos_text" class="feed-them-social-admin-input" id="instagram_no_more_photos_text" placeholder="No More Photos" value="<?php echo esc_attr( get_option( 'instagram_no_more_photos_text' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-instagram-feed-styles-input-wrap-->
					<?php } ?>
					<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php esc_html_e( 'Save All Changes' ); ?>"/>
					<?php } ?>
			</form>
		</div>
		<!--/feed-them-social-admin-wrap-->

		<?php
	}
}//end class
