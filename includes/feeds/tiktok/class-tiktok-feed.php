<?php
/**
 * Feed Them Social - TikTok Feed
 *
 * This page is used to create the TikTok feed.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.2.1
 */

namespace feedthemsocial;

/**
 * TikTok Feed Class
 */
class Tiktok_Feed {

     /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settings_functions;

   /**
	 * Feed Functions
	 *
	 * General Feed Functions to be used in most Feeds.
	 *
	 * @var object
	 */
	public $feed_functions;

	/**
	 * Feed Cache
	 *
	 * Feed Cache class.
	 *
	 * @var object
	 */
	public $feed_cache;

    /**
	 * Access Options
	 *
	 * Access Options for tokens.
	 *
	 * @var object
	 */
	public $access_options;

	/**
	 * Constructor
	 *
	 * Twitter Feed constructor.
	 *
	 * @since 4.2.1
	 */
	public function __construct( $settings_functions, $feed_functions, $feed_cache, $access_options ) {
		$this->add_actions_filters();

        // Settings Functions Class.
        $this->settings_functions = $settings_functions;

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Set Feed Cache object.
		$this->feed_cache = $feed_cache;

		// Access Options for tokens.
		$this->access_options = $access_options;
	}

	/**
	 * Add Actions & Filters
	 *
	 * Adds the Actions and filters for the class.
	 *
	 * @since 4.0.0
	 */
	public function add_actions_filters() {

		add_action( 'wp_enqueue_scripts', array( $this, 'tiktok_head' ) );
	}

    /**
	 * FTS TikTok Head
	 *
	 * Add Styles and Scripts functions.
	 *
	 * @since 2.9.6.5
	 */
    public function tiktok_head() {
        // no actions or filters to load at this time.
    }

	/**
	 * Description
	 *
     * The description text.
     *
	 * @param array $post_data Post data.
	 * @return string
	 * @since 4.2.1
	 */
	public function description( $post_data, $word_count, $more ) {

		$text = isset( $post_data->title ) ? $post_data->title : '';

		// Message. Convert links to real links.
		$pattern   = array( '/http:(\S)+/', '/https:(\S)+/', '/@+(\w+)/u', '/#+(\w+)/u' );
		$replace   = array( ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="https://www.tiktok.com/@$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="https://www.tiktok.com/tag/$1" target="_blank" rel="nofollow">#$1</a>' );
		$full_text = preg_replace( $pattern, $replace, $text );

		if ( is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) && $word_count !== '' ) {
			$truncate_words = new \FeedThemSocialTruncateHTML();
			$full_text = $truncate_words::fts_custom_trim_words( $full_text, $word_count, $more );
		}

		return nl2br( $full_text );
	}

	/**
	 * TikTok Image
	 *
	 * @param string $post_data The post data.
	 * @param string $popup Our Custom popup.
	 * @return string
	 * @since 4.2.1
	 */
	public function tiktok_image( $post_data, $popup, $hide_images_in_posts ) {

		$screen_name = isset( $post_data->user->screen_name ) ?  $post_data->user->screen_name : '';
		$post_id = isset( $post_data->id ) ?  $post_data->id : '';
		$permalink  =  isset( $post_data->share_url ) ?  $post_data->share_url : '';
		$twitter_video_extended = isset( $post_data->extended_entities->media[0]->type ) ? $post_data->extended_entities->media[0]->type : '';

		if ( is_array( $popup ) ) {
		    // This is specific to function fts_twitter_external_link_wrap. This is the only function where we pass an array through the $popup string.
	        $details_array = (object)$popup;
            $popup = $details_array->popup;
            $permalink = $details_array->post_url;
            $media_url = $details_array->image;
			$media_addition = 'yes';
		} else {

			$media_url = $post_data->cover_image_url;
		}

		if ( $popup === 'yes' ){
			return '<a href="https://www.tiktok.com/embed/v2/'.$post_id.'" class="fts-tiktok-image fts-tiktok-popup-open"><img class="fts-twitter-description-image" src="' . esc_url( $media_url ) . '" alt="' .  esc_attr( $screen_name ) . ' photo"/></a>';
		}
		else {
			return '<a href="' . esc_url( $permalink ) . '" target="_blank" class="fts-tiktok-image"><img class="fts-twitter-description-image" src="' . esc_url( $media_url ) . '" alt="' .  esc_attr( $screen_name ) . ' photo"/></a>';
		}
	}

	/**
	 * TikTok Permalink
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 4.2.1
	 */
	public function comment_count( $post_data ) {
		$comment_count = isset( $post_data->comment_count ) && $post_data->comment_count !== '0' ? $post_data->comment_count : '';

		if( $comment_count !== 0) {
			return '<span class="fts-tiktok-comment-count">' . esc_html( $comment_count ) . '</span>';
		}
	}

	/**
	 * TikTok Count
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 4.2.1
	 */
	public function like_count( $post_data ) {
        // Retweet count.
        $like_count = isset( $post_data->like_count ) && '0' !== $post_data->like_count ? $post_data->like_count : '';

		if( $like_count !== 0) {
			return '<span class="fts-tiktok-like-count">' . esc_html( $like_count ) . '</span>';
		}
	}

	/**
	 * TikTok Favorite Count
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 4.2.1
	 */
	public function view_count( $post_data ) {
        // Favorite count.
        $view_count = isset( $post_data->view_count ) && '0' !== $post_data->view_count ? $post_data->view_count : '';

		if( $view_count !== 0) {
			return '<span class="fts-tiktok-views-count">' . esc_html( $view_count ) . '</span>';
		}
	}

