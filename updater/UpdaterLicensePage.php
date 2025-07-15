<?php
/**
 * Feed Them Social - Updater License Page
 *
 * Update License Page handles the License keys activation and deactivation process. This page is also used to show the paid extensions available for FTS.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\updater;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Updater License Page
 *
 * @package feedthemsocial
 * @since 2.1.6
 */
class UpdaterLicensePage {

    // static variables
    private static $instance = false;

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
     * Store URL
     *
     * The url of the store coming from slickremix.com.
     *
     * @var object
     * @since 4.2.8
     */
    public $storeUrl;

    /**
     * Main Menu Slug
     *
     * The menu name, Extension License, in the main menu.
     *
     * @var object
     * @since 4.2.8
     */
    public $mainMenuSlug;

    /**
     * License Page Slug
     *
     * The plugin slug name for the license page.
     *
     * @var object
     * @since 4.2.8
     */
    public $licensePageSlug;

    /**
     * Settings Section Name
     *
     * The section name for each group of settings.
     *
     * @var object
     * @since 4.2.8
     */
    public $settingSectionName;

    /**
     * Settings Option Name
     *
     * The setting option name.
     *
     * @var object
     * @since 4.2.8
     */
    public $settingOptionName;

    // Declare the properties explicitly
    public $pluginTitle;
    public $demoUrl;
    public $purchaseUrl;

    /**
     * Construct
     *
     * FTS_settings_page constructor.
     *
     * @since 2.1.6
     */
    public function __construct( $updaterOptionsInfo,  $feedFunctions) {

        // Set License Page Variables
        $this->storeUrl = $updaterOptionsInfo['store_url'];
        $this->mainMenuSlug = $updaterOptionsInfo['main_menu_slug'];
        $this->licensePageSlug = $updaterOptionsInfo['license_page_slug'];
        $this->settingSectionName = $updaterOptionsInfo['setting_section_name'];
        $this->settingOptionName = $updaterOptionsInfo['setting_option_name'];

        // Premium Extension List.
        $this->premExtensionList = FEED_THEM_SOCIAL_PREM_EXTENSION_LIST;

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        //Add the License Page.
        $this->addLicensePage();
    }

    /**
     * Add License Page
     *
     * Add the License Page for handling License keys activation/deactivation.
     *
     * @since 2.1.6
     */
    public function addLicensePage() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $prem_active = false;
        foreach ( $this->premExtensionList as $plugin ) {
            if ( is_plugin_active( $plugin['plugin_url'] ) ) {
                $prem_active = true;
            }
        }

