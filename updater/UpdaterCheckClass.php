<?php
/**
 * Feed Them Social - Updater Check Class
 *
 * In the Free Version this is NOT an updater but displays the license page for users to see they can extend the Free plugin with Extensions
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\updater;

// Exit if accessed directly
if (!\defined('ABSPATH')) {
    exit;
}

/**
 * Allows plugins to use their own update API.
 *
 * @author Pippin Williamson
 * @version 1.0.2
 */
class UpdaterCheckClass {

    private $apiUrl;
    private $apiData = array();
    private $name;
    private $slug;
    private $version;
    private $wpOverride = false;
    private $pluginIdentifier;
    private $pluginName;

    /**
     * Class constructor.
     *
     * @uses plugin_basename()
     * @uses hook()
     *
     * @param string $_api_url The URL pointing to the custom API endpoint.
     * @param string $_plugin_file Path to the plugin file.
     * @param array $_api_data Optional data to send with API calls.
     */
    public function __construct($_api_url, $_plugin_file, $pluginIdentifier, $item_name, $_api_data = null) {

        global $edd_plugin_data;

        $this->apiUrl = trailingslashit($_api_url);
        $this->apiData = $_api_data;
        $this->name = plugin_basename($this->getPluginFileName($_plugin_file));
        $this->slug = basename($this->getPluginFileName($_plugin_file), '.php');
        $this->version = $_api_data['version'];
        $this->wpOverride = isset($_api_data['wp_override']) ? (bool)$_api_data['wp_override'] : false;
        $this->pluginIdentifier = $pluginIdentifier;
        $this->pluginName = $item_name;

        $edd_plugin_data[$this->slug] = $this->apiData;

        if ( empty( $_api_data['license'] ) && $_api_data['status'] !== 'valid' ) {
            add_action( 'admin_notices', array( $this, 'pluginKeyEmptyAdminNotice' ) );
        } elseif ( ! empty( $_api_data['license'] ) && $_api_data['status'] !== 'valid' ) {
            add_action( 'admin_notices', array( $this, 'pluginKeyNotValidAdminNotice' ) );
        }


        // Set up hooks.
        $this->init();
        // display custom admin notice
    }

    /**
     * License Key field Empty on Extension License Page
     *
     * @since 1.0.2
     */
    public function pluginKeyEmptyAdminNotice() {
        ?>

        <div class="error notice">
            <p>
                <?php
                echo \sprintf(
                    esc_html__( '%1$s needs a valid License Key! %2$sClick here to add one%4$s or you will not receive update notices in WordPress. - %3$sGet your License Key Here%4$s.', 'feed-them-social' ),
                    esc_html( $this->pluginName ),
                    '<a href="' . esc_url( 'admin.php?page=fts-license-page' ) . '">',
                    '<a href="' . esc_url( 'https://www.slickremix.com/my-account/' ) . '" target="_blank">',
                    '</a>'
                );
                ?>
            </p>
        </div>

        <?php
    }

    /**
     * License Key Not Valid or Expired
     *
     * @version 1.0.2
     */
    public function pluginKeyNotValidAdminNotice() {
        ?>

        <div class="error notice">
            <p>
                <?php
                echo \sprintf(
                    esc_html__( '%1$s - Your License Key is not active, expired, or is invalid. %2$sClick here to add one%4$s or you will not receive update notices in WordPress. - %3$sGet your License Key Here%4$s.', 'feed-them-social' ),
                    esc_html( $this->pluginName ),
                    '<a href="' . esc_url( 'admin.php?page=fts-license-page' ) . '">',
                    '<a href="' . esc_url( 'https://www.slickremix.com/my-account/' ) . '" target="_blank">',
                    '</a>'
                );
                ?>
            </p>
        </div>

        <?php
    }

    /**
     * Use Plugin Name that this file exists in.
     *
     * @Added by SlickRemix
     *
     * @since 1.0.2
     */
    public function getPluginFileName($_plugin_file) {

        $plugs = plugin_basename($_plugin_file);
        $plugin_folder_name = explode('/', $plugs);


        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        $plugins = get_plugins();
        foreach ($plugins as $plugin_file => $plugin_info) {
            if (strpos($plugin_file, $plugin_folder_name[0]) !== false) {
                $plug_name = $plugin_file;
            }
        }
        return $plug_name;
    }

