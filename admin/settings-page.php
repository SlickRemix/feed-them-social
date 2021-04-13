<?php
/**
 * Settings Page
 *
 * Class Feed Them Social Settings Page
 *
 * @class    Settings_Page
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial;

/**
 * Class Settings_Page
 */
class Settings_Page {

	/**
	 * Load Function
	 *
	 * Load up all our actions and filters.
	 *
	 * @since 1.0.0
	 */
	public static function load() {
		$instance = new self();

		// Add Actions and Filters.
		$instance->add_actions_filters();
	}

	/**
	 * Add Action Filters
	 *
	 * Add Settings to our menu.
	 *
	 * @since 1.0.0
	 */
	public function add_actions_filters() {
		if ( is_admin() ) {
			// Adds setting page to Feed Them Social menu.
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		}
	}

	/**
	 * Settings_Page constructor.
	 */
	public function __construct() {}

	/**
	 *  Submenu Pages
	 *
	 * Admin Submenu buttons.
	 *
	 * @since 1.0.0
	 */
	public function add_submenu_page() {
		// Settings Page.
		add_submenu_page(
			'edit.php?post_type=fts',
			esc_html__( 'Settings', 'feed_them_social' ),
			esc_html__( 'Settings', 'feed_them_social' ),
			'manage_options',
			'ft-gallery-settings-page',
			array( $this, 'Settings_Page' )
		);
	}

