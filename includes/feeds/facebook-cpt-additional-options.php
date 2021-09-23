<?php
/**
 * Facebook Additional Options Class
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
 * Class Facebook_Add_Options
 */
class Facebook_Additional_Options {

	/**
	 * All Gallery Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Facebook_Add_Options constructor.
	 */
	public function __construct() {
		$this->reviews_text_styles();
		$this->reviews_overall_rating_styles();
		$this->language_options();
		$this->like_button_box_options();
		$this->global_facebook_style_options();
		$this->error_messages_options();
	}

	/**
	 * All Facebook Additional Options
	 *
	 * Function to return all Gallery options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_options() {

		return $this->all_options;

	}

	/**
	 * Reviews: Style and Text Options
	 *
	 * Options for the Reviews: Style and Text Options.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function reviews_text_styles() {
		$this->all_options['facebook_reviews_text_styles'] = array(
			'section_attr_key'   => 'facebook_reviews_text_styles_',
			'section_title'      => esc_html__( 'Reviews: Style and Text Options', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(

				// Stars Background Color
				array(
					'input_wrap_class' => 'fb-reviews-title-color-label ',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Stars Background Color%1$sApplies to Overall Rating too.%2$s', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb-reviews-backg-color',
					'name'             => 'fb_reviews_backg_color',
					'placeholder'      => '#4791ff',
					'default_value'    => '',
				),
				// Stars & Text Background Color
				array(
					'input_wrap_class' => 'fb-reviews-text-color',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Stars & Text Color%1$sApplies to Overall Rating too.%2$s', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb-reviews-text-color',
					'name'             => 'fb-reviews-text-color',
					'placeholder'      => '#fff',
					'default_value'    => '',
				),
				// Text for word Star.
				array(
					'input_wrap_class' => 'fb_reviews_star_language',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Text for the word "star"', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_reviews_star_language',
					'name'             => 'fb_reviews_star_language',
					'placeholder'      => 'star',
					'default_value'    => '',
				),
				// Text for word Recommended.
				array(
					'input_wrap_class' => 'fb_reviews_recommended_language',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Text for the word "Recommended"', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_reviews_recommended_language',
					'name'             => 'fb_reviews_recommended_language',
					'placeholder'      => 'Recommended',
					'default_value'    => '',
				),
				// Remove See More Reviews.
				array(
					'input_wrap_class' => 'fb_reviews_remove_see_reviews_link',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Remove "See More Reviews" link', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_reviews_remove_see_reviews_link',
					'name'             => 'fb_reviews_remove_see_reviews_link',
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

			),
		);

		return $this->all_options['facebook_reviews_text_styles'];
	} //END Reviews: Style and Text Options.

	/**
	 * Reviews: Overall Rating Style Options
	 *
	 * Options for the Reviews: Overall Rating Style Options.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function reviews_overall_rating_styles() {
		$this->all_options['facebook_reviews_overall_rating_styles'] = array(
			'section_attr_key'   => 'facebook_reviews_overall_ratings_styles_',
			'section_title'      => esc_html__( 'Reviews: Overall Rating Style Options', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',

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

				// Hide Overall Rating Background & Border.
				array(
					'input_wrap_class' => 'fb_reviews_overall_rating_background_border_hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Overall Rating Background & Border', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_reviews_overall_rating_background_border_hide',
					'name'             => 'fb_reviews_overall_rating_background_border_hide',
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

				// Overall Rating Background Color.
				array(
					'input_wrap_class' => 'fb-reviews-title-color-label ',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Overall Rating Background Color', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_reviews_overall_rating_background_color',
					'name'             => 'fb_reviews_overall_rating_background_color',
					'placeholder'      => '#fff',
					'default_value'    => '',
				),


				// Overall Rating Text Color.
				array(
					'input_wrap_class' => 'fb-reviews-text-color',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Overall Rating Text Color', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_reviews_overall_rating_text_color',
					'name'             => 'fb_reviews_overall_rating_text_color',
					'placeholder'      => '#fff',
					'default_value'    => '',
				),

				// Overall Rating Border Color.
				array(
					'input_wrap_class' => 'fb-reviews-text-color',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Overall Rating Border Color', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_reviews_overall_rating_border_color',
					'name'             => 'fb_reviews_overall_rating_border_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

				// Overall Rating Background Padding.
				array(
					'input_wrap_class' => 'fb_reviews_see_more_reviews_language',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Text for the word "Recommended"', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_reviews_see_more_reviews_language',
					'name'             => 'fb_reviews_see_more_reviews_language',
					'placeholder'      => 'See More Reviews',
					'default_value'    => '',
				),
			),
		);

		return $this->all_options['facebook_reviews_text_styles'];
	} //END Reviews: Overall Rating Style Options


	/**
	 * Language Options
	 *
	 * Language Options
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function language_options() {
		$this->all_options['facebook_languages_options'] = array(
			'section_attr_key'   => 'facebook_language_options_',
			'section_title'      => esc_html__( 'Language Options', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',

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

				// Language For Facebook Feeds.
				array(
					'input_wrap_class' => 'fb_language',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Language For Facebook Feeds', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb-lang-btn',
					'name'             => 'fb_language',
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
				// Hide Notice on Front End for Facebook Feed.
				array(
					'input_wrap_class' => 'fb_hide_no_posts_message',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Notice on Front End', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_hide_no_posts_message',
					'name'             => 'fb_hide_no_posts_message',
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

				// View on Facebook Text.
				array(
					'input_wrap_class' => 'fb_view_on_fb_fts',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Text for "View on Facebook Text"', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_view_on_fb_fts',
					'name'             => 'fb_view_on_fb_fts',
					'placeholder'      => '#fff',
					'default_value'    => '',
				),
			),
		);

		return $this->all_options['facebook_languages_options'];
	} //END Language Options.

	/**
	 * Like Button or Box Options
	 *
	 * Like Button or Box Options.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function like_button_box_options() {
		$this->all_options['facebook_like_button_box_options'] = array(
			'section_attr_key'   => 'facebook_like_button_box_options_',
			'section_title'      => esc_html__( 'Like Button or Box Options', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',

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

				// Show Follow Button.
				array(
					'input_wrap_class' => 'fb_language',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Follow Button', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb-lang-btn',
					'name'             => 'fb_language',
					'default_value'    => 'dont-display',
					'options'          => array(
						array(
							'optgroup' => array(
								'label' => '',

							),
							'label' => esc_html__( 'Don\'t Display a Button', 'feed_them_social' ),
							'value' => 'dont-display',
						),
						array(
							'label' => esc_html__( 'Like Box', 'feed_them_social' ),
							'value' => 'like-box',
						),
						array(
							'label' => esc_html__( 'Like Box with Faces', 'feed_them_social' ),
							'value' => 'like-box-faces',
						),
						array(
							'label' => esc_html__( 'Like Box with Faces', 'feed_them_social' ),
							'value' => 'like-box-faces',
						),
					),
				),

				// Show Profile Icon next to social option above.
				array(
					'input_wrap_class' => 'fb_show_follow_like_box_cover',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Profile Icon next to social option above', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_show_follow_like_box_cover',
					'name'             => 'fb_show_follow_like_box_cover',
					'default_value'    => 'Yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Display Cover Photo in Like Box', 'feed_them_social' ),
							'value' => 'fb_like_box_cover-yes',
						),
						array(
							'label' => esc_html__( 'Hide Cover Photo in Like Box', 'feed_them_social' ),
							'value' => 'fb_like_box_cover-no',
						),
					),
				),

				// Like Button Color.
				array(
					'input_wrap_class' => 'fb-like-btn-color',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Like Button Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb-like-btn-color',
					'name'             => 'fb-like-btn-color',
					'default_value'    => 'fb_like_box_cover-yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Display Cover Photo in Like Box', 'feed_them_social' ),
							'value' => 'fb_like_box_cover-yes',
						),
						array(
							'label' => esc_html__( 'Hide Cover Photo in Like Box', 'feed_them_social' ),
							'value' => 'fb_like_box_cover-no',
						),
					),
				),

				// Like Button Color.
				array(
					'input_wrap_class' => 'fb-show-follow-btn-where',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Placement of the Button(s)', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb-show-follow-btn-where',
					'name'             => 'fb-show-follow-btn-where',
					'default_value'    => 'fb-like-top-above-title',
					'options'          => array(
						array(
							'label' => esc_html__( 'Show Top of Feed Above Title', 'feed_them_social' ),
							'value' => 'fb-like-top-above-title',
						),
						array(
							'label' => esc_html__( 'Show Top of Feed Below Title', 'feed_them_social' ),
							'value' => 'fb-like-top-below-title',
						),
						array(
							'label' => esc_html__( 'Show Bottom of Feed', 'feed_them_social' ),
							'value' => 'fb-like-below',
						),
					),
				),
			),
		);

		return $this->all_options['facebook_like_button_box_options'];
	} //END Like Button or Box Options.


	/**
	 * Global Facebook Style Options
	 *
	 * Global Facebook Style Options
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function global_facebook_style_options() {
		$this->all_options['global_facebook_style_options'] = array(
			'section_attr_key'   => 'facebook_like_button_box_options_',
			'section_title'      => esc_html__( 'Like Button or Box Options', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',

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

				// Page Title Tag.
				array(
					'input_wrap_class' => 'fb_title_htag',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Page Title Tag', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_title_htag',
					'name'             => 'fb_title_htag',
					'default_value'    => 'h1',
					'options'          => array(
						array(
							'label' => esc_html__( 'h1 (Default)', 'feed_them_social' ),
							'value' => 'h1',
						),
						array(
							'label' => esc_html__( 'h2', 'feed_them_social' ),
							'value' => 'h2',
						),
						array(
							'label' => esc_html__( 'h2', 'feed_them_social' ),
							'value' => 'h3',
						),
						array(
							'label' => esc_html__( 'h2', 'feed_them_social' ),
							'value' => 'h4',
						),
						array(
							'label' => esc_html__( 'h2', 'feed_them_social' ),
							'value' => 'h5',
						),
						array(
							'label' => esc_html__( 'h2', 'feed_them_social' ),
							'value' => 'h6',
						),
					),
				),

				// Page Title Size.
				array(
					'input_wrap_class' => 'fb_title_htag_size',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Page Title Size', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_title_htag_size',
					'name'             => 'fb_title_htag_size',
					'placeholder'      => '16px',
					'default_value'    => '',
				),

				// Text after your FB name.
				array(
					'input_wrap_class' => 'fb_hide_shared_by_etc_text',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Text after your FB name %1$sie* Shared by or New Photo Added etc.%2$s', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_hide_shared_by_etc_text',
					'name'             => 'fb_hide_shared_by_etc_text',
					'default_value'    => 'no',
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

				// Hide Images in Posts.
				array(
					'input_wrap_class' => 'fb_hide_images_in_posts',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Images in Posts', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_hide_images_in_posts',
					'name'             => 'fb_hide_images_in_posts',
					'default_value'    => 'no',
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

				// Max-width for Images & Videos.
				array(
					'input_wrap_class' => 'fb_max_image_width',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Max-width for Images & Videos', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_max_image_width',
					'name'             => 'fb_max_image_width',
					'placeholder'      => '500px',
					'default_value'    => '',
				),

				// Feed Header Extra Text Color.
				array(
					'input_wrap_class' => 'fb_header_extra_text_color',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Header Extra Text Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_header_extra_text_color',
					'name'             => 'fb_header_extra_text_color',
					'placeholder'      => '#222',
					'default_value'    => '',
				),

				// Feed Description Text Size.
				array(
					'input_wrap_class' => 'fb_text_size',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Description Text Size', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_text_size',
					'name'             => 'fb_text_size',
					'placeholder'      => '12px',
					'default_value'    => '',
				),

				// Feed Text Color.
				array(
					'input_wrap_class' => 'fb_text_color',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Text Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_text_color',
					'name'             => 'fb_text_color',
					'placeholder'      => '#222',
					'default_value'    => '',
				),

				// Feed Link Color.
				array(
					'input_wrap_class' => 'fb_link_color',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Link Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_link_color',
					'name'             => 'fb_link_color',
					'placeholder'      => '#222',
					'default_value'    => '',
				),

				// Feed Link Color.
				array(
					'input_wrap_class' => 'fb_link_color_hover',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Link Color Hover', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_link_color_hover',
					'name'             => 'fb_link_color_hover',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

				// Feed Width.
				array(
					'input_wrap_class' => 'fb_feed_width',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Width', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_feed_width',
					'name'             => 'fb_feed_width',
					'placeholder'      => '500px',
					'default_value'    => '',
				),

				// Feed Margin.
				array(
					'input_wrap_class' => 'fb_feed_margin',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Feed Margin %1$sTo center feed type auto%2$s', 'feed-them-social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fb_feed_margin',
					'name'             => 'fb_feed_margin',
					'placeholder'      => '10px',
					'default_value'    => '',
				),

				// Feed Padding.
				array(
					'input_wrap_class' => 'fb_feed_padding',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Padding', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_feed_padding',
					'name'             => 'fb_feed_padding',
					'placeholder'      => '10px',
					'default_value'    => '',
				),

				// Feed Background Color.
				array(
					'input_wrap_class' => 'fb_feed_background_color',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Feed Background Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_feed_background_color',
					'name'             => 'fb_feed_background_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

				// Border Bottom Color.
				array(
					'input_wrap_class' => 'fb_border_bottom_color',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Border Bottom Color', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_border_bottom_color',
					'name'             => 'fb_border_bottom_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
				),

			),
		);

		return $this->all_options['global_facebook_style_options'];
	} //END Global Facebook Style Options.


	/**
	 * Facebook Error Messages
	 *
	 * Facebook Error Messages
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function error_messages_options() {
		$this->all_options['facebook_error_messages_options'] = array(
			'section_attr_key'   => 'facebook_error_messages_options_',
			'section_title'      => esc_html__( 'Facebook Error Messages', 'feed_them_social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',

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

				// Hide Error Handler Message.
				array(
					'input_wrap_class' => 'fb_hide_error_handler_message',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Error Handler Message', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_hide_error_handler_message',
					'name'             => 'fb_hide_error_handler_message',
					'default_value'    => 'no',
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
			),
		);

		return $this->all_options['facebook_error_messages_options'];
	} //END Like Button or Box Options.
}