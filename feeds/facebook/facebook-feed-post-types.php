<?php namespace feedthemsocial;

/**
 * Class FTS Facebook Feed Post Types
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Facebook_Feed_Post_Types extends FTS_Facebook_Feed
{



    /**
     * Feed Post Types
     *
     * Display Facebook Feed.
     *
     * @param $set_zero
     * @param $FBtype
     * @param $post_data
     * @param $FB_Shortcode
     * @param $response_post_array
     * @param null $single_event_array_response
     * @return string
     * @since 1.9.6
     */
    function feed_post_types($set_zero, $FBtype, $post_data, $FB_Shortcode, $response_post_array, $single_event_array_response = null) {
        //Reviews Plugin
        if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
            $FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
        }

        $fts_dynamic_vid_name_string = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);

        if ($set_zero == $FB_Shortcode['posts'])
            return;
        //Create Facebook Variables
        $FBfinalstory = '';
        $first_dir = '';

        $FBpicture = isset($post_data->picture) ? $post_data->picture : "";
        $FBlink = isset($post_data->link) ? $post_data->link : "";
        $FBname = isset($post_data->name) ? $post_data->name : '';
        $FBcaption = isset($post_data->caption) ? $post_data->caption : "";
        $FBmessage = (isset($post_data->message) ? $post_data->message : (isset($post_data->review_text) ? $post_data->review_text : '') . '');
        $FBdescription = isset($post_data->description) ? $post_data->description : "";
        $FBLinkEventName = isset($post_data->to->data[0]->name) ? $post_data->to->data[0]->name : "";
        $FBstory = isset($post_data->story) ? $post_data->story : "";
        $FBicon = isset($post_data->icon) ? $post_data->icon : "";
        $FBby = isset($post_data->properties->text) ? $post_data->properties->text : "";
        $FBbylink = isset($post_data->properties->href) ? $post_data->properties->href : "";

        $FBpost_share_count = isset($post_data->shares->count) ? $post_data->shares->count : "";
        $FBpost_like_count_array = isset($post_data->likes->data) ? $post_data->likes->data : "";
        $FBpost_comments_count_array = isset($post_data->comments->data) ? $post_data->comments->data : "";

        $FBpost_object_id = isset($post_data->object_id) ? $post_data->object_id : "";
        $FBalbum_photo_count = isset($post_data->count) ? $post_data->count : "";
        $FBalbum_cover = isset($post_data->cover_photo->id) ? $post_data->cover_photo->id : "";
        $FBalbum_picture = isset($post_data->photos->data[0]->webp_images[0]->source) ? $post_data->photos->data[0]->webp_images[0]->source : '';

        //  echo '<pre>';
        //  print_r($FBalbum_picture);
        //  echo '</pre>';

        $FBattachments = isset($post_data->attachments) ? $post_data->attachments : "";
        // youtube and vimeo embed url
        $FBvideo_embed = isset($post_data->source) ? $post_data->source : "";

        $FBvideo = isset($post_data->embed_html) ? $post_data->embed_html : "";
        $FBvideoPicture = isset($post_data->format[2]->picture) ? $post_data->format[2]->picture : "";

        if ($FBalbum_cover) {
            $photo_data = json_decode($response_post_array[$FBalbum_cover . '_photo']);
        }
        if (isset($post_data->id)) {
            $FBpost_id = $post_data->id;
            $FBpost_full_ID = explode('_', $FBpost_id);
            if (isset($FBpost_full_ID[0])) {
                $FBpost_user_id = $FBpost_full_ID[0];
            }
            if (isset($FBpost_full_ID[1])) {
                $FBpost_single_id = $FBpost_full_ID[1];
            }
        }
        else {
            $FBpost_id = '';
            $FBpost_user_id = '';
        }
        if ($FB_Shortcode['type'] == 'albums' && !$FBalbum_cover) {
            unset($post_data);
        }
        //Create Post Data Key
        if (isset($post_data->object_id)) {
            $post_data_key = $post_data->object_id;
        } else {
            $post_data_key = isset($post_data->id) ? $post_data->id : "";
        }
        //Count Likes/Shares/
        $lcs_array = $this->get_likes_shares_comments($response_post_array, $post_data_key, $FBpost_share_count);

        $FBlocation = isset($post_data->location) ? $post_data->location : "";
        $FBembed_vid = isset($post_data->embed_html) ? $post_data->embed_html : "";
        $FBfromName = isset($post_data->from->name) ? $post_data->from->name : "";
        $FBfromName = preg_quote($FBfromName, "/");;
        $FBstory = isset($post_data->story) ? $post_data->story : "";
        $ftsCustomDate = get_option('fts-custom-date') ? get_option('fts-custom-date') : '';
        $ftsCustomTime = get_option('fts-custom-time') ? get_option('fts-custom-time') : '';
        $CustomDateCheck = get_option('fts-date-and-time-format') ? get_option('fts-date-and-time-format') : '';

        $FBpictureGallery1 = isset($post_data->attachments->data[0]->subattachments->data[1]->media->image->src) ? $post_data->attachments->data[0]->subattachments->data[1]->media->image->src : '';
        $FBpictureGallery2 = isset($post_data->attachments->data[0]->subattachments->data[2]->media->image->src)? $post_data->attachments->data[0]->subattachments->data[2]->media->image->src :  '';
        $FBpictureGallery3 = isset($post_data->attachments->data[0]->subattachments->data[3]->media->image->src) ? $post_data->attachments->data[0]->subattachments->data[3]->media->image->src : '';

        // we get the width of the first attachment so we can set the max width for the frame around the main image and thumbs.. this makes it so our percent width on thumbnails are nice and aligned
        $FBpictureGallery0Width = isset($post_data->attachments->data[0]->subattachments->data[0]->media->image->src) ? $post_data->attachments->data[0]->subattachments->data[0]->media->image->width : '';

        // June 22, 2017 - Going to leave the attachments description idea for a future update, lots more work to get the likes and comments for attachments and have that info be in the popup
        // $FBpictureGalleryDescription0 = isset($post_data->attachments->data[0]->subattachments->data[1]->description) ? $post_data->attachments->data[0]->subattachments->data[1]->media->image->src : '';
        // $FBpictureGalleryDescription1 = isset($post_data->attachments->data[0]->subattachments->data[2]->description)? $post_data->attachments->data[0]->subattachments->data[2]->media->image->src :  '';
        // $FBpictureGalleryDescription2 = isset($post_data->attachments->data[0]->subattachments->data[3]->description) ? $post_data->attachments->data[0]->subattachments->data[3]->media->image->src : '';

        $FBpictureGalleryLink1 = isset($post_data->attachments->data[0]->subattachments->data[1]->target->url) ? $post_data->attachments->data[0]->subattachments->data[1]->target->url : '';
        $FBpictureGalleryLink2 = isset($post_data->attachments->data[0]->subattachments->data[2]->target->url) ? $post_data->attachments->data[0]->subattachments->data[2]->target->url : '';
        $FBpictureGalleryLink3 = isset($post_data->attachments->data[0]->subattachments->data[3]->target->url) ? $post_data->attachments->data[0]->subattachments->data[3]->target->url : '';

        if (isset($FB_Shortcode['slider_spacing']) && $FB_Shortcode['slider_spacing'] !== '' && isset($FB_Shortcode['scrollhorz_or_carousel']) && $FB_Shortcode['scrollhorz_or_carousel'] == "carousel") {

            $FB_Shortcode['space_between_photos'] = '0 ' . $FB_Shortcode['slider_spacing'];

        }

        if ($ftsCustomDate == '' && $ftsCustomTime == '' && $CustomDateCheck !== 'fts-custom-date') {
            $CustomDateFormat = $CustomDateCheck;
        } elseif ($ftsCustomDate !== '' && $CustomDateCheck == 'fts-custom-date' || $ftsCustomTime !== '' && $CustomDateCheck == 'fts-custom-date') {
            $CustomDateFormat = $ftsCustomDate . ' ' . $ftsCustomTime;
        } else {
            $CustomDateFormat = 'F jS, Y \a\t g:ia';
        }

        $createdTime = isset($post_data->created_time) ? $post_data->created_time : '';
        $CustomTimeFormat = strtotime($createdTime);

        if (!empty($FBstory)) {
            $FBfinalstory = preg_replace('/\b' . $FBfromName . 's*?\b(?=([^"]*"[^"]*")*[^"]*$)/i', '', $FBstory, 1);
        }

        $FTS_FB_OUTPUT = '';


        $fts_hide_photos_type = get_option('fb_hide_images_in_posts') ? get_option('fb_hide_images_in_posts') : 'no';

        switch ($FBtype) {
            case 'video'  :
                $FTS_FB_OUTPUT .= '<div class="fts-jal-single-fb-post fts-fb-video-post-wrap" ';
                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                    $FTS_FB_OUTPUT .= 'style="width:' . $FB_Shortcode['colmn_width'] . '!important; margin:' . $FB_Shortcode['space_between_posts'] . '!important"';
                }
                $FTS_FB_OUTPUT .= '>';

                break;
            case 'app':
            case 'cover':
            case 'profile':
            case 'mobile':
            case 'wall':
            case 'normal':
            case 'photo':


                $FTS_FB_OUTPUT .= "<div class='fts-fb-photo-post-wrap fts-jal-single-fb-post' ";
                if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {

                    if (isset($FB_Shortcode['scrollhorz_or_carousel']) && $FB_Shortcode['scrollhorz_or_carousel'] == 'scrollhorz') {
                        $FTS_FB_OUTPUT .= 'style="max-width:' . $FB_Shortcode['image_width'] . ';height:100%;  margin:' . $FB_Shortcode['space_between_photos'] . '!important"';
                    } else {
                        $FTS_FB_OUTPUT .= 'style="width:' . $FB_Shortcode['image_width'] . ' !important; height:' . $FB_Shortcode['image_height'] . '!important; margin:' . $FB_Shortcode['space_between_photos'] . '!important"';
                    }
                }
                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                    $FTS_FB_OUTPUT .= 'style="width:' . $FB_Shortcode['colmn_width'] . '!important; margin:' . $FB_Shortcode['space_between_posts'] . '!important"';
                }
                $FTS_FB_OUTPUT .= '>';


                break;
            case 'album':
            default:
                $FTS_FB_OUTPUT .= '<div class="fts-jal-single-fb-post"';
                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                    $FTS_FB_OUTPUT .= 'style="width:' . $FB_Shortcode['colmn_width'] . '!important; margin:' . $FB_Shortcode['space_between_posts'] . '!important"';
                }
                $FTS_FB_OUTPUT .= '>';
                break;
        }
        //output Single Post Wrap


        //Don't $FTS_FB_OUTPUT .= if Events Feed
        if ($FB_Shortcode['type'] !== 'events') {

            //Reviews
            if ($FB_Shortcode['type'] == 'reviews') {
                $itemscope_reviews = 'itemscope itemtype="http://schema.org/Review"';
                $review_rating = '<meta itemprop="itemReviewed" itemscope itemtype="http://schema.org/CreativeWork"><div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" style="display: none;"><meta itemprop="worstRating" content = "1"><meta itemprop="ratingValue" content = "'.$post_data->rating.'"><meta  itemprop="bestRating" content = "5"></div>';
            } else {
                $itemscope_reviews = '';
                $review_rating = '';
            }

            //Right Wrap
            $FTS_FB_OUTPUT .= '<div ' . $itemscope_reviews . ' class="fts-jal-fb-right-wrap">'.$review_rating.'';

            //Top Wrap (Exluding : albums, album_photos, and hiding)
            $hide_date_likes_comments = $FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['hide_date_likes_comments'] == 'yes' || $FB_Shortcode['type'] == 'albums' && $FB_Shortcode['hide_date_likes_comments'] == 'yes' ? 'hide-date-likes-comments-etc' : '';

            $show_media = isset($FB_Shortcode['show_media']) ? $FB_Shortcode['show_media']  : 'bottom';

            if ($show_media !== 'top') {
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-top-wrap ' . $hide_date_likes_comments . '">';
            }
            // if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
            // } else {
            //User Thumbnail

            $fb_hide_shared_by_etc_text = get_option('fb_hide_shared_by_etc_text');
            $fb_hide_shared_by_etc_text = isset($fb_hide_shared_by_etc_text) && $fb_hide_shared_by_etc_text == 'no' ? '' : $FBfinalstory;

            if ($show_media !== 'top') {

                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-user-thumb">';
                    $FTS_FB_OUTPUT .= '<a href="https://facebook.com/' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id) . '" target="_blank"><img border="0" alt="' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->name : $post_data->from->name) . '" src="https://graph.facebook.com/' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id) . '/picture"/></a>';
                    $FTS_FB_OUTPUT .= '</div>';




                //UserName
                $FTS_FB_OUTPUT .= $FB_Shortcode['type'] == 'reviews' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') ? '<span class="fts-jal-fb-user-name" itemprop="author" itemscope itemtype="http://schema.org/Person"><a href="https://facebook.com/' . $post_data->reviewer->id . '/" target="_blank" ><span itemprop="name">' . $post_data->reviewer->name . '</span></a>' . $FTS_Facebook_Reviews->reviews_rating_format($FB_Shortcode, $post_data->rating) . '</span>' : '<span class="fts-jal-fb-user-name"><a href="https://facebook.com/' . $post_data->from->id . '" target="_blank">' . $post_data->from->name . '</a>' . $fb_hide_shared_by_etc_text . '</span>';

                // tied to date function
                $feed_type = 'facebook';
                $times = $CustomTimeFormat;
                $fts_final_date = $this->fts_custom_date($times, $feed_type);
                //PostTime
                $FTS_FB_OUTPUT .= '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';
            }


            if($FB_Shortcode['type'] !== 'reviews') {
                //Comments Count
                $FBpost_id_final = substr($FBpost_id, strpos($FBpost_id, "_") + 1);
            }
            //filter messages to have urls
            //Output Message
            if ($FBmessage && $show_media !== 'top') {

                $itemprop_description_reviews = is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') ? ' itemprop="description"' : '';

                // here we trim the words for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
                if (is_plugin_active('feed-them-premium/feed-them-premium.php') && array_key_exists('words', $FB_Shortcode) && $show_media !== 'top' || is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php') && array_key_exists('words', $FB_Shortcode) && $FB_Shortcode['words'] !== '' && $show_media !== 'top' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && array_key_exists('words', $FB_Shortcode) && $show_media !== 'top') {
                    $more = isset($more) ? $more : "";
                    $trimmed_content = $this->fts_custom_trim_words($FBmessage, $FB_Shortcode['words'], $more);

                    // Going to consider this for the future if facebook fixes the api to define when are checking in. Add  '.$checked_in.' inside the fts-jal-fb-message div
                    // $checked_in = '<a target="_blank" class="fts-checked-in-img" href="https://www.facebook.com/'.$post_data->place->id.'"><img src="https://graph.facebook.com/'.$post_data->place->id.'/picture?width=150"/></a><a target="_blank" class="fts-checked-in-text-link" href="https://www.facebook.com/'.$post_data->place->id.'">'.__("Checked in at", "feed-them-social").' '.$post_data->place->name.'</a><br/> '.__("Location", "feed-them-social").': '.$post_data->place->location->city.', '.$post_data->place->location->country.' '.$post_data->place->location->zip.'<br/><a target="_blank" class="fts-fb-get-directions fts-checked-in-get-directions" href="https://www.facebook.com/'.$post_data->place->id.'">'.__("Get Direction", "feed-them-social").'</a>';

                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message"'.$itemprop_description_reviews.'>';

                    $FTS_FB_OUTPUT .= !empty($trimmed_content) ? $trimmed_content : '';
                    //If POPUP
                    // $FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';
                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div> ';

                } elseif($show_media !== 'top') {
                    $FB_final_message = $this->fts_facebook_tag_filter($FBmessage);
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message"'.$itemprop_description_reviews.'>';
                    $FTS_FB_OUTPUT .= nl2br($FB_final_message);
                    //If POPUP
                    // $FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';

                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }

            }
            elseif (!$FBmessage && $FB_Shortcode['type'] == 'album_photos' || !$FBmessage && $FB_Shortcode['type'] == 'albums') {

                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';

                $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_desc($FBname, $FB_Shortcode, $FBtype, NULL, $FBby) : '';

                //Output Photo Caption
                $FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
                //Photo Count
                $FTS_FB_OUTPUT .= $FBalbum_photo_count ? $FBalbum_photo_count . ' Photos' : '';
                //Location
                $FTS_FB_OUTPUT .= $FBlocation ? $this->fts_facebook_location($FBtype, $FBlocation) : '';
                //Output Photo Description
                $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';



                //Output Photo Description
                if (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
                    $FTS_FB_OUTPUT .= '<div class="fts-fb-caption fts-fb-album-view-link">';
                    if ($FBalbum_picture) {
                        $FTS_FB_OUTPUT .= '<a href="' . $FBalbum_picture . '" class="fts-view-album-photos-large" target="_blank">' . __('View Photo', 'feed-them-social') . '</a></div>';
                    } elseif (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                        if ($FB_Shortcode['play_btn'] !== 'yes') {
                            if(isset($post_data->format[3]->picture)){
                                $PhotoOption = $post_data->format[3]->picture;
                            }
                            elseif(isset($post_data->format[2]->picture)){
                                $PhotoOption = $post_data->format[2]->picture;
                            }
                            elseif(isset($post_data->format[1]->picture)){
                                $PhotoOption = $post_data->format[1]->picture;
                            }
                            elseif(isset($post_data->format[0]->picture)){
                                $PhotoOption = $post_data->format[0]->picture;
                            }
                            else {
                                $PhotoOption = '';
                            }
                            $FTS_FB_OUTPUT .= '<a href="' . $post_data->source . '"  data-poster="' . $PhotoOption .'" id="fts-view-vid1-' . $fts_dynamic_vid_name_string . '" class="fts-jal-fb-vid-html5video fts-view-fb-videos-large fts-view-fb-videos-btn fb-video-popup-' . $fts_dynamic_vid_name_string . '">' . __('View Video', 'feed-them-social') . '</a>';
                        }
                        $FTS_FB_OUTPUT .= '</div>';
                    } else {
                        //photos
                        $FTS_FB_OUTPUT .= '<a href="'.$post_data->source.'" class="fts-view-album-photos-large" target="_blank">' . __('View Photo', 'feed-them-social') . '</a></div>';
                    }

                    //      $FTS_FB_OUTPUT .= '<div class="fts-fb-caption"><a class="view-on-facebook-albums-link" href="' . $FBlink . '" target="_blank">' . __('View on Facebook', 'feed-them-social') . '</a></div>';
                }

                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
            } //END Output Message
            elseif ($FBmessage == '' && $FB_Shortcode['type'] !== 'album_photos' || $FBmessage == '' && $FB_Shortcode['type'] !== 'albums') {
                //If POPUP
                //  $FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-jal-fb-message"><div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div></div>' : '';
            }
            if ($show_media !== 'top') {
                $FTS_FB_OUTPUT .= '</div>'; // end .fts-jal-fb-top-wrap <!--end fts-jal-fb-top-wrap -->
            }

        }
        //Post Type Build
        $fts_show_post = false;
        switch ($FBtype) {
            //**************************************************
            // START NOTE POST
            //**************************************************
            case 'note':
                //  && !$FBpicture == '' makes it so the attachment unavailable message does not show up
                //  if (!$FBpicture && !$FBname && !$FBdescription && !$FBpicture == '') {
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap">';
                //Output Link Picture
                $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->full_picture) : '';

                if ($FBname || $FBcaption || $FBdescription) {
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
                    //Output Link Name
                    $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
                    //Output Link Caption
                    if ($FBcaption == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.') {
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-caption" style="width:100% !important">';
                        _e('This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social');
                        $FTS_FB_OUTPUT .= '</div>';
                    } else {
                        //    $FTS_FB_OUTPUT .= $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype);
                    }
                    //If POPUP
                    //    $FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';
                    //Output Link Description
                    //  $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype) : '';
                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }


                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                //   }

                break;

            //**************************************************
            // START STATUS POST
            //**************************************************
            case 'status':
                //  && !$FBpicture == '' makes it so the attachment unavailable message does not show up
                if (!$FBpicture && !$FBname && !$FBdescription && !$FBpicture == '') {
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap">';
                    //Output Link Picture
                    $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';

                    if ($FBname || $FBcaption || $FBdescription) {
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
                        //Output Link Name
                        $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
                        //Output Link Caption
                        if ($FBcaption == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.') {
                            $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-caption" style="width:100% !important">';
                            _e('This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social');
                            $FTS_FB_OUTPUT .= '</div>';
                        } else {
                            $FTS_FB_OUTPUT .= $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype);
                        }
                        //Output Link Description
                        $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype) : '';
                        $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                    }


                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }

                break;
            //**************************************************
            // Start Multiple Events
            //**************************************************
            case 'events':
                $single_event_id = $post_data->id;
                $single_event_info = json_decode($single_event_array_response['event_single_' . $single_event_id . '_info']);
                $single_event_location = json_decode($single_event_array_response['event_single_' . $single_event_id . '_location']);
                $single_event_cover_photo = json_decode($single_event_array_response['event_single_' . $single_event_id . '_cover_photo']);
                $single_event_ticket_info = json_decode($single_event_array_response['event_single_' . $single_event_id . '_ticket_info']);
                // echo'<pre>';
                // print_r($single_event_info);
                // echo'</pre>';
                //Event Cover Photo
                $event_cover_photo = isset($single_event_cover_photo->cover->source) ? $single_event_cover_photo->cover->source : "";
                $event_description = isset($single_event_info->description) ? $single_event_info->description : "";
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-right-wrap fts-events-list-wrap">';
                //Link Picture
                $FB_event_name = isset($single_event_info->name) ? $single_event_info->name : "";
                $FB_event_location = isset($single_event_location->place->name) ? $single_event_location->place->name : "";
                $FB_event_city = isset($single_event_location->place->location->city) ? $single_event_location->place->location->city . ', ' : "";
                $FB_event_state = isset($single_event_location->place->location->state) ? $single_event_location->place->location->state : "";
                $FB_event_street = isset($single_event_location->place->location->street) ? $single_event_location->place->location->street : "";
                $FB_event_zip = isset($single_event_location->place->location->zip) ? ' ' . $single_event_location->place->location->zip : "";
                $FB_event_latitude = isset($single_event_location->place->location->latitude) ? $single_event_location->place->location->latitude : "";
                $FB_event_longitude = isset($single_event_location->place->location->longitude) ? $single_event_location->place->location->longitude : "";
                $FB_event_ticket_info = isset($single_event_ticket_info->ticket_uri) ? $single_event_ticket_info->ticket_uri : "";
                date_default_timezone_set(get_option('fts-timezone'));

                // custom one day ago check
                if ($CustomDateCheck == 'one-day-ago') {
                    $FB_event_start_time = date_i18n('l, F jS, Y \a\t g:ia', strtotime($single_event_info->start_time));
                } else {
                    $FB_event_start_time = date_i18n($CustomDateFormat, strtotime($single_event_info->start_time));
                }


                //Output Photo Description
                if (!empty($event_cover_photo)) {
                    $FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' && is_plugin_active('feed-them-premium/feed-them-premium.php') ? '<a href="' . $event_cover_photo . '" class="fts-jal-fb-picture fts-fb-large-photo" target="_blank"><img class="fts-fb-event-photo" src="' . $event_cover_photo . '"></a>' : '<a href="https://facebook.com/events/' . $single_event_id . '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img class="fts-fb-event-photo" src="' . $event_cover_photo . '" /></a>';
                }
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-top-wrap">';
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message">';
                //Link Name
                $FTS_FB_OUTPUT .= '<div class="fts-event-title-wrap">';
                $FTS_FB_OUTPUT .= $FB_event_name ? $this->fts_facebook_post_name('https://facebook.com/events/' . $single_event_id . '', $FB_event_name, $FBtype) : '';
                $FTS_FB_OUTPUT .= '</div>';
                //Link Caption

                $FTS_FB_OUTPUT .= $FB_event_start_time ? '<div class="fts-fb-event-time">' . $FB_event_start_time . '</div>' : '';
                //Link Description
                if (!empty($FB_event_location)) {
                    $FTS_FB_OUTPUT .= '<div class="fts-fb-location"><span class="fts-fb-location-title">' . $FB_event_location . '</span>';
                    //Street Adress
                    $FTS_FB_OUTPUT .= $FB_event_street;
                    //City & State
                    $FTS_FB_OUTPUT .= ($FB_event_city or $FB_event_state) ? '<br/>' . $FB_event_city . $FB_event_state . $FB_event_zip : '';
                    $FTS_FB_OUTPUT .= '</div>';
                }
                //Get Directions
                if (!empty($FB_event_latitude) && !empty($FB_event_longitude)) {
                    $FTS_FB_OUTPUT .= '<a target="_blank" class="fts-fb-get-directions" href="https://www.google.com/maps/dir/Current+Location/' . $FB_event_latitude . ',' . $FB_event_longitude . '
">' . __('Get Directions', 'feed-them-social') . '</a>';
                }
                if (!empty($FB_event_ticket_info) && !empty($FB_event_ticket_info)) {
                    $FTS_FB_OUTPUT .= '<a target="_blank" class="fts-fb-ticket-info" href="' . $single_event_ticket_info->ticket_uri . '">' . __('Ticket Info', 'feed-them-social') . '</a>';
                }
                //Output Message
                if (!empty($FB_Shortcode['words']) && $event_description && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                    // here we trim the words for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
                    $FTS_FB_OUTPUT .= $this->fts_facebook_post_desc($event_description, $FB_Shortcode, $FBtype, NULL, $FBby, $FB_Shortcode['type']);
                } //END is_plugin_active
                // if the premium plugin is not active we will just show the regular full description
                else {
                    $FTS_FB_OUTPUT .= $this->fts_facebook_post_desc($event_description, $FBtype, NULL, $FBby, $FB_Shortcode['type']);
                }
                //If POPUP
                //   $FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="https://facebook.com/events/' . $single_event_id . '" class="fts-view-on-facebook-link" target="_blank">' . __('View Event on Facebook', 'feed-them-facebook') . '</a></div> ' : '';

                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div></div>';
                break;
            //**************************************************
            // START LINK POST
            //**************************************************
            case 'link':
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap">';
                //start url check
                $url = $FBlink;
                $url_parts = parse_url($url);
                $host = $url_parts['host'];

                if ($host == 'www.facebook.com') {
                    $spliturl = $url_parts['path'];
                    $path_components = explode('/', $spliturl);
                    $first_dir = $path_components[1];
                }
                //end url check

                //Output Link Picture
                //  $FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-social') . '</a></div> ' : '';


                if ($host == 'www.facebook.com' and $first_dir == 'events') {
                    $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';
                }
                elseif (strpos($FBlink, 'soundcloud') > 0) {
                    //Get the SoundCloud URL
                    $url = $FBlink;
                    //Get the JSON data of song details with embed code from SoundCloud oEmbed
                    $getValues = file_get_contents('http://soundcloud.com/oembed?format=js&url=' . $url . '&auto_play=true&iframe=true');
                    //Clean the Json to decode
                    $decodeiFrame = substr($getValues, 1, -2);
                    //json decode to convert it as an array
                    $jsonObj = json_decode($decodeiFrame);
                    //Change the height of the embed player if you want else uncomment below line
                    // echo str_replace('height="400"', 'height="140"', $jsonObj->html);
                    $fts_dynamic_vid_name_string = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);
                    $fts_dynamic_vid_name = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-picture ' . $fts_dynamic_vid_name . '">';
                            if (!empty($post_data->full_picture)) {
                                $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo('javascript:;', $FB_Shortcode, $post_data->from->name, $post_data->full_picture) : '';
                            } elseif (!empty($post_data->picture)) {
                                $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo('javascript:;', $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';
                            }
                            $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-play-btn"></div>';
                        $FTS_FB_OUTPUT .= '</div>';
                    $FTS_FB_OUTPUT .= '<script>';
                    $FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
                    $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_vid_name . '").click(function() {';
                    $FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
                    $FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
                    $FTS_FB_OUTPUT .= '	jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper">' . $jsonObj->html . '</div>\');';
                    if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                        $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
                        $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
                    }
                    $FTS_FB_OUTPUT .= '});';
                    $FTS_FB_OUTPUT .= '});';
                    $FTS_FB_OUTPUT .= '</script>';
                } elseif (!empty($post_data->full_picture)) {
                    $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->full_picture) : '';
                } elseif (!empty($post_data->picture)) {
                    $FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';
                }


                $FB_Shortcode['words'] = isset($FB_Shortcode['words']) ? $FB_Shortcode['words'] : "";
                //Description Wrap
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
                //Output Link Name
                $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
                if ($host == 'www.facebook.com' and $first_dir == 'events') {
                    $FTS_FB_OUTPUT .= ' &#9658; ';
                    $FTS_FB_OUTPUT .= '<a href="' . $FBlink . '" class="fts-jal-fb-name" target="_blank">' . $FBLinkEventName . '</a>';
                }//end if event
                //Output Link Description
                $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype) : '';


                //Output Link Caption
                $FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                break;
            //**************************************************
            // START VIDEO POST
            //**************************************************
            case 'video'  :
                $video_data = json_decode($response_post_array[$post_data_key . '_video']);

                // echo '<pre>';
                //   print_r($video_data);
                // echo '</pre>';

                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-wrap">';

                if (!empty($FBpicture)) {
                    if ((strpos($FBvideo_embed, 'fbcdn') > 0) && !empty($video_data->format)) {
                        if (!empty($video_data->format)) {
                            foreach ($video_data->format as $video_data_format) {
                                if ($video_data_format->filter == 'native') {
                                    //This line is here so we can fetch the source to feed into the popup since some html 5 videos can be displayed without the need for a button.
                                    $FTS_FB_OUTPUT .= '<a href="' . $video_data->source . '" style="display:none !important" class="fts-facebook-link-target fts-jal-fb-vid-image fts-video-type"></a>';
                                    $FTS_FB_OUTPUT .= '<div class="fts-fluid-videoWrapper-html5">';
                                    $FTS_FB_OUTPUT .= '<video controls poster="' . $video_data_format->picture . '" width="100%;" style="max-width:100%;">';
                                    $FTS_FB_OUTPUT .= '<source src="' . $video_data->source . '" type="video/mp4">';
                                    $FTS_FB_OUTPUT .= '</video>';
                                    $FTS_FB_OUTPUT .= '</div>';
                                }
                            }
                            $FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
                        }
                    } elseif ((strpos($FBvideo_embed, 'fbcdn') > 0) && empty($video_data->format)) {

                        $fts_dynamic_vid_name_string = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);
                        $fts_dynamic_vid_name = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-picture ' . $fts_dynamic_vid_name . '">';







                        // This puts the video in a popup instead of displaying it directly on the page.
                        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
                            $FTS_FB_OUTPUT .= '<a href="' . $post_data->source . '" class="fts-facebook-link-target fts-jal-fb-vid-html5video">';
                        }







                        $FTS_FB_OUTPUT .= '<img border="0" alt="' . $post_data->from->name . '" src="' . $post_data->full_picture . '"/>';
                        // CLOSE This puts the video in a popup instead of displaying it directly on the page.
                        // This puts the video in a popup instead of displaying it directly on the page.
                        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
                            $FTS_FB_OUTPUT .= '</a>';
                        }
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-play-btn"></div>';

                        $FTS_FB_OUTPUT .= '<div class="fts-fluid-videoWrapper-html5"><video id="' . $fts_dynamic_vid_name . '" controls width="100%;" style="max-width:100%;"><source src="" type="video/mp4"></video></div><div class="slicker-facebook-album-photoshadow"></div></div>';

                        // This puts the video on the page instead of the popup if you don't have the premium version
                        if (!isset($FB_Shortcode['popup']) || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] !=='yes' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == '' || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'no') {
                            $FTS_FB_OUTPUT .= '<script>jQuery(document).ready(function() {';
                            $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_vid_name . '").bind("click", function() {';
                            $FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
                            $FTS_FB_OUTPUT .= 'if(jQuery(this).hasClass("fts-jal-fb-vid-picture")){var video = jQuery("#' . $fts_dynamic_vid_name . '"); video[0].src = "' . $post_data->source . '";video[0].load(); video[0].play();}';
                            $FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture"); ';
                            if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                                $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
                                $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
                            }
                            $FTS_FB_OUTPUT .= '});';
                            $FTS_FB_OUTPUT .= '});</script>';
                        }
                    } else {
                        //Create Dynamic Class Name
                        $fts_dynamic_vid_name_string = trim($this->rand_string(10) . '_' . $FB_Shortcode['type']);
                        $fts_dynamic_vid_name = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-picture ' . $fts_dynamic_vid_name . '">';




                        if (strpos($FBvideo_embed, 'youtube') > 0 || strpos($FBvideo_embed, 'youtu.be') > 0) {
                            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $FBvideo_embed, $matches);
                            $videoURLfinal = 'https://www.youtube.com/watch?v=' . $matches[1];
                        } else {
                            $videoURLfinal = $FBvideo_embed;
                        }






                        // This puts the video in a popup instead of displaying it directly on the page.
                        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {

                            if(strpos($FBlink, 'youtube') > 0 || strpos($FBlink, 'youtu.be') > 0 || strpos($FBlink, 'vimeo') > 0){
                                $FTS_FB_OUTPUT .= '<a href="' . $videoURLfinal . '" class="fts-facebook-link-target fts-jal-fb-vid-image fts-iframe-type">';
                            }
                            else {
                                $FTS_FB_OUTPUT .= '<a href="' . $videoURLfinal . '" class="fts-facebook-link-target fts-jal-fb-vid-html5video">';
                            }
                        }










                        $FTS_FB_OUTPUT .= '<img class="fts-jal-fb-vid-image" border="0" alt="' . $post_data->from->name . '" src="' . $post_data->full_picture . '"/>';


                        // This puts the video in a popup instead of displaying it directly on the page.
                        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
                            $FTS_FB_OUTPUT .= '</a>';
                        }


                        $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-play-btn"></div></div>';
                        // strip Youtube URL then ouput Iframe and script
                        if (strpos($FBlink, 'youtube') > 0) {
                            //  $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
                            //  preg_match($pattern, $FBlink, $matches);
                            //  $youtubeURLfinal = $matches[1];

                            // This puts the video on the page instead of the popup if you don't have the premium version
                            if (!isset($FB_Shortcode['popup']) || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] !=='yes' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == '' || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'no') {
                                $FTS_FB_OUTPUT .= '<script>jQuery(document).ready(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_vid_name . '").click(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
                                $FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
                                //  $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="https://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
                                $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . $FBpost_id . '" src="' . $FBvideo_embed . '" frameborder="0" allowfullscreen></iframe></div>\');';
                                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
                                }
                                $FTS_FB_OUTPUT .= '});';
                                $FTS_FB_OUTPUT .= '});</script>';
                            }
                        } //strip Youtube URL then ouput Iframe and script
                        elseif (strpos($FBlink, 'youtu.be') > 0) {
                            //  $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
                            //  preg_match($pattern, $FBlink, $matches);
                            //  $youtubeURLfinal = $matches[1];
                            // This puts the video in a popup instead of displaying it directly on the page.
                            if (!isset($FB_Shortcode['popup']) || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] !=='yes' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == ' ' || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'no') {
                                $FTS_FB_OUTPUT .= '<script>';
                                $FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_vid_name . '").click(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
                                $FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
                                // $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
                                $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . $FBpost_id . '" src="' . $FBvideo_embed . '" frameborder="0" allowfullscreen></iframe></div>\');';
                                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
                                }
                                $FTS_FB_OUTPUT .= '});';
                                $FTS_FB_OUTPUT .= '});';
                                $FTS_FB_OUTPUT .= '</script>';
                            }
                        } //strip Vimeo URL then ouput Iframe and script
                        elseif (strpos($FBlink, 'vimeo') > 0) {
                            //   $pattern = '/(\d+)/';
                            //   preg_match($pattern, $FBlink, $matches);
                            //   $vimeoURLfinal = $matches[0];

                            // This puts the video in a popup instead of displaying it directly on the page.
                            if (!isset($FB_Shortcode['popup']) || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] !=='yes' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == '' || isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'no') {
                                $FTS_FB_OUTPUT .= '<script>';
                                $FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(".' . $fts_dynamic_vid_name . '").click(function() {';
                                $FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
                                $FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
                                //  $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$FBpost_id.'" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';
                                $FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="' . $FBvideo_embed . '" class="video' . $FBpost_id . '" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';

                                if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
                                    $FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
                                }
                                $FTS_FB_OUTPUT .= '});';
                                $FTS_FB_OUTPUT .= '});';
                                $FTS_FB_OUTPUT .= '</script>';
                            }
                        }
                    }
                }
                if ($FBname || $FBcaption || $FBdescription) {
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap fb-id' . $FBpost_id . '">';
                    //Output Video Name
                    $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype, $FBpost_id) : '';
                    //Output Video Description
                    $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, $FBpost_id) : '';
                    //Output Video Caption
                    $FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype, $FBpost_id) : '';
                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                break;
            //**************************************************
            // START PHOTO POST
            //**************************************************
            case 'photo':

                if (isset($fts_hide_photos_type) && $fts_hide_photos_type == 'yes' && $FB_Shortcode['type'] !== 'album_photos' && $FB_Shortcode['video_album'] !== 'yes') break;

                //Wrapping with if statement to prevent Notice on some facebook page feeds.
                if ($FB_Shortcode['type'] == 'group') {
                    $photo_source = json_decode($response_post_array[$post_data_key . '_group_post_photo']);
                }
                //Group or page?
                $photo_source_final = isset($photo_source->full_picture) && $FB_Shortcode['type'] == 'group' || isset($post_data->full_picture) && $FB_Shortcode['type'] == 'event' || isset($post_data->full_picture) && $FB_Shortcode['type'] == 'page' ? $post_data->full_picture : 'https://graph.facebook.com/' . $FBpost_object_id . '/picture';




                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
                if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
                    $FTS_FB_OUTPUT .= ' style="line-height:' . $FB_Shortcode['image_height'] . ' !important;"';
                }
                $FTS_FB_OUTPUT .= '>';
                //   $FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-social') . '</a></div> ' : '';
                //Output Photo Picture
                if ($FBpost_object_id) {
                    if ($FBpost_object_id) {

                        // if we have more than one attachment we get the first image width and set that for the max width
                        $fts_fb_image_count = isset($post_data->attachments->data[0]->subattachments->data) ? count($post_data->attachments->data[0]->subattachments->data) : '0';
                        // $FTS_FB_OUTPUT .= $fts_fb_image_count;
                        if ($fts_fb_image_count == '0' || $fts_fb_image_count > 2) {

                           // $FTS_FB_OUTPUT .= $fts_fb_image_count;
                            $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $photo_source_final : $FBlink) . '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo" ><img border="0" alt="' . $post_data->from->name . '" src="' . $photo_source_final . '"></a>';

                        }


                        if ($FBpictureGallery1 !== '') {


                            // we count the number of attachments in the subattachments->data portion of the array and count the objects http://php.net/manual/en/function.count.php
                            $fts_fb_image_counter = $fts_fb_image_count - 3;
                            $fts_fb_image_count_output = '<div class="fts-image-count-tint-underlay"></div><div class="fts-image-count"><span>+</span>'.$fts_fb_image_counter.'</div>';

                            $fts_fb_image_count_check = $fts_fb_image_count < 3 ? ' fts-more-images-tint' : '';

                            $FBpictureGallery1Check = $FBpictureGallery2 == '' ? '100%;' : $FBpictureGallery0Width . 'px';
                            // if we only have 2 photos we show them side by side
                            $FBpictureGallery2True = $FBpictureGallery2 == '' ? ' fts-more-photos-auto-width' : '';
                            // if we have 3 photos we add this class so we can make the 2 attachments below the large image will fit side by side
                            $FBpictureGallery3True = $FBpictureGallery3 == ''  && $FBpictureGallery2 !== '' ? ' fts-more-photos-three-photo-wrap' : '';

                            $columnsCSS = '';

                           // print $fts_fb_image_count;

                            if($fts_fb_image_count === 2){
                                $columns = '2';
                                $columnsCSS = 'fts-more-photos-2-or-3-photos ';
                                $morethan3 = 'fts-2-photos ';
                            }
                            elseif($fts_fb_image_count === 3) {
                                $columns = '2';
                                $columnsCSS = 'fts-more-photos-2-or-3-photos ';
                                $morethan3 = 'fts-3-photos ';
                            }
                            elseif($fts_fb_image_count >= 4) {
                                $columns = '3';
                                $columnsCSS = 'fts-more-photos-4-photos ';
                                $morethan3 = 'fts-4-photos ';
                            }

                            $FTS_FB_OUTPUT .= '<div class="fts-clear"></div><div class="'.$columnsCSS.'fts-fb-more-photos-wrap fts-facebook-inline-block-centered' . $FBpictureGallery2True . $FBpictureGallery3True . '" style="max-width:' . $FBpictureGallery1Check . '" data-ftsi-id=' . $fts_dynamic_vid_name_string . ' data-ftsi-columns="'.$columns.'" data-ftsi-margin="1px" data-ftsi-force-columns="yes">';
                        }
                          if($fts_fb_image_count === 2) {
                              $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $photo_source_final : $FBlink) . '" target="_blank" class="slicker-facebook-placeholder fts-fb-thumbs-wrap '.$morethan3.'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . $photo_source_final . ');"></a>';

                          }
                        if ($FBpictureGallery1 !== '') {
                                $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $FBpictureGallery1 : $FBpictureGalleryLink1) . '" target="_blank" class="slicker-facebook-placeholder fts-fb-thumbs-wrap '.$morethan3.'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . $FBpictureGallery1 . ');"></a>';

                            if($FBpictureGallery2 !== ''){
                                $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $FBpictureGallery2 : $FBpictureGalleryLink2) . '" target="_blank" class="fts-2-or-3-photos slicker-facebook-placeholder fts-fb-thumbs-wrap '.$morethan3.'fts-fb-thumb-one-wrap fts-fb-large-photo" style="background:url(' . $FBpictureGallery2 . ');"></a>';

                            }
                            if($FBpictureGallery3 !== ''){
                                $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $FBpictureGallery3 : $FBpictureGalleryLink3) . '" target="_blank" class="slicker-facebook-placeholder fts-fb-thumbs-wrap '.$morethan3.'fts-fb-thumb-two-wrap fts-fb-large-photo'.$fts_fb_image_count_check.'" style="background:url(' . $FBpictureGallery3 . ');">'. $fts_fb_image_count_output .'</a>';
                            }
                        }
                        if ($FBpictureGallery1 !== '') {
                            $FTS_FB_OUTPUT .= '</div>';
                        }
                    }
                    else {
                        $FTS_FB_OUTPUT .= '<a href="' . (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? $photo_source_final : $FBlink) . '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . $post_data->from->name . '" src="' . $photo_source_final . '"></a>';
                    }
                } elseif ($FBpicture) {
                    if ($FBpost_object_id) {
                        $FTS_FB_OUTPUT .= $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, 'https://graph.facebook.com/' . $FBpost_object_id . '/picture');
                    } else {

                        if(isset($post_data->format[1]->picture)){
                            $video_photo = $post_data->format[1]->picture;
                        }
                        elseif(isset($post_data->format[0]->picture)){
                            $video_photo = $post_data->format[0]->picture;
                        }
                        else{
                            $video_photo = $post_data->picture;
                        }

                        $FTS_FB_OUTPUT .= isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $video_photo) : $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->source);
                    }
                }
                $FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
                // FB Video play button for facebook videos. This button takes data from our a tag and along with additional js in the magnific-popup.js we can now load html5 videos. SO lightweight this way because no pre-loading of videos are on the page. We only show the posterboard on mobile devices because tablets and desktops will auto load the videos. SRL
                if (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
                    if (isset($FB_Shortcode['play_btn']) && $FB_Shortcode['play_btn'] == 'yes') {
                        $fb_play_btn_visible = isset($FB_Shortcode['play_btn_visible']) && $FB_Shortcode['play_btn_visible'] == 'yes' ? ' visible-video-button' : '';
                        $post_data_source = isset($post_data->source) ? $post_data->source : '';
                        $post_data_format_3_picture = isset($post_data->format[3]->picture) ? $post_data->format[3]->picture : '';

                        $FTS_FB_OUTPUT .= '<a href="' . $post_data_source . '" data-poster="' . $post_data_format_3_picture . '" id="fts-view-vid1-' . $fts_dynamic_vid_name_string . '" title="' . $FBdescription . '" class="fts-jal-fb-vid-html5video fts-view-fb-videos-btn fb-video-popup-' . $fts_dynamic_vid_name_string . $fb_play_btn_visible . ' fts-slicker-backg" style="height:' . $FB_Shortcode['play_btn_size'] . ' !important; width:' . $FB_Shortcode['play_btn_size'] . '; line-height: ' . $FB_Shortcode['play_btn_size'] . '; font-size:' . $FB_Shortcode['play_btn_size'] . '"><span class="fts-fb-video-icon" style="height:' . $FB_Shortcode['play_btn_size'] . '; width:' . $FB_Shortcode['play_btn_size'] . '; line-height:' . $FB_Shortcode['play_btn_size'] . '; font-size:' . $FB_Shortcode['play_btn_size'] . '"></span></a>';
                    }
                }
                if (!$FB_Shortcode['type'] == 'album_photos') {
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap" style="display:none">';
                    //Output Photo Name
                    $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
                    //Output Photo Caption
                    $FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
                    //Output Photo Description
                    $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                break;

            //**************************************************
            // START ALBUM POST
            //**************************************************
            case 'app':
            case 'cover':
            case 'profile':
            case 'mobile':
            case 'wall':
            case 'normal':
            case 'album':
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
                if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
                    $FTS_FB_OUTPUT .= ' style="line-height:' . $FB_Shortcode['image_height'] . ' !important;"';
                }
                $FTS_FB_OUTPUT .= '>';
                //Output Photo Picture
                $FTS_FB_OUTPUT .= $FBalbum_cover ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $FBalbum_picture) : '';
                $FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
                if (!$FB_Shortcode['type'] == 'albums') {
                    $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
                    //Output Photo Name
                    $FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
                    //Output Photo Caption
                    $FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
                    //Output Photo Description
                    $FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
                    $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                }
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
                break;

        }
