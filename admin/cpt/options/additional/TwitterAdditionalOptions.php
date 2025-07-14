<?php
/**
 * Twitter Additional Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    TwitterAdditionalOptions
 * @since    4.3.9
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
 * Class TwitterAdditionalOptions
 */
class TwitterAdditionalOptions {

    /**
     * All Options
     *
     * @var array
     */
    public $allOptions;

    /**
     * Twitter_Add_Options constructor.
     */
    public function __construct() {
        $this->followBtnOptions();
        $this->languageOptions();
        $this->videoPlayerOptions();
        $this->profilePhotoOptions();
        $this->styleOptions();
        $this->gridStyleOptions();
        $this->loadMoreOptions();
    }

    /**
     * All Twitter Additional Options
     *
     * Function to return all Twitter Additional Options
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
     * Twitter Follow Button Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function followBtnOptions() {
        $main_options = [
            // Show Stats Bar
            [
                'option_type'   => 'select',
                'label'         => __( 'Stats Bar', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'twitter-stats-bar',
                'name'          => 'twitter_stats_bar',
                'default_value' => 'yes',
                'options'       => [
                    [ 'label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => __( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Follow Button.
            [
                'input_wrap_class' => 'tiktok-show-stats-profile-photo tiktok-stats-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Stats Profile Photo', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_show_stats_profile_photo',
                'name'             => 'tiktok_show_stats_profile_photo',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Follow Button.
            [
                'input_wrap_class' => 'tiktok_show_stats_follow_btn tiktok-stats-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Stats Follow Button', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_show_stats_follow_btn',
                'name'             => 'tiktok_show_stats_follow_btn',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Follow Count Singular.
            [
                'input_wrap_class' => 'tiktok_show_follow_button_inline tiktok-stats-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Stats Follow Button Inline', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_show_follow_button_inline',
                'name'             => 'tiktok_show_follow_button_inline',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Follow Count Singular.
            [
                'input_wrap_class' => 'twitter_show_follow_count tiktok-stats-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Stats Counts', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_show_stats_counts',
                'name'             => 'tiktok_show_stats_counts',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Button where.
            [
                'input_wrap_class' => 'tiktok_show_stats_description tiktok-stats-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Stats Description', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_show_stats_description',
                'name'             => 'tiktok_show_stats_description',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
            // Show Follow Button.
            [
                'input_wrap_class' => 'twitter_show_follow_btn tiktok-show-follow-button-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Show Follow Button', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_show_follow_btn',
                'name'             => 'twitter_show_follow_btn',
                'default_value'    => 'no',
                'options'          => [
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                ],
            ],
            // Show Button where.
            [
                'input_wrap_class' => 'tiktok_show_stats_description tiktok-show-follow-button-hide',
                'option_type'      => 'select',
                'label'            => esc_html__( 'Follow Button Position', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_show_follow_btn_where',
                'name'             => 'twitter_show_follow_btn_where',
                'default_value'    => 'yes',
                'options'          => [
                    [ 'label' => esc_html__( 'Above Feed', 'feed-them-social' ), 'value' => 'twitter-follow-above' ],
                    [ 'label' => esc_html__( 'Below Feed', 'feed-them-social' ), 'value' => 'twitter-follow-below' ],
                ],
            ],
        ];

        $this->allOptions['twitter_follow_btn_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_follow_btn_options_',
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_follow_btn_options'];
    }

    /**
     * Twitter Language Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function languageOptions() {
        $main_options = [
            // Follow on TikTok Text.
            [
                'input_wrap_class'      => 'tiktok_language tiktok_follow_on_tiktok_fts',
                'grouped_options_title' => __( 'Language Options', 'feed-them-social' ),
                'option_type'           => 'input',
                'label'                 => esc_html__( 'Follow Button Text', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'tiktok_follow_on_tiktok',
                'name'                  => 'tiktok_follow_on_tiktok',
                'placeholder'           => 'Follow on TikTok',
                'default_value'         => esc_html__( 'Follow on TikTok', 'feed-them-social' ),
            ],
            // View on TikTok Text.
            [
                'input_wrap_class' => 'tiktok_language tiktok_view_on_tiktok_fts',
                'option_type'      => 'input',
                'label'            => esc_html__( 'View Link Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_view_on_tiktok',
                'name'             => 'tiktok_view_on_tiktok',
                'placeholder'      => 'View on TikTok',
                'default_value'    => esc_html__( 'View on TikTok', 'feed-them-social' ),
            ],
        ];

        $this->allOptions['twitter_language_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_language_options_',
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_language_options'];
    }

    /**
     * Twitter Video Player Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function videoPlayerOptions() {
        $main_options = [
            // Show Follow Button.
            [
                'input_wrap_class'      => 'twitter_allow_videos',
                'grouped_options_title' => __( 'Video Player Options', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => esc_html__( 'Show videos', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'twitter_allow_videos',
                'name'                  => 'twitter_allow_videos',
                'default_value'         => 'yes',
                'options'               => [
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                ],
            ],
        ];

        $this->allOptions['twitter_video_player_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_video_player_options_',
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_video_player_options'];
    }

    /**
     * Twitter Profile Photo Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function profilePhotoOptions() {
        $main_options = [
            // Hide Profile Photo.
            [
                'input_wrap_class'      => 'twitter_hide_profile_photo',
                'grouped_options_title' => __( 'Profile Photo', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => esc_html__( 'Hide Profile Photo', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'tiktok_hide_profile_photo',
                'name'                  => 'tiktok_hide_profile_photo',
                'default_value'         => 'no',
                'options'               => [
                    [ 'label' => esc_html__( 'No', 'feed-them-social' ), 'value' => 'no' ],
                    [ 'label' => esc_html__( 'Yes', 'feed-them-social' ), 'value' => 'yes' ],
                ],
            ],
        ];

        $this->allOptions['twitter_profile_photo_options'] = $this->generateOptionsArray(
            [
                'section_attr_key'  => 'twitter_profile_photo_options_',
                'form_wrap_classes' => 'twitter-page-shortcode-form',
                'form_wrap_id'      => 'fts-twitter-page-form',
                'main_options'      => $main_options,
            ]
        );

        return $this->allOptions['twitter_profile_photo_options'];
    }

    /**
     * Twitter Style Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function styleOptions() {
        $main_options = [
            // Feed Description Text Size
            [
                'input_wrap_class' => 'twitter_text_size',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Description Text Size', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_text_size',
                'name'             => 'twitter_text_size',
                'placeholder'      => '12px',
                'default_value'    => '',
            ],
            // Feed Text Color
            [
                'input_wrap_class' => 'twitter_text_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Text Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_text_color',
                'name'             => 'twitter_text_color',
                'placeholder'      => '#222',
                'default_value'    => '',
            ],
            // Feed Link Color
            [
                'input_wrap_class' => 'twitter_link_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Link Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_link_color',
                'name'             => 'twitter_link_color',
                'placeholder'      => 'rgb(29, 155, 240)',
                'default_value'    => '',
            ],
            // Feed Link Color Hover
            [
                'input_wrap_class' => 'twitter_link_color_hover fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Link Color Hover', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_link_color_hover',
                'name'             => 'twitter_link_color_hover',
                'placeholder'      => 'rgb(65 173 246)',
                'default_value'    => '',
            ],
            // Feed Width
            [
                'input_wrap_class' => 'twitter_feed_width',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Width', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_feed_width',
                'name'             => 'twitter_feed_width',
                'placeholder'      => '500px',
                'default_value'    => '',
            ],
            // Feed Margin
            [
                'input_wrap_class' => 'twitter_feed_margin',
                'option_type'      => 'input',
                'label'            => \sprintf( esc_html__( 'Feed Margin %1$sTo center feed type auto%2$s', 'feed-them-social' ), '<br/><small>', '</small>' ),
                'type'             => 'text',
                'id'               => 'twitter_feed_margin',
                'name'             => 'twitter_feed_margin',
                'placeholder'      => '10px',
                'default_value'    => '',
            ],
            // Feed Padding
            [
                'input_wrap_class' => 'twitter_feed_padding',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Padding', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_feed_padding',
                'name'             => 'twitter_feed_padding',
                'placeholder'      => '10px',
                'default_value'    => '',
            ],
            // Feed Background Color
            [
                'input_wrap_class' => 'twitter_feed_background_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Background Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_feed_background_color',
                'name'             => 'twitter_feed_background_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
            ],
            // Feed Border Bottom Color
            [
                'input_wrap_class' => 'twitter_border_bottom_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Feed Border Bottom Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_border_bottom_color',
                'name'             => 'twitter_border_bottom_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
            ],
        ];

        $this->allOptions['twitter_style_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_style_options_',
                'section_title'    => esc_html__( 'Styles and Options', 'feed-them-social' ),
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_style_options'];
    }

    /**
     * Twitter Grid Styles
     *
     * @return mixed
     * @since 4.3.9
     */
    public function gridStyleOptions() {
        $main_options = [
            // Posts Background Color
            [
                'input_wrap_class' => 'twitter_grid_posts_background_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Posts Background Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_grid_posts_background_color',
                'name'             => 'twitter_grid_posts_background_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
            // Border Bottom Color
            [
                'input_wrap_class' => 'twitter_grid_border_bottom_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Border Bottom Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_grid_border_bottom_color',
                'name'             => 'twitter_grid_border_bottom_color',
                'placeholder'      => '#ddd',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
        ];

        $this->allOptions['twitter_grid_style_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_grid_style_options_',
                'section_title'    => esc_html__( 'Grid Styles', 'feed-them-social' ),
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_grid_style_options'];
    }

    /**
     * Twitter Load More Button Styles & Options
     *
     * @return mixed
     * @since 4.3.9
     */
    public function loadMoreOptions() {
        $main_options = [
            // Button Color
            [
                'input_wrap_class' => 'twitter_loadmore_background_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Button Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_loadmore_background_color',
                'name'             => 'twitter_loadmore_background_color',
                'placeholder'      => '#f0f0f0',
                'default_value'    => '#f0f0f0',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
            // Text Color
            [
                'input_wrap_class' => 'twitter_loadmore_text_color fts-color-picker',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Text Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'twitter_loadmore_text_color',
                'name'             => 'twitter_loadmore_text_color',
                'placeholder'      => '#000',
                'default_value'    => '#000',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
            // "Load More" Text
            [
                'input_wrap_class' => 'tiktok_load_more_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"Load More" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_load_more_text',
                'name'             => 'tiktok_load_more_text',
                'placeholder'      => 'Load More',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
            // "Load More" Text
            [
                'input_wrap_class' => 'tiktok_load_more_text',
                'option_type'      => 'input',
                'label'            => esc_html__( '"No More Posts" Text', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_no_more_posts_text',
                'name'             => 'tiktok_no_more_posts_text',
                'placeholder'      => 'No More Posts',
                'default_value'    => '',
                'req_extensions'   => [ 'feed_them_social_tiktok_premium' ],
            ],
        ];

        $this->allOptions['twitter_load_more_options'] = $this->generateOptionsArray(
            [
                'section_attr_key' => 'twitter_grid_style_options_',
                'section_title'    => esc_html__( 'Load More Button', 'feed-them-social' ),
                'main_options'     => $main_options,
            ]
        );

        return $this->allOptions['twitter_load_more_options'];
    }
}
