<?php namespace feedthemsocial;

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
		$this->add_actions_filters();

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Set Feeds CPT object.
		$this->feeds_cpt = $feeds_cpt;

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;
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
     * FTS ShortCode Filter
     *
     * Chooses which feed function to use.
     *
     * @since 3.0.0
     */
    public function fts_shortcode_filter( $inputted_atts ){

	    $cpt_id = $this->cpt_check($inputted_atts);


    	//Check the CPT ID exists in Shortcode
    	if ($cpt_id){
			new FTS_Functions();
    		$this->get_feed_type($cpt_id);

		    // Twitter Feed.
		    $twitter_feed = new FTS_Twitter_Feed( $this->feed_functions, $this->feeds_cpt, $this->feed_cache );
		    echo $twitter_feed->display_twitter( $inputted_atts );

		    echo print_r($inputted_atts);
	    }
    }


	/**
	 * Get Feed Type
	 *
	 * Get the feed type from option set in the CPT.
	 *
	 * @param $cpt_id string
	 */
	public function get_feed_type( string $cpt_id ){
		$cpt_post = get_post( $cpt_id ) ;



		//$this->feed_functions->

		//if( $cpt_post && isset($cpt_post['feed_type']) && !empty($cpt_post['feed_type'])){
		//	return $cpt_post['feed_type'];
		//}
		//return false;
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
	public function cpt_check( array $inputted_atts ) {

		if ( is_array( $inputted_atts ) && isset( $inputted_atts['cpt_id'] ) && ! empty( $inputted_atts['cpt_id'] ) ) {
			return $inputted_atts['cpt_id'];
		}

		return false;
	}
}
