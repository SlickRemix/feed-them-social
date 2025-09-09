<?php
/**
 * Cron Jobs
 *
 * This class is for loading up cron jobs for the TikTok & Instagram Basic Feed.
 * It will refresh an access token every 24 hours for TikTok and every 54 days for Instagram.
 * Instagram expires every 60 days, but we are setting it to 54 days to be safe.
 * YouTube expires every hour.
 *
 * @class     CronJobs
 * @version   4.2.3
 * @copyright Copyright (c) 2012-2024, SlickRemix
 * @category  Class
 * @author    feedthemsocial
 */

namespace feedthemsocial\admin\cron_jobs;

use feedthemsocial\includes\DebugLog;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

class CronJobs {

    /**
     * Feed Functions
     *
     * The Feed Functions Class
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $optionsFunctions;

    /**
     * Settings Functions
     *
     * The settings Functions class.
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
     * Core_Functions constructor.
     */
    public function __construct( $feedFunctions, $optionsFunctions, $settingsFunctions, $feedCache ) {

        // Feed Functions Class.
        $this->feedFunctions = $feedFunctions;

        // Options Functions Class.
        $this->optionsFunctions = $optionsFunctions;

        // Feed Settings.
        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Cache object.
        $this->feedCache = $feedCache;

        // Add Actions and Filters.
        $this->addActionsFilters();
    }

    /**
     * Add Action Filters
     */
    public function addActionsFilters() {
        // Hook for the custom cron schedules
        add_filter('cron_schedules', array($this, 'ftsCronSchedules'));

        // Register actions on init
        add_action('init', array($this, 'registerCronActions'));
    }

    /**
     * Adds custom intervals for cron jobs
     */
    public function ftsCronSchedules($schedules) {
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
                'display'  => __('Once Hourly')
            );
        }

        if (!isset($schedules['fts_cache_clear'])) {
            // Cache clear interval
            // Production: Must be active.
            $fts_settings = get_option('fts_settings');
            $cache_time = $fts_settings['fts_cache_time'] ?? null;
            // Testing: Set cache clear interval to 60 seconds.
            // $cache_time = '60';
            $cache_interval = is_numeric($cache_time) && $cache_time > 0 ? (int)$cache_time : 86400; // Default to 1 Day if not set.
            $schedules['fts_cache_clear'] = array(
                'interval' => $cache_interval,
                'display'  => __('Cache Clear Interval')
            );
        }

        return $schedules;
    }

    /**
     * The task to run for clearing the cache
     *
     * Backup to delete all cache in case transient_timeout fails
     */
    public function clearCacheTask() {
        // Debug: Log task execution
        DebugLog::log( 'cronJobs', 'Running clearCacheTask', true );
        $this->feedCache->feedThemClearCache();
    }

    /**
     * Set up a cron job.
     */
    public function ftsSetCronJob($cpt_id, $feed_name, $revoke_token) {

        if( $cpt_id === 'clear-cache-set-cron-job' ){
            $event_hook = 'fts_clear_cache_event';

            DebugLog::log( 'cronJobs', 'Running fts_clear_cache_event', true );

            // Unschedule any existing events
            $timestamp = wp_next_scheduled($event_hook);
            if ($timestamp) {
                wp_unschedule_event($timestamp, $event_hook);

                DebugLog::log( 'cronJobs', 'Running wp_unschedule_event', true );
            }

            // Schedule the new event
            wp_schedule_event(time(), 'fts_cache_clear', $event_hook);

            DebugLog::log( 'cronJobs', 'Running wp_schedule_event', true );
        }
        else {
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
            $this->optionsFunctions->updateSingleOption('fts_feed_options_array', "{$feed_name}_scheduled_event", $event_hook, true, $cpt_id, false);
        }

    }

    /**
     * The task to run for each cron job.
     */
    public function ftsRefreshTokenTask($cpt_id, $feed_name ) {
        if ($feed_name === 'instagram_business_basic') {
            $this->feedFunctions->ftsInstagramRefreshToken($cpt_id);
        }
        elseif ($feed_name === 'tiktok') {
            $this->feedFunctions->ftsTiktokRefreshToken($cpt_id);
        }
        elseif ($feed_name === 'youtube') {
            $this->feedFunctions->ftsYoutubeRefreshToken($cpt_id);
        }
    }

    /**
     * Register actions for each scheduled cron event.
     */
    public function registerCronActions() {
        // Retrieve all CPTs that have a cron event hook set
        $args = [
            'post_type'   => 'fts', // Replace with your CPT name
            'numberposts' => -1,
            'fields'      => 'ids'
        ];
        $posts = get_posts($args);
        foreach ($posts as $feedCptId) {

            $instagram_hook = $this->feedFunctions->getFeedOption($feedCptId, 'instagram_business_basic_scheduled_event');
            if (!empty($instagram_hook)) {
                add_action($instagram_hook, function() use ($feedCptId) {
                    $this->ftsRefreshTokenTask($feedCptId, 'instagram_business_basic');
                });
            }

            $tiktok_hook = $this->feedFunctions->getFeedOption($feedCptId, 'tiktok_scheduled_event');
            if (!empty($tiktok_hook)) {
                add_action($tiktok_hook, function() use ($feedCptId) {
                    $this->ftsRefreshTokenTask($feedCptId, 'tiktok');
                });
            }

            $youtube_hook = $this->feedFunctions->getFeedOption($feedCptId, 'youtube_scheduled_event');
            if (!empty($youtube_hook)) {
                add_action($youtube_hook, function() use ($feedCptId) {
                    $this->ftsRefreshTokenTask($feedCptId, 'youtube');
                });
            }
        }

        // Register cache clear action
        add_action('fts_clear_cache_event', array($this, 'clearCacheTask'));
    }
}//end class
