<?php
/**
 * Feed Them Social - Twitter Feed
 *
 * This page is used to create the Twitter feed!
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2022, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

/**
 * Twitter Feed Class
 */
class Twitter_Feed {

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
	 * Construct
	 * Added Since 9/28/2016 https://dev.twitter.com/overview/api/upcoming-changes-to-tweets
	 *
	 * Twitter Feed constructor.
	 *
	 * @since 1.9.6
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

		add_action( 'wp_enqueue_scripts', array( $this, 'twitter_head' ) );
		add_action( 'wp_ajax_nopriv_fts_twitter_share_url_check', array( $this, 'fts_twitter_share_url_check' ) );
		add_action( 'wp_ajax_fts_twitter_share_url_check', array( $this, 'fts_twitter_share_url_check' ) );
	}

    /**
	 * FTS Twitter Head
	 *
	 * Add Styles and Scripts functions.
	 *
	 * @since 2.9.6.5
	 */
    public function twitter_head() {
        // no actions or filters to load at this time.
    }

	/**
	 * Load Videos
	 *
	 * @param array $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function load_videos( $post_data ) {

		if ( isset( $post_data->quoted_status->entities->media[0]->type ) ) {
			$twitter_final = isset( $post_data->quoted_status->entities->media[0]->expanded_url ) ? $post_data->quoted_status->entities->media[0]->expanded_url : '';
		} else {
			$twitter_final = isset( $post_data->entities->urls[0]->expanded_url ) ? $post_data->entities->urls[0]->expanded_url : '';
		}

		// strip Vimeo URL then output iframe.
		if ( strpos( $twitter_final, 'vimeo' ) > 0 ) {
			if ( strpos( $twitter_final, 'staffpicks' ) > 0 ) {
				$parsed_url      = $twitter_final;
				$parsed_url      = parse_url( $parsed_url );
				$vimeo_url_final = preg_replace( '/\D/', '', $parsed_url['path'] );
			} else {
				$vimeo_url_final = (int) substr( parse_url( $twitter_final, PHP_URL_PATH ), 1 );
			}
			return '<div class="fts-fluid-videoWrapper"><iframe src="' . esc_url( 'https://player.vimeo.com/video/' . $vimeo_url_final . '?autoplay=0' ) . '" class="video" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
		} elseif (
			// strip Vimeo Staffpics URL then ouput Iframe.
			strpos( $twitter_final, 'youtube' ) > 0 && ! strpos( $twitter_final, '-youtube' ) > 0 ) {
			$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
			preg_match( $pattern, $twitter_final, $matches );
			$youtube_url_final = $matches[1];

			return '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="' . esc_url( 'https://www.youtube.com/embed/' . $youtube_url_final . '?autoplay=0') . '" frameborder="0" allowfullscreen></iframe></div>';
		} elseif (
			// strip YouTube URL then ouput Iframe and script.
			strpos( $twitter_final, 'youtu.be' ) > 0 ) {
			$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
			preg_match( $pattern, $twitter_final, $matches );
			$youtube_url_final = $matches[1];
			return '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="'. esc_url( 'https://www.youtube.com/embed/' . $youtube_url_final . '?autoplay=0' ) . '" frameborder="0" allowfullscreen></iframe></div>';
		} elseif (
			// strip YouTube URL then ouput Iframe and script.
			strpos( $twitter_final, 'soundcloud' ) > 0 ) {

			// Get the JSON data of song details with embed code from SoundCloud oEmbed.
			$get_values = wp_remote_get( 'https://soundcloud.com/oembed?format=js&url=' . $twitter_final . '&auto_play=false&iframe=true' );

			// Clean the Json to decode.
			$decode_iframe = substr( $get_values, 1, -2 );

			// json decode to convert it as an array.
			$json_object = json_decode( $decode_iframe );

			/**
			 * DC: Would wp_kses work here?
             * SRL: possibly, have to look deeper into the html structure.
             * might not be best approach since 3rd party could always make changes.
			 */
			return '<div class="fts-fluid-videoWrapper">' . $json_object->html . '</div>';
		} else {

			// START VIDEO POST.
			// Check through the different video options available. For some reason the variants which are the actual video urls vary at times in quality so we are going to shoot for 4 first then 2, 3 and 1.
			if ( isset( $post_data->extended_entities->media[0]->video_info->variants[4]->content_type ) && 'video/mp4' === $post_data->extended_entities->media[0]->video_info->variants[4]->content_type ) {
				$twitter_final = isset( $post_data->extended_entities->media[0]->video_info->variants[4]->url ) ? $post_data->extended_entities->media[0]->video_info->variants[4]->url : '';
			} elseif ( isset( $post_data->extended_entities->media[0]->video_info->variants[2]->content_type ) && 'video/mp4' === $post_data->extended_entities->media[0]->video_info->variants[2]->content_type ) {
				$twitter_final = isset( $post_data->extended_entities->media[0]->video_info->variants[2]->url ) ? $post_data->extended_entities->media[0]->video_info->variants[2]->url : '';
			} elseif ( isset( $post_data->extended_entities->media[0]->video_info->variants[3]->content_type ) && 'video/mp4' === $post_data->extended_entities->media[0]->video_info->variants[3]->content_type ) {
				$twitter_final = isset( $post_data->extended_entities->media[0]->video_info->variants[3]->url ) ? $post_data->extended_entities->media[0]->video_info->variants[3]->url : '';
			} elseif ( isset( $post_data->extended_entities->media[0]->video_info->variants[1]->content_type ) && 'video/mp4' === $post_data->extended_entities->media[0]->video_info->variants[1]->content_type ) {
				$twitter_final = isset( $post_data->extended_entities->media[0]->video_info->variants[1]->url ) ? $post_data->extended_entities->media[0]->video_info->variants[1]->url : '';
			}

			// The only difference in these lines is the "retweeted_status" These are twitter videos from Tweet link people post, the ones above are direct videos users post to there timeline.
			elseif ( isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[4]->content_type ) && 'video/mp4' === $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[4]->content_type ) {
				$twitter_final = isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[4]->url ) ? $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[4]->url : '';
			} elseif ( isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[2]->content_type ) && 'video/mp4' === $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[2]->content_type ) {
				$twitter_final = isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[2]->url ) ? $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[2]->url : '';
			} elseif ( isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[3]->content_type ) && 'video/mp4' === $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[3]->content_type ) {
				$twitter_final = isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[3]->url ) ? $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[3]->url : '';
			} elseif ( isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[1]->content_type ) && 'video/mp4' === $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[1]->content_type ) {
				$twitter_final = isset( $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[1]->url ) ? $post_data->retweeted_status->extended_entities->media[0]->video_info->variants[1]->url : '';
			}

			// The only difference in these lines is the "quoted_status" These are twitter videos from Tweet link people post, the ones above are direct videos users post to there timeline.
			elseif ( isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[4]->content_type ) && 'video/mp4' === $post_data->quoted_status->extended_entities->media[0]->video_info->variants[4]->content_type ) {
				$twitter_final = isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[4]->url ) ? $post_data->quoted_status->extended_entities->media[0]->video_info->variants[4]->url : '';
			} elseif ( isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[2]->content_type ) && 'video/mp4' === $post_data->quoted_status->extended_entities->media[0]->video_info->variants[2]->content_type ) {
				$twitter_final = isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[2]->url ) ? $post_data->quoted_status->extended_entities->media[0]->video_info->variants[2]->url : '';
			} elseif ( isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[3]->content_type ) && 'video/mp4' === $post_data->quoted_status->extended_entities->media[0]->video_info->variants[3]->content_type ) {
				$twitter_final = isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[3]->url ) ? $post_data->quoted_status->extended_entities->media[0]->video_info->variants[3]->url : '';
			} elseif ( isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[1]->content_type ) && 'video/mp4' === $post_data->quoted_status->extended_entities->media[0]->video_info->variants[1]->content_type ) {
				$twitter_final = isset( $post_data->quoted_status->extended_entities->media[0]->video_info->variants[1]->url ) ? $post_data->quoted_status->extended_entities->media[0]->video_info->variants[1]->url : '';
			}

			// Check to see if there is a poster image available.
			if ( isset( $post_data->extended_entities->media[0]->media_url_https ) ) {

				$twitter_final_poster = isset( $post_data->extended_entities->media[0]->media_url_https ) ? $post_data->extended_entities->media[0]->media_url_https : '';
			} elseif ( isset( $post_data->quoted_status->extended_entities->media[0]->media_url_https ) ) {

				$twitter_final_poster = isset( $post_data->quoted_status->extended_entities->media[0]->media_url_https ) ? $post_data->quoted_status->extended_entities->media[0]->media_url_https : '';
			} elseif ( isset( $post_data->retweeted_status->extended_entities->media[0]->media_url_https ) ) {

				$twitter_final_poster = isset( $post_data->retweeted_status->extended_entities->media[0]->media_url_https ) ? $post_data->retweeted_status->extended_entities->media[0]->media_url_https : '';
			}

			$fts_twitter_output = '<div class="fts-jal-fb-vid-wrap">';

			// This line is here so we can fetch the source to feed into the popup since some html 5 videos can be displayed without the need for a button.
			$fts_twitter_output .= '<a href="' . esc_url( $twitter_final ) . '" style="display:none !important" class="fts-facebook-link-target fts-jal-fb-vid-image fts-video-type"></a>';
			$fts_twitter_output .= '<div class="fts-fluid-videoWrapper-html5">';
			$fts_twitter_output .= '<video controls poster="' . esc_attr( $twitter_final_poster ) . '" width="100%;" style="max-width:100%;">';
			$fts_twitter_output .= '<source src="' . esc_url( $twitter_final ) . '" type="video/mp4">';
			$fts_twitter_output .= '</video>';
			$fts_twitter_output .= '</div>';

			$fts_twitter_output .= '</div>';

			// REMOVING THIS TWITTER VID OPTION TILL WE GET SOME ANSWERS.
			// https://twittercommunity.com/t/twitter-statuses-oembed-parameters-not-working/105868.
			// https://stackoverflow.com/questions/50419158/twitter-statuses-oembed-parameters-not-working.
			// return '<div class="fts-fluid-videoWrapper"><iframe src="' . $twitter_final_video . '" class="video" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';.
			// echo $twitter_final;.

			return $fts_twitter_output;
		}
		// end main else.
	}
	// end function.

	/**
	 * Description
	 *
     * The description text.
     *
	 * @param array $post_data Post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function description( $post_data ) {
		$text = isset( $post_data->retweeted_status->full_text ) ? $post_data->retweeted_status->full_text : $post_data->full_text;

		// Message. Convert links to real links.
		$pattern   = array( '/http:(\S)+/', '/https:(\S)+/', '/@+(\w+)/u', '/#+(\w+)/u' );
		$replace   = array( ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="https://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="https://twitter.com/hashtag/$1?src=hash" target="_blank" rel="nofollow">#$1</a>' );
		$full_text = preg_replace( $pattern, $replace, $text );

		return nl2br( $full_text );
	}

	/**
	 * Quote Description
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function tweet_quote_description( $post_data ) {

		$text = $post_data->quoted_status->full_text;

		// Message. Convert links to real links.
		$pattern   = array( '/http:(\S)+/', '/https:(\S)+/', '/@+(\w+)/u', '/#+(\w+)/u' );
		$replace   = array( ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="https://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="https://twitter.com/hashtag/$1?src=hash" target="_blank" rel="nofollow">#$1</a>' );
		$full_text = preg_replace( $pattern, $replace, $text );

		return nl2br( $full_text );
	}







    /**
	 * FTS Twitter Quoted Wrap
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 2.9.6.5
	 */
	public function fts_twitter_share_url_check(){

	     // Verify Nonce Security.
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['fts_security'] ) ) , sanitize_text_field( wp_unslash( $_REQUEST['fts_time'] ) ) . 'fts-twitter-nonce' ) ) {
            exit( 'Sorry, You can\'t do that!' );
        }

        $twitter_external_url = $_REQUEST['fts_url'];
	    $no_video_image_check = $_REQUEST['fts_no_video_image'];
	    $fts_popup = $_REQUEST['fts_popup'];

	            // echo ' test ';
                // A regular user posted photo or video is not allowed to pass here.
                if( 'true' === $no_video_image_check ){

                     // There are 2 Types:
                     // 1. Twitter card data. ie twitter:title, twitter:description, twitter:image.
                     // 2. Site does not have Twitter info, so we can get the og:title, og:description, og:image
                     // If 1 or 2 are not found then we return nothing.

                     // FYI sometimes get_meta_tags will not work because a website will block it's usage.
                     $tags = get_meta_tags( $twitter_external_url );
                     // First try and us the get_meta_tags php function because this is quicker
                     // Otherwise we use preg_match to find what we need from the <meta properties"og:image" for example.
                     // More exceptions might need to be created but this is what's been done so far...
                     if( !empty( $tags['twitter:image'] ) && true == $tags['twitter:image'] || !empty( $tags['twitter:image:src'] ) && true == $tags['twitter:image:src'] ){

                         $property_image_url = isset( $tags['twitter:image:src'] ) && true == $tags['twitter:image:src'] ? $tags['twitter:image:src'] : $tags['twitter:image'];
                         $property_image =  true == $property_image_url ? $property_image_url : '';
                         $property_title =  true == $tags['twitter:title'] ? $tags['twitter:title'] :'';
                         $property_description =  true == $tags['twitter:description'] ? $tags['twitter:description'] : '';
                         // echo ' twitter-card ';
                     }
                     else {
                        // error_log( ' og image ' );
                        // echo ' og image ';

                        $html = $this->feed_functions->fts_get_feed_json( $twitter_external_url );
                        if( !empty( $html['data'] ) ){
                            // Try curl
                            $html = $html['data'];
                        }
                        elseif( ini_get('allow_url_fopen') ){
                            // If curl fails try file get contents
                            $html = file_get_contents( $twitter_external_url );
                        }

                        // The first 2 are preg_match_all with single quotes '', the second 2 are with double quotes "". We have to check for both.
                        // og:image
                        if( true == preg_match_all( "/ content='(.*?)' property='og:image'/", $html, $matches ) ) {
                            $property_image =  $matches[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( "/ property='og:image' content='(.*?)'/", $html, $matches )  ){
                            $property_image =  $matches[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ property="og:image" content="(.*?)"/', $html, $matches ) ){
                            $property_image =  $matches[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ content="(.*?)" property="og:image"/', $html, $matches )  ){
                            $property_image =  $matches[ 1 ][ 0 ];
                        }
                        else {
                            $property_image = '';
                        }

                         // og:title
                        if(  true == preg_match_all( "/ content='(.*?)' property='og:title'/", $html, $matches2 ) ){
                            $property_title = $matches2[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( "/ property='og:title' content='(.*?)'/", $html, $matches2 )  ){
                            $property_title = $matches2[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ property="og:title" content="(.*?)"/', $html, $matches2 ) ){
                            $property_title = $matches2[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ content="(.*?)" property="og:title"/', $html, $matches2 )  ){
                            $property_title = $matches2[ 1 ][ 0 ];
                        }
                        else {
                            $property_title = '';
                        }

                         // og:description
                        if(  true == preg_match_all( "/ content='(.*?)' property='og:description'/", $html, $matches3 ) ){
                            $property_description = $matches3[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( "/ property='og:description' content='(.*?)'/", $html, $matches3 )  ){
                            $property_description = $matches3[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ property="og:description" content="(.*?)"/', $html, $matches3 ) ){
                            $property_description = $matches3[ 1 ][ 0 ];
                        }
                        elseif ( true == preg_match_all( '/ content="(.*?)" property="og:description"/', $html, $matches3 )  ){
                            $property_description = $matches3[ 1 ][ 0 ];
                        }
                        else {
                            $property_description = '';
                        }
                }
		}

	    $twitter_info = array(
	            'post_url'   => $twitter_external_url,
	            'image' => $property_image,
	            'title' => $property_title,
	            'description' => $property_description,
	            'popup' => $fts_popup,
	            // adding this so when we check the ajax data output we have an error message so we can do something if
	            // and image, title or description are not present. If so then we will not show the external link content in the post.
	            'error' => 'missing_info',
	    );

	   if( !empty( $twitter_info[ 'image' ] ) && !empty( $twitter_info[ 'title' ] ) && !empty( $twitter_info[ 'description' ] ) ){
	        echo $this->fts_twitter_external_link_wrap( $twitter_info );
	   }
	   else {
	       echo $twitter_info[ 'error' ];
	   }

        wp_die();

	}

	/**
	 * FTS Twitter Quote Tweet Wrap
	 *
     * This occurs when a user retweets a tweet and ads a quote along with it.
     * It's an option you can choose when you click the retweet icon on twitter.com on any tweet.
     *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 2.9.6.5
	 */
	public function fts_twitter_quoted_wrap( $post_data, $popup, $hide_images_in_posts ) {

	    $post_url = $post_data->quoted_status_permalink->expanded;
	    $username = $post_data->quoted_status->user->name;
	    $screen_name = $post_data->quoted_status->user->screen_name;

	    $twitter_image = $this->tweet_image( $post_data, $popup, $hide_images_in_posts );
	    $twitter_image_class = isset( $twitter_image ) ? 'fts-twitter-image-visible' : 'fts-twitter-no-image';

	    echo '<div class="fts-twitter-quoted-text-wrap fts-twitter-quote">';

	        echo $twitter_image;

            echo '<div class="fts-twitter-quoted-text fts-quote-tweet ' . esc_html( $twitter_image_class ) . '">';

                echo '<a href="' . esc_url( $post_url ) . '" target="_blank">';

	                echo '<span class="fts-twitter-full-name">'. esc_html( $username ) . '</span>';
	                echo '<svg viewBox="0 0 24 24" aria-label="Verified account" class="r-1cvl2hr r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-f9ja8p r-og9te1 r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>';
            	    echo '<span class="fts-twitter-at-name">@' . esc_html( $screen_name ) . '</span><br/>';

                    echo '<span class="fts-twitter-quoted-description">';
                       echo \wp_kses(
                           $this->tweet_quote_description( $post_data ),
                             array(
                                  'br'     => array(),
                                  'em'     => array(),
                                  'strong' => array(),
                                  'small'  => array(),
                                 )
                             );
                    echo '</span>';
                echo '</a>';

            echo '</div>';
       echo ' </div>';
	}


	/**
	 * FTS Twitter Quoted Wrap
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 2.9.6.5
	 */
	public function fts_twitter_retweeted_wrap( $post_data, $popup, $hide_images_in_posts ) {

	    $post_url = $post_data->quoted_status_permalink->expanded;
	    $username = $post_data->retweeted_status->quoted_status->user->name;
	    $screen_name = $post_data->retweeted_status->quoted_status->user->screen_name;

	    $twitter_image = $this->tweet_image( $post_data, $popup, $hide_images_in_posts );
	    $twitter_image_class = 'fts-twitter-image-visible';

            if( !empty( $username ) ){

                echo '<div class="fts-twitter-quoted-text-wrap fts-twitter-quote">';

                    echo $twitter_image;

                            echo '<div class="fts-twitter-quoted-text fts-retweet-quote ' . esc_html( $twitter_image_class ) . '">';

                                echo '<a href="' . esc_url( $post_url ) . '" target="_blank">';

                                    echo '<span class="fts-twitter-full-name">'. esc_html( $username ) . '</span>';
                                    echo '<svg viewBox="0 0 24 24" aria-label="Verified account" class="r-1cvl2hr r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-f9ja8p r-og9te1 r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>';
                                    echo '<span class="fts-twitter-at-name">@' . esc_html( $screen_name ) . '</span><br/>';

                                    echo '<span class="fts-twitter-quoted-description">';
                                       echo \wp_kses(
                                           $this->tweet_quote_description( $post_data ),
                                             array(
                                                  'br'     => array(),
                                                  'em'     => array(),
                                                  'strong' => array(),
                                                  'small'  => array(),
                                                 )
                                             );
                                    echo '</span>';
                                echo '</a>';

                            echo '</div>';
               echo ' </div>';

            }
            else {
                    echo $twitter_image;
            }
	}

	/**
	 * FTS Twitter External Link Wrap
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 2.9.6.5
	 */
	public function fts_twitter_external_link_wrap( $details ) {

	    $post_url = $details['post_url'];
	    $title = $details['title'];
	    $description = $details['description'];

	    echo '<div class="fts-twitter-quoted-text-wrap fts-twitter-quote">';

	        echo $this->tweet_image( '', $details, '' );

            echo '<div class="fts-twitter-quoted-text fts-external-link-specific">';

                echo '<a href="' . esc_url( $post_url ) . '" target="_blank">';

                    $domain = wp_parse_url( $post_url );
                    $domain = str_replace('www.','', $domain['host'] );

                    echo '<span class="fts-twitter-domain-name">'. $domain . '</span>';

                    echo '<span class="fts-twitter-quoted-title">' . $title . '</span>';

                    echo '<span class="fts-twitter-quoted-description">';
                        // This description comes from the external url getting the meta og:description.
                       echo \wp_kses(
                           $description,
                             array(
                                  'br'     => array(),
                                  'em'     => array(),
                                 'strong' => array(),
                                 'small'  => array(),
                                 )
                             );
                    echo '</span>';
                echo '</a>';

            echo '</div>';
       echo ' </div>';

	}






	/**
	 * Tweet Image
	 *
	 * @param string $post_data The post data.
	 * @param string $popup Our Custom popup.
	 * @return string
	 * @since 1.9.6
	 */
	public function tweet_image( $post_data, $popup, $hide_images_in_posts ) {

		$screen_name = isset( $post_data->user->screen_name ) ?  $post_data->user->screen_name : '';
		$post_id = isset( $post_data->id ) ?  $post_data->id : '';
		$permalink                        = 'https://twitter.com/' . $screen_name . '/status/' . $post_id;
		$twitter_video_extended = isset( $post_data->extended_entities->media[0]->type ) ? $post_data->extended_entities->media[0]->type : '';

		if ( ! empty( $post_data->entities->media[0]->media_url ) && 'video' !== $twitter_video_extended ) {
			$media_url = $post_data->entities->media[0]->media_url_https;
		} elseif ( ! empty( $post_data->retweeted_status->entities->media[0]->media_url_https ) ) {
			$media_url = $post_data->retweeted_status->entities->media[0]->media_url_https;
		} elseif ( ! empty( $post_data->quoted_status->entities->media[0]->media_url_https ) ) {
			$media_url = $post_data->quoted_status->entities->media[0]->media_url_https;
			$media_addition = 'yes';
		} elseif ( ! empty( $post_data->retweeted_status->quoted_status->entities->media[0]->media_url_https ) ) {
			$media_url = $post_data->retweeted_status->quoted_status->entities->media[0]->media_url_https;
			$media_addition = 'yes';
		} elseif ( is_array( $popup ) ) {
		    // This is specific to function fts_twitter_external_link_wrap. This is the only function where we pass an array through the $popup string.
	        $details_array = (object)$popup;
            $popup = $details_array->popup;
            $permalink = $details_array->post_url;
            $media_url = $details_array->image;
			$media_addition = 'yes';
		} else {
			$media_url = '';
		}
        // small check to see if popup is yes if so then show the medial url instead of the permalink.
		$permalink = 'yes' === $popup ? $media_url : $permalink;
        // final check to see of popop equals yes and if so add our custom class so we know it needs to open in popup.
		$popup = 'yes' === $popup ? 'fts-twitter-link-image' : '';

		if ( ! empty( $media_url ) && isset( $hide_images_in_posts ) && 'yes' !== $hide_images_in_posts ) {
			if ( isset( $media_addition ) && 'yes' === $media_addition ){

			     return '<a href="' . esc_url( $permalink ) . '" class="' . esc_attr( $popup ) . '" target="_blank"><span class="fts-twitter-external-backg-image" style="background-image:url( ' . esc_url( $media_url ) . ' );"><img src="' . esc_url( $media_url ) . '" class="fts-og-image" alt="' . esc_attr( $screen_name ) . ' photo" /></span></a>';
			}
			else {
				 return '<a href="' . esc_url( $permalink ) . '" class="' . esc_attr( $popup ) . '" target="_blank"><img class="fts-twitter-description-image" src="' . esc_url( $media_url ) . '" alt="' .  esc_attr( $screen_name ) . ' photo"/></a>';
			}
		}
	}

	/**
	 * Tweet Permalink
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function tweet_permalink( $post_data ) {
		$permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;

		return '<div class="fts-tweet-reply-left"><a href="' . esc_url( $permalink ) . '" target="_blank" title="Reply" aria-label="Reply"><div class="fts-twitter-reply-feed">


<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="external-link" class="svg-inline--fa fa-external-link fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M440,256H424a8,8,0,0,0-8,8V464a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V112A16,16,0,0,1,48,96H248a8,8,0,0,0,8-8V72a8,8,0,0,0-8-8H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V264A8,8,0,0,0,440,256ZM500,0,364,.34a12,12,0,0,0-12,12v10a12,12,0,0,0,12,12L454,34l.7.71L131.51,357.86a12,12,0,0,0,0,17l5.66,5.66a12,12,0,0,0,17,0L477.29,57.34l.71.7-.34,90a12,12,0,0,0,12,12h10a12,12,0,0,0,12-12L512,12A12,12,0,0,0,500,0Z"></path></svg></div></a></div>';
	}

	/**
	 * Retweet Count
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function retweet_count( $post_data ) {
        // Retweet count.
        $retweet_count = isset( $post_data->retweet_count ) && '0' !== $post_data->retweet_count ? $post_data->retweet_count : '';

		return '<a href="' . esc_html( 'https://twitter.com/intent/retweet?tweet_id=' . $post_data->id . '&related=' . $post_data->user->screen_name ) . '" target="_blank" class="fts-twitter-retweet-wrap" title="' . esc_attr( 'Retweet', 'feed-them-social' ) . '" aria-label="' . esc_attr( 'Retweet', 'feed-them-social' ) . '"><div class="fts-twitter-retweet-feed">
<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="retweet" class="svg-inline--fa fa-retweet fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M634.828 363.799l-98.343 98.343c-4.686 4.686-12.284 4.686-16.971 0l-98.343-98.343c-4.686-4.686-4.686-12.284 0-16.971l5.656-5.656c4.686-4.686 12.284-4.686 16.971 0l68.202 68.2V128H260.024a11.996 11.996 0 0 1-8.485-3.515l-8-8c-7.56-7.56-2.206-20.485 8.485-20.485H520c13.255 0 24 10.745 24 24v289.372l68.201-68.201c4.686-4.686 12.284-4.686 16.971 0l5.656 5.656c4.686 4.687 4.686 12.285 0 16.972zm-246.367 23.716a12.002 12.002 0 0 0-8.485-3.515H128V102.628l68.201 68.2c4.686 4.686 12.284 4.686 16.97 0l5.657-5.657c4.686-4.686 4.687-12.284 0-16.971l-98.343-98.343c-4.686-4.686-12.284-4.686-16.971 0L5.172 148.201c-4.686 4.686-4.686 12.285 0 16.971l5.657 5.657c4.686 4.686 12.284 4.686 16.97 0L96 102.628V392c0 13.255 10.745 24 24 24h267.976c10.691 0 16.045-12.926 8.485-20.485l-8-8z"></path></svg>' . esc_html( $retweet_count ) . '</div></a>';
	}

	/**
	 * Favorite Count
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function favorite_count( $post_data ) {
        // Favorite count.
        $favorite_count = isset( $post_data->favorite_count ) && '0' !== $post_data->favorite_count ? $post_data->favorite_count : '';

		return '<a href="' . esc_html( 'https://twitter.com/intent/like?tweet_id=' . $post_data->id . '&related=' . $post_data->user->screen_name ) . '" target="_blank" class="fts-twitter-favorites-wrap" title="' . esc_attr( 'Favorite', 'feed-them-social' ) . '" aria-label="' . esc_attr( 'Favorite', 'feed-them-social' ) . '"><div class="fts-twitter-favorites-feed">
<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="heart" class="svg-inline--fa fa-heart fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M462.3 62.7c-54.5-46.4-136-38.7-186.6 13.5L256 96.6l-19.7-20.3C195.5 34.1 113.2 8.7 49.7 62.7c-62.8 53.6-66.1 149.8-9.9 207.8l193.5 199.8c6.2 6.4 14.4 9.7 22.6 9.7 8.2 0 16.4-3.2 22.6-9.7L472 270.5c56.4-58 53.1-154.2-9.7-207.8zm-13.1 185.6L256.4 448.1 62.8 248.3c-38.4-39.6-46.4-115.1 7.7-161.2 54.8-46.8 119.2-12.9 142.8 11.5l42.7 44.1 42.7-44.1c23.2-24 88.2-58 142.8-11.5 54 46 46.1 121.5 7.7 161.2z"></path></svg>' . esc_html( $favorite_count ) . '</div></a>';
	}

    /**
	 * Feed Fetch Errors Check
	 *
	 * @param string $post_data The post data.
	 * @return string
	 * @since 1.9.6
	 */
	public function feed_fetch_error_check( $post_data ) {
        // Favorite count.
        $favorite_count = isset( $post_data->favorite_count ) && '0' !== $post_data->favorite_count ? $post_data->favorite_count : '';

		return '<a href="' . esc_html( 'https://twitter.com/intent/like?tweet_id=' . $post_data->id . '&related=' . $post_data->user->screen_name ) . '" target="_blank" class="fts-twitter-favorites-wrap" title="' . esc_attr( 'Favorite', 'feed-them-social' ) . '" aria-label="' . esc_attr( 'Favorite', 'feed-them-social' ) . '"><div class="fts-twitter-favorites">' . esc_html( $favorite_count ) . '</div></a>';
	}

     /**
     * Twitter Custom Styles
     *
     * Custom Styles for feed in a shortcode.
     *
     * @param string $a First Date.
     * @return false|int
     * @since 4.0
     */
    public function twitter_custom_styles( $feed_post_id ) {

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
            .fts-twitter-div {
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
            .fts-twitter-link-image {
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
	 * Display Twitter
	 *
	 * Display Twitter Feed.
	 *
	 * @param integer $feed_post_id The ID of the Feed's CPT Post.
	 * @return array
	 * @since 1.9.6
	 */
	public function display_twitter( $feed_post_id ) {
            // SRL: this needs attention on because this will not load proper on block based themes. ie* 2022, 2020 WordPress Themes.
            // Here is a fix reference. https://wordpress.org/support/topic/issue-with-wp_localize_script-or-similar-on-block-themes/
            wp_localize_script( 'fts-global-js', 'fts_twitter_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            // Saved Feed Settings!
            $saved_feed_options = $this->feed_functions->get_saved_feed_options( $feed_post_id );

            // Get our Additional Options.
            $this->twitter_custom_styles( $feed_post_id );

             /*echo'<pre>';
             print_r($saved_feed_options);
             echo'</pre>';*/

            // Show Follow Button.
            $twitter_show_follow_btn       = $saved_feed_options['twitter_show_follow_btn'];

            // Location of Show Follow Button.
            $twitter_show_follow_btn_where = $saved_feed_options['twitter_show_follow_btn_where'];

            // Show Follow Button Count
            $twitter_show_follow_count     = $saved_feed_options['twitter_show_follow_count'];

             // Twitter Username!
            $twitter_name = $saved_feed_options['twitter_name'] ?? '';

            // Tweets Count!
            $tweets_count = $saved_feed_options['tweets_count'] ?? '6';

            // Twitter Height!
            $twitter_height = $saved_feed_options['twitter_height'] ?? '';

             // Description Image!
            $description_image = $saved_feed_options['description_image'] ?? '';

            // Search!
            $search = $saved_feed_options['twitter_hashtag_etc_name'] ?? '';

            // Feed Type!
            $type = $saved_feed_options['twitter-messages-selector'] ?? '';

            // Show Retweets!
            $show_retweets = $saved_feed_options['twitter_show_retweets'] ?? '';

            // Show Replies!
            $show_replies = $saved_feed_options['twitter_show_replies'] ?? '';

            // Cover Photo!
            $cover_photo = $saved_feed_options['twitter_cover_photo'] ?? '';

            // Stats Bar!
            $stats_bar = $saved_feed_options['twitter_stats_bar'] ?? '';

            $twitter_hide_profile_photo          = $saved_feed_options['twitter_hide_profile_photo'] ?? '';

            $hide_images_in_posts                = $saved_feed_options['twitter_hide_images_in_posts'] ?? '';

            $popup_check                         = $saved_feed_options['twitter_popup_option'] ?? '';

			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			// option to allow this action or not from the Twitter Options page.
			if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {

				$loadmore                    = $saved_feed_options['twitter_load_more_option'] ?? '';
				$loadmore_style              = $saved_feed_options['twitter_load_more_style'] ?? '';
                $loadmore_btn_margin         = $saved_feed_options['twitter_loadmore_button_margin'] ?? '';
                $loadmore_btn_maxwidth       = $saved_feed_options['twitter_loadmore_button_width'] ?? '';
                $grid                        = $saved_feed_options['twitter_grid_option'] ?? '';
                $column_width                = $saved_feed_options['twitter_grid_column_width'] ?? '';
                $space_between_posts         = $saved_feed_options['twitter_grid_space_between_posts'] ?? '';
				$twitter_load_more_text      = $saved_feed_options['twitter_load_more_text'] ?? __( 'Load More', 'feed-them-social' );
				$twitter_no_more_tweets_text = $saved_feed_options['twitter_no_more_tweets_text'] ??  __( 'No More Tweets', 'feed-them-social' );

				if ( isset( $popup_check ) && $popup_check === 'yes' ) {
					$fts_fix_magnific = $this->settings_functions->fts_get_option( 'remove_magnific_css' ) ?? '';
			        if ( isset( $fts_fix_magnific ) && $fts_fix_magnific !== '1' ) {
						wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION, true );
					}
					wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION, true );
				}
			}

            $popup = is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $popup_check ) ? $popup_check :  'no';

            // Premium Tweets Count Check!
			if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && $tweets_count > '6' ) {
				$tweets_count = '6';
			}

			// Exclude Replies?
			$exclude_replies = $show_replies === 'no' ? 'true' : 'false' ;

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
				$_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feed_functions->get_random_string() . '_' . 'twitter' );
				// Create Dynamic Class Name.
				$fts_dynamic_class_name = '';
				if ( isset( $_REQUEST['fts_dynamic_name'] ) ) {
					$fts_dynamic_class_name = 'feed_dynamic_class' . sanitize_key( $_REQUEST['fts_dynamic_name'] );
				}
			}

			// Data Cache Name!
			$data_cache = ! empty( $search ) ? 'twitter_data_cache_' . $search . '_num' . $tweets_count : 'twitter_data_cache_' . $twitter_name . '_num' . $tweets_count;

			    //Access Tokens Options.
                $fts_twitter_custom_access_token        = !empty( $saved_feed_options['fts_twitter_custom_access_token'] ) ? $saved_feed_options['fts_twitter_custom_access_token'] : '';
                $fts_twitter_custom_access_token_secret = !empty( $saved_feed_options['fts_twitter_custom_access_token_secret'] ) ? $saved_feed_options['fts_twitter_custom_access_token_secret'] : '';

                if ( empty( $fts_twitter_custom_access_token ) && empty( $fts_twitter_custom_access_token_secret ) ) {
				    // NO Access tokens found.
				    echo esc_html( 'Feed Them Social: Twitter Feed not loaded, please add your Access Token from the Gear Icon Tab.', 'feed-them-social' );
                    return false;
			    }

                $fts_twitter_custom_consumer_key    = 'DKWMIoc4s6hH3ED0nNFNwcTe3';
				$fts_twitter_custom_consumer_secret = 'U7XeBfbx1mU3vV1uPcYGmUr5e0a15evwpYY2QSbRfAYoNjum2q';

			// Check Cache.
			if ( false !== $this->feed_cache->fts_check_feed_cache_exists( $data_cache ) && ! isset( $_GET['load_more_ajaxing'] ) ) {
				$fetched_tweets = json_decode( $this->feed_cache->fts_get_feed_cache( $data_cache ) );
				$cache_used     = true;

			} else {

				// Use custom api info.
				if ( ! empty( $fts_twitter_custom_access_token ) && ! empty( $fts_twitter_custom_access_token_secret ) ) {
					$connection = new TwitterOAuthFTS(
						// Consumer Key.
						$fts_twitter_custom_consumer_key,
						// Consumer Secret.
						$fts_twitter_custom_consumer_secret,
						// Access Token.
						$fts_twitter_custom_access_token,
						// Access Token Secret.
						$fts_twitter_custom_access_token_secret
					);
				}

				/* Testing
				$fetch_api_limit = $connection->get(
				'application/rate_limit_status',
				array(
					'resources' => 'help,users,search,statuses',
				)
				);
				error_log( print_r( $fetch_api_limit, true ) );*/

				// $videosDecode = 'https://api.twitter.com/1.1/statuses/oembed.json?id=507185938620219395';.
				// numTimes = get_option('twitter_replies_offset') == TRUE ? get_option('twitter_replies_offset') : '1' ;.

				// If excluding replies, we need to fetch more than requested as the total is fetched first, and then replies removed.
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && isset( $loadmore ) && 'yes' === $loadmore ) {
					$total_to_fetch = $tweets_count;
				} else {
                    $tweets_count = !empty( $tweets_count ) ? $tweets_count : 6;
					$total_to_fetch = 'true' === $exclude_replies ? max( 50, $tweets_count * 3 ) : $tweets_count;
				}
				// $total_to_fetch = $tweets_count;.
				$description_image = !empty( $description_image ) ?? '';

				if ( isset( $show_retweets ) && 'yes' === $show_retweets ) {
					$show_retweets = 'true';
				}
				if ( isset( $show_retweets ) && 'no' === $show_retweets ) {
					$show_retweets = 'false';
				}

				$fetched_tweets = array();

				// $url_of_status = !empty($url_of_status) ? $url_of_status : "";.
				// $widget_type_for_videos = !empty($widget_type_for_videos) ? $widget_type_for_videos : "";.

				if ( ! empty( $search )  && 'hashtag' === $type ) {

					$connection_search_array = array(
						'q'           => $search,
						'count'       => $total_to_fetch,
						'result_type' => 'recent',
						'include_rts' => $show_retweets,
						'tweet_mode'  => 'extended',
					);

					// For Load More Ajax.
					if ( isset( $_REQUEST['since_id'] ) && isset( $_REQUEST['max_id'] ) ) {

						// $connection_search_array['since_id'] =  $_REQUEST['since_id'];.
						$connection_search_array['max_id'] = sanitize_text_field( wp_unslash( $_REQUEST['max_id'] ) ) - 1;
					}

					$fetched_tweets = $connection->get(
						'search/tweets',
						$connection_search_array
					);
				} else {

					$connection_user_array = array(
						'tweet_mode'      => 'extended',
						'screen_name'     => $twitter_name,
						'count'           => $total_to_fetch,
						'exclude_replies' => $exclude_replies,
						'images'          => $description_image,
						'include_rts'     => $show_retweets,
					);

					// For Load More Ajax.
					if ( isset( $_REQUEST['since_id'] ) && isset( $_REQUEST['max_id'] ) ) {
						// $connection_user_array['since_id'] =  $_REQUEST['since_id'];.
						$connection_user_array['max_id'] = sanitize_text_field( wp_unslash( $_REQUEST['max_id'] ) ) - 1;
					}

					if ( null !== $connection ) {
						$fetched_tweets = $connection->get(
							'statuses/user_timeline',
							$connection_user_array
						);
					}
				}

                // Check API failure. Return empty array so no fatal error.
                if ( ! empty( $search ) ) {
					$fetched_tweets = $fetched_tweets->statuses ?? [];
				} else {
					$fetched_tweets = $fetched_tweets ?? [];
				}

				// Check if $tweets_count and $fetched_tweets are valid and defined
                if (isset($tweets_count) && isset($fetched_tweets) && is_array($fetched_tweets)) {
                    // Get the count based on $exclude_replies.
                    $limit_to_display = min($tweets_count, count($fetched_tweets));
                    for ($i = 0; $i < $limit_to_display; $i++) {
                        $tweets_count = $limit_to_display;
                        break;
                    }
                    $convert_array1['data'] = $fetched_tweets;

                    // Check if $convert_array1 is a valid array
                    if (is_array($convert_array1)) {
                        $fetched_tweets = (object) $convert_array1;
                    } else {
                        // Let our error handler below take over. This is just a placeholder.
                        // Handle the error when $convert_array1 is not a valid array
                        // echo 'Error: \$convert_array1 is not a valid array';
                    }
                } else {
                    // Let our error handler below take over. This is just a placeholder.
                    // Handle the error when $tweets_count or $fetched_tweets are not valid or defined
                    // echo 'Error: \$tweets_count or \$fetched_tweets are not valid or defined';
                }
			}

			// END ELSE.
			// Error Check.
			if ( isset( $fetched_tweets->errors ) ) {
				$error_check = __( 'Oops, Something is wrong. ', 'feed-them-social' ) . $fetched_tweets->errors[0]->message;
				if ( '32' === $fetched_tweets->errors[0]->code ) {
					$error_check .= __( ' Please check that you have entered your Twitter API token information correctly on the Twitter Options page of Feed Them Social.', 'feed-them-social' );
				}
				if ( '34' === $fetched_tweets->errors[0]->code ) {
					$error_check .= __( ' Please check the Twitter Username you have entered is correct in your shortcode for Feed Them Social.', 'feed-them-social' );
				}
			} elseif ( empty( $fetched_tweets ) && ! isset( $fetched_tweets->errors ) ) {
				// No Tweets Found!
				$error_check = __( ' This account has no tweets. Please Tweet to see this feed. Feed Them Social.', 'feed-them-social' );
			}
            // Start Outputting Feed.
			ob_start();

			// IS RATE LIMIT REACHED?
			if ( isset( $fetched_tweets->errors ) && '32' !== $fetched_tweets->errors[0]->code && '34' !== $fetched_tweets->errors[0]->code ) {
				echo esc_html( 'Rate Limited Exceeded. Please go to the Feed Them Social Plugin then the Twitter Options page for Feed Them Social and follow the instructions under the header Twitter API Token.', 'feed-them-social' );
			}
			// Did the fetch fail?
			if ( isset( $error_check ) ) {

				if ( current_user_can( 'administrator' ) ) {
					echo esc_html( $error_check );
				} else {
					echo esc_html__( 'No Tweets available. Login as Admin to see more details.', 'feed-them-social' );
				}
			} else {

				if ( isset( $fetched_tweets ) && ! empty( $fetched_tweets ) ) {

					// Cache It.
					if ( ! isset( $cache_used ) && ! isset( $_GET['load_more_ajaxing'] ) ) {

						$this->feed_cache->fts_create_feed_cache( $data_cache, $fetched_tweets );
					}

					$protocol       = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
					$user_permalink = $protocol . 'twitter.com/' . $twitter_name;

					foreach ( $fetched_tweets->data as $post_data ) {

						$profile_banner_url = $post_data->user->profile_banner_url ?? '';
						$statuses_count     = $post_data->user->statuses_count ?? '';
						$followers_count    = $post_data->user->followers_count ?? '';
						$friends_count      = $post_data->user->friends_count ?? '';
						$favourites_count   = $post_data->user->favourites_count ?? '';

						// we break this foreach because we only need one post to get the info above.
						break;
					}

					// Make sure it's not ajaxing.
					if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

						if ( isset( $profile_banner_url ) && isset( $cover_photo ) && 'yes' === $cover_photo ) {
							?>
	                        <div class="fts-twitter-backg-image">
							<?php
							if ( isset( $twitter_show_follow_btn ) && 'yes' === $twitter_show_follow_btn && 'twitter-follow-above' === $twitter_show_follow_btn_where && ! empty( $twitter_name ) ) {
								echo '<div class="twitter-social-btn-top">';
								echo $this->feed_functions->social_follow_button( 'twitter', $twitter_name, $saved_feed_options );
								echo '</div>';
							}
							?>
		                    <img src="<?php echo esc_url( $profile_banner_url ); ?>"/>

	                        </div>
							<?php
						} elseif ( isset( $twitter_show_follow_btn ) && 'yes' === $twitter_show_follow_btn && 'twitter-follow-above' === $twitter_show_follow_btn_where && ! empty( $twitter_name ) && 'yes' !== $cover_photo ) {
							echo '<div class="twitter-social-btn-top">';
							echo $this->feed_functions->social_follow_button( 'twitter', $twitter_name, $saved_feed_options );
							echo '</div>';
						}// if cover photo = yes.

						// These need to be in this order to keep the different counts straight since I used either $statuses_count or $followers_count throughout.
						if ( isset( $stats_bar ) && 'yes' === $stats_bar && empty( $search ) ) {

							// here we add a , for all numbers below 9,999.
							if ( isset( $statuses_count ) && $statuses_count <= 9999 ) {
								$statuses_count = number_format( (float) $statuses_count );
							}
							// here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions.
							if ( isset( $statuses_count ) && $statuses_count >= 1000000 ) {
								$statuses_count = round( ( $statuses_count / 1000000 ), 1 ) . 'm';
							}
							// here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands.
							if ( isset( $statuses_count ) && $statuses_count >= 10000 ) {
								$statuses_count = round( ( $statuses_count / 1000 ), 1 ) . 'k';
							}

							// here we add a , for all numbers below 9,999.
							if ( isset( $followers_count ) && $followers_count <= 9999 ) {
								$followers_count = number_format( (float) $followers_count );
							}
							// here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions.
							if ( isset( $followers_count ) && $followers_count >= 1000000 ) {
								$followers_count = round( ( $followers_count / 1000000 ), 1 ) . 'm';
							}
							// here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands.
							if ( isset( $followers_count ) && $followers_count >= 10000 ) {
								$followers_count = round( ( $followers_count / 1000 ), 1 ) . 'k';
							}
						}

						// option to allow the followers plus count to show.
						if ( isset( $twitter_show_follow_count ) && 'yes' === $twitter_show_follow_count && empty( $search ) && isset( $stats_bar ) && 'yes' !== $stats_bar ) {
							echo '<div class="twitter-followers-fts-singular"><a href="' . esc_url( $user_permalink ) . '" target="_blank">' . esc_html( 'Followers:', 'feed-them-social' ) . '</a> ' . esc_html( $followers_count ) . '</div>';
						}
						if ( isset( $stats_bar ) && 'yes' === $stats_bar && empty( $search ) ) {

							// option to allow the followers plus count to show.
							echo '<div class="fts-twitter-followers-wrap">';
							echo '<div class="twitter-followers-fts fts-tweets-first"><a href="' . esc_url( $user_permalink ) . '" target="_blank">' . esc_html( 'Tweets', 'feed-them-social' ) . '</a> ' . esc_html( $statuses_count ) . '</div>';
							echo '<div class="twitter-followers-fts fts-following-link-div"><a href="' . esc_url( $user_permalink ) . '" target="_blank">' . esc_html( 'Following', 'feed-them-social' ) . '</a> ' . number_format( (float) $friends_count ) . '</div>';
							echo '<div class="twitter-followers-fts fts-followers-link-div"><a href="' . esc_url( $user_permalink ) . '" target="_blank">' . esc_html( 'Followers', 'feed-them-social' ) . '</a> ' . esc_html( $followers_count ) . '</div>';
							echo '<div class="twitter-followers-fts fts-likes-link-div"><a href="' . esc_url( $user_permalink ) . '" target="_blank">' . esc_html( 'Likes', 'feed-them-social' ) . '</a> ' . number_format( (float) $favourites_count ) . '</div>';
							echo '</div>';

						}

						if ( isset( $grid ) && 'yes' === $grid ) {
							?>
<div id="twitter-feed-<?php echo esc_attr( $twitter_name ); ?>" class="fts-slicker-twitter-posts masonry js-masonry
											  <?php
												echo esc_attr( $fts_dynamic_class_name );
												if ( isset( $popup ) && 'yes' === $popup ) {

													?>
				popup-gallery-twitter<?php } ?>" style='margin:0 auto' data-masonry-options='{"itemSelector": ".fts-tweeter-wrap", "isFitWidth": true, "transitionDuration": 0 }'>
								<?php
						} else {
							?>
	<div id="twitter-feed-<?php echo esc_attr( $twitter_name ); ?>" class="<?php echo esc_attr( $fts_dynamic_class_name ); ?> fts-twitter-div
								 <?php
									if ( ! empty( $twitter_height ) && 'auto' !== $twitter_height ) {
										?>
											fts-twitter-scrollable
										<?php
									}
									if ( isset( $popup ) && 'yes' === $popup ) {

										?>
											popup-gallery-twitter<?php } ?>"
								<?php
								if ( ! empty( $twitter_height ) && 'auto' !== $twitter_height ) {

									?>
								style="height:<?php echo esc_attr( $twitter_height ); ?>"<?php } ?>>
				<?php } ?>

							<?php
					}

					 /*echo'<pre>';
					 print_r($fetched_tweets->data);
                     echo'</pre>';*/

					$i = 0;
					foreach ( $fetched_tweets->data as $post_data ) {

						$user_name            = $post_data->user->name ?? '';
						$description          = $this->description( $post_data );
						$user_name_retweet    = $post_data->retweeted_status->user->name ?? '';
						$twitter_name         = $post_data->user->screen_name ?? '';
						$screen_name_retweet  = $post_data->retweeted_status->user->screen_name ?? '';
						$in_reply_screen_name = $post_data->entities->user_mentions[0]->screen_name ?? '';
						$protocol             = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

						$user_permalink = $protocol . 'twitter.com/' . $twitter_name;

						$user_retweet_permalink = $protocol . 'twitter.com/' . $screen_name_retweet;

						$in_reply_permalink = $protocol . 'twitter.com/' . $in_reply_screen_name;

						// Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */.
						$image = $post_data->user->profile_image_url_https ?? '';

						$image_retweet = $post_data->retweeted_status->user->profile_image_url_https ?? '';

						// $image = str_replace($not_protocol, $protocol, $image);.
						// Need to get time in Unix format.
						$times = $post_data->created_at ?? '';
						// tied to date function.
						$feed_type = 'twitter';

						// call our function to get the date.
						$fts_date_time = $this->feed_functions->fts_custom_date( $times, $feed_type );

						$fts_twitter_full_width = $saved_feed_options['twitter_full_width'] ?? '';
						$fts_dynamic_name       = isset( $fts_dynamic_name ) ? $fts_dynamic_name : '';

						?>

			<div class="fts-tweeter-wrap <?php echo esc_attr( $fts_dynamic_name ); ?>"
						<?php
						if ( isset( $grid ) && 'yes' === $grid ) {
							echo ' style="width:' . esc_attr( $column_width ) . '!important; margin:' . esc_attr( $space_between_posts ) . '!important"';
						}
						?>>
				<div class="tweeter-info">

						<?php if ( 'yes' !== $fts_twitter_full_width ) { ?>
						<div class="fts-twitter-image"></div>
					<?php } ?>

					<div class="<?php if ( 'yes' === $fts_twitter_full_width ) {?>fts-twitter-full-width <?php
								} else {?>fts-right<?php } ?>">

						<div class="fts-uppercase fts-bold">
						    <?php
							if ( empty( $post_data->retweeted_status ) && 'yes' !== $fts_twitter_full_width ) {
								?>
								<a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank"
								class="fts-twitter-username"><img class="twitter-image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $user_name ); ?>"/></a>
							<?php } elseif( 'yes' !== $fts_twitter_full_width ) { ?>
								<a href="<?php echo esc_url( $user_retweet_permalink ); ?>" target="_blank"
								class="fts-twitter-permalink fts-twitter-username"><img class="twitter-image" src="<?php echo esc_url( $image_retweet ); ?>" alt="<?php echo esc_attr( $user_name_retweet ); ?>"/></a>

							<?php } ?>

                             <span class="fts-twitter-name-wrap">

									<?php if ( ! isset( $post_data->retweeted_status ) && empty( $post_data->in_reply_to_user_id ) ) { ?>

                                        <a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank" class="fts-twitter-full-name"><?php echo esc_html( $post_data->user->name ); ?></a> &#183; <span ><a href="<?php echo esc_url( $user_permalink ); ?>"
											class="time" target="_blank" title="<?php echo esc_html( $fts_date_time ); ?>"><?php echo esc_html( $fts_date_time ); ?></a><br/>
                                        <a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank" class="fts-twitter-at-name">@<?php echo esc_html( $twitter_name ); ?></a>

										<?php
                                    } else {

	                                    if ( empty( $post_data->in_reply_to_user_id ) ) {
		?>
									<a href="<?php echo esc_url( $user_permalink ); ?>" target="_blank" class="fts-twitter-at-name"><?php echo esc_html( $post_data->user->name ); ?> <?php echo esc_html( 'Retweeted', 'feed-them-social' ); ?>
										<strong>&middot;</strong></a>
									<a href="<?php echo esc_url( $user_retweet_permalink ); ?>" target="_blank" class="fts-twitter-full-name"><?php echo esc_html( $user_name_retweet ); ?></a><br/>
									<a href="<?php echo esc_url( $user_retweet_permalink ); ?>" target="_blank" class="fts-twitter-at-name">@<?php echo esc_html( $screen_name_retweet ); ?></a>
								<?php } else { ?>
									<a href="<?php echo esc_url( $in_reply_permalink ); ?>" target="_blank" class="fts-twitter-at-name"><?php echo esc_html( 'In reply to', 'feed-them-social' ); ?>&nbsp;<?php echo esc_html( $post_data->entities->user_mentions[0]->name ); ?> </a>
								<?php } ?>
							</span>
		<?php
}
						$permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id; ?>
						</div>
						<span class="fts-twitter-logo"><a href="<?php echo esc_url( $permalink ); ?>"><i class="fa fa-twitter"></i></a></span>
						<span class="fts-twitter-text">
						<?php
						echo wp_kses(
							$description,
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
						?>
							<div class="fts-twitter-caption">
												<a href="<?php echo esc_url( $user_permalink ); ?>" class="fts-view-on-twitter-link" target="_blank"><?php echo esc_html( 'View on Twitter', 'feed-them-social' ); ?></a>
											</div>
										</span>

						<?php

						if ( isset( $post_data->quoted_status->entities->media[0]->type ) ) {
							$twitter_final = isset( $post_data->quoted_status->entities->media[0]->expanded_url ) && 'video' === $post_data->quoted_status->entities->media[0]->expanded_url ? $post_data->quoted_status->entities->media[0]->expanded_url : '';

						} else {
							$twitter_final = $post_data->entities->urls[0]->expanded_url ?? '';
						}

						// Regular Posted Videos.
						$twitter_video_reg = isset( $post_data->extended_entities->media[0]->type ) && 'video' === $post_data->extended_entities->media[0]->type ? $post_data->extended_entities->media[0]->type : '';

						// Retweeted video urls // NOTE I HAVE NOT COMPLETED THIS OPTION COMPLETELY BECAUSE I CANNOT FIND AN EXAMPLE.
						$twitter_video_retweeted = isset( $post_data->retweeted_status->extended_entities->media[0]->type ) && 'video' === $post_data->retweeted_status->extended_entities->media[0]->type ? $post_data->retweeted_status->extended_entities->media[0]->type : '';

						// Quoted status which is when people retweet or copy paste video tweet link to there tweet. why people do this instead of retweeting is beyond me.
						$twitter_video_quoted_status = isset( $post_data->quoted_status->extended_entities->media[0]->type ) && 'video' === $post_data->quoted_status->extended_entities->media[0]->type ? $post_data->quoted_status->extended_entities->media[0]->type : '';

						// Quoted status which is when people retweet or copy paste image tweet link to there tweet. why people do this instead of retweeting is beyond me.
						$twitter_image_quoted_status = isset( $post_data->quoted_status->extended_entities->media[0]->type ) && 'photo' === $post_data->quoted_status->extended_entities->media[0]->type ? $post_data->quoted_status->extended_entities->media[0]->type : '';

						$twitter_is_video_allowed = $saved_feed_options['twitter_allow_videos'] ?? '';
						$twitter_allow_videos     = $twitter_is_video_allowed ?? 'yes';
                        $no_video_image_check     =   '' ===  $twitter_video_reg && '' === $twitter_video_retweeted && '' ===  $twitter_video_quoted_status && '' ===  $twitter_image_quoted_status ? 'true' : 'false';
                        $fts_image = $this->tweet_image( $post_data, $popup, $hide_images_in_posts );

						if (
                            // These first 4 are the different types of actual twitter videos that can come about!
                            'yes' === $twitter_allow_videos && 'video' === $twitter_final ||
                            'yes' === $twitter_allow_videos && 'video' === $twitter_video_reg ||
                            'yes' === $twitter_allow_videos && 'video' === $twitter_video_quoted_status ||
                            'yes' === $twitter_allow_videos && 'video' === $twitter_video_retweeted ||

                            // 3rd party videos/music we are checking for; youtube, vimeo  and soudcloud
                            'yes' === $twitter_allow_videos && strpos( $twitter_final, 'vimeo' ) > 0 ||
                            'yes' === $twitter_allow_videos && strpos( $twitter_final, 'youtube' ) > 0 && ! strpos( $twitter_final, '-youtube' ) > 0 ||
                            'yes' === $twitter_allow_videos && strpos( $twitter_final, 'youtu.be' ) > 0 ||
                            'yes' === $twitter_allow_videos && strpos( $twitter_final, 'soundcloud' ) > 0 ) {

							if ( 'video' === $twitter_video_quoted_status ) {
								?>
								<div class="fts-twitter-quoted-text-wrap fts-twitter-quoted-video">
								<?php
							}

								// Print our video if one is available.
								echo $this->load_videos( $post_data );

							if ( 'video' === $twitter_video_quoted_status ) {
								?>

								<div class="fts-twitter-quoted-text">
									<a href="<?php echo esc_url( $user_retweet_permalink ); ?>" target="_blank"
									class="fts-twitter-full-name"><?php echo esc_html( $post_data->quoted_status->user->name ); ?></a>
									<a href="<?php echo esc_url( $user_retweet_permalink ); ?>" target="_blank"
									class="fts-twitter-at-name">@<?php echo esc_html( $post_data->quoted_status->user->screen_name ); ?></a><br/>
									<?php
									echo wp_kses(
										$this->tweet_quote_description( $post_data ),
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
									?>
								</div>

								<?php
							}

							if ( 'video' === $twitter_video_quoted_status ) {
								?>
								</div>
								<?php
							}
                        }
						elseif ( 'photo' === $twitter_image_quoted_status ) {

                                $this->fts_twitter_quoted_wrap( $post_data, $popup, $hide_images_in_posts );

						}
						else {
                              //  echo 'sadfasdf<br/>';
                              //  echo $twitter_final;
                              //  echo '<br/>';
                            if( empty( $post_data->quoted_status->full_text ) && ! empty ( $twitter_final ) && ! strpos( $twitter_final, 'twitter.com' ) && empty( $fts_image ) ){

                                // Status: External Tweet
                                // THIS IS COMPLETE.
                                // EXTERNAL LINK IMAGE
                                // EXTERNAL LINK DOMAIN NAME
                                // EXTERNAL LINK TITLE TEXT
                                // EXTERNAL LINK DESCRIPTION TEXT
                                // EXAMPLE: https://twitter.com/trevorwood/status/1437473626371067904

                                // Function to check the url and see if we need to get image, title and description from twitter meta or og meta from external websites.
                                // Instead of using the function call below I'm running a class check and if so foreach and process the data via ajax.
                                // Just leaving for reference.
                                // $property = $this->fts_twitter_share_url_check( $twitter_final, $no_video_image_check, $fts_image );

                                // js function is called fts_external_link_meta_content.
                                // Check to see if the image exists, also check to see if the $no_video_image_check is true or false and lastly check to see the external url is present.
                                // Get the classes from the div below and results and pass them to our ajax js so we can run the function and replace the html with the results in the fts-twitter-external-url-wrap div below.
                                // A successful result should include the external ur and true for $fts_image and false $no_video_image_check.
                                // We hide the div below and only show it when the contents are returned from our ajax success.
                                $image_exixts = isset( $fts_image ) ? 'true' : 'false';
                                $time  = time();
                                $nonce = wp_create_nonce( $time . 'fts-twitter-nonce' );
                               // echo 'asdfasdf' . $nonce;
                                echo '<div class="fts-twitter-external-url-wrap" data-twitter-security="' . $nonce . '" data-twitter-time="' . $time . '" data-twitter-url="' . $twitter_final . '" data-image-exists-check="' . $image_exixts . '" data-no-video-image-check="' . $no_video_image_check . '" data-twitter-popup="' . $popup . '"></div>';

                            }
                            elseif ( !empty( $post_data->quoted_status->full_text ) ){
                                // Status: quoted retweet
                                // THIS IS COMPLETE.
                                // This happens when you click the retweet option and choose Quoted Tweet.
                                // it will then allow you to add your own text along info from the retweet.
                                // This will display the quoted text, username, handle name and description, and image if one is present.
                                // QUOTED RETWEET USERNAME AND HANDLE NAME;
                                // QUOTED RETWEET DESCRIPTION;
                                // QUOTED RETWEETED IMAGE
                                $this->fts_twitter_quoted_wrap( $post_data, $popup, $hide_images_in_posts );
                            }
                            elseif ( !empty( $post_data->retweeted_status ) ){
                                // Status: retweet
                                // THIS IS WHEN YOU CLICK THE SHARE OPTION IN TWITTER AND COPY THE TWEET LINK
                                // this will show the short link by itself and below that will display the quoted text,
                                // username, handle name and description, and image if one is present.
                                // SHARED A TWITTER LINK USERNAME AND HANDLE NAME;
                                // SHARED A TWITTER LINK DESCRIPTION;
                                // SHARED A TWITTER LINK IMAGE
                                $this->fts_twitter_retweeted_wrap( $post_data, $popup, $hide_images_in_posts );
                            }
                            else {
                                // Status: user tweet with image
                                // This is a user specific image, not a shared or retweeted image which is $fts_image
                                //echo 'TEST' . $hide_images_in_posts;
                                echo $fts_image;
                            }
						}
						?>
					</div>
					<div class="fts-twitter-reply-wrap <?php
							if ( 'yes' === $fts_twitter_full_width ) {
								?>fts-twitter-full-width<?php
							} else {
								?>
										fts-twitter-no-margin-left<?php } ?>">
									<?php
									// twitter permalink per post.
									$permalink = 'https://twitter.com/' . $post_data->user->screen_name . '/status/' . $post_data->id;
									echo $this->feed_functions->fts_share_option( $permalink, $description );
									?>
					</div>
					<div class="fts-twitter-reply-wrap-left fts-twitter-svg-addition">
						<div class="fts-tweet-others-right"><?php echo $this->retweet_count( $post_data ); ?><?php echo $this->favorite_count( $post_data ); ?><?php  echo $this->tweet_permalink( $post_data ); ?></div>
					</div>
					<div class="fts-clear"></div>
				</div><?php // <!--tweeter-info-->. ?>
			</div>
							<?php
							$i++;
							// cannot use === for this equation because $i is a dynamic number.
							if ( $i == $tweets_count ) {
								break;
							}
					}
					// endforeach;.
					// Make sure it's not ajaxing.
					if ( ! isset( $_GET['load_more_ajaxing'] ) && isset( $loadmore, $loadmore_style ) &&  'yes' === $loadmore && 'autoscroll' === $loadmore_style ) {

						$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];

						// this div returns outputs our ajax request via jquery append html from above.
						echo '<div id="output_' . esc_attr( $fts_dynamic_name ) . '"></div>';
						if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
							echo '<div class="fts-twitter-load-more-wrapper">';
							echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more fts-fb-autoscroll-loader">Twitter</div>';
							echo '</div>';
						}
					}
					?>
	</div>
					<?php

					// this makes it so the page does not scroll if you reach the end of scroll bar or go back to top.
					if ( ! empty( $twitter_height ) && 'auto' !== $twitter_height ) {
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
				}// END IF $fetched_tweets.
			}
			// END ELSE.
			// Load More BUTTON Start.
			// First Key.
			$first_key = isset( $fetched_tweets->data ) ? current( $fetched_tweets->data ) : '';

			$_REQUEST['since_id'] = $first_key->id_str ?? '';

			// Last Key.
			$last_key           = isset( $fetched_tweets->data ) ? end( $fetched_tweets->data ) : '';
			$_REQUEST['max_id'] = $last_key->id_str ?? '';

			if ( isset( $loadmore ) && 'yes' === $loadmore ) {
				?>
		<script>var sinceID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_js( $_REQUEST['since_id'] ); ?>";
			var maxID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['max_id'] ) ) ); ?>";</script>
				<?php
			}

			// Make sure it's not ajaxing.
			if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_REQUEST['fts_no_more_posts'] ) && isset( $loadmore ) && 'yes' === $loadmore ) {
				$fts_dynamic_name = sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) );
				$time             = time();
				$nonce            = wp_create_nonce( $time . 'load-more-nonce' );
				?>
		<script>
			jQuery(document).ready(function () {

				<?php
				// $loadmore_style = load_more_posts_style shortcode att.
				if ( 'autoscroll' === $loadmore_style && 'yes' === $loadmore ) { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode.
                    ?>
				jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>").bind("scroll", function () {
					if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
					<?php
				} else {
					// this is where we do CLICK function to LOADMORE if = button in shortcode!
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
                            // SRL 4.0: omitting this for now.
							//var loadmore_count = "tweets_count=<?php //echo esc_js( $loadmore_count ); ?>";
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
                                    // SRL 4.0: ommiting this for now.
									// loadmore_count: loadmore_count,
                                    feed_id: feed_id
								},
								type: 'GET',
								url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
								success: function (data) {
									console.log('Well Done and got this from sever: ' + data);
								<?php if ( 'autoscroll' === $loadmore_style && 'yes' === $loadmore ) { ?>
									jQuery('#output_<?php echo esc_js( $fts_dynamic_name ); ?>').append(data).filter('#output_<?php echo esc_js( $fts_dynamic_name ); ?>').html();
									<?php } else { ?>
									jQuery('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').append(data).filter('.<?php echo esc_js( $fts_dynamic_class_name ); ?>').html();
									<?php } ?>

									if (!maxID_<?php echo sanitize_key( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ); ?> || maxID_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?> == 'no more') {
										jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo esc_js( $twitter_no_more_tweets_text ); ?></div>');
										jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').removeAttr('id');
										jQuery(".<?php echo esc_js( $fts_dynamic_class_name ); ?>").unbind('scroll');
									}
									jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php echo esc_js( $twitter_load_more_text ); ?>');
									//	jQuery('#loadMore_< ?php echo $fts_dynamic_name ?>').removeClass('flip360-fts-load-more');
									jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');
									// Reload the share each funcion otherwise you can't open share option.
									jQuery.fn.ftsShare();
								<?php
								if ( isset( $grid ) && 'yes' === $grid ) {
									?>
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
				); // end of form.submit
							<?php
							if ( isset( $grid ) && 'yes' === $grid ) {
								?>
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
				if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && 'button' === $loadmore_style && 'yes' === $loadmore ) {
					echo '<div class="fts-clear"></div>';
					echo '<div class="fts-twitter-load-more-wrapper">';
					echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '"" style="';
					if ( isset( $loadmore_btn_maxwidth ) && ! empty( $loadmore_btn_maxwidth ) ) {
						echo 'max-width:' . esc_attr( $loadmore_btn_maxwidth ) . ';';
					}
					$loadmore_btn_margin = isset( $loadmore_btn_margin ) ? $loadmore_btn_margin : '10px';
					echo 'margin:' . esc_attr( $loadmore_btn_margin ) . ' auto ' . esc_attr( $loadmore_btn_margin ) . '" class="fts-fb-load-more">' . esc_html( $twitter_load_more_text ) . '</div>';
					echo '</div>';
				}
			}
			// End Check.
			unset( $_REQUEST['since_id'], $_REQUEST['max_id'] );

			// SOCIAL BUTTON.
			if ( isset( $twitter_show_follow_btn ) && 'yes' === $twitter_show_follow_btn && 'twitter-follow-below' === $twitter_show_follow_btn_where && ! empty( $twitter_name ) ) {
				echo '<div class="twitter-social-btn-bottom">';
				echo $this->feed_functions->social_follow_button( 'twitter', $twitter_name, $saved_feed_options );
				echo '</div>';
			}

			return ob_get_clean();
	}

}//end class
