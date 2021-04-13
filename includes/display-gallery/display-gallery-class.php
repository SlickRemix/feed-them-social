<?php
/**
 * Display Gallery
 *
 * Class Display Gallery
 *
 * @class    Display_Gallery
 * @version  1.0.1
 * @package  FeedThemSocial/Core
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Class Display_Gallery
 */
class Display_Gallery {


    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Load Function
     *
     * Load up all our actions and filters.
     *
     * @since 1.0.0
     */
    public static function load() {
        $instance = new self();

        $instance->add_actions_filters();
    }

    /**
     * Display_Gallery constructor.
     */
    public function __construct() {
    }

    /**
     * Add Action Filters
     *
     * Load up all our styles and js.
     *
     * @since 1.0.0
     */
    public function add_actions_filters() {
        // Add API Endpoint.
        add_action( 'rest_api_init', array( $this, 'fts_register_gallery_route' ) );

        // Add Shortcodes.
        add_shortcode( 'feed_them_social', array( $this, 'fts_display_gallery_shortcode' ) );
        add_shortcode( 'ft-gallery-album', array( $this, 'fts_display_gallery_shortcode' ) );

        add_action( 'wp_ajax_fts_update_title_ajax', array( $this, 'fts_update_title_ajax' ) );
        add_action( 'wp_ajax_fts_edit_image_ajax', array( $this, 'fts_edit_image_ajax' ) );
        add_action( 'wp_ajax_fts_update_image_ajax', array( $this, 'fts_update_image_ajax' ) );
        add_action( 'wp_ajax_fts_delete_image_ajax', array( $this, 'fts_delete_image_ajax' ) );
        add_action( 'wp_ajax_fts_update_image_information_ajax', array( $this, 'fts_update_image_information_ajax' ) );

        // Add Display Gallery Scripts.
        add_action( 'current_screen', array( $this, 'fts_display_gallery_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'fts_head' ) );

        add_action( 'wp_ajax_fts_load_more', array( $this, 'fts_load_more' ) );
        add_action( 'wp_ajax_nopriv_fts_load_more', array( $this, 'fts_load_more' ) );
    }

    /**
     *  Register Gallery Route (REST API)
     *
     * Register gallery route to use WordPress's REST API.
     *
     * @since 1.0.0
     */
    public function fts_register_gallery_route() {
        register_rest_route(
            'ftgallery/v2',
            '/post-gallery',
            array(
                'methods'  => \WP_REST_Server::READABLE,
                'callback' => array( $this, 'fts_display_post_images' ),
            )
        );
    }

    /**
     *  Display Gallery Scripts
     *
     * Add scripts to WordPress Admin header.
     *
     * @since 1.0.0
     */
    public function fts_display_gallery_scripts() {
        $current_screen = get_current_screen();
        // if(isset( $_GET['page'], $_GET['tab'] ) && 'wc-settings' === $_GET['page'] && 'slickremix_hide_woo_products' === $_GET['tab'] ) {
        //     wp_enqueue_script( 'fts_display_gallery_scripts', plugins_url( '/feed-them-social/admin/js/admin.js' ), array('jquery'), FTS_CURRENT_VERSION, true );
        // }.

        if ( 'fts' === $current_screen->post_type && 'post' === $current_screen->base || 'fts' === $current_screen->post_type && isset( $_GET['page'] ) && 'template_settings_page' === $_GET['page'] || is_admin() && 'fts_albums' === $current_screen->post_type && 'post' === $current_screen->base ) {
            wp_enqueue_script( 'js_color', plugins_url( '/feed-them-social/metabox-settings/js/jscolor/jscolor.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
            wp_enqueue_script( 'fts_display_gallery_scripts', plugins_url( '/feed-them-social/admin/js/admin.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
            wp_enqueue_script( 'jquery' );
            wp_localize_script( 'fts_display_gallery_scripts', 'ssAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
            wp_enqueue_script( 'fts_display_gallery_scripts' );
        }
    }

    /**
     *  Header Scripts
     *
     * Add gallery scripts to frontend header.
     *
     * @since 1.0.0
     */
    public function fts_head() {

        wp_enqueue_style( 'ft-gallery-feeds', plugins_url( 'feed-them-social/includes/cpt/css/styles.css' ), array(), FTS_CURRENT_VERSION, false );
        wp_enqueue_script( 'ft-masonry-pkgd', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
        wp_enqueue_script( 'ft-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, true );
        wp_enqueue_script( 'ft-front-end-js', plugins_url( 'feed-them-social/includes/js/front-end.js' ), array(), FTS_CURRENT_VERSION, true );
        if ( is_plugin_active( 'feed_them_social-premium/feed-them-social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            wp_enqueue_script( 'add-to-cart-ajax_ajax', plugins_url() . '/feed-them-social/includes/feeds/js/add-to-cart-ajax.js', array( 'jquery' ), FTS_CURRENT_VERSION, true );
        }
        $php_info = array(
            'enable_right_click' => null !== get_option( 'fts_enable_right_click' ) ? get_option( 'fts_enable_right_click' ) : '',
        );
        wp_localize_script( 'ft-front-end-js', 'ftgPremiumOption', $php_info );
    }

    /**
     * FTS Ago
     *
     * Create date format like fb and twitter. Thanks: http://php.quicoto.com/how-to-calculate-relative-time-like-facebook/ .
     *
     * @param string $timestamp The time stamp so we can convert it.
     * @return string
     * @since 1.0.0
     */
    public function fts_ago( $timestamp ) {
        // not setting isset'ing anything because you have to save the settings page to even enable this feature.
        $fts_language_second = get_option( 'fts_language_second' );
        if ( empty( $fts_language_second ) ) {
            $fts_language_second = 'second';
        }
        $fts_language_seconds = get_option( 'fts_language_seconds' );
        if ( empty( $fts_language_seconds ) ) {
            $fts_language_seconds = 'seconds';
        }
        $fts_language_minute = get_option( 'fts_language_minute' );
        if ( empty( $fts_language_minute ) ) {
            $fts_language_minute = 'minute';
        }
        $fts_language_minutes = get_option( 'fts_language_minutes' );
        if ( empty( $fts_language_minutes ) ) {
            $fts_language_minutes = 'minutes';
        }
        $fts_language_hour = get_option( 'fts_language_hour' );
        if ( empty( $fts_language_hour ) ) {
            $fts_language_hour = 'hour';
        }
        $fts_language_hours = get_option( 'fts_language_hours' );
        if ( empty( $fts_language_hours ) ) {
            $fts_language_hours = 'hours';
        }
        $fts_language_day = get_option( 'fts_language_day' );
        if ( empty( $fts_language_day ) ) {
            $fts_language_day = 'day';
        }
        $fts_language_days = get_option( 'fts_language_days' );
        if ( empty( $fts_language_days ) ) {
            $fts_language_days = 'days';
        }
        $fts_language_week = get_option( 'fts_language_week' );
        if ( empty( $fts_language_week ) ) {
            $fts_language_week = 'week';
        }
        $fts_language_weeks = get_option( 'fts_language_weeks' );
        if ( empty( $fts_language_weeks ) ) {
            $fts_language_weeks = 'weeks';
        }
        $fts_language_month = get_option( 'fts_language_month' );
        if ( empty( $fts_language_month ) ) {
            $fts_language_month = 'month';
        }
        $fts_language_months = get_option( 'fts_language_months' );
        if ( empty( $fts_language_months ) ) {
            $fts_language_months = 'months';
        }
        $fts_language_year = get_option( 'fts_language_year' );
        if ( empty( $fts_language_year ) ) {
            $fts_language_year = 'year';
        }
        $fts_language_years = get_option( 'fts_language_years' );
        if ( empty( $fts_language_years ) ) {
            $fts_language_years = 'years';
        }
        $fts_language_ago = get_option( 'fts_language_ago' );
        if ( empty( $fts_language_ago ) ) {
            $fts_language_ago = 'ago';
        }

        $periods        = array( $fts_language_second, $fts_language_minute, $fts_language_hour, $fts_language_day, $fts_language_week, $fts_language_month, $fts_language_year, 'decade' );
        $periods_plural = array( $fts_language_seconds, $fts_language_minutes, $fts_language_hours, $fts_language_days, $fts_language_weeks, $fts_language_months, $fts_language_years, 'decades' );

        if ( ! is_numeric( $timestamp ) ) {
            $timestamp = strtotime( $timestamp );
            if ( ! is_numeric( $timestamp ) ) {
                return '';
            }
        }
        $difference = date_i18n( time() ) - $timestamp;
        // Customize in your own language. Why thank-you I will.
        $lengths = array( '60', '60', '24', '7', '4.35', '12', '10' );

        if ( $difference > 0 ) {
            // this was in the past.
            $ending = $fts_language_ago;
        } else {
            // this was in the future.
            $difference = -$difference;
            // not doing dates in the future for posts.
            $ending = 'to go';
        }
        $the_count = count( $lengths );
        for ( $j = 0; $difference >= $lengths[ $j ] && $j < $the_count - 1; $j++ ) {
            $difference /= $lengths[ $j ];
        }

        $difference = round( $difference );

        if ( 1 !== $difference ) {
            $periods[ $j ] = $periods_plural[ $j ];
        }
        return "$difference $periods[$j] $ending";

    }

    /**
     *  Rand String
     *
     * Random String Generator
     *
     * @param int $length The length of the random string to be returned.
     * @return string
     * @since 1.0.0
     */
    public function fts_rand_string( $length = 10 ) {
        $characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen( $characters );
        $random_string     = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
        }

        return $random_string;
    }

    /**
     *  Custom Date
     *
     * Generate Custom Date using settings from Settings Page
     *
     * @param string $created_time The time it was created.
     * @return string
     * @since 1.0.0
     */
    public function fts_custom_date( $created_time ) {
        $fts_custom_date   = get_option( 'ft-gallery-custom-date' );
        $fts_custom_time   = get_option( 'ft-gallery-custom-time' );
        $custom_date_check = get_option( 'ft-gallery-date-and-time-format' );
        $fts_timezone      = get_option( 'ft-gallery-timezone' );

        if ( '' === $fts_custom_date && '' === $fts_custom_time ) {
            $custom_date_format = $custom_date_check;
        } elseif ( '' !== $fts_custom_date || '' !== $fts_custom_time ) {
            $custom_date_format = $fts_custom_date . ' ' . $fts_custom_time;
        } else {
            $custom_date_format = 'F jS, Y \a\t g:ia';
        }
        if ( ! empty( $fts_timezone ) ) {
            date_default_timezone_set( $fts_timezone );
        }

        if ( 'one-day-ago' === $custom_date_check ) {
            $u_time = $this->fts_ago( $created_time );
        } else {
            $u_time = ! empty( $custom_date_check ) ? date_i18n( $custom_date_format, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
        }

        // Return the time.
        return $u_time;
    }

    /**
     *  Custom Trim Words
     *
     * This function is a duplicate of fb trim words and is used for all feeds except fb, which uses it's original function that also filters tags which we don't need.
     *
     * @param string $text The text to be trimmed.
     * @param int    $num_words The number of works to return.
     * @param string $more What to use after the words are trimmed.
     * @return mixed
     * @since 1.0.0
     */
    public function fts_trim_words( $text, $num_words = 45, $more ) {
        ! empty( $num_words ) && 0 !== $num_words ? $more : __( '...' );
        $text = nl2br( $text );
        $text = strip_shortcodes( $text );
        // Add tags that you don't want stripped.
        $text        = strip_tags( $text, '<strong><br><em><i><a>' );
        $words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
        $sep         = ' ';
        if ( count( $words_array ) > $num_words ) {
            array_pop( $words_array );
            $text  = implode( $sep, $words_array );
            $text .= $more;
        } else {
            $text = implode( $sep, $words_array );
        }

        return wpautop( $text );
    }


    /**
     *  Get Image Sizes
     *
     * @param int $attachment_id The attachment ID.
     * @return mixed
     * @since 1.0.0
     */
    public function fts_get_image_sizes( $attachment_id ) {

        return wp_get_attachment_metadata( $attachment_id );
    }

    /**
     *  Get Attachment
     *
     * Get attachement info from core function
     *
     * @param int $attachment_id The attachment ID.
     * @return array
     * @since 1.0.0
     */
    public function fts_get_attachment( $attachment_id ) {

        return $this->fts_get_attachment_info( $attachment_id );
    }

    /**
     *  Display Post Images
     * Return a List of Images attached to a post.
     *
     * @return mixed
     * @since
     */
    public function fts_display_post_images() {
        global $post;
        $final_id = $post->ID;

        $args = array(
            'post_type'      => 'attachment',
            'numberposts'    => -1,
            'post_status'    => null,
            'post_mime_type' => 'image',
            'post_parent'    => $final_id,
        );

        return get_posts( $args );
    }


    /**
     *  Get Media Rest (REST API)
     *
     * Get Media using WordPress's REST API
     *
     * @param string $parent_post_id The parent post ID to be returned.
     * @param int    $per_page How many items to return per page.
     * @return string
     * @since 1.0.0
     */
    public function fts_get_media_rest( $parent_post_id, $per_page = '100' ) {

        $request = new \WP_REST_Request( 'GET', '/wp/v2/media' );
        // Set one or more request query parameters.
        $request->set_param( 'per_page', $per_page );
        $request->set_param( 'parent', $parent_post_id );
        $request->set_param( 'media_type', 'image' );

        $response = rest_do_request( $request );
        // print_r($response); .
        // Check for error.
        if ( is_wp_error( $response ) ) {
            return 'oops something isn\'t right.';
        }

        $final_response = isset( $response->data ) ? $response->data : 'No Images attached to this post.';

        return $final_response;
    }

    /**
     *  Delete Gallery Image REST API
     *
     * Delete Image using WordPress's REST API
     *
     * @param int $parent_post_id The parent post ID.
     * @return string
     * @since 1.0.0
     */
    public function fts_delete_media_rest( $parent_post_id ) {
        $request = new \WP_REST_Request( 'DELETE', '/wp/v2/media/' . $parent_post_id );
        // Set one or more request query parameters.
        $request->set_param( 'force', true );

        $response = rest_do_request( $request );

        // Check for error.
        if ( is_wp_error( $response ) ) {
            return 'oops something isn\'t right.';
        }

        $final_response = isset( $response->data ) ? $response->data : 'No Images attached to this post.';

        return $final_response;
    }

    /**
     *  Update Media Rest
     *
     * Update or Remove Gallery Image REST API
     *
     * @link https://developer.wordpress.org/rest-api/reference/media/#update-media
     *
     * @param int   $parent_post_id The parent post ID.
     * @param array $args An Array of post arguments.
     * @return string
     * @see fts_delete_quick_item_ajax() Where ajax is fired to trigger this function
     * @since 1.0.0
     */
    public function fts_update_media_rest( $parent_post_id, array $args ) {
        $request = new \WP_REST_Request( 'POST', '/wp/v2/media/' . $parent_post_id );

        // Set each Parameter passed.
        if ( isset( $args ) && ! empty( $args ) ) {
            foreach ( $args as $param => $value ) {
                // Set Parameter and Value of Parameter.
                $request->set_param( $param, $value );
            }
        }

        $response = rest_do_request( $request );

        // Check for error.
        if ( is_wp_error( $response ) ) {
            return 'oops something isn\'t right:' . $response;
        }

        $final_response = isset( $response->data ) ? $response->data : 'No Images attached to this post.';

        return $final_response;
    }

    /**
     *  Delete Image AJAX
     *
     * Delete an image using AJAX.
     *
     * @since 1.0.0
     */
    public function fts_delete_image_ajax() {
        $permission = check_ajax_referer( 'fts_delete_image_nonce', 'nonce', false );
        if ( false == $permission ) {
            esc_html_e( 'error', 'feed_them_social' );
        } else {
            $display_gallery = new Display_Gallery();
            $my_request      = stripslashes_deep( $_REQUEST );
            $request         = isset( $my_request['id'] ) ? sanitize_text_field( wp_unslash( $my_request['id'] ) ) : '';
            $display_gallery->fts_delete_media_rest( $request );
            esc_html_e( 'success', 'feed_them_social' );
        }
        exit();
    }

    /**
     *  Update Image AJAX
     *
     * Update image using AJAX
     *
     * @since 1.0.0
     */
    public function fts_update_image_ajax() {

        $display_gallery = new Display_Gallery();

        $my_request = stripslashes_deep( $_REQUEST );
        // Remove image from Gallery if data-remove on a tag.
        if ( isset( $my_request['fts_img_remove'] ) && 'true' === $my_request['fts_img_remove'] ) {
            $args = array( 'post' => null );
        }

        $request = isset( $my_request['id'] ) ? sanitize_text_field( wp_unslash( $my_request['id'] ) ) : '';
        $display_gallery->fts_update_media_rest( sanitize_text_field( $request ), $args );
        esc_html_e( 'Update success', 'feed_them_social' );

        exit();
    }

    /**
     *  Update Image Information AJAX
     *
     * Preload Image information to input fields in popup AJAX
     *
     * @since 1.0.0
     */
    public function fts_update_image_information_ajax() {
        $permission = check_ajax_referer( 'fts_edit_image_nonce', 'nonce', false );
        if ( false == $permission ) {
            echo 'error';
        } else {

            $my_request       = stripslashes_deep( $_REQUEST );
            $gallery_class    = new Gallery();
            $request          = isset( $my_request['id'] ) ? sanitize_text_field( wp_unslash( $my_request['id'] ) ) : '';
            $attachment_array = $gallery_class->fts_get_attachment_info( $request );

            echo wp_json_encode( $attachment_array );
        }
        exit();
    }


    /**
     *  Edit Image AJAX
     *
     * Edit image using AJAX
     *
     * @since 1.0.0
     */
    public function fts_edit_image_ajax() {

        $my_request = stripslashes_deep( $_REQUEST );

        if ( isset( $my_request['nonce'] ) && 'attach_image' !== $my_request['nonce'] ) {
            $permission = check_ajax_referer( 'fts_edit_image_nonce', 'nonce', false );
            if ( '' === $permission ) {
                echo 'error';
            } else {

                $img_title       = isset( $my_request['title'] ) && ! empty( $my_request['title'] ) ? sanitize_text_field( wp_unslash( $my_request['title'] ) ) : '';
                $img_alttext     = isset( $my_request['alttext'] ) && ! empty( $my_request['alttext'] ) ? sanitize_text_field( wp_unslash( $my_request['alttext'] ) ) : '';
                $img_description = isset( $my_request['description'] ) && ! empty( $my_request['description'] ) ? wp_kses(
                    $my_request['description'],
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
                        'p'      => array(),
                    )
                ) : '';

                // Set Parameters for image from Gallery.
                $args = array(
                    'title'       => $img_title,
                    'alt_text'    => $img_alttext,
                    'description' => $img_description,
                );

                $request_id = isset( $my_request['id'] ) ? sanitize_text_field( wp_unslash( $my_request['id'] ) ) : '';

                $this->fts_update_media_rest( $request_id, $args );

                $gallery_class = new Gallery();

                // Get Attachment Info.
                $attachment_array = $gallery_class->fts_get_attachment_info( $request_id );

                echo wp_json_encode( $attachment_array );
            }
        } else {
            $request_post_id = isset( $my_request['postID'] ) ? sanitize_text_field( wp_unslash( $my_request['postID'] ) ) : '';
            $args            = array( 'post' => $request_post_id );

            $request_id = isset( $my_request['id'] ) ? sanitize_text_field( wp_unslash( $my_request['id'] ) ) : '';
            $this->fts_update_media_rest( $request_id, $args );
            echo esc_html( $request_id ) . ' Image attached to this post';
        }
        exit();
    }

    /**
     *  Update Title AJAX
     *
     * Add file name as title on image plupload
     * Í
     *
     * @since 1.0.0
     */
    public function fts_update_title_ajax() {

        $my_request   = stripslashes_deep( $_REQUEST );
        $request_url  = isset( $my_request['url'] ) ? sanitize_text_field( wp_unslash( $my_request['url'] ) ) : '';
        $url          = isset( $request_url ) && ! empty( $request_url ) ? $request_url : '';
        $get_image_id = $this->fts_get_attachment_id( $url );

        $display_gallery = new Display_Gallery();

        // Set Parameters for image from Gallery.
        $args = array(
            'title' => '',
        );

        $display_gallery->fts_update_media_rest( $get_image_id, $args );
        echo esc_html( $get_image_id );

        exit();
    }

    /**
     *  Sort Order Select
     *
     * Sort order select option to sort the images on front end.
     *
     * @param string $ftg_id The ID of the post we are sorting image for.
     *
     * @since 1.0.0
     */
    public function ftg_sort_order_select( $ftg_id ) {

        $option = $this->fts_get_option_or_get_postmeta( $ftg_id );

        $orderby_date         = isset( $_GET['orderby'] ) && 'date' === $_GET['orderby'] ? 'selected="selected"' : '';
        $orderby_alphabetical = isset( $_GET['orderby'] ) && 'title' === $_GET['orderby'] ? 'selected="selected"' : '';
        $orderby_original     = isset( $_GET['orderby'] ) && 'menu_order' === $_GET['orderby'] ? 'selected="selected"' : '';

        $ftg_align_pagination = null !== $option['ftg_align_sort_select'] ? $option['ftg_align_sort_select'] : '';

        $align_class = 'right' === $ftg_align_pagination ? 'ftg-sort-order-right' : '';

        $onchange = isset( $_GET['ftg-tags'] ) ? 'location.href = location.href + \'&orderby=\' + orderby.options[selectedIndex].value' : 'this.form.submit()';

        print '<div class="ftg-orderby-wrap ' . esc_attr( $align_class ) . '"><form class="feed_them_social-ordering" method="get" ><select name="orderby" class="ftg-orderby" onchange="' . esc_attr( $onchange ) . '">
					<option value="menu_order" ' . esc_html( $orderby_original ) . '>' . esc_html( 'Sort order of Images', 'feed_them_social' ) . '</option>
					<option value="title" ' . esc_html( $orderby_alphabetical ) . '>' . esc_html( 'Sort alphabetically (A-Z)', 'feed_them_social' ) . '</option>
					<option value="date" ' . esc_html( $orderby_date ) . '>' . esc_html( 'Sort by date', 'feed_them_social' ) . '</option></select></form></div>';
    }

    /**
     * FT Pagination
     *
     * Paginate the photos on the front end.
     *
     * @param string $ftg_id The ID of the post we are sorting image for.
     * @param string $is_album Check to see if this an album or not.
     * @param string $tags The tags.
     * @param string $tags_list Tags List.
     * @param string $image_count_for_tags The image count for tags.
     *
     * @since 1.0.0
     */
    public function ftg_pagination( $ftg_id, $is_album = null, $tags, $tags_list, $image_count_for_tags = null ) {

        $option = $this->fts_get_option_or_get_postmeta( $ftg_id );

        $per_page = $option['fts_pagination_photo_count'];

        $gallery_class = new Gallery();

        if ( isset( $is_album ) && 'yes' === $is_album ) {

            $total_pagination_count = $gallery_class->ft_album_count_post_galleries( $ftg_id );
            $pagination_text        = esc_html( 'Galleries', 'feed_them_social' );

        } elseif ( isset( $tags ) && 'yes' === $tags ) {

            // can be category, post_tag, or custom taxonomy name.
            $taxonomy               = 'ftg-tags';
            $my_get                 = stripslashes_deep( $_GET );
            $total_pagination_count = $image_count_for_tags;
            $ftg_tags               = sanitize_text_field( wp_unslash( $my_get[ $taxonomy ] ) );
            $pagination_text        = isset( $ftg_tags ) && 'page' === $ftg_tags ? esc_html( 'Galleries', 'feed_them_social' ) : esc_html( 'Images', 'feed_them_social' );

        } else {
            $total_pagination_count = $gallery_class->fts_count_post_images( $ftg_id );
            $pagination_text        = esc_html( 'Images', 'feed_them_social' );
        }

        $check_total_pagination_count = ceil( esc_html( $total_pagination_count ) / esc_html( $per_page ) );

        if ( $check_total_pagination_count <= get_query_var( 'page' ) ) {
            // This is the final count number, meaning the last page of pagination.
            $count_fix      = get_query_var( 'page' ) - '1';
            $per_page_final = $per_page * $count_fix + 1;
            $count_per_page = $total_pagination_count;
        } elseif ( '1' < get_query_var( 'page' ) ) {
            // This is any other number that 1 or the last page.
            $count_per_page = min( $total_pagination_count, $per_page * get_query_var( 'page' ) );
            $per_page_final = $count_per_page - $per_page + 1;
        } else {
            // This is only for the 1st page.
            $per_page_final = '1';
            $count_per_page = $per_page < $total_pagination_count ? $per_page : $total_pagination_count;
        }

        $ftg_align_pagination = null !== $option['ftg_align_pagination'] ? $option['ftg_align_pagination'] : '';
        $ftg_align_count      = null !== $option['ftg_align_count'] ? $option['ftg_align_count'] : '';
        $ftg_display_count    = null !== $option['ftg_display_image_count'] ? $option['ftg_display_image_count'] : '';

        $fts_pagination_text_color          = $option['fts_pagination_text_color'] ? '.ftg-pagination .page-numbers{color:' . esc_html( $option['fts_pagination_text_color'] ) . '!important;}' : '';
        $fts_pagination_button_color        = $option['fts_pagination_button_color'] ? '.ftg-pagination a.page-numbers{background:' . esc_html( $option['fts_pagination_button_color'] ) . '!important;}' : '';
        $fts_pagination_active_button_color = $option['fts_pagination_active_button_color'] ? '.ftg-pagination .page-numbers.current{background:' . esc_html( $option['fts_pagination_active_button_color'] ) . '!important;}' : '';

        if ( '' !== $fts_pagination_text_color || '' !== $fts_pagination_button_color || '' !== $fts_pagination_active_button_color ) {
            // FINISH CONVERTING THE PAGINATION STYLES TO SHOW... I NEED TO MOVE THIS TO STYLES IN HEADER I THINK... I DON'T SEE INLINE STYLE OPTIONS FOR https://developer.wordpress.org/reference/functions/paginate_links/ .
            print '<style>' . esc_html( $fts_pagination_text_color ) . esc_html( $fts_pagination_button_color ) . esc_html( $fts_pagination_active_button_color ) . '</style>';
        }

        $align_class = 'left' === $ftg_align_pagination ? ' ftg-page-left' : '';

        $align_count_class = 'right' === $ftg_align_count ? ' ftg-total-page-count-align-right' : '';

        $fts_true_pagination_count_text_color = null !== $option['fts_true_pagination_count_text_color'] ? ' style="color:' . esc_attr( $option['fts_true_pagination_count_text_color'] ) . '"' : '';
        $page_count                                  = 'yes' === $ftg_display_count ? '<div class="ftg-total-pagination-count' . esc_attr( $align_count_class ) . '"' . $fts_true_pagination_count_text_color . '>' . esc_html( 'Showing', 'feed_them_social' ) . ' ' . esc_html( $per_page_final ) . '-' . esc_html( $count_per_page ) . ' of ' . esc_html( $total_pagination_count ) . ' ' . esc_html( $pagination_text ) . '</div>' : '';

        if ( 'left' === $ftg_align_pagination ) {
            print wp_kses(
                $page_count,
                array(
                    'div' => array(
                        'class' => array(),
                        'style' => array(),
                    ),
                )
            );
        }

        print '<div class="ftg-pagination' . esc_attr( $align_class ) . '">';

        $pagination_counts = paginate_links(
            array(
                'base'      => add_query_arg( 'page', '%#%' ),
                'format'    => '?page=%#%',
                'current'   => max( 1, get_query_var( 'page' ) ),
                'mid_size'  => 3,
                'end_size'  => 3,
                'prev_text' => __( '&#10094;' ),
                'next_text' => __( '&#10095;' ),
                'total'     => ceil( esc_html( $total_pagination_count ) / esc_html( $per_page ) ), // 3 items per page
            )
        );
        print wp_kses(
            $pagination_counts,
            array(
                'a'    => array(
                    'href'  => array(),
                    'class' => array(),
                ),
                'span' => array(
                    'class' => array(),
                ),
            )
        );
        print '</div>';
        if ( 'right' === $ftg_align_pagination ) {
            print wp_kses(
                $page_count,
                array(
                    'div' => array(
                        'class' => array(),
                        'style' => array(),
                    ),
                )
            );
        }
        print '<div class="clear"></div>';
    }

    /**
     *
     * Album Gallery List of ids
     *
     * Outputs a comma delimited list of galleries attached to an album.
     *
     * @param array $image_list_check Return a list of album gallery ids.
     * @return array
     * @since 1.1.5
     */
    public function albums_gallery_list_of_ids( $image_list_check ) {
        $result = array(); // Create empty string.

        foreach ( $image_list_check as $id ) {
            $result[] = $id->ID;
        }

        return $result;
    }

    /**
     *
     *  Get Option or Get Post Meta
     *
     * Passes values from galleries or template page
     *
     * @param string $ftg_id Gallery ID.
     * @return string
     * @since 1.1.6
     */
    public function fts_get_option_or_get_postmeta( $ftg_id ) {

        if ( 'tags' === $ftg_id ) {
            $option = get_option( 'template_settings_page_settings_options' );
        } else {

            $post_type = get_post_type( $ftg_id );

            if ( 'fts' === $post_type ) {
                $new_options_array = get_post_meta( $ftg_id, 'fts_settings_options', true );
            } elseif ( 'fts_albums' === $post_type ) {
                $new_options_array = get_post_meta( $ftg_id, 'fts_albums_settings_options', true );
            }

            if ( isset( $new_options_array ) && is_array( $new_options_array ) ) {
                $option = $new_options_array;
            } else {
                // this is our fall back to make galleries work before the 1.1.6 update
                // grab all possible meta values of the post in array.
                $get_post_meta_array = get_post_meta( $ftg_id );

                foreach ( $get_post_meta_array as $key => $value ) {
                    $option[ $key ] = $value[0];
                }
            }
        }

        return isset( $option ) ? $option : '';
    }


    /**
     *
     *  Display Gallery Shortcode
     *
     * Create shortcode to display shortcode
     *
     * @param array  $atts The shortcode attributes used.
     * @param string $tag If it's a tag or not.
     * @return string
     * @since 1.0.0
     */
    public function fts_display_gallery_shortcode( $atts, $content = null, $tag ) {

        $ftg = shortcode_atts(
            array(
                // All We need is ID of Gallery Post.
                'id'          => '',
                'offset'      => '',
                'media_count' => '',
            ),
            $atts
        );

        $gallery_class = new Gallery();

        $option = $this->fts_get_option_or_get_postmeta( $ftg['id'] );

        $ftg['is_album'] = 'ft-gallery-album' === $tag ? 'yes' : '';

        $album_gallery_ids = get_post_meta( $ftg['id'], 'fts_album_gallery_ids', true ) ? get_post_meta( $ftg['id'], 'fts_album_gallery_ids', true ) : '';

        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

            // START IMAGE TAGS.
            $ft_tags_link_color               = isset( $option['ft_tags_link_color'] ) ? $option['ft_tags_link_color'] : '';
            $ft_tags_text_size                = isset( $option['ft_tags_text_size'] ) ? $option['ft_tags_text_size'] : '';
            $ft_tags_text_margin_right        = isset( $option['ft_tags_text_margin_right'] ) ? $option['ft_tags_text_margin_right'] : '';
            $fts_tags_background_color = isset( $option['fts_tags_background_color'] ) ? $option['fts_tags_background_color'] : '';
            $fts_tags_padding          = isset( $option['fts_tags_padding'] ) ? $option['fts_tags_padding'] : '';
            $ft_tags_text_color               = isset( $option['ft_tags_text_color'] ) ? $option['ft_tags_text_color'] : '';
            $fts_tags_text_size        = isset( $option['fts_tags_text_size'] ) ? $option['fts_tags_text_size'] : '';
            $ftg_align_tags                   = isset( $option['ftg_align_tags'] ) && 'left' !== $option['ftg_align_tags'] ? $option['ftg_align_tags'] : '';

            if ( isset( $ft_tags_link_color ) && ! empty( $ft_tags_link_color ) ) {
                $ft_tags_link_color = 'color:' . $ft_tags_link_color . ';';
            }
            if ( isset( $ft_tags_text_size ) && ! empty( $ft_tags_text_size ) ) {
                $ft_tags_text_size = 'font-size:' . $ft_tags_text_size . ';';
            }
            if ( isset( $ft_tags_text_color ) && ! empty( $ft_tags_text_color ) ) {
                $ft_tags_text_color = 'color:' . $ft_tags_text_color . ';';
            }
            if ( isset( $fts_tags_background_color ) && ! empty( $fts_tags_background_color ) ) {
                $fts_tags_background_color = 'background:' . $fts_tags_background_color . ';';
            }
            if ( isset( $fts_tags_padding ) && ! empty( $fts_tags_padding ) ) {
                $fts_tags_padding = 'padding:' . $fts_tags_padding . ';';
            }
            if ( isset( $ftg_align_tags ) && ! empty( $ftg_align_tags ) ) {
                $ftg_align_tags = 'text-align:' . $ftg_align_tags . ';';
            }
            if ( isset( $fts_tags_text_size ) && ! empty( $fts_tags_text_size ) ) {
                $fts_tags_text_size = 'font-size:' . $fts_tags_text_size . ';';
            }
            if ( isset( $ft_tags_text_margin_right ) && ! empty( $ft_tags_text_margin_right ) ) {
                $ft_tags_text_margin_right = 'margin-right:' . $ft_tags_text_margin_right . ';';
            }

            if ( ! empty( $ft_tags_link_color ) || ! empty( $ft_tags_text_size ) || ! empty( $ft_tags_text_margin_right ) || ! empty( $ft_tags_text_color ) || ! empty( $fts_tags_background_color ) || ! empty( $fts_tags_padding ) || ! empty( $ftg_align_tags ) || ! empty( $fts_tags_text_size ) ) {
                print '<style>';
                if ( ! empty( $fts_tags_background_color ) || ! empty( $fts_tags_padding ) || ! empty( $ftg_align_tags ) ) {
                    print '.ftg-image-terms-list{' . esc_html( $fts_tags_background_color . $fts_tags_padding . $ftg_align_tags ) . '}';
                }
                if ( ! empty( $fts_tags_text_size ) || ! empty( $ft_tags_text_color ) || ! empty( $ft_tags_text_margin_right ) ) {
                    print '.ftg-image-tags-text{' . esc_html( $fts_tags_text_size . $ft_tags_text_color . $ft_tags_text_margin_right ) . '}';
                }
                if ( ! empty( $ft_tags_link_color ) || ! empty( $ft_tags_text_size ) ) {
                    print '.ftg-image-terms-list a{' . esc_html( $ft_tags_link_color . $ft_tags_text_size ) . '}';
                }
                print '</style>';
            }
            // END IMAGE TAGS.
            // START GALLERY TAGS.
            $ft_page_tags_link_color               = isset( $option['ft_page_tags_link_color'] ) ? $option['ft_page_tags_link_color'] : '';
            $ft_page_tags_text_size                = isset( $option['ft_page_tags_text_size'] ) ? $option['ft_page_tags_text_size'] : '';
            $ft_page_tags_text_margin_right        = isset( $option['ft_page_tags_text_margin_right'] ) ? $option['ft_page_tags_text_margin_right'] : '';
            $ft_page_gallery_tags_background_color = isset( $option['ft_page_gallery_tags_background_color'] ) ? $option['ft_page_gallery_tags_background_color'] : '';
            $fts_page_tags_padding          = isset( $option['fts_page_tags_padding'] ) ? $option['fts_page_tags_padding'] : '';
            $ft_page_tags_text_color               = isset( $option['ft_page_tags_text_color'] ) ? $option['ft_page_tags_text_color'] : '';
            $fts_page_tags_text_size        = isset( $option['fts_page_tags_text_size'] ) ? $option['fts_page_tags_text_size'] : '';
            $ftg_align_page_tags                   = isset( $option['ftg_align_page_tags'] ) && 'left' !== $option['ftg_align_page_tags'] ? $option['ftg_align_page_tags'] : '';

            if ( isset( $ft_page_tags_link_color ) && ! empty( $ft_page_tags_link_color ) ) {
                $ft_page_tags_link_color = 'color:' . $ft_page_tags_link_color . ';';
            }
            if ( isset( $ft_page_tags_text_size ) && ! empty( $ft_page_tags_text_size ) ) {
                $ft_page_tags_text_size = 'font-size:' . $ft_page_tags_text_size . ';';
            }
            if ( isset( $ft_page_tags_text_color ) && ! empty( $ft_page_tags_text_color ) ) {
                $ft_page_tags_text_color = 'color:' . $ft_page_tags_text_color . ';';
            }
            if ( isset( $ft_page_gallery_tags_background_color ) && ! empty( $ft_page_gallery_tags_background_color ) ) {
                $ft_page_gallery_tags_background_color = 'background:' . $ft_page_gallery_tags_background_color . ';';
            }
            if ( isset( $fts_page_tags_padding ) && ! empty( $fts_page_tags_padding ) ) {
                $fts_page_tags_padding = 'padding:' . $fts_page_tags_padding . ';';
            }
            if ( isset( $ftg_align_page_tags ) && ! empty( $ftg_align_page_tags ) ) {
                $ftg_align_page_tags = 'text-align:' . $ftg_align_page_tags . ';';
            }
            if ( isset( $fts_page_tags_text_size ) && ! empty( $fts_page_tags_text_size ) ) {
                $fts_page_tags_text_size = 'font-size:' . $fts_page_tags_text_size . ';';
            }
            if ( isset( $ft_page_tags_text_margin_right ) && ! empty( $ft_page_tags_text_margin_right ) ) {
                $ft_page_tags_text_margin_right = 'margin-right:' . $ft_page_tags_text_margin_right . ';';
            }

            if ( ! empty( $ft_page_tags_link_color ) || ! empty( $ft_page_tags_text_size ) || ! empty( $ft_page_tags_text_margin_right ) || ! empty( $ft_page_tags_text_color ) || ! empty( $ft_page_gallery_tags_background_color ) || ! empty( $fts_page_tags_padding ) || ! empty( $ftg_align_page_tags ) || ! empty( $fts_page_tags_text_size ) ) {
                print '<style>';
                if ( ! empty( $ft_page_gallery_tags_background_color ) || ! empty( $fts_page_tags_padding ) || ! empty( $ftg_align_page_tags ) ) {
                    print '.ftg-page-terms-list{' . esc_html( $ft_page_gallery_tags_background_color . $fts_page_tags_padding . $ftg_align_page_tags ) . '}';
                }
                if ( ! empty( $fts_page_tags_text_size ) || ! empty( $ft_page_tags_text_color ) || ! empty( $ft_page_tags_text_margin_right ) ) {
                    print '.ftg-page-tags-text{' . esc_html( $fts_page_tags_text_size . $ft_page_tags_text_color . $ft_page_tags_text_margin_right ) . '}';
                }
                if ( ! empty( $ft_page_tags_link_color ) || ! empty( $ft_page_tags_text_size ) ) {
                    print '.ftg-page-tags-link, .ftg-page-tags-link a{' . esc_html( $ft_page_tags_link_color . $ft_page_tags_text_size ) . '}';
                }
                print '</style>';
            }

            $ftg_woo_icon_background_color = isset( $option['ftg_woo_icon_background_color'] ) ? $option['ftg_woo_icon_background_color'] : '';
            $ftg_woo_icon_color            = isset( $option['ftg_woo_icon_color'] ) ? $option['ftg_woo_icon_color'] : '';
            $ftg_woo_icon_hover_color      = isset( $option['ftg_woo_icon_hover_color'] ) ? $option['ftg_woo_icon_hover_color'] : '';

            // END GALLERY TAGS.
            if ( isset( $option['ft_popup_display_options'] ) && 'full-width-second-half-bottom' === $option['ft_popup_display_options'] ||
                isset( $option['ft_popup_display_options'] ) && 'full-width-photo-only' === $option['ft_popup_display_options'] ||
                '' !== $ftg_woo_icon_background_color || '' !== $ftg_woo_icon_color || '' !== $ftg_woo_icon_hover_color ) {
                ?>
                <style>
                    <?php
                    if ( '' !== $ftg_woo_icon_background_color ) {
                        ?>
                    .ft-gallery-responsive-cart-icon {
                        background: <?php echo esc_attr( $ftg_woo_icon_background_color ); ?>!important
                    }
                    <?php
                }
                if ( '' !== $ftg_woo_icon_color ) {
                    ?>
                    .ft-gallery-responsive-cart-icon a {
                        color: <?php echo esc_attr( $ftg_woo_icon_color ); ?>!important
                    }

                    <?php
                }
                if ( '' !== $ftg_woo_icon_hover_color ) {
                    ?>
                    .fts-feed-type-wp_gallery:hover a {
                        color: <?php echo esc_attr( $ftg_woo_icon_hover_color ); ?>!important
                    }
                    <?php
                }
                if ( isset( $option['ft_popup_display_options'] ) && 'full-width-second-half-bottom' === $option['ft_popup_display_options'] ) {
                    ?>
                    @media (min-width: 0px) {
                        .ft-gallery-popup .fts-popup-second-half.fts-instagram-popup-second-half {
                            float: left !important
                        }

                        .ft-gallery-popup .fts-popup-second-half {
                            height: 100% !important;
                            width: 100% !important;
                            position: relative !important;
                            float: left !important;
                        }

                        .ft-gallery-popup .fts-popup-half {
                            background: #000 !important;
                            text-align: center !important;
                            vertical-align: middle !important;
                            z-index: 500 !important;
                            width: 100% !important;
                        }

                        .ft-gallery-popup .mfp-bottom-bar {
                            background: #FFF;
                            padding-bottom: 10px
                        }

                        .ft-gallery-popup .mfp-iframe-holder .mfp-content {
                            top: 0
                        }

                        .ft-gallery-popup .mfp-iframe-holder .fts-popup-image-position {
                            height: auto !important
                        }

                        .ft-gallery-popup .mfp-container {
                            padding-top: 40px;
                            padding-bottom: 0
                        }

                        .ft-gallery-popup .mfp-container:before {
                            display: none
                        }

                        .fts-popup-image-position {
                            min-height: 50px !important
                        }

                        .ft-gallery-popup .fts-popup-second-half .mfp-bottom-bar {
                            height: auto !important;
                            overflow: visible !important;
                            min-height: auto !important
                        }
                    }

                    <?php
                } elseif ( 'full-width-photo-only' === $option['ft_popup_display_options'] ) {
                    ?>
                    @media (min-width: 0px) {
                        .ft-gallery-popup .fts-popup-half {
                            background: #000 !important;
                            text-align: center !important;
                            vertical-align: middle !important;
                            z-index: 500 !important;
                            width: 100% !important;
                        }

                        .ft-gallery-popup .mfp-container:before {
                            display: inline-block;
                        }

                        .ft-gallery-popup .fts-popup-second-half {
                            height: 100%;
                            width: 100%;
                            position: relative;
                            float: left;
                        }

                        .ft-gallery-popup .mfp-container {
                            padding-top: 40px;
                            padding-bottom: 0;
                        }

                        .ft-gallery-popup .fts-popup-second-half .mfp-bottom-bar {
                            height: auto !important;
                            overflow: visible !important;
                            min-height: auto !important;
                        }

                        .ft-gallery-popup .mfp-bottom-bar {
                            background: #FFF;
                            padding-bottom: 10px;
                        }

                        .ft-gallery-popup .fts-popup-second-half {
                            display: none !important;
                        }
                    }

                    <?php } ?>
                </style>
                <?php
            }
        }  // is ajaxing, for loadmore button.
        $feed_type = 'wp_gallery';

        // Color Options for Album to Gallery Text Link.
        if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] && ! isset( $_GET['load_more_ajaxing'] ) || isset( $_GET['ftg-tags'], $_GET['type'] ) && 'page' === $_GET['type'] ) {
            $ft_album_position_text_check = $option['ft_album_position_text'] ? esc_html( $option['ft_album_position_text'] ) : '';
            if ( 'top' === $ft_album_position_text_check ) {
                $ft_album_position_text = $option['ft_album_position_text'] ? '.ft-album-contents{top:0}' : '';

            } elseif ( 'bottom' === $ft_album_position_text_check ) {
                $ft_album_position_text = $option['ft_album_position_text'] ? '.ft-album-contents{bottom:0}' : '';

            } elseif ( 'middle' === $ft_album_position_text_check ) {
                $ft_album_position_text = $option['ft_album_position_text'] ? '.ft-album-contents{height:100%}' : '';
            }

            $ft_album_link_padding          = $option['ft_album_link_padding'] ? '.ft-album-contents{padding:' . $option['ft_album_link_padding'] . '!Important}' : '';
            $ft_album_link_size             = $option['ft_album_link_size'] ? '.ft-album-contents a{font-size:' . $option['ft_album_link_size'] . '!Important}' : '';
            $ft_album_link_color            = $option['ft_album_link_color'] ? '.ft-album-contents a{color:' . $option['ft_album_link_color'] . '!Important}' : '';
            $ft_album_link_hover_color      = $option['ft_album_link_hover_color'] ? '.ft-album-contents a:hover, .ft-album-contents:hover .ft-view-photo, .fts-mashup-image-and-video-wrap:hover a.ft-view-photo, .fts-feed-type-wp_gallery:hover a.ft-view-photo {color:' . $option['ft_album_link_hover_color'] . '!Important}' : '';
            $ft_album_text_weight           = $option['ft_album_text_weight'] ? '.ft-album-contents a{font-weight:' . $option['ft_album_text_weight'] . '!Important}' : '';
            $ft_album_align_text            = $option['ft_album_align_text'] ? '.ft-album-contents{text-align:' . $option['ft_album_align_text'] . '!Important}' : '';
            $ft_album_link_background_color = $option['ft_album_link_background_color'] ? '.ft-album-contents-backround{background:' . $option['ft_album_link_background_color'] . '!Important}' : '';
            $ft_album_background_opacity    = $option['ft_album_background_opacity'] ? '.ft-album-contents-backround{opacity:' . $option['ft_album_background_opacity'] . '!Important}' : '';
            if ( ! empty( $ft_album_link_padding ) || ! empty( $ft_album_link_size ) || ! empty( $ft_album_link_color ) || ! empty( $ft_album_link_hover_color ) || ! empty( $ft_album_align_text ) || ! empty( $ft_album_link_background_color ) || ! empty( $ft_album_background_opacity ) ) {
                print '<style>' . esc_html( $ft_album_position_text . $ft_album_link_padding . $ft_album_link_size . $ft_album_link_color . $ft_album_link_hover_color . $ft_album_text_weight . $ft_album_align_text . $ft_album_link_background_color . $ft_album_background_opacity ) . '</style>';
            }
        }

        // format types are: post, post-in-grid, gallery.
        $format_type = isset( $option['fts_type'] ) ? esc_html( $option['fts_type'] ) : '';

        $my_request = stripslashes_deep( $_REQUEST );
        // Make sure it's not ajaxing.
        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
            $my_request['fts_dynamic_name'] = trim( $this->fts_rand_string( 10 ) );
            // Create Dynamic Class Name.
            $fts_dynamic_class_name = '';
            if ( isset( $my_request['fts_dynamic_name'] ) ) {
                $fts_dynamic_class_name = 'feed_dynamic_class_' . sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) );
            }
        }
        $fts_dynamic_string = trim( $this->fts_rand_string( 10 ) );

        if ( ! empty( $option['fts_load_more_option'] ) && 'yes' === $option['fts_load_more_option'] ) {

            $post_count = null !== $option['fts_photo_count'] ? $option['fts_photo_count'] : '999';

        } elseif ( ! empty( $option['fts_show_true_pagination'] ) && 'yes' === $option['fts_show_true_pagination'] ) {
            $post_count = null !== $option['fts_pagination_photo_count'] ? $option['fts_pagination_photo_count'] : '999';
        } else {
            $post_count = '9999';
        }

        $scroll_more = isset( $option['fts_load_more_style'] ) ? $option['fts_load_more_style'] : '';

        $loadmore_btn_maxwidth = isset( $option['fts_loadmore_button_width'] ) ? $option['fts_loadmore_button_width'] : '';
        $loadmore_btn_margin   = isset( $option['fts_loadmore_button_margin'] ) ? $option['fts_loadmore_button_margin'] : '';

        $loadmore_btn_maxwidth = isset( $loadmore_btn_maxwidth ) ? $loadmore_btn_maxwidth : '350px';
        $loadmore_btn_margin   = isset( $loadmore_btn_margin ) ? $loadmore_btn_margin : '10px';

        $pagination_check = isset( $option['fts_show_pagination'] ) ? $option['fts_show_pagination'] : '';

        $pagination = isset( $pagination_check ) ? esc_html( $pagination_check ) : 'yes';

        // this is the image size in written format,ie* thumbnail, medium, large etc.
        $title_description           = isset( $option['fts_photo_caption'] ) ? $option['fts_photo_caption'] : '';
        $title_description_placement = isset( $option['fts_photo_caption_placement'] ) ? $option['fts_photo_caption_placement'] : 'show_top';
        $stack_animation             = 'no';
        $feed_name_rand_string       = 'fts_' . $this->fts_rand_string( 10 );
        $padding                     = isset( $option['fts_padding'] ) ? $option['fts_padding'] : '';
        if ( 'post' === $option['fts_type'] || 'gallery' === $option['fts_type'] ) {
            $height = $option['fts_height'];
        } else {
            $height = '';
        }
        $mashup_margin               = 'auto';
        $center_container            = 'yes';
        $wrapper_margin              = isset( $option['fts_margin'] ) ? $option['fts_margin'] : '';
        $space_between_photos        = isset( $option['fts_grid_space_between_posts'] ) ? $option['fts_grid_space_between_posts'] : '';
        $background_color            = isset( $option['fts_feed_background_color'] ) ? $option['fts_feed_background_color'] : '';
        $border_bottom_color         = isset( $option['fts_border_bottom_color'] ) ? $option['fts_border_bottom_color'] : '';
        $background_color_grid_posts = isset( $option['fts_grid_posts_background_color'] ) ? $option['fts_grid_posts_background_color'] : '';

        $image_size = isset( $option['fts_max_image_vid_width'] ) ? 'max-width:' . esc_html( $option['fts_max_image_vid_width'] ) . '' : '';

        $fts_columns_masonry2 = null !== $option['fts_columns_masonry2'] ? esc_html( $option['fts_columns_masonry2'] ) : '';

        if ( empty( $fts_columns_masonry2 ) ) {
            $masonry_class = 'ftg-masonry-3-column';
        } elseif ( '2' === $fts_columns_masonry2 ) {
            $masonry_class = 'ftg-masonry-2-column';
        } elseif ( '3' === $fts_columns_masonry2 ) {
            $masonry_class = 'ftg-masonry-3-column';
        } elseif ( '4' === $fts_columns_masonry2 ) {
            $masonry_class = 'ftg-masonry-4-column';
        } elseif ( '5' === $fts_columns_masonry2 ) {
            $masonry_class = 'ftg-masonry-5-column';
        } else {
            // leaving this else for people who may already have had a size set, however when they resave on the page it will convert to the new method.
            // I'm forcing this because in this time of mobile devices allowing people to set a size just ruins that experience. and creates more support.
            $grid_width = isset( $option['fts_grid_column_width'] ) ? $option['fts_grid_column_width'] : '';
        }

        $fts_columns_masonry_margin = null !== $option['fts_columns_masonry_margin'] ? $option['fts_columns_masonry_margin'] : '';
        // we leave a space before each class to separate it from the class ftg-masonry above.
        if ( empty( $fts_columns_masonry_margin ) ) {
            $masonry_margin = ' ftg-masonry-5px-margin';
        } elseif ( '1' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-1px-margin';
        } elseif ( '2' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-2px-margin';
        } elseif ( '3' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-3px-margin';
        } elseif ( '4' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-4px-margin';
        } elseif ( '5' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-5px-margin';
        } elseif ( '10' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-10px-margin';
        } elseif ( '15' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-15px-margin';
        } elseif ( '20' === $fts_columns_masonry_margin ) {
            $masonry_margin = ' ftg-masonry-20px-margin';
        }

        $feed_width = isset( $option['fts_width'] ) ? $option['fts_width'] : '';

        // We use this to activate the watermark options, so they are turned off by default.
        $fts_watermark_enable_options = isset( $option['fts_watermark_enable_options'] ) ? $option['fts_watermark_enable_options'] : '';

        // Watermark Type.
        // 2 options: Watermark Overlay Image (Does not Imprint logo on Image) = overlay / Watermark Image (Imprint logo on the selected image sizes) = imprint.
        $watermark = isset( $option['fts_watermark'] ) ? $option['fts_watermark'] : '';

        // 3 options: watermark in popup = popup-only / Watermark for image on page and popup = page-and-popup / Watermark for image on page = page-only.
        $watermark_overlay_enable = isset( $option['fts_watermark_overlay_enable'] ) ? $option['fts_watermark_overlay_enable'] : '';

        $watermark_image_position      = isset( $option['fts_position'] ) ? $option['fts_position'] : '';
        $watermark_image_margin        = isset( $option['ft_watermark_image_margin'] ) ? $option['ft_watermark_image_margin'] : '';
        $watermark_image_url           = isset( $option['ft_watermark_image_input'] ) ? $option['ft_watermark_image_input'] : '';
        $watermark_image_opacity       = isset( $option['ft_watermark_image_opacity'] ) ? $option['ft_watermark_image_opacity'] : '';
        $watermark_right_click_disable = isset( $option['fts_watermark_disable_right_click'] ) ? $option['fts_watermark_disable_right_click'] : '';

        // Option to disable the right click to inspect element on page so people can't just steal image easily.
        if ( isset( $watermark_right_click_disable ) && 'yes' === $watermark_right_click_disable ) {
            ?>
            <script>
                jQuery(document).bind("contextmenu", function (event) {
                    event.preventDefault();
                });
                // window.ondragstart = function() { return false; }
                jQuery(document).ready(function () {
                    jQuery('img').on('dragstart', function (event) {
                        event.preventDefault();
                    });
                });
            </script>
            <?php
        }

        $edit_url = get_admin_url() . 'post.php?post=' . $ftg['id'] . '&action=edit';
        $popup    = isset( $option['fts_popup'] ) ? $option['fts_popup'] : '';

        if ( isset( $popup ) && 'yes' === $popup ) {

            // it's ok if these styles & scripts load at the bottom of the page.
            $fts_fix_magnific = get_option( 'fts_fix_magnific' ) ? get_option( 'fts_fix_magnific' ) : '';
            if ( isset( $fts_fix_magnific ) && '1' !== $fts_fix_magnific ) {
                wp_enqueue_style( 'ft-gallery-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION );
            }
            if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                // run our magnific popup.js in fts instead of double loading.
                wp_enqueue_script( 'ft-gallery-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION, true );
            }
            // here is the click function for our custom popup.
            wp_enqueue_script( 'ft-gallery-popup-click-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup-click.js' ), array(), FTS_CURRENT_VERSION, true );
        }

        $hide_icon                              = isset( $option['fts_wp_icon'] ) ? $option['fts_wp_icon'] : '';
        $hide_date                              = isset( $option['fts_wp_date'] ) ? $option['fts_wp_date'] : '';
        $show_share                             = isset( $option['fts_wp_share'] ) ? $option['fts_wp_share'] : '';
        $show_purchase_link                     = isset( $option['fts_purchase_link'] ) ? $option['fts_purchase_link'] : '';
        $hide_add_to_cart                       = isset( $option['fts_hide_add_to_cart'] ) ? $option['fts_hide_add_to_cart'] : 'no';
        $fts_show_add_to_cart_over_image = isset( $option['fts_show_add_to_cart_over_image'] ) ? $option['fts_show_add_to_cart_over_image'] : '';
        $respnsive_gallery_cart_position        = isset( $option['fts_position_add_to_cart_over_image'] ) ? $option['fts_position_add_to_cart_over_image'] : 'bottom-right';

        if ( isset( $option['fts_purchase_word'] ) && null !== $option['fts_purchase_word'] ) {
            $purchase_text = isset( $option['fts_purchase_word'] ) ? $option['fts_purchase_word'] : '';
        } else {
            $purchase_text = 'Purchase';
        }

        $username      = isset( $option['fts_username'] ) && null !== $option['fts_username'] ? $option['fts_username'] : '';
        $username_link = isset( $option['fts_user_link'] ) && null !== $option['fts_user_link'] ? $option['fts_user_link'] : 'javacript:;';
        // link target options are: _blank, _self.
        $link_target = '_blank';
        ob_start();
        if ( ! isset( $_GET['load_more_ajaxing'] ) && isset( $title ) ) {
            ?>
            <div class="ft-gallery-main-title"><?php print esc_html( $title ); ?></div>
            <?php
        }

        if ( ! isset( $_GET['load_more_ajaxing'] ) && isset( $option['fts_show_page_tags'] ) && 'above_images' === $option['fts_show_page_tags'] && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
            $ftg_tags = new image_and_gallery_tags_class();
            echo wp_kses(
                $ftg_tags->fts_tags( $ftg['id'], null, 'page' ),
                array(
                    'a'    => array(
                        'href'  => array(),
                        'title' => array(),
                    ),
                    'span' => array(
                        'class' => array(),
                        'title' => array(),
                    ),
                    'div'  => array(
                        'class' => array(),
                        'title' => array(),
                    ),
                )
            );
        }

        $ftg_sorting_options               = null !== $option['ftg_sorting_options'] && 'yes' === $option['ftg_sorting_options'] ? $option['ftg_sorting_options'] : '';
        $fts_pagination_photo_count = null !== $option['fts_pagination_photo_count'] ? $option['fts_pagination_photo_count'] : '50';
        $ftg_loadmore_option               = null !== $option['fts_load_more_option'] && 'yes' === $option['fts_load_more_option'] ? $option['fts_load_more_option'] : '';
        $ftg_photo_count                   = null !== $option['fts_photo_count'] ? $option['fts_photo_count'] : '50';
        $fts_show_true_pagination   = null !== $option['fts_show_true_pagination'] && 'yes' === $option['fts_show_true_pagination'] ? $option['fts_show_true_pagination'] : '';

        if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] || isset( $_GET['ftg-tags'] ) && 'page' === $_GET['type'] ) {

            $orderby_set = '' !== $option['ftg_sort_type'] ? $option['ftg_sort_type'] : 'date';
            $orderby     = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : $orderby_set;
            if ( isset( $_GET['orderby'] ) && 'menu_order' === $_GET['orderby'] || 'menu_order' === $orderby_set && empty( $_GET['orderby'] ) || 'title' === $orderby_set && empty( $_GET['orderby'] ) || isset( $_GET['orderby'] ) && 'title' === $_GET['orderby'] ) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }

            $count_per_page = $post_count;
            if ( 'yes' === $fts_show_true_pagination || ! empty( $_GET['ftg-tags'] ) ) {
                $paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
                // After that, calculate the offset.
                $offset = ( $paged - 1 ) * $count_per_page;
            } else {
                // This is for the loadmore button, so we use the paged option instead of the offset option which we can't use in this method.
                $paged  = $ftg['offset'];
                $offset = '';
            }
            // A list of ids that are in this album so we can display them.
            $image_list_check = $album_gallery_ids;

            if ( ! empty( $_GET['ftg-tags'] ) ) {

                $image_list = get_posts(
                    array(
                        'posts_per_page' => esc_html( $count_per_page ),
                        'post_type'      => 'fts',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'ftg-tags',
                                'field'    => 'slug',
                                'terms'    => array( sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) ),
                                'operator' => 'IN',
                            ),
                        ),
                        'orderby'        => esc_html( $orderby ),
                        'order'          => esc_html( $order ),
                        'paged'          => esc_html( $paged ),
                        'offset'         => $offset,
                    )
                );

