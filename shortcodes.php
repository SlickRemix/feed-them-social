<?php namespace feedthemsocial;

/**
 * Shortcodes for Feed Them Social
 */
class Shortcodes {

	public $feed_functions;

	public $feeds_cpt;

	public $feed_cache;

	public $metabox_settings;

	/**
	 * Shortcodes constructor.
	 */
	public function __construct( $main_post_type, $feed_functions, $feeds_cpt, $feed_cache ){
		$this->add_actions_filters();

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Set Feeds CPT object.
		$this->feeds_cpt = $feeds_cpt;

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;

		// Set Metabox Settings object.
		$this->metabox_settings = $feeds_cpt->metabox_settings_class;

		// Main Post Type
		$this->metabox_settings = $feeds_cpt->metabox_settings_class;
	}

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 3.0.0
     */
    public function add_actions_filters(){
        add_shortcode( 'feed_them_social', array( $this, 'fts_shortcode_filter' ) );
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
     * FTS ShortCode Filter
     *
     * Chooses which feed function to use.
     *
     * @since 3.0.0
     */
    public function fts_shortcode_filter( $inputted_atts ){

	    $cpt_id = $this->cpt_check($inputted_atts);

	    $feed_type = $this->get_feed_type($cpt_id);

	    $twitter_feed = new FTS_Twitter_Feed( $this->feed_functions, $this->feeds_cpt, $this->feed_cache );
	    echo $twitter_feed->display_twitter( $inputted_atts );

	   $this->shortcode_location( $cpt_id );

    	//Check the CPT ID exists in Shortcode
    	if ($cpt_id){

    		//Filter by Feed Type
    		switch ( $feed_type ){
			    case 'facebook-feed-type':
				    break;
			    case 'instagram-feed-type':
				    break;
			    case 'twitter-feed-type':
				    // Twitter Feed.
				    break;
			    case 'youtube-feed-type':
				    break;
			    case 'combine-streams-feed-type':
			    	break;
		    }
	    }
    }


	/**
	 * Get Feed Type
	 *
	 * Get the feed type from option set in the CPT.
	 *
	 * @param $cpt_id string
	 */
	public function get_feed_type( $cpt_id ){
		// Get Saved Settings Array.
		$saved_settings = $this->metabox_settings->get_saved_settings_array( $cpt_id, 'fts');

		if( $saved_settings && isset($saved_settings['feed_type']) && !empty($saved_settings['feed_type'])){
			return $saved_settings['feed_type'];
		}
		return false;
	}


	/**
	 * Custom Post Type Check
	 *
	 * Check to see if CPT ID attribute exists.
	 *
	 * @param array $inputted_atts
	 * @return array|bool
	 * @since 3.0.0
	 */
	public function cpt_check( $inputted_atts ) {

		if ( is_array( $inputted_atts ) && isset( $inputted_atts['cpt_id'] ) && ! empty( $inputted_atts['cpt_id'] ) ) {
			return $inputted_atts['cpt_id'];
		}

		return false;
	}
}
