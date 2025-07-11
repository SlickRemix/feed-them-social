<?php
/**
 * Handles all debug logging for the plugin with granular control.
 *
 * To enable, define the FTS_DEBUG constant in wp-config.php.
 * - To enable specific groups: define('FTS_DEBUG', 'api,cache,shortcode');
 * - To enable all groups: define('FTS_DEBUG', 'all');
 *
 * If the constant is not defined, this function does nothing.
 *
 * Add this to enable debug logging for specific groups:
 * DebugLog::log( 'api', 'Access Token Received', $access_token );
 * or to show all use
 * DebugLog::log( 'all', 'Access Token Received', $access_token );
 * DebugLog::log( 'json', 'Decoded JSON Value', json_decode($value) );
 * DebugLog::log( 'CronJobs', 'Running clearCacheTask', true );
 *
 * @package FeedThemSocial/Includes
 */

namespace feedthemsocial\includes;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class DebugLog
 *
 * Handles all debug logging for the plugin with granular control.
 * This is a static utility class and should not be instantiated.
 */
final class DebugLog {

    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct() {}

    /**
     * Handles all debug logging for the plugin.
     *
     * To enable, define the FTS_DEBUG constant in wp-config.php.
     * - To enable specific groups: define('FTS_DEBUG', 'api,cache,shortcode');
     * - To enable all groups:     define('FTS_DEBUG', 'all');
     *
     * @param string $group The debug group (e.g., 'api', 'cache', 'shortcode').
     * @param string $title A title for the log entry.
     * @param mixed  $data  The data to log (string, array, object).
     */
    public static function log( string $group, string $title, $data ) {
        if ( ! \defined( 'FTS_DEBUG' ) ) {
            return;
        }

        $enabled_groups_str = strtolower( FTS_DEBUG );
        $enabled_groups     = array_map( 'trim', explode( ',', $enabled_groups_str ) );

        if ( ! \in_array( strtolower( $group ), $enabled_groups, true ) && ! \in_array( 'all', $enabled_groups, true ) ) {
            return;
        }

        $message = \sprintf(
            '[FTS Debug | %s] %s: %s',
            ucfirst( $group ),
            $title,
            print_r( $data, true )
        );
        // This will log to the PHP error log.
        error_log( $message );
    }
}
