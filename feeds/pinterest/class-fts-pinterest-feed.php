<?php
/**
 * Feed Them Social - Pinterest Feed
 *
 * This file is used to create the Pinterest Feeds
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Class FTS Pinterest Feed
 *
 * @package feedthemsocial
 */
class FTS_Pinterest_Feed extends feed_them_social_functions {

	/**
	 * Construct
	 *
	 * Pinterest Feed constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
		add_shortcode( 'fts_pinterest', array( $this, 'fts_pinterest_board_feed' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fts_pinterest_head' ) );
	}

	/**
	 * FTS Pinterest Head
	 *
	 * Add Styles and Scripts function.
	 *
	 * @since 1.9.6
	 */
	public function fts_pinterest_head() {
		wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css' ), array(), FTS_CURRENT_VERSION );
	}

	/**
	 * FTS Pinterest Image url
	 *
	 * The image url from API
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_pinterest_image_url( $post_data ) {
		$pinterest_image_url = isset( $post_data->image->original->url ) ? $post_data->image->original->url : '';
		return $pinterest_image_url;
	}

	/**
	 * FTS Pinterest Repins Likes Wrap
	 *
	 * The repins and likes wrap
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_pinterest_repins_likes_wrap( $post_data ) {
		$wrap_start = '<div class="fts-single-pin-social-meta-wrap">';
		$repins     = isset( $post_data->counts->saves ) ? '<span class="fts-single-pin-repin-count">' . $post_data->counts->saves . '</span>' : '';
		$likes      = isset( $post_data->counts->comments ) ? '<span class="fts-single-pin-like-count">' . $post_data->counts->comments . '</span>' : '';
		$wrap_end   = '</div>';

		return $wrap_start . $repins . $likes . $wrap_end;
	}

	/**
	 * FTS Pinterest Description
	 *
	 * The Description from API
	 *
	 * @param string $post_data Post Data.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_pinterest_description( $post_data ) {

		$pinterest_description = $post_data->note;
		$pinterest_description = $this->fts_pinterest_tag_filter( $pinterest_description );
		return $pinterest_description;
	}

	/**
	 * FTS View on Pinterest Link
	 *
	 * The raw url from API
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_view_on_pinterest_link( $post_data ) {
		$pinterest_post_url = isset( $post_data->url ) ? $post_data->url : '';
		return $pinterest_post_url;
	}

	/**
	 * FTS View on Pinterest Link Wrap
	 *
	 * This includes the a tag and pinterest url
	 *
	 * @param string $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function fts_view_on_pinterest_link_wrap( $post_data ) {
		return '<a href="' . $this->fts_view_on_pinterest_url( $post_data ) . '" class="fts-view-on-pinterest-link" target="_blank">' . __( 'View on Pinterest', 'feed-them-instagram' ) . '</a>';
	}

	/**
	 * FTS Pinterest Board Feed
	 *
	 * @param array $atts Attributes.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_pinterest_board_feed( $atts ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Premium Plugin.
		if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
			include WP_CONTENT_DIR . '/plugins/feed-them-premium/feeds/pinterest/pinterest-feed.php';
		} else {
			extract(
				shortcode_atts(
					array(
						'pinterest_name' => '',
						'board_id'       => '',
						'pins_count'     => '',
						'boards_count'   => '',
						// type can equal 1 of 3 things; boards_list, single_board_pins, pins_from_user.
						'type'           => 'boards_list',
					),
					$atts
				)
			);
			if ( null === $boards_count ) {
				$boards_count = '6';
			}
			if ( null === $pins_count ) {
				$pins_count = '6';
			}
		}
		ob_start();

		// Which Display Type.
		switch ( $type ) {
			case 'pins_from_user':
				echo $this->getPins( $pinterest_name, $board_id = null, $pins_count, $type );
				break;
			case 'single_board_pins':
				echo $this->getPins( $pinterest_name, $board_id, $pins_count, $type );
				break;
			case 'boards_list':
			default:
				echo $this->getBoards( $pinterest_name, $boards_count );
				break;
		}
		return ob_get_clean();
	}
	/**
	 * Get Boards
	 *
	 * @param string $pinterest_name Pinterest Name.
	 * @param int    $boards_count Board Count.
	 * @param null   $pins_count Pins Count.
	 * @return string
	 * @since 1.9.6
	 */
	public function getBoards( $pinterest_name, $boards_count, $pins_count = null ) {

		$api_token = get_option( 'fts_pinterest_custom_api_token' );

		wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		// masonry snippet in fts-global.
		wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );

