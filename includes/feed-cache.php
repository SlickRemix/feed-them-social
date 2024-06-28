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

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
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
	public $data_protection;

	/**
	 * Settings Functions
	 *
	 * The settings Functions class
	 *
	 * @var object
	 */
	public $settings_functions;

	/**
	 * Construct
	 *
	 * Functions constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct(  $data_protection, $settings_functions ) {
		// Data Protection Class.
		$this->data_protection = $data_protection;

		// Settings Functions.
		$this->settings_functions = $settings_functions;

		// Add Actions and Filters.
		$this->add_actions_filters();
	}

	/**
	 * add_actions_filters
	 *
	 * For Loading in the Admin.
	 *
	 * @since 1.9.6
	 */
	public function add_actions_filters() {

	    add_action( 'init', array( $this, 'fts_clear_cache_script' ) );
        add_action( 'init', array( $this, 'fts_dev_mode_clear_cache_script' ) );
	    add_action( 'wp_ajax_fts_clear_cache_ajax', array( $this, 'fts_clear_cache_ajax' ) );

        // SRL 4.0: I don't think we need these here.
		// add_action( 'wp_ajax_fts_refresh_token_ajax', array( $this, 'fts_refresh_token_ajax' ) );
		// add_action( 'wp_ajax_fts_instagram_token_ajax', array( $this, 'fts_instagram_token_ajax' ) );
	}

	/**
	 * Get date format options.
	 *
	 * @since	1.0.0
	 * @return	array	Array of date format options
	 */
	public function fts_get_cache_options()	{
		$formats = array(
            '3600'   => __( '1 Hour', 'feed-them-social' ),
            '7200'   => __( '2 Hours', 'feed-them-social' ),
            '10800'   => __( '3 Hours', 'feed-them-social' ),
            '21600'   => __( '6 Hours', 'feed-them-social' ),
            '43200'   => __( '12 Hours', 'feed-them-social' ),
			'86400'   => __( '1 Day', 'feed-them-social' ),
			'172800'  => __( '2 Days', 'feed-them-social' ),
			'259200'  => __( '3 Days', 'feed-them-social' ),
			'604800'  => __( '1 Week', 'feed-them-social' ),
			'1209600' => __( '2 Weeks', 'feed-them-social' ),
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
			default:
            case '3600':
                $fts_display_cache_time = __( '1 Hour', 'feed-them-social' );
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
			case '86400':
				$fts_display_cache_time = __( '1 Day (Default)', 'feed-them-social' );
				break;
			case '172800':
				$fts_display_cache_time = __( '2 Days', 'feed-them-social' );
				break;
			case '259200':
				$fts_display_cache_time = __( '3 Days', 'feed-them-social' );
				break;
			case '604800':
				$fts_display_cache_time = __( '1 Week', 'feed-them-social' );
				break;
			case '1209600':
				$fts_display_cache_time = __( '2 Weeks', 'feed-them-social' );
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
		// YO!
		/* echo '<br/><br/>Now we are in the create feed cache function. What is the response at this point just before we encrypt response.<br/>';
		 print_r($response);*/

		if(is_array($response)){
			$encrypted_response = array();
			foreach ($response as $item_key => $item_value){
				$encrypted_response[ $item_key ] = $this->data_protection->encrypt( $item_value );
			}

			$encrypted_response = serialize($encrypted_response);

			// YO!
			/*echo '<br/><br/> Serialized Array<br/>';
			 print_r($encrypted_response);*/
		}
		else{
			$encrypted_response = $this->data_protection->encrypt( $response );
            // YO!
           /* echo '<br/><br/>#2 Now we have encrypted the data. What is the response at this point.<br/>';
            print_r($encrypted_response);*/

        }

		// Is there old Cache? If so Delete it!
		if ( true === $this->fts_check_feed_cache_exists( $transient_name ) ) {
			// Make Sure to delete old permanent cache before setting up new cache!
			$this->delete_permanent_feed_cache( $transient_name );
		}
		// Cache Time set on Settings Page under FTS Tab. 86400 = 1 day.
		$cache_time_option = $this->settings_functions->fts_get_option('fts_cache_time');
        $cache_time_limit = $cache_time_option ?? '86400';
        // echo '<br/><br/>Check the cache time limit.<br/>';
        // error_log($cache_time_limit);

		//Check an Encrypted Response was returned.
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
		if ( true === $errored ) {
			$trans = get_transient( 'fts_p_' . $transient_name );
		}
		else{
			// If no error use Timed Cache!
			$trans =  get_transient( 'fts_t_' . $transient_name );
		}

		// YO!
        /*echo '<br/>GET CACHE What is the response at this point:<br/>';
        print_r($trans);*/

		if ($trans){

			//is the transient value serialized? If so, un-serialize it!
			$unserialized_value = \maybe_unserialize( $trans );

			// echo '<br/><br/>UNSerialized Array<br/>';
			// print_r($unserialized_value);

			// Is value an array?
			if(is_array($unserialized_value)){
				$decrypted_value = array();
				foreach ($unserialized_value as $item_key => $item_value){
					$decrypted_value[ $item_key ] = $this->data_protection->decrypt( $item_value );
				}
			}
			else{
				// YO!
				 //echo '<br/><br/>Not an array so decrypt string.<br/>';
				// Not an array so decrypt string.
				$decrypted_value = false !== $this->data_protection->decrypt( $trans ) ? $this->data_protection->decrypt( $trans ) : $trans;
			}

			// YO!
			/*echo '<br/><br/>Decrypted!<br/>';
			print_r($decrypted_value);*/
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
		if ( true === $errored && false !== $transient_permanent_check ) {
			return true;
		}
		if ( true !== $errored && false !== $transient_permanent_check && false !== $transient_time_check ) {
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

		wp_reset_query();

        echo 'Success';

        wp_die();
	}

	/**
	 * Feed Them Clear Cache
	 *
	 * Clear ALL FTS Cache.
	 *
	 * @return string
	 * @since 1.9.6
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
            $fts_dev_mode_cache = $this->settings_functions->fts_get_option( 'fts_cache_time' );
            if ( '1' !== $fts_dev_mode_cache ) {
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

    /**
     * FTS Clear Cache Script
     *
     * This is for the fts_clear_cache_ajax submission.
     *
     * @since 1.9.6
     */
    public
    function fts_dev_mode_clear_cache_script ()
    {
        $fts_admin_activation_clear_cache = get_option( 'Feed_Them_Social_Activated_Plugin' );
        $fts_dev_mode_cache = $this->settings_functions->fts_get_option( 'fts_cache_time' );
        if ( '1' === $fts_dev_mode_cache || 'feed_them_social' === $fts_admin_activation_clear_cache ) {
            wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/developer-admin.min.js' ), array('jquery'), FTS_CURRENT_VERSION, false );
            wp_localize_script(
                'fts_clear_cache_script',
                'ftsAjax',
                array(
                    'createNewFeedUrl' => admin_url( 'edit.php?post_type=fts&page=create-new-feed' ),
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'clearCacheNonce' => wp_create_nonce( 'fts_clear_cache' ),
                )
            );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'fts_clear_cache_script' );
        }

        // We delete this option if found so we only empty the cache once when the plugin is ever activated or updated!
        delete_option( 'Feed_Them_Social_Activated_Plugin' );
    }

}