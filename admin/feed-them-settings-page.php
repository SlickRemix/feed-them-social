<?php

namespace feedthemsocial;
/**
 * Class FTS Settings Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */

class FTS_settings_page
{
    /**
     * Construct
     *
     * FTS_settings_page constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
    }

    /**
     * Feed Them Settings Page
     *
     * Main Settings Page.
     *
     * @since 1.9.6
     */
    function feed_them_settings_page() {
        $fts_functions = new feed_them_social_functions();

        if (!function_exists('curl_init')) {
            print '<div class="error"><p>' . __('Warning: cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social') . '</p></div>';
        } ?>

        <div class="feed-them-social-admin-wrap">
            <div class="fts-backg"></div>
            <div class="fts-content">
                <h1 class="fts-logo-header"><?php _e('Feed Them Social', 'feed-them-social'); ?></h1>

                <div class="feed-them-icon-wrap">
                    <a href="javascript:;" class="youtube-icon"></a>
                    <a href="javascript:;" class="twitter-icon"></a>
                    <a href="javascript:;" class="facebook-icon"></a>
                    <a href="javascript:;" class="instagram-icon"></a>
                    <a href="javascript:;" class="pinterest-icon"></a>

                        <div id="discount-for-review"><a href="admin.php?page=fts-license-page"><?php _e('View Extensions & Demos', 'feed-them-social'); ?></a></div>
                </div>

                <div class="fts-tabs" id="fts-tabs">

                    <label for="fts-tab1"
                           class="fts-tab1 fts-tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'general_options') {
                               echo 'tab-active';
                           } elseif (!isset($_GET['tab'])) {
                               echo 'tab-active';
                           } ?>" id="general_options">
                        <span><?php _e('Create Shortcode', 'sidebar-support') ?></span>
                    </label>

