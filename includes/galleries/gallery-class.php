<?php
/**
 * Gallery Class
 *
 * This class is what initiates the Feed Them Social class
 *
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gallery
 *
 * @package FeedThemSocial/Core
 */
class Gallery {

	/**
	 * Parent Post ID
	 * used to set Gallery ID
	 *
	 * @var string
	 */
	public $parent_post_id = '';

	/**
	 * Saved Settings Array
	 * an array of settings to save when saving page
	 *
	 * @var string
	 */
	public $saved_settings_array = array();

	/**
	 * Global Prefix
	 * Sets Prefix for global options
	 *
	 * @var string
	 */
	public $global_prefix = 'global_';

	/**
	 * ZIP Gallery Class
	 * initiates ZIP Gallery Class
	 *
	 * @var string
	 */
	public $zip_gallery_class = '';

	/**
	 * Gallery Options
	 * initiates Gallery Options Class
	 *
	 * @var string
	 */
	public $gallery_options_class = '';

	/**
	 * Metabox Settings Class
	 * initiates Metabox Settings Class
	 *
	 * @var string
	 */
	public $metabox_settings_class = '';


	/**
	 * Load Class
	 *
	 * Function to initiate class loading.
	 *
	 * @param array  $all_options All options.
	 * @param string $main_post_type Main Post Type.
	 * @since 1.1.8
	 */
	public static function load( $all_options, $main_post_type ) {
		$instance = new self();
		$instance->set_class_vars( $all_options, $main_post_type );
		$instance->add_actions_filters();
	}

	/**
	 * Gallery constructor.
	 */
	public function __construct() { }


	/**
	 * Set Class Variables
	 *
	 *  Sets the variables for this class
	 *
	 * @param array  $all_options All options.
	 * @param string $main_post_type Main Post Type.
	 * @since 1.1.8
	 */
	public function set_class_vars( $all_options, $main_post_type ) {
			$this->core_functions_class = new Core_Functions();

			$this->saved_settings_array = $all_options;

		// we set current_user_can so our backend functions don't get loaded to the front end.
		// this came about after a ticket we received about our plugin being active and
		// causing a woo booking plugin to not be able to checkout proper, when checking out it would show the cart was empty.
		// this current_user_can resolves that problem.
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include ABSPATH . 'wp-includes/pluggable.php';
		}

		if ( current_user_can( 'manage_options' ) ) {
			// Load Metabox Setings Class (including all of the scripts and styles attached).
			$this->metabox_settings_class = new Metabox_Settings( $this, $this->saved_settings_array );

			// Set Main Post Type.
			$this->metabox_settings_class->set_main_post_type( $main_post_type );

			// Set Metabox Specific Form Inputs.
			$this->metabox_settings_class->set_metabox_specific_form_inputs( true );
		}

