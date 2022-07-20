<?php
/**
 * Feed Them Social Class (Main Class)
 *
 * This class is what initiates the Feed Them Social class
 *
 * Plugin Name: Feed Them Social - for Twitter feed, Youtube, and more
 * Plugin URI: https://feedthemsocial.com/
 * Description: Display a Custom Facebook feed, Instagram feed, Twitter feed and YouTube feed on pages, posts or widgets.
 * Version: 3.0.1
 * Author: SlickRemix
 * Author URI: https://www.slickremix.com/
 * Text Domain: feed-them-social
 * Domain Path: /languages
 * Requires at least: WordPress 4.0.0
 * Tested up to: WordPress 6.0.1
 * Stable tag: 3.0.1
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version    3.0.1
 * @package    FeedThemSocial/Core
 * @copyright  Copyright (c) 2012-2022 SlickRemix
 *
 * Need Support: https://wordpress.org/support/plugin/feed-them-social
 * Paid Extension Support: https://www.slickremix.com/my-account/#tab-support
 */

/**
 * Feed Them Social Current Version
 *
 * Makes sure any js or css changes are reloaded properly. Added to enqued css and js files throughout!
 */
define( 'FTS_CURRENT_VERSION', '3.0.1' );

define( 'FEED_THEM_SOCIAL_NOTICE_STATUS', get_option( 'rating_fts_slick_notice', false ) );

/**
 * Class Feed_Them_Social
 */
final class Feed_Them_Social {