                    <label for="fts-tab2"
                           class="fts-tab2 fts-tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'global_options') {
                               echo ' tab-active';
                           } ?>" id="global_options">
                        <span><?php _e('Global Options', 'sidebar-support') ?></span>
                    </label>

                    <div id="fts-tab-content1"
                         class="fts-tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'general_options' || !isset($_GET['tab'])) {
                             echo ' pane-active';
                         } ?>">
                        <section>

                            <h2 class="fts-logo-subheader"><?php _e('Create Shortcode for Social Network', 'feed-them-social'); ?></h2>
                            <div class="use-of-plugin"><?php _e('Please select what type of feed you would like using the select option below. After setting your options click the green Generate Shortcode button, then copy and paste the shortcode to a page, post or widget.', 'feed-them-social'); ?></div>

                            <form class="feed-them-social-admin-form" id="feed-selector-form">
                                <select id="shortcode-form-selector">
                                    <option value=""><?php _e('Select a Social Network', 'feed-them-social'); ?> </option>
                                    <option value="fts-fb-page-shortcode-form"><?php _e('Facebook Feed', 'feed-them-social'); ?></option>
                                    <option value="combine-steams-shortcode-form"><?php _e('Combine Streams Feed', 'feed-them-social'); ?></option>
                                    <option value="twitter-shortcode-form"><?php _e('Twitter Feed', 'feed-them-social'); ?></option>
                                    <option value="vine-shortcode-form" style="display: none"><?php _e('Vine Feed', 'feed-them-social'); ?></option>
                                    <option value="instagram-shortcode-form"><?php _e('Instagram Feed', 'feed-them-social'); ?></option>
                                    <option value="youtube-shortcode-form"><?php _e('YouTube Feed'); ?></option>
                                    <option value="pinterest-shortcode-form"><?php _e('Pinterest Feed', 'feed-them-social'); ?></option>
                                </select>
                            </form><!--/feed-them-social-admin-form-->

                            <?php

                            $step2_custom_message = __('<br/><strong>STEP 2:</strong> Generator your custom shortcode using the options below, then click generate shortcode and paste that to a Page, Post or widget.', 'feed-them-social');

                            $limitforpremium = !is_plugin_active('feed-them-premium/feed-them-premium.php') ? '<small class="fts-required-more-posts"><br/>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>' : '';

                            if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                                $facebookReviewsTokenCheck = array(
                                    1 => array(
                                        'option_name' => 'fts_facebook_custom_api_token',
                                        'no_token_msg' => __('<strong>STEP 1:</strong> Please get your API Token on our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                    ),
                                    2 => array(
                                        'option_name' => 'fts_facebook_custom_api_token_biz',
                                        'no_token_msg' => __('<strong>STEP 1:</strong> Please add a Facebook Page Reviews API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page. ' . $step2_custom_message . '', 'feed-them-social'),
                                        'req_plugin' => 'facebook_reviews',
                                    )
                                );

                            } else {
                                $facebookReviewsTokenCheck = array(
                                    1 => array(
                                        'option_name' => 'fts_facebook_custom_api_token',
                                        'no_token_msg' => __('<strong>STEP 1:</strong> Please get your API Token on our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                    ),
                                );
                            }
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
                            $feed_settings_array = array(
                                //******************************************
                                // Combine Streams Feed
                                //******************************************
                                'combine_streams' => array(
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
                                            'placeholder' => __('6 is the default number', 'feed-them-social'),
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
                                            'label' => __('# of Posts per Social Network', 'feed-them-social'). '<br/><small>' . __('NOT the combined total', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            //'instructional-text' => __('', 'feed-them-social'),
                                            'id' => 'combine_social_network_post_count',
                                            'name' => 'combine_social_network_post_count',
                                            'value' => '',
                                            'placeholder' => __('1 is the default number', 'feed-them-social'),
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
                                            'placeholder' => '45 ' . __('is the default number', 'feed-them-social'),
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
                                            'color_picker' => 'yes',
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
                                        //Social Icon
                                        array(
                                            'input_wrap_class' => 'combine_show_social_icon',
                                            'option_type' => 'select',
                                            'label' => __('Show Social Icon', 'feed-them-social'). '<br/><small>' . __('Right, Left or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'combine_show_social_icon',
                                            'name' => 'combine_show_social_icon',
                                            'req_plugin' => 'combine_streams',
                                            'options' => array(
                                                array(
                                                    'label' => __('Right', 'feed-them-social'),
                                                    'value' => 'right',
                                                ),
                                                array(
                                                    'label' => __('Left', 'feed-them-social'),
                                                    'value' => 'left',
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
                                        //Show Description below image or video Name
                                        array(
                                            'input_wrap_class' => 'combine_show_media',
                                            'option_type' => 'select',
                                            'label' => __('Show Image/Video', 'feed-them-social') . '<br/><small>' . __('Bottom (default) or Top of Post', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'combine_show_media',
                                            'name' => 'combine_show_media',
                                            'req_plugin' => 'combine_streams',
                                            'options' => array(
                                                array(
                                                    'label' => __('Below Username, Date & Description', 'feed-them-social'),
                                                    'value' => 'bottom',
                                                ),
                                                array(
                                                    'label' => __('Above Username, Date & Description', 'feed-them-social'),
                                                    'value' => 'top',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'show_media',
                                            ),
                                        ), //Show Username
                                        array(
                                            'input_wrap_class' => 'combine_hide_date',
                                            'option_type' => 'select',
                                            'label' => __('Show Username', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'combine_hide_date',
                                            'name' => 'combine_hide_date',
                                            'req_plugin' => 'combine_streams',
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
                                            'short_attr' => array(
                                                'attr_name' => 'show_date',
                                            ),
                                        ),
                                        //Show Date
                                        array(
                                            'input_wrap_class' => 'combine_hide_name',
                                            'option_type' => 'select',
                                            'label' => __('Show Date', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'combine_hide_name',
                                            'name' => 'combine_hide_name',
                                            'req_plugin' => 'combine_streams',
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
                                            'short_attr' => array(
                                                'attr_name' => 'show_name',
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
                                            'label' => __('Facebook Name', 'feed-them-social'),
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
                                     //   array(
                                     //       'option_type' => 'input',
                                     //       'input_wrap_class' => 'combine-instagram-id-option-wrap',
                                     //       'label' => __('Convert Instagram Name to ID', 'feed-them-social'),
                                     //       'type' => 'text',
                                     //       'id' => 'combine_convert_instagram_username',
                                     //       'name' => 'combine_convert_instagram_username',
                                     //       'instructional-text' => __('You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below', 'feed-them-social'),
                                     //       'req_plugin' => 'combine_streams',
                                     //       //Relative to JS.
                                     //       'short_attr' => array(
                                     //           'attr_name' => '',
                                     //           'ifs' => 'combine_instagram',
                                     //           'no_attribute' => 'yes'
                                     //       ),
                                     //   ),

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
                                                    'text' => __('<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social') ,
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
                                            'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Username</a> and paste it below.',
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
                                            'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Playlist ID</a> and paste them below.',
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
                                            'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Channel ID</a> and paste it below.',
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
                                            'instructional-text' =>  '<strong> ' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-social'),
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
                                ),//End Combine Streams
                                //******************************************
                                // Facebook Page Feed
                                //******************************************
                                'facebook' => array(
                                    'section_attr_key' => 'facebook_',
                                    'section_title' => __('Facebook Page Shortcode Generator', 'feed-them-social'),
                                    'section_wrap_class' => 'fts-facebook_page-shortcode-form',
                                    //Form Info
                                    'form_wrap_classes' => 'fts-fb-page-shortcode-form',
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
                                 //       //Facebook Page Single Event Posts
                                 //       array(
                                 //           'value' => 'event',
                                 //           'title' => __('Facebook Page Single Event Posts', 'feed-them-social'),
                                 //       ),
                                 //       //Facebook Group
                                 //       array(
                                 //           'value' => 'group',
                                 //           'title' => __('Facebook Group', 'feed-them-social'),
                                 //       ),
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
                                            'placeholder' => __('6 is the default number', 'feed-them-social'),
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
                                            'label' => __('Show Page Title', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
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
                                            'label' => __('Align Title', 'feed-them-social'). '<br/><small>' . __('Left, Center or Right', 'feed-them-social') . '</small>',
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
                                            'label' => __('Show Page Description', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
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











                                        //Show Description below image or video Name
                                        array(
                                            'input_wrap_class' => 'facebook_show_media',
                                            'option_type' => 'select',
                                            'label' => __('Show Image/Video', 'feed-them-social') . '<br/><small>' . __('Bottom or Top of Post', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'facebook_show_media',
                                            'name' => 'facebook_show_media',
                                            'req_plugin' => 'fts_premium',
                                            'or_req_plugin' => 'combine_streams',
                                            'options' => array(
                                                array(
                                                    'label' => __('Below Username, Date & Description', 'feed-them-social'),
                                                    'value' => 'bottom',
                                                ),
                                                array(
                                                    'label' => __('Above Username, Date & Description', 'feed-them-social'),
                                                    'value' => 'top',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'show_media',
                                            ),
                                        ),
                                        //Show Thumbnail
                                        array(
                                            'input_wrap_class' => 'facebook_hide_thumbnail',
                                            'option_type' => 'select',
                                            'label' => __('Show User Thumbnail', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'facebook_hide_thumbnail',
                                            'name' => 'facebook_hide_thumbnail',
                                            'req_plugin' => 'fts_premium',
                                            'or_req_plugin' => 'combine_streams',
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
                                            'short_attr' => array(
                                                'attr_name' => 'show_thumbnail',
                                            ),
                                        ),
                                        //Show Username
                                        array(
                                            'input_wrap_class' => 'facebook_hide_date',
                                            'option_type' => 'select',
                                            'label' => __('Show Username', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'facebook_hide_date',
                                            'name' => 'facebook_hide_date',
                                            'req_plugin' => 'fts_premium',
                                            'or_req_plugin' => 'combine_streams',
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
                                            'short_attr' => array(
                                                'attr_name' => 'show_date',
                                            ),
                                        ),
                                        //Show Date
                                        array(
                                            'input_wrap_class' => 'facebook_hide_name',
                                            'option_type' => 'select',
                                            'label' => __('Show Date', 'feed-them-social') . '<br/><small>' . __('Yes or No', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'facebook_hide_name',
                                            'name' => 'facebook_hide_name',
                                            'req_plugin' => 'fts_premium',
                                            'or_req_plugin' => 'combine_streams',
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
                                            'short_attr' => array(
                                                'attr_name' => 'show_name',
                                            ),
                                        ),










                                        //Facebook Amount of words
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Amount of words per post', 'feed-them-social') . '<br/><small>' . __('Type 0 to remove the posts description', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'fb_page_word_count_option',
                                            'name' => 'fb_page_word_count_option',
                                            'placeholder' => '45 ' . __('is the default number', 'feed-them-social'),
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
                                            'label' => __('Overall Rating above Feed', 'feed-them-social') . '<br/><small>' . __('More settings: <a href="admin.php?page=fts-facebook-feed-styles-submenu-page#overall-rating-options">Facebook Options</a> page.', 'feed-them-social') . '</small>',
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
                                            )
                                        ),
                                        //Hide Reviews with no Text
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Hide Reviews with no description', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'reviews_with_no_text',
                                            'name' => 'reviews_with_no_text',
                                            'options' => array(
                                                1 => array(
                                                    'label' => __('Yes', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                                2 => array(
                                                    'label' => __('No', 'feed-them-social'),
                                                    'value' => 'yes',
                                                )
                                            ),
                                            'req_plugin' => 'facebook_reviews',
                                            'short_attr' => array(
                                                'attr_name' => 'remove_reviews_no_description',
                                                'ifs' => 'reviews',
                                            ),
                                        ),
                                        //Hide Reviews the text link, "See More Reviews"
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Hide the text "See More Reviews"', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'hide_see_more_reviews_link',
                                            'name' => 'hide_see_more_reviews_link',
                                            'options' => array(

                                                1 => array(
                                                    'label' => __('No', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                                2 => array(
                                                    'label' => __('Yes', 'feed-them-social'),
                                                    'value' => 'yes',
                                                )
                                            ),
                                            'req_plugin' => 'facebook_reviews',
                                            'short_attr' => array(
                                                'attr_name' => 'hide_see_more_reviews_link',
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
                                            'instructional-text' => '<strong> ' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-social'),
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
                                            'placeholder' => __('3 is the default number', 'feed-them-social'),
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
                                ),//End Facebook Page Feed
                                //******************************************
                                // Youtube Feed
                                //******************************************
                                'youtube' => array(
                                    'section_attr_key' => 'youtube_',
                                    'section_title' => __('Youtube Shortcode Generator', 'feed-them-social'),
                                    'section_wrap_class' => 'fts-youtube-shortcode-form',
                                    //Form Info
                                    'form_wrap_classes' => 'youtube-shortcode-form',
                                    'form_wrap_id' => 'fts-youtube-form',
                                    //Feed Type Selection
                                    'feed_type_select' => array(
                                        'label' => __('Feed Type', 'feed-them-social') . '<br/><small><a href="http://feedthemsocial.com/youtube-demo-1-large-with-4-video-per-row/" target="_blank">' . __('See Example Demos', 'feed-them-social') . '</a></small>',
                                        'select_wrap_classes' => 'youtube-gen-selection',
                                        'select_classes' => '',
                                        'select_name' => 'youtube-messages-selector',
                                        'select_id' => 'youtube-messages-selector',
                                    ),
                                    //Token Check
                                    'token_check' => array(
                                        array(
                                            'option_name' => 'youtube_custom_api_token',
                                            'no_token_msg' => __('<strong>STEP 1:</strong> Please add your API Token or Access Token to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Youtube Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                        ),
                                    ),
                                    //Feed Types and their options
                                    'feeds_types' => array(
                                        //Channel Feed
                                        array(
                                            'value' => 'channelID',
                                            'title' => __('Channel Feed', 'feed-them-social'),
                                        ),
                                        //Channel Playlist Feed
                                        array(
                                            'value' => 'playlistID',
                                            'title' => __('Channel\'s Specific Playlist', 'feed-them-social'),
                                        ),
                                        //User's Most Recent Videos
                                        array(
                                            'value' => 'username',
                                            'title' => __('User\'s Most Recent Videos', 'feed-them-social'),
                                        ),
                                        //User's Playlist
                                        array(
                                            'value' => 'userPlaylist',
                                            'title' => __('User\'s Specific Playlist', 'feed-them-social'),
                                        ),
                                        //Single Video with description
                                        array(
                                            'value' => 'singleID',
                                            'title' => __('Single Video with title, date & description', 'feed-them-social'),
                                        ),
                                    ),
                                    'short_attr_final' => 'yes',
                                    //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]

                                    //'empty_error'=> 'set',
                                    //'empty_error_value'=> 'auto',

                                    'main_options' => array(
                                        //Youtube Name
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_name',
                                            'label' => __('Youtube Username (required)', 'feed-them-social'),
                                            'instructional-text' => __('You must copy your YouTube <strong>Username</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social') . ' <a href="https://www.youtube.com/channel/" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
                                            'type' => 'text',
                                            'id' => 'youtube_name',
                                            'name' => 'youtube_name',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'username',
                                                'empty_error' => 'yes',
                                                'ifs' => 'username',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'username',
                                                ),
                                            ),
                                        ),
                                        //Youtube Playlist ID
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_playlistID',
                                            'label' => __('Youtube Playlist ID (required)', 'feed-them-social'),
                                            'instructional-text' => __('You must copy your YouTube <strong>Playlist</strong> and <strong>Channel</strong> url link and paste them below. Your urls should look similar to our Example urls below. <br/><br/><strong>Playlist ID:</strong>', 'feed-them-social') . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN</a><br/><strong>' . __('Channel ID:', 'feed-them-social') .'</strong> <a href="https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA" target="_blank">https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA</a>',
                                            'type' => 'text',
                                            'id' => 'youtube_playlistID',
                                            'name' => 'youtube_playlistID',
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'playlist_id',
                                                'empty_error' => 'yes',
                                                'ifs' => 'playlistID',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'playlistID',
                                                ),
                                            ),
                                        ),
                                        //Youtube Playlist ID2
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_playlistID2',
                                            'label' => __('Youtube Playlist ID (required)', 'feed-them-social'),
                                            'instructional-text' => __('You must copy your YouTube <strong>Playlist</strong> and <strong>Username</strong> url and paste them below. Your urls should look similar to our Example urls below.<br/><br/><strong>Playlist ID:</strong>', 'feed-them-social') . ' <a href="https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym" target="_blank">https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym</a><br/><strong>' . __('Username:', 'feed-them-social') .'</strong> <a href="https://www.youtube.com/user/nationalgeographic" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
                                            'type' => 'text',
                                            'id' => 'youtube_playlistID2',
                                            'name' => 'youtube_playlistID2',
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'playlist_id',
                                                'empty_error' => 'yes',
                                                'ifs' => 'userPlaylist',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'userPlaylist',
                                                ),
                                            ),
                                        ),
                                        //Youtube Name 2
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_name2',
                                            'label' => __('Youtube Username<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_name2',
                                            'name' => 'youtube_name2',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'username_subscribe_btn',
                                                'ifs' => 'userPlaylist',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'userPlaylist',
                                                ),
                                            ),
                                        ),
                                        //Youtube Channel ID
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_channelID',
                                            'label' => __('Youtube Channel ID (required)', 'feed-them-social'),
                                            'instructional-text' => __('You must copy your YouTube <strong>Channel</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social') . ' <a href="https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw" target="_blank">https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw</a>',
                                            'type' => 'text',
                                            'id' => 'youtube_channelID',
                                            'name' => 'youtube_channelID',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'channel_id',
                                                'ifs' => 'channelID',
                                                'empty_error' => 'yes',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'channelID',
                                                ),
                                            ),
                                        ),
                                        //Youtube Channel ID 2
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_channelID2',
                                            'label' => __('Youtube Channel ID<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_channelID2',
                                            'name' => 'youtube_channelID2',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'channel_id',
                                                'ifs' => 'playlistID',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'playlistID',
                                                ),
                                            ),
                                        ),

                                        //Youtube Single Video ID
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_singleVideoID',
                                            'label' => __('Single Youtube Video ID (required)', 'feed-them-social'),
                                            'instructional-text' => __('You must copy your <strong>YouTube Video</strong> url link and paste it below. Your url should look similar to our Example url below. <br/><strong>Video URL:</strong>', 'feed-them-social') . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0</a>',
                                            'type' => 'text',
                                            'id' => 'youtube_singleVideoID',
                                            'name' => 'youtube_singleVideoID',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'video_id_or_link',
                                                'ifs' => 'singleID',
                                                'empty_error' => 'yes',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#youtube-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'singleID',
                                                ),
                                            ),
                                        ),

                                        //# of videos
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'youtube_vid_count',
                                            'label' => __('# of videos', 'feed-them-social') . $limitforpremium,
                                            'type' => 'text',
                                            'id' => 'youtube_vid_count',
                                            'name' => 'youtube_vid_count',
                                            'placeholder' => __('4 is the default value', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'vid_count',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'vid_count=4',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-youtube-first-video-wrap',
                                            )
                                        ),
                                        //Display First video full size
                                        array(
                                            'grouped_options_title' => __('First Video Display', 'feed-them-social'),
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('Display First video full size', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_first_video',
                                            'name' => 'youtube_first_video',
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
                                            'short_attr' => array(
                                                'attr_name' => 'large_vid',
                                            ),
                                            'sub_options_end' => true,
                                        ),
                                        //Display Large Video Title
                                        array(
                                            'option_type' => 'select',
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'label' => __('Show the Large Video Title', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_large_vid_title',
                                            'name' => 'youtube_large_vid_title',
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
                                                'attr_name' => 'large_vid_title',
                                            ),
                                        ),
                                        //Display Large Video Description
                                        array(
                                            'option_type' => 'select',
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'label' => __('Show the Large Video Description', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_large_vid_description',
                                            'name' => 'youtube_large_vid_description',
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
                                                'attr_name' => 'large_vid_description',
                                            ),
                                        ),


                                        //Play thumbs in large video container
                                        array(
                                            'grouped_options_title' => __('Video Thumbnails', 'feed-them-social'),
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('Click thumb to play Video', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_play_thumbs',
                                            'name' => 'youtube_play_thumbs',
                                            'options' => array(
                                                array(
                                                    'label' => __('Play on Page', 'feed-them-social'),
                                                    'value' => 'yes',
                                                ),
                                                array(
                                                    'label' => __('Open in YouTube', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                                array(
                                                    'label' => __('Open in Popup (Premium Version Required)', 'feed-them-social'),
                                                    'value' => 'popup',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'thumbs_play_in_iframe',
                                            ),
                                        ),

                                        //# of videos in each row
                                        array(

                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('# of videos in each row', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_columns',
                                            'name' => 'youtube_columns',
                                            'default_value' => '4',
                                            'options' => array(
                                                array(
                                                    'label' => __('1', 'feed-them-social'),
                                                    'value' => '1',
                                                ),
                                                array(
                                                    'label' => __('2', 'feed-them-social'),
                                                    'value' => '2',
                                                ),
                                                array(
                                                    'label' => __('3', 'feed-them-social'),
                                                    'value' => '3',
                                                ),
                                                array(
                                                    'label' => __('4', 'feed-them-social'),
                                                    'value' => '4',
                                                ),
                                                array(
                                                    'label' => __('5', 'feed-them-social'),
                                                    'value' => '5',
                                                ),
                                                array(
                                                    'label' => __('6', 'feed-them-social'),
                                                    'value' => '6',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'vids_in_row',
                                            ),
                                        ),
                                        //omit first video thumbnail
                                        array(

                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('Hide the first thumbnail', 'feed-them-social') . '<br/><small>' . __('Useful if playing videos on the page.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_omit_first_thumbnail',
                                            'name' => 'youtube_omit_first_thumbnail',
                                            'default_value' => 'no',
                                            'options' => array(
                                                array(
                                                    'label' => __('No', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                                array(
                                                    'label' => __('Yes', 'feed-them-social'),
                                                    'value' => 'yes',
                                                )
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'omit_first_thumbnail',
                                            ),
                                        ),
                                        //Space between Vids
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'input',
                                            'label' => __('Space between video thumbnails', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'fts-slicker-youtube-container-margin',
                                            'name' => 'fts-slicker-youtube-container-margin',
                                            'placeholder' => '1px is the default value',
                                            'value' => '',
                                            'short_attr' => array(
                                                'attr_name' => 'space_between_videos',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'space_between_videos=1px',
                                            ),
                                        ),


                                        // Force Video Rows
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('Force thumbnails rows', 'feed-them-gallery') . '<br/><small>' . __('No, will allow the video images to be responsive for smaller devices. Yes, will force the selected rows.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'fts_youtube_force_columns',
                                            'name' => 'fts_youtube_force_columns',
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
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'force_columns',
                                            ),
                                        ),
                                        //Display Max Res Images for thumbs
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('High quality thumbnail images', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_maxres_thumbnail_images',
                                            'name' => 'youtube_maxres_thumbnail_images',
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
                                            'short_attr' => array(
                                                'attr_name' => 'maxres_thumbnail_images',
                                            ),
                                        ),
                                        //Background color for thumbs container
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'input',
                                            'color_picker' => 'yes',
                                            'label' => __('Container Background color ', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_thumbs_wrap_color',
                                            'name' => 'youtube_thumbs_wrap_color',
                                            'default' => '#000',
                                            'placeholder' => '#000',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'thumbs_wrap_color',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'thumbs_wrap_color=#000',
                                            ),
                                            
                                        ),
                                        //Align container right or left of video
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'option_type' => 'select',
                                            'label' => __('Align Thumbs', 'feed-them-social') . '<br/><small>' . __('Bottom (default), Right, or left of Videoo', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_thumbs_wrap',
                                            'name' => 'youtube_thumbs_wrap',
                                            'options' => array(
                                                array(
                                                    'label' => __('Below Video', 'feed-them-social'),
                                                    'value' => 'none',
                                                ),
                                                array(
                                                    'label' => __('Right', 'feed-them-social'),
                                                    'value' => 'right',
                                                ),
                                                array(
                                                    'label' => __('Left', 'feed-them-social'),
                                                    'value' => 'left',
                                                ),
                                            ),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'wrap',
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',

                                        ),
                                        //Align container right or left of video
                                        array(
                                            'input_wrap_class' => 'youtube_align_comments_wrap',
                                            'option_type' => 'select',
                                            'label' => __('Align Title, Description etc.', 'feed-them-social') . '<br/><small>' . __('Bottom (default), Right, or left of Video', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_comments_wrap',
                                            'name' => 'youtube_comments_wrap',
                                            'options' => array(
                                                array(
                                                    'label' => __('Below Video', 'feed-them-social'),
                                                    'value' => 'none',
                                                ),
                                                array(
                                                    'label' => __('Right', 'feed-them-social'),
                                                    'value' => 'right',
                                                ),
                                                array(
                                                    'label' => __('Left', 'feed-them-social'),
                                                    'value' => 'left',
                                                ),
                                            ),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'wrap_single',
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',

                                        ),
                                        //Align container right or left of video
                                        array(
                                            'input_wrap_class' => 'youtube_video_thumbs_display',
                                            'option_type' => 'select',
                                            'label' => __('Video/Thumbs width options', 'feed-them-social') . '<br/><small>' . __('Sizes: 80/20, 60/40 or 50/50', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_video_thumbs_display',
                                            'name' => 'youtube_video_thumbs_display',
                                            'options' => array(
                                                array(
                                                    'label' => __('None', 'feed-them-social'),
                                                    'value' => 'none',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 80%, Thumbs Container 20%)', 'feed-them-social'),
                                                    'value' => '1',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 60%, Thumbs Container 40%)', 'feed-them-social'),
                                                    'value' => '2',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 50%, Thumbs Container 50%)', 'feed-them-social'),
                                                    'value' => '3',
                                                ),
                                            ),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'video_wrap_display',
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',

                                        ),
                                        //Align container right or left of video
                                        array(
                                            'input_wrap_class' => 'youtube_video_single_info_display',
                                            'option_type' => 'select',
                                            'label' => __('Video/Info width options', 'feed-them-social') . '<br/><small>' . __('Sizes: 80/20, 60/40 or 50/50', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_video_comments_display',
                                            'name' => 'youtube_video_comments_display',
                                            'options' => array(
                                                array(
                                                    'label' => __('None', 'feed-them-social'),
                                                    'value' => 'none',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 80%, Info Container 20%)', 'feed-them-social'),
                                                    'value' => '1',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 60%, Info Container 40%)', 'feed-them-social'),
                                                    'value' => '2',
                                                ),
                                                array(
                                                    'label' => __('Option 1 (Video 50%, Info Container 50%)', 'feed-them-social'),
                                                    'value' => '3',
                                                ),
                                            ),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'video_wrap_display_single',
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',

                                        ),
                                        //Youtube Load More Button
                                        array(
                                            'input_wrap_class' => 'youtube_hide_option',
                                            'grouped_options_title' => __('Load More', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Load More Button', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_load_more_option',
                                            'name' => 'youtube_load_more_option',
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
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            'short_attr' => array(
                                                'attr_name' => '',
                                                'empty_error_value' => '',
                                                'no_attribute' => 'yes',

                                            ),
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'youtube-loadmore-wrap',
                                            ),
                                        ),
                                        //Youtube Load More Style
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Load More Style', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_load_more_style',
                                            'name' => 'youtube_load_more_style',
                                            'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social'),
                                            'options' => array(
                                                array(
                                                    'label' => __('Button', 'feed-them-social'),
                                                    'value' => 'button',
                                                ),
                                                array(
                                                    'label' => __('AutoScroll', 'feed-them-social'),
                                                    'value' => 'autoscroll',
                                                ),
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-youtube-load-more-options-wrap',
                                            ),
                                            'sub_options_end' => true,
                                        ),
                                        //youtube Page Load more Amount
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Amount', 'feed-them-social') . '<br/><small>' . __('How many more videos will load at a time.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_loadmore_count',
                                            'name' => 'youtube_loadmore_count',
                                            'placeholder' => __('5 is the default number', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_count',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_count=5',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-youtube-load-more-options2-wrap',
                                            ),
                                        ),
                                        //youtube Load more Button Width
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Width', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto width', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_loadmore_button_width',
                                            'name' => 'youtube_loadmore_button_width',
                                            'placeholder' => '300px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_btn_maxwidth',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_btn_maxwidth=300px',
                                                'ifs' => 'load_more',
                                            ),
                                        ),
                                        //youtube Load more Button Margin
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Margin', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'youtube_loadmore_button_margin',
                                            'name' => 'youtube_loadmore_button_margin',
                                            'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_btn_margin',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_btn_margin=10px',
                                                'ifs' => 'load_more',
                                            ),
                                            'sub_options_end' => 2,
                                        ),
                                        //Display Comments
                                        array(
                                            'grouped_options_title' => __('Comments', 'feed-them-social'),
                                            'option_type' => 'input',
                                            'label' => __('# of Comments', 'feed-them-social') . '<br/><small>' . __('Maximum amount is 50', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'youtube_comments_count',
                                            'name' => 'youtube_comments_count',
                                            'placeholder' => '',
                                            'value' => '',
                                            'short_attr' => array(
                                                'attr_name' => 'comments_count',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'comments_count=0',
                                            ),
                                            'req_plugin' => 'fts_premium',
                                        ),
                                    ),
                                    //Final Shortcode ifs
                                    'shortcode_ifs' => array(
                                        'load_more' => array(
                                            'if' => array(
                                                'class' => 'select#youtube_load_more_option',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                        'username' => array(
                                            'if' => array(
                                                'class' => 'select#youtube-messages-selector',
                                                'operator' => '==',
                                                'value' => 'username',
                                            ),
                                        ),
                                        'userPlaylist' => array(
                                            'if' => array(
                                                'class' => 'select#youtube-messages-selector',
                                                'operator' => '==',
                                                'value' => 'userPlaylist',
                                            ),
                                        ),
                                        'channelID' => array(
                                            'if' => array(
                                                'class' => 'select#youtube-messages-selector',
                                                'operator' => '==',
                                                'value' => 'channelID',
                                            ),
                                        ),
                                        'playlistID' => array(
                                            'if' => array(
                                                'class' => 'select#youtube-messages-selector',
                                                'operator' => '==',
                                                'value' => 'playlistID',
                                            ),
                                        ),
                                        'singleID' => array(
                                            'if' => array(
                                                'class' => 'select#youtube-messages-selector',
                                                'operator' => '==',
                                                'value' => 'singleID',
                                            ),
                                        ),
                                    ),
                                    //Generator Info
                                    'generator_title' => __('YouTube Feed Shortcode', 'feed-them-social'),
                                    'generator_class' => 'youtube-final-shortcode',
                                ),//End Youtube Feed
                                //******************************************
                                // Pinterest
                                //******************************************
                                'pinterest' => array(
                                    'section_attr_key' => 'pinterest_',
                                    'section_title' => __('Pinterest Shortcode Generator', 'feed-them-social'),
                                    'section_wrap_class' => 'pinterest-shortcode-form',
                                    //Form Info
                                    'form_wrap_classes' => 'pinterest-shortcode-form',
                                    'form_wrap_id' => 'fts-pinterest-form',
                                    //Feed Type Selection
                                    'feed_type_select' => array(
                                        'label' => __('Feed Type', 'feed-them-social'),
                                        'select_wrap_classes' => 'pinterest-gen-selection',
                                        'select_classes' => '',
                                        'select_name' => 'pinterest-messages-selector',
                                        'select_id' => 'pinterest-messages-selector',
                                    ),
                                    //Token Check
                                    'token_check' => array(
                                        array(
                                            'option_name' => 'fts_pinterest_custom_api_token',
                                            'no_token_msg' => __('<strong>STEP 1:</strong> Please add a Pinterest API Token to our <a href="admin.php?page=fts-pinterest-feed-styles-submenu-page">Pinterest Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                        ),
                                    ),
                                    //Feed Types and their options
                                    'feeds_types' => array(
                                        //Board List
                                        array(
                                            'value' => 'boards_list',
                                            'title' => __('Board List', 'feed-them-social'),
                                        ),
                                        //Single Board Pins
                                        array(
                                            'value' => 'single_board_pins',
                                            'title' => __('Pins From a Specific Board', 'feed-them-social'),
                                        ),
                                        //Single Board Pins
                                        array(
                                            'value' => 'pins_from_user',
                                            'title' => __('Latest Pins from a User', 'feed-them-social'),
                                        ),
                                    ),
                                    'short_attr_final' => 'yes',
                                    //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]

                                    //'empty_error'=> 'set',
                                    //'empty_error_value'=> 'auto',

                                    'main_options' => array(
                                        //Feed Type
                                        array(
                                            'option_type' => 'select',
                                            'id' => 'pinterest-messages-selector',
                                            'name' => 'pinterest-messages-selector',
                                            //DONT SHOW HTML
                                            'no_html' => 'yes',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'type',
                                            ),
                                        ),
                                        //Pinterest Board Name
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'board-name',
                                            'label' => __('Pinterest Board Name (required)', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'pinterest_board_name',
                                            'name' => 'pinterest_board_name',
                                            'value' => '',
                                            'instructional-text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __('Pinterest and Board Name', 'feed-them-social') . '</a> ' . __('and paste them below.', 'feed-them-social'),
                                            'instructional-class' => 'pinterest-board-and-name-text',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'board_id',
                                                'var_final_if' => 'no',
                                                'empty_error' => 'yes',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#pinterest-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'single_board_pins',
                                                ),
                                                'ifs' => 'single_board_pins',
                                            ),
                                        ),
                                        //Pinterest Name
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'pinterest_name',
                                            'label' => __('Pinterest Username (required)', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'pinterest_name',
                                            'name' => 'pinterest_name',
                                            'value' => '',
                                            'instructional-text' => __('Copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __('Pinterest Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social'),
                                            'instructional-class' => 'pinterest-name-text',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'pinterest_name',
                                                'empty_error' => 'yes',
                                                'var_final_if' => 'no',
                                            ),
                                        ),
                                        //Board Count
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'number-of-boards',
                                            'label' => __('# of Boards', 'feed-them-social') . $limitforpremium,
                                            'type' => 'text',
                                            'id' => 'boards_count',
                                            'name' => 'boards_count',
                                            // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                                            //'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                                            'placeholder' => __('6 is the default value', 'feed-them-social'),
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'boards_count',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'boards_count=6',
                                                'ifs' => 'boards',
                                            ),
                                        ),
                                        //Pins Count
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'show-pins-amount',
                                            'label' => __('# of Pins', 'feed-them-social') . $limitforpremium,
                                            'type' => 'text',
                                            'id' => 'pins_count',
                                            'name' => 'pins_count',
                                            // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                                            // 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                                            'placeholder' => __('6 is the default value', 'feed-them-social'),
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'pins_count',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'pins_count=6',
                                                'ifs' => 'single_board_pins,pins_from_user',
                                            ),
                                        ),
                                    ),
                                    //Final Shortcode ifs
                                    'shortcode_ifs' => array(
                                        'single_board_pins' => array(
                                            'if' => array(
                                                'class' => 'select#pinterest-messages-selector',
                                                'operator' => '==',
                                                'value' => 'single_board_pins',
                                            ),
                                        ),
                                        'pins_from_user' => array(
                                            'if' => array(
                                                'class' => 'select#pinterest-messages-selector',
                                                'operator' => '==',
                                                'value' => 'pins_from_user',
                                            ),
                                        ),
                                        'boards' => array(
                                            'if' => array(
                                                'class' => 'select#pinterest-messages-selector',
                                                'operator' => '==',
                                                'value' => 'boards_list',
                                            ),
                                        ),
                                    ),
                                    //Generator Info
                                    'generator_title' => __('Pinterest Feed Shortcode', 'feed-them-social'),
                                    'generator_class' => 'pinterest-final-shortcode',

                                ),//End Pinterest Feed
                                //******************************************
                                // Twitter
                                //******************************************
                                'twitter' => array(
                                    'section_attr_key' => 'twitter_',
                                    'section_title' => __('Twitter Shortcode Generator', 'feed-them-social'),
                                    'section_wrap_class' => 'fts-twitter-shortcode-form',
                                    //Form Info
                                    'form_wrap_classes' => 'twitter-shortcode-form',
                                    'form_wrap_id' => 'fts-twitter-form',
                                    //Token Check
                                    'token_check' => array(
                                        array(
                                            'option_name' => 'fts_twitter_custom_access_token_secret',
                                            'no_token_msg' => __('<strong>STEP 1:</strong> Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                        ),
                                    ),
                                    //Feed Type Selection
                                    'feed_type_select' => array(
                                        'label' => __('Feed Type', 'feed-them-social'),
                                        'select_wrap_classes' => 'twitter-gen-selection',
                                        'select_classes' => '',
                                        'select_name' => 'twitter-messages-selector',
                                        'select_id' => 'twitter-messages-selector',
                                    ),
                                    //Feed Types and their options
                                    'feeds_types' => array(
                                        //User Feed
                                        array(
                                            'value' => 'user',
                                            'title' => __('User Feed', 'feed-them-social'),
                                        ),
                                        //hastag Feed
                                        array(
                                            'value' => 'hashtag',
                                            'title' => __('Hashtag, Search and more Feed', 'feed-them-social'),
                                        ),
                                    ),
                                    'short_attr_final' => 'yes',
                                    //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]

                                    'main_options' => array(
                                        //Twitter Search Name
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'twitter_hashtag_etc_name',
                                            'label' => __('Twitter Search Name (required)', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_hashtag_etc_name',
                                            'name' => 'twitter_hashtag_etc_name',
                                            'value' => '',
                                            'instructional-text' => __('You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'search',
                                                'var_final_if' => 'no',
                                                'empty_error' => 'yes',
                                                'ifs' => 'twitter_search',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#twitter-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'hashtag',
                                                ),
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'twitter-hashtag-etc-wrap',
                                                'sub_options_title' => __('Twitter Search', 'feed-them-social'),
                                            ),
                                            'sub_options_end' => true,

                                        ),
                                        //Twitter Name
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'twitter_name',
                                            'label' => __('Twitter Name', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_name',
                                            'name' => 'twitter_name',
                                            'instructional-text' => '<span class="hashtag-option-small-text">' . __('Twitter Name is only required if you want to show a', 'feed-them-social') . ' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">' . __('Follow Button', 'feed-them-social') . '</a>.</span><span class="must-copy-twitter-name">' . __('You must copy your', 'feed-them-social') . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __('Twitter Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</span>',
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'twitter_name',
                                                'var_final_if' => 'no',
                                                'empty_error' => 'yes',
                                                'empty_error_if' => array(
                                                    'attribute' => 'select#twitter-messages-selector',
                                                    'operator' => '==',
                                                    'value' => 'user',
                                                ),
                                            ),
                                        ),
                                        //Tweet Count
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('# of Tweets (optional)', 'feed-them-social') . $limitforpremium,
                                            'type' => 'text',
                                            'id' => 'tweets_count',
                                            'name' => 'tweets_count',
                                            // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                                            // 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                                            'placeholder' => __('6 is the default value', 'feed-them-social'),
                                            'value' => '',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'tweets_count',
                                                'var_final_if' => 'yes',
                                                'var_final_value' => 'no',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'tweets_count=6',
                                            ),
                                        ),
                                        //Twitter Fixed Height
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Twitter Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'twitter_height',
                                            'name' => 'twitter_height',
                                            'placeholder' => '450px ' . __('for example', 'feed-them-social'),
                                            'short_attr' => array(
                                                'attr_name' => 'twitter_height',
                                                'var_final_if' => 'yes',
                                                'var_final_value' => '',
                                                'empty_error' => 'set',
                                                'empty_error_value' => '',
                                            ),
                                        ),
                                        //Show Cover Photo
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Cover Photo', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-cover-photo',
                                            'name' => 'twitter-cover-photo',
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
                                                'attr_name' => 'cover_photo',
                                            ),
                                        ),
                                        //Show Stats Bar
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Stats Bar', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-stats-bar',
                                            'name' => 'twitter-stats-bar',
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
                                                'attr_name' => 'stats_bar',
                                            ),
                                        ),
                                        //Show Retweets
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Retweets', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-show-retweets',
                                            'name' => 'twitter-show-retweets',
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
                                                'attr_name' => 'show_retweets',
                                            ),
                                        ),
                                        //Show Replies
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Replies', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-show-replies',
                                            'name' => 'twitter-show-replies',
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
                                                'attr_name' => 'show_replies',
                                            ),
                                        ),
                                        //Pop Up Option
                                        array(
                                            'grouped_options_title' => __('Popup', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Display Photos & Videos in Popup', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-popup-option',
                                            'name' => 'twitter-popup-option',
                                            // Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
                                            'prem_req' => 'yes',
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
                                            'req_plugin' => 'fts_premium',
                                            'short_attr' => array(
                                                'attr_name' => 'popup',
                                                'ifs' => 'twitter_popup',
                                            ),
                                        ),
                                        //******************************************
                                        // Facebook Load More Options
                                        //******************************************
                                        //Twitter Load More Button
                                        array(
                                            'grouped_options_title' => __('Load More', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Load More Button', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_load_more_option',
                                            'name' => 'twitter_load_more_option',
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
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            'short_attr' => array(
                                                'attr_name' => '',
                                                'empty_error_value' => '',
                                                'no_attribute' => 'yes',

                                            ),
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'twitter-loadmore-wrap',
                                            ),
                                        ),
                                        //Twitter Load More Style
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Load More Style', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_load_more_style',
                                            'name' => 'twitter_load_more_style',
                                            'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social'),
                                            'options' => array(
                                                array(
                                                    'label' => __('Button', 'feed-them-social'),
                                                    'value' => 'button',
                                                ),
                                                array(
                                                    'label' => __('AutoScroll', 'feed-them-social'),
                                                    'value' => 'autoscroll',
                                                ),
                                            ),
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-twitter-load-more-options-wrap',
                                            ),
                                            'sub_options_end' => true,
                                        ),
                                        //Twitter Page Load more Amount
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Amount', 'feed-them-social') . '<br/><small>' . __('How many more posts will load at a time.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'twitter_loadmore_count',
                                            'name' => 'twitter_loadmore_count',
                                            'placeholder' => __('5 is the default number', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_count',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_count=5',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-twitter-load-more-options2-wrap',
                                            ),
                                        ),
                                        //Twitter Load more Button Width
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Width', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto width', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'twitter_loadmore_button_width',
                                            'name' => 'twitter_loadmore_button_width',
                                            'placeholder' => '300px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'prem_req' => 'yes',
                                            'req_plugin' => 'fts_premium',
                                            'or_req_plugin' => 'facebook_reviews',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_btn_maxwidth',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_btn_maxwidth=300px',
                                                'ifs' => 'load_more',
                                            ),
                                        ),
                                        //Twitter Load more Button Margin
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Margin', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_loadmore_button_margin',
                                            'name' => 'twitter_loadmore_button_margin',
                                            'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
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
                                        // Twitter Grid Options
                                        //******************************************
                                        // Twitter Display Posts in Grid
                                        array(
                                            'grouped_options_title' => __('Grid', 'feed-them-social'),
                                            'input_wrap_class' => 'twitter-posts-in-grid-option-wrap',
                                            'option_type' => 'select',
                                            'label' => __('Display Posts in Grid', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter-grid-option',
                                            'name' => 'twitter-grid-option',
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
                                            'id' => 'twitter_grid_column_width',
                                            'name' => 'twitter_grid_column_width',
                                            'instructional-text' => '<strong> ' . __('NOTE:', 'feed-them-social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed-them-social'),
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
                                                'sub_options_wrap_class' => 'fts-twitter-grid-options-wrap',
                                                //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                                            ),
                                        ),
                                        //Grid Spaces Between Posts
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Grid Spaces Between Posts', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'twitter_grid_space_between_posts',
                                            'name' => 'twitter_grid_space_between_posts',
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
                                    ),
                                    //Final Shortcode ifs
                                    'shortcode_ifs' => array(
                                        'twitter_popup' => array(
                                            'if' => array(
                                                'class' => 'select#twitter-popup-option',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                        'twitter_search' => array(
                                            'if' => array(
                                                'class' => 'select#twitter-messages-selector',
                                                'operator' => '==',
                                                'value' => 'hashtag',
                                            ),
                                        ),
                                        'load_more' => array(
                                            'if' => array(
                                                'class' => 'select#twitter_load_more_option',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                        'grid' => array(
                                            'if' => array(
                                                'class' => 'select#twitter-grid-option',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                    ),
                                    //Generator Info
                                    'generator_title' => __('Twitter Feed Shortcode', 'feed-them-social'),
                                    'generator_class' => 'twitter-final-shortcode',

                                ),//End Twitter Feed
                                //******************************************
                                // Instagram
                                //******************************************
                                'instagram' => array(
                                    'section_attr_key' => 'instagram_',
                                    'section_title' => __('Instagram Shortcode Generator', 'feed-them-social'),
                                    'section_wrap_class' => 'fts-instagram-shortcode-form',
                                    //Form Info
                                    'form_wrap_classes' => 'instagram-shortcode-form',
                                    'form_wrap_id' => 'fts-instagram-form',
                                    //Token Check
                                    'token_check' => array(
                                        array(
                                            'option_name' => 'fts_instagram_custom_api_token',
                                            'no_token_msg' => __('<strong>STEP 1:</strong> Please get your Access Token on the <a href="admin.php?page=fts-instagram-feed-styles-submenu-page">Instagram Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social'),
                                        ),
                                    ),
                                    //Feed Type Selection
                                    'feed_type_select' => array(
                                        'label' => __('Feed Type', 'feed-them-social'),
                                        'select_wrap_classes' => 'instagram-gen-selection',
                                        'select_classes' => '',
                                        'select_name' => 'instagram-messages-selector',
                                        'select_id' => 'instagram-messages-selector',
                                    ),
                                    //Feed Types and their options
                                    'feeds_types' => array(
                                        //User Feed
                                        array(
                                            'value' => 'user',
                                            'title' => __('User Feed', 'feed-them-social'),
                                        ),
                                        //hastag Feed
                                        array(
                                            'value' => 'hashtag',
                                            'title' => __('Hashtag Feed', 'feed-them-social'),
                                        ),
                                        //location Feed
                                        array(
                                            'value' => 'location',
                                            'title' => __('Location Feed', 'feed-them-social'),
                                        ),
                                    ),
                                    //Feed Type Selection
                               //     'conversion_input' => array(
                               //         'main_wrap_class' => 'instagram-id-option-wrap',
                               //         'conv_section_title' => __('Convert Instagram Name to ID', 'feed-them-social'),
                               //         'instructional-text' => 'You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below',
                               //         'input_wrap_class' => 'instagram_name',
                               //         'label' => __('Instagram Name (required)', 'feed-them-social'),
                               //         'id' => 'convert_instagram_username',
                               //         'name' => 'convert_instagram_username',
                               //         //Button
                               //         'btn-value' => __('Convert Instagram Username', 'feed-them-social'),
                               //         'onclick' => 'converter_instagram_username();',
                               //     ),
                               //     'short_attr_final' => 'yes',
                                    //Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
                                    'main_options' => array(
                                        //Instagram ID
                                        array(
                                            'option_type' => 'input',
                                            'input_wrap_class' => 'instagram_name',
                                            'label' => array(
                                                1 => array(
                                                    'text' => __('Instagram ID # (required)', 'feed-them-social'),
                                                    'class' => 'instagram-user-option-text',
                                                ),
                                                2 => array(
                                                    'text' => __('Hashtag (required)', 'feed-them-social'),
                                                    'class' => 'instagram-hashtag-option-text',
                                                ),
                                                3 => array(
                                                    'text' => __('Location ID (required)', 'feed-them-social'),
                                                    'class' => 'instagram-location-option-text',
                                                ),
                                            ),
                                            'type' => 'text',
                                            'id' => 'instagram_id',
                                            'name' => 'instagram_id',
                                            'required' => 'yes',
                                            'instructional-text' => array(
                                                1 => array(
                                                    'text' => __('<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.<br/><strong>To create an additional feed</strong> go back the ', 'feed-them-social') . ' <a href="admin.php?page=fts-instagram-feed-styles-submenu-page" target="">' . __('Instagram Options', 'feed-them-social') . '</a>' . __(' and make sure you are logged into that specific Instagram account then click the button again to get a new Access Token/ID. Remember to click Save all Changes and return here.</div>', 'feed-them-social') ,
                                                    'class' => 'instagram-user-option-text',
                                                ),
                                                2 => array(
                                                    'text' => __('Add your Hashtag below. <strong>DO NOT</strong> add the #, just the name.', 'feed-them-social'),
                                                    'class' => 'instagram-hashtag-option-text',
                                                ),
                                                3 => array(
                                                    'text' => __('<strong>NOTE:</strong> The post count may not count proper in some location instances because private instagram photos are in the mix. We cannot pull private accounts photos in any location feed. Add your Location ID below.', 'feed-them-social'),
                                                    'class' => 'instagram-location-option-text',
                                                ),
                                            ),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'instagram_id',
                                                'var_final_if' => 'no',
                                                'empty_error' => 'yes',
                                            ),
                                        ),
                                        //Access Token
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Access Token (required) ', 'feed-them-social') . '<br/><small>' . __('', 'feed-them-gallery') . '</small>',
                                            'type' => 'text',
                                            'id' => 'insta_access_token',
                                            'name' => 'insta_access_token',
                                            // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                                            // 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                                            'placeholder' => __('', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'access_token',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => '',
                                            ),
                                        ),
                                        //Pic Count
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('# of Pics (optional)', 'feed-them-social') . $limitforpremium,
                                            'type' => 'text',
                                            'id' => 'pics_count',
                                            'name' => 'pics_count',
                                            // Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
                                            // 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
                                            'placeholder' => __('6 is the default value', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'pics_count',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'pics_count=6',
                                            ),
                                        ),
                                        //Feed Type
                                        array(
                                            'option_type' => 'select',
                                            'id' => 'instagram-messages-selector',
                                            'no_html' => 'yes',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'type',
                                            ),
                                        ),
                                        //Instagram Width
                                        array(
                                            'input_wrap_class' => 'instagram_width_option',
                                            'option_type' => 'input',
                                            'label' => __('Gallery Width', 'feed-them-social'),
                                            'label_note' => __('Leave blank for auto height', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_page_width',
                                            'name' => 'instagram_page_width',
                                            'placeholder' => '50% or 450px ' . __('for example', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'width',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                //Special case: need no attribute if empty
                                                'empty_error_value' => '',
                                            ),
                                        ),
                                        //Instagram Fixed Height
                                        array(
                                            'input_wrap_class' => 'instagram_fixed_height_option',
                                            'option_type' => 'input',
                                            'label' => __('Gallery Fixed Height', 'feed-them-social') . '<br/><small>' . __('Use this option to create a scrolling feed.', 'feed-them-gallery') . '</small>',
                                            'label_note' => __('Leave blank for auto height', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_page_height',
                                            'name' => 'instagram_page_height',
                                            'placeholder' => '450px ' . __('for example', 'feed-them-social'),
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'height',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                //Special case: need no attribute if empty
                                                'empty_error_value' => '',
                                            ),
                                        ),
                                        //******************************************
                                        // Profile Wrap
                                        //******************************************
                                        array(
                                            'grouped_options_title' => __('Profile', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Show Profile Info', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-profile-wrap',
                                            'name' => 'instagram-profile-wrap',
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
                                                'attr_name' => 'profile_wrap',
                                            ),
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'main-instagram-profile-options-wrap',
                                            ),
                                        ),
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Profile Photo', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-profile-photo',
                                            'name' => 'instagram-profile-photo',
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
                                                'attr_name' => 'profile_photo',
                                                'ifs' => 'profile_wrap',
                                            ),
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'instagram-profile-options-wrap',
                                            ),
                                        ),
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Profile Stats', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-profile-stats',
                                            'name' => 'instagram-profile-stats',
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
                                                'attr_name' => 'profile_stats',
                                                'ifs' => 'profile_wrap',
                                            ),
                                        ),
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Profile Name', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-profile-name',
                                            'name' => 'instagram-profile-name',
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
                                                'attr_name' => 'profile_name',
                                                'ifs' => 'profile_wrap',
                                            ),
                                        ),
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Show Profile Description', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-profile-description',
                                            'name' => 'instagram-profile-description',
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
                                                'attr_name' => 'profile_description',
                                                'ifs' => 'profile_wrap',
                                            ),
                                            'sub_options_end' => 2,
                                        ),
                                        //******************************************
                                        // Super Gallery
                                        //******************************************
                                        array(
                                            'grouped_options_title' => __('Gallery Options', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Gallery Style', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram-custom-gallery',
                                            'name' => 'instagram-custom-gallery',
                                            'options' => array(
                                                1 => array(
                                                    'label' => __('New Gallery Style', 'feed-them-social'),
                                                    'value' => 'yes',
                                                ),
                                                2 => array(
                                                    'label' => __('Classic Gallery Style', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'super_gallery',
                                                'ifs' => 'super_gallery',
                                            ),
                                        ),
                                        array(
                                            'input_wrap_class' => 'fb-page-columns-option-hide',
                                            'option_type' => 'select',
                                            'label' => __('Number of Columns', 'feed-them-gallery'),
                                            'type' => 'text',
                                            'instructional-text' => '<strong>' . __('NOTE:', 'feed-them-social') . '</strong>' . __('Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below. Please add px after any number.', 'feed-them-gallery') . ' <a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a>',
                                            'id' => 'fts_instagram_columns',
                                            'name' => 'fts_instagram_columns',
                                            'default_value' => '3',
                                            'options' => array(
                                                array(
                                                    'label' => __('1', 'feed-them-social'),
                                                    'value' => '1',
                                                ),
                                                array(
                                                    'label' => __('2', 'feed-them-social'),
                                                    'value' => '2',
                                                ),
                                                array(
                                                    'label' => __('3', 'feed-them-social'),
                                                    'value' => '3',
                                                ),
                                                array(
                                                    'label' => __('4', 'feed-them-social'),
                                                    'value' => '4',
                                                ),
                                                array(
                                                    'label' => __('5', 'feed-them-social'),
                                                    'value' => '5',
                                                ),
                                                array(
                                                    'label' => __('6', 'feed-them-social'),
                                                    'value' => '6',
                                                ),
                                                array(
                                                    'label' => __('7', 'feed-them-social'),
                                                    'value' => '7',
                                                ),
                                                array(
                                                    'label' => __('8', 'feed-them-social'),
                                                    'value' => '8',
                                                )
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'columns',
                                                'ifs' => 'super_gallery',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-super-instagram-options-wrap',
                                            ),

                                        ),
                                        array(
                                            'input_wrap_class' => 'fb-page-columns-option-hide',
                                            'option_type' => 'select',
                                            'label' => __('Force Columns', 'feed-them-gallery') . '<br/><small>' . __('No, will allow the images to be responsive for smaller devices. Yes, will force columns.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'fts_instagram_force_columns',
                                            'name' => 'fts_instagram_force_columns',
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
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'force_columns',
                                                'ifs' => 'super_gallery',
                                            ),
                                        ),

                                        //Space between Photos
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('The space between photos', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'fts-slicker-instagram-container-margin',
                                            'name' => 'fts-slicker-instagram-container-margin',
                                            'placeholder' => '1px',
                                            'value' => '',
                                            'short_attr' => array(
                                                'attr_name' => 'space_between_photos',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'space_between_photos=1px',
                                                'ifs' => 'super_gallery',
                                            ),
                                        ),
                                        //Icon Size
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Size of the Instagram Icon', 'feed-them-social'),
                                            'label_note' => __('Visible when you hover over photo', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'fts-slicker-instagram-icon-center',
                                            'name' => 'fts-slicker-instagram-icon-center',
                                            'placeholder' => '65px',
                                            'short_attr' => array(
                                                'attr_name' => 'icon_size',
                                                'var_final_if' => 'yes',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'icon_size=65px',
                                                'ifs' => 'super_gallery',
                                            ),
                                        ),
                                        //Hide Date, Likes and Comments
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Date, Heart & Comment icon', 'feed-them-social'),
                                            'label_note' => __('Good for image sizes under 120px', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'fts-slicker-instagram-container-hide-date-likes-comments',
                                            'name' => 'fts-slicker-instagram-container-hide-date-likes-comments',
                                            'options' => array(
                                                1 => array(
                                                    'label' => __('Show', 'feed-them-social'),
                                                    'value' => 'no',
                                                ),
                                                2 => array(
                                                    'label' => __('Hide', 'feed-them-social'),
                                                    'value' => 'yes',
                                                ),
                                            ),
                                            'short_attr' => array(
                                                'attr_name' => 'hide_date_likes_comments',
                                                'ifs' => 'super_gallery',
                                            ),
                                            'sub_options_end' => true,
                                        ),
                                        //******************************************
                                        // Load More
                                        //******************************************
                                        array(
                                            'grouped_options_title' => __('Load More', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Load more posts', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_load_more_option',
                                            'name' => 'instagram_load_more_option',
                                            // Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
                                            'prem_req' => 'yes',
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
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'load_more',
                                                'var_final_if' => 'no',
                                                'no_attribute' => 'yes',
                                            ),
                                        ),
                                        //Load More Option Type
                                        array(
                                            'option_type' => 'select',
                                            'label' => __('Load more style', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_load_more_style',
                                            'name' => 'instagram_load_more_style',
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
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore',
                                                'var_final_if' => 'no',
                                                'var_final_value' => '',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-instagram-load-more-options-wrap',
                                            ),
                                        ),
                                        //Instagram Page Load more Amount
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Amount', 'feed-them-social') . '<br/><small>' . __('How many more posts will load at a time.', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'instagram_loadmore_count',
                                            'name' => 'instagram_loadmore_count',
                                            'placeholder' => __('5 is the default number', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_count',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_count=5',
                                                'ifs' => 'load_more',
                                            ),
                                            //This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
                                            'sub_options' => array(
                                                'sub_options_wrap_class' => 'fts-instagram-load-more-options2-wrap',
                                                //'sub_options_instructional_txt' => '<a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
                                            ),
                                        ),

                                        //Instagram Page Load more Button Width
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Width', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto width', 'feed-them-social') . '</small>',
                                            'type' => 'text',
                                            'id' => 'instagram_loadmore_button_width',
                                            'name' => 'instagram_loadmore_button_width',
                                            'placeholder' => '300px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_btn_maxwidth',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_btn_maxwidth=300px',
                                                'ifs' => 'load_more',
                                            ),
                                        ),
                                        //Facebook Page Load more Button Margin
                                        array(
                                            'option_type' => 'input',
                                            'label' => __('Load more Button Margin', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_loadmore_button_margin',
                                            'name' => 'instagram_loadmore_button_margin',
                                            'placeholder' => '10px ' . __('for example', 'feed-them-social'),
                                            'value' => '',
                                            'req_plugin' => 'fts_premium',
                                            //Relative to JS.
                                            'short_attr' => array(
                                                'attr_name' => 'loadmore_btn_margin',
                                                'empty_error' => 'set',
                                                'empty_error_value' => 'loadmore_btn_margin=10px',
                                                'ifs' => 'load_more',
                                            ),
                                            'sub_options_end' => 2,
                                        ),
                                        //Pop Up Option
                                        array(
                                            'grouped_options_title' => __('Popup', 'feed-them-social'),
                                            'option_type' => 'select',
                                            'label' => __('Display Photos & Videos in Popup', 'feed-them-social'),
                                            'type' => 'text',
                                            'id' => 'instagram_popup_option',
                                            'name' => 'instagram_popup_option',
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
                                                'attr_name' => 'popup',
                                            ),
                                        ),
                                    ),
                                    //Final Shortcode ifs
                                    'shortcode_ifs' => array(
                                        'profile_wrap' => array(
                                            'if' => array(
                                                'class' => 'select#instagram-profile-wrap',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                        'super_gallery' => array(
                                            'if' => array(
                                                'class' => 'select#instagram-custom-gallery',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                        'load_more' => array(
                                            'if' => array(
                                                'class' => 'select#instagram_load_more_option',
                                                'operator' => '==',
                                                'value' => 'yes',
                                            ),
                                        ),
                                    ),
                                    //Generator Info
                                    'generator_title' => __('Instagram Feed Shortcode', 'feed-them-social'),
                                    'generator_class' => 'instagram-final-shortcode',
                                ),//End Instagram Feed
                            );

                            echo $fts_functions->fts_settings_html_form(false, $feed_settings_array, $required_plugins);
                            ?>

                        </section>
                    </div> <!-- #fts-tab-content1 -->

                    <div id="fts-tab-content2"
                         class="fts-tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'global_options') {
                             echo ' pane-active';
                         } ?>">
                        <section>
                            <div class="feed-them-clear-cache">
                                <h2><?php _e('Clear All Cache Options', 'feed-them-social'); ?></h2>
                                <div class="use-of-plugin"><?php _e('Please Clear Cache if you have changed a Feed Them Social Shortcode. This will Allow you to see the changes right away.', 'feed-them-social'); ?></div>
                                <?php if (isset($_GET['cache']) && $_GET['cache'] == 'clearcache') {
                                    echo '<div class="feed-them-clear-cache-text">' . $fts_functions->feed_them_clear_cache() . '</div>';
                                }
                                isset($ftsDevModeCache) ? $ftsDevModeCache : "";
                                isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
                                $ftsDevModeCache = get_option('fts_clear_cache_developer_mode') == TRUE ? get_option('fts_clear_cache_developer_mode') : '900';
                                $ftsAdminBarMenu = get_option('fts_admin_bar_menu');
                                ?>

                                <form method="post" action="?page=feed-them-settings-page&cache=clearcache&tab=global_options">
                                    <input class="feed-them-social-admin-submit-btn" type="submit" value="<?php _e('Clear All FTS Feeds Cache', 'feed-them-social'); ?>"/>
                                </form>
                            </div><!--/feed-them-clear-cache-->
                            <!-- custom option for padding -->
                            <form method="post" class="fts-color-settings-admin-form" action="options.php">
                                <p>
                                    <label><?php _e('Cache Time', 'feed-them-social'); ?></label>
                                    <select id="fts_clear_cache_developer_mode" name="fts_clear_cache_developer_mode">
                                        <option value="10" <?php if ($ftsDevModeCache == '10') echo 'selected="selected"'; ?>><?php _e('10 Seconds (for testing only)', 'feed-them-social'); ?></option>
                                        <option value="300" <?php if ($ftsDevModeCache == '300') echo 'selected="selected"'; ?>><?php _e('5 Minutes', 'feed-them-social'); ?></option>
                                        <option value="600" <?php if ($ftsDevModeCache == '600') echo 'selected="selected"'; ?>><?php _e('10 Minutes', 'feed-them-social'); ?></option>
                                        <option value="900" <?php if ($ftsDevModeCache == '900') echo 'selected="selected"'; ?>><?php _e('15 Minutes', 'feed-them-social'); ?></option>
                                        <option value="1200" <?php if ($ftsDevModeCache == '1200') echo 'selected="selected"'; ?>><?php _e('20 Minutes', 'feed-them-social'); ?></option>
                                        <option value="1800" <?php if ($ftsDevModeCache == '1800') echo 'selected="selected"'; ?>><?php _e('30 Minutes', 'feed-them-social'); ?></option>
                                        <option value="3600" <?php if ($ftsDevModeCache == '3600') echo 'selected="selected"'; ?>><?php _e('60 Minutes', 'feed-them-social'); ?></option>
                                        <option value="86400" <?php if ($ftsDevModeCache == '86400') echo 'selected="selected"'; ?>><?php _e('1 Day (Default)', 'feed-them-social'); ?></option>
                                        <option value="604800" <?php if ($ftsDevModeCache == '604800') echo 'selected="selected"'; ?>><?php _e('1 Week', 'feed-them-social'); ?></option>
                                        <option value="1209600" <?php if ($ftsDevModeCache == '1209600') echo 'selected="selected"'; ?>><?php _e('2 Weeks', 'feed-them-social'); ?></option>
                                        <option value="1" <?php if ($ftsDevModeCache == '1') echo 'selected="selected"'; ?>><?php _e('Clear cache on every page load', 'feed-them-social'); ?></option>
                                    </select>
                                </p>
                                <label><?php _e('Admin Bar', 'feed-them-social'); ?></label>
                                <select id="fts_admin_bar_menu" name="fts_admin_bar_menu">
                                    <option value="show-admin-bar-menu" <?php if ($ftsAdminBarMenu == 'show-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Show Admin Bar Menu', 'feed-them-social'); ?></option>
                                    <option value="hide-admin-bar-menu" <?php if ($ftsAdminBarMenu == 'hide-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Hide Admin Bar Menu', 'feed-them-social'); ?></option>
                                </select>
                                <div class="feed-them-custom-css">
                                    <?php // get our registered settings from the fts functions
                                    settings_fields('feed-them-social-settings'); ?>
                                    <?php
                                    isset($ftsDateTimeFormat) ? $ftsDateTimeFormat : "";
                                    isset($ftsTimezone) ? $ftsTimezone : "";
                                    isset($ftsCustomDate) ? $ftsCustomDate : "";
                                    isset($ftsCustomTime) ? $ftsCustomTime : "";
                                    $ftsDateTimeFormat = get_option('fts-date-and-time-format');
                                    $ftsTimezone = get_option('fts-timezone');
                                    $ftsCustomDate = get_option('date_format');
                                    $ftsCustomTime = get_option('time_format');
                                    $ftsCustomTimezone = get_option('fts-timezone') ? get_option('fts-timezone') : "America/Los_Angeles";
                                    date_default_timezone_set($ftsCustomTimezone);

                                    ?>
                                    <div style="float:left; max-width:400px; margin-right:30px;">
                                        <h2><?php _e('FaceBook & Twitter Date Format', 'feed-them-social'); ?></h2>

                                        <fieldset>
                                            <select id="fts-date-and-time-format" name="fts-date-and-time-format">
                                                <option value="l, F jS, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'l, F jS, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('l, F jS, Y \a\t g:ia'); ?></option>
                                                <option value="F j, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('F j, Y \a\t g:ia'); ?></option>
                                                <option value="F j, Y g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y g:ia') echo 'selected="selected"'; ?>><?php echo date('F j, Y g:ia'); ?></option>
                                                <option value="F, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('F, Y \a\t g:ia'); ?></option>
                                                <option value="M j, Y @ g:ia" <?php if ($ftsDateTimeFormat == 'M j, Y @ g:ia') echo 'selected="selected"'; ?>><?php echo date('M j, Y @ g:ia'); ?></option>
                                                <option value="M j, Y @ G:i" <?php if ($ftsDateTimeFormat == 'M j, Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('M j, Y @ G:i'); ?></option>
                                                <option value="m/d/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'm/d/Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('m/d/Y \a\t g:ia'); ?></option>
                                                <option value="m/d/Y @ G:i" <?php if ($ftsDateTimeFormat == 'm/d/Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('m/d/Y @ G:i'); ?></option>
                                                <option value="d/m/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'd/m/Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('d/m/Y \a\t g:ia'); ?></option>
                                                <option value="d/m/Y @ G:i" <?php if ($ftsDateTimeFormat == 'd/m/Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('d/m/Y @ G:i'); ?></option>
                                                <option value="Y/m/d \a\t g:ia" <?php if ($ftsDateTimeFormat == 'Y/m/d \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('Y/m/d \a\t g:ia'); ?></option>
                                                <option value="Y/m/d @ G:i" <?php if ($ftsDateTimeFormat == 'Y/m/d @ G:i') echo 'selected="selected"'; ?>><?php echo date('Y/m/d @ G:i'); ?></option>
                                                <option value="one-day-ago" <?php if ($ftsDateTimeFormat == 'one-day-ago') echo 'selected="selected"'; ?>><?php _e('1 day ago', 'feed-them-social'); ?></option>
                                                <option value="fts-custom-date" <?php if ($ftsDateTimeFormat == 'fts-custom-date') echo 'selected="selected"'; ?>><?php _e('Use Custom Date and Time Option Below', 'feed-them-social'); ?></option>
                                            </select>
                                        </fieldset>

                                        <?php
                                        //Date translate
                                        $fts_language_second = get_option('fts_language_second', 'second');
                                        $fts_language_seconds = get_option('fts_language_seconds', 'seconds');
                                        $fts_language_minute = get_option('fts_language_minute', 'minute');
                                        $fts_language_minutes = get_option('fts_language_minutes', 'minutes');
                                        $fts_language_hour = get_option('fts_language_hour', 'hour');
                                        $fts_language_hours = get_option('fts_language_hours', 'hours');
                                        $fts_language_day = get_option('fts_language_day', 'day');
                                        $fts_language_days = get_option('fts_language_days', 'days');
                                        $fts_language_week = get_option('fts_language_week', 'week');
                                        $fts_language_weeks = get_option('fts_language_weeks', 'weeks');
                                        $fts_language_month = get_option('fts_language_month', 'month');
                                        $fts_language_months = get_option('fts_language_months', 'months');
                                        $fts_language_year = get_option('fts_language_year', 'year');
                                        $fts_language_years = get_option('fts_language_years', 'years');
                                        $fts_language_ago = get_option('fts_language_ago', 'ago');
                                        ?>

                                        <div class="custom_time_ago_wrap" style="display:none;">
                                            <h2><?php _e('Translate words for 1 day ago option.', 'feed-them-social'); ?></h2>
                                            <label for="fts_language_second"><?php _e("second"); ?></label>
                                            <input name="fts_language_second" type="text" value="<?php echo stripslashes(esc_attr($fts_language_second)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_seconds"><?php _e("seconds"); ?></label>
                                            <input name="fts_language_seconds" type="text" value="<?php echo stripslashes(esc_attr($fts_language_seconds)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_minute"><?php _e("minute"); ?></label>
                                            <input name="fts_language_minute" type="text" value="<?php echo stripslashes(esc_attr($fts_language_minute)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_minutes"><?php _e("minutes"); ?></label>
                                            <input name="fts_language_minutes" type="text" value="<?php echo stripslashes(esc_attr($fts_language_minutes)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_hour"><?php _e("hour"); ?></label>
                                            <input name="fts_language_hour" type="text" value="<?php echo stripslashes(esc_attr($fts_language_hour)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_hours"><?php _e("hours"); ?></label>
                                            <input name="fts_language_hours" type="text" value="<?php echo stripslashes(esc_attr($fts_language_hours)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_day"><?php _e("day"); ?></label>
                                            <input name="fts_language_day" type="text" value="<?php echo stripslashes(esc_attr($fts_language_day)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_days"><?php _e("days"); ?></label>
                                            <input name="fts_language_days" type="text" value="<?php echo stripslashes(esc_attr($fts_language_days)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_week"><?php _e("week"); ?></label>
                                            <input name="fts_language_week" type="text" value="<?php echo stripslashes(esc_attr($fts_language_week)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_weeks"><?php _e("weeks"); ?></label>
                                            <input name="fts_language_weeks" type="text" value="<?php echo stripslashes(esc_attr($fts_language_weeks)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_month"><?php _e("month"); ?></label>
                                            <input name="fts_language_month" type="text" value="<?php echo stripslashes(esc_attr($fts_language_month)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_months"><?php _e("months"); ?></label>
                                            <input name="fts_language_months" type="text" value="<?php echo stripslashes(esc_attr($fts_language_months)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_year"><?php _e("year"); ?></label>
                                            <input name="fts_language_year" type="text" value="<?php echo stripslashes(esc_attr($fts_language_year)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_years"><?php _e("years"); ?></label>
                                            <input name="fts_language_years" type="text" value="<?php echo stripslashes(esc_attr($fts_language_years)); ?>" size="25"/>
                                            <br/>
                                            <label for="fts_language_ago"><?php _e("ago"); ?></label>
                                            <input name="fts_language_ago" type="text" value="<?php echo stripslashes(esc_attr($fts_language_ago)); ?>" size="25"/>

                                        </div>
                                        <script>
                                            // change the feed type 'how to' message when a feed type is selected

                                            <?php if ($ftsDateTimeFormat == 'one-day-ago'){ ?>
                                            jQuery('.custom_time_ago_wrap').show();
                                            <?php    } ?>
                                            jQuery('#fts-date-and-time-format').change(function () {

                                                var ftsTimeAgo = jQuery("select#fts-date-and-time-format").val();
                                                if (ftsTimeAgo == 'one-day-ago') {
                                                    jQuery('.custom_time_ago_wrap').show();
                                                }
                                                else {
                                                    jQuery('.custom_time_ago_wrap').hide();
                                                }

                                            });

                                        </script>
                                        <h2 style="border-top:0px; margin-bottom:4px !important;"><?php _e('Custom Date and Time', 'feed-them-social'); ?></h2>
                                        <div><?php if ($ftsCustomDate !== '' || $ftsCustomTime !== '') {
                                                echo date(get_option('fts-custom-date') . ' ' . get_option('fts-custom-time'));
                                            } ?></div>
                                        <p style="margin:12px 0 !important;">
                                            <input name="fts-custom-date" style="max-width:105px;" class="fts-color-settings-admin-input" id="fts-custom-date" placeholder="<?php _e('Date', 'feed-them-social'); ?>" value="<?php echo get_option('fts-custom-date'); ?>"/>
                                            <input name="fts-custom-time" style="max-width:75px;" class="fts-color-settings-admin-input" id="fts-custom-time" placeholder="<?php _e('Time', 'feed-them-social'); ?>" value="<?php echo get_option('fts-custom-time'); ?>"/>
                                        </p>
                                        <div><?php _e('This will override the date and time format above.', 'feed-them-social'); ?>
                                            <br/><a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e('Options for custom date and time formatting.', 'feed-them-social'); ?></a>
                                        </div>
                                    </div>
                                    <div style="float:left; max-width:330px; margin-right: 30px;">
                                        <h2><?php _e('TimeZone', 'feed-them-social'); ?></h2>
                                        <fieldset>
                                            <select id="fts-timezone" name="fts-timezone">
                                                <option value="Pacific/Midway" <?php if ($ftsTimezone == "Pacific/Midway") echo 'selected="selected"' ?> ><?php _e('(GMT-11:00) Midway Island, Samoa', 'feed-them-social'); ?></option>

                                                <option value="America/Adak" <?php if ($ftsTimezone == "America/Adak") echo 'selected="selected"' ?> ><?php _e('(GMT-10:00) Hawaii-Aleutian', 'feed-them-social'); ?></option>

                                                <option value="Etc/GMT+10" <?php if ($ftsTimezone == "Etc/GMT+10") echo 'selected="selected"' ?> ><?php _e('(GMT-10:00) Hawaii', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Marquesas" <?php if ($ftsTimezone == "Pacific/Marquesas") echo 'selected="selected"' ?> ><?php _e('(GMT-09:30) Marquesas Islands', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Gambier" <?php if ($ftsTimezone == "Pacific/Gambier") echo 'selected="selected"' ?> ><?php _e('(GMT-09:00) Gambier Islands', 'feed-them-social'); ?></option>

                                                <option value="America/Anchorage" <?php if ($ftsTimezone == "America/Anchorage") echo 'selected="selected"' ?> ><?php _e('(GMT-09:00) Alaska', 'feed-them-social'); ?></option>

                                                <option value="America/Ensenada" <?php if ($ftsTimezone == "America/Ensenada") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Tijuana, Baja California', 'feed-them-social'); ?></option>

                                                <option value="Etc/GMT+8" <?php if ($ftsTimezone == "Etc/GMT+8") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Pitcairn Islands', 'feed-them-social'); ?></option>

                                                <option value="America/Los_Angeles" <?php if ($ftsTimezone == "America/Los_Angeles") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Pacific Time (US & Canada)', 'feed-them-social'); ?></option>

                                                <option value="America/Denver" <?php if ($ftsTimezone == "America/Denver") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Mountain Time (US & Canada)', 'feed-them-social'); ?></option>

                                                <option value="America/Chihuahua" <?php if ($ftsTimezone == "America/Chihuahua") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'feed-them-social'); ?></option>

                                                <option value="America/Dawson_Creek" <?php if ($ftsTimezone == "America/Dawson_Creek") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Arizona', 'feed-them-social'); ?></option>

                                                <option value="America/Belize" <?php if ($ftsTimezone == "America/Belize") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Saskatchewan, Central America', 'feed-them-social'); ?></option>

                                                <option value="America/Cancun" <?php if ($ftsTimezone == "America/Cancun") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'feed-them-social'); ?></option>

                                                <option value="Chile/EasterIsland" <?php if ($ftsTimezone == "Chile/EasterIsland") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Easter Island', 'feed-them-social'); ?></option>

                                                <option value="America/Chicago" <?php if ($ftsTimezone == "America/Chicago") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Central Time (US & Canada)', 'feed-them-social'); ?></option>

                                                <option value="America/New_York" <?php if ($ftsTimezone == "America/New_York") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Eastern Time (US & Canada)', 'feed-them-social'); ?></option>

                                                <option value="America/Havana" <?php if ($ftsTimezone == "America/Havana") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Cuba', 'feed-them-social'); ?></option>

                                                <option value="America/Bogota" <?php if ($ftsTimezone == "America/Bogota") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Bogota, Lima, Quito, Rio Branco', 'feed-them-social'); ?></option>

                                                <option value="America/Caracas" <?php if ($ftsTimezone == "America/Caracas") echo 'selected="selected"' ?> ><?php _e('(GMT-04:30) Caracas', 'feed-them-social'); ?></option>

                                                <option value="America/Santiago" <?php if ($ftsTimezone == "America/Santiago") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Santiago', 'feed-them-social'); ?></option>

                                                <option value="America/La_Paz" <?php if ($ftsTimezone == "America/La_Paz") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) La Paz', 'feed-them-social'); ?></option>

                                                <option value="Atlantic/Stanley" <?php if ($ftsTimezone == "Atlantic/Stanley") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Faukland Islands', 'feed-them-social'); ?></option>

                                                <option value="America/Campo_Grande" <?php if ($ftsTimezone == "America/Campo_Grande") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Brazil', 'feed-them-social'); ?></option>

                                                <option value="America/Goose_Bay" <?php if ($ftsTimezone == "America/Goose_Bay") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Atlantic Time (Goose Bay)', 'feed-them-social'); ?></option>

                                                <option value="America/Glace_Bay" <?php if ($ftsTimezone == "America/Glace_Bay") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Atlantic Time (Canada)', 'feed-them-social'); ?></option>

                                                <option value="America/St_Johns" <?php if ($ftsTimezone == "America/St_Johns") echo 'selected="selected"' ?> ><?php _e('(GMT-03:30) Newfoundland', 'feed-them-social'); ?></option>

                                                <option value="America/Araguaina" <?php if ($ftsTimezone == "America/Araguaina") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) UTC-3', 'feed-them-social'); ?></option>

                                                <option value="America/Montevideo" <?php if ($ftsTimezone == "America/Montevideo") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Montevideo', 'feed-them-social'); ?></option>

                                                <option value="America/Miquelon" <?php if ($ftsTimezone == "America/Miquelon") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Miquelon, St. Pierre', 'feed-them-social'); ?></option>

                                                <option value="America/Godthab" <?php if ($ftsTimezone == "America/Godthab") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Greenland', 'feed-them-social'); ?></option>

                                                <option value="America/Argentina/Buenos_Aires" <?php if ($ftsTimezone == "America/Argentina/Buenos_Aires") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Buenos Aires', 'feed-them-social'); ?></option>

                                                <option value="America/Sao_Paulo" <?php if ($ftsTimezone == "America/Sao_Paulo") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Brasilia', 'feed-them-social'); ?></option>

                                                <option value="America/Noronha" <?php if ($ftsTimezone == "America/Noronha") echo 'selected="selected"' ?> ><?php _e('(GMT-02:00) Mid-Atlantic', 'feed-them-social'); ?></option>

                                                <option value="Atlantic/Cape_Verde" <?php if ($ftsTimezone == "Atlantic/Cape_Verde") echo 'selected="selected"' ?> ><?php _e('(GMT-01:00) Cape Verde Is.', 'feed-them-social'); ?></option>

                                                <option value="Atlantic/Azores" <?php if ($ftsTimezone == "Atlantic/Azores") echo 'selected="selected"' ?> ><?php _e('(GMT-01:00) Azores', 'feed-them-social'); ?></option>

                                                <option value="Europe/Belfast" <?php if ($ftsTimezone == "Europe/Belfast") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Belfast', 'feed-them-social'); ?></option>

                                                <option value="Europe/Dublin" <?php if ($ftsTimezone == "Europe/Dublin") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Dublin', 'feed-them-social'); ?></option>

                                                <option value="Europe/Lisbon" <?php if ($ftsTimezone == "Europe/Lisbon") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Lisbon', 'feed-them-social'); ?></option>

                                                <option value="Europe/London" <?php if ($ftsTimezone == "Europe/London") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : London', 'feed-them-social'); ?></option>

                                                <option value="Africa/Abidjan" <?php if ($ftsTimezone == "Africa/Abidjan") echo 'selected="selected"' ?> ><?php _e('(GMT) Monrovia, Reykjavik', 'feed-them-social'); ?></option>

                                                <option value="Europe/Amsterdam" <?php if ($ftsTimezone == "Europe/Amsterdam") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'feed-them-social'); ?></option>

                                                <option value="Europe/Belgrade" <?php if ($ftsTimezone == "Europe/Belgrade") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'feed-them-social'); ?></option>

                                                <option value="Europe/Brussels" <?php if ($ftsTimezone == "Europe/Brussels") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'feed-them-social'); ?></option>

                                                <option value="Africa/Algiers" <?php if ($ftsTimezone == "Africa/Algiers") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) West Central Africa', 'feed-them-social'); ?></option>

                                                <option value="Africa/Windhoek" <?php if ($ftsTimezone == "Africa/Windhoek") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Windhoek', 'feed-them-social'); ?></option>

                                                <option value="Asia/Beirut" <?php if ($ftsTimezone == "Asia/Beirut") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Beirut', 'feed-them-social'); ?></option>

                                                <option value="Africa/Cairo" <?php if ($ftsTimezone == "Africa/Cairo") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Cairo', 'feed-them-social'); ?></option>

                                                <option value="Asia/Gaza" <?php if ($ftsTimezone == "Asia/Gaza") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Gaza', 'feed-them-social'); ?></option>

                                                <option value="Africa/Blantyre" <?php if ($ftsTimezone == "Africa/Blantyre") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Harare, Pretoria', 'feed-them-social'); ?></option>

                                                <option value="Asia/Jerusalem" <?php if ($ftsTimezone == "Asia/Jerusalem") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Jerusalem', 'feed-them-social'); ?></option>

                                                <option value="Europe/Minsk" <?php if ($ftsTimezone == "Europe/Minsk") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Minsk', 'feed-them-social'); ?></option>

                                                <option value="Asia/Damascus" <?php if ($ftsTimezone == "Asia/Damascus") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Syria', 'feed-them-social'); ?></option>

                                                <option value="Europe/Moscow" <?php if ($ftsTimezone == "Europe/Moscow") echo 'selected="selected"' ?> ><?php _e('(GMT+03:00) Moscow, St. Petersburg, Volgograd', 'feed-them-social'); ?></option>

                                                <option value="Africa/Addis_Ababa" <?php if ($ftsTimezone == "Africa/Addis_Ababa") echo 'selected="selected"' ?> ><?php _e('(GMT+03:00) Nairobi', 'feed-them-social'); ?></option>

                                                <option value="Asia/Tehran" <?php if ($ftsTimezone == "Asia/Tehran") echo 'selected="selected"' ?> ><?php _e('(GMT+03:30) Tehran', 'feed-them-social'); ?></option>

                                                <option value="Asia/Dubai" <?php if ($ftsTimezone == "Asia/Dubai") echo 'selected="selected"' ?> ><?php _e('(GMT+04:00) Abu Dhabi, Muscat', 'feed-them-social'); ?></option>

                                                <option value="Asia/Yerevan" <?php if ($ftsTimezone == "Asia/Yerevan") echo 'selected="selected"' ?> ><?php _e('(GMT+04:00) Yerevan', 'feed-them-social'); ?></option>

                                                <option value="Asia/Kabul" <?php if ($ftsTimezone == "Asia/Kabul") echo 'selected="selected"' ?> ><?php _e('(GMT+04:30) Kabul', 'feed-them-social'); ?></option>

                                                <option value="Asia/Yekaterinburg" <?php if ($ftsTimezone == "Asia/Yekaterinburg") echo 'selected="selected"' ?> ><?php _e('(GMT+05:00) Ekaterinburg', 'feed-them-social'); ?></option>

                                                <option value="Asia/Tashkent" <?php if ($ftsTimezone == "Asia/Tashkent") echo 'selected="selected"' ?> ><?php _e('(GMT+05:00) Tashkent', 'feed-them-social'); ?></option>

                                                <option value="Asia/Kolkata" <?php if ($ftsTimezone == "Asia/Kolkata") echo 'selected="selected"' ?> ><?php _e('(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'feed-them-social'); ?></option>

                                                <option value="Asia/Katmandu" <?php if ($ftsTimezone == "Asia/Katmandu") echo 'selected="selected"' ?> ><?php _e('(GMT+05:45) Kathmandu', 'feed-them-social'); ?></option>

                                                <option value="Asia/Dhaka" <?php if ($ftsTimezone == "Asia/Dhaka") echo 'selected="selected"' ?> ><?php _e('(GMT+06:00) Astana, Dhaka', 'feed-them-social'); ?></option>

                                                <option value="Asia/Novosibirsk" <?php if ($ftsTimezone == "Asia/Novosibirsk") echo 'selected="selected"' ?> ><?php _e('(GMT+06:00) Novosibirsk', 'feed-them-social'); ?></option>

                                                <option value="Asia/Rangoon" <?php if ($ftsTimezone == "Asia/Rangoon") echo 'selected="selected"' ?> ><?php _e('(GMT+06:30) Yangon (Rangoon)', 'feed-them-social'); ?></option>

                                                <option value="Asia/Bangkok" <?php if ($ftsTimezone == "Asia/Bangkok") echo 'selected="selected"' ?> ><?php _e('(GMT+07:00) Bangkok, Hanoi, Jakarta', 'feed-them-social'); ?></option>

                                                <option value="Asia/Krasnoyarsk" <?php if ($ftsTimezone == "Asia/Krasnoyarsk") echo 'selected="selected"' ?> ><?php _e('(GMT+07:00) Krasnoyarsk', 'feed-them-social'); ?></option>

                                                <option value="Asia/Hong_Kong" <?php if ($ftsTimezone == "Asia/Hong_Kong") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 'feed-them-social'); ?></option>

                                                <option value="Asia/Irkutsk" <?php if ($ftsTimezone == "Asia/Irkutsk") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Irkutsk, Ulaan Bataar', 'feed-them-social'); ?></option>

                                                <option value="Australia/Perth" <?php if ($ftsTimezone == "Australia/Perth") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Perth', 'feed-them-social'); ?></option>

                                                <option value="Australia/Eucla" <?php if ($ftsTimezone == "Australia/Eucla") echo 'selected="selected"' ?> ><?php _e('(GMT+08:45) Eucla', 'feed-them-social'); ?></option>

                                                <option value="Asia/Tokyo" <?php if ($ftsTimezone == "Asia/Tokyo") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Osaka, Sapporo, Tokyo', 'feed-them-social'); ?></option>

                                                <option value="Asia/Seoul" <?php if ($ftsTimezone == "Asia/Seoul") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Seoul', 'feed-them-social'); ?></option>

                                                <option value="Asia/Yakutsk" <?php if ($ftsTimezone == "Asia/Yakutsk") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Yakutsk', 'feed-them-social'); ?></option>

                                                <option value="Australia/Adelaide" <?php if ($ftsTimezone == "Australia/Adelaide") echo 'selected="selected"' ?> ><?php _e('(GMT+09:30) Adelaide', 'feed-them-social'); ?></option>

                                                <option value="Australia/Darwin" <?php if ($ftsTimezone == "Australia/Darwin") echo 'selected="selected"' ?> ><?php _e('(GMT+09:30) Darwin', 'feed-them-social'); ?></option>

                                                <option value="Australia/Brisbane" <?php if ($ftsTimezone == "Australia/Brisbane") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Brisbane', 'feed-them-social'); ?></option>

                                                <option value="Australia/Hobart" <?php if ($ftsTimezone == "Australia/Hobart") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Sydney', 'feed-them-social'); ?></option>

                                                <option value="Asia/Vladivostok" <?php if ($ftsTimezone == "Asia/Vladivostok") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Vladivostok', 'feed-them-social'); ?></option>

                                                <option value="Australia/Lord_Howe" <?php if ($ftsTimezone == "Australia/Lord_Howe") echo 'selected="selected"' ?> ><?php _e('(GMT+10:30) Lord Howe Island', 'feed-them-social'); ?></option>

                                                <option value="Etc/GMT-11" <?php if ($ftsTimezone == "Etc/GMT-11") echo 'selected="selected"' ?> ><?php _e('(GMT+11:00) Solomon Is., New Caledonia', 'feed-them-social'); ?></option>

                                                <option value="Asia/Magadan" <?php if ($ftsTimezone == "Asia/Magadan") echo 'selected="selected"' ?> ><?php _e('(GMT+11:00) Magadan', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Norfolk" <?php if ($ftsTimezone == "Pacific/Norfolk") echo 'selected="selected"' ?> ><?php _e('(GMT+11:30) Norfolk Island', 'feed-them-social'); ?></option>

                                                <option value="Asia/Anadyr" <?php if ($ftsTimezone == "Asia/Anadyr") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Anadyr, Kamchatka', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Auckland" <?php if ($ftsTimezone == "Pacific/Auckland") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Auckland, Wellington', 'feed-them-social'); ?></option>

                                                <option value="Etc/GMT-12" <?php if ($ftsTimezone == "Etc/GMT-12") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Chatham" <?php if ($ftsTimezone == "Pacific/Chatham") echo 'selected="selected"' ?> ><?php _e('(GMT+12:45) Chatham Islands', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Tongatapu" <?php if ($ftsTimezone == "Pacific/Tongatapu") echo 'selected="selected"' ?> ><?php _e('(GMT+13:00) Nuku\'alofa', 'feed-them-social'); ?></option>

                                                <option value="Pacific/Kiritimati" <?php if ($ftsTimezone == "Pacific/Kiritimati") echo 'selected="selected"' ?> ><?php _e('(GMT+14:00) Kiritimati', 'feed-them-social'); ?></option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="clear"></div>

                                    <br/>
                                    <h2><?php _e('Custom CSS Option', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox" id="fts-color-options-settings-custom-css" value="1" <?php echo checked('1', get_option('fts-color-options-settings-custom-css')); ?>/>
                                        <?php
                                        if (get_option('fts-color-options-settings-custom-css') == '1') { ?>
                                            <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('Custom CSS option is being used now.', 'feed-them-social'); ?><?php
                                        } else { ?>
                                            <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong> <?php _e('You are using the default CSS.', 'feed-them-social'); ?><?php
                                        }
                                        ?>
                                    </p>
                                    <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'feed-them-social'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'feed-them-social'); ?></span> <?php _e('custom CSS', 'feed-them-social'); ?>
                                    </label>
                                    <div class="clear"></div>
                                    <div class="fts-custom-css-text"><?php _e('Thanks for using our plugin :) Add your custom CSS additions or overrides below.', 'feed-them-social'); ?></div>
                                    <textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></textarea>
                                </div><!--/feed-them-custom-css-->

                                <div class="feed-them-custom-logo-css">
                                    <h2><?php _e('Disable Share Option', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts_disable_share_button" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_disable_share_button" value="1" <?php echo checked('1', get_option('fts_disable_share_button')); ?>/> <?php _e('Check this if you want to disable the Share Icon on all feeds', 'feed-them-social'); ?>
                                    </p>
                                    <br/>
                                    <div class="feed-them-social-admin-input-wrap">
                                        <div class="feed-them-social-admin-input-label fts-social-icons-color-label">
                                            <?php _e('Social Icons Color', 'feed-them-social'); ?>
                                        </div>
                                        <input type="text" name="fts_social_icons_color" class="feed-them-social-admin-input fts-social-icons-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fts_social_icons_color'); ?>"/>
                                        <div class="clear"></div>
                                    </div>
                                    <!--/fts-facebook-feed-styles-input-wrap-->

                                    <div class="feed-them-social-admin-input-wrap">
                                        <div class="feed-them-social-admin-input-label fts-social-icons-color-label">
                                            <?php _e('Social Icons Hover Color', 'feed-them-social'); ?>
                                        </div>
                                        <input type="text" name="fts_social_icons_hover_color" class="feed-them-social-admin-input fts-social-icons-hover-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fts_social_icons_hover_color'); ?>"/>
                                        <div class="clear"></div>
                                    </div>
                                    <!--/fts-facebook-feed-styles-input-wrap-->

                                    <div class="feed-them-social-admin-input-wrap">
                                        <div class="feed-them-social-admin-input-label fts-social-icons-color-back-label">
                                            <?php _e('Icons wrap background Color', 'feed-them-social'); ?>
                                        </div>
                                        <input type="text" name="fts_social_icons_back_color" class="feed-them-social-admin-input fts-social-icons-back-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fts-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fts_social_icons_back_color'); ?>"/>
                                        <div class="clear"></div>
                                    </div>
                                    <!--/fts-facebook-feed-styles-input-wrap-->

                                    <br/>
                                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                                    <h2><?php _e('Disable Magnific Popup CSS', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts_fix_magnific" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_fix_magnific" value="1" <?php echo checked('1', get_option('fts_fix_magnific')); ?>/> <?php _e('Check this if you are experiencing problems with your theme(s) or other plugin(s) popups.', 'feed-them-social'); ?>
                                    </p>
                                    <br/>
                                    <?php } ?>

                                    <h2><?php _e('Fix Twitter Time', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts_twitter_time_offset" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_twitter_time_offset" value="1" <?php echo checked('1', get_option('fts_twitter_time_offset')); ?>/> <?php _e('Check this if the Twitter time is still off by 3 hours after setting the TimeZone above.', 'feed-them-social'); ?>
                                    </p>
                                    <br/>

                                    <h2><?php _e('Fix Internal Server Error', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts_curl_option" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_curl_option" value="1" <?php echo checked('1', get_option('fts_curl_option')); ?>/> <?php _e('Check this option if you are getting a 500 Internal Server Error when trying to load a page with our feed on it.', 'feed-them-social'); ?>
                                    </p>
                                    <br/>

                                    <h2><?php _e('Powered by Text', 'feed-them-social'); ?></h2>
                                    <p>
                                        <input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked('1', get_option('fts-powered-text-options-settings')); ?>/>
                                        <?php
                                        if (get_option('fts-powered-text-options-settings') == '1') { ?>
                                            <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('You are not showing the Powered by Logo.', 'feed-them-social'); ?><?php
                                        } else { ?>
                                            <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong><?php _e('The Powered by text will appear in the site. Awesome! Thanks so much for sharing.', 'feed-them-social'); ?><?php
                                        }
                                        ?>
                                    </p>
                                    <br/>
                                    <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes', 'feed-them-social') ?>"/>
                                    <div class="clear"></div>
                                </div><!--/feed-them-custom-logo-css-->
                            </form>
                    </div><!--/font-content-->

                    </section>
                </div>
            </div>

        </div><!--/feed-them-social-admin-wrap-->

        <script>
            jQuery(document).ready(function ($) {

                //create hash tag in url for fts-tabs
                jQuery('.feed-them-social-admin-wrap #fts-tabs').on('click', "label.fts-tabbed", function () {
                    var myURL = document.location;
                    document.location = myURL + "&tab=" + jQuery(this).attr('id');

                })

            });
        </script>

        <h1 class="plugin-author-note"><?php _e('Plugin Authors Note', 'feed-them-social'); ?></h1>
        <div class="fts-plugin-reviews">
            <div class="fts-plugin-reviews-rate"><?php _e(' Feed Them Social was created by 2 Brothers, Spencer and Justin Labadie. That’s it, 2 people! We spend all our time creating and supporting this plugin. Show us some love if you like our plugin and leave a quick review for us, it will make our day!', 'feed-them-social'); ?>
                <a href="https://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank"><?php _e('Leave us a Review', 'feed-them-social'); ?>
                    ★★★★★</a>
            </div>
            <div class="fts-plugin-reviews-support"><?php _e('If you\'re using the Free plugin and are having troubles getting setup please contact us on the <a href="https://wordpress.org/support/plugin/feed-them-social" target="_blank">Free Wordpress Support Forum</a>. We will respond within 24hrs during weekdays.', 'feed-them-social'); ?>
                <?php _e('If you have a paid extensions from us please use our <a href="https://www.slickremix.com/support/" target="_blank">Paid Extension Support Ticket System</a>.', 'feed-them-social'); ?>

                <div class="fts-text-align-center">
                    <a class="feed-them-social-admin-slick-logo" href="https://www.slickremix.com" target="_blank"></a>
                </div>
            </div>
        </div>

        <script>

            jQuery(document).ready(function () {

                // Master feed selector
                jQuery('#shortcode-form-selector').change(function () {
                    jQuery('.shortcode-generator-form').hide();
                    jQuery('.' + jQuery(this).val()).fadeIn('fast');

                    <?php if (get_option('youtube_custom_access_token') && get_option('youtube_custom_access_token') !== '' && get_option('youtube_custom_access_token') !== '') { ?>
                    if (jQuery('select#shortcode-form-selector').val() == 'youtube-shortcode-form') {
                        jQuery(".shortcode-generator-form.youtube-shortcode-form .fts-required-token-message").hide();
                    }<?php } ?>

                    if (jQuery('select#shortcode-form-selector').val() == 'fts-fb-page-shortcode-form') {
                        jQuery("#facebook-messages-selector").change();
                        jQuery("html, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media").show();
                    }

                    if (jQuery("select#shortcode-form-selector").val() == "vine-shortcode-form") {
                        jQuery("form#feed-selector-form").append('<div class="feed-them-social-admin-input-wrap fts-premium-options-message" id="bye-vine"><a class="not-active-title" href="https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws" target="_blank">Vine Depreciated</a><?php _e('A notice to all users of Feed Them Social that use the Vine feed in our plugin... It appears they will be closing the doors at some point soon. No specific date, but well keep you posted before it gets fully phased out. <a href="https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws">https://medium.com/@vine/important-news-about-vine-909c5f4ae7a7#.lcz07v6ws</a><br><br>You can see the shortcode options and shortcode examples here, we will no longer be creating a shortcode generator for this feed. <a href="https://www.slickremix.com/docs/shortcode-options-table/#vine">https://www.slickremix.com/docs/shortcode-options-table/#vine</a> ', 'feed-them-social') ?></div>')
                        jQuery("#bye-vine").show();
                    }
                    else {
                        jQuery("form#feed-selector-form").remove("#bye-vine");
                    }

                    //Combined Feed
                    <?php if (!is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) { ?>
                    if (jQuery("select#shortcode-form-selector").val() == "combine-steams-shortcode-form") {
                        jQuery('.combine-steams-shortcode-form, .fts-required-more-posts').hide();
                        jQuery('#not_active_main_select, .fts-required-more-posts').show();
                    }
                    <?php } ?>

                    jQuery('select#combine-steams-selector').val('all')
                    //Remove Controller Class so everything reappears for Facebook Feed
                    if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
                        jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
                        jQuery('.fts-required-more-posts').hide();
                    }
                    else {
                        jQuery('.fts-required-more-posts').show();
                    }
                    jQuery('select#facebook-messages-selector option[value="events"]').show();

                });

                jQuery('select#fb_hide_like_box_button').bind('change', function (e) {
                    if (jQuery('select#fb_hide_like_box_button').val() == 'no') {
                        jQuery('.like-box-wrap').show();
                    }
                    else {
                        jQuery('.like-box-wrap').hide();
                    }
                });

                jQuery('#facebook_show_video_button').change(function () {
                    jQuery('.fb-video-play-btn-options-content').toggle();
                });

                //Combine Feed Type Selector
                jQuery('select#combine-steams-selector').bind('change', function (e) {
                    if (jQuery('select#combine-steams-selector').val() == 'multiple_facebook') {
                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').show();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').hide();
                        jQuery('.fts-facebook_page-shortcode-form').addClass('multiple_facebook');

                        jQuery('.multiple_facebook select#facebook-messages-selector option[value="events"]').hide();
                    }
                    else {

                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').hide();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').show();

                        //Remove Controller Class so everything reappears for Facebook Feed
                        if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
                            jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
                        }
                    }
                });

                // change the feed type 'how to' message when a feed type is selected
                jQuery('#facebook-messages-selector').change(function () {
                    jQuery('.facebook-message-generator').hide();
                    jQuery('.' + jQuery(this).val()).fadeIn('fast');
                    // if the facebook type select is changed we hide the shortcode code so not to confuse people
                    jQuery('.final-shortcode-textarea').hide();
                    // only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
                    var facebooktype = jQuery("select#facebook-messages-selector").val();


                    if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'album_videos') {
                        jQuery('.fts-super-facebook-options-wrap,.align-images-wrap').show();
                        jQuery('.fixed_height_option,.main-grid-options-wrap').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').hide();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap,.align-images-wrap ').hide();
                        jQuery('.fixed_height_option,.main-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').show();
                    }

                    if (facebooktype == 'page' || facebooktype == 'event' || facebooktype == 'group') {
                        jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").show();
                    }
                    else {
                        jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").hide();
                    }

                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

                    // This is to show all option when prem active if you selected the Facebook Page reviews if not active. Otherwise all other fb-options-wraps are hidden when selecting another fb feed from settings page drop down.
                    jQuery('.fb-options-wrap').show();
                    jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

                    if (facebooktype == 'album_videos') {
                        jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').hide();
                        jQuery('.video, .fb-video-play-btn-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('# of Posts')").html("<?php _e('# of Videos', 'feed-them-social') ?>");
                    }
                    else {
                        jQuery('.video, .fb-video-play-btn-options-wrap').hide();
                        jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').show();
                        jQuery(".feed-them-social-admin-input-label:contains('# of Videos')").html("<?php _e('# of Posts', 'feed-them-social') ?>");
                    }
                    <?php  }
                    else{ ?>

                    jQuery('.video, .fb-video-play-btn-options-wrap').hide();
                    jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

                    <?php } ?>

                    if (facebooktype == 'page') {
                        jQuery('.inst-text-facebook-page').show();
                    }
                    else {
                        jQuery('.inst-text-facebook-page').hide();
                    }

                    if (facebooktype == 'events') {
                        jQuery('.inst-text-facebook-event-list').show();
                        jQuery('.facebook-loadmore-wrap').hide();

                    }
                    else {
                        jQuery('.inst-text-facebook-event-list').hide();
                        jQuery('.facebook-loadmore-wrap').show();
                    }

                    <?php if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                    if (facebooktype == 'reviews') {
                        jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').show();
                        jQuery('.align-images-wrap,.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').hide();
                    } else {
                        jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').hide();
                        jQuery('.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').show();
                    }
                    <?php }  ?>

                    // only show the post type visible if the facebook page feed type is selected
                    jQuery('.facebook-post-type-visible').hide();
                    if (facebooktype == 'page') {
                        jQuery('.facebook-post-type-visible').show();
                    }
                    var fb_feed_type_option = jQuery("select#facebook-messages-selector").val();
                    if (fb_feed_type_option == 'album_photos') {
                        jQuery('.fb_album_photos_id').show();
                    }
                    else {
                        jQuery('.fb_album_photos_id').hide();
                    }
                });
                //Instagram Profile wrap
                jQuery('select#instagram-profile-wrap').bind('change', function (e) {
                    if (jQuery('#instagram-profile-wrap').val() == 'yes') {
                        jQuery('.instagram-profile-options-wrap').show();
                    }
                    else {
                        jQuery('.instagram-profile-options-wrap').hide();
                    }
                });
                // Instagram Super Gallery option
                jQuery('#instagram-custom-gallery').bind('change', function (e) {
                    if (jQuery('#instagram-custom-gallery').val() == 'no') {
                        jQuery('.fts-super-instagram-options-wrap').hide();
                    }
                    else {
                        jQuery('.fts-super-instagram-options-wrap').show();
                    }
                });


                jQuery('#instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'hashtag') {
                        jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.instagram-location-option-text,.main-instagram-profile-options-wrap").hide();
                        jQuery(".instagram-hashtag-option-text").show();
                    }
                    else if (jQuery('#instagram-messages-selector').val() == 'location') {
                        jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.instagram-hashtag-option-text,.main-instagram-profile-options-wrap").hide();
                        jQuery(".instagram-location-option-text").show();
                    }
                    else {
                        jQuery(".instagram-id-option-wrap,.instagram-user-option-text,.main-instagram-profile-options-wrap").show();
                        jQuery(".instagram-hashtag-option-text,.instagram-location-option-text").hide();
                    }
                });

                jQuery('#combine_instagram_type').bind('change', function (e) {
                    if (jQuery('#combine_instagram_type').val() == 'hashtag') {
                        jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text,.combine-instagram-location-option-text").hide();
                        jQuery(".combine-instagram-hashtag-option-text").show();
                    }
                    else if (jQuery('#combine_instagram_type').val() == 'location') {
                        jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text,.combine-instagram-hashtag-option-text").hide();
                        jQuery(".combine-instagram-location-option-text").show();
                    }
                    else {
                        jQuery(".combine-instagram-id-option-wrap,.combine-instagram-user-option-text").show();
                        jQuery(".combine-instagram-hashtag-option-text,.combine-instagram-location-option-text").hide();
                    }
                });

                    function getQueryString(Param) {
                        return decodeURI(
                            (RegExp('[#|&]' + Param + '=' + '(.+?)(&|$)').exec(location.hash) || [, null])[1]
                        );
                    }

                    if (window.location.hash && getQueryString('feed_type') == 'instagram') {
                        jQuery('#feed-selector-form').find('option[value=instagram-shortcode-form]').attr('selected','selected');
                        jQuery('.shortcode-generator-form.instagram-shortcode-form').show();
                        jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo get_option('fts_instagram_custom_id') ?>');
                        jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo get_option('fts_instagram_custom_api_token') ?>');
                    }

                jQuery('#shortcode-form-selector, #instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'user') {
                        jQuery('#instagram_id, #insta_access_token').val('');
                        jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo get_option('fts_instagram_custom_id') ?>');
                        jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo get_option('fts_instagram_custom_api_token') ?>');
                    }
                    else {
                        jQuery('#instagram_id').val('');
                    }

                });

                jQuery('#combine_instagram, #combine_instagram_type').bind('change', function (e) {
                    jQuery('#combine_instagram_name').val('');
                    if (jQuery('#combine_instagram_type').val() == 'user') {
                        jQuery('#combine_instagram_name').val(jQuery('#combine_instagram_name').val() + '<?php echo get_option('fts_instagram_custom_id') ?>');
                    }
                    else {
                        jQuery('#combine_instagram_name').val('');
                    }

                });

                <?php if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                jQuery('#instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'location') {
                        jQuery("#instagram_id").hide();
                        jQuery('<div class="feed-them-social-admin-input-default fts-custom-premium-required">Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> to edit.</div>').insertAfter('.feed-them-social-admin-input-label.instagram-location-option-text')
                        jQuery(".feed-them-social-admin-submit-btn").hide();
                    }
                    else {
                        jQuery("#instagram_id").show();
                        jQuery(".fts-custom-premium-required").hide();
                        jQuery(".feed-them-social-admin-submit-btn").show();
                    }

                });
                <?php }  ?>

                jQuery('#twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#twitter-messages-selector').val() == 'hashtag') {
                        jQuery(".hashtag-option-small-text,.twitter-hashtag-etc-wrap").show();
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").hide();
                    }
                    else {
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
                        jQuery(".twitter-hashtag-etc-wrap,.hashtag-option-small-text").hide();
                    }
                });

                jQuery('#combine-twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#combine-twitter-messages-selector').val() == 'hashtag') {
                        jQuery(".combine-twitter-hashtag-etc-wrap").show();
                        jQuery(".combine_twitter_name").hide();
                    }
                    else {
                        jQuery(".combine_twitter_name").show();
                        jQuery(".combine-twitter-hashtag-etc-wrap").hide();
                    }
                });

                //Twitter Grid option
                jQuery('#twitter-grid-option').bind('change', function (e) {
                    if (jQuery('#twitter-grid-option').val() == 'yes') {
                        jQuery('.fts-twitter-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Center Facebook Container?', 'feed-them-social'); ?>')").parent('div').show();
                    }
                    else {
                        jQuery('.fts-twitter-grid-options-wrap').hide();
                    }
                });

                //Twitter show load more options
                jQuery('#twitter_load_more_option').bind('change', function (e) {
                    if (jQuery('#twitter_load_more_option').val() == 'yes') {
                        jQuery('.fts-twitter-load-more-options-wrap').show();
                        jQuery('.fts-twitter-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                    }
                });

                //youtube show load more options
                jQuery('#youtube_load_more_option').bind('change', function (e) {
                    if (jQuery('#youtube_load_more_option').val() == 'yes') {
                        jQuery('.fts-youtube-load-more-options-wrap').show();
                        jQuery('.fts-youtube-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-youtube-load-more-options-wrap, .fts-youtube-load-more-options2-wrap').hide();
                    }
                });

                // facebook show grid options
                jQuery('#fb-grid-option').bind('change', function (e) {
                    if (jQuery('#fb-grid-option').val() == 'yes') {
                        jQuery('.fts-facebook-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Center Facebook Container?', 'feed-them-social'); ?>')").parent('div').show();
                    }
                    else {
                        jQuery('.fts-facebook-grid-options-wrap').hide();
                    }
                });

                // facebook Super Gallery option
                jQuery('#facebook-custom-gallery').bind('change', function (e) {
                    if (jQuery('#facebook-custom-gallery').val() == 'yes') {
                        jQuery('.fts-super-facebook-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap').hide();
                    }
                });

                //Facebook Display Popup option
                jQuery('#facebook_popup').bind('change', function (e) {
                    if (jQuery('#facebook_popup').val() == 'yes') {
                        jQuery('.display-comments-wrap').show();
                    }
                    else {
                        jQuery('.display-comments-wrap').hide();
                    }
                });

                // facebook show load more options
                jQuery('#fb_load_more_option').bind('change', function (e) {
                    if (jQuery('#fb_load_more_option').val() == 'yes') {

                        if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
                            jQuery('.fts-facebook-load-more-options-wrap').show();
                        }
                        jQuery('.fts-facebook-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                    }
                });
                // Instagram show load more options
                jQuery('#instagram_load_more_option').bind('change', function (e) {
                    if (jQuery('#instagram_load_more_option').val() == 'yes') {
                        jQuery('.fts-instagram-load-more-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-instagram-load-more-options-wrap').hide();
                    }
                });


                //Combine Grid Options
                jQuery('#combine_grid_option').bind('change', function (e) {
                    if (jQuery('#combine_grid_option').val() == 'yes') {
                        jQuery('.combine-grid-options-wrap ').show();
                    }
                    else {
                        jQuery('.combine-grid-options-wrap ').hide();
                    }
                });

                //Combine Facebook
                jQuery('select#combine_facebook').bind('change', function (e) {
                    if (jQuery('select#combine_facebook').val() == 'yes') {
                        jQuery('.combine-facebook-wrap').show();
                    }
                    else {
                        jQuery('.combine-facebook-wrap').hide();
                    }
                });
                //Combine Twitter
                jQuery('#combine_twitter').bind('change', function (e) {
                    if (jQuery('#combine_twitter').val() == 'yes') {
                        jQuery('.combine-twitter-wrap').show();
                    }
                    else {
                        jQuery('.combine-twitter-wrap').hide();
                    }
                });
                //Combine Instagram
                jQuery('#combine_instagram').bind('change', function (e) {
                    if (jQuery('#combine_instagram').val() == 'yes') {
                        jQuery('.combine-instagram-wrap').show();
                    }
                    else {
                        jQuery('.combine-instagram-wrap').hide();
                    }
                });
                //Combine Pinterest
                jQuery('#combine_pinterest').bind('change', function (e) {
                    if (jQuery('#combine_pinterest').val() == 'yes') {
                        jQuery('.combine-pinterest-wrap').show();
                    }
                    else {
                        jQuery('.combine-pinterest-wrap').hide();
                    }
                });
                //Combine Pinterest Type Options
                jQuery('#combine_pinterest_type').bind('change', function (e) {
                    if (jQuery('#combine_pinterest_type').val() == 'pins_from_user') {
                        jQuery('.combine_board_id').hide();
                    }
                    if (jQuery('#combine_pinterest_type').val() == 'single_board_pins') {
                        jQuery('.combine_board_id').show();
                    }
                })
                //Combine Youtube
                jQuery('#combine_youtube').bind('change', function (e) {
                    if (jQuery('#combine_youtube').val() == 'yes') {
                        jQuery('.combine-youtube-wrap').show();
                    }
                    else {
                        jQuery('.combine-youtube-wrap').hide();
                    }
                });
                //Youtube Options
                jQuery('select#combine_youtube_type').bind('change', function (e) {
                    if (jQuery('#combine_youtube_type').val() == 'channelID') {
                        jQuery('.combine_youtube_name, .combine_playlist_id').hide();
                        jQuery('.combine_channel_id').show();
                    }
                    else if (jQuery('#combine_youtube_type').val() == 'userPlaylist') {
                        jQuery('.combine_channel_id').hide();
                        jQuery('.combine_playlist_id, .combine_youtube_name').show();
                    }
                    else if (jQuery('#combine_youtube_type').val() == 'playlistID') {
                        jQuery('.combine_youtube_name').hide();
                        jQuery('.combine_playlist_id, .combine_channel_id').show();
                    }
                    else {
                        jQuery('.combine_youtube_name').show();
                        jQuery('.combine_playlist_id, .combine_channel_id').hide();
                    }
                });


                // Pinterest options
                // hide this div till needed for free version
                jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').hide();
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'boards_list') {
                        jQuery('.number-of-boards, .pinterest-name-text').show();
                        jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').hide();
                    }
                });
                // Pinterest options
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'single_board_pins') {
                        jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').show();
                        jQuery('.number-of-boards, .pinterest-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').show();
                    }
                })
                // Pinterest options
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'pins_from_user') {
                        jQuery('.show-pins-amount, .pinterest-name-text').show();
                        jQuery('.number-of-boards, .board-name, .pinterest-board-and-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').show();
                    }
                });


            });
            <?php
            $output = '';
            //If shortcode Generator Changes
            echo 'jQuery("#shortcode-form-selector").change(function () {';
            //Hide Premium Msg Boxes if showing
            echo 'jQuery("div.fts-premium-options-message").hide();';
            echo '});';
            foreach ($feed_settings_array as $section => $section_info) {

            //Premium Message Boxes JS
            if (isset($section_info['premium_msg_boxes'])) {
                echo 'jQuery("#' . $section_info['feed_type_select']['select_id'] . '").change(function () {';
                echo 'jQuery("form.' . $section . '_options_wrap").show();';
                foreach ($section_info['premium_msg_boxes'] as $key => $premium_msg) if (!is_plugin_active($required_plugins[$premium_msg['req_plugin']]['plugin_url'])) {
                    //If Variables
                    $premium_if_class = $section_info['shortcode_ifs'][$key]['if']['class'];
                    $premium_if_operator = $section_info['shortcode_ifs'][$key]['if']['operator'];
                    $premium_if_value = $section_info['shortcode_ifs'][$key]['if']['value'];
                    echo 'if (jQuery("' . $premium_if_class . '").val() ' . $premium_if_operator . ' "' . $premium_if_value . '") { jQuery("form.' . $section . '_options_wrap").hide(); jQuery("div#not_active_' . $key . '").show(); }';
                    echo 'else{jQuery("div#not_active_' . $key . '").hide(); }';
                }
                echo '});';
            }

            //Main JS Function for each Feed.
            echo 'function updateTextArea_' . $section . '() { ' . "\n";

            $final_shortcode_var = array();
            foreach ($section_info['main_options'] as $option) {
            $no_attribute = !isset($option['short_attr']['no_attribute']) || isset($option['short_attr']['no_attribute']) && $option['short_attr']['no_attribute'] !== 'yes' ? false : true;
            if ($no_attribute == false) {
            if (!empty($option['short_attr']) || !isset($option['short_attr']['no_html'])) {
                $option_id = isset($option['id']) ? $option['id'] : '';
                $input_wrap_class = isset($option['input_wrap_class']) ? $option['input_wrap_class'] : '';
                $section_attr_key = isset($section_info['section_attr_key']) ? $section_info['section_attr_key'] : '';
                $attr_name = isset($option['short_attr']['attr_name']) ? $option['short_attr']['attr_name'] : '';
                $empty_error = isset($option['short_attr']['empty_error']) ? $option['short_attr']['empty_error'] : '';
                $empty_error_value = isset($option['short_attr']['empty_error_value']) ? $option['short_attr']['empty_error_value'] : '';
                $var_final_check = isset($option['short_attr']['var_final_if']) && ($option['short_attr']['var_final_if'] == 'yes' || $option['short_attr']['var_final_if'] == 'set') ? '_final' : '';

                $set_operator = isset($option['short_attr']['set_operator']) ? $option['short_attr']['set_operator'] : '';
                $set_equals = isset($option['short_attr']['set_equals']) ? $option['short_attr']['set_equals'] : '';

                //Is this field Hidden
                echo 'if (jQuery(\'#' . $option_id . '\').is(":visible") || jQuery(\'#' . $option_id . '\').hasClass( "non-visible")){';
                //Create Variable
                switch ($option['option_type']) {
                    case 'input' :
                        echo 'var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ' = ' . (empty($empty_error) || $empty_error !== 'set' ? '\' ' . $attr_name . '=\' + ' : '') . 'jQuery("input#' . $option_id . '").val();' . "\n";
                        break;
                    case 'select' :
                        echo 'var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ' = \' ' . $attr_name . '=\' + jQuery("select#' . $option_id . '").val();' . "\n";
                        break;
                }
                //If Field Empty throw error (only if field can't be empty)
                if (!empty($empty_error) && $empty_error == 'yes' || !empty($empty_error) && $empty_error == 'set') {
                    //Show Empty Error and Highlight input
                    if ($empty_error == 'yes') {

                        echo isset($option['short_attr']['empty_error_if']) ? 'var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . '_error = jQuery("' . $option['short_attr']['empty_error_if']['attribute'] . '").val(); if (' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . '_error ' . $option['short_attr']['empty_error_if']['operator'] . ' "' . $option['short_attr']['empty_error_if']['value'] . '") {' : '';

                        echo 'if (' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ' == " ' . $attr_name . '=") {
                                    jQuery(".' . $input_wrap_class . '").addClass(\'fts-empty-error\');
                                    jQuery("input#' . $option_id . '").focus();
                                    return false;
                                    }
                                    if (' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ' != " ' . $attr_name . '=") {
                                        jQuery(".' . $input_wrap_class . '").removeClass(\'fts-empty-error\');
                                    }' . "\n";

                        $empty_error_value = !empty($empty_error_value) ? ' ' . $empty_error_value : '';
                        echo isset($option['short_attr']['empty_error_if']) ? '}  
                                if (' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ' != " ' . $attr_name . '=") {
                                    var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check . ' = \' ' . $attr_name . '=\' + jQuery("input#' . $option_id . '").val();
                                }
                                else {
                                    var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check . ' = \'' . $empty_error_value . '\';
                                }
                                ' : '';
                    }
                    //Don't Show Empty Error but Automatically set value if not set.
                    if ($empty_error == 'set') {
                        $empty_error_value = !empty($empty_error_value) ? ' ' . $empty_error_value : '';
                        echo 'if (' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . ($set_operator && $set_equals ? $set_operator . ' \' ' . $attr_name . '=' . $set_equals . '\'' : '') . ') {
                                        var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check . ' = \' ' . $attr_name . '=\' + jQuery("' . $option['option_type'] . '#' . $option_id . '").val();
                                    }
                                    else {
                                        var ' . (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check . ' = \'' . $empty_error_value . '\';
                                    }' . "\n";
                    }
                }
                //Is this field Hidden
                echo '}';
            } else {
                $output .= 'Please add "short_attr" to array.';
            }

            //Premium Required? if so Check if active
            if (!isset($option['req_plugin']) || (isset($option['req_plugin']) && is_plugin_active($required_plugins[$option['req_plugin']]['plugin_url']) || isset($option['or_req_plugin']) && is_plugin_active($required_plugins[$option['or_req_plugin']]['plugin_url'])) || isset($option['or_req_plugin_three']) && is_plugin_active($required_plugins[$option['or_req_plugin_three']]['plugin_url'])) {
                //Check "IF"s if they exist
                if (isset($option['short_attr']['ifs'])) {
                    $if_array = $option['short_attr']['ifs'];
                    $if_array = explode(',', $if_array);
                    foreach ($if_array as $key => $if_group) {
                        $and_if_array = isset($option['short_attr']['and_ifs']) ? $option['short_attr']['and_ifs'] : '';
                        if (!$and_if_array) {
                            $final_shortcode_var[$if_group][$attr_name] = (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check;
                        } else {
                            //Unset to Shift to end if key exists already
                            if (isset($final_shortcode_var[$if_group]['and_ifs'])) {
                                $inital_and_if = $final_shortcode_var[$if_group]['and_ifs'];
                                unset($final_shortcode_var[$if_group]['and_ifs']);
                                $final_shortcode_var[$if_group]['and_ifs'] = $inital_and_if;
                            }
                            $final_shortcode_var[$if_group]['and_ifs'][$option['short_attr']['and_ifs']][$attr_name] = (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check;
                        }
                    }
                } //no IF
                else {
                    $final_shortcode_var['general_options'][] = (isset($section_attr_key) ? $section_attr_key : '') . $attr_name . $var_final_check;
                }
            }

            ?>      // Extra Options to show px if user does not enter it.
                    // Facebook
            var isPXpresent = jQuery('#facebook_page_height').val();
            // This is in place to auto add the px if a specific input is missing it.
            if (jQuery('#facebook_page_height').val().indexOf('px') <= 0 && isPXpresent !== "") {
                jQuery('#facebook_page_height').val(jQuery('#facebook_page_height').val() + 'px');
            }
            <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
            var isPXpresent2 = jQuery('#facebook_grid_column_width').val();
            if (jQuery('#facebook_grid_column_width').val().indexOf('px') <= 0 && isPXpresent2 !== "") {
                jQuery('#facebook_grid_column_width').val(jQuery('#facebook_grid_column_width').val() + 'px');
            }
            var isPXpresent3 = jQuery('#facebook_grid_space_between_posts').val();
            if (jQuery('#facebook_grid_space_between_posts').val().indexOf('px') <= 0 && isPXpresent3 !== "") {
                jQuery('#facebook_grid_space_between_posts').val(jQuery('#facebook_grid_space_between_posts').val() + 'px');
            }
            var isPXpresent4 = jQuery('#loadmore_button_width').val();
            if (jQuery('#loadmore_button_width').val().indexOf('px') <= 0 && isPXpresent4 !== "") {
                jQuery('#loadmore_button_width').val(jQuery('#loadmore_button_width').val() + 'px');
            }
            var isPXpresent5 = jQuery('#loadmore_button_margin').val();
            if (jQuery('#loadmore_button_margin').val().indexOf('px') <= 0 && isPXpresent5 !== "") {
                jQuery('#loadmore_button_margin').val(jQuery('#loadmore_button_margin').val() + 'px');
            }
            var isPXpresent12 = jQuery('#like_box_width').val();
            if (jQuery('#like_box_width').val().indexOf('px') <= 0 && isPXpresent12 !== "") {
                jQuery('#like_box_width').val(jQuery('#like_box_width').val() + 'px');
            }
            <?php } ?>

            // Twitter
            var isPXpresent6 = jQuery('#twitter_height').val();
            if (jQuery('#twitter_height').val().indexOf('px') <= 0 && isPXpresent6 !== "") {
                jQuery('#twitter_height').val(jQuery('#twitter_height').val() + 'px');
            }
            // Instagram
            var isPXpresent11 = jQuery('#instagram_page_height').val();
            if (jQuery('#instagram_page_height').val().indexOf('px') <= 0 && isPXpresent11 !== "") {
                jQuery('#instagram_page_height').val(jQuery('#instagram_page_height').val() + 'px');
            }
            // Instagram
            var isPXpresent13 = jQuery('#fts-slicker-youtube-container-margin').val();
            if (jQuery('#fts-slicker-youtube-container-margin').val().indexOf('px') <= 0 && isPXpresent13 !== "") {
                jQuery('#fts-slicker-youtube-container-margin').val(jQuery('#fts-slicker-youtube-container-margin').val() + 'px');
            }

            <?php if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) { ?>
            var isPXpresent = jQuery('#combine_grid_column_width').val();
            // This is in place to auto add the px if a specific input is missing it.
            if (jQuery('#combine_grid_column_width').val().indexOf('px') <= 0 && isPXpresent !== "") {
                jQuery('#combine_grid_column_width').val(jQuery('#combine_grid_column_width').val() + 'px');
            }
            var isPXpresent = jQuery('#combine_grid_space_between_posts').val();
            // This is in place to auto add the px if a specific input is missing it.
            if (jQuery('#combine_grid_space_between_posts').val().indexOf('px') <= 0 && isPXpresent !== "") {
                jQuery('#combine_grid_space_between_posts').val(jQuery('#combine_grid_space_between_posts').val() + 'px');
            }
            var isPXpresent = jQuery('#combine_height').val();
            // This is in place to auto add the px if a specific input is missing it.
            if (jQuery('#combine_height').val().indexOf('px') <= 0 && isPXpresent !== "") {
                jQuery('#combine_height').val(jQuery('#combine_height').val() + 'px');
            }
            <?php } ?>

            <?php

            }
            }
            //End JS Loop

            //Start Final Shortcode
            echo 'var final_' . $section . '_shorcode_start = \'[fts_' . (isset($section_info['shorcode_label']) ? $section_info['shorcode_label'] : $section) . '\';' . "\n";

            $shortcode_general_options = '';
            echo 'var final_' . $section . '_shorcode_attributes =\'\';' . "\n";

            if (isset($final_shortcode_var['general_options'])) {
                foreach ($final_shortcode_var['general_options'] as $final_attribute) {
                    //Add Attributes to shortcode
                    echo 'if (' . $final_attribute . '){final_' . $section . '_shorcode_attributes +=' . $final_attribute . ';}' . "\n";
                }
            }
            //End of shorcode
            echo 'var final_' . $section . '_shorcode_end = \']\';' . "\n";

            //Special Options!
            foreach ($final_shortcode_var as $special_option_group => $special_options) if ($special_option_group !== 'general_options') {
                if (isset($section_info['shortcode_ifs'][$special_option_group])) {
                    //If Variables
                    $if_class = $section_info['shortcode_ifs'][$special_option_group]['if']['class'];
                    $if_operator = $section_info['shortcode_ifs'][$special_option_group]['if']['operator'];
                    $if_value = $section_info['shortcode_ifs'][$special_option_group]['if']['value'];

                    //And IF variables
                    if (isset($final_shortcode_var[$special_option_group]['and_ifs'])) {
                        $and_ifs_array = $final_shortcode_var[$special_option_group]['and_ifs'];
                        //And Ifs Print
                        foreach ($and_ifs_array as $key => $and_ifs_attribute_array) {
                            //If Variables
                            $and_if_class = $section_info['shortcode_ifs'][$key]['if']['class'];
                            $and_if_operator = $section_info['shortcode_ifs'][$key]['if']['operator'];
                            $and_if_value = $section_info['shortcode_ifs'][$key]['if']['value'];

                            echo 'if (jQuery("' . $if_class . '").val() ' . $if_operator . ' "' . $if_value . '" && jQuery("' . $and_if_class . '").val() ' . $and_if_operator . ' "' . $and_if_value . '") {' . "\n";
                            foreach ($and_ifs_attribute_array as $and_if_key => $and_if_attribute) {
                                //Add Attributes to shortcode
                                echo 'if (' . $and_if_attribute . '){ final_' . $section . '_shorcode_attributes +=' . $and_if_attribute . ';}';
                            }
                            echo "\n" . '}' . "\n";
                        }
                    }
                    unset($final_shortcode_var[$special_option_group]['and_ifs']);
                    //If Variables
                    $i = 0;
                    echo 'if (jQuery("' . $if_class . '").val() ' . $if_operator . ' "' . $if_value . '") {' . "\n";
                    foreach ($final_shortcode_var[$special_option_group] as $key => $final_special_attribute) {
                        //Add Attributes to shortcode
                        echo 'if (' . $final_special_attribute . '){ final_' . $section . '_shorcode_attributes +=' . $final_special_attribute . ';}';
                    }
                    echo "\n" . '}' . "\n";
                }
            }
            //Put the shortcode together
            echo 'var final_' . $section . '_shorcode = final_' . $section . '_shorcode_start + final_' . $section . '_shorcode_attributes + final_' . $section . '_shorcode_end;' . "\n";

            //Create Final Shortcode and show it!
            echo 'jQuery(\'.' . $section_info['generator_class'] . '\').val(final_' . $section . '_shorcode);' . "\n";
            echo 'jQuery(\'.' . $section_info['form_wrap_classes'] . ' .final-shortcode-textarea\').slideDown();';

            echo '}';
            }
            ?>

            //END Instagram//


            //START convert Instagram name to id for regular isntagram and combined feeds instagram option //
            function converter_instagram_username() {

                var convert_instagram_username = jQuery("input#convert_instagram_username").val();

                var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();

                // Regular Instagram Converter
                if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username == "") {
                    jQuery("#convert_instagram_username").addClass('fts-empty-error');
                    jQuery("input#convert_instagram_username").focus();
                    return false;
                }
                else if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username !== "") {
                    jQuery(".convert_instagram_username").removeClass('fts-empty-error');
                    var username_id = "#convert_instagram_username";
                    var picker_wrap = "#fts-instagram-username-picker-wrap";
                    var username = convert_instagram_username;
                }


                // Combined Feeds Converter
                if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined == "") {
                    // alert('wtf');
                    jQuery("#combine_convert_instagram_username").addClass('fts-empty-error');
                    jQuery("input#combine_convert_instagram_username").focus();
                    return false;
                }
                else if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined !== "") {

                    jQuery(".convert_instagram_username").removeClass('fts-empty-error');
                    var username_id = "#combine_convert_instagram_username";
                    var picker_wrap = "#fts-instagram-username-picker-wrap-combined";
                    var username = convert_instagram_username_combined;
                }

                console.log(username);

                <?php $fts_instagram_tokens_array = array('9844495a8c4c4c51a7c519d0e7e8f293', '9844495a8c4c4c51a7c519d0e7e8f293');
                $fts_instagram_access_token = $fts_instagram_tokens_array[array_rand($fts_instagram_tokens_array, 1)];
                ?>
                jQuery.getJSON("https://api.instagram.com/v1/users/search?q=" + username + "&client_id=<?php echo $fts_instagram_access_token; ?>&access_token=258559306.da06fb6.c222db6f1a794dccb7a674fec3f0941f&callback=?",

                    {
                        format: "json"
                    },
                    function (data) {

                        console.log(data);

                        var convert_instagram_username = jQuery("input#convert_instagram_username").val();

                        var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();
                        jQuery('.fts-instagram-username-picker-wrap').html('');
                        jQuery('.fts-instagram-username-picker-wrap').closest('.instructional-text').css("border", "none");


                        jQuery.each(data.data, function (key, val) {
                            console.log(data.data[key].full_name);
                            console.log(data.data[key].profile_picture);
                            console.log(data.data[key].username);
                            console.log(data.data[key].id);

                            if (jQuery(username_id).val() === data.data[key].username) {

                                jQuery(username_id).prepend(data.data[key].username);

                                jQuery(picker_wrap).prepend('<li class="fts-insta-username-' + data.data[key].username + '"><div class="fts-insta-profile-picture-div"><img src="' + data.data[key].profile_picture + '"/><div class="fts-insta-fullname-div"><strong>Full Name:</strong> ' + data.data[key].full_name + '</div><div class="fts-insta-username-div"><strong>Username:</strong> ' + data.data[key].username + '</div><div class="fts-insta-id-div"><strong>ID:</strong> <span class="fts-insta-id-final">' + data.data[key].id + '</span></span></div></li>');


                                if (jQuery("#fts-instagram-form").is(':visible') && convert_instagram_username !== "") {
                                    jQuery('.fts-insta-username-' + data.data[key].username + '').addClass('fts-insta-id-active');
                                    jQuery('#instagram_id').val(data.data[key].id);
                                    console.log('Success');
                                    console.log('.fts-insta-username-' + data.data[key].username + '');
                                }

                                if (jQuery("#fts-combine-steams-form").is(':visible') && convert_instagram_username_combined !== "") {
                                    jQuery('.fts-insta-username-' + data.data[key].username + '').addClass('fts-insta-id-active');
                                    jQuery('#combine_instagram_name').val(data.data[key].id);


                                    console.log('Success');
                                    console.log('.fts-insta-username-' + data.data[key].username + '');

                                }

                            }
                            else {
                                jQuery(picker_wrap).append('<li class="fts-insta-username-' + data.data[key].username + '"><div class="fts-insta-profile-picture-div"><img src="' + data.data[key].profile_picture + '"/><div class="fts-insta-fullname-div"><strong>Full Name:</strong> ' + data.data[key].full_name + '</div><div class="fts-insta-username-div"><strong>Username:</strong> ' + data.data[key].username + '</div><div class="fts-insta-id-div"><strong>ID:</strong> <span class="fts-insta-id-final">' + data.data[key].id + '</span></span></div></li>');

                            }

                        });

                    });
            }

            //Append button to instagram converter input
          //  jQuery('.combine-instagram-id-option-wrap').append('<input type="button" class="feed-them-social-admin-submit-btn" value="Convert Instagram Username" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />');

            jQuery(document).ready(function () {

                // Use this to force form to be open for easier development access to shortcode options( comment out when not in use ) .fts-instagram-form
                // jQuery('.shortcode-generator-form').hide();
                // jQuery('.combine-steams-shortcode-form').fadeIn('fast');

                // This is for when you click on the returned instagram id,name list it will make that li active and append the value to the instagram ID input.
                jQuery('.fts-instagram-username-picker-wrap').on('click', 'li', function () {
                    var convert_instagram_username = jQuery("input#convert_instagram_username").val();
                    var convert_instagram_username_combined = jQuery("input#combine_convert_instagram_username").val();
                    var insta_page_id = jQuery(this).find('.fts-insta-id-final').html();
                    console.log(insta_page_id);

                    if (convert_instagram_username !== "") {
                        jQuery("#instagram_id").val(insta_page_id);
                    }
                    if (convert_instagram_username_combined !== "") {
                        jQuery("#combine_instagram_name").val(insta_page_id);
                    }

                    jQuery('.fts-instagram-username-picker-wrap li').not(this).removeClass('fts-insta-id-active');
                    jQuery(this).addClass('fts-insta-id-active');

                });

                jQuery(".toggle-custom-textarea-show").click(function () {
                    jQuery('textarea#fts-color-options-main-wrapper-css-input').slideToggle();
                    jQuery('.toggle-custom-textarea-show span').toggle();
                    jQuery('.fts-custom-css-text').toggle();
                });

                // START: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.
                jQuery("#fb_page_id").change(function () {
                    var feedID = jQuery("input#fb_page_id").val();
                    if (feedID.indexOf('facebook.com') != -1 || feedID.indexOf('facebook.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#fb_page_id').val(newfeedID);
                        return;
                    }
                });

                jQuery("#twitter_name").change(function () {
                    var feedID = jQuery("input#twitter_name").val();
                    if (feedID.indexOf('twitter.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#twitter_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#convert_instagram_username").change(function () {
                    var feedID = jQuery("input#convert_instagram_username").val();
                    if (feedID.indexOf('instagram.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#convert_instagram_username').val(newfeedID);
                        return;
                    }
                });

                jQuery("#pinterest_board_name").change(function () {
                    var feedID = jQuery("input#pinterest_board_name").val();
                    if (feedID.indexOf('pinterest.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#pinterest_board_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#pinterest_name").change(function () {
                    var feedID = jQuery("input#pinterest_name").val();
                    if (feedID.indexOf('pinterest.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#pinterest_name').val(newfeedID);
                        return;
                    }
                });

                <?php
                //show the js for the discount option under social icons on the settings page
               // if(!is_plugin_active('feed-them-premium/feed-them-premium.php')) {
               // jQuery("#discount-for-review").click(function () {
               //     jQuery('.discount-review-text').slideToggle();
               // });
                 //} ?>

                //START youtube//
                //Youtube Options
                jQuery('select#youtube-messages-selector').bind('change', function (e) {
                    if (jQuery('#youtube-messages-selector').val() == 'channelID') {
                        jQuery('.youtube_name, .youtube_playlistID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_channelID, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'userPlaylist') {
                        jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_playlistID2, .youtube_name2, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'playlistID') {
                        jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_playlistID, .youtube_channelID2, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'singleID') {
                        jQuery('.youtube_name,.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_vid_count, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').hide();
                        jQuery('.youtube_singleVideoID, .youtube_align_comments_wrap, .youtube_video_single_info_display').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'username') {
                        jQuery('.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_name, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                });


                jQuery('.youtube_first_video').hide();

                jQuery('select#youtube_columns').change(function () {
                    var youtube_columns_count = jQuery(this).val();

                    if (youtube_columns_count == '1') {
                        jQuery('.youtube_first_video').hide();
                    }
                    else {
                        jQuery('.youtube_first_video').show();
                    }
                });


                jQuery("#youtube_name").change(function () {
                    var feedID = jQuery("input#youtube_name").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_name2").change(function () {
                    var feedID = jQuery("input#youtube_name2").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name2').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_channelID").change(function () {
                    var feedID = jQuery("input#youtube_channelID").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_channelID2").change(function () {
                    var feedID = jQuery("input#youtube_channelID2").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID2').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_playlistID").change(function () {
                    var feedID = jQuery("input#youtube_playlistID").val();
                    if (feedID.indexOf('&list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_playlistID2").change(function () {
                    var feedID = jQuery("input#youtube_playlistID2").val();
                    if (feedID.indexOf('&list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID2').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_singleVideoID").change(function () {
                    var feedID = jQuery("input#youtube_singleVideoID").val();
                    if (feedID.indexOf('watch?v=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_singleVideoID').val(newfeedID);
                        return;
                    }
                });


                // END: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.



                jQuery(".copyme").focus(function () {

                    var jQuerythis = jQuery(this);
                    jQuerythis.select();
                    // Work around Chrome's little problem
                    jQuerythis.mouseup(function () {
                        // Prevent further mouseup intervention
                        jQuerythis.unbind("mouseup");
                        return false;
                    });
                });

            }); //end document ready

            // Like box/button Options Premium Content
            jQuery('#facebook-messages-selector').change(function () {
                if (jQuery("select#facebook-messages-selector").val() == "group" || jQuery("select#facebook-messages-selector").val() == "event" || jQuery("select#facebook-messages-selector").val() == "events") {
                    jQuery('.main-like-box-wrap').hide();
                    // alert(jQuery("select#facebook-messages-selector").val());
                }
                else {
                    jQuery('.main-like-box-wrap').show();
                }
            });

            // Carousel and Slideshow Premium Content
            jQuery('#facebook-messages-selector').change(function () {
                if (jQuery("select#facebook-messages-selector").val() == "album_photos" || jQuery("select#facebook-messages-selector").val() == "album_videos") {
                    jQuery('.slideshow-wrap').show();
                }
                else {
                    jQuery('.slideshow-wrap').hide();
                }
            });
            jQuery('#scrollhorz_or_carousel').change(function () {
                jQuery('.slider_carousel_wrap').toggle();
            });
            jQuery('#fts-slider').change(function () {
                jQuery('.slider_options_wrap').toggle();
            });

        </script>
    <?php }
}//END Class