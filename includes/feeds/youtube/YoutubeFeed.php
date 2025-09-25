<?php
/**
 * Feed Them Social - YouTube Feed
 *
 * This page is used to create the YouTube feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\includes\feeds\youtube;

use feedthemsocial\includes\DebugLog;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class YouTube Feed
 *
 * @package feedthemsocial
 */
class YoutubeFeed {

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Feed Functions
     *
     * General Feed Functions to be used in most Feeds.
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Feed Cache
     *
     * Feed Cache class.
     *
     * @var object
     */
    public $feedCache;

    /**
     * Access Options
     *
     * Access Options for tokens.
     *
     * @var object
     */
    public $accessOptions;

    /**
     * Construct
     * u
     * FTS YouTube Feed constructor.
     *
     * @since 2.3.2
     */
    public function __construct( $settingsFunctions, $feedFunctions, $feedCache, $accessOptions ) {

        // Add Actions and Filters.
        $this->addActionsFilters();

        // Settings Functions Class.
        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        // Set Feed Cache object.
        $this->feedCache = $feedCache;

        // Access Options for tokens.
        $this->accessOptions = $accessOptions;
    }

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 4.0.0
     */
    public function addActionsFilters() {
        // no actions or filters to load at this time.
    }

    /**
     * Display YouTube
     *
     * @param string $feed_post_id Feed Post ID (CPT id).
     * @since 2.3.2
     */
    public function displayYoutube( $feed_post_id ) {

        if ( isset( $_REQUEST['next_url'] ) ) {
            $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );
            if ( 'www.googleapis.com' !== $next_url_host ) {
                wp_die( esc_html__( 'Invalid Google URL.', 'feed-them-social' ), 403 );
            }
        }

