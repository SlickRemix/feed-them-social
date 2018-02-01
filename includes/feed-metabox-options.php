<?php
/**
 * Feed Metabox Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Feed_Metabox_Options
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;
// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Class Gallery_Options
 */
class Feed_Metabox_Options {
    public $all_options = '';

    public function __construct() {

        $this->facebook_options();
        $this->combine_streams_options();
    }

    /**
     * FT Gallery Required Plugins
     *
     * Return an array of required plugins.
     *
     * @return array
     * @since 1.0.0
     */
    function ft_gallery_required_plugins() {
        $required_plugins = array(
            'fts_premium' => array(
                //Name will go into Non-Premium field so make sure it says "extension" Example: Must have {Plugin Name} to edit.
                'name' => '<h3>Feed Them Premium extension</h3>',
                //Slick URL should Take them to plugin on Slickremix.com because they need for required fields
                'slick_url' => 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/',
                //Plugin URL for checking if plugin is active
                'plugin_url' => 'feed-them-premium/feed-them-premium.php',
                'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> to edit.',
            ),
            'facebook_reviews' => array(
                'name' => '<h3>Facebook Reviews extension</h3>',
                'slick_url' => 'https://www.slickremix.com/downloads/feed-them-social-facebook-reviews/',
                'plugin_url' => 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php',
                'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> and <a href="https://www.slickremix.com/downloads/feed-them-carousel-premium/">carousel</a> to edit.',
            ),
            'fts_carousel' => array(
                'name' => '<h3>Feed Them Carousel extension</h3>',
                'slick_url' => 'https://www.slickremix.com/downloads/feed-them-carousel-premium/',
                'plugin_url' => 'feed-them-carousel-premium/feed-them-carousel-premium.php',
                'no_active_msg' => 'Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> and <a href="https://www.slickremix.com/downloads/feed-them-carousel-premium/">carousel</a> to edit.',
            ),
            'combine_streams' => array(
                'name' => '<h3>Feed Them Social Combined Streams extension</h3>',
                'slick_url' => 'https://www.slickremix.com/downloads/feed-them-social-combined-streams/',
                'plugin_url' => 'feed-them-social-combined-streams/feed-them-social-combined-streams.php',
                'no_active_msg' => 'Must have <a href="https://www.slickremix.com/downloads/feed-them-social-combined-streams/">combined streams extenstion</a> to edit.',
            ),
        );

        return $required_plugins;
    }

