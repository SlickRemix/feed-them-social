<?php
/**
 * Settings Page
 *
 * This class is for loading up the Settings Page
 *
 * @class    Settings
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial\admin\settings;

use feedthemsocial\admin\cron_jobs\CronJobs;
use feedthemsocial\includes\DebugLog;

/**
 * Class Settings
 */
class SettingsPage {

    /**
     * Settings Functions
     *
     * The settings Functions class
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Feed Cache.
     *
     * Class used for caching.
     *
     * @var object
     */
    public $feedCache;

    /**
     * Settings Functions
     *
     * The settings Functions class
     *
     * @var array
     */
    public $allSettings;

    /**
     * Settings constructor.
     */
    public function __construct( $settingsFunctions, $feedCache ) {
        // Settings Functions.
        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Cache object.
        $this->feedCache = $feedCache;

        // Get All Settings.
        $this->allSettings = $this->settingsFunctions->fts_get_settings();

        // Add Actions and Filters.
        $this->addActionsFilters();
    }

    /**
     * Add Action Filters
     *
     * Add System Info to our menu.
     *
     * @since 1.0.0
     */
    public function addActionsFilters() {

        // Notices
        add_action( 'admin_init', array( $this, 'showNotices' ) );

        // Add the settings menu page
        add_action( 'admin_menu', array( $this, 'addSubmenuPage' ) );

        // Register Settings
        add_action( 'admin_init', array( $this, 'registerSettings' ) );

        // Additional date format fieldsmsp_local_user_allowed
        add_filter( 'fts_after_setting_output', array( $this, 'dateTranslateFields' ), 10, 2 );

        // Additional date format fieldsmsp_local_user_allowed
        add_filter( 'fts_after_setting_output', array( $this, 'customDateTimeFields' ), 10, 2 );

    }

    /**
     * FT Gallery Submenu Pages
     *
     * Admin Submenu buttons.
     *
     * @since 1.0.0
     */
    public function addSubmenuPage() {

        global $pagenow, $typenow;

        // Settings Page.
        add_submenu_page(
            'edit.php?post_type=fts',
            esc_html__( 'Settings', 'feed-them-social' ),
            esc_html__( 'Settings', 'feed-them-social' ),
            'manage_options',
            'fts-settings-page',
            array( $this, 'displaySettingsPage' ), 3
        );

        if( $typenow === 'fts' && $pagenow === 'edit.php' ) {
            wp_register_style( 'fts_settings', plugins_url( 'feed-them-social/admin/css/jquery-ui-fresh.min.css' ), array(), FEED_THEM_SOCIAL_VERSION );
            wp_enqueue_style( 'fts_settings' );

            wp_register_script( 'fts_settings_admin', plugins_url( 'feed-them-social/admin/js/settings.min.js' ), array(), FEED_THEM_SOCIAL_VERSION );
            wp_enqueue_script( 'fts_settings_admin' );

            // This is for the circles with question marks in them on the Settings page.
            wp_enqueue_script( 'jquery-ui-tooltip' );
        }
    }

    /**
     * Displays plugin notices, including updated settings.
     *
     * @since   1.0.0
     * @return  void
     */
    public function showNotices() {
        $notices = array(
            'updated' => array(),
            'error'   => array(),
        );

        $notices = apply_filters('fts_admin_notices', $notices);

        // Add success notices.
        if ( \count($notices['updated']) > 0) {
            foreach ($notices['updated'] as $notice => $message) {
                add_settings_error('fts-notices', $notice, $message, 'updated');
            }
        }

        // Add error notices.
        if ( \count($notices['error']) > 0) {
            foreach ($notices['error'] as $notice => $message) {
                add_settings_error('fts-notices', $notice, $message, 'error');
            }
        }

        // Display the notices via `admin_notices` hook.
        add_action('admin_notices', function () {
            settings_errors('fts-notices');
        });
    }

