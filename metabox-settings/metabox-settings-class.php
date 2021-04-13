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
 * Class Metabox_Settings
 *
 * @package feed_them_social
 */
class Metabox_Settings {

	/**
	 * Current This
	 *
	 * The $this variable data from where call Metabox_Settings is being constructed.
	 *
	 * @var array
	 */
	public $current_this = '';

	/**
	 * Holds the hook id
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $hook_id = '';

	/**
	 * Settings Page Name
	 *
	 * This is the page name set for the edit settings page (ie. page=template_settings_page) generally set in URL
	 *
	 * @var array
	 */
	public $settings_page_name = '';

	/**
	 * Main Post Type
	 *
	 * The post type to be checked
	 *
	 * @var string
	 */
	public $main_post_type = '';

	/**
	 * Is Page
	 *
	 * Is the class being loaded on a page?
	 *
	 * @var boolean
	 */
	public $is_page = '';

	/**
	 * Parent Post ID
	 * used to set Gallery ID
	 *
	 * @var string
	 */
	public $parent_post_id = '';

	/**
	 * Core Functions Class
	 * initiates Core Functions Class
	 *
	 * @var \feed_them_social\Core_Functions|string
	 */
	public $core_functions_class = '';

	/**
	 * Option Prefix
	 * Set for pages this way options have prefix so no options names get set the same. Set in Construct.
	 *
	 * @var string
	 */
	public $option_prefix = '';

	/**
	 * Specific Form Options
	 * This allows us to add Specific Metabox Inputs from the constructing class using '' function we add to that class.
	 *
	 * @var string
	 */
	private $metabox_specific_form_inputs = '';

	/**
	 * Saved Settings Array
	 * an array of settings to save when saving page
	 *
	 * @var string
	 */
	public $settings_array = array();

