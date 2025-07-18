<?php
/**
 * Feed Them Social - Instagram Feed
 *
 * This file is used to create the Instagram Feeds
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace feedthemsocial\includes\feeds\instagram;

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class InstagramFeed
 *
 * @package feedthemsocial
 */
class InstagramFeed {

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
     * Feed Access Token
     *
     * Feed Access Token that should be decrypted.
     *
     * @var object
     */
    private $feedAccessToken;

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Data Protection
     *
     * The Data Protection class.
     *
     * @var object
     */
    public $dataProtection;

    /**
     * Construct
     *
     * Instagram Feed constructor.
     *
     * @since 1.9.6
     */
    public function __construct( $settingsFunctions, $feedFunctions, $feedCache, $accessOptions ) {

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
     * FB Custom Styles
     *
     * Custom Styles for feed in a shortcode.
     *
     * @param string $a First Date.
     * @return false|int
     * @since 4.0
     */
    public function instagramCustomStyles( $feed_post_id, $fts_instagram_slider ) {

        $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feed_post_id );

        // CSS options.
        $instagram_loadmore_background_color = $saved_feed_options['instagram_loadmore_background_color'] ?? '';
        $instagram_loadmore_text_color       = $saved_feed_options['instagram_loadmore_text_color'] ?? '';

        if( $fts_instagram_slider ) {
            $instagram_slider_arrow_color = $saved_feed_options['instagram_slider_arrow_color'] ?? '';
            $instagram_slider_arrow_hover_color = $saved_feed_options['instagram_slider_arrow_hover_color'] ?? '';
            $instagram_slider_dots_color = $saved_feed_options['instagram_slider_dots_color'] ?? '';
            $instagram_slider_dots_hover_color = $saved_feed_options['instagram_slider_dots_hover_color'] ?? '';
            $instagram_slider_nav_dots_margin = $saved_feed_options['instagram_slider_nav_dots_margin'] ?? '';
        }

?>
<style type="text/css">
<?php
if ( ! empty( $instagram_loadmore_background_color ) ) { ?>
.fts-instagram-load-more-wrapper .fts-fb-load-more { background: <?php echo esc_html( $instagram_loadmore_background_color ); ?> !important; }
<?php }

if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
.fts-instagram-load-more-wrapper .fts-fb-load-more { color: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important; }
<?php }

if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
.fts-instagram-load-more-wrapper .fts-fb-spinner > div { background: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important; }
<?php }

if( $fts_instagram_slider ){
if ( ! empty( $instagram_slider_dots_color  ) ) { ?>
.fts-tns-outer .fts-tns-nav > span { background: <?php echo esc_html( $instagram_slider_dots_color ); ?> !important; }
<?php }

 if ( ! empty( $instagram_slider_dots_hover_color  ) ) { ?>
.fts-tns-outer .fts-tns-nav > span:hover, .fts-tns-outer .fts-tns-nav > span.fts-tns-nav-active  { background: <?php echo esc_html( $instagram_slider_dots_hover_color ); ?> !important; }
<?php }
  if ( ! empty( $instagram_slider_arrow_color) ) { ?>
.fts-tns-outer .fts-tns-controls span path { fill: <?php echo esc_html( $instagram_slider_arrow_color ); ?> !important; }
<?php }

 if ( ! empty( $instagram_slider_arrow_hover_color) ) { ?>
.fts-tns-outer .fts-tns-controls span:hover path  { fill: <?php echo esc_html( $instagram_slider_arrow_hover_color ); ?> !important; }
<?php }

 if ( ! empty( $instagram_slider_nav_dots_margin) ) { ?>
.fts-tns-nav  { margin: <?php echo esc_html( $instagram_slider_nav_dots_margin ); ?> !important; }
<?php }
 } // end slider plugin active
 ?>