    /**
     * Main Instance of Feed Them Social
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Create Instance of Feed Them Social
     *
     * @since 1.0.0
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Feed_Them_Social ) ) {
            self::$instance = new Feed_Them_Social();

            if ( ! function_exists( 'is_plugin_active' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
            }

            // Third check the php version is not less than 5.2.9
            // Make sure php version is greater than 5.3!
            if ( function_exists( 'phpversion' ) ) {
                $phpversion = phpversion();
            }
            $phpcheck = '5.2.9';
            if ( $phpversion > $phpcheck ) {
                // Add actions!
                add_action( 'init', array( self::$instance, 'fts_action_init' ) );
            } else {
                // if the php version is not at least 5.3 do action!
                deactivate_plugins( 'feed-them-social/feed-them-social.php' );
                if ( $phpversion < $phpcheck ) {
                    add_action( 'admin_notices', array( self::$instance, 'fts_required_php_check1' ) );

                }
            }

            register_activation_hook( __FILE__, array( self::$instance, 'fts_activate' ) );

            add_action( 'admin_init', array( self::$instance, 'feed_them_social_load_plugin' ) );
            add_action( 'admin_notices', array( self::$instance, 'fts_install_notice' ) );
            add_action( 'admin_notices', array( self::$instance, 'fts_update_notice' ) );
            add_action( 'upgrader_process_complete', array( self::$instance, 'fts_upgrade_completed' ), 10, 2 );
            // Inlcude our custom message letting users know of Major Changes to Feed Them Social 3.0
            add_action( 'in_plugin_update_message-feed-them-social/feed-them.php', array( self::$instance, 'prefix_plugin_update_message' ), 10, 2 );

            // Include our own Settings link to plugin activation and update page.
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( self::$instance, 'fts_free_plugin_actions' ), 10, 4 );

            // Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
            add_filter( 'plugin_row_meta', array( self::$instance, 'fts_leave_feedback_link' ), 10, 2 );

            add_action( 'admin_init', array( self::$instance, 'fts_check_nag_get' ) );
            add_action( 'admin_init', array( self::$instance, 'fts_maybe_set_transient' ) );

            // only display the notice if the time offset has passed and the user hasn't already dismissed it!
            if ( 'fts-review-waiting' !== get_transient( 'rating_fts_slick_notice_waiting' ) && 'dismissed' !== FEED_THEM_SOCIAL_NOTICE_STATUS ) {
                add_action( 'admin_notices', array( self::$instance, 'fts_rating_notice_html' ) );
            }

            // Setup Constants for FTS!
            self::$instance->setup_constants();
            // Include the files!
            self::$instance->includes();
            // Error Handler!
            self::$instance->error_handler = new feedthemsocial\fts_error_handler();

            // Truncate HTML Class
            self::$instance->truncate_html = new FeedThemSocialTruncateHTML();

            // DATA PROTECTIOM
            self::$instance->data_protection = new feedthemsocial\Data_Protection(); // Core (and load init)!

            // Core (and load init)!
            self::$instance->core_functions = new feedthemsocial\feed_them_social_functions( );

            // Free Plugin License page!
            self::$instance->updater = new feedthemsocial\updater_init();

            // Facebook!
            self::$instance->facebook_feed = new feedthemsocial\FTS_Facebook_Feed( );

            // Twitter!
            self::$instance->twitter_feed = new feedthemsocial\FTS_Twitter_Feed();

            // Instagram!
            self::$instance->instagram_feed = new feedthemsocial\FTS_Instagram_Feed();

            // Pinterest!
            // self::$instance->pinterest_feed = new feedthemsocial\FTS_Pinterest_Feed();

            // Youtube!
            self::$instance->youtube_feed = new feedthemsocial\FTS_Youtube_Feed_Free();
        }

        return self::$instance;
    }

    /**
     * This function runs when WordPress completes its upgrade process
     *
     * It iterates through each plugin updated to see if ours is included
     *
     * @param string $upgrader_object What we upgrading.
     * @param array  $options options for upgrade.
     * @since 1.0.0
     */
    public function fts_upgrade_completed( $upgrader_object, $options ) {
        // The path to our plugin's main file!
        $our_plugin = plugin_basename( __FILE__ );
        // If an update has taken place and the updated type is plugins and the plugins element exists!
        if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there!
            foreach ( $options['plugins'] as $plugin ) {
                if ( $plugin === $our_plugin ) {
                    // Set a transient to record that our plugin has just been updated!
                    set_transient( 'fts_updated', 1 );
                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     *
     * @since 1.0.0
     */
    public function fts_update_notice() {
        // Check the transient to see if we've just updated the plugin!
        if ( get_transient( 'fts_updated' ) ) {
            echo '<div class="notice notice-success updated is-dismissible">';
            echo sprintf(
                    esc_html__( '%10$sThanks for updating Feed Them Social. We have deleted the cache in our plugin so you can view any changes we have made.%11$s %8$s%6$sNOTE%7$s: Feed Them Social will have some amazing changes in the coming months. Please take a moment and %4$s read them here%5$s.%9$s', 'feed-them-social' ),
                    '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
                    '</a>',
                    '<br/><br/>',
                    '<a href="' . esc_url( 'https://www.slickremix.com/feed-them-social-3-0-major-changes/' ) . '" target="_blank">',
                    '</a>',
                    '<strong>',
                    '</strong>',
                    '<div class="fts-update-message">',
                    '</div>',
                    '<p>',
                    '</p>'
                );
            echo '</div>';
            delete_transient( 'fts_updated' );
        }
    }

    /**
     * Show a notice to anyone who has just installed the plugin for the first time
     * This notice shouldn't display to anyone who has just updated this plugin
     *
     * @since 1.0.0
     */
    public function fts_install_notice() {
        // Check the transient to see if we've just activated the plugin!
        if ( get_transient( 'fts_activated' ) ) {
            echo '<div class="notice notice-success updated is-dismissible">';
            echo sprintf(
                esc_html__( '%10$sThanks for installing Feed Them Social. To get started please view our %1$sSettings%2$s page.%11$s %8$s%6$sNOTE%7$s: Feed Them Social 3.0 will have some amazing changes in the coming months. Please take a moment and %4$s read them here%5$s.%9$s', 'feed-them-social' ),
                '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
                '</a>',
                '<br/><br/>',
                '<a href="' . esc_url( 'https://www.slickremix.com/feed-them-social-3-0-major-changes/' ) . '" target="_blank">',
                '</a>',
                '<strong>',
                '</strong>',
                '<div class="fts-update-message">',
                '</div>',
                '<p>',
                '</p>'
            );
            echo '</div>';
            // Delete the transient so we don't keep displaying the activation message!
            delete_transient( 'fts_activated' );
        }

    }

   function prefix_plugin_update_message( $plugin_data, $new_data ) {
       echo '<div class="fts-update-message">';
       echo sprintf(
           esc_html__( 'Feed Them Social 3.0 will have some amazing changes in the coming months. Please take a moment and %4$s read them here%5$s.', 'feed-them-social' ),
           '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
           '</a>',
           '<br/><br/>',
           '<a href="' . esc_url( 'https://www.slickremix.com/feed-them-social-3-0-major-changes/' ) . '" target="_blank">',
           '</a>',
           '<strong>',
           '</strong>'
       );
       echo '</div>';
   }

    /**
     * Run this on activation
     * Set a transient so that we know we've just activated the plugin
     *
     * @since 1.0.0
     */
    public function fts_activate() {
        set_transient( 'fts_activated', 1 );

        // we add an db option to check then delete the db option after activation and the cache has emptied.
        // the delete_option is on the feed-them-functions.php file at the bottom of the function ftg_clear_cache_script!
        add_option( 'Feed_Them_Social_Activated_Plugin', 'feed-them-social' );
    }

    /**
     * Setup Constants
     *
     * Setup plugin constants for plugin
     *
     * @since 1.0.0
     */
    private function setup_constants() {
        // Makes sure the plugin is defined before trying to use it!
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $plugin_data    = get_plugin_data( __FILE__ );
        $plugin_version = $plugin_data['Version'];

        // Free Version Plugin version!
        if ( ! defined( 'FEED_THEM_SOCIAL_VERSION' ) ) {
            define( 'FEED_THEM_SOCIAL_VERSION', $plugin_version );
        }

        // Plugin Folder Path!
        if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_PATH' ) ) {
            define( 'FEED_THEM_SOCIAL_PLUGIN_PATH', plugins_url() );
        }
        // Plugin Directoy Path!
        if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR' ) ) {
            define( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR', plugin_dir_path( __FILE__ ) );
        }

        if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
            // Plugin Directoy Path!
            if ( ! defined( 'FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR' ) ) {
                define( 'FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR', WP_PLUGIN_DIR . '/feed-them-premium/feed-them-premium.php' );
            }
        }
        // Define constants!
        if ( ! defined( 'MY_TEXTDOMAIN' ) ) {
            define( 'MY_TEXTDOMAIN', 'feed-them-social' );
        }
    }

    /**
     * Includes Files
     *
     * Include files needed for Feed Them Social
     *
     * @since 1.0.0
     */
    private function includes() {

        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/error-handler.php';

        //Data Protection
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/data-protection/data-protection.php';

        // Core classes!
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-them-functions.php';
        $load_fts = new feedthemsocial\feed_them_social_functions( );
        $load_fts->init();

        // Admin Pages!
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-system-info-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-settings-page-options.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-settings-page.php';

        // Feed Option Pages!
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-facebook-options-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-twitter-options-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-instagram-options-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-pinterest-options-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/class-fts-youtube-options-page.php';

        // Updater Classes!
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-license-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-class.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-init.php';

        // Feed Classes!
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/facebook/class-fts-facebook-feed.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/facebook/class-fts-facebook-feed-post-types.php';
        $load_fb_fts = 'feedthemsocial\FTS_Facebook_Feed';
        new $load_fb_fts();
        include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/twitter/class-fts-twitter-feed.php';
        $load_tw_fts = 'feedthemsocial\FTS_Twitter_Feed';
        new $load_tw_fts();
        include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/instagram/class-fts-instagram-feed.php';
        include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/pinterest/class-fts-pinterest-feed.php';

        include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'feeds/youtube/class-youtube-feed-free.php';


        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/trim-words.php';
    }

    /**
     * FTS Action Init
     *
     * Loads language files
     *
     * @since 1.0.0
     */
    public function fts_action_init() {
        // Localization!
        load_plugin_textdomain( 'feed-them-social', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * FTS Required php Check
     *
     * Are they running proper PHP version
     *
     * @since 1.0.0
     */
    public function fts_required_php_check1() {
        echo '<div class="error"><p>';
        echo sprintf(
            esc_html__( '%1$s Feed Them Social Warning:%2$s Your php version is %1$s%3$s%2$s. You need to be running at least %1$s5.3%2$s or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too. %4$s If you are hosting with BlueHost or Godaddy and the php version above is saying you are running %1$s5.2.17%2$s but you are really running something higher please %5$sclick here for the fix%6$s. If you cannot get it to work using the method described in the link please contact your host provider and explain the problem so they can fix it.', 'feed-them-social' ),
            '<strong>',
            '</strong>',
            phpversion(),
            '<br/><br/>',
            '<a href="' . esc_url( 'https://wordpress.org/support/topic/php-version-difference-after-changing-it-at-bluehost-php-config?replies=4' ) . '" target="_blank">',
            '</a>'
        );
        echo '</p></div>';
    }

    /**
     * FTS Plugin Actions
     *
     * Loads links in the Plugins page in WordPress Dashboard
     *
     * @param array  $actions  actions.
     * @param string $plugin_file path to file.
     * @param string $plugin_data plugin info.
     * @param string $context the context.
     * @return mixed
     * @since 1.0.0
     */
    public function fts_free_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
        array_unshift(
            $actions,
            '<a href="admin.php?page=feed-them-settings-page">' . esc_html__( 'Settings', 'feed-them-social' ) . '</a> | <a href="' . esc_url( 'https://www.slickremix.com/support/', 'feed-them-social' ) . '">' . esc_html__( 'Support', 'feed-them-social' ) . '</a>'
        );
        return $actions;
    }

    /**
     * FTS Leave Feedback Link
     *
     * Link to add feedback for plugin
     *
     * @param array  $links links to check.
     * @param string $file path to plugin main file.
     * @return mixed
     * @since 1.0.0
     */
    public function fts_leave_feedback_link( $links, $file ) {
        if ( plugin_basename( __FILE__ ) === $file ) {
            $links['feedback'] = '<a href="' . esc_url( 'https://wordpress.org/support/view/plugin-reviews/feed-them-social', 'feed-them-social' ) . '" target="_blank">' . esc_html__( 'Rate Plugin', 'feed-them-social' ) . '</a>';
        }
        return $links;
    }

    /**
     * FTS Load Plugin
     *
     * Load plugin options on activation check
     *
     * @since 1.0.0
     */
    public function feed_them_social_load_plugin() {

        if ( is_admin() && 'feed-them-social' === get_option( 'Feed_Them_Social_Activated_Plugin' ) ) {

            // The Options list!
            $activation_options = array(
                'fts-date-and-time-format'       => 'one-day-ago',
                'fts_clear_cache_developer_mode' => '86400',
                'fts_admin_bar_menu' => 'show-admin-bar-menu',
            );

            foreach ( $activation_options as $option_key => $option_value ) {
                // We don't use update_option because we only want this to run for options that have not already been set by the user!
                add_option( $option_key, $option_value );
            }
        }
    }

    /**
     * FTS Review Check
     *
     * Checks $_GET to see if the nag variable is set and what it's value is
     *
     * @since 2.4.5
     */
    public function fts_check_nag_get() {
        $fts_nag_nonce = wp_create_nonce( 'fts-nag-nonce' );

        global $current_user;
        $user_id = $current_user->ID;
        // Used for testing: delete_user_meta( $user_id, 'fts_slick_ignore_rating_notice' );
        if ( wp_verify_nonce( $fts_nag_nonce, 'fts-nag-nonce' ) ) {

            $transient = 'rating_fts_slick_notice_waiting';
            $option    = 'rating_fts_slick_notice';
            $nag       = 'rating_fts_slick_ignore_notice_nag';

            // Used for testing: echo isset( $_GET[ $nag ] ) ? $_GET[ $nag ] : 'no set nag';.
            if ( isset( $_GET[ $nag ] ) && '1' === $_GET[ $nag ] && ! get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' ) ) {

                update_option( $option, 'dismissed' );
                update_user_meta( $user_id, 'fts_slick_ignore_rating_notice', '1' );
            } elseif ( isset( $_GET[ $nag ] ) && 'later' === $_GET[ $nag ] ) {
                $time = 2 * WEEK_IN_SECONDS;
                // Used for testin: echo $time;.
                set_transient( $transient, 'fts-review-waiting', $time );
                update_option( $option, 'pending' );
            }
            // Used for testin: echo 'no hit';.
            return;
        }
    }

    /**
     * FTS Set Review Transient
     *
     * Set a transient if the notice has not been dismissed or has not been set yet.
     *
     * @since 2.4.5
     */
    public function fts_maybe_set_transient() {

        $fts_set_transient_nonce = wp_create_nonce( 'fts-set-transient-nonce' );

        if ( wp_verify_nonce( $fts_set_transient_nonce, 'fts-set-transient-nonce' ) ) {
            // Variables to define specific terms!
            $transient = 'rating_fts_slick_notice_waiting';
            $option    = 'rating_fts_slick_notice';

            $fts_rating_notice_waiting = get_transient( $transient );
            $notice_status             = get_option( $option, false );

            global $current_user;
            $user_id = $current_user->ID;

            if ( ! $fts_rating_notice_waiting && ! ( 'dismissed' === $notice_status || 'pending' === $notice_status ) && ! get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' ) ) {
                $time = 2 * WEEK_IN_SECONDS;
                set_transient( $transient, 'fts-review-waiting', $time );
                update_option( $option, 'pending' );
                // Used for testing: print 'waiting';.
            }
            // Used for testing:
            // print get_transient( $transient );
            // Used for testing: print ' & ';
            // Used for testing: print $notice_status;
            // Used for testing:
            // update_option( $option, '' );
            // Used for testing:
            // set_transient( $transient, '', '' );
            // Used for testing: echo 'no hit2';.
            return;
        }
    }

    /**
     * FTS Ratings Notice
     *
     * Generates the html for the admin notice
     *
     * @since 2.4.5
     */
    public function fts_rating_notice_html() {

        // Only show to admins!
        if ( current_user_can( 'manage_options' ) ) {

            global $current_user;
            $user_id = $current_user->ID;

            // Used for testing: print_r( get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' ) );
            // Used for testing: $all_meta_for_user = get_user_meta( $user_id );
            // Used for testing: print_r( $all_meta_for_user );.
            /* Has the user already clicked to ignore the message? */
            if ( ! get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' )  && ! isset( $_GET['rating_fts_slick_ignore_notice_nag'] ) ) {

                ?>
                <div class="fts_notice fts_review_notice">
                    <img src="<?php echo esc_url( plugins_url( 'feed-them-social/admin/images/feed-them-social-logo.png' ) ); ?>" alt="Feed Them Social">
                    <div class="fts-notice-text">
                        <p><?php echo esc_html__( 'It\'s great to see that you\'ve been using our Feed Them Social plugin for a while now. Hopefully you\'re happy with it!  If so, would you consider leaving a positive review? It really helps support the plugin and helps others discover it too!', 'feed-them-social' ); ?></p>
                        <p class="fts-links">
                            <a class="fts_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Sure, I\'d love to', 'feed-them-social' ); ?></a>
                            <a class="fts_notice_dismiss" href="<?php echo esc_url( add_query_arg( 'rating_fts_slick_ignore_notice_nag', '1' ) ); ?>"><?php echo esc_html__( 'I\'ve already given a review', 'feed-them-social' ); ?></a>
                            <a class="fts_notice_dismiss" href="<?php echo esc_url( add_query_arg( 'rating_fts_slick_ignore_notice_nag', 'later' ) ); ?>"><?php echo esc_html__( 'Ask me later', 'feed-them-social' ); ?></a>
                            <a class="fts_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Not working, I need support', 'feed-them-social' ); ?></a>
                            <a class="fts_notice_dismiss" href="<?php echo esc_url( add_query_arg( 'rating_fts_slick_ignore_notice_nag', '1' ) ); ?>"><?php echo esc_html__( 'No thanks', 'feed-them-social' ); ?></a>
                        </p>
                    </div>
                </div>
                <?php
            }
        }
    }
}

/**
 * Feed Them Social
 *
 * Start it up!
 *
 * @return feed_them_social
 * @since 1.0.0
 */
function feed_them_social() {

    return Feed_Them_Social::instance();
}

// Initiate Feed Them Social!
feed_them_social();
