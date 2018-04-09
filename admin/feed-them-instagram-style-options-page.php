<?php

namespace feedthemsocial;
/**
 * Class FTS Instagram Options Page
 *
 * @package feedthemsocial
 */
class FTS_instagram_options_page
{

    /**
     * Construct
     *
     * Instagram Style Options Page constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
    }

    /**
     * Feed Them Instagram Options Page
     *
     * @since 1.9.6
     */
    function feed_them_instagram_options_page() {
        $fts_functions = new feed_them_social_functions();
        $fts_instagram_access_token = get_option('fts_instagram_custom_api_token');
        $fts_instagram_custom_id = get_option('fts_instagram_custom_id');
        $fts_instagram_show_follow_btn = get_option('instagram_show_follow_btn');
        $fts_instagram_show_follow_btn_where = get_option('instagram_show_follow_btn_where');

        ?>
        <div class="feed-them-social-admin-wrap">
            <h1>
                <?php _e('Instagram Feed Options', 'feed-them-social'); ?>
            </h1>
            <div class="use-of-plugin">
                <?php _e('Get your Access Token and add a follow button and position it using the options below.', 'feed-them-social'); ?>
            </div>
            <!-- custom option for padding -->
            <form method="post" class="fts-facebook-feed-options-form" action="options.php">


                <div class="feed-them-social-admin-input-wrap"  style="padding-top:0px; ">
                    <div class="fts-title-description-settings-page">
                        <?php // get our registered settings from the fts functions
                        settings_fields('fts-instagram-feed-style-options'); ?>
                        <h3>
                            <?php _e('Instagram API Token', 'feed-them-social'); ?>
                        </h3>
                        <?php

                        $insta_url = 'https://api.instagram.com/v1/tags/slickremix/media/recent/?access_token=' . $fts_instagram_access_token;
                        //Get Data for Instagram
                        $response = wp_remote_fopen($insta_url);
                        //Error Check
                        $test_app_token_response = json_decode($response);

                        //   echo '<pre>';
                        //   print_r(json_decode($response));
                        //   echo '</pre>';
                        ?>
                        <p>
                            <?php _e('This is required to make the feed work. Just click the button below and it will connect to your instagram to get an access token, then it will return it in the input below. Then just click the save button and you will now be able to generate your Instagram feed. If the button is not working for you and can always manually create an Access Token <a href="http://www.slickremix.com/docs/how-to-create-instagram-access-token/" target="_blank">here</a>.', 'feed-them-social'); ?>
                        </p>
                        <p>
                            <a href="https://instagram.com/oauth/authorize/?client_id=da06fb6699f1497bb0d5d4234a50da75&redirect_uri=http://www.slickremix.com/instagram-token-plugin/?return_uri=<?php echo admin_url('admin.php?page=fts-instagram-feed-styles-submenu-page'); ?>&response_type=token&scope=public_content" class="fts-instagram-get-access-token">
                                <?php _e('Log in and get my Access Token'); ?>
                            </a></p>
                    </div>

                    <div class="fts-clear"></div>


                    <div class="feed-them-social-admin-input-wrap" style="margin-bottom: 0px">
                        <div class="feed-them-social-admin-input-label fts-instagram-border-bottom-color-label">
                            <?php _e('Instagram ID', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fts_instagram_custom_id" class="feed-them-social-admin-input" id="fts_instagram_custom_id" value="<?php echo $fts_instagram_custom_id ?>"/>
                        <div class="fts-clear"></div>
                    </div>


                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-instagram-border-bottom-color-label">
                            <?php _e('Access Token Required', 'feed-them-social'); ?>
                        </div>
                        <script>
                            jQuery(document).ready(function ($) {
                                function getQueryString(Param) {
                                    return decodeURI(
                                        (RegExp('[#|&]' + Param + '=' + '(.+?)(&|$)').exec(location.hash) || [, null])[1]
                                    );
                                }

                                if (window.location.hash) {

                                    $('select').find('option[value=5]').attr('selected','selected');

                                    $('#fts_instagram_custom_api_token').val('');
                                    $('#fts_instagram_custom_api_token').val($('#fts_instagram_custom_api_token').val() + getQueryString('access_token'));


                                    $('#fts_instagram_custom_id').val('');
                                    var str =  getQueryString('access_token');
                                    $('#fts_instagram_custom_id').val($('#fts_instagram_custom_id').val() + str.split('.', 1));

                                }
                            });
                        </script>
                        <input type="text" name="fts_instagram_custom_api_token" class="feed-them-social-admin-input" id="fts_instagram_custom_api_token" value="<?php echo $fts_instagram_access_token ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <?php
                    // Error Check
                    // if the combined streams plugin is active we won't allow the settings page link to open up the Instagram Feed, instead we'll remove the #feed_type=instagram and just let the user manually select the combined streams or single instagram feed.
                    if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
                        $custom_instagram_link_hash = '';
                    }
                    else {
                        $custom_instagram_link_hash = '#feed_type=instagram';
                    }
                    if (!isset($test_app_token_response->meta->error_message) && !empty($fts_instagram_access_token) || isset($test_app_token_response->meta->error_message) && $test_app_token_response->meta->error_message == 'This client has not been approved to access this resource.') {
                        echo '<div class="fts-successful-api-token">' . __('Your access token is working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page'.$custom_instagram_link_hash .'">settings page</a>.', 'feed-them-social') . '</div>';
                    } elseif (isset($test_app_token_response->meta->error_message) && !empty($fts_instagram_access_token)) {
                        echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response->meta->error_message . '</div>';
                    }
                    if (empty($fts_instagram_access_token)) {
                        echo '<div class="fts-failed-api-token">' . __('You are required to get an access token to view your photos. Click Save all Changes after getting your Access Token.', 'feed-them-social') . '</div>';
                    }
                    ?>
                    <div class="fts-clear"></div>
                </div>





                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page" >
                        <h3>
                            <?php _e('Follow Button Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('This will only show on regular feeds not combined feeds.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-instagram-text-color-label">
                        <?php _e('Show Follow Button', 'feed-them-social'); ?>
                    </div>
                    <select name="instagram_show_follow_btn" id="instagram-show-follow-btn" class="feed-them-social-admin-input">
                        <option <?php echo selected($fts_instagram_show_follow_btn, 'no', false) ?> value="no">
                            <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fts_instagram_show_follow_btn, 'yes', false) ?> value="yes">
                            <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-instagram-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-instagram-text-color-label">
                        <?php _e('Placement of the Buttons', 'feed-them-social'); ?>
                    </div>
                    <select name="instagram_show_follow_btn_where" id="instagram-show-follow-btn-where" class="feed-them-social-admin-input">
                        <option>
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($fts_instagram_show_follow_btn_where, 'instagram-follow-above', false) ?>'
                        value="instagram-follow-above">
                        <?php _e('Show Above Feed', 'feed-them-social'); ?>
                        </option>
                        <option
                        '<?php echo selected($fts_instagram_show_follow_btn_where, 'instagram-follow-below', false) ?>'
                        value="instagram-follow-below">
                        <?php _e('Show Below Feed', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="fts-clear"></div>
                </div>
                <!--/fts-instagram-feed-styles-input-wrap-->
                <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Load More Button Styles & Options', 'feed-them-social'); ?>
                        </h3>
                    </div>
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
                            <?php _e('Load More Button Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="instagram_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="instagram-loadmore-background-color-input" placeholder="#ddd" value="<?php echo get_option('instagram_loadmore_background_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-instagram-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                            <?php _e('Load More Button Text Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="instagram_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="instagram-loadmore-text-color-input" placeholder="#ddd" value="<?php echo get_option('instagram_loadmore_text_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-instagram-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"Load More" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="instagram_load_more_text" class="feed-them-social-admin-input" id="instagram_load_more_text" placeholder="Load More" value="<?php echo get_option('instagram_load_more_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-instagram-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Photos" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="instagram_no_more_photos_text" class="feed-them-social-admin-input" id="instagram_no_more_photos_text" placeholder="No More Photos" value="<?php echo get_option('instagram_no_more_photos_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-instagram-feed-styles-input-wrap-->
                    <?php } ?>

                        <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>"/>

            </form>
            <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a></div>
        <!--/feed-them-social-admin-wrap-->

    <?php }
}//END Class