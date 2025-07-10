<?php
/**
 * Feed Them Social
 *
 * Class Feed Them Social Load Plugin Class
 *
 * @class    Feed_Them_Social
 * @version  3.0.0
 * @package  FeedThemSocial/Core
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

// PSR-4 Autoloader.
spl_autoload_register(function ( $class ) {
    $prefix = 'feedthemsocial\\';
    $base_dir = __DIR__ . '/';
    $len = \strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Import classes to use short names, following the instantiation order.
use feedthemsocial\data_protection\Data_Protection;
use feedthemsocial\options\Options_Functions;
use feedthemsocial\admin\settings\Settings_Functions;
use feedthemsocial\includes\Feed_Cache;
use feedthemsocial\admin\cpt\options\additional\Facebook_Additional_Options;
use feedthemsocial\admin\cpt\options\additional\Instagram_Additional_Options;
use feedthemsocial\admin\cpt\options\additional\Twitter_Additional_Options;
use feedthemsocial\admin\cpt\options\additional\Youtube_Additional_Options;
use feedthemsocial\admin\cpt\options\Feed_CPT_Options;
use feedthemsocial\includes\Feed_Functions;
use feedthemsocial\admin\settings\Settings_Page;
use feedthemsocial\admin\System_Info;
use feedthemsocial\admin\cpt\Feed_Options_Import_Export;
use feedthemsocial\admin\cpt\options\Settings_Options_JS;
use feedthemsocial\metabox\Metabox_Functions;
use feedthemsocial\admin\cpt\access_tokens\Access_Token_Options;
use feedthemsocial\admin\cpt\Feeds_CPT;
use feedthemsocial\includes\feeds\facebook\Facebook_Feed_Post_Types;
use feedthemsocial\includes\feeds\facebook\Facebook_Feed;
use feedthemsocial\includes\feeds\instagram\Instagram_Feed;
use feedthemsocial\includes\feeds\tiktok\Tiktok_Feed;
use feedthemsocial\includes\feeds\youtube\Youtube_Feed;
use feedthemsocial\includes\Feed_Shortcode;
use feedthemsocial\updater\Updater_Check_Init;
use feedthemsocial\blocks\Block_Loader;
use feedthemsocial\admin\cron_jobs\Cron_Jobs;
use feedthemsocial\includes\TrimWords;

/**
 * Feed Them Social Class
 */
class Feed_Them_Social {

    /**
     * Construct
     *
     * Access Token Options Page constructor.
     *
     * @since 1.9.6
     */
    public function __construct() {
        // Set up actions and filters first.
        $this->add_actions_filters();

        // Setup constants.
        $this->setup_constants('7.0.0');

        // Misc Includes.
        $this->includes();

        // Load plugin components on init, AFTER WordPress is fully loaded.
        add_action('init', array($this, 'load_plugin_components'), 5);
    }

    /**
     * Add Actions and Filters
     *
     * Add Actions and filters for the plugin.
     *
     * @since 1.0.0
     */
    private function add_actions_filters() {
        // Load text domain for translations
        add_action( 'init', array( $this, 'load_textdomain' ) );
    }

