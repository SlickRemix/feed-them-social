<?php
 /**
 * Feed Them Social - Twitter Access Options
 *
 * This page is used to retrieve and set access tokens for Twitter.
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
 * Class Twitter_Access_Options
 *
 * @package feedthemsocial
 * @since 3.0.0
 */
class Twitter_Access_Options {

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
	 * Twitter Style Options Page constructor.
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
	 * Access Token Options
	 *
	 * @since 3.0.0
	 */
	public function access_options() {

        $post_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : '';
        $post_url = add_query_arg( array(
            'post' => $post_id,
        ), admin_url( 'post.php' ) );

        // Check if new tokens have been returned.
		// $this->set_new_access_tokens();
		?>
		<div id="twitter-token-master-wrap" class="feed-them-social-admin-wrap">
				<?php

					$fts_twitter_custom_consumer_key    = '';
					$fts_twitter_custom_consumer_secret = '';

					$test_fts_twitter_custom_consumer_key    = '35mom6axGlf60ppHJYz1dsShc';
					$test_fts_twitter_custom_consumer_secret = '7c2TJvUT7lS2EkCULpK6RGHrgXN1BA4oUi396pQEdRj3OEq5QQ';

					$fts_twitter_custom_consumer_key    = isset( $fts_twitter_custom_consumer_key ) && '' !== $fts_twitter_custom_consumer_key ? $fts_twitter_custom_consumer_key : $test_fts_twitter_custom_consumer_key;
					$fts_twitter_custom_consumer_secret = isset( $fts_twitter_custom_consumer_secret ) && '' !== $fts_twitter_custom_consumer_secret ? $fts_twitter_custom_consumer_secret : $test_fts_twitter_custom_consumer_secret;


                    // http://fts30.local/wp-admin/post.php?post=178&action=edit&feed_type=twitter&oauth_token=&oauth_token_secret=#feed_setup

					// $fts_twitter_custom_access_token        = get_option( 'fts_twitter_custom_access_token' );
                    $fts_twitter_custom_access_token = '1561334624-tZ2B3whzdqIs9hVBkvnKMZNNxOnpKCEGeGJu9ls';
					//$fts_twitter_custom_access_token_secret = get_option( 'fts_twitter_custom_access_token_secret' );
                    $fts_twitter_custom_access_token_secret = 'Gwfx5oCJLgcUa4WBBBDch8oZAXTuP0qtSuCZ6QxPqoNtH';


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
						 /*echo '<pre>';
						 print_r($fetched_tweets);
						 echo '</pre>';*/
						// END TESTING!

					?>
				<div class="feed-them-social-admin-input-wrap" style="padding-top: 0px">
					<div class="fts-title-description-settings-page">
						<p>
							<?php
							echo sprintf(
								esc_html__( '%1$sLogin and get my Access Tokens%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://www.slickremix.com/get-twitter-token/?redirect_url=' . $post_url . '&scope=manage_pages' ) . '" class="fts-twitter-get-access-token">',
								'</a>'
							);
							?>
						</p>
					</div>
					<a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" target="_blank" class="fts-admin-button-no-work"><?php echo esc_html__( 'Not working?', 'feed-them-social' ); ?></a>
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
										esc_html__( '%1$sYour access token is working, time to customize your %2$soptions%3$s.%4$s', 'feed-them-social' ),
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
			<?php
	}

}//end class