    /**
     * Add all settings sections and fields.
     *
     * @since    1.0
     * @return    void
     */
    public function registerSettings() {

        if ( ! get_option( 'fts_settings' ) ) {
            add_option( 'fts_settings' );
        }

        foreach ( $this->getRegisteredSettings() as $tab => $sections ) {
            foreach ( $sections as $section => $settings) {

                // Check for backwards compatibility
                $section_tabs = $this->getSettingsTabSections( $tab );
                if ( ! \is_array( $section_tabs ) || ! \array_key_exists( $section, $section_tabs ) ) {
                    $section = 'main';
                    $settings = $sections;
                }

                add_settings_section(
                    'fts_settings_' . $tab . '_' . $section,
                    __return_null(),
                    '__return_false',
                    'fts_settings_' . $tab . '_' . $section
                );

                foreach ( $settings as $option ) {
                    // For backwards compatibility
                    if ( empty( $option['id'] ) ) {
                        continue;
                    }

                    $args = wp_parse_args( $option, array(
                        'section'       => $section,
                        'id'            => null,
                        'desc'          => '',
                        'name'          => '',
                        'size'          => null,
                        'options'       => '',
                        'std'           => '',
                        'min'           => null,
                        'max'           => null,
                        'step'          => null,
                        'chosen'        => null,
                        'placeholder'   => null,
                        'allow_blank'   => true,
                        'readonly'      => false,
                        'faux'          => false,
                        'tooltip_title' => false,
                        'tooltip_desc'  => false,
                        'tooltip_class' => false,
                        'field_class'   => ''
                    ) );

                    add_settings_field(
                        'fts_settings[' . $args['id'] . ']',
                        $args['name'],
                        method_exists( $this->settingsFunctions, 'fts' . ucfirst( $args['type'] ) . 'Callback' ) ? array( $this->settingsFunctions, 'fts' . ucfirst( $args['type'] ) . 'Callback' ) : array( $this->settingsFunctions, 'ftsMissingCallback' ),
                        'fts_settings_' . $tab . '_' . $section,
                        'fts_settings_' . $tab . '_' . $section,
                        $args
                    );
                }
            }

        }

        // Creates our settings in the options table.
        register_setting( 'fts_settings', 'fts_settings', array( 'sanitize_callback' => array( $this, 'settingsSanitize' ) ) );

    } // registerSettings