    /**
     * Set up WordPress filters to hook into WP's update process.
     *
     * @uses add_filter()
     *
     * @return void
     *
     * @since 1.0.2
     */
    public function init() {

        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'), 10);
        add_filter('plugins_api', array($this, 'pluginsApiFilter'), 10, 3);
        remove_action('after_plugin_row_' . $this->name, 'wp_plugin_update_row', 10, 2);
        add_action('after_plugin_row_' . $this->name, array($this, 'showUpdateNotification'), 10, 2);
        add_action('admin_init', array($this, 'showChangelog'));
    }

    /**
     * Check for Updates at the defined API endpoint and modify the update array.
     *
     * This function dives into the update API just when WordPress creates its update array,
     * then adds a custom API call and injects the custom plugin data retrieved from the API.
     * It is reassembled from parts of the native WordPress plugin update code.
     * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
     *
     * @uses apiRequest()
     *
     * @param array $_transient_data Update array build by WordPress.
     * @return array Modified update array with custom plugin data.
     *
     * @since 1.0.2
     */
    public function checkUpdate($_transient_data) {

        global $pagenow;

        if (!\is_object($_transient_data)) {
            $_transient_data = new \stdClass;
        }

        if ( $pagenow === 'plugins.php' && is_multisite()) {
            return $_transient_data;
        }

        if (!empty($_transient_data->response) && !empty($_transient_data->response[$this->name]) && $this->wpOverride == false ) {
            return $_transient_data;
        }

        $version_info = $this->apiRequest('plugin_latest_version', array('slug' => $this->slug));

        if ( $version_info !== false && \is_object($version_info) && isset($version_info->new_version)) {

            if (version_compare($this->version, $version_info->new_version, '<')) {

                $_transient_data->response[$this->name] = $version_info;

            }

            $_transient_data->last_checked = time();
            $_transient_data->checked[$this->name] = $this->version;

        }

        return $_transient_data;
    }