     /**
     * TikTok Custom Styles
     *
     * Custom Styles for feed in a shortcode.
     *
     * @param string $a First Date.
     * @return false|int
     * @since 4.2.1
     */
    public function tiktok_custom_styles( $feed_post_id ) {

        $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_post_id );
        ?>
        <style type="text/css">
        <?php
            $twitter_text_size                   = $saved_feed_options['twitter_text_size'] ?? '';
            $twitter_text_color                  = $saved_feed_options['twitter_text_color'] ?? '';
            $twitter_link_color                  = $saved_feed_options['twitter_link_color'] ?? '';
            $twitter_link_color_hover            = $saved_feed_options['twitter_link_color_hover'] ?? '';
            $twitter_feed_width                  = $saved_feed_options['twitter_feed_width'] ?? '';
            $twitter_feed_margin                 = $saved_feed_options['twitter_feed_margin'] ?? '';
            $twitter_feed_padding                = $saved_feed_options['twitter_feed_padding'] ?? '';
            $twitter_feed_background_color       = $saved_feed_options['twitter_feed_background_color'] ?? '';
            $twitter_border_bottom_color         = $saved_feed_options['twitter_border_bottom_color'] ?? '';
            $twitter_max_image_width             = $saved_feed_options['twitter_max_image_width'] ?? '';
            $twitter_grid_border_bottom_color    = $saved_feed_options['twitter_grid_border_bottom_color'] ?? '';
            $twitter_grid_posts_background_color = $saved_feed_options['twitter_grid_posts_background_color'] ?? '';
            $twitter_loadmore_background_color   = $saved_feed_options['twitter_loadmore_background_color'] ?? '';
            $twitter_loadmore_text_color         = $saved_feed_options['twitter_loadmore_text_color'] ?? '';

            $fts_social_icons_color              = $this->settings_functions->fts_get_option( 'social_icons_text_color' ) ;
            $fts_social_icons_hover_color        = $this->settings_functions->fts_get_option( 'social_icons_text_color_hover' );
            $fts_social_icons_back_color         = $this->settings_functions->fts_get_option( 'icons_wrap_background' );

