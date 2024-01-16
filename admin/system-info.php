<?php
/**
 * System Info
 *
 * This class is for loading up the System Info Page for debugging issues
 *
 * @class    System_Info
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

/**
 * Class System_Info
 */
class System_Info {

	/**
	 * Settings Functions
	 *
	 * The settings Functions class
	 *
	 * @var object
	 */
	public $settings_functions;

	/**
	 * Feed Cache.
	 *
	 * Class used for caching.
	 *
	 * @var object
	 */
	public $feed_cache;

	/**
	 * System_Info constructor.
	 */
	public function __construct( $settings_functions, $feed_cache ) {

		// Settings Functions.
		$this->settings_functions = $settings_functions;

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;

		// Add Actions and Filters.
		$this->add_actions_filters();
    }

	/**
	 * Add Action Filters
	 *
	 * Add System Info to our menu.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {
		if ( is_admin() ) {
			// Adds setting page to Feed Them Social menu.
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		}
	}

	/**
	 *  Submenu Pages
	 *
	 * Admin Submenu buttons
	 *
	 * @since 1.0.0
	 */
	public function add_submenu_page() {
		// System Info.
		add_submenu_page(
			'edit.php?post_type=fts',
			__( 'System Info', 'feed-them-social' ),
			__( 'System Info', 'feed-them-social' ),
			'manage_options',
			'fts-system-info-submenu-page',
			array( $this, 'fts_system_info_page' )
		);
	}