		$pinterest_show_follow_btn       = get_option( 'pinterest_show_follow_btn' );
		$pinterest_show_follow_btn_where = get_option( 'pinterest_show_follow_btn_where' );

		// Pinterest Boards Cache Folder.
		$pin_cache_boards_url = 'pin_boards_list_' . $pinterest_name . '_bnum' . $boards_count . '';
		// Pinterest Boards' Pins Cache Folder.
		$pin_cache_boards_pins_url = 'pin_boards_list_' . $pinterest_name . '_bpnum' . $boards_count . '_pnum3';

		// Get Boards.
		if ( false !== $this->fts_check_feed_cache_exists( $pin_cache_boards_url ) ) {
			$boards_returned = $this->fts_get_feed_cache( $pin_cache_boards_url );
		} else {

			$board_data['boards'] = 'https://api.pinterest.com/v1/me/boards/?access_token=' . $api_token . '';

			$boards_returned = $this->fts_get_feed_json( $board_data );
			// Create Cache.
			$this->fts_create_feed_cache( $pin_cache_boards_url, $boards_returned );
		}
		$boards = json_decode( $boards_returned['boards'] );
		// Get Boards Pins.
		if ( false !== $this->fts_check_feed_cache_exists( $pin_cache_boards_pins_url ) ) {
			$pinfo = $this->fts_get_feed_cache( $pin_cache_boards_pins_url );
		} else {
			$pinfo = $this->getPinsFromBoards( $boards, $pinterest_name, $boards_count, $pins_count );

			// Create Cache.
			$this->fts_create_feed_cache( $pin_cache_boards_pins_url, $pinfo );
		}

		$output = '';
		$count  = 0;
		$output = '<div class="fts-pinterest-wrapper">';

		// SOCIAL BUTTON.
		if ( isset( $pinterest_show_follow_btn ) && 'yes' === $pinterest_show_follow_btn && 'pinterest-follow-above' === $pinterest_show_follow_btn_where ) {
			$output .= '<div class="pinterest-social-btn-top">';
			$output .= $this->social_follow_button( 'pinterest', $pinterest_name );
			$output .= '</div>';
		}

		// Setup Boards.
		foreach ( $boards->data as $key => $board ) {
			if ( $count <= $boards_count - 1 ) {
				$board_pins = json_decode( $pinfo[ $key . 'pins' ] );

				$board_pinfo      = isset( $pinfo[ $count . 'pins' ] ) ? json_decode( $pinfo[ $count . 'pins' ] ) : '';
				$pins             = isset( $board_pinfo->data->pins ) ? $board_pinfo->data->pins : array();
				$board_pins_count = isset( $board_pinfo->data->board->counts ) ? '<div class="fts-pin-board-pin-count">' . $board_pinfo->data->board->counts . '</div>' : '';
				$output          .= '<a class="fts-pin-board-wrap" href="' . $board->url . '" target="_blank">';
				$output          .= '<div class="fts-pin-board-img-wrap" style="background-image:url(' . $board_pins->data['0']->image->original->url . ')"><span class="hoverMask">' . $board_pins_count . '</span>';
				$output          .= '</div>';
				$output          .= '<div class="fts-pin-board-thumbs-wrap">';
				// Get Thumbs for this Board.
					$number_output = 0;
				foreach ( $board_pins->data as $key => $post_data ) {
					if ( $key > 0 ) {

						$number_output++;
						$output .= '<div class="pinterest-single-thumb-wrap" style="background-image:url(' . $post_data->image->original->url . ');"><span class="hoverMask"></span></div>';
						if ( $number_output > 1 ) {
							break;
						}
					}
				}

				$output .= '</div>';
				$output .= '<h3 class="fts-pin-board-board_title"><span>' . $board->name . '</span></h3>';
				$output .= '</a>';
			}
			$count++;
		}
		$output .= '<div class="fts-clear"></div></div>';

