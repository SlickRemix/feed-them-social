<?php
/**
 * Feed Them Social - Feed Cache
 *
 * This page is used to create the Twitter feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\includes;

use feedthemsocial\admin\cron_jobs\CronJobs;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Feed_Cache
 * @package feedthemsocial
 */
class Feed_Cache {

    /**
     * Data Protection
     *
     * The Data Protection class.
     *
     * @var object
     */
    public $dataProtection;

    /**
     * Settings Functions
     *
     * The settings Functions class
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * 1 Day Time.
     *
     * @var string
     */
    const CACHE_TIME_ONE_DAY = '86400';

    /**
     * Construct
     *
     * Functions constructor.
     *
     * @since 1.9.6
     */
    public function __construct(  $dataProtection, $settingsFunctions ) {
        // Data Protection Class.
        $this->dataProtection = $dataProtection;

        // Settings Functions.
        $this->settingsFunctions = $settingsFunctions;

        // Add Actions and Filters.
        $this->addActionsFilters();
    }

    /**
     * addActionsFilters
     *
     * For Loading in the Admin.
     *
     * @since 1.9.6
     */
    public function addActionsFilters() {

        add_action( 'init', array( $this, 'fts_clear_cache_script' ) );
        add_action( 'wp_ajax_fts_clear_cache_ajax', array( $this, 'fts_clear_cache_ajax' ) );
    }

    /**
     * Get date format options.
     *
     * @since    1.0.0
     * @return    array    Array of date format options
     */
    public function fts_get_cache_options()    {
        $formats = array(
            '3600'   => __( '1 Hour', 'feed-them-social' ),
            '7200'   => __( '2 Hours', 'feed-them-social' ),
            '10800'   => __( '3 Hours', 'feed-them-social' ),
            '21600'   => __( '6 Hours', 'feed-them-social' ),
            '43200'   => __( '12 Hours', 'feed-them-social' ),
            self::CACHE_TIME_ONE_DAY => __( '1 Day', 'feed-them-social' ),
            '172800'  => __( '2 Days', 'feed-them-social' ),
            '259200'  => __( '3 Days', 'feed-them-social' ),
            '345600'  => __( '4 Days', 'feed-them-social' ),
        );
        return $formats;
    }

    /**
     * FTS Cachetime amount
     *
     * @param string $fts_cachetime Cache time.
     * @return mixed
     * @since
     */
    public function fts_cachetime_amount( $fts_cachetime ) {
        switch ( $fts_cachetime ) {
            case '1':
                $fts_display_cache_time = __( 'Clear cache on every page load', 'feed-them-social' );
                break;
            case '7200':
                $fts_display_cache_time = __( '2 Hours', 'feed-them-social' );
                break;
            case '10800':
                $fts_display_cache_time = __( '3 Hours', 'feed-them-social' );
                break;
            case '21600':
                $fts_display_cache_time = __( '6 Hours', 'feed-them-social' );
                break;
            case '43200':
                $fts_display_cache_time = __( '12 Hours', 'feed-them-social' );
                break;
            case self::CACHE_TIME_ONE_DAY:
                $fts_display_cache_time = __( '1 Day', 'feed-them-social' );
                break;
            case '172800':
                $fts_display_cache_time = __( '2 Days', 'feed-them-social' );
                break;
            case '259200':
                $fts_display_cache_time = __( '3 Days', 'feed-them-social' );
                break;
            case '345600':
                $fts_display_cache_time = __( '4 Days', 'feed-them-social' );
                break;
            case '3600':
            default:
                $fts_display_cache_time = __( '1 Hour', 'feed-them-social' );
                break;
        }
        return $fts_display_cache_time;
    }

    /**
     * FTS Create Feed Cache
     *
     * Create Feed Cache. This is also where the previous cache is deleted and replace with new cache.
     *
     * @param string $transient_name transient name.
     * @param array  $response Data returned from response.
     * @since 1.9.6
     */
    public function fts_create_feed_cache( $transient_name, $response ) {

        // Now we are in the create feed cache function.
        // The response at this point just before we encrypt response print_r($response).

        if( \is_array($response)){
            $encrypted_response = array();
            foreach ($response as $item_key => $item_value){
                $encrypted_response[ $item_key ] = $this->dataProtection->encrypt( $item_value );
            }

            $encrypted_response = serialize($encrypted_response);
        }
        else{
            $encrypted_response = $this->dataProtection->encrypt( $response );
        }

        // Is there old Cache? If so Delete it!
        if ( $this->fts_check_feed_cache_exists( $transient_name ) === true ) {
            // Make Sure to delete old permanent cache before setting up new cache!
            $this->delete_permanent_feed_cache( $transient_name );
        }
        // Cache Time set on Settings Page under FTS Tab. 86400 = 1 day.
        $cache_time_option = $this->settingsFunctions->fts_get_option('fts_cache_time');
        // Ensure a valid cache time or fallback to default (86400 seconds)
        $cache_time_limit = (is_numeric($cache_time_option) && $cache_time_option > 0) ? $cache_time_option : self::CACHE_TIME_ONE_DAY;

        // Check an Encrypted Response was returned.
        if( $encrypted_response ){
            // Timed Cache.
            set_transient( 'fts_t_' . $transient_name, $encrypted_response, $cache_time_limit );

            // Permanent Feed cache. NOTE set to 0.
            set_transient( 'fts_p_' . $transient_name, $encrypted_response, 0 );
        }
    }

