<?php
/**
 * Instagram Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Instagram_Additional_Options
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
 * Class Instagram_Add_Options
 */
class Instagram_Additional_Options {

	/**
	 * All Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Instagram_Add_Options constructor.
	 */
	public function __construct() {
		$this->follow_btn_options();
		$this->load_more_options();
        $this->slider_color_options();
	}

	/**
	 * All Instagram Additional Options
	 *
	 * Function to return all Instagram Additional Options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_options() {

		return $this->all_options;

	}

    /**
     * Instagram Slider Options
     *
     * Color Styles for the Instagram Slider.
     *
     * @return mixed
     * @since 4.3.1
     */
    public function slider_color_options() {
        $this->all_options['instagram_slider_color_options'] = array(
            'section_attr_key'   => 'instagram_slider_color_options_',
            'section_title'      => esc_html__( 'Slider Navigation', 'feed-them-social' ),
            'section_wrap_id' => 'fts-tab-content1',
            'section_wrap_class' => 'fts-tab-content',
            // Form Info.
            'form_wrap_classes'  => 'fb-page-shortcode-form',
            'form_wrap_id'       => 'fts-fb-page-form',
            'main_options'       => array(
                // Slider Controls Arrow Color
                array(
                    'input_wrap_id'    => '',
                    'input_wrap_class' => 'fts-color-picker instagram_slider_arrows_colors',
                    'option_type'      => 'input',
                    'label'            => __( 'Arrows', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'instagram_slider_arrow_color',
                    'name'             => 'instagram_slider_arrow_color',
                    'class'            => '',
                    'default_value'    => '#494949',
                    'placeholder'      => '#494949',
                    'req_extensions'   => array( 'feed_them_social_instagram_slider' ),

                ),

                // Slider Arrow Color Hover
                array(
                    'input_wrap_id'    => '',
                    'input_wrap_class' => 'fts-color-picker instagram_slider_arrows_colors',
                    'option_type'      => 'input',
                    'label'            => __( 'Arrows Hover', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'instagram_slider_arrow_hover_color',
                    'name'             => 'instagram_slider_arrow_hover_color',
                    'class'            => '',
                    'default_value'    => '#b2b2b2',
                    'placeholder'      => '#b2b2b2',
                    'req_extensions'   => array( 'feed_them_social_instagram_slider' ),

                ),

                // Slider Dots Color
                array(
                    'input_wrap_id'    => '',
                    'input_wrap_class' => 'fts-color-picker instagram_slider_dots_colors',
                    'option_type'      => 'input',
                    'label'            => __( 'Dots', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'instagram_slider_dots_color',
                    'name'             => 'instagram_slider_dots_color',
                    'class'            => '',
                    'default_value'    => '#494949',
                    'placeholder'      => '#494949',
                    'req_extensions'   => array( 'feed_them_social_instagram_slider' ),

                ),

                // Slider Dots Hover
                array(
                    'input_wrap_id'    => '',
                    'input_wrap_class' => 'fts-color-picker instagram_slider_dots_colors',
                    'option_type'      => 'input',
                    'label'            => __( 'Dots Hover', 'feed-them-social' ),
                    'type'             => 'text',
                    'id'               => 'instagram_slider_dots_hover_color',
                    'name'             => 'instagram_slider_dots_hover_color',
                    'class'            => '',
                    'default_value'    => '#b2b2b2',
                    'placeholder'      => '#b2b2b2',
                    'req_extensions'   => array( 'feed_them_social_instagram_slider' ),
                ),
            ),
        );
        return $this->all_options['instagram_slider_color_options'];
    }

	/**
	 * Instagram Follow Button Options
	 *
	 * Follow Button Options for Instagram.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function follow_btn_options() {
		$this->all_options['instagram_follow_btn_options'] = array(
			'section_attr_key'   => 'instagram_follow_btn_options_',
			//'section_title'      => esc_html__( 'Follow Button', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(
				// Show Follow Button.
				array(
					'input_wrap_class' => 'instagram_show_follow_btn',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_show_follow_btn',
					'name'             => 'instagram_show_follow_btn',
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
				// Show Follow Button.
				array(
					'input_wrap_class' => 'instagram_show_follow_btn_where',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Placement of the Buttons', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_show_follow_btn_where',
					'name'             => 'instagram_show_follow_btn_where',
					'default_value'    => 'instagram-follow-above',
					'options'          => array(
						array(
							'label' => esc_html__( 'Show Above Feed', 'feed-them-social' ),
							'value' => 'instagram-follow-above',
						),
						array(
							'label' => esc_html__( 'Show Below Feed', 'feed-them-social' ),
							'value' => 'instagram-follow-below',
						),
					),
				),

			),
		);

		return $this->all_options['instagram_follow_btn_options'];
	} //END Instagram Follow Button Options.

	/**
	 * Instagram Premium Load More Styles
	 *
	 * Options for the Load More buttons.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function load_more_options() {
		$this->all_options['instagram_load_more_options'] = array(
			'section_attr_key'   => 'instagram_load_more_options',
			'section_title'      => esc_html__( 'Load More Button', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			//Options Wrap Class
			'options_wrap_class'       => 'fts-cpt-additional-options',


			'main_options'       => array(

				// Load More Button Color
				array(
					'input_wrap_class' => 'instagram_loadmore_background_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Button Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_loadmore_background_color',
					'name'             => 'instagram_loadmore_background_color',
					'placeholder'      => '#f0f0f0',
					'default_value'    => '#f0f0f0',
                    'req_extensions'  => array('feed_them_social_premium', 'feed_them_social_instagram_slider' ),
				),

				// Load More Button Text Color
				array(
					'input_wrap_class' => 'instagram_loadmore_text_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Button Text Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_loadmore_text_color',
					'name'             => 'instagram_loadmore_text_color',
					'placeholder'      => '#000',
					'default_value'    => '#000',
                    'req_extensions'  => array('feed_them_social_premium', 'feed_them_social_instagram_slider' ),
				),


				// "Load More" Text
				array(
					'input_wrap_class' => 'instagram_load_more_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_load_more_text',
					'name'             => 'instagram_load_more_text',
					'placeholder'      => 'Load More',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium', 'feed_them_social_instagram_slider' ),
				),

				// "No More Photos" Text
				array(
					'input_wrap_class' => 'instagram_no_more_photos_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"No More Photos" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'instagram_no_more_photos_text',
					'name'             => 'instagram_no_more_photos_text',
					'placeholder'      => 'No More Photos',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium', 'feed_them_social_instagram_slider' ),
				),
			),
		);

		return $this->all_options['instagram_load_more_options'];
	} //END Reviews: Overall Rating Style Options
}