    /**
     * Show update notification row -- needed for multisite subsites, because WP won't tell you otherwise!
     *
     * @param string $file
     * @param array  $plugin
     * @since 1.0.2
     */
    public function showUpdateNotification( $file, $plugin ) {
        // Early exit if the user can't update, it's not a multisite, or not the correct plugin file.
        if ( ! current_user_can( 'update_plugins' ) || ! is_multisite() || $this->name !== $file ) {
            return;
        }

        // Remove our filter on the site transient to avoid conflicts.
        remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'checkUpdate' ), 10 );

        $update_cache = get_site_transient( 'update_plugins' );
        $update_cache = \is_object( $update_cache ) ? $update_cache : new \stdClass();

        // Check if the update cache needs to be populated.
        if ( empty( $update_cache->response[ $this->name ] ) ) {
            $cache_key    = md5( 'edd_plugin_' . sanitize_key( $this->name ) . '_version_info' ); // NOSONAR php:S4790 - MD5 used for cache key generation, not cryptographic security
            $version_info = get_transient( $cache_key );

            if ( $version_info === false ) {
                $version_info = $this->apiRequest( 'plugin_latest_version', array( 'slug' => $this->slug ) );
                set_transient( $cache_key, $version_info, 3600 );
            }

            if ( \is_object( $version_info ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {
                $update_cache->response[ $this->name ] = $version_info;
            }

            $update_cache->last_checked       = time();
            $update_cache->checked[ $this->name ] = $this->version;

            set_site_transient( 'update_plugins', $update_cache );
        }

        $version_info = $update_cache->response[ $this->name ] ?? null;

        // Restore our filter
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'checkUpdate' ), 10 );

        // Guard clause: exit if there's no valid update information or the version is not newer.
        if ( empty( $version_info ) || ! is_object( $version_info ) || version_compare( $this->version, $version_info->new_version, '>=' ) ) {
            return;
        }

        // If we've reached here, an update is available and should be displayed.
        $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
        echo '<tr class="plugin-update-tr" id="' . esc_attr( $this->slug ) . '-update" data-slug="' . esc_attr( $this->slug ) . '" data-plugin="' . esc_attr( $this->slug . '/' . $file ) . '">';
        echo '<td colspan="3" class="plugin-update colspanchange">';
        echo '<div class="update-message notice inline notice-warning notice-alt">';

        $changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

        if ( empty( $version_info->download_link ) ) {
            printf(
                __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'feed-them-social' ),
                esc_html( $version_info->name ),
                '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
                esc_html( $version_info->new_version ),
                '</a>'
            );
        } else {
            printf(
                __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'feed-them-social' ),
                esc_html( $version_info->name ),
                '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
                esc_html( $version_info->new_version ),
                '</a>',
                '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) . '">',
                '</a>'
            );
        }

        do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

        echo '</div></td></tr>';
    }

    /**
     * Updates information on the "View version x.x details" page with custom data.
     *
     * @uses apiRequest()
     *
     * @param mixed $_data
     * @param string $_action
     * @param object $_args
     * @return object $_data
     */
    public function pluginsApiFilter($_data, $_action = '', $_args = null) {

        delete_site_transient('edd_api_request_' . substr(md5(serialize('feed-them-premium')), 0, 15)); // NOSONAR php:S4790 - MD5 used for cache key generation, not cryptographic security

        if ( $_action !== 'plugin_information' ) {
            return $_data;
        }

        if (!isset($_args->slug) || ($_args->slug !== $this->slug)) {
            return $_data;
        }

        $to_send = array(
            'slug' => $this->slug,
            'is_ssl' => is_ssl(),
            'fields' => array(
                'banners' => false, // These will be supported soon hopefully
                'reviews' => false
            )
        );

        $cache_key = 'edd_api_request_' . substr(md5(serialize($this->slug)), 0, 15); // NOSONAR php:S4790 - MD5 used for cache key generation, not cryptographic security

        //Get the transient where we store the api request for this plugin for 24 hours
        $edd_api_request_transient = get_site_transient($cache_key);

        //If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
        if (empty($edd_api_request_transient)) {

            $api_response = $this->apiRequest('plugin_latest_version', $to_send);

            //Expires in 1 day
            set_site_transient($cache_key, $api_response, DAY_IN_SECONDS);

            if ( $api_response !== false && \is_object($api_response) ) {
                $_data = $api_response;
            }
        } else {
            $_data = $edd_api_request_transient;
        }

        // Ensure all required WordPress plugin information fields are present
        if (is_object($_data)) {

            // Add missing contributor information - keep as stdClass with array values
            if (!isset($_data->contributors)) {
                $_data->contributors = new \stdClass();
                $_data->contributors->slickremix = array(
                    'profile' => 'https://profiles.wordpress.org/slickremix',
                    'avatar' => 'https://secure.gravatar.com/avatar/yourgravatarhash?s=96&d=monsterid&r=g',
                    'display_name' => 'SlickRemix'
                );
            } else {
                // If contributors exist but are in wrong format, convert them properly
                if (is_object($_data->contributors)) {
                    $contributors = new \stdClass();
                    foreach ($_data->contributors as $username => $details) {
                        // Ensure each contributor is an array
                        if (is_object($details)) {
                            $contributors->$username = (array) $details;
                        } else {
                            $contributors->$username = $details;
                        }
                    }
                    $_data->contributors = $contributors;
                }
            }

            // Ensure author information is present
            if (!isset($_data->author)) {
                $_data->author = '<a href="https://www.slickremix.com/">SlickRemix</a>';
            }

            // Add homepage if missing
            if (!isset($_data->homepage)) {
                $_data->homepage = 'https://www.slickremix.com/';
            }

            // Add WordPress compatibility info if missing
            if (!isset($_data->requires)) {
                $_data->requires = '4.5.0';
            }

            if (!isset($_data->tested)) {
                $_data->tested = '6.8.2';
            }

            // Ensure plugin name is set
            if (!isset($_data->name)) {
                $_data->name = $this->pluginName;
            }

            // Add plugin slug if missing
            if (!isset($_data->slug)) {
                $_data->slug = $this->slug;
            }

            // Ensure version information is properly formatted
            if (isset($_data->new_version)) {
                // Clean version number (remove any V prefix)
                $_data->new_version = ltrim($_data->new_version, 'Vv ');
            }

            // Ensure sections is always an array
            if (!isset($_data->sections)) {
                $_data->sections = array();
            }

            // Handle sections properly - ensure it's always an array
            if (isset($_data->sections)) {
                if (is_string($_data->sections)) {
                    $_data->sections = maybe_unserialize($_data->sections);
                } elseif (is_object($_data->sections)) {
                    $_data->sections = (array) $_data->sections;
                }

                // Final check to ensure sections is an array
                if (!is_array($_data->sections)) {
                    $_data->sections = array();
                }
            }

            // Add basic changelog if missing
            if (!isset($_data->sections['changelog']) && isset($_data->new_version)) {
                $_data->sections['changelog'] = '<h4>Version ' . $_data->new_version . '</h4><ul><li>Latest version available for download.</li></ul>';
            }

            // Add basic description if missing
            if (!isset($_data->sections['description'])) {
                $_data->sections['description'] = '<p>Premium extension for Feed Them Social plugin.</p>';
            }
        }

        return $_data;
    }

    /**
     * Disable SSL verification in order to prevent download update failures
     *
     * @param array $args
     * @param string $url
     * @return array $array
     */
    public function httpRequestArgs($args, $url): array
    {
        // If it is an https request and we are performing a package download, disable ssl verification
        if (strpos($url, 'https://') !== false && strpos($url, 'edd_action=package_download')) {
            $args['sslverify'] = false;
        }
        return $args;
    }

    /**
     * Calls the API and, if successful, returns the object delivered by the API.
     *
     * @uses get_bloginfo()
     * @uses wp_remote_post()
     * @uses is_wp_error()
     *
     * @param string $_action The requested action.
     * @param array $_data Parameters for the API action.
     * @return false|object
     */
    private function apiRequest($_action, $_data) {

        global $wp_version;

        $data = array_merge($this->apiData, $_data);

        if ($data['slug'] != $this->slug) {
            return;
        }

        if ( trailingslashit( home_url() ) === $this->apiUrl ) {
            return false; // Don't allow a plugin to ping itself!
        }

        $api_params = array(
            'edd_action' => 'get_version',
            'license' => !empty($data['license']) ? $data['license'] : '',
            'item_name' => isset($data['item_name']) ? $data['item_name'] : false,
            'item_id' => isset($data['item_id']) ? $data['item_id'] : false,
            'slug' => $data['slug'],
            'author' => $data['author'],
            'url' => home_url()
        );

        $request = wp_remote_post(
            $this->apiUrl,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        if (!is_wp_error($request)) {
            $request = json_decode(wp_remote_retrieve_body($request));
        }

        if ($request && isset($request->sections)) {
            $request->sections = maybe_unserialize($request->sections);
        } else {
            $request = false;
        }

        return $request;
    }

    /**
     * Show Changelog
     *
     * @since 1.0.2
     */
    public function showChangelog() {
        global $edd_plugin_data;

        // Early exit if the request is not for the changelog.
        if ( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' !== $_REQUEST['edd_sl_action'] ) {
            return;
        }

        // Early exit if required parameters are missing.
        if ( empty( $_REQUEST['plugin'] ) || empty( $_REQUEST['slug'] ) ) {
            return;
        }

        // Check user permissions.
        if ( ! current_user_can( 'update_plugins' ) ) {
            wp_die( __( 'You do not have permission to install plugin updates', 'feed-them-social' ), __( 'Error', 'feed-them-social' ), array( 'response' => 403 ) );
        }

        $data       = $edd_plugin_data[ $_REQUEST['slug'] ];
        $cache_key  = md5( 'edd_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_version_info' ); // NOSONAR php:S4790 - MD5 used for cache key generation, not cryptographic security
        $version_info = get_transient( $cache_key );

        // If version info is not in the cache, fetch it from the API.
        if ( $version_info === false ) {
            $api_params = array(
                'edd_action' => 'get_version',
                'item_name'  => $data['item_name'] ?? false,
                'item_id'    => $data['item_id'] ?? false,
                'slug'       => $_REQUEST['slug'],
                'author'     => $data['author'],
                'url'        => home_url(),
            );

            $request = wp_remote_post( $this->apiUrl, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

            // Assume failure, only populate on successful response.
            $version_info = false;
            if ( ! is_wp_error( $request ) ) {
                $response_body = json_decode( wp_remote_retrieve_body( $request ) );

                if ( ! empty( $response_body ) && isset( $response_body->sections ) ) {
                    $response_body->sections = maybe_unserialize( $response_body->sections );
                    $version_info            = $response_body;
                }
            }

            set_transient( $cache_key, $version_info, 3600 );
        }

        // Display the changelog if it exists.
        if ( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
            echo '<div style="background:#fff;padding:10px;">' . esc_html( $version_info->sections['changelog'] ) . '</div>';
        }

        exit;
    }
}
