<?php
/**
 * Activate Plugin
 *
 * Class Feed Them Social Load Plugin Class.
 *
 * @class    Feed_Them_Social
 * @version  3.0.0
 * @package  FeedThemSocial/Core
 * @category Class
 * @author   SlickRemix
 */
namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Activate Plugin
 */
class Activate_Plugin {

	/**
	 * Activate Plugin Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		//Pre-Activate Plugin Checks.
		$this->pre_activate_plugin_checks();
	}

	/**
	 * Add Action Filters
	 *
	 * Load up all our styles and js.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {
		// Register Activate Transient
		register_activation_hook( __FILE__, array( $this, 'activate_transient' ) );

		// Display Install Notice Add Action.
		add_action( 'admin_notices', array( $this, 'display_install_notice' ) );

		// Display Update Notice Add Action.
		add_action( 'admin_notices', array( $this, 'display_update_notice' ) );

		// Upgrade Completed Add Action.
		add_action( 'upgrader_process_complete', array( $this, 'upgrade_completed' ), 10, 2 );

		// Add Support/Settings links on plugin install page.
		add_filter( 'plugin_action_links_' . FEED_THEM_SOCIAL_PLUGIN_BASENAME, array( $this, 'free_plugin_install_page_links' ), 10, 4 );

		// Add filters for feedback/Rate link on plugin install page.
		add_filter( 'plugin_row_meta', array( $this, 'leave_feedback_link' ), 10, 2 );

		// Plugin Activation Function.
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

		// Set Plugin Timezone.
		add_action( 'admin_init', array( $this, 'set_plugin_timezone' ) );

		// Review/Rating notice option names
		$review_transient = 'fts_slick_rating_notice_waiting';
		$review_option    = 'fts_slick_rating_notice';
		$review_nag       = 'fts_slick_ignore_rating_notice_nag';

		// Review Nag Check.
		$this->review_nag_check( $review_nag, $review_option, $review_transient );

		// Set Review Transient.
		$this->set_review_transient( $review_transient, $review_option );

		// Set Review Status.
		$this->set_review_status( $review_option, $review_transient );

	}

	/**
	 * Pre-Activate Plugin Checks
	 *
	 * Before plugin activates do checks. Deactivate plugin if checks fail to prevent taking down a site.
	 *
	 * @since 1.0.0
	 */
	public function pre_activate_plugin_checks() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		// See if the PHP Version Constant exists.
		if ( function_exists( 'phpversion' ) ) {
			$server_php_version = PHP_VERSION;
		}

		// Check the server set PHP version against the minimum required PHP version needed to run plugin.
		if ( $server_php_version >= FEED_THEM_SOCIAL_MIN_PHP ) {
			// Load Translation Languages because PHP version check passed.
			add_action( 'init', array( $this, 'load_translations_languages' ) );
		} else {
			deactivate_plugins( 'feed-them-social/feed-them-social.php' );
			if ( $server_php_version < FEED_THEM_SOCIAL_MIN_PHP ) {
				add_action( 'admin_notices', array( $this, 'failed_php_version_notice' ) );
			}
		}

