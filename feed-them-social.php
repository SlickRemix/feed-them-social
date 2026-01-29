<?php
/**
 * Feed Them Social
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social - Social Media Feeds, Video, and Photo Galleries
 * Plugin URI: https://feedthemsocial.com/
 * Description: Custom feeds for Instagram, TikTok, Facebook Pages, Album Photos, Videos & Covers & YouTube on pages, posts, widgets, Elementor & Beaver Builder.
 * Version: 4.4.1
 * Author: SlickRemix
 * Author URI: https://www.slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: WordPress 5.4
 * Tested up to: WordPress 6.9
 * Stable tag: 4.4.1
 * Requires PHP: 7.0
 * Tested PHP: 8.3
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    4.4.1
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2025 SlickRemix
 *
 * Need Support: https://wordpress.org/support/plugin/feed-them-social
 * Paid Extension Support: https://www.slickremix.com/my-account/#tab-support
 */

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

// Define the plugin version.
define( 'FTS_CURRENT_VERSION', '4.4.1' );

// Require the file that contains the new autoloader and main plugin class.
require_once __DIR__ . '/LoadPlugin.php'; // NOSONAR - false positive.

// Instantiate the main class to start the plugin.
// The autoloader will handle all other class dependencies from here.
new \feedthemsocial\LoadPlugin(); // NOSONAR - false positive.