			// If Premium add Functionality!
		if ( is_plugin_active( 'feed-them-social-premium/feed-them-social-premium.php' ) ) {
			$this->zip_gallery_class = new Zip_Gallery();
		}
	}


	/**
	 * Add Actions & Filters
	 *
	 * Adds the Actions and filters for the class.
	 *
	 * @since 1.1.8
	 */
	public function add_actions_filters() {

		// Scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'fts_scripts' ) );

		// Set local variables:!
		$this->plugin_locale = 'feed_them_social';

		// Register Gallery CPT!
		add_action( 'init', array( $this, 'fts_cpt' ) );

		// Response Messages!
		add_filter( 'post_updated_messages', array( $this, 'fts_updated_messages' ) );

		// Gallery List function!
		add_filter( 'manage_fts_posts_columns', array( $this, 'fts_set_custom_edit_columns' ) );
		add_action( 'manage_fts_posts_custom_column', array( $this, 'fts_custom_edit_column' ), 10, 2 );

		// Change Button Text!
		add_filter( 'gettext', array( $this, 'fts_set_button_text' ), 20, 3 );

		// Add Meta Boxes!
		add_action( 'add_meta_boxes', array( $this, 'fts_add_metaboxes' ) );

		// Rename Submenu Item to Galleries!
		add_filter( 'attribute_escape', array( $this, 'fts_rename_submenu_name' ), 10, 2 );
		// Add Shortcode!
		add_shortcode( 'fts_list', array( $this, 'fts_display_list' ) );

		// Drag and Drop, buttons etc for media!
		add_action( 'wp_ajax_plupload_action', array( $this, 'fts_plupload_action' ) );

		add_action( 'current_screen', array( $this, 'fts_check_page' ) );

		// Save Meta Box Info!
		add_action( 'save_post_fts', array( $this, 'fts_save_custom_meta_box' ), 10, 2 );

		// Add API Endpoint!
		add_action( 'rest_api_init', array( $this, 'ft_galley_register_gallery_options_route' ) );

		add_action( 'wp_ajax_list_update_order', array( $this, 'fts_order_list' ) );

		// Create another image size for our gallery edit pages!
		add_image_size( 'fts_thumb', 150, 150, true );
		// Add the image name to the media library so we can get a clean version when showing thumbnail on the page for the first time!
		add_filter( 'image_size_names_choose', array( $this, 'fts_custom_thumb_sizes' ) );

		if ( '' === get_option( 'fts_duplicate_post_show' ) ) {

			add_action( 'admin_action_fts_duplicate_post_as_draft', array( $this, 'fts_duplicate_post_as_draft' ) );
			add_filter( 'page_row_actions', array( $this, 'fts_duplicate_post_link' ), 10, 2 );
			add_filter( 'fts_row_actions', array( $this, 'fts_duplicate_post_link' ), 10, 2 );
			add_action( 'post_submitbox_start', array( $this, 'fts_duplicate_post_add_duplicate_post_button' ) );

		}
	}


	/**
	 *  Tab Notice HTML
	 *
	 * Creates notice html for return
	 *
	 * @since 1.0.0
	 */
	public function fts_tab_premium_msg() {
		echo sprintf(
			esc_html__( '%1$sPlease purchase, install and activate %2$sFeed Them Social Premium%3$s for these additional awesome features!%4$s', 'feed_them_social' ),
			'<div class="ft-gallery-premium-mesg">',
			'<a href="' . esc_url( 'https://www.slickremix.com/downloads/feed-them-social/' ) . '" target="_blank">',
			'</a>',
			'</div>'
		);
	}

	/**
	 *  Custom Thumb Sizes
	 *
	 * Adds Custom sizes too
	 *
	 * @param array $sizes Thumbnail Sizes.
	 * @return array
	 * @since
	 */
	public function fts_custom_thumb_sizes( $sizes ) {
		return array_merge(
			$sizes,
			array(
				'fts_thumb' => esc_html__( 'Feed Them Social Thumb', 'feed_them_social' ),
			)
		);
	}

	/**
	 *  Order List
	 *
	 * Attachment order list
	 *
	 * @since 1.0.0
	 */
	public function fts_order_list() {
		// we use the list_item (id="list_item_23880") which then finds the ID right after list_item and we use the id from there.
		$attachment_id = $_POST['list_item'];

		foreach ( $attachment_id as $img_index => $img_id ) {
			$a = array(
				'ID'         => esc_html( $img_id ),
				'menu_order' => esc_html( $img_index ),
			);
			wp_update_post( $a );
		}
	}

	/**
	 *  Check Page
	 *
	 * What page are we on?
	 *
	 * @since 1.0.0
	 */
	public function fts_check_page() {
		$current_screen = get_current_screen();

		$my_get  = stripslashes_deep( $_GET );
		$my_post = stripslashes_deep( $_POST );

		if ( 'fts' === $current_screen->post_type && 'post' === $current_screen->base && is_admin() ) {
			if ( isset( $my_get['post'] ) ) {
				$this->parent_post_id = $my_get['post'];
			}
			if ( isset( $my_post['post'] ) ) {
				$this->parent_post_id = $my_post['post'];
			}
		}
	}

	/**
	 *  Register Gallery Options (REST API)
	 *
	 * Register the gallery options via REST API
	 *
	 * @since 1.0.0
	 */
	public function ft_galley_register_gallery_options_route() {
		register_rest_route(
			'ftgallery/v2',
			'/gallery-options',
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'fts_get_gallery_options' ),
			)
		);
	}

	/**
	 *  Get Gallery Options (REST API)
	 *
	 * Get options using WordPress's REST API
	 *
	 * @param array $gallery_id Gallery ID.
	 * @return string
	 * @since 1.0.0
	 */
	public function fts_get_gallery_options_rest( $gallery_id ) {

		$request = new \WP_REST_Request( 'GET', '/ftgallery/v2/gallery-options' );

		$request->set_param( 'gallery_id', $gallery_id );

		$response = rest_do_request( $request );

		// Check for error.
		if ( is_wp_error( $response ) ) {
			return esc_html__( 'oops something isn\'t right.', 'feed_them_social' );
		}

		$final_response = isset( $response->data ) ? $response->data : esc_html__( 'No Images attached to this post.', 'feed_them_social' );

		return $final_response;
	}

	/**
	 *  Get Gallery Options
	 *
	 * Get options set for a gallery
	 *
	 * @param array $gallery_id Gallery ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function fts_get_gallery_options( $gallery_id ) {

		$post_info = get_post( $gallery_id['gallery_id'] );

		// echo '<pre>';
		// print_r($post_info);
		// echo '</pre>';
		$old_options   = get_post_meta( $gallery_id['gallery_id'], 'fts_settings_options', true );
		$options_array = isset( $old_options ) && ! empty( $old_options ) ? $old_options : array();

		if ( ! $options_array ) {

			// Basic Post Info.
			$options_array['fts_image_id'] = isset( $post_info->ID ) ? $post_info->ID : esc_html__( 'This ID does not exist anymore', 'feed_them_social' );
			$options_array['fts_author']   = isset( $post_info->post_author ) ? $post_info->post_author : '';
			// $options_array['fts_post_date'] = $post_info->post_date_gmt;
			$options_array['fts_post_title'] = isset( $post_info->post_title ) ? $post_info->post_title : '';
			// $options_array['fts_post_alttext'] = $post_info->post_title;
			// $options_array['fts_comment_status'] = $post_info->comment_status;
			foreach ( $this->saved_settings_array as $box_array ) {
				foreach ( $box_array as $box_key => $settings ) {
					if ( 'main_options' === $box_key ) {
						// Gallery Settings.
						foreach ( $settings as $option ) {
							$option_name          = ! empty( $option['name'] ) ? $option['name'] : '';
							$option_default_value = ! empty( $option['default_value'] ) ? $option['default_value'] : '';

							if ( ! empty( $option_name ) && ! empty( $option_default_value ) ) {

								// Set value or use Default_value.
								$options_array[ $option_name ] = $option_default_value;
							}
						}
					}
				}
			}
		}

		return $options_array;
	}

	/**
	 *  Custom Post Type
	 *
	 * Create  custom post type
	 *
	 * @since 1.0.0
	 */
	public function fts_cpt() {
		$responses_cpt_args = array(
			'label'               => esc_html__( 'Feed Them Social', 'feed_them_social' ),
			'labels'              => array(
				'menu_name'          => esc_html__( 'Feeds', 'feed_them_social' ),
				'name'               => esc_html__( 'Feeds', 'feed_them_social' ),
				'singular_name'      => esc_html__( 'Feed', 'feed_them_social' ),
				'add_new'            => esc_html__( 'Add Feed', 'feed_them_social' ),
				'add_new_item'       => esc_html__( 'Add New Feed', 'feed_them_social' ),
				'edit_item'          => esc_html__( 'Edit Feed', 'feed_them_social' ),
				'new_item'           => esc_html__( 'New Feed', 'feed_them_social' ),
				'view_item'          => esc_html__( 'View Feed', 'feed_them_social' ),
				'search_items'       => esc_html__( 'Search Feeds', 'feed_them_social' ),
				'not_found'          => esc_html__( 'No Feeds Found', 'feed_them_social' ),
				'not_found_in_trash' => esc_html__( 'No Feeds Found In Trash', 'feed_them_social' ),
			),

			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,

			'capabilities'        => array(
				'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap'        => true, // Allows Users to still edit Payments
			'has_archive'         => true,
			'hierarchical'        => true,
			'query_var'           => 'fts',
			'rewrite'             => array( 'slug' => 'fts-cpt' ),

			'menu_icon'           => '',
			'supports'            => array( 'title', 'revisions', 'thumbnail' ),
			'order'               => 'DESC',
		// Set the available taxonomies here
		// 'taxonomies' => array('fts_topics')
		);
		register_post_type( 'fts', $responses_cpt_args );
	}

	/**
	 *  Categories (Custom Taxonomy)
	 *
	 * Create  Custom Taxonomy
	 *
	 * @since 1.0.2
	 */
	public function fts_categories() {

		$labels = array(
			'name'              => esc_html__( 'Categories', 'feed_them_social' ),
			'singular_name'     => esc_html__( 'Category', 'feed_them_social' ),
			'search_items'      => esc_html__( 'Search Categories', 'feed_them_social' ),
			'all_items'         => esc_html__( 'All Categories', 'feed_them_social' ),
			'parent_item'       => esc_html__( 'Parent Category', 'feed_them_social' ),
			'parent_item_colon' => esc_html__( 'Parent Category:', 'feed_them_social' ),
			'edit_item'         => esc_html__( 'Edit Category', 'feed_them_social' ),
			'update_item'       => esc_html__( 'Update Category', 'feed_them_social' ),
			'add_new_item'      => esc_html__( 'Add New Category', 'feed_them_social' ),
			'new_item_name'     => esc_html__( 'New Category Name', 'feed_them_social' ),
			'menu_name'         => esc_html__( 'Categories', 'feed_them_social' ),
		);

		register_taxonomy(
			'fts_cats',
			array( 'fts' ),
			array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'query_var'             => true,
				'rewrite'               => true,
				'update_count_callback' => '_update_generic_term_count',
			)
		);
	}

	/**
	 *  Register Taxonomy for Attachments
	 *
	 * Registers
	 *
	 * @since 1.0.2
	 */
	public function fts_add_cats_to_attachments() {
		register_taxonomy_for_object_type( 'fts_cats', 'attachment' );
		// add_post_type_support('attachment', 'fts_cats');
	}

	/**
	 *  Rename Submenu Name
	 * Renames the submenu item in the WordPress dashboard's menu
	 *
	 * @param $safe_text
	 * @param $text
	 * @return string
	 * @since 1.0.0
	 */
	public function fts_rename_submenu_name( $safe_text, $text ) {
		if ( 'Feeds' !== $text ) {
			return $safe_text;
		}
		// We are on the main menu item now. The filter is not needed anymore.
		remove_filter( 'attribute_escape', array( $this, 'fts_rename_submenu_name' ) );

		return esc_html( 'Feed Them Social' );
	}

	/**
	 *  Updated Messages
	 * Updates the messages in the admin area so they match plugin
	 *
	 * @param $messages
	 * @return mixed
	 * @since 1.0.0
	 */
	public function fts_updated_messages( $messages ) {
		// global $post, $post_ID;
		$messages['fts'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => esc_html__( 'Gallery updated.', 'feed_them_social' ),
			2  => esc_html__( 'Custom field updated.', 'feed_them_social' ),
			3  => esc_html__( 'Custom field deleted.', 'feed_them_social' ),
			4  => esc_html__( 'Gallery updated.', 'feed_them_social' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Response restored to revision from %s', 'feed_them_social' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => esc_html__( 'Gallery created.', 'feed_them_social' ),
			7  => esc_html__( 'Gallery saved.', 'feed_them_social' ),
			8  => esc_html__( 'Gallery submitted.', 'feed_them_social' ),
			9  => esc_html__( 'Gallery scheduled for:', 'feed_them_social' ),
			// translators: Publish box date format, see http://php.net/date
			// date_i18n( ( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => esc_html__( 'Gallery draft updated.', 'feed_them_social' ),
		);

		return $messages;
	}

	/**
	 *  Set Custom Edit Columns
	 *
	 * Sets the custom admin columns for gallery list page
	 *
	 * @param $columns
	 * @return array
	 * @since 1.0.0
	 */
	public function fts_set_custom_edit_columns( $columns ) {

		$new = array();

		foreach ( $columns as $key => $value ) {
			// when we find the date column.
			if ( 'title' === $key ) {
				$new[ $key ] = $value;
				// put the tags column before it.
				$new['gallery_thumb']     = '';
				$new['gallery_shortcode'] = esc_html__( 'Gallery Shortcode', 'feed_them_social' );

				if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
					$text = esc_html__( 'Gallery ZIP', 'feed_them_social' );
				} else {
					$text = '';
				}

				$new['gallery_zip'] = $text;

			} else {
				$new[ $key ] = $value;
			}
		}

		return $new;
	}

	/**
	 *  Count Post Images
	 * Return a count of images for our gallery list column.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function fts_count_post_images( $post_id ) {
		$attachments = get_children(
			array(
				'post_parent'    => $post_id,
				'post_mime_type' => 'image',
			)
		);

		return count( $attachments );
	}

	/**
	 * FT Albums Gallery Count
	 * Return a count of galleries in our album.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function ft_album_count_post_galleries( $post_id ) {

		$number_of_galleries_in_ablum = get_post_meta( $post_id, 'fts_album_gallery_ids', true );

		return count( $number_of_galleries_in_ablum );
	}

	/**
	 * FT Galley Custom Edit Column
	 * Put info in matching coloumns we set
	 *
	 * @param $column
	 * @param $post_id
	 * @since 1.0.0
	 */
	public function fts_custom_edit_column( $column, $post_id ) {
		switch ( $column ) {
			case 'gallery_thumb':
				$display_gallery = new Display_Gallery();
				$image_list      = $display_gallery->fts_get_media_rest( $post_id, '1' );
				$thumb_text      = $this->fts_count_post_images( $post_id ) . ' ' . esc_html__( 'Images', 'feed_them_social' );
				$edit_post_url   = get_edit_post_link( $post_id );

				if ( $image_list ) {
					?>
					<a href="<?php echo esc_url( $edit_post_url ); ?>"><img src="<?php echo esc_url( $image_list[0]['media_details']['sizes']['thumbnail']['source_url'] ); ?>" alt="" /><?php echo esc_html( $thumb_text ); ?></a>
					<?php
				}
				break;
			// display a thumbnail photo!
			case 'gallery_shortcode':
				?>
				<input value="[feed_them_social id=<?php echo esc_html( $post_id ); ?>]" onclick="this.select()"/>
				<?php
				break;

			case 'gallery_zip':
				// Add Premium Coloumns!
				if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
					$newest_zip = get_post_meta( $post_id, 'fts_newest_zip_id', true );

					if ( $newest_zip ) {
						$newest_zip_check = $this->fts_zip_exists_check( $newest_zip );

						if ( 'true' === $newest_zip_check ) {
							$fts_get_attachment_info = $this->fts_get_attachment_info( $newest_zip );
							?>
							<a class="fts_download_button_icon" href="<?php echo esc_url( $fts_get_attachment_info['download_url'] ); ?>"><span class="dashicons dashicons-download"></span></a>
							<?php

						} else {
							esc_html_e( 'No ZIP created.', 'feed_them_social' );
						}
					} else {
						esc_html_e( 'No ZIP created.', 'feed_them_social' );
					}
				}
				break;

		}
	}

	/**
	 *  Set Button Text
	 * Set Edit Post buttons for Galleries custom post type
	 *
	 * @param $translated_text
	 * @param $text
	 * @param $domain
	 * @return mixed
	 * @since 1.0.0
	 */
	public function fts_set_button_text( $translated_text, $text, $domain ) {
		$post_id          = isset( $_GET['post'] ) ? $_GET['post'] : '';
		$custom_post_type = get_post_type( $post_id );
		if ( ! empty( $post_id ) && 'fts_responses' === $custom_post_type ) {
			switch ( $translated_text ) {
				case 'Publish':
					$translated_text = esc_html__( 'Save Gallery', 'feed_them_social' );
					break;
				case 'Update':
					$translated_text = esc_html__( 'Update Gallery', 'feed_them_social' );
					break;
				case 'Save Draft':
					$translated_text = esc_html__( 'Save Gallery Draft', 'feed_them_social' );
					break;
				case 'Edit Payment':
					$translated_text = esc_html__( 'Edit Gallery', 'feed_them_social' );
					break;
			}
		}

		return $translated_text;
	}

	/**
	 *  Scripts
	 *
	 * Create Gallery custom post type
	 *
	 * @since 1.0.0
	 */
	public function fts_scripts() {

		global $id, $post;

		// Get current screen.
		$current_screen = get_current_screen();

		if ( is_admin() && 'fts' === $current_screen->post_type && 'post' === $current_screen->base || is_admin() && 'fts' === $current_screen->post_type && isset( $_GET['page'] ) && 'template_settings_page' === $_GET['page'] || is_admin() && 'fts_albums' === $current_screen->post_type && 'post' === $current_screen->base ) {

			// Set the post_id for localization.
			$post_id = isset( $post->ID ) ? array( 'post' => $post->ID ) : '';

			// Image Uploader!
			wp_enqueue_media( $post_id );

			add_filter( 'plupload_init', array( $this, 'plupload_init' ) );

			// Enqueue Magnific Popup CSS.
			wp_enqueue_style( 'magnific-popup-css', plugins_url( 'feed-them-social/includes/feeds/css/magnific-popup.css' ), array(), FTS_CURRENT_VERSION );

			// Enqueue Magnific Popup JS.
			wp_enqueue_script( 'magnific-popup-js', plugins_url( 'feed-them-social/includes/feeds/js/magnific-popup.js' ), array(), FTS_CURRENT_VERSION );

			// Updates the attachments when saving
			// add_filter( 'wp_insert_post_data', array( $this, 'fts_sort_images_meta_save' ), 99, 2 );
			wp_enqueue_style( 'ft-gallery-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css', array(), FTS_CURRENT_VERSION );

		} else {
			return;
		}
	}

	/**
	 *  Sort Images Meta Save
	 *
	 * Sort images for meta save
	 *
	 * @param $post_data
	 * @return mixed
	 * @since 1.0.0
	 */
	public function fts_sort_images_meta_save( $post_data ) {

		$attach_id = $this->fts_get_attachment_info( $post_data['ID'] );

		foreach ( $attach_id as $img_index => $img_id ) {
			$a = array(
				'ID'         => $img_id,
				'menu_order' => $img_index,
			);
			// wp_update_post( $a );
		}
		return $post_data;
	}

	/**
	 * Add Gallery Meta Boxes
	 *
	 * Add metaboxes to the gallery
	 *
	 * @since 1.0.0
	 */
	public function fts_add_metaboxes() {
		global $post;
		// Check we are using Feed Them Social Custom Post type.
		if ( 'fts' !== $post->post_type ) {
			return;
		}

		// Image Uploader and Gallery area in admin.
		add_meta_box( 'ft-galleries-upload-mb', esc_html__( 'Feed Them Social Settings', 'feed_them_social' ), array( $this, 'fts_tab_menu_metabox' ), 'fts', 'normal', 'high', null );

		// Link Settings Meta Box.
		add_meta_box( 'ft-galleries-shortcode-side-mb', esc_html__( 'Feed Them Social Shortcode', 'feed_them_social' ), array( $this, 'fts_shortcode_meta_box' ), 'fts', 'side', 'high', null );
	}

	/**
	 *  Format Bytes
	 *
	 * Creates a human readable size for return
	 *
	 * @param $bytes
	 * @param int   $precision
	 * @return float
	 * @since 1.0.0
	 */
	public function fts_format_bytes( $bytes, $precision = 2 ) {
		$units  = array( 'B', 'KB', 'MB', 'GB', 'TB' );
		$bytes  = max( $bytes, 0 );
		$pow    = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow    = min( $pow, count( $units ) - 1 );
		$bytes /= pow( 1024, $pow );

		return round( $bytes, $precision );
	}

	/**
	 *  Edit Page Popup
	 *
	 * Outputs the edit page popup html
	 *
	 * @param $gallery_id
	 * @since 1.1.6
	 */
	public function fts_edit_page_popup( $gallery_id ) {
		?>
		<div class="ft-gallery-popup-form <?php echo $premium_active = is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ? 'ftg-premium-active' : 'ftg-premium-not-active' ?>" style="display:none">

			<label><?php esc_html_e( 'Title of image', 'feed_them_social' ); ?></label>
			<input value="" class="fts-gallery-title"/>
			<label><?php esc_html_e( 'Alt text for image', 'feed_them_social' ); ?></label>
			<input value="" class="fts-gallery-alttext"/>
			<label><?php esc_html_e( 'Description of image', 'feed_them_social' ); ?></label>
			<textarea class="fts-gallery-description"></textarea><br/>

            <?php if( 'ftg-premium-active' === $premium_active ) {?>
			<div class="tagsdiv popup-ftg-tags" id="ftg-tags" data-id="<?php echo esc_attr( $gallery_id ); ?>"
				 data-taxonomy="ftg-tags">
				<div class="jaxtag">

					<div class="ajaxtag hide-if-no-js">
						<label class="screen-reader-text"
							   for="new-tag-ftg-tags"><?php esc_html_e( 'Add New Tags', 'feed_them_social' ); ?></label>
						<p><input data-wp-taxonomy="ftg-tags" type="text" id="new-tag-ftg-tags" name="newtag[ftg-tags]"
								  class="tax-input-ftg-tags newtag form-input-tip" size="16" autocomplete="off"
								  aria-describedby="new-tag-ftg-tags-desc" value=""/>
							<button class="button save-media-term" data-taxonomy="ftg-tags" data-id="<?php echo esc_attr( $gallery_id ); ?>"><?php esc_html_e( 'Add ', 'feed_them_social' ); ?></button>
						</p>
					</div>
					<p class="howto" id="new-tag-ftg-tags-desc"><?php esc_html_e( 'Separate tags with commas', 'feed_them_social' ); ?></p>

				</div>
				<p class="ftg-tags-none"><?php esc_html_e( 'No Tags found for this Image.', 'feed_them_social' ); ?></p>
				<ul class="tagchecklist" role="list"></ul>
			</div>

			<div class="fts-gallery-tags-edit-wrap"></div>
			<?php } ?>

			<div class="ft-submit-wrap"><a class="ft-gallery-edit-img-ajax button button-primary button-large"
										   id="ft-gallery-edit-img-ajax" href="javascript:;"
										   data-nonce="<?php echo esc_attr( wp_create_nonce( 'fts_edit_image_nonce' ) ); ?>"> <?php esc_html_e( 'Save', 'feed_them_social' ); ?> </a>
			</div>
		</div>
		<div class="clear"></div>

		<?php

	}

	/**
	 *  Metabox Tabs List
	 *
	 * The list of tabs Items for settings page metaboxes
	 *
	 * @return array
	 * @since 1.1.6
	 */
	public function fts_metabox_tabs_list() {

		$metabox_tabs_list = array(
			// Base of each tab! The array keys are the base name and the array value is a list of tab keys.
			'base_tabs' => array(
				'post' => array( 'images', 'layout', 'colors', 'zips', 'woocommerce', 'watermark', 'pagination', 'tags' ),
			),
			// Tabs List! The cont_func item is relative the the Function name for that tabs content. The array Keys for each tab are also relative to classes and ID on wraps of display_metabox_content function.
			'tabs_list' => array(
				// Images Tab!
				'images'      => array(
					'menu_li_class'      => 'tab1',
					'menu_a_text'        => esc_html__( 'Images', 'feed_them_social' ),
					'menu_a_class'       => 'account-tab-highlight',
					'menu_aria_expanded' => 'true',
					'cont_wrap_id'       => 'ftg-tab-content1',
					'cont_func'          => 'tab_upload_content',
				),
				// Layout Tab!
				'layout'      => array(
					'menu_li_class' => 'tab2',
					'menu_a_text'   => esc_html__( 'Layout', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content2',
					'cont_func'     => 'tab_layout_content',
				),
				// Colors Tab!
				'colors'      => array(
					'menu_li_class' => 'tab3',
					'menu_a_text'   => esc_html__( 'Colors', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content3',
					'cont_func'     => 'tab_colors_content',
				),
				// Zips Tab!
				'zips'        => array(
					'menu_li_class' => 'tab4',
					'menu_a_text'   => esc_html__( 'Zips', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content6',
					'cont_func'     => 'tab_zips_content',
				),
				// WooCommerce Tab!
				'woocommerce' => array(
					'menu_li_class' => 'tab5',
					'menu_a_text'   => esc_html__( 'WooCommerce', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content5',
					'cont_func'     => 'tab_woocommerce_content',
				),
				// Watermark Tab!
				'watermark'   => array(
					'menu_li_class' => 'tab6',
					'menu_a_text'   => esc_html__( 'Watermark', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content7',
					'cont_func'     => 'tab_watermark_content',
				),
				// Pagination Tab!
				'pagination'  => array(
					'menu_li_class' => 'tab7',
					'menu_a_text'   => esc_html__( 'Pagination', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content8',
					'cont_func'     => 'tab_pagination_content',
				),
				// Tags Tab!
				'tags'        => array(
					'menu_li_class' => 'tab8',
					'menu_a_text'   => esc_html__( 'Tags', 'feed_them_social' ),
					'cont_wrap_id'  => 'ftg-tab-content9',
					'cont_func'     => 'tab_tags_content',
				),
			),
		);

		return $metabox_tabs_list;
	}

	/**
	 *  Tab Menu Metabox
	 *
	 * Creates the Tabs Menu Metabox
	 *
	 * @param $object
	 * @since 1.0.0
	 */
	public function fts_tab_menu_metabox( $object ) {

		// Popup HTML.
		$this->fts_edit_page_popup( $this->parent_post_id );

		$params['object'] = $object;

		$this->metabox_settings_class->display_metabox_content( $this->fts_metabox_tabs_list(), $params );

		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			?>
				<script>
			jQuery('#ftg_sorting_options, #ftg_free_download_size').attr('disabled', 'disabled');
			jQuery('#ftg_sorting_options option[value="no"], #ftg_free_download_size option:first').text('Premium Required');
			jQuery('.ftg-pagination-notice-colored').remove();
			</script>
			<?php } ?>


		<div class="clear"></div>

		<?php
	}

	/**
	 * Tab Upload Content
	 *
	 * Outputs Upload tab's content for metabox.
	 *
	 * @param $params
	 * @since 1.1.6
	 */
	public function tab_upload_content( $params ) {

		global $wp_version;

		// Set WordPress version.
		$wp_version = substr( str_replace( '.', '', $wp_version ), 0, 2 );

		$object        = $params['object'];
		$gallery_class = $params['this'];

		/*
		$Settings_options = get_post_meta( $_GET['post'], 'fts_settings_options', true );

		echo '<pre>';
		print_r( $Settings_options );
		echo '</pre>';*/

		?>
			<div class="ftg-section">

				<div id="uploadContainer" style="margin-top: 10px;">

					<!-- Current image -->
					<div id="current-uploaded-image"
						 class="<?php echo has_post_thumbnail() ? 'open' : 'closed'; ?>">
					<?php
					if ( has_post_thumbnail() ) :
						?>
						<?php the_post_thumbnail( 'fts_thumb' ); ?><?php else : ?>
							<img class="attachment-full" src=""/>
						<?php endif; ?>
					</div>
						<?php $thumbnail_id = get_post_thumbnail_id( $gallery_class->parent_post_id ); ?>
						<?php $ajax_nonce = wp_create_nonce( "set_post_thumbnail-$gallery_class->parent_post_id" ); ?>
						<?php
						// adjust values here.
						$id       = 'img1'; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1" then $_POST[“img1"] will have all the image urls.
						$svalue   = ''; // this will be initial value of the above form field. Image urls.
						$multiple = true; // allow multiple files upload.
						$width    = null; // If you want to automatically resize all uploaded images then provide width here (in pixels).
						$height   = null; // If you want to automatically resize all uploaded images then provide height here (in pixels).

						if ( ! isset( $_GET['post'] ) ) {
							global $post;
							// Getting the next post id by seeing if an autodraft has been made in our custom post type.
							$create_next_args  = array(
								'post_type'           => 'fts',
								'posts_per_page'      => 1,
								'post_status'         => 'auto-draft',
								'ignore_sticky_posts' => 1,
								'orderby'             => 'date',
								'order'               => 'DSC',
							);
							$create_next_query = new \WP_Query( $create_next_args );

							if ( $create_next_query->have_posts() ) :
								while ( $create_next_query->have_posts() ) :
									$create_next_query->the_post();
									$edit_link_url = $post->ID;
								endwhile;
							endif;

							// for testing
							// echo $edit_link_url;
							$gallery_class->parent_post_id = $edit_link_url;
						}

						// check to see if the auto create option has been checked on the woocommerce tab and if so we add a class to the uploaderSection wrapper.
						if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && isset( $_GET['post'] ) ) {

							$display_gallery = new Display_Gallery();
							$option          = $display_gallery->fts_get_option_or_get_postmeta( $_GET['post'] );

							$ftg_auto_create_on_upload = isset( $option['fts_auto_image_woo_prod'] ) && 'true' === $option['fts_auto_image_woo_prod'] ? ' class="ftg-auto-create-product-on-upload"' : '';

						}
							$final_check_ftg_auto_create_on_upload = isset( $ftg_auto_create_on_upload ) ? $ftg_auto_create_on_upload : '';
						?>
					<!-- Uploader section -->
					<div id="uploaderSection"<?php echo $final_check_ftg_auto_create_on_upload; ?>>
						<div id="plupload-upload-ui" class="hide-if-no-js drag-drop">
							<div id="drag-drop-area">
								<div class="drag-drop-inside">
									<p class="drag-drop-info"><?php esc_attr_e( 'Drop images here' ); ?></p>
									<p><?php echo esc_html__( 'or', 'feed_them_social' ); ?></p>
									<div class="drag-drop-buttons">
										<input id="<?php echo esc_attr( $id ); ?>plupload-browse-button"
											   type="button"
											   value="<?php esc_attr_e( 'Select Images', 'feed_them_social' ); ?>"
											   class="button"/>

									</div>
									<div class="drag-drop-buttons">
									<?php if ( $wp_version >= 35 ) : ?>
											<!--<a href="#" id="dgd_library_button" class="button insert-media add_media" data-editor="content" title="Add Media">-->
											<a href="javascript:;" id="dgd_library_button"
											   class="button" title="Add Media">
												<span class="wp-media-buttons-icon"></span><?php esc_attr_e( 'Media Library', 'feed_them_social' ); ?>
											</a>
											<?php
											else :
												$browse_library_btn = bloginfo( 'wpurl' ) . '/wp-admin/media-upload.php?post_id=' . $gallery_class->parent_post_id . '&amp;tab=library&amp;=&amp;post_mime_type=image&amp;TB_iframe=1&amp;width=640&amp;height=353';
												?>
											<a href="<?php echo esc_url( $browse_library_btn ); ?>"
											   class="thickbox add_media button-secondary"
											   id="content-browse_library" title="Browse Media Library"
											   onclick="return false;">
												<?php esc_attr_e( 'Media Library', 'feed_them_social' ); ?>
											</a>
											<?php endif; ?>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="upload-max-size">
							<?php
							$bytes = wp_max_upload_size();
							echo esc_html__( 'Maximum upload file size', 'feed_them_social' ) . ': ' . $gallery_class->fts_format_bytes( $bytes, $precision = 2 ) . ' MB.';
							?>
					</div>

						<?php
						$display_gallery = new Display_Gallery();
						$option          = $display_gallery->fts_get_option_or_get_postmeta( $object->ID );

						$post_count  = '50';
						$orderby_set = isset( $option['ftg_sort_type'] ) ? $option['ftg_sort_type'] : 'menu_order';
						$orderby     = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : $orderby_set;
						if ( isset( $_GET['orderby'] ) && 'menu_order' === $_GET['orderby'] || 'menu_order' === $orderby_set || 'title' === $orderby_set || isset( $_GET['orderby'] ) && 'title' === $_GET['orderby'] ) {
							$order = 'asc';
						} else {
							$order = 'desc';
						}

						// JUST NEED TO CLEAN ALL THIS UP!!!!!!
						?>
					<script>
						function reloadPage(id) {
							window.location = '<?php echo esc_url_raw( $_SERVER['REQUEST_URI'] ); ?>&images=' + id.value;
						}</script>
					<?php
					$post_count = isset( $_GET['images'] ) ? sanitize_text_field( $_GET['images'] ) : $post_count;
					$pagenum    = isset( $_GET['pagenum'] ) ? sanitize_text_field( $_GET['pagenum'] ) : '1';
					$paged      = $pagenum;
					// $image_list = $display_gallery->fts_get_media_rest($gallery_class->parent_post_id, '100');
					$args       = array(
						'post_parent'    => $object->ID,
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'posts_per_page' => $post_count,
						'orderby'        => $orderby,
						'order'          => $order,
						'exclude'        => 0, // Exclude featured thumbnail
						'paged'          => $paged,
					);
					$image_list = get_posts( $args );

					if ( is_array( $image_list ) && true == $object->ID && isset( $image_list[0] ) ) {
						?>
						<div class="ftg-number-of-images-wrap"><?php echo esc_html( $gallery_class->fts_count_post_images( $object->ID ) ); ?><?php esc_html_e( ' Images', 'feed_them_social' ); ?></div>
					<?php } ?>

					<input type="hidden" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>"
						   value="<?php echo esc_html( $svalue ); ?>"/>

					<div class="plupload-upload-uic hide-if-no-js
							<?php
							if ( $multiple ) :
								?>
									plupload-upload-uic-multiple<?php endif; ?>"
						 id="<?php echo $id; ?>plupload-upload-ui">
									<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo esc_html( wp_create_nonce( $id . 'pluploadan' ) ); ?>"></span>
							<?php if ( $width && $height ) : ?>
							<span class="plupload-resize"></span>
							<span class="plupload-width"
								  id="plupload-width<?php echo esc_attr( $width ); ?>"></span>
							<span class="plupload-height"
								  id="plupload-height<?php echo esc_attr( $height ); ?>"></span>
						<?php endif; ?>
						<div class="filelist"></div>
					</div>
				</div>

					<?php

					$total_pagination_count = $gallery_class->fts_count_post_images( $object->ID );
					if ( $post_count < $total_pagination_count ) {
						?>
					<div class="ftg-pagination-header">
						<?php
						$orderby_ten          = isset( $_GET['images'] ) && '50' === $_GET['images'] ? ' selected="selected"' : '';
						$orderby_hundred      = isset( $_GET['images'] ) && '100' === $_GET['images'] ? ' selected="selected"' : '';
						$orderby_five_hundred = isset( $_GET['images'] ) && '500' === $_GET['images'] ? ' selected="selected"' : '';
						$orderby_all          = isset( $_GET['images'] ) && '-1' === $_GET['images'] ? ' selected="selected"' : '';
						?>
						<div class="ftg-images-amount-wrap">
							<select name="images" class="ftg-images-amount" onchange="javascript:reloadPage(this)">
								<option value="50" <?php echo esc_html( $orderby_ten ); ?>><?php echo esc_html__( '50 Images', 'feed_them_social' ); ?></option>
								<option value="100"<?php echo esc_html( $orderby_hundred ); ?>><?php echo esc_html__( '100 Images', 'feed_them_social' ); ?></option>
								<option value="500"<?php echo esc_html( $orderby_five_hundred ); ?>><?php echo esc_html__( '500 Images', 'feed_them_social' ); ?></option>
								<option value="-1"<?php echo esc_html( $orderby_all ); ?>><?php echo esc_html__( 'All Images (This may load slow if you have more than 500 images)', 'feed_them_social' ); ?></option>
							</select>
						</div>

						<?php

						if ( isset( $pagenum ) && '1' !== $pagenum ) {
							$count_per_page = min( $total_pagination_count, $post_count * $pagenum );
							$per_page_final = $count_per_page - $post_count;
						} else {
							$per_page_final = '1';
							$count_per_page = $post_count;
						}

						if ( isset( $_GET['images'] ) && '-1' !== $_GET['images'] ) {
							// Here we get_option the amount of users we want to see per page based on the saved settings field.
							?>
						<div class="ftg-total-pagination-count"><?php echo esc_html__( 'Showing', 'feed_them_social' ); ?> <?php echo esc_html( $per_page_final ); ?>-<?php echo esc_html( $count_per_page ); ?> of <?php echo esc_html( $total_pagination_count ); ?> <?php echo esc_html( 'Images', 'feed_them_social' ); ?></div>
							<?php
						}
						?>

						<div class="ftg-pagination">
							<?php
							echo paginate_links(
								array(
									'base'      => add_query_arg( 'pagenum', '%#%' ),
									'format'    => '?pagenum=%#%',
									'current'   => max( 1, esc_html( $pagenum ) ),
									'mid_size'  => 3,
									'end_size'  => 3,
									'prev_text' => esc_html( '&#10094;' ),
									'next_text' => esc_html( '&#10095;' ),
									'total'     => ceil( esc_html( $total_pagination_count ) / esc_html( $post_count ) ), // 3 items per page
								)
							);
							?>
						</div>
						<div class="ftg-clear"></div>

						  </div><div class="ftg-clear"></div>

							<?php
					}

						// Happens in JS file
						$this->core_functions_class->fts_tab_notice_html();
					?>

				<script>
					jQuery('.metabox_submit').click(function (e) {
						e.preventDefault();
						//  jQuery('#publish').click();
						jQuery('#post').click();
					});

					jQuery(document).ready(function () {
						jQuery('.gallery-edit-button-question-one').click(function () {
							jQuery('.gallery-edit-question-download-gallery').toggle();
							jQuery('.gallery-edit-question-digital-gallery-product, .gallery-edit-question-individual-image-product').hide();
						});

						jQuery('.gallery-edit-button-question-two').click(function () {
							jQuery('.gallery-edit-question-digital-gallery-product').toggle();
							jQuery('.gallery-edit-question-download-gallery, .gallery-edit-question-individual-image-product').hide();
						});

						jQuery('.gallery-edit-button-question-three').click(function () {
							jQuery('.gallery-edit-question-individual-image-product').toggle();
							jQuery('.gallery-edit-question-download-gallery, .gallery-edit-question-digital-gallery-product').hide();
						});
					});

				</script>

					<?php
					// The size of the image in the popup
					$image_size_name = get_post_meta( $object->ID, 'fts_images_sizes_popup', true );

					// $images_count = count( $attachments );
					?>
				<input type="submit" class="metabox_submit" value="Submit" style="display: none;"/>

					<?php
					// don't show these buttons until the page has been published with some photos in it
					if ( isset( $image_list[0] ) ) {
						?>
					<div class="ft-gallery-options-buttons-wrap">
						<?php if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) { ?>
							<div class="gallery-edit-button-wrap">
								<button type="button" class="button"
										id="fts-gallery-checkAll"><?php esc_html_e( 'Select All', 'feed_them_social' ); ?></button>
							</div>
						<?php } ?>
						<div class="gallery-edit-button-wrap">
							<button
								<?php
								if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
									esc_html_e( 'disabled', 'feed_them_social' );
								}
								?>
									type="button"
									class="ft-gallery-download-gallery fts_download_button_icon button button-primary button-larg"
									onclick="fts_create_zip('<?php echo $gallery_class->parent_post_id; ?>', 'yes','false')"><?php esc_html_e( 'Zip Gallery & Download', 'feed_them_social' ); ?></button>
							<a class="gallery-edit-button-question-one" href="javascript:;"
							   rel="gallery-edit-question-download-gallery">?</a>
						</div>
							<?php
							// if (is_plugin_active('woocommerce/woocommerce.php')) {
							// Selected Image Product
							$selected_zip_product = get_post_meta( $gallery_class->parent_post_id, 'fts_zip_to_woo_model_prod', true );
							?>
						<div class="gallery-edit-button-wrap">
							<button disabled type="button"
									class="ft-gallery-zip-gallery fts_download_button_icon button button-primary button-larg"
									onclick="fts_create_zip('<?php esc_html_e( $object->ID ); ?>', 'no', 'yes', 'no')"><?php esc_html_e( 'Create Digital Gallery Product', 'feed_them_social' ); ?></button>
							<a class="gallery-edit-button-question-two" href="javascript:;"
							   rel="gallery-edit-question-digital-gallery-product">?</a>
						</div>
						<div class="gallery-edit-button-wrap">
							<button type="button" disabled="disabled"
									class="ft-gallery-create-woo fts_download_button_icon button button-primary button-larg"
									onclick="fts_image_to_woo('<?php esc_html_e( $gallery_class->parent_post_id ); ?>')"><?php esc_html_e( 'Create individual Image Product(s)', 'feed_them_social' ); ?></button>
							<a class="gallery-edit-button-question-three" href="javascript:;"
							   rel="gallery-edit-question-individual-image-product">?</a>
						</div>
						<?php // } ?>
					</div>
					<div class="clear"></div>
					<?php } ?>
				<div class="gallery-edit-question-message gallery-edit-question-download-gallery"
					 style="display: none;">
					<h3><?php esc_html_e( 'Zip Gallery and Download', 'feed_them_social' ); ?></h3>
						<?php
						echo sprintf(
							esc_html__( 'This button will create a zip of all the full size images in this gallery on the %1$sZIPs tab%2$s and then download a zip onto your computer. If you would like to just download a ZIP you have already made and NOT create a new ZIP of the gallery you may do so from the %1$sZIPs tab%2$s.', 'feed_them_social' ),
							'<a href="#zips" class="ftg-zips-tab" >',
							'</a>'
						);

						if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
							$gallery_class->fts_tab_premium_msg();
						}
						?>
				</div>
					<?php
					// if (is_plugin_active('woocommerce/woocommerce.php')) {
					?>
				<div class="gallery-edit-question-message gallery-edit-question-digital-gallery-product"
					 style="display: none;">
					<h3><?php _e( 'Create Digital Gallery Zip and Turn into a Product' ); ?></h3>
					<?php
					echo sprintf(
						esc_html__( 'This button will create a zip on the %1$sZIPs tab%2$s of all the full size images in this gallery and then create a WooCommerce Product out of that ZIP. You must have a "ZIP Model Product" selected on the %3$sWoocommerce tab%4$s for this to work.', 'feed_them_social' ),
						'<a href="#zips" class="ftg-zips-tab" >',
						'</a>',
						'<a href="#woocommerce" class="ftg-woo-tab" >',
						'</a>'
					);
					if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
						$gallery_class->fts_tab_premium_msg();
					}
					?>
				</div>
				<div class="gallery-edit-question-message gallery-edit-question-individual-image-product"
					 style="display: none;">
					<h3><?php esc_html_e( 'Create Products from Individual Images' ); ?></h3>
					<?php
					echo sprintf(
						esc_html__( 'This button will create a WooCommerce Product for each of the images selected below. 1 image creates 1 WooCommerce product. You must have either the "Global Model Product" or "Smart Image Orientation Model Product" selected on the %1$sWoocommerce tab%2$s for this to work. You must click the Select All button or click any of the images in your gallery below before you click the Create Individual Image Products(s) button.', 'feed_them_social' ),
						'<a href="#woocommerce" class="ftg-woo-tab" >',
						'</a>'
					);
					if ( ! is_plugin_active( 'feed-them-social-premium/feed_them_social-premium.php' ) ) {
						$gallery_class->fts_tab_premium_msg();
					}
					?>
				</div>
					<?php
					// }
					?>
				<div class="clear"></div>

				<ul class="plupload-thumbs
							<?php
							if ( $multiple ) :
								?>
								plupload-thumbs-multiple<?php endif; ?>" id="<?php echo esc_attr( $id ); ?>plupload-thumbs"
					data-post-id="<?php echo esc_attr( $object->ID ); ?>">
						<?php
						$show_title = get_post_meta( $object->ID, 'fts_show_title', true );
						?>
						<?php

						// Display Images Gallery
						$size = 'thumbnail';

						$attr = array(
							'class' => "attachment-$size wp-post-image",
						);
						// && isset($image_list[0])
						if ( is_array( $image_list ) && isset( $image_list[0] ) ) {

							$image_output = '';

							foreach ( $image_list as $key => $image ) {
								$times = $image->post_date;
								$image = wp_prepare_attachment_for_js( $image->ID );

								$fts_final_date = $display_gallery->fts_custom_date( $times, 'wp_gallery' );
								$instagram_date = $fts_final_date;

								// The size of the image in the popup
								$image_size_name = get_post_meta( $object->ID, 'fts_images_sizes_popup', true );
								// this is the image size in written format,ie* thumbnail, medium, large etc.
								$item_popup       = explode( ' ', $image_size_name );
								$item_final_popup = wp_get_attachment_image_src( $attachment_id = $image['id'], $item_popup[0], false );

								$image_source_large        = wp_get_attachment_image_src( $attachment_id = $image['id'], 'large', false );
								$image_source_medium_large = wp_get_attachment_image_src( $attachment_id = $image['id'], 'medium_large', false );
								$image_source_medium       = wp_get_attachment_image_src( $attachment_id = $image['id'], 'medium', false );
								$image_source_thumb        = wp_get_attachment_image_src( $attachment_id = $image['id'], 'thumbnail', false );

								if ( isset( $image_size_name ) && 'Choose an option' !== $image_size_name ) {
									$image_source_popup = $item_final_popup[0];
								} elseif ( isset( $image_source_large ) ) {
									$image_source_popup = $image_source_large[0];
								} elseif ( isset( $image_source_medium_large ) ) {
									$image_source_popup = $image_source_medium_large[0];
								} elseif ( isset( $image_source_medium ) ) {
									$image_source_popup = $image_source_medium[0];
								} else {
									$image_source_popup = $image_source_thumb[0];
								}

								$next_img = isset( $image_list[ $key + 1 ] ) ? $image_list[ $key + 1 ] : $image_list[0];
								$prev_img = isset( $image_list[ $key - 1 ] ) ? $image_list[ $key - 1 ] : $image_list[ count( $image_list ) - 1 ];

								if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {

									// Check custom post meta for woo product field
									$image_post_meta = get_post_meta( $image['id'], 'fts_woo_prod', true );

									$gallery_to_woo = new Gallery_to_Woocommerce();

									// If Image already has product meta check the product still exists
									if ( true == $image_post_meta ) {
										$product_exist = $gallery_to_woo->fts_create_woo_prod_exists_check( $image_post_meta );
										if ( $product_exist ) {
											$product_created_already = ' ftg-product-exists';
										} // add empty div so we don't get a undefined in popup
										else {
											$product_created_already = ' ftg-no-product';
										}
									} else {
										// we add this additional else because the === true statement is only for images that may have had products but have been deleted...
										// so at some point the fts_woo_prod had been set, so we run this extra check which is crucial to the create new product button
										$product_created_already = ' ftg-no-product';
									}
								}

								$product_created_already = isset( $product_created_already ) ? $product_created_already : '';

								?>
							 <li class="thumb echo <?php echo esc_attr( $product_created_already ); ?>" id="list_item_<?php echo esc_attr( $image['id'] ); ?>" data-image-id="<?php echo esc_attr( $image['id'] ); ?>" data-menu-order="<?php echo esc_attr( $image['menuOrder'] ); ?>">
								<?php

								// used for testing purposes to style or fix loader stuff
								// $meta_box .= '<div class="ftg-loading-overlay"><div class="ftg-loading-overlay-loader"></div></div>';
								// Zip to WooCommerce
								if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {

									// $gallery_class->fts_get_attachment_info($object->ID);
									?>
							 <div class="ft-gallery-woo-btns-wrap-for-popup">
									<?php
									// If Image already has product meta check the product still exists
									if ( true == $image_post_meta && $product_exist ) {
										?>
										<div class="ft-gallery-file-delete ft-gallery-file-zip-to-woo"><a class="fts_create_woo_prod_button" target="_blank" href="<?php echo esc_url( get_edit_post_link( $image_post_meta ) ); ?>"><?php echo esc_html__( 'Edit product', 'feed_them_social' ); ?></a></div>
											<?php
									}

									?>
							</div>
									<?php

									// Add In Later Version
									/*
									 else{
									echo '<div class="ft-gallery-file-delete ft-gallery-file-zip-to-woo"><a class="fts_create_woo_prod_button" onclick="fts_image_to_woo(\'zip\',\'' . $zip_name . '\',\'' . $abs_file_url . '\')">Create product</a></div>';
									}*/
								}

								if ( isset( $image['sizes']['fts_thumb'] ) ) {
									$image_url = wp_get_attachment_image_src( $attachment_id = $image['id'], 'fts_thumb', false );
								} else {
									$image_url = wp_get_attachment_image_src( $attachment_id = $image['id'], 'thumbnail', false );
								}

								$ft_custom_thumb = $image_url[0];
								// $meta_box .= '<a href="' . $image['media_details']['sizes']['full']['source_url'] . '" rel="gallery-' . $image['id'] . '" class="ft-gallery-edit-img-popup">';
								?>
								 <img src="<?php echo esc_url( $ft_custom_thumb ); ?>"/>

								 <div class="ft-gallery-edit-thumb-btn"><button type="button" title="Edit this image." class="ft-gallery-edit-img-popup" data-id="<?php echo esc_attr( $image['id'] ); ?>" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_edit_image_nonce' ) ); ?>" data-imageurl="<?php echo esc_url( $image_source_popup ); ?>"></button></div>

								 <?php

									// $meta_box .= '</a>';
									if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
										?>
								<div class="ft-gallery-select-thumbn"><label class="ft-gallery-myCheckbox"><input type="checkbox" class=“ft-gallery-img-checkbox” rel="<?php echo esc_attr( $image['id'] ); ?>" name="image-<?php echo esc_attr( $image['id'] ); ?>" id="image-<?php echo esc_attr( $image['id'] ); ?>"/><span></span></label></div>
										<?php
									}
									?>
								 <div class="ft-gallery-remove-thumb-btn"><a title="Remove Image from this Gallery" class="ft-gallery-remove-img-ajax" data-ft-gallery-img-remove="true" data-id="<?php echo esc_attr( $image['id'] ); ?>" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_update_image_nonce' ) ); ?>" href="javascript:;"></a></div>
								 <div class="ft-gallery-delete-thumb-btn"><a title="Delete Image Completely" class="ft-gallery-force-delete-img-ajax" data-id="<?php echo esc_attr( $image['id'] ); ?>" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_delete_image_nonce' ) ); ?>" href="javascript:;"></a></div> <div class="clear"></div>
									 <?php

										if ( ! empty( $image_post_meta ) && is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {

											// If Image already has product meta check the product still exists
											$product_exist = $gallery_to_woo->fts_create_woo_prod_exists_check( $image_post_meta );
											if ( $product_exist ) {
												?>
												<div class="ft-gallery-woo-edit-thumb-btn"><a class="fts_create_woo_prod_button" target="_blank" href="<?php echo esc_url( get_edit_post_link( $image_post_meta ) ); ?>"></a></div>
												<?php
											} // add empty div so we don't get a undefined in popup

										}

										?>
								 <div class="ft-image-id-for-popup"><p><strong><?php echo esc_html__( 'Uploaded:', 'feed_them_social' ); ?></strong> <?php echo esc_html( $instagram_date ); ?></p><br/><input value="<?php echo esc_attr( $image['id'] ); ?>" class="fts-gallery-id" type="text" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_edit_image_nonce' ) ); ?>"  /><input value="<?php echo esc_html( $next_img->ID ); ?>" class="fts-gallery-id fts-next-image" type="text" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_edit_image_nonce' ) ); ?>"  /><input value="<?php echo esc_html( $prev_img->ID ); ?>" class="fts-gallery-id fts-prev-image" type="text" data-nonce="<?php echo esc_html( wp_create_nonce( 'fts_edit_image_nonce' ) ); ?>"  /></div>
							 </li>
									 <?php
							}
						}
						?>
				</ul>
				<div class="clear"></div>
					<?php
					if ( ! isset( $image_list[0] ) ) {
						?>
					<style type="text/css">
						.slickdocit-videowrapper {
							max-width: 100%;
							display: none;
							margin-bottom: 15px;
						}

						.slickdocit-fluidMedia {
							position: relative;
							padding-bottom: 53.5%; /* proportion value to aspect ratio 16:9 (9 / 16 = 0.5625 or 56.25%) */
							padding-top: 30px;
							height: 0;
							overflow: hidden;
						}

						#slickdocit-show-video, #slickdocit-hide-video {
							background: #FFFF;
							display: inline-block;
							border-radius: 50px;
							padding: 10px 15px 10px 16px;
							margin-bottom: 15px;
							cursor: pointer;
							font-size: 13px;
							float: right;
						}

						#slickdocit-show-video:hover, #slickdocit-hide-video {
							opacity: .8;
						}

						#slickdocit-hide-video {
							display: none;
						}

						.slickdocit-fluidMedia iframe {
							position: absolute;
							top: 0;
							left: 0;
							width: 100%;
							height: 100%;
						}

						.slickdocit-play:before {
							font-family: FontAwesomeSlick;
							content: "\f04b ";
							display: inline-block !important;
							margin-left: 7px !important;
						}
					</style>
					<div class="gallery-edit-question-message gallery-edit-question-download-gallery gallery-quick-guide-getting-started">
						<div class="slickdocit-videowrapper">
							<div class="slickdocit-fluidMedia">
								<iframe id="slickdocit-iframe"
										src="https://www.youtube.com/embed/Fa2mjmFAGZQ?rel=0"
										data-autoplay-src="https://www.youtube.com/embed/Fa2mjmFAGZQ?rel=0&autoplay=1"
										frameborder="0" allowscriptaccess="always"
										allowfullscreen=""></iframe>
							</div>
						</div>
						<div id="slickdocit-show-video"
							 class="slickdocit-show-video"><?php esc_html_e( 'View Quick Setup Video', 'feed_them_social' ); ?>
							<span class="slickdocit-play"></span></div>
						<div id="slickdocit-hide-video"
							 class="ftg-close-vid"><?php esc_html_e( 'Close Video', 'feed_them_social' ); ?>
							<span class="slickdocit-play"></div>
						<script>
							jQuery(".slickdocit-show-video").click(function () {
								var videoURL = jQuery("#slickdocit-iframe");
								videoURL.attr("src", videoURL.data("autoplay-src"));
								jQuery(".slickdocit-videowrapper").slideDown();
								jQuery('.slickdocit-show-video').hide();
								jQuery('.ftg-close-vid').show();
							});
							jQuery(".ftg-close-vid").click(function () {
								var videoURL = jQuery("#slickdocit-iframe");
								jQuery(".slickdocit-videowrapper").slideUp();
								jQuery('.ftg-close-vid').hide();
								//Then assign the src to null, this then stops the video been playing
								jQuery('.slickdocit-show-video').show();
								videoURL.attr("src", '');
							});
						</script>
						<h3><?php esc_html_e( 'Quick Guide to Getting Started', 'feed_them_social' ); ?></h3>
						<p>
							<?php
							echo sprintf(
								esc_html__( 'Please look over the options on the %1$sSettings%2$s page before creating your first gallery.%3$s1. Enter a title for your gallery at the top of the page in the "Enter title here" input. %4$s2. Add images to the gallery and sort them in the order you want. %4$s3. Publish the gallery by clicking the blue "Publish" button. %4$s4. Now you can edit your images title, description and more. %5$sView our %6$sImage Gallery Demos%7$s or %8$sFull documentation%9$s for more details.', 'feed_them_social' ),
								'<a href="' . esc_url( 'edit.php?post_type=fts&page=ft-gallery-settings-page' ) . '"  target="_blank">',
								'</a>',
								'<p/><p>',
								'<br/>',
								'</p>',
								'<a href="' . esc_url( 'https://feedthemgallery.com/gallery-demo-one/' ) . '" target="_blank">',
								'</a>',
								'<a href="' . esc_url( 'https://www.slickremix.com/feed-them-social/' ) . '" target="_blank">',
								'</a>'
							);
							?>
					</div>
						<?php
					}

					$plupload_init = array(
						'runtimes'            => 'html5,silverlight,flash,html4',
						'browse_button'       => 'plupload-browse-button', // will be adjusted per uploader
						'container'           => 'plupload-upload-ui', // will be adjusted per uploader
						'drop_element'        => 'drag-drop-area', // will be adjusted per uploader
						'file_data_name'      => 'async-upload', // will be adjusted per uploader
						'multiple_queues'     => true,
						'max_file_size'       => wp_max_upload_size() . 'b',
						'url'                 => admin_url( 'admin-ajax.php' ),
						'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
						'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
						'filters'             => array(
							array(
								'title'      => esc_html__( 'Allowed Files', 'feed_them_social' ),
								'extensions' => '*',
							),
						),
						'multipart'           => true,
						'urlstream_upload'    => true,
						'multi_selection'     => false, // will be added per uploader
					// additional post data to send to our ajax hook
						'multipart_params'    => array(
							'_ajax_nonce' => '', // will be added per uploader
							'action'      => 'plupload_action', // the ajax action name
							'postID'      => $gallery_class->parent_post_id,
							'imgid'       => 0, // will be added per uploader
						),
					);
		?>
			<script type="text/javascript">
				var base_plupload_config =<?php echo json_encode( $plupload_init ); ?>;
			</script>

			<div class="clear"></div>
		</div>
					<?php
	}

				/**
				 *  Tab Layout Content
				 *
				 * Outputs Layout tab's content for metabox.
				 *
				 * @since 1.0.0
				 */
	public function tab_layout_content( $params ) {
		$gallery_class = $params['this'];

		 echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['layout'], null, $gallery_class->parent_post_id );
		?>
			<div class="clear"></div>
			<div class="ft-gallery-note ft-gallery-note-footer">
				<?php
				echo sprintf(
					esc_html__( 'Additional Global options available on the %1$sSettings Page%2$s', 'feed_them_social' ),
					'<a href="' . esc_url( 'edit.php?post_type=fts&page=ft-gallery-settings-page' ) . '" >',
					'</a>'
				);
				?>
			</div>
					<?php
	}

	/**
	 * Tab Colors Content
	 *
	 * Outputs Colors tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_colors_content( $params ) {

		$gallery_class = $params['this'];

		echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['colors'], null, $gallery_class->parent_post_id );
		?>
		<div class="clear"></div>

		<div class="ft-gallery-note ft-gallery-note-footer">
			<?php
			echo sprintf(
				esc_html__( 'Additional Global options available on the %1$sSettings Page%2$s', 'feed_them_social' ),
				'<a href="' . esc_url( 'edit.php?post_type=fts&page=ft-gallery-settings-page' ) . '" >',
				'</a>'
			);
			?>
		</div>
					<?php
	}

	/**
	 * Tab ZIPS Content
	 *
	 * Outputs ZIPS tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_zips_content( $params ) {
		$gallery_class = $params['this'];
				// If Premium add Functionality
		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			echo '<div class="ftg-section">' . $gallery_class->fts_tab_premium_msg() . '</div>';

		}
				// If Premium add Functionality
		if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			// Happens in JS file
			$this->core_functions_class->fts_tab_notice_html();
		}
		?>
				<div class="ftg-section">

					<h3><?php _e( 'Gallery Digital Zip History List', 'feed_them_social' ); ?></h3>
					<?php
					// If Premium add Functionality
					if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
						?>

						<ul id="ft-gallery-zip-list" class="ftg-free-list">
							<li class="ft-gallery-zip zip-list-item-24527">
								<div class="ft-gallery-file-name">
									<a href="javascript:;"
									   title="Download"><?php esc_html_e( 'Example-Gallery-Name' ); ?></a>
								</div>
								<div class="ft-gallery-file-time"><?php esc_html_e( 'October 14, 2020 - 2:45pm' ); ?></div>
								<div class="ft-gallery-file-delete">
									<a class="fts_delete_zip_button"><?php esc_html_e( 'Delete' ); ?></a>
								</div>
								<div class="ft-gallery-file-delete ft-gallery-file-zip-to-woo">
									<a class="fts_create_woo_prod_button"><?php esc_html_e( 'Create product' ); ?></a>
								</div>
								<div class="ft-gallery-file-view">
									<a class="fts_view_zip_button"><?php esc_html_e( 'View Contents' ); ?></a>
								</div>
								<ol class="zipcontents_list"></ol>
							</li>
						</ul>
						<?php
					}

					// If Premium add Functionality
					if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
						// Happens in JS file
						echo $gallery_class->zip_gallery_class->fts_list_zip_files( $gallery_class->parent_post_id );
					}
					?>

				</div>
				<div class="clear"></div>
					<?php
	}

	/**
	 * Tab Woocommerce Content
	 *
	 * Outputs WooCommerce tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_woocommerce_content( $params ) {
		$gallery_class = $params['this'];
		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			?>

					<div class="ftg-section">
				<?php $gallery_class->fts_tab_premium_msg(); ?>
					</div>
				<?php } ?>

					<?php
					// echo '<pre>';
					// print_r(wp_prepare_attachment_for_js('21529'));
					// echo '</pre>';
					echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['twitter'], null, $gallery_class->parent_post_id );
					?>

				<div class="tab-5-extra-options">

			</div>

            <?php
	}

	/**
	 * Tab Watermark Content
	 *
	 * Outputs Watermark tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_watermark_content( $params ) {
		$gallery_class = $params['this'];
		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			?>
					<div class="ftg-section">
			<?php $gallery_class->fts_tab_premium_msg(); ?>
					</div>
						<?php
		}
				echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['watermark'], null, $gallery_class->parent_post_id );
		?>
				<div class="clear"></div>

				<div class="ft-gallery-note ft-gallery-note-footer">
				<?php

				// echo '<pre>';
				// print_r( $gallery_class->metabox_settings_class->get_saved_settings_array( $gallery_class->parent_post_id ) );
				// echo '</pre>';
				echo sprintf(
					esc_html__( 'Please %1$screate a ticket%2$s if you are experiencing trouble and one of our team members will be happy to assist you.', 'feed_them_social' ),
					'<a href="' . esc_url( 'https://www.slickremix.com/my-account/#tab-support' ) . '" target="_blank">',
					'</a>'
				);
				?>
			</div>
		<?php if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) { ?>
			<script>
				jQuery('#ftg-tab-content7 input, #ftg-tab-content7 select').attr('disabled', 'disabled');
				jQuery('#ftg-tab-content7 input').val('Premium Required');
				jQuery('#ftg-tab-content7 select option').text('Premium Required');
			</script>
			<?php
}
	}

	/**
	 * Tab Pagination Content
	 *
	 * Outputs Watermark tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_pagination_content( $params ) {
		$gallery_class = $params['this'];
		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			?>
				<div class="ftg-section">
					<?php $gallery_class->fts_tab_premium_msg(); ?>
				</div>
						<?php
		}
				echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['pagination'], null, $gallery_class->parent_post_id );
		?>

			<div class="clear"></div>

			<div class="ft-gallery-note ft-gallery-note-footer">
				<?php
				echo sprintf(
					esc_html__( 'Please %1$screate a ticket%2$s if you are experiencing trouble and one of our team members will be happy to assist you.', 'feed_them_social' ),
					'<a href="' . esc_url( 'https://www.slickremix.com/my-account/#tab-support' ) . '" target="_blank">',
					'</a>'
				);
				?>
			</div>
		<?php if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) { ?>
			<script>
				jQuery('#ftg-tab-content8 input, #ftg-tab-content8 select').attr('disabled', 'disabled');
				jQuery('#ftg-tab-content8 input').val('Premium Required');
				jQuery('#ftg-tab-content8 select option').text('Premium Required');
			</script>
			<?php
}
	}

	/**
	 * Tab Tags Content
	 *
	 * Outputs Tags tab's content for metabox.
	 *
	 * @since 1.0.0
	 */
	public function tab_tags_content( $params ) {
		$gallery_class = $params['this'];
		if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			?>
				<div class="ftg-section">
					<?php $gallery_class->fts_tab_premium_msg(); ?>
				</div>
						<?php
		}
				echo $gallery_class->metabox_settings_class->settings_html_form( $gallery_class->saved_settings_array['tags'], null, $gallery_class->parent_post_id );
		?>

				<div class="clear"></div>

				<div class="ft-gallery-note ft-gallery-note-footer">
				<?php
				echo sprintf(
					esc_html__( 'Please %1$screate a ticket%2$s if you are experiencing trouble and one of our team members will be happy to assist you.', 'feed_them_social' ),
					'<a href="' . esc_url( 'https://www.slickremix.com/my-account/#tab-support' ) . '" target="_blank">',
					'</a>'
				);
				?>
			</div>
		<?php if ( ! is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) { ?>
			<script>
				jQuery('#ftg-tab-content9 input, #ftg-tab-content9 select').attr('disabled', 'disabled');
				jQuery('#ftg-tab-content9 input').val('Premium Required');
				jQuery('#ftg-tab-content9 select option').text('Premium Required');
			</script>
			<?php
}
	}

				/**
				 *  Uploader Action
				 *
				 * File upload handler. Inserts Attachments info. Generates attachment info. May auto-generate WooCommerce Products
				 *
				 * @since 1.0.0
				 */
	public function fts_plupload_action() {

		// check ajax noonce
		$imgid = $_POST['imgid'];

		check_ajax_referer( $imgid . 'pluploadan' );

		// Fetch post ID:
		$post_id = $_POST['postID'];
		// $file = $_FILES['async-upload'];
		// handle file upload
		$status = wp_handle_upload(
			$_FILES[ $imgid . 'async-upload' ],
			array(
				'gallery_form' => true,
				'action'       => 'plupload_action',
			)
		);

		// Insert uploaded file as attachment:
		$attach_id = wp_insert_attachment(
			array(
				'post_mime_type' => $status['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $status['url'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$status['file'],
			sanitize_text_field( $post_id )
		);

		// Include the image handler library:
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate meta data and update attachment:
		$attach_data = wp_generate_attachment_metadata( $attach_id, $status['file'] );

		wp_update_attachment_metadata( $attach_id, $attach_data );

		// Use File & Title renaming
		if ( '1' == get_option( 'ft-gallery-use-attachment-naming' ) ) {
			$file_name = preg_replace( '/\.[^.]+$/', '', basename( $status['url'] ) );
			$this->fts_rename_attachment( $post_id, $attach_id, $file_name );
			$this->fts_generate_new_attachment_name( $post_id, $attach_id, $file_name );
		} else {
			$this->fts_format_attachment_title( preg_replace( '/\.[^.]+$/', '', basename( $status['url'] ) ), $attach_id, true );
		}

		$date = date_i18n( 'Y-m-d H:i:s' );

		$attachment_date = array(
			'ID'        => sanitize_text_field( $attach_id ),
			'post_date' => sanitize_text_field( $date ),
		);
		wp_update_post( $attachment_date );

		$pre_array = wp_get_attachment_image_src( $attach_id, $size = 'fts_thumb' );
		// We create an array and send the thumbnail url and also the attachment id so we can sort the gallery before the page is even refreshed with our ajax 'response' js var in the metabox.js file
		$return = array(
			'url' => $pre_array[0],
			'id'  => $attach_id,
		);
		// json_encode response so we can get the array of results and use them in our ajax 'response' js var in the metabox.js file too....ie* response['url'] response['id']
		echo json_encode( $return );
		exit;
	}

				/**
				 *  Create Thumb
				 *
				 * Create a 150x150 thumbnail for our gallery edit page
				 *
				 * @param $image_source
				 * @since 1.0.0
				 */
	public function fts_create_thumb( $image_source ) {
		$image = $image_source;
		// error_log($image_source . ' Full FILE NAME WITH HTTP<br/><br/>');
		$instance_common = new FTGallery_Create_Image();
		$force_overwrite = true;
		// Generate the new cropped gallery image.
		$instance_common->resize_image( $image, '150', '150', false, 'c', '100', false, null, $force_overwrite );
	}

				/**
				 *  Generate new Attachment Name
				 *
				 * Generates a new attachment name (used in upload action)
				 *
				 * @param $gallery_id
				 * @param $attachment_ID
				 * @since 1.0.0
				 */
	public function fts_generate_new_attachment_name( $gallery_id, $attachment_ID, $file_name ) {
		$final_title = '';

		// Include Gallery Title
		if ( '1' === get_option( 'fts_attch_title_gallery_name' ) ) {
			$final_title .= get_the_title( $gallery_id ) . ' ';
		}
		// Include Gallery ID
		if ( ! empty( $gallery_id ) && '1' === get_option( 'fts_attch_title_post_id' ) ) {
			$final_title .= $gallery_id . ' ';
		}
		// include Date Uploaded
		if ( isset( $_POST['postID'] ) && '1' === get_option( 'fts_attch_title_date' ) ) {
			$final_title .= date_i18n( 'F jS, Y' ) . ' ';
		}
		// Include File Name
		if ( '1' === get_option( 'fts_attch_title_file_name' ) ) {
			$final_title .= $file_name . ' ';
		}
		// Include Attch ID
		if ( '1' === get_option( 'fts_attch_title_attch_id' ) ) {
			$final_title .= $attachment_ID . ' ';
		}

		if ( '1' !== get_option( 'fts_attch_title_gallery_name' ) && '1' !== get_option( 'fts_attch_title_post_id' ) && '1' !== get_option( 'fts_attch_title_date' ) && '1' !== get_option( 'fts_attch_title_attch_id' ) ) {
			$final_title .= $file_name . ' ';
		}

		$this->fts_format_attachment_title( $final_title, $attachment_ID, 'true' );
	}

				/**
				 *  Rename Attachment
				 *
				 * Renames attachment (used for File Re-name settings option)
				 *
				 * @param $gallery_id
				 * @param $attachment_ID
				 * @since 1.0.0
				 */
	public function fts_rename_attachment( $gallery_id, $attachment_ID, $file_name ) {

		$file = get_attached_file( $attachment_ID );
		$path = pathinfo( $file );

		$final_filename = '';

		// Include Gallery Title
		if ( '1' === get_option( 'fts_attch_name_gallery_name' ) ) {
			$final_filename .= get_the_title( $gallery_id ) . '-';
		}
		// Include Gallery ID
		if ( ! empty( $gallery_id ) && '1' === get_option( 'fts_attch_name_post_id' ) ) {
			$final_filename .= $gallery_id . '-';
		}
		// include Date Uploaded
		if ( isset( $_POST['postID'] ) && '1' === get_option( 'fts_attch_name_date' ) ) {
			$final_filename .= date_i18n( 'F jS, Y' ) . '-';
		}
		// Include File Name
		if ( '1' === get_option( 'fts_attch_name_file_name' ) ) {
			$final_filename .= $file_name . ' ';
		}
		// Include Attch ID
		if ( '1' === get_option( 'fts_attch_name_attch_id' ) ) {
			$final_filename .= $attachment_ID . ' ';
		}

		$final_filename = sanitize_file_name( $final_filename );

		$newfile = $path['dirname'] . '/' . $final_filename . '.' . $path['extension'];

		rename( $file, $newfile );
		update_attached_file( $attachment_ID, esc_url_raw( $newfile ) );
	}

				/**
				 *  Shortcode Meta Box
				 *
				 *  copy & paste shortcode input box
				 *
				 * @param $object
				 * @since 1.0.0
				 */
	public function fts_shortcode_meta_box( $object ) {
		?>
		<div class="ft-gallery-meta-wrap">
		<?php

		$gallery_id = isset( $_GET['post'] ) ? $_GET['post'] : '';

		$screen = get_current_screen();

		if ( 'edit.php?post_type=fts' === $screen->parent_file && 'add' === $screen->action ) {
			?>
			<p>
				<label><label><?php echo esc_html__( 'Save or Publish this Gallery to be able to copy this Gallery\'s Shortcode.', 'feed_them_social' ); ?></label>
			</p>
						<?php
		} else {
			// Copy Shortcode
			?>
			<p>
				<label><label><?php echo esc_html__( 'Copy and Paste this shortcode to any page, post or widget.', 'feed_them_social' ); ?></label>
					<input readonly="readonly" value="[feed_them_social id=<?php echo esc_html( $gallery_id ); ?>]" onclick="this.select();"/>
			</p>
						<?php
		}

		?>
		</div>
		<?php
	}

				/**
				 * Get Attachment Info
				 * Combines get_post and wp_get_attachment_metadata to create some clean attachment info
				 *
				 * @param $attachment_id
				 * @param bool          $include_meta_data (True || False) Default: False
				 * @return array
				 * @since 1.0.0
				 */
	public function fts_get_attachment_info( $attachment_id, $include_meta_data = false ) {
		// Get all of the Attachment info!
		$attach_array = wp_prepare_attachment_for_js( $attachment_id );

		$path_parts = pathinfo( $attach_array['filename'] );

		$attachment_terms = get_the_terms( $attach_array['id'], 'ftg-tags' );

		$attachment_info = array(
			'ID'           => $attach_array['id'],

			// these 2 items needed for the set_downloads woocommerce function check
			'download_id'  => $attach_array['id'],
			'name'         => $attach_array['title'],

			'title'        => $attach_array['title'],
			'type'         => $attach_array['type'],
			'subtype'      => $attach_array['type'],
			'alt'          => $attach_array['alt'],
			'caption'      => $attach_array['caption'],
			'description'  => $attach_array['description'],
			'href'         => $attach_array['link'],
			'src'          => $attach_array['url'],
			'mime-type'    => $attach_array['mime'],
			'file'         => $attach_array['url'],
			'slug'         => $path_parts['filename'],
			'download_url' => get_permalink( $attach_array['uploadedTo'] ) . '?attachment_name=' . $attach_array['id'] . '&download_file=1',

			// Tags
			'tags'         => false !== $attachment_terms ? $attachment_terms : 'no tags',
		);

		// IF Exif data is set to return and is set in Meta Data.
		// if($include_meta_data){
		$meta_data = wp_get_attachment_metadata( $attachment_id );

		$attachment_info['meta_data'] = isset( $meta_data ) ? $meta_data : '';

		// }
		return $attachment_info;
	}

				/**
				 *  Format Attachment Title
				 * Format the title for attachments to ensure awesome titles (options on settings page)
				 *
				 * @param $title
				 * @param null  $attachment_id
				 * @param null  $update_post
				 * @return mixed|string
				 * @since 1.0.0
				 */
	public function fts_format_attachment_title( $title, $attachment_id = null, $update_post = null ) {

		$options     = get_option( 'fts_format_attachment_titles_options' );
		$cap_options = isset( $options['fts_cap_options'] ) ? $options['fts_cap_options'] : 'dont_alter';

		if ( ! empty( $attachment_id ) ) {
			$uploaded_post_id = get_post( $attachment_id );
			// $title = $uploaded_post_id->post_title;
		}

		/* Update post. */
		$char_array = array();
		if ( isset( $options['fts_fat_hyphen'] ) && $options['fts_fat_hyphen'] ) {
			$char_array[] = '-';
		}
		if ( isset( $options['fts_fat_underscore'] ) && $options['fts_fat_underscore'] ) {
			$char_array[] = '_';
		}
		if ( isset( $options['fts_fat_period'] ) && $options['fts_fat_period'] ) {
			$char_array[] = '.';
		}
		if ( isset( $options['fts_fat_tilde'] ) && $options['fts_fat_tilde'] ) {
			$char_array[] = '~';
		}
		if ( isset( $options['fts_fat_plus'] ) && $options['fts_fat_plus'] ) {
			$char_array[] = '+';
		}

		/* Replace chars with spaces, if any selected. */
		if ( ! empty( $char_array ) ) {
			$title = str_replace( $char_array, ' ', $title );
		}

		/* Trim multiple spaces between words. */
		$title = preg_replace( '/\s+/', ' ', $title );

		/* Capitalize Title. */
		switch ( $cap_options ) {
			case 'cap_all':
				$title = ucwords( $title );
				break;
			case 'cap_first':
				$title = ucfirst( strtolower( $title ) );
				break;
			case 'all_lower':
				$title = strtolower( $title );
				break;
			case 'all_upper':
				$title = strtoupper( $title );
				break;
			case 'dont_alter':
				/* Leave title as it is. */
				break;
		}

		// Return Clean Title otherwise update post!
		if ( 'true' !== $update_post ) {
			return esc_html( $title );
		}

		// add formatted title to the alt meta field
		if ( isset( $options['fts_fat_alt'] ) && $options['fts_fat_alt'] ) {
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $title ) );
		}

		// update the post
		$uploaded_post = array(
			'ID'         => sanitize_text_field( $attachment_id ),
			'post_title' => sanitize_text_field( $title ),
		);

		// add formatted title to the description meta field
		if ( isset( $options['fts_fat_description'] ) && $options['fts_fat_description'] ) {
			$uploaded_post['post_content'] = sanitize_text_field( $title );
		}

		// add formatted title to the caption meta field
		if ( isset( $options['fts_fat_caption'] ) && $options['fts_fat_caption'] ) {
			$uploaded_post['post_excerpt'] = sanitize_text_field( $title );
		}

		wp_update_post( $uploaded_post );

		return $title;
	}

				/**
				 *  ZIP exists check
				 * Check if ZIP still exists
				 *
				 * @param $id_to_check
				 * @return bool
				 * @since 1.0.0
				 */
	public function fts_zip_exists_check( $id_to_check ) {
		$fts_zip_status = get_post_status( $id_to_check );

		// Check the Status if False or in Trash return false
		return false == $fts_zip_status || 'trash' === $fts_zip_status ? 'false' : 'true';
	}

	/**
	 *  Duplicate Post As Draft
	 * Function creates post duplicate as a draft and redirects then to the edit post screen
	 *
	 * @since 1.0.0
	 */
	public function fts_duplicate_post_as_draft() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'fts_duplicate_post_as_draft' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No Gallery to duplicate has been supplied!', 'feed_them_social' ) );
		}

		/*
		 * Nonce verification
		 */
		if ( ! isset( $_GET['duplicate_nonce'] ) || ! wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		/*
		 * get the original post id
		 */
		$post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if post data exists, create the post duplicate
		 */
		if ( isset( $post ) && null !== $post ) {

			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_results = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d", $post_id ) );

			if ( 0 !== count( $post_meta_results ) ) {
				foreach ( $post_meta_results as $meta_info ) {
					if ( '_wp_old_slug' === $meta_info->meta_value ) {
						continue;
					}
					$wpdb->query(
						$wpdb->prepare(
							"INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value ) VALUES ( %d, %s, %s )",
							$new_post_id,
							$meta_info->meta_key,
							$meta_info->meta_value
						)
					);
				}
			}

			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
			exit;
		}

		wp_die( esc_html__( 'Gallery duplication failed, could not find original Gallery: ' . $post_id, 'feed_them_social' ) );
	}

	/**
	 * Metabox Specific Form Inputs
	 *
	 * This adds to the ouput of the metabox output forms for settings_html_form function in the Metabox Settings class.
	 *
	 * @param $params
	 * @param $input_option
	 * @return
	 * @since 1.1.6
	 */
	public function metabox_specific_form_inputs( $params ) {
		// 'This' Class object.
		$gallery_class = $params['this'];
		// Gallery ID.
		$gallery_id = isset( $_GET['post'] ) ? $_GET['post'] : '';
		// Gallery Options (REST API call).
		$gallery_options_returned = $gallery_class->fts_get_gallery_options_rest( $gallery_id );
		// Option Info.
		$option = $params['input_option'];

		$output = '';

		if ( isset( $option['option_type'] ) ) {
			switch ( $option['option_type'] ) {

				// Checkbox for image sizes COMMENTING OUT BUT LEAVING FOR FUTURE QUICK USE
				// case 'checkbox-image-sizes':
				// $final_value_images = array('thumbnailzzz','mediummmm', 'large', 'full');
				// Get Gallery Options via the Rest API
				// $final_value_images = $gallery_options_returned['ft_watermark_image_sizes']['image_sizes'];
				// print_r($final_value_images);
				// array('thumbnailzzz','mediummmm', 'largeee', 'fullll');
				// $output .= '<label for="'. $option['id'] . '"><input type="checkbox" val="' . $option['default_value'] . '" name="ft_watermark_image_sizes[image_sizes][' . $option['default_value'] . ']" id="'.$option['id'] . '" '. ( array_key_exists($option['default_value'], $final_value_images) ? ' checked="checked"' : '') .'/>';
				// $output .= '' . $option['default_value'] . '</label>';
				// break;
				// Checkbox for image sizes used so you can check the image sizes you want to be water marked after you save the page.
				case 'checkbox-dynamic-image-sizes':
					$final_value_images = isset( $gallery_options_returned['ft_watermark_image_sizes']['image_sizes'] ) ? $gallery_options_returned['ft_watermark_image_sizes']['image_sizes'] : array();
					$output            .= '<div class="clear"></div>';

					global $_wp_additional_image_sizes;

					$sizes = array();
					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
						$output .= '<label for="' . esc_attr( $_size ) . '"><input type="checkbox" val="' . esc_attr( $_size ) . '" name="ft_watermark_image_sizes[image_sizes][' . esc_attr( $_size ) . ']" id="' . esc_attr( $option['id'] ) . '-' . esc_attr( $_size ) . '" ' . ( array_key_exists( $_size, $final_value_images ) ? ' checked="checked"' : '' ) . '/>' . esc_html( $_size ) . ' ' . esc_html( $sizes[ $_size ]['width'] ) . ' x ' . esc_html( $sizes[ $_size ]['height'] ) . '</label><br/>';

					}
					$output .= '<label for="full"><input type="checkbox" val="full" id="ft_watermark_image_-full" name="ft_watermark_image_sizes[image_sizes][full]" ' . ( array_key_exists( 'full', $final_value_images ) ? 'checked="checked"' : '' ) . '/>full</label><br/>';
					$output .= '<br/><br/>';
					// TESTING AREA
					// echo $final_value_images;
					// echo '<pre>';
					// print_r($sizes);
					// echo '</pre>';
					break;

				// Image sizes for page.
				case 'ft-images-sizes-page':
					$final_value_images = isset( $gallery_options_returned['fts_images_sizes_page'] ) ? $gallery_options_returned['fts_images_sizes_page'] : '';
					$output            .= '<select name="' . esc_attr( $option['name'] ) . '" id="' . esc_attr( $option['id'] ) . '"  class="feed_them_social-admin-input">';

					global $_wp_additional_image_sizes;

					$sizes   = array();
					$output .= '<option val="Choose an option" ' . ( 'not_set' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'Choose an option', 'feed_them_social' ) . '</option>';
					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
						$output .= '<option val="' . esc_attr( $_size ) . '" ' . ( esc_attr( $_size ) . ' ' . esc_attr( $sizes[ $_size ]['width'] ) . ' x ' . $sizes[ $_size ]['height'] === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html( $_size ) . ' ' . esc_html( $sizes[ $_size ]['width'] ) . ' x ' . esc_html( $sizes[ $_size ]['height'] ) . '</option>';
					}
					$output .= '<option val="full" ' . ( 'full' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'full', 'feed_them_social' ) . '</option>';
					// TESTING AREA
					// echo $final_value_images;
					// echo '<pre>';
					// print_r($sizes);
					// echo '</pre>';
					$output .= '</select>';
					break;

				// Image sizes for popup.
				case 'ft-images-sizes-popup':
					$final_value_images = isset( $gallery_options_returned['fts_images_sizes_popup'] ) ? $gallery_options_returned['fts_images_sizes_popup'] : '';
					$output            .= '<select name="' . esc_attr( $option['name'] ) . '" id="' . esc_attr( $option['id'] ) . '"  class="feed_them_social-admin-input">';

					global $_wp_additional_image_sizes;

					$sizes = array();

					$output .= '<option val="Choose an option" ' . ( 'not_set' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'Choose an option', 'feed_them_social' ) . '</option>';
					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
						$output .= '<option val="' . esc_attr( $_size ) . '" ' . ( esc_attr( $_size ) . ' ' . esc_attr( $sizes[ $_size ]['width'] ) . ' x ' . $sizes[ $_size ]['height'] == $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_attr( $_size ) . ' ' . esc_attr( $sizes[ $_size ]['width'] ) . ' x ' . esc_attr( $sizes[ $_size ]['height'] ) . '</option>';
					}
					$output .= '<option val="full" ' . ( 'full' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'full', 'feed_them_social' ) . '</option>';
					// TESTING AREA
					// echo $final_value_images;
					// echo '<pre>';
					// print_r($sizes);
					// echo '</pre>';
					$output .= '</select>';
					break;

				// Image sizes for Free download icon.
				case 'ftg-free-download-size':
					$final_value_images = isset( $gallery_options_returned['ftg_free_download_size'] ) ? $gallery_options_returned['ftg_free_download_size'] : '';
					$output            .= '<select name="' . esc_attr( $option['name'] ) . '" id="' . esc_attr( $option['id'] ) . '"  class="feed_them_social-admin-input">';

					global $_wp_additional_image_sizes;

					$sizes   = array();
					$output .= '<option val="Choose an option" ' . ( 'not_set' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'Choose an option', 'feed_them_social' ) . '</option>';
					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
						$output .= '<option val="' . esc_attr( $_size ) . '" ' . ( esc_attr( $_size ) . ' ' . esc_attr( $sizes[ $_size ]['width'] ) . ' x ' . $sizes[ $_size ]['height'] == $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_attr( $_size ) . ' ' . esc_attr( $sizes[ $_size ]['width'] ) . ' x ' . esc_attr( $sizes[ $_size ]['height'] ) . '</option>';
					}
					$output .= '<option val="full" ' . ( 'full' === $final_value_images ? 'selected="selected"' : '' ) . '>' . esc_html__( 'full', 'feed_them_social' ) . '</option>';
					// TESTING AREA
					// echo $final_value_images;
					// echo '<pre>';
					// print_r($sizes);
					// echo '</pre>';
					$output .= '</select>';
					break;
			}
		}

		return $output;
	}

	/**
	 *  Save Custom Meta Box
	 * Save Fields for Galleries
	 *
	 * @param $post_id
	 * @param $post
	 * @return string
	 * @since 1.0.0
	 */
	public function fts_save_custom_meta_box( $post_id, $post ) {
		/*
		if ( ! isset( $_POST['ft-galleries-settings-meta-box-nonce'] ) || ! wp_verify_nonce( $_POST['ft-galleries-settings-meta-box-nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// Can User Edit Post?
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// Autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// CPT Check
		$slug = 'fts';
		if ( $slug != $post->post_type ) {
			return $post_id;
		}

		//  $attach_ID = $this->fts_get_gallery_attached_media_ids( $post_id );
		//  foreach ( $attach_ID as $img_index => $img_id ) {
		//      $a = array(
		//          'ID'         => $img_id,
		//          'menu_order' => $img_index,
		//      );
		//      wp_update_post( $a );
		//  }

		if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			include FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR . 'includes/watermark/save.php';
		}
		// end premium
		// Return settings
		return 'is this even working';*/
	}

				/**
				 *  Get Gallery Attached Media IDs
				 *
				 * Get an Array of ID's of attachments for this Gallery.
				 *
				 * @param $gallery_id
				 * @param string     $mime_type (leave empty for all types)
				 * @return array
				 * @since 1.0.0
				 */
	public function fts_get_gallery_attached_media_ids( $gallery_id, $mime_type = '' ) {
		$post_attachments = get_attached_media( $mime_type, $gallery_id );

		$attachment_ids_array = array();
		foreach ( $post_attachments as $attachment ) {
			$attachment_ids_array[] = $attachment->ID;
		}

		return $attachment_ids_array;
	}

				/**
				 *  Duplicate Post Link
				 * Add the duplicate link to action list for post_row_actions
				 *
				 * @param $actions
				 * @param $post
				 * @return mixed
				 * @since 1.0.0
				 */
	public function fts_duplicate_post_link( $actions, $post ) {
		// make sure we only show the duplicate gallery link on our pages
		if ( current_user_can( 'edit_posts' ) && 'fts' === $_GET['post_type'] ) {
			$actions['duplicate'] = '<a id="ft-gallery-duplicate-action" href="' . esc_url( wp_nonce_url( 'admin.php?action=fts_duplicate_post_as_draft&post=' . $post->ID, basename( __FILE__ ), 'duplicate_nonce' ) ) . '" title="Duplicate this item" rel="permalink">' . esc_html__( 'Duplicate', 'feed_them_social' ) . '</a>';
		}

		return $actions;
	}

				/**
				 *  Duplicate Post ADD Duplicate Post Button
				 * Add a button in the post/page edit screen to create a clone
				 *
				 * @since 1.0.0
				 */
	public function fts_duplicate_post_add_duplicate_post_button() {
		$current_screen = get_current_screen();
		$verify         = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		// check to make sure we are not on a new fts post, because what is the point of duplicating a new one until we have published it?
		if ( 'fts' === $current_screen->post_type && 'fts' !== $verify ) {
			$id = $_GET['post'];
			?>
			<div id="ft-gallery-duplicate-action">
				<a href="<?php echo esc_url( wp_nonce_url( 'admin.php?action=fts_duplicate_post_as_draft&post=' . $id, basename( __FILE__ ), 'duplicate_nonce' ) ); ?>"
				   title="Duplicate this item"
				   rel="permalink"><?php esc_html_e( 'Duplicate Gallery', 'feed_them_social' ); ?></a>
			</div>
						<?php
		}
	}
} ?>
