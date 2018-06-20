<?php

namespace feedthemsocial;
/**
 * Class FTS Youtube Feed
 *
 * @package feedthemsocial
 */
class FTS_Youtube_Feed_Free extends feed_them_social_functions
{
    /**
     * Construct
     *u
     * FTS Youtube Feed constructor.
     *
     * @since 2.3.2
     */
    function __construct() {
        add_shortcode('fts_youtube', array($this, 'fts_youtube_func'));

        add_action('wp_enqueue_scripts', array($this, 'fts_youtube_head'));
    }

    /**
     * FTS Youtube Head
     *
     * @since 2.3.2
     */
    function fts_youtube_head() {
        wp_enqueue_style('fts-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            $fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
            if (isset($fts_fix_magnific) && $fts_fix_magnific !== '1') {
                wp_enqueue_style('fts-popup', plugins_url('feed-them-social/feeds/css/magnific-popup.css'));
            }
            wp_enqueue_script('fts-popup-js', plugins_url('feed-them-social/feeds/js/magnific-popup.js'));
        }
    }


    /**
     * FTS Youtube Functions
     *
     * @param $atts
     * @return string
     * @since 2.3.2
     */
    function fts_youtube_func($atts) {
        global $channel_id, $playlist_id, $username_subscribe_btn, $username;

        $youtubeAPIkey = get_option('youtube_custom_api_token');
       // $youtubeAccessToken = 'asdf';
        $youtubeAccessToken = get_option('youtube_custom_access_token');


        wp_enqueue_script('fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array('jquery'));

        $youtubeAccessTokenNew = '';
        if (!isset($_GET['load_more_ajaxing']) && $youtubeAPIkey == '' && $youtubeAccessToken !== '') {
            // Double Check Our Experiation Time on the Token and refresh it if needed
            $expiration_time = get_option('youtube_custom_token_exp_time');
            // Access token is good for 3600 seconds
            // Give the access token a 5 minute buffer (300 seconds) before getting a new one.
            //  $expiration_time = $expiration_time - 300;
            $expiration_time = $expiration_time - 300;
            if (time() > $expiration_time) {
                $youtubeAccessTokenNew = $this->feed_them_youtube_refresh_token();
             // print $youtubeAccessTokenNew;
             //   print '<br/>ajaxing now to refresh token<br/>';
                if($youtubeAccessToken !== ''){
                  $youtubeAccessToken = $youtubeAccessTokenNew;
             //       print 'New token accepted for feed<br/>';
                };
            }

        }


        // you must create a youtube app now to get this.
        if (!empty($youtubeAccessToken) && empty($youtubeAPIkey)) {
            $youtubeAPIkeyORtoken =  'access_token='.$youtubeAccessToken.'';
        }
        else {
            $youtubeAPIkeyORtoken =  'key='.$youtubeAPIkey.'';
        }



        if($youtubeAPIkey !== '' || $youtubeAccessToken !== ''){

            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/youtube/youtube-feed.php';
            } else {
                extract(shortcode_atts(array(
                    'username' => '',
                    'vid_count' => '1',
                    'large_vid' => '',
                    'thumbs_play_in_iframe' => '',
                    'large_vid_description' => 'yes',
                    'large_vid_title' => 'yes',
                    'vids_in_row' => '4',
                    'channel_id' => '',
                    'playlist_id' => '',
                    'username_subscribe_btn' => '',
                    'space_between_videos' => '',
                    'force_columns' => 'no',
                    'thumbs_wrap_color' => '',
                    'thumbs_wrap_height' => '',
                    'maxres_thumbnail_images' => '',
                    'video_wrap_display' => '',
                    //for single videos
                    'video_id_or_link' => '',
                    'comments_visible' => '',
                    'comments_count' => '',

                ), $atts));
            }
            if (!is_plugin_active('feed-them-premium/feed-them-premium.php') && $vid_count > '6') {
                $vid_count = '6';
            }
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && !isset($popup)) {
                $popup = 'yes';
                $comments_count = '0';
            }
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($thumbs_play_in_iframe) && $thumbs_play_in_iframe == 'popup') {
                $popup = 'yes';
                $thumbs_play_in_iframe = 'no';
            }
            // YouTube has a limit of 50 per page and if you try to load more the array errors so we make sure that does not happen
            if($vid_count > 50){
                $vid_count = '50';
            }

            // free additions so we don't have to update all the plugins
            extract(shortcode_atts(array(
                'omit_first_thumbnail' => '',

            ), $atts));

            // if omit_first_thumbnail == yes then we make sure and skip the first iteration in the loop
            if ($omit_first_thumbnail == 'yes') {
                $b = false;
                $vid_count = $vid_count + 1;
            }
            else {
                $b = true;
            }





            $youtube_show_follow_btn = get_option('youtube_show_follow_btn');
            $youtube_show_follow_btn_where = get_option('youtube_show_follow_btn_where');
            $share_this = new feed_them_social_functions();

            $thumbs_play_iframe = $thumbs_play_in_iframe;


