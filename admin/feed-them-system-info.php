<?php
namespace feedthemsocial;
/**
 * Class FTS System Info Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_system_info_page extends feed_them_social_functions {

    /**
     * Construct
     *
     * Facebook Them System constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
		if ( is_admin() ) {
			if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page'){
				//Set Search to Page and Posts
				add_filter('pre_get_posts',  array($this,'search_filter'));
				//Filter for shortcodes
				add_shortcode('shortcodefinderSlick',  array($this,'wpb_find_shortcode'));
			}
		}
	}

    /**
     * Feed Them Sstem Info Page
     *
     * @since 1.9.6
     */
    function feed_them_system_info_page() {
?>
		<div class="fts-help-admin-wrap"> <a class="buy-extensions-btn" href="http://www.slickremix.com/downloads/category/feed-them-social/" target="_blank">
		<?php _e( 'Get Extensions Here!', 'feed-them-social' ); ?>
		</a>
		<h2>
		<?php _e( 'System Info', 'feed-them-social' ); ?>
		</h2>
		<p>
		<?php _e( 'Please click the box below and copy the report. You will need to paste this information along with your question in our', 'feed-them-social' ); ?>
		<a href="http://www.slickremix.com/support-forum/" target="_blank">
		<?php _e( 'Support Forum', 'feed-them-social' ); ?>
		</a>.
		<?php _e( 'Ask your question then paste the copied text below it.  To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>
		</p>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=fts-system-info-submenu-page' ) ); ?>" method="post" dir="ltr" >
		<textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="fts-sysinfo" title="<?php _e( 'To copy the system info, click here then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>">
### Begin System Info ###
		<?php
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version; ?>

SITE_URL:                 <?php echo site_url() . "\n"; ?>
Feed Them Social Version: <?php echo FEED_THEM_SOCIAL_VERSION. "\n"; ?>

-- Wordpress Configuration:
	
WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Active Theme:             <?php echo $theme . "\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

-- Webserver Configuration:
	
PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

-- PHP Configuration:
	
Safe Mode:                <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
Upload Max Size:          <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Post Max Size:            <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
Upload Max Filesize:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Time Limit:               <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
Max Input Vars:           <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
Allow URL File Open:      <?php echo ( ini_get( 'allow_url_fopen' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
Display Erros:            <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>

-- PHP Extensions:
	
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; ?><?php echo "\n"; ?>

-- FTS Settings->Global Options:
<?php $fts_cachetime = get_option('fts_clear_cache_developer_mode') ? get_option('fts_clear_cache_developer_mode') : '86400' ; ?>

Cache time:               <?php echo $this->fts_cachetime_amount($fts_cachetime) . "\n"; ?>

-- Active Plugins:

<?php $plugins = \get_plugins();
$active_plugins = get_option( 'active_plugins', array() );
foreach ( $plugins as $plugin_path => $plugin ) {
// If the plugin isn't active, don't show it.
if ( ! in_array( $plugin_path, $active_plugins ) )
continue;
echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
			}
if ( is_multisite() ) :
?>

-- Network Active Plugins:

		<?php
				$plugins = \wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $active_plugins ) )
					continue;

				$plugin = get_plugin_data( $plugin_path );

				echo $plugin['Name'] . ' :' . $plugin['Version'] ."\n";
			}

			endif;

			$facebookOptions = get_option('fts_facebook_custom_api_token') ? 'Yes' : 'No' ;
			$facebookOptionsAppID = get_option('fb_app_ID') ? 'Yes' : 'No' ;
			$twitterOptions1 = get_option('fts_twitter_custom_consumer_key') ? 'Yes' : 'No' ;
			$twitterOptions2 = get_option('fts_twitter_custom_consumer_secret') ? 'Yes' : 'No' ;
			$twitterOptions3 = get_option('fts_twitter_custom_access_token') ? 'Yes' : 'No' ;
			$twitterOptions4 = get_option('fts_twitter_custom_access_token_secret') ? 'Yes' : 'No' ;
			$instagramOptions = get_option('fts_instagram_custom_api_token') ? 'Yes' : 'No' ;
			$pinterest_token = get_option('fts_pinterest_custom_api_token') ? 'Yes' : 'No' ;

			$ftsDateTimeFormat = get_option('fts-date-and-time-format') ? get_option('fts-date-and-time-format') : 'No' ;
			$ftsTimezone = get_option('fts-timezone') ? get_option('fts-timezone') : 'No' ;

			$ftsOffsetPostLimit = get_option('fb_count_offset') ? get_option('fb_count_offset') : 'None' ;
			$ftsHideOffsetPostLimitNotice = get_option('fb_hide_no_posts_message') ? 'No' : 'Yes' ;
			$ftsFixTimeOut = get_option('fts_curl_option') ? get_option('fts_curl_option') : 'No' ;

			$ftsFixTwitterTime = get_option('fts_twitter_time_offset') ? get_option('fts_twitter_time_offset') : '' ;
			$ftsDisableMagnificCSS = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '' ;

	?>

-- Custom Token or Keys added to Options Pages
-- You must have a custom token to use the feeds

<?php if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
	$fb_reviews_token = get_option('fts_facebook_custom_api_token_biz') ?'Yes' :'No' ;
	?>
Facebook Reviews App Token: <?php echo $fb_reviews_token      . "\n"; } ?>
Facebook App Token:         <?php echo $facebookOptions      . "\n"; ?>
FB App ID for Like Button:  <?php echo $facebookOptionsAppID . "\n"; ?>
Twitter Consumer Key:       <?php echo $twitterOptions1      . "\n"; ?>
Twitter Secret:             <?php echo $twitterOptions2      . "\n"; ?>
Twitter Token:              <?php echo $twitterOptions3      . "\n"; ?>
Twitter Token Secret:       <?php echo $twitterOptions4      . "\n"; ?>
Pinterest Token:            <?php echo $pinterest_token      . "\n"; ?>
Instagram:                  <?php echo $instagramOptions     . "\n";

