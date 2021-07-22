<?php
 /**
 * Feed Them Social - Twitter API Token
 *
 * This page is used to retrieve and set access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class Twitter_API_Token
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Twitter_Access_Token {


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
	 * Twitter API Token Options
	 *
	 * @since 1.9.6
	 */
	public function twitter_access_token_options() {

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
							<?php echo esc_html__( 'This is required to make the feed work. Simply click the button below and it will connect to your Twitter account to get an access token and access token secret, and it will return it in the input below. Then just click the save button and you will now be able to generate your Twitter feed.', 'feed-them-social' ); ?>
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

					<div class="fts-twitter-add-all-keys-click-option">
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
							<?php echo esc_html__( 'Access Token', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fts_twitter_custom_access_token" class="feed-them-social-admin-input" id="fts_twitter_custom_access_token" value="<?php echo esc_attr( $oath_token ); ?>"/>
						<div class="fts-clear"></div>
					</div>

					<div class="feed-them-social-admin-input-wrap">
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
							// Clear Cache!
							do_action( 'wp_ajax_fts_clear_cache_ajax' );
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

                <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php echo esc_html( 'Save All Changes' ); ?>"/>
                <?php } ?>
			</form>
		</div>
		<!--/feed-them-social-admin-wrap-->
		<?php
	}
}//end class