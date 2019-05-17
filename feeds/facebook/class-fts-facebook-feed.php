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
	public function __construct() {
		add_shortcode( 'fts_facebook_group', array( $this, 'fts_fb_func' ) );
		add_shortcode( 'fts_facebook_page', array( $this, 'fts_fb_func' ) );
		add_shortcode( 'fts_facebook_event', array( $this, 'fts_fb_func' ) );
		add_shortcode( 'fts_facebook', array( $this, 'fts_fb_func' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fts_fb_head' ) );
	}

	/**
	 * FTS FB Head
	 *
	 * Add Styles and Scripts functions.
	 *
	 * @since 1.9.6
	 */
	public function fts_fb_head() {
		wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css' ), array(), FTS_CURRENT_VERSION, false );

		if ( is_plugin_active( 'feed-them-social/feed-them.php' ) && is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			wp_enqueue_script( 'fts-feeds', plugins_url( 'feed-them-carousel-premium/feeds/js/jquery.cycle2.js' ), array(), FTS_CURRENT_VERSION, false );
		}
	}

	/**
	 * Date Sort
	 *
	 * Date sort option for multiple feeds in a shortcode.
	 *
	 * @param string $a First Date.
	 * @param string $b Second Date.
	 * @return false|int
	 * @since 1.9.6
	 */
	public function dateSort( $a, $b ) {
		$date_a = strtotime( $a->created_time );
		$date_b = strtotime( $b->created_time );
		return ( $date_b - $date_a );
	}


	/**
	 * FTS FB Func
	 *
	 * Display Facebook Feed.
	 *
	 * @param string $atts Shortcode attributes.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_fb_func( $atts ) {
		// masonry snippet in fts-global.
		wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		$developer_mode = 'on';
		// Make sure everything is reset.
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		// Eventually add premium page file.
		if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {

			$fts_facebook_reviews = new FTS_Facebook_Reviews();
			$review_atts          = $fts_facebook_reviews->shortcode_attributes();
			$fb_shortcode         = shortcode_atts( $review_atts, $atts );
			// Load up some scripts for popup.
			$this->load_popup_scripts( $fb_shortcode );
		} elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/facebook/facebook-premium-feed.php';
			// Doing this to phase out the invalid snake case.
			$fb_shortcode = $FB_Shortcode;
			// Load up some scripts for popup.
			$this->load_popup_scripts( $fb_shortcode );
		} elseif ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			// Doing this to phase out the invalid snake case.
			$fb_shortcode = $FB_Shortcode;
			$fb_shortcode = shortcode_atts(
				array(
					'id'                       => '',
					'type'                     => '',
					'posts'                    => '',
					'posts_displayed'          => '',
					'height'                   => '',
					'album_id'                 => '',
					'image_width'              => '',
					'image_height'             => '',
					'space_between_photos'     => '',
					'hide_date_likes_comments' => '',
					'center_container'         => '',
					'image_stack_animation'    => '',
					'image_position_lr'        => '',
					'image_position_top'       => '',
					'hide_comments_popup'      => '',
					// only works with combined FB streams otherwise you need the premium version.
					'popup'                    => '',
					'words'                    => '',
					'grid'                     => '',
					'colmn_width'              => '',
					'space_between_posts'      => '',
					// new show media on top options.
					'show_media'               => '',
					'show_date'                => '',
					'show_name'                => '',
				//	'access_token'             => '',

				),
				$atts
			);
			if ( null === $fb_shortcode['posts'] ) {
				$fb_shortcode['posts'] = '6';
			}
		} else {
			$fb_shortcode = shortcode_atts(
				array(
					'id'                       => '',
					'type'                     => '',
					'posts'                    => '',
					'description'              => 'yes',
					'posts_displayed'          => '',
					'height'                   => '',
					'album_id'                 => '',
					'image_width'              => '',
					'image_height'             => '',
					'space_between_photos'     => '',
					'hide_date_likes_comments' => '',
					'center_container'         => '',
					'image_stack_animation'    => '',
					'image_position_lr'        => '',
					'image_position_top'       => '',
					'hide_comments_popup'      => '',
				//	'access_token'             => '',

				),
				$atts
			);
			if ( null === $fb_shortcode['posts'] ) {
				$fb_shortcode['posts'] = '6';
			}
		}

		if ( 'album_videos' === $fb_shortcode['type'] ) {
			$fb_shortcode['type']        = 'album_photos';
			$fb_shortcode['video_album'] = 'yes';
			$fb_shortcode['album_id']    = 'photo_stream';
			if ( isset( $fb_shortcode['loadmore_btn_maxwidth'] ) && ! empty( $fb_shortcode['loadmore_btn_maxwidth'] ) ) {
				$fb_shortcode['loadmore'] = 'button';
			}
		}

		if ( ! is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && $fb_shortcode['posts'] > '6' ) {
			$fb_shortcode['posts'] = '6';
		}

		// Get Access Token.
	//	$access_token = isset( $fb_shortcode['access_token'] ) ? $fb_shortcode['access_token'] : '';
	//	if ( ! empty( $access_token ) ) {
	//		$access_token = $fb_shortcode['access_token'];
	//	} else {
			$access_token = $this->get_access_token();
	//	}

		// UserName?.
		if ( ! $fb_shortcode['id'] ) {
			return 'Please enter a username for this feed.';
		}
		if ( 'reviews' === $fb_shortcode['type'] && ! is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
			return '<div style="clear:both; padding:15px 0;">You must have FTS Facebook Reviews extension active to see this feed.</div>';
		}

		$type = isset( $fb_shortcode['type'] ) ? $fb_shortcode['type'] : '';
		if ( 'group' === $type || 'page' === $type || 'event' === $type ) {

			// EMPTY FACEBOOK POSTS OFFSET AND COUNT.
			// Option Now Being Removed from here and the Facebook Settings Page.
			// Setting it to blank so no matter what it will never error get_option('fb_count_offset');.
			$fb_count_offset = '';

			// View Link.
			$fts_view_fb_link = '';
			// Get Cache Name.
			$fb_cache_name = '';
			// Get language.
			$language = '';

			// Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
			$response2 = $this->get_facebook_feed_response( $fb_shortcode, $fb_cache_name, $access_token, $language );

			// Test to see if the re-sort date option is working from function above.
			// print $this->dateSort;.
			$feed_data_check = json_decode( $response2['feed_data'] );

			// SHOW THE REGULAR FEEDS PRINT_R
			// echo '<pre>';
			// print_r($feed_data_check);
			// echo '</pre>';
			// $idNew = array();
			// $idNew = explode(',', $fb_shortcode['id']);
			// Testing options before foreach loop
			// $idNew = 'tonyhawk';
			// print_r($feed_data_check->$idNew->data);.
			if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
				$fts_count_ids = substr_count( $fb_shortcode['id'], ',' );
			} else {
				$fts_count_ids = '';
			}

			if ( isset( $feed_data_check->data ) ) {
				if ( $fts_count_ids >= 1 && 'reviews' !== $fb_shortcode['type'] ) {
					$fts_list_arrays = array();
					foreach ( $feed_data_check as $feed_data_name ) {

						if ( isset( $feed_data_name->data ) ) {
							$fts_list_arrays = array_merge_recursive( $fts_list_arrays, $feed_data_name->data );
						}
						// var_dump( $fts_list_arrays[$i]);.
					}
					$merged_array['data'] = $fts_list_arrays;
					$feed_data_check      = (object) $merged_array;
				}

				// Test the created dataes are being sorted properly
				// foreach($merged_array['data'] as $newSort) {
				// print date("jS F, Y", strtotime($newSort->created_time));
				// print '<br/>';
				// }.
				$set_zero = 0;
				foreach ( $feed_data_check->data as $post_count ) {

					$fb_message     = isset( $post_count->message ) ? $post_count->message : '';
					$fb_story       = isset( $post_count->story ) ? $post_count->story : '';
					$fb_type        = isset( $post_count->type ) ? $post_count->type : '';
					$fb_status_type = isset( $post_count->status_type ) ? $post_count->status_type : '';

					// This is the method to skip empty posts or posts that are simply about changing settings or other non important post types
					// We will count all the ones that are like this and add that number to the output of posts to offset the posts we are filtering out. Line 278 needs the same treatment of if options.
					if ( 'status' === $fb_type && empty( $fb_message ) && empty( $fb_story ) || 'event' === $fb_type || 'event' === $fb_type && false !== strpos( $fb_story, 'shared their event' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'changed the name of the event to' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'changed the privacy setting' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'an admin of the group' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'created the group' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'added an event' ) || 'event' === $fb_type && false !== strpos( $fb_story, 'added an event' ) ) {
						$set_zero++;
					} elseif ( '0' === $feed_data_check->data ) {
						// If more than the 5 posts(default in free) or the post= from shortcode is set to the amount of posts that are being filtered above we will add 7 to the post count to try and get at some posts.
						// This will only happen for Page and Group feeds.
						$set_zero = '7';
					}
				}// END POST foreach.

				// Result of the foreach loop above minus the empty posts and offset by those posts the actual number of posts entered is shown
				// $fb_shortcode['posts'] = $result;.
				if ( ! empty( $fb_count_offset ) ) {
					$set_zero              = $fb_count_offset;
					$unset_count           = $fb_shortcode['posts'] + $set_zero;
					$fb_shortcode['posts'] = $unset_count;
				} else {
					$unset_count           = $fb_shortcode['posts'] + $set_zero;
					$fb_shortcode['posts'] = $unset_count;
				}

				// SHOW THE $feed_data_check PRINT_R
				// echo '<pre>';
				// print_r($feed_data_check);
				// echo '</pre>, ';.
			}
			// END.
		}

		ob_start();
		// Uncomment these for testing purposes to see the actual count and the offset count
		// print   $set_zero;
		// print   $fb_shortcode['posts'];
		// print   $fb_type;
		// View Link.
		$fts_view_fb_link = $this->get_view_link( $fb_shortcode );
		// Get Cache Name.
		$fb_cache_name = $this->get_fb_cache_name( $fb_shortcode );
		// Get language.
		$language = $this->get_language( $fb_shortcode );
		if ( 'reviews' !== $fb_shortcode['type'] ) {
			// Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
			$response = $this->get_facebook_feed_response( $fb_shortcode, $fb_cache_name, $access_token, $language );
			// Json decode data and build it from cache or response.
			$page_data = json_decode( $response['page_data'] );
			$feed_data = json_decode( $response['feed_data'] );
		}

		if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && true == get_option( 'fts_facebook_custom_api_token_biz' ) && 'reviews' === $fb_shortcode['type'] ||
			is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && ! empty( $fb_shortcode['token'] ) && 'reviews' === $fb_shortcode['type'] ||
			is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && ! empty( $fb_shortcode['access_token'] ) && 'reviews' === $fb_shortcode['type'] ) {

			if ( 'yes' === $fb_shortcode['remove_reviews_no_description'] && ! isset( $_GET['load_more_ajaxing'] ) ) {

				$fts_facebook_reviews = new FTS_Facebook_Reviews();
				$no_description_count = $fts_facebook_reviews->review_count_check( $fb_shortcode );

				// testing purposes
				// print ''. $no_description_count - $fb_shortcode['posts'] .' = The amount of posts with no review text.';
				// this count includes our original posts count + the amount of posts we found with no description.
				$fb_shortcode['posts'] = $no_description_count;
			}
			if ( ! empty( $fb_shortcode['token'] ) ) {
				$biz_access_token = $fb_shortcode['token'];
			} elseif ( ! empty( $fb_shortcode['access_token'] ) ) {
				$biz_access_token = $fb_shortcode['access_token'];
			} else {
				$biz_access_token = get_option( 'fts_facebook_custom_api_token_biz' );
			}

			// Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
			$response = $this->get_facebook_feed_response( $fb_shortcode, $fb_cache_name, $biz_access_token, $language );

			$feed_data = json_decode( $response['feed_data'] );

            $feed_data = (object) $feed_data;
            // Add Feed Type to post array.

			// SHOW THE REVIEWS FEED PRINT_R
			// echo '<pre>';
			// print_r($feed_data );
			// echo '</pre>';


			if ( 'yes' === $fb_shortcode['remove_reviews_no_description'] ) {
				// $no_description_count2 = 0;.
				foreach ( $feed_data->data as $k => $v ) {
					if ( ! isset( $v->review_text ) ) {
						// print $v->reviewer->name . ' (Key# ' . $k . ') : Now Unset from array<br/>';.
						unset( $feed_data->data[ $k ] );
						// $no_description_count2++;.
					}
				}
			}
			$ratings_data = json_decode( $response['ratings_data'] );

			// SHOW THE REVIEWS RATING INFO PRINT_R
			// echo '<pre>';
			// print_r($ratings_data );
			// echo '</pre>';.

            // Add fts_profile_pic_url to the array so we can show profile photos for reviews and comments in popup
            foreach ( $feed_data->data as $post_array ) {

                $the_image = 'https://graph.facebook.com/'.$post_array->reviewer->id.'/picture?redirect=false&access_token='.$biz_access_token.'';

                $profile_pic_response = wp_remote_get($the_image );
                $profile_pic_data = wp_remote_retrieve_body( $profile_pic_response );
                $profile_pic_output = json_decode( $profile_pic_data );

                  //  echo '<pre>';
                  //  print_r($profile_pic_output->data->url);
                  //  echo '</pre>';

                $post_array->fts_profile_pic_url = $profile_pic_output->data->url;
            }
		}

		if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
			$fts_count_ids = substr_count( $fb_shortcode['id'], ',' );
		} else {
			$fts_count_ids = '';
		}

		if ( $fts_count_ids >= 1 && 'reviews' !== $fb_shortcode['type'] ) {

			$fts_list_arrays = array();
			foreach ( $feed_data as $feed_data_name ) {

				$fts_list_arrays = array_merge_recursive( $fts_list_arrays, $feed_data_name->data );
				// var_dump( $fts_list_arrays[$i]);.
			}
			// Sort the array using the call back function.
			usort( $fts_list_arrays, array( $this, 'dateSort' ) );

			$merged_array['data'] = $fts_list_arrays;
			$feed_data            = (object) $merged_array;
		}
		// SHOW THE REGULAR FEEDS PRINT_R (WORKS FOR VIDEOS TOO)
		// echo '<pre>';
		// print_r($feed_data );
		// echo '</pre>';

		// If No Response or Error then return.
		if ( is_array( $response ) && isset( $response[0] ) && isset( $response[1] ) && false === $response[0] ) {
			return $response[1];
		}

		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			// Make sure it's not ajaxing and we will allow the omition of certain album covers from the list by using omit_album_covers=0,1,2,3 in the shortcode.
			if ( ! isset( $_GET['load_more_ajaxing'] ) && 'albums' === $fb_shortcode['type'] ) {

				// omit_album_covers=0,1,2,3 for example.
				$omit_album_covers     = $fb_shortcode['omit_album_covers'];
				$omit_album_covers_new = array();
				$omit_album_covers_new = explode( ',', $omit_album_covers );
				foreach ( $feed_data->data as $post_data ) {
					foreach ( $omit_album_covers_new as $omit ) {
						unset( $feed_data->data[ $omit ] );
					}
				}
			}
		}
		// Reviews Rating Filter.
		if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && 'reviews' === $fb_shortcode['type'] ) {
			foreach ( $feed_data->data as $key => $post_data ) {
				// we are not going to show the unrecommended reviews in the feed at this point, no options in our plugin srl 8-28-18.
				if ( isset( $post_data->rating ) && $post_data->rating < $fb_shortcode['reviews_type_to_show'] || isset( $post_data->recommendation_type ) && 'negative' === $post_data->recommendation_type ) {
					unset( $feed_data->data[ $key ] );
				}
			}
		}

		// Make sure it's not ajaxing.
		if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
			// Get Response (AKA Page & Feed Information).
			$_REQUEST['fts_dynamic_name'] = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $fb_shortcode['type'] );
			// Create Dynamic Class Name.
			$fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
			// SOCIAL BUTTON.
			if ( ! $fts_count_ids >= 1 ) {
				$this->fb_social_btn_placement( $fb_shortcode, $access_token, 'fb-like-top-above-title' );
			}

			if ( 'reviews' !== $fb_shortcode['type'] ) {
				$page_data->description = isset( $page_data->description ) ? $page_data->description : '';
				$page_data->name        = isset( $page_data->name ) ? $page_data->name : '';
			}
			// fts-fb-header-wrapper (for grid).
			echo isset( $fb_shortcode['grid'] ) && 'yes' !== $fb_shortcode['grid'] && 'album_photos' !== $fb_shortcode['type'] && 'albums' !== $fb_shortcode['type'] ? '<div class="fts-fb-header-wrapper">' : '';

			// Header.
			echo '<div class="fts-jal-fb-header">';

			if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && isset( $fb_shortcode['overall_rating'] ) && 'yes' === $fb_shortcode['overall_rating'] ) {

				// echo $this->get_facebook_overall_rating_response($fb_shortcode, $fb_cache_name, $access_token);.
				$fb_reviews_overall_rating_of_5_stars_text        = get_option( 'fb_reviews_overall_rating_of_5_stars_text' );
				$fb_reviews_overall_rating_of_5_stars_text        = ! empty( $fb_reviews_overall_rating_of_5_stars_text ) ? ' ' . $fb_reviews_overall_rating_of_5_stars_text : ' of 5 stars';
				$fb_reviews_overall_rating_reviews_text           = get_option( 'fb_reviews_overall_rating_reviews_text' );
				$fb_reviews_overall_rating_reviews_text           = ! empty( $fb_reviews_overall_rating_reviews_text ) ? ' ' . $fb_reviews_overall_rating_reviews_text : ' reviews';
				$fb_reviews_overall_rating_background_border_hide = get_option( 'fb_reviews_overall_rating_background_border_hide' );
				$fb_reviews_overall_rating_background_border_hide = ! empty( $fb_reviews_overall_rating_background_border_hide ) && 'yes' === $fb_reviews_overall_rating_background_border_hide ? ' fts-review-details-master-wrap-no-background-or-border' : '';
				echo '<div class="fts-review-details-master-wrap' . esc_attr( $fb_reviews_overall_rating_background_border_hide ) . '" itemscope itemtype="http://schema.org/CreativeWork"><i class="fts-review-star">' . esc_html( $ratings_data->overall_star_rating ) . ' &#9733;</i>';
				echo '<div class="fts-review-details-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="fts-review-details"><span itemprop="ratingValue">' . esc_html( $ratings_data->overall_star_rating ) . '</span>' . esc_html( $fb_reviews_overall_rating_of_5_stars_text ) . '</div>';
				echo '<div class="fts-review-details-count"><span itemprop="reviewCount">' . esc_html( $ratings_data->rating_count ) . '</span>' . esc_html( $fb_reviews_overall_rating_reviews_text ) . '</div></div></div>';

			}
			if ( 'reviews' !== $fb_shortcode['type'] ) {
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
					// echo our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
					$fts_align_title = isset( $fb_shortcode['title_align'] ) && '' !== $fb_shortcode['title_align'] ? 'style="text-align:' . $fb_shortcode['title_align'] . ';"' : '';
					echo isset( $fb_shortcode['title'] ) && 'no' !== $fb_shortcode['title'] ? '<h1 ' . esc_attr( $fts_align_title ) . '><a href="' . esc_url( $fts_view_fb_link ) . '" target="_blank">' . esc_html( $page_data->name ) . '</a></h1>' : '';

				} else {
					// echo our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
					echo '<h1><a href="' . esc_url( $fts_view_fb_link ) . '" target="_blank">' . esc_html( $page_data->name ) . '</a></h1>';
				}
				// Description.
				echo isset( $fb_shortcode['description'] ) && 'no' !== $fb_shortcode['description'] ? '<div class="fts-jal-fb-group-header-desc">' . wp_kses(
					$this->fts_facebook_tag_filter( $page_data->description ),
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</div>' : '';
			}
			// END Header.
			echo '</div>';
			// Close fts-fb-header-wrapper.
			echo isset( $fb_shortcode['grid'] ) && 'yes' !== $fb_shortcode['grid'] && 'album_photos' !== $fb_shortcode['type'] && 'albums' !== $fb_shortcode['type'] ? '</div>' : '';
		} //End check.

		// SOCIAL BUTTON.
		if ( ! $fts_count_ids >= 1 ) {
			$this->fb_social_btn_placement( $fb_shortcode, $access_token, 'fb-like-top-below-title' );
		}

		// Feed Header.
		// Make sure it's not ajaxing.
		if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

			$fts_mashup_media_top      = isset( $fb_shortcode['show_media'] ) && 'top' === $fb_shortcode['show_media'] ? 'fts-mashup-media-top ' : '';
			$fts_mashup_show_name      = isset( $fb_shortcode['show_name'] ) && 'no' === $fb_shortcode['show_name'] ? ' fts-mashup-hide-name ' : '';
			$fts_mashup_show_date      = isset( $fb_shortcode['show_date'] ) && 'no' === $fb_shortcode['show_date'] ? ' fts-mashup-hide-date ' : '';
			$fts_mashup_show_thumbnail = isset( $fb_shortcode['show_thumbnail'] ) && 'no' === $fb_shortcode['show_thumbnail'] ? ' fts-mashup-hide-thumbnail ' : '';

			if ( ! isset( $fb_type ) && 'albums' === $fb_shortcode['type'] || ! isset( $fb_type ) && 'album_photos' === $fb_shortcode['type'] || isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {

				if ( isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
					echo '';
				} elseif ( isset( $fb_shortcode['slider'] ) && 'yes' !== $fb_shortcode['slider'] && 'yes' === $fb_shortcode['image_stack_animation'] || isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] || isset( $fb_shortcode['image_stack_animation'] ) && 'yes' === $fb_shortcode['image_stack_animation'] ) {
					wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
					echo '<script>';
					echo 'jQuery(window).load(function(){';
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").masonry({';
					echo 'itemSelector: ".fts-jal-single-fb-post"';
					echo '});';
					echo '});';
					echo '</script>';
				}

				if ( ! isset( $fb_type ) && 'albums' === $fb_shortcode['type'] || ! isset( $fb_type ) && 'album_photos' === $fb_shortcode['type'] && ! isset( $fb_type ) && ! isset( $fb_shortcode['slider'] ) || ! isset( $fb_type ) && 'album_photos' === $fb_shortcode['type'] && ! isset( $fb_type ) && isset( $fb_shortcode['slider'] ) && 'yes' !== $fb_shortcode['slider'] ) {
					echo '<div class="fts-slicker-facebook-photos fts-slicker-facebook-albums' . ( isset( $fb_shortcode['video_album'] ) && $fb_shortcode['video_album'] && 'yes' === $fb_shortcode['video_album'] ? ' popup-video-gallery-fb' : '' ) . ( isset( $fb_shortcode['image_stack_animation'] ) && 'yes' === $fb_shortcode['image_stack_animation'] ? ' masonry js-masonry' : '' ) . ( isset( $fb_shortcode['images_align'] ) && $fb_shortcode['images_align'] ? ' popup-video-gallery-align-' . esc_attr( $fb_shortcode['images_align'] ) : '' ) . ' popup-gallery-fb ' . esc_attr( $fts_dynamic_class_name ) . '"';
					if ( 'yes' === $fb_shortcode['image_stack_animation'] ) {
						echo 'data-masonry-options=\'{ "isFitWidth": ' . ( 'no' === $fb_shortcode['center_container'] ? 'false' : 'true' ) . ' ' . ( 'no' === $fb_shortcode['image_stack_animation'] ? ', "transitionDuration": 0' : '' ) . '}\' style="margin:auto;"';
					}
					echo '>';
				} elseif (
					// slideshow scrollHorz or carousel.
					! isset( $fb_type ) && isset( $fb_shortcode['slider'] ) && 'yes' === $fb_shortcode['slider'] ) {
					$fts_cycle_type = isset( $fb_shortcode['scrollhorz_or_carousel'] ) ? $fb_shortcode['scrollhorz_or_carousel'] : 'scrollHorz';

					if ( isset( $fts_cycle_type ) && 'carousel' === $fts_cycle_type ) {
						$fts_cycle_slideshow = 'slideshow';
					} else {
						$fts_cycle_slideshow = 'cycle-slideshow';
					}
					echo '';

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
					// numbers_below_feed.
					$fts_controls_bar_color  = ! empty( $fb_shortcode['slider_controls_bar_color'] ) ? $fb_shortcode['slider_controls_bar_color'] : '#000';
					$fts_controls_text_color = ! empty( $fb_shortcode['slider_controls_text_color'] ) ? $fb_shortcode['slider_controls_text_color'] : '#ddd';
					if ( isset( $fb_shortcode['slider_controls_width'] ) && 'carousel' !== $fb_shortcode['scrollhorz_or_carousel'] ) {
						$max_width_set = isset( $fb_shortcode['image_width'] ) && '' !== $fb_shortcode['image_width'] && 'carousel' !== $fb_shortcode['scrollhorz_or_carousel'] ? $fb_shortcode['image_width'] : '100%';
					} else {
						$max_width_set = isset( $fb_shortcode['slider_controls_width'] ) && '' !== $fb_shortcode['slider_controls_width'] && 'carousel' === $fb_shortcode['scrollhorz_or_carousel'] ? $fb_shortcode['slider_controls_width'] : '100%';
					}
					if (
						isset( $fb_shortcode['slider_controls'] ) && 'dots_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'arrows_above_feed' === $fb_shortcode['slider_controls'] ||
						isset( $fb_shortcode['slider_controls'] ) && 'numbers_above_feed' === $fb_shortcode['slider_controls']
					) {

						// Slider Dots Wrapper.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls']
						) {

							echo '<div class="fts-slider-icons-center fts-pager-option-dots-only-top" style="margin:auto; width:100%;max-width:' . esc_attr( $max_width_set . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color ) . '"><div class="fts-pager-option fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '"></div></div>';
						}

						// Slider Arrow and Numbers Wrapper.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'numbers_above_feed' === $fb_shortcode['slider_controls']
						) {
							echo '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . esc_attr( $max_width_set . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color ) . '">';
						}

						// Previous Arrow.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_above_feed' === $fb_shortcode['slider_controls']
						) {
							echo '<span class="fts-prevControl-icon fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
						}
						// Numbers.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_above_feed' === $fb_shortcode['slider_controls']
						) {
							echo '<span id="fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" class="fts-custom-caption" ></span>';
						}
						// Next Arrow.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_above_feed' === $fb_shortcode['slider_controls']
						) {
							echo '<span class="fts-nextControl-icon fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
						}

						// Slider Arrow and Numbers Wrapper.
						if (
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'arrows_above_feed' === $fb_shortcode['slider_controls'] ||
							isset( $fb_shortcode['slider_controls'] ) && 'numbers_above_feed' === $fb_shortcode['slider_controls']
						) {
							echo '</div>';
						}
					}

					echo '<div class="popup-gallery-fb fts-fb-slideshow fts-slicker-facebook-photos fts-slicker-facebook-albums ' . esc_attr( $fts_cycle_slideshow ) . ' ' . ( isset( $fb_shortcode['video_album'] ) && $fb_shortcode['video_album'] && 'yes' === $fb_shortcode['video_album'] ? 'popup-video-gallery-fb' : '' ) . ' ' . ( isset( $fb_shortcode['images_align'] ) && $fb_shortcode['images_align'] ? ' popup-video-gallery-align-' . esc_attr( $fb_shortcode['images_align'] ) : '' ) . ' popup-gallery-fb ' . esc_attr( $fts_dynamic_class_name ) . '"

style="margin:' . ( isset( $fb_shortcode['slider_margin'] ) && '' !== $fb_shortcode['slider_margin'] ? esc_attr( $fb_shortcode['slider_margin'] ) : 'auto' ) . ';' . ( isset( $fts_cycle_type ) && 'carousel' === $fts_cycle_type ? 'width:100%; max-width:100%; overflow:hidden;height:' . esc_attr( $fb_shortcode['image_height'] ) . ';' : 'overflow:hidden; height:' . esc_attr( $fb_shortcode['image_height'] ) . '; max-width:' . ( isset( $fb_shortcode['image_width'] ) && '' !== $fb_shortcode['image_width'] ? esc_attr( $fb_shortcode['image_width'] ) : 'auto' ) ) . ';" data-cycle-caption="#fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-caption-template="{{slideNum}} / {{slideCount}}" data-cycle-pager=".fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-pause-on-hover="true" data-cycle-prev=".fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-next=".fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-timeout="' . ( ! empty( $fb_shortcode['slider_timeout'] ) ? esc_attr( $fb_shortcode['slider_timeout'] ) : '0' ) . '" data-cycle-manual-speed="' . ( ! empty( $fb_shortcode['slider_speed'] ) ? esc_attr( $fb_shortcode['slider_speed'] ) : '400' ) . '" data-cycle-auto-height="false" data-cycle-slides="> div" data-cycle-fx="' . ( ! empty( $fb_shortcode['scrollhorz_or_carousel'] ) ? esc_attr( $fb_shortcode['scrollhorz_or_carousel'] ) : '' ) . '" data-cycle-carousel-visible=' . ( ! empty( $fb_shortcode['slides_visible'] ) ? esc_attr( $fb_shortcode['slides_visible'] ) : '4' ) . ' data-cycle-swipe=true data-cycle-swipe-fx=' . ( ! empty( $fb_shortcode['scrollhorz_or_carousel'] ) ? esc_attr( $fb_shortcode['scrollhorz_or_carousel'] ) : '' ) . '>';
				}

				if ( isset( $fb_shortcode['grid'] ) && 'yes' === $fb_shortcode['grid'] ) {
					echo '<div class="fts-slicker-facebook-posts masonry js-masonry ' . esc_attr( $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail ) . ( 'yes' === $fb_shortcode['popup'] ? 'popup-gallery-fb-posts ' : '' ) . ( 'reviews' === $fb_shortcode['type'] ? 'fts-reviews-feed ' : '' ) . esc_attr( $fts_dynamic_class_name ) . ' " style="margin:auto;" data-masonry-options=\'{ "isFitWidth": ' . ( 'no' === $fb_shortcode['center_container'] ? 'false' : 'true' ) . ' ' . ( 'no' === $fb_shortcode['image_stack_animation'] ? ', "transitionDuration": 0' : '' ) . '}\'>';
				}
			} else {
				echo '<div class="fts-jal-fb-group-display fts-simple-fb-wrapper ' . esc_attr( $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail ) . ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ? ' popup-gallery-fb-posts ' : '' ) . ( 'reviews' === $fb_shortcode['type'] ? 'fts-reviews-feed ' : '' ) . esc_attr( $fts_dynamic_class_name ) . ' ' . ( 'auto' !== $fb_shortcode['height'] && ! empty( $fb_shortcode['height'] ) ? 'fts-fb-scrollable" style="height:' . esc_attr( $fb_shortcode['height'] ) . '"' : '"' ) . '>';
			}
		} //End ajaxing Check

		// *********************
		// Post Information
		// *********************
		$fb_load_more_text   = get_option( 'fb_load_more_text' ) ? get_option( 'fb_load_more_text' ) : esc_html( 'Load More', 'feed-them-social' );
		$response_post_array = $this->get_post_info( $feed_data, $fb_shortcode, $access_token, $language );

		// Single event info call.
		if ( 'events' === $fb_shortcode['type'] ) {
			$single_event_array_response = $this->get_event_post_info( $feed_data, $fb_shortcode, $access_token, $language );
		}

		$set_zero = 0;

		// echo '<br/><br/>feed array<br/><br/>';.
        // echo '<pre>';
        // print_r($feed_data );
        // echo '</pre>';
		// THE MAIN FEED
		// LOOP to fix Post count!
		foreach ( $feed_data->data as $k => $v ) {
			if ( $k >= $fb_shortcode['posts'] ) {
				unset( $feed_data->data[ $k ] );
			}
		}

		// Nov. 4th. 2016 // Uncomment this to sort the dates proper if facebook is returning them out of order.
		// We had one case of this here for a list of posts coming from an event.
		// https://wordpress.org/support/topic/facebook-event-posts-not-ordered-by-date/
		// usort($feed_data->data, array($this, "dateSort"));
		// Loop for all facebook feeds.
		foreach ( $feed_data->data as $post_data ) {

			$fb_message     = isset( $post_data->message ) ? $post_data->message : '';
			$fb_status_type = isset( $post_data->status_type ) ? $post_data->status_type : '';

			$fb_story = isset( $post_data->story ) ? $post_data->story : '';
			$fb_type  = isset( $post_data->type ) ? $post_data->type : '';

			// This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
			if ( 'status' === $fb_type && empty( $fb_message ) && empty( $fb_story ) || 'event' === $fb_type || 'event' === $fb_type && false !== strpos( $fb_story, 'shared their event' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'changed the name of the event to' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'changed the privacy setting' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'an admin of the group' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'created the group' ) || 'status' === $fb_type && false !== strpos( $fb_story, 'added an event' ) || 'event' === $fb_type && false !== strpos( $fb_story, 'added an event' ) ) {
			} else {
				// define type note also affects load more fucntion call.
				if ( ! $fb_type && 'album_photos' === $fb_shortcode['type'] ) {
					$fb_type = 'photo';
				}
				if ( ! $fb_type && 'events' === $fb_shortcode['type'] ) {
					$fb_type = 'events';

				}

				$post_types                  = new fts_facebook_feed_post_types();
				$single_event_array_response = isset( $single_event_array_response ) ? $single_event_array_response : '';

				// echo '<br/><br/>were function gets called <br/><br/>' .
				// print_r( $post_data );.
				$post_types->feed_post_types( $set_zero, $fb_type, $post_data, $fb_shortcode, $response_post_array, $single_event_array_response );

			}

			$set_zero++;
		}// END POST foreach

		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'reviews' !== $fb_shortcode['type'] || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && 'reviews' === $fb_shortcode['type'] ) {
			if ( ! empty( $feed_data->data ) ) {
				$this->fts_facebook_loadmore( $atts, $feed_data, $fb_type, $fb_shortcode, sanitize_key( $_REQUEST['fts_dynamic_name'] ) );
			}
		}

		echo '</div>'; // closing main div for fb photos, groups etc
		// only show this script if the height option is set to a number.
		if ( 'auto' !== $fb_shortcode['height'] && ! empty( $fb_shortcode['height'] ) ) {
			echo '<script>';
			// this makes it so the page does not scroll if you reach the end of scroll bar or go back to top'.
			echo 'jQuery.fn.isolatedScrollFacebookFTS = function() {';
			echo 'this.bind("mousewheel DOMMouseScroll", function (e) {';
			echo 'var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,';
			echo 'bottomOverflow = this.scrollTop + jQuery(this).outerHeight() - this.scrollHeight >= 0,';
			echo 'topOverflow = this.scrollTop <= 0;';
			echo 'if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {';
			echo 'e.preventDefault();';
			echo '}';
			echo '});';
			echo 'return this;';
			echo '};';
			echo 'jQuery(".fts-fb-scrollable").isolatedScrollFacebookFTS();';
			echo '</script>';
		} //end $fb_shortcode['height'] !== 'auto' && empty($fb_shortcode['height']) == NULL
		// Make sure it's not ajaxing.
		if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
			echo '<div class="fts-clear"></div><div id="fb-root"></div>';
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'reviews' !== $fb_shortcode['type'] || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && 'reviews' === $fb_shortcode['type'] ) {
				if ( 'button' === $fb_shortcode['loadmore'] ) {

					echo '<div class="fts-fb-load-more-wrapper">';
					echo '<div id="loadMore_' . esc_attr( $_REQUEST['fts_dynamic_name'] ) . '" style="';
					if ( isset( $fb_shortcode['loadmore_btn_maxwidth'] ) && '' !== $fb_shortcode['loadmore_btn_maxwidth'] ) {
						echo 'max-width:' . esc_attr( $fb_shortcode['loadmore_btn_maxwidth'] ) . ';';
					}
					$loadmore_btn_margin = isset( $fb_shortcode['loadmore_btn_margin'] ) ? $fb_shortcode['loadmore_btn_margin'] : '20px';
					echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $fb_load_more_text ) . '</div>';
					echo '</div>';
				}
			}
		}//End Check

		// Checks for sliders.
		if (
			isset( $fb_shortcode['slider_controls'] ) && 'dots_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'arrows_below_feed' === $fb_shortcode['slider_controls'] ||
			isset( $fb_shortcode['slider_controls'] ) && 'numbers_below_feed' === $fb_shortcode['slider_controls']
		) {

			// Slider Dots Wrapper.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls']
			) {
				if ( isset( $fb_shortcode['slider_controls_width'] ) && 'carousel' !== $fb_shortcode['scrollhorz_or_carousel'] ) {
					$max_width_set = isset( $fb_shortcode['image_width'] ) && '' !== $fb_shortcode['image_width'] && 'carousel' !== $fb_shortcode['scrollhorz_or_carousel'] ? $fb_shortcode['image_width'] : '100%';
				} else {
					$max_width_set = isset( $fb_shortcode['slider_controls_width'] ) && '' !== $fb_shortcode['slider_controls_width'] && 'carousel' === $fb_shortcode['scrollhorz_or_carousel'] ? $fb_shortcode['slider_controls_width'] : '100%';
				}

				echo '<div class="fts-slider-icons-center" style="margin:auto; width:100%;max-width:' . esc_attr( $max_width_set ) . ';background:' . esc_attr( $fts_controls_bar_color ) . ';color:' . esc_attr( $fts_controls_text_color ) . '"><div class="fts-pager-option fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '"></div></div>';
			}

			// Slider Arrow and Numbers Wrapper.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'numbers_below_feed' === $fb_shortcode['slider_controls']
			) {
				echo '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . esc_attr( $max_width_set ) . ';background:' . esc_attr( $fts_controls_bar_color ) . ';color:' . esc_attr( $fts_controls_text_color ) . '">';
			}

			// Previous Arrow.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_below_feed' === $fb_shortcode['slider_controls']
			) {
				echo '<span class="fts-prevControl-icon fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
			}
			// Numbers.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_below_feed' === $fb_shortcode['slider_controls']
			) {
				echo '<span id="fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" class="fts-custom-caption" ></span>';
			}
			// Next Arrow.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_below_feed' === $fb_shortcode['slider_controls']
			) {
				echo '<span class="fts-nextControl-icon fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
			}

			// Slider Arrow and Numbers Wrapper.
			if (
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'dots_arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_and_numbers_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'arrows_below_feed' === $fb_shortcode['slider_controls'] ||
				isset( $fb_shortcode['slider_controls'] ) && 'numbers_below_feed' === $fb_shortcode['slider_controls']
			) {
				echo '</div>';
			}
		}

		unset( $_REQUEST['next_url'] );

		// ******************
		// SOCIAL BUTTON
		// ******************
		if ( ! $fts_count_ids >= 1 ) {
			$this->fb_social_btn_placement( $fb_shortcode, $access_token, 'fb-like-below' );
		}

		return ob_get_clean();
	}

	/**
	 * Get FTS Dnamic Class Name
	 *
	 * @return string
	 * @since 1.9.6
	 */
	public function get_fts_dynamic_class_name() {
		$fts_dynamic_class_name = '';
		if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
			$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
		}
		return $fts_dynamic_class_name;
	}

	/**
	 * FTS Facebook Location
	 *
	 * Facebook Post Location.
	 *
	 * @param null   $fb_type What kind of facebook feed it is.
	 * @param string $location The location of the photo or video.
	 * @since 1.9.6
	 */
	public function fts_facebook_location( $fb_type = null, $location ) {
		switch ( $fb_type ) {
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'album':
				echo '<div class="fts-fb-location">' . esc_html( $location ) . '</div>';
		}
	}

	/**
	 * FTS Facebook Post Photo
	 *
	 * @param string $fb_link The link to post.
	 * @param string $fb_shortcode The shortcode.
	 * @param string $photo_from Who it's from.
	 * @param string $photo_source The source url.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_photo( $fb_link, $fb_shortcode, $photo_from, $photo_source ) {
		if ( 'album_photos' === $fb_shortcode['type'] || 'albums' === $fb_shortcode['type'] ) {
			echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" class="fts-jal-fb-picture album-photo-fts" style="width:' . esc_attr( $fb_shortcode['image_width'] . ';height:' . $fb_shortcode['image_height'] ) . ';';
			if ( 'albums' === $fb_shortcode['type'] ) {
				echo 'background-image:url(' . esc_url( 'https://graph.facebook.com/' . $photo_source . '/picture' ) . ');">';
			} else {
				echo 'background-image:url(' . esc_url( $photo_source ) . ');">';
			}
			echo '</a>';
		} else {
			$fb_shortcode_popup = isset( $fb_shortcode['popup'] ) ? $fb_shortcode['popup'] : '';
			if ( 'yes' === $fb_shortcode_popup && 'javascript:;' !== $fb_link ) {
				echo '<a href="' . esc_url( $photo_source ) . '" target="_blank" class="fts-facebook-link-target fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_html( $photo_from ) . '" src="' . esc_url( $photo_source ) . '"/></a>';

			} else {
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' . esc_html( $photo_from ) . '" src="' . esc_url( $photo_source ) . '"/></a>';
			}
		}
	}

	/**
	 * FTS Facebook Post Name
	 *
	 * @param string $fb_link The post link.
	 * @param string $fb_name The facebook name.
	 * @param string $fb_type The type of feed.
	 * @param null   $fb_post_id The facebook post ID.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_name( $fb_link, $fb_name, $fb_type, $fb_post_id = null ) {
		switch ( $fb_type ) {
			case 'video':
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" class="fts-jal-fb-name fb-id' . esc_attr( $fb_post_id ) . '">' . wp_kses(
					$this->fts_facebook_tag_filter( $fb_name ),
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</a>';
				break;
			default:
				$fb_name = $this->fts_facebook_tag_filter( $fb_name );
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" class="fts-jal-fb-name">' . wp_kses(
					$this->fts_facebook_tag_filter( $fb_name ),
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</a>';
				break;
		}
	}

	/**
	 * FTS Facebook Post Description
	 *
	 * @param string $fb_description The post description.
	 * @param string $fb_shortcode The shortcode.
	 * @param string $fb_type The type of feed.
	 * @param null   $fb_post_id The post ID.
	 * @param null   $fb_by The post by.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_desc( $fb_description, $fb_shortcode, $fb_type, $fb_post_id = null, $fb_by = null ) {
        $trunacate_words =  new \ FeedThemSocialTruncateHTML();
		switch ( $fb_type ) {
			case 'video':
				$fb_description = $this->fts_facebook_tag_filter( $fb_description );
				echo '<div class="fts-jal-fb-description fb-id' . esc_attr( $fb_post_id ) . '">' . wp_kses(
					$fb_description,
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</div>';
				break;
			case 'photo':
				if ( 'album_photos' === $fb_shortcode['type'] ) {
					if ( array_key_exists( 'words', $fb_shortcode ) ) {
						$more            = isset( $more ) ? $more : '';
						$trimmed_content = $trunacate_words->fts_custom_trim_words( $fb_description, $fb_shortcode['words'], $more );
						echo '<div class="fts-jal-fb-description fts-non-popup-text">' . wp_kses(
							$trimmed_content,
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
						// Here we display the full description in the popup.
						if ( 'yes' === $fb_shortcode['popup'] || 'yes' === $fb_shortcode['video_album'] ) {
							echo '<div class="fts-jal-fb-description fts-jal-fb-description-popup" style="display: none;">' . wp_kses(
								nl2br( $fb_description ),
								array(
									'a'      => array(
										'href'  => array(),
										'title' => array(),
									),
									'br'     => array(),
									'em'     => array(),
									'strong' => array(),
									'small'  => array(),
								)
							) . '</div>';
						}
					} elseif ( isset( $fb_shortcode['words'] ) && '0' !== $fb_shortcode['words'] ) {
						$fb_description = $this->fts_facebook_tag_filter( $fb_description );
						echo '<div class="fts-jal-fb-description">' . wp_kses(
							nl2br( $fb_description ),
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					}
				}
				break;
			case 'albums':
				if ( 'albums' === $fb_shortcode['type'] ) {
					if ( array_key_exists( 'words', $fb_shortcode ) ) {
						$more            = isset( $more ) ? $more : '';
						$trimmed_content = $trunacate_words->fts_custom_trim_words( $fb_description, $fb_shortcode['words'], $more );
						echo '<div class="fts-jal-fb-description">' . wp_kses(
							$trimmed_content,
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					} else {
						$fb_description = $this->fts_facebook_tag_filter( $fb_description );
						echo '<div class="fts-jal-fb-description">' . wp_kses(
							nl2br( $fb_description ),
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					}
				} else {
					// Do for Default feeds or the video gallery feed.
					if ( isset( $fb_shortcode['words'] ) && '0' !== $fb_shortcode['words'] ) {
						$fb_description = $this->fts_facebook_tag_filter( $fb_description );
						if ( is_array( $fb_shortcode ) && array_key_exists( 'words', $fb_shortcode ) && '0' !== $fb_shortcode['words'] ) {
							$more            = isset( $more ) ? $more : '';
							$trimmed_content = $trunacate_words->fts_custom_trim_words( $fb_description, $fb_shortcode['words'], $more );
							echo '<div class="fts-jal-fb-description">' . wp_kses(
								$trimmed_content,
								array(
									'a'      => array(
										'href'  => array(),
										'title' => array(),
									),
									'br'     => array(),
									'em'     => array(),
									'strong' => array(),
									'small'  => array(),
								)
							) . '</div>';
						} else {
							echo '<div class="fts-jal-fb-description">';
							echo wp_kses(
								nl2br( $fb_description ),
								array(
									'a'      => array(
										'href'  => array(),
										'title' => array(),
									),
									'br'     => array(),
									'em'     => array(),
									'strong' => array(),
									'small'  => array(),
								)
							);
							echo '</div>';
						}
						if ( ! empty( $fb_link ) ) {
							echo '<div>By: <a href="' . esc_url( $fb_link ) . '">' . esc_html( $fb_by ) . '<a/></div>';
						}
					}
				}
				break;
			default:
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
					// here we trim the words for the links description text... for the premium version. The $fb_shortcode['words'] string actually comes from the javascript.
					if ( is_array( $fb_shortcode ) && array_key_exists( 'words', $fb_shortcode ) ) {
						$more            = isset( $more ) ? $more : '';
						$trimmed_content = $trunacate_words->fts_custom_trim_words( $fb_description, $fb_shortcode['words'], $more );
						echo '<div class="jal-fb-description">' . wp_kses(
							$trimmed_content,
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					} elseif ( is_array( $fb_shortcode ) && array_key_exists( 'words', $fb_shortcode ) && '0' !== $fb_shortcode['words'] ) {
						$fb_description = $this->fts_facebook_tag_filter( $fb_description );
						echo '<div class="jal-fb-description">' . wp_kses(
							nl2br( $fb_description ),
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					}
				} else {
					// if the premium plugin is not active we will just show the regular full description.
					$fb_description = $this->fts_facebook_tag_filter( $fb_description );
					echo '<div class="jal-fb-description">' . wp_kses(
						nl2br( $fb_description ),
						array(
							'a'      => array(
								'href'  => array(),
								'title' => array(),
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
	 * FTS Facebook Post Caption
	 *
	 * @param string $fb_caption The post caption.
	 * @param string $fb_shortcode The shortcode.
	 * @param string $fb_type The type of feed.
	 * @param null   $fb_post_id The post ID.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_cap( $fb_caption, $fb_shortcode, $fb_type, $fb_post_id = null ) {
        $trunacate_words =  new \ FeedThemSocialTruncateHTML();
		switch ( $fb_type ) {
			case 'video':
				$fb_caption = $this->fts_facebook_tag_filter( str_replace( 'www.', '', $fb_caption ) );
				echo '<div class="fts-jal-fb-caption fb-id' . esc_attr( $fb_post_id ) . '">' . wp_kses(
					$fb_caption,
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
						'small'  => array(),
					)
				) . '</div>';
				break;
			default:
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
					// here we trim the words for the links description text... for the premium version. The $fb_shortcode['words'] string actually comes from the javascript.
					if ( array_key_exists( 'words', $fb_shortcode ) ) {
						$more            = isset( $more ) ? $more : '';
						$trimmed_content = $trunacate_words->fts_custom_trim_words( $fb_caption, $fb_shortcode['words'], $more );
						echo '<div class="jal-fb-caption">' . wp_kses(
							$trimmed_content,
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					} else {
						$fb_caption = $this->fts_facebook_tag_filter( $fb_caption );
						echo '<div class="jal-fb-caption">' . wp_kses(
							nl2br( $fb_caption ),
							array(
								'a'      => array(
									'href'  => array(),
									'title' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
								'small'  => array(),
							)
						) . '</div>';
					}
				} else {
					// if the premium plugin is not active we will just show the regular full description.
					$fb_caption = $this->fts_facebook_tag_filter( $fb_caption );
					echo '<div class="jal-fb-caption">' . wp_kses(
						nl2br( $fb_caption ),
						array(
							'a'      => array(
								'href'  => array(),
								'title' => array(),
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
	 * Get Likes Shares Comments
	 *
	 * Get the total count for all.
	 *
	 * @param string $response_post_array The array from facebook.
	 * @param string $post_data_key The post data Key.
	 * @param string $fb_post_share_count The post Share Count.
	 * @return array
	 * @since 1.9.6
	 */
	public function get_likes_shares_comments( $response_post_array, $post_data_key, $fb_post_share_count ) {
		$lsc_array = array();
		// Get Likes & Comments.
		if ( $response_post_array ) {
			if ( isset( $response_post_array[ $post_data_key . '_likes' ] ) ) {
				$like_count_data = json_decode( $response_post_array[ $post_data_key . '_likes' ] );

				// Like Count.
				if ( ! empty( $like_count_data->summary->total_count ) ) {
					$fb_post_like_count = $like_count_data->summary->total_count;
				} else {
					$fb_post_like_count = 0;
				}
				if ( 0 === $fb_post_like_count ) {
					$lsc_array['likes'] = '';
				}
				if ( 1 === $fb_post_like_count ) {
					$lsc_array['likes'] = "<i class='icon-thumbs-up'></i> 1";
				}
				if ( $fb_post_like_count > '1' ) {
					$lsc_array['likes'] = "<i class='icon-thumbs-up'></i> " . esc_html( $fb_post_like_count );
				}
			}
			if ( isset( $response_post_array[ $post_data_key . '_comments' ] ) ) {
				$comment_count_data = json_decode( $response_post_array[ $post_data_key . '_comments' ] );

				if ( ! empty( $comment_count_data->summary->total_count ) ) {
					$fb_post_comments_count = $comment_count_data->summary->total_count;
				} else {
					$fb_post_comments_count = 0;
				}
				if ( 0 === $fb_post_comments_count ) {
					$lsc_array['comments'] = '';
				}
				if ( 1 === $fb_post_comments_count ) {
					$lsc_array['comments']        = "<i class='icon-comments'></i> 1";
					$lsc_array['comments_thread'] = $comment_count_data;

				}
				if ( $fb_post_comments_count > '1' ) {
					$lsc_array['comments']        = "<i class='icon-comments'></i> " . $fb_post_comments_count;
					$lsc_array['comments_thread'] = $comment_count_data;
				}
			}
		}
		// Shares Count.
		if ( 0 === $fb_post_share_count || ! $fb_post_share_count ) {
			$lsc_array['shares'] = '';
		}
		if ( 1 === $fb_post_share_count ) {
			$lsc_array['shares'] = "<i class='icon-file'></i> 1";
		}
		if ( $fb_post_share_count > '1' ) {
			$lsc_array['shares'] = "<i class='icon-file'></i> " . $fb_post_share_count;
		}
		return $lsc_array;
	}


	/**
	 * FTS Facebook Post See More
	 *
	 * Generate See More Button.
	 *
	 * @param string $fb_link The facebook link.
	 * @param string $lcs_array The lcs array.
	 * @param string $fb_type The type of feed.
	 * @param null   $fb_post_id The post id.
	 * @param string $fb_shortcode The shortcode.
	 * @param null   $fb_post_user_id The user id.
	 * @param null   $fb_post_single_id The single post id.
	 * @param null   $single_event_id The event id.
	 * @param string $post_data The post data.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_see_more( $fb_link, $lcs_array, $fb_type, $fb_post_id = null, $fb_shortcode, $fb_post_user_id = null, $fb_post_single_id = null, $single_event_id = null, $post_data ) {

		$description = isset( $post_data->message ) ? $post_data->message : '';
		// SHOW THE FB FEED PRINT_R
		// echo'<pre>';.
		// print_r();.
		// echo'</pre>';.
		$view_on_facebook = get_option( 'fb_view_on_fb_fts' ) ? get_option( 'fb_view_on_fb_fts' ) : __( 'View on Facebook', 'feed-them-social' );
		$share_this       = new feed_them_social_functions();
		switch ( $fb_type ) {
			case 'events':
				$single_event_id = 'https://www.facebook.com/events/' . $single_event_id;
				echo '<div class="fts-likes-shares-etc-wrap">';
				echo $share_this->fts_share_option( $single_event_id, $description );
				echo '<a href="' . esc_attr( $single_event_id ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html( $view_on_facebook ) . '</a></div>';
				break;
			case 'photo':
				if ( ! empty( $fb_link ) ) {
					echo '<div class="fts-likes-shares-etc-wrap">';
					echo $share_this->fts_share_option( $fb_link, $description );
					echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" class="fts-jal-fb-see-more">';
				} else {
					// exception for videos.
					$single_video_id = 'https://www.facebook.com/' . $fb_post_id;
					echo '<div class="fts-likes-shares-etc-wrap">';
					echo $share_this->fts_share_option( $single_video_id, $description );
					echo '<a href="' . esc_url( $single_video_id ) . '" target="_blank" class="fts-jal-fb-see-more">';
				}
				if ( 'album_photos' === $fb_shortcode['type'] && 'yes' === $fb_shortcode['hide_date_likes_comments'] ) {

					echo '<div class="hide-date-likes-comments-etc">' . wp_kses(
						$lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' ' . $lcs_array['shares'],
						array(
							'a' => array(
								'href'  => array(),
								'title' => array(),
							),
							'i' => array(
								'class' => array(),
							),
						)
					) . ' &nbsp;&nbsp;</div>';
				} else {

					echo '' . wp_kses(
						$lcs_array['likes'] . ' ' . $lcs_array['comments'] . ' ' . $lcs_array['shares'],
						array(
							'a' => array(
								'href'  => array(),
								'title' => array(),
							),
							'i' => array(
								'class' => array(),
							),
						)
					) . ' &nbsp;&nbsp;';
				}
				echo '&nbsp;' . esc_html( $view_on_facebook ) . '</a></div>';
				break;
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'albums':
				$url_parsed    = parse_url( $fb_link, PHP_URL_QUERY );
				$params        = parse_str( $url_parsed, $params );
				$new_album_url = str_replace( 'album.php?fbid=' . $params['fbid'] . '&id=' . $params['id'] . '&aid=' . $params['aid'], 'media/set/?set=a.' . $params['fbid'] . '.' . $params['aid'] . '.' . $params['id'], $fb_link );

				echo '<div class="fts-likes-shares-etc-wrap">';
				echo $share_this->fts_share_option( $new_album_url, $description );
				echo '<a href="' . esc_url( $new_album_url ) . '" target="_blank" class="fts-jal-fb-see-more">';
				if ( 'albums' === $fb_shortcode['type'] && 'yes' === $fb_shortcode['hide_date_likes_comments'] ) {
				} else {

					echo '' . wp_kses(
						$lcs_array['likes'] . ' ' . $lcs_array['comments'],
						array(
							'a' => array(
								'href'  => array(),
								'title' => array(),
							),
							'i' => array(
								'class' => array(),
							),
						)
					) . ' &nbsp;&nbsp;';
				}
				echo '&nbsp;' . esc_html( $view_on_facebook ) . '</a></div>';
				break;
			default:
				if ( 'yes' !== get_option( 'fb_reviews_remove_see_reviews_link' ) ) {
					if ( 'reviews' === $fb_shortcode['type'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
						$fb_reviews_see_more_reviews_language = get_option( 'fb_reviews_see_more_reviews_language' ) ? get_option( 'fb_reviews_see_more_reviews_language' ) : 'See More Reviews';

						$hide_see_more = isset( $fb_shortcode['hide_see_more_reviews_link'] ) ? $fb_shortcode['hide_see_more_reviews_link'] : 'yes';
						if ( 'yes' !== $hide_see_more ) {
							echo ' <a href="' . esc_url( 'https://www.facebook.com/' . $fb_shortcode['id'] . '/reviews' ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html( $fb_reviews_see_more_reviews_language, 'feed-them-social' ) . '</a>';
						}
					} else {
						$post_single_id = 'https://www.facebook.com/' . $fb_post_user_id . '/posts/' . $fb_post_single_id;
						echo '<div class="fts-likes-shares-etc-wrap">';
						echo $share_this->fts_share_option( $post_single_id, $description );
						echo '<a href="' . esc_url( $post_single_id ) . '" target="_blank" class="fts-jal-fb-see-more">';

						echo '' . wp_kses(
							$lcs_array['likes'] . ' ' . $lcs_array['comments'],
							array(
								'a' => array(
									'href'  => array(),
									'title' => array(),
								),
								'i' => array(
									'class' => array(),
								),
							)
						) . ' &nbsp;&nbsp;&nbsp;' . esc_html( $view_on_facebook ) . '</a></div>';
					}
				}
				break;
		}
	}

	/**
	 * Get Access Token
	 *
	 * @return mixed
	 * @since 1.9.6
	 */
	public function get_access_token() {
		// The API Access Token.
		// $custom_access_token = get_option('fts_facebook_custom_api_token');
		// if (!empty($custom_access_token)) {
		// return $access_token;
		// } else {
		// Randomizer
		// $values = array(
		// '431287540548931|4A23YYIFqhd-gpz_E4Fy6U_Seo0',
		// '1748446362151826|epVUmLiKT8QhLN63iRvvXXHwxqk',
		// '1875381106044241|KmWz3mtzGye0M5HTdX0SK7rqpIU',
		// '754106341419549|AMruxCJ_ly8825VXeLhBKo_kOfs',
		// '438563519819257|1GJ8GLl1AQ7ZTvXV_Xpok_QpH6s',
		// '753693994788276|xm_PXoNRWW8WPQdcQArRpBgWn5Q',
		// '644818402385988|sABEvG0QiOaJRlNLC2NphfQLlfg',
		// '292500071162951|9MA-kzWVs6HTEybpdxKjgF_gqeo',
		// '263710677420086|Jpui2CFig7RbtdHaHPN_fiEa77U',
		// '1850081601881384|u2JcPCn7TH40MY5BwC-i4PMHGm8',
		// );
		// $access_token = $values[array_rand($values, 1)];.
		return get_option( 'fts_facebook_custom_api_token' );
		// }
	}

	/**
	 * Get View Link
	 *
	 * @param string $fb_shortcode The facebook feed shortcode.
	 * @return string
	 * @since 1.9.6
	 */
	public function get_view_link( $fb_shortcode ) {
		switch ( $fb_shortcode['type'] ) {
			case 'group':
				$fts_view_fb_link = 'https://www.facebook.com/groups/' . $fb_shortcode['id'] . '/';
				break;
			case 'page':
				$fts_view_fb_link = 'https://www.facebook.com/' . $fb_shortcode['id'] . '/';
				break;
			case 'event':
				$fts_view_fb_link = 'https://www.facebook.com/events/' . $fb_shortcode['id'] . '/';
				break;
			case 'events':
				$fts_view_fb_link = 'https://www.facebook.com/' . $fb_shortcode['id'] . '/events/';
				break;
			case 'albums':
				$fts_view_fb_link = 'https://www.facebook.com/' . $fb_shortcode['id'] . '/photos_stream?tab=photos_albums';
				break;
			// album photos and videos album.
			case 'album_photos':
				$fts_view_fb_link = isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ? 'https://www.facebook.com/' . $fb_shortcode['id'] . '/videos/' : 'https://www.facebook.com/' . $fb_shortcode['id'] . '/photos_stream/';
				break;
			case 'hashtag':
				$fts_view_fb_link = 'https://www.facebook.com/hashtag/' . $fb_shortcode['id'] . '/';
				break;
			case 'reviews':
				$fts_view_fb_link = 'https://www.facebook.com/' . $fb_shortcode['id'] . '/reviews/';
				break;
		}
		$fts_view_fb_link = isset( $fts_view_fb_link ) ? $fts_view_fb_link : '';
		return $fts_view_fb_link;
	}

	/**
	 * Get FB Cache Name
	 *
	 * @param string $fb_shortcode The facebook feed shortcode.
	 * @return string
	 * @since 1.9.6
	 */
	public function get_fb_cache_name( $fb_shortcode ) {
		// URL to get page info.
		$r_count = substr_count( $fb_shortcode['id'], ',' );

		if ( $r_count >= 1 ) {
			$result             = preg_replace( '/[ ,]+/', '-', trim( $fb_shortcode['id'] ) );
			$fb_shortcode['id'] = $result;
		}

		switch ( $fb_shortcode['type'] ) {
			case 'album_photos':
				$fb_data_cache_name = 'fb_' . $fb_shortcode['type'] . '_' . $fb_shortcode['id'] . '_' . $fb_shortcode['album_id'] . '_num' . $fb_shortcode['posts'] . '';
				break;
			default:
				$fb_data_cache_name = 'fb_' . $fb_shortcode['type'] . '_' . $fb_shortcode['id'] . '_num' . $fb_shortcode['posts'] . '';
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
	public function get_language() {
		// this check is in place because we used this option and it failed for many people because we use wp get contents instead of curl.
		// this can be removed in a future update and just keep the $language_option = get_option('fb_language', 'en_US');.
		$language_option_check = get_option( 'fb_language' );
		if ( isset( $language_option_check ) && 'Please Select Option' !== $language_option_check ) {
			$language_option = get_option( 'fb_language', 'en_US' );
		} else {
			$language_option = 'en_US';
		}
		return ! empty( $language_option ) ? '&locale=' . $language_option : '';
	}

	/**
	 * Get Facebook Overall Rating Response
	 *
	 * @param string $fb_shortcode The facebook feed shortcode.
	 * @param string $fb_cache_name The Cache Name.
	 * @param string $access_token The Access Token.
	 * @since 2.1.3
	 */
	public function get_facebook_overall_rating_response( $fb_shortcode, $fb_cache_name, $access_token ) {

		// $mulit_data_rating = $this->fts_get_feed_json($mulit_data_rating);.
		// Error Check
		// $feed_data_rating_overall = json_decode($mulit_data['rating_data']);.
		$fb_reviews_overall_rating_of_5_stars_text        = get_option( 'fb_reviews_overall_rating_of_5_stars_text' );
		$fb_reviews_overall_rating_of_5_stars_text        = ! empty( $fb_reviews_overall_rating_of_5_stars_text ) ? ' ' . $fb_reviews_overall_rating_of_5_stars_text : ' of 5 stars';
		$fb_reviews_overall_rating_reviews_text           = get_option( 'fb_reviews_overall_rating_reviews_text' );
		$fb_reviews_overall_rating_reviews_text           = ! empty( $fb_reviews_overall_rating_reviews_text ) ? ' ' . $fb_reviews_overall_rating_reviews_text : ' reviews';
		$fb_reviews_overall_rating_background_border_hide = get_option( 'fb_reviews_overall_rating_background_border_hide' );
		$fb_reviews_overall_rating_background_border_hide = ! empty( $fb_reviews_overall_rating_background_border_hide ) && 'yes' === $fb_reviews_overall_rating_background_border_hide ? ' fts-review-details-master-wrap-no-background-or-border' : '';

		echo '<div class="fts-review-details-master-wrap' . esc_attr( $fb_reviews_overall_rating_background_border_hide ) . '"><i class="fts-review-star">' . esc_html( $feed_data_rating_overall->overall_star_rating ) . ' &#9733;</i>';
		echo '<div class="fts-review-details-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="fts-review-details"><span itemprop="ratingValue">' . esc_html( $feed_data_rating_overall->overall_star_rating ) . '</span>' . esc_html( $fb_reviews_overall_rating_of_5_stars_text ) . '</div>';
		echo '<div class="fts-review-details-count"><span itemprop="reviewCount">' . esc_html( $feed_data_rating_overall->rating_count ) . '</span>' . esc_html( $fb_reviews_overall_rating_reviews_text ) . '</div></div></div>';

		// $fb_cache_name = $fb_shortcode['id'] . $this->rand_string(10);
		// Make sure it's not ajaxing
		// if (!isset($_GET['load_more_ajaxing'])) {
		// Create Cache
		// $FTS_FB_OUTPUT = $this->fts_create_feed_cache($fb_cache_name, $feed_data_rating_overall);
		// }.
	}


	/**
	 * Get Facebook Feed Response
	 *
	 * @param string $fb_shortcode The facebook shortcode.
	 * @param string $fb_cache_name FB cache name.
	 * @param string $access_token The Access Token.
	 * @param string $language Language.
	 * @return array|mixed
	 * @since 1.9.6
	 */
	public function get_facebook_feed_response( $fb_shortcode, $fb_cache_name, $access_token, $language ) {

		if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
			$fts_count_ids = substr_count( $fb_shortcode['id'], ',' );
		} else {
			$fts_count_ids = '';
		}

		if ( false !== $this->fts_check_feed_cache_exists( $fb_cache_name ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
			$response = $this->fts_get_feed_cache( $fb_cache_name );
		} else {
			// Page.
			if ( 'page' === $fb_shortcode['type'] && 'page_only' === $fb_shortcode['posts_displayed'] ) {
				$mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $fb_shortcode['id'] . '?fields=id,name,description&access_token=' . $access_token . $language . '' );

                if(isset($_REQUEST['next_url'])){
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token='.get_option('fts_facebook_custom_api_token'), $_REQUEST['next_url'] );
                }

				if ( ! $fts_count_ids >= 1 ) {
					// We cannot add sanitize_text_field here on the $_REQUEST['next_url'] otherwise it will fail to load the contents from the facebook API.
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/posts?fields=id,caption,attachments,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				} else {
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/posts?ids=' . $fb_shortcode['id'] . '&fields=id,caption,attachments,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				}
			} elseif (
				// Albums.
				'albums' === $fb_shortcode['type'] ) {
				$mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $fb_shortcode['id'] . '?fields=id,name,description,link&access_token=' . $access_token . $language . '' );
                if(isset($_REQUEST['next_url'])){
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token='.get_option('fts_facebook_custom_api_token'), $_REQUEST['next_url'] );
                }
				// Check If Ajax next URL needs to be used.
				if ( ! $fts_count_ids >= 1 ) {
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/albums?fields=id,photos,created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				} else {
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/albums?ids=' . $fb_shortcode['id'] . '&fields=id,photos,created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				}

				// $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? esc_url_raw($_REQUEST['next_url']) : 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/albums?fields=id,created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '';
			} elseif (
				// Album Photos.
				'album_photos' === $fb_shortcode['type'] ) {
				$mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $fb_shortcode['id'] . '?fields=id,name,description&access_token=' . $access_token . $language . '' );
                if(isset($_REQUEST['next_url'])){
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token='.get_option('fts_facebook_custom_api_token'), $_REQUEST['next_url'] );
                }
				// Check If Ajax next URL needs to be used
				// The reason I did not create a whole new else if for the video album is because I did not want to duplicate all the code required to make the video because the videos gallery comes from the photo albums on facebook.
				if ( isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
					if ( ! $fts_count_ids >= 1 ) {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/videos?fields=id,created_time,description,from,icon,link,message,object_id,picture,place,source,to,type,format,embed_html&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					} else {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/videos?ids=' . $fb_shortcode['id'] . '&fields=id,created_time,description,from,icon,link,message,object_id,picture,place,source,to,type,format,embed_html&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					}
				} elseif ( isset( $fb_shortcode['album_id'] ) && 'photo_stream' === $fb_shortcode['album_id'] ) {
					if ( ! $fts_count_ids >= 1 ) {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					} else {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/photos?ids=' . $fb_shortcode['id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					}
				} else {
					if ( ! $fts_count_ids >= 1 ) {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['album_id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					} else {
						$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/photos?ids=' . $fb_shortcode['album_id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
					}
				}
			} elseif ( 'reviews' === $fb_shortcode['type'] ) {

				// Reviews.
				if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
					$fts_facebook_reviews = new FTS_Facebook_Reviews();
					$mulit_data           = $fts_facebook_reviews->review_connection( $fb_shortcode, $access_token, $language );

					$mulit_data['ratings_data'] = esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/?fields=overall_star_rating,rating_count&access_token=' . $access_token . '' );


				} else {
					return 'Please Purchase and Activate the Feed Them Social Reviews plugin.';
					exit;
				}
			} else {
				$mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $fb_shortcode['id'] . '?fields=feed,id,name,description&access_token=' . $access_token . $language . '' );

				// Check If Ajax next URL needs to be used.
				if ( ! $fts_count_ids >= 1 ) {
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $fb_shortcode['id'] . '/feed?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				} else {
					$mulit_data['feed_data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/feed?ids=' . $fb_shortcode['id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,full_picture,place,shares,source,status_type,story,to,type&limit=' . $fb_shortcode['posts'] . '&access_token=' . $access_token . $language . '' );
				}
			}
			$response = $this->fts_get_feed_json( $mulit_data );

			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// Error Check.
				$feed_data                = json_decode( $response['feed_data'] );
				$fts_error_check          = new fts_error_handler();
				$fts_error_check_complete = $fts_error_check->facebook_error_check( $fb_shortcode, $feed_data );
				if ( is_array( $fts_error_check_complete ) && true === $fts_error_check_complete[0] ) {
					return array( false, $fts_error_check_complete[1] );
				}
			}

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) && ! empty( $response['feed_data'] ) ) {
				// Create Cache.
				$this->fts_create_feed_cache( $fb_cache_name, $response );
			}
		} // end main else.

		return $response;

	}


	/**
	 * Get Facebook Feed Dynamic Name
	 *
	 * @param string $fb_shortcode The facebook feed shortcode.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function get_facebook_feed_dynamic_name( $fb_shortcode ) {

		return $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->fts_rand_string( 10 ) . '_' . $fb_shortcode['type'] );

	}

	/**
	 * Get Facebook Feed Dynamic Class Name
	 *
	 * @param null $fts_dynamic_name Our Dynamic Name for ajax.
	 * @return string
	 * @since 1.9.6
	 */
	public function get_facebook_feed_dynamic_class_name( $fts_dynamic_name = null ) {
		$fts_dynamic_class_name = '';
		if ( isset( $fts_dynamic_name ) ) {
			$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
		}
		return $fts_dynamic_class_name;
	}


	/**
	 * Get Post Info
	 *
	 * For Facebook.
	 *
	 * @param string $feed_data The facebook contents.
	 * @param string $fb_shortcode FB cache name.
	 * @param string $access_token The Access Token.
	 * @param string $language Language.
	 * @return array|mixed
	 * @since 1.9.6
	 */
	public function get_post_info( $feed_data, $fb_shortcode, $access_token, $language ) {
		$developer_mode = get_option( 'fts_clear_cache_developer_mode' );

		if ( 'album_photos' === $fb_shortcode['type'] ) {
			$fb_post_data_cache = 'fb_' . $fb_shortcode['type'] . '_post_' . $fb_shortcode['album_id'] . '_num' . $fb_shortcode['posts'] . '';
		} else {
			$fb_post_data_cache = 'fb_' . $fb_shortcode['type'] . '_post_' . $fb_shortcode['id'] . '_num' . $fb_shortcode['posts'] . '';
		}
		if ( false !== $this->fts_check_feed_cache_exists( $fb_post_data_cache ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
			$response_post_array = $this->fts_get_feed_cache( $fb_post_data_cache );
		} else {
			// Build the big post counter.
			$fb_post_array = array();
			// Single Events Array.
			$set_zero = 0;
			foreach ( $feed_data->data as $counter ) {

				$counter->id = isset( $counter->id ) ? $counter->id : '';

				if ( $set_zero === $fb_shortcode['posts'] ) {
					break;
				}

				$fb_type       = isset( $counter->type ) ? $counter->type : '';
				$post_data_key = isset( $counter->object_id ) ? $counter->object_id : $counter->id;

				// Likes & Comments.
				$fb_post_array[ $post_data_key . '_likes' ]    = 'https://graph.facebook.com/' . $post_data_key . '/reactions?summary=1&access_token=' . $access_token;
				$fb_post_array[ $post_data_key . '_comments' ] = 'https://graph.facebook.com/' . $post_data_key . '/comments?summary=1&access_token=' . $access_token;
				// Video.
				if ( 'video' === $fb_type ) {
					$fb_post_array[ $post_data_key . '_video' ] = 'https://graph.facebook.com/' . $post_data_key;
				}
				// Photo.
				$fb_album_cover = isset( $counter->cover_photo->id ) ? $counter->cover_photo->id : '';
				if ( 'albums' === $fb_shortcode['type'] && ! $fb_album_cover ) {
					unset( $counter );
					continue;
				}
				if ( 'albums' === $fb_shortcode['type'] ) {
					$fb_post_array[ $fb_album_cover . '_photo' ] = 'https://graph.facebook.com/' . $fb_album_cover;
				}
				if ( 'hashtag' === $fb_shortcode['type'] ) {
					$fb_post_array[ $post_data_key . '_photo' ] = 'https://graph.facebook.com/' . $counter->source;
				}
				// GROUP Photo.
				if ( 'group' === $fb_shortcode['type'] ) {
					$fb_post_array[ $post_data_key . '_group_post_photo' ] = 'https://graph.facebook.com/' . $counter->id . '?fields=picture,full_picture&access_token=' . $access_token;
				}

				$set_zero++;
			}

			// Response.
			$response_post_array = $this->fts_get_feed_json( $fb_post_array );
			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				// Create Cache.
				$this->fts_create_feed_cache( $fb_post_data_cache, $response_post_array );
			}
		}
		// SHOW THE POST RESPONSE PRINT_R
		// echo'<pre>';
		// print_r($response_post_array);
		// echo'</pre>';.
		return $response_post_array;
	}


	/**
	 * Get Post Info
	 *
	 * For Facebook.
	 *
	 * @param string $feed_data The facebook contents.
	 * @param string $fb_shortcode FB cache name.
	 * @param string $access_token The Access Token.
	 * @param string $language Language.
	 * @return array|mixed
	 * @since 2.1.6
	 */
	public function get_event_post_info( $feed_data, $fb_shortcode, $access_token, $language ) {
		$developer_mode = get_option( 'fts_clear_cache_developer_mode' );

		$fb_event_post_data_cache = 'fbe_' . $fb_shortcode['type'] . '_post_' . $fb_shortcode['id'] . '_num' . $fb_shortcode['posts'] . '';
		if ( false !== $this->fts_check_feed_cache_exists( $fb_event_post_data_cache ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
			$response_event_post_array = $this->fts_get_feed_cache( $fb_event_post_data_cache );
		} else {
			// Single Events Array.
			$fb_single_events_array = array();
			$set_zero               = 0;
			foreach ( $feed_data->data as $counter ) {

				$counter->id = isset( $counter->id ) ? $counter->id : '';

				if ( $set_zero === $fb_shortcode['posts'] ) {
					break;
				}

				$single_event_id = $counter->id;
				$language        = isset( $language ) ? $language : '';
				// Event Info, Time etc.
				$fb_single_events_array[ 'event_single_' . $single_event_id . '_info' ] = 'https://graph.facebook.com/' . $single_event_id . '/?access_token=' . $access_token . $language;
				// Event Location.
				$fb_single_events_array[ 'event_single_' . $single_event_id . '_location' ] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=place&access_token=' . $access_token . $language;
				// Event Cover Photo.
				$fb_single_events_array[ 'event_single_' . $single_event_id . '_cover_photo' ] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=cover&access_token=' . $access_token . $language;
				// Event Ticket Info.
				$fb_single_events_array[ 'event_single_' . $single_event_id . '_ticket_info' ] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=ticket_uri&access_token=' . $access_token . $language;

				$set_zero++;
			}

			$response_event_post_array = $this->fts_get_feed_json( $fb_single_events_array );
			// Create Cache.
			$this->fts_create_feed_cache( $fb_event_post_data_cache, $response_event_post_array );

		}
		// SHOW THE $response_event_post_array FEED PRINT_R
		// '<pre>';.
		// print_r($response_event_post_array);
		// echo'</pre>';.
		return $response_event_post_array;
	}


	/**
	 * FB Social Button Placement
	 *
	 * @param string $fb_shortcode The facebook contents.
	 * @param string $access_token The Access Token.
	 * @param string $share_loc Language.
	 * @return string|void
	 * @since 2.0.1
	 */
	public function fb_social_btn_placement( $fb_shortcode, $access_token, $share_loc ) {
		// Don't do it for these!
		if ( 'group' === $fb_shortcode['type'] || 'event' === $fb_shortcode['type'] || isset( $fb_shortcode['hide_like_option'] ) && 'yes' === $fb_shortcode['hide_like_option'] ) {
			return;
		}
		// Facebook Follow Button Options.
		$fb_show_follow_btn = get_option( 'fb_show_follow_btn' );

		if ( isset( $fb_shortcode['show_follow_btn_where'] ) && '' !== $fb_shortcode['show_follow_btn_where'] ) {
			if ( 'above_title' === $fb_shortcode['show_follow_btn_where'] ) {
				$fb_show_follow_btn_where = 'fb-like-top-above-title';
			} elseif ( 'below_title' === $fb_shortcode['show_follow_btn_where'] ) {
				$fb_show_follow_btn_where = 'fb-like-top-below-title';
			} elseif ( 'bottom' === $fb_shortcode['show_follow_btn_where'] ) {
				$fb_show_follow_btn_where = 'fb-like-below';
			}
		} else {
			$fb_show_follow_btn_where = get_option( 'fb_show_follow_btn_where' );
		}

		if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

			$like_option_align_final = isset( $fb_shortcode['like_option_align'] ) ? 'fts-fb-social-btn-' . $fb_shortcode['like_option_align'] . '' : '';

			if ( $share_loc === $fb_show_follow_btn_where ) {
				switch ( $fb_show_follow_btn_where ) {
					case 'fb-like-top-above-title':
						// Top Above Title.
						if ( isset( $fb_show_follow_btn ) && 'dont-display' !== $fb_show_follow_btn ) {
							echo '<div class="fb-social-btn-top ' . esc_attr( $like_option_align_final ) . '">';
							$this->social_follow_button( 'facebook', $fb_shortcode['id'], $access_token, $fb_shortcode );
							echo '</div>';
						}
						break;
					// Top Below Title.
					case 'fb-like-top-below-title':
						if ( isset( $fb_show_follow_btn ) && 'dont-display' !== $fb_show_follow_btn ) {
							echo '<div class="fb-social-btn-below-description ' . esc_attr( $like_option_align_final ) . '">';
							$this->social_follow_button( 'facebook', $fb_shortcode['id'], $access_token, $fb_shortcode );
							echo '</div>';
						}
						break;
					// Bottom.
					case 'fb-like-below':
						if ( isset( $fb_show_follow_btn ) && 'dont-display' !== $fb_show_follow_btn ) {
							echo '<div class="fb-social-btn-bottom ' . esc_attr( $like_option_align_final ) . '">';
							$this->social_follow_button( 'facebook', $fb_shortcode['id'], $access_token, $fb_shortcode );
							echo '</div>';
						}
						break;
				}
			}
		}
	}

	/**
	 * FTS Custom Trim Words
	 *
     * Not using this anymore but keeping it as a fallback function for the combined if user has not updated the free version before the combined extension
     *
	 * @param string $text The description text.
	 * @param int    $num_words Number of words you want to be showm.
	 * @param string $more The ...
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_custom_trim_words( $text, $num_words = 45, $more ) {
		! empty( $num_words ) && 0 !== $num_words ? $more = __( '...' ) : '';
		$text = nl2br( $text );
		// Filter for Hashtags and Mentions Before returning.
		$text = $this->fts_facebook_tag_filter( $text );
		$text = strip_shortcodes( $text );
		// Add tags that you don't want stripped.
		$text        = strip_tags( $text, '<strong><br><em><i><a>' );
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;
		} else {
			$text = implode( $sep, $words_array );
		}
		return wpautop( $text );
	}

	/**
	 * FTS Facebook Tag Filter
	 *
	 * Tags Filter (return clean tags)
	 *
	 * @param string $fb_description Facebook Description.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_facebook_tag_filter( $fb_description ) {
		// Converts URLs to Links.
		$fb_description = preg_replace( '@(?!(?!.*?<a)[^<]*<\/a>)(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-‌​Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0" target="_blank">\0</a>', $fb_description );

		$splitano     = explode( 'www', $fb_description );
		$count        = count( $splitano );
		$return_value = '';

		for ( $i = 0; $i < $count; $i++ ) {
			if ( 'href=' === substr( $splitano[ $i ], -6, 5 ) ) {
				$return_value .= $splitano[ $i ] . 'http://www';
			} elseif ( $i < $count - 1 ) {
				$return_value .= $splitano[ $i ] . 'www';
			} else {
				$return_value .= $splitano[ $i ];
			}
		}
		// Mentions.
		$return_value = preg_replace( '/@+(\w+)/u', '<a target="_blank" href="https://www.facebook.com/$1">@$1</a>', $return_value );
		// Hash tags.
		$return_value = preg_replace( '/#+(\w+)/u', '<a target="_blank" href="https://www.facebook.com/hashtag/$1">#$1</a>', $return_value );

		return $return_value;
	}

	/**
	 * Load PopUp Scripts
	 *
	 * @param string $fb_shortcode The Facebook feed shortcode.
	 * @since 1.9.6
	 */
	public function load_popup_scripts( $fb_shortcode ) {
		if ( 'yes' === $fb_shortcode['popup'] ) {
			// it's ok if these styles & scripts load at the bottom of the page.
			$fts_fix_magnific = get_option( 'fts_fix_magnific' ) ? get_option( 'fts_fix_magnific' ) : '';
			if ( isset( $fts_fix_magnific ) && '1' !== $fts_fix_magnific ) {
				wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION, false );
			}
			wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION, false );
			wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
			if ( ! isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
				wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
			}
		}
	}

	/**
	 * FTS Facebook LoadMore
	 *
	 * @param string $atts The shortcode attributes.
	 * @param string $feed_data The Feed data.
	 * @param string $fb_type The type of facebook feed.
	 * @param string $fb_shortcode The Facebook feed shortcode.
	 * @since 1.9.6
	 */
	public function fts_facebook_loadmore( $atts, $feed_data, $fb_type, $fb_shortcode ) {
		if ( ( isset( $fb_shortcode['loadmore'] ) && 'button' === $fb_shortcode['loadmore'] || isset( $fb_shortcode['loadmore'] ) && 'autoscroll' === $fb_shortcode['loadmore'] ) && ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'reviews' !== $fb_shortcode['type'] || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && 'reviews' === $fb_shortcode['type'] ) ) {

			$fb_load_more_text       = get_option( 'fb_load_more_text' ) ? get_option( 'fb_load_more_text' ) : esc_html( 'Load More', 'feed-them-social' );
			$fb_no_more_posts_text   = get_option( 'fb_no_more_posts_text' ) ? get_option( 'fb_no_more_posts_text' ) : esc_html( 'No More Posts', 'feed-them-social' );
			$fb_no_more_photos_text  = get_option( 'fb_no_more_photos_text' ) ? get_option( 'fb_no_more_photos_text' ) : esc_html( 'No More Photos', 'feed-them-social' );
			$fb_no_more_videos_text  = get_option( 'fb_no_more_videos_text' ) ? get_option( 'fb_no_more_videos_text' ) : esc_html( 'No More Videos', 'feed-them-social' );
			$fb_no_more_reviews_text = get_option( 'fb_no_more_reviews_text' ) ? get_option( 'fb_no_more_reviews_text' ) : esc_html( 'No More Reviews', 'feed-them-social' );

			// Load More BUTTON Start.
			$next_url       = isset( $feed_data->paging->next ) ? $feed_data->paging->next : '';

			$posts          = isset( $fb_shortcode['posts'] ) ? $fb_shortcode['posts'] : '';
			$loadmore_count = isset( $fb_shortcode['loadmore_count'] ) && '' !== $fb_shortcode['loadmore_count'] ? $fb_shortcode['loadmore_count'] : '';
			// we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of posts.
			$_REQUEST['next_url'] = '' !== $loadmore_count ? str_replace( "limit=$posts", "limit=$loadmore_count", $next_url ) : $next_url;

			$access_token = is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? 'access_token='.get_option( 'fts_facebook_custom_api_token_biz' ) : 'access_token='.get_option( 'fts_facebook_custom_api_token' );
            $_REQUEST['next_url'] = str_replace( $access_token, "access_token=XXX", $next_url );

			echo '<script>';
			echo 'var nextURL_' . esc_js( $_REQUEST['fts_dynamic_name'] ) . '= "' . esc_url_raw( $_REQUEST['next_url'] ) . '";';
			echo '</script>';

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && ! empty( $fb_shortcode['loadmore'] ) ) {
				$fts_dynamic_name       = $_REQUEST['fts_dynamic_name'];
				$time                   = time();
				$nonce                  = wp_create_nonce( $time . 'load-more-nonce' );
				$fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
				echo '<script>';
				echo 'jQuery(document).ready(function() {';
				if ( 'autoscroll' === $fb_shortcode['loadmore'] ) {
					// this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode.
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").bind("scroll",function() {';
					echo 'if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {';
				} else {
					// this is where we do CLICK function to LOADMORE if  = button in shortcode.
					echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").click(function() {';
				}
				echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").addClass("fts-fb-spinner");';
				echo 'var button = jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").html("<div class=\'bounce1\'></div><div class=\'bounce2\'></div><div class=\'bounce3\'></div>");';
				echo 'console.log(button);';

				echo 'var yes_ajax = "yes";';
				echo 'var fts_d_name = "' . esc_js( $fts_dynamic_name ) . '";';
				echo 'var fts_security = "' . esc_js( $nonce ) . '";';
				echo 'var fts_time = "' . esc_js( $time ) . '";';

				echo 'var feed_name = "fts_facebook";';
				echo 'var loadmore_count = "posts=' . esc_js( $fb_shortcode['loadmore_count'] ) . '";';
				echo 'var feed_attributes = ' . json_encode( $atts ) . ';';

				echo 'jQuery.ajax({';
				echo 'data: {action: "my_fts_fb_load_more", next_url: nextURL_' . esc_js( $fts_dynamic_name ) . ', fts_dynamic_name: fts_d_name, feed_name: feed_name, loadmore_count: loadmore_count, feed_attributes: feed_attributes, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},';
				echo 'type: "GET",';
				echo 'url: "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '",';
				echo 'success: function( data ) {';
				echo 'console.log("Well Done and got this from sever: " + data);';
				if ( $fb_type && 'albums' === $fb_shortcode['type'] || $fb_type && 'album_photos' === $fb_shortcode['type'] && 'yes' !== $fb_shortcode['video_album'] || 'yes' === $fb_shortcode['grid'] ) {
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").append(data).filter(".' . esc_js( $fts_dynamic_class_name ) . '").html();';
					// if (isset($fb_shortcode['image_stack_animation']) && $fb_shortcode['image_stack_animation'] == 'yes') {.
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").masonry( "reloadItems");';
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").masonry("layout");';

					echo 'setTimeout(function() {';
					// Do something after 3 seconds
					// This can be direct code, or call to some other function.
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").masonry("layout");';
					echo '}, 500);';

					// }.
					echo 'if(!nextURL_' . esc_js( $_REQUEST['fts_dynamic_name'] ) . ' || nextURL_' . esc_js( $_REQUEST['fts_dynamic_name'] ) . ' == "no more"){';
					if ( 'reviews' === $fb_shortcode['type'] ) {
						echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_reviews_text ) . '</div>\');';
					} elseif ( 'videos' === $fb_shortcode['type'] ) {
						echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_videos_text ) . '</div>\');';
					} else {
						echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_photos_text ) . '</div>\');';
					}

					echo ' jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeAttr("id");';
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").unbind("scroll");';
					echo '}';
				} else {
					if ( isset( $fb_shortcode['video_album'] ) && 'yes' === $fb_shortcode['video_album'] ) {
						echo 'var result = jQuery(data).insertBefore( jQuery("#output_' . esc_js( $fts_dynamic_name ) . '") );';
						echo 'var result = jQuery(".feed_dynamic_' . esc_js( $fts_dynamic_name ) . '_album_photos").append(data).filter("#output_' . esc_js( $fts_dynamic_name ) . '").html();';
					} else {
						echo 'var result = jQuery("#output_' . esc_js( $fts_dynamic_name ) . '").append(data).filter("#output_' . esc_js( $fts_dynamic_name ) . '").html();';
					}
					echo 'jQuery("#output_' . esc_js( $fts_dynamic_name ) . '").html(result);';
					echo 'if(!nextURL_' . esc_js( $_REQUEST['fts_dynamic_name'] ) . ' || nextURL_' . esc_js( $_REQUEST['fts_dynamic_name'] ) . ' == "no more"){';
					// Reviews.
					if ( 'reviews' === $fb_shortcode['type'] ) {
						echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_reviews_text ) . '</div>\');';
					} else {
						echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_posts_text ) . '</div>\');';
					}
					echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeAttr("id");';
					echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").unbind("scroll");';
					echo '}';

				}
				echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").html("' . esc_html( $fb_load_more_text ) . '");';
				// jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("flip360-fts-load-more");.
				echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeClass("fts-fb-spinner");';
				if ( isset( $fb_shortcode['popup'] ) && 'yes' === $fb_shortcode['popup'] ) {
					// We return this function again otherwise the popup won't work correctly for the newly loaded items.
					echo 'jQuery.fn.slickFacebookPopUpFunction();';
				}
				// Reload the share each funcion otherwise you can't open share option..
				echo 'jQuery.fn.ftsShare();slickremixImageResizingFacebook2();slickremixImageResizingFacebook3();';

				echo '}';
				echo '});';
				// end of ajax().
				echo 'return false;';
				// string $scrollMore is at top of this js script. acception for scroll option closing tag.
				if ( 'autoscroll' === $fb_shortcode['loadmore'] ) {
					echo '}';
					// end of scroll ajax load.
				}
				echo '});';
				// end of document.ready.
				echo '});';
				// end of form.submit.
				echo '</script>';
			}
			// End Check.
			// main closing div not included in ajax check so we can close the wrap at all times.
			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
				// this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;".
				echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more-output"></div>';
				if ( ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'reviews' !== $fb_shortcode['type'] || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && 'reviews' === $fb_shortcode['type'] ) && 'autoscroll' === $fb_shortcode['loadmore'] ) {
					echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Facebook</div>';
				}
			}
		}
		// end of if loadmore is button or autoscroll.
	}
	// end fts_facebook_loadmore().

	/**
	 * Random String
	 *
	 * Create a random string
	 *
	 * @param string $length How many character to randomize.
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