// This puts the video in a popup instead of displaying it directly on the page.
        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
            //Post Comments
            $FTS_FB_OUTPUT .= '<div class="fts-fb-comments-wrap">';
            $hide_comments_popup = isset($FB_Shortcode['hide_comments_popup']) ? $FB_Shortcode['hide_comments_popup'] : 'no';
            if (isset($lcs_array['comments_thread']->data) && !empty($lcs_array['comments_thread']->data) && $hide_comments_popup !== 'yes' || isset($lcs_array['comments_thread']->data) && !empty($lcs_array['comments_thread']->data) && $hide_comments_popup == ''){
                //Post Comments
                $FTS_FB_OUTPUT .= '<div class="fts-fb-comments-content fts-comments-post-' . $FBpost_id . '">';

                foreach ($lcs_array['comments_thread']->data as $comment) {
                    $FTS_FB_OUTPUT .= '<div class="fts-fb-comment fts-fb-comment-' . $comment->id . '">';
                    //User Profile Img
                    $avatar_id = isset($comment->from->id) ? $comment->from->id : '118790751525884';
                    $FTS_FB_OUTPUT .= '<img class="fts-fb-comment-user-pic" src="https://graph.facebook.com/' . $avatar_id . '/picture?type=square"/>';
                    $FTS_FB_OUTPUT .= '<div class="fts-fb-comment-msg">';
                    if(isset($comment->from->name)) {
                        $FTS_FB_OUTPUT .= '<span class="fts-fb-comment-user-name">' . $comment->from->name . '</span> ';
                    }
                    $FTS_FB_OUTPUT .= $comment->message . '</div>';

                    //Comment Message
                    $FTS_FB_OUTPUT .= '</div>';
                }
                $FTS_FB_OUTPUT .= '</div>';
            }
            $FTS_FB_OUTPUT .= '</div><!-- END Comments Wrap -->';
        }


        //filter messages to have urls
        //Output Message For combined feeds in the bottom
        if ($FBmessage && isset($FB_Shortcode['show_media']) && $show_media == 'top') {


            if (isset($FB_Shortcode['show_social_icon']) && $FB_Shortcode['show_social_icon'] == 'right') {
                $FTS_FB_OUTPUT .= '<div class="fts-mashup-icon-wrap-right fts-mashup-facebook-icon"><a href="https://facebook.com/' . $post_data->from->id . '" target="_blank"></a></div>';
            }
            //show icon
            if (isset($FB_Shortcode['show_social_icon']) && $FB_Shortcode['show_social_icon'] == 'left') {
                $FTS_FB_OUTPUT .= '<div class="fts-mashup-icon-wrap-left fts-mashup-facebook-icon"><a href="https://facebook.com/' . $post_data->from->id . '" target="_blank"></a></div>';
            }
            $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-top-wrap ' . $hide_date_likes_comments . '" style="display:block !important;">';
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-user-thumb">';
                $FTS_FB_OUTPUT .= '<a href="https://facebook.com/' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id) . '" target="_blank"><img border="0" alt="' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->name : $post_data->from->name) . '" src="https://graph.facebook.com/' . ($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id) . '/picture"/></a>';
                $FTS_FB_OUTPUT .= '</div>';

                //UserName
                $FTS_FB_OUTPUT .= '<span class="fts-jal-fb-user-name"><a href="https://facebook.com/' . $post_data->from->id . '" target="_blank">' . $post_data->from->name . '</a>' . $fb_hide_shared_by_etc_text . '</span>';

                // tied to date function
                $feed_type = 'facebook';
                $times = $CustomTimeFormat;
                $fts_final_date = $this->fts_custom_date($times, $feed_type);
                //PostTime
                $FTS_FB_OUTPUT .= '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';

            // here we trim the words for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
            if (is_plugin_active('feed-them-social-combined-streams/feed-them-social-combined-streams.php') && array_key_exists('words', $FB_Shortcode) || is_plugin_active('feed-them-premium/feed-them-premium.php') && array_key_exists('words', $FB_Shortcode)) {
                $more = isset($more) ? $more : "";
                $trimmed_content = $this->fts_custom_trim_words($FBmessage, $FB_Shortcode['words'], $more);

                // Going to consider this for the future if facebook fixes the api to define when are checking in. Add  '.$checked_in.' inside the fts-jal-fb-message div
                // $checked_in = '<a target="_blank" class="fts-checked-in-img" href="https://www.facebook.com/'.$post_data->place->id.'"><img src="https://graph.facebook.com/'.$post_data->place->id.'/picture?width=150"/></a><a target="_blank" class="fts-checked-in-text-link" href="https://www.facebook.com/'.$post_data->place->id.'">'.__("Checked in at", "feed-them-social").' '.$post_data->place->name.'</a><br/> '.__("Location", "feed-them-social").': '.$post_data->place->location->city.', '.$post_data->place->location->country.' '.$post_data->place->location->zip.'<br/><a target="_blank" class="fts-fb-get-directions fts-checked-in-get-directions" href="https://www.facebook.com/'.$post_data->place->id.'">'.__("Get Direction", "feed-them-social").'</a>';

                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message">';

                $FTS_FB_OUTPUT .= !empty($trimmed_content) ? $trimmed_content : '';
                //If POPUP
                // $FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';
                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div> ';

            }
            else {
                $FB_final_message = $this->fts_facebook_tag_filter($FBmessage);
                $FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message">';
                $FTS_FB_OUTPUT .= nl2br($FB_final_message);
                //If POPUP
                // $FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $FBlink . '" class="fts-view-on-facebook-link" target="_blank">' . __('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';

                $FTS_FB_OUTPUT .= '<div class="fts-clear"></div></div>';
            }
            $FTS_FB_OUTPUT .= '</div>';

        }

        $FTS_FB_OUTPUT .= '<div class="fts-clear"></div>';
        $FTS_FB_OUTPUT .= '</div>';
        $FBpost_single_id = isset($FBpost_single_id) ? $FBpost_single_id : "";
        $final_FBpost_like_count = isset($final_FBpost_like_count) ? $final_FBpost_like_count : "";
        $final_FBpost_comments_count = isset($final_FBpost_comments_count) ? $final_FBpost_comments_count : "";
        $single_event_id = isset($single_event_id) ? $single_event_id : "";
        $FTS_FB_OUTPUT .= $this->fts_facebook_post_see_more($FBlink, $lcs_array, $FBtype, $FBpost_id, $FB_Shortcode, $FBpost_user_id, $FBpost_single_id, $single_event_id, $post_data);
        $FTS_FB_OUTPUT .= '<div class="fts-clear"></div>';
        $FTS_FB_OUTPUT .= '</div>';

        return $FTS_FB_OUTPUT;

    }//function free_post_types
}// FTS_Facebook_Feed END CLASS
?>