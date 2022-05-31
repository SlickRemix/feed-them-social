<?php namespace feedthemsocial;
/**
 * Feed Shortcode
 *
 * This class determines what feed needs to be displayed and outputs it.
 *
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @author   SlickRemix
 */

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feed_Shortcode
 */
class Feed_Shortcode {

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $options_functions;


    public $feed_functions;

	/**
	 * Feed Display Constructor.
	 */
	public function __construct( $feed_functions, $options_functions, $facebook_feed, $twitter_feed ){
		// Add Actions and filters.
		$this->add_actions_filters();

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

        // Set Feed Functions object.
        $this->options_functions = $options_functions;

		// Facebook Feed.
		$this->facebook_feed = $facebook_feed;

		// Twitter Feed.
		$this->twitter_feed = $twitter_feed;
	}

    /**
     * Register Frontend Styles and Scripts
     *
     * Adds the Actions and filters for the class.
     *
     * @since 3.0.0
     */
    public function add_actions_filters(){
		// Add Shortcode Filter for displaying a feed.
        add_shortcode( 'feed_them_social', array( $this, 'display_feed_shortcode_filter' ) );

	    // Register Frontend Styles & Scripts
	    add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles_scripts' ) );
    }

	/**
	 * Register Frontend Styles & Scripts
	 *
	 * Registers Frontend Styles & Scripts for the Feeds.
	 *
	 * @since 3.0.0
	 */
	public function register_frontend_styles_scripts(){
		// Register Feed Styles.
		wp_register_style( 'FTS-Feed-Styles', plugins_url( 'feed-them-social/includes/feeds/css/styles.css' ), false, FTS_CURRENT_VERSION );

		// Register Premium Styles & Scripts.
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			// Register Masonry Script.
			wp_register_script( 'FTS-masonry-pkgd', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			// Register Images Loaded Script.
			wp_register_script( 'FTS-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
		}

		// Register Feed Them Carousel Scripts.
		if ( is_plugin_active( 'feed-them-social/feed-them.php' ) && is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			wp_enqueue_script( 'fts-feeds', plugins_url( 'feed-them-carousel-premium/feeds/js/jquery.cycle2.js' ), array(), FTS_CURRENT_VERSION, false );
		}

		// masonry snippet in fts-global.
		wp_register_script( 'FTS-Global-JS', plugins_url( 'feed-them-social/includes/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
	}

	/**
	 * Load Frontend Styles & Scripts
	 *
	 * Load Frontend Styles & Scripts ONLY on pages feed is on.
	 *
	 * @since 3.0.0
	 */
	public function load_frontend_styles_scripts(){
		// Feed Styles.
		wp_enqueue_style('FTS-Feed-Styles');
		//Feed Global JS.
		wp_enqueue_script('FTS-Global-JS');

		// Premium Feed Styles and Scripts.
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			// Masonry Script
			wp_enqueue_style('FTS-masonry-pkgd');
			// Images Loaded
			wp_enqueue_script('FTS-images-loaded');
		}
	}

	/**
     * Shortcode_location
     *
     * When a page containing a shortcode is viewed on the front end we
     * then update the post meta key fts_shortcode_location with the ID of the page.
     *
     * @since 3.0
     */
    public function shortcode_location( $cpt_id ) {
        if ( is_admin() ) {
            return;
        }

        global $post;
        $array_check = $this->feed_functions->get_feed_option( $cpt_id, 'fts_shortcode_location' );
        $array_check_decode = json_decode( $array_check );

        // Process: A user saves a shortcode to a page, when they view the page we update the fts_shortcode_location with the id.
        // the fts_shortcode_location option will contain an array of ids, that is all.
        // To see how we remove ids from the array if a user deletes the shortcode go to the
        // feeds-cpt-class.php and search for case 'shortcode_location':
        // that is where we will update the fts_shortcode_location to remove an ids not found. This happens when the user loads the wp-admin/edit.php?post_type=fts page
        if( !is_array( $array_check_decode ) ){
            $encoded = json_encode( array( $post->ID ) );
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'fts_shortcode_location', $encoded, true, $cpt_id );
        }
        elseif( is_array( $array_check_decode ) ) {
            if (  !in_array( $post->ID, $array_check_decode, true ) ){
                $add_id = array_merge( $array_check_decode, array( $post->ID ) );
                $encoded = json_encode( $add_id );
                $this->options_functions->update_single_option( 'fts_feed_options_array', 'fts_shortcode_location', $encoded, true, $cpt_id );
            }
        }
    }

	/**
	 * Shortcode Feed ID Exists
	 *
	 * Check to see Feed ID exists in shortcode
	 *
	 * @param array $atts
	 * @return array|bool
	 * @since 3.0.0
	 */
	public function shortcode_feed_id_exists( $atts ) {
		// Make sure attributes contain cpt_id which is the Feed's CPT Post ID.
		if ( is_array( $atts ) && isset( $atts['cpt_id'] ) && ! empty( $atts['cpt_id'] ) ) {
			return $atts['cpt_id'];
		}
		// If it doesn't exists return false.
		return false;
	}

	/**
	 * Display Feed
	 *
	 * Display the feed by Feed Type.
	 *
	 * @param array $feed_post_id Feed Post ID.
	 * @param array $feed_type Feed Type for a Feed.
	 * @since 3.0.0
	 */
	public function display_feed( $feed_post_id, $feed_type ) {
		if ( $feed_type ){
			//Filter by Feed Type
			switch ( $feed_type ){
				case 'facebook-feed-type':
					//Display the Feed!
					echo $this->facebook_feed->display_facebook( $feed_post_id );
					break;
				case 'instagram-feed-type':
					break;
				case 'instagram-business-feed-type':
					break;
				// Twitter Feed.
				case 'twitter-feed-type':
					//Display the Feed!
					echo $this->twitter_feed->display_twitter( $feed_post_id );
					break;
				case 'youtube-feed-type':
					break;
				case 'combine-streams-feed-type':
					break;
			}
		}
	}

    /**
     * Display Feed ShortCode Filter
     *
     * The Shortcode filter to display a Feed based on the Feed Post ID and Feed Post Type. Also Loads scripts for feed only
     * only on page feed is loading.
     *
     * @param array $atts Attributes from the shortcode.
     * @since 3.0.0
     */
    public function display_feed_shortcode_filter( $atts ){
		// Feed Post ID that exists in the shortcode.
	    $feed_post_id = $this->shortcode_feed_id_exists( $atts );

		// If Feed Post ID exists then outputting what is needed.
		if( $feed_post_id ){
			// Get Feed Type.
			$feed_type = $this->feed_functions->get_feed_type( $feed_post_id );

			// Load Frontend Styles & Scripts ONLY on Feed pages.
			$this->load_frontend_styles_scripts();

			// Shortcode Location.
			$this->shortcode_location( $feed_post_id );

			// Display Feed by the Feed Post ID and Feed Type.
			$this->display_feed( $feed_post_id, $feed_type);
		}
    }
}