<?php
/*
Plugin Name: Feed Them Social (Facebook, Instagram, Twitter, etc)
Plugin URI: http://slickremix.com/
Description: Create and display custom feeds for Facebook Groups, Facebook Pages, Facebook Events, Facebook Photos, Facebook Album Covers, Twitter, Instagram, Pinterest and more.
Version: 2.3.6
Author: SlickRemix
Author URI: http://slickremix.com/
Text Domain: feed-them-social
Domain Path: /languages
Requires at least: wordpress 4.5.0
Tested up to: WordPress 4.9.1
Stable tag: 2.3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * @package    	Feed Them
 * @category   	Core
 * @author      SlickRemix
 * @copyright  	Copyright (c) 2012-2017 SlickRemix

Need Support? http://www.slickremix.com/support/
*/

define('FEED_THEM_PLUGIN_PATH', plugins_url());
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
$fts_plugin_rel_url = plugin_dir_path(__FILE__);

/**
 *  FTS options on activation
 *
 * @since 2.2.4
 */
function fts_plugin_activation() {
    // we add an db option to check then delete the db option after activation and the cache has emptied.
    // the delete_option is on the feed-them-functions.php file at the bottom of the function fts_clear_cache_script
    add_option( 'Feed_Them_Social_Activated_Plugin', 'feed-them-social' );

}
//FTS Activation Function
register_activation_hook( __FILE__,'fts_plugin_activation');

/**
 * FTS Load Plugin options on activation check
 *
 * @since 2.2.4
 */
function feed_them_social_load_plugin() {

    if ( is_admin() && get_option( 'Feed_Them_Social_Activated_Plugin' ) == 'feed-them-social' ) {

        //Options List
        $activation_options = array(
            'fts-date-and-time-format' => 'one-day-ago',
            'fts-timezone' => 'America/New_York',
            'fts_clear_cache_developer_mode' => '86400',
        );

        foreach($activation_options as $option_key => $option_value){
            // We don't use update_option because we only want this to run for options that have not already been set by the user
            add_option($option_key, $option_value);
        }
    }
}
add_action( 'admin_init', 'feed_them_social_load_plugin' );

/**
 * FTS Delete Cache Main
 * Used to delete the cache on plugin activation and update wp hooks
 *
 * @since 2.3.0
 */
function fts_delete_cache_main(){
    global $wpdb;
    $not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%'));
    $expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%'));
    wp_reset_query();
}
/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 *
 * @param $upgrader_object Array
 * @param $options Array
 * @since 2.3.0
 */
function ftsocial_upe_upgrade_completed( $upgrader_object, $options ) {
    // The path to our plugin's main file
    $our_plugin = plugin_basename( __FILE__ );
    // If an update has taken place and the updated type is plugins and the plugins element exists
    if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
        // Iterate through the plugins being updated and check if ours is there
        foreach( $options['plugins'] as $plugin ) {
            if( $plugin == $our_plugin ) {
                // Set a transient to record that our plugin has just been updated
                set_transient( 'ftsocial_updated', 1 );
            }
        }
    }
}
add_action( 'upgrader_process_complete', 'ftsocial_upe_upgrade_completed', 10, 2 );

/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 * @since 2.3.0
 */
function ftsocial_upe_display_update_notice() {
    // Check the transient to see if we've just updated the plugin
    fts_delete_cache_main();
    if( get_transient( 'ftsocial_updated' ) ) {
        echo '<div class="notice notice-success updated is-dismissible"><p>' . __( 'Thanks for updating Feed Them Social. We have deleted the cache in our plugin so you can view any changes we have made.', 'feed-them-social' ) . '</p></div>';
        delete_transient( 'ftsocial_updated' );
    }
}
add_action( 'admin_notices', 'ftsocial_upe_display_update_notice' );

/**
 * Show a notice to anyone who has just installed the plugin for the first time
 * This notice shouldn't display to anyone who has just updated this plugin
 * @since 2.3.0
 */
function ftsocial_upe_display_install_notice() {
    // Check the transient to see if we've just activated the plugin
    if( get_transient( 'ftsocial_activated' ) ) {
        fts_delete_cache_main();
        echo '<div class="notice notice-success updated is-dismissible"><p>' . __( 'Thanks for installing Feed Them Social. To get started please view our <a href="admin.php?page=feed-them-settings-page">Settings</a> page.', 'feed-them-social' ) . '</p></div>';
        // Delete the transient so we don't keep displaying the activation message
        delete_transient( 'ftsocial_activated' );
    }
}
add_action( 'admin_notices', 'ftsocial_upe_display_install_notice' );

/**
 * Run this on activation
 * Set a transient so that we know we've just activated the plugin
 *
 * @since 2.3.0
 */
function ftsocial_activate() {
    set_transient( 'ftsocial_activated', 1 );
}
register_activation_hook( __FILE__, 'ftsocial_activate' );

/**
 * FTS System Version
 *
 * Returns current plugin version.
 *
 * @return mixed
 * @since 1.9.6
 */
function ftsystem_version() {
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}
/**
 * FTS Versions Needed
 *
 * Define minimum premium version allowed to be active with Free Version.
 *
 * @return array
 * @since 1.9.6
 */
function fts_versions_needed() {
    $fts_versions_needed = array(
        'feed-them-premium/feed-them-premium.php' => '1.5.3',
        'fts-bar/fts-bar.php' => '1.0.8',
        'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' => '1.0.0',
        'feed-them-carousel-premium/feed-them-carousel-premium.php' => '1.0.0',
    );
    return $fts_versions_needed;
}
// Make sure php version is greater than 5.3
if (function_exists('phpversion'))
    $phpversion = phpversion();