$youtubeOptions = get_option('youtube_custom_api_token') || get_option('youtube_custom_access_token') && get_option('youtube_custom_refresh_token') && get_option('youtube_custom_token_exp_time') ?'Yes' :'No' ;
$ftsFixLoadmore = get_option('fts_fix_loadmore') ? get_option('fts_fix_loadmore') : 'No' ;
$feed_them_social_premium_license_key = get_option('feed_them_social_premium_license_key');
$fts_bar_license_key = get_option('fts_bar_license_key');
$feed_them_carousel_premium_license_key = get_option('feed_them_carousel_premium_license_key');
$feed_them_social_combined_streams_license_key = get_option('feed_them_social_combined_streams_license_key');

	?>YouTube:                    <?php echo $youtubeOptions     . "\n"; ?>

-- FaceBook & Twitter Date Format and Timezone

Date Format:                <?php echo $ftsDateTimeFormat     . "\n"; ?>
Timezone:                   <?php echo $ftsTimezone     . "\n"; ?>

-- Fix Twitter Time:

Fix:                        <?php echo isset($ftsFixTwitterTime) && $ftsFixTwitterTime == 1 ? 'Yes'. "\n" : 'No'. "\n"; ?>

-- Disable Magnific CSS:

Fix:                        <?php echo isset($ftsDisableMagnificCSS) && $ftsDisableMagnificCSS == 1 ? 'Yes'. "\n" : 'No'. "\n"; ?>

-- Fix Internal Server Error:
		<?php if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php') || is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || is_plugin_active('fts-bar/fts-bar.php') || is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php') ) { ?>

Fix:                        <?php echo isset($ftsFixTimeOut) && $ftsFixTimeOut == 1 ? 'Yes'. "\n" : 'No'. "\n"; ?>

-- Load More Options:

Override:                   <?php echo isset($ftsFixLoadmore) && $ftsFixLoadmore == 1 ? 'Yes'. "\n" : 'No'. "\n"; ?>

-- Premium Extensions:

<?php if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) { ?>
FTS Combined Streams:       <?php echo isset($feed_them_social_combined_streams_license_key) && $feed_them_social_combined_streams_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; }if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
Premium Active:             <?php echo isset($feed_them_social_premium_license_key) && $feed_them_social_premium_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; }if (is_plugin_active('fts-bar/fts-bar.php')) { ?>
FTS Bar Active:             <?php echo isset($fts_bar_license_key) && $fts_bar_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; }if (is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php')) { ?>
FTS Carousel Premium:       <?php echo isset($feed_them_carousel_premium_license_key) && $feed_them_carousel_premium_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; }if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
Facebook Reviews Active:    <?php echo isset($fb_reviews_token) && $fb_reviews_token !== '' ? 'Yes'. "\n" : 'No'. "\n";}
		} ?>
			
### End System Info ###</textarea>
	<?php
//-- Pages or Posts with Shortcode(s).
//-- If you are using our shortcode in a widget you'll need to paste your shortcode in our support forum.

 // COMMENTING OUT FOR NOW BECAUSE TO MUCH INFO FROM PEOPLES SITES ARE BEING ADDED TO OUR SUPPORT FORUMS.
 //echo do_shortcode("[shortcodefinderSlick find='[fts']"); ?>
		</form>
		<a class="fts-settings-admin-slick-logo" href="http://www.slickremix.com/support-forum/" target="_blank"></a> </div>
		<?php
		}
		//**************************************************
		// Search Filter
		//**************************************************
		function search_filter( $query ) {
			if ( $query->is_search ) {
				$query->set( 'post_type', array('post', 'page') );
			}
			return $query;
		}
		//**************************************************
		// Find Shortcode Filter
		//**************************************************
		function wpb_find_shortcode($atts, $content=null) {
			ob_start();
			extract( shortcode_atts( array(
						'find' => '',
					), $atts ) );

			$string = $atts['find'];

			$args = array(
				's' => $string,
				'posts_per_page'=>100,
			);

			$the_query = new \WP_Query( $args );

			$posts = $the_query->get_posts();

			foreach ($posts as $post) {
	  $the_query->the_post(); ?>
<<< <?php the_permalink(); ?> >>>
<?php remove_filter( 'the_content', 'do_shortcode', 11 ); the_content();
			}

		wp_reset_postdata();
		return ob_get_clean();
	}

}//End Class