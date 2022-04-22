<?php namespace feedthemsocial;
/**
 * Shortcodes
 *
 * This class determines what feed needs to be displayed and serves it up.
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
 * Shortcodes for Feed Them Social
 */
class Shortcodes {

	public $feed_functions;

	public $feeds_cpt;

	public $feed_cache;

	/**
	 * Shortcodes constructor.
	 */
	public function __construct( $feed_functions, $feeds_cpt, $feed_cache ){
		// Add Actions and filters.
		$this->add_actions_filters();

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Set Feeds CPT object.
		$this->feeds_cpt = $feeds_cpt;

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;

		$this->twitter_feed = new FTS_Twitter_Feed( $this->feed_functions, $this->feeds_cpt, $this->feed_cache );
	}

    /**
     * Register Frontend Styles and Scripts
     *
     * Adds the Actions and filters for the class.
     *
     * @since 3.0.0
     */
    public function add_actions_filters(){
		// Shortcode for FTS.
        add_shortcode( 'feed_them_social', array( $this, 'shortcode_filter' ) );

	    add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles_scripts' ) );
    }

	/**
	 * Add Actions & Filters
	 *
	 * Adds the Actions and filters for the class.
	 *
	 * @since 3.0.0
	 */
	public function register_frontend_styles_scripts(){
		wp_register_style( 'FTS-Feed-Styles', plugins_url( 'feed-them-social/includes/feeds/css/styles.css' ), false, FTS_CURRENT_VERSION );

		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			wp_register_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			wp_register_script( 'fts-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
		}
		// masonry snippet in fts-global.
		wp_register_script( 'FTS-Global-JS', plugins_url( 'feed-them-social/includes/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
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
        // Used for testing.
        //echo get_the_title( $post->ID );

        // Get args for fts custom post type.
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'fts',
            'post_status'    => 'publish',
            'suppress_filters' => true
        );

        $posts_array = get_posts( $args );

        foreach($posts_array as $post_array) {
            $shortcode_location_id = $post->ID;
            update_post_meta( $cpt_id, 'fts_shortcode_location', $shortcode_location_id );
        }
    }

    /**
     * ShortCode Filter
     *
     * Chooses which feed function to use.
     *
     * @since 3.0.0
     */
    public function shortcode_filter( $inputted_atts ){

		// Check CPT ID exists.
	    $cpt_id = $this->cpt_id_exists($inputted_atts);

		// If CPT ID exists begin feed filtering.
		if( $cpt_id ){
			// Get Feed Type.
			$feed_type = $this->feed_functions->get_feed_type( $cpt_id );

			// Shortcode Location.
			$this->shortcode_location( $cpt_id );

			//Check the CPT ID exists in Shortcode
			if ($cpt_id && $feed_type){
				//Filter by Feed Type
				switch ( $feed_type ){
					case 'facebook-feed-type':
						break;
					case 'instagram-feed-type':
						break;
					case 'instagram-business-feed-type':
						break;
					// Twitter Feed.
					case 'twitter-feed-type':
						//Load Scripts and Styles.
						wp_enqueue_style('FTS-Feed-Styles');
						wp_enqueue_script('FTS-Global-JS');

						//Display the Feed!
						echo $this->twitter_feed->display_twitter( $inputted_atts );
						break;
					case 'youtube-feed-type':
						break;
					case 'combine-streams-feed-type':
						break;
				}
			}
		}

    }

	/**
	 * CPT ID Exists
	 *
	 * Check to see if CPT ID attribute exists.
	 *
	 * @param array $inputted_atts
	 * @return array|bool
	 * @since 3.0.0
	 */
	public function cpt_id_exists( $inputted_atts ) {

		if ( is_array( $inputted_atts ) && isset( $inputted_atts['cpt_id'] ) && ! empty( $inputted_atts['cpt_id'] ) ) {
			return $inputted_atts['cpt_id'];
		}

		return false;
	}
}
