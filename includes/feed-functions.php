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
	 * Settings Functions
	 *
	 * The settings Functions class.
	 *
	 * @var object
	 */
	public $settings_functions;

	/**
	 * Feed Settings Array
	 * An array of Feed Settings. Set in admin/cpt/options/feeds-cpt-options.php
	 *
	 * @var array
	 */
	public $feed_settings_array = array();

	/**
	 * Feed Cache.
	 *
	 * Class used for caching.
	 *
	 * @var object
	 */
	public $feed_cache;

	/**
	 * Feed Functions constructor.
	 */
	public function __construct( $settings_functions, $feed_cpt_options, $feed_cache ){
		$this->add_actions_filters();

		// Settings Functions Class.
		$this->settings_functions = $settings_functions;

		// Feed Settings array.
		$this->feed_settings_array = $feed_cpt_options->get_all_options();

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;
	}

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 3.0.0
     */
    public function add_actions_filters(){

		// Display admin bar
	    $display_admin_bar = get_option( 'fts_show_admin_bar' ) ;
	    if ( '1' === $display_admin_bar ) {
		    // FTS Admin Bar!
		    add_action( 'wp_before_admin_bar_render', array( $this, 'fts_admin_bar_menu' ), 999 );
		}

	    // Add Custom JS to the header of FTS pages only!
	    $use_custom_js = get_option( 'use_custom_js' );
	    if ( '1' === $use_custom_js ) {

		    add_action( 'wp_footer', array( $this, 'use_custom_js_scripts' ) );
	    }

	    // Set Powered by JS for FTS!
	    $fts_powered_text_options_settings = get_option( 'fts-powered-text-options-settings' );
	    if ( '1' !== $fts_powered_text_options_settings ) {
		    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_powered_by_js' ) );
	    }

	    // Facebook Settings option. Add Custom CSS to the header of FTS pages only!
	    $fts_include_fb_custom_css_checked_css = '1';
	    if ( '1' === $fts_include_fb_custom_css_checked_css ) {
		    add_action( 'wp_print_styles', array( $this, 'fts_fb_color_options_head_css' ) );
	    }

	    if ( is_admin() ) {
		    // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA!
		    add_action( 'admin_enqueue_scripts', array( $this, 'feed_them_admin_css' ) );
		    // Main Settings Page!
		    if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-twitter-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-pinterest-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-youtube-feed-styles-submenu-page' === $_GET['page'] ) {
			    add_action( 'admin_enqueue_scripts', array( $this, 'feed_them_settings' ) );
		    }
		    // System Info Page!
		    if ( isset( $_GET['page'] ) && 'fts-system-info-submenu-page' === $_GET['page'] ) {
			    add_action( 'admin_enqueue_scripts', array( $this, 'feed_them_system_info_css' ) );
		    }
		    // FTS License Page!
		    if ( isset( $_GET['page'] ) && 'fts-license-page' === $_GET['page'] ) {
			    add_action( 'admin_footer', array( $this, 'fts_plugin_license' ) );
		    }
	    }
    }

	/**
	 * Enqueue Powered By JS
	 *
	 * Enqueue powered by js on frontend.
	 *
	 * @since 1.9.6
	 */
	public function enqueue_powered_by_js() {
		wp_enqueue_script( 'fts_powered_by_js', plugins_url( 'feed-them-social/includes/js/powered-by.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
	}

	/**
	 * FTS Admin Bar Menu
	 *
	 * Create our custom menu in the admin bar.
	 *
	 * @since 1.9.6
	 */
	public function fts_admin_bar_menu() {
		global $wp_admin_bar;

		$fts_admin_bar_menu = get_option( 'fts_admin_bar_menu' );
		$fts_dev_mode_cache = get_option( 'fts_clear_cache_developer_mode' );
		if ( ! is_super_admin() || ! is_admin_bar_showing() || 'hide-admin-bar-menu' === $fts_admin_bar_menu ) {
			return;
		}
		$wp_admin_bar->add_menu(
			array(
				'id'    => 'feed_them_social_admin_bar',
				'title' => __( 'Feed Them Social', 'feed-them-social' ),
				'href'  => false,
			)
		);
		if ( '1' === $fts_dev_mode_cache ) {
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'feed_them_social_admin_bar_clear_cache',
					'parent' => 'feed_them_social_admin_bar',
					'title'  => __( 'Cache clears on page refresh now', 'feed-them-social' ),
					'href'   => false,
				)
			);
		} else {
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'feed_them_social_admin_set_cache',
					'parent' => 'feed_them_social_admin_bar',
					'title'  => __( 'Clear Cache', 'feed-them-social' ),
					'href'   => '#',
					'meta' => array('onclick' => 'fts_ClearCache();') //JavaScript function trigger just as an example.
				)
			);
		}

		$fts_cachetime = $this->settings_functions->fts_get_option( 'fts_cache_time' ) ? $this->settings_functions->fts_get_option( 'fts_cache_time' ) : '86400';

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'feed_them_social_admin_bar_set_cache',
				'parent' => 'feed_them_social_admin_bar',
				'title'  => sprintf(
					__( 'Set Cache Time %1$s%2$s%3$s', 'feed-them-social' ),
					'<span>',
					$this->feed_cache->fts_cachetime_amount( $fts_cachetime ),
					'</span>'
				),
				'href'   => admin_url( 'edit.php?post_type=fts&page=fts-settings-page' ),

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
	 * @param $feed_post_id string the feed post id to getting settings options from.
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
	 * Get settings for the feed using cpt post id or create the default settings array.
     *
	 * @param $feed_post_id string the feed post id to getting settings options from.
	 * @param $create_default string create the default settings array if nothing else is set?
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
     * @param $feed_post_id string the feed post id to getting settings options from.
	 * @param $setting_name string name of setting in the settings array.
     * @param $create_default string create the default settings array if nothing else is set?
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

	/**
	 * FTS Get Feed json
	 *
	 * Generate Get Json (includes MultiCurl).
	 *
	 * @param array $feeds_mulit_data feeds data info.
	 * @return array
	 * @since 1.9.6
	 */
	public function fts_get_feed_json( $feeds_mulit_data ) {
		// Make Multiple Requests from array with more than 2 keys!
		if ( is_array( $feeds_mulit_data ) && count( $feeds_mulit_data ) > 1 ) {
			$new_feeds_mulit_data = array();

			foreach ( $feeds_mulit_data as $key => $url ) {
				$new_feeds_mulit_data[ $key ]['url']  = $url;
				$new_feeds_mulit_data[ $key ]['type'] = 'GET';
			}
			// Fetch Multiple Requests!
			$responses = \Requests::request_multiple( $new_feeds_mulit_data );

			$data = array();
			foreach ( $responses as $key => $response ) {

				if ( is_a( $response, 'Requests_Response' ) ) {
					$data[ $key ] = $response->body;
				}
			}
		} else {
			// Make Single Requests from array with 1 keys!
			if ( is_array( $feeds_mulit_data ) ) {
				foreach ( $feeds_mulit_data as $key => $url ) {

					$single_response = \Requests::get( $url );

					$data = array();
					if ( is_a( $single_response, 'Requests_Response' ) ) {
						$data[ $key ] = $single_response->body;
					}
				}
			} else {
				// Make Single request from just url!
				$single_response_url = $feeds_mulit_data;

				if ( ! empty( $single_response_url ) ) {
					$single_response = \Requests::get( $single_response_url );

					if ( is_a( $single_response, 'Requests_Response' ) ) {
						$data['data'] = $single_response->body;
					}
				}
			}
		}
		// Do nothing if Curl was Successful!
		return $data;
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
	public function fts_ago( $timestamp ) {
		// not setting isset'ing anything because you have to save the settings page to even enable this feature
		$fts_language_second = get_option( 'fts_language_second' );
		if ( empty( $fts_language_second ) ) {
			$fts_language_second = esc_html__( 'second', 'feed-them-social' );
		}
		$fts_language_seconds = get_option( 'fts_language_seconds' );
		if ( empty( $fts_language_seconds ) ) {
			$fts_language_seconds = esc_html__( 'seconds', 'feed-them-social' );
		}
		$fts_language_minute = get_option( 'fts_language_minute' );
		if ( empty( $fts_language_minute ) ) {
			$fts_language_minute = esc_html__( 'minute', 'feed-them-social' );
		}
		$fts_language_minutes = get_option( 'fts_language_minutes' );
		if ( empty( $fts_language_minutes ) ) {
			$fts_language_minutes = esc_html__( 'minutes', 'feed-them-social' );
		}
		$fts_language_hour = get_option( 'fts_language_hour' );
		if ( empty( $fts_language_hour ) ) {
			$fts_language_hour = esc_html__( 'hour', 'feed-them-social' );
		}
		$fts_language_hours = get_option( 'fts_language_hours' );
		if ( empty( $fts_language_hours ) ) {
			$fts_language_hours = esc_html__( 'hours', 'feed-them-social' );
		}
		$fts_language_day = get_option( 'fts_language_day' );
		if ( empty( $fts_language_day ) ) {
			$fts_language_day = esc_html__( 'day', 'feed-them-social' );

		}
		$fts_language_days = get_option( 'fts_language_days' );
		if ( empty( $fts_language_days ) ) {
			$fts_language_days = esc_html__( 'days', 'feed-them-social' );
		}
		$fts_language_week = get_option( 'fts_language_week' );
		if ( empty( $fts_language_week ) ) {
			$fts_language_week = esc_html__( 'week', 'feed-them-social' );
		}
		$fts_language_weeks = get_option( 'fts_language_weeks' );
		if ( empty( $fts_language_weeks ) ) {
			$fts_language_weeks = esc_html__( 'weeks', 'feed-them-social' );
		}
		$fts_language_month = get_option( 'fts_language_month' );
		if ( empty( $fts_language_month ) ) {
			$fts_language_month = esc_html__( 'month', 'feed-them-social' );
		}
		$fts_language_months = get_option( 'fts_language_months' );
		if ( empty( $fts_language_months ) ) {
			$fts_language_months = esc_html__( 'months', 'feed-them-social' );
		}
		$fts_language_year = get_option( 'fts_language_year' );
		if ( empty( $fts_language_year ) ) {
			$fts_language_year = esc_html__( 'year', 'feed-them-social' );
		}
		$fts_language_years = get_option( 'fts_language_years' );
		if ( empty( $fts_language_years ) ) {
			$fts_language_years = esc_html__( 'years', 'feed-them-social' );
		}
		$fts_language_ago = get_option( 'fts_language_ago' );
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
	public function fts_custom_date( $created_time, $feed_type ) {


		$fts_custom_date         = get_option( 'fts-custom-date' );
		$fts_custom_time         = get_option( 'fts-custom-time' );
		$custom_date_check       = get_option( 'fts-date-and-time-format' );
		$fts_twitter_offset_time = get_option( 'fts_twitter_time_offset' );
		$fts_timezone            = get_option( 'fts-timezone' );

		if ( '' === $fts_custom_date && '' === $fts_custom_time ) {
			$custom_date_check = $custom_date_check;
		} elseif ( '' !== $fts_custom_date || '' !== $fts_custom_time ) {
			$custom_date_check = $fts_custom_date . ' ' . $fts_custom_time;
		} else {
			$custom_date_check = 'F jS, Y \a\t g:ia';
		}

		// Always store the current timezone so that it can be restored later
		$fts_old_timezone = date_default_timezone_get();
		if ( ! empty( $fts_timezone ) ) {
			date_default_timezone_set( $fts_timezone );
		}
		// Twitter date time!
		if ( 'twitter' === $feed_type ) {

			$fts_twitter_offset_time_final = 1 === $fts_twitter_offset_time ? strtotime( $created_time ) : strtotime( $created_time ) - 3 * 3600;

			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, $fts_twitter_offset_time_final ) : $this->fts_ago( $created_time );
			}
		}

		// Instagram date time!
		if ( 'instagram' === $feed_type ) {
			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
			}
		}
		// Youtube and Pinterest date time!
		if ( 'pinterest' === $feed_type ) {
			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
			}
		}
		// WP Gallery and Pinterest date time!
		if ( 'wp_gallery' === $feed_type ) {
			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
			}
		}
		// Facebook date time!
		if ( 'facebook' === $feed_type ) {
			$time_set       = $fts_timezone;
			$time_set_check = isset( $time_set ) ? $time_set : 'America/New_York';
			date_default_timezone_set( $time_set_check );

			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, $created_time ) : $this->fts_ago( $created_time );
			}
		}
		// Instagram date time!
		if ( 'youtube' === $feed_type ) {
			if ( 'one-day-ago' === $custom_date_check ) {
				$u_time = $this->fts_ago( $created_time );
			} else {
				$u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
			}
		}

		// Restore the timezone to its value when entering this function to avoid side-effects
		date_default_timezone_set( $fts_old_timezone );

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
	public function fts_fb_color_options_head_css() {
		$fb_hide_no_posts_message       = get_option( 'fb_hide_no_posts_message' );
		$fb_header_extra_text_color     = get_option( 'fb_header_extra_text_color' );
		$fb_text_color                  = get_option( 'fb_text_color' );
		$fb_link_color                  = get_option( 'fb_link_color' );
		$fb_link_color_hover            = get_option( 'fb_link_color_hover' );
		$fb_feed_width                  = get_option( 'fb_feed_width' );
		$fb_feed_margin                 = get_option( 'fb_feed_margin' );
		$fb_feed_padding                = get_option( 'fb_feed_padding' );
		$fb_feed_background_color       = get_option( 'fb_feed_background_color' );
		$fb_post_background_color       = get_option( 'fb_post_background_color' );
		$fb_grid_posts_background_color = get_option( 'fb_grid_posts_background_color' );
		$fb_grid_border_bottom_color    = get_option( 'fb_grid_border_bottom_color' );
		$fb_loadmore_background_color   = get_option( 'fb_loadmore_background_color' );
		$fb_loadmore_text_color         = get_option( 'fb_loadmore_text_color' );
		$fb_border_bottom_color         = get_option( 'fb_border_bottom_color' );
		$fb_grid_posts_background_color = get_option( 'fb_grid_posts_background_color' );
		$fb_reviews_backg_color         = get_option( 'fb_reviews_backg_color' );
		$fb_reviews_text_color          = get_option( 'fb_reviews_text_color' );

		$fb_reviews_overall_rating_background_color   = get_option( 'fb_reviews_overall_rating_background_color' );
		$fb_reviews_overall_rating_border_color       = get_option( 'fb_reviews_overall_rating_border_color' );
		$fb_reviews_overall_rating_text_color         = get_option( 'fb_reviews_overall_rating_text_color' );
		$fb_reviews_overall_rating_background_padding = get_option( 'fb_reviews_overall_rating_background_padding' );

		$fb_max_image_width = get_option( 'fb_max_image_width' );

		$fb_events_title_color   = get_option( 'fb_events_title_color' );
		$fb_events_title_size    = get_option( 'fb_events_title_size' );
		$fb_events_maplink_color = get_option( 'fb_events_map_link_color' );

		$twitter_hide_profile_photo          = get_option( 'twitter_hide_profile_photo' );
		$twitter_text_color                  = get_option( 'twitter_text_color' );
		$twitter_link_color                  = get_option( 'twitter_link_color' );
		$twitter_link_color_hover            = get_option( 'twitter_link_color_hover' );
		$twitter_feed_width                  = get_option( 'twitter_feed_width' );
		$twitter_feed_margin                 = get_option( 'twitter_feed_margin' );
		$twitter_feed_padding                = get_option( 'twitter_feed_padding' );
		$twitter_feed_background_color       = get_option( 'twitter_feed_background_color' );
		$twitter_border_bottom_color         = get_option( 'twitter_border_bottom_color' );
		$twitter_max_image_width             = get_option( 'twitter_max_image_width' );
		$twitter_grid_border_bottom_color    = get_option( 'twitter_grid_border_bottom_color' );
		$twitter_grid_posts_background_color = get_option( 'twitter_grid_posts_background_color' );
		$twitter_loadmore_background_color   = get_option( 'twitter_loadmore_background_color' );
		$twitter_loadmore_text_color         = get_option( 'twitter_loadmore_text_color' );

		$instagram_loadmore_background_color = get_option( 'instagram_loadmore_background_color' );
		$instagram_loadmore_text_color       = get_option( 'instagram_loadmore_text_color' );

		$pinterest_board_title_color       = get_option( 'pinterest_board_title_color' );
		$pinterest_board_title_size        = get_option( 'pinterest_board_title_size' );
		$pinterest_board_backg_hover_color = get_option( 'pinterest_board_backg_hover_color' );

		$fts_social_icons_color       = get_option( 'fts_social_icons_color' );
		$fts_social_icons_hover_color = get_option( 'fts_social_icons_hover_color' );
		$fts_social_icons_back_color  = get_option( 'fts_social_icons_back_color' );

		$youtube_loadmore_background_color = get_option( 'youtube_loadmore_background_color' );
		$youtube_loadmore_text_color       = get_option( 'youtube_loadmore_text_color' );

		$fb_text_size      = get_option( 'fb_text_size' );
		$twitter_text_size = get_option( 'twitter_text_size' );
		?>
		<style type="text/css"><?php if ( ! empty( $fb_header_extra_text_color ) ) { ?>

			<?php }if ( ! empty( $fb_hide_no_posts_message ) && 'yes' === $fb_hide_no_posts_message ) { ?>
            .fts-facebook-add-more-posts-notice {
                display: none !important;
            }

            .fts-jal-single-fb-post .fts-jal-fb-user-name {
                color: <?php echo esc_html( $fb_header_extra_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_loadmore_background_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $fb_loadmore_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_loadmore_text_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_loadmore_text_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_text_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post,
            .fts-simple-fb-wrapper .fts-jal-fb-description-wrap,
            .fts-simple-fb-wrapper .fts-jal-fb-post-time,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post,
            .fts-slicker-facebook-posts .fts-jal-fb-description-wrap,
            .fts-slicker-facebook-posts .fts-jal-fb-post-time {
                color: <?php echo esc_html( $fb_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_link_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post .fts-review-name,
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a,
            .fts-jal-fb-group-header-desc a {
                color: <?php echo esc_html( $fb_link_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_link_color_hover ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover,
            .fts-simple-fb-wrapper .fts-fb-load-more:hover,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover,
            .fts-slicker-facebook-posts .fts-fb-load-more:hover,
            .fts-jal-fb-group-header-desc a:hover {
                color: <?php echo esc_html( $fb_link_color_hover ); ?> !important;
            }

			<?php }if ( ! empty( $fb_feed_width ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                max-width: <?php echo esc_html( $fb_feed_width ); ?> !important;
            }

			<?php }if ( ! empty( $fb_max_image_width ) ) { ?>
            .fts-fb-large-photo, .fts-jal-fb-vid-picture, .fts-jal-fb-picture, .fts-fluid-videoWrapper-html5 {
                max-width: <?php echo esc_html( $fb_max_image_width ); ?> !important;
                float: left;
            }

			<?php }if ( ! empty( $fb_events_title_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                color: <?php echo esc_html( $fb_events_title_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_events_title_size ) ) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                font-size: <?php echo esc_html( $fb_events_title_size ); ?> !important;
                line-height: <?php echo esc_html( $fb_events_title_size ); ?> !important;
            }

			<?php }if ( ! empty( $fb_events_maplink_color ) ) { ?>
            .fts-simple-fb-wrapper a.fts-fb-get-directions {
                color: <?php echo esc_html( $fb_events_maplink_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_feed_margin ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                margin: <?php echo esc_html( $fb_feed_margin ); ?> !important;
            }

			<?php }if ( ! empty( $fb_feed_padding ) ) { ?>
            .fts-simple-fb-wrapper {
                padding: <?php echo esc_html( $fb_feed_padding ); ?> !important;
            }

			<?php }if ( ! empty( $fb_feed_background_color ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $fb_feed_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_post_background_color ) ) { ?>
            .fts-mashup-media-top .fts-jal-single-fb-post {
                background: <?php echo esc_html( $fb_post_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_grid_posts_background_color ) ) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                background: <?php echo esc_html( $fb_grid_posts_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_border_bottom_color ) ) { ?>
            .fts-jal-single-fb-post {
                border-bottom-color: <?php echo esc_html( $fb_border_bottom_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_grid_border_bottom_color ) ) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                border-bottom-color: <?php echo esc_html( $fb_grid_border_bottom_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_grid_posts_background_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                background: <?php echo esc_html( $twitter_grid_posts_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_grid_border_bottom_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                border-bottom-color: <?php echo esc_html( $twitter_grid_border_bottom_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_loadmore_background_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $twitter_loadmore_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_backg_color ) ) { ?>
            .fts-review-star {
                background: <?php echo esc_html( $fb_reviews_backg_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_overall_rating_background_color ) ) { ?>
            .fts-review-details-master-wrap {
                background: <?php echo esc_html( $fb_reviews_overall_rating_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_overall_rating_border_color ) ) { ?>
            .fts-review-details-master-wrap {
                border-bottom-color: <?php echo esc_html( $fb_reviews_overall_rating_border_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_overall_rating_background_padding ) ) { ?>
            .fts-review-details-master-wrap {
                padding: <?php echo esc_html( $fb_reviews_overall_rating_background_padding ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_overall_rating_text_color ) ) { ?>
            .fts-review-details-master-wrap {
                color: <?php echo esc_html( $fb_reviews_overall_rating_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $fb_reviews_text_color ) ) { ?>
            .fts-review-star {
                color: <?php echo esc_html( $fb_reviews_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_text_color ) ) { ?>
            .tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before, a span.fts-video-loading-notice {
                color: <?php echo esc_html( $twitter_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_link_color ) ) { ?>
            .tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a, .twitter-followers-fts a, body.fts-twitter-reply-wrap a {
                color: <?php echo esc_html( $twitter_link_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_link_color_hover ) ) { ?>
            .tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply, body.fts-twitter-reply-wrap a:hover {
                color: <?php echo esc_html( $twitter_link_color_hover ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_feed_width ) ) { ?>
            .fts-twitter-div {
                max-width: <?php echo esc_html( $twitter_feed_width ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_feed_margin ) ) { ?>
            .fts-twitter-div {
                margin: <?php echo esc_html( $twitter_feed_margin ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_feed_padding ) ) { ?>
            .fts-twitter-div {
                padding: <?php echo esc_html( $twitter_feed_padding ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_feed_background_color ) ) { ?>
            .fts-twitter-div {
                background: <?php echo esc_html( $twitter_feed_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_border_bottom_color ) ) { ?>
            .tweeter-info {
                border-bottom: 1px solid <?php echo esc_html( $twitter_border_bottom_color ); ?> !important;
            }

			<?php }if ( ! empty( $twitter_max_image_width ) ) { ?>
            .fts-twitter-link-image {
                max-width: <?php echo esc_html( $twitter_max_image_width ); ?> !important;
                display: block;
            }

			<?php }if ( ! empty( $instagram_loadmore_background_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $instagram_loadmore_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important;
            }

			<?php }if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important;
            }

			<?php } if ( ! empty( $pinterest_board_backg_hover_color ) ) { ?>
            a.fts-pin-board-wrap:hover {
                background: <?php echo esc_html( $pinterest_board_backg_hover_color ); ?> !important;
            }

			<?php } if ( ! empty( $pinterest_board_title_color ) ) { ?>
            body h3.fts-pin-board-board_title {
                color: <?php echo esc_html( $pinterest_board_title_color ); ?> !important;
            }

			<?php } if ( ! empty( $pinterest_board_title_size ) ) { ?>
            body h3.fts-pin-board-board_title {
                font-size: <?php echo esc_html( $pinterest_board_title_size ); ?> !important;
            }

			<?php
}
if ( ! empty( $fts_social_icons_color ) ) {
	?>
            .ft-gallery-share-wrap a.ft-galleryfacebook-icon, .ft-gallery-share-wrap a.ft-gallerytwitter-icon, .ft-gallery-share-wrap a.ft-gallerygoogle-icon, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon, .ft-gallery-share-wrap a.ft-galleryemail-icon {
                color: <?php echo esc_html( $fts_social_icons_color ); ?> !important;
            }

			<?php
}
if ( ! empty( $fts_social_icons_hover_color ) ) {
	?>
            .ft-gallery-share-wrap a.ft-galleryfacebook-icon:hover, .ft-gallery-share-wrap a.ft-gallerytwitter-icon:hover, .ft-gallery-share-wrap a.ft-gallerygoogle-icon:hover, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon:hover, .ft-gallery-share-wrap a.ft-galleryemail-icon:hover {
                color: <?php echo esc_html( $fts_social_icons_hover_color ); ?> !important;
            }

			<?php
}
if ( ! empty( $fts_social_icons_back_color ) ) {
	?>
            .ft-gallery-share-wrap {
                background: <?php echo esc_html( $fts_social_icons_back_color ); ?> !important;
            }

			<?php
}
if ( ! empty( $twitter_text_size ) ) {
	?>
            span.fts-twitter-text {
                font-size: <?php echo esc_html( $twitter_text_size ); ?> !important;
            }

			<?php
}
if ( ! empty( $fb_text_size ) ) {
	?>
            .fts-jal-fb-group-display .fts-jal-fb-message, .fts-jal-fb-group-display .fts-jal-fb-message p, .fts-jal-fb-group-header-desc, .fts-jal-fb-group-header-desc p, .fts-jal-fb-group-header-desc a {
                font-size: <?php echo esc_html( $fb_text_size ); ?> !important;
            }

			<?php
}
if ( ! empty( $youtube_loadmore_background_color ) ) {
	?>
            .fts-youtube-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $youtube_loadmore_background_color ); ?> !important;
            }

			<?php }if ( ! empty( $youtube_loadmore_text_color ) ) { ?>
            .fts-youtube-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $youtube_loadmore_text_color ); ?> !important;
            }

			<?php
}
if ( ! empty( $youtube_loadmore_text_color ) ) {
	?>
            .fts-youtube-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $youtube_loadmore_text_color ); ?> !important;
            }

			<?php } ?>

		</style>
		<?php
	}

	/**
	 * Feed Them Admin CSS
	 *
	 * Admin CSS.
	 *
	 * @since 1.9.6
	 */
	public function feed_them_admin_css() {
		wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
		wp_enqueue_style( 'feed_them_admin' );
	}

	/**
	 * Feed Them System Info CSS
	 *
	 * Admin System Info CSS.
	 *
	 * @since 1.9.6
	 */
	public function feed_them_system_info_css() {
		wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
		wp_enqueue_style( 'fts-settings-admin-css' );
	}

	/**
	 * Feed Them Settings
	 *
	 * Admin Settings Scripts and CSS.
	 *
	 * @since 1.9.6
	 */
	public function feed_them_settings() {
		$fts_functions_load_settings_nonce = wp_create_nonce( 'fts-functions-load-settings-nonce' );

		if ( wp_verify_nonce( $fts_functions_load_settings_nonce, 'fts-functions-load-settings-nonce' ) ) {
			wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION, false );
			wp_enqueue_style( 'feed_them_settings_css' );
			if ( isset( $_GET['page'] ) && 'fts-youtube-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-twitter-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-pinterest-feed-styles-submenu-page' === $_GET['page'] ) {
				wp_enqueue_script( 'feed_them_style_options_color_js', plugins_url( 'admin/js/jscolor/jscolor.js', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION, false );
			}
		}
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
	public function get_random_string( $length = 10 ) {
		$characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
		}

		return $random_string;
	}

	/**
	 * Social Follow Buttons
	 *
	 * @param string $feed feed type.
	 * @param string $user_id user id.
	 * @param null   $access_token access token.
	 * @param null   $fb_shortcode shortcode attribute.
	 * @since 1.9.6
	 */
	public function social_follow_button( $feed, $user_id, $access_token = null, $fb_shortcode = null ) {
		$fts_social_follow_nonce = wp_create_nonce( 'fts-social-follow-nonce' );

		if ( wp_verify_nonce( $fts_social_follow_nonce, 'fts-social-follow-nonce' ) ) {

			global $channel_id, $playlist_id, $username_subscribe_btn, $username;
			switch ( $feed ) {
				case 'facebook':
					// Facebook settings options for follow button!
					$fb_show_follow_btn            = get_option( 'fb_show_follow_btn' );
					$fb_show_follow_like_box_cover = get_option( 'fb_show_follow_like_box_cover' );
					$language_option_check         = get_option( 'fb_language' );

					if ( isset( $language_option_check ) && 'Please Select Option' !== $language_option_check ) {
						$language_option = get_option( 'fb_language', 'en_US' );
					} else {
						$language_option = 'en_US';
					}
					$fb_like_btn_color = get_option( 'fb_like_btn_color', 'light' );
					$show_faces        = 'like-button-share-faces' === $fb_show_follow_btn || 'like-button-faces' === $fb_show_follow_btn || 'like-box-faces' === $fb_show_follow_btn ? 'true' : 'false';
					$share_button      = 'like-button-share-faces' === $fb_show_follow_btn || 'like-button-share' === $fb_show_follow_btn ? 'true' : 'false';
					$page_cover        = 'fb_like_box_cover-yes' === $fb_show_follow_like_box_cover ? 'true' : 'false';
					if ( ! isset( $_POST['fts_facebook_script_loaded'] ) ) {
						echo '<div id="fb-root"></div>
							<script>jQuery(".fb-page").hide(); (function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/' . esc_html( $language_option ) . '/sdk.js#xfbml=1&appId=&version=v3.1";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, "script", "facebook-jssd"));</script>';
						$_POST['fts_facebook_script_loaded'] = 'yes';
					}

					// Page Box!
					if ( 'like-box' === $fb_show_follow_btn || 'like-box-faces' === $fb_show_follow_btn ) {

						$like_box_width = isset( $fb_shortcode['like_box_width'] ) && '' !== $fb_shortcode['like_box_width'] ? $fb_shortcode['like_box_width'] : '500px';

						echo '<div class="fb-page" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-hide-cover="' . esc_html( $page_cover ) . '" data-width="' . esc_html( $like_box_width ) . '"  data-show-facepile="' . esc_html( $show_faces ) . '" data-show-posts="false"></div>';
					} else {
						echo '<div class="fb-like" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-layout="standard" data-action="like" data-colorscheme="' . esc_html( $fb_like_btn_color ) . '" data-show-faces="' . esc_html( $show_faces ) . '" data-share="' . esc_html( $share_button ) . '" data-width:"100%"></div>';
					}
					break;
				case 'instagram':
					echo '<a href="' . esc_url( 'https://instagram.com/' . $user_id . '/' ) . '" target="_blank" rel="noreferrer">' . esc_html( 'Follow on Instagram', 'feed-them-social' ) . '</a>';
					break;
				case 'twitter':
					if ( ! isset( $_POST['fts_twitter_script_loaded'] ) ) {
						echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
						$_POST['fts_twitter_script_loaded'] = 'yes';
					}
					// CAN't ESCAPE Twitter link because then JS doesn't work!
					echo '<a class="twitter-follow-button" href="' . ' https://twitter.com/' . $user_id . ' " data-show-count="false" data-lang="en"> Follow @' . esc_html( $user_id ) . '</a>';
					break;
				case 'pinterest':
					if ( ! isset( $_POST['fts_pinterest_script_loaded'] ) ) {
						echo '<script>jQuery(function () {jQuery.getScript("//assets.pinterest.com/js/pinit.js");});</script>';
						$_POST['fts_pinterest_script_loaded'] = 'yes';
					}
					// we return this one until we echo out the pinterest feed instead of $output.=.
					return '<a data-pin-do="buttonFollow" href="https://www.pinterest.com/' . esc_html( $user_id ) . '/">' . esc_html( $user_id ) . '</a>';
					break;
				case 'youtube':
					if ( ! isset( $_POST['fts_youtube_script_loaded'] ) ) {
						echo '<script src="' . esc_url( 'https://apis.google.com/js/platform.js' ) . '"></script>';
						$_POST['fts_youtube_script_loaded'] = 'yes';
					}
					if ( '' === $channel_id && '' === $playlist_id && '' !== $username || '' !== $playlist_id && '' !== $username_subscribe_btn ) {

						if ( '' !== $username_subscribe_btn ) {
							echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $username_subscribe_btn ) . '" data-layout="full" data-count="default"></div>';
						} else {
							echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $user_id ) . '" data-layout="full" data-count="default"></div>';
						}
					} elseif ( '' !== $channel_id && '' !== $playlist_id || '' !== $channel_id ) {
						echo '<div class="g-ytsubscribe" data-channelid="' . esc_html( $channel_id ) . '" data-layout="full" data-count="default"></div>';
					}
					break;
			}
		}
	}

	/**
	 * FTS Share Option
	 *
	 * @param string $fb_link link for social network.
	 * @param string $description description field for some of the social networks.
	 * @since
	 */
	public function fts_share_option( $fb_link, $description ) {

		$hide_share = get_option( 'fts_disable_share_button', true ) ? get_option( 'fts_disable_share_button', true ) : '';

		if ( isset( $hide_share ) && '1' !== $hide_share ) {
			// Social media sharing URLs
			$link                      = $fb_link;
			$description               = wp_strip_all_tags( $description );
			$ft_gallery_share_linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link;
			$ft_gallery_share_email    = 'mailto:?subject=Shared Link&body=' . $link . ' - ' . $description;
			$ft_gallery_share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $link;
			$ft_gallery_share_twitter  = 'https://twitter.com/intent/tweet?text=' . $link . '+' . $description;
			$ft_gallery_share_google   = 'https://plus.google.com/share?url=' . $link;

			// The share wrap and links
			$output  = '<div class="fts-share-wrap">';
			$output .= '<a href="javascript:;" class="ft-gallery-link-popup" title="' . esc_html__( 'Social Share Options', 'feed-them-social' ) . '">' . esc_html( '', 'feed-them-social' ) . '</a>';
			$output .= '<div class="ft-gallery-share-wrap">';
			$output .= '<a href="' . esc_attr( $ft_gallery_share_facebook ) . '" target="_blank" rel="noreferrer" class="ft-galleryfacebook-icon" title="Share this post on Facebook"><i class="fa fa-facebook-square"></i></a>';
			$output .= '<a href="' . esc_attr( $ft_gallery_share_twitter ) . '" target="_blank" rel="noreferrer" class="ft-gallerytwitter-icon" title="Share this post on Twitter"><i class="fa fa-twitter"></i></a>';
			$output .= '<a href="' . esc_attr( $ft_gallery_share_google ) . '" target="_blank" rel="noreferrer" class="ft-gallerygoogle-icon" title="Share this post on Google"><i class="fa fa-google-plus"></i></a>';
			$output .= '<a href="' . esc_attr( $ft_gallery_share_linkedin ) . '" target="_blank" rel="noreferrer" class="ft-gallerylinkedin-icon" title="Share this post on Linkedin"><i class="fa fa-linkedin"></i></a>';
			$output .= '<a href="' . esc_attr( $ft_gallery_share_email ) . '" target="_blank" rel="noreferrer" class="ft-galleryemail-icon" title="Share this post in your email"><i class="fa fa-envelope"></i></a>';
			$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
	}
}