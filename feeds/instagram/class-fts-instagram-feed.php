<?php
/**
 * Feed Them Social - Instagram Feed
 *
 * This file is used to create the Instagram Feeds
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace feedthemsocial;

/**
 * Class FTS_Instagram_Feed
 *
 * @package feedthemsocial
 */
class FTS_Instagram_Feed extends feed_them_social_functions {

	/**
	 * Construct
	 *
	 * Instagram Feed constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
		add_shortcode( 'fts_instagram', array( $this, 'fts_instagram_func' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fts_instagram_head' ) );
	}

	/**
	 * Convert Instagram Description Links using
	 *
	 * Takes our description and converts and links to a tags.
	 *
	 * @param string $bio The Bio.
	 * @return null|string|string[]
	 * @since 1.9.6
	 */
	public function convert_instagram_description_links( $bio ) {
		// Create links from @mentions and regular links.
		$bio = preg_replace( '~https?://[^<>\s]+~i', '<a href="$0" target="_blank">$0</a>', $bio );
		$bio = preg_replace( '/#+(\w+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank">$0</a>', $bio );
		$bio = preg_replace( '/@+(\w+)/u', '<a href="https://www.instagram.com/$1" target="_blank">@$1</a>', $bio );

		return $bio;
	}

	/**
	 * Convert Instagram Links
	 *
	 * Convert any link found in the description to a clickable one.
	 *
	 * @param string $instagram_caption_a_title Caption title.
	 * @return null|string|string[]
	 * @since 1.9.6
	 */
	public function convert_instagram_links( $instagram_caption_a_title ) {
		// Create links from @mentions, #hashtags and regular links.
		$instagram_caption_a_title = preg_replace( '~https?://[^<>\s]+~i', '<a href="$0" target="_blank">$0</a>', $instagram_caption_a_title );
		$instagram_caption         = preg_replace( '/#+(\w+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank">$0</a>', $instagram_caption_a_title );
		$instagram_caption         = preg_replace( '/@+(\w+)/u', '<a href="https://www.instagram.com/$1" target="_blank">@$1</a>', $instagram_caption );

		return $instagram_caption;
	}

	/**
	 * FTS Instagram Head
	 *
	 * Enqueue styles for this feed.
	 *
	 * @since 1.9.6
	 */
	public function fts_instagram_head() {
		wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css' ), array(), FTS_CURRENT_VERSION );
	}

	/**
	 * FTS Instagram Likes Count
	 *
	 * Convert the likes count to a usable number.
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_likes_count( $post_data ) {
		// These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
		$hastag_likes    = isset( $post_data->like_count ) ? $post_data->like_count : '';
		$instagram_likes = isset( $post_data->likes->count ) ? $post_data->likes->count : $hastag_likes;
		// here we add a , for all numbers below 9,999.
		if ( isset( $instagram_likes ) && $instagram_likes <= 9999 ) {
			$instagram_likes = number_format( $instagram_likes );
		}
		// here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
		if ( isset( $instagram_likes ) && $instagram_likes >= 1000000 ) {
			$instagram_likes = round( ( $instagram_likes / 1000000 ), 1 ) . 'm';
		}
		// here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands.
		if ( isset( $instagram_likes ) && $instagram_likes >= 10000 ) {
			$instagram_likes = round( ( $instagram_likes / 1000 ), 1 ) . 'k';
		}

		return $instagram_likes;
	}

	/**
	 * FTS Instagram Comments Count
	 *
	 * Convert the likes count to a usable number.
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_comments_count( $post_data ) {
		$hastag_comments    = isset( $post_data->comments_count ) ? $post_data->comments_count : '';
		$instagram_comments = isset( $post_data->comments->count ) ? $post_data->comments->count : $hastag_comments;
		// here we add a , for all numbers below 9,999.
		if ( isset( $instagram_comments ) && $instagram_comments <= 9999 ) {
			$instagram_comments = number_format( $instagram_comments );
		}
		// here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions.
		if ( isset( $instagram_comments ) && $instagram_comments >= 1000000 ) {
			$instagram_comments = round( ( $instagram_comments / 1000000 ), 1 ) . 'm';
		}
		// here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands.
		if ( isset( $instagram_comments ) && $instagram_comments >= 10000 ) {
			$instagram_comments = round( ( $instagram_comments / 1000 ), 1 ) . 'k';
		}

		return $instagram_comments;
	}

	/**
	 * FTS Instagram Likes Comments Wrap
	 *
	 * Output the likes and comments ul wrapper.
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_likes_comments_wrap( $post_data ) {
		return '<ul class="heart-comments-wrap"><li class="instagram-image-likes">' . $this->fts_instagram_likes_count( $post_data ) . '</li><li class="instagram-image-comments">' . $this->fts_instagram_comments_count( $post_data ) . '</li></ul>';
	}

	/**
	 * FTS Instagram Image Link
	 *
	 * Instagram image url from the API
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	// One of these statements needs to be fixed so if the video is the first carousel post then we need to use the $post_data->permalink . 'media?size=l', it works ok for single post videos but not carousel ones.
	public function fts_instagram_image_link( $post_data ) {

		$hashtag_children = isset( $post_data->children ) ? $post_data->children->data[0]->media_url : '';

		$instagram_api_children = isset( $post_data->images ) ? $post_data->images->standard_resolution->url : $hashtag_children;

		$data_type_child = strpos( $hashtag_children, 'mp4' ) ? $post_data->permalink . 'media?size=l' : $instagram_api_children ;

		$hastag_media_url = isset( $post_data->media_url ) ? $post_data->media_url : $data_type_child;

		$hastag_media_url_final = isset( $post_data->media_type ) && 'VIDEO' === $post_data->media_type ? $post_data->permalink . 'media?size=l' : $hastag_media_url;

		$instagram_lowrez_url = isset( $post_data->images->standard_resolution->url ) ? $post_data->images->standard_resolution->url : $hastag_media_url_final;

		return $instagram_lowrez_url;
	}

	/**
	 * FTS Instagram Carousel Images
	 *
	 * Instagram image url from the API
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_carousel_links( $post_data ) {

		foreach ( $post_data->children->data as $image ) {?>
		<div class='slicker-instagram-placeholder fts-instagram-wrapper' style='background-image:url(
			<?php
		}

	}

	/**
	 * FTS Instagram Video Link
	 *
	 * Video Link from Instagram API
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_video_link( $post_data ) {

		$hashtag_children = isset( $post_data->children ) ? $post_data->children->data[0]->media_url : '';

		$instagram_api_children = isset( $post_data->videos ) ? $post_data->videos->standard_resolution->url : $hashtag_children;

		$hastag_media_url = isset( $post_data->media_url ) ? $post_data->media_url : $instagram_api_children;

		$instagram_video_standard_resolution = isset( $post_data->videos->standard_resolution->url ) ? $post_data->videos->standard_resolution->url : $hastag_media_url;

		return $instagram_video_standard_resolution;
	}

	/**
	 * FTS Instagram Description
	 *
	 * Description of image from Instagram API
	 *
	 * @param string $post_data Post data.
	 * @return null|string|string[]
	 * @since 1.9.6
	 */
	public function fts_instagram_description( $post_data ) {

		$hastag_caption            = isset( $post_data->caption ) ? $post_data->caption : '';
		$instagram_caption_a_title = isset( $post_data->caption->text ) ? $post_data->caption->text : $hastag_caption;
		$instagram_caption_a_title = htmlspecialchars( $instagram_caption_a_title );
		$instagram_caption         = $this->convert_instagram_links( $instagram_caption_a_title );

		return $instagram_caption;
	}

