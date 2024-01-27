<?php
/**
 * Twitter Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Twitter_Additional_Options
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
 * Class Twitter_Additional_Options
 */
class Twitter_Additional_Options {

	/**
	 * All Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Twitter_Add_Options constructor.
	 */
	public function __construct() {
		$this->follow_btn_options();
        $this->language_options();
		// $this->video_player_options();
		$this->profile_photo_options();
		$this->style_options();
		$this->grid_style_options();
		$this->load_more_options();
	}

	/**
	 * All Twitter Additional Options
	 *
	 * Function to return all Twitter Additional Options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_options() {

		return $this->all_options;

	}

	/**
	 * Twitter Follow Button Options
	 *
	 * Options for the Twitter Follow Buttons.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function follow_btn_options() {
		$this->all_options['twitter_follow_btn_options'] = array(
			'section_attr_key'   => 'twitter_follow_btn_options_',
			//'section_title'      => esc_html__( 'Follow Button Options', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(

                // Show Stats Bar
                array(
                    'option_type' => 'select',
                    'label'       => __( 'Stats Bar', 'feed-them-social' ),
                    'type'        => 'text',
                    'id'          => 'twitter-stats-bar',
                    'name'        => 'twitter_stats_bar',
                    'default_value'    => 'yes',
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
                        'attr_name' => 'stats_bar',
                    ),
                ),

                // Show Follow Button.
                array(
                    'input_wrap_class' => 'tiktok-show-stats-profile-photo tiktok-stats-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Show Stats Profile Photo', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_show_stats_profile_photo',
                    'name'             => 'tiktok_show_stats_profile_photo',
                    'default_value'    => 'yes',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                    ),
                ),

                // Show Follow Button.
                array(
                    'input_wrap_class' => 'tiktok_show_stats_follow_btn tiktok-stats-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Show Stats Follow Button', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_show_stats_follow_btn',
                    'name'             => 'tiktok_show_stats_follow_btn',
                    'default_value'    => 'yes',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                    ),
                ),

                // Show Follow Count Singular.
                array(
                    'input_wrap_class' => 'tiktok_show_follow_button_inline tiktok-stats-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Show Stats Follow Button Inline', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_show_follow_button_inline',
                    'name'             => 'tiktok_show_follow_button_inline',
                    'default_value'    => 'yes',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                    ),
                ),
                // Show Follow Count Singular.
                array(
                    'input_wrap_class' => 'twitter_show_follow_count tiktok-stats-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Show Stats Counts', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_show_stats_counts',
                    'name'             => 'tiktok_show_stats_counts',
                    'default_value'    => 'yes',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                    ),
                ),
				// Show Button where.
				array(
					'input_wrap_class' => 'tiktok_show_stats_description tiktok-stats-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Stats Description', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'tiktok_show_stats_description',
					'name'             => 'tiktok_show_stats_description',
					'default_value'    => 'yes',
					'options'          => array(
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
					),
				),




                // Show Follow Button.
                array(
                    'input_wrap_class' => 'twitter_show_follow_btn tiktok-show-follow-button-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'twitter_show_follow_btn',
                    'name'             => 'twitter_show_follow_btn',
                    'default_value'    => 'no',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
                        array(
                            'label' => esc_html__( 'Yes', 'feed-them-social' ),
                            'value' => 'yes',
                        ),
                    ),
                ),
                // Show Button where.
                array(
                    'input_wrap_class' => 'tiktok_show_stats_description tiktok-show-follow-button-hide',
                    'option_type'      => 'select',
                    'label'            => esc_html__( 'Follow Button Position', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'twitter_show_follow_btn_where',
                    'name'             => 'twitter_show_follow_btn_where',
                    'default_value'    => 'yes',
                    'options'          => array(
                        array(
                            'label' => esc_html__( 'Above Feed', 'feed-them-social' ),
                            'value' => 'twitter-follow-above',
                        ),
                        array(
                            'label' => esc_html__( 'Below Feed', 'feed-them-social' ),
                            'value' => 'twitter-follow-below',
                        ),
                    ),
                ),
			),
		);

		return $this->all_options['twitter_follow_btn_options'];
	} //END Twitter Follow Button Options.

    /**
     * Twitter Video Player Options
     *
     * Options for Video Player.
     *
     * @return mixed
     * @since 1.0.0
     */
    public function language_options() {
        $this->all_options['twitter_language_options'] = array(
            'section_attr_key'   => 'twitter_language_options_',
            //section_title'      => esc_html__( 'Video Player Options', 'feed-them-social' ),
            'section_wrap_id' => 'fts-tab-content1',
            'section_wrap_class' => 'fts-tab-content',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form',
            'form_wrap_id'       => 'fts-fb-page-form',
            //Options Wrap Class
            'options_wrap_class'       => 'fts-cpt-additional-options',


            'main_options'       => array(

                // Follow on TikTok Text.
                array(
                    'input_wrap_class' => 'tiktok_language',
                    'grouped_options_title' => __( 'Language Options', 'feed-them-social' ),
                    'input_wrap_class' => 'tiktok_follow_on_tiktok_fts',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Follow Button Text', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_follow_on_tiktok',
                    'name'             => 'tiktok_follow_on_tiktok',
                    'placeholder'      => 'Follow on TikTok',
                    'default_value'    => esc_html__( 'Follow on TikTok', 'feed-them-social' ),
                ),

                // View on TikTok Text.
                array(
                    'input_wrap_class' => 'tiktok_language',
                    'input_wrap_class' => 'tiktok_view_on_tiktok_fts',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'View Link Text', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'tiktok_view_on_tiktok',
                    'name'             => 'tiktok_view_on_tiktok',
                    'placeholder'      => 'View on TikTok',
                    'default_value'    => esc_html__( 'View on TikTok', 'feed-them-social' ),
                ),
            ),
        );

        return $this->all_options['twitter_language_options'];
    } //END Twitter Language Options