	/**
	 * Settings Page
	 *
	 * Feed Them Social Settings Page
	 *
	 * @since 1.0.0
	 */
	public function Settings_Page() {
		// Feed Them Social Functions Class.
		// Enqueue JS Color JS.
		wp_enqueue_script( 'js_color', plugins_url( '/feed-them-social/metabox-settings/js/jscolor/jscolor.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, true );

		?>
		<div class="ft-gallery-main-template-wrapper-all">


			<div class="ft-gallery-settings-admin-wrap" id="theme-settings-wrap">
				<h2><img src="<?php echo esc_url( plugins_url( 'css/ft-gallery-logo.png', __FILE__ ) ); ?>" /></h2>
				<a class="buy-extensions-btn" href="<?php echo esc_url( 'https://www.slickremix.com/ft-gallery-documentation/' ); ?>" target="_blank"><?php esc_html_e( 'Setup Documentation', 'feed_them_social' ); ?></a>

				<div class="ft-gallery-settings-admin-input-wrap company-info-style ft-gallery-cache-wrap" style="padding-bottom: 0px;">
					<?php
					$ss_admin_bar_menu = get_option( 'ft-gallery-admin-bar-menu' );
					?>
					<div class="clear"></div>
				</div>
				<!--/ft-gallery-settings-admin-input-wrap-->

				<form method="post" class="ft-gallery-settings-admin-form wp-core-ui" action="options.php">
					<?php
					// get our registered settings from the gq theme functions.
					settings_fields( 'ft-gallery-settings' );
					?>

					<div class="ft-rename-options-wrap">
						<h4 style="margin-top: 10px;  border:none;padding: 0 0 20px 0;"><?php esc_html_e( 'Attachment File & Title Renaming [on upload]', 'feed_them_social' ); ?></h4>


						<?php esc_html_e( 'Use attachment renaming when importing/uploading attachments. This will overwrite original Filename.', 'feed_them_social' ); ?>
						<br />
						<strong><?php esc_html_e( 'Below are examples of what the attachment filenames and Titles will look like after uploading:', 'feed_them_social' ); ?></strong> <?php esc_html_e( '(Click "Save All Changes" to view Examples)', 'feed_them_social' ); ?>
						<br /><br />
						<input name="ft-gallery-use-attachment-naming" type="checkbox" id="ft-gallery-attachment-naming" value="1" <?php echo checked( '1', get_option( 'ft-gallery-use-attachment-naming' ) ); ?>/>
						<?php
						if ( '1' === get_option( 'ft-gallery-use-attachment-naming' ) ) {
							?>
							<strong><?php esc_html_e( 'Checked:', 'feed_them_social' ); ?></strong>
													  <?php
														esc_html_e( 'You are using Attachment File and Title Renaming when uploading each image.', 'feed_them_social' );

						} else {
							?>
							<strong><?php esc_html_e( 'Not Checked:', 'feed_them_social' ); ?></strong>
													  <?php
														esc_html_e( 'You are using the Original filename for Attachment names and Titles that is uploaded with each file.', 'feed_them_social' );
						}
						?>
						<br /><br />
						<div class="clear"></div>

						<div class="settings-sub-wrap">
							<h5><?php esc_html_e( 'Filename', 'feed_them_social' ); ?></h5>

							<label><input name="fts_attch_name_gallery_name" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_name_gallery_name' ) ); ?>/> <?php esc_html_e( 'Include Gallery Name', 'feed_them_social' ); ?>
								( Example: this-gallery-name )</label>

							<label><input name="fts_attch_name_post_id" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_name_post_id' ) ); ?>/> <?php esc_html_e( 'Include Gallery ID Number', 'feed_them_social' ); ?>
								( Example: 20311 )</label>

							<label><input name="fts_attch_name_date" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_name_date' ) ); ?>/> <?php esc_html_e( 'Include Date', 'feed_them_social' ); ?>
								( Example: 08-11-17 )</label>

							<label><input name="fts_attch_name_file_name" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_name_file_name' ) ); ?>/> <?php esc_html_e( 'Include File Name', 'feed_them_social' ); ?>
								( Example: my-image-name )</label>

							<label><input name="fts_attch_name_attch_id" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_name_attch_id' ) ); ?>/> <?php esc_html_e( 'Include Attachment ID', 'feed_them_social' ); ?>
								( Example: 1234 )</label>

							<div class="ft-gallery-attch-name-example">
								<?php
								$attch_name_output = '';
								// Attachment Filename Gallery Name.
								if ( '1' === get_option( 'fts_attch_name_gallery_name' ) ) {
									$attch_name_output .= '<span class="fts_attch_name_gallery_name">this-gallery-name</span>-';
								}
								// Attachment Filename Gallery ID.
								if ( '1' === get_option( 'fts_attch_name_post_id' ) ) {
									$attch_name_output .= '<span class="fts_attch_name_post_id">20311</span>-';
								}
								// Attachment Filename Date.
								if ( '1' === get_option( 'fts_attch_name_date' ) ) {
									$attch_name_output .= '<span class="fts_attch_name_date">08-11-17</span>-';
								}
								// Attachment Filename Date.
								if ( '1' === get_option( 'fts_attch_name_file_name' ) ) {
									$attch_name_output .= '<span class="fts_attch_name_file_name">my-image-name</span>-';
								}
								// Attachment Filename Date.
								if ( '1' === get_option( 'fts_attch_name_attch_id' ) ) {
									$attch_name_output .= '<span class="fts_attch_name_attch_id">1234</span>';
								}
								$final_output = $attch_name_output . '.jpg';
								// Output Filename Example.
								echo '<div class="clear"></div><div class="ftg-filename-renaming-example"><strong><em>Example Filename:</em></strong> ' . wp_kses(
									str_replace( '-.jpg', '.jpg', $final_output ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								) . '</div>';
								?>
							</div>
						</div>

						<div class="settings-sub-wrap">
							<h5><?php esc_html_e( 'Title', 'feed_them_social' ); ?></h5>

							<label><input name="fts_attch_title_gallery_name" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_title_gallery_name' ) ); ?>/> <?php esc_html_e( 'Include Gallery Name', 'feed_them_social' ); ?>
								( Example: This Gallery Name )</label>

							<label><input name="fts_attch_title_post_id" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_title_post_id' ) ); ?>/> <?php esc_html_e( 'Include Gallery ID Number', 'feed_them_social' ); ?>
								( Example: 20311 )</label>

							<label><input name="fts_attch_title_date" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_title_date' ) ); ?>/> <?php esc_html_e( 'Include Date', 'feed_them_social' ); ?>
								( Example: 08-11-17 )</label>

							<label><input name="fts_attch_title_file_name" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_title_file_name' ) ); ?>/> <?php esc_html_e( 'Include File Name', 'feed_them_social' ); ?>
								( Example: My Image Name )</label>

							<label><input name="fts_attch_title_attch_id" type="checkbox" value="1" <?php echo checked( '1', get_option( 'fts_attch_title_attch_id' ) ); ?>/> <?php esc_html_e( 'Include Attachment ID', 'feed_them_social' ); ?>
								( Example: 1234 )</label>

							<div class="clear"></div>

							<div class="ft-gallery-attch-name-example">
								<?php
								$attch_title_output = '';
								// Attachment Title Gallery Name.
								if ( '1' === get_option( 'fts_attch_title_gallery_name' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_gallery_name">This Gallery Name </span>';
								}
								// Attachment Title Gallery ID.
								if ( '1' === get_option( 'fts_attch_title_post_id' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_post_id">20311 </span>';
								}
								// Attachment Title Date.
								if ( '1' === get_option( 'fts_attch_title_date' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_date">08-11-17 </span>';
								}
								// Attachment Title File Name.
								if ( '1' === get_option( 'fts_attch_title_file_name' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_file_name">My Image Name </span>';
								}
								// Attachment Filename Date.
								if ( '1' === get_option( 'fts_attch_title_attch_id' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_attch_id">1234</span>';
								}

								if ( '1' !== get_option( 'fts_attch_title_gallery_name' ) && '1' !== get_option( 'fts_attch_title_post_id' ) && '1' !== get_option( 'fts_attch_title_date' ) && '1' !== get_option( 'fts_attch_title_file_name' ) && '1' !== get_option( 'fts_attch_title_attch_id' ) ) {
									$attch_title_output .= '<span class="fts_attch_title_attch_id">My Image Name</span>';
								}

								// Output Filename Example.
								$final_output = $attch_title_output;

								// Output Filename Example.
								echo '<div class="clear"></div><div class="ftg-title-renaming-example"><strong><em>Example Title:</em></strong> ' . wp_kses(
									$final_output,
									array(
										'span' => array(
											'class' => array(),
										),
									)
								) . '</div>';
								?>
							</div>

						</div>

						<div class="clear"></div>
						<h4><?php esc_html_e( 'Format Attachment Titles', 'feed_them_social' ); ?></h4>

						<?php $options = get_option( 'fts_format_attachment_titles_options' ); ?>

						<div class="settings-sub-wrap">
							<h5><?php esc_html_e( 'Remove Characters', 'feed_them_social' ); ?></h5>
							<label><input name="fts_format_attachment_titles_options[fts_fat_hyphen]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_hyphen'] ) ) {
									checked( '1', $options['fts_fat_hyphen'] );
							}
							?>
								> <?php esc_html_e( 'Hyphen', 'feed_them_social' ); ?> (-)</label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_underscore]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_underscore'] ) ) {
									checked( '1', $options['fts_fat_underscore'] );
							}
							?>
								> <?php esc_html_e( 'Underscore', 'feed_them_social' ); ?> (_)</label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_period]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_period'] ) ) {
									checked( '1', $options['fts_fat_period'] );
							}
							?>
								> <?php esc_html_e( 'Period', 'feed_them_social' ); ?> (.)</label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_tilde]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_title'] ) ) {
									checked( '1', $options['fts_fat_title'] );
							}
							?>
								> <?php esc_html_e( 'Tilde', 'feed_them_social' ); ?> (~)</label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_plus]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_plus'] ) ) {
									checked( '1', $options['fts_fat_plus'] );
							}
							?>
								> <?php esc_html_e( 'Plus', 'feed_them_social' ); ?> (+)</label>

							<div class="clear"></div>
							<div class="description"><?php esc_html_e( 'This is only for the image title the image file will still contain a hyphen - in the file name.', 'feed_them_social' ); ?></div>

						</div>


						<div class="settings-sub-wrap">
							<h5><?php esc_html_e( 'Capitalization Method', 'feed_them_social' ); ?></h5>

							<label><input name="fts_format_attachment_titles_options[fts_cap_options]" type="radio" value="cap_all"
							<?php
							if ( isset( $options['fts_cap_options'] ) ) {
								checked( 'cap_all', $options['fts_cap_options'] );}
							?>
							> <?php esc_html_e( 'Capitalize All Words', 'feed_them_social' ); ?>
							</label>

