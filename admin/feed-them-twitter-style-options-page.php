<?php

namespace feedthemsocial;
/**
 * Class FTS Twitter Options Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_twitter_options_page
{

    /**
     * Construct
     *
     * Twitter Style Options Page constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
    }

    /**
     * Feed Them Twitter Options Page
     *
     * @since 1.9.6
     */
    function feed_them_twitter_options_page() {
        $fts_functions = new feed_them_social_functions();
        ?>
        <div class="feed-them-social-admin-wrap">
            <h1>
                <?php _e('Twitter Feed Options', 'feed-them-social'); ?>
            </h1>
            <div class="use-of-plugin">
                <?php _e('Change the color of your twitter feed and more using the options below.', 'feed-them-social'); ?>
            </div>
            <!-- custom option for padding -->
            <form method="post" class="fts-twitter-feed-options-form" action="options.php">

                <?php // get our registered settings from the fts functions
                settings_fields('fts-twitter-feed-style-options');

                $twitter_full_width = get_option('twitter_full_width');
                $twitter_allow_videos = get_option('twitter_allow_videos');
                $twitter_allow_shortlink_conversion = get_option('twitter_allow_shortlink_conversion');
                $twitter_show_follow_btn = get_option('twitter_show_follow_btn');
                $twitter_show_follow_count = get_option('twitter_show_follow_count');
                $twitter_show_follow_btn_where = get_option('twitter_show_follow_btn_where');
                $fts_twitter_hide_images_in_posts = get_option('fts_twitter_hide_images_in_posts');



                $fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
                $fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');

                $test_fts_twitter_custom_consumer_key = '35mom6axGlf60ppHJYz1dsShc';
                $test_fts_twitter_custom_consumer_secret = '7c2TJvUT7lS2EkCULpK6RGHrgXN1BA4oUi396pQEdRj3OEq5QQ';

                $fts_twitter_custom_consumer_key = isset($fts_twitter_custom_consumer_key) && $fts_twitter_custom_consumer_key !== '' ? $fts_twitter_custom_consumer_key : $test_fts_twitter_custom_consumer_key;
                $fts_twitter_custom_consumer_secret =  isset($fts_twitter_custom_consumer_secret) && $fts_twitter_custom_consumer_secret !== '' ? $fts_twitter_custom_consumer_secret : $test_fts_twitter_custom_consumer_secret;

                $fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
                $fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');

                if (isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page') {

                include(WP_CONTENT_DIR . '/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php');

                $test_connection = new TwitterOAuthFTS(
                //Consumer Key
                $fts_twitter_custom_consumer_key,
                //Consumer Secret
                $fts_twitter_custom_consumer_secret,
                //Access Token
                $fts_twitter_custom_access_token,
                //Access Token Secret
                $fts_twitter_custom_access_token_secret
                );

                    $fetchedTweets = $test_connection->get(
                        'statuses/user_timeline',
                        array(
                            'screen_name' => 'twitter',
                            'count' => '1',
                        )
                    );

                // TESTING AREA
                //    $fetchedTweets = $test_connection->get(
                //        'statuses/user_timeline',
                //        array(
                //            'tweet_mode' => 'extended',
                //            'screen_name' => 'slickremix',
                //            'count' => '1',
                //        )
                //    );

                //    echo '<pre>';
                        //    print_r($fetchedTweets);
                        //    echo '</pre>';
                // END TESTING
                }
                ?>
                <div class="feed-them-social-admin-input-wrap" style="padding-top: 0px">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Twitter API Token', 'feed-them-social'); ?>
                        </h3>
                        <p><?php _e('This is required to make the feed work. Simply click the button below and it will connect to your Twitter account to get an access token and access token secret, and it will return it in the input below. Then just click the save button and you will now be able to generate your Twitter feed.', 'feed-them-social'); ?>
                        </p>
                        <p>
                            <a href="https://www.slickremix.com/get-twitter-token/?redirect_url=<?php echo admin_url('admin.php?page=fts-twitter-feed-styles-submenu-page');?>" class="fts-twitter-get-access-token">Log in and get my Access Tokens</a>

                        </p>

                    </div>


                    <a href="http://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/" target="_blank" class="fts-admin-button-no-work">Button not working?</a>
                </div>




                <div class="fts-clear"></div>
                <div class="feed-them-social-admin-input-wrap">
                    <?php $extra_keys = get_option('fts_twitter_custom_consumer_key') == '' && get_option('fts_twitter_custom_consumer_secret') == '' ? 'style="display:none"' : ''; ?>

                    <div class="fts-twitter-add-all-keys-click-option"><label for="fts-custom-tokens-twitter"><input type="checkbox" id="fts-custom-tokens-twitter" name="fts_twitter_custom_tokens" value="1" <?php echo checked('1', $extra_keys == ''); ?>> Add your own tokens?</label></div>

                    <div class="twitter-extra-keys" <?php echo $extra_keys ?> >
                        <div class="twitter-extra-keys-text"><?php _e('Learn how to manualy create the Consumer Key/Secret and the Access Token/Secret', 'feed-them-social'); ?> <a href="http://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/" target="_blank" ><?php _e('here', 'feed-them-social'); ?></a>.</div>
                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                                <?php _e('Consumer Key (API Key)', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="fts_twitter_custom_consumer_key" class="feed-them-social-admin-input" id="fts_twitter_custom_consumer_key" value="<?php echo get_option('fts_twitter_custom_consumer_key'); ?>"/>
                            <div class="fts-clear"></div>
                        </div>
                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                                <?php _e('Consumer Secret (API Secret)', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="fts_twitter_custom_consumer_secret" class="feed-them-social-admin-input" id="fts_twitter_custom_consumer_secret" value="<?php echo get_option('fts_twitter_custom_consumer_secret'); ?>"/>
                            <div class="fts-clear"></div>
                        </div>
                    </div>

                    <script>
                        jQuery(document).ready(function ($) {

                            jQuery('#fts-custom-tokens-twitter').click(function(){
                                jQuery(".twitter-extra-keys").toggle();
                            });

                            <?php

                            $oath_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : 'notset';
                            $oauth_token_secret = isset($_GET['oauth_token_secret']) ? $_GET['oauth_token_secret'] : 'notset';
                            if($oath_token !== 'notset' && $oauth_token_secret !== 'notset'){
                                ?>
                                $('#fts_twitter_custom_access_token').val('');
                                $('#fts_twitter_custom_access_token_secret').val('');
                                $('#fts_twitter_custom_access_token').val($('#fts_twitter_custom_access_token').val() + '<?php echo $oath_token ?>');
                                $('#fts_twitter_custom_access_token_secret').val($('#fts_twitter_custom_access_token_secret').val() + '<?php echo $oauth_token_secret ?>');
                            <?php } ?>

                        });
                    </script>
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('Access Token', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fts_twitter_custom_access_token" class="feed-them-social-admin-input" id="fts_twitter_custom_access_token" value="<?php echo get_option('fts_twitter_custom_access_token'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('Access Token Secret', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fts_twitter_custom_access_token_secret" class="feed-them-social-admin-input" id="fts_twitter_custom_access_token_secret" value="<?php echo get_option('fts_twitter_custom_access_token_secret'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>


                    <div class="feed-them-social-admin-input-wrap"> <?php
                        // && !empty($test_fts_twitter_custom_access_token) && !empty($test_fts_twitter_custom_access_token_secret)
                        if (!empty($fts_twitter_custom_access_token_secret) && !empty($fts_twitter_custom_access_token_secret)) {
                            if ($test_connection->http_code != 200 || isset($fetchedTweets->errors)) {
                                echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . '';
                                foreach ($fetchedTweets->errors as $error) {
                                    echo ' <strong>' . $error->message . '. </strong> ' . __('You may have entered in the Access information incorrectly please re-enter and try again.', 'feed-them-social') . '';
                                }
                                echo '</div>';
                            } else {
                                echo '<div class="fts-successful-api-token">' . __('Your access token is working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div>';
                            }
                        } else {
                            echo '<div class="fts-successful-api-token">' . __('You are using our Default Access tokens for testing purposes. Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a> to test your feed, but remember to add your own tokens after testing as the default tokens will not always work.', 'feed-them-social') . '</div>';
                        }


                        ?>
                    </div>

                    <div class="fts-clear"></div>
                </div>















                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page" >
                        <h3>
                            <?php _e('Follow Button Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('This will only show on regular feeds not combined feeds.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Show Follow Count', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_show_follow_count" id="twitter-show-follow-count" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($twitter_show_follow_count, 'no', false) ?>' value="no">
                        <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_show_follow_count, 'yes', false) ?>' value="yes">
                        <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Show Follow Button', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_show_follow_btn" id="twitter-show-follow-btn" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($twitter_show_follow_btn, 'no', false) ?>' value="no">
                        <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_show_follow_btn, 'yes', false) ?>' value="yes">
                        <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Placement of Follow Button', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_show_follow_btn_where" id="twitter-show-follow-btn-where" class="feed-them-social-admin-input">
                        <option>
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_show_follow_btn_where, 'twitter-follow-above', false) ?>'
                        value="twitter-follow-above">
                        <?php _e('Show Above Feed', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_show_follow_btn_where, 'twitter-follow-below', false) ?>'
                        value="twitter-follow-below">
                        <?php _e('Show Below Feed', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Video Player Options', 'feed-them-social'); ?>
                        </h3>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Show videos', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_allow_videos" id="twitter-allow-videos" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($twitter_allow_videos, 'no', false) ?>' value="no">
                        <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_allow_videos, 'yes', false) ?>' value="yes">
                        <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap" style="display: none">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Convert shortlinks for video<br/><small>Like bitly etc. May slow load time slightly</small>', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_allow_shortlink_conversion" id="twitter-allow-shortlink-conversion" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($twitter_allow_shortlink_conversion, 'no', false) ?>' value="no">
                        <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_allow_shortlink_conversion, 'yes', false) ?>' value="yes">
                        <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Profile Photo Option', 'feed-them-social'); ?>
                        </h3>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Hide Profile Photo', 'feed-them-social'); ?>
                    </div>
                    <select name="twitter_full_width" id="twitter-full-width" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($twitter_full_width, 'no', false) ?>' value="no">
                        <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($twitter_full_width, 'yes', false) ?>' value="yes">
                        <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Style Options', 'feed-them-social'); ?>
                        </h3>
                    </div>

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                            <?php _e('Hide Images in Posts', 'feed-them-social'); ?>
                        </div>
                        <select name="fts_twitter_hide_images_in_posts" id="fts_twitter_hide_images_in_posts" class="feed-them-social-admin-input">
                            <option value="">
                                <?php _e('Please Select Option', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fts_twitter_hide_images_in_posts, 'no', false) ?> value="no">
                                <?php _e('No', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fts_twitter_hide_images_in_posts, 'yes', false) ?> value="yes">
                                <?php _e('Yes', 'feed-them-social'); ?>
                            </option>
                        </select>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Max-width for Feed Images', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_max_image_width" class="feed-them-social-admin-input" placeholder="500px" value="<?php echo get_option('twitter_max_image_width'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-text-size-label">
                            <?php _e('Feed Description Text Size', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_text_size" class="feed-them-social-admin-input twitter-text-size-input" id="twitter-text-size-input" placeholder="12px" value="<?php echo get_option('twitter_text_size'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                            <?php _e('Feed Text Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_text_color" class="feed-them-social-admin-input twitter-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-text-color-input" placeholder="#222" value="<?php echo get_option('twitter_text_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-link-color-label">
                            <?php _e('Feed Link Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_link_color" class="feed-them-social-admin-input twitter-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-link-color-input" placeholder="#222" value="<?php echo get_option('twitter_link_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-link-color-hover-label">
                            <?php _e('Feed Link Color Hover', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_link_color_hover" class="feed-them-social-admin-input twitter-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('twitter_link_color_hover'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-feed-width-label">
                            <?php _e('Feed Width', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_feed_width" class="feed-them-social-admin-input twitter-feed-width-input" id="twitter-feed-width-input" placeholder="500px" value="<?php echo get_option('twitter_feed_width'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-feed-margin-label">
                            <?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_feed_margin" class="feed-them-social-admin-input twitter-feed-margin-input" id="twitter-feed-margin-input" placeholder="10px" value="<?php echo get_option('twitter_feed_margin'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-feed-padding-label">
                            <?php _e('Feed Padding', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_feed_padding" class="feed-them-social-admin-input twitter-feed-padding-input" id="twitter-feed-padding-input" placeholder="10px" value="<?php echo get_option('twitter_feed_padding'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-feed-background-color-label">
                            <?php _e('Feed Background Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_feed_background_color" class="feed-them-social-admin-input twitter-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_feed_background_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('Feed Border Bottom Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_border_bottom_color" class="feed-them-social-admin-input twitter-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_border_bottom_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->
                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page">
                            <h3>
                                <?php _e('Grid Styles', 'feed-them-social'); ?>
                            </h3>
                        </div>
                        <div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label">
                            <?php _e('Posts Background Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_grid_posts_background_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                            <?php _e('Border Bottom Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="twitter_grid_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_grid_border_bottom_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page">
                            <h3>
                                <?php _e('Load More Button Styles & Options', 'feed-them-social'); ?>
                            </h3>
                        </div>
                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
                                <?php _e('Button Color', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="twitter_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-background-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_loadmore_background_color'); ?>"/>
                            <div class="fts-clear"></div>
                        </div>
                        <!--/fts-twitter-feed-styles-input-wrap-->

                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                                <?php _e('Text Color', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="twitter_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-text-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_loadmore_text_color'); ?>"/>
                            <div class="fts-clear"></div>
                        </div>
                        <!--/fts-twitter-feed-styles-input-wrap-->

                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label">
                                <?php _e('"Load More" Text', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="twitter_load_more_text" class="feed-them-social-admin-input" id="twitter_load_more_text" placeholder="Load More" value="<?php echo get_option('twitter_load_more_text'); ?>"/>
                            <div class="clear"></div>
                        </div>
                        <!--/fts-twitter-feed-styles-input-wrap-->

                        <div class="feed-them-social-admin-input-wrap">
                            <div class="feed-them-social-admin-input-label">
                                <?php _e('"No More Tweets" Text', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="twitter_no_more_tweets_text" class="feed-them-social-admin-input" id="twitter_no_more_tweets_text" placeholder="No More Photos" value="<?php echo get_option('twitter_no_more_tweets_text'); ?>"/>
                            <div class="clear"></div>
                        </div>
                        <!--/fts-twitter-feed-styles-input-wrap-->
                            
                        <div class="feed-them-social-admin-input-wrap" style="display: none;">
                            <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                                <?php _e('Fix Post Count<br/><small>Type 2 or 3 if your feed is skipping posts when using the loadmore option.</small>', 'feed-them-social'); ?>
                            </div>
                            <input type="text" name="twitter_replies_offset" class="feed-them-social-admin-input" id="twitter-replies-offset" placeholder="1" value="<?php echo get_option('twitter_replies_offset'); ?>"/>
                            <div class="fts-clear"></div>
                        </div>
                        <!--/fts-twitter-feed-styles-input-wrap-->

                        <?php } ?>



                            <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>"/>

            </form>
            <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
            <div class="fts-clear"></div>
        </div>
        <!--/feed-them-social-admin-wrap-->
        <div class="clear"></div>
    <?php }
}//END Class