    /**
     * FTS Get Feed Cache
     *
     * @param string  $transient_name Transient name.
     * @param boolean $errored Error Check.
     * @return mixed
     * @since 1.9.6
     */
    public function fts_get_feed_cache( $transient_name, $errored = null ) {

        // If Error use Permanent Cache!
        if ( $errored === true ) {
            $trans = get_transient( 'fts_p_' . $transient_name );
        }
        else{
            // If no error use Timed Cache!
            $trans =  get_transient( 'fts_t_' . $transient_name );
        }

        if ($trans){

            //is the transient value serialized? If so, un-serialize it!
            $unserialized_value = \maybe_unserialize( $trans );

            // Is value an array?
            if( \is_array($unserialized_value)){
                $decrypted_value = array();
                foreach ($unserialized_value as $item_key => $item_value){
                    $decrypted_value[ $item_key ] = $this->dataProtection->decrypt( $item_value );
                }
            }
            else{
                // Not an array so decrypt string.
                $decrypted_value = $this->dataProtection->decrypt( $trans ) !== false ? $this->dataProtection->decrypt( $trans ) : $trans;
            }
        }

        return !empty( $decrypted_value ) ? $decrypted_value : '';
    }

    /**
     * FTS Check Feed Cache Exists
     *
     * @param string  $transient_name transient name.
     * @param boolean $errored Error Check.
     * @return bool
     * @since 1.9.6
     */
    public function fts_check_feed_cache_exists( $transient_name, $errored = null ) {

        $transient_permanent_check = get_transient( 'fts_p_' . $transient_name );
        $transient_time_check      = get_transient( 'fts_t_' . $transient_name );

        // If error exists is set and old cache still exists.
        if ( $errored === true && $transient_permanent_check !== false ) {
            return true;
        }
        if ( $errored !== true && $transient_permanent_check !== false && $transient_time_check !== false ) {
            return true;
        }

        return false;
    }

    /**
     * FTS Clear ALL FTS Cache Ajax
     *
     * @since 1.9.6
     */
    public function fts_clear_cache_ajax() {

        check_ajax_referer( 'fts_clear_cache' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( esc_html__( 'Forbidden', 'feed-them-social' ), 403 );
        }

        global $wpdb;

        // Clear UnExpired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_t_%' ) );
        // Clear Expired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_t_%' ) );

        // Set new cron job when user deletes cache so the cron job time matches up with the new transient_timeout_fts cache time.
        $cron_job = new CronJobs( null, null, null, null );
        $cron_job->ftsSetCronJob( 'clear-cache-set-cron-job', null, null );
        wp_reset_query();
        echo 'Success';
        wp_die();
    }

    /**
     * FTS Clear Cache Cron Job
     *
     * Clear ALL FTS Cache. Used for the Cron Job function.
     *
     * @return string
     * @since 4.3.4
     */
    public function feed_them_clear_cache() {
        global $wpdb;
        // Clear UnExpired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_t_%' ) );
        // Clear Expired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_t_%' ) );
        wp_reset_query();
        return 'Cache for ALL FTS Feeds cleared!';
    }

    /**
     * Delete permanent feed Cache
     *
     * Clear ONLY permanent feed's cache.
     *
     * @return string
     * @since 1.9.6
     */
    public function delete_permanent_feed_cache( $transient_name ) {
        global $wpdb;
        // Clear ONLY Specific Feeds Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_p_' . $transient_name ) );
        wp_reset_query();
        return 'Cache for this feed cleared!';
    }

    /**
     * Feed Them Clear Cache
     *
     * Clear ALL FTS Cache.
     *
     * @return string
     * @since 1.9.6
     */
    public function feed_them_clear_admin_cache() {
        global $wpdb;
        // Clear UnExpired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", 'fts_facebook_%' ) );
        // Clear Expired Timed Cache!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", 'fts_instagram_%' ) );
        wp_reset_query();
        return 'Cache for ALL FTS Admin Options cleared!';
    }

    /**
     * FTS Clear Cache Script
     *
     * This is for the fts_clear_cache_ajax submission.
     *
     * @since 1.9.6
     */
    public function fts_clear_cache_script()
    {
        if( is_user_logged_in() ) {
            $fts_dev_mode_cache = $this->settingsFunctions->fts_get_option( 'fts_cache_time' );
            if ( $fts_dev_mode_cache !== '1' ) {
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/admin.min.js' ), array('jquery'), FTS_CURRENT_VERSION, false );
                wp_localize_script(
                    'fts_clear_cache_script',
                    'ftsAjax',
                    array(
                        'createNewFeedUrl' => admin_url( 'edit.php?post_type=fts&page=create-new-feed' ),
                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                        'clearCacheNonce' => wp_create_nonce( 'fts_clear_cache' ),
                    )
                );
                wp_enqueue_script( 'fts_clear_cache_script' );
            }
        }
    }
}
