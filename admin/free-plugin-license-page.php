<?php

namespace feedthemsocial;

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Class FTS Settings Page
 *
 * @package feedthemsocial
 * @since 2.1.6
 */
class fts_Free_Plugin_License_Page {

    public $prem_plugins = '';
    public $main_menu_slug = 'feed-them-settings-page';
    public $license_page_slug = 'fts-license-page';
    //used for settings section creation and actions
    public $setting_section_name = 'fts_license_options';
    //Used to save options array
    public $setting_option_name = 'feed_them_social_license_keys';
    public $plugin_identifier = '';
    public $store_url = '';

    // static variables
    private static $instance = false;

    /**
     * Construct
     *
     * FTS_settings_page constructor.
     *
     * @since 2.1.6
     */
    function __construct() {
        // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
        if (!defined('SLICKREMIX_STORE_URL')) {
            define('SLICKREMIX_STORE_URL', 'http://www.slickremix.com/'); // you should use your own CONSTANT name, and be sure to replace it throughout this file.
        }

        $this->store_url = SLICKREMIX_STORE_URL;

        //List of Plugins! Keep this up to date in order for showing what is available for FTS on Plugin License page.
        $this->prem_plugins = array(
            'feed_them_social_premium' => array(
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
                'title' => 'FTS Bar',
                'plugin_url' => 'fts-bar/fts-bar.php',
                'demo_url' => 'http://feedthemsocial.com/fts-bar/',
                'purchase_url' => 'https://www.slickremix.com/downloads/fts-bar/',
            ),
        );
        $this->install();
    }

    /**
     * Install Updater
     *
     * @since 2.1.6
     */
    function install() {
        if (!function_exists('is_plugin_active'))
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

        $prem_active = false;
        foreach ($this->prem_plugins as $plugin) {
            if (is_plugin_active($plugin['plugin_url'])) {
                $prem_active = true;
            }
        }

        add_action('admin_menu', array($this, 'license_menu'));
        add_action('admin_init', array($this, 'register_options'));
    }
        /**
         * Register Plugin License Page Options (overrides options from prem extensions updater files
         *
         * @since 2.1.6
         */
        function register_options() {
            //Create settings section
            add_settings_section($this->setting_section_name, '', null, $this->license_page_slug);

            //Register Option for settings array
            register_setting($this->license_page_slug, $this->setting_option_name, array($this, 'fts_sanitize_license'));

            //Add settings fields for each plugin/extension
            foreach ($this->prem_plugins as $key => $plugin) {
                //For plugins/extensions that are active
                if (is_plugin_active($plugin['plugin_url'])) {
                    $args = array(
                        'key' => $key,
                        'plugin_name' => $plugin['title'],
                    );

                    add_settings_field('feed_them_social_license_keys[' . $key . '][license_key]', '', array($this, 'add_option_setting'), $this->license_page_slug, $this->setting_section_name, $args);
                } //Show Special Box for non actives plugins/extensions!
                else {
                    //Set Variables
                    $args = array(
                        'plugin_name' => $plugin['title'],
                        'demo_url' => $plugin['demo_url'],
                        'purchase_url' => $plugin['purchase_url'],
                    );
                    //show Premium needed box
                    add_settings_field('feed_them_social_license_keys[' . $key . '][license_key]', '', array($this, 'display_premium_needed_license'), $this->license_page_slug, $this->setting_section_name, $args);
                }
            }
        }

