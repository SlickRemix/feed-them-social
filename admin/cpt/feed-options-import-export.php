<?php namespace feedthemsocial;
/**
 * Feed Options Import Export
 *
 * This class is used to create the Feed Settings Importer and Exporter on the Feed Edit page (CPT)
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0.5
 */

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feed Options Import Export Class
 */
class Feed_Options_Import_Export {
	/**
	 * Feed Functions Class
	 *
	 * initiates Feed Functions object.
	 *
	 * @var object
	 */
	public $feed_functions;

	/**
	 * Data Protection
	 *
	 * Data Protection Class for encryption.
	 *
	 * @var object
	 */
	public $data_protection;

	/**
	 * System Info
	 *
	 * System Info page functions.
	 *
	 * @var object
	 */
	public $system_info;

	/**
	 * Feed Options Import Export constructor.
	 */
	public function __construct( $feed_functions, $data_protection, $system_info ) {
		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Set Data Protection object.
		$this->data_protection = $data_protection;

		// Set System info object.
		$this->system_info = $system_info;

		// Export Feed Options AJAX
		add_action( 'wp_ajax_fts_export_feed_options_ajax', array( $this, 'fts_export_feed_options_ajax' ) );
		// Import Feed Options AJAX
		add_action( 'wp_ajax_fts_import_feed_options_ajax', array( $this, 'fts_import_feed_options_ajax' ) );
	}

