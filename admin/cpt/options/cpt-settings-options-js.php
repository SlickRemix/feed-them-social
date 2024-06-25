<?php
/**
 * Settings Options JS
 *
 * This class is for creating a metabox settings pages/sections!
 *
 * @version  3.0.0
 * @package  FeedThemGalley/Core
 * @author   SlickRemix
 */

namespace feedthemsocial;


/**
 * Class Settings_Options_JS
 *
 * Class for adding JS to specific feed metabox options.
 *
 * @package feedthemsocial
 */
class Settings_Options_JS {

	public function facebook_js(){

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

		?>
		<script>
		jQuery(document).ready(function () {

            var fts_color_picker = jQuery('.fts-color-picker input');

            if( fts_color_picker.length ) {
                fts_color_picker.wpColorPicker();
            }
            jQuery('.fts-color-picker .fts-required-extension-wrap').parent().find('.wp-picker-container').hide();


            // Contains is not specific enough because we introduces Tiktok Premium Required. So we need to use the filter option instead.
            if (jQuery('.feed-them-social-req-extension').filter(function() {
                return jQuery(this).text().trim() === "Premium Required";
            }).length > 0) {
                jQuery('.fts-social-selector option').filter(function() {
                    return jQuery(this).text().trim() === "Videos";
                }).attr('disabled', 'disabled').html('Videos - Premium Plugin Required');
            }
            // Contains works in this case because there is only one instance of Reviews Required.
            if ( jQuery('.feed-them-social-req-extension:contains("Reviews Required")').length > 0 ) {
                jQuery('.fts-social-selector option:contains("Page Reviews")').attr('disabled', 'disabled').html('Page Reviews - Reviews Plugin Required');;
            }

            jQuery( '.tabbed' ).click( function (e) {
                if(  'pointer-events: none !important' === jQuery( 'a', this ).attr( 'style' ) ){
                    jQuery('.tab1 a').click();
                    jQuery('#ftg-tab-content1').prepend(' <div class="fts-show-how-to-message"><span style="font-size:22px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M96 0C60.7 0 32 28.7 32 64V448c-17.7 0-32 14.3-32 32s14.3 32 32 32H320c17.7 0 32-14.3 32-32s-14.3-32-32-32V304h16c22.1 0 40 17.9 40 40v32c0 39.8 32.2 72 72 72s72-32.2 72-72V252.3c32.5-10.2 56-40.5 56-76.3V144c0-8.8-7.2-16-16-16H544V80c0-8.8-7.2-16-16-16s-16 7.2-16 16v48H480V80c0-8.8-7.2-16-16-16s-16 7.2-16 16v48H432c-8.8 0-16 7.2-16 16v32c0 35.8 23.5 66.1 56 76.3V376c0 13.3-10.7 24-24 24s-24-10.7-24-24V344c0-48.6-39.4-88-88-88H320V64c0-35.3-28.7-64-64-64H96zM216.9 82.7c6 4 8.5 11.5 6.3 18.3l-25 74.9H256c6.7 0 12.7 4.2 15 10.4s.5 13.3-4.6 17.7l-112 96c-5.5 4.7-13.4 5.1-19.3 1.1s-8.5-11.5-6.3-18.3l25-74.9H96c-6.7 0-12.7-4.2-15-10.4s-.5-13.3 4.6-17.7l112-96c5.5-4.7 13.4-5.1 19.3-1.1z"/></svg> Getting Started</span><br/>Choose a Feed type » Click Login and Get my Access Token</div>');

                }
                else {
                    jQuery('.fts-show-how-to-message').remove();
                }
            });

			jQuery('select#facebook_hide_like_box_button').bind('change', function (e) {
				if (jQuery('select#facebook_hide_like_box_button').val() == 'no') {
					jQuery('.like-box-wrap').show();
				}
				else {
					jQuery('.like-box-wrap').hide();
				}
			}).change();


            //Facebook Display Popup option
            jQuery('select#facebook_popup').bind('change', function (e) {
                if (jQuery('#facebook_popup').val() == 'yes') {
                    jQuery('.display-comments-wrap').show();
                }
                else {
                    jQuery('.display-comments-wrap').hide();
                }
            }).change();

            // facebook show grid options
            jQuery('#facebook_grid').bind('change', function (e) {
                if (jQuery('#facebook_grid').val() == 'yes') {
                    jQuery('.fts-facebook-grid-options-wrap').show();
                    jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
                }
                else {
                    jQuery('.fts-facebook-grid-options-wrap').hide();
                }
            }).change();

            // facebook show load more options
            jQuery('#facebook_load_more').bind('change', function (e) {
                if (jQuery('#facebook_load_more').val() == 'yes') {

                    if (jQuery('#facebook_page_feed_type').val() !== 'album_videos') {
                        jQuery('.fts-facebook-load-more-options-wrap').show();
                    }
                    jQuery('.fts-facebook-load-more-options2-wrap').show();
                }
                else {
                    jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                }
            }).change();

            // facebook fts-slider
            jQuery('#fts-slider').bind('change', function (e) {
                if (jQuery('#fts-slider').val() == 'yes') {

                    jQuery("#facebook_load_more").val('no');
                    jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                    jQuery('.slider_options_wrap').show();

                }
                else {
                    jQuery('.slider_options_wrap').hide();
                }
            }).change();

			jQuery('#facebook_show_video_button').change(function () {
                if (jQuery('#facebook_show_video_button').val() == 'yes') {

                    jQuery('.fb-video-play-btn-options-content').show();
                }
                else {
                    jQuery('.fb-video-play-btn-options-content').hide();
                }
			}).change();


			// change the feed type 'how to' message when a feed type is selected
			jQuery('#facebook_page_feed_type').change(function () {

                jQuery('.facebook-message-generator').hide();
                jQuery('.' + jQuery(this).val()).fadeIn('fast');
                // if the facebook type select is changed we hide the shortcode code so not to confuse people
                jQuery('.final-shortcode-textarea').hide();
                // only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
                var facebooktype = jQuery("select#facebook_page_feed_type").val();


                if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'album_videos') {
                jQuery('.fts-super-facebook-options-wrap, .align-images-wrap, .slideshow-wrap').show();
                jQuery('.fixed_height_option,.main-grid-options-wrap').hide();
                jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').hide();
                }
                else {
                jQuery('.fts-super-facebook-options-wrap, .align-images-wrap, .slideshow-wrap ').hide();
                jQuery('.fixed_height_option,.main-grid-options-wrap').show();
                jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').show();
                }

                if (facebooktype == 'albums' ) {
                    jQuery('.facebook-omit-album-covers, .facebook-album-covers-since-date').show();           }
                else {
                    jQuery('.facebook-omit-album-covers, .facebook-album-covers-since-date').hide();
                }

                if (facebooktype == 'page' || facebooktype == 'event' || facebooktype == 'group') {
                jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").show();
                }
                else {
                jQuery(".facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ").hide();
                }

                /* FB Pages, Ablums, Photos etc */
                jQuery('#shortcode-form-selector, #facebook_page_feed_type').bind('change', function (e) {
                    if (jQuery('#facebook_page_feed_type').val() == 'page' || jQuery('#facebook_page_feed_type').val() == 'album_photos' || jQuery('#facebook_page_feed_type').val() == 'albums' || jQuery('#facebook_page_feed_type').val() == 'album_videos') {
                        jQuery('#facebook_page_id, #fb_access_token').val('');
                        jQuery('#facebook_page_id').val(jQuery('#facebook_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
                        jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token' ) ); ?>');
                    }
                });

                /* FB Pages, Ablums, Photos etc */
                jQuery('#shortcode-form-selector, #facebook_page_feed_type').bind('change', function (e) {
                    if (jQuery('#facebook_page_feed_type').val() == 'reviews') {
                        jQuery('#facebook_page_id, #fb_access_token').val('');
                        jQuery('#facebook_page_id').val(jQuery('#facebook_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>');
                        jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>');
                    }
                });


                // facebook Super Gallery option
                jQuery('#facebook-custom-gallery').bind('change', function (e) {
                    if (jQuery('#facebook-custom-gallery').val() == 'yes') {
                        jQuery('.fts-super-facebook-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap').hide();
                    }
                });

                    <?php if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>

                        // This is to show all option when prem active if you selected the Facebook Page reviews if not active. Otherwise all other fb-options-wraps are hidden when selecting another fb feed from settings page drop down.
                        jQuery('.fb-options-wrap').show();
                        jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

                        if ( facebooktype == 'album_videos' ) {
                            jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').hide();
                            jQuery('.video, .fb-video-play-btn-options-wrap').show();
                            jQuery(".feed-them-social-admin-input-label:contains('# of Posts')").html("<?php echo esc_js( '# of Videos', 'feed-them-social' ); ?>");
                        }
                        else {
                            jQuery('.video, .fb-video-play-btn-options-wrap').hide();
                            jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').show();
                            jQuery(".feed-them-social-admin-input-label:contains('# of Videos')").html("<?php echo esc_js( '# of Posts', 'feed-them-social' ); ?>");
                        }
                    <?php
                    } else {
                    ?>

                    jQuery('.video, .fb-video-play-btn-options-wrap').hide();
                    jQuery('body .fb_album_photos_id, .fts-required-more-posts').hide();

                    <?php } ?>

                    if (facebooktype == 'page') {
                        jQuery('.inst-text-facebook-page').show();
                    }
                    else {
                        jQuery('.inst-text-facebook-page').hide();
                    }

                    if (facebooktype == 'events') {
                        jQuery('.inst-text-facebook-event-list').show();
                        jQuery('.facebook-loadmore-wrap').hide();

                    }
                    else {
                        jQuery('.inst-text-facebook-event-list').hide();
                        jQuery('.facebook-loadmore-wrap').show();
                    }

                    <?php if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) { ?>
                        if (facebooktype == 'reviews') {
                            jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').show();
                            jQuery('.align-images-wrap,.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').hide();
                        } else {
                            jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').hide();
                            jQuery('.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts').show();
                        }
                    <?php } ?>

                    // only show the post type visible if the facebook page feed type is selected
                    // jQuery('.facebook-post-type-visible').hide();
                    if (facebooktype == 'page') {
                        // SRL 8-23-23: We need to hide this option now because it requires the
                        // pages_read_user_content or Page Public Content Access permission.
                        jQuery('.facebook-post-type-visible').hide();
                    }
                    var fb_feed_type_option = jQuery("select#facebook_page_feed_type").val();
                    if (fb_feed_type_option == 'album_photos') {
                        jQuery('.fb_album_photos_id').show();
                        alert('Important Notice > Feed Type > Album Photos: On 6-28-2024 Meta updated the API and in doing so broke the /photos/ type=uploaded part of the endpoint. We have created a ticket and are waiting for a response to the issue. Please copy the url below and paste it in your browser then upvote the ticket to help bring more attention to it. https://developers.facebook.com/community/threads/2008841156185264/');
                    }
                    else {
                        jQuery('.fb_album_photos_id').hide();
                    }

            }).change();
		});
		</script>

