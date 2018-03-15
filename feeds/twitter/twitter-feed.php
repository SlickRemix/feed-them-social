<?php

namespace feedthemsocial;
class FTS_Twitter_Feed extends feed_them_social_functions {
    /**
     * Construct
     * Added Since 9/28/2016 https://dev.twitter.com/overview/api/upcoming-changes-to-tweets
     *
     * Twitter Feed constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
        add_shortcode('fts_twitter', array($this, 'fts_twitter_func'));
        add_action('wp_enqueue_scripts', array($this, 'fts_twitter_head'));
    }

    /**
     * FTS Twitter Head
     *
     * Add Styles and Scripts functions.
     *
     * @since 1.9.6
     */
    function fts_twitter_head() {
        wp_enqueue_style('fts-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));

        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            wp_enqueue_script('fts-masonry-pkgd', plugins_url('feed-them-social/feeds/js/masonry.pkgd.min.js'), array('jquery'));
            wp_enqueue_script('fts-images-loaded', plugins_url('feed-them-social/feeds/js/imagesloaded.pkgd.min.js'));
        }
        // masonry snippet in fts-global
        wp_enqueue_script('fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array('jquery'));
    }

    function fts_twitter_load_videos($post_data) {
        // if (!wp_verify_nonce($_REQUEST['fts_security'], $_REQUEST['fts_time'] . 'load-more-nonce')) {
        //     exit('Sorry, You can\'t do that!');
        // } else {

        if (isset($post_data->quoted_status->entities->media[0]->type)) {
            $tFinal = isset($post_data->quoted_status->entities->media[0]->expanded_url) ? $post_data->quoted_status->entities->media[0]->expanded_url : "";
            $post_data->id = isset($post_data->quoted_status->id) ? $post_data->quoted_status->id : "";
        } else {
            $tFinal = isset($post_data->entities->urls[0]->expanded_url) ? $post_data->entities->urls[0]->expanded_url : "";
        }


        $tFinal_retweet_video = isset($post_data->retweeted_status->entities->media[0]->expanded_url) ? $post_data->retweeted_status->entities->media[0]->expanded_url : "";


        //strip Vimeo URL then ouput Iframe

        if (strpos($tFinal, 'vimeo') > 0) {
            if (strpos($tFinal, 'staffpicks') > 0) {
                $parsed_url = $tFinal;
                // var_dump(parse_url($parsed_url));
                $parsed_url = parse_url($parsed_url);
                $vimeoURLfinal = preg_replace('/\D/', '', $parsed_url["path"]);
            } else {
                $vimeoURLfinal = (int)substr(parse_url($tFinal, PHP_URL_PATH), 1);
                // echo $vimeoURLfinal;
            }

            // echo $vimeoURLfinal;
            return '<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/' . $vimeoURLfinal . '?autoplay=0" class="video" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        } //strip Vimeo Staffpics URL then ouput Iframe


        elseif (strpos($tFinal, 'vine') > 0 && !strpos($tFinal, '-vine') > 0) {
            // $pattern = str_replace( array( 'https://vine.co/v/', '/', 'http://vine.co/v/'), '', $tFinal);
            // $vineURLfinal = $pattern;
            return '<div class="fts-fluid-videoWrapper"><iframe height="281" class="fts-vine-embed" src="' . $tFinal . '/embed/simple" frameborder="0"></iframe></div>';
        } //strip Youtube URL then ouput Iframe and script
        elseif (strpos($tFinal, 'youtube') > 0 && !strpos($tFinal, '-youtube') > 0) {
            $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
            preg_match($pattern, $tFinal, $matches);
            $youtubeURLfinal = $matches[1];

            return '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="http://www.youtube.com/embed/' . $youtubeURLfinal . '?autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        } //strip Youtube URL then ouput Iframe and script
        elseif (strpos($tFinal, 'youtu.be') > 0) {
            $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
            preg_match($pattern, $tFinal, $matches);
            $youtubeURLfinal = $matches[1];

            return '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="http://www.youtube.com/embed/' . $youtubeURLfinal . '?autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        } //strip Youtube URL then ouput Iframe and script
        elseif (strpos($tFinal, 'soundcloud') > 0) {
            //Get the JSON data of song details with embed code from SoundCloud oEmbed
            $getValues = file_get_contents('http://soundcloud.com/oembed?format=js&url=' . $tFinal . '&auto_play=false&iframe=true');
            //Clean the Json to decode
            $decodeiFrame = substr($getValues, 1, -2);
            //json decode to convert it as an array
            $jsonObj = json_decode($decodeiFrame);

            return '<div class="fts-fluid-videoWrapper">' . $jsonObj->html . '</div>';
        } else {
            include_once(WP_CONTENT_DIR . '/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php');

            $fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
            $fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');

            $test_fts_twitter_custom_consumer_key = '35mom6axGlf60ppHJYz1dsShc';
            $test_fts_twitter_custom_consumer_secret = '7c2TJvUT7lS2EkCULpK6RGHrgXN1BA4oUi396pQEdRj3OEq5QQ';

            $fts_twitter_custom_consumer_key = isset($fts_twitter_custom_consumer_key) && $fts_twitter_custom_consumer_key !== '' ? $fts_twitter_custom_consumer_key : $test_fts_twitter_custom_consumer_key;
            $fts_twitter_custom_consumer_secret = isset($fts_twitter_custom_consumer_secret) && $fts_twitter_custom_consumer_secret !== '' ? $fts_twitter_custom_consumer_secret : $test_fts_twitter_custom_consumer_secret;

            $fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
            $fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');

            //Use custom api info
            if (!empty($fts_twitter_custom_access_token) && !empty($fts_twitter_custom_access_token_secret)) {
                $connection = new TwitterOAuthFTS(
                //Consumer Key
                    $fts_twitter_custom_consumer_key,
                    //Consumer Secret
                    $fts_twitter_custom_consumer_secret,
                    //Access Token
                    $fts_twitter_custom_access_token,
                    //Access Token Secret
                    $fts_twitter_custom_access_token_secret
                );
            } //else use default info
            else {
                $connection = new TwitterOAuthFTS(
                //Consumer Key
                    '4UUpTLglrsvQMjmrfdgdtHEEJ',
                    //Consumer Secret
                    'ngtRtVKRvcY4e2lZHHkKNc63JPMn8SnOw1bM0jd6Fv8H5C3phP',
                    //Access Token
                    '1561334624-CSmnb3JqhKctSGzYfB5ouf3GmR9Pne1fR2q9PzY',
                    //Access Token Secret
                    'CH2Ojl5G4sgn8kUaBIEhy6M0UUvBWs1CrYW8sh1fpCQXT'
                );
            }
            //  if (strpos($tFinal, 'amp.twimg.com') > 0) {

            $reg_video = $post_data->id;
            // bug about mixed content made by srl
            // https://twittercommunity.com/t/bug-with-mixed-content-in-embedded-tweet-with-video/77507
            $videosDecode = $reg_video;
            $fetchedTweets2 = $connection->get(
                'statuses/oembed',
                array(
                    'id' => $videosDecode,
                    'widget_type' => 'video',
                    'hide_tweet' => true,
                    'hide_thread' => true,
                    'hide_media' => false,
                    'omit_script' => false,
                )
            );


            // print $tFinal;

            return $fetchedTweets2->html;
            //   } else {
            //      exit('That is not allowed. FTS!');
            //  }
        } //strip Vine URL then ouput Iframe and script


        //  } // end main else
        //  die();
    } // end function

    function fts_twitter_description($post_data) {

        $text = isset($post_data->retweeted_status->full_text) ? $post_data->retweeted_status->full_text : $post_data->full_text;

        // Message. Convert links to real links.
        $pattern = array('/http:(\S)+/', '/https:(\S)+/', '/[@]+([0-9\p{L}]+)/u', '/[#]+([0-9\p{L}]+)/u');
        $replace = array(' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="https://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="https://twitter.com/hashtag/$1?src=hash" target="_blank" rel="nofollow">#$1</a>');
        $full_text = preg_replace($pattern, $replace, $text);

        return nl2br($full_text);
    }


    function fts_twitter_quote_description($post_data) {

        $text = $post_data->quoted_status->full_text;

        // Message. Convert links to real links.
        $pattern = array('/http:(\S)+/', '/https:(\S)+/', '/[@]+([0-9\p{L}]+)/u', '/[#]+([0-9\p{L}]+)/u');
        $replace = array(' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="https://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="https://twitter.com/hashtag/$1?src=hash" target="_blank" rel="nofollow">#$1</a>');
        $full_text = preg_replace($pattern, $replace, $text);

        return nl2br($full_text);
    }


    function fts_twitter_image($post_data, $popup) {
        $fts_twitter_hide_images_in_posts = get_option('fts_twitter_hide_images_in_posts');
        $permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;

        $twitter_video_extended = isset($post_data->extended_entities->media[0]->type) ? $post_data->extended_entities->media[0]->type : '';

        if (!empty($post_data->entities->media[0]->media_url) && $twitter_video_extended !== 'video') {
            $media_url = $post_data->entities->media[0]->media_url_https;
            // $media_url = str_replace($not_protocol, $protocol, $media_url);
        } elseif (!empty($post_data->retweeted_status->entities->media[0]->media_url_https)) {
            $media_url = $post_data->retweeted_status->entities->media[0]->media_url_https;
        } elseif (!empty($post_data->quoted_status->entities->media[0]->media_url_https)) {
            $media_url = $post_data->quoted_status->entities->media[0]->media_url_https;
        } elseif (!empty($post_data->quoted_status->entities->media[0]->media_url_https)) {
            $media_url = $post_data->quoted_status->entities->media[0]->media_url_https;
        } else {
            $media_url = '';
        }

        if ($media_url !== '' && isset($fts_twitter_hide_images_in_posts) && $fts_twitter_hide_images_in_posts !== 'yes') {
            if (isset($popup) && $popup == 'yes') {
                return '<a href="' . $media_url . '" class="fts-twitter-link-image" target="_blank"><img class="fts-twitter-description-image" src="' . $media_url . '" alt="' . $post_data->user->screen_name . ' photo"/></a>';
            } else {
                return '<a href="' . $permalink . '" class="" target="_blank"><img class="fts-twitter-description-image" src="' . $media_url . '" alt="' . $post_data->user->screen_name . ' photo"/></a>';
            }
        }
    }


    function fts_twitter_permalink($post_data) {
        $permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;

        return '<div class="fts-tweet-reply-left"><a href="' . $permalink . '" target="_blank" title="Reply" aria-label="Reply"><div class="fts-twitter-reply"></div></a></div>';
    }

    function fts_twitter_retweet($post_data) {
        if (isset($post_data->retweet_count) && $post_data->retweet_count !== 0) {
            $retweet_count = $post_data->retweet_count;
        } else {
            $retweet_count = '';
        }

        return '<a href="https://twitter.com/intent/retweet?tweet_id=' . $post_data->id . '&related=' . $post_data->user->screen_name . '" target="_blank" class="fts-twitter-retweet-wrap" title="' . __('Retweet', 'feed-them-social') . '" aria-label="' . __('Retweet', 'feed-them-social') . '"><div class="fts-twitter-retweet">' . $retweet_count . '</div></a>';
    }

    function fts_twitter_favorite($post_data) {
        if (isset($post_data->favorite_count) && $post_data->favorite_count !== 0) {
            $favorite_count = $post_data->favorite_count;
        } else {
            $favorite_count = '';
        }

        return '<a href="https://twitter.com/intent/like?tweet_id=' . $post_data->id . '&related=' . $post_data->user->screen_name . '" target="_blank" class="fts-twitter-favorites-wrap" title="' . __('Favorite', 'feed-them-social') . '" aria-label="' . __('Favorite', 'feed-them-social') . '"><div class="fts-twitter-favorites">' . $favorite_count . '</div></a>';
    }


    /**
     * FTS Twitter Function
     *
     * Display Twitter Feed.
     *
     * @param $atts
     * @return mixed
     * @since 1.9.6
     */
    function fts_twitter_func($atts) {

        global $connection;
        $twitter_show_follow_btn = get_option('twitter_show_follow_btn');
        $twitter_show_follow_btn_where = get_option('twitter_show_follow_btn_where');
        $twitter_show_follow_count = get_option('twitter_show_follow_count');
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        // option to allow this action or not from the Twitter Options page
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {

            $twitter_load_more_text = get_option('twitter_load_more_text') ? get_option('twitter_load_more_text') : __('Load More', 'feed-them-social');
            $twitter_no_more_tweets_text = get_option('twitter_no_more_tweets_text') ? get_option('twitter_no_more_tweets_text') : __('No More Tweets', 'feed-them-social');

            include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/twitter/twitter-feed.php';

            if ($popup == 'yes') {
                // it's ok if these styles & scripts load at the bottom of the page
                $fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
                if (isset($fts_fix_magnific) && $fts_fix_magnific !== '1') {
                    wp_enqueue_style('fts-popup', plugins_url('feed-them-social/feeds/css/magnific-popup.css'));
                }
                wp_enqueue_script('fts-popup-js', plugins_url('feed-them-social/feeds/js/magnific-popup.js'));
            }
        } else {
            extract(shortcode_atts(array(
                'twitter_name' => '',
                'twitter_height' => '',
                'tweets_count' => '',
                'description_image' => '',
                'search' => '',
                'show_retweets' => '',
                'cover_photo' => '',
                'stats_bar' => '',
                'show_replies' => '',
            ), $atts));
        }
        $numTweets = $tweets_count;
        if ($numTweets == NULL) {
            $numTweets = '6';
        }

        if (!is_plugin_active('feed-them-premium/feed-them-premium.php') && $numTweets > '6') {
            $numTweets = '6';
        }

        $name = $twitter_name;

        if ($show_replies == 'no') {
            $exclude_replies = 'true';
        } else {
            $exclude_replies = 'false';
        }

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10) . '_' . 'twitter');
            //Create Dynamic Class Name
            $fts_dynamic_class_name = '';
            if (isset($_REQUEST['fts_dynamic_name'])) {
                $fts_dynamic_class_name = 'feed_dynamic_class' . $_REQUEST['fts_dynamic_name'];
            }
        }

        ob_start();

        if (!empty($search)) {
            $data_cache = 'twitter_data_cache_' . $search . '_num' . $numTweets . '';
        } else {
            $data_cache = 'twitter_data_cache_' . $name . '_num' . $numTweets . '';
        }

        //Check Cache
        if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($data_cache)) && !isset($_GET['load_more_ajaxing'])) {
            $fetchedTweets = $this->fts_get_feed_cache($data_cache);
            $cache_used = true;
        } else {
            include_once WP_CONTENT_DIR . '/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php';

            $fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
            $fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');

            $test_fts_twitter_custom_consumer_key = '35mom6axGlf60ppHJYz1dsShc';
            $test_fts_twitter_custom_consumer_secret = '7c2TJvUT7lS2EkCULpK6RGHrgXN1BA4oUi396pQEdRj3OEq5QQ';

            $fts_twitter_custom_consumer_key = isset($fts_twitter_custom_consumer_key) && $fts_twitter_custom_consumer_key !== '' ? $fts_twitter_custom_consumer_key : $test_fts_twitter_custom_consumer_key;
            $fts_twitter_custom_consumer_secret = isset($fts_twitter_custom_consumer_secret) && $fts_twitter_custom_consumer_secret !== '' ? $fts_twitter_custom_consumer_secret : $test_fts_twitter_custom_consumer_secret;

            $fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
            $fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');

            //Use custom api info
            if (!empty($fts_twitter_custom_access_token) && !empty($fts_twitter_custom_access_token_secret)) {
                $connection = new TwitterOAuthFTS(
                //Consumer Key
                    $fts_twitter_custom_consumer_key,
                    //Consumer Secret
                    $fts_twitter_custom_consumer_secret,
                    //Access Token
                    $fts_twitter_custom_access_token,
                    //Access Token Secret
                    $fts_twitter_custom_access_token_secret
                );
            } //else use default info
            else {
                $connection = new TwitterOAuthFTS(
                //Consumer Key
                    '4UUpTLglrsvQMjmrfdgdtHEEJ',
                    //Consumer Secret
                    'ngtRtVKRvcY4e2lZHHkKNc63JPMn8SnOw1bM0jd6Fv8H5C3phP',
                    //Access Token
                    '1561334624-CSmnb3JqhKctSGzYfB5ouf3GmR9Pne1fR2q9PzY',
                    //Access Token Secret
                    'CH2Ojl5G4sgn8kUaBIEhy6M0UUvBWs1CrYW8sh1fpCQXT'
                );
            }
            // $videosDecode = 'https://api.twitter.com/1.1/statuses/oembed.json?id=507185938620219395';
            // numTimes = get_option('twitter_replies_offset') == TRUE ? get_option('twitter_replies_offset') : '1' ;
            // If excluding replies, we need to fetch more than requested as the
            // total is fetched first, and then replies removed.
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($loadmore) && $loadmore == 'button' ||
                is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($loadmore) && $loadmore == 'autoscroll'
            ) {
                $totalToFetch = $numTweets;
            } else {
                $totalToFetch = $exclude_replies == 'true' ? max(50, $numTweets * 3) : $numTweets;
            }
            // $totalToFetch = $numTweets;
            $description_image = !empty($description_image) ? $description_image : "";

