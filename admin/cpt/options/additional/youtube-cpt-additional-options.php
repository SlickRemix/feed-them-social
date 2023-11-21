<?php
/**
 * YouTube Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Youtube_Additional_Options
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
class Youtube_Additional_Options {

	/**
	 * All Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Youtube_Add_Options constructor.
	 */
	public function __construct() {
		$this->follow_btn_options();
		$this->load_more_options();
	}

	/**
	 * All YouTube Additional Options
	 *
	 * Function to return all YouTube Additional Options.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_options() {

		return $this->all_options;

	}

	/**
	 * YouTube Follow Button Options
	 *
	 * Follow Button Options for Youtube.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function follow_btn_options() {
		$this->all_options['youtube_follow_btn_options'] = array(
			'section_attr_key'   => 'youtube_follow_btn_options_',
			//'section_title'      => esc_html__( 'Follow Button Options', 'feed-them-social' ),
			'section_wrap_id' => 'fts-tab-content1',
			'section_wrap_class' => 'fts-tab-content',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(
				// Show Follow Button.
				array(
					'input_wrap_class' => 'youtube_show_follow_btn',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube_show_follow_btn',
					'name'             => 'youtube_show_follow_btn',
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
					'input_wrap_class' => 'youtube-show-follow-btn-where',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Placement of the Buttons', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube-show-follow-btn-where',
					'name'             => 'youtube-show-follow-btn-where',
					'default_value'    => 'youtube-follow-above',
					'options'          => array(
						array(
							'label' => esc_html__( 'Show Above Feed', 'feed-them-social' ),
							'value' => 'youtube-follow-above',
						),
						array(
							'label' => esc_html__( 'Show Below Feed', 'feed-them-social' ),
							'value' => 'youtube-follow-below',
						),
					),
				),

                // Background color for thumbs container
                array(
                    'input_wrap_class' => 'fts-color-picker',
                    'option_type'  => 'input',
                    'color_picker' => 'yes',
                    'label'        => __( 'Video Container ', 'feed-them-social' ),
                    'type'         => 'text',
                    'id'           => 'youtube_thumbs_wrap_color',
                    'name'         => 'youtube_thumbs_wrap_color',
                    'default_value'=> '#000',
                    'placeholder'  => '#000',

                    // Relative to JS.
                    'short_attr'   => array(
                        'attr_name'   => 'thumbs_wrap_color',
                        'empty_error' => 'set',
                        'empty_error_value' => 'thumbs_wrap_color=#000',
                    ),
                ),

			),
		);

		return $this->all_options['youtube_follow_btn_options'];
	} //END YouTube Follow Button Options.

	/**
	 * YouTube Load More Styles
	 *
	 * Options for the Load More buttons.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function load_more_options() {
		$this->all_options['youtube_load_more_options'] = array(
			'section_attr_key'   => 'youtube_load_more_options_',
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
					'input_wrap_class' => 'youtube_loadmore_background_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Button Background Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube_loadmore_background_color',
					'name'             => 'youtube_loadmore_background_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium'),
				),

				// Load More Button Text Color
				array(
					'input_wrap_class' => 'youtube_loadmore_text_color fts-color-picker',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Button Text Color', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube_loadmore_text_color',
					'name'             => 'youtube_loadmore_text_color',
					'placeholder'      => '#ddd',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium'),
				),


				// "Load More" Text
				array(
					'input_wrap_class' => 'youtube_load_more_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube_load_more_text',
					'name'             => 'youtube_load_more_text',
					'placeholder'      => 'Load More',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium'),
				),

				// "No More Photos" Text
				array(
					'input_wrap_class' => 'youtube_no_more_photos_text',
					'option_type'      => 'input',
					'label'            => esc_html__( '"No More Photos" Text', 'feed-them-social' ),
					'type'             => 'text',
					'id'               => 'youtube_no_more_photos_text',
					'name'             => 'youtube_no_more_photos_text',
					'placeholder'      => 'No More Videos',
					'default_value'    => '',
                    'req_extensions'  => array('feed_them_social_premium'),
				),
			),
		);

		return $this->all_options['youtube_load_more_options'];
	} //END YouTube Load More Styles.
}