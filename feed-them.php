<?php

/**
 * Feed Them Social Class (Main Class)
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social (Facebook, Instagram, Twitter, etc)
 * Plugin URI: http://feedthemsocial.com/
 * Description: Create and display custom feeds for Facebook Groups, Facebook Pages, Facebook Events, Facebook Photos, Facebook Album Covers, Twitter, Instagram, Pinterest and more.
 * Version: 2.4.0
 * Author: SlickRemix
 * Author URI: https://slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: wordpress 4.0.0
 * Tested up to: WordPress 4.9.5
 * Stable tag: 2.4.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    2.4.0
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2018 SlickRemix
 *
 * Need Support? http://www.slickremix.com/my-account
 */

final class Feed_Them_Social {

    /**
     * Main Instance of Feed Them Social
     * @var
     */
    private static $instance;

    /**
     * Create Instance of Feed Them Social
     *
     * @since 1.0.0
     */
    public static function instance() {
        if (!isset(self::$instance) && !(self::$instance instanceof Feed_Them_Social)) {
            self::$instance = new Feed_Them_Social;

            if (!function_exists('is_plugin_active'))
                require_once(ABSPATH . '/wp-admin/includes/plugin.php');

            // Third check the php version is not less than 5.2.9
            // Make sure php version is greater than 5.3
            if (function_exists('phpversion'))
                $phpversion = phpversion();
            $phpcheck = '5.2.9';
            if ($phpversion > $phpcheck) {
                // Add actions
                add_action('init', array(self::$instance, 'fts_action_init'));
            } // end if php version check
            else {
                // if the php version is not at least 5.3 do action
                deactivate_plugins('feed-them-social/feed-them-social.php');
                if ($phpversion < $phpcheck) {
                    add_action('admin_notices', array(self::$instance, 'fts_required_php_check1'));

                }
            } // end ftg_required_php_check

            register_activation_hook(__FILE__, array(self::$instance, 'fts_activate'));

            add_action('admin_init', array(self::$instance, 'feed_them_social_load_plugin'));
            add_action('admin_notices', array(self::$instance, 'fts_install_notice'));
            add_action('admin_notices', array(self::$instance, 'fts_update_notice'));
            add_action('upgrader_process_complete', array(self::$instance, 'fts_upgrade_completed', 10, 2));

            // Include our own Settings link to plugin activation and update page.
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(self::$instance, 'fts_free_plugin_actions'), 10, 4);

            // Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
            add_filter('plugin_row_meta', array(self::$instance, 'fts_leave_feedback_link'), 10, 2);

            //Setup Constants for FTS
            self::$instance->setup_constants();
            //add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

            //Include the files
            self::$instance->includes();

            //Error Handler
            self::$instance->error_handler = new feedthemsocial\fts_error_handler();

            //FTS Custom Post Type
            //self::$instance->fts_custom_post_type = new feedthemsocial\FTS_Custom_Post_Type();

            //Core (and load init)
            self::$instance->core_functions = new feedthemsocial\feed_them_social_functions();

            //Free Plugin License page.
            self::$instance->updater = new feedthemsocial\updater_init();

            //Facebook Class
            self::$instance->facebook_feed = new feedthemsocial\FTS_Facebook_Feed();

            //Twitter Class
            self::$instance->twitter_feed = new feedthemsocial\FTS_Twitter_Feed();

            //Instagram
            self::$instance->instagram_feed = new feedthemsocial\FTS_Instagram_Feed();

            //Pinterest
            self::$instance->pinterest_feed = new feedthemsocial\FTS_Pinterest_Feed();

            //Youtube
            self::$instance->pinterest_feed = new feedthemsocial\FTS_Youtube_Feed_Free();
        }

