<?php
/**
 * Feed Them Social - Updater Check Init
 *
 * In the Free Version this is NOT an updater but displays the license page for users to see they can extend the Free plugin with Extensions
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\updater;

// uncomment this line for testing
// set_site_transient( 'update_plugins', null );
// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Allows plugins to use their own update API.
 *
 * (sample plugin version 1.6.5)
 *
 * @author Pippin Williamson
 * @version 1.6.5
 */
class UpdaterCheckInit {
    /**
     * Updater Options Info
     *
     * This info is for creating the updater license page and updater license options
     *
     * @var array
     */
    public $updaterOptionsInfo = array();

    /**
     * Premium Plugin List
     *
     * List of Premium Plugins!
     *
     * @var array
     */
    public $premPluginsList = array();

    /**
     * Feed Functions
     *
     * General Feed Functions to be used in most Feeds.
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Premium Extension List.
     *
     * A list of the Premium Extensions and its urls to SlickRemix.com.
     *
     * @var array
     */
    public $premExtensionList;

    /**
     * UpdaterCheckInit constructor.
     */
    public function __construct( $feedFunctions ) {
        // New Updater! - This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed!
        if ( ! \function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        // Premium Extension List.
        $this->premExtensionList = FEED_THEM_SOCIAL_PREM_EXTENSION_LIST;

        $this->updaterOptionsInfo = array(
            //Plugins
            'author' => 'slickremix',
            //Store URL is where the premium plugins are located.
            'store_url' => 'https://www.slickremix.com/',
            //Menu Slug for the plugin the update license page will be added to.
            'main_menu_slug' => 'edit.php?post_type=fts',
            //Slug to be used for license page
            'license_page_slug' => 'fts-license-page',
            //Settings Section name for license page options
            'setting_section_name' => 'fts_license_options',
            //Settings Option name (This will house an array of options but save it as one 'option' in WordPress database)
            'setting_option_name' => 'feed_them_social_license_keys',
        );

        //Create License Page for main plugin.
        new UpdaterLicensePage($this->updaterOptionsInfo, $this->feedFunctions);

        // Remove old updater actions first
        $this->removeOldUpdaterActions();

        // Initialize the plugin updater directly instead of hooking to plugins_loaded
        $this->pluginUpdaterCheckInit();
    }

    /**
     * Update old License Keys Check
     *
     * Check if the old License Keys options need to be converted to new array method. (Backwards Compatibility)
     *
     * @param array $settings_array the settings array!
     * @since 1.6.5
     */
    public function updateOldLicenseKeysCheck( $settings_array ) {
        $option_update_needed = false;

        //If Setting array is not set then set to array
        $settings_array = $settings_array ?? array();


        // Remove Old Updater Actions!
        foreach ( FEED_THEM_SOCIAL_PREM_EXTENSION_LIST as $plugin_key => $prem_plugin ) {
            // Set Old Key (for EDD sample remove this code. This is only here because we messed up originally)!
            $plugin_key = $plugin_key === 'feed_them_social_facebook_reviews' ? 'feed-them-social-facebook-reviews' : $plugin_key;

            //Backwards Compatibility for Pre-1-click license page will get removed on first save in Sanitize function.
            $old_license = get_option($plugin_key . '_license_key');
            $old_status = get_option($plugin_key . '_license_status');

            //Is old License Key set?
            if ($old_license) {
                //Set New Key
                $settings_array[$plugin_key]['license_key'] = $old_license;

                delete_option($plugin_key . '_license_key');
                //set option update needed to true so we know we need to update settings array;
                $option_update_needed = true;
            }
            //Is old Status set?
            if ($old_status) {
                $settings_array[$plugin_key]['license_status'] = $old_status;

                delete_option($plugin_key . '_license_status');
                //set option update needed to true so we know we need to update settings array;
                $option_update_needed = true;
            }
        }

        // Re-save Settings array with new options!
        if ( $option_update_needed === true ) {
            update_option( $this->updaterOptionsInfo['setting_option_name'], $settings_array );
        }
    }

    /**
     * Remove Old Updater Actions
     *
     * Removes any actions previous set by old updaters
     *
     * @since 1.5.6
     */
    public function removeOldUpdaterActions() {
        // Remove Old Updater Actions!
        foreach ( FEED_THEM_SOCIAL_PREM_EXTENSION_LIST as $plugin_key => $prem_plugin ) {
            if ( has_action( 'plugins_loaded', $plugin_key . '_plugin_updater' ) ) {
                remove_action( 'plugins_loaded', $plugin_key . '_plugin_updater', 10 );
            }
        }

        //License Key Array Option
        $settings_array = get_option($this->updaterOptionsInfo['setting_option_name']);

        //If Settings array isn't set see if old licence keys exist
        if (!$settings_array) {
            //Backwards Compatibility with old 'Sample Plugin' (only Use if Necessary)
            $this->updateOldLicenseKeysCheck($settings_array);
        }
    }

    /**
     * Premium Plugin Updater Check Initialize
     *
     * Licensing and update code
     *
     * @since 1.5.6
     */
    public function pluginUpdaterCheckInit() {

        $installed_plugins = get_plugins();

        // Simple Checks print_r($this->updaterOptionsInfo['store_url']) and also print_r($this->updaterOptionsInfo)
        foreach ( $this->premExtensionList as $plugin_identifier => $plugin_info) {
            
            $is_active = isset($plugin_info['plugin_url']) && !empty($plugin_info['plugin_url']) && is_plugin_active($plugin_info['plugin_url']);

            if ($is_active) {

                $settings_array = get_option($this->updaterOptionsInfo['setting_option_name']);

                $license = $settings_array[$plugin_identifier]['license_key'] ?? '';
                $status = $settings_array[$plugin_identifier]['license_status'] ?? '';

                //Build updater Array
                $plugin_details = array(
                    'version' => $installed_plugins[$plugin_info['plugin_url']]['Version'], // Current version number
                    'license' => trim($license),                                            // License key (used get_option above to retrieve from DB)
                    'status' => $status,                                                    // License key Status (used get_option above to retrieve from DB)
                    'item_name' => $plugin_info['title'],                                   // Name of this plugin
                    'author' => $this->updaterOptionsInfo['author']                       // Author of this plugin
                );

                // setup the updater
                new UpdaterCheckClass($this->updaterOptionsInfo['store_url'], $plugin_info['plugin_url'], $plugin_identifier, $plugin_info['title'], $plugin_details);
            }
        }
    }
}
