<?php
/**
 * Shortcode_Button Class
 *
 * This class has the functions to create add a shortcode button to WordPress "Edit Post" page
 *
 * @class    Shortcode_Button
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shortcode_Button
 */
class Shortcode_Button {

	/**
	 * All Options
	 *
	 * Adds all the options.
	 *
	 * @var    $all_options
	 * @since 1.0.0
	 */
	public $all_options = '';

	/**
	 * Load
	 *
	 * Load up our actions and filters.
	 *
	 * @since 1.0.0
	 */
	public static function load() {
		$instance = new self();

		// Initiate Shortcode_media_button.
		$instance->fts_shortcode_media_button();
		$instance->add_actions_filters();
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Add Action Filters
	 *
	 * Load up all our styles and js.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {
		add_action( 'wp_ajax_fts_editor_get_galleries', array( $this, 'fts_editor_get_galleries' ) );
		add_filter( 'media_buttons_context', array( $this, 'fts_shortcode_media_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'fts_shortcode_get_all_options' ) );
		add_action( 'print_media_templates', array( $this, 'fts_print_media_templates' ) );
	}

	/**
	 *  Shortcode Get All Options
	 *
	 * Adds the Custom Shortcode button scripts that appears on admin post type pages.
	 *
	 * @since 1.0.0
	 */
	public function fts_shortcode_get_all_options() {

		$current_screen = get_current_screen();
		$is_admin       = is_admin();

		// We must only show contents below if we're on a post page in the wp admin.
		if ( $is_admin && 'post' !== $current_screen->base || $is_admin && 'fts' === $current_screen->post_type || $is_admin && 'fts_albums' === $current_screen->post_type ) {
			return;
		}

		// Enqueue the gallery / album selection script.
		wp_enqueue_script( 'gallery-select-script', plugins_url( 'feed-them-social/includes/shortcode-button/js/gallery-select.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
		wp_localize_script(
			'gallery-select-script',
			'fts_select',
			array(
				'get_galleries_nonce' => wp_create_nonce( 'ft-gallery-editor-get-galleries' ),
				'modal_title'         => __( 'Insert', 'feed_them_social' ),
				'insert_button_label' => __( 'Insert', 'feed_them_social' ),
			)
		);

		// Enqueue the script that will trigger the editor button.
		wp_enqueue_script( 'editor-script', plugins_url( 'feed-them-social/includes/shortcode-button/js/editor.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
		wp_localize_script(
			'gallery-select-script',
			'fts_editor',
			array(
				'modal_title'         => __( 'Insert Gallery', 'feed_them_social' ),
				'insert_button_label' => __( 'Insert', 'feed_them_social' ),
			)
		);
	}

	/**
	 *  Shortcode Media Button
	 *
	 * Adds a custom gallery insert button beside the media uploader button.
	 *
	 * @since 1.0.0
	 *
	 * @return string $buttons Amended media buttons context HTML.
	 */
	public function fts_shortcode_media_button() {

		// Create the media button.
		$button = '<a id="ft-media-modal-button" href="javascript:;" class="button feed_them_social-choose-gallery" data-action="gallery" title="' . esc_attr__( 'Add Feed', 'feed_them_social' ) . '" >
            <span class="ft-media-icon"></span> ' .
			__( 'Add Feed', 'feed_them_social' ) .
			'</a>
            ';
		// Filter the button.
		$button = apply_filters( 'fts_media_button', $button );

		// Append the button.
		return $button;

	}

	/**
	 *  Get Galleries
	 *
	 * Returns all galleries created on the site.
	 *
	 * @since 1.0.0
	 *
	 * @param    bool   $skip_empty Skip empty sliders.
	 * @param    bool   $ignore_cache Ignore Transient cache.
	 * @param    string $search_terms Search for specified Galleries by Title.
	 *
	 * @return array|bool Array of gallery data or false if none found.
	 */
	public function fts_get_galleries( $skip_empty = true, $ignore_cache = false, $search_terms = '' ) {

		// Get gallery items.
		$galleries = $this->fts_internal_get_galleries( $skip_empty, $search_terms );

		// Return the gallery data.
		return $galleries;
	}

	/**
	 *  Internal Get Galleries
	 *
	 * Internal method that returns all galleries created on the site.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $skip_empty Skip Empty Galleries.
	 * @param string $search_terms Search for specified Galleries by Title.
	 * @return mixed Array of gallery data or false if none found.
	 */
	public function fts_internal_get_galleries( $skip_empty = true, $search_terms = '' ) {

		// Build WP_Query arguments.
		$args = array(
			'post_type'      => 'fts',
			'post_status'    => 'publish',
			'posts_per_page' => 99,
			'no_found_rows'  => true,
			'fields'         => 'ids',
		);

		// If search terms exist, add a search parameter to the arguments.
		if ( ! empty( $search_terms ) ) {
			$args['s'] = $search_terms;
		}

		// Run WP_Query.
		$galleries = new \ WP_Query( $args );
		if ( ! isset( $galleries->posts ) || empty( $galleries->posts ) ) {
			return false;
		}

		// Now loop through all the galleries found and only use galleries that have images in them.
		$ret = array();
		foreach ( $galleries->posts as $id ) {
			$data = '[ft-gallery id=' . $id . ']';

			// error_log($data); .
			$ret[] = array( 'id' => $id );
			// Add gallery to array of galleries.
		}

		// Return the gallery data.
		return $ret;
	}

	/**
	 *  Editor Get Galleries
	 *
	 * Returns Galleries, with an optional search term
	 *
	 * @since 1.0.0
	 */
	public function fts_editor_get_galleries() {
		global $post;

		// Check nonce.
		check_admin_referer( 'ft-gallery-editor-get-galleries', 'nonce' );

		// Get POSTed fields.
		$my_post      = stripslashes_deep( $_POST );
		$search       = (bool) sanitize_text_field( wp_unslash( $my_post['search'] ) );
		$search_terms = sanitize_text_field( wp_unslash( $my_post['search_terms'] ) );
		$prepend_ids  = stripslashes( $my_post['prepend_ids'] );
		$results      = array();

		// Get galleries.
		$galleries = $this->fts_get_galleries( false, true, ( $search ? $search_terms : '' ) );

		$display_gallery = new Display_Gallery();
		// Build array of just the data we need.
		foreach ( (array) $galleries as $gallery ) {
			// Get the thumbnail of the first image.
			$image_list = $display_gallery->fts_get_media_rest( $gallery['id'], '1' );
			$thumbnail  = $image_list[0]['media_details']['sizes']['medium']['source_url'];
			// error_log( $thumbnail );.
			// Instead of pulling the title from config, attempt to pull it from the gallery post first.
			if ( isset( $gallery['id'] ) ) {
				$gallery_post = get_post( $gallery['id'] );
			} else {
				$gallery_post = false;
			}

			$temp_title = false;
			if ( isset( $gallery_post->post_title ) ) {
				$temp_title = trim( $gallery_post->post_title );
			}

			if ( ! empty( $temp_title ) ) {
				$gallery_title = $gallery_post->post_title;
			} elseif ( isset( $gallery['config']['title'] ) ) {
				$gallery_title = $gallery['config']['title'];
			} else {
				$gallery_title = false;
			}

			// Check to make sure variables are there.
			$gallery_id          = false;
			$gallery_config_slug = false;

			if ( isset( $gallery['id'] ) ) {
				$gallery_id = $gallery['id'];
			}
			if ( isset( $gallery['config']['slug'] ) ) {
				$gallery_config_slug = $gallery['config']['slug'];
			}

			// Add gallery to results.
			$results[] = array(
				'id'        => $gallery_id,
				'slug'      => $gallery_config_slug,
				'title'     => $gallery_title,
				'thumbnail' => $thumbnail,
				// Tells the editor modal whether this is a Gallery or Album for the shortcode output.
				'action'    => 'gallery',
			);
		}

		// If any prepended Gallery IDs were specified, get them now
		// These will typically be a Defaults Gallery, which wouldn't be included in the above fts_get_galleries() call.
		if ( is_array( $prepend_ids ) && count( $prepend_ids ) > 0 ) {
			$prepend_results = array();

			// Get each Gallery.
			foreach ( $prepend_ids as $gallery_id ) {
				// Get gallery.
				$gallery = get_post_meta( $gallery_id, '_eg_gallery_data', true );

				// Get gallery first image.
				if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
					$display_gallery = new Display_Gallery();
					$image_list      = $display_gallery->fts_get_media_rest( $gallery['id'], '1' );
					$thumbnail       = $image_list[0]['media_details']['sizes']['thumbnail']['source_url'];
				}

				// Add gallery to results.
				$prepend_results[] = array(
					'id'        => $gallery['id'],
					'slug'      => $gallery['config']['slug'],
					'title'     => $gallery['config']['title'],
					'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
					'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
				);
			}

			// Add to results.
			if ( is_array( $prepend_results ) && count( $prepend_results ) > 0 ) {
				$results = array_merge( $prepend_results, $results );
			}
		}

		// Return galleries.
		wp_send_json_success( $results );

	}

	/**
	 *  Print Media Templates
	 *
	 * Outputs backbone.js wp.media compatible templates, which are loaded into the modal view
	 *
	 * @since 1.0.0
	 */
	public function fts_print_media_templates() {

		// Insert Gallery (into Visual / Text Editor)
		// Use: wp.media.template( 'ft-selection' ).
		?>
		<script type="text/html" id="tmpl-ft-selection">
			<div class="media-frame-title">
				<h1>{{data.modal_title}}</h1>
			</div>
			<div class="media-frame-content">
				<div class="attachments-browser ft-gallery ft-gallery-editor">
					<!-- Galleries -->
					<ul class="attachments"></ul>

					<!-- Sidebar -->
					<div class="media-sidebar attachment-info"></div>

					<!-- Search -->
					<div class="media-toolbar">
						<div class="media-toolbar-secondary">
							<span class="spinner"></span>
						</div>
						<div class="media-toolbar-primary search-form">
							<label for="ft-gallery-search" class="screen-reader-text"><?php esc_html_e( 'Search', 'feed_them_social' ); ?></label>
							<input type="search" placeholder="<?php esc_html_e( 'Search', 'feed_them_social' ); ?>" id="ft-gallery-search" class="search"/>
						</div>
					</div>
				</div>
			</div>

			<!-- Footer Bar -->
			<div class="media-frame-toolbar">
				<div class="media-toolbar">
					<div class="media-toolbar-primary search-form">
						<button type="button" class="button media-button button-primary button-large media-button-insert" disabled="disabled">
							{{data.insert_button_label}}
						</button>
					</div>
				</div>
			</div>
		</script>
		<?php
		// Single Selection Item (Gallery or Album)
		// Use: wp.media.template( 'ft-selection-item' ).
		?>
		<script type="text/html" id="tmpl-ft-selection-item">
			<div class="attachment-preview" data-id="{{ data.id }}">
				<div class="thumbnail">
					<#
							if ( data.thumbnail != '' ) {
							#>
						<img src="{{ data.thumbnail }}" alt="{{ data.title }}"/>
						<#
								}
								#>
							<strong>
								<span>{{ data.title }}</span>
							</strong>
							<code>
								[feed-them-{{ data.action }} id="{{ data.id }}"]
							</code>
				</div>
			</div>

			<a class="check">
				<div class="media-modal-icon"></div>
			</a>
		</script>
		<?php
		// Selection Sidebar
		// Use: wp.media.template( 'ft-selection-sidebar' ).
		?>
		<script type="text/html" id="tmpl-ft-selection-sidebar">
			<h3><?php esc_html_e( 'Helpful Tips', 'feed_them_social' ); ?></h3>
			<strong><?php esc_html_e( 'Choosing Your Gallery', 'feed_them_social' ); ?></strong><p>
				<?php esc_html_e( 'Simply click on one of the boxes to the left or you can Ctrl(PC) / cmd(MAC) and click to select multiple Galleries.  The "Insert" button will be activated once you have selected a gallery.', 'feed_them_social' ); ?>
			</p><strong><?php esc_html_e( 'Insert Your Gallery', 'feed_them_social' ); ?></strong><p>
				<?php esc_html_e( 'To insert your gallery, click on the "Insert" button below.', 'feed_them_social' ); ?>
			</p>
			<h3><?php esc_html_e( 'Title Options', 'feed_them_social' ); ?></h3>
			<p><?php esc_html_e( 'Add the Gallery Title before each shortcode and Align the Title if you like.', 'feed_them_social' ); ?></p>
			<div class="settings">
				<label class="setting">
					<p>
						<span class="name"><?php esc_html_e( 'Display Title', 'feed_them_social' ); ?></span>
						<select name="title" size="1">
							<option value="0" selected><?php esc_html_e( 'No', 'feed_them_social' ); ?></option>
							<?php
							for ( $i = 1; $i <= 6; $i++ ) {
								?>
								<option value="h<?php echo esc_attr( $i ); ?>"><?php echo sprintf( esc_html( 'Yes, as Heading H%s', 'feed_them_social' ), esc_attr( $i ) ); ?></option>
								<?php
							}
							?>
						</select>
					</p>
				</label>
				<label class="setting">
					<span class="name"><?php esc_html_e( 'Align Title', 'feed_them_social' ); ?></span>
					<select name="align" size="1">
						<option value="" selected><?php esc_html_e( 'No', 'feed_them_social' ); ?></option>
						<option value="left"><?php esc_html_e( 'Left', 'feed_them_social' ); ?></option>
						<option value="center"><?php esc_html_e( 'Center', 'feed_them_social' ); ?></option>
						<option value="right"><?php esc_html_e( 'Right', 'feed_them_social' ); ?></option>
					</select>
				</label>
			</div>
		</script>
		<?php
		// Error
		// Use: wp.media.template( 'ft-gallery-error' ).
		?>
		<script type="text/html" id="tmpl-ft-gallery-error">
			<p>
				{{ data.error }} </p>
		</script>

		<?php
	}
}