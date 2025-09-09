<?php
/**
 * System Info
 *
 * This class is for loading up the System Info Page for debugging issues
 *
 * @class    SystemInfo
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial\admin;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class SystemInfo
 */
class SystemInfo {

    /**
     * Settings Functions
     *
     * The settings Functions class
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Feed Functions
     *
     * General Feed Functions to be used in most Feeds.
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Feed Cache.
     *
     * Class used for caching.
     *
     * @var object
     */
    public $feedCache;

    /**
     * Error Code Text.
     *
     * @var string
     */
    const ERROR_CODE_TEXT = 'Error: ';

    /**
     * SystemInfo constructor.
     */
    public function __construct( $settingsFunctions, $feedFunctions, $feedCache ) {

        // Settings Functions.
        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        // Set Feed Cache object.
        $this->feedCache = $feedCache;

        // Add Actions and Filters.
        $this->addActionsFilters();
    }

    /**
     * Add Action Filters
     *
     * Add System Info to our menu.
     *
     * @since 1.0.0
     */
    public function addActionsFilters() {
        if ( is_admin() ) {
            // Adds setting page to Feed Them Social menu.
            add_action( 'admin_menu', array( $this, 'addSubmenuPage' ) );
        }
    }

    /**
     *  Submenu Pages
     *
     * Admin Submenu buttons
     *
     * @since 1.0.0
     */
    public function addSubmenuPage() {
        // System Info.
        add_submenu_page(
            'edit.php?post_type=fts',
            __( 'System Info', 'feed-them-social' ),
            __( 'System Info', 'feed-them-social' ),
            'manage_options',
            'fts-system-info-submenu-page',
            array( $this, 'ftsSystemInfoPage' )
        );
    }

    /**
     * Check if a specific cron job is scheduled and get the next run time.
     *
     * @param string $cron_job_name The name of the cron job to check.
     * @return string
     * @since 4.3.4
     */
    public function isCronJobRunning($cron_job_name) {
        $crons = _get_cron_array();
        if (!$crons) {
            return 'Cron jobs are not enabled or no cron jobs are scheduled.';
        }

        foreach ($crons as $timestamp => $cron_hooks) {
            if (isset($cron_hooks[$cron_job_name])) {
                $next_run_time = wp_date('m-d-Y g:i A', $timestamp); // Format the timestamp in 12-hour format
                return 'The cron job ' . esc_html($cron_job_name) . ' is scheduled. Next run time: ' . esc_html($next_run_time);
            }
        }

        return 'The cron job ' . esc_html($cron_job_name) . ' is not scheduled.';
    }


