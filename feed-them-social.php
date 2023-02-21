<?php
/**
 * Feed Them Social Class (Main Class)
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social - Page, Post, Video and Photo Galleries
 * Plugin URI: https://feedthemsocial.com/
 * Description: Custom feeds for Instagram, Facebook Pages, Album Photos, Videos & Covers, Twitter & YouTube on pages, posts or widgets.
 * Version: 4.0.0
 * Author: SlickRemix
 * Author URI: https://www.slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: WordPress 4.0.0
 * Tested up to: WordPress 6.1.1
 * Stable tag: 3.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    4.0.0
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2023 SlickRemix
 *
 * Need Support: https://wordpress.org/support/plugin/feed-them-social
 * Paid Extension Support: https://www.slickremix.com/my-account/#tab-support
 */

// Set Plugin Current Version.
define( 'FTS_CURRENT_VERSION', '4.0.0' );

// Require file for plugin loading.
require_once __DIR__ . '/class-load-plugin.php';

// Feed Them Social Class. Load up the plugin!
new Feed_Them_Social();