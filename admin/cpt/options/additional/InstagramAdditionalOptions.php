<?php
/**
 * Instagram Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    InstagramAdditionalOptions
 * @version  4.3.9
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial\admin\cpt\options\additional;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Instagram_Add_Options
 */
class InstagramAdditionalOptions {

    /**
     * All Options
     *
     * @var array
     */
    public $allOptions;

    /**
     * Instagram_Add_Options constructor.
     */
    public function __construct() {
        $this->followBtnOptions();
        $this->loadMoreOptions();
        $this->sliderColorOptions();
    }

    /**
     * All Instagram Additional Options
     *
     * Function to return all Instagram Additional Options
     *
     * @return array
     * @since 4.3.9
     */
    public function getAllOptions() {
        return $this->allOptions;
    }

    /**
     * Generates the boilerplate array for a settings section.
     *
     * @param array $args The unique arguments for the section.
     * @return array The structured settings array.
     */
    private function generateOptionsArray(array $args): array
    {
        // Set default values for all common keys
        $defaults = [
            'section_attr_key'   => '',
            'section_wrap_id'    => 'fts-tab-content1',
            'section_wrap_class' => 'fts-tab-content',
            'form_wrap_classes'  => 'fb-page-shortcode-form',
            'form_wrap_id'       => 'fts-fb-page-form',
            'options_wrap_class' => 'fts-cpt-additional-options',
            'premium_msg_boxes'  => [],
            'main_options'       => [],
        ];

        // Merge the unique arguments with the defaults, preserving all keys
        return array_replace_recursive($defaults, $args);
    }

    /**
     * Instagram Slider Options
     *
     * Color Styles for the Instagram Slider.
     *
     * @return mixed
     * @since 4.3.1
     */
    public function sliderColorOptions() {
        $main_options = [
            // Slider Controls Arrow Color
            [
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
                'req_extensions'   => [ 'feed_them_social_instagram_slider' ],

            ],
            // Slider Arrow Color Hover
            [
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
                'req_extensions'   => [ 'feed_them_social_instagram_slider' ],

            ],
            // Slider Dots Color
            [
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
                'req_extensions'   => [ 'feed_them_social_instagram_slider' ],

            ],
            // Slider Dots Hover
            [
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
                'req_extensions'   => [ 'feed_them_social_instagram_slider' ],
            ],
        ];

        $this->allOptions['instagram_slider_color_options'] = $this->generateOptionsArray([
            'section_attr_key'   => 'instagram_slider_color_options_',
            'section_title'      => esc_html__( 'Slider Navigation', 'feed-them-social' ),
            'main_options'       => $main_options,
        ]);

        return $this->allOptions['instagram_slider_color_options'];
    }

    /**
     * Instagram Follow Button Options
     *
     * Follow Button Options for Instagram.
     *
     * @return mixed
     * @since 4.3.9
     */
    public function followBtnOptions() {
        $main_options = [
            // Show Follow Button.
            [
                'input_wrap_class' => 'instagram_show_follow_btn',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_show_follow_btn',
                'name'             => 'instagram_show_follow_btn',
                'default_value'    => 'no',
                'options'          => [
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                ],
            ],
            // Show Follow Button.
            [
                'input_wrap_class' => 'instagram_show_follow_btn_where',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Placement of the Buttons', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_show_follow_btn_where',
                'name'             => 'instagram_show_follow_btn_where',
                'default_value'    => 'instagram-follow-above',
                'options'          => [
                    [ 'label' => esc_html__( 'Show Above Feed', 'feed-them-social' ), 'value' => 'instagram-follow-above' ],
                    [ 'label' => esc_html__( 'Show Below Feed', 'feed-them-social' ), 'value' => 'instagram-follow-below' ],
                ],
            ],
        ];

        $this->allOptions['instagram_follow_btn_options'] = $this->generateOptionsArray([
            'section_attr_key' => 'instagram_follow_btn_options_',
            'main_options'     => $main_options,
        ]);

        return $this->allOptions['instagram_follow_btn_options'];
    }

    /**
     * Instagram Premium Load More Styles
     *
     * Options for the Load More buttons.
     *
     * @return mixed
     * @since 4.3.9
     */
    public function loadMoreOptions() {
        $main_options = [
            // Load More Button Color
            [
                'input_wrap_class' => 'instagram_loadmore_background_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Button Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_loadmore_background_color',
                'name'             => 'instagram_loadmore_background_color',
                'placeholder'      => '#f0f0f0',
                'default_value'    => '#f0f0f0',
                'req_extensions'   => [ 'feed_them_social_premium', 'feed_them_social_instagram_slider' ],
            ],
            // Load More Button Text Color
            [
                'input_wrap_class' => 'instagram_loadmore_text_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Button Text Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_loadmore_text_color',
                'name'             => 'instagram_loadmore_text_color',
                'placeholder'      => '#000',
                'default_value'    => '#000',
                'req_extensions'   => [ 'feed_them_social_premium', 'feed_them_social_instagram_slider' ],
            ],
            // "Load More" Text
            [
                'input_wrap_class' => 'instagram_load_more_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_load_more_text',
                'name'             => 'instagram_load_more_text',
                'placeholder'      => 'Load More',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium', 'feed_them_social_instagram_slider' ],
            ],
            // "No More Photos" Text
            [
                'input_wrap_class' => 'instagram_no_more_photos_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"No More Photos" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_no_more_photos_text',
                'name'             => 'instagram_no_more_photos_text',
                'placeholder'      => 'No More Photos',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium', 'feed_them_social_instagram_slider' ],
            ],
        ];

        $this->allOptions['instagram_load_more_options'] = $this->generateOptionsArray([
            'section_attr_key' => 'instagram_load_more_options',
            'section_title'    => esc_html__( 'Load More Button', 'feed-them-social' ),
            'main_options'     => $main_options,
        ]);

        return $this->allOptions['instagram_load_more_options'];
    }
}
