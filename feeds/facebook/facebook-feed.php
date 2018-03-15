<?php namespace feedthemsocial;
/**
 * Class FTS Facebook Feed
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Facebook_Feed extends feed_them_social_functions {
    /**
     * Construct
     *
     * Facebook Feed constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
        add_shortcode('fts_facebook_group', array($this, 'fts_fb_func'));
        add_shortcode('fts_facebook_page', array($this, 'fts_fb_func'));
        add_shortcode('fts_facebook_event', array($this, 'fts_fb_func'));
        add_shortcode('fts_facebook', array($this, 'fts_fb_func'));
        add_action('wp_enqueue_scripts', array($this, 'fts_fb_head'));
    }

    /**
     * FTS FB Head
     *
     * Add Styles and Scripts functions.
     *
     * @since 1.9.6
     */
    function fts_fb_head() {
        wp_enqueue_style('fts-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));
        if (is_plugin_active('feed-them-social/feed-them.php') && is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php') && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            wp_enqueue_script('fts-feeds', plugins_url('feed-them-carousel-premium/feeds/js/jquery.cycle2.js'));
        }
    }

    // Date sort option for multiple feeds in a shortcode
    function dateSort($a,$b){
        $dateA = strtotime($a->created_time);
        $dateB = strtotime($b->created_time);
        return ($dateB-$dateA);
    }


    /**
     * FTS FB Func
     *
     * Display Facebook Feed.
     *
     * @param $atts
     * @return string
     * @since 1.9.6
     */
    function fts_fb_func($atts, $cache) {
        // masonry snippet in fts-global
        wp_enqueue_script('fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array('jquery'));
        $developer_mode = 'on';
        //Make sure everything is reset
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        //Eventually add premium page file
        if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
            $FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
            $review_atts = $FTS_Facebook_Reviews->shortcode_attributes();
            $FB_Shortcode = shortcode_atts($review_atts, $atts);
            //Load up some scripts for popup
            $this->load_popup_scripts($FB_Shortcode);
        } elseif (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include(WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/facebook/facebook-premium-feed.php');
            //Load up some scripts for popup
            $this->load_popup_scripts($FB_Shortcode);
        } elseif (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php') && !is_plugin_active('feed-them-premium/feed-them-premium.php')){
            $FB_Shortcode = shortcode_atts(array(
                'id' => '',
                'type' => '',
                'posts' => '',
                'posts_displayed' => '',
                'height' => '',
                'album_id' => '',
                'image_width' => '',
                'image_height' => '',
                'space_between_photos' => '',
                'hide_date_likes_comments' => '',
                'center_container' => '',
                'image_stack_animation' => '',
                'image_position_lr' => '',
                'image_position_top' => '',
                'hide_comments_popup' => '',
                //only works with combined FB streams otherwise you need the premium version.
                'popup' => '',
                'words' => '',
                'grid' => '',
                'colmn_width' => '',
                'space_between_posts' => '',
                //new show media on top options
                'show_media'  => '',
                'show_date'  => '',
                'show_name'  => '',

            ), $atts);
            if ($FB_Shortcode['posts'] == NULL)
                $FB_Shortcode['posts'] = '6';

        }else {
            $FB_Shortcode = shortcode_atts(array(
                'id' => '',
                'type' => '',
                'posts' => '',
                'posts_displayed' => '',
                'height' => '',
                'album_id' => '',
                'image_width' => '',
                'image_height' => '',
                'space_between_photos' => '',
                'hide_date_likes_comments' => '',
                'center_container' => '',
                'image_stack_animation' => '',
                'image_position_lr' => '',
                'image_position_top' => '',
                'hide_comments_popup' => '',

            ), $atts);
            if ($FB_Shortcode['posts'] == NULL)
                $FB_Shortcode['posts'] = '6';
        }

        if($FB_Shortcode['type'] == 'album_videos'){
            $FB_Shortcode['type'] = 'album_photos';
            $FB_Shortcode['video_album'] = 'yes';
            $FB_Shortcode['album_id'] = 'photo_stream';
        }


        if (!is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-premium/feed-them-premium.php') && !is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php') && $FB_Shortcode['posts'] > '6') {
            $FB_Shortcode['posts'] = '6';
        }

        //Get Access Token
        $access_token = $this->get_access_token();
        //UserName?
        if (!$FB_Shortcode['id']) {
            return 'Please enter a username for this feed.';
        }
        if ($FB_Shortcode['type'] == 'reviews' && !is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
            return '<div style="clear:both; padding:15px 0;">You must have FTS Facebook Reviews extension active to see this feed.</div>';
        }

        $type = isset($FB_Shortcode['type']) ? $FB_Shortcode['type'] : '';
        if ($type == 'group' || $type == 'page' || $type == 'event') {

            //////////////////////////////////////////////////
            ///// EMPTY FACEBOOK POSTS OFFSET AND COUNT /////
            ////////////////////////////////////////////////

            // Option Now Being Removed from here and the Facebook Settings Page
            // Setting it to blank so no matter what it will never error get_option('fb_count_offset');
            $FB_count_offset = '';

            //View Link
            $fts_view_fb_link = '';
            //Get Cache Name
            $fb_cache_name = '';
            //Get language
            $language = '';

            //Get Response (AKA Page & Feed Information) ERROR CHECK inside this function
            $response2 = $this->get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language);

            // Test to see if the re-sort date option is working from function above.
            // print $this->dateSort;

            $feed_data_check = json_decode($response2['feed_data']);

             //  echo '<pre>';
             //  print_r($feed_data_check);
             //  echo '</pre>';

            //  $idNew = array();
            //  $idNew = explode(',', $FB_Shortcode['id']);

            // Testing options before foreach loop
            // $idNew = 'tonyhawk';
            // print_r($feed_data_check->$idNew->data);

            if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
                $ftsCountIds = substr_count($FB_Shortcode['id'], ",");
            }
            else{
                $ftsCountIds = '';
            }

            if(isset($feed_data_check->data)){
                if($ftsCountIds >= 1 && $FB_Shortcode['type'] !== 'reviews') {
                    $fts_list_arrays = array();
                    foreach ($feed_data_check as $feed_data_name) {

                        if(isset($feed_data_name->data)){
                            $fts_list_arrays =  array_merge_recursive($fts_list_arrays, $feed_data_name->data);
                        }
                        //var_dump( $fts_list_arrays[$i]);

                    }
                    // we don't need to sort event feeds for this check because we already to that
                    if($FB_Shortcode['type'] !== 'events') {
                        // Sort the array using the call back function
                        usort($fts_list_arrays, array($this, "dateSort"));
                    }

                    $merged_Array['data'] = $fts_list_arrays;
                    $feed_data_check = (object) $merged_Array;
                }

                // Test the created dataes are being sorted properly
                //   foreach($merged_Array['data'] as $newSort) {
                //       print date("jS F, Y", strtotime($newSort->created_time));
                //       print '<br/>';
                //    }

                $set_zero = 0;
                foreach ($feed_data_check->data as $post_count) {

                    $FBmessage = isset($post_count->message) ? $post_count->message : "";
                    $FBstory = isset($post_count->story) ? $post_count->story : "";
                    $FBtype = isset($post_count->type) ? $post_count->type : "";
                    $FBstatus_type = isset($post_count->status_type) ? $post_count->status_type : "";

                    // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
                    // We will count all the ones that are like this and add that number to the output of posts to offset the posts we are filtering out. Line 278 needs the same treatment of if options.
                    if ($FBtype == 'status' && $FBmessage == '' && $FBstory == '' || $FBtype == 'event' || $FBtype == 'event' && strpos($FBstory, 'shared their event') !== false || $FBtype == 'status' && strpos($FBstory, 'changed the name of the event to') !== false || $FBtype == 'status' && strpos($FBstory, 'changed the privacy setting') !== false || $FBtype == 'status' && strpos($FBstory, 'an admin of the group') !== false || $FBtype == 'status' && strpos($FBstory, 'created the group') !== false || $FBtype == 'status' && strpos($FBstory, 'added an event') !== false || $FBtype == 'event' && strpos($FBstory, 'added an event') !== false) {
                        $set_zero++;
                    }
                    // If more than the 5 posts(default in free) or the post= from shortcode is set to the amount of posts that are being filtered above we will add 7 to the post count to try and get at some posts.
                    // This will only happen for Page and Group feeds.
                    elseif ($feed_data_check->data == 0) {
                        $set_zero = '7';
                    }
                }// END POST foreach

                // Result of the foreach loop above minus the empty posts and offset by those posts the actual number of posts entered is shown.
                //			$FB_Shortcode['posts'] = $result;
                if (!empty($FB_count_offset)) {
                    $set_zero = $FB_count_offset;
                    $unsetCount = $FB_Shortcode['posts'] + $set_zero;
                    $FB_Shortcode['posts'] = $unsetCount;
                } else {
                    $unsetCount = $FB_Shortcode['posts'] + $set_zero;
                    $FB_Shortcode['posts'] = $unsetCount;
                }


              //  echo '<pre>';
              //  print_r($feed_data_check);
              //  echo '</pre>, ';
            }

            ///////////////////////////////////////////////////
            ////////////////////// END ///////////////////////
            //////////////////////////////////////////////////
        }

        ob_start();

        //  Uncomment these for testing purposes to see the actual count and the offset count
        //  print 	$set_zero;
        //  print 	$FB_Shortcode['posts'];
        //	print   $FBtype;

        //View Link
        $fts_view_fb_link = $this->get_view_link($FB_Shortcode);
        //Get Cache Name
        $fb_cache_name = $this->get_fb_cache_name($FB_Shortcode);
        //Get language
        $language = $this->get_language($FB_Shortcode);
        if ($FB_Shortcode['type'] !== 'reviews') {
            //Get Response (AKA Page & Feed Information) ERROR CHECK inside this function
            $response = $this->get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language);
            //Json decode data and build it from cache or response
            $page_data = json_decode($response['page_data']);
            $feed_data = json_decode($response['feed_data']);
        }


        if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && get_option('fts_facebook_custom_api_token_biz') == TRUE && $FB_Shortcode['type'] == 'reviews') {

                if($FB_Shortcode['remove_reviews_no_description'] == 'yes' && !isset($_GET['load_more_ajaxing'])) {

                    $FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
                    $no_description_count = $FTS_Facebook_Reviews->review_count_check($FB_Shortcode);

                    // testing purposes
                   // print ''. $no_description_count - $FB_Shortcode['posts'] .' = The amount of posts with no review text.';

                    // this count includes our original posts count + the amount of posts we found with no description
                    $FB_Shortcode['posts'] = $no_description_count;
                }

                //Get Response (AKA Page & Feed Information) ERROR CHECK inside this function
                $response = $this->get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language);

                $feed_data = json_decode($response['feed_data']);

                if($FB_Shortcode['remove_reviews_no_description'] == 'yes') {
                    // $no_description_count2 = 0;
                        foreach ($feed_data->data as $k => $v) {
                            if (!isset($v->review_text)) {
                                //  print $v->reviewer->name . ' (Key# ' . $k . ') : Now Unset from array<br/>';
                                unset($feed_data->data[$k]);
                                // $no_description_count2++;
                            }
                        }
                }
            $ratings_data = json_decode($response['ratings_data']);
        }


        if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
            $ftsCountIds = substr_count($FB_Shortcode['id'], ",");
        }
        else{
            $ftsCountIds = '';
        }

        if($ftsCountIds >= 1 && $FB_Shortcode['type'] !== 'reviews') {

            $fts_list_arrays = array();
            foreach ($feed_data as $feed_data_name) {

                $fts_list_arrays = array_merge_recursive($fts_list_arrays, $feed_data_name->data);
                //var_dump( $fts_list_arrays[$i]);

            }

            // we don't need to sort event feeds because we already to that
            if($FB_Shortcode['type'] !== 'events') {
                // Sort the array using the call back function
                usort($fts_list_arrays, array($this, "dateSort"));
            }


            $merged_Array['data'] = $fts_list_arrays;
            $feed_data = (object)$merged_Array;
        }

        //  echo '<pre>';
        // print_r($feed_data );
        //  echo '</pre>';

        //If No Response or Error then return
        if (is_array($response) && isset($response[0]) && isset($response[1]) && $response[0] == false) {
            return $response[1];
        }

        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            //Make sure it's not ajaxing and we will allow the omition of certain album covers from the list by using omit_album_covers=0,1,2,3 in the shortcode
            if (!isset($_GET['load_more_ajaxing'])) {
                if ($FB_Shortcode['type'] == 'albums') {
                    // omit_album_covers=0,1,2,3 for example
                    $omit_album_covers = $FB_Shortcode['omit_album_covers'];
                    $omit_album_covers_new = array();
                    $omit_album_covers_new = explode(',', $omit_album_covers);
                    foreach ($feed_data->data as $post_data) {
                        foreach ($omit_album_covers_new as $omit) {
                            unset($feed_data->data[$omit]);
                        }
                    }
                }
            }
        }
        //Reviews Rating Filter
        if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews') {
            foreach ($feed_data->data as $key => $post_data) {
                if ($post_data->rating < $FB_Shortcode['reviews_type_to_show']) {
                    unset($feed_data->data[$key]);
                }
            }
        }

        //If events array Flip it so it's in proper order
        if ($FB_Shortcode['type'] == 'events') {
            if ($feed_data->data) {
                usort($feed_data->data, function ($a, $b) {
                    $a = strtotime($a->start_time);
                    $b = strtotime($b->start_time);
                    return (($a == $b) ? (0) : (($a > $b) ? (1) : (-1)));
                });
                //	 $feed_data->data = array_reverse($feed_data->data);
            }
        }

        $FTS_FB_OUTPUT = '';
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            //Get Response (AKA Page & Feed Information)
            $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);
            //Create Dynamic Class Name
            $fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
            //******************
            // SOCIAL BUTTON
            //******************
            if(!$ftsCountIds >= 1) {
                $FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-top-above-title');
            }




            if ($FB_Shortcode['type'] !== 'reviews') {
                $page_data->description = isset($page_data->description) ? $page_data->description : "";
                $page_data->name = isset($page_data->name) ? $page_data->name : "";
            }
            // fts-fb-header-wrapper (for grid)
            $FTS_FB_OUTPUT .= isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] !== 'yes' && $FB_Shortcode['type'] !== 'album_photos' && $FB_Shortcode['type'] !== 'albums' ? '<div class="fts-fb-header-wrapper">' : '';


            //Header
            $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-header">';


            if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && isset($FB_Shortcode['overall_rating']) && $FB_Shortcode['overall_rating'] == 'yes') {

                // $FTS_FB_OUTPUT .= $this->get_facebook_overall_rating_response($FB_Shortcode, $fb_cache_name, $access_token);

                $fb_reviews_overall_rating_of_5_stars_text = get_option('fb_reviews_overall_rating_of_5_stars_text');
                $fb_reviews_overall_rating_of_5_stars_text = !empty($fb_reviews_overall_rating_of_5_stars_text) ? ' ' . $fb_reviews_overall_rating_of_5_stars_text : ' of 5 stars';
                $fb_reviews_overall_rating_reviews_text = get_option('fb_reviews_overall_rating_reviews_text');
                $fb_reviews_overall_rating_reviews_text = !empty($fb_reviews_overall_rating_reviews_text) ? ' ' . $fb_reviews_overall_rating_reviews_text : ' reviews';
                $fb_reviews_overall_rating_background_border_hide = get_option('fb_reviews_overall_rating_background_border_hide');
                $fb_reviews_overall_rating_background_border_hide = !empty($fb_reviews_overall_rating_background_border_hide) && $fb_reviews_overall_rating_background_border_hide == 'yes' ? ' fts-review-details-master-wrap-no-background-or-border' : '';
                $FTS_FB_OUTPUT .= '<div class="fts-review-details-master-wrap' . $fb_reviews_overall_rating_background_border_hide . '" itemscope itemtype="http://schema.org/CreativeWork"><i class="fts-review-star">' . $ratings_data->overall_star_rating . ' &#9733;</i>';
                $FTS_FB_OUTPUT .= '<div class="fts-review-details-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="fts-review-details"><span itemprop="ratingValue">' . $ratings_data->overall_star_rating . '</span>' . $fb_reviews_overall_rating_of_5_stars_text . '</div>';
                $FTS_FB_OUTPUT .= '<div class="fts-review-details-count"><span itemprop="reviewCount">' . $ratings_data->rating_count . '</span>' . $fb_reviews_overall_rating_reviews_text . '</div></div></div>';


            }
            if($FB_Shortcode['type'] !== 'reviews') {
                if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {

                    // $FTS_FB_OUTPUT .= our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.

                    $fts_align_title = isset($FB_Shortcode['title_align']) && $FB_Shortcode['title_align'] !== '' ? 'style="text-align:' . $FB_Shortcode['title_align'] . ';"' : '';
                    $FTS_FB_OUTPUT .= isset($FB_Shortcode['title']) && $FB_Shortcode['title'] !== 'no' ? '<h1 ' . $fts_align_title . '><a href="' . $fts_view_fb_link . '" target="_blank">' . $page_data->name . '</a></h1>' : '';
                    //Description
                    $FTS_FB_OUTPUT .= isset($FB_Shortcode['description']) && $FB_Shortcode['description'] !== 'no' ? '<div class="fts-jal-fb-group-header-desc">' . $this->fts_facebook_tag_filter($page_data->description) . '</div>' : '';

                } else {
                    // $FTS_FB_OUTPUT .= our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
                    $FTS_FB_OUTPUT .= '<h1><a href="' . $fts_view_fb_link . '" target="_blank">' . $page_data->name . '</a></h1>';
                    //Description
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-group-header-desc">' . $this->fts_facebook_tag_filter($page_data->description) . '</div>';
                }
            }
            //END Header
            $FTS_FB_OUTPUT .= '</div>';
            // Close fts-fb-header-wrapper
            $FTS_FB_OUTPUT .= isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] !== 'yes' && $FB_Shortcode['type'] !== 'album_photos' && $FB_Shortcode['type'] !== 'albums' ? '</div>' : '';
        } //End check


        //******************
        // SOCIAL BUTTON
        //******************
        if(!$ftsCountIds >= 1) {
            $FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-top-below-title');
        }


        //*********************
        // Feed Header
        //*********************
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {


            $fts_mashup_media_top = isset($FB_Shortcode['show_media']) && $FB_Shortcode['show_media'] == 'top'  ? 'fts-mashup-media-top ' : '';
            $fts_mashup_show_name = isset($FB_Shortcode['show_name']) && $FB_Shortcode['show_name'] == 'no'  ? ' fts-mashup-hide-name ' : '';
            $fts_mashup_show_date = isset($FB_Shortcode['show_date']) && $FB_Shortcode['show_date'] == 'no'  ? ' fts-mashup-hide-date ' : '';
            $fts_mashup_show_thumbnail = isset($FB_Shortcode['show_thumbnail']) && $FB_Shortcode['show_thumbnail'] == 'no'  ? ' fts-mashup-hide-thumbnail ' : '';


            if (!isset($FBtype) && $FB_Shortcode['type'] == 'albums' || !isset($FBtype) && $FB_Shortcode['type'] == 'album_photos' || isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {


                if (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                } elseif (isset($FB_Shortcode['slider']) && $FB_Shortcode['slider'] !== 'yes' && $FB_Shortcode['image_stack_animation'] == 'yes' || isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes' || isset($FB_Shortcode['image_stack_animation']) && $FB_Shortcode['image_stack_animation'] == 'yes' ) {
                    wp_enqueue_script('fts-masonry-pkgd', plugins_url('feed-them-social/feeds/js/masonry.pkgd.min.js'), array('jquery'));
                    $FTS_FB_OUTPUT .= '<script>';
                    $FTS_FB_OUTPUT .= 'jQuery(window).load(function(){';
                    $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").masonry({';
                    $FTS_FB_OUTPUT .= 'itemSelector: ".fts-jal-single-fb-post"';
                    $FTS_FB_OUTPUT .= '});';
                    $FTS_FB_OUTPUT .= '});';
                    $FTS_FB_OUTPUT .= '</script>';
                }


                if (!isset($FBtype) && $FB_Shortcode['type'] == 'albums' || !isset($FBtype) && $FB_Shortcode['type'] == 'album_photos' && !isset($FBtype) && !isset($FB_Shortcode['slider']) || !isset($FBtype) && $FB_Shortcode['type'] == 'album_photos' && !isset($FBtype) && isset($FB_Shortcode['slider']) && $FB_Shortcode['slider'] !== 'yes') {
                    $FTS_FB_OUTPUT .= '<div class="fts-slicker-facebook-photos fts-slicker-facebook-albums' . (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] && $FB_Shortcode['video_album'] == 'yes' ? ' popup-video-gallery-fb' : '') . (isset($FB_Shortcode['image_stack_animation']) && $FB_Shortcode['image_stack_animation'] == 'yes' ? ' masonry js-masonry' : '') . (isset($FB_Shortcode['images_align']) && $FB_Shortcode['images_align'] ? ' popup-video-gallery-align-' . $FB_Shortcode['images_align'] : '') . ' popup-gallery-fb ' . $fts_dynamic_class_name . '"';if($FB_Shortcode['image_stack_animation'] == 'yes'){ $FTS_FB_OUTPUT .= 'data-masonry-options=\'{ "isFitWidth": ' . ($FB_Shortcode['center_container'] == 'no' ? 'false' : 'true') . ' ' . ($FB_Shortcode['image_stack_animation'] == 'no' ? ', "transitionDuration": 0' : '') . '}\' style="margin:auto;"';} $FTS_FB_OUTPUT .= '>';

                } // slideshow scrollHorz or carousel
                elseif (!isset($FBtype) && isset($FB_Shortcode['slider']) && $FB_Shortcode['slider'] == 'yes') {
                    $fts_cycleType = $FB_Shortcode['scrollhorz_or_carousel'] ? $FB_Shortcode['scrollhorz_or_carousel'] : 'scrollHorz';

                    if (isset($fts_cycleType) && $fts_cycleType == 'carousel') {
                        $fts_cycle_slideshow = 'slideshow';
                    } else {
                        $fts_cycle_slideshow = 'cycle-slideshow';
                    }
                    $FTS_FB_OUTPUT .= '';

                    // none

                    // dots_above_feed
                    // dots_and_arrows_above_feed
                    // dots_and_numbers_above_feed
                    // dots_arrows_and_numbers_above_feed
                    // arrows_and_numbers_above_feed
                    // arrows_above_feed
                    // numbers_above_feed

                    // dots_below_feed
                    // dots_and_arrows_below_feed
                    // dots_and_numbers_below_feed
                    // dots_arrows_and_numbers_below_feed
                    // arrows_and_numbers_below_feed
                    // arrows_below_feed
                    // numbers_below_feed

                    $fts_controls_bar_color = !empty($FB_Shortcode['slider_controls_bar_color']) ? $FB_Shortcode['slider_controls_bar_color'] : '#000';
                    $fts_controls_text_color = !empty($FB_Shortcode['slider_controls_text_color']) ? $FB_Shortcode['slider_controls_text_color'] : '#ddd';
                    if (isset($FB_Shortcode['slider_controls_width']) && $FB_Shortcode['scrollhorz_or_carousel'] !== 'carousel') {
                        $maxWidthSet = isset($FB_Shortcode['image_width']) && $FB_Shortcode['image_width'] !== '' && $FB_Shortcode['scrollhorz_or_carousel'] !== 'carousel' ? $FB_Shortcode['image_width'] : '100%';
                    } else {
                        $maxWidthSet = isset($FB_Shortcode['slider_controls_width']) && $FB_Shortcode['slider_controls_width'] !== '' && $FB_Shortcode['scrollhorz_or_carousel'] == 'carousel' ? $FB_Shortcode['slider_controls_width'] : '100%';
                    }
                    if (
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_above_feed' ||
                        isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_above_feed'
                    ) {


                        // Slider Dots Wrapper
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed'
                        ) {


                            $FTS_FB_OUTPUT .= '<div class="fts-slider-icons-center fts-pager-option-dots-only-top" style="margin:auto; width:100%;max-width:' . $maxWidthSet . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color . '"><div class="fts-pager-option fts-custom-pager-' . $fts_dynamic_class_name . '"></div></div>';
                        }


                        // Slider Arrow and Numbers Wrapper
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_above_feed'
                        ) {
                            $FTS_FB_OUTPUT .= '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . $maxWidthSet . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color . '">';
                        }

                        // Previous Arrow
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_above_feed'
                        ) {
                            $FTS_FB_OUTPUT .= '<span class="fts-prevControl-icon fts-prevControl-' . $fts_dynamic_class_name . '"></span>';
                        }
                        // Numbers
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_above_feed'
                        ) {
                            $FTS_FB_OUTPUT .= '<span id="fts-custom-caption-' . $fts_dynamic_class_name . '" class="fts-custom-caption" ></span>';
                        }
                        // Next Arrow
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_above_feed'
                        ) {
                            $FTS_FB_OUTPUT .= '<span class="fts-nextControl-icon fts-nextControl-' . $fts_dynamic_class_name . '"></span>';
                        }


                        // Slider Arrow and Numbers Wrapper
                        if (
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_above_feed' ||
                            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_above_feed'
                        ) {
                            $FTS_FB_OUTPUT .= '</div>';
                        }

                    }


                    $FTS_FB_OUTPUT .= '<div class="popup-gallery-fb fts-fb-slideshow fts-slicker-facebook-photos fts-slicker-facebook-albums ' . $fts_cycle_slideshow . ' ' . (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] && $FB_Shortcode['video_album'] == 'yes' ? 'popup-video-gallery-fb' : '') . ' ' . (isset($FB_Shortcode['images_align']) && $FB_Shortcode['images_align'] ? ' popup-video-gallery-align-' . $FB_Shortcode['images_align'] : '') . ' popup-gallery-fb ' . $fts_dynamic_class_name . '"

