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

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feed Them Social Class
 */
class Feed_Them_Social {

	/* Construct
	*
	* Access Token Options Page constructor.
	*
	* @since 1.9.6
	*/
	public function __construct(  ) {
		// Load the Plugin, Hooray!
		$this->load_plugin();
	}

	/**
	 * Load Plugin
	 *
	 * Service function to load up plugin.
	 *
	 * @since 1.0.0
	 */
	private function load_plugin() {

		$minimum_required_PHP_version = '7.0.0';

		// Setup Constants for Feed Them Social.
		$this->setup_constants( $minimum_required_PHP_version );

		// Activate Plugin Class. (Load this before $this->includes() for pre-activation plugin checks.)
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'activate-plugin.php';

		// Activate Plugin Functions. (pre-activation plugin checks included in construct).
		$activate_plugin = new \feedthemsocial\Activate_Plugin();

		// Include the Plugin files.
		$this->includes();

		// Add Actions and Filters.
		$activate_plugin->add_actions_filters();

		// Data Protection.
		$data_protection = new feedthemsocial\Data_Protection();

		// Options Functions.
		$options_functions = new feedthemsocial\Options_Functions( FEED_THEM_SOCIAL_POST_TYPE );

		// Settings Functions.
		$settings_functions = new feedthemsocial\Settings_Functions();

		// Feed Cache.
		$feed_cache = new feedthemsocial\Feed_Cache( $data_protection, $settings_functions );

		// Facebook Additional Options.
		$facebook_additional_options = new feedthemsocial\Facebook_Additional_Options();

		// Instagram Additional Options.
		$instagram_additional_options = new feedthemsocial\Instagram_Additional_Options();

		// Twitter Additional Options.
		$twitter_additional_options = new feedthemsocial\Twitter_Additional_Options();

		// YouTube Additional Options.
		$youtube_additional_options = new feedthemsocial\Youtube_Additional_Options();

		// Feed Options.
		$feed_cpt_options = new feedthemsocial\Feed_CPT_Options( $facebook_additional_options, $instagram_additional_options, $twitter_additional_options, $youtube_additional_options );

		// Feed Functions.
		$feed_functions = new feedthemsocial\Feed_Functions( $settings_functions, $options_functions, $feed_cpt_options, $feed_cache, $data_protection );

		// Settings Page.
        new feedthemsocial\Settings_Page( $settings_functions, $feed_cache );

		// System Info.
		$system_info = new feedthemsocial\System_Info( $settings_functions, $feed_cache );

		// Feed Options Import/Export.
		new feedthemsocial\Feed_Options_Import_Export( $feed_functions, $data_protection, $system_info );

		// Setting Options JS.
		$setting_options_js = new feedthemsocial\Settings_Options_JS();

		// Metabox Functions.
		$metabox_functions = new feedthemsocial\Metabox_Functions( $feed_functions, $feed_cpt_options->get_all_options(true), $settings_functions, $options_functions, FEED_THEM_SOCIAL_OPTION_ARRAY_NAME, $data_protection );

		// Access Options.
		$access_options = new feedthemsocial\Access_Options( $feed_functions, $feed_cpt_options, $metabox_functions, $data_protection, $options_functions );

		// Feeds CPT.
        $feeds_cpt = new feedthemsocial\Feeds_CPT( $settings_functions, $feed_functions, $feed_cpt_options, $setting_options_js, $metabox_functions, $access_options, $options_functions );

		// Facebook Post Types.
		$facebook_post_types = new feedthemsocial\Facebook_Feed_Post_Types( $feed_functions, $settings_functions, $access_options );

		// Facebook Feed.
		$facebook_feed = new feedthemsocial\Facebook_Feed( $settings_functions, $feed_functions, $feed_cache, $facebook_post_types, $access_options );

		// Instagram Feed.
		$instagram_feed = new feedthemsocial\Instagram_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );

		// Twitter Feed.
		// $twitter_feed = new feedthemsocial\Twitter_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );

