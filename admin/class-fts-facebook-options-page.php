<?php
/**
 * Feed Them Social - Facebook Options Page
 *
 * This page is used to create the general options for Facebook Feeds
 * including setting access tokens.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Facebook Options Page
 *
 * @package feedthemsocial
 */
class FTS_Facebook_Options_Page {

    public $data_protection;

	/** * Construct
	 *
	 * Facebook Style Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct(  ) {
        // Data Protection
        $this->data_protection = new Data_Protection();
	}

	/**
	 * Feed Them Facebook Options Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_facebook_options_page() {
		$fts_functions = new feed_them_social_functions();
		?>

		<div class="feed-them-social-admin-wrap">
			<h1>
				<?php esc_html_e( 'Facebook Feed Options', 'feed-them-social' ); ?>
			</h1>
			<div class="use-of-plugin">
				<?php esc_html_e( 'Change the language, color and more for your facebook feed using the options below.', 'feed-them-social' ); ?>
                <?php
                echo sprintf(
                    esc_html__( 'Please note, use of this plugin is subject to %1$sFacebook\'s Platform Terms%2$s', 'feed-them-social' ),
                    '<a href="' . esc_url( 'https://developers.facebook.com/terms/' ) . '" target="_blank">',
                    '</a>'
                );
                ?>
			</div>
			<!-- custom option for padding -->
			<form method="post" class="fts-facebook-feed-options-form" action="options.php" id="fts-facebook-feed-options-form">
				<br/>
				<?php

				$fts_fb_options_nonce = wp_create_nonce( 'fts-facebook-options-page-nonce' );

				if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-facebook-options-page-nonce' ) ) {

					// get our registered settings from the fts functions!
					settings_fields( 'fts-facebook-feed-style-options' );
					// Language select!
					$fb_language = get_option( 'fb_language', 'en_US' );
					// share button!
					$fb_show_follow_btn                 = get_option( 'fb_show_follow_btn' );
					$fb_show_follow_btn_where           = get_option( 'fb_show_follow_btn_where' );
					$fb_show_follow_btn_profile_pic     = get_option( 'fb_show_follow_btn_profile_pic' );
					$fb_like_btn_color                  = get_option( 'fb_like_btn_color', 'light' );
					$fb_hide_shared_by_etc_text         = get_option( 'fb_hide_shared_by_etc_text' );
					$fb_title_htag                      = get_option( 'fb_title_htag' );
					$fb_hide_images_in_posts            = get_option( 'fb_hide_images_in_posts' );
					$fb_hide_error_handler_message      = get_option( 'fb_hide_error_handler_message' );
					$fb_hide_no_posts_message           = get_option( 'fb_hide_no_posts_message' );
					$fb_reviews_remove_see_reviews_link = get_option( 'fb_reviews_remove_see_reviews_link' );
					$fb_loadmore_background_color       = get_option( 'fb_loadmore_background_color' );
					$fb_loadmore_text_color             = get_option( 'fb_loadmore_text_color' );

					$fb_reviews_overall_rating_background_border_hide = get_option( 'fb_reviews_overall_rating_background_border_hide' );

					$lang_options_array = json_decode( $fts_functions->xml_json_parse( 'https://raw.githubusercontent.com/pennersr/django-allauth/master/allauth/socialaccount/providers/facebook/data/FacebookLocales.xml' ) );
					// echo '<pre>';
					// print_r($lang_options_array);
					// echo '</pre>';.
					?>
					<div id="fb-token-master-wrap" class="feed-them-social-admin-input-wrap" style="padding-bottom:0px;">
						<div class="fts-title-description-settings-page" style="padding-top:0; border:none; margin-bottom:0px;">
							<h3>
								<?php esc_html_e( 'Facebook API Token', 'feed-them-social' ); ?>
							</h3>
							<?php esc_html_e( 'This Facebook Access Token is for Business Pages, Photos and Videos only and is simply used to display the feed. You must be an admin of the business page to get your token. This will NOT work for personal profiles or groups. ', 'feed-them-social' ); ?>

                            <?php echo sprintf(
                                esc_html__( '%1$sClick the button below to get an access token. This gives us read-only access to get your Facebook posts.%2$s', 'feed-them-social' ),
                                '<p>',
                                '</p>'
                            );
                            ?>

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

                        //Facebook Business.
                        $fb_custom_api_token   = get_option( 'fts_facebook_custom_api_token' );
                        $fb_custom_encrypted   = $this->data_protection->decrypt( $fb_custom_api_token );
                        $check_custom_token_value = false !== $fb_custom_encrypted ? $fb_custom_encrypted : $fb_custom_api_token;
                        $check_custom_encrypted   = false !== $fb_custom_encrypted ? 'encrypted' : '';

                        //Facebook Business Reviews.
                        $fb_custom_api_token_biz   = get_option( 'fts_facebook_custom_api_token_biz' );
                        $fb_custom_biz_encrypted   = $this->data_protection->decrypt( $fb_custom_api_token_biz );
                        $check_custom_token_biz_value = false !== $fb_custom_biz_encrypted ? $fb_custom_biz_encrypted : $fb_custom_api_token_biz;
                        $check_custom_biz_encrypted   = false !== $fb_custom_biz_encrypted ? 'encrypted' : '';

                        if ( ! empty( $fb_custom_api_token ) || ! empty( $fb_custom_api_token_biz ) ) {

                            $fb_custom_api_token     = $check_custom_token_value;
                            $fb_custom_api_token_biz = $check_custom_token_biz_value;

							$test_app_token_url     = array(
								'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $fb_custom_api_token . '&access_token=' . $fb_custom_api_token,
							);
							$test_app_token_url_biz = array(
								'app_token_id_biz' => 'https://graph.facebook.com/debug_token?input_token=' . $fb_custom_api_token_biz . '&access_token=' . $fb_custom_api_token_biz . '&',
								/*'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=7054444020102908771&client_secret=7016612gg8c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjL3G2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'*/
							);
							$test_app_token_response     = $fts_functions->fts_get_feed_json( $test_app_token_url );
							$test_app_token_response     = json_decode( $test_app_token_response['app_token_id'] );
							$test_app_token_response_biz = $fts_functions->fts_get_feed_json( $test_app_token_url_biz );
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

                            <input type="text" name="fts_facebook_custom_api_token" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token" data-token="<?php echo $check_custom_encrypted ?>" value="<?php echo $check_custom_token_value ?>" />

							<div class="clear"></div>

							<input type="text" hidden name="fts_facebook_custom_api_token_user_name" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_name' ) ); ?>"/>
							<input type="text" hidden name="fts_facebook_custom_api_token_profile_image" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_profile_image" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_profile_image' ) ); ?>"/>

							<div class="clear"></div>
							<?php

                            if ( ! empty( $test_app_token_response ) && ! empty( $fb_custom_api_token ) ) {
								if ( isset( $test_app_token_response->data->is_valid ) || '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' === $test_app_token_response->error->message ) {
									$fb_id   = get_option( 'fts_facebook_custom_api_token_user_id' );
									$fb_name = get_option( 'fts_facebook_custom_api_token_user_name' );
									echo '<div class="fts-successful-api-token fts-special-working-wrap">';

									if ( ! empty( $fb_id ) && ! empty( $fb_name ) && ! empty( $fb_custom_api_token ) ) {
										echo '<h3><a href="' . esc_url( 'https://www.facebook.com/' . get_option( 'fts_facebook_custom_api_token_user_id' ) ) . '" target="_blank">' . esc_html( $fb_name ) . '</a></h3>';
									}

									echo sprintf(
										esc_html__( 'Your Access Token is now working! Generate your shortcode on the %1$sSettings Page%2$s', 'feed-them-social' ),
										'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page#feed_type=facebook' ) . '">',
										'</a>'
									);

									echo '</div>';
								}
								if ( isset( $test_app_token_response->data->error->message ) && ! empty( $fb_custom_api_token ) || isset( $test_app_token_response->error->message ) && ! empty( $fb_custom_api_token ) && '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' !== $test_app_token_response->error->message ) {
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

									if ( isset( $test_app_token_response->data->error->message ) && empty( $fb_custom_api_token ) || isset( $test_app_token_response->error->message ) && empty( $fb_custom_api_token ) ) {
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

                                <?php echo sprintf(
                                    esc_html__( '%1$sClick the button below to get an access token. This gives us read-only access to get your Facebook reviews.%2$s', 'feed-them-social' ),
                                    '<p>',
                                    '</p>'
                                );
                                ?>
                                <p>
									<?php
                                    // https://developers.facebook.com/docs/graph-api/reference/page/ratings/
									echo sprintf(
										esc_html__( '%1$sLogin and get my Reviews Access Token%2$s', 'feed-them-social' ),
										'<a href="' . esc_url( 'https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/facebook-token/&state=' . admin_url( 'admin.php?page=fts-facebook-feed-styles-submenu-page' ) . '%26reviews_token=yes&scope=pages_show_list,pages_read_engagement,pages_read_user_content' ) . '" class="fts-facebook-get-access-token">',
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
							<input type="text" name="fts_facebook_custom_api_token_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_biz" data-token="<?php echo $check_custom_biz_encrypted ?>" value="<?php echo $check_custom_token_biz_value ?>" />
							<input type="text" hidden name="fts_facebook_custom_api_token_user_name_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name_biz" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_user_name_biz' ) ); ?>"/>
							<input type="text" hidden name="fts_facebook_custom_api_token_biz_profile_image" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_biz_profile_image" value="<?php echo esc_attr( get_option( 'fts_facebook_custom_api_token_biz_profile_image' ) ); ?>"/>
							<div class="clear"></div>

							<?php
							if ( ! empty( $test_app_token_response_biz ) && ! empty( $fb_custom_api_token_biz ) ) {
								$fb_name_biz = get_option( 'fts_facebook_custom_api_token_user_name_biz' );
								$fb_id_biz   = get_option( 'fts_facebook_custom_api_token_user_id_biz' );
								if ( isset( $test_app_token_response_biz->data->is_valid ) || $test_app_token_response_biz->error->message == '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {
									echo '<div class="fts-successful-api-token fts-special-working-wrap">';

									// https://graph.facebook.com/' . $fb_id_biz . '/picture
									if ( ! empty( $fb_id_biz ) && ! empty( $fb_name_biz ) && ! empty( $fb_custom_api_token_biz ) ) {
										echo '<h3><a href="https://facebook.com/' . $fb_custom_api_token_biz . '" target="_blank">' . $fb_name_biz . '</a></h3>';
									}
									echo __( 'Your Page Reviews Access Token is now working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page#feed_type=facebook_reviews">settings page</a>.', 'feed-them-social' ) . '</div>';

								}

								if ( isset( $test_app_token_response_biz->data->error->message ) && ! empty( $fb_custom_api_token_biz ) || isset( $test_app_token_response_biz->error->message ) && ! empty( $fb_custom_api_token ) && $test_app_token_response_biz->error->message !== '(#100) You must provide an app access token, or a user access token that is an owner or developer of the app' ) {
									if ( isset( $test_app_token_response_biz->data->error->message ) ) {
										echo '<div class="fts-failed-api-token">' . __( 'Oh No something\'s wrong.', 'feed-them-social' ) . ' ' . $test_app_token_response_biz->data->error->message . ' ' . __( 'Please click the button above to retrieve a new Access Token.', 'feed-them-social' ) . '</div>';
									}
									if ( isset( $test_app_token_response_biz->error->message ) && ! empty( $fb_custom_api_token_biz ) && ! isset( $_GET['return_long_lived_token'] ) ) {
										echo '<div class="fts-failed-api-token">' . __( 'Oh No something\'s wrong.', 'feed-them-social' ) . ' ' . $test_app_token_response_biz->error->message . ' ' . __( 'Please click the button above to retrieve a new Access Token.', 'feed-them-social' ) . '</div>';
									}
								}
							}
							if ( ! isset( $_GET['reviews_token'] ) && empty( $fb_custom_api_token_biz ) ) {
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

						<div class="feed-them-social-admin-input-wrap">
							<div class="fts-title-description-settings-page">
								<h3>
									<?php esc_html_e( 'Reviews: Style and Text Options', 'feed-them-social' ); ?>
								</h3>
								<?php esc_html_e( 'The styles above still apply, these are just some extra options for the Reviews List feed.', 'feed-them-social' ); ?>
							</div>
							<div class="feed-them-social-admin-input-label fb-events-title-color-label">
								<?php
								echo sprintf(
									esc_html__( 'Stars Background Color%1$sApplies to Overall Rating too.%2$s', 'feed-them-social' ),
									'<br/><small>',
									'</small>'
								);
								?>
							</div>
							<input type="text" name="fb_reviews_backg_color" class="feed-them-social-admin-input fb-reviews-backg-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-reviews-backg-color" placeholder="#4791ff" value="<?php echo esc_attr( get_option( 'fb_reviews_backg_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
								<?php
								echo sprintf(
									esc_html__( 'Stars & Text Color%1$sApplies to Overall Rating too.%2$s', 'feed-them-social' ),
									'<br/><small>',
									'</small>'
								);
								?>
							</div>
							<input type="text" name="fb_reviews_text_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-reviews-text-color" placeholder="#fff" value="<?php echo esc_attr( get_option( 'fb_reviews_text_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
								<?php esc_html_e( 'Text for the word "star"', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_star_language" class="feed-them-social-admin-input" id="fb_reviews_star_language" placeholder="star" value="<?php echo esc_attr( get_option( 'fb_reviews_star_language' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
								<?php esc_html_e( 'Text for the word "Recommended"', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_recommended_language" class="feed-them-social-admin-input" id="fb_reviews_recommended_language" placeholder="Recommeded" value="<?php echo esc_attr( get_option( 'fb_reviews_recommended_language' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
								<?php esc_html_e( 'Text for "See More Reviews"', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_see_more_reviews_language" class="feed-them-social-admin-input" id="fb_reviews_see_more_reviews_language" placeholder="See More Reviews" value="<?php echo esc_attr( get_option( 'fb_reviews_see_more_reviews_language' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Remove "See More Reviews" link', 'feed-them-social' ); ?>
							</div>
							<select name="fb_reviews_remove_see_reviews_link" id="fb_reviews_remove_see_reviews_link" class="feed-them-social-admin-input">
								<option value="">
									<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_reviews_remove_see_reviews_link, 'yes', false ); ?> value="<?php esc_html_e( 'yes' ); ?>">
									<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_reviews_remove_see_reviews_link, 'no', false ); ?> value="<?php esc_html_e( 'no' ); ?>">
									<?php esc_html_e( 'No', 'feed-them-social' ); ?>
								</option>
							</select>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="fts-title-description-settings-page" id="overall-rating-options">
							<h3>
								<?php esc_html_e( 'Reviews: Overall Rating Style Options', 'feed-them-social' ); ?>
							</h3>
							<?php esc_html_e( 'These styles are for the overall rating that appear above your feed.', 'feed-them-social' ); ?>
						</div>
						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Hide Overall Rating Background & Border', 'feed-them-social' ); ?>
							</div>
							<select name="fb_reviews_overall_rating_background_border_hide" id="fb_reviews_overall_rating_background_border_hide" class="feed-them-social-admin-input">
								<option value="">
									<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_reviews_overall_rating_background_border_hide, 'yes', false ); ?> value="<?php esc_html_e( 'yes' ); ?>">
									<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_reviews_overall_rating_background_border_hide, 'no', false ); ?> value="<?php esc_html_e( 'no' ); ?>">
									<?php esc_html_e( 'No', 'feed-them-social' ); ?>
								</option>
							</select>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating Background Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_background_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_background_color" placeholder="#fff" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_background_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating Text Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_text_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_text_color" placeholder="#fff" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_text_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating Border Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_border_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_border_color" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_border_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating Background Padding', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_background_padding" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_background_padding" placeholder="10px 10px 15px 10px" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_background_padding' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating "of 5 stars" text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_of_5_stars_text" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_of_5_stars_text" placeholder="of 5 stars" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_of_5_stars_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( 'Overall Rating "reviews" text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_reviews_overall_rating_reviews_text" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_reviews_text" placeholder="reviews" value="<?php echo esc_attr( get_option( 'fb_reviews_overall_rating_reviews_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<?php
} // end if reviewsp plugin active
?>

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php esc_html_e( 'Language Options', 'feed-them-social' ); ?>
							</h3>
							<?php

							echo sprintf(
								esc_html__( 'You must have your Facebook Access Token saved above before this feature will work. This option will translate the FB Titles and Like Button or Box Text. It will not translate your actual post. To translate the Feed Them Social parts of this plugin just set your language on the %1$sWordPress settings%2$s page. If would like to help translate please %3$sClick Here.%4$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'options-general.php' ) . '" target="_blank">',
								'</a>',
								'<a href="' . esc_url( 'http://translate.slickremix.com/glotpress/projects/feed-them-social/' ) . '" target="_blank">',
								'</a>'
							);

							?>

						</div>
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Language For Facebook Feeds', 'feed-them-social' ); ?>
						</div>
						<select name="fb_language" id="fb-lang-btn" class="feed-them-social-admin-input">
							<option value="en_US">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<?php
							if ( ! empty( $lang_options_array->locale ) ) {
								foreach ( $lang_options_array->locale as $language ) {
									echo '<option ' . selected( $fb_language, $language->codes->code->standard->representation, true ) . ' value="' . esc_html( $language->codes->code->standard->representation ) . '">' . esc_html( $language->englishName ) . '</option>';
								}
							}
							?>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap" style="display: none;">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php esc_html_e( 'Offset Limit', 'feed-them-social' ); ?>
							</h3>
							<?php
							echo sprintf(
								esc_html__( '%1$sWARNING, PLEASE READ CAREFULLY!%2$s DO NOT use this field to set your facebook posts. If you are getting the message "Please go to the Facebook Options page of our plugin and look for the "Change Limit" option and add the number 7 or more." then adjust the number below so posts will show in your feed. Generally adding at least %3$s7%4$s is a good idea if you are getting that notice. This is only for Pages and Groups. We filter certain posts that do not have a story or message or if the shared content is not available via the API.', 'feed-them-social' ),
								'<strong style="color:red">',
								'</strong>',
								'<strong>',
								'</strong>'
							);
							?>
						</div>
						<div class="feed-them-social-admin-input-label">
							<?php esc_html_e( 'Offset Quantity', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_count_offset" class="feed-them-social-admin-input" id="fb_count_offset" value="<?php echo esc_attr( get_option( 'fb_count_offset' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Hide Notice on Front End', 'feed-them-social' ); ?>
						</div>
						<select name="fb_hide_no_posts_message" id="fb_hide_no_posts_message" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_no_posts_message, 'yes', false ); ?> value="<?php esc_html_e( 'yes' ); ?>">
								<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_no_posts_message, 'no', false ); ?> value="<?php esc_html_e( 'no' ); ?>">
								<?php esc_html_e( 'No', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->



				<div class="feed-them-social-admin-input-wrap">
					<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
						<?php esc_html_e( 'View on Facebook', 'feed-them-social' ); ?>
					</div>
					<input type="text" name="fb_view_on_fb_fts" class="feed-them-social-admin-input" placeholder="" value="<?php echo esc_attr( get_option( 'fb_view_on_fb_fts' ) ); ?>"/>
					<div class="clear"></div>



						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->



					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php esc_html_e( 'Like Button or Box Options', 'feed-them-social' ); ?>
							</h3>
							<?php esc_html_e( 'This will only show on regular feeds not combined feeds.', 'feed-them-social' ); ?>
						</div>
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Show Follow Button', 'feed-them-social' ); ?>

						</div>
						<select name="fb_show_follow_btn" id="fb-show-follow-btn" class="feed-them-social-admin-input">
							<option>
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn, 'dont-display', false ); ?> value="<?php esc_html_e( 'dont-display' ); ?>">
								<?php esc_html_e( 'Don\'t Display a Button', 'feed-them-social' ); ?>
							</option>
							<optgroup label="Like Box">
								<option <?php echo selected( $fb_show_follow_btn, 'like-box', false ); ?> value="<?php esc_html_e( 'like-box' ); ?>">
									<?php esc_html_e( 'Like Box', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_show_follow_btn, 'like-box-faces', false ); ?> value="<?php esc_html_e( 'like-box-faces' ); ?>">
									<?php esc_html_e( 'Like Box with Faces', 'feed-them-social' ); ?>
								</option>
							</optgroup>
							<optgroup label="Like Button">
								<option <?php echo selected( $fb_show_follow_btn, 'like-button', false ); ?> value="<?php esc_html_e( 'like-button' ); ?>">
									<?php esc_html_e( 'Like Button', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_show_follow_btn, 'like-button-share', false ); ?> value="<?php esc_html_e( 'like-button-share' ); ?>">
									<?php esc_html_e( 'Like Button and Share Button', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_show_follow_btn, 'like-button-faces', false ); ?> value="<?php esc_html_e( 'like-button-faces' ); ?>">
									<?php esc_html_e( 'Like Button with Faces', 'feed-them-social' ); ?>
								</option>
								<option <?php echo selected( $fb_show_follow_btn, 'like-button-share-faces', false ); ?> value="<?php esc_html_e( 'like-button-share-faces' ); ?>">
									<?php esc_html_e( 'Like Button and Share Button with Faces', 'feed-them-social' ); ?>
								</option>
							</optgroup>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap" style="display:none">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Show Profile Icon next to social option above', 'feed-them-social' ); ?>
						</div>
						<select name="fb_show_follow_like_box_cover" id="fb-show-follow-like-box-cover" class="feed-them-social-admin-input">
							<option>
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn_profile_pic, 'fb_like_box_cover-yes', false ); ?> value="<?php esc_html_e( 'fb_like_box_cover-yes' ); ?>">
								<?php esc_html_e( 'Display Cover Photo in Like Box', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn_profile_pic, 'fb_like_box_cover-no', false ); ?> value="<?php esc_html_e( 'fb_like_box_cover-no' ); ?>">
								<?php esc_html_e( 'Hide Cover Photo in Like Box', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Like Button Color', 'feed-them-social' ); ?>
						</div>
						<select name="fb_like_btn_color" id="fb-like-btn-color" class="feed-them-social-admin-input">
							<option value="light">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_like_btn_color, 'light', false ); ?> value="<?php esc_html_e( 'light' ); ?>">
								<?php esc_html_e( 'Light', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_like_btn_color, 'dark', false ); ?> value="<?php esc_html_e( 'dark' ); ?>">
								<?php esc_html_e( 'Dark', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Placement of the Button(s)', 'feed-them-social' ); ?>
						</div>
						<select name="fb_show_follow_btn_where" id="fb-show-follow-btn-where" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn_where, 'fb-like-top-above-title', false ); ?> value="<?php esc_attr_e( 'fb-like-top-above-title' ); ?>">
								<?php esc_html_e( 'Show Top of Feed Above Title', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn_where, 'fb-like-top-below-title', false ); ?> value="<?php esc_attr_e( 'fb-like-top-below-title' ); ?>">
								<?php esc_html_e( 'Show Top of Feed Below Title', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_show_follow_btn_where, 'fb-like-below', false ); ?> value="<?php esc_attr_e( 'fb-like-below' ); ?>">
								<?php esc_html_e( 'Show Botton of Feed', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page" style="margin-top:0;">
							<h3>
								<?php esc_html_e( 'Global Facebook Style Options', 'feed-them-social' ); ?>
							</h3>
						</div>

						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php
							echo sprintf(
								esc_html__( 'Page Title Tag %1$s %2$s', 'feed-them-social' ),
								'<br/><small>',
								'</small>'
							);
							?>
						</div>
						<select name="fb_title_htag" id="fb_title_htag" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h1', false ); ?> value="<?php esc_attr_e( 'h1' ); ?>">
								<?php esc_html_e( 'h1 (Default)', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h2', false ); ?> value="<?php esc_attr_e( 'h2' ); ?>">
								<?php esc_html_e( 'h2', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h3', false ); ?> value="<?php esc_attr_e( 'h3' ); ?>">
								<?php esc_html_e( 'h3', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h4', false ); ?> value="<?php esc_attr_e( 'h4' ); ?>">
								<?php esc_html_e( 'h4', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h5', false ); ?> value="<?php esc_attr_e( 'h5' ); ?>">
								<?php esc_html_e( 'h5', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_title_htag, 'h6', false ); ?> value="<?php esc_attr_e( 'h6' ); ?>">
								<?php esc_html_e( 'h6', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
							<?php esc_html_e( 'Page Title Size', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_title_htag_size" class="feed-them-social-admin-input" placeholder="16px" value="<?php echo esc_attr( get_option( 'fb_title_htag_size' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->


					<div class="feed-them-social-admin-input-wrap">

						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php
							echo sprintf(
								esc_html( 'Text after your FB name %1$sie* Shared by or New Photo Added etc.%2$s', 'feed-them-social' ),
								'<br/><small>',
								'</small>'
							);
							?>
						</div>
						<select name="fb_hide_shared_by_etc_text" id="fb_hide_shared_by_etc_text" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_shared_by_etc_text, 'no', false ); ?> value="<?php esc_attr_e( 'no' ); ?>">
								<?php esc_html_e( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_shared_by_etc_text, 'yes', false ); ?> value="<?php esc_attr_e( 'yes' ); ?>">
								<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
							<?php esc_html_e( 'Hide Images in Posts', 'feed-them-social' ); ?>
						</div>
						<select name="fb_hide_images_in_posts" id="fb_hide_images_in_posts" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_images_in_posts, 'no', false ); ?> value="<?php esc_attr_e( 'no' ); ?>">
								<?php esc_html_e( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_images_in_posts, 'yes', false ); ?> value="<?php esc_attr_e( 'yes' ); ?>">
								<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
						<div class="clear"></div>
					</div>
					<!--/fts-twitter-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
							<?php esc_html_e( 'Max-width for Images & Videos', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_max_image_width" class="feed-them-social-admin-input" placeholder="500px" value="<?php echo esc_attr( get_option( 'fb_max_image_width' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
							<?php esc_html_e( 'Feed Header Extra Text Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_header_extra_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fb_header_extra_text_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-text-size-label">
							<?php esc_html_e( 'Feed Description Text Size', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_text_size" class="feed-them-social-admin-input fb-text-size-input" id="fb-text-size-input" placeholder="12px" value="<?php echo esc_attr( get_option( 'fb_text_size' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
							<?php esc_html_e( 'Feed Text Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fb_text_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-link-color-label">
							<?php esc_html_e( 'Feed Link Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_link_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fb_link_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-link-color-hover-label">
							<?php esc_html_e( 'Feed Link Color Hover', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_link_color_hover" class="feed-them-social-admin-input fb-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-hover-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_link_color_hover' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-feed-width-label">
							<?php esc_html_e( 'Feed Width', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_feed_width" class="feed-them-social-admin-input fb-feed-width-input" id="fb-feed-width-input" placeholder="500px" value="<?php echo esc_attr( get_option( 'fb_feed_width' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-feed-margin-label">
							<?php
							echo sprintf(
								esc_html( 'Feed Margin %1$sTo center feed type auto%2$s', 'feed-them-social' ),
								'<br/><small>',
								'</small>'
							);
							?>
						</div>
						<input type="text" name="fb_feed_margin" class="feed-them-social-admin-input fb-feed-margin-input" id="fb-feed-margin-input" placeholder="10px" value="<?php echo esc_attr( get_option( 'fb_feed_margin' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-feed-padding-label">
							<?php esc_html_e( 'Feed Padding', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_feed_padding" class="feed-them-social-admin-input fb-feed-padding-input" id="fb-feed-padding-input" placeholder="10px" value="<?php echo esc_attr( get_option( 'fb_feed_padding' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) { ?>
						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-fb-post-background-color-label">
								<?php
								echo sprintf(
									esc_html( 'Post Background Color %1$sOnly works with show_media=top%2$s', 'feed-them-social' ),
									'<br/><small>',
									'</small>'
								);
								?>
							</div>
							<input type="text" name="fb_post_background_color" class="feed-them-social-admin-input fb-post-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_post_background_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->
					<?php } ?>

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-feed-background-color-label">
							<?php esc_html_e( 'Feed Background Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_feed_background_color" class="feed-them-social-admin-input fb-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_feed_background_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
							<?php esc_html_e( 'Border Bottom Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_border_bottom_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
						<div class="feed-them-social-admin-input-wrap">
							<div class="fts-title-description-settings-page">
								<h3>
									<?php esc_html_e( 'Grid Styles', 'feed-them-social' ); ?>
								</h3>
							</div>
							<div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label">
								<?php esc_html_e( 'Posts Background Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_grid_posts_background_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-fb-grid-border-bottom-color-label">
								<?php esc_html_e( 'Border Bottom Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_grid_border_bottom_color" class="feed-them-social-admin-input fb-grid-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_grid_border_bottom_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="fts-title-description-settings-page">
								<h3>
									<?php esc_html_e( 'Load More Button Styles & Options', 'feed-them-social' ); ?>
								</h3>
							</div>
							<div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
								<?php esc_html_e( 'Button Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-loadmore-background-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_loadmore_background_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-fb-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
								<?php esc_html_e( 'Text Color', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-loadmore-text-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_loadmore_text_color' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-fb-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( '"Load More" Text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_load_more_text" class="feed-them-social-admin-input" id="fb_load_more_text" placeholder="Load More" value="<?php echo esc_attr( get_option( 'fb_load_more_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( '"No More Posts" Text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_no_more_posts_text" class="feed-them-social-admin-input" id="fb_no_more_posts_text" placeholder="No More Posts" value="<?php echo esc_attr( get_option( 'fb_no_more_posts_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( '"No More Photos" Text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_no_more_photos_text" class="feed-them-social-admin-input" id="fb_no_more_photos_text" placeholder="No More Photos" value="<?php echo esc_attr( get_option( 'fb_no_more_photos_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( '"No More Videos" Text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_no_more_videos_text" class="feed-them-social-admin-input" id="fb_no_more_videos_text" placeholder="No More Videos" value="<?php echo esc_attr( get_option( 'fb_no_more_videos_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->

					<?php } ?>

					<?php if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>

						<div class="feed-them-social-admin-input-wrap">
							<div class="feed-them-social-admin-input-label">
								<?php esc_html_e( '"No More Reviews" Text', 'feed-them-social' ); ?>
							</div>
							<input type="text" name="fb_no_more_reviews_text" class="feed-them-social-admin-input" id="fb_no_more_reviews_text" placeholder="No More Reviews" value="<?php echo esc_attr( get_option( 'fb_no_more_reviews_text' ) ); ?>"/>
							<div class="clear"></div>
						</div>
						<!--/fts-facebook-feed-styles-input-wrap-->
					<?php } ?>

					<div class="feed-them-social-admin-input-wrap" style="display: none !important;">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php esc_html_e( 'Event Style Options', 'feed-them-social' ); ?>
							</h3>
							<?php esc_html_e( 'The styles above still apply, these are just some extra options for the Event List feed.', 'feed-them-social' ); ?>
						</div>
						<div class="feed-them-social-admin-input-label fb-events-title-color-label">
							<?php esc_html_e( 'Events Feed: Title Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_events_title_color" class="feed-them-social-admin-input fb-events-title-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-events-title-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_events_title_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap" style="display: none !important;">
						<div class="feed-them-social-admin-input-label fb-events-title-size-label">
							<?php esc_html_e( 'Events Feed: Title Size', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_events_title_size" class="feed-them-social-admin-input fb-events-title-size" id="fb-events-title-color-input" placeholder="20px" value="<?php echo esc_attr( get_option( 'fb_events_title_size' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap" style="display: none !important;">
						<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
							<?php esc_html_e( 'Events Feed: Map Link Color', 'feed-them-social' ); ?>
						</div>
						<input type="text" name="fb_events_map_link_color" class="feed-them-social-admin-input fb-events-map-link-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-events-map-link-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fb_events_map_link_color' ) ); ?>"/>
						<div class="clear"></div>
					</div>
					<!--/fts-facebook-feed-styles-input-wrap-->

					<div class="feed-them-social-admin-input-wrap">
						<div class="fts-title-description-settings-page">
							<h3>
								<?php esc_html_e( 'Facebook Error Message', 'feed-them-social' ); ?>
							</h3>
							<?php
							echo sprintf(
								esc_html( 'If your feed is displaying a notice or error message at times you can utilize this option to hide them from displaying. Make sure and delete the %1$sCache%2$s to see the change. %3$sNOTE: This does not hide any php warnings that may come up. To remove those go to the wp-config.php file on root of your WordPress install and set the wp_debug option to FALSE. Having that option set to TRUE is really only necessary when developing.%4$s', 'feed-them-social' ),
								'<a href="' . esc_url( 'admin.php?page=feed-them-settings-page&tab=global_options' ) . '">',
								'</a>',
								'<p><small>',
								'</small></p>'
							);
							?>
						</div>
						<div class="feed-them-social-admin-input-label fb-error-handler-label">
							<?php esc_html_e( 'Hide Error Handler Message', 'feed-them-social' ); ?>
						</div>
						<select name="fb_hide_error_handler_message" id="fb_hide_error_handler_message" class="feed-them-social-admin-input">
							<option value="">
								<?php esc_html_e( 'Please Select Option', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_error_handler_message, 'no', false ); ?> value="<?php esc_attr_e( 'no' ); ?>">
								<?php esc_html_e( 'No', 'feed-them-social' ); ?>
							</option>
							<option <?php echo selected( $fb_hide_error_handler_message, 'yes', false ); ?> value="<?php esc_attr_e( 'yes' ); ?>">
								<?php esc_html_e( 'Yes', 'feed-them-social' ); ?>
							</option>
						</select>
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
}//end class
