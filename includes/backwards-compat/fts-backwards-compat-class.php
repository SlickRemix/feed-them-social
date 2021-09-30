<?php
/**
 * Backwards Compatibility Class
 *
 * @package     FTS
 * @subpackage  FTS
 * @copyright   Copyright (c) 2021, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.3.4
 */

namespace feedthemsocial;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Backwards Compat Class
 *
 * @class    FTS_Backwards_Compat
 * @version  1.3.4
 * @package  FeedThemSocial
 * @category Class
 * @author   SlickRemix
 */
class FTS_Backwards_Compat {
	/**
	 * Old option names.
	 *
	 * @var	array
	 */
	public $old_options;

    /**
	 * Load Function
	 *
	 * Load up all our actions and filters.
	 *
	 * @since 1.3.4
	 */
	public static function load() {
		$instance = new self();

		$instance->old_options = array(
            'fts_clear_cache_developer_mode',
            'fts_fix_magnific',
            'fts-powered-text-options-settings',
            'fts_curl_option',
            'fts_admin_bar_menu',
            'fts-date-and-time-format',
            'fts-timezone',
            'fts_language_second',
            'fts_language_seconds',
            'fts_language_minute',
            'fts_language_minutes',
            'fts_language_hour',
            'fts_language_hours',
            'fts_language_day',
            'fts_language_days',
            'fts_language_week',
            'fts_language_weeks',
            'fts_language_month',
            'fts_language_months',
            'fts_language_year',
            'fts_language_years',
            'fts_language_ago',
            'fts-custom-date',
            'fts-custom-time',
            'fts_twitter_time_offset',
            'fts-color-options-settings-custom-css',
            'fts-color-options-main-wrapper-css-input',
            'fts_disable_share_button',
            'fts_social_icons_color',
            'fts_social_icons_hover_color',
            'fts_social_icons_back_color',
		);

		// Add Actions and Filters.
		$instance->add_actions_filters();
	} // load

	/**
	 * Add Action Filters
	 *
	 * Add Settings to our menu.
	 *
	 * @since 1.3.4
	 */
	public function add_actions_filters() {
		/**
		 * Hook into option retrieval to ensure we provide values for old options.
		 *
		 * This users with extensions who no longer have support are not impacted
		 * by the new settings API.
		 */
		add_action( 'init', array( $this, 'setup_option_filters' ) );
	} // add_actions_filters

	/**
	 * Loop through the old setting options and add filters for correct value retrieval.
	 *
	 * @since	1.3.4
	 */
	public function setup_option_filters()	{
		foreach( $this->old_options as $option )	{
			add_filter( "pre_option_{$option}", array( $this, 'filter_option_values' ), 10, 3 );
		}
	} // setup_option_filters

	/**
	 * Filter the values of old FTS options.
	 *
	 * @since	1.3.4
	 * @param	mixed	$value		The required value of the option
	 * @param	string	$option		The option name
	 * @param	mixed	$default	Default value if the option does not exist
	 * @return	mixed	The required value of the option
     *
     *
     *
     *   'fts_clear_cache_developer_mode',
    'fts_fix_magnific',
    'fts-powered-text-options-settings',
    'fts_curl_option',
    'fts_admin_bar_menu',
    'fts-date-and-time-format',
    'fts-timezone',
    'fts_language_second',
    'fts_language_seconds',
    'fts_language_minute',
    'fts_language_minutes',
    'fts_language_hour',
    'fts_language_hours',
    'fts_language_day',
    'fts_language_days',
    'fts_language_week',
    'fts_language_weeks',
    'fts_language_month',
    'fts_language_months',
    'fts_language_year',
    'fts_language_years',
    'fts_language_ago',
    'fts-custom-date',
    'fts-custom-time',
    'fts_twitter_time_offset',
    'fts-color-options-settings-custom-css',
    'fts-color-options-main-wrapper-css-input',
    'fts_disable_share_button',
    'fts_social_icons_color',
    'fts_social_icons_hover_color',
    'fts_social_icons_back_color',
	 */
	public function filter_option_values( $value, $option, $default )	{
		switch( $option )	{
            case 'fts_clear_cache_developer_mode':
                $value = fts_get_option( 'fts_cache_time' );
                break;
            case 'fts_admin_bar_menu':
                $value = fts_get_option( 'fts_show_admin_bar' );
                break;
            case 'fts-date-and-time-format':
                $value = fts_get_option( 'date_time_format' );
                break;
            case 'fts_language_second':
            case 'fts_language_seconds':
            case 'fts_language_minute':
            case 'fts_language_minutes':
            case 'fts_language_hour':
            case 'fts_language_hours':
            case 'fts_language_day':
            case 'fts_language_days':
            case 'fts_language_week':
            case 'fts_language_weeks':
            case 'fts_language_month':
            case 'fts_language_months':
            case 'fts_language_year':
            case 'fts_language_years':
            case 'fts_language_ago':
                $key   = str_replace( 'fts_', '', $option );
                $value = fts_get_option( $key );
                break;
            case 'fts-custom-date':
                $value = fts_get_option( 'custom_date' );
                break;
            case 'fts-custom-time':
                $value = fts_get_option( 'custom_time' );
                break;
            case 'fts-timezone':
                $value = fts_get_option( 'timezone' );
                break;
            case 'fts-color-options-settings-custom-css':
				$value = fts_get_option( 'use_custom_css' );
				break;
			case 'fts-color-options-main-wrapper-css-input':
				$value = fts_get_option( 'custom_css' );
				break;




            case 'fts_disable_share_button':
                $value = fts_get_option( 'hide_sharing' );
                break;
            case 'fts_social_icons_color':
                $value = fts_get_option( 'social_icons_text_color' );
                break;
            case 'fts_social_icons_hover_color':
                $value = fts_get_option( 'social_icons_text_color_hover' );
                break;
            case 'fts_social_icons_back_color':
                $value = fts_get_option( 'icons_wrap_background' );
                break;


			case 'fts_fix_magnific':
			    $value = fts_get_option( 'remove_magnific_css' );
                break;
            case 'fts_twitter_time_offset':
                $value = fts_get_option( 'twitter_time' );
                break;
            case 'fts_curl_option':
                $value = fts_get_option( 'fix_curl_error' );
                break;
			case 'fts-powered-text-options-settings':
				$value = fts_get_option( 'powered_by' );
				break;
			default:
				$value = $value;
		}

		return $value;
	} // filter_option_values
} // FTS_Backwards_Compat