        /**
         * Add Options to Plugin License page
         *
         * @param $args
         * @since 2.1.6
         */
        function add_option_setting($args) {
            $key = $args['key'];
            $plugin_name = $args['plugin_name'];

            //Backwards Compatibility for Pre-1-click license page will get removed on first save in Sanitize function.
            $old_license = get_option($key . '_license_key');
            $old_status = get_option($key . '_license_status');

            //License Key Array Option
            $settings_array = get_option($this->setting_option_name);

            $license = !empty($old_license) ? $old_license : isset($settings_array[$key]['license_key']) ? $settings_array[$key]['license_key'] : '';
            $status = !empty($old_status) ? $old_status : isset($settings_array[$key]['license_status']) ? $settings_array[$key]['license_status'] : '';
            $license_error = isset($settings_array[$key]['license_error']) ? $settings_array[$key]['license_error'] : '';

            ?>
            <tr valign="top" class="fts-license-wrap">
                <th scope="row" valign="top">
                    <?php _e($plugin_name); ?>
                </th>
                <td>
                    <input id="<?php echo $this->setting_option_name ?>[<?php echo $key ?>][license_key]" name="<?php echo $this->setting_option_name ?>[<?php echo $key ?>][license_key]" type="text" placeholder="<?php _e('Enter your license key'); ?>" class="regular-text" value="<?php esc_attr_e($license); ?>"/>
                    <label class="description" for="<?php echo $this->setting_option_name ?>[<?php echo $key ?>][license_key]"><?php if ($status !== false && $status == 'valid') { ?>

                            <?php wp_nonce_field('license_page_nonce','license_page_nonce'); ?>
                            <input type="submit" class="button-secondary" name="<?php echo $key ?>_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>

                            <div class="edd-license-data"><p><?php _e('License Key Active.'); ?></p></div>

                            <?php
                        } else {
                            wp_nonce_field('license_page_nonce','license_page_nonce'); ?>
                            <div class="edd-license-data edd-license-msg-error">
                                <p><?php echo $license_error ?><?php $this->update_admin_notices();
                                    _e('To receive updates notifications, please enter your valid license key.'); ?></p>
                            </div>
                        <?php } ?></label>

                    <?php
                    //Create Upgrade Button
                    if (isset($license) && !empty($license) && $status !== false && $status == 'valid') {
                        echo '<a class="edd-upgrade-license-btn button-secondary" target="_blank" href="https://www.slickremix.com/my-account/?&view=upgrades&license_key=' . $license . '">Upgrade License</a>';
                    }
                    ?>
                </td>
            </tr> <?php
        }

        /**
         * Add Plugin License Menu
         *
         * @since 2.1.6
         */
        function license_menu() {
            global $submenu;

            add_submenu_page($this->main_menu_slug, __('Plugin License', 'feed-them-social'), __('Plugin License', 'feed-them-social'), 'manage_options', $this->license_page_slug, array($this, 'license_page'));
        }

        /**
         * Add FREE Plugin License Page for displaying what is available to extend FTS
         *
         * @since 2.1.6
         */
        function license_page() {

            $options = get_option($this->setting_option_name);

            echo '<pre>';
            print_r($options);
            echo '</pre>';

            ?>
            <div class="wrap">
                <h2><?php _e('Plugin License Options'); ?></h2>
                <div class="license-note"> <?php _e("If you need more licenses or your key has expired, please go to the <a href='https://www.slickremix.com/my-account/' target='_blank'>MY ACCOUNT</a> page on our website to upgrade or renew your license.<br/>To get started follow the instructions below.", "feed-them-social") ?> </div>

                <div class="fts-activation-msg">
                    <ol>
                        <li><?php _e('Install the zip file of the plugin you should have received after purchase on the <a href="plugin-install.php">plugins page</a> and leave the free version active too.', 'feed-them-social') ?></li>
                        <li><?php _e('Now Enter your License Key and Click the <strong>Save Changes button</strong>.', 'feed-them-social') ?></li>
                    </ol>
                </div>
                <form method="post" action="options.php" class="fts-license-master-form">
                    <?php settings_fields($this->license_page_slug); ?>
                    <table class="form-table">
                        <tbody>

                        <?php
                        $prem_active = false;
                        foreach ($this->prem_plugins as $plugin) {
                            if (is_plugin_active($plugin['plugin_url'])) {
                                $prem_active = true;
                            }
                        }
                        //No Premium plugins Active make Plugin License page.
                        if ($prem_active === true) {
                            do_settings_fields($this->license_page_slug, $this->setting_section_name);
                        } else {
                            //Each Premium Plugin wrap
                            foreach ($this->prem_plugins as $plugin) {
                                //Set Variables
                                $args = array(
                                    'plugin_name' => $plugin['title'],
                                    'demo_url' => $plugin['demo_url'],
                                    'purchase_url' => $plugin['purchase_url'],
                                );

                                //show Premium needed box
                                $this->display_premium_needed_license($args);
                            }
                        } ?>

                        </tbody>
                    </table>
                    <?php
                    if ($prem_active === true) {
                        submit_button();
                    }
                    ?>
                </form>
                <div style="margin-top:0px;">
                    <a href="https://www.slickremix.com/downloads/feed-them-gallery/" target="_blank"><img style="max-width: 100%;" src="<?php echo plugins_url('feed-them-social/admin/images/ft-gallery-promo.jpg'); ?>"/></a>
                </div>
            </div>
            <?php
        }