		// Uncomment this to test. PHP Version check.
		//add_action( 'admin_notices', array( $this, 'failed_php_version_notice' ) );
	}

	/**
	 * Load Translation Languages
	 *
	 * Loads Translation language files.
	 *
	 * @since 1.0.0
	 */
	public function load_translations_languages() {
		// Localization. (Plugin string translations).
		// Needs FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR to make sure the path to languages folder is correct.
		load_plugin_textdomain( 'feed-them-social', false, FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . '/languages' );
	}

	/**
	 *  Failed PHP Version Notice
	 *
	 * Show notice because the version of PHP running on server doesn't meet the plugin minimum requirements to run properly.
	 *
	 * @since 1.0.0
	 */
	public function failed_php_version_notice() {
		echo sprintf(
			esc_html__( '%1$sWarning:%2$s Your server PHP version is %3$s. You need to be running at least %4$s or greater to use this plugin. Please upgrade the php by contacting your host provider.%5$s', 'feed-them-social' ),
			'<div class="error"><p><strong>',
			'</strong>',
			PHP_VERSION,
			FEED_THEM_SOCIAL_MIN_PHP,
			'</p></div>'
		);
	}

	/**
	 * Activate Transient
	 *
	 * Set transient for plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate_transient() {
		// Set Activation Transient.
		set_transient( 'fts_activated', 1 );
		// Set/Update FTS Version.
		update_option( 'fts_version', FEED_THEM_SOCIAL_VERSION );
	}

	/**
	 * Display Install Notice
	 *
	 * Show a notice for first time plugin install. This notice shouldn't display to anyone who has just updated this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_install_notice() {
		// Check the transient to see if we've just activated the plugin.
		if ( get_transient( 'fts_activated' ) ) {
			echo sprintf(
				esc_html__( '%1$sThanks for installing Feed Them Social. To get started please view the %2$sSettings%3$s page.%4$s', 'feed-them-social' ),
				'<div class="notice notice-success updated is-dismissible"><p>',
				'<a href="' . esc_url( 'edit.php?post_type=fts&page=fts-settings-page' ) . '">',
				'</a>',
				'</p></div>'
			);
			// Delete the transient so we don't keep displaying the activation message.
			delete_transient( 'fts_activated' );
		}
	}

	/**
	 * Display Update Notice
	 *
	 * Show notice for plugin updated. This notice shouldn't display for first time plugin install.
	 *
	 * @since 1.0.0
	 */
	public function display_update_notice() {
		// Check the transient to see if we've just updated the plugin.
		if ( get_transient( 'fts_updated' ) ) {
			echo sprintf(
				esc_html__( '%1$sThanks for updating Feed Them Social. The plugins cache has been cleared.%2$s', 'feed-them-social' ),
				'<div class="notice notice-success updated is-dismissible"><p>',
				'</p></div>'
			);
			delete_transient( 'fts_updated' );
		}
	}

	/**
	 * Upgrade Completed
	 *
	 * This function runs when WordPress completes its upgrade process. It iterates through each plugin updated to see if ours is included.
	 *
	 * @param array $upgrader_object Array The upgrader object.
	 * @param array $options Array The options.
	 * @since 1.0.0
	 */
	public function upgrade_completed( $upgrader_object, $options ) {
		// The path to our plugin's main file.
		$our_plugin = FEED_THEM_SOCIAL_PLUGIN_BASENAME;
		// If an update has taken place and the updated type is plugins and the plugins element exists.
		if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {
			// Iterate through the plugins being updated and check if ours is there.
			foreach ( $options['plugins'] as $plugin ) {
				if ( $plugin === $our_plugin ) {
					// Set a transient to record that our plugin has just been updated.
					set_transient( 'fts_updated', 1 );
				}
			}
		}
	}

	/**
	 * Free Plugin Install Page Links
	 *
	 * Loads links in the Plugins page in WordPress Dashboard
	 *
	 * @param array $install_page_links What action to take.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function free_plugin_install_page_links( $install_page_links ) {
		array_unshift(
			$install_page_links,
			'<a href="' . admin_url() . 'edit.php?post_type=fts">' . esc_html__( 'Feeds', 'feed-them-social' ) . '</a> | <a href="' . admin_url() . 'edit.php?post_type=fts&page=fts-settings-page">' . esc_html__( 'Settings',  'feed-them-social' ) . '</a> | <a target="_blank" href="' . esc_url( 'https://www.slickremix.com/support/' ) . '">' . esc_html__( 'Support',  'feed-them-social' ) . '</a>'
		);
		return $install_page_links;
	}

	/**
	 * Leave Feedback Link
	 *
	 * Link to add feedback and Rate plugin.
	 *
	 * @param string $links The link to show.
	 * @param string $file The file basename.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function leave_feedback_link( $links, $file ) {
		if ( FEED_THEM_SOCIAL_PLUGIN_BASENAME === $file ) {
			$links['feedback'] = sprintf(
				esc_html__( '%1$sRate Plugin%2$s', 'feed-them-social' ),
				'<a href="' . esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/' ) . '" target="_blank">',
				'</a>'
			);
		}
		return $links;
	}

	/**
	 *  Plugin Activation
	 *
	 * Add/Set option for Feed Them Social Activation.
	 *
	 * @since 1.0.0
	 */
	public function plugin_activation() {
		// we add an db option to check then delete the db option after activation and the cache has emptied.
		// the delete_option is on the feed-them-functions.php file at the bottom of the function ftg_clear_cache_script.
		add_option( 'Feed_Them_Social_Activated_Plugin', 'feed-them-social' );
	}

	/**
	 * Set Plugin TimeZone
	 *
	 * Set timezone options for activated plugin.
	 *
	 * @since 1.0.0
	 */
	public function set_plugin_timezone() {

		if ( is_admin() && 'feed-them-social' === get_option( 'Feed_Them_Social_Activated_Plugin' ) ) {

			// Activation Options.
			$activation_options = array(
				'ft-gallery-date-and-time-format' => 'one-day-ago',
				'ft-gallery-timezone'             => 'America/New_York',
			);

			foreach ( $activation_options as $option_key => $option_value ) {
				// We don't use update_option because we only want this to run for options that have not already been set by the user.
				add_option( $option_key, $option_value );
			}
		}
	}

	/**
	 * Review Nag Check
	 *
	 * Checks $_GET to see if the nag variable is set and what it's value is
	 *
	 * @param string $get See what the $_GET url is.
	 * @param string $review_nag See if we are nagging 1 or 0.
	 * @param string $review_option The option to check for.
	 * @param string $review_transient Check the transient exists or not.
	 * @since 1.0.8
	 */
	public function review_nag_check( $review_nag, $review_option, $review_transient ) {

		if ( isset( $_GET[ $review_nag ] ) ) {

			// Includes pluggable.php to ensure that current_user_can can be used.
			if ( ! function_exists( 'wp_get_current_user' ) ) {
				require_once ABSPATH . WPINC . '/pluggable.php';
			}

			if ( ! current_user_can( 'manage_options' ) || !isset( $_REQUEST['_wpnonce'] ) || false === wp_verify_nonce( $_REQUEST['_wpnonce'], 'ignore_rating_notice_nag' ) ) {
				wp_die(
					__( 'Missing capability', 'feed-them-social' ),
					__( 'Forbidden', 'feed-them-social' ),
					array(
						'response' => 403
					)
				);
			}

			if ( '1' === $_GET[ $review_nag ] ) {
				update_option( $review_option, 'dismissed' );
			} elseif ( 'later' === $_GET[ $review_nag ] ) {
				$time = 2 * WEEK_IN_SECONDS;
				set_transient( $review_transient, 'fts-review-waiting', $time );
				update_option( $review_option, 'pending' );
			}
		}
	}

	/**
	 * Set Review Transient
	 *
	 * Set a review transient if the notice has not been dismissed or has not been set yet.
	 *
	 * @param string $review_transient Check the transient exists or not.
	 * @param string $review_option The option to check for.
	 * @since 1.0.8
	 */
	public function set_review_transient( $review_transient, $review_option ) {
		$rating_notice_waiting = get_transient( $review_transient );
		$notice_status         = get_option( $review_option, false );

		if ( ! $rating_notice_waiting && ! ( 'dismissed' === $notice_status || 'pending' === $notice_status ) ) {
			$time = 2 * WEEK_IN_SECONDS;
			// Testing.
			$time = 2;
			set_transient( $review_transient, 'fts-review-waiting', $time );
			update_option( $review_option, 'pending' );
		}
	}

	/**
	 * Set Review Status
	 *
	 * Checks to see what the review status is.
	 *
	 * @param string $review_option The option to check for.
	 * @param string $review_transient Check the transient exists or not.
	 * @since 1.0.8
	 */
	public function set_review_status( $review_option, $review_transient ) {
		$get_notice_status = get_option( $review_option, false );
		// Only display the notice if the time offset has passed and the user hasn't already dismissed it!.
		if ( 'fts-review-waiting' !== get_transient( $review_transient ) && 'dismissed' !== $get_notice_status ) {
			add_action( 'admin_notices', array( $this, 'rating_notice_html' ) );
		}

		// Testing.
		/*echo $get_notice_status;
        echo ' ';
        print_r( get_transient( $review_transient ) );
        // Uncomment this for testing the notice.
         if ( !isset( $_GET['ftg_slick_ignore_rating_notice_nag'] ) ) {
          add_action( 'admin_notices', array($this, 'rating_notice_html') );
         }*/
	}

	/**
	 * Ratings Notice HTML
	 *
	 * Generates the html for the admin review/rating notice.
	 *
	 * @since 1.0.8
	 */
	public function rating_notice_html() {
		// Only show to admins.
		if ( current_user_can( 'manage_options' ) ) {
			global $current_user;
			$user_id = $current_user->ID;

			// Used for testing:
			// print_r( get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' ) );
			// Used for testing:
			// $all_meta_for_user = get_user_meta( $user_id );
			// Used for testing:
			// print_r( $all_meta_for_user );
			/* Has the user already clicked to ignore the message? */

			if ( ! get_user_meta( $user_id, 'fts_slick_ignore_rating_notice' )  && ! isset( $_GET['fts_slick_ignore_rating_notice_nag'] ) ) {

				$ignore_rating_notice_nag_nonce = wp_create_nonce( 'ignore_rating_notice_nag' );

				?>
				<div class="ftg_notice ftg_review_notice">
					<img src="<?php echo esc_url( plugins_url( 'feed-them-social/admin/images/feed-them-social-logo.png' ) ); ?>" alt="Feed Them Social">
					<div class='fts-notice-text'>
						<p><?php echo esc_html( 'It\'s great to see that you\'ve been using our Feed Them Social plugin for a while now. Hopefully you\'re happy with it!  If so, would you consider leaving a positive review? It really helps support the plugin and helps others discover it too!', 'feed-them-social' ); ?></p>
						<p class="fts-links">
							<a class="ftg_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/reviews/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Sure, I\'d love to', 'feed-them-social' ); ?></a>
							<a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag' => '1', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'I\'ve already given a review', 'feed-them-social' ); ?></a>
							<a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag' => 'later', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'Ask me later', 'feed-them-social' ); ?> </a>
							<a class="ftg_notice_dismiss" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/feed-them-social/#new-post' ); ?>" target="_blank"><?php echo esc_html__( 'Not working, I need support', 'feed-them-social' ); ?></a>
							<a class="ftg_notice_dismiss" href="<?php echo esc_url( add_query_arg( ['fts_slick_ignore_rating_notice_nag' => '1', '_wpnonce' => $ignore_rating_notice_nag_nonce] ) ); ?>"><?php echo esc_html__( 'No thanks', 'feed-them-social' ); ?></a>
						</p>

					</div>
				</div>

				<?php
			}
		}
	}
}