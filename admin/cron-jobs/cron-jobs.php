<?php
/**
 * Cron Jobs
 *
 * This class is for loading up cron jobs for the TikTok Feed.
 * It will refresh an access token every 24 hours.
 * YouTube and Instagram will be added later.
 *
 * @class     Cron_Jobs
 * @version   4.2.1
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
     * Adds custom interval for the cron job
     */
    public function fts_cron_schedules($schedules) {
        // Add a 'once_daily' schedule if not already set
        if (!isset($schedules['once_daily'])) {
            $schedules['once_daily'] = array(
                'interval' => 86400, // 86400 is 24 hours in seconds
                'display'  => __('Once Daily')
            );
        }
        return $schedules;
    }

    /**
     * Set up a cron job.
     */
    public function fts_set_cron_job($cpt_id, $feed_name, $revoke_token) {

        // error_log("The cron job is setup for CPT ID: " . $cpt_id);

        $event_hook = "fts_{$feed_name}_refresh_token_{$cpt_id}";

        // Unschedule any existing event with this hook
        $timestamp = wp_next_scheduled($event_hook, array($cpt_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, $event_hook, array($cpt_id));
        }

        if( $revoke_token === false ){
            // Schedule a new event
            wp_schedule_event(time(), 'once_daily', $event_hook, array($cpt_id));
        }

        // Store the event hook name in the feed options
        $this->options_functions->update_single_option('fts_feed_options_array', 'tiktok_scheduled_event', $event_hook, true, $cpt_id, false);

    }

    /**
     * The task to run for each cron job.
     */
    public function fts_refresh_token_task($cpt_id) {
        // error_log("The cron job is working for CPT ID: " . $cpt_id);
        $this->feed_functions->fts_tiktok_refresh_token($cpt_id);
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
            $hook = $this->feed_functions->get_feed_option( $feed_cpt_id, 'tiktok_scheduled_event' );
            if (!empty($hook)) {
                add_action($hook, array($this, 'fts_refresh_token_task'));
               // error_log( 'register_cron_actions ' . $feed_cpt_id );
            }
        }
    }
}//end class
