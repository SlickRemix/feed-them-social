<?php

namespace feedthemsocial;

// uncomment this line for testing
//set_site_transient( 'update_plugins', null );

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Allows plugins to use their own update API.
 *
 * @author Pippin Williamson
 * @version 1.6.5
 */
class updater_init {

    //This info is for creating the updater license page and updater license options
    public $updater_options_info = array();
    //List of Premium Plugins
    public $prem_plugins_list = array();

    public function __construct() {
        // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
        /*if (!defined('SLICKREMIX_STORE_URL')) {
            define('SLICKREMIX_STORE_URL', 'http://www.slickremix.com/'); // you should use your own CONSTANT name, and be sure to replace it throughout this file.
        }*/

        // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
        //New Updater
        //include(dirname(__FILE__) . '/namespaced_updater_overrides.php');

        if (!function_exists('is_plugin_active')) {
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');
        }

        $this->updater_options_info = array(
            //Plugins
            'author' => 'slickremix',
            //Store URL is where the premium plugins are located.
            'store_url' => 'https://www.slickremix.com/',
            //Menu Slug for the plugin the update license page will be added to.
            'main_menu_slug' => 'feed-them-settings-page',
            //Slug to be used for license page
            'license_page_slug' => 'fts-license-page',
            //Settings Section name for license page options
            'setting_section_name' => 'fts_license_options',
            //Settings Option name (This will house an array of options but save it as one 'option' in WordPress database)
            'setting_option_name' => 'feed_them_social_license_keys',
        );

        //List of Plugins! Used for License check and Plugin License page.
        $this->prem_plugins_list = array(
            'feed_them_social_premium' => array(
                //Title MUST match title of product in EDD store on site plugin is being sold
                'title' => 'Feed Them Social Premium',
                'plugin_url' => 'feed-them-premium/feed-them-premium.php',
                'demo_url' => 'http://feedthemsocial.com/facebook-page-feed-demo/',
                'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/',
            ),
            'feed_them_social_combined_streams' => array(
                'title' => 'Feed Them Social Combined Streams',
                'plugin_url' => 'feed-them-social-combined-streams/feed-them-social-combined-streams.php',
                'demo_url' => 'http://feedthemsocial.com/feed-them-social-combined-streams/',
                'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-combined-streams/',
            ),
            'feed_them_social_facebook_reviews' => array(
                'title' => 'Feed Them Social Facebook Reviews',
                'plugin_url' => 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php',
                'demo_url' => 'http://feedthemsocial.com/facebook-page-reviews-demo/',
                'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-facebook-reviews/',
            ),
            'feed_them_carousel_premium' => array(
                'title' => 'Feed Them Carousel Premium',
                'plugin_url' => 'feed-them-carousel-premium/feed-them-carousel-premium.php',
                'demo_url' => 'http://feedthemsocial.com/facebook-carousels-or-sliders/',
                'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-carousel-premium/',
            ),
            'fts_bar' => array(
                'title' => 'Feed Them Social Bar',
                'plugin_url' => 'fts-bar/fts-bar.php',
                'demo_url' => 'http://feedthemsocial.com/fts-bar/',
                'purchase_url' => 'https://www.slickremix.com/downloads/fts-bar/',
            ),
        );

        //Create License Page for main plugin.
        new updater_license_page($this->updater_options_info, $this->prem_plugins_list);

        add_action('plugins_loaded', array($this, 'remove_old_updater_actions'), 0);

        //Run Update Check Class
        add_action('plugins_loaded', array($this, 'plugin_updater_check_init'), 11, 1);

    }