    /**
     * Retrieve the array of plugin settings.
     *
     * @since    1.3.4
     * @return    array    Array of plugin settings to register
     */
    public function getRegisteredSettings() {

        global $fts_options;

        /**
         * 'Whitelisted' FTS settings, filters are provided for each settings
         * section to allow extensions and other plugins to add their own settings.
         */
        $fts_settings = array(
            /** General Settings */
            'general' => apply_filters( 'fts_settings_general',
                array(
                    'general-main' => array(
                        'website_header' => array(
                            'id'      => 'website_header',
                            'name'    => '<h2> ' . __( 'General Settings', 'feed-them-social' ) . '</h2>',
                            'type'    => 'header',
                            'tooltip_desc' => 'These are the basic options for Cache time, Hide Powered by text and more.',
                        ),
                        'fts_cache_time' => array(
                            'id'            => 'fts_cache_time',
                            'name'          => __( 'Cache Time', 'feed-them-social' ),
                            'type'          => 'select',
                            'options'       => $this->feedCache->ftsGetCacheOptions(),
                            'std'           => '',
                            'field_class'   => 'fts_cache_time',
                            'tooltip_class' => 'fts-cache-time-tooltip',
                            'tooltip_desc' => 'Choose the amount of time you would like your feed to be cached for. If you are using an additional caching plugin or varnish make sure and omit the page the feed is on from caching, otherwise you may need manually empty the cache for the feed to update.',
                            'desc' => '<div id="fts-clear-cache">Clear Cache</div><div class="clearfix"></div><div class="fts-cache-messages"></div>',
                        ),
                        'powered_by' => array(
                            'id'      => 'powered_by',
                            'name'    => __( 'Hide Powered by Text', 'feed-them-gallery' ),
                            'type'    => 'checkbox',
                            //'std'     => '',
                            'tooltip_desc'    => __( 'Check to hide Powered by Feed Them Social text.', 'feed-them-social' ),

                        ),
                        'remove_magnific_css' => array(
                            'id'      => 'remove_magnific_css',
                            'name'    => __( 'Disable Popup CSS', 'feed-them-social' ),
                            'type'    => 'checkbox',
                           // 'std'     => 0,
                            'tooltip_class' => 'fts-checkbox-tooltip-no-margin-top',
                            'tooltip_desc' => 'Check this if you are experiencing problems with your theme(s) or other plugin(s) and need to disable the Magnific Popup CSS that our plugin uses.',
                        ),
                        'fts_show_admin_bar' => array(
                            'id'      => 'fts_show_admin_bar',
                            'name'    => __( 'Show Admin Menu Bar ', 'feed-them-social' ),
                            'type'    => 'checkbox',
                           //'std'     => 0,
                            'tooltip_class' => 'fts-checkbox-tooltip-no-margin-top',
                            'tooltip_desc' => 'Display a menu in the Admin bar at the top of the website while logged in. The menu name will say Feed Them Social and contain a list of menu items to help navigate faster.',
                        )
                    ),
                    'formatting' => array(
                        'formatting_header' => array(
                            'id'      => 'formatting_header',
                            'name'    => '<h2> ' . __( 'Date & Time', 'feed-them-social' ) . '</h2>',
                            'type'    => 'header',
                            'tooltip_desc' => 'The date and time options set here are specifically for your social feed.',
                        ),
                        // we don't need to set a timezone so use this option for something else.
                        'timezone' => array(
                                'id'      => 'timezone',
                                'name'    => __( 'TimeZone', 'feed-them-gallery' ),
                                'type'    => 'select',
                                'options' => $this->settingsFunctions->ftsGetTimezoneSettingOptions(),
                                'std'     => 'America/Los_Angeles',
                                'tooltip_desc'    => __( 'This option is only for Facebook and TikTok. Choose the TimeZone that is correct for your location. This will make sure the social media feed time is correct.', 'feed-them-social' ),

                        ),
                        'date_time_format' => array(
                            'id'            => 'date_time_format',
                            'name'          => __( 'Format', 'feed-them-social' ),
                            'type'          => 'select',
                            'options'       => $this->settingsFunctions->ftsGetDateFormatSettingOptions(),
                            'std'           => 'l, F jS, Y \a\t g:ia',
                            'field_class'   => 'fts_date_time_format',
                            'tooltip_desc'    => __( 'Select the date and time format you would like to see on your social feed. The 1 Day Ago option is set by default. You can hide the date and time when creating a feed if you prefer.', 'feed-them-social' )

                        ),
                    ),
                )
            ),
            'styles' => apply_filters( 'fts_settings_styles',
                array(
                    'css' => array(
                        'use_custom_css' => array(
                            'id'      => 'use_custom_css',
                            'name'    => __( 'Use Custom CSS', 'feed-them-social' ),
                            'type'    => 'checkbox',
                            'desc'    => '',
                           // 'std'     => '1',
                            'tooltip_desc'    => 'If checked the CSS you enter below will be loaded on the front end of the website. This checkbox was created in case you want to keep your CSS but do not want it to load yet.',
                        ),
                        'custom_css' => array(
                            'id'      => 'custom_css',
                            'name'    => __( 'Custom CSS', 'feed-them-social' ),
                            'type'    => 'textarea',
                            'desc'    => __( 'Add your custom CSS code above. You do not need to add <code>style</code> tags', 'feed-them-social' )
                        )
                    ),
                    'js' => array(
                        'use_custom_js' => array(
                            'id'      => 'use_custom_js',
                            'name'    => __( 'Use Custom JS', 'feed-them-social' ),
                            'type'    => 'checkbox',
                            'desc'    => '',
                           // 'std'     => '1',
                            'tooltip_desc'    => 'If checked the JS you enter below will be loaded on the front end of the website. This checkbox was created in case you want to keep your JS but do not want it to load yet.',
                        ),
                        'custom_js' => array(
                            'id'      => 'custom_js',
                            'name'    => __( 'Custom JS', 'feed-them-social' ),
                            'type'    => 'textarea',
                            'desc'    => __( 'Add your custom JS code above. You do not need to add <code>script</code> tags', 'feed-them-social' )
                        )
                    )
                )
            ),

            'sharing' => apply_filters( 'fts_settings_styles',

                array(
                        'sharing_header' => array(
                            'id'      => 'sharing_header',
                            'name'    => '<h2> ' . __( 'Social Sharing', 'feed-them-social' ) . '</h2>',
                            'type'    => 'header',
                            'tooltip_desc' => 'The Social Share Options are on by default for all feeds. You can choose custom colors below and also disable the social sharing option if you prefer.',
                        ),
                        'hide_sharing' => array(
                            'id'      => 'hide_sharing',
                            'name'    => __( 'Disable Share Options', 'feed-them-social' ),
                            'type'    => 'checkbox',
                            'desc'    => '',
                           // 'std'     => '1',
                            'tooltip_desc'    => 'Check this if you want to disable the Share Icon on all feeds.',
                        ),
                        'social_icons_text_color' => array(
                            'id'          => 'social_icons_text_color',
                            'name'        => __( 'Social Icons', 'feed-them-social' ),
                            'type'        => 'color',
                            'placeholder' => __( '#ddd', 'feed-them-social' ),
                            'tooltip_desc'    => 'This is the main color for the social icons that appear in the social feed, generally at the bottom of each post.',
                        ),
                        'social_icons_text_color_hover' => array(
                            'id'          => 'social_icons_text_color_hover',
                            'name'        => __( 'Social Icons Hover', 'feed-them-social' ),
                            'type'        => 'color',
                            'placeholder' => __( '#ddd', 'feed-them-social' ),
                            'tooltip_desc'    => 'This is the color that will appear if you hover your mouse over any of the share icons.',
                        ),
                        'icons_wrap_background' => array(
                            'id'          => 'icons_wrap_background',
                            'name'        => __( 'Social Icons Background', 'feed-them-social' ),
                            'type'        => 'color',
                            'placeholder' => __( '#ddd', 'feed-them-social' ),
                            'tooltip_desc'    => 'This color is for the wrapper background that contains the social icons.',
                        ),
                )
            ),

            'delete' => apply_filters( 'fts_settings_delete',

                array(
                    'sharing_header' => array(
                        'id'      => 'delete_data_header',
                        'name'    => '<h2> ' . __( 'Delete Data', 'feed-them-social' ) . '</h2>',
                        'type'    => 'header',
                        'tooltip_desc' => 'This section is for deleting all of the data that Feed Them Social has created. This includes all feeds, settings, and cron jobs stored in the database.',
                    ),
                    'remove_on_uninstall' => array(
                        'id'      => 'remove_on_uninstall',
                        'name'    => __( 'Remove Data on Uninstall', 'feed-them-social' ),
                        'type'    => 'checkbox',
                        //'std'     => 1,
                        'tooltip_class' => 'fts-checkbox-tooltip',
                        'tooltip_desc'    => __( 'Check this box if you would like Feed Them Social to completely remove all of its data when the plugin is deleted.', 'feed-them-social' )
                    )
                )
            )
        );

        return apply_filters( 'fts_registered_settings', $fts_settings );
    } // getRegisteredSettings


