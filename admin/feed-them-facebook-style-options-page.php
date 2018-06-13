<?php
namespace feedthemsocial;
/**
 * Class FTS Facebook Options Page
 *
 * @package feedthemsocial
 */
class FTS_facebook_options_page
{

    /**
     * Construct
     *
     * Facebook Style Options Page constructor.
     *
     * @since 1.9.6
     */
    function __construct() {

    }

    /**
     * Feed Them Facebook Options Page
     *
     * @since 1.9.6
     */
    function feed_them_facebook_options_page() {
        $fts_functions = new feed_them_social_functions();
        ?>

        <div class="feed-them-social-admin-wrap">
            <h1>
                <?php _e('Facebook Feed Options', 'feed-them-social'); ?>
            </h1>
            <div class="use-of-plugin">
                <?php _e('Change the language, color and more for your facebook feed using the options below.', 'feed-them-social'); ?>
            </div>
            <!-- custom option for padding -->
            <form method="post" class="fts-facebook-feed-options-form" action="options.php" id="fts-facebook-feed-options-form">
                <br/>
                <?php // get our registered settings from the fts functions
                settings_fields('fts-facebook-feed-style-options');
                //Language select
                $fb_language = get_option('fb_language', 'en_US');
                //share button
                $fb_show_follow_btn = get_option('fb_show_follow_btn');
                $fb_show_follow_btn_where = get_option('fb_show_follow_btn_where');
                $fb_show_follow_btn_profile_pic = get_option('fb_show_follow_btn_profile_pic');
                $fb_like_btn_color = get_option('fb_like_btn_color', 'light');
                $fb_hide_shared_by_etc_text = get_option('fb_hide_shared_by_etc_text');
                $fb_hide_images_in_posts = get_option('fb_hide_images_in_posts');
                $fb_hide_error_handler_message = get_option('fb_hide_error_handler_message');
                $fb_hide_no_posts_message = get_option('fb_hide_no_posts_message');
                $fb_reviews_remove_see_reviews_link = get_option('fb_reviews_remove_see_reviews_link');
                $fb_loadmore_background_color = get_option('fb_loadmore_background_color');
                $fb_loadmore_text_color = get_option('fb_loadmore_text_color');

                $fb_reviews_overall_rating_background_border_hide = get_option('fb_reviews_overall_rating_background_border_hide');

                $lang_options_array = json_decode($fts_functions->xml_json_parse('https://raw.githubusercontent.com/pennersr/django-allauth/master/allauth/socialaccount/providers/facebook/data/FacebookLocales.xml'));
                //echo'<pre>';
                // print_r($lang_options_array);
                //echo'</pre>';

                ?>
                <div id="fb-token-master-wrap" class="feed-them-social-admin-input-wrap" style="padding-bottom:0px;">
                    <div class="fts-title-description-settings-page" style="padding-top:0; border:none; margin-bottom:0px;">
                        <h3>
                            <?php _e('Facebook API Token', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('This Facebook Access Token is for Business Pages, Photos and Videos only and is simply used to display the feed. This will NOT work for personal accounts or groups. You must be an admin of the page to get your token.', 'feed-them-social'); ?>
                        <p>
                            <a href="https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/facebook-token/&state=<?php echo admin_url('admin.php?page=fts-facebook-feed-styles-submenu-page'); ?>&scope=manage_pages%2Cpublic_profile%2Cuser_friends%2Cemail" class="fts-facebook-get-access-token">Login
                                and get my Access Token</a></p>

                    </div>
                    <a href="mailto:support@slickremix.com" target="_blank" class="fts-admin-button-no-work"><?php _e('Button not working?', 'feed-them-social'); ?></a>
                    <?php
                    $test_app_token_id = get_option('fts_facebook_custom_api_token');
                    $test_app_token_id_biz = get_option('fts_facebook_custom_api_token_biz');
                    if (!empty($test_app_token_id) || !empty($test_app_token_id_biz)) {
                        $fts_fb_access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
                        $test_app_token_URL = array(
                            'app_token_id' => 'https://graph.facebook.com/debug_token?input_token=' . $test_app_token_id . '&access_token=' . $test_app_token_id
                            // 'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=705020102908771&client_secret=70166128c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjLG2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'
                        );
                        $test_app_token_URL_biz = array(
                            'app_token_id_biz' => 'https://graph.facebook.com/debug_token?input_token=' . $test_app_token_id_biz . '&access_token=' . $test_app_token_id_biz . '&'
                            // 'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=705020102908771&client_secret=70166128c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjLG2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'
                        );

                        //Test App ID
                        // Leave these for reference:
                        // App token for FTS APP2: 358962200939086|lyXQ5-zqXjvYSIgEf8mEhE9gZ_M
                        // App token for FTS APP3: 705020102908771|rdaGxW9NK2caHCtFrulCZwJNPyY
                        $test_app_token_response = $fts_functions->fts_get_feed_json($test_app_token_URL);
                        $test_app_token_response = json_decode($test_app_token_response['app_token_id']);


                        $test_app_token_response_biz = $fts_functions->fts_get_feed_json($test_app_token_URL_biz);
                        $test_app_token_response_biz = json_decode($test_app_token_response_biz['app_token_id_biz']);

                        //   echo'<pre>';
                        ///   print_r($test_app_token_response);
                        //   echo'</pre>';
                    }
                    ?>
                    <div class="clear"></div>
                    <div class="feed-them-social-admin-input-wrap fts-fb-token-wrap" id="fts-fb-token-wrap" style="margin-bottom:0px;">
                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('Access Token Required', 'feed-them-social'); ?>
                        </div>

                        <input type="text" name="fts_facebook_custom_api_token" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_facebook_custom_api_token'); ?>"/>
                        <input type="text" hidden name="fts_facebook_custom_api_token_user_id" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_id" value="<?php echo get_option('fts_facebook_custom_api_token_user_id'); ?>"/>
                        <input type="text" hidden name="fts_facebook_custom_api_token_user_name" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name" value="<?php echo get_option('fts_facebook_custom_api_token_user_name'); ?>"/>
                        <div class="clear"></div>
                        <?php if (!empty($test_app_token_response) && !empty($test_app_token_id)) {
                            if (isset($test_app_token_response->data->is_valid) || $test_app_token_response->error->message == "(#100) You must provide an app access token or a user access token that is an owner or developer of the app") {
                                $fb_id = get_option('fts_facebook_custom_api_token_user_id');
                                $fb_name = get_option('fts_facebook_custom_api_token_user_name');
                                echo '<div class="fts-successful-api-token fts-special-working-wrap">';

                                if (!empty($fb_id) && !empty($fb_name) && !empty($test_app_token_id)) {
                                    echo '<img border="0" height="50" width="50" class="fts-fb-page-thumb" src="https://graph.facebook.com/' . $fb_id . '/picture"/><h3>' . $fb_name . '</h3>';
                                }
                                echo __('Your Access Token is now working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div>';

                            }
                            if (isset($test_app_token_response->data->error->message) && !empty($test_app_token_id) || isset($test_app_token_response->error->message) && !empty($test_app_token_id) && $test_app_token_response->error->message !== "(#100) You must provide an app access token or a user access token that is an owner or developer of the app") {
                                if (isset($test_app_token_response->data->error->message)) {
                                    echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response->data->error->message . ' ' . __('. Please click the button above to retrieve a new Access Token.', 'feed-them-social') . '</div>';
                                }
                                if (isset($test_app_token_response->error->message)) {
                                    echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response->error->message . ' ' . __('. Please click the button above to retrieve a new Access Token.', 'feed-them-social') . '</div>';
                                }

                                if (isset($test_app_token_response->data->error->message) && empty($test_app_token_id) || isset($test_app_token_response->error->message) && empty($test_app_token_id)) {
                                    echo '<div class="fts-failed-api-token">' . __('To get started, please click the button above to retrieve your Access Token.', 'feed-them-social') . '</div>';
                                }
                            }

                        } else {
                            echo '<div class="fts-successful-api-token default-token">' . __('You are using our Default APP Token for testing purposes. Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a> to test your feed, but remember to add your own tokens after testing as the default token will not always work.', 'feed-them-social') . '</div>';
                        }
                        ?>
                        <div class="clear"></div>

                        <?php


                        if (isset($_GET['return_long_lived_token']) && !isset($_GET['reviews_token'])) {
                            // Echo our shortcode for the page token list with loadmore button
                            // These functions are on feed-them-functions.php
                            echo do_shortcode('[fts_fb_page_token]');

                        } ?>
                    </div>

                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <?php if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                    <!--  style="padding-top:0; border:none; margin-bottom:0px; -->
                    <div id="fts-fb-reviews-wrap" class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page" style="margin-bottom:0px;">
                            <h3>
                                <?php _e('Facebook Page Reviews Access Token', 'feed-them-social'); ?>
                            </h3>
                            <?php _e('This Facebook Access Token works for the Reviews feed only and is simply used to display the feed. You must be an admin of the page to get your token.', 'feed-them-social'); ?>
                            <p>
                                <a href="https://www.facebook.com/dialog/oauth?client_id=1123168491105924&redirect_uri=https://www.slickremix.com/facebook-token/&state=<?php echo admin_url('admin.php?page=fts-facebook-feed-styles-submenu-page'); ?>%26reviews_token=yes&scope=manage_pages%2Cpublic_profile%2Cuser_friends%2Cemail" class="fts-facebook-get-access-token">Login
                                    and get my Reviews Access Token</a></p>

                        </div>

                        <a href="mailto:support@slickremix.com" target="_blank" class="fts-admin-button-no-work"><?php _e('Button not working?', 'feed-them-social'); ?></a>
                        <div class="clear"></div>
                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('Page Reviews Access Token', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fts_facebook_custom_api_token_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_biz" value="<?php echo get_option('fts_facebook_custom_api_token_biz'); ?>"/>
                        <input type="text" hidden name="fts_facebook_custom_api_token_user_id_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_id_biz" value="<?php echo get_option('fts_facebook_custom_api_token_user_id_biz'); ?>"/>
                        <input type="text" hidden name="fts_facebook_custom_api_token_user_name_biz" class="feed-them-social-admin-input" id="fts_facebook_custom_api_token_user_name_biz" value="<?php echo get_option('fts_facebook_custom_api_token_user_name_biz'); ?>"/>
                        <div class="clear"></div>

                        <?php
                        if (!empty($test_app_token_response_biz) && !empty($test_app_token_id_biz)) {
                            $fb_name_biz = get_option('fts_facebook_custom_api_token_user_name_biz');
                            $fb_id_biz = get_option('fts_facebook_custom_api_token_user_id_biz');
                            if (isset($test_app_token_response_biz->data->is_valid) || $test_app_token_response_biz->error->message == "(#100) You must provide an app access token or a user access token that is an owner or developer of the app") {
                                echo '<div class="fts-successful-api-token fts-special-working-wrap">';


                                if (!empty($fb_id_biz) && !empty($fb_name_biz) && !empty($test_app_token_id_biz)) {
                                    echo '<img border="0" height="50" width="50" class="fts-fb-page-thumb" src="https://graph.facebook.com/' . $fb_id_biz . '/picture"/><h3>' . $fb_name_biz . '</h3>';
                                }
                                echo __('Your Page Reviews Access Token is now working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div>';

                            }

                            if (isset($test_app_token_response_biz->data->error->message) && !empty($test_app_token_id_biz) || isset($test_app_token_response_biz->error->message) && !empty($test_app_token_id) && $test_app_token_response_biz->error->message !== "(#100) You must provide an app access token or a user access token that is an owner or developer of the app") {
                                if (isset($test_app_token_response_biz->data->error->message)) {
                                    echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response_biz->data->error->message . ' ' . __('Please click the button above to retreive a new Access Token.', 'feed-them-social') . '</div>';
                                }
                                if (isset($test_app_token_response_biz->error->message) && !empty($test_app_token_id_biz) && !isset($_GET["return_long_lived_token"])) {
                                    echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response_biz->error->message . ' ' . __('Please click the button above to retreive a new Access Token.', 'feed-them-social') . '</div>';
                                }
                            }

                        }
                        if (empty($test_app_token_id_biz)) {
                            echo '<div class="fts-failed-api-token get-started-message">' . __('To get started, please click the button above to retrieve your Page Reviews Access Token.', 'feed-them-social') . '</div>';
                        }


                        if (isset($_GET['return_long_lived_token']) && isset($_GET['reviews_token'])) {
                            // Echo our shortcode for the page token list with loadmore button
                            // These functions are on feed-them-functions.php
                            echo do_shortcode('[fts_fb_page_token]');

                        } ?>

                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page">
                            <h3>
                                <?php _e('Reviews: Style and Text Options', 'feed-them-social'); ?>
                            </h3>
                            <?php _e('The styles above still apply, these are just some extra options for the Reviews List feed.', 'feed-them-social'); ?>
                        </div>
                        <div class="feed-them-social-admin-input-label fb-events-title-color-label">
                            <?php _e('Stars Background Color<br/><small>Applies to Overall Rating too.</small>', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_backg_color" class="feed-them-social-admin-input fb-reviews-backg-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-reviews-backg-color" placeholder="#4791ff" value="<?php echo get_option('fb_reviews_backg_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
                            <?php _e('Stars & Text Color<br/><small>Applies to Overall Rating too.</small>', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_text_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-reviews-text-color" placeholder="#fff" value="<?php echo get_option('fb_reviews_text_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
                            <?php _e('Text for the word "star"', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_star_language" class="feed-them-social-admin-input" id="fb_reviews_star_language" placeholder="star" value="<?php echo get_option('fb_reviews_star_language'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
                            <?php _e('Text for "See More Reviews"', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_see_more_reviews_language" class="feed-them-social-admin-input" id="fb_reviews_see_more_reviews_language" placeholder="See More Reviews" value="<?php echo get_option('fb_reviews_see_more_reviews_language'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Remove "See More Reviews" link', 'feed-them-social'); ?>
                        </div>
                        <select name="fb_reviews_remove_see_reviews_link" id="fb_reviews_remove_see_reviews_link" class="feed-them-social-admin-input">
                            <option value="">
                                <?php _e('Please Select Option', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_reviews_remove_see_reviews_link, 'yes', false) ?> value="yes">
                                <?php _e('Yes', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_reviews_remove_see_reviews_link, 'no', false) ?> value="no">
                                <?php _e('No', 'feed-them-social'); ?>
                            </option>
                        </select>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="fts-title-description-settings-page" id="overall-rating-options">
                        <h3>
                            <?php _e('Reviews: Overall Rating Style Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('These styles are for the overall rating that appear above your feed.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Hide Overall Rating Background & Border', 'feed-them-social'); ?>
                        </div>
                        <select name="fb_reviews_overall_rating_background_border_hide" id="fb_reviews_overall_rating_background_border_hide" class="feed-them-social-admin-input">
                            <option value="">
                                <?php _e('Please Select Option', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_reviews_overall_rating_background_border_hide, 'yes', false) ?> value="yes">
                                <?php _e('Yes', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_reviews_overall_rating_background_border_hide, 'no', false) ?> value="no">
                                <?php _e('No', 'feed-them-social'); ?>
                            </option>
                        </select>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating Background Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_background_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_background_color" placeholder="#fff" value="<?php echo get_option('fb_reviews_overall_rating_background_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->


                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating Text Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_text_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_text_color" placeholder="#fff" value="<?php echo get_option('fb_reviews_overall_rating_text_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating Border Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_border_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb_reviews_overall_rating_border_color" placeholder="#ddd" value="<?php echo get_option('fb_reviews_overall_rating_border_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating Background Padding', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_background_padding" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_background_padding" placeholder="10px 10px 15px 10px" value="<?php echo get_option('fb_reviews_overall_rating_background_padding'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->


                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating "of 5 stars" text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_of_5_stars_text" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_of_5_stars_text" placeholder="of 5 stars" value="<?php echo get_option('fb_reviews_overall_rating_of_5_stars_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Overall Rating "reviews" text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_reviews_overall_rating_reviews_text" class="feed-them-social-admin-input" id="fb_reviews_overall_rating_reviews_text" placeholder="reviews" value="<?php echo get_option('fb_reviews_overall_rating_reviews_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                <?php } // end if reviewsp plugin active
                ?>

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Language Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('You must have your Facebook Access Token saved above before this feature will work. This option will translate the FB Titles and Like Button or Box Text. It will not translate your actual post. To translate the Feed Them Social parts of this plugin just set your language on the <a href="options-general.php" target="_blank">wordpress settings</a> page. If would like to help translate please visit our', 'feed-them-social'); ?>
                        <a href="http://glotpress.slickremix.com/projects" target="_blank">GlottPress</a>.
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Language For Facebook Feeds', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_language" id="fb-lang-btn" class="feed-them-social-admin-input">
                        <option value="en_US">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <?php
                        foreach ($lang_options_array->locale as $language) {
                            echo '<option ' . selected($fb_language, $language->codes->code->standard->representation, true) . ' value="' . $language->codes->code->standard->representation . '">' . $language->englishName . '</option>';
                        }
                        ?>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap" style="display: none;">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Offset Limit', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('<strong style="color:red">WARNING, PLEASE READ CAREFULLY!</strong> DO NOT use this field to set your facebook posts. If you are getting the message "Please go to the Facebook Options page of our plugin and look for the "Change Limit" option and add the number 7 or more." then adjust the number below so posts will show in your feed. Generally adding at least <strong>7</strong> is a good idea if you are getting that notice. This is only for Pages and Groups. We filter certain posts that do not have a story or message or if the shared content is not available via the API.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label">
                        <?php _e('Offset Quantity', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_count_offset" class="feed-them-social-admin-input" id="fb_count_offset" value="<?php echo get_option('fb_count_offset'); ?>" />
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Hide Notice on Front End', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_hide_no_posts_message" id="fb_hide_no_posts_message" class="feed-them-social-admin-input">
                        <option value="">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_no_posts_message, 'yes', false) ?> value="yes">
                            <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_no_posts_message, 'no', false) ?> value="no">
                            <?php _e('No', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Like Button or Box Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('This will only show on regular feeds not combined feeds.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Show Follow Button', 'feed-them-social'); ?>

                    </div>
                    <select name="fb_show_follow_btn" id="fb-show-follow-btn" class="feed-them-social-admin-input">
                        <option>
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn, 'dont-display', false) ?> value="dont-display">
                            <?php _e('Don\'t Display a Button', 'feed-them-social'); ?>
                        </option>
                        <optgroup label="Like Box">
                            <option <?php echo selected($fb_show_follow_btn, 'like-box', false) ?> value="like-box">
                                <?php _e('Like Box', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_show_follow_btn, 'like-box-faces', false) ?> value="like-box-faces">
                                <?php _e('Like Box with Faces', 'feed-them-social'); ?>
                            </option>
                        </optgroup>
                        <optgroup label="Like Button">
                            <option <?php echo selected($fb_show_follow_btn, 'like-button', false) ?> value="like-button">
                                <?php _e('Like Button', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_show_follow_btn, 'like-button-share', false) ?> value="like-button-share">
                                <?php _e('Like Button and Share Button', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_show_follow_btn, 'like-button-faces', false) ?> value="like-button-faces">
                                <?php _e('Like Button with Faces', 'feed-them-social'); ?>
                            </option>
                            <option <?php echo selected($fb_show_follow_btn, 'like-button-share-faces', false) ?> value="like-button-share-faces">
                                <?php _e('Like Button and Share Button with Faces', 'feed-them-social'); ?>
                            </option>
                        </optgroup>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap" style="display:none">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Show Profile Icon next to social option above', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_show_follow_like_box_cover" id="fb-show-follow-like-box-cover" class="feed-them-social-admin-input">
                        <option>
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn_profile_pic, 'fb_like_box_cover-yes', false) ?> value="fb_like_box_cover-yes">
                            <?php _e('Display Cover Photo in Like Box', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn_profile_pic, 'fb_like_box_cover-no', false) ?> value="fb_like_box_cover-no">
                            <?php _e('Hide Cover Photo in Like Box', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Like Button Color', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_like_btn_color" id="fb-like-btn-color" class="feed-them-social-admin-input">
                        <option value="light">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_like_btn_color, 'light', false) ?> value="light">
                            <?php _e('Light', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_like_btn_color, 'dark', false) ?> value="dark">
                            <?php _e('Dark', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Placement of the Button(s)', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_show_follow_btn_where" id="fb-show-follow-btn-where" class="feed-them-social-admin-input">
                        <option value="">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn_where, 'fb-like-top-above-title', false) ?> value="fb-like-top-above-title">
                            <?php _e('Show Top of Feed Above Title', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn_where, 'fb-like-top-below-title', false) ?> value="fb-like-top-below-title">
                            <?php _e('Show Top of Feed Below Title', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_show_follow_btn_where, 'fb-like-below', false) ?> value="fb-like-below">
                            <?php _e('Show Botton of Feed', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap" style="display: none">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Facebook APP ID<br/><small>Not required if you used the "Login and get my Access Token" button, otherwise View Step 3 to <a href="http://www.slickremix.com/docs/create-facebook-app-id-or-user-token" target="_blank">get APP ID</a>.</small>', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_app_ID" class="feed-them-social-admin-input" id="fb-app-ID" value="<?php // echo get_option('fb_app_ID'); ?>" placeholder="Not Required for New Users"/>
                    <div class="clear"></div>
                </div>
                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page" style="margin-top:0;">
                        <h3>
                            <?php _e('Global Facebook Style Options', 'feed-them-social'); ?>
                        </h3>
                    </div>

                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Text after your FB name <br/><small>ie* Shared by or New Photo Added etc.</small>', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_hide_shared_by_etc_text" id="fb_hide_shared_by_etc_text" class="feed-them-social-admin-input">
                        <option value="">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_shared_by_etc_text, 'no', false) ?> value="no">
                            <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_shared_by_etc_text, 'yes', false) ?> value="yes">
                            <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
                        <?php _e('Hide Images in Posts', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_hide_images_in_posts" id="fb_hide_images_in_posts" class="feed-them-social-admin-input">
                        <option value="">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_images_in_posts, 'no', false) ?> value="no">
                            <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_images_in_posts, 'yes', false) ?> value="yes">
                            <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-text-color-label">
                        <?php _e('Max-width for Images & Videos', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_max_image_width" class="feed-them-social-admin-input" placeholder="500px" value="<?php echo get_option('fb_max_image_width'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-text-color-label">
                        <?php _e('Feed Header Extra Text Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_header_extra_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo get_option('fb_header_extra_text_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-text-size-label">
                        <?php _e('Feed Description Text Size', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_text_size" class="feed-them-social-admin-input fb-text-size-input" id="fb-text-size-input" placeholder="12px" value="<?php echo get_option('fb_text_size'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-text-color-label">
                        <?php _e('Feed Text Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo get_option('fb_text_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-link-color-label">
                        <?php _e('Feed Link Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_link_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-input" placeholder="#222" value="<?php echo get_option('fb_link_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-link-color-hover-label">
                        <?php _e('Feed Link Color Hover', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_link_color_hover" class="feed-them-social-admin-input fb-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('fb_link_color_hover'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-feed-width-label">
                        <?php _e('Feed Width', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_feed_width" class="feed-them-social-admin-input fb-feed-width-input" id="fb-feed-width-input" placeholder="500px" value="<?php echo get_option('fb_feed_width'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-feed-margin-label">
                        <?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_feed_margin" class="feed-them-social-admin-input fb-feed-margin-input" id="fb-feed-margin-input" placeholder="10px" value="<?php echo get_option('fb_feed_margin'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-feed-padding-label">
                        <?php _e('Feed Padding', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_feed_padding" class="feed-them-social-admin-input fb-feed-padding-input" id="fb-feed-padding-input" placeholder="10px" value="<?php echo get_option('fb_feed_padding'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) { ?>
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-post-background-color-label">
                            <?php _e('Post Background Color<br/><small>Only works with show_media=top</small>', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_post_background_color" class="feed-them-social-admin-input fb-post-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_post_background_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->
                <?php } ?>

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-feed-background-color-label">
                        <?php _e('Feed Background Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_feed_background_color" class="feed-them-social-admin-input fb-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_feed_background_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                        <?php _e('Border Bottom Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fb_border_bottom_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page">
                            <h3>
                                <?php _e('Grid Styles', 'feed-them-social'); ?>
                            </h3>
                        </div>
                        <div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label">
                            <?php _e('Posts Background Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_grid_posts_background_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-grid-border-bottom-color-label">
                            <?php _e('Border Bottom Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_grid_border_bottom_color" class="feed-them-social-admin-input fb-grid-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fb_grid_border_bottom_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="fts-title-description-settings-page">
                            <h3>
                                <?php _e('Load More Button Styles & Options', 'feed-them-social'); ?>
                            </h3>
                        </div>
                        <div class="feed-them-social-admin-input-label fts-fb-loadmore-background-color-label">
                            <?php _e('Button Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-loadmore-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_loadmore_background_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-fb-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                            <?php _e('Text Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-loadmore-text-color-input" placeholder="#ddd" value="<?php echo get_option('fb_loadmore_text_color'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-fb-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"Load More" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_load_more_text" class="feed-them-social-admin-input" id="fb_load_more_text" placeholder="Load More" value="<?php echo get_option('fb_load_more_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Posts" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_no_more_posts_text" class="feed-them-social-admin-input" id="fb_no_more_posts_text" placeholder="No More Posts" value="<?php echo get_option('fb_no_more_posts_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Photos" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_no_more_photos_text" class="feed-them-social-admin-input" id="fb_no_more_photos_text" placeholder="No More Photos" value="<?php echo get_option('fb_no_more_photos_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Videos" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_no_more_videos_text" class="feed-them-social-admin-input" id="fb_no_more_videos_text" placeholder="No More Videos" value="<?php echo get_option('fb_no_more_videos_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->

                <?php } ?>

                <?php if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Reviews" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="fb_no_more_reviews_text" class="feed-them-social-admin-input" id="fb_no_more_reviews_text" placeholder="No More Reviews" value="<?php echo get_option('fb_no_more_reviews_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-facebook-feed-styles-input-wrap-->
                <?php } ?>

                <div class="feed-them-social-admin-input-wrap" style="display: none !important;">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Event Style Options', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('The styles above still apply, these are just some extra options for the Event List feed.', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label fb-events-title-color-label">
                        <?php _e('Events Feed: Title Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_events_title_color" class="feed-them-social-admin-input fb-events-title-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-events-title-color-input" placeholder="#ddd" value="<?php echo get_option('fb_events_title_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fb-events-title-size-label">
                        <?php _e('Events Feed: Title Size', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_events_title_size" class="feed-them-social-admin-input fb-events-title-size" id="fb-events-title-color-input" placeholder="20px" value="<?php echo get_option('fb_events_title_size'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
                        <?php _e('Events Feed: Map Link Color', 'feed-them-social'); ?>
                    </div>
                    <input type="text" name="fb_events_map_link_color" class="feed-them-social-admin-input fb-events-map-link-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-events-map-link-color-input" placeholder="#ddd" value="<?php echo get_option('fb_events_map_link_color'); ?>"/>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->


                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('Facebook Error Message', 'feed-them-social'); ?>
                        </h3>
                        <?php _e('If your feed is displaying a notice or error message at times you can utilize this option to hide them from displaying. Make sure and delete the <a href="admin.php?page=feed-them-settings-page&tab=global_options">Cache</a> to see the change. <p><small>NOTE: This does not hide any php warnings that may come up. To remove those go to the wp-config.php file on root of your WordPress install and set the wp_debug option to FALSE. Having that option set to TRUE is really only necessary when developing.</small></p>', 'feed-them-social'); ?>
                    </div>
                    <div class="feed-them-social-admin-input-label fb-error-handler-label">
                        <?php _e('Hide Error Handler Message', 'feed-them-social'); ?>
                    </div>
                    <select name="fb_hide_error_handler_message" id="fb_hide_error_handler_message" class="feed-them-social-admin-input">
                        <option value="">
                            <?php _e('Please Select Option', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_error_handler_message, 'no', false) ?> value="no">
                            <?php _e('No', 'feed-them-social'); ?>
                        </option>
                        <option <?php echo selected($fb_hide_error_handler_message, 'yes', false) ?> value="yes">
                            <?php _e('Yes', 'feed-them-social'); ?>
                        </option>
                    </select>
                    <div class="clear"></div>
                </div>
                <!--/fts-facebook-feed-styles-input-wrap-->

                <div class="clear"></div>
                <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>"/>
            </form>
            <div class="clear"></div>
            <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a></div>
        <!--/feed-them-social-admin-wrap-->
    <?php }
}//END Class