		// SOCIAL BUTTON.
		if ( isset( $pinterest_show_follow_btn ) && 'yes' === $pinterest_show_follow_btn && 'pinterest-follow-below' === $pinterest_show_follow_btn_where ) {
			$output .= '<div class="pinterest-social-btn-bottom">';
			$output .= $this->social_follow_button( 'pinterest', $pinterest_name );
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Get Pins From Boards
	 *
	 * @param int    $boards Boards.
	 * @param string $pinterest_name Pinterest Name.
	 * @param null   $pins_count Pins Count.
	 * @return array
	 * @since 1.9.6
	 */
	public function getPinsFromBoards( $boards, $pinterest_name, $pins_count ) {

		$api_token = get_option( 'fts_pinterest_custom_api_token' );
		wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		// masonry snippet in fts-global!
		wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );

		$pins_data = array();
		foreach ( $boards->data as $key => $board ) {

			// Check if the board is full url or just a single board name!
			$board = explode( '/', $board->url );

			// Create get request and put it in the cache!
			$pins_data[ $key . 'pins' ] = 'https://api.pinterest.com/v1/boards/' . $board[3] . '/' . $board[4] . '/pins/?limit=' . $pins_count . '&access_token=' . $api_token . '&fields=image';

		}
		$pins_returned = $this->fts_get_feed_json( $pins_data );

		return $pins_returned;
	}
	/**
	 * Get Pins
	 *
	 * Get Pins from Users/Single Board.
	 *
	 * @param string $pinterest_name Pinterest name.
	 * @param string $board_id Board ID.
	 * @param int    $pins_count Pins Count.
	 * @param string $type Board type.
	 * @return string
	 * @since 1.9.6
	 */
	public function getPins( $pinterest_name, $board_id, $pins_count, $type ) {

		$api_token  = get_option( 'fts_pinterest_custom_api_token' );
		$api_points = '&fields=id%2Clink%2Cnote%2Curl%2Cattribution%2Cmetadata%2Cboard%2Ccounts%2Ccreated_at%2Ccreator%2Cimage%2Cmedia%2Coriginal_link';

		wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		// masonry snippet in fts-global.
		wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
		wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );

		$output                          = '';
		$pinterest_show_follow_btn       = get_option( 'pinterest_show_follow_btn' );
		$pinterest_show_follow_btn_where = get_option( 'pinterest_show_follow_btn_where' );
		// Pinterest Pins Cache Folder.
		$pin_cache_pins_url = 'pin_' . $type . '_' . $pinterest_name . ( ! empty( $board_id ) ? '_board' . $board_id : '' ) . ( 'single_board_pins' === $type || 'pins_from_user' === $type ? '_pnum' . $pins_count : '_unum' . $pins_count ) . '';

		// Get Boards Pins.
		if ( false !== $this->fts_check_feed_cache_exists( $pin_cache_pins_url ) ) {
			$pins_returned = $this->fts_get_feed_cache( $pin_cache_pins_url );
		} else {
			$single_board = isset( $board_id ) && ! preg_match( '/\/(.*)\/(.*)\//', $board_id ) ? '/' . $pinterest_name . '/' . $board_id . '/' : '';
			// Get Boards.
			$pins_data['pins'] = ! isset( $board_id ) ? 'https://api.pinterest.com/v1/me/pins/?limit=' . $pins_count . '&access_token=' . $api_token . $api_points : 'https://api.pinterest.com/v1/boards' . $single_board . 'pins/?limit=' . $pins_count . '&access_token=' . $api_token . $api_points;

			$pins_returned = $this->fts_get_feed_json( $pins_data );
			// Create Cache.
			$this->fts_create_feed_cache( $pin_cache_pins_url, $pins_returned );
		}

		$pins = json_decode( $pins_returned['pins'] );

