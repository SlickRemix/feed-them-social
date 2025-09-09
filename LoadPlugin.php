<?php
/**
 * Feed Them Social
 *
 * Class Feed Them Social Load Plugin Class
 *
 * @class    LoadPlugin
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
use feedthemsocial\data_protection\DataProtection;
use feedthemsocial\options\OptionsFunctions;
use feedthemsocial\admin\settings\SettingsFunctions;
use feedthemsocial\includes\FeedCache;
use feedthemsocial\admin\cpt\options\additional\FacebookAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\InstagramAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\TwitterAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\YoutubeAdditionalOptions;
use feedthemsocial\admin\cpt\options\FeedCPTOptions;
use feedthemsocial\includes\FeedFunctions;
use feedthemsocial\admin\settings\SettingsPage;
use feedthemsocial\admin\SystemInfo;
use feedthemsocial\admin\cpt\FeedOptionsImportExport;
use feedthemsocial\admin\cpt\options\SettingsOptionsJS;
use feedthemsocial\metabox\MetaboxFunctions;
use feedthemsocial\admin\cpt\access_tokens\AccessTokenOptions;
use feedthemsocial\admin\cpt\FeedsCPT;
use feedthemsocial\includes\feeds\facebook\FacebookFeedPostTypes;
use feedthemsocial\includes\feeds\facebook\FacebookFeed;
use feedthemsocial\includes\feeds\instagram\InstagramFeed;
use feedthemsocial\includes\feeds\tiktok\TiktokFeed;
use feedthemsocial\includes\feeds\youtube\YoutubeFeed;
use feedthemsocial\includes\FeedShortcode;
use feedthemsocial\updater\UpdaterCheckInit;
use feedthemsocial\blocks\BlockLoader;
use feedthemsocial\admin\cron_jobs\CronJobs;
use feedthemsocial\includes\TrimWords;
use feedthemsocial\includes\DebugLog;

/**
 * Feed Them Social Class
 */
class LoadPlugin {

    /**
     * Construct
     *
     * Access Token Options Page constructor.
     *
     * @since 1.9.6
     */
    public function __construct() {

        // Setup constants.
        $this->setupConstants('7.0.0');

        // Misc Includes.
        $this->includes();

        $activate_plugin = new ActivatePlugin();
        $activate_plugin->addActionsFilters();

        // Load plugin components on init, AFTER WordPress is fully loaded.
        add_action('init', array($this, 'loadPluginComponents'), 5);
    }

    /**
     * Add Actions and Filters
     *
     * Add Actions and filters for the plugin.
     *
     * @since 1.0.0
     */
    private function addActionsFilters() {
        // Load text domain for translations
        add_action( 'init', array( $this, 'loadTextdomain' ) );
    }

    /**
     * Load Plugin Components
     *
     * Load plugin components after WordPress init
     *
     * @since 4.3.8
     */
    public function loadPluginComponents() {
        // All these 'new' statements will now automatically trigger the autoloader.
        $dataProtection = new DataProtection();
        $optionsFunctions = new OptionsFunctions( FEED_THEM_SOCIAL_POST_TYPE );
        $settingsFunctions = new SettingsFunctions();
        $feedCache = new FeedCache( $dataProtection, $settingsFunctions );
        $facebookAdditionalOptions = new FacebookAdditionalOptions();
        $instagramAdditionalOptions = new InstagramAdditionalOptions();
        $twitterAdditionalOptions = new TwitterAdditionalOptions();
        $youtubeAdditionalOptions = new YoutubeAdditionalOptions();
        $feed_cpt_options = new FeedCPTOptions( $facebookAdditionalOptions, $instagramAdditionalOptions, $twitterAdditionalOptions, $youtubeAdditionalOptions );
        $feedFunctions = new FeedFunctions( $settingsFunctions, $optionsFunctions, $feed_cpt_options, $feedCache, $dataProtection );

        new SettingsPage( $settingsFunctions, $feedCache );
        $systemInfo = new SystemInfo( $settingsFunctions, $feedFunctions, $feedCache );
        new FeedOptionsImportExport( $feedFunctions, $dataProtection, $systemInfo );
        $settingOptionsJs = new SettingsOptionsJS();
        $metaboxFunctions = new MetaboxFunctions( $feedFunctions, $feed_cpt_options->getAllOptions(true), $settingsFunctions, $optionsFunctions, FEED_THEM_SOCIAL_OPTION_ARRAY_NAME, $dataProtection );
        $accessOptions = new AccessTokenOptions( $feedFunctions, $feed_cpt_options, $metaboxFunctions, $dataProtection, $optionsFunctions );
        new FeedsCPT( $settingsFunctions, $feedFunctions, $feed_cpt_options, $settingOptionsJs, $metaboxFunctions, $accessOptions, $optionsFunctions );
        $facebookPostTypes = new FacebookFeedPostTypes( $feedFunctions, $settingsFunctions, $accessOptions );
        $facebookFeed = new FacebookFeed( $settingsFunctions, $feedFunctions, $feedCache, $facebookPostTypes, $accessOptions );
        $instagramFeed = new InstagramFeed( $settingsFunctions, $feedFunctions, $feedCache, $accessOptions );
        $tiktok_feed = new TiktokFeed( $settingsFunctions, $feedFunctions, $feedCache, $accessOptions );
        $youtubeFeed = new YoutubeFeed( $settingsFunctions, $feedFunctions, $feedCache, $accessOptions );

        if( $feedFunctions->isExtensionActive( 'feed_them_social_combined_streams' ) ) {
            $combinedStreams = new \feed_them_social_combined_streams\Combined_Streams_Feed( $feedFunctions, $feedCache, $accessOptions, $facebookFeed, $facebookPostTypes, $instagramFeed, $tiktok_feed, $youtubeFeed );
        } else {
            $combinedStreams = '';
        }

        new FeedShortcode( $settingsFunctions, $feedFunctions, $optionsFunctions, $facebookFeed, $instagramFeed, $tiktok_feed, $youtubeFeed, $combinedStreams );
        new BlockLoader();
        new CronJobs( $feedFunctions, $optionsFunctions, $settingsFunctions, $feedCache );
        new UpdaterCheckInit( $feedFunctions );
        new TrimWords();
    }