</style><?php
    }

    /**
     * Convert Instagram Description Links using
     *
     * Takes our description and converts and links to a tags.
     *
     * @param string $bio The Bio.
     * @return null|string
     * @since 1.9.6
     */
    public function convertInstagramDescriptionLinks( $bio ) {
        // Create links from @mentions and regular links.
        $bio = preg_replace( '~https?://[^<>\s]+~i', '<a href="$0" target="_blank" rel="noreferrer">$0</a>', $bio );
        $bio = preg_replace( '/#+(\w+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank" rel="noreferrer">$0</a>', $bio );
        $bio = preg_replace( '/@+(\w+)/u', '<a href="https://www.instagram.com/$1" target="_blank" rel="noreferrer">@$1</a>', $bio );

        return $bio;
    }

    /**
     * Convert Instagram Links
     *
     * Convert any link found in the description to a clickable one.
     *
     * @param string $instagram_caption_a_title Caption title.
     * @return null|string
     * @since 1.9.6
     */
    public function convertInstagramLinks( $instagram_caption_a_title ) {
        // Create links from @mentions, #hashtags and regular links.
        $instagram_caption_a_title = preg_replace( '~https?://[^<>\s]+~i', '<a href="$0" target="_blank" rel="noreferrer">$0</a>', $instagram_caption_a_title );
        $instagram_caption         = preg_replace( '/#+(\w+)/u', '<a href="https://www.instagram.com/explore/tags/$1" target="_blank" rel="noreferrer">$0</a>', $instagram_caption_a_title );
        $instagram_caption         = preg_replace( '/@+(\w+)/u', '<a href="https://www.instagram.com/$1" target="_blank" rel="noreferrer">@$1</a>', $instagram_caption );

        return $instagram_caption;
    }

    /**
     * Display Instagram Feed
     *
     * Outputs the Instagram Feed.
     *
     * @param string $feed_post_id Feed Post ID (CPT id).
     * @return mixed
     * @since 1.9.6
     */
    public function displayInstagram( $feed_post_id ) {

        if ( isset( $_REQUEST['next_url'] ) && !empty( $_REQUEST['next_url'] ) ) {
            $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );
            if ( $next_url_host !== 'graph.facebook.com' && $next_url_host !== 'graph.instagram.com' ) {
                wp_die( esc_html__( 'Invalid Facebook URL', 'feed_them_social' ), 403 );
            }
        }

        $fts_instagram_feed_nonce = wp_create_nonce( 'fts-instagram-feed-page-nonce' );

        if ( wp_verify_nonce( $fts_instagram_feed_nonce, 'fts-instagram-feed-page-nonce' ) ) {

            ob_start();

            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            $fts_premium          = $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' );
            $fts_instagram_slider = $this->feedFunctions->isExtensionActive( 'feed_them_social_instagram_slider' );

            // Saved Feed Options!
            $saved_feed_options = $this->feedFunctions->getSavedFeedOptions( $feed_post_id );

            // Set Variables based on Instagram Feed Type.
            switch( $saved_feed_options['instagram_feed_type'] ){
                case 'business':
                case 'hashtag':
                    $instagram_id   = !empty( $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token_user_id'] : '';
                    $access_token   = !empty($saved_feed_options['fts_facebook_instagram_custom_api_token'] ) ? $saved_feed_options['fts_facebook_instagram_custom_api_token'] : '';
                        // The check requires '' to be checked not empty() and it needs to be inside this case statement otherwise
                        // it will load the error message even though the feed is trying to load and the access token is set.
                        // Plus this way we can define what feed type it is.
                        if( $access_token === '' ){
                            ?>
                            <div class="fts-shortcode-content-no-feed fts-empty-access-token">
                                <?php echo esc_html( 'Feed Them Social: Instagram Business Feed not loaded, please add your Access Token from the Gear Icon Tab.', 'feed-them-social' ); ?>
                            </div>
                            <?php
                            return;
                        }
                    break;

                case 'basic':
                default:
                    $instagram_id            = !empty( $saved_feed_options['fts_instagram_custom_id'] ) ? $saved_feed_options['fts_instagram_custom_id'] : '';
                    $access_token            = !empty( $saved_feed_options['fts_instagram_custom_api_token'] ) ? $saved_feed_options['fts_instagram_custom_api_token'] : '';
                    // The check requires '' to be checked not empty() and it needs to be inside this case statement otherwise
                    // it will load the error message even though the feed is trying to load and the access token is set.
                    // Plus this way we can define what feed type it is.
                    if( $access_token === '' ){
                        ?>
                        <div class="fts-shortcode-content-no-feed fts-empty-access-token">
                            <?php echo esc_html( 'Feed Them Social: Instagram Basic Feed not loaded, please add your Access Token from the Gear Icon Tab.', 'feed-them-social' ); ?>
                        </div>
                        <?php
                        return;
                    }
                    break;

            }

            // Get our Additional Options.
            $this->instagramCustomStyles( $feed_post_id, $fts_instagram_slider );

            $height         = $saved_feed_options['instagram_page_height'] ?? '';
            $image_height   = $saved_feed_options['instagram_image_height'] ?? '';

            if ( $fts_premium || $fts_instagram_slider ) {

                $popup                 = $saved_feed_options['instagram_popup_option'] ?? '';
                $loadmore_option       = $saved_feed_options['instagram_load_more_option'] ?? '';
                $loadmore              = $saved_feed_options['instagram_load_more_style'] ?? '';
                $loadmore_btn_margin   = $saved_feed_options['instagram_loadmore_button_margin'] ?? '';
                $loadmore_btn_maxwidth = $saved_feed_options['instagram_loadmore_button_width'] ?? '';

                // $popup variable comes from the premium version
                if ( isset( $popup ) && $popup === 'yes' ) {
                    // it's ok if these styles & scripts load at the bottom of the page.
                    $fts_fix_magnific = $this->settingsFunctions->fts_get_option( 'remove_magnific_css' ) ?? '';
                    if ( $fts_fix_magnific !== '1' ) {
                        wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.min.css' ), array(), FTS_CURRENT_VERSION, false );
                    }
                    wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.min.js' ), array(), FTS_CURRENT_VERSION, false );
                }
            }
            elseif ( $saved_feed_options['instagram_pics_count'] === null ) {
                $saved_feed_options['instagram_pics_count'] = '6';
            }

            if ( ! $fts_premium && $saved_feed_options['instagram_pics_count'] > '6' && ! $fts_instagram_slider && $saved_feed_options['instagram_pics_count'] > '6') {
                $saved_feed_options['instagram_pics_count'] = '6';
            }

            if ( isset( $_GET['load_more_ajaxing'] ) ) {
                $saved_feed_options['instagram_pics_count'] = '10';
            }

            // Decrypt Access Token.
            $this->feedAccessToken = $this->accessOptions->decryptAccessToken( $access_token );

             $instagram_data_array = array();

            if ( $fts_premium || $fts_instagram_slider ) {
                $instagram_load_more_text      = $saved_feed_options['instagram_load_more_text'] ?? __( 'Load More', 'feed-them-social' );
                $instagram_no_more_photos_text = $saved_feed_options['instagram_no_more_photos_text'] ?? __( 'No More Posts', 'feed-them-social' );
            }

            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feedFunctions->getRandomString(10 ) . '_' . $saved_feed_options['instagram_feed_type'] );
                // Create Dynamic Class Name.
                $fts_dynamic_class_name = '';
                if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
                    $fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
                }
            }

            if ( isset( $_REQUEST['next_url'] ) ) {
                $_REQUEST['next_url'] = str_replace( FTS_ACCESS_TOKEN_XXX, FTS_ACCESS_TOKEN_EQUALS . $this->feedAccessToken, $_REQUEST['next_url'] );
            }
            // URL to get Feeds.
             $debug = 'false';
            if ( $saved_feed_options['instagram_feed_type'] === 'hashtag' ) {
                // cheezballs = 17843830210018045
                // sleepytime = 17841401899184039
                // Search for the Instagram hashtag ID's.
                // https://developers.facebook.com/docs/instagram-api/reference/ig-hashtag-search
                // https://developers.facebook.com/docs/instagram-api/reference/hashtag
                // These are the 2 types of things you can search for after getting the id of the hashtag from the above 2 links.
                // https://developers.facebook.com/docs/instagram-api/reference/hashtag/recent-media
                // https://developers.facebook.com/docs/instagram-api/reference/hashtag/top-media

                $hashtag = $saved_feed_options['instagram_hashtag'] ?? '';
                $search  = $saved_feed_options['instagram_hashtag_type'] ?? '';

                $cache_hashtag_id_array = 'instagram_hashtag_id_cache_' . $instagram_id . '_num' . $saved_feed_options['instagram_pics_count'] . '_search' . $search . '_hash' . $hashtag . '';

                if ( $this->feedCache->ftsCheckFeedCacheExists( $cache_hashtag_id_array ) === false ) {
                    // This call is required because users enter a hashtag name, then we have to check the API to see if it exists and if it does return the ID number for that hashtag.
                    $instagram_hashtag_data_array['data'] = FTS_FACEBOOK_GRAPH_URL . 'ig_hashtag_search?user_id=' . $instagram_id . '&q=' . $hashtag . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;

                    $hashtag_response = $this->feedFunctions->ftsGetFeedJson( $instagram_hashtag_data_array );

                    $hashtag_error_check = json_decode( $hashtag_response['data'] );

                    foreach ( $hashtag_error_check->data as $ht ) {
                        $hashtag_id = $ht->id;
                    }

                    $this->feedCache->ftsCreateFeedCache( $cache_hashtag_id_array, $hashtag_error_check );

                } else {
                    $response            = $this->feedCache->ftsGetFeedCache( $cache_hashtag_id_array );
                    $hashtag_error_check = json_decode( $response );

                   foreach ( $hashtag_error_check->data as $ht ) {
                        $hashtag_id = $ht->id;
                    }

                    // Used for Testing Only.
                    if ( current_user_can( 'administrator' ) && $debug === 'true' ) {
                        esc_html_e( 'Hash 1 Array Check Cached', 'feed-them-social' );
                        echo '<br/><pre>';
                        print_r( $hashtag_error_check );
                        echo '</pre>';
                    }
                }

                //  SRL: 01/19/21 Work around so we can load more than one hashtag shortcode on the same page that has the same user ID and pics_count otherwise it will be interpreted as
                //  whatever the first shortcodes hashtag making all the feeds have the same photos.
                $saved_feed_options['instagram_pics_count'] .= '?1=' . $hashtag;

                $hash_final_cache = 'instagram_final_cache_' . $instagram_id . '_num' . $saved_feed_options['instagram_pics_count'] . '_search' . $search . '_hash' . $hashtag . '';

                // The below needs to be cached and the hashtag ID above merged with like how we did below on line 493
                if ( $search === 'recent-media' || '' === $search ) {
                    // Now that we have the Instagram ID we can do a search for the endpoint 'Recent Media'.
                    $instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : FTS_FACEBOOK_GRAPH_URL . $hashtag_id . '/recent_media?user_id=' . $instagram_id . '&fields=timestamp,media_url,caption,comments_count,permalink,like_count,media_type,id,children{media_url,media_type,permalink}&limit=' . $saved_feed_options['instagram_pics_count'] . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;

                } elseif ( $search === 'top-media' ) {
                    // Now that we have the Instagram ID we can do a search for the endpoint 'Top Media'.
                    $instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : FTS_FACEBOOK_GRAPH_URL . $hashtag_id . '/top_media?user_id=' . $instagram_id . '&fields=timestamp,media_url,caption,id,comments_count,permalink,like_count,media_type,children{media_url,media_type,permalink}&limit=' . $saved_feed_options['instagram_pics_count'] . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;
                }

                // First we make sure the feed is not cached already before trying to run the Instagram API.
                if ( $this->feedCache->ftsCheckFeedCacheExists( $hash_final_cache ) === false ) {
                    // https://developers.facebook.com/docs/instagram/oembed#oembed-product
                    $hashtag_response = $this->feedFunctions->ftsGetFeedJson( $instagram_data_array );

                        $hashtag_error_check = json_decode( $hashtag_response['data'] );

                        foreach ( $hashtag_error_check->data as $media ) {
                            // Instagram hashtag data returned from the facebook API does not contain a thumbnail_url for videos.
                            // We have to use the instagram_oembed feature to grab the thumbnail_url for a video
                            // so we can display it in the feed for carousel album posts that contain a video. All posts including hashtag video posts link back to Instagram for that user as well.
                            if( $media->media_type === 'VIDEO' ){
                                $permalink = $media->permalink;
                                $instagram_business_data_array['data'] = FTS_FACEBOOK_GRAPH_URL . 'v9.0/instagram_oembed?url=' . $permalink . '&fields=thumbnail_url' . FTS_AND_ACCESS_TOKEN_EQUALS.$this->feedAccessToken;
                                $instagram_business_media_response     = $this->feedFunctions->ftsGetFeedJson( $instagram_business_data_array );
                                $instagram_business_media              = json_decode( $instagram_business_media_response['data'] );
                                $media->thumbnail_url = $instagram_business_media->thumbnail_url;

                             }

                            if( $media->media_type === 'CAROUSEL_ALBUM' ){
                                if( $media->children->data[0]->media_type === 'VIDEO' ){
                                    $permalink_child = $media->children->data[0]->permalink;
                                    $instagram_business_data_array_child['data'] = FTS_FACEBOOK_GRAPH_URL . 'v9.0/instagram_oembed?url=' . $permalink_child . '&fields=thumbnail_url' . FTS_AND_ACCESS_TOKEN_EQUALS.$this->feedAccessToken;
                                    $instagram_business_media_response_child     = $this->feedFunctions->ftsGetFeedJson( $instagram_business_data_array_child );
                                    $instagram_business_media_child             = json_decode( $instagram_business_media_response_child['data'] );
                                    $media->children->data[0]->thumbnail_url = $instagram_business_media_child->thumbnail_url;
                                }
                             }
                        }
                        unset($media); // unset the reference
                        $insta_data = $hashtag_error_check;

                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                          $this->feedCache->ftsCreateFeedCache( $hash_final_cache, $insta_data );
                    }
                }
                else {

                    $insta_data = $this->feedCache->ftsGetFeedCache( $hash_final_cache );

                    $insta_data = json_decode( $insta_data );

                    // Used for Testing Only.
                    if ( current_user_can( 'administrator' ) && $debug === 'true' ) {
                        esc_html_e( 'Hash 2 Array Check Cached', 'feed-them-social' );
                        echo '<br/><pre>';
                            print_r( $insta_data );
                        echo '</pre>';
                    }
                }
            }
            elseif ( $saved_feed_options['instagram_feed_type'] === 'business' ) {

                $business_cache = 'instagram_business_cache' . $instagram_id . '_num' . $saved_feed_options['instagram_pics_count'] . '';

                // this is not getting cached currently
                $instagram_data_array['user_info'] = FTS_FACEBOOK_GRAPH_URL . $instagram_id . '?fields=biography%2Cid%2Cig_id%2Cfollowers_count%2Cfollows_count%2Cmedia_count%2Cname%2Cprofile_picture_url%2Cusername%2Cwebsite' . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;

                // This only returns the next url and a list of media ids. We then have to loop through the ids and make a call to get each ids data from the API.
                $instagram_data_array['data'] = isset( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : FTS_FACEBOOK_GRAPH_URL . $instagram_id . '/media?limit=' . $saved_feed_options['instagram_pics_count'] . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;

                // First we make sure the feed is not cached already before trying to run the Instagram API.
                if ( $this->feedCache->ftsCheckFeedCacheExists( $business_cache ) === false ) {

                    $instagram_business_response = $this->feedFunctions->ftsGetFeedJson( $instagram_data_array );

                    $instagram_business = json_decode( $instagram_business_response['data'] );
                    $instagram_business_user_info = json_decode( $instagram_business_response['user_info'] );

                    // We loop through the media ids from the above $instagram_business_data_array['data'] and request the info for each to create an array we can cache.
                    $instagram_business_output = (object) [ 'data' => [] ];

                    foreach ( $instagram_business->data as $media ) {
                        $media_id                              = $media->id;
                        $instagram_business_data_array['data'] = FTS_FACEBOOK_GRAPH_URL . $media_id . '?fields=caption,comments_count,like_count,id,media_url,media_type,permalink,thumbnail_url,timestamp,username,children{media_url}' . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;
                        $instagram_business_media_response     = $this->feedFunctions->ftsGetFeedJson( $instagram_business_data_array );
                        $instagram_business_media              = json_decode( $instagram_business_media_response['data'] );
                        $instagram_business_output->data[]     = $instagram_business_media;
                    }


                    // The reason we array_merge the $instagram_business_output is because it contains the paging for next and previous links so we can loadmore posts
                    $insta_data = (object) array_merge( (array) $instagram_business_user_info, (array) $instagram_business, (array) $instagram_business_output );

                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        $this->feedCache->ftsCreateFeedCache( $business_cache, $insta_data );
                    }
                }

                else {
                    $insta_data = $this->feedCache->ftsGetFeedCache( $business_cache );

                    $insta_data = json_decode( $insta_data );

                    // Used for Testing Only.
                    if ( current_user_can( 'administrator' ) && $debug === 'true' ) {
                        esc_html_e( 'Business Array Check Cached', 'feed-them-social' );
                        echo '<br/><pre>';
                            print_r( $insta_data);
                        echo '</pre>';
                    }
                }
            }
            elseif ( $saved_feed_options['instagram_feed_type'] === 'basic' ) {

                    $basic_cache = 'instagram_basic_cache' . $instagram_id . '_num' . $saved_feed_options['instagram_pics_count'] . '';

                    $api_requests = array(
                        // Get the media count and other info for the user. We are only using the username and media_count in the feed. These are all the options for reference. id,username,media_count,account_type,profile_picture_url
                        'user_info' => 'https://graph.instagram.com/me?fields=id,username,media_count,profile_picture_url' . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken,
                        // This only returns the next url and a list of media ids. We then have to loop through the ids and make a call to get each ids data from the API.
                        'data' => isset($_REQUEST['next_url']) ? esc_url_raw($_REQUEST['next_url']) : 'https://graph.instagram.com/' . $instagram_id . '/media?limit=' . $saved_feed_options['instagram_pics_count'] . FTS_AND_ACCESS_TOKEN_EQUALS . $this->feedAccessToken
                    );

                    if( !empty( $instagram_id ) ) {
                        $feed_data = $this->feedFunctions->useCacheCheck( $api_requests, $basic_cache, 'instagram' );
                    }

                    // JSON Decode the Feed Data.
                    $insta_data = json_decode( $feed_data );

                    $media_count_prep = $insta_data->media_count ?? '';

                    // here we add a , for all numbers below 9,999.
                    if ( isset( $media_count_prep ) && $media_count_prep <= 9999 ) {
                        $media_count = number_format( (float) $media_count_prep );
                    }
                    // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
                    if ( isset( $media_count_prep ) && $media_count_prep >= 1000000 ) {
                        $media_count = round( ( $media_count_prep / 1000000 ), 1 ) . 'm';
                    }
                    // here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands.
                    if ( isset( $media_count_prep ) && $media_count_prep >= 10000 ) {
                        $media_count = round( ( $media_count_prep / 1000 ), 1 ) . 'k';
                    }
                    // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
                    if ( isset( $media_count_prep ) && $media_count_prep >= 1000000 ) {
                        $media_count = round( ( $media_count_prep / 1000000 ), 1 ) . 'm';
                    }
            }

            $instagram_user_info = ! empty( $response['user_info'] ) ? json_decode( $response['user_info'] ) : '';
            // URL to get Feeds.
            if ( $saved_feed_options['instagram_feed_type'] === 'business' ) {
                $username        = $insta_data->username ?? '';
                $bio             = $insta_data->biography ?? '';
                $full_name       = $insta_data->name ?? '';
                $website         = $insta_data->website ?? '';

            }
            elseif( $saved_feed_options['instagram_feed_type'] === 'basic' ){
                // Used for the follow button in header or footer of feed.
                 $username = $insta_data->username ?? '';
            }

            $profile_picture = $insta_data->profile_picture_url ?? '';

            // For Testing.
            $debug_userinfo = 'false';
            if ( current_user_can( 'administrator' ) && $debug_userinfo === 'true' ) {
                echo '<pre>';
                print_r( $instagram_user_info );
                echo '</pre>';
            }

            // ->pagination->next_url.
            if ( current_user_can( 'administrator' ) && $debug === 'true' ) {
                echo '<pre>';
                print_r( $response );
                echo '</pre>';
            }


            if ( ! isset( $insta_data->data ) || empty( $insta_data->data ) ) {
                if ( ! function_exists( 'curl_init' ) ) {
                    echo esc_html( 'Feed Them Social: cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social' );
                }
                return false;
            }

            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // Social Button.
                if ( isset( $profile_picture ) && $saved_feed_options['instagram_profile_wrap'] === 'yes' ) {
                    ?>
    <div class="fts-instagram-profile-wrap">
                    <?php if ( isset( $saved_feed_options['instagram_profile_photo'] ) && $saved_feed_options['instagram_profile_photo'] === 'yes' ) { ?>
            <div class="fts-profile-pic">
                <a href="https://www.instagram.com/<?php echo esc_attr( $username ); ?>" target="_blank" rel="noreferrer"><img
                            src="<?php echo esc_url( $profile_picture ); ?>" style="max-width:125px" title="<?php echo esc_attr( $username ); ?>" alt="<?php echo esc_attr( $username ); ?>"/></a>
            </div>
                        <?php
}

if ( isset( $saved_feed_options['instagram_profile_name'], $saved_feed_options['instagram_feed_type'] ) && $saved_feed_options['instagram_profile_name'] === 'yes' && $saved_feed_options['instagram_feed_type'] === 'business' ) {
    ?>
            <div class="fts-profile-name-wrap">

                <div class="fts-isnta-full-name"><?php echo esc_html( $full_name ); ?></div>
        <?php
        if ( isset( $username ) && $saved_feed_options['instagram_show_follow_btn'] === 'yes' && $saved_feed_options['instagram_show_follow_btn_where'] === 'instagram-follow-above' ) {
            echo '<div class="fts-follow-header-wrap">';
            echo $this->feedFunctions->socialFollowButton( 'instagram', $username, $saved_feed_options );
            echo '</div>';
        }
        ?>
            </div>
    <?php
}
// $profile stats comes from the shortcode
if ( isset( $saved_feed_options['instagram_profile_stats'], $saved_feed_options['instagram_feed_type'] ) && 'yes' === $saved_feed_options['instagram_profile_stats']  && 'business' === $saved_feed_options['instagram_feed_type'] ) {
    // These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
    $number_posted_pics_fb_api = isset( $insta_data->media_count ) ? $insta_data->media_count : '';
    $number_posted_pics        = isset( $insta_data->data->counts->media ) ? $insta_data->data->counts->media : $number_posted_pics_fb_api;
    // here we add a , for all numbers below 9,999.
    if ( isset( $number_posted_pics ) && $number_posted_pics <= 9999 ) {
        $number_posted_pics = number_format( (float) $number_posted_pics );
    }
    // here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
    if ( isset( $number_posted_pics ) && $number_posted_pics >= 1000000 ) {
        $number_posted_pics = round( ( $number_posted_pics / 1000000 ), 1 ) . 'm';
    }
    // here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands.
    if ( isset( $number_posted_pics ) && $number_posted_pics >= 10000 ) {
        $number_posted_pics = round( ( $number_posted_pics / 1000 ), 1 ) . 'k';
    }

    $number_followed_by_fb_api = isset( $insta_data->followers_count ) ? $insta_data->followers_count : '';
    $number_followed_by        = isset( $insta_data->data->counts->followed_by ) ? $insta_data->data->counts->followed_by : $number_followed_by_fb_api;
    // here we add a , for all numbers below 9,999.
    if ( isset( $number_followed_by ) && $number_followed_by <= 9999 ) {
        $number_followed_by = number_format( (float) $number_followed_by );
    }
    // here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions.
    if ( isset( $number_followed_by ) && $number_followed_by >= 1000000 ) {
        $number_followed_by = round( ( $number_followed_by / 1000000 ), 1 ) . 'm';
    }
    // here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands.
    if ( isset( $number_followed_by ) && $number_followed_by >= 10000 ) {
        $number_followed_by = round( ( $number_followed_by / 1000 ), 1 ) . 'k';
    }

    $number_follows_fb_api = isset( $insta_data->follows_count ) ? $insta_data->follows_count : '';
    $number_follows        = isset( $insta_data->data->counts->follows ) ? $insta_data->data->counts->follows : $number_follows_fb_api;
    // here we add a , for all numbers below 9,999.
    if ( isset( $number_follows ) && $number_follows <= 9999 ) {
        $number_follows = number_format( (float) $number_follows );
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
if ( isset( $saved_feed_options['instagram_profile_description'], $saved_feed_options['instagram_feed_type'] ) && $saved_feed_options['instagram_profile_description'] === 'yes' && $saved_feed_options['instagram_feed_type'] === 'business' ) {
    ?>

            <div class="fts-profile-description"><?php echo $this->convertInstagramDescriptionLinks( $bio ); ?>
                <a href="<?php echo esc_url( $website ); ?>"><?php echo esc_url( $website ); ?></a></div>

        <?php } ?>

        <div class="fts-clear"></div>

    </div>
                    <?php
                } elseif ( $saved_feed_options['instagram_show_follow_btn'] === 'yes' && $saved_feed_options['instagram_show_follow_btn_where'] === 'instagram-follow-above' && $saved_feed_options['instagram_feed_type'] !== 'hashtag' ) {
                    echo '<div class="instagram-social-btn-top">';
                    echo $this->feedFunctions->socialFollowButton( 'instagram', $username, $saved_feed_options );
                    echo '</div>';
                }

            // Determine if the scrollable container is needed.
            $is_scrollable = ( ! empty( $loadmore ) && $loadmore === 'autoscroll') || ! empty( $height );

                if ( $is_scrollable ) {
                    // Prepare style attributes in an array.
                    $styles   = array();
                    $styles[] = 'overflow:auto;';

                    if ( ! empty( $saved_feed_options['instagram_page_width'] ) ) {
                        $styles[] = 'max-width: ' . esc_attr( $saved_feed_options['instagram_page_width'] ) . ';';
                    }
                    if ( ! empty( $height ) ) {
                        $styles[] = 'height: ' . esc_attr( $height ) . ';';
                    }
                    ?>
                    <div class="fts-instagram-scrollable <?php echo esc_attr( $fts_dynamic_class_name ); ?>instagram" style="<?php echo implode( ' ', $styles ); ?>">
                        <?php
                }

                $saved_feed_options['instagram_columns']        = $saved_feed_options['instagram_columns'] ?? '';
                $saved_feed_options['instagram_force_columns']  = $saved_feed_options['instagram_force_columns'] ?? '';
                $saved_feed_options['instagram_columns_tablet'] = $saved_feed_options['instagram_columns_tablet'] ?? '';
                $saved_feed_options['instagram_columns_mobile'] = $saved_feed_options['instagram_columns_mobile'] ?? '';

                if( $fts_instagram_slider ) {
                    $saved_feed_options['instagram_slider_speed']        = $saved_feed_options['instagram_slider_speed'] ?? '500';
                    $saved_feed_options['instagram_slider_controls']     = $saved_feed_options['instagram_slider_controls'] ?? '';
                    $saved_feed_options['instagram_slider_edge_padding'] = $saved_feed_options['instagram_slider_edge_padding'] ?? '';
                    $saved_feed_options['instagram_slider_arrows_size']  = $saved_feed_options['instagram_slider_arrows_size'] ?? '30';
                    $instagram_slider_padding                            = !empty($saved_feed_options['instagram_slider_padding']) ? 'padding: ' . esc_attr( $saved_feed_options['instagram_slider_padding'] ) . '"' : '';
                }

                $instagram_slider_option =  $saved_feed_options['instagram_slider'] ?? '';
                $instagram_page_width    = !empty( $saved_feed_options['instagram_page_width'] ) ? 'style="max-width: ' . esc_attr( $saved_feed_options['instagram_page_width'] ) . '; margin:auto"' : '';
                $instagram_popup         = !empty( $popup ) && $popup === 'yes' ? 'popup-gallery ' : '';

                if( $fts_instagram_slider && $instagram_slider_option === 'yes'){
                    echo '<div id="fts-slider-' .esc_attr( $fts_dynamic_class_name ) .'" class="ftsi-slider-wrapper"  style="display:none;'.$instagram_slider_padding.'">
                            <div class="ftsi-slider-content" '.$instagram_page_width. '>
                                <div class="fts-instagram-slider ' .esc_attr( $instagram_popup  . $fts_dynamic_class_name ) .'" data-ftsi-slider-arrow-size="'. esc_attr( $saved_feed_options['instagram_slider_arrows_size'] ) .'" data-ftsi-slider-edge-padding="'. esc_attr( $saved_feed_options['instagram_slider_edge_padding'] ) .'" data-ftsi-slider-controls="'. esc_attr( $saved_feed_options['instagram_slider_controls'] ) .'" data-ftsi-slider-speed="'. esc_attr( $saved_feed_options['instagram_slider_speed'] ) .'" data-ftsi-slider-columns="'. esc_attr( $saved_feed_options['instagram_columns'] ) .'" data-ftsi-slider-columns-tablet="' . esc_attr( $saved_feed_options['instagram_columns_tablet'] ). '" data-ftsi-slider-columns-mobile="' .esc_attr( $saved_feed_options['instagram_columns_mobile'] ) .'" data-ftsi-slider-force-columns="'. esc_attr( $saved_feed_options['instagram_force_columns'] ) .'" data-ftsi-slider-padding="'. esc_attr( $saved_feed_options['instagram_space_between_photos'] ?? '1px' ) .'" data-ftsi-height="'. esc_attr( $image_height ) .'">';
                }
                else {
                    echo '<div id="' .esc_attr( $fts_dynamic_class_name ).'" class="fts-instagram-inline-block-centered ' .esc_attr( $instagram_popup  . $fts_dynamic_class_name ) .'" '. $instagram_page_width .' data-ftsi-columns="'. esc_attr( $saved_feed_options['instagram_columns'] ) .'" data-ftsi-columns-tablet="' . esc_attr( $saved_feed_options['instagram_columns_tablet'] ). '" data-ftsi-columns-mobile="' .esc_attr( $saved_feed_options['instagram_columns_mobile'] ) .'" data-ftsi-force-columns="'. esc_attr( $saved_feed_options['instagram_force_columns'] ) .'" data-ftsi-margin="'. esc_attr( $saved_feed_options['instagram_space_between_photos'] ?? '1px' ) .'" data-ftsi-height="'. esc_attr( $image_height ) .'">';
                }
                  $set_zero = 0;
            } // END Make sure it's not ajaxing

            if ( $insta_data->data) {
                foreach ( $insta_data->data as $post_data ) {
                    if ( isset( $set_zero ) && $set_zero === $saved_feed_options['instagram_pics_count'] ) {
                        break;
                    }

                    // Create Instagram Variables
                    // tied to date function.
                    $feed_type   = 'instagram';
                    $fb_api_time = isset( $post_data->timestamp ) ? $post_data->timestamp : '';
                    $times       = isset( $post_data->created_time ) ? $post_data->created_time : $fb_api_time;
                    // call our function to get the date.
                    $instagram_date = $this->feedFunctions->ftsCustomDate( $times, $feed_type );

                    if ( $saved_feed_options['instagram_feed_type'] === 'hashtag' || $saved_feed_options['instagram_feed_type'] === 'location' ) {
                        $username           = $post_data->user->username ?? '';
                        $profile_picture    = $post_data->user->profile_picture ?? '';
                        $full_name          = $post_data->user->full_name ?? '';
                        $instagram_username = $username;
                    } elseif ( $saved_feed_options['instagram_feed_type'] === 'basic' || $saved_feed_options['instagram_feed_type'] === 'business' ) {
                        $instagram_username = $post_data->username;
                        $username           = $instagram_username;
                    } else {
                        $instagram_username = $instagram_user_info->data->username;
                    }
                    $instagram_caption_a_hashtag_title = $post_data->caption ?? '';
                    $instagram_caption_a_title         = $post_data->caption->text ?? $instagram_caption_a_hashtag_title;
                    $instagram_caption_a_title         = htmlspecialchars( $instagram_caption_a_title );
                    $instagram_caption                 = $this->convertInstagramLinks( $instagram_caption_a_title );

                    $instagram_thumb_url                 = $post_data->images->thumbnail->url ?? '';
                    $instagram_lowrez_url                = $post_data->images->standard_resolution->url ?? '';
                    $instagram_video_standard_resolution = $post_data->videos->standard_resolution->url ?? '';
                    $instagram_slider_option             =  $saved_feed_options['instagram_slider'] ?? '';

                    if ( isset( $_SERVER['HTTPS'] ) ) {
                        $instagram_thumb_url  = str_replace( 'http://', 'https://', $instagram_thumb_url );
                        $instagram_lowrez_url = str_replace( 'http://', 'https://', $instagram_lowrez_url );
                    }

                    if( $fts_instagram_slider && $instagram_slider_option === 'yes'){ ?>
                        <div class="fts-instagram-slider-wrap">
                    <?php } ?>
                            <div class="slicker-instagram-placeholder fts-instagram-wrapper" style="background-image:url('<?php echo esc_url( $this->ftsInstagramImageLink( $post_data ) ); ?>')">
                        <?php
                        if ( $fts_premium && isset( $popup ) && $popup === 'yes' || $fts_instagram_slider && isset( $popup ) && $popup === 'yes' ) {
                            ?>
                        <div class="fts-instagram-popup-profile-wrap">
                            <div class="fts-profile-pic"><?php $user_type = isset( $saved_feed_options['instagram_feed_type'] ) && 'hashtag' === $saved_feed_options['instagram_feed_type'] ? 'explore/tags/' . $hashtag : $username; ?>
                                <a href="https://www.instagram.com/<?php echo esc_html( $user_type ); ?>" target="_blank" rel="noreferrer">
                            <?php
                            if ( !empty($profile_picture) ) {
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
                                    <a href="https://www.instagram.com/<?php echo esc_html( $user_type ); ?>" target="_blank" rel="noreferrer" style="color: #000;">
                                    <?php
                                    switch( $saved_feed_options['instagram_feed_type'] ){
                                        case 'user' :
                                            echo esc_html( $full_name );
                                            break;
                                        case 'basic' :
                                            echo '<span class="fts-insta-basic-name">' .esc_html( $instagram_username ).'</span>';
                                            echo '<small class="fts-insta-post-count-total">'.esc_html( $media_count ). ' ' . __( ' posts total', 'feed-them-social' ).'</small>';
                                            break;
                                        case 'business' :
                                            echo esc_html( $instagram_username );
                                            break;
                                        default :
                                            echo esc_html( '#' . $hashtag );
                                            break;
                                    }
                                    ?>
                                    </a>
                                </div>

                                <?php
                                if ( isset( $instagram_username ) && 'yes' === $saved_feed_options['instagram_show_follow_btn'] && 'instagram-follow-above' === $saved_feed_options['instagram_show_follow_btn_where'] && 'hashtag' !== $saved_feed_options['instagram_feed_type'] ) {
                                    echo '<div class="fts-follow-header-wrap">';
                                    echo $this->feedFunctions->socialFollowButton( 'instagram', $instagram_username, $saved_feed_options );
                                    echo '</div>';
                                }
                                ?>

                                <div class="fts-clear"></div>
                            </div>
                        </div>
                            <?php
                            // this is already escaping in the function, re escaping will cause errors.
                             echo $this->ftsInstagramPopupDescription( $post_data );
                        }

                        // We need to check the type now because hashtag feeds from facebooks API use all caps now.
                        $data_type_image    = isset( $post_data->type ) && 'image' === $post_data->type ? 'image' : 'IMAGE';
                        $data_type_video    = isset( $post_data->type ) && 'video' === $post_data->type ? 'video' : 'VIDEO';
                        $data_type_carousel = isset( $post_data->type ) && 'carousel' === $post_data->type ? 'carousel' : 'CAROUSEL_ALBUM';
                        $data_type_hashtag  = $post_data->media_type ?? '';
                        $data_type          = isset( $post_data->type ) ? $post_data->type : $data_type_hashtag;

                        // Check to see if a video is the first child if children are present
                        $instagram_basic_api_child_url = isset( $post_data->children->data[0]->media_url ) ? $post_data->children->data[0]->media_url : '';
                        $instagram_api_child_url       = isset( $post_data->carousel_media ) ? $post_data->carousel_media[0]->videos->standard_resolution->url : $instagram_basic_api_child_url;
                        // $child url is the fb/instagram api
                        $child_url       = isset( $post_data->children ) ? $post_data->children->data[0]->media_url : $instagram_api_child_url;
                        $data_type_child = ! empty( $child_url ) && false !== strpos( $child_url, 'mp4' ) ? 'VIDEO' : '';

                        ?>
                    <a href='<?php

                        if ( $fts_premium && isset( $popup ) && $popup === 'yes' && $data_type_image === $data_type ||
                             $fts_instagram_slider && isset( $popup ) && $popup === 'yes' && $data_type_image === $data_type ||
                            $fts_premium && $popup === 'yes' && $data_type_carousel === $data_type && empty( $data_type_child ) ||
                            $fts_instagram_slider && $popup === 'yes' && $data_type_carousel === $data_type && empty( $data_type_child )) {

                            print esc_url( $this->ftsInstagramImageLink( $post_data ) );

                        } elseif ( $fts_premium && $popup === 'yes' && $data_type_video === $data_type ||
                            $fts_instagram_slider && $popup === 'yes' && $data_type_video === $data_type ||
                            $fts_premium && $popup === 'yes' && ! empty( $data_type_child ) && $data_type_child === 'VIDEO' ||
                            $fts_instagram_slider && $popup === 'yes' && ! empty( $data_type_child ) && $data_type_child === 'VIDEO') {

                            // Some videos are not returned for a number of reason from the API so we fall back to the image url
                            print $this->ftsInstagramVideoLink( $post_data ) ? esc_url( $this->ftsInstagramVideoLink( $post_data ) ) : esc_url( $this->ftsInstagramImageLink( $post_data ) );

                        } else {
                            print esc_url( $this->ftsViewOnInstagramUrl( $post_data ) );
                        }
                        $fts_child = isset( $post_data->children ) || isset( $post_data->carousel_media ) ? 'fts-child-media ' : '';
                        ?>' title='<?php print esc_attr( $instagram_caption_a_title ); ?>' target="_blank" rel="noreferrer" class='<?php print $fts_child; ?>fts-instagram-link-target fts-slicker-backg
                        <?php
                        if ( $data_type_video === $data_type && isset( $popup ) && $popup === 'yes' && ! empty( $this->ftsInstagramVideoLink( $post_data ) ) || ! empty( $data_type_child ) && 'VIDEO' === $data_type_child && isset( $popup ) && $popup === 'yes' && ! empty( $this->ftsInstagramVideoLink( $post_data ) ) ) {
                            ?>fts-instagram-video-link<?php
                        } else {
                            ?> fts-instagram-img-link<?php
                        } ?>' style="height:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?> !important; width:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>; line-height:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>; font-size:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>;"><span class="fts-instagram-icon" style="height:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>; width:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>; line-height:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>; font-size:<?php echo esc_attr( $saved_feed_options['instagram_icon_size'] ); ?>;"></span></a>
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
                            foreach ( \array_slice( $carousel_media, 1 ) as $child ) {
                                $url_images            = isset( $child->images->standard_resolution->url ) ? $child->images->standard_resolution->url : '';
                                $url                   = isset( $child->videos->standard_resolution->url ) ? $child->videos->standard_resolution->url : $url_images;
                                $url_final             = isset( $child->media_url ) ? $child->media_url : $url;
                                $data_type_video_child = ! empty( $url_final ) && false != strpos( $url_final, 'mp4' ) ? 'video_media' : 'image_media';
                                ?>
                                <a href='<?php
                                if ( $fts_premium && isset( $popup ) && $popup === 'yes' && $data_type_video_child === 'image_media' ||
                                    $fts_instagram_slider && isset( $popup ) && $popup === 'yes' && $data_type_video_child === 'image_media' ) {
                                    print esc_url( $this->ftsInstagramImageLink( $child ) );
                                } elseif ( $fts_premium && $popup === 'yes' && $data_type_video_child === 'video_media' ||
                                    $fts_instagram_slider && $popup === 'yes' && $data_type_video_child === 'video_media' ) {
                                    print esc_url( $this->ftsInstagramVideoLink( $child ) );
                                }
                                ?>' title='<?php print esc_attr( $instagram_caption_a_title ); ?>' target="_blank" rel="noreferrer" class='fts-child-media fts-child-media-hide fts-instagram-link-target fts-slicker-backg
                                <?php
                                if ( $data_type_video_child === 'video_media' && isset( $popup ) && $popup === 'yes' ) {
                                    ?>
                                     fts-instagram-video-link
                                    <?php
                                } else {
                                    ?>
                                     fts-instagram-img-link<?php } ?>'></a>
                                <?php
                            }
                        } elseif ( isset( $data_type ) && 'VIDEO' === $data_type && !empty($this->ftsInstagramVideoLink( $post_data )) || isset( $data_type ) && $data_type === 'video' && !empty($this->ftsInstagramVideoLink( $post_data )) ) {
                            ?>
                            <div class="fts-instagram-video-image-wrapper"><div class="fts-instagram-video-image"></div></div>
                            <?php
                        }
                        ?>

                        <div class='slicker-date'>

                            <div class="fts-insta-date-popup-grab">
                            <?php
                            if ( $saved_feed_options['instagram_hide_date_likes_comments'] === 'yes' ) {
                                echo esc_html( $instagram_date );
                            } else {
                                echo '&nbsp;';
                            }
                            ?>
                            </div>
                        </div>
                    <div class='slicker-instaG-backg-link'>

                        <div class='slicker-instaG-photoshadow'></div>
                    </div>
                        <div class="fts-insta-likes-comments-grab-popup">

                            <?php
                                // this is already escaping in the function, re escaping will cause errors.
                                echo $this->feedFunctions->ftsShareOption( $this->ftsViewOnInstagramUrl( $post_data ), $this->ftsInstagramDescription( $post_data ) );

                                if ( $saved_feed_options['instagram_feed_type'] !== 'basic' && $saved_feed_options['instagram_hide_date_likes_comments'] === 'yes' ) {
                                    ?>
                                    <div class="fts-instagram-reply-wrap-left">
                                        <ul class='slicker-heart-comments-wrap'>
                                            <li class='slicker-instagram-image-likes'><?php echo esc_html( $this->ftsInstagramLikesCount( $post_data ) ); ?> </li>
                                            <li class='slicker-instagram-image-comments'>
                                                <span class="fts-comment-instagram"></span> <?php echo esc_html( $this->ftsInstagramCommentsCount( $post_data ) ); ?>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                        </div>
                </div>
                    <?php
                    if( $fts_instagram_slider && $instagram_slider_option === 'yes'){ ?>
                        </div>
                    <?php }
                        if ( isset( $set_zero ) ) {
                            $set_zero++;
                        }
                }

                if ( $fts_premium && $loadmore_option === 'yes' || $fts_instagram_slider && $loadmore_option === 'yes' ) {
                    // ******************
                    // Load More BUTTON Start
                    // Check to see if the next isset for the hashtag feed. If so then pass it down so it's used.
                    // ******************
                    $next_hashtag_url = isset( $insta_data->paging->next ) ? $insta_data->paging->next : '';
                    $next_url         = isset( $insta_data->pagination->next_url ) ? $insta_data->pagination->next_url : $next_hashtag_url;
                    // fb api uses limit for the post count and instagram api uses count.
                    // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of posts.
                    // SRL 4.0 slowly get rid of the loadmore count, this is overkill imo and overcomplicates what should be an easy process.
                    $loadmore_count = '';
                    $_REQUEST['next_url'] = $next_url;

                    $instagram_loadmore_count = $saved_feed_options['instagram_loadmore_count'] ?? '';

                    $access_token         = FTS_ACCESS_TOKEN_EQUALS . $this->feedAccessToken;
                    $_REQUEST['next_url'] = str_replace( $access_token, FTS_ACCESS_TOKEN_XXX, $next_url );
                    ?>
                    <script>var nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo str_replace( ['"', "'"], '', $_REQUEST['next_url'] ); ?>";</script>
                    <?php
                    // Make sure it's not ajaxing.
                    if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && ! empty( $loadmore ) ) {
                        $fts_dynamic_name = sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
                        $time             = time();
                        $nonce            = wp_create_nonce( $time . 'load-more-nonce' );
                        ?>
                <script>jQuery(document).ready(function () {
                        <?php
                        // $loadmore = load_more_posts_style shortcode att.
                        if ( 'autoscroll' === $loadmore ) { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode.
                            ?>

                            // If autoscroll
                            jQuery(".<?php echo esc_js( $fts_dynamic_class_name ) ?>instagram").bind("scroll",function() {

                                if( jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight ) {
                                   // console.log( jQuery(this).scrollTop() + jQuery(this).innerHeight() );
                                   // console.log( jQuery(this)[0].scrollHeight );
                        <?php }
                            else { ?>
                                // If button
                                jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").off().click(function() {
                        <?php } ?>
                                    jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
                                    var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                                    console.log(button);

                                    var feed_name = "feed_them_social";
                                    var feed_id = "<?php echo esc_js( $feed_post_id ); ?>";
                                    var yes_ajax = "yes";
                                    var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ); ?>";
                                    var fts_security = "<?php echo esc_js( $nonce ); ?>";
                                    var fts_time = "<?php echo esc_js( $time ); ?>";
                                    jQuery.ajax({
                                        data: {
                                            action: "myFtsFbLoadMore",
                                            next_url: nextURL_<?php echo sanitize_key( $fts_dynamic_name ); ?>,
                                            fts_dynamic_name: fts_d_name,
                                            load_more_ajaxing: yes_ajax,
                                            fts_security: fts_security,
                                            fts_time: fts_time,
                                            feed_name: feed_name,
                                            feed_id: feed_id
                                        },
                                        type: 'GET',
                                        url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
                                        success: function (data) {
                                            console.log('Well Done and got this from sever: ' + data);
                                            jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').append(data).filter('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').html();
                                            if (!nextURL_<?php echo esc_js( sanitize_key( $_REQUEST['fts_dynamic_name'] ) ); ?> || 'no more' === nextURL_<?php echo esc_js( sanitize_key( $_REQUEST['fts_dynamic_name'] ) ); ?> ) {
                                                jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').addClass('no-more-posts-fts-fb').html('<?php echo esc_js( $instagram_no_more_photos_text ); ?>');
                                                jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').removeAttr('id');
                                                jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>instagram").off('scroll');
                                            }

                                            jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php echo esc_js( $instagram_load_more_text ); ?>');
                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');

                                            <?php if( $saved_feed_options['instagram_feed_type'] === 'basic' && $instagram_loadmore_count === 'yes' ) { ?>
                                                // Retrieve the current value and convert it to an integer
                                                const currentValue = parseInt(jQuery('#fts-insta-update-post-count-<?php echo esc_js( $fts_dynamic_name ); ?>').html(), 10);

                                                // PHP value (assuming it's an integer)
                                                const additionValue = <?php echo esc_js( $saved_feed_options['instagram_pics_count'] ); ?>;
                                                // Add the values
                                                let newTotal = currentValue + additionValue;
                                                // Retrieve the value from the element with class 'fts-insta-media-count-total' and convert it to an integer
                                                const maxTotal = parseInt(jQuery('.fts-insta-media-count-total').html(), 10);
                                                // If newTotal exceeds maxTotal, set newTotal to maxTotal
                                                if (newTotal > maxTotal) {
                                                    newTotal = maxTotal;
                                                }
                                                // Update the element with the new value
                                                jQuery('#fts-insta-update-post-count-<?php echo esc_js( $fts_dynamic_name ); ?>').html(newTotal);
                                            <?php } ?>


                                            if (typeof ftsShare === 'function') {
                                                ftsShare(); // Reload the share each function otherwise you can't open share option
                                            }
                                            if( jQuery.isFunction(jQuery.fn.slickInstagramPopUpFunction) ){
                                                jQuery.fn.slickInstagramPopUpFunction(); // Reload this function again otherwise the popup won't work correctly for the newly loaded items
                                            }
                                            if (typeof outputSRmargin === "function") {
                                                outputSRmargin(document.querySelector('#margin').value)
                                            } // Reload our margin for the demo
                                            slickremixImageResizing(); // Reload our imagesizing function so the images show up proper
                                        }
                                    }); // end of ajax()
                                     return false;
                            // string $scrollMore is at top of this js script. exception for scroll option closing tag.
                            <?php if ( $loadmore === 'autoscroll' ) { ?>
                                    };
                                }); // end of scroll ajax load.
                            <?php } else { ?>
                                }); // end of click button.
                            <?php } ?>
                        }); // end of document.ready.
                    </script><?php

                    }//End Check.
                }
            }
            // main closing div not included in ajax check so we can close the wrap at all times.
            print '</div>'; // closing main div for photos and scroll wrap.
            if( $fts_instagram_slider && $fts_instagram_slider === 'yes'){ ?>
                    </div>
                </div>
            <?php } // closing slider wrapper and content div

            // Make sure it's not ajaxing.
            if ( $fts_premium && $loadmore_option === 'yes' && ! isset( $_GET['load_more_ajaxing'] ) ||
                $fts_instagram_slider && $loadmore_option === 'yes' && ! isset( $_GET['load_more_ajaxing'] ) ) {
                $fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );
                // this div returns outputs our ajax request via jquery append html from above.
                print '<div class="fts-clear"></div>';
                print '<div id="output_' . esc_attr( $fts_dynamic_name ) . '"></div>';
                if ( ! empty( $loadmore ) && $loadmore === 'autoscroll' ) {
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
            <?php } //end $height !== 'auto' && empty( $height ) == null. ?>
            <?php
            if ( ! empty( $loadmore ) && $loadmore === 'autoscroll' || ! empty( $height ) ) {
                print '</div>'; // closing height div for scrollable feeds.
            }

            if ( $fts_premium && $loadmore_option === 'yes' ||
                $fts_instagram_slider && $loadmore_option === 'yes') {
                // Make sure it's not ajaxing.
                if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                    print '<div class="fts-clear"></div>';
                    if ( ! empty( $loadmore ) && $loadmore === 'button' ) {

                        print '<div class="fts-instagram-load-more-wrapper">';
                        print '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" style="';
                        if ( $loadmore_btn_maxwidth !== '' ) {
                            print 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
                        }
                        $loadmore_btn_margin = $loadmore_btn_margin ?? '10px';
                        print 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $instagram_load_more_text ) . '</div>';
                        print '</div>';

                        if( $saved_feed_options['instagram_feed_type'] === 'basic' &&  $instagram_loadmore_count === 'yes'){ ?>
                            <div class="fts-instagram-post-count">
                                <span id="fts-insta-update-post-count-<?php echo esc_html( $fts_dynamic_name ); ?>"><?php echo $saved_feed_options['instagram_pics_count'] ?></span> <?php echo __( 'of', 'feed-them-social' ) . ' <span class="fts-insta-media-count-total">' . esc_html( $media_count ) . '</span>' ?>
                            </div>
                        <?php }

                    }
                }//End Check.
                unset( $_REQUEST['next_url'] );
            }
            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // Social Button.
                if ( isset( $username ) && $saved_feed_options['instagram_show_follow_btn'] === 'yes' && $saved_feed_options['instagram_show_follow_btn_where'] === 'instagram-follow-below' && $saved_feed_options['instagram_feed_type'] !== 'hashtag' ) {
                    echo '<div class="instagram-social-btn-bottom">';
                    echo $this->feedFunctions->socialFollowButton( 'instagram', $username, $saved_feed_options );
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
        } // end nonce
        return ob_get_clean();
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
    public function ftsInstagramLikesCount( $post_data ) {
        // These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
        $hastag_likes    = isset( $post_data->like_count ) ? $post_data->like_count : '';
        $instagram_likes = isset( $post_data->likes->count ) ? $post_data->likes->count : $hastag_likes;
        // here we add a , for all numbers below 9,999.
        if ( isset( $instagram_likes ) && $instagram_likes <= 9999 ) {
            $instagram_likes = number_format( (float) $instagram_likes );
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
    public function ftsInstagramCommentsCount( $post_data ) {
        $hastag_comments    = isset( $post_data->comments_count ) ? $post_data->comments_count : '';
        $instagram_comments = isset( $post_data->comments->count ) ? $post_data->comments->count : $hastag_comments;
        // here we add a , for all numbers below 9,999.
        if ( isset( $instagram_comments ) && $instagram_comments <= 9999 ) {
            $instagram_comments = number_format( (float) $instagram_comments );
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
    public function ftsInstagramLikesCommentsWrap( $post_data ) {
        return '<ul class="heart-comments-wrap"><li class="instagram-image-likes">' . $this->ftsInstagramLikesCount( $post_data ) . '</li><li class="instagram-image-comments">' . $this->ftsInstagramCommentsCount( $post_data ) . '</li></ul>';
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
    public function ftsInstagramImageLink( $post_data ) {

        $hashtag_children_pic = $post_data->children->data[0]->thumbnail_url ?? '';

        $hashtag_children = isset( $post_data->children ) ? $post_data->children->data[0]->media_url : '';

        $instagram_api_children = isset( $post_data->images ) ? $post_data->images->standard_resolution->url : $hashtag_children;

        $data_type_child = strpos( $hashtag_children, 'mp4' ) ? $hashtag_children_pic : $instagram_api_children;

        $hastag_media_url = $post_data->media_url ?? $data_type_child;

        $hastag_media_url_final = isset( $post_data->media_type ) && $post_data->media_type === 'VIDEO' ? $post_data->thumbnail_url : $hastag_media_url;

        $instagram_lowrez_url = $post_data->images->standard_resolution->url ?? $hastag_media_url_final;

        return $instagram_lowrez_url;
    }

    /**
     * FTS Instagram Carousel Images
     *
     * Instagram image url from the API
     *
     * @param string $post_data Post data.
     * @since 1.9.6
     */
    public function ftsInstagramCarouselLinks( $post_data ) {
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
    public function ftsInstagramVideoLink( $post_data ) {
        if ( isset( $post_data->children ) ) {
            $video = $post_data->children->data[0]->media_url ?? '';
        } else {
            $hashtag_children = $post_data->children->data[0]->media_url ?? '';

            $instagram_api_children = $post_data->videos->standard_resolution->url ?? $hashtag_children;

            $hashtag_media_url = $post_data->media_url ?? $instagram_api_children;

            $video = $post_data->videos->standard_resolution->url ?? $hashtag_media_url;
        }

        return $video;
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
    public function ftsInstagramDescription( $post_data ) {
        $hashtag_caption           = $post_data->caption ?? '';
        $instagram_caption_a_title = $post_data->caption->text ?? $hashtag_caption;
        $instagram_caption         = $this->convertInstagramLinks( $instagram_caption_a_title );
        $charset = \get_option('blog_charset') ?? 'UTF-8';
        $instagram_caption         = $this->escapeSpecialChars($instagram_caption, $charset);

        return $instagram_caption;
    }

    /**
     * Escape Special Chars
     *
     * Escape special characters in a string separate from links.
     *
     * @param string $text description text.
     * @return null|string|string[]
     * @since 4.2.8
     */
    public function escapeSpecialChars($text, $charset) {
        // Split the text into HTML tags and non-tag content
        $parts = preg_split('/(<[^>]+>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $escaped_text = '';

        foreach ($parts as $part) {
            if (preg_match('/^<[^>]+>$/', $part)) {
                // This part is an HTML tag, keep it as is (or escape attributes only)
                $escaped_text .= $this->escapeAttributes($part, $charset);
            } else {
                // This is normal text (not inside a tag), escape it
                $escaped_text .= htmlspecialchars($part, ENT_NOQUOTES, $charset);
            }
        }

        return $escaped_text;
    }

    /**
     * Escape Attributes
     *
     * Escape the attributes of an HTML tag.
     *
     * @param string $tag description text.
     * @param string $charset the character set.
     * @return null|string|string[]
     * @since 4.2.8
     */
    public function escapeAttributes($tag, $charset) {
        return preg_replace_callback(
            '/(\w+)=("[^"]*"|\'[^\']*\')/',
            function($matches) use ($charset) {
                // Strip the outer quotes, escape the value, and re-wrap with the same quote type
                $quote = $matches[2][0]; // Keep track of whether it was single or double quotes
                $escaped_value = htmlspecialchars(substr($matches[2], 1, -1), ENT_NOQUOTES, $charset);
                return $matches[1] . '=' . $quote . $escaped_value . $quote;
            },
            $tag
        );
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
    public function ftsViewOnInstagramUrl( $post_data ) {
        $hashtag_permalink   = $post_data->permalink ?? '';
        return $post_data->link ?? $hashtag_permalink;
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
    public function ftsViewOnInstagramLink( $post_data ) {
        return '<a href="' . esc_url( $this->ftsViewOnInstagramUrl( $post_data ) ) . '" class="fts-view-on-instagram-link" target="_blank" rel="noreferrer">' . esc_html__( 'View on Instagram', 'feed-them-social' ) . '</a>';
    }

    /**
     * Instagram Popup Description
     *
     * Outputs Full description for our popup
     *
     * @param string $post_data Post data.
     * @return string
     * @since 1.9.6
     */
    public function ftsInstagramPopupDescription( $post_data ) {
        return '<div class="fts-instagram-caption"><div class="fts-instagram-caption-content"><p>' . $this->ftsInstagramDescription( $post_data ) . '</p></div>' . $this->ftsViewOnInstagramLink( $post_data ) . '</div>';
    }
}//end class
