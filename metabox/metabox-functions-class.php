<?php
/**
 * Metabox Settings Class
 *
 * This class is for creating a metabox settings pages/sections!
 *
 * @version  1.1.6
 * @package  FeedThemGalley/Core
 * @author   SlickRemix
 */

namespace feedthemsocial;

/**
 * Class Metabox_Functions
 *
 * @package feed_them_social
 */
class Metabox_Functions {

	/**
	 * Holds the hook id
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $hook_id;

	/**
	 * Settings Page Name
	 *
	 * This is the page name set for the edit settings page (ie. page=template_settings_page) generally set in URL
	 *
	 * @var array
	 */
	public $settings_page_name;

	/**
	 * Main Post Type
	 *
	 * The post type to be checked
	 *
	 * @var string
	 */
	public $main_post_type;

	/**
	 * Is Page
	 *
	 * Is the class being loaded on a page?
	 *
	 * @var boolean
	 */
	public $is_page;

	/**
	 * Parent Post ID
	 * used to set Gallery ID
	 *
	 * @var string
	 */
	public $parent_post_id;

	/**
	 * Specific Form Options
	 * This allows us to add Specific Metabox Inputs from the constructing class using '' function we add to that class.
	 *
	 * @var string
	 */
	private $metabox_specific_form_inputs;

	/**
	 * Default Options Array
	 *
     * Default options array. Usually set in the options file.
	 *
	 * @var string
	 */
	public $default_options_array;


	/**
	 * Settings Functions
	 *
	 * The settings Functions class.
	 *
	 * @var object
	 */
	public $settings_functions;

	/**
	 * Options Functions
	 *
	 * The settings Functions class.
	 *
	 * @var object
	 */
	public $options_functions;

	/**
	 * Array Options Name
	 *
	 * The settings Functions class.
	 *
	 * @var object
	 */
	public $array_options_name;

	/**
	 * Metabox Functions constructor.
	 *
	 * Constructor.
	 *
	 * @param array $default_options_array All the options.
     * @param object $settings_functions Settings Functions.
     * @param object $options_functions Options Functions.
     * @param string $array_options_name Array Options name.
	 * @param string $is_page What page.
     *
	 * @since 1.0
	 */
	public function __construct( $default_options_array, $settings_functions, $options_functions, $array_options_name, $is_page = null) {
		// Default Options Array.
		$this->default_options_array = $default_options_array;

		// Settings Functions Class.
		$this->settings_functions = $settings_functions;

		// Options Functions Class.
		$this->options_functions = $options_functions;

        // Array Options Name.
		$this->array_options_name = $array_options_name;

		// Is Page.
		$this->is_page = $is_page;

		// Add Actions & Filters.
		$this->add_actions_filters();

		// Set Default main post type.
		$this->main_post_type = FEED_THEM_SOCIAL_POST_TYPE;

        // Metabox Nonce Name
		$this->metabox_nonce_name = $this->main_post_type . '_metabox_options_nonce';
	}