    /**
     * Load Plugin Components
     *
     * Load plugin components after WordPress init
     *
     * @since 4.3.8
     */
    public function load_plugin_components() {
        // All these 'new' statements will now automatically trigger the autoloader.
        $activate_plugin = new Activate_Plugin();
        $activate_plugin->add_actions_filters();

        $data_protection = new Data_Protection();
        $options_functions = new Options_Functions( FEED_THEM_SOCIAL_POST_TYPE );
        $settings_functions = new Settings_Functions();
        $feed_cache = new Feed_Cache( $data_protection, $settings_functions );
        $facebook_additional_options = new Facebook_Additional_Options();
        $instagram_additional_options = new Instagram_Additional_Options();
        $twitter_additional_options = new Twitter_Additional_Options();
        $youtube_additional_options = new Youtube_Additional_Options();
        $feed_cpt_options = new Feed_CPT_Options( $facebook_additional_options, $instagram_additional_options, $twitter_additional_options, $youtube_additional_options );
        $feed_functions = new Feed_Functions( $settings_functions, $options_functions, $feed_cpt_options, $feed_cache, $data_protection );

        new Settings_Page( $settings_functions, $feed_cache );
        $system_info = new System_Info( $settings_functions, $feed_functions, $feed_cache );
        new Feed_Options_Import_Export( $feed_functions, $data_protection, $system_info );
        $setting_options_js = new Settings_Options_JS();
        $metabox_functions = new Metabox_Functions( $feed_functions, $feed_cpt_options->get_all_options(true), $settings_functions, $options_functions, FEED_THEM_SOCIAL_OPTION_ARRAY_NAME, $data_protection );
        $access_options = new Access_Token_Options( $feed_functions, $feed_cpt_options, $metabox_functions, $data_protection, $options_functions );
        new Feeds_CPT( $settings_functions, $feed_functions, $feed_cpt_options, $setting_options_js, $metabox_functions, $access_options, $options_functions );
        $facebook_post_types = new Facebook_Feed_Post_Types( $feed_functions, $settings_functions, $access_options );
        $facebook_feed = new Facebook_Feed( $settings_functions, $feed_functions, $feed_cache, $facebook_post_types, $access_options );
        $instagram_feed = new Instagram_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );
        $tiktok_feed = new Tiktok_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );
        $youtube_feed = new Youtube_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );

        if( $feed_functions->is_extension_active( 'feed_them_social_combined_streams' ) ) {
            $combined_streams = new \feed_them_social_combined_streams\Combined_Streams_Feed( $feed_functions, $feed_cache, $access_options, $facebook_feed, $facebook_post_types, $instagram_feed, $tiktok_feed, $youtube_feed );
        } else {
            $combined_streams = '';
        }

        new Feed_Shortcode( $settings_functions, $feed_functions, $options_functions, $facebook_feed, $instagram_feed, $tiktok_feed, $youtube_feed, $combined_streams );
        new Block_Loader();
        new Cron_Jobs( $feed_functions, $options_functions, $settings_functions, $feed_cache );
        new Updater_Check_Init( $feed_functions );
        new TrimWords();
    }

    /**
     * Load Textdomain
     *
     * Load plugin textdomain.
     *
     * @since 4.3.8
     */
    public function load_textdomain() {
        // Localization. (Plugin string translations).
        // Needs FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR to make sure the path to languages folder is correct.
        load_plugin_textdomain( 'feed-them-social', false, FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/languages' );
    }

    /**
     * Setup Constants
     *
     * Setup plugin constants for plugin
     *
     * @since 1.0.0
     */
    private function setup_constants( $minimum_required_PHP_version ) {
        // Makes sure the plugin is defined before trying to use it.
        if ( ! \function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        // This is the URL the Updater / License Key Validation pings.
        if (!\defined('SLICKREMIX_STORE_URL')) {
            \define('SLICKREMIX_STORE_URL', 'https://www.slickremix.com/');
        }

        // Feed Them Social Post Type.
        if ( ! \defined( 'FEED_THEM_SOCIAL_POST_TYPE' )  ) {
            \define( 'FEED_THEM_SOCIAL_POST_TYPE', 'fts' );
        }

        // Feed Them Social Option Array Name. Used to set the option name in the database.
        if ( ! \defined( 'FEED_THEM_SOCIAL_OPTION_ARRAY_NAME' )  ) {
            define( 'FEED_THEM_SOCIAL_OPTION_ARRAY_NAME', 'fts_feed_options_array' );
        }

        // Minimum PHP Version for Feed Them Social.
        if ( ! \defined( 'FEED_THEM_SOCIAL_MIN_PHP' ) ) {
            \define( 'FEED_THEM_SOCIAL_MIN_PHP', $minimum_required_PHP_version );
        }

        // Plugin Basename.
        if ( ! \defined( 'FEED_THEM_SOCIAL_PLUGIN_BASENAME' ) ) {
            \define( 'FEED_THEM_SOCIAL_PLUGIN_BASENAME', 'feed-them-social/feed-them-social.php' );
        }

        // Plugins Absolute Path. (Needs to be after BASENAME constant to work).
        if ( ! \defined( 'FEED_THEM_SOCIAL_PLUGIN_ABS_PATH' ) ) {
            \define( 'FEED_THEM_SOCIAL_PLUGIN_ABS_PATH', plugin_dir_path( __DIR__ ) . FEED_THEM_SOCIAL_PLUGIN_BASENAME );
        }

        // Plugin version. (Needs to be after BASENAME and ABS_PATH constants to work).
        if ( ! \defined( 'FEED_THEM_SOCIAL_VERSION' ) ) {
            \define( 'FEED_THEM_SOCIAL_VERSION', FTS_CURRENT_VERSION );
        }

        // Plugin Folder Path.
        if ( ! \defined( 'FEED_THEM_SOCIAL_PLUGIN_PATH' ) ) {
            \define( 'FEED_THEM_SOCIAL_PLUGIN_PATH', plugins_url() );
        }

        // Plugin Directory Path.
        if ( ! \defined( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR' ) ) {
            \define( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR', plugin_dir_path( __FILE__ ) );
        }

        // Premium Extension List.
        if ( ! \defined( 'FEED_THEM_SOCIAL_PREM_EXTENSION_LIST' ) ) {
            \define( 'FEED_THEM_SOCIAL_PREM_EXTENSION_LIST', array(
                    'feed_them_social_premium'          => array(
                        // Title MUST match title of product in EDD store on site plugin is being sold!
                        'title'        => 'Feed Them Social Premium',
                        'plugin_url'   => 'feed-them-premium/feed-them-premium.php',
                        'demo_url'     => 'https://feedthemsocial.com/facebook-page-feed-demo/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/',
                    ),
                    'feed_them_social_combined_streams' => array(
                        'title'        => 'Feed Them Social Combined Streams',
                        'plugin_url'   => 'feed-them-social-combined-streams/feed-them-social-combined-streams.php',
                        'demo_url'     => 'https://feedthemsocial.com/feed-them-social-combined-streams/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-combined-streams/',
                    ),
                    'feed_them_social_facebook_reviews' => array(
                        'title'        => 'Feed Them Social Facebook Reviews',
                        'load_class'   => 'Feed_Them_Social_Facebook_Reviews',
                        'plugin_url'   => 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php',
                        'demo_url'     => 'https://feedthemsocial.com/facebook-page-reviews-demo/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-facebook-reviews/',
                    ),
                    'feed_them_carousel_premium'        => array(
                        'title'        => 'Feed Them Carousel Premium',
                        'plugin_url'   => 'feed-them-carousel-premium/feed-them-carousel-premium.php',
                        'demo_url'     => 'https://feedthemsocial.com/facebook-carousels-or-sliders/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-carousel-premium/',
                    ),
                    'feed_them_social_tiktok_premium' => array(
                        'title'        => 'Feed Them Social TikTok Premium',
                        'load_class'   => 'Feed_Them_Social_TikTok_Premium',
                        'plugin_url'   => 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php',
                        'demo_url'     => 'https://feedthemsocial.com/tiktok-feed-demo/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-tiktok-premium/',
                    ),
                    'feed_them_social_instagram_slider' => array(
                        'title'        => 'Feed Them Social Instagram Slider',
                        'load_class'   => 'Feed_Them_Social_Instagram_Slider',
                        'plugin_url'   => 'feed-them-social-instagram-slider/feed-them-social-instagram-slider.php',
                        'demo_url'     => 'https://feedthemsocial.com/instagram-slider/',
                        'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-instagram-slider/',
                    ),
                )
            );
        }

        // Facebook Graph URL.
        if ( ! \defined( 'FTS_FACEBOOK_GRAPH_URL' ) ) {
            \define( 'FTS_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/' );
        }

        // Access Token XXX.
        if ( ! \defined( 'FTS_ACCESS_TOKEN_XXX' ) ) {
            \define( 'FTS_ACCESS_TOKEN_XXX', 'access_token=XXX' );
        }

        // Access Token Equals.
        if ( ! \defined( 'FTS_ACCESS_TOKEN_EQUALS' ) ) {
            \define( 'FTS_ACCESS_TOKEN_EQUALS', 'access_token=' );
        }

        // And Access Token Equals.
        if ( ! \defined( 'FTS_AND_ACCESS_TOKEN_EQUALS' ) ) {
            \define( 'FTS_AND_ACCESS_TOKEN_EQUALS', '&access_token=' );
        }
    }

    /**
     * Includes Files
     *
     * Include files needed for Feed Them Social
     * Exceptions for PSR-4 autoloader.
     *
     * @since 1.0.0
     */
    public function includes() {

        // Beaver Builder Module
        if (class_exists('FLBuilder')) {
            include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/modules/beaver-builder/includes/module.php';
        }

        // Elementor Module
        if ( did_action( 'elementor/loaded' ) ) {
            include_once FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/modules/elementor/includes/module.php';
        }
    }
}
