<?php
/**
 * Feed Them Social - Youtube Options Page
 *
 * This page is used to create the general options for Youtube Feeds
 * including setting access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Youtube Options Page
 *
 * @package feedthemsocial
 */
class FTS_Youtube_Options_Page {


	/**
	 * Construct
	 *
	 * Youtube Style Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {     }

	/**
	 * Feed Them Youtube Option Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_youtube_options_page() {
		$fts_functions                     = new feed_them_social_functions();
		$fts_youtube_show_follow_btn       = get_option( 'youtube_show_follow_btn' );
		$fts_youtube_show_follow_btn_where = get_option( 'youtube_show_follow_btn_where' );

		?>
		<div class="feed-them-social-admin-wrap">
			<h1>
				<?php echo esc_html( 'Feed Options', 'feed-them-social' ); ?>
			</h1>
			<div class="use-of-plugin">
				<?php echo esc_html( 'Add a follow button and position it using the options below. This option will not work for combined feeds.', 'feed-them-social' ); ?>
			</div>

			<!-- custom option for padding -->
			<form method="post" class="fts-youtube-feed-options-form" action="options.php">
				<?php
				$fts_fb_options_nonce = wp_create_nonce( 'fts-youtube-options-page-nonce' );

				if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-youtube-options-page-nonce' ) ) {

					settings_fields( 'fts-youtube-feed-style-options' );
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
					// Get Data for Youtube!
					$response = wp_remote_fopen( $youtube_user_id_data );
					// Error Check!
					$test_app_token_response = json_decode( $response );

					?>

				<div class="feed-them-social-admin-input-wrap" style="padding-top: 0">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html( 'YouTube API Key', 'feed-them-social' ); ?>
						</h3>
						<p><?php echo esc_html( 'This is required to make the feed work. Simply click the button below and it will connect to your YouTube account to get an access token and access token secret, and it will return it in the input below. Then just click the save button and you will now be able to generate your YouTube feed.', 'feed-them-social' ); ?>
						</p>
						<p>
							<?php
							echo sprintf(
								esc_html( '%1$sLogin and get my Access Token (API key)%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://www.slickremix.com/youtube-token/?redirect_url=' . admin_url( 'admin.php?page=fts-youtube-feed-styles-submenu-page' ) ) . '" class="fts-youtube-get-access-token">',
								'</a>'
							);
							?>
						</p>

					</div>

					<a href="https://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank" class="fts-admin-button-no-work">Button not working?</a>
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
						<div class="youtube-extra-keys-text" style="<?php echo esc_attr( $extra_keys_no ); ?>"><?php echo esc_html( 'Learn how to manually create your own YouTube API Key', 'feed-them-social' ); ?>
							<a href="<?php echo esc_url( 'https://www.slickremix.com/docs/get-api-key-for-youtube/' ); ?>" target="_blank"><?php echo esc_html( 'here', 'feed-them-social' ); ?></a>.
						</div>

						<div class="feed-them-social-admin-input-label fts-youtube-border-bottom-color-label">
							<?php echo esc_html( 'API Key Required', 'feed-them-social' ); ?>
						</div>

						<input type="text" name="youtube_custom_api_token" class="feed-them-social-admin-input" id="youtube_custom_api_token" value="<?php echo esc_attr( get_option( 'youtube_custom_api_token' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
				</div>

				<div class="hide-button-tokens-options" style="<?php echo esc_attr( $extra_keys_no ); ?>">
					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label">
							<?php echo esc_html( 'Refresh Token', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_custom_refresh_token" class="feed-them-social-admin-input" id="youtube_custom_refresh_token" value="<?php echo esc_attr( get_option( 'youtube_custom_refresh_token' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<div class="feed-them-social-admin-input-wrap" style="margin-bottom:0;">
						<div class="feed-them-social-admin-input-label">
							<?php echo esc_html( 'Access Token', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_custom_access_token" class="feed-them-social-admin-input" id="youtube_custom_access_token" value="<?php echo esc_attr( get_option( 'youtube_custom_access_token' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<?php
					// Add yes to show the expiration time and js that runs it below!
					$dev_mode = 'no';
					?>
					<div class="feed-them-social-admin-input-wrap fts-exp-time-wrapper" style="margin-top:10px;
					<?php
					if ( 'yes' !== $dev_mode ) {
						?>
							display:none<?php } ?>">
						<div class="feed-them-social-admin-input-label">
							<?php echo esc_html( 'Expiration Time for Access Token', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_custom_tokenecho esc_htmlxp_time" class="feed-them-social-admin-input" id="youtube_custom_tokenecho esc_htmlxp_time" value="<?php echo esc_attr( get_option( 'youtube_custom_tokenecho esc_htmlxp_time' ) ); ?>"/>
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
					if ( isset( $_GET['refresh_token'] ) && isset( $_GET['access_token'] ) && isset( $_GET['expires_in'] ) ) {
						// START AJAX TO SAVE TOKEN TO DB RIGHT AWAY SO WE CAN DO OUR NEXT SET OF CHECKS
						// new token action!
						$fts_functions->feed_them_youtube_refresh_token();
					}

					$expiration_time = get_option( 'youtube_custom_tokenecho esc_htmlxp_time' );
					// Give the access token a 5 minute buffer (300 seconds) before getting a new one.
					$expiration_time = $expiration_time - 300;
					// Test Liner!
					if ( time() < $expiration_time && empty( $youtube_api_key ) && 'yes' === $dev_mode ) {
						?>
						<script>
							// Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
							var countDownDate = new Date(<?php echo esc_js( $expiration_time ); ?> * 1000;
							)

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
									document.getElementById("fts-timer").innerHTML = "Expired, refresh page to get new token (developer use only)";
								}
							}, 1000);
						</script>
						<?php
					} elseif ( empty( $youtube_api_key ) && ! empty( $youtube_access_token ) && time() > $expiration_time ) {
						// refresh token action!
						$fts_functions->feed_them_youtube_refresh_token();
					}

					foreach ( $test_app_token_response as $user_id ) {
						if ( ! isset( $user_id->error->errors[0]->reason ) && ! empty( $youtube_api_key ) ) {
							$type_of_key = __( 'API key', 'feed-them-social' );
						} elseif ( ! isset( $user_id->error->errors[0]->reason ) && ! empty( $youtube_access_token ) ) {
							$type_of_key = __( 'Access Token', 'feed-them-social' );
						}

						// Error Check!
						if ( ! isset( $test_app_token_response->error->errors[0]->reason ) && ! empty( $youtube_api_key ) || ! isset( $test_app_token_response->error->errors[0]->reason ) && ! empty( $youtube_access_token ) && empty( $youtube_api_key ) ) {
							echo sprintf(
								esc_html( '%1$s Your %2$s is working! Generate your shortcode on the %3$s settings page.%4$s %5$s', 'feed-them-social' ),
								'<div class="fts-successful-api-token">',
								esc_html( $type_of_key ),
								'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
								'</a>',
								'</div>'
							);
						} elseif ( isset( $user_id->error->errors[0]->reason ) && ! empty( $youtube_api_key ) || ! isset( $user_id->error->errors[0]->reason ) && ! empty( $youtube_access_token ) ) {
							echo sprintf(
								esc_html( '%1$s This %2$s does not appear to be valid. YouTube responded with: %3$s %4$s ', 'feed-them-social' ),
								'<div class="fts-failed-api-token">',
								esc_html( $type_of_key ),
								esc_html( $user_id->errors[0]->reason ),
								'</div>'
							);
						}
						if ( empty( $youtube_api_key ) && empty( $youtube_access_token ) ) {
							echo sprintf(
								esc_html( '%1$s You must click the button above or register for an API token to use the YouTube feed.%2$s', 'feed-them-social' ),
								'<div class="fts-failed-api-token">',
								'</div>'
							);
						}
						break;
					}
					?>


					<div class="fts-clear"></div>
				</div>

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3><?php echo esc_html( 'Follow Button Options', 'feed-them-social' ); ?></h3>
					</div>
					<div class="feed-them-social-admin-input-label fts-youtube-text-color-label"><?php echo esc_html( 'Show Follow Button', 'feed-them-social' ); ?></div>

					<select name="youtube_show_follow_btn" id="youtube-show-follow-btn"
							class="feed-them-social-admin-input">
						<option
							<?php echo selected( $fts_youtube_show_follow_btn, 'yes', false ); ?>
								value="<?php echo esc_attr( 'yes' ); ?>">
							<?php echo esc_html( 'Yes', 'feed-them-social' ); ?>
						</option>
						<option <?php echo selected( $fts_youtube_show_follow_btn, 'no', false ); ?>
								value="<?php echo esc_attr( 'no' ); ?>">
							<?php echo esc_html( 'No', 'feed-them-social' ); ?>
						</option>
					</select>

					<div class="fts-clear"></div>
				</div><!--/fts-youtube-feed-styles-input-wrap-->

				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-youtube-text-color-label"><?php echo esc_html( 'Placement of the Buttons', 'feed-them-social' ); ?></div>

					<select name="youtube_show_follow_btn_where" id="youtube-show-follow-btn-where"
							class="feed-them-social-admin-input">
						<option><?php echo esc_html( 'Please Select Option', 'feed-them-social' ); ?></option>
						<option
							<?php echo selected( $fts_youtube_show_follow_btn_where, 'youtube-follow-above', false ); ?>
								value="<?php echo esc_attr( 'youtube-follow-above' ); ?>">
							<?php echo esc_html( 'Show Above Feed', 'feed-them-social' ); ?>
						</option>
						<option
							<?php echo selected( $fts_youtube_show_follow_btn_where, 'youtube-follow-below', false ); ?>
								value="<?php echo esc_attr( 'youtube-follow-below' ); ?>">
							<?php echo esc_html( 'Show Below Feed', 'feed-them-social' ); ?>
						</option>
					</select>

					<div class="fts-clear"></div>
				</div><!--/fts-youtube-feed-styles-input-wrap-->


					<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html( 'Load More Button Styles & Options', 'feed-them-social' ); ?>
						</h3>
					</div>
					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
							<?php echo esc_html( 'Button Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="youtube-loadmore-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'youtube_loadmore_background_color' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-youtube-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
							<?php echo esc_html( 'Text Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="youtube-loadmore-text-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'youtube_loadmore_text_color' ) ); ?>"/>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-youtube-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label">
							<?php echo esc_html( '"Load More" Text', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_load_more_text" class="feed-them-social-admin-input" id="youtube_load_more_text" placeholder="Load More" value="<?php echo esc_attr( get_option( 'youtube_load_more_text' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-youtube-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label">
							<?php echo esc_html( '"No More Videos" Text', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="youtube_no_more_videos_text" class="feed-them-social-admin-input" id="youtube_no_more_videos_text" placeholder="No More Videos" value="<?php echo esc_attr( get_option( 'youtube_no_more_videos_text' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-youtube-feed-styles-input-wrap-->

					<?php } // END premium ?>
					<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php echo esc_html( 'Save All Changes' ); ?>"/>
					<?php } ?>
			</form>
		</div>
		<!--/feed-them-social-admin-wrap-->
		<?php
	}
}//end class
