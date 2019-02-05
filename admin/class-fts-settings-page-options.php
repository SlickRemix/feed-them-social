<?php
/**
 * Feed Them Social - Settings Options
 *
 * This class is used for the settings options on the settiings page
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2018, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

class FTS_Settings_Page_Options {
	/**
	 * Construct
	 *
	 * FTS_Settings_Page_Options constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct() {
	}

	public function settings_page_options ($facebookReviewsTokenCheck, $limitforpremium, $step2_custom_message){
		$feed_settings_array = array(

			// ******************************************
			// Combine Streams Feed
			// ******************************************
			'combine_streams' => array(
				'shorcode_label'     => 'mashup',
				'section_attr_key'   => 'combine_',
				'section_title'      => __( 'Combine Streams Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'fts-combine-steams-shortcode-form',
				// Form Info
				'form_wrap_classes'  => 'combine-steams-shortcode-form',
				'form_wrap_id'       => 'fts-combine-steams-form',

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feeds To Combine', 'feed-them-social' ),
					'select_wrap_classes' => 'fts-combine-steams-selector',
					'select_classes' => '',
					'select_name'    => 'combine-steams-selector',
					'select_id'      => 'combine-steams-selector',
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// All Feeds (1 of each for now)
					1 => array(
						'value' => 'all',
						'title' => __( 'All Feeds', 'feed-them-social' ),
					),

					// All Feeds (1 of each for now)
					2 => array(
						'value' => 'multiple_facebook',
						'title' => __( 'Multiple Facebook Feeds', 'feed-them-social' ),
					),
				),
				'premium_msg_boxes'  => array(
					'main_select' => array(
						'req_plugin' => 'combine_streams',
						'msg'        => 'With this extension you can mix a Facebook, Instagram, Twitter, Youtube and Pinterest posts all in one feed. The other feature this exentsion gives you is the abillity to mix multiple Facebook accounts into one feed!
<a href="https://feedthemsocial.com/feed-them-social-combined-streams/" target="_blank">View Combined Streams Demo</a> . <a href="https://feedthemsocial.com/feed-them-social-combined-streams/#combined-fb-streams" target="_blank">View Combined Facebook Streams Demo</a>',
                    )
				),
				'short_attr_final'   => 'yes',

				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				'main_options'       => array(

					// Combined Total # of Posts
					array(
						'grouped_options_title' => __( 'Combined Stream', 'feed-them-social' ),
						'option_type' => 'input',
						'label'       => __( 'Combined Total # of Posts', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_post_count',
						'name'        => 'combine_post_count',
						'value'       => '',
						'placeholder' => __( '6 is the default number', 'feed-them-social' ),
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'posts',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => 'posts=6',
						),
					),

					// # of Posts per Social Network
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_social_network_post_count',
						'label'       => __( '# of Posts per Social Network', 'feed-them-social' ) . '<br/><small>' . __( 'NOT the combined total', 'feed-them-social' ) . '</small>',
						'type'        => 'text',

						// 'instructional-text' => __('', 'feed-them-social'),
						'id'          => 'combine_social_network_post_count',
						'name'        => 'combine_social_network_post_count',
						'value'       => '',
						'placeholder' => __( '1 is the default number', 'feed-them-social' ),
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'social_network_posts',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => 'social_network_posts=1',
						),
					),

					// Facebook Amount of words
					array(
						'option_type' => 'input',
						'label'       => __( 'Amount of words per post', 'feed-them-social' ) . '<br/><small>' . __( 'Type 0 to remove the posts description', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_word_count_option',
						'name'        => 'combine_word_count_option',
						'placeholder' => '45 ' . __( 'is the default number', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'   => 'words',
							'empty_error' => 'set',
							'empty_error_value' => 'words=45',
						),
					),

					// Center Container
					array(
						'option_type' => 'select',
						'label'       => __( 'Center Feed Container', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_container_position',
						'name'        => 'combine_container_position',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => 'center_container',
						),
					),

					// Page Fixed Height
					array(
						'input_wrap_class' => 'combine_height',
						'option_type' => 'input',
						'label'       => __( 'Feed Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_height',
						'name'        => 'combine_height',
						'value'       => '',
						'req_plugin'  => 'combine_streams',
						'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'height',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
					),

					// Background Color
					array(
						'option_type'  => 'input',
						'color_picker' => 'yes',
						'input_wrap_class' => 'combine_background_color',
						'label'        => __( 'Background Color', 'feed-them-social' ),
						'type'         => 'text',
						'id'           => 'combine_background_color',
						'name'         => 'combine_background_color', // Relative to JS.
						'req_plugin'   => 'combine_streams',
						'short_attr'   => array(
							'attr_name'    => 'background_color',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
					),

					// Social Icon
					array(
						'input_wrap_class' => 'combine_show_social_icon',
						'option_type' => 'select',
						'label'       => __( 'Show Social Icon', 'feed-them-social' ) . '<br/><small>' . __( 'Right, Left or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_show_social_icon',
						'name'        => 'combine_show_social_icon',
						'req_plugin'  => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
							array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_social_icon',
						),
					),

					// Show Description below image or video Name
					array(
						'input_wrap_class' => 'combine_show_media',
						'option_type' => 'select',
						'label'       => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default) or Top of Post', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_show_media',
						'name'        => 'combine_show_media',
						'req_plugin'  => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
								'value' => 'bottom',
							),
							array(
								'label' => __( 'Above Username, Date & Description', 'feed-them-social' ),
								'value' => 'top',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_media',
						),
					), // Show Username
					array(
						'input_wrap_class' => 'combine_hide_date',
						'option_type' => 'select',
						'label'       => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_hide_date',
						'name'        => 'combine_hide_date',
						'req_plugin'  => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_date',
						),
					),

					// Show Date
					array(
						'input_wrap_class' => 'combine_hide_name',
						'option_type' => 'select',
						'label'       => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'combine_hide_name',
						'name'        => 'combine_hide_name',
						'req_plugin'  => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_name',
						),
					),

					// Padding
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_padding',
						'label'       => __( 'Padding', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_padding',
						'name'        => 'combine_padding',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'padding',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
					),

					// Combine Facebook
					array(
						'grouped_options_title' => __( 'Facebook', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Combine Facebook', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_facebook',
						'name'        => 'combine_facebook',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
							'ifs' => 'combine_facebook',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-combine-facebook-wrap',
						),
					),

					// Combine Facebook ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_facebook_name',
						'label'       => __( 'Facebook ID or Name', 'feed-them-social' ),
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-facebook-feed-styles-submenu-page" target="_blank">Facebook Options</a> page. If that is in place your page ID should appear in the input below.',
						'type'        => 'text',
						'id'          => 'combine_facebook_name',
						'name'        => 'combine_facebook_name',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'facebook_name',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-facebook-wrap',
						),
						'sub_options_end' => true,
					),

					// Combine Twitter
					array(
						'grouped_options_title' => __( 'Twitter', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Combine Twitter', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_twitter',
						'name'        => 'combine_twitter',
						'req_plugin'  => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-combine-twitter-wrap',
						),
					),

					// Feed Type Selection
					array(
						'option_type' => 'select',
						'label'       => __( 'Feed Type', 'feed-them-social' ),
						'select_wrap_classes' => 'combine-twitter-gen-selection',
						'select_classes' => '',
						'name'        => 'combine-twitter-messages-selector',
						'id'          => 'combine-twitter-messages-selector',
						'req_plugin'  => 'combine_streams',

						// Feed Types and their options
						'options'     => array(

							// User Feed
							array(
								'value' => 'user',
								'label' => __( 'User Feed', 'feed-them-social' ),
							),

							// hastag Feed
							array(
								'value' => 'hashtag',
								'label' => __( 'Hashtag, Search and more Feed', 'feed-them-social' ),
							),
						),
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-twitter-wrap',
						),
					),

					// 'short_attr_final' => 'yes',
					// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
					// Twitter Search Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_twitter_hashtag_etc_name',
						'label'       => __( 'Twitter Search Name (required)', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_twitter_hashtag_etc_name',
						'name'        => 'combine_twitter_hashtag_etc_name',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'search',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
							'empty_error_if' => array(
								'attribute' => 'select#combine-twitter-messages-selector',
								'operator' => '==',
								'value'    => 'hashtag',
							),
						),
						'req_plugin'  => 'combine_streams',

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-twitter-hashtag-etc-wrap',
							'sub_options_title' => __( 'Twitter Search', 'feed-them-social' ),
						),
						'sub_options_end' => true,
					),

					// Twitter Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_twitter_name',
						'label'       => __( 'Twitter Name', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_twitter_name',
						'name'        => 'combine_twitter_name',
						'instructional-text' => '<span class="must-copy-twitter-name">' . __( 'You must copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __( 'Twitter Name', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ) . '</span>',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'twitter_name',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
							'empty_error_if' => array(
								'attribute' => 'select#combine-twitter-messages-selector',
								'operator' => '==',
								'value'    => 'user',
							),
						),
						'req_plugin'  => 'combine_streams',
						'sub_options_end' => 2,
					),

					// Combine Instagram
					array(
						'grouped_options_title' => __( 'Instagram', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Combine Instagram', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_instagram',
						'name'        => 'combine_instagram',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-combine-instagram-wrap',
						),
					),

					// Instagram Type
					array(
						'input_wrap_class' => 'combine_instagram_type',
						'option_type' => 'select',
						'label'       => __( 'Instagram Type', 'feed-them-social' ),
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-instagram-feed-styles-submenu-page" target="_blank">Instagram Options</a>.',
						'type'        => 'text',
						'id'          => 'combine_instagram_type',
						'name'        => 'combine_instagram_type',
						'options'     => array(

							// User Feed
							array(
								'value' => 'user',
								'label' => __( 'User Feed', 'feed-them-social' ),
							),

							// hastag Feed
							array(
								'value' => 'hashtag',
								'label' => __( 'Hashtag Feed', 'feed-them-social' ),
							),

							// location Feed
							array(
								'value' => 'location',
								'label' => __( 'Location Feed', 'feed-them-social' ),
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => 'instagram_type',
							'ifs' => 'combine_instagram',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-instagram-wrap',
						),
					),

					// Combine Convert Instagram Name
					// array(
					// 'option_type' => 'input',
					// 'input_wrap_class' => 'combine-instagram-id-option-wrap',
					// 'label' => __('Convert Instagram Name to ID', 'feed-them-social'),
					// 'type' => 'text',
					// 'id' => 'combine_convert_instagram_username',
					// 'name' => 'combine_convert_instagram_username',
					// 'instructional-text' => __('You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below', 'feed-them-social'),
					// 'req_plugin' => 'combine_streams',
					// Relative to JS.
					// 'short_attr' => array(
					// 'attr_name' => '',
					// 'ifs' => 'combine_instagram',
					// 'no_attribute' => 'yes'
					// ),
					// ),
					// Instagram ID
					array(
						'option_type' => 'input',

						// 'input_wrap_class' => 'combine_instagram_name',
						'label'       => array(
							1 => array(
								'text' => __( 'Instagram ID # (required)', 'feed-them-social' ),
								'class' => 'combine-instagram-user-option-text',
							),
							2 => array(
								'text' => __( 'Hashtag (required)', 'feed-them-social' ),
								'class' => 'combine-instagram-hashtag-option-text',
							),
							3 => array(
								'text' => __( 'Location ID (required)', 'feed-them-social' ),
								'class' => 'combine-instagram-location-option-text',
							),
						),
						'type'        => 'text',
						'id'          => 'combine_instagram_name',
						'name'        => 'combine_instagram_name',
						'required'    => 'combine_streams',
						'instructional-text' => array(
							1 => array(
								'text' => __( '<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social' ),
								'class' => 'combine-instagram-user-option-text',
							),
							2 => array(
								'text' => __( 'Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social' ),
								'class' => 'combine-instagram-hashtag-option-text',
							),
							3 => array(
								'text' => __( '<strong>NOTE:</strong> The post count may not count proper in some location instances because private instagram photos are in the mix. We cannot pull private accounts photos in any location feed. Add your Location ID below.', 'feed-them-social' ),
								'class' => 'combine-instagram-location-option-text',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'instagram_name',
							'ifs' => 'combine_instagram',
							'var_final_if' => 'no',
							'empty_error' => 'set',
							'empty_error_value' => '',
						),
						'sub_options_end' => 2,
					),

					// Combine Pinterest
					array(
						'grouped_options_title' => __( 'Pinterest', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Combine Pinterest', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_pinterest',
						'name'        => 'combine_pinterest',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-combine-pinterest-wrap',
						),
					),

					// Pinterest Type
					array(
						'input_wrap_class' => 'combine_pinterest_type',
						'option_type' => 'select',
						'label'       => __( 'Pinterest Type', 'feed-them-social' ),
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-pinterest-feed-styles-submenu-page" target="_blank">Pinterest Options</a> page then copy your <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">Pinterest and or Board Name</a> and paste them below based on your selection. A users board list is not available in this feed.',
						'type'        => 'text',
						'id'          => 'combine_pinterest_type',
						'name'        => 'combine_pinterest_type',
						'options'     => array(

							// Single Board Pins
							array(
								'label' => __( 'Latest Pins from a User', 'feed-them-social' ),
								'value' => 'pins_from_user',
							),

							// Single Board Pins
							array(
								'label' => __( 'Pins From a Specific Board', 'feed-them-social' ),
								'value' => 'single_board_pins',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => 'pinterest_type',
							'ifs' => 'combine_pinterest',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-pinterest-wrap',
						),
					),

					// Pinterest Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_pinterest_name',
						'label'       => __( 'Pinterest Name', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_pinterest_name',
						'name'        => 'combine_pinterest_name',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'pinterest_name',
							'ifs' => 'combine_pinterest',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => '',
						),
					),

					// Pinterest Board ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_board_id',
						'label'       => __( 'Pinterest Board ID', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_board_id',
						'name'        => 'combine_board_id',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'board_id',
							'ifs' => 'pinterest_single_board_pins',
						),
						'sub_options_end' => 2,
					),

					// Combine Youtube
					array(
						'grouped_options_title' => __( 'Youtube', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Combine Youtube', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_youtube',
						'name'        => 'combine_youtube',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-combine-youtube-wrap',
						),
					),

					// Youtube Type
					array(
						'input_wrap_class' => 'combine_youtube_type',
						'option_type' => 'select',
						'label'       => __( 'Youtube Type', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_youtube_type',
						'name'        => 'combine_youtube_type',
						'options'     => array( // Channel Feed
							array(
								'label' => __( 'Channel Feed', 'feed-them-social' ),
								'value' => 'channelID',
							), // Channel Playlist Feed
							array(
								'label' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ),
								'value' => 'playlistID',
							),

							// User's Most Recent Videos
							array(
								'label' => __( 'User\'s Most Recent Videos', 'feed-them-social' ),
								'value' => 'username',
							),

							// User's Playlist
							array(
								'label' => __( 'User\'s Specific Playlist', 'feed-them-social' ),
								'value' => 'userPlaylist',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => '',
							'no_attribute' => 'yes',
							'ifs' => 'combine_youtube',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-youtube-wrap',
						),
					),

					// Youtube Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_youtube_name',
						'label'       => __( 'YouTube Username', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_youtube_name',
						'name'        => 'combine_youtube_name',
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Username</a> and paste it below.',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'youtube_name',
							'ifs' => 'combine_youtube',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => '',
						),
					),

					// YouTube Playlist ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_playlist_id',
						'label'       => __( 'YouTube Playlist ID', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_playlist_id',
						'name'        => 'combine_playlist_id',
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Playlist ID</a> and paste them below.',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'playlist_id',
							'ifs' => 'combine_youtube',
						),
					),

					// YouTube Channel ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'combine_channel_id',
						'label'       => __( 'YouTube Channel ID', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_channel_id',
						'name'        => 'combine_channel_id',
						'instructional-text' => '<strong>REQUIRED:</strong> Make sure you have an <strong>API Key</strong> or <strong>Access Token</strong> in place on the <a class="not-active-title" href="admin.php?page=fts-youtube-feed-styles-submenu-page" target="_blank">Youtube Options</a> page then copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Channel ID</a> and paste it below.',
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'channel_id',
							'ifs' => 'combine_youtube',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Combine Streams Grid Options
					// ******************************************
					// Facebook Page Display Posts in Grid
					array(
						'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
						'input_wrap_class' => 'combine_grid_option',
						'option_type' => 'select',
						'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_grid_option',
						'name'        => 'combine_grid_option',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'combine_streams',
						'short_attr'  => array(
							'attr_name'    => 'grid',
							'empty_error'  => 'set',
							'set_operator' => '==',
							'set_equals'   => 'yes',
							'empty_error_value' => '',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-main-grid-options-wrap',
						),
					),

					// Grid Column Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Column Width', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_grid_column_width',
						'name'        => 'combine_grid_column_width',
						'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
						'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'column_width',
							'empty_error' => 'set',
							'empty_error_value' => 'column_width=310px',
							'ifs' => 'combine_grid',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'combine-grid-options-wrap',
						),
					),

					// Grid Spaces Between Posts
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'combine_grid_space_between_posts',
						'name'        => 'combine_grid_space_between_posts',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'req_plugin'  => 'combine_streams',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'space_between_posts',
							'empty_error' => 'set',
							'empty_error_value' => 'space_between_posts=10px',
							'ifs' => 'combine_grid',
						),
						'sub_options_end' => 2,
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'main_select'      => array(
						'if' => array(
							'class'    => 'select#shortcode-form-selector',
							'operator' => '==',
							'value'    => 'combine-steams-shortcode-form',
						),
					),
					'combine_facebook' => array(
						'if' => array(
							'class'    => 'select#combine_facebook',
							'operator' => '==',
							'value'    => 'yes',
						),
					),

					// 'combine_twitter' => array(
					// 'if' => array(
					// 'class' => 'select#combine-twitter-messages-selector',
					// 'operator' => '==',
					// 'value' => '',
					// ),
					// ),
					// 'combine_twitter_search' => array(
					// 'if' => array(
					// 'class' => 'select#combine-twitter-messages-selector',
					// 'operator' => '==',
					// 'value' => '',
					// ),
					// ),
					'combine_instagram' => array(
						'if' => array(
							'class'    => 'select#combine_instagram',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'combine_pinterest' => array(
						'if' => array(
							'class'    => 'select#combine_pinterest',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'combine_youtube'  => array(
						'if' => array(
							'class'    => 'select#combine_youtube',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'combine_load_more' => array(
						'if' => array(
							'class'    => 'select#fb_load_more_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'combine_grid'     => array(
						'if' => array(
							'class'    => 'select#combine_grid_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'yt_username'      => array(
						'if' => array(
							'class'    => 'select#combine_youtube_type',
							'operator' => '==',
							'value'    => 'username',
						),
					),
					'yt_userPlaylist'  => array(
						'if' => array(
							'class'    => 'select#combine_youtube_type',
							'operator' => '==',
							'value'    => 'userPlaylist',
						),
					),
					'yt_channelID'     => array(
						'if' => array(
							'class'    => 'select#combine_youtube_type',
							'operator' => '==',
							'value'    => 'channelID',
						),
					),
					'yt_playlistID'    => array(
						'if' => array(
							'class'    => 'select#combine_youtube_type',
							'operator' => '==',
							'value'    => 'playlistID',
						),
					),
					'pinterest_single_board_pins' => array(
						'if' => array(
							'class'    => 'select#combine_pinterest_type',
							'operator' => '==',
							'value'    => 'single_board_pins',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'Combine Streams Shortcode', 'feed-them-social' ),
				'generator_class'    => 'combine-streams-final-shortcode',
			), // End Combine Streams

			// ******************************************
			// Facebook Page Feed
			// ******************************************
			'facebook'        => array(
				'section_attr_key'   => 'facebook_',
				'section_title'      => __( 'Facebook Page Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'fts-facebook_page-shortcode-form',

				// Form Info
				'form_wrap_classes'  => 'fts-fb-page-shortcode-form',
				'form_wrap_id'       => 'fts-fb-page-form',

				// Token Check
				'token_check'        => $facebookReviewsTokenCheck,

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feed Type', 'feed-them-social' ),
					'select_wrap_classes' => 'fts-social-selector',
					'select_classes' => '',
					'select_name'    => 'facebook-messages-selector',
					'select_id'      => 'facebook-messages-selector',
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// Facebook Page
					array(
						'value' => 'page',
						'title' => __( 'Facebook Page', 'feed-them-social' ),
					),

					// Facebook Page List of Events
					// array(
					// 'value' => 'events',
					// 'title' => __('Facebook Page List of Events', 'feed-them-social'),
					// ),
					// Facebook Page Single Event Posts
					// array(
					// 'value' => 'event',
					// 'title' => __('Facebook Page Single Event Posts', 'feed-them-social'),
					// ),
					// Facebook Group
					// array(
					// 'value' => 'group',
					// 'title' => __('Facebook Group', 'feed-them-social'),
					// ),
					// Facebook Album Photos
					array(
						'value' => 'album_photos',
						'title' => __( 'Facebook Album Photos', 'feed-them-social' ),
					),

					// Facebook Album Covers
					array(
						'value' => 'albums',
						'title' => __( 'Facebook Album Covers', 'feed-them-social' ),
					),

					// Facebook Videos
					array(
						'value' => 'album_videos',
						'title' => __( 'Facebook Videos', 'feed-them-social' ),
					),

					// Facebook Page Reviews
					array(
						'value' => 'reviews',
						'title' => __( 'Facebook Page Reviews', 'feed-them-social' ),
					),
				),
				'premium_msg_boxes'  => array(
					'album_videos' => array(
						'req_plugin' => 'fts_premium',
						'msg'        => 'The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your wordpress site! <a href="https://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br /><br />Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too! <a href="https://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a>',
					),
					'reviews'      => array(
						'req_plugin' => 'facebook_reviews',
						'msg'        => 'The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="https://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a>',
					),
				),
				'short_attr_final'   => 'yes',
				'main_options'       => array(

					// Feed Type
					array(
						'option_type' => 'select',
						'id'          => 'facebook-messages-selector',
						'name'        => 'facebook-messages-selector',

						// DONT SHOW HTML
						'no_html'     => 'yes',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'type',
						),
					),

					// Facebook ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'fb_page_id',
						'label'       => __( 'Facebook ID (required)', 'feed-them-social' ),
						'instructional-text' => array(
							array(
								'text' => __( 'If your Access Token is set on the Facebook Options page of our plugin your ID should appear below.', 'feed-them-social' ),
								'class' => 'facebook-message-generator page inst-text-facebook-page',
							),
							array(
								'text' => __( 'Copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-group-id/" target="_blank">' . __( 'Facebook Group ID', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ),
								'class' => 'facebook-message-generator group inst-text-facebook-group',
							),
							array(
								'text' => __( 'Copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __( 'Facebook Page ID', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below. PLEASE NOTE: This will only work with Facebook Page Events and you cannot have more than 25 events on Facebook.', 'feed-them-social' ),
								'class' => 'facebook-message-generator event-list inst-text-facebook-event-list',
							),
							array(
								'text' => __( 'Copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . __( 'Facebook Event ID', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ),
								'class' => 'facebook-message-generator event inst-text-facebook-event',
							),
							array(
								'text' => __( 'To show a specific Album copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . __( 'Facebook Album ID', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the third input below. If you want to show all your uploaded photos leave the Album ID input blank.', 'feed-them-social' ),
								'class' => 'facebook-message-generator album_photos inst-text-facebook-album-photos',
							),
							array(
                                'text' => __( 'If your Access Token is set on the Facebook Options page of our plugin your ID should appear below.', 'feed-them-social' ),
                                'class' => 'facebook-message-generator albums inst-text-facebook-albums',
							),
							array(
                                'text' => __( 'If your Access Token is set on the Facebook Options page of our plugin your ID should appear below.', 'feed-them-social' ),
                                'class' => 'facebook-message-generator video inst-text-facebook-video',
							),
							array(
                                'text' => __( 'If your Access Token is set on the Facebook Options page of our plugin your ID should appear below.', 'feed-them-social' ),
                                'class' => 'facebook-message-generator reviews inst-text-facebook-reviews',
							),
						),
						'type'        => 'text',
						'id'          => 'fb_page_id',
						'name'        => 'fb_page_id',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'id',
							'var_final_if' => 'no',
							'empty_error'  => 'yes',
						),
					),

					// Access Token
			//		array(
			//			'option_type' => 'input',
			//			'label'       => __( 'Access Token (required) ', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
			//			'type'        => 'text',
			//			'id'          => 'fb_access_token',
			//			'name'        => 'fb_access_token',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
			//			'placeholder' => __( '', 'feed-them-social' ),

						// Relative to JS.
			//			'short_attr'  => array(
			//				'attr_name'    => 'access_token',
			//				'var_final_if' => 'yes',
			//				'empty_error'  => 'set',
			//				'empty_error_value' => '',
			//			),
			//		),

					// Facebook Album ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'fb_album_photos_id',
						'label'       => __( 'Album ID ', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank to show all uploaded photos', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_album_id',
						'name'        => 'fb_album_id',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'album_id',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => 'album_id=photo_stream',
							'empty_error_if' => array(
								'attribute' => 'select#facebook-messages-selector',
								'operator' => '==',
								'value'    => 'album_photos',
							),
							'ifs' => 'album_photos',
						),
					),

					// Facebook Page Post Type Visible
					array(
						'input_wrap_class' => 'facebook-post-type-visible',
						'option_type' => 'select',
						'label'       => __( 'Post Type Visible', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb_page_posts_displayed',
						'name'        => 'fb_page_posts_displayed',
						'options'     => array(
							array(
								'label' => __( 'Display Posts made by Page only', 'feed-them-social' ),
								'value' => 'page_only',
							),
							array(
								'label' => __( 'Display Posts made by Page and Others', 'feed-them-social' ),
								'value' => 'page_and_others',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'posts_displayed',
							'ifs' => 'page',
						),
					),

					// Facebook page # of Posts
					array(
						'option_type' => 'input',
						'label'       => __( '# of Posts', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'fb_page_post_count',
						'name'        => 'fb_page_post_count',
						'value'       => '',
						'placeholder' => __( '6 is the default number', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'posts',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => 'posts=6',
						),
					),

					// Facebook Page Facebook Fixed Height
					array(
						'input_wrap_class' => 'fixed_height_option',
						'option_type' => 'input',
						'label'       => __( 'Facebook Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_page_height',
						'name'        => 'facebook_page_height',
						'value'       => '',
						'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'height',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
					),

					// Facebook Page Show Page Title (Premium)
					array(
						'input_wrap_class' => 'fb-page-title-option-hide',
						'option_type' => 'select',
						'label'       => __( 'Show Page Title', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_page_title_option',
						'name'        => 'fb_page_title_option',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'title',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'facebook-title-options-wrap',
						),
					),

					// Facebook Page Align Title (Premium)
					array(
						'input_wrap_class' => 'fb-page-title-align',
						'option_type' => 'select',
						'label'       => __( 'Align Title', 'feed-them-social' ) . '<br/><small>' . __( 'Left, Center or Right', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_page_title_align',
						'name'        => 'fb_page_title_align',
						'options'     => array(
							1 => array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
							2 => array(
								'label' => __( 'Center', 'feed-them-social' ),
								'value' => 'center',
							),
							3 => array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'title_align',
						),
					),

					// Facebook Page Show Page Description (Premium)
					array(
						'input_wrap_class' => 'fb-page-description-option-hide',
						'option_type' => 'select',
						'label'       => __( 'Show Page Description', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_page_description_option',
						'name'        => 'fb_page_description_option',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'description',
						),
						'sub_options_end' => true,
					),

					// Show Description below image or video Name
					array(
						'input_wrap_class' => 'facebook_show_media',
						'option_type' => 'select',
						'label'       => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom or Top of Post', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_show_media',
						'name'        => 'facebook_show_media',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
								'value' => 'bottom',
							),
							array(
								'label' => __( 'Above Username, Date & Description', 'feed-them-social' ),
								'value' => 'top',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_media',
						),
					),

					// Show Thumbnail
					array(
						'input_wrap_class' => 'facebook_hide_thumbnail',
						'option_type' => 'select',
						'label'       => __( 'Show User Thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_hide_thumbnail',
						'name'        => 'facebook_hide_thumbnail',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_thumbnail',
						),
					),

					// Show Username
					array(
						'input_wrap_class' => 'facebook_hide_date',
						'option_type' => 'select',
						'label'       => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_hide_date',
						'name'        => 'facebook_hide_date',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_date',
						),
					),

					// Show Date
					array(
						'input_wrap_class' => 'facebook_hide_name',
						'option_type' => 'select',
						'label'       => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_hide_name',
						'name'        => 'facebook_hide_name',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_name',
						),
					),

					// Facebook Amount of words
					array(
						'option_type' => 'input',
						'label'       => __( 'Amount of words per post', 'feed-them-social' ) . '<br/><small>' . __( 'Type 0 to remove the posts description', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_page_word_count_option',
						'name'        => 'fb_page_word_count_option',
						'placeholder' => '45 ' . __( 'is the default number', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'   => 'words',
							'empty_error' => 'set',
							'empty_error_value' => 'words=45',
						),
					),

					// Facebook Image Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Facebook Image Width', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fts-slicker-facebook-container-image-width',
						'name'        => 'fts-slicker-facebook-container-image-width',
						'placeholder' => '250px',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'image_width',
							'empty_error' => 'set',
							'empty_error_value' => 'image_width=250px',
							'ifs' => 'album_photos,albums,album_videos',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-super-facebook-options-wrap',
						),
					),

					// Facebook Image Height
					array(
						'option_type' => 'input',
						'label'       => __( 'Facebook Image Height', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fts-slicker-facebook-container-image-height',
						'name'        => 'fts-slicker-facebook-container-image-height',
						'placeholder' => '250px',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'image_height',
							'empty_error' => 'set',
							'empty_error_value' => 'image_height=250px',
							'ifs' => 'album_photos,albums,album_videos',
						),
					),

					// Facebook The space between photos
					array(
						'option_type' => 'input',
						'label'       => __( 'The space between photos', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-facebook-container-margin',
						'name'        => 'fts-slicker-facebook-container-margin',
						'placeholder' => '1px',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'space_between_photos',
							'empty_error' => 'set',
							'empty_error_value' => 'space_between_photos=1px',
							'ifs' => 'album_photos,albums,album_videos',
						),
					),

					// Hide Date, Likes and Comments
					array(
						'option_type' => 'select',
						'label'       => __( 'Hide Date, Likes and Comments', 'feed-them-social' ),
						'label_note'  => __( 'Good for image sizes under 120px', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-facebook-container-hide-date-likes-comments',
						'name'        => 'fts-slicker-facebook-container-hide-date-likes-comments',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'hide_date_likes_comments',
							'ifs' => 'album_photos,albums,album_videos',
						),
					),

					// Center Facebook Container
					array(
						'option_type' => 'select',
						'label'       => __( 'Center Facebook Container', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-facebook-container-position',
						'name'        => 'fts-slicker-facebook-container-position',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'center_container',
							'ifs' => 'album_photos,albums,album_videos',
						),
						'sub_options_end' => true,
					),

					// Image Stacking Animation NOT USING THIS ANYMORE
					array(
						'option_type' => 'input',
						'label'       => __( 'Image Stacking Animation On', 'feed-them-social' ),
						'label_note'  => __( 'This happens when resizing browser', 'feed-them-social' ),
						'type'        => 'hidden',

						// used to trick is Visible in JS
						'class'       => 'non-visible',
						'id'          => 'fts-slicker-facebook-container-animation',
						'name'        => 'fts-slicker-facebook-container-animation',
						'value'       => 'no',
						'short_attr'  => array(
							'attr_name' => 'image_stack_animation',
							'empty_error' => 'set',
							'empty_error_value' => 'image_stack_animation=no',
							'ifs' => 'grid',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'facebook-image-animation-option-wrap',
						),
						'sub_options_end' => true,
					),

					// Align Images non-grid
					array(
						'input_wrap_id' => 'facebook_align_images_wrapper',
						'option_type' => 'select',
						'label'       => __( 'Align Images', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_align_images',
						'name'        => 'facebook_align_images',
						'options'     => array(
							1 => array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
							2 => array(
								'label' => __( 'Center', 'feed-them-social' ),
								'value' => 'center',
							),
							3 => array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'images_align',
							'ifs' => 'page',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'align-images-wrap',
						),
						'sub_options_end' => true,
					),

					// ******************************************
					// Facebook Review Options
					// ******************************************
					// Reviews to Show
					array(
						'grouped_options_title' => __( 'Reviews', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Reviews to Show', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'reviews_type_to_show',
						'name'        => 'reviews_type_to_show',
						'options'     => array(
							1 => array(
								'label' => __( 'Show all Reviews', 'feed-them-social' ),
								'value' => '1',
							),
							2 => array(
								'label' => __( '5 Star Reviews only', 'feed-them-social' ),
								'value' => '5',
							),
							3 => array(
								'label' => __( '4 and 5 Stars Reviews only', 'feed-them-social' ),
								'value' => '4',
							),
							4 => array(
								'label' => __( '3, 4 and 5 Star Reviews only', 'feed-them-social' ),
								'value' => '3',
							),
							5 => array(
								'label' => __( '2, 3, 4, and 5 Star Reviews only', 'feed-them-social' ),
								'value' => '2',
							),
						),
						'req_plugin'  => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'reviews_type_to_show',
							'ifs' => 'reviews',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'facebook-reviews-wrap',
						),
					),

					// Rating Format
					array(
						'option_type' => 'select',
						'label'       => __( 'Rating Format', 'feed-them-social' ) . '<br/><small>' . __( '8/17/2018: Facebook has moved to what are called "recommendations" so for some people this option may not be necessary.', 'feed-them-premium' ) . '</small>',
						'type'        => 'text',
						'id'          => 'reviews_rating_format',
						'name'        => 'reviews_rating_format',
						'options'     => array(
							1 => array(
								'label' => __( '5 star - &#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social' ),
								'value' => '1',
							),
							2 => array(
								'label' => __( '5 star &#9733;', 'feed-them-social' ),
								'value' => '2',
							),
							3 => array(
								'label' => __( '5 star', 'feed-them-social' ),
								'value' => '3',
							),
							4 => array(
								'label' => __( '5 &#9733;', 'feed-them-social' ),
								'value' => '4',
							),
							5 => array(
								'label' => __( '&#9733;&#9733;&#9733;&#9733;&#9733;', 'feed-them-social' ),
								'value' => '5',
							),
						),
						'req_plugin'  => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'reviews_rating_format',
							'ifs' => 'reviews',
						),
					),

					// Overall Rating
					array(
						'option_type' => 'select',
						'label'       => __( 'Overall Rating above Feed', 'feed-them-social' ) . '<br/><small>' . __( 'More settings: <a href="admin.php?page=fts-facebook-feed-styles-submenu-page#overall-rating-options">Facebook Options</a> page.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'reviews_overall_rating_show',
						'name'        => 'reviews_overall_rating_show',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'req_plugin'  => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'overall_rating',
							'ifs' => 'reviews',
						),
					),

					// Hide Reviews with no Text
					array(
						'option_type' => 'select',
						'label'       => __( 'Hide Reviews with no description', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'reviews_with_no_text',
						'name'        => 'reviews_with_no_text',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'remove_reviews_no_description',
							'ifs' => 'reviews',
						),
					),

					// Hide Reviews the text link, "See More Reviews"
					array(
						'option_type' => 'select',
						'label'       => __( 'Hide the text "See More Reviews"', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'hide_see_more_reviews_link',
						'name'        => 'hide_see_more_reviews_link',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'hide_see_more_reviews_link',
							'ifs' => 'reviews',
						),
						'sub_options_end' => true,
					),

					// ******************************************
					// Like Box Options
					// ******************************************
					// Facebook Hide Like Box or Button (Premium)
					array(
						'grouped_options_title' => __( 'Like Box', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Hide Like Box or Button', 'feed-them-social' ) . '<br/><small>' . __( 'Turn on from <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fb_hide_like_box_button',
						'name'        => 'fb_hide_like_box_button',
						'options'     => array(
							1 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'hide_like_option',
							'ifs' => 'not_group',
							'empty_error' => 'set',
							'set_operator' => '==',
							'set_equals' => 'no',
							'empty_error_value' => '',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-like-box-wrap',
						),
					),

					// Position of Like Box or Button (Premium)
					array(
						'option_type' => 'select',
						'label'       => __( 'Position of Like Box or Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb_position_likebox',
						'name'        => 'fb_position_likebox',
						'options'     => array(
							1 => array(
								'label' => __( 'Above Title', 'feed-them-social' ),
								'value' => 'above_title',
							),
							2 => array(
								'label' => __( 'Below Title', 'feed-them-social' ),
								'value' => 'below_title',
							),
							3 => array(
								'label' => __( 'Bottom of Feed', 'feed-them-social' ),
								'value' => 'bottom',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'show_follow_btn_where',
							'ifs'     => 'not_group',
							'and_ifs' => 'like_box',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'like-box-wrap',
						),
					),

					// Facebook Page Align Like Box or Button (Premium)
					array(
						'option_type' => 'select',
						'label'       => __( 'Align Like Box or Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb_align_likebox',
						'name'        => 'fb_align_likebox',
						'options'     => array(
							1 => array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
							2 => array(
								'label' => __( 'Center', 'feed-them-social' ),
								'value' => 'center',
							),
							3 => array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'like_option_align',
							'ifs'     => 'not_group',
							'and_ifs' => 'like_box',
						),
					),

					// Facebook Page Width of Like Box
					array(
						'option_type' => 'input',
						'label'       => __( 'Width of Like Box', 'feed-them-social' ) . '<br/><small>' . __( 'This only works for the Like Box', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'like_box_width',
						'name'        => 'like_box_width',
						'placeholder' => __( '500px max', 'feed-them-social' ),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'like_box_width',
							'empty_error' => 'set',
							'empty_error_value' => 'like_box_width=500px',
							'ifs'     => 'not_group',
							'and_ifs' => 'like_box',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Popup
					// ******************************************
					// Facebook Page Display Photos in Popup
					array(
						'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Display Photos in Popup', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_popup',
						'name'        => 'facebook_popup',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => 'popup',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'facebook-popup-wrap',
						),
						'sub_options_end' => true,
					),

					// Facebook Comments in Popup
					array(
						'option_type' => 'select',
						'label'       => __( 'Hide Comments in Popup', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_popup_comments',
						'name'        => 'facebook_popup_comments',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'short_attr'  => array(
							'attr_name' => 'hide_comments_popup',
							'ifs' => 'popup',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'display-comments-wrap',
						),
						'sub_options_end' => true,
					),

					// ******************************************
					// Facebook Load More Options
					// ******************************************
					// Facebook Page Load More Button
					array(
						'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Load More Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb_load_more_option',
						'name'        => 'fb_load_more_option',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
							'ifs' => 'not_events',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'facebook-loadmore-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
					),

					// Facebook Page Load More Style
					array(
						'option_type' => 'select',
						'label'       => __( 'Load More Style', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb_load_more_style',
						'name'        => 'fb_load_more_style',
						'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
						'options'     => array(
							1 => array(
								'label' => __( 'Button', 'feed-them-social' ),
								'value' => 'button',
							),
							2 => array(
								'label' => __( 'AutoScroll', 'feed-them-social' ),
								'value' => 'autoscroll',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name' => 'loadmore',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
						'sub_options_end' => true,
					),

					// Facebook Page Load more Button Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'loadmore_button_width',
						'name'        => 'loadmore_button_width',
						'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_maxwidth',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_maxwidth=300px',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
					),

					// Facebook Page Load more Button Margin
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'loadmore_button_margin',
						'name'        => 'loadmore_button_margin',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_margin',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_margin=10px',
							'ifs' => 'load_more',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Facebook Grid Options
					// ******************************************
					// Facebook Page Display Posts in Grid
					array(
						'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
						'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
						'option_type' => 'select',
						'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fb-grid-option',
						'name'        => 'fb-grid-option',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',
						'short_attr'  => array(
							'attr_name'    => 'grid',
							'empty_error'  => 'set',
							'set_operator' => '==',
							'set_equals'   => 'yes',
							'empty_error_value' => '',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-grid-options-wrap',
						),
					),

					// Grid Column Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Column Width', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_grid_column_width',
						'name'        => 'facebook_grid_column_width',
						'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
						'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'colmn_width',
							'empty_error' => 'set',
							'empty_error_value' => 'colmn_width=310px',
							'ifs' => 'grid',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
					),

					// Grid Spaces Between Posts
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_grid_space_between_posts',
						'name'        => 'facebook_grid_space_between_posts',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'space_between_posts',
							'empty_error' => 'set',
							'empty_error_value' => 'space_between_posts=10px',
							'ifs' => 'grid',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Facebook Video Options
					// ******************************************
					// Video Play Button
					array(
						'grouped_options_title' => __( 'Video Button Options', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Video Play Button', 'feed-them-social' ) . '<br/><small>' . __( 'Displays over Video Thumbnail', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_show_video_button',
						'name'        => 'facebook_show_video_button',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'play_btn',
							'empty_error' => 'set',
							'set_operator' => '==',
							'set_equals' => 'yes',
							'ifs' => 'album_videos',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fb-video-play-btn-options-wrap',
						),
					),

					// Size of the Play Button
					array(
						'option_type' => 'input',
						'label'       => __( 'Size of the Play Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'facebook_size_video_play_btn',
						'name'        => 'facebook_size_video_play_btn',
						'placeholder' => '40px ' . __( 'for example', 'feed-them-social' ),
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'play_btn_size',
							'empty_error' => 'set',
							'empty_error_value' => 'play_btn_size=40px',
							'ifs'     => 'album_videos',
							'and_ifs' => 'video',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fb-video-play-btn-options-content',
						),
					),

					// Show Play Button in Front
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Play Button in Front', 'feed-them-social' ) . '<br/><small>' . __( 'Displays before hovering over thumbnail', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'facebook_show_video_button_in_front',
						'name'        => 'facebook_show_video_button_in_front',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'play_btn_visible',
							'ifs'     => 'album_videos',
							'and_ifs' => 'video',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Facebook Carousel
					// ******************************************
					// Carousel/Slideshow
					array(
						'grouped_options_title' => __( 'Carousel/Slider', 'feed-them-social' ),
						'input_wrap_id' => 'facebook_slider',
						'instructional-text' => __( 'Create a Carousel or Slideshow with these options.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . __( 'View Demos', 'feed-them-social' ) . '</a> ' . __( 'and copy easy to use shortcode examples.', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Carousel/Slideshow', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slider',
						'name'        => 'fts-slider',
						'options'     => array(
							1 => array(
								'label' => __( 'Off', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'On', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_carousel',
						'short_attr'  => array(
							'attr_name' => 'slider',
							'empty_error' => 'set',
							'set_operator' => '==',
							'set_equals' => 'yes',
							'ifs' => 'album_photos,album_videos',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'slideshow-wrap',
						),
					),

					// Carousel/Slideshow Type
					array(
						'input_wrap_id' => 'facebook_scrollhorz_or_carousel',
						'option_type' => 'select',
						'label'       => __( 'Type', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'scrollhorz_or_carousel',
						'name'        => 'scrollhorz_or_carousel',
						'options'     => array(
							1 => array(
								'label' => __( 'Slideshow', 'feed-them-social' ),
								'value' => 'scrollhorz',
							),
							2 => array(
								'label' => __( 'Carousel', 'feed-them-social' ),
								'value' => 'carousel',
							),
						),
						'req_plugin'  => 'fts_carousel',
						'short_attr'  => array(
							'attr_name' => 'scrollhorz_or_carousel',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'slider_options_wrap',
						),
					),

					// Carousel Slides Visible
					array(
						'input_wrap_id' => 'facebook_slides_visible',
						'option_type' => 'input',
						'label'       => __( 'Carousel Slides Visible', 'feed-them-social' ) . '<br/><small>' . __( 'Not for Slideshow. Example: 1-500', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slides_visible',
						'name'        => 'slides_visible',
						'placeholder' => __( '3 is the default number', 'feed-them-social' ),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slides_visible',
							'empty_error' => 'set',
							'empty_error_value' => 'slides_visible=3',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'slider_carousel_wrap',
						),
					),

					// Carousel Spacing in between Slides
					array(
						'input_wrap_id' => 'facebook_slider_spacing',
						'option_type' => 'input',
						'label'       => __( 'Spacing in between Slides', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_spacing',
						'name'        => 'slider_spacing',
						'value'       => '',
						'placeholder' => __( '2px', 'feed-them-social' ),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_spacing',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_spacing=2px',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
						'sub_options_end' => true,
					),

					// Carousel/Slideshow Margin
					array(
						'input_wrap_id' => 'facebook_slider_margin',
						'option_type' => 'input',
						'label'       => __( 'Carousel/Slideshow Margin', 'feed-them-social' ) . '<br/><small>' . __( 'Center feed. Add space above/below.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_margin',
						'name'        => 'slider_margin',
						'value'       => '',
						'placeholder' => __( '-6px auto 1px auto', 'feed-them-social' ),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_margin',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_margin="-6px auto 1px auto"',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow Slider Speed
					array(
						'input_wrap_id' => 'facebook_slider_speed',
						'option_type' => 'input',
						'label'       => __( 'Slider Speed', 'feed-them-social' ) . '<br/><small>' . __( 'How fast the slider changes', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_speed',
						'name'        => 'slider_speed',
						'value'       => '',
						'placeholder' => __( '0-10000', 'feed-them-social' ),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_speed',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_speed=1000',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow Slider Timeout
					array(
						'input_wrap_id' => 'facebook_slider_timeout',
						'option_type' => 'input',
						'label'       => __( 'Slider Timeout', 'feed-them-social' ) . '<br/><small>' . __( 'Amount of Time before the next slide.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_timeout',
						'name'        => 'slider_timeout',
						'value'       => '',
						'placeholder' => __( '0-10000', 'feed-them-social' ),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_timeout',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_timeout=1000',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow
					array(
						'input_wrap_id' => 'facebook_slider_controls',
						'option_type' => 'select',
						'label'       => __( 'Slider Controls', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_controls',
						'name'        => 'slider_controls',
						'options'     => array(
							1 => array(
								'label' => __( 'Dots above Feed', 'feed-them-social' ),
								'value' => 'dots_above_feed',
							),
							2 => array(
								'label' => __( 'Dots and Arrows above Feed', 'feed-them-social' ),
								'value' => 'dots_and_arrows_above_feed',
							),
							3 => array(
								'label' => __( 'Dots and Numbers above Feed', 'feed-them-social' ),
								'value' => 'dots_and_numbers_above_feed',
							),
							4 => array(
								'label' => __( 'Dots, Arrows and Numbers above Feed', 'feed-them-social' ),
								'value' => 'dots_arrows_and_numbers_above_feed',
							),
							5 => array(
								'label' => __( 'Arrows and Numbers above feed', 'feed-them-social' ),
								'value' => 'arrows_and_numbers_above_feed',
							),
							6 => array(
								'label' => __( 'Arrows above Feed', 'feed-them-social' ),
								'value' => 'arrows_above_feed',
							),
							7 => array(
								'label' => __( 'Numbers above Feed', 'feed-them-social' ),
								'value' => 'numbers_above_feed',
							),
							8 => array(
								'label' => __( 'Dots below Feed', 'feed-them-social' ),
								'value' => 'dots_below_feed',
							),
							array(
								'label' => __( 'Dots and Arrows below Feed', 'feed-them-social' ),
								'value' => 'dots_and_arrows_below_feed',
							),
							array(
								'label' => __( 'Dots and Numbers below Feed', 'feed-them-social' ),
								'value' => 'dots_and_numbers_below_feed',
							),
							array(
								'label' => __( 'Dots, Arrows and Numbers below Feed', 'feed-them-social' ),
								'value' => 'dots_arrows_and_numbers_below_feed',
							),
							array(
								'label' => __( 'Arrows below Feed', 'feed-them-social' ),
								'value' => 'arrows_below_feed',
							),
							array(
								'label' => __( 'Numbers Below Feed', 'feed-them-social' ),
								'value' => 'numbers_below_feed',
							),
						),
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_controls',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow Slider Controls Text Color
					array(
						'input_wrap_id' => 'facebook_slider_controls_text_color',
						'option_type' => 'input',
						'label'       => __( 'Slider Controls Text Color', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_controls_text_color',
						'name'        => 'slider_controls_text_color',
						'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
						'value'       => '',
						'placeholder' => '#FFF',
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_controls_text_color',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_controls_text_color=#FFF',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow Slider Controls Bar Color
					array(
						'input_wrap_id' => 'facebook_slider_controls_bar_color',
						'option_type' => 'input',
						'label'       => __( 'Slider Controls Bar Color', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_controls_bar_color',
						'name'        => 'slider_controls_bar_color',
						'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
						'value'       => '',
						'placeholder' => '#000',
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_controls_bar_color',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_controls_bar_color=320px',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
					),

					// Carousel/Slideshow Slider Controls Bar Color
					array(
						'input_wrap_id' => 'facebook_slider_controls_width',
						'option_type' => 'input',
						'label'       => __( 'Slider Controls Max Width', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'slider_controls_width',
						'name'        => 'slider_controls_width',
						'class'       => 'fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
						'value'       => '',
						'placeholder' => '320px',
						'req_plugin'  => 'fts_carousel',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'slider_controls_width',
							'empty_error' => 'set',
							'empty_error_value' => 'slider_controls_width=320px',
							'ifs'     => 'album_photos,album_videos',
							'and_ifs' => 'carousel',
						),
						'sub_options_end' => 2,
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'page'         => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'page',
						),
					),
					'events'       => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'events',
						),
					),
					'not_events'   => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '!==',
							'value'    => 'events',
						),
					),
					'event'        => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'event',
						),
					),
					'group'        => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'group',
						),
					),
					'not_group'    => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '!==',
							'value'    => 'group',
						),
					),
					'album_photos' => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'album_photos',
						),
					),
					'albums'       => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'albums',
						),
					),
					'album_videos' => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'album_videos',
						),
					),
					'reviews'      => array(
						'if' => array(
							'class'    => 'select#facebook-messages-selector',
							'operator' => '==',
							'value'    => 'reviews',
						),
					),
					'like_box'     => array(
						'if' => array(
							'class'    => 'select#fb_hide_like_box_button',
							'operator' => '==',
							'value'    => 'no',
						),
					),
					'popup'        => array(
						'if' => array(
							'class'    => 'select#facebook_popup',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'load_more'    => array(
						'if' => array(
							'class'    => 'select#fb_load_more_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'video'        => array(
						'if' => array(
							'class'    => 'select#facebook_show_video_button',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'grid'         => array(
						'if' => array(
							'class'    => 'select#fb-grid-option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'carousel'     => array(
						'if' => array(
							'class'    => 'select#fts-slider',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'Facebook Page Feed Shortcode', 'feed-them-social' ),
				'generator_class'    => 'facebook-page-final-shortcode',
			), // End Facebook Page Feed

			// ******************************************
			// Youtube Feed
			// ******************************************
			'youtube'         => array(
				'section_attr_key'   => 'youtube_',
				'section_title'      => __( 'Youtube Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'fts-youtube-shortcode-form',

				// Form Info
				'form_wrap_classes'  => 'youtube-shortcode-form',
				'form_wrap_id'       => 'fts-youtube-form',

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feed Type', 'feed-them-social' ) . '<br/><small><a href="https://feedthemsocial.com/youtube-demo-1-large-with-4-video-per-row/" target="_blank">' . __( 'See Example Demos', 'feed-them-social' ) . '</a></small>',
					'select_wrap_classes' => 'youtube-gen-selection',
					'select_classes' => '',
					'select_name'    => 'youtube-messages-selector',
					'select_id'      => 'youtube-messages-selector',
				),

				// Token Check
				'token_check'        => array(
					array(
						'option_name'  => 'youtube_custom_api_token',
						'no_token_msg' => __( '<strong>STEP 1:</strong> Please add your API Token or Access Token to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Youtube Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
					),
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// Channel Feed
					array(
						'value' => 'channelID',
						'title' => __( 'Channel Feed', 'feed-them-social' ),
					),

					// Channel Playlist Feed
					array(
						'value' => 'playlistID',
						'title' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ),
					),

					// User's Most Recent Videos
					array(
						'value' => 'username',
						'title' => __( 'User\'s Most Recent Videos', 'feed-them-social' ),
					),

					// User's Playlist
					array(
						'value' => 'userPlaylist',
						'title' => __( 'User\'s Specific Playlist', 'feed-them-social' ),
					),

					// Single Video with description
					array(
						'value' => 'singleID',
						'title' => __( 'Single Video with title, date & description', 'feed-them-social' ),
					),
				),
				'short_attr_final'   => 'yes',

				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				// 'empty_error'=> 'set',
				// 'empty_error_value'=> 'auto',
				'main_options'       => array(

					// Youtube Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_name',
						'label'       => __( 'Youtube Username (required)', 'feed-them-social' ),
						'instructional-text' => __( 'You must copy your YouTube <strong>Username</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/channel/" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
						'type'        => 'text',
						'id'          => 'youtube_name',
						'name'        => 'youtube_name',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'username',
							'empty_error' => 'yes',
							'ifs' => 'username',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'username',
							),
						),
					),

					// Youtube Playlist ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_playlistID',
						'label'       => __( 'Youtube Playlist ID (required)', 'feed-them-social' ),
						'instructional-text' => __( 'You must copy your YouTube <strong>Playlist</strong> and <strong>Channel</strong> url link and paste them below. Your urls should look similar to our Example urls below. <br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN</a><br/><strong>' . __( 'Channel ID:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA" target="_blank">https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA</a>',
						'type'        => 'text',
						'id'          => 'youtube_playlistID',
						'name'        => 'youtube_playlistID',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'playlist_id',
							'empty_error' => 'yes',
							'ifs' => 'playlistID',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'playlistID',
							),
						),
					),

					// Youtube Playlist ID2
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_playlistID2',
						'label'       => __( 'Youtube Playlist ID (required)', 'feed-them-social' ),
						'instructional-text' => __( 'You must copy your YouTube <strong>Playlist</strong> and <strong>Username</strong> url and paste them below. Your urls should look similar to our Example urls below.<br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym" target="_blank">https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym</a><br/><strong>' . __( 'Username:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/user/nationalgeographic" target="_blank">https://www.youtube.com/user/nationalgeographic</a>',
						'type'        => 'text',
						'id'          => 'youtube_playlistID2',
						'name'        => 'youtube_playlistID2',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'playlist_id',
							'empty_error' => 'yes',
							'ifs' => 'userPlaylist',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'userPlaylist',
							),
						),
					),

					// Youtube Name 2
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_name2',
						'label'       => __( 'Youtube Username<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_name2',
						'name'        => 'youtube_name2',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'username_subscribe_btn',
							'ifs' => 'userPlaylist',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'userPlaylist',
							),
						),
					),

					// Youtube Channel ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_channelID',
						'label'       => __( 'Youtube Channel ID (required)', 'feed-them-social' ),
						'instructional-text' => __( 'You must copy your YouTube <strong>Channel</strong> url and paste it below. Your url should look similar to our Example url.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw" target="_blank">https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw</a>',
						'type'        => 'text',
						'id'          => 'youtube_channelID',
						'name'        => 'youtube_channelID',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'channel_id',
							'ifs' => 'channelID',
							'empty_error' => 'yes',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'channelID',
							),
						),
					),

					// Youtube Channel ID 2
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_channelID2',
						'label'       => __( 'Youtube Channel ID<br/><small>Required if showing <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">Subscribe button</a></small>', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_channelID2',
						'name'        => 'youtube_channelID2',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'channel_id',
							'ifs' => 'playlistID',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'playlistID',
							),
						),
					),

					// Youtube Single Video ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_singleVideoID',
						'label'       => __( 'Single Youtube Video ID (required)', 'feed-them-social' ),
						'instructional-text' => __( 'You must copy your <strong>YouTube Video</strong> url link and paste it below. Your url should look similar to our Example url below. <br/><strong>Video URL:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0</a>',
						'type'        => 'text',
						'id'          => 'youtube_singleVideoID',
						'name'        => 'youtube_singleVideoID',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'video_id_or_link',
							'ifs' => 'singleID',
							'empty_error' => 'yes',
							'empty_error_if' => array(
								'attribute' => 'select#youtube-messages-selector',
								'operator' => '==',
								'value'    => 'singleID',
							),
						),
					),

					// # of videos
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'youtube_vid_count',
						'label'       => __( '# of videos', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'youtube_vid_count',
						'name'        => 'youtube_vid_count',
						'placeholder' => __( '4 is the default value', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'   => 'vid_count',
							'empty_error' => 'set',
							'empty_error_value' => 'vid_count=4',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-youtube-first-video-wrap',
						),
					),

					// Display First video full size
					array(
						'grouped_options_title' => __( 'First Video Display', 'feed-them-social' ),
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'Display First video full size', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_first_video',
						'name'        => 'youtube_first_video',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'large_vid',
						),
						'sub_options_end' => true,
					),

					// Display Large Video Title
					array(
						'option_type' => 'select',
						'input_wrap_class' => 'youtube_hide_option',
						'label'       => __( 'Show the Large Video Title', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_large_vid_title',
						'name'        => 'youtube_large_vid_title',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'large_vid_title',
						),
					),

					// Display Large Video Description
					array(
						'option_type' => 'select',
						'input_wrap_class' => 'youtube_hide_option',
						'label'       => __( 'Show the Large Video Description', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_large_vid_description',
						'name'        => 'youtube_large_vid_description',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'large_vid_description',
						),
					),

					// Play thumbs in large video container
					array(
						'grouped_options_title' => __( 'Video Thumbnails', 'feed-them-social' ),
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'Click thumb to play Video', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_play_thumbs',
						'name'        => 'youtube_play_thumbs',
						'options'     => array(
							array(
								'label' => __( 'Play on Page', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'Open in YouTube', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Open in Popup (Premium Version Required)', 'feed-them-social' ),
								'value' => 'popup',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'thumbs_play_in_iframe',
						),
					),

					// # of videos in each row
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( '# of videos in each row', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_columns',
						'name'        => 'youtube_columns',
						'default_value' => '4',
						'options'     => array(
							array(
								'label' => __( '1', 'feed-them-social' ),
								'value' => '1',
							),
							array(
								'label' => __( '2', 'feed-them-social' ),
								'value' => '2',
							),
							array(
								'label' => __( '3', 'feed-them-social' ),
								'value' => '3',
							),
							array(
								'label' => __( '4', 'feed-them-social' ),
								'value' => '4',
							),
							array(
								'label' => __( '5', 'feed-them-social' ),
								'value' => '5',
							),
							array(
								'label' => __( '6', 'feed-them-social' ),
								'value' => '6',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'vids_in_row',
						),
					),

					// omit first video thumbnail
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'Hide the first thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Useful if playing videos on the page.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_omit_first_thumbnail',
						'name'        => 'youtube_omit_first_thumbnail',
						'default_value' => 'no',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'omit_first_thumbnail',
						),
					),

					// Space between Vids
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'input',
						'label'       => __( 'Space between video thumbnails', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-youtube-container-margin',
						'name'        => 'fts-slicker-youtube-container-margin',
						'placeholder' => '1px is the default value',
						'value'       => '',
						'short_attr'  => array(
							'attr_name'    => 'space_between_videos',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => 'space_between_videos=1px',
						),
					),

					// Force Video Rows
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'Force thumbnails rows', 'feed-them-social' ) . '<br/><small>' . __( 'No, will allow the video images to be responsive for smaller devices. Yes, will force the selected rows.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fts_youtube_force_columns',
						'name'        => 'fts_youtube_force_columns',
						'default_value' => 'no',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'force_columns',
						),
					),

					// Display Max Res Images for thumbs
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'High quality thumbnail images', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_maxres_thumbnail_images',
						'name'        => 'youtube_maxres_thumbnail_images',
						'options'     => array(
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'maxres_thumbnail_images',
						),
					),

					// Background color for thumbs container
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type'  => 'input',
						'color_picker' => 'yes',
						'label'        => __( 'Container Background color ', 'feed-them-social' ),
						'type'         => 'text',
						'id'           => 'youtube_thumbs_wrap_color',
						'name'         => 'youtube_thumbs_wrap_color',
						'default'      => '#000',
						'placeholder'  => '#000',

						// Relative to JS.
						'short_attr'   => array(
							'attr_name'   => 'thumbs_wrap_color',
							'empty_error' => 'set',
							'empty_error_value' => 'thumbs_wrap_color=#000',
						),
					),

					// Align container right or left of video
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'option_type' => 'select',
						'label'       => __( 'Align Thumbs', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Videoo', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_thumbs_wrap',
						'name'        => 'youtube_thumbs_wrap',
						'options'     => array(
							array(
								'label' => __( 'Below Video', 'feed-them-social' ),
								'value' => 'none',
							),
							array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
							array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'wrap',
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
					),

					// Align container right or left of video
					array(
						'input_wrap_class' => 'youtube_align_comments_wrap',
						'option_type' => 'select',
						'label'       => __( 'Align Title, Description etc.', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Video', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_comments_wrap',
						'name'        => 'youtube_comments_wrap',
						'options'     => array(
							array(
								'label' => __( 'Below Video', 'feed-them-social' ),
								'value' => 'none',
							),
							array(
								'label' => __( 'Right', 'feed-them-social' ),
								'value' => 'right',
							),
							array(
								'label' => __( 'Left', 'feed-them-social' ),
								'value' => 'left',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'wrap_single',
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
					),

					// Align container right or left of video
					array(
						'input_wrap_class' => 'youtube_video_thumbs_display',
						'option_type' => 'select',
						'label'       => __( 'Video/Thumbs width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_video_thumbs_display',
						'name'        => 'youtube_video_thumbs_display',
						'options'     => array(
							array(
								'label' => __( 'None', 'feed-them-social' ),
								'value' => 'none',
							),
							array(
								'label' => __( 'Option 1 (Video 80%, Thumbs Container 20%)', 'feed-them-social' ),
								'value' => '1',
							),
							array(
								'label' => __( 'Option 1 (Video 60%, Thumbs Container 40%)', 'feed-them-social' ),
								'value' => '2',
							),
							array(
								'label' => __( 'Option 1 (Video 50%, Thumbs Container 50%)', 'feed-them-social' ),
								'value' => '3',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'video_wrap_display',
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
					),

					// Align container right or left of video
					array(
						'input_wrap_class' => 'youtube_video_single_info_display',
						'option_type' => 'select',
						'label'       => __( 'Video/Info width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_video_comments_display',
						'name'        => 'youtube_video_comments_display',
						'options'     => array(
							array(
								'label' => __( 'None', 'feed-them-social' ),
								'value' => 'none',
							),
							array(
								'label' => __( 'Option 1 (Video 80%, Info Container 20%)', 'feed-them-social' ),
								'value' => '1',
							),
							array(
								'label' => __( 'Option 1 (Video 60%, Info Container 40%)', 'feed-them-social' ),
								'value' => '2',
							),
							array(
								'label' => __( 'Option 1 (Video 50%, Info Container 50%)', 'feed-them-social' ),
								'value' => '3',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'video_wrap_display_single',
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
					),

					// Youtube Load More Button
					array(
						'input_wrap_class' => 'youtube_hide_option',
						'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Load More Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_load_more_option',
						'name'        => 'youtube_load_more_option',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'youtube-loadmore-wrap',
						),
					),

					// Youtube Load More Style
					array(
						'option_type' => 'select',
						'label'       => __( 'Load More Style', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_load_more_style',
						'name'        => 'youtube_load_more_style',
						'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
						'options'     => array(
							array(
								'label' => __( 'Button', 'feed-them-social' ),
								'value' => 'button',
							),
							array(
								'label' => __( 'AutoScroll', 'feed-them-social' ),
								'value' => 'autoscroll',
							),
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'loadmore',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-youtube-load-more-options-wrap',
						),
						'sub_options_end' => true,
					),

					// youtube Page Load more Amount
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more videos will load at a time.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_loadmore_count',
						'name'        => 'youtube_loadmore_count',
						'placeholder' => __( '5 is the default number', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_count',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_count=5',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-youtube-load-more-options2-wrap',
						),
					),

					// youtube Load more Button Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_loadmore_button_width',
						'name'        => 'youtube_loadmore_button_width',
						'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_maxwidth',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_maxwidth=300px',
							'ifs' => 'load_more',
						),
					),

					// youtube Load more Button Margin
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'youtube_loadmore_button_margin',
						'name'        => 'youtube_loadmore_button_margin',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_margin',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_margin=10px',
							'ifs' => 'load_more',
						),
						'sub_options_end' => 2,
					),

					// Display Comments
					array(
						'grouped_options_title' => __( 'Comments', 'feed-them-social' ),
						'option_type' => 'input',
						'label'       => __( '# of Comments', 'feed-them-social' ) . '<br/><small>' . __( 'Maximum amount is 50', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'youtube_comments_count',
						'name'        => 'youtube_comments_count',
						'placeholder' => '',
						'value'       => '',
						'short_attr'  => array(
							'attr_name'   => 'comments_count',
							'empty_error' => 'set',
							'empty_error_value' => 'comments_count=0',
						),
						'req_plugin'  => 'fts_premium',
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'load_more'    => array(
						'if' => array(
							'class'    => 'select#youtube_load_more_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'username'     => array(
						'if' => array(
							'class'    => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'username',
						),
					),
					'userPlaylist' => array(
						'if' => array(
							'class'    => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'userPlaylist',
						),
					),
					'channelID'    => array(
						'if' => array(
							'class'    => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'channelID',
						),
					),
					'playlistID'   => array(
						'if' => array(
							'class'    => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'playlistID',
						),
					),
					'singleID'     => array(
						'if' => array(
							'class'    => 'select#youtube-messages-selector',
							'operator' => '==',
							'value'    => 'singleID',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'YouTube Feed Shortcode', 'feed-them-social' ),
				'generator_class'    => 'youtube-final-shortcode',
			), // End Youtube Feed

			// ******************************************
			// Pinterest
			// ******************************************
			'pinterest'       => array(
				'section_attr_key'   => 'pinterest_',
				'section_title'      => __( 'Pinterest Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'pinterest-shortcode-form',

				// Form Info
				'form_wrap_classes'  => 'pinterest-shortcode-form',
				'form_wrap_id'       => 'fts-pinterest-form',

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feed Type', 'feed-them-social' ),
					'select_wrap_classes' => 'pinterest-gen-selection',
					'select_classes' => '',
					'select_name'    => 'pinterest-messages-selector',
					'select_id'      => 'pinterest-messages-selector',
				),

				// Token Check
				'token_check'        => array(
					array(
						'option_name'  => 'fts_pinterest_custom_api_token',
						'no_token_msg' => __( '<strong>STEP 1:</strong> Please add a Pinterest API Token to our <a href="admin.php?page=fts-pinterest-feed-styles-submenu-page">Pinterest Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
					),
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// Board List
					array(
						'value' => 'boards_list',
						'title' => __( 'Board List', 'feed-them-social' ),
					),

					// Single Board Pins
					array(
						'value' => 'single_board_pins',
						'title' => __( 'Pins From a Specific Board', 'feed-them-social' ),
					),

					// Single Board Pins
					array(
						'value' => 'pins_from_user',
						'title' => __( 'Latest Pins from a User', 'feed-them-social' ),
					),
				),
				'short_attr_final'   => 'yes',

				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				// 'empty_error'=> 'set',
				// 'empty_error_value'=> 'auto',
				'main_options'       => array(

					// Feed Type
					array(
						'option_type' => 'select',
						'id'          => 'pinterest-messages-selector',
						'name'        => 'pinterest-messages-selector',

						// DONT SHOW HTML
						'no_html'     => 'yes',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'type',
						),
					),

					// Pinterest Board Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'board-name',
						'label'       => __( 'Pinterest Board Name (required)', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'pinterest_board_name',
						'name'        => 'pinterest_board_name',
						'value'       => '',
						'instructional-text' => __( 'Copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __( 'Pinterest and Board Name', 'feed-them-social' ) . '</a> ' . __( 'and paste them below.', 'feed-them-social' ),
						'instructional-class' => 'pinterest-board-and-name-text',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'board_id',
							'var_final_if' => 'no',
							'empty_error' => 'yes',
							'empty_error_if' => array(
								'attribute' => 'select#pinterest-messages-selector',
								'operator' => '==',
								'value'    => 'single_board_pins',
							),
							'ifs' => 'single_board_pins',
						),
					),

					// Pinterest Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'pinterest_name',
						'label'       => __( 'Pinterest Username (required)', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'pinterest_name',
						'name'        => 'pinterest_name',
						'value'       => '',
						'instructional-text' => __( 'Copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __( 'Pinterest Name', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ),
						'instructional-class' => 'pinterest-name-text',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'pinterest_name',
							'empty_error'  => 'yes',
							'var_final_if' => 'no',
						),
					),

					// Board Count
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'number-of-boards',
						'label'       => __( '# of Boards', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'boards_count',
						'name'        => 'boards_count',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
						'placeholder' => __( '6 is the default value', 'feed-them-social' ),
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'boards_count',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => 'boards_count=6',
							'ifs' => 'boards',
						),
					),

					// Pins Count
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'show-pins-amount',
						'label'       => __( '# of Pins', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'pins_count',
						'name'        => 'pins_count',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
						'placeholder' => __( '6 is the default value', 'feed-them-social' ),
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'pins_count',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => 'pins_count=6',
							'ifs' => 'single_board_pins,pins_from_user',
						),
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'single_board_pins' => array(
						'if' => array(
							'class'    => 'select#pinterest-messages-selector',
							'operator' => '==',
							'value'    => 'single_board_pins',
						),
					),
					'pins_from_user' => array(
						'if' => array(
							'class'    => 'select#pinterest-messages-selector',
							'operator' => '==',
							'value'    => 'pins_from_user',
						),
					),
					'boards'         => array(
						'if' => array(
							'class'    => 'select#pinterest-messages-selector',
							'operator' => '==',
							'value'    => 'boards_list',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'Pinterest Feed Shortcode', 'feed-them-social' ),
				'generator_class'    => 'pinterest-final-shortcode',
			), // End Pinterest Feed

			// ******************************************
			// Twitter
			// ******************************************
			'twitter'         => array(
				'section_attr_key'   => 'twitter_',
				'section_title'      => __( 'Twitter Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'fts-twitter-shortcode-form',

				// Form Info
				'form_wrap_classes'  => 'twitter-shortcode-form',
				'form_wrap_id'       => 'fts-twitter-form',

				// Token Check
				'token_check'        => array(
					array(
						'option_name'  => 'fts_twitter_custom_access_token_secret',
						'no_token_msg' => __( '<strong>STEP 1:</strong> Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
					),
				),

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feed Type', 'feed-them-social' ),
					'select_wrap_classes' => 'twitter-gen-selection',
					'select_classes' => '',
					'select_name'    => 'twitter-messages-selector',
					'select_id'      => 'twitter-messages-selector',
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// User Feed
					array(
						'value' => 'user',
						'title' => __( 'User Feed', 'feed-them-social' ),
					),

					// hastag Feed
					array(
						'value' => 'hashtag',
						'title' => __( 'Hashtag, Search and more Feed', 'feed-them-social' ),
					),
				),
				'short_attr_final'   => 'yes',

				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				'main_options'       => array(

					// Twitter Search Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'twitter_hashtag_etc_name',
						'label'       => __( 'Twitter Search Name (required)', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_hashtag_etc_name',
						'name'        => 'twitter_hashtag_etc_name',
						'value'       => '',
						'instructional-text' => __( 'You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'search',
							'var_final_if' => 'no',
							'empty_error' => 'yes',
							'ifs' => 'twitter_search',
							'empty_error_if' => array(
								'attribute' => 'select#twitter-messages-selector',
								'operator' => '==',
								'value'    => 'hashtag',
							),
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'twitter-hashtag-etc-wrap',
							'sub_options_title' => __( 'Twitter Search', 'feed-them-social' ),
						),
						'sub_options_end' => true,
					),

					// Twitter Name
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'twitter_name',
						'label'       => __( 'Twitter Name', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_name',
						'name'        => 'twitter_name',
						'instructional-text' => '<span class="hashtag-option-small-text">' . __( 'Twitter Name is only required if you want to show a', 'feed-them-social' ) . ' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">' . __( 'Follow Button', 'feed-them-social' ) . '</a>.</span><span class="must-copy-twitter-name">' . __( 'You must copy your', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __( 'Twitter Name', 'feed-them-social' ) . '</a> ' . __( 'and paste it in the first input below.', 'feed-them-social' ) . '</span>',
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'twitter_name',
							'var_final_if' => 'no',
							'empty_error'  => 'yes',
							'empty_error_if' => array(
								'attribute' => 'select#twitter-messages-selector',
								'operator' => '==',
								'value'    => 'user',
							),
						),
					),

					// Tweet Count
					array(
						'option_type' => 'input',
						'label'       => __( '# of Tweets (optional)', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'tweets_count',
						'name'        => 'tweets_count',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
						'placeholder' => __( '6 is the default value', 'feed-them-social' ),
						'value'       => '',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'tweets_count',
							'var_final_if' => 'yes',
							'var_final_value' => 'no',
							'empty_error'  => 'set',
							'empty_error_value' => 'tweets_count=6',
						),
					),

					// Twitter Fixed Height
					array(
						'option_type' => 'input',
						'label'       => __( 'Twitter Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto height', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'twitter_height',
						'name'        => 'twitter_height',
						'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),
						'short_attr'  => array(
							'attr_name'    => 'twitter_height',
							'var_final_if' => 'yes',
							'var_final_value' => '',
							'empty_error'  => 'set',
							'empty_error_value' => '',
						),
					),

					// Show Cover Photo
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Cover Photo', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-cover-photo',
						'name'        => 'twitter-cover-photo',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'cover_photo',
						),
					),

					// Show Stats Bar
					array(
						'option_type' => 'select',
						'label'       => __( 'Stats Bar', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-stats-bar',
						'name'        => 'twitter-stats-bar',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'stats_bar',
						),
					),

					// Show Retweets
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Retweets', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-show-retweets',
						'name'        => 'twitter-show-retweets',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_retweets',
						),
					),

					// Show Replies
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Replies', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-show-replies',
						'name'        => 'twitter-show-replies',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'show_replies',
						),
					),

					// Pop Up Option
					array(
						'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Display Photos & Videos in Popup', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-popup-option',
						'name'        => 'twitter-popup-option',

						// Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
						'prem_req'    => 'yes',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'popup',
							'ifs' => 'twitter_popup',
						),
					),

					// ******************************************
					// Facebook Load More Options
					// ******************************************
					// Twitter Load More Button
					array(
						'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Load More Button', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_load_more_option',
						'name'        => 'twitter_load_more_option',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name'    => '',
							'empty_error_value' => '',
							'no_attribute' => 'yes',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'twitter-loadmore-wrap',
						),
					),

					// Twitter Load More Style
					array(
						'option_type' => 'select',
						'label'       => __( 'Load More Style', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_load_more_style',
						'name'        => 'twitter_load_more_style',
						'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
						'options'     => array(
							array(
								'label' => __( 'Button', 'feed-them-social' ),
								'value' => 'button',
							),
							array(
								'label' => __( 'AutoScroll', 'feed-them-social' ),
								'value' => 'autoscroll',
							),
						),
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'loadmore',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-twitter-load-more-options-wrap',
						),
						'sub_options_end' => true,
					),

					// Twitter Page Load more Amount
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more posts will load at a time.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'twitter_loadmore_count',
						'name'        => 'twitter_loadmore_count',
						'placeholder' => __( '5 is the default number', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_count',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_count=5',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-twitter-load-more-options2-wrap',
						),
					),

					// Twitter Load more Button Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'twitter_loadmore_button_width',
						'name'        => 'twitter_loadmore_button_width',
						'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'prem_req'    => 'yes',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_maxwidth',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_maxwidth=300px',
							'ifs' => 'load_more',
						),
					),

					// Twitter Load more Button Margin
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_loadmore_button_margin',
						'name'        => 'twitter_loadmore_button_margin',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_margin',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_margin=10px',
							'ifs' => 'load_more',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Twitter Grid Options
					// ******************************************
					// Twitter Display Posts in Grid
					array(
						'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
						'input_wrap_class' => 'twitter-posts-in-grid-option-wrap',
						'option_type' => 'select',
						'label'       => __( 'Display Posts in Grid', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter-grid-option',
						'name'        => 'twitter-grid-option',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'short_attr'  => array(
							'attr_name'    => 'grid',
							'empty_error'  => 'set',
							'set_operator' => '==',
							'set_equals'   => 'yes',
							'empty_error_value' => '',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-grid-options-wrap',
						),
					),

					// Grid Column Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Column Width', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_grid_column_width',
						'name'        => 'twitter_grid_column_width',
						'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define the Width of each post and the Space between each post below. You must add px after any number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/docs/responsive-grid-css/" target="_blank">', '</a>' ),
						'placeholder' => '310px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'colmn_width',
							'empty_error' => 'set',
							'empty_error_value' => 'colmn_width=310px',
							'ifs' => 'grid',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-twitter-grid-options-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
					),

					// Grid Spaces Between Posts
					array(
						'option_type' => 'input',
						'label'       => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'twitter_grid_space_between_posts',
						'name'        => 'twitter_grid_space_between_posts',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',
						'or_req_plugin' => 'combine_streams',
						'or_req_plugin_three' => 'facebook_reviews',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'space_between_posts',
							'empty_error' => 'set',
							'empty_error_value' => 'space_between_posts=10px',
							'ifs' => 'grid',
						),
						'sub_options_end' => 2,
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'twitter_popup'  => array(
						'if' => array(
							'class'    => 'select#twitter-popup-option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'twitter_search' => array(
						'if' => array(
							'class'    => 'select#twitter-messages-selector',
							'operator' => '==',
							'value'    => 'hashtag',
						),
					),
					'load_more'      => array(
						'if' => array(
							'class'    => 'select#twitter_load_more_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'grid'           => array(
						'if' => array(
							'class'    => 'select#twitter-grid-option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'Twitter Feed Shortcode', 'feed-them-social' ),
				'generator_class'    => 'twitter-final-shortcode',
			), // End Twitter Feed

			// ******************************************
			// Instagram
			// ******************************************
			'instagram'       => array(
				'section_attr_key'   => 'instagram_',
				'section_title'      => __( 'Instagram Shortcode Generator', 'feed-them-social' ),
				'section_wrap_class' => 'fts-instagram-shortcode-form',

				// Form Info
				'form_wrap_classes'  => 'instagram-shortcode-form',
				'form_wrap_id'       => 'fts-instagram-form',

				// Token Check
				'token_check'        => array(
					array(
						'option_name'  => 'fts_instagram_custom_api_token',
						'no_token_msg' => __( '<strong>STEP 1:</strong> Please get your Access Token on the <a href="admin.php?page=fts-instagram-feed-styles-submenu-page">Instagram Options</a> page before getting started. ' . $step2_custom_message . '', 'feed-them-social' ),
					),
				),

				// Feed Type Selection
				'feed_type_select'   => array(
					'label'          => __( 'Feed Type', 'feed-them-social' ),
					'select_wrap_classes' => 'instagram-gen-selection',
					'select_classes' => '',
					'select_name'    => 'instagram-messages-selector',
					'select_id'      => 'instagram-messages-selector',
				),

				// Feed Types and their options
				'feeds_types'        => array(

					// User Feed
					array(
						'value' => 'user',
						'title' => __( 'User Feed', 'feed-them-social' ),
					),

					// hastag Feed
					array(
						'value' => 'hashtag',
						'title' => __( 'Hashtag Feed', 'feed-them-social' ),
					),

					// location Feed
					array(
						'value' => 'location',
						'title' => __( 'Location Feed', 'feed-them-social' ),
					),
				),

				// Feed Type Selection
				// 'conversion_input' => array(
				// 'main_wrap_class' => 'instagram-id-option-wrap',
				// 'conv_section_title' => __('Convert Instagram Name to ID', 'feed-them-social'),
				// 'instructional-text' => 'You must copy your <a href="https://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below',
				// 'input_wrap_class' => 'instagram_name',
				// 'label' => __('Instagram Name (required)', 'feed-them-social'),
				// 'id' => 'convert_instagram_username',
				// 'name' => 'convert_instagram_username',
				// Button
				// 'btn-value' => __('Convert Instagram Username', 'feed-them-social'),
				// 'onclick' => 'converter_instagram_username();',
				// ),
				// 'short_attr_final' => 'yes',
				// Inputs relative to all Feed_types of this feed. (Eliminates Duplication)[Excluded from loop when creating select]
				'main_options'       => array(

					// Instagram ID
					array(
						'option_type' => 'input',
						'input_wrap_class' => 'instagram_name',
						'label'       => array(
							1 => array(
								'text' => __( 'Instagram ID # (required)', 'feed-them-social' ),
								'class' => 'instagram-user-option-text',
							),
							2 => array(
								'text' => __( 'Hashtag (required)', 'feed-them-social' ),
								'class' => 'instagram-hashtag-option-text',
							),
							3 => array(
								'text' => __( 'Location ID (required)', 'feed-them-social' ),
								'class' => 'instagram-location-option-text',
							),
						),
						'type'        => 'text',
						'id'          => 'instagram_id',
						'name'        => 'instagram_id',
						'required'    => 'yes',
						'instructional-text' => array(
							1 => array(
                                'text' => __( '<div class="fts-insta-info-plus-wrapper">If your Access Token is set on the Instagram Options page of our plugin your ID should appear below.</div>', 'feed-them-social' ),
                                'class' => 'instagram-user-option-text',
							),
							2 => array(
								'text' => __( 'Add your Hashtag below. <strong>DO NOT</strong> add the #, just the name.', 'feed-them-social' ),
								'class' => 'instagram-hashtag-option-text',
							),
							3 => array(
								'text' => __( '<strong>NOTE:</strong> The post count may not count proper in some location instances because private instagram photos are in the mix. We cannot pull private accounts photos in any location feed. Add your Location ID below.', 'feed-them-social' ),
								'class' => 'instagram-location-option-text',
							),
						),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'instagram_id',
							'var_final_if' => 'no',
							'empty_error'  => 'yes',
						),
					),

					// Access Token
			//		array(
			//			'option_type' => 'input',
			//			'label'       => __( 'Access Token (required) ', 'feed-them-social' ) . '<br/><small>' . __( '', 'feed-them-social' ) . '</small>',
			//			'type'        => 'text',
			//			'id'          => 'insta_access_token',
			//			'name'        => 'insta_access_token',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
			//			'placeholder' => __( '', 'feed-them-social' ),

						// Relative to JS.
			//			'short_attr'  => array(
			//				'attr_name'    => 'access_token',
			//				'var_final_if' => 'yes',
			//				'empty_error'  => 'set',
			//				'empty_error_value' => '',
			//			),
			//		),

					// Pic Count
					array(
						'option_type' => 'input',
						'label'       => __( '# of Pics (optional)', 'feed-them-social' ) . $limitforpremium,
						'type'        => 'text',
						'id'          => 'pics_count',
						'name'        => 'pics_count',

						// Only needed if Prem_Req = More otherwise remove (must have array key req_plugin)
						// 'prem_req_more_msg' => '<br/><small>' . __('More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-social') . '</small>',
						'placeholder' => __( '6 is the default value', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'pics_count',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',
							'empty_error_value' => 'pics_count=6',
						),
					),

					// Feed Type
					array(
						'option_type' => 'select',
						'id'          => 'instagram-messages-selector',
						'no_html'     => 'yes',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'type',
						),
					),

					// Instagram Width
					array(
						'input_wrap_class' => 'instagram_width_option',
						'option_type' => 'input',
						'label'       => __( 'Gallery Width', 'feed-them-social' ),
						'label_note'  => __( 'Leave blank for auto height', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_page_width',
						'name'        => 'instagram_page_width',
						'placeholder' => '50% or 450px ' . __( 'for example', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'width',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',

							// Special case: need no attribute if empty
							'empty_error_value' => '',
						),
					),

					// Instagram Fixed Height
					array(
						'input_wrap_class' => 'instagram_fixed_height_option',
						'option_type' => 'input',
						'label'       => __( 'Gallery Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Use this option to create a scrolling feed.', 'feed-them-social' ) . '</small>',
						'label_note'  => __( 'Leave blank for auto height', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_page_height',
						'name'        => 'instagram_page_height',
						'placeholder' => '450px ' . __( 'for example', 'feed-them-social' ),

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'height',
							'var_final_if' => 'yes',
							'empty_error'  => 'set',

							// Special case: need no attribute if empty
							'empty_error_value' => '',
						),
					),

					// ******************************************
					// Profile Wrap
					// ******************************************
					array(
						'grouped_options_title' => __( 'Profile', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Show Profile Info', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-profile-wrap',
						'name'        => 'instagram-profile-wrap',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'profile_wrap',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'main-instagram-profile-options-wrap',
						),
					),
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Profile Photo', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-profile-photo',
						'name'        => 'instagram-profile-photo',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'profile_photo',
							'ifs' => 'profile_wrap',
						),
						'sub_options' => array(
							'sub_options_wrap_class' => 'instagram-profile-options-wrap',
						),
					),
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Profile Stats', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-profile-stats',
						'name'        => 'instagram-profile-stats',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'profile_stats',
							'ifs' => 'profile_wrap',
						),
					),
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Profile Name', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-profile-name',
						'name'        => 'instagram-profile-name',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'profile_name',
							'ifs' => 'profile_wrap',
						),
					),
					array(
						'option_type' => 'select',
						'label'       => __( 'Show Profile Description', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-profile-description',
						'name'        => 'instagram-profile-description',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'profile_description',
							'ifs' => 'profile_wrap',
						),
						'sub_options_end' => 2,
					),

					// ******************************************
					// Super Gallery
					// ******************************************
					array(
						'grouped_options_title' => __( 'Gallery Options', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Gallery Style', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram-custom-gallery',
						'name'        => 'instagram-custom-gallery',
						'options'     => array(
							1 => array(
								'label' => __( 'New Gallery Style', 'feed-them-social' ),
								'value' => 'yes',
							),
							2 => array(
								'label' => __( 'Classic Gallery Style', 'feed-them-social' ),
								'value' => 'no',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'super_gallery',
							'ifs' => 'super_gallery',
						),
					),
					array(
						'input_wrap_class' => 'fb-page-columns-option-hide',
						'option_type' => 'select',
						'label'       => __( 'Number of Columns', 'feed-them-social' ),
						'type'        => 'text',
						'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong>' . __( 'Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below. Please add px after any number.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __( 'View demo', 'feed-them-social' ) . '</a>',
						'id'          => 'fts_instagram_columns',
						'name'        => 'fts_instagram_columns',
						'default_value' => '3',
						'options'     => array(
							array(
								'label' => __( '1', 'feed-them-social' ),
								'value' => '1',
							),
							array(
								'label' => __( '2', 'feed-them-social' ),
								'value' => '2',
							),
							array(
								'label' => __( '3', 'feed-them-social' ),
								'value' => '3',
							),
							array(
								'label' => __( '4', 'feed-them-social' ),
								'value' => '4',
							),
							array(
								'label' => __( '5', 'feed-them-social' ),
								'value' => '5',
							),
							array(
								'label' => __( '6', 'feed-them-social' ),
								'value' => '6',
							),
							array(
								'label' => __( '7', 'feed-them-social' ),
								'value' => '7',
							),
							array(
								'label' => __( '8', 'feed-them-social' ),
								'value' => '8',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'columns',
							'ifs' => 'super_gallery',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-super-instagram-options-wrap',
						),
					),
					array(
						'input_wrap_class' => 'fb-page-columns-option-hide',
						'option_type' => 'select',
						'label'       => __( 'Force Columns', 'feed-them-social' ) . '<br/><small>' . __( 'No, will allow the images to be responsive for smaller devices. Yes, will force columns.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'fts_instagram_force_columns',
						'name'        => 'fts_instagram_force_columns',
						'default_value' => 'no',
						'options'     => array(
							array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'force_columns',
							'ifs' => 'super_gallery',
						),
					),

					// Space between Photos
					array(
						'option_type' => 'input',
						'label'       => __( 'The space between photos', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-instagram-container-margin',
						'name'        => 'fts-slicker-instagram-container-margin',
						'placeholder' => '1px',
						'value'       => '',
						'short_attr'  => array(
							'attr_name' => 'space_between_photos',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => 'space_between_photos=1px',
							'ifs' => 'super_gallery',
						),
					),

					// Icon Size
					array(
						'option_type' => 'input',
						'label'       => __( 'Size of the Instagram Icon', 'feed-them-social' ),
						'label_note'  => __( 'Visible when you hover over photo', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-instagram-icon-center',
						'name'        => 'fts-slicker-instagram-icon-center',
						'placeholder' => '65px',
						'short_attr'  => array(
							'attr_name' => 'icon_size',
							'var_final_if' => 'yes',
							'empty_error' => 'set',
							'empty_error_value' => 'icon_size=65px',
							'ifs' => 'super_gallery',
						),
					),

					// Hide Date, Likes and Comments
					array(
						'option_type' => 'select',
						'label'       => __( 'Date, Heart & Comment icon', 'feed-them-social' ),
						'label_note'  => __( 'Good for image sizes under 120px', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'fts-slicker-instagram-container-hide-date-likes-comments',
						'name'        => 'fts-slicker-instagram-container-hide-date-likes-comments',
						'options'     => array(
							1 => array(
								'label' => __( 'Show', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Hide', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'short_attr'  => array(
							'attr_name' => 'hide_date_likes_comments',
							'ifs' => 'super_gallery',
						),
						'sub_options_end' => true,
					),

					// ******************************************
					// Load More
					// ******************************************
					array(
						'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Load more posts', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_load_more_option',
						'name'        => 'instagram_load_more_option',

						// Premium Required - yes/no/more (more allows for us to limit things by numbers, also allows for special message above option.)
						'prem_req'    => 'yes',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name'    => 'load_more',
							'var_final_if' => 'no',
							'no_attribute' => 'yes',
						),
					),

					// Load More Option Type
					array(
						'option_type' => 'select',
						'label'       => __( 'Load more style', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_load_more_style',
						'name'        => 'instagram_load_more_style',
						'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed-them-social' ),
						'options'     => array(
							1 => array(
								'label' => __( 'Button', 'feed-them-social' ),
								'value' => 'button',
							),
							2 => array(
								'label' => __( 'AutoScroll', 'feed-them-social' ),
								'value' => 'autoscroll',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'loadmore',
							'var_final_if' => 'no',
							'var_final_value' => '',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-instagram-load-more-options-wrap',
						),
					),

					// Instagram Page Load more Amount
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Amount', 'feed-them-social' ) . '<br/><small>' . __( 'How many more posts will load at a time.', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'instagram_loadmore_count',
						'name'        => 'instagram_loadmore_count',
						'placeholder' => __( '5 is the default number', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_count',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_count=5',
							'ifs' => 'load_more',
						),

						// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
						'sub_options' => array(
							'sub_options_wrap_class' => 'fts-instagram-load-more-options2-wrap',

							// 'sub_options_instructional_txt' => '<a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a> ' . __('of the Super Instagram gallery.', 'feed-them-social'),
						),
					),

					// Instagram Page Load more Button Width
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
						'type'        => 'text',
						'id'          => 'instagram_loadmore_button_width',
						'name'        => 'instagram_loadmore_button_width',
						'placeholder' => '300px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_maxwidth',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_maxwidth=300px',
							'ifs' => 'load_more',
						),
					),

					// Facebook Page Load more Button Margin
					array(
						'option_type' => 'input',
						'label'       => __( 'Load more Button Margin', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_loadmore_button_margin',
						'name'        => 'instagram_loadmore_button_margin',
						'placeholder' => '10px ' . __( 'for example', 'feed-them-social' ),
						'value'       => '',
						'req_plugin'  => 'fts_premium',

						// Relative to JS.
						'short_attr'  => array(
							'attr_name' => 'loadmore_btn_margin',
							'empty_error' => 'set',
							'empty_error_value' => 'loadmore_btn_margin=10px',
							'ifs' => 'load_more',
						),
						'sub_options_end' => 2,
					),

					// Pop Up Option
					array(
						'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
						'option_type' => 'select',
						'label'       => __( 'Display Photos & Videos in Popup', 'feed-them-social' ),
						'type'        => 'text',
						'id'          => 'instagram_popup_option',
						'name'        => 'instagram_popup_option',
						'options'     => array(
							1 => array(
								'label' => __( 'No', 'feed-them-social' ),
								'value' => 'no',
							),
							2 => array(
								'label' => __( 'Yes', 'feed-them-social' ),
								'value' => 'yes',
							),
						),
						'req_plugin'  => 'fts_premium',
						'short_attr'  => array(
							'attr_name' => 'popup',
						),
					),
				),

				// Final Shortcode ifs
				'shortcode_ifs'      => array(
					'profile_wrap'  => array(
						'if' => array(
							'class'    => 'select#instagram-profile-wrap',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'super_gallery' => array(
						'if' => array(
							'class'    => 'select#instagram-custom-gallery',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
					'load_more'     => array(
						'if' => array(
							'class'    => 'select#instagram_load_more_option',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),

				// Generator Info
				'generator_title'    => __( 'Instagram Feed Shortcode', 'feed-them-social' ),
				'generator_class'    => 'instagram-final-shortcode',
			), // End Instagram Feed
		);

		return $feed_settings_array;
	}
}