style="margin:' . (isset($FB_Shortcode['slider_margin']) && $FB_Shortcode['slider_margin'] !== '' ? $FB_Shortcode['slider_margin'] : 'auto') . ';' . (isset($fts_cycleType) && $fts_cycleType == 'carousel' ? 'width:100%; max-width:100%; overflow:hidden;height:' . $FB_Shortcode['image_height'] . ';' : 'overflow:hidden; height:' . $FB_Shortcode['image_height'] . '; max-width:' . (isset($FB_Shortcode['image_width']) && $FB_Shortcode['image_width'] !== '' ? $FB_Shortcode['image_width'] : 'auto')) . ';" data-cycle-caption="#fts-custom-caption-' . $fts_dynamic_class_name . '" data-cycle-caption-template="{{slideNum}} / {{slideCount}}" data-cycle-pager=".fts-custom-pager-' . $fts_dynamic_class_name . '" data-cycle-pause-on-hover="true" data-cycle-prev=".fts-prevControl-' . $fts_dynamic_class_name . '" data-cycle-next=".fts-nextControl-' . $fts_dynamic_class_name . '" data-cycle-timeout="' . (!empty($FB_Shortcode['slider_timeout']) ? $FB_Shortcode['slider_timeout'] : '0') . '" data-cycle-manual-speed="' . (!empty($FB_Shortcode['slider_speed']) ? $FB_Shortcode['slider_speed'] : '400') . '" data-cycle-auto-height="false" data-cycle-slides="> div" data-cycle-fx="' . (!empty($FB_Shortcode['scrollhorz_or_carousel']) ? $FB_Shortcode['scrollhorz_or_carousel'] : '') . '" data-cycle-carousel-visible=' . (!empty($FB_Shortcode['slides_visible']) ? $FB_Shortcode['slides_visible'] : '4') . ' data-cycle-swipe=true data-cycle-swipe-fx=' . (!empty($FB_Shortcode['scrollhorz_or_carousel']) ? $FB_Shortcode['scrollhorz_or_carousel'] : '') . '>';
                }


                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                    $FTS_FB_OUTPUT .= '<div class="fts-slicker-facebook-posts masonry js-masonry ' . $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail . ($FB_Shortcode['popup'] == 'yes' ? 'popup-gallery-fb-posts ' : '') . ($FB_Shortcode['type'] == 'reviews' ? 'fts-reviews-feed ' : '') . $fts_dynamic_class_name . ' " style="margin:auto;" data-masonry-options=\'{ "isFitWidth": ' . ($FB_Shortcode['center_container'] == 'no' ? 'false' : 'true') . ' ' . ($FB_Shortcode['image_stack_animation'] == 'no' ? ', "transitionDuration": 0' : '') . '}\'>';
                    //  $FTS_FB_OUTPUT .= '<div class="fts-slicker-facebook-photos fts-slicker-facebook-posts masonry js-masonry ' . ($FB_Shortcode['popup'] == 'yes' ? 'popup-gallery-fb-posts ' : '') . ($FB_Shortcode['type'] == 'reviews' ? 'fts-reviews-feed ' : '') . $fts_dynamic_class_name . ' " style="margin:auto;" data-masonry-options=\'{ "isFitWidth": ' . ($FB_Shortcode['center_container'] == 'no' ? 'false' : 'true') . ' ' . ($FB_Shortcode['image_stack_animation'] == 'no' ? ', "transitionDuration": 0' : '') . '}\'>';
                }
            }
            else {
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-group-display fts-simple-fb-wrapper ' . $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? ' popup-gallery-fb-posts ' : '') . ($FB_Shortcode['type'] == 'reviews' ? 'fts-reviews-feed ' : '') . $fts_dynamic_class_name . ' ' . ($FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL ? 'fts-fb-scrollable" style="height:' . $FB_Shortcode['height'] . '"' : '"') . '>';
            }
        } //End ajaxing Check


        //*********************
        // Post Information
        //*********************
        $fb_load_more_text = get_option('fb_load_more_text') ? get_option('fb_load_more_text') : __('Load More', 'feed-them-social');
        $response_post_array = $this->get_post_info($feed_data, $FB_Shortcode, $access_token, $language);

        //Single event info call
        if ($FB_Shortcode['type'] == 'events') {
            $single_event_array_response = $this->get_event_post_info($feed_data, $FB_Shortcode, $access_token, $language);
        }

        $set_zero = 0;
        //THE MAIN FEED


        //LOOP to fix Post count!
        foreach ($feed_data->data as $k => $v) {
            if ($k >= $FB_Shortcode['posts']) unset($feed_data->data[$k]);
        }

        // Nov. 4th. 2016 // Uncomment this to sort the dates proper if facebook is returning them out of order.
        // We had one case of this here for a list of posts coming from an event.
        // https://wordpress.org/support/topic/facebook-event-posts-not-ordered-by-date/
        // usort($feed_data->data, array($this, "dateSort"));

        // Loop for all facebook feeds.
        foreach ($feed_data->data as $post_data) {

            $FBmessage = isset($post_data->message) ? $post_data->message : "";
            $FBstatusType = isset($post_data->status_type) ? $post_data->status_type : "";

            $FBstory = isset($post_data->story) ? $post_data->story : "";
            $FBtype = isset($post_data->type) ? $post_data->type : "";


            // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types
            if ($FBtype == 'status' && $FBmessage == '' && $FBstory == '' || $FBtype == 'event' || $FBtype == 'event' && strpos($FBstory, 'shared their event') !== false || $FBtype == 'status' && strpos($FBstory, 'changed the name of the event to') !== false || $FBtype == 'status' && strpos($FBstory, 'changed the privacy setting') !== false || $FBtype == 'status' && strpos($FBstory, 'an admin of the group') !== false || $FBtype == 'status' && strpos($FBstory, 'created the group') !== false || $FBtype == 'status' && strpos($FBstory, 'added an event') !== false || $FBtype == 'event' && strpos($FBstory, 'added an event') !== false) {
            } else {
                //Define Type NOTE Also affects Load More Fucntion call
                if (!$FBtype && $FB_Shortcode['type'] == 'album_photos') {
                    $FBtype = 'photo';
                }
                if (!$FBtype && $FB_Shortcode['type'] == 'events') {
                    $FBtype = 'events';

                }

                $post_types = new FTS_Facebook_Feed_Post_Types();
                $single_event_array_response = isset($single_event_array_response) ? $single_event_array_response : '';
                $FTS_FB_OUTPUT .= $post_types->feed_post_types($set_zero, $FBtype, $post_data, $FB_Shortcode, $response_post_array, $single_event_array_response);

            }

            $set_zero++;
        }// END POST foreach

        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews') {
            if (!empty($feed_data->data)) {
                $FTS_FB_OUTPUT .= $this->fts_facebook_loadmore($atts, $feed_data, $FBtype, $FB_Shortcode, $_REQUEST['fts_dynamic_name']);
            }
        }

        $FTS_FB_OUTPUT .= '</div>'; // closing main div for fb photos, groups etc
        //only show this script if the height option is set to a number
        if ($FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL) {
            $FTS_FB_OUTPUT .= '<script>';
            // this makes it so the page does not scroll if you reach the end of scroll bar or go back to top'
            $FTS_FB_OUTPUT .= 'jQuery.fn.isolatedScrollFacebookFTS = function() {';
            $FTS_FB_OUTPUT .= 'this.bind("mousewheel DOMMouseScroll", function (e) {';
            $FTS_FB_OUTPUT .= 'var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,';
            $FTS_FB_OUTPUT .= 'bottomOverflow = this.scrollTop + jQuery(this).outerHeight() - this.scrollHeight >= 0,';
            $FTS_FB_OUTPUT .= 'topOverflow = this.scrollTop <= 0;';
            $FTS_FB_OUTPUT .= 'if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {';
            $FTS_FB_OUTPUT .= 'e.preventDefault();';
            $FTS_FB_OUTPUT .= '}';
            $FTS_FB_OUTPUT .= '});';
            $FTS_FB_OUTPUT .= 'return this;';
            $FTS_FB_OUTPUT .= '};';
            $FTS_FB_OUTPUT .= 'jQuery(".fts-fb-scrollable").isolatedScrollFacebookFTS();';
            $FTS_FB_OUTPUT .= '</script>';
        } //end $FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            $FTS_FB_OUTPUT .= '<div class="fts-clear"></div><div id="fb-root"></div>';
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews') {
                if ($FB_Shortcode['loadmore'] == 'button') {

                    $FTS_FB_OUTPUT .= '<div class="fts-fb-load-more-wrapper">';
                    $FTS_FB_OUTPUT .= '<div id="loadMore_' . $_REQUEST['fts_dynamic_name'] . '" style="';
                    if (isset($FB_Shortcode['loadmore_btn_maxwidth']) && $FB_Shortcode['loadmore_btn_maxwidth'] !== '') {
                        $FTS_FB_OUTPUT .= 'max-width:' . $FB_Shortcode['loadmore_btn_maxwidth'] . ';';
                    }
                    $loadmore_btn_margin = isset($FB_Shortcode['loadmore_btn_margin']) ? $FB_Shortcode['loadmore_btn_margin'] : '20px';
                    $FTS_FB_OUTPUT .= 'margin:' . $loadmore_btn_margin . ' auto ' . $loadmore_btn_margin . '" class="fts-fb-load-more">' . $fb_load_more_text . '</div>';
                    $FTS_FB_OUTPUT .= '</div>';
                }
            }


        }//End Check


        // Checks for sliders
        if (
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_below_feed' ||
            isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_below_feed'
        ) {


            // Slider Dots Wrapper
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed'
            ) {
                if (isset($FB_Shortcode['slider_controls_width']) && $FB_Shortcode['scrollhorz_or_carousel'] !== 'carousel') {
                    $maxWidthSet = isset($FB_Shortcode['image_width']) && $FB_Shortcode['image_width'] !== '' && $FB_Shortcode['scrollhorz_or_carousel'] !== 'carousel' ? $FB_Shortcode['image_width'] : '100%';
                } else {
                    $maxWidthSet = isset($FB_Shortcode['slider_controls_width']) && $FB_Shortcode['slider_controls_width'] !== '' && $FB_Shortcode['scrollhorz_or_carousel'] == 'carousel' ? $FB_Shortcode['slider_controls_width'] : '100%';
                }

                $FTS_FB_OUTPUT .= '<div class="fts-slider-icons-center" style="margin:auto; width:100%;max-width:' . $maxWidthSet . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color . '"><div class="fts-pager-option fts-custom-pager-' . $fts_dynamic_class_name . '"></div></div>';
            }


            // Slider Arrow and Numbers Wrapper
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_below_feed'
            ) {
                $FTS_FB_OUTPUT .= '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . $maxWidthSet . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color . '">';
            }

            // Previous Arrow
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_below_feed'
            ) {
                $FTS_FB_OUTPUT .= '<span class="fts-prevControl-icon fts-prevControl-' . $fts_dynamic_class_name . '"></span>';
            }
            // Numbers
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_below_feed'
            ) {
                $FTS_FB_OUTPUT .= '<span id="fts-custom-caption-' . $fts_dynamic_class_name . '" class="fts-custom-caption" ></span>';
            }
            // Next Arrow
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_below_feed'
            ) {
                $FTS_FB_OUTPUT .= '<span class="fts-nextControl-icon fts-nextControl-' . $fts_dynamic_class_name . '"></span>';
            }


            // Slider Arrow and Numbers Wrapper
            if (
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'dots_arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_and_numbers_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'arrows_below_feed' ||
                isset($FB_Shortcode['slider_controls']) && $FB_Shortcode['slider_controls'] == 'numbers_below_feed'
            ) {
                $FTS_FB_OUTPUT .= '</div>';
            }

        }



        unset($_REQUEST['next_url']);


        //******************
        // SOCIAL BUTTON
        //******************
        if(!$ftsCountIds >= 1) {
            $FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-below');
        }

        $FTS_FB_OUTPUT .= ob_get_clean();
        return $FTS_FB_OUTPUT;
    }

    /**
     * Get FTS Dnamic Class Name
     *
     * @return string
     * @since 1.9.6
     */
    function get_fts_dynamic_class_name() {
        $fts_dynamic_class_name = '';
        if (isset($_REQUEST['fts_dynamic_name'])) {
            $fts_dynamic_class_name = 'feed_dynamic_class' . $_REQUEST['fts_dynamic_name'];
        }
        return $fts_dynamic_class_name;
    }

    /**
     * FTS Facebook Location
     *
     * Facebook Post Location.
     *
     * @param null $FBtype
     * @param $location
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_location($FBtype = NULL, $location) {
        switch ($FBtype) {
            case 'app':
            case 'cover':
            case 'profile':
            case 'mobile':
            case 'wall':
            case 'normal':
            case 'album':
                $output = '<div class="fts-fb-location">' . $location . '</div>';
                return $output;
        }
    }

    /**
     * FTS Facebook Post Photo
     *
     * @param $FBlink
     * @param $FB_Shortcode
     * @param $photo_from
     * @param $photo_source
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_post_photo($FBlink, $FB_Shortcode, $photo_from, $photo_source) {
        if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
            $output = '<a href="' . $FBlink . '" target="_blank" class="fts-jal-fb-picture album-photo-fts" style="width:' . $FB_Shortcode['image_width'].';height:' . $FB_Shortcode['image_height'].';';
            //  if ($FB_Shortcode['image_position_lr'] !== '-0%' || $FB_Shortcode['image_position_top'] !== '-0%') {
            //     $output .= 'style="right:' . $FB_Shortcode['image_position_lr'] . ';left:' . $FB_Shortcode['image_position_lr'] . ';top:' . $FB_Shortcode['image_position_top'] . '"';

            //  }
            if ($FB_Shortcode['type'] == 'albums') {
                $output .= 'background-image:url(' . $photo_source . ');">';

                //   $output .= '><img border="0" alt="' . $photo_from . '" src="https://graph.facebook.com/' . $photo_source . '/picture"/>';
            } else {
                //  $output .= '><img border="0" alt="' . $photo_from . '" src="' . $photo_source . '"/>';
                $output .= 'background-image:url(' . $photo_source . ');">';
            }
            $output .= '</a>';
        } else {
            $FB_ShortcodePopup = isset($FB_Shortcode['popup']) ? $FB_Shortcode['popup'] : '';
            if ($FB_ShortcodePopup == 'yes' && $FBlink !== 'javascript:;') {
                //   $output = isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-social') . '</a></div> ' : '';

                $output = '<a href="' . $photo_source . '" target="_blank" class="fts-facebook-link-target fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . $photo_from . '" src="' . $photo_source . '"/></a>';

            } else {
                $output = '<a href="' . $FBlink . '" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' . $photo_from . '" src="' . $photo_source . '"/></a>';
            }

        }
        return $output;
    }

    /**
     * FTS Facebook Post Name
     *
     * @param $FBlink
     * @param $FBname
     * @param $FBtype
     * @param null $FBpost_id
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_post_name($FBlink, $FBname, $FBtype, $FBpost_id = NULL) {
        switch ($FBtype) {
            case 'video':
                $FBname = $this->fts_facebook_tag_filter($FBname);
                $output = '<a href="' . $FBlink . '" target="_blank" class="fts-jal-fb-name fb-id' . $FBpost_id . '">' . $FBname . '</a>';
                return $output;
            default:
                $FBname = $this->fts_facebook_tag_filter($FBname);
                $output = '<a href="' . $FBlink . '" target="_blank" class="fts-jal-fb-name">' . $FBname . '</a>';
                return $output;
        }
    }

    /**
     * FTS Facebook Post Description
     *
     * @param $FBdescription
     * @param $FB_Shortcode
     * @param $FBtype
     * @param null $FBpost_id
     * @param null $FBby
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, $FBpost_id = NULL, $FBby = NULL) {
        switch ($FBtype) {
            case 'video':
                $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                $output = '<div class="fts-jal-fb-description fb-id' . $FBpost_id . '">' . $FBdescription . '</div>';
                return $output;
            case 'photo':
                if ($FB_Shortcode['type'] == 'album_photos') {
                    if (array_key_exists('words', $FB_Shortcode)) {
                        $more = isset($more) ? $more : "";
                        $trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
                        $output = '<div class="fts-jal-fb-description fts-non-popup-text">' . $trimmed_content . '</div>';

                        if ($FB_Shortcode['popup'] == 'yes' || $FB_Shortcode['video_album'] == 'yes') {
                            $output .= '<div class="fts-jal-fb-description fts-jal-fb-description-popup" style="display: none;">' . nl2br($FBdescription) . '</div>';
                        }

                        return $output;
                    } elseif (isset($FB_Shortcode['words']) && $FB_Shortcode['words'] !== '0') {
                        $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                        $output = '<div class="fts-jal-fb-description">' . nl2br($FBdescription) . '</div>';
                        return $output;
                    }
                }
            case 'albums':
                if ($FB_Shortcode['type'] == 'albums') {
                    if (array_key_exists('words', $FB_Shortcode)) {
                        $more = isset($more) ? $more : "";
                        $trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
                        $output = '<div class="fts-jal-fb-description">' . $trimmed_content . '</div>';
                        return $output;
                    } else {
                        $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                        $output = '<div class="fts-jal-fb-description">' . nl2br($FBdescription) . '</div>';
                        return $output;
                    }
                } //Do for Default feeds or the video gallery feed
                else {
                    $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                    if (is_array($FB_Shortcode) && array_key_exists('words', $FB_Shortcode) && $FB_Shortcode['words'] !== '0') {
                        $more = isset($more) ? $more : "";
                        $trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
                        $output = '<div class="fts-jal-fb-description">' . $trimmed_content . '</div>';
                    } else {
                        $output = '<div class="fts-jal-fb-description">';
                        $output .= nl2br($FBdescription);
                        $output .= '</div>';
                    }
                    if (!empty($FBlink)) {
                        $output .= '<div>By: <a href="' . $FBlink . '">' . $FBby . '<a/></div>';
                    }
                    if (isset($FB_Shortcode['words']) && $FB_Shortcode['words'] !== '0') {
                        return $output;
                    }
                }
            default:
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
                    // here we trim the words for the links description text... for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
                    if (is_array($FB_Shortcode) && array_key_exists('words', $FB_Shortcode)) {
                        $more = isset($more) ? $more : "";
                        $trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
                        $output = '<div class="jal-fb-description">' . $trimmed_content . '</div>';
                        return $output;
                    } elseif (is_array($FB_Shortcode) && array_key_exists('words', $FB_Shortcode) && $FB_Shortcode['words'] !== '0') {
                        $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                        $output = '<div class="jal-fb-description">' . nl2br($FBdescription) . '</div>';
                        return $output;
                    }
                } //END is_plugin_active
                // if the premium plugin is not active we will just show the regular full description
                else {
                    $FBdescription = $this->fts_facebook_tag_filter($FBdescription);
                    $output = '<div class="jal-fb-description">' . nl2br($FBdescription) . '</div>';
                    return $output;
                }
        }
    }

    /**
     * FTS Facebook Post Caption
     *
     * @param $FBcaption
     * @param $FB_Shortcode
     * @param $FBtype
     * @param null $FBpost_id
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype, $FBpost_id = NULL) {

        switch ($FBtype) {
            case 'video':
                $FBcaption = $this->fts_facebook_tag_filter(str_replace('www.', '', $FBcaption));
                $output = '<div class="fts-jal-fb-caption fb-id' . $FBpost_id . '">' . $FBcaption . '</div>';
                return $output;
            default:
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
                    // here we trim the words for the links description text... for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
                    if (array_key_exists('words', $FB_Shortcode)) {
                        $more = isset($more) ? $more : "";
                        $trimmed_content = $this->fts_custom_trim_words($FBcaption, $FB_Shortcode['words'], $more);
                        $output = '<div class="jal-fb-caption">' . $trimmed_content . '</div>';
                    } else {
                        $FBcaption = $this->fts_facebook_tag_filter($FBcaption);
                        $output = '<div class="jal-fb-caption">' . nl2br($FBcaption) . '</div>';
                    }
                } //END is_plugin_active
                // if the premium plugin is not active we will just show the regular full description
                else {
                    $FBcaption = $this->fts_facebook_tag_filter($FBcaption);
                    $output = '<div class="jal-fb-caption">' . nl2br($FBcaption) . '</div>';
                }
                return $output;
        }
    }

    /**
     * Get Likes Shares Comments
     *
     * Get the total count for all.
     *
     * @param $response_post_array
     * @param $post_data_key
     * @param $FBpost_share_count
     * @return array
     * @since 1.9.6
     */
    function get_likes_shares_comments($response_post_array, $post_data_key, $FBpost_share_count) {
        $LSC_array = array();
        //Get Likes & Comments
        if ($response_post_array) {
            if (isset($response_post_array[$post_data_key . '_likes'])) {
                $like_count_data = json_decode($response_post_array[$post_data_key . '_likes']);


                //Like Count
                if (!empty($like_count_data->summary->total_count)) {
                    $FBpost_like_count = $like_count_data->summary->total_count;
                } else {
                    $FBpost_like_count = 0;
                }
                if ($FBpost_like_count == '0') {
                    $LSC_array['likes'] = "";
                }
                if ($FBpost_like_count == '1') {
                    $LSC_array['likes'] = "<i class='icon-thumbs-up'></i> 1";
                }
                if ($FBpost_like_count > '1') {
                    $LSC_array['likes'] = "<i class='icon-thumbs-up'></i> " . $FBpost_like_count;
                }
            }
            if (isset($response_post_array[$post_data_key . '_comments'])) {
                $comment_count_data = json_decode($response_post_array[$post_data_key . '_comments']);

                if (!empty($comment_count_data->summary->total_count)) {
                    $FBpost_comments_count = $comment_count_data->summary->total_count;
                } else {
                    $FBpost_comments_count = 0;
                }
                if ($FBpost_comments_count == '0') {
                    $LSC_array['comments'] = "";
                }
                if ($FBpost_comments_count == '1') {
                    $LSC_array['comments'] = "<i class='icon-comments'></i> 1";
                    $LSC_array['comments_thread'] =  $comment_count_data;

                }
                if ($FBpost_comments_count > '1') {
                    $LSC_array['comments'] = "<i class='icon-comments'></i> " . $FBpost_comments_count;
                    $LSC_array['comments_thread'] =  $comment_count_data;
                }
            }
        }
        //Shares Count
        if ($FBpost_share_count == '0' or !$FBpost_share_count) {
            $LSC_array['shares'] = "";
        }
        if ($FBpost_share_count == '1') {
            $LSC_array['shares'] = "<i class='icon-file'></i> 1";
        }
        if ($FBpost_share_count > '1') {
            $LSC_array['shares'] = "<i class='icon-file'></i> " . $FBpost_share_count;
        }
        return $LSC_array;
    }



    /**
     * FTS Facebook Post See More
     *
     * Generate See More Button.
     *
     * @param $FBlink
     * @param $lcs_array
     * @param $FBtype
     * @param null $FBpost_id
     * @param $FB_Shortcode
     * @param null $FBpost_user_id
     * @param null $FBpost_single_id
     * @param null $single_event_id
     * @param $post_data
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_post_see_more($FBlink, $lcs_array, $FBtype, $FBpost_id = NULL, $FB_Shortcode, $FBpost_user_id = NULL, $FBpost_single_id = NULL, $single_event_id = null, $post_data) {

        $description = isset($post_data->message) ? $post_data->message : '';
        //  echo'<pre>';
        //  print_r();
        //  echo'</pre>';

        $share_this = new feed_them_social_functions();
        switch ($FBtype) {
            case 'events':
                $single_event_id = 'https://facebook.com/events/' . $single_event_id;
                $fts_share_option = $share_this->fts_share_option($single_event_id, $description);
                $output = '<div class="fts-likes-shares-etc-wrap">'.$fts_share_option.'<a href="'.$single_event_id.'" target="_blank" class="fts-jal-fb-see-more">' . __('View on Facebook', 'feed-them-social') . '</a></div>';
                return $output;
            case 'photo':
                if (!empty($FBlink)) {
                    $fts_share_option = $share_this->fts_share_option($FBlink, $description);
                    $output = '<div class="fts-likes-shares-etc-wrap">'.$fts_share_option.'<a href="' . $FBlink . '" target="_blank" class="fts-jal-fb-see-more">';
                } // exception for videos
                else {
                    $single_video_id = 'https://facebook.com/' . $FBpost_id;
                    $fts_share_option = $share_this->fts_share_option($single_video_id, $description);
                    $output = '<div class="fts-likes-shares-etc-wrap">'.$fts_share_option.'<a href="'.$single_video_id.'" target="_blank" class="fts-jal-fb-see-more">';
                }
                if ($FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['hide_date_likes_comments'] == 'yes') {
                    $output .= '<div class="hide-date-likes-comments-etc">' . $lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' ' . $lcs_array['shares'] . ' &nbsp;&nbsp;</div>';
                } else {
                    $output .= '' . $lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' ' . $lcs_array['shares'] . ' &nbsp;&nbsp;';
                }
                $output .= '&nbsp;' . __('View on Facebook', 'feed-them-social') . '</a></div>';
                return $output;
            case 'app':
            case 'cover':
            case 'profile':
            case 'mobile':
            case 'wall':
            case 'normal':
            case 'album':
            case 'events':
                $url_parsed = parse_url($FBlink, PHP_URL_QUERY);
                parse_str($url_parsed, $params);
                $new_album_url = str_replace('album.php?fbid=' . $params['fbid'] . '&id=' . $params['id'] . '&aid=' . $params['aid'], 'media/set/?set=a.' . $params['fbid'] . '.' . $params['aid'] . '.' . $params['id'], $FBlink);

                $fts_share_option = $share_this->fts_share_option($new_album_url, $description);

                $output = '<div class="fts-likes-shares-etc-wrap">'.$fts_share_option.'<a href="' . $new_album_url . '" target="_blank" class="fts-jal-fb-see-more">';
                if ($FB_Shortcode['type'] = 'albums' && $FB_Shortcode['hide_date_likes_comments'] == 'yes') {
                } else {
                    $output .= '' . $lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' &nbsp;&nbsp;';
                }
                $output .= '&nbsp;' . __('View on Facebook', 'feed-them-social') . '</a></div>';
                return $output;
            default:

                // $output = $this->fts_share_option($FBlink);

                if ($FB_Shortcode['type'] == 'reviews' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                    $output = '';
                    $fb_reviews_see_more_reviews_language = get_option('fb_reviews_see_more_reviews_language') ? get_option('fb_reviews_see_more_reviews_language') : 'See More Reviews';

                    $hide_see_more = isset($FB_Shortcode['hide_see_more_reviews_link']) ? $FB_Shortcode['hide_see_more_reviews_link'] : 'yes';
                    if($hide_see_more !== 'yes') {
                        $output .= ' <a href="https://facebook.com/' . $FB_Shortcode['id'] . '/reviews" target="_blank" class="fts-jal-fb-see-more">' . __($fb_reviews_see_more_reviews_language, 'feed-them-social') . '</a>';
                    }
                } else {
                    $post_single_id = 'https://facebook.com/' . $FBpost_user_id . '/posts/' . $FBpost_single_id;
                    $fts_share_option = $share_this->fts_share_option($post_single_id, $description);
                    $output = '<div class="fts-likes-shares-etc-wrap">'.$fts_share_option.'<a href="'.$post_single_id.'" target="_blank" class="fts-jal-fb-see-more">';
                    $output .= '' . $lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' &nbsp;&nbsp;&nbsp;' . __('View on Facebook', 'feed-them-social') . '</a></div>';
                }
                if(get_option('fb_reviews_remove_see_reviews_link') !== 'yes' ){
                    return $output;
                }
        }
    }

    /**
     * Get Access Token
     *
     * @return mixed
     * @since 1.9.6
     */
    function get_access_token() {
        //API Access Token
        $custom_access_token = get_option('fts_facebook_custom_api_token');
        if (!empty($custom_access_token)) {
            $access_token = get_option('fts_facebook_custom_api_token');
            return $access_token;
        } else {
            //Randomizer
            $values = array(
                '431287540548931|4A23YYIFqhd-gpz_E4Fy6U_Seo0',
                '1748446362151826|epVUmLiKT8QhLN63iRvvXXHwxqk',
                '1875381106044241|KmWz3mtzGye0M5HTdX0SK7rqpIU',
                '754106341419549|AMruxCJ_ly8825VXeLhBKo_kOfs',
                '438563519819257|1GJ8GLl1AQ7ZTvXV_Xpok_QpH6s',
                '753693994788276|xm_PXoNRWW8WPQdcQArRpBgWn5Q',
                '644818402385988|sABEvG0QiOaJRlNLC2NphfQLlfg',
                '292500071162951|9MA-kzWVs6HTEybpdxKjgF_gqeo',
                '263710677420086|Jpui2CFig7RbtdHaHPN_fiEa77U',
                '1850081601881384|u2JcPCn7TH40MY5BwC-i4PMHGm8',
            );
            $access_token = $values[array_rand($values, 1)];
            return $access_token;
        }
    }

    /**
     * Get View Link
     *
     * @param $FB_Shortcode
     * @return string
     * @since 1.9.6
     */
    function get_view_link($FB_Shortcode) {
        switch ($FB_Shortcode['type']) {
            case 'group' :
                $fts_view_fb_link = 'https://www.facebook.com/groups/' . $FB_Shortcode['id'] . '/';
                break;
            case 'page':
                $fts_view_fb_link = 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/';
                break;
            case 'event' :
                $fts_view_fb_link = 'https://www.facebook.com/events/' . $FB_Shortcode['id'] . '/';
                break;
            case 'events' :
                $fts_view_fb_link = 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/events/';
                break;
            case 'albums':
                $fts_view_fb_link = 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/photos_stream?tab=photos_albums';
                break;
            // album photos and videos album
            case 'album_photos':
                $fts_view_fb_link = isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ? 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/videos/' : 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/photos_stream/';
                break;
            case 'hashtag':
                $fts_view_fb_link = 'https://www.facebook.com/hashtag/' . $FB_Shortcode['id'] . '/';
                break;
            case 'reviews':
                $fts_view_fb_link = 'https://www.facebook.com/' . $FB_Shortcode['id'] . '/reviews/';
                break;
        }
        $fts_view_fb_link =  isset($fts_view_fb_link) ? $fts_view_fb_link : '';
        return $fts_view_fb_link;
    }

    /**
     * Get FB Cache Name
     *
     * @param $FB_Shortcode
     * @return string
     * @since 1.9.6
     */
    function get_fb_cache_name($FB_Shortcode) {
        //URL to get page info
        $rCount = substr_count($FB_Shortcode['id'], ",");

        if($rCount >= 1){
            $result = preg_replace('/[ ,]+/', '-', trim($FB_Shortcode['id']));
            $FB_Shortcode['id'] = $result;
        }

        switch ($FB_Shortcode['type']) {
            case 'album_photos':
                $fb_data_cache_name = 'fb_' . $FB_Shortcode['type'] . '_' . $FB_Shortcode['id'] . '_' . $FB_Shortcode['album_id'] . '_num' . $FB_Shortcode['posts'] . '';
                break;
            default:
                $fb_data_cache_name = 'fb_' . $FB_Shortcode['type'] . '_' . $FB_Shortcode['id'] . '_num' . $FB_Shortcode['posts'] . '';
                break;
        }
        return $fb_data_cache_name;
    }

    /**
     * Get Language
     *
     * @return string
     * @since 1.9.6
     */
    function get_language() {
        //this check is in place because we used this option and it failed for many people because we use wp get contents instead of curl
        // this can be removed in a future update and just keep the $language_option = get_option('fb_language', 'en_US');
        $language_option_check = get_option('fb_language');
        if (isset($language_option_check) && $language_option_check !== 'Please Select Option') {
            $language_option = get_option('fb_language', 'en_US');
        } else {
            $language_option = 'en_US';
        }
        $language = !empty($language_option) ? '&locale=' . $language_option : '';
        return $language;
    }

    /**
     * Get Facebook Overall Rating Response
     *
     * @param $FB_Shortcode
     * @param $fb_cache_name
     * @param $access_token
     * @param $language
     * @return array|mixed
     * @since 2.1.3
     */
    function get_facebook_overall_rating_response($FB_Shortcode, $fb_cache_name, $access_token) {


        //   $mulit_data_rating = $this->fts_get_feed_json($mulit_data_rating);

        //Error Check
        //   $feed_data_rating_overall = json_decode($mulit_data['rating_data']);

        $fb_reviews_overall_rating_of_5_stars_text = get_option('fb_reviews_overall_rating_of_5_stars_text');
        $fb_reviews_overall_rating_of_5_stars_text = !empty($fb_reviews_overall_rating_of_5_stars_text) ? ' ' . $fb_reviews_overall_rating_of_5_stars_text : ' of 5 stars';
        $fb_reviews_overall_rating_reviews_text = get_option('fb_reviews_overall_rating_reviews_text');
        $fb_reviews_overall_rating_reviews_text = !empty($fb_reviews_overall_rating_reviews_text) ? ' ' . $fb_reviews_overall_rating_reviews_text : ' reviews';
        $fb_reviews_overall_rating_background_border_hide = get_option('fb_reviews_overall_rating_background_border_hide');
        $fb_reviews_overall_rating_background_border_hide = !empty($fb_reviews_overall_rating_background_border_hide) && $fb_reviews_overall_rating_background_border_hide == 'yes' ? ' fts-review-details-master-wrap-no-background-or-border' : '';

        $FTS_FB_OUTPUT = '<div class="fts-review-details-master-wrap' . $fb_reviews_overall_rating_background_border_hide . '"><i class="fts-review-star">' . $feed_data_rating_overall->overall_star_rating . ' &#9733;</i>';
        $FTS_FB_OUTPUT .= '<div class="fts-review-details-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="fts-review-details"><span itemprop="ratingValue">' . $feed_data_rating_overall->overall_star_rating . '</span>' . $fb_reviews_overall_rating_of_5_stars_text . '</div>';
        $FTS_FB_OUTPUT .= '<div class="fts-review-details-count"><span itemprop="reviewCount">' . $feed_data_rating_overall->rating_count . '</span>' . $fb_reviews_overall_rating_reviews_text . '</div></div></div>';

        // $fb_cache_name = $FB_Shortcode['id'] . $this->rand_string(10);

        //Make sure it's not ajaxing
        // if (!isset($_GET['load_more_ajaxing'])) {
        //Create Cache
        //     $FTS_FB_OUTPUT = $this->fts_create_feed_cache($fb_cache_name, $feed_data_rating_overall);
        // }
        return $FTS_FB_OUTPUT;
    }


    /**
     * Get Facebook Feed Response
     *
     * @param $FB_Shortcode
     * @param $fb_cache_name
     * @param $access_token
     * @param $language
     * @return array|mixed
     * @since 1.9.6
     */
    function get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language) {

        if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php')) {
            $ftsCountIds = substr_count($FB_Shortcode['id'], ",");
        }
        else{
            $ftsCountIds = '';
        }

        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($fb_cache_name)) and !isset($_GET['load_more_ajaxing'])) {
            $response = $this->fts_get_feed_cache($fb_cache_name);
        } else {
            //Page
            if ($FB_Shortcode['type'] == 'page' && $FB_Shortcode['posts_displayed'] == 'page_only') {
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=id,name,description&access_token=' . $access_token . $language . '');

                if(!$ftsCountIds >= 1) {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/posts?fields=id,caption,attachments,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,with_tags,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
                else{
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/posts?ids=' . $FB_Shortcode['id'] . '&fields=id,caption,attachments,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,with_tags,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }

            } //Event
            elseif ($FB_Shortcode['type'] == 'events') {
                date_default_timezone_set(get_option('fts-timezone'));
                $date = date('Y-m-d');
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=id,name&access_token=' . $access_token . $language . '');
                //Check If Ajax next URL needs to be used
                if(!$ftsCountIds >= 1) {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/events?since=' . $date . '&access_token=' . $access_token . $language . '';
                }
                else {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/events?ids=' . $FB_Shortcode['id'] . '&since=' . $date . '&access_token=' . $access_token . $language . '';
                }
            } //Albums
            elseif ($FB_Shortcode['type'] == 'albums') {
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=id,name,description,link&access_token=' . $access_token . $language . '');
                //Check If Ajax next URL needs to be used
                if(!$ftsCountIds >= 1) {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/albums?fields=id,photos{webp_images},created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
                else {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/albums?ids=' . $FB_Shortcode['id'] . '&fields=id,photos{webp_images},created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }

//                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/albums?fields=id,created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
            } //Album Photos
            elseif ($FB_Shortcode['type'] == 'album_photos') {
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=id,name,description&access_token=' . $access_token . $language . '');
                //Check If Ajax next URL needs to be used
                //The reason I did not create a whole new else if for the video album is because I did not want to duplicate all the code required to make the video because the videos gallery comes from the photo albums on facebook.
                if (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                    if(!$ftsCountIds >= 1) {
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/videos?fields=id,created_time,description,from,icon,link,message,object_id,picture,place,shares,source,to,type,format,embed_html&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                    else{
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/videos?ids=' . $FB_Shortcode['id'] . '&fields=id,created_time,description,from,icon,link,message,object_id,picture,place,shares,source,to,type,format,embed_html&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                } elseif (isset($FB_Shortcode['album_id']) && $FB_Shortcode['album_id'] == 'photo_stream') {
                    if(!$ftsCountIds >= 1) {
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                    else{
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/photos?ids=' . $FB_Shortcode['id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                } else {
                    if(!$ftsCountIds >= 1) {
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['album_id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                    else {
                        $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/photos?ids=' . $FB_Shortcode['album_id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                    }
                }
            } //HashTag
            elseif ($FB_Shortcode['type'] == 'hashtag') {
                $mulit_data = array(
                    'page_data' => 'https://graph.facebook.com/search?q=%23' . $FB_Shortcode['id'] . '&access_token=' . $access_token . $language . ''
                );
                //Check If Ajax next URL needs to be used
                $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/search?q=%23' . $FB_Shortcode['id'] . '&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                //Check If Ajax next URL needs to be used
            } //Group
            elseif ($FB_Shortcode['type'] == 'group') {
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=id,name,description&access_token=' . $access_token . $language . '');
                //Check If Ajax next URL needs to be used
                if(!$ftsCountIds >= 1) {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/feed?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
                else{
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/feed?ids=' . $FB_Shortcode['id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
            } //Reviews
            elseif ($FB_Shortcode['type'] == 'reviews') {
                if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                    $FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
                    $mulit_data = $FTS_Facebook_Reviews->review_connection($FB_Shortcode, $access_token, $language);

                    $mulit_data['ratings_data'] = 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/?fields=overall_star_rating,rating_count&access_token=' . $access_token . '';

                } else {
                    return 'Please Purchase and Activate the Feed Them Social Reviews plugin.';
                    exit;
                }
            } else {
                $mulit_data = array('page_data' => 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '?fields=feed,id,name,description&access_token=' . $access_token . $language . '');
                //Check If Ajax next URL needs to be used
                if(!$ftsCountIds >= 1) {
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/feed?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
                else{
                    $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/feed?ids=' . $FB_Shortcode['id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $FB_Shortcode['posts'] . '&access_token=' . $access_token . $language . '';
                }
            }
            $response = $this->fts_get_feed_json($mulit_data);

            if (!isset($_GET['load_more_ajaxing'])) {
                //Error Check
                $feed_data = json_decode($response['feed_data']);
                $fts_error_check = new fts_error_handler();
                $fts_error_check_complete = $fts_error_check->facebook_error_check($FB_Shortcode, $feed_data);
                if (is_array($fts_error_check_complete) && $fts_error_check_complete[0] == true) {
                    return array(false, $fts_error_check_complete[1]);
                }
            }

            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing']) && !empty($response['feed_data'])) {
                //Create Cache
                $this->fts_create_feed_cache($fb_cache_name, $response);
            }
        } // end main else


        //RETURN THE RESPONSE!!!
        return $response;


    }


    /**
     * Get Facebook Feed Dynamic Name
     *
     * @param $FB_Shortcode
     * @return mixed
     * @since 1.9.6
     */
    function get_facebook_feed_dynamic_name($FB_Shortcode) {
        return $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);
    }

    /**
     * Get Facebook Feed Dynamic Class Name
     *
     * @param null $fts_dynamic_name
     * @return string
     * @since 1.9.6
     */
    function get_facebook_feed_dynamic_class_name($fts_dynamic_name = null) {
        $fts_dynamic_class_name = '';
        if (isset($fts_dynamic_name)) {
            $fts_dynamic_class_name = 'feed_dynamic_class' . $_REQUEST['fts_dynamic_name'];
        }
        return $fts_dynamic_class_name;
    }


    /**
     * Get Post Info
     *
     * For Facebook.
     *
     * @param $feed_data
     * @param $FB_Shortcode
     * @param $access_token
     * @param $language
     * @return array|mixed
     * @since 1.9.6
     */
    function get_post_info($feed_data, $FB_Shortcode, $access_token, $language) {
        //  $developer_mode = get_option('fts_clear_cache_developer_mode') == TRUE ? get_option('fts_clear_cache_developer_mode') : '900';
        $developer_mode = get_option('fts_clear_cache_developer_mode');

        if ($FB_Shortcode['type'] == 'album_photos') {
            $fb_post_data_cache = 'fb_' . $FB_Shortcode['type'] . '_post_' . $FB_Shortcode['album_id'] . '_num' . $FB_Shortcode['posts'] . '';
        }
        else {
            $fb_post_data_cache = 'fb_' . $FB_Shortcode['type'] . '_post_' . $FB_Shortcode['id'] . '_num' . $FB_Shortcode['posts'] . '';
        }

        // if (file_exists($fb_post_data_cache) && !filesize($fb_post_data_cache) == 0 && filemtime($fb_post_data_cache) > time() - 900 && false !== strpos($fb_post_data_cache, '-num' . $FB_Shortcode['posts'] . '') && !isset($_GET['load_more_ajaxing']) && $developer_mode !== '1') {
        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($fb_post_data_cache)) and !isset($_GET['load_more_ajaxing'])) {
            $response_post_array = $this->fts_get_feed_cache($fb_post_data_cache);
        }
        else {
            //Build the big post counter.
            $fb_post_array = array();
            //Single Events Array
            $set_zero = 0;
            foreach ($feed_data->data as $counter) {

                $counter->id = isset($counter->id) ? $counter->id : "";

                if ($set_zero == $FB_Shortcode['posts'])
                    break;

                $FBtype = isset($counter->type) ? $counter->type : "";
                $post_data_key = isset($counter->object_id) ? $counter->object_id : $counter->id;

                //Likes & Comments
                $fb_post_array[$post_data_key . '_likes'] = 'https://graph.facebook.com/' . $post_data_key . '/reactions?summary=1&access_token=' . $access_token;
                $fb_post_array[$post_data_key . '_comments'] = 'https://graph.facebook.com/' . $post_data_key . '/comments?summary=1&access_token=' . $access_token;
                //Video
                if ($FBtype == 'video') {
                    $fb_post_array[$post_data_key . '_video'] = 'https://graph.facebook.com/' . $post_data_key;
                }
                //Photo
                $FBalbum_cover = isset($counter->cover_photo->id) ? $counter->cover_photo->id : "";
                if ($FB_Shortcode['type'] == 'albums' && !$FBalbum_cover) {
                    unset($counter);
                    continue;
                }
                if ($FB_Shortcode['type'] == 'albums') {
                    $fb_post_array[$FBalbum_cover . '_photo'] = 'https://graph.facebook.com/' . $FBalbum_cover;
                }
                if ($FB_Shortcode['type'] == 'hashtag') {
                    $fb_post_array[$post_data_key . '_photo'] = 'https://graph.facebook.com/' . $counter->source;
                }
                //GROUP Photo
                if ($FB_Shortcode['type'] == 'group') {
                    $fb_post_array[$post_data_key . '_group_post_photo'] = 'https://graph.facebook.com/' . $counter->id . '?fields=picture,full_picture&access_token=' . $access_token;
                }

                $set_zero++;
            }


            //Response
            $response_post_array = $this->fts_get_feed_json($fb_post_array);
            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {
                //Create Cache
                $this->fts_create_feed_cache($fb_post_data_cache, $response_post_array);
            }

        } //End else
        //  echo'<pre>';
        //   print_r($response_post_array);
        //  echo'</pre>';
        return $response_post_array;
    }


    /**
     * Get Post Info
     *
     * For Facebook.
     *
     * @param $feed_data
     * @param $FB_Shortcode
     * @param $access_token
     * @param $language
     * @return array|mixed
     * @since 2.1.6
     */
    function get_event_post_info($feed_data, $FB_Shortcode, $access_token, $language) {
        //  $developer_mode = get_option('fts_clear_cache_developer_mode') == TRUE ? get_option('fts_clear_cache_developer_mode') : '900';
        $developer_mode = get_option('fts_clear_cache_developer_mode');

        $fb_event_post_data_cache = 'fbe_' . $FB_Shortcode['type'] . '_post_' . $FB_Shortcode['id'] . '_num' . $FB_Shortcode['posts'] . '';
        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($fb_event_post_data_cache)) and !isset($_GET['load_more_ajaxing'])) {
            $response_event_post_array = $this->fts_get_feed_cache($fb_event_post_data_cache);
        }
        else {
            //Single Events Array
            $fb_single_events_array = array();
            $set_zero = 0;
            foreach ($feed_data->data as $counter) {

                $counter->id = isset($counter->id) ? $counter->id : "";

                if ($set_zero == $FB_Shortcode['posts'])
                    break;

                $single_event_id = $counter->id;
                $language = isset($language) ? $language : '';
                //Event Info, Time etc
                $fb_single_events_array['event_single_' . $single_event_id . '_info'] = 'https://graph.facebook.com/' . $single_event_id . '/?access_token=' . $access_token . $language;
                //Event Location
                $fb_single_events_array['event_single_' . $single_event_id . '_location'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=place&access_token=' . $access_token . $language;
                //Event Cover Photo
                $fb_single_events_array['event_single_' . $single_event_id . '_cover_photo'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=cover&access_token=' . $access_token . $language;
                //Event Ticket Info
                $fb_single_events_array['event_single_' . $single_event_id . '_ticket_info'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=ticket_uri&access_token=' . $access_token . $language;

                $set_zero++;
            }



            $response_event_post_array = $this->fts_get_feed_json($fb_single_events_array);
            //Create Cache
            $this->fts_create_feed_cache($fb_event_post_data_cache, $response_event_post_array);



        } //End else
        //  echo'<pre>';
        //  print_r($response_event_post_array);
        //  echo'</pre>';
        return $response_event_post_array;
    }


    /**
     * FB Social Button Placement
     *
     * @param $FB_Shortcode
     * @param $access_token
     * @param $share_loc
     * @return string|void
     * @since 2.0.1
     */
    function fb_social_btn_placement($FB_Shortcode, $access_token, $share_loc) {
        //Don't do it for these!
        if ($FB_Shortcode['type'] == 'group' || $FB_Shortcode['type'] == 'event' || isset($FB_Shortcode['hide_like_option']) && $FB_Shortcode['hide_like_option'] == 'yes') {
            return;
        }
        //Facebook Follow Button Options
        $fb_show_follow_btn = get_option('fb_show_follow_btn');

        if (isset($FB_Shortcode['show_follow_btn_where']) && $FB_Shortcode['show_follow_btn_where'] !== '') {
            // $fb_show_follow_btn = 'yes';
            if ($FB_Shortcode['show_follow_btn_where'] == 'above_title') {
                $fb_show_follow_btn_where = 'fb-like-top-above-title';
            } elseif ($FB_Shortcode['show_follow_btn_where'] == 'below_title') {
                $fb_show_follow_btn_where = 'fb-like-top-below-title';
            } elseif ($FB_Shortcode['show_follow_btn_where'] == 'bottom') {
                $fb_show_follow_btn_where = 'fb-like-below';
            }
        } else {
            $fb_show_follow_btn_where = get_option('fb_show_follow_btn_where');
        }


        if (!isset($_GET['load_more_ajaxing'])) {

            $like_option_align_final = isset($FB_Shortcode['like_option_align']) ? 'fts-fb-social-btn-' . $FB_Shortcode['like_option_align'] . '' : '';

            $output = '';
            if ($share_loc === $fb_show_follow_btn_where) {
                switch ($fb_show_follow_btn_where) {
                    case 'fb-like-top-above-title':
                        // Top Above Title
                        if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
                            $output .= '<div class="fb-social-btn-top ' . $like_option_align_final . '">';
                            $output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token, $FB_Shortcode);
                            $output .= '</div>';
                        }
                        break;
                    //Top Below Title
                    case 'fb-like-top-below-title':
                        if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
                            $output .= '<div class="fb-social-btn-below-description ' . $like_option_align_final . '">';
                            $output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token, $FB_Shortcode);
                            $output .= '</div>';
                        }
                        break;
                    //Bottom
                    case 'fb-like-below':
                        if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
                            $output .= '<div class="fb-social-btn-bottom ' . $like_option_align_final . '">';
                            $output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token, $FB_Shortcode);
                            $output .= '</div>';
                        }
                        break;
                }
            }
            return $output;
        }
    }

    /**
     * FTS Custom Trim Words
     *
     * @param $text
     * @param int $num_words
     * @param $more
     * @return mixed
     * @since 1.9.6
     */
    function fts_custom_trim_words($text, $num_words = 45, $more) {
        !empty($num_words) && $num_words !== 0 ? $more = __('...') : '';
        $text = nl2br($text);
        //Filter for Hashtags and Mentions Before returning.
        $text = $this->fts_facebook_tag_filter($text);
        $text = strip_shortcodes($text);
        // Add tags that you don't want stripped
        $text = strip_tags($text, '<strong><br><em><i><a>');
        $words_array = preg_split("/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
        $sep = ' ';
        if (count($words_array) > $num_words) {
            array_pop($words_array);
            $text = implode($sep, $words_array);
            $text = $text . $more;
        } else {
            $text = implode($sep, $words_array);
        }
        return wpautop($text);
    }

    /**
     * FTS Facebook Tag Filter
     *
     * Tags Filter (return clean tags)
     *
     * @param $FBdescription
     * @return mixed
     * @since 1.9.6
     */
    function fts_facebook_tag_filter($FBdescription) {
        //Converts URLs to Links
        $FBdescription = preg_replace('@(?!(?!.*?<a)[^<]*<\/a>)(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-‌​Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0" target="_blank">\0</a>', $FBdescription);

        $splitano = explode("www", $FBdescription);
        $count = count($splitano);
        $returnValue = "";

        for ($i = 0; $i < $count; $i++) {
            if (substr($splitano[$i], -6, 5) == "href=") {
                $returnValue .= $splitano[$i] . "http://www";
            } else if ($i < $count - 1) {
                $returnValue .= $splitano[$i] . "www";
            } else {
                $returnValue .= $splitano[$i];
            }
        }
        // Mentions
        $returnValue = preg_replace('/[@]+([0-9\p{L}]+)/u', '<a target="_blank" href="https://facebook.com/$1">@$1</a>', $returnValue);
        //Hash tags
        $returnValue = preg_replace('/[#]+([0-9\p{L}]+)/u', '<a target="_blank" href="https://facebook.com/hashtag/$1">#$1</a>', $returnValue);

        return $returnValue;
    }

    /**
     * Rand String
     *
     * Create a random string so divs can have custom classes and ids
     *
     * @param int $length
     * @return string
     * @since 1.9.6
     */
    function rand_string($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Load PopUp Scripts
     *
     * @param $FB_Shortcode
     * @since 1.9.6
     */
    function load_popup_scripts($FB_Shortcode) {
        if ($FB_Shortcode['popup'] == 'yes') {
            // it's ok if these styles & scripts load at the bottom of the page
            $fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
            if (isset($fts_fix_magnific) && $fts_fix_magnific !== '1') {
                wp_enqueue_style('fts-popup', plugins_url('feed-them-social/feeds/css/magnific-popup.css'));
            }
            wp_enqueue_script('fts-popup-js', plugins_url('feed-them-social/feeds/js/magnific-popup.js'));
            wp_enqueue_script('fts-images-loaded', plugins_url('feed-them-social/feeds/js/imagesloaded.pkgd.min.js'));
            if (!isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                wp_enqueue_script('fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array('jquery'));
            }
        }
    }

    /**
     * FTS Facebook LoadMore
     *
     * @param $atts
     * @param $feed_data
     * @param $FBtype
     * @param $FB_Shortcode
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_loadmore($atts, $feed_data, $FBtype, $FB_Shortcode) {
        $LOADMORE_OUPUT = '';
        if ((isset($FB_Shortcode['loadmore']) && $FB_Shortcode['loadmore'] == 'button' || isset($FB_Shortcode['loadmore']) && $FB_Shortcode['loadmore'] == 'autoscroll') && (is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews')) {

            $fb_load_more_text = get_option('fb_load_more_text') ? get_option('fb_load_more_text') : __('Load More', 'feed-them-social');
            $fb_no_more_posts_text = get_option('fb_no_more_posts_text') ? get_option('fb_no_more_posts_text') : __('No More Posts', 'feed-them-social');
            $fb_no_more_photos_text = get_option('fb_no_more_photos_text') ? get_option('fb_no_more_photos_text') : __('No More Photos', 'feed-them-social');
            $fb_no_more_videos_text = get_option('fb_no_more_videos_text') ? get_option('fb_no_more_videos_text') : __('No More Videos', 'feed-them-social');
            $fb_no_more_reviews_text = get_option('fb_no_more_reviews_text') ? get_option('fb_no_more_reviews_text') : __('No More Reviews', 'feed-them-social');
            //******************
            //Load More BUTTON Start
            //******************

            $next_url = isset($feed_data->paging->next) ? $feed_data->paging->next : "";
            $posts = isset($FB_Shortcode['posts']) ? $FB_Shortcode['posts'] : "";
            $loadmore_count = isset($FB_Shortcode['loadmore_count']) && $FB_Shortcode['loadmore_count'] !=='' ? $FB_Shortcode['loadmore_count'] :'';
            // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of posts
            $_REQUEST['next_url'] = $loadmore_count !== '' ? str_replace("limit=$posts","limit=$loadmore_count",$next_url) : $next_url;


            //If events array Flip it so it's in proper order
            if ($FB_Shortcode['type'] == 'events') {
                $key_needed = isset($set_zero);
                $single_event_id = isset($data->data[$key_needed]->id);
                $single_event_info = json_decode($single_event_array_response['event_single_' . $single_event_id . '_info']);
                $FB_event_start_time = date('Y-m-d', strtotime($single_event_info->start_time));
                if (isset($FB_event_start_time) && $FB_event_start_time !== '1969-12-31') {
                    $_REQUEST['next_url'] = isset($data->paging->next) ? 'https://graph.facebook.com/' . $FB_Shortcode['id'] . '/events?since=' . $FB_event_start_time . '&access_token=' . $access_token . $language . '' : "";
                } else {
                    $_REQUEST['next_url'] = 'no more';
                }
            }
            $LOADMORE_OUPUT .= '<script>';
            $LOADMORE_OUPUT .= 'var nextURL_' . $_REQUEST['fts_dynamic_name'] . '= "' . $_REQUEST['next_url'] . '";';
            $LOADMORE_OUPUT .= '</script>';
            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts']) && !empty($FB_Shortcode['loadmore'])) {
                $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
                $time = time();
                $nonce = wp_create_nonce($time . "load-more-nonce");
                $fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
                $LOADMORE_OUPUT .= '<script>';
                $LOADMORE_OUPUT .= 'jQuery(document).ready(function() {';
                if ($FB_Shortcode['loadmore'] == 'autoscroll') {
                    // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").bind("scroll",function() {';
                    $LOADMORE_OUPUT .= 'if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {';
                } else {
                    // this is where we do CLICK function to LOADMORE if  = button in shortcode
                    $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").click(function() {';
                }
                $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").addClass("fts-fb-spinner");';
                $bounce = "<div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div>";
                $LOADMORE_OUPUT .= 'var button = jQuery("#loadMore_' . $fts_dynamic_name . '").html("' . $bounce . '");';
                $LOADMORE_OUPUT .= 'console.log(button);';

                $LOADMORE_OUPUT .= 'var yes_ajax = "yes";';
                $LOADMORE_OUPUT .= 'var fts_d_name = "' . $fts_dynamic_name . '";';
                $LOADMORE_OUPUT .= 'var fts_security = "' . $nonce . '";';
                $LOADMORE_OUPUT .= 'var fts_time = "' . $time . '";';

                $LOADMORE_OUPUT .= 'var feed_name = "fts_facebook";';
                $LOADMORE_OUPUT .= 'var loadmore_count = "posts='.$FB_Shortcode['loadmore_count'].'";';
                $LOADMORE_OUPUT .= 'var feed_attributes = ' . json_encode($atts) . ';';


                $LOADMORE_OUPUT .= 'jQuery.ajax({';
                $LOADMORE_OUPUT .= 'data: {action: "my_fts_fb_load_more", next_url: nextURL_' . $fts_dynamic_name . ', fts_dynamic_name: fts_d_name, feed_name: feed_name, loadmore_count: loadmore_count, feed_attributes: feed_attributes, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},';
                $LOADMORE_OUPUT .= 'type: "GET",';
                $LOADMORE_OUPUT .= 'url: "'.admin_url('admin-ajax.php').'",';
                $LOADMORE_OUPUT .= 'success: function( data ) {';
                $LOADMORE_OUPUT .= 'console.log("Well Done and got this from sever: " + data);';
                if ($FBtype && $FB_Shortcode['type'] == 'albums' || $FBtype && $FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['video_album'] !== 'yes' || $FB_Shortcode['grid'] == 'yes') {
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").append(data).filter(".' . $fts_dynamic_class_name . '").html();';
                    //   if (isset($FB_Shortcode['image_stack_animation']) && $FB_Shortcode['image_stack_animation'] == 'yes') {
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").masonry( "reloadItems");';
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").masonry("layout");';

                    $LOADMORE_OUPUT .= 'setTimeout(function() {';
                    // Do something after 3 seconds
                    // This can be direct code, or call to some other function
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").masonry("layout");';
                    $LOADMORE_OUPUT .= '}, 500);';


                    //   }
                    $LOADMORE_OUPUT .= 'if(!nextURL_' . $_REQUEST['fts_dynamic_name'] . ' || nextURL_' . $_REQUEST['fts_dynamic_name'] . ' == "no more"){';
                    if ($FB_Shortcode['type'] == 'reviews') {
                        $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $fb_no_more_reviews_text . '</div>\');';
                    } elseif ($FB_Shortcode['type'] == 'videos') {
                        $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $fb_no_more_videos_text . '</div>\');';
                    } else {
                        $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $fb_no_more_photos_text . '</div>\');';
                    }

                    $LOADMORE_OUPUT .= ' jQuery("#loadMore_' . $fts_dynamic_name . '").removeAttr("id");';
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").unbind("scroll");';
                    $LOADMORE_OUPUT .= '}';
                } else {
                    if (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                        $LOADMORE_OUPUT .= 'var result = jQuery(data).insertBefore( jQuery("#output_' . $fts_dynamic_name . '") );';
                        $LOADMORE_OUPUT .= 'var result = jQuery(".feed_dynamic_' . $fts_dynamic_name . '_album_photos").append(data).filter("#output_' . $fts_dynamic_name . '").html();';
                    } else {
                        $LOADMORE_OUPUT .= 'var result = jQuery("#output_' . $fts_dynamic_name . '").append(data).filter("#output_' . $fts_dynamic_name . '").html();';
                    }
                    $LOADMORE_OUPUT .= 'jQuery("#output_' . $fts_dynamic_name . '").html(result);';
                    $LOADMORE_OUPUT .= 'if(!nextURL_' . $_REQUEST['fts_dynamic_name'] . ' || nextURL_' . $_REQUEST['fts_dynamic_name'] . ' == "no more"){';
                    //Reviews
                    if ($FB_Shortcode['type'] == 'reviews') {
                        $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $fb_no_more_reviews_text . '</div>\');';
                    } else {
                        $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $fb_no_more_posts_text . '</div>\');';
                    }
                    $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").removeAttr("id");';
                    $LOADMORE_OUPUT .= 'jQuery(".' . $fts_dynamic_class_name . '").unbind("scroll");';
                    $LOADMORE_OUPUT .= '}';

                }
                $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").html("' . $fb_load_more_text . '");';
                //jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("flip360-fts-load-more");
                $LOADMORE_OUPUT .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").removeClass("fts-fb-spinner");';
                if (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
                 // We return this function again otherwise the popup won't work correctly for the newly loaded items
                 $LOADMORE_OUPUT .= 'jQuery.fn.slickFacebookPopUpFunction();';
                }
                //Reload the share each funcion otherwise you can't open share option.
                $LOADMORE_OUPUT .= 'jQuery.fn.ftsShare();slickremixImageResizingFacebook2();slickremixImageResizingFacebook3();';

                $LOADMORE_OUPUT .= '}';
                $LOADMORE_OUPUT .= '});';// end of ajax()
                $LOADMORE_OUPUT .= 'return false;';
                // string $scrollMore is at top of this js script. acception for scroll option closing tag
                if ($FB_Shortcode['loadmore'] == 'autoscroll') {
                    $LOADMORE_OUPUT .= '}';// end of scroll ajax load.
                }
                $LOADMORE_OUPUT .= '});';// end of document.ready
                $LOADMORE_OUPUT .= '});';// end of form.submit
                $LOADMORE_OUPUT .= '</script>';
            }//End Check
            // main closing div not included in ajax check so we can close the wrap at all times
            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {
                $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
                // this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;"
                $LOADMORE_OUPUT .= '<div id="output_' . $fts_dynamic_name . '" class="fts-fb-load-more-output"></div>';
                if ((is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews') && $FB_Shortcode['loadmore'] == 'autoscroll') {
                    $LOADMORE_OUPUT .= '<div id="loadMore_' . $fts_dynamic_name . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Facebook</div>';
                }
            }
        }// end of if loadmore is button or autoscroll
        return $LOADMORE_OUPUT;
    }//End Loadmore/Scroll
}// FTS_Facebook_Feed END CLASS
?>