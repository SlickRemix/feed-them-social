<?php
/**
 * Feed Them Social - Facebook Feed
 *
 * This page is used to create the Facebook feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Facebook Feed
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Facebook_Feed {

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
     * Facebook Post Types
     *
     * Facebook Post Types class.
     *
     * @var object
     */
    public $facebook_post_types;

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settings_functions;

    /**
     * Access Options
     *
     * Access Options for tokens.
     *
     * @var object
     */
    public $access_options;

    /**
     * Feed Access Token
     *
     * Feed Access Token that should be decrypted.
     *
     * @var object
     */
    private $feed_access_token;

    /**
     * Construct
     *
     * Facebook Feed constructor.
     *
     * @since 1.9.6
     */
    public function __construct( $settings_functions, $feed_functions, $feed_cache, $facebook_post_types, $access_options ) {

        // Settings Functions Class.
        $this->settings_functions = $settings_functions;

        // Set Feed Functions object.
        $this->feed_functions = $feed_functions;

        // Set Feed Cache object.
        $this->feed_cache = $feed_cache;

        // Facebook Post Types.
        $this->facebook_post_types = $facebook_post_types;

        // Access Options for tokens.
        $this->access_options = $access_options;
    }

    /**
     * FB Custom Styles
     *
     * Custom Styles for feed in a shortcode.
     *
     * @param string $feed_post_id Feed Post ID.
     * @since 4.0
     */
    public function fb_custom_styles( $feed_post_id ) {

        $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_post_id );

        // CSS options.
        $fb_hide_no_posts_message       = $saved_feed_options['fb_hide_no_posts_message'] ?? '';
        $fb_header_extra_text_color     = $saved_feed_options['fb_header_extra_text_color'] ?? '';
        $fb_text_color                  = $saved_feed_options['fb_text_color'] ?? '';
        $fb_link_color                  = $saved_feed_options['fb_link_color'] ?? '';
        $fb_link_color_hover            = $saved_feed_options['fb_link_color_hover'] ?? '';
        $fb_feed_width                  = $saved_feed_options['fb_feed_width'] ?? '';
        $fb_feed_margin                 = $saved_feed_options['fb_feed_margin'] ?? '';
        $fb_feed_padding                = $saved_feed_options['fb_feed_padding'] ?? '';
        $fb_feed_background_color       = $saved_feed_options['fb_feed_background_color'] ?? '';
        $fb_post_background_color       = $saved_feed_options['fb_post_background_color'] ?? '';
        $fb_grid_posts_background_color = $saved_feed_options['fb_grid_posts_background_color'] ?? '';
        $fb_grid_border_bottom_color    = $saved_feed_options['fb_grid_border_bottom_color'] ?? '';
        $fb_loadmore_background_color   = $saved_feed_options['fb_loadmore_background_color'] ?? '';
        $fb_loadmore_text_color         = $saved_feed_options['fb_loadmore_text_color'] ?? '';
        $fb_border_bottom_color         = $saved_feed_options['fb_border_bottom_color'] ?? '';

        $fb_reviews_backg_color         = $saved_feed_options['fb_reviews_backg_color'] ?? '';
        $fb_reviews_text_color          = $saved_feed_options['fb-reviews-text-color'] ?? '';
        $fb_reviews_overall_rating_background_color   =  $saved_feed_options['fb_reviews_overall_rating_background_color'] ?? '';
        $fb_reviews_overall_rating_border_color       =  $saved_feed_options['fb_reviews_overall_rating_border_color'] ?? '';
        $fb_reviews_overall_rating_text_color         =  $saved_feed_options['fb_reviews_overall_rating_text_color'] ?? '';
        $fb_reviews_overall_rating_background_padding =  $saved_feed_options['fb_reviews_overall_rating_background_padding'] ?? '';

        $fb_max_image_width =  $saved_feed_options['fb_max_image_width'] ?? '';

        $fb_events_title_color   =  $saved_feed_options['fb_events_title_color'] ?? '';
        $fb_events_title_size    =  $saved_feed_options['fb_events_title_size'] ?? '';
        $fb_events_maplink_color =  $saved_feed_options['fb_events_map_link_color'] ?? '';

        $fb_text_size      =  $saved_feed_options['fb_text_size'] ?? '';

        ?>
        <style type="text/css">
        <?php
        if ( ! empty( $fb_hide_no_posts_message ) && $fb_hide_no_posts_message === 'yes' ) { ?>
        .fts-facebook-add-more-posts-notice {
            display: none !important;
        }
        <?php }

        if ( ! empty( $fb_header_extra_text_color ) ) { ?>
        .fts-jal-single-fb-post .fts-jal-fb-user-name a {
            color: <?php echo esc_html( $fb_header_extra_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_loadmore_background_color ) ) { ?>
        .fts-fb-load-more-wrapper .fts-fb-load-more {
            background: <?php echo esc_html( $fb_loadmore_background_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_loadmore_text_color ) ) { ?>
        .fts-fb-load-more-wrapper .fts-fb-load-more {
            color: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_loadmore_text_color ) ) { ?>
        .fts-fb-load-more-wrapper .fts-fb-spinner > div {
            background: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_text_color ) ) { ?>
        .fts-simple-fb-wrapper .fts-jal-single-fb-post,
        .fts-simple-fb-wrapper .fts-jal-fb-description-wrap,
        .fts-simple-fb-wrapper .fts-jal-fb-post-time,
        .fts-slicker-facebook-posts .fts-jal-single-fb-post,
        .fts-slicker-facebook-posts .fts-jal-fb-description-wrap,
        .fts-slicker-facebook-posts .fts-jal-fb-post-time {
            color: <?php echo esc_html( $fb_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_link_color ) ) { ?>
        .fts-simple-fb-wrapper .fts-jal-single-fb-post .fts-review-name,
        .fts-simple-fb-wrapper .fts-jal-single-fb-post a,
        .fts-slicker-facebook-posts .fts-jal-single-fb-post a,
        .fts-jal-fb-group-header-desc a {
            color: <?php echo esc_html( $fb_link_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_link_color_hover ) ) { ?>
        .fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover,
        .fts-simple-fb-wrapper .fts-fb-load-more:hover,
        .fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover,
        .fts-slicker-facebook-posts .fts-fb-load-more:hover,
        .fts-jal-fb-group-header-desc a:hover {
            color: <?php echo esc_html( $fb_link_color_hover ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_feed_width ) ) { ?>
        .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
            max-width: <?php echo esc_html( $fb_feed_width ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_max_image_width ) ) { ?>
        .fts-fb-large-photo, .fts-jal-fb-vid-picture, .fts-jal-fb-picture, .fts-fluid-videoWrapper-html5 {
            max-width: <?php echo esc_html( $fb_max_image_width ); ?> !important;
            float: left;
        }
        <?php }

        if ( ! empty( $fb_events_title_color ) ) { ?>
        .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
            color: <?php echo esc_html( $fb_events_title_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_events_title_size ) ) { ?>
        .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
            font-size: <?php echo esc_html( $fb_events_title_size ); ?> !important;
            line-height: <?php echo esc_html( $fb_events_title_size ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_events_maplink_color ) ) { ?>
        .fts-simple-fb-wrapper a.fts-fb-get-directions {
            color: <?php echo esc_html( $fb_events_maplink_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_feed_margin ) ) { ?>
        .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
            margin: <?php echo esc_html( $fb_feed_margin ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_feed_padding ) ) { ?>
        .fts-simple-fb-wrapper {
            padding: <?php echo esc_html( $fb_feed_padding ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_feed_background_color ) ) { ?>
        .fts-simple-fb-wrapper {
            background: <?php echo esc_html( $fb_feed_background_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_post_background_color ) ) { ?>
        .fts-mashup-media-top .fts-jal-single-fb-post {
            background: <?php echo esc_html( $fb_post_background_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_grid_posts_background_color ) ) { ?>
        .fts-slicker-facebook-posts .fts-jal-single-fb-post {
            background: <?php echo esc_html( $fb_grid_posts_background_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_border_bottom_color ) ) { ?>
        .fts-jal-single-fb-post {
            border-bottom-color: <?php echo esc_html( $fb_border_bottom_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_grid_border_bottom_color ) ) { ?>
        .fts-slicker-facebook-posts .fts-jal-single-fb-post {
            border-bottom-color: <?php echo esc_html( $fb_grid_border_bottom_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_backg_color ) ) { ?>
        .fts-review-star {
            background: <?php echo esc_html( $fb_reviews_backg_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_overall_rating_background_color ) ) { ?>
        .fts-review-details-master-wrap {
            background: <?php echo esc_html( $fb_reviews_overall_rating_background_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_overall_rating_border_color ) ) { ?>
        .fts-review-details-master-wrap {
            border-bottom-color: <?php echo esc_html( $fb_reviews_overall_rating_border_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_overall_rating_background_padding ) ) { ?>
        .fts-review-details-master-wrap {
            padding: <?php echo esc_html( $fb_reviews_overall_rating_background_padding ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_overall_rating_text_color ) ) { ?>
        .fts-review-details-master-wrap {
            color: <?php echo esc_html( $fb_reviews_overall_rating_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_reviews_text_color ) ) { ?>
        .fts-review-star {
            color: <?php echo esc_html( $fb_reviews_text_color ); ?> !important;
        }
        <?php }

        if ( ! empty( $fb_text_size ) ) {
        ?>
        .fts-jal-fb-group-display .fts-jal-fb-message, .fts-jal-fb-group-display .fts-jal-fb-message p, .fts-jal-fb-group-header-desc, .fts-jal-fb-group-header-desc p, .fts-jal-fb-group-header-desc a {
            font-size: <?php echo esc_html( $fb_text_size ); ?> !important;
        }
        <?php
        } ?>

        </style><?php
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
     * Display Facebook
     *
     * Outputs the Facebook Feed.
     *
     * @param integer $feed_post_id The ID of the Feed's CPT Post.
     * @return string
     * @since 1.9.6
     */
    public function display_facebook( $feed_post_id ) {

        if ( isset( $_REQUEST['next_url'] ) && !empty( $_REQUEST['next_url'] ) ) {
            $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );
            if ( $next_url_host !== 'graph.facebook.com' && $next_url_host !== 'graph.instagram.com' ) {
                wp_die( esc_html__( 'Invalid Facebook URL', 'feed-them-social' ), 403 );
            }
        }

        // Make sure everything is reset.
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Saved Feed Options!
        $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_post_id );

        // Decrypt Access Token.
        $fts_facebook_custom_api_token = !empty( $saved_feed_options['fts_facebook_custom_api_token'] ) ? $saved_feed_options['fts_facebook_custom_api_token'] : '';
        $this->feed_access_token = $this->access_options->decrypt_access_token( $fts_facebook_custom_api_token );

        $load_popup_scripts = false;
        if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
            // Load up some scripts for popup.
            $load_popup_scripts = true;
        }
        else {
            if ( $saved_feed_options['facebook_page_post_count'] === null ) {
                $saved_feed_options['facebook_page_post_count'] = '6';
            }
        }

        // Load Pop Up Scripts
        if( $load_popup_scripts ){
            $this->load_popup_scripts( $saved_feed_options );
        }

        // Get our Additional Options.
        $this->fb_custom_styles( $feed_post_id );

        if ( $saved_feed_options['facebook_page_feed_type'] === 'album_videos' ) {
            $saved_feed_options['facebook_page_feed_type'] = 'album_photos';
            $saved_feed_options['facebook_video_album'] = 'yes';
            $saved_feed_options['facebook_album_id'] = 'photo_stream';
            if ( isset( $saved_feed_options['facebook_loadmore_button_width'] ) && ! empty( $saved_feed_options['facebook_loadmore_button_width'] ) ) {
                $saved_feed_options['facebook_load_more_style'] = 'button';
            }
        }

        if ( ! is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && ! is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) && $saved_feed_options['facebook_page_post_count'] > '6' ) {
            $saved_feed_options['facebook_page_post_count'] = '6';
        }

        // UserName?.
        if ( empty( $saved_feed_options['fts_facebook_custom_api_token_user_id'] ) ) {
            ?>
            <div class="fts-shortcode-content-no-feed fts-empty-access-token">
                <?php echo esc_html( 'Feed Them Social: Facebook Feed not loaded, please add your Access Token from the Gear Icon Tab.', 'feed-them-social' ); ?>
            </div>
            <?php
            return;
        }
        if ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' && ! is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
            return '<div style="clear:both; padding:15px 0;">You must have FTS Facebook Reviews extension active to see this feed.</div>';
        }

        $type = $saved_feed_options['facebook_page_feed_type'] ?? '';
        if ( 'group' === $type || 'page' === $type || 'event' === $type ) {

            // EMPTY FACEBOOK POSTS OFFSET AND COUNT.
            // Option Now Being Removed from here and the Facebook Settings Page.
            // Setting it to blank so no matter what it will never error get_option('fb_count_offset');.
            $fb_count_offset = '';

            // View Link.
            // SRL: 8-19-22. Even though it shows not is use, leaving $fts_view_fb_link = ''; here just in case it's tied to something we are not aware of yet.
            $fts_view_fb_link = '';

            // Get Cache Name.
            $fb_cache_name = '';
            // Get language.
            $language = '';

            // Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
            $response2 = $this->get_facebook_feed_response( $saved_feed_options, $fb_cache_name, $language );

            // Test to see if the re-sort date option is working from function above.
            // print $this->dateSort;.
            $feed_data = !empty( $response2['feed_data'] ) ? $response2['feed_data'] : '';
            $feed_data_check = json_decode( $feed_data );

            // SHOW THE REGULAR FEEDS PRINT_R
            //  echo '<pre>';
            //  print_r($feed_data_check);
            //  echo '</pre>';
            // $idNew = array();
            // $idNew = explode(',', $saved_feed_options['fts_facebook_custom_api_token_user_id']);
            // Testing options before foreach loop
            // $idNew = 'tonyhawk';
            // print_r($feed_data_check->$idNew->data);.
            // 4.0 This was made for multiple facebook feed, that is no longer an option. Reference for now, delete in future if not used.

            if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
                $fts_count_ids = substr_count( $saved_feed_options['fts_facebook_custom_api_token_user_id'], ',' );
            } else {
                $fts_count_ids = '';
            }

            if ( isset( $feed_data_check->data ) ) {
                if ( $fts_count_ids >= 1 && $saved_feed_options['facebook_page_feed_type'] !== 'reviews' ) {
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

                // Test the created dates are being sorted properly
                // foreach($merged_array['data'] as $newSort) {
                // print date("jS F, Y", strtotime($newSort->created_time));
                // print '<br/>';
                // }.
                $set_zero = 0;
                foreach ( $feed_data_check->data as $post_count ) {

                    $fb_message         = $post_count->message ?? '';
                    $fb_story           = $post_count->story ?? '';
                    $facebook_post_type = $post_count->attachments->data[0]->type ?? '';
                    $fb_status_type     = $post_count->status_type ?? '';

                    // Testing.
                    // echo $facebook_post_type;

                    // SRL 4.0: Made some edits below, probably need to revisit this in the future and find a more efficient way of omitting specific posts.
                    // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types
                    // We will count all the ones that are like this and add that number to the output of posts to offset the posts we are filtering out. Line 278 needs the same treatment of if options.
                    if ( strpos( $fb_story, 'updated their website address' ) !== false ||
                        $facebook_post_type === 'profile_media' ||
                        $facebook_post_type === 'cover_photo' ||
                        $facebook_post_type === 'status' && empty( $fb_message ) && empty( $fb_story ) ||
                        $facebook_post_type === 'event' || $facebook_post_type === 'status' && strpos( $fb_story, 'changed the name of the event to' ) !== false ||
                        $facebook_post_type === 'status' && strpos( $fb_story, 'changed the privacy setting' ) !== false ||
                        $facebook_post_type === 'status' && strpos( $fb_story, 'an admin of the group' ) !== false ||
                        $facebook_post_type === 'status' && strpos( $fb_story, 'created the group' ) !== false ||
                        $facebook_post_type === 'status' && strpos( $fb_story, 'added an event' ) !== false ||
                        $facebook_post_type === 'event' && strpos( $fb_story, 'added an event' ) !== false ) {
                        $set_zero++;
                    } elseif ( $feed_data_check->data === '0' ) {
                        // If more than the 5 posts(default in free) or the post= from shortcode is set to the amount of posts that are being filtered above we will add 7 to the post count to try and get at some posts.
                        // This will only happen for Page and Group feeds.
                        $set_zero = '7';
                    }
                }// END POST foreach.

                // Result of the foreach loop above minus the empty posts and offset by those posts the actual number of posts entered is shown
                // $saved_feed_options['facebook_page_post_count'] = $result;.
                if ( ! empty( $fb_count_offset ) ) {
                    $set_zero              = $fb_count_offset;
                    $unset_count           = $saved_feed_options['facebook_page_post_count'] + $set_zero;
                    $saved_feed_options['facebook_page_post_count'] = $unset_count;
                } else {
                    $unset_count           = $saved_feed_options['facebook_page_post_count'] + $set_zero;
                    $saved_feed_options['facebook_page_post_count'] = $unset_count;
                }

                // SHOW THE $feed_data_check PRINT_R
                /* echo '<pre>';
                 print_r($feed_data_check);
                 echo '</pre>, ';*/
            }
            // END.
        }

        ob_start();

        // Uncomment these for testing purposes to see the actual count and the offset count
        // print   $set_zero;
        // print   $saved_feed_options['facebook_page_post_count'];
        // print   'asdfasdfasdf<br/>';
        // print   $facebook_post_type;

        // View Link.
        $fts_view_fb_link = $this->get_view_link( $saved_feed_options );
        // Get Cache Name.
        $fb_cache_name = $this->get_fb_cache_name( $saved_feed_options );
        // Get language.
        $language = $this->get_language( $saved_feed_options );

        if ( $saved_feed_options['facebook_page_feed_type'] !== 'reviews' ) {
            // Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
            $response = $this->get_facebook_feed_response( $saved_feed_options, $fb_cache_name, $language );
            // Json decode data and build it from cache or response.
            $page_data = !empty($response['page_data']) ? json_decode($response['page_data'], true) : null;
            $feed_data = !empty($response['feed_data']) ? json_decode( $response['feed_data'] ) : null;

            /*error_log(print_r($feed_data));*/
        }

        if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {

            $fts_facebook_reviews = new \feed_them_social_facebook_reviews\Facebook_Reviews_Feed( $this->feed_functions, $this->feed_access_token);

            if ( isset( $saved_feed_options['remove_reviews_no_description'] ) && 'yes' === $saved_feed_options['remove_reviews_no_description'] && ! isset( $_GET['load_more_ajaxing'] ) ) {

                $no_description_count = $fts_facebook_reviews->review_count_check( $saved_feed_options );

                // testing purposes
                // print ''. $no_description_count - $saved_feed_options['facebook_page_post_count'] .' = The amount of posts with no review text.';
                // this count includes our original posts count + the amount of posts we found with no description.
                $saved_feed_options['facebook_page_post_count'] = $no_description_count;
            }

            // Get Response (AKA Page & Feed Information) ERROR CHECK inside this function.
            $response = $this->get_facebook_feed_response( $saved_feed_options, $fb_cache_name, $language );

            $feed_data = json_decode( $response['feed_data'] );

            $feed_data = (object) $feed_data;
            // Add Feed Type to post array.
            // SHOW THE REVIEWS FEED PRINT_R
            // echo '<pre>';
            // print_r($feed_data );
            // echo '</pre>';
            if ( isset( $saved_feed_options['remove_reviews_no_description'] ) && 'yes' === $saved_feed_options['remove_reviews_no_description'] ) {
                // $no_description_count2 = 0;.

                if (isset($feed_data->data) && count($feed_data->data) > 0) {
                    foreach ($feed_data->data as $k => $v) {
                        if (!isset($v->review_text)) {
                            // print $v->reviewer->name . ' (Key# ' . $k . ') : Now Unset from array<br/>';.
                            unset($feed_data->data[$k]);
                            // $no_description_count2++;.
                        }
                    }
                }

            }
            $ratings_data = json_decode( $response['ratings_data'] );

            // SHOW THE REVIEWS RATING INFO PRINT_R
            // echo '<pre>';
            // print_r($ratings_data );
            // echo '</pre>';.
            // Add fts_profile_pic_url to the array so we can show profile photos for reviews and comments in popup

            if (isset($feed_data->data) && count($feed_data->data) > 0) {
                foreach ($feed_data->data as $post_array) {

                    $the_image = 'https://graph.facebook.com/' . $post_array->reviewer->id . '/picture?redirect=false&access_token=' . $this->feed_access_token;

                    $profile_pic_response = wp_remote_get($the_image);
                    $profile_pic_data = wp_remote_retrieve_body($profile_pic_response);
                    $profile_pic_output = json_decode($profile_pic_data);

                    // echo '<pre>';
                    // print_r($profile_pic_output->data->url);
                    // echo '</pre>';
                    $post_array->fts_profile_pic_url = $profile_pic_output->data->url;
                }
            }

        }

        else {
            $fts_facebook_reviews = '';
        }

        $fts_count_ids = is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ? substr_count( $saved_feed_options['fts_facebook_custom_api_token_user_id'], ',' ) : '';

        if ( $fts_count_ids >= 1 && $saved_feed_options['facebook_page_feed_type'] !== 'reviews' ) {

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
        // SHOW THE REGULAR FEEDS PRINT_R (WORKS FOR VIDEOS AND ALBUMS TOO)
        // echo '<pre>';
        // print_r($feed_data );
        // echo '</pre>';
        // If No Response or Error then return.
        if ( isset( $response[0], $response[1] ) && \is_array( $response ) && $response[0] === false ) {
            return $response[1];
        }

        // Make sure it's not ajaxing and we will allow the omition of certain album covers from the list by using omit_album_covers=0,1,2,3 in the shortcode.
        if ( ! isset( $_GET['load_more_ajaxing'] ) && $saved_feed_options['facebook_page_feed_type'] === 'albums' ) {

            // omit_album_covers=0,1,2,3 for example.
            $omit_album_covers     = !empty( $saved_feed_options['facebook_omit_album_covers'] ) ? $saved_feed_options['facebook_omit_album_covers'] : '';
            $omit_album_covers_new = array();
            $omit_album_covers_new = explode( ',', $omit_album_covers );
            if (isset($feed_data->data) && \count($feed_data->data) > 0) {
                foreach ($feed_data->data as $post_data) {
                    foreach ($omit_album_covers_new as $omit) {
                        unset($feed_data->data[$omit]);
                    }
                }
            }
        }


        // Reviews Rating Filter.
        if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {

            if (isset($feed_data->data) && \count($feed_data->data) > 0) {
                foreach ($feed_data->data as $key => $post_data) {
                    // We are not going to show the unrecommended reviews in the feed at this point, no options in our plugin srl.
                    if (isset($post_data->recommendation_type) && $post_data->recommendation_type === 'negative' ) {
                        unset($feed_data->data[$key]);
                    }
                }
            }

        }

        // Make sure it's not ajaxing.
        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
            // Get Response (AKA Page & Feed Information).
            $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feed_functions->get_random_string( 10 ) . '_' . $saved_feed_options['facebook_page_feed_type'] );
            // Create Dynamic Class Name.

            $fts_dynamic_class_name =  $this->feed_functions->get_feed_dynamic_class_name();
            // SOCIAL BUTTON.

            if ( ! $fts_count_ids >= 1 && $saved_feed_options['facebook_hide_like_box_button'] === 'no' ) {
                $this->fb_social_btn_placement( $saved_feed_options, 'above_title' );
            }

            // fts-fb-header-wrapper (for grid).
            echo isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] !== 'yes' && $saved_feed_options['facebook_page_feed_type'] !== 'album_photos' && $saved_feed_options['facebook_page_feed_type'] !== 'albums' ? '<div class="fts-fb-header-wrapper">' : '';

            // Header.
            echo '<div class="fts-jal-fb-header">';

            if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && isset( $saved_feed_options['reviews_overall_rating_show'] ) && $saved_feed_options['reviews_overall_rating_show'] === 'yes' ) {

                // $feed_data_rating_overall = new \feed_them_social_facebook_reviews\Facebook_Reviews_Feed( $this->feed_functions, $this->feed_access_token );
                // echo $this->get_facebook_overall_rating_response($saved_feed_options, $fb_cache_name, $this->feed_access_token);.

                $fb_reviews_overall_rating_of_5_stars_text        = $saved_feed_options['fb_reviews_overall_rating_of_5_stars_text'];
                $fb_reviews_star_language                         = ! empty( $fb_reviews_overall_rating_of_5_stars_text ) ? ' ' . $fb_reviews_overall_rating_of_5_stars_text : ' of 5 stars';
                $fb_reviews_overall_rating_reviews_text           = $saved_feed_options['fb_reviews_overall_rating_reviews_text'];
                $fb_reviews_overall_rating_reviews_text           = ! empty( $fb_reviews_overall_rating_reviews_text ) ? ' ' . $fb_reviews_overall_rating_reviews_text : ' reviews';
                $fb_reviews_overall_rating_background_border_hide = $saved_feed_options['fb_reviews_overall_rating_background_border_hide'];
                $fb_reviews_overall_rating_background_border_hide = ! empty( $fb_reviews_overall_rating_background_border_hide ) && $fb_reviews_overall_rating_background_border_hide === 'yes' ? ' fts-review-details-master-wrap-no-background-or-border' : '';
                echo '<div class="fts-review-details-master-wrap' . esc_attr( $fb_reviews_overall_rating_background_border_hide ) . '" itemscope itemtype="http://schema.org/CreativeWork"><i class="fts-review-star">' . esc_html( $ratings_data->overall_star_rating ) . ' &#9733;</i>';
                echo '<div class="fts-review-details-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><div class="fts-review-details"><span itemprop="ratingValue">' . esc_html( $ratings_data->overall_star_rating ) . '</span>' . esc_html( $fb_reviews_star_language ) . '</div>';
                echo '<div class="fts-review-details-count"><span itemprop="reviewCount">' . esc_html( $ratings_data->rating_count ) . '</span>' . esc_html( $fb_reviews_overall_rating_reviews_text ) . '</div></div></div>';

            }
            if ( $saved_feed_options['facebook_page_feed_type'] !== 'reviews' ) {

                $page_description = $page_data['description'] ?? '';
                $page_name   = $page_data['name'] ?? '';
                $fb_title_htag = $saved_feed_options[ 'fb_title_htag' ] ?? 'h1';

                if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                    // echo our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
                    $fb_title_htag_size = isset( $saved_feed_options[ 'fb_title_htag_size' ] ) ? 'font-size:' . $saved_feed_options[ 'fb_title_htag_size' ] . ';' : '';
                    $fts_align_title    = isset( $saved_feed_options['facebook_page_title_align']) && $saved_feed_options['facebook_page_title_align'] !== '' ? 'style=text-align:' . $saved_feed_options['facebook_page_title_align']. ';' . $fb_title_htag_size . '' : $fb_title_htag_size;
                    echo isset( $saved_feed_options['facebook_page_title'] ) && $saved_feed_options['facebook_page_title'] !== 'no' ? '<' . esc_html( $fb_title_htag ) . ' ' . esc_attr( $fts_align_title ) . '><a href="' . esc_url( $fts_view_fb_link ) . '" target="_blank" rel="noreferrer">' . esc_html( $page_name ) . '</a></' . esc_html( $fb_title_htag ) . '>' : '';

                } else if( isset( $saved_feed_options['facebook_page_title'] ) && $saved_feed_options['facebook_page_title'] !== 'no' ) {
                    // echo our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
                    $fb_title_htag_size = isset( $saved_feed_options[ 'fb_title_htag_size' ] ) ? 'style=font-size:' . $saved_feed_options[ 'fb_title_htag_size' ] . ';' : '';
                    echo '<' . esc_html( $fb_title_htag ) . ' ' . esc_attr( $fb_title_htag_size ) . '><a href="' . esc_url( $fts_view_fb_link ) . '" target="_blank" rel="noreferrer">' . esc_html( $page_name ) . '</a></' . esc_html( $fb_title_htag ) . '>';
                }
                // Description.
                echo !empty( $saved_feed_options['facebook_page_description'] ) && $saved_feed_options['facebook_page_description'] !== 'no' ? '<div class="fts-jal-fb-group-header-desc">' . wp_kses(
                        $this->facebook_post_types->facebook_tag_filter( $page_description ),
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
            echo isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] !== 'yes' && $saved_feed_options['facebook_page_feed_type'] !== 'album_photos' && $saved_feed_options['facebook_page_feed_type'] !== 'albums' ? '</div>' : '';
        } //End check.

        // SOCIAL BUTTON.
        if ( ! $fts_count_ids >= 1 && $saved_feed_options['facebook_hide_like_box_button'] === 'no' ) {
            $this->fb_social_btn_placement( $saved_feed_options, 'below_title' );
        }

        // Feed Header.
        // Make sure it's not ajaxing.
        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
            $fts_mashup_media_top      = isset( $saved_feed_options['facebook_show_media']) && $saved_feed_options['facebook_show_media'] === 'top' ? 'fts-mashup-media-top ' : '';
            $fts_mashup_show_name      = isset( $saved_feed_options['show_name'] ) && $saved_feed_options['show_name'] === 'no' ? ' fts-mashup-hide-name ' : '';
            $fts_mashup_show_date      = isset( $saved_feed_options['show_date'] ) && $saved_feed_options['show_date'] === 'no' ? ' fts-mashup-hide-date ' : '';
            $fts_mashup_show_thumbnail = isset( $saved_feed_options['show_thumbnail'] ) && $saved_feed_options['show_thumbnail'] === 'no' ? ' fts-mashup-hide-thumbnail ' : '';

            if ( ! isset( $facebook_post_type ) && $saved_feed_options['facebook_page_feed_type'] === 'albums' || ! isset( $facebook_post_type ) && $saved_feed_options['facebook_page_feed_type'] === 'album_photos' || isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] === 'yes' ) {

                if ( isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] === 'yes' ) {
                    echo '';
                } elseif ( isset( $saved_feed_options['fts-slider'], $saved_feed_options['facebook_container_animation'] ) && $saved_feed_options['fts-slider'] !== 'yes' && $saved_feed_options['facebook_container_animation'] === 'yes' || isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] === 'yes' || isset( $saved_feed_options['facebook_container_animation'] ) && $saved_feed_options['facebook_container_animation'] === 'yes' ) {
                    wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
                    echo '<script>';
                    echo 'jQuery(window).on(\'load\', function(){';
                    echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").masonry({';
                    echo 'itemSelector: ".fts-jal-single-fb-post"';
                    echo '});';
                    echo '});';
                    echo '</script>';
                }

                $fts_slider = $saved_feed_options['fts-slider'] ?? '';

                if ( $saved_feed_options['facebook_page_feed_type'] === 'albums' && $fts_slider !== 'yes' ||
                    $saved_feed_options['facebook_page_feed_type'] === 'album_photos' && $fts_slider !== 'yes' ) {
                    echo '<div class="fts-slicker-facebook-photos fts-slicker-facebook-albums' . ( isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] && $saved_feed_options['facebook_video_album'] === 'yes' ? ' popup-video-gallery-fb' : '' ) . ( isset( $saved_feed_options['facebook_container_animation'] ) && $saved_feed_options['facebook_container_animation'] === 'yes' ? ' masonry js-masonry' : '' ) . ( isset( $saved_feed_options['images_align'] ) && $saved_feed_options['images_align'] ? ' popup-video-gallery-align-' . esc_attr( $saved_feed_options['images_align'] ) : '' ) . ' popup-gallery-fb ' . esc_attr( $fts_dynamic_class_name ) . '"';
                    if ( isset( $saved_feed_options['facebook_container_animation'] ) && $saved_feed_options['facebook_container_animation'] === 'yes' ) {
                        echo 'data-masonry-options=\'{ "isFitWidth": ' . ( $saved_feed_options['facebook_container_position'] === 'yes' ? 'false' : 'true' ) . ' ' . ( $saved_feed_options['facebook_container_animation'] === 'no' ? ', "transitionDuration": 0' : '' ) . '}\' style="margin:auto;"';
                    }
                    echo '>';
                } elseif (
                    // slideshow scrollHorz or carousel.
                    ! isset( $facebook_post_type ) && isset( $saved_feed_options['fts-slider'] ) && $saved_feed_options['fts-slider'] === 'yes' ) {

                    $fts_cycle_type = $saved_feed_options['scrollhorz_or_carousel'] ?? 'scrollHorz';

                    if ( isset( $fts_cycle_type ) && $fts_cycle_type === 'carousel' ) {
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
                    $fts_controls_bar_color  = ! empty( $saved_feed_options['slider_controls_bar_color'] ) ? $saved_feed_options['slider_controls_bar_color'] : '#000';
                    $fts_controls_text_color = ! empty( $saved_feed_options['slider_controls_text_color'] ) ? $saved_feed_options['slider_controls_text_color'] : '#ddd';

                    if ( isset( $saved_feed_options['slider_controls_width'] ) && $saved_feed_options['scrollhorz_or_carousel'] !== 'carousel' ) {
                        $max_width_set = isset( $saved_feed_options['facebook_image_width'] ) && $saved_feed_options['facebook_image_width'] !== '' && $saved_feed_options['scrollhorz_or_carousel'] !== 'carousel' ? $saved_feed_options['facebook_image_width'] : '100%';
                    } else {
                        $max_width_set = isset( $saved_feed_options['slider_controls_width'] ) && $saved_feed_options['slider_controls_width'] !== '' && $saved_feed_options['scrollhorz_or_carousel'] === 'carousel' ? $saved_feed_options['slider_controls_width'] : '100%';
                    }
                    if (
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_numbers_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_above_feed' ||
                        isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'numbers_above_feed'
                    ) {

                        // Slider Dots Wrapper.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed'
                        ) {

                            echo '<div class="fts-slider-icons-center fts-pager-option-dots-only-top" style="margin:auto; width:100%;max-width:' . esc_attr( $max_width_set . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color ) . '"><div class="fts-pager-option fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '"></div></div>';
                        }

                        //LEAVING OFF HERE. SLIDESHOW NOT WORKING IN BACKEND BUT IS IN FRONT END... ALSO POPUP IS NOT WORKING IN ONE INSTANCE
                        // Slider Arrow and Numbers Wrapper.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'numbers_above_feed'
                        ) {
                            echo '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . esc_attr( $max_width_set . ';background:' . $fts_controls_bar_color . ';color:' . $fts_controls_text_color ) . '">';
                        }

                        // Previous Arrow.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_above_feed'
                        ) {
                            echo '<span class="fts-prevControl-icon fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
                        }
                        // Numbers.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_numbers_above_feed'
                        ) {
                            echo '<span id="fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" class="fts-custom-caption" ></span>';
                        }
                        // Next Arrow.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_above_feed'
                        ) {
                            echo '<span class="fts-nextControl-icon fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
                        }

                        // Slider Arrow and Numbers Wrapper.
                        if (
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_and_numbers_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'arrows_above_feed' ||
                            isset( $saved_feed_options['slider_controls'] ) && $saved_feed_options['slider_controls'] === 'numbers_above_feed'
                        ) {
                            echo '</div>';
                        }
                    }

                    echo '<div class="popup-gallery-fb fts-fb-slideshow fts-slicker-facebook-photos fts-slicker-facebook-albums ' . esc_attr( $fts_cycle_slideshow ) . ' ' . ( isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] && $saved_feed_options['facebook_video_album'] === 'yes' ? 'popup-video-gallery-fb' : '' ) . ' ' . ( isset( $saved_feed_options['images_align'] ) && $saved_feed_options['images_align'] ? ' popup-video-gallery-align-' . esc_attr( $saved_feed_options['images_align'] ) : '' ) . ' popup-gallery-fb ' . esc_attr( $fts_dynamic_class_name ) . '"

style="margin:' . ( isset( $saved_feed_options['slider_margin'] ) && $saved_feed_options['slider_margin'] !== '' ? esc_attr( $saved_feed_options['slider_margin'] ) : 'auto' ) . ';' . ( isset( $fts_cycle_type ) && $fts_cycle_type === 'carousel' ? 'width:100%; max-width:100%; overflow:hidden;height:' . esc_attr( $saved_feed_options['facebook_image_height'] ) . '!important;' : 'overflow:hidden; height:' . esc_attr( $saved_feed_options['facebook_image_height'] ) . '; max-width:' . ( isset( $saved_feed_options['facebook_image_width'] ) && $saved_feed_options['facebook_image_width'] !== '' ? esc_attr( $saved_feed_options['facebook_image_width'] ) : 'auto' ) ) . ';" data-cycle-caption="#fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-caption-template="{{slideNum}} / {{slideCount}}" data-cycle-pager=".fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-pause-on-hover="true" data-cycle-prev=".fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-next=".fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '" data-cycle-timeout="' . ( ! empty( $saved_feed_options['slider_timeout'] ) ? esc_attr( $saved_feed_options['slider_timeout'] ) : '0' ) . '" data-cycle-manual-speed="' . ( ! empty( $saved_feed_options['slider_speed'] ) ? esc_attr( $saved_feed_options['slider_speed'] ) : '400' ) . '" data-cycle-auto-height="false" data-cycle-slides="> div" data-cycle-fx="' . ( ! empty( $saved_feed_options['scrollhorz_or_carousel']) ? esc_attr( $saved_feed_options['scrollhorz_or_carousel']) : '' ) . '" data-cycle-carousel-visible=' . ( ! empty( $saved_feed_options['slides_visible'] ) ? esc_attr( $saved_feed_options['slides_visible'] ) : '4' ) . ' data-cycle-swipe=true data-cycle-swipe-fx=' . ( ! empty( $saved_feed_options['scrollhorz_or_carousel']) ? esc_attr( $saved_feed_options['scrollhorz_or_carousel']) : '' ) . '>';
                }

                if ( $saved_feed_options['facebook_page_feed_type'] === 'page' && isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] === 'yes' ||
                    $saved_feed_options['facebook_page_feed_type'] === 'reviews' && isset( $saved_feed_options['facebook_grid'] ) && $saved_feed_options['facebook_grid'] === 'yes' ) {
                    echo '<div class="fts-slicker-facebook-posts masonry js-masonry ' . esc_attr( $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail ) . ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $saved_feed_options['facebook_popup'] === 'yes' ? 'popup-gallery-fb-posts ' : '' ) . ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' ? 'fts-reviews-feed ' : '' ) . esc_attr( $fts_dynamic_class_name ) . ' " style="margin:auto;" data-masonry-options=\'{ "isFitWidth": ' . ( $saved_feed_options['facebook_container_position'] === 'no' ? 'false' : 'true' ) . ',"transitionDuration": 0 }\'>';
                }
            } else {
                $facebook_page_height = !empty( $saved_feed_options['facebook_page_height'] ) && $saved_feed_options['facebook_page_height'] !== 'auto' ? 'height:' . $saved_feed_options['facebook_page_height'] : '';

                echo '<div class="fts-jal-fb-group-display fts-simple-fb-wrapper ' . esc_attr( $fts_mashup_media_top . $fts_mashup_show_name . $fts_mashup_show_date . $fts_mashup_show_thumbnail ) . ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && $saved_feed_options['facebook_popup'] === 'yes' ? ' popup-gallery-fb-posts ' : '' ) . ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' ? 'fts-reviews-feed ' : '' ) . esc_attr( $fts_dynamic_class_name ) . ( !empty($facebook_page_height) && $facebook_page_height !== 'auto' ? ' fts-fb-scrollable" style="' . esc_attr( $facebook_page_height ) . '"' : '"' ) . '>';
            }
        } //End ajaxing Check

        // *********************
        // Post Information
        // *********************
        $fb_load_more_text   = $saved_feed_options['fb_load_more_text'] ?? esc_html( 'Load More', 'feed-them-social' );
        $response_post_array = $this->get_post_info( $feed_data, $saved_feed_options, $language, $fb_cache_name );

        // Single event info call.
        if ( $saved_feed_options['facebook_page_feed_type'] === 'events' ) {
            $single_event_array_response = $this->get_event_post_info( $feed_data, $saved_feed_options, $language );
        }

        $set_zero = 0;

        // echo '<br/><br/>feed array<br/><br/>';.
        // echo '<pre>';
        // print_r($feed_data );
        // echo '</pre>';.
        // THE MAIN FEED
        // LOOP to fix Post count!

        if (isset($feed_data->data) && \count($feed_data->data) > 0) {
            foreach ($feed_data->data as $k => $v) {
                if ($k >= $saved_feed_options['facebook_page_post_count']) {
                    unset($feed_data->data[$k]);
                }
            }
        }

        // Nov. 4th. 2016 // Uncomment this to sort the dates proper if facebook is returning them out of order.
        // We had one case of this here for a list of posts coming from an event.
        // https://wordpress.org/support/topic/facebook-event-posts-not-ordered-by-date/
        // usort($feed_data->data, array($this, "dateSort"));
        // Loop for all facebook feeds.

        if (isset($feed_data->data) && \count($feed_data->data) > 0) {
            foreach ($feed_data->data as $post_data) {

                //Adding the profile pic to the feed data array so we can use in the feeds.
                // Check if decoding was successful and if the keys exist
                if ( \is_array($page_data) && isset($page_data['picture']['data']['url']) && \is_object($feed_data)) {
                    // Adding the profile pic URL to the feed data object
                    $post_data->fts_main_profile_pic_url = $page_data['picture']['data']['url'] ?? '';
                }
                $fb_message = $post_data->message ?? '';
                $fb_status_type = $post_data->status_type ?? '';

                $fb_story = $post_data->story ?? '';

                if ( $saved_feed_options['facebook_page_feed_type'] === 'albums' ) {
                    $facebook_post_type = $post_data->type ?? '';
                } else {
                    $facebook_post_type = $post_data->attachments->data[0]->type ?? '';
                }

                // Testing.
                //echo $fb_story;

                // SRL 4.0 I noticed that if you change the fb language option when creating a fb feed then our english language comparisons are not working properly.
                // I fixed this for the most part by remove the check for needle... to do. need to check if regular posts have a status type.
                // This is the method to skip empty posts or posts that are simply about changing settings or other non important post types.
                if ( strpos($fb_story, 'updated their website address') !== false ||
                    $facebook_post_type === 'profile_media' ||
                    $facebook_post_type === 'cover_photo' ||
                    $facebook_post_type === 'status' && empty($fb_message) && empty($fb_story) ||
                    $facebook_post_type === 'event' ||
                    $facebook_post_type === 'status' && strpos($fb_story, 'changed the name of the event to') !== false ||
                    $facebook_post_type === 'status' && strpos($fb_story, 'changed the privacy setting') !== false ||
                    $facebook_post_type === 'status' && strpos($fb_story, 'an admin of the group') !== false ||
                    $facebook_post_type === 'status' && strpos($fb_story, 'created the group') !== false ||
                    $facebook_post_type === 'status' && strpos($fb_story, 'added an event') !== false ) {

                    // Skip the Post...

                } else {
                    // define type note also affects load more function call.
                    if (!$facebook_post_type && $saved_feed_options['facebook_page_feed_type'] === 'album_photos' ) {
                        $facebook_post_type = 'photo';
                    }
                    if (!$facebook_post_type && $saved_feed_options['facebook_page_feed_type'] === 'events' ) {
                        $facebook_post_type = 'events';
                    }

                    $single_event_array_response = $single_event_array_response ?? '';

                    // echo '<br/><br/>were function gets called <br/><br/>' .
                   /* echo '<pre>';
                    echo print_r($post_data);
                    echo '</pre>';*/
                    $this->facebook_post_types->feed_post_types($set_zero, $facebook_post_type, $post_data, $saved_feed_options, $response_post_array, $single_event_array_response, $fts_facebook_reviews);
                }

                $set_zero++;
            }// END POST foreach
        }

        if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $saved_feed_options['facebook_page_feed_type'] !== 'reviews' || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {
            if ( ! empty( $feed_data->data ) ) {
                $this->fts_facebook_loadmore( $feed_post_id, $feed_data, $facebook_post_type, $saved_feed_options );
            }
        }

        echo '</div>'; // closing main div for fb photos, groups etc
        // only show this script if the height option is set to a number.
        if ( isset( $saved_feed_options['facebook_page_height'] ) && $saved_feed_options['facebook_page_height'] !== 'auto' ) {
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
        } //end $saved_feed_options['facebook_page_height'] !== 'auto' && empty($saved_feed_options['facebook_page_height']) == NULL
        // Make sure it's not ajaxing.
        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
            echo '<div class="fts-clear"></div><div id="fb-root"></div>';
            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $saved_feed_options['facebook_page_feed_type'] !== 'reviews' || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {
                if ( $saved_feed_options['facebook_load_more_style'] === 'button' && $saved_feed_options['facebook_load_more'] === 'yes' ) {

                    echo '<div class="fts-fb-load-more-wrapper">';
                    echo '<div id="loadMore_' . esc_attr( $_REQUEST['fts_dynamic_name'] ) . '" style="';
                    if ( isset( $saved_feed_options['facebook_loadmore_button_width'] ) && '' !== $saved_feed_options['facebook_loadmore_button_width'] ) {
                        echo 'max-width:' . esc_attr( $saved_feed_options['facebook_loadmore_button_width'] ) . ';';
                    }
                    $loadmore_btn_margin = $saved_feed_options['loadmore_button_margin'] ?? '20px';
                    echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $fb_load_more_text ) . '</div>';
                    echo '</div>';
                }
            }
        }//End Check

        // Checks for Slider control option.
        if( isset( $saved_feed_options['fts-slider'] ) && $saved_feed_options['slider_controls'] && 'yes' === $saved_feed_options['fts-slider'] ){
            //Check at lease one of Slider Controls is set.
            if (
                $saved_feed_options['slider_controls'] === 'dots_below_feed' ||
                $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                $saved_feed_options['slider_controls'] === 'dots_and_numbers_below_feed' ||
                $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                $saved_feed_options['slider_controls'] === 'arrows_below_feed' ||
                $saved_feed_options['slider_controls'] === 'numbers_below_feed'
            ) {

                // Slider Dots Wrapper.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed'
                ) {
                    if ( isset( $saved_feed_options['slider_controls_width'] ) && $saved_feed_options['scrollhorz_or_carousel'] !== 'carousel' ) {
                        $max_width_set = isset( $saved_feed_options['facebook_image_width'] ) && $saved_feed_options['facebook_image_width'] !== '' && $saved_feed_options['scrollhorz_or_carousel'] !== 'carousel' ? $saved_feed_options['facebook_image_width'] : '100%';
                    } else {
                        $max_width_set = isset( $saved_feed_options['slider_controls_width'] ) && $saved_feed_options['slider_controls_width'] !== '' && $saved_feed_options['scrollhorz_or_carousel'] === 'carousel' ? $saved_feed_options['slider_controls_width'] : '100%';
                    }

                    $fts_controls_text_color = isset( $saved_feed_options['slider_controls_text_color'] ) && $saved_feed_options['slider_controls_text_color'] !== '' ? $saved_feed_options['slider_controls_text_color'] : '#333333';
                    $fts_controls_bar_color = isset( $saved_feed_options['slider_controls_bar_color'] ) && $saved_feed_options['slider_controls_bar_color'] !== '' ? $saved_feed_options['slider_controls_bar_color'] : '#333333';

                    echo '<div class="fts-slider-icons-center" style="margin:auto; width:100%;max-width:' . esc_attr( $max_width_set ) . ';background:' . esc_attr( $fts_controls_bar_color ) . ';color:' . esc_attr( $fts_controls_text_color ) . '"><div class="fts-pager-option fts-custom-pager-' . esc_attr( $fts_dynamic_class_name ) . '"></div></div>';
                }

                // Slider Arrow and Numbers Wrapper.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'numbers_below_feed'
                ) {
                    echo '<div class="fts-slider-center" style="margin:auto; width:100%; max-width:' . esc_attr( $max_width_set ) . ';background:' . esc_attr( $fts_controls_bar_color ) . ';color:' . esc_attr( $fts_controls_text_color ) . '">';
                }

                // Previous Arrow.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_below_feed'
                ) {
                    echo '<span class="fts-prevControl-icon fts-prevControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
                }
                // Numbers.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_and_numbers_below_feed'
                ) {
                    echo '<span id="fts-custom-caption-' . esc_attr( $fts_dynamic_class_name ) . '" class="fts-custom-caption" ></span>';
                }
                // Next Arrow.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_below_feed'
                ) {
                    echo '<span class="fts-nextControl-icon fts-nextControl-' . esc_attr( $fts_dynamic_class_name ) . '"></span>';
                }

                // Slider Arrow and Numbers Wrapper.
                if (
                    $saved_feed_options['slider_controls'] === 'dots_and_arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'dots_arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_and_numbers_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'arrows_below_feed' ||
                    $saved_feed_options['slider_controls'] === 'numbers_below_feed'
                ) {
                    echo '</div>';
                }
            }
        }


        unset( $_REQUEST['next_url'] );

        // ******************
        // SOCIAL BUTTON
        // ******************
        if ( ! $fts_count_ids >= 1 && $saved_feed_options['facebook_hide_like_box_button'] === 'no' ) {
            $this->fb_social_btn_placement( $saved_feed_options, 'bottom' );
        }

        return ob_get_clean();
    }

    /**
     * FTS Facebook Location
     *
     * Facebook Post Location.
     *
     * @param string $facebook_post_type What kind of facebook feed it is.
     * @param string $location The location of the photo or video.
     * @since 1.9.6
     */
    public function fts_facebook_location( $facebook_post_type, $location ) {
        switch ( $facebook_post_type ) {
            case 'app':
            case 'cover':
            case 'profile':
            case 'mobile':
            case 'wall':
            case 'normal':
            case 'album':
                echo '<div class="fts-fb-location">' . esc_html( $location ) . '</div>';
            break;
            default:
                break;
        }
    }

    /**
     * Get View Link
     *
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @return string
     * @since 1.9.6
     */
    public function get_view_link( $saved_feed_options ) {
        switch ( $saved_feed_options['facebook_page_feed_type'] ) {
            case 'group':
                $fts_view_fb_link = 'https://www.facebook.com/groups/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/';
                break;
            case 'page':
                $fts_view_fb_link = 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/';
                break;
            case 'event':
                $fts_view_fb_link = 'https://www.facebook.com/events/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/';
                break;
            case 'events':
                $fts_view_fb_link = 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/events/';
                break;
            case 'albums':
                $fts_view_fb_link = 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/photos_stream?tab=photos_albums';
                break;
            // album photos and videos album.
            case 'album_photos':
                $fts_view_fb_link = isset( $saved_feed_options['facebook_video_album'] ) && 'yes' === $saved_feed_options['facebook_video_album'] ? 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/videos/' : 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/photos_stream/';
                break;
            case 'hashtag':
                $fts_view_fb_link = 'https://www.facebook.com/hashtag/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/';
                break;
            case 'reviews':
                $fts_view_fb_link = 'https://www.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/reviews/';
                break;
            default:
                break;
        }

        return $fts_view_fb_link ?? '';
    }

    /**
     * Get FB Cache Name
     *
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @return string
     * @since 1.9.6
     */
    public function get_fb_cache_name( $saved_feed_options ) {
        // URL to get page info.
        $r_count = substr_count( $saved_feed_options['fts_facebook_custom_api_token_user_id'], ',' );

        if ( $r_count >= 1 ) {
            $result             = preg_replace( '/[ ,]+/', '-', trim( $saved_feed_options['fts_facebook_custom_api_token_user_id'] ) );
            $saved_feed_options['fts_facebook_custom_api_token_user_id'] = $result;
        }

        switch ( $saved_feed_options['facebook_page_feed_type'] ) {
            case 'album_photos':
                $facebook_album_id = $saved_feed_options['facebook_album_id'] ?? '';
                $fb_data_cache_name = 'fb_' . $saved_feed_options['facebook_page_feed_type'] . '_' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '_' . $facebook_album_id . '_num' . $saved_feed_options['facebook_page_post_count'] . '';
                break;
            default:
                $fb_data_cache_name = 'fb_' . $saved_feed_options['facebook_page_feed_type'] . '_' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '_num' . $saved_feed_options['facebook_page_post_count'] . '';
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
    public function get_language( $saved_feed_options ) {
        // this check is in place because we used this option and it failed for many people because we use wp get contents instead of curl.
        // this can be removed in a future update and just keep the $language_option = get_option('fb_language', 'en_US');.
        $language_option_check = ! empty( $saved_feed_options['fb_language'] ) ? $saved_feed_options['fb_language'] : '';
        if ( isset( $language_option_check ) && $language_option_check !== 'Please Select Option' ) {
            $language_option = $saved_feed_options['fb_language'] ?? 'en_US';
        } else {
            $language_option = 'en_US';
        }
        return ! empty( $language_option ) ? '&locale=' . $language_option : '';
    }



    /**
     * Get Facebook Feed Response
     *
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @param string $fb_cache_name FB cache name.
     * @param string $language Language.
     * @return array|mixed
     * @throws \Exception
     * @since 1.9.6
     */
    public function get_facebook_feed_response( $saved_feed_options, $fb_cache_name, $language ) {

        if ( isset( $_REQUEST['next_url'] ) && ! empty( $_REQUEST['next_url'] ) ) {
            $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );
            if ( $next_url_host !== 'graph.facebook.com' && $next_url_host !== 'graph.instagram.com' ) {
                wp_die( esc_html__( 'Invalid Facebook URL', 'feed_them_social' ), 403 );
            }
        }

        $fts_count_ids = is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ? substr_count( $saved_feed_options['fts_facebook_custom_api_token_user_id'], ',' ) : '';

        if ( false !== $this->feed_cache->fts_check_feed_cache_exists( $fb_cache_name ) && ! isset( $_GET['load_more_ajaxing'] ) ) {

            // YO!
            // echo 'Cache Should Be Printing out here.<br/>';
            // echo $fb_cache_name;
            // print_r( $this->feed_cache->fts_get_feed_cache( $fb_cache_name ) );

            $response = $this->feed_cache->fts_get_feed_cache( $fb_cache_name );
        } else {

            // echo $this->feed_access_token;
            // Page.
            if ( $saved_feed_options['facebook_page_feed_type'] === 'page' && $saved_feed_options['facebook_page_posts_displayed'] === 'page_only' ) {
                $mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '?fields=id,name,description,picture&access_token=' . $this->feed_access_token . $language . '' );

                if ( isset( $_REQUEST['next_url'] ) ) {
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token=' . $this->feed_access_token, $_REQUEST['next_url'] );
                }

                if ( ! $fts_count_ids >= 1 ) {
                    // We cannot add sanitize_text_field here on the $_REQUEST['next_url'] otherwise it will fail to load the contents from the facebook API.
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/posts?fields=id,attachments,created_time,from,icon,message,picture,full_picture,place,shares,status_type,story,to&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                } else {
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/posts?ids=' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '&fields=id,attachments,created_time,from,icon,message,picture,full_picture,place,shares,status_type,story,to&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                }
            } elseif ( $saved_feed_options['facebook_page_feed_type'] === 'albums') {
                // Albums.
                $dateString = !empty($saved_feed_options['facebook_album_covers_since_date']) ? $saved_feed_options['facebook_album_covers_since_date'] : '';

                // Format should look like this: 01-01-2024 or this 1-1-2024
                if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $dateString)) {
                    // Convert the date string to a DateTime object
                    $dateObject = \DateTime::createFromFormat('m-d-Y', $dateString);

                    // Check if the date conversion was successful
                    if ($dateObject !== false) {
                        // Convert the DateTime object to a Unix timestamp
                        $unixTimestamp = $dateObject->getTimestamp();
                        $albums_since_date = '&since=' . $unixTimestamp;
                    } else {
                        // Handle invalid date conversion
                        $albums_since_date = '';
                    }
                }
                else {
                    $albums_since_date = '';
                }

                $mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '?fields=id,name,description,link,picture&access_token=' . $this->feed_access_token . $language . '' );
                if ( isset( $_REQUEST['next_url'] ) ) {
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token=' . $this->feed_access_token, $_REQUEST['next_url'] );
                }
                // Check If Ajax next URL needs to be used.
                if ( ! $fts_count_ids >= 1 ) {
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : wp_unslash( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/albums?fields=id,photos{images,name,created_time},created_time,name,from,link,cover_photo,count,updated_time,type'.$albums_since_date.'&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                } else {
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : wp_unslash( 'https://graph.facebook.com/albums?ids=' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '&fields=id,photos{images,name,created_time},created_time,name,from,link,cover_photo,count,updated_time,type&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                }

                // $mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? esc_url($_REQUEST['next_url']) : 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/albums?fields=id,created_time,name,from,cover_photo,count,updated_time&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '';
            } elseif (
                // Album Photos.
                $saved_feed_options['facebook_page_feed_type'] === 'album_photos' ) {

                $mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '?fields=id,name,description,link,picture&access_token=' . $this->feed_access_token . $language . '' );

                $photo_stream = $saved_feed_options['facebook_page_feed_type'] === 'album_photos' && empty( $saved_feed_options['facebook_album_id'] ) ? 'photo_stream' : '';

                if ( isset( $_REQUEST['next_url'] ) ) {
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token=' . $this->feed_access_token, $_REQUEST['next_url'] );
                }
                // Check If Ajax next URL needs to be used
                // The reason I did not create a whole new else if for the video album is because I did not want to duplicate all the code required to make the video because the videos gallery comes from the photo albums on facebook.
                if ( isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] === 'yes' ) {
                    if ( ! $fts_count_ids >= 1 ) {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/videos?fields=id,created_time,description,from,icon,link,message,object_id,picture,place,source,to,type,format,embed_html&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    } else {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/videos?ids=' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '&fields=id,created_time,description,from,icon,link,message,object_id,picture,place,source,to,type,format,embed_html&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    }
                } elseif ( $photo_stream === 'photo_stream' ) {
                    if ( ! $fts_count_ids >= 1 ) {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    } else {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/photos?ids=' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&type=uploaded&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    }

                } else {
                    if ( ! $fts_count_ids >= 1 ) {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['facebook_album_id'] . '/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    } else {
                        $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/photos?ids=' . $saved_feed_options['facebook_album_id'] . '&fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                    }
                }
            } elseif ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {

                // YO!
                // echo 'myCacheName Ok so we are good to this point, but when you reload the page the cache is not decrypting somewhere.';
                // echo $fb_cache_name;
                // Reviews.
                if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
                    $fts_facebook_reviews = new \feed_them_social_facebook_reviews\Facebook_Reviews_Feed( $this->feed_functions, $this->feed_access_token );

                    $mulit_data = $fts_facebook_reviews->review_connection( $saved_feed_options, $language );

                    $mulit_data['ratings_data'] = esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/?fields=overall_star_rating,rating_count&access_token=' . $this->feed_access_token . '' );

                } else {
                    return 'Please Purchase and Activate the Feed Them Social Reviews plugin.';
                }
            } else {
                // This is meant for posts made by others option, however it now requires a new permission from Facebook that we do not have. Page Public Content Access.
                $mulit_data = array( 'page_data' => 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '?fields=feed,id,name,description,picture&access_token=' . $this->feed_access_token . $language . '' );

                if ( isset( $_REQUEST['next_url'] ) ) {
                    $_REQUEST['next_url'] = str_replace( 'access_token=XXX', 'access_token=' . $this->feed_access_token, $_REQUEST['next_url'] );
                }

                if ( ! $fts_count_ids >= 1 ) {
                    // We cannot add sanitize_text_field here on the $_REQUEST['next_url'] otherwise it will fail to load the contents from the facebook API.
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '/posts?fields=id,attachments,created_time,from,icon,message,picture,full_picture,place,shares,status_type,story,to&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                } else {
                    $mulit_data['feed_data'] = !empty( $_REQUEST['next_url'] ) ? esc_url_raw( $_REQUEST['next_url'] ) : esc_url_raw( 'https://graph.facebook.com/posts?ids=' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '&fields=id,attachments,created_time,from,icon,message,picture,full_picture,place,shares,status_type,story,to&limit=' . $saved_feed_options['facebook_page_post_count'] . '&access_token=' . $this->feed_access_token . $language . '' );
                }
            }
            $response = $this->feed_functions->fts_get_feed_json( $mulit_data );

            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                // Error Check.
                $feed_data                = json_decode( $response['feed_data'] );
                $fts_error_check          = new fts_error_handler();
                $fts_error_check_complete = $fts_error_check->facebook_error_check( $saved_feed_options, $feed_data );

                if ( is_array( $fts_error_check_complete ) && $fts_error_check_complete[0] === true ) {

                    // If old Cache exists use it instead of showing an error.
                    if ( $this->feed_cache->fts_check_feed_cache_exists( $fb_cache_name, true ) === true ) {

                        // If Current user is Admin and Cache exists for use then still show Admin the error for debugging purposes.
                        if ( current_user_can( 'administrator' ) ) {
                            echo wp_kses(
                                $fts_error_check_complete[1] . ' <em>**NOTE** This error is only shown to logged in Admins of this WordPress install</em>',
                                array(
                                    'a'      => array(
                                        'href'  => array(),
                                        'title' => array(),
                                    ),
                                    'br'     => array(),
                                    'em'     => array(),
                                    'strong' => array(),
                                )
                            );
                        }

                        // Return Cache because it exists in Database. Better than showing nothing right?
                        return $this->feed_cache->fts_get_feed_cache( $fb_cache_name, true );
                    }
                    // If User is Admin and no Old cache is saved in database for use.
                    if ( current_user_can( 'administrator' ) ) {
                        return array( false, $fts_error_check_complete[1] );
                    }
                }
            }

            // Make sure it's not ajaxing.
            if ( ! empty( $response['feed_data'] ) ) {
                // Create Cache.

                // echo 'Caching Response:<br/>';
                // NOT using below $response just for testing.
                //$response = is_array( $response ) ? serialize( $response ) : $response ;
                //print_r($response);

                $this->feed_cache->fts_create_feed_cache( $fb_cache_name, $response );

                //print_r( $response );
            }
        } // end main else.

        return $response;
    }

    /**
     * Get Post Info
     *
     * For Facebook.
     *
     * @param string $feed_data The facebook contents.
     * @param array  $saved_feed_options The feed options saved in the CPT.
     * @param string $language Language.
     * @return array|mixed
     * @since 1.9.6
     */
    public function get_post_info( $feed_data, $saved_feed_options, $language, $fb_cache_name ) {

        // If Album include album ID in Post Data Cache name.
        if ( $saved_feed_options['facebook_page_feed_type'] === 'album_photos' ) {
            $facebook_album_id = !empty( $saved_feed_options['facebook_album_id'] ) ? $saved_feed_options['facebook_album_id'] : '';
            $fb_post_data_cache = 'fb_' . $saved_feed_options['facebook_page_feed_type'] . '_post_' . $facebook_album_id . '_num' . $saved_feed_options['facebook_page_post_count'] . '';
            $post_count = $saved_feed_options['facebook_page_post_count'];
        }
        elseif ( isset( $saved_feed_options['combine_facebook'] ) && $saved_feed_options['combine_facebook'] === 'yes' ){
            $fb_post_data_cache = 'fb_page_combine_post_' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '_num' . $saved_feed_options['combine_social_network_post_count'] . '';
            $post_count = $saved_feed_options['combine_social_network_post_count'];
        }
        else {
            $fb_post_data_cache = 'fb_' . $saved_feed_options['facebook_page_feed_type'] . '_post_' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '_num' . $saved_feed_options['facebook_page_post_count'] . '';
            $post_count = $saved_feed_options['facebook_page_post_count'];
        }

        if ( $this->feed_cache->fts_check_feed_cache_exists( $fb_post_data_cache ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
            $response_post_array = $this->feed_cache->fts_get_feed_cache( $fb_post_data_cache );
        } else {

            // Build the big post counter.
            $fb_post_array = array();

            // Single Events Array.
            $set_zero = 0;

            if (isset($feed_data->data) && \count($feed_data->data) > 0) {
                foreach ($feed_data->data as $post_data) {

                    $post_data->id = $post_data->id ?? '';

                    if ($set_zero === $post_count) {
                        break;
                    }

                    $facebook_post_type = $post_data->attachments->data[0]->type ?? '';
                    $post_data_key = $post_data->attachments->data[0]->object_id ?? $post_data->id;

                    // Don't run these if it's a review feed otherwise you will get an error response from facebook.
                    if ( $saved_feed_options['facebook_page_feed_type'] !== 'reviews' ) {
                        // Set Likes URL in post array.
                        $fb_post_array[$post_data_key . '_likes'] = 'https://graph.facebook.com/' . $post_data_key . '/reactions?summary=1&access_token=' . $this->access_options->decrypt_access_token($saved_feed_options['fts_facebook_custom_api_token']);
                        // Set Comments URL in post array.
                        $fb_post_array[$post_data_key . '_comments'] = 'https://graph.facebook.com/' . $post_data_key . '/comments?summary=1&access_token=' . $this->access_options->decrypt_access_token($saved_feed_options['fts_facebook_custom_api_token']);
                    }

                    // Video.
                    if ( $facebook_post_type === 'video' ) {
                        $fb_post_array[$post_data_key . '_video'] = 'https://graph.facebook.com/' . $post_data_key;
                    }
                    // Photo.
                    $fb_album_cover = isset($post_data->cover_photo->id) ? $post_data->cover_photo->id : '';
                    if ( $saved_feed_options['facebook_page_feed_type'] === 'albums' && !$fb_album_cover) {
                        unset($post_data);
                        continue;
                    }
                    if ( $saved_feed_options['facebook_page_feed_type'] === 'albums' ) {
                        $fb_post_array[$fb_album_cover . '_photo'] = 'https://graph.facebook.com/' . $fb_album_cover;
                    }
                    if ( $saved_feed_options['facebook_page_feed_type'] === 'hashtag' ) {
                        $fb_post_array[$post_data_key . '_photo'] = 'https://graph.facebook.com/' . $post_data->source;
                    }
                    // GROUP Photo.
                    if ( $saved_feed_options['facebook_page_feed_type'] === 'group' ) {
                        $fb_post_array[$post_data_key . '_group_post_photo'] = 'https://graph.facebook.com/' . $post_data->id . '?fields=picture,full_picture&access_token=' . $this->feed_access_token;
                    }

                    $set_zero++;
                }
            }


            $fts_error_check          = new fts_error_handler();
            $fts_error_check_complete = $fts_error_check->facebook_error_check( $saved_feed_options, $feed_data );
            if ( is_array( $fts_error_check_complete ) && true === $fts_error_check_complete[0] ) {

                // If old Cache exists use it instead of showing an error.
                if ( $this->feed_cache->fts_check_feed_cache_exists( $fb_cache_name, true ) === true ) {
                    // Return Cache because it exists in Database. Better than showing nothing right?
                    return $this->feed_cache->fts_get_feed_cache( $fb_cache_name, true );
                }
            }

            // Response.
            $response_post_array = $this->feed_functions->fts_get_feed_json( $fb_post_array );
            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // Create Cache.
                $this->feed_cache->fts_create_feed_cache( $fb_post_data_cache, $response_post_array );
            }
        }
        // SHOW THE POST RESPONSE PRINT_R
        /* echo'uyuyyuyuuyuy<pre>';
         print_r($response_post_array);
         echo'</pre>';*/

        return $response_post_array;
    }

    /**
     * Get Post Info
     *
     * For Facebook.
     *
     * @param string $feed_data The facebook contents.
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @param string $language Language.
     * @return array|mixed
     * @since 2.1.6
     */
    public function get_event_post_info( $feed_data, $saved_feed_options, $language ) {

        $fb_event_post_data_cache = 'fbe_' . $saved_feed_options['facebook_page_feed_type'] . '_post_' . $saved_feed_options['fts_facebook_custom_api_token_user_id'] . '_num' . $saved_feed_options['facebook_page_post_count'] . '';
        if ( $this->feed_cache->fts_check_feed_cache_exists( $fb_event_post_data_cache ) !== false && ! isset( $_GET['load_more_ajaxing'] ) ) {
            $response_event_post_array = $this->feed_cache->fts_get_feed_cache( $fb_event_post_data_cache );
        } else {
            // Single Events Array.
            $fb_single_events_array = array();
            $set_zero               = 0;

            if (isset($feed_data->data) && \count($feed_data->data) > 0) {
                foreach ($feed_data->data as $post_data) {

                    $post_data->id = $post_data->id ?? '';

                    if ($set_zero === $saved_feed_options['facebook_page_post_count']) {
                        break;
                    }

                    $single_event_id = $post_data->id;
                    $language = $language ?? '';
                    // Event Info, Time etc.
                    $fb_single_events_array['event_single_' . $single_event_id . '_info'] = 'https://graph.facebook.com/' . $single_event_id . '/?access_token=' . $this->feed_access_token . $language;
                    // Event Location.
                    $fb_single_events_array['event_single_' . $single_event_id . '_location'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=place&access_token=' . $this->feed_access_token . $language;
                    // Event Cover Photo.
                    $fb_single_events_array['event_single_' . $single_event_id . '_cover_photo'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=cover&access_token=' . $this->feed_access_token . $language;
                    // Event Ticket Info.
                    $fb_single_events_array['event_single_' . $single_event_id . '_ticket_info'] = 'https://graph.facebook.com/' . $single_event_id . '/?fields=ticket_uri&access_token=' . $this->feed_access_token . $language;

                    $set_zero++;
                }
            }


            $response_event_post_array = $this->feed_functions->fts_get_feed_json( $fb_single_events_array );
            // Create Cache.
            $this->feed_cache->fts_create_feed_cache( $fb_event_post_data_cache, $response_event_post_array );

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
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @param string $this->feed_access_token The Access Token.
     * @param string $share_loc Language.
     * @return string|void
     * @since 2.0.1
     */
    public function fb_social_btn_placement( $saved_feed_options, $share_loc ) {

        // Don't do it for these!
        if ( $saved_feed_options['facebook_page_feed_type'] === 'group' || $saved_feed_options['facebook_page_feed_type'] === 'event' || isset( $saved_feed_options['hide_like_option'] ) && $saved_feed_options['hide_like_option'] === 'yes' ) {
            return;
        }
        // Facebook Follow Button Options.
        $fb_show_follow_btn = $saved_feed_options['facebook_hide_like_box_button'];

        $fb_show_follow_btn_where = $saved_feed_options['facebook_position_likebox'] ?? 'above_title';

        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

            $like_option_align_final = isset( $saved_feed_options['facebook_align_likebox'] ) ? 'fts-fb-social-btn-' . $saved_feed_options['facebook_align_likebox'] . '' : '';

            if ( $share_loc === $fb_show_follow_btn_where && isset( $fb_show_follow_btn ) && $fb_show_follow_btn === 'no' ) {

                switch ( $fb_show_follow_btn_where ) {
                    case 'above_title':
                        // Top Above Title.
                        echo '<div class="fb-social-btn-top ' . esc_attr( $like_option_align_final ) . '">';
                        $this->feed_functions->social_follow_button( 'facebook', $saved_feed_options['fts_facebook_custom_api_token_user_id'], $saved_feed_options );
                        echo '</div>';
                        break;
                    // Top Below Title.
                    case 'below_title':
                        echo '<div class="fb-social-btn-below-description ' . esc_attr( $like_option_align_final ) . '">';
                        $this->feed_functions->social_follow_button( 'facebook', $saved_feed_options['fts_facebook_custom_api_token_user_id'], $saved_feed_options );
                        echo '</div>';
                        break;
                    // Bottom.
                    case 'bottom':
                        echo '<div class="fb-social-btn-bottom ' . esc_attr( $like_option_align_final ) . '">';
                        $this->feed_functions->social_follow_button( 'facebook', $saved_feed_options['fts_facebook_custom_api_token_user_id'],  $saved_feed_options );
                        echo '</div>';
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * Load PopUp Scripts
     *
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @since 1.9.6
     */
    public function load_popup_scripts( $saved_feed_options ) {
        if ( isset(  $saved_feed_options['facebook_popup']  ) && $saved_feed_options['facebook_popup'] === 'yes' ) {
            // it's ok if these styles & scripts load at the bottom of the page.
            $fts_fix_magnific = $this->settings_functions->fts_get_option( 'remove_magnific_css' ) ?? '';
            if ( isset( $fts_fix_magnific ) && $fts_fix_magnific !== '1' ) {
                wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.min.css' ), array(), FTS_CURRENT_VERSION, false );
            }
            wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
            wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
        }
    }

    /**
     * FTS Facebook LoadMore
     *
     * @param string $feed_post_id ID of the CPT Post settings are saved in.
     * @param string $feed_data The Feed data.
     * @param string $facebook_post_type The type of facebook feed.
     * @param array $saved_feed_options The feed options saved in the CPT.
     * @since 1.9.6
     */
    public function fts_facebook_loadmore( $feed_post_id, $feed_data, $facebook_post_type, $saved_feed_options ) {

        //echo print_r( $saved_feed_options );


        if ( ( isset( $saved_feed_options['facebook_load_more_style'] ) && $saved_feed_options['facebook_load_more_style'] === 'button' && $saved_feed_options['facebook_load_more'] === 'yes' || isset( $saved_feed_options['facebook_load_more_style'] ) && $saved_feed_options['facebook_load_more_style'] === 'autoscroll') && ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $saved_feed_options['facebook_page_feed_type'] !== 'reviews' || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'reviews') ) {

            $fb_load_more_text       = $saved_feed_options['fb_load_more_text'] ?? esc_html( 'Load More', 'feed-them-social' );
            $fb_no_more_posts_text   = $saved_feed_options['fb_no_more_posts_text'] ?? esc_html( 'No More Posts', 'feed-them-social' );
            $fb_no_more_photos_text  = $saved_feed_options['fb_no_more_photos_text'] ?? esc_html( 'No More Photos', 'feed-them-social' );
            $fb_no_more_videos_text  = $saved_feed_options['fb_no_more_videos_text'] ?? esc_html( 'No More Videos', 'feed-them-social' );
            $fb_no_more_reviews_text = $saved_feed_options['fb_no_more_reviews_text'] ?? esc_html( 'No More Reviews', 'feed-them-social' );

            // Load More BUTTON Start.
            $next_url = $feed_data->paging->next ?? '';

            $posts          = $saved_feed_options['facebook_page_post_count'] ?? '';

            // SRL 4.0 turning this off
            // $loadmore_count = $saved_feed_options['loadmore_count'] ?? false;
            // we check to see if the loadmore count number is set and if so pass that as the new count number when fetching the next set of posts.
            //$_REQUEST['next_url'] = $loadmore_count ? str_replace( "limit=$posts", "limit=$loadmore_count", $next_url ) : $next_url;

            $_REQUEST['next_url'] = str_replace( 'access_token='. $this->feed_access_token, 'access_token=XXX', $next_url );

            echo '<script>';
            echo 'var nextURL_' . sanitize_key( $_REQUEST['fts_dynamic_name'] ) . '= "' .  str_replace( ['"', "'"], '', $_REQUEST['next_url'] ) . '";';
            echo '</script>';

            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && ! empty( $saved_feed_options['facebook_load_more_style'] ) ) {
                $fts_dynamic_name       = $_REQUEST['fts_dynamic_name'];
                $time                   = time();
                $nonce                  = wp_create_nonce( $time . 'load-more-nonce' );
                $fts_dynamic_class_name =  $this->feed_functions->get_feed_dynamic_class_name();
                echo '<script>';
                echo 'jQuery(document).ready(function() {';
                if ( $saved_feed_options['facebook_load_more_style'] === 'autoscroll' ) {
                    // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode.
                    echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").bind("scroll",function() {';
                    echo 'if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {';
                } else {
                    // this is where we do CLICK function to LOADMORE if  = button in shortcode.
                    echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").off().click(function() {';
                }
                echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").addClass("fts-fb-spinner");';
                echo 'var button = jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").html("<div class=\'bounce1\'></div><div class=\'bounce2\'></div><div class=\'bounce3\'></div>");';
                echo 'console.log(button);';

                echo 'var yes_ajax = "yes";';
                echo 'var fts_d_name = "' . esc_js( $fts_dynamic_name ) . '";';
                echo 'var fts_security = "' . esc_js( $nonce ) . '";';
                echo 'var fts_time = "' . esc_js( $time ) . '";';
                // Shortcode Feed Name.
                echo 'var feed_name = "feed_them_social";';
                // CPT Feed ID
                echo 'var feed_id = ' . esc_js( $feed_post_id ) . ';';
                echo 'var loadmore_count = "posts=' . esc_js( $posts ) . '";';
                //echo 'var feed_attributes = ' . json_encode( $atts ) . ';';


                echo 'jQuery.ajax({';
                echo 'data: {action: "my_fts_fb_load_more", next_url: nextURL_' . sanitize_key( $fts_dynamic_name ) . ', fts_dynamic_name: fts_d_name, feed_name: feed_name, feed_id: feed_id, loadmore_count: loadmore_count, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},';
                echo 'type: "GET",';
                echo 'url: "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '",';
                echo 'success: function( data ) {';
                echo 'console.log("Well Done and got this from sever: " + data);';
                if ( $facebook_post_type && $saved_feed_options['facebook_page_feed_type'] === 'albums' ||
                    $facebook_post_type && $saved_feed_options['facebook_page_feed_type'] === 'album_photos' ||
                    $saved_feed_options['facebook_grid'] === 'yes' ) {
                    echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").append(data).filter(".' . esc_js( $fts_dynamic_class_name ) . '").html();';

                    // if ( isset($saved_feed_options['facebook_container_animation']) && 'yes' === $saved_feed_options['facebook_container_animation'] ) {

                    echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry( "reloadItems");';
                    echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry("layout");';

                    echo 'setTimeout(function() {';
                    // Do something after 3 seconds
                    // This can be direct code, or call to some other function.
                    echo 'jQuery(".fts-slicker-facebook-posts, .fts-mashup").masonry("layout");';
                    echo '}, 500);';

                    // }

                    echo 'if(!nextURL_' . sanitize_key( $_REQUEST['fts_dynamic_name'] ) . ' || nextURL_' . sanitize_key( $_REQUEST['fts_dynamic_name'] ) . ' == "no more"){';

                    $facebook_loadmore_button_width = !empty( $saved_feed_options['facebook_loadmore_button_width'] ) ? $saved_feed_options['facebook_loadmore_button_width'] : 'auto';
                    $loadmore_btn_margin = !empty( $saved_feed_options['loadmore_button_margin'] )  ? $saved_feed_options['loadmore_button_margin'] : '20px auto';

                    if ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {
                        echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb" style="max-width:'.esc_attr( $facebook_loadmore_button_width ).';margin:'. $loadmore_btn_margin .' auto ' . $loadmore_btn_margin. '">' . esc_html( $fb_no_more_reviews_text ) . '</div>\');';
                    } elseif ( $saved_feed_options['facebook_page_feed_type'] === 'videos' ) {
                        echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb" style="max-width:'.esc_attr( $facebook_loadmore_button_width ).';margin:'. $loadmore_btn_margin .' auto ' . $loadmore_btn_margin. '">' . esc_html( $fb_no_more_videos_text ) . '</div>\');';
                    } else {
                        echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb" style="max-width:'.esc_attr( $facebook_loadmore_button_width ).';margin:'. $loadmore_btn_margin .' auto ' . $loadmore_btn_margin. '">' . esc_html( $fb_no_more_photos_text ) . '</div>\');';
                    }

                    echo ' jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeAttr("id");';
                    echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").off("scroll");';
                    echo '}';

                } else {

                    if ( isset( $saved_feed_options['facebook_video_album'] ) && $saved_feed_options['facebook_video_album'] === 'yes' ) {
                        echo 'var result = jQuery(data).insertBefore( jQuery("#output_' . esc_js( $fts_dynamic_name ) . '") );';
                        echo 'var result = jQuery(".feed_dynamic_' . esc_js( $fts_dynamic_name ) . '_album_photos").append(data).filter("#output_' . esc_js( $fts_dynamic_name ) . '").html();';
                    } else {
                        echo 'var result = jQuery("#output_' . esc_js( $fts_dynamic_name ) . '").append(data).filter("#output_' . esc_js( $fts_dynamic_name ) . '").html();';
                    }
                    echo 'jQuery("#output_' . esc_js( $fts_dynamic_name ) . '").html(result);';
                    echo 'if(!nextURL_' . sanitize_key( $_REQUEST['fts_dynamic_name'] ) . ' || nextURL_' . sanitize_key( $_REQUEST['fts_dynamic_name'] ) . ' == "no more"){';
                    // Reviews.
                    if ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' ) {
                        echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_reviews_text ) . '</div>\');';
                    } else {
                        echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">' . esc_html( $fb_no_more_posts_text ) . '</div>\');';
                    }
                    echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeAttr("id");';
                    echo 'jQuery(".' . esc_js( $fts_dynamic_class_name ) . '").off("scroll");';
                    echo '}';

                }
                echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").html("' . esc_html( $fb_load_more_text ) . '");';
                // jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("flip360-fts-load-more");.
                echo 'jQuery("#loadMore_' . esc_js( $fts_dynamic_name ) . '").removeClass("fts-fb-spinner");';
                if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $saved_feed_options['facebook_popup']  ) && $saved_feed_options['facebook_popup'] === 'yes' ) {
                    // We return this function again otherwise the popup won't work correctly for the newly loaded items.
                    echo 'jQuery.fn.slickFacebookPopUpFunction();';
                }
                // Reload the share each Function otherwise you can't open share option..
                echo 'ftsShare();slickremixImageResizingFacebook2();slickremixImageResizingFacebook3();';

                echo '}';
                echo '});';
                // end of ajax().
                echo 'return false;';
                // string $scrollMore is at top of this js script. Exception for scroll option closing tag.
                if ( $saved_feed_options['facebook_load_more_style'] === 'autoscroll' ) {
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
                // this div returns outputs our ajax request via jquery append html from above  style="display:nonee;".
                echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more-output"></div>';
                if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'page' && $saved_feed_options['facebook_load_more_style'] === 'autoscroll' ||
                    is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && $saved_feed_options['facebook_page_feed_type'] === 'reviews' && $saved_feed_options['facebook_load_more_style'] === 'autoscroll' ) {

                    echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Facebook</div>';
                }
            }
        }
        // end of if loadmore is button or autoscroll.
    }
    // end fts_facebook_loadmore().

}//end class