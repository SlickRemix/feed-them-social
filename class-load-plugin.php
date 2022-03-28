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

		$main_post_type = 'fts';

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

		// Settings Functions.
		$settings_functions = new \feedthemsocial\Settings_Functions();

		// Feed Cache.
		$feed_cache = new \feedthemsocial\Feed_Cache( $data_protection, $settings_functions );

        // Feed Them Functions!
        $feed_functions = new \feedthemsocial\FTS_Functions();

		// Settings Page.
        new \feedthemsocial\Settings_Page( $settings_functions, $feed_cache );

		// System Info.
		new \feedthemsocial\System_Info( $settings_functions, $feed_cache );

		// Access Token API.
        //$access_token_api = new \feedthemsocial\Access_Token_API();

		//Setting Options JS.
		$setting_options_js = new \feedthemsocial\Settings_Options_JS();

		// Feed Options.
		$feed_cpt_options = new \feedthemsocial\Feed_CPT_Options();

		// Feeds CPT.
        $feeds_cpt = new \feedthemsocial\Feeds_CPT( $feed_cpt_options, $main_post_type, $setting_options_js, $settings_functions  );

		// Shortcode Button for Admin page, posts and CPTs.
		new \feedthemsocial\Shortcode_Button();

		// Shortcodes.
		new \feedthemsocial\Shortcodes( $main_post_type, $feed_functions, $feeds_cpt, $feed_cache );

        // Backwards compatability.
        new \feedthemsocial\FTS_Backwards_Compat( $settings_functions );

        // Upgrades.
        new \feedthemsocial\FTS_Upgrades();

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

		// Minimum PHP Version for Feed Them Social.
		if ( ! defined( 'FEED_THEM_SOCIAL_MIN_PHP' ) && $minimum_required_PHP_version ) {
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

		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'metabox-settings/metabox-settings-class.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/settings/settings-page.php';
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/settings/settings-functions.php';

		// FTS Functions Class.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-them-functions.php';

		//Setting Options Js.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/options/cpt-settings-options-js.php';

		//Facebook Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/facebook-access-token.php';

		//Instagram Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/instagram-access-token.php';

		//Twitter Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/twitter-access-token.php';

		//Youtube Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/single/youtube-access-token.php';

		//Access Token API.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/cpt/access-tokens/access-token-api-options.php';

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

        // Twitter OAuth.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/twitteroauth/twitteroauth.php';

		// Twitter Feed.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feeds/twitter/class-fts-twitter-feed.php';

		// Feed Cache
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/feed-cache.php';

		// Shortcode Button.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'includes/shortcode-button/shortcode-button.php';

		// Include Shortcodes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/shortcodes.php';

        // Backwards compatability.
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/cpt/options/backwards-compat/fts-backwards-compat-class.php';

        // Upgraders
        include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/admin/upgrades/fts-upgrade-class.php';

        // Updater Classes.
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-license-page.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-class.php';
		include FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'updater/updater-check-init.php';
	}
}