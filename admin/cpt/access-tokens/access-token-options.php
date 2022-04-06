<?php
/**
 * Feed Them Social - Access_Options
 *
 * This is used to call all of the classes to retrieve Access Tokens from the social networks.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Access Options.
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Access_Options {

	/**
	 * Feed Functions
	 *
	 * The Feed Functions Class
	 *
	 * @var object
	 */
	public $feed_functions;

	/**
	 * Data Protection
	 *
	 * Data Protection Class for encryption.
	 *
	 * @var object
	 */
	public $data_protection;

	/**
	 * Construct
	 *
	 * Access Token Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct( $feed_functions, $data_protection ) {
		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Data Protection.
		$this->data_protection = $data_protection;
	}

	/**
	 * Call Access Tokens
	 *
	 * Create the Access Tokens
	 *
	 * @since 2.7.1
	 */
	public function get_access_token_options( $feed_type ) {
		if($feed_type){
			// Determine Feed Type. Call Class. Return Options.
			switch ($feed_type){
				// Facebook Access Options Class.
				case 'facebook-feed-type':
					// Facebook Access Options Class.

					$facebook_access_options = new Facebook_Access_Options( $this->feed_functions, $this->data_protection );
					// Load the options.
					$access_options = $facebook_access_options->access_options();
					break;
				// Instagram Access Options Class.
				case 'instagram-feed-type':
					// Instagram Access Options Class.
					$instagram_access_options = new Instagram_Access_Options( $this->feed_functions, $this->data_protection );
					// Load the options.
					$access_options = $instagram_access_options->access_options();
					break;
				// Twitter Access Options Class.
				case 'twitter-feed-type':
					// Twitter Access Options Class.
					$twitter_access_options = new Twitter_Access_Options( $this->feed_functions, $this->data_protection );
					// Load the options.
					$access_options = $twitter_access_options->access_options();
					break;
				// Youtube Access Options Class.
				case 'youtube-feed-type':
					// Youtube Access Options Class.
					$youtube_access_options = new Youtube_Access_Options( $this->feed_functions, $this->data_protection );
					// Load the options.
					$access_options = $youtube_access_options->access_options();
					break;
			}
			// Return Access Options.
			return $access_options;
		}
		// Didn't find any options.
		return esc_html__( 'Oop, No Access Token options have been found for this social network', 'feed_them_social' );
	}
}//end class