							<label><input name="fts_format_attachment_titles_options[fts_cap_options]" type="radio" value="cap_first"
							<?php
							if ( isset( $options['fts_cap_options'] ) ) {
								checked( 'cap_first', $options['fts_cap_options'] );}
							?>
							> <?php esc_html_e( 'Capitalize First Word Only', 'feed_them_social' ); ?>
							</label>

							<label><input name="fts_format_attachment_titles_options[fts_cap_options]" type="radio" value="all_lower"
							<?php
							if ( isset( $options['fts_cap_options'] ) ) {
								checked( 'all_lower', $options['fts_cap_options'] );}
							?>
							> <?php esc_html_e( 'All Words Lower Case', 'feed_them_social' ); ?>
							</label>

							<label><input name="fts_format_attachment_titles_options[fts_cap_options]" type="radio" value="all_upper"
							<?php
							if ( isset( $options['fts_cap_options'] ) ) {
								checked( 'all_upper', $options['fts_cap_options'] );}
							?>
							> <?php esc_html_e( 'All Words Upper Case', 'feed_them_social' ); ?>
							</label>

							<label><input name="fts_format_attachment_titles_options[fts_cap_options]" type="radio" value="dont_alter"
							<?php
							if ( isset( $options['fts_cap_options'] ) ) {
								checked( 'dont_alter', $options['fts_cap_options'] );}
							?>
							> <?php esc_html_e( 'Don\'t Alter (title text isn\'t modified in any way)', 'feed_them_social' ); ?>
							</label>
							<div class="clear"></div>
							<div class="description"><?php esc_html_e( 'Capitalization works on individual words separated by spaces. If the title contains NO spaces after formatting then only the first letter will be capitalized.', 'feed_them_social' ); ?></div>
						</div>
						<div class="settings-sub-wrap">

							<div class="clear"></div>

