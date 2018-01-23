<?php
namespace feedthemsocial;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
    public $plugin_identifier = '';

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
        //No Premium plugins Active make Plugin License page.
        if ($prem_active == false) {
            if (!self::$instance) {
                self::$instance = true;
                add_action('admin_menu', array($this, 'license_menu'));
            }
        }
        //Premium Active: Add boxes to plugin licence page they don't have.
        //Rgister new override options
        if (isset($_GET['page']) && $_GET['page'] == 'fts-license-page') {
            add_action('current_screen', array($this, 'register_options'));
        }
    }

    /**
     * Register Plugin License Page Options (overrides options from prem extentions updater files
     *
     * @since 2.1.6
     */
    function register_options() {
        add_settings_section('main_section', '', null, $this->license_page_slug);

        foreach ($this->prem_plugins as $key => $plugin) {
            if (is_plugin_active($plugin['plugin_url'])) {
                $this->plugin_identifier = $key;
                register_setting($this->license_page_slug . '_license_manager_page', $key . '_license_key', array($this, 'edd_sanitize_license'));
                $args = array(
                    'key' => $key,
                    'plugin_name' => $plugin['title'],
                    'demo_url' => $plugin['demo_url'],
                    'purchase_url' => $plugin['purchase_url'],
                );
                //Show Active Premium Plugins
                add_settings_field($key . '_license_key', '', array($this, 'add_option_setting'), $this->license_page_slug, 'main_section', $args);
            } else {
                register_setting($this->license_page_slug . '_license_manager_page', $key . '_license_key');
                //Show Special Box for non actives plugins!
                //Set Variables
                $args = array(
                    'plugin_name' => $plugin['title'],
                    'demo_url' => $plugin['demo_url'],
                    'purchase_url' => $plugin['purchase_url'],
                );

                //show Premium needed box
                add_settings_field($key . '_license_key', '', array($this, 'display_premium_needed_license'), $this->license_page_slug, 'main_section', $args);
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

        $license = get_option($key . '_license_key');
        $status = get_option($key . '_license_status');
        ?>
        <tr valign="top" class="fts-license-wrap">
            <th scope="row" valign="top">
                <?php _e($plugin_name); ?>
            </th>
            <td>
                <input id="<?php echo $key ?>_license_key" name="<?php echo $key ?>_license_key" type="text" placeholder="<?php _e('Enter your license key'); ?>" class="regular-text" value="<?php esc_attr_e($license); ?>"/>
                <label class="description" for="<?php print $key ?>_license_key"><?php if ($status !== false && $status == 'valid') { ?>

                        <?php wp_nonce_field($key . 'license_page_nonce', $key . 'license_page_nonce'); ?>
                        <input type="submit" class="button-secondary" name="<?php echo $key ?>_edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>

                        <div class="edd-license-data"><p><?php _e('License Key Active.'); ?></p></div>

                        <?php
                    } else {
                        wp_nonce_field($key . 'license_page_nonce', $key . 'license_page_nonce'); ?>
                        <input type="submit" class="button-secondary" name="<?php echo $key ?>_edd_license_activate" value="<?php _e('Activate License'); ?>"/>
                        <div class="edd-license-data edd-license-msg-error"><p><?php $this->update_admin_notices();
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
        //Override submenu page if needed
        if (isset($submenu[$this->main_menu_slug]) && in_array($this->license_page_slug, wp_list_pluck($submenu[$this->main_menu_slug], 2))) {
            remove_submenu_page($this->main_menu_slug, $this->license_page_slug);
        }
        if (isset($submenu[$this->main_menu_slug]) && !in_array($this->license_page_slug, wp_list_pluck($submenu[$this->main_menu_slug], 2))) {
            add_submenu_page($this->main_menu_slug, __('Plugin License', 'feed-them-social'), __('Plugin License', 'feed-them-social'), 'manage_options', $this->license_page_slug, array($this, 'license_page'));
        }
    }

    /**
     * Add FREE Plugin License Page for displaying what is available to extend FTS
     *
     * @since 2.1.6
     */
    function license_page() {
        ?>
        <div class="wrap">
            <h2><?php _e('Plugin License Options'); ?></h2>
            <div class="license-note"> <?php _e("If you need more licenses or your key has expired, please go to the <a href='https://www.slickremix.com/my-account/' target='_blank'>MY ACCOUNT</a> page on our website to upgrade or renew your license.<br/>To get started follow the instructions below.", "feed-them-social") ?> </div>

            <div class="fts-activation-msg">
                <ol>
                    <li><?php _e('Install the zip file of the plugin you should have received after purchase on the <a href="plugin-install.php">plugins page</a> and leave the free version active too.', 'feed-them-social') ?></li>
                    <li><?php _e('Now Enter your License Key and Click the <strong>Save Changes button</strong>.', 'feed-them-social') ?></li>
                    <li><?php _e('Finally, Click the <strong>Activate License button</strong>.', 'feed-them-social') ?></li>
                </ol>
            </div>
            <form method="post" action="options.php" class="fts-license-master-form">
                <?php settings_fields($this->license_page_slug . '_license_manager_page'); ?>
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
                        do_settings_fields($this->license_page_slug, 'main_section');
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
                <?php if ($prem_active === true) {
                    submit_button();
                } ?>
            </form>
            <div style="margin-top:0px;"><a href="https://www.slickremix.com/downloads/feed-them-gallery/" target="_blank"><img style="max-width: 100%;" src="<?php echo plugins_url('feed-them-social/admin/images/ft-gallery-promo.jpg'); ?>"/></a></div>
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

        $key = $args['demo_url'];
        $plugin_name = $args['purchase_url'];

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
    function upgrade_license_btn($plugin_key ,$license_key, $status) {
        if (isset($license_key) && !empty($license_key) && $status !== false && $status == 'valid') {
            //$api_params = array();
            //$response = wp_remote_get('https://www.slickremix.com/wp-json/slick-license/v2/get-license-info?license_key=' . $license_key, array('timeout' => 60, 'sslverify' => false, 'body' => $api_params));

            $response[$plugin_key] = 'https://www.slickremix.com/wp-json/slick-license/v2/get-license-info?license_key=' . $license_key;

            $fts_functions = new feed_them_social_functions();

            $response = $fts_functions->fts_get_feed_json($response);

            $license_data = json_decode($response[$plugin_key]);

            if(isset($license_data->payment_id) && !empty($license_data->payment_id) && isset($license_data->payment_id ) && !empty($license_data->payment_id)){
                echo '<a class="edd-upgrade-license-btn button-secondary" target="_blank" href="https://www.slickremix.com/my-account/?&view=upgrades&license_key=' . $license_data->license_id . '">Upgrade License</a>';
            }
            return;
        }
        return;
    }

    /**
     * Sanatize License Keys
     *
     * @param $new
     * @return mixed
     * @since 2.1.6
     */
    function edd_sanitize_license($new) {
        $old = get_option($this->plugin_identifier . '_license_key');
        if ($old && $old != $new) {
            delete_option($this->plugin_identifier . '_license_status'); // new license has been entered, so must reactivate
        }
        return $new;
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