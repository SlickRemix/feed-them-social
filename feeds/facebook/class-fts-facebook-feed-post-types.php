<?php namespace feedthemsocial;

/**
 * Class FTS Facebook Feed Post Types
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_Facebook_Feed_Post_Types extends FTS_Facebook_Feed {


	/**
	 * Feed Location Option
	 *
	 * Display Location flag and text
	 *
	 * @param string $fb_places_id The ID.
	 * @param string $fb_name The facebook page name.
	 * @param string $fb_places_name The location name.
	 * @since 1.9.6
	 */
	public function feed_location_option( $fb_places_id, $fb_name, $fb_places_name ) {
			echo '<div class="fts-fb-location-wrap">';
			echo '<div class="fts-fb-location-img"></div>';
			echo '<a href="' . esc_url( 'https://www.facebook.com/' . $fb_places_id . '/' ) . '" class="fts-fb-location-link" target="_blank" rel="noreferrer">' . esc_attr( $fb_name ) . '</a>';
			echo '<div class="fts-fb-location-name">' . esc_html( $fb_places_name ) . '</div>';
			echo '</div>';
	}
	/**
	 * Feed Post Types
	 *
	 * Display Facebook Feed.
	 *
	 * @param string $set_zero A way to skip posts.
	 * @param string $fb_type The type of Facebook Feed.
	 * @param string $post_data All post info.
	 * @param string $fb_shortcode All shortcode options picked.
	 * @param string $response_post_array All post info.
	 * @param string $single_event_array_response All post info.
	 * @since 1.9.6
	 */
	public function feed_post_types( $set_zero, $fb_type, $post_data, $fb_shortcode, $response_post_array, $single_event_array_response = null ) {

		// echo '<pre>';
		// print_r($lcs_array);
		// echo '</pre>';
		// echo 'ASDF';
		// Reviews Plugin.
		if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
			$fts_facebook_reviews = new FTS_Facebook_Reviews();
		}

		$fts_dynamic_vid_name_string = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $fb_shortcode['type'] );

		if ( $set_zero === $fb_shortcode['posts'] ) {
			return;
		}
		// Create Facebook Variables.
		$fb_final_story               = '';
		$first_dir                    = '';
		$fb_picture                   = isset( $post_data->picture ) ? $post_data->picture : '';
		$fb_link                      = isset( $post_data->link ) ? $post_data->link : '';
		$fb_name                      = isset( $post_data->name ) ? $post_data->name : '';
		$fb_caption                   = isset( $post_data->caption ) ? $post_data->caption : '';
		$fb_description               = isset( $post_data->description ) ? $post_data->description : '';
		$fb_link_event_name           = isset( $post_data->to->data[0]->name ) ? $post_data->to->data[0]->name : '';
		$fb_story                     = isset( $post_data->story ) ? $post_data->story : '';
		$fb_icon                      = isset( $post_data->icon ) ? $post_data->icon : '';
		$fb_by                        = isset( $post_data->properties->text ) ? $post_data->properties->text : '';
		$fb_bylink                    = isset( $post_data->properties->href ) ? $post_data->properties->href : '';
		$fb_post_share_count          = isset( $post_data->shares->count ) ? $post_data->shares->count : '';
		$fb_post_like_count_array     = isset( $post_data->likes->data ) ? $post_data->likes->data : '';
		$fb_post_comments_count_array = isset( $post_data->comments->data ) ? $post_data->comments->data : '';
		$fb_post_object_id            = isset( $post_data->object_id ) ? $post_data->object_id : '';
		$fb_album_photo_count         = isset( $post_data->count ) ? $post_data->count : '';
		$fb_album_cover               = isset( $post_data->photos->data[0]->images[0]->source ) ? $post_data->photos->data[0]->images[0]->source : '';
		$fb_album_picture             = isset( $post_data->source ) ? $post_data->source : '';
		$fb_places_name               = isset( $post_data->place->name ) ? $post_data->place->name : '';
		$fb_places_id                 = isset( $post_data->place->id ) ? $post_data->place->id : '';

		$fb_attachments_title = isset( $post_data->attachments->data[0]->title ) ? $post_data->attachments->data[0]->title : '';
		$fb_attachments       = isset( $post_data->attachments ) ? $post_data->attachments : '';
		$fb_picture_job       = isset( $post_data->attachments->data[0]->media->image->src ) ? $post_data->attachments->data[0]->media->image->src : '';
		// youtube and vimeo embed url.
		$fb_video_embed = isset( $post_data->source ) ? $post_data->source : '';

		$fb_post_from_id = isset( $post_data->from->id ) ? $post_data->from->id : '';
		$from_id_picture = $fb_post_from_id !== $fb_shortcode['id'] ? $fb_shortcode['id'] : $fb_post_from_id;

		// if (isset($post_data->format[1]->picture)) {.
		// $video_photo = $post_data->format[1]->picture;.
		// } elseif (isset($post_data->format[0]->picture)) {.
		// $video_photo = $post_data->format[0]->picture;.
		// } else {.
		// $video_photo = $post_data->picture;.
		// }.
		if ( isset( $post_data->format[3]->picture ) ) {
			$video_photo = $post_data->format[3]->picture;
		} elseif ( isset( $post_data->format[2]->picture ) ) {
			$video_photo = $post_data->format[2]->picture;
		} elseif ( isset( $post_data->format[1]->picture ) ) {
			$video_photo = $post_data->format[1]->picture;
		} elseif ( isset( $post_data->format[0]->picture ) ) {
			$video_photo = $post_data->format[0]->picture;
		} else {
			$video_photo = '';
		}

		if ( ! empty( $post_data->format[3]->height ) && '0' !== $post_data->format[3]->height ) {
			$embed_html = $post_data->format[3]->embed_html;

			$embed_width  = $post_data->format[3]->width;
			$embed_height = $post_data->format[3]->height;
		} elseif ( ! empty( $post_data->format[2]->height ) && '0' !== $post_data->format[2]->height ) {
			$embed_html   = $post_data->format[2]->embed_html;
			$embed_width  = $post_data->format[2]->width;
			$embed_height = $post_data->format[2]->height;
		} elseif ( ! empty( $post_data->format[1]->height ) && '0' !== $post_data->format[1]->height ) {
			$embed_html   = $post_data->format[1]->embed_html;
			$embed_width  = $post_data->format[1]->width;
			$embed_height = $post_data->format[1]->height;
		} elseif ( ! empty( $post_data->format[0]->height ) && '0' !== $post_data->format[0]->height ) {
			$embed_html   = $post_data->format[0]->embed_html;
			$embed_width  = $post_data->format[0]->width;
			$embed_height = $post_data->format[0]->height;
		} else {
			$embed_html   = 'none';
			$embed_width  = '';
			$embed_height = '';
		}

		// This will take our embed iframe from the array and then strip out the src url for the iframe so we can.
		// add this to our maginific popup.
		if ( 'none' !== $embed_html ) {
			preg_match( '/src="([^"]+)"/', $embed_html, $match );

			$embed_html = $match[1] . '&autoplay=true';
			// we do this check so we can add a data-height class name for our popup to know that we need to add the height to the iframe.
			// otherwise we let the magnific pop up scaler class do the work.
			if ( $embed_height > $embed_width ) {
				$data_height = 'fts-greater-than-width-height';
			} elseif ( $embed_height === $embed_width ) {
				$data_height = 'fts-equal-width-height';
			} else {
				$data_height = '';
			}
			// fts-view-fb-videos-btn.
			$fts_view_fb_videos_btn = 'fts-view-fb-videos-btn';

		} else {
			$embed_html             = $video_photo;
			$fts_view_fb_videos_btn = '';
			$data_height            = '';
		}

		$fb_video         = isset( $post_data->embed_html ) ? $post_data->embed_html : '';
		$fb_video_picture = isset( $post_data->format[2]->picture ) ? $post_data->format[2]->picture : '';

		if ( $fb_album_cover ) {
			// $photo_data = json_decode( $response_post_array[ $fb_album_cover . '_photo' ] );
		}
		if ( isset( $post_data->id ) ) {
			$fb_post_id      = $post_data->id;
			$fb_post_full_id = explode( '_', $fb_post_id );
			if ( isset( $fb_post_full_id[0] ) ) {
				$fb_post_user_id = $fb_post_full_id[0];
			}
			if ( isset( $fb_post_full_id[1] ) ) {
				$fb_post_single_id = $fb_post_full_id[1];
			} else {
				$fb_post_single_id = '';
			}
		} else {
			$fb_post_id      = '';
			$fb_post_user_id = '';
		}

			$fb_joblink = isset( $post_data->id, $post_data->from->id ) ? 'https://www.facebook.com/' . $post_data->from->id . '/posts/' . $fb_post_single_id . '' : '';

		if ( 'albums' === $fb_shortcode['type'] && ! $fb_album_cover ) {
			unset( $post_data );
		}
		// Create Post Data Key.
		if ( isset( $post_data->object_id ) ) {
			$post_data_key = $post_data->object_id;
		} else {
			$post_data_key = isset( $post_data->id ) ? $post_data->id : '';
		}
		// Count Likes/Shares/.
		$lcs_array = $this->get_likes_shares_comments( $response_post_array, $post_data_key, $fb_post_share_count );

		// echo '<pre>';
		// print_r($lcs_array);
		// echo '</pre>';
		// $fb_location  = isset( $post_data->location ) ? $post_data->location : '';
		$fb_embed_vid = isset( $post_data->embed_html ) ? $post_data->embed_html : '';
		$fb_from_name = isset( $post_data->from->name ) ? $post_data->from->name : '';
		$fb_from_name = preg_quote( $fb_from_name, '/' );

		$fb_story          = isset( $post_data->story ) ? $post_data->story : '';
		$fts_custom_date   = get_option( 'fts-custom-date' ) ? get_option( 'fts-custom-date' ) : '';
		$fts_custom_time   = get_option( 'fts-custom-time' ) ? get_option( 'fts-custom-time' ) : '';
		$custom_date_check = get_option( 'fts-date-and-time-format' ) ? get_option( 'fts-date-and-time-format' ) : '';

		$fb_picture_gallery1 = isset( $post_data->attachments->data[0]->subattachments->data[1]->media->image->src ) ? $post_data->attachments->data[0]->subattachments->data[1]->media->image->src : '';
		$fb_picture_gallery2 = isset( $post_data->attachments->data[0]->subattachments->data[2]->media->image->src ) ? $post_data->attachments->data[0]->subattachments->data[2]->media->image->src : '';
		$fb_picture_gallery3 = isset( $post_data->attachments->data[0]->subattachments->data[3]->media->image->src ) ? $post_data->attachments->data[0]->subattachments->data[3]->media->image->src : '';

		// we get the width of the first attachment so we can set the max width for the frame around the main image and thumbs.. this makes it so our percent width on thumbnails are nice and aligned.
		$fb_picture_gallery0_width = isset( $post_data->attachments->data[0]->subattachments->data[0]->media->image->src ) ? $post_data->attachments->data[0]->subattachments->data[0]->media->image->width : '';

		// June 22, 2017 - Going to leave the attachments description idea for a future update, lots more work to get the likes and comments for attachments and have that info be in the popup.
		// $fb_pictureGalleryDescription0 = isset($post_data->attachments->data[0]->subattachments->data[1]->description) ? $post_data->attachments->data[0]->subattachments->data[1]->media->image->src : '';.
		// $fb_pictureGalleryDescription1 = isset($post_data->attachments->data[0]->subattachments->data[2]->description)? $post_data->attachments->data[0]->subattachments->data[2]->media->image->src :  '';.
		// $fb_pictureGalleryDescription2 = isset($post_data->attachments->data[0]->subattachments->data[3]->description) ? $post_data->attachments->data[0]->subattachments->data[3]->media->image->src : '';.
		// KZeni Edit: https://github.com/KZeni
		// February 25, 2019 - Uncommented Description variables so they can be used when making it so the pictures meet accessibility standards.
		$picture_from_fb               = __( 'Picture from Facebook', 'feed-them-social' );
		$fb_pictureGalleryDescription0 = isset( $post_data->attachments->data[0]->subattachments->data[1]->description ) ? $post_data->attachments->data[0]->subattachments->data[1]->description : $picture_from_fb;
		$fb_pictureGalleryDescription1 = isset( $post_data->attachments->data[0]->subattachments->data[2]->description ) ? $post_data->attachments->data[0]->subattachments->data[2]->description : $picture_from_fb;
		$fb_pictureGalleryDescription2 = isset( $post_data->attachments->data[0]->subattachments->data[3]->description ) ? $post_data->attachments->data[0]->subattachments->data[3]->description : $picture_from_fb;

		$fb_picture_gallery_link1 = isset( $post_data->attachments->data[0]->subattachments->data[1]->target->url ) ? $post_data->attachments->data[0]->subattachments->data[1]->target->url : '';
		$fb_picture_gallery_link2 = isset( $post_data->attachments->data[0]->subattachments->data[2]->target->url ) ? $post_data->attachments->data[0]->subattachments->data[2]->target->url : '';
		$fb_picture_gallery_link3 = isset( $post_data->attachments->data[0]->subattachments->data[3]->target->url ) ? $post_data->attachments->data[0]->subattachments->data[3]->target->url : '';

		if ( isset( $fb_shortcode['scrollhorz_or_carousel'], $fb_shortcode['slider_spacing'] ) && ! empty( $fb_shortcode['slider_spacing'] ) && 'carousel' === $fb_shortcode['scrollhorz_or_carousel'] ) {

			$fb_shortcode['space_between_photos'] = '0 ' . $fb_shortcode['slider_spacing'];

		}

		if ( empty( $fts_custom_date ) && empty( $fts_custom_time ) && 'fts-custom-date' !== $custom_date_check ) {
			$custom_date_format = $custom_date_check;
		} elseif ( ! empty( $fts_custom_date ) && 'fts-custom-date' === $custom_date_check || ! empty( $fts_custom_time ) && 'fts-custom-date' === $custom_date_check ) {
			$custom_date_format = $fts_custom_date . ' ' . $fts_custom_time;
		} else {
			$custom_date_format = 'F jS, Y \a\t g:ia';
		}

		$album_created_time = isset( $post_data->photos->data[0]->created_time ) ? $post_data->photos->data[0]->created_time : '';
		$other_created_time = isset( $post_data->created_time ) ? $post_data->created_time : '';
		$created_time       = '' !== $album_created_time ? $album_created_time : $other_created_time;
		$custom_time_format = strtotime( $created_time );

		if ( ! empty( $fb_story ) ) {
			$fb_final_story = preg_replace( '/\b' . $fb_from_name . 's*?\b(?=([^"]*"[^"]*")*[^"]*$)/i', '', $fb_story, 1 );
		}

		$fts_hide_photos_type = get_option( 'fb_hide_images_in_posts' ) ? get_option( 'fb_hide_images_in_posts' ) : 'no';

		switch ( $fb_type ) {
			case 'video':
				echo '<div class="fts-jal-single-fb-post fts-fb-video-post-wrap" ';
				if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {
					echo 'style="width:' . esc_attr( $fb_shortcode['colmn_width'] ) . '!important; margin:' . esc_attr( $fb_shortcode['space_between_posts'] ) . '!important"';
				}
				echo '>';

				break;
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'photo':
				echo "<div class='fts-fb-photo-post-wrap fts-jal-single-fb-post' ";
				if ( 'album_photos' === $fb_shortcode['type'] || 'albums' === $fb_shortcode['type'] ) {

					if ( isset( $fb_shortcode['scrollhorz_or_carousel'] ) && 'scrollhorz' === $fb_shortcode['scrollhorz_or_carousel'] ) {
						echo 'style="max-width:' . esc_attr( $fb_shortcode['image_width'] ) . ';height:100%;  margin:' . esc_attr( $fb_shortcode['space_between_photos'] ) . '!important"';
					} else {
						echo 'style="width:' . esc_attr( $fb_shortcode['image_width'] ) . ' !important; height:' . esc_attr( $fb_shortcode['image_height'] ) . '!important; margin:' . esc_attr( $fb_shortcode['space_between_photos'] ) . '!important"';
					}
				}
				if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {
					echo 'style="width:' . esc_attr( $fb_shortcode['colmn_width'] ) . '!important; margin:' . esc_attr( $fb_shortcode['space_between_posts'] ) . '!important"';
				}
				echo '>';

				break;
			case 'album':
			default:
				echo '<div class="fts-jal-single-fb-post"';
				if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {
					echo 'style="width:' . esc_attr( $fb_shortcode['colmn_width'] ) . '!important; margin:' . esc_attr( $fb_shortcode['space_between_posts'] ) . '!important"';
				}
				echo '>';
				break;
		}
		// output Single Post Wrap.
		// Don't echo if Events Feed.
		if ( 'events' !== $fb_shortcode['type'] ) {

			// Reviews.
			$itemscope_reviews = 'reviews' === $fb_shortcode['type'] && isset( $post_data->rating ) ? 'itemscope itemtype="http://schema.org/Review"' : '';

			// Right Wrap.
			// $review_rating CANNOT be esc at this time.
			echo '<div ' . esc_attr( $itemscope_reviews ) . ' class="fts-jal-fb-right-wrap">';
			if ( 'reviews' === $fb_shortcode['type'] && isset( $post_data->rating ) ) {
				echo '<meta itemprop="itemReviewed" itemscope itemtype="http://schema.org/CreativeWork"><div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" style="display: none;"><meta itemprop="worstRating" content = "1"><meta itemprop="ratingValue" content = "' . esc_attr( $post_data->rating ) . '"><meta  itemprop="bestRating" content = "5"></div>';
			}
			// Top Wrap (Excluding : albums, album_photos, and hiding).
			$hide_date_likes_comments = 'album_photos' === $fb_shortcode['type'] && 'yes' === $fb_shortcode['hide_date_likes_comments'] || 'albums' === $fb_shortcode['type'] && 'yes' === $fb_shortcode['hide_date_likes_comments'] ? 'hide-date-likes-comments-etc' : '';

			$show_media = isset( $fb_shortcode['show_media'] ) ? $fb_shortcode['show_media'] : 'bottom';

			if ( 'top' !== $show_media ) {
				echo '<div class="fts-jal-fb-top-wrap ' . esc_attr( $hide_date_likes_comments ) . '">';
			}
			// if ($fb_shortcode['type'] == 'album_photos' || $fb_shortcode['type'] == 'albums') {.
			// } else {.
			// User Thumbnail.
			$fb_hide_shared_by_etc_text = get_option( 'fb_hide_shared_by_etc_text' );
			$fb_hide_shared_by_etc_text = isset( $fb_hide_shared_by_etc_text ) && 'no' === $fb_hide_shared_by_etc_text ? '' : $fb_final_story;

			if ( 'top' !== $show_media ) {

				if ( 'albums' !== $fb_shortcode['type'] ) {
					echo '<div class="fts-jal-fb-user-thumb">';

					$avatar_id                  = plugin_dir_url( dirname( __FILE__ ) ) . 'images/slick-comment-pic.png';
					$profile_photo_exists_check = isset( $post_data->fts_profile_pic_url ) && strpos( $post_data->fts_profile_pic_url, 'profilepic' ) !== false ? $post_data->fts_profile_pic_url : $avatar_id;

					echo ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? '' : '<a href="https://www.facebook.com/' . esc_attr( $from_id_picture ) . '" target="_blank" rel="noreferrer">' ) . '<img border="0" alt="' . ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? esc_attr( $post_data->reviewer->name ) : esc_attr( $post_data->from->name ) ) . '" src="' . ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? esc_url( $profile_photo_exists_check ) . '"/>' : 'https://graph.facebook.com/' . esc_attr( $from_id_picture ) ) . ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? '' : '/picture"/></a>' );

					echo '</div>';

				}

				// UserName.
				// $fts_facebook_reviews->reviews_rating_format CANNOT be esc at this time.
				$hide_name = 'albums' === $fb_shortcode['type'] ? ' fts-fb-album-hide' : '';

				echo ( 'reviews' === $fb_shortcode['type'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? '<span class="fts-jal-fb-user-name fts-review-name" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">' . esc_attr( $post_data->reviewer->name ) . '</span>' . $fts_facebook_reviews->reviews_rating_format( $fb_shortcode, isset( $post_data->rating ) ? esc_html( $post_data->rating ) : '' ) . '</span>' : '<span class="fts-jal-fb-user-name' . $hide_name . '"><a href="https://www.facebook.com/' . esc_attr( $from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $post_data->from->name ) . '</a>' . esc_html( $fb_hide_shared_by_etc_text ) . '</span>' );

				// tied to date function.
				$feed_type      = 'facebook';
				$times          = $custom_time_format;
				$fts_final_date = $this->fts_custom_date( $times, $feed_type );
				// PostTime.
				// $fts_final_date CANNOT be esc at this time.
				if ( 'albums' !== $fb_shortcode['type'] ) {
					echo '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';
				}
			}

			if ( 'reviews' !== $fb_shortcode['type'] ) {
				// Comments Count.
				$fb_post_id_final = substr( $fb_post_id, strpos( $fb_post_id, '_' ) + 1 );
			}

				$fb_title_job_opening = isset( $post_data->attachments->data[0]->title ) && 'job_search_job_opening' === $post_data->attachments->data[0]->type ? $post_data->attachments->data[0]->title : '';

			// filter messages to have urls.
			// Output Message.
			$fb_message = ( isset( $post_data->message ) ? $post_data->message : ( isset( $post_data->review_text ) ? $post_data->review_text : '' ) . '' );
			if ( empty( $fb_message ) ) {

				if ( isset( $post_data->description ) ) {
					$fb_message = isset( $post_data->description ) ? $post_data->description : '';
				} elseif ( isset( $post_data->attachments->data[0]->description ) ) {
					$fb_message = isset( $post_data->attachments->data[0]->description ) ? $post_data->attachments->data[0]->description : '';
				}
			}

			if ( $fb_message && 'top' !== $show_media ) {

				if ( ! empty( $fb_places_id ) ) {
					$this->feed_location_option( $fb_places_id, $fb_name, $fb_places_name );
				}

				$itemprop_description_reviews = is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? ' itemprop="description"' : '';

				// here we trim the words for the premium version. The $fb_shortcode['words'] string actually comes from the javascript.
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && array_key_exists( 'words', $fb_shortcode ) && 'top' !== $show_media || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && array_key_exists( 'words', $fb_shortcode ) && ! empty( $fb_shortcode['words'] ) && 'top' !== $show_media || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && array_key_exists( 'words', $fb_shortcode ) && 'top' !== $show_media ) {
					$more = isset( $more ) ? $more : '';

					$trimmed_content = $this->fts_custom_trim_words( $fb_message, $fb_shortcode['words'], $more );

					// Going to consider this for the future if facebook fixes the api to define when are checking in. Add  '.$checked_in.' inside the fts-jal-fb-message div.
					// $checked_in = '<a target="_blank" class="fts-checked-in-img" href="https://www.facebook.com/'.$post_data->place->id.'"><img src="https://graph.facebook.com/'.$post_data->place->id.'/picture?width=150"/></a><a target="_blank" class="fts-checked-in-text-link" href="https://www.facebook.com/'.$post_data->place->id.'">'.esc_html("Checked in at", "feed-them-social").' '.$post_data->place->name.'</a><br/> '.esc_html("Location", "feed-them-social").': '.$post_data->place->location->city.', '.$post_data->place->location->country.' '.$post_data->place->location->zip.'<br/><a target="_blank" class="fts-fb-get-directions fts-checked-in-get-directions" href="https://www.facebook.com/'.$post_data->place->id.'">'.esc_html("Get Direction", "feed-them-social").'</a>';.
					echo '<div class="fts-jal-fb-message"' . esc_attr( $itemprop_description_reviews ) . '>';

					echo esc_html( $fb_title_job_opening );
					// $trimmed_content CANNOT be esc at this time.
					echo ! empty( $trimmed_content ) ? $trimmed_content : '';
					// The Popup.
					// echo $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html__('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
					echo '<div class="fts-clear"></div></div> ';

				} elseif ( 'top' !== $show_media ) {
					$fb_final_message = $this->fts_facebook_tag_filter( $fb_message );
					echo '<div class="fts-jal-fb-message"' . esc_attr( $itemprop_description_reviews ) . '>';
					// $fb_final_message CANNOT be esc at this time.
					echo nl2br( $fb_final_message );
					// If POPUP.
					// echo isset($fb_shortcode['popup']) && $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
					echo '<div class="fts-clear"></div></div>';
				}
			} elseif ( ! $fb_message && 'album_photos' === $fb_shortcode['type'] || ! $fb_message && 'albums' === $fb_shortcode['type'] ) {

				echo '<div class="fts-jal-fb-description-wrap">';

				$fb_caption ? $this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type ) : '';
				// Output Photo Caption.
				// if ( !is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'albums' === $fb_shortcode['type']  ){
					// Album Post Description.
				if ( 'albums' === $fb_shortcode['type'] ) {
					echo '<div class="fts-fb-album-name-and-count ">';
				}
					$fb_name ? $this->fts_facebook_post_desc( $fb_name, $fb_shortcode, $fb_type, null, $fb_by ) : '';
				// echo $fb_type;
				// echo 'asdfasdf';
					// Albums Photo Count.
					echo $fb_album_photo_count ? esc_html( $fb_album_photo_count ) . ' Photos' : '';
				if ( 'albums' === $fb_shortcode['type'] ) {
					echo '</div>';
				}
				// }
				// Location.
				// $fb_location ? $this->fts_facebook_location( $fb_type, $fb_location ) : '';
				// Output Photo Description.
				$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type, null, $fb_by ) : '';

				// Output Photo Description.
				if ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ) {
					echo '<div class="fts-fb-caption fts-fb-album-view-link">';
					// Album Covers.
					if ( 'albums' === $fb_shortcode['type'] ) {

							echo '<div class="fts-fb-album-additional-pics">';
							// Album Covers. <img src="' . esc_url( $fb_album_additional_pic->images[1]->source ) . '"/>
							$isFirst = true;
						foreach ( $post_data->photos->data as $key => $fb_album_additional_pic ) {
							// $fb_album_additional_pic_check = isset( $fb_album_additional_pic->name ) ? $this->fts_facebook_post_desc( $fb_album_additional_pic->name, $fb_shortcode, $fb_type, null, $fb_by  ): '';
							// $fb_album_additional_pic ? $fb_album_additional_pic_check : '';
							echo '<div class="fts-fb-album-additional-pics-content">';

								$hide_all_but_one_link = ! $isFirst ? 'style="display:none"' : '';

								echo '<a href="' . esc_url( $fb_album_additional_pic->images[0]->source ) . '" class="fts-view-album-photos-large data-fb-album-photo-description" target="_blank" rel="noreferrer"  ' . $hide_all_but_one_link . '>' . esc_html__( 'View Album', 'feed-them-social' ) . '</a>';
								echo '<div class="fts-fb-album-additional-pics-description-wrap">';
									echo '<div class="fts-jal-fb-description-wrap fts-fb-album-description-content fts-jal-fb-description-popup">';

							// tied to date function.
							$feed_type          = 'facebook';
							$album_created_time = isset( $fb_album_additional_pic->created_time ) ? $fb_album_additional_pic->created_time : '';
							$times              = $album_created_time;
							$fts_final_date     = $this->fts_custom_date( $times, $feed_type );
							echo '<div class="fts-jal-fb-user-thumb">';
							echo '<a href="https://www.facebook.com/' . esc_attr( $from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $post_data->from->name ) . '" src="' . 'https://graph.facebook.com/' . esc_attr( $from_id_picture ) . '/picture"/></a>';
							echo '</div>';

							// UserName.
							// $fts_facebook_reviews->reviews_rating_format CANNOT be esc at this time.
							echo '<span class="fts-jal-fb-user-name"><a href="https://www.facebook.com/' . esc_attr( $from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $post_data->from->name ) . '</a>' . esc_html( $fb_hide_shared_by_etc_text ) . '</span>';

							echo '<div class="fts-fb-album-date-wrap">' . $fts_final_date . '</div>';

							echo '<div class="fts-clear"></div>';

							// Album Post Description.
							// $fb_name ? $this->fts_facebook_post_desc( $fb_name, $fb_shortcode, $fb_type, null, $fb_by ) : '';
							// Albums Photo Count.
							$fb_name ? $this->fts_facebook_post_desc( $fb_name, $fb_shortcode, $fb_type, null, $fb_by ) : '';
							$view_additional_album_photos = '24' == $key ? '. <a href="' . $fb_link . '" target="_blank" rel="noreferrer">' . esc_html__( 'View more for this Album', 'feed-them-social' ) . '</a>' : '';
							echo $fb_album_photo_count ? ' ' . esc_html( $key + 1 ) . ' ' . esc_html__( 'of', 'feed-them-social' ) . ' ' . esc_html( $fb_album_photo_count ) . ' ' . esc_html__( 'Photos', 'feed-them-social' ) . ' ' . $view_additional_album_photos : '';
							echo '<br/><br/>';

										$fb_album_additional_pic_name = isset( $fb_album_additional_pic->name ) ? $fb_album_additional_pic->name : '';
										$fb_album_additional_pic_name ? $this->fts_facebook_post_desc( $fb_album_additional_pic_name, $fb_shortcode, $fb_type, null, $fb_by ) : '';
									echo '</div>';
								echo '</div>';
							echo '</div>';
							$isFirst = false;
						}

						echo '</div>';
						echo '</div>';
					} elseif (

						// Album Photos.
						'album_photos' === $fb_shortcode['type'] && ( isset( $fb_shortcode['video_album'] ) && 'yes' !== $fb_shortcode['video_album'] || ! isset( $fb_shortcode['video_album'] ) ) ) {
						echo '<a href="' . esc_url( $fb_album_picture ) . '" class="fts-view-album-photos-large" target="_blank" rel="noreferrer">' . esc_html__( 'View Photo', 'feed-them-social' ) . '</a></div>';

					} elseif (
						// Video Albums.
						isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
						if ( 'yes' !== $fb_shortcode['play_btn'] ) {

							echo '<a href="' . esc_url( $embed_html ) . '"  data-poster="' . esc_url( $video_photo ) . '" id="fts-view-vid1-' . esc_attr( $fts_dynamic_vid_name_string ) . '" class="fts-jal-fb-vid-html5video ' . esc_attr( $fts_view_fb_videos_btn ) . ' fts-view-fb-videos-large fts-view-fb-videos-btn fb-video-popup-' . esc_attr( $fts_dynamic_vid_name_string ) . '">' . esc_html__( 'View Video', 'feed-them-social' ) . '</a>';

							echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
							if ( $embed_height >= $embed_width ) {
								echo '<div class=' . esc_url( $data_height ) . ' data-width="' . esc_attr( $embed_width ) . '" data-height="' . esc_attr( $embed_height ) . '"></div>';
							}
							echo '</div>';
						}
						echo '</div>';
					} else {
						// photos.
						echo '<a href="' . esc_url( $post_data->source ) . '" class="fts-view-album-photos-large" target="_blank" rel="noreferrer">' . esc_html__( 'View Photo', 'feed-them-social' ) . '</a></div>';
					}

					// echo '<div class="fts-fb-caption"><a class="view-on-facebook-albums-link" href="' . $fb_link . '" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div>';.
				}

				echo '<div class="fts-clear"></div></div>';
			} //END Output Message
			// elseif ($fb_message == '' && $fb_shortcode['type'] !== 'album_photos' || $fb_message == '' && $fb_shortcode['type'] !== 'albums') {.
			// If POPUP.
			// echo $fb_shortcode['popup'] == 'yes' ? '<div class="fts-jal-fb-message"><div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div></div>' : '';.
			// }.
			if ( 'top' !== $show_media ) {
				echo '</div>';
				// end .fts-jal-fb-top-wrap <!--end fts-jal-fb-top-wrap -->.
			}
		}
		// Post Type Build.
		$fts_show_post = false;
		switch ( $fb_type ) {

			// START NOTE POST.
			case 'note':
				// && !$fb_picture == '' makes it so the attachment unavailable message does not show up.
				// if (!$fb_picture && !$fb_name && !$fb_description && !$fb_picture == '') {.
				echo '<div class="fts-jal-fb-link-wrap">';
				// Output Link Picture.
				$fb_picture ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->full_picture ) : '';

				if ( $fb_name || $fb_caption || $fb_description ) {
					echo '<div class="fts-jal-fb-description-wrap">';
					// Output Link Name.
					$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type ) : '';
					// Output Link Caption.
					if ( 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' === $fb_caption ) {
						echo '<div class="fts-jal-fb-caption" style="width:100% !important">';
						esc_html( 'This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social' );
						echo '</div>';
					} else {
						$this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type );
					}
					// If POPUP.
					// echo $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
					// Output Link Description.
					// echo $fb_description ? $this->fts_facebook_post_desc($fb_description, $fb_shortcode, $fb_type) : '';.
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				// }.
				break;

			// START STATUS POST.
			case 'status':
				// && !$fb_picture == '' makes it so the attachment unavailable message does not show up.
				// if (!$fb_picture && !$fb_name && !$fb_description && !$fb_picture == '') {.
					echo '<div class="fts-jal-fb-link-wrap">';
					// Output Link Picture.
				if ( $fb_picture_job ) {
					$this->fts_facebook_post_photo( $fb_joblink, $fb_shortcode, $post_data->from->name, $fb_picture_job );
				} else {
					$fb_picture ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->picture ) : '';
				}

				if ( $fb_name || $fb_caption || $fb_description ) {
					echo '<div class="fts-jal-fb-description-wrap">';
					// Output Link Name.
					$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type ) : '';
					// Output Link Caption.
					if ( 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' === $fb_caption ) {
						echo '<div class="fts-jal-fb-caption" style="width:100% !important">';
						echo esc_html( 'This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social' );
						echo '</div>';
					} else {
						$this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type );
					}
					// Output Link Description.
					$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type ) : '';
					echo '<div class="fts-clear"></div></div>';
				}
					echo '<div class="fts-clear"></div></div>';
				// }
				break;
			// Start Multiple Events.
			case 'events':
				$single_event_id          = $post_data->id;
				$single_event_info        = json_decode( $single_event_array_response[ 'event_single_' . $single_event_id . '_info' ] );
				$single_event_location    = json_decode( $single_event_array_response[ 'event_single_' . $single_event_id . '_location' ] );
				$single_event_cover_photo = json_decode( $single_event_array_response[ 'event_single_' . $single_event_id . '_cover_photo' ] );
				$single_event_ticket_info = json_decode( $single_event_array_response[ 'event_single_' . $single_event_id . '_ticket_info' ] );
				// echo'<pre>';.
				// print_r($single_event_info);.
				// echo'</pre>';.
				// Event Cover Photo.
				$event_cover_photo = isset( $single_event_cover_photo->cover->source ) ? $single_event_cover_photo->cover->source : '';
				$event_description = isset( $single_event_info->description ) ? $single_event_info->description : '';
				echo '<div class="fts-jal-fb-right-wrap fts-events-list-wrap">';
				// Link Picture.
				$fb_event_name        = isset( $single_event_info->name ) ? $single_event_info->name : '';
				$fb_event_location    = isset( $single_event_location->place->name ) ? $single_event_location->place->name : '';
				$fb_event_city        = isset( $single_event_location->place->location->city ) ? $single_event_location->place->location->city . ', ' : '';
				$fb_event_state       = isset( $single_event_location->place->location->state ) ? $single_event_location->place->location->state : '';
				$fb_event_street      = isset( $single_event_location->place->location->street ) ? $single_event_location->place->location->street : '';
				$fb_event_zip         = isset( $single_event_location->place->location->zip ) ? ' ' . $single_event_location->place->location->zip : '';
				$fb_event_latitude    = isset( $single_event_location->place->location->latitude ) ? $single_event_location->place->location->latitude : '';
				$fb_event_longitude   = isset( $single_event_location->place->location->longitude ) ? $single_event_location->place->location->longitude : '';
				$fb_event_ticket_info = isset( $single_event_ticket_info->ticket_uri ) ? $single_event_ticket_info->ticket_uri : '';
				date_default_timezone_set( get_option( 'fts-timezone' ) );

				// custom one day ago check.
				if ( 'one-day-ago' === $custom_date_check ) {
					$fb_event_start_time = date_i18n( 'l, F jS, Y \a\t g:ia', strtotime( $single_event_info->start_time ) );
				} else {
					$fb_event_start_time = date_i18n( $custom_date_format, strtotime( $single_event_info->start_time ) );
				}

				// Output Photo Description.
				if ( ! empty( $event_cover_photo ) ) {
					echo isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ? '<a href="' . esc_url( $event_cover_photo ) . '" class="fts-jal-fb-picture fts-fb-large-photo" target="_blank" rel="noreferrer"><img class="fts-fb-event-photo" src="' . esc_url( $event_cover_photo ) . '"></a>' : '<a href="https://www.facebook.com/events/' . esc_attr( $single_event_id ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img class="fts-fb-event-photo" src="' . esc_url( $event_cover_photo ) . '" /></a>';
				}
				echo '<div class="fts-jal-fb-top-wrap">';
				echo '<div class="fts-jal-fb-message">';
				// Link Name.
				echo '<div class="fts-event-title-wrap">';
				$fb_event_name ? $this->fts_facebook_post_name( 'https://www.facebook.com/events/' . esc_attr( $single_event_id ) . '', esc_attr( $fb_event_name ), esc_attr( $fb_type ) ) : '';
				echo '</div>';
				// Link Caption.
				if ( $fb_event_start_time ) {
					echo '<div class="fts-fb-event-time">' . $fb_event_start_time . '</div>';
				}
				// Link Description.
				if ( ! empty( $fb_event_location ) ) {
					echo '<div class="fts-fb-location"><span class="fts-fb-location-title">' . esc_html( $fb_event_location ) . '</span>';
					// Street Adress.
					echo esc_html( $fb_event_street );
					// City & State.
					echo $fb_event_city || $fb_event_state ? '<br/>' . esc_html( $fb_event_city . $fb_event_state . $fb_event_zip ) : '';
					echo '</div>';
				}
				// Get Directions.
				if ( ! empty( $fb_event_latitude ) && ! empty( $fb_event_longitude ) ) {
					echo '<a target="_blank" class="fts-fb-get-directions" href="' . esc_html( 'https://www.google.com/maps/dir/Current+Location/' . $fb_event_latitude . ',' . $fb_event_longitude . '' ) . '"  
>' . esc_html( 'Get Directions', 'feed-them-social' ) . '</a>';
				}
				if ( ! empty( $fb_event_ticket_info ) && ! empty( $fb_event_ticket_info ) ) {
					echo '<a target="_blank" rel="noreferrer" class="fts-fb-ticket-info" href="' . esc_url( $single_event_ticket_info->ticket_uri ) . '">' . esc_html( 'Ticket Info', 'feed-them-social' ) . '</a>';
				}
				// Output Message.
				if ( ! empty( $fb_shortcode['words'] ) && $event_description && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
					// here we trim the words for the premium version. The $fb_shortcode['words'] string actually comes from the javascript.
					$this->fts_facebook_post_desc( $event_description, $fb_shortcode, $fb_type, null, $fb_by, $fb_shortcode['type'] );
				} else {
					// if the premium plugin is not active we will just show the regular full description.
					$this->fts_facebook_post_desc( $event_description, $fb_type, null, $fb_by, $fb_shortcode['type'] );
				}
				// Our POPUP.
				// echo $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="https://www.facebook.com/events/' . $single_event_id . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View Event on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
				echo '<div class="fts-clear"></div></div></div>';
				break;

			// START LINK POST.
			case 'link':
				echo '<div class="fts-jal-fb-link-wrap">';
				// start url check.
				if ( ! empty( $fb_link ) ) {
					$url       = $fb_link;
					$url_parts = parse_url( $url );
					$host      = $url_parts['host'];
				}

				if ( isset( $host ) && 'www.facebook.com' === $host ) {
					$spliturl        = $url_parts['path'];
					$path_components = explode( '/', $spliturl );
					$first_dir       = $path_components[1];
				}
				// end url check.
				// Output Link Picture.
				// echo isset($fb_shortcode['popup']) && $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
				if ( isset( $host ) && 'www.facebook.com' === $host && 'events' === $first_dir ) {
					$fb_picture ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->picture ) : '';
				} elseif ( strpos( $fb_link, 'soundcloud' ) > 0 ) {
					// Get the SoundCloud URL.
					$url = $fb_link;
					// Get the JSON data of song details with embed code from SoundCloud oEmbed.
					$get_values = file_get_contents( 'http://soundcloud.com/oembed?format=js&url=' . $url . '&auto_play=true&iframe=true' );
					// Clean the Json to decode.
					$decode_iframe = substr( $get_values, 1, -2 );
					// json decode to convert it as an array.
					$json_obj = json_decode( $decode_iframe );
					// Change the height of the embed player if you want else uncomment below line.
					// echo str_replace('height="400"', 'height="140"', $json_obj->html);.
					$fts_dynamic_vid_name_string = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $fb_shortcode['type'] );
					$fts_dynamic_vid_name        = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
					echo '<div class="fts-jal-fb-vid-picture ' . esc_attr( $fts_dynamic_vid_name ) . '">';
					if ( ! empty( $post_data->full_picture ) ) {
						$fb_picture ? $this->fts_facebook_post_photo( 'javascript:;', $fb_shortcode, $post_data->from->name, $post_data->full_picture ) : '';
					} elseif ( ! empty( $post_data->picture ) ) {
						$fb_picture ? $this->fts_facebook_post_photo( 'javascript:;', $fb_shortcode, $post_data->from->name, $post_data->picture ) : '';
					}
					echo '<div class="fts-jal-fb-vid-play-btn"></div>';
					echo '</div>';
					echo '<script>';
					echo 'jQuery(document).ready(function() {';
					echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
					echo 'jQuery(this).addClass("fts-vid-div");';
					echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
					echo '	jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper">' . $json_obj->html . '</div>\');';
					if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {
						echo 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
						echo 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
					}
					echo '});';
					echo '});';
					echo '</script>';
				} elseif ( ! empty( $post_data->full_picture ) ) {
					$fb_picture ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->full_picture ) : '';
				} elseif ( ! empty( $post_data->picture ) ) {
					$fb_picture ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->picture ) : '';
				}

				$fb_shortcode['words'] = isset( $fb_shortcode['words'] ) ? $fb_shortcode['words'] : '';
				// Description Wrap.
				echo '<div class="fts-jal-fb-description-wrap">';
				// Output Link Name.
				$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type ) : '';
				if ( isset( $host ) && 'www.facebook.com' === $host && 'events' === $first_dir ) {
					echo ' &#9658; ';
					echo '<a href="' . esc_url( $fb_link ) . '" class="fts-jal-fb-name" target="_blank" rel="noreferrer">' . esc_html( $fb_link_event_name ) . '</a>';
				}//end if event.
				// Output Link Description.
				$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type ) : '';

				// Output Link Caption.
				$fb_caption ? $this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type ) : '';
				echo '<div class="fts-clear"></div></div>';
				echo '<div class="fts-clear"></div></div>';
				break;

			// START VIDEO POST.
			case 'video':
				// $video_data = json_decode($response_post_array[$post_data_key . '_video']);.
				// echo '<pre>';.
				// print_r($video_data);.
				// echo '</pre>';.
				echo '<div class="fts-jal-fb-vid-wrap">';

				if ( ! empty( $fb_picture ) ) {

					// Create Dynamic Class Name.
					$fts_dynamic_vid_name_string = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $fb_shortcode['type'] );
					$fts_dynamic_vid_name        = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
					echo '<div class="fts-jal-fb-vid-picture ' . esc_html( $fts_dynamic_vid_name ) . '">';

					if ( strpos( $fb_video_embed, 'youtube' ) > 0 || strpos( $fb_video_embed, 'youtu.be' ) > 0 ) {
						preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $fb_video_embed, $matches );
						$video_url_final = 'https://www.youtube.com/watch?v=' . $matches[1];
					} else {
						$video_url_final = esc_url( $embed_html );
					}

					// This puts the video in a popup instead of displaying it directly on the page.
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ) {

						if ( strpos( $fb_link, 'youtube' ) > 0 || strpos( $fb_link, 'youtu.be' ) > 0 || strpos( $fb_link, 'vimeo' ) > 0 ) {
							echo '<a href="' . esc_url( $video_url_final ) . '" class="fts-facebook-link-target fts-jal-fb-vid-image fts-iframe-type">';
						} else {

							if ( 'video' === $post_data->type ) {
								$page_id      = $post_data->from->id;
								$video_id     = $post_data->object_id;
								$fb_embed_url = 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2F' . $page_id . '%2Fvideos%2F' . $video_id . '%2F&autoplay=true';
								echo '<a href="' . esc_url( $fb_embed_url ) . '" class="fts-jal-fb-vid-image ' . esc_attr( $fts_view_fb_videos_btn ) . ' fts-jal-fb-vid-html5video ">';

							} else {
								echo '<a href="' . esc_url( $embed_html ) . '" class="fts-facebook-link-target fts-jal-fb-vid-html5video ">';
							}
						}
					}
					// srl: 8/27/17 - FB BUG: for some reason the full_picture for animated gifs is not correct so we dig deeper and grab another image size fb has set.
					if ( isset( $post_data->attachments->data[0]->type ) && 'animated_image_video' === $post_data->attachments->data[0]->type ) {
						$vid_pic = $post_data->attachments->data[0]->media->image->src;
					} else {
						$vid_pic = $post_data->full_picture;
					}
					echo '<img class="fts-jal-fb-vid-image" border="0" alt="' . esc_attr( $post_data->from->name ) . '" src="' . esc_url( $vid_pic ) . '"/>';

					// This puts the video in a popup instead of displaying it directly on the page.
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ) {
						echo '</a>';
					}

					echo '<div class="fts-jal-fb-vid-play-btn"></div></div>';

					// If this is a facebook embed video then ouput Iframe and script.
					$embed_height = isset( $post_data->attachments->data[0]->media->image->height ) ? $post_data->attachments->data[0]->media->image->height : '';
					$embed_width  = isset( $post_data->attachments->data[0]->media->image->width ) ? $post_data->attachments->data[0]->media->image->width : '';
					$video_type   = isset( $post_data->type ) ? $post_data->type : '';
					$video_inline = isset( $post_data->attachments->data[0]->type ) ? $post_data->attachments->data[0]->type : '';
					// && $video_inline == 'video_inline'.
					if ( 'video' === $video_type && 'video_inline' === $video_inline || 'video' === $video_type && 'animated_image_video' === $video_inline ) {

						if ( $embed_height > $embed_width ) {
							$data_height = 'fts-greater-than-width-height';
						} elseif ( $embed_height === $embed_width ) {
							$data_height = 'fts-equal-width-height fts-fluid-videoWrapper ';
						} else {
							$data_height = 'fts-fluid-videoWrapper';
						}

						echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
						if ( $embed_height >= $embed_width ) {
							echo '<div class=' . esc_attr( $data_height ) . ' data-width="' . esc_attr( $embed_width ) . '" data-height="' . esc_attr( $embed_height ) . '"></div>';
						}
						echo '</div>';
						// This puts the video on the page instead of the popup if you don't have the premium version.
						if ( ! isset( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'yes' !== $fb_shortcode['popup'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $fb_shortcode['popup'] ) && empty( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'no' === $fb_shortcode['popup'] ) {

							$page_id  = isset( $post_data->from->id ) ? $post_data->from->id : '';
							$video_id = isset( $post_data->object_id ) ? $post_data->object_id : '';

							$fb_embed_url = 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2F' . $page_id . '%2Fvideos%2F' . $video_id . '%2F&autoplay=true';

							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$fb_post_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="' . esc_js( $data_height ) . ' fts-fb-video-on-page" ><iframe style="background:none !important" class="video-' . esc_js( $fb_post_id ) . '" src="' . esc_url( $fb_embed_url ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							echo 'jQuery( ".' . esc_js( $fts_dynamic_vid_name ) . ' .fts-greater-than-width-height.fts-fb-video-on-page, .' . esc_js( $fts_dynamic_vid_name ) . ' iframe" ).css({"height": "' . esc_js( $embed_height ) . 'px", "width": "' . esc_js( $embed_width ) . 'px"});';
							if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] || isset( $fb_shortcode['grid_combined'] ) && 'yes' === $fb_shortcode['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					}
					// strip Youtube URL then ouput Iframe and script.
					if ( strpos( $fb_link, 'youtube' ) > 0 ) {
						// $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';.
						// preg_match($pattern, $fb_link, $matches);.
						// $youtubeURLfinal = $matches[1];.
						// This puts the video on the page instead of the popup if you don't have the premium version.
						if ( ! isset( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'yes' !== $fb_shortcode['popup'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $fb_shortcode['popup'] ) && empty( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'no' === $fb_shortcode['popup'] ) {
							echo '<script>jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$fb_post_id.'" src="https://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . esc_js( $fb_post_id ) . '" src="' . esc_url( $fb_video_embed ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] || isset( $fb_shortcode['grid_combined'] ) && 'yes' === $fb_shortcode['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});</script>';
						}
					} elseif (
						// strip Youtube URL then ouput Iframe and script.
						strpos( $fb_link, 'youtu.be' ) > 0 ) {
						// $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';.
						// preg_match($pattern, $fb_link, $matches);.
						// $youtubeURLfinal = $matches[1];.
						// This puts the video in a popup instead of displaying it directly on the page.
						if ( ! isset( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'yes' !== $fb_shortcode['popup'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $fb_shortcode['popup'] ) && empty( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'no' === $fb_shortcode['popup'] ) {
							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$fb_post_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . esc_js( $fb_post_id ) . '" src="' . esc_url( $fb_video_embed ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] || isset( $fb_shortcode['grid_combined'] ) && 'yes' === $fb_shortcode['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					} elseif (
						// strip Vimeo URL then ouput Iframe and script.
						strpos( $fb_link, 'vimeo' ) > 0 ) {
						// $pattern = '/(\d+)/';.
						// preg_match($pattern, $fb_link, $matches);.
						// $vimeoURLfinal = $matches[0];.
						// This puts the video in a popup instead of displaying it directly on the page.
						if ( ! isset( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'yes' !== $fb_shortcode['popup'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $fb_shortcode['popup'] ) && empty( $fb_shortcode['popup'] ) || isset( $fb_shortcode['popup'] ) && 'no' === $fb_shortcode['popup'] ) {
							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$fb_post_id.'" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="' . esc_url( $fb_video_embed ) . '" class="video' . esc_js( $fb_post_id ) . '" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';
							if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] || isset( $fb_shortcode['grid_combined'] ) && 'yes' === $fb_shortcode['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					}
				}
				if ( $fb_name || $fb_caption || $fb_description ) {
					echo '<div class="fts-jal-fb-description-wrap fb-id' . esc_attr( $fb_post_id ) . '">';
					// Output Video Name.
					$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type, $fb_post_id ) : '';
					// Output Video Description.
					$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type, $fb_post_id ) : '';
					// Output Video Caption.
					$fb_caption ? $this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type, $fb_post_id ) : '';
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				break;
			// START PHOTO POST.
			case 'photo':
				if ( isset( $fts_hide_photos_type ) && 'yes' === $fts_hide_photos_type && 'album_photos' !== $fb_shortcode['type'] && 'yes' !== $fb_shortcode['video_album'] ) {
					break;
				}

				// Wrapping with if statement to prevent Notice on some facebook page feeds.
				if ( 'group' === $fb_shortcode['type'] ) {
					$photo_source = json_decode( $response_post_array[ $post_data_key . '_group_post_photo' ] );
				}
				// Group or page?
				$photo_source_final = isset( $post_data->full_picture ) ? $post_data->full_picture : 'https://graph.facebook.com/' . $fb_post_object_id . '/picture';

				echo '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ( 'album_photos' === $fb_shortcode['type'] || 'albums' === $fb_shortcode['type'] ) {
					echo ' style="line-height:' . esc_attr( $fb_shortcode['image_height'] ) . ' !important;"';
				}
				echo '>';
				// echo isset($fb_shortcode['popup']) && $fb_shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="' . $fb_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
				// Output Photo Picture.
				if ( $fb_post_object_id ) {
					if ( $fb_post_object_id ) {

						// if we have more than one attachment we get the first image width and set that for the max width.
						$fts_fb_image_count = isset( $post_data->attachments->data[0]->subattachments->data ) ? count( $post_data->attachments->data[0]->subattachments->data ) : '0';
						// TESTING: lets see how many images are being output per post.
						// echo $fts_fb_image_count;.
						// echo $fts_fb_image_count;.
						if ( '0' === $fts_fb_image_count || '1' === $fts_fb_image_count || $fts_fb_image_count > 2 ) {

							// echo $fts_fb_image_count;.
							echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $photo_source_final ) : esc_url( $fb_link ) ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_attr( $post_data->from->name ) . '" src="' . esc_url( $photo_source_final ) . '"></a>';

						}

						if ( '' !== $fb_picture_gallery1 ) {

							// we count the number of attachments in the subattachments->data portion of the array and count the objects http://php.net/manual/en/function.count.php.
							$fts_fb_image_counter = $fts_fb_image_count - 3;

							$fts_fb_image_count_check = $fts_fb_image_count < 3 ? ' fts-more-images-tint' : '';

							$fb_picture_gallery1_check = '' === $fb_picture_gallery2 ? '100%;' : $fb_picture_gallery0_width . 'px';
							// if we only have 2 photos we show them side by side.
							$fb_picture_gallery2_check = '' === $fb_picture_gallery2 ? ' fts-more-photos-auto-width' : '';
							// if we have 3 photos we add this class so we can make the 2 attachments below the large image will fit side by side.
							$fb_picture_gallery3_check = '' === $fb_picture_gallery3 && '' !== $fb_picture_gallery2 ? ' fts-more-photos-three-photo-wrap' : '';

							$columns_css = '';

							// print $fts_fb_image_count;.
							if ( 2 === $fts_fb_image_count ) {
								$columns     = '2';
								$columns_css = 'fts-more-photos-2-or-3-photos ';
								$morethan3   = 'fts-2-photos ';
							} elseif ( 3 === $fts_fb_image_count ) {
								$columns     = '2';
								$columns_css = 'fts-more-photos-2-or-3-photos ';
								$morethan3   = 'fts-3-photos ';
							} elseif ( $fts_fb_image_count >= 4 ) {
								$columns     = '3';
								$columns_css = 'fts-more-photos-4-photos ';
								$morethan3   = 'fts-4-photos ';
							}

							echo '<div class="fts-clear"></div><div class="' . esc_attr( $columns_css . 'fts-fb-more-photos-wrap fts-facebook-inline-block-centered' . $fb_picture_gallery2_check . $fb_picture_gallery3_check ) . '" style="max-width:' . esc_attr( $fb_picture_gallery1_check ) . '" data-ftsi-id=' . esc_attr( $fts_dynamic_vid_name_string ) . ' data-ftsi-columns="' . esc_attr( $columns ) . '" data-ftsi-margin="1px" data-ftsi-force-columns="yes">';
						}
						if ( 2 === $fts_fb_image_count ) {
							echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $photo_source_final ) : esc_url( $fb_link ) ) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . esc_url( $photo_source_final ) . ');" title="' . esc_attr( $fb_pictureGalleryDescription0 ) . '" aria-label="' . esc_attr( $fb_pictureGalleryDescription0 ) . '"></a>';

						}
						if ( '' !== $fb_picture_gallery1 ) {
							echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $fb_picture_gallery1 ) : esc_url( $fb_picture_gallery_link1 ) ) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . esc_url( $fb_picture_gallery1 ) . ');" title="' . esc_attr( $fb_pictureGalleryDescription1 ) . '" aria-label="' . esc_attr( $fb_pictureGalleryDescription1 ) . '"></a>';

							if ( '' !== $fb_picture_gallery2 ) {
								echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $fb_picture_gallery2 ) : esc_url( $fb_picture_gallery_link2 ) ) . '" target="_blank" rel="noreferrer" class="fts-2-or-3-photos slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-one-wrap fts-fb-large-photo" style="background:url(' . esc_url( $fb_picture_gallery2 ) . ');" title="' . esc_attr( $fb_pictureGalleryDescription1 ) . '" aria-label="' . esc_attr( $fb_pictureGalleryDescription1 ) . '"></a>';

							}
							if ( '' !== $fb_picture_gallery3 ) {
								echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $fb_picture_gallery3 ) : esc_url( $fb_picture_gallery_link3 ) ) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-two-wrap fts-fb-large-photo' . esc_attr( $fts_fb_image_count_check ) . '" style="background:url(' . esc_url( $fb_picture_gallery3 ) . ');" title="' . esc_attr( $fb_pictureGalleryDescription2 ) . '" aria-label="' . esc_attr( $fb_pictureGalleryDescription2 ) . '"><div class="fts-image-count-tint-underlay"></div><div class="fts-image-count"><span>+</span>' . esc_html( $fts_fb_image_counter ) . '</div></a>';
							}
						}
						if ( '' !== $fb_picture_gallery1 ) {
							echo '</div>';
						}
					} else {
						echo '<a href="' . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? esc_url( $photo_source_final ) : esc_url( $fb_link ) ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_attr( $post_data->from->name ) . '" src="' . esc_url( $photo_source_final ) . '" title="' . $fb_pictureGalleryDescription0 . '" aria-label="' . $fb_pictureGalleryDescription0 . '"></a>';
					}
				} elseif ( $fb_picture ) {
					if ( $fb_post_object_id ) {
						$this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, 'https://graph.facebook.com/' . $fb_post_object_id . '/picture' );
					} else {
						echo isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ? $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $video_photo ) : $this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $post_data->source );
					}
				}
				echo '<div class="slicker-facebook-album-photoshadow"></div>';
				// FB Video play button for facebook videos. This button takes data from our a tag and along with additional js in the magnific-popup.js we can now load html5 videos. SO lightweight this way because no pre-loading of videos are on the page. We only show the posterboard on mobile devices because tablets and desktops will auto load the videos. SRL.
				if ( isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
					if ( isset( $fb_shortcode['play_btn'] ) && 'yes' === $fb_shortcode['play_btn'] ) {
						$fb_play_btn_visible = isset( $fb_shortcode['play_btn_visible'] ) && 'yes' === $fb_shortcode['play_btn_visible'] ? ' visible-video-button' : '';

						// $post_data_source = isset($post_data->source) ? $post_data->source : $embed_html;.
						// $post_data_source = isset($embed_html) ? $embed_html : '';.
						// $post_data_format_3_picture = isset($post_data->format[3]->picture) ? $post_data->format[3]->picture : '';.
						echo '<a href="' . esc_url( $embed_html ) . '"  data-poster="" id="fts-view-vid1-' . esc_attr( $fts_dynamic_vid_name_string ) . '" title="' . esc_html( $fb_description ) . '" class="fts-jal-fb-vid-html5video ' . esc_attr( $fts_view_fb_videos_btn . ' fb-video-popup-' . $fts_dynamic_vid_name_string . ' ' . $fb_play_btn_visible ) . ' fts-slicker-backg" style="height:' . esc_attr( $fb_shortcode['play_btn_size'] ) . ' !important; width:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '; line-height: ' . esc_attr( $fb_shortcode['play_btn_size'] ) . '; font-size:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '"><span class="fts-fb-video-icon" style="height:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '; width:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '; line-height:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '; font-size:' . esc_attr( $fb_shortcode['play_btn_size'] ) . '"></span></a>';

						echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
						if ( $embed_height >= $embed_width ) {
							echo '<div class=' . esc_attr( $data_height ) . ' data-width="' . esc_attr( $embed_width ) . '" data-height="' . esc_attr( $embed_height ) . '"></div>';
						}
						echo '</div>';
					}
				}
				if ( 'album_photos' === ! $fb_shortcode['type'] ) {
					echo '<div class="fts-jal-fb-description-wrap" style="display:none">';
					// Output Photo Name.
					$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type ) : '';
					// Output Photo Caption.
					$fb_caption ? $this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type ) : '';
					// Output Photo Description.
					$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type, null, $fb_by ) : '';
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				break;

			// START ALBUM POST.
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'album':
				echo '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ( 'album_photos' === $fb_shortcode['type'] || 'albums' === $fb_shortcode['type'] ) {
					echo ' style="line-height:' . esc_attr( $fb_shortcode['image_height'] ) . ' !important;"';
				}
				echo '>';

				// echo '<pre>rrr';
				// print_r($fb_album_cover);
				// echo '</pre>';
				// Output Photo Picture.
				$this->fts_facebook_post_photo( $fb_link, $fb_shortcode, $post_data->from->name, $fb_album_cover );
				echo '<div class="slicker-facebook-album-photoshadow"></div>';
				if ( 'albums' === ! $fb_shortcode['type'] ) {
					echo '<div class="fts-jal-fb-description-wrap">';
					// Output Photo Name.
					$fb_name ? $this->fts_facebook_post_name( $fb_link, $fb_name, $fb_type ) : '';
					// Output Photo Caption.
					$fb_caption ? $this->fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type ) : '';
					// Output Photo Description.
					$fb_description ? $this->fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type, null, $fb_by ) : '';
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				break;

		}
		// This puts the video in a popup instead of displaying it directly on the page.
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ) {
			// Post Comments.
			echo '<div class="fts-fb-comments-wrap">';
			$hide_comments_popup = isset( $fb_shortcode['hide_comments_popup'] ) ? $fb_shortcode['hide_comments_popup'] : 'no';
			if ( isset( $lcs_array['comments_thread']->data ) && ! empty( $lcs_array['comments_thread']->data ) && 'yes' !== $hide_comments_popup || isset( $lcs_array['comments_thread']->data ) && ! empty( $lcs_array['comments_thread']->data ) && empty( $hide_comments_popup ) ) {
				// Post Comments.
				echo '<div class="fts-fb-comments-content fts-comments-post-' . esc_attr( $fb_post_id ) . '">';

				foreach ( $lcs_array['comments_thread']->data as $comment ) {
					if ( ! empty( $comment->message ) ) {
						echo '<div class="fts-fb-comment fts-fb-comment-' . esc_attr( $comment->id ) . '">';
						// User Profile Img.
						// Not having page public content access persmission anymore is not allowing us to get profile pics anymore, and the link to personal accounts won't work anymore either for people posting to our page.
						// $avatar_id = isset( $comment->from->id ) ? 'https://graph.facebook.com/'.$comment->from->id.'/picture?redirect=1&type=square' : plugin_dir_url( dirname( __FILE__ ) ) . 'images/slick-comment-pic.png';
						$avatar_id = plugin_dir_url( dirname( __FILE__ ) ) . 'images/slick-comment-pic.png';
						echo '<img class="fts-fb-comment-user-pic" src="' . esc_url( $avatar_id ) . '"/>';
						echo '<div class="fts-fb-comment-msg">';
						if ( isset( $comment->from->name ) ) {
							echo '<span class="fts-fb-comment-user-name">' . esc_html( $comment->from->name ) . '</span> ';
						}
						echo esc_html( $comment->message ) . '</div>';

						// Comment Message.
						echo '</div>';
					}
				}
				echo '</div>';

				// echo '<pre>';
				// print_r( $lcs_array['comments_thread']->data );
				// echo '</pre>';
			}
			echo '</div><!-- END Comments Wrap -->';
		}

		// filter messages to have urls.
		// Output Message For combined feeds in the bottom.
		if ( isset( $fb_shortcode['show_media'] ) && 'top' === $show_media ) {

			if ( isset( $fb_shortcode['show_social_icon'] ) && 'right' === $fb_shortcode['show_social_icon'] ) {
				echo '<div class="fts-mashup-icon-wrap-right fts-mashup-facebook-icon"><a href="' . esc_url( 'https://www.facebook.com/' . $from_id_picture ) . '" target="_blank" rel="noreferrer"></a></div>';
			}
			// show icon.
			if ( isset( $fb_shortcode['show_social_icon'] ) && 'left' === $fb_shortcode['show_social_icon'] ) {
				echo '<div class="fts-mashup-icon-wrap-left fts-mashup-facebook-icon"><a href="' . esc_url( 'https://www.facebook.com/' . $from_id_picture ) . '" target="_blank" rel="noreferrer"></a></div>';
			}
			echo '<div class="fts-jal-fb-top-wrap ' . esc_attr( $hide_date_likes_comments ) . '" style="display:block !important;">';
			echo '<div class="fts-jal-fb-user-thumb">';
			echo ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? '' : '<a href="' . esc_url( 'https://www.facebook.com/' . $from_id_picture ) . '" target="_blank" rel="noreferrer">' ) . '<img border="0" alt="' . ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? esc_attr( $post_data->reviewer->name ) : esc_attr( $post_data->from->name ) ) . '" src="' . esc_url( 'https://graph.facebook.com/' . ( 'reviews' === esc_attr( $fb_shortcode['type'] ) ? $post_data->reviewer->id : $from_id_picture ) . '/picture' ) . '"/></a>' . ( 'reviews' === $fb_shortcode['type'] ? '' : '</a>' );
			echo '</div>';

			// UserName.
			echo '<span class="fts-jal-fb-user-name"><a href="' . esc_url( 'https://www.facebook.com/' . $from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $post_data->from->name ) . '</a>' . esc_html( $fb_hide_shared_by_etc_text ) . '</span>';

			// tied to date function.
			$feed_type      = 'facebook';
			$times          = $custom_time_format;
			$fts_final_date = $this->fts_custom_date( $times, $feed_type );
			// PostTime.
			echo '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';

			if ( ! empty( $fb_places_id ) ) {
				$this->feed_location_option( $fb_places_id, $fb_name, $fb_places_name );
			}

			// here we trim the words for the premium version. The $fb_shortcode['words'] string actually comes from the javascript.
			if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && array_key_exists( 'words', $fb_shortcode ) || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && array_key_exists( 'words', $fb_shortcode ) ) {
				$more            = isset( $more ) ? $more : '';
				$trimmed_content = $this->fts_custom_trim_words( $fb_message, $fb_shortcode['words'], $more );

				echo '<div class="fts-jal-fb-message">';

				echo esc_html( $fb_title_job_opening );
				echo ! empty( $trimmed_content ) ? $trimmed_content : '';
					echo '<div class="fts-clear"></div></div> ';

			} else {
				$fb_final_message = $this->fts_facebook_tag_filter( $fb_message );
				echo '<div class="fts-jal-fb-message">';
				echo nl2br( $fb_final_message );
				echo '<div class="fts-clear"></div></div>';
			}
			echo '</div>';

		}

		echo '<div class="fts-clear"></div>';
		echo '</div>';
		$fb_post_single_id = isset( $fb_post_single_id ) ? $fb_post_single_id : '';
		$single_event_id   = isset( $single_event_id ) ? $single_event_id : '';
		$this->fts_facebook_post_see_more( $fb_link, $lcs_array, $fb_type, $fb_post_id, $fb_shortcode, $fb_post_user_id, $fb_post_single_id, $single_event_id, $post_data );
		echo '<div class="fts-clear"></div>';
		echo '</div>';

	}//end feed_post_types()
}//end class
