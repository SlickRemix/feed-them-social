<?php
/**
 * Feed Them Social - Feed Cache
 *
 * This page is used to create the Twitter feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2021, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;


/**
 * Class Feed_Cache
 * @package feedthemsocial
 */
class Feed_Cache {
	/**
	 * Construct
	 *
	 * Functions constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
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
		// This is for the fts_clear_cache_ajax submission!
		if ( 'show-admin-bar-menu' === get_option( 'fts_admin_bar_menu' ) ) {
			add_action( 'init', array( $this, 'fts_clear_cache_script' ) );
			add_action( 'wp_head', array( $this, 'my_fts_ajaxurl' ) );
			add_action( 'wp_ajax_fts_clear_cache_ajax', array( $this, 'fts_clear_cache_ajax' ) );
		}
		add_action( 'wp_ajax_fts_refresh_token_ajax', array( $this, 'fts_refresh_token_ajax' ) );
		add_action( 'wp_ajax_fts_instagram_token_ajax', array( $this, 'fts_instagram_token_ajax' ) );
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
		// Is there old Cache? If so Delete it!
		if ( true === $this->fts_check_feed_cache_exists( $transient_name ) ) {
			// Make Sure to delete old permanent cache before setting up new cache!
			$this->delete_permanent_feed_cache( $transient_name );
		}
		// Cache Time set on Settings Page under FTS Tab.
		$cache_time_limit = true === get_option( 'fts_clear_cache_developer_mode' ) && '1' !== get_option( 'fts_clear_cache_developer_mode' ) ? get_option( 'fts_clear_cache_developer_mode' ) : '900';

		// Timed Cache.
		set_transient( 'fts_t_' . $transient_name, $response, $cache_time_limit );

		// Permanent Feed cache. NOTE set to 0.
		set_transient( 'fts_p_' . $transient_name, $response, 0 );
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
			return get_transient( 'fts_p_' . $transient_name );
		}

		// If no error use Timed Cache!
		return get_transient( 'fts_t_' . $transient_name );
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
		global $wpdb;

		// Clear UnExpired Timed Cache!
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_t_%' ) );
		// Clear Expired Timed Cache!
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_t_%' ) );

		wp_reset_query();
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

		// Clear ONLY Specfic Feeds Cache!
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_p_' . $transient_name ) );

		wp_reset_query();
		return 'Cache for this feed cleared!';
	}


	/**
	 * FTS Clear Cache Script
	 *
	 * This is for the fts_clear_cache_ajax submission.
	 *
	 * @since 1.9.6
	 */
	public function fts_clear_cache_script() {

		$fts_admin_activation_clear_cache = get_option( 'Feed_Them_Social_Activated_Plugin' );
		$fts_dev_mode_cache               = get_option( 'fts_clear_cache_developer_mode' );
		if ( '1' === $fts_dev_mode_cache || 'feed-them-social' === $fts_admin_activation_clear_cache ) {
			wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/developer-admin.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'fts_clear_cache_script' );
		}
		if ( 'hide-admin-bar-menu' !== $fts_dev_mode_cache && '1' !== $fts_dev_mode_cache ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/admin.js' ), array(), FTS_CURRENT_VERSION, false );
			wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/developer-admin.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'fts_clear_cache_script' );
		}

		// we delete this option if found so we only empty the cache once when the plugin is ever activated or updated!
		delete_option( 'Feed_Them_Social_Activated_Plugin' );
	}
}