    /**
     * Retrieve settings tabs
     *
     * @since    1.3.4
     * @return    array        $tabs
     */
    public function getSettingsTabs() {

        $settings = $this->getRegisteredSettings();

        $tabs                     = array();
        $tabs['general']          = __( 'General', 'feed-them-social' );
        $tabs                     = apply_filters( 'fts_settings_tabs_after_general', $tabs );
        $tabs['styles']           = __( 'Styles & Scripts', 'feed-them-social' );
        $tabs                     = apply_filters( 'fts_settings_tabs_after_styles', $tabs );
        $tabs['sharing']           = __( 'Social Sharing', 'feed-them-social' );
        $tabs                     = apply_filters( 'fts_settings_tabs_after_sharing', $tabs );
        $tabs['delete']           = __( 'Delete Data', 'feed-them-social' );
        $tabs                     = apply_filters( 'fts_settings_tabs_after_delete', $tabs );


        if ( ! empty( $settings['extensions'] ) ) {
            $tabs['extensions'] = __( 'Extensions', 'feed-them-social' );
        }

        if ( ! empty( $settings['licenses'] ) ) {
            $tabs['licenses'] = __( 'Licenses', 'feed-them-social' );
        }

        return apply_filters( 'fts_settings_tabs', $tabs );
    } // getSettingsTabs

    /**
     * Retrieve settings tabs
     *
     * @since    1.3.4
     * @return    array        $section
     */
    public function getSettingsTabSections( $tab = false ) {

        $tabs     = false;
        $sections = $this->getRegisteredSettingsSections();

        if( $tab && ! empty( $sections[ $tab ] ) ) {
            $tabs = $sections[ $tab ];
        }
        elseif ( $tab ) {
            $tabs = false;
        }

        return $tabs;
    } // getSettingsTabSections

