<?php namespace feedthemsocial;
/**
 * Feeds Functions Class
 *
 * This page is used to create the Facebook Access Token options!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.0
 */

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feed Functions Class
 */
class Feed_Functions {

	/**
	 * Feed Settings Array
	 * An array of Feed Settings. Set in admin/cpt/options/feeds-cpt-options.php
	 *
	 * @var array
	 */
	public $feed_settings_array = array();

	/**
	 * Feed Functions constructor.
	 */
	public function __construct( $feed_cpt_options ){
		$this->add_actions_filters();

		$this->feed_settings_array = $feed_cpt_options->get_all_options();
	}

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 3.0.0
     */
    public function add_actions_filters(){ }

	/**
	 * Create Default Feed Settings Array
	 *
	 * Creates an array based on default settings of the feed_settings_array.
	 *
	 * @return array | boolean
	 */
	public function create_default_feed_settings() {
		$default_settings_array = array();
		// Feed Settings in admin/cpt/options/feeds-cpt-options.php
		foreach ( $this->feed_settings_array as $feed_settings_array ) {
			foreach ( $feed_settings_array as $setting_key => $settings ) {
				if ( 'main_options' === $setting_key ) {
					// Feed Settings.
					foreach ( $settings as $option ) {
						$option_name          = $option['name'] ?? '';
						$option_default_value = $option['default_value'] ?? '';
                        // Ensure option name and Default value exists if so set default to new array.
						if ( ! empty( $option_name ) && ! empty( $option_default_value ) ) {
							// Set Default_value.
							$default_settings_array[ $option_name ] = $option_default_value;
						}
					}
				}
			}
		}
		return $default_settings_array;
	}

	/**
	 * Get Saved Feed Settings
	 *
	 * Get saved settings for the feed using cpt post id.
	 *
	 * @return array | boolean
	 */
	public function get_saved_feed_settings( $feed_post_id ) {

		$settings_array = get_post_meta( $feed_post_id, FEED_THEM_SOCIAL_POST_TYPE . '_settings_options', true );

		return $settings_array;
	}

	/**
	 * Get Feed Settings
	 *
	 * Get settings for the feed using cpt post id or set defaults.
	 *
	 * @return array | boolean
	 */
	public function get_feed_settings( $feed_post_id, $create_default = true ) {
		// Get saved settings if possible.
		$settings_array = $this->get_saved_feed_settings( $feed_post_id );

		//If settings aren't saved already create_default_feed_settings.
		if ( ! $settings_array && $create_default) {
			// Creates an array based on default settings of the feed_settings_array.
			$settings_array = $this->create_default_feed_settings();
		}

		return $settings_array;
	}

	/**
	 * Get Feed Settings
	 *
	 * Get a single setting for the feed using feed post id and setting name.
	 *
	 * @return array | boolean
	 */
	public function get_feed_setting( $feed_post_id, $setting_name, $create_default = true ) {
		// Get Feed Settings.
		$feeds_settings = $this->get_feed_settings( $feed_post_id, $create_default );

		return $feeds_settings[ $setting_name ] ?? false;
	}

	/**
	 * Get Feed Type
	 *
	 * Get the feed type from option using in the feed's CPT id.
	 *
	 * @param $feed_cpt_id string
	 */
	public function get_feed_type( $feed_cpt_id ){
		// Get Saved Settings Array.
		$feed_type = $this->get_feed_setting( $feed_cpt_id, 'feed_type' );

		return $feed_type ?? false;
	}
}