							<h5><?php esc_html_e( 'Misc. Options', 'feed_them_social' ); ?></h5>
							<label><input name="fts_format_attachment_titles_options[fts_fat_alt]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_alt'] ) ) {
									checked( '1', $options['fts_fat_alt'] );
							}
							?>
								> <?php esc_html_e( 'Add Title to \'Alternative Text\' Field?', 'feed_them_social' ); ?>
							</label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_caption]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_caption'] ) ) {
									checked( '1', $options['fts_fat_caption'] );
							}
							?>
								> <?php esc_html_e( 'Add Title to \'Caption\' Field?', 'feed_them_social' ); ?></label>

							<label><input name="fts_format_attachment_titles_options[fts_fat_description]" type="checkbox" value="1"
							<?php
							if ( isset( $options['fts_fat_description'] ) ) {
									checked( '1', $options['fts_fat_description'] );
							}
							?>
								> <?php esc_html_e( 'Add Title to \'Description\' Field?', 'feed_them_social' ); ?></label>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>
						<div class="settings-example-block" style="margin-top: 25px;">
							<strong>Below is an example of what the attachment Titles will look like after
								uploading:</strong> (Click "Save All Changes" to view Example)<br />
							<em>
								<small>NOTE: Title will come from Filename of uploaded attachment. You may still set
									a custom name for each photo after uploaded.
								</small>
							</em>
						</div>

						<div class="ft-gallery-attch-name-example">
							<?php
							$gallery_class = new Gallery();
							// Output Title Example.
							echo '<div class="ftg-filename-renaming-example"><strong><em>Example Title:</em></strong> ' . wp_kses(
								$gallery_class->fts_format_attachment_title( 'Gallery Image Title' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							) . '</div>';
							?>
						</div>
					</div>
					<div class="clear"></div>

					<h4><?php esc_html_e( 'Custom CSS Option', 'feed_them_social' ); ?></h4>
					<p class="special">
						<input name="ft-gallery-options-settings-custom-css-second" type="checkbox" id="ft-gallery-options-settings-custom-css-second" value="1" <?php echo checked( '1', get_option( 'ft-gallery-options-settings-custom-css-second' ) ); ?>/>
						<?php
						if ( '1' === get_option( 'ft-gallery-options-settings-custom-css-second' ) ) {
							?>
							<strong><?php esc_html_e( 'Checked: ', 'feed_them_social' ); ?></strong>
													  <?php
														esc_html_e( 'Custom CSS option is being used now.', 'feed_them_social' );
						} else {
							?>
							<strong><?php esc_html_e( 'Not Checked: ', 'feed_them_social' ); ?></strong>
													  <?php
														esc_html_e( 'You are using the default CSS.', 'feed_them_social' );
						}
						?>
					</p>

					<label class="toggle-custom-textarea-show button"><span><?php esc_html_e( 'Show', 'feed_them_social' ); ?></span><span class="toggle-custom-textarea-hide"><?php esc_html_e( 'Hide', 'feed_them_social' ); ?></span> <?php esc_html_e( 'custom CSS', 'feed_them_social' ); ?>
					</label>
					<div class="ft-gallery-custom-css-text"><?php esc_html_e( '<p>Add Your Custom CSS Code below.</p>', 'feed_them_social' ); ?></div>
					<textarea name="ft-gallery-settings-admin-textarea-css" class="ft-gallery-settings-admin-textarea-css" id="ft-gallery-main-wrapper-css-input"><?php echo esc_html( get_option( 'ft-gallery-settings-admin-textarea-css' ) ); ?></textarea>


					<h4><?php esc_html_e( 'Gallery Image Color & Size Options', 'feed_them_social' ); ?></h4>

					<p><label><?php esc_html_e( 'Title', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fts_text_color' ) ); ?>" />
					</p>

					<p><label><?php esc_html_e( 'Title Size', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_text_size" class="feed-them-social-admin-input" id="fb-text-size-input" placeholder="14px" value="<?php echo esc_attr( get_option( 'fts_text_size' ) ); ?>" />
					</p>

					<p><label><?php esc_html_e( 'Description', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_description_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-text-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fts_description_color' ) ); ?>" />
					</p>

					<p><label><?php esc_html_e( 'Description Size', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_description_size" class="feed-them-social-admin-input" id="fb-description-size-input" placeholder="14px" value="<?php echo esc_attr( get_option( 'fts_description_size' ) ); ?>" />
					</p>

					<p><label><?php esc_html_e( 'Link', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_link_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-input" placeholder="#222" value="<?php echo esc_attr( get_option( 'fts_link_color' ) ); ?>" />
					</p>

					<p>
						<label><?php esc_html_e( 'Link Hover', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_link_color_hover" class="feed-them-social-admin-input fb-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="fb-link-color-hover-input" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fts_link_color_hover' ) ); ?>" />
					</p>
					<p>
						<label><?php esc_html_e( 'Date', 'feed_them_social' ); ?></label>
						<input type="text" name="fts_post_time" class="feed-them-social-admin-input fb-date-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="ft-gallery-post-time" placeholder="#ddd" value="<?php echo esc_attr( get_option( 'fts_post_time' ) ); ?>" />
					</p>



					<div class="clear"></div>

					<div class="ft-gallery-date-settings-options-wrap">
						<h4><?php esc_html_e( 'Date Options for Images', 'feed_them_social' ); ?></h4>
						<?php

						isset( $fts_date_time_format ) ? $fts_date_time_format : '';
						isset( $fts_timezone ) ? $fts_timezone : '';
						isset( $fts_custom_date ) ? $fts_custom_date : '';
						isset( $fts_custom_time ) ? $fts_custom_time : '';
						$fts_date_time_format = get_option( 'ft-gallery-date-and-time-format' );
						$fts_timezone         = get_option( 'ft-gallery-timezone' );
						$fts_custom_date      = get_option( 'ft-gallery-date_format' );
						$fts_custom_time      = get_option( 'ft-gallery-time-format' );
						$fts_custom_timezone  = get_option( 'ft-gallery-timezone' ) ? get_option( 'ft-gallery-timezone' ) : 'America/Los_Angeles';
						date_default_timezone_set( $fts_custom_timezone );

						?>
						<div style="float:left; max-width:400px; margin-right:30px;">
							<h5><?php esc_html_e( 'Image Date Format', 'feed_them_social' ); ?></h5>

							<fieldset>
								<select id="ft-gallery-date-and-time-format" name="ft-gallery-date-and-time-format">
									<option value="l, F jS, Y \a\t g:ia"
									<?php
									if ( 'l, F jS, Y \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'l, F jS, Y \a\t g:ia' ) ); ?></option>
									<option value="F j, Y \a\t g:ia"
									<?php
									if ( 'F j, Y \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'F j, Y \a\t g:ia' ) ); ?></option>
									<option value="F j, Y g:ia"
									<?php
									if ( 'F j, Y g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'F j, Y g:ia' ) ); ?></option>
									<option value="F, Y \a\t g:ia"
									<?php
									if ( 'F, Y \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'F, Y \a\t g:ia' ) ); ?></option>
									<option value="M j, Y @ g:ia"
									<?php
									if ( 'M j, Y @ g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'M j, Y @ g:ia' ) ); ?></option>
									<option value="M j, Y @ G:i"
									<?php
									if ( 'M j, Y @ G:i' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'M j, Y @ G:i' ) ); ?></option>
									<option value="m/d/Y \a\t g:ia"
									<?php
									if ( 'm/d/Y \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'm/d/Y \a\t g:ia' ) ); ?></option>
									<option value="m/d/Y @ G:i"
									<?php
									if ( 'm/d/Y @ G:i' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'm/d/Y @ G:i' ) ); ?></option>
									<option value="d/m/Y \a\t g:ia"
									<?php
									if ( 'd/m/Y \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'd/m/Y \a\t g:ia' ) ); ?></option>
									<option value="d/m/Y @ G:i"
									<?php
									if ( 'd/m/Y @ G:i' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'd/m/Y @ G:i' ) ); ?></option>
									<option value="Y/m/d \a\t g:ia"
									<?php
									if ( 'Y/m/d \a\t g:ia' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'Y/m/d \a\t g:ia' ) ); ?></option>
									<option value="Y/m/d @ G:i"
									<?php
									if ( 'Y/m/d @ G:i' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php echo esc_html( date( 'Y/m/d @ G:i' ) ); ?></option>
									<option value="one-day-ago"
									<?php
									if ( 'one-day-ago' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php esc_html_e( '1 day ago', 'feed_them_social' ); ?></option>
									<option value="fts-custom-date"
									<?php
									if ( 'fts-custom-date' === $fts_date_time_format ) {
										echo 'selected="selected"';}
									?>
									><?php esc_html_e( 'Use Custom Date and Time Option Below', 'feed_them_social' ); ?></option>
								</select>
							</fieldset>

							<?php
							// Date translate.
							$fts_language_second  = get_option( 'fts_language_second', 'second' );
							$fts_language_seconds = get_option( 'fts_language_seconds', 'seconds' );
							$fts_language_minute  = get_option( 'fts_language_minute', 'minute' );
							$fts_language_minutes = get_option( 'fts_language_minutes', 'minutes' );
							$fts_language_hour    = get_option( 'fts_language_hour', 'hour' );
							$fts_language_hours   = get_option( 'fts_language_hours', 'hours' );
							$fts_language_day     = get_option( 'fts_language_day', 'day' );
							$fts_language_days    = get_option( 'fts_language_days', 'days' );
							$fts_language_week    = get_option( 'fts_language_week', 'week' );
							$fts_language_weeks   = get_option( 'fts_language_weeks', 'weeks' );
							$fts_language_month   = get_option( 'fts_language_month', 'month' );
							$fts_language_months  = get_option( 'fts_language_months', 'months' );
							$fts_language_year    = get_option( 'fts_language_year', 'year' );
							$fts_language_years   = get_option( 'fts_language_years', 'years' );
							$fts_language_ago     = get_option( 'fts_language_ago', 'ago' );
							?>

							<div class="custom_time_ago_wrap" style="display:none;">
								<h5><?php esc_html_e( 'Translate words for 1 day ago option.', 'feed_them_social' ); ?></h5>
								<label for="fts_language_second"><?php esc_html_e( 'second' ); ?></label>
								<input name="fts_language_second" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_second ) ); ?>" size="25" />
								<br />
								<label for="fts_language_seconds"><?php esc_html_e( 'seconds' ); ?></label>
								<input name="fts_language_seconds" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_seconds ) ); ?>" size="25" />
								<br />
								<label for="fts_language_minute"><?php esc_html_e( 'minute' ); ?></label>
								<input name="fts_language_minute" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_minute ) ); ?>" size="25" />
								<br />
								<label for="fts_language_minutes"><?php esc_html_e( 'minutes' ); ?></label>
								<input name="fts_language_minutes" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_minutes ) ); ?>" size="25" />
								<br />
								<label for="fts_language_hour"><?php esc_html_e( 'hour' ); ?></label>
								<input name="fts_language_hour" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_hour ) ); ?>" size="25" />
								<br />
								<label for="fts_language_hours"><?php esc_html_e( 'hours' ); ?></label>
								<input name="fts_language_hours" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_hours ) ); ?>" size="25" />
								<br />
								<label for="fts_language_day"><?php esc_html_e( 'day' ); ?></label>
								<input name="fts_language_day" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_day ) ); ?>" size="25" />
								<br />
								<label for="fts_language_days"><?php esc_html_e( 'days' ); ?></label>
								<input name="fts_language_days" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_days ) ); ?>" size="25" />
								<br />
								<label for="fts_language_week"><?php esc_html_e( 'week' ); ?></label>
								<input name="fts_language_week" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_week ) ); ?>" size="25" />
								<br />
								<label for="fts_language_weeks"><?php esc_html_e( 'weeks' ); ?></label>
								<input name="fts_language_weeks" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_weeks ) ); ?>" size="25" />
								<br />
								<label for="fts_language_month"><?php esc_html_e( 'month' ); ?></label>
								<input name="fts_language_month" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_month ) ); ?>" size="25" />
								<br />
								<label for="fts_language_months"><?php esc_html_e( 'months' ); ?></label>
								<input name="fts_language_months" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_months ) ); ?>" size="25" />
								<br />
								<label for="fts_language_year"><?php esc_html_e( 'year' ); ?></label>
								<input name="fts_language_year" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_year ) ); ?>" size="25" />
								<br />
								<label for="fts_language_years"><?php esc_html_e( 'years' ); ?></label>
								<input name="fts_language_years" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_years ) ); ?>" size="25" />
								<br />
								<label for="fts_language_ago"><?php esc_html_e( 'ago' ); ?></label>
								<input name="fts_language_ago" type="text" value="<?php echo esc_attr( stripslashes( $fts_language_ago ) ); ?>" size="25" />

							</div>
							<script>
								// change the feed type 'how to' message when a feed type is selected

								<?php if ( 'one-day-ago' === $fts_date_time_format ) { ?>
								jQuery('.custom_time_ago_wrap').show();
								<?php } ?>
								jQuery('#ft-gallery-date-and-time-format').change(function () {

									var ftsTimeAgo = jQuery("select#ft-gallery-date-and-time-format").val();
									if ('one-day-ago' === ftsTimeAgo ) {
										jQuery('.custom_time_ago_wrap').show();
									}
									else {
										jQuery('.custom_time_ago_wrap').hide();
									}

								});

							</script>
							<h5 style="border-top:0; margin-bottom:4px !important;"><?php esc_html_e( 'Custom Date and Time', 'feed_them_social' ); ?></h5>
							<div>
							<?php
							if ( '' !== $fts_custom_date || '' !== $fts_custom_time ) {
									echo esc_html( date( get_option( 'ft-gallery-custom-date' ) . ' ' . get_option( 'ft-gallery-custom-time' ) ) );
							}
							?>
								</div>
							<p style="margin:12px 0 !important;">
								<input name="ft-gallery-custom-date" style="max-width:105px;" class="fts-color-settings-admin-input" id="ft-gallery-custom-date" placeholder="<?php esc_html_e( 'Date', 'feed_them_social' ); ?>" value="<?php echo esc_attr( get_option( 'ft-gallery-custom-date' ) ); ?>" />
								<input name="ft-gallery-custom-time" style="max-width:75px;" class="fts-color-settings-admin-input" id="ft-gallery-custom-time" placeholder="<?php esc_html_e( 'Time', 'feed_them_social' ); ?>" value="<?php echo esc_attr( get_option( 'ft-gallery-custom-time' ) ); ?>" />
							</p>
							<div><?php esc_html_e( 'This will override the date and time format above.', 'feed_them_social' ); ?>
								<br /><a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php esc_html_e( 'Options for custom date and time formatting.', 'feed_them_social' ); ?></a>
							</div>
						</div>
						<div style="float:left; max-width:330px; margin-right: 30px;">
							<h5><?php esc_html_e( 'TimeZone', 'feed_them_social' ); ?></h5>
							<fieldset>
								<select id="ft-gallery-timezone" name="ft-gallery-timezone">
									<option value="Pacific/Midway" <?php echo 'Pacific/Midway' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-11:00) Midway Island, Samoa', 'feed-them-social' ); ?>
									</option>
									<option value="America/Adak" <?php echo 'America/Adak' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-10:00) Hawaii-Aleutian', 'feed-them-social' ); ?>
									</option>
									<option value="Etc/GMT+10" <?php echo 'Etc/GMT+10' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-10:00) Hawaii', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Marquesas" <?php echo 'Pacific/Marquesas' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-09:30) Marquesas Islands', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Gambier" <?php echo 'Pacific/Gambier' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-09:00) Gambier Islands', 'feed-them-social' ); ?>
									</option>
									<option value="America/Anchorage" <?php echo 'America/Anchorage' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-09:00) Alaska', 'feed-them-social' ); ?>
									</option>
									<option value="America/Anchorage" <?php echo 'America/Anchorage' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-09:00) Gambier Islands', 'feed-them-social' ); ?>
									</option>
									<option value="America/Ensenada" <?php echo 'America/Ensenada' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-08:00) Tijuana, Baja California', 'feed-them-social' ); ?>
									</option>
									<option value="Etc/GMT+8" <?php echo 'Etc/GMT+8' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-08:00) Pitcairn Islands', 'feed-them-social' ); ?>
									</option>
									<option value="America/Los_Angeles" <?php echo 'America/Los_Angeles' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-08:00) Pacific Time (US & Canada)', 'feed-them-social' ); ?>
									</option>
									<option value="America/Denver" <?php echo 'America/Denver' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-07:00) Mountain Time (US & Canada)', 'feed-them-social' ); ?>
									</option>
									<option value="America/Chihuahua" <?php echo 'America/Chihuahua' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'feed-them-social' ); ?>
									</option>
									<option value="America/Dawson_Creek" <?php echo 'America/Dawson_Creek' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-07:00) Arizona', 'feed-them-social' ); ?>
									</option>
									<option value="America/Belize" <?php echo 'America/Belize' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-06:00) Saskatchewan, Central America', 'feed-them-social' ); ?>
									</option>
									<option value="America/Cancun" <?php echo 'America/Cancun' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'feed-them-social' ); ?>
									</option>
									<option value="Chile/EasterIsland" <?php echo 'Chile/EasterIsland' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-06:00) Easter Island', 'feed-them-social' ); ?>
									</option>
									<option value="America/Chicago" <?php echo 'America/Chicago' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-06:00) Central Time (US & Canada)', 'feed-them-social' ); ?>
									</option>
									<option value="America/New_York" <?php echo 'America/New_York' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-05:00) Eastern Time (US & Canada)', 'feed-them-social' ); ?>
									</option>
									<option value="America/Havana" <?php echo 'America/Havana' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-05:00) Cuba', 'feed-them-social' ); ?>
									</option>
									<option value="America/Bogota" <?php echo 'America/Bogota' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-05:00) Bogota, Lima, Quito, Rio Branco', 'feed-them-social' ); ?>
									</option>
									<option value="America/Caracas" <?php echo 'America/Caracas' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:30) Caracas', 'feed-them-social' ); ?>
									</option>
									<option value="America/Santiago" <?php echo 'America/Santiago' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) Santiago', 'feed-them-social' ); ?>
									</option>
									<option value="America/La_Paz" <?php echo 'America/La_Paz' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) La Paz', 'feed-them-social' ); ?>
									</option>
									<option value="Atlantic/Stanley" <?php echo 'Atlantic/Stanley' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) Faukland Islands', 'feed-them-social' ); ?>
									</option>
									<option value="America/Campo_Grande" <?php echo 'America/Campo_Grande' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) Brazil', 'feed-them-social' ); ?>
									</option>
									<option value="America/Goose_Bay" <?php echo 'America/Goose_Bay' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) Atlantic Time (Goose Bay)', 'feed-them-social' ); ?>
									</option>
									<option value="America/Glace_Bay" <?php echo 'America/Glace_Bay' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-04:00) Atlantic Time (Canada)', 'feed-them-social' ); ?>
									</option>
									<option value="America/St_Johns" <?php echo 'America/St_Johns' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:30) Newfoundland', 'feed-them-social' ); ?>
									</option>
									<option value="America/Araguaina" <?php echo 'America/Araguaina' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) UTC-3', 'feed-them-social' ); ?>
									</option>
									<option value="America/Montevideo" <?php echo 'America/Montevideo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) Montevideo', 'feed-them-social' ); ?>
									</option>
									<option value="America/Miquelon" <?php echo 'America/Miquelon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) Miquelon, St. Pierre', 'feed-them-social' ); ?>
									</option>
									<option value="America/Godthab" <?php echo 'America/Godthab' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) Greenland', 'feed-them-social' ); ?>
									</option>
									<option value="America/Argentina/Buenos_Aires" <?php echo 'America/Argentina/Buenos_Aires' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) Buenos Aires', 'feed-them-social' ); ?>
									</option>
									<option value="America/Sao_Paulo" <?php echo 'America/Sao_Paulo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-03:00) Brasilia', 'feed-them-social' ); ?>
									</option>
									<option value="America/Noronha" <?php echo 'America/Noronha' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-02:00) Mid-Atlantic', 'feed-them-social' ); ?>
									</option>
									<option value="Atlantic/Cape_Verde" <?php echo 'Atlantic/Cape_Verde' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-01:00) Cape Verde Is.', 'feed-them-social' ); ?>
									</option>
									<option value="Atlantic/Azores" <?php echo 'Atlantic/Azores' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT-01:00) Azores', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Belfast" <?php echo 'Europe/Belfast' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT) Greenwich Mean Time : Belfast', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Dublin" <?php echo 'Europe/Dublin' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT) Greenwich Mean Time : Dublin', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Lisbon" <?php echo 'Europe/Lisbon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT) Greenwich Mean Time : Lisbon', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/London" <?php echo 'Europe/London' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT) Greenwich Mean Time : London', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Abidjan" <?php echo 'Africa/Abidjan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT) Monrovia, Reykjavik', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Amsterdam" <?php echo 'Europe/Amsterdam' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Belgrade" <?php echo 'Europe/Belgrade' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Algiers" <?php echo 'Africa/Algiers' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+01:00) West Central Africa', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Windhoek" <?php echo 'Africa/Windhoek' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+01:00) Windhoek', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Beirut" <?php echo 'Asia/Beirut' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Beirut', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Cairo" <?php echo 'Africa/Cairo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Cairo', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Gaza" <?php echo 'Asia/Gaza' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Gaza', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Blantyre" <?php echo 'Africa/Blantyre' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Harare, Pretoria', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Jerusalem" <?php echo 'Asia/Jerusalem' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Jerusalem', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Minsk" <?php echo 'Europe/Minsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Minsk', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Damascus" <?php echo 'Asia/Damascus' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+02:00) Syria', 'feed-them-social' ); ?>
									</option>
									<option value="Europe/Moscow" <?php echo 'Europe/Moscow' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+03:00) Moscow, St. Petersburg, Volgograd', 'feed-them-social' ); ?>
									</option>
									<option value="Africa/Addis_Ababa" <?php echo 'Africa/Addis_Ababa' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+03:00) Nairobi', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Tehran" <?php echo 'Asia/Tehran' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+03:30) Tehran', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Dubai" <?php echo 'Asia/Dubai' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+04:00) Abu Dhabi, Muscat', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Yerevan" <?php echo 'Asia/Yerevan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+04:00) Yerevan', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Kabul" <?php echo 'Asia/Kabul' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+04:30) Kabul', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Yekaterinburg" <?php echo 'Asia/Yekaterinburg' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+05:00) Ekaterinburg', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Tashkent" <?php echo 'Asia/Tashkent' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+05:00) Tashkent', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Kolkata" <?php echo 'Asia/Kolkata' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Katmandu" <?php echo 'Asia/Katmandu' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+05:45) Kathmandu', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Dhaka" <?php echo 'Asia/Dhaka' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+06:00) Astana, Dhaka', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Novosibirsk" <?php echo 'Asia/Novosibirsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+06:00) Novosibirsk', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Rangoon" <?php echo 'Asia/Rangoon' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+06:30) Yangon (Rangoon)', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Bangkok" <?php echo 'Asia/Bangkok' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+07:00) Bangkok, Hanoi, Jakarta', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Krasnoyarsk" <?php echo 'Asia/Krasnoyarsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+07:00) Krasnoyarsk', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Hong_Kong" <?php echo 'Asia/Hong_Kong' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Irkutsk" <?php echo 'Asia/Irkutsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+08:00) Irkutsk, Ulaan Bataar', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Perth" <?php echo 'Australia/Perth' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+08:00) Perth', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Eucla" <?php echo 'Australia/Eucla' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+08:45) Eucla', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Tokyo" <?php echo 'Asia/Tokyo' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+09:00) Osaka, Sapporo, Tokyo', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Seoul" <?php echo 'Asia/Seoul' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+09:00) Seoul', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Yakutsk" <?php echo 'Asia/Yakutsk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+09:00) Yakutsk', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Adelaide" <?php echo 'Australia/Adelaide' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+09:30) Adelaide', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Darwin" <?php echo 'Australia/Darwin' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+09:30) Darwin', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Brisbane" <?php echo 'Australia/Brisbane' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+10:00) Brisbane', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Hobart" <?php echo 'Australia/Hobart' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+10:00) Sydney', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Vladivostok" <?php echo 'Asia/Vladivostok' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+10:00) Vladivostok', 'feed-them-social' ); ?>
									</option>
									<option value="Australia/Lord_Howe" <?php echo 'Australia/Lord_Howe' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+10:30) Lord Howe Island', 'feed-them-social' ); ?>
									</option>
									<option value="Etc/GMT-11" <?php echo 'Etc/GMT-11' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+11:00) Solomon Is., New Caledonia', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Magadan" <?php echo 'Asia/Magadan' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+11:00) Magadan', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Norfolk" <?php echo 'Pacific/Norfolk' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+11:30) Norfolk Island', 'feed-them-social' ); ?>
									</option>
									<option value="Asia/Anadyr" <?php echo 'Asia/Anadyr' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+12:00) Anadyr, Kamchatka', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Auckland" <?php echo 'Pacific/Auckland' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+12:00) Auckland, Wellington', 'feed-them-social' ); ?>
									</option>
									<option value="Etc/GMT-12" <?php echo 'Etc/GMT-12' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Chatham" <?php echo 'Pacific/Chatham' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+12:45) Chatham Islands', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Tongatapu" <?php echo 'Pacific/Tongatapu' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+13:00) Nuku\'alofa', 'feed-them-social' ); ?>
									</option>
									<option value="Pacific/Kiritimati" <?php echo 'Pacific/Kiritimati' === $fts_timezone ? 'selected="selected"' : ''; ?>>
										<?php echo esc_html( '(GMT+14:00) Kiritimati', 'feed-them-social' ); ?>
									</option>
								</select>
							</fieldset>
						</div>
					</div>


					<div class="clear"></div>
					<div class="ft-gallery-date-settings-options-wrap">
						<h4><?php esc_html_e( 'Disable Magnific Popup CSS', 'feed_them_social' ); ?></h4>
						<p>
							<input name="fts_fix_magnific" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_fix_magnific" value="1" <?php echo checked( '1', get_option( 'fts_fix_magnific' ) ); ?>/> <?php esc_html_e( 'Check this if your theme is already loading the style sheet for the popup.', 'feed_them_social' ); ?>
						</p>

						<div class="clear"></div>

						<h4><?php esc_html_e( 'Disable Duplicate Gallery Option', 'feed_them_social' ); ?></h4>
						<p>
							<input name="fts_duplicate_post_show" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_duplicate_post_show" value="1" <?php echo checked( '1', get_option( 'fts_duplicate_post_show' ) ); ?>/> <?php esc_html_e( 'Check this if you already have a duplicate post plugin installed.', 'feed_them_social' ); ?>
						</p>


						<div class="clear"></div>
						<h4><?php esc_html_e( 'Admin Menu Bar Option', 'feed_them_social' ); ?></h4>
						<label><?php esc_html_e( 'Menu Bar', 'feed_them_social' ); ?></label>
						<select id="ft-gallery-admin-bar-menu" name="ft-gallery-admin-bar-menu">
							<option value="show-admin-bar-menu"
							<?php
							if ( 'show-admin-bar-menu' === $ss_admin_bar_menu ) {
								echo 'selected="selected"';}
							?>
							><?php esc_html_e( 'Show Admin Bar Menu', 'feed_them_social' ); ?></option>
							<option value="hide-admin-bar-menu"
							<?php
							if ( 'hide-admin-bar-menu' === $ss_admin_bar_menu ) {
								echo 'selected="selected"';}
							?>
							><?php esc_html_e( 'Hide Admin Bar Menu', 'feed_them_social' ); ?></option>
						</select>

						<div class="clear"></div>

						<div class="ft-gallery-date-settings-options-wrap">
							<h4><?php esc_html_e( 'Powered by Text', 'feed_them_social' ); ?></h4>
							<p>
								<input name="ft-gallery-powered-text-options-settings" class="ft-powered-by-settings-admin-input" type="checkbox" id="ft-gallery-powered-text-options-settings" value="1" <?php echo checked( '1', get_option( 'ft-gallery-powered-text-options-settings' ) ); ?>/>
								<?php
								if ( '1' === get_option( 'ft-gallery-powered-text-options-settings' ) ) {
									?>
									<strong><?php esc_html_e( 'Checked: ', 'feed_them_social' ); ?></strong> <?php esc_html_e( 'You are not showing the Powered by Logo in the popup.', 'feed_them_social' ); ?>
																  <?php
								} else {
									?>
									<strong><?php esc_html_e( 'Not Checked: ', 'feed_them_social' ); ?></strong><?php esc_html_e( 'The Powered by text will appear in the popup. Awesome! Thanks so much for sharing.', 'feed_them_social' ); ?>
																	  <?php
								}
								?>
							</p>
						</div>

							<div class="ft-gallery-woo-settings-options-wrap">

							<h4><?php esc_html_e( 'Woocommerce Options', 'feed_them_social' ); ?></h4>

							<?php if ( is_plugin_active( 'feed_them_social-premium/feed_them_social-premium.php' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
								<div class="settings-sub-wrap">

									<h5><?php esc_html_e( 'Disable Right Click', 'feed_them_social' ); ?></h5>

									<label><input name="fts_enable_right_click" type="checkbox" value="true" <?php echo checked( 'true', get_option( 'fts_enable_right_click' ) ); ?>/> <?php esc_html_e( 'This will disable the right click option on all pages of your website.', 'feed_them_social' ); ?>
									</label>

									<div class="clear" style="padding-top:15px"></div>
									<h5><?php esc_html_e( 'Product Creation', 'feed_them_social' ); ?></h5>

									<label><input name="fts_attch_prod_to_gallery_cat" type="checkbox" value="true" <?php echo checked( 'true', get_option( 'fts_attch_prod_to_gallery_cat' ) ); ?>/> <?php esc_html_e( 'Attach Product to a Category named after Gallery', 'feed_them_social' ); ?>
									</label>

									<div class="clear"></div>

									<h5 style="margin-top: 30px;"><?php esc_html_e( 'Add to Cart Button Functionality', 'feed_them_social' ); ?></h5>

									<?php $woo_options = get_option( 'fts_woo_add_to_cart' ) ? get_option( 'fts_woo_add_to_cart' ) : 0;   // print_r($woo_options) ?>

									<label><input name="fts_woo_add_to_cart[fts_woo_options]" type="radio" value="prod_page" <?php checked( 'prod_page', $woo_options['fts_woo_options'] ); ?>> <strong><?php esc_html_e( '(Default)', 'feed_them_social' ); ?></strong> <?php esc_html_e( 'Take Customers to product page. (Doesn\'t add product to cart)', 'feed_them_social' ); ?>
									</label>

									<label><input name="fts_woo_add_to_cart[fts_woo_options]" type="radio" value="cart_checkout" <?php checked( 'cart_checkout', $woo_options['fts_woo_options'] ); ?>> <?php esc_html_e( 'Take user directly to checkout. Useful for variable products.', 'feed_them_social' ); ?>
									</label>

									<label><input name="fts_woo_add_to_cart[fts_woo_options]" type="radio" value="add_cart" <?php checked( 'add_cart', $woo_options['fts_woo_options'] ); ?>> <?php esc_html_e( 'Add product to cart. (Adds product to cart but doesn\'t take them to checkout.) This will not work if your product has required variations.', 'feed_them_social' ); ?>
									</label>

									<label><input name="fts_woo_add_to_cart[fts_woo_options]" type="radio" value="add_cart_checkout" <?php checked( 'add_cart_checkout', $woo_options['fts_woo_options'] ); ?>> <?php esc_html_e( 'Add product to cart and take user directly to checkout. This will not work if your product has required variations.', 'feed_them_social' ); ?>
									</label>

									<div class="clear"></div>
								</div>

								</div>





								<?php
} else {
	echo '<div class="ft-gallery-premium-mesg">Please purchase <a href="https://www.slickremix.com/downloads/feed-them-social/" target="_blank">Feed Them Social Premium</a> for the Awesome additional features!</div>  ';
}
?>
							<div class="clear"></div>

						<input type="submit" class="ft-gallery-settings-admin-submit button button-primary button-larg" value="<?php esc_html_e( 'Save All Changes', 'feed_them_social' ); ?>" />

				</form>
			</div>
			<!--/ft-gallery-settings-admin-wrap-->
			<div class="clear"></div>
		</div><!--/ft-gallery-main-template-wrapper-all-->

		<h1 class="plugin-author-note"><?php esc_html_e( 'Plugin Authors Note', 'feed_them_social' ); ?></h1>
		<div class="fts-plugin-reviews">
			<div class="fts-plugin-reviews-rate">Feed Them Social was created by 2 Brothers, Spencer and Justin Labadie.
				That’s it, 2 people! We spend all our time creating and supporting our plugins. Show us some love if you
				like our plugin and leave a quick review for us, it will make our day!
				<a href="https://www.facebook.com/pg/SlickRemix/reviews/?ref=page_internal" target="_blank">Leave us a
					Review ★★★★★</a>
			</div>
			<div class="fts-plugin-reviews-support">If you're having troubles getting setup please contact us. We will
				respond within 24hrs, but usually within 1-6hrs.
				<a href="https://www.slickremix.com/support/" target="_blank">Create Support Ticket</a>
				<div class="fts-text-align-center">
					<a class="feed_them_social-admin-slick-logo" href="https://www.slickremix.com" target="_blank"></a>
				</div>
			</div>
		</div>

		<!-- These scripts must load in the footer of page -->
		<script>
			jQuery(document).ready(function () {
				jQuery(".toggle-custom-textarea-show").click(function () {
					jQuery('textarea#ft-gallery-main-wrapper-css-input').slideToggle('fast');
					jQuery('.toggle-custom-textarea-show span').toggle();
					jQuery('.ft-gallery-custom-css-text').toggle();
				});
			});
		</script>
		<?php
	}
}//end class
