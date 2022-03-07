<?php
/**
 * Feed Them Social Class (Main Class)
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social - Page, Post, Video and Photo Galleries
 * Plugin URI: https://feedthemsocial.com/
 * Description: Customize feeds for Facebook Pages, Album Photos, Videos & Covers, Instagram, Twitter, Pinterest & YouTube on pages, posts or widgets.
 * Version: 3.0
 * Author: SlickRemix
 * Author URI: https://www.slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: WordPress 4.0.0
 * Tested up to: WordPress 5.3.0
 * Stable tag: 3.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    3.0
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2022 SlickRemix
 *
 * Need Support: https://wordpress.org/support/plugin/feed-them-social
 * Paid Extension Support: https://www.slickremix.com/my-account/#tab-support
 */

// Doing this ensures any JS or CSS changes are reloaded properly. Added to enqueued css and js files throughout.
define( 'FTS_CURRENT_VERSION', '3.0' );

// Require file for plugin loading.
require_once __DIR__ . '/class-load-plugin.php';

// Feed Them Social Class
$feed_them_social = new Feed_Them_Social();

// Load the Plugin!
$feed_them_social->load_plugin();