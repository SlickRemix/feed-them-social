<?php
/**
 * Feed Them Social Class (Main Class)
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social - Page, Post, Video and Photo Galleries
 * Plugin URI: https://feedthemsocial.com/
 * Description: Custom feeds for Instagram, TikTok, Facebook Pages, Album Photos, Videos & Covers & YouTube on pages, posts, widgets, Elementor & Beaver Builder.
 * Version: 4.2.9
 * Author: SlickRemix
 * Author URI: https://www.slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: WordPress 5.4
 * Tested up to: WordPress 6.5.5
 * Stable tag: 4.2.9
 * Requires PHP: 7.0
 * Tested PHP: 8.3
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    4.2.9
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2024 SlickRemix
 *
 * Need Support: https://wordpress.org/support/plugin/feed-them-social
 * Paid Extension Support: https://www.slickremix.com/my-account/#tab-support
 */

// Set Plugin's Current Version.
define( 'FTS_CURRENT_VERSION', '4.2.9' );

// Require file for plugin loading.
require_once __DIR__ . '/class-load-plugin.php';

// Feed Them Social Class. Load up the plugin.
new Feed_Them_Social();