        // Saved Feed Options!
        $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feed_post_id );

        $youtube_api_key      = !empty( $saved_feed_options['youtube_custom_api_token'] ) ? $saved_feed_options['youtube_custom_api_token'] : '';
        $youtube_access_token = !empty( $saved_feed_options['youtube_custom_access_token'] ) ? $saved_feed_options['youtube_custom_access_token'] : '';


        if ( ! empty( $youtube_access_token ) && empty( $youtube_api_key ) ) {
            // this relies on our approved app from google.
            // we are only using readme option from google now so we cannot get comments this way.
            // that's fine though since we only allow to show comments in the premium version.
            //$youtube_api_key_or_token = FTS_ACCESS_TOKEN_EQUALS . $this->accessOptions->decryptAccessToken( $youtube_access_token );
            $youtube_api_key_or_token = FTS_ACCESS_TOKEN_EQUALS . $youtube_access_token;
        } else {
            // you must create your own youtube app now to get this.
            // this is also the method required to show comments as well now.
            //$youtube_api_key_or_token = 'key=' . $this->accessOptions->decryptAccessToken( $youtube_api_key );
            $youtube_api_key_or_token = 'key=' . $youtube_api_key;
        }

        if ( ! empty( $youtube_api_key ) || ! empty( $youtube_access_token ) ) {

            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            //$saved_feed_options['youtube_channelID'];
            // Video count. If not set in database used count of 4

            $vid_count = $saved_feed_options['youtube_vid_count'] ?? '4';

            // YouTube Show Follow Button Options.
            $youtube_show_follow_btn       = $saved_feed_options['youtube_show_follow_btn'];
            $youtube_show_follow_btn_where = $saved_feed_options['youtube-show-follow-btn-where'];

            $thumbs_play_iframe = $saved_feed_options['youtube_play_thumbs'];

            if( $saved_feed_options['youtube_feed_type'] === 'singleID' && !empty( $saved_feed_options['youtube_singleVideoID'] ) ){
                $wrap = $saved_feed_options['youtube_comments_wrap'] ?? '';
            }
            else {
                $wrap = $saved_feed_options['youtube_thumbs_wrap'] ?? '';
            }


            if ( ! $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && $vid_count > '6' ) {
                $vid_count = 6;
                $saved_feed_options['youtube_comments_count'] = '0';
            }
            if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && $saved_feed_options['youtube_play_thumbs'] === 'yes' ) {
                $popup          = 'no';
                // Thumb clicks play in iframe.
                $thumbs_play_iframe = 'yes';
            }
            if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && $saved_feed_options['youtube_play_thumbs'] === 'popup' ) {

                $popup                 = 'yes';
                $saved_feed_options['youtube_play_thumbs'] = 'yes';

                $fts_fix_magnific = $this->settingsFunctions->fts_get_option( 'remove_magnific_css' ) ?? '';
                if ( isset( $fts_fix_magnific ) && $fts_fix_magnific !== '1' ) {
                    wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.min.css' ), array(), FTS_CURRENT_VERSION, false );
                }
                wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );

            }
            if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && isset($saved_feed_options['youtube_load_more_option']) && $saved_feed_options['youtube_load_more_option'] === 'yes' ) {

                    $loadmore                  = !empty( $saved_feed_options['youtube_load_more_option']) ? $saved_feed_options['youtube_load_more_option'] : '';
                    $loadmore_type             = !empty( $saved_feed_options['youtube_load_more_style']) ? $saved_feed_options['youtube_load_more_style'] : '';
                    $loadmore_btn_margin       = !empty( $saved_feed_options['youtube_loadmore_button_margin']) ? $saved_feed_options['youtube_loadmore_button_margin'] : '';
                    $loadmore_btn_maxwidth     = !empty( $saved_feed_options['youtube_loadmore_button_width']) ? $saved_feed_options['youtube_loadmore_button_width'] : '';
                    $loadmore_background_color = !empty( $saved_feed_options['youtube_loadmore_background_color'] ) ? $saved_feed_options['youtube_loadmore_background_color'] : '';
                    $loadmore_text_color       = !empty( $saved_feed_options['youtube_loadmore_text_color']) ? $saved_feed_options['youtube_loadmore_text_color'] : '';

                    if ( ! empty( $loadmore_background_color ) || ! empty( $loadmore_text_color ) ) { ?>
                        <style type="text/css">

                        <?php
                        if ( ! empty( $loadmore_background_color ) ) {
                        ?>
                        .fts-youtube-load-more-wrapper .fts-fb-load-more {
                            background: <?php echo esc_html( $loadmore_background_color ); ?> !important;
                        }
                        <?php }

                        if ( ! empty( $loadmore_text_color ) ) { ?>
                        .fts-youtube-load-more-wrapper .fts-fb-load-more {
                            color: <?php echo esc_html( $loadmore_text_color ); ?> !important;
                        }
                        <?php
                        }

                        if ( ! empty( $loadmore_text_color ) ) {
                        ?>
                        .fts-youtube-load-more-wrapper .fts-fb-spinner > div {
                            background: <?php echo esc_html( $loadmore_text_color ); ?> !important;
                        }

                        <?php } ?>

                        </style>
                 <?php }
            }


            // YouTube has a limit of 50 per page and if you try to load more the array errors so we make sure that does not happen.
            if ( $vid_count > 50 ) {
                $vid_count = '50';
            }

            // Default Omit First Thumb to false.
            $omit_first_thumb = true;
            // If omit_first_thumbnail is set to yes then we make sure and skip the first iteration in the loop.
            if ( $saved_feed_options['youtube_omit_first_thumbnail'] === 'yes' ) {
                $omit_first_thumb = false;
                $vid_count++;
            }


            // Make sure its not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feedFunctions->getRandomString() );
                // Create Dynamic Class Name.
                $fts_dynamic_class_name = '';
                if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
                    $fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
                }
            }
            else {

                $fts_dynamic_class_name = $this->getFtsDynamicClassName();
            }

            // check to see of the user added a full youtube link instead of just the id and if so parse out everything but the id we need.
            if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', isset( $saved_feed_options['youtube_singleVideoID'] ) ? $saved_feed_options['youtube_singleVideoID'] : '', $match ) ) {
                $saved_feed_options['youtube_singleVideoID'] = $match[1];
            }


            if ( $saved_feed_options['youtube_feed_type'] !== 'singleID' ) {
                if ( $saved_feed_options['youtube_feed_type'] === 'username' && ! empty( $saved_feed_options['youtube_name'] ) ) {
                    // here we are getting the users channel ID for their uploaded videos.
                    $youtube_user_id_data = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=' . $saved_feed_options['youtube_name'] . '&' . $youtube_api_key_or_token;
                    // $user_id_returned              = $this->feedFunctions->ftsGetFeedJson( $youtube_user_id_data );
                    // $user_id_final                 = json_decode( $user_id_returned['items'] );
                    // YouTube Username.
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        $user_cache_name = 'yt_user_' . $saved_feed_options['youtube_name'];
                    }
                    else {
                        $user_cache_name = '';
                    }

                    $user_returned = $this->feedFunctions->useCacheCheck( $youtube_user_id_data, $user_cache_name, 'youtube' );

                    // If the YT User returned is not empty and is an array.
                    if ( ! empty( $user_returned ) && \is_array( $user_returned ) ) {

                        // Decode User's data.
                        $user_returned = json_decode( $user_returned['data'] );

                        DebugLog::log( 'YoutubeFeed', 'User Returned', $user_returned );

                        if ( \is_object( $user_returned ) && isset( $user_returned->items ) ) {
                            // User Playlist ID!
                            $user_playlist_id = !empty( $user_returned->items[0]->contentDetails->relatedPlaylists->uploads ) ? $user_returned->items[0]->contentDetails->relatedPlaylists->uploads : '';

                            DebugLog::log( 'YoutubeFeed', 'User Playlist', $user_playlist_id );

                            // now we parse the users uploaded vids ID and create the playlist.
                            $youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $vid_count . '&playlistId=' . $user_playlist_id . '&order=date&' . $youtube_api_key_or_token ) );
                        }
                    }

                    // YouTube Playlist Cache Name.
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        $feed_cache_name = 'pics_vids_list_' . $saved_feed_options['youtube_name'] . '_bnum' . $vid_count . '_user';
                    }
                }

                elseif ( $saved_feed_options['youtube_feed_type'] === 'channelID' && ! empty( $saved_feed_options['youtube_channelID'] ) ) {

                    $youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : sanitize_text_field( wp_unslash( 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $saved_feed_options['youtube_channelID'] . '&order=date&maxResults=' . $vid_count . '&' . $youtube_api_key_or_token ) );

                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        // YouTube Channel Cache.
                        $feed_cache_name = 'pics_vids_list_' . $saved_feed_options['youtube_channelID'] . '_bnum' . $vid_count . '_channel';
                    }
                }

                elseif ( $saved_feed_options['youtube_feed_type'] === 'playlistID' && ! empty( $saved_feed_options['youtube_playlistID'] )  ) {

                    // I don't understand the section here.. Need to clean this up. echo '<br/>playlistID shortcode in use: ';
                    $youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : $this->buildYoutubePlaylistUrl($vid_count, $saved_feed_options['youtube_playlistID'], $youtube_api_key_or_token );

                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        // YouTube Playlist Cache Folder.
                        $feed_cache_name = 'pics_vids_list_' . $saved_feed_options['youtube_playlistID'] . '_bnum' . $vid_count . '_playlist';
                    }
                }

                elseif ( $saved_feed_options['youtube_feed_type'] === 'userPlaylist' && ! empty( $saved_feed_options['youtube_playlistID2'] )  ) {

                    $youtube_feed_api_url = isset( $_REQUEST['next_url'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) : $this->buildYoutubePlaylistUrl($vid_count, $saved_feed_options['youtube_playlistID2'], $youtube_api_key_or_token );

                    $user_playlist_id = $saved_feed_options['youtube_playlistID2'];

                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        // YouTube Playlist Cache Folder.
                        $youtube_playlistID = !empty($saved_feed_options['youtube_playlistID']) ? $saved_feed_options['youtube_playlistID'] : '';
                        $feed_cache_name = 'pics_user_vids_list_' . $youtube_playlistID . '_bnum' . $vid_count . '_playlist';
                    }
                }

                if ( isset( $youtube_feed_api_url ) ) {
                    // YO!
                    // STOPPING HERE. SEEMS AS THOUGH THE URL OR SOMETHING IS NOT CACHING IDK
                    // echo ' why you no use cache check ';
                    // echo $youtube_feed_api_url;
                    // Call, fetch and Check data from API url! youtube URL: echo $youtube_feed_api_url;
                    $feed_cache_name = !empty( $feed_cache_name ) ? $feed_cache_name : '';
                    $feed_returned = $this->feedFunctions->useCacheCheck( $youtube_feed_api_url, $feed_cache_name, 'youtube' );

                    // JSON Decode the Feed Data.
                    $videos = json_decode( $feed_returned['data'] );

                    DebugLog::log( 'YoutubeFeed', 'playlistID and channelID shortcode used', $videos );
                }
            }

            ob_start();

            // SOCIAL BUTTON TOP.
            if ( ! isset( $_GET['load_more_ajaxing'] ) && $saved_feed_options['youtube_feed_type'] !== 'singleID' && 'yes' === $youtube_show_follow_btn && 'youtube-follow-above' === $youtube_show_follow_btn_where && ! isset( $_GET['load_more_ajaxing'] ) ) {
                echo '<div class="youtube-social-btn-top">';
                if ( $saved_feed_options['youtube_feed_type'] === 'username' && !empty( $saved_feed_options['youtube_name'] ) || $saved_feed_options['youtube_feed_type'] === 'userPlaylist'  && !empty( $saved_feed_options['youtube_name2'] ) ) {
                    $youtube_name = !empty($saved_feed_options['youtube_name']) ? $saved_feed_options['youtube_name'] : '';
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $youtube_name, $saved_feed_options );
                }
                elseif (  $saved_feed_options['youtube_feed_type'] === 'channelID' && !empty( $saved_feed_options['youtube_channelID'] )  ) {
                    $youtube_channelID = !empty($saved_feed_options['youtube_channelID']) ? $saved_feed_options['youtube_channelID'] : '';
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $youtube_channelID, $saved_feed_options );
                }
                elseif (  $saved_feed_options['youtube_feed_type'] === 'playlistID' && !empty( $saved_feed_options['youtube_channelID2'] ) ) {
                    $youtube_channelID2 = !empty($saved_feed_options['youtube_channelID2']) ? $saved_feed_options['youtube_channelID2'] : '';
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $youtube_channelID2, $saved_feed_options );
                }
                echo '</div>';
            }

            $youtubeDomain = 'https://www.youtube.com';

            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                if( !empty( $saved_feed_options['youtube_video_comments_display'] ) && !empty( $saved_feed_options['youtube_feed_type'] ) && $saved_feed_options['youtube_feed_type'] === 'singleID' ){
                    // The comments display options has a similar option like the thumbs display.
                    $video_thumbs_display = $saved_feed_options['youtube_video_comments_display'] ?? '1';
                }
                else {
                    $video_thumbs_display = $saved_feed_options['youtube_video_thumbs_display'] ?? 'none';
                }

               // Video Thumbs Display!
                switch ( $video_thumbs_display ){
                    case '1':
                        $video_thumbs_display = ' fts-youtube-thumbs-wrap-option-80-20';
                        break;
                    case '2':
                        $video_thumbs_display = ' fts-youtube-thumbs-wrap-option-60-40';
                        break;
                    case '3':
                        $video_thumbs_display = ' fts-youtube-thumbs-wrap-option-50-50';
                        break;
                    default:
                        break;
                }

                // If Premium is active and wrap isset
                if (isset($wrap)){
                    // Thumbs Wrap!
                    switch ( $wrap ){
                        case 'right':
                            $wrap = ' fts-youtube-thumbs-wrap ' . $video_thumbs_display;
                            break;
                        case 'left':
                            $wrap = ' fts-youtube-thumbs-wrap-left '. $video_thumbs_display;
                            break;
                        default:
                            $wrap = '';
                    }
                }

                $thumbgallery_class_master = $saved_feed_options['youtube_feed_type'] !== 'singleID' ? ' fts-youtube-thumbs-gallery-master ' : '';
                $youtube_name = !empty( $saved_feed_options['youtube_name'] ) ? $saved_feed_options['youtube_name'] : '';
                echo '<div class="et_smooth_scroll_disabled fts_smooth_scroll_disabled">';
                echo '<div id="fts-yt-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" class="' . esc_attr( $thumbgallery_class_master . 'fts-master-youtube-wrap fts-yt-videogroup fts-yt-user-' . esc_attr( $youtube_name ) . ' fts-yt-vids-in-row' . esc_attr( $saved_feed_options['youtube_columns'] ) ) . '">';
                echo '<div id="fts-yt-videolist-' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" class="fts-yt-videolist">';

                if ( isset( $videos->items ) &&  ( $saved_feed_options['youtube_first_video'] === 'yes' || $saved_feed_options['youtube_columns'] === '1') ) {
                    foreach ( $videos->items as $post_data ) {
                        // we check to make sure no playlist video kinds are in the array ($post_data->id->kind !== 'youtube#playlist') because they return a blank video in the channel feed because youtube is simply adding it to the array for youtube not thinking of the API in this case it would seem.
                        $video_check = isset( $post_data->id->kind ) && $post_data->id->kind === 'youtube#playlist' ? 'set' : 'notset';
                        if ( $video_check !== 'set' ) {
                            $second_video_margin_btm = $saved_feed_options['youtube_large_vid_title'] === 'yes' && $saved_feed_options['youtube_large_vid_description'] !== 'yes' ? 'fts-youtube-second-video-margin-btm' : '';

                            echo '<div class="fts-yt-large' . esc_attr( $wrap . ' ' . $second_video_margin_btm ) . '">';
                            echo '<div class="fts-yt-first-video">';

                            if ( $saved_feed_options['youtube_large_vid_title'] === 'yes' ) {
                                echo '<h2>' . esc_html( $this->ftsYoutubeTitle( $post_data ) ) . '</h2>';
                            }
                            // URL for the video is escaped in this function.
                            echo $this->ftsYoutubeVideoAndWrap( $post_data, $saved_feed_options['youtube_feed_type'] );

                            $youtube_description   = $this->ftsYoutubeTagFilter( $this->ftsYoutubeDescription( $post_data ) );
                             $saved_feed_options['youtube_large_vid_description'] = $saved_feed_options['youtube_large_vid_description'] === 'yes' ?  $saved_feed_options['youtube_large_vid_description'] : '';

                            if ( $saved_feed_options['youtube_large_vid_description'] === 'yes' ) {
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
                            // && $saved_feed_options['youtube_large_vid_title'] !== 'yes' &&  $saved_feed_options['youtube_large_vid_description'] !== 'yes'  are all about being set and if so they we show the oldschool 1 video with title and description format
                            if ( $saved_feed_options['youtube_play_thumbs'] !== 'yes' && $saved_feed_options['youtube_large_vid_title'] !== 'yes' && $saved_feed_options['youtube_large_vid_description'] !== 'yes' || $saved_feed_options['youtube_play_thumbs'] !== 'no' ) {
                                // we stop the foreach loop here because we only want the first video in the loop!
                                break;
                            }
                        }
                    }
                }

                $columns        =  $saved_feed_options['youtube_columns'] ?? '';
                $columns_tablet =  $saved_feed_options['youtube_columns_tablet'] ?? '';
                $columns_mobile =  $saved_feed_options['youtube_columns_mobile'] ?? '';
                $saved_feed_options['youtube_force_columns'] = isset( $saved_feed_options['youtube_force_columns'] ) ? $saved_feed_options['youtube_force_columns'] . '" ' : 'no';

                $saved_feed_options['youtube_container_margin'] = isset( $saved_feed_options['youtube_container_margin'] ) && $saved_feed_options['youtube_container_margin'] !== '' ? $saved_feed_options['youtube_container_margin'] : '1px';

                $thumbs_wrap_color_final  = isset( $saved_feed_options['youtube_thumbs_wrap_color']) ? 'background:' . $saved_feed_options['youtube_thumbs_wrap_color']. '!important' : '';
                $thumbs_wrap_color_scroll = isset( $saved_feed_options['youtube_thumbs_wrap_color']) ? 'background:' . $saved_feed_options['youtube_thumbs_wrap_color'] : '';

                if ( ! empty( $saved_feed_options['youtube_singleVideoID'] ) && $saved_feed_options['youtube_feed_type'] === 'singleID' ) {
                    echo '<div id="fts-yt-large-' . esc_attr( $saved_feed_options['youtube_singleVideoID'] ) . '" class="fts-yt-large' . esc_attr( $wrap ) . '">';
                    echo '<div class="fts-yt-first-video">';
                    echo $this->buildYoutubeIframe( $saved_feed_options['youtube_singleVideoID'] );
                    echo '</div>';
                    echo '</div>';

                } elseif ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && ! empty( $wrap ) ) {
                    $set_comments_height = $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && $wrap !== '' ? 'youtube-comments-wrap-premium ' : '';
                    echo '<div class="' . esc_attr( $set_comments_height ) . 'youtube-comments-wrap' . esc_attr( $wrap ) . ' youtube-comments-thumbs"  id="fts-yt-comments"></div>';
                }

                if ( ! empty( $saved_feed_options['youtube_thumbs_wrap_height'] ) || ! empty( $wrap ) ) {
                    echo '<div class="' . esc_attr( $fts_dynamic_class_name . ' fts-youtube-scrollable' . $wrap ) . '" style="height:250px;' . esc_attr( $thumbs_wrap_color_scroll ) . '" >';
                }

                $youtube_singleVideoID =  $saved_feed_options['youtube_feed_type'] === 'singleID' && !empty( $saved_feed_options['youtube_singleVideoID'] ) ? $saved_feed_options['youtube_singleVideoID'] : '';
                $thumbgallery_class     = $saved_feed_options['youtube_feed_type'] !== 'singleID' ? ' fts-youtube-no-thumbs-gallery' : '';

                echo '<div data-ftsi-columns="' . esc_attr( $columns ) . '" data-ftsi-columns-tablet="' . esc_attr( $columns_tablet ) . '" data-ftsi-columns-mobile="' . esc_attr( $columns_mobile ) . '" data-ftsi-force-columns="' . esc_attr( $saved_feed_options['youtube_force_columns'] ) . '" data-ftsi-margin="' . esc_attr( $saved_feed_options['youtube_container_margin'] ) . '" class="' . esc_attr( $fts_dynamic_class_name ) . ' fts-youtube-popup-gallery fts-youtube-inline-block-centered ' . esc_attr( $thumbgallery_class ) . '" style="' . esc_attr( $thumbs_wrap_color_final ) . '"">';

                if ( ! empty( $saved_feed_options['youtube_singleVideoID'] ) && $saved_feed_options['youtube_feed_type'] === 'singleID' ) {

                    $youtube_video_url = 'https://www.youtube.com/watch?v=' . $saved_feed_options['youtube_singleVideoID'];

                    $set_comments_height = $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && $wrap !== '' ? 'youtube-comments-wrap-premium ' : '';

                    if ( $wrap !== 'right' || $wrap !== 'left' ) {
                        echo '<div class="fts-youtube-noscroll">';
                    }

                    echo '<div class="' . esc_attr( $set_comments_height ) . 'youtube-comments-wrap' . esc_attr( $wrap ) . '"  style="display: block !important;">';

                    $this->ftsYoutubeSingleVideoInfo( $saved_feed_options['youtube_singleVideoID'], $youtube_api_key_or_token );

                    echo $this->feedFunctions->ftsShareOption( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
                    echo $this->getViewOnYoutubeLink( $youtube_video_url );

                    // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions granted to view comments.
                    if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' )  && isset( $saved_feed_options['youtube_comments_count'] ) && $saved_feed_options['youtube_comments_count'] !== '0' && !empty(  $saved_feed_options[ 'youtube_custom_api_token' ] ) ) {
                        $this->ftsYoutubeCommentThreads( $saved_feed_options['youtube_singleVideoID'], $youtube_api_key_or_token, $saved_feed_options['youtube_comments_count'] );
                    }

                    echo '</div>';

                    if ( $wrap !== 'right' || $wrap !== 'left' ) {
                        echo '</div>';
                    }

                    echo '</div>';

                }
            }
            if ( $saved_feed_options['youtube_columns'] !== '0' && $saved_feed_options['youtube_feed_type'] !== 'singleID' && $saved_feed_options['youtube_large_vid_title'] !== 'yes' && $saved_feed_options['youtube_large_vid_description'] !== 'yes' && isset( $videos->items ) ) {

                $count = '0';
                foreach ( $videos->items as $post_data ) {
                    $kind = $post_data->id->kind ?? '';
                    // if omit_first_thumbnail == yes then we make sure and skip the first iteration in the loop.
                    if ( !$omit_first_thumb ) {
                        $omit_first_thumb = true;
                        continue;
                    }

                    // print $omit_first_thumbnail;.
                    // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
                    if ( $kind !== 'youtube#playlist' ) {

                        $user_name_href = 'https://www.youtube.com/channel/' . $post_data->snippet->channelId;
                        $date           = $this->feedFunctions->ftsCustomDate( $post_data->snippet->publishedAt, 'youtube' );

                        $thumbnail = $post_data->snippet->thumbnails->standard->url ?? $post_data->snippet->thumbnails->high->url;

                        $saved_feed_options['youtube_maxres_thumbnail_images'] = isset( $saved_feed_options['youtube_maxres_thumbnail_images'] ) && $saved_feed_options['youtube_maxres_thumbnail_images'] !== '' ? $saved_feed_options['youtube_maxres_thumbnail_images'] : '';

                        if ( isset( $post_data->snippet->thumbnails->maxres->url ) && $saved_feed_options['youtube_maxres_thumbnail_images'] === 'yes' ) {
                            $thumbnail = $post_data->snippet->thumbnails->maxres->url;
                        }

                        if ( ! empty( $saved_feed_options['youtube_name'] ) || ! empty( $saved_feed_options['youtube_playlistID'] ) || ! empty( $saved_feed_options['youtube_playlistID2'] ) ) {
                            $video_id = !empty( $post_data->snippet->resourceId->videoId ) ? $post_data->snippet->resourceId->videoId : '';
                        } else {
                            $video_id = !empty( $post_data->id->videoId ) ? $post_data->id->videoId : '';
                        }

                        $popup_set = isset( $wrap ) && $wrap !== '' && $saved_feed_options['youtube_play_thumbs'] === 'yes' || ! $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ? 'slicker-youtube-placeholder-' . sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) . ' ' : '';

                        echo '<div class="' . esc_html( $popup_set ) . 'slicker-youtube-placeholder fts-youtube-' . esc_attr( $video_id ) . '" data-id="fts-youtube-id-' . esc_attr( $fts_dynamic_class_name ) . '" style="background-image:url(' . esc_url( $thumbnail ) . ')">';

                        $youtube_title       = $this->ftsYoutubeTitle( $post_data );
                        $youtube_description = $this->ftsYoutubeTagFilter( $this->ftsYoutubeDescription( $post_data ) );
                        $channel_title       = $post_data->snippet->channelTitle;

                        $url    = $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && isset($popup) && $popup === 'yes' && $thumbs_play_iframe !== 'yes' ? ' fts-yt-popup-open' : '';
                        $target = $thumbs_play_iframe === 'yes' ? '' : 'target="_blank"';

                        if ( ! empty( $saved_feed_options['youtube_name'] ) || ! empty( $saved_feed_options['youtube_playlistID'] ) ) { // https://www.youtube.com/watch?v=g9ArG6H_z0Q.

                            $youtube_video_url = $youtubeDomain . '/watch?v=' . $video_id;

                            $href         = isset( $thumbs_play_iframe ) && $thumbs_play_iframe === 'yes' ? 'javascript:;' : esc_url( $youtube_video_url );
                            $iframe_embed = $this->buildYoutubeIframeUrl( $video_id, true );
                            $iframe       = isset( $thumbs_play_iframe ) && $thumbs_play_iframe === 'yes' ? ' fts-youtube-iframe-click' : '';
                            // escaping the $href above because one option is html and one is url raw.
                            echo '<a href="' . $href . '" rel="' . esc_url( $iframe_embed ) . '" ' . esc_attr( $target ) . ' class="fts-yt-open' . esc_attr( $url . $iframe ) . '"></a>';

                            if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
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
                                echo $this->feedFunctions->ftsShareOption( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
                                echo $this->getViewOnYoutubeLink( $youtube_video_url );
                                // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions greanted to view comments.
                                if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' )  && isset( $saved_feed_options['youtube_comments_count'] ) && $saved_feed_options['youtube_comments_count'] !== '0' && !empty(  $saved_feed_options[ 'youtube_custom_api_token' ] ) ) {
                                    $this->ftsYoutubeCommentThreads( $video_id, $youtube_api_key_or_token, $saved_feed_options['youtube_comments_count'] );
                                }
                                echo '</div>';
                            }
                        } else {

                            $youtube_video_url =  $youtubeDomain . '/watch?v=' . $video_id;

                            $href         = isset( $thumbs_play_iframe ) && $thumbs_play_iframe === 'yes' ? esc_html( 'javascript:;' ) : esc_url( $youtube_video_url );
                            $iframe_embed = $this->buildYoutubeIframeUrl( $video_id, true );
                            $iframe       = isset( $thumbs_play_iframe ) && $thumbs_play_iframe === 'yes' ? ' fts-youtube-iframe-click' : '';
                            // escaping the $href above because one option is html and one is url raw.
                            echo '<a href="' . $href . '" rel="' . esc_url( $iframe_embed ) . '" ' . esc_attr( $target ) . ' class="fts-yt-open' . esc_attr( $url . $iframe ) . '"></a>';

                            if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
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
                                echo $this->feedFunctions->ftsShareOption( isset( $youtube_video_url ) ? $youtube_video_url : null, isset( $youtube_title ) ? $youtube_title : null );
                                echo $this->getViewOnYoutubeLink( $youtube_video_url );

                                // The comments will only work if the user has entered an API Key, an Access Token does not have enough permissions greanted to view comments.
                                if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' )  && isset( $saved_feed_options['youtube_comments_count'] ) && $saved_feed_options['youtube_comments_count'] !== '0' && !empty( $saved_feed_options[ 'youtube_custom_api_token' ] ) ) {
                                    $this->ftsYoutubeCommentThreads( $video_id, $youtube_api_key_or_token, $saved_feed_options['youtube_comments_count'] );
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

            if ( $saved_feed_options['youtube_feed_type'] !== 'singleID' ) {

                // Load More BUTTON Start.
                $youtube_load_more_text      = $saved_feed_options['youtube_load_more_text'] ?? __( 'Load More', 'feed-them-social' );
                $youtube_no_more_videos_text = $saved_feed_options['youtube_no_more_videos_text'] ?? __( 'No More Videos', 'feed-them-social' );

                if ( ! empty( $saved_feed_options['youtube_name'] ) ) {
                    // now we parse the users uploaded vids ID and create the playlist.
                    $next_url = isset( $videos->nextPageToken ) ? $this->buildYoutubePlaylistUrl( $vid_count, $saved_feed_options['youtube_playlistID'], $youtube_api_key_or_token, $videos->nextPageToken ) : '';
                } elseif ( ! empty( $saved_feed_options['youtube_channelID'] ) && empty( $saved_feed_options['youtube_playlistID'] ) ) {
                    $next_url = isset( $videos->nextPageToken ) ? 'https://www.googleapis.com/youtube/v3/search?pageToken=' . $videos->nextPageToken . '&part=snippet&channelId=' . $saved_feed_options['youtube_channelID'] . '&order=date&maxResults=' . $vid_count . '&' . $youtube_api_key_or_token : '';
                } elseif ( ! empty( $saved_feed_options['youtube_playlistID'] ) || ! empty( $saved_feed_options['youtube_playlistID'] ) && ! empty( $saved_feed_options['youtube_channelID'] ) ) {
                    $next_url = isset( $videos->nextPageToken ) ? $this->buildYoutubePlaylistUrl( $vid_count, $saved_feed_options['youtube_playlistID'], $youtube_api_key_or_token, $videos->nextPageToken ) : '';
                } elseif ( ! empty( $saved_feed_options['youtube_playlistID2'] ) || ! empty( $saved_feed_options['youtube_playlistID2'] ) && ! empty( $saved_feed_options['youtube_channelID2'] ) ) {
                    $next_url = isset( $videos->nextPageToken ) ? $this->buildYoutubePlaylistUrl( $vid_count, $saved_feed_options['youtube_playlistID2'], $youtube_api_key_or_token, $videos->nextPageToken ) : '';
                } else {
                    $next_url = '';
                }

                if ( ! empty( $loadmore ) && $loadmore === 'yes' && $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
                    $loadmore_count = isset( $vid_count ) ? $vid_count * 2 : '25';
                    // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of pics/videos.
                    $_REQUEST['next_url'] = ! empty( $loadmore ) && $loadmore === 'yes' ? str_replace( 'maxResults=' . $vid_count, 'maxResults=' . $loadmore_count, $next_url ) : $next_url;

                    ?><script>
                        var nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?>= "<?php echo  str_replace( ['"', "'"], '', $_REQUEST['next_url'] ); ?>";
                    </script>
                    <?php
                }
                // Make sure it's not ajaxing.
                if ( ! isset( $_GET['load_more_ajaxing'] ) && $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && ! empty( $loadmore ) && $loadmore === 'yes' ) {
                    $fts_dynamic_name       = sanitize_key( $_REQUEST['fts_dynamic_name'] );
                    $time                   = time();
                    $nonce                  = wp_create_nonce( $time . 'load-more-nonce' );
                    $fts_dynamic_class_name = $this->getFtsDynamicClassName();
                    ?>
                <script>
                    jQuery(document).ready(function() {

                    <?php if ( $loadmore_type === 'autoscroll' && $loadmore === 'yes' ) { ?>
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
                            console.log(nextURL_<?php echo sanitize_key( $fts_dynamic_name )  ?>);

                            var yes_ajax = "yes";
                            var feed_id = "<?php echo esc_js( $feed_post_id ); ?>";
                            var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ) ?>";
                            var fts_security = "<?php echo esc_js( $nonce ) ?>";
                            var fts_time = "<?php echo esc_js( $time ) ?>";

                            var feed_name = "feed_them_social";
                           // var loadmore_count = "vid_count=<?php // echo esc_js( $loadmore_count ) ?>";
                            //var feed_attributes = <?php //echo wp_json_encode( $atts ) ?>;

                            jQuery.ajax({
                                data: {
                                    action: "myFtsFbLoadMore",
                                    next_url: nextURL_<?php echo sanitize_key( $fts_dynamic_name ) ?>,
                                    fts_dynamic_name: fts_d_name,
                                    feed_name: feed_name,
                                  //  loadmore_count: loadmore_count,
                                    //feed_attributes: feed_attributes,
                                    load_more_ajaxing: yes_ajax,
                                    fts_security: fts_security,
                                    fts_time: fts_time,
                                    feed_id: feed_id
                                },
                                type: "GET",
                                url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>",
                                success: function( data ) {
                                    console.log("Well Done and got this from sever: " + data);

                                    var result = jQuery(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").append(data).filter(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").html();

                                    jQuery(".fts-youtube-popup-gallery.<?php echo esc_js( $fts_dynamic_class_name ) ?>").html(result);

                                    if( !nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?> ||  "no more" === nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) ?> ){
                                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo esc_js( $youtube_no_more_videos_text ) ?></div>');
                                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").removeAttr("id");
                                    }
                                    else {
                                        jQuery(".<?php echo esc_js( $fts_dynamic_class_name ) ?>").off('scroll');
                                    }

                                    <?php if ( $loadmore_type === 'button' && $loadmore === 'yes' ) { ?>
                                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").html("<?php echo esc_html( $youtube_load_more_text ) ?>");
                                    <?php } ?>

                                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ) ?>").removeClass("fts-fb-spinner");

                                    <?php if ( isset($popup) && $popup === 'yes' ) { ?>
                                        // We return this function again otherwise the popup won't work correctly for the newly loaded items.
                                        jQuery.fn.slickYoutubePopUpFunction();
                                    <?php } ?>

                                    // Reload the share each function otherwise you can't open share option.
                                    ftsShare();

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
                        <?php if ( $loadmore_type === 'autoscroll' && $loadmore === 'yes' ) { ?>
                                };
                            }) // end of scroll ajax load.
                        <?php } else { ?>
                            }); // end of click button.
                        <?php } ?>
                    }); // end of document.ready.
                </script><?php

                }//End Check.
                // for gallery option play_video_in_iframe.
                if ( $saved_feed_options['youtube_play_thumbs'] === 'yes' && ! isset( $_GET['load_more_ajaxing'] ) ) {
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
                ftsShare();
                
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
            }// END if($saved_feed_options['youtube_singleVideoID'] == '').
            // main closing div not included in ajax check so we can close the wrap at all times.
            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                $fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );

                // this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;".
                echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more-output"></div>';
                echo '</div><!--END main wrap for thumbnails-->';
                // END main wrap for thumbnails.
                if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && isset( $loadmore_type ) && 'autoscroll' === $loadmore_type && 'yes' === $loadmore ) {
                    echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader" style="' . esc_attr( $thumbs_wrap_color_final ) . '"></div>';
                }
                if ( ! empty( $saved_feed_options['youtube_thumbs_wrap_height'] ) || ! empty( $wrap ) ) {
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
                if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) && isset( $loadmore_type ) &&
                    $loadmore_type === 'button' && $loadmore === 'yes' &&  $saved_feed_options['youtube_feed_type'] !== 'singleID' ) {

                    echo '<div class="fts-youtube-load-more-wrapper">';
                    echo '<div id="loadMore_' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ) . '" style="';
                    if ( ! empty( $loadmore_btn_maxwidth ) ) {
                        echo 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
                    }
                    $loadmore_btn_margin = $loadmore_btn_margin ?? '20px';
                    echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $youtube_load_more_text ) . '</div>';
                    echo '</div>';
                }
            }//End Check.

            unset( $_REQUEST['next_url'] );

            // SOCIAL BUTTON BOTTOM.
            if (  ! isset( $_GET['load_more_ajaxing'] ) && $saved_feed_options['youtube_feed_type'] !== 'singleID' && isset( $youtube_show_follow_btn ) && 'yes' === $youtube_show_follow_btn && 'youtube-follow-below' === $youtube_show_follow_btn_where ) {
                echo '<div class="youtube-social-btn-bottom">';

                if ( $saved_feed_options['youtube_feed_type'] === 'username' && !empty( $saved_feed_options['youtube_name'] ) || $saved_feed_options['youtube_feed_type'] === 'userPlaylist'  && !empty( $saved_feed_options['youtube_name2'] ) ) {
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $saved_feed_options['youtube_name'], $saved_feed_options );
                }
                elseif (  $saved_feed_options['youtube_feed_type'] === 'channelID' && !empty( $saved_feed_options['youtube_channelID'] )  ) {
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $saved_feed_options['youtube_channelID'], $saved_feed_options );
                }
                elseif (  $saved_feed_options['youtube_feed_type'] === 'playlistID' && !empty( $saved_feed_options['youtube_channelID2'] ) ) {
                    echo $this->feedFunctions->socialFollowButton( 'youtube', $saved_feed_options['youtube_channelID2'], $saved_feed_options );
                }
                echo '</div>';
            }
            ?>
            <script>
                // This needs to load here below the feed to load properly for
                // Elementor page preview, and also some types of tabs that use js to load.
                jQuery(document).ready(function() {
                    slickremixImageResizingYouTube();
                });
            </script>
            <?php

            return ob_get_clean();

        } else {
            // NO Access tokens found.
            ?>
            <div class="fts-shortcode-content-no-feed fts-empty-access-token">
                <?php echo esc_html( 'Feed Them Social: YouTube Feed not loaded, please add an API Token or Access Token from the Gear Icon Tab of this feed.', 'feed-them-social' ); ?>
            </div>
            <?php
          }
    }

    /**
     * Get FTS Dynamic Class Name
     *
     * @return string
     * @since 1.9.6
     */
    public function getFtsDynamicClassName() {
        $fts_dynamic_class_name = '';
        if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
            $fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
        }
        return $fts_dynamic_class_name;
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
    public function ftsYoutubeTagFilter( $youtube_description ) {
        // Create links from @mentions and regular links.
        $youtube_description = preg_replace( '/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $youtube_description );
        $youtube_description = preg_replace( '/[#]+([0-9\p{L}]+)/u', '<a href="https://www.youtube.com/results?search_query=%23$1" target="_blank">$0</a>', $youtube_description );
        return nl2br( $youtube_description );
    }

    /**
     * YouTube Comments Thread
     *
     * @param string  $video_id Video id.
     * @param string  $youtube_api_key_or_token YouTube token.
     * @param integer $youtube_comments_count Comments Count.
     * @since 1.9.6
     */
    public function ftsYoutubeCommentThreads( $video_id, $youtube_api_key_or_token, $youtube_comments_count ) {
        $fts_comments_thread_nonce = wp_create_nonce( 'fts-comments-thread-nonce' );

        if ( wp_verify_nonce( $fts_comments_thread_nonce, 'fts-comments-thread-nonce' ) ) {

            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // YouTube Comment Cache!
                $youtube_comments_cache_url = 'video_comments_list_' . $video_id . '_number_comments_' . $youtube_comments_count . '';
            }

            // YouTube Use Comments Cache!
            if ( !empty($youtube_comments_cache_url) && $this->feedCache->ftsCheckFeedCacheExists( $youtube_comments_cache_url ) !== false && ! isset( $_GET['load_more_ajaxing'] ) ) {
                $comments = json_decode( $this->feedCache->ftsGetFeedCache( $youtube_comments_cache_url ) );
            } else {
                // https://developers.google.com/youtube/v3/docs/comments/list.
                $comments['items'] = 'https://www.googleapis.com/youtube/v3/commentThreads?' . $youtube_api_key_or_token . '&textFormat=plainText&part=snippet&videoId=' . $video_id . '&maxResults=' . $youtube_comments_count . '';
                $comments_returned = $this->feedFunctions->ftsGetFeedJson( $comments );
                $comments          = json_decode( $comments_returned['items'] );

                if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                    $this->feedCache->ftsCreateFeedCache( $youtube_comments_cache_url, $comments );
                }
            }

            if ( isset($comments->pageInfo->totalResults) && $comments->pageInfo->totalResults !== 0 && !empty($comments->items) ) {
                echo '<div class="fts-fb-comments-content">';
                foreach ( $comments->items as $comment_data ) {
                    $message = $comment_data->snippet->topLevelComment->snippet->textDisplay;
                    if ( $message !== '><!!' ) {

                        $youtube_comment = $this->ftsYoutubeTagFilter( $message );

                        echo '<div class="fts-fb-comment">';
                        echo '<a href="' . esc_url( $comment_data->snippet->topLevelComment->snippet->authorChannelUrl ) . '" target="_blank" class="">';
                        echo '<img src="' . esc_url( $comment_data->snippet->topLevelComment->snippet->authorProfileImageUrl ) . '" class="fts-fb-comment-user-pic"/>';
                        echo '</a>';
                        echo '<div class="fts-fb-comment-msg">';
                        echo '<span class="fts-fb-comment-user-name">';
                        echo '<a href="' . esc_url( $comment_data->snippet->topLevelComment->snippet->authorChannelUrl ) . '" target="_blank" class="">';
                        echo esc_html( $comment_data->snippet->topLevelComment->snippet->authorDisplayName );
                        echo '</a>';
                        echo '</span> ';
                        echo '<span class="fts-fb-comment-date">' . esc_html( $this->feedFunctions->ftsCustomDate( $comment_data->snippet->topLevelComment->snippet->publishedAt, 'youtube' ) ) . '</span><br/>';
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
     * FTS YouTube Single Video Info
     *
     * @param string $video_id Video id.
     * @param string $youtube_api_key_or_token YouTube token.
     * @since 1.9.6
     */
    public function ftsYoutubeSingleVideoInfo( $video_id, $youtube_api_key_or_token ) {
        $fts_single_video_nonce = wp_create_nonce( 'fts-single-video-thread-nonce' );

        if ( wp_verify_nonce( $fts_single_video_nonce, 'fts-single-video-thread-nonce' ) ) {

            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                // YouTube Comment Cache.
                $youtube_single_video_cache_name = 'video_single_' . $video_id . '';
            }
            // https://developers.google.com/youtube/v3/docs/comments/list.
            $api_url['items'] = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&' . $youtube_api_key_or_token . '&part=snippet';

            $video = $this->feedFunctions->useCacheCheck( $api_url, $youtube_single_video_cache_name, 'youtube_single' );

            $feed_data = json_decode( $video['items'] );

            foreach ( $feed_data->items as $video_data ) {
                $user_name_href      = 'https://www.youtube.com/channel/' . $video_data->snippet->channelId;
                $channel_title       = $video_data->snippet->channelTitle;
                $youtube_title       = $this->ftsYoutubeTitle( $video_data );
                $youtube_description = $this->ftsYoutubeTagFilter( $this->ftsYoutubeDescription( $video_data ) );
                $date                = $this->feedFunctions->ftsCustomDate( $video_data->snippet->publishedAt, 'youtube' );

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
     * YouTube Description
     *
     * @param object $post_data post data.
     * @return string
     * @since 4.3.9
     */
    public function ftsYoutubeDescription( $post_data ) {
        return $post_data->snippet->description ?? '';
    }

    /**
     * YouTube Title
     *
     * @param object $post_data post data.
     * @return string
     * @since 4.3.9
     */
    public function ftsYoutubeTitle( $post_data ) {
        return $post_data->snippet->title ?? '';
    }

    /**
     * Build YouTube Playlist "Load More" URL.
     *
     * Constructs the complete URL for fetching the next page of playlist items.
     *
     * @param string $next_page_token The token for the next page of results.
     * @param string $vid_count The number of videos to retrieve.
     * @param string $playlist_id The ID of the YouTube playlist.
     * @param string $api_key_or_token The API key or access token.
     * @return string The fully constructed "Load More" API URL.
     * @since 4.3.9
     */
    /**
     * Build YouTube Playlist URL for the initial request or the next page.
     *
     * @param string      $vid_count        The number of videos to retrieve.
     * @param string      $playlist_id      The ID of the YouTube playlist.
     * @param string      $api_key_or_token The API key or access token.
     * @param string|null $next_page_token  The token for the next page, if it exists.
     * @return string The fully constructed API URL.
     */
    private function buildYoutubePlaylistUrl( string $vid_count, string $playlist_id, string $api_key_or_token, string $next_page_token = null ): string {
        $base_url = 'https://www.googleapis.com/youtube/v3/playlistItems';

        $params = [
            'part'       => 'snippet',
            'maxResults' => $vid_count,
            'playlistId' => $playlist_id,
            'order'      => 'date',
        ];

        // Conditionally add the page token to the parameters if it exists.
        if ( ! empty( $next_page_token ) ) {
            $params['pageToken'] = $next_page_token;
        }

        $query_string = http_build_query( $params, '', '&' );

        return $base_url . '?' . $query_string . '&' . $api_key_or_token;
    }

    /**
     * Get "View on YouTube" link.
     *
     * @param string $video_url The URL of the YouTube video.
     * @return string The HTML for the link.
     * @since 4.3.9
     */
    private function getViewOnYoutubeLink( string $video_url ): string {
        return '<a href="' . esc_url( $video_url ) . '" target="_blank" class="fts-jal-fb-see-more">' . esc_html__( 'View on YouTube', 'feed-them-premium' ) . '</a>';
    }

    /**
     * Build YouTube Iframe URL.
     *
     * @param string $video_id The YouTube video ID.
     * @param bool   $autoplay Whether to autoplay the video.
     * @return string The URL for the YouTube iframe src.
     * @since 4.3.9
     */
    private function buildYoutubeIframeUrl( string $video_id, bool $autoplay = false ): string {
        $params = [
            'wmode'      => 'transparent',
            'HD'         => '0',
            'rel'        => '0',
            'showinfo'   => '0',
            'controls'   => '1',
            'autoplay'   => $autoplay ? '1' : '0',
        ];

        // The wmode=opaque is added for some players.
        if ($autoplay) {
            $params['wmode'] = 'opaque';
        }

        return esc_url( 'https://www.youtube.com/embed/' . $video_id . '?' . http_build_query( $params ) );
    }

    /**
     * Build YouTube Iframe HTML.
     *
     * @param string $video_id The YouTube video ID.
     * @param bool   $include_wrapper Whether to include the fts-fluid-videoWrapper div.
     * @return string The HTML for the YouTube iframe.
     * @since 4.3.9
     */
    private function buildYoutubeIframe( string $video_id, bool $include_wrapper = true ): string {
        $iframe_url = $this->buildYoutubeIframeUrl( $video_id, false );

        $iframe = '<iframe src="' . $iframe_url . '" frameborder="0" allowfullscreen></iframe>';

        if ( $include_wrapper ) {
            return '<div class="fts-fluid-videoWrapper">' . $iframe . '</div>';
        }

        return $iframe;
    }


    /**
     * FTS YouTube Video and Wrap
     *
     * @param object $post_data post data.
     * @param string $username username.
     * @param string $playlist_id playlist id.
     * @since 1.9.6
     */
    public function ftsYoutubeVideoAndWrap( $post_data, $feed_type ): string {
        if ( $feed_type === 'username' || $feed_type === 'userPlaylist' || $feed_type === 'playlistID' ) {
            $video_id = $post_data->snippet->resourceId->videoId ?? '';
        } else {
            // This is a channel
            $video_id = $post_data->id->videoId ?? '';
        }

        if ( ! empty( $video_id ) ) {
            return $this->buildYoutubeIframe( $video_id );
        }

        return '';
    }
}
