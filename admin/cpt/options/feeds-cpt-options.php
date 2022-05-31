<?php
/**
 * Gallery Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Feed_CPT_Options
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Feed_CPT_Options
 */
class Feed_CPT_Options {

	/**
	 * All Gallery Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Feed_CPT_Options constructor.
	 */
	public function __construct() { }

	/**
	 * All Gallery Options
	 *
	 * Function to return all Gallery options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_options() {
		$this->feed_type_options();
        $this->twitter_token_options();
        $this->facebook_token_options();
        $this->instagram_token_options();
        $this->instagram_business_token_options();
        $this->youtube_token_options();
		//$this->layout_options();
		$this->color_options();
		$this->facebook_options();
		$this->instagram_options();
		$this->twitter_options();
		$this->youtube_options();
		$this->combine_options();

		return $this->all_options;
	}

	/**
	 * Feed Type
	 *
	 * Options for the Feed Type
	 *
	 * @return mixed
	 * @since 3.0.0
	 */
	public function feed_type_options() {
		$this->all_options['feed_type_options'] = array(
			'section_attr_key'   => 'feed_type_',
			//'section_title'      => esc_html__( 'Click on Social Network', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',

			'main_options'       => array(

				// Gallery Type.
				array(
					'input_wrap_class' => 'ft-wp-gallery-type',
					'option_type'      => 'select',
					'label'            => trim(
						sprintf(
							esc_html__( 'Feed Type: ', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						)
					),
					'type'             => 'text',
                   /* 'instructional-text' => sprintf(
                        esc_html__( '1. Select the Social Network you want to create a feed for. %1$s2. Get your access token. %1$s3. Click on the Social Network tab to the left and set your options. %1$s%1$sNote: To Create another social feed click %2$sAdd New%3$s and follow the same 3 steps.', 'feed_them_social' ),
                        '<br/>',
                        '<a href="post-new.php?post_type=fts" >',
                        '</a>',
                    ),*/

                    'id'               => 'feed_type',
					'name'             => 'feed_type',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'Facebook', 'feed-them-social' ),
							'value' => 'facebook-feed-type',
						),
						array(
							'label' => esc_html__( 'Instagram', 'feed-them-social' ),
							'value' => 'instagram-feed-type',
						),
                        array(
                            'label' => esc_html__( 'Instagram Business', 'feed-them-social' ),
                            'value' => 'instagram-business-feed-type',
                        ),
						array(
							'label' => esc_html__( 'Twitter', 'feed-them-social' ),
							'value' => 'twitter-feed-type',
						),
						array(
							'label' => esc_html__( 'YouTube Feed', 'feed-them-social' ),
							'value' => 'youtube-feed-type',
						),
						array(
							'label' => esc_html__( 'Combine Streams', 'feed-them-social' ),
							'value' => 'combine-streams-feed-type',
						),
					),
				),

                array(
                    'input_wrap_class' => 'fts-shortcode-location',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Shortcode Location', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_shortcode_location',
                    'name'             => 'fts_shortcode_location',
                    'value'            => 'Not Set',
                ),

			),
		);

		return $this->all_options['feed_type_options'];
	} //END LAYOUT OPTIONS

    /**
     * Twitter Token Options
     *
     * Options for the Feed Type
     *
     * @return mixed
     * @since 3.0.0
     */
    public function twitter_token_options() {

        $oath_token         = isset( $_GET['oauth_token'] ) && ! empty( $_GET['oauth_token'] ) ? sanitize_text_field( wp_unslash( $_GET['oauth_token'] ) ) : '';
        $oauth_token_secret = isset( $_GET['oauth_token_secret'] ) && ! empty( $_GET['oauth_token_secret'] ) ? sanitize_text_field( wp_unslash( $_GET['oauth_token_secret'] ) ) : '';

        $this->all_options['twitter_token_options'] = array(
            'section_attr_key'   => 'twitter_token_',
            'section_title'      => esc_html__( 'Twitter Access Token', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_id' => 'fts-feed-type',
            'section_wrap_class' => 'fts-tab-content1-twitter fts-token-wrap',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form-twitter',
            'form_wrap_id'       => 'fts-fb-page-form-twitter',


            'main_options'       => array(

                array(
                    'input_wrap_class' => 'fts-twitter-custom-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_twitter_custom_access_token',
                    'name'             => 'fts_twitter_custom_access_token',
                    'placeholder'      => '',
                    'default_value'    => $oath_token,
                    'instructional-text' => sprintf(
                        esc_html__( '%1$s %2$s Click the button below to get an access token. This gives us read-only access to get your Twitter posts. Once you have your Access Tokens you will be able to create your feed.', 'feed_them_social' ),
                        '<strong>',
                        '</strong>'
                    ),
                ),
                array(
                    'input_wrap_class' => 'fts-twitter-custom-access-token-secret',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token Secret', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_twitter_custom_access_token_secret',
                    'name'             => 'fts_twitter_custom_access_token_secret',
                    'placeholder'      => '',
                    'default_value'    => $oauth_token_secret,
                ),

            ),
        );

        return $this->all_options['twitter_token_options'];
    } //END TWITTER TOKEN OPTIONS

    /**
     * Facebook Token Options
     *
     * Options for the Feed Type
     *
     * @return mixed
     * @since 3.0.0
     */
    public function facebook_token_options() {

        $this->all_options['facebook_token_options'] = array(
            'section_attr_key'   => 'facebook_token_',
            'section_title'      => esc_html__( 'Facebook Access Token', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_id' => 'fts-feed-type',
            'section_wrap_class' => 'fts-tab-content1-facebook fts-token-wrap',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form-facebook',
            'form_wrap_id'       => 'fts-fb-page-form-facebook',


            'main_options'       => array(

                array(
                    'input_wrap_class' => 'fts-facebook-custom-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Page ID', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_custom_api_token_user_id',
                    'name'             => 'fts_facebook_custom_api_token_user_id',
                    'placeholder'      => '',
                    'default_value'    => '',
                    'instructional-text' => sprintf(
                        esc_html__( 'This Facebook Access Token is for Business Pages, Photos and Videos only and is simply used to display the feed. You must be an admin of the business page to get your token. This will NOT work for personal profiles or groups.%3$sClick the button below to get an access token. This gives us read-only access to get your Facebook posts. Once you have your Access Token you will be able to create your feed. Please note, use of this plugin is subject to %1$sFacebook\'s Platform Terms%2$s%4$s', 'feed_them_social' ),
                        '<a href="https://developers.facebook.com/terms/" target="_blank">',
                        '</a>',
                        '<p>',
                        '</p>'
                    ),
                ),

                array(
                    'input_wrap_class' => 'fts-facebook-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_custom_api_token',
                    'name'             => 'fts_facebook_custom_api_token',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-facebook-custom-api-token-user-name',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'User Name ', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_custom_api_token_user_name',
                    'name'             => 'fts_facebook_custom_api_token_user_name',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),

            ),
        );

        return $this->all_options['facebook_token_options'];
    } //END FACEBOOK TOKEN OPTIONS

    /**
     * Instagram Token Options
     *
     * Options for the Feed Type
     *
     * @return mixed
     * @since 3.0.0
     */
    public function instagram_token_options() {

        $this->all_options['instagram_token_options'] = array(
            'section_attr_key'   => 'instagram_token_',
            'section_title'      => esc_html__( 'Instagram Basic Access Token', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_id' => 'fts-feed-type',
            'section_wrap_class' => 'fts-tab-content1-instagram fts-token-wrap',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form-instagram',
            'form_wrap_id'       => 'fts-fb-page-form-instagram',
            'main_options'       => array(
                array(
                    'input_wrap_class' => 'fts-instagram-custom-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Instagram ID', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_instagram_custom_id',
                    'name'             => 'fts_instagram_custom_id',
                    'placeholder'      => '',
                    'default_value'    => '',
                    'instructional-text' => sprintf(
                        esc_html__( 'Click the button below to get an access token. This gives us read-only access to get your Instagram posts. Once you have your Access Token you will be able to create your feed. Please note, use of this plugin is subject to %1$sFacebook\'s Platform Terms%2$s', 'feed_them_social' ),
                        '<a href="https://developers.facebook.com/terms/" target="_blank">',
                        '</a>'
                    ),
                ),
                array(
                    'input_wrap_class' => 'fts-instagram-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_instagram_custom_api_token',
                    'name'             => 'fts_instagram_custom_api_token',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-instagram-custom-api-token-user-name fts-success-class fts-exp-time-wrapper',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Refresh Expire Time', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_instagram_custom_api_token_expires_in',
                    'name'             => 'fts_instagram_custom_api_token_expires_in',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),

            ),
        );

        return $this->all_options['instagram_token_options'];
    } //END INSTAGRAM TOKEN OPTIONS

    /**
     * Instagram Business Token Options
     *
     * Options for the Feed Type
     *
     * @return mixed
     * @since 3.0.0
     */
    public function instagram_business_token_options() {

        $this->all_options['instagram_business_token_options'] = array(
            'section_attr_key'   => 'facebook_instagram_token_',
            'section_title'      => esc_html__( 'Instagram Business Access Token', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_id' => 'fts-feed-type',
            'section_wrap_class' => 'fts-tab-content1-facebook-instagram fts-token-wrap',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form-facebook-instagram',
            'form_wrap_id'       => 'fts-fb-page-form-facebook-instagram',


            'main_options'       => array(

                array(
                    'input_wrap_class' => 'fts-facebook-instagram-custom-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Page ID', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_instagram_custom_api_token_user_id',
                    'name'             => 'fts_facebook_instagram_custom_api_token_user_id',
                    'placeholder'      => '',
                    'default_value'    => '',
                    'instructional-text' => sprintf(
                        esc_html__( 'Click the button below to get an access token. This gives us read-only access to get your Instagram posts. Your Instagram must be linked to a Facebook Business Page. Once you have your Access Token you will be able to create your feed. %1$sRead Instructions%2$s. Please note, use of this plugin is subject to %3$sFacebook\'s Platform Terms%4$s', 'feed_them_social' ),
                        '<a target="_blank" href="https://www.slickremix.com/docs/link-instagram-account-to-facebook/">',
                        '</a>',
                        '<a href="https://developers.facebook.com/terms/" target="_blank">',
                        '</a>'
                    ),
                ),
                array(
                    'input_wrap_class' => 'fts-facebook-instagram-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_instagram_custom_api_token',
                    'name'             => 'fts_facebook_instagram_custom_api_token',
                    'data_token'       => '',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-facebook-instagram-custom-api-token-user-name',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Instagram Name ', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_instagram_custom_api_token_user_name',
                    'name'             => 'fts_facebook_instagram_custom_api_token_user_name',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-facebook-instagram-custom-api-token-fb-user-name',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Facebook Name ', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'fts_facebook_instagram_custom_api_token_fb_user_name',
                    'name'             => 'fts_facebook_instagram_custom_api_token_fb_user_name',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),

            ),
        );

        return $this->all_options['instagram_business_token_options'];
    } //END INSTAGRAM BUSINESS TOKEN OPTIONS


    /**
     * Youtube Token Options
     *
     * Options for the Feed Type
     *
     * @return mixed
     * @since 3.0.0
     */
    public function youtube_token_options() {

        $this->all_options['youtube_token_options'] = array(
            'section_attr_key'   => 'youtube_token_',
            'section_title'      => esc_html__( 'YouTube Access Token', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_id'    => 'fts-feed-type',
            'section_wrap_class' => 'fts-tab-content1-youtube fts-token-wrap',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form-youtube',
            'form_wrap_id'       => 'fts-fb-page-form-youtube',


            'main_options'       => array(

                array(
                    'input_wrap_class' => 'fts-youtube-add-all-keys-click-option',
                    'option_type'      => 'input',
                    'label' => sprintf(
                        esc_html__( '%1$sAPI Key%2$s', 'feed_them_social' ),
                        '<a href="https://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank">',
                        '</a>'
                    ),
                    'type'             => 'text',
                    'id'               => 'youtube_custom_api_token',
                    'name'             => 'youtube_custom_api_token',
                    'placeholder'      => '',
                    'default_value'    => '',
                    'instructional-text' => sprintf(
                            esc_html__( 'Click the button below to get an access token. This gives us read-only access to get your YouTube videos. You can also %1$sadd your own API Key%2$s. Once you have your Access Tokens or you have entered an API key, you will be able to create your feed.', 'feed_them_social' ),
                        '<span>',
                        '</span>'
                    ),

                ),

                array(
                    'input_wrap_class' => 'fts-youtube-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'youtube_custom_access_token',
                    'name'             => 'youtube_custom_access_token',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-youtube-refresh-access-token',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Refresh Token', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'youtube_custom_refresh_token',
                    'name'             => 'youtube_custom_refresh_token',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),
                array(
                    'input_wrap_class' => 'fts-success-class fts-exp-time-wrapper',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Access Token Expire Time', 'feed_them_social' ),
                    'type'             => 'text',
                    'id'               => 'youtube_custom_token_exp_time',
                    'name'             => 'youtube_custom_token_exp_time',
                    'placeholder'      => '',
                    'default_value'    => '',
                ),

            ),
        );

        return $this->all_options['youtube_token_options'];
    } //END YOUTUBE TOKEN OPTIONS
    
	/**
	 * Layout Options
	 *
	 * Options for the Layout Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function layout_options() {
		$this->all_options['layout'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => esc_html( 'Facebook Options', 'feed_them_social' ) . '<span class="fts-valid-text"></span>',
			'section_wrap_class' => 'fts-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			// Token Check // We'll use these option for premium messages in the future.
			'premium_msg_boxes'  => array(
				'album_videos' => array(
					'req_plugin' => 'fts_premium',
					'msg'        => '',
				),
				'reviews'      => array(
					'req_plugin' => 'facebook_reviews',
					'msg'        => '',
				),
			),

			'main_options'       => array(

				// Gallery Type.
				array(
					'input_wrap_class' => 'ft-wp-gallery-type',
					'option_type'      => 'select',
					'label'            => trim(
						sprintf(
							esc_html__( 'Choose the gallery type%1$s View all Gallery %2$sDemos%3$s', 'feed_them_social' ),
							'<br/><small>',
							'<a href="' . esc_url( 'https://feedthemgallery.com/gallery-demo-one/' ) . '" target="_blank">',
							'</a></small>'
						)
					),
					'type'             => 'text',
					'id'               => 'fts_type',
					'name'             => 'fts_type',
					'default_value'    => 'gallery',
					'options'          => array(
						array(
							'label' => esc_html__( 'Responsive Image Gallery ', 'feed_them_social' ),
							'value' => 'gallery',
						),
						array(
							'label' => esc_html__( 'Image Gallery Collage (Masonry)', 'feed_them_social' ),
							'value' => 'gallery-collage',
						),
						array(
							'label' => esc_html__( 'Image Post', 'feed_them_social' ),
							'value' => 'post',
						),
						array(
							'label' => esc_html__( 'Image Post in Grid (Masonry)', 'feed_them_social' ),
							'value' => 'post-in-grid',
						),
					),
				),
				// Show Photo Caption.
				array(
					'input_wrap_class' => 'fb-page-description-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Photo Caption', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_photo_caption',
					'name'             => 'fts_photo_caption',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'Title and Description', 'feed_them_social' ),
							'value' => 'title_description',
						),
						array(
							'label' => esc_html__( 'Title', 'feed_them_social' ),
							'value' => 'title',
						),
						array(
							'label' => esc_html__( 'Description', 'feed_them_social' ),
							'value' => 'description',
						),
						array(
							'label' => esc_html__( 'None', 'feed_them_social' ),
							'value' => 'none',
						),
					),
				),

				// Photo Caption Placement.
				array(
					'input_wrap_class' => 'ftg-page-title-description-placement-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Photo Caption Placement', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_photo_caption_placement',
					'name'             => 'fts_photo_caption_placement',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'Caption Above Photo', 'feed_them_social' ),
							'value' => 'show_top',
						),
						array(
							'label' => esc_html__( 'Caption Below Photo', 'feed_them_social' ),
							'value' => 'show_bottom',
						),
					),
				),

				// ******************************************
				// Facebook Grid Options
				// ******************************************
				// Facebook Page Display Posts in Grid
				// array(
				// 'grouped_options_title' => __('Grid', 'feed_them_social'),
				// 'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
				// 'option_type' => 'select',
				// 'label' => __('Display Posts in Grid', 'feed_them_social'),
				// 'type' => 'text',
				// 'id' => 'fts_grid_option',
				// 'name' => 'fts_grid_option',
				// 'default_value' => 'no',
				// 'options' => array(
				// array(
				// 'label' => __('No', 'feed_them_social'),
				// 'value' => 'no',
				// ),
				// array(
				// 'label' => __('Yes', 'feed_them_social'),
				// 'value' => 'yes',
				// ),
				// ),
				// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
				// 'sub_options' => array(
				// 'sub_options_wrap_class' => 'main-grid-options-wrap',
				// ),
				// ),
				array(
					'input_wrap_class'   => 'fb-page-columns-option-hide',
					'option_type'        => 'select',
					'label'              => esc_html__( 'Number of Columns', 'feed_them_social' ),
					'type'               => 'text',
					'instructional-text' => sprintf(
						esc_html__( '%1$sNOTE:%2$s Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
						'<strong>',
						'</strong>'
					),
					'id'                 => 'fts_columns',
					'name'               => 'fts_columns',
					'default_value'      => '4',
					'options'            => array(
						array(
							'label' => esc_html__( '1', 'feed_them_social' ),
							'value' => '1',
						),
						array(
							'label' => esc_html__( '2', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5', 'feed_them_social' ),
							'value' => '5',
						),
						array(
							'label' => esc_html__( '6', 'feed_them_social' ),
							'value' => '6',
						),
						array(
							'label' => esc_html__( '7', 'feed_them_social' ),
							'value' => '7',
						),
						array(
							'label' => esc_html__( '8', 'feed_them_social' ),
							'value' => '8',
						),
					),
				),
				array(
					'input_wrap_class'   => 'ftg-masonry-columns-option-hide',
					'option_type'        => 'select',
					'label'              => esc_html__( 'Number of Columns', 'feed_them_social' ),
					'type'               => 'text',
					'instructional-text' => sprintf(
						esc_html__( '%1$sNOTE:%2$s Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
						'<strong>',
						'</strong>'
					),
					'id'                 => 'fts_columns_masonry2',
					'name'               => 'fts_columns_masonry2',
					'default_value'      => '3',
					'options'            => array(
						array(
							'label' => esc_html__( '2', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5', 'feed_them_social' ),
							'value' => '5',
						),
					),
				),
				array(
					'input_wrap_class' => 'ftg-masonry-columns-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Space between Images', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_columns_masonry_margin',
					'name'             => 'fts_columns_masonry_margin',
					'default_value'    => '5',
					'options'          => array(
						array(
							'label' => esc_html__( '1px', 'feed_them_social' ),
							'value' => '1',
						),
						array(
							'label' => esc_html__( '2px', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3px', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4px', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5px', 'feed_them_social' ),
							'value' => '5',
						),
						array(
							'label' => esc_html__( '10px', 'feed_them_social' ),
							'value' => '10',
						),
						array(
							'label' => esc_html__( '15px', 'feed_them_social' ),
							'value' => '15',
						),
						array(
							'label' => esc_html__( '20px', 'feed_them_social' ),
							'value' => '20',
						),
					),
				),
				array(
					'input_wrap_class' => 'fb-page-columns-option-hide',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Force Columns%1$s Yes, will force image columns. No, will allow the images to be resposive for smaller devices%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_force_columns',
					'name'             => 'fts_force_columns',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),

					),
				),
				// Grid Column Width
				// array(
				// 'input_wrap_class' => 'fb-page-grid-option-hide fb-page-columns-option-hide ftg-hide-for-columns',
				// 'option_type' => 'input',
				// 'label' => __('Grid Column Width', 'feed_them_social'),
				// 'type' => 'text',
				// 'id' => 'fts_grid_column_width',
				// 'name' => 'fts_grid_column_width',
				// 'instructional-text' =>
				// sprintf(__('%1$sNOTE:%2$s Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed_them_social'),
				// '<strong>',
				// '</strong>'
				// ),
				// 'placeholder' => '310px ' . __('for example', 'feed_them_social'),
				// 'default_value' => '310px',
				// 'value' => '',
					   // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					   // 'sub_options' => array(
					   // 'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',
					   // ),
				// ),
				   // Grid Spaces Between Posts.
				   array(
					   'input_wrap_class' => 'fb-page-grid-option-hide fb-page-grid-option-border-bottom',
					   'option_type'      => 'input',
					   'label'            => esc_html__( 'Space between Images', 'feed_them_social' ),
					   'type'             => 'text',
					   'id'               => 'fts_grid_space_between_posts',
					   'name'             => 'fts_grid_space_between_posts',
					   'placeholder'      => '1px ' . esc_html__( 'for example', 'feed_them_social' ),
					   'default_value'    => '1px',
					   // 'sub_options_end' => 2,
				   ),
				// Show Name.
				array(
					'input_wrap_class' => 'ft-gallery-user-name',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'User Name%1$s Company or user who took this photo%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_username',
					'name'             => 'fts_username',
					'placeholder'      => '',
					'default_value'    => '',
				),
				// Show Name Link.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'User Custom Link%1$s Custom about page or social media page link%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_user_link',
					'name'          => 'fts_user_link',
					'placeholder'   => '',
					'default_value' => '',
				),
				// Show Share.
				array(
					'input_wrap_class' => 'ft-gallery-share',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Show Share Options%1$s Appears in the bottom left corner and in popup%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_wp_share',
					'name'             => 'fts_wp_share',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
				),
				// Show Date.
				array(
					'input_wrap_class' => 'ft-gallery-date',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Show Date%1$s Date image was uploaded%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_wp_date',
					'name'             => 'fts_wp_date',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
				),
				// Image Sizes on page.
				array(
					'input_wrap_class'   => 'ft-images-sizes-page',
					'option_type'        => 'ft-images-sizes-page',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'              => esc_html__( 'Image Size on Page', 'feed_them_social' ),
					'class'              => 'ft-gallery-images-sizes-page',
					'type'               => 'select',
					'id'                 => 'fts_images_sizes_page',
					'name'               => 'fts_images_sizes_page',
					'default_value'      => 'medium',
					'placeholder'        => '',
					'autocomplete'       => 'off',
				),

				// Max-width for Images & Videos.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Max-width for Images', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_max_image_vid_width',
					'name'          => 'fts_max_image_vid_width',
					'placeholder'   => '500px',
					'default_value' => '',
				),
				// Gallery Width.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Gallery Max-width', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_width',
					'name'          => 'fts_width',
					'placeholder'   => '500px',
					'default_value' => '',
				),
				// Gallery Height for scrolling feeds using Post format only, this does not work for grid or gallery options except gallery squared because it does not use masonry. For all others it will be hidden.
				array(
					'input_wrap_class' => 'ft-gallery-height',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Gallery Height%1$s Set the height to have a scrolling feed. Only works for Responsive Image Gallery and the Image Post option.%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_height',
					'name'             => 'fts_height',
					'placeholder'      => '600px',
					'default_value'    => '',
				),
				// Gallery Margin.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'Gallery Margin%1$s To center feed type auto%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_margin',
					'name'          => 'fts_margin',
					'placeholder'   => 'auto',
					'default_value' => 'auto',
				),
				// Gallery Padding.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Gallery Padding', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_padding',
					'name'          => 'fts_padding',
					'placeholder'   => '10px',
					'default_value' => '',
				),
				// ******************************************
				// Gallery Popup
				// ******************************************
				// Display Photos in Popup
				array(
					'grouped_options_title' => esc_html__( 'Popup', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 => esc_html__( 'Display Photos in Popup', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'fts_popup',
					'name'                  => 'fts_popup',
					'default_value'         => 'yes',
					'options'               => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
					'sub_options'           => array(
						'sub_options_wrap_class' => 'facebook-popup-wrap',
					),
					'sub_options_end'       => true,
				),
				// Image Sizes in popup.
				array(
					'input_wrap_class'   => 'ft-images-sizes-popup',
					'option_type'        => 'ft-images-sizes-popup',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'              => esc_html__( 'Image Size in Popup', 'feed_them_social' ),
					'class'              => 'ft-gallery-images-sizes-popup',
					'type'               => 'select',
					'id'                 => 'fts_images_sizes_popup',
					'name'               => 'fts_images_sizes_popup',
					'default_value'      => '',
					'placeholder'        => '',
					'autocomplete'       => 'off',
				),
				array(
					'input_wrap_class' => 'ft-popup-display-options',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Popup Options', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'ft_popup_display_options',
					'name'             => 'ft_popup_display_options',
					'default_value'    => 'no',
					'options'          => array(
						array(
							'label' => esc_html__( 'Default', 'feed_them_social' ),
							'value' => 'default',
						),
						array(
							'label' => esc_html__( 'Full Width & Info below Photo', 'feed_them_social' ),
							'value' => 'full-width-second-half-bottom',
						),
						array(
							'label' => esc_html__( 'Full Width, Photo Only', 'feed_them_social' ),
							'value' => 'full-width-photo-only',
						),
					),
				),

				// ******************************************
				// Gallery Load More Options
				// ******************************************
				// Load More Button
				array(
					'grouped_options_title' => esc_html__( 'Load More Images', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 =>
						sprintf(
							esc_html__( 'Load More Button%1$s Load More unavailable while using the Pagination option.%2$s', 'feed_them_social' ),
							'<br/><small class="ftg-loadmore-notice-colored" style="display: none;">',
							'</small>'
						),
					'type'                  => 'text',
					'id'                    => 'fts_load_more_option',
					'name'                  => 'fts_load_more_option',
					'default_value'         => 'no',
					'options'               => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
					'sub_options'           => array(
						'sub_options_wrap_class' => 'facebook-loadmore-wrap',
					),
				),

				// # of Photos
				array(

					'option_type'   => 'input',
					'label'         => esc_html__( '# of Photos Visible', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_photo_count',
					'name'          => 'fts_photo_count',
					'default_value' => '',
					'placeholder'   => '',
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output).
					'sub_options'   => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
					),

				),

				// Load More Style.
				array(
					'option_type'        => 'select',
					'label'              => esc_html__( 'Load More Style', 'feed_them_social' ),
					'type'               => 'text',
					'id'                 => 'fts_load_more_style',
					'name'               => 'fts_load_more_style',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'default_value'      => 'button',
					'options'            => array(
						1 => array(
							'label' => esc_html__( 'Button', 'feed_them_social' ),
							'value' => 'button',
						),
						2 => array(
							'label' => esc_html__( 'AutoScroll', 'feed_them_social' ),
							'value' => 'autoscroll',
						),
					),
					'sub_options_end'    => true,
				),

				// Load more Button Width.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'Load more Button Width%1$s Leave blank for auto width%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_loadmore_button_width',
					'name'          => 'fts_loadmore_button_width',
					'placeholder'   => '300px ' . esc_html__( 'for example', 'feed_them_social' ),
					'default_value' => '300px',
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options'   => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',
					),
				),
				// Load more Button Margin.
				array(
					'option_type'     => 'input',
					'label'           => esc_html__( 'Load more Button Margin', 'feed_them_social' ),
					'type'            => 'text',
					'id'              => 'fts_loadmore_button_margin',
					'name'            => 'fts_loadmore_button_margin',
					'placeholder'     => '10px ' . esc_html__( 'for example', 'feed_them_social' ),
					'default_value'   => '10px',
					'value'           => '',
					'sub_options_end' => 2,
				),
			),

		);

		return $this->all_options['layout'];
	} //END LAYOUT OPTIONS

	/**
	 * Color Options
	 *
	 * Options for the Color Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function color_options() {
		$this->all_options['colors'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => esc_html__( 'Feed Color Options', 'feed_them_social' ),
			'section_wrap_class' => 'fts-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(

				// Feed Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'         => 'ft-gallery-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-feed-background-color-input',
					'name'          => 'fts_feed_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Feed Grid Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Grid Posts Background Color', 'feed_them_social' ),
					'class'         => 'fb-feed-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-grid-posts-background-color-input',
					'name'          => 'fts_grid_posts_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Border Bottom Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Border Bottom Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-border-bottom-color-input',
					'name'          => 'fts_border_bottom_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore background Color.
				array(
					'grouped_options_title' => esc_html__( 'Loadmore Button', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'                 => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'                  => 'text',
					'id'                    => 'ft-gallery-loadmore-background-color-input',
					'name'                  => 'fts_loadmore_background_color',
					'default_value'         => '',
					'placeholder'           => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),
				// Loadmore background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-text-color-input',
					'name'          => 'fts_loadmore_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore Count Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Image Count Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-count-text-color-input',
					'name'          => 'fts_loadmore_count_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

			),
		);

		return $this->all_options['colors'];
	} //END LAYOUT OPTIONS



	/**
	 * Facebook Options
	 *
	 * Options for the Watermark Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function facebook_options() {
		$this->all_options['facebook'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => __( 'Facebook Feed', 'feed-them-social' ),
			'section_wrap_class' => 'fts-facebook_page-shortcode-form',

			// Form Info
			'form_wrap_classes'  => 'fts-fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',

			'premium_msg_boxes'  => array(
				'album_videos' => array(
					'req_plugin' => 'fts_premium',
					'msg'        => 'The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your WordPress site! <a href="https://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br /><br />Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too! <a href="https://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a>',
				),
				'reviews'      => array(
					'req_plugin' => 'facebook_reviews',
					'msg'        => 'The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="https://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a>',
				),
			),

			//Options Wrap Class
			//'options_wrap_class'       => 'fts-cpt-main-options',

			'main_options'       => array(


				// Show Description below image or video Name
				array(
					'input_wrap_class' => 'fts-social-selector',
					'option_type' => 'select',
					'label'       => __( 'Feed Type', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_feed_type',
					'name'        => 'facebook_page_feed_type',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Facebook Page', 'feed-them-social' ),
							'value' => 'page',
						),

						// Facebook Page List of Events
						// array(
						// 'label' => __('Facebook Page List of Events', 'feed-them-social'),
						// 'value' => 'events',
						// ),
						// Facebook Page Single Event Posts
						// array(
						// 'label' => __('Facebook Page Single Event Posts', 'feed-them-social'),
						// 'value' => 'event',
						// ),
						// Facebook Group
						// array(
						// 'label' => __('Facebook Group', 'feed-them-social'),
						// 'value' => 'group',
						// ),
						// Facebook Album Photos
						array(
							'label' => __( 'Facebook Album Photos', 'feed-them-social' ),
							'value' => 'album_photos',
						),

						// Facebook Album Covers
						array(
							'label' => __( 'Facebook Album Covers', 'feed-them-social' ),
							'value' => 'albums',
						),

						// Facebook Videos
						array(
							'label' => __( 'Facebook Videos', 'feed-them-social' ),
							'value' => 'album_videos',
						),

						// Facebook Page Reviews
						array(
							'label' => __( 'Facebook Page Reviews', 'feed-them-social' ),
							'value' => 'reviews',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'type',
					),
				),

				// Access Token SRL. DO NOT REMOVE!
				/*array(
					'option_type' => 'input',
					'label'       => __( 'Access Token (required) ', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'fb_access_token',
					'name'        => 'fb_access_token',

					// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
					'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
					'placeholder' => __( '', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'access_token',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),*/

				// Facebook Album ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'fb_album_photos_id',
					'label'       => __( 'Album ID ', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank to show all uploaded photos', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_album_id',
					'name'        => 'facebook_album_id',
					'value'       => '',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'album_id',
						'var_final_if' => 'yes',
						'empty_error' => 'set',
						'empty_error_value' => 'album_id=photo_stream',
						'empty_error_if' => array(
							'attribute' => 'select#facebook_page_feed_type',
							'operator' => '==',
							'value'    => 'album_photos',
						),
						'ifs' => 'album_photos',
					),
				),

				// Facebook Page Post Type Visible
				array(
					'input_wrap_class' => 'facebook-post-type-visible',
					'option_type' => 'select',
					'label'       => __( 'Post Type Visible', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_page_posts_displayed',
					'name'        => 'facebook_page_posts_displayed',
					'options'     => array(
						array(
							'label' => __( 'Display Posts made by Page only', 'feed-them-social' ),
							'value' => 'page_only',
						),
						array(
							'label' => __( 'Display Posts made by Page and Others', 'feed-them-social' ),
							'value' => 'page_and_others',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'posts_displayed',
						'ifs' => 'page',
					),
				),

				// Facebook page # of Posts
				array(
					'option_type' => 'input',
					'label'       => __( '# of Posts', 'feed-them-social' ) ,
					'type'        => 'text',
					'id'          => 'facebook_page_post_count',
					'name'        => 'facebook_page_post_count',
					'value'       => '',
					'placeholder' => __( '6 is the default number', 'feed-them-social' ),
					'default_value' => '6',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'posts',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => 'posts=6',
					),
				),

				// Facebook Page Facebook Fixed Height
				array(
					'input_wrap_class' => 'fixed_height_option',
					'option_type' => 'input',
					'label'       => __( 'Facebook Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_height',
					'name'        => 'facebook_page_height',
					'value'       => '',
					'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'height',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),

				// Facebook Page Show Page Title (Premium)
				array(
					'input_wrap_class' => 'fb-page-title-option-hide',
					'option_type' => 'select',
					'label'       => __( 'Show Page Title', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_title',
					'name'        => 'facebook_page_title',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'title',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'facebook-title-options-wrap',
					),
				),

				// Facebook Page Align Title (Premium)
				array(
					'input_wrap_class' => 'fb-page-title-align',
					'option_type' => 'select',
					'label'       => __( 'Align Title', 'feed-them-social' ) . '<br/><small>' . __( 'Left, Center or Right', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_title_align',
					'name'        => 'facebook_page_title_align',
					'options'     => array(
						1 => array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => __( 'Center', 'feed-them-social' ),
							'value' => 'center',
						),
						3 => array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'title_align',
					),
				),

				// Facebook Page Show Page Description (Premium)
				array(
					'input_wrap_class' => 'fb-page-description-option-hide',
					'option_type' => 'select',
					'label'       => __( 'Show Page Description', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_description',
					'name'        => 'facebook_page_description',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'description',
					),
					'sub_options_end' => true,
				),

				// Show Description below image or video Name
				array(
					'input_wrap_class' => 'facebook_show_media',
					'option_type' => 'select',
					'label'       => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom or Top of Post', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_show_media',
					'name'        => 'facebook_show_media',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
							'value' => 'bottom',
						),
						array(
							'label' => __( 'Above Username, Date & Description', 'feed-them-social' ),
							'value' => 'top',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_media',
					),
				),

				// Show Thumbnail
				array(
					'input_wrap_class' => 'facebook_hide_thumbnail',
					'option_type' => 'select',
					'label'       => __( 'Show User Thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_hide_thumbnail',
					'name'        => 'facebook_hide_thumbnail',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_thumbnail',
					),
				),

				// Hide Date
				array(
					'input_wrap_class' => 'facebook_hide_date',
					'option_type' => 'select',
					'label'       => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_hide_date',
					'name'        => 'facebook_hide_date',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_date',
					),
				),

				// Hide Name
				array(
					'input_wrap_class' => 'facebook_hide_name',
					'option_type' => 'select',
					'label'       => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_hide_name',
					'name'        => 'facebook_hide_name',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_name',
					),
				),

				// Facebook Amount of words
				array(
					'option_type' => 'input',
					'label'       => __( 'Amount of words per post', 'feed-them-social' ) . '<br/><small>' . __( 'Type 0 to remove the posts description', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_page_word_count',
					'name'        => 'facebook_page_word_count',
					'placeholder' => '45 ' . __( 'is the default number', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'   => 'words',
						'empty_error' => 'set',
						'empty_error_value' => 'words=45',
					),
				),

				// Facebook Image Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Facebook Image Width', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_image_width',
					'name'        => 'facebook_image_width',
					'placeholder' => '250px',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'facebook_image_width',
						'empty_error' => 'set',
						'empty_error_value' => 'image_width=250px',
						'ifs' => 'album_photos,albums,album_videos',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-super-facebook-options-wrap',
					),
				),

				// Facebook Image Height
				array(
					'option_type' => 'input',
					'label'       => __( 'Facebook Image Height', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_image_height',
					'name'        => 'facebook_image_height',
					'placeholder' => '250px',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'image_height',
						'empty_error' => 'set',
						'empty_error_value' => 'image_height=250px',
						'ifs' => 'album_photos,albums,album_videos',
					),
				),

				// Facebook The space between photos
				array(
					'option_type' => 'input',
					'label'       => __( 'The space between photos', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_space_between_photos',
					'name'        => 'facebook_space_between_photos',
					'placeholder' => '1px',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'space_between_photos',
						'empty_error' => 'set',
						'empty_error_value' => 'space_between_photos=1px',
						'ifs' => 'album_photos,albums,album_videos',
					),
				),

				// Hide Date, Likes and Comments
				array(
					'option_type' => 'select',
					'label'       => __( 'Hide Date, Likes and Comments', 'feed-them-social' ),
					'label_note'  => __( 'Good for image sizes under 120px', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_hide_date_likes_comments',
					'name'        => 'facebook_hide_date_likes_comments',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'hide_date_likes_comments',
						'ifs' => 'album_photos,albums,album_videos',
					),
				),

				// Center Facebook Container
				array(
					'option_type' => 'select',
					'label'       => __( 'Center Facebook Container', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_container_position',
					'name'        => 'facebook_container_position',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'center_container',
						'ifs' => 'album_photos,albums,album_videos',
					),
					'sub_options_end' => true,
				),

				// Image Stacking Animation NOT USING THIS ANYMORE
				array(
					'option_type' => 'input',
					'label'       => __( 'Image Stacking Animation On', 'feed-them-social' ),
					'label_note'  => __( 'This happens when resizing browser', 'feed-them-social' ),
					'type'        => 'hidden',

					// used to trick is Visible in JS
					'class'       => 'non-visible',
					'id'          => 'facebook_container_animation',
					'name'        => 'facebook_container_animation',
					'value'       => 'no',
					'short_attr'  => array(
						'attr_name' => 'image_stack_animation',
						'empty_error' => 'set',
						'empty_error_value' => 'image_stack_animation=no',
						'ifs' => 'grid',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'facebook-image-animation-option-wrap',
					),
					'sub_options_end' => true,
				),

				// Align Images non-grid
				array(
					'input_wrap_id' => 'facebook_align_images_wrapper',
					'option_type' => 'select',
					'label'       => __( 'Align Images', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_align_images',
					'name'        => 'facebook_align_images',
					'options'     => array(
						1 => array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => __( 'Center', 'feed-them-social' ),
							'value' => 'center',
						),
						3 => array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'images_align',
						'ifs' => 'page',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'align-images-wrap',
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Facebook Review Options
				// ******************************************
				// Reviews to Show
				array(
					'grouped_options_title' => __( 'Reviews', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Reviews to Show', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'reviews_type_to_show',
					'name'        => 'reviews_type_to_show',
					'options'     => array(
						1 => array(
							'label' => __( 'Show all Reviews', 'feed-them-social' ),
							'value' => '1',
						),
						2 => array(
							'label' => __( '5 Star Reviews only', 'feed-them-social' ),
							'value' => '5',
						),
						3 => array(
							'label' => __( '4 and 5 Stars Reviews only', 'feed-them-social' ),
							'value' => '4',
						),
						4 => array(
							'label' => __( '3, 4 and 5 Star Reviews only', 'feed-them-social' ),
							'value' => '3',
						),
						5 => array(
							'label' => __( '2, 3, 4, and 5 Star Reviews only', 'feed-them-social' ),
							'value' => '2',
						),
					),
					'req_plugin'  => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'reviews_type_to_show',
						'ifs' => 'reviews',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'facebook-reviews-wrap',
					),
				),

				// Rating Format
				array(
					'option_type' => 'select',
					'label'       => __( 'Rating Format', 'feed-them-social' ) . '<br/><small>' . __( '8/17/2018: Facebook has moved to what are called "recommendations" so for some people this option may not be necessary.', 'feed-them-premium' ) . '</small>',
					'type'        => 'text',
					'id'          => 'reviews_rating_format',
					'name'        => 'reviews_rating_format',
					'options'     => array(
						1 => array(
							'label' => __( '5 star - &#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social' ),
							'value' => '1',
						),
						2 => array(
							'label' => __( '5 star &#9733;', 'feed-them-social' ),
							'value' => '2',
						),
						3 => array(
							'label' => __( '5 star', 'feed-them-social' ),
							'value' => '3',
						),
						4 => array(
							'label' => __( '5 &#9733;', 'feed-them-social' ),
							'value' => '4',
						),
						5 => array(
							'label' => __( '&#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social' ),
							'value' => '5',
						),
					),
					'req_plugin'  => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'reviews_rating_format',
						'ifs' => 'reviews',
					),
				),

				// Overall Rating
				array(
					'option_type' => 'select',
					'label'       => __( 'Overall Rating above Feed', 'feed-them-social' ) . '<br/><small>' . __( 'More settings: <a href="admin.php?page=fts-facebook-feed-styles-submenu-page#overall-rating-options">Facebook Options</a> page.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'reviews_overall_rating_show',
					'name'        => 'reviews_overall_rating_show',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'req_plugin'  => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'overall_rating',
						'ifs' => 'reviews',
					),
				),

				// Hide Reviews with no Text
				array(
					'option_type' => 'select',
					'label'       => __( 'Hide Reviews with no description', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'reviews_with_no_text',
					'name'        => 'reviews_with_no_text',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'remove_reviews_no_description',
						'ifs' => 'reviews',
					),
				),

				// Hide Reviews the text link, "See More Reviews"
				array(
					'option_type' => 'select',
					'label'       => __( 'Hide the text "See More Reviews"', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'hide_see_more_reviews_link',
					'name'        => 'hide_see_more_reviews_link',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'hide_see_more_reviews_link',
						'ifs' => 'reviews',
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Like Box Options
				// ******************************************
				// Facebook Hide Like Box or Button (Premium)
				array(
					'grouped_options_title' => __( 'Like Box', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Hide Like Box or Button', 'feed-them-social' ) . '<br/><small>' . __( 'Turn on from <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_hide_like_box_button',
					'name'        => 'facebook_hide_like_box_button',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-like-box-wrap',
					),
				),

				// Position of Like Box or Button (Premium)
				array(
					'option_type' => 'select',
					'label'       => __( 'Position of Like Box or Button', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_position_likebox',
					'name'        => 'facebook_position_likebox',
					'options'     => array(
						1 => array(
							'label' => __( 'Above Title', 'feed-them-social' ),
							'value' => 'above_title',
						),
						2 => array(
							'label' => __( 'Below Title', 'feed-them-social' ),
							'value' => 'below_title',
						),
						3 => array(
							'label' => __( 'Bottom of Feed', 'feed-them-social' ),
							'value' => 'bottom',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'show_follow_btn_where',
						'ifs'     => 'not_group',
						'and_ifs' => 'like_box',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'like-box-wrap',
					),
				),

				// Facebook Page Align Like Box or Button (Premium)
				array(
					'option_type' => 'select',
					'label'       => __( 'Align Like Box or Button', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_align_likebox',
					'name'        => 'facebook_align_likebox',
					'options'     => array(
						1 => array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => __( 'Center', 'feed-them-social' ),
							'value' => 'center',
						),
						3 => array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'like_option_align',
						'ifs'     => 'not_group',
						'and_ifs' => 'like_box',
					),
				),

				// Facebook Page Width of Like Box
				array(
					'option_type' => 'input',
					'label'       => __( 'Width of Like Box', 'feed-them-social' ) . '<br/><small>' . __( 'This only works for the Like Box', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_like_box_width',
					'name'        => 'facebook_like_box_width',
					'placeholder' => __( '500px max', 'feed-them-social' ),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'facebook_like_box_width',
						'empty_error' => 'set',
						'empty_error_value' => 'facebook_like_box_width=500px',
						'ifs'     => 'not_group',
						'and_ifs' => 'like_box',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Popup
				// ******************************************
				// Facebook Page Display Photos in Popup
				array(
					'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Display Photos in Popup', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_popup',
					'name'        => 'facebook_popup',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => 'popup',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'facebook-popup-wrap',
					),
					'sub_options_end' => true,
				),

				// Facebook Comments in Popup
				array(
					'option_type' => 'select',
					'label'       => __( 'Hide Comments in Popup', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_popup_comments',
					'name'        => 'facebook_popup_comments',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => 'facebook_popup_comments',
						'ifs' => 'popup',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'display-comments-wrap',
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Facebook Load More Options
				// ******************************************
				// Facebook Page Load More Button
				array(
					'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Load More Button', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_load_more',
					'name'        => 'facebook_load_more',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
						'ifs' => 'not_events',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'facebook-loadmore-wrap',

						// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
					),
				),

				// Facebook Page Load More Style
				array(
					'option_type' => 'select',
					'label'       => __( 'Load More Style', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_load_more_style',
					'name'        => 'facebook_load_more_style',
					'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
					'options'     => array(
						1 => array(
							'label' => __( 'Button', 'feed-them-social' ),
							'value' => 'button',
						),
						2 => array(
							'label' => __( 'AutoScroll', 'feed-them-social' ),
							'value' => 'autoscroll',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name' => 'loadmore',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',

						// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
					),
					'sub_options_end' => true,
				),

				// Facebook Page Load more Button Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_loadmore_button_width',
					'name'        => 'facebook_loadmore_button_width',
					'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_maxwidth',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_maxwidth=300px',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',

						// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
					),
				),

				// Facebook Page Load more Button Margin
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'loadmore_button_margin',
					'name'        => 'loadmore_button_margin',
					'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_margin',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_margin=10px',
						'ifs' => 'load_more',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Facebook Grid Options
				// ******************************************
				// Facebook Page Display Posts in Grid
				array(
					'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
					'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
					'option_type' => 'select',
					'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_grid',
					'name'        => 'facebook_grid',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',
					'short_attr'  => array(
						'attr_name'    => 'grid',
						'empty_error'  => 'set',
						'set_operator' => '==',
						'set_equals'   => 'yes',
						'empty_error_value' => '',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-grid-options-wrap',
					),
				),

				// Grid Column Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Grid Column Width', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_grid_column_width',
					'name'        => 'facebook_grid_column_width',
					'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
					'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'colmn_width',
						'empty_error' => 'set',
						'empty_error_value' => 'colmn_width=310px',
						'ifs' => 'grid',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',

						// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
					),
				),

				// Grid Spaces Between Posts
				array(
					'option_type' => 'input',
					'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_grid_space_between_posts',
					'name'        => 'facebook_grid_space_between_posts',
					'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',
					'or_req_plugin' => 'combine_streams',
					'or_req_plugin_three' => 'facebook_reviews',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'space_between_posts',
						'empty_error' => 'set',
						'empty_error_value' => 'space_between_posts=10px',
						'ifs' => 'grid',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Facebook Video Options
				// ******************************************
				// Video Play Button
				array(
					'grouped_options_title' => __( 'Video Button Options', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Video Play Button', 'feed-them-social' ) . '<br/><small>' . __( 'Displays over Video Thumbnail', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_show_video_button',
					'name'        => 'facebook_show_video_button',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'facebook_play_btn',
						'empty_error' => 'set',
						'set_operator' => '==',
						'set_equals' => 'yes',
						'ifs' => 'album_videos',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fb-video-play-btn-options-wrap',
					),
				),

				// Size of the Play Button
				array(
					'option_type' => 'input',
					'label'       => __( 'Size of the Play Button', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'facebook_size_video_play_btn',
					'name'        => 'facebook_size_video_play_btn',
					'placeholder' => '40px ' . __( 'for example', 'feed-them-social' ),
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'facebook_play_btn_size',
						'empty_error' => 'set',
						'empty_error_value' => 'play_btn_size=40px',
						'ifs'     => 'album_videos',
						'and_ifs' => 'video',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fb-video-play-btn-options-content',
					),
				),

				// Show Play Button in Front
				array(
					'option_type' => 'select',
					'label'       => __( 'Show Play Button in Front', 'feed-them-social' ) . '<br/><small>' . __( 'Displays before hovering over thumbnail', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'facebook_show_video_button_in_front',
					'name'        => 'facebook_show_video_button_in_front',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'facebook_play_btn_visible',
						'ifs'     => 'album_videos',
						'and_ifs' => 'video',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Facebook Carousel
				// ******************************************
				// Carousel/Slideshow
				array(
					'grouped_options_title' => __( 'Carousel/Slider', 'feed-them-social' ),
					'input_wrap_id' => 'facebook_slider',
					'instructional-text' => __( 'Create a Carousel or Slideshow with these options.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . __( 'View Demos', 'feed-them-social' ) . '</a> ' . __( 'and copy easy to use shortcode examples.', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Carousel/Slideshow', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'fts-slider',
					'name'        => 'fts-slider',
					'options'     => array(
						1 => array(
							'label' => __( 'Off', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'On', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_carousel',
					'short_attr'  => array(
						'attr_name' => 'slider',
						'empty_error' => 'set',
						'set_operator' => '==',
						'set_equals' => 'yes',
						'ifs' => 'album_photos,album_videos',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'slideshow-wrap',
					),
				),

				// Carousel/Slideshow Type
				array(
					'input_wrap_id' => 'facebook_scrollhorz_or_carousel',
					'option_type' => 'select',
					'label'       => __( 'Type', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'scrollhorz_or_carousel',
					'name'        => 'scrollhorz_or_carousel',
					'options'     => array(
						1 => array(
							'label' => __( 'Slideshow', 'feed-them-social' ),
							'value' => 'scrollhorz',
						),
						2 => array(
							'label' => __( 'Carousel', 'feed-them-social' ),
							'value' => 'carousel',
						),
					),
					'req_plugin'  => 'fts_carousel',
					'short_attr'  => array(
						'attr_name' => 'scrollhorz_or_carousel',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'slider_options_wrap',
					),
				),

				// Carousel Slides Visible
				array(
					'input_wrap_id' => 'facebook_slides_visible',
					'option_type' => 'input',
					'label'       => __( 'Carousel Slides Visible', 'feed-them-social' ) . '<br/><small>' . __( 'Not for Slideshow. Example: 1-500', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slides_visible',
					'name'        => 'slides_visible',
					'placeholder' => __( '3 is the default number', 'feed-them-social' ),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slides_visible',
						'empty_error' => 'set',
						'empty_error_value' => 'slides_visible=3',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'slider_carousel_wrap',
					),
				),

				// Carousel Spacing in between Slides
				array(
					'input_wrap_id' => 'facebook_slider_spacing',
					'option_type' => 'input',
					'label'       => __( 'Spacing in between Slides', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_spacing',
					'name'        => 'slider_spacing',
					'value'       => '',
					'placeholder' => __( '2px', 'feed-them-social' ),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_spacing',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_spacing=2px',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
					'sub_options_end' => true,
				),

				// Carousel/Slideshow Margin
				array(
					'input_wrap_id' => 'facebook_slider_margin',
					'option_type' => 'input',
					'label'       => __( 'Carousel/Slideshow Margin', 'feed-them-social' ) . '<br/><small>' . __( 'Center feed. Add space above/below.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_margin',
					'name'        => 'slider_margin',
					'value'       => '',
					'placeholder' => __( '-6px auto 1px auto', 'feed-them-social' ),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_margin',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_margin="-6px auto 1px auto"',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow Slider Speed
				array(
					'input_wrap_id' => 'facebook_slider_speed',
					'option_type' => 'input',
					'label'       => __( 'Slider Speed', 'feed-them-social' ) . '<br/><small>' . __( 'How fast the slider changes', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_speed',
					'name'        => 'slider_speed',
					'value'       => '',
					'placeholder' => __( '0-10000', 'feed-them-social' ),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_speed',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_speed=1000',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow Slider Timeout
				array(
					'input_wrap_id' => 'facebook_slider_timeout',
					'option_type' => 'input',
					'label'       => __( 'Slider Timeout', 'feed-them-social' ) . '<br/><small>' . __( 'Amount of Time before the next slide.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_timeout',
					'name'        => 'slider_timeout',
					'value'       => '',
					'placeholder' => __( '0-10000', 'feed-them-social' ),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_timeout',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_timeout=1000',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow
				array(
					'input_wrap_id' => 'facebook_slider_controls',
					'option_type' => 'select',
					'label'       => __( 'Slider Controls', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_controls',
					'name'        => 'slider_controls',
					'options'     => array(
						1 => array(
							'label' => __( 'Dots above Feed', 'feed-them-social' ),
							'value' => 'dots_above_feed',
						),
						2 => array(
							'label' => __( 'Dots and Arrows above Feed', 'feed-them-social' ),
							'value' => 'dots_and_arrows_above_feed',
						),
						3 => array(
							'label' => __( 'Dots and Numbers above Feed', 'feed-them-social' ),
							'value' => 'dots_and_numbers_above_feed',
						),
						4 => array(
							'label' => __( 'Dots, Arrows and Numbers above Feed', 'feed-them-social' ),
							'value' => 'dots_arrows_and_numbers_above_feed',
						),
						5 => array(
							'label' => __( 'Arrows and Numbers above feed', 'feed-them-social' ),
							'value' => 'arrows_and_numbers_above_feed',
						),
						6 => array(
							'label' => __( 'Arrows above Feed', 'feed-them-social' ),
							'value' => 'arrows_above_feed',
						),
						7 => array(
							'label' => __( 'Numbers above Feed', 'feed-them-social' ),
							'value' => 'numbers_above_feed',
						),
						8 => array(
							'label' => __( 'Dots below Feed', 'feed-them-social' ),
							'value' => 'dots_below_feed',
						),
						array(
							'label' => __( 'Dots and Arrows below Feed', 'feed-them-social' ),
							'value' => 'dots_and_arrows_below_feed',
						),
						array(
							'label' => __( 'Dots and Numbers below Feed', 'feed-them-social' ),
							'value' => 'dots_and_numbers_below_feed',
						),
						array(
							'label' => __( 'Dots, Arrows and Numbers below Feed', 'feed-them-social' ),
							'value' => 'dots_arrows_and_numbers_below_feed',
						),
						array(
							'label' => __( 'Arrows below Feed', 'feed-them-social' ),
							'value' => 'arrows_below_feed',
						),
						array(
							'label' => __( 'Numbers Below Feed', 'feed-them-social' ),
							'value' => 'numbers_below_feed',
						),
					),
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_controls',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow Slider Controls Text Color
				array(
					'input_wrap_id' => 'facebook_slider_controls_text_color',
					'option_type' => 'input',
					'label'       => __( 'Slider Controls Text Color', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_controls_text_color',
					'name'        => 'slider_controls_text_color',
					'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'value'       => '',
					'placeholder' => '#FFF',
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_controls_text_color',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_controls_text_color=#FFF',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow Slider Controls Bar Color
				array(
					'input_wrap_id' => 'facebook_slider_controls_bar_color',
					'option_type' => 'input',
					'label'       => __( 'Slider Controls Bar Color', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_controls_bar_color',
					'name'        => 'slider_controls_bar_color',
					'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'value'       => '',
					'placeholder' => '#000',
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_controls_bar_color',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_controls_bar_color=320px',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
				),

				// Carousel/Slideshow Slider Controls Bar Color
				array(
					'input_wrap_id' => 'facebook_slider_controls_width',
					'option_type' => 'input',
					'label'       => __( 'Slider Controls Max Width', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'slider_controls_width',
					'name'        => 'slider_controls_width',
					'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'value'       => '',
					'placeholder' => '320px',
					'req_plugin'  => 'fts_carousel',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'slider_controls_width',
						'empty_error' => 'set',
						'empty_error_value' => 'slider_controls_width=320px',
						'ifs'     => 'album_photos,album_videos',
						'and_ifs' => 'carousel',
					),
					'sub_options_end' => 2,
				),
			),

			// Final Shortcode ifs
			'shortcode_ifs'      => array(
				'page'         => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'page',
					),
				),
				'events'       => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'events',
					),
				),
				'not_events'   => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '!==',
						'value'    => 'events',
					),
				),
				'event'        => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'event',
					),
				),
				'group'        => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'group',
					),
				),
				'not_group'    => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '!==',
						'value'    => 'group',
					),
				),
				'album_photos' => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'album_photos',
					),
				),
				'albums'       => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'albums',
					),
				),
				'album_videos' => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'album_videos',
					),
				),
				'reviews'      => array(
					'if' => array(
						'class'    => 'select#facebook_page_feed_type',
						'operator' => '==',
						'value'    => 'reviews',
					),
				),
				'like_box'     => array(
					'if' => array(
						'class'    => 'select#facebook_hide_like_box_button',
						'operator' => '==',
						'value'    => 'no',
					),
				),
				'popup'        => array(
					'if' => array(
						'class'    => 'select#facebook_popup',
						'operator' => '==',
						'value'    => 'yes',
					),
				),
				'load_more'    => array(
					'if' => array(
						'class'    => 'select#facebook_load_more_option',
						'operator' => '==',
						'value'    => 'yes',
					),
				),
				'video'        => array(
					'if' => array(
						'class'    => 'select#facebook_show_video_button',
						'operator' => '==',
						'value'    => 'yes',
					),
				),
				'grid'         => array(
					'if' => array(
						'class'    => 'select#facebook_grid',
						'operator' => '==',
						'value'    => 'yes',
					),
				),
				'carousel'     => array(
					'if' => array(
						'class'    => 'select#fts-slider',
						'operator' => '==',
						'value'    => 'yes',
					),
				),
			),
		);

		return $this->all_options['facebook'];
	} //END WATERMARK OPTIONS

	/**
	 * Instagram Options
	 *
	 * Options for the Instagram Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function instagram_options() {

		$this->all_options['instagram'] = array(
			'section_attr_key'   => 'instagram_',
			'section_title'      => __( 'Instagram Feed', 'feed-them-social' ),
			'section_wrap_class' => 'fts-instagram-shortcode-form',

			// Form Info
			'form_wrap_classes'  => 'instagram-shortcode-form',
			'form_wrap_id'       => 'fts-instagram-form',

			// Token Check
			'token_check'        => array(
				array(
					'option_name'  => 'fts_twitter_custom_access_token_secret',
					'no_token_msg' => __( '<strong>STEP 1:</strong> Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
				),
			),

			// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
			'main_options'       => array(

                // Feed Type
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Feed Type', 'feed-them-social' ),
                    'type'        => 'text',
                    'input_wrap_class' => 'instagram-messages-selector',
                    'id'          => 'instagram-messages-selector',
                    'name'        => 'instagram-messages-selector',
                    'default_value'    => '',
                    'options'     => array(
                        array(
                            'label' => __( 'Basic', 'feed-them-social' ),
                            'value' => 'basic',
                        ),
                        array(
                            'label' => __( 'Business', 'feed-them-social' ),
                            'value' => 'business',
                        ),
                        array(
                            'label' => __( 'Hashtag', 'feed-them-social' ),
                            'value' => 'hashtag',
                        ),
                    ),
                ),

				// Instagram ID SRL. DO NOT REMOVE
				/*array(
					'option_type' => 'input',
					'input_wrap_class' => 'instagram_name',
					'label'       => array(
						1 => array(
							'text' => __( 'Instagram ID # (required)', 'feed-them-social' ),
							'class' => 'instagram-user-option-text',
						)
					),
					'type'        => 'text',
					'id'          => 'instagram_id',
					'name'        => 'instagram_id',
					'required'    => 'yes',
					'instructional-text' => array(
						1 => array(
							'text' => __( '<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social' ),
							'class' => 'instagram-user-option-text',
						)
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'instagram_id',
						'var_final_if' => 'no',
						'empty_error'  => 'yes',
					),
				),*/


				// Instagram Hashtag
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'instagram_hashtag',
					'label'       => array(
						1 => array(
							'text' => __( 'Hashtag (required)', 'feed-them-social' ),
							'class' => 'instagram-hashtag-option-text',
						),
					),
					'type'        => 'text',
					'id'          => 'instagram_hashtag',
					'name'        => 'instagram_hashtag',
					'required'    => 'yes',
					'instructional-text' => array(
						1 => array(
							'text' => __( 'Add your hashtag below. <strong>DO NOT</strong> add the #, just the name. Only one hashtag allowed at this time. Hashtag media only stays on Instagram for 24 hours and the API does not give us a date/time. In order to use the Instagram hashtag feed you must have your Instagram account linked to a Facebook Business Page. <a target="_blank" href="https://www.slickremix.com/docs/link-instagram-account-to-facebook/">Read Instructions.</a>', 'feed-them-social' ),
							'class' => 'instagram-hashtag-option-text',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'hashtag',
						'var_final_if' => 'no',
						'empty_error'  => 'yes',
					),
				),

				/*// Access Token. SRL. DO NOT REMOVE
				array(
					'option_type' => 'input',
					'label'       => __( 'Access Token (required) ', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'insta_access_token',
					'name'        => 'insta_access_token',

					// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
					'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
					'placeholder' => __( '', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'access_token',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),*/

				// Hashtag Type
				array(
					'option_type' => 'select',
					'input_wrap_class' => 'instagram_hashtag_type',
					'label'       => __( 'Hashtag Search Type', 'feed-them-social' ),

					'type'        => 'text',
					'id'          => 'instagram_hashtag_type',
					'name'        => 'instagram_hashtag_type',
					'class'       => 'instagram-hashtag-type',
					'options'     => array(
						1 => array(
							'label' => __( 'Recent Media', 'feed-them-social' ),
							'value' => 'recent-media',
						),
						2 => array(
							'label' => __( 'Top Media (Most Interactions)', 'feed-them-social' ),
							'value' => 'top-media',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'search',
					),
				),

				// Pic Count
				array(
					'option_type' => 'input',
					'label'       => __( '# of Pics (optional)', 'feed-them-social' ) . $limitforpremium,
					'type'        => 'text',
					'id'          => 'pics_count',
					'name'        => 'pics_count',

					// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
					// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
					'placeholder' => __( '6 is the default value', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'pics_count',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => 'pics_count=6',
					),
				),

				// Feed Type
				array(
					'option_type' => 'select',
					'id'          => 'instagram-messages-selector',
					'no_html'     => 'yes',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'type',
					),
				),


				// Instagram Width
				array(
					'input_wrap_class' => 'instagram_width_option',
					'option_type' => 'input',
					'label'       => __( 'Gallery Width', 'feed-them-social' ),
					'label_note'  => __( 'Leave blank for auto height', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_page_width',
					'name'        => 'instagram_page_width',
					'placeholder' => '50% or 450px ' . __( 'for example', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'width',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',

						// Special case: need no attribute if empty
						'empty_error_value' => '',
					),
				),

				// Instagram Fixed Height
				array(
					'input_wrap_class' => 'instagram_fixed_height_option',
					'option_type' => 'input',
					'label'       => __( 'Gallery Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Use this option to create a scrolling feed.', 'feed-them-social' ) . '</small>',
					'label_note'  => __( 'Leave blank for auto height', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_page_height',
					'name'        => 'instagram_page_height',
					'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'height',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',

						// Special case: need no attribute if empty
						'empty_error_value' => '',
					),
				),

				// ******************************************
				// Profile Wrap
				// ******************************************
				array(
					'grouped_options_title' => __( 'Profile', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Show Profile Info', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-profile-wrap',
					'name'        => 'instagram-profile-wrap',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'profile_wrap',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-instagram-profile-options-wrap',
					),
				),
				array(
					'option_type' => 'select',
					'label'       => __( 'Show Profile Photo', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-profile-photo',
					'name'        => 'instagram-profile-photo',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'profile_photo',
						'ifs' => 'profile_wrap',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'instagram-profile-options-wrap',
					),
				),
				array(
					'option_type' => 'select',
					'label'       => __( 'Show Profile Stats', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-profile-stats',
					'name'        => 'instagram-profile-stats',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'profile_stats',
						'ifs' => 'profile_wrap',
					),
				),
				array(
					'option_type' => 'select',
					'label'       => __( 'Show Profile Name', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-profile-name',
					'name'        => 'instagram-profile-name',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'profile_name',
						'ifs' => 'profile_wrap',
					),
				),
				array(
					'option_type' => 'select',
					'label'       => __( 'Show Profile Description', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-profile-description',
					'name'        => 'instagram-profile-description',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'profile_description',
						'ifs' => 'profile_wrap',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Super Gallery - SRL  DO NOT REMOVE!
				// ******************************************
				/*array(
					'grouped_options_title' => __( 'Responsive Gallery', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Gallery Style', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram-custom-gallery',
					'name'        => 'instagram-custom-gallery',
					'options'     => array(
						1 => array(
							'label' => __( 'Responsive Gallery', 'feed-them-social' ),
							'value' => 'yes',
						),
						//	2 => array(
						//		'label' => __( 'Classic Gallery Style', 'feed-them-social' ),
						//		'value' => 'no',
						//	),
					),
					'short_attr'  => array(
						'attr_name' => 'super_gallery',
						'ifs' => 'super_gallery',
					),
				),*/
				array(
                    'grouped_options_title' => __( 'Responsive Gallery', 'feed-them-social' ),
					'input_wrap_class' => 'fb-page-columns-option-hide',
					'option_type' => 'select',
					'label'       => __( 'Number of Columns', 'feed-them-social' ),
					'type'        => 'text',
					'instructional-text' => '<strong>' . __( 'NOTE: ', 'feed-them-social' ) . '</strong>' . __( 'Choose the Number of Columns and Space between each image below. Please add px after any number.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __( 'View demo', 'feed-them-social' ) . '</a>',
					'id'          => 'fts_instagram_columns',
					'name'        => 'fts_instagram_columns',
					'default_value' => '3',
					'options'     => array(
						array(
							'label' => __( '1', 'feed-them-social' ),
							'value' => '1',
						),
						array(
							'label' => __( '2', 'feed-them-social' ),
							'value' => '2',
						),
						array(
							'label' => __( '3', 'feed-them-social' ),
							'value' => '3',
						),
						array(
							'label' => __( '4', 'feed-them-social' ),
							'value' => '4',
						),
						array(
							'label' => __( '5', 'feed-them-social' ),
							'value' => '5',
						),
						array(
							'label' => __( '6', 'feed-them-social' ),
							'value' => '6',
						),
						array(
							'label' => __( '7', 'feed-them-social' ),
							'value' => '7',
						),
						array(
							'label' => __( '8', 'feed-them-social' ),
							'value' => '8',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'columns',
						'ifs' => 'super_gallery',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-super-instagram-options-wrap',
					),
				),
				array(
					'input_wrap_class' => 'fb-page-columns-option-hide',
					'option_type' => 'select',
					'label'       => __( 'Force Columns', 'feed-them-social' ) . '<br/><small>' . __( 'No, will allow the images to be responsive for smaller devices. Yes, will force columns.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'fts_instagram_force_columns',
					'name'        => 'fts_instagram_force_columns',
					'default_value' => 'no',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'force_columns',
						'ifs' => 'super_gallery',
					),
				),

				// Space between Photos
				array(
					'option_type' => 'input',
					'label'       => __( 'The space between photos', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'fts-slicker-instagram-container-margin',
					'name'        => 'fts-slicker-instagram-container-margin',
					'placeholder' => '1px',
					'value'       => '',
					'short_attr'  => array(
						'attr_name' => 'space_between_photos',
						'var_final_if' => 'yes',
						'empty_error' => 'set',
						'empty_error_value' => 'space_between_photos=1px',
						'ifs' => 'super_gallery',
					),
				),

				// Icon Size
				array(
					'option_type' => 'input',
					'label'       => __( 'Size of the Instagram Icon', 'feed-them-social' ),
					'label_note'  => __( 'Visible when you hover over photo', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'fts-slicker-instagram-icon-center',
					'name'        => 'fts-slicker-instagram-icon-center',
					'placeholder' => '65px',
					'short_attr'  => array(
						'attr_name' => 'icon_size',
						'var_final_if' => 'yes',
						'empty_error' => 'set',
						'empty_error_value' => 'icon_size=65px',
						'ifs' => 'super_gallery',
					),
				),

				// Hide Date, Likes and Comments
				array(
					'option_type' => 'select',
					'label'       => __( 'Date, Heart & Comment icon', 'feed-them-social' ) . '<br/><small>' . __( 'Heart and Comment counts only work when using Feed Type: Business Feed.', 'feed-them-social' ) . '</small>',
					'label_note'  => __( 'Good for image sizes under 120px', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'fts-slicker-instagram-container-hide-date-likes-comments',
					'name'        => 'fts-slicker-instagram-container-hide-date-likes-comments',
					'options'     => array(
						1 => array(
							'label' => __( 'Show', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Hide', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'hide_date_likes_comments',
						'ifs' => 'super_gallery',
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Load More
				// ******************************************
				array(
					'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Load more posts', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_load_more_option',
					'name'        => 'instagram_load_more_option',

					// Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
					'prem_req'    => 'yes',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'load_more',
						'var_final_if' => 'no',
						'no_attribute' => 'yes',
					),
				),

				// Load More Option Type
				array(
					'option_type' => 'select',
					'label'       => __( 'Load more style', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_load_more_style',
					'name'        => 'instagram_load_more_style',
					'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
					'options'     => array(
						1 => array(
							'label' => __( 'Button', 'feed-them-social' ),
							'value' => 'button',
						),
						2 => array(
							'label' => __( 'AutoScroll', 'feed-them-social' ),
							'value' => 'autoscroll',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'loadmore',
						'var_final_if' => 'no',
						'var_final_value' => '',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-instagram-load-more-options-wrap',
					),
				),

				// Instagram Page Load more Amount
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more posts will load at a time.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'instagram_loadmore_count',
					'name'        => 'instagram_loadmore_count',
					'placeholder' => __( '5 is the default number', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_count',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_count=5',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-instagram-load-more-options2-wrap',

						// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
					),
				),

				// Instagram Page Load more Button Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'instagram_loadmore_button_width',
					'name'        => 'instagram_loadmore_button_width',
					'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_maxwidth',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_maxwidth=300px',
						'ifs' => 'load_more',
					),
				),

				// Facebook Page Load more Button Margin
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_loadmore_button_margin',
					'name'        => 'instagram_loadmore_button_margin',
					'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_margin',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_margin=10px',
						'ifs' => 'load_more',
					),
					'sub_options_end' => 2,
				),

				// Pop Up Option
				array(
					'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Display Photos & Videos', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'instagram_popup_option',
					'name'        => 'instagram_popup_option',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'popup',
					),
				),
			),
		);

		return $this->all_options['instagram'];
	}


	/**
	 * Twitter Options
	 *
	 * Options for the Twitter Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function twitter_options() {

		$this->all_options['twitter'] = array(
            'section_attr_key'   => 'twitter_',
            'section_title'      => __( 'Twitter Feed', 'feed-them-social' ),
            'section_wrap_class' => 'fts-twitter-shortcode-form',

            // Form Info
            'form_wrap_classes'  => 'twitter-shortcode-form',
            'form_wrap_id'       => 'fts-twitter-form',

            // Token Check
            'token_check'        => array(
                array(
                    'option_name'  => 'fts_twitter_custom_access_token_secret',
                    'no_token_msg' => __( '<strong>STEP 1:</strong> Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
                ),
            ),

            // Feed Type Selection
            'feed_type_select'   => array(
                'label'          => __( 'Feed Type', 'feed-them-social' ),
                'select_name'    => 'twitter-messages-selector',
                'select_id'      => 'twitter-messages-selector',
            ),

            // Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
            'main_options'       => array(

	            // Feed Type
	            array(
		            'option_type' => 'select',
		            'label'       => __( 'Feed Type', 'feed-them-social' ),
		            'type'        => 'text',
                    'input_wrap_class' => 'twitter-messages-selector',
		            'id'          => 'twitter-messages-selector',
		            'name'        => 'twitter-messages-selector',
		            'default_value'    => '',
		            'options'     => array(
			            array(
				            'label' => __( 'User Feed', 'feed-them-social' ),
				            'value' => 'user',
			            ),
			            array(
				            'label' => __( 'Hashtag, Search and more Feed', 'feed-them-social' ),
				            'value' => 'hashtag',
			            ),
		            ),
	            ),

                // Twitter Search Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'twitter_hashtag_etc_name',
                    'label'       => __( 'Twitter Search Name (required)', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_hashtag_etc_name',
                    'name'        => 'twitter_hashtag_etc_name',
                    'value'       => '',
                    'instructional-text' => __( 'You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social' ),

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'search',
                        'var_final_if' => 'no',
                        'empty_error' => 'yes',
                        'ifs' => 'twitter_search',
                        'empty_error_if' => array(
                            'attribute' => 'select#twitter-messages-selector',
                            'operator' => '==',
                            'value'    => 'hashtag',
                        ),
                    ),

                    // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'twitter-hashtag-etc-wrap',
                        'sub_options_title' => __( 'Search', 'feed-them-social' ),
                    ),
                    'sub_options_end' => true,
                ),

                // Twitter Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'twitter_name',
                    'label'       => __( 'Twitter Name', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_name',
                    'name'        => 'twitter_name',
                    'instructional-text' => '<span class="hashtag-option-small-text">' . __( 'Twitter Name is only required if you want to show a', 'feed-them-social' ) . ' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">' . __( 'Follow Button', 'feed-them-social' ) . '</a>.</span><span class="must-copy-twitter-name">' . __( 'You must copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __( 'Twitter Name', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ) . '</span>',
                    'value'       => '',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name'    => 'twitter_name',
                        'var_final_if' => 'no',
                        'empty_error'  => 'yes',
                        'empty_error_if' => array(
                            'attribute' => 'select#twitter-messages-selector',
                            'operator' => '==',
                            'value'    => 'user',
                        ),
                    ),
                ),

                // Tweet Count
                array(
                    'option_type' => 'input',
                    'label'       => __( '# of Tweets (optional)', 'feed-them-social' ) . $limitforpremium,
                    'type'        => 'text',
                    'id'          => 'tweets_count',
                    'name'        => 'tweets_count',

                    // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                    // 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                    'placeholder' => __( '6 is the default value', 'feed-them-social' ),
                    'value'       => '',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name'    => 'tweets_count',
                        'var_final_if' => 'yes',
                        'var_final_value' => 'no',
                        'empty_error'  => 'set',
                        'empty_error_value' => 'tweets_count=6',
                    ),
                ),

                // Twitter Fixed Height
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Twitter Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
                    'type'        => 'text',
                    'id'          => 'twitter_height',
                    'name'        => 'twitter_height',
                    'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),
                    'short_attr'  => array(
                        'attr_name'    => 'twitter_height',
                        'var_final_if' => 'yes',
                        'var_final_value' => '',
                        'empty_error'  => 'set',
                        'empty_error_value' => '',
                    ),
                ),

                // Show Cover Photo
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Show Cover Photo', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-cover-photo',
                    'name'        => 'twitter-cover-photo',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr'  => array(
                        'attr_name' => 'cover_photo',
                    ),
                ),

                // Show Stats Bar
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Stats Bar', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-stats-bar',
                    'name'        => 'twitter-stats-bar',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr'  => array(
                        'attr_name' => 'stats_bar',
                    ),
                ),

                // Show Retweets
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Show Retweets', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-show-retweets',
                    'name'        => 'twitter-show-retweets',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr'  => array(
                        'attr_name' => 'show_retweets',
                    ),
                ),

                // Show Replies
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Show Replies', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-show-replies',
                    'name'        => 'twitter-show-replies',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr'  => array(
                        'attr_name' => 'show_replies',
                    ),
                ),

                // Pop Up Option
                array(
                    'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
                    'option_type' => 'select',
                    'label'       => __( 'Display Photos & Videos', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-popup-option',
                    'name'        => 'twitter-popup-option',

                    // Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
                    'prem_req'    => 'yes',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin'  => 'fts_premium',
                    'short_attr'  => array(
                        'attr_name' => 'popup',
                        'ifs' => 'twitter_popup',
                    ),
                ),

                // ******************************************
                // Facebook Load More Options
                // ******************************************
                // Twitter Load More Button
                array(
                    'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
                    'option_type' => 'select',
                    'label'       => __( 'Load More Button', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_load_more_option',
                    'name'        => 'twitter_load_more_option',
                    'options'     => array(
                        array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'prem_req'    => 'yes',
                    'req_plugin'  => 'fts_premium',
                    'short_attr'  => array(
                        'attr_name'    => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'twitter-loadmore-wrap',
                    ),
                ),

                // Twitter Load More Style
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Load More Style', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_load_more_style',
                    'name'        => 'twitter_load_more_style',
                    'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
                    'options'     => array(
                        array(
                            'label' => __( 'Button', 'feed-them-social' ),
                            'value' => 'button',
                        ),
                        array(
                            'label' => __( 'AutoScroll', 'feed-them-social' ),
                            'value' => 'autoscroll',
                        ),
                    ),
                    'prem_req'    => 'yes',
                    'req_plugin'  => 'fts_premium',
                    'short_attr'  => array(
                        'attr_name' => 'loadmore',
                        'ifs' => 'load_more',
                    ),

                    // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-twitter-load-more-options-wrap',
                    ),
                    'sub_options_end' => true,
                ),

                // Twitter Page Load more Amount
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more posts will load at a time.', 'feed-them-social' ) . '</small>',
                    'type'        => 'text',
                    'id'          => 'twitter_loadmore_count',
                    'name'        => 'twitter_loadmore_count',
                    'placeholder' => __( '5 is the default number', 'feed-them-social' ),
                    'value'       => '',
                    'req_plugin'  => 'fts_premium',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'loadmore_count',
                        'empty_error' => 'set',
                        'empty_error_value' => 'loadmore_count=5',
                        'ifs' => 'load_more',
                    ),

                    // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-twitter-load-more-options2-wrap',
                    ),
                ),

                // Twitter Load more Button Width
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
                    'type'        => 'text',
                    'id'          => 'twitter_loadmore_button_width',
                    'name'        => 'twitter_loadmore_button_width',
                    'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
                    'value'       => '',
                    'prem_req'    => 'yes',
                    'req_plugin'  => 'fts_premium',
                    'or_req_plugin' => 'facebook_reviews',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'loadmore_btn_maxwidth',
                        'empty_error' => 'set',
                        'empty_error_value' => 'loadmore_btn_maxwidth=300px',
                        'ifs' => 'load_more',
                    ),
                ),

                // Twitter Load more Button Margin
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_loadmore_button_margin',
                    'name'        => 'twitter_loadmore_button_margin',
                    'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
                    'value'       => '',
                    'req_plugin'  => 'fts_premium',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'loadmore_btn_margin',
                        'empty_error' => 'set',
                        'empty_error_value' => 'loadmore_btn_margin=10px',
                        'ifs' => 'load_more',
                    ),
                    'sub_options_end' => 2,
                ),

                // ******************************************
                // Twitter Grid Options
                // ******************************************
                // Twitter Display Posts in Grid
                array(
                    'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
                    'input_wrap_class' => 'twitter-posts-in-grid-option-wrap',
                    'option_type' => 'select',
                    'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-grid-option',
                    'name'        => 'twitter-grid-option',
                    'options'     => array(
                        1 => array(
                            'label' => __( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin'  => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'short_attr'  => array(
                        'attr_name'    => 'grid',
                        'empty_error'  => 'set',
                        'set_operator' => '==',
                        'set_equals'   => 'yes',
                        'empty_error_value' => '',
                    ),

                    // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-grid-options-wrap',
                    ),
                ),

                // Grid Column Width
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Grid Column Width', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_grid_column_width',
                    'name'        => 'twitter_grid_column_width',
                    'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
                    'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
                    'value'       => '',
                    'req_plugin'  => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'colmn_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'colmn_width=310px',
                        'ifs' => 'grid',
                    ),

                    // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-twitter-grid-options-wrap',

                        // 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                    ),
                ),

                // Grid Spaces Between Posts
                array(
                    'option_type' => 'input',
                    'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter_grid_space_between_posts',
                    'name'        => 'twitter_grid_space_between_posts',
                    'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
                    'value'       => '',
                    'req_plugin'  => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',

                    // Relative to JS.
                    'short_attr'  => array(
                        'attr_name' => 'space_between_posts',
                        'empty_error' => 'set',
                        'empty_error_value' => 'space_between_posts=10px',
                        'ifs' => 'grid',
                    ),
                    'sub_options_end' => 2,
                ),
            ),
        );

		return $this->all_options['twitter'];
	}

	/**
	 * Youtube Options
	 *
	 * Options for the Youtube Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function youtube_options() {

		$this->all_options['youtube'] = array(
			'section_attr_key'   => 'youtube_',
			'section_title'      => __( 'Youtube Feed', 'feed-them-social' ),
			'section_wrap_class' => 'fts-youtube-shortcode-form',

			// Form Info
			'form_wrap_classes'  => 'youtube-shortcode-form',
			'form_wrap_id'       => 'fts-youtube-form',

			// Token Check
			'token_check'        => array(
				array(
					'option_name'  => 'youtube_custom_api_token',
					'no_token_msg' => __( '<strong>STEP 1:</strong> Please add your API Token or Access Token to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Youtube Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
				),
			),

			'main_options'       => array(

                // Feed Type
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Feed Type', 'feed-them-social' ),
                    'type'        => 'text',
                    'input_wrap_class' => 'youtube-messages-selector',
                    'id'          => 'youtube-messages-selector',
                    'name'        => 'youtube-messages-selector',
                    'default_value'    => '',
                    'options'     => array(
                        array(
                            'label' => __( 'Channel Feed', 'feed-them-social' ),
                            'value' => 'channelID',
                        ),
                        array(
                            'label' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ),
                            'value' => 'playlistID',
                        ),
                        array(
                            'label' => __( 'User\'s Specific Playlist', 'feed-them-social' ),
                            'value' => 'userPlaylist',
                        ),
                        array(
                            'label' => __( 'Single Video with title, date & description', 'feed-them-social' ),
                            'value' => 'singleID',
                        ),
                    ),
                ),

				// Youtube Name
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_name',
					'label'       => __( 'Youtube Username (required)', 'feed-them-social' ),
					'instructional-text' => __( 'You must copy your YouTube <strong>Username</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/channel/" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
					'type'        => 'text',
					'id'          => 'youtube_name',
					'name'        => 'youtube_name',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'username',
						'empty_error' => 'yes',
						'ifs' => 'username',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'username',
						),
					),
				),

				// Youtube Playlist ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_playlistID',
					'label'       => __( 'Youtube Playlist ID', 'feed-them-social' ),
					'instructional-text' => __( 'You must copy your YouTube <strong>Playlist</strong> and <strong>Channel</strong> url link and paste them below. Your urls should look similar to our Example urls below. <br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN</a><br/><strong>' . __( 'Channel ID:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA" target="_blank">https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA</a>',
					'type'        => 'text',
					'id'          => 'youtube_playlistID',
					'name'        => 'youtube_playlistID',
					'value'       => '',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'playlist_id',
						'empty_error' => 'yes',
						'ifs' => 'playlistID',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'playlistID',
						),
					),
				),

				// Youtube Playlist ID2
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_playlistID2',
					'label'       => __( 'Youtube Playlist ID ', 'feed-them-social' ),
					'instructional-text' => __( 'You must copy your YouTube <strong>Playlist</strong> and <strong>Username</strong> url and paste them below. Your urls should look similar to our Example urls below.<br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym" target="_blank">https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym</a><br/><strong>' . __( 'Username:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/user/nationalgeographic" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
					'type'        => 'text',
					'id'          => 'youtube_playlistID2',
					'name'        => 'youtube_playlistID2',
					'value'       => '',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'playlist_id',
						'empty_error' => 'yes',
						'ifs' => 'userPlaylist',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'userPlaylist',
						),
					),
				),

				// Youtube Name 2
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_name2',
					'label'       => __( 'Youtube Username<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_name2',
					'name'        => 'youtube_name2',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'username_subscribe_btn',
						'ifs' => 'userPlaylist',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'userPlaylist',
						),
					),
				),

				// Youtube Channel ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_channelID',
					'label'       => __( 'Youtube Channel ID', 'feed-them-social' ),
					'instructional-text' => __( 'You must copy your YouTube <strong>Channel</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw" target="_blank">https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw</a>',
					'type'        => 'text',
					'id'          => 'youtube_channelID',
					'name'        => 'youtube_channelID',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'channel_id',
						'ifs' => 'channelID',
						'empty_error' => 'yes',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'channelID',
						),
					),
				),

				// Youtube Channel ID 2
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_channelID2',
					'label'       => __( 'Youtube Channel ID<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_channelID2',
					'name'        => 'youtube_channelID2',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'channel_id',
						'ifs' => 'playlistID',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'playlistID',
						),
					),
				),

				// Youtube Single Video ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_singleVideoID',
					'label'       => __( 'Single Youtube Video ID', 'feed-them-social' ),
					'instructional-text' => __( 'You must copy your <strong>YouTube Video</strong> url link and paste it below. Your url should look similar to our Example url below. <br/><strong>Video URL:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0</a>',
					'type'        => 'text',
					'id'          => 'youtube_singleVideoID',
					'name'        => 'youtube_singleVideoID',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'video_id_or_link',
						'ifs' => 'singleID',
						'empty_error' => 'yes',
						'empty_error_if' => array(
							'attribute' => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'singleID',
						),
					),
				),

				// # of videos
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'youtube_vid_count',
					'label'       => __( '# of videos', 'feed-them-social' ) . $limitforpremium,
					'type'        => 'text',
					'id'          => 'youtube_vid_count',
					'name'        => 'youtube_vid_count',
					'placeholder' => __( '4 is the default value', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'   => 'vid_count',
						'empty_error' => 'set',
						'empty_error_value' => 'vid_count=4',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-youtube-first-video-wrap',
					),
				),

				// Display First video full size
				array(
					'grouped_options_title' => __( 'First Video Display', 'feed-them-social' ),
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'Display First video full size', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_first_video',
					'name'        => 'youtube_first_video',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'large_vid',
					),
					'sub_options_end' => true,
				),

				// Display Large Video Title
				array(
					'option_type' => 'select',
					'input_wrap_class' => 'youtube_hide_option',
					'label'       => __( 'Show Large Video Title', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_large_vid_title',
					'name'        => 'youtube_large_vid_title',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'large_vid_title',
					),
				),

				// Display Large Video Description
				array(
					'option_type' => 'select',
					'input_wrap_class' => 'youtube_hide_option',
					'label'       => __( 'Show Large Video Description', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_large_vid_description',
					'name'        => 'youtube_large_vid_description',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'large_vid_description',
					),
				),

				// Play thumbs in large video container
				array(
					'grouped_options_title' => __( 'Video Thumbnails', 'feed-them-social' ),
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'Click thumb to play Video', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_play_thumbs',
					'name'        => 'youtube_play_thumbs',
					'options'     => array(
						array(
							'label' => __( 'Play on Page', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'Open in YouTube', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Open in Popup (Premium Version Required)', 'feed-them-social' ),
							'value' => 'popup',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'thumbs_play_in_iframe',
					),
				),

				// # of videos in each row
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( '# of videos in each row', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_columns',
					'name'        => 'youtube_columns',
					'default_value' => '4',
					'options'     => array(
						array(
							'label' => __( '1', 'feed-them-social' ),
							'value' => '1',
						),
						array(
							'label' => __( '2', 'feed-them-social' ),
							'value' => '2',
						),
						array(
							'label' => __( '3', 'feed-them-social' ),
							'value' => '3',
						),
						array(
							'label' => __( '4', 'feed-them-social' ),
							'value' => '4',
						),
						array(
							'label' => __( '5', 'feed-them-social' ),
							'value' => '5',
						),
						array(
							'label' => __( '6', 'feed-them-social' ),
							'value' => '6',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'vids_in_row',
					),
				),

				// omit first video thumbnail
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'Hide the first thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Useful if playing videos on the page.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_omit_first_thumbnail',
					'name'        => 'youtube_omit_first_thumbnail',
					'default_value' => 'no',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'omit_first_thumbnail',
					),
				),

				// Space between Vids
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'input',
					'label'       => __( 'Space between video thumbnails', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'fts-slicker-youtube-container-margin',
					'name'        => 'fts-slicker-youtube-container-margin',
					'placeholder' => '1px is the default value',
					'value'       => '',
					'short_attr'  => array(
						'attr_name'    => 'space_between_videos',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => 'space_between_videos=1px',
					),
				),

				// Force Video Rows
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'Force thumbnails rows', 'feed-them-social' ) . '<br/><small>' . __( 'No, will allow the video images to be responsive for smaller devices. Yes, will force the selected rows.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'fts_youtube_force_columns',
					'name'        => 'fts_youtube_force_columns',
					'default_value' => 'no',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'force_columns',
					),
				),

				// Display Max Res Images for thumbs
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'High quality thumbnail images', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_maxres_thumbnail_images',
					'name'        => 'youtube_maxres_thumbnail_images',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'maxres_thumbnail_images',
					),
				),

				// Background color for thumbs container
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type'  => 'input',
					'color_picker' => 'yes',
					'label'        => __( 'Container Background color ', 'feed-them-social' ),
					'type'         => 'text',
					'id'           => 'youtube_thumbs_wrap_color',
					'name'         => 'youtube_thumbs_wrap_color',
					'default'      => '#000',
					'placeholder'  => '#000',

					// Relative to JS.
					'short_attr'   => array(
						'attr_name'   => 'thumbs_wrap_color',
						'empty_error' => 'set',
						'empty_error_value' => 'thumbs_wrap_color=#000',
					),
				),

				// Align container right or left of video
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'option_type' => 'select',
					'label'       => __( 'Align Thumbs', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Videoo', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_thumbs_wrap',
					'name'        => 'youtube_thumbs_wrap',
					'options'     => array(
						array(
							'label' => __( 'Below Video', 'feed-them-social' ),
							'value' => 'none',
						),
						array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
						array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'wrap',
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
				),

				// Align container right or left of video
				array(
					'input_wrap_class' => 'youtube_align_comments_wrap',
					'option_type' => 'select',
					'label'       => __( 'Align Title, Description etc.', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Video', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_comments_wrap',
					'name'        => 'youtube_comments_wrap',
					'options'     => array(
						array(
							'label' => __( 'Below Video', 'feed-them-social' ),
							'value' => 'none',
						),
						array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
						array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'wrap_single',
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
				),

				// Align container right or left of video
				array(
					'input_wrap_class' => 'youtube_video_thumbs_display',
					'option_type' => 'select',
					'label'       => __( 'Video/Thumbs width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_video_thumbs_display',
					'name'        => 'youtube_video_thumbs_display',
					'options'     => array(
						array(
							'label' => __( 'None', 'feed-them-social' ),
							'value' => 'none',
						),
						array(
							'label' => __( 'Option 1 (Video 80%, Thumbs Container 20%)', 'feed-them-social' ),
							'value' => '1',
						),
						array(
							'label' => __( 'Option 1 (Video 60%, Thumbs Container 40%)', 'feed-them-social' ),
							'value' => '2',
						),
						array(
							'label' => __( 'Option 1 (Video 50%, Thumbs Container 50%)', 'feed-them-social' ),
							'value' => '3',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'video_wrap_display',
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
				),

				// Align container right or left of video
				array(
					'input_wrap_class' => 'youtube_video_single_info_display',
					'option_type' => 'select',
					'label'       => __( 'Video/Info width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_video_comments_display',
					'name'        => 'youtube_video_comments_display',
					'options'     => array(
						array(
							'label' => __( 'None', 'feed-them-social' ),
							'value' => 'none',
						),
						array(
							'label' => __( 'Option 1 (Video 80%, Info Container 20%)', 'feed-them-social' ),
							'value' => '1',
						),
						array(
							'label' => __( 'Option 1 (Video 60%, Info Container 40%)', 'feed-them-social' ),
							'value' => '2',
						),
						array(
							'label' => __( 'Option 1 (Video 50%, Info Container 50%)', 'feed-them-social' ),
							'value' => '3',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'video_wrap_display_single',
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
				),

				// Youtube Load More Button
				array(
					'input_wrap_class' => 'youtube_hide_option',
					'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Load More Button', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_load_more_option',
					'name'        => 'youtube_load_more_option',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'youtube-loadmore-wrap',
					),
				),

				// Youtube Load More Style
				array(
					'option_type' => 'select',
					'label'       => __( 'Load More Style', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_load_more_style',
					'name'        => 'youtube_load_more_style',
					'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
					'options'     => array(
						array(
							'label' => __( 'Button', 'feed-them-social' ),
							'value' => 'button',
						),
						array(
							'label' => __( 'AutoScroll', 'feed-them-social' ),
							'value' => 'autoscroll',
						),
					),
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',
					'short_attr'  => array(
						'attr_name' => 'loadmore',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-youtube-load-more-options-wrap',
					),
					'sub_options_end' => true,
				),

				// youtube Page Load more Amount
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more videos will load at a time.', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_loadmore_count',
					'name'        => 'youtube_loadmore_count',
					'placeholder' => __( '5 is the default number', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_count',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_count=5',
						'ifs' => 'load_more',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'fts-youtube-load-more-options2-wrap',
					),
				),

				// youtube Load more Button Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_loadmore_button_width',
					'name'        => 'youtube_loadmore_button_width',
					'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'prem_req'    => 'yes',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_maxwidth',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_maxwidth=300px',
						'ifs' => 'load_more',
					),
				),

				// youtube Load more Button Margin
				array(
					'option_type' => 'input',
					'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'youtube_loadmore_button_margin',
					'name'        => 'youtube_loadmore_button_margin',
					'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'fts_premium',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'loadmore_btn_margin',
						'empty_error' => 'set',
						'empty_error_value' => 'loadmore_btn_margin=10px',
						'ifs' => 'load_more',
					),
					'sub_options_end' => 2,
				),

				// Display Comments
				array(
					'grouped_options_title' => __( 'Comments', 'feed-them-social' ),
					'option_type' => 'input',
					'label'       => __( '# of Comments', 'feed-them-social' ) . '<br/><small>' . __( 'Maximum amount is 50', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'youtube_comments_count',
					'name'        => 'youtube_comments_count',
					'placeholder' => '',
					'value'       => '',
					'short_attr'  => array(
						'attr_name'   => 'comments_count',
						'empty_error' => 'set',
						'empty_error_value' => 'comments_count=0',
					),
					'req_plugin'  => 'fts_premium',
				),
			),
		);

		return $this->all_options['youtube'];
	}


	/**
	 * Combined Options
	 *
	 * Options for the Combined Streams Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function combine_options() {

		$this->all_options['combine'] = array(
			'section_attr_key'   => 'combine_',
			'section_title'      => __( 'Combine Streams Feed', 'feed-them-social' ),
			'section_wrap_class' => 'fts-combine-streams-shortcode-form',
			// Form Info
			'form_wrap_classes'  => 'combine-streams-shortcode-form',
			'form_wrap_id'       => 'fts-combine-steams-form',

			// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
			'main_options'       => array(

				// Combined Total # of Posts
				array(
					'option_type' => 'input',
                    'input_wrap_class' => 'combine_post_count',
					'label'       => __( 'Combined Total # of Posts', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_post_count',
					'name'        => 'combine_post_count',
					'value'       => '',
					'placeholder' => __( '6 is the default number', 'feed-them-social' ),
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'posts',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => 'posts=6',
					),
				),

				// # of Posts per Social Network
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_social_network_post_count',
					'label'       => __( '# of Posts per Social Network', 'feed-them-social' ) . '<br/><small>' . __( 'NOT the combined total', 'feed-them-social' ) . '</small>',
					'type'        => 'text',

					// 'instructional-text' => __('', 'feed-them-social'),
					'id'          => 'combine_social_network_post_count',
					'name'        => 'combine_social_network_post_count',
					'value'       => '',
					'placeholder' => __( '1 is the default number', 'feed-them-social' ),
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'social_network_posts',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => 'social_network_posts=1',
					),
				),

				// Facebook Amount of words
				array(
					'option_type' => 'input',
					'label'       => __( 'Amount of words per post', 'feed-them-social' ) . '<br/><small>' . __( 'Type 0 to remove the posts description', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_word_count_option',
					'name'        => 'combine_word_count_option',
					'placeholder' => '45 ' . __( 'is the default number', 'feed-them-social' ),
					'value'       => '',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'   => 'words',
						'empty_error' => 'set',
						'empty_error_value' => 'words=45',
					),
				),

				// Center Container
				array(
					'option_type' => 'select',
					'label'       => __( 'Center Feed Container', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_container_position',
					'name'        => 'combine_container_position',
					'options'     => array(
						1 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => 'center_container',
					),
				),

				// Page Fixed Height
				array(
					'input_wrap_class' => 'combine_height',
					'option_type' => 'input',
					'label'       => __( 'Feed Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_height',
					'name'        => 'combine_height',
					'value'       => '',
					'req_plugin'  => 'combine_streams',
					'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'height',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),

				// Background Color
				array(
					'option_type'  => 'input',
					'color_picker' => 'yes',
					'input_wrap_class' => 'combine_background_color',
					'label'        => __( 'Background Color', 'feed-them-social' ),
					'type'         => 'text',
					'id'           => 'combine_background_color',
					'name'         => 'combine_background_color', // Relative to JS.
					'req_plugin'   => 'combine_streams',
					'short_attr'   => array(
						'attr_name'    => 'background_color',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),

				// Social Icon
				array(
					'input_wrap_class' => 'combine_show_social_icon',
					'option_type' => 'select',
					'label'       => __( 'Show Social Icon', 'feed-them-social' ) . '<br/><small>' . __( 'Right, Left or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_show_social_icon',
					'name'        => 'combine_show_social_icon',
					'req_plugin'  => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Right', 'feed-them-social' ),
							'value' => 'right',
						),
						array(
							'label' => __( 'Left', 'feed-them-social' ),
							'value' => 'left',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'facebook_show_social_icon',
					),
				),

				// Show Description below image or video Name
				array(
					'input_wrap_class' => 'combine_show_media',
					'option_type' => 'select',
					'label'       => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default) or Top of Post', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_show_media',
					'name'        => 'combine_show_media',
					'req_plugin'  => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
							'value' => 'bottom',
						),
						array(
							'label' => __( 'Above Username, Date & Description', 'feed-them-social' ),
							'value' => 'top',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_media',
					),
				), // Show Username
				array(
					'input_wrap_class' => 'combine_hide_date',
					'option_type' => 'select',
					'label'       => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_hide_date',
					'name'        => 'combine_hide_date',
					'req_plugin'  => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_date',
					),
				),

				// Show Date
				array(
					'input_wrap_class' => 'combine_hide_name',
					'option_type' => 'select',
					'label'       => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
					'type'        => 'text',
					'id'          => 'combine_hide_name',
					'name'        => 'combine_hide_name',
					'req_plugin'  => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'show_name',
					),
				),

				// Padding
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_padding',
					'label'       => __( 'Padding', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_padding',
					'name'        => 'combine_padding',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'padding',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
				),

				// Combine Facebook
				array(
					'grouped_options_title' => __( 'Facebook', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Combine Facebook', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_facebook',
					'name'        => 'combine_facebook',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
						'ifs' => 'combine_facebook',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-combine-facebook-wrap',
					),
				),

				// Combine Facebook ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_facebook_name',
					'label'       => __( 'Facebook ID or Name', 'feed-them-social' ),
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-facebook-feed-styles-submenu-page" target="_blank">Facebook Options</a> page. If that is in place your page ID should appear in the input below.',
					'type'        => 'text',
					'id'          => 'combine_facebook_name',
					'name'        => 'combine_facebook_name',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'facebook_name',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-facebook-wrap',
					),
					'sub_options_end' => true,
				),

				// Combine Twitter
				array(
					'grouped_options_title' => __( 'Twitter', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Combine Twitter', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_twitter',
					'name'        => 'combine_twitter',
					'req_plugin'  => 'combine_streams',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-combine-twitter-wrap',
					),
				),

				// Feed Type Selection
				array(
					'option_type' => 'select',
					'label'       => __( 'Feed Type', 'feed-them-social' ),
					'select_wrap_classes' => 'combine-twitter-gen-selection',
					'select_classes' => '',
					'name'        => 'combine-twitter-messages-selector',
					'id'          => 'combine-twitter-messages-selector',
					'req_plugin'  => 'combine_streams',

					// Feed Types and their options
					'options'     => array(

						// User Feed
						array(
							'value' => 'user',
							'label' => __( 'User Feed', 'feed-them-social' ),
						),

						// hastag Feed
						array(
							'value' => 'hashtag',
							'label' => __( 'Hashtag, Search and more Feed', 'feed-them-social' ),
						),
					),
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-twitter-wrap',
					),
				),

				// 'short_attr_final' => 'yes',
				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				// Twitter Search Name

				// MUST ADD TWITTER DIRECTIONS FOR SEARCH

				// If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. from:user_name%#YourHashtag
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_twitter_hashtag_etc_name',
					'label'       => __( 'Twitter Search Name (required)', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_twitter_hashtag_etc_name',
					'name'        => 'combine_twitter_hashtag_etc_name',
					'value'       => '',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'search',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
						'empty_error_if' => array(
							'attribute' => 'select#combine-twitter-messages-selector',
							'operator' => '==',
							'value'    => 'hashtag',
						),
					),
					'req_plugin'  => 'combine_streams',

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-twitter-hashtag-etc-wrap',
						// 'sub_options_title' => __( 'Twitter Search', 'feed-them-social' ),
					),
					'sub_options_end' => true,
				),

				// Twitter Name
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_twitter_name',
					'label'       => __( 'Twitter Name', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_twitter_name',
					'name'        => 'combine_twitter_name',
					'instructional-text' => '<span class="must-copy-twitter-name">' . __( 'You must copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __( 'Twitter Name', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ) . '</span>',
					'value'       => '',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'twitter_name',
						'var_final_if' => 'yes',
						'empty_error'  => 'set',
						'empty_error_value' => '',
						'empty_error_if' => array(
							'attribute' => 'select#combine-twitter-messages-selector',
							'operator' => '==',
							'value'    => 'user',
						),
					),
					'req_plugin'  => 'combine_streams',
					'sub_options_end' => 2,
				),

				// Combine Instagram
				array(
					'grouped_options_title' => __( 'Instagram', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Combine Instagram', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_instagram',
					'name'        => 'combine_instagram',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-combine-instagram-wrap',
					),
				),

				// Instagram Type
				array(
					'input_wrap_class' => 'combine_instagram_type',
					'option_type' => 'select',
					'label'       => __( 'Instagram Type', 'feed-them-social' ),
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-instagram-feed-styles-submenu-page" target="_blank">Instagram Options</a>.',
					'type'        => 'text',
					'id'          => 'combine_instagram_type',
					'name'        => 'combine_instagram_type',
					'options'     => array(

						// Basic Feed
						array(
							'value' => 'basic',
							'label' => __( 'Basic Feed', 'feed-them-social' ),
						),
						// Business Feed
						array(
							'value' => 'business',
							'label' => __( 'Business Feed', 'feed-them-social' ),
						),
						// Hastag Feed
						array(
							'value' => 'hashtag',
							'label' => __( 'Hashtag Feed', 'feed-them-social' ),
						),

						// location Feed
						//array(
						//	'value' => 'location',
						//	'label' => __( 'Location Feed', 'feed-them-social' ),
						// ),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => 'instagram_type',
						'ifs' => 'combine_instagram',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-instagram-wrap',
					),
				),

				// Combine Convert Instagram Name
				// array(
				// 'option_type' => 'input',
				// 'input_wrap_class' => 'combine-instagram-id-option-wrap',
				// 'label' => __('Convert Instagram Name to ID', 'feed-them-social'),
				// 'type' => 'text',
				// 'id' => 'combine_convert_instagram_username',
				// 'name' => 'combine_convert_instagram_username',
				// 'instructional-text' => __('You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below', 'feed-them-social'),
				// 'req_plugin' => 'combine_streams',
				// Relative to JS.
				// 'short_attr' => array(
				// 'attr_name' => '',
				// 'ifs' => 'combine_instagram',
				// 'no_attribute' => 'yes'
				// ),
				// ),
				// Instagram ID
				array(
					'option_type' => 'input',

					// 'input_wrap_class' => 'combine_instagram_name',
					'label'       => array(
						1 => array(
							'text' => __( 'Instagram ID # (required)', 'feed-them-social' ),
							'class' => 'combine-instagram-user-option-text',
						),
						2 => array(
							'text' => __( 'Instagram ID # (required)', 'feed-them-social' ),
							'class' => 'combine-instagram-hashtag-option-text',
						)
					),
					'type'        => 'text',
					'id'          => 'combine_instagram_name',
					'name'        => 'combine_instagram_name',
					'required'    => 'combine_streams',
					'instructional-text' => array(
						1 => array(
							'text' => __( '<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social' ),
							'class' => 'combine-instagram-user-option-text',
						),
						2 => array(
							'text' => __( '<div class="fts-insta-info-plus-wrapper">If your Hashtag Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social' ),
							'class' => 'combine-instagram-hashtag-option-text',
						)
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'instagram_name',
						'ifs' => 'combine_instagram',
						'var_final_if' => 'no',
						'empty_error' => 'set',
						'empty_error_value' => '',
					)
				),


				// Instagram Hashtag
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_instagram_hashtag',
					'label'       => array(
						1 => array(
							'text' => __( 'Hashtag (required)', 'feed-them-social' ),
							'class' => 'combine-instagram-hashtag-option-text',
						),
					),
					'type'        => 'text',
					'id'          => 'combine_instagram_hashtag',
					'name'        => 'combine_instagram_hashtag',
					'required'    => 'yes',
					'instructional-text' => array(
						1 => array(
							'text' => __( 'Add your hashtag below. <strong>DO NOT</strong> add the #, just the name. Only one hashtag allowed at this time. Hashtag media only stays on Instagram for 24 hours and the API does not give us a date/time. That also means if you decide to combine this feed these media posts will appear before any other posts because we cannot sort them by date. In order to use the Instagram hashtag feed you must have your Instagram account linked to a Facebook Business Page. <a target="_blank" href="https://www.slickremix.com/docs/link-instagram-account-to-facebook/">Read Instructions.</a>', 'feed-them-social' ),
							'class' => 'combine-instagram-hashtag-option-text',
						),
					),

					// Relative to JS.
					'short_attr'  => array(
						'attr_name'    => 'hashtag',
						'var_final_if' => 'no',
						'empty_error'  => 'yes',
					),
				),


				// Hashtag Type
				array(
					'option_type' => 'select',
					'input_wrap_class' => 'combine_instagram_hashtag_type',
					'label'       => __( 'Hashtag Search Type', 'feed-them-social' ),

					'type'        => 'text',
					'id'          => 'combine_instagram_hashtag_type',
					'name'        => 'combine_instagram_hashtag_type',
					'class'       => 'combine_instagram-hashtag-type',
					'options'     => array(
						1 => array(
							'label' => __( 'Recent Media', 'feed-them-social' ),
							'value' => 'recent-media',
						),
						2 => array(
							'label' => __( 'Top Media (Most Interactions)', 'feed-them-social' ),
							'value' => 'top-media',
						),
					),
					'short_attr'  => array(
						'attr_name' => 'instagram_search',
					),
					'sub_options_end' => 2,
				),

				// Combine Pinterest
				/* array(
					'grouped_options_title' => __( 'Pinterest', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Combine Pinterest', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_pinterest',
					'name'        => 'combine_pinterest',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-combine-pinterest-wrap',
					),
				), */

				// Pinterest Type
				array(
					'input_wrap_class' => 'combine_pinterest_type',
					'option_type' => 'select',
					'label'       => __( 'Pinterest Type', 'feed-them-social' ),
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-pinterest-feed-styles-submenu-page" target="_blank">Pinterest Options</a> page then copy your <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">Pinterest and or Board Name</a> and paste them below based on your selection. A users board list is not available in this feed.',
					'type'        => 'text',
					'id'          => 'combine_pinterest_type',
					'name'        => 'combine_pinterest_type',
					'options'     => array(

						// Single Board Pins
						array(
							'label' => __( 'Latest Pins from a User', 'feed-them-social' ),
							'value' => 'pins_from_user',
						),

						// Single Board Pins
						array(
							'label' => __( 'Pins From a Specific Board', 'feed-them-social' ),
							'value' => 'single_board_pins',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => 'pinterest_type',
						'ifs' => 'combine_pinterest',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-pinterest-wrap',
					),
				),

				// Pinterest Name
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_pinterest_name',
					'label'       => __( 'Pinterest Name', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_pinterest_name',
					'name'        => 'combine_pinterest_name',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'pinterest_name',
						'ifs' => 'combine_pinterest',
						'var_final_if' => 'yes',
						'empty_error' => 'set',
						'empty_error_value' => '',
					),
				),

				// Pinterest Board ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_board_id',
					'label'       => __( 'Pinterest Board ID', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_board_id',
					'name'        => 'combine_board_id',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'board_id',
						'ifs' => 'pinterest_single_board_pins',
					),
					'sub_options_end' => 2,
				),

				// Combine Youtube
				array(
					'grouped_options_title' => __( 'Youtube', 'feed-them-social' ),
					'option_type' => 'select',
					'label'       => __( 'Combine Youtube', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_youtube',
					'name'        => 'combine_youtube',
					'options'     => array(
						array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name'    => '',
						'empty_error_value' => '',
						'no_attribute' => 'yes',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'main-combine-youtube-wrap',
					),
				),

				// Youtube Type
				array(
					'input_wrap_class' => 'combine_youtube_type',
					'option_type' => 'select',
					'label'       => __( 'Youtube Type', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_youtube_type',
					'name'        => 'combine_youtube_type',
					'options'     => array( // Channel Feed
						array(
							'label' => __( 'Channel Feed', 'feed-them-social' ),
							'value' => 'channelID',
						), // Channel Playlist Feed
						array(
							'label' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ),
							'value' => 'playlistID',
						),

						// User's Most Recent Videos
						array(
							'label' => __( 'User\'s Most Recent Videos', 'feed-them-social' ),
							'value' => 'username',
						),

						// User's Playlist
						array(
							'label' => __( 'User\'s Specific Playlist', 'feed-them-social' ),
							'value' => 'userPlaylist',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name' => '',
						'no_attribute' => 'yes',
						'ifs' => 'combine_youtube',
					),
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-youtube-wrap',
					),
				),

				// Youtube Name
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_youtube_name',
					'label'       => __( 'YouTube Username', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_youtube_name',
					'name'        => 'combine_youtube_name',
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Username</a> and paste it below.',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'youtube_name',
						'ifs' => 'combine_youtube',
						'var_final_if' => 'yes',
						'empty_error' => 'set',
						'empty_error_value' => '',
					),
				),

				// YouTube Playlist ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_playlist_id',
					'label'       => __( 'YouTube Playlist ID', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_playlist_id',
					'name'        => 'combine_playlist_id',
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Playlist ID</a> and paste them below.',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'playlist_id',
						'ifs' => 'combine_youtube',
					),
				),

				// YouTube Channel ID
				array(
					'option_type' => 'input',
					'input_wrap_class' => 'combine_channel_id',
					'label'       => __( 'YouTube Channel ID', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_channel_id',
					'name'        => 'combine_channel_id',
					'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Channel ID</a> and paste it below.',
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'channel_id',
						'ifs' => 'combine_youtube',
					),
					'sub_options_end' => 2,
				),

				// ******************************************
				// Combine Streams Grid Options
				// ******************************************
				// Facebook Page Display Posts in Grid
				array(
					'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
					'input_wrap_class' => 'combine_grid_option',
					'option_type' => 'select',
					'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_grid_option',
					'name'        => 'combine_grid_option',
					'options'     => array(
						1 => array(
							'label' => __( 'No', 'feed-them-social' ),
							'value' => 'no',
						),
						2 => array(
							'label' => __( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
					),
					'req_plugin'  => 'combine_streams',
					'short_attr'  => array(
						'attr_name'    => 'grid',
						'empty_error'  => 'set',
						'set_operator' => '==',
						'set_equals'   => 'yes',
						'empty_error_value' => '',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-main-grid-options-wrap',
					),
				),

				// Grid Column Width
				array(
					'option_type' => 'input',
					'label'       => __( 'Grid Column Width', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_grid_column_width',
					'name'        => 'combine_grid_column_width',
					'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
					'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'column_width',
						'empty_error' => 'set',
						'empty_error_value' => 'column_width=310px',
						'ifs' => 'combine_grid',
					),

					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options' => array(
						'sub_options_wrap_class' => 'combine-grid-options-wrap',
					),
				),

				// Grid Spaces Between Posts
				array(
					'option_type' => 'input',
					'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
					'type'        => 'text',
					'id'          => 'combine_grid_space_between_posts',
					'name'        => 'combine_grid_space_between_posts',
					'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
					'req_plugin'  => 'combine_streams',

					// Relative to JS.
					'short_attr'  => array(
						'attr_name' => 'space_between_posts',
						'empty_error' => 'set',
						'empty_error_value' => 'space_between_posts=10px',
						'ifs' => 'combine_grid',
					),
					'sub_options_end' => 2,
				),
			),
		);

		return $this->all_options['combine'];
	}
}