    /**
     * Get the settings sections for each tab
     * Uses a static to avoid running the filters on every request to this function
     *
     * @since    1.3.4
     * @return    array        Array of tabs and sections
     */
    public function getRegisteredSettingsSections() {

        static $sections = false;

        if ( false !== $sections ) {
            return $sections;
        }

        $sections = array(
            'general' => apply_filters( 'fts_settings_sections_general', array(
                'general-main'       => __( 'Site', 'feed-them-social' ),
                'formatting' => __( 'Date & Time', 'feed-them-social' ),
            ) ),
            'styles'  => apply_filters( 'fts_settings_sections_styles', array(
                'css'        => __( 'Custom CSS', 'feed-them-social' ),
                'js' => __( 'Custom JS', 'feed-them-social' ),
            ) )
        );

        $sections = apply_filters( 'fts_settings_sections', $sections );

        return $sections;
    } // registered_settings_sections

    /**
     * Settings Sanitization.
     *
     * Adds a settings error (for the updated message)
     * At some point this will validate input.
     *
     * @since    1.3.4
     * @param    array    $input    The value inputted in the field.
     * @return    array    $input    Sanitizied value.
     */
    public function settingsSanitize( $input = array()) {

        $allSettings = $this->allSettings;

        if ( empty( $_POST['_wp_http_referer'] ) ) {
            return $input;
        }

        parse_str( $_POST['_wp_http_referer'], $referrer );

        $settings = $this->getRegisteredSettings();

        $tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';
        $section  = isset( $referrer['section'] ) ? $referrer['section'] : 'main';

        $input = $input ? $input : array();

        $input = apply_filters( 'fts_settings_' . $tab . '-' . $section . '_sanitize', $input );
        if ( 'main' === $section )  {
            // Check for extensions that aren't using new sections
            $input = apply_filters( 'fts_settings_' . $tab . '_sanitize', $input );

            // Check for an override on the section for when main is empty
            if ( ! empty( $_POST['fts_section_override'] ) ) {
                $section = sanitize_text_field( $_POST['fts_section_override'] );
            }
        }

        // Loop through each setting being saved and pass it through a sanitization filter
        foreach ( $input as $key => $value ) {
            // Get the setting type (checkbox, select, etc)
            $type = isset( $settings[ $tab ][ $key ]['type'] ) ? $settings[ $tab ][ $key ]['type'] : false;

            if ( $type ) {
                // Field type specific filter
                $input[ $key ] = apply_filters( 'fts_settings_sanitize_' . $type, $value, $key );
            }

            // Specific key filter
            $input[ $key ] = apply_filters( 'fts_settings_sanitize_' . $key, $value );

            // General filter
            $input[ $key ] = apply_filters( 'fts_settings_sanitize', $input[ $key ], $key );

        }

        // Loop through the whitelist and unset any that are empty for the tab being saved
        $main_settings    = $section == 'main' ? $settings[ $tab ] : array(); // Check for extensions that aren't using new sections
        $section_settings = ! empty( $settings[ $tab ][ $section ] ) ? $settings[ $tab ][ $section ] : array();

        $found_settings = array_merge( $main_settings, $section_settings );

        if ( ! empty( $found_settings ) ) {
            foreach ( $found_settings as $key => $value ) {

                // Settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
                if ( is_numeric( $key ) ) {
                    $key = $value['id'];
                }

                if ( empty( $input[ $key ] ) && isset( $allSettings[ $key ] ) ) {
                    unset( $allSettings[ $key ] );
                }
            }
        }

        DebugLog::log( 'SettingsPage', 'Print All Settings', $allSettings );

        // Is $fts_options an array? Show it successfully updated!
        if( \is_array($allSettings)){
            // Merge our new settings with the existing
            $output = array_merge( $allSettings, $input );
            add_settings_error( 'fts-notices', 'settings_updated', __( 'Settings Updated.', 'feed-them-social' ), 'updated' );

            // Set new cron job for clearing cache.
            // This is here so we can set a new cron job if the user changes the cache time.
            $cron_job = new CronJobs( null, null, null, null );
            $cron_job->ftsSetCronJob( 'clear-cache-set-cron-job', null, null );
            DebugLog::log( 'SettingsPage', 'FTS Cache Emptied. Setting Cron Job from settings-page.php.', true );

            return $output;
        }

        // Show error message for Settings not saving because something is wrong.
        add_settings_error( 'fts-notices', 'settings_updated', __( 'Oops, Settings did not update.', 'feed-them-social' ), 'error' );

    } // settingsSanitize