	/**
	 * FTS Export Options Ajax
	 *
	 * This will export the option of a feed and decrypt the access token for Facebook or Instagram. Used when users request support.
	 *
	 * @since 4.0.5
	 */
	public function fts_export_feed_options_ajax() {

		check_ajax_referer( 'fts_export_feed_options_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Forbidden', 'feed-them-social' ), 403 );
		}

		$cpt_id = (int) $_REQUEST['cpt_id'];
		$saved_feed_options = $this->feed_functions->get_saved_feed_options( esc_html( $cpt_id ) );

		// If Instagram token decrypt.
		if ( isset($saved_feed_options['fts_instagram_custom_api_token']) ) {
			$saved_feed_options['fts_instagram_custom_api_token'] = $this->data_protection->decrypt( $saved_feed_options['fts_instagram_custom_api_token'] );
		}
		// If Instagram Business token decrypt.
		if ( isset($saved_feed_options['fts_facebook_instagram_custom_api_token']) ) {
			$saved_feed_options['fts_facebook_instagram_custom_api_token'] = $this->data_protection->decrypt( $saved_feed_options['fts_facebook_instagram_custom_api_token'] );
		}

		// If Facebook Business token decrypt.
		if ( isset($saved_feed_options['fts_facebook_custom_api_token']) ) {
			$saved_feed_options['fts_facebook_custom_api_token'] = $this->data_protection->decrypt( $saved_feed_options['fts_facebook_custom_api_token'] );
		}

        $data = array(
            'system_info' => $this->system_info->fts_system_info_support_ticket(),
            'feed_options' => json_encode( $saved_feed_options )
        );

        echo json_encode( $data );

		wp_die();
	}

	/**
	 * FTS Import Options Ajax
	 *
	 * This will import the options of a feed and encrypt the access token for Facebook or Instagram. Used when users request support.
	 *
	 * @since 4.0.5
	 */
	public function fts_import_feed_options_ajax() {

		check_ajax_referer( 'fts_import_feed_options_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Forbidden', 'feed-them-social' ), 403 );
		}

		if ( ! isset( $_REQUEST['cpt_id'] ) ) {
			wp_send_json_error( esc_html__( 'Missing feed id.', 'feed-them-social' ), 400  );
		} elseif ( ! isset( $_REQUEST['cpt_import'] ) || empty( $_REQUEST['cpt_import'] ) ) {
			wp_send_json_error( esc_html__( 'Missing import data.', 'feed-them-social' ), 400 );
		}

		$cpt_id = (int) $_REQUEST['cpt_id'];

		$saved_feed_options = json_decode( stripslashes( $_REQUEST['cpt_import'] ) , true );

		if ( JSON_ERROR_NONE !== json_last_error() ) {
			wp_send_json_error( esc_html__( 'Failed to decode json string: ' . json_last_error_msg(), 'feed-them-social' ), 400 );
		}
		// Settings Sanitization Array
		$possible_setting_keys = [
			'feed_type' => 'sanitize_key',
			'fts_facebook_custom_api_token_user_id' => 'intval',
			'fts_facebook_custom_api_token_user_name' => 'htmlspecialchars',
			'fts_facebook_custom_api_token' => 'htmlspecialchars',
			'facebook_page_feed_type' => 'htmlspecialchars',
			'facebook_page_posts_displayed' => 'sanitize_key',
			'facebook_page_post_count' => 'intval',
			'facebook_page_title' => 'sanitize_key',
			'facebook_page_description' => 'sanitize_key',
			'facebook_image_width' => 'sanitize_key',
			'facebook_image_height' => 'sanitize_key',
			'facebook_hide_date_likes_comments' => 'sanitize_key',
			'facebook_container_position' => 'sanitize_key',
			'facebook_align_images' => 'sanitize_key',
			'facebook_hide_like_box_button' => 'sanitize_key',
			'instagram_feed_type' => 'sanitize_key',
			'instagram_pics_count' => 'intval',
			'instagram_profile_wrap' => 'sanitize_key',
			'instagram_profile_photo' => 'sanitize_key',
			'instagram_profile_stats' => 'sanitize_key',
			'instagram_profile_name' => 'sanitize_key',
			'instagram_profile_description' => 'sanitize_key',
			'instagram_columns' => 'sanitize_key',
			'instagram_force_columns' => 'sanitize_key',
			'instagram_icon_size' => 'sanitize_key',
			'instagram_hide_date_likes_comments' => 'sanitize_key',
			'twitter-messages-selector' => 'sanitize_key',
			'twitter_name' => 'htmlspecialchars',
			'twitter_cover_photo' => 'sanitize_key',
			'twitter_stats_bar' => 'sanitize_key',
			'twitter_show_retweets' => 'sanitize_key',
			'twitter_show_replies' => 'sanitize_key',
			'youtube_feed_type' => 'htmlspecialchars',
			'youtube_channelID' => 'htmlspecialchars',
			'youtube_vid_count' => 'intval',
			'youtube_first_video' => 'sanitize_key',
			'youtube_large_vid_title' => 'sanitize_key',
			'youtube_large_vid_description' => 'sanitize_key',
			'youtube_play_thumbs' => 'sanitize_key',
			'youtube_columns' => 'intval',
			'youtube_omit_first_thumbnail' => 'sanitize_key',
			'youtube_force_columns' => 'sanitize_key',
			'youtube_maxres_thumbnail_images' => 'sanitize_key',
			'combine_post_count' => 'intval',
			'combine_social_network_post_count' => 'intval',
			'combine_container_position' => 'sanitize_key',
			'combine_show_social_icon' => 'sanitize_key',
			'combine_show_media' => 'sanitize_key',
			'combine_hide_date' => 'sanitize_key',
			'combine_hide_name' => 'sanitize_key',
			'combine_grid_option' => 'sanitize_key',
			'fb_show_follow_btn' => 'sanitize_key',
			'fb_like_btn_color' => 'sanitize_key',
			'fb_language' => 'htmlspecialchars',
			'fb_hide_no_posts_message' => 'sanitize_key',
			'facebook_view_on_facebook' => 'htmlspecialchars',
			'fb_title_htag' => 'sanitize_key',
			'facebook_hide_shared_by_etc_text' => 'sanitize_key',
			'fb_hide_images_in_posts' => 'sanitize_key',
			'fb_hide_error_handler_message' => 'sanitize_key',
			'instagram_show_follow_btn' => 'sanitize_key',
			'instagram_show_follow_btn_where' => 'sanitize_key',
			'twitter_show_follow_btn' => 'sanitize_key',
			'twitter_show_follow_count' => 'sanitize_key',
			'twitter_show_follow_btn_where' => 'sanitize_key',
			'twitter_allow_videos' => 'sanitize_key',
			'twitter_full_width' => 'sanitize_key',
			'fts_twitter_hide_images_in_posts' => 'sanitize_key',
			'youtube_show_follow_btn' => 'sanitize_key',
			'youtube-show-follow-btn-where' => 'sanitize_key',
			'youtube_thumbs_wrap_color' => 'sanitize_hex_color'
		];

		// Sanitize Array
		foreach( $possible_setting_keys as $key  => $sanitize_callback ) {
			if ( isset( $saved_feed_options[$key] ) ) {
				$saved_feed_options[$key] = $sanitize_callback( $saved_feed_options[$key] );
			}
		}

		// If Instagram token encrypt.
		if ( isset($saved_feed_options['fts_instagram_custom_api_token']) ) {
			$saved_feed_options['fts_instagram_custom_api_token'] = $this->data_protection->encrypt( $saved_feed_options['fts_instagram_custom_api_token'] );
		}
		// If Instagram Business token encrypt.
		if ( isset($saved_feed_options['fts_facebook_instagram_custom_api_token']) ) {
			$saved_feed_options['fts_facebook_instagram_custom_api_token'] = $this->data_protection->encrypt( $saved_feed_options['fts_facebook_instagram_custom_api_token'] );
		}
		// If Facebook Business token decrypt.
		if ( isset($saved_feed_options['fts_facebook_custom_api_token']) ) {
			$saved_feed_options['fts_facebook_custom_api_token'] = $this->data_protection->encrypt( $saved_feed_options['fts_facebook_custom_api_token'] );
		}

		update_post_meta( $cpt_id, 'fts_feed_options_array', $saved_feed_options );

		wp_die();
	}
}