    /**
     * Facebook Options
     *
     * These are Gallery to Woo options (just for saving not for display)
     *
     * @return mixed
     * @since 1.0.0
     */
    function facebook_options() {

        $this->all_options['facebook'] = array(
            'section_attr_key' => 'facebook_',
            'section_title' => __('Facebook Page Shortcode Generator', 'feed-them-social'),
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'fb-page-shortcode-form',
            'form_wrap_id' => 'fts-fb-page-form',
            //Token Check
            'token_check' => $facebookReviewsTokenCheck,
            //Feed Type Selection
            'feed_type_select' => array(
                'label' => __('Feed Type', 'feed-them-social'),
                'select_wrap_classes' => 'fts-social-selector',
                'select_classes' => '',
                'select_name' => 'facebook-messages-selector',
                'select_id' => 'facebook-messages-selector',
            ),
            //Feed Types and their options
            'feeds_types' => array(
                //Facebook Page
                array(
                    'value' => 'page',
                    'title' => __('Facebook Page', 'feed-them-social'),
                ),
                //Facebook Page List of Events
                array(
                    'value' => 'events',
                    'title' => __('Facebook Page List of Events', 'feed-them-social'),
                ),
                //Facebook Page Single Event Posts
                array(
                    'value' => 'event',
                    'title' => __('Facebook Page Single Event Posts', 'feed-them-social'),
                ),
                //Facebook Group
                array(
                    'value' => 'group',
                    'title' => __('Facebook Group', 'feed-them-social'),
                ),
                //Facebook Album Photos
                array(
                    'value' => 'album_photos',
                    'title' => __('Facebook Album Photos', 'feed-them-social'),
                ),
                //Facebook Album Covers
                array(
                    'value' => 'albums',
                    'title' => __('Facebook Album Covers', 'feed-them-social'),
                ),
                //Facebook Videos
                array(
                    'value' => 'album_videos',
                    'title' => __('Facebook Videos', 'feed-them-social'),
                ),
                //Facebook Page Reviews
                array(
                    'value' => 'reviews',
                    'title' => __('Facebook Page Reviews', 'feed-them-social'),
                ),
            ),
            'premium_msg_boxes' => array(
                'album_videos' => array(
                    'req_plugin' => 'fts_premium',
                    'msg' => 'The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your wordpress site! <a href="http://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br><br>Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too! <a href="http://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a>',
                ),
                'reviews' => array(
                    'req_plugin' => 'facebook_reviews',
                    'msg' => 'The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="http://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a>',
                ),
            ),
            'short_attr_final' => 'yes',

            'main_options' => array(
                //Feed Type
                array(
                    'option_type' => 'select',
                    'id' => 'facebook-messages-selector',
                    'name' => 'facebook-messages-selector',
                    //DONT SHOW HTML
                    'no_html' => 'yes',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'type',
                    ),
                ),
                //Facebook ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'fb_page_id',
                    'label' => __('Facebook ID (required)', 'feed-them-social'),
                    'instructional-text' => array(
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __('Facebook Page ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below. You cannot use Personal Profiles it must be a Facebook Page. If your page ID looks something like, My-Page-Name-50043151918, only use the number portion, 50043151918.', 'feed-them-social') . ' <a href="http://feedthemsocial.com/?feedID=50043151918" target="_blank">' . __('Test your Page ID on our demo', 'feed-them-social') . '</a>',
                            'class' => 'facebook-message-generator page inst-text-facebook-page',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-group-id/" target="_blank">' . __('Facebook Group ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social'),
                            'class' => 'facebook-message-generator group inst-text-facebook-group',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __('Facebook Page ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below. PLEASE NOTE: This will only work with Facebook Page Events and you cannot have more than 25 events on Facebook.', 'feed-them-social'),
                            'class' => 'facebook-message-generator event-list inst-text-facebook-event-list',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . __('Facebook Event ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social'),
                            'class' => 'facebook-message-generator event inst-text-facebook-event',
                        ),
                        array(
                            'text' => __('To show a specific Album copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . __('Facebook Album ID', 'feed-them-social') . '</a> ' . __('and paste it in the second input below. If you want to show all your uploaded photos leave the Album ID input blank.', 'feed-them-social'),
                            'class' => 'facebook-message-generator album_photos inst-text-facebook-album-photos',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . __('Facebook Album Covers ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social'),
                            'class' => 'facebook-message-generator albums inst-text-facebook-albums',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/docs/how-to-get-your-facebook-id-and-video-gallery-id" target="_blank">' . __('Facebook ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social'),
                            'class' => 'facebook-message-generator video inst-text-facebook-video',
                        ),
                        array(
                            'text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __('Facebook Page ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below. If your page ID looks something like, My-Page-Name-50043151918, only use the number portion, 50043151918.', 'feed-them-social'),
                            'class' => 'facebook-message-generator reviews inst-text-facebook-reviews',
                        ),
                    ),
                    'type' => 'text',
                    'id' => 'fb_page_id',
                    'name' => 'fb_page_id',
                    'value' => '',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'id',
                        'var_final_if' => 'no',
                        'empty_error' => 'yes',
                    ),
                ),
                //Facebook Album ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'fb_album_photos_id',
                    'label' => __('Album ID ', 'feed-them-social') . '<br/><small>' . __('Leave blank to show all uploaded photos', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'fb_album_id',
                    'name' => 'fb_album_id',
                    'value' => '',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'album_id',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => 'album_id=photo_stream',
                        'empty_error_if' => array(
                            'attribute' => 'select#facebook-messages-selector',
                            'operator' => '==',
                            'value' => 'album_photos',
                        ),
                        'ifs' => 'album_photos',
                    ),
                ),
                //Facebook Page Post Type Visible
                array(
                    'input_wrap_class' => 'facebook-post-type-visible',
                    'option_type' => 'select',
                    'label' => __('Post Type Visible', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_page_posts_displayed',
                    'name' => 'fb_page_posts_displayed',
                    'options' => array(
                        array(
                            'label' => __('Display Posts made by Page only', 'feed-them-social'),
                            'value' => 'page_only',
                        ),
                        array(
                            'label' => __('Display Posts made by Page and Others', 'feed-them-social'),
                            'value' => 'page_and_others',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => 'posts_displayed',
                        'ifs' => 'page',
                    ),
                ),
                //Facebook page # of Posts
                array(
                    'option_type' => 'input',
                    'label' => __('# of Posts', 'feed-them-social') . $limitforpremium,
                    'type' => 'text',
                    'id' => 'fb_page_post_count',
                    'name' => 'fb_page_post_count',
                    'value' => '',
                    'placeholder' => __('6 is the default value', 'feed-them-social'),
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'posts',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => 'posts=6',
                    ),
                ),
                //Facebook Page Facebook Fixed Height
                array(
                    'input_wrap_class' => 'fixed_height_option',
                    'option_type' => 'input',
                    'label' => __('Facebook Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'facebook_page_height',
                    'name' => 'facebook_page_height',
                    'value' => '',
                    'placeholder' => '450px ' . __('for example', 'feed-them-social'),
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'height',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //Facebook Page Show Page Title (Premium)
                array(
                    'input_wrap_class' => 'fb-page-title-option-hide',
                    'option_type' => 'select',
                    'label' => __('Show Page Title', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_page_title_option',
                    'name' => 'fb_page_title_option',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'short_attr' => array(
                        'attr_name' => 'title',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-title-options-wrap',
                    ),
                ),
                //Facebook Page Align Title (Premium)
                array(
                    'input_wrap_class' => 'fb-page-title-align',
                    'option_type' => 'select',
                    'label' => __('Align Title', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_page_title_align',
                    'name' => 'fb_page_title_align',
                    'options' => array(
                        1 => array(
                            'label' => __('Left', 'feed-them-social'),
                            'value' => 'left',
                        ),
                        2 => array(
                            'label' => __('Center', 'feed-them-social'),
                            'value' => 'center',
                        ),
                        3 => array(
                            'label' => __('Right', 'feed-them-social'),
                            'value' => 'right',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'short_attr' => array(
                        'attr_name' => 'title_align',
                    ),
                ),
                //Facebook Page Show Page Description (Premium)
                array(
                    'input_wrap_class' => 'fb-page-description-option-hide',
                    'option_type' => 'select',
                    'label' => __('Show Page Description', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_page_description_option',
                    'name' => 'fb_page_description_option',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'short_attr' => array(
                        'attr_name' => 'description',
                    ),
                    'sub_options_end' => true,
                ),
                //Facebook Amount of words
                array(
                    'option_type' => 'input',
                    'label' => __('Amount of words per post', 'feed-them-social') . '<br/><small>' . __('Type 0 to remove the posts description', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'fb_page_word_count_option',
                    'name' => 'fb_page_word_count_option',
                    'placeholder' => '45 ' . __('is the default value', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'words',
                        'empty_error' => 'set',
                        'empty_error_value' => 'words=45',
                    ),
                ),
                //Facebook Image Width
                array(
                    'option_type' => 'input',
                    'label' => __('Facebook Image Width', 'feed-them-social') . '<br/><small>' . __('Max width is 640px', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'fts-slicker-facebook-container-image-width',
                    'name' => 'fts-slicker-facebook-container-image-width',
                    'placeholder' => '250px',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'image_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'image_width=250px',
                        'ifs' => 'album_photos,albums,album_videos',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-super-facebook-options-wrap',
                    ),
                ),
                //Facebook Image Height
                array(
                    'option_type' => 'input',
                    'label' => __('Facebook Image Height', 'feed-them-social') . '<br/><small>' . __('Max width is 640px', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'fts-slicker-facebook-container-image-height',
                    'name' => 'fts-slicker-facebook-container-image-height',
                    'placeholder' => '250px',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'image_height',
                        'empty_error' => 'set',
                        'empty_error_value' => 'image_height=250px',
                        'ifs' => 'album_photos,albums,album_videos',
                    ),
                ),
                //Facebook The space between photos
                array(
                    'option_type' => 'input',
                    'label' => __('The space between photos', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fts-slicker-facebook-container-margin',
                    'name' => 'fts-slicker-facebook-container-margin',
                    'placeholder' => '1px',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'space_between_photos',
                        'empty_error' => 'set',
                        'empty_error_value' => 'space_between_photos=1px',
                        'ifs' => 'album_photos,albums,album_videos',
                    ),
                ),
                //Hide Date, Likes and Comments
                array(
                    'option_type' => 'select',
                    'label' => __('Hide Date, Likes and Comments', 'feed-them-social'),
                    'label_note' => __('Good for image sizes under 120px', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fts-slicker-facebook-container-hide-date-likes-comments',
                    'name' => 'fts-slicker-facebook-container-hide-date-likes-comments',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => 'hide_date_likes_comments',
                        'ifs' => 'album_photos,albums,album_videos',
                    ),
                ),
                //Center Facebook Container
                array(
                    'option_type' => 'select',
                    'label' => __('Center Facebook Container', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fts-slicker-facebook-container-position',
                    'name' => 'fts-slicker-facebook-container-position',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => 'center_container',
                        'ifs' => 'album_photos,albums,album_videos',
                    ),
                    'sub_options_end' => true,
                ),
                //Image Stacking Animation NOT USING THIS ANYMORE
                array(
                    'option_type' => 'input',
                    'label' => __('Image Stacking Animation On', 'feed-them-social'),
                    'label_note' => __('This happens when resizing browser', 'feed-them-social'),
                    'type' => 'hidden',
                    //used to trick is Visible in JS
                    'class' => 'non-visible',
                    'id' => 'fts-slicker-facebook-container-animation',
                    'name' => 'fts-slicker-facebook-container-animation',
                    'value' => 'no',
                    'short_attr' => array(
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
                //Align Images non-grid
                array(
                    'input_wrap_id' => 'facebook_align_images_wrapper',
                    'option_type' => 'select',
                    'label' => __('Align Images', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_align_images',
                    'name' => 'facebook_align_images',
                    'options' => array(
                        1 => array(
                            'label' => __('Left', 'feed-them-social'),
                            'value' => 'left',
                        ),
                        2 => array(
                            'label' => __('Center', 'feed-them-social'),
                            'value' => 'center',
                        ),
                        3 => array(
                            'label' => __('Right', 'feed-them-social'),
                            'value' => 'right',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => 'images_align',
                        'ifs' => 'page',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'align-images-wrap',
                    ),
                    'sub_options_end' => true,
                ),
                //******************************************
                // Facebook Review Options
                //******************************************
                //Reviews to Show
                array(
                    'grouped_options_title' => __('Reviews', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Reviews to Show', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'reviews_type_to_show',
                    'name' => 'reviews_type_to_show',
                    'options' => array(
                        1 => array(
                            'label' => __('Show all Reviews', 'feed-them-social'),
                            'value' => '1',
                        ),
                        2 => array(
                            'label' => __('5 Star Reviews only', 'feed-them-social'),
                            'value' => '5',
                        ),
                        3 => array(
                            'label' => __('4 and 5 Stars Reviews only', 'feed-them-social'),
                            'value' => '4',
                        ),
                        4 => array(
                            'label' => __('3, 4 and 5 Star Reviews only', 'feed-them-social'),
                            'value' => '3',
                        ),
                        5 => array(
                            'label' => __('2, 3, 4, and 5 Star Reviews only', 'feed-them-social'),
                            'value' => '2',
                        ),
                    ),
                    'req_plugin' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'reviews_type_to_show',
                        'ifs' => 'reviews',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-reviews-wrap',
                    ),
                ),
                //Rating Format
                array(
                    'option_type' => 'select',
                    'label' => __('Rating Format', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'reviews_rating_format',
                    'name' => 'reviews_rating_format',
                    'options' => array(
                        1 => array(
                            'label' => __('5 star - &#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social'),
                            'value' => '1',
                        ),
                        2 => array(
                            'label' => __('5 star &#9733;', 'feed-them-social'),
                            'value' => '2',
                        ),
                        3 => array(
                            'label' => __('5 star', 'feed-them-social'),
                            'value' => '3',
                        ),
                        4 => array(
                            'label' => __('5 &#9733;', 'feed-them-social'),
                            'value' => '4',
                        ),
                        5 => array(
                            'label' => __('&#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social'),
                            'value' => '5',
                        ),
                    ),
                    'req_plugin' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'reviews_rating_format',
                        'ifs' => 'reviews',
                    )
                ),
                //Overall Rating
                array(
                    'option_type' => 'select',
                    'label' => __('Overall Rating above Feed', 'feed-them-social') . '<br/><small>' . __('More settings: <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page.', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'reviews_overall_rating_show',
                    'name' => 'reviews_overall_rating_show',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        )
                    ),
                    'req_plugin' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'overall_rating',
                        'ifs' => 'reviews',
                    ),
                    'sub_options_end' => true,
                ),
                //******************************************
                // Like Box Options
                //******************************************
                //Facebook Hide Like Box or Button (Premium)
                array(
                    'grouped_options_title' => __('Like Box', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Hide Like Box or Button', 'feed-them-social') . '<br/><small>' . __('Turn on from <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'fb_hide_like_box_button',
                    'name' => 'fb_hide_like_box_button',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),

                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'hide_like_option',
                        'ifs' => 'not_group',
                        'empty_error' => 'set',
                        'set_operator' => '==',
                        'set_equals' => 'no',
                        'empty_error_value' => '',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-like-box-wrap',
                    ),
                ),
                //Position of Like Box or Button (Premium)
                array(
                    'option_type' => 'select',
                    'label' => __('Position of Like Box or Button', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_position_likebox',
                    'name' => 'fb_position_likebox',
                    'options' => array(
                        1 => array(
                            'label' => __('Above Title', 'feed-them-social'),
                            'value' => 'above_title',
                        ),
                        2 => array(
                            'label' => __('Below Title', 'feed-them-social'),
                            'value' => 'below_title',
                        ),
                        3 => array(
                            'label' => __('Bottom of Feed', 'feed-them-social'),
                            'value' => 'bottom',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'show_follow_btn_where',
                        'ifs' => 'not_group',
                        'and_ifs' => 'like_box',

                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'like-box-wrap',
                    ),
                ),
                //Facebook Page Align Like Box or Button (Premium)
                array(
                    'option_type' => 'select',
                    'label' => __('Align Like Box or Button', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_align_likebox',
                    'name' => 'fb_align_likebox',
                    'options' => array(
                        1 => array(
                            'label' => __('Left', 'feed-them-social'),
                            'value' => 'left',
                        ),
                        2 => array(
                            'label' => __('Center', 'feed-them-social'),
                            'value' => 'center',
                        ),
                        3 => array(
                            'label' => __('Right', 'feed-them-social'),
                            'value' => 'right',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'like_option_align',
                        'ifs' => 'not_group',
                        'and_ifs' => 'like_box',
                    ),
                ),
                //Facebook Page Width of Like Box
                array(
                    'option_type' => 'input',
                    'label' => __('Width of Like Box', 'feed-them-social') . '<br/><small>' . __('This only works for the Like Box', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'like_box_width',
                    'name' => 'like_box_width',
                    'placeholder' => __('500px max', 'feed-them-social'),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'like_box_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'like_box_width=500px',
                        'ifs' => 'not_group',
                        'and_ifs' => 'like_box',
                    ),
                    'sub_options_end' => 2,
                ),
                //******************************************
                // Popup
                //******************************************
                //Facebook Page Display Photos in Popup
                array(
                    'grouped_options_title' => __('Popup', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Display Photos in Popup', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_popup',
                    'name' => 'facebook_popup',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'popup',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-popup-wrap',
                    ),
                    'sub_options_end' => true,
                ),
                //Facebook Comments in Popup
                array(
                    'option_type' => 'select',
                    'label' => __('Hide Comments in Popup', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_popup_comments',
                    'name' => 'facebook_popup_comments',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'hide_comments_popup',
                        'ifs' => 'popup',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'display-comments-wrap',
                    ),
                    'sub_options_end' => true,
                ),
                //******************************************
                // Facebook Load More Options
                //******************************************
                //Facebook Page Load More Button
                array(
                    'grouped_options_title' => __('Load More', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Load More Button', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_load_more_option',
                    'name' => 'fb_load_more_option',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                        'ifs' => 'not_events',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-loadmore-wrap',
                        //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                    ),
                ),
                //Facebook Page Load More Style
                array(
                    'option_type' => 'select',
                    'label' => __('Load More Style', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb_load_more_style',
                    'name' => 'fb_load_more_style',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social'),
                    'options' => array(
                        1 => array(
                            'label' => __('Button', 'feed-them-social'),
                            'value' => 'button',
                        ),
                        2 => array(
                            'label' => __('AutoScroll', 'feed-them-social'),
                            'value' => 'autoscroll',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'loadmore',
                        'ifs' => 'load_more',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
                        //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                    ),
                    'sub_options_end' => true,
                ),
                //Facebook Page Load more Button Width
                array(
                    'option_type' => 'input',
                    'label' => __('Load more Button Width', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto width', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'loadmore_button_width',
                    'name' => 'loadmore_button_width',
                    'placeholder' => '300px ' . __('for example', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'facebook_reviews',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'loadmore_btn_maxwidth',
                        'empty_error' => 'set',
                        'empty_error_value' => 'loadmore_btn_maxwidth=300px',
                        'ifs' => 'load_more',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',
                        //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                    ),
                ),
                //Facebook Page Load more Button Margin
                array(
                    'option_type' => 'input',
                    'label' => __('Load more Button Margin', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'loadmore_button_margin',
                    'name' => 'loadmore_button_margin',
                    'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'facebook_reviews',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'loadmore_btn_margin',
                        'empty_error' => 'set',
                        'empty_error_value' => 'loadmore_btn_margin=10px',
                        'ifs' => 'load_more',
                    ),
                    'sub_options_end' => 2,
                ),
                //******************************************
                // Facebook Grid Options
                //******************************************
                //Facebook Page Display Posts in Grid
                array(
                    'grouped_options_title' => __('Grid', 'feed-them-social'),
                    'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
                    'option_type' => 'select',
                    'label' => __('Display Posts in Grid', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fb-grid-option',
                    'name' => 'fb-grid-option',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    'short_attr' => array(
                        'attr_name' => 'grid',
                        'empty_error' => 'set',
                        'set_operator' => '==',
                        'set_equals' => 'yes',
                        'empty_error_value' => '',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-grid-options-wrap',
                    ),
                ),
                //Grid Column Width
                array(
                    'option_type' => 'input',
                    'label' => __('Grid Column Width', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_grid_column_width',
                    'name' => 'facebook_grid_column_width',
                    'instructional-text' => __('NOTE:', 'feed-them-social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-social'),
                    'placeholder' => '310px ' . __('for example', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',

                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'colmn_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'colmn_width=310px',
                        'ifs' => 'grid',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',
                        //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                    ),
                ),
                //Grid Spaces Between Posts
                array(
                    'option_type' => 'input',
                    'label' => __('Grid Spaces Between Posts', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_grid_space_between_posts',
                    'name' => 'facebook_grid_space_between_posts',
                    'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'fts_premium',
                    'or_req_plugin' => 'combine_streams',
                    'or_req_plugin_three' => 'facebook_reviews',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'space_between_posts',
                        'empty_error' => 'set',
                        'empty_error_value' => 'space_between_posts=10px',
                        'ifs' => 'grid',
                    ),
                    'sub_options_end' => 2,
                ),
                //******************************************
                // Facebook Video Options
                //******************************************
                //Video Play Button
                array(
                    'grouped_options_title' => __('Video Button Options', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Video Play Button', 'feed-them-social') . '<br/><small>' . __('Displays over Video Thumbnail', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'facebook_show_video_button',
                    'name' => 'facebook_show_video_button',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'short_attr' => array(
                        'attr_name' => 'play_btn',
                        'empty_error' => 'set',
                        'set_operator' => '==',
                        'set_equals' => 'yes',
                        'ifs' => 'album_videos',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fb-video-play-btn-options-wrap',
                    ),
                ),
                //Size of the Play Button
                array(
                    'option_type' => 'input',
                    'label' => __('Size of the Play Button', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'facebook_size_video_play_btn',
                    'name' => 'facebook_size_video_play_btn',
                    'placeholder' => '40px ' . __('for example', 'feed-them-social'),
                    'req_plugin' => 'fts_premium',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'play_btn_size',
                        'empty_error' => 'set',
                        'empty_error_value' => 'play_btn_size=40px',
                        'ifs' => 'album_videos',
                        'and_ifs' => 'video',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fb-video-play-btn-options-content',
                    ),
                ),
                //Show Play Button in Front
                array(
                    'option_type' => 'select',
                    'label' => __('Show Play Button in Front', 'feed-them-social') . '<br/><small>' . __('Displays before hovering over thumbnail', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'facebook_show_video_button_in_front',
                    'name' => 'facebook_show_video_button_in_front',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_premium',
                    'short_attr' => array(
                        'attr_name' => 'play_btn_visible',
                        'ifs' => 'album_videos',
                        'and_ifs' => 'video',
                    ),
                    'sub_options_end' => 2,
                ),
                //******************************************
                // Facebook Carousel
                //******************************************
                //Carousel/Slideshow
                array(
                    'grouped_options_title' => __('Carousel/Slider', 'feed-them-social'),
                    'input_wrap_id' => 'facebook_slider',
                    'instructional-text' => __('Create a Carousel or Slideshow with these options.', 'feed-them-social') . ' <a href="http://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . __('View Demos', 'feed-them-social') . '</a> ' . __('and copy easy to use shortcode examples.', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Carousel/Slideshow', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'fts-slider',
                    'name' => 'fts-slider',
                    'options' => array(
                        1 => array(
                            'label' => __('Off', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('On', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'fts_carousel',
                    'short_attr' => array(
                        'attr_name' => 'slider',
                        'empty_error' => 'set',
                        'set_operator' => '==',
                        'set_equals' => 'yes',
                        'ifs' => 'album_photos,album_videos',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'slideshow-wrap',
                    ),
                ),
                //Carousel/Slideshow Type
                array(
                    'input_wrap_id' => 'facebook_scrollhorz_or_carousel',
                    'option_type' => 'select',
                    'label' => __('Type', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'scrollhorz_or_carousel',
                    'name' => 'scrollhorz_or_carousel',
                    'options' => array(
                        1 => array(
                            'label' => __('Slideshow', 'feed-them-social'),
                            'value' => 'scrollhorz',
                        ),
                        2 => array(
                            'label' => __('Carousel', 'feed-them-social'),
                            'value' => 'carousel',
                        ),
                    ),
                    'req_plugin' => 'fts_carousel',
                    'short_attr' => array(
                        'attr_name' => 'scrollhorz_or_carousel',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'slider_options_wrap',
                    ),
                ),
                //Carousel Slides Visible
                array(
                    'input_wrap_id' => 'facebook_slides_visible',
                    'option_type' => 'input',
                    'label' => __('Carousel Slides Visible', 'feed-them-social') . '<br/><small>' . __('Not for Slideshow. Example: 1-500', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slides_visible',
                    'name' => 'slides_visible',
                    'placeholder' => __('3 is the default value', 'feed-them-social'),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slides_visible',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slides_visible=3',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'slider_carousel_wrap',
                    ),
                ),
                //Carousel Spacing in between Slides
                array(
                    'input_wrap_id' => 'facebook_slider_spacing',
                    'option_type' => 'input',
                    'label' => __('Spacing in between Slides', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_spacing',
                    'name' => 'slider_spacing',
                    'value' => '',
                    'placeholder' => __('2px', 'feed-them-social'),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_spacing',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_spacing=2px',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                    'sub_options_end' => true,
                ),
                //Carousel/Slideshow Margin
                array(
                    'input_wrap_id' => 'facebook_slider_margin',
                    'option_type' => 'input',
                    'label' => __('Carousel/Slideshow Margin', 'feed-them-social') . '<br/><small>' . __('Center feed. Add space above/below.', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_margin',
                    'name' => 'slider_margin',
                    'value' => '',
                    'placeholder' => __('-6px auto 1px auto', 'feed-them-social'),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_margin',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_margin="-6px auto 1px auto"',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow Slider Speed
                array(
                    'input_wrap_id' => 'facebook_slider_speed',
                    'option_type' => 'input',
                    'label' => __('Slider Speed', 'feed-them-social') . '<br/><small>' . __('How fast the slider changes', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_speed',
                    'name' => 'slider_speed',
                    'value' => '',
                    'placeholder' => __('0-10000', 'feed-them-social'),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_speed',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_speed=1000',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow Slider Timeout
                array(
                    'input_wrap_id' => 'facebook_slider_timeout',
                    'option_type' => 'input',
                    'label' => __('Slider Timeout', 'feed-them-social') . '<br/><small>' . __('Amount of Time before the next slide.', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_timeout',
                    'name' => 'slider_timeout',
                    'value' => '',
                    'placeholder' => __('0-10000', 'feed-them-social'),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_timeout',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_timeout=1000',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow
                array(
                    'input_wrap_id' => 'facebook_slider_controls',
                    'option_type' => 'select',
                    'label' => __('Slider Controls', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_controls',
                    'name' => 'slider_controls',
                    'options' => array(
                        1 => array(
                            'label' => __('Dots above Feed', 'feed-them-social'),
                            'value' => 'dots_above_feed',
                        ),
                        2 => array(
                            'label' => __('Dots and Arrows above Feed', 'feed-them-social'),
                            'value' => 'dots_and_arrows_above_feed',
                        ),
                        3 => array(
                            'label' => __('Dots and Numbers above Feed', 'feed-them-social'),
                            'value' => 'dots_and_numbers_above_feed',
                        ),
                        4 => array(
                            'label' => __('Dots, Arrows and Numbers above Feed', 'feed-them-social'),
                            'value' => 'dots_arrows_and_numbers_above_feed',
                        ),
                        5 => array(
                            'label' => __('Arrows and Numbers above feed', 'feed-them-social'),
                            'value' => 'arrows_and_numbers_above_feed',
                        ),
                        6 => array(
                            'label' => __('Arrows above Feed', 'feed-them-social'),
                            'value' => 'arrows_above_feed',
                        ),
                        7 => array(
                            'label' => __('Numbers above Feed', 'feed-them-social'),
                            'value' => 'numbers_above_feed',
                        ),
                        8 => array(
                            'label' => __('Dots below Feed', 'feed-them-social'),
                            'value' => 'dots_below_feed',
                        ),
                        array(
                            'label' => __('Dots and Arrows below Feed', 'feed-them-social'),
                            'value' => 'dots_and_arrows_below_feed',
                        ),
                        array(
                            'label' => __('Dots and Numbers below Feed', 'feed-them-social'),
                            'value' => 'dots_and_numbers_below_feed',
                        ),
                        array(
                            'label' => __('Dots, Arrows and Numbers below Feed', 'feed-them-social'),
                            'value' => 'dots_arrows_and_numbers_below_feed',
                        ),
                        array(
                            'label' => __('Arrows below Feed', 'feed-them-social'),
                            'value' => 'arrows_below_feed',
                        ),
                        array(
                            'label' => __('Numbers Below Feed', 'feed-them-social'),
                            'value' => 'numbers_below_feed',
                        ),
                    ),
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_controls',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow Slider Controls Text Color
                array(
                    'input_wrap_id' => 'facebook_slider_controls_text_color',
                    'option_type' => 'input',
                    'label' => __('Slider Controls Text Color', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_controls_text_color',
                    'name' => 'slider_controls_text_color',
                    'class' => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'value' => '',
                    'placeholder' => '#FFF',
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_controls_text_color',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_controls_text_color=#FFF',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow Slider Controls Bar Color
                array(
                    'input_wrap_id' => 'facebook_slider_controls_bar_color',
                    'option_type' => 'input',
                    'label' => __('Slider Controls Bar Color', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_controls_bar_color',
                    'name' => 'slider_controls_bar_color',
                    'class' => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'value' => '',
                    'placeholder' => '#000',
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_controls_bar_color',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_controls_bar_color=320px',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                ),
                //Carousel/Slideshow Slider Controls Bar Color
                array(
                    'input_wrap_id' => 'facebook_slider_controls_width',
                    'option_type' => 'input',
                    'label' => __('Slider Controls Max Width', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'slider_controls_width',
                    'name' => 'slider_controls_width',
                    'class' => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'value' => '',
                    'placeholder' => '320px',
                    'req_plugin' => 'fts_carousel',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'slider_controls_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'slider_controls_width=320px',
                        'ifs' => 'album_photos,album_videos',
                        'and_ifs' => 'carousel',
                    ),
                    'sub_options_end' => 2,
                ),
            ),
            //Final Shortcode ifs
            'shortcode_ifs' => array(
                'page' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
                'events' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'events',
                    ),
                ),
                'not_events' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '!==',
                        'value' => 'events',
                    ),
                ),
                'event' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'event',
                    ),
                ),
                'group' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'group',
                    ),
                ),
                'not_group' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '!==',
                        'value' => 'group',
                    ),
                ),
                'album_photos' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'album_photos',
                    ),
                ),
                'albums' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'albums',
                    ),
                ),
                'album_videos' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'album_videos',
                    ),
                ),
                'reviews' => array(
                    'if' => array(
                        'class' => 'select#facebook-messages-selector',
                        'operator' => '==',
                        'value' => 'reviews',
                    ),
                ),
                'like_box' => array(
                    'if' => array(
                        'class' => 'select#fb_hide_like_box_button',
                        'operator' => '==',
                        'value' => 'no',
                    ),
                ),
                'popup' => array(
                    'if' => array(
                        'class' => 'select#facebook_popup',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'load_more' => array(
                    'if' => array(
                        'class' => 'select#fb_load_more_option',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'video' => array(
                    'if' => array(
                        'class' => 'select#facebook_show_video_button',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'grid' => array(
                    'if' => array(
                        'class' => 'select#fb-grid-option',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'carousel' => array(
                    'if' => array(
                        'class' => 'select#fts-slider',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
            ),
            //Generator Info
            'generator_title' => __('Facebook Page Feed Shortcode', 'feed-them-social'),
            'generator_class' => 'facebook-page-final-shortcode',
        );

        return $this->all_options['facebook'];
    }


    /* *
        * Combine Steams Options
        *
        * These are the options for the combine streams plugin
        *
        * @return mixed
        * @since 1.0.0
        */
    function combine_streams_options() {




        $this->all_options['combine_streams'] = array(
            'shorcode_label' => 'mashup',
            'section_attr_key' => 'combine_',
            'section_title' => __('Combine Streams Shortcode Generator', 'feed-them-social'),
            'section_wrap_class' => 'fts-combine-steams-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'combine-steams-shortcode-form',
            'form_wrap_id' => 'fts-combine-steams-form',
            //Token Check
            /* 'token_check' => array(
                 1 => array(
                     'option_name' => 'fts_facebook_custom_api_token',
                     'no_token_msg' => 'You can view this feed without adding an API token but we suggest you add one if you are getting errors. You can add a token here if you like on our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page.',
                 ),
                 2 => array(
                     'option_name' => 'fts_facebook_custom_api_token_biz',
                     'no_token_msg' => 'Please add a Facebook Page Reviews API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before trying to view your Facebook Reviews feed.',
                     'req_plugin' => 'facebook_reviews',
                 ),
             ),*/
            //Feed Type Selection
            'feed_type_select' => array(
                'label' => __('Feeds To Combine', 'feed-them-social'),
                'select_wrap_classes' => 'fts-combine-steams-selector',
                'select_classes' => '',
                'select_name' => 'combine-steams-selector',
                'select_id' => 'combine-steams-selector',
            ),
            //Feed Types and their options
            'feeds_types' => array(
                //All Feeds (1 of each for now)
                1 => array(
                    'value' => 'all',
                    'title' => __('All Feeds', 'feed-them-social'),
                ),
                //All Feeds (1 of each for now)
                2 => array(
                    'value' => 'multiple_facebook',
                    'title' => __('Multiple Facebook Feeds', 'feed-them-social'),
                ),
            ),
            'premium_msg_boxes' => array(
                'main_select' => array(
                    'req_plugin' => 'combine_streams',
                    'msg' => 'With this extension you can mix a Facebook, Instagram, Twitter, Youtube and Pinterest posts all in one feed. The other feature this exentsion gives you is the abillity to mix multiple Facebook accounts into one feed!
<a href="http://feedthemsocial.com/feed-them-social-combined-streams/" target="_blank">View Combined Streams Demo</a> . <a href="http://feedthemsocial.com/feed-them-social-combined-streams/#combined-fb-streams" target="_blank">View Combined Facebook Streams Demo</a>',
                ),
            ),
            'short_attr_final' => 'yes',
            //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]

            'main_options' => array(
                //Combined Total # of Posts
                array(
                    'grouped_options_title' => __('Combined Stream', 'feed-them-social'),
                    'option_type' => 'input',
                    'label' => __('Combined Total # of Posts', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_post_count',
                    'name' => 'combine_post_count',
                    'value' => '',
                    'placeholder' => __('6 is the default value', 'feed-them-social'),
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'posts',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => 'posts=6',
                    ),
                ),
                //# of Posts per Social Network
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_social_network_post_count',
                    'label' => __('# of Posts per Social Network (NOT the combined total)', 'feed-them-social'),
                    'type' => 'text',
                    //'instructional-text' => __('', 'feed-them-social'),
                    'id' => 'combine_social_network_post_count',
                    'name' => 'combine_social_network_post_count',
                    'value' => '',
                    'placeholder' => __('1 is the default value', 'feed-them-social'),
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'social_network_posts',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => 'social_network_posts=1',
                    ),
                ),
                //Facebook Amount of words
                array(
                    'option_type' => 'input',
                    'label' => __('Amount of words per post', 'feed-them-social') . '<br/><small>' . __('Type 0 to remove the posts description', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'combine_word_count_option',
                    'name' => 'combine_word_count_option',
                    'placeholder' => '45 ' . __('is the default value', 'feed-them-social'),
                    'value' => '',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'words',
                        'empty_error' => 'set',
                        'empty_error_value' => 'words=45',
                    ),
                ),
                //Center Container
                array(
                    'option_type' => 'select',
                    'label' => __('Center Feed Container', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_container_position',
                    'name' => 'combine_container_position',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'center_container',
                    ),
                ),
                //Page Fixed Height
                array(
                    'input_wrap_class' => 'combine_height',
                    'option_type' => 'input',
                    'label' => __('Feed Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small>',
                    'type' => 'text',
                    'id' => 'combine_height',
                    'name' => 'combine_height',
                    'value' => '',
                    'req_plugin' => 'combine_streams',
                    'placeholder' => '450px ' . __('for example', 'feed-them-social'),
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'height',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //Background Color
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_background_color',
                    'label' => __('Background Color', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_background_color',
                    'name' => 'combine_background_color', //Relative to JS.
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'background_color',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //Padding
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_padding',
                    'label' => __('Padding', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_padding',
                    'name' => 'combine_padding',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'padding',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //Social Icon
                array(
                    'input_wrap_class' => 'combine_show_social_icon',
                    'option_type' => 'select',
                    'label' => __('Show Social Icon', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_show_social_icon',
                    'name' => 'combine_show_social_icon',
                    'req_plugin' => 'combine_streams',
                    'options' => array(
                        array(
                            'label' => __('Left', 'feed-them-social'),
                            'value' => 'left',
                        ),
                        array(
                            'label' => __('Right', 'feed-them-social'),
                            'value' => 'right',
                        ),
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => 'show_social_icon',
                    ),
                ),
                //Combine Facebook
                array(
                    'grouped_options_title' => __('Facebook', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Combine Facebook', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_facebook',
                    'name' => 'combine_facebook',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                        'ifs' => 'combine_facebook',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-combine-facebook-wrap',
                    ),
                ),
                //Combine Facebook ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_facebook_name',
                    'label' => __('Facebook ID', 'feed-them-social'),
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-facebook-feed-styles-submenu-page" target="_blank">Facebook Options</a> page then copy your <a href="https://www.slickremix.com/docs/how-to-get-your-facebook-id-and-video-gallery-id" target="_blank">Facebook Name</a> and paste it in the first input below.',
                    'type' => 'text',
                    'id' => 'combine_facebook_name',
                    'name' => 'combine_facebook_name',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'facebook_name',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-facebook-wrap',
                    ),
                    'sub_options_end' => true,
                ),


                //Combine Twitter
                array(
                    'grouped_options_title' => __('Twitter', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Combine Twitter', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_twitter',
                    'name' => 'combine_twitter',
                    'req_plugin' => 'combine_streams',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-combine-twitter-wrap',
                    ),
                ),


                //Feed Type Selection
                array(
                    'option_type' => 'select',
                    'label' => __('Feed Type', 'feed-them-social'),
                    'select_wrap_classes' => 'combine-twitter-gen-selection',
                    'select_classes' => '',
                    'name' => 'combine-twitter-messages-selector',
                    'id' => 'combine-twitter-messages-selector',
                    'req_plugin' => 'combine_streams',
                    //Feed Types and their options
                    'options' => array(
                        //User Feed
                        array(
                            'value' => 'user',
                            'label' => __('User Feed', 'feed-them-social'),
                        ),
                        //hastag Feed
                        array(
                            'value' => 'hashtag',
                            'label' => __('Hashtag, Search and more Feed', 'feed-them-social'),
                        ),
                    ),
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-twitter-wrap',
                    ),
                ),

                // 'short_attr_final' => 'yes',
                //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]


                //Twitter Search Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_twitter_hashtag_etc_name',
                    'label' => __('Twitter Search Name (required)', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_twitter_hashtag_etc_name',
                    'name' => 'combine_twitter_hashtag_etc_name',
                    'value' => '',
                    'instructional-text' => __('You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social'),
                    //Relative to JS.
                    'short_attr' => array(


                        'attr_name' => 'search',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                        'empty_error_if' => array(
                            'attribute' => 'select#combine-twitter-messages-selector',
                            'operator' => '==',
                            'value' => 'hashtag',
                        ),


                    ),
                    'req_plugin' => 'combine_streams',
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-twitter-hashtag-etc-wrap',
                        'sub_options_title' => __('Twitter Search', 'feed-them-social'),
                    ),
                    'sub_options_end' => true,

                ),
                //Twitter Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_twitter_name',
                    'label' => __('Twitter Name', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_twitter_name',
                    'name' => 'combine_twitter_name',
                    'instructional-text' => '<span class="must-copy-twitter-name">' . __('You must copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __('Twitter Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</span>',
                    'value' => '',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'twitter_name',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                        'empty_error_if' => array(
                            'attribute' => 'select#combine-twitter-messages-selector',
                            'operator' => '==',
                            'value' => 'user',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'sub_options_end' => 2,
                ),


                //Combine Instagram
                array(
                    'grouped_options_title' => __('Instagram', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Combine Instagram', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_instagram',
                    'name' => 'combine_instagram',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-combine-instagram-wrap',
                    ),
                ),


                //Instagram Type
                array(
                    'input_wrap_class' => 'combine_instagram_type',
                    'option_type' => 'select',
                    'label' => __('Instagram Type', 'feed-them-social'),
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-instagram-feed-styles-submenu-page" target="_blank">Instagram Options</a>.',
                    'type' => 'text',
                    'id' => 'combine_instagram_type',
                    'name' => 'combine_instagram_type',
                    'options' => array(
                        //User Feed
                        array(
                            'value' => 'user',
                            'label' => __('User Feed', 'feed-them-social'),
                        ),
                        //hastag Feed
                        array(
                            'value' => 'hashtag',
                            'label' => __('Hashtag Feed', 'feed-them-social'),
                        ),
                        //location Feed
                        array(
                            'value' => 'location',
                            'label' => __('Location Feed', 'feed-them-social'),
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'instagram_type',
                        'ifs' => 'combine_instagram',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-instagram-wrap',
                    ),
                ),


                //Combine Convert Instagram Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine-instagram-id-option-wrap',
                    'label' => __('Convert Instagram Name to ID', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_convert_instagram_username',
                    'name' => 'combine_convert_instagram_username',
                    'instructional-text' => __('You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below', 'feed-them-social'),
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => '',
                        'ifs' => 'combine_instagram',
                        'no_attribute' => 'yes'
                    ),
                ),

                //Instagram ID
                array(
                    'option_type' => 'input',
                    // 'input_wrap_class' => 'combine_instagram_name',
                    'label' => array(
                        1 => array(
                            'text' => __('Instagram ID # (required)', 'feed-them-social'),
                            'class' => 'combine-instagram-user-option-text',
                        ),
                        2 => array(
                            'text' => __('Hashtag (required)', 'feed-them-social'),
                            'class' => 'combine-instagram-hashtag-option-text',
                        ),
                        3 => array(
                            'text' => __('Location ID (required)', 'feed-them-social'),
                            'class' => 'combine-instagram-location-option-text',
                        ),
                    ),
                    'type' => 'text',
                    'id' => 'combine_instagram_name',
                    'name' => 'combine_instagram_name',
                    'required' => 'combine_streams',
                    'instructional-text' => array(
                        1 => array(
                            'text' => __('<div class="fts-insta-info-plus-wrapper">Choose a different ID if yours is not the first name below after clicking Convert Instagram Username button.</div><!-- the li list comes from an ajax call after looking up the user ID --><ul id="fts-instagram-username-picker-wrap-combined" class="fts-instagram-username-picker-wrap"></ul>', 'feed-them-social'),
                            'class' => 'combine-instagram-user-option-text',
                        ),
                        2 => array(
                            'text' => __('Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social'),
                            'class' => 'combine-instagram-hashtag-option-text',
                        ),
                        3 => array(
                            'text' => __('<strong>NOTE:</strong> The post count may not count proper in some location instances because private instagram photos are in the mix. We cannot pull private accounts photos in any location feed. Add your Location ID below.', 'feed-them-social'),
                            'class' => 'combine-instagram-location-option-text',
                        ),
                    ),
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'instagram_name',
                        'ifs' => 'combine_instagram',
                        'var_final_if' => 'no',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                    'sub_options_end' => 2,
                ),


                //Combine Pinterest
                array(
                    'grouped_options_title' => __('Pinterest', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Combine Pinterest', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_pinterest',
                    'name' => 'combine_pinterest',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-combine-pinterest-wrap',
                    ),
                ),
                //Pinterest Type
                array(
                    'input_wrap_class' => 'combine_pinterest_type',
                    'option_type' => 'select',
                    'label' => __('Pinterest Type', 'feed-them-social'),
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-pinterest-feed-styles-submenu-page" target="_blank">Pinterest Options</a> page then copy your <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">Pinterest and or Board Name</a> and paste them below based on your selection. A users board list is not available in this feed.',
                    'type' => 'text',
                    'id' => 'combine_pinterest_type',
                    'name' => 'combine_pinterest_type',
                    'options' => array(
                        //Single Board Pins
                        array(
                            'label' => __('Latest Pins from a User', 'feed-them-social'),
                            'value' => 'pins_from_user',
                        ),
                        //Single Board Pins
                        array(
                            'label' => __('Pins From a Specific Board', 'feed-them-social'),
                            'value' => 'single_board_pins',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'pinterest_type',
                        'ifs' => 'combine_pinterest',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-pinterest-wrap',
                    ),
                ),
                //Pinterest Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_pinterest_name',
                    'label' => __('Pinterest Name', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_pinterest_name',
                    'name' => 'combine_pinterest_name',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'pinterest_name',
                        'ifs' => 'combine_pinterest',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //Pinterest Board ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_board_id',
                    'label' => __('Pinterest Board ID', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_board_id',
                    'name' => 'combine_board_id',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'board_id',
                        'ifs' => 'pinterest_single_board_pins',
                    ),
                    'sub_options_end' => 2,
                ),
                //Combine Youtube
                array(
                    'grouped_options_title' => __('Youtube', 'feed-them-social'),
                    'option_type' => 'select',
                    'label' => __('Combine Youtube', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_youtube',
                    'name' => 'combine_youtube',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => '',
                        'empty_error_value' => '',
                        'no_attribute' => 'yes',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'main-combine-youtube-wrap',
                    ),
                ),
                //Youtube Type
                array(
                    'input_wrap_class' => 'combine_youtube_type',
                    'option_type' => 'select',
                    'label' => __('Youtube Type', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_youtube_type',
                    'name' => 'combine_youtube_type',
                    'options' => array( //Channel Feed
                        array(
                            'label' => __('Channel Feed', 'feed-them-social'),
                            'value' => 'channelID',
                        ), //Channel Playlist Feed
                        array(
                            'label' => __('Channel\'s Specific Playlist', 'feed-them-social'),
                            'value' => 'playlistID',
                        ),
                        //User's Most Recent Videos
                        array(
                            'label' => __('User\'s Most Recent Videos', 'feed-them-social'),
                            'value' => 'username',
                        ),
                        //User's Playlist
                        array(
                            'label' => __('User\'s Specific Playlist', 'feed-them-social'),
                            'value' => 'userPlaylist',
                        ),


                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => '',
                        'no_attribute' => 'yes',
                        'ifs' => 'combine_youtube',
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-youtube-wrap',
                    ),
                ),
                //Youtube Name
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_youtube_name',
                    'label' => __('YouTube Username', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_youtube_name',
                    'name' => 'combine_youtube_name',
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Username</a> and paste it below.',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'youtube_name',
                        'ifs' => 'combine_youtube',
                        'var_final_if' => 'yes',
                        'empty_error' => 'set',
                        'empty_error_value' => '',
                    ),
                ),
                //YouTube Playlist ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_playlist_id',
                    'label' => __('YouTube Playlist ID', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_playlist_id',
                    'name' => 'combine_playlist_id',
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Playlist ID</a> and paste them below.',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'playlist_id',
                        'ifs' => 'combine_youtube',
                    ),
                ),
                //YouTube Channel ID
                array(
                    'option_type' => 'input',
                    'input_wrap_class' => 'combine_channel_id',
                    'label' => __('YouTube Channel ID', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_channel_id',
                    'name' => 'combine_channel_id',
                    'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Channel ID</a> and paste it below.',
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'channel_id',
                        'ifs' => 'combine_youtube',
                    ),
                    'sub_options_end' => 2,
                ),
                //******************************************
                // Combine Streams Grid Options
                //******************************************
                //Facebook Page Display Posts in Grid
                array(
                    'grouped_options_title' => __('Grid', 'feed-them-social'),
                    'input_wrap_class' => 'combine_grid_option',
                    'option_type' => 'select',
                    'label' => __('Display Posts in Grid', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_grid_option',
                    'name' => 'combine_grid_option',
                    'options' => array(
                        1 => array(
                            'label' => __('No', 'feed-them-social'),
                            'value' => 'no',
                        ),
                        2 => array(
                            'label' => __('Yes', 'feed-them-social'),
                            'value' => 'yes',
                        ),
                    ),
                    'req_plugin' => 'combine_streams',
                    'short_attr' => array(
                        'attr_name' => 'grid',
                        'empty_error' => 'set',
                        'set_operator' => '==',
                        'set_equals' => 'yes',
                        'empty_error_value' => '',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-main-grid-options-wrap',
                    ),
                ),
                //Grid Column Width
                array(
                    'option_type' => 'input',
                    'label' => __('Grid Column Width', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_grid_column_width',
                    'name' => 'combine_grid_column_width',
                    'instructional-text' => __('NOTE:', 'feed-them-social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-social'),
                    'placeholder' => '310px ' . __('for example', 'feed-them-social'),
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'column_width',
                        'empty_error' => 'set',
                        'empty_error_value' => 'column_width=310px',
                        'ifs' => 'combine_grid',
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'combine-grid-options-wrap',
                    ),
                ),
                //Grid Spaces Between Posts
                array(
                    'option_type' => 'input',
                    'label' => __('Grid Spaces Between Posts', 'feed-them-social'),
                    'type' => 'text',
                    'id' => 'combine_grid_space_between_posts',
                    'name' => 'combine_grid_space_between_posts',
                    'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                    'req_plugin' => 'combine_streams',
                    //Relative to JS.
                    'short_attr' => array(
                        'attr_name' => 'space_between_posts',
                        'empty_error' => 'set',
                        'empty_error_value' => 'space_between_posts=10px',
                        'ifs' => 'combine_grid',
                    ),
                    'sub_options_end' => 2,
                ),
            ),
            //Final Shortcode ifs
            'shortcode_ifs' => array(
                'main_select' => array(
                    'if' => array(
                        'class' => 'select#shortcode-form-selector',
                        'operator' => '==',
                        'value' => 'combine-steams-shortcode-form',
                    ),
                ),
                'combine_facebook' => array(
                    'if' => array(
                        'class' => 'select#combine_facebook',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                //  'combine_twitter' => array(
                //      'if' => array(
                //          'class' => 'select#combine-twitter-messages-selector',
                //          'operator' => '==',
                //          'value' => '',
                //      ),
                //  ),
                //  'combine_twitter_search' => array(
                //      'if' => array(
                //          'class' => 'select#combine-twitter-messages-selector',
                //          'operator' => '==',
                //          'value' => '',
                //     ),
                //  ),
                'combine_instagram' => array(
                    'if' => array(
                        'class' => 'select#combine_instagram',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'combine_pinterest' => array(
                    'if' => array(
                        'class' => 'select#combine_pinterest',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'combine_youtube' => array(
                    'if' => array(
                        'class' => 'select#combine_youtube',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'combine_load_more' => array(
                    'if' => array(
                        'class' => 'select#fb_load_more_option',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'combine_grid' => array(
                    'if' => array(
                        'class' => 'select#combine_grid_option',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
                'yt_username' => array(
                    'if' => array(
                        'class' => 'select#combine_youtube_type',
                        'operator' => '==',
                        'value' => 'username',
                    ),
                ),
                'yt_userPlaylist' => array(
                    'if' => array(
                        'class' => 'select#combine_youtube_type',
                        'operator' => '==',
                        'value' => 'userPlaylist',
                    ),
                ),
                'yt_channelID' => array(
                    'if' => array(
                        'class' => 'select#combine_youtube_type',
                        'operator' => '==',
                        'value' => 'channelID',
                    ),
                ),
                'yt_playlistID' => array(
                    'if' => array(
                        'class' => 'select#combine_youtube_type',
                        'operator' => '==',
                        'value' => 'playlistID',
                    ),
                ),
                'pinterest_single_board_pins' => array(
                    'if' => array(
                        'class' => 'select#combine_pinterest_type',
                        'operator' => '==',
                        'value' => 'single_board_pins',
                    ),
                ),
            ),
            //Generator Info
            'generator_title' => __('Combine Streams Shortcode', 'feed-them-social'),
            'generator_class' => 'combine-streams-final-shortcode',
        );

        return $this->all_options['combine_streams'];
    }


    /**
     * Layout Options
     *
     * Options for the Layout Tab
     *
     * @return mixed
     * @since 1.0.0
     */
    function layout_options() {
        $this->all_options['layout'] = array(
            'section_attr_key' => 'facebook_',
            'section_title' => __('Layout Options', 'feed-them-gallery'),
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'fb-page-shortcode-form',
            'form_wrap_id' => 'fts-fb-page-form',
            //Token Check // We'll use these option for premium messages in the future
            'premium_msg_boxes' => array(
                'album_videos' => array(
                    'req_plugin' => 'fts_premium',
                    'msg' => '',
                ),
                'reviews' => array(
                    'req_plugin' => 'facebook_reviews',
                    'msg' => '',
                ),
            ),

            'main_options' => array(
                //Gallery Type
                array(
                    'input_wrap_class' => 'ft-wp-gallery-type',
                    'option_type' => 'select',
                    'label' => __('Choose the gallery type', 'feed-them-gallery') . '<br/><small>' . __('View all Gallery <a href="http://feedthemgallery.com/gallery-demo-one/" target="_blank">Demos</a>', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_type',
                    'name' => 'ft_gallery_type',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Responsive Image Gallery ', 'feed-them-gallery'),
                            'value' => 'gallery',
                        ),
                        array(
                            'label' => __('Image Gallery Collage', 'feed-them-gallery'),
                            'value' => 'gallery-collage',
                        ),
                        array(
                            'label' => __('Image Post', 'feed-them-gallery'),
                            'value' => 'post',
                        ),
                        array(
                            'label' => __('Image Post in Grid', 'feed-them-gallery'),
                            'value' => 'post-in-grid',
                        ),
                    ),
                ),
                //Show Photo Caption
                array(
                    'input_wrap_class' => 'fb-page-description-option-hide',
                    'option_type' => 'select',
                    'label' => __('Show Photo Caption', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_photo_caption',
                    'name' => 'ft_gallery_photo_caption',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Title and Description', 'feed-them-gallery'),
                            'value' => 'title_description',
                        ),
                        array(
                            'label' => __('Title', 'feed-them-gallery'),
                            'value' => 'title',
                        ),
                        array(
                            'label' => __('Description', 'feed-them-gallery'),
                            'value' => 'description',
                        ),
                        array(
                            'label' => __('None', 'feed-them-gallery'),
                            'value' => 'none',
                        ),
                    ),
                ),
                //******************************************
                // Facebook Grid Options
                //******************************************
                //Facebook Page Display Posts in Grid
                //     array(
                //         'grouped_options_title' => __('Grid', 'feed-them-gallery'),
                //         'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
                //         'option_type' => 'select',
                //         'label' => __('Display Posts in Grid', 'feed-them-gallery'),
                //         'type' => 'text',
                //         'id' => 'ft_gallery_grid_option',
                //         'name' => 'ft_gallery_grid_option',
                //         'default_value' => 'no',
                //         'options' => array(
                //             array(
                //                 'label' => __('No', 'feed-them-gallery'),
                //                 'value' => 'no',
                //            ),
                //             array(
                //                 'label' => __('Yes', 'feed-them-gallery'),
                //                 'value' => 'yes',
                //             ),
                //         ),
                //         //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                //         'sub_options' => array(
                //             'sub_options_wrap_class' => 'main-grid-options-wrap',
                //          ),
                //      ),
                array(
                    'input_wrap_class' => 'fb-page-columns-option-hide',
                    'option_type' => 'select',
                    'label' => __('Number of Columns', 'feed-them-gallery'),
                    'type' => 'text',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below.', 'feed-them-gallery'),
                    'id' => 'ft_gallery_columns',
                    'name' => 'ft_gallery_columns',
                    'default_value' => '4',
                    'options' => array(
                        array(
                            'label' => __('1', 'feed-them-gallery'),
                            'value' => '1',
                        ),
                        array(
                            'label' => __('2', 'feed-them-gallery'),
                            'value' => '2',
                        ),
                        array(
                            'label' => __('3', 'feed-them-gallery'),
                            'value' => '3',
                        ),
                        array(
                            'label' => __('4', 'feed-them-gallery'),
                            'value' => '4',
                        ),
                        array(
                            'label' => __('5', 'feed-them-gallery'),
                            'value' => '5',
                        ),
                        array(
                            'label' => __('6', 'feed-them-gallery'),
                            'value' => '6',
                        ),
                        array(
                            'label' => __('7', 'feed-them-gallery'),
                            'value' => '7',
                        ),
                        array(
                            'label' => __('8', 'feed-them-gallery'),
                            'value' => '8',
                        )
                    ),
                ),
                array(
                    'input_wrap_class' => 'fb-page-columns-option-hide',
                    'option_type' => 'select',
                    'label' => __('Force Columns', 'feed-them-gallery') . '<br/><small>' . __('Yes, will force image columns. No, will allow the images to be resposive for smaller devices', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_force_columns',
                    'name' => 'ft_gallery_force_columns',
                    'default_value' => '',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),

                    ),
                ),
                //Grid Column Width
                array(
                    'input_wrap_class' => 'fb-page-grid-option-hide fb-page-columns-option-hide ftg-hide-for-columns',
                    'option_type' => 'input',
                    'label' => __('Grid Column Width', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_grid_column_width',
                    'name' => 'ft_gallery_grid_column_width',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-gallery'),
                    'placeholder' => '310px ' . __('for example', 'feed-them-gallery'),
                    'default_value' => '310px',
                    'value' => '',
                    //         //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    //  'sub_options' => array(
                    //      'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',
                    //  ),
                ),
                //Grid Spaces Between Posts
                array(
                    'input_wrap_class' => 'fb-page-grid-option-hide fb-page-grid-option-border-bottom',
                    'option_type' => 'input',
                    'label' => __('Space between Images', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_grid_space_between_posts',
                    'name' => 'ft_gallery_grid_space_between_posts',
                    'placeholder' => '1px ' . __('for example', 'feed-them-gallery'),
                    'default_value' => '1px',
                    // 'sub_options_end' => 2,
                ),
                //Show Name
                array(
                    'input_wrap_class' => 'ft-gallery-user-name',
                    'option_type' => 'input',
                    'label' => __('User Name', 'feed-them-gallery') . '<br/><small>' . __('Company or user who took this photo', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_username',
                    'name' => 'ft_gallery_username',
                    'placeholder' => '',
                    'default_value' => '',
                ),
                //Show Name Link
                array(
                    'option_type' => 'input',
                    'label' => __('User Custom Link', 'feed-them-gallery') . '<br/><small>' . __('Custom about page or social media page link', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_user_link',
                    'name' => 'ft_gallery_user_link',
                    'placeholder' => '',
                    'default_value' => '',
                ),
                //Show Share
                array(
                    'input_wrap_class' => 'ft-gallery-share',
                    'option_type' => 'select',
                    'label' => __('Show Share Options', 'feed-them-gallery') . '<br/><small>' . __('Appears in the bottom left corner and in popup', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_wp_share',
                    'name' => 'ft_gallery_wp_share',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                ),
                //Show Date
                array(
                    'input_wrap_class' => 'ft-gallery-date',
                    'option_type' => 'select',
                    'label' => __('Show Date', 'feed-them-gallery') . '<br/><small>' . __('Date image was uploaded', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_wp_date',
                    'name' => 'ft_gallery_wp_date',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                ),
                //Show Icon
                array(
                    'input_wrap_class' => 'ft-gallery-icon',
                    'option_type' => 'select',
                    'label' => __('Show Wordpress Icon', 'feed-them-gallery') . '<br/><small>' . __('Appears in the top left corner', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_wp_icon',
                    'name' => 'ft_gallery_wp_icon',
                    'default_value' => 'no',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                ),

                //Words per photo caption
                //  array(
                // 'option_type' => 'input',
                // 'label' => __('# of words per photo caption', 'feed-them-gallery') . '<br/><small>' . __('Typing 0 removes the photo caption', 'feed-them-gallery') . '</small>',
                // 'type' => 'hidden',
                //  'id' => 'ft_gallery_word_count_option',
                //  'name' => 'ft_gallery_word_count_option',
                //  'placeholder' => '',
                //  'default_value' => '',
                //               ),

                // Image Sizes on page
                array(
                    'input_wrap_class' => 'ft-images-sizes-page',
                    'option_type' => 'ft-images-sizes-page',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called <a href="http://sidebar-support.com/wp-admin/plugin-install.php?s=regenerate+thumbnails&tab=search&type=term" target="_blank">Regenerate Thumbnails</a> does an amazing job of that.', 'feed-them-gallery'),
                    'label' => __('Image Size on Page', 'feed-them-gallery'),
                    'class' => 'ft-gallery-images-sizes-page',
                    'type' => 'select',
                    'id' => 'ft_gallery_images_sizes_page',
                    'name' => 'ft_gallery_images_sizes_page',
                    'default_value' => 'medium',
                    'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),

                //Max-width for Images & Videos
                array(
                    'option_type' => 'input',
                    'label' => __('Max-width for Images', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_max_image_vid_width',
                    'name' => 'ft_gallery_max_image_vid_width',
                    'placeholder' => '500px',
                    'default_value' => '',
                ),
                //Gallery Width
                array(
                    'option_type' => 'input',
                    'label' => __('Gallery Max-width', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_width',
                    'name' => 'ft_gallery_width',
                    'placeholder' => '500px',
                    'default_value' => '',
                ),
                //Gallery Height for scrolling feeds using Post format only, this does not work for grid or gallery options except gallery squared because it does not use masonry. For all others it will be hidden
                array(
                    'input_wrap_class' => 'ft-gallery-height',
                    'option_type' => 'input',
                    'label' => __('Gallery Height<br/><small>' . __('Set the height to have a scrolling feed.', 'feed-them-gallery') . '</small>', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_height',
                    'name' => 'ft_gallery_height',
                    'placeholder' => '600px',
                    'default_value' => '',
                ),
                //Gallery Margin
                array(
                    'option_type' => 'input',
                    'label' => __('Gallery Margin', 'feed-them-gallery') . '<br/><small>' . __('To center feed type auto', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_margin',
                    'name' => 'ft_gallery_margin',
                    'placeholder' => 'auto',
                    'default_value' => 'auto',
                ),
                //Gallery Padding
                array(
                    'option_type' => 'input',
                    'label' => __('Gallery Padding', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_padding',
                    'name' => 'ft_gallery_padding',
                    'placeholder' => '10px',
                    'default_value' => '',
                ),
                //******************************************
                // Gallery Popup
                //******************************************
                //Display Photos in Popup
                array(
                    'grouped_options_title' => __('Popup', 'feed-them-gallery'),
                    'option_type' => 'select',
                    'label' => __('Display Photos in Popup', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_popup',
                    'name' => 'ft_gallery_popup',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-popup-wrap',
                    ),
                    'sub_options_end' => true,
                ),
                // Image Sizes in popup
                array(
                    'input_wrap_class' => 'ft-images-sizes-popup',
                    'option_type' => 'ft-images-sizes-popup',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('If for some reason the image size you choose does not appear on in your popup you may need to regenerate your images. This free plugin called <a href="http://sidebar-support.com/wp-admin/plugin-install.php?s=regenerate+thumbnails&tab=search&type=term" target="_blank">Regenerate Thumbnails</a> does an amazing job of that.', 'feed-them-gallery'),
                    'label' => __('Image Size in Popup', 'feed-them-gallery'),
                    'class' => 'ft-gallery-images-sizes-popup',
                    'type' => 'select',
                    'id' => 'ft_gallery_images_sizes_popup',
                    'name' => 'ft_gallery_images_sizes_popup',
                    'default_value' => '',
                    'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                array(
                    'input_wrap_class' => 'ft-popup-display-options',
                    'option_type' => 'select',
                    'label' => __('Popup Options', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_popup_display_options',
                    'name' => 'ft_popup_display_options',
                    'default_value' => 'no',
                    'options' => array(
                        array(
                            'label' => __('Default', 'feed-them-gallery'),
                            'value' => 'default',
                        ),
                        array(
                            'label' => __('Full Width & Info below Photo', 'feed-them-gallery'),
                            'value' => 'full-width-second-half-bottom',
                        ),
                        array(
                            'label' => __('Full Width, Photo Only', 'feed-them-gallery'),
                            'value' => 'full-width-photo-only',
                        ),
                    )
                ),


                //******************************************
                // Gallery Load More Options
                //******************************************
                //Load More Button

                //# of Photos
                array(
                    'grouped_options_title' => __('Load More', 'feed-them-gallery'),
                    'option_type' => 'input',
                    'label' => __('# of Photos', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_photo_count',
                    'name' => 'ft_gallery_photo_count',
                    'default_value' => '',
                    'placeholder' => __('', 'feed-them-gallery'),
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'facebook-loadmore-wrap',
                    ),
                ),
                array(

                    'option_type' => 'select',
                    'label' => __('Load More Button', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_load_more_option',
                    'name' => 'ft_gallery_load_more_option',
                    'default_value' => 'no',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                    )
                ),
                //Load More Style
                array(
                    'option_type' => 'select',
                    'label' => __('Load More Style', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_load_more_style',
                    'name' => 'ft_gallery_load_more_style',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-gallery'),
                    'default_value' => 'button',
                    'options' => array(
                        1 => array(
                            'label' => __('Button', 'feed-them-gallery'),
                            'value' => 'button',
                        ),
                        2 => array(
                            'label' => __('AutoScroll', 'feed-them-gallery'),
                            'value' => 'autoscroll',
                        ),
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
                    ),
                    'sub_options_end' => true,
                ),

                //Load more Button Width
                array(
                    'option_type' => 'input',
                    'label' => __('Load more Button Width', 'feed-them-gallery') . '<br/><small>' . __('Leave blank for auto width', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_loadmore_button_width',
                    'name' => 'ft_gallery_loadmore_button_width',
                    'placeholder' => '300px ' . __('for example', 'feed-them-gallery'),
                    'default_value' => '300px',
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',
                    ),
                ),
                //Load more Button Margin
                array(
                    'option_type' => 'input',
                    'label' => __('Load more Button Margin', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_loadmore_button_margin',
                    'name' => 'ft_gallery_loadmore_button_margin',
                    'placeholder' => '10px ' . __('for example', 'feed-them-gallery'),
                    'default_value' => '10px',
                    'value' => '',
                    'sub_options_end' => 2,
                ),

                //******************************************
                // Gallery Pagination Options
                //******************************************
                //Pagination


                //Load More Style
                array(
                    'grouped_options_title' => __('Pagination', 'feed-them-gallery'),
                    'option_type' => 'select',
                    'label' => __('Show pagination', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_show_pagination',
                    'name' => 'ft_gallery_show_pagination',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('This will display the number of images you have in your gallery, and will appear centered at the bottom of your image feed. For Example: 4 of 50 (4 being the number of images you have loaded on the page already and 50 being the total number of images in the gallery.', 'feed-them-gallery'),
                    'default_value' => 'yes',
                    'options' => array(
                        1 => array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        2 => array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                    //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                    'sub_options' => array(
                        'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
                    ),
                    'sub_options_end' => true,
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
    function color_options() {
        $this->all_options['colors'] = array(
            'section_attr_key' => 'facebook_',
            'section_title' => __('Feed Color Options', 'feed-them-gallery'),
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'fb-page-shortcode-form',
            'form_wrap_id' => 'fts-fb-page-form',
            'main_options' => array(

                //Feed Background Color
                array(
                    'option_type' => 'input',
                    'label' => __('Background Color', 'feed-them-gallery'),
                    'class' => 'ft-gallery-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-feed-background-color-input',
                    'name' => 'ft_gallery_feed_background_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Feed Grid Background Color
                array(
                    'option_type' => 'input',
                    'label' => __('Grid Posts Background Color', 'feed-them-gallery'),
                    'class' => 'fb-feed-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-grid-posts-background-color-input',
                    'name' => 'ft_gallery_grid_posts_background_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Border Bottom Color
                array(
                    'option_type' => 'input',
                    'label' => __('Border Bottom Color', 'feed-them-gallery'),
                    'class' => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-border-bottom-color-input',
                    'name' => 'ft_gallery_border_bottom_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Loadmore background Color
                array(
                    'grouped_options_title' => __('Loadmore Button', 'feed-them-gallery'),
                    'option_type' => 'input',
                    'label' => __('Background Color', 'feed-them-gallery'),
                    'class' => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-loadmore-background-color-input',
                    'name' => 'ft_gallery_loadmore_background_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Loadmore background Color
                array(
                    'option_type' => 'input',
                    'label' => __('Text Color', 'feed-them-gallery'),
                    'class' => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-loadmore-text-color-input',
                    'name' => 'ft_gallery_loadmore_text_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Pagination Color
                array(
                    'grouped_options_title' => __('Pagination Color', 'feed-them-gallery'),
                    'option_type' => 'input',
                    'label' => __('Text Color', 'feed-them-gallery'),
                    'class' => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'text',
                    'id' => 'ft-gallery-pagination-text-color-input',
                    'name' => 'ft_gallery_pagination_text_color',
                    'default_value' => '',
                    'placeholder' => __('#ddd', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
            )
        );

        return $this->all_options['colors'];
    } //END LAYOUT OPTIONS

    /**
     * Woocommerce Options
     *
     * Options for the Woocommerce Tab
     *
     * @return mixed
     * @since 1.0.0
     */
    function woocommerce_options() {

        $this->all_options['woocommerce'] = array(
            //required_prem_plugin must match the array key returned in ft_gallery_required_plugins function
            'required_prem_plugin' => 'feed_them_gallery_premium',
            'input_wrap_class' => 'ft-woocommerce-styles',
            'section_attr_key' => 'woocommerce_',
            'section_title' => __('Woocommerce Options', 'feed-them-gallery'),
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'fb-page-shortcode-form',
            'form_wrap_id' => 'fts-fb-page-form',
            'main_options' => array(
                //Show Purchase Button
                array(
                    'input_wrap_class' => 'ft-gallery-purchase-link',
                    'option_type' => 'select',
                    'label' => __('Show Purchase Link', 'feed-them-gallery') . '<br/><small>' . __('Appears on the page and popup', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_purchase_link',
                    'name' => 'ft_gallery_purchase_link',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                    ),
                ),
                //Purchase Button Text
                array(
                    'option_type' => 'input',
                    'label' => __('Change Purchase Link text', 'feed-them-gallery') . '<br/><small>' . __('The default word is Purchase', 'feed-them-gallery') . '</small>',
                    'type' => 'text',
                    'id' => 'ft_gallery_purchase_word',
                    'name' => 'ft_gallery_purchase_word',
                    'placeholder' => __('Purchase', 'feed-them-gallery'),
                    'default_value' => '',
                ),
                array(
                    'option_type' => 'checkbox',
                    'label' => __('Auto Create a product for each image uploaded.', 'ft-gallery') . '<br/><small>' . __('You must have a "Single Image Model Product" selected for this option to work.', 'ft-gallery') . '</small>',
                    'class' => 'ft-gallery-auto-image-woo-prod',
                    'type' => 'checkbox',
                    'id' => 'ft_gallery_auto_image_woo_prod',
                    'name' => 'ft_gallery_auto_image_woo_prod',
                    'default_value' => '',

                ),

            ),
        );


        return $this->all_options['woocommerce'];
    } //END LAYOUT OPTIONS

    /**
     * Woocommerce Extra Options
     *
     * These are Gallery to Woo options (just for saving not for display)
     *
     * @return mixed
     * @since 1.0.0
     */
    function woocommerce_extra_options() {

        $this->all_options['woocommerce_exta'] = array(
            'main_options' => array(
                //required_prem_plugin must match the array key returned in ft_gallery_required_plugins function
                'required_prem_plugin' => 'feed_them_gallery_premium',
                //******************************************
                // Images to Products
                //******************************************
                //Automatically turn created Images to products
                array(
                    'option_type' => 'select',
                    'default_value' => '',
                    'name' => 'ft_gallery_image_to_woo_model_prod',
                ),
                array(
                    'option_type' => 'select',
                    'default_value' => '',
                    'name' => 'ft_gallery_zip_to_woo_model_prod',
                ),
                array(
                    'option_type' => 'checkbox',
                    'default_value' => '',
                    'name' => 'ft_gallery_auto_image_woo_prod',
                ),
            )
        );

        return $this->all_options['woocommerce_exta'];
    }

    /**
     * Watermark Options
     *
     * Options for the Watermark Tab
     *
     * @return mixed
     * @since 1.0.0
     */
    function watermark_options() {
        $this->all_options['watermark'] = array(
            //required_prem_plugin must match the array key returned in ft_gallery_required_plugins function
            'required_prem_plugin' => 'feed_them_gallery_premium',
            'section_attr_key' => 'facebook_',
            'section_title' => __('Watermark Options', 'feed-them-gallery'),
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            //Form Info
            'form_wrap_classes' => 'fb-page-shortcode-form',
            'form_wrap_id' => 'fts-fb-page-form',
            'main_options' => array(
                // Disable Right Click
                array(
                    'input_wrap_class' => 'ft-watermark-disable-right-click',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('This option will disable the right click option on desktop computers so people cannot look at the source code. This is not fail safe but for the vast majority this is enough to deter people from trying to find the image source.', 'feed-them-gallery'),
                    'option_type' => 'select',
                    'label' => __('Disable Right Click', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_watermark_disable_right_click',
                    'name' => 'ft_gallery_watermark_disable_right_click',
                    'default_value' => '',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        )
                    ),
                ),
                // Use Watermark Options
                array(
                    'input_wrap_class' => 'ft-watermark-enable-options',
                    'option_type' => 'select',
                    'label' => __('Use Options Below', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_watermark_enable_options',
                    'name' => 'ft_gallery_watermark_enable_options',
                    'default_value' => 'no',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        )
                    ),
                ),

                //Choose Watermark Image
                array(
                    'option_type' => 'input',
                    'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('Upload the exact image size you want to display, we will not rescale the image in anyway.', 'feed-them-gallery'),
                    'label' => __('Watermark Image', 'feed-them-gallery'),
                    'id' => 'ft-watermark-image',
                    'name' => 'ft-watermark-image',
                    'class' => '',
                    'type' => 'button',
                    'default_value' => __('Upload or Choose Watermark', 'feed-them-gallery'),
                    'placeholder' => '',
                    'value' => '',
                    'autocomplete' => 'off',
                ),
                //Watermark Image Link for front end if user does not use imagick or GD library method
                array(
                    'input_wrap_class' => 'ft-watermark-hide-these-options',
                    'option_type' => 'input',
                    // 'label' => __('Watermark Image', 'feed-them-gallery'),
                    // 'class' => 'fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'hidden',
                    'id' => 'ft_watermark_image_input',
                    // 'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-gallery'),

                    'name' => 'ft_watermark_image_input',
                    'default_value' => '',
                    // 'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Watermark Image ID so we can pass it to merge the watermark over images
                array(
                    'input_wrap_class' => 'ft-watermark-hide-these-options',
                    'option_type' => 'input',
                    // 'label' => __('Watermark Image', 'feed-them-gallery'),
                    // 'class' => 'fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
                    'type' => 'hidden',
                    'id' => 'ft_watermark_image_id',
                    // 'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-gallery') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-gallery'),

                    'name' => 'ft_watermark_image_id',
                    'default_value' => '',
                    // 'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),


                //Watermark Options
                array(
                    'input_wrap_class' => 'ft-watermark-enabled',
                    'option_type' => 'select',
                    'label' => __('Watermark Type', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_watermark',
                    'name' => 'ft_gallery_watermark',
                    'default_value' => 'yes',
                    'options' => array(
                        array(
                            'label' => __('Watermark Overlay Image (Does not Imprint logo on Image)', 'feed-them-gallery'),
                            'value' => 'overlay',
                        ),
                        array(
                            'label' => __('Watermark Image (Imprint logo on the selected image sizes)', 'feed-them-gallery'),
                            'value' => 'imprint',
                        )
                    ),
                ),

                //Watermark Options
                array(
                    'input_wrap_class' => 'ft-watermark-overlay-options',
                    'option_type' => 'select',
                    'label' => __('Overlay Options', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_watermark',
                    'name' => 'ft_gallery_watermark_overlay_enable',
                    'default_value' => 'popup-only',
                    'options' => array(
                        array(
                            'label' => __('Select an Option', 'feed-them-gallery'),
                            'value' => '',
                        ),
                        array(
                            'label' => __('Watermark in popup only', 'feed-them-gallery'),
                            'value' => 'popup-only',
                        ),
                        array(
                            'label' => __('Watermark for image on page only', 'feed-them-gallery'),
                            'value' => 'page-only',
                        ),
                        array(
                            'label' => __('Watermark for image on page and popup', 'feed-them-gallery'),
                            'value' => 'page-and-popup',
                        ),
                    ),
                ),

                //Hidden Input to set array
                array(
                    'input_wrap_class' => 'ft-watermark-hidden-options ft-gallery-image-sizes-checkbox-wrap-label',
                    'option_type' => 'checkbox-image-sizes',
                    'instructional-text' => '<strong>' . __('IMPORTANT:', 'feed-them-gallery') . '</strong> ' . __('This option will permanently mark your chosen image size once you click the publish button or update button. Set the opacity of your <strong>Watermark Image</strong> before you upload it above for this option. We suggest using a png for the best clarity and not a gif.', 'feed-them-gallery'),
                    'label' => __('Image Sizes', 'feed-them-gallery'),
                    'class' => 'ft-watermark-opacity',
                    'type' => 'hidden',
                    'id' => 'ft_watermark_image_sizes',
                    'name' => 'ft_watermark_image_sizes',
                    'default_value' => '',
                    'value' => '',
                    'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),


                //Watermark Image Sizes to convert
                array(
                    'input_wrap_class' => 'ft-watermark-hidden-options ft-gallery-image-sizes-checkbox-wrap',
                    'option_type' => 'checkbox-dynamic-image-sizes',
                    'label' => __('', 'feed-them-gallery'),
                    'class' => 'ft-watermark-opacity',
                    'type' => 'checkbox',
                    'id' => 'ft_watermark_image_',
                    'name' => '',
                    'default_value' => '',
                    'placeholder' => __('', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Duplicate Full Image before it is watermarked, usefull if zip option is being used and or selling full image
                array(
                    'input_wrap_class' => 'ft-watermark-duplicate-image',
                    'option_type' => 'select',
                    'label' => __('Duplicate Full Image<br/>before watermarking', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_duplicate_image',
                    'name' => 'ft_gallery_duplicate_image',
                    'default_value' => '',
                    'options' => array(
                        array(
                            'label' => __('No', 'feed-them-gallery'),
                            'value' => 'no',
                        ),
                        array(
                            'label' => __('Yes', 'feed-them-gallery'),
                            'value' => 'yes',
                        ),
                    ),
                ),
                //Watermark Opacity
                array(
                    'input_wrap_class' => 'ft-gallery-watermark-opacity',
                    'option_type' => 'input',
                    'label' => __('Image Opacity', 'feed-them-gallery'),
                    'class' => 'ft-watermark-opacity',
                    'type' => 'text',
                    'id' => 'ft_watermark_image_opacity',
                    'name' => 'ft_watermark_image_opacity',
                    'default_value' => '',
                    'placeholder' => __('.5 for example', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),
                //Watermark Position
                array(
                    'input_wrap_class' => 'ft-watermark-position',
                    'option_type' => 'select',
                    'label' => __('Watermark Position', 'feed-them-gallery'),
                    'type' => 'text',
                    'id' => 'ft_gallery_position',
                    'name' => 'ft_gallery_position',
                    'default_value' => 'bottom-right',
                    'options' => array(
                        array(
                            'label' => __('Centered', 'feed-them-gallery'),
                            'value' => 'center',
                        ),
                        array(
                            'label' => __('Top Right', 'feed-them-gallery'),
                            'value' => 'top-right',
                        ),
                        array(
                            'label' => __('Top Left', 'feed-them-gallery'),
                            'value' => 'top-left',
                        ),
                        array(
                            'label' => __('Top Center', 'feed-them-gallery'),
                            'value' => 'top-center',
                        ),
                        array(
                            'label' => __('Bottom Right', 'feed-them-gallery'),
                            'value' => 'bottom-right',
                        ),
                        array(
                            'label' => __('Bottom Left', 'feed-them-gallery'),
                            'value' => 'bottom-left',
                        ),
                        array(
                            'label' => __('Bottom Center', 'feed-them-gallery'),
                            'value' => 'bottom-center',
                        ),
                    ),
                ),
                //watermark Image Margin
                array(
                    'option_type' => 'input',
                    'label' => __('Watermark Margin', 'feed-them-gallery'),
                    'class' => 'ft-watermark-image-margin',
                    'type' => 'text',
                    'id' => 'ft_watermark_image_margin',
                    'name' => 'ft_watermark_image_margin',
                    'default_value' => '',
                    'placeholder' => __('10px', 'feed-them-gallery'),
                    'autocomplete' => 'off',
                ),

            )
        );

        return $this->all_options['watermark'];
    } //END LAYOUT OPTIONS

    /**
     * All Gallery Options
     *
     * Function to return all Gallery options
     *
     * @return string
     * @since 1.0.0
     */
    function all_gallery_options() {


        return $this->all_options;


    }

}