    /**
     * Update old License Keys Check
     *
     * Check if the old License Keys options need to be converted to new array method. (Backwards Compatibility)
     *
     * @param $settings_array
     * @since 1.6.5
     */
    function update_old_license_keys_check($settings_array) {
        $option_update_needed = false;

        //If Setting array is not set then set to array
        if (!$settings_array) {
            $settings_array = array();
        }

        //Remove Old Updater Actions
        foreach ($this->prem_plugins_list as $plugin_key => $prem_plugin) {
            //Set Old Key (for EDD sample remove this code. This is only here because we messed up originally)
            $old_plugin_key = ($plugin_key == 'feed_them_social_facebook_reviews') ? 'feed-them-social-facebook-reviews' : $plugin_key;

            //Backwards Compatibility for Pre-1-click license page will get removed on first save in Sanitize function.
            $old_license = get_option($old_plugin_key . '_license_key');
            $old_status = get_option($old_plugin_key . '_license_status');

            //Is old License Key set?
            if ($old_license) {
                //Set New Key
                $settings_array[$plugin_key]['license_key'] = $old_license;

                delete_option($old_plugin_key . '_license_key');
                //set option update needed to true so we know we need to update settings array;
                $option_update_needed = true;
            }
            //Is old Status set?
            if ($old_status) {
                $settings_array[$plugin_key]['license_status'] = $old_status;

                delete_option($old_plugin_key . '_license_status');
                //set option update needed to true so we know we need to update settings array;
                $option_update_needed = true;
            }
        }

        //Re-save Settings array with new options
        if ($option_update_needed == true) {
            update_option($this->updater_options_info['setting_option_name'], $settings_array);
        }
    }

    /**
     * Remove Old Updater Actions
     *
     * Removes any actions previous set by old updaters
     *
     * @since 1.5.6
     */
    function remove_old_updater_actions() {
        //Remove Old Updater Actions
        foreach ($this->prem_plugins_list as $plugin_key => $prem_plugin) {
            if (has_action('plugins_loaded', $plugin_key . '_plugin_updater')) {
                remove_action('plugins_loaded', $plugin_key . '_plugin_updater', 10);
            }
        }

        //License Key Array Option
        $settings_array = get_option($this->updater_options_info['setting_option_name']);

        //If Settings array isn't set see if old licence keys exist
        if (!$settings_array) {
            //Backwards Compatibility with old 'Sample Plugin' (only Use if Necessary)
            $this->update_old_license_keys_check($settings_array);
        }
    }

    /**
     * Premium Plugin Updater Check Initialize
     *
     * Licensing and update code
     *
     * @since 1.5.6
     */
    function plugin_updater_check_init() {

        $installed_plugins = get_plugins();

        /*echo '<pre style=" width: 500px; margin: 0 auto; text-align: left">';
                 print_r($this->updater_options_info['store_url']);
                 echo '</pre>';*/

        foreach ($this->prem_plugins_list as $plugin_identifier => $plugin_info) {

            if (isset($plugin_info['plugin_url']) && !empty($plugin_info['plugin_url']) && is_plugin_active($plugin_info['plugin_url'])) {

                $settings_array = get_option($this->updater_options_info['setting_option_name']);

                $license = isset($settings_array[$plugin_identifier]['license_key']) ? $settings_array[$plugin_identifier]['license_key'] : '';
                $status = isset($settings_array[$plugin_identifier]['license_status']) ? $settings_array[$plugin_identifier]['license_status'] : '';

                $plugin_path = plugin_dir_path(basename($plugin_info['plugin_url'], '.php'));

                //Build updater Array
                $plugin_details = array(
                    'version' => $installed_plugins[$plugin_info['plugin_url']]['Version'], // Current version number
                    'license' => trim($license),                     // License key (used get_option above to retrieve from DB)
                    'status' => $status,                       // License key Status (used get_option above to retrieve from DB)
                    'item_name' => $plugin_info['title'],      // Name of this plugin
                    'author' => $this->updater_options_info['author']    // Author of this plugin

                );

                // setup the updater
                new updater_check_class($this->updater_options_info['store_url'], $plugin_info['plugin_url'], $plugin_details, $plugin_identifier, $plugin_info['title']);
            }
        }
    }
}

?>