    /**
     * Settings Page
     *
     * Feed Them Gallery Settings Page
     *
     * @since   1.3.4
     */
    public function displaySettingsPage()  {
        if ( ! current_user_can( 'manage_options' ) )    {
            wp_die(
                '<h1>' . __( 'Cheatin&#8217; uh?', 'feed-them-social' ) . '</h1>' .
                '<p>'  . __( 'You do not have permission to access this page.', 'feed-them-social' ) . '</p>',
                403
            );
        }

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        $settings_tabs = $this->getSettingsTabs();
        $settings_tabs = empty( $settings_tabs ) ? array() : $settings_tabs;
        $active_tab    = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
        $active_tab    = \array_key_exists( $active_tab, $settings_tabs ) ? $active_tab : 'general';
        $sections      = $this->getSettingsTabSections( $active_tab );
        $key           = 'main';

        if ( \is_array( $sections ) ) {
            $key = key( $sections );
        }

        $registered_sections = $this->getSettingsTabSections( $active_tab );
        $section             = isset( $_GET['section'] ) && ! empty( $registered_sections ) && array_key_exists( $_GET['section'], $registered_sections ) ? sanitize_text_field( $_GET['section'] ) : $key;

        // Unset 'main' if it's empty and default to the first non-empty if it's the chosen section
        $allSettings = $this->getRegisteredSettings();

        // Let's verify we have a 'main' section to show
        $has_main_settings = true;
        if ( empty( $allSettings[ $active_tab ]['main'] ) )    {
            $has_main_settings = false;
        }

        // Check for old non-sectioned settings
        if ( ! $has_main_settings ) {
            foreach ( $allSettings[ $active_tab ] as $sid => $stitle ) {
                if ( ! ( \is_string( $sid ) && \is_array( $sections ) && \array_key_exists( $sid, $sections ) ) ) {
                    $has_main_settings = true;
                    break;
                }
            }
        }

        $override = false;
        if ( $has_main_settings === false ) {
            unset( $sections['main'] );

            if ( $section === 'main' ) {
                foreach ( $sections as $section_key => $section_title ) {
                    if ( ! empty( $allSettings[ $active_tab ][ $section_key ] ) ) {
                        $section  = $section_key;
                        $override = true;
                        break;
                    }
                }
            }
        }

        ob_start();

        ?>
        <script>
            jQuery(document).ready(function ($) {

                var fts_color_picker = $('.ftg-color-picker');

                if( fts_color_picker.length ) {
                    fts_color_picker.wpColorPicker();
                }
            });
        </script>

        <div class="wrap <?php echo 'wrap-' . esc_attr($active_tab); ?>">
            <h1 class="wp-heading-inline"><?php _e( 'Settings', 'feed-them-social' ); ?></h1>
            <h1 class="nav-tab-wrapper">
                <?php
                foreach( $this->getSettingsTabs() as $tab_id => $tab_name ) {

                    $tab_url = add_query_arg( array(
                        'post_type'        => 'fts',
                        'page'             => 'fts-settings-page',
                        'settings-updated' => false,
                        'tab'              => $tab_id
                    ), admin_url( 'edit.php' ) );

                    // Remove the section from the tabs so we always end up at the main section
                    $tab_url = remove_query_arg( 'section', $tab_url );

                    $active = $active_tab === $tab_id ? ' nav-tab-active' : '';

                    echo '<a href="' . esc_url( $tab_url ) . '" class="nav-tab' . $active . '">';
                    echo esc_html( $tab_name );
                    echo '</a>';
                }
                ?>
            </h1>
            <?php

            $number_of_sections = \is_array( $sections ) ? \count( $sections ) : 0;
            $number = 0;
            if ( $number_of_sections > 1 ) {
                echo '<div><ul class="subsubsub">';
                foreach( $sections as $section_id => $section_name ) {
                    echo '<li>';
                    $number++;
                    $tab_url = add_query_arg( array(
                        'post_type'        => 'fts',
                        'page'             => 'fts-settings-page',
                        'settings-updated' => false,
                        'tab'              => $active_tab,
                        'section'          => $section_id
                    ), admin_url( 'edit.php' ) );

                    /**
                     * Allow filtering of the section URL.
                     *
                     * Enables plugin authors to insert links to non-setting pages as sections.
                     *
                     * @since    1.1.10
                     * @param    str        The section URL
                     * @param    str        The section ID (array key)
                     * @param    str        The current active tab
                     * @return    str
                     */
                    $tab_url = apply_filters( 'fts_options_page_section_url', $tab_url, $section_id, $active_tab );

                    $class = '';
                    if ( $section == $section_id ) {
                        $class = 'current';
                    }
                    echo '<a class="' . $class . '" href="' . esc_url( $tab_url ) . '">' . esc_html( $section_name ) . '</a>';

                    if ( $number != $number_of_sections ) {
                        echo ' | ';
                    }
                    echo '</li>';
                }
                echo '</ul></div>';
            }
            ?>
            <div id="tab_container" class="<?php echo esc_attr( $section ); ?>">
                <form method="post" action="options.php">
                    <table class="form-table">
                        <?php
                            settings_fields( 'fts_settings' );

                            if ( $section === 'main' ) {
                                do_action( 'fts_settings_tab_top', $active_tab );
                            }

                            do_action( 'fts_settings_tab_top_' . $active_tab . '_' . $section );

                            do_settings_sections( 'fts_settings_' . $active_tab . '_' . $section );

                            do_action( 'fts_settings_tab_bottom_' . $active_tab . '_' . $section  );

                            // If the main section was empty and we overrode the view with the next subsection, prepare the section for saving
                            if ( $override === true ) {
                                ?><input type="hidden" name="fts_section_override" value="<?php echo esc_attr( $section ); ?>" /><?php
                            }
                        ?>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div><!-- #tab_container-->
            <?php do_action( 'fts_settings_bottom' ); ?>
        </div><!-- .wrap -->
        <?php
        echo ob_get_clean();
    }

