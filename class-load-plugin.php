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
use feedthemsocial\Facebook_Feed;
use feedthemsocial\Metabox_Settings;
use feedthemsocial\Twitter_Feed;

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
		$settings_functions = new \feedthemsocial\Settings_Functions();

		// Feed Cache.
		$feed_cache = new \feedthemsocial\Feed_Cache( $data_protection, $settings_functions );

		// Feed Options.
		$feed_cpt_options = new \feedthemsocial\Feed_CPT_Options();

		// Feed Functions.
		$feed_functions = new \feedthemsocial\Feed_Functions( $options_functions, $feed_cpt_options, $feed_cache, $data_protection );

		// Settings Page.
        new \feedthemsocial\Settings_Page( $settings_functions, $feed_cache );

		// System Info.
		new \feedthemsocial\System_Info( $settings_functions, $feed_cache );

		// Setting Options JS.
		$setting_options_js = new \feedthemsocial\Settings_Options_JS();

		// Metabox Functions.
		$metabox_functions = new \feedthemsocial\Metabox_Functions( $feed_cpt_options->get_all_options(), $settings_functions, $options_functions, 'fts_feed_options_array' );

		// Access Options.
		$access_options = new \feedthemsocial\Access_Options( $feed_functions, $feed_cpt_options, $metabox_functions, $data_protection, $options_functions );

		// Feeds CPT.
        $feeds_cpt = new \feedthemsocial\Feeds_CPT( $feed_functions, $feed_cpt_options, $setting_options_js, $metabox_functions, $access_options, $options_functions );

		// CPT Shortcode Button for Admin page, posts and CPTs.
		new \feedthemsocial\Shortcode_Button();

		// Facebook Post Types.
		$facebook_post_types = new \feedthemsocial\Facebook_Feed_Post_Types( $feed_functions );

		// Facebook Feed.
		$facebook_feed = new \feedthemsocial\Facebook_Feed( $feed_functions, $feed_cache, $facebook_post_types );

		// Twitter Feed.
		$twitter_feed = new \feedthemsocial\Twitter_Feed( $feed_functions, $feed_cache );

		// Feed Display.
		new \feedthemsocial\Feed_Shortcode( $feed_functions, $options_functions, $facebook_feed, $twitter_feed );

        // Backwards compatability.
        new \feedthemsocial\Backwards_Compat( $settings_functions );

        // Upgrades
        new \feedthemsocial\FTS_Upgrades( $settings_functions );

		// Updater Init.
		new \feedthemsocial\updater_init();
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

		// Feed Them Social Post Type.
		if ( ! defined( 'FEED_THEM_SOCIAL_POST_TYPE' )  ) {
			define( 'FEED_THEM_SOCIAL_POST_TYPE', 'fts' );
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

		// Premium Plugin Directoy Path.
		if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && ! defined( 'FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR' ) ) {
			define( 'FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR', WP_PLUGIN_DIR . '/feed_them_social-premium/feed_them_social-premium.php' );
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

		// Setting Options Js.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/cpt-settings-options-js.php';

		// Facebook Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/facebook-access-token.php';

		// Instagram Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/instagram-access-token.php';

        // Instagram Business Access Token API.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/instagram-business-access-token.php';

		// Twitter Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/twitter-access-token.php';

		// Youtube Access Token API.
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

		// Youtube Additional Options
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/additional/youtube-cpt-additional-options.php';

		// Feeds CPT Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/feeds-cpt-class.php';

		// Facebook Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/facebook/class-facebook-feed.php';

		// Facebook Feed Post Types.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/facebook/class-facebook-feed-post-types.php';

        // Twitter OAuth.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/twitteroauth/twitteroauth.php';

		// Twitter Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/class-twitter-feed.php';

		// Feed Cache.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-cache.php';

		// Shortcode Button.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/shortcode-button/shortcode-button.php';

		// Include Shortcodes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-shortcode.php';

        // Backwards compatability.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/settings/backwards-compat/fts-backwards-compat-class.php';

        // Upgraders
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/settings/backwards-compat/fts-upgrade-class.php';

        // Updater Classes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-license-page.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-class.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-init.php';
	}
}