	/**
	 * Metabox_Settings constructor.
	 *
	 * Constructor.
	 *
	 * @param string $current_this Current this.
	 * @param string $settings_array All the settings.
	 * @param string $is_page What page.
	 * @since 1.0
	 */
	public function __construct( $current_this, $settings_array, $is_page = null ) {

		$this->core_functions_class = new Core_Functions();

		// Set Class Variables.
		$this->current_this = $current_this;

		// Set Settings Array.
		$this->set_settings_array( $settings_array );

		// Is Page.
		$this->is_page = $is_page;

		// Add Actions & Filters.
		$this->add_actions_filters();

		// Set Default main post type.
		$this->set_main_post_type();
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
			wp_register_style( 'slick-admin-page', plugins_url( 'feed-them-social/metabox-settings/css/admin-pages.css' ), array(), FTS_CURRENT_VERSION );
			// Enqueue Admin Page CSS.
			wp_enqueue_style( 'slick-admin-page' );

			// Enqueue Styles CSS.
			wp_register_style( 'slick-styles', plugins_url( 'feed-them-social/includes/cpt/css/styles.css' ), array(), FTS_CURRENT_VERSION );
			// Enqueue Admin Styles CSS.
			wp_enqueue_style( 'slick-styles' );

			// Register Metabox CSS.
			wp_register_style( 'slick-metabox', plugins_url( 'feed-them-social/metabox-settings/css/metabox.css' ), array(), FTS_CURRENT_VERSION );
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
			wp_enqueue_script( 'jquery-ui-progressbar' );

			// Enqueue JS Color JS.
			wp_enqueue_script( 'js_color', plugins_url( '/feed-them-social/metabox-settings/js/jscolor/jscolor.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );

			// Register Metabox JS.
			wp_register_script( 'slick-metabox-js', plugins_url( 'feed-them-social/metabox-settings/js/metabox.js' ), array(), FTS_CURRENT_VERSION, true );

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
			wp_register_script( 'slick-metabox-tabs', plugins_url( 'feed-them-social/metabox-settings/js/metabox-tabs.js' ), array(), FTS_CURRENT_VERSION, true );

			// Localize Metabox Tabs JS.
			wp_localize_script(
				'slick-metabox-tabs',
				'ftg_mb_tabs',
				array(
					'submit_msgs' => array(
						'saving_msg'  => __( 'Saving Options' ),
						'success_msg' => __( 'Settings Saved Successfully' ),
					),
				)
			);

			// Enqueue Metabox Tabs JS.
			wp_enqueue_script( 'slick-metabox-tabs' );

			// Register jQuery Nested Sortable JS.
			wp_register_script( 'jquery-nested-sortable-js', plugins_url( 'feed-them-social/metabox-settings/js/jquery.mjs.nestedSortable.js' ), array( 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable, ' ), FTS_CURRENT_VERSION, false );
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
			wp_register_script( 'updatefrombottom-admin-js', plugins_url( 'feed-them-social/metabox-settings/js/update-from-bottom.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );
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
	 * Set Option Prefix
	 *
	 * Set the option Prefix
	 *
	 * @param string $option_prefix Get the option prefix.
	 * @since 1.0
	 */
	public function set_option_prefix( $option_prefix ) {
		// Option Prefix. (needed if is_page = true). Posts don't need this because ID is used to set option name.
		$this->option_prefix = $option_prefix;
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
	public function set_main_post_type( $main_post_type = null ) {
		if ( $main_post_type ) {
			$this->main_post_type = $main_post_type;
		} else {
			$this->main_post_type = isset( $this->current_this->main_post_type ) ? $this->current_this->main_post_type : 'post';
		}
	}

	/**
	 * Set Settings Array
	 *
	 * Set the settings array
	 *
	 * @param array $settings_array Get the settings array.
	 * @since 1.0
	 */
	public function set_settings_array( $settings_array ) {
		// Settings Array.
		$this->saved_settings_array = is_array( $settings_array ) ? $settings_array : array();
	}

	/**
	 * Get Saved Settings Array
	 *
	 * Get the saved settings array.
	 *
	 * @param string $post_id The post ID.
	 * @return array
	 * @since 1.0
	 */
	public function get_saved_settings_array( $post_id = null ) {
		// Get Current info.
		$current_info = $this->current_info_array();
		// Saved Settings!
		$old_settings_page = get_option( $this->hook_id . '_settings_options' );
		$old_settings_post = get_post_meta( $post_id, $current_info['post_type'] . '_settings_options', true );

		// Get Old Settings Array if set.
		$old_settings = true == $this->is_page ? $old_settings_page : $old_settings_post;

		return isset( $old_settings ) && ! empty( $old_settings ) ? $old_settings : esc_html__( 'No Settings Saved.', 'feed_them_social' );
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
		add_meta_box( 'submitdiv', 'Save Options', array( $this, 'submit_meta_box' ), $this->hook_id, 'side', 'high' );
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
											<div class="ft_icon"></div>
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
	 * @param string $tabs_list The tabs list.
	 * @param string $params The parameters.
	 * @since 1.1.6
	 */
	public function display_metabox_content( $tabs_list, $params = null ) {

		wp_nonce_field( basename( __FILE__ ), 'slick-metabox-settings-options-nonce' );

		// Set Current Params.
		$params['this'] = $this->current_this;

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
														// call_user_func(array(feed_them_social()->gallery, $tab_item['cont_func']), $params).
														call_user_func( array( $this->current_this, $tab_item['cont_func'] ), $params );
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
	 * Settings HTML Form
	 *
	 * Used to return settings form fields output for Settings Options
	 *
	 * @param string $section_info The section info.
	 * @param string $required_plugins The Required plugins.
	 * @param string $current_post_id Current post id.
	 * @return string
	 * @since @since 1.0.0
	 */
	public function settings_html_form( $section_info, $required_plugins, $current_post_id = null ) {

		$current_info = $this->current_info_array();

		$old_settings_page = get_option( $this->hook_id . '_settings_options' );
		$old_settings_post = get_post_meta( $current_post_id, $current_info['post_type'] . '_settings_options', true );

		// Get Old Settings Array if set.
		$old_settings = true == $this->is_page ? $old_settings_page : $old_settings_post;

		$prem_required_plugins = $this->core_functions_class->fts_required_plugins();

		$section_required_prem_plugin = ! isset( $section_info['required_prem_plugin'] ) || isset( $section_info['required_prem_plugin'] ) && is_plugin_active( $prem_required_plugins[ $section_info['required_prem_plugin'] ]['plugin_url'] ) ? 'active' : '';

		// Start creation of fields for each Feed.
		$output = '<div class="ftg-section" class="' . $section_info['section_wrap_class'] . '">';

		// Section Title.
		$output .= isset( $section_info['section_title'] ) ? '<h3>' . $section_info['section_title'] . '</h3>' : '';

		// Happens in JS file.
		$this->core_functions_class->fts_tab_notice_html();

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

				// Set Option name. Use Prefix?
				// $option_name = isset( $this->option_prefix ) ? $this->option_prefix . $option['name'] : $option['name'];.
				$option_name = $option['name'];

				// Set Option ID. Use Prefix?
				// $option_id = isset( $this->option_prefix ) ? $this->option_prefix . $option['id'] : $option['id'];.
				$option_id = $option['id'];

				$final_value = isset( $old_settings[ $option_name ] ) && ! empty( $old_settings[ $option_name ] ) ? $old_settings[ $option_name ] : $option['default_value'];

				$default_option_types = array( 'input', 'select', 'checkbox' );
				$option_type          = $option['option_type'];

				// Do we need to output any Metabox Specific Form Inputs?
				if ( isset( $this->metabox_specific_form_inputs ) && true == $this->metabox_specific_form_inputs ) {
					// Set Current Params.
					$params = array(
						// 'This' Class object.
						'this'         => $this->current_this,
						// Option Info.
						'input_option' => $option,

					);

					$output .= call_user_func( array( $this->current_this, 'metabox_specific_form_inputs' ), $params );
				}

				if ( isset( $option['option_type'] ) ) {
					switch ( $option['option_type'] ) {
						// Input.
						case 'input':
							$output .= '<input ' . ( isset( $section_required_prem_plugin ) && 'active' !== $section_required_prem_plugin ? 'disabled ' : '' ) . 'type="' . $option['type'] . '" name="' . $option_name . '" id="' . $option_id . '" class="feed_them_social-admin-input ' . ( isset( $option['class'] ) ? $option['class'] : '' ) . '" placeholder="' . ( isset( $option['placeholder'] ) ? $option['placeholder'] : '' ) . '" value="' . $final_value . '"' . ( isset( $option['autocomplete'] ) ? ' autocomplete="' . $option['autocomplete'] . '"' : '' ) . ' />';
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

						/*
						 Case 'repeatable':
						echo '<a class="repeatable-add button" href="#">';
						_e('Add Another design', 'feed_them_social');
						echo '</a><ul id="' . $option['id'] . '-repeatable" class="custom_repeatable">';
						$i = 0;
						if ($meta) {
						foreach ($meta as $row) {
						echo '<li><span class="sort hndle">|||</span>
								 <textarea name="' . $option['id'] . '[' . $i . ']" id="' . $option['id'] . '">' . $row . '</textarea>
								 <a class="repeatable-remove button" href="#">-</a>
								 </li>';
						$i++;
						}
						} else {
						echo '<li><span class="sort hndle">|||</span>
							 <textarea name="' . $option['id'] . '[' . $i . ']" id="' . $option['id'] . '">' . $row . '</textarea>
							 <a class="repeatable-remove button" href="#">';
						_e('Delete this design', 'design-approval-system');
						echo '</a></li>';
						}
						echo '</ul>
						<span class="description">' . $option['desc'] . '</span>';
						break;
						*/

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

		$output .= '</div> <!--/Section Wrap Class END -->';

		return wp_kses(
			$output,
			array(
				'a'      => array(
					'href'  => array(),
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
				),
				'h3'     => array(
					'class' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'small'  => array(),
			)
		);
	}

	/**
	 *  Save Custom Meta Box
	 * Save Fields for Galleries
	 *
	 * @param string $post_id The post ID.
	 * @return string
	 * @since 1.0.0
	 */
	public function save_meta_box( $post_id ) {

		// delete_option( $this->hook_id . '_settings_options' );.
		$current_info = $this->current_info_array();

		// delete_post_meta( $post_id, $current_info['post_type'] . '_settings_options' );
		// Variable to check if anything was updated.
		$updated = false;

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized user' );
		}

		// Check Nonce!
		if ( ! isset( $_POST['slick-metabox-settings-options-nonce'] ) || ! wp_verify_nonce( $_POST['slick-metabox-settings-options-nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		$old_settings_page = get_option( $this->hook_id . '_settings_options' );
		$old_settings_post = get_post_meta( $post_id, $current_info['post_type'] . '_settings_options', true );

		// Get Old Settings Array if set.
		$old_settings = true == $this->is_page ? (array) $old_settings_page : (array) $old_settings_post;

		// Array of Settings to save. Use old settings if available otherwise use new array!
		$array_to_save = isset( $old_settings ) && ! empty( $old_settings ) ? $old_settings : array();

		foreach ( $this->saved_settings_array as $box_array ) {

			foreach ( $box_array as $box_key => $settings ) {

				if ( 'main_options' === $box_key ) {

					foreach ( $settings as $option ) {

						// Set Option name. Use Prefix? (commented line below is from prefix methodology.
						// $option_name = isset( $this->option_prefix ) ? $this->option_prefix . $option['name'] : $option['name'];.
						$option_name = isset( $option['name'] ) ? $option['name'] : '';

						$option_type = $option['option_type'];

						if ( 'checkbox' === $option_type ) {
							$new = isset( $_POST[ $option_name ] ) && 'false' !== $_POST[ $option_name ] ? 'true' : 'false';
						} else {
							$new = isset( $_POST[ $option_name ] ) && ! empty( $option_name ) ? wp_unslash( $_POST[ $option_name ] ) : '';
						}

						// If anything has changed update options!
						$array_to_save[ $option_name ] = is_array( $new ) ? $new : sanitize_text_field( $new );
					}
				}
			}
		}

		// If Post - Return Settings.
		if ( true == $this->is_page ) {
			// Update options for a page.
			update_option( $this->hook_id . '_settings_options', $array_to_save );

			// // If Page - then Safe Redirect to page we came from. To make the Coding Standards happy, we have to initialize this.
			if ( ! isset( $_POST['_wp_http_referer'] ) ) {
				$_POST['_wp_http_referer'] = wp_login_url();
			}

			// Sanitize the value of the $_POST collection for the Coding Standards.
			$url = sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) );

			wp_safe_redirect( urldecode( $url ) );
			exit;
		}

       // error_log( print_r( $array_to_save, true ) );

		// If not doing Page stuff Update options for a Post.
		update_post_meta( $post_id, $current_info['post_type'] . '_settings_options', $array_to_save );

		// REFACTOR NEEDED.
		if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) ) {
			include FEED_THEM_SOCIAL_PREMIUM_PLUGIN_FOLDER_DIR . 'includes/watermark/save.php';
		}

		return $array_to_save;
	}
}