	/**
	 * System Info Page
	 *
	 * System info page html.
	 *
	 * @since 1.0.0
	 */
	public function fts_system_info_page() {
		?>
		<div class="ft-gallery-main-template-wrapper-all">

		<div class="ft-gallery-settings-admin-wrap" id="theme-settings-wrap">
			<h2><?php esc_html_e( 'System Info', 'feed-them-social' ); ?></h2>
			<p>
				<?php esc_html_e( 'Please click the box below and copy the report. You will need to paste this information along with your question when creating a', 'feed-them-social' ); ?>
				<a href="https://www.slickremix.com/my-account/#tab-support" target="_blank">
					<?php esc_html_e( 'Support Ticket', 'feed-them-social' ); ?></a>.</p>
			<p>
				<?php esc_html_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>
			</p>
			<form action="<?php echo esc_url( admin_url( 'admin.php?page=ft-gallery-system-info-submenu-page' ) ); ?>" method="post" dir="ltr">
		<textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="ft-gallery-sysinfo" title="<?php esc_html_e( 'To copy the system info, click here then press Ctrl + C (PC) or Cmd + C (Mac).', 'feed-them-social' ); ?>">
<?php echo $this->fts_system_info_support_ticket(); ?></textarea>
			</form>
		</div>
		</div>

		<?php
	}

function fts_system_info_support_ticket(){ ob_start() ?>
### Begin System Info ###
<?php
$theme_data = wp_get_theme();
$theme      = $theme_data->name . ' ' . $theme_data->version;
?>

SITE_URL: <?php echo esc_url( site_url() ) . "\n"; ?>
Feed Them Social Version: <?php echo esc_html( FEED_THEM_SOCIAL_VERSION ) . "\n"; ?>

-- WordPress Configuration:
WordPress Version: <?php echo esc_html( get_bloginfo( 'version' ) ) . "\n"; ?>
Multisite: <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n"; ?>
Permalink Structure: <?php echo esc_html( get_option( 'permalink_structure' ) ) . "\n"; ?>
Active Theme: <?php echo esc_html( $theme ) . "\n"; ?>
PHP Memory Limit: <?php echo esc_html( ini_get( 'memory_limit' ) ) . "\n"; ?>
WP_DEBUG: <?php echo esc_html( defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" ); ?>
Cron Jobs: <?php echo esc_html( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ? 'Disabled' . "\n" : 'Enabled' . "\n" ); ?>

-- Webserver Configuration:
PHP Version: <?php
echo PHP_VERSION . "\n";
$my_request = stripslashes_deep( $_SERVER );
?>
Web Server Info: <?php echo esc_html( $my_request['SERVER_SOFTWARE'] ) . "\n"; ?>

-- PHP Configuration:
Upload Max Size: <?php echo esc_html( ini_get( 'upload_max_filesize' ) . "\n" ); ?>
Post Max Size: <?php echo esc_html( ini_get( 'post_max_size' ) . "\n" ); ?>
Upload Max Filesize: <?php echo esc_html( ini_get( 'upload_max_filesize' ) . "\n" ); ?>
Time Limit: <?php echo esc_html( ini_get( 'max_execution_time' ) . "\n" ); ?>
Max Input Vars: <?php echo esc_html( ini_get( 'max_input_vars' ) . "\n" ); ?>
Allow URL File Open: <?php echo esc_html( ini_get( 'allow_url_fopen' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ); ?><?php echo "\n"; ?>
Display Erros: <?php echo esc_html( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ); ?><?php echo "\n"; ?>

-- PHP Extensions:
json:                     <?php echo ( extension_loaded( 'json' ) ) ? 'Your server supports json.' : 'Your server does not support json. Please contact your host to activate or install this php extension.'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
curl_multi:               <?php echo ( function_exists( 'curl_multi_select' ) ) ? 'Your server supports curl_multi_select.' : 'Your server does not support curl_multi_select. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>

-- FTS Settings->Global Options: <?php $fts_cachetime = $this->settings_functions->fts_get_option( 'fts_cache_time' ) ? $this->settings_functions->fts_get_option( 'fts_cache_time' ) : '86400'; ?>

Cache time: <?php echo esc_html( $this->feed_cache->fts_cachetime_amount( $fts_cachetime ) ) . "\n"; ?>

-- Active Plugins:
<?php
$plugins        = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );
foreach ( $plugins as $plugin_path => $plugin ) {
    // If the plugin isn't active, don't show it.
    if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
        continue;
    }
    echo esc_html( $plugin['Name'] . ': ' . $plugin['Version'] . "\n" );
}
if ( is_multisite() ) :
    ?>
-- Network Active Plugins:
    <?php
    $plugins        = wp_get_active_network_plugins();
    $active_plugins = get_site_option( 'active_sitewide_plugins', array() );

    foreach ( $plugins as $plugin_path ) {
        $plugin_base = plugin_basename( $plugin_path );

        // If the plugin isn't active, don't show it.
        if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
            continue;
        }

        $plugin = get_plugin_data( $plugin_path );

        echo esc_html( $plugin['Name'] . ' :' . $plugin['Version'] . "\n" );

    }
endif;

if ( is_plugin_active( 'feed-them-social/feed-them-social.php' ) ) {
    $feed_them_social_license_key = get_option( 'feed_them_social_license_keys' );
	// print_r( $feed_them_social_license_key );
    ?>

-- FTS Plugins Active & License Validation
Premium: <?php echo is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
License Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_premium']['license_status']) && $feed_them_social_license_key['feed_them_social_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>

TikTok Premium: <?php echo is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
License Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_tiktok_premium']['license_status']) && $feed_them_social_license_key['feed_them_social_tiktok_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>

Combined Streams: <?php echo is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
License Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_combined_streams']['license_status']) && $feed_them_social_license_key['feed_them_social_combined_streams']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>

Facebook Reviews: <?php echo is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
License Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_facebook_reviews']['license_status']) && $feed_them_social_license_key['feed_them_social_facebook_reviews']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>

Carousel Premium: <?php echo is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
License Valid: <?php echo isset($feed_them_social_license_key['feed_them_carousel_premium']['license_status']) && $feed_them_social_license_key['feed_them_carousel_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
<?php } ?>

### End System Info ###<?php return ob_get_clean(); } // end fts_system_info_support_ticket

}//end class
