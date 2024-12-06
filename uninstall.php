<?php

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;

/**
 * Uninstall Feed Them Social.
 *
 * Removes all settings.
 *
 * @package     Feed Them Social
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 *
 */

/**
 * Determine whether to run multisite uninstall or standard.
 *
 * @since   4.0
 */
if ( is_multisite() )   {
    global $wpdb;

    foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $blog_id )  {
        switch_to_blog( $blog_id );
        fts_uninstall();
        restore_current_blog();
    }

} else  {
    fts_uninstall();
}

/**
 * The main uninstallation function.
 *
 * The uninstall will only execute if the user has explicitly
 * enabled the option for data to be removed.
 *
 * @since   4.3.4
 */
function fts_uninstall()    {
	$fts_settings = get_option( 'fts_settings' );

    if ( isset( $fts_settings['remove_on_uninstall'] ) && $fts_settings['remove_on_uninstall'] === '-1' ) {
        return;
	}

	$fts_all_options = array(
		'fts_version',
		'fts_settings'
	);

	foreach( $fts_all_options as $fts_all_option )	{
		delete_option( $fts_all_option );
        // error_log('fts_uninstall: deleted option: ' . $fts_all_option);
	}

    // Remove custom post types
    $custom_post_types = array('fts');

    foreach ($custom_post_types as $post_type) {
        // Get all posts of the custom post type
        $posts = get_posts(array(
            'post_type'      => $post_type,
            'posts_per_page' => -1, // Get all posts
            'post_status'    => 'any' // Include all statuses
        ));

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true); // Delete permanently
            // error_log('fts_uninstall: deleted custom post type: ' . $post_type . ' with ID: ' . $post->ID);
        }
    }

    // Remove all cron jobs related to the plugin
    $cron_prefixes = array('fts_instagram', 'fts_youtube', 'fts_tiktok', 'fts_clear_cache');

    // Access the global cron array directly
    $crons = _get_cron_array();
    if (!empty($crons)) {
        foreach ($crons as $timestamp => $cron) {
            foreach ($cron as $hook => $events) {
                foreach ($cron_prefixes as $prefix) {
                    if (strpos($hook, $prefix) === 0) {
                        // Remove all instances of this hook
                        unset($crons[$timestamp][$hook]);
                        // error_log('fts_uninstall: fully removed cron job: ' . $hook . ' from timestamp: ' . $timestamp);
                    }
                }
            }
        }
        // Update the global cron array after making changes
        _set_cron_array($crons);
    }

} // fts_uninstall