        add_action('admin_menu', array($this, 'licenseMenu'));
        add_action('admin_init', array($this, 'registerOptions'));
    }

    /**
     * Register Options
     *
     * Register Extension License Page Options (overrides options from prem extensions updater files.
     *
     * @since 2.1.6
     */
    public function registerOptions() {
        // Create settings section!
        add_settings_section( $this->settingSectionName, '', null, $this->licensePageSlug );

        // Register Option for settings array!
        register_setting( $this->licensePageSlug, $this->settingOptionName, array( $this, 'ftsSanitizeLicense' ) );

        //Add settings fields for each plugin/extension
        foreach ( $this->premExtensionList as $key => $plugin) {
            //For plugins/extensions that are active
            if (is_plugin_active($plugin['plugin_url'])) {
                $args = array(
                    'key' => $key,
                    'plugin_name' => $plugin['title'],
                );

                add_settings_field( $this->settingOptionName . '[' . $key . '][license_key]', '', array($this, 'addOptionSetting'), $this->licensePageSlug, $this->settingSectionName, $args);
            } //Show Special Box for non actives plugins/extensions!
            else {
                //Set Variables
                $args = array(
                    'plugin_name' => $plugin['title'],
                    'demo_url' => $plugin['demo_url'],
                    'purchase_url' => $plugin['purchase_url'],
                );
                //show Premium needed box
                add_settings_field($this->settingOptionName . '[' . $key . '][license_key]', '', array($this, 'displayPremiumNeededLicense'), $this->licensePageSlug, $this->settingSectionName, $args);
            }
        }
    }

    /**
     * Add Option Setting
     *
     * Add Options to Extension License page.
     *
     * @param $args
     * @since 2.1.6
     */
    public function addOptionSetting( $args ) {
        $key         = $args['key'];
        $pluginName = $args['plugin_name'];

        //License Key Array Option
        $settings_array = get_option($this->settingOptionName);

        $license       = $settings_array[$key]['license_key'] ?? '';
        $status        = $settings_array[$key]['license_status'] ?? '';
        $license_error = $settings_array[$key]['license_error'] ?? '';

        ?>
        <tr class="fts-license-wrap">
            <th scope="row">
                <?php echo esc_html( $pluginName ); ?>
            </th>
            <td>
                <input id="<?php echo esc_html( $this->settingOptionName ); ?>[<?php echo esc_html( $key ); ?>][license_key]" name="<?php echo esc_html( $this->settingOptionName ); ?>[<?php echo esc_html( $key ); ?>][license_key]" type="text" placeholder="<?php echo esc_attr( 'Enter your license key' ); ?>" class="regular-text" value="<?php echo esc_attr( $license ); ?>"/>
                <label class="description" for="<?php echo esc_html( $this->settingOptionName ); ?>[<?php echo esc_html( $key ); ?>][license_key]"><?php if ( false !== $status && 'valid' === $status ) { ?>

                        <?php wp_nonce_field( 'license_page_nonce', 'license_page_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="<?php echo esc_html( $key ); ?>_license_deactivate" value="<?php echo esc_html( ( 'Deactivate License' ) ); ?>"/>

                        <div class="edd-license-data"><p><?php echo esc_html__( 'License Key Active.', 'feed-them-social' ); ?></p></div>

                        <?php
} else {
    wp_nonce_field( 'license_page_nonce', 'license_page_nonce' );
    ?>
                        <div class="edd-license-data edd-license-msg-error">
                            <p><?php echo esc_html( $license_error ); ?>
                                <?php
                                echo esc_html( $this->updateAdminNotices() ) ;
                                echo esc_html__( 'To receive updates notifications, please enter your valid license key.', 'feed-them-social' );
                                ?>
                                </p>
                        </div>
                    <?php } ?></label>

                <?php
                // Create Upgrade Button!
                if ( isset( $license ) && ! empty( $license ) && false !== $status && 'valid' === $status ) {
                    echo '<a class="edd-upgrade-license-btn button-secondary" target="_blank" href="' . esc_url( 'https://www.slickremix.com/my-account/?&view=upgrades&license_key=' . $license ) . '">Upgrade License</a>';
                }
                ?>
            </td>
        </tr>
        <?php
    }

    /**
     * License Menu
     *
     * Add Extension License Menu.
     *
     * @since 2.1.6
     */
    public function licenseMenu() {
        global $submenu;

        add_submenu_page( $this->mainMenuSlug, __( 'Extension License', 'feed-them-social' ), __( 'Extension License', 'feed-them-social' ), 'manage_options', $this->licensePageSlug, array( $this, 'licensePage' ) );
    }

    /**
     * License Page
     *
     * The Extension License Page in the admin dashboard for displaying what is available to extend FTS
     *
     * @since 2.1.6
     */
    public function licensePage() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'Extension License Options', 'feed-them-social' ); ?></h2>
            <div class="license-note">
                <?php
                echo \sprintf(
                    esc_html__( 'If you need more licenses or your key has expired, please go to the %1$sMY ACCOUNT%2$s page on our website to upgrade or renew your license.%3$sTo get started follow the instructions below.', 'feed-them-social' ),
                    '<a href="' . esc_url( 'https://www.slickremix.com/my-account/' ) . '" target="_blank">',
                    '</a>',
                    '<br/>'
                );
                ?>
            </div>

            <div class="fts-activation-msg">
                <ol>
                    <li>
                    <?php
                    echo \sprintf(
                        esc_html__( 'Install the plugin zip file you\'ve received after purchase on the %1$splugins page%2$s and leave the free version active.', 'feed-them-social' ),
                        '<a href="' . esc_url( 'plugin-install.php' ) . '" target="_blank">',
                        '</a>'
                    );
                    ?>
                    </li>
                    <li>
                    <?php
                    echo \sprintf(
                        esc_html__( 'Enter your License Key and Click the %1$sSave Changes button%2$s.', 'feed-them-social' ),
                        '<strong>',
                        '</strong>'
                    );
                    ?>
                    </li>
                </ol>
            </div>
            <form method="post" action="options.php" class="fts-license-master-form">
                <?php settings_fields( $this->licensePageSlug ); ?>
                <table class="form-table">
                    <tbody>

                    <?php
                    $prem_active = false;

                    // Use this for testing $this->premExtensionList;

                    foreach ( $this->premExtensionList as $plugin ) {
                        if ( is_plugin_active( $plugin['plugin_url'] ) ) {
                            $prem_active = true;
                        }
                    }
                    // No Premium plugins Active make Extension License page.
                    if ( $prem_active === true ) {
                        do_settings_fields( $this->licensePageSlug, $this->settingSectionName );
                    } else {
                        // Each Premium Plugin wrap!
                        foreach ( $this->premExtensionList as $plugin ) {
                            // Set Variables!
                            $args = array(
                                'plugin_name'  => $plugin['title'],
                                'demo_url'     => $plugin['demo_url'],
                                'purchase_url' => $plugin['purchase_url'],
                            );

                            //show Premium needed box
                            $this->displayPremiumNeededLicense( $args );
                        }
                    } ?>

                    </tbody>
                </table>
                <?php
                if ( $prem_active === true ) {
                    submit_button();
                }
                ?>
            </form>
        </div>
        <?php
    }


    /**
     * Display Premium Needed License
     *
     * Display Premium Needed boxes for plugins not active/installed for FTS.
     *
     * @param array $args by function or add_settings_field!
     * @since 2.1.6
     */
    public function displayPremiumNeededLicense( $args ) {
        $this->pluginTitle = $args['plugin_name'];
        $this->demoUrl     = $args['demo_url'];
        $this->purchaseUrl = $args['purchase_url'];
        ?>

        <tr class="fts-license-wrap">
            <th scope="row"><?php echo esc_html( $this->pluginTitle ); ?></th>
            <td>
                <div class="fts-no-license-overlay">
                    <div class="fts-no-license-button-wrap"
                    ">
                    <a class="fts-no-license-button-purchase-btn" href="<?php echo esc_url( $this->demoUrl ); ?>" target="_blank">Demo</a>
                    <a class="fts-no-license-button-demo-btn" href="<?php echo esc_url( $this->purchaseUrl ); ?>" target="_blank">Buy
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
        <?php
    }

    /**
     * Upgrade License Button
     *
     * Generates an Upgrade license button based on information from SlickRemix's license keys
     *
     * @param $license_key
     * @since 2.1.6
     */
    public function upgradeLicenseBtn( $plugin_key, $license_key, $status ) {
        if ( isset( $license_key ) && ! empty( $license_key ) && false !== $status && 'valid' === $status ) {
            $response[ $plugin_key ] = 'https://www.slickremix.com/wp-json/slick-license/v2/get-license-info?license_key=' . $license_key;

            // Get License Info From SlickRemix.com
            $response = $this->feedFunctions->ftsGetFeedJson($response);

            $license_data = json_decode($response[$plugin_key]);

            if (isset($license_data->payment_id) && !empty($license_data->payment_id) && isset($license_data->payment_id) && !empty($license_data->payment_id)) {
                echo \sprintf(__('%1$sUpgrade License%2$s', 'feed-them-social'),
                    '<a class="edd-upgrade-license-btn button-secondary" href="'.esc_url('https://www.slickremix.com/my-account/?&view=upgrades&license_key=' . $license_data->license_id).'" target="_blank">',
                    '</a>'
                );
            }
            return;
        }
        return;
    }

    /**
     * Sanitize License
     *
     * Sanitize License Keys for database entry.
     *
     * @param $new
     * @return mixed
     * @since 1.5.6
     */
    public function ftsSanitizeLicense( $new ) {
        $fts_fb_options_nonce = wp_create_nonce( 'fts-sanitize-license-nonce' );

        if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-sanitize-license-nonce' ) ) {

            $settings_array = get_option( $this->settingOptionName );

            if ( ! $settings_array ) {
                $settings_array = $new;
            } else {
                $settings_array = array_merge( $settings_array, $new );
            }

            foreach ( $this->premExtensionList as $key => $plugin ) {
                if ( is_plugin_active( $plugin['plugin_url'] ) ) {

                    // listen for our activate button to be clicked!
                    if ( isset( $_POST[ $key . '_license_deactivate' ] ) ) {
                        $settings_array = $this->deactivateLicense( $key, $settings_array[ $key ]['license_key'], $settings_array );
                    } else {
                        // Clean Up old options if they exist!
                        $old_license = get_option( $key . '_license_key' );
                        $old_status  = get_option( $key . '_license_status' );

                        if ( ! empty( $old_license ) ) {
                            delete_option( $key . '_license_key' );
                        }
                        if ( ! empty( $old_status ) ) {
                            delete_option( $key . '_license_status' );
                        }
                        $settings_array = $this->activateLicense( $key, $new[ $key ]['license_key'], $settings_array );
                    }
                }
            }

            return $settings_array;
        }
    }

    /**
     * Activate License
     *
     * Activate the License Key.
     *
     * @since 1.5.6
     */
    public function activateLicense($key, $license, $settings_array) {

        $license = trim($license);

        // data to send in our API request!
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license,
            'item_name'  => rawurlencode( $this->premExtensionList[ $key ]['title'] ), // the name of our product in EDD!
            'url'        => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(
            $this->storeUrl,
            array(
                'timeout'   => 60,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        // make sure the response came back okay
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200 ) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = $this->getGenericErrorMessage();
            }

        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if ( $license_data->success === false ) {

                switch ($license_data->error) {

                    case 'expired' :

                        $message = \sprintf(
                            __('Your license key expired on %s.', 'feed-them-social'),
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, time()))
                        );
                        break;

                    case 'revoked' :

                        $message = __('Your license key has been disabled.', 'feed-them-social');
                        break;

                    case 'missing' :

                        $message = __('Invalid license.', 'feed-them-social');
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __('Your license is not active for this URL.', 'feed-them-social');
                        break;

                    case 'item_name_mismatch':
                        $message = \sprintf( esc_html__( 'This appears to be an invalid license key for %s.', 'feed-them-social' ), $this->premExtensionList[ $key ]['title'] );
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.', 'feed-them-social');
                        break;

                    default :
                        $message = $this->getGenericErrorMessage();
                        break;
                }
            }
        }

        //There is an error so set it in array
        if (!empty($message)) {
            unset($settings_array[$key]['license_status']);
            $settings_array[$key]['license_error'] = $message;

            return $settings_array;
        }

        //No errors. Set License Status in array
        unset($settings_array[$key]['license_error']);
        $settings_array[$key]['license_status'] = $license_data->license;

        return $settings_array;
    }

    /**
     * Deactivate License
     *
     * Deactivate a license key. (This will decrease the site count!)
     *
     * @param $new
     * @return mixed
     * @since 1.5.6
     */
    public function deactivateLicense($key, $license, $settings_array) {
        // retrieve the license from the database
        $license = trim($license);

        // data to send in our API request!
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license,
            'item_name'  => rawurlencode( $this->premExtensionList[ $key ]['title'] ), // the name of our product in EDD!
            'url'        => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(
            $this->storeUrl,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        // make sure the response came back okay
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200 ) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = $this->getGenericErrorMessage();
            }
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        //There is an error so set it in array
        if (!empty($message)) {
            unset($settings_array[$key]['license_status']);
            $settings_array[$key]['license_error'] = $message;

            return $settings_array;
        }

        // $license_data->license will be either "deactivated" or "failed"
        if ( 'deactivated' === $license_data->license ) {
            // No errors. unset plugin key from main options array!
            unset( $settings_array[ $key ] );
        }

        return $settings_array;
    }

    /**
     * Update Admin Notices
     *
     * Update the admin notices in dashboard. This will catch errors from the activation method above and displaying it to the customer.
     *
     * @since 2.1.6
     */
    public function updateAdminNotices() {
        if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

            switch ($_GET['sl_activation']) {

                case 'false':
                    $message = rawurldecode( $_GET['message'] );
                    return $message;
                    break;

                case 'true':
                default:
                    // Developers can put a custom success message here for when activation is successful if they want.
                    break;
            }
        }
    }

    /**
     * Get Generic Error Message
     *
     * Returns a generic, translatable error message string.
     *
     * @return string
     */
    private function getGenericErrorMessage(): string {
        return __( 'An error occurred, please try again.', 'feed-them-social' );
    }

}//End CLASS