            if ($show_retweets == 'yes') {
                $show_retweets = 'true';
            }
            if ($show_retweets == 'no') {
                $show_retweets = 'false';
            }


            // $url_of_status = !empty($url_of_status) ? $url_of_status : "";
            // $widget_type_for_videos = !empty($widget_type_for_videos) ? $widget_type_for_videos : "";
            if (!empty($search)) {

                $connection_search_array = array(
                    'q' => $search,
                    'count' => $totalToFetch,//
                    'result_type' => 'recent',
                    'include_rts' => $show_retweets,
                    'tweet_mode' => 'extended',
                );

                //For Load More Ajax
                if (isset($_REQUEST['since_id']) && isset($_REQUEST['max_id'])) {
                    //$connection_search_array['since_id'] =  $_REQUEST['since_id'];
                    $connection_search_array['max_id'] = $_REQUEST['max_id'] - 1;
                }

                $fetchedTweets = $connection->get(
                    'search/tweets',
                    $connection_search_array
                );
            } else {

                $connection_user_array = array(
                    'tweet_mode' => 'extended',
                    'screen_name' => $name,
                    'count' => $totalToFetch,
                    'exclude_replies' => $exclude_replies,
                    'images' => $description_image,
                    'include_rts' => $show_retweets,
                );

                //For Load More Ajax
                if (isset($_REQUEST['since_id']) && isset($_REQUEST['max_id'])) {
                    //$connection_user_array['since_id'] =  $_REQUEST['since_id'];
                    $connection_user_array['max_id'] = $_REQUEST['max_id'] - 1;
                }

                $fetchedTweets = $connection->get(
                    'statuses/user_timeline',
                    $connection_user_array
                );
            }