        // Twitter Feed.
        $tiktok_feed = new feedthemsocial\Tiktok_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );

        // YouTube Feed.
		$youtube_feed = new feedthemsocial\Youtube_Feed( $settings_functions, $feed_functions, $feed_cache, $access_options );

		// Check if Extension is active if so call class.
		if( $feed_functions->is_extension_active( 'feed_them_social_combined_streams' ) ) {
			// Display Combined Streams Feed.
			$combined_streams = new \feed_them_social_combined_streams\Combined_Streams_Feed( $feed_functions, $feed_cache, $access_options, $facebook_feed, $facebook_post_types, $instagram_feed, $tiktok_feed, $youtube_feed );
		}
        else {
            $combined_streams = '';
        }
			// Feed Display.
		new feedthemsocial\Feed_Shortcode( $settings_functions, $feed_functions, $options_functions, $facebook_feed, $instagram_feed, $tiktok_feed, $youtube_feed, $combined_streams );

        // Shorten words in Posts.
        new FeedThemSocialTruncateHTML();

        // Backwards compatability.
        new feedthemsocial\Backwards_Compat( $settings_functions );

        // Upgrades
        new feedthemsocial\FTS_Upgrades( $settings_functions );

		// Updater Init.
		new feedthemsocial\updater_init( $feed_functions );

		// Block Init
		new feedthemsocial\BlockLoader();

        // Cron Jobs
        new feedthemsocial\Cron_Jobs( $feed_functions, $options_functions );
	}

	/**
	 * Load Extensions
	 *
	 * Service function to load up extensions.
	 *
	 * @since 1.0.0
	 */
	private function load_extensions() {
		// Load Extension's Classes needed.
		foreach (FEED_THEM_SOCIAL_PREM_EXTENSION_LIST as $extension){
			if( $extension ){
				// Is Extension Active?
				if ( is_plugin_active( $extension['plugin_url'] && $extension['load_class'] ) ) {

				}
			}
		}
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
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		// This is the URL the Updater / License Key Validation pings.
		if (!defined('SLICKREMIX_STORE_URL')) {
			define('SLICKREMIX_STORE_URL', 'https://www.slickremix.com/');
		}

		// Feed Them Social Post Type.
		if ( ! defined( 'FEED_THEM_SOCIAL_POST_TYPE' )  ) {
			define( 'FEED_THEM_SOCIAL_POST_TYPE', 'fts' );
		}

		// Feed Them Social Option Array Name. Used to set the option name in the database.
		if ( ! defined( 'FEED_THEM_SOCIAL_OPTION_ARRAY_NAME' )  ) {
			define( 'FEED_THEM_SOCIAL_OPTION_ARRAY_NAME', 'fts_feed_options_array' );
		}

		// Minimum PHP Version for Feed Them Social.
		if ( ! defined( 'FEED_THEM_SOCIAL_MIN_PHP' ) ) {
			define( 'FEED_THEM_SOCIAL_MIN_PHP', $minimum_required_PHP_version );
		}

		// Plugin Basename.
		if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_BASENAME' ) ) {
			define( 'FEED_THEM_SOCIAL_PLUGIN_BASENAME', 'feed-them-social/feed-them-social.php' );
		}

		// Plugins Absolute Path. (Needs to be after BASENAME constant to work).
		if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_ABS_PATH' ) ) {
			define( 'FEED_THEM_SOCIAL_PLUGIN_ABS_PATH', plugin_dir_path( __DIR__ ) . FEED_THEM_SOCIAL_PLUGIN_BASENAME );
		}

		// Plugin version. (Needs to be after BASENAME and ABS_PATH constants to work).
		if ( ! defined( 'FEED_THEM_SOCIAL_VERSION' ) ) {
			$plugin_data    = get_plugin_data( FEED_THEM_SOCIAL_PLUGIN_ABS_PATH );
			$plugin_version = $plugin_data['Version'];

			define( 'FEED_THEM_SOCIAL_VERSION', $plugin_version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_PATH' ) ) {
			define( 'FEED_THEM_SOCIAL_PLUGIN_PATH', plugins_url() );
		}

		// Plugin Directory Path.
		if ( ! defined( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR' ) ) {
			define( 'FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Premium Extension List.
		if ( ! defined( 'FEED_THEM_SOCIAL_PREM_EXTENSION_LIST' ) ) {
			define( 'FEED_THEM_SOCIAL_PREM_EXTENSION_LIST', array(
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
			  )
			);
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
		// System Info
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/system-info.php';

		// Data Protection Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'data-protection/data-protection.php';

		// Data Protection Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/trim-words.php';

		// Options Functions Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'options/options-functions.php';

		// Metabox Functions Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'metabox/metabox-functions-class.php';

		// Settings Page.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/settings/settings-page.php';

		// Settings Functions.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/settings/settings-functions.php';

		// Feed Functions Class. (eventually replacing most of FTS Functions Class.)
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-functions.php';

		// Error Handler.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/error-handler.php';

		// Feed Options Import Export.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/feed-options-import-export.php';

		// Setting Options Js.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/cpt-settings-options-js.php';

		// Facebook Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/facebook-access-token.php';

		// Instagram Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/instagram-access-token.php';

        // Instagram Business Access Token API.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/instagram-business-access-token.php';

		// Twitter Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/tiktok-access-token.php';

		// YouTube Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/youtube-access-token.php';

		// Access Token Options.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/access-token-options.php';

		// Feeds CPT Options.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/feeds-cpt-options.php';

		// Facebook Additional Options
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/additional/facebook-cpt-additional-options.php';

		// Instagram Additional Options
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/additional/instagram-cpt-additional-options.php';

		// Twitter Additional Options
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/additional/twitter-cpt-additional-options.php';

		// YouTube Additional Options
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/additional/youtube-cpt-additional-options.php';

		// Feeds CPT Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/feeds-cpt-class.php';

		// Facebook Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/facebook/class-facebook-feed.php';

		// Facebook Feed Post Types.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/facebook/class-facebook-feed-post-types.php';

		// Instagram Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/instagram/class-instagram-feed.php';

        // Twitter OAuth. Turned off Because of Twitter API Changes.
        // include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/twitteroauth/twitteroauth.php';

		// Twitter Feed. Turned off Because of Twitter API Changes.
		// include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/class-twitter-feed.php';

        // TikTok Feed.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/tiktok/class-tiktok-feed.php';

		// YouTube Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/youtube/class-youtube-feed.php';

		// Feed Cache.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-cache.php';

		// Include Shortcodes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-shortcode.php';

        // Backwards compatability.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/settings/backwards-compat/fts-backwards-compat-class.php';

        // Upgrades
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/settings/backwards-compat/fts-upgrade-class.php';

        // Updater Classes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-license-page.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-class.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-init.php';

		// Feed Block
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'blocks/block-loader.php';

        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/cron-jobs/cron-jobs.php';

        // Beaver Builder Module
        if (class_exists('FLBuilder')) {
            include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/modules/beaver-builder/includes/module.php';
        }

        // Elementor Module
        if ( did_action( 'elementor/loaded' ) ) {
            include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/modules/elementor/includes/module.php';
        }


	}

}