        /**
         * Display Premium Needed boxes for plugins not active/installed for FTS
         *
         * @param $args Passed by function or add_settings_field
         * @since 2.1.6
         */
        function display_premium_needed_license($args) {
            $this->plugin_title = $args['plugin_name'];
            $this->demo_url = $args['demo_url'];
            $this->purchase_url = $args['purchase_url'];
            ?>

            <tr valign="top" class="fts-license-wrap">
                <th scope="row" valign="top"><?php echo $this->plugin_title ?></th>
                <td>
                    <div class="fts-no-license-overlay">
                        <div class="fts-no-license-button-wrap"
                        ">
                        <a class="fts-no-license-button-purchase-btn" href="<?php echo $this->demo_url ?>" target="_blank">Demo</a>
                        <a class="fts-no-license-button-demo-btn" href="<?php echo $this->purchase_url ?>" target="_blank">Buy
                            Extension</a>
                    </div>
                    </div>
                    <input id="no_license_key" name="no_license_key" type="text" placeholder="Enter your license key" class="regular-text" value="">
                    <label class="description" for="no_license_key">
                        <div class="edd-license-data edd-license-msg-error"><p>To receive updates notifications, please
                                enter your valid license key.</p></div>

                    </label>
                </td>
            </tr>
            <?php return;
        }

        /**
         * Generates an Upgrade license button based on information from SlickRemix's license keys
         *
         * @param $license_key
         * @since 2.1.6
         */
        function upgrade_license_btn($plugin_key, $license_key, $status) {
            if (isset($license_key) && !empty($license_key) && $status !== false && $status == 'valid') {
                //$api_params = array();
                //$response = wp_remote_get('https://www.slickremix.com/wp-json/slick-license/v2/get-license-info?license_key=' . $license_key, array('timeout' => 60, 'sslverify' => false, 'body' => $api_params));

                $response[$plugin_key] = 'https://www.slickremix.com/wp-json/slick-license/v2/get-license-info?license_key=' . $license_key;

                $fts_functions = new feed_them_social_functions();

                $response = $fts_functions->fts_get_feed_json($response);

                $license_data = json_decode($response[$plugin_key]);

                if (isset($license_data->payment_id) && !empty($license_data->payment_id) && isset($license_data->payment_id) && !empty($license_data->payment_id)) {
                    echo '<a class="edd-upgrade-license-btn button-secondary" target="_blank" href="https://www.slickremix.com/my-account/?&view=upgrades&license_key=' . $license_data->license_id . '">Upgrade License</a>';
                }
                return;
            }
            return;
        }

        /**
         * Sanitize License Keys
         *
         * @param $new
         * @return mixed
         * @since 1.5.6
         */
        function fts_sanitize_license($new) {

            $settings_array = get_option($this->setting_option_name);

            if (!$settings_array) {
                $settings_array = $new;
            } else {
                $settings_array = array_merge($settings_array, $new);
            }

            foreach ($this->prem_plugins as $key => $plugin) {
                if (is_plugin_active($plugin['plugin_url'])) {

                    // listen for our activate button to be clicked
                    if (isset($_POST[$key . '_license_deactivate'])) {
                        $settings_array = $this->deactivate_license($key, $settings_array[$key]['license_key'], $settings_array);
                    }
                    else{
                        //Clean Up old options if they exist
                        $old_license = get_option($key . '_license_key');
                        $old_status = get_option($key . '_license_status');

                        if (!empty($old_license)) {
                            delete_option($key . '_license_key');
                        }
                        if (!empty($old_status)) {
                            delete_option($key . '_license_status');
                        }
                        $settings_array = $this->activate_license($key, $new[$key]['license_key'], $settings_array);
                    }
                }
            }

            return $settings_array;
        }

