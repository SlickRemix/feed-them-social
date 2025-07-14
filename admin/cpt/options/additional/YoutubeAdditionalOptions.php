<?php
/**
 * YouTube Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    YoutubeAdditionalOptions
 * @version  1.0.0
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
 * Class YoutubeAdditionalOptions
 */
class YoutubeAdditionalOptions {

    /**
     * All Options
     *
     * @var array
     */
    public $allOptions;

    /**
     * Youtube_Add_Options constructor.
     */
    public function __construct() {
        $this->followBtnOptions();
        $this->loadMoreOptions();
    }

    /**
     * All YouTube Additional Options
     *
     * Function to return all YouTube Additional Options.
     *
     * @return array
     * @since 1.0.0
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
    private function generateOptionsArray( array $args ): array {
        // Set default values for all common keys.
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

        // Merge the unique arguments with the defaults, preserving all keys.
        return array_replace_recursive( $defaults, $args );
    }

    /**
     * YouTube Follow Button Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function followBtnOptions() {
        $main_options = [
            // Show Follow Button.
            [
                'input_wrap_class' => 'youtube_show_follow_btn',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_show_follow_btn',
                'name'             => 'youtube_show_follow_btn',
                'options'          => [
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                ],
            ],
            // Show Follow Button.
            [
                'input_wrap_class' => 'youtube-show-follow-btn-where',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Placement of the Buttons', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube-show-follow-btn-where',
                'name'             => 'youtube-show-follow-btn-where',
                'default_value'    => 'youtube-follow-above',
                'options'          => [
                    [ 'label' => esc_html__( 'Show Above Feed', 'feed-them-social' ), 'value' => 'youtube-follow-above' ],
                    [ 'label' => esc_html__( 'Show Below Feed', 'feed-them-social' ), 'value' => 'youtube-follow-below' ],
                ],
            ],
            // Background color for thumbs container
            [
                'input_wrap_class' => 'fts-color-picker',
                'option_type'      => 'input',
                'color_picker'     => 'yes',
                'label'            => __( 'Video Container ', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_thumbs_wrap_color',
                'name'             => 'youtube_thumbs_wrap_color',
                'default_value'    => '#000',
                'placeholder'      => '#000',
            ],
        ];

        $this->allOptions['youtube_follow_btn_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'youtube_follow_btn_options_',
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['youtube_follow_btn_options'];
    }

    /**
     * YouTube Load More Styles
     *
     * @return mixed
     * @since 1.0.0
     */
    public function loadMoreOptions() {
        $main_options = [
            // Load More Button Color
            [
                'input_wrap_class' => 'youtube_loadmore_background_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Button Background Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_loadmore_background_color',
                'name'             => 'youtube_loadmore_background_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium' ],
            ],
            // Load More Button Text Color
            [
                'input_wrap_class' => 'youtube_loadmore_text_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Button Text Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_loadmore_text_color',
                'name'             => 'youtube_loadmore_text_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium' ],
            ],
            // "Load More" Text
            [
                'input_wrap_class' => 'youtube_load_more_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_load_more_text',
                'name'             => 'youtube_load_more_text',
                'placeholder'      => 'Load More',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium' ],
            ],
            // "No More Photos" Text
            [
                'input_wrap_class' => 'youtube_no_more_photos_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"No More Photos" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_no_more_photos_text',
                'name'             => 'youtube_no_more_photos_text',
                'placeholder'      => 'No More Videos',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_premium' ],
            ],
        ];

        $this->allOptions['youtube_load_more_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'youtube_load_more_options_',
                'section_title'    => esc_html__( 'Load More Button', 'feed-them-social' ),
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['youtube_load_more_options'];
    }
}