	/**
	 * Twitter Video Player Options
	 *
	 * Options for Video Player.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function video_player_options() {
		$this->all_options['twitter_video_player_options'] = array(
			'section_attr_key'   => 'twitter_video_player_options_',
			//section_title'      => esc_html__( 'Video Player Options', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',


			'main_options'       => array(

				// Show Follow Button.
				array(
					'input_wrap_class' => 'twitter_allow_videos',
                    'grouped_options_title' => __( 'Video Player Options', 'feed-them-social' ),
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show videos', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_allow_videos',
					'name'             => 'twitter_allow_videos',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed-them-social' ),
							'value' => 'no',
						),

					),
				),
			),
		);

		return $this->all_options['twitter_video_player_options'];
	} //END Twitter Video Player Options

	/**
	 * Twitter Profile Photo Options
	 *
	 * Options for Profile Photo.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function profile_photo_options() {
		$this->all_options['twitter_profile_photo_options'] = array(
			'section_attr_key'   => 'twitter_profile_photo_options_',
			//'section_title'      => esc_html__( 'Profile Photo', 'feed-them-social' ),
			//'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'twitter-page-shortcode-form',
			'form_wrap_id'       => 'fts-twitter-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',

			'main_options'       => array(

				// Hide Profile Photo.
				array(
					'input_wrap_class' => 'twitter_hide_profile_photo',
                    'grouped_options_title' => __( 'Profile Photo', 'feed-them-social' ),
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Profile Photo', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'tiktok_hide_profile_photo',
					'name'             => 'tiktok_hide_profile_photo',
					'default_value'    => 'no',
					'options'          => array(
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
						array(
							'label' => esc_html__( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),

					),
				),
			),
		);

		return $this->all_options['twitter_profile_photo_options'];
	} //END Twitter Profile Photo Options

	/**
	 * Twitter Style Options
	 *
	 * Style Options for Twitter Feed.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function style_options() {
		$this->all_options['twitter_style_options'] = array(
			'section_attr_key'   => 'twitter_style_options_',
            'section_title'      => esc_html__( 'Styles and Options', 'feed-them-social' ),
			//'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',

			'main_options'       => array(

				// Hide Images in Posts.
				/*array(
					'input_wrap_class' => 'twitter_hide_images_in_posts',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Images in Posts', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_hide_images_in_posts',
					'name'             => 'twitter_hide_images_in_posts',
					'default_value'    => 'no',
					'options'          => array(
                        array(
                            'label' => esc_html__( 'No', 'feed-them-social' ),
                            'value' => 'no',
                        ),
						array(
							'label' => esc_html__( 'Yes', 'feed-them-social' ),
							'value' => 'yes',
						),

					),
				),

				// Max-width for Feed Images
				array(
					'input_wrap_class' => 'twitter_max_image_width',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Max-width for Feed Images', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_max_image_width',
					'name'             => 'twitter_max_image_width',
					'placeholder'      => '500px',
					'default_value'    => '',
				),*/

				// Feed Description Text Size
				array(
					'input_wrap_class' => 'twitter_text_size',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Description Text Size', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_text_size',
					'name'             => 'twitter_text_size',
					'placeholder'      => '12px',
					'default_value'    => '',
				),

