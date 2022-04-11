<?php
/**
 * Feed Them Social - Twitter Options Page
 *
 * This page is used to create the general options for Twitter Feeds
 * including setting access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Twitter Options Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Twitter_Options_Page {


	/**
	 * Construct
	 *
	 * Twitter Style Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
	}


	/**
	 * Set New Access Tokens
	 *
	 * Set the Tokens from Twitter on return.
	 *
	 * @since 2.7.1
	 */
	public function set_new_access_tokens() {
		// Set New Access Tokens!
		if ( isset( $_GET['oauth_token'], $_GET['oauth_token_secret'] ) && ! empty( $_GET['oauth_token'] ) && ! empty( $_GET['oauth_token_secret'] ) ) {
			$new_oath_token         = sanitize_text_field( wp_unslash( $_GET['oauth_token'] ) );
			$new_oauth_token_secret = sanitize_text_field( wp_unslash( $_GET['oauth_token_secret'] ) );
			// Set Returned Access Tokens.
			update_option( 'fts_twitter_custom_access_token', $new_oath_token );
			update_option( 'fts_twitter_custom_access_token_secret', $new_oauth_token_secret );
		}
	}

	/**
	 * Feed Them Twitter Options Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_twitter_options_page() {

	    // Check if new tokens have been returned.
		$this->set_new_access_tokens();

		?>
		<div class="feed-them-social-admin-wrap">
			<h1>
				<?php echo esc_html__( 'Twitter Feed Options', 'feed-them-social' ); ?>
			</h1>
			<div class="use-of-plugin">
				<?php echo esc_html__( 'Change the color of your twitter feed and more using the options below.', 'feed-them-social' ); ?>
			</div>
			<!-- custom option for padding -->
			<form method="post" class="fts-twitter-feed-options-form" action="options.php">
				<?php
				$fts_fb_options_nonce = wp_create_nonce( 'fts-twitter-options-page-nonce' );

				if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-twitter-options-page-nonce' ) ) {

					// get our registered settings from the fts functions!
					settings_fields( 'fts-twitter-feed-style-options' );

					$twitter_full_width                 = get_option( 'twitter_full_width' );
					$twitter_allow_videos               = get_option( 'twitter_allow_videos' );
					$twitter_allow_shortlink_conversion = get_option( 'twitter_allow_shortlink_conversion' );
					$twitter_show_follow_btn            = get_option( 'twitter_show_follow_btn' );
					$twitter_show_follow_count          = get_option( 'twitter_show_follow_count' );
					$twitter_show_follow_btn_where      = get_option( 'twitter_show_follow_btn_where' );
					$fts_twitter_hide_images_in_posts   = get_option( 'fts_twitter_hide_images_in_posts' );

					$fts_twitter_custom_consumer_key    = get_option( 'fts_twitter_custom_consumer_key' );
					$fts_twitter_custom_consumer_secret = get_option( 'fts_twitter_custom_consumer_secret' );

					$test_fts_twitter_custom_consumer_key    = '35mom6axGlf60ppHJYz1dsShc';
					$test_fts_twitter_custom_consumer_secret = '7c2TJvUT7lS2EkCULpK6RGHrgXN1BA4oUi396pQEdRj3OEq5QQ';

					$fts_twitter_custom_consumer_key    = isset( $fts_twitter_custom_consumer_key ) && '' !== $fts_twitter_custom_consumer_key ? $fts_twitter_custom_consumer_key : $test_fts_twitter_custom_consumer_key;
					$fts_twitter_custom_consumer_secret = isset( $fts_twitter_custom_consumer_secret ) && '' !== $fts_twitter_custom_consumer_secret ? $fts_twitter_custom_consumer_secret : $test_fts_twitter_custom_consumer_secret;

					$fts_twitter_custom_access_token        = get_option( 'fts_twitter_custom_access_token' );
					$fts_twitter_custom_access_token_secret = get_option( 'fts_twitter_custom_access_token_secret' );

					if ( isset( $_GET['page'] ) && 'fts-twitter-feed-styles-submenu-page' === $_GET['page'] ) {

						include WP_PLUGIN_DIR . '/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php';

						$test_connection = new TwitterOAuthFTS(
							// Consumer Key!
							$fts_twitter_custom_consumer_key,
							// Consumer Secret!
							$fts_twitter_custom_consumer_secret,
							// Access Token!
							$fts_twitter_custom_access_token,
							// Access Token Secret!
							$fts_twitter_custom_access_token_secret
						);

						$fetched_tweets = $test_connection->get(
							'statuses/user_timeline',
							array(
								'screen_name' => 'twitter',
								'count'       => '1',
							)
						);

						// TESTING AREA!
						// $fetched_tweets = $test_connection->get(
						// 'statuses/user_timeline',
						// array(
						// 'tweet_mode' => 'extended',
						// 'screen_name' => 'slickremix',
						// 'count' => '1',
						// )
						// );
						// echo '<pre>';
						// print_r($fetched_tweets);
						// echo '</pre>';
						// END TESTING!
					}
					?>
				<div class="feed-them-social-admin-input-wrap" style="padding-top: 0px">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html__( 'Twitter API Token', 'feed-them-social' ); ?>
						</h3>
						<p>
							<?php echo esc_html__( 'Click the button below to get an access token. This gives us read-only access to get your Twitter posts.', 'feed-them-social' ); ?>
						</p>
						<p>
							<?php
							echo sprintf(
								esc_html__( '%1$sLogin and get my Access Tokens%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://www.slickremix.com/get-twitter-token/?redirect_url=' . admin_url( 'admin.php?page=fts-twitter-feed-styles-submenu-page' ) . '&scope=manage_pages' ) . '" class="fts-twitter-get-access-token">',
								'</a>'
							);
							?>
						</p>
					</div>
					<a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" target="_blank" class="fts-admin-button-no-work"><?php echo esc_html__( 'Button not working?', 'feed-them-social' ); ?></a>
				</div>
				<div class="fts-clear"></div>
				<div class="feed-them-social-admin-input-wrap">
					<?php
					$fts_twitter_custom_consumer_key    = get_option( 'fts_twitter_custom_consumer_key' );
					$fts_twitter_custom_consumer_secret = get_option( 'fts_twitter_custom_consumer_secret' );
					$extra_keys                         = empty( $fts_twitter_custom_consumer_key ) && empty( $fts_twitter_custom_consumer_secret ) ? 'display:none' : '';
					?>

                    <!-- SRL 4-11-22: Hiding the create twitter tokens option because the process of getting approved is not as easy as it used to be and it's creating more support tickets too -->
					<div class="fts-twitter-add-all-keys-click-option" style="display: none">
						<label for="fts-custom-tokens-twitter">
							<input type="checkbox" id="fts-custom-tokens-twitter" name="fts_twitter_custom_tokens" value="1" <?php echo checked( '1', '' === $extra_keys ); ?>> <?php echo esc_html__( 'Add your own tokens?', 'feed-them-social' ); ?>
						</label>
					</div>

					<div class="twitter-extra-keys" style="<?php echo esc_attr( $extra_keys ); ?>" >
						<div class="twitter-extra-keys-text">
							<?php
							echo sprintf(
								esc_html__( 'Learn how to manually create the Consumer Key/Secret and the Access Token/Secret %1$shere%2$s.', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/' ) . '" target="_blank">',
								'</a>'
							);
							?>
						</div>
							<div class="feed-them-social-admin-input-wrap">
								<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
									<?php echo esc_html__( 'Consumer Key (API Key)', 'feed-them-social' ); ?>
								</div>
								<input type="text" name="fts_twitter_custom_consumer_key" class="feed-them-social-admin-input" id="fts_twitter_custom_consumer_key" value="<?php echo esc_attr( get_option( 'fts_twitter_custom_consumer_key' ) ); ?>"/>
								<div class="fts-clear"></div>
							</div>
							<div class="feed-them-social-admin-input-wrap">
								<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
									<?php echo esc_html__( 'Consumer Secret (API Secret)', 'feed-them-social' ); ?>
								</div>
								<input type="text" name="fts_twitter_custom_consumer_secret" class="feed-them-social-admin-input" id="fts_twitter_custom_consumer_secret" value="<?php echo esc_attr( get_option( 'fts_twitter_custom_consumer_secret' ) ); ?>"/>
								<div class="fts-clear"></div>
							</div>
					</div>

						<script>
							jQuery(document).ready(function ($) {
								jQuery('#fts-custom-tokens-twitter').click(function () {
									jQuery(".twitter-extra-keys").toggle();
								});
							});
						</script>
						<?php
						$oath_token         = isset( $_GET['oauth_token'] ) && ! empty( $_GET['oauth_token'] ) ? sanitize_text_field( wp_unslash( $_GET['oauth_token'] ) ) : get_option( 'fts_twitter_custom_access_token' );
						$oauth_token_secret = isset( $_GET['oauth_token_secret'] ) && ! empty( $_GET['oauth_token_secret'] ) ? sanitize_text_field( wp_unslash( $_GET['oauth_token_secret'] ) ) : get_option( 'fts_twitter_custom_access_token_secret' );
						?>
						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
								<?php echo esc_html__( 'Access Token Required', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fts_twitter_custom_access_token" class="feed-them-social-admin-input" id="fts_twitter_custom_access_token" value="<?php echo esc_attr( $oath_token ); ?>"/>
							<div class="fts-clear"></div>
						</div>

                        <!-- We don't  need to display this field to the user, so we only show the Acccess Token -->
                        <div class="feed-them-social-admin-input-wrap" style="display: none">
							<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
								<?php echo esc_html__( 'Access Token Secret', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fts_twitter_custom_access_token_secret" class="feed-them-social-admin-input" id="fts_twitter_custom_access_token_secret" value="<?php echo esc_attr( $oauth_token_secret ); ?>"/>
							<div class="fts-clear"></div>
						</div>

						<div class="feed-them-social-admin-input-wrap">
							<?php
							// && !empty($test_fts_twitter_custom_access_token) && !empty($test_fts_twitter_custom_access_token_secret)!
							if ( ! empty( $fts_twitter_custom_access_token_secret ) && ! empty( $fts_twitter_custom_access_token_secret ) ) {
								if ( 200 !== $test_connection->http_code || isset( $fetched_tweets->errors ) ) {
									echo sprintf(
										esc_html__( '%1$sOh No, something\'s wrong. ', 'feed-them-social' ),
										'<div class="fts-failed-api-token">'
									);
									foreach ( $fetched_tweets->errors as $error ) {
										echo sprintf(
											esc_html__( '%1$s%2$s%3$s You may have entered in the Access information incorrectly please re-enter and try again.%4$s', 'feed-them-social' ),
											'<strong>',
											esc_html( $error->message ),
											'</strong>',
											'</div>'
										);
									}
								} else {
									echo sprintf(
										esc_html__( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s.%4$s', 'feed-them-social' ),
										'<div class="fts-successful-api-token">',
										'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
										'</a>',
										'</div>'
									);
								}
							} else {
								echo sprintf(
									esc_html__( '%1$sTo get started, please click the button above to retrieve your Access Token.%2$s', 'feed-them-social' ),
									'<div class="fts-failed-api-token get-started-message">',
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
								<?php echo esc_html__( 'Follow Button Options', 'feed-them-social' ); ?>
							</h3>
							<?php echo esc_html__( 'This will only show on regular feeds not combined feeds.', 'feed-them-social' ); ?>
						</div>
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php echo esc_html__( 'Show Follow Count', 'feed-them-social' ); ?>
						</div>
						<select name="twitter_show_follow_count" id="twitter-show-follow-count" class="feed-them-social-admin-input">
							<option <?php echo selected( $twitter_show_follow_count, 'no', false ); ?> value=" <?php echo esc_attr( 'no' ); ?>">
								<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_show_follow_count, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
								<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php echo esc_html__( 'Show Follow Button', 'feed-them-social' ); ?>
						</div>
						<select name="twitter_show_follow_btn" id="twitter-show-follow-btn" class="feed-them-social-admin-input">
							<option <?php echo selected( $twitter_show_follow_btn, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
								<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_show_follow_btn, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
								<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php echo esc_html__( 'Placement of Follow Button', 'feed-them-social' ); ?>
						</div>
						<select name="twitter_show_follow_btn_where" id="twitter-show-follow-btn-where" class="feed-them-social-admin-input">
							<option>
								<?php echo esc_html__( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_show_follow_btn_where, 'twitter-follow-above', false ); ?> value="<?php echo esc_attr( 'twitter-follow-above' ); ?>">
								<?php echo esc_html__( 'Show Above Feed', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_show_follow_btn_where, 'twitter-follow-below', false ); ?> value="<?php echo esc_attr( 'twitter-follow-below' ); ?>">
								<?php echo esc_html__( 'Show Below Feed', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php echo esc_html__( 'Video Player Options', 'feed-them-social' ); ?>
							</h3>
						</div>
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php echo esc_html__( 'Show videos', 'feed-them-social' ); ?>
						</div>
						<select name="twitter_allow_videos" id="twitter-allow-videos" class="feed-them-social-admin-input">
							<option <?php echo selected( $twitter_allow_videos, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
								<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_allow_videos, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
								<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap" style="display: none">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php
							echo sprintf(
								esc_html__( 'Convert shortlinks for video%1$sLike bitly etc. May slow load time slightly%2$s.', 'feed-them-social' ),
								'<br/><small>',
								'</small>'
							);
							?>
						</div>
						<select name="twitter_allow_shortlink_conversion" id="twitter-allow-shortlink-conversion" class="feed-them-social-admin-input">
							<option
							<?php echo selected( $twitter_allow_shortlink_conversion, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
								<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_allow_shortlink_conversion, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
								<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php echo esc_html__( 'Profile Photo Option', 'feed-them-social' ); ?>
							</h3>
						</div>
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php echo esc_html__( 'Hide Profile Photo', 'feed-them-social' ); ?>
						</div>
						<select name="twitter_full_width" id="twitter-full-width" class="feed-them-social-admin-input">
							<option
							<?php echo selected( $twitter_full_width, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
								<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $twitter_full_width, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
								<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="fts-clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php echo esc_html__( 'Style Options', 'feed-them-social' ); ?>
							</h3>
						</div>

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
								<?php echo esc_html__( 'Hide Images in Posts', 'feed-them-social' ); ?>
							</div>
							<select name="fts_twitter_hide_images_in_posts" id="fts_twitter_hide_images_in_posts" class="feed-them-social-admin-input">
								<option value="">
									<?php echo esc_html__( 'Please Select Option', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fts_twitter_hide_images_in_posts, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
									<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fts_twitter_hide_images_in_posts, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
									<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
								</option>
							</select>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php echo esc_html__( 'Max-width for Feed Images', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_max_image_width" class="feed-them-social-admin-input" placeholder="500px" value="<?php echo esc_attr( get_option( 'twitter_max_image_width' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-text-size-label">
								<?php echo esc_html__( 'Feed Description Text Size', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_text_size" class="feed-them-social-admin-input twitter-text-size-input" id="twitter-text-size-input" placeholder="12px" value="<?php echo esc_attr( get_option( 'twitter_text_size' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
								<?php echo esc_html__( 'Feed Text Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_text_color" class="feed-them-social-admin-input twitter-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-text-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'twitter_text_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-link-color-label">
								<?php echo esc_html__( 'Feed Link Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_link_color" class="feed-them-social-admin-input twitter-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-link-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'twitter_link_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-link-color-hover-label">
								<?php echo esc_html__( 'Feed Link Color Hover', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_link_color_hover" class="feed-them-social-admin-input twitter-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-link-color-hover-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_link_color_hover' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-feed-width-label">
								<?php echo esc_html__( 'Feed Width', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_feed_width" class="feed-them-social-admin-input twitter-feed-width-input" id="twitter-feed-width-input" placeholder="500px" value="<?php echo esc_attr( get_option( 'twitter_feed_width' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-feed-margin-label">
								<?php
								echo sprintf(
									esc_html__( 'Feed Margin %1$sTo center feed type auto%2$s', 'feed-them-social' ),
									'<br/><small>',
									'</small>'
								);
								?>
							</div>
							<input type="text" name="twitter_feed_margin" class="feed-them-social-admin-input twitter-feed-margin-input" id="twitter-feed-margin-input" placeholder="10px" value="<?php echo esc_attr( get_option( 'twitter_feed_margin' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-feed-padding-label">
								<?php echo esc_html__( 'Feed Padding', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_feed_padding" class="feed-them-social-admin-input twitter-feed-padding-input" id="twitter-feed-padding-input" placeholder="10px" value="<?php echo esc_attr( get_option( 'twitter_feed_padding' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-feed-background-color-label">
								<?php echo esc_html( 'Feed Background Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_feed_background_color" class="feed-them-social-admin-input twitter-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-feed-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_feed_background_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
								<?php echo esc_html( 'Feed Border Bottom Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_border_bottom_color" class="feed-them-social-admin-input twitter-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_border_bottom_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->
						<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>

						<div class="feed-them-social-admin-input-wrap">
							<div class="fts-title-description-settings-page">
								<h3>
									<?php echo esc_html( 'Grid Styles', 'feed-them-social' ); ?>
								</h3>
							</div>
							<div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label">
								<?php echo esc_html( 'Posts Background Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_grid_posts_background_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
								<?php echo esc_html( 'Border Bottom Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="twitter_grid_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_grid_border_bottom_color' ) ); ?>"/>
							<div class="fts-clear"></div>
						</div>
						<!--/fts-twitter-feed-styles-input-wrap-->

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
								<input type="text" name="twitter_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_loadmore_background_color' ) ); ?>"/>
								<div class="fts-clear"></div>
							</div>
							<!--/fts-twitter-feed-styles-input-wrap-->

							<div class="feed-them-social-admin-input-wrap">
								<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
									<?php echo esc_html( 'Text Color', 'feed-them-social' ); ?>
								</div>
								<input type="text" name="twitter_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-text-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'twitter_loadmore_text_color' ) ); ?>"/>
								<div class="fts-clear"></div>
							</div>
							<!--/fts-twitter-feed-styles-input-wrap-->

							<div class="feed-them-social-admin-input-wrap">
								<div class="feed-them-social-admin-input-label">
									<?php echo esc_html( '"Load More" Text', 'feed-them-social' ); ?>
								</div>
								<input type="text" name="twitter_load_more_text" class="feed-them-social-admin-input" id="twitter_load_more_text" placeholder="Load More" value="<?php echo esc_attr( get_option( 'twitter_load_more_text' ) ); ?>"/>
								<div class="clear"></div>
							</div>
							<!--/fts-twitter-feed-styles-input-wrap-->

							<div class="feed-them-social-admin-input-wrap">
								<div class="feed-them-social-admin-input-label">
									<?php echo esc_html( '"No More Tweets" Text', 'feed-them-social' ); ?>
								</div>
								<input type="text" name="twitter_no_more_tweets_text" class="feed-them-social-admin-input" id="twitter_no_more_tweets_text" placeholder="No More Photos" value="<?php echo esc_attr( get_option( 'twitter_no_more_tweets_text' ) ); ?>"/>
								<div class="clear"></div>
							</div>
							<!--/fts-twitter-feed-styles-input-wrap-->

							<div class="feed-them-social-admin-input-wrap" style="display: none;">
								<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
									<?php
									echo sprintf(
										esc_html( 'Fix Post Count %1$sType 2 or 3 if your feed is skipping posts when using the loadmore option.%2$s', 'feed-them-social' ),
										'<br/><small>',
										'</small>'
									);
									?>
								</div>
								<input type="text" name="twitter_replies_offset" class="feed-them-social-admin-input" id="twitter-replies-offset" placeholder="1" value="<?php echo esc_attr( get_option( 'twitter_replies_offset' ) ); ?>"/>
								<div class="fts-clear"></div>
							</div>
							<!--/fts-twitter-feed-styles-input-wrap-->
							<?php } ?>
							<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php echo esc_html( 'Save All Changes' ); ?>"/>
				<?php } ?>
			</form>
		</div>
		<!--/feed-them-social-admin-wrap-->
		<?php
	}
}//end class
