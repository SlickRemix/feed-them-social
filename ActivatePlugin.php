<?php
/**
 * Activate Plugin
 *
 * Class Feed Them Social Load Plugin Class.
 *
 * @class    LoadPlugin
 * @version  3.0.0
 * @package  FeedThemSocial/Core
 * @category Class
 * @author   SlickRemix
 */
namespace feedthemsocial;

use feedthemsocial\includes\DebugLog;
use feedthemsocial\admin\cron_jobs\CronJobs;
use feedthemsocial\includes\ErrorHandler;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Activate Plugin
 */
class ActivatePlugin {

    /**
     * Activate Plugin Constructor
     *
     * @since 4.0.0
     */
    public function __construct() {
        //Pre-Activate Plugin Checks.
        $this->preActivatePluginChecks();
    }

    /**
     * Pre-Activate Plugin Checks
     *
     * Before plugin activates do checks. Deactivate plugin if checks fail to prevent taking down a site.
     *
     * @since 1.0.0
     */
    public function preActivatePluginChecks() {
        if ( ! \function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        // If PHP version is too low, deactivate the plugin and show a notice
        if (version_compare(PHP_VERSION, FEED_THEM_SOCIAL_MIN_PHP, '<')) {
            deactivate_plugins('feed-them-social/feed-them-social.php');
            add_action('admin_notices', array($this, 'failedPhpVersionNotice'));
        }
        // Uncomment this to test. PHP Version check.
        // add_action( 'admin_notices', array( $this, 'failedPhpVersionNotice' ) );
    }

    /**
     * Add Action Filters
     *
     * Load up all our styles and js.
     *
     * @since 1.0.0
     */
    public function addActionsFilters() {
        // Register Activate Transient
        register_activation_hook( plugin_dir_path( __FILE__ ) . 'feed-them-social.php', array( $this, 'activateTransient' ) );

        // Display Install Notice Add Action.
        add_action( 'admin_notices', array( $this, 'displayInstallNotice' ) );

        // Display Update Notice Add Action.
        add_action( 'admin_notices', array( $this, 'displayUpdateNotice' ) );

        // Upgrade Completed Add Action.
        add_action( 'upgrader_process_complete', array( $this, 'upgradeCompleted' ), 10, 2 );

        // Add Support/Settings links on plugin install page.
        add_filter( 'plugin_action_links_' . FEED_THEM_SOCIAL_PLUGIN_BASENAME, array( $this, 'freePluginInstallPageLinks' ), 10, 4 );

        // Add filters for feedback/Rate link on plugin install page.
        add_filter( 'plugin_row_meta', array( $this, 'leaveFeedbackLink' ), 10, 2 );

        // Set Plugin Timezone.
        add_action( 'admin_init', array( $this, 'setPluginReviewOption' ) );

        // Review/Rating notice option names
        $review_transient = 'fts_slick_rating_notice_waiting2024';
        $review_option    = 'fts_slick_rating_notice2024';

        // Set Review Transient.
        $this->setReviewTransient( $review_transient, $review_option );

        // Set Review Status.
        $this->setReviewStatus( $review_option, $review_transient );

        // Hook into extension activations to run version checks
        $this->addExtensionActivationHooks();

        // Plugin Activation Function.
        register_activation_hook( plugin_dir_path( __FILE__ ) . 'feed-them-social.php', array( $this, 'pluginActivation' ) );

    }

    /**
     *  Failed PHP Version Notice
     *
     * Show notice because the version of PHP running on server doesn't meet the plugin minimum requirements to run properly.
     *
     * @since 1.0.0
     */
    public function failedPhpVersionNotice() {
        echo \sprintf(
            esc_html__( '%1$sWarning:%2$s Your server PHP version is %3$s. You need to be running at least %4$s or greater to use this plugin. Please upgrade the php by contacting your host provider.%5$s', 'feed-them-social' ),
            '<div class="error"><p><strong>',
            '</strong>',
            PHP_VERSION,
            FEED_THEM_SOCIAL_MIN_PHP,
            '</p></div>'
        );
    }

    /**
     * Activate Transient
     *
     * Set transient for plugin activation.
     *
     * @since 1.0.0
     */
    public function activateTransient() {

        // Set Activation Transient.
        set_transient( 'fts_activated', 1 );

        // Set/Update FTS Version.
        update_option( 'fts_version', FEED_THEM_SOCIAL_VERSION );
    }

    /**
     * Display Install Notice
     *
     * Show a notice for first time plugin install. This notice shouldn't display to anyone who has just updated this plugin.
     *
     * @since 1.0.0
     */
    public function displayInstallNotice() {
        // Check the transient to see if we've just activated the plugin.
        if ( get_transient( 'fts_activated' ) ) {
            echo \sprintf(
                esc_html__( '%1$sThanks for installing Feed Them Social. To get started create a %2$sNew Feed%3$s.%4$s', 'feed-them-social' ),
                '<div class="notice notice-success updated is-dismissible"><p>',
                '<a href="' . esc_url( 'edit.php?post_type=fts&page=create-new-feed' ) . '">',
                '</a>',
                '</p></div>'
            );
            // Delete the transient so we don't keep displaying the activation message.
            delete_transient( 'fts_activated' );
        }
    }

    /**
     * Display Update Notice
     *
     * Show notice for plugin updated. This notice shouldn't display for first time plugin install.
     *
     * @since 1.0.0
     */
    public function displayUpdateNotice() {
        // Check the transient to see if we've just updated the plugin.
        if ( get_transient( 'fts_updated' ) ) {
            echo \sprintf(
                esc_html__( '%1$sThanks for updating Feed Them Social. The plugins cache has been cleared.%2$s', 'feed-them-social' ),
                '<div class="notice notice-success updated is-dismissible"><p>',
                '</p></div>'
            );
            delete_transient( 'fts_updated' );
        }
    }

    /**
     * Upgrade Completed
     *
     * This function runs when WordPress completes its upgrade process. It iterates through each plugin updated to see if ours is included.
     *
     * @param array $options Array The options.
     * @since 1.0.0
     */
    public function upgradeCompleted( $upgrader_object, $options ) {
        // The path to our plugin's main file.
        $our_plugin = FEED_THEM_SOCIAL_PLUGIN_BASENAME;

        // Check if $options is an array or object and process accordingly.
        if ( \is_array( $options ) ) {
            // Handle plugin installation.
            if ( isset( $options['action'], $options['type'], $options['plugin'] ) &&
                $options['action'] === 'install' &&
                $options['type'] === 'plugin' &&
                $options['plugin'] === $our_plugin ) {
                DebugLog::log( 'ActivatePlugin', 'Handle plugin installation/replacement (array).', true );
                $this->handlePluginEvent();
                return;
            }

            // Handle plugin updates.
            if ( isset( $options['action'], $options['type'], $options['plugins'] ) &&
                $options['action'] === 'update' &&
                $options['type'] === 'plugin' &&
                \in_array( $our_plugin, $options['plugins'], true ) ) {
                DebugLog::log( 'ActivatePlugin', 'Handle plugin updates (array).', true );
                $this->handlePluginEvent();
                return;
            }
        } elseif ( \is_object( $options ) ) {
            // Convert object to array for easier processing.
            $options = (array) $options;

            // Handle plugin installation.
            if ( isset( $options['action'], $options['type'], $options['plugin'] ) &&
                $options['action'] === 'install' &&
                $options['type'] === 'plugin' &&
                $options['plugin'] === $our_plugin ) {
                DebugLog::log( 'ActivatePlugin', 'Handle plugin installation/replacement (object).', true );
                $this->handlePluginEvent();
                return;
            }

            // Handle plugin updates.
            if ( isset( $options['action'], $options['type'], $options['plugins'] ) &&
                $options['action'] === 'update' &&
                $options['type'] === 'plugin' &&
                \in_array( $our_plugin, $options['plugins'], true ) ) {
                DebugLog::log( 'ActivatePlugin', 'Handle plugin updates (object).', true );
                $this->handlePluginEvent();
                return;
            }
        }

        // If $options doesn't match expected formats, log it for debugging.
        DebugLog::log( 'ActivatePlugin', 'Unexpected upgrader options', true );
    }

    /**
     * Handle Plugin Event
     *
     * Common logic for when the plugin is updated or replaced.
     */
    protected function handlePluginEvent() {
        // Set a transient to record that our plugin has just been updated/replaced.
        set_transient( 'fts_updated', 1 );

        // Set/Update new cron job for clearing cache.
        $this->setCronJob();

        // Run plugin version checks during upgrade
        $this->runPluginVersionCheck();
    }

    /**
     * Free Plugin Install Page Links
     *
     * Loads links in the Plugins page in WordPress Dashboard
     *
     * @param array $install_page_links What action to take.
     * @return mixed
     * @since 1.0.0
     */
    public function freePluginInstallPageLinks( $install_page_links ) {
        array_unshift(
            $install_page_links,
            '<a href="' . admin_url() . 'edit.php?post_type=fts">' . esc_html__( 'Feeds', 'feed-them-social' ) . '</a> | <a href="' . admin_url() . 'edit.php?post_type=fts&page=fts-settings-page">' . esc_html__( 'Settings',  'feed-them-social' ) . '</a> | <a target="_blank" href="' . esc_url( 'https://www.slickremix.com/support/' ) . '">' . esc_html__( 'Support',  'feed-them-social' ) . '</a>'
        );
        return $install_page_links;
    }

    /**
     * Leave Feedback Link
     *
     * Link to add feedback and Rate plugin.
     *
     * @param string $links The link to show.
     * @param string $file The file basename.
     * @return mixed
     * @since 1.0.0
     */
    public function leaveFeedbackLink( $links, $file ) {
        if ( $file === FEED_THEM_SOCIAL_PLUGIN_BASENAME ) {
            $links['feedback'] = \sprintf(
                esc_html__( '%1$sRate Plugin%2$s', 'feed-them-social' ),
                '<a href="' . esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/' ) . '" target="_blank">',
                '</a>'
            );
        }
        return $links;
    }

    /**
     *  Plugin Activation
     *
     * Add/Set option for Feed Them Social Activation.
     *
     * @since 1.0.0
     */
    public function pluginActivation() {

        // Activation options to add to the fts_settings array.
        $activation_options = array(
            'fts_cache_time'     => '86400',
            'fts_show_admin_bar' => '1',
            'date_time_format'   => 'one-day-ago',
            'timezone'           => 'America/New_York',
        );

        // Retrieve existing fts_settings or initialize an empty array.
        $fts_settings = get_option('fts_settings', array());

        // Only add keys from activation_options if they do not already exist in fts_settings.
        foreach ($activation_options as $option_key => $option_value) {
            if (!isset($fts_settings[$option_key])) {
                $fts_settings[$option_key] = $option_value;
            }
        }

        // Save the updated settings back to the database.
        update_option('fts_settings', $fts_settings);

        // Set/Update new cron job for clearing cache.
        $this->setCronJob();

        // Run plugin version checks during activation
        $this->runPluginVersionCheck();
    }


    /**
     * Set Plugin Review Option
     *
     * Set the reviews options for the plugin.
     *
     * @since 1.0.0
     */
    public function setPluginReviewOption() {

        // Review Nag Check.
        // Must run this on admin_init so no errors on multisite after clicking any buttons.
        $review_transient = 'fts_slick_rating_notice_waiting2024';
        $review_option    = 'fts_slick_rating_notice2024';
        $review_nag       = 'fts_slick_ignore_rating_notice_nag2024';
        $this->reviewNagCheck( $review_nag, $review_option, $review_transient );
    }

    /**
     * Review Nag Check
     *
     * Checks $_GET to see if the nag variable is set and what it's value is
     *
     * @param string $get See what the $_GET url is.
     * @param string $review_nag See if we are nagging 1 or 0.
     * @param string $review_option The option to check for.
     * @param string $review_transient Check the transient exists or not.
     * @since 1.0.8
     */
    public function reviewNagCheck( $review_nag, $review_option, $review_transient ) {

        if ( isset( $_GET[ $review_nag ] ) ) {

            // Includes pluggable.php to ensure that current_user_can can be used.
            if ( ! \function_exists( 'wp_get_current_user' ) ) {
                require_once ABSPATH . WPINC . '/pluggable.php';
            }

            if ( ! current_user_can( 'manage_options' ) || !isset( $_REQUEST['_wpnonce'] ) || wp_verify_nonce( $_REQUEST['_wpnonce'], 'ignore_rating_notice_nag2024' ) === false ) {
                wp_die(
                    __( 'Missing capability', 'feed-them-social' ),
                    __( 'Forbidden', 'feed-them-social' ),
                    array(
                        'response' => 403
                    )
                );
            }

            if ( $_GET[ $review_nag ] === '1' ) {
                update_option( $review_option, 'dismissed2024' );
            } elseif ( $_GET[ $review_nag ] === 'later' ) {
                $time = 2 * WEEK_IN_SECONDS;
                // For Testing use $time = 2;
                set_transient( $review_transient, 'fts-review-waiting2024', $time );
                update_option( $review_option, 'pending2024' );
            }
        }
    }

    /**
     * Set Review Transient
     *
     * Set a review transient if the notice has not been dismissed or has not been set yet.
     *
     * @param string $review_transient Check the transient exists or not.
     * @param string $review_option The option to check for.
     * @since 1.0.8
     */
    public function setReviewTransient( $review_transient, $review_option ) {
        $rating_notice_waiting = get_transient( $review_transient );
        $notice_status         = get_option( $review_option, false );

        if ( ! $rating_notice_waiting && ! ( $notice_status === 'dismissed2024' || $notice_status === 'pending2024') ) {
            $time = 2 * WEEK_IN_SECONDS;
            // For Testing use $time = 2;
            set_transient( $review_transient, 'fts-review-waiting2024', $time );
            update_option( $review_option, 'pending2024' );
        }
    }

    /**
     * Set Review Status
     *
     * Checks to see what the review status is.
     *
     * @param string $review_option The option to check for.
     * @param string $review_transient Check the transient exists or not.
     * @since 1.0.8
     */
    public function setReviewStatus( $review_option, $review_transient ) {
        $get_notice_status = get_option( $review_option, false );
        // Only display the notice if the time offset has passed and the user hasn't already dismissed it!.
        if ( get_transient( $review_transient ) !== 'fts-review-waiting2024' && $get_notice_status !== 'dismissed2024' ) {
            add_action( 'admin_notices', array( $this, 'ratingNoticeHtml' ) );
        }

        // Testing.
        // echo $get_notice_status;
        // echo ' ';
        // print_r( get_transient( $review_transient ) );
        // Uncomment this for testing the notice.
        // if ( !isset( $_GET['ftg_slick_ignore_rating_notice_nag2024'] ) ) {
        //  add_action( 'admin_notices', array($this, 'ratingNoticeHtml') );
        // }
    }

    /**
     * Ratings Notice HTML
     *
     * Generates the html for the admin review/rating notice.
     *
     * @since 1.0.8
     */
    public function ratingNoticeHtml() {
        // Only show to admins.
        if ( current_user_can( 'manage_options' ) ) {
            global $current_user;
            $user_id = $current_user->ID;

            // Used for testing:
            // print_r( get_user_meta( $user_id, 'fts_slick_ignore_rating_notice_nag2024' ) );
            // $all_meta_for_user = get_user_meta( $user_id );
            // print_r( $all_meta_for_user );

            // Has the user already clicked to ignore the message?
            if ( ! get_user_meta( $user_id, 'fts_slick_ignore_rating_notice_nag2024' )  && ! isset( $_GET['fts_slick_ignore_rating_notice_nag2024'] ) ) {

                $ignore_rating_notice_nag_nonce = wp_create_nonce( 'ignore_rating_notice_nag2024' );

                ?>
                <div class="ftg_notice ftg_review_notice">
                    <img src="<?php echo esc_url( plugins_url( 'feed-them-social/admin/images/feed-them-social-logo.png' ) ); ?>" alt="Feed Them Social">
                    <div class='fts-notice-text'>
                        <p><?php echo esc_html( 'It\'s great to see that you\'ve been using our Feed Them Social plugin for a while now. Hopefully you\'re happy with it!  If so, would you consider leaving a positive review? It really helps support the plugin and helps others discover it too!', 'feed-them-social' ); ?></p>
                        <p class="fts-links">
                            <a class="ftg_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Sure, I\'d love to', 'feed-them-social' ); ?></a>
                            <a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag2024' => '1', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'I\'ve already given a review', 'feed-them-social' ); ?></a>
                            <a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag2024' => 'later', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'Ask me later', 'feed-them-social' ); ?> </a>
                            <a class="ftg_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Not working, I need support', 'feed-them-social' ); ?></a>
                            <a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag2024' => '1', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'No thanks', 'feed-them-social' ); ?></a>
                        </p>

                    </div>
                </div>

                <?php
            }
        }
    }

