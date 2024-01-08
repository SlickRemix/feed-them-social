<?php

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;

/**
 * Uninstall Feed Them Gallery.
 *
 * Removes all settings.
 *
 * @package     Feed Them Social
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 *
 */

/**
 * Determine whether to run multisite uninstall or standard.
 *
 * @since   1.0
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
 * @since   1.0
 */
function fts_uninstall()    {
	$fts_settings = get_option( 'fts_settings' );

	if ( empty( $fts_settings ) || empty( $fts_settings['remove_on_uninstall'] ) )	{
		return;
	}

	$fts_all_options = array(
		'fts_version',
		'fts_settings'
	);

	foreach( $fts_all_options as $fts_all_option )	{
		delete_option( $fts_all_option );
	}
} // fts_uninstall
