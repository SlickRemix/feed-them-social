<?php
/**
 * Feed Them Social - Access_Options
 *
 * This is used to call all of the classes that create permissions cl
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class Twitter_API_Token
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Access_Options {

	/**
	 * Facebook Access Options
	 *
	 */
	public $facebook_access_options;

	/**
	 * Instagram Access Options
	 *
	 */
	public $instagram_access_options;

	/**
	 * Twitter Access Options
	 *
	 */
	public $twitter_access_options;

	/**
	 * Youtube Access Options
	 *
	 */
	public $youtube_access_options;


	/**
	 * Construct
	 *
	 * Access Token Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {}

	/**
	 * Call Access Tokens
	 *
	 * Create the Access Tokens
	 *
	 * @since 2.7.1
	 */
	public function access_option_classes() {
		// Facebook Access Options Class.
		$this->facebook_access_options = new Facebook_Access_Options();
		// Instagram Access Options Class.
		$this->instagram_access_options = new Instagram_Access_Options();
		// Twitter Access Options Class.
        $this->twitter_access_options = new Twitter_Access_Options();
		// Youtube Access Options Class.
		$this->youtube_access_options = new Youtube_Access_Options();
	}
}//end class