    /**
     * Set Cron Job
     *
     * Run this function when the plugin is activated, updated or auto-updated.
     *
     * @since 4.3.4
     */
    public function setCronJob() {

        // Set new cron job for clearing cache.
        $cron_job = new CronJobs( null, null, null, null );
        $cron_job->ftsSetCronJob( 'clear-cache-set-cron-job', null, null );

        DebugLog::log( 'ActivatePlugin', 'Setting Cron Job from activate-plugin.php.', true );
    }

    /**
     * Add Extension Activation Hooks
     *
     * Hook into the activation of each FTS extension to run version checks.
     * This ensures that when any extension is activated, we check compatibility
     * with the current core plugin version.
     *
     * @since 4.3.9
     */
    public function addExtensionActivationHooks() {
        // Get the list of extensions we need to monitor
        $error_handler = new ErrorHandler();
        $extensions = $error_handler->ftsVersionsNeeded();

        // Hook into each extension's activation
        foreach ( $extensions as $plugin_path => $plugin_info ) {
            add_action( 'activate_' . $plugin_path, array( $this, 'runPluginVersionCheck' ) );
        }

        // Also hook into general plugin activation to catch any FTS extensions
        add_action( 'activated_plugin', array( $this, 'checkActivatedPlugin' ) );

        // Hook into auto-updates to run version checks when extensions are auto-updated
        add_action( 'automatic_updates_complete', array( $this, 'checkAutoUpdatedPlugins' ) );
    }

