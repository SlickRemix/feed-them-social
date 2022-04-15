<?php
/**
 * Feed Them Social - Youtube Feed
 *
 * This page is used to create the YouTube feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Youtube Feed
 *
 * @package feedthemsocial
 */
class FTS_Youtube_Feed_Free extends feed_them_social_functions {

	/**
	 * Construct
	 * u
	 * FTS Youtube Feed constructor.
	 *
	 * @since 2.3.2
	 */
	public function __construct() {

        // Data Protection
        $this->data_protection = new Data_Protection();

		add_shortcode( 'fts_youtube', array( $this, 'fts_youtube_func' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'fts_youtube_head' ) );
	}

	/**
	 * FTS Youtube Head
	 *
	 * @since 2.3.2
	 */
	public function fts_youtube_head() {
		wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css' ), array(), FTS_CURRENT_VERSION, false );
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			$fts_fix_magnific = get_option( 'fts_fix_magnific' ) ? get_option( 'fts_fix_magnific' ) : '';
			if ( isset( $fts_fix_magnific ) && '1' !== $fts_fix_magnific ) {
				wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION, false );
			}
			wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION, false );
		}
	}


	/**
	 * FTS Youtube Functions
	 *
	 * @param string $atts attributes.
	 * @since 2.3.2
	 */
	public function fts_youtube_func( $atts ) {

		$fts_fb_options_nonce = wp_create_nonce( 'fts-instagram-options-page-nonce' );

		if ( wp_verify_nonce( $fts_fb_options_nonce, 'fts-instagram-options-page-nonce' ) ) {

			global $channel_id, $playlist_id, $username_subscribe_btn, $username;

			$youtube_api_key      = get_option( 'youtube_custom_api_token' );
			$youtube_access_token = get_option( 'youtube_custom_access_token' );

			wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );

            // the way this refresh token works atm is. if the token is expired then we fetch a new token when any front end user views a page the feed is on.
            // the ajax runs to fetch a new token if it's expired, then it saves it to the db, but because that happens after the user has already loaded the page,
            // we need to show the cached feed so the feed does not return a token expired message. THEN after the next page reload the actual refreshed token will be in place.
            // we still keep calling the cached version after that point so we are not uses up the API until the users deletes the cache or it is deleted per the determined time.
		    // this will not return the feed proper if token is expired need to fix this
			if ( ! empty( $youtube_access_token ) ) {
				// Double Check Our Expiration Time on the Token and refresh it if needed.
				$expiration_time = get_option( 'youtube_custom_token_exp_time' );

				// Access token is good for 3600 seconds, that about an hour.
				if ( time() > $expiration_time ) {
					 $this->feed_them_youtube_refresh_token();
				}
			}

			if ( ! empty( $youtube_access_token ) && empty( $youtube_api_key ) ) {
                // this relies on our approved app from google.
                // we are only using readme option from google now so we cannot get comments this way.
                // that's fine though since we only allow to show comments in the premium version.
				$youtube_api_key_or_token = 'access_token=' . $youtube_access_token . '';
			} else {
                // you must create your own youtube app now to get this.
                // this is also the method required to show comments as well now.
				$youtube_api_key_or_token = 'key=' . $youtube_api_key . '';
			}

			if ( ! empty( $youtube_api_key ) || ! empty( $youtube_access_token ) ) {

				include_once ABSPATH . 'wp-admin/includes/plugin.php';

				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
					include WP_PLUGIN_DIR . '/feed-them-premium/feeds/youtube/youtube-feed.php';
				} else {
					extract(
						shortcode_atts(
							array(
								'username'                => '',
								'vid_count'               => '1',
								'large_vid'               => '',
								'thumbs_play_in_iframe'   => '',
								'large_vid_description'   => 'yes',
								'large_vid_title'         => 'yes',
								'vids_in_row'             => '4',
								'channel_id'              => '',
								'playlist_id'             => '',
								'username_subscribe_btn'  => '',
								'space_between_videos'    => '',
								'force_columns'           => 'no',
								'thumbs_wrap_color'       => '',
								'thumbs_wrap_height'      => '',
								'maxres_thumbnail_images' => '',
								'video_wrap_display'      => '',
								// for single videos.
								'video_id_or_link'        => '',
								'comments_visible'        => '',
								'comments_count'          => '',

							),
							$atts
						)
					);
				}
				if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $vid_count > '6' ) {
					$vid_count = '6';
				}
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! isset( $popup ) ) {
					$popup          = 'yes';
					$comments_count = '0';
				}
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'popup' === $thumbs_play_in_iframe ) {
					$popup                 = 'yes';
					$thumbs_play_in_iframe = 'no';
				}
				// YouTube has a limit of 50 per page and if you try to load more the array errors so we make sure that does not happen.
				if ( $vid_count > 50 ) {
					$vid_count = '50';
				}

				// free additions so we don't have to update all the plugins.
				extract(
					shortcode_atts(
						array(
							'omit_first_thumbnail' => '',

						),
						$atts
					)
				);

				// if omit_first_thumbnail == yes then we make sure and skip the first iteration in the loop.
				if ( 'yes' === $omit_first_thumbnail ) {
					$b         = false;
					$vid_count = $vid_count++;
				} else {
					$b = true;
				}

				$youtube_show_follow_btn       = get_option( 'youtube_show_follow_btn' );
				$youtube_show_follow_btn_where = get_option( 'youtube_show_follow_btn_where' );
				$fts_functions_class           = new feed_them_social_functions();

				$thumbs_play_iframe = $thumbs_play_in_iframe;

				// Make sure its not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					$_REQUEST['fts_dynamic_name'] = sanitize_key( $this->fts_rand_string() );
					// Create Dynamic Class Name.
					$fts_dynamic_class_name = '';
					if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
						$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
					}
				}

				// check to see of the user added a full youtube link instead of just the id and if so parse out everything but the id we need.
				if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_id_or_link, $match ) ) {
					$video_id_or_link = $match[1];
				}

				if ( empty( $video_id_or_link ) ) {
					if ( ! empty( $username ) ) {
						// here we are getting the users channel ID for their uploaded videos.
						$youtube_user_id_data = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=' . $username . '&' . $youtube_api_key_or_token;
						// $user_id_returned              = $this->fts_get_feed_json( $youtube_user_id_data );
						// $user_id_final                 = json_decode( $user_id_returned['items'] );
						// Youtube Username.
						if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
							$user_cache_name = 'yt_user_' . $username;
						}

						$user_returned = $this->use_cache_check( $youtube_user_id_data, $user_cache_name, 'youtube' );

						// If the YT User returned is not empty and is an arary.
						if ( ! empty( $user_returned ) && is_array( $user_returned ) ) {

							// Decode User's data.
							$user_returned = json_decode( $user_returned['data'] );

							// error_log( print_r( $user_returned, true ) );
							if ( is_object( $user_returned ) && isset( $user_returned->items ) ) {
								// User Playlist ID!
								$user_playlist_id = $user_returned->items[0]->contentDetails->relatedPlaylists->uploads;

								// error_log( print_r( $user_playlist_id, true ) );
								// now we parse the users uploaded vids ID and create the playlist.
								$youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $vid_count . '&playlistId=' . $user_playlist_id . '&order=date&' . $youtube_api_key_or_token ) );
							}
						}

						// Youtube Playlist Cache Name.
						if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
							$feed_cache_name = 'pics_vids_list_' . $username . '_bnum' . $vid_count . '_user';
						}
					} elseif ( ! empty( $channel_id ) && empty( $playlist_id ) ) {

						/*
						$youtube_channel_id_data['items'] = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtube_api_key_or_token ) );
						$user_channel_returned            = $this->fts_get_feed_json( $youtube_channel_id_data );
						$videos                           = $user_channel_returned['items'];

						$videos_check = json_decode( $videos );

						$set_zero = '';
						if ( isset( $videos_check->items ) ) {

							$set_zero = 0;
							foreach ( $videos_check->items as $post_data ) {

								$kind = isset( $post_data->id->kind ) ? $post_data->id->kind : '';
								// This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
								// We will count all the ones that are like this and add that number to the output of posts to offset the posts we are filtering out. Line 319 needs the same treatment of if options.
								if ( 'youtube#playlist' === $kind ) {
									$set_zero++;
								}
							}// END POST foreach.
						}
						$unset_count = $vid_count + $set_zero;
						$vid_count   = $unset_count;*/
						// Uncomment these for testing purposes to see the actual count and the offset count.
						// echo'<pre>';
						// print_r($set_zero);
						// echo'</pre>';
						// echo'<pre>';
						// print_r('vidcount: '.$vid_count);
						// echo'</pre>';
						// echo'<pre>';
						// print_r($videos_check);
						// echo'</pre>';.
						$youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtube_api_key_or_token ) );

						if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
							// Youtube Channel Cache.
							$feed_cache_name = 'pics_vids_list_' . $channel_id . '_bnum' . $vid_count . '_channel';
						}
					} elseif ( ! empty( $playlist_id ) || ! empty( $playlist_id ) && ! empty( $channel_id ) ) {

                        // I don't understand the section here.. blllaaaaaahh need to clean this mess up!
                       // echo '<br/>playlistID shortcode in use: ';

                        $youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $vid_count . '&playlistId=' . $playlist_id . '&order=date&' . $youtube_api_key_or_token ) );

						if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
							// Youtube Playlist Cache Folder.
							$feed_cache_name = 'pics_vids_list_' . $playlist_id . '_bnum' . $vid_count . '_playlist';
						}
					}

					if ( isset( $youtube_feed_api_url ) ) {
                        // YO!
                        // STOPPING HERE. SEEMS AS THOUGH THE URL OR SOMETHING IS NOT CACHING IDK
                        // echo ' why you no use cache check ';
                        // echo $youtube_feed_api_url;
						// Call, fetch and Check data from API url!

                        // echo ' youtube URL: ';
                        // echo $youtube_feed_api_url;
						$feed_returned = $this->use_cache_check( $youtube_feed_api_url, $feed_cache_name, 'youtube' );

						// JSON Decode the Feed Data.
						$videos = json_decode( $feed_returned['data'] );

                        // YO! This is the print_r you want to show most feeds.
                        // echo'playlistID and channelID shortcode used: <pre>';
                        // print_r($videos);
                        // echo'</pre>';

					}
				}

				ob_start();

				// SOCIAL BUTTON TOP.
				if ( ! isset( $_GET['load_more_ajaxing'] ) && empty( $video_id_or_link ) && 'yes' === $youtube_show_follow_btn && 'youtube-follow-above' === $youtube_show_follow_btn_where && ! isset( $_GET['load_more_ajaxing'] ) ) {
					echo '<div class="youtube-social-btn-top">';
					if ( ! empty( $username ) || ! empty( $username_subscribe_btn ) ) {
						echo $this->social_follow_button( 'youtube', $username );
					} elseif ( ! empty( $channel_id ) ) {
						echo $this->social_follow_button( 'youtube', $channel_id );
					}
					echo '</div>';
				}
				// This first line was added to fix the bug that happens when using the popular DIVI theme.
				$ssl = is_ssl() ? 'https' : 'http';

				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

					$video_wrap_display = isset( $video_wrap_display ) ? $video_wrap_display : '2';

					if ( '1' === $video_wrap_display ) {
						$video_wrap_display = ' fts-youtube-thumbs-wrap-option-80-20';
					} elseif ( '2' === $video_wrap_display ) {
						$video_wrap_display = ' fts-youtube-thumbs-wrap-option-60-40';
					} elseif ( '3' === $video_wrap_display ) {
						$video_wrap_display = ' fts-youtube-thumbs-wrap-option-50-50';
					}

					if ( isset( $wrap ) && 'right' === $wrap ) {

						$wrap = ' fts-youtube-thumbs-wrap' . $video_wrap_display . '';

					} elseif ( isset( $wrap ) && 'left' === $wrap ) {
						$wrap = ' fts-youtube-thumbs-wrap-left' . $video_wrap_display . '';
					} else {
						$wrap = '';
					}

					$thumbgallery_class_master = empty( $video_id_or_link ) ? ' fts-youtube-thumbs-gallery-master ' : '';

					echo '<div class="et_smooth_scroll_disabled fts_smooth_scroll_disabled">';
					echo '<div id="fts-yt-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" class="' . esc_attr( $thumbgallery_class_master . 'fts-master-youtube-wrap fts-yt-videogroup fts-yt-user-' . $username . ' fts-yt-vids-in-row' . $vids_in_row ) . '">';
					echo '<div id="fts-yt-videolist-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" class="fts-yt-videolist">';

					if ( isset( $videos->items ) &&  ( 'yes' === $large_vid || '1' === $vids_in_row ) ) {
						foreach ( $videos->items as $post_data ) {
							// we check to make sure no playlist video kinds are in the array ($post_data->id->kind !== 'youtube#playlist') because they return a blank video in the channel feed because youtube is simply adding it to the array for youtube not thinking of the API in this case it would seem.
							$video_check = isset( $post_data->id->kind ) && 'youtube#playlist' === $post_data->id->kind ? 'set' : 'notset';
							if ( 'set' !== $video_check ) {

								$second_video_margin_btm = 'yes' === $large_vid_title && 'yes' !== $large_vid_description ? 'fts-youtube-second-video-margin-btm' : '';

								echo '<div class="fts-yt-large' . esc_attr( $wrap . ' ' . $second_video_margin_btm ) . '">';
								echo '<div class="fts-yt-first-video">';

								if ( 'yes' === $large_vid_title ) {
									echo '<h2>' . esc_html( $this->fts_youtube_title( $post_data ) ) . '</h2>';
								}
								// URL for the video is escaped in this function.
								echo $this->fts_youtube_video_and_wrap( $post_data, $username, $playlist_id );

								$youtube_description   = $this->fts_youtube_tag_filter( $this->fts_youtube_description( $post_data ) );
								$large_vid_description = 'yes' === $large_vid_description ? $large_vid_description : '';

								if ( 'yes' === $large_vid_description ) {
									echo '<p>' . wp_kses(
										$youtube_description,
										array(
											'a'      => array(
												'href'   => array(),
												'title'  => array(),
												'target' => array(),
											),
											'br'     => array(),
											'em'     => array(),
											'strong' => array(),
											'small'  => array(),
										)
									) . '</p>';
								}
								echo '</div>';
								echo '</div>';
								// && $large_vid_title !== 'yes' && $large_vid_description !== 'yes'  are all about being set and if so they we show the oldschool 1 video with title and description format
								if ( 'yes' !== $thumbs_play_in_iframe && 'yes' !== $large_vid_title && 'yes' !== $large_vid_description || 'no' !== $thumbs_play_in_iframe ) {
									// we stop the foreach loop here because we only want the first video in the loop!
									break;
								}
							}
						}
					}

					$columns       = isset( $vids_in_row ) ? $vids_in_row : '';
					$force_columns = isset( $force_columns ) ? $force_columns . '" ' : 'no';

					$space_between_videos = isset( $space_between_videos ) && '' !== $space_between_videos ? $space_between_videos : '1px';

					$thumbs_wrap_color_final  = isset( $thumbs_wrap_color ) ? 'background:' . $thumbs_wrap_color . '!important' : '';
					$thumbs_wrap_color_scroll = isset( $thumbs_wrap_color ) ? 'background:' . $thumbs_wrap_color . '' : '';

					if ( ! empty( $video_id_or_link ) ) {

						echo '<div id="fts-yt-large-' . esc_attr( $video_id_or_link ) . '" class="fts-yt-large' . esc_attr( $wrap ) . '">';
						echo '<div class="fts-yt-first-video">';
						echo '<div class="fts-fluid-videoWrapper">';

						echo '<iframe src="' . esc_url( $ssl . '://www.youtube.com/embed/' . $video_id_or_link . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0 ' ) . '" frameborder="0" allowfullscreen></iframe>';

						echo '</div>';
						echo '</div>';
						echo '</div>';

					} elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! empty( $wrap ) ) {
						$set_comments_height = is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && '' !== $wrap ? 'youtube-comments-wrap-premium ' : '';
						echo '<div class="' . esc_attr( $set_comments_height ) . 'youtube-comments-wrap' . esc_attr( $wrap ) . ' youtube-comments-thumbs"  id="fts-yt-comments"></div>';
					}

					if ( ! empty( $thumbs_wrap_height ) || ! empty( $wrap ) ) {

						echo '<div class="' . esc_attr( $fts_dynamic_class_name . ' fts-youtube-scrollable' . $wrap ) . '" style="height:250px;' . esc_attr( $thumbs_wrap_color_scroll ) . '" >';
					}

					$video_id_or_link_final = isset( $video_id_or_link ) && '' === $video_id_or_link ? $space_between_videos : '';
					$thumbgallery_class     = isset( $video_id_or_link ) && '' !== $video_id_or_link ? ' fts-youtube-no-thumbs-gallery' : '';

					echo '<div data-ftsi-columns="' . esc_attr( $columns ) . '" data-ftsi-force-columns="' . esc_attr( $force_columns ) . '" data-ftsi-margin="' . esc_html( $video_id_or_link_final ) . '" class="' . esc_attr( $fts_dynamic_class_name ) . ' fts-youtube-popup-gallery fts-youtube-inline-block-centered ' . esc_attr( $thumbgallery_class ) . '" style="' . esc_attr( $thumbs_wrap_color_final ) . '"">';

					if ( ! empty( $video_id_or_link ) ) {

						$youtube_video_url = 'https://www.youtube.com/watch?v=' . $video_id_or_link . '';

						$set_comments_height = is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && '' !== $wrap ? 'youtube-comments-wrap-premium ' : '';

						if ( 'right' !== $wrap || 'left' !== $wrap ) {
							echo '<div class="fts-youtube-noscroll">';
						}

						echo '<div class="' . esc_attr( $set_comments_height ) . 'youtube-comments-wrap' . esc_attr( $wrap ) . '"  style="display: block !important;">';

						$this->fts_youtube_single_video_info( $video_id_or_link, $youtube_api_key_or_token );

						echo $fts_functions_class->fts_share_option( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
						echo '<a href="' . esc_url( $youtube_video_url ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html__( 'View on YouTube', 'feed-them-premium' ) . '</a>';

                        // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions greanted to view comments.
                        if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' )  && isset( $comments_count ) && '0' !== $comments_count && !empty( get_option( 'youtube_custom_api_token' ) ) ) {
                            $this->fts_youtube_commentThreads( $video_id_or_link, $youtube_api_key_or_token, $comments_count );
						}

						echo '</div>';

						if ( 'right' !== $wrap || 'left' !== $wrap ) {
							echo '</div>';
						}

						echo '</div>';

					}
				}
				if ( '0' !== $vids_in_row && empty( $video_id_or_link ) && 'yes' !== $large_vid_title && 'yes' !== $large_vid_description && isset( $videos->items ) ) {

					$count = '0';
					foreach ( $videos->items as $post_data ) {
						$kind = isset( $post_data->id->kind ) ? $post_data->id->kind : '';
						// if omit_first_thumbnail == yes then we make sure and skip the first iteration in the loop.
						if ( ! $b ) {
							$b = true;
							continue;
						}

						// print $omit_first_thumbnail;.
						// This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
						if ( 'youtube#playlist' !== $kind ) {

							$user_name_href = 'https://www.youtube.com/channel/' . $post_data->snippet->channelId;
							$date           = $this->fts_custom_date( $post_data->snippet->publishedAt, 'youtube' );

							$thumbnail = isset( $post_data->snippet->thumbnails->standard->url ) ? $post_data->snippet->thumbnails->standard->url : $post_data->snippet->thumbnails->high->url;

							$maxres_thumbnail_images = isset( $maxres_thumbnail_images ) && '' !== $maxres_thumbnail_images ? $maxres_thumbnail_images : '';

							if ( isset( $post_data->snippet->thumbnails->maxres->url ) && 'yes' === $maxres_thumbnail_images ) {
								$thumbnail = $post_data->snippet->thumbnails->maxres->url;
							} else {
								$thumbnail = $thumbnail;
							}

							if ( ! empty( $username ) || ! empty( $playlist_id ) ) {
								$video_id = $post_data->snippet->resourceId->videoId;
							} else {
								$video_id = isset( $post_data->id->videoId ) ? $post_data->id->videoId : $post_data->id->playlistId;
							}

							$popup_set = isset( $wrap ) && '' !== $wrap && isset( $thumbs_play_in_iframe ) && 'yes' === $thumbs_play_in_iframe || ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ? 'slicker-youtube-placeholder-' . sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) . ' ' : '';

							echo '<div class="' . esc_html( $popup_set ) . 'slicker-youtube-placeholder fts-youtube-' . esc_attr( $video_id ) . '" data-id="fts-youtube-id-' . esc_attr( $fts_dynamic_class_name ) . '" style="background-image:url(' . esc_url( $thumbnail ) . ')">';

							$youtube_title       = $this->fts_youtube_title( $post_data );
							$youtube_description = $this->fts_youtube_tag_filter( $this->fts_youtube_description( $post_data ) );
							$channel_title       = $post_data->snippet->channelTitle;

							$url    = is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && 'yes' !== $thumbs_play_iframe ? ' fts-yt-popup-open' : '';
							$target = 'yes' === $thumbs_play_iframe ? '' : 'target="_blank"';

							if ( ! empty( $username ) || ! empty( $playlist_id ) ) { // https://www.youtube.com/watch?v=g9ArG6H_z0Q.

								$youtube_video_url = $ssl . '://www.youtube.com/watch?v=' . $video_id;

								$href         = isset( $thumbs_play_iframe ) && 'yes' === $thumbs_play_iframe ? 'javascript:;' : esc_url_raw( $youtube_video_url );
								$iframe_embed = '' . $ssl . '://www.youtube.com/embed/' . $video_id . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque';
								$iframe       = isset( $thumbs_play_iframe ) && 'yes' === $thumbs_play_iframe ? ' fts-youtube-iframe-click' : '';
								// escaping the $href above because one option is html and one is url raw.
								echo '<a href="' . $href . '" rel="' . esc_url_raw( $iframe_embed ) . '" ' . esc_attr( $target ) . ' class="fts-yt-open' . esc_attr( $url . $iframe ) . '"></a>';

								if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
									// echo '<div id="#fts-' . $video_id . '" class="fts-yt-overlay-wrap">';.
									echo '<div class="entriestitle fts-youtube-popup fts-facebook-popup"><div class="fts-master-youtube-wrap-close fts-yt-close-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '"></div>';
									echo '<h3><a href="' . esc_url( $user_name_href ) . '" target="_blank">' . esc_html( $channel_title ) . '</a></h3>';
									echo '<div class="fts-youtube-date">' . esc_html( $date ) . '</div>';
									echo '<h4>' . esc_html( $youtube_title ) . '</h4>';
									echo '<div class="fts-youtube-description-popup">' . wp_kses(
										$youtube_description,
										array(
											'a'      => array(
												'href'   => array(),
												'title'  => array(),
												'target' => array(),
											),
											'br'     => array(),
											'em'     => array(),
											'strong' => array(),
											'small'  => array(),
										)
									) . '</div>';
									echo $fts_functions_class->fts_share_option( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
									echo '<a href="' . esc_url( $youtube_video_url ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html__( 'View on YouTube', 'feed-them-premium' ) . '</a>';
                                    // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions greanted to view comments.
                                    if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' )  && isset( $comments_count ) && '0' !== $comments_count && !empty( get_option( 'youtube_custom_api_token' ) ) ) {
                                        $this->fts_youtube_commentThreads( $video_id, $youtube_api_key_or_token, $comments_count );
									}
									echo '</div>';
								}
							} else {

								$youtube_video_url = $ssl . '://www.youtube.com/watch?v=' . $video_id;

								$href         = isset( $thumbs_play_iframe ) && 'yes' === $thumbs_play_iframe ? esc_html( 'javascript:;' ) : esc_url_raw( $youtube_video_url );
								$iframe_embed = '' . $ssl . '://www.youtube.com/embed/' . $video_id . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=1&wmode=opaque';
								$iframe       = isset( $thumbs_play_iframe ) && 'yes' === $thumbs_play_iframe ? ' fts-youtube-iframe-click' : '';
								// escaping the $href above because one option is html and one is url raw.
								echo '<a href="' . $href . '" rel="' . esc_url_raw( $iframe_embed ) . '" ' . esc_attr( $target ) . ' class="fts-yt-open' . esc_attr( $url . $iframe ) . '"></a>';

								if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
									echo '<div class="entriestitle fts-youtube-popup fts-facebook-popup"><div class="fts-master-youtube-wrap-close fts-yt-close-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '"></div>';
									echo '<h3><a href="' . esc_url( $user_name_href ) . '" target="_blank">' . esc_html( $channel_title ) . '</a></h3>';
									echo '<div class="fts-youtube-date">' . esc_html( $date ) . '</div>';
									echo '<h4>' . esc_html( $youtube_title ) . '</h4>';
									echo '<div class="fts-youtube-description-popup">' . wp_kses(
										$youtube_description,
										array(
											'a'      => array(
												'href'   => array(),
												'title'  => array(),
												'target' => array(),
											),
											'br'     => array(),
											'em'     => array(),
											'strong' => array(),
											'small'  => array(),
										)
									) . '</div>';
									echo $fts_functions_class->fts_share_option( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
									echo '<a href="' . esc_url( $youtube_video_url ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html__( 'View on YouTube', 'feed-them-premium' ) . '</a>';

                                    // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions greanted to view comments.
                                    if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' )  && isset( $comments_count ) && '0' !== $comments_count && !empty( get_option( 'youtube_custom_api_token' ) ) ) {
                                        $this->fts_youtube_commentThreads( $video_id, $youtube_api_key_or_token, $comments_count );
									}
									echo '</div>';
								}
							}
							echo '</div>';
						}
						$count++;
						if ( $count === $vid_count ) {
							break;
						}
					}
				}

				if ( empty( $video_id_or_link ) ) {

					// Load More BUTTON Start.
					$youtube_load_more_text      = get_option( 'youtube_load_more_text' ) ? get_option( 'youtube_load_more_text' ) : __( 'Load More', 'feed-them-social' );
					$youtube_no_more_videos_text = get_option( 'youtube_no_more_videos_text' ) ? get_option( 'youtube_no_more_videos_text' ) : __( 'No More Videos', 'feed-them-social' );

					if ( ! empty( $username ) ) {
						// now we parse the users uploaded vids ID and create the playlist.
						$next_url = isset( $videos->nextPageToken ) ? 'https://www.googleapis.com/youtube/v3/playlistItems?pageToken=' . $videos->nextPageToken . '&part=snippet&maxResults=' . $vid_count . '&playlistId=' . $user_playlist_id . '&order=date&' . $youtube_api_key_or_token : '';
					} elseif ( ! empty( $channel_id ) && empty( $playlist_id ) ) {
						$next_url = isset( $videos->nextPageToken ) ? 'https://www.googleapis.com/youtube/v3/search?pageToken=' . $videos->nextPageToken . '&part=snippet&channelId=' . $channel_id . '&order=date&maxResults=' . $vid_count . '&' . $youtube_api_key_or_token : '';
					} elseif ( ! empty( $playlist_id ) || ! empty( $playlist_id ) && ! empty( $channel_id ) ) {
						$next_url = isset( $videos->nextPageToken ) ? 'https://www.googleapis.com/youtube/v3/playlistItems?pageToken=' . $videos->nextPageToken . '&part=snippet&maxResults=' . $vid_count . '&playlistId=' . $playlist_id . '&order=date&' . $youtube_api_key_or_token : '';
					}

					if ( ! empty( $loadmore ) && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
						$loadmore_count = isset( $loadmore_count ) ? $loadmore_count : '';
						// we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of pics/videos.
						$_REQUEST['next_url'] = ! empty( $loadmore ) ? str_replace( 'maxResults=' . $vid_count, 'maxResults=' . $loadmore_count, $next_url ) : $next_url;

						?><script>
						    var nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?>= "<?php echo esc_url_raw( $_REQUEST['next_url'] ) ?>";
						</script>
						<?php
					}
					// Make sure it's not ajaxing.
					if ( ! isset( $_GET['load_more_ajaxing'] ) && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! empty( $loadmore ) ) {
						$fts_dynamic_name       = sanitize_key( $_REQUEST['fts_dynamic_name'] );
						$time                   = time();
						$nonce                  = wp_create_nonce( $time . 'load-more-nonce' );
						$fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
                        ?>
					<script>
						jQuery(document).ready(function() {

                        <?php if ( 'autoscroll' === $loadmore ) { ?>


                            // If =autoscroll in shortcode.
                            jQuery(".<?php echo esc_js( $fts_dynamic_class_name ) ?>").bind("scroll",function() {

                                // 4-9-22 SRL: added +1 because it needs an extra pixel of space to fire to function when shortcode is in smaller containers.
                                if( 1 + jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight ) {

                                    console.log( jQuery(this).scrollTop() + jQuery(this).innerHeight() );
                                    console.log( jQuery(this)[0].scrollHeight );

                        <?php }
                            else { ?>
                            // If =button in shortcode.
                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").unbind().click(function() {
                        <?php } ?>
                                jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").addClass("fts-fb-spinner");
                                var button = jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');

                                console.log(button);
                                console.log(nextURL_<?php echo esc_js( $fts_dynamic_name )  ?>);

                                var yes_ajax = "yes";
                                var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ) ?>";
                                var fts_security = "<?php echo esc_js( $nonce ) ?>";
                                var fts_time = "<?php echo esc_js( $time ) ?>";

                                var feed_name = "fts_youtube";
                                var loadmore_count = "vid_count=<?php echo esc_js( $loadmore_count ) ?>";
                                var feed_attributes = <?php echo wp_json_encode( $atts ) ?>;

                                jQuery.ajax({
                                    data: {
                                        action: "my_fts_fb_load_more",
                                        next_url: nextURL_<?php echo esc_js( $fts_dynamic_name ) ?>,
                                        fts_dynamic_name: fts_d_name,
                                        feed_name: feed_name,
                                        loadmore_count: loadmore_count,
                                        feed_attributes: feed_attributes,
                                        load_more_ajaxing: yes_ajax,
                                        fts_security: fts_security,
                                        fts_time: fts_time
                                    },
                                    type: "GET",
                                    url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>",
                                    success: function( data ) {
                                        console.log("Well Done and got this from sever: " + data);

                                        var result = jQuery(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").append(data).filter(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").html();

                                        jQuery(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").html(result);

                                        if( !nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?> ||  "no more" === nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?> ){
                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo esc_js( $youtube_no_more_videos_text ) ?></div>');
                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").removeAttr("id");
                                        }
                                        else {
                                            jQuery(".<?php echo esc_js( $fts_dynamic_class_name ) ?>").off('scroll');
                                        }

                                        <?php if ( 'button' === $loadmore ) { ?>
                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").html("<?php echo esc_html( $youtube_load_more_text ) ?>");
                                        <?php } ?>

                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").removeClass("fts-fb-spinner");

                                        <?php if ( 'yes' === $popup ) { ?>
                                            // We return this function again otherwise the popup won't work correctly for the newly loaded items.
                                            jQuery.fn.slickYoutubePopUpFunction();
                                        <?php } ?>

                                        // Reload the share each funcion otherwise you can't open share option.
                                        jQuery.fn.ftsShare();

                                        // Reload our margin for the demo.
                                        if(typeof outputSRmargin === "function"){
                                            outputSRmargin(document.querySelector("#margin").value);
                                        }

                                        // Reload our image sizing function so the images show up proper.
                                        slickremixImageResizingYouTube();
                                    }
                                });// end of ajax().
                            return false;
                            // string $scrollMore is at top of this js script. exception for scroll option closing tag.
                            <?php if ( 'autoscroll' === $loadmore ) { ?>
                                    };
                                }); // end of scroll ajax load.
                            <?php } else { ?>
                                }); // end of click button.
                            <?php } ?>
						}); // end of document.ready.
                    </script><?php

					}//End Check.
					// for gallery option play_video_in_iframe.
					if ( 'yes' === $thumbs_play_in_iframe && ! isset( $_GET['load_more_ajaxing'] ) ) {
						echo '<script>';

						echo '  jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '").unbind().on("click", ".slicker-youtube-placeholder", function(event) {
                    event.stopPropagation();
                    jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .youtube-comments-thumbs").animate({ scrollTop: 0 }, "fast");
                    jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .youtube-comments-thumbs").show();
                    jQuery( "#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '.fts-youtube-scrollable" ).addClass( "fts-scrollable-function" );
                    jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .fts-youtube-scrollable, #fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .fts-fb-autoscroll-loader").hide();
                    var this_frame = jQuery(this).find("a.fts-youtube-iframe-click").attr("rel");
                    jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .fts-fluid-videoWrapper iframe").attr("src", this_frame);
                    var findText = jQuery(this).find(".entriestitle").clone(true, true);
                    findText.appendTo("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .youtube-comments-thumbs");
                    jQuery.fn.ftsShare();
                    
                    });
                    jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '").on("click", ".fts-yt-close-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '", function(event) {
                        event.stopPropagation();
                        jQuery( "#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .fts-youtube-scrollable" ).removeClass( "fts-scrollable-function" );
                        jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .youtube-comments-thumbs").hide();
                        jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .fts-youtube-scrollable, .fts-fb-autoscroll-loader").show();
                        jQuery("#fts-yt-' . esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . ' .youtube-comments-thumbs").html("");
                         slickremixImageResizingYouTube();
                    });';
						echo '</script>';
					}
				}// END if($video_id_or_link == '').
				// main closing div not included in ajax check so we can close the wrap at all times.
				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					$fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );

					// this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;".
					echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more-output"></div>';
					echo '</div><!--END main wrap for thumbnails-->';
					// END main wrap for thumbnails.
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'autoscroll' === $loadmore ) {
						echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader" style="' . esc_attr( $thumbs_wrap_color_final ) . '"></div>';
					}
					if ( ! empty( $thumbs_wrap_height ) || ! empty( $wrap ) ) {
						echo '</div>';
						// End If scroll.
					}

					echo '</div>'; // End fts-yt-videolist.
					echo '</div>'; // fts-master-youtube-wrap.
					echo '</div>'; // End DIVI theme .et_smooth_scroll_disabled.

				}

				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					echo '<div class="fts-clear"></div>';
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'button' === $loadmore ) {

						echo '<div class="fts-youtube-load-more-wrapper">';
						echo '<div id="loadMore_' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" style="';
						if ( ! empty( $loadmore_btn_maxwidth ) ) {
							echo 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
						}
						$loadmore_btn_margin = isset( $loadmore_btn_margin ) ? $loadmore_btn_margin : '20px';
						echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $youtube_load_more_text ) . '</div>';
						echo '</div>';
					}
				}//End Check.

				unset( $_REQUEST['next_url'] );

				// SOCIAL BUTTON BOTTOM.
				if ( isset( $youtube_show_follow_btn ) && 'yes' === $youtube_show_follow_btn && 'youtube-follow-below' === $youtube_show_follow_btn_where && ! isset( $_GET['load_more_ajaxing'] ) ) {
					echo '<div class="youtube-social-btn-bottom">';

					if ( ! empty( $username ) || ! empty( $username_subscribe_btn ) ) {
						echo $this->social_follow_button( 'youtube', $username );
					} elseif ( ! empty( $channel_id ) ) {
						echo $this->social_follow_button( 'youtube', $channel_id );
					}
					echo '</div>';
				}

				return ob_get_clean();

			} else {
				print 'Please add an access token to the Youtube Options page of Feed Them Social.';
			}
		}
	}

	/**
	 * Get FTS Dnamic Class Name
	 *
	 * @return string
	 * @since 1.9.6
	 */
	public function get_fts_dynamic_class_name() {
		$fts_dynamic_name_nonce = wp_create_nonce( 'fts-dynamic-name-nonce' );

		if ( wp_verify_nonce( $fts_dynamic_name_nonce, 'fts-dynamic-name-nonce' ) ) {
			$fts_dynamic_class_name = '';
			if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
				$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
			}
			return $fts_dynamic_class_name;
		}
		exit;
	}

	/**
	 * FTS YouTube Tag Filter
	 *
	 * Tags Filter (return clean tags)
	 *
	 * @param string $youtube_description youtube description string to filter.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_youtube_tag_filter( $youtube_description ) {

		// Create links from @mentions and regular links.
		$youtube_description = preg_replace( '/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $youtube_description );
		$youtube_description = preg_replace( '/[#]+([0-9\p{L}]+)/u', '<a href="https://www.youtube.com/results?search_query=%23$1" target="_blank">$0</a>', $youtube_description );
		return nl2br( $youtube_description );
	}

	/**
	 * Youtube Comments Thread
	 *
	 * @param string  $video_id Video id.
	 * @param string  $youtube_api_key_or_token Youtube token.
	 * @param integer $comments_count Comments Count.
	 * @since 1.9.6
	 */
	public function fts_youtube_commentThreads( $video_id, $youtube_api_key_or_token, $comments_count ) {

		$fts_comments_thread_nonce = wp_create_nonce( 'fts-comments-thread-nonce' );

		if ( wp_verify_nonce( $fts_comments_thread_nonce, 'fts-comments-thread-nonce' ) ) {

			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// Youtube Comment Cache!
				$youtube_comments_cache_url = 'video_comments_list_' . $video_id . '_number_comments_' . $comments_count . '';
			}

			// Youtube Use Comments Cache!
			if ( false !== $this->fts_check_feed_cache_exists( $youtube_comments_cache_url ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
				$comments = json_decode( $this->fts_get_feed_cache( $youtube_comments_cache_url ) );
			} else {
				// https://developers.google.com/youtube/v3/docs/comments/list.
				$comments['items'] = 'https://www.googleapis.com/youtube/v3/commentThreads?' . $youtube_api_key_or_token . '&textFormat=plainText&part=snippet&videoId=' . $video_id . '&maxResults=' . $comments_count . '';
				$comments_returned = $this->fts_get_feed_json( $comments );
				$comments          = json_decode( $comments_returned['items'] );

				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					$this->fts_create_feed_cache( $youtube_comments_cache_url, $comments );
				}
			}

			if ( 0 !== $comments->pageInfo->totalResults ) {
				$output = '';
				echo '<div class="fts-fb-comments-content">';
				foreach ( $comments->items as $comment_data ) {
					$message = $comment_data->snippet->topLevelComment->snippet->textDisplay;
					if ( '><!!' !== $message ) {

						$youtube_comment = $this->fts_youtube_tag_filter( $message );

						echo '<div class="fts-fb-comment">';
						echo '<a href="' . $comment_data->snippet->topLevelComment->snippet->authorChannelUrl . '" target="_blank" class="">';
						echo '<img src="' . $comment_data->snippet->topLevelComment->snippet->authorProfileImageUrl . '" class="fts-fb-comment-user-pic"/>';
						echo '</a>';
						echo '<div class="fts-fb-comment-msg">';
						echo '<span class="fts-fb-comment-user-name">';
						echo '<a href="' . $comment_data->snippet->topLevelComment->snippet->authorChannelUrl . '" target="_blank" class="">';
						echo $comment_data->snippet->topLevelComment->snippet->authorDisplayName;
						echo '</a>';
						echo '</span> ';
						echo '<span class="fts-fb-comment-date">' . esc_html( $this->fts_custom_date( $comment_data->snippet->topLevelComment->snippet->publishedAt, 'youtube' ) ) . '</span><br/>';
						echo wp_kses(
							$youtube_comment,
							array(
								'a'      => array(
									'href'   => array(),
									'title'  => array(),
									'target' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						);
						echo '</div>';
						echo '</div>';
					}
				}
				echo '</div>';
			}
		}
	}


	/**
	 * FTS Youtube Single Video Info
	 *
	 * @param string $video_id Video id.
	 * @param string $youtube_api_key_or_token Youtube token.
	 * @since 1.9.6
	 */
	public function fts_youtube_single_video_info( $video_id, $youtube_api_key_or_token ) {
		$fts_single_video_nonce = wp_create_nonce( 'fts-single-video-thread-nonce' );

		if ( wp_verify_nonce( $fts_single_video_nonce, 'fts-single-video-thread-nonce' ) ) {

			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

				// Youtube Comment Cache.
				$youtube_single_video_cache_name = 'video_single_' . $video_id . '';
			}
			// https://developers.google.com/youtube/v3/docs/comments/list.
            $api_url['items'] = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&' . $youtube_api_key_or_token . '&part=snippet';

            $video = $this->use_cache_check( $api_url, $youtube_single_video_cache_name, 'youtube_single' );

            $feed_data = json_decode( $video['items'] );

			foreach ( $feed_data->items as $video_data ) {
				$user_name_href      = 'https://www.youtube.com/channel/' . $video_data->snippet->channelId;
				$channel_title       = $video_data->snippet->channelTitle;
				$youtube_title       = $this->fts_youtube_title( $video_data );
				$youtube_description = $this->fts_youtube_tag_filter( $this->fts_youtube_description( $video_data ) );
				$date                = $this->fts_custom_date( $video_data->snippet->publishedAt, 'youtube' );

				echo '<div class="entriestitle fts-youtube-popup fts-facebook-popup"  style="display: block !important;">';
				echo '<h3><a href="' . esc_url( $user_name_href ) . '" target="_blank">' . esc_html( $channel_title ) . '</a></h3>';
				echo '<div class="fts-youtube-date">' . esc_html( $date ) . '</div>';
				echo '<h4>' . esc_html( $youtube_title ) . '</h4>';
				echo '<div class="fts-youtube-description-popup">' . wp_kses(
					$youtube_description,
					array(
						'a'      => array(
							'href'   => array(),
							'title'  => array(),
							'target' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</div>';

			}
		}
	}

	/**
	 * Random String generator
	 *
	 * @param int $length String length.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_rand_string( $length = 10 ) {
		$characters        = 'abcdefghijklmnopqrstuvwxyz';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
		}

		return $random_string;
	}
}