				// Feed Text Color
				array(
					'input_wrap_class' => 'twitter_text_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Text Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_text_color',
					'name'             => 'twitter_text_color',
					'placeholder'      => '#222',
					'default_value'    => '',
				),

                // Feed Link Color
                array(
                    'input_wrap_class' => 'twitter_link_color fts-color-picker',
                    'option_type'      => 'input',
                    'label'            => esc_html__( 'Feed Link Color', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'twitter_link_color',
                    'name'             => 'twitter_link_color',
                    'placeholder'      => 'rgb(29, 155, 240)',
                    'default_value'    => '',
                ),

				// Feed Link Color Hover
				array(
					'input_wrap_class' => 'twitter_link_color_hover fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Link Color Hover', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_link_color_hover',
					'name'             => 'twitter_link_color_hover',
					'placeholder'      => 'rgb(65 173 246)',
					'default_value'    => '',
				),

				// Feed Width
				array(
					'input_wrap_class' => 'twitter_feed_width',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Width', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_feed_width',
					'name'             => 'twitter_feed_width',
					'placeholder'      => '500px',
					'default_value'    => '',
				),

				// Feed Margin
				array(
					'input_wrap_class' => 'twitter_feed_margin',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Feed Margin %1$sTo center feed type auto%2$s', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'twitter_feed_margin',
					'name'             => 'twitter_feed_margin',
					'placeholder'      => '10px',
					'default_value'    => '',
				),

				// Feed Padding
				array(
					'input_wrap_class' => 'twitter_feed_padding',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Padding', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_feed_padding',
					'name'             => 'twitter_feed_padding',
					'placeholder'      => '10px',
					'default_value'    => '',
				),

				// Feed Background Color
				array(
					'input_wrap_class' => 'twitter_feed_background_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Background Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_feed_background_color',
					'name'             => 'twitter_feed_background_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

				// Feed Border Bottom Color
				array(
					'input_wrap_class' => 'twitter_border_bottom_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Border Bottom Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_border_bottom_color',
					'name'             => 'twitter_border_bottom_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

			),
		);

		return $this->all_options['twitter_style_options'];
	} //END Twitter Style Options


	/**
	 * Twitter Grid Styles
	 *
	 * Grid Styles for Twitter Feed.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function grid_style_options() {
		$this->all_options['twitter_grid_style_options'] = array(
			'section_attr_key'   => 'twitter_grid_style_options_',
			'section_title'      => esc_html__( 'Grid Styles', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',


			'main_options'       => array(

				// Posts Background Color
				array(
					'input_wrap_class' => 'twitter_grid_posts_background_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Posts Background Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_grid_posts_background_color',
					'name'             => 'twitter_grid_posts_background_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),

				// Border Bottom Color
				array(
					'input_wrap_class' => 'twitter_grid_border_bottom_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Border Bottom Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_grid_border_bottom_color',
					'name'             => 'twitter_grid_border_bottom_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),
			),
		);

		return $this->all_options['twitter_grid_style_options'];
	} //END Twitter Grid Styles

	/**
	 * Twitter Load More Button Styles & Options
	 *
	 * Load More Button Styles & Options.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function load_more_options() {
		$this->all_options['twitter_load_more_options'] = array(
			'section_attr_key'   => 'twitter_grid_style_options_',
			'section_title'      => esc_html__( 'Load More Button', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',


			'main_options'       => array(

				// Button Color
				array(
					'input_wrap_class' => 'twitter_loadmore_background_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Button Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_loadmore_background_color',
					'name'             => 'twitter_loadmore_background_color',
                    'placeholder'      => '#f0f0f0',
                    'default_value'    => '#f0f0f0',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),

				// Text Color
				array(
					'input_wrap_class' => 'twitter_loadmore_text_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Text Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'twitter_loadmore_text_color',
					'name'             => 'twitter_loadmore_text_color',
                    'placeholder'      => '#000',
                    'default_value'    => '#000',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),

				// "Load More" Text
				array(
					'input_wrap_class' => 'tiktok_load_more_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'tiktok_load_more_text',
					'name'             => 'tiktok_load_more_text',
					'placeholder'      => 'Load More',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),

				// "Load More" Text
				array(
					'input_wrap_class' => 'tiktok_load_more_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"No More Posts" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'tiktok_no_more_posts_text',
					'name'             => 'tiktok_no_more_posts_text',
					'placeholder'      => 'No More Posts',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_tiktok_premium'),
				),
			),
		);

		return $this->all_options['twitter_load_more_options'];
	} //END Twitter Grid Styles
}