        return self::$instance;
    }

    /**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     *
     * @param $upgrader_object Array
     * @param $options Array
     * @since 1.0.0
     */
    function fts_upgrade_completed($upgrader_object, $options) {
        // The path to our plugin's main file
        $our_plugin = plugin_basename(__FILE__);
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
            // Iterate through the plugins being updated and check if ours is there
            foreach ($options['plugins'] as $plugin) {
                if ($plugin == $our_plugin) {
                    // Set a transient to record that our plugin has just been updated
                    set_transient('fts_updated', 1);
                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     * @since 1.0.0
     */
    function fts_update_notice() {
        // Check the transient to see if we've just updated the plugin
        if (get_transient('fts_updated')) {
            echo '<div class="notice notice-success updated is-dismissible"><p>' . __('Thanks for updating Feed Them Social. We have deleted the cache in our plugin so you can view any changes we have made.', 'feed-them-social') . '</p></div>';
            delete_transient('fts_updated');
        }
    }

    /**
     * Show a notice to anyone who has just installed the plugin for the first time
     * This notice shouldn't display to anyone who has just updated this plugin
     * @since 1.0.0
     */
    function fts_install_notice() {
        // Check the transient to see if we've just activated the plugin
        if (get_transient('fts_activated')) {
            echo '<div class="notice notice-success updated is-dismissible"><p>' . __('Thanks for installing Feed Them Social. To get started please view our <a href="admin.php?page=feed-them-settings-page">Settings</a> page.', 'feed-them-social') . '</p></div>';
            // Delete the transient so we don't keep displaying the activation message
            delete_transient('fts_activated');
        }
    }

    /**
     * Run this on activation
     * Set a transient so that we know we've just activated the plugin
     *
     * @since 1.0.0
     */
    function fts_activate() {
        set_transient('fts_activated', 1);

        // we add an db option to check then delete the db option after activation and the cache has emptied.
        // the delete_option is on the feed-them-functions.php file at the bottom of the function ftg_clear_cache_script
        add_option('Feed_Them_Social_Activated_Plugin', 'feed-them-social');
    }

    /**
     * Setup Constants
     *
     * Setup plugin constants for plugin
     *
     * @since 1.0.0
     */
    private function setup_constants() {
        // Makes sure the plugin is defined before trying to use it
        if (!function_exists('is_plugin_active'))
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

        $plugin_data = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        // Free Version Plugin version
        if (!defined('FEED_THEM_SOCIAL_VERSION')) {
            define('FEED_THEM_SOCIAL_VERSION', $plugin_version);
        }

        // Plugin Folder Path
        if (!defined('FEED_THEM_SOCIAL_PLUGIN_PATH')) {
            define('FEED_THEM_SOCIAL_PLUGIN_PATH', plugins_url());
        }
        // Plugin Directoy Path
        if (!defined('FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR')) {
            define('FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR', plugin_dir_path(__FILE__));
        }

        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            // Plugin Directoy Path
            if (!defined('FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR')) {
                define('FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR', WP_PLUGIN_DIR . '/feed-them-premium/feed-them-premium.php');
            }
        }
        // Define constants:
        if (!defined('MY_TEXTDOMAIN')) {
            define('MY_TEXTDOMAIN', 'feed-them-social');
        }
    }

    /**
     * Includes Files
     *
     * Include files needed for Feed Them Social
     *
     * @since 1.0.0
     */
    private function includes() {

        //include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-metabox-options.php');

        //Custom Post Type
        //include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/fts-cpt-class.php');

        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/error-handler.php');

        // Core Class
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-them-functions.php');
        $load_fts = 'feedthemsocial\feed_them_social_functions';
        $load_fts = new $load_fts;
        $load_fts->init();

        //Admin Pages
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-system-info.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-settings-page.php');

        //Feed Option Pages
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-facebook-style-options-page.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-twitter-style-options-page.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-instagram-style-options-page.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-pinterest-style-options-page.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/feed-them-youtube-style-options-page.php');

        //Updater Classes
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-license-page.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-class.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-init.php');

        //Feed Classes
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/facebook/facebook-feed.php');
        include(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/facebook/facebook-feed-post-types.php');
        $load_fb_fts = 'feedthemsocial\FTS_Facebook_Feed';
        new $load_fb_fts;
        include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/twitter/twitter-feed.php');
        $load_tw_fts = 'feedthemsocial\FTS_Twitter_Feed';
        new $load_tw_fts;
        include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/instagram/instagram-feed.php');
        include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/pinterest/pinterest-feed.php');

        include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/youtube/youtube-feed.php');

        //Steemit API
        //include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/steemit/slickremix-steem-php/SteemLayer.php');
        //include_once(FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/steemit/slickremix-steem-php/SteemApi.php');
    }

    /**
     * FTS Action Init
     *
     * Loads language files
     *
     * @since 1.0.0
     */
    function fts_action_init() {
        // Localization
        load_plugin_textdomain('feed-them-social', false, basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * FTS Required php Check
     *
     * Are they running proper PHP version
     *
     * @since 1.0.0
     */
    function fts_required_php_check1() {
        echo '<div class="error"><p>' . __('<strong>Warning:</strong> Your php version is ' . phpversion() . '. You need to be running at least 5.3 or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too.<br/><br/>If you are hosting with BlueHost or Godaddy and the php version above is saying you are running 5.2.17 but you are really running something higher please <a href="https://wordpress.org/support/topic/php-version-difference-after-changing-it-at-bluehost-php-config?replies=4" target="_blank">click here for the fix</a>. If you cannot get it to work using the method described in the link please contact your host provider and explain the problem so they can fix it.', 'feed-them-social') . '</p></div>';
    }

    /**
     * FTS Plugin Actions
     *
     * Loads links in the Plugins page in Wordpress Dashboard
     *
     * @param $actions
     * @param $plugin_file
     * @param $plugin_data
     * @param $context
     * @return mixed
     * @since 1.0.0
     */
    function fts_free_plugin_actions($actions, $plugin_file, $plugin_data, $context) {
        array_unshift(
            $actions, '<a href="admin.php?page=feed-them-settings-page">' . __('Settings') .'</a> | <a href="' . __('https://www.slickremix.com/support/') . '">' . __('Support') . '</a>'

        );
        return $actions;
    }

    /**
     * FTS Leave Feedback Link
     *
     * Link to add feedback for plugin
     *
     * @param $links
     * @param $file
     * @return mixed
     * @since 1.0.0
     */
    function fts_leave_feedback_link($links, $file) {
        if ($file === plugin_basename(__FILE__)) {
            $links['feedback'] = '<a href="http://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank">' . __('Rate Plugin', 'feed-them-social') . '</a>';
            // $links['support'] = '<a href="http://www.slickremix.com/support-forum/forum/feed-them-social-2/" target="_blank">' . __('Get support', 'feed-them-premium') . '</a>';
            //  $links['plugininfo']  = '<a href="plugin-install.php?tab=plugin-information&plugin=feed-them-premium&section=changelog&TB_iframe=true&width=640&height=423" class="thickbox">' . __( 'Plugin info', 'gd_quicksetup' ) . '</a>';
        }
        return $links;
    }

    /**
     * FTS Load Plugin
     *
     * Load plugin options on activation check
     *
     * @since 1.0.0
     */
    function feed_them_social_load_plugin() {

        if (is_admin() && get_option('Feed_Them_Social_Activated_Plugin') == 'feed-them-social') {

            //Options List
            $activation_options = array(
                'fts-date-and-time-format' => 'one-day-ago',
                'fts_clear_cache_developer_mode' => '86400',
            );

            foreach ($activation_options as $option_key => $option_value) {
                // We don't use update_option because we only want this to run for options that have not already been set by the user
                add_option($option_key, $option_value);
            }
        }
    }
}

/**
 * Feed Them Social
 *
 * Start it up!
 *
 * @return feed_them_social
 * @since 1.0.0
 */
function feed_them_social() {
    return Feed_Them_Social::instance();
}

//Initiate Feed Them Social
feed_them_social();
?>