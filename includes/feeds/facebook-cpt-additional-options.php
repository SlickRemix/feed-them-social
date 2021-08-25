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
					'default_value'    => 'Yes',
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
					'default_value'    => 'Yes',
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
					'default_value'    => 'Yes',
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
					'default_value'    => 'Yes',
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
					'default_value'    => 'Yes',
					'options'          => array(
						array(
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
				// Hide Notice on Front End for Facebook Feed.
				array(
					'input_wrap_class' => 'fb_hide_no_posts_message',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Hide Notice on Front End', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fb_hide_no_posts_message',
					'name'             => 'fb_hide_no_posts_message',
					'default_value'    => 'Yes',
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

		return $this->all_options['facebook_like_button_box_options'];
	} //END Like Button or Box Options.



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
			'section_title'      => esc_html( 'Facebook Options', 'feed_them_social' ),
			'section_wrap_class' => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',

			//Options Wrap Class
			'options_wrap_class'       => '.fts-cpt-additional-options',
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
						esc_html__( '%1$sNOTE:%2$s Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
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
						esc_html__( '%1$sNOTE:%2$s Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
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
				// Taking this option out so we can position our close button better.
				// Show Icon.
			// array(
			// 'input_wrap_class' => 'ft-gallery-icon',
			// 'option_type'      => 'select',
			// 'label'            =>
			// sprintf(
			// esc_html__( 'Show Wordpress Icon%1$s Appears in the top left corner%2$s', 'feed_them_social' ),
			// '<br/><small>',
			// '</small>'
			// ),
			// 'type'             => 'text',
			// 'id'               => 'fts_wp_icon',
			// 'name'             => 'fts_wp_icon',
			// 'default_value'    => 'no',
			// 'options'          => array(
			// array(
			// 'label' => esc_html__( 'Yes', 'feed_them_social' ),
			// 'value' => 'yes',
			// ),
			// array(
			// 'label' => esc_html__( 'No', 'feed_them_social' ),
			// 'value' => 'no',
			// ),
			// ),
			// ),
				// Words per photo caption
				// array(
				// 'option_type' => 'input',
				// 'label' => __('# of words per photo caption', 'feed_them_social') . '<br/><small>' . __('Typing 0 removes the photo caption', 'feed_them_social') . '</small>',
				// 'type' => 'hidden',
				// 'id' => 'fts_word_count_option',
				// 'name' => 'fts_word_count_option',
				// 'placeholder' => '',
				// 'default_value' => '',
				// ),
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

				// ******************************************
				// Gallery Image Count Options
				// ******************************************
				// Load More Style
				array(
					'option_type'        => 'select',
					'label'              => esc_html__( 'Show Image Count', 'feed_them_social' ),
					'type'               => 'text',
					'id'                 => 'fts_show_pagination',
					'name'               => 'fts_show_pagination',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s This will display the number of images you have in your gallery, and will appear centered at the bottom of your image feed. For Example: 4 of 50 (4 being the number of images you have loaded on the page already and 50 being the total number of images in the gallery.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'default_value'      => 'yes',
					'options'            => array(
						1 => array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output).
					'sub_options'        => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
					),
					'sub_options_end'    => true,
				),

				// ******************************************
				// Gallery Sort Options
				// ******************************************
				array(
					'grouped_options_title' => esc_html__( 'Order of Images', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 => esc_html__( 'Choose the order of Images', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'ftg_sort_type',
					'name'                  => 'ftg_sort_type',
					'default_value'         => 'above-below',
					'options'               => array(
						1 => array(
							'label' => esc_html__( 'Sort by date', 'feed_them_social' ),
							'value' => 'date',
						),
						2 => array(
							'label' => esc_html__( 'The order you manually sorted images', 'feed_them_social' ),
							'value' => 'menu_order',
						),
						3 => array(
							'label' => esc_html__( 'Sort alphabetically (A-Z)', 'feed_them_social' ),
							'value' => 'title',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         =>
						sprintf(
							esc_html__( 'Display Options%1$s Display a select option for this gallery so your users can select the sort order. Does not work with Loadmore button, only works with Pagination.%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'ftg_sorting_options',
					'name'          => 'ftg_sorting_options',
					'default_value' => 'no',
					'options'       => array(
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

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Position of Select Option', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg_position_of_sort_select',
					'name'          => 'ftg_position_of_sort_select',
					'default_value' => 'above-below',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Top', 'feed_them_social' ),
							'value' => 'above',
						),
						2 => array(
							'label' => esc_html__( 'Bottom', 'feed_them_social' ),
							'value' => 'below',
						),
						3 => array(
							'label' => esc_html__( 'Top and Bottom', 'feed_them_social' ),
							'value' => 'above-below',
						),
					),
					'sub_options'   => array(
						'sub_options_wrap_class' => 'ftg-sorting-options-wrap',
					),
				),

				array(
					'option_type'     => 'select',
					'label'           => esc_html__( 'Align Select Option', 'feed_them_social' ),
					'type'            => 'text',
					'id'              => 'ftg_align_sort_select',
					'name'            => 'ftg_align_sort_select',
					'default_value'   => 'left',
					'options'         => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Download Free Image Button Sort Options
				// ******************************************
				array(
					'grouped_options_title' => esc_html__( 'Free Image Download', 'feed_them_social' ),
					'option_type'           => 'ftg-free-download-size',
					'instructional-text'    =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s To turn this option on simply choose an image size. A download icon will appear under the image to the right and in the popup. If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'                 => esc_html__( 'Choose the size', 'feed_them_social' ),
					'class'                 => 'ft-images-sizes-free-download-button',
					'type'                  => 'select',
					'id'                    => 'ftg_free_download_size',
					'name'                  => 'ftg_free_download_size',
					'default_value'         => '',
					'placeholder'           => '',
					'autocomplete'          => 'off',
				),

				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Free Download Text', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_free_download_text',
					'name'          => 'fts_free_download_text',
					'placeholder'   => 'Free Download',
					'default_value' => '',
					'value'         => '',
				),

			),

		);

		return $this->all_options['layout'];
	} //END LAYOUT OPTIONS
}
