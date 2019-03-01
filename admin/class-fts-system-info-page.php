<?php
/**
 * Feed Them Social - System Info Page
 *
 * This page is used to get the details of WordPress install, Server Info and settings and Feed Them Social Settings.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS System Info Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_System_Info_Page extends feed_them_social_functions {

	/**
	 * Construct
	 *
	 * Facebook Them System constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
	}

	/**
	 * Feed Them Sstem Info Page
	 *
	 * @since 1.9.6
	 */
	public function feed_them_system_info_page() {
		?>
		<div class="fts-help-admin-wrap"> <a class="buy-extensions-btn" href="https://www.slickremix.com/downloads/category/feed-them-social/" target="_blank">
		<?php esc_html_e( 'Get Extensions Here!', 'feed-them-social' ); ?>
		</a>
		<h2>
		<?php esc_html_e( 'System Info', 'feed-them-social' ); ?>
		</h2>
		<p>
		<?php esc_html_e( 'Please click the box below and copy the report. You will need to paste this information along with your question when requesting', 'feed-them-social' ); ?>
		<a href="https://www.slickremix.com/support/" target="_blank">
		<?php esc_html_e( 'Support', 'feed-them-social' ); ?>
		</a>.
		<?php esc_html_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>
		</p>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=fts-system-info-submenu-page' ) ); ?>" method="post" dir="ltr" >
		<textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="fts-sysinfo" title="<?php esc_html_e( 'To copy the system info, click here then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>">
### Begin System Info ###
<?php
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version;
?>SITE_URL:                 <?php echo esc_html( site_url() ) . "\n"; ?>
Feed Them Social Version: <?php echo esc_html( FEED_THEM_SOCIAL_VERSION ) . "\n"; ?>

-- WordPress Configuration:
WordPress Version:        <?php echo esc_html( get_bloginfo( 'version' ) ) . "\n"; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n"; ?>
Permalink Structure:      <?php echo esc_html( get_option( 'permalink_structure' ) ) . "\n"; ?>
Active Theme:             <?php echo esc_html( $theme ) . "\n"; ?>
PHP Memory Limit:         <?php echo esc_html( ini_get( 'memory_limit' ) ) . "\n"; ?>
WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? esc_html( WP_DEBUG ) ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n"; ?>

-- Webserver Configuration:
PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
<?php $server_software = sanitize_key( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ); ?>
Web Server Info:          <?php echo esc_html( $server_software ) . "\n"; ?>

-- PHP Configuration:
Safe Mode:                <?php echo ini_get( 'safe_mode' ) ? 'Yes' : "No\n"; ?>
Upload Max Size:          <?php echo esc_html( ini_get( 'upload_max_filesize' ) ) . "\n"; ?>
Post Max Size:            <?php echo esc_html( ini_get( 'post_max_size' ) ) . "\n"; ?>
Upload Max Filesize:      <?php echo esc_html( ini_get( 'upload_max_filesize' ) ) . "\n"; ?>
Time Limit:               <?php echo esc_html( ini_get( 'max_execution_time' ) ) . "\n"; ?>
Max Input Vars:           <?php echo esc_html( ini_get( 'max_input_vars' ) ) . "\n"; ?>
Allow URL File Open:      <?php echo ( ini_get( 'allow_url_fopen' ) ) ? esc_html( 'On (' . ini_get( 'display_errors' ) . ')' ) : 'N/A'; ?><?php echo "\n"; ?>
Display Erros:            <?php echo ( ini_get( 'display_errors' ) ) ? esc_html( 'On (' . ini_get( 'display_errors' ) . ')' ) : 'N/A'; ?><?php echo "\n"; ?>

-- PHP Extensions:
json:                     <?php echo ( extension_loaded( 'json' ) ) ? 'Your server supports json.' : 'Your server does not support json. Please contact your host to activate or install this php extension.'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
curl_multi:               <?php echo ( function_exists( 'curl_multi_select' ) ) ? 'Your server supports curl_multi_select.' : 'Your server does not support curl_multi_select. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>


-- FTS Settings->Global Options: <?php $fts_cachetime = get_option( 'fts_clear_cache_developer_mode' ) ? get_option( 'fts_clear_cache_developer_mode' ) : '86400'; ?>

Cache time: <?php echo esc_html( $this->fts_cachetime_amount( $fts_cachetime ) ) . "\n"; ?>

-- Active Plugins: 
<?php
		$plugins        = \get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
				continue;
			}
			echo esc_html( $plugin['Name'] ) . ': ' . esc_html( $plugin['Version'] ) . "\n";
		}
		if ( is_multisite() ) :
			?>