            //Make sure its not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {
                $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10));
                //Create Dynamic Class Name
                $fts_dynamic_class_name = '';
                if (isset($_REQUEST['fts_dynamic_name'])) {
                    $fts_dynamic_class_name = 'feed_dynamic_class' . $_REQUEST['fts_dynamic_name'];
                }
            }







            // check to see of the user added a full youtube link instead of just the id and if so parse out everything but the id we need.
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_id_or_link, $match)) {
                $video_id_or_link = $match[1];
            }

            if ($video_id_or_link == '') {
                if ($username !== '') {
                    // here we are getting the users channel ID for their uploaded videos
                    $youtube_userID_data['items'] = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=' . $username . '&' . $youtubeAPIkeyORtoken;
                    $userID_returned = $this->fts_get_feed_json($youtube_userID_data);
                    $userIDfinal = json_decode($userID_returned['items']);


                    // now we parse the users uploaded vids ID and create the playlist
                    foreach ($userIDfinal->items as $userID) {
                        $video_data['videos'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $vid_count . '&playlistId=' . $userID->contentDetails->relatedPlaylists->uploads . '&order=date&' . $youtubeAPIkeyORtoken;
                    }

                    $videos_returned = $this->fts_get_feed_json($video_data);
                    $videos = $videos_returned['videos'];

                    //   echo'<pre>';
                    //   print_r($videos_returned);
                    //   echo'</pre>';


                    //Youtube Username Cache Folder
                    if (!isset($_GET['load_more_ajaxing'])) {
                        $youtube_cache_url = 'pics_vids_list_' . $username . '_bnum' . $vid_count . '_user';
                    }
                }
                elseif ($channel_id !== '' && $playlist_id == '') {

                    $youtube_channelID_data['items'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtubeAPIkeyORtoken;
                    $userChannel_returned = $this->fts_get_feed_json($youtube_channelID_data);
                    $videos = $userChannel_returned['items'];


                    $videos_check = json_decode($videos);
                    $set_zero = '';
                    if(isset($videos_check->items)){

                        $set_zero = 0;
                        foreach ($videos_check->items as $post_data) {

                            $kind = isset($post_data->id->kind) ? $post_data->id->kind : "";
                            // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
                            // We will count all the ones that are like this and add that number to the output of posts to offset the posts we are filtering out. Line 278 needs the same treatment of if options.
                            if ($kind == 'youtube#playlist') {
                                $set_zero++;
                            }
                        }// END POST foreach
                    }
                    $unsetCount = $vid_count + $set_zero;
                    $vid_count = $unsetCount;
                    //  Uncomment these for testing purposes to see the actual count and the offset count
                    //  echo'<pre>';
                    //  print_r($set_zero);
                    //  echo'</pre>';
                    //  echo'<pre>';
                    //  print_r('vidcount: '.$vid_count);
                    //  echo'</pre>';
                    //  echo'<pre>';
                    //  print_r($kind);
                    //  echo'</pre>';


                    $youtube_channelID_data['items'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtubeAPIkeyORtoken;
                    $userChannel_returned = $this->fts_get_feed_json($youtube_channelID_data);
                    $videos = $userChannel_returned['items'];


                    if (!isset($_GET['load_more_ajaxing'])) {
                        //Youtube Channel Cache
                        $youtube_cache_url = 'pics_vids_list_' . $channel_id . '_bnum' . $vid_count . '_channel';
                    }


                } elseif ($playlist_id !== '' || $playlist_id !== '' && $channel_id !== '') {
                    $youtube_playlistID_data = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $vid_count . '&playlistId=' . $playlist_id . '&order=date&' . $youtubeAPIkeyORtoken;
                    $video_data['videos'] = $youtube_playlistID_data;
                    $videos_returned = $this->fts_get_feed_json($video_data);
                    $videos = $videos_returned['videos'];
                    if (!isset($_GET['load_more_ajaxing'])) {
                        //Youtube Playlist Cache Folder
                        $youtube_cache_url = 'pics_vids_list_' . $playlist_id . '_bnum' . $vid_count . '_playlist';
                    }
                }
                //Youtube Create Cache
                if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($youtube_cache_url) && !isset($_GET['load_more_ajaxing']) )) {
                    if ($youtubeAccessTokenNew == '') {
                        $videos = $this->fts_get_feed_cache($youtube_cache_url);
                       // print 'cached';
                    }
                    else {
                        $videos = json_decode($videos);
                        $this->fts_create_feed_cache($youtube_cache_url, $videos);
                       // print 're-caching because token expired';
                    }
                } else {
                    $videos = json_decode($videos);

                    if (!isset($_GET['load_more_ajaxing'])) {
                        $this->fts_create_feed_cache($youtube_cache_url, $videos);
                       // print 'caching';
                    }
                }
            }

            $output = '';

            ob_start();

            //***********************
            // SOCIAL BUTTON TOP
            //***********************

            if (!isset($_GET['load_more_ajaxing']) && $video_id_or_link == '') {
                if (isset($youtube_show_follow_btn) && $youtube_show_follow_btn == 'yes' && $youtube_show_follow_btn_where == 'youtube-follow-above' && !isset($_GET['load_more_ajaxing'])) {
                    $output .= '<div class="youtube-social-btn-top">';
                    if ($username !== '' || $username_subscribe_btn !== '') {
                        $output .= $this->social_follow_button('youtube', $username);
                    } elseif ($channel_id !== '') {
                        $output .= $this->social_follow_button('youtube', $channel_id);
                    }
                    $output .= '</div>';
                }
            }
            // we ob_get_clean here so the button is on top and also allows the content in wordpress textarea to be on top if shortcode is below text.
            $output .= ob_get_clean();

            // and we start over so we can ob_get_clean at the very end
            ob_start();
            // This first line was added to fix the bug that happens when using the popular DIVI theme.

            $ssl = is_ssl() ? 'https' : 'http';

            if (!isset($_GET['load_more_ajaxing'])) {

                $video_wrap_display = isset($video_wrap_display) ? $video_wrap_display : '2';

                if ($video_wrap_display == '1') {
                    $video_wrap_display = ' fts-youtube-thumbs-wrap-option-80-20';
                } elseif ($video_wrap_display == '2') {
                    $video_wrap_display = ' fts-youtube-thumbs-wrap-option-60-40';
                } elseif ($video_wrap_display == '3') {
                    $video_wrap_display = ' fts-youtube-thumbs-wrap-option-50-50';
                }

                if (isset($wrap) && $wrap == 'right') {

                    $wrap = ' fts-youtube-thumbs-wrap' . $video_wrap_display . '';

                } elseif (isset($wrap) && $wrap == 'left') {
                    $wrap = ' fts-youtube-thumbs-wrap-left' . $video_wrap_display . '';
                } else {
                    $wrap = '';
                }

                $thumbgalleryClassMaster = isset($video_id_or_link) && $video_id_or_link == '' ? ' fts-youtube-thumbs-gallery-master ' : '';

                $output .= '<div class="et_smooth_scroll_disabled fts_smooth_scroll_disabled">';
                $output .= '<div id="fts-yt-' . $_REQUEST['fts_dynamic_name'] . '" class="' . $thumbgalleryClassMaster . 'fts-master-youtube-wrap fts-yt-videogroup fts-yt-user-' . $username . ' fts-yt-vids-in-row' . $vids_in_row . '">';
                $output .= '<div id="fts-yt-videolist-' . $_REQUEST['fts_dynamic_name'] . '" class="fts-yt-videolist">';

                if ($large_vid == 'yes' || $vids_in_row == '1') {
                    foreach ($videos->items as $post_data) {
                        // we check to make sure no playlist video kinds are in the array ($post_data->id->kind !== 'youtube#playlist') because they return a blank video in the channel feed because youtube is simply adding it to the array for youtube not thinking of the API in this case it would seem.
                        $video_check = isset($post_data->id->kind) && $post_data->id->kind  == 'youtube#playlist' ? 'set' : 'notset';
                        if($video_check !== 'set'){



                            $second_video_margin_btm = $large_vid_title == 'yes' && $large_vid_description !== 'yes'  ? 'fts-youtube-second-video-margin-btm' : '';

                            $output .= '<div class="fts-yt-large' . $wrap . ' '.$second_video_margin_btm.'">';
                            $output .= '<div class="fts-yt-first-video">';


                            if ($large_vid_title == 'yes') {
                                $output .= '<h2>' . $this->fts_youtube_title($post_data) . '</h2>';
                            }

                            $output .= $this->fts_youtube_video_and_wrap($post_data, $username, $playlist_id);


                            $youtube_description = $this->fts_youtube_tag_filter($this->fts_youtube_description($post_data));
                            $large_vid_description = isset($large_vid_description) && $large_vid_description == 'yes' ? $large_vid_description : '';

                            if ($large_vid_description == 'yes') {
                                $output .= '<p>' . $youtube_description . '</p>';
                            }
                            $output .= '</div>';
                            $output .= '</div>';
                            // && $large_vid_title !== 'yes' && $large_vid_description !== 'yes'  are all about being set and if so they we show the oldschool 1 video with title and description format
                            if($thumbs_play_in_iframe !== 'yes' && $large_vid_title !== 'yes' && $large_vid_description !== 'yes' || $thumbs_play_in_iframe !== 'no'){
                                // we stop the foreach loop here because we only want the first video in the loop
                                break;
                            }
                        }

                    }
                }

                $columns = isset($vids_in_row) ? 'data-ftsi-columns="' . $vids_in_row . '" ' : '';
                $force_columns = isset($force_columns) ? 'data-ftsi-force-columns="' . $force_columns . '" ' : 'no';

                $space_between_videos = isset($space_between_videos) && $space_between_videos !== '' ? $space_between_videos : '1px';


                $thumbs_wrap_color_final = isset($thumbs_wrap_color) ? ' style="background:' . $thumbs_wrap_color . '!important"' : '';
                $thumbs_wrap_color_scroll = isset($thumbs_wrap_color) ? 'background:' . $thumbs_wrap_color . '' : '';


                if (isset($video_id_or_link) && $video_id_or_link !== '') {

                    $output .= '<div id="fts-yt-large-' . $video_id_or_link . '" class="fts-yt-large' . $wrap . '">';
                    $output .= '<div class="fts-yt-first-video">';
                    //$output .= '<div class="fts-fluid-videoWrapper"><iframe id="fts-'.$post_data->snippet->resourceId->videoId.'" src="http://www.youtube.com/embed/'.$post_data->snippet->resourceId->videoId.'?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&fs=1&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
                    $output .= '<div class="fts-fluid-videoWrapper">';

                    $output .= '<iframe src="' . $ssl . '://www.youtube.com/embed/' . $video_id_or_link . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0" frameborder="0" allowfullscreen></iframe>';

                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';


                } elseif (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($wrap) && $wrap !== '') {
                    $set_comments_height = is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($wrap) && $wrap !== '' ? 'youtube-comments-wrap-premium ' : '';
                    $output .= '<div class="' . $set_comments_height . 'youtube-comments-wrap' . $wrap . ' youtube-comments-thumbs"  id="fts-yt-comments"></div>';
                }


                if (isset($thumbs_wrap_height) && $thumbs_wrap_height !== '' || isset($wrap) && $wrap !== '') {

                    $output .= '<div class="' . $fts_dynamic_class_name . ' fts-youtube-scrollable' . $wrap . '" style="height:250px;' . $thumbs_wrap_color_scroll . '" >';
                }

                $video_id_or_link_final = isset($video_id_or_link) && $video_id_or_link == '' ? 'data-ftsi-margin=' . $space_between_videos . '' : '';
                $thumbgalleryClass = isset($video_id_or_link) && $video_id_or_link !== '' ? ' fts-youtube-no-thumbs-gallery' : '';


                $output .= '<div ' . $columns . $force_columns . '' . $video_id_or_link_final . ' class="' . $fts_dynamic_class_name . ' fts-youtube-popup-gallery fts-youtube-inline-block-centered ' . $thumbgalleryClass . '" ' . $thumbs_wrap_color_final . '>';


                if (isset($video_id_or_link) && $video_id_or_link !== '') {


                    $youtube_video_url = 'https://www.youtube.com/watch?v=' . $video_id_or_link . '';

                    $set_comments_height = is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($wrap) && $wrap !== '' ? 'youtube-comments-wrap-premium ' : '';

                    if (isset($wrap) && $wrap !== 'right' || isset($wrap) && $wrap !== 'left') {
                        $output .= '<div class="fts-youtube-noscroll">';
                    }

                    $fts_share_option_youtube = $share_this->fts_share_option(isset($youtube_video_url) ? $youtube_video_url : NULL, isset($youtube_title) ? $youtube_title : NULL);
                    $output .= '<div class="' . $set_comments_height . 'youtube-comments-wrap' . $wrap . '"  style="display: block !important;">';


                    $output .= $this->fts_youtube_single_video_info($video_id_or_link, $youtubeAPIkeyORtoken, $youtubeAccessTokenNew);


                    $output .= $fts_share_option_youtube;
                    $output .= '<a href="' . $youtube_video_url . '" target="_blank" class="fts-jal-fb-see-more">' . __('View on YouTube', 'feed-them-premium') . '</a>';
                    if (isset($comments_count) && $comments_count !== '0' && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                        $output .= $this->fts_youtube_commentThreads($video_id_or_link, $youtubeAPIkeyORtoken, $comments_count);
                    }
                    $output .= '</div>';
                    if (isset($wrap) && $wrap !== 'right' || isset($wrap) && $wrap !== 'left') {
                        $output .= '</div>';
                    }


                    $output .= '</div>';


                }


            }
            if ($vids_in_row !== '0' && $video_id_or_link == '' && $large_vid_title !== 'yes' && $large_vid_description !== 'yes' ) {

                $count = '0';
                foreach ($videos->items as $post_data) {
                    $kind = isset($post_data->id->kind) ? $post_data->id->kind : "";
                    // if omit_first_thumbnail == yes then we make sure and skip the first iteration in the loop
                    if(!$b) {
                        $b = true;
                        continue;
                    }

                    // print $omit_first_thumbnail;
                    // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types
                    if ($kind == 'youtube#playlist') {    // 	unset($post_data);
                    }

                    else {

                        $user_name_href = 'https://www.youtube.com/channel/' . $post_data->snippet->channelId;
                        $date = $this->fts_custom_date($post_data->snippet->publishedAt, 'youtube');

                        $thumbnail = isset($post_data->snippet->thumbnails->standard->url) ? $post_data->snippet->thumbnails->standard->url : $post_data->snippet->thumbnails->high->url;

                        $maxres_thumbnail_images = isset($maxres_thumbnail_images) && $maxres_thumbnail_images !== '' ? $maxres_thumbnail_images : '';

                        if (isset($post_data->snippet->thumbnails->maxres->url) && $maxres_thumbnail_images == 'yes') {
                            $thumbnail = $post_data->snippet->thumbnails->maxres->url;
                        } else {
                            $thumbnail = $thumbnail;
                        }


                        if ($username !== '' || $playlist_id !== '') {
                            $vidID = $post_data->snippet->resourceId->videoId;
                        } else {
                            $vidID = isset($post_data->id->videoId) ? $post_data->id->videoId : $post_data->id->playlistId;
                        }


                        $popupSet = isset($wrap) && $wrap !== '' && isset($thumbs_play_in_iframe) && $thumbs_play_in_iframe == 'yes' || !is_plugin_active('feed-them-premium/feed-them-premium.php') ? 'slicker-youtube-placeholder-' . $_REQUEST['fts_dynamic_name'] . ' ' : '';

                        $output .= '<div class="' . $popupSet . 'slicker-youtube-placeholder fts-youtube-' . $vidID . '" data-id="fts-youtube-id-' . $fts_dynamic_class_name . '" style="background-image:url(' . $thumbnail . ')">';

                        $youtube_title = $this->fts_youtube_title($post_data);
                        $youtube_description = $this->fts_youtube_tag_filter($this->fts_youtube_description($post_data));
                        $channelTitle = $post_data->snippet->channelTitle;

                        $url = is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($popup) && $popup == 'yes' && $thumbs_play_iframe !== 'yes' ? ' fts-yt-popup-open' : '';
                        $target = $thumbs_play_iframe == 'yes' ? '' : 'target="_blank"';

                        if ($username !== '' || $playlist_id !== '') { //https://www.youtube.com/watch?v=g9ArG6H_z0Q

                            $youtube_video_url = $ssl . '://www.youtube.com/watch?v=' . $vidID;

                            $href = isset($thumbs_play_iframe) && $thumbs_play_iframe == 'yes' ? 'javascript:;' : $youtube_video_url;

                            $iframe_embed = 'rel="' . $ssl . '://www.youtube.com/embed/' . $vidID . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque"';
                            $iframe = isset($thumbs_play_iframe) && $thumbs_play_iframe == 'yes' ? ' fts-youtube-iframe-click' : '';

                            // $iframe_embed = '<iframe src="'.$ssl . '://www.youtube.com/embed/'.$vidID.'?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque" frameborder="0" allowfullscreen=""></iframe>';
                            $output .= '<a href="' . $href . '" ' . $iframe_embed . ' ' . $target . ' class="fts-yt-open' . $url . '' . $iframe . '"></a>';

                            if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                // $output .= '<div id="#fts-' . $vidID . '" class="fts-yt-overlay-wrap">';
                                $fts_share_option_youtube = $share_this->fts_share_option(isset($youtube_video_url) ? $youtube_video_url : NULL, isset($youtube_title) ? $youtube_title : NULL);

                                $output .= '<div class="entriestitle fts-youtube-popup fts-facebook-popup"><div class="fts-master-youtube-wrap-close fts-yt-close-' . $_REQUEST['fts_dynamic_name'] . '"></div>';
                                $output .= '<h3><a href="' . $user_name_href . '" target="_blank">' . $channelTitle . '</a></h3>';
                                $output .= '<div class="fts-youtube-date">' . $date . '</div>';
                                $output .= '<h4>' . $youtube_title . '</h4>';
                                $output .= '<div class="fts-youtube-description-popup">' . $youtube_description . '</div>';
                                $output .= $fts_share_option_youtube;
                                $output .= '<a href="' . $youtube_video_url . '" target="_blank" class="fts-jal-fb-see-more">' . __('View on YouTube', 'feed-them-premium') . '</a>';
                                if (isset($comments_count) && $comments_count !== '0' && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                    // $output .= 'wtf';
                                    $output .= $this->fts_youtube_commentThreads($vidID, $youtubeAPIkeyORtoken, $comments_count);
                                }
                                $output .= '</div>';
                            }
                        } else {

                            $youtube_video_url = $ssl . '://www.youtube.com/watch?v=' . $vidID;

                            $href = isset($thumbs_play_iframe) && $thumbs_play_iframe == 'yes' ? 'javascript:;' : $youtube_video_url;

                            $iframe_embed = 'rel="' . $ssl . '://www.youtube.com/embed/' . $vidID . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque"';
                            $iframe = isset($thumbs_play_iframe) && $thumbs_play_iframe == 'yes' ? ' fts-youtube-iframe-click' : '';

                            // $iframe_embed = '<iframe src="'.$ssl . '://www.youtube.com/embed/'.$vidID.'?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque" frameborder="0" allowfullscreen=""></iframe>';
                            $output .= '<a href="' . $href . '" ' . $iframe_embed . ' ' . $target . ' class="fts-yt-open' . $url . '' . $iframe . '"></a>';

                            if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                $fts_share_option_youtube = $share_this->fts_share_option(isset($youtube_video_url) ? $youtube_video_url : NULL, isset($youtube_title) ? $youtube_title : NULL);

                                $output .= '<div class="entriestitle fts-youtube-popup fts-facebook-popup"><div class="fts-master-youtube-wrap-close fts-yt-close-' . $_REQUEST['fts_dynamic_name'] . '"></div>';
                                $output .= '<h3><a href="' . $user_name_href . '" target="_blank">' . $channelTitle . '</a></h3>';
                                $output .= '<div class="fts-youtube-date">' . $date . '</div>';
                                $output .= '<h4>' . $youtube_title . '</h4>';
                                $output .= '<div class="fts-youtube-description-popup">' . $youtube_description . '</div>';
                                $output .= $fts_share_option_youtube;
                                $output .= '<a href="' . $youtube_video_url . '" target="_blank" class="fts-jal-fb-see-more">' . __('View on YouTube', 'feed-them-premium') . '</a>';
                                if (isset($comments_count) && $comments_count !== '0' && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                    $output .= $this->fts_youtube_commentThreads($vidID, $youtubeAPIkeyORtoken, $comments_count);
                                }
                                $output .= '</div>';
                            }
                        }
                        $output .= '</div>';
                    };
                    $count++;
                    if ($count == $vid_count) break;
                }
            }


            if ($video_id_or_link == '') {

                //******************
                //Load More BUTTON Start
                //******************

                $youtube_load_more_text = get_option('youtube_load_more_text') ? get_option('youtube_load_more_text') : __('Load More', 'feed-them-social');
                $youtube_no_more_videos_text = get_option('youtube_no_more_videos_text') ? get_option('youtube_no_more_videos_text') : __('No More Videos', 'feed-them-social');


                if ($username !== '') {
                    // now we parse the users uploaded vids ID and create the playlist
                    foreach ($userIDfinal->items as $userID) {
                        $next_url = isset($videos->nextPageToken) ? 'https://www.googleapis.com/youtube/v3/playlistItems?pageToken=' . $videos->nextPageToken . '&part=snippet&maxResults=' . $vid_count . '&playlistId=' . $userID->contentDetails->relatedPlaylists->uploads . '&order=date&' . $youtubeAPIkeyORtoken : '';
                    }
                } elseif ($channel_id !== '' && $playlist_id == '') {
                    $next_url = isset($videos->nextPageToken) ? 'https://www.googleapis.com/youtube/v3/search?pageToken=' . $videos->nextPageToken . '&part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtubeAPIkeyORtoken : '';
                } elseif ($playlist_id !== '' || $playlist_id !== '' && $channel_id !== '') {
                    $next_url = isset($videos->nextPageToken) ? 'https://www.googleapis.com/youtube/v3/playlistItems?pageToken=' . $videos->nextPageToken . '&part=snippet&maxResults=' . $vid_count . '&playlistId=' . $playlist_id . '&order=date&' . $youtubeAPIkeyORtoken : '';
                }

                if (isset($loadmore) && $loadmore !== '' && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                    $loadmore_count = isset($loadmore_count) ? $loadmore_count : '';
                    // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of pics/videos
                    $_REQUEST['next_url'] = isset($loadmore_count) && $loadmore_count !== '' ? str_replace("maxResults=$vid_count", "maxResults=$loadmore_count", $next_url) : $next_url;

                        $output .= '<script>';
                        $output .= 'var nextURL_' . $_REQUEST['fts_dynamic_name'] . '= "' . $_REQUEST['next_url'] . '";';
                        $output .= '</script>';
                }
                //Make sure it's not ajaxing
                if (!isset($_GET['load_more_ajaxing']) && is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($loadmore) && $loadmore !== '' ) {
                    $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
                    $time = time();
                    $nonce = wp_create_nonce($time . "load-more-nonce");
                    $fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
                    $output .= '<script>';
                    $output .= 'jQuery(document).ready(function() {';
                    if ($loadmore == 'autoscroll') {
                        // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode
                        $output .= 'jQuery(".' . $fts_dynamic_class_name . '").bind("scroll",function() {';
                        $output .= 'if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {';

                        $output .= 'jQuery(".' . $fts_dynamic_class_name . '").unbind("scroll");';

                    } else {
                        // this is where we do CLICK function to LOADMORE if  = button in shortcode
                        $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").unbind().click(function() {';
                    }
                    $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").addClass("fts-fb-spinner");';
                    $bounce = "<div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div>";
                    $output .= 'var button = jQuery("#loadMore_' . $fts_dynamic_name . '").html("' . $bounce . '");';
                    $output .= 'console.log(button);';
                    $output .= 'console.log(nextURL_' . $fts_dynamic_name . ');';
                    $output .= 'var yes_ajax = "yes";';
                    $output .= 'var fts_d_name = "' . $fts_dynamic_name . '";';
                    $output .= 'var fts_security = "' . $nonce . '";';
                    $output .= 'var fts_time = "' . $time . '";';

                    $output .= 'var feed_name = "fts_youtube";';
                    $output .= 'var loadmore_count = "vid_count=' . $loadmore_count . '";';
                    $output .= 'var feed_attributes = ' . json_encode($atts) . ';';


                    $output .= 'jQuery.ajax({';
                    $output .= 'data: {action: "my_fts_fb_load_more", next_url: nextURL_' . $fts_dynamic_name . ', fts_dynamic_name: fts_d_name, feed_name: feed_name, loadmore_count: loadmore_count, feed_attributes: feed_attributes, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},';
                    $output .= 'type: "GET",';
                    $output .= 'url: "' . admin_url('admin-ajax.php') . '",';
                    $output .= 'success: function( data ) {';
                    $output .= 'console.log("Well Done and got this from sever: " + data);';


                    $output .= 'var result = jQuery(".fts-youtube-popup-gallery.' . $fts_dynamic_class_name . '").append(data).filter(".fts-youtube-popup-gallery.' . $fts_dynamic_class_name . '").html();';

                    $output .= 'jQuery(".fts-youtube-popup-gallery.' . $fts_dynamic_class_name . '").html(result);';
                    $output .= 'if(!nextURL_' . $_REQUEST['fts_dynamic_name'] . ' || nextURL_' . $_REQUEST['fts_dynamic_name'] . ' == "no more"){';

                    $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . $youtube_no_more_videos_text . '</div>\');';

                    $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").removeAttr("id");';
                    $output .= 'jQuery(".' . $fts_dynamic_class_name . '").unbind("scroll");';
                    $output .= '}';

                    if ($loadmore == 'button') {
                        $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").html("' . $youtube_load_more_text . '");';
                    }
                    //jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("flip360-fts-load-more");
                    $output .= 'jQuery("#loadMore_' . $fts_dynamic_name . '").removeClass("fts-fb-spinner");';
                    if (isset($popup) && $popup == 'yes') {
                        // We return this function again otherwise the popup won't work correctly for the newly loaded items
                        $output .= 'jQuery.fn.slickYoutubePopUpFunction();';
                    }
                    //Reload the share each funcion otherwise you can't open share option.
                    $output .= 'jQuery.fn.ftsShare();';
                    // Reload our margin for the demo
                    $output .= 'if(typeof outputSRmargin === "function"){outputSRmargin(document.querySelector("#margin").value)}';
                    $output .= 'slickremixImageResizingYouTube();'; // Reload our imagesizing function so the images show up proper

                    $output .= '}';
                    $output .= '});';// end of ajax()
                    $output .= 'return false;';
                    // string $scrollMore is at top of this js script. acception for scroll option closing tag
                    if ($loadmore == 'autoscroll') {
                        $output .= '}';// end of scroll ajax load.
                    }
                    $output .= '});';// end of document.ready
                    $output .= '});';// end of form.submit
                    $output .= '</script>';

                }//End Check
                // for gallery option play_video_in_iframe
                if(isset($thumbs_play_in_iframe) && $thumbs_play_in_iframe == 'yes' && !isset($_GET['load_more_ajaxing'])){
                    $output .= '<script>';

                    $output .= '  jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . '").unbind().on("click", ".slicker-youtube-placeholder", function(event) {
                    event.stopPropagation();
                    jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .youtube-comments-thumbs").animate({ scrollTop: 0 }, "fast");
                    jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .youtube-comments-thumbs").show();
                    jQuery( "#fts-yt-' . $_REQUEST['fts_dynamic_name'] . '.fts-youtube-scrollable" ).addClass( "fts-scrollable-function" );
                    jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .fts-youtube-scrollable, #fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .fts-fb-autoscroll-loader").hide();
                    var this_frame = jQuery(this).find("a.fts-youtube-iframe-click").attr("rel");
                    jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .fts-fluid-videoWrapper iframe").attr("src", this_frame);
                    var findText = jQuery(this).find(".entriestitle").clone(true, true);
                    findText.appendTo("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .youtube-comments-thumbs");
                    jQuery.fn.ftsShare();
                    
                    });
                    jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . '").on("click", ".fts-yt-close-' . $_REQUEST['fts_dynamic_name'] . '", function(event) {
                        event.stopPropagation();
                        jQuery( "#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .fts-youtube-scrollable" ).removeClass( "fts-scrollable-function" );
                        jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .youtube-comments-thumbs").hide();
                        jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .fts-youtube-scrollable, .fts-fb-autoscroll-loader").show();
                        jQuery("#fts-yt-' . $_REQUEST['fts_dynamic_name'] . ' .youtube-comments-thumbs").html("");
                         slickremixImageResizingYouTube();
                    });';
                    $output .= '</script>';
                }
            }// END if($video_id_or_link == '')
            // main closing div not included in ajax check so we can close the wrap at all times
            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {
                $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
                // this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;"
                // $output .= '<div id="output_' . $fts_dynamic_name . '" class="fts-fb-load-more-output"></div>';


                $output .= '</div><!--END main wrap for thumbnails-->'; // END main wrap for thumbnails

                if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $loadmore == 'autoscroll') {
                    $output .= '<div id="loadMore_' . $fts_dynamic_name . '" class="fts-fb-load-more fts-fb-autoscroll-loader" '.$thumbs_wrap_color_final.'></div>';
                }
                if (isset($thumbs_wrap_height) && $thumbs_wrap_height !== '' || isset($wrap) && $wrap !== '') {
                    $output .= '</div>'; // End If scroll
                }

                $output .= '</div>'; // End fts-yt-videolist
                $output .= '</div>'; // fts-master-youtube-wrap
                $output .= '</div>'; // End DIVI theme .et_smooth_scroll_disabled

            }


            //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {
                $output .= '<div class="fts-clear"></div>';
                if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                    if ($loadmore == 'button') {

                        $output .= '<div class="fts-youtube-load-more-wrapper">';
                        $output .= '<div id="loadMore_' . $_REQUEST['fts_dynamic_name'] . '" style="';
                        if (isset($loadmore_btn_maxwidth) && $loadmore_btn_maxwidth !== '') {
                            $output .= 'max-width:' . $loadmore_btn_maxwidth . ';';
                        }
                        $loadmore_btn_margin = isset($loadmore_btn_margin) ? $loadmore_btn_margin : '20px';
                        $output .= 'margin:' . $loadmore_btn_margin . ' auto ' . $loadmore_btn_margin . '" class="fts-fb-load-more">' . $youtube_load_more_text . '</div>';
                        $output .= '</div>';
                    }
                }


            }//End Check

            unset($_REQUEST['next_url']);

            //***********************
            // SOCIAL BUTTON BOTTOM
            //***********************
            if (isset($youtube_show_follow_btn) && $youtube_show_follow_btn == 'yes' && $youtube_show_follow_btn_where == 'youtube-follow-below' && !isset($_GET['load_more_ajaxing'])) {
                $output .= '<div class="youtube-social-btn-bottom">';


                if ($username !== '' || $username_subscribe_btn !== '') {
                    $output .= $this->social_follow_button('youtube', $username);
                } elseif ($channel_id !== '') {
                    $output .= $this->social_follow_button('youtube', $channel_id);
                }
                $output .= '</div>';
            }

            $output .= ob_get_clean();

            return $output;

        }
        else {
            print 'Please add an access token to the Youtube Options page of Feed Them Social.';
        }
    }

    /**
     * Random String generator
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
     * FTS YouTube Tag Filter
     *
     * Tags Filter (return clean tags)
     *
     * @param $FBdescription
     * @return mixed
     * @since 1.9.6
     */
    function fts_youtube_tag_filter($YouTubeDescription) {
        //Create links from @mentions and regular links.
        $YouTubeDescription = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $YouTubeDescription);
        $YouTubeDescription = preg_replace('/[#]+([0-9\p{L}]+)/u', '<a href="https://www.youtube.com/results?search_query=%23$1" target="_blank">$0</a>', $YouTubeDescription);
        return nl2br($YouTubeDescription);
    }

    /*
     * @since 2.3.2
     */
    function fts_youtube_commentThreads($videoID, $youtubeAPIkeyORtoken, $comments_count) {

        if (!isset($_GET['load_more_ajaxing'])) {
            //Youtube Comment Cache
            $youtube_comments_cache_url = 'video_comments_list_' . $videoID . '_number_comments_' . $comments_count . '';
        }

        //Youtube Use Comments Cache
        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($youtube_comments_cache_url) && !isset($_GET['load_more_ajaxing']))) {
            $comments = $this->fts_get_feed_cache($youtube_comments_cache_url);
        } else {
            //https://developers.google.com/youtube/v3/docs/comments/list
            $comments['items'] = 'https://www.googleapis.com/youtube/v3/commentThreads?' . $youtubeAPIkeyORtoken . '&textFormat=plainText&part=snippet&videoId=' . $videoID . '&maxResults=' . $comments_count . '';
            $comments_returned = $this->fts_get_feed_json($comments);
            $comments = json_decode($comments_returned['items']);

            if (!isset($_GET['load_more_ajaxing'])) {
                $this->fts_create_feed_cache($youtube_comments_cache_url, $comments);
            }
        }
        // echo'<pre>';
        // print_r($comments);
        //  echo'</pre>';
        if($comments->pageInfo->totalResults  !== 0) {
            $output = '';
            $output .= '<div class="fts-fb-comments-content">';
            foreach ($comments->items as $comment_data) {
                $message = $comment_data->snippet->topLevelComment->snippet->textDisplay;
                if ($message !== '><!!') {

                    $youtube_comment = $this->fts_youtube_tag_filter($message);

                    $output .= '<div class="fts-fb-comment">';
                    $output .= '<a href="' . $comment_data->snippet->topLevelComment->snippet->authorChannelUrl . '" target="_blank" class="">';
                    $output .= '<img src="' . $comment_data->snippet->topLevelComment->snippet->authorProfileImageUrl . '" class="fts-fb-comment-user-pic"/>';
                    $output .= '</a>';
                    $output .= '<div class="fts-fb-comment-msg">';
                    $output .= '<span class="fts-fb-comment-user-name">';
                    $output .= '<a href="' . $comment_data->snippet->topLevelComment->snippet->authorChannelUrl . '" target="_blank" class="">';
                    $output .= $comment_data->snippet->topLevelComment->snippet->authorDisplayName;
                    $output .= '</a>';
                    $output .= '</span> ';
                    $output .= '<span class="fts-fb-comment-date">' . $fts_date_time = $this->fts_custom_date($comment_data->snippet->topLevelComment->snippet->publishedAt, 'youtube') . '</span><br/>';
                    $output .= $youtube_comment;
                    $output .= '</div>';
                    $output .= '</div>';
                }

            }
            $output .= '</div>';
        }
        else {
            $output = '';
        }
        return $output;
    }

    /*
     * @since 2.3.2
     */
    function fts_youtube_single_video_info($videoID, $youtubeAPIkeyORtoken, $youtubeAccessTokenNew) {

        if (!isset($_GET['load_more_ajaxing'])) {
            //Youtube Comment Cache
            $youtube_single_video_cache_url = 'video_single_' . $videoID . '';
        }
        //https://developers.google.com/youtube/v3/docs/comments/list
        $video['items'] = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoID . '&' . $youtubeAPIkeyORtoken . '&part=snippet';

        //Youtube Use Comments Cache
        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($youtube_single_video_cache_url) && !isset($_GET['load_more_ajaxing']))) {

            if ($youtubeAccessTokenNew == '') {
                $video = $this->fts_get_feed_cache($youtube_single_video_cache_url);
               // print 'cached';
            }
            else {
                $video_returned = $this->fts_get_feed_json($video);
                $video = json_decode($video_returned['items']);
              //  print 're-caching because token expired';

                if (!isset($_GET['load_more_ajaxing'])) {
                    $this->fts_create_feed_cache($youtube_single_video_cache_url, $video);
                }
            }
        }
        else {

            $video_returned = $this->fts_get_feed_json($video);
            $video = json_decode($video_returned['items']);

            if (!isset($_GET['load_more_ajaxing'])) {
                $this->fts_create_feed_cache($youtube_single_video_cache_url, $video);
            }
        }
        //  echo'<pre>';
        //  print_r($video);
        //  echo'</pre>';

        $output = '';
        foreach ($video->items as $video_data) {
            $user_name_href = 'https://www.youtube.com/channel/' . $video_data->snippet->channelId;
            $channelTitle = $video_data->snippet->channelTitle;
            $youtube_title = $this->fts_youtube_title($video_data);
            $youtube_description = $this->fts_youtube_tag_filter($this->fts_youtube_description($video_data));
            $date = $this->fts_custom_date($video_data->snippet->publishedAt, 'youtube');
          //  $date = $video_data->snippet->publishedAt;
            $output .= '<div class="entriestitle fts-youtube-popup fts-facebook-popup"  style="display: block !important;">';
            $output .= '<h3><a href="' . $user_name_href . '" target="_blank">' . $channelTitle . '</a></h3>';
            $output .= '<div class="fts-youtube-date">' . $date . '</div>';
            $output .= '<h4>' . $youtube_title . '</h4>';
            $output .= '<div class="fts-youtube-description-popup">' . $youtube_description . '</div>';


        }
        return $output;
    }
}
?>