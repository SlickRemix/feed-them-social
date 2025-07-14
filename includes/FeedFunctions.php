<?php

/**
 * Feeds Functions Class
 *
 * This page is used to create the Facebook Access Token options!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0.0
 */

namespace feedthemsocial\includes;

// Exit if accessed directly!
use feedthemsocial\admin\cron_jobs\CronJobs;

if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Feed Functions Class
 */
class FeedFunctions {

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $optionsFunctions;


    /**
     * Feed Settings Array
     * An array of Feed Settings. Set in admin/cpt/options/feeds-cpt-options.php
     *
     * @var array
     */
    public $feedCptOptionsArray = array();

    /**
     * Feed Cache.
     *
     * Class used for caching.
     *
     * @var object
     */
    public $feedCache;

    /**
     * Data Protection
     *
     * Data Protection Class for encryption.
     *
     * @var object
     */
    public $dataProtection;

    /**
     * Extension List.
     *
     * List of extensions for FTS.
     *
     * @var object
     */
    public $premExtensionList = FEED_THEM_SOCIAL_PREM_EXTENSION_LIST;

    /**
     * Seconds.
     *
     * @var string
     */
    const SECONDS_WORD = ' seconds';

    /**
     * Feed Functions constructor.
     */
    public function __construct( $settingsFunctions, $optionsFunctions, $feed_cpt_options, $feedCache, $dataProtection ){

        // Settings Functions Class.
        $this->settingsFunctions = $settingsFunctions;

        // Add Actions and Filters.
        $this->addActionsFilters();

        // Options Functions Class.
        $this->optionsFunctions = $optionsFunctions;

        // Feed Settings array.
        $this->feedCptOptionsArray = $feed_cpt_options->getAllOptions();

        // Set Feed Cache object.
        $this->feedCache = $feedCache;

        // Set Data Protection object.
        $this->dataProtection = $dataProtection;

        // Widget Code.
        add_filter( 'widget_text', 'do_shortcode' );

        add_action( 'wp_ajax_ftsEncryptTokenAjax', array( $this, 'ftsEncryptTokenAjax' ) );
        add_action( 'wp_ajax_ftsDecryptTokenAjax', array( $this, 'ftsDecryptTokenAjax' ) );
        add_action( 'wp_ajax_ftsRefreshFeedAjax', array( $this, 'ftsRefreshFeedAjax' ) );

        if ( is_admin() || $this->isExtensionActive( 'feed_them_social_premium' ) || $this->isExtensionActive( 'feed_them_social_facebook_reviews' ) ) {
            // Load More Options.
            add_action( 'wp_ajax_myFtsFbLoadMore', array( $this, 'myFtsFbLoadMore' ) );
            add_action( 'wp_ajax_nopriv_myFtsFbLoadMore', array( $this, 'myFtsFbLoadMore' ) );
        }
    }

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 4.0.0
     */
    public function addActionsFilters(){

        // Display admin bar
        $display_admin_bar = $this->settingsFunctions->fts_get_option( 'fts_show_admin_bar' );
        if ( $display_admin_bar === '1' ) {
            // FTS Admin Bar!
            add_action( 'wp_before_admin_bar_render', array( $this, 'ftsAdminBarMenu' ), 999 );
        }

        if ( is_admin() ) {
            // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA!
            add_action( 'admin_enqueue_scripts', array( $this, 'feedThemAdminCss' ) );
            // Main Settings Page!
            if ( isset( $_GET['page'] ) && $_GET['page'] === 'feed-them-settings-page' || isset( $_GET['page'] ) && $_GET['page'] === 'fts-facebook-feed-styles-submenu-page' || isset( $_GET['page'] ) && $_GET['page'] === 'fts-twitter-feed-styles-submenu-page' || isset( $_GET['page'] ) && $_GET['page'] === 'fts-instagram-feed-styles-submenu-page' || isset( $_GET['page'] ) && $_GET['page'] === 'fts-pinterest-feed-styles-submenu-page' || isset( $_GET['page'] ) && $_GET['page'] === 'fts-youtube-feed-styles-submenu-page' ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'feedThemSettings' ) );
            }
            // System Info Page!
            if ( isset( $_GET['page'] ) && $_GET['page'] === 'fts-system-info-submenu-page' ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'feedThemSystemInfoCss' ) );
            }
        }
    }

    /**
     * FTS Admin Bar Menu
     *
     * Create our custom menu in the admin bar.
     *
     * @since 1.9.6
     */
    public function ftsAdminBarMenu() {
        global $wp_admin_bar;

        if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
            return;
        }
        $wp_admin_bar->add_menu(
            array(
                'id'    => 'feed_them_social_admin_bar',
                'title' => __( 'Feed Them Social', 'feed-them-social' ),
                'href'  => false,
            )
        );

        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_set_cache',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => __( 'Clear Cache', 'feed-them-social' ),
                'href'   => false,
                'meta' => array('onclick' => 'fts_ClearCache("alert");') //JavaScript function trigger just as an example.
            )
        );

        $fts_cachetime = $this->settingsFunctions->fts_get_option( 'fts_cache_time' ) ?: '86400';

        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_bar_set_cache',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => \sprintf(
                    __( 'Set Cache Time %1$s%2$s%3$s', 'feed-them-social' ),
                    '(',
                    $this->feedCache->ftsCachetimeAmount( $fts_cachetime ),
                    ')'
                ),
                'href'   => admin_url( 'edit.php?post_type=fts&page=fts-settings-page' ),

            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_bar_feeds',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => __( 'Feeds', 'feed-them-social' ),
                'href'   => admin_url( 'edit.php?post_type=fts' ),
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_bar_settings',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => __( 'Settings', 'feed-them-social' ),
                'href'   => admin_url( 'edit.php?post_type=fts&page=fts-settings-page' ),
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_bar_styles_scripts',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => __( 'Styles & Scripts', 'feed-them-social' ),
                'href'   => admin_url( 'edit.php?post_type=fts&page=fts-settings-page&tab=styles' ),
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id'     => 'feed_them_social_admin_bar_social_sharing',
                'parent' => 'feed_them_social_admin_bar',
                'title'  => __( 'Social Sharing', 'feed-them-social' ),
                'href'   => admin_url( 'edit.php?post_type=fts&page=fts-settings-page&tab=sharing' ),
            )
        );
    }

    /**
     * Is Extension Active
     *
     * Checks an the extension currently active.
     *
     * @param $check_extension_key string underscored name of extension in FEED_THEM_SOCIAL_PREM_EXTENSION_LIST.
     *
     * @return boolean
     */
    public function isExtensionActive( $check_extension_key ) {
        foreach ( FEED_THEM_SOCIAL_PREM_EXTENSION_LIST as $extension_key => $extension_info ) {
            if ( $extension_key === $check_extension_key) {
                if( is_plugin_active( $extension_info['plugin_url']) ){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get Saved Feed Options
     *
     * Get saved Options for the feed using cpt post id.
     *
     * @param $feed_post_id string the feed post id to get options from.
     *
     * @return array | boolean
     */
    public function getSavedFeedOptions( $feed_post_id ) {
        // Get saved Options if possible.
        return $this->optionsFunctions->getSavedOptionsArray( 'fts_feed_options_array', true, $feed_post_id);
    }

    /**
     * Get Feed Setting
     *
     * Get a single Feed Option using Feed CPT ID and Option name.
     *
     * @param $feed_post_id string feed post id to get options from.
     * @param $option_name string name of Option in the Options array.
     * @return array | boolean
     */
    public function getFeedOption( $feed_post_id, $option_name ) {
        // Get Feed Options.
        $saved_feed_options = $this->getSavedFeedOptions( $feed_post_id );

        return $saved_feed_options[ $option_name ] ?? false;
    }

    /**
     * Get Feed Type
     *
     * Get the feed type from option using in the feed's CPT id.
     *
     * @param $feedCptId string
     */
    public function getFeedType( $feedCptId ){
        // Get Feed Type from Saved Options Array.
        return $this->getFeedOption( $feedCptId, 'feed_type' );
    }

    /**
     * FTS Get Feed json
     *
     * Generate Get Json (includes MultiCurl).
     *
     * @param array $request_feed_data feeds data info.
     * @return array
     * @since 1.9.6
     */
    public function ftsGetFeedJson( $request_feed_data ) {

        $data = array();

        // Log the request data for debugging
        DebugLog::log( 'FeedFunctions', 'Request Data', $request_feed_data );

        // Make Multiple Requests from array with more than 2 keys!
        // Multi is only being used with Facebook
        // This is so we can return the page data and the feed data all at once.
        if ( \is_array( $request_feed_data ) && \count( $request_feed_data ) > 1 ) {
            $multi_request_data = array();

            // Build Multiple Feed request data.
            foreach ( $request_feed_data as $key => $single_request_data ) {
                if (is_array($single_request_data) && isset($single_request_data['url'])) {
                    // Handling single request with headers
                    $multi_request_data[ $key ] = $single_request_data;
                } else {
                    // Handling single request without headers
                    $multi_request_data[ $key ]['url'] = $single_request_data;
                    $multi_request_data[ $key ]['type'] = 'GET';
                }
            }
            // Fetch Multiple Requests!
            $responses = $this->getResponse( $multi_request_data, true);
            $data = array();
            foreach ( $responses as $key => $response ) {
                $data[ $key ] = $response->body;
            }
        } else {
            // Make Single Requests from array with 1 key!
            if ( is_array( $request_feed_data ) ) {
                // Check if headers are included in the single request
                if (isset($request_feed_data['url'])) {

                    $single_response = $this->getResponse( $request_feed_data, false);
                    $data = array('data' => $single_response->body);
                } else {
                    foreach ( $request_feed_data as $key => $url ) {
                        $single_response = $this->getResponse( $url, false);
                        $data = array();
                        $data[ $key ] = $single_response->body;
                    }
                }
            } else {
                // Make Single request from just url!
                if ( ! empty( $request_feed_data ) ) {
                    $single_response = $this->getResponse( $request_feed_data, false );
                    $data['data'] = $single_response->body;
                }
            }
        }

        // Do nothing if Curl was Successful!
        return !empty( $data ) ? $data : '';
    }

    /**
     * Get Response
     *
     * Get response using proper request class. (The request class is shipped with WordPress's Core even though it is third party.)
     *
     * @param array $request_feed_data feeds data info.
     * @param boolean $multiple Multiple feed requests.
     *
     * @return array
     * @since 4.0.9
     */
    public function getResponse( $request_feed_data, $multiple = false ) {

        // Log the request data for debugging
        DebugLog::log( 'FeedFunctions', 'Get Response Data', $request_feed_data );

        // WordPress 6.2 or greater. Call Requests Class for PSR-4 requests.
        if ( class_exists('\WpOrg\Requests\Requests') ) {
            // Multiple Requests.
            if ( true === $multiple ) {
                return \WpOrg\Requests\Requests::request_multiple( $request_feed_data );
            }
            // Handling Single POST Request
            elseif (isset($request_feed_data['method']) && $request_feed_data['method'] === 'POST') {
                $url = $request_feed_data['url'];
                $headers = isset($request_feed_data['headers']) ? $request_feed_data['headers'] : array();
                $body = isset($request_feed_data['body']) ? $request_feed_data['body'] : array();
                return \WpOrg\Requests\Requests::post($url, $headers, $body);
            }
            // Single Request.
            else {
                // Check if headers are included in the single request
                if (is_array($request_feed_data) && isset($request_feed_data['url'])) {
                    $url = $request_feed_data['url'];
                    $headers = isset($request_feed_data['headers']) ? $request_feed_data['headers'] : array();
                    return \WpOrg\Requests\Requests::get($url, $headers);
                }
                else {
                    return \WpOrg\Requests\Requests::get( $request_feed_data );
                }
            }
        }
        // WordPress 6.1 or less. Call Requests Class for PSR-0 requests.
        else {
            // Multiple Requests.
            if ( true === $multiple ) {
                return \Requests::request_multiple( $request_feed_data );
            }
            // Single Request.
            else {
                // Check if headers are included in the single request
                if (is_array($request_feed_data) && isset($request_feed_data['url'])) {
                    $url = $request_feed_data['url'];
                    $headers = isset($request_feed_data['headers']) ? $request_feed_data['headers'] : array();
                    return \Requests::get($url, $headers);
                } // Handling Single POST Request
                elseif (isset($request_feed_data['method']) && $request_feed_data['method'] === 'POST') {
                    $url = $request_feed_data['url'];
                    $headers = isset($request_feed_data['headers']) ? $request_feed_data['headers'] : array();
                    $body = isset($request_feed_data['body']) ? $request_feed_data['body'] : array();
                    return \Requests::post($url, $headers, $body);
                }
                else {
                    return \Requests::get( $request_feed_data );
                }
            }
        }
    }


    /**
     * Get Feed Dynamic Class Name
     *
     * @return string
     * @since 4.0.0
     */
    public function getFeedDynamicClassName() {

        $feed_dynamic_class_name = '';
        if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
            $feed_dynamic_class_name = 'feed_dynamic_class' . esc_attr( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
        }
        return $feed_dynamic_class_name;

    }

    /**
     * FTS Ago
     *
     * Create date format like fb and twitter. Thanks: http://php.quicoto.com/how-to-calculate-relative-time-like-facebook/ .
     *
     * @param string $timestamp Timestamp!
     * @return string
     * @since 1.9.6
     */
    public function ftsAgo( $timestamp ) {
        // not setting isset'ing anything because you have to save the settings page to even enable this feature
        $fts_language_second = $this->settingsFunctions->fts_get_option( 'fts_language_second' );
        if ( empty( $fts_language_second ) ) {
            $fts_language_second = esc_html__( 'second', 'feed-them-social' );
        }
        $fts_language_seconds = $this->settingsFunctions->fts_get_option( 'language_seconds' );
        if ( empty( $fts_language_seconds ) ) {
            $fts_language_seconds = esc_html__( 'seconds', 'feed-them-social' );
        }
        $fts_language_minute = $this->settingsFunctions->fts_get_option( 'language_minute' );
        if ( empty( $fts_language_minute ) ) {
            $fts_language_minute = esc_html__( 'minute', 'feed-them-social' );
        }
        $fts_language_minutes = $this->settingsFunctions->fts_get_option( 'language_minutes' );
        if ( empty( $fts_language_minutes ) ) {
            $fts_language_minutes = esc_html__( 'minutes', 'feed-them-social' );
        }
        $fts_language_hour = $this->settingsFunctions->fts_get_option( 'language_hour' );
        if ( empty( $fts_language_hour ) ) {
            $fts_language_hour = esc_html__( 'hour', 'feed-them-social' );
        }
        $fts_language_hours = $this->settingsFunctions->fts_get_option( 'language_hours' );
        if ( empty( $fts_language_hours ) ) {
            $fts_language_hours = esc_html__( 'hours', 'feed-them-social' );
        }
        $fts_language_day = $this->settingsFunctions->fts_get_option( 'language_day' );
        if ( empty( $fts_language_day ) ) {
            $fts_language_day = esc_html__( 'day', 'feed-them-social' );

        }
        $fts_language_days = $this->settingsFunctions->fts_get_option( 'language_days' );
        if ( empty( $fts_language_days ) ) {
            $fts_language_days = esc_html__( 'days', 'feed-them-social' );
        }
        $fts_language_week = $this->settingsFunctions->fts_get_option( 'language_week' );
        if ( empty( $fts_language_week ) ) {
            $fts_language_week = esc_html__( 'week', 'feed-them-social' );
        }
        $fts_language_weeks = $this->settingsFunctions->fts_get_option( 'language_weeks' );
        if ( empty( $fts_language_weeks ) ) {
            $fts_language_weeks = esc_html__( 'weeks', 'feed-them-social' );
        }
        $fts_language_month = $this->settingsFunctions->fts_get_option( 'language_month' );
        if ( empty( $fts_language_month ) ) {
            $fts_language_month = esc_html__( 'month', 'feed-them-social' );
        }
        $fts_language_months = $this->settingsFunctions->fts_get_option( 'language_months' );
        if ( empty( $fts_language_months ) ) {
            $fts_language_months = esc_html__( 'months', 'feed-them-social' );
        }
        $fts_language_year = $this->settingsFunctions->fts_get_option( 'language_year' );
        if ( empty( $fts_language_year ) ) {
            $fts_language_year = esc_html__( 'year', 'feed-them-social' );
        }
        $fts_language_years = $this->settingsFunctions->fts_get_option( 'language_years' );
        if ( empty( $fts_language_years ) ) {
            $fts_language_years = esc_html__( 'years', 'feed-them-social' );
        }
        $fts_language_ago = $this->settingsFunctions->fts_get_option( 'language_ago' );
        if ( empty( $fts_language_ago ) ) {
            $fts_language_ago = esc_html__( 'ago', 'feed-them-social' );
        }

        // $periods = array( "sec", "min", "hour", "day", "week", "month", "years", "decade" );.
        $periods        = array( $fts_language_second, $fts_language_minute, $fts_language_hour, $fts_language_day, $fts_language_week, $fts_language_month, $fts_language_year, 'decade' );
        $periods_plural = array( $fts_language_seconds, $fts_language_minutes, $fts_language_hours, $fts_language_days, $fts_language_weeks, $fts_language_months, $fts_language_years, 'decades' );

        if ( ! is_numeric( $timestamp ) ) {
            $timestamp = strtotime( $timestamp );
            if ( ! is_numeric( $timestamp ) ) {
                return '';
            }
        }
        $difference = time() - $timestamp;
        // Customize in your own language. Why thank-you I will.
        $lengths = array( '60', '60', '24', '7', '4.35', '12', '10' );

        if ( $difference > 0 ) {
            // this was in the past
            $ending = $fts_language_ago;
        } else {
            // this was in the future
            $difference = -$difference;
            // not doing dates in the future for posts
            $ending = 'to go';
        }
        for ( $j = 0; $difference >= $lengths[ $j ] && $j < count( $lengths ) - 1; $j++ ) {
            $difference /= $lengths[ $j ];
        }

        $difference = round( $difference );

        if ( $difference > 1 ) {
            $periods[ $j ] = $periods_plural[ $j ];
        }

        return "$difference $periods[$j] $ending";
    }

    /**
     * FTS Custom Date
     *
     * @param string $created_time Created time.
     * @param string $feed_type Feed type.
     * @return string
     * @since 1.9.6
     */
    public function ftsCustomDate( $created_time, $feed_type ) {

        $fts_custom_date         = $this->settingsFunctions->fts_get_option( 'custom_date' );
        $fts_custom_time         = $this->settingsFunctions->fts_get_option( 'custom_time' );
        $custom_date             = $this->settingsFunctions->fts_get_option( 'date_time_format' );
        $fts_twitter_offset_time = $this->settingsFunctions->fts_get_option( 'twitter_time' );
        $timezone_set            = $this->settingsFunctions->fts_get_option( 'timezone' );

        // Facebook & Twitter Feed: Create a new DateTimeZone object.
        // Using the default WordPress timezone options does not always work so users need a way to correct the time if needed.
        $fts_timezone = \in_array( $timezone_set, timezone_identifiers_list(), true ) ? new \DateTimeZone($timezone_set) : null;

        if ( empty( $fts_custom_date ) && empty( $fts_custom_time ) ) {
            $custom_date_check = $custom_date;
        } elseif ( $fts_custom_date !== '' || $fts_custom_time !== '' ) {
            $custom_date_check = $fts_custom_date . ' ' . $fts_custom_time;
        } else {
            $custom_date_check = 'F jS, Y \a\t g:ia';
        }

        // Twitter date time.
        if ( 'twitter' === $feed_type ) {

            $fts_twitter_offset_time_final = $fts_twitter_offset_time === '1' ? strtotime( $created_time ) - 3 * 3600 : strtotime( $created_time );

            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->ftsAgo( $created_time );
            } else {
                $u_time = ! empty( $custom_date_check ) ? wp_date( $custom_date_check, $fts_twitter_offset_time_final, $fts_timezone ) : $this->ftsAgo( $created_time );
            }
        }

        // Instagram date time.
        if ( 'instagram' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->ftsAgo( $created_time );
            } else {
                $u_time = ! empty( $custom_date_check ) ? wp_date( $custom_date_check, strtotime( $created_time ), null ) : $this->ftsAgo( $created_time );
            }
        }

        // Facebook date time.
        if ( 'facebook' === $feed_type ) {

            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->ftsAgo( $created_time );
            } else {
                $u_time = ! empty( $custom_date_check ) ? wp_date( $custom_date_check, $created_time, $fts_timezone ) : $this->ftsAgo( $created_time );
            }
        }

        // YouTube date time.
        if ( 'youtube' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->ftsAgo( $created_time );
            } else {
                $u_time = ! empty( $custom_date_check ) ? wp_date( $custom_date_check, strtotime( $created_time ), null ) : $this->ftsAgo( $created_time );
            }
        }

        // Return the time!
        return $u_time;
    }

    /**
     * FTS FB Color Options Head CSS
     *
     * Color Options CSS for Facebook.
     *
     * @since 1.9.6
     */
    public function ftsCustomHeadCss() {

        if ( empty( $this->settingsFunctions->fts_get_option( 'use_custom_css' ) !== '1' ) ) {
            return;
        }
        ?>
        <style type="text/css"><?php echo esc_html(  !empty( $this->settingsFunctions->fts_get_option( 'custom_css' ) ) ?  $this->settingsFunctions->fts_get_option( 'custom_css' ) : ''  ); ?></style>
        <?php
    }

    /**
     * Feed Them Admin CSS
     *
     * Admin CSS.
     *
     * @since 1.9.6
     */
    public function feedThemAdminCss() {
        wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.min.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
        wp_enqueue_style( 'feed_them_admin' );
    }

    /**
     * Feed Them System Info CSS
     *
     * Admin System Info CSS.
     *
     * @since 1.9.6
     */
    public function feedThemSystemInfoCss() {
        wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.min.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
        wp_enqueue_style( 'fts-settings-admin-css' );
    }

    /**
     * Feed Them Settings
     *
     * Admin Settings Scripts and CSS.
     *
     * @since 1.9.6
     */
    public function feedThemSettings() {

        wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.min.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION, false );
        wp_enqueue_style( 'feed_them_settings_css' );

    }

    /**
     * My FTS Ajaxurl
     *
     * Ajax var on front end for twitter videos and  Load More (if premium active).
     *
     * @since 1.9.6
     */
    public function myFtsAjaxurl() {
        wp_enqueue_script( 'jquery' );
    }

    /**
     * My FTS FB Load More
     *
     * This function is being called from the fb feed... it calls the ajax in this case.
     *
     * @since 1.9.6
     * @updated 2.1.4 (fts_fb_page_token)
     */
    public function myFtsFbLoadMore() {

        // Check security token is set.
        if ( ! isset( $_REQUEST['fts_security'], $_REQUEST['fts_time'], $_REQUEST['feed_name'], $_REQUEST['feed_id'] ) ) {
            exit( 'Sorry, You can\'t do that!' );
        }

        // Verify Nonce Security.
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['fts_security'] ) ) , sanitize_text_field( wp_unslash( $_REQUEST['fts_time'] ) ) . 'load-more-nonce' ) ) {
            exit( 'Sorry, You can\'t do that!' );
        }

        $shortcode = '';
        switch ( $_REQUEST['feed_name'] ) {
            case 'feed_them_social':
                $cpt_id = (int) $_REQUEST['feed_id'];
                $shortcode = "[feed_them_social cpt_id={$cpt_id}]";
                break;
            case 'fts_fb_page_token':

                if ( ! current_user_can( 'manage_options' ) ) {
                    exit( esc_html__( 'Forbidden', 'feed_them_social' ) );
                }

                $cpt_id = (int) $_REQUEST['feed_id'];
                $shortcode = "[fts_fb_page_token cpt_id={$cpt_id}]";
                break;

            default:
                exit( esc_html__( 'That is not an FTS shortcode!', 'feed_them_social' ) );
        }

        /**
         * Mapping the request parameter to the next URL host
         *
         *
         */

        if ( ( isset( $_REQUEST['next_url'] ) && !empty( $_REQUEST['next_url'] ) ) || ( isset( $_REQUEST['next_location_url'] ) && !empty( $_REQUEST['next_location_url'] ) ) ) {

            $next_urls = [
                'graph.facebook.com',
                'www.googleapis.com',
                'graph.instagram.com'
            ];

            if ( isset( $_REQUEST['next_url'] ) ) {

                $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );


            } elseif ( isset( $_REQUEST['next_location_url'] ) ) {

                $next_url_host = parse_url( $_REQUEST['next_location_url'],  PHP_URL_HOST );

            }

            if ( ! in_array( $next_url_host, $next_urls ) ) {
                exit( esc_html__( 'Looks like you entered an invalid URL', 'feed_them_social' ) );
            }

        }

        if ( empty( $shortcode ) ) {
            exit( esc_html__( 'That is not an FTS shortcode!', 'feed_them_social' ) );
        }

        echo do_shortcode( $shortcode );

        die();
    }

    /**
     * FTS Instagram Refresh Token
     * Use the Instagram Business Basic Access Token and refresh it every 54 days.
     *
     * @since 4.3.2
     */
    public function ftsInstagramRefreshToken( $feedCptId ) {

        // https://developers.facebook.com/docs/instagram-platform/instagram-api-with-instagram-login/business-login
        // refresh token!
        $check_token =  $this->getFeedOption( $feedCptId, 'fts_instagram_custom_api_token' );

        $check_basic_token_value = $this->dataProtection->decrypt( $check_token ) !== false ? $this->dataProtection->decrypt( $check_token ) : $check_token;
        $oauth2token_url  = esc_url_raw( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token' . FTS_AND_ACCESS_TOKEN_EQUALS . $check_basic_token_value );

        // Make the request
        $get_response = wp_remote_get( $oauth2token_url );

        // Check for errors
        if ( is_wp_error( $get_response ) ) {
            DebugLog::log( 'FeedFunctions', 'Instagram token refresh failed', $get_response->get_error_message() );
            return; // Stop execution if there is an error
        }

        // Get the body of the response and decode it
        $response_body = wp_remote_retrieve_body( $get_response );
        $response = json_decode( $response_body, true );

        DebugLog::log( 'FeedFunctions', 'Instagram token refresh response', $response );

        // https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=IGQWRNQUJZAWW03ESTdITHlvOWtZASmtoZADh4RkstZAE5ULTNIdkpfUHB4b3o4SEh4QTMtUTBsYmlhUHJRcXMtRU5jVlhySUFkRkkyYWdPWnlvdlVhTXpOb3pweG5QcnhxZAlhPTjdBdGVEQjdlZAwZDZD
        // Example Response from Instagram:
        // {
        //   "access_token": "IGQWRNQUJZAWW03ESTdITHlvOWtZASmtoZADh4RkstZAE5ULTNIdkpfUHB4b3o4SEh4QTMtUTBsYmlhUHJRcXMtRU5jVlhySUFkRkkyYWdPWnlvdlVhTXpOb3pweG5QcnhxZAlhPTjdBdGVEQjdlZAwZDZD",
        //   "token_type": "bearer",
        //   "expires_in": 5165891,
        //   "permissions": "instagram_business_basic"
        // }
        //

        if ( isset( $response['access_token'], $response['expires_in'] ) ) {
            $access_token = $this->dataProtection->encrypt( $response['access_token'] );
            $expires_in = $response['expires_in'];

            $start_of_time = strtotime( '+' . $expires_in . self::SECONDS_WORD );
            // We add * 1000 to convert to milliseconds because that is how we display it in the feed options for the user in the Token area.
            $start_of_time_final = $start_of_time !== false ? sanitize_key( $start_of_time * 1000 ) : '';

            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_instagram_custom_api_token', $access_token, true, $feedCptId, true );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_instagram_custom_api_token_expires_in', $start_of_time_final, true, $feedCptId, true );

            DebugLog::log( 'FeedFunctions', 'Updated Instagram Business Basic Token for feed', $feedCptId );

            return false;
        }

        // Return if no access token queried from refresh token.
        // Make sure this does not cause error on front end feed if cached already.
        DebugLog::log( 'FeedFunctions', 'No Token returned from Instagram', $feedCptId );
    }

    /**
     * FTS YouTube Refresh Token
     *
     * @since 4.3.2
     */
    public function ftsYoutubeRefreshToken( $feedCptId ) {

        $check_refresh_token =  $this->getFeedOption( $feedCptId, 'youtube_custom_refresh_token' );
        $check_refresh_token_value = $this->dataProtection->decrypt( $check_refresh_token ) !== false ? $this->dataProtection->decrypt( $check_refresh_token ) : $check_refresh_token;

        // In case we need to assist with debugging we add the
        // cpt id, server and script uri to the post data.
        $server     = !empty( site_url() ) ? esc_url( site_url() ) : 'na';
        $script_uri = !empty($_SERVER['SCRIPT_URI']) ? ' & Script URI ' . esc_url( $_SERVER['SCRIPT_URI'] ) : 'na';

        $postdata = http_build_query(
            array(
                'feed_them_social'  => 'yes',
                'cpd_id'              => $feedCptId,
                'server'            => $server . $script_uri,
                'fts_refresh_token' => $check_refresh_token_value,
                'expires_in'        => $this->getFeedOption( $feedCptId, 'youtube_custom_token_exp_time' ),
                'fts_oauth_nonce'   => wp_create_nonce( 'fts_oauth_youtube' ),
            )
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://youtube-token-refresh.feedthemsocial.com' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Cache-Control: no-cache';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $response = json_decode($result);

        DebugLog::log( 'FeedFunctions', 'YouTube token refresh response', $response );

        $nonce   = $response->fts_oauth_nonce ?? null;
        $post_id = $response->fts_cpt_id ?? null;

        // Check if nonce is valid and cross check the post id.
        if ( ! isset( $nonce ) || wp_verify_nonce( $nonce, 'fts_oauth_youtube' ) !== 1 && $post_id === $feedCptId ) {

            DebugLog::log( 'FeedFunctions', 'Invalid YouTube oauth nonce', true );

            wp_die( __( 'Invalid YouTube oauth nonce', 'feed-them-social' ) );
        }

        // Update the new access token and expires in time.
        if( !empty( $response->access_token ) && !empty( $response->expires_in ) ){

            $access_token = $response->access_token ?? null;
            // 10-25-24: Need to implement a refresh token time to get a new one so the user does not have to keep re-authenticating.
            // $refresh_token = $response->refresh_token ?? null;
            $expires_in = $response->expires_in ?? null;

            $start_of_time = isset( $expires_in ) ? strtotime( '+' . $expires_in . self::SECONDS_WORD) : '';
            // We add * 1000 to convert to milliseconds because that is how we display it in the feed options for the user in the Token area.
            $start_of_time_final = $start_of_time !== false ? sanitize_key( $start_of_time * 1000 ) : '';

            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_access_token', $access_token, true, $feedCptId, true );
            // 10-25-24: Need to implement a refresh token time to get a new one so the user does not have to keep re-authenticating.
            // $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_refresh_token', $refresh_token, true, $feedCptId, true );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_token_exp_time', $start_of_time_final, true, $feedCptId, true );

            return false;
        }

        // Return if no access token queried from refresh token.
        // This will stop error on front end feed if cached already.
        DebugLog::log( 'FeedFunctions', 'YouTube Token Not Returned', true );

    }

    /**
     * FTS TikTik Refresh Token
     *
     * @since 4.2.1
     */
    public function ftsTiktokRefreshToken( $feedCptId ) {

        // In case we need to assist with debugging we add the
        // cpt id, server and script uri to the post data.
        $server     = !empty( site_url() ) ? esc_url( site_url() ) : 'na';
        $script_uri = !empty($_SERVER['SCRIPT_URI']) ? ' & Script URI ' . esc_url( $_SERVER['SCRIPT_URI'] ) : 'na';

        $postdata = http_build_query(
            array(
                'feed_them_social'  => 'yes',
                'cpd_id'              => $feedCptId,
                'server'            => $server . $script_uri,
                'fts_refresh_token' => $this->getFeedOption( $feedCptId, 'fts_tiktok_refresh_token' ),
                'expires_in'        => $this->getFeedOption( $feedCptId, 'fts_tiktok_refresh_expires_in' ),
                'fts_oauth_nonce'   => wp_create_nonce( 'fts_oauth_tiktok' ),
            )
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://tiktok-token-refresh.feedthemsocial.com' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Cache-Control: no-cache';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $response = json_decode($result);

        // Testing
        DebugLog::log( 'FeedFunctions', 'Show fts_tiktok_refresh_token $postdata', $postdata );

        $nonce   = $response->fts_oauth_nonce ?? null;
        $post_id = $response->fts_cpt_id ?? null;

        // Check if nonce is valid and cross check the post id.
        if ( ! isset( $nonce ) || wp_verify_nonce( $nonce, 'fts_oauth_tiktok' ) !== 1 && $post_id === $feedCptId ) {
            DebugLog::log( 'FeedFunctions', 'Invalid TikTok oauth nonce for refresh token', true );
            wp_die( __( 'Invalid TikTok oauth nonce', 'feed-them-social' ) );
        }

        DebugLog::log( 'FeedFunctions', 'Show TikTok $response', $response );
        DebugLog::log( 'FeedFunctions', 'Show TikTok $result', $result );

        // Example Response
        // access_token": "act.example12345Example12345Example",
        // "expires_in": 86400,
        // "open_id": "asdf-12345c-1a2s3d-ac98-asdf123as12as34",
        // "refresh_expires_in": 31536000,
        // "refresh_token": "rft.example12345Example12345Example",
        // "scope": "user.info.basic,video.list",
        // "token_type": "Bearer"

        $expires_in = $response->expires_in ?? null;
        $access_token = $response->access_token ?? null;
        $refresh_expires_in = $response->refresh_expires_in ?? null;
        $refresh_token = $response->refresh_token ?? null;

        // Debugging: Print the values
        DebugLog::log( 'TikTokFeedFunctions', 'Nonce', $nonce );
        DebugLog::log( 'TikTokFeedFunctions', 'Post ID', $feedCptId );
        DebugLog::log( 'TikTokFeedFunctions', 'expires_in', $expires_in );
        DebugLog::log( 'TikTokFeedFunctions', 'access_token', $access_token );
        DebugLog::log( 'TikTokFeedFunctions', 'refresh_expires_in', $refresh_expires_in );
        DebugLog::log( 'TikTokFeedFunctions', 'refresh_token', $refresh_token );

        // Check if any of the variables are empty
        if (!empty($expires_in) &&
            !empty($access_token) &&
            !empty($refresh_expires_in) &&
            !empty($refresh_token)) {

            $start_of_time = isset( $expires_in ) ? strtotime( '+' . $expires_in . self::SECONDS_WORD ) : '';
            // We add * 1000 to convert to milliseconds because that is how we display it in the feed options for the user in the Token area.
            $start_of_time_final = $start_of_time !== false ? sanitize_key( $start_of_time * 1000 ) : '';

            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_saved_time_expires_in', $start_of_time_final, true, $feedCptId, true );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_access_token', $access_token, true, $feedCptId, true );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_expires_in', $refresh_expires_in, true, $feedCptId, true );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_token', $refresh_token, true, $feedCptId, true );

            DebugLog::log( 'TikTokFeedFunctions', 'Updated TikTok Token for feed', $feedCptId );

            return false;
        }
        DebugLog::log( 'TikTokFeedFunctions', 'No Token returned from TikTok', $feedCptId );
    }

    /**
     * Get Random String
     *
     * Generates a random string.
     *
     * @param int $length String Length.
     * @return string
     * @since 1.9.6
     */
    public function getRandomString( $length = 10 ) {
        $characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = \strlen( $characters );
        $random_string     = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
        }

        //Return the Random String.
        return $random_string;
    }

    /**
     * Social Follow Buttons
     *
     * @param string $feed feed type.
     * @param string $user_id user id.
     * @param null   $saved_feed_options shortcode attribute.
     * @since 1.9.6
     */
    public function socialFollowButton( $feed, $user_id, $saved_feed_options = null ) {

        // Return Social follow button based on Feed Type.
        switch ( $feed ) {
            case 'facebook':
                // Facebook settings options for follow button.
                $fb_show_follow_btn            = isset( $saved_feed_options[ 'fb_show_follow_btn' ] ) ? $saved_feed_options[ 'fb_show_follow_btn' ] : '';
                $fb_show_follow_like_box_cover = isset( $saved_feed_options[ 'fb_show_follow_like_box_cover' ] ) ? $saved_feed_options[ 'fb_show_follow_like_box_cover' ] : '';
                $language_option_check         = isset( $saved_feed_options[ 'fb_language' ] ) ? $saved_feed_options[ 'fb_language' ] : '';

                if ( isset( $language_option_check ) && $language_option_check !== 'Please Select Option' ) {
                    $language_option = $saved_feed_options[ 'fb_language' ];
                } else {
                    $language_option = 'en_US';
                }
                $fb_like_btn_color = $saved_feed_options['fb_like_btn_color'] ?? '';
                $show_faces        = $fb_show_follow_btn === 'like-button-share-faces' || $fb_show_follow_btn === 'like-button-faces' || $fb_show_follow_btn === 'like-box-faces' ? 'true' : 'false';
                $share_button      = $fb_show_follow_btn === 'like-button-share-faces' || $fb_show_follow_btn === 'like-button-share' ? 'true' : 'false';
                $page_cover        = $fb_show_follow_like_box_cover === 'fb_like_box_cover-yes' ? 'true' : 'false';

                ?><div id="fb-root"></div>
                <script>
                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/' . <?php echo esc_html( $language_option ) ?> . '/sdk.js#xfbml=1&appId=&version=v3.1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, "script", "facebook-jssd"));

                    // Check to see if class .fts-fb-likeb-scripts-loaded is applied to body, this tells us the page has loaded once already
                    // and now we need to run the FB.XFBML script again so the likebox/button loads.
                    if( jQuery("body.wp-admin.fts-fb-likeb-scripts-loaded").length ) {

                        FB.XFBML.parse();
                    }
                    jQuery("body.wp-admin").addClass("fts-fb-likeb-scripts-loaded");

                </script>
                <?php
                // Page Box!
                if ( $fb_show_follow_btn === 'like-box' || $fb_show_follow_btn === 'like-box-faces' ) {

                    $facebook_like_box_width = isset( $saved_feed_options['facebook_like_box_width'] ) && $saved_feed_options['facebook_like_box_width'] !== '' ? $saved_feed_options['facebook_like_box_width'] : '500px';

                    echo '<div class="fb-page" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-hide-cover="' . esc_html( $page_cover ) . '" data-width="' . esc_html( $facebook_like_box_width ) . '"  data-show-facepile="' . esc_html( $show_faces ) . '" data-show-posts="false"></div>';
                } else {
                    echo '<div class="fb-like" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-layout="standard" data-action="like" data-colorscheme="' . esc_html( $fb_like_btn_color ) . '" data-show-faces="' . esc_html( $show_faces ) . '" data-share="' . esc_html( $share_button ) . '" data-width:"100%"></div>';
                }
                break;
            case 'instagram':
                echo '<a href="' . esc_url( 'https://instagram.com/' . $user_id . '/' ) . '" target="_blank" rel="noreferrer">' . esc_html( 'Follow on Instagram', 'feed-them-social' ) . '</a>';
                break;
            case 'tiktok':
                echo '<div class="fts-tiktok-bio-follow-button"><a href="'. esc_url( $user_id ) .'" target="_blank">' . esc_html( $saved_feed_options['tiktok_follow_on_tiktok'] ?? 'Follow on TikTok' ) . '</a></div>';
                break;

            case 'youtube':

                echo '<script src="' . esc_url( 'https://apis.google.com/js/platform.js' ) . '"></script>';

                if ( $saved_feed_options['youtube_feed_type'] === 'channelID' && !empty( $saved_feed_options['youtube_channelID'] ) ) {
                    echo '<div class="g-ytsubscribe" data-channelid="' . esc_html( $saved_feed_options['youtube_channelID'] ) . '" data-layout="full" data-count="default"></div>';
                }
                elseif ( $saved_feed_options['youtube_feed_type'] === 'playlistID' && !empty( $saved_feed_options['youtube_channelID2'] ) ) {
                    echo '<div class="g-ytsubscribe" data-channelid="' . esc_html( $saved_feed_options['youtube_channelID2'] ) . '" data-layout="full" data-count="default"></div>';
                }
                elseif ( $saved_feed_options['youtube_feed_type'] === 'userPlaylist' && !empty( $saved_feed_options['youtube_name2'] ) ) {
                    echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $saved_feed_options['youtube_name2'] ) . '" data-layout="full" data-count="default"></div>';
                }
                elseif ( $saved_feed_options['youtube_feed_type'] === 'username' && !empty( $saved_feed_options['youtube_name'] ) ) {
                    echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $saved_feed_options['youtube_name']  ) . '" data-layout="full" data-count="default"></div>';
                }
                break;
            default:
                break;
        }
    }

    /**
     * FTS Share Option
     *
     * @param string $fb_link link for social network.
     * @param string $description description field for some of the social networks.
     * @since
     */
    public function ftsShareOption( $fb_link, $description ) {

        $hide_share = $this->settingsFunctions->fts_get_option( 'hide_sharing' ) ?? '';

        if ( isset( $hide_share ) && $hide_share !== '1' ) {
            // Social media sharing URLs
            $link                      = $fb_link;
            $description               = wp_strip_all_tags( $description );
            $ft_gallery_share_linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link;
            $ft_gallery_share_email    = 'mailto:?subject=Shared Link&body=' . $link . ' - ' . $description;
            $ft_gallery_share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $link;
            $ft_gallery_share_twitter  = 'https://twitter.com/intent/tweet?text=' . $link . '+' . $description;

            // The share wrap and links
            $output  = '<div class="fts-share-wrap">';
            $output .= '<a href="javascript:;" class="ft-gallery-link-popup" title="' . esc_html__( 'Social Share Options', 'feed-them-social' ) . '"></a>';
            $output .= '<div class="ft-gallery-share-wrap">';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_facebook ) . '" target="_blank" rel="noreferrer" class="ft-galleryfacebook-icon" title="Share this post on Facebook"><i class="fa fa-facebook-square"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_twitter ) . '" target="_blank" rel="noreferrer" class="ft-gallerytwitter-icon" title="Share this post on Twitter"><i class="fa fa-twitter"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_linkedin ) . '" target="_blank" rel="noreferrer" class="ft-gallerylinkedin-icon" title="Share this post on Linkedin"><i class="fa fa-linkedin"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_email ) . '" target="_blank" rel="noreferrer" class="ft-galleryemail-icon" title="Share this post in your email"><i class="fa fa-envelope"></i></a>';
            $output .= '</div>';
            $output .= '</div>';
            return $output;
        }
    }

    /**
     * FTS Instagram Token Ajax
     *
     * This will save the encrypted version of the token to the database and return the original token to the input field upon page submit.
     *
     * @since 2.9.7.2
     */
    public function ftsEncryptTokenAjax() {

        check_ajax_referer( 'fts_encrypt_token' );

        $access_token = json_decode( wp_unslash( $_REQUEST['access_token'] ) , true );
        $encrypt      = $this->dataProtection->encrypt( $access_token['token'] );

        DebugLog::log( 'FeedFunctions', 'Encrypt Access Token', $access_token );

        $cpt_id = (int) $_REQUEST['cpt_id'];

        // Now the encrypted version is saved to the DB.
        if( $_REQUEST['token_type'] === 'basic' ){
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_instagram_custom_api_token', $encrypt, true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_instagram_custom_id', $access_token['user_id'], true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_instagram_custom_api_token_expires_in', $access_token['expires_in'], true, $cpt_id, false );

            $cron_job = new CronJobs( null, $this->optionsFunctions, null, null );
            $cron_job->ftsSetCronJob( $cpt_id, 'instagram_business_basic', false );

        }
        elseif ( $_REQUEST['token_type'] === 'business' ) {
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_instagram_custom_api_token', $encrypt, true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_instagram_custom_api_token_user_id', $access_token['user_id'], true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_instagram_custom_api_token_user_name', $access_token['instagram_user_name'], true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_instagram_custom_api_token_fb_user_name', $access_token['facebook_user_name'], true, $cpt_id, false );
        }
        elseif( $_REQUEST['token_type'] === 'fbBusiness' ){
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_custom_api_token', $encrypt, true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_custom_api_token_user_id', $access_token['user_id'], true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_facebook_custom_api_token_user_name', $access_token['facebook_user_name'], true, $cpt_id, false );
        }
        elseif( $_REQUEST['token_type'] === 'tiktok' ){

            if( isset($access_token['revoke_token']) && $access_token['revoke_token'] === 'yes' ){
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_user_id', ' ', true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_access_token', ' ', true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_expires_in', ' ', true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_saved_time_expires_in', ' ', true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_token', ' ', true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_expires_in', ' ', true, $cpt_id, false );

                $cron_job = new CronJobs( null, $this->optionsFunctions, null, null );
                $cron_job->ftsSetCronJob( $cpt_id, 'tiktok', true );
            }
            else {
                // atm we are not encrypting tiktok tokens.
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_user_id', $access_token['user_id'], true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_access_token', $access_token['token'], true, $cpt_id, false );
                // The expiration time is 24hrs from the time the token is created (86400) That is the value the expires_in returns from TikTok.
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_expires_in', $access_token['expires_in'], true, $cpt_id, false );
                // we are adding time() to the expires_in so we can check if the token is expired. The expiration time is 24hrs from the time the token is created.
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_saved_time_expires_in', time() * 1000, true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_token', $access_token['refresh_token'], true, $cpt_id, false );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_tiktok_refresh_expires_in', $access_token['refresh_expires_in'], true, $cpt_id, false );

                // Once the token is saved we need to check if it is expired and if it is we need to refresh it.
                // We create a cron job that will refresh the token every 24hrs.
                // The caveat is that a user must visit the site for the cron job to run so we will need to make
                // sure the feed stays cached until the cron job runs.
                $cron_job = new CronJobs( null, $this->optionsFunctions, null, null );
                $cron_job->ftsSetCronJob( $cpt_id, 'tiktok', false );
            }

        }
        elseif( $_REQUEST['token_type'] === 'youtube' ){
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_access_token', $access_token['token'], true, $cpt_id, false );
            // Encrypting the refresh token because it lasts longer than 1 Hour.
            $encrypt_refresh_token = $this->dataProtection->encrypt( $access_token['refresh_token'] );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_refresh_token', $encrypt_refresh_token, true, $cpt_id, false );
            $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'youtube_custom_token_exp_time', $access_token['exp_time'], true, $cpt_id, false );

            $cron_job = new CronJobs( null, $this->optionsFunctions, null, null );
            $cron_job->ftsSetCronJob( $cpt_id, 'youtube', false );
        }

        $token_data = array (
            'feed_type'  => $access_token['feed_type'],
            'id'         => $cpt_id,
            'token'      => $access_token['token'],
            'encrypted'  => $encrypt,
        );

        // Passing array so we can see it in the console and do other stuff on success based on the feed type
        echo json_encode( $token_data );

        wp_die();
    }

    /**
     * FTS Feed Refresh Ajax
     *
     * This will refresh the feed in the CPT while the user is making changes.
     *
     * @since 4.0
     */
    public function ftsRefreshFeedAjax() {

        check_ajax_referer( 'fts_refresh_feed_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( esc_html__( 'Forbidden', 'feed_them_social' ), 403 );
        }

        $cpt_id = (int) $_REQUEST['cpt_id'];

        // We pass the original access token back so we can add it to our input field.
        // Also passing the encrypted token so we can see it in the console.
        echo do_shortcode('[feed_them_social cpt_id='. esc_html( $cpt_id ) .']');

        wp_die();
    }

    /**
     * FTS Instagram Token Ajax
     *
     * This will save the encrypted version of the token to the database and return the original token to the input field upon page submit.
     *
     * @since 2.9.7.2
     */
    public function ftsDecryptTokenAjax() {

        check_ajax_referer( 'fts_decrypt_token' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( esc_html__( 'Forbidden', 'feed_them_social' ), 403 );
        }

        $access_token            = $_REQUEST['encrypted_token'];
        echo $this->dataProtection->decrypt( $access_token );

        wp_die();
    }

    /**
     * Use Cache Check
     *
     * Checks to see if we need to use cache or not. NEEDS TO BE SORTED TO PROPER FILE AFTER 4.0 Launch.
     *
     * @param string|array $api_url API Call.
     * @param string       $cache_name Cache name.
     * @return array|mixed
     * @throws \Exception Throw Exception if all fails.
     * @since
     */
    public function useCacheCheck( $api_url, $cache_name, $feed_type ) {
        if ( !isset( $_GET['load_more_ajaxing'] ) && $this->feedCache->ftsCheckFeedCacheExists( $cache_name ) === true ) {
            $response = $this->feedCache->ftsGetFeedCache( $cache_name );
            return $response;
        }

        $fts_error_check = new ErrorHandler();
        // Error Check.
        if( $feed_type === 'youtube' ){
            $response = $this->ftsGetFeedJson( $api_url );
            $feed_data = json_decode( $response['data'] );
            $fts_error_check_complete = $fts_error_check->youtubeErrorCheck( $feed_data );
        }

        if( $feed_type === 'youtube_single' ){
            $response = $this->ftsGetFeedJson( $api_url );
            $feed_data = json_decode( $response['items'] );
            $fts_error_check_complete = $fts_error_check->youtubeErrorCheck( $feed_data );
        }

        if( $feed_type === 'instagram' ){
            $instagram_basic_response = $this->ftsGetFeedJson( $api_url );
            $instagram_basic = json_decode( $instagram_basic_response['data']);
            $instagram_basic_user = json_decode( $instagram_basic_response['user_info'] );

            // Use for testing to return print_r( $instagram_basic_user );

            $instagram_basic_output = (object)['data' => []];

            if ( !empty( $instagram_basic->data ) ) {

                // We need to get the access token from the url and decrypt it.

                $parts = parse_url($api_url['data']);
                parse_str( $parts['query'], $query);
                $access_token = $this->dataProtection->decrypt(  $query['access_token'] ) !== false ? $this->dataProtection->decrypt(  $query['access_token'] ) : $query['access_token'];

                // We loop through the media ids from the above $instagram_basic_data_array['data'] and request the info for each to create an array we can cache.
                foreach ( $instagram_basic->data as $media ) {


                    $media_id = $media->id;
                    $instagram_basic_data_array['data'] = 'https://graph.instagram.com/' . $media_id . '?fields=caption,id,media_url,media_type,permalink,thumbnail_url,timestamp,username,children{media_url}' . FTS_AND_ACCESS_TOKEN_EQUALS . $access_token;
                    $instagram_basic_media_response = $this->ftsGetFeedJson( $instagram_basic_data_array );
                    $instagram_basic_media = json_decode( $instagram_basic_media_response['data'] );
                    $instagram_basic_output->data[] = $instagram_basic_media;
                }
            }

            $feed_data = (object) array_merge( (array) $instagram_basic_user, (array) $instagram_basic, (array) $instagram_basic_output );
            $response = json_encode( $feed_data );
            $fts_error_check_complete = $fts_error_check->instagramErrorCheck( $instagram_basic );
        }

        // Check the response print_r( $fts_error_check_complete ) or response print_r( $response );

        // An Access token will expire every 60 minutes for Youtube.
        // Instagram Basic token expires every 60 days, but we are going to refresh the token every 7 days for now.
        // When a user refreshes any page on the front end or backend settings page we user our refresh token to get a new access token if the time has expired.
        // If the time has passed before a user has refreshed the website, then the API call will error, and we don't want to cache that error.
        // Instead we allow the cached version to be served and upon page reload the new access token will be saved to the db via ajax and the feed will continue to show.
        // Yes works for front end users not logged in too because we use nopriv for the add_action ajax call.
        if ( \is_array( $fts_error_check_complete ) && $fts_error_check_complete[0] === true ) {

            // If old Cache exists use it instead of showing an error.
            if ( $this->feedCache->ftsCheckFeedCacheExists( $cache_name, true ) === true ) {

                // If Current user is Admin and Cache exists for use, then still show Admin the error for debugging purposes.
                if ( current_user_can( 'administrator' ) ) {
                    echo wp_kses(
                        $fts_error_check_complete[1] . ' <em>NOTICE: Error only visible to Admin.</em>',
                        array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                            ),
                            'br' => array(),
                            'em' => array(),
                            'strong' => array(),
                        )
                    );
                }

                // Return Cache because it exists in Database. Better than showing nothing right?
                return $this->feedCache->ftsGetFeedCache( $cache_name, true );
            }

            // If User is Admin and no old cache is saved in database for use.
            if ( current_user_can( 'administrator' ) ) {
                echo $fts_error_check_complete[0];
            }
        }

        // Finally if nothing else, check if there is a response and if so create the cache.
        if( ($feed_type === 'youtube_single') && !empty( $response['data'] ) ) {
            $this->feedCache->ftsCreateFeedCache( $cache_name, $response );
        }

        if( ($feed_type === 'youtube') && !empty( $response['data'] ) ) {
            $this->feedCache->ftsCreateFeedCache( $cache_name, $response );
        }

        if( ($feed_type === 'instagram') && !empty( $instagram_basic->data ) ) {
            $this->feedCache->ftsCreateFeedCache( $cache_name, $response );
        }

        return $response;
    }

    /**
     * XML json Parse. Only being used for the FB Feed Language Option.
     *
     * @param string $url string to parse the content for.
     * @return mixed
     * @since 1.9.6
     */
    public function xmlJsonParse( $url ) {

        DebugLog::log( 'FeedFunctions', 'xml_json_parse', $url );

        $url_to_get['xml_url']      = $url;
        $file_contents_returned = $this->ftsGetFeedJson( $url_to_get );
        $file_contents          = $file_contents_returned['xml_url'];
        $file_contents          = str_replace( array( "\n", "\r", "\t" ), '', $file_contents );
        $file_contents          = trim( str_replace( '"', "'", $file_contents ) );
        $simple_xml             = simplexml_load_string( $file_contents );
        return json_encode( $simple_xml );
    }
}