-- Network Active Plugins:

<?php
				$plugins    = \wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
					continue;
				}

				$plugin = get_plugin_data( $plugin_path );

				echo esc_html( $plugin['Name'] ) . ' :' . esc_html( $plugin['Version'] ) . "\n";
			}

			endif;

			$facebook_options  = get_option( 'fts_facebook_custom_api_token' ) ? 'Yes' : 'No';
			$twitter_options1  = get_option( 'fts_twitter_custom_consumer_key' ) ? 'Yes' : 'No';
			$twitter_options2  = get_option( 'fts_twitter_custom_consumer_secret' ) ? 'Yes' : 'No';
			$twitter_options3  = get_option( 'fts_twitter_custom_access_token' ) ? 'Yes' : 'No';
			$twitter_options4  = get_option( 'fts_twitter_custom_access_token_secret' ) ? 'Yes' : 'No';
			$instagram_options = get_option( 'fts_instagram_custom_api_token' ) ? 'Yes' : 'No';
			$pinterest_token   = get_option( 'fts_pinterest_custom_api_token' ) ? 'Yes' : 'No';

			$fts_date_time_format = get_option( 'fts-date-and-time-format' ) ? get_option( 'fts-date-and-time-format' ) : 'No';
			$fts_timezone         = get_option( 'fts-timezone' ) ? get_option( 'fts-timezone' ) : 'No';

			$fts_offset_post_limit             = get_option( 'fb_count_offset' ) ? get_option( 'fb_count_offset' ) : 'None';
			$fts_hide_offset_post_limit_notice = get_option( 'fb_hide_no_posts_message' ) ? 'No' : 'Yes';
			$fts_fix_time_out                  = get_option( 'fts_curl_option' ) ? get_option( 'fts_curl_option' ) : 'No';

			$fts_fix_twitter_time     = get_option( 'fts_twitter_time_offset' ) ? get_option( 'fts_twitter_time_offset' ) : '';
			$fts_disable_magnific_css = get_option( 'fts_fix_magnific' ) ? get_option( 'fts_fix_magnific' ) : '';

		?>

-- Custom Token or Keys added to Options Pages
-- You must have a custom token to use the feeds

<?php
		if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
			$fb_reviews_token = get_option( 'fts_facebook_custom_api_token_biz' ) ? 'Yes' : 'No';
			?>
Facebook Reviews App Token: <?php echo esc_html( $fb_reviews_token ) . "\n"; } ?>
Facebook App Token:         <?php echo esc_html( $facebook_options ) . "\n"; ?>
Twitter Consumer Key:       <?php echo esc_html( $twitter_options1 ) . "\n"; ?>
Twitter Secret:             <?php echo esc_html( $twitter_options2 ) . "\n"; ?>
Twitter Token:              <?php echo esc_html( $twitter_options3 ) . "\n"; ?>
Twitter Token Secret:       <?php echo esc_html( $twitter_options4 ) . "\n"; ?>
Pinterest Token:            <?php echo esc_html( $pinterest_token ) . "\n"; ?>
Instagram: 		    <?php echo esc_html( $instagram_options ) . "\n";
		$youtube_options                               = get_option( 'youtube_custom_api_token' ) || get_option( 'youtube_custom_access_token' ) && get_option( 'youtube_custom_refresh_token' ) && get_option( 'youtube_custom_token_exp_time' ) ? 'Yes' : 'No';
		$fts_fix_loadmore                              = get_option( 'fts_fix_loadmore' ) ? get_option( 'fts_fix_loadmore' ) : 'No';
		$feed_them_social_premium_license_key          = get_option( 'feed_them_social_premium_license_key' );
		$fts_bar_license_key                           = get_option( 'fts_bar_license_key' );
		$feed_them_carousel_premium_license_key        = get_option( 'feed_them_carousel_premium_license_key' );
		$feed_them_social_combined_streams_license_key = get_option( 'feed_them_social_combined_streams_license_key' );
		$fb_hide_error_handler_message                 = get_option( 'fb_hide_error_handler_message' );
		$fb_hide_images_in_posts                       = get_option( 'fb_hide_images_in_posts' );
