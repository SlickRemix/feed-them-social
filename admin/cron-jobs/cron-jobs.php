<?php
/**
 * Cron Jobs
 *
 * This class is for loading up cron jobs for the TikTok & Instagram Basic Feed.
 * It will refresh an access token every 24 hours for TikTok and every 54 days for Instagram.
 * Instagram expires every 60 days, but we are setting it to 54 days to be safe.
 * YouTube expires every hour.
 *
 * @class     Cron_Jobs
 * @version   4.2.3
 * @copyright Copyright (c) 2012-2024, SlickRemix
 * @category  Class
 * @author    feedthemsocial
 */

namespace feedthemsocial;

class Cron_Jobs {

    /**
     * Feed Functions
     *
     * The Feed Functions Class
     *
     * @var object
     */
    public $feed_functions;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $options_functions;

    /**
     * Core_Functions constructor.
     */
    public function __construct( $feed_functions, $options_functions ) {

        // Feed Functions Class.
        $this->feed_functions = $feed_functions;

        // Options Functions Class.
        $this->options_functions = $options_functions;

        // Add Actions and Filters.
        $this->add_actions_filters();
    }

    /**
     * Add Action Filters
     */
    public function add_actions_filters() {
        // Hook for the custom cron schedules
        add_filter('cron_schedules', array($this, 'fts_cron_schedules'));
        // Register actions on init
        add_action('init', array($this, 'register_cron_actions'));
    }

    /**
     * Adds custom intervals for cron jobs
     */
    public function fts_cron_schedules($schedules) {

        // Tiktok refreshes every 24 hours.
        if (!isset($schedules['once_daily'])) {
            $schedules['once_daily'] = array(
                'interval' => 86400, // 86400 is 24 hours in seconds
                'display'  => __('Once Daily')
            );
        }

        // Instagram refreshes every 54 days.
        if (!isset($schedules['every_54_days'])) {
            $schedules['every_54_days'] = array(
                'interval' => 54 * DAY_IN_SECONDS, // 54 days in seconds
                'display'  => __('Every 54 Days')
            );
        }

        // YouTube refreshes every 1 Hour.
        if (!isset($schedules['every_hour'])) {
            $schedules['every_hour'] = array(
                'interval' => 3600, // 3600 is 1 hour in seconds
               // 'interval' => 60,
                'display'  => __('Once Hourly')
            );
        }

        return $schedules;
    }

    /**
     * Set up a cron job.
     */
    public function fts_set_cron_job($cpt_id, $feed_name, $revoke_token) {
        $event_hook = "fts_{$feed_name}_refresh_token_{$cpt_id}";

        // Unschedule any existing event with this hook
        $timestamp = wp_next_scheduled($event_hook, array($cpt_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, $event_hook, array($cpt_id));
        }

        if( $revoke_token === false ){
            // Determine the schedule based on the feed type
            if( $feed_name === 'instagram_business_basic' ){
                $schedule = 'every_54_days';
            }
            elseif( $feed_name === 'tiktok' ){
                $schedule = 'once_daily';
            }
            elseif( $feed_name === 'youtube' ){
                $schedule = 'every_hour';
            }
            // Schedule a new event
            wp_schedule_event(time(), $schedule, $event_hook, array($cpt_id));
        }

        // Store the event hook name in the feed options
        $this->options_functions->update_single_option('fts_feed_options_array', "{$feed_name}_scheduled_event", $event_hook, true, $cpt_id, false);
    }

    /**
     * The task to run for each cron job.
     */
    public function fts_refresh_token_task($cpt_id, $feed_name ) {
        if ($feed_name === 'instagram_business_basic') {
            $this->feed_functions->fts_instagram_refresh_token($cpt_id);
        }
        elseif ($feed_name === 'tiktok') {
            $this->feed_functions->fts_tiktok_refresh_token($cpt_id);
        }
        elseif ($feed_name === 'youtube') {
            $this->feed_functions->fts_youtube_refresh_token($cpt_id);
        }
    }

    /**
     * Register actions for each scheduled cron event.
     */
    public function register_cron_actions() {
        // Retrieve all CPTs that have a cron event hook set
        $args = [
            'post_type'   => 'fts', // Replace with your CPT name
            'numberposts' => -1,
            'fields'      => 'ids'
        ];
        $posts = get_posts($args);
        foreach ($posts as $feed_cpt_id) {

            $instagram_hook = $this->feed_functions->get_feed_option($feed_cpt_id, 'instagram_business_basic_scheduled_event');
            if (!empty($instagram_hook)) {
                add_action($instagram_hook, function() use ($feed_cpt_id) {
                    $this->fts_refresh_token_task($feed_cpt_id, 'instagram_business_basic');
                });
            }

            $tiktok_hook = $this->feed_functions->get_feed_option($feed_cpt_id, 'tiktok_scheduled_event');
            if (!empty($tiktok_hook)) {
                add_action($tiktok_hook, function() use ($feed_cpt_id) {
                    $this->fts_refresh_token_task($feed_cpt_id, 'tiktok');
                });
            }

            $youtube_hook = $this->feed_functions->get_feed_option($feed_cpt_id, 'youtube_scheduled_event');
            if (!empty($youtube_hook)) {
                add_action($youtube_hook, function() use ($feed_cpt_id) {
                    $this->fts_refresh_token_task($feed_cpt_id, 'youtube');
                });
            }
        }
    }
}//end class