    /**
     * Adds the translation fields to the image date setting field.
     *
     * @since    1.3.4
     * @param    string    $html    HTML output
     * @param    array    $args    Array of arguments passed to setting
     * @return    string    HTML output
     */
    public function dateTranslateFields( $html, $args )    {
        if ( $args['id'] === 'date_time_format' )    {
            ob_start();

            $style = $this->settingsFunctions->fts_get_option( 'date_time_format' ) !== 'one-day-ago' ? ' style="display: none;"' : '';
            ?>

            <tr class="custom_time_ago_wrap"<?php echo $style; ?>>
                <th scope="row"><h3><?php _e( 'Customize Translation', 'feed-them-social' ); ?></h3></th>
                <td>&nbsp;</td>
            </tr>

            <?php
            foreach( $this->getTranslationFields() as $field => $value ) : ?>
                <tr class="custom_time_ago_wrap fts-<?php echo str_replace( 'language_', '', esc_html( $field ) ); ?>"<?php echo $style; ?>>
                    <th scope="row"><?php echo str_replace( 'language_', '', esc_html( $field ) ); ?></th>
                    <td>
                        <?php $this->settingsFunctions->ftsTextCallback( array(
                            'id'          => $field,
                            'std'         => $value,
                            'readonly'    => 'false',
                            'field_class' => '',
                            'desc'        => ''
                        ) ); ?>
                    </td>
                </tr>

            <?php endforeach;

            $html .= ob_get_clean();
        }

        return $html;
    } // dateTranslateFields

    /**
     * Adds the custom date/time fields to the image date setting field.
     *
     * @since    1.3.4
     * @param    string    $html    HTML output
     * @param    array    $args    Array of arguments passed to setting
     * @return    string    HTML output
     */
    public function customDateTimeFields( $html, $args )    {
        if ( $args['id'] === 'date_time_format' )    {
            ob_start();

            $style = $this->settingsFunctions->fts_get_option( 'date_time_format' ) !== 'fts-custom-date' ? ' style="display: none;"' : '';
            ?>

                <tr class="custom_date_time_wrap"<?php echo $style; ?>>
                    <th scope="row"><?php echo esc_html__( 'Custom Date', 'feed-them-social' ); ?></th>
                    <td>
                        <?php $this->settingsFunctions->ftsTextCallback( array(
                            'id'          => 'custom_date',
                            'std'         => '',
                            'readonly'    => 'false',
                            'field_class' => '',
                            'desc'        => '',
                            'placeholder' => 'F j, Y'
                        ) ); ?>
                    </td>
                </tr>

                <tr class="custom_date_time_wrap"<?php echo $style; ?>>
                    <th scope="row"><?php echo esc_html__( 'Custom Time', 'feed-them-social' ); ?></th>
                    <td>
                        <?php $this->settingsFunctions->ftsTextCallback( array(
                            'id'          => 'custom_time',
                            'std'         => '',
                            'readonly'    => 'false',
                            'field_class' => '',
                            'desc'        => '',
                            'placeholder' => 'g:i a'
                        ) ); ?>
                        <p>
                            <?php
                            echo \sprintf(
                        __( 'Add your own custom date or time format.', 'feed-them-social' ) . '<br>' .
                        '<a href="%s" target="_blank">%s.</a>',
                        'https://wordpress.org/support/article/formatting-date-and-time/#format-string-examples',
                        __( 'Documentation on date and time formatting', 'feed-them-social' )
                        );

                            ?>
                        </p>
                    </td>
                </tr>
            <?php

            $html .= ob_get_clean();
        }

        return $html;
    } // customDateTimeFields