    /**
     * System Info Page
     *
     * System info page html.
     *
     * @since 1.0.0
     */
    public function ftsSystemInfoPage() {
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
<?php echo $this->ftsSystemInfoSupportTicket(); ?></textarea>
            </form>
        </div>
        </div>

        <?php
    }

public function ftsSystemInfoSupportTicket(){ ob_start() ?>
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
PHP Memory Limit: <?php echo esc_html( \ini_get( 'memory_limit' ) ) . "\n"; ?>
WP_DEBUG: <?php echo esc_html( \defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" ); ?>

-- Cron Job Status:
Cron Jobs: <?php echo esc_html( \defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ? 'Disabled' . "\n" : 'Enabled' . "\n" ); ?>
<?php echo esc_html( $this->isCronJobRunning('fts_clear_cache_event') ) . "\n"; ?>

-- FTS Settings->General Options:
<?php $fts_cachetime = $this->settingsFunctions->fts_get_option( 'fts_cache_time' ) ? $this->settingsFunctions->fts_get_option( 'fts_cache_time' ) : '86400'; ?>
Cache time: <?php echo esc_html( $this->feedCache->ftsCachetimeAmount( $fts_cachetime ) ) . "\n"; ?>

-- Webserver Configuration:
PHP Version: <?php
echo PHP_VERSION . "\n";
$my_request = stripslashes_deep( $_SERVER );
?>
Web Server Info: <?php echo esc_html( $my_request['SERVER_SOFTWARE'] ) . "\n"; ?>

-- PHP Configuration:
Upload Max Size: <?php echo esc_html( \ini_get( 'upload_max_filesize' ) . "\n" ); ?>
Post Max Size: <?php echo esc_html( \ini_get( 'post_max_size' ) . "\n" ); ?>
Upload Max Filesize: <?php echo esc_html( \ini_get( 'upload_max_filesize' ) . "\n" ); ?>
Time Limit: <?php echo esc_html( \ini_get( 'max_execution_time' ) . "\n" ); ?>
Max Input Vars: <?php echo esc_html( \ini_get( 'max_input_vars' ) . "\n" ); ?>
Allow URL File Open: <?php echo esc_html( \ini_get( 'allow_url_fopen' ) ? 'On (' . \ini_get( 'display_errors' ) . ')' : 'N/A' ); ?><?php echo "\n"; ?>
Display Erros: <?php echo esc_html( \ini_get( 'display_errors' ) ? 'On (' . \ini_get( 'display_errors' ) . ')' : 'N/A' ); ?><?php echo "\n"; ?>

-- PHP Extensions:
json:                     <?php echo ( \extension_loaded( 'json' ) ) ? 'Your server supports json.' : 'Your server does not support json. Please contact your host to activate or install this php extension.'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( \function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( \function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>
curl_multi:               <?php echo ( \function_exists( 'curl_multi_select' ) ) ? 'Your server supports curl_multi_select.' : 'Your server does not support curl_multi_select. Please contact your host to activate or install this php function.'; ?><?php echo "\n"; ?>

-- Active Plugins:
<?php
$plugins        = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );
foreach ( $plugins as $plugin_path => $plugin ) {
    // If the plugin isn't active, don't show it.
    if ( ! \in_array( $plugin_path, $active_plugins, true ) ) {
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
        if ( ! \array_key_exists( $plugin_base, $active_plugins ) ) {
            continue;
        }

        $plugin = get_plugin_data( $plugin_path );

        echo esc_html( $plugin['Name'] . ' :' . $plugin['Version'] . "\n" );

    }
endif;

if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
    $feed_them_social_license_key = get_option( 'feed_them_social_license_keys' );
    // Use this for testing print_r( $feed_them_social_license_key );
    ?>

-- FTS Plugins Active & License Validation
Premium: <?php echo $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_premium']['license_status']) && $feed_them_social_license_key['feed_them_social_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
Key: <?php echo isset($feed_them_social_license_key['feed_them_social_premium']['license_key']) ? $feed_them_social_license_key['feed_them_social_premium']['license_key'] . "\n" : 'NA' . "\n"; ?>
<?php if( isset($feed_them_social_license_key['feed_them_social_premium']['license_error'])){
echo self::ERROR_CODE_TEXT . $feed_them_social_license_key['feed_them_social_premium']['license_error'] . "\n";
} ?>

TikTok Premium: <?php echo $this->feedFunctions->isExtensionActive( 'feed_them_social_tiktok_premium' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_tiktok_premium']['license_status']) && $feed_them_social_license_key['feed_them_social_tiktok_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
Key: <?php echo isset($feed_them_social_license_key['feed_them_social_tiktok_premium']['license_key']) ? $feed_them_social_license_key['feed_them_social_tiktok_premium']['license_key'] . "\n" : 'NA' . "\n"; ?>
<?php if( isset($feed_them_social_license_key['feed_them_social_tiktok_premium']['license_error'])){
echo self::ERROR_CODE_TEXT . $feed_them_social_license_key['feed_them_social_tiktok_premium']['license_error'] . "\n";
} ?>

Combined Streams: <?php echo $this->feedFunctions->isExtensionActive( 'feed_them_social_combined_streams' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_combined_streams']['license_status']) && $feed_them_social_license_key['feed_them_social_combined_streams']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
Key: <?php echo isset($feed_them_social_license_key['feed_them_social_combined_streams']['license_key']) ? $feed_them_social_license_key['feed_them_social_combined_streams']['license_key'] . "\n" : 'NA' . "\n"; ?>
<?php if( isset($feed_them_social_license_key['feed_them_social_combined_streams']['license_error'])){
echo self::ERROR_CODE_TEXT . $feed_them_social_license_key['feed_them_social_combined_streams']['license_error'] . "\n";
} ?>

Facebook Reviews: <?php echo $this->feedFunctions->isExtensionActive( 'feed_them_social_facebook_reviews' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
Valid: <?php echo isset($feed_them_social_license_key['feed_them_social_facebook_reviews']['license_status']) && $feed_them_social_license_key['feed_them_social_facebook_reviews']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
Key: <?php echo isset($feed_them_social_license_key['feed_them_social_facebook_reviews']['license_key']) ? $feed_them_social_license_key['feed_them_social_facebook_reviews']['license_key'] . "\n" : 'NA' . "\n"; ?>
<?php if( isset($feed_them_social_license_key['feed_them_social_facebook_reviews']['license_error'])){
echo self::ERROR_CODE_TEXT . $feed_them_social_license_key['feed_them_social_facebook_reviews']['license_error'] . "\n";
} ?>

Carousel Premium: <?php echo $this->feedFunctions->isExtensionActive( 'feed_them_carousel_premium' ) ? 'Active' . "\n" : 'No' . "\n"; ?>
Valid: <?php echo isset($feed_them_social_license_key['feed_them_carousel_premium']['license_status']) && $feed_them_social_license_key['feed_them_carousel_premium']['license_status'] === 'valid' ? 'Yes' . "\n" : 'No' . "\n"; ?>
Key: <?php echo isset($feed_them_social_license_key['feed_them_carousel_premium']['license_key']) ? $feed_them_social_license_key['feed_them_carousel_premium']['license_key'] . "\n" : 'NA' . "\n"; ?>
<?php if( isset($feed_them_social_license_key['feed_them_carousel_premium']['license_error'])){
echo self::ERROR_CODE_TEXT . $feed_them_social_license_key['feed_them_carousel_premium']['license_error'] . "\n";
} ?>

<?php } // close is fts active ?>### End System Info ###<?php return ob_get_clean();
    } // end ftsSystemInfoSupportTicket

}//end class
