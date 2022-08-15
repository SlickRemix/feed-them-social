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
		?>
		<script>
		jQuery(document).ready(function () {
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
            jQuery('#facebook_load_more_option').bind('change', function (e) {
                if (jQuery('#facebook_load_more_option').val() == 'yes') {

                    if (jQuery('#facebook_page_feed_type').val() !== 'album_videos') {
                        jQuery('.fts-facebook-load-more-options-wrap').show();
                    }
                    jQuery('.fts-facebook-load-more-options2-wrap').show();
                }
                else {
                    jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                }
            }).change();

			jQuery('#facebook_show_video_button').change(function () {
				jQuery('.fb-video-play-btn-options-content').toggle();
			});

			// change the feed type 'how to' message when a feed type is selected
			jQuery('#facebook_page_feed_type').change(function () {

                jQuery('.facebook-message-generator').hide();
                jQuery('.' + jQuery(this).val()).fadeIn('fast');
                // if the facebook type select is changed we hide the shortcode code so not to confuse people
                jQuery('.final-shortcode-textarea').hide();
                // only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
                var facebooktype = jQuery("select#facebook_page_feed_type").val();


                if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'album_videos') {
                jQuery('.fts-super-facebook-options-wrap,.align-images-wrap').show();
                jQuery('.fixed_height_option,.main-grid-options-wrap').hide();
                jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').hide();
                }
                else {
                jQuery('.fts-super-facebook-options-wrap,.align-images-wrap ').hide();
                jQuery('.fixed_height_option,.main-grid-options-wrap').show();
                jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Display Posts in Grid', 'feed-them-social' ); ?>')").parent('div').show();
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

                    if (facebooktype == 'album_videos') {
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
                    jQuery('.facebook-post-type-visible').hide();
                    if (facebooktype == 'page') {
                        jQuery('.facebook-post-type-visible').show();
                    }
                    var fb_feed_type_option = jQuery("select#facebook_page_feed_type").val();
                    if (fb_feed_type_option == 'album_photos') {
                        jQuery('.fb_album_photos_id').show();
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
            jQuery(document).ready(function () {

                jQuery('#instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'business') {
                        jQuery(".main-instagram-profile-options-wrap").show();
                    }
                    else {
                        jQuery(".main-instagram-profile-options-wrap").hide();
                    }
                    if (jQuery('#instagram-messages-selector').val() == 'hashtag') {
                        jQuery(".instagram_hashtag, .instagram_hashtag_type").show();
                    }
                    else {
                        jQuery(".instagram_hashtag, .instagram_hashtag_type").hide();
                    }
                }).change();

                //Twitter Load More Options
                jQuery('.fts-instagram-load-more-options-wrap, .fts-instagram-load-more-options2-wrap').hide()
                jQuery('#instagram_load_more_option').bind('change', function (e) {
                    if (jQuery('#instagram_load_more_option').val() == 'yes') {
                        jQuery('.fts-instagram-load-more-options-wrap').show();
                        jQuery('.fts-instagram-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-instagram-load-more-options-wrap, .fts-instagram-load-more-options2-wrap').hide();
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
                    if (jQuery('#twitter-messages-selector').val() == 'hashtag') {
                        jQuery( '.twitter-hashtag-etc-wrap' ).css('display', 'inline-block');
                        jQuery(".hashtag-option-small-text").show();
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").hide();
                    }
                    else {
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
                        jQuery(".twitter-hashtag-etc-wrap,.hashtag-option-small-text").hide();
                    }
                }).change();

                //Twitter Load More Options
                jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                jQuery('#twitter_load_more_option').bind('change', function (e) {
                    if (jQuery('#twitter_load_more_option').val() == 'yes') {
                        jQuery('.fts-twitter-load-more-options-wrap').show();
                        jQuery('.fts-twitter-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                    }
                }).change();

                //Twitter Grid Option
                jQuery('.fts-twitter-grid-options-wrap').hide();
                jQuery('#twitter-grid-option').bind('change', function (e) {
                    if (jQuery('#twitter-grid-option').val() == 'yes') {
                        jQuery('.fts-twitter-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
                    }
                    else {
                        jQuery('.fts-twitter-grid-options-wrap').hide();
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
                        jQuery('.youtube_name,.youtube_playlistID, .youtube_channelID, .youtube_channelID2, .youtube_playlistID2, .youtube_name2, .youtube_vid_count, .youtube_hide_option, .youtube_video_thumbs_display, h3.sectioned-options-title').hide();
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