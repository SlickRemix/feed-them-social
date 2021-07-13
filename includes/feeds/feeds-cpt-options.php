<?php


/**
 * Gallery Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    Feed_CPT_Options
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Feed_CPT_Options
 */
class Feed_CPT_Options {

	/**
	 * All Gallery Options
	 *
	 * @var array
	 */
	public $all_options;

	/**
	 * Feed_CPT_Options constructor.
	 */
	public function __construct() { }

	/**
	 * All Gallery Options
	 *
	 * Function to return all Gallery options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_all_options() {
		$instance = new self();

		$instance->feed_type_options();
		$instance->layout_options();
		$instance->color_options();
		$instance->watermark_options();
		$instance->twitter_options();
		$instance->woocommerce_extra_options();
		$instance->pagination_options();
		$instance->tags_options();

		return $instance->all_options;
	}

	/**
	 * Color Options
	 *
	 * Options for the Color Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function feed_type_options() {
		$this->all_options['feed_type_options'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => esc_html__( 'Feed Color Options', 'feed_them_social' ),
			'section_wrap_class' => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(

				// Feed Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'         => 'ft-gallery-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-feed-background-color-input',
					'name'          => 'fts_feed_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Feed Grid Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Grid Posts Background Color', 'feed_them_social' ),
					'class'         => 'fb-feed-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-grid-posts-background-color-input',
					'name'          => 'fts_grid_posts_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Border Bottom Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Border Bottom Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-border-bottom-color-input',
					'name'          => 'fts_border_bottom_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore background Color.
				array(
					'grouped_options_title' => esc_html__( 'Loadmore Button', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'                 => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'                  => 'text',
					'id'                    => 'ft-gallery-loadmore-background-color-input',
					'name'                  => 'fts_loadmore_background_color',
					'default_value'         => '',
					'placeholder'           => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),
				// Loadmore background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-text-color-input',
					'name'          => 'fts_loadmore_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore Count Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Image Count Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-count-text-color-input',
					'name'          => 'fts_loadmore_count_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

			),
		);

		return $this->all_options['feed_type_options'];
	} //END LAYOUT OPTIONS

	/**
	 * Layout Options
	 *
	 * Options for the Layout Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function layout_options() {
		$this->all_options['layout'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => esc_html( 'Layout Options', 'feed_them_social' ),
			'section_wrap_class' => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			// Token Check // We'll use these option for premium messages in the future.
			'premium_msg_boxes'  => array(
				'album_videos' => array(
					'req_plugin' => 'fts_premium',
					'msg'        => '',
				),
				'reviews'      => array(
					'req_plugin' => 'facebook_reviews',
					'msg'        => '',
				),
			),

			'main_options'       => array(

				// Gallery Type.
				array(
					'input_wrap_class' => 'ft-wp-gallery-type',
					'option_type'      => 'select',
					'label'            => trim(
						sprintf(
							esc_html__( 'Choose the gallery type%1$s View all Gallery %2$sDemos%3$s', 'feed_them_social' ),
							'<br/><small>',
							'<a href="' . esc_url( 'https://feedthemgallery.com/gallery-demo-one/' ) . '" target="_blank">',
							'</a></small>'
						)
					),
					'type'             => 'text',
					'id'               => 'fts_type',
					'name'             => 'fts_type',
					'default_value'    => 'gallery',
					'options'          => array(
						array(
							'label' => esc_html__( 'Responsive Image Gallery ', 'feed_them_social' ),
							'value' => 'gallery',
						),
						array(
							'label' => esc_html__( 'Image Gallery Collage (Masonry)', 'feed_them_social' ),
							'value' => 'gallery-collage',
						),
						array(
							'label' => esc_html__( 'Image Post', 'feed_them_social' ),
							'value' => 'post',
						),
						array(
							'label' => esc_html__( 'Image Post in Grid (Masonry)', 'feed_them_social' ),
							'value' => 'post-in-grid',
						),
					),
				),
				// Show Photo Caption.
				array(
					'input_wrap_class' => 'fb-page-description-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Show Photo Caption', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_photo_caption',
					'name'             => 'fts_photo_caption',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'Title and Description', 'feed_them_social' ),
							'value' => 'title_description',
						),
						array(
							'label' => esc_html__( 'Title', 'feed_them_social' ),
							'value' => 'title',
						),
						array(
							'label' => esc_html__( 'Description', 'feed_them_social' ),
							'value' => 'description',
						),
						array(
							'label' => esc_html__( 'None', 'feed_them_social' ),
							'value' => 'none',
						),
					),
				),

				// Photo Caption Placement.
				array(
					'input_wrap_class' => 'ftg-page-title-description-placement-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Photo Caption Placement', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_photo_caption_placement',
					'name'             => 'fts_photo_caption_placement',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'Caption Above Photo', 'feed_them_social' ),
							'value' => 'show_top',
						),
						array(
							'label' => esc_html__( 'Caption Below Photo', 'feed_them_social' ),
							'value' => 'show_bottom',
						),
					),
				),

				// ******************************************
				// Facebook Grid Options
				// ******************************************
				// Facebook Page Display Posts in Grid
				// array(
				// 'grouped_options_title' => __('Grid', 'feed_them_social'),
				// 'input_wrap_class' => 'fb-posts-in-grid-option-wrap',
				// 'option_type' => 'select',
				// 'label' => __('Display Posts in Grid', 'feed_them_social'),
				// 'type' => 'text',
				// 'id' => 'fts_grid_option',
				// 'name' => 'fts_grid_option',
				// 'default_value' => 'no',
				// 'options' => array(
				// array(
				// 'label' => __('No', 'feed_them_social'),
				// 'value' => 'no',
				// ),
				// array(
				// 'label' => __('Yes', 'feed_them_social'),
				// 'value' => 'yes',
				// ),
				// ),
				// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
				// 'sub_options' => array(
				// 'sub_options_wrap_class' => 'main-grid-options-wrap',
				// ),
				// ),
				array(
					'input_wrap_class'   => 'fb-page-columns-option-hide',
					'option_type'        => 'select',
					'label'              => esc_html__( 'Number of Columns', 'feed_them_social' ),
					'type'               => 'text',
					'instructional-text' => sprintf(
						esc_html__( '%1$sNOTE:%2$s Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
						'<strong>',
						'</strong>'
					),
					'id'                 => 'fts_columns',
					'name'               => 'fts_columns',
					'default_value'      => '4',
					'options'            => array(
						array(
							'label' => esc_html__( '1', 'feed_them_social' ),
							'value' => '1',
						),
						array(
							'label' => esc_html__( '2', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5', 'feed_them_social' ),
							'value' => '5',
						),
						array(
							'label' => esc_html__( '6', 'feed_them_social' ),
							'value' => '6',
						),
						array(
							'label' => esc_html__( '7', 'feed_them_social' ),
							'value' => '7',
						),
						array(
							'label' => esc_html__( '8', 'feed_them_social' ),
							'value' => '8',
						),
					),
				),
				array(
					'input_wrap_class'   => 'ftg-masonry-columns-option-hide',
					'option_type'        => 'select',
					'label'              => esc_html__( 'Number of Columns', 'feed_them_social' ),
					'type'               => 'text',
					'instructional-text' => sprintf(
						esc_html__( '%1$sNOTE:%2$s Using the Columns option will make this gallery fully responsive and it will adapt in size to your containers width. Choose the Number of Columns and Space between each image below.', 'feed_them_social' ),
						'<strong>',
						'</strong>'
					),
					'id'                 => 'fts_columns_masonry2',
					'name'               => 'fts_columns_masonry2',
					'default_value'      => '3',
					'options'            => array(
						array(
							'label' => esc_html__( '2', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5', 'feed_them_social' ),
							'value' => '5',
						),
					),
				),
				array(
					'input_wrap_class' => 'ftg-masonry-columns-option-hide',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Space between Images', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_columns_masonry_margin',
					'name'             => 'fts_columns_masonry_margin',
					'default_value'    => '5',
					'options'          => array(
						array(
							'label' => esc_html__( '1px', 'feed_them_social' ),
							'value' => '1',
						),
						array(
							'label' => esc_html__( '2px', 'feed_them_social' ),
							'value' => '2',
						),
						array(
							'label' => esc_html__( '3px', 'feed_them_social' ),
							'value' => '3',
						),
						array(
							'label' => esc_html__( '4px', 'feed_them_social' ),
							'value' => '4',
						),
						array(
							'label' => esc_html__( '5px', 'feed_them_social' ),
							'value' => '5',
						),
						array(
							'label' => esc_html__( '10px', 'feed_them_social' ),
							'value' => '10',
						),
						array(
							'label' => esc_html__( '15px', 'feed_them_social' ),
							'value' => '15',
						),
						array(
							'label' => esc_html__( '20px', 'feed_them_social' ),
							'value' => '20',
						),
					),
				),
				array(
					'input_wrap_class' => 'fb-page-columns-option-hide',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Force Columns%1$s Yes, will force image columns. No, will allow the images to be resposive for smaller devices%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_force_columns',
					'name'             => 'fts_force_columns',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),

					),
				),
				// Grid Column Width
				// array(
				// 'input_wrap_class' => 'fb-page-grid-option-hide fb-page-columns-option-hide ftg-hide-for-columns',
				// 'option_type' => 'input',
				// 'label' => __('Grid Column Width', 'feed_them_social'),
				// 'type' => 'text',
				// 'id' => 'fts_grid_column_width',
				// 'name' => 'fts_grid_column_width',
				// 'instructional-text' =>
				// sprintf(__('%1$sNOTE:%2$s Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed_them_social'),
				// '<strong>',
				// '</strong>'
				// ),
				// 'placeholder' => '310px ' . __('for example', 'feed_them_social'),
				// 'default_value' => '310px',
				// 'value' => '',
					   // This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					   // 'sub_options' => array(
					   // 'sub_options_wrap_class' => 'fts-facebook-grid-options-wrap',
					   // ),
				// ),
				   // Grid Spaces Between Posts.
				   array(
					   'input_wrap_class' => 'fb-page-grid-option-hide fb-page-grid-option-border-bottom',
					   'option_type'      => 'input',
					   'label'            => esc_html__( 'Space between Images', 'feed_them_social' ),
					   'type'             => 'text',
					   'id'               => 'fts_grid_space_between_posts',
					   'name'             => 'fts_grid_space_between_posts',
					   'placeholder'      => '1px ' . esc_html__( 'for example', 'feed_them_social' ),
					   'default_value'    => '1px',
					   // 'sub_options_end' => 2,
				   ),
				// Show Name.
				array(
					'input_wrap_class' => 'ft-gallery-user-name',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'User Name%1$s Company or user who took this photo%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_username',
					'name'             => 'fts_username',
					'placeholder'      => '',
					'default_value'    => '',
				),
				// Show Name Link.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'User Custom Link%1$s Custom about page or social media page link%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_user_link',
					'name'          => 'fts_user_link',
					'placeholder'   => '',
					'default_value' => '',
				),
				// Show Share.
				array(
					'input_wrap_class' => 'ft-gallery-share',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Show Share Options%1$s Appears in the bottom left corner and in popup%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_wp_share',
					'name'             => 'fts_wp_share',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
				),
				// Show Date.
				array(
					'input_wrap_class' => 'ft-gallery-date',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Show Date%1$s Date image was uploaded%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_wp_date',
					'name'             => 'fts_wp_date',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
				),
				// Taking this option out so we can position our close button better.
				// Show Icon.
			// array(
			// 'input_wrap_class' => 'ft-gallery-icon',
			// 'option_type'      => 'select',
			// 'label'            =>
			// sprintf(
			// esc_html__( 'Show Wordpress Icon%1$s Appears in the top left corner%2$s', 'feed_them_social' ),
			// '<br/><small>',
			// '</small>'
			// ),
			// 'type'             => 'text',
			// 'id'               => 'fts_wp_icon',
			// 'name'             => 'fts_wp_icon',
			// 'default_value'    => 'no',
			// 'options'          => array(
			// array(
			// 'label' => esc_html__( 'Yes', 'feed_them_social' ),
			// 'value' => 'yes',
			// ),
			// array(
			// 'label' => esc_html__( 'No', 'feed_them_social' ),
			// 'value' => 'no',
			// ),
			// ),
			// ),
				// Words per photo caption
				// array(
				// 'option_type' => 'input',
				// 'label' => __('# of words per photo caption', 'feed_them_social') . '<br/><small>' . __('Typing 0 removes the photo caption', 'feed_them_social') . '</small>',
				// 'type' => 'hidden',
				// 'id' => 'fts_word_count_option',
				// 'name' => 'fts_word_count_option',
				// 'placeholder' => '',
				// 'default_value' => '',
				// ),
				// Image Sizes on page.
				array(
					'input_wrap_class'   => 'ft-images-sizes-page',
					'option_type'        => 'ft-images-sizes-page',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'              => esc_html__( 'Image Size on Page', 'feed_them_social' ),
					'class'              => 'ft-gallery-images-sizes-page',
					'type'               => 'select',
					'id'                 => 'fts_images_sizes_page',
					'name'               => 'fts_images_sizes_page',
					'default_value'      => 'medium',
					'placeholder'        => '',
					'autocomplete'       => 'off',
				),

				// Max-width for Images & Videos.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Max-width for Images', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_max_image_vid_width',
					'name'          => 'fts_max_image_vid_width',
					'placeholder'   => '500px',
					'default_value' => '',
				),
				// Gallery Width.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Gallery Max-width', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_width',
					'name'          => 'fts_width',
					'placeholder'   => '500px',
					'default_value' => '',
				),
				// Gallery Height for scrolling feeds using Post format only, this does not work for grid or gallery options except gallery squared because it does not use masonry. For all others it will be hidden.
				array(
					'input_wrap_class' => 'ft-gallery-height',
					'option_type'      => 'input',
					'label'            =>
						sprintf(
							esc_html__( 'Gallery Height%1$s Set the height to have a scrolling feed. Only works for Responsive Image Gallery and the Image Post option.%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'             => 'text',
					'id'               => 'fts_height',
					'name'             => 'fts_height',
					'placeholder'      => '600px',
					'default_value'    => '',
				),
				// Gallery Margin.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'Gallery Margin%1$s To center feed type auto%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_margin',
					'name'          => 'fts_margin',
					'placeholder'   => 'auto',
					'default_value' => 'auto',
				),
				// Gallery Padding.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Gallery Padding', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_padding',
					'name'          => 'fts_padding',
					'placeholder'   => '10px',
					'default_value' => '',
				),
				// ******************************************
				// Gallery Popup
				// ******************************************
				// Display Photos in Popup
				array(
					'grouped_options_title' => esc_html__( 'Popup', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 => esc_html__( 'Display Photos in Popup', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'fts_popup',
					'name'                  => 'fts_popup',
					'default_value'         => 'yes',
					'options'               => array(
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
					'sub_options'           => array(
						'sub_options_wrap_class' => 'facebook-popup-wrap',
					),
					'sub_options_end'       => true,
				),
				// Image Sizes in popup.
				array(
					'input_wrap_class'   => 'ft-images-sizes-popup',
					'option_type'        => 'ft-images-sizes-popup',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'              => esc_html__( 'Image Size in Popup', 'feed_them_social' ),
					'class'              => 'ft-gallery-images-sizes-popup',
					'type'               => 'select',
					'id'                 => 'fts_images_sizes_popup',
					'name'               => 'fts_images_sizes_popup',
					'default_value'      => '',
					'placeholder'        => '',
					'autocomplete'       => 'off',
				),
				array(
					'input_wrap_class' => 'ft-popup-display-options',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Popup Options', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'ft_popup_display_options',
					'name'             => 'ft_popup_display_options',
					'default_value'    => 'no',
					'options'          => array(
						array(
							'label' => esc_html__( 'Default', 'feed_them_social' ),
							'value' => 'default',
						),
						array(
							'label' => esc_html__( 'Full Width & Info below Photo', 'feed_them_social' ),
							'value' => 'full-width-second-half-bottom',
						),
						array(
							'label' => esc_html__( 'Full Width, Photo Only', 'feed_them_social' ),
							'value' => 'full-width-photo-only',
						),
					),
				),

				// ******************************************
				// Gallery Load More Options
				// ******************************************
				// Load More Button
				array(
					'grouped_options_title' => esc_html__( 'Load More Images', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 =>
						sprintf(
							esc_html__( 'Load More Button%1$s Load More unavailable while using the Pagination option.%2$s', 'feed_them_social' ),
							'<br/><small class="ftg-loadmore-notice-colored" style="display: none;">',
							'</small>'
						),
					'type'                  => 'text',
					'id'                    => 'fts_load_more_option',
					'name'                  => 'fts_load_more_option',
					'default_value'         => 'no',
					'options'               => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
					'sub_options'           => array(
						'sub_options_wrap_class' => 'facebook-loadmore-wrap',
					),
				),

				// # of Photos
				array(

					'option_type'   => 'input',
					'label'         => esc_html__( '# of Photos Visible', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_photo_count',
					'name'          => 'fts_photo_count',
					'default_value' => '',
					'placeholder'   => '',
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output).
					'sub_options'   => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
					),

				),

				// Load More Style.
				array(
					'option_type'        => 'select',
					'label'              => esc_html__( 'Load More Style', 'feed_them_social' ),
					'type'               => 'text',
					'id'                 => 'fts_load_more_style',
					'name'               => 'fts_load_more_style',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s The Button option will show a "Load More Posts" button under your feed. The AutoScroll option will load more posts when you reach the bottom of the feed. AutoScroll ONLY works if you\'ve filled in a Fixed Height for your feed.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'default_value'      => 'button',
					'options'            => array(
						1 => array(
							'label' => esc_html__( 'Button', 'feed_them_social' ),
							'value' => 'button',
						),
						2 => array(
							'label' => esc_html__( 'AutoScroll', 'feed_them_social' ),
							'value' => 'autoscroll',
						),
					),
					'sub_options_end'    => true,
				),

				// Load more Button Width.
				array(
					'option_type'   => 'input',
					'label'         =>
						sprintf(
							esc_html__( 'Load more Button Width%1$s Leave blank for auto width%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_loadmore_button_width',
					'name'          => 'fts_loadmore_button_width',
					'placeholder'   => '300px ' . esc_html__( 'for example', 'feed_them_social' ),
					'default_value' => '300px',
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					'sub_options'   => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap',
					),
				),
				// Load more Button Margin.
				array(
					'option_type'     => 'input',
					'label'           => esc_html__( 'Load more Button Margin', 'feed_them_social' ),
					'type'            => 'text',
					'id'              => 'fts_loadmore_button_margin',
					'name'            => 'fts_loadmore_button_margin',
					'placeholder'     => '10px ' . esc_html__( 'for example', 'feed_them_social' ),
					'default_value'   => '10px',
					'value'           => '',
					'sub_options_end' => 2,
				),

				// ******************************************
				// Gallery Image Count Options
				// ******************************************
				// Load More Style
				array(
					'option_type'        => 'select',
					'label'              => esc_html__( 'Show Image Count', 'feed_them_social' ),
					'type'               => 'text',
					'id'                 => 'fts_show_pagination',
					'name'               => 'fts_show_pagination',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s This will display the number of images you have in your gallery, and will appear centered at the bottom of your image feed. For Example: 4 of 50 (4 being the number of images you have loaded on the page already and 50 being the total number of images in the gallery.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'default_value'      => 'yes',
					'options'            => array(
						1 => array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output).
					'sub_options'        => array(
						'sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap',
					),
					'sub_options_end'    => true,
				),

				// ******************************************
				// Gallery Sort Options
				// ******************************************
				array(
					'grouped_options_title' => esc_html__( 'Order of Images', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 => esc_html__( 'Choose the order of Images', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'ftg_sort_type',
					'name'                  => 'ftg_sort_type',
					'default_value'         => 'above-below',
					'options'               => array(
						1 => array(
							'label' => esc_html__( 'Sort by date', 'feed_them_social' ),
							'value' => 'date',
						),
						2 => array(
							'label' => esc_html__( 'The order you manually sorted images', 'feed_them_social' ),
							'value' => 'menu_order',
						),
						3 => array(
							'label' => esc_html__( 'Sort alphabetically (A-Z)', 'feed_them_social' ),
							'value' => 'title',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Display Options', 'feed_them_social' ),
					'label'         =>
						sprintf(
							esc_html__( 'Display Options%1$s Display a select option for this gallery so your users can select the sort order. Does not work with Loadmore button, only works with Pagination.%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'ftg_sorting_options',
					'name'          => 'ftg_sorting_options',
					'default_value' => 'no',
					'options'       => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Position of Select Option', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg_position_of_sort_select',
					'name'          => 'ftg_position_of_sort_select',
					'default_value' => 'above-below',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Top', 'feed_them_social' ),
							'value' => 'above',
						),
						2 => array(
							'label' => esc_html__( 'Bottom', 'feed_them_social' ),
							'value' => 'below',
						),
						3 => array(
							'label' => esc_html__( 'Top and Bottom', 'feed_them_social' ),
							'value' => 'above-below',
						),
					),
					'sub_options'   => array(
						'sub_options_wrap_class' => 'ftg-sorting-options-wrap',
					),
				),

				array(
					'option_type'     => 'select',
					'label'           => esc_html__( 'Align Select Option', 'feed_them_social' ),
					'type'            => 'text',
					'id'              => 'ftg_align_sort_select',
					'name'            => 'ftg_align_sort_select',
					'default_value'   => 'left',
					'options'         => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
					),
					'sub_options_end' => true,
				),

				// ******************************************
				// Download Free Image Button Sort Options
				// ******************************************
				array(
					'grouped_options_title' => esc_html__( 'Free Image Download', 'feed_them_social' ),
					'option_type'           => 'ftg-free-download-size',
					'instructional-text'    =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s To turn this option on simply choose an image size. A download icon will appear under the image to the right and in the popup. If for some reason the image size you choose does not appear on the front end you may need to regenerate your images. This free plugin called %3$sRegenerate Thumbnails%4$s does an amazing job of that.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<a href="' . esc_url( 'plugin-install.php?s=regenerate+thumbnails&tab=search&type=term' ) . '" target="_blank">',
							'</a>'
						),
					'label'                 => esc_html__( 'Choose the size', 'feed_them_social' ),
					'class'                 => 'ft-images-sizes-free-download-button',
					'type'                  => 'select',
					'id'                    => 'ftg_free_download_size',
					'name'                  => 'ftg_free_download_size',
					'default_value'         => '',
					'placeholder'           => '',
					'autocomplete'          => 'off',
				),

				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Free Download Text', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_free_download_text',
					'name'          => 'fts_free_download_text',
					'placeholder'   => 'Free Download',
					'default_value' => '',
					'value'         => '',
				),

			),

		);

		return $this->all_options['layout'];
	} //END LAYOUT OPTIONS

	/**
	 * Color Options
	 *
	 * Options for the Color Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function color_options() {
		$this->all_options['colors'] = array(
			'section_attr_key'   => 'facebook_',
			'section_title'      => esc_html__( 'Feed Color Options', 'feed_them_social' ),
			'section_wrap_class' => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'  => 'fb-page-shortcode-form',
			'form_wrap_id'       => 'fts-fb-page-form',
			'main_options'       => array(

				// Feed Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'         => 'ft-gallery-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-feed-background-color-input',
					'name'          => 'fts_feed_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Feed Grid Background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Grid Posts Background Color', 'feed_them_social' ),
					'class'         => 'fb-feed-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-grid-posts-background-color-input',
					'name'          => 'fts_grid_posts_background_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Border Bottom Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Border Bottom Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-border-bottom-color-input',
					'name'          => 'fts_border_bottom_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore background Color.
				array(
					'grouped_options_title' => esc_html__( 'Loadmore Button', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Background Color', 'feed_them_social' ),
					'class'                 => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'                  => 'text',
					'id'                    => 'ft-gallery-loadmore-background-color-input',
					'name'                  => 'fts_loadmore_background_color',
					'default_value'         => '',
					'placeholder'           => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),
				// Loadmore background Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-text-color-input',
					'name'          => 'fts_loadmore_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore Count Color.
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Image Count Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-loadmore-count-text-color-input',
					'name'          => 'fts_loadmore_count_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

			),
		);

		return $this->all_options['colors'];
	} //END LAYOUT OPTIONS

	/**
	 * Twitter Options
	 *
	 * Options for the Twitter Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function twitter_options() {

		$this->all_options['twitter'] = array(
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
        );

		return $this->all_options['twitter'];
	}

	/**
	 * Woocommerce Extra Options
	 *
	 * These are Gallery to Woo options (just for saving not for display)
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function woocommerce_extra_options() {

		$this->all_options['woocommerce_exta'] = array(
			'main_options' => array(
				// required_prem_plugin must match the array key returned in fts_required_plugins function.
				'required_prem_plugin' => 'feed_them_social_premium',
				// ******************************************
				// Images to Products
				// ******************************************
				// Automatically turn created Images to products.
				array(
					'option_type'   => 'checkbox',
					'default_value' => '',
					'name'          => 'fts_auto_image_woo_prod',
				),
				array(
					'option_type'   => 'checkbox',
					'default_value' => '',
					'name'          => 'fts_smart_image_orient_prod',
				),
				array(
					'option_type'   => 'select',
					'default_value' => '',
					'name'          => 'fts_image_to_woo_model_prod',
				),
				array(
					'option_type'   => 'select',
					'default_value' => '',
					'name'          => 'fts_landscape_to_woo_model_prod',
				),
				array(
					'option_type'   => 'select',
					'default_value' => '',
					'name'          => 'fts_square_to_woo_model_prod',
				),
				array(
					'option_type'   => 'select',
					'default_value' => '',
					'name'          => 'fts_portrait_to_woo_model_prod',
				),

				array(
					'option_type'   => 'select',
					'default_value' => '',
					'name'          => 'fts_zip_to_woo_model_prod',
				),
			),
		);

		return $this->all_options['woocommerce_exta'];
	}

	/**
	 * Watermark Options
	 *
	 * Options for the Watermark Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function watermark_options() {
		$this->all_options['watermark'] = array(
			// required_prem_plugin must match the array key returned in fts_required_plugins function.
			'required_prem_plugin' => 'feed_them_social_premium',
			'section_attr_key'     => 'facebook_',
			'section_title'        => esc_html__( 'Watermark Options', 'feed_them_social' ),
			'section_wrap_class'   => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'    => 'fb-page-shortcode-form',
			'form_wrap_id'         => 'fts-fb-page-form',
			'main_options'         => array(
				// Disable Right Click.
				array(
					'input_wrap_class'   => 'ft-watermark-disable-right-click',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s This option will disable the right click option on desktop computers so people cannot look at the source code. This is not fail safe but for the vast majority this is enough to deter people from trying to find the image source.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'option_type'        => 'select',
					'label'              => esc_html__( 'Disable Right Click', 'feed_them_social' ),
					'type'               => 'text',
					'id'                 => 'fts_watermark_disable_right_click',
					'name'               => 'fts_watermark_disable_right_click',
					'default_value'      => '',
					'options'            => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),
				// Use Watermark Options.
				array(
					'input_wrap_class' => 'ft-watermark-enable-options',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Use Options Below', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_watermark_enable_options',
					'name'             => 'fts_watermark_enable_options',
					'default_value'    => 'no',
					'options'          => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),

				// Choose Watermark Image.
				array(
					'option_type'        => 'input',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sNOTE:%2$s Upload the exact image size you want to display, we will not rescale the image in anyway.', 'feed_them_social' ),
							'<strong>',
							'</strong>'
						),
					'label'              => esc_html__( 'Watermark Image', 'feed_them_social' ),
					'id'                 => 'ft-watermark-image',
					'name'               => 'ft-watermark-image',
					'class'              => '',
					'type'               => 'button',
					'default_value'      => esc_html__( 'Upload or Choose Watermark', 'feed_them_social' ),
					'placeholder'        => '',
					'value'              => '',
					'autocomplete'       => 'off',
				),
				// Watermark Image Link for front end if user does not use imagick or GD library method.
				array(
					'input_wrap_class' => 'ft-watermark-hide-these-options',
					'option_type'      => 'input',
					// 'label' => __('Watermark Image', 'feed_them_social'),
					// 'class' => 'fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'             => 'hidden',
					'id'               => 'ft_watermark_image_input',
					// 'instructional-text' => '<strong>' . __('NOTE:', 'feed_them_social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed_them_social'),
					'name'             => 'ft_watermark_image_input',
					'default_value'    => '',
					// 'placeholder' => __('', 'feed_them_social'),
					'autocomplete'     => 'off',
				),
				// Watermark Image ID so we can pass it to merge the watermark over images.
				array(
					'input_wrap_class' => 'ft-watermark-hide-these-options',
					'option_type'      => 'input',
					// 'label' => __('Watermark Image', 'feed_them_social'),
					// 'class' => 'fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'             => 'hidden',
					'id'               => 'ft_watermark_image_id',
					// 'instructional-text' => '<strong>' . __('NOTE:', 'feed_them_social') . '</strong> ' . __('Define the Width of each post and the Space between each post below. You must add px after any number.', 'feed_them_social'),
					'name'             => 'ft_watermark_image_id',
					'default_value'    => '',
					// 'placeholder' => __('', 'feed_them_social'),
					'autocomplete'     => 'off',
				),

				// Watermark Options
				array(
					'input_wrap_class' => 'ft-watermark-enabled',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Watermark Type', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_watermark',
					'name'             => 'fts_watermark',
					'default_value'    => 'yes',
					'options'          => array(
						array(
							'label' => esc_html__( 'Watermark Overlay Image (Does not Imprint logo on Image)', 'feed_them_social' ),
							'value' => 'overlay',
						),
						array(
							'label' => esc_html__( 'Watermark Image (Imprint logo on the selected image sizes)', 'feed_them_social' ),
							'value' => 'imprint',
						),
					),
				),

				// Watermark Options
				array(
					'input_wrap_class' => 'ft-watermark-overlay-options',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Overlay Options', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_watermark',
					'name'             => 'fts_watermark_overlay_enable',
					'default_value'    => 'popup-only',
					'options'          => array(
						array(
							'label' => esc_html__( 'Select an Option', 'feed_them_social' ),
							'value' => '',
						),
						array(
							'label' => esc_html__( 'Watermark in popup only', 'feed_them_social' ),
							'value' => 'popup-only',
						),
						array(
							'label' => esc_html__( 'Watermark for image on page only', 'feed_them_social' ),
							'value' => 'page-only',
						),
						array(
							'label' => esc_html__( 'Watermark for image on page and popup', 'feed_them_social' ),
							'value' => 'page-and-popup',
						),
					),
				),

				// Hidden Input to set array
				array(
					'input_wrap_class'   => 'ft-watermark-hidden-options ft-gallery-image-sizes-checkbox-wrap-label',
					'option_type'        => 'checkbox-image-sizes',
					'instructional-text' =>
						sprintf(
							esc_html__( '%1$sIMPORTANT:%2$s This option will permanently mark your chosen image size once you click the publish button or update button. Set the opacity of your %3$sWatermark Image%4$s before you upload it above for this option. We suggest using a png for the best clarity and not a gif.', 'feed_them_social' ),
							'<strong>',
							'</strong>',
							'<strong>',
							'</strong>'
						),
					'label'              => esc_html__( 'Image Sizes', 'feed_them_social' ),
					'class'              => 'ft-watermark-opacity',
					'type'               => 'hidden',
					'id'                 => 'ft_watermark_image_sizes',
					'name'               => 'ft_watermark_image_sizes',
					'default_value'      => '',
					'value'              => '',
					'placeholder'        => __( '', 'feed_them_social' ),
					'autocomplete'       => 'off',
				),

				// Watermark Image Sizes to convert
				array(
					'input_wrap_class' => 'ft-watermark-hidden-options ft-gallery-image-sizes-checkbox-wrap',
					'option_type'      => 'checkbox-dynamic-image-sizes',
					'label'            => __( '', 'feed_them_social' ),
					'class'            => 'ft-watermark-opacity',
					'type'             => 'checkbox',
					'id'               => 'ft_watermark_image_',
					'name'             => '',
					'default_value'    => '',
					'placeholder'      => __( '', 'feed_them_social' ),
					'autocomplete'     => 'off',
				),
				// Duplicate Full Image before it is watermarked, usefull if zip option is being used and or selling full image
				array(
					'input_wrap_class' => 'ft-watermark-duplicate-image',
					'option_type'      => 'select',
					'label'            =>
						sprintf(
							esc_html__( 'Duplicate Full Image%1$s before watermarking', 'feed_them_social' ),
							'<br/>'
						),
					'type'             => 'text',
					'id'               => 'fts_duplicate_image',
					'name'             => 'fts_duplicate_image',
					'default_value'    => '',
					'options'          => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),
				// Watermark Opacity
				array(
					'input_wrap_class' => 'ft-gallery-watermark-opacity',
					'option_type'      => 'input',
					'label'            => esc_html__( 'Image Opacity', 'feed_them_social' ),
					'class'            => 'ft-watermark-opacity',
					'type'             => 'text',
					'id'               => 'ft_watermark_image_opacity',
					'name'             => 'ft_watermark_image_opacity',
					'default_value'    => '',
					'placeholder'      => esc_html__( '.5 for example', 'feed_them_social' ),
					'autocomplete'     => 'off',
				),
				// Watermark Position
				array(
					'input_wrap_class' => 'ft-watermark-position',
					'option_type'      => 'select',
					'label'            => esc_html__( 'Watermark Position', 'feed_them_social' ),
					'type'             => 'text',
					'id'               => 'fts_position',
					'name'             => 'fts_position',
					'default_value'    => 'bottom-right',
					'options'          => array(
						array(
							'label' => esc_html__( 'Centered', 'feed_them_social' ),
							'value' => 'center',
						),
						array(
							'label' => esc_html__( 'Top Right', 'feed_them_social' ),
							'value' => 'top-right',
						),
						array(
							'label' => esc_html__( 'Top Left', 'feed_them_social' ),
							'value' => 'top-left',
						),
						array(
							'label' => esc_html__( 'Top Center', 'feed_them_social' ),
							'value' => 'top-center',
						),
						array(
							'label' => esc_html__( 'Bottom Right', 'feed_them_social' ),
							'value' => 'bottom-right',
						),
						array(
							'label' => esc_html__( 'Bottom Left', 'feed_them_social' ),
							'value' => 'bottom-left',
						),
						array(
							'label' => esc_html__( 'Bottom Center', 'feed_them_social' ),
							'value' => 'bottom-center',
						),
					),
				),
				// watermark Image Margin
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Watermark Margin', 'feed_them_social' ),
					'class'         => 'ft-watermark-image-margin',
					'type'          => 'text',
					'id'            => 'ft_watermark_image_margin',
					'name'          => 'ft_watermark_image_margin',
					'default_value' => '10px',
					'placeholder'   => esc_html__( '10px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
			),
		);

		return $this->all_options['watermark'];
	} //END WATERMARK OPTIONS

	/**
	 * Pagination Options
	 *
	 * Options for the Pagination Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function pagination_options() {
		$this->all_options['pagination'] = array(
			'required_prem_plugin' => 'feed_them_social_premium',
			'section_attr_key'     => 'facebook_',
			'section_title'        => esc_html__( 'Pagination', 'feed_them_social' ),
			'section_wrap_class'   => 'ftg-section-options',
			// Form Info.
			'form_wrap_classes'    => 'fb-page-shortcode-form',
			'form_wrap_id'         => 'fts-fb-page-form',
			// Token Check // We'll use these option for premium messages in the future.
			'premium_msg_boxes'    => array(
				'album_videos' => array(
					'req_plugin' => 'fts_premium',
					'msg'        => '',
				),
				'reviews'      => array(
					'req_plugin' => 'facebook_reviews',
					'msg'        => '',
				),
			),

			'main_options'         => array(

				// ******************************************
				// Gallery Pagination Options
				// ******************************************
				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Display Pagination', 'feed_them_social' ),
					'label'         =>
						sprintf(
							esc_html__( 'Display Pagination%1$s Pagination unavailable while using the Load More option.%2$s', 'feed_them_social' ),
							'<br/><small class="ftg-pagination-notice-colored" style="display: none;">',
							'</small>'
						),
					'type'          => 'text',
					'id'            => 'fts_show_true_pagination',
					'name'          => 'fts_show_true_pagination',
					'default_value' => 'no',
					'options'       => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),

				// # of Photos
				array(

					'option_type'   => 'input',
					'label'         => esc_html__( '# of Photos Visible', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_pagination_photo_count',
					'name'          => 'fts_pagination_photo_count',
					'default_value' => '',
					'placeholder'   => __( '', 'feed_them_social' ),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Position of Pagination', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'fts_position_of_pagination',
					'name'          => 'fts_position_of_pagination',
					'default_value' => 'above-below',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Top', 'feed_them_social' ),
							'value' => 'above',
						),
						2 => array(
							'label' => esc_html__( 'Bottom', 'feed_them_social' ),
							'value' => 'below',
						),
						3 => array(
							'label' => esc_html__( 'Top and Bottom', 'feed_them_social' ),
							'value' => 'above-below',
						),
					),
					// This should be placed in the STARTING field of sub options that way wrap and instruction text is above this div (end will be in final options for div output)
					// 'sub_options' => array(
					// 'sub_options_wrap_class' => 'ftg-pagination-options-wrap',
					// ),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Align Pagination', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg_align_pagination',
					'name'          => 'ftg_align_pagination',
					'default_value' => 'right',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
					),
				),
				// Pagination Color
				// JUST NEED TO FINISH THE COLOR OPTIONS FOR THE PAGINATION AND APPLY THEM TO THE FRONT END
				// Loadmore background Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Button Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-pagination-background-color-input',
					'name'          => 'fts_pagination_button_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Active Button', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-pagination-background-color-input',
					'name'          => 'fts_pagination_active_button_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Loadmore background Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Numbers Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-pagination-text-color-input',
					'name'          => 'fts_pagination_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

				array(
					'grouped_options_title' => esc_html__( 'Image Count Options', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 =>
						sprintf(
							esc_html__( 'Display Image Count%1$s For Example: Showing 1-50 of 800 Images.%2$s', 'feed_them_social' ),
							'<br/><small>',
							'</small>'
						),
					'type'                  => 'text',
					'id'                    => 'ftg_display_image_count',
					'name'                  => 'ftg_display_image_count',
					'default_value'         => 'yes',
					'options'               => array(
						1 => array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
						2 => array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Align Image Count', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg_align_count',
					'name'          => 'ftg_align_count',
					'default_value' => 'left',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
					),
				),

				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Image count Text Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-gallery-true-pagination-count-text-color-input',
					'name'          => 'fts_true_pagination_count_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

			),
		);

		return $this->all_options['pagination'];
	} //END PAGINATION OPTIONS


	/**
	 * Tags Options
	 *
	 * Options for the Tags Tab
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function tags_options() {
		$this->all_options['tags'] = array(
			'required_prem_plugin' => 'feed_them_social_premium',
			'section_attr_key'     => 'facebook_',
			'section_title'        => esc_html__( 'Image Tags', 'feed_them_social' ),
			'section_wrap_class'   => 'ftg-section-options',
			// Form Info
			'form_wrap_classes'    => 'fb-page-shortcode-form',
			'form_wrap_id'         => 'fts-fb-page-form',
			// Token Check // We'll use these option for premium messages in the future
			'premium_msg_boxes'    => array(
				'album_videos' => array(
					'req_plugin' => 'fts_premium',
					'msg'        => '',
				),
				'reviews'      => array(
					'req_plugin' => 'facebook_reviews',
					'msg'        => '',
				),
			),

			'main_options'         => array(

				// ******************************************
				// Gallery Tags Options
				// ******************************************
				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Show Image Tags', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-gallery-show-tags',
					'name'          => 'fts_show_tags',
					'default_value' => 'no',
					'options'       => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Yes', 'feed_them_social' ),
							'value' => 'yes',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Tags Separator', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg-image-tags-separator',
					'name'          => 'ftg_image_tags_separator',
					'default_value' => '',
					'options'       => array(
						array(
							'label' => esc_html__( 'Comma - ie* One, Two, Three', 'feed_them_social' ),
							'value' => ',&nbsp;',
						),
						array(
							'label' => esc_html__( 'Period - ie* One &#46; Two &#46; Three', 'feed_them_social' ),
							'value' => '&nbsp;&#46;&nbsp;',
						),
						array(
							'label' => esc_html__( 'Bullet - ie* One &bull; Two &bull; Three', 'feed_them_social' ),
							'value' => '&nbsp;&bull;&nbsp;',
						),
						array(
							'label' => esc_html__( 'Pipe - ie* One | Two | Three', 'feed_them_social' ),
							'value' => '&nbsp;|&nbsp;',
						),
						array(
							'label' => esc_html__( 'Space - ie* One Two Three', 'feed_them_social' ),
							'value' => '&nbsp;',
						),
						array(
							'label' => esc_html__( 'Dash - ie* One - Two - Three', 'feed_them_social' ),
							'value' => '&nbsp;-&nbsp;',
						),
					),
				),

				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Link Font Size', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-tags-text-size',
					'name'          => 'ft_tags_text_size',
					'default_value' => '',
					'placeholder'   => esc_html__( '12px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Tags Link Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Link Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-tags-link-color',
					'name'          => 'ft_tags_link_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Align', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg-align-tags',
					'name'          => 'ftg_align_tags',
					'default_value' => '',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
						3 => array(
							'label' => esc_html__( 'Center', 'feed_them_social' ),
							'value' => 'center',
						),
					),
				),
				/*
				array(
					'option_type' => 'input',
					'label' => __('Link Margin Right', 'feed_them_social'),
					'type' => 'text',
					'id' => 'ft-tags-link-margin-right',
					'name' => 'ft_tags_link_margin_right',
					'default_value' => '',
					'placeholder' => __('5px', 'feed_them_social'),
					'autocomplete' => 'off',
				),*/

