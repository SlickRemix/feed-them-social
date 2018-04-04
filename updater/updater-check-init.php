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

        if (!function_exists('is_plugin_active')){
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
            'feed-them-social-facebook-reviews' => array(
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

        add_action('plugins_loaded', array($this,'remove_old_updater_actions'),0);

        //Run Update Check Class
        add_action('plugins_loaded', array($this,'plugin_updater_check_init'), 11, 1);
    }

    /**
     * Remove Old Updater Actions
     *
     * Romoves any actions previous set by old updaters
     *
     * @since 1.5.6
     */
    function remove_old_updater_actions(){
        //Remove Old Updater Actions
        foreach($this->prem_plugins_list as $plugin_key => $prem_plugin) {
            if($plugin_key === 'feed-them-social-facebook-reviews' && has_action('plugins_loaded', 'feed_them_social_facebook_reviews_plugin_updater')){
                remove_action('plugins_loaded', 'feed_them_social_facebook_reviews_plugin_updater', 10);
            }
            elseif(has_action('plugins_loaded', $plugin_key.'_plugin_updater')){
                remove_action('plugins_loaded', $plugin_key.'_plugin_updater', 10);
            }
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