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
			jQuery('select#fb_hide_like_box_button').bind('change', function (e) {
				if (jQuery('select#fb_hide_like_box_button').val() == 'no') {
					jQuery('.like-box-wrap').show();
				}
				else {
					jQuery('.like-box-wrap').hide();
				}
			});

			jQuery('#facebook_show_video_button').change(function () {
				jQuery('.fb-video-play-btn-options-content').toggle();
			});

			// change the feed type 'how to' message when a feed type is selected
			jQuery('#facebook-messages-selector').change(function () {
			jQuery('.facebook-message-generator').hide();
			jQuery('.' + jQuery(this).val()).fadeIn('fast');
			// if the facebook type select is changed we hide the shortcode code so not to confuse people
			jQuery('.final-shortcode-textarea').hide();
			// only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
			var facebooktype = jQuery("select#facebook-messages-selector").val();


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
            if (window.location.hash && getQueryString('feed_type') == 'facebook') {
                jQuery('#feed-selector-form').find('option[value=fts-fb-page-shortcode-form]').attr('selected', 'selected');
                jQuery('#fts-tab-content1 .fts-fb-page-shortcode-form').show();
                jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
                jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token' ) ); ?>');
            }

            jQuery('#shortcode-form-selector, #facebook-messages-selector').bind('change', function (e) {
                if (jQuery('#facebook-messages-selector').val() == 'page' || jQuery('#facebook-messages-selector').val() == 'album_photos' || jQuery('#facebook-messages-selector').val() == 'albums' || jQuery('#facebook-messages-selector').val() == 'album_videos') {
                    jQuery('#fb_page_id, #fb_access_token').val('');
                    jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
                    jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token' ) ); ?>');
                }
            });

            /* FB Pages, Ablums, Photos etc */
            if (window.location.hash && getQueryString('feed_type') == 'facebook_reviews') {
                jQuery('#feed-selector-form').find('option[value=fts-fb-page-shortcode-form]').attr('selected', 'selected');
                jQuery('#fts-tab-content1 .fts-fb-page-shortcode-form').show();

                jQuery('#facebook-messages-selector').find('option[value=reviews]').attr('selected', 'selected');
                jQuery('.facebook-reviews-wrap, .inst-text-facebook-reviews').show();
                jQuery('.align-images-wrap,.facebook-title-options-wrap, .facebook-popup-wrap, .fts-required-more-posts, .fts-required-more-posts, .inst-text-facebook-page').hide();

                jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>');
                jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>');
            }

            jQuery('#shortcode-form-selector, #facebook-messages-selector').bind('change', function (e) {
                if (jQuery('#facebook-messages-selector').val() == 'reviews') {
                    jQuery('#fb_page_id, #fb_access_token').val('');
                    jQuery('#fb_page_id').val(jQuery('#fb_page_id').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id_biz' ) ); ?>');
                    jQuery('#fb_access_token').val(jQuery('#fb_access_token').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_biz' ) ); ?>');
                }
            });

            // facebook show grid options
            jQuery('#fb-grid-option').bind('change', function (e) {
                if (jQuery('#fb-grid-option').val() == 'yes') {
                    jQuery('.fts-facebook-grid-options-wrap').show();
                    jQuery(".feed-them-social-admin-input-label:contains('<?php echo esc_js( 'Center Facebook Container?', 'feed-them-social' ); ?>')").parent('div').show();
                }
                else {
                    jQuery('.fts-facebook-grid-options-wrap').hide();
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

			//Facebook Display Popup option
            jQuery('#facebook_popup').bind('change', function (e) {
                if (jQuery('#facebook_popup').val() == 'yes') {
                    jQuery('.display-comments-wrap').show();
                }
                else {
                    jQuery('.display-comments-wrap').hide();
                }
            });

			// facebook show load more options
            jQuery('#fb_load_more_option').bind('change', function (e) {
                if (jQuery('#fb_load_more_option').val() == 'yes') {

                    if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
                        jQuery('.fts-facebook-load-more-options-wrap').show();
                    }
                    jQuery('.fts-facebook-load-more-options2-wrap').show();
                }
                else {
                    jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
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
                var fb_feed_type_option = jQuery("select#facebook-messages-selector").val();
                if (fb_feed_type_option == 'album_photos') {
                    jQuery('.fb_album_photos_id').show();
                }
                else {
                    jQuery('.fb_album_photos_id').hide();
                }
            });
		});
		</script>

		<?php
	}

	public function instagram_js(){
		?>
		<script>
            jQuery(document).ready(function () {

                /* Instagram */
                function getQueryString(Param) {
                    return decodeURI(
                        (RegExp('[#|&]' + Param + '=' + '(.+?)(&|$)').exec(location.hash) || [, null])[1]
                    );
                }

                if (window.location.hash && getQueryString('feed_type') == 'instagram') {
                    jQuery('#feed-selector-form').find('option[value=instagram-shortcode-form]').attr('selected', 'selected');
                    jQuery('.shortcode-generator-form.instagram-shortcode-form').show();
                    jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_id' ) ); ?>');
                    jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_api_token' ) ); ?>');
                }

                jQuery('#shortcode-form-selector, #instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'basic') {
                        jQuery('#instagram_id, #insta_access_token').val('');
                        jQuery('#instagram_id').val(jQuery('#instagram_id').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_id' ) ); ?>');
                        jQuery('#insta_access_token').val(jQuery('#insta_access_token').val() + '<?php echo esc_js( get_option( 'fts_instagram_custom_api_token' ) ); ?>');
                    }
                    else if (jQuery('#instagram-messages-selector').val() == 'hashtag' || jQuery('#instagram-messages-selector').val() == 'business') {
                        jQuery('#instagram_id').val('<?php echo esc_js( get_option( 'fts_facebook_instagram_custom_api_token_user_id' ) ); ?>');
                        jQuery('#insta_access_token').val('<?php echo esc_js( get_option( 'fts_facebook_instagram_custom_api_token' ) ); ?>');
                    }
                    else {
                        jQuery('#instagram_id').val('');
                    }

                });

	            <?php if ( ! is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) { ?>
                jQuery('#instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'location') {
                        jQuery("#instagram_id").hide();
                        jQuery('<div class="feed-them-social-admin-input-default fts-custom-premium-required">Must have <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium</a> to edit.</div>').insertAfter('.feed-them-social-admin-input-label.instagram-location-option-text');
                        jQuery(".feed-them-social-admin-submit-btn").hide();
                    }
                    else {
                        jQuery("#instagram_id").show();
                        jQuery(".fts-custom-premium-required").hide();
                        jQuery(".feed-them-social-admin-submit-btn").show();
                    }

                });
	            <?php } ?>
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
                        jQuery(".hashtag-option-small-text,.twitter-hashtag-etc-wrap").show();
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").hide();
                    }
                    else {
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
                        jQuery(".twitter-hashtag-etc-wrap,.hashtag-option-small-text").hide();
                    }
                });

                //Twitter Load More Options
                jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide()
                jQuery('#twitter_load_more_option').bind('change', function (e) {
                    if (jQuery('#twitter_load_more_option').val() == 'yes') {
                        jQuery('.fts-twitter-load-more-options-wrap').show();
                        jQuery('.fts-twitter-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-twitter-load-more-options-wrap, .fts-twitter-load-more-options2-wrap').hide();
                    }
                });

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
                });
            });
		</script>

		<?php
	}

	public function youtube_js(){
		?>
		<script>
            jQuery(document).ready(function () {
				//Youtuve Load More Option
                jQuery('#youtube_load_more_option').bind('change', function (e) {
                    if (jQuery('#youtube_load_more_option').val() == 'yes') {
                        jQuery('.fts-youtube-load-more-options-wrap').show();
                        jQuery('.fts-youtube-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-youtube-load-more-options-wrap, .fts-youtube-load-more-options2-wrap').hide();
                    }
                });
            });
		</script>

		<?php
	}

	public function combine_js(){
		?>
		<script>
            jQuery(document).ready(function () {

                //COMBINE Grid Options
                jQuery('#combine_grid_option').bind('change', function (e) {
                    if (jQuery('#combine_grid_option').val() == 'yes') {
                        jQuery('.combine-grid-options-wrap ').show();
                    }
                    else {
                        jQuery('.combine-grid-options-wrap ').hide();
                    }
                });

                // FACEBOOK Combine Options
                jQuery('#combine_facebook').bind('change', function (e) {
                    jQuery('#combine_facebook_name').val('');
                    jQuery('#combine_facebook_name').val(jQuery('#combine_facebook_name').val() + '<?php echo esc_js( get_option( 'fts_facebook_custom_api_token_user_id' ) ); ?>');
                });

                jQuery('select#combine_facebook').bind('change', function (e) {
                    if (jQuery('select#combine_facebook').val() == 'yes') {
                        jQuery('.combine-facebook-wrap').show();
                    }
                    else {
                        jQuery('.combine-facebook-wrap').hide();
                    }
                });

                jQuery('select#combine-steams-selector').bind('change', function (e) {
                    if (jQuery('select#combine-steams-selector').val() == 'multiple_facebook') {
                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').show();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').hide();
                        jQuery('.fts-facebook_page-shortcode-form').addClass('multiple_facebook');

                        jQuery('.multiple_facebook select#facebook-messages-selector option[value="events"]').hide();
                    }
                    else {

                        jQuery('.facebook_options_wrap,#fts-fb-page-form, .facebook_hide_thumbnail, .facebook_hide_date, .facebook_hide_name, .facebook_show_media ').hide();
                        jQuery('.combine_streams_options_wrap, .fts-required-more-posts').show();

                        //Remove Controller Class so everything reappears for Facebook Feed
                        if (jQuery('.fts-facebook_page-shortcode-form').hasClass('multiple_facebook')) {
                            jQuery('.fts-facebook_page-shortcode-form').removeClass('multiple_facebook');
                        }
                    }
                });

                //INSTAGRAM Combine Options
                jQuery('#combine_instagram_type').bind('change', function (e) {
                    if (jQuery('#combine_instagram_type').val() == 'hashtag') {

                        jQuery(".combine-instagram-id-option-wrap,.combine-instagram-location-option-text, .combine-instagram-user-option-text").hide();
                        jQuery(".combine-instagram-hashtag-option-text").show();

                        jQuery(".combine-instagram-hashtag-option-text, .combine-instagram-hashtag-option-text, #combine_instagram_name, .combine_instagram_hashtag, .combine_instagram_hashtag_type").show();

                        if(!jQuery('.combine_instagram_type div').hasClass('fts-instagram-hashtag-location-options-message')){
                            // jQuery(  ".combine_instagram_type").append( fts_notice_message );
                        }
                        else {
                            jQuery(".fts-instagram-hashtag-location-options-message").show();
                        }

                    }
                    else {
                        jQuery(".combine-instagram-user-option-text").show();
                        jQuery(".combine-instagram-hashtag-option-text,.combine-instagram-location-option-text, .combine_instagram_hashtag_type, .combine_instagram_hashtag, .fts-instagram-hashtag-location-options-message").hide();

                    }
                });

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
                });

                jQuery('#combine_twitter').bind('change', function (e) {
                    if (jQuery('#combine_twitter').val() == 'yes') {
                        jQuery('.combine-twitter-wrap').show();
                    }
                    else {
                        jQuery('.combine-twitter-wrap').hide();
                    }
                });

                //YOUTUBE Combine Options
                jQuery('#combine_youtube').bind('change', function (e) {
                    if (jQuery('#combine_youtube').val() == 'yes') {
                        jQuery('.combine-youtube-wrap').show();
                    }
                    else {
                        jQuery('.combine-youtube-wrap').hide();
                    }
                });

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
                });


            });

		</script>

		<?php
	}
}