$phpcheck = '5.2.9';
if ($phpversion > $phpcheck) {
//Error Handler
    include($fts_plugin_rel_url . 'includes/error-handler.php');
    new feedthemsocial\fts_error_handler();
    /**
     * FTS Action Init
     *
     * @since 1.9.6
     */
    function fts_action_init() {
// Localization
        load_plugin_textdomain('feed-them-social', false, basename(dirname(__FILE__)) . '/languages');
    }
// Add actions
    add_action('init', 'fts_action_init');
// Include admin
    include($fts_plugin_rel_url . 'admin/feed-them-settings-page.php');
    include($fts_plugin_rel_url . 'admin/feed-them-facebook-style-options-page.php');
    include($fts_plugin_rel_url . 'admin/feed-them-twitter-style-options-page.php');
    include($fts_plugin_rel_url . 'admin/feed-them-instagram-style-options-page.php');
    include($fts_plugin_rel_url . 'admin/feed-them-pinterest-style-options-page.php');
    include($fts_plugin_rel_url . 'admin/feed-them-youtube-style-options-page.php');
// Include core files and classes
    include($fts_plugin_rel_url . 'includes/feed-them-functions.php');
    $load_fts = 'feedthemsocial\feed_them_social_functions';
    $load_fts = new $load_fts;
    $load_fts->init();

//Free Plugin License page.
include($fts_plugin_rel_url . 'admin/free-plugin-license-page.php');
    
// Include feeds
    include($fts_plugin_rel_url . 'admin/feed-them-system-info.php');
    include($fts_plugin_rel_url . 'feeds/facebook/facebook-feed.php');
    include($fts_plugin_rel_url . 'feeds/facebook/facebook-feed-post-types.php');
    $load_fb_fts = 'feedthemsocial\FTS_Facebook_Feed';
    new $load_fb_fts;
    include_once($fts_plugin_rel_url . 'feeds/twitter/twitter-feed.php');
    $load_tw_fts = 'feedthemsocial\FTS_Twitter_Feed';
    new $load_tw_fts;
    include_once($fts_plugin_rel_url . 'feeds/instagram/instagram-feed.php');
    include_once($fts_plugin_rel_url . 'feeds/pinterest/pinterest-feed.php');
    include_once($fts_plugin_rel_url . 'feeds/youtube/youtube-feed.php');
     //   include_once($fts_plugin_rel_url . 'feeds/twitter/vine-feed.php');
     //   $load_vn_fts = 'feedthemsocial\FTS_Vine_Feed';
     //   new $load_vn_fts;

} // end if php version check
else {
    // if the php version is not at least 5.3 do action
    deactivate_plugins('feed-them-social/feed-them.php');
    if ($phpversion < $phpcheck) {
        add_action('admin_notices', 'fts_required_php_check1');
        /**
         * FTS Required php Check
         *
         * @since 1.9.6
         */
        function fts_required_php_check1() {
            echo '<div class="error"><p>' . __('<strong>Warning:</strong> Your php version is ' . phpversion() . '. You need to be running at least 5.3 or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too.<br/><br/>If you are hosting with BlueHost or Godaddy and the php version above is saying you are running 5.2.17 but you are really running something higher please <a href="https://wordpress.org/support/topic/php-version-difference-after-changing-it-at-bluehost-php-config?replies=4" target="_blank">click here for the fix</a>. If you cannot get it to work using the method described in the link please contact your host provider and explain the problem so they can fix it.', 'my-theme') . '</p></div>';
        }
    }
} // end fts_required_php_check

// Include our own Settings link to plugin activation and update page.
add_filter("plugin_action_links_" . plugin_basename(__FILE__), "fts_free_plugin_actions", 10, 4);

/**
 * FTS Plugin Actions
 *
 * @param $actions
 * @param $plugin_file
 * @param $plugin_data
 * @param $context
 * @return mixed
 * @since 1.9.6
 */
function fts_free_plugin_actions($actions, $plugin_file, $plugin_data, $context) {
    array_unshift(
        $actions, "<a href=\"" . __('https://wordpress.org/support/plugin/feed-them-social') . "\">" . __("Support") . "</a> | <a href=\"" . menu_page_url('feed-them-settings-page', false) . "\">" . __("Settings") . "</a>"

    );
    return $actions;
}

// Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
add_filter("plugin_row_meta", "fts_free_add_leave_feedback_link", 10, 2);

/**
 * FTS Add Leave Feedback Link
 *
 * @param $links
 * @param $file
 * @return mixed
 * @since 1.9.6
 */
function fts_free_add_leave_feedback_link($links, $file) {
    if ($file === plugin_basename(__FILE__)) {
        $links['feedback'] = '<a href="http://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank">' . __('Rate Plugin', 'feed-them-premium') . '</a>';
        // $links['support'] = '<a href="http://www.slickremix.com/support-forum/forum/feed-them-social-2/" target="_blank">' . __('Get support', 'feed-them-premium') . '</a>';
        //  $links['plugininfo']  = '<a href="plugin-install.php?tab=plugin-information&plugin=feed-them-premium&section=changelog&TB_iframe=true&width=640&height=423" class="thickbox">' . __( 'Plugin info', 'gd_quicksetup' ) . '</a>';
    }
    return $links;
}

/**
 * Class feed_them_social_functions
 */
class feed_them_social_functions {
    /**
     * Register Settings
     *
     * @since 1.9.6
     */
    function register_settings() {
    }
}?>