<?php
/**
 * Feed Them Social - Facebook Feed Post Types
 *
 * This page is used to create the Facebook Feed Post Types!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Facebook Feed Post Types
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Facebook_Feed_Post_Types {

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
     * Access Options
     *
     * Access Options for tokens.
     *
     * @var object
     */
    public $access_options;

	/**
	 * Construct
	 *
	 * Facebook Feed constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct( $feed_functions, $settings_functions, $access_options ) {

        // Settings Functions Class.
        $this->settings_functions = $settings_functions;

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

        // Access Options for tokens.
        $this->access_options = $access_options;
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
    public function facebook_tag_filter( $fb_description ) {
        // Convert URLs to links.
        $fb_description = preg_replace_callback('@(https?://\S+|www\.\S+)@i', function ($matches) {
            $url = $matches[0];
            // Prepend 'http://' if the URL starts with 'www.' and doesn't have 'http://'.
            if (strpos($url, 'www.') === 0 && strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
                $url = 'https://' . $url;
            }
            return '<a href="' . $url . '" target="_blank" rel="noreferrer">' . $matches[0] . '</a>';
        }, $fb_description);

        // Process email addresses.
        $emailRegex = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';
        $fb_description = preg_replace($emailRegex, '<a href="mailto:$0">$0</a>', $fb_description);

        // Process mentions.
        $mentionsRegex = '/(?<=\s|^)@(\w+)/';
        $fb_description = preg_replace($mentionsRegex, '<a href="https://www.facebook.com/$1" target="_blank" rel="noreferrer">@$1</a>', $fb_description);

        // Process hashtags.
        $hashtagsRegex = '/#([\p{L}\p{M}\w]+)/u';
        $fb_description = preg_replace($hashtagsRegex, '<a href="https://www.facebook.com/hashtag/$1" target="_blank" rel="noreferrer">#$1</a>', $fb_description);

        return $fb_description;
    }




    /**
	 * Feed Location Option
	 *
	 * Display Location flag and text
	 *
	 * @param string $facebook_post_places_id The ID.
	 * @param string $facebook_post_name The facebook page name.
	 * @param string $facebook_post_places_name The location name.
	 * @since 1.9.6
	 */
	public function feed_location_option( $facebook_post_places_id, $facebook_post_name, $facebook_post_places_name ) {
		echo '<div class="fts-fb-location-wrap">';
		echo '<div class="fts-fb-location-img"></div>';
		echo '<a href="' . esc_url( 'https://www.facebook.com/' . $facebook_post_places_id . '/' ) . '" class="fts-fb-location-link" target="_blank" rel="noreferrer">' . esc_attr( $facebook_post_name ) . '</a>';
		echo '<div class="fts-fb-location-name">' . esc_html( $facebook_post_places_name ) . '</div>';
		echo '</div>';
	}

	/**
	 * FTS Facebook Post Description
	 *
	 * @param string $fb_description The post description.
	 * @param array  $saved_feed_options The feed options saved in the CPT.
	 * @param string $facebook_post_type The type of feed.
	 * @param null   $fb_post_id The post ID.
	 * @param null   $fb_by The post by.
	 * @since 1.9.6
	 */
	public function facebook_post_desc( $fb_description, $saved_feed_options, $facebook_post_type, $fb_post_id = null, $fb_by = null ) {


		$trunacate_words = new \FeedThemSocialTruncateHTML();

		$fb_description = $this->facebook_tag_filter( $fb_description );
		$more           = isset( $more ) ? $more : '...';

		switch ( $facebook_post_type ) {
			case 'video_direct_response' :
			case 'video_inline' :
				echo '<div class="fts-jal-fb-description fb-id' . esc_attr( $fb_post_id ) . '">' . wp_kses(
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
				break;
			case 'photo':
				if ( 'album_photos' === $saved_feed_options['facebook_page_feed_type'] ) {
					if ( isset( $saved_feed_options['facebook_page_word_count'] ) ) {
                        $trunacate_words = new \FeedThemSocialTruncateHTML();
						$trimmed_content = $trunacate_words::fts_custom_trim_words( $fb_description, $saved_feed_options['facebook_page_word_count'] , $more );
						echo '<div class="fts-jal-fb-description fts-non-popup-text">' . wp_kses(
								nl2br( $trimmed_content ),
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
						if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup'] ) && 'yes' === $saved_feed_options['facebook_popup']  || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_video_album'] ) && isset( $saved_feed_options['facebook_video_album'] ) && 'yes' === $saved_feed_options['facebook_video_album'] ) {
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
					} elseif ( isset( $saved_feed_options['facebook_page_word_count'] ) && '0' !== $saved_feed_options['facebook_page_word_count']) {
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
				if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {

                    if ( !empty( $saved_feed_options['facebook_page_word_count'] ) ) {

                        $trunacate_words = new \FeedThemSocialTruncateHTML();
						$trimmed_content = $trunacate_words::fts_custom_trim_words( $fb_description, $saved_feed_options['facebook_page_word_count'] , $more );
						echo '<div class="fts-jal-fb-description">' . wp_kses(
								nl2br( $trimmed_content ),
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
					// Do for Default feeds or the
                    // Video gallery feed.
						if ( !empty( $saved_feed_options['facebook_page_word_count'] ) ) {
                            $trunacate_words = new \FeedThemSocialTruncateHTML();
							$trimmed_content = $trunacate_words::fts_custom_trim_words( $fb_description, $saved_feed_options['facebook_page_word_count'] , $more );
							echo '<div class="fts-jal-fb-description">' . wp_kses(
									$trimmed_content,
									array(
										'a'      => array(
											'href'   => array(),
											'title'  => array(),
											'target' => array(),
											'rel'    => array(),
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
										'href'   => array(),
										'title'  => array(),
										'target' => array(),
										'rel'    => array(),
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
							echo '<div>By: <a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer">' . esc_html( $fb_by ) . '<a/></div>';
						}
				}
				break;
			default:
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
					// here we trim the words for the links description text... for the premium version. The $saved_feed_options['facebook_page_word_count']string actually comes from the javascript.
					if ( ! empty( $saved_feed_options['facebook_page_word_count'] ) && $saved_feed_options['facebook_page_word_count'] ) {

                        $trunacate_words = new \FeedThemSocialTruncateHTML();
						$trimmed_content = $trunacate_words::fts_custom_trim_words( $fb_description, $saved_feed_options['facebook_page_word_count'] , $more );
						echo '<div class="jal-fb-description">' . wp_kses(
								nl2br( $trimmed_content ),
								array(
									'a'      => array(
										'href'   => array(),
										'title'  => array(),
										'target' => array(),
										'rel'    => array(),
                                        'class'    => array(),
									),
									'br'     => array(),
									'em'     => array(),
									'strong' => array(),
									'small'  => array(),
                                    'span'      => array(
                                        'class'   => array(),
                                    ),
								)
							) . '</div>';
					} elseif ( isset( $saved_feed_options['facebook_page_word_count'] ) && '0' !== $saved_feed_options['facebook_page_word_count']) {
						echo '<div class="jal-fb-description">' . wp_kses(
								nl2br( $fb_description ),
								array(
									'a'      => array(
										'href'   => array(),
										'title'  => array(),
										'target' => array(),
										'rel'    => array(),
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
					echo '<div class="jal-fb-description">' . wp_kses(
							nl2br( $fb_description ),
							array(
								'a'      => array(
									'href'   => array(),
									'title'  => array(),
									'target' => array(),
									'rel'    => array(),
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
					$lsc_array['likes'] = "<i class='icon-thumbs-up'></i>1";
				}
				if ( $fb_post_like_count > '1' ) {
					$lsc_array['likes'] = "<i class='icon-thumbs-up'></i>" . esc_html( $fb_post_like_count );
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
					$lsc_array['comments']        = "<i class='icon-comments'></i>1";
					$lsc_array['comments_thread'] = $comment_count_data;

				}
				if ( $fb_post_comments_count > '1' ) {
					$lsc_array['comments']        = "<i class='icon-comments'></i>" . $fb_post_comments_count;
					$lsc_array['comments_thread'] = $comment_count_data;
				}
			}
		}
		// Shares Count.
		if ( 0 === $fb_post_share_count || ! $fb_post_share_count ) {
			$lsc_array['shares'] = '';
		}
		if ( 1 === $fb_post_share_count ) {
			$lsc_array['shares'] = "<span class='fts-count-wrap fts-shares-wrap'><i class='icon-file'></i>1</span>";
		}
		if ( $fb_post_share_count > '1' ) {
			$lsc_array['shares'] = "<span class='fts-count-wrap fts-shares-wrap'><i class='icon-file'></i>" . $fb_post_share_count . '</span>';
		}
		return $lsc_array;
	}

	/**
	 * Facebook Post Caption
	 *
	 * @param string $fb_caption The post caption.
	 * @param array  $saved_feed_options The feed options saved in the CPT.
	 * @param string $facebook_post_type The type of feed.
	 * @param null   $fb_post_id The post ID.
	 * @since 1.9.6
	 */
	public function facebook_post_cap( $fb_caption, $saved_feed_options, $facebook_post_type, $fb_post_id = null ) {
		$trunacate_words = new \FeedThemSocialTruncateHTML();

		switch ( $facebook_post_type ) {
			case 'video_direct_response' :
			case 'video_inline' :
				$fb_caption = $this->facebook_tag_filter( str_replace( 'www.', '', $fb_caption ) );
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
					// here we trim the words for the links description text... for the premium version. The $saved_feed_options['facebook_page_word_count']string actually comes from the javascript.
					if ( isset( $saved_feed_options['facebook_page_word_count'] ) ) {
						$more            = isset( $more ) ? $more : '';

                        $trunacate_words = new \FeedThemSocialTruncateHTML();
						$trimmed_content = $trunacate_words::fts_custom_trim_words( $fb_caption, $saved_feed_options['facebook_page_word_count'] , $more );
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
						$fb_caption = $this->facebook_tag_filter( $fb_caption );
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
					$fb_caption = $this->facebook_tag_filter( $fb_caption );
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
	 * FTS Facebook Post See More
	 *
	 * Generate See More Button.
	 *
	 * @param string $fb_link The facebook link.
	 * @param array $lcs_array The lcs array.
	 * @param string $facebook_post_type The type of feed.
	 * @param string $fb_post_id The post id.
	 * @param array $saved_feed_options The feed options saved in the CPT.
	 * @param null   $fb_post_user_id The user id.
	 * @param null   $fb_post_single_id The single post id.
	 * @param null   $single_event_id The event id.
	 * @param string $post_data The post data.
	 * @since 1.9.6
	 */
     public function facebook_post_see_more($fb_link, $lcs_array, $facebook_post_type, $saved_feed_options, $facebook_post_username = null, $fb_post_id = null, $fb_post_user_id = null, $fb_post_single_id = null, $single_event_id = null, $post_data = null ) {
    //public function facebook_post_see_more( $fb_link, $lcs_array, $facebook_post_type, $fb_post_id = null, $saved_feed_options, $fb_post_user_id = null, $fb_post_single_id = null, $single_event_id = null, $post_data = null ) {

		$description = isset( $post_data->message ) ?? '';
		// SHOW THE FB FEED PRINT_R
        /* echo'<pre>';
		 print_r( $post_data);
		 echo'</pre>';*/

        $likes = !empty( $lcs_array['likes'] ) ? $lcs_array['likes'] : '';
        $comments = !empty( $lcs_array['comments'] ) ? $lcs_array['comments'] : '';
        $shares = !empty( $lcs_array['shares'] ) ? $lcs_array['shares'] : '';

		switch ( $facebook_post_type ) {
			case 'events':
				$single_event_id = 'https://www.facebook.com/events/' . $single_event_id;
				echo '<div class="fts-likes-shares-etc-wrap">';
				echo $this->feed_functions->fts_share_option( $single_event_id, $description );
				echo '<a href="' . esc_url( $single_event_id ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">' . esc_html( $saved_feed_options['facebook_view_on_facebook'] ) . '</a></div>';
				break;
            case 'album': // for posts that have more than one photo in them
            case 'photo':
				if ( ! empty( $fb_link ) ) {
					echo '<div class="fts-likes-shares-etc-wrap">';
					echo $this->feed_functions->fts_share_option( $fb_link, $description );
					echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">';
				} else {
					// exception for videos.
					$single_video_id = 'https://www.facebook.com/' . $fb_post_id;
					echo '<div class="fts-likes-shares-etc-wrap">';
					echo $this->feed_functions->fts_share_option( $single_video_id, $description );
					echo '<a href="' . esc_url( $single_video_id ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">';
				}
				if ( 'album_photos' === $saved_feed_options['facebook_page_feed_type'] && 'yes' === $saved_feed_options['facebook_hide_date_likes_comments'] ) {

					echo '<div class="hide-date-likes-comments-etc">' . wp_kses(
                            '<span class="fts-count-wrap fts-likes-wrap">' . $likes . '</span><span class="fts-count-wrap fts-comments-wrap">' . $comments . '</span><span class="fts-count-wrap fts-shares-wrap">' . $shares . '</span>',
                            array(
                                'a' => array(
                                    'href'  => array(),
                                    'title' => array(),
                                ),
                                'i' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ) . '</div>';
				} else {

                    echo '' . wp_kses(
                            '<span class="fts-count-wrap fts-likes-wrap">' . $likes . '</span><span class="fts-count-wrap fts-comments-wrap">' . $comments . '</span><span class="fts-count-wrap fts-shares-wrap">' . $shares . '</span>',
                            array(
                                'a' => array(
                                    'href'  => array(),
                                    'title' => array(),
                                ),
                                'i' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        );
				}
            echo '<span class="fts-view-on-facebook">' . esc_html( $saved_feed_options['facebook_view_on_facebook'] ) . '</span></a></div>';

            break;
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'albums':

            // Parse the query string from the URL
            $url_parsed = parse_url($fb_link, PHP_URL_QUERY);

            // Initialize $params as an array
            $params = [];
            parse_str($url_parsed, $params);

            // Check if all required parameters are present
            if (isset($params['fbid'], $params['id'], $params['aid'])) {
                // Construct the new album URL
                $new_album_url = str_replace(
                    'album.php?fbid=' . $params['fbid'] . '&id=' . $params['id'] . '&aid=' . $params['aid'],
                    'media/set/?set=a.' . $params['fbid'] . '.' . $params['aid'] . '.' . $params['id'],
                    $fb_link
                );
            } else {
                // Handle the case where one or more parameters are missing
                $new_album_url = $fb_link;
                // Optionally, you can set $new_album_url to a default value or handle the error differently
            }


				echo '<div class="fts-likes-shares-etc-wrap fts-albums-single-image">';
				echo '<div class="fts-albums-hide-main-album-link-in-popup">';
				echo $this->feed_functions->fts_share_option( $fb_link, $description );
				echo '<a href="' . esc_url( $new_album_url ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">';
				if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] && 'yes' === $saved_feed_options['facebook_hide_date_likes_comments'] ) {
				} else {

                    echo '' . wp_kses(
                            '<span class="fts-count-wrap fts-likes-wrap">' . $likes . '</span><span class="fts-count-wrap fts-comments-wrap">' . $comments . '</span>',
                            array(
                                'a' => array(
                                    'href'  => array(),
                                    'title' => array(),
                                ),
                                'i' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        );
				}
				echo '<span class="fts-view-on-facebook">' . esc_html( $saved_feed_options['facebook_view_on_facebook'] ) . '</span></a></div></div>';
				break;
            // SRL added case '': to account for posts with descriptions that have no video or photos and were possible made from status_type => mobile_status_update
            case '':
            default:
                if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' )  && $saved_feed_options['facebook_page_feed_type'] === 'reviews') {
                    if ( isset( $saved_feed_options['fb_reviews_remove_see_reviews_link'] ) && 'yes' !== $saved_feed_options['fb_reviews_remove_see_reviews_link'] ) {
						$fb_reviews_see_more_reviews_language = $saved_feed_options['fb_reviews_see_more_reviews_language'] ?? 'See More Reviews';

						$hide_see_more = $saved_feed_options['hide_see_more_reviews_link'] ?? 'yes';
						if ( 'yes' !== $hide_see_more ) {
							echo ' <a href="' . esc_url( 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/reviews' ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">' . esc_html( $fb_reviews_see_more_reviews_language, 'feed-them-social' ) . '</a>';
						}
					}
				}
                else {
                    $post_single_id = 'https://www.facebook.com/' . $fb_post_user_id . '_' . $fb_post_id;
                    echo '<div class="fts-likes-shares-etc-wrap">';
                    echo $this->feed_functions->fts_share_option( $post_single_id, $description );
                    echo '<a href="' . esc_url( $post_single_id ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-see-more">';

                    echo '' . wp_kses(
                            '<span class="fts-count-wrap fts-likes-wrap">' . $likes . '</span><span class="fts-count-wrap fts-comments-wrap">' . $comments . '</span><span class="fts-count-wrap fts-shares-wrap">' . $shares . '</span>',
                            array(
                                'a' => array(
                                    'href'  => array(),
                                    'title' => array(),
                                ),
                                'i' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ) . '<span class="fts-view-on-facebook">' . esc_html( $saved_feed_options['facebook_view_on_facebook'] ) . '</span></a></div>';
                }
				break;
		}
	}

	/**
	 * FTS Facebook Post Name
	 *
	 * @param string $fb_link The post link.
	 * @param string $fb_name The facebook name.
	 * @param string $facebook_post_type The type of feed.
	 * @param null   $fb_post_id The facebook post ID.
	 * @since 1.9.6
	 */
	public function fts_facebook_post_name( $fb_link, $fb_name, $facebook_post_type, $fb_post_id = null ) {

		switch ( $facebook_post_type ) {
			case 'video_direct_response' :
			case 'video_inline' :
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-name fb-id' . esc_attr( $fb_post_id ) . '">' . wp_kses(
						$this->facebook_tag_filter( $fb_name ),
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
				$fb_name = $this->facebook_tag_filter( $fb_name );
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-name">' . wp_kses(
						$this->facebook_tag_filter( $fb_name ),
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
	 * Facebook Post Photo
	 *
	 * @param string $fb_link The link to post.
	 * @param array  $saved_feed_options The feed options saved in the CPT.
	 * @param string $photo_from Who it's from.
	 * @param string $photo_source The source url.
	 * @since 1.9.6
	 */
	public function facebook_post_photo( $fb_link, $saved_feed_options, $photo_from, $photo_source ) {
		if ( 'album_photos' === $saved_feed_options['facebook_page_feed_type'] || 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {
			echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture album-photo-fts" style="width:' . esc_attr( $saved_feed_options['facebook_image_width'] . ';height:' . $saved_feed_options['facebook_image_height'] ) . ';';
			echo 'background-image:url(' . esc_url( $photo_source ) . ');">';
			echo '</a>';
		} else {
			$saved_feed_options_popup = $saved_feed_options['facebook_popup'] ?? '';
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'yes' === $saved_feed_options_popup && 'javascript:;' !== $fb_link ) {
				echo '<a href="' . esc_url( $photo_source ) . '" target="_blank" rel="noreferrer" class="fts-facebook-link-target fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_html( $photo_from ) . '" src="' . esc_url( $photo_source ) . '"/></a>';

			} else {
				echo '<a href="' . esc_url( $fb_link ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture"><img border="0" alt="' . esc_html( $photo_from ) . '" src="' . esc_url( $photo_source ) . '"/></a>';
			}
		}
	}

	/**
	 * Feed Post Types
	 *
	 * Display Facebook Feed.
	 *
	 * @param string $set_zero A way to skip posts.
	 * @param string $facebook_post_type The type of Facebook Feed.
	 * @param array  $facebook_post Data for returned Facebook Post.
	 * @param array  $saved_feed_options The feed options saved in the CPT.
	 * @param string $response_post_array All post info.
	 * @param array  $single_event_array_response All post info.
	 * @since 1.9.6
	 */
	public function feed_post_types( $set_zero, $facebook_post_type, $facebook_post, $saved_feed_options, $response_post_array, $single_event_array_response = null, $fts_facebook_reviews = null ) {
        /*echo '<pre>';
		echo print_r($facebook_post);
        echo '</pre>';*/

		$fts_dynamic_vid_name_string = sanitize_key( $this->feed_functions->get_random_string( 10 ) . '_' . $saved_feed_options['facebook_page_feed_type'] );

		// If Set Zero skip this post and return
		if ( $set_zero === $saved_feed_options['facebook_page_post_count'] ) {
			// Post Skipped.
			return;
		}
		// Returned Post Data variables.
		$facebook_post_id                = $facebook_post->id  ?? '';
        $facebook_post_username          = $facebook_post->from->name  ?? '';
		$facebook_post_profile_pic_url   = $facebook_post->fts_profile_pic_url ?? '';
		$facebook_post_source            = $facebook_post->source ?? '';
		$facebook_post_picture           = $facebook_post->picture ?? '';
		$facebook_post_album_link        = $facebook_post->link ?? '';
		$facebook_post_link              = $facebook_post->attachments->data[0]->url ?? $facebook_post_album_link;
		$facebook_post_name              = $facebook_post->name ?? '';
        if( empty($facebook_post_name)){
            $facebook_post_name = $facebook_post->attachments->data[0]->title ?? '';
        }
		$facebook_post_caption           = $facebook_post->attachments->data[0]->caption ?? '';
		$facebook_post_description       = $facebook_post->attachments->data[0]->description ?? '';
		$facebook_post_link_event_name   = $facebook_post->to->data[0]->name ?? '';
		$facebook_post_story             = $facebook_post->story ?? '';
		$facebook_post_icon              = $facebook_post->icon ?? '';
		$facebook_post_by                = $facebook_post->properties->text ?? '';
		$facebook_post_bylink            = $facebook_post->properties->href ?? '';

		$facebook_post_share_count       = $facebook_post->shares->count ?? '';

		$facebook_post_like_count        = $facebook_post->likes->data ?? '';
		$facebook_post_comments_count    = $facebook_post->comments->data ?? '';
		$facebook_post_object_id         = $facebook_post->attachments->data[0]->id ?? '';
		$facebook_post_album_photo_count = $facebook_post->count ?? '';
		$facebook_post_album_cover       = $facebook_post->photos->data[0]->images[0]->source ?? '';
		$facebook_post_album_picture     = $facebook_post_source ?? '';
		$facebook_post_places_name       = $facebook_post->place->name ?? '';
		$facebook_post_places_id         = $facebook_post->place->id ?? '';

		$facebook_post_attachments_title = $facebook_post->attachments->data[0]->title ?? '';
		$facebook_post_attachments       = $facebook_post->attachments ?? '';
		$facebook_post_picture_job       = $facebook_post->attachments->data[0]->media->image->src ?? '';
		// YouTube and Vimeo embed url.
		$facebook_post_video_embed       = $facebook_post->attachments->data[0]->media->source ?? '';

		$facebook_post_from_id           = $facebook_post->from->id ?? '';
		$facebook_post_from_id_picture   = $facebook_post_from_id !== $saved_feed_options['fts_facebook_custom_api_token_user_id'] ? $saved_feed_options['fts_facebook_custom_api_token_user_id'] : $facebook_post_from_id;
		$fts_main_profile_pic_url        = $facebook_post->fts_main_profile_pic_url ?? '';
        $facebook_post_from_name         = $facebook_post->from->name ?? '';

		$facebook_post_final_story       = '';
		$facebook_post_dir               = '';

		// Facebook Post Video Photo.
		if ( isset( $facebook_post->format[3]->picture ) ) {
			$facebook_post_video_photo = $facebook_post->format[3]->picture;
		} elseif ( isset( $facebook_post->format[2]->picture ) ) {
			$facebook_post_video_photo = $facebook_post->format[2]->picture;
		} elseif ( isset( $facebook_post_picture )   ) {
			$facebook_post_video_photo = $facebook_post_picture;
		} else {
			$facebook_post_video_photo = '';
		}

		if ( ! empty( $facebook_post->format[3]->height ) && '0' !== $facebook_post->format[3]->height ) {
			$facebook_post_embed_html   = $facebook_post->format[3]->embed_html;
			$facebook_post_embed_width  = $facebook_post->format[3]->width;
			$facebook_post_embed_height = $facebook_post->format[3]->height;
		} elseif ( ! empty( $facebook_post->format[2]->height ) && '0' !== $facebook_post->format[2]->height ) {
			$facebook_post_embed_html   = $facebook_post->format[2]->embed_html;
			$facebook_post_embed_width  = $facebook_post->format[2]->width;
			$facebook_post_embed_height = $facebook_post->format[2]->height;
		} elseif ( ! empty( $facebook_post->format[1]->height ) && '0' !== $facebook_post->format[1]->height ) {
			$facebook_post_embed_html   = $facebook_post->format[1]->embed_html;
			$facebook_post_embed_width  = $facebook_post->format[1]->width;
			$facebook_post_embed_height = $facebook_post->format[1]->height;
		} elseif ( ! empty( $facebook_post->format[0]->height ) && '0' !== $facebook_post->format[0]->height ) {
			$facebook_post_embed_html   = $facebook_post->format[0]->embed_html;
			$facebook_post_embed_width  = $facebook_post->format[0]->width;
			$facebook_post_embed_height = $facebook_post->format[0]->height;
		} else {
			$facebook_post_embed_html   = 'none';
			$facebook_post_embed_width  = '';
			$facebook_post_embed_height = '';
		}

		// This will take our embed iframe from the array and then strip out the src url for the iframe so we can.
		// add this to our maginific popup.
		if ( 'none' !== $facebook_post_embed_html ) {
			preg_match( '/src="([^"]+)"/', $facebook_post_embed_html, $match );

			$facebook_post_embed_html = $match[1] . '&autoplay=true';
			// we do this check so we can add a data-height class name for our popup to know that we need to add the height to the iframe.
			// otherwise we let the magnific pop up scaler class do the work.
			if ( $facebook_post_embed_height > $facebook_post_embed_width ) {
				$facebook_post_height_class_name = 'fts-greater-than-width-height';
			} elseif ( $facebook_post_embed_height === $facebook_post_embed_width ) {
				$facebook_post_height_class_name = 'fts-equal-width-height';
			} else {
				$facebook_post_height_class_name = '';
			}
			// fts-view-fb-videos-btn.
			$fts_view_fb_videos_btn = 'fts-view-fb-videos-btn';

		} else {
			$facebook_post_embed_html             = isset( $facebook_post_source ) ? $facebook_post_source : $facebook_post_video_photo;
			$fts_view_fb_videos_btn = 'fts-view-fb-videos-btn';
			$facebook_post_height_class_name            = 'fts-equal-width-height';
		}

		if ( isset( $facebook_post_id  ) ) {
			$facebook_post_full_id = explode( '_', $facebook_post_id );
			$facebook_post_user_id = $facebook_post_full_id[0] ?? '';
			$facebook_post_single_id = $facebook_post_full_id[1] ?? '';

		} else {
			$facebook_post_id      = '';
			$facebook_post_user_id = '';
		}

		$facebook_joblink = isset( $facebook_post_id , $facebook_post_from_id ) ? 'https://www.facebook.com/' . $facebook_post_from_id . '/posts/' . $facebook_post_single_id . '' : '';

		if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] && ! $facebook_post_album_cover ) {
			unset( $facebook_post );
		}
		// Create Post Data Key.
		if ( isset( $facebook_post->attachments->data[0]->object_id ) ) {
			$facebook_post_key = $facebook_post->attachments->data[0]->object_id;
		} else {
			$facebook_post_key = $facebook_post_id  ?? '';
		}
		// Count Likes/Shares/.
		$lcs_array = $this->get_likes_shares_comments( $response_post_array, $facebook_post_key, $facebook_post_share_count );

		/*echo '$lcs_array<pre>';
		 print_r($lcs_array);
		echo '</pre>'; */

       /* echo '$response_post_array<pre>';
        print_r($response_post_array);
        echo '</pre>';*/

		// $facebook_location  = $facebook_post->location ?? '';
		$facebook_from_name = $facebook_post_from_name ?? '';
		$facebook_from_name = preg_quote( $facebook_from_name, '/' );

		$facebook_post_story = $facebook_post->story ?? '';
        $fts_custom_date     = $this->settings_functions->fts_get_option( 'custom_date' ) ?? '';
        $fts_custom_time     = $this->settings_functions->fts_get_option( 'custom_time' ) ?? '';
        $custom_date_check   = $this->settings_functions->fts_get_option( 'date_time_format' ) ?? '';

		$facebook_post_picture_gallery1 = $facebook_post->attachments->data[0]->subattachments->data[1]->media->image->src ?? '';
		$facebook_post_picture_gallery2 = $facebook_post->attachments->data[0]->subattachments->data[2]->media->image->src ?? '';
		$facebook_post_picture_gallery3 = $facebook_post->attachments->data[0]->subattachments->data[3]->media->image->src ?? '';

		// we get the width of the first attachment so we can set the max width for the frame around the main image and thumbs.. this makes it so our percent width on thumbnails are nice and aligned.
		$facebook_post_picture_gallery0_width = $facebook_post->attachments->data[0]->subattachments->data[0]->media->image->width ?? '';

		// June 22, 2017 - Going to leave the attachments description idea for a future update, lots more work to get the likes and comments for attachments and have that info be in the popup.
		// $facebook_post_pictureGalleryDescription0 = $facebook_post->attachments->data[0]->subattachments->data[1]->media->image->src ?? '';.
		// $facebook_post_pictureGalleryDescription1 = $facebook_post->attachments->data[0]->subattachments->data[2]->media->image->src ??  '';.
		// $facebook_post_pictureGalleryDescription2 = $facebook_post->attachments->data[0]->subattachments->data[3]->media->image->src ?? '';.
		// KZeni Edit: https://github.com/KZeni
		// February 25, 2019 - Uncommented Description variables so they can be used when making it so the pictures meet accessibility standards.
		$picture_from_fb = __( 'Picture from Facebook', 'feed-them-social' );
		$facebook_post_pictureGalleryDescription0 = $facebook_post->attachments->data[0]->subattachments->data[1]->description ?? $picture_from_fb;
		$facebook_post_pictureGalleryDescription1 = $facebook_post->attachments->data[0]->subattachments->data[2]->description ??  $picture_from_fb;
		$facebook_post_pictureGalleryDescription2 = $facebook_post->attachments->data[0]->subattachments->data[3]->description ?? $picture_from_fb;

		$facebook_post_picture_gallery_link1 = $facebook_post->attachments->data[0]->subattachments->data[1]->target->url ?? '';
		$facebook_post_picture_gallery_link2 = $facebook_post->attachments->data[0]->subattachments->data[2]->target->url ?? '';
		$facebook_post_picture_gallery_link3 = $facebook_post->attachments->data[0]->subattachments->data[3]->target->url ?? '';

		if ( isset( $scrollhorz_or_carousel, $saved_feed_options['slider_spacing'] ) && ! empty( $saved_feed_options['slider_spacing'] ) && 'carousel' === $saved_feed_options['scrollhorz_or_carousel'] && 'yes' === $saved_feed_options['fts-slider']) {
			$saved_feed_options['facebook_space_between_photos'] = '0 ' . $saved_feed_options['slider_spacing'];
		}

		if ( empty( $fts_custom_date ) && empty( $fts_custom_time ) && 'fts-custom-date' !== $custom_date_check ) {
			$custom_date_format = $custom_date_check;
		} elseif ( ! empty( $fts_custom_date ) && 'fts-custom-date' === $custom_date_check || ! empty( $fts_custom_time ) && 'fts-custom-date' === $custom_date_check ) {
			$custom_date_format = $fts_custom_date . ' ' . $fts_custom_time;
		} else {
			$custom_date_format = 'F jS, Y \a\t g:ia';
		}

		$album_created_time = $facebook_post->photos->data[0]->created_time ?? '';
		$other_created_time = $facebook_post->created_time ?? '';
		$created_time       = '' !== $album_created_time ? $album_created_time : $other_created_time;
		$custom_time_format = strtotime( $created_time );

		if ( ! empty( $facebook_post_story ) ) {
			$facebook_post_final_story = preg_replace( '/\b' . $facebook_from_name . 's*?\b(?=([^"]*"[^"]*")*[^"]*$)/i', '', $facebook_post_story, 1 );
		}

		$fts_hide_photos_type = $saved_feed_options[ 'fb_hide_images_in_posts' ] ?? 'no';

		if ( strpos( $facebook_post_link, 'youtube' ) > 0 || strpos( $facebook_post_link, 'youtu.be' ) > 0 || strpos( $facebook_post_link, 'vimeo' ) > 0 ) {
			$facebook_post_type = 'video_inline';
		}

		switch ( $facebook_post_type ) {
			case 'video_direct_response' :
			case 'video_inline' :
				echo '<div class="fts-jal-single-fb-post fts-fb-video-post-wrap" ';
				if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] ) {
					echo 'style="width:' . esc_attr( $saved_feed_options['facebook_grid_column_width'] ) . '; margin:' . esc_attr( $saved_feed_options['facebook_grid_space_between_posts'] ) . '"';
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

                $facebook_grid_space_between_posts = !empty( $saved_feed_options['facebook_grid_space_between_posts'] ) ? $saved_feed_options['facebook_grid_space_between_posts'] : '1px';

                echo "<div class='fts-fb-photo-post-wrap fts-jal-single-fb-post' ";
				if ( 'album_photos' === $saved_feed_options['facebook_page_feed_type'] || 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {

                    $facebook_space_between_photos = !empty( $saved_feed_options['facebook_space_between_photos'] ) ? $saved_feed_options['facebook_space_between_photos'] : '1px';

                    if ( isset( $saved_feed_options['fts-slider'] ) && 'yes' === $saved_feed_options['fts-slider'] && isset( $saved_feed_options['scrollhorz_or_carousel']) && 'scrollhorz' === $saved_feed_options['scrollhorz_or_carousel']) {
						echo 'style="text-align:left;max-width:' . esc_attr( $saved_feed_options['facebook_image_width'] ) . ';height:100%;  margin:' . esc_attr( $facebook_space_between_photos ) . '!important"';
					} else {
                    	echo 'style="text-align:center; width:' . esc_attr( $saved_feed_options['facebook_image_width'] ) . ' !important; height:' . esc_attr( $saved_feed_options['facebook_image_height'] ) . '!important; margin:' . esc_attr( $facebook_space_between_photos ) . '!important"';
					}
				}
				if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] ) {
					echo 'style="width:' . esc_attr( $saved_feed_options['facebook_grid_column_width'] ) . '; margin:' . esc_attr( $facebook_grid_space_between_posts ) . '"';
				}
				echo '>';

				break;
			case 'album':
			default:
                $facebook_grid_space_between_posts = !empty( $saved_feed_options['facebook_grid_space_between_posts'] ) ? $saved_feed_options['facebook_grid_space_between_posts'] : '1px';

                // This is also the wrapper for reviews posts when using the reviews extension.
				echo '<div class="fts-jal-single-fb-post"';
				if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] ) {
					echo 'style="width:' . esc_attr( $saved_feed_options['facebook_grid_column_width'] ) . '; margin:' . esc_attr( $facebook_grid_space_between_posts ) . '"';
				}
				echo '>';
				break;
		}
		// output Single Post Wrap.
		// Don't echo if Events Feed.
		if ( 'events' !== $saved_feed_options['facebook_page_feed_type'] ) {

			// Reviews.
			$itemscope_reviews = 'reviews' === $saved_feed_options['facebook_page_feed_type'] && isset( $facebook_post->rating ) ? 'itemscope itemtype="http://schema.org/Review"' : '';

			// Right Wrap.
			// $review_rating CANNOT be esc at this time.
			echo '<div ' . esc_attr( $itemscope_reviews ) . ' class="fts-jal-fb-right-wrap">';
			if ( 'reviews' === $saved_feed_options['facebook_page_feed_type'] && isset( $facebook_post->rating ) ) {
				echo '<meta itemprop="itemReviewed" itemscope itemtype="http://schema.org/CreativeWork"><div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" style="display: none;"><meta itemprop="worstRating" content="1"><meta itemprop="ratingValue" content="' . esc_attr( $facebook_post->rating ) . '"><meta  itemprop="bestRating" content="5"></div>';
			}

			// Hide Date, Likes and Comments.
			$saved_feed_options['facebook_hide_date_likes_comments'] = 'album_photos' === $saved_feed_options['facebook_page_feed_type'] && 'yes' === $saved_feed_options['facebook_hide_date_likes_comments'] || 'albums' === $saved_feed_options['facebook_page_feed_type'] && 'yes' === $saved_feed_options['facebook_hide_date_likes_comments'] ? 'hide-date-likes-comments-etc' : '';

			// Show Media.

            if( 'combine-streams-feed-type' ===  $saved_feed_options['feed_type'] ){
                $show_media = $saved_feed_options['combine_show_media'] ? $saved_feed_options['combine_show_media']: 'bottom';
            }
            else {
                $show_media = isset( $saved_feed_options['facebook_show_media'] ) ? $saved_feed_options['facebook_show_media']: 'bottom';
            }

			// Facebook Hide Shared By etc text.
			$facebook_hide_shared_by_etc_text = $saved_feed_options['facebook_hide_shared_by_etc_text'] && 'no' === $saved_feed_options['facebook_hide_shared_by_etc_text'] ? '' : $facebook_post_final_story;

			if ( 'top' !== $show_media ) {
				// Top Wrap (Excluding : albums, album_photos, and hiding).
				echo '<div class="fts-jal-fb-top-wrap ' . esc_attr( $saved_feed_options['facebook_hide_date_likes_comments'] ) . '">';

				if ( 'albums' !== $saved_feed_options['facebook_page_feed_type'] ) {
					echo '<div class="fts-jal-fb-user-thumb">';

					$avatar_id                  = plugin_dir_url( __DIR__ ) . 'images/slick-comment-pic.png';
					$profile_photo_exists_check = isset( $facebook_post_profile_pic_url ) && strpos( $facebook_post_profile_pic_url, 'profilepic' ) !== false ? $facebook_post_profile_pic_url : $avatar_id;

                    if( $saved_feed_options['facebook_page_feed_type'] === 'reviews' && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' )){
                        echo '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $facebook_post->reviewer->name ) . '" src="' . esc_attr( $profile_photo_exists_check ) . '"></a>';
                    }
                    elseif ( $saved_feed_options['feed_type'] !== 'combine-streams-feed-type' ) {
                        echo '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_attr( $fts_main_profile_pic_url ) . '"/></a>';
                    }
					// echo ( 'reviews' === esc_attr( $saved_feed_options['facebook_page_feed_type'] ) ? '' : '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer">' ) . '<img border="0" alt="' . ( 'reviews' === esc_attr( $saved_feed_options['facebook_page_feed_type'] ) ? esc_attr( $facebook_post->reviewer->name ) : esc_attr( $facebook_post_from_name ) ) . '" src="' . ( 'reviews' === esc_attr( $saved_feed_options['facebook_page_feed_type'] ) ? esc_url( $profile_photo_exists_check ) . '"/>' : 'https://graph.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) ) . ( 'reviews' === esc_attr( $saved_feed_options['facebook_page_feed_type'] ) ? '' : '/picture"/></a>' );

					echo '</div>';

				}

				// UserName.
				// $fts_facebook_reviews->reviews_rating_format CANNOT be esc at this time.
				$hide_name = 'albums' === $saved_feed_options['facebook_page_feed_type'] ? ' fts-fb-album-hide' : '';

                // WHY REVIEWS IS LOADING SO DAMN SLOW... LEAVING OFF HERE.. STATEMENT BELOW DOES NOT HAVE ANYTHING TO DO WITH IT FROM WHAT I CAN TELL.
				echo 'reviews' === $saved_feed_options['facebook_page_feed_type'] && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? '<span class="fts-jal-fb-user-name fts-review-name" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">' . esc_attr( $facebook_post->reviewer->name ) . '</span>' . $fts_facebook_reviews->reviews_rating_format( $saved_feed_options, isset( $facebook_post->rating ) ? esc_html( $facebook_post->rating ) : '' ) . '</span>' : '<span class="fts-jal-fb-user-name' . $hide_name . '"><a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $facebook_post_from_name ) . '</a>' . esc_html( $facebook_hide_shared_by_etc_text ) . '</span>';

				// tied to date function.
				$feed_type      = 'facebook';
				$times          = $custom_time_format;
				$fts_final_date = $this->feed_functions->fts_custom_date( $times, $feed_type );
				// PostTime.
				// $fts_final_date CANNOT be esc at this time.
				if ( 'albums' !== $saved_feed_options['facebook_page_feed_type'] ) {
					echo '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';
				}
			}

			if ( 'reviews' !== $saved_feed_options['facebook_page_feed_type'] ) {
				// Comments Count.
				$facebook_post_id_final = substr( $facebook_post_id, strpos( $facebook_post_id, '_' ) + 1 );
			}

			$facebook_title_job_opening = isset( $facebook_post->attachments->data[0]->title ) && 'job_search_job_opening' === $facebook_post->attachments->data[0]->type ? $facebook_post->attachments->data[0]->title : '';

			// filter messages to have urls.
			// Output Message.
			$facebook_message = ( isset( $facebook_post->message ) ? $facebook_post->message : ( isset( $facebook_post->review_text ) ? $facebook_post->review_text : '' ) . '' );
			if ( empty( $facebook_message ) ) {

				if ( isset( $facebook_post->description ) ) {
					$facebook_message = isset( $facebook_post->description ) ? $facebook_post->description : '';
				} elseif ( isset( $facebook_post->attachments->data[0]->description ) ) {
					$facebook_message = isset( $facebook_post->attachments->data[0]->description ) ? $facebook_post->attachments->data[0]->description : '';
				}
			}

			if ( $facebook_message && 'top' !== $show_media ) {

				if ( ! empty( $facebook_post_places_id ) ) {
					$this->feed_location_option( $facebook_post_places_id, $facebook_post_name, $facebook_post_places_name );
				}

				$itemprop_description_reviews = is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ? ' itemprop="description"' : '';

				// here we trim the words for the premium version. The $saved_feed_options['facebook_page_word_count']string actually comes from the javascript.
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_page_word_count'] ) && $saved_feed_options['facebook_page_word_count'] && 'top' !== $show_media ||
                    is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && isset( $saved_feed_options['combine_word_count_option'] ) && $saved_feed_options['combine_word_count_option'] && 'top' !== $show_media ||
                    is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && isset( $saved_feed_options['facebook_page_word_count'] ) && $saved_feed_options['facebook_page_word_count'] && 'top' !== $show_media ) {
                    // SRL 4.0: make this an option eventually.
                    $more = '...';

                   // $word_count = !empty( $saved_feed_options['combine_word_count_option'] ) ? $saved_feed_options['combine_word_count_option'] : $saved_feed_options['facebook_page_word_count'];

                   /* $facebook_final_message = $this->facebook_tag_filter( $facebook_message );
                    $trunacate_words = new \FeedThemSocialTruncateHTML();
					$trimmed_content = $trunacate_words::fts_custom_trim_words( $facebook_final_message, $word_count , $more );*/

					// Going to consider this for the future if facebook fixes the api to define when are checking in. Add  '.$checked_in.' inside the fts-jal-fb-message div.
					// $checked_in = '<a target="_blank" class="fts-checked-in-img" href="https://www.facebook.com/'.$facebook_post->place->id.'"><img src="https://graph.facebook.com/'.$facebook_post->place->id.'/picture?width=150"/></a><a target="_blank" class="fts-checked-in-text-link" href="https://www.facebook.com/'.$facebook_post->place->id.'">'.esc_html("Checked in at", "feed-them-social").' '.$facebook_post->place->name.'</a><br/> '.esc_html("Location", "feed-them-social").': '.$facebook_post->place->location->city.', '.$facebook_post->place->location->country.' '.$facebook_post->place->location->zip.'<br/><a target="_blank" class="fts-fb-get-directions fts-checked-in-get-directions" href="https://www.facebook.com/'.$facebook_post->place->id.'">'.esc_html("Get Direction", "feed-them-social").'</a>';.
					echo '<div class="fts-jal-fb-message"' . esc_attr( $itemprop_description_reviews ) . '>';

					echo esc_html( $facebook_title_job_opening );
					// $trimmed_content CANNOT be esc at this time.
                    $this->facebook_post_desc( $facebook_message, $saved_feed_options, $facebook_post_type );
					// The Popup.
					// echo $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html__('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
					echo '<div class="fts-clear"></div></div> ';

				} elseif ( 'top' !== $show_media ) {
					$facebook_final_message = $this->facebook_tag_filter( $facebook_message );
					echo '<div class="fts-jal-fb-message"' . esc_attr( $itemprop_description_reviews ) . '>';
					// $facebook_final_message CANNOT be esc at this time.
					echo nl2br( $facebook_final_message );
					// If POPUP.
					// echo isset($saved_feed_options['facebook_popup'] ) && $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
					echo '<div class="fts-clear"></div></div>';
				}
			} elseif ( ! $facebook_message && 'album_photos' === $saved_feed_options['facebook_page_feed_type'] || ! $facebook_message && 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {

				echo '<div class="fts-jal-fb-description-wrap">';

				$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
				// Output Photo Caption.

				// Album Post Description.
				if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {
					echo '<div class="fts-fb-album-name-and-count ">';
				}
				$this->facebook_post_desc( $facebook_post_name, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );
				// Albums Photo Count.
				echo $facebook_post_album_photo_count ? esc_html( $facebook_post_album_photo_count ) . ' Photos' : '';
				if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {
					echo '</div>';
				}

				// Location.
				// $facebook_location ? $this->fts_facebook_location( $facebook_post_type, $facebook_location ) : '';

				// Output Photo Description.
				$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );

				// Output Photo Description.
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ) {
					echo '<div class="fts-fb-caption fts-fb-album-view-link">';
					// Album Covers.
					if ( $saved_feed_options['facebook_page_feed_type'] === 'albums' ) {

						echo '<div class="fts-fb-album-additional-pics">';
						// Album Covers. <img src="' . esc_url( $facebook_album_additional_pic->images[1]->source ) . '"/>
						$isFirst = true;
                        if( isset( $facebook_post->photos->data ) ) {
                            foreach ( $facebook_post->photos->data as $key => $facebook_album_additional_pic ) {
                                // $facebook_album_additional_pic_check = isset( $facebook_album_additional_pic->name ) ? $this->facebook_post_desc( $facebook_album_additional_pic->name, $saved_feed_options, $facebook_post_type, null, $facebook_post_by  ): '';
                                // $facebook_album_additional_pic ? $facebook_album_additional_pic_check : '';
                                echo '<div class="fts-fb-album-additional-pics-content">';

                                $hide_all_but_one_link = !$isFirst ? 'style="display:none"' : '';

                                echo '<a href="' . esc_url( $facebook_album_additional_pic->images[0]->source ) . '" class="fts-view-album-photos-large data-fb-album-photo-description" target="_blank" rel="noreferrer"  ' . $hide_all_but_one_link . '>' . esc_html__( 'View Album', 'feed-them-social' ) . '</a>';
                                echo '<div class="fts-fb-album-additional-pics-description-wrap">';
                                echo '<div class="fts-jal-fb-description-wrap fts-fb-album-description-content fts-jal-fb-description-popup">';

                                // tied to date function.
                                $feed_type = 'facebook';
                                $album_created_time = $facebook_album_additional_pic->created_time ?? '';
                                $times = $album_created_time;
                                $fts_final_date = $this->feed_functions->fts_custom_date( $times, $feed_type );
                                echo '<div class="fts-jal-fb-user-thumb">';
                                echo '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_attr( $fts_main_profile_pic_url ) . '"/></a>';
                                echo '</div>';

                                // UserName.
                                // $fts_facebook_reviews->reviews_rating_format CANNOT be esc at this time.
                                echo '<span class="fts-jal-fb-user-name"><a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $facebook_post_from_name ) . '</a>' . esc_html( $facebook_hide_shared_by_etc_text ) . '</span>';

                                echo '<div class="fts-fb-album-date-wrap">' . $fts_final_date . '</div>';

                                echo '<div class="fts-clear"></div>';

                                // Album Post Description.
                                // $facebook_post_name ? $this->facebook_post_desc( $facebook_post_name, $saved_feed_options, $facebook_post_type, null, $facebook_post_by ) : '';
                                // Albums Photo Count.
                                $this->facebook_post_desc( $facebook_post_name, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );
                                $view_additional_album_photos = '24' == $key ? '. <a href="' . $facebook_post_album_link . '" target="_blank" rel="noreferrer">' . esc_html__( 'View more for this Album', 'feed-them-social' ) . '</a>' : '';
                                echo $facebook_post_album_photo_count ? ' ' . esc_html( $key + 1 ) . ' ' . esc_html__( 'of', 'feed-them-social' ) . ' ' . esc_html( $facebook_post_album_photo_count ) . ' ' . esc_html__( 'Photos', 'feed-them-social' ) . ' ' . $view_additional_album_photos : '';
                                echo '<br/><br/>';

                                $facebook_album_additional_pic_name = isset( $facebook_album_additional_pic->name ) ? $facebook_album_additional_pic->name : '';
                                $this->facebook_post_desc( $facebook_album_additional_pic_name, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );
                                echo '</div>';

                                $facebook_album_single_picture_id = isset( $facebook_album_additional_pic->id ) ? $facebook_album_additional_pic->id : '';
                                $facebook_single_image_link = 'https://www.facebook.com/' . $facebook_album_single_picture_id . '';
                                $single_event_id = isset( $single_event_id ) ?? '';
                                $this->facebook_post_see_more( $facebook_single_image_link, $lcs_array, $facebook_post_type, $saved_feed_options, $facebook_post_username, $facebook_post_id, $facebook_post_user_id, $facebook_post_single_id, $single_event_id, $facebook_post );

                                echo '</div>';
                                echo '</div>';
                                $isFirst = false;
                            }
                        }
						echo '</div>';
						echo '</div>';
					}
					elseif (
						// Album Photos.
						$saved_feed_options['facebook_page_feed_type'] === 'album_photos' && isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] !== 'yes' || ! isset( $saved_feed_options['facebook_video_album'] ) ) {
						echo '<a href="' . esc_url( $facebook_post_album_picture ) . '" class="fts-view-album-photos-large" target="_blank" rel="noreferrer">' . esc_html__( 'View Photo', 'feed-them-social' ) . '</a></div>';

					} elseif (
						// Video Albums.
						isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] === 'yes' ) {
						if ( $saved_feed_options['facebook_show_video_button'] !== 'yes' ) {

							echo '<a href="' . esc_url( $facebook_post_embed_html ) . '"  data-poster="' . esc_url( $facebook_post_video_photo ) . '" id="fts-view-vid1-' . esc_attr( $fts_dynamic_vid_name_string ) . '" class="fts-jal-fb-vid-html5video ' . esc_attr( $fts_view_fb_videos_btn ) . ' fts-view-fb-videos-large fts-view-fb-videos-btn fb-video-popup-' . esc_attr( $fts_dynamic_vid_name_string ) . '">' . esc_html__( 'View Video', 'feed-them-social' ) . '</a>';

							echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
							if ( $facebook_post_embed_height >= $facebook_post_embed_width ) {
								echo '<div class=' . esc_url( $facebook_post_height_class_name ) . ' data-width="' . esc_attr( $facebook_post_embed_width ) . '" data-height="' . esc_attr( $facebook_post_embed_height ) . '"></div>';
							}
							echo '</div>';
						}
						echo '</div>';
					} else {
						// photos.
						echo '<a href="' . esc_url( $facebook_post_source ) . '" class="fts-view-album-photos-large" target="_blank" rel="noreferrer">' . esc_html__( 'View Photo', 'feed-them-social' ) . '</a></div>';
					}

					// echo '<div class="fts-fb-caption"><a class="view-on-facebook-albums-link" href="' . $facebook_post_link . '" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div>';.
				}

				echo '<div class="fts-clear"></div></div>';
			} //END Output Message
			// elseif ($facebook_message == '' && $saved_feed_options['facebook_page_feed_type'] !== 'album_photos' || $facebook_message == '' && $saved_feed_options['facebook_page_feed_type'] !== 'albums') {.
			// If POPUP.
			// echo $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-jal-fb-message"><div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div></div>' : '';.
			// }.
			if ( 'top' !== $show_media ) {
				echo '</div>';
				// end .fts-jal-fb-top-wrap <!--end fts-jal-fb-top-wrap -->.
			}
		}
		// Post Type Build.
		switch ( $facebook_post_type ) {
			// START NOTE POST.
			case 'knowledge_note':
            case 'messenger_generic_template':
				// && !$facebook_post_picture == '' makes it so the attachment unavailable message does not show up.
				// if (!$facebook_post_picture && !$facebook_post_name && !$facebook_post_description && !$facebook_post_picture == '') {.
				echo '<div class="fts-jal-fb-link-wrap">';

				// Output Link Picture.
				$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post->attachments->data[0]->media->image->src );

				if ( $facebook_post_name || $facebook_post_caption || $facebook_post_description ) {
                    // If the $facebook_post_name which is the description matches the $facebook_message then we don't want to output it again.
                    if (str_replace(' ', '', $facebook_post_name) !== str_replace(' ', '', $facebook_message)) {
                        echo '<div class="fts-jal-fb-description-wrap">';
                        // Output Link Name.
                        $this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type );
                        // Output Link Caption.
                        if ( 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' === $facebook_post_caption ) {
                            echo '<div class="fts-jal-fb-caption" style="width:100% !important">';
                            esc_html( 'This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social' );
                            echo '</div>';
                        } else {
                            $this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
                        }
                        // If POPUP.
                        // echo $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
                        // Output Link Description.
                        // echo $facebook_post_description ? $this->facebook_post_desc($facebook_post_description, $saved_feed_options, $facebook_post_type) : '';.
                        echo '<div class="fts-clear"></div></div>';
                    }
				}
				echo '<div class="fts-clear"></div></div>';
				// }.
				break;

			// START STATUS POST.
			case 'status':
				// && !$facebook_post_picture == '' makes it so the attachment unavailable message does not show up.
				// if (!$facebook_post_picture && !$facebook_post_name && !$facebook_post_description && !$facebook_post_picture == '') {.
				echo '<div class="fts-jal-fb-link-wrap">';
				// Output Link Picture.
				if ( $facebook_post_picture_job ) {
					$this->facebook_post_photo( $facebook_joblink, $saved_feed_options, $facebook_post_from_name, $facebook_post_picture_job );
				} else {
					$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_picture );
				}

				if ( $facebook_post_name || $facebook_post_caption || $facebook_post_description ) {
					echo '<div class="fts-jal-fb-description-wrap">';
					// Output Link Name.
					$this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type );
					// Output Link Caption.
					if ( 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' === $facebook_post_caption ) {
						echo '<div class="fts-jal-fb-caption" style="width:100% !important">';
						echo esc_html( 'This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social' );
						echo '</div>';
					} else {
						$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
					}
					// Output Link Description.
					$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type );
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				// }
				break;
			// Start Multiple Events.
			case 'events':
				$single_event_id = $facebook_post_id ;
				$single_event_info = json_decode( $single_event_array_response['event_single_' . $single_event_id . '_info'] );
				$single_event_location = json_decode( $single_event_array_response['event_single_' . $single_event_id . '_location'] );
				$single_event_cover_photo = json_decode( $single_event_array_response['event_single_' . $single_event_id . '_cover_photo'] );
				$single_event_ticket_info = json_decode( $single_event_array_response['event_single_' . $single_event_id . '_ticket_info'] );
				// echo'<pre>';.
				// print_r($single_event_info);.
				// echo'</pre>';.
				// Event Cover Photo.
				$event_cover_photo = isset( $single_event_cover_photo->cover->source ) ? $single_event_cover_photo->cover->source : '';
				$event_description = isset( $single_event_info->description ) ? $single_event_info->description : '';
				echo '<div class="fts-jal-fb-right-wrap fts-events-list-wrap">';
				// Link Picture.
				$facebook_event_name = isset( $single_event_info->name ) ? $single_event_info->name : '';
				$facebook_event_location = isset( $single_event_location->place->name ) ? $single_event_location->place->name : '';
				$facebook_event_city = isset( $single_event_location->place->location->city ) ? $single_event_location->place->location->city . ', ' : '';
				$facebook_event_state = isset( $single_event_location->place->location->state ) ? $single_event_location->place->location->state : '';
				$facebook_event_street = isset( $single_event_location->place->location->street ) ? $single_event_location->place->location->street : '';
				$facebook_event_zip = isset( $single_event_location->place->location->zip ) ? ' ' . $single_event_location->place->location->zip : '';
				$facebook_event_latitude = isset( $single_event_location->place->location->latitude ) ? $single_event_location->place->location->latitude : '';
				$facebook_event_longitude = isset( $single_event_location->place->location->longitude ) ? $single_event_location->place->location->longitude : '';
				$facebook_event_ticket_info = isset( $single_event_ticket_info->ticket_uri ) ? $single_event_ticket_info->ticket_uri : '';

				// custom one day ago check.
				if ( 'one-day-ago' === $custom_date_check ) {
					$facebook_event_start_time = date_i18n( 'l, F jS, Y \a\t g:ia', strtotime( $single_event_info->start_time ) );
				} else {
					$facebook_event_start_time = date_i18n( $custom_date_format, strtotime( $single_event_info->start_time ) );
				}

				// Output Photo Description.
				if ( !empty( $event_cover_photo ) ) {
					echo is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ? '<a href="' . esc_url( $event_cover_photo ) . '" class="fts-jal-fb-picture fts-fb-large-photo" target="_blank" rel="noreferrer"><img class="fts-fb-event-photo" src="' . esc_url( $event_cover_photo ) . '"></a>' : '<a href="https://www.facebook.com/events/' . esc_attr( $single_event_id ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img class="fts-fb-event-photo" src="' . esc_url( $event_cover_photo ) . '" /></a>';
				}
				echo '<div class="fts-jal-fb-top-wrap">';
				echo '<div class="fts-jal-fb-message">';
				// Link Name.
				echo '<div class="fts-event-title-wrap">';
				$this->fts_facebook_post_name( 'https://www.facebook.com/events/' . esc_attr( $single_event_id ) . '', esc_attr( $facebook_event_name ), esc_attr( $facebook_post_type ) ) ;
				echo '</div>';
				// Link Caption.
				if ( $facebook_event_start_time ) {
					echo '<div class="fts-fb-event-time">' . $facebook_event_start_time . '</div>';
				}
				// Link Description.
				if ( !empty( $facebook_event_location ) ) {
					echo '<div class="fts-fb-location"><span class="fts-fb-location-title">' . esc_html( $facebook_event_location ) . '</span>';
					// Street Adress.
					echo esc_html( $facebook_event_street );
					// City & State.
					echo $facebook_event_city || $facebook_event_state ? '<br/>' . esc_html( $facebook_event_city . $facebook_event_state . $facebook_event_zip ) : '';
					echo '</div>';
				}
				// Get Directions.
				if ( !empty( $facebook_event_latitude ) && !empty( $facebook_event_longitude ) ) {
					echo '<a target="_blank" class="fts-fb-get-directions" href="' . esc_html( 'https://www.google.com/maps/dir/Current+Location/' . $facebook_event_latitude . ',' . $facebook_event_longitude . '' ) . '"  
>' . esc_html( 'Get Directions', 'feed-them-social' ) . '</a>';
				}
				if ( !empty( $facebook_event_ticket_info ) && !empty( $facebook_event_ticket_info ) ) {
					echo '<a target="_blank" rel="noreferrer" class="fts-fb-ticket-info" href="' . esc_url( $single_event_ticket_info->ticket_uri ) . '">' . esc_html( 'Ticket Info', 'feed-them-social' ) . '</a>';
				}
				// Output Message.
				if ( !empty( $saved_feed_options['facebook_page_word_count']) && $event_description && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
					// here we trim the words for the premium version. The $saved_feed_options['facebook_page_word_count']string actually comes from the javascript.
					$this->facebook_post_desc( $event_description, $saved_feed_options, $facebook_post_type, null, $facebook_post_by, $saved_feed_options['facebook_page_feed_type'] );
				} else {
					// if the premium plugin is not active we will just show the regular full description.
					$this->facebook_post_desc( $event_description, $facebook_post_type, null, $facebook_post_by, $saved_feed_options['facebook_page_feed_type'] );
				}
				// Our POPUP.
				// echo $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="https://www.facebook.com/events/' . $single_event_id . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View Event on Facebook', 'feed-them-facebook') . '</a></div> ' : '';.
				echo '<div class="fts-clear"></div></div></div>';
				break;

			// START LINK POST.
			case 'share':
				echo '<div class="fts-jal-fb-link-wrap">';
				// start url check.
                // In some cases the url is actually a phone number like tel:+5555555555 so we need to check and if so bypass it.
				if ( !empty( $facebook_post_link ) && strpos( $facebook_post_link, 'http' ) > 0 ) {
					$url = $facebook_post_link;
					$url_parts = parse_url( $url );
					$host = $url_parts['host'];
				}

				if ( isset( $host ) && 'www.facebook.com' === $host ) {
					$spliturl = $url_parts['path'];
					$path_components = explode( '/', $spliturl );
					$facebook_post_dir = $path_components[1];
				}
				// end url check.
				// Output Link Picture.
				// echo isset($saved_feed_options['facebook_popup'] ) && $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
				if ( isset( $host ) && 'www.facebook.com' === $host && 'events' === $facebook_post_dir ) {
					$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_picture );
				} elseif ( strpos( $facebook_post_link, 'soundcloud' ) > 0 ) {
					// Get the SoundCloud URL.
					$url = $facebook_post_link;
					// Get the JSON data of song details with embed code from SoundCloud oEmbed.
					$get_values = file_get_contents( 'http://soundcloud.com/oembed?format=js&url=' . $url . '&auto_play=true&iframe=true' );
					// Clean the Json to decode.
					$decode_iframe = substr( $get_values, 1, -2 );
					// json decode to convert it as an array.
					$json_obj = json_decode( $decode_iframe );
					// Change the height of the embed player if you want else uncomment below line.
					// echo str_replace('height="400"', 'height="140"', $json_obj->html);.
					$fts_dynamic_vid_name_string = sanitize_key( $this->feed_functions->get_random_string( 10 ) . '_' . $saved_feed_options['facebook_page_feed_type'] );
					$fts_dynamic_vid_name = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
					echo '<div class="fts-jal-fb-vid-picture ' . esc_attr( $fts_dynamic_vid_name ) . '">';
					if ( !empty( $facebook_post->attachments->data[0]->media->image->src ) ) {
						$this->facebook_post_photo( 'javascript:;', $saved_feed_options, $facebook_post_from_name, $facebook_post->attachments->data[0]->media->image->src );
					} elseif ( !empty( $facebook_post_picture ) ) {
						$this->facebook_post_photo( 'javascript:;', $saved_feed_options, $facebook_post_from_name, $facebook_post_picture );
					}
					echo '<div class="fts-jal-fb-vid-play-btn"></div>';
					echo '</div>';
					echo '<script>';
					echo 'jQuery(document).ready(function() {';
					echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
					echo 'jQuery(this).addClass("fts-vid-div");';
					echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
					echo '	jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper">' . $json_obj->html . '</div>\');';
					if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] ) {
						echo 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
						echo 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
					}
					echo '});';
					echo '});';
					echo '</script>';
				} elseif ( !empty( $facebook_post->attachments->data[0]->media->image->src ) ) {
					$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post->attachments->data[0]->media->image->src );
				} elseif ( !empty( $facebook_post_picture ) ) {
					$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_picture );
				}

				$saved_feed_options['facebook_page_word_count']= !empty( $saved_feed_options['facebook_page_word_count']) ? $saved_feed_options['facebook_page_word_count']: '';
				// Description Wrap.
				echo '<div class="fts-jal-fb-description-wrap">';
				// Output Link Name.
				$this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type );
				if ( isset( $host ) && 'www.facebook.com' === $host && 'events' === $facebook_post_dir ) {
					echo ' &#9658; ';
					echo '<a href="' . esc_url( $facebook_post_link ) . '" class="fts-jal-fb-name" target="_blank" rel="noreferrer">' . esc_html( $facebook_post_link_event_name ) . '</a>';
				}//end if event.

				// Output Link Description.
				$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type );

				// Output Link Caption.
				$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
				echo '<div class="fts-clear"></div></div>';
				echo '<div class="fts-clear"></div></div>';
				break;

			// START VIDEO POST.
			case 'video_direct_response' :
			case 'video_inline' :
				// $video_data = json_decode($response_post_array[$facebook_post_key . '_video']);.
				// echo '<pre>';.
				// print_r($video_data);.
				// echo '</pre>';.
				echo '<div class="fts-jal-fb-vid-wrap">';

				if ( !empty( $facebook_post_picture ) ) {

					// Create Dynamic Class Name.
					$fts_dynamic_vid_name_string = sanitize_key( $this->feed_functions->get_random_string( 10 ) . '_' . $saved_feed_options['facebook_page_feed_type'] );
					$fts_dynamic_vid_name = 'feed_dynamic_video_class' . $fts_dynamic_vid_name_string;
					echo '<div class="fts-jal-fb-vid-picture ' . esc_html( $fts_dynamic_vid_name ) . '">';

					if ( strpos( $facebook_post_link, 'youtube' ) > 0 || strpos( $facebook_post_link, 'youtu.be' ) > 0 ) {
						preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $facebook_post_video_embed, $matches );
						$video_url_final = 'https://www.youtube.com/watch?v=' . $matches[1];
					} else {
						$video_url_final = esc_url( $facebook_post_embed_html );
					}

					// This puts the video in a popup instead of displaying it directly on the page.
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ) {

						if ( strpos( $facebook_post_link, 'youtube' ) > 0 || strpos( $facebook_post_link, 'youtu.be' ) > 0 || strpos( $facebook_post_link, 'vimeo' ) > 0 ) {
							echo '<a href="' . esc_url( $video_url_final ) . '" class="fts-facebook-link-target fts-jal-fb-vid-image fts-iframe-type">';
						} else {

							if ( 'video_direct_response' === $facebook_post->attachments->data[0]->type || 'video_inline' === $facebook_post->attachments->data[0]->type ) {
								$page_id = $facebook_post_from_id;
								$video_id = $facebook_post->attachments->data[0]->target->id;
								$facebook_embed_url = 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2F' . $page_id . '%2Fvideos%2F' . $video_id . '%2F&autoplay=true';
								echo '<a href="' . esc_url( $facebook_embed_url ) . '" class="fts-jal-fb-vid-image ' . esc_attr( $fts_view_fb_videos_btn ) . ' fts-jal-fb-vid-html5video ">';

							} else {
								echo '<a href="' . esc_url( $facebook_post_embed_html ) . '" class="fts-facebook-link-target fts-jal-fb-vid-html5video ">';
							}
						}
					}
					// srl: 8/27/17 - FB BUG: for some reason the full_picture for animated gifs is not correct so we dig deeper and grab another image size fb has set.
					if ( isset( $facebook_post->attachments->data[0]->type ) && 'animated_image_video' === $facebook_post->attachments->data[0]->type ) {
						$vid_pic = $facebook_post->attachments->data[0]->media->image->src;
					} else {
						$vid_pic = $facebook_post->attachments->data[0]->media->image->src;
					}
					echo '<img class="fts-jal-fb-vid-image" border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_url( $vid_pic ) . '"/>';

					// This puts the video in a popup instead of displaying it directly on the page.
					if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ) {
						echo '</a>';
					}

					echo '<div class="fts-jal-fb-vid-play-btn"></div></div>';

					// If this is a facebook embed video then ouput Iframe and script.
					$facebook_post_embed_height = $facebook_post->attachments->data[0]->media->image->height ?? '';
					$facebook_post_embed_width = $facebook_post->attachments->data[0]->media->image->width ?? '';
					$video_type = $facebook_post->attachments->data[0]->type ?? '';
					// $video_inline = isset( $facebook_post->attachments->data[0]->type ) ? $facebook_post->attachments->data[0]->type : '';

					// && $video_inline == 'video_inline'. /////// || 'video' === $video_type && 'animated_image_video' === $video_inline
					if ( 'video_direct_response' === $video_type || 'video_inline' === $video_type ) {

						if ( $facebook_post_embed_height > $facebook_post_embed_width ) {
							$facebook_post_height_class_name = 'fts-greater-than-width-height';
						} elseif ( $facebook_post_embed_height === $facebook_post_embed_width ) {
							$facebook_post_height_class_name = 'fts-equal-width-height fts-fluid-videoWrapper ';
						} else {
							$facebook_post_height_class_name = 'fts-fluid-videoWrapper';
						}

						echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
						if ( $facebook_post_embed_height >= $facebook_post_embed_width ) {
							echo '<div class=' . esc_attr( $facebook_post_height_class_name ) . ' data-width="' . esc_attr( $facebook_post_embed_width ) . '" data-height="' . esc_attr( $facebook_post_embed_height ) . '"></div>';
						}
						echo '</div>';
						// This puts the video on the page instead of the popup if you don't have the premium version.
						if ( !isset( $saved_feed_options['facebook_popup']  ) ||
                            isset( $saved_feed_options['facebook_popup']  ) && 'yes' !== $saved_feed_options['facebook_popup']  && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ||
                            isset( $saved_feed_options['facebook_popup']  ) && empty( $saved_feed_options['facebook_popup']  ) ||
                            isset( $saved_feed_options['facebook_popup']  ) && 'no' === $saved_feed_options['facebook_popup']  ) {

							$page_id = $facebook_post_from_id ?? '';
							$video_id = $facebook_post->attachments->data[0]->target->id ?? '';

							$facebook_embed_url = 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2F' . $page_id . '%2Fvideos%2F' . $video_id . '%2F&autoplay=true';

							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$facebook_post_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="' . esc_js( $facebook_post_height_class_name ) . ' fts-fb-video-on-page" ><iframe style="background:none !important" class="video-' . esc_js( $facebook_post_id ) . '" src="' . esc_url( $facebook_embed_url ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							echo 'jQuery( ".' . esc_js( $fts_dynamic_vid_name ) . ' .fts-greater-than-width-height.fts-fb-video-on-page, .' . esc_js( $fts_dynamic_vid_name ) . ' iframe" ).css({"height": "' . esc_js( $facebook_post_embed_height ) . 'px", "width": "' . esc_js( $facebook_post_embed_width ) . 'px"});';
							if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] || isset( $saved_feed_options['grid_combined'] ) && 'yes' === $saved_feed_options['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					}
					// strip YouTube URL then ouput Iframe and script.
					if ( strpos( $facebook_post_link, 'youtube' ) > 0 ) {
						// $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';.
						// preg_match($pattern, $facebook_post_link, $matches);.
						// $youtubeURLfinal = $matches[1];.
						// This puts the video on the page instead of the popup if you don't have the premium version.
						if ( !isset( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'yes' !== $saved_feed_options['facebook_popup']  && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $saved_feed_options['facebook_popup']  ) && empty( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'no' === $saved_feed_options['facebook_popup']  ) {
							echo '<script>jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$facebook_post_id.'" src="https://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . esc_js( $facebook_post_id ) . '" src="' . esc_url( $facebook_post_video_embed ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] || isset( $saved_feed_options['grid_combined'] ) && 'yes' === $saved_feed_options['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});</script>';
						}
					} elseif (
						// strip YouTube URL then ouput Iframe and script.
						strpos( $facebook_post_link, 'youtu.be' ) > 0 ) {
						// $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';.
						// preg_match($pattern, $facebook_post_link, $matches);.
						// $youtubeURLfinal = $matches[1];.
						// This puts the video in a popup instead of displaying it directly on the page.
						if ( !isset( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'yes' !== $saved_feed_options['facebook_popup']  && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $saved_feed_options['facebook_popup']  ) && empty( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'no' === $saved_feed_options['facebook_popup']  ) {
							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$facebook_post_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video' . esc_js( $facebook_post_id ) . '" src="' . esc_url( $facebook_post_video_embed ) . '" frameborder="0" allowfullscreen></iframe></div>\');';
							if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] || isset( $saved_feed_options['grid_combined'] ) && 'yes' === $saved_feed_options['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					} elseif (
						// strip Vimeo URL then ouput Iframe and script.
						strpos( $facebook_post_link, 'vimeo' ) > 0 ) {
						// $pattern = '/(\d+)/';.
						// preg_match($pattern, $facebook_post_link, $matches);.
						// $vimeoURLfinal = $matches[0];.
						// This puts the video in a popup instead of displaying it directly on the page.
						if ( !isset( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'yes' !== $saved_feed_options['facebook_popup']  && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || isset( $saved_feed_options['facebook_popup']  ) && empty( $saved_feed_options['facebook_popup']  ) || isset( $saved_feed_options['facebook_popup']  ) && 'no' === $saved_feed_options['facebook_popup']  ) {
							echo '<script>';
							echo 'jQuery(document).ready(function() {';
							echo 'jQuery(".' . esc_js( $fts_dynamic_vid_name ) . '").click(function() {';
							echo 'jQuery(this).addClass("fts-vid-div");';
							echo 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							// echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$facebook_post_id.'" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';.
							echo 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="' . esc_url( $facebook_post_video_embed ) . '" class="video' . esc_js( $facebook_post_id ) . '" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';
							if ( isset( $saved_feed_options['facebook_grid_column_width'] ) && 'yes' === $saved_feed_options['facebook_grid'] || isset( $saved_feed_options['grid_combined'] ) && 'yes' === $saved_feed_options['grid_combined'] ) {
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
								echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "layout" );';
							}
							echo '});';
							echo '});';
							echo '</script>';
						}
					}
				}
				if ( $facebook_post_name || $facebook_post_caption || $facebook_post_description ) {
					echo '<div class="fts-jal-fb-description-wrap fb-id' . esc_attr( $facebook_post_id ) . '">';
					// Output Video Name.
					$this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type, $facebook_post_id );
					// Output Video Description.
					$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type, $facebook_post_id );
					// Output Video Caption.
					$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type, $facebook_post_id );
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				break;
			// START PHOTO POST.
			case 'photo':
				if ( isset( $fts_hide_photos_type ) && $fts_hide_photos_type === 'yes' && isset($saved_feed_options['facebook_page_feed_type']) && $saved_feed_options['facebook_page_feed_type'] !== 'album_photos' && isset($saved_feed_options['facebook_video_album']) && $saved_feed_options['facebook_video_album'] !== 'yes' ) {
					break;
				}

				// Wrapping with if statement to prevent Notice on some facebook page feeds.
				if ( 'group' === $saved_feed_options['facebook_page_feed_type'] ) {
					$photo_source = json_decode( $response_post_array[$facebook_post_key . '_group_post_photo'] );
				}

				echo '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ( isset($saved_feed_options['facebook_page_feed_type']) && $saved_feed_options['facebook_page_feed_type'] === 'album_photos' || isset($saved_feed_options['facebook_page_feed_type']) && $saved_feed_options['facebook_page_feed_type'] === 'albums' ) {
					echo ' style="line-height:' . esc_attr( $saved_feed_options['facebook_image_height'] ) . ' !important;"';
				}
				echo '>';
				// echo isset($saved_feed_options['facebook_popup'] ) && $saved_feed_options['facebook_popup']  == 'yes' ? '<div class="fts-fb-caption"><a href="' . $facebook_post_link . '" class="fts-view-on-facebook-link" target="_blank">' . esc_html('View on Facebook', 'feed-them-social') . '</a></div> ' : '';.
				// Output Photo Picture.
				if ( $facebook_post_picture ) {
					if ( $facebook_post_object_id ) {
						$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, 'https://graph.facebook.com/' . $facebook_post_object_id . '/picture' );
					} else {
						if ( isset( $saved_feed_options['facebook_video_album'] ) && 'yes' === $saved_feed_options['facebook_video_album'] ) {
							$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_video_photo );
						} elseif ( isset( $saved_feed_options['facebook_page_feed_type'] ) && 'album_photos' === $saved_feed_options['facebook_page_feed_type'] ) {
							$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_source );
						} else {
							$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post->attachments->data[0]->media->image->src );
						}
					}
				}
				echo '<div class="slicker-facebook-album-photoshadow"></div>';
                // Testing
                // echo $saved_feed_options['facebook_video_album'];

                // FB Video play button for facebook videos. This button takes data from our a tag and along with additional js in the magnific-popup.js we can now load html5 videos. SO lightweight this way because no pre-loading of videos are on the page. We only show the posterboard on mobile devices because tablets and desktops will auto load the videos. SRL.
				if ( isset( $saved_feed_options['facebook_video_album'] ) && 'yes' === $saved_feed_options['facebook_video_album'] ) {
					if ( isset( $saved_feed_options['facebook_show_video_button']  ) && 'yes' === $saved_feed_options['facebook_show_video_button']  ) {
						$facebook_play_btn_visible = isset( $saved_feed_options['facebook_show_video_button_in_front'] ) && 'yes' === $saved_feed_options['facebook_show_video_button_in_front'] ? ' visible-video-button' : '';

						// $facebook_post_source = isset($facebook_post_source) ? $facebook_post_source : $facebook_post_embed_html;.
						// $facebook_post_source = isset($facebook_post_embed_html) ? $facebook_post_embed_html : '';.
						// $facebook_post_format_3_picture = isset($facebook_post->format[3]->picture) ? $facebook_post->format[3]->picture : '';.
                        $facebook_play_btn_size = isset( $saved_feed_options['facebook_size_video_play_btn'] ) ? $saved_feed_options['facebook_size_video_play_btn'] : '68px';
						echo '<a href="' . esc_url( $facebook_post_embed_html ) . '"  data-poster="" id="fts-view-vid1-' . esc_attr( $fts_dynamic_vid_name_string ) . '" title="' . esc_html( $facebook_post_description ) . '" class="fts-jal-fb-vid-html5video ' . esc_attr( $fts_view_fb_videos_btn . ' fb-video-popup-' . $fts_dynamic_vid_name_string . ' ' . $facebook_play_btn_visible ) . ' fts-slicker-backg" style="height:' . esc_attr( $facebook_play_btn_size ) . ' !important; width:' . esc_attr( $facebook_play_btn_size ) . '; line-height: ' . esc_attr( $facebook_play_btn_size ) . '; font-size:' . esc_attr( $facebook_play_btn_size ) . '"><span class="fts-fb-video-icon" style="height:' . esc_attr( $facebook_play_btn_size ) . '; width:' . esc_attr( $facebook_play_btn_size ) . '; line-height:' . esc_attr( $facebook_play_btn_size ) . '; font-size:' . esc_attr( $facebook_play_btn_size ) . '"></span></a>';

						echo '<div class="fts-fb-embed-iframe-check-used-for-popup fts-fb-embed-yes">';
						if ( $facebook_post_embed_height >= $facebook_post_embed_width ) {
							echo '<div class=' . esc_attr( $facebook_post_height_class_name ) . ' data-width="' . esc_attr( $facebook_post_embed_width ) . '" data-height="' . esc_attr( $facebook_post_embed_height ) . '"></div>';
						}
						echo '</div>';
					}
				}
				if ( 'album_photos' === !$saved_feed_options['facebook_page_feed_type'] ) {
					echo '<div class="fts-jal-fb-description-wrap" style="display:none">';
					// Output Photo Name.
					$this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type );
					// Output Photo Caption.
					$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
					// Output Photo Description.
					$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );
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
				if ( 'album_photos' === $saved_feed_options['facebook_page_feed_type'] || 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {
					echo ' style="line-height:' . esc_attr( $saved_feed_options['facebook_image_height'] ) . ' !important;"';
				}
				echo '>';

				// echo '<pre>rrr';
				// print_r($facebook_post_album_cover);
				// echo '</pre>';
				// Output Photo Picture.

				// if ( $facebook_post_object_id ) {
				// if ( $facebook_post_object_id ) {

				$photo_source_final = isset( $facebook_post->attachments->data[0]->media->image->src ) ? $facebook_post->attachments->data[0]->media->image->src : 'https://graph.facebook.com/' . $facebook_post_object_id . '/picture';
				// This if statement is in place because we need to remove this link if its an albums so we don't get an extra image that is blank in the popup.
				if ( 'albums' !== $saved_feed_options['facebook_page_feed_type'] ) {
					// if we have more than one attachment we get the first image width and set that for the max width.
					$fts_fb_image_count = isset( $facebook_post->attachments->data[0]->subattachments->data ) ? count( $facebook_post->attachments->data[0]->subattachments->data ) : '0';
					// TESTING: lets see how many images are being output per post.
					// echo $fts_fb_image_count;.
					// echo $fts_fb_image_count;.
					if ( '0' === $fts_fb_image_count || '1' === $fts_fb_image_count || $fts_fb_image_count > 2 ) {

						// echo $fts_fb_image_count;.
						echo '<a href="' . (isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $photo_source_final ) : esc_url( $facebook_post_link )) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_url( $photo_source_final ) . '"></a>';

					}

					if ( '' !== $facebook_post_picture_gallery1 ) {

						// we count the number of attachments in the subattachments->data portion of the array and count the objects http://php.net/manual/en/function.count.php.
						$fts_fb_image_counter = $fts_fb_image_count - 3;

						$fts_fb_image_count_check = $fts_fb_image_count < 3 ? ' fts-more-images-tint' : '';

						$facebook_post_picture_gallery1_check = '' === $facebook_post_picture_gallery2 ? '100%;' : $facebook_post_picture_gallery0_width . 'px';
						// if we only have 2 photos we show them side by side.
						$facebook_post_picture_gallery2_check = '' === $facebook_post_picture_gallery2 ? ' fts-more-photos-auto-width' : '';
						// if we have 3 photos we add this class so we can make the 2 attachments below the large image will fit side by side.
						$facebook_post_picture_gallery3_check = '' === $facebook_post_picture_gallery3 && '' !== $facebook_post_picture_gallery2 ? ' fts-more-photos-three-photo-wrap' : '';

						$columns_css = '';

						// print $fts_fb_image_count;.
						if ( 2 === $fts_fb_image_count ) {
							$columns = '2';
							$columns_css = 'fts-more-photos-2-or-3-photos ';
							$morethan3 = 'fts-2-photos ';
						} elseif ( 3 === $fts_fb_image_count ) {
							$columns = '2';
							$columns_css = 'fts-more-photos-2-or-3-photos ';
							$morethan3 = 'fts-3-photos ';
						} elseif ( $fts_fb_image_count >= 4 ) {
							$columns = '3';
							$columns_css = 'fts-more-photos-4-photos ';
							$morethan3 = 'fts-4-photos ';
						}

						echo '<div class="fts-clear"></div><div class="' . esc_attr( $columns_css . 'fts-fb-more-photos-wrap fts-facebook-inline-block-centered' . $facebook_post_picture_gallery2_check . $facebook_post_picture_gallery3_check ) . '" style="max-width:' . esc_attr( $facebook_post_picture_gallery1_check ) . '" data-ftsi-id=' . esc_attr( $fts_dynamic_vid_name_string ) . ' data-ftsi-columns="' . esc_attr( $columns ) . '" data-ftsi-margin="1px" data-ftsi-force-columns="yes">';
					}
					if ( 2 === $fts_fb_image_count ) {
						echo '<a href="' . (is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $photo_source_final ) : esc_url( $facebook_post_link )) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . esc_url( $photo_source_final ) . ');" title="' . esc_attr( $facebook_post_pictureGalleryDescription0 ) . '" aria-label="' . esc_attr( $facebook_post_pictureGalleryDescription0 ) . '"></a>';

					}
					if ( '' !== $facebook_post_picture_gallery1 ) {
						echo '<a href="' . (is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $facebook_post_picture_gallery1 ) : esc_url( $facebook_post_picture_gallery_link1 )) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-zero-wrap fts-fb-large-photo" style="background:url(' . esc_url( $facebook_post_picture_gallery1 ) . ');" title="' . esc_attr( $facebook_post_pictureGalleryDescription1 ) . '" aria-label="' . esc_attr( $facebook_post_pictureGalleryDescription1 ) . '"></a>';

						if ( '' !== $facebook_post_picture_gallery2 ) {
							echo '<a href="' . (is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $facebook_post_picture_gallery2 ) : esc_url( $facebook_post_picture_gallery_link2 )) . '" target="_blank" rel="noreferrer" class="fts-2-or-3-photos slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-one-wrap fts-fb-large-photo" style="background:url(' . esc_url( $facebook_post_picture_gallery2 ) . ');" title="' . esc_attr( $facebook_post_pictureGalleryDescription1 ) . '" aria-label="' . esc_attr( $facebook_post_pictureGalleryDescription1 ) . '"></a>';

						}
						if ( '' !== $facebook_post_picture_gallery3 ) {
							echo '<a href="' . (is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $facebook_post_picture_gallery3 ) : esc_url( $facebook_post_picture_gallery_link3 )) . '" target="_blank" rel="noreferrer" class="slicker-facebook-placeholder fts-fb-thumbs-wrap ' . esc_attr( $morethan3 ) . 'fts-fb-thumb-two-wrap fts-fb-large-photo' . esc_attr( $fts_fb_image_count_check ) . '" style="background:url(' . esc_url( $facebook_post_picture_gallery3 ) . ');" title="' . esc_attr( $facebook_post_pictureGalleryDescription2 ) . '" aria-label="' . esc_attr( $facebook_post_pictureGalleryDescription2 ) . '"><div class="fts-image-count-tint-underlay"></div><div class="fts-image-count"><span>+</span>' . esc_html( $fts_fb_image_counter ) . '</div></a>';
						}
					}
					if ( '' !== $facebook_post_picture_gallery1 ) {
						echo '</div>';
					}
				}
				//  }
				//  else {
				//      echo '<a href="' . ( isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ? esc_url( $photo_source_final ) : esc_url( $facebook_post_link ) ) . '" target="_blank" rel="noreferrer" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_url( $photo_source_final ) . '" title="' . $facebook_post_pictureGalleryDescription0 . '" aria-label="' . $facebook_post_pictureGalleryDescription0 . '"></a>';
				//  }
				// }
				if ( 'albums' === $saved_feed_options['facebook_page_feed_type'] ) {
					$this->facebook_post_photo( $facebook_post_link, $saved_feed_options, $facebook_post_from_name, $facebook_post_album_cover );
				}
				echo '<div class="slicker-facebook-album-photoshadow"></div>';
				if ( 'albums' === ! $saved_feed_options['facebook_page_feed_type'] ) {
					echo '<div class="fts-jal-fb-description-wrap">';
					// Output Photo Name.
					$this->fts_facebook_post_name( $facebook_post_link, $facebook_post_name, $facebook_post_type );
					// Output Photo Caption.
					$this->facebook_post_cap( $facebook_post_caption, $saved_feed_options, $facebook_post_type );
					// Output Photo Description.
					$this->facebook_post_desc( $facebook_post_description, $saved_feed_options, $facebook_post_type, null, $facebook_post_by );
					echo '<div class="fts-clear"></div></div>';
				}
				echo '<div class="fts-clear"></div></div>';
				break;
            default:
                break;

		}
		// This puts the video in a popup instead of displaying it directly on the page.
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && 'yes' === $saved_feed_options['facebook_popup']  ) {
			// Post Comments.
			echo '<div class="fts-fb-comments-wrap">';
			$hide_comments_popup = isset( $saved_feed_options['facebook_popup_comments']) ? $saved_feed_options['facebook_popup_comments']: 'no';
			if ( isset( $lcs_array['comments_thread']->data ) && ! empty( $lcs_array['comments_thread']->data ) && 'yes' !== $hide_comments_popup || isset( $lcs_array['comments_thread']->data ) && ! empty( $lcs_array['comments_thread']->data ) && empty( $hide_comments_popup ) ) {
				// Post Comments.
				echo '<div class="fts-fb-comments-content fts-comments-post-' . esc_attr( $facebook_post_id ) . '">';

				foreach ( $lcs_array['comments_thread']->data as $comment ) {
					if(!empty($comment->message)) {
						echo '<div class="fts-fb-comment fts-fb-comment-' . esc_attr( $comment->id ) . '">';
						// User Profile Img.
                        $comment_profile_url_check = isset( $comment->from->id ) ? 'https://graph.facebook.com/'.$comment->from->id.'?fields=picture&access_token='. $this->access_options->decrypt_access_token($saved_feed_options['fts_facebook_custom_api_token']) : 'WTF';
                        $response                  = wp_remote_fopen( $comment_profile_url_check );
                        $comment_profile_url       = json_decode( $response, true );
						$avatar_id = $comment_profile_url['picture']['data']['url'] ?? (plugin_dir_url( dirname( __FILE__ ) ) . 'images/slick-comment-pic.png');
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
		if ( isset( $saved_feed_options['facebook_show_media']) && 'top' === $show_media ||
            isset( $saved_feed_options['combine_show_media']) && 'top' === $show_media ) {

			if ( 'right' === ($saved_feed_options['facebook_show_social_icon'] ?? '') || 'right' === ($saved_feed_options['combine_show_social_icon'] ?? '') ) {
				echo '<div class="fts-mashup-icon-wrap-right fts-mashup-facebook-icon"><a href="' . esc_url( 'https://www.facebook.com/' . $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"></a></div>';
			}
			// show icon.
			if ( 'left' === ($saved_feed_options['facebook_show_social_icon'] ?? '') || 'left' === ($saved_feed_options['combine_show_social_icon'] ?? '') ) {
				echo '<div class="fts-mashup-icon-wrap-left fts-mashup-facebook-icon"><a href="' . esc_url( 'https://www.facebook.com/' . $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"></a></div>';
			}
			echo '<div class="fts-jal-fb-top-wrap ' . esc_attr( $saved_feed_options['facebook_hide_date_likes_comments'] ) . '" style="display:block !important;">';
			echo '<div class="fts-jal-fb-user-thumb">';

			$avatar_id                  = plugin_dir_url( __DIR__ ) . 'images/slick-comment-pic.png';
			$profile_photo_exists_check = isset( $facebook_post_profile_pic_url ) && strpos( $facebook_post_profile_pic_url, 'profilepic' ) !== false ? $facebook_post_profile_pic_url : $avatar_id;


            if( $saved_feed_options['facebook_page_feed_type'] === 'reviews' && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' )){
                echo '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $facebook_post->reviewer->name ) . '" src="' . esc_attr( $profile_photo_exists_check ) . '"></a>';
            }
            else {
                echo '<a href="https://www.facebook.com/' . esc_attr( $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer"><img border="0" alt="' . esc_attr( $facebook_post_from_name ) . '" src="' . esc_attr( $fts_main_profile_pic_url ) . '"/></a>';
            }

            echo '</div>';

			// UserName.
			echo '<span class="fts-jal-fb-user-name"><a href="' . esc_url( 'https://www.facebook.com/' . $facebook_post_from_id_picture ) . '" target="_blank" rel="noreferrer">' . esc_html( $facebook_post_from_name ) . '</a>' . esc_html( $facebook_hide_shared_by_etc_text ) . '</span>';

			// tied to date function.
			$feed_type      = 'facebook';
			$times          = $custom_time_format;
			$fts_final_date = $this->feed_functions->fts_custom_date( $times, $feed_type );
			// PostTime.
			echo '<span class="fts-jal-fb-post-time">' . $fts_final_date . '</span><div class="fts-clear"></div>';

			if ( ! empty( $facebook_post_places_id ) ) {
				$this->feed_location_option( $facebook_post_places_id, $facebook_post_name, $facebook_post_places_name );
			}

			// here we trim the words for the premium version. The $saved_feed_options['facebook_page_word_count']string actually comes from the javascript.
			if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && isset( $saved_feed_options['combine_word_count_option'] ) && $saved_feed_options['combine_word_count_option'] ||
                is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_page_word_count'] ) && $saved_feed_options['facebook_page_word_count'] ) {

                // SRL 4.0: Make this an option eventually.
                $more  = '...';

                $word_count = !empty( $saved_feed_options['combine_word_count_option'] ) ? $saved_feed_options['combine_word_count_option'] : $saved_feed_options['facebook_page_word_count'];

                $trunacate_words = new \FeedThemSocialTruncateHTML();
				$trimmed_content = $trunacate_words::fts_custom_trim_words( $facebook_message, $word_count , $more );

				echo '<div class="fts-jal-fb-message">';

				echo esc_html( $facebook_title_job_opening );
				echo ! empty( $trimmed_content ) ? $trimmed_content : '';
				echo '<div class="fts-clear"></div></div> ';

			} else {
				$facebook_final_message = $this->facebook_tag_filter( $facebook_message );
				echo '<div class="fts-jal-fb-message">';
				echo nl2br( $facebook_final_message );
				echo '<div class="fts-clear"></div></div>';
			}
			echo '</div>';

		}

		echo '<div class="fts-clear"></div>';
		echo '</div>';
		$facebook_post_single_id = isset( $facebook_post_single_id ) ? $facebook_post_single_id : '';
		$single_event_id   = isset( $single_event_id ) ? $single_event_id : '';

         /*echo '<pre>';
        print_r($facebook_post_type);
         echo '</pre>';*/

        $post_data = $facebook_post ?? '';

        $this->facebook_post_see_more( $facebook_post_link, $lcs_array, $facebook_post_type, $saved_feed_options, $facebook_post_username, $facebook_post_id, $facebook_post_user_id, $facebook_post_single_id, $single_event_id, $post_data );
        // old call..
           // $this->fts_facebook_post_see_more( $fb_link, $lcs_array, $fb_type, $fb_post_id, $fb_shortcode, $fb_post_user_id, $fb_post_single_id, $single_event_id, $post_data );

        echo '<div class="fts-clear"></div>';
		echo '</div>';

	}//end feed_post_types()
}//end class
