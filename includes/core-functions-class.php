<?php
/**
 * Core Functions Class
 *
 * This class has some of the core functions of Feed Them Social
 *
 * @class    Core_Functions
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

/**
 * Class Core_Functions
 */
class Core_Functions {

	/**
	 * $output.
	 *
	 * @var string
	 */
	public $output = '';

	/**
	 * $feeds_core.
	 *
	 * @var string
	 */
	public $feeds_core = '';

	/**
	 * Global Prefix
	 * Sets Prefix for global options
	 *
	 * @var string
	 */
	public $global_prefix = 'global_';

	/**
	 * Core_Functions constructor.
	 */
	public function __construct() {}

	/**
	 * Load Function
	 *
	 * Load up all our actions and filters.
	 *
	 * @since 1.0.0
	 */
	public static function load() {
		$instance = new self();

		$instance->add_actions_filters();
	}

	/**
	 * Add Action Filters
	 *
	 * Load up all our styles and js.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {
		// Set Template Filters.
		add_filter( 'single_template', array( $this, 'fts_locate_template' ), 999 );
		add_filter( 'archive_template', array( $this, 'fts_locate_template' ), 999 );
		add_filter( 'taxonomy_template', array( $this, 'fts_locate_template' ), 999 );
		add_filter( 'page_template', array( $this, 'fts_locate_template' ), 999 );
		add_action( 'parse_query', array( $this, 'fts_cpt_request_redirect_fix' ) );

	}

	/**
	 *  Tab Notice HTML
	 *
	 * Creates notice html for return.
	 *
	 * @since 1.0.0
	 */
	public function fts_tab_notice_html() {
		echo '<div class="ft-gallery-notice"></div>';
	}


	/**
	 *  CPT Request Redirect Fix
	 *
	 * Paging does not work on single custom post type pages - always a redirect to page 1 by
	 * WP core hack see https://core.trac.wordpress.org/ticket/15551
	 *
	 * @param string $request The redirect.
	 * @return string
	 * @since 1.1.6
	 */
	public function fts_cpt_request_redirect_fix( $request ) {
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$cpts = get_post_types( $args, 'names', 'and' );

		if ( isset( $request->query_vars['post_type'] ) && in_array( $request->query_vars['post_type'], $cpts, true ) && true == $request->is_singular && - 1 === $request->current_post && true == $request->is_paged ) {
			add_filter( 'redirect_canonical', '__return_false' );
		}

		return $request;
	}

	/**
	 *  Required Plugins
	 *
	 * Return an array of required plugins.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function fts_required_plugins() {
		$required_premium_plugins = array(
			'feed_them_social_premium' => array(
				'title'        => 'Feed Them Social Premium',
				'plugin_url'   => 'feed_them_social-premium/feed_them_social-premium.php',
				'demo_url'     => 'https://feedthemgallery.com/',
				'purchase_url' => 'https://www.slickremix.com/downloads/feed-them-social/',
			),
		);

		return $required_premium_plugins;
	}

	/**
	 *  Required Plugins
	 *
	 * Return an array of required plugins.
	 *
	 * @param string $located Location of the template files.
	 * @return string
	 * @since 1.0.0
	 */
	public function fts_locate_template( $located ) {
		global $post;

		$post_type = isset( $post ) ? $post->post_type : '';

		$is_tags = isset( $_GET['ftg-tags'] ) ? sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) : null;

		if ( isset( $is_tags ) ) {
			// Set The Template name.
			$template_name = 'archive-ftg-tags.php';

			$use_template = true;
		} else {
			switch ( $post_type ) {

				case 'fts':
					// Set The Template name.
					$template_name = 'gallery-template.php';

					$use_template = true;
					break;
				case 'fts_albums':
					// Set The Template name.
					$template_name = 'album-template.php';

					$use_template = true;
					break;
				default:
					$use_template = false;
					break;
			}
		}

		if ( true == $use_template ) {
			// No file found yet.
			$located = false;
			// Continue if template is empty.
			if ( empty( $template_name ) ) {
				// Trim off any slashes from the template name.
				$template_name = ltrim( $template_name, '/' );
			}
			// Check child theme first.
			if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'ft-gallery/' . $template_name ) ) {
				$located = trailingslashit( get_stylesheet_directory() ) . 'ft-gallery/' . $template_name;
				// Check parent theme next.
			} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'ft-gallery/' . $template_name ) ) {
				$located = trailingslashit( get_template_directory() ) . 'ft-gallery/' . $template_name;
				// Check theme compatibility last.
			} elseif ( file_exists( trailingslashit( FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'templates/' . $template_name ) ) ) {
				$located = trailingslashit( FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'templates/' . $template_name );
			}
			// Use Plugins Album template.
			if ( empty( $located ) ) {

				$plugin_location = FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'templates/' . $template_name;

				return $plugin_location;
			}
		}

		if ( ! empty( $located ) ) {

			return $located;
		}
	}

}//end class
