<?php
/**
 * Feed Them Social - Pinterest Options Page
 *
 * This page is used to create the general options for Pinterest Feeds
 * including setting access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Pinterest Options Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Pinterest_Options_Page {
	/**
	 * FTS_Pinterest_Options_Page constructor.
	 */
	public function __construct() {
	}

	/**
	 * Feed Them Pinterest Options Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_pinterest_options_page() {
		$fts_pinterest_access_token          = get_option( 'fts_pinterest_custom_api_token' );
		$fts_pinterest_show_follow_btn       = get_option( 'pinterest_show_follow_btn' );
		$fts_pinterest_show_follow_btn_where = get_option( 'pinterest_show_follow_btn_where' );
        $access_token         = isset( $_GET['access_token'] ) ? sanitize_text_field( $_GET['access_token'] ) : get_option( 'fts_pinterest_custom_api_token' );
		?>
        <div class="fts-failed-api-token" style="margin-bottom: 20px;">
            <?php
            echo esc_html__( 'Without notice or warning the Pinterest API has become unavailable. Once the API is available to us again we will make an update to correct the issue. For the time being we are going to disable the access token button. 4-20-2020', 'feed-them-social' );
            ?>
        </div>

        <div class="feed-them-social-admin-wrap">

			<h1>
				<?php echo esc_html__( 'Pinterest Feed Options', 'feed-them-social' ); ?>
			</h1>
			<div class="use-of-plugin">
				<?php echo esc_html__( 'Add a follow button and position it using the options below.', 'feed-them-social' ); ?>
			</div>

			<!-- custom option for padding -->
			<form method="post" class="fts-pinterest-feed-options-form" action="options.php">

			<?php
			$fts_fb_options_nonce = wp_create_nonce( 'fts-pinterest-options-page-nonce' );

			if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-pinterest-options-page-nonce' ) ) {
				?>

				<?php settings_fields( 'fts-pinterest-feed-style-options' ); ?>

				<div class="feed-them-social-admin-input-wrap" style="padding-top:0; display: noneee">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html__( 'Pinterest Access Token', 'feed-them-social' ); ?>
						</h3>

						<p><?php echo esc_html__( 'This is required to make the feed work. Click the button below and it will connect to your Pinterest account to get an access token, and it will return it in the input below. Then click the save button and you will now be able to generate your Pinterest feed from the Settings page of our plugin.', 'feed-them-social' ); ?>
						</p>
						<p>
							<?php
							echo sprintf(
								esc_html__( '%1$sLogin and get my Access Token%2$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'https://api.pinterest.com/oauth/?response_type=token&redirect_uri=https://www.slickremix.com/pinterest-token-plugin/&client_id=5063534389122615467&scope=read_public&state=' . admin_url( 'admin.php?page=fts-pinterest-feed-styles-submenu-page' ) ) . '" class="fts-pinterest-get-access-token">',
								'</a>'
							);
							?>
						</p>
						<a href="<?php echo esc_url( 'mailto:support@slickremix.com' ); ?>" style="margin-top:14px;display:inline-block" class="fts-admin-button-no-work"><?php echo esc_html__( 'Button not working?', 'feed-them-social' ); ?></a>
					</div>

					<div class="fts-clear"></div>

					<div class="feed-them-social-admin-input-wrap" style="margin-bottom:0;">
						<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
							<?php echo esc_html__( 'Access Token Required', 'feed-them-social' ); ?>
						</div>

						<input type="text" name="fts_pinterest_custom_api_token" class="feed-them-social-admin-input" id="fts_pinterest_custom_api_token" value="<?php echo esc_attr( $access_token ); ?>"/>
						<div class="fts-clear"></div>
					</div>

					<?php
					// Get Data for Instagram!
					$response = wp_remote_fopen( 'https://api.pinterest.com/v1/me/?access_token=' . $fts_pinterest_access_token . '&id' );
					// Error Check!
					$test_app_token_response = json_decode( $response );

					// Error Check!
					if ( ! isset( $test_app_token_response->status ) && ! empty( $fts_pinterest_access_token ) ) {
						echo sprintf(
							esc_html__( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s', 'feed-them-social' ),
							'<div class="fts-successful-api-token">',
							'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
							'</a></div>'
						);
					} elseif ( isset( $test_app_token_response->status ) && ! empty( $fts_pinterest_access_token ) ) {
						echo sprintf(
							esc_html__( '%1$sOh No something\'s wrong. %2$s. Please try again, if you are still having troulbes please contact us on our Support Forum. Make sure to include screenshots of the browser page that may come up with any errors. %3$sSupport Forum%4$s', 'feed-them-social' ),
							'<div class="fts-failed-api-token">',
							esc_html( $test_app_token_response->message ),
							'<a href="' . esc_url( 'https://www.slickremix.com/support/' ) . '">',
							'</a></div>'
						);
					}
					if ( empty( $fts_pinterest_access_token ) ) {
						echo sprintf(
							esc_html__( '%1$sYou are required to get an access token to view your any of the Pinterest Feeds. Click "Save All Changes" after getting your Access Token.%2$s', 'feed-them-social' ),
							'<div class="fts-failed-api-token">',
							'</div>'
						);
					}
					?>

					<div class="fts-clear"></div>
				</div>
				<!--/fts-pinterest-feed-styles-input-wrap-->

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html__( 'Follow Button Options', 'feed-them-social' ); ?>
						</h3>
						<?php echo esc_html__( 'This will only show on regular feeds not combined feeds.', 'feed-them-social' ); ?>
					</div>
					<div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php echo esc_html__( 'Show Follow Button', 'feed-them-social' ); ?></div>

					<select name="pinterest_show_follow_btn" id="pinterest-show-follow-btn" class="feed-them-social-admin-input">
						<option <?php echo selected( $fts_pinterest_show_follow_btn, 'no', false ); ?> value="<?php echo esc_attr( 'no' ); ?>">
							<?php echo esc_html__( 'No', 'feed-them-social' ); ?>
						</option>
						<option <?php echo selected( $fts_pinterest_show_follow_btn, 'yes', false ); ?> value="<?php echo esc_attr( 'yes' ); ?>">
							<?php echo esc_html__( 'Yes', 'feed-them-social' ); ?>
						</option>
					</select>

					<div class="fts-clear"></div>
				</div><!--/fts-twitter-feed-styles-input-wrap-->

				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php echo esc_html__( 'Placement of the Buttons', 'feed-them-social' ); ?></div>

					<select name="pinterest_show_follow_btn_where" id="pinterest-show-follow-btn-where" class="feed-them-social-admin-input">
						<option>
							<?php echo esc_html__( 'Please Select Option', 'feed-them-social' ); ?>
						</option>
						<option <?php echo selected( $fts_pinterest_show_follow_btn_where, 'pinterest-follow-above', false ); ?> value="<?php echo esc_attr( 'pinterest-follow-above' ); ?>">
							<?php echo esc_html__( 'Show Above Feed', 'feed-them-social' ); ?>
						</option>
						<option <?php echo selected( $fts_pinterest_show_follow_btn_where, 'pinterest-follow-below', false ); ?> value="<?php echo esc_attr( 'pinterest-follow-below' ); ?>">
							<?php echo esc_html__( 'Show Below Feed', 'feed-them-social' ); ?>
						</option>
					</select>

					<div class="fts-clear"></div>
				</div><!--/fts-twitter-feed-styles-input-wrap-->

				<div class="feed-them-social-admin-input-wrap">
					<div class="fts-title-description-settings-page">
						<h3>
							<?php echo esc_html__( 'Boards List Style Options', 'feed-them-social' ); ?>
						</h3>
						<?php
						echo sprintf(
							esc_html__( 'These styles are for the list of Boards type feed %1$sseen here%2$s', 'feed-them-social' ),
							'<a href="' . esc_url( 'https://feedthemsocial.com/pinterest/' ) . '" target="_blank">',
							'</a>'
						);
						?>
					</div>
					<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
						<?php echo esc_html__( 'Board Title Color', 'feed-them-social' ); ?>
					</div>
					<input type="text" name="pinterest_board_title_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="pinterest_board_title_color" placeholder="#555555" value="<?php echo esc_attr( get_option( 'pinterest_board_title_color' ) ); ?>"/>
					<div class="fts-clear"></div>
				</div>
				<!--/fts-facebook-feed-styles-input-wrap-->
				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
						<?php echo esc_html__( 'Board Title Size', 'feed-them-social' ); ?>
					</div>
					<input type="text" name="pinterest_board_title_size" class="feed-them-social-admin-input" placeholder="16px" value="<?php echo esc_attr( get_option( 'pinterest_board_title_size' ) ); ?>"/>
					<div class="fts-clear"></div>
				</div>
				<!--/fts-facebook-feed-styles-input-wrap-->
				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-fb-link-color-label">
						<?php echo esc_html__( 'Background on Hover', 'feed-them-social' ); ?>
					</div>
					<input type="text" name="pinterest_board_backg_hover_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="pinterest_board_backg_hover_color" placeholder="#FFF" value="<?php echo esc_attr( get_option( 'pinterest_board_backg_hover_color' ) ); ?>"/>
					<div class="fts-clear"></div>
				</div>
				<!--/fts-facebook-feed-styles-input-wrap-->

				<div class="fts-clear"></div>
				<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php echo esc_html__( 'Save All Changes' ); ?>"/>
			<?php } ?>
			</form>
		</div>
		<!--/feed-them-social-admin-wrap-->

		<?php
	}
}//end class