    /**
     * Load Textdomain
     *
     * Load plugin textdomain.
     *
     * @since 4.3.8
     */
    public function loadTextdomain() {
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
    private function setupConstants( $minimum_required_PHP_version ) {
        // Makes sure the plugin is defined before trying to use it.
        if ( ! \function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $fts_constants = [
            'SLICKREMIX_STORE_URL'                 => 'https://www.slickremix.com/',
            'FEED_THEM_SOCIAL_POST_TYPE'           => 'fts',
            'FEED_THEM_SOCIAL_OPTION_ARRAY_NAME'   => 'fts_feed_options_array',
            'FEED_THEM_SOCIAL_MIN_PHP'             => $minimum_required_PHP_version,
            'FEED_THEM_SOCIAL_PLUGIN_BASENAME'     => 'feed-them-social/feed-them-social.php',
            'FEED_THEM_SOCIAL_PLUGIN_ABS_PATH'     => plugin_dir_path( __DIR__ ) . 'feed-them-social/feed-them-social.php',
            'FEED_THEM_SOCIAL_VERSION'             => FTS_CURRENT_VERSION,
            'FEED_THEM_SOCIAL_PLUGIN_PATH'         => plugins_url(),
            'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR'   => plugin_dir_path( __FILE__ ),
            'FTS_FACEBOOK_GRAPH_URL'               => 'https://graph.facebook.com/',
            'FTS_ACCESS_TOKEN_XXX'                 => 'access_token=XXX',
            'FTS_ACCESS_TOKEN_EQUALS'              => 'access_token=',
            'FTS_AND_ACCESS_TOKEN_EQUALS'          => '&access_token=',
            'FEED_THEM_SOCIAL_PREM_EXTENSION_LIST' => [
                'feed_them_social_premium'          => [
                    'title'        => 'Feed Them Social Premium',
                    'plugin_url'   => 'feed-them-premium/feed-them-premium.php',
                    'demo_url'     => 'https://feedthemsocial.com/facebook-page-feed-demo/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/',
                ],
                'feed_them_social_combined_streams' => [
                    'title'        => 'Feed Them Social Combined Streams',
                    'plugin_url'   => 'feed-them-social-combined-streams/feed-them-social-combined-streams.php',
                    'demo_url'     => 'https://feedthemsocial.com/feed-them-social-combined-streams/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-combined-streams/',
                ],
                'feed_them_social_facebook_reviews' => [
                    'title'        => 'Feed Them Social Facebook Reviews',
                    'load_class'   => 'Feed_Them_Social_Facebook_Reviews',
                    'plugin_url'   => 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php',
                    'demo_url'     => 'https://feedthemsocial.com/facebook-page-reviews-demo/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-facebook-reviews/',
                ],
                'feed_them_carousel_premium'        => [
                    'title'        => 'Feed Them Carousel Premium',
                    'plugin_url'   => 'feed-them-carousel-premium/feed-them-carousel-premium.php',
                    'demo_url'     => 'https://feedthemsocial.com/facebook-carousels-or-sliders/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-carousel-premium/',
                ],
                'feed_them_social_tiktok_premium' => [
                    'title'        => 'Feed Them Social TikTok Premium',
                    'load_class'   => 'Feed_Them_Social_TikTok_Premium',
                    'plugin_url'   => 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php',
                    'demo_url'     => 'https://feedthemsocial.com/tiktok-feed-demo/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-tiktok-premium/',
                ],
                'feed_them_social_instagram_slider' => [
                    'title'        => 'Feed Them Social Instagram Slider',
                    'load_class'   => 'Feed_Them_Social_Instagram_Slider',
                    'plugin_url'   => 'feed-them-social-instagram-slider/feed-them-social-instagram-slider.php',
                    'demo_url'     => 'https://feedthemsocial.com/instagram-slider/',
                    'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social-instagram-slider/',
                ],
            ],
        ];

        foreach ( $fts_constants as $name => $value ) {
            if ( ! \defined( $name ) ) {
                \define( $name, $value );
            }
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