    /**
     * Retrieve the translation fields.
     *
     * @since    1.3.4
     * @return    array    Array of fields and defaults
     */
    public function getTranslationFields()    {
        return array(
            'language_second'  => $this->settingsFunctions->fts_get_option( 'language_second', __( 'second', 'feed-them-social' ) ),
            'language_seconds' => $this->settingsFunctions->fts_get_option( 'language_seconds', __( 'seconds', 'feed-them-social' ) ),
            'language_minute'  => $this->settingsFunctions->fts_get_option( 'language_minute', __( 'minute', 'feed-them-social' ) ),
            'language_minutes' => $this->settingsFunctions->fts_get_option( 'language_minutes', __( 'minutes', 'feed-them-social' ) ),
            'language_hour'    => $this->settingsFunctions->fts_get_option( 'language_hour', __( 'hour', 'feed-them-social' ) ),
            'language_hours'   => $this->settingsFunctions->fts_get_option( 'language_hours', __( 'hours', 'feed-them-social' ) ),
            'language_day'     => $this->settingsFunctions->fts_get_option( 'language_day', __( 'day', 'feed-them-social' ) ),
            'language_days'    => $this->settingsFunctions->fts_get_option( 'language_days', __( 'days', 'feed-them-social' ) ),
            'language_week'    => $this->settingsFunctions->fts_get_option( 'language_week', __( 'week', 'feed-them-social' ) ),
            'language_weeks'   => $this->settingsFunctions->fts_get_option( 'language_weeks', __( 'weeks', 'feed-them-social' ) ),
            'language_month'   => $this->settingsFunctions->fts_get_option( 'language_month', __( 'month', 'feed-them-social' ) ),
            'language_months'  => $this->settingsFunctions->fts_get_option( 'language_months', __( 'months', 'feed-them-social' ) ),
            'language_year'    => $this->settingsFunctions->fts_get_option( 'language_year', __( 'year', 'feed-them-social' ) ),
            'language_years'   => $this->settingsFunctions->fts_get_option( 'language_years', __( 'years', 'feed-them-social' ) ),
            'language_ago'     => $this->settingsFunctions->fts_get_option( 'language_ago', __( 'ago', 'feed-them-social' ) ),
        );
    } // getTranslationFields

    /**
     * Adds the translation fields to the image date setting field.
     *
     * @since    1.3.4
     * @param    string    $html    HTML output
     * @param    array    $args    Array of arguments passed to setting
     * @return    string    HTML output
     */
    public function customDateFields( $html, $args )    {
        if ( 'date_time_format' === $args['id'] )    {
            ob_start();

            $style = $this->settingsFunctions->fts_get_option( 'date_time_format' ) !== 'fts-custom-date' ? ' style="display: none;"' : '';
            ?>

            <tr class="custom_time_ago_wrap"<?php echo $style; ?>>
                <th scope="row"><?php _e( 'Custom Date & Time', 'feed-them-social' ); ?></th>
                <td>&nbsp;</td>
            </tr>

            <?php
            foreach( $this->getTranslationFields() as $field => $value ) : ?>
                <tr class="custom_date_time_wrap">
                    <th scope="row"><?php echo str_replace( 'language_', '', esc_html( $field ) ); ?></th>
                    <td>
                        <?php $this->settingsFunctions->ftsTextCallback( array(
                            'id'          => $field,
                            'std'         => $value,
                            'readonly'    => 'false',
                            'field_class' => '',
                            'desc'        => ''
                        ) ); ?>
                    </td>
                </tr>

            <?php endforeach;

            $html .= ob_get_clean();
        }

        return $html;
    } // dateTranslateFields

}//end class