        /**
         * Activate License Key
         *
         * @since 1.5.6
         */
        function activate_license($key, $license, $settings_array) {

            $license = trim($license);

            // data to send in our API request
            $api_params = array(
                'edd_action' => 'activate_license',
                'license' => $license,
                'item_name' => urlencode($this->prem_plugins[$key]['title']), // the name of our product in EDD
                'url' => home_url(),
            );

            // Call the custom API.
            $response = wp_remote_post($this->store_url, array('timeout' => 60, 'sslverify' => false, 'body' => $api_params));

            // make sure the response came back okay
            if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                if (is_wp_error($response)) {
                    $message = $response->get_error_message();
                } else {
                    $message = __('An error occurred, please try again.');
                }

            } else {
                $license_data = json_decode(wp_remote_retrieve_body($response));

                if (false === $license_data->success) {

                    switch ($license_data->error) {

                        case 'expired' :

                            $message = sprintf(
                                __('Your license key expired on %s.'),
                                date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                            );
                            break;

                        case 'revoked' :

                            $message = __('Your license key has been disabled.');
                            break;

                        case 'missing' :

                            $message = __('Invalid license.');
                            break;

                        case 'invalid' :
                        case 'site_inactive' :

                            $message = __('Your license is not active for this URL.');
                            break;

                        case 'item_name_mismatch' :

                            $message = sprintf(__('This appears to be an invalid license key for %s.'), $this->prem_plugins[$key]['title']);
                            break;

                        case 'no_activations_left':

                            $message = __('Your license key has reached its activation limit.');
                            break;

                        default :

                            $message = __('An error occurred, please try again.');
                            break;
                    }
                }
            }

            //There is an error so set it in array
            if ( ! empty( $message ) ) {
                unset($settings_array[$key]['license_status']);
                $settings_array[$key]['license_error'] = $message;

                return $settings_array;
            }

            //No errors. Set License Status in array
            unset($settings_array[$key]['license_error']);
            $settings_array[$key]['license_status'] = $license_data->license;

            return $settings_array;
        }

        /***********************************************
         * Illustrates how to deactivate a license key.
         * This will decrease the site count
         ***********************************************/
        function deactivate_license($key, $license, $settings_array) {
            // retrieve the license from the database
            $license = trim($license);

            // data to send in our API request
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license' => $license,
                'item_name' => urlencode($this->prem_plugins[$key]['title']), // the name of our product in EDD
                'url' => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post($this->store_url, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

            // make sure the response came back okay
            if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                if (is_wp_error($response)) {
                    $message = $response->get_error_message();
                } else {
                    $message = __('An error occurred, please try again.');
                }
            }

            // decode the license data
            $license_data = json_decode(wp_remote_retrieve_body($response));

            //There is an error so set it in array
            if ( ! empty( $message ) ) {
                unset($settings_array[$key]['license_status']);
                $settings_array[$key]['license_error'] = $message;

                return $settings_array;
            }

            // $license_data->license will be either "deactivated" or "failed"
            if ($license_data->license == 'deactivated') {
                //No errors. unset plugin key from main options array
                unset($settings_array[$key]);
            }

            return $settings_array;
        }

        /**
         * This is a means of catching errors from the activation method above and displaying it to the customer
         *
         * @since 2.1.6
         */
        function update_admin_notices() {
            if (isset($_GET['sl_activation']) && !empty($_GET['message'])) {

                switch ($_GET['sl_activation']) {

                    case 'false':
                        $message = urldecode($_GET['message']);
                        echo $message;
                        break;

                    case 'true':
                    default:
                        // Developers can put a custom success message here for when activation is successful if they want.
                        break;
                }
            }
        }

    }//End CLASS

?>