	/**
	 * FTS View on Instagram url
	 *
	 * Link to view the image on Instagram
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_view_on_instagram_url( $post_data ) {
		$hastag_permalink   = isset( $post_data->permalink ) ? $post_data->permalink : '';
		$instagram_post_url = isset( $post_data->link ) ? $post_data->link : $hastag_permalink;

		return $instagram_post_url;
	}

	/**
	 * FTS View on Instagram Link
	 *
	 * Full a tag with Instagram url from the function above
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_view_on_instagram_link( $post_data ) {
		return '<a href="' . esc_url( $this->fts_view_on_instagram_url( $post_data ) ) . '" class="fts-view-on-instagram-link" target="_blank">' . esc_html( 'View on Instagram', 'feed-them-social' ) . '</a>';
	}

	/**
	 * FTS Instagram Popup Description
	 *
	 * Full description for our popup
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_instagram_popup_description( $post_data ) {
		return '<div class="fts-instagram-caption"><div class="fts-instagram-caption-content"><p>' . $this->fts_instagram_description( $post_data ) . '</p></div>' . $this->fts_view_on_instagram_link( $post_data ) . '</div>';
	}

	/**
	 * FTS Instagram Function
	 *
	 * Display the Instagram Feed.
	 *
	 * @param array $atts Attributes array.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_instagram_func( $atts ) {

		$fts_instagram_feed_nonce = wp_create_nonce( 'fts-instagram-feed-page-nonce' );

		if ( wp_verify_nonce( $fts_instagram_feed_nonce, 'fts-instagram-feed-page-nonce' ) ) {

			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
				include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/instagram/instagram-feed.php';
				// $popup variable comes from the premium version
				if ( isset( $popup ) && 'yes' === $popup ) {
					// it's ok if these styles & scripts load at the bottom of the page.
					$fts_fix_magnific = get_option( 'fts_fix_magnific' ) ? get_option( 'fts_fix_magnific' ) : '';
					if ( '1' !== $fts_fix_magnific ) {
						wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION, false );
					}
					wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION, false );
				}
			} else {
				extract(
					shortcode_atts(
						array(
							'instagram_id'             => '',
							'type'                     => '',
							'pics_count'               => '',
							'super_gallery'            => '',
							'image_size'               => '',
							'icon_size'                => '',
							'space_between_photos'     => '',
							'hide_date_likes_comments' => '',
							'center_container'         => '',
							'height'                   => '',
							'width'                    => '',
							// user profile options.
							'profile_wrap'             => '',
							'profile_photo'            => '',
							'profile_stats'            => '',
							'profile_name'             => '',
							'profile_description'      => '',
							'columns'                  => '',
							'force_columns'            => '',
							'access_token'             => '',
						),
						$atts
					)
				);
				if ( null === $pics_count ) {
					$pics_count = '6';
				}
			}
			// Added new debug option SRL: 6/7/18.
			extract(
				shortcode_atts(
					array(
						'debug'          => '',
						'debug_userinfo' => '',
					),
					$atts
				)
			);

			if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $pics_count > '6' ) {
						$pics_count = '6';
			}

			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				$pics_count = $pics_count;
			} else {
				$pics_count = '10';
			}

			wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			$instagram_data_array = array();

			$fts_hashtag_check_token_type        = '' === $access_token ? get_option( 'fts_facebook_instagram_custom_api_token' ) : $access_token;
			$fts_check_token_type                = '' === $access_token ? get_option( 'fts_instagram_custom_api_token' ) : $access_token;
			$fts_instagram_access_token          = 'hashtag' === $type ? $fts_hashtag_check_token_type : $fts_check_token_type;
			$fts_instagram_show_follow_btn       = get_option( 'instagram_show_follow_btn' );
			$fts_instagram_show_follow_btn_where = get_option( 'instagram_show_follow_btn_where' );
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
				$instagram_load_more_text      = get_option( 'instagram_load_more_text' ) ? get_option( 'instagram_load_more_text' ) : __( 'Load More', 'feed-them-social' );
				$instagram_no_more_photos_text = get_option( 'instagram_no_more_photos_text' ) ? get_option( 'instagram_no_more_photos_text' ) : __( 'No More Photos', 'feed-them-social' );
			}

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// $type is the variable coming the shortcode.
				$_REQUEST['fts_dynamic_name'] = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $type );
				// Create Dynamic Class Name.
				$fts_dynamic_class_name = '';
				if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
					$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
				}
			}

			ob_start();

			// New method since Instagram API changes as of April 4th, 2018.
			if ( '' === $access_token ) {
				$fts_instagram_access_token_final = $fts_instagram_access_token;
			} else {
				$fts_instagram_access_token_final = $access_token;
			}

			if ( isset( $_REQUEST['next_url'] ) ) {
				$_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token=' . $fts_instagram_access_token_final, $_REQUEST['next_url'] );
			}
			// URL to get Feeds.
			$debug = 'false';
			if ( 'hashtag' === $type ) {
				// cheezballs = 17843830210018045
				// sleepytime = 17841401899184039
				// Search for the Instagram hashtag ID's.
				// https://developers.facebook.com/docs/instagram-api/reference/ig-hashtag-search
				// https://developers.facebook.com/docs/instagram-api/reference/hashtag
				// These are the 2 types of things you can search for after getting the id of the hashtag from the above 2 links.
				// https://developers.facebook.com/docs/instagram-api/reference/hashtag/recent-media
				// https://developers.facebook.com/docs/instagram-api/reference/hashtag/top-media
				$cache_hashtag_id_array = 'instagram_cache_' . $instagram_id . '_num' . $pics_count . '_search' . $search . '';

				if ( false === $this->fts_check_feed_cache_exists( $cache_hashtag_id_array ) ) {

					$instagram_hashtag_data_array['data'] = 'https://graph.facebook.com/ig_hashtag_search?user_id=' . $instagram_id . '&q=' . $hashtag . '&access_token=' . $fts_instagram_access_token_final;

					$hashtag_response = $this->fts_get_feed_json( $instagram_hashtag_data_array );

					$hashtag_error_check = json_decode( $hashtag_response['data'] );

					foreach ( $hashtag_error_check->data as $ht ) {
						$hashtag_id = $ht->id;
					}

					$this->fts_create_feed_cache( $cache_hashtag_id_array, $hashtag_response );
				} else {
					$response            = $this->fts_get_feed_cache( $cache_hashtag_id_array );
					$hashtag_error_check = json_decode( $response['data'] );

					foreach ( $hashtag_error_check->data as $ht ) {
						$hashtag_id = $ht->id;
					}

					// Used for Testing Only.
					if ( current_user_can( 'administrator' ) && 'true' === $debug ) {
						esc_html_e( 'Array Check Cached', 'feed-them-social' );
						echo '<br/><pre>';
						print_r( $hashtag_error_check );
						echo '</pre>';
					}
				}

				if ( 'recent-media' === $search || '' === $search ) {
					// Now that we have the Instagram ID we can do a search for the endpoint 'Recent Media'.
					$instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : 'https://graph.facebook.com/v4.0/' . $hashtag_id . '/recent_media?user_id=' . $instagram_id . '&fields=media_url{id},caption,comments_count,permalink,like_count,media_type,id,children{media_url}&limit=' . $pics_count . '&access_token=' . $fts_instagram_access_token_final;
				} elseif ( 'top-media' === $search ) {
					// Now that we have the Instagram ID we can do a search for the endpoint 'Top Media'.
					$instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : 'https://graph.facebook.com/' . $hashtag_id . '/top_media?user_id=' . $instagram_id . '&fields=media_url,caption,id,comments_count,permalink,like_count,media_type,children{media_url}&limit=' . $pics_count . '&access_token=' . $fts_instagram_access_token_final;
				}
			} else {
				$instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : 'https://api.instagram.com/v1/users/' . $instagram_id . '/media/recent/?count=' . $pics_count . '&access_token=' . $fts_instagram_access_token_final;

				$instagram_data_array['user_info'] = 'https://api.instagram.com/v1/users/' . $instagram_id . '?access_token=' . $fts_instagram_access_token_final;
			}

			$cache = 'instagram_cache_' . $instagram_id . '_num' . $pics_count . '';
				// First we make sure the feed is not cached already before trying to run the Instagram API.
			if ( false === $this->fts_check_feed_cache_exists( $cache ) ) {
				$response = $this->fts_get_feed_json( $instagram_data_array );

				// Error Check.
				$error_check = json_decode( $response['data'] );

				// Used for Testing Only.
				if ( current_user_can( 'administrator' ) && 'true' === $debug ) {
					esc_html_e( 'Array Check', 'feed-them-social' );
					echo '<br/><pre>';
					print_r( $error_check );
					echo '</pre>';
				}
			}

			// If the feed is cached then we run the cached array to display the feed.
			if ( false !== $this->fts_check_feed_cache_exists( $cache ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
				$response   = $this->fts_get_feed_cache( $cache );
				$insta_data = json_decode( $response['data'] );
				$note       = esc_html( 'Cached', 'feed-them-social' );

			} elseif ( isset( $error_check->error_message ) || isset( $error_check->meta->error_message ) || empty( $error_check ) ) {
				// If the Instagram API array returns any error messages we check for them here and return the corresponding error message!
				if ( current_user_can( 'administrator' ) ) {

					if ( isset( $error_check->error_message ) ) {
						$error = $error_check->error_message;
					} elseif ( isset( $error_check->meta->error_message ) ) {
						$error = $error_check->meta->error_message;
					} else {
						$error = esc_html( 'Please go to the Instagram Options page of our plugin a double check your Instagram ID matches the one used in your shortcode on this page.', 'feed-them-social' );
					}

					return esc_html( 'Feed Them Social (Notice visible to Admin only). Instagram returned:', 'feed-them-social' ) . ' ' . $error;
				} else {
					return;
				}
			} else {
				$insta_data = json_decode( $response['data'] );
				// if Error DON'T Cache.
				if ( ! isset( $error_check->meta->error_message ) && ! isset( $_GET['load_more_ajaxing'] ) || ! isset( $error_check->error_message ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
					$this->fts_create_feed_cache( $cache, $response );
					$note = esc_html( 'Not Cached', 'feed-them-social' );
				}
			}

			$instagram_user_info = ! empty( $response['user_info'] ) ? json_decode( $response['user_info'] ) : '';
			// URL to get Feeds.
			if ( 'hashtag' !== $type && 'location' !== $type ) {
				$username        = $instagram_user_info->data->username;
				$bio             = $instagram_user_info->data->bio;
				$profile_picture = $instagram_user_info->data->profile_picture;
				$full_name       = $instagram_user_info->data->full_name;
				$website         = $instagram_user_info->data->website;
			}

			if ( current_user_can( 'administrator' ) && 'true' === $debug_userinfo ) {
				echo '<pre>';
				print_r( $instagram_user_info );
				echo '</pre>';
			}

			// ->pagination->next_url.
			if ( current_user_can( 'administrator' ) && 'true' === $debug ) {
				echo esc_html( $note ) . '<br/><pre>';
				print_r( $response );
				echo '</pre>';
			}

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// Social Button.
				if ( isset( $profile_picture ) && 'yes' === $profile_wrap ) {
					?>
	<div class="fts-profile-wrap">
					<?php if ( isset( $profile_photo ) && 'yes' === $profile_photo ) { ?>
			<div class="fts-profile-pic">
				<a href="https://www.instagram.com/<?php echo esc_attr( $username ); ?>" target="_blank"><img
							src="<?php echo esc_url( $profile_picture ); ?>" title="<?php echo esc_attr( $username ); ?>"/></a>
			</div>
						<?php
}

if ( isset( $profile_name ) && 'yes' === $profile_name ) {
	?>
			<div class="fts-profile-name-wrap">

				<div class="fts-isnta-full-name"><?php echo esc_html( $full_name ); ?></div>
		<?php
		if ( isset( $instagram_user_info->data->username ) && 'yes' === $fts_instagram_show_follow_btn && 'instagram-follow-above' === $fts_instagram_show_follow_btn_where ) {
			echo '<div class="fts-follow-header-wrap">';
			echo $this->social_follow_button( 'instagram', $instagram_user_info->data->username );
			echo '</div>';
		}
		?>
			</div>
	<?php
}
// $profile stats comes from the shortcode
if ( 'yes' === $profile_stats ) {
	// These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
	$number_posted_pics = isset( $instagram_user_info->data->counts->media ) ? $instagram_user_info->data->counts->media : '';
	// here we add a , for all numbers below 9,999.
	if ( isset( $number_posted_pics ) && $number_posted_pics <= 9999 ) {
		$number_posted_pics = number_format( $number_posted_pics );
	}
	// here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
	if ( isset( $number_posted_pics ) && $number_posted_pics >= 1000000 ) {
		$number_posted_pics = round( ( $number_posted_pics / 1000000 ), 1 ) . 'm';
	}
	// here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands.
	if ( isset( $number_posted_pics ) && $number_posted_pics >= 10000 ) {
		$number_posted_pics = round( ( $number_posted_pics / 1000 ), 1 ) . 'k';
	}

	$number_followed_by = $instagram_user_info->data->counts->followed_by;
	// here we add a , for all numbers below 9,999.
	if ( isset( $number_followed_by ) && $number_followed_by <= 9999 ) {
		$number_followed_by = number_format( $number_followed_by );
	}
	// here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions.
	if ( isset( $number_followed_by ) && $number_followed_by >= 1000000 ) {
		$number_followed_by = round( ( $number_followed_by / 1000000 ), 1 ) . 'm';
	}
	// here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands.
	if ( isset( $number_followed_by ) && $number_followed_by >= 10000 ) {
		$number_followed_by = round( ( $number_followed_by / 1000 ), 1 ) . 'k';
	}

	$number_follows = $instagram_user_info->data->counts->follows;
	// here we add a , for all numbers below 9,999.
	if ( isset( $number_follows ) && $number_follows <= 9999 ) {
		$number_follows = number_format( $number_follows );
	}
	// here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions.
	if ( isset( $number_follows ) && $number_follows >= 1000000 ) {
		$number_follows = round( ( $number_follows / 1000000 ), 1 ) . 'm';
	}
	// here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands.
	if ( isset( $number_follows ) && $number_follows >= 10000 ) {
		$number_follows = round( ( $number_follows / 1000 ), 1 ) . 'k';
	}
	?>
			<div class="fts-profile-stats">
				<div class="fts-insta-posts">
					<span><?php echo esc_html( $number_posted_pics ); ?></span> <?php echo esc_html( 'posts', 'feed-them-social' ); ?></div>
				<div class="fts-insta-followers">
					<span><?php echo esc_html( $number_followed_by ); ?></span> <?php echo esc_html( 'followers', 'feed-them-social' ); ?>
				</div>
				<div class="fts-insta-following">
					<span><?php echo esc_html( $number_follows ); ?></span> <?php echo esc_html( 'following', 'feed-them-social' ); ?></div>
			</div>
			<?php
}

if ( 'yes' === $profile_description ) {
	?>

			<div class="fts-profile-description"><?php echo $this->convert_instagram_description_links( $bio ); ?>
				<a href="<?php echo esc_url( $website ); ?>"><?php echo esc_url( $website ); ?></a></div>

		<?php } ?>

		<div class="fts-clear"></div>

	</div>
					<?php
				} elseif ( isset( $instagram_user_info->data->username ) && 'yes' === $fts_instagram_show_follow_btn && 'instagram-follow-above' === $fts_instagram_show_follow_btn_where ) {
					echo '<div class="instagram-social-btn-top">';
					echo $this->social_follow_button( 'instagram', $instagram_user_info->data->username );
					echo '</div>';
				}

				if ( isset( $scroll_more ) && 'autoscroll' === $scroll_more || ! empty( $height ) ) {
					?>
<div class="fts-instagram-scrollable <?php echo esc_attr( $fts_dynamic_class_name ); ?>instagram" style="overflow:auto;
					<?php
					if ( '' !== $width ) {
						?>
		max-width:
						<?php
						echo esc_attr( $width ) . ';';
					}
					if ( '' !== $height ) {
						?>
		height:
						<?php
						echo esc_attr( $height );
					}
					?>
					">
					<?php
				}
				if ( 'yes' === $super_gallery ) {
					$columns       = isset( $columns ) ? $columns : '';
					$force_columns = isset( $force_columns ) ? $force_columns : '';
					?>
		<div
					<?php
					if ( '' !== $width ) {
						?>
						style="max-width:
						<?php
						echo esc_attr( $width ) . ';"';
					}
					?>
					data-ftsi-columns="<?php echo esc_attr( $columns ); ?>" data-ftsi-force-columns="<?php echo esc_attr( $force_columns ); ?>" data-ftsi-margin="<?php echo esc_attr( $space_between_photos ); ?>" data-ftsi-width="<?php echo esc_attr( $image_size ); ?>" class="
												  <?php
													echo 'fts-instagram-inline-block-centered ' . esc_attr( $fts_dynamic_class_name );
													if ( isset( $popup ) && 'yes' === $popup ) {
														echo ' popup-gallery';
													}
													echo '">';
				} else {
					?>
		<div
					<?php
					if ( '' !== $width ) {
						?>
				style="max-width:
						<?php
						echo esc_attr( $width ) . '; margin:auto;" ';
					}
					?>
						class="fts-instagram
					<?php
					if ( isset( $popup ) && 'yes' === $popup ) {
						echo 'popup-gallery ';
					}
					echo esc_attr( $fts_dynamic_class_name ) . '">';
				}
				$set_zero = 0;
			} // END Make sure it's not ajaxing

			if ( ! isset( $insta_data->data ) ) {
				if ( ! function_exists( 'curl_init' ) ) {
					echo esc_html( 'cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social' ) . '</div>';
				} else {
					echo esc_html( 'To see the Instagram feed you need to add your own API Token to the Instagram Options page of our plugin.', 'feed-them-social' ) . '</div>';
				}
			}
			// echo '<pre style="text-align: left;">asdfasdf';
			// print_r( $insta_data );
			// echo '</pre>';
			foreach ( $insta_data->data as $post_data ) {
				if ( isset( $set_zero ) && $set_zero === $pics_count ) {
					break;
				}

				// Create Instagram Variables
				// tied to date function.
				$feed_type = 'instagram';
				$times     = isset( $post_data->created_time ) ? $post_data->created_time : '';
				// call our function to get the date.
				$instagram_date = $this->fts_custom_date( $times, $feed_type );

				if ( 'hashtag' === $type || 'location' === $type ) {
					$username           = isset( $post_data->user->username ) ? $post_data->user->username : '';
					$profile_picture    = isset( $post_data->user->profile_picture ) ? $post_data->user->profile_picture : '';
					$full_name          = isset( $post_data->user->full_name ) ? $post_data->user->full_name : '';
					$instagram_username = $username;
				} else {

					$instagram_username = $instagram_user_info->data->username;
				}
				$instagram_caption_a_hashtag_title = isset( $post_data->caption ) ? $post_data->caption : '';
				$instagram_caption_a_title         = isset( $post_data->caption->text ) ? $post_data->caption->text : $instagram_caption_a_hashtag_title;
				$instagram_caption_a_title         = htmlspecialchars( $instagram_caption_a_title );
				$instagram_caption                 = $this->convert_instagram_links( $instagram_caption_a_title );

				$instagram_thumb_url                 = isset( $post_data->images->thumbnail->url ) ? $post_data->images->thumbnail->url : '';
				$instagram_lowrez_url                = isset( $post_data->images->standard_resolution->url ) ? $post_data->images->standard_resolution->url : '';
				$instagram_video_standard_resolution = isset( $post_data->videos->standard_resolution->url ) ? $post_data->videos->standard_resolution->url : '';

				if ( isset( $_SERVER['HTTPS'] ) ) {
					$instagram_thumb_url  = str_replace( 'http://', 'https://', $instagram_thumb_url );
					$instagram_lowrez_url = str_replace( 'http://', 'https://', $instagram_lowrez_url );
				}

				// Super Gallery If statement.
				if ( 'yes' === $super_gallery ) {
					?>
			<div class='slicker-instagram-placeholder fts-instagram-wrapper' style='background-image:url(<?php echo esc_url( $this->fts_instagram_image_link( $post_data ) ); ?>);'>
					<?php

					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup ) && 'yes' === $popup ) {
						?>
					<div class="fts-instagram-popup-profile-wrap">
						<div class="fts-profile-pic"><?php $user_type = isset( $hashtag ) ? 'explore/tags/' . $hashtag : $username; ?>
							<a href="https://www.instagram.com/<?php echo esc_html( $user_type ); ?>" target="_blank">
						<?php
						if ( 'user' === $type ) {
							?>
									<img src="<?php echo esc_url( $profile_picture ); ?>" title="<?php echo esc_attr( $username ); ?>"/>
								<?php
						} else {
							?>
									<span class="fts-instagram-icon" style="height:40px; width:40px; line-height:40px; font-size:40px;"></span><?php } ?>
							</a>
						</div>
						<div class="fts-profile-name-wrap">

							<div class="fts-isnta-full-name">
								<a href="https://www.instagram.com/<?php echo esc_html( $user_type ); ?>" target="_blank" style="color: #000;">
								<?php
								if ( 'user' === $type ) {
									echo esc_html( $full_name );
								} else {
									echo esc_html( '#' . $hashtag );
								}
								?>
								</a>
							</div>

							<?php
							if ( isset( $instagram_user_info->data->username ) && 'yes' === $fts_instagram_show_follow_btn && 'instagram-follow-above' === $fts_instagram_show_follow_btn_where ) {
								echo '<div class="fts-follow-header-wrap">';
								echo $this->social_follow_button( 'instagram', $instagram_username );
								echo '</div>';
							}
							?>
						</div>
					</div>
						<?php
						// this is already escaping in the function, re escaping will cause errors.
						 echo $this->fts_instagram_popup_description( $post_data );
					}

					// We need to check the type now because hashtag feeds from facebooks API use all caps now.
					$data_type_image    = isset( $post_data->type ) && 'image' === $post_data->type ? 'image' : 'IMAGE';
					$data_type_video    = isset( $post_data->type ) && 'video' === $post_data->type ? 'video' : 'VIDEO';
					$data_type_carousel = isset( $post_data->type ) && 'carousel' === $post_data->type ? 'carousel' : 'CAROUSEL_ALBUM';
					$data_type_hashtag  = isset( $post_data->media_type ) ? $post_data->media_type : '';
					$data_type          = isset( $post_data->type ) ? $post_data->type : $data_type_hashtag;

					// Check to see if a video is the first child if children are present
					$instagram_api_child_url =  isset( $post_data->carousel_media ) ? $post_data->carousel_media[0]->videos->standard_resolution->url : '';
					//$child url is the fb/instagram api
					$child_url       = isset( $post_data->children ) ? $post_data->children->data[0]->media_url : $instagram_api_child_url;
					$data_type_child = ! empty( $child_url ) && false !== strpos( $child_url, 'mp4' ) ? 'VIDEO' : '';

					?>
				<a href='
					<?php

					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup ) && 'yes' === $popup && $data_type_image === $data_type || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && $data_type_carousel === $data_type && empty( $data_type_child ) ) {

					    print esc_url( $this->fts_instagram_image_link( $post_data ) );

					} elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && $data_type_video === $data_type || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && ! empty( $data_type_child ) && 'VIDEO' === $data_type_child ) {

					    // this statement below does not make sense, check later.
						print $this->fts_instagram_video_link( $post_data ) ? esc_url( $this->fts_instagram_video_link( $post_data ) ) : esc_url( $post_data->permalink . 'media?size=l' );

					} else {
						print esc_url( $this->fts_view_on_instagram_url( $post_data ) );
					}
					$fts_child = isset( $post_data->children ) || isset( $post_data->carousel_media ) ? 'fts-child-media ' : '';
					?>
					' title='<?php print esc_attr( $instagram_caption_a_title ); ?>' target="_blank" class='<?php print $fts_child; ?>fts-instagram-link-target fts-slicker-backg
					<?php
					if ( $data_type_video === $data_type && isset( $popup ) && 'yes' === $popup && ! empty( $this->fts_instagram_video_link( $post_data ) ) || ! empty( $data_type_child ) && 'VIDEO' === $data_type_child && isset( $popup ) && 'yes' === $popup && ! empty( $this->fts_instagram_video_link( $post_data ) ) ) {
						?>
												 fts-instagram-video-link
												<?php
					} else {
						?>
												 fts-instagram-img-link<?php } ?>' style="height:<?php echo esc_attr( $icon_size ); ?> !important; width:<?php echo esc_attr( $icon_size ); ?>; line-height:<?php echo esc_attr( $icon_size ); ?>; font-size:<?php echo esc_attr( $icon_size ); ?>;"><span
							class="fts-instagram-icon"
							style="height:<?php echo esc_attr( $icon_size ); ?>; width:<?php echo esc_attr( $icon_size ); ?>; line-height:<?php echo esc_attr( $icon_size ); ?>; font-size:<?php echo esc_attr( $icon_size ); ?>;"></span></a>


					<?php
					// Must use method where we use the link above which is visible with Instagram icon and then we use the child array below and skip the first child
					// element so we don't have duplicated of the first child. We do this because we need to hide these other links with CSS. We have to have these links here
					// because that is how the magnific popup works in order to get to the next image or video.
					// NOTE: $post_data->childer is FB/Instagram API, $post_data->carousel_media is OG Instagram API.

					if ( isset( $post_data->children ) || isset( $post_data->carousel_media ) ) {

					    $carousel_media = isset( $post_data->children ) ? $post_data->children->data : $post_data->carousel_media;
						?>
						<div class="fts-carousel-image-wrapper"><div class="fts-carousel-image" ></div></div>
						<?php
						foreach ( array_slice( $carousel_media, 1 ) as $child ) {

						    // echo '<pre style="text-align: left;"> wwwqwqwq';
                            // print_r( $child );
                            // echo '</pre>';

                            $url_images = isset( $child->images->standard_resolution->url ) ? $child->images->standard_resolution->url : '';
							$url                   = isset( $child->videos->standard_resolution->url ) ? $child->videos->standard_resolution->url : $url_images;
							$url_final                  = isset( $child->media_url ) ? $child->media_url : $url;
							$data_type_video_child = ! empty( $url_final ) && false != strpos( $url_final, 'mp4' ) ? 'video_media' : 'image_media';
							?>
	<a href='
							<?php
							if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup ) && 'yes' === $popup && 'image_media' === $data_type_video_child ) {
                                print esc_url( $this->fts_instagram_image_link( $child ) );
							} elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && 'video_media' === $data_type_video_child ) {
								print esc_url( $this->fts_instagram_video_link( $child ) );
							} else {

							}
							?>
						' title='<?php print esc_attr( $instagram_caption_a_title ); ?>' target="_blank" id="fts-child-media" class='fts-child-media fts-child-media-hide fts-instagram-link-target fts-slicker-backg
							<?php
							if ( 'video_media' === $data_type_video_child && isset( $popup ) && 'yes' === $popup ) {
								?>
													 fts-instagram-video-link
													<?php
							} else {
								?>
													 fts-instagram-img-link<?php } ?>'></a>



							<?php
						}
					} elseif ( isset( $data_type ) && 'VIDEO' === $data_type || isset( $data_type ) && 'video' === $data_type ) {
						?>
						<div class="fts-instagram-video-image-wrapper"><div class="fts-instagram-video-image"></div></div>
						<?php
					}
					?>













					<div class='slicker-date'>

						<div class="fts-insta-date-popup-grab">
						<?php
						if ( 'no' === $hide_date_likes_comments && 'hashtag' !== $type ) {
							echo esc_html( $instagram_date );
						} else {
							echo '&nbsp;'; }
						?>
						</div>
					</div>
				<div class='slicker-instaG-backg-link'>

					<div class='slicker-instaG-photoshadow'></div>
				</div>
						<?php if ( 'no' === $hide_date_likes_comments ) { ?>
					<div class="fts-insta-likes-comments-grab-popup">
							<?php

							// this is already escaping in the function, re escaping will cause errors.
							echo $this->fts_share_option( $this->fts_view_on_instagram_url( $post_data ), $this->fts_instagram_description( $post_data ) );

							?>
						<div class="fts-instagram-reply-wrap-left">
							<ul class='slicker-heart-comments-wrap'>
								<li class='slicker-instagram-image-likes'><?php echo esc_html( $this->fts_instagram_likes_count( $post_data ) ); ?> </li>
								<li class='slicker-instagram-image-comments'>
									<span class="fts-comment-instagram"></span> <?php echo esc_html( $this->fts_instagram_comments_count( $post_data ) ); ?>
								</li>
							</ul>
						</div>
					</div>
				<?php } ?>
			</div>
					<?php
				} else {
					// Classic Gallery If statement.
					?>
			<div class='instagram-placeholder fts-instagram-wrapper' style='width:150px;'>
						<?php
						if ( isset( $popup ) && 'yes' === $popup ) {
							print '<div class="fts-backg"></div>';
						} else {
							?>
					<a class='fts-backg' target='_blank' href='<?php echo esc_url( $this->fts_view_on_instagram_url( $post_data ) ); ?>'></a><?php }; ?>
				<div class='date slicker-date'>
					<div class="fts-insta-date-popup-grab"><?php echo esc_html( $instagram_date ); ?></div>
				</div>


						<?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup ) && 'yes' === $popup ) { ?>


					<div class="fts-instagram-popup-profile-wrap">
						<div class="fts-profile-pic">
							<a href="https://www.instagram.com/<?php echo esc_attr( $username ); ?>" target="_blank"><img
										src="<?php echo esc_attr( $profile_picture ); ?>" title="<?php echo esc_attr( $username ); ?>"/></a>
						</div>

						<div class="fts-profile-name-wrap">

							<div class="fts-isnta-full-name"><?php echo esc_attr( $full_name ); ?></div>
							<?php
							if ( isset( $instagram_user_info->data->username ) && 'yes' === $fts_instagram_show_follow_btn && 'instagram-follow-above' === $fts_instagram_show_follow_btn_where ) {
								echo '<div class="fts-follow-header-wrap">';
								echo $this->social_follow_button( 'instagram', $instagram_user_info->data->username );
								echo '</div>';
							}
							?>
						</div>
					</div>

							<?php
							// caption for our popup.
							echo $this->fts_instagram_popup_description( $post_data );
							?>
				<?php } ?>

				<a href="
					<?php
					// We need to check the type now because hashtag feeds from facebooks API use all caps now.
					$data_type_image    = isset( $post_data->type ) && 'image' === $post_data->type ? : 'IMAGE';
					$data_type_video    = isset( $post_data->type ) && 'video' === $post_data->type ? : 'VIDEO';
					$data_type_carousel = isset( $post_data->type ) && 'carousel' === $post_data->type ? : 'CAROUSEL';
					$data_type_hashtag  = isset( $post_data->media_type ) ? $post_data->media_type : '';
					$data_type          = isset( $post_data->type ) ? $post_data->type : $data_type_hashtag;
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup ) && 'yes' === $popup && ( $data_type_image === $data_type || $data_type_carousel === $data_type ) ) {
						print esc_url( $this->fts_instagram_image_link( $post_data ) );
					} elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $popup && $data_type_video === $data_type ) {
						print esc_url( $this->fts_instagram_video_link( $post_data ) );
					} else {
						print esc_url( $this->fts_view_on_instagram_url( $post_data ) );
					}
					?>
					" class='fts-instagram-link-target instaG-backg-link
					<?php
					if ( 'video' === $post_data->type ) {
						?>
						fts-instagram-video-link
						<?php
					} else {
						?>
	fts-instagram-img-link<?php } ?>' target='_blank' title='<?php echo esc_attr( $instagram_caption_a_title ); ?>'>
					<img src="<?php echo esc_url( $instagram_thumb_url ); ?>" class="instagram-image"/>
					<div class='instaG-photoshadow'></div>
				</a>
				<div class="fts-insta-likes-comments-grab-popup">
							<?php echo $this->fts_instagram_likes_comments_wrap( $post_data ); ?>
				</div>
			</div>
					<?php
				}
				if ( isset( $set_zero ) ) {
					$set_zero++;
				}
			}

			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! empty( $scroll_more ) ) {
				// ******************
				// Load More BUTTON Start
				// Check to see if the next isset for the hashtag feed. If so then pass it down so it's used.
				// ******************
					$next_hashtag_url = isset( $insta_data->paging->next ) ? $insta_data->paging->next : '';
					$next_url         = isset( $insta_data->pagination->next_url ) ? $insta_data->pagination->next_url : $next_hashtag_url;
					// fb api uses limit for the post count and instagram api uses count.
					$the_count = 'hashtag' === $type ? 'limit' : 'count';
					// we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of posts.
					$_REQUEST['next_url'] = '' !== $loadmore_count ? str_replace( "'.$the_count.'=$pics_count", "'.$the_count.'=$loadmore_count", $next_url ) : $next_url;
					$access_token         = 'access_token=' . $fts_instagram_access_token_final;
					$_REQUEST['next_url'] = str_replace( $access_token, 'access_token=XXX', $next_url );

				?>
		<script>var nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_url_raw( $_REQUEST['next_url'] ); ?>";</script>
				<?php
				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && ! empty( $loadmore ) ) {
					$fts_dynamic_name = sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
					$time             = time();
					$nonce            = wp_create_nonce( $time . 'load-more-nonce' );
					?>
			<script>jQuery(document).ready(function () {
					<?php
					// $scroll_more = load_more_posts_style shortcode att.
					if ( 'autoscroll' === $scroll_more ) { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode.
						?>
					jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>instagram").bind("scroll", function () {
						if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
							<?php
					} else { // this is where we do CLICK function to LOADMORE if = button in shortcode.
						?>
							jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").click(function () {
					<?php } ?>
								jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
								var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
								console.log(button);

								var feed_name = "fts_instagram";
								var loadmore_count = "pics_count=<?php echo esc_js( $loadmore_count ); ?>";
								var feed_attributes = <?php echo wp_json_encode( $atts ); ?>;
								var yes_ajax = "yes";
								var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ); ?>";
								var fts_security = "<?php echo esc_js( $nonce ); ?>";
								var fts_time = "<?php echo esc_js( $time ); ?>";
								jQuery.ajax({
									data: {
										action: "my_fts_fb_load_more",
										next_url: nextURL_<?php echo esc_js( $fts_dynamic_name ); ?>,
										fts_dynamic_name: fts_d_name,
										load_more_ajaxing: yes_ajax,
										fts_security: fts_security,
										fts_time: fts_time,
										feed_name: feed_name,
										loadmore_count: loadmore_count,
										feed_attributes: feed_attributes
									},
									type: 'GET',
									url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
									success: function (data) {
										console.log('Well Done and got this from sever: ' + data);
										jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').append(data).filter('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').html();
										if (!nextURL_<?php echo esc_js( sanitize_key( $_REQUEST['fts_dynamic_name'] ) ); ?> || nextURL_<?php echo esc_js( sanitize_key( $_REQUEST['fts_dynamic_name'] ) ); ?> === 'no more') {
											jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo esc_js( $instagram_no_more_photos_text ); ?></div>');
											jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').removeAttr('id');
											jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>instagram").unbind('scroll');
										}
										jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php echo esc_js( $instagram_load_more_text ); ?>');
										jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');
										jQuery.fn.ftsShare(); // Reload the share each funcion otherwise you can't open share option
										jQuery.fn.slickInstagramPopUpFunction(); // Reload this function again otherwise the popup won't work correctly for the newly loaded items
										if (typeof outputSRmargin === "function") {
											outputSRmargin(document.querySelector('#margin').value)
										} // Reload our margin for the demo
										slickremixImageResizing(); // Reload our imagesizing function so the images show up proper
									}
								}); // end of ajax()
								return false;
								<?php
								// string $scroll_more is at top of this js script. exception for scroll option closing tag.
								if ( 'autoscroll' === $scroll_more ) {
									?>
							}; // end of scroll ajax load
							<?php } ?>
						}
					); // end of document.ready
				}); // end of form.submit </script>
					<?php
				}//End Check.
			}
			// main closing div not included in ajax check so we can close the wrap at all times.
			print '</div>'; // closing main div for photos and scroll wrap.

			// Make sure it's not ajaxing.
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
				$fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );
				// this div returns outputs our ajax request via jquery append html from above.
				print '<div class="fts-clear"></div>';
				print '<div id="output_' . esc_attr( $fts_dynamic_name ) . '"></div>';
				if ( ! empty( $scroll_more ) && 'autoscroll' === $scroll_more ) {
					print '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Instagram</div>';
				}
			}
			?>
			<?php
			// only show this script if the height option is set to a number.
			if ( ! empty( $height ) && 'auto' !== $height ) {
				?>
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
			<?php } //end $height !== 'auto' && empty($height) == NULL. ?>
			<?php
			if ( ! empty( $scroll_more ) && 'autoscroll' === $scroll_more || ! empty( $height ) ) {
				print '</div>'; // closing height div for scrollable feeds.
			}

			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					print '<div class="fts-clear"></div>';
					if ( ! empty( $scroll_more ) && 'button' === $scroll_more ) {

						print '<div class="fts-instagram-load-more-wrapper">';
						print '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" style="';
						if ( '' !== $loadmore_btn_maxwidth ) {
							print 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
						}
						$loadmore_btn_margin = isset( $loadmore_btn_margin ) ? $loadmore_btn_margin : '10px';
						print 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $instagram_load_more_text ) . '</div>';
						print '</div>';

					}
				}//End Check.
				unset( $_REQUEST['next_url'] );
			}
			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// Social Button.
				if ( isset( $instagram_user_info->data->username ) && 'yes' === $fts_instagram_show_follow_btn && 'instagram-follow-below' === $fts_instagram_show_follow_btn_where ) {
					echo '<div class="instagram-social-btn-bottom">';
					echo $this->social_follow_button( 'instagram', $instagram_user_info->data->username );
					echo '</div>';
				}
			}
		} // end nonce

		return ob_get_clean();
	}
	/**
	 * Random String
	 *
	 * Create a random string
	 *
	 * @param string $length Length.
	 * @return mixed
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

}//end class

?>
