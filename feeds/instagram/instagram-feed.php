<?php
namespace feedthemsocial;
/**
 * Class FTS_Instagram_Feed
 * @package feedthemsocial
 */
class FTS_Instagram_Feed extends feed_them_social_functions
{
    /**
     * Construct
     *
     * Instagram Feed constructor.
     *
     * @since 1.9.6
     */
    function __construct()
    {
        add_shortcode('fts_instagram', array($this, 'fts_instagram_func'));
        add_action('wp_enqueue_scripts', array($this, 'fts_instagram_head'));
    }

    function convert_instagram_description_links($bio) {
        //Create links from @mentions and regular links.
        $bio = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $bio );
        $bio = preg_replace('/[#]+([0-9\p{L}]+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank">$0</a>', $bio );
        $bio = preg_replace('/[@]+([0-9\p{L}]+)/u', '<a href="https://www.instagram.com/$1" target="_blank">@$1</a>', $bio );
        return $bio;
    }

    function convert_instagram_links($instagram_caption_a_title) {
        //Create links from @mentions and regular links.
        $instagram_caption_a_title = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $instagram_caption_a_title );
        $instagram_caption = preg_replace('/[#]+([0-9\p{L}]+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank">$0</a>', $instagram_caption_a_title );
        $instagram_caption = preg_replace('/[@]+([0-9\p{L}]+)/u', '<a href="https://www.instagram.com/$1" target="_blank">@$1</a>', $instagram_caption );
        return $instagram_caption;
    }

    /**
     * FTS Instagram Head
     *
     * @since 1.9.6
     */
    function fts_instagram_head()
    {
        wp_enqueue_style('fts-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));
    }

    function fts_instagram_likes_count($post_data) {
        // These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
            $instagram_likes = isset($post_data->likes->count) ? $post_data->likes->count : "";
            // here we add a , for all numbers below 9,999
            if (isset($instagram_likes) && $instagram_likes <= 9999) {
                $instagram_likes = number_format($instagram_likes);
            }
            // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions
            if (isset($instagram_likes) && $instagram_likes >= 1000000) {
                $instagram_likes = round(($instagram_likes / 1000000), 1) . 'm';
            }
            // here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands
            if (isset($instagram_likes) && $instagram_likes >= 10000) {
                $instagram_likes = round(($instagram_likes / 1000), 1) . 'k';
            }
        return  $instagram_likes;
    }

    function fts_instagram_comments_count($post_data) {
            $instagram_comments = isset($post_data->comments->count) ? $post_data->comments->count : "";
            // here we add a , for all numbers below 9,999
            if (isset($instagram_comments) && $instagram_comments <= 9999) {
                $instagram_comments = number_format($instagram_comments);
            }
            // here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions
            if (isset($instagram_comments) && $instagram_comments >= 1000000) {
                $instagram_comments = round(($instagram_comments / 1000000), 1) . 'm';
            }
            // here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands
            if (isset($instagram_comments) && $instagram_comments >= 10000) {
                $instagram_comments = round(($instagram_comments / 1000), 1) . 'k';
            }
        return  $instagram_comments;
    }

    function fts_instagram_likes_comments_wrap($post_data) {
        return  '<ul class="heart-comments-wrap"><li class="instagram-image-likes">'.$this->fts_instagram_likes_count($post_data).'</li><li class="instagram-image-comments">'.$this->fts_instagram_comments_count($post_data).'</li></ul>';
    }

    function fts_instagram_image_link($post_data) {
        $instagram_lowRez_url = isset($post_data->images->standard_resolution->url) ? $post_data->images->standard_resolution->url : "";
        return  $instagram_lowRez_url;
    }

    function fts_instagram_video_link($post_data) {
        $instagram_video_standard_resolution = isset($post_data->videos->standard_resolution->url) ? $post_data->videos->standard_resolution->url : "";
        return  $instagram_video_standard_resolution;
    }

    function fts_instagram_description($post_data) {
        $instagram_caption_a_title = isset($post_data->caption->text) ? $post_data->caption->text : "";
        $instagram_caption_a_title = htmlspecialchars($instagram_caption_a_title);
        $instagram_caption = $this->convert_instagram_links($instagram_caption_a_title);
        return  $instagram_caption;
    }

    function fts_view_on_instagram_url($post_data) {
        $instagram_post_url = isset($post_data->link) ? $post_data->link : "";
        return  $instagram_post_url;
    }

    function fts_view_on_instagram_link($post_data) {
        return  '<a href="'.$this->fts_view_on_instagram_url($post_data).'" class="fts-view-on-instagram-link" target="_blank">'. __('View on Instagram', 'feed-them-social').'</a>';
    }

    function fts_instagram_popup_description($post_data) {
        return  '<div class="fts-instagram-caption"><div class="fts-instagram-caption-content"><p>'.$this->fts_instagram_description($post_data) . '</p></div>'.$this->fts_view_on_instagram_link($post_data).'</div>';
    }

    /**
     * FTS Instagram Function
     *
     * Display Instagram Feed.
     *
     * @param $atts
     * @return mixed
     * @since 1.9.6
     */
    function fts_instagram_func($atts)
    {

        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/instagram/instagram-feed.php';
            if (isset($popup) && $popup == 'yes') {
                // it's ok if these styles & scripts load at the bottom of the page
                $fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
                if (isset($fts_fix_magnific) && $fts_fix_magnific !== '1') {
                    wp_enqueue_style('fts-popup', plugins_url('feed-them-social/feeds/css/magnific-popup.css'));
                }
                wp_enqueue_script('fts-popup-js', plugins_url('feed-them-social/feeds/js/magnific-popup.js'));
            }
        } else {
            extract(shortcode_atts(array(
                'instagram_id' => '',
                'type' => '',
                'pics_count' => '',
                'super_gallery' => '',
                'image_size' => '',
                'icon_size' => '',
                'space_between_photos' => '',
                'hide_date_likes_comments' => '',
                'center_container' => '',
                'height' => '',
                'width' => '',
                // user profile options
                'profile_wrap' => '',
                'profile_photo' => '',
                'profile_stats' => '',
                'profile_name' => '',
                'profile_description' => '',
                'columns' => '',
                'force_columns' => '',
                'access_token' => '',
            ), $atts));
            if ($pics_count == NULL)
                $pics_count = '6';
        }

        if(!is_plugin_active('feed-them-premium/feed-them-premium.php') && $pics_count > '6'){
            $pics_count = '6';
        }

        if(!isset($_GET['load_more_ajaxing'])) {
            $pics_count = $pics_count;
        }
        else {
            $pics_count = '10';
        }

        wp_enqueue_script('fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array('jquery'));
        $instagram_data_array = array();

        $fts_instagram_access_token = get_option('fts_instagram_custom_api_token');
        $fts_instagram_show_follow_btn = get_option('instagram_show_follow_btn');
        $fts_instagram_show_follow_btn_where = get_option('instagram_show_follow_btn_where');
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')){
            $instagram_load_more_text = get_option('instagram_load_more_text') ? get_option('instagram_load_more_text') : __('Load More', 'feed-them-social');
            $instagram_no_more_photos_text = get_option('instagram_no_more_photos_text') ? get_option('instagram_no_more_photos_text') : __('No More Photos', 'feed-them-social');
        }

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10) . '_' . $type);
            //Create Dynamic Class Name
            $fts_dynamic_class_name = '';
            if (isset($_REQUEST['fts_dynamic_name'])) {
                $fts_dynamic_class_name = 'feed_dynamic_class' . $_REQUEST['fts_dynamic_name'];
            }
        }

        ob_start();
          // Omitting for Time Being since Instagram API changes as of April 4th, 2018
          //  if (empty($fts_instagram_access_token)) {
          //      $fts_instagram_tokens_array = array('267791236.df31d88.30e266dda9f84e9f97d9e603f41aaf9e', '267791236.14c1243.a5268d6ed4cf4d2187b0e98b365443af', '267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8', '258559306.502d2c4.c5ff817f173547d89477a2bd2e6047f9');
          //      $fts_instagram_access_token = $fts_instagram_tokens_array[array_rand($fts_instagram_tokens_array, 1)];
          //  } else {
          //      $fts_instagram_access_token = $fts_instagram_access_token;
          //  }

        // New method since Instagram API changes as of April 4th, 2018
        if($access_token == '') {
            $fts_instagram_access_token = $fts_instagram_access_token;
        }
        else {
            $fts_instagram_access_token = $access_token;
        }

        //URL to get Feeds
        if ($type == 'hashtag') {
            $instagram_data_array['data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://api.instagram.com/v1/tags/' . $instagram_id . '/media/recent/?access_token=' . $fts_instagram_access_token;
        }
        elseif($type == 'location') {
                    $instagram_data_array['data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://api.instagram.com/v1/locations/' . $instagram_id . '/media/recent/?count=' . $pics_count . '&access_token=' . $fts_instagram_access_token;
                }
                else {
            // $instagram_data_array['data'] = 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
            $instagram_data_array['data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://api.instagram.com/v1/users/' . $instagram_id . '/media/recent/?count=' . $pics_count . '&access_token=' . $fts_instagram_access_token;
        }

        $instagram_data_array['user_info'] = 'https://api.instagram.com/v1/users/' . $instagram_id . '?access_token=' . $fts_instagram_access_token;

        $cache = 'instagram_cache_' . $instagram_id . '_num' . $pics_count . '';

        $response = $this->fts_get_feed_json($instagram_data_array);

        //Error Check
        $error_check = json_decode($response['data']);
        if (isset($error_check->meta->error_message)) {
            return $error_check->meta->error_message;
        }

        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($cache)) && !isset($_GET['load_more_ajaxing'])) {
            $response = $this->fts_get_feed_cache($cache);
            $insta_data = json_decode($response['data']);
        } else {
            $insta_data = json_decode($response['data']);
            //if Error DON'T Cache
            if (!isset($error_check->meta->error_message) && !isset($_GET['load_more_ajaxing'])) {
                $this->fts_create_feed_cache($cache, $response);
            }
        }

        $instagram_user_info = !empty($response['user_info']) ? json_decode($response['user_info']) : '';
         //URL to get Feeds
        if ($type !== 'hashtag' && $type !== 'location') {
            $username = $instagram_user_info->data->username;
            $bio = $instagram_user_info->data->bio;
            $profile_picture = $instagram_user_info->data->profile_picture;
            $full_name = $instagram_user_info->data->full_name;
            $website = $instagram_user_info->data->website;
        }


         // echo '<pre>';
         // print_r($instagram_user_info);
         // echo '</pre>';

            //->pagination->next_url
        //  echo '<pre>';
        //  print_r($insta_data);
        //  echo '</pre>';

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            //******************
            // SOCIAL BUTTON
            //******************
             if (isset($profile_picture) && $profile_wrap == "yes") { ?>
                        <div class="fts-profile-wrap">
                              <?php  if (isset($profile_photo) && $profile_photo == "yes") { ?>
                                     <div class="fts-profile-pic"><a href="https://www.instagram.com/<?php print $username; ?>"><img src="<?php print $profile_picture; ?>" title="<?php print $username; ?>"/></a></div>
                              <?php }

                              if (isset($profile_name) && $profile_name == "yes") { ?>
                                     <div class="fts-profile-name-wrap">

                                        <div class="fts-isnta-full-name"><?php print $full_name; ?></div>
                                            <?php
                                            if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn == 'yes' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_user_info->data->username)) {
                                                 echo '<div class="fts-follow-header-wrap">';
                                                 $this->social_follow_button('instagram', $instagram_user_info->data->username);
                                                 echo '</div>';
                                            }
                                            ?>
                                        </div>
                              <?php }

                                    if (isset($profile_stats) && $profile_stats == "yes") {
                                     // These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
                                    $number_posted_pics = isset($instagram_user_info->data->counts->media) ? $instagram_user_info->data->counts->media : "";
                                    // here we add a , for all numbers below 9,999
                                    if (isset($number_posted_pics) && $number_posted_pics <= 9999) {
                                        $number_posted_pics = number_format($number_posted_pics);
                                    }
                                    // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions
                                    if (isset($number_posted_pics) && $number_posted_pics >= 1000000) {
                                        $number_posted_pics = round(($number_posted_pics / 1000000), 1) . 'm';
                                    }
                                    // here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands
                                    if (isset($number_posted_pics) && $number_posted_pics >= 10000) {
                                        $number_posted_pics = round(($number_posted_pics / 1000), 1) . 'k';
                                    }

                                    $number_followed_by = $instagram_user_info->data->counts->followed_by;
                                    // here we add a , for all numbers below 9,999
                                    if (isset($number_followed_by) && $number_followed_by <= 9999) {
                                        $number_followed_by = number_format($number_followed_by);
                                    }
                                    // here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions
                                    if (isset($number_followed_by) && $number_followed_by >= 1000000) {
                                        $number_followed_by = round(($number_followed_by / 1000000), 1) . 'm';
                                    }
                                    // here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands
                                    if (isset($number_followed_by) && $number_followed_by >= 10000) {
                                        $number_followed_by = round(($number_followed_by / 1000), 1) . 'k';
                                    }

                                     $number_follows = $instagram_user_info->data->counts->follows;
                                    // here we add a , for all numbers below 9,999
                                    if (isset($number_follows) && $number_follows <= 9999) {
                                        $number_follows = number_format($number_follows);
                                    }
                                    // here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions
                                    if (isset($number_follows) && $number_follows >= 1000000) {
                                        $number_follows = round(($number_follows / 1000000), 1) . 'm';
                                    }
                                    // here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands
                                    if (isset($number_follows) && $number_follows >= 10000) {
                                        $number_follows = round(($number_follows / 1000), 1) . 'k';
                                    }
                                    ?>
                                    <div class="fts-profile-stats">
                                        <div class="fts-insta-posts"><span><?php print $number_posted_pics; ?></span> <?php _e('posts', 'feed-them-social'); ?></div>
                                        <div class="fts-insta-followers"><span><?php print $number_followed_by; ?></span> <?php _e('followers', 'feed-them-social'); ?></div>
                                        <div class="fts-insta-following"><span><?php print $number_follows; ?></span>  <?php _e('following', 'feed-them-social'); ?></div>
                                    </div>
                            <?php }

                            if (isset($profile_description) && $profile_description == "yes") { ?>

                            <div class="fts-profile-description"><?php print $this->convert_instagram_description_links($bio); ?> <a href="<?php print $website ?>"><?php print $website ?></a></div>

                            <?php } ?>

                            <div class="fts-clear"></div>

                        </div>
                    <?php }
            elseif (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn == 'yes' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_user_info->data->username)) {
                echo '<div class="instagram-social-btn-top">';
                $this->social_follow_button('instagram', $instagram_user_info->data->username);
                echo '</div>';
            }

            if (isset($scrollMore) && $scrollMore == 'autoscroll' || isset($height) && $height !== '') { ?>
                <div class="fts-instagram-scrollable <?php echo $fts_dynamic_class_name ?>instagram" style="overflow:auto;<?php if ($width !== '') { ?> max-width:<?php echo $width.';'; }  if ($height !== '') { ?> height:<?php echo $height; } ?>">
            <?php }
            if (isset($super_gallery) && $super_gallery == 'yes') {
                 $columns = isset($columns) ? 'data-ftsi-columns="'.$columns.'" ' : '';
                 $force_columns = isset($force_columns) ? 'data-ftsi-force-columns="'.$force_columns.'" ' : '';
                 ?><div <?php if ($width !== '') { ?>style="max-width:<?php echo $width.';"' ; }  print $columns . $force_columns ?>data-ftsi-margin="<?php print $space_between_photos ?>" data-ftsi-width="<?php print $image_size ?>" class="<?php print 'fts-instagram-inline-block-centered'; print ' ' .$fts_dynamic_class_name; if (isset($popup) && $popup == 'yes') {print ' popup-gallery';}print '">';
            }
           else { ?>
                <div <?php if ($width !== '') { ?> style="max-width:<?php echo $width.'; margin:auto;" ' ; }?>class="fts-instagram <?php if (isset($popup) && $popup == 'yes') {print 'popup-gallery ';} print $fts_dynamic_class_name .'">';
                }
            $set_zero = 0;
        } // END Make sure it's not ajaxing


        if (!isset($insta_data->data)) {
            if (!function_exists('curl_init')) {
                echo 'cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.</div>';
            } else {
                echo 'To see the Instagram feed you need to add your own API Token to the Instagram Options page of our plugin.</div>';
            }
        }
      //   echo '<pre>';
      //   print_r($insta_data);
      //   echo '</pre>';

        foreach ($insta_data->data as $post_data) {
            if (isset($set_zero) && $set_zero == $pics_count)
                break;

            //Create Instagram Variables

                    // tied to date function
                    $feed_type = 'instagram';
                    $times = $post_data->created_time;
                    // call our function to get the date
                    $instagram_date = $this->fts_custom_date($times, $feed_type);




        if ($type == 'hashtag' || $type == 'location') {
            $username = isset($post_data->user->username) ? $post_data->user->username : "";
            $profile_picture = isset($post_data->user->profile_picture) ? $post_data->user->profile_picture : "";
            $full_name = isset($post_data->user->full_name) ? $post_data->user->full_name : "";
            $instagram_username = $username;
        }
        else {

             $instagram_username = $instagram_user_info->data->username;
        }

            $instagram_caption_a_title = isset($post_data->caption->text) ? $post_data->caption->text : "";
            $instagram_caption_a_title = htmlspecialchars($instagram_caption_a_title);
            $instagram_caption = $this->convert_instagram_links($instagram_caption_a_title);



            $instagram_thumb_url = isset($post_data->images->thumbnail->url) ? $post_data->images->thumbnail->url : "";
            $instagram_lowRez_url = isset($post_data->images->standard_resolution->url) ? $post_data->images->standard_resolution->url : "";
            $instagram_video_standard_resolution = isset($post_data->videos->standard_resolution->url) ? $post_data->videos->standard_resolution->url : "";

            if (isset($_SERVER["HTTPS"])) {
                $instagram_thumb_url = str_replace('http://', 'https://', $instagram_thumb_url);
                $instagram_lowRez_url = str_replace('http://', 'https://', $instagram_lowRez_url);
            }



            $fts_dynamic_vid_name_string = trim($this->rand_string(10) . '_' . $type);





            // Super Gallery If statement
            if (isset($super_gallery) && $super_gallery == 'yes') {?>
                <div class='slicker-instagram-placeholder fts-instagram-wrapper' style='background-image:url(<?php print $this->fts_instagram_image_link($post_data); ?>);'>
              <?php

                if(is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == "yes"){ ?>
                <div class="fts-instagram-popup-profile-wrap">
                    <div class="fts-profile-pic">
                        <a href="https://www.instagram.com/<?php print $username; ?>"><?php if($type == 'user') {?><img src="<?php print $profile_picture; ?>" title="<?php print $username; ?>"/><?php } else { ?><span class="fts-instagram-icon" style="height:40px; width:40px; line-height:40px; font-size:40px;"></span><?php } ?></a>
                    </div>
                                <div class="fts-profile-name-wrap">

                                        <div class="fts-isnta-full-name">
                                            <a href="https://www.instagram.com/<?php print $username; ?>" style="color: #000;"><?php if($type == 'user') { print $full_name; } else { print $username; } ?></a>
                                        </div>
                                            <?php
                                            if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn == 'yes' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_username)) {
                                                 echo '<div class="fts-follow-header-wrap">';
                                                 $this->social_follow_button('instagram', $instagram_username);
                                                 echo '</div>';
                                            }
                                            ?>
                                        </div>
                                </div>
                <?php

                         print $this->fts_instagram_popup_description($post_data);
                     }

                      ?>
                    <a href='<?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == 'yes' && ($post_data->type == 'image' || $post_data->type == 'carousel')) {
                        print $this->fts_instagram_image_link($post_data);
                    }
                    elseif (is_plugin_active('feed-them-premium/feed-them-premium.php')  && isset($popup) && $popup == 'yes' && $post_data->type == 'video') {
                        print $this->fts_instagram_video_link($post_data);
                    }
                    else {
                        print $this->fts_view_on_instagram_url($post_data);
                    } ?>' title='<?php print $instagram_caption_a_title ?>' target="_blank" class='fts-instagram-link-target fts-slicker-backg <?php if($post_data->type == 'video' && isset($popup) && $popup == 'yes'){ ?>fts-instagram-video-link<?php } else{ ?>fts-instagram-img-link<?php } ?>' style="height:<?php print $icon_size ?> !important; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"><span class="fts-instagram-icon" style="height:<?php print $icon_size ?>; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"></span></a>


                    <?php if ($hide_date_likes_comments == 'no') { ?>
                        <div class='slicker-date'><div class="fts-insta-date-popup-grab"><?php print $instagram_date ?></div></div>
                    <?php } ?>
                    <div class='slicker-instaG-backg-link'>

                        <div class='slicker-instaG-photoshadow'></div>
                    </div>
                    <?php if ($hide_date_likes_comments == 'no') { ?>
                        <div class="fts-insta-likes-comments-grab-popup">
                                <?php
                                $fts_share_option = $this->fts_share_option($this->fts_view_on_instagram_url($post_data),$this->fts_instagram_description($post_data));
                                print $fts_share_option;
                                ?>
                                <div class="fts-instagram-reply-wrap-left">
                        <ul class='slicker-heart-comments-wrap'>
                            <li class='slicker-instagram-image-likes'><?php print $this->fts_instagram_likes_count($post_data); ?> </li>
                            <li class='slicker-instagram-image-comments'>
                                <span class="fts-comment-instagram"></span> <?php print $this->fts_instagram_comments_count($post_data); ?></li>
                        </ul>
                        </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } // Classic Gallery If statement
            else { ?>
                <div class='instagram-placeholder fts-instagram-wrapper' style='width:150px;'><?php if (isset($popup) && $popup == 'yes') {
                        print '<div class="fts-backg"></div>';
                    } else { ?>
                        <a class='fts-backg' target='_blank' href='<?php print $this->fts_view_on_instagram_url($post_data); ?>'></a>  <?php }; ?>
                    <div class='date slicker-date'><div class="fts-insta-date-popup-grab"><?php print $instagram_date ?></div></div>


                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == 'yes') { ?>


                            <div class="fts-instagram-popup-profile-wrap"><div class="fts-profile-pic">
                                <a href="https://www.instagram.com/<?php print $username; ?>"><img src="<?php print $profile_picture; ?>" title="<?php print $username; ?>"/></a>
                            </div>

                                <div class="fts-profile-name-wrap">

                                        <div class="fts-isnta-full-name"><?php print $full_name; ?></div>
                                            <?php
                                            if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn == 'yes' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_user_info->data->username)) {
                                                 echo '<div class="fts-follow-header-wrap">';
                                                 $this->social_follow_button('instagram', $instagram_user_info->data->username);
                                                 echo '</div>';
                                            }
                                            ?>
                                        </div>
                                </div>

                    <?php
                    // caption for our popup
                    print $this->fts_instagram_popup_description($post_data); ?>
                    <?php } ?>

                    <a href="<?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == 'yes' && $post_data->type == 'image') {
                        print $this->fts_instagram_image_link($post_data);
                    }
                    elseif (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == 'yes' && $post_data->type == 'video') {
                        print $this->fts_instagram_video_link($post_data);
                    } else {
                        print $this->fts_view_on_instagram_url($post_data);
                    } ?>" class='fts-instagram-link-target instaG-backg-link <?php if($post_data->type == 'video'){ ?>fts-instagram-video-link<?php } else{ ?>fts-instagram-img-link<?php } ?>' target='_blank' title='<?php print $instagram_caption_a_title ?>'>
                        <img src="<?php print $instagram_thumb_url ?>" class="instagram-image"/>
                        <div class='instaG-photoshadow'></div>
                    </a>
                     <div class="fts-insta-likes-comments-grab-popup">
                       <?php print $this->fts_instagram_likes_comments_wrap($post_data); ?>
                    </div>
                </div>
            <?php }
            if (isset($set_zero)) {
                $set_zero++;
            }
        }
        //******************
        //Load More BUTTON Start
        //******************
        $next_url = isset($insta_data->pagination->next_url) ? $insta_data->pagination->next_url : '';
        // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of pics/videos
        $_REQUEST['next_url'] = isset($loadmore_count) && $loadmore_count !== '' ? str_replace("count=$pics_count","count=$loadmore_count",$next_url) : $next_url; ?>
        <script>var nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?>= "<?php echo $_REQUEST['next_url']; ?>";</script>
        <?php
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts']) && !empty($loadmore)) {
            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
            $time = time();
            $nonce = wp_create_nonce($time . "load-more-nonce");
            ?>
            <script>jQuery(document).ready(function () {
                    <?php // $scrollMore = load_more_posts_style shortcode att
                    if ($scrollMore == 'autoscroll') { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode ?>
                    jQuery(".<?php echo $fts_dynamic_class_name ?>instagram").bind("scroll", function () {
                        if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
                            <?php }
                            else { // this is where we do CLICK function to LOADMORE if = button in shortcode ?>
                            jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").click(function () {
                                <?php } ?>
                                jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").addClass('fts-fb-spinner');
                                var button = jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                                console.log(button);

                                var feed_name = "fts_instagram";
                                var loadmore_count = "pics_count=<?php echo $loadmore_count ?>";
                                var feed_attributes = <?php echo json_encode($atts); ?>;
                                var yes_ajax = "yes";
                                var fts_d_name = "<?php echo $fts_dynamic_name;?>";
                                var fts_security = "<?php echo $nonce;?>";
                                var fts_time = "<?php echo $time;?>";
                                jQuery.ajax({
                                    data: {
                                        action: "my_fts_fb_load_more",
                                        next_url: nextURL_<?php echo $fts_dynamic_name ?>,
                                        fts_dynamic_name: fts_d_name,
                                        load_more_ajaxing: yes_ajax,
                                        fts_security: fts_security,
                                        fts_time: fts_time,
                                        feed_name: feed_name,
                                        loadmore_count: loadmore_count,
                                        feed_attributes: feed_attributes
                                    },
                                    type: 'GET',
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function (data) {
                                        console.log('Well Done and got this from sever: ' + data);
                                        jQuery('.<?php echo $fts_dynamic_class_name ?>').append(data).filter('.<?php echo $fts_dynamic_class_name ?>').html();
                                        if (!nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> || nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more') {
                                            jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo $instagram_no_more_photos_text ?></div>');
                                            jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
                                            jQuery(".<?php echo $fts_dynamic_class_name ?>instagram").unbind('scroll');
                                        }
                                        jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<?php echo $instagram_load_more_text; ?>');
                                        jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").removeClass('fts-fb-spinner');
                                        jQuery.fn.ftsShare(); // Reload the share each funcion otherwise you can't open share option
                                        jQuery.fn.slickInstagramPopUpFunction(); // Reload this function again otherwise the popup won't work correctly for the newly loaded items
                                        if(typeof outputSRmargin === "function"){outputSRmargin(document.querySelector('#margin').value)} // Reload our margin for the demo
                                        slickremixImageResizing(); // Reload our imagesizing function so the images show up proper
                                    }
                                }); // end of ajax()
                                return false;
                                <?php // string $scrollMore is at top of this js script. acception for scroll option closing tag
                                if ($scrollMore == 'autoscroll' ) { ?>
                            }; // end of scroll ajax load
                            <?php } ?>
                        }
                        ); // end of document.ready
            }); // end of form.submit </script>
            <?php
        }//End Check
       // main closing div not included in ajax check so we can close the wrap at all times

        print '</div>'; // closing main div for photos and scroll wrap

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
            // this div returns outputs our ajax request via jquery append html from above

            print '<div class="fts-clear"></div>';
            print '<div id="output_' . $fts_dynamic_name . '"></div>';
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'autoscroll') {
                print '<div id="loadMore_' . $fts_dynamic_name . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Instagram</div>';
            }
        }
        ?>
        <?php //only show this script if the height option is set to a number
        if ($height !== '' && empty($height) == NULL) { ?>
            <script>
                // this makes it so the page does not scroll if you reach the end of scroll bar or go back to top
                jQuery.fn.isolatedScrollFacebookFTS = function () {
                    this.bind('mousewheel DOMMouseScroll', function (e) {
                        var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,
                            bottomOverflow = this.scrollTop + jQuery(this).outerHeight() - this.scrollHeight >= 0,
                            topOverflow = this.scrollTop <= 0;
                        if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {
                            e.preventDefault();
                        }
                    });
                    return this;
                };
                jQuery('.fts-instagram-scrollable').isolatedScrollFacebookFTS();
            </script>
        <?php } //end $height !== 'auto' && empty($height) == NULL ?>
        <?php
        if (isset($scrollMore) && $scrollMore == 'autoscroll' || isset($height) && $height !== '') {
            print '</div>'; // closing height div for scrollable feeds
        }

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            print '<div class="fts-clear"></div>';
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($scrollMore) && $scrollMore == 'button') {

                print '<div class="fts-instagram-load-more-wrapper">';
                        print'<div id="loadMore_' . $fts_dynamic_name . '"" style="';
                        if (isset($loadmore_btn_maxwidth) && $loadmore_btn_maxwidth !== '') {
                            print'max-width:' . $loadmore_btn_maxwidth . ';';
                        }
                        $loadmore_btn_margin = isset($loadmore_btn_margin) ? $loadmore_btn_margin : '10px';
                        print 'margin:' . $loadmore_btn_margin . ' auto ' . $loadmore_btn_margin . '" class="fts-fb-load-more">' . $instagram_load_more_text . '</div>';
                    print'</div>';

            }
        }//End Check
        unset($_REQUEST['next_url']);

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            //******************
            // SOCIAL BUTTON
            //******************
            if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn !== 'no' && $fts_instagram_show_follow_btn_where == 'instagram-follow-below' && isset($instagram_user_info->data->username)) {
                echo '<div class="instagram-social-btn-bottom">';
                $this->social_follow_button('instagram', $instagram_user_info->data->username);
                echo '</div>';
            }
        }
        return ob_get_clean();
    }
    /**
     * Random String
     *
     * Create a random string
     *
     * @param $length
     * @return mixed
     * @since 1.9.6
     */
    function rand_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str = $chars[rand(0, $size - 1)];
        }
        return $str;
    }

}//fts_instagram_func END CLASS

?>