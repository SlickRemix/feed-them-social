<?php
namespace feedthemsocial;
/**
 * Class FTS Vine Feed
 *
 * @package feedthemsocial
 */
class FTS_Vine_Feed extends feed_them_social_functions {

	/**
	 * Construct
	 *
	 * Vine Feed constructor.
	 *
	 * @since 1.9.6
	 */
	function __construct() {
      // ommitting these from outputting for now
	 //	add_shortcode( 'fts_vine', array( $this, 'fts_vine_func'));
	//	add_action('wp_enqueue_scripts', array( $this, 'fts_vine_head'));
	}

	/**
	 * FTS Vine Head
	 *
	 * Add Styles and Scripts functions.
	 *
	 * @since 1.9.6
     */
	function fts_vine_head() {
		wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css'));
		
		$fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
		if(isset($fts_fix_magnific) && $fts_fix_magnific !== '1'){
			wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css'));
		}
			wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js'), array( 'jquery' ));
	}

	/**
	 * FTS Vine Footer
	 *
	 * @since 1.9.6
     */
	function fts_vine_footer() {
					echo '<script src="https://platform.vine.co/static/scripts/embed.js"></script>';
	}

	/**
	 * FTS Vine Functions
	 *
	 * @param $atts
	 * @return mixed
	 * @since 1.9.6
     */
	function fts_vine_func($atts) {

		add_action( 'wp_footer', array( $this, 'fts_vine_footer'));
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		//**************************************************
		// Display Vine Feed
		//**************************************************
		// If premium active show additional options and check if the current premium vesion has the file or not too.
		if (is_plugin_active('feed-them-premium/feed-them-premium.php') && file_exists(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/twitter/vine-feed.php')) {
			include WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/twitter/vine-feed.php';
		}
		else {
			extract( shortcode_atts( array(
						'id' => '',
						'maxwidth' => '',
						'popup' => 'yes',
						'space_between_photos' => '',
						'round_thumb_corner_size' => '',
					), $atts ) );
			// omitting limit of 6		
			// $vids_count = '6';
		}
		$idNew = array();
		$idNew = explode(',', $id);
		$type = 'vineFeed';

		ob_start();

		//   echo '<pre>';
		//   print_r($idNew);
		//   echo '</pre>';

		echo '<div class="fts-vine-wrapper">';
		$set_zero = 0;
		// NOTE: have to loop the json decode and all because be we cannot get array of more than one vine video at a time from vines api at the moment.
		foreach ($idNew as $idF) {
			
			// omitting limit of 6		
			// if (isset($set_zero) && $set_zero == $vids_count)
			//	break;
			
			$randomString =  trim($this->rand_string_vine(10).'_'.$type);
			$vine_url['id'] = 'https://vine.co/oembed.json?id='.$idF.'';
			$vine_data = $this->fts_get_feed_json($vine_url);
			$vine_videos_final = json_decode($vine_data['id']);
			// Vine video wrapper that repeats so we can show a gallery of videos with next and previous buttons
			echo '<div class="fts-vine-video-wrap popup-gallery-vine" style="max-width:'.$maxwidth.'; margin:'.$space_between_photos.';">';
			// The content of the vine video which contains the thumbnail, logo author title and text.
			echo '<div class="fts-vine-content">';
			echo '<a href="#test-popup-'.$randomString.'" class="fts-vine-thumbnail" target="_blank" style="max-height:'.$maxwidth.';">';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($show_vine_logo) && $show_vine_logo == 'no') {}
			else {
				// Vine logo that appears in the right top corner of the thumbnail
				echo '<span class="fts-vine-logo"';
			 // Action for premium version to change the logo size
				if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($vine_logo_size)) {
					echo $vine_logo_size !== '' ? 'style="font-size:'.$vine_logo_size.'"' : "" ;
				}
				echo '></span>';
			}
			// black screen cover that appears on hover
			echo '<span class="fts-vine-thumbnail-cover"';
			echo isset($round_thumb_corner_size) && $round_thumb_corner_size !== '' ? 'style="border-radius:'.$round_thumb_corner_size.'">' : ">" ;
			echo '</span>';
			echo '<img src="'.$vine_videos_final->thumbnail_url.'"';
			echo isset($round_thumb_corner_size) && $round_thumb_corner_size !== '' ? 'style="border-radius:'.$round_thumb_corner_size.'"' : "" ;
			echo "/></a>";
			// Action for premium version to show or hide the text
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && isset($show_text) && $show_text == 'no') {}
			else {
				// Author name and url to vine
				echo '<a href="'.$vine_videos_final->author_url.'" target="_blank" class="fts-vine-author">'.$vine_videos_final->author_name.'</a>';
				// Video description
				echo '<div class="fts-vine-text">';
				echo $vine_videos_final->title;
				echo '</div>';
			}
			echo '</div>';
			// This is the popup with Vine video, it has a dynamic string in the ID so we can show multiple popups and that they corospond with the proper thumbnail.
			echo '<div id="test-popup-'.$randomString.'" class="fts-vine-white-popup mfp-hide">';
			echo '<div class="fts-fluid-videoWrapper fts-iframe-vine"><iframe src="https://vine.co/v/'.$idF.'/embed/simple" frameborder="0"></iframe></div>';
			// this is just a quick start. I konw we cant count the total of items using count($set_zero), just setup for later if this takes off.
			// $slickCustomCounter = $set_zero + 1;
			// echo '<div class="slick-custom-counter">'.$slickCustomCounter.' of '.count($set_zero) .'</div>';
			echo '</div>';
			
			echo '</div>'; // end fts-vine-video-wrap popup-gallery-vine
			if (isset($set_zero)) {
				$set_zero++;
			}
		}// end for each idNew
		
		echo '</div>'; // end for each fts-vine-wrapper
		return ob_get_clean();
	}//END ELSE

	/**
	 * Random String Vine
	 *
	 * @param int $length
	 * @return string
	 * @since 1.9.6
     */
	function rand_string_vine($length = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}// FTS_Vine_Feed END CLASS
?>