            if ( ! empty( $twitter_grid_posts_background_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                background: <?php echo esc_html( $twitter_grid_posts_background_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_grid_border_bottom_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                border-bottom-color: <?php echo esc_html( $twitter_grid_border_bottom_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_loadmore_background_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $twitter_loadmore_background_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }

            <?php }

             if ( ! empty( $twitter_text_color ) ) { ?>
            .tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before, a span.fts-video-loading-notice {
                color: <?php echo esc_html( $twitter_text_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_link_color ) ) { ?>
            .tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a, .twitter-followers-fts a, body.fts-twitter-reply-wrap a {
                color: <?php echo esc_html( $twitter_link_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_link_color_hover ) ) { ?>
            .tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply, body.fts-twitter-reply-wrap a:hover {
                color: <?php echo esc_html( $twitter_link_color_hover ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_feed_width ) ) { ?>
            .fts-twitter-div, .fts-tiktok-bio-profile-wrap {
                max-width: <?php echo esc_html( $twitter_feed_width ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_feed_margin ) ) { ?>
            .fts-twitter-div {
                margin: <?php echo esc_html( $twitter_feed_margin ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_feed_padding ) ) { ?>
            .fts-twitter-div {
                padding: <?php echo esc_html( $twitter_feed_padding ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_feed_background_color ) ) { ?>
            .fts-twitter-div {
                background: <?php echo esc_html( $twitter_feed_background_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_border_bottom_color ) ) { ?>
            .tweeter-info {
                border-bottom: 1px solid <?php echo esc_html( $twitter_border_bottom_color ); ?> !important;
            }
			<?php }

            if ( ! empty( $twitter_max_image_width ) ) { ?>
            .fts-tiktok-popup {
                max-width: <?php echo esc_html( $twitter_max_image_width ); ?> !important;
                display: block;
            }
			<?php }

              if ( ! empty( $twitter_text_size ) ) {
             ?>
            span.fts-twitter-text {
                font-size: <?php echo esc_html( $twitter_text_size ); ?> !important;
            }

			<?php
            }
              if ( ! empty( $fts_social_icons_color ) ) {
	        ?>
            .ft-gallery-share-wrap a i:before {
                color: <?php echo esc_html( $fts_social_icons_color ); ?> !important;
            }
			<?php
            }

            if ( ! empty( $fts_social_icons_hover_color ) ) {
	        ?>
            .ft-gallery-share-wrap a i:hover:before {
                color: <?php echo esc_html( $fts_social_icons_hover_color ); ?> !important;
            }
			<?php
            }

            if ( ! empty( $fts_social_icons_back_color ) ) {
	        ?>
            .ft-gallery-share-wrap {
                background: <?php echo esc_html( $fts_social_icons_back_color ); ?> !important;
            }
			<?php } ?>
        </style><?php
    }

	/**
     * Load Popup Scripts
     *
     * @param array $premium_options The feed options saved in the CPT but loaded in tiktok Premium.
     * @since 4.2.1
     */
    public function load_popup_scripts( $premium_options ) {
        if ( isset( $premium_options['popup']  ) && $premium_options['popup'] === 'yes' ) {
            // It's ok if these styles & scripts load at the bottom of the page.
            $fts_fix_magnific = $this->settings_functions->fts_get_option( 'remove_magnific_css' ) ?? '';
            if ( isset( $fts_fix_magnific ) && $fts_fix_magnific !== '1' ) {
                wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.min.css' ), array(), FTS_CURRENT_VERSION, false );
            }
            wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
            wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
        }
    }

	/**
	 * Format Count
	 *
	 * Display Numbers in specific format.
	 *
	 * @param integer $count The count of videos, following, followers, likes.
	 * @return string
	 * @since 4.2.3
	 */
	function format_count($count) {
		if ($count <= 9999) {
			return number_format((float) $count);
		}
		elseif ($count >= 1000000) {
			return round(($count / 1000000), 1) . 'm';
		}
		elseif ($count >= 10000) {
			return round(($count / 1000), 1) . 'k';
		}
		return $count;
	}

	/**
	 * Display TikTok
	 *
	 * Display TikTok Feed.
	 *
	 * @param integer $feed_post_id The ID of the Feed's CPT Post.
	 * @return array
	 * @since 4.2.1
	 */
	public function display_tiktok( $feed_post_id ) {

            // SRL: this needs attention on because this will not load proper on block based themes. ie* 2022, 2020 WordPress Themes.
            // Here is a fix reference. https://wordpress.org/support/topic/issue-with-wp_localize_script-or-similar-on-block-themes/
            wp_localize_script( 'fts-global-js', 'fts_twitter_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            // Saved Feed Settings!
            $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_post_id );

            // Get our Additional Options.
            $this->tiktok_custom_styles( $feed_post_id );

             /*echo'<pre>';
             print_r($saved_feed_options);
             echo'</pre>';*/

            $tiktok_user_id = $saved_feed_options['fts_tiktok_user_id'] ?? '';
            // Show Follow Button.
            $twitter_show_follow_btn       = $saved_feed_options['twitter_show_follow_btn'] ?? '';
            // Location of Show Follow Button.
            $twitter_show_follow_btn_where = $saved_feed_options['twitter_show_follow_btn_where'] ?? '';
			$tiktok_follow_on_tiktok	   = $saved_feed_options['tiktok_follow_on_tiktok'] ?? 'Follow on TikTok';
            // TikTok Count.
            $video_count = $saved_feed_options['tweets_count'] ?? '6';
            // Twitter Height.
            $twitter_height = $saved_feed_options['twitter_height'] ?? '';
            // Feed Type.
            $type = $saved_feed_options['twitter-messages-selector'] ?? '';

			$tiktok_hide_profile_photo 	     = $saved_feed_options['tiktok_hide_profile_photo'] ?? '';
			$tiktok_columns 				 = $saved_feed_options['tiktok_columns'] ?? '';
            $tiktok_columns_tablet 		     = $saved_feed_options['tiktok_columns_tablet'] ?? '';
            $tiktok_columns_mobile 		     = $saved_feed_options['tiktok_columns_mobile'] ?? '';
			$tiktok_force_columns 			 = $saved_feed_options['tiktok_force_columns'] ?? '';
			$tiktok_space_between_photos     = $saved_feed_options['tiktok_space_between_photos'] ?? '1px';
			$tiktok_icon_size 				 = $saved_feed_options['tiktok_icon_size'] ?? '';
			$tiktok_hide_date_likes_comments = $saved_feed_options['tiktok_hide_date_likes_comments'] ?? '';
			$tiktok_image_height			 = $saved_feed_options['tiktok_image_height'] ?? '';

            // Stats Bar.
            $stats_bar					    = $saved_feed_options['twitter_stats_bar'] ?? '';
            $stats_bar_profile_photo 	    = $saved_feed_options['tiktok_show_stats_profile_photo'] ?? '';
            $stats_bar_follow_btn 		    = $saved_feed_options['tiktok_show_stats_follow_btn'] ?? '';
            $stats_bar_follow_button_inline = $saved_feed_options['tiktok_show_follow_button_inline'] ?? '';
            $stats_bar_counts 				= $saved_feed_options['tiktok_show_stats_counts'] ?? '';
            $stats_bar_description 			= $saved_feed_options['tiktok_show_stats_description'] ?? '';
            $twitter_hide_profile_photo     = $saved_feed_options['twitter_hide_profile_photo'] ?? '';
            $hide_images_in_posts           = $saved_feed_options['twitter_hide_images_in_posts'] ?? '';

			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			$load_popup_scripts = false;

			// option to allow this action or not from the Twitter Options page.
			if ( is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) ) {

				$fts_tiktok_premium = new \feed_them_social_tiktok_premium\TikTok_Feed( $this->feed_functions, null);
				$premium_options = $fts_tiktok_premium->tiktok_premium_options( $saved_feed_options );
				$popup				   = $premium_options['popup'] ?? '';
				$loadmore              = $premium_options['loadmore'] ?? '';
				$loadmore_style        = $premium_options['loadmore_style'] ?? '';
				$loadmore_btn_margin   = $premium_options['loadmore_btn_margin'] ?? '';
				$loadmore_btn_maxwidth = $premium_options['loadmore_btn_maxwidth'] ?? '';
				$grid                  = $premium_options['grid'] ?? '';
				$column_width          = $premium_options['column_width'] ?? '';
				$space_between_posts   = $premium_options['space_between_posts'] ?? '';
				$load_more_text        = $premium_options['load_more_text'] ?? '';
				$no_more_posts_text    = $premium_options['no_more_posts_text'] ?? '';
				$load_popup_scripts    = $premium_options['load_popup_scripts'] ?? false;
			}
			else {
				$grid = 'no';
				$popup = 'no';
			}
			 // Load Pop Up Scripts
			if( $load_popup_scripts ){
				$this->load_popup_scripts( $premium_options );
			}

            // Premium TikTok Count Check!
			if ( ! is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) && $video_count > '6' ) {
				$video_count = '6';
			}

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				$_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feed_functions->get_random_string() . '_' . 'twitter' );
				if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
					$fts_dynamic_class = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
				}
			}
			// Create Dynamic Class Name.
			$fts_dynamic_class_name = $fts_dynamic_class ?? '';

			// Data Cache Name!
			$data_cache = 'tiktok_data_cache_' . $tiktok_user_id . '_num' . $video_count;

			    //Access Tokens Options.
                $fts_tiktok_access_token  = !empty( $saved_feed_options['fts_tiktok_access_token'] ) ? $saved_feed_options['fts_tiktok_access_token'] : '';

                if ( empty( $fts_tiktok_access_token ) ) {
				    // NO Access tokens found.
				    ?>
					<div class="fts-shortcode-content-no-feed fts-empty-access-token">
						<?php echo esc_html( 'Feed Them Social: TikTok Feed not loaded, please add your Access Token from the Gear Icon Tab.', 'feed-them-social' ); ?>
					</div>
					<?php
					return false;
			    }

			if ( $this->feed_cache->fts_check_feed_cache_exists( $data_cache ) !== false && ! isset( $_GET['load_more_ajaxing'] ) ) {

				$fetched_data = $this->feed_cache->fts_get_feed_cache( $data_cache );
				/*echo 'Cached TikTok Feed...';
				echo '<pre>';
                	print_r( $fetched_data );
                echo '</pre>';*/
			}
			else {

			// Decrypt Access Token? Turning this off for TikTok Feed because Tokens refresh every 24hrs.
			// $decrypted_access_token = $this->feed_access_token = $this->access_options->decrypt_access_token( $fts_tiktok_access_token );
			// $user_open_id = $saved_feed_options['fts_tiktok_user_id'];

			// if(  !isset( $_GET['load_more_ajaxing'] ) ) {
				 // Need to double check turning on the if(  !isset( $_GET['load_more_ajaxing'] ) ){  above. I believe we need to send
				 // an empty array to the multi_data array if we are loading more so this does not error out.
				 // Prepare the user data request data with headers
				 // https://developers.tiktok.com/doc/tiktok-api-v2-get-user-info/
				 //

				 $user_feed_data = array(
					 'url' => 'https://open.tiktokapis.com/v2/user/info/?fields=union_id,avatar_url,avatar_url_100,avatar_large_url,display_name,bio_description,profile_deep_link,follower_count,following_count,likes_count,video_count',
					 'headers' => array(
						 'Authorization' => 'Bearer ' . $fts_tiktok_access_token
					 ),
					 'feed_type' => 'tiktok',
				 );

				 $data['user_data'] = $user_feed_data;
				 $fetched_user_data = $this->feed_functions->fts_get_feed_json( $data );
				 $fetched_data_user = json_decode( $fetched_user_data['user_data'] );
			// }
			// else {
			//	 $fetched_data_user = '';
			// }


				$body_array = array('max_count' => $video_count);

				if( isset( $_GET['load_more_ajaxing'] ) && is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) ){
					$since_id = $fts_tiktok_premium->tiktok_connection( $_REQUEST['since_id'] );
					if (!empty($since_id)) {
						$body_array['cursor'] = $since_id;
					}
				}

				// Get the feed data
				// https://developers.tiktok.com/doc/tiktok-api-v2-video-list/
				$video_feed_request = array(
					'url' => 'https://open.tiktokapis.com/v2/video/list/?fields=cover_image_url,id,title',
					'headers' => array(
						'Authorization' => 'Bearer ' . $fts_tiktok_access_token,
						'Content-Type' => 'application/json'
					),
					'body' => json_encode($body_array),
					'method' => 'POST'
				);

				$multi_data_video['feed_data'] = $video_feed_request;
				$video_feed_response = $this->feed_functions->fts_get_feed_json($multi_data_video);
				$fetched_data_videos = json_decode($video_feed_response['feed_data']);


				/*echo '<pre>';
				error_log( print_r($fetched_data_videos, true));
				echo '</pre>';*/



				// Extract video IDs
				$video_ids = array();
				if (isset($fetched_data_videos->data->videos) && !empty($fetched_data_videos->data->videos)) {
					foreach ($fetched_data_videos->data->videos as $video) {
						if (isset($video->id)) {
							$video_ids[] = $video->id;
						}
					}
				}
				/*echo '<pre>';
				error_log( print_r($video_ids, true));
				echo '</pre>';*/

				// Prepare the new API call data
				// https://developers.tiktok.com/doc/tiktok-api-v2-video-query/
				$video_query_data = array(
					'url' => 'https://open.tiktokapis.com/v2/video/query/?fields=id,duration,height,width,like_count,comment_count,share_count,view_count,create_time,share_url',
					'headers' => array(
						'Authorization' => 'Bearer ' . $fts_tiktok_access_token,
						'Content-Type' => 'application/json'
					),
					'body' => json_encode(array(
						'filters' => array(
							'video_ids' => $video_ids
						)
					)),
					'method' => 'POST'
				);

				// Add this new call to your multi_data array
				$multi_data_video_final['video_query'] = $video_query_data;
				$final_data = $this->feed_functions->fts_get_feed_json( $multi_data_video_final );
				$video_return_data = json_decode($final_data['video_query'], true);

				/*echo '<pre>';
				error_log( 'wtf' . print_r($video_return_data, true));
				echo '</pre>';*/

				// Step 1: Create a map of video query results
				$video_details_map = array();
				if (isset($video_return_data['data']['videos']) && is_array($video_return_data['data']['videos'])) {
					foreach ($video_return_data['data']['videos'] as $video_details) {
						if (isset($video_details['id'])) {
							$video_details_map[$video_details['id']] = $video_details;
						}
					}
				}


				// Step 2: Merge detailed data into feed data
				if (isset($fetched_data_videos->data->videos) && !empty($fetched_data_videos->data->videos)) {
					foreach ($fetched_data_videos->data->videos as $key => $video) {
						if (isset($video->id) && isset($video_details_map[$video->id])) {
							// Merge the detailed data into the video item
							foreach ($video_details_map[$video->id] as $detailKey => $detailValue) {
								$video->{$detailKey} = $detailValue;
							}
							// Update the video item in the original list
							$fetched_data_videos->data->videos[$key] = $video;
						}
					}
				}
				/*echo '<pre>';
				error_log( 'wtf' . print_r($fetched_data_videos, true));
				echo '</pre>';*/

				// Encode $fetched_data_videos into a JSON string
				$fetched_data_videos_json = json_encode($fetched_data_videos);
				$fetched_data_user_json = json_encode($fetched_data_user);

				// Merge User Data and Video Feed Data
				$fetched_data = array(
					'user_data' => $fetched_data_user_json,
					'feed_data' => $fetched_data_videos_json
				);

				/*// TikTok Error Check.
                echo 'WTF <pre>';
                print_r( $fetched_data['user_data'] );
                echo '</pre>';

                return;*/

				// Cache It.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					$this->feed_cache->fts_create_feed_cache( $data_cache, $fetched_data );

					// echo 'Caching TikTok Feed...';
					/*echo '<pre>';
                		print_r( $fetched_data );
                	echo '</pre>';*/
				}
			}

			// TikTok Error Check.
			/*echo '<pre>';
			print_r( $fetched_data);
			echo '</pre>';*/

			$user_data = json_decode( $fetched_data['user_data'] );
			$feed_data = json_decode( $fetched_data['feed_data'] );

			/*echo 'User Data<pre>';
			 print_r( $user_data);
			echo '</pre>';*/

			/*echo 'Feed Data<pre>';
			 print_r( $feed_data);
			echo '</pre>';*/

			// Error Check.
			if ( !empty($feed_data->data->error_msg ) ) {
				// No Tweets Found!
				$error_check = __( $feed_data->data->error_msg. ' Log ID:' . $feed_data->data->error_code, 'feed-them-social' );
			}
            // Start Outputting Feed.
			ob_start();
			// Did the fetch fail?
			if ( isset( $error_check ) ) {

				if ( current_user_can( 'administrator' ) ) {
					echo sprintf(
					 esc_html__( 'TikTok error message: '.$error_check.' Please click the Login and Get my Access Token button again from the %1$sfeed edit page%2$s', 'feed-them-social' ),
                            '<a href="'.site_url() . '/wp-admin/post.php?post='. $feed_post_id .'&action=edit#feed_setup" target="_blank">',
                            '</a>'
					 );
				} else {
					echo esc_html__( 'No TikTok videos available. Login as Admin to see more details.', 'feed-them-social' );
				}
			}
			else {

					// Need to make an option to allow users to show the display nicename or the @name.
					// I figure most people would want to display the nice name but who knows.
					$bio_nicename    = $user_data->data->user->display_name ?? '';
					$display_at_name = $feed_data->data->videos[0]->share_url ?? '';

					$user_permalink 	= $user_data->data->user->profile_deep_link ?? '';
					$bio_description    = $user_data->data->user->bio_description ?? '';

					// Find the position of '@'
					$atPos = strpos($display_at_name, '@');

					// Extract the substring after '@'
					if ($atPos !== false) {
						// display the @name, need to make an option for this.
						// $afterAt = substr($display_at_name, $atPos + 1);
						// do not display the @name
						$afterAt = substr($display_at_name, $atPos);

						// Find the position of the next slash after '@'
						$slashPos = strpos($afterAt, '/');

						// Extract the username
						$display_name = $slashPos !== false ? substr($afterAt, 0, $slashPos) : $afterAt;

					} else {
						$display_name = '';
					}

					$avatar_url_100   	= $user_data->data->user->avatar_url_100 ?? '';
					$avatar_url_large   = $user_data->data->user->avatar_large_url ?? '';

					// Make sure it's not ajaxing.
					if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

						if ( $stats_bar === 'no' && isset( $twitter_show_follow_btn ) && $twitter_show_follow_btn === 'yes' && $twitter_show_follow_btn_where === 'twitter-follow-above' ) {
							echo '<div class="tiktok-social-btn-top">';
							echo $this->feed_functions->social_follow_button( 'tiktok', $user_permalink, $saved_feed_options );
							echo '</div>';
						}

						// These need to be in this order to keep the different counts straight since I used either $video_count or $stats_followers_count throughout.
						if ( $stats_bar === 'yes' && $stats_bar_counts === 'yes' ) {

							$stats_video_count_check     = $user_data->data->user->video_count ?? '';
							$stats_followers_count_check = $user_data->data->user->follower_count ?? '';
							$stats_following_count_check = $user_data->data->user->following_count ?? '';
							$stats_likes_count_check   	 = $user_data->data->user->likes_count ?? '';
							
							/* Quick Testing
							$stats_video_count_check     = 300300;
							$stats_following_count_check = 300200;
							$stats_followers_count_check = 400200;
							$stats_likes_count_check     = 6000300;*/

							$stats_video_count 	   = isset($stats_video_count_check) ? $this->format_count($stats_video_count_check) : '';
							$stats_following_count = isset($stats_following_count_check) ? $this->format_count($stats_following_count_check) : '';
							$stats_followers_count = isset($stats_followers_count_check) ? $this->format_count($stats_followers_count_check) : '';
							$stats_likes_count 	   = isset($stats_likes_count_check) ? $this->format_count($stats_likes_count_check) : '';
						}

						if ( $stats_bar === 'yes' ) {
							$tiktok_show_follow_button_inline_class = ($saved_feed_options['tiktok_show_follow_button_inline'] ?? '') === 'yes' ? ' tiktok-show-follow-button-inline' : '';
							?>
							<div class="fts-tiktok-bio-profile-wrap<?php echo $tiktok_show_follow_button_inline_class ?>">
								<?php if ( $stats_bar_profile_photo === 'yes' ) { ?>
									<div class="fts-tiktok-bio-profile-pic"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"
											class="fts-twitter-username"><img class="twitter-image" src="<?php echo esc_url( $avatar_url_large ); ?>" alt="<?php echo esc_attr( $display_name ); ?>"/></a>
									</div>
								<?php } ?>
								<div class="tiktok-bio-names-follow-wrap">
									<div class="fts-tiktok-bio-wrap">
										<div class="fts-tiktok-bio-nicename"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><?php echo esc_html( $bio_nicename ); ?></a></div>
										<div class="fts-tiktok-bio-username"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><?php echo esc_html( $display_name ); ?></a></div>
									</div>
									<?php if ( $stats_bar_follow_btn === 'yes') { ?>
										<div class="fts-tiktok-bio-follow-button"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><?php echo esc_html( $tiktok_follow_on_tiktok ); ?></a></div>
									<?php } ?>
								</div>
									<?php if ( $stats_bar_counts === 'yes' ) { ?>
									<div class="fts-twitter-followers-wrap">
										<div class="twitter-followers-fts fts-tweets-first"><a href="<?php echo esc_url( $user_permalink ) ?>" target="_blank"><span><?php echo esc_html( $stats_video_count ) ?></span><?php echo esc_html( 'Videos', 'feed-them-social' ); ?></a></div>
										<div class="twitter-followers-fts fts-following-link-div"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><span><?php echo esc_html( $stats_following_count ); ?></span><?php echo esc_html( 'Following', 'feed-them-social' ); ?></a></div>
										<div class="twitter-followers-fts fts-followers-link-div"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><span><?php echo esc_html( $stats_followers_count ); ?></span><?php echo esc_html( 'Followers', 'feed-them-social' ); ?></a></div>
										<div class="twitter-followers-fts fts-likes-link-div"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><span><?php echo esc_html( $stats_likes_count ); ?></span><?php echo esc_html( 'Likes', 'feed-them-social' ); ?></a></div>
									</div>
									<?php } ?>
									<?php if ( $stats_bar_description === 'yes' ) { ?>
										<div class="fts-tiktok-bio-description"><?php echo esc_html( $bio_description ); ?></div>
									<?php } ?>
							</div>
							<?php
						}

						if ( isset( $grid ) && $grid === 'yes' ) {
						// Start Grid format where there is a new div wrapper ?>
				<div id="twitter-feed-<?php echo esc_attr( $display_name ); ?>" class="fts-slicker-twitter-posts masonry js-masonry <?php echo esc_attr( $fts_dynamic_class_name );
					if ( isset( $popup ) && 'yes' === $popup ) {?> popup-gallery-tiktok<?php } ?>" style='margin:0 auto' data-masonry-options='{"itemSelector": ".fts-tweeter-wrap", "isFitWidth": true, "transitionDuration": 0 }'>
						<?php }

						// Start of Classic feed wrapper
						elseif (isset( $type ) && $type === 'classic' && isset( $grid ) && $grid !== 'yes') {  ?>
						<div id="twitter-feed-<?php echo esc_attr( $display_name ); ?>" class="<?php echo esc_attr( $fts_dynamic_class_name ); ?> fts-twitter-div <?php
									if ( ! empty( $twitter_height ) && 'auto' !== $twitter_height ) {
										?>fts-twitter-scrollable<?php
									}
									if ( isset( $popup ) && $popup === 'yes' ) {?> popup-gallery-tiktok<?php } ?>"
								<?php
								if ( ! empty( $twitter_height ) && $twitter_height !== 'auto' ) {?>style="height:<?php echo esc_attr( $twitter_height ); ?>"<?php } ?>>
								<?php }

						// Start of Responsive feed wrapper
						elseif  (isset( $type ) && $type === 'responsive' && isset( $grid ) && $grid !== 'yes') { ?>
						<div id="twitter-feed-<?php echo esc_attr( $display_name ); ?>" data-ftsi-columns="<?php echo esc_attr( $tiktok_columns ); ?>" data-ftsi-columns-tablet="<?php echo esc_attr( $tiktok_columns_tablet ); ?>" data-ftsi-columns-mobile="<?php echo esc_attr( $tiktok_columns_mobile ); ?>" data-ftsi-height="<?php echo esc_attr( $tiktok_image_height ); ?>" data-ftsi-margin="<?php echo esc_attr( $tiktok_space_between_photos ); ?>" data-ftsi-width="<?php echo isset( $saved_feed_options['tiktok_page_width'] ) ? esc_attr( $saved_feed_options['tiktok_page_width']  ) : ''; ?>" class="fts-twitter-div <?php echo 'fts-instagram-inline-block-centered ' . esc_attr( $fts_dynamic_class_name );
							if ( ! empty( $twitter_height ) && 'auto' !== $twitter_height ) {
								?> fts-twitter-scrollable<?php
							}
							if ( isset( $popup ) && $popup === 'yes' ) { echo ' popup-gallery-tiktok'; }else {  echo ' no-popup-gallery-tiktok'; }?>"
							<?php if ( ! empty( $twitter_height ) && $twitter_height !== 'auto' ) {?>style="height:<?php echo esc_attr( $twitter_height ); ?>"<?php } ?>>
						<?php }

					}// End if is not ajaxing

					/* echo'<pre>';
					 print_r($fetched_data->data);
                     echo'</pre>';

					 echo 'WTF';*/

					foreach ( $feed_data->data->videos as $post_data ) {

						$user_name            = $display_name ?? '';
						$word_count 		  = !empty( $saved_feed_options['tiktok_word_count'] ) ? $saved_feed_options['tiktok_word_count'] : '';
						$more 		  		  = '...';
						$description          = $this->description( $post_data, $word_count, $more );
						$image = $avatar_url_100 ?? '';
						// Need to get time in Unix format.
						$times = $post_data->create_time ?? '';
						// tied to date function.
						$feed_type = 'twitter';

						// call our function to get the date.
						$fts_date_time = $this->feed_functions->fts_custom_date( $times, $feed_type );

						$fts_dynamic_name = isset( $fts_dynamic_name ) ? $fts_dynamic_name : '';

						if( $type === 'classic' || isset( $grid ) && $grid === 'yes'){ ?>

							<div class="fts-tiktok-popup-grab fts-tweeter-wrap <?php echo esc_attr( $fts_dynamic_name ); ?>"
									<?php if ( isset( $grid ) && $grid === 'yes' ) {
										echo ' style="width:' . esc_attr( $column_width ) . '; margin:' . esc_attr( $space_between_posts ) . '"';
									} ?>>
								<div class="tweeter-info">

								<div class="fts-tiktok-popup-grab fts-right">
									<div class="fts-tiktok-content fts-tiktok-popup">
										  <div class="fts-tiktok-content-inner">
											<div class="fts-uppercase fts-bold">
												<?php if( $tiktok_hide_profile_photo === 'no' ){ ?>
													<a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"
													class="fts-twitter-username"><img class="twitter-image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $user_name ); ?>"/></a>
												<?php } ?>
												 <span class="fts-twitter-name-wrap">
													 <a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank" class="fts-twitter-full-name"><?php echo esc_html( $user_name ); ?></a>
													 <a href="<?php echo esc_url( $user_permalink ); ?>" class="fts-tiktok-time" target="_blank" title="<?php echo esc_html( $fts_date_time ); ?>"><?php echo esc_html( $fts_date_time ); ?></a>
												</span>
											</div>
											<span class="fts-tiktok-logo"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><i class="fa fa-tiktok"></i></a></span>
											<span class="fts-twitter-text">
											<?php
											echo wp_kses(
												$description,
												array(
													'a'      => array(
														'href'   => array(),
														'title'  => array(),
														'rel' 	 => array(),
														'target' => array(),
													),
													'br'     => array(),
													'em'     => array(),
													'strong' => array(),
													'small'  => array(),
												)
											);
											?>
												<div class="fts-twitter-caption">
													<a href="<?php echo esc_url( $user_permalink ); ?>" class="fts-view-on-twitter-link" target="_blank"><?php echo esc_html( 'View on Twitter', 'feed-them-social' ); ?></a>
												</div>
											</span>
										</div>
									</div>
									<?php
									$fts_image = $this->tiktok_image( $post_data, $popup, $hide_images_in_posts );
									echo $fts_image;
									?>
								</div>
								<div class="fts-twitter-reply-wrap fts-twitter-no-margin-left">
									<?php
									// TikTok permalink per post.
									$permalink = !empty( $post_data->share_url ) ? $post_data->share_url : '';
									?>
									<div class="fts-likes-shares-etc-wrap">
									<?php echo $this->feed_functions->fts_share_option( $permalink, $description ); ?>
									</div>
								</div>
								<div class="fts-twitter-reply-wrap-left fts-twitter-svg-addition">
									<div class="fts-tiktok-social-counts-wrap"><a href="<?php echo $permalink ?>" target="_blank" class="fts-jal-fb-see-more"><div class="fts-tiktok-social-counts"><?php echo $this->view_count( $post_data ); ?><?php echo $this->like_count( $post_data ); ?><?php echo $this->comment_count( $post_data ); ?><span class="fts-view-on-facebook"><?php echo esc_html( $saved_feed_options['tiktok_view_on_tiktok'] ) ?></span></div></a></div>
								</div>
								<div class="fts-clear"></div>
							</div><?php // <!--tweeter-info-->. ?>
							</div>

						<?php } // end classic feed type

						if( $type === 'responsive' && isset( $grid ) && $grid !== 'yes' ){ ?>

							<div class="slicker-instagram-placeholder fts-instagram-wrapper fts-tiktok-popup-grab fts-tiktok-responsive" style="background-image:url('<?php echo esc_url( $post_data->cover_image_url ); ?>')">
								<?php
								if ( is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) && isset( $popup ) && $popup === 'yes' ) { ?>
									<div class="fts-instagram-popup-profile-wrap">

										<div class="fts-tiktok-content fts-tiktok-popup">
											<div class="fts-tiktok-content-inner">
												<div class="fts-uppercase fts-bold">
													<?php if( $tiktok_hide_profile_photo === 'no' ){ ?>
														<a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"
														   class="fts-twitter-username"><img class="twitter-image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $user_name ); ?>"/></a>
													<?php } ?>
													<span class="fts-twitter-name-wrap">
															<a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank" class="fts-twitter-full-name"><?php echo esc_html( $user_name ); ?></a>
															<a href="<?php echo esc_url( $user_permalink ); ?>" class="fts-tiktok-time" target="_blank" title="<?php echo esc_html( $fts_date_time ); ?>"><?php echo esc_html( $fts_date_time ); ?></a>
												</span>
												</div>
												<span class="fts-tiktok-logo"><a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"><i class="fa fa-tiktok"></i></a></span>
												<span class="fts-twitter-text">
											<?php
											echo wp_kses(
												$description,
												array(
													'a'      => array(
														'href'   => array(),
														'title'  => array(),
														'rel' 	 => array(),
														'target' => array(),
													),
													'br'     => array(),
													'em'     => array(),
													'strong' => array(),
													'small'  => array(),
												)
											);
											?>
												<div class="fts-twitter-caption">
													<a href="<?php echo esc_url( $user_permalink ); ?>" class="fts-view-on-twitter-link" target="_blank"><?php echo esc_html( 'View on Twitter', 'feed-them-social' ); ?></a>
												</div>
											</span>
											</div>
										</div>

									</div>
									<?php
								}
								$popup_options = is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) && isset( $popup ) && $popup === 'yes' ? 'https://www.tiktok.com/embed/v2/'. $post_data->id : $post_data->share_url;
								?>
								<a href='<?php echo $popup_options ?>' title='<?php echo wp_kses(
									$description,
									array(
										'br'     => array(),
										'em'     => array(),
										'strong' => array(),
										'small'  => array(),
									)
								); ?>' target="_blank" rel="noreferrer" class='fts-instagram-link-target fts-slicker-backg
								<?php
								if ( isset( $popup ) && $popup === 'yes' ) {
									?> fts-tiktok-popup-open <?php
								} else {
									?> fts-instagram-img-link<?php } ?>' style="height:<?php echo esc_attr( $tiktok_icon_size ); ?> !important; width:<?php echo esc_attr( $tiktok_icon_size ); ?>; line-height:<?php echo esc_attr( $tiktok_icon_size ); ?>; font-size:<?php echo esc_attr( $tiktok_icon_size ); ?>;"><span
										class="fts-instagram-icon"
										style="height:<?php echo esc_attr( $tiktok_icon_size ); ?>; width:<?php echo esc_attr( $tiktok_icon_size ); ?>; line-height:<?php echo esc_attr( $tiktok_icon_size ); ?>; font-size:<?php echo esc_attr( $tiktok_icon_size ); ?>;"></span></a>

								<div class='slicker-date'>
									<div class="fts-insta-date-popup-grab">
										<?php
										if ( isset($tiktok_hide_date_likes_comments) && $tiktok_hide_date_likes_comments === 'yes' ) {
											echo esc_html( $fts_date_time );
										} else {
											echo '&nbsp;'; }
										?>
									</div>
								</div>
								<div class='slicker-instaG-backg-link'>

									<div class='slicker-instaG-photoshadow'></div>
								</div>
								<div class="fts-insta-likes-comments-grab-popup">

									<div class="fts-likes-shares-etc-wrap">
										<?php
										// TikTok permalink per post.
										$permalink = !empty( $post_data->share_url ) ? $post_data->share_url : '';
										// this is already escaping in the function, escaping again will cause errors.
										echo $this->feed_functions->fts_share_option( $permalink, $description );
										?>
									</div>

									<div class="fts-twitter-reply-wrap-left fts-twitter-svg-addition slicker-heart-comments-wrap">
										<div class="fts-tiktok-social-counts-wrap">
											<a href="<?php echo $permalink ?>" target="_blank" class="fts-jal-fb-see-more">
												<div class="fts-tiktok-social-counts">
													<?php if ( isset($tiktok_hide_date_likes_comments) && $tiktok_hide_date_likes_comments === 'yes' ) {
														echo $this->view_count( $post_data );
														echo $this->like_count( $post_data );
														echo $this->comment_count( $post_data );
													} ?><span class="fts-view-on-facebook"><?php echo esc_html( $saved_feed_options['tiktok_view_on_tiktok'] ) ?></span>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>

						<?php } // end responsive feed type

					} // endforeach;.

					// Make sure it's not ajaxing.
					if ( ! isset( $_GET['load_more_ajaxing'] ) && isset( $loadmore, $loadmore_style ) &&  $loadmore === 'yes' && $loadmore_style === 'autoscroll' ) {

						$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
						// This div returns outputs our ajax request via jquery append html from above.
						echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '"></div>';
						if ( is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) ) {
							echo '<div class="fts-twitter-load-more-wrapper">';
							echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">' . esc_html( $load_more_text ) . '</div>';
							echo '</div>';
						}
					} ?>
					<div class="fts-clear"></div>
				</div>
					<?php
					// this makes it so the page does not scroll if you reach the end of scroll bar or go back to top.
					if ( ! empty( $twitter_height ) && $twitter_height !== 'auto' ) {
						?>
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
						jQuery('.fts-twitter-scrollable').isolatedScrollTwitter();</script>
						<?php
					}

			// Load More BUTTON Start.
			// Note: the cursor value is a UTC Unix timestamp in milli-seconds.
			// You can pass in a customized timestamp to fetch the user's videos posted before the provided timestamp.
			// https://developers.tiktok.com/doc/tiktok-api-v2-video-list/
			$_REQUEST['since_id'] = $feed_data->data->cursor ?? '';
			// Whether there is more videos. Check to see if has_more is (1) true or (blank) false.
			$_REQUEST['max_id'] = isset( $feed_data->data->has_more ) && $feed_data->data->has_more === true ? 'has more' : 'no more';

			if ( isset( $loadmore ) && $loadmore === 'yes' ) {
				?>
			<script>var sinceID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_js( $_REQUEST['since_id'] ); ?>";
				var maxID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['max_id'] ) ) ); ?>";
			</script>
					<?php
				}
				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && isset( $loadmore ) && $loadmore === 'yes' ) {
					$fts_dynamic_name = sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
					$time             = time();
					$nonce            = wp_create_nonce( $time . 'load-more-nonce' );
					?>
			<script>
				jQuery(document).ready(function () {
					<?php
					if ( 'autoscroll' === $loadmore_style && 'yes' === $loadmore ) { // Scroll function to LOADMORE if = autoscroll in shortcode.
						?>
					jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>").bind("scroll", function () {
						if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
						<?php
					} else {
						// Click function to Load more if = button in shortcode.
						?>
							jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").click(function () {
					<?php } ?>
								jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
								var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
								console.log(button);
								var yes_ajax = "yes";
								var feed_id = "<?php echo esc_js( $feed_post_id ); ?>";
								var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ); ?>";
								var fts_security = "<?php echo esc_js( $nonce ); ?>";
								var fts_time = "<?php echo esc_js( $time ); ?>";
								var feed_name = "feed_them_social";
								jQuery.ajax({
									data: {
										action: "my_fts_fb_load_more",
										since_id: sinceID_<?php echo sanitize_key( $fts_dynamic_name ); ?>,
										max_id: maxID_<?php echo sanitize_key( $fts_dynamic_name ); ?>,
										fts_dynamic_name: fts_d_name,
										load_more_ajaxing: yes_ajax,
										fts_security: fts_security,
										fts_time: fts_time,
										feed_name: feed_name,
										feed_id: feed_id
									},
									type: 'GET',
									url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
									success: function (data) {
										console.log('Well Done and got this from sever: ' + data);
										<?php if ( 'autoscroll' === $loadmore_style && 'yes' === $loadmore ) { ?>
											jQuery('#output_<?php echo esc_js( $fts_dynamic_name ); ?>').append(data).filter('#output_<?php echo esc_js( $fts_dynamic_name ); ?>').html();
										<?php }
										else { ?>
											jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').append(data).filter('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').html();
										<?php } ?>

										if ( maxID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?> === 'no more') {
											jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').addClass('no-more-posts-fts-fb').html('<?php echo esc_js( $no_more_posts_text ); ?>');
											jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').unbind('click').removeAttr('id');
											jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>").unbind('scroll');
										}

										jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php echo esc_js( $load_more_text ); ?>');

										jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');

										if (typeof ftsShare === 'function') {
											ftsShare(); // Reload the share each function otherwise you can't open share option
										}
										if( jQuery.isFunction(jQuery.fn.slickTickTokPopUpFunction() ) ){
											jQuery.fn.slickTickTokPopUpFunction(); // Reload this function again otherwise the popup won't work correctly for the newly loaded items
										}
										// Reload our margin for the demo
										if (typeof outputSRmargin === "function") {
											outputSRmargin(document.querySelector('#margin').value)
										}
										// Reload our image sizing function so the images show up proper
										slickremixImageResizing();
									<?php
									if ( isset( $grid ) && 'yes' === $grid ) {?>
										jQuery(".fts-slicker-twitter-posts").masonry("reloadItems");

										setTimeout(function () {
											jQuery(".fts-slicker-twitter-posts").masonry("layout");
										}, 500);
									<?php } ?>

									}
								}); // end of ajax()
								return false;
										<?php
										// string $loadmore_style is at top of this js script. exception for scroll option closing tag.
										if ( 'autoscroll' === $loadmore_style && 'yes' === $loadmore ) {
											?>
							}; // end of scroll ajax load.
									<?php } ?>
						}
					) // end of form.submit
					<?php if ( isset( $grid ) && 'yes' === $grid ) { ?>
						// We run this otherwise the videos that load in posts will overlap other posts.
						setTimeout(function () {
							jQuery(".fts-slicker-twitter-posts").masonry("layout");
							jQuery(".fts-slicker-twitter-posts").masonry("reloadItems");
						}, 1200);
					<?php } ?>

				}); // end of document.ready
			</script>
						<?php
				}//End Check.

				// Make sure it's not ajaxing.
				if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
					if ( is_plugin_active( 'feed-them-social-tiktok-premium/feed-them-social-tiktok-premium.php' ) && $loadmore_style === 'button' && $loadmore === 'yes' ) {
						echo '<div class="fts-clear"></div>';
						echo '<div class="fts-twitter-load-more-wrapper">';
						echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '"" style="';
						if ( isset( $loadmore_btn_maxwidth ) && ! empty( $loadmore_btn_maxwidth ) ) {
							echo 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
						}
						$loadmore_btn_margin = isset( $loadmore_btn_margin ) ? $loadmore_btn_margin : '10px';
						echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $load_more_text ) . '</div>';
						echo '</div>';
					}
				}
				// End Check.
				unset( $_REQUEST['since_id'], $_REQUEST['max_id'] );

				// Social Button Bottom.
				if ( ! isset( $_GET['load_more_ajaxing'] ) && $stats_bar === 'no' && isset( $twitter_show_follow_btn ) && $twitter_show_follow_btn === 'yes' && $twitter_show_follow_btn_where === 'twitter-follow-below' ) {
						echo '<div class="tiktok-social-btn-bottom">';
						echo $this->feed_functions->social_follow_button( 'tiktok', $user_permalink, $saved_feed_options );
						echo '</div>';
				}
				?>
				<script>
					// This needs to load here below the feed to load properly for
					// Elementor page preview, and also some types of tabs that use js to load.
					document.addEventListener("DOMContentLoaded", function(event) {
						if (typeof slickremixImageResizing === 'function') {
							slickremixImageResizing();
						}
						if (typeof ftsShare === 'function') {
							ftsShare();
						}
					});
				</script>
				<?php
			}
			// END ELSE.
			return ob_get_clean();
	}
}//end class