                // For Albums: we run this a second time so we can count all the posts and pass the value to the pagination for tags only.
                // the reason being is we are unsure of a way to tell the difference between page tags and image tags.
                $ftg_gallery_count_for_tags = get_posts(
                    array(
                        'posts_per_page' => '-1',
                        'post_type'      => 'fts',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'ftg-tags',
                                'field'    => 'slug',
                                'terms'    => array( sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) ),
                                'operator' => 'IN',
                            ),
                        ),
                    )
                );
                $toal_count_for_tags        = isset( $ftg_gallery_count_for_tags ) ? count( $ftg_gallery_count_for_tags ) : '';

            } else {
                $image_list = get_posts(
                    array(
                        'posts_per_page' => esc_html( $count_per_page ),
                        'post__in'       => $this->albums_gallery_list_of_ids( $image_list_check ),
                        'post_type'      => 'fts',
                        'orderby'        => esc_html( $orderby ),
                        'order'          => esc_html( $order ),
                        'paged'          => esc_html( $paged ),
                        'offset'         => $offset,
                    )
                );
            }

            // $image_list = get_posts('numberposts=2&include=559,117,129&post_type=fts');
            // Return test
            // $getpost_attr['post_type'] = 'fts'; $getpost_attr['include'] = $this->albums_gallery_list_of_ids($image_list_check);
            // $getpost_attr['posts_per_page'] = esc_html( $post_count ); $getpost_attr['orderby'] = esc_html( $orderby );
            // $getpost_attr['order'] = esc_html( $order );
            // $getpost_attr['paged'] = esc_html( $paged );
            // echo '<pre>';
            // print_r($getpost_attr);
            // echo '</pre>';
            // echo '<pre>';
            // print_r($image_list);
            // echo '</pre>'; .
        } else {




            $orderby_set = null !== $option['ftg_sort_type'] ? $option['ftg_sort_type'] : 'menu_order';
            $orderby     = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : $orderby_set;
            if ( isset( $_GET['orderby'] ) && 'menu_order' === $_GET['orderby'] || 'menu_order' === $orderby_set && empty( $_GET['orderby'] ) || 'title' === $orderby_set && empty( $_GET['orderby'] ) || isset( $_GET['orderby'] ) && 'title' === $_GET['orderby'] ) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }

            if ( 'yes' === $fts_show_true_pagination ) {
                $paged  = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
                $offset = ( $paged - 1 ) * $post_count;
            } else {
                $paged  = $ftg['offset'];
                $offset = '';
            }

            if ( ! empty( $_GET['ftg-tags'] ) ) {

                // echo '<pre>';
                // print_r($image_list);
                // echo '</pre>'; .
                $image_list = get_posts(
                    array(
                        'post_type'      => 'attachment',
                        'post_mime_type' => 'image',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'ftg-tags',
                                'field'    => 'slug',
                                'terms'    => array( sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) ),
                                'operator' => 'IN',
                            ),
                        ),
                        'posts_per_page' => esc_html( $post_count ),
                        'orderby'        => esc_html( $orderby ),
                        'order'          => esc_html( $order ),
                        'offset'         => $offset,
                    )
                );

                // we run this a second time so we can count all the posts and pass the value to the pagination for tags only.
                // the reason being is we are unsure of a way to tell the difference between page tags and image tags.
                $ftg_image_count_for_tags = get_posts(
                    array(
                        'post_type'      => 'attachment',
                        'post_mime_type' => 'image',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'ftg-tags',
                                'field'    => 'slug',
                                'terms'    => array( sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) ),
                                'operator' => 'IN',
                            ),
                        ),
                        'posts_per_page' => '-1',
                    )
                );
                $toal_count_for_tags      = isset( $ftg_image_count_for_tags ) ? count( $ftg_image_count_for_tags ) : '';

            } else {
                $image_list = get_posts(
                    array(
                        'post_parent'    => $ftg['id'],
                        'post_type'      => 'attachment',
                        'post_mime_type' => 'image',
                        'posts_per_page' => esc_html( $post_count ),
                        'orderby'        => esc_html( $orderby ),
                        'order'          => esc_html( $order ),
                        'paged'          => esc_html( $paged ),
                        'offset'         => $offset,
                    )
                );
            }
        }
        //  echo '<pre>';
        // print_r($image_list));
        //  echo '</pre>';

        // This is related to the tags search select option.
        // Since we do not seperate the tags between pages and images we use count to see if the numbers are off
        // if the numbers are off then we show a message suggesting they view the gallery tag instead. Same with the second statement below.
        if( isset( $_GET['ftg-tags'], $_GET['count'] ) && $_GET['count'] > $toal_count_for_tags ) {

            if('image' === $_GET['type']){
                $page_type = 'Galleries';
                $url_page_type = 'page';
            }
            else {
                $page_type = 'Images';
                $url_page_type = 'image';
            }
            echo '<div class="ftg-no-image-tag-wrap">';

            $the_count = isset( $_GET['count'] ) ? '&count=' . $_GET['count'] : '';

            echo sprintf(esc_html__('To also view %4$s tagged with %3$s %1$sclick here%2$s.', 'feed_them_social'),
                '<a href="'.esc_url( get_site_url() .'?type='. esc_attr( $url_page_type ) .'&ftg-tags='.sanitize_text_field( wp_unslash( $_GET['ftg-tags'] . $the_count ) ) ).'">',
                '</a>',
                sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ),
                $page_type
            );
            echo '</div>';
        }
        // This is also related to the tags search select option.
        // So if no image tags are found in our search, since we are assuming more people might be tagging image than galleries, we show a message
        // to let the user know they can click the link to search for that tag in the gallery search of our page instead of the image search
        if( is_array( $image_list ) && !isset( $image_list[0] ) && isset( $_GET['type'] ) && 'image' === $_GET['type'] ) {
            echo '<div class="ftg-no-image-tag-wrap">';
            echo sprintf(esc_html__('No Image tags found. To view the Gallery tag of %3$s instead %1$sclick here%2$s.', 'feed_them_social'),
                '<a href="'.esc_url( get_site_url() .'?type=page&ftg-tags='.sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) ).'">',
                '</a>',
                sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) )
            );
            echo '</div>';
        }

        if(is_array( $image_list ) && isset( $image_list[0] )){
            // echo '<pre>';
            // print_r(count($image_list));
            // echo '</pre>'; .
            if ( 'yes' === $ftg_loadmore_option ) {
                $post_count = $ftg_photo_count;
            } elseif ( 'yes' === $fts_show_true_pagination ) {
                $post_count = $fts_pagination_photo_count;
            } else {
                $post_count = '9999';
            }
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                $tags      = isset( $_GET['ftg-tags'] ) ? 'yes' : 'no';
                $tags_list = isset( $_GET['ftg-tags'] ) ? sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) : '';

                // Opening Div for header pagination.
                if ( 'yes' === $ftg_sorting_options || 'yes' === $fts_show_true_pagination ) {
                    print '<div class="ftg-pagination-header">';
                }
                if ( 'yes' === $ftg_sorting_options ) {
                    $fts_position_of_pagination = null !== $option['ftg_position_of_sort_select'] ? $option['ftg_position_of_sort_select'] : '';
                    if ( 'above-below' === $fts_position_of_pagination || 'above' === $fts_position_of_pagination ) {
                        $this->ftg_sort_order_select( $ftg['id'] );
                    }
                }

                if ( 'yes' === $fts_show_true_pagination ) {
                    $fts_position_of_pagination = null !== $option['fts_position_of_pagination'] ? $option['fts_position_of_pagination'] : '';
                    if ( 'above-below' === $fts_position_of_pagination || 'above' === $fts_position_of_pagination ) {

                        $count_for_tags_final = isset( $toal_count_for_tags ) ? $toal_count_for_tags : '';
                        $this->ftg_pagination( $ftg['id'], $ftg['is_album'], $tags, $tags_list, $count_for_tags_final );
                    }
                }
                // End closing Div for header pagination.
                if ( 'yes' === $ftg_sorting_options || 'yes' === $fts_show_true_pagination ) {
                    print '</div><div class="ftg-clear"></div>';
                }
            }

            // echo do_shortcode( '[ft-gallery-specific-cats-menu gallery_id='.$ftg['id'].' menu_title="Specific Gallery Categories"]' );.
            $fts_loadmore_background_color = isset( $option['fts_loadmore_background_color'] ) ? $option['fts_loadmore_background_color'] : '';
            $fts_loadmore_text_color       = isset( $option['fts_loadmore_text_color'] ) ? $option['fts_loadmore_text_color'] : '';
            $fts_pagination_text_color     = isset( $option['fts_loadmore_count_text_color'] ) ? $option['fts_loadmore_count_text_color'] : '';
            $feed_width                           = isset( $feed_width ) && '' !== $feed_width ? 'max-width:' . $feed_width . ';' : '';
            $mashup_margin                        = isset( $mashup_margin ) && '' !== $mashup_margin ? 'margin:' . $mashup_margin . ';' : '';
            $height                               = isset( $height ) && '' !== $height ? 'height:' . $height . ';overflow:auto;' : '';
            $padding                              = isset( $padding ) && '' !== $padding ? 'padding:' . $padding . ';' : '';
            $background_color                     = isset( $background_color ) && '' !== $background_color ? 'background:' . $background_color . ';' : '';
            $background_color_grid_posts          = isset( $background_color_grid_posts ) && '' !== $background_color_grid_posts ? 'background:' . $background_color_grid_posts . ';' : '';

            $border_bottom_color = isset( $border_bottom_color ) && '' !== $border_bottom_color ? 'border-bottom:1px solid ' . $border_bottom_color . ';' : '';

            if ( '' !== $feed_width || '' !== $mashup_margin || '' !== $height || '' !== $padding || '' !== $background_color ) {
                $style_start = 'style="';
                $style_end   = '"';
            } else {
                $style_start = '';
                $style_end   = '';
            }

            $fts_powered_text_options_settings = get_option( 'ft-gallery-powered-text-options-settings' );

            if ( '1' === $fts_powered_text_options_settings ) {
                ?>
                <script>jQuery('body').addClass('ft-gallery-powered-by-hide');</script>
                <?php
            }
            // Make sure it's not ajaxing.
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // We have 3 wrapper options at the moment post, post-in-grid, gallery and gallery-collage.
                if ( 'post-in-grid' === $format_type || 'gallery-collage' === $format_type ) {
                    print '<div class="ft-wp-gallery ft-wp-gallery-masonry popup-gallery-fb-posts ' . esc_attr( $feed_name_rand_string ) . ' ' . esc_attr( $fts_dynamic_class_name ) . ' masonry js-masonry"';
                    if ( isset( $center_container ) && 'yes' === $center_container ) {
                        print 'data-masonry-options=\'{"itemSelector": ".ft-gallery-post-wrap", "isFitWidth": ' . ( 'no' === $center_container ? 'false' : 'true' ) . ' ' . ( 'no' === $stack_animation ? ', "transitionDuration": 0' : '' ) . '}\' style="margin:auto;' . esc_attr( $background_color ) . '"';
                    }
                    print '>';
                } elseif ( 'gallery' === $format_type ) {
                    $scrollable = isset( $scroll_more ) && 'autoscroll' === $scroll_more ? $feed_name_rand_string . '-scrollable ft-wp-gallery-scrollable' : '';

                    $fts_columns       = $option['fts_columns'] ? $option['fts_columns'] : '5';
                    $fts_force_columns = $option['fts_force_columns'] ? $option['fts_force_columns'] : 'yes';

                    print '<div data-ftg-columns="'.esc_attr( $fts_columns ).'" data-ftg-force-columns="' . esc_attr( $fts_force_columns ) . '" data-ftg-margin=' . esc_attr( $space_between_photos ) . ' class="fts-mashup ft-wp-gallery-centered ft-wp-gallery popup-gallery-fb-posts ' . esc_attr( $feed_name_rand_string ) . ' ' . esc_attr( $scrollable ) . '" ' . $style_start . esc_attr( $feed_width . $mashup_margin . $height . $padding . $background_color ) . $style_end . '>';
                    print '<div class="' . esc_attr( $fts_dynamic_class_name ) . '">';

                } elseif ( 'post' === $format_type ) {
                    print '<div class="fts-mashup ft-wp-gallery popup-gallery-fb-posts ' . esc_attr( $feed_name_rand_string ) . ' ' . esc_attr( $fts_dynamic_class_name ) . '" ' . $style_start . esc_attr( $feed_width . $mashup_margin . $height . $padding . $background_color ) . $style_end . '>';
                }
            }


            if ( is_array( $image_list ) && isset( $image_list[0] ) ) {

                if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                    $gallery_to_woo       = new Gallery_to_Woocommerce();
                    $siteurl              = get_option( 'siteurl' );
                    $purchase_link        = get_option( 'fts_woo_add_to_cart' );
                    $purchase_link_option = isset( $purchase_link['fts_woo_options'] ) ? $purchase_link['fts_woo_options'] : '';
                }

                $fts_load_more_option = $option['fts_load_more_option'];

                // Make sure it's not ajaxing.
                if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $my_request['fts_no_more_posts'] ) ) {
                    $ftg['offset'] = '0';
                }

                $ftgp_current_version = defined( 'FTGP_CURRENT_VERSION' ) ? FTGP_CURRENT_VERSION : '';

                if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && $ftgp_current_version > '1.0.5' ) {
                    $albums_class = new Albums();
                }

                foreach ( $image_list as $key => $image ) {

                    $date = isset( $image->post_date ) ? $image->post_date : '';

                    if ( $ftgp_current_version > '1.0.5' && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] || $ftgp_current_version > '1.0.5' && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && isset( $_GET['ftg-tags'] ) && 'page' === $_GET['type'] ) {

                        $gallery_id = $image->ID;

                        $gallery_post_link         = get_post_permalink( $gallery_id );
                        $gallery_attachments_count = $albums_class->fts_count_gallery_attachments( $gallery_id );
                        $gallery_title             = get_the_title( $gallery_id );

                        $attached_media = $gallery_class->fts_get_gallery_attached_media_ids( $image->ID, 'image' );
                        $featured       = $albums_class->gallery_featured_first( $image->ID, true );
                        if ( isset( $featured ) ) {
                            // This was being used for the image source for albums but using this makes it so you can't choose the image size.
                            // So for now this is not being used anyway but going to leave it because we may find a use for the featured image at some point.
                            $featured_image = $featured;
                            // print_r($featured);
                        }

                        $image = wp_prepare_attachment_for_js( $attached_media[0] );

                    } else {

                        // very interesting function that returns some detailed info.
                        // found @: https://wordpress.org/ideas/topic/functions-to-get-an-attachments-caption-title-alt-description.
                        $image = wp_prepare_attachment_for_js( $image->ID );
                    }

                    $description = make_clickable( $image['description'] );
                    $img_title   = $image['title'];

                    $image_description = isset( $description ) ? $description : '';

                    // Social media sharing URLs.

                    $parse = parse_url(get_permalink());

                    // need to fix this so the pagination number is in the url, we need this for the new share image url option
                    // also need to only make this hash tag view image option only for the image url for the input in the share wrapper.
                    // right now it's tied to all the urls. So the social media buttons will only link to the gallery because social media
                    // companies parse out he hashtag in the url so people will have to manually share until we find a better solution.
                    $link                      = $parse['scheme'] .'://'. $parse['host'] . $_SERVER['REQUEST_URI'] . '#ftg-image-' . esc_attr( $key + 1 );

                   // echo 'asdfsadf'. $link;

                    $fts_share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $link;
                    $fts_share_twitter  = 'https://twitter.com/intent/tweet?text=' . $link;
                    $fts_share_google   = 'https://plus.google.com/share?url=' . $link;
                    $fts_share_linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link . '&title=' . wp_strip_all_tags( $image_description );
                    $fts_share_email    = 'mailto:?subject=Shared Link&body=' . $link . ' - ' . wp_strip_all_tags( $image_description );

                    $ftg_final_date = $this->fts_custom_date( $date, $feed_type );

                    // date_i18n( get_option( 'date_format' ), strtotime( '11/15-1976' ) );.
                    // All text for img(s) on the page, this does not apply to image background gallery types.
                    $fts_alt_text = null !== $image['alt'] ? $image['alt'] : $img_title;

                    // The size of the image in the popup.
                    $image_size_name = $option['fts_images_sizes_popup'];
                    // this is the image size in written format,ie* thumbnail, medium, large etc.
                    $item_popup       = explode( ' ', $image_size_name );
                    $item_final_popup = wp_get_attachment_image_src( $image['id'], $item_popup[0], false );

                    // The size of the image on the page (some people might not want the full source on the page because that is a lot of weight so we let them choose).
                    $image_size_page = $option['fts_images_sizes_page'];
                    // this is the image size in written format,ie* thumbnail, medium, large etc.
                    $item_page       = explode( ' ', $image_size_page );
                    $item_final_page = wp_get_attachment_image_src( $image['id'], $item_page[0], false );

                    $image_source_full = wp_get_attachment_image_src( $image['id'], 'full', false );

                    $image_source_large        = wp_get_attachment_image_src( $image['id'], 'large', false );
                    $image_source_medium_large = wp_get_attachment_image_src( $image['id'], 'medium_large', false );
                    $image_source_medium       = wp_get_attachment_image_src( $image['id'], 'medium', false );

                    if ( isset( $image_size_page ) && 'Choose an option' !== $image_size_page ) {
                        $image_source_page = $item_final_page[0];
                    } elseif ( isset( $image_size_page, $image_source_large ) ) {
                        $image_source_page = $image_source_large[0];
                    } elseif ( isset( $image_size_page, $image_source_medium_large ) ) {
                        $image_source_page = $image_source_medium_large[0];
                    } elseif ( isset( $image_size_page, $image_source_medium ) ) {
                        $image_source_page = $image_source_medium[0];
                    } else {
                        $image_source_page = '';
                    }
                    if ( isset( $popup ) && 'yes' === $popup ) {
                        if ( isset( $image_size_name ) && 'Choose an option' !== $image_size_name ) {
                            $image_source_popup = $item_final_popup[0];
                        } elseif ( isset( $image_size_name, $image_source_large ) ) {
                            $image_source_popup = $image_source_large[0];
                        } elseif ( isset( $image_size_name, $image_source_medium_large ) ) {
                            $image_source_popup = $image_source_medium_large[0];
                        } elseif ( isset( $image_size_name, $image_source_medium ) ) {
                            $image_source_popup = $image_source_medium[0];
                        } else {
                            $image_source_popup = '';
                        }
                    }

                  //  echo '<pre>';
                  //  print_r($item_final_popup);
                  //  echo '</pre>';

                    if ( isset( $ftg['is_album'], $featured_image ) && 'yes' === $ftg['is_album'] || isset( $_GET['ftg-tags'], $_GET['type'] ) && 'page' === $_GET['type'] ) {
                        $image_source_page  = $image_source_page;
                        $image_source_popup = $gallery_post_link;
                    }

                    if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                        // Check custom post meta for woo product field.
                        $product_id = get_post_meta( $image['id'], 'fts_woo_prod', true );
                    }

                    // Regular Post Format.
                    if ( 'post' === $format_type || 'post-in-grid' === $format_type || 'gallery-collage' === $format_type ) {
                        ?>
                        <div class="ft-gallery-post-wrap fts-feed-type-wp_gallery ft-post-format
                        <?php
                        if ( 'post' !== $format_type ) {
                            echo esc_attr( $masonry_class . $masonry_margin );
                        }
                        if ( 'gallery-collage' === $format_type ) {
                            ?>
                             ft-gallery-collage<?php } ?> ft-gallery-<?php echo esc_attr( $fts_dynamic_string ); ?>" style="
                        <?php
                        if ( 'post-in-grid' === $format_type || 'gallery-collage' === $format_type ) {
                            echo isset( $grid_width ) ? 'width:' . esc_attr( $grid_width ) . ';' : '';
                            ?>
                                margin:<?php print esc_attr( $masonry_margin ); ?>;
                            <?php
                        }
                        print esc_attr( $background_color_grid_posts . $padding . $border_bottom_color );
                        ?>
                                ">





                            <?php if ( 'show_top' === $title_description_placement ) { ?>
                                <div class="ft-text-for-popup" style="
                                <?php
                                if ( isset( $hide_icon, $username, $hide_date, $title_description ) && 'no' === $hide_icon && 'none' === $username && 'no' === $hide_date && 'none' === $title_description || 'gallery-collage' === $format_type ) {
                                    ?>
                                        display:none !important;<?php } ?>">
                                    <div class="ft-text-for-popup-content">
                                        <?php if ( isset( $hide_icon ) && 'yes' === $hide_icon && empty( $_GET['ftg-tags'] ) ) { ?>
                                            <div class="ft-gallery-icon-wrap-right fts-mashup-wp_gallery-icon ft-wp-gallery-icon">
                                                <a href="<?php print esc_url( $username_link ); ?>"
                                                   target="<?php print esc_attr( $link_target ); ?>"></a>
                                            </div>
                                        <?php } ?>
                                        <?php if ( isset( $username ) && 'none' !== $username ) { ?>
                                            <span class="ft-gallery-fb-user-name">
                                            <?php
                                            if ( empty( $_GET['ftg-tags'] ) ) {
                                                ?>
                                                <a href="<?php print esc_url( $username_link ); ?>"
                                                   target="<?php print esc_attr( $link_target ); ?>"><?php print esc_html( $username ); ?></a><?php } ?></span>
                                        <?php } ?>
                                        <?php if ( isset( $hide_date ) && 'yes' === $hide_date ) { ?>
                                            <span class="ft-gallery-post-time"><?php print esc_html( $ftg_final_date ); ?></span>
                                        <?php } ?>

                                        <div class="ft-gallery-description-wrap">
                                            <?php if ( 'title' === $title_description || 'title_description' === $title_description ) { ?>
                                                <p><strong class="ftg-title-wrap"><?php print esc_html( $img_title ); ?></strong>
                                                </p><?php } ?><?php if ( 'description' === $title_description || 'title_description' === $title_description ) { ?>
                                                <p>
                                                    <?php
                                                    print wp_kses(
                                                        nl2br( $image_description ),
                                                        array(
                                                            'a'  => array(
                                                                'href' => array(),
                                                                'title' => array(),
                                                            ),
                                                            'br' => array(),
                                                            'em' => array(),
                                                            'strong' => array(),
                                                            'small' => array(),
                                                        )
                                                    );
                                                    ?>
                                                </p>
                                                <?php
                                            }

                                            if ( empty( $ftg['is_album'] ) || isset( $_GET['ftg-tags'] ) && 'page' !== $_GET['type'] ) {
                                                // Image Tags.
                                                if ( 'yes' === $option['fts_show_tags'] && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                                                    $ftg_tags = new image_and_gallery_tags_class();
                                                    echo wp_kses(
                                                        $ftg_tags->fts_tags( $image['id'], $ftg['id'], 'image' ),
                                                        array(
                                                            'a'    => array(
                                                                'href'  => array(),
                                                                'title' => array(),
                                                            ),
                                                            'span' => array(
                                                                'class' => array(),
                                                                'title' => array(),
                                                            ),
                                                            'div'  => array(
                                                                'class' => array(),
                                                                'title' => array(),
                                                            ),
                                                        )
                                                    );
                                                }
                                            }

                                            ?>
                                        </div>


                                    </div>
                                </div>
                            <?php } ?>

                            <div class="fts-mashup-image-and-video-wrap"
                                <?php

                                $popup_not_album_or_tag = isset( $ftg['is_album'] )&& 'yes' === $ftg['is_album'] || isset( $_GET['ftg-tags'] ) && 'page' === $_GET['type'] ? '' : ' ft-gallery-link-popup-click-action';

                                if ( isset( $image_size ) && '' !== $image_size ) {
                                    ?>
                                    style="<?php print esc_attr( $image_size ); ?>"<?php } ?>>

                                <a href="<?php print esc_url( $image_source_popup ); ?>"
                                   title='<?php print esc_attr( $fts_alt_text ); ?>'
                                   data-image_id='<?php echo esc_attr( $key + 1 ); ?>'
                                   class="ft-gallery-link-popup-master<?php print esc_attr( $popup_not_album_or_tag ); ?>"
                                   style="position: relative; overflow: hidden;"><img class="fts-mashup-instagram-photo "
                                                                                      src="<?php print esc_url( $image_source_page ); ?>"
                                                                                      alt="<?php print esc_attr( $fts_alt_text ); ?>">
                                    <?php
                                    if ( isset( $fts_watermark_enable_options, $watermark ) && 'yes' === $fts_watermark_enable_options && 'overlay' === $watermark ) {
                                        ?>
                                        <div class="
                                        <?php
                                        if ( isset( $watermark_overlay_enable ) && 'popup-only' === $watermark_overlay_enable ) {
                                            ?>
                                            ft-image-overlay fts-image-overlay-hide
                                            <?php
                                        } elseif ( isset( $watermark_overlay_enable ) && 'page-and-popup' === $watermark_overlay_enable ) {
                                            ?>
                                            ft-image-overlay<?php } ?>">
                                            <div class="fts-watermark-inside fts-watermark-inside-<?php echo esc_attr( $watermark_image_position ); ?>"
                                                <?php
                                                if ( isset( $watermark_image_opacity ) && null !== $watermark_image_opacity ) {
                                                    ?>
                                                    style="opacity:<?php echo esc_attr( $watermark_image_opacity ); ?>"<?php } ?>>
                                                <img src="<?php print esc_url( $watermark_image_url ); ?>"
                                                    <?php
                                                    if ( isset( $watermark_image_margin ) && null !== $watermark_image_margin ) {
                                                        ?>
                                                        style="margin:<?php echo esc_attr( $watermark_image_margin ); ?>"<?php } ?>
                                                     alt="<?php print esc_attr( $fts_alt_text ); ?>"/>
                                            </div>
                                        </div>
                                    <?php } ?></a>

                                <?php
                                if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] || isset( $_GET['ftg-tags'] ) && 'page' === $_GET['type'] ) {

                                    ?>
                                    <div class="ft-album-contents">
                                        <div class="ft-album-contents-backround"></div>
                                        <div class="ftg-verticle-align"><a href="<?php print esc_url( $gallery_post_link ); ?>"
                                                                           title='<?php print esc_attr( $fts_alt_text ); ?>'
                                                                           class="ft-view-photo">
                                                <?php
                                                echo esc_html( $gallery_title . ' (' . $gallery_attachments_count . ')' );
                                                ?>
                                            </a></div>
                                    </div>
                                <?php } ?>
                            </div>



                            <?php if ( 'show_bottom' === $title_description_placement ) { ?>
                                <div class="ft-text-bottom">
                                    <div class="ft-text-for-popup" style="
                                    <?php
                                    if ( isset( $hide_icon, $username, $hide_date, $title_description ) && 'no' === $hide_icon && 'none' === $username && 'no' === $hide_date && 'none' === $title_description || 'gallery-collage' === $format_type ) {
                                        ?>
                                            display:none !important;<?php } ?>">
                                        <div class="ft-text-for-popup-content">
                                            <?php if ( isset( $hide_icon ) && 'yes' === $hide_icon && empty( $_GET['ftg-tags'] ) ) { ?>
                                                <div class="ft-gallery-icon-wrap-right fts-mashup-wp_gallery-icon ft-wp-gallery-icon">
                                                    <a href="<?php print esc_url( $username_link ); ?>"
                                                       target="<?php print esc_attr( $link_target ); ?>"></a>
                                                </div>
                                            <?php } ?>
                                            <?php if ( isset( $username ) && 'none' !== $username ) { ?>
                                                <span class="ft-gallery-fb-user-name">
                                            <?php
                                            if ( empty( $_GET['ftg-tags'] ) ) {
                                                ?>
                                                <a href="<?php print esc_url( $username_link ); ?>"
                                                   target="<?php print esc_attr( $link_target ); ?>"><?php print esc_html( $username ); ?></a><?php } ?></span>
                                            <?php } ?>
                                            <?php if ( isset( $hide_date ) && 'yes' === $hide_date ) { ?>
                                                <span class="ft-gallery-post-time"><?php print esc_html( $ftg_final_date ); ?></span>
                                            <?php } ?>

                                            <div class="ft-gallery-description-wrap">
                                                <?php if ( 'title' === $title_description || 'title_description' === $title_description ) { ?>
                                                    <p><strong class="ftg-title-wrap"><?php print esc_html( $img_title ); ?></strong>
                                                    </p><?php } ?><?php if ( 'description' === $title_description || 'title_description' === $title_description ) { ?>
                                                    <p>
                                                        <?php
                                                        print wp_kses(
                                                            nl2br( $image_description ),
                                                            array(
                                                                'a'  => array(
                                                                    'href' => array(),
                                                                    'title' => array(),
                                                                ),
                                                                'br' => array(),
                                                                'em' => array(),
                                                                'strong' => array(),
                                                                'small' => array(),
                                                            )
                                                        );
                                                        ?>
                                                    </p>
                                                    <?php
                                                }

                                                if ( empty( $ftg['is_album'] ) || isset( $_GET['ftg-tags'] ) && 'page' !== $_GET['type'] ) {
                                                    // Image Tags.
                                                    if ( 'yes' === $option['fts_show_tags'] && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                                                        $ftg_tags = new image_and_gallery_tags_class();
                                                        echo wp_kses(
                                                            $ftg_tags->fts_tags( $image['id'], $ftg['id'], 'image' ),
                                                            array(
                                                                'a'    => array(
                                                                    'href'  => array(),
                                                                    'title' => array(),
                                                                ),
                                                                'span' => array(
                                                                    'class' => array(),
                                                                    'title' => array(),
                                                                ),
                                                                'div'  => array(
                                                                    'class' => array(),
                                                                    'title' => array(),
                                                                ),
                                                            )
                                                        );
                                                    }
                                                }

                                                ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <?php
                            }

                            $my_get = stripslashes_deep( $_GET );
                            $my_get['type'] = isset( $my_get['type'] ) ? $my_get['type'] : '';
                            if ( isset( $ftg['is_album'] ) && 'yes' !== $ftg['is_album'] && !isset( $my_get['ftg-tags'], $my_get['type'] ) && 'page' !== $my_get['type'] || isset( $my_get['ftg-tags'], $my_get['type'] ) && 'page' !== $my_get['type'] ) {

                                if ( 'yes' !== $hide_add_to_cart ) {

                                    print ' <div class="ftg-varation-for-popup">';
                                    if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && empty( $ftg['is_album'] ) ) {

                                        // Get $product object from product ID.
                                        $this->ftg_variable_add_to_cart( $product_id );
                                    }
                                    print '</div>';

                                }

                                $free_image_size = isset( $option['ftg_free_download_size'] ) ? $option['ftg_free_download_size'] : '';
                                if ( 'yes' === $show_share || is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && 'yes' === $show_purchase_link && empty( $ftg['is_album'] ) ) {
                                    ?>

                                    <div class="fts-mashup-count-wrap">

                                        <?php if ( 'yes' === $show_share ) {
                                            $is_loadmore = 'yes' === $ftg_loadmore_option ? ' ftg-share-loadmore' : '';
                                            ?>
                                            <div class="fts-share-wrap">
                                                <a href="javascript:;" class="ft-gallery-link-popup"></a>
                                                <div class='ft-gallery-share-wrap<?php echo $is_loadmore ?>'>
                                                    <a href='<?php print esc_url( $fts_share_facebook ); ?>'
                                                       target='_blank'
                                                       class='ft-galleryfacebook-icon'><i class='fa fa-facebook-square'></i></a>
                                                    <a href='<?php print esc_url( $fts_share_twitter ); ?>'
                                                       target='_blank'
                                                       class='ft-gallerytwitter-icon'><i class='fa fa-twitter'></i></a>
                                                    <a href='<?php print esc_url( $fts_share_google ); ?>'
                                                       target='_blank'
                                                       class='ft-gallerygoogle-icon'><i class='fa fa-google-plus'></i></a>
                                                    <a href='<?php print esc_url( $fts_share_linkedin ); ?>'
                                                       target='_blank'
                                                       class='ft-gallerylinkedin-icon'><i class='fa fa-linkedin'></i></a>
                                                    <a href='<?php print esc_url( $fts_share_email ); ?>'
                                                       target='_blank'
                                                       class='ft-galleryemail-icon'><i class='fa fa-envelope'></i></a>
                                                    <?php if ( 'yes' !== $ftg_loadmore_option ) { ?>
                                                        <div class="ft-gallery-clear"></div>
                                                        <div class="ftg-share-text"><?php esc_html_e( 'Use link to share image', 'feed_them_social' ) ?></div>
                                                        <div class="ftg-text-copied"><?php esc_html_e( 'Copied to Clipboard', 'feed_them_social' ) ?></div>
                                                        <a href='javascript:;'
                                                           class='ft-gallerylink-icon' onclick="ftgallerycopy('ftg-image-link<?php echo $key + 1 ?>');"><i class='fa fa-link'></i></a> <input id="ftg-image-link<?php echo $key + 1 ?>" onclick="this.select()" value="<?php echo $link ?>">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="ft-gallery-cta-button-wrap">
                                            <?php
                                            if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && 'yes' === $show_purchase_link ) {

                                                // Check to see if we are working with a variable product and if so make the purchase link go to cart.
                                                $product = wc_get_product( $product_id );

                                                $fts_cart_page_name = get_option( 'fts_cart_page_name' ) ? get_option( 'fts_cart_page_name' ) : 'cart';

                                                if ( 'variable' === $product->get_type( 'variable' ) && 'prod_page' !== $purchase_link_option ) {
                                                    $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                                } else {
                                                    if ( 'prod_page' === $purchase_link_option ) {
                                                        $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                                    } elseif ( 'add_cart' === $purchase_link_option ) {
                                                        $purchase_link = '' . $siteurl . '/?add-to-cart=' . $product_id . '';
                                                    } elseif ( 'add_cart_checkout' === $purchase_link_option ) {
                                                        $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name . '/?add-to-cart=' . $product_id . '';
                                                    } elseif ( 'cart_checkout' === $purchase_link_option ) {
                                                        $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                                    } else {
                                                        $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                                    }
                                                }

                                                // If Image already has product meta check the product still exists.
                                                if ( ! empty( $product_id ) && empty( $ftg['is_album'] ) ) {
                                                    $product_exist = $gallery_to_woo->fts_create_woo_prod_exists_check( $product_id );
                                                    if ( $product_exist ) {
                                                        echo '<a class="ft-gallery-buy-now ft-gallery-link-popup-master" href="' . esc_url( $purchase_link ) . '" ">' . esc_html( $purchase_text ) . '</a>';
                                                    }
                                                }
                                            } // end if woo active and product ID set.

                                            if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && ! empty( $free_image_size ) && 'Choose an option' !== $free_image_size ) {

                                                // this is the image size in written format,ie* thumbnail, medium, large etc.
                                                $item_page_free           = explode( ' ', $free_image_size );
                                                $free_image_url           = wp_get_attachment_image_src( $image['id'], $item_page_free[0], false );
                                                $free_image_download_text = ! empty( $option['fts_free_download_text'] ) ? $option['fts_free_download_text'] : '';

                                                print '<a href="' . esc_url( $free_image_url[0] ) . '" download title="" class="ft-gallery-download noLightbox">' . esc_html( $free_image_download_text ) . '</a>';
                                            }
                                            ?>
                                        </div>

                                    </div>

                                    <div class="clear"></div>
                                    <?php
                                }
                            }

                            ?>
                        </div>
                        <?php
                    } elseif ( 'gallery' === $format_type ) {
                        // Image gallery squared and responsive.
                        ?>
                        <div class='fts-feed-type-wp_gallery slicker-ft-gallery-placeholder ft-gallery-wrapper ft-gallery-<?php echo esc_attr( $fts_dynamic_string ); ?>'
                             style='background-image:url(<?php print esc_url( $image_source_page ); ?>);<?php echo isset( $grid_width ) ? 'height:' . esc_attr( $grid_width ) . ';width:' . esc_attr( $grid_width ) . ';' : ''; ?>margin:<?php print esc_attr( $space_between_photos ); ?>;'>

                            <?php
                            if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] || isset( $_GET['ftg-tags'] ) && 'page' === $_GET['type'] ) {
                                ?>
                                <a href="<?php print esc_url( $image_source_popup ); ?>"
                                   title='<?php print esc_attr( $fts_alt_text ); ?>'
                                   data-image_id='<?php echo esc_attr( $key + 1 ); ?>'
                                   class="ft-gallery-link-popup-master"
                                   style="position: relative; overflow: hidden;"></a>

                                <div class="ft-album-contents"><a href="<?php print esc_url( $gallery_post_link ); ?>"
                                                                  title='<?php print esc_attr( $fts_alt_text ); ?>'
                                                                  class="ftg-album-link"></a>
                                    <div class="ft-album-contents-backround"></div>
                                    <div class="ftg-verticle-align"><a href="<?php print esc_url( $gallery_post_link ); ?>"
                                                                       title='<?php print esc_attr( $fts_alt_text ); ?>'
                                                                       class="ft-view-photo">
                                            <?php
                                            echo esc_html( $gallery_title . ' (' . $gallery_attachments_count . ')' );
                                            ?>
                                        </a></div>
                                </div>

                                <?php
                            }

                            if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && 'yes' === $fts_show_add_to_cart_over_image ) {

                                // Check to see if we are working with a variable product and if so make the purchase link go to cart.
                                $product = wc_get_product( $product_id );

                                $fts_cart_page_name = get_option( 'fts_cart_page_name' ) ? get_option( 'fts_cart_page_name' ) : 'cart';

                                if ( 'variable' === $product->get_type( 'variable' ) && 'prod_page' !== $purchase_link_option ) {
                                    $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                } else {
                                    if ( 'prod_page' === $purchase_link_option ) {
                                        $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                    } elseif ( 'add_cart' === $purchase_link_option ) {
                                        $purchase_link = '' . $siteurl . '/?add-to-cart=' . $product_id . '';
                                    } elseif ( 'add_cart_checkout' === $purchase_link_option ) {
                                        $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name . '/?add-to-cart=' . $product_id . '';
                                    } elseif ( 'cart_checkout' === $purchase_link_option ) {
                                        $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                    } else {
                                        $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                    }
                                }

                                // If Image already has product meta check the product still exists.
                                if ( ! empty( $product_id ) && empty( $ftg['is_album'] ) ) {
                                    $product_exist = $gallery_to_woo->fts_create_woo_prod_exists_check( $product_id );
                                    if ( $product_exist ) {
                                        $fts_purchase_link_or_popup = isset( $option['fts_popup_or_add_to_cart_link'] ) && 'popup' === $option['fts_popup_or_add_to_cart_link'] ? 'javascript:;' : $purchase_link;
                                        $fts_popup_cart_icon        = isset( $option['fts_popup_or_add_to_cart_link'] ) && 'popup' === $option['fts_popup_or_add_to_cart_link'] ? 'pointer-events:none' : '';
                                        echo '<div class="ft-gallery-responsive-cart-icon ft-gallery-cart-icon-position-' . $respnsive_gallery_cart_position . '" style="'.esc_attr( $fts_popup_cart_icon ).'">
                                        <a class="ft-gallery-buy-now ft-gallery-responsive-link" href="' . esc_url( $fts_purchase_link_or_popup ) . '"></a>
                                        </div>';
                                    }
                                }
                            } // end if woo active and product ID set.

                            $ftg_page_tags_check = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : '';

                            // refers to tags... ! isset( $_GET['type'] ) && 'page' !== $_GET['type'].
                            if ( isset( $popup ) && 'yes' === $popup ) {

                            if ( 'page' !== $ftg_page_tags_check ) {
                            ?>
                            <div class='slicker-instaG-backg-link'>
                                <div class="ft-text-for-popup">
                                    <div class="ft-text-for-popup-content">
                                        <?php if ( 'yes' === $hide_icon ) { ?>
                                            <div class="ft-gallery-icon-wrap-right fts-mashup-wp_gallery-icon ft-wp-gallery-icon">
                                                <a href="<?php print esc_url( $username_link ); ?>"
                                                   target="<?php print esc_attr( $link_target ); ?>"></a>
                                            </div>
                                        <?php } ?>

                                        <?php if ( 'none' !== $username ) { ?>
                                            <span class="ft-gallery-fb-user-name"><a
                                                        href="<?php print esc_url( $username_link ); ?>"
                                                        target="<?php print esc_attr( $link_target ); ?>"><?php print esc_html( $username ); ?></a></span>
                                        <?php } ?>

                                        <?php if ( 'yes' === $hide_date ) { ?>
                                            <span class="ft-gallery-post-time"><?php print esc_html( $ftg_final_date ); ?></span>
                                        <?php } ?>
                                        <div class="ft-gallery-description-wrap">
                                            <?php if ( 'title' === $title_description || 'title_description' === $title_description ) { ?>
                                                <p><strong class="ftg-title-wrap"><?php print esc_html( $img_title ); ?></strong>
                                                </p><?php } ?><?php if ( 'description' === $title_description || 'title_description' === $title_description ) { ?>
                                                <p>
                                                    <?php
                                                    print wp_kses(
                                                        nl2br( $image_description ),
                                                        array(
                                                            'a' => array(
                                                                'href' => array(),
                                                                'title' => array(),
                                                            ),
                                                            'br' => array(),
                                                            'em' => array(),
                                                            'strong' => array(),
                                                            'small' => array(),
                                                        )
                                                    );
                                                    ?>
                                                </p>
                                                <?php
                                            }
                                            if ( empty( $ftg['is_album'] ) || isset( $_GET['ftg-tags'] ) && 'page' !== $_GET['type'] ) {

                                                // Image Tags.
                                                if ( 'yes' === $option['fts_show_tags'] && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                                                    $ftg_tags = new image_and_gallery_tags_class();

                                                    echo wp_kses(
                                                        $ftg_tags->fts_tags( $image['id'], $ftg['id'], 'image' ),
                                                        array(
                                                            'a'    => array(
                                                                'href'  => array(),
                                                                'title' => array(),
                                                            ),
                                                            'span' => array(
                                                                'class' => array(),
                                                                'title' => array(),
                                                            ),
                                                            'div'  => array(
                                                                'class' => array(),
                                                                'title' => array(),
                                                            ),
                                                        )
                                                    );
                                                }
                                            }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php print esc_url( $image_source_popup ); ?>"
                               title='<?php print esc_attr( $fts_alt_text ); ?>'
                               data-image_id='<?php echo esc_attr( $key + 1 ); ?>'
                               class="ft-gallery-link-popup-master ft-gallery-link-popup-click-action ft-view-photo">
                                <?php
                                }
                                }
                                ?>
                                <?php
                                if ( isset( $fts_watermark_enable_options, $watermark ) && 'yes' === $fts_watermark_enable_options && 'overlay' === $watermark ) {
                                    ?>
                                    <div class="
                                    <?php
                                    if ( isset( $watermark_overlay_enable ) && 'popup-only' === $watermark_overlay_enable ) {
                                        ?>
                                        ft-image-overlay fts-image-overlay-hide
                                        <?php
                                    } elseif ( isset( $watermark_overlay_enable ) && 'page-and-popup' === $watermark_overlay_enable ) {
                                        ?>
                                        ft-image-overlay<?php } ?>">
                                        <div class="fts-watermark-inside fts-watermark-inside-<?php echo esc_attr( $watermark_image_position ); ?>"
                                            <?php
                                            if ( isset( $watermark_image_opacity ) && null !== $watermark_image_opacity ) {
                                                ?>
                                                style="opacity:<?php echo esc_attr( $watermark_image_opacity ); ?>"<?php } ?>>
                                            <img src="<?php print esc_url( $watermark_image_url ); ?>"
                                                <?php
                                                if ( isset( $watermark_image_margin ) && null !== $watermark_image_margin ) {
                                                    ?>
                                                    style="margin:<?php echo esc_attr( $watermark_image_margin ); ?>"<?php } ?>
                                                 alt="<?php print esc_attr( $fts_alt_text ); ?>"/>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                if ( isset( $popup ) && 'yes' === $popup ) {

                                // Get $product object from product ID.
                                ?>
                            </a>
                        <?php
                        if ( 'yes' !== $hide_add_to_cart ) {
                            print ' <div class="ftg-varation-for-popup" style="display: none!important;">';
                            if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && empty( $ftg['is_album'] ) ) {

                                // Get $product object from product ID.
                                $this->ftg_variable_add_to_cart( $product_id );
                            }
                            print '</div>';
                        }

                        $free_image_size = $option['ftg_free_download_size'] ? $option['ftg_free_download_size'] : '';
                        if ( 'yes' === $show_share || is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && 'yes' === $show_purchase_link && empty( $ftg['is_album'] ) || is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && ! empty( $free_image_size ) && 'Choose an option' !== $free_image_size ) {
                            ?>
                            <div class="fts-mashup-count-wrap">

                                <?php if ( 'yes' === $show_share ) {
                                    $is_loadmore = 'yes' === $ftg_loadmore_option ? ' ftg-share-loadmore' : '';
                                    ?>
                                    <div class="fts-share-wrap">
                                        <a href="javascript:;" class="ft-gallery-link-popup"></a>
                                        <div class='ft-gallery-share-wrap<?php echo $is_loadmore ?>'>
                                            <a href='<?php print esc_url( $fts_share_facebook ); ?>' target='_blank'
                                               class='ft-galleryfacebook-icon'><i class='fa fa-facebook-square'></i></a>
                                            <a href='<?php print esc_url( $fts_share_twitter ); ?>' target='_blank'
                                               class='ft-gallerytwitter-icon'><i class='fa fa-twitter'></i></a>
                                            <a href='<?php print esc_url( $fts_share_google ); ?>' target='_blank'
                                               class='ft-gallerygoogle-icon'><i class='fa fa-google-plus'></i></a>
                                            <a href='<?php print esc_url( $fts_share_linkedin ); ?>' target='_blank'
                                               class='ft-gallerylinkedin-icon'><i class='fa fa-linkedin'></i></a>
                                            <a href='<?php print esc_url( $fts_share_email ); ?>' target='_blank'
                                               class='ft-galleryemail-icon'><i class='fa fa-envelope'></i></a>
                                            <?php if ( 'yes' !== $ftg_loadmore_option ) { ?>
                                                <div class="ft-gallery-clear"></div>
                                                <div class="ftg-share-text"><?php esc_html_e( 'Use link to share image', 'feed_them_social' ) ?></div>
                                                <div class="ftg-text-copied"><?php esc_html_e( 'Copied to Clipboard', 'feed_them_social' ) ?></div>
                                                <a href='javascript:;'
                                                   class='ft-gallerylink-icon' onclick="ftgallerycopy('ftg-image-link<?php echo $key + 1 ?>');"><i class='fa fa-link'></i></a> <input id="ftg-image-link<?php echo $key + 1 ?>" onclick="this.select()" value="<?php echo $link ?>">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="ft-gallery-cta-button-wrap">
                                    <?php
                                    if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && isset( $product_id ) && '' !== $product_id && 'yes' === $show_purchase_link ) {

                                        // Check to see if we are working with a variable product and if so make the purchase link go to cart.
                                        $product = wc_get_product( $product_id );

                                        $fts_cart_page_name = get_option( 'fts_cart_page_name' ) ? get_option( 'fts_cart_page_name' ) : 'cart';

                                        if ( 'variable' === $product->get_type( 'variable' ) && 'prod_page' !== $purchase_link_option ) {
                                            $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                        } else {
                                            if ( 'prod_page' === $purchase_link_option ) {
                                                $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                            } elseif ( 'add_cart' === $purchase_link_option ) {
                                                $purchase_link = '' . $siteurl . '/?add-to-cart=' . $product_id . '';
                                            } elseif ( 'add_cart_checkout' === $purchase_link_option ) {
                                                $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name . '/?add-to-cart=' . $product_id . '';
                                            } elseif ( 'cart_checkout' === $purchase_link_option ) {
                                                $purchase_link = '' . $siteurl . '/' . $fts_cart_page_name;
                                            } else {
                                                $purchase_link = '' . $siteurl . '/product/?p=' . $product_id . '';
                                            }
                                        }

                                        // If Image already has product meta check the product still exists.
                                        if ( ! empty( $product_id ) && empty( $ftg['is_album'] ) ) {
                                            $product_exist = $gallery_to_woo->fts_create_woo_prod_exists_check( $product_id );
                                            if ( $product_exist ) {
                                                echo '<a class="ft-gallery-buy-now ft-gallery-link-popup-master" href="' . esc_url( $purchase_link ) . '" ">' . esc_html( $purchase_text ) . '</a>';
                                            }
                                        }
                                    } // end if woo active and product ID set.

                                    if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && ! empty( $free_image_size ) && 'Choose an option' !== $free_image_size ) {
                                        // this is the image size in written format,ie* thumbnail, medium, large etc.
                                        $item_page_free           = explode( ' ', $free_image_size );
                                        $free_image_url           = wp_get_attachment_image_src( $attachment_id = $image['id'], $item_page_free[0], false );
                                        $free_image_download_text = ! empty( $option['fts_free_download_text'] ) ? $option['fts_free_download_text'] : '';

                                        print '<a href="' . esc_url( $free_image_url[0] ) . '" download title="" class="ft-gallery-download noLightbox">' . esc_html( $free_image_download_text ) . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                        <?php } ?>


                            <div class="clear"></div>
                        <?php } ?>
                        </div>
                        <?php if ( empty( $ftg['is_album'] ) ) { ?>
                            <!--</div>-->
                            <?php
                        }
                    }
                    // END else is no reg post format.
                }
                // END // We have 3 wrapper options at the moment post, post-in-grid, gallery and gallery-collage.
                $offset = $ftg['offset'];

                if ( 'yes' === $fts_load_more_option ) {

                    // Load More BUTTON Start.
                    $my_request['fts_offset'] = $offset;

                    // Make sure it's not ajaxing.
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                        if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] ) {
                            $is_album = 'yes';
                        } else {
                            $is_album = 'no';
                        }
                        $offset     = 2;
                        $post_count = $post_count;
                    } else {
                        $offset     = 1 + $ftg['offset'];
                        $post_count = $post_count + $ftg['media_count'];
                    }

                    if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] ) {
                        $total_albums = $gallery_class->ft_album_count_post_galleries( $ftg['id'] );
                        if ( $post_count > $total_albums ) {
                            $post_count = $total_albums;
                        }
                    } elseif ( $post_count > $gallery_class->fts_count_post_images( $ftg['id'] ) ) {
                        $post_count = $gallery_class->fts_count_post_images( $ftg['id'] );
                    }
                    ?>
                    <script>

                        var fts_offset<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?>= "<?php echo esc_js( $offset ); ?>";
                        var fts_posts<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?>= "<?php echo esc_js( $post_count ); ?>";
                        jQuery('.ft-gallery-image-loaded-count').html(fts_posts<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?>)</script>
                    <?php
                    // Make sure it's not ajaxing.
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                        $fts_dynamic_name = sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) );
                        $time                    = time();
                        $nonce                   = wp_create_nonce( $time . 'load-more-nonce' );
                        ?>
                        <script> jQuery(document).ready(function () {
                                <?php if ( 'autoscroll' === $scroll_more ) { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode. ?>
                                jQuery(".<?php echo esc_js( $feed_name_rand_string ); ?>-scrollable").bind("scroll", function () {
                                    if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
                                        <?php
                                        } else { // this is where we do CLICK function to LOADMORE if = button in shortcode.
                                        ?>
                                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").click(function () {
                                            <?php
                                            }
                                            $fts_bounce_color = isset( $fts_loadmore_text_color ) && null !== $fts_loadmore_text_color ? ' style="background:' . esc_html( $fts_loadmore_text_color ) . ';"' : '';
                                            ?>
                                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
                                            var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"<?php echo $fts_bounce_color; ?>></div><div class="bounce2"<?php echo $fts_bounce_color; ?>></div><div class="bounce3"<?php echo $fts_bounce_color; ?>></div>');
                                            console.log(button);

                                            var yes_ajax = "yes";
                                            var fts_id = "<?php echo esc_js( $ftg['id'] ); ?>";
                                            var fts_offset = fts_offset<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?>;
                                            var fts_post_count = fts_posts<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?>;
                                            var fts_security = "<?php echo esc_js( $nonce ); ?>";
                                            var fts_time = "<?php echo esc_js( $time ); ?>";
                                            var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ); ?>";
                                            var fts_is_album = "<?php echo esc_js( $is_album ); ?>";
                                            jQuery.ajax({
                                                data: {
                                                    action: "fts_load_more",
                                                    fts_id: fts_id,
                                                    fts_offset: fts_offset,
                                                    fts_media_count: fts_post_count,
                                                    load_more_ajaxing: yes_ajax,
                                                    fts_security: fts_security,
                                                    fts_time: fts_time,
                                                    fts_dynamic_name: fts_d_name,
                                                    fts_is_album: fts_is_album,
                                                },
                                                type: 'GET',
                                                url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                                                success: function (data) {
                                                    console.log('Well Done and got this from sever: ' + data);
                                                    jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').append(data).filter('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').html();
                                                    <?php if ( 'post-in-grid' === $format_type || 'gallery-collage' === $format_type ) { ?>
                                                    jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').masonry('reloadItems');
                                                    setTimeout(function () {
                                                        // Do something after 3 seconds
                                                        jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').masonry('layout');
                                                    }, 500);
                                                    <?php
                                                    }

                                                    if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] ) {
                                                        $final_post_count = $gallery_class->ft_album_count_post_galleries( $ftg['id'] );
                                                    } else {
                                                        $final_post_count = $gallery_class->fts_count_post_images( $ftg['id'] );
                                                    }

                                                    ?>
                                                    if (fts_posts<?php echo sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) ); ?> >=  <?php echo esc_js( $final_post_count ); ?>) {
                                                        jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').replaceWith('<?php
                                                        print '<div style="';
                                                        if ( isset( $loadmore_btn_maxwidth ) && '' !== $loadmore_btn_maxwidth ) {
                                                            print 'max-width:' . esc_js( $loadmore_btn_maxwidth ) . ';';
                                                        }
                                                        if ( isset( $fts_loadmore_background_color ) && '' !== $fts_loadmore_background_color ) {
                                                            print 'background:' . esc_js( $fts_loadmore_background_color ) . ';';
                                                        }
                                                        if ( isset( $fts_loadmore_text_color ) && '' !== $fts_loadmore_text_color ) {
                                                            print 'color:' . esc_js( $fts_loadmore_text_color ) . ';';
                                                        }
                                                        print 'margin:' . esc_js( $loadmore_btn_margin ) . ' auto ' . esc_js( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( 'No More Photos', 'feed_them_social' ) . '</div>';
                                                        ?>
                                                        '
                                                    )
                                                        ;
                                                        //  jQuery('.ft-wp-gallery-scrollable').removeAttr('class');
                                                        jQuery('.<?php echo esc_js( $feed_name_rand_string ); ?>-scrollable').unbind('scroll');
                                                    }
                                                    jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php esc_html_e( 'Load More', 'feed_them_social' ); ?>');
                                                    jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');
                                                    <?php if ( 'yes' !== $ftg['is_album'] ) { ?>
                                                    // Reload the share each funcion otherwise you can't open share option.
                                                    jQuery.fn.ftsShare();

                                                    <?php
                                                    if ( isset( $popup ) && 'yes' === $popup ) {?>
                                                    // Reload this function again otherwise the popup won't work correctly for the newly loaded items
                                                    jQuery.fn.slickWordpressPopUpFunction();
                                                    <?php }
                                                    if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
                                                    jQuery.fn.ftg_apply_quant_btn();
                                                    jQuery.getScript("<?php echo esc_url( '/wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart-variation.min.js' ); ?>");
                                                    <?php
                                                    }
                                                    }
                                                    ?>

                                                    <?php
                                                    if ( 'gallery' === $format_type ) {
                                                    ?>
                                                    if (jQuery("#ftg-gallery-demo").hasClass("ftg-demo-1")) {
                                                        outputSRmargin(document.querySelector('#margin').value)
                                                    } // Reload our margin for the demo
                                                    // Reload our imagesizing function so the images show up proper
                                                    slickremixFTGalleryImageResizing();
                                                    <?php } ?>
                                                }
                                            }); // end of ajax()
                                            return false;
                                            <?php
                                            // string $scroll_more is at top of this js script. exception for scroll option closing tag.
                                            if ( 'autoscroll' === $scroll_more ) {
                                            ?>
                                        }; // end of scroll ajax load.
                                        <?php } ?>
                                    }
                                ); // end of document.ready
                            }); // end of form.submit
                        </script>
                        <?php
                    }
                    // End Check.
                    // main closing div not included in ajax check so we can close the wrap at all times.
                    print '</div>'; // closing main div for photos and scroll wrap.

                    // Make sure it's not ajaxing
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                        $fts_dynamic_name = sanitize_text_field( wp_unslash( $my_request['fts_dynamic_name'] ) );
                        // this div returns outputs our ajax request via jquery append html from above.
                        print '<div class="fts-clear"></div>';
                        print '<div id="output_' . esc_attr( $fts_dynamic_class_name ) . '"></div>';
                        if ( 'autoscroll' === $scroll_more ) {
                            print '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">' . __( 'Load More', 'feed_them_social' ) . '</div>';
                        }

                        ?>
                        <?php
                        // only show this script if the height option is set to a number.
                        if ( '' !== $height && null === empty( $height ) ) {
                            ?>
                            <script>
                                // this makes it so the page does not scroll if you reach the end of scroll bar or go back to top
                                jQuery.fn.isolatedScrollFTGallery = function () {
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
                                jQuery('.ft-wp-gallery-scrollable').isolatedScrollFTGallery();
                            </script>
                        <?php } //end $height !== 'auto' && NULL === empty($height). ?>
                        <?php
                        if ( isset( $scroll_more ) && 'autoscroll' === $scroll_more || isset( $height ) && '' !== $height ) {
                            print '</div><!--closing height div for scrollable feeds-->'; // closing height div for scrollable feeds.
                        } elseif ( 'gallery' === $format_type ) {

                            print '</div><!--closing height div for scrollable feeds-->'; // closing height div for scrollable feeds.
                        }
                        print '<div class="fts-clear"></div>';
                        if ( isset( $scroll_more ) && 'button' === $scroll_more ) {

                            print '<div class="fts-instagram-load-more-wrapper">';
                            print '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '"" style="';
                            if ( isset( $loadmore_btn_maxwidth ) && '' !== $loadmore_btn_maxwidth ) {
                                print 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
                            }
                            if ( isset( $fts_loadmore_background_color ) && '' !== $fts_loadmore_background_color ) {
                                print 'background:' . esc_attr( $fts_loadmore_background_color ) . ';';
                            }
                            if ( isset( $fts_loadmore_text_color ) && '' !== $fts_loadmore_text_color ) {
                                print 'color:' . esc_attr( $fts_loadmore_text_color ) . ';';
                            }
                            print 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( 'Load More', 'feed_them_social' ) . '</div>';
                            print '</div>';

                        }
                        if ( 'yes' === $pagination ) {

                            if ( isset( $fts_pagination_text_color ) && null !== $fts_pagination_text_color ) {
                                $fts_pagination_text_color = 'style="color:' . esc_attr( $fts_pagination_text_color ) . ';"';
                            }
                            if ( isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] ) {
                                $total_post_count = $gallery_class->ft_album_count_post_galleries( $ftg['id'] );
                            } else {
                                $total_post_count = $gallery_class->fts_count_post_images( $ftg['id'] );
                            }
                            echo '<div class="ftgallery-image-count-wrap"' . $fts_pagination_text_color . '>';
                            echo '<span class="ft-gallery-image-loaded-count">' . esc_html( $post_count ) . '</span>';
                            echo '<span class="ft-gallery-count-of">' . esc_html( 'of', 'feed_them_social' ) . '</span>';
                            echo '<span class="ft-gallery-image-count-total"> ' . esc_html( $total_post_count ) . ' </span>';
                            echo '</div>';
                        }
                    }//End Check
                    unset( $my_request['fts_offset'] );
                } else {
                    if ( 'gallery' === $format_type ) {
                        print '</div>'; // closing div for feed
                    }
                    print '</div>'; // closing div for main wrapper
                }
            } //Error or Empty!
            else {
                $image_list;
            }

            // Make sure it's not ajaxing
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

                // Opening Div for footer pagination
                if ( 'yes' === $ftg_sorting_options || 'yes' === $fts_show_true_pagination ) {
                    print '<div class="ftg-pagination-footer">';
                }
                if ( 'yes' === $ftg_sorting_options ) {
                    $fts_position_of_pagination = null !== $option['ftg_position_of_sort_select'] ? $option['ftg_position_of_sort_select'] : '';
                    if ( 'above-below' === $fts_position_of_pagination || 'below' === $fts_position_of_pagination ) {
                        $this->ftg_sort_order_select( $ftg['id'] );
                    }
                }

                if ( 'yes' === $fts_show_true_pagination ) {
                    $fts_position_of_pagination = null !== $option['fts_position_of_pagination'] ? $option['fts_position_of_pagination'] : '';
                    if ( 'above-below' === $fts_position_of_pagination || 'below' === $fts_position_of_pagination ) {
                        $count_for_tags_final = isset( $toal_count_for_tags ) ? $toal_count_for_tags : '';
                        $this->ftg_pagination( $ftg['id'], $ftg['is_album'], $tags, $tags_list, $count_for_tags_final );
                    }
                }
                // End closing Div for footer pagination
                if ( 'yes' === $ftg_sorting_options || 'yes' === $fts_show_true_pagination ) {
                    print '</div><div class="ftg-clear"></div>';
                }

                if ( isset( $option['fts_show_page_tags'] ) && 'below_images' === $option['fts_show_page_tags'] && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
                    $ftg_tags = new image_and_gallery_tags_class();
                    echo wp_kses(
                        $ftg_tags->fts_tags( $ftg['id'], null, 'page' ),
                        array(
                            'a'    => array(
                                'href'  => array(),
                                'title' => array(),
                            ),
                            'span' => array(
                                'class' => array(),
                                'title' => array(),
                            ),
                            'div'  => array(
                                'class' => array(),
                                'title' => array(),
                            ),
                        )
                    );
                }

                if ( current_user_can( 'manage_options' ) && ! isset( $_GET['ftg-tags'] ) ) {
                    $gallery_or_album_text = isset( $ftg['is_album'] ) && 'yes' === $ftg['is_album'] ? esc_html( 'Edit Album', 'feed_them_social' ) : esc_html( 'Edit Gallery', 'feed_them_social' );
                    ?>
                    <div class="ft-gallery-edit-link" style="text-align: center;">
                        <a href="<?php print esc_url( $edit_url ); ?>"
                           target="_blank"><?php echo esc_html( $gallery_or_album_text ); ?></a>
                    </div>
                    <?php
                }
            }//End is ajaxing
        }

        return ob_get_clean();
    }


    /**
     *  Get Attachment ID
     *
     * Get an attachment ID given a URL.
     *
     * @param string $url
     *
     * @return int Attachment ID on success, 0 on failure
     */
    public function fts_get_attachment_id( $url ) {

        $attachment_id = 0;

        $dir = wp_upload_dir();

        if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?

            $file = basename( $url );

            $query_args = array(
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'value'   => $file,
                        'compare' => 'LIKE',
                        'key'     => '_wp_attachment_metadata',
                    ),
                ),
            );

            $query = new \ WP_Query( $query_args );

            if ( $query->have_posts() ) {

                foreach ( $query->posts as $post_id ) {

                    $meta = wp_get_attachment_metadata( $post_id );

                    $original_file       = basename( $meta['file'] );
                    $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

                    if ( $original_file === $file || in_array( $file, $cropped_image_files, true ) ) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }

        return $attachment_id;
    }


    /**
     *  2day Array
     *
     * Arrange 2 dimensional array
     *
     * @param $array
     * @param int   $col_count
     * @return bool
     * @since 1.0.0
     */
    public function fts_array_2d( $array, $col_count = 1 ) {
        $result = false;
        if ( ! empty( $array ) && is_array( $array ) ) {
            $row_count = ceil( count( $array ) / $col_count );
            $pointer   = 0;
            for ( $row = 0; $row < $row_count; $row++ ) {
                for ( $col = 0; $col < $col_count; ++$col ) {
                    if ( isset( $array[ $pointer ] ) ) {
                        $result[ $row ]['id'] = $array[ $pointer ];
                        $pointer++;
                    }
                }
            }
        }

        return $result;
    }


    /**
     *  Sort Image List
     *
     * Sort the list of images
     *
     * @param string $gallery_id The gallery ID to pass.
     * @return string
     * @since 1.0.0
     */
    public function fts_sort_image_list( $gallery_id ) {
        // IF images are sorted on gallery post.
        $option = $this->fts_get_option_or_get_postmeta( $gallery_id );

        $image_list_sort = $this->fts_get_media_rest( $gallery_id, '100' );
        // We take the saved array that gets stored in the post meta field, ft-gallery-images-sort-order, when you sort the images on a gallery page and use the function below fts_array_2d to format the array so we can flip the array and use the number count and compare the $image_list arrays id's with our usort function below that.
        $image_sort = $option['ft-gallery-images-sort-order'];
        echo '<pre>' . print_r( $image_list_sort, 1 ) . '</pre>';
        // http://snipplr.com/view/67672/.
        function fts_array_2d( $array, $col_count = 1 ) {
            $result = false;
            if ( ! empty( $array ) && is_array( $array ) ) {
                $row_count = ceil( count( $array ) / $col_count );
                $pointer   = 0;
                for ( $row = 0; $row < $row_count; $row++ ) {
                    for ( $col = 0; $col < $col_count; ++$col ) {
                        if ( isset( $array[ $pointer ] ) ) {
                            $result[ $row ]['id'] = $array[ $pointer ];
                            $pointer++;
                        }
                    }
                }
            }

            return $result;
        }

        $result = fts_array_2d( $image_sort, 1 );
        echo '<pre>' . print_r( $image_sort, 1 ) . '</pre>';

        $skeys = array_flip( $image_sort );

        echo '<pre>' . print_r( $skeys, 1 ) . '</pre>';
        usort(
            $image_list_sort,
            function ( $a, $b ) use ( $skeys ) {
                $final   = isset( $skeys[ $a['id'] ] ) ? $skeys[ $a['id'] ] : null;
                $a       = $final;
                $b       = $skeys[ $b['id'] ];
                $newlist = $a - $b;
                echo '<pre>' . print_r( $newlist, 1 ) . '</pre>';

                return $newlist;
            }
        );

        // END IF images are sorted on gallery post
    }

    /**
     * Load More
     *
     * This function is being called from the fb feed... it calls the ajax in this case.
     *
     * @since 1.0.0
     */
    public function fts_load_more() {

        $my_request = stripslashes_deep( $_REQUEST );

        if ( ! wp_verify_nonce( $my_request['fts_security'], $my_request['fts_time'] . 'load-more-nonce' ) ) {
            exit( 'Sorry, You can\'t do that!' );
        } else {

            $post_count = sanitize_text_field( wp_unslash( $my_request['fts_post_count'] ) );
            $offset     = sanitize_text_field( wp_unslash( $my_request['fts_offset'] ) );
            $media      = sanitize_text_field( wp_unslash( $my_request['fts_media_count'] ) );
            $is_album   = $my_request['fts_is_album'] && 'yes' === sanitize_text_field( wp_unslash( $my_request['fts_is_album'] ) ) ? 'is_album=yes' : '';

            $shortcode = $my_request['fts_is_album'] && 'yes' === sanitize_text_field( wp_unslash( $my_request['fts_is_album'] ) ) ? 'ft-gallery-album' : 'feed_them_social';

            echo do_shortcode( '[' . esc_html( $shortcode ) . ' id=' . sanitize_text_field( wp_unslash( $my_request['fts_id'] ) ) . ' ' . $is_album . ' offset=' . sanitize_text_field( wp_unslash( $offset ) ) . ' media_count=' . sanitize_text_field( wp_unslash( $media ) ) . ']' );
        }
        die();
    }

    /**
     * FTG Variable Add To Cart
     *
     * Used to load the simple price or variable price and add to cart buttons etc.
     *
     * @param string $product_id The product ID to pass.
     * @since 1.0.0
     */
    public function ftg_variable_add_to_cart( $product_id ) {
        global $product;

        $product = wc_get_product( $product_id );

        if ( 'variable' === $product->get_type( 'variable' ) ) {
            ?>
            <div class="ft-gallery-variations-wrap">
                <div class="ft-gallery-variations-price-wrap">
                    <?php
                    // Saving commented out items: Use case... if we want to have a From: $10 - $50 option.
                    // $prefix = sprintf('%s: ', __('From', 'feed_them_social')); .
                    $min_price_regular = $product->get_variation_regular_price( 'min', true );
                    $min_price_sale    = $product->get_variation_sale_price( 'min', true );
                    $max_price         = $product->get_variation_price( 'max', true );
                    // $min_price = $product->get_variation_price('min', true); .
                    $price = ( $min_price_sale === $min_price_regular ) ? wc_price( $min_price_regular ) . ' - ' . wc_price( $max_price ) : print '<del>' . wc_price( $min_price_regular ) . '</del><ins>' . wc_price( $min_price_sale ) . '</ins>';
                    // print ( $min_price == $max_price ) ? $price : sprintf('%s%s', $prefix, $price); .
                    print wp_kses(
                        $price,
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                            'del'  => array(),
                            'ins'  => array(),
                        )
                    );
                    ?>
                </div>
                <div class="ft-gallery-variations-text ft-gallery-js-load">
                    <?php
                    // Enqueue variation scripts.
                    wp_enqueue_script( 'wc-add-to-cart-variation' );
                    // Load the template.
                    wc_get_template(
                        'single-product/add-to-cart/variable.php',
                        array(
                            'available_variations' => $product->get_available_variations(),
                            'attributes'           => $product->get_variation_attributes(),
                            'selected_attributes'  => $product->get_default_attributes(),
                        )
                    );
                    ?>
                </div>
            </div>
            <?php
        } elseif ( 'simple' === $product->get_type( 'variable' ) ) {
            ?>

            <div class="ft-gallery-variations-wrap">
                <div class="ft-gallery-variations-price-wrap ft-gallery-simple-price">

                    <?php
                    $price_regular = $product->get_regular_price( 'min', true );
                    $price_sale    = $product->get_sale_price( 'min', true );
                    $price         = $price_sale ? '<del>' . wc_price( $price_regular ) . '</del><ins>' . wc_price( $price_sale ) . '</ins>' : wc_price( $price_regular );

                    print wp_kses(
                        $price,
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                            'del'  => array(),
                            'ins'  => array(),
                        )
                    );
                    ?>
                </div>
                <div class="ft-gallery-simple-cart">
                    <?php
                    // Enqueue variation scripts.
                    wc_get_template( 'single-product/add-to-cart/simple.php' );
                    ?>
                </div>
            </div>
        <?php }
    }
}

?>