            if (!empty($search)) {
                $fetchedTweets = $fetchedTweets->statuses;
            } else {
                $fetchedTweets = $fetchedTweets;
            }
            //   echo'<pre>';
            //   print_r($fetchedTweets);
            //   echo'</pre>';
            // get the count based on $exclude_replies
            $limitToDisplay = min($numTweets, count($fetchedTweets));
            for ($i = 0; $i < $limitToDisplay; $i++) {
                $numTweets = $limitToDisplay;
                break;
            }
            //   echo'<pre>';
            //   print_r($numTweets);
            //  echo'</pre>';

            $convert_Array1['data'] = $fetchedTweets;
            $fetchedTweets = (object)$convert_Array1;

            //  echo'<pre>';
            //      print_r($fetchedTweets);
            //  echo'</pre>';
        }//END ELSE
        //Error Check
        if (isset($fetchedTweets->errors)) {
            $error_check = __('Oops, Somethings wrong. ', 'feed-them-social') . $fetchedTweets->errors[0]->message;
            if ($fetchedTweets->errors[0]->code == 32) {
                $error_check .= __(' Please check that you have entered your Twitter API token information correctly on the Twitter Options page of Feed Them Social.', 'feed-them-social');
            }
            if ($fetchedTweets->errors[0]->code == 34) {
                $error_check .= __(' Please check the Twitter Username you have entered is correct in your shortcode for Feed Them Social.', 'feed-them-social');
            }
        } elseif (empty($fetchedTweets) && !isset($fetchedTweets->errors)) {
            $error_check = __(' This account has no tweets. Please Tweet to see this feed. Feed Them Social.', 'feed-them-social');
        }
        //IS RATE LIMIT REACHED?
        if (isset($fetchedTweets->errors) && $fetchedTweets->errors[0]->code !== 32 && $fetchedTweets->errors[0]->code !== 34) {
            _e('Rate Limited Exceeded. Please go to the Feed Them Social Plugin then the Twitter Options page and follow the instructions under the header Twitter API Token.', 'feed-them-social');
        }
        // Did the fetch fail?
        if (isset($error_check)) {
            echo $error_check;
        }//END IF
        else {
            if (!empty($fetchedTweets)) {
                //Cache It
                if (!isset($cache_used) && !isset($_GET['load_more_ajaxing'])) {
                    $this->fts_create_feed_cache($data_cache, $fetchedTweets);
                }

                $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
                // $not_protocol = !isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
                $user_permalink = $protocol . 'twitter.com/' . $twitter_name;


                foreach ($fetchedTweets->data as $post_data) {

                    $profile_banner_url = isset($post_data->user->profile_banner_url) ? $post_data->user->profile_banner_url : "";
                    $statuses_count = isset($post_data->user->statuses_count) ? $post_data->user->statuses_count : "";
                    $followers_count = isset($post_data->user->followers_count) ? $post_data->user->followers_count : "";

                    $friends_count = isset($post_data->user->friends_count) ? $post_data->user->friends_count : "";
                    $favourites_count = isset($post_data->user->favourites_count) ? $post_data->user->favourites_count : "";
                    // we break this foreach because we only need one post to get the info above.
                    break;
                }

                //******************
                // SOCIAL BUTTON IF COVER PHOTO ON
                //******************
                if (!empty($search)) {
                    $twitter_name = $twitter_name;
                }

                //Make sure it's not ajaxing
            if (!isset($_GET['load_more_ajaxing'])) {


                if (isset($profile_banner_url) && isset($cover_photo) && $cover_photo == "yes") {
                    ?>
                    <div class="fts-twitter-backg-image">
                        <?php
                        if (isset($twitter_show_follow_btn) && $twitter_show_follow_btn == 'yes' && $twitter_show_follow_btn_where == 'twitter-follow-above' && $twitter_name !== '') {
                            echo '<div class="twitter-social-btn-top">';
                            $this->social_follow_button('twitter', $twitter_name);
                            echo '</div>';
                        }
                        ?>
                        <img src="<?php print $profile_banner_url; ?>" />

                    </div>
                <?php } elseif (isset($twitter_show_follow_btn) && $twitter_show_follow_btn == 'yes' && $twitter_show_follow_btn_where == 'twitter-follow-above' && $twitter_name !== '' && $cover_photo !== "yes") {
                    echo '<div class="twitter-social-btn-top">';
                    $this->social_follow_button('twitter', $twitter_name);
                    echo '</div>';
                }// if cover photo = yes

                // These need to be in this order to keep the different counts straight since I used either $statuses_count or $followers_count throughout.
                if (isset($stats_bar) && $stats_bar == "yes" && $search == '') {
                    // here we add a , for all numbers below 9,999
                    if (isset($statuses_count) && $statuses_count <= 9999) {
                        $statuses_count = number_format($statuses_count);
                    }
                    // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions
                    if (isset($statuses_count) && $statuses_count >= 1000000) {
                        $statuses_count = round(($statuses_count / 1000000), 1) . 'm';
                    }
                    // here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands
                    if (isset($statuses_count) && $statuses_count >= 10000) {
                        $statuses_count = round(($statuses_count / 1000), 1) . 'k';
                    }

                    // here we add a , for all numbers below 9,999
                    if (isset($followers_count) && $followers_count <= 9999) {
                        $followers_count = number_format($followers_count);
                    }
                    // here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions
                    if (isset($followers_count) && $followers_count >= 1000000) {
                        $followers_count = round(($followers_count / 1000000), 1) . 'm';
                    }
                    // here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands
                    if (isset($followers_count) && $followers_count >= 10000) {
                        $followers_count = round(($followers_count / 1000), 1) . 'k';
                    }
                }

                // option to allow the followers plus count to show
                if (isset($twitter_show_follow_count) && $twitter_show_follow_count == 'yes' && $search == '' && isset($stats_bar) && $stats_bar !== "yes") {
                    print '<div class="twitter-followers-fts-singular"><a href="' . $user_permalink . '" target="_blank">' . __('Followers:', 'feed-them-social') . '</a> ' . $followers_count . '</div>';
                }
                if (isset($stats_bar) && $stats_bar == "yes" && $search == '') {
                    // option to allow the followers plus count to show

                    print '<div class="fts-twitter-followers-wrap">';
                    print '<div class="twitter-followers-fts fts-tweets-first"><a href="' . $user_permalink . '" target="_blank">' . __('Tweets', 'feed-them-social') . '</a> ' . $statuses_count . '</div>';
                    print '<div class="twitter-followers-fts fts-following-link-div"><a href="' . $user_permalink . '" target="_blank">' . __('Following', 'feed-them-social') . '</a> ' . number_format($friends_count) . '</div>';
                    print '<div class="twitter-followers-fts fts-followers-link-div"><a href="' . $user_permalink . '" target="_blank">' . __('Followers', 'feed-them-social') . '</a> ' . $followers_count . '</div>';
                    print '<div class="twitter-followers-fts fts-likes-link-div"><a href="' . $user_permalink . '" target="_blank">' . __('Likes', 'feed-them-social') . '</a> ' . number_format($favourites_count) . '</div>';
                    print '</div>';

                }


            if (isset($grid) && $grid == 'yes') { ?>
                <div id="twitter-feed-<?php print $twitter_name ?>" class="fts-slicker-twitter-posts masonry js-masonry <?php print $fts_dynamic_class_name;
                if (isset($popup) && $popup == 'yes') { ?> popup-gallery-twitter<?php } ?>" style='margin:0 auto' data-masonry-options='{"itemSelector": ".fts-tweeter-wrap", "isFitWidth": true, "transitionDuration": 0 }'>
                    <?php }
                    else { ?>
                    <div id="twitter-feed-<?php print $twitter_name ?>" class="<?php print $fts_dynamic_class_name ?> fts-twitter-div<?php if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) { ?> fts-twitter-scrollable<?php }
                    if (isset($popup) && $popup == 'yes') { ?> popup-gallery-twitter<?php } ?>" <?php if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) { ?>style="height:<?php echo $twitter_height; ?>"<?php } ?>>
                        <?php } ?>

                        <?php }
                        $i = 0;
                        foreach ($fetchedTweets->data as $post_data) {

                            $name = isset($post_data->user->name) ? $post_data->user->name : "";
                            $description = $this->fts_twitter_description($post_data);
                            $name_retweet = isset($post_data->retweeted_status->user->name) ? $post_data->retweeted_status->user->name : "";
                            $twitter_name = isset($post_data->user->screen_name) ? $post_data->user->screen_name : "";
                            $screen_name_retweet = isset($post_data->retweeted_status->user->screen_name) ? $post_data->retweeted_status->user->screen_name : "";
                            $in_reply_screen_name = isset($post_data->entities->user_mentions[0]->screen_name) ? $post_data->entities->user_mentions[0]->screen_name : "";
                            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
                            $not_protocol = !isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';

                            $permalink = $protocol . 'twitter.com/' . $twitter_name . '/status/' . $post_data->user->id_str;
                            $user_permalink = $protocol . 'twitter.com/' . $twitter_name;

                            $user_retweet_permalink = $protocol . 'twitter.com/' . $screen_name_retweet;


                            $in_reply_permalink = $protocol . 'twitter.com/' . $in_reply_screen_name;

                            //  $widget_type_for_videos = $post_data->widget_type_for_videos;

                            /* Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */
                            $image = isset($post_data->user->profile_image_url_https) ? $post_data->user->profile_image_url_https : "";

                            $image_retweet = isset($post_data->retweeted_status->user->profile_image_url_https) ? $post_data->retweeted_status->user->profile_image_url_https : "";

                            //  $image = str_replace($not_protocol, $protocol, $image);

                            // Need to get time in Unix format.
                            $times = isset($post_data->created_at) ? $post_data->created_at : "";
                            // tied to date function
                            $feed_type = 'twitter';
                            // call our function to get the date
                            $fts_date_time = $this->fts_custom_date($times, $feed_type);

                            $id = isset($post_data->id) ? $post_data->id : "";

                            // the retweet count works for posts and retweets
                            $retweet_count = isset($post_data->retweet_count) ? $post_data->retweet_count : "";

                            // the favorites count needs to be switched up for retweets
                            if (empty($post_data->retweeted_status->favorite_count)) {
                                $favorite_count = $post_data->favorite_count;
                            } else {
                                $favorite_count = $post_data->retweeted_status->favorite_count;
                            }

                            $fts_twitter_full_width = get_option('twitter_full_width');
                            $fts_dynamic_name = isset($fts_dynamic_name) ? $fts_dynamic_name : '';

                            ?>

                            <div class="fts-tweeter-wrap <?php echo $fts_dynamic_name ?>" <?php if (isset($grid) && $grid == 'yes') {
                                print ' style="width:' . $colmn_width . '!important; margin:' . $space_between_posts . '!important"';
                            } ?>>
                                <div class="tweeter-info">

                                    <?php if ($fts_twitter_full_width !== 'yes') { ?>
                                        <div class="fts-twitter-image"> <?php
                                            if (!isset($post_data->retweeted_status)) { ?>
                                                <a href="<?php print $user_permalink; ?>" target="_blank" class="fts-twitter-username"><img class="twitter-image" src="<?php print $image ?>" alt="<?php print $name ?>" /></a>
                                            <?php } else { ?>
                                                <a href="<?php print $user_retweet_permalink; ?>" target="_blank" class="fts-twitter-permalink fts-twitter-username"><img class="twitter-image" src="<?php print $image_retweet ?>" alt="<?php print $name_retweet ?>" /></a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <div class="<?php if ($fts_twitter_full_width == 'yes') { ?>fts-twitter-full-width<?php } else { ?>fts-right<?php } ?>">
                                        <div class="fts-uppercase fts-bold">

                                            <?php if (!isset($post_data->retweeted_status) && empty($post_data->in_reply_to_user_id)) { ?>
                                                <a href="<?php print $user_permalink ?>" target="_blank" class="fts-twitter-full-name"><?php print $post_data->user->name; ?></a>
                                                <a href="<?php print $user_permalink ?>" target="_blank" class="fts-twitter-at-name">@<?php print $twitter_name ?></a>
                                            <?php } else {

                                                if (empty($post_data->in_reply_to_user_id)) { ?>
                                                    <a href="<?php print $user_permalink ?>" target="_blank" class="fts-twitter-at-name"><?php print $post_data->user->name; ?> <?php echo _e('Retweeted', 'feed-them-social'); ?>
                                                        <strong>&middot;</strong></a>
                                                    <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-full-name"><?php print $name_retweet; ?></a>
                                                    <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-at-name">@<?php print $screen_name_retweet ?></a>
                                                <?php } else { ?>
                                                    <a href="<?php print $in_reply_permalink ?>" target="_blank" class="fts-twitter-at-name"><?php echo _e('In reply to', 'feed-them-social'); ?><?php echo $post_data->entities->user_mentions[0]->name; ?> </a>
                                                <?php } ?><?php }
                                            $permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;
                                            ?>

                                        </div>

                                        <span class="time"><a href="<?php print $permalink; ?>" target="_blank"><?php print $fts_date_time ?></a></span><br />
                                        <span class="fts-twitter-text"><?php print $description ?>
                                            <div class="fts-twitter-caption">
                                                <a href="<?php print $user_permalink; ?>" class="fts-view-on-twitter-link" target="_blank"><?php echo _e('View on Twitter', 'feed-them-social'); ?></a>
                                            </div>
                                        </span>

                                        <?php
                                        // Regular Posted Videos
                                        $twitter_video_reg = isset($post_data->extended_entities->media[0]->type) && $post_data->extended_entities->media[0]->type == 'video';

                                        // Retweeted video urls
                                        $twitter_video_retweeted = isset($post_data->retweeted_status->extended_entities->media[0]->type) ? $post_data->retweeted_status->extended_entities->media[0]->type : '';

                                        // Quoted status which is when people retweet or copy paste video tweet link to there tweet. why people do this instead of retweeting is beyond me.
                                        $twitter_video_quoted_status = isset($post_data->quoted_status->extended_entities->media[0]->type) ? $post_data->quoted_status->extended_entities->media[0]->type : '';


                                        // Quoted status which is when people retweet or copy paste video tweet link to there tweet. why people do this instead of retweeting is beyond me.
                                        $twitter_image_quoted_status = isset($post_data->quoted_status->extended_entities->media[0]->type) && $post_data->quoted_status->extended_entities->media[0]->type == 'photo';


                                        $twitter_is_video_allowed = get_option('twitter_allow_videos');
                                        $twitter_allow_videos = !empty($twitter_is_video_allowed) ? $twitter_is_video_allowed : 'yes';


                                        if ($twitter_video_quoted_status == 'video') { ?>
                                        <div class="fts-twitter-quoted-text-wrap fts-twitter-quoted-video">
                                            <?php }

                                            //Print our video if one is available
                                            print $this->fts_twitter_load_videos($post_data);


                                            if ($twitter_video_quoted_status == 'video') { ?>

                                                <div class="fts-twitter-quoted-text">
                                                    <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-full-name"><?php print $post_data->quoted_status->user->name; ?></a>
                                                    <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-at-name">@<?php print $post_data->quoted_status->user->screen_name; ?></a><br />
                                                    <?php print $this->fts_twitter_quote_description($post_data) ?>
                                                </div>

                                            <?php }

                                            if ($twitter_video_quoted_status == 'video') { ?>
                                        </div>
                                    <?php }

                                    //Print our IMAGE if one is available
                                    if ($twitter_video_quoted_status !== 'video' && $twitter_video_retweeted !== 'video') {
                                        //Print our IMAGE if one is available
                                        $popup = isset($popup) ? $popup : '';


                                        if ($twitter_image_quoted_status == 'photo') { ?>
                                            <div class="fts-twitter-quoted-text-wrap fts-twitter-quoted-image">
                                        <?php }


                                        print $this->fts_twitter_image($post_data, $popup);

                                        if ($twitter_image_quoted_status == 'photo') { ?>

                                            <div class="fts-twitter-quoted-text">
                                                <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-full-name"><?php print $post_data->quoted_status->user->name; ?></a>
                                                <a href="<?php print $user_retweet_permalink ?>" target="_blank" class="fts-twitter-at-name">@<?php print $post_data->quoted_status->user->screen_name; ?></a><br />
                                                <?php print $this->fts_twitter_quote_description($post_data) ?>
                                            </div>

                                        <?php }

                                        if ($twitter_image_quoted_status == 'photo') { ?>
                                            </div>
                                        <?php }

                                    } ?>
                                    </div>
                                    <div class="fts-twitter-reply-wrap <?php if ($fts_twitter_full_width == 'yes') { ?>fts-twitter-full-width<?php } else { ?>fts-twitter-no-margin-left<?php } ?>"><?php
                                        // twitter permalink per post
                                        $permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;
                                        print $this->fts_share_option($permalink, $description);
                                        ?>
                                    </div>
                                    <div class="fts-twitter-reply-wrap-left"><?php
                                        // twitter permalink per post
                                        print $this->fts_twitter_permalink($post_data); ?>
                                        <div class="fts-tweet-others-right"><?php print $this->fts_twitter_retweet($post_data) ?><?php print $this->fts_twitter_favorite($post_data) ?></div>
                                    </div>
                                    <div class="fts-clear"></div>
                                </div><?php // <!--tweeter-info--> ?>
                            </div>
                            <?php $i++;
                            if ($i == $numTweets) break;
                        }  // endforeach;

                        //Make sure it's not ajaxing
                        if (!isset($_GET['load_more_ajaxing']) && !empty($scrollMore) && $scrollMore == 'autoscroll') {

                            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
                            // this div returns outputs our ajax request via jquery append html from above
                            print '<div id="output_' . $fts_dynamic_name . '"></div>';
                            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'autoscroll') {
                                print '<div class="fts-twitter-load-more-wrapper">';
                                print '<div id="loadMore_' . $fts_dynamic_name . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Twitter</div>';
                                print'</div>';
                            }
                        } ?>
                </div><?php // #twitter-feed-

                // this makes it so the page does not scroll if you reach the end of scroll bar or go back to top
                if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) { ?>
                    <script>jQuery.fn.isolatedScrollTwitter = function () {
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
                        jQuery('.fts-twitter-scrollable').isolatedScrollTwitter();</script><?php }

            }// END IF $fetchedTweets
        }//END ELSE

        //******************
        //Load More BUTTON Start
        //******************

        //First Key
        $first_key = isset($fetchedTweets->data) ? current($fetchedTweets->data) : '';

        $_REQUEST['since_id'] = isset($first_key->id_str) ? $first_key->id_str : '';

        //Last Key
        $last_key = isset($fetchedTweets->data) ? end($fetchedTweets->data) : '';
        $_REQUEST['max_id'] = isset($last_key->id_str) ? $last_key->id_str : '';

        if (isset($loadmore)) { ?>
            <script>var sinceID_<?php echo $_REQUEST['fts_dynamic_name']; ?>= "<?php echo $_REQUEST['since_id']; ?>";
                var maxID_<?php echo $_REQUEST['fts_dynamic_name']; ?>= "<?php echo $_REQUEST['max_id']; ?>";</script>
            <?php
        }

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts']) && !empty($loadmore)) {
            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
            $time = time();
            $nonce = wp_create_nonce($time . "load-more-nonce");
            ?>
            <script>
                jQuery(document).ready(function () {

                    <?php // $scrollMore = load_more_posts_style shortcode att
                    if ($scrollMore == 'autoscroll') { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode ?>
                    jQuery(".<?php echo $fts_dynamic_class_name ?>").bind("scroll", function () {
                        if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
                            <?php }
                            else { // this is where we do CLICK function to LOADMORE if = button in shortcode ?>
                            jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").click(function () {
                                <?php } ?>
                                jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").addClass('fts-fb-spinner');
                                var button = jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                                console.log(button);

                                var yes_ajax = "yes";
                                var fts_d_name = "<?php echo $fts_dynamic_name;?>";
                                var fts_security = "<?php echo $nonce;?>";
                                var fts_time = "<?php echo $time;?>";
                                var feed_name = "fts_twitter";
                                var loadmore_count = "tweets_count=<?php echo $loadmore_count ?>";
                                var feed_attributes = <?php echo json_encode($atts); ?>;
                                jQuery.ajax({
                                    data: {
                                        action: "my_fts_fb_load_more",
                                        since_id: sinceID_<?php echo $fts_dynamic_name ?>,
                                        max_id: maxID_<?php echo $fts_dynamic_name ?>,
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
                                        <?php  if(isset($loadmore) && $loadmore == 'autoscroll') {?>
                                        jQuery('#output_<?php echo $fts_dynamic_name ?>').append(data).filter('#output_<?php echo $fts_dynamic_name ?>').html();
                                        <?php } else { ?>
                                        jQuery('.<?php echo $fts_dynamic_class_name ?>').append(data).filter('.<?php echo $fts_dynamic_class_name ?>').html();
                                        <?php } ?>

                                        if (!maxID_<?php echo $_REQUEST['fts_dynamic_name']; ?> || maxID_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more') {
                                            jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo $twitter_no_more_tweets_text ?></div>');
                                            jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
                                            jQuery(".<?php echo $fts_dynamic_class_name ?>").unbind('scroll');
                                        }
                                        jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<?php echo $twitter_load_more_text ?>');
                                        //	jQuery('#loadMore_< ?php echo $fts_dynamic_name ?>').removeClass('flip360-fts-load-more');
                                        jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").removeClass('fts-fb-spinner');
                                        // Reload the share each funcion otherwise you can't open share option.
                                        jQuery.fn.ftsShare();
                                        <?php
                                        if(isset($grid) && $grid == 'yes') {?>
                                        jQuery(".fts-slicker-twitter-posts").masonry("reloadItems");

                                        setTimeout(function () {
                                            // Do something after 3 seconds
                                            // This can be direct code, or call to some other function
                                            jQuery(".fts-slicker-twitter-posts").masonry("layout");
                                        }, 500);
                                        <?php } ?>

                                    }
                                }); // end of ajax()
                                return false;
                                <?php // string $scrollMore is at top of this js script. acception for scroll option closing tag
                                if ($scrollMore == 'autoscroll' ) { ?>
                            } // end of scroll ajax load.
                            <?php } ?>
                        }
                        ); // end of form.submit
                    <?php
                    if(isset($grid) && $grid == 'yes') {?>
                    // We run this otherwise the videos that load in posts will overlap other posts.
                    setTimeout(function () {
                        jQuery(".fts-slicker-twitter-posts").masonry("layout");
                        jQuery(".fts-slicker-twitter-posts").masonry("reloadItems");
                    }, 1200);
                    <?php } ?>

                }); // end of document.ready
            </script>
            <?php
        }//End Check

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($scrollMore) && $scrollMore == 'button') {
                print '<div class="fts-clear"></div>';
                print '<div class="fts-twitter-load-more-wrapper">';
                print'<div id="loadMore_' . $fts_dynamic_name . '"" style="';
                if (isset($loadmore_btn_maxwidth) && $loadmore_btn_maxwidth !== '') {
                    print'max-width:' . $loadmore_btn_maxwidth . ';';
                }
                $loadmore_btn_margin = isset($loadmore_btn_margin) ? $loadmore_btn_margin : '10px';
                print 'margin:' . $loadmore_btn_margin . ' auto ' . $loadmore_btn_margin . '" class="fts-fb-load-more">' . $twitter_load_more_text . '</div>';
                print'</div>';
            }
        }//End Check
        unset($_REQUEST['since_id'], $_REQUEST['max_id']);

        //******************
        // SOCIAL BUTTON
        //******************
        if (isset($twitter_show_follow_btn) && $twitter_show_follow_btn == 'yes' && $twitter_show_follow_btn_where == 'twitter-follow-below' && $twitter_name !== '') {
            echo '<div class="twitter-social-btn-bottom">';
            $this->social_follow_button('twitter', $twitter_name);
            echo '</div>';
        }

        return ob_get_clean();
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
            $randomString .= $characters[ rand(0, $charactersLength - 1) ];
        }

        return $randomString;
    }
}// FTS_Twitter_Feed END CLASS
?>