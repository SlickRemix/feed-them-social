<?php
/**
 * Backwards Compat Class
 *
 * @package     FTS
 * @subpackage  Admin/Backwards Compat
 * @copyright   Copyright (c) 2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.3.4
 */

namespace feedthemsocial;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Upgrades Class
 *
 * @class    Backwards_Compat
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

/**
 * Class Backwards_Compat
 */
class FTS_Upgrades {

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settings_functions;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings_functions ) {

        // Settings Functions Class.
        $this->settings_functions = $settings_functions;

		// Add Actions and Filters.
		$this->add_actions_filters();
	}

	/**
	 * Add Action Filters
	 *
	 * Add Settings to our menu.
	 *
	 * @since 1.3.4
	 */
	public function add_actions_filters() {
        // Process upgrades sent via POST/GET
        add_action( 'admin_init', array( $this, 'process_upgrade_actions' ) );

        // Automatic upgrades
        add_action( 'admin_init', array( $this, 'do_automatic_upgrades' ) );
	}

    /**
     * Processes all FTS upgrade actions sent via POST and GET by looking for the 'fts-upgrade-action'
     * request and running do_action() to call the function
     *
     * @since   1.3.4
     * @return  void
     */
    public function process_upgrade_actions() {
        if ( isset( $_POST['fts-upgrade-action'] ) ) {
            do_action( 'fts-upgrade-' . $_POST['fts-upgrade-action'], $_POST );
        }

        if ( isset( $_GET['fts-upgrade-action'] ) ) {
            do_action( 'fts-upgrade-' . $_GET['fts-upgrade-action'], $_GET );
        }

    } // process_upgrade_actions

    /**
     * Perform automatic database upgrades when necessary
     *
     * @since	1.3.4
     * @return	void
    */
    public function do_automatic_upgrades() {
        $did_upgrade = false;
        $fts_version = preg_replace( '/[^0-9.].*/', '', get_option( 'fts_version', '1.0' ) );

        if ( version_compare( $fts_version, '3.0', '<' ) ) {
            $this->v2963_upgrades();
            // error_log( 'FTS vs test check ' . get_option( 'fts_version' ));
        }
        else{
            // Testing
            //error_log( 'FTS vs test check fail ' . get_option( 'fts_version' ));
        }

        if ( version_compare( $fts_version, FTS_CURRENT_VERSION, '<' ) )	{
            // Let us know that an upgrade has happened
            $did_upgrade = true;
            // error_log( 'FTS did_upgrade');
        }

        if ( $did_upgrade )	{
            update_option( 'fts_version_upgraded_from', get_option( 'fts_version' ) );
            update_option( 'fts_version', preg_replace( '/[^0-9.].*/', '', FTS_CURRENT_VERSION ) );
            // error_log( 'FTS Upgrade complete');
        }

    } // do_automatic_upgrades

    /**
     * Upgrade routine to migrate settings to new format.
     *
     * @since	1.3.4
     * @return	void
     */
    public function v2963_upgrades()	{
        $fts_options = array();

		/**
		 * Remove the filters that alter the values returned for old options
		 * so we can retrieve their values and migrate them.
         * Options are in order from top to bottom as they were
         * in the old vs of the plugins settings page.
		 */
		$old_options = array(
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

		foreach( $old_options as $option )	{
			remove_filter( "pre_option_{$option}", array( 'FTS_Backwards_Compat', 'filter_option_values' ), 10, 3 );
		}

        /**
         * Migrate the cache time, basic options and data/time settings.
         */
        $style_options = array(
            'fts_clear_cache_developer_mode'    => 'fts_cache_time',
            'fts-powered-text-options-settings' => 'powered_by',
            'fts_fix_magnific'                  => 'remove_magnific_css',
            'fts_curl_option'                   => 'fix_curl_error',
            'fts_admin_bar_menu'                => 'fts_show_admin_bar',
            'fts-date-and-time-format'          => 'date_time_format',
            'fts-timezone'                      => 'timezone',
            'fts_language_second'               => 'language_second',
			'fts_language_seconds'              => 'language_seconds',
			'fts_language_minute'               => 'language_minute',
			'fts_language_minutes'              => 'language_minutes',
			'fts_language_hour'                 => 'language_hour',
			'fts_language_hours'                => 'language_hours',
			'fts_language_day'                  => 'language_day',
			'fts_language_days'                 => 'language_days',
			'fts_language_week'                 => 'language_week',
			'fts_language_weeks'                => 'language_weeks',
			'fts_language_month'                => 'language_month',
			'fts_language_months'               => 'language_months',
			'fts_language_year'                 => 'language_year',
			'fts_language_years'                => 'language_years',
			'fts_language_ago'                  => 'language_ago',
            'fts-custom-date'                   => 'custom_date',
            'fts-custom-time'                   => 'custom_time',
            'fts_twitter_time_offset'           => 'twitter_time',

        );

        foreach( $style_options as $old_option => $new_option ) {
            $current                    = get_option( $old_option, '' );

            // We're switching an old select option to new checkbox
            if ( 'fts_admin_bar_menu' === $old_option )   {
                $current = 'hide-admin-bar-menu' === $current ? -1 : 1;
            }

            $fts_options[ $new_option ] = $current;
            delete_option( $old_option );
        }

        /**
         * Migrate Styles settings.
         */
        $misc_options = array(
            'fts-color-options-settings-custom-css'           => 'use_custom_css',
            'fts-color-options-main-wrapper-css-input'        => 'custom_css'
        );

        foreach( $misc_options as $old_option => $new_option )  {
            $current                    = get_option( $old_option, '' );
            $fts_options[ $new_option ] = $current;
            delete_option( $old_option );
        }

        /**
         * Migrate Social Sharing settings.
         */
        $share_options = array(
            'fts_disable_share_button'     => 'hide_sharing',
            'fts_social_icons_color'       => 'social_icons_text_color',
            'fts_social_icons_hover_color' => 'social_icons_text_color_hover',
            'fts_social_icons_back_color'  => 'icons_wrap_background',
        );

        foreach( $share_options as $old_option => $new_option )   {
            $current     = get_option( $old_option );
            $fts_options[ $new_option ] = $current;
            delete_option( $old_option );
        }

        update_option( 'fts_settings', $fts_options );
        // Used for testing.
        // delete_option( 'fts_settings' );

    } // v2963_upgrades

} // FTS_Upgrades