		<?php
	}

	public function instagram_js(){
		?>
        <script>

        // This is relative to clicking the basic or bussines token options.
        // If you clicked on the basic token option then we will hide the feed type row and select basic as the option.
        // If the user selected business token option then we display the feed type row with select and hide the basic option leaving the
        // business and hashtag select options.
        function instagram_feed_type_select(){


            if( jQuery('#fts-instagram-business-token-button').hasClass( 'fts-social-icon-wrap-active' ) &&
                jQuery('#instagram_feed_type').find('[value="business"]').attr('selected') ||
                jQuery( '#feed_type' ).val() === 'instagram-business-feed-type' &&
                jQuery('#instagram_feed_type').val() === 'basic' ||
                jQuery( '#feed_type' ).val() === 'instagram-business-feed-type' &&
                jQuery('#instagram_feed_type').val() === 'business' ) {
                //jQuery( '#feed_type' ).val() === 'instagram-business-feed-type' && jQuery('#instagram_feed_type').find('[value="basic"]').attr('selected')
                // I do the or statement above because basic will always be first in the select so we have to override that by doing a second check.

                jQuery(".main-instagram-profile-options-wrap").show();

                jQuery('#instagram_feed_type').find('[value="basic"]').removeAttr('selected').hide();
                jQuery('#instagram_feed_type').find('[value="business"]').attr('selected', 'selected').show();
                jQuery('#instagram_feed_type').find('[value="hashtag"]').show();
                jQuery('.instagram_feed_type').show();
                jQuery('.instagram_hashtag, .instagram_hashtag_type').hide();
            }

            else if( jQuery('#fts-instagram-business-token-button').hasClass( 'fts-social-icon-wrap-active' ) &&
                jQuery('#instagram_feed_type').find('[value="hashtag"]').attr('selected') ||
                jQuery( '#feed_type' ).val() === 'instagram-business-feed-type' &&
                jQuery('#instagram_feed_type').val() === 'hashtag' ) {

                jQuery('#instagram_feed_type').find('[value="basic"]').removeAttr('selected').hide();
                jQuery('#instagram_feed_type').find('[value="business"]').removeAttr('selected').show();
                jQuery('#instagram_feed_type').find('[value="hashtag"]').attr('selected', 'selected').show();

                jQuery('.instagram_hashtag, .instagram_hashtag_type, .instagram_feed_type').show();
            }

            else if( jQuery('#fts-instagram-business-token-button').hasClass( 'fts-social-icon-wrap-active' ) ) {

                jQuery('#instagram_feed_type').find('[value="basic"]').removeAttr('selected').hide();
                jQuery('#instagram_feed_type').find('[value="business"]').attr('selected', 'selected').show();
                jQuery('#instagram_feed_type').find('[value="hashtag"]').removeAttr('selected').show();
                jQuery('.instagram_feed_type').show();
            }

            if( jQuery('#fts-instagram-basic-token-button').hasClass( 'fts-social-icon-wrap-active' ) ||
                jQuery( '#feed_type' ).val() === 'instagram-feed-type' &&
                jQuery('#instagram_feed_type').val() === 'basic' ) {

                jQuery('#instagram_feed_type').find('[value="basic"]').attr('selected', 'selected');
                jQuery('#instagram_feed_type').find('[value="business"]').removeAttr('selected').hide();
                jQuery('#instagram_feed_type').find('[value="hashtag"]').removeAttr('selected').hide();
                jQuery('.instagram_hashtag, .instagram_hashtag_type, .instagram_feed_type').hide();
            }
        }

        jQuery(document).ready(function () {

            setTimeout(function () {

                instagram_feed_type_select()

            }, 10);

            // run function when user clicks on the instagram tab
            jQuery( '#tabs-menu .tab4' ).click( function (e) {
                instagram_feed_type_select();

            });


            jQuery('#instagram_feed_type').bind('change', function (e) {
                if (jQuery('#instagram_feed_type').val() == 'business') {
                    jQuery(".main-instagram-profile-options-wrap").show();
                }
                else {
                    jQuery(".main-instagram-profile-options-wrap").hide();
                }
                if (jQuery('#instagram_feed_type').val() == 'hashtag') {
                    jQuery(".instagram_hashtag, .instagram_hashtag_type").show();
                }
                else {
                    jQuery(".instagram_hashtag, .instagram_hashtag_type").hide();
                }

            }).change();


            //Instagram Load More Options
            jQuery('.fts-instagram-load-more-options-wrap, .fts-instagram-load-more-options2-wrap').hide();
            jQuery('#instagram_load_more_option').bind('change', function (e) {
                if (jQuery('#instagram_load_more_option').val() == 'yes') {
                    jQuery('.fts-instagram-load-more-options-wrap').show();
                    jQuery('.fts-instagram-load-more-options2-wrap').show();
                }

                else {
                    jQuery('.fts-instagram-load-more-options-wrap, .fts-instagram-load-more-options2-wrap').hide();
                }
            }).change();

            //Instagram Business Load More Options
            jQuery('#instagram_profile_wrap').bind('change', function (e) {
                if (jQuery('#instagram_profile_wrap').val() == 'yes') {
                    jQuery('.instagram-profile-options-wrap').show();
                }

                else {
                    jQuery('.instagram-profile-options-wrap').hide();
                }
            }).change();
        });
    </script>

		<?php
	}

	public function twitter_js(){
		?>
		<script>
            jQuery(document).ready(function () {

                //Twitter Feed Type Selector
                jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
                jQuery(".twitter-hashtag-etc-wrap,.hashtag-option-small-text").hide();

                jQuery('#twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#twitter-messages-selector').val() == 'classic') {
                        jQuery(".fts-responsive-tiktok-options-wrap").hide();
                    }
                    else {
                        jQuery(".fts-responsive-tiktok-options-wrap").show();
                    }
                }).change();

                //Twitter Load More Options
                jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                jQuery('#tiktok_load_more_option').bind('change', function (e) {
                    if (jQuery('#tiktok_load_more_option').val() == 'yes') {
                        jQuery('.fts-twitter-load-more-options-wrap').show();
                        jQuery('.fts-twitter-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                    }
                }).change();

                //Twitter Grid Option
                jQuery('.fts-twitter-grid-options-wrap').hide();
                jQuery('#tiktok-grid-option').bind('change', function (e) {
                    if (jQuery('#tiktok-grid-option').val() == 'yes') {
                        jQuery('.fts-twitter-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
                    }
                    else {
                        jQuery('.fts-twitter-grid-options-wrap').hide();
                    }
                }).change();

                //TikTok Stats Options
                jQuery('#twitter-stats-bar').bind('change', function (e) {
                    if (jQuery('#twitter-stats-bar').val() == 'yes') {
                        jQuery('.tiktok-stats-hide').show();
                        // This is the follow button option not in the stats options.
                        jQuery('.tiktok-show-follow-button-hide').hide();
                    }
                    else {
                        jQuery('.tiktok-stats-hide').hide();
                        // This is the follow button option not in the stats options.
                        jQuery('.tiktok-show-follow-button-hide').show();
                    }
                }).change();

            });
		</script>

		<?php
	}

	public function youtube_js(){
		?>
		<script>
            jQuery(document).ready(function () {

                jQuery('select#youtube-messages-selector').bind('change', function (e) {
                    if (jQuery('#youtube-messages-selector').val() == 'channelID') {
                        jQuery('.youtube_name, .youtube_playlistID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_channelID, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'userPlaylist') {
                        jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_playlistID2, .youtube_name2, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'playlistID') {
                        jQuery('.youtube_name, .youtube_channelID, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_playlistID, .youtube_channelID2, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'singleID') {
                        jQuery('.youtube_name,.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_vid_count, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title, .fts-youtube-load-more-options2-wrap').hide();
                        jQuery('.youtube_singleVideoID, .youtube_align_comments_wrap, .youtube_video_single_info_display').show();
                    }
                    else if (jQuery('#youtube-messages-selector').val() == 'username') {
                        jQuery('.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_align_comments_wrap, .youtube_singleVideoID, .youtube_video_single_info_display').hide();
                        jQuery('.youtube_name, .youtube_hide_option, .youtube_video_thumbs_display, .youtube_vid_count, h3.sectioned-options-title').show();
                    }
                }).change();


                jQuery('.youtube_first_video').hide();

                jQuery('select#youtube_columns').change(function () {
                    var youtube_columns_count = jQuery(this).val();

                    if (youtube_columns_count == '1') {
                        jQuery('.youtube_first_video').hide();
                    }
                    else {
                        jQuery('.youtube_first_video').show();
                    }
                }).change();


                jQuery("#youtube_name").change(function () {
                    var feedID = jQuery("input#youtube_name").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name').val(newfeedID);

                    }
                });

                jQuery("#youtube_name2").change(function () {
                    var feedID = jQuery("input#youtube_name2").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name2').val(newfeedID);

                    }
                });

                jQuery("#youtube_channelID").change(function () {
                    var feedID = jQuery("input#youtube_channelID").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID').val(newfeedID);

                    }
                });

                jQuery("#youtube_channelID2").change(function () {
                    var feedID = jQuery("input#youtube_channelID2").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID2').val(newfeedID);

                    }
                });

                jQuery("#youtube_playlistID").change(function () {
                    var feedID = jQuery("input#youtube_playlistID").val();
                    if (feedID.indexOf('&list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID').val(newfeedID);

                    }
                });

                jQuery("#youtube_playlistID2").change(function () {
                    var feedID = jQuery("input#youtube_playlistID2").val();
                    if (feedID.indexOf('&list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID2').val(newfeedID);

                    }
                });

                jQuery("#youtube_singleVideoID").change(function () {
                    var feedID = jQuery("input#youtube_singleVideoID").val();
                    if (feedID.indexOf('watch?v=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_singleVideoID').val(newfeedID);

                    }
                });


				//Youtuve Load More Option
                jQuery('#youtube_load_more_option').bind('change', function (e) {
                    if (jQuery('#youtube_load_more_option').val() == 'yes') {
                        jQuery('.fts-youtube-load-more-options-wrap').show();
                        jQuery('.fts-youtube-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-youtube-load-more-options-wrap, .fts-youtube-load-more-options2-wrap').hide();
                    }
                }).change();
            });
		</script>

		<?php
	}
    //Combine JS
	public function combine_js(){
		?>
		<script>
        // SRL 8-1-22 uncommenting for now, do not remove though.
         //   jQuery(document).ready(function () {

              function combine_js(){
                //COMBINE Grid Options
                jQuery('#combine_grid_option').bind('change', function (e) {
                    if (jQuery('#combine_grid_option').val() == 'yes') {
                        jQuery('.combine-grid-options-wrap ').show();
                    }
                    else {
                        jQuery('.combine-grid-options-wrap ').hide();
                    }
                }).change();

                // NEED FIND A WAY TO TRIGGER CHANGE WHEN CONVERTING WITH CONVERT SHORTCODE BUTTON,
                // IF POSSIBLE NOT SUPER BIG DEAL BECAUSE WHEN YOU SAVE
                // THE PAGE IT WILL SHOW AND HIDE THE PROPER INPUTS OK.
                // FACEBOOK Combine Options
                jQuery('#combine_facebook').bind('change', function (e) {
                   // jQuery('#combine_facebook_name').val('');
                    jQuery('#combine_facebook_name').val(jQuery('#combine_facebook_name').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
                });

                jQuery('select#combine_facebook').bind('change', function (e) {
                    if (jQuery('select#combine_facebook').val() == 'yes') {
                        jQuery('.combine-facebook-wrap').show();
                    }
                    else {
                        jQuery('.combine-facebook-wrap').hide();
                    }
                }).change();

                /*jQuery('select#combine-steams-selector').bind('change', function (e) {
                    if (jQuery('select#combine-steams-selector').val() == 'multiple_facebook') {
                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').show();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').hide();
                        jQuery('.fts-facebook_page-shortcode-form').addClass('multiple_facebook');

                        jQuery('.multiple_facebook select#facebook_page_feed_type option[value="events"]').hide();
                    }
                    else {

                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').hide();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').show();

                        //Remove Controller Class so everything reappears for Facebook Feed
                        if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
                            jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
                        }
                    }
                }).change();*/





                //INSTAGRAM Combine Options
                jQuery('#combine_instagram_hashtag_select').bind('change', function (e) {

                        if ('yes' === jQuery('#combine_instagram_hashtag_select').val()) {

                            jQuery(".combine-instagram-id-option-wrap,.combine-instagram-location-option-text, .combine-instagram-user-option-text").hide();
                            jQuery(".combine-instagram-hashtag-option-text").show();

                            jQuery(".combine-instagram-hashtag-option-text, .combine-instagram-hashtag-option-text, #combine_instagram_name, .combine_instagram_hashtag, .combine_instagram_hashtag_type").show();

                            if (!jQuery('.combine_instagram_type div').hasClass('fts-instagram-hashtag-location-options-message')) {
                                // jQuery(  ".combine_instagram_type").append( fts_notice_message );
                            } else {
                                jQuery(".fts-instagram-hashtag-location-options-message").show();
                            }

                        } else {
                            jQuery(".combine-instagram-user-option-text").show();
                            jQuery(".combine-instagram-hashtag-option-text,.combine-instagram-location-option-text, .combine_instagram_hashtag_type, .combine_instagram_hashtag, .fts-instagram-hashtag-location-options-message").hide();

                        }
                }).change();


                //TWITTER Combine Options
                jQuery('#combine-twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#combine-twitter-messages-selector').val() == 'hashtag') {
                        jQuery(".combine-twitter-hashtag-etc-wrap").show();
                        jQuery(".combine_twitter_name").hide();
                    }
                    else {
                        jQuery(".combine_twitter_name").show();
                        jQuery(".combine-twitter-hashtag-etc-wrap").hide();
                    }
                }).change();


                //YOUTUBE Combine Options
                jQuery('#combine_youtube').bind('change', function (e) {
                    if (jQuery('#combine_youtube').val() == 'yes') {
                       // jQuery('.combine-youtube-wrap').show();
                    }
                    else {
                       // jQuery('.combine-youtube-wrap').hide();
                    }
                }).change();

                jQuery('select#combine_youtube_type').bind('change', function (e) {
                    if (jQuery('#combine_youtube_type').val() == 'channelID') {
                        jQuery('.combine_youtube_name, .combine_playlist_id').hide();
                        jQuery('.combine_channel_id').show();
                    }
                    else if (jQuery('#combine_youtube_type').val() == 'userPlaylist') {
                        jQuery('.combine_channel_id').hide();
                        jQuery('.combine_playlist_id, .combine_youtube_name').show();
                    }
                    else if (jQuery('#combine_youtube_type').val() == 'playlistID') {
                        jQuery('.combine_youtube_name').hide();
                        jQuery('.combine_playlist_id, .combine_channel_id').show();
                    }
                    else {
                        jQuery('.combine_youtube_name').show();
                        jQuery('.combine_playlist_id, .combine_channel_id').hide();
                    }
                }).change();


                jQuery("#combine_youtube_name").change(function () {
                    var feedID = jQuery("input#combine_youtube_name").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#combine_youtube_name').val(newfeedID);

                    }
                });

                jQuery("#combine_channel_id").change(function () {
                    var feedID = jQuery("input#combine_channel_id").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#combine_channel_id').val(newfeedID);

                    }
                });

                jQuery("#combine_playlist_id").change(function () {
                    var feedID = jQuery("input#combine_playlist_id").val();
                    if (feedID.indexOf('&list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#combine_playlist_id').val(newfeedID);

                    }
                });

                // START: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.
                jQuery("#combine_facebook_name").change(function () {
                    var feedID = jQuery("input#combine_facebook_name").val();
                    if (feedID.indexOf('facebook.com') != -1 || feedID.indexOf('facebook.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#combine_facebook_name').val(newfeedID);

                    }
                });


                jQuery("#combine_twitter_name").change(function () {
                    var feedID = jQuery("input#combine_twitter_name").val();
                    if (feedID.indexOf('twitter.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#combine_twitter_name').val(newfeedID);

                    }
                });

                }
              combine_js();

          //  });
		</script>

		<?php
	}
}