?>
YouTube: 		    <?php echo esc_html( $youtube_options ) . "\n"; ?>

-- FaceBook & Twitter Date Format and Timezone

Date Format: <?php echo esc_html( $fts_date_time_format ) . "\n"; ?>
Timezone: <?php echo esc_html( $fts_timezone ) . "\n"; ?>

-- Hide Facebook Images in Posts:

Hide: <?php echo isset( $fb_hide_images_in_posts ) && 'yes' === $fb_hide_images_in_posts ? 'Yes' . "\n" : 'No' . "\n"; ?>

-- Hide Facebook Error Handler:

Hide: <?php echo isset( $fb_hide_error_handler_message ) && 'yes' === $fb_hide_error_handler_message ? 'Yes' . "\n" : 'No' . "\n"; ?>

-- Fix Twitter Time:

Fix: <?php echo isset( $fts_fix_twitter_time ) && 1 === $fts_fix_twitter_time ? 'Yes' . "\n" : 'No' . "\n"; ?>

-- Disable Magnific CSS:

Fix: <?php echo isset( $fts_disable_magnific_css ) && 1 === $fts_disable_magnific_css ? 'Yes' . "\n" : 'No' . "\n"; ?>

-- Fix Internal Server Error:

Fix: <?php echo isset( $fts_fix_time_out ) && 1 === $fts_fix_time_out ? 'Yes' . "\n" : 'No' . "\n"; ?>
<?php if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || is_plugin_active( 'fts-bar/fts-bar.php' ) || is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) ) { ?>

-- Load More Options:

Override: <?php echo isset( $fts_fix_loadmore ) && 1 === $fts_fix_loadmore ? 'Yes' . "\n" : 'No' . "\n"; ?>

-- Premium Extensions:

<?php if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) { ?>
FTS Combined Streams:    <?php echo isset( $feed_them_social_combined_streams_license_key ) && '' !== $feed_them_social_combined_streams_license_key ? 'Yes' . "\n" : 'No' . "\n"; } if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>
Premium Active:          <?php echo isset( $feed_them_social_premium_license_key ) && '' !== $feed_them_social_premium_license_key ? 'Yes' . "\n" : 'No' . "\n"; }if ( is_plugin_active( 'fts-bar/fts-bar.php' ) ) { ?>
FTS Bar Active:          <?php echo isset( $fts_bar_license_key ) && '' !== $fts_bar_license_key ? 'Yes' . "\n" : 'No' . "\n"; }if ( is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) ) { ?>
FTS Carousel Premium:    <?php echo isset( $feed_them_carousel_premium_license_key ) && '' !== $feed_them_carousel_premium_license_key ? 'Yes' . "\n" : 'No' . "\n"; }if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
Facebook Reviews Active: <?php echo isset( $fb_reviews_token ) && '' !== $fb_reviews_token ? 'Yes' . "\n" : 'No' . "\n"; }}?>
### End System Info ###</textarea>
		<?php
		// -- Pages or Posts with Shortcode(s).
		// -- If you are using our shortcode in a widget you'll need to paste your shortcode in our support forum.
		// COMMENTING OUT FOR NOW BECAUSE TO MUCH INFO FROM PEOPLES SITES ARE BEING ADDED TO OUR SUPPORT FORUMS.
		// echo do_shortcode("[shortcodefinderSlick find='[fts']");
		?>
		</form>
		<a class="fts-settings-admin-slick-logo" href="https://www.slickremix.com/support/" target="_blank"></a> </div>
		<?php
	}

}//end class
