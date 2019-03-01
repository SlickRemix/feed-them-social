<?php
/**
 * Feed Them Social - Settings Page
 *
 * This page is used to Set Global options and Creates Shortcode Generator
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Settings Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Settings_Page {

	/**
	 * Construct
	 *
	 * FTS_settings_page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
	}

	/**
	 * Feed Them Settings Page
	 *
	 * Main Settings Page.
	 *
	 * @since 1.9.6
	 */
	public function feed_them_settings_page() {
		$fts_functions = new feed_them_social_functions();

		if ( ! function_exists( 'curl_init' ) ) {
			print '<div class="error"><p>' . esc_html( 'Warning: cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social' ) . '</p></div>';
		}

		$fts_fb_options_nonce = wp_create_nonce( 'fts-settings-page-nonce' );

		if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-settings-page-nonce' ) ) {
			?>

			<div class="feed-them-social-admin-wrap">
				<div class="fts-backg"></div>
				<div class="fts-content">
					<h1 class="fts-logo-header"><?php echo esc_html( 'Feed Them Social', 'feed-them-social' ); ?></h1>

					<div class="feed-them-icon-wrap">
						<a href="javascript:" class="youtube-icon"></a>
						<a href="javascript:" class="twitter-icon"></a>
						<a href="javascript:" class="facebook-icon"></a>
						<a href="javascript:" class="instagram-icon"></a>
						<a href="javascript:" class="pinterest-icon"></a>

						<div id="discount-for-review">
							<a href="admin.php?page=fts-license-page"><?php echo esc_html( 'View Extensions & Demos', 'feed-them-social' ); ?></a>
						</div>
					</div>

					<div class="fts-tabs" id="fts-tabs">

						<label for="fts-tab1" class="fts-tab1 fts-tabbed <?php echo isset( $_GET['tab'] ) && 'general_options' === $_GET['tab'] || ! isset( $_GET['tab'] ) ? 'tab-active' : ''; ?>" id="general_options">
							<span><?php echo esc_html( 'Create Shortcode', 'feed-them-social' ); ?></span>
						</label>

						<label for="fts-tab2" class="fts-tab2 fts-tabbed <?php echo isset( $_GET['tab'] ) && 'global_options' === $_GET['tab'] ? 'tab-active' : ''; ?>" id="global_options">
							<span><?php echo esc_html( 'Global Options', 'feed-them-social' ); ?></span>
						</label>

						<div id="fts-tab-content1" class="fts-tab-content fts-hide-me <?php echo isset( $_GET['tab'] ) && 'general_options' === $_GET['tab'] || ! isset( $_GET['tab'] ) ? 'pane-active' : ''; ?>">
							<section>

								<h2 class="fts-logo-subheader"><?php echo esc_html( 'Create Shortcode for Social Network', 'feed-them-social' ); ?></h2>
								<div class="use-of-plugin"><?php echo esc_html( 'Please select what type of feed you would like using the select option below. After setting your options click the green Generate Shortcode button, then copy and paste the shortcode to a page, post or widget.', 'feed-them-social' ); ?></div>

								<form class="feed-them-social-admin-form" id="feed-selector-form">
									<select id="shortcode-form-selector">
										<option value=""><?php echo esc_html( 'Select a Social Network', 'feed-them-social' ); ?> </option>
										<option value="fts-fb-page-shortcode-form"><?php echo esc_html( 'Facebook Feed', 'feed-them-social' ); ?></option>
										<option value="combine-steams-shortcode-form"><?php echo esc_html( 'Combine Streams Feed', 'feed-them-social' ); ?></option>
										<option value="twitter-shortcode-form"><?php echo esc_html( 'Twitter Feed', 'feed-them-social' ); ?></option>
										<option value="instagram-shortcode-form"><?php echo esc_html( 'Instagram Feed', 'feed-them-social' ); ?></option>
										<option value="youtube-shortcode-form"><?php echo esc_html( 'YouTube Feed' ); ?></option>
										<option value="pinterest-shortcode-form"><?php echo esc_html( 'Pinterest Feed', 'feed-them-social' ); ?></option>
									</select>
								</form><!--/feed-them-social-admin-form-->

								<?php

								$step2_custom_message = __( '<br/><strong>STEP 2:</strong> Generate your custom shortcode using the options below, then click generate shortcode and paste that to a Page, Post or widget.', 'feed-them-social' );

								$limitforpremium = ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ? '<small class="fts-required-more-posts"><br/>' . __( 'More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social' ) . '</small>' : '';

								if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
									$facebook_reviews_token_check = array(
										1 => array(
											'option_name'  => 'fts_facebook_custom_api_token',
											'no_token_msg' => sprintf(
												__( '%1$sSTEP 1:%2$s Please get your API Token on our %3$sFacebook Options%4$s page before getting started.%5$s', 'feed-them-social' ),
												'<strong>',
												'</strong>',
												'<a href="admin.php?page=fts-facebook-feed-styles-submenu-page">',
												'</a>',
												$step2_custom_message
											),
										),
										2 => array(
											'option_name'  => 'fts_facebook_custom_api_token_biz',
											'no_token_msg' => sprintf(
												__( '%1$sSTEP 1:%2$s Please add a Facebook Page Reviews API Token to our %3$sFacebook Options%4$s page before getting started.%5$s', 'feed-them-social' ),
												'<strong>',
												'</strong>',
												'<a href="admin.php?page=fts-facebook-feed-styles-submenu-page">',
												'</a>',
												$step2_custom_message
											),
											'req_plugin'   => 'facebook_reviews',
										),
									);

								} else {
									$facebook_reviews_token_check = array(
										1 => array(
											'option_name'  => 'fts_facebook_custom_api_token',
											'no_token_msg' => sprintf(
												__( '%1$sSTEP 1:%2$s Please get your API Token on our %3$sFacebook Options%4$s page before getting started.%5$s', 'feed-them-social' ),
												'<strong>',
												'</strong>',
												'<a href="admin.php?page=fts-facebook-feed-styles-submenu-page">',
												'</a>',
												$step2_custom_message
											),
										),
									);
								}
								$required_plugins = array(
									'fts_premium'      => array(
										// Name will go into Non-Premium field so make sure it says "extension" Example: Must have {Plugin Name} to edit.
										'name'          => '<h3>Feed Them Premium extension</h3>',
										// Slick URL should Take them to plugin on Slickremix.com because they need for required fields!
										'slick_url'     => 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/',
										// Plugin URL for checking if plugin is active!
										'plugin_url'    => 'feed-them-premium/feed-them-premium.php',
										'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> to edit.',
									),
									'facebook_reviews' => array(
										'name'          => '<h3>Facebook Reviews extension</h3>',
										'slick_url'     => 'https://www.slickremix.com/downloads/feed-them-social-facebook-reviews/',
										'plugin_url'    => 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php',
										'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> and <a href="https://www.slickremix.com/downloads/feed-them-carousel-premium/">carousel</a> to edit.',
									),
									'fts_carousel'     => array(
										'name'          => '<h3>Feed Them Carousel extension</h3>',
										'slick_url'     => 'https://www.slickremix.com/downloads/feed-them-carousel-premium/',
										'plugin_url'    => 'feed-them-carousel-premium/feed-them-carousel-premium.php',
										'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> and <a href="https://www.slickremix.com/downloads/feed-them-carousel-premium/">carousel</a> to edit.',
									),
									'combine_streams'  => array(
										'name'          => '<h3>Feed Them Social Combined Streams extension</h3>',
										'slick_url'     => 'https://www.slickremix.com/downloads/feed-them-social-combined-streams/',
										'plugin_url'    => 'feed-them-social-combined-streams/feed-them-social-combined-streams.php',
										'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-combined-streams/">combined streams extenstion</a> to edit.',
									),
								);

								$settings_options_array = new FTS_Settings_Page_Options();
								$feed_settings_array    = $settings_options_array->settings_page_options( $facebook_reviews_token_check, $limitforpremium, $step2_custom_message );

								echo $fts_functions->fts_settings_html_form( false, $feed_settings_array, $required_plugins );
								?>
							</section>
						</div> <!-- #fts-tab-content1 -->

						<div id="fts-tab-content2" class="fts-tab-content fts-hide-me <?php echo isset( $_GET['tab'] ) && 'global_options' === $_GET['tab'] ? 'pane-active' : ''; ?>
							">
							<section>
								<div class="feed-them-clear-cache">
									<h2><?php echo esc_html( 'Clear All Cache Options', 'feed-them-social' ); ?></h2>
									<div class="use-of-plugin"><?php echo esc_html( 'Please Clear Cache if you have changed a Feed Them Social Shortcode. This will Allow you to see the changes right away.', 'feed-them-social' ); ?></div>
									<?php
									if ( isset( $_GET['cache'] ) && 'clearcache' === $_GET['cache'] ) {
										echo '<div class="feed-them-clear-cache-text">' . esc_html( $fts_functions->feed_them_clear_cache() ) . '</div>';
									}

									$fts_dev_mode_cache = null !== get_option( 'fts_clear_cache_developer_mode' ) ? get_option( 'fts_clear_cache_developer_mode' ) : '900';
									$fts_admin_bar_menu = get_option( 'fts_admin_bar_menu' );
									?>

									<form method="post" action="?page=feed-them-settings-page&cache=clearcache&tab=global_options">
										<input class="feed-them-social-admin-submit-btn" type="submit" value="<?php echo esc_html( 'Clear All FTS Feeds Cache', 'feed-them-social' ); ?>"/>
									</form>
								</div><!--/feed-them-clear-cache-->
								<!-- custom option for padding -->
								<form method="post" class="fts-color-settings-admin-form" action="options.php">
									<p>
										<label><?php echo esc_html( 'Cache Time', 'feed-them-social' ); ?></label>
										<select id="fts_clear_cache_developer_mode" name="fts_clear_cache_developer_mode">
											<option value=""><?php echo esc_html( 'Please choose an option', 'feed-them-social' ); ?></option>
											<option value="86400" <?php echo '86400' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '1 Day ago (Suggested Default)', 'feed-them-social' ); ?></option>
											<option value="172800" <?php echo '172800' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '2 Days', 'feed-them-social' ); ?></option>
											<option value="259200" <?php echo '259200' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '3 Days', 'feed-them-social' ); ?></option>
											<option value="604800" <?php echo '604800' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '1 Week', 'feed-them-social' ); ?></option>
											<option value="1209600" <?php echo '1209600' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '2 Weeks', 'feed-them-social' ); ?></option>
											<option value="1" <?php echo '1' === $fts_dev_mode_cache ? 'selected="selected"' : ''; ?>><?php echo esc_html( '(Developers Only) Clear cache on every page load', 'feed-them-social' ); ?></option>
										</select>
									</p>
									<label><?php echo esc_html( 'Admin Bar', 'feed-them-social' ); ?></label>
									<select id="fts_admin_bar_menu" name="fts_admin_bar_menu">
										<option value="<?php echo esc_attr( 'show-admin-bar-menu' ); ?>" <?php echo 'show-admin-bar-menu' === $fts_admin_bar_menu ? 'selected="selected"' : ''; ?>>
											<?php echo esc_html( 'Show Admin Bar Menu', 'feed-them-social' ); ?>
										</option>
										<option value="<?php echo esc_attr( 'hide-admin-bar-menu' ); ?>" <?php echo 'hide-admin-bar-menu' === $fts_admin_bar_menu ? 'selected="selected"' : ''; ?>>
											<?php echo esc_html( 'Hide Admin Bar Menu', 'feed-them-social' ); ?>
										</option>
									</select>
									<div class="feed-them-custom-css">
										<?php
										// get our registered settings from the fts functions!
										settings_fields( 'feed-them-social-settings' );
										?>
										<?php
										$fts_date_time_format = get_option( 'fts-date-and-time-format' );
										$fts_timezone         = get_option( 'fts-timezone' );
										$fts_custom_date      = get_option( 'date_format' );
										$fts_custom_time      = get_option( 'time_format' );
										$fts_custom_timezone  = get_option( 'fts-timezone' ) ? get_option( 'fts-timezone' ) : 'America/Los_Angeles';
										date_default_timezone_set( $fts_custom_timezone );

										?>
										<div style="float:left; max-width:400px; margin-right:30px;">
											<h2><?php echo esc_html( 'FaceBook & Twitter Date Format', 'feed-them-social' ); ?></h2>

											<fieldset>
												<select id="fts-date-and-time-format" name="fts-date-and-time-format">
													<option value="<?php echo esc_attr( 'l, F jS, Y \a\t g:ia' ); ?>" <?php echo 'l, F jS, Y \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'l, F jS, Y \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'F j, Y \a\t g:ia' ); ?>" <?php echo 'F j, Y \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'F j, Y \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'F j, Y g:ia' ); ?>" <?php echo 'F j, Y g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'F j, Y g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'F, Y \a\t g:ia' ); ?>" <?php echo 'F, Y \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'F, Y \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'M j, Y @ g:ia' ); ?>" <?php echo 'M j, Y @ g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'M j, Y @ g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'M j, Y @ G:i' ); ?>" <?php echo 'M j, Y @ G:i' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'M j, Y @ G:i' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'm/d/Y \a\t g:ia' ); ?>" <?php echo 'm/d/Y \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'm/d/Y \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'm/d/Y @ G:i' ); ?>" <?php echo 'm/d/Y @ G:i' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'm/d/Y @ G:i' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'd/m/Y \a\t g:ia' ); ?>" <?php echo 'd/m/Y \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'd/m/Y \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'd/m/Y @ G:i' ); ?>" <?php echo 'd/m/Y @ G:i' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'd/m/Y @ G:i' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'Y/m/d \a\t g:ia' ); ?>" <?php echo 'Y/m/d \a\t g:ia' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'Y/m/d \a\t g:ia' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'Y/m/d @ G:i' ); ?>" <?php echo 'Y/m/d @ G:i' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( date( 'Y/m/d @ G:i' ) ); ?>
													</option>
													<option value="<?php echo esc_attr( 'one-day-ago' ); ?>" <?php echo 'one-day-ago' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( 'One Day Ago' ); ?>
													</option>
													<option value="<?php echo esc_attr( 'fts-custom-date' ); ?>" <?php echo 'fts-custom-date' === $fts_date_time_format ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( 'Use Custom Date and Time Option Below', 'feed-them-social' ); ?>
													</option>
												</select>
											</fieldset>

											<?php
											// Date translate!
											$fts_language_second  = get_option( 'fts_language_second', 'second' );
											$fts_language_seconds = get_option( 'fts_language_seconds', 'seconds' );
											$fts_language_minute  = get_option( 'fts_language_minute', 'minute' );
											$fts_language_minutes = get_option( 'fts_language_minutes', 'minutes' );
											$fts_language_hour    = get_option( 'fts_language_hour', 'hour' );
											$fts_language_hours   = get_option( 'fts_language_hours', 'hours' );
											$fts_language_day     = get_option( 'fts_language_day', 'day' );
											$fts_language_days    = get_option( 'fts_language_days', 'days' );
											$fts_language_week    = get_option( 'fts_language_week', 'week' );
											$fts_language_weeks   = get_option( 'fts_language_weeks', 'weeks' );
											$fts_language_month   = get_option( 'fts_language_month', 'month' );
											$fts_language_months  = get_option( 'fts_language_months', 'months' );
											$fts_language_year    = get_option( 'fts_language_year', 'year' );
											$fts_language_years   = get_option( 'fts_language_years', 'years' );
											$fts_language_ago     = get_option( 'fts_language_ago', 'ago' );
											?>

											<div class="custom_time_ago_wrap" style="display:none;">
												<h2><?php echo esc_html( 'Translate words for 1 day ago option.', 'feed-them-social' ); ?></h2>
												<label for="fts_language_second"><?php echo esc_html( 'second' ); ?></label>
												<input name="fts_language_second" type="text" value="<?php echo esc_attr( $fts_language_second ); ?>" size="25"/>
												<br/>
												<label for="fts_language_seconds"><?php echo esc_html( 'seconds' ); ?></label>
												<input name="fts_language_seconds" type="text" value="<?php echo esc_attr( $fts_language_seconds ); ?>" size="25"/>
												<br/>
												<label for="fts_language_minute"><?php echo esc_html( 'minute' ); ?></label>
												<input name="fts_language_minute" type="text" value="<?php echo esc_attr( $fts_language_minute ); ?>" size="25"/>
												<br/>
												<label for="fts_language_minutes"><?php echo esc_html( 'minutes' ); ?></label>
												<input name="fts_language_minutes" type="text" value="<?php echo esc_attr( $fts_language_minutes ); ?>" size="25"/>
												<br/>
												<label for="fts_language_hour"><?php echo esc_html( 'hour' ); ?></label>
												<input name="fts_language_hour" type="text" value="<?php echo esc_attr( $fts_language_hour ); ?>" size="25"/>
												<br/>
												<label for="fts_language_hours"><?php echo esc_html( 'hours' ); ?></label>
												<input name="fts_language_hours" type="text" value="<?php echo esc_attr( $fts_language_hours ); ?>" size="25"/>
												<br/>
												<label for="fts_language_day"><?php echo esc_html( 'day' ); ?></label>
												<input name="fts_language_day" type="text" value="<?php echo esc_attr( $fts_language_day ); ?>" size="25"/>
												<br/>
												<label for="fts_language_days"><?php echo esc_html( 'days' ); ?></label>
												<input name="fts_language_days" type="text" value="<?php echo esc_attr( $fts_language_days ); ?>" size="25"/>
												<br/>
												<label for="fts_language_week"><?php echo esc_html( 'week' ); ?></label>
												<input name="fts_language_week" type="text" value="<?php echo esc_attr( $fts_language_week ); ?>" size="25"/>
												<br/>
												<label for="fts_language_weeks"><?php echo esc_html( 'weeks' ); ?></label>
												<input name="fts_language_weeks" type="text" value="<?php echo esc_attr( $fts_language_weeks ); ?>" size="25"/>
												<br/>
												<label for="fts_language_month"><?php echo esc_html( 'month' ); ?></label>
												<input name="fts_language_month" type="text" value="<?php echo esc_attr( $fts_language_month ); ?>" size="25"/>
												<br/>
												<label for="fts_language_months"><?php echo esc_html( 'months' ); ?></label>
												<input name="fts_language_months" type="text" value="<?php echo esc_attr( $fts_language_months ); ?>" size="25"/>
												<br/>
												<label for="fts_language_year"><?php echo esc_html( 'year' ); ?></label>
												<input name="fts_language_year" type="text" value="<?php echo esc_attr( $fts_language_year ); ?>" size="25"/>
												<br/>
												<label for="fts_language_years"><?php echo esc_html( 'years' ); ?></label>
												<input name="fts_language_years" type="text" value="<?php echo esc_attr( $fts_language_years ); ?>" size="25"/>
												<br/>
												<label for="fts_language_ago"><?php echo esc_html( 'ago' ); ?></label>
												<input name="fts_language_ago" type="text" value="<?php echo esc_attr( $fts_language_ago ); ?>" size="25"/>

											</div>
											<script>
												// change the feed type 'how to' message when a feed type is selected

												<?php if ( 'one-day-ago' === $fts_date_time_format ) { ?>
												jQuery('.custom_time_ago_wrap').show();
												<?php } ?>
												jQuery('#fts-date-and-time-format').change(function () {

													var ftsTimeAgo = jQuery("select#fts-date-and-time-format").val();
													if ( 'one-day-ago' === ftsTimeAgo ) {
														jQuery('.custom_time_ago_wrap').show();
													}
													else {
														jQuery('.custom_time_ago_wrap').hide();
													}

												});

											</script>
											<h2 style="border-top:0px; margin-bottom:4px !important;"><?php echo esc_html( 'Custom Date and Time', 'feed-them-social' ); ?></h2>
											<div>
												<?php echo ! empty( $fts_custom_date ) || ! empty( $fts_custom_time ) ? esc_html( date( get_option( 'fts-custom-date' ) . ' ' . get_option( 'fts-custom-time' ) ) ) : ''; ?>
											</div>
											<p style="margin:12px 0 !important;">
												<input name="fts-custom-date" style="max-width:105px;" class="fts-color-settings-admin-input" id="fts-custom-date" placeholder="<?php esc_attr( 'Date', 'feed-them-social' ); ?>" value="<?php echo esc_attr( get_option( 'fts-custom-date' ) ); ?>"/>
												<input name="fts-custom-time" style="max-width:75px;" class="fts-color-settings-admin-input" id="fts-custom-time" placeholder="<?php esc_attr( 'Time', 'feed-them-social' ); ?>" value="<?php echo esc_attr( get_option( 'fts-custom-time' ) ); ?>"/>
											</p>
											<div><?php echo esc_html( 'This will override the date and time format above.', 'feed-them-social' ); ?>
												<br/><a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php echo esc_html( 'Options for custom date and time formatting.', 'feed-them-social' ); ?></a>
											</div>
										</div>
										<div style="float:left; max-width:330px; margin-right: 30px;">
											<h2><?php echo esc_html( 'TimeZone', 'feed-them-social' ); ?></h2>
											<fieldset>
												<select id="fts-timezone" name="fts-timezone">
													<option value="Pacific/Midway" <?php echo 'Pacific/Midway' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-11:00) Midway Island, Samoa', 'feed-them-social' ); ?>
													</option>
													<option value="America/Adak" <?php echo 'America/Adak' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-10:00) Hawaii-Aleutian', 'feed-them-social' ); ?>
													</option>
													<option value="Etc/GMT+10" <?php echo 'Etc/GMT+10' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-10:00) Hawaii', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Marquesas" <?php echo 'Pacific/Marquesas' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-09:30) Marquesas Islands', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Gambier" <?php echo 'Pacific/Gambier' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-09:00) Gambier Islands', 'feed-them-social' ); ?>
													</option>
													<option value="America/Anchorage" <?php echo 'America/Anchorage' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-09:00) Alaska', 'feed-them-social' ); ?>
													</option>
													<option value="America/Anchorage" <?php echo 'America/Anchorage' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-09:00) Gambier Islands', 'feed-them-social' ); ?>
													</option>
													<option value="America/Ensenada" <?php echo 'America/Ensenada' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-08:00) Tijuana, Baja California', 'feed-them-social' ); ?>
													</option>
													<option value="Etc/GMT+8" <?php echo 'Etc/GMT+8' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-08:00) Pitcairn Islands', 'feed-them-social' ); ?>
													</option>
													<option value="America/Los_Angeles" <?php echo 'America/Los_Angeles' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-08:00) Pacific Time (US & Canada)', 'feed-them-social' ); ?>
													</option>
													<option value="America/Denver" <?php echo 'America/Denver' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-07:00) Mountain Time (US & Canada)', 'feed-them-social' ); ?>
													</option>
													<option value="America/Chihuahua" <?php echo 'America/Chihuahua' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'feed-them-social' ); ?>
													</option>
													<option value="America/Dawson_Creek" <?php echo 'America/Dawson_Creek' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-07:00) Arizona', 'feed-them-social' ); ?>
													</option>
													<option value="America/Belize" <?php echo 'America/Belize' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-06:00) Saskatchewan, Central America', 'feed-them-social' ); ?>
													</option>
													<option value="America/Cancun" <?php echo 'America/Cancun' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'feed-them-social' ); ?>
													</option>
													<option value="Chile/EasterIsland" <?php echo 'Chile/EasterIsland' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-06:00) Easter Island', 'feed-them-social' ); ?>
													</option>
													<option value="America/Chicago" <?php echo 'America/Chicago' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-06:00) Central Time (US & Canada)', 'feed-them-social' ); ?>
													</option>
													<option value="America/New_York" <?php echo 'America/New_York' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-05:00) Eastern Time (US & Canada)', 'feed-them-social' ); ?>
													</option>
													<option value="America/Havana" <?php echo 'America/Havana' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-05:00) Cuba', 'feed-them-social' ); ?>
													</option>
													<option value="America/Bogota" <?php echo 'America/Bogota' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-05:00) Bogota, Lima, Quito, Rio Branco', 'feed-them-social' ); ?>
													</option>
													<option value="America/Caracas" <?php echo 'America/Caracas' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:30) Caracas', 'feed-them-social' ); ?>
													</option>
													<option value="America/Santiago" <?php echo 'America/Santiago' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) Santiago', 'feed-them-social' ); ?>
													</option>
													<option value="America/La_Paz" <?php echo 'America/La_Paz' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) La Paz', 'feed-them-social' ); ?>
													</option>
													<option value="Atlantic/Stanley" <?php echo 'Atlantic/Stanley' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) Faukland Islands', 'feed-them-social' ); ?>
													</option>
													<option value="America/Campo_Grande" <?php echo 'America/Campo_Grande' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) Brazil', 'feed-them-social' ); ?>
													</option>
													<option value="America/Goose_Bay" <?php echo 'America/Goose_Bay' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) Atlantic Time (Goose Bay)', 'feed-them-social' ); ?>
													</option>
													<option value="America/Glace_Bay" <?php echo 'America/Glace_Bay' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-04:00) Atlantic Time (Canada)', 'feed-them-social' ); ?>
													</option>
													<option value="America/St_Johns" <?php echo 'America/St_Johns' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:30) Newfoundland', 'feed-them-social' ); ?>
													</option>
													<option value="America/Araguaina" <?php echo 'America/Araguaina' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) UTC-3', 'feed-them-social' ); ?>
													</option>
													<option value="America/Montevideo" <?php echo 'America/Montevideo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) Montevideo', 'feed-them-social' ); ?>
													</option>
													<option value="America/Miquelon" <?php echo 'America/Miquelon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) Miquelon, St. Pierre', 'feed-them-social' ); ?>
													</option>
													<option value="America/Godthab" <?php echo 'America/Godthab' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) Greenland', 'feed-them-social' ); ?>
													</option>
													<option value="America/Argentina/Buenos_Aires" <?php echo 'America/Argentina/Buenos_Aires' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) Buenos Aires', 'feed-them-social' ); ?>
													</option>
													<option value="America/Sao_Paulo" <?php echo 'America/Sao_Paulo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-03:00) Brasilia', 'feed-them-social' ); ?>
													</option>
													<option value="America/Noronha" <?php echo 'America/Noronha' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-02:00) Mid-Atlantic', 'feed-them-social' ); ?>
													</option>
													<option value="Atlantic/Cape_Verde" <?php echo 'Atlantic/Cape_Verde' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-01:00) Cape Verde Is.', 'feed-them-social' ); ?>
													</option>
													<option value="Atlantic/Azores" <?php echo 'Atlantic/Azores' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT-01:00) Azores', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Belfast" <?php echo 'Europe/Belfast' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT) Greenwich Mean Time : Belfast', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Dublin" <?php echo 'Europe/Dublin' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT) Greenwich Mean Time : Dublin', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Lisbon" <?php echo 'Europe/Lisbon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT) Greenwich Mean Time : Lisbon', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/London" <?php echo 'Europe/London' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT) Greenwich Mean Time : London', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Abidjan" <?php echo 'Africa/Abidjan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT) Monrovia, Reykjavik', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Amsterdam" <?php echo 'Europe/Amsterdam' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Belgrade" <?php echo 'Europe/Belgrade' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Algiers" <?php echo 'Africa/Algiers' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+01:00) West Central Africa', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Windhoek" <?php echo 'Africa/Windhoek' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+01:00) Windhoek', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Beirut" <?php echo 'Asia/Beirut' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Beirut', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Cairo" <?php echo 'Africa/Cairo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Cairo', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Gaza" <?php echo 'Asia/Gaza' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Gaza', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Blantyre" <?php echo 'Africa/Blantyre' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Harare, Pretoria', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Jerusalem" <?php echo 'Asia/Jerusalem' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Jerusalem', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Minsk" <?php echo 'Europe/Minsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Minsk', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Damascus" <?php echo 'Asia/Damascus' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+02:00) Syria', 'feed-them-social' ); ?>
													</option>
													<option value="Europe/Moscow" <?php echo 'Europe/Moscow' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+03:00) Moscow, St. Petersburg, Volgograd', 'feed-them-social' ); ?>
													</option>
													<option value="Africa/Addis_Ababa" <?php echo 'Africa/Addis_Ababa' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+03:00) Nairobi', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Tehran" <?php echo 'Asia/Tehran' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+03:30) Tehran', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Dubai" <?php echo 'Asia/Dubai' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+04:00) Abu Dhabi, Muscat', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Yerevan" <?php echo 'Asia/Yerevan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+04:00) Yerevan', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Kabul" <?php echo 'Asia/Kabul' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+04:30) Kabul', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Yekaterinburg" <?php echo 'Asia/Yekaterinburg' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+05:00) Ekaterinburg', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Tashkent" <?php echo 'Asia/Tashkent' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+05:00) Tashkent', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Kolkata" <?php echo 'Asia/Kolkata' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Katmandu" <?php echo 'Asia/Katmandu' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+05:45) Kathmandu', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Dhaka" <?php echo 'Asia/Dhaka' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+06:00) Astana, Dhaka', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Novosibirsk" <?php echo 'Asia/Novosibirsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+06:00) Novosibirsk', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Rangoon" <?php echo 'Asia/Rangoon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+06:30) Yangon (Rangoon)', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Bangkok" <?php echo 'Asia/Bangkok' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+07:00) Bangkok, Hanoi, Jakarta', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Krasnoyarsk" <?php echo 'Asia/Krasnoyarsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+07:00) Krasnoyarsk', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Hong_Kong" <?php echo 'Asia/Hong_Kong' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Irkutsk" <?php echo 'Asia/Irkutsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+08:00) Irkutsk, Ulaan Bataar', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Perth" <?php echo 'Australia/Perth' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+08:00) Perth', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Eucla" <?php echo 'Australia/Eucla' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+08:45) Eucla', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Tokyo" <?php echo 'Asia/Tokyo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+09:00) Osaka, Sapporo, Tokyo', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Seoul" <?php echo 'Asia/Seoul' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+09:00) Seoul', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Yakutsk" <?php echo 'Asia/Yakutsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+09:00) Yakutsk', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Adelaide" <?php echo 'Australia/Adelaide' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+09:30) Adelaide', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Darwin" <?php echo 'Australia/Darwin' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+09:30) Darwin', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Brisbane" <?php echo 'Australia/Brisbane' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+10:00) Brisbane', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Hobart" <?php echo 'Australia/Hobart' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+10:00) Sydney', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Vladivostok" <?php echo 'Asia/Vladivostok' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+10:00) Vladivostok', 'feed-them-social' ); ?>
													</option>
													<option value="Australia/Lord_Howe" <?php echo 'Australia/Lord_Howe' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+10:30) Lord Howe Island', 'feed-them-social' ); ?>
													</option>
													<option value="Etc/GMT-11" <?php echo 'Etc/GMT-11' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+11:00) Solomon Is., New Caledonia', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Magadan" <?php echo 'Asia/Magadan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+11:00) Magadan', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Norfolk" <?php echo 'Pacific/Norfolk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+11:30) Norfolk Island', 'feed-them-social' ); ?>
													</option>
													<option value="Asia/Anadyr" <?php echo 'Asia/Anadyr' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+12:00) Anadyr, Kamchatka', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Auckland" <?php echo 'Pacific/Auckland' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+12:00) Auckland, Wellington', 'feed-them-social' ); ?>
													</option>
													<option value="Etc/GMT-12" <?php echo 'Etc/GMT-12' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Chatham" <?php echo 'Pacific/Chatham' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+12:45) Chatham Islands', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Tongatapu" <?php echo 'Pacific/Tongatapu' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+13:00) Nuku\'alofa', 'feed-them-social' ); ?>
													</option>
													<option value="Pacific/Kiritimati" <?php echo 'Pacific/Kiritimati' === $fts_timezone ? 'selected="selected"' : ''; ?>>
														<?php echo esc_html( '(GMT+14:00) Kiritimati', 'feed-them-social' ); ?>
													</option>
												</select>
											</fieldset>
										</div>
										<div class="clear"></div>

										<br/>
										<h2><?php echo esc_html( 'Custom CSS Option', 'feed-them-social' ); ?></h2>
										<p>
											<input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox" id="fts-color-options-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'fts-color-options-settings-custom-css' ) ); ?>/>
											<?php
											if ( '1' === get_option( 'fts-color-options-settings-custom-css' ) ) {
												?>
												<strong><?php echo esc_html( 'Checked:', 'feed-them-social' ); ?></strong> <?php echo esc_html( 'Custom CSS option is being used now.', 'feed-them-social' ); ?>
												<?php
											} else {
												?>
												<strong><?php echo esc_html( 'Not Checked:', 'feed-them-social' ); ?></strong> <?php echo esc_html( 'You are using the default CSS.', 'feed-them-social' ); ?>
												<?php
											}
											?>
										</p>
										<label class="toggle-custom-textarea-show"><span><?php echo esc_html( 'Show', 'feed-them-social' ); ?></span><span class="toggle-custom-textarea-hide"><?php echo esc_html( 'Hide', 'feed-them-social' ); ?></span> <?php echo esc_html( 'custom CSS', 'feed-them-social' ); ?>
										</label>
										<div class="clear"></div>
										<div class="fts-custom-css-text"><?php echo esc_html( 'Thanks for using our plugin :) Add your custom CSS additions or overrides below.', 'feed-them-social' ); ?></div>
										<textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo esc_textarea( get_option( 'fts-color-options-main-wrapper-css-input' ) ); ?></textarea>
									</div><!--/feed-them-custom-css-->

									<div class="feed-them-custom-logo-css">
										<h2><?php echo esc_html( 'Disable Share Option', 'feed-them-social' ); ?></h2>
										<p>
											<input name="fts_disable_share_button" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_disable_share_button" value="1" <?php echo checked( '1', get_option( 'fts_disable_share_button' ) ); ?>/> <?php echo esc_html( 'Check this if you want to disable the Share Icon on all feeds', 'feed-them-social' ); ?>
										</p>
										<br/>
										<div class="feed-them-social-admin-input-wrap">
											<div class="feed-them-social-admin-input-label fts-social-icons-color-label">
												<?php echo esc_html( 'Social Icons Color', 'feed-them-social' ); ?>
											</div>
											<input type="text" name="fts_social_icons_color" class="feed-them-social-admin-input fts-social-icons-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fts_social_icons_color' ) ); ?>"/>
											<div class="clear"></div>
										</div>
										<!--/fts-facebook-feed-styles-input-wrap-->

										<div class="feed-them-social-admin-input-wrap">
											<div class="feed-them-social-admin-input-label fts-social-icons-color-label">
												<?php echo esc_html( 'Social Icons Hover Color', 'feed-them-social' ); ?>
											</div>
											<input type="text" name="fts_social_icons_hover_color" class="feed-them-social-admin-input fts-social-icons-hover-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fts_social_icons_hover_color' ) ); ?>"/>
											<div class="clear"></div>
										</div>
										<!--/fts-facebook-feed-styles-input-wrap-->

										<div class="feed-them-social-admin-input-wrap">
											<div class="feed-them-social-admin-input-label fts-social-icons-color-back-label">
												<?php echo esc_html( 'Icons wrap background Color', 'feed-them-social' ); ?>
											</div>
											<input type="text" name="fts_social_icons_back_color" class="feed-them-social-admin-input fts-social-icons-back-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fts_social_icons_back_color' ) ); ?>"/>
											<div class="clear"></div>
										</div>
										<!--/fts-facebook-feed-styles-input-wrap-->

										<br/>
										<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>
											<h2><?php echo esc_html( 'Disable Magnific Popup CSS', 'feed-them-social' ); ?></h2>
											<p>
												<input name="fts_fix_magnific" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_fix_magnific" value="1" <?php echo checked( '1', get_option( 'fts_fix_magnific' ) ); ?>/> <?php echo esc_html( 'Check this if you are experiencing problems with your theme(s) or other plugin(s) popups.', 'feed-them-social' ); ?>
											</p>
											<br/>
										<?php } ?>

										<h2><?php echo esc_html( 'Fix Twitter Time', 'feed-them-social' ); ?></h2>
										<p>
											<input name="fts_twitter_time_offset" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_twitter_time_offset" value="1" <?php echo checked( '1', get_option( 'fts_twitter_time_offset' ) ); ?>/> <?php echo esc_html( 'Check this if the Twitter time is still off by 3 hours after setting the TimeZone above.', 'feed-them-social' ); ?>
										</p>
										<br/>

										<h2><?php echo esc_html( 'Fix Internal Server Error', 'feed-them-social' ); ?></h2>
										<p>
											<input name="fts_curl_option" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_curl_option" value="1" <?php echo checked( '1', get_option( 'fts_curl_option' ) ); ?>/> <?php echo esc_html( 'Check this option if you are getting a 500 Internal Server Error when trying to load a page with our feed on it.', 'feed-them-social' ); ?>
										</p>
										<br/>

										<h2><?php echo esc_html( 'Powered by Text', 'feed-them-social' ); ?></h2>
										<p>
											<input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked( '1', get_option( 'fts-powered-text-options-settings' ) ); ?>/>
											<?php
											if ( '1' === get_option( 'fts-powered-text-options-settings' ) ) {
												?>
												<strong><?php echo esc_html( 'Checked:', 'feed-them-social' ); ?></strong> <?php echo esc_html( 'You are not showing the Powered by Logo.', 'feed-them-social' ); ?>
												<?php
											} else {
												?>
												<strong><?php echo esc_html( 'Not Checked:', 'feed-them-social' ); ?></strong><?php echo esc_html( 'The Powered by text will appear in the site. Awesome! Thanks so much for sharing.', 'feed-them-social' ); ?>
												<?php
											}
											?>
										</p>
										<br/>
										<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php echo esc_html( 'Save All Changes', 'feed-them-social' ); ?>"/>
										<div class="clear"></div>
									</div><!--/feed-them-custom-logo-css-->
								</form>
						</div><!--/font-content-->

						</section>
					</div>
				</div>

			</div><!--/feed-them-social-admin-wrap-->

			<script>
				jQuery(document).ready(function ($) {

					//create hash tag in url for fts-tabs
					jQuery('.feed-them-social-admin-wrap #fts-tabs').on('click', "label.fts-tabbed", function () {
						var myURL = document.location;
						document.location = myURL + "&tab=" + jQuery(this).attr('id');

					})

				});
			</script>

			<h1 class="plugin-author-note"><?php echo esc_html( 'Plugin Authors Note', 'feed-them-social' ); ?></h1>
			<div class="fts-plugin-reviews">
				<div class="fts-plugin-reviews-rate"><?php echo esc_html( ' Feed Them Social was created by 2 Brothers, Spencer and Justin Labadie. That’s it, 2 people! We spend all our time creating and supporting this plugin. Show us some love if you like our plugin and leave a quick review for us, it will make our day!', 'feed-them-social' ); ?>
					<a href="https://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank"><?php echo esc_html( 'Leave us a Review', 'feed-them-social' ); ?>
						★★★★★</a>
				</div>
				<div class="fts-plugin-reviews-support">
					<?php
					// Free Support Message!
					echo sprintf(
						esc_html( 'If you\'re using the Free plugin and are having troubles getting setup please contact us on the %1$sFree WordPress Support Forum%2$s. We will respond within 24hrs during weekdays.', 'feed-them-social' ),
						'<a href="' . esc_url( 'https://wordpress.org/support/plugin/feed-them-social' ) . '" target="_blank">',
						'</a>'
					);
					// Paid Support Message!
					echo sprintf(
						esc_html( 'If you have a paid extensions from us please use our %1$sPaid Extension Support Ticket System%2$s', 'feed-them-social' ),
						'<a href="' . esc_url( 'https://www.slickremix.com/my-account/#tab-support' ) . '" target="_blank">',
						'</a>'
					);
					?>

					<div class="fts-text-align-center">
						<a class="feed-them-social-admin-slick-logo" href="https://www.slickremix.com" target="_blank"></a>
					</div>
				</div>
			</div>

			<script>

				jQuery(document).ready(function () {

					// Master feed selector
					jQuery('#shortcode-form-selector').change(function () {
						jQuery('.shortcode-generator-form').hide();
						jQuery('.' + jQuery(this).val()).fadeIn('fast');

						<?php if ( get_option( 'youtube_custom_access_token' ) && get_option( 'youtube_custom_access_token' ) !== '' && get_option( 'youtube_custom_access_token' ) !== '' ) { ?>
						if (jQuery('select#shortcode-form-selector').val() == 'youtube-shortcode-form') {
							jQuery(".shortcode-generator-form.youtube-shortcode-form .fts-required-token-message").hide();
						}<?php } ?>

						if (jQuery('select#shortcode-form-selector').val() == 'fts-fb-page-shortcode-form') {
							jQuery("#facebook-messages-selector").change();
							jQuery("html, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media").show();
						}

						if (jQuery("select#shortcode-form-selector").val() == "vine-shortcode-form") {
							jQuery("form#feed-selector-form").append('<div class="feed-them-social-admin-input-wrap fts-premium-options-message" id="bye-vine"><a class="not-active-title" href="https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws" target="_blank">Vine Depreciated</a><?php esc_js( 'A notice to all users of Feed Them Social that use the Vine feed in our plugin... It appears they will be closing the doors at some point soon. No specific date, but well keep you posted before it gets fully phased out. <a href="https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws">https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws</a><br><br>You can see the shortcode options and shortcode examples here, we will no longer be creating a shortcode generator for this feed. <a href="https://www.slickremix.com/docs/shortcode-options-table/#vine">https://www.slickremix.com/docs/shortcode-options-table/#vine</a> ', 'feed-them-social' ); ?></div>');
							jQuery("#bye-vine").show();
						}
						else {
							jQuery("form#feed-selector-form").remove("#bye-vine");
						}

						//Combined Feed
						<?php if ( ! is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) { ?>
						if (jQuery("select#shortcode-form-selector").val() == "combine-steams-shortcode-form") {
							jQuery('.combine-steams-shortcode-form, .fts-required-more-posts').hide();
							jQuery('#not_active_main_select, .fts-required-more-posts').show();
						}
						<?php } ?>

						jQuery('select#combine-steams-selector').val('all');
						//Remove Controller Class so everything reappears for Facebook Feed
						if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
							jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
							jQuery('.fts-required-more-posts').hide();
						}
						else {
							jQuery('.fts-required-more-posts').show();
						}
						jQuery('select#facebook-messages-selector option[value="events"]').show();

					});

					jQuery('select#fb_hide_like_box_button').bind('change', function (e) {
						if (jQuery('select#fb_hide_like_box_button').val() == 'no') {
							jQuery('.like-box-wrap').show();
						}
						else {
							jQuery('.like-box-wrap').hide();
						}
					});

					jQuery('#facebook_show_video_button').change(function () {
						jQuery('.fb-video-play-btn-options-content').toggle();
					});

					//Combine Feed Type Selector
					jQuery('select#combine-steams-selector').bind('change', function (e) {
						if (jQuery('select#combine-steams-selector').val() == 'multiple_facebook') {
							jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').show();
							jQuery('.combine_streams_options_wrap, .fts-required-more-posts').hide();
							jQuery('.fts-facebook_page-shortcode-form').addClass('multiple_facebook');

							jQuery('.multiple_facebook select#facebook-messages-selector option[value="events"]').hide();
						}
						else {

							jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').hide();
							jQuery('.combine_streams_options_wrap, .fts-required-more-posts').show();

							//Remove Controller Class so everything reappears for Facebook Feed
							if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
								jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
							}
						}
					});

					// change the feed type 'how to' message when a feed type is selected
					jQuery('#facebook-messages-selector').change(function () {
						jQuery('.facebook-message-generator').hide();
						jQuery('.' + jQuery(this).val()).fadeIn('fast');
						// if the facebook type select is changed we hide the shortcode code so not to confuse people
						jQuery('.final-shortcode-textarea').hide();
						// only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
						var facebooktype = jQuery("select#facebook-messages-selector").val();


						if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'album_videos') {
							jQuery('.fts-super-facebook-options-wrap,.align-images-wrap').show();
							jQuery('.fixed_height_option,.main-grid-options-wrap').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').hide();
						}
						else {
							jQuery('.fts-super-facebook-options-wrap,.align-images-wrap ').hide();
							jQuery('.fixed_height_option,.main-grid-options-wrap').show();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').show();
						}

						if (facebooktype == 'page' || facebooktype == 'event' || facebooktype == 'group') {
							jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").show();
						}
						else {
							jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").hide();
						}

						<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>

						// This is to show all option when prem active if you selected the Facebook Page reviews if not active. Otherwise all other fb-options-wraps are hidden when selecting another fb feed from settings page drop down.
						jQuery('.fb-options-wrap').show();
						jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

						if (facebooktype == 'album_videos') {
							jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').hide();
							jQuery('.video, .fb-video-play-btn-options-wrap').show();
							jQuery(".feed-them-social-admin-input-label:contains('# of Posts')").html("<?php echo esc_js( '# of Videos', 'feed-them-social' ); ?>");
						}
						else {
							jQuery('.video, .fb-video-play-btn-options-wrap').hide();
							jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').show();
							jQuery(".feed-them-social-admin-input-label:contains('# of Videos')").html("<?php echo esc_js( '# of Posts', 'feed-them-social' ); ?>");
						}
							<?php
} else {
	?>

						jQuery('.video, .fb-video-play-btn-options-wrap').hide();
						jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

						<?php } ?>

						if (facebooktype == 'page') {
							jQuery('.inst-text-facebook-page').show();
						}
						else {
							jQuery('.inst-text-facebook-page').hide();
						}

						if (facebooktype == 'events') {
							jQuery('.inst-text-facebook-event-list').show();
							jQuery('.facebook-loadmore-wrap').hide();

						}
						else {
							jQuery('.inst-text-facebook-event-list').hide();
							jQuery('.facebook-loadmore-wrap').show();
						}

						<?php if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
						if (facebooktype == 'reviews') {
							jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').show();
							jQuery('.align-images-wrap,.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').hide();
						} else {
							jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').hide();
							jQuery('.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').show();
						}
						<?php } ?>

						// only show the post type visible if the facebook page feed type is selected
						jQuery('.facebook-post-type-visible').hide();
						if (facebooktype == 'page') {
							jQuery('.facebook-post-type-visible').show();
						}
						var fb_feed_type_option = jQuery("select#facebook-messages-selector").val();
						if (fb_feed_type_option == 'album_photos') {
							jQuery('.fb_album_photos_id').show();
						}
						else {
							jQuery('.fb_album_photos_id').hide();
						}
					});
					//Instagram Profile wrap
					jQuery('select#instagram-profile-wrap').bind('change', function (e) {
						if (jQuery('#instagram-profile-wrap').val() == 'yes') {
							jQuery('.instagram-profile-options-wrap').show();
						}
						else {
							jQuery('.instagram-profile-options-wrap').hide();
						}
					});
					// Instagram Super Gallery option
					jQuery('#instagram-custom-gallery').bind('change', function (e) {
						if (jQuery('#instagram-custom-gallery').val() == 'no') {
							jQuery('.fts-super-instagram-options-wrap').hide();
						}
						else {
							jQuery('.fts-super-instagram-options-wrap').show();
						}
					});


                    var fts_notice_message = '<div class="feed-them-social-admin-input-wrap fts-instagram-hashtag-location-options-message fts-premium-options-message" id="not_active_main_select" style="display: block;"><a class="not-active-title" href="https://www.slickremix.com/instagram-hashtag-and-location-options/" target="_blank"><h3>Hashtag and Location Depreciation Notice</h3></a>The hashtag and location options are being retired for the moment. You can <a target="_blank" href="https://www.slickremix.com/instagram-hashtag-and-location-options/">read more about it here</a>. It appears there is a way to do hashtag calls through the Facebook/Instagram api, so we are looking into making this happen. It is limited in terms of API calls so this may not work for many people. Unfortunately these are the new privacy guide lines set forth by facebook since they own Instagram.<br/><br/>Your Existing Hashtag or Location feeds will work until December 11th, 2018. We will be making an update on the 10th to remove the call so any existing hashtag or location feeds will not display</div>';

                    jQuery('#instagram-messages-selector').bind('change', function (e) {
						if (jQuery('#instagram-messages-selector').val() == 'hashtag') {
							jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.instagram-location-option-text,.main-instagram-profile-options-wrap").hide();
							jQuery(".instagram-hashtag-option-text").show();
                               jQuery(".instagram_options_wrap").hide();
                               if(!jQuery('div').hasClass('fts-instagram-hashtag-location-options-message')){
                                   jQuery(  ".fts-instagram-shortcode-form").append( fts_notice_message );
                               }
                               else {
                                   jQuery(".fts-instagram-hashtag-location-options-message").show();
                               }

						}
						else if (jQuery('#instagram-messages-selector').val() == 'location') {
							jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.instagram-hashtag-option-text,.main-instagram-profile-options-wrap").hide();
							jQuery(".instagram-location-option-text").show();
                            jQuery(".instagram_options_wrap").hide();
                            if(!jQuery('div').hasClass('fts-instagram-hashtag-location-options-message')){
                                jQuery(  ".fts-instagram-shortcode-form").append( fts_notice_message );
                            }
                            else {
                                jQuery(".fts-instagram-hashtag-location-options-message").show();
                            }
						}
						else {
							jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.main-instagram-profile-options-wrap").show();
							jQuery(".instagram-hashtag-option-text,.instagram-location-option-text").hide();
                            jQuery(".instagram_options_wrap").show();
                            jQuery(".fts-instagram-hashtag-location-options-message").hide();

						}
					});

					jQuery('#combine_instagram_type').bind('change', function (e) {
						if (jQuery('#combine_instagram_type').val() == 'hashtag') {
							jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text,.combine-instagram-location-option-text").hide();
							jQuery(".combine-instagram-hashtag-option-text").show();

                            jQuery(".combine-instagram-hashtag-option-text, .combine-instagram-hashtag-option-text, #combine_instagram_name").hide();

                            if(!jQuery('.combine_instagram_type div').hasClass('fts-instagram-hashtag-location-options-message')){
                                jQuery(  ".combine_instagram_type").append( fts_notice_message );
                            }
                            else {
                                jQuery(".fts-instagram-hashtag-location-options-message").show();
                            }
						}
						else if (jQuery('#combine_instagram_type').val() == 'location') {
							jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text,.combine-instagram-hashtag-option-text").hide();
							jQuery(".combine-instagram-location-option-text").show();

                            jQuery(".combine-instagram-location-option-text, .combine-instagram-location-option-text, #combine_instagram_name").hide();

                            jQuery(".instagram_options_wrap").hide();
                            if(!jQuery('.combine_instagram_type div').hasClass('fts-instagram-hashtag-location-options-message')){
                                jQuery(  ".combine_instagram_type").append( fts_notice_message );
                            }
                            else {
                                jQuery(".fts-instagram-hashtag-location-options-message").show();
                            }
						}
						else {
							jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text").show();
							jQuery(".combine-instagram-hashtag-option-text,.combine-instagram-location-option-text").hide();
                            jQuery("#combine_instagram_name").show();
                            jQuery(".fts-instagram-hashtag-location-options-message").hide();

						}
					});

					/* Instagram */
					function getQueryString(Param) {
						return decodeURI(
							(RegExp('[#|&]' + Param + '=' + '(.+?)(&|$)').exec(location.hash) || [, null])[1]
						);
					}

					if (window.location.hash && getQueryString('feed_type') == 'instagram') {
						jQuery('#feed-selector-form').find('option[value=instagram-shortcode-form]').attr('selected', 'selected');
						jQuery('.shortcode-generator-form.instagram-shortcode-form').show();
						jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_id' ) ); ?>');
						jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_api_token' ) ); ?>');
					}

					jQuery('#shortcode-form-selector, #instagram-messages-selector').bind('change', function (e) {
						if (jQuery('#instagram-messages-selector').val() == 'user') {
							jQuery('#instagram_id, #insta_access_token').val('');
							jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_id' ) ); ?>');
							jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_api_token' ) ); ?>');
						}
						else {
							jQuery('#instagram_id').val('');
						}

					});
					/* FB Pages, Ablums, Photos etc */
					if (window.location.hash && getQueryString('feed_type') == 'facebook') {
						jQuery('#feed-selector-form').find('option[value=fts-fb-page-shortcode-form]').attr('selected', 'selected');
						jQuery('#fts-tab-content1 .fts-fb-page-shortcode-form').show();
						jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
						jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token' ) ); ?>');
					}

					jQuery('#shortcode-form-selector, #facebook-messages-selector').bind('change', function (e) {
						if (jQuery('#facebook-messages-selector').val() == 'page' || jQuery('#facebook-messages-selector').val() == 'album_photos' || jQuery('#facebook-messages-selector').val() == 'albums' || jQuery('#facebook-messages-selector').val() == 'album_videos') {
							jQuery('#fb_page_id, #fb_access_token').val('');
							jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
							jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token' ) ); ?>');
						}
						else {

						}

					});

					/* FB Pages, Ablums, Photos etc */
					if (window.location.hash && getQueryString('feed_type') == 'facebook_reviews') {
						jQuery('#feed-selector-form').find('option[value=fts-fb-page-shortcode-form]').attr('selected', 'selected');
						jQuery('#fts-tab-content1 .fts-fb-page-shortcode-form').show();

						jQuery('#facebook-messages-selector').find('option[value=reviews]').attr('selected', 'selected');
						jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').show();
						jQuery('.align-images-wrap,.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts, .inst-text-facebook-page').hide();

						jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>');
						jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>');
					}

					jQuery('#shortcode-form-selector, #facebook-messages-selector').bind('change', function (e) {
						if (jQuery('#facebook-messages-selector').val() == 'reviews') {
							jQuery('#fb_page_id, #fb_access_token').val('');
							jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>');
							jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>');
						}
						else {

						}

					});

					jQuery('#combine_facebook').bind('change', function (e) {
						jQuery('#combine_facebook_name').val('');
						jQuery('#combine_facebook_name').val(jQuery('#combine_facebook_name').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
					});

					jQuery('#combine_instagram, #combine_instagram_type').bind('change', function (e) {
						jQuery('#combine_instagram_name').val('');
						if (jQuery('#combine_instagram_type').val() == 'user') {
							jQuery('#combine_instagram_name').val(jQuery('#combine_instagram_name').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_id' ) ); ?>');
						}
						else {
							jQuery('#combine_instagram_name').val('');
						}

					});

					<?php if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>
					jQuery('#instagram-messages-selector').bind('change', function (e) {
						if (jQuery('#instagram-messages-selector').val() == 'location') {
							jQuery("#instagram_id").hide();
							jQuery('<div class="feed-them-social-admin-input-default fts-custom-premium-required">Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> to edit.</div>').insertAfter('.feed-them-social-admin-input-label.instagram-location-option-text');
							jQuery(".feed-them-social-admin-submit-btn").hide();
						}
						else {
							jQuery("#instagram_id").show();
							jQuery(".fts-custom-premium-required").hide();
							jQuery(".feed-them-social-admin-submit-btn").show();
						}

					});
					<?php } ?>

					jQuery('#twitter-messages-selector').bind('change', function (e) {
						if (jQuery('#twitter-messages-selector').val() == 'hashtag') {
							jQuery(".hashtag-option-small-text,.twitter-hashtag-etc-wrap").show();
							jQuery(".hashtag-option-not-required, .must-copy-twitter-name").hide();
						}
						else {
							jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
							jQuery(".twitter-hashtag-etc-wrap,.hashtag-option-small-text").hide();
						}
					});

					jQuery('#combine-twitter-messages-selector').bind('change', function (e) {
						if (jQuery('#combine-twitter-messages-selector').val() == 'hashtag') {
							jQuery(".combine-twitter-hashtag-etc-wrap").show();
							jQuery(".combine_twitter_name").hide();
						}
						else {
							jQuery(".combine_twitter_name").show();
							jQuery(".combine-twitter-hashtag-etc-wrap").hide();
						}
					});

					//Twitter Grid option
					jQuery('#twitter-grid-option').bind('change', function (e) {
						if (jQuery('#twitter-grid-option').val() == 'yes') {
							jQuery('.fts-twitter-grid-options-wrap').show();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
						}
						else {
							jQuery('.fts-twitter-grid-options-wrap').hide();
						}
					});

					//Twitter show load more options
					jQuery('#twitter_load_more_option').bind('change', function (e) {
						if (jQuery('#twitter_load_more_option').val() == 'yes') {
							jQuery('.fts-twitter-load-more-options-wrap').show();
							jQuery('.fts-twitter-load-more-options2-wrap').show();
						}

						else {
							jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
						}
					});

					//youtube show load more options
					jQuery('#youtube_load_more_option').bind('change', function (e) {
						if (jQuery('#youtube_load_more_option').val() == 'yes') {
							jQuery('.fts-youtube-load-more-options-wrap').show();
							jQuery('.fts-youtube-load-more-options2-wrap').show();
						}

						else {
							jQuery('.fts-youtube-load-more-options-wrap, .fts-youtube-load-more-options2-wrap').hide();
						}
					});

					// facebook show grid options
					jQuery('#fb-grid-option').bind('change', function (e) {
						if (jQuery('#fb-grid-option').val() == 'yes') {
							jQuery('.fts-facebook-grid-options-wrap').show();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
						}
						else {
							jQuery('.fts-facebook-grid-options-wrap').hide();
						}
					});

					// facebook Super Gallery option
					jQuery('#facebook-custom-gallery').bind('change', function (e) {
						if (jQuery('#facebook-custom-gallery').val() == 'yes') {
							jQuery('.fts-super-facebook-options-wrap').show();
						}
						else {
							jQuery('.fts-super-facebook-options-wrap').hide();
						}
					});

					//Facebook Display Popup option
					jQuery('#facebook_popup').bind('change', function (e) {
						if (jQuery('#facebook_popup').val() == 'yes') {
							jQuery('.display-comments-wrap').show();
						}
						else {
							jQuery('.display-comments-wrap').hide();
						}
					});

					// facebook show load more options
					jQuery('#fb_load_more_option').bind('change', function (e) {
						if (jQuery('#fb_load_more_option').val() == 'yes') {

							if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
								jQuery('.fts-facebook-load-more-options-wrap').show();
							}
							jQuery('.fts-facebook-load-more-options2-wrap').show();
						}

						else {
							jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
						}
					});
					// Instagram show load more options
					jQuery('#instagram_load_more_option').bind('change', function (e) {
						if (jQuery('#instagram_load_more_option').val() == 'yes') {
							jQuery('.fts-instagram-load-more-options-wrap').show();
						}
						else {
							jQuery('.fts-instagram-load-more-options-wrap').hide();
						}
					});


					//Combine Grid Options
					jQuery('#combine_grid_option').bind('change', function (e) {
						if (jQuery('#combine_grid_option').val() == 'yes') {
							jQuery('.combine-grid-options-wrap ').show();
						}
						else {
							jQuery('.combine-grid-options-wrap ').hide();
						}
					});

					//Combine Facebook
					jQuery('select#combine_facebook').bind('change', function (e) {
						if (jQuery('select#combine_facebook').val() == 'yes') {
							jQuery('.combine-facebook-wrap').show();
						}
						else {
							jQuery('.combine-facebook-wrap').hide();
						}
					});
					//Combine Twitter
					jQuery('#combine_twitter').bind('change', function (e) {
						if (jQuery('#combine_twitter').val() == 'yes') {
							jQuery('.combine-twitter-wrap').show();
						}
						else {
							jQuery('.combine-twitter-wrap').hide();
						}
					});
					//Combine Instagram
					jQuery('#combine_instagram').bind('change', function (e) {
						if (jQuery('#combine_instagram').val() == 'yes') {
							jQuery('.combine-instagram-wrap').show();
						}
						else {
							jQuery('.combine-instagram-wrap').hide();
						}
					});
					//Combine Pinterest
					jQuery('#combine_pinterest').bind('change', function (e) {
						if (jQuery('#combine_pinterest').val() == 'yes') {
							jQuery('.combine-pinterest-wrap').show();
						}
						else {
							jQuery('.combine-pinterest-wrap').hide();
						}
					});
					//Combine Pinterest Type Options
					jQuery('#combine_pinterest_type').bind('change', function (e) {
						if (jQuery('#combine_pinterest_type').val() == 'pins_from_user') {
							jQuery('.combine_board_id').hide();
						}
						if (jQuery('#combine_pinterest_type').val() == 'single_board_pins') {
							jQuery('.combine_board_id').show();
						}
					});
					//Combine Youtube
					jQuery('#combine_youtube').bind('change', function (e) {
						if (jQuery('#combine_youtube').val() == 'yes') {
							jQuery('.combine-youtube-wrap').show();
						}
						else {
							jQuery('.combine-youtube-wrap').hide();
						}
					});
					//Youtube Options
					jQuery('select#combine_youtube_type').bind('change', function (e) {
						if (jQuery('#combine_youtube_type').val() == 'channelID') {
							jQuery('.combine_youtube_name, .combine_playlist_id').hide();
							jQuery('.combine_channel_id').show();
						}
						else if (jQuery('#combine_youtube_type').val() == 'userPlaylist') {
							jQuery('.combine_channel_id').hide();
							jQuery('.combine_playlist_id, .combine_youtube_name').show();
						}
						else if (jQuery('#combine_youtube_type').val() == 'playlistID') {
							jQuery('.combine_youtube_name').hide();
							jQuery('.combine_playlist_id, .combine_channel_id').show();
						}
						else {
							jQuery('.combine_youtube_name').show();
							jQuery('.combine_playlist_id, .combine_channel_id').hide();
						}
					});


					// Pinterest options
					// hide this div till needed for free version
					jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Pins', 'feed-them-social' ); ?>')").parent('div').hide();
					jQuery('#pinterest-messages-selector').bind('change', function (e) {
						if (jQuery('#pinterest-messages-selector').val() == 'boards_list') {
							jQuery('.number-of-boards, .pinterest-name-text').show();
							jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Boards', 'feed-them-social' ); ?>')").parent('div').show();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Pins', 'feed-them-social' ); ?>')").parent('div').hide();
						}
					});
					// Pinterest options
					jQuery('#pinterest-messages-selector').bind('change', function (e) {
						if (jQuery('#pinterest-messages-selector').val() == 'single_board_pins') {
							jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').show();
							jQuery('.number-of-boards, .pinterest-name-text').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Boards', 'feed-them-social' ); ?>')").parent('div').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Pins', 'feed-them-social' ); ?>')").parent('div').show();
						}
					});
					// Pinterest options
					jQuery('#pinterest-messages-selector').bind('change', function (e) {
						if (jQuery('#pinterest-messages-selector').val() == 'pins_from_user') {
							jQuery('.show-pins-amount, .pinterest-name-text').show();
							jQuery('.number-of-boards, .board-name, .pinterest-board-and-name-text').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Boards', 'feed-them-social' ); ?>')").parent('div').hide();
							jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( '# of Pins', 'feed-them-social' ); ?>')").parent('div').show();
						}
					});


				});
				<?php
				$output = '';
				// If shortcode Generator Changes!
				echo 'jQuery("#shortcode-form-selector").change(function () {';
				// Hide Premium Msg Boxes if showing!
				echo 'jQuery("div.fts-premium-options-message").hide();';
				echo '});';
				foreach ( $feed_settings_array as $section => $section_info ) {

					// Premium Message Boxes JS!
					if ( isset( $section_info['premium_msg_boxes'] ) ) {
						echo 'jQuery("#' . esc_js( $section_info['feed_type_select']['select_id'] ) . '").change(function () {';
						echo 'jQuery("form.' . esc_js( $section ) . '_options_wrap").show();';
						foreach ( $section_info['premium_msg_boxes'] as $key => $premium_msg ) {
							if ( ! is_plugin_active( $required_plugins[ $premium_msg['req_plugin'] ]['plugin_url'] ) ) {
								$premium_if_class    = $section_info['shortcode_ifs'][ $key ]['if']['class'];
								$premium_if_operator = $section_info['shortcode_ifs'][ $key ]['if']['operator'];
								$premium_if_value    = $section_info['shortcode_ifs'][ $key ]['if']['value'];
								echo 'if (jQuery("' . esc_js( $premium_if_class ) . '").val() ' . esc_js( $premium_if_operator ) . ' "' . esc_js( $premium_if_value ) . '") { jQuery("form.' . esc_js( $section ) . '_options_wrap").hide(); jQuery("div#not_active_' . esc_js( $key ) . '").show(); }';
								echo 'else{jQuery("div#not_active_' . esc_js( $key ) . '").hide(); }';
							}
						}
						echo '});';
					}

					// Main JS Function for each Feed.
					echo 'function updateTextArea_' . esc_js( $section ) . '() { ' . "\n";

					$final_shortcode_var = array();
					foreach ( $section_info['main_options'] as $option ) {
						$no_attribute = ! isset( $option['short_attr']['no_attribute'] ) || isset( $option['short_attr']['no_attribute'] ) && 'yes' !== $option['short_attr']['no_attribute'] ? false : true;
						if ( false === $no_attribute ) {
							if ( ! empty( $option['short_attr'] ) || ! isset( $option['short_attr']['no_html'] ) ) {
								$option_id         = isset( $option['id'] ) ? $option['id'] : '';
								$input_wrap_class  = isset( $option['input_wrap_class'] ) ? $option['input_wrap_class'] : '';
								$section_attr_key  = isset( $section_info['section_attr_key'] ) ? $section_info['section_attr_key'] : '';
								$attr_name         = isset( $option['short_attr']['attr_name'] ) ? $option['short_attr']['attr_name'] : '';
								$empty_error       = isset( $option['short_attr']['empty_error'] ) ? $option['short_attr']['empty_error'] : '';
								$empty_error_value = isset( $option['short_attr']['empty_error_value'] ) ? $option['short_attr']['empty_error_value'] : '';
								$var_final_check   = isset( $option['short_attr']['var_final_if'] ) && ( 'yes' === $option['short_attr']['var_final_if'] || 'set' === $option['short_attr']['var_final_if'] ) ? '_final' : '';

								$set_operator = isset( $option['short_attr']['set_operator'] ) ? $option['short_attr']['set_operator'] : '';
								$set_equals   = isset( $option['short_attr']['set_equals'] ) ? $option['short_attr']['set_equals'] : '';

								// Is this field Hidden!
								echo 'if (jQuery(\'#' . esc_js( $option_id ) . '\').is(":visible") || jQuery(\'#' . esc_js( $option_id ) . '\').hasClass( "non-visible")){';
								switch ( $option['option_type'] ) {
									case 'input':
										echo 'var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ' = ' . ( empty( $empty_error ) || 'set' !== $empty_error ? '\' ' . esc_js( $attr_name ) . '=\' + ' : '' ) . 'jQuery("input#' . esc_js( $option_id ) . '").val();' . "\n";
										break;
									case 'select':
										echo 'var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ' = \' ' . esc_js( $attr_name ) . '=\' + jQuery("select#' . esc_js( $option_id ) . '").val();' . "\n";
										break;
								}
								// If Field Empty throw error (only if field can't be empty)!
								if ( ! empty( $empty_error ) && 'yes' === $empty_error || ! empty( $empty_error ) && 'set' === $empty_error ) {
									// Show Empty Error and Highlight input!
									if ( 'yes' === $empty_error ) {

										echo isset( $option['short_attr']['empty_error_if'] ) ? 'var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . '_error = jQuery("' . esc_js( $option['short_attr']['empty_error_if']['attribute'] ) . '").val(); if (' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . '_error ' . esc_js( $option['short_attr']['empty_error_if']['operator'] ) . ' "' . esc_js( $option['short_attr']['empty_error_if']['value'] ) . '") {' : '';

										echo 'if (' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ' == " ' . esc_js( $attr_name ) . '=") {
                                    jQuery(".' . esc_js( $input_wrap_class ) . '").addClass(\'fts-empty-error\');
                                    jQuery("input#' . esc_js( $option_id ) . '").focus();
                                    return false;
                                    }
                                    if (' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ' != " ' . esc_js( $attr_name ) . '=") {
                                        jQuery(".' . esc_js( $input_wrap_class ) . '").removeClass(\'fts-empty-error\');
                                    }' . "\n";

										$empty_error_value = ! empty( $empty_error_value ) ? ' ' . $empty_error_value : '';
										echo isset( $option['short_attr']['empty_error_if'] ) ? '}  
                                if (' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ' != " ' . esc_js( $attr_name ) . '=") {
                                    var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name . $var_final_check ) . ' = \' ' . esc_js( $attr_name ) . '=\' + jQuery("input#' . esc_js( $option_id ) . '").val();
                                }
                                else {
                                    var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name . $var_final_check ) . ' = \'' . esc_js( $empty_error_value ) . '\';
                                }
                                ' : '';
									}
									// Don't Show Empty Error but Automatically set value if not set.
									if ( 'set' === $empty_error ) {
										$empty_error_value = ! empty( $empty_error_value ) ? ' ' . $empty_error_value : '';
										echo 'if (' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name ) . ( $set_operator && $set_equals ? esc_js( $set_operator ) . ' \' ' . esc_js( $attr_name ) . '=' . esc_js( $set_equals ) . '\'' : '' ) . ') {
                                        var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name . $var_final_check ) . ' = \' ' . esc_js( $attr_name ) . '=\' + jQuery("' . esc_js( $option['option_type'] ) . '#' . esc_js( $option_id ) . '").val();
                                    }
                                    else {
                                        var ' . ( isset( $section_attr_key ) ? esc_js( $section_attr_key ) : '' ) . esc_js( $attr_name . $var_final_check ) . ' = \'' . esc_js( $empty_error_value ) . '\';
                                    }' . "\n";
									}
								}
								// Is this field Hidden!
								echo '}';
							} else {
								$output .= 'Please add "short_attr" to array.';
							}

							// Premium Required? if so Check if active!
							if ( ! isset( $option['req_plugin'] ) || ( isset( $option['req_plugin'] ) && is_plugin_active( $required_plugins[ $option['req_plugin'] ]['plugin_url'] ) || isset( $option['or_req_plugin'] ) && is_plugin_active( $required_plugins[ $option['or_req_plugin'] ]['plugin_url'] ) ) || isset( $option['or_req_plugin_three'] ) && is_plugin_active( $required_plugins[ $option['or_req_plugin_three'] ]['plugin_url'] ) ) {
								// Check "IF"s if they exist!
								if ( isset( $option['short_attr']['ifs'] ) ) {
									$if_array = $option['short_attr']['ifs'];
									$if_array = explode( ',', $if_array );
									foreach ( $if_array as $key => $if_group ) {
										$and_if_array = isset( $option['short_attr']['and_ifs'] ) ? $option['short_attr']['and_ifs'] : '';
										if ( $and_if_array ) {
											// Unset to Shift to end if key exists already!
											if ( isset( $final_shortcode_var[ $if_group ]['and_ifs'] ) ) {
												$inital_and_if = $final_shortcode_var[ $if_group ]['and_ifs'];
												unset( $final_shortcode_var[ $if_group ]['and_ifs'] );
												$final_shortcode_var[ $if_group ]['and_ifs'] = $inital_and_if;
											}
											$final_shortcode_var[ $if_group ]['and_ifs'][ $option['short_attr']['and_ifs'] ][ $attr_name ] = ( isset( $section_attr_key ) ? $section_attr_key : '' ) . $attr_name . $var_final_check;
										} else {
											$final_shortcode_var[ $if_group ][ $attr_name ] = ( isset( $section_attr_key ) ? $section_attr_key : '' ) . $attr_name . $var_final_check;
										}
									}
								} else {
									// no IF.
									$final_shortcode_var['general_options'][] = ( isset( $section_attr_key ) ? $section_attr_key : '' ) . $attr_name . $var_final_check;
								}
							}

							?>
				// Extra Options to show px if user does not enter it.
				// Facebook
				var isPXpresent = jQuery('#facebook_page_height').val();
				// This is in place to auto add the px if a specific input is missing it.
				if (jQuery('#facebook_page_height').val().indexOf('px') <= 0 && isPXpresent !== "") {
					jQuery('#facebook_page_height').val(jQuery('#facebook_page_height').val() + 'px');
				}
							<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
				var isPXpresent2 = jQuery('#facebook_grid_column_width').val();
				if (jQuery('#facebook_grid_column_width').val().indexOf('px') <= 0 && isPXpresent2 !== "") {
					jQuery('#facebook_grid_column_width').val(jQuery('#facebook_grid_column_width').val() + 'px');
				}
				var isPXpresent3 = jQuery('#facebook_grid_space_between_posts').val();
				if (jQuery('#facebook_grid_space_between_posts').val().indexOf('px') <= 0 && isPXpresent3 !== "") {
					jQuery('#facebook_grid_space_between_posts').val(jQuery('#facebook_grid_space_between_posts').val() + 'px');
				}
				var isPXpresent4 = jQuery('#loadmore_button_width').val();
				if (jQuery('#loadmore_button_width').val().indexOf('px') <= 0 && isPXpresent4 !== "") {
					jQuery('#loadmore_button_width').val(jQuery('#loadmore_button_width').val() + 'px');
				}
				var isPXpresent5 = jQuery('#loadmore_button_margin').val();
				if (jQuery('#loadmore_button_margin').val().indexOf('px') <= 0 && isPXpresent5 !== "") {
					jQuery('#loadmore_button_margin').val(jQuery('#loadmore_button_margin').val() + 'px');
				}
				var isPXpresent12 = jQuery('#like_box_width').val();
				if (jQuery('#like_box_width').val().indexOf('px') <= 0 && isPXpresent12 !== "") {
					jQuery('#like_box_width').val(jQuery('#like_box_width').val() + 'px');
				}
				<?php } ?>

				// Twitter
				var isPXpresent6 = jQuery('#twitter_height').val();
				if (jQuery('#twitter_height').val().indexOf('px') <= 0 && isPXpresent6 !== "") {
					jQuery('#twitter_height').val(jQuery('#twitter_height').val() + 'px');
				}
				// Instagram
				var isPXpresent11 = jQuery('#instagram_page_height').val();
				if (jQuery('#instagram_page_height').val().indexOf('px') <= 0 && isPXpresent11 !== "") {
					jQuery('#instagram_page_height').val(jQuery('#instagram_page_height').val() + 'px');
				}
				// Instagram
				var isPXpresent13 = jQuery('#fts-slicker-youtube-container-margin').val();
				if (jQuery('#fts-slicker-youtube-container-margin').val().indexOf('px') <= 0 && isPXpresent13 !== "") {
					jQuery('#fts-slicker-youtube-container-margin').val(jQuery('#fts-slicker-youtube-container-margin').val() + 'px');
				}

							<?php if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) { ?>
				var isPXpresent = jQuery('#combine_grid_column_width').val();
				// This is in place to auto add the px if a specific input is missing it.
				if (jQuery('#combine_grid_column_width').val().indexOf('px') <= 0 && isPXpresent !== "") {
					jQuery('#combine_grid_column_width').val(jQuery('#combine_grid_column_width').val() + 'px');
				}
				var isPXpresent = jQuery('#combine_grid_space_between_posts').val();
				// This is in place to auto add the px if a specific input is missing it.
				if (jQuery('#combine_grid_space_between_posts').val().indexOf('px') <= 0 && isPXpresent !== "") {
					jQuery('#combine_grid_space_between_posts').val(jQuery('#combine_grid_space_between_posts').val() + 'px');
				}
				var isPXpresent = jQuery('#combine_height').val();
				// This is in place to auto add the px if a specific input is missing it.
				if (jQuery('#combine_height').val().indexOf('px') <= 0 && isPXpresent !== "") {
					jQuery('#combine_height').val(jQuery('#combine_height').val() + 'px');
				}
				<?php } ?>

							<?php

						}
					}
					// End JS Loop
					// Start Final Shortcode!
					echo 'var final_' . esc_js( $section ) . '_shorcode_start = \'[fts_' . ( isset( $section_info['shorcode_label'] ) ? esc_js( $section_info['shorcode_label'] ) : esc_js( $section ) ) . '\';' . "\n";

					$shortcode_general_options = '';
					echo 'var final_' . esc_js( $section ) . '_shorcode_attributes =\'\';' . "\n";

					if ( isset( $final_shortcode_var['general_options'] ) ) {
						foreach ( $final_shortcode_var['general_options'] as $final_attribute ) {
							// Add Attributes to shortcode!
							echo 'if (' . esc_js( $final_attribute ) . '){final_' . esc_js( $section ) . '_shorcode_attributes +=' . esc_js( $final_attribute ) . ';}' . "\n";
						}
					}
					// End of shorcode!
					echo 'var final_' . esc_js( $section ) . '_shorcode_end = \']\';' . "\n";

					// Special Options!
					foreach ( $final_shortcode_var as $special_option_group => $special_options ) {
						if ( ( 'general_options' !== $special_option_group ) && isset( $section_info['shortcode_ifs'][ $special_option_group ] ) ) {
							$if_class    = $section_info['shortcode_ifs'][ $special_option_group ]['if']['class'];
							$if_operator = $section_info['shortcode_ifs'][ $special_option_group ]['if']['operator'];
							$if_value    = $section_info['shortcode_ifs'][ $special_option_group ]['if']['value'];

							if ( isset( $final_shortcode_var[ $special_option_group ]['and_ifs'] ) ) {
								$and_ifs_array = $final_shortcode_var[ $special_option_group ]['and_ifs'];

								foreach ( $and_ifs_array as $key => $and_ifs_attribute_array ) {
									$and_if_class    = $section_info['shortcode_ifs'][ $key ]['if']['class'];
									$and_if_operator = $section_info['shortcode_ifs'][ $key ]['if']['operator'];
									$and_if_value    = $section_info['shortcode_ifs'][ $key ]['if']['value'];

									echo 'if (jQuery("' . esc_js( $if_class ) . '").val() ' . esc_js( $if_operator ) . ' "' . esc_js( $if_value ) . '" && jQuery("' . esc_js( $and_if_class ) . '").val() ' . esc_js( $and_if_operator ) . ' "' . esc_js( $and_if_value ) . '") {' . "\n";
									foreach ( $and_ifs_attribute_array as $and_if_key => $and_if_attribute ) {
										// Add Attributes to shortcode!
										echo 'if (' . esc_js( $and_if_attribute ) . '){ final_' . esc_js( $section ) . '_shorcode_attributes +=' . esc_js( $and_if_attribute ) . ';}';
									}
									echo "\n" . '}' . "\n";
								}
							}
							unset( $final_shortcode_var[ $special_option_group ]['and_ifs'] );

							$i = 0;
							echo 'if (jQuery("' . esc_js( $if_class ) . '").val() ' . esc_js( $if_operator ) . ' "' . esc_js( $if_value ) . '") {' . "\n";
							foreach ( $final_shortcode_var[ $special_option_group ] as $key => $final_special_attribute ) {
								// Add Attributes to shortcode!
								echo 'if (' . esc_js( $final_special_attribute ) . '){ final_' . esc_js( $section ) . '_shorcode_attributes +=' . esc_js( $final_special_attribute ) . ';}';
							}
							echo "\n" . '}' . "\n";
						}
					}
					// Put the shortcode together!
					echo 'var final_' . esc_js( $section ) . '_shorcode = final_' . esc_js( $section ) . '_shorcode_start + final_' . esc_js( $section ) . '_shorcode_attributes + final_' . esc_js( $section ) . '_shorcode_end;' . "\n";

					// Create Final Shortcode and show it!
					echo 'jQuery(\'.' . esc_js( $section_info['generator_class'] ) . '\').val(final_' . esc_js( $section ) . '_shorcode);' . "\n";
					echo 'jQuery(\'.' . esc_js( $section_info['form_wrap_classes'] ) . ' .final-shortcode-textarea\').slideDown();';

					echo '}';
				}
				?>

				//END Instagram//


				//START convert Instagram name to id for regular isntagram and combined feeds instagram option //
				function converter_instagram_username() {

					var convert_instagram_username = jQuery("input#convert_instagram_username").val();

					var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();

					// Regular Instagram Converter
					if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username == "") {
						jQuery("#convert_instagram_username").addClass('fts-empty-error');
						jQuery("input#convert_instagram_username").focus();
						return false;
					}
					else if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username !== "") {
						jQuery(".convert_instagram_username").removeClass('fts-empty-error');
						var username_id = "#convert_instagram_username";
						var picker_wrap = "#fts-instagram-username-picker-wrap";
						var username = convert_instagram_username;
					}


					// Combined Feeds Converter
					if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined == "") {
						// alert('wtf');
						jQuery("#combine_convert_instagram_username").addClass('fts-empty-error');
						jQuery("input#combine_convert_instagram_username").focus();
						return false;
					}
					else if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined !== "") {

						jQuery(".convert_instagram_username").removeClass('fts-empty-error');
						var username_id = "#combine_convert_instagram_username";
						var picker_wrap = "#fts-instagram-username-picker-wrap-combined";
						var username = convert_instagram_username_combined;
					}

					console.log(username);

					<?php
					$fts_instagram_tokens_array = array( '9844495a8c4c4c51a7c519d0e7e8f293', '9844495a8c4c4c51a7c519d0e7e8f293' );
					$fts_instagram_access_token = $fts_instagram_tokens_array[ array_rand( $fts_instagram_tokens_array, 1 ) ];
					?>
					jQuery.getJSON("https://api.instagram.com/v1/users/search?q=" + username + "&client_id=<?php echo esc_attr( $fts_instagram_access_token ); ?>&access_token=258559306.da06fb6.c222db6f1a794dccb7a674fec3f0941f&callback=?",

						{
							format: "json"
						},
						function (data) {

							console.log(data);

							var convert_instagram_username = jQuery("input#convert_instagram_username").val();

							var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();
							jQuery('.fts-instagram-username-picker-wrap').html('');
							jQuery('.fts-instagram-username-picker-wrap').closest('.instructional-text').css("border", "none");


							jQuery.each(data.data, function (key, val) {
								console.log(data.data[key].full_name);
								console.log(data.data[key].profile_picture);
								console.log(data.data[key].username);
								console.log(data.data[key].id);

								if (jQuery(username_id).val() === data.data[key].username) {

									jQuery(username_id).prepend(data.data[key].username);

									jQuery(picker_wrap).prepend('<li class="fts-insta-username-' + data.data[key].username + '"><div class="fts-insta-profile-picture-div"><img src="' + data.data[key].profile_picture + '"/><div class="fts-insta-fullname-div"><strong>Full Name:</strong> ' + data.data[key].full_name + '</div><div class="fts-insta-username-div"><strong>Username:</strong> ' + data.data[key].username + '</div><div class="fts-insta-id-div"><strong>ID:</strong> <span class="fts-insta-id-final">' + data.data[key].id + '</span></span></div></li>');


									if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username !== "") {
										jQuery('.fts-insta-username-' + data.data[key].username + '').addClass('fts-insta-id-active');
										jQuery('#instagram_id').val(data.data[key].id);
										console.log('Success');
										console.log('.fts-insta-username-' + data.data[key].username + '');
									}

									if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined !== "") {
										jQuery('.fts-insta-username-' + data.data[key].username + '').addClass('fts-insta-id-active');
										jQuery('#combine_instagram_name').val(data.data[key].id);


										console.log('Success');
										console.log('.fts-insta-username-' + data.data[key].username + '');

									}

								}
								else {
									jQuery(picker_wrap).append('<li class="fts-insta-username-' + data.data[key].username + '"><div class="fts-insta-profile-picture-div"><img src="' + data.data[key].profile_picture + '"/><div class="fts-insta-fullname-div"><strong>Full Name:</strong> ' + data.data[key].full_name + '</div><div class="fts-insta-username-div"><strong>Username:</strong> ' + data.data[key].username + '</div><div class="fts-insta-id-div"><strong>ID:</strong> <span class="fts-insta-id-final">' + data.data[key].id + '</span></span></div></li>');

								}

							});

						});
				}

				//Append button to instagram converter input
				//  jQuery('.combine-instagram-id-option-wrap').append('<input type="button" class="feed-them-social-admin-submit-btn" value="Convert Instagram Username" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />');

				jQuery(document).ready(function () {

					// Use this to force form to be open for easier development access to shortcode options( comment out when not in use ) .fts-instagram-form
					// jQuery('.shortcode-generator-form').hide();
					// jQuery('.combine-steams-shortcode-form').fadeIn('fast');

					// This is for when you click on the returned instagram id,name list it will make that li active and append the value to the instagram ID input.
					jQuery('.fts-instagram-username-picker-wrap').on('click', 'li', function () {
						var convert_instagram_username = jQuery("input#convert_instagram_username").val();
						var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();
						var insta_page_id = jQuery(this).find('.fts-insta-id-final').html();
						console.log(insta_page_id);

						if (convert_instagram_username !== "") {
							jQuery("#instagram_id").val(insta_page_id);
						}
						if (convert_instagram_username_combined !== "") {
							jQuery("#combine_instagram_name").val(insta_page_id);
						}

						jQuery('.fts-instagram-username-picker-wrap li').not(this).removeClass('fts-insta-id-active');
						jQuery(this).addClass('fts-insta-id-active');

					});

					jQuery(".toggle-custom-textarea-show").click(function () {
						jQuery('textarea#fts-color-options-main-wrapper-css-input').slideToggle();
						jQuery('.toggle-custom-textarea-show span').toggle();
						jQuery('.fts-custom-css-text').toggle();
					});

					// START: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.
					jQuery("#fb_page_id").change(function () {
						var feedID = jQuery("input#fb_page_id").val();
						if (feedID.indexOf('facebook.com') != -1 || feedID.indexOf('facebook.com') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#fb_page_id').val(newfeedID);

						}
					});

					jQuery("#twitter_name").change(function () {
						var feedID = jQuery("input#twitter_name").val();
						if (feedID.indexOf('twitter.com') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#twitter_name').val(newfeedID);

						}
					});

					jQuery("#convert_instagram_username").change(function () {
						var feedID = jQuery("input#convert_instagram_username").val();
						if (feedID.indexOf('instagram.com') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#convert_instagram_username').val(newfeedID);

						}
					});

					jQuery("#pinterest_board_name").change(function () {
						var feedID = jQuery("input#pinterest_board_name").val();
						if (feedID.indexOf('pinterest.com') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#pinterest_board_name').val(newfeedID);

						}
					});

					jQuery("#pinterest_name").change(function () {
						var feedID = jQuery("input#pinterest_name").val();
						if (feedID.indexOf('pinterest.com') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#pinterest_name').val(newfeedID);

						}
					});

					<?php
					// show the js for the discount option under social icons on the settings page
					// if(!is_plugin_active('feed-them-premium/feed-them-premium.php')) {
					// jQuery("#discount-for-review").click(function () {
					// jQuery('.discount-review-text').slideToggle();
					// });!
					?>

					//START youtube//
					//Youtube Options
					jQuery('select#youtube-messages-selector').bind('change', function (e) {
						if (jQuery('#youtube-messages-selector').val() == 'channelID') {
							jQuery('.youtube_name, .youtube_playlistID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
							jQuery('.youtube_channelID, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
						}
						else if (jQuery('#youtube-messages-selector').val() == 'userPlaylist') {
							jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
							jQuery('.youtube_playlistID2, .youtube_name2, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').show();
						}
						else if (jQuery('#youtube-messages-selector').val() == 'playlistID') {
							jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
							jQuery('.youtube_playlistID, .youtube_channelID2, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
						}
						else if (jQuery('#youtube-messages-selector').val() == 'singleID') {
							jQuery('.youtube_name,.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_vid_count, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').hide();
							jQuery('.youtube_singleVideoID, .youtube_align_comments_wrap, .youtube_video_single_info_display').show();
						}
						else if (jQuery('#youtube-messages-selector').val() == 'username') {
							jQuery('.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
							jQuery('.youtube_name, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
						}
					});


					jQuery('.youtube_first_video').hide();

					jQuery('select#youtube_columns').change(function () {
						var youtube_columns_count = jQuery(this).val();

						if (youtube_columns_count == '1') {
							jQuery('.youtube_first_video').hide();
						}
						else {
							jQuery('.youtube_first_video').show();
						}
					});


					jQuery("#youtube_name").change(function () {
						var feedID = jQuery("input#youtube_name").val();
						if (feedID.indexOf('youtube.com/user') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_name').val(newfeedID);

						}
					});

					jQuery("#youtube_name2").change(function () {
						var feedID = jQuery("input#youtube_name2").val();
						if (feedID.indexOf('youtube.com/user') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_name2').val(newfeedID);

						}
					});

					jQuery("#youtube_channelID").change(function () {
						var feedID = jQuery("input#youtube_channelID").val();
						if (feedID.indexOf('youtube.com/channel') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_channelID').val(newfeedID);

						}
					});

					jQuery("#youtube_channelID2").change(function () {
						var feedID = jQuery("input#youtube_channelID2").val();
						if (feedID.indexOf('youtube.com/channel') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_channelID2').val(newfeedID);

						}
					});

					jQuery("#youtube_playlistID").change(function () {
						var feedID = jQuery("input#youtube_playlistID").val();
						if (feedID.indexOf('&list=') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_playlistID').val(newfeedID);

						}
					});

					jQuery("#youtube_playlistID2").change(function () {
						var feedID = jQuery("input#youtube_playlistID2").val();
						if (feedID.indexOf('&list=') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_playlistID2').val(newfeedID);

						}
					});

					jQuery("#youtube_singleVideoID").change(function () {
						var feedID = jQuery("input#youtube_singleVideoID").val();
						if (feedID.indexOf('watch?v=') != -1) {
							feedID = feedID.replace(/\/$/, '');
							feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
							var newfeedID = feedID;
							jQuery('#youtube_singleVideoID').val(newfeedID);

						}
					});


					// END: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.


					jQuery(".copyme").focus(function () {

						var jQuerythis = jQuery(this);
						jQuerythis.select();
						// Work around Chrome's little problem
						jQuerythis.mouseup(function () {
							// Prevent further mouseup intervention
							jQuerythis.unbind("mouseup");
							return false;
						});
					});

				}); //end document ready

				// Like box/button Options Premium Content
				jQuery('#facebook-messages-selector').change(function () {
					if (jQuery("select#facebook-messages-selector").val() == "group" || jQuery("select#facebook-messages-selector").val() == "event" || jQuery("select#facebook-messages-selector").val() == "events") {
						jQuery('.main-like-box-wrap').hide();
						// alert(jQuery("select#facebook-messages-selector").val());
					}
					else {
						jQuery('.main-like-box-wrap').show();
					}
				});

				// Carousel and Slideshow Premium Content
				jQuery('#facebook-messages-selector').change(function () {
					if (jQuery("select#facebook-messages-selector").val() == "album_photos" || jQuery("select#facebook-messages-selector").val() == "album_videos") {
						jQuery('.slideshow-wrap').show();
					}
					else {
						jQuery('.slideshow-wrap').hide();
					}
				});
				jQuery('#scrollhorz_or_carousel').change(function () {
					jQuery('.slider_carousel_wrap').toggle();
				});
				jQuery('#fts-slider').change(function () {
					jQuery('.slider_options_wrap').toggle();
				});

			</script>
			<?php
		}
	}
}
// end class.