    /**
     * Check Activated Plugin
     *
     * Check if the activated plugin is an FTS extension and run version check if so.
     *
     * @param string $plugin The plugin that was activated.
     * @since 4.3.9
     */
    public function checkActivatedPlugin( $plugin ) {
        // Get the list of extensions we need to monitor
        $error_handler = new ErrorHandler();
        $extensions = array_keys( $error_handler->ftsVersionsNeeded() );

        // If the activated plugin is one of our extensions, run version check
        if ( \in_array( $plugin, $extensions, true ) ) {
            DebugLog::log( 'ActivatePlugin', 'FTS Extension activated: ' . $plugin . '. Running version check.', true );
            $this->runPluginVersionCheck();
        }
    }

    /**
     * Check Auto Updated Plugins
     *
     * Check if any FTS extensions were auto-updated and run version checks.
     *
     * @param array $update_results The results of the automatic updates.
     * @since 4.3.9
     */
    public function checkAutoUpdatedPlugins( $update_results ) {
        if ( ! empty( $update_results['plugin'] ) && \is_array( $update_results['plugin'] ) ) {
            $error_handler = new ErrorHandler();
            $extensions = array_keys( $error_handler->ftsVersionsNeeded() );

            foreach ( $update_results['plugin'] as $result ) {
                if ( ! empty( $result->item->plugin ) && \in_array( $result->item->plugin, $extensions, true ) ) {
                    DebugLog::log( 'ActivatePlugin', 'FTS Extension auto-updated: ' . $result->item->plugin . '. Will run version check.', true );
                    // An extension was updated, run the check.
                    $this->runPluginVersionCheck();
                    // We only need to run it once, even if multiple extensions were updated.
                    return;
                }
            }
        }
    }

    /**
     * Run Plugin Version Check
     *
     * Run plugin version checks during activation and upgrade processes.
     * This ensures that incompatible plugin versions are detected and handled
     * immediately when the plugin is activated or upgraded.
     *
     * @since 4.3.9
     */
    public function runPluginVersionCheck() {
        $error_handler = new ErrorHandler();
        $error_handler->ftsPluginVersionCheck();
    }

}