		// echo'<pre>';.
		// print_r($pins);.
		// echo'</pre>';.
		// SOCIAL BUTTON.
		if ( isset( $pinterest_show_follow_btn ) && 'yes' === $pinterest_show_follow_btn && 'pinterest-follow-above' === $pinterest_show_follow_btn_where ) {
			$output .= '<div class="pinterest-social-btn-top">';
			$output .= $this->social_follow_button( 'pinterest', $pinterest_name );
			$output .= '</div>';
		}

		$output .= "<div class='fts-pinterest-wrapper fts-pins-wrapper masonry js-masonry' style='margin:0 auto' data-masonry-options='{\"itemSelector\": \".fts-single-pin-wrap\", \"isFitWidth\": true, \"transitionDuration\": 0 }'>";
		// Setup Boards.
		foreach ( $pins->data as $post_data ) {
				// Pin Display.
				$output .= '<div class="fts-single-pin-wrap">';
				$output .= '<a class="fts-single-pin-link" href="' . $this->fts_view_on_pinterest_link( $post_data ) . '" target="_blank">';
				// Pin Main Image.
				$output .= '<div class="fts-single-pin-img-wrap"><img class="fts-single-pin-cover" src="' . $this->fts_pinterest_image_url( $post_data ) . '" alt="'. __('Pinterest Photo', 'feed-them-social') . '"/></div>';
				$output .= '</a>';
				// Pin Meta wrap.
				$output .= '<div class="fts-single-pin-meta-wrap">';

				// Pin Description.
				$pinterest_description = $this->fts_pinterest_description( $post_data );

				$output .= isset( $pinterest_description ) ? '<div class="fts-single-pin-description">' . $pinterest_description . '</div>' : '';

				// Pinned To (Single Board view ONLY).
				$output .= isset( $board_id ) && ! empty( $post_data->attribution ) && ! empty( $post_data->attribution->author_url ) && ! empty( $post_data->attribution->provider_icon_url ) && ! empty( $post_data->attribution->author_name ) ? '<a class="fts-single-attribution-wrap" href="' . $post_data->attribution->author_url . '" target="_blank"><img class="fts-single-pin-attribution-icon" src="' . $post_data->attribution->provider_icon_url . '" alt="'. __('Pinterest Attribution Icon', 'feed-them-social') . '"/><div class="fts-single-pin-attribution-provider">by ' . $post_data->attribution->author_name . '</div></a>' : '';
				// Repins and likes wrap.
				$output .= $this->fts_pinterest_repins_likes_wrap( $post_data );

				$output .= '</div>';
				// Pinned To (User view ONLY).
				$output .= ! isset( $board_id ) ? '<a class="fts-single-pin-pinned-to-wrap" href="' . $post_data->board->url . '" target="_blank"><div class="fts-single-pin-pinned-to-text">Pinned onto</div><div class="fts-single-pin-pinned-to-title">' . $post_data->board->name . '</div></a>' : '';
				$output .= '</div>';
		}
		$output .= '</div><div class="fts-clear"></div>';

		// SOCIAL BUTTON.
		if ( isset( $pinterest_show_follow_btn ) && 'yes' === $pinterest_show_follow_btn && 'pinterest-follow-below' === $pinterest_show_follow_btn_where ) {
			$output .= '<div class="pinterest-social-btn-bottom">';
			$output .= $this->social_follow_button( 'pinterest', $pinterest_name );
			$output .= '</div>';
		}
		return $output;
	}

	/**
	 * FTS Pinterest Tag Filter
	 *
	 * Tags Filter (return clean tags)
	 *
	 * @param string $pinterest_description Facebook Description.
	 * @return mixed
	 * @since 1.9.6
	 */
	public function fts_pinterest_tag_filter( $pinterest_description ) {
			// Create links from @mentions and regular links.
			$pinterest_description = preg_replace( '~https?://[^<>\s]+~i', '<a href="$0" target="_blank">$0</a>', $pinterest_description );
			$pinterest_description = preg_replace( '/#+(\w+)/u', '<a href="https://www.pinterest.com/search/?q=%23$1&rs=hashtag" target="_blank">$0</a>', $pinterest_description );
			return $pinterest_description;

	}
}//end class
