<?php
namespace feedthemsocial;
/**
 * Class FTS Youtube Options Page
 *
 * @package feedthemsocial
 */
class FTS_youtube_options_page
{
    /**
     * Construct
     *
     * Youtube Style Options Page constructor.
     *
     * @since 1.9.6
     */
    function __construct()
    {
    }

    /**
     * Feed Them Youtube Option Page
     *
     * @since 1.9.6
     */
    function feed_them_youtube_options_page()
    {
        $fts_functions = new feed_them_social_functions();
        $fts_youtube_show_follow_btn = get_option('youtube_show_follow_btn');
        $fts_youtube_show_follow_btn_where = get_option('youtube_show_follow_btn_where');

        ?>
        <div class="feed-them-social-admin-wrap">
            <h1>
                <?php _e('Feed Options', 'feed-them-social'); ?>
            </h1>
            <div class="use-of-plugin">
                <?php _e('Add a follow button and position it using the options below. This option will not work for combined feeds.', 'feed-them-social'); ?>
            </div>

            <!-- custom option for padding -->
            <form method="post" class="fts-youtube-feed-options-form" action="options.php">
                <?php settings_fields('fts-youtube-feed-style-options'); ?>


                <?php

                $youtubeAPIkey = get_option('youtube_custom_api_token');
                $youtubeAccessToken = get_option('youtube_custom_access_token');
                if(isset($youtubeAPIkey) && $youtubeAPIkey !==''){
                    $youtubeAPIkeyORtoken = 'key='.$youtubeAPIkey.'';
                }
                elseif (isset($youtubeAPIkey) && $youtubeAPIkey =='' && isset($youtubeAccessToken) && $youtubeAccessToken !==''  ){
                    $youtubeAPIkeyORtoken = 'access_token='.$youtubeAccessToken.'';
                }
                else {
                    $youtubeAPIkeyORtoken = '';
                }

                $youtube_userID_data = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=slickremix&' . $youtubeAPIkeyORtoken;
                //Get Data for Youtube
                $response = wp_remote_fopen($youtube_userID_data);
                //Error Check
                $test_app_token_response = json_decode($response);

                //   echo'<pre>';
                //   print_r($test_app_token_response);
                //   echo'</pre>';

                $refresh_token = isset($_GET['refresh_token']) ? $_GET['refresh_token'] : 'notset';
                $access_token = isset($_GET['access_token']) ? $_GET['access_token'] : '';
                $expires_in = isset($_GET['expires_in']) ? $_GET['expires_in'] : '';

                ?>

                <div class="feed-them-social-admin-input-wrap" style="padding-top: 0px">
                    <div class="fts-title-description-settings-page">
                        <h3>
                            <?php _e('YouTube API Key', 'feed-them-social'); ?>
                        </h3>
                        <p><?php _e('This is required to make the feed work. Simply click the button below and it will connect to your YouTube account to get an access token and access token secret, and it will return it in the input below. Then just click the save button and you will now be able to generate your YouTube feed.', 'feed-them-social'); ?>
                        </p>
                        <p>
                            <a href="https://www.slickremix.com/youtube-token/?redirect_url=<?php echo admin_url('admin.php?page=fts-youtube-feed-styles-submenu-page');?>" class="fts-youtube-get-access-token">Log in and get my Access Token (API key)</a>
                        </p>

                    </div>


                    <a href="http://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank" class="fts-admin-button-no-work">Button not working?</a>
                </div>


                <div class="fts-clear"></div>
                <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0px;">

                    <?php $extra_keys = get_option('youtube_custom_api_token') == '' ? 'style="display:none"' : '';
                          $extra_keys_no = get_option('youtube_custom_api_token');
                            if(!empty($extra_keys_no)) {
                                $extra_keys_no =  'style="display:none"';
                            }
                    ?>

                    <div class="fts-twitter-add-all-keys-click-option"><label for="fts-custom-tokens-twitter"><input type="checkbox" id="fts-custom-tokens-twitter" name="fts_twitter_custom_tokens" value="1" <?php echo checked('1', $extra_keys == ''); ?>> Add your own API Key?</label></div>




                    <div class="fts-clear"></div>

                    <div class="twitter-extra-keys" <?php echo $extra_keys ?>> <div class="twitter-extra-keys-text" <?php echo $extra_keys_no ?>><?php _e('Learn how to manualy create your own YouTube API Key', 'feed-them-social'); ?> <a href="http://www.slickremix.com/docs/get-api-key-for-youtube/" target="_blank" ><?php _e('here', 'feed-them-social'); ?></a>.</div>

                        <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                            <?php _e('API Key Required', 'feed-them-social'); ?>
                        </div>

                        <input type="text" name="youtube_custom_api_token" class="feed-them-social-admin-input" id="youtube_custom_api_token" value="<?php echo get_option('youtube_custom_api_token'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                </div>



                <div class="hide-button-tokens-options" <?php echo $extra_keys_no ?> >
                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Refresh Token', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_custom_refresh_token" class="feed-them-social-admin-input" id="youtube_custom_refresh_token" value="<?php echo get_option('youtube_custom_refresh_token'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <div class="feed-them-social-admin-input-wrap"  style="margin-bottom:0px;">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Access Token', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_custom_access_token" class="feed-them-social-admin-input" id="youtube_custom_access_token" value="<?php echo get_option('youtube_custom_access_token'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <?php
                    // Add no to show the expiration time and js that runs it below
                    $dev_mode = 'no'; ?>
                    <div class="feed-them-social-admin-input-wrap fts-exp-time-wrapper"  style="margin-top:10px;<?php if($dev_mode !== 'yes'){?>display:none<?php } ?>">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('Expiration Time for Access Token', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_custom_token_exp_time" class="feed-them-social-admin-input" id="youtube_custom_token_exp_time" value="<?php echo get_option('youtube_custom_token_exp_time'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                </div>

                <div class="feed-them-social-admin-input-wrap  fts-youtube-last-row" style="margin-top:0px;">
                    <script>
                        jQuery(document).ready(function ($) {
                            jQuery('#fts-custom-tokens-twitter').click(function(){
                                jQuery(".twitter-extra-keys, .hide-button-tokens-options").toggle();
                            });
                            <?php

                            if($refresh_token !== 'notset'){
                            update_option('youtube_custom_token_exp_time',  strtotime("+" . $expires_in . " seconds"));
                            ?>
                            $('#youtube_custom_refresh_token, #youtube_custom_access_token, #youtube_custom_token_exp_time').val('');
                            $('#youtube_custom_refresh_token').val($('#youtube_custom_refresh_token').val() + '<?php echo $refresh_token ?>');
                            $('#youtube_custom_access_token').val($('#youtube_custom_access_token').val() + '<?php echo $access_token ?>');
                            $('#youtube_custom_token_exp_time').val($('#youtube_custom_token_exp_time').val() + '<?php echo strtotime("+" . $expires_in . " seconds") ?>');
                            <?php } ?>

                        });
                    </script>
                    <?php
                    if(isset($_GET['refresh_token']) && isset($_GET['access_token']) && isset($_GET['expires_in'])) {
                        // START AJAX TO SAVE TOKEN TO DB RIGHT AWAY SO WE CAN DO OUR NEXT SET OF CHECKS
                        // new token action
                        $fts_functions->feed_them_youtube_refresh_token();
                    }

                    $expiration_time = get_option('youtube_custom_token_exp_time');
                    // Give the access token a 5 minute buffer (300 seconds) before getting a new one.
                    $expiration_time = $expiration_time - 300;
                    //Test Liner
                    // $expiration_time = '1';
                    if (time() < $expiration_time && empty($youtubeAPIkey) && $dev_mode == 'yes') { ?>
                        <script>
                            // Set the time * 1000 because js uses milliseconds not seconds and that is what youtube gives us is a 3600 seconds of time
                            var countDownDate = new Date(<?php echo $expiration_time ?> * 1000);

                            // Update the count down every 1 second
                            var x = setInterval(function() {

                                // Get todays date and time
                                var now = new Date().getTime();

                                // Find the distance between now an the count down date
                                var distance = countDownDate - now;

                                // Time calculations for days, hours, minutes and seconds
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Display the result in the element with id="demo"
                                jQuery('<span id="fts-timer"></span>').insertBefore('.hide-button-tokens-options .fts-exp-time-wrapper .fts-clear');
                                document.getElementById("fts-timer").innerHTML = minutes + "m " + seconds + "s ";

                                // If the count down is finished, write some text
                                if (distance < 0) {
                                    clearInterval(x); jQuery('.fts-success').fadeIn();
                                    document.getElementById("fts-timer").innerHTML = "Expired, refresh page to get new token (developer use only)";
                                }
                            }, 1000);
                        </script>
                        <?php
                        //  echo '<p>We save the Expiration time which is the strtotime("+"  3600 " seconds") so when the actual time() is comapred if the time has lapsed we rerun our token request</p>';
                        //  echo '<strong>Exp Time:</strong> '.$expiration_time;
                        //  echo '<br/>';
                        //  echo '<strong>Current Time:</strong> '.time();
                        //  echo '<br/>';
                        //  print '<strong>Current Token:</strong> ' .get_option('youtube_custom_access_token', true);
                    }
                    elseif($youtubeAPIkey == '' && $youtubeAccessToken !== '' && time() > $expiration_time) {
                        // refresh token action
                        $fts_functions->feed_them_youtube_refresh_token();
                    }

                    foreach ($test_app_token_response as $userID) {
                        if (!isset($userID->error->errors[0]->reason) && !empty($youtubeAPIkey)) {
                            $typeOfKey = __('API key', 'feed-them-social');
                        }
                        elseif(!isset($userID->error->errors[0]->reason) && !empty($youtubeAccessToken)){
                            $typeOfKey = __('Access Token', 'feed-them-social');
                        }

                        // Error Check
                        if (!isset($test_app_token_response->error->errors[0]->reason) && !empty($youtubeAPIkey) || !isset($test_app_token_response->error->errors[0]->reason) && !empty($youtubeAccessToken) && empty($youtubeAPIkey)) {
                            echo '<div class="fts-successful-api-token">' . __('Your '.$typeOfKey.' is working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div>';
                        }
                        elseif (isset($userID->error->errors[0]->reason) && !empty($youtubeAPIkey) || !isset($userID->error->errors[0]->reason) && !empty($youtubeAccessToken)) {
                            echo '<div class="fts-failed-api-token">' . __('This '.$typeOfKey.' does not appear to be valid. YouTube responded with: ', 'feed-them-social') . ' ' . $userID->errors[0]->reason . '</div>';
                        }
                        if ($youtubeAPIkey == '' && $youtubeAccessToken == '') {
                            echo '<div class="fts-failed-api-token">' . __('You must click the button above or register for an API token to use the YouTube feed.', 'feed-them-social') . '</div>';
                        }
                        break;
                    }
                    ?>


                    <div class="fts-clear"></div>
                </div>

                <div class="feed-them-social-admin-input-wrap">
                    <div class="fts-title-description-settings-page">
                        <h3><?php _e('Follow Button Options', 'feed-them-social'); ?></h3>
                    </div>
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Show Follow Button', 'feed-them-social'); ?></div>

                    <select name="youtube_show_follow_btn" id="youtube-show-follow-btn" class="feed-them-social-admin-input">
                        <option
                        '<?php echo selected($fts_youtube_show_follow_btn, 'no', false) ?>'
                        value="no"><?php _e('No', 'feed-them-social'); ?></option>
                        <option
                        '<?php echo selected($fts_youtube_show_follow_btn, 'yes', false) ?>'
                        value="yes"><?php _e('Yes', 'feed-them-social'); ?></option>
                    </select>

                    <div class="fts-clear"></div>
                </div><!--/fts-twitter-feed-styles-input-wrap-->

                <div class="feed-them-social-admin-input-wrap">
                    <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Placement of the Buttons', 'feed-them-social'); ?></div>

                    <select name="youtube_show_follow_btn_where" id="youtube-show-follow-btn-where" class="feed-them-social-admin-input">
                        <option><?php _e('Please Select Option', 'feed-them-social'); ?></option>
                        <option
                        '<?php echo selected($fts_youtube_show_follow_btn_where, 'youtube-follow-above', false) ?>'
                        value="youtube-follow-above"><?php _e('Show Above Feed', 'feed-them-social'); ?></option>
                        <option
                        '<?php echo selected($fts_youtube_show_follow_btn_where, 'youtube-follow-below', false) ?>'
                        value="youtube-follow-below"><?php _e('Show Below Feed', 'feed-them-social'); ?></option>
                    </select>

                    <div class="fts-clear"></div>
                </div><!--/fts-twitter-feed-styles-input-wrap-->



                <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

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
                        <input type="text" name="youtube_loadmore_background_color" class="feed-them-social-admin-input fb-loadmore-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-background-color-input" placeholder="#ddd" value="<?php echo get_option('youtube_loadmore_background_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
                            <?php _e('Text Color', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_loadmore_text_color" class="feed-them-social-admin-input fb-loadmore-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="twitter-loadmore-text-color-input" placeholder="#ddd" value="<?php echo get_option('youtube_loadmore_text_color'); ?>"/>
                        <div class="fts-clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"Load More" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_load_more_text" class="feed-them-social-admin-input" id="youtube_load_more_text" placeholder="Load More" value="<?php echo get_option('youtube_load_more_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <div class="feed-them-social-admin-input-wrap">
                        <div class="feed-them-social-admin-input-label">
                            <?php _e('"No More Videos" Text', 'feed-them-social'); ?>
                        </div>
                        <input type="text" name="youtube_no_more_videos_text" class="feed-them-social-admin-input" id="youtube_no_more_videos_text" placeholder="No More Videos" value="<?php echo get_option('youtube_no_more_videos_text'); ?>"/>
                        <div class="clear"></div>
                    </div>
                    <!--/fts-twitter-feed-styles-input-wrap-->

                    <?php } // END premium ?>

                    <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>"/>

            </form>
            <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a></div>
        <!--/feed-them-social-admin-wrap-->
    <?php }
}//END Class