	/**
	 * Add Action Filters
	 *
	 * Load up all our styles and js.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {

		if ( is_admin() ) {
			// Save Page Metaboxes.
			if ( true == $this->is_page ) {
				// Add Save Metabox if Settings page is a page.
				add_action( 'admin_init', array( $this, 'add_submit_meta_box' ) );

				// Save Admin Page Metabox.
				add_action( 'admin_post_slickmetabox_form', array( $this, 'save_meta_box' ) );

			} else {
				// Save Post Metaboxes.
				add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
			}

			// Load Metabox Scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'metabox_scripts_styles' ) );

            // FTS License Page!
            if ( isset( $_GET['page'] ) && 'fts-license-page' === $_GET['page'] ) {
                add_action( 'admin_footer', array( $this, 'fts_plugin_license' ) );
            }
		}
	}

	/**
	 * Settings Page Scripts Styles
	 *
	 * Registers and Enqueues (in the admin) scripts and styles for settings page
	 *
	 * @param string $hook_suffix Find the hook suffix.
	 * @since 1.0.0
	 */
	public function metabox_scripts_styles( $hook_suffix ) {

		$current_info = $this->current_info_array();

		$page_base = $this->main_post_type . '_page_' . $this->settings_page_name;

		// SRL: THESE SCRIPTS CAN BE LOADED ON ALL OF OUR PAGES, BUT SHOULD ONLY LOAD ON OUR PLUGINS PAGES.
		if ( $this->main_post_type === $current_info['post_type'] ) {
			// Register Admin Page CSS.
			wp_register_style( 'slick-admin-page', plugins_url( 'feed-them-social/metabox/css/admin-pages.css' ), array(), FTS_CURRENT_VERSION );
			// Enqueue Admin Page CSS.
			wp_enqueue_style( 'slick-admin-page' );

			// Register Metabox CSS.
			wp_register_style( 'slick-metabox', plugins_url( 'feed-them-social/metabox/css/metabox.css' ), array(), FTS_CURRENT_VERSION );
			// Enqueue Metabox CSS.
			wp_enqueue_style( 'slick-metabox' );
		}

		// Is a 'Page' edit page. (aka Settings Class )
		// if ( $this->main_post_type === $current_info['post_type'] && $page_base === $current_info['base'] ) {
		// SRL: THESE SCRIPTS SHOULD ONLY BE LOADED ON THE GALLERY, ALBUM AND TEMPLATE SETTINGS PAGE.
		if ( isset( $_GET['page'] ) && 'template_settings_page' === $_GET['page'] || $this->main_post_type === $current_info['post_type'] && 'post' === $current_info['base'] && in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {

			// Enqueue jQuery. (Registered in WordPress Core)!
			wp_enqueue_script( 'jquery' );

			// Enqueue jQuery Form JS. (Registered in WordPress Core)!
			wp_enqueue_script( 'jquery-form' );

			// Enqueue jQuery UI Progressbar JS. (Registered in WordPress Core)!
			// wp_enqueue_script( 'jquery-ui-progressbar' );

			// Enqueue JS Color JS.
			wp_enqueue_script( 'js_color', plugins_url( '/feed-them-social/metabox/js/jscolor/jscolor.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );

			// Register Metabox JS.
			// wp_register_script( 'slick-metabox-js', plugins_url( 'feed-them-social/metabox/js/metabox.js' ), array(), FTS_CURRENT_VERSION, true );

			// Localize Metabox JS.
			wp_localize_script(
				'slick-metabox-js',
				'dgd_strings',
				array(
					'panel' => array(
						'title'  => __( 'Upload Images for Feed Them Social' ),
						'button' => __( 'Save and Close Popup' ),
					),
				)
			);

			// Enqueue Metabox JS.
			wp_enqueue_script( 'slick-metabox-js' );

			// Register Metabox Tabs JS.
			wp_register_script( 'slick-metabox-tabs', plugins_url( 'feed-them-social/metabox/js/metabox-tabs.js' ), array(), FTS_CURRENT_VERSION, true );

			// Localize Metabox Tabs JS.
			wp_localize_script(
				'slick-metabox-tabs',
				'ftg_mb_tabs',
				array(
					'submit_msgs' => array(
						'saving_msg'  => __( 'Saving Options' ),
						'success_msg' => __( 'Settings Saved Successfully' ),
                        'fts_post'    => admin_url( 'post.php?post=' .$_GET['post'] . '&action=edit' ),
                    )
				)
			);

			// Enqueue Metabox Tabs JS.
			wp_enqueue_script( 'slick-metabox-tabs' );

			// Register jQuery Nested Sortable JS.
			wp_register_script( 'jquery-nested-sortable-js', plugins_url( 'feed-them-social/metabox/js/jquery.mjs.nestedSortable.js' ), array( 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable, ' ), FTS_CURRENT_VERSION, false );
			// Enqueue jQuery Nested Sortable JS.
			wp_enqueue_script( 'jquery-nested-sortable-js' );
		}

		// SRL: THESE SCRIPTS SHOULD ONLY BE LOADED ON THE GALLERY, ALBUM AND TEMPLATE SETTINGS PAGE, BUT THEY ARE ALSO LOADING ON THE GALLERY LIST AND ALBUM LIST PAGE TOO
		// If is page we need to load extra metabox scripts usually loaded on a post page.
		if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || isset( $_GET['page'] ) && 'template_settings_page' === $_GET['page'] ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );

			// Register Update From Bottom JS.
			wp_register_script( 'updatefrombottom-admin-js', plugins_url( 'feed-them-social/metabox/js/update-from-bottom.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
			// Localize Update From Bottom JS.
			wp_localize_script(
				'updatefrombottom-admin-js',
				'updatefrombottomParams',
				array(
					'update'                         => esc_html__( 'Update', 'feed_them_social' ),
					'publish'                        => esc_html__( 'Publish', 'feed_them_social' ),
					'publishing'                     => esc_html__( 'Publishing...', 'feed_them_social' ),
					'updating'                       => esc_html__( 'Updating...', 'feed_them_social' ),
					'totop'                          => esc_html__( 'To top', 'feed_them_social' ),
                    // These next 2 options where added for the Main Options and Additional Options sub tabs under each feed.
                    'mainoptions'                    => esc_html__( 'Main Options', 'feed_them_social' ),
                    'additionaloptions'            => esc_html__( 'Additional Options', 'feed_them_social' ),
                    'additionalSettings'            => sprintf( esc_html__( 'Additional Global options available on the %1$sSettings Page%2$s.', 'feed_them_social' ),
                        '<a href="edit.php?post_type=fts&amp;page=fts-settings-page" target="_blank">',
                        '</a>'
                    ),
					// used in the success message for when images have been completely uploaded in the drag and drop are or file add button.
					'images_complete_on_auto_upload' => esc_html__( 'The Image(s) are done uploading. Please click the Publish or Update button now to edit your image(s).', 'feed_them_social' ),
				)
			);

			// Enqueue Update From Bottom JS.
			wp_enqueue_script( 'updatefrombottom-admin-js' );
		}
	}

	/**
	 * Set Hook ID
	 *
	 * Set the hook ID
	 *
	 * @param string $hook_id Get the hook ID.
	 * @since 1.0
	 */
	public function set_hook_id( $hook_id ) {
		global $hook_suffix;

		// Set Custom Hook ID or used Global Hook Suffix for hook naming.
		$this->hook_id = ! empty( $hook_id ) ? $hook_id : $hook_suffix;
	}

	/**
	 * Set Settings Page Name
	 *
	 * Set the settings page name.
	 *
	 * @param string $settings_page_name Get the settings page name.
	 * @since 1.0
	 */
	public function set_settings_page_name( $settings_page_name ) {
		// This is the page name set for the edit settings page (ie. page=template_settings_page) generally set in URL.
		$this->settings_page_name = $settings_page_name;
	}

	/**
	 * Set Metabox Specific Form Inputs
	 *
	 * Set the specific form inputs
	 *
	 * @param string $metabox_specific_form_inputs Get the specific form inputs.
	 * @since 1.0
	 */
	public function set_metabox_specific_form_inputs( $metabox_specific_form_inputs ) {
		// This allows us to add Metabox Specific Form Inputs from the constructing class using 'metabox_specific_form_inputs' function we add to that class.
		$this->metabox_specific_form_inputs = $metabox_specific_form_inputs;
	}

	/**
	 * Set Main Post Type
	 *
	 * Set the main post type
	 *
	 * @param string $main_post_type Get the main post type.
	 * @since 1.0
	 */
	public function set_main_post_type( $current_class = null, $main_post_type = null ) {
		if ( $main_post_type ) {
			$this->main_post_type = $main_post_type;
		} else {
			$this->main_post_type = isset( $current_class->main_post_type ) ? $current_class->main_post_type : 'post';
		}
	}

	/**
	 * Current Info Array
	 *
	 * Get the current info array.
	 *
	 * @since 1.0
	 */
	public function current_info_array() {

		if ( function_exists( 'get_current_screen' ) ) {
			// Current Info!
			$current_info['info'] = get_current_screen();

			// Current Base!
			$current_info['base'] = isset( $current_info['info']->base ) ? $current_info['info']->base : null;

			// Current Post type!
			$current_info['post_type'] = isset( $current_info['info'] ) && $this->main_post_type === $current_info['info']->post_type ? $current_info['info']->post_type : null;

			return $current_info;
		}
	}

	/**
	 * Add Submit Meta Box
	 *
	 * Add the metaboxes to our pages.
	 *
	 * @since 1.0
	 */
	public function add_submit_meta_box() {
		add_meta_box( 'submitdiv', esc_html__( 'Save Options', 'feed_them_social' ), array( $this, 'submit_meta_box' ), $this->hook_id, 'side', 'high' );
	}

	/**
	 * Submit Meta Box Callback
	 *
	 * @since 0.1.0
	 */
	public function submit_meta_box() {

		/* Reset URL */
		$reset_url = '#';

		?>
		<div id="submitpost" class="submitbox">

			<div id="major-publishing-actions">

				<?php
				// <div id="delete-action">
				// <a href=" echo esc_url( $reset_url ); " class="submitdelete deletion">Reset Settings</a>
				// </div><!-- #delete-action -->.
				?>

				<div id="publishing-action">
					<span class="spinner"></span>
					<input type="submit" value="Save" class="button button-primary button-large">
				</div>

				<div class="clear"></div>

			</div><!-- #major-publishing-actions -->

		</div><!-- #submitpost -->

		<?php
	}


	/**
	 * Metabox Tabs Menu
	 *
	 * Outputs the metabox tabs menu html
	 *
	 * @param string $current_info Array Info for the current page.
	 * @param array  $tabs_list Array List of tabs.
	 *
	 * @since 1.1.6
	 */
	public function metabox_tabs_menu( $current_info, $tabs_list ) {

		if ( $tabs_list ) {
			foreach ( $tabs_list['base_tabs'] as $base_key => $base_items ) {
				// If Base array key is equal to current base (page)!
				if ( $base_key === $current_info['base'] ) {
					?>
					<div class="tabs-menu-wrap" id="tabs-menu">
						<ul class="nav nav-tabs nav-append-content">
							<?php
							// Display the Tabs Menu Items that are in the base items list!
							foreach ( $tabs_list['tabs_list'] as $tab_key => $tab_item ) {
								if ( in_array( $tab_key, $base_items, true ) ) {
									?>
									<li class="tabbed <?php echo esc_attr( $tab_item['menu_li_class'] ); ?>">
										<a href="#<?php echo esc_attr( $tab_key ); ?>" data-toggle="tab"<?php echo isset( $tab_item['menu_a_class'] ) ? 'class="' . esc_attr( $tab_item['menu_a_class'] ) . '"' : ''; ?><?php echo isset( $tab_item['menu_aria_expanded'] ) ? ' aria-expanded="' . esc_attr( $tab_item['menu_aria_expanded'] ) . '"' : ''; ?>>
                                            <div class="fts-click-cover"></div>
                                            <div class="ft_icon">
                                                <?php if( 'combine_streams_feed' === $tab_key ){ ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 96C0 78.33 14.33 64 32 64H144.6C164.1 64 182.4 72.84 194.6 88.02L303.4 224H384V176C384 166.3 389.8 157.5 398.8 153.8C407.8 150.1 418.1 152.2 424.1 159L504.1 239C514.3 248.4 514.3 263.6 504.1 272.1L424.1 352.1C418.1 359.8 407.8 361.9 398.8 358.2C389.8 354.5 384 345.7 384 336V288H303.4L194.6 423.1C182.5 439.2 164.1 448 144.6 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H144.6L247 256L144.6 128H32C14.33 128 0 113.7 0 96V96z"/></svg>
                                                <?php }
                                                      if( 'instagram_feed' === $tab_key ){ ?><img src="/wp-content/plugins/feed-them-social/metabox/images/instagram-logo-admin.png" class="instagram-feed-type-image-tab">
                                                <?php } ?>
                                            </div>
											<span class="das-text"><?php echo esc_html( $tab_item['menu_a_text'] ); ?></span>
										</a>
									</li>
									<?php
								}
							}
							?>
						</ul>
					</div>
					<?php
				}
			}
		}
	}

	/**
	 * Display Metabox Content
	 *
	 * Display the Metabox content for each tab based on menu key 'cont_func'!
	 *
	 * @param array $tabs_list The tabs list.
	 * @param array $params The parameters.
	 * @since 1.1.6
	 */
	public function display_metabox_content( $current_class, $tabs_list ) {

        // Set and return Nonce Field by nonce name.
		wp_nonce_field( basename( __FILE__ ), $this->metabox_nonce_name );

		$current_info = $this->current_info_array();

		// Get Base of Current Screen.
		if ( isset( $current_info['base'] ) ) {
			?>
			<div class="ft-gallery-settings-tabs-meta-wrap">

				<div class="tabs" id="tabs">
					<?php
					// Tabs Menu!
					wp_kses(
						$this->metabox_tabs_menu( $current_info, $tabs_list ),
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
					)
					?>
					<div class="tab-content-wrap">
						<?php
						if ( $tabs_list['base_tabs'] ) {
							foreach ( $tabs_list['base_tabs'] as $base_key => $base_items ) {
								// If Base array key is equal to current base (page)!
								if ( $base_key === $current_info['base'] ) {
									foreach ( $base_items as $base_item ) {
										foreach ( $tabs_list['tabs_list'] as $tab_key => $tab_item ) {
											if ( isset( $tab_item['cont_func'] ) && $base_item === $tab_key ) {
												?>
												<div class="tab-pane <?php echo esc_attr( $tab_key ); ?>-tab-pane " id="<?php echo esc_attr( $tab_key ); ?>">

													<div id="<?php echo esc_attr( $tab_item['cont_wrap_id'] ); ?>" class="tab-content
												<?php
												if ( isset( $_GET['tab'] ) && $tab_key === $_GET['tab'] || ! isset( $_GET['tab'] ) ) {
													echo ' pane-active';
												}
												?>
										">
														<?php
                                                        //Dynamic Function to create a Tab using current class.
														call_user_func( array( $current_class, $tab_item['cont_func'] ) );
														?>
													</div> <!-- #tab-content -->

												</div><!-- /.tab-pane -->
												<?php

											}
										}
									}
								}
							}
						}
						?>
					</div>

					<div class="clear"></div>

				</div> <!-- #tabs close -->

				<div id="ftg-saveResult"></div>
			</div>
			<?php
		}
	}

	/**
	 * Options HTML Form
	 *
	 * Return metabox options form fields for output.
	 *
	 * @param array $section_info The section info.
	 * @param array $required_plugins The Required plugins.
	 * @param string $current_post_id Current post id.
	 * @return string
	 * @since @since 1.0.0
	 */
	public function options_html_form( $section_info, $required_plugins, $current_post_id = null ) {

        // If page set false otherwise this is a CPT!
        $is_cpt = true == $this->is_page ? false : true;

		// Get Old Settings Array if set.
		$saved_options = $this->options_functions->get_saved_options_array( $this->array_options_name, $is_cpt, $current_post_id);

        // Premium Required plugins.
		$prem_required_plugins = $this->settings_functions->fts_required_plugins();

		$section_required_prem_plugin = ! isset( $section_info['required_prem_plugin'] ) || isset( $section_info['required_prem_plugin'] ) && is_plugin_active( $prem_required_plugins[ $section_info['required_prem_plugin'] ]['plugin_url'] ) ? 'active' : '';

		// Start creation of fields for each Feed.
		$output = '<div id="' . esc_attr( $section_info['section_wrap_id'] ) . '" class="fts-section ' . $section_info['section_wrap_class'] . '">';

		// Section Title.
		$output .= isset( $section_info['section_title'] ) ? '<h3>' . $section_info['section_title'] . '</h3>' : '';

		// Errors Notice Div.
		$output .= $this->error_notice_html();

		// Section Options Wrap Class.
		$output .= isset( $section_info['options_wrap_class'] ) ? '<div class="'.$section_info['options_wrap_class'].'">' : '';

		// Create settings fields for Feed OPTIONS.
		foreach ( (array) $section_info['main_options'] as $option ) {
			if ( ! isset( $option['no_html'] ) || isset( $option['no_html'] ) && 'yes' !== $option['no_html'] ) {

				// Is a premium extension required?
				$required_plugin = ! isset( $option['req_plugin'] ) || isset( $option['req_plugin'] ) && is_plugin_active( $required_plugins[ $option['req_plugin'] ]['plugin_url'] ) ? true : false;

				// Sub option output START?
				$output .= isset( $option['sub_options'] ) ? '<div class="' . $option['sub_options']['sub_options_wrap_class'] . ( ! $required_plugin ? ' not-active-premium-fields' : '' ) . '">' . ( isset( $option['sub_options']['sub_options_title'] ) ? '<h3>' . $option['sub_options']['sub_options_title'] . '</h3>' : '' ) . ( isset( $option['sub_options']['sub_options_instructional_txt'] ) ? '<div class="instructional-text">' . $option['sub_options']['sub_options_instructional_txt'] . '</div>' : '' ) : '';

				$output .= isset( $option['grouped_options_title'] ) ? '<h3 class="sectioned-options-title">' . $option['grouped_options_title'] . '</h3>' : '';

				// Only on a few options generally.
				$output .= isset( $option['outer_wrap_class'] ) || isset( $option['outer_wrap_display'] ) ? '<div ' . ( isset( $option['outer_wrap_class'] ) ? 'class="' . $option['outer_wrap_class'] . '"' : '' ) . ' ' . ( isset( $option['outer_wrap_display'] ) && ! empty( $option['outer_wrap_display'] ) ? 'style="display:' . $option['outer_wrap_display'] . '"' : '' ) . '>' : '';
				// Main Input Wrap.
				$output .= '<div class="feed_them_social-admin-input-wrap ' . ( isset( $option['input_wrap_class'] ) ? $option['input_wrap_class'] : '' ) . '" ' . ( isset( $section_info['input_wrap_id'] ) ? 'id="' . $section_info['input_wrap_id'] . '"' : '' ) . '>';
				// Instructional Text.
				$output .= ! empty( $option['instructional-text'] ) && ! is_array( $option['instructional-text'] ) ? '<div class="instructional-text ' . ( isset( $option['instructional-class'] ) ? $option['instructional-class'] : '' ) . '">' . $option['instructional-text'] . '</div>' : '';

				if ( ! empty( $option['instructional-text'] ) && is_array( $option['instructional-text'] ) ) {
					foreach ( $option['instructional-text'] as $instructional_txt ) {
						// Instructional Text.
						$output .= '<div class="instructional-text ' . ( isset( $instructional_txt['class'] ) ? $instructional_txt['class'] : '' ) . '">' . $instructional_txt['text'] . '</div>';
					}
				}

				// Label Text.
				$output .= isset( $option['label'] ) && ! is_array( $option['label'] ) ? '<div class="feed_them_social-admin-input-label ' . ( isset( $option['label_class'] ) ? $option['label_class'] : '' ) . '">' . $option['label'] . '</div>' : '';

				if ( ! empty( $option['label'] ) && is_array( $option['label'] ) ) {
					foreach ( $option['label'] as $label_txt ) {
						// Label Text.
						$output .= '<div class="feed_them_social-admin-input-label ' . ( isset( $label_txt['class'] ) ? $label_txt['class'] : '' ) . '">' . $label_txt['text'] . '</div>';
					}
				}

				// Set Option name.
				$option_name = $option['name'];

				// Set Option ID.
				$option_id = $option['id'];

                // Use Saved Options or Default Value?
				$final_value = isset( $saved_options[ $option_name ] ) && ! empty( $saved_options[ $option_name ] ) ? $saved_options[ $option_name ] : $option['default_value'];

				// Do we need to output any Metabox Specific Form Inputs?
				if ( isset( $this->metabox_specific_form_inputs ) && true == $this->metabox_specific_form_inputs ) {
					// Set Current Params.
					$params = array(
						// 'This' Class object.
						//'this'         => $this->current_this,
						// Option Info.
						//'input_option' => $option,
					);

					$output .= call_user_func( array( $this->current_this, 'metabox_specific_form_inputs' ), $params );
				}

				if ( isset( $option['option_type'] ) ) {

                    $check_encrypted = '';
                    if( 'fts_instagram_custom_api_token' === $option_id ||
                        'fts_facebook_instagram_custom_api_token' === $option_id ||
                        'fts_facebook_custom_api_token' === $option_id ||
                        'fts_twitter_custom_access_token' === $option_id ||
                        'youtube_custom_api_token' === $option_id ||
                        'youtube_custom_access_token' === $option_id ) {
                        $data_protection = new Data_Protection();
                        $check_encrypted = false !== $data_protection->decrypt( $final_value ) ? 'encrypted' : $final_value;
                    }

					switch ( $option['option_type'] ) {
						// Input.
						case 'input':
							$output .= '<input ' . ( isset( $section_required_prem_plugin ) && 'active' !== $section_required_prem_plugin ? 'disabled ' : '' ) . 'type="' . $option['type'] . '" name="' . $option_name . '" id="' . $option_id . '" class="feed_them_social-admin-input ' . ( isset( $option['class'] ) ? $option['class'] : '' ) . '" placeholder="' . ( isset( $option['placeholder'] ) ? $option['placeholder'] : '' ) . '" value="' . $final_value . '"' . ( isset( $option['autocomplete'] ) ? ' autocomplete="' . $option['autocomplete'] . '"' : '' ) . ' data-token="' . $check_encrypted . '"  />';
							break;

						// Select.
						case 'select':
							$output .= '<select ' . ( isset( $section_required_prem_plugin ) && 'active' !== $section_required_prem_plugin ? 'disabled ' : '' ) . 'name="' . $option_name . '" id="' . $option_id . '"  class="feed_them_social-admin-input">';
							$i       = 0;
							foreach ( $option['options'] as $select_option ) {
								$output .= '<option value="' . $select_option['value'] . '" ' . ( ! empty( $final_value ) && $final_value === $select_option['value'] || empty( $final_value ) && 0 === $i ? 'selected="selected"' : '' ) . '>' . $select_option['label'] . '</option>';
								$i++;
							}
							$output .= '</select>';
							break;

						// Checkbox.
						case 'checkbox':
							$output .= '<input ' . ( isset( $section_required_prem_plugin ) && 'active' !== $section_required_prem_plugin ? 'disabled ' : '' ) . 'type="checkbox" name="' . $option_name . '" id="' . $option_id . '" ' . ( ! empty( $final_value ) && 'true' === $final_value ? ' checked="checked"' : '' ) . '/>';
							break;
					}
				}

				$output .= '<div class="clear"></div>';
				$output .= '</div><!--/feed_them_social-admin-input-wrap-->';

				$output .= isset( $option['outer_wrap_class'] ) || isset( $option['outer_wrap_display'] ) ? '</div>' : '';

				// Sub option output END?
				if ( isset( $option['sub_options_end'] ) ) {
					$output .= ! is_numeric( $option['sub_options_end'] ) ? '</div>' : '';
					// Multiple Div needed?
					if ( is_numeric( $option['sub_options_end'] ) ) {
						$x = 1;
						while ( $x <= $option['sub_options_end'] ) {
							$output .= '</div>';
							$x++;
						}
					}
				}
			}
		}

		//END Section Options Wrap Class.
		$output .= isset( $section_info['options_wrap_class'] ) ? '</div>' : '';

		$output .= '</div> <!--/Section Wrap Class END -->';

		return wp_kses(
			$output,
			array(
				'a'      => array(
					'href'  => array(),
                    'target'  => array(),
					'title' => array(),
					'class' => array(),
				),
				'div'    => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
				'select' => array(
					'name'  => array(),
					'class' => array(),
					'id'    => array(),
				),
				'option' => array(
					'value'    => array(),
					'selected' => array(),
				),
				'input'  => array(
					'value'       => array(),
					'type'        => array(),
					'class'       => array(),
					'id'          => array(),
					'placeholder' => array(),
					'name'        => array(),
					'checked'     => array(),
                    'data-token'  => array(),
				),
				'h3'     => array(
					'class' => array(),
				),
                'p'     => array(),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'small'  => array(),
                'span'      => array(
                    'class'   => array(),
                ),
			)
		);
	}

	/**
	 * Save Meta Box
	 *
     * Save or Update Metabox Options Array.
	 *
	 * @param string $cpt_id The post ID.
	 * @return array | string
	 * @since 1.0.0
	 */
	public function save_meta_box( $cpt_id ) {

        // Check if User can Manage Options.
        $this->options_functions->check_user_manage_options();

		// Verify Nonce by set nonce name.
		if ( isset( $_POST[$this->metabox_nonce_name] ) && !wp_verify_nonce( $_POST[ $this->metabox_nonce_name ], basename( __FILE__ ) ) ) {
			return wp_die( 'Cannot Verify This form!' );
		}

        // Testing
		//error_log( print_r( $_POST, true ) );
		//$this->options_functions->delete_options_array( $this->array_options_name, true, $cpt_id);
		//$this->options_functions->update_single_option( $this->array_options_name, 'feed_type', 'instagram-feed-type', true, $cpt_id );

        // Save/Update the Options array using the Array Option Name and Default Options Array.
		return $this->options_functions->update_options_array( $this->array_options_name, $this->default_options_array, true, $cpt_id );
	}

    /**
     * Tab Notice HTML
     *
     * Creates notice html for return.
     *
     * @since 3.0.0
     */
    public function error_notice_html() {
        // ft-gallery-notice happens in JS file.
        return '<div class="ft-gallery-notice"></div>';
    }

    /**
     * My FTS Plugin License
     *
     * Put in place to only show the Activate Plugin license if the input has a value
     *
     * @since 2.1.4
     */
    public function fts_plugin_license() {
        wp_enqueue_script( 'jquery' );
        ?>
        <style>.fts-license-master-form th {
                background: #f9f9f9;
                padding: 14px;
                border-bottom: 1px solid #ccc;
                margin: -14px -14px 20px;
                width: 100%;
                display: block
            }

            .fts-license-master-form .form-table tr {
                float: left;
                margin: 0 15px 15px 0;
                background: #fff;
                border: 1px solid #ccc;
                width: 30.5%;
                max-width: 350px;
                padding: 14px;
                min-height: 220px;
                position: relative;
                box-sizing: border-box
            }

            .fts-license-master-form .form-table td {
                padding: 0;
                display: block
            }

            .fts-license-master-form td input.regular-text {
                margin: 0 0 8px;
                width: 100%
            }

            .fts-license-master-form .edd-license-data[class*=edd-license-] {
                position: absolute;
                background: #fafafa;
                padding: 14px;
                border-top: 1px solid #eee;
                margin: 20px -14px -14px;
                min-height: 67px;
                width: 100%;
                bottom: 14px;
                box-sizing: border-box
            }

            .fts-license-master-form .edd-license-data p {
                font-size: 13px;
                margin-top: 0
            }

            .fts-license-master-form tr {
                display: none
            }

            .fts-license-master-form tr.fts-license-wrap {
                display: block
            }

            .fts-license-master-form .edd-license-msg-error {
                background: rgba(255, 0, 0, 0.49)
            }

            .fts-license-master-form tr.fts-license-wrap {
                display: block
            }

            .fts-license-master-form .edd-license-msg-error {
                background: #e24e4e !important;
                color: #FFF
            }

            .fts-license-wrap .edd-license-data p {
                color: #1e981e
            }

            .edd-license-msg-error p {
                color: #FFF !important
            }

            .feed-them_page_fts-license-page .button-secondary {
                display: none;
            }</style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if (jQuery('#feed_them_social_premium_license_key').val() !== '') {
                    jQuery('#feed_them_social_premium_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed_them_social_combined_streams_license_key').val() !== '') {
                    jQuery('#feed_them_social_combined_streams_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed-them-social-facebook-reviews_license_key').val() !== '') {
                    jQuery('#feed-them-social-facebook-reviews_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#fts_bar_license_key').val() !== '') {
                    jQuery('#fts_bar_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed_them_carousel_premium_license_key').val() !== '') {
                    jQuery('#feed_them_carousel_premium_license_key').next('label').find('.button-secondary').show()
                }
            });
        </script>
        <?php
    }
}