				// Tags Background Color
				array(
					'grouped_options_title' => esc_html__( 'Image Tags Background Wrap', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Color', 'feed_them_social' ),
					'class'                 => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'                  => 'text',
					'id'                    => 'ft-tags-background-color-input',
					'name'                  => 'fts_tags_background_color',
					'default_value'         => '',
					'placeholder'           => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),

				// Tags Background Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Padding', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-tags-padding',
					'name'          => 'fts_tags_padding',
					'default_value' => '',
					'placeholder'   => esc_html__( '18px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

				// Tags Text
				array(
					'grouped_options_title' => esc_html__( 'Customize the word, Tags:', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Change Tags Text', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'ftg-image-tags-text',
					'name'                  => 'ftg_image_tags_text',
					'default_value'         => '',
					'placeholder'           => esc_html__( 'Tags:', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),
				// Tags Text Size
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Font Size', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-tags-text-size',
					'name'          => 'fts_tags_text_size',
					'default_value' => '',
					'placeholder'   => esc_html__( '12px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Font Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-tags-text-color',
					'name'          => 'ft_tags_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( ' Margin Right', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-tags-text-margin-right',
					'name'          => 'ft_tags_text_margin_right',
					'default_value' => '',
					'placeholder'   => esc_html__( '5px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

				// ******************************************
				// Gallery Gallery Tags Options
				// ******************************************
				array(
					'grouped_options_title' => esc_html__( 'Gallery Tags', 'feed_them_social' ),
					'option_type'           => 'select',
					'label'                 => esc_html__( 'Show Gallery Tags', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'ft-gallery-show-page-tags',
					'name'                  => 'fts_show_page_tags',
					'default_value'         => 'no',
					'options'               => array(
						array(
							'label' => esc_html__( 'No', 'feed_them_social' ),
							'value' => 'no',
						),
						array(
							'label' => esc_html__( 'Above Images', 'feed_them_social' ),
							'value' => 'above_images',
						),
						array(
							'label' => esc_html__( 'Below Images', 'feed_them_social' ),
							'value' => 'below_images',
						),
					),
				),

				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Tags Separator', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg-page-tags-separator',
					'name'          => 'ftg_page_tags_separator',
					'default_value' => '',
					'options'       => array(
						array(
							'label' => esc_html__( 'Comma - ie* One, Two, Three', 'feed_them_social' ),
							'value' => ',&nbsp;',
						),
						array(
							'label' => esc_html__( 'Period - ie* One &#46; Two &#46; Three', 'feed_them_social' ),
							'value' => '&nbsp;&#46;&nbsp;',
						),
						array(
							'label' => esc_html__( 'Bullet - ie* One &bull; Two &bull; Three', 'feed_them_social' ),
							'value' => '&nbsp;&bull;&nbsp;',
						),
						array(
							'label' => esc_html__( 'Pipe - ie* One | Two | Three', 'feed_them_social' ),
							'value' => '&nbsp;|&nbsp;',
						),
						array(
							'label' => esc_html__( 'Space - ie* One Two Three', 'feed_them_social' ),
							'value' => '&nbsp;',
						),
						array(
							'label' => esc_html__( 'Dash - ie* One - Two - Three', 'feed_them_social' ),
							'value' => '&nbsp;-&nbsp;',
						),
					),
				),

				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Link Font Size', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-page-tags-text-size',
					'name'          => 'ft_page_tags_text_size',
					'default_value' => '',
					'placeholder'   => esc_html__( '12px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				// Tags Link Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Link Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-page-tags-link-color',
					'name'          => 'ft_page_tags_link_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'select',
					'label'         => esc_html__( 'Align', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ftg-align-page-tags',
					'name'          => 'ftg_align_page_tags',
					'default_value' => '',
					'options'       => array(
						1 => array(
							'label' => esc_html__( 'Left', 'feed_them_social' ),
							'value' => 'left',
						),
						2 => array(
							'label' => esc_html__( 'Right', 'feed_them_social' ),
							'value' => 'right',
						),
						3 => array(
							'label' => esc_html__( 'Center', 'feed_them_social' ),
							'value' => 'center',
						),
					),
				),
				/*
				array(
					'option_type' => 'input',
					'label' => __('Link Margin Right', 'feed_them_social'),
					'type' => 'text',
					'id' => 'ft-page-tags-link-margin-right',
					'name' => 'ft_page_tags_link_margin_right',
					'default_value' => '',
					'placeholder' => __('5px', 'feed_them_social'),
					'autocomplete' => 'off',
				),*/

				// Tags Background Color
				array(
					'grouped_options_title' => esc_html__( 'Gallery Tags Background Wrap', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Color', 'feed_them_social' ),
					'class'                 => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'                  => 'text',
					'id'                    => 'ft-page-tags-background-color-input',
					'name'                  => 'ft_page_gallery_tags_background_color',
					'default_value'         => '',
					'placeholder'           => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),

				// Tags Background Color
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Padding', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-page-tags-padding',
					'name'          => 'fts_page_tags_padding',
					'default_value' => '',
					'placeholder'   => esc_html__( '18px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

				// Tags Text
				array(
					'grouped_options_title' => esc_html__( 'Customize the phrase, Gallery Tags:', 'feed_them_social' ),
					'option_type'           => 'input',
					'label'                 => esc_html__( 'Change Tags: Text', 'feed_them_social' ),
					'type'                  => 'text',
					'id'                    => 'ft-gallery-page-tags-text',
					'name'                  => 'ftg_page_tags_text',
					'default_value'         => '',
					'placeholder'           => esc_html__( 'Gallery Tags:', 'feed_them_social' ),
					'autocomplete'          => 'off',
				),
				// Tags Text Size
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Font Size', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-gallery-page-tags-text-size',
					'name'          => 'fts_page_tags_text_size',
					'default_value' => '',
					'placeholder'   => esc_html__( '12px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( 'Font Color', 'feed_them_social' ),
					'class'         => 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}',
					'type'          => 'text',
					'id'            => 'ft-page-tags-text-color',
					'name'          => 'ft_page_tags_text_color',
					'default_value' => '',
					'placeholder'   => esc_html__( '#ddd', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),
				array(
					'option_type'   => 'input',
					'label'         => esc_html__( ' Margin Right', 'feed_them_social' ),
					'type'          => 'text',
					'id'            => 'ft-page-tags-text-margin-right',
					'name'          => 'ft_page_tags_text_margin_right',
					'default_value' => '',
					'placeholder'   => esc_html__( '5px', 'feed_them_social' ),
					'autocomplete'  => 'off',
				),

			),
		);

		return $this->all_options['tags'];
	} //END TAGS OPTIONS
}
