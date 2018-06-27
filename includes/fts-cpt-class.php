<?php
/**
 * FTS CPT Class
 *
 * This class is for creating a Custom Post Type for Feed Them Social.
 *
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @author   SlickRemix
 */

namespace feedthemsocial;
// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * FTS CPT
 * @package FeedThemSocial/Core
 */
class FTS_Custom_Post_Type {

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
    public $saved_settings_array = '';

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
     * @var \feed_them_gallery\Zip_Gallery|string
     */
    public $zip_gallery_class = '';

    /**
     * Gallery Options
     * initiates Gallery Options Class
     *
     * @var \feed_them_gallery\Zip_Gallery|string
     */
    public $gallery_options_class = '';


    /**
     * Gallery constructor.
     */
    public function __construct() {
        // Globalize:
        global $wp_version;

        $required_plugins = array();

        //Scripts
        add_action('admin_enqueue_scripts', array($this, 'fts_cpt_scripts'));
        //******************************************
        // Gallery Layout Opyions
        //******************************************
        $this->fts_cpt_options_class = new Feed_Metabox_Options();

        $this->saved_settings_array = $this->fts_cpt_options_class->all_gallery_options();

        //Register Gallery CPT
        add_action('init', array($this, 'feed_them_social_cpt'));
        //Response Messages
        add_filter('post_updated_messages', array($this, 'fts_cpt_updated_messages'));

        //Gallery List function
        add_filter('manage_fts_cpt_posts_columns', array($this, 'fts_cpt_set_custom_edit_columns'));
        add_action('manage_fts_cpt_posts_custom_column', array($this, 'fts_cpt_custom_edit_column'), 10, 2);

        //Change Button Text
        add_filter('gettext', array($this, 'fts_cpt_set_button_text'), 20, 3);
        //Add Meta Boxes
        add_action('add_meta_boxes', array($this, 'fts_cpt_add_metaboxes'));

        // Set local variables:
        $this->plugin_locale = MY_TEXTDOMAIN;
        // Set WordPress version:
        $this->wordpress_version = substr(str_replace('.', '', $wp_version), 0, 2);

        add_action('current_screen', array($this, 'fts_cpt_check_page'));

        //Save Meta Box Info
        add_action('save_post', array($this, 'fts_cpt_save_custom_meta_box'), 10, 2);

        if (get_option('fts_cpt_duplicate_post_show') == '') {

            add_action('admin_action_fts_cpt_duplicate_post_as_draft', array($this, 'fts_cpt_duplicate_post_as_draft'));
            add_filter('page_row_actions', array($this, 'fts_cpt_duplicate_post_link'), 10, 2);
            add_filter('fts_cpt_row_actions', array($this, 'fts_cpt_duplicate_post_link'), 10, 2);
            add_action('post_submitbox_start', array($this, 'fts_cpt_duplicate_post_add_duplicate_post_button'));

        }
    }

    /**
     * FTS Feed Tab Notice HTML
     *
     * creates notice html for return
     *
     * @since 1.0.0
     */
    function fts_cpt_tab_premium_msg() {
        echo '<div class="fts-cpt-premium-mesg">Please purchase, install and activate <a href="https://www.slickremix.com/downloads/feed-them-social/" target="_blank">Feed Them Social Premium</a> for these additional awesome features!</div>';
    }

    /**
     * FTS Feed Check Page
     *
     * What page are we on?
     *
     * @since 1.0.0
     */
    function fts_cpt_check_page() {
        $current_screen = get_current_screen();

        if (is_admin() && $current_screen->post_type == 'fts_cpt' && $current_screen->base == 'post') {

            if (isset($_GET['post'])) {
                $this->parent_post_id = $_GET['post'];
            }
            if (isset($_POST['post'])) {
                $this->parent_post_id = $_POST['post'];
            }
        }
    }

    /**
     * FTS Feed Get Gallery Options
     *
     * Get options set for a gallery
     *
     * @param $gallery_id
     * @return array
     * @since 1.0.0
     */
    public function fts_cpt_get_gallery_options($gallery_id) {

        $post_info = get_post($gallery_id['gallery_id']);

        // echo '<pre>';
        // print_r($post_info);
        // echo '</pre>';

        $options_array = array();

        //Basic Post Info
        $options_array['fts_cpt_image_id'] = isset($post_info->ID) ? $post_info->ID : 'This ID does not exist anymore';
        $options_array['fts_cpt_author'] = isset($post_info->post_author) ? $post_info->post_author : '';
        //   $options_array['fts_cpt_post_date'] = $post_info->post_date_gmt;
        $options_array['fts_cpt_post_title'] = isset($post_info->post_title) ? $post_info->post_title : '';
        //   $options_array['fts_cpt_post_alttext'] = $post_info->post_title;
        //   $options_array['fts_cpt_comment_status'] = $post_info->comment_status;


        foreach ($this->saved_settings_array as $box_array) {
            foreach ($box_array as $box_key => $settings) {
                if ($box_key == 'main_options') {
                    //Gallery Settings
                    foreach ($settings as $option) {
                        $option_name = !empty($option['name']) ? $option['name'] : '';
                        $option_default_value = !empty($option['default_value']) ? $option['default_value'] : '';

                        if (!empty($option_name)) {
                            $option_value = get_post_meta($gallery_id['gallery_id'], $option_name, true);
                            //Set value or use Default_value
                            $options_array[$option_name] = !empty($option_value) ? $option_value : $option_default_value;
                        }

                    }
                }
            }
        }

        return $options_array;
    }

    /**
     * FTS Custom Post Type
     *
     * Create Feed Them Social custom post type
     *
     * @since 1.0.0
     */
    public function feed_them_social_cpt() {
        $responses_cpt_args = array(
            'label' => __('Feed Them Social', 'feed-them-social'),
            'labels' => array(
                'menu_name' => __('Feeds', 'feed-them-social'),
                'name' => __('Feeds', 'feed-them-social'),
                'singular_name' => __('Feed', 'feed-them-social'),
                'add_new' => __('Add Feed', 'feed-them-social'),
                'add_new_item' => __('Add New Feed', 'feed-them-social'),
                'edit_item' => __('Edit Feed', 'feed-them-social'),
                'new_item' => __('New Feed', 'feed-them-social'),
                'view_item' => __('View Feed', 'feed-them-social'),
                'search_items' => __('Search Feeds', 'feed-them-social'),
                'not_found' => __('No Feeds Found', 'feed-them-social'),
                'not_found_in_trash' => __('No Feeds Found In Trash', 'feed-them-social'),
            ),

            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            //Display under FTS tab in admin menu
            'show_in_menu' => 'feed-them-settings-page',
            'show_in_nav_menus' => false,
            'exclude_from_search' => true,

            'capabilities' => array(
                'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
            ),
            'map_meta_cap' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'query_var' => 'fts_cpt',

            'menu_icon' => '',
            'supports' => array('title', 'revisions'),
            'order' => 'DESC',
            // Set the available taxonomies here
            //'taxonomies' => array('fts_cpt_topics')
        );
        register_post_type('fts_cpt', $responses_cpt_args);
    }


    /**
     * FTS Feed Updated Messages
     * Updates the messages in the admin area so they match plugin
     *
     * @param $messages
     * @return mixed
     * @since 1.0.0
     */
    public function fts_cpt_updated_messages($messages) {
        global $post, $post_ID;
        $messages['fts_cpt'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __('Feed updated.', 'feed-them-social'),
            2 => __('Custom field updated.', 'feed-them-social'),
            3 => __('Custom field deleted.', 'feed-them-social'),
            4 => __('Feed updated.', 'feed-them-social'),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf(__('Response restored to revision from %s', 'feed-them-social'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 => __('Feed created.', 'feed-them-social'),
            7 => __('Feed saved.', 'feed-them-social'),
            8 => __('Feed submitted.', 'feed-them-social'),
            9 => __('Feed scheduled for: <strong>%1$s</strong>.', 'feed-them-social'),
            // translators: Publish box date format, see http://php.net/date
            // date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => __('Feed draft updated.', 'feed-them-social'),
        );

        return $messages;
    }

    /**
     * FTS Feed Set Custom Edit Columns
     *
     * Sets the custom admin columns for gallery list page
     *
     * @param $columns
     * @return array
     * @since 1.0.0
     */
    function fts_cpt_set_custom_edit_columns($columns) {

        $new = array();

        foreach ($columns as $key => $value) {

            if ($key == 'title') {  // when we find the date column
                $new[$key] = $value;
                $new['feed_shortcode'] = __('Feed Shortcode', 'feed-them-social');
                $new['feed_type'] = __('Feed Type', 'feed-them-social');
            } else {
                $new[$key] = $value;
            }
        }

        return $new;
    }

    /**
     * FTS Feed Count Post Images
     * Return a count of images for our gallery list column.
     *
     * @return mixed
     * @since 1.0.0
     */
    public function fts_cpt_count_post_images($post_id) {
        $attachments = get_children(array(
            'post_parent' => $post_id,
            'post_mime_type' => 'image'
        ));

        $count = count($attachments);

        return $count;
    }

    /**
     * FT Galley Custom Edit Column
     * Put info in matching coloumns we set
     *
     * @param $column
     * @param $post_id
     * @since 1.0.0
     */
    function fts_cpt_custom_edit_column($column, $post_id) {
        switch ($column) {
            // display a thumbnail photo
            case 'feed_shortcode' :
                echo '<input value="[feed-them-social id=' . $post_id . ']" onclick="this.select()"/>';
                break;

            case 'feed_type' :
                echo '<input value="[feed-them-social id=' . $post_id . ']" onclick="this.select()"/>';
                break;
        }
    }

    /**
     * FTS Feed Set Button Text
     * Set Edit Post buttons for Feeds custom post type
     *
     * @param $translated_text
     * @param $text
     * @param $domain
     * @return mixed
     * @since 1.0.0
     */
    public function fts_cpt_set_button_text($translated_text, $text, $domain) {
        $post_id = isset($_GET['post']) ? $_GET['post'] : '';
        $custom_post_type = get_post_type($post_id);
        if (!empty($post_id) && $custom_post_type == 'fts_cpt_responses') {
            switch ($translated_text) {
                case 'Publish' :
                    $translated_text = __('Save Feed', 'feed-them-social');
                    break;
                case 'Update' :
                    $translated_text = __('Update Feed', 'feed-them-social');
                    break;
                case 'Save Draft' :
                    $translated_text = __('Save Feed Draft', 'feed-them-social');
                    break;
                case 'Edit Payment' :
                    $translated_text = __('Edit Feed', 'feed-them-social');
                    break;
            }
        }

        return $translated_text;
    }

    /**
     * FTS Feed Scripts
     *
     * Create Feed custom post type
     *
     * @since 1.0.0
     */
    public function fts_cpt_scripts() {

        global $id, $post;

        // Get current screen.
        $current_screen = get_current_screen();

        if (is_admin() && $current_screen->post_type == 'fts_cpt' && $current_screen->base == 'post') {

            // Set the post_id for localization.
            $post_id = isset($post->ID) ? $post->ID : (int)$id;

            // Image Uploader
            wp_enqueue_media(array(
                'post' => $post_id,
            ));
            add_filter('plupload_init', array($this, 'plupload_init'));
            // Updates the attachments when saving
            //  add_filter( 'wp_insert_post_data', array( $this, 'fts_cpt_sort_images_meta_save' ), 99, 2 );

            wp_enqueue_style('fts-cpt-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));
            //wp_enqueue_style('fts-cpt-popup', plugins_url('feed-them-social/includes/feeds/css/magnific-popup.css'));
            //wp_enqueue_script('fts-cpt-popup-js', plugins_url('feed-them-social/includes/feeds/js/magnific-popup.js'));
            wp_register_style('side_sup_settings_css', plugins_url('feed-them-social/admin/css/metabox.css'));
            wp_enqueue_style('side_sup_settings_css');

            //wp_register_script('jquery-nested-sortable', plugins_url('feed-them-social/admin/js/jquery.mjs.nestedSortable.js'), array('jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable, '));
            //wp_enqueue_script('jquery-nested-sortable');

            wp_enqueue_style('fts-cpt-admin-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');

            //wp_enqueue_script('jquery-ui-progressbar');  // the progress bar
            //  wp_register_script('Side-Sup-Sidebar-Builder', plugins_url('feed-them-social/admin/js/metabox.js'), 'jquery-ui-progressbar', 1.0, true);
            //wp_register_script('fts-cpt-metabox', plugins_url('feed-them-social/admin/js/metabox.js'));
            //wp_enqueue_script('fts-cpt-metabox');


            // Add buttons that appears at the bottom of pages to publish, update or go to top of page
            wp_enqueue_script('fts-updatefrombottom-admin-scripts', plugins_url('feed-them-social/feeds/js/update-from-bottom.js'), array('jquery'));

            # Translatable trings
            $js_data = array(
                'update' => __('Update', 'feed-them-social'),
                'publish' => __('Publish', 'feed-them-social'),
                'publishing' => __('Publishing...', 'feed-them-social'),
                'updating' => __('Updating...', 'feed-them-social'),
                'totop' => __('To top', 'feed-them-social'),
            );
            # Localize strings to javascript
            wp_localize_script('fts-updatefrombottom-admin-scripts', 'updatefrombottomParams', $js_data);

        } else {
            return;
        }
    }

    /**
     * Add Feed Meta Boxes
     *
     * Add metaboxes to the gallery
     *
     * @since 1.0.0
     */
    public
    function fts_cpt_add_metaboxes() {
        global $post;
        // Check we are using Feed Them Feed Custom Post type
        if ('fts_cpt' != $post->post_type) {
            return;
        }
        //Image Uploader and Gallery area in admin
        add_meta_box('fts-cpts-main-mb', __('Feed Settings', 'feed-them-gallery'), array($this, 'fts_cpt_main_meta_box'), 'fts_cpt', 'normal', 'high', null);
        //Link Settings Meta Box
        add_meta_box('fts-cpts-shortcode-side-mb', __('Feed Them Social Shortcode', 'feed-them-social'), array($this, 'fts_cpt_shortcode_meta_box'), 'fts_cpt', 'side', 'high', null);
    }

    /**
     * FTS Feed Format Bytes
     *
     * Creates a human readable size for return
     * @param $bytes
     * @param int $precision
     * @return float
     * @since 1.0.0
     */
    public
    function fts_cpt_format_bytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision);
    }


    /**
     * FTS Feed Uploader Meta Box
     *
     * Uploading functionality trigger. (Most of the code comes from media.php and handlers.js)
     *
     * @param $object
     * @since 1.0.0
     */
    public
    function fts_cpt_main_meta_box($object) {
        wp_nonce_field(basename(__FILE__), 'fts-cpts-settings-meta-box-nonce'); ?>

        <?php

        $gallery_id = isset($_GET['post']) ? $_GET['post'] : ''; ?>
        <div class="fts-cpt-settings-tabs-meta-wrap">
            <div class="tabs" id="tabs">
                <div class="tabs-menu-wrap" id="tabs-menu">
                    <label for="tab1" class="tab1 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_account') {
                        echo 'tab-active';
                    } elseif (!isset($_GET['tab'])) {
                        echo 'tab-active';
                    } ?>" id="ft_account">
                        <div class="ft_icon"></div>
                        <span class="das-text"><?php _e('Account', 'feed-them-social') ?></span>
                    </label>

                    <label for="tab2" class="tab2 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_facebook') {
                        echo 'tab-active';
                    } ?>" id="ft_layout">
                        <div class="ft_icon"></div>
                        <span class="das-text"><?php _e('Facebook', 'feed-them-social') ?></span>
                    </label>

                    <label for="tab3" class="tab3 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_instagram') {
                        echo ' tab-active';
                    } ?>" id="ft_colors">
                        <div class="ft_icon"></div>
                        <span class="das-text"><?php _e('Instagram', 'feed-them-social') ?></span>

                    </label>

                    <label for="tab4" class="tab4 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_combined') {
                        echo ' tab-active';
                    } ?>" id="ft_global" style="display: none;">
                        <div class="ft_icon"></div>
                        <span class="das-text"><?php _e('Combined Streams', 'feed-them-social') ?></span>
                    </label>

                </div>

                <div id="tab-content1" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_account' || !isset($_GET['tab'])) {
                    echo ' pane-active';
                } ?>">
                    <section>


                        <?php
                        //Happens in JS file
                        $this->fts_cpt_tab_notice_html(); ?>

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

                        <?php // echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['facebook'], null); ?>

                        <div class="clear"></div>
                    </section>

                </div> <!-- #tab-content1 -->

                <div id="tab-content2" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_facebook') {
                    echo ' pane-active';
                } ?>">

                    <?php echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['facebook'], null); ?>
                    <div class="clear"></div>
                    <div class="fts-cpt-note"><?php _e('Additional Global options available on the <a href="edit.php?post_type=fts_cpt&page=fts-cpt-settings-page">settings page</a>.', 'feed-them-social') ?></div>

                </div>

                <div id="tab-content3" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'fts_instagram') {
                    echo ' pane-active';
                } ?>">
                    <?php
                    echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['instagram'], null); ?>
                    <div class="clear"></div>

                    <div class="fts-cpt-note"><?php _e('Additional global color options available on the <a href="edit.php?post_type=fts_cpt&page=fts-cpt-settings-page">settings page</a>.', 'feed-them-social') ?></div>

                </div>

                <div id="tab-content4" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'ft_combined') {
                    echo ' pane-active';
                } ?>">
                    <?php

                    //If Premium add Functionality
                    if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                        echo '<section>' . $this->fts_cpt_tab_premium_msg() . '</section>';
                    }
                    ?>
                    <section>

                        <?php echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['combine_streams'], null); ?>

                    </section>
                    <div class="clear"></div>
                </div>

                <div id="tab-content5" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'ft_woo_commerce') {
                    echo ' pane-active';
                } ?>">

                    <?php

                    if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                        <section>
                            <?php $this->fts_cpt_tab_premium_msg(); ?>
                        </section>
                    <?php } ?>

                    <?php
                    //  echo '<pre>';
                    //  print_r(wp_prepare_attachment_for_js('21529'));
                    //  echo '</pre>';

                    echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['woocommerce'], null); ?>

                    <div class="tab-5-extra-options">

                        <div class="feed-them-social-admin-input-wrap ">
                            <div class="feed-them-social-admin-input-label"><?php _e('Single Image Model Product', 'feed-them-social'); ?></div>
                            <?php
                            if (is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                $gallery_to_woo_class = new Gallery_to_Woocommerce();
                                echo $gallery_to_woo_class->fts_cpt_image_to_woo_model_prod_select($this->parent_post_id);
                            }
                            ?>
                            </br><span class="tab-section-description"><small><?php _e('Select a Product that will be duplicated when creating a Woocommerce products for individual images. 1 image will turn 1 woo product. Saves time when creating variable product Example: Printable images that have different print sizes, material, ect...', 'feed-them-social'); ?></small></span>
                            <span class="tab-section-description"><a href="https://docs.woocommerce.com/document/variable-product/" target="_blank"><small><?php _e('Learn how to create a <strong>Variable product</strong> in Woocommerce.', 'feed-them-social'); ?></small></a> </span>
                        </div>
                        <div class="feed-them-social-admin-input-wrap ">
                            <div class="feed-them-social-admin-input-label"><?php _e('ZIP Model Product', 'feed-them-social'); ?></div>
                            <?php
                            if (is_plugin_active('woocommerce/woocommerce.php') && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                                echo $gallery_to_woo_class->fts_cpt_zip_to_woo_model_prod_select($this->parent_post_id);
                            }
                            ?>
                            </br><span class="tab-section-description"><small><?php _e('Select a Product that will be duplicated when creating a Woocommerce product for Gallery Digital ZIP. (Turns all images in Gallery into a ZIP for a Simple Virtual/Downloadable Woocommerce product.)', 'feed-them-social'); ?></small></span>
                            <span class="tab-section-description"><a href="https://docs.woocommerce.com/document/managing-products/#section-5" target="_blank"><small><?php _e('Learn how to create a <strong>Simple product</strong> in Woocommerce.', 'feed-them-social'); ?></small></a> </span>
                            <span class="tab-section-description"><small><?php _e('**NOTE** This Product must have options ', 'feed-them-social'); ?>
                                    <a href="https://docs.woocommerce.com/document/managing-products/#section-14" target="_blank"><?php _e('Virtual', 'feed-them-social'); ?></a><?php _e(' and ', 'feed-them-social'); ?>
                                    <a href="https://docs.woocommerce.com/document/managing-products/#section-15" target="_blank"><?php _e('Downloadable', 'feed-them-social'); ?></a> <?php _e('checked to appear in select option above. No Download link is needed in product though as it will be auto-filled in when Feed Them Social creates a new ZIP product.', 'feed-them-social'); ?></small></span>

                        </div>

                        <div class="clear"></div>

                        <div class="fts-cpt-note"><?php _e('Additional Global WooCommerce options available on the <a href="edit.php?post_type=fts_cpt&page=fts-cpt-settings-page">settings page</a>.', 'feed-them-social') ?></div>

                    </div>

                </div>

                <div id="tab-content7" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'ft_watermark') {
                    echo ' pane-active';
                } ?>">

                    <?php if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                        <section>
                            <?php $this->fts_cpt_tab_premium_msg(); ?>
                        </section>
                    <?php }

                    echo $this->fts_cpt_settings_html_form($this->parent_post_id, $this->saved_settings_array['watermark'], null); ?>

                    <div class="clear"></div>

                    <div class="fts-cpt-note"><?php _e('Please <a href="https://www.slickremix.com/my-account/#tab-support" target="_blank">create a ticket</a> if you are experiencing trouble and one of our team members will be happy to assist you.', 'feed-them-social') ?></div>

                </div>

                <div id="tab-content8" class="tab-content fts-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'ft_whcc') {
                    echo ' pane-active';
                } ?>">

                    <?php if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                        <section>
                            <?php $this->fts_cpt_tab_premium_msg(); ?>
                        </section>
                    <?php } else {

                        $whcc = new FT_Gallery_WHCC();

                        $mulit_data = array();

                        $mulit_data['access_token'] = 'https://sandbox.login.whcc.com/oauth?response_type=request_url&consumer_key=3CFB458C7BDCB0F76C2B&consumer_secret=Ug0d5Ugv298=&callback_url=http://sidebar-support.com/wp-admin/post.php?post=24748&action=edit&tab=ft_whcc';

                        //$mulit_data['whcc_account_info']  = 'https://sandbox.apps.whcc.com/oas/client?access_token=134436881391';

                        $whcc_response = $whcc->fts_cpt_get_json($mulit_data);

                        echo '<pre>';
                        print_r($whcc_response);

                        //print_r(json_decode($whcc_response['whcc_account_info'],true ));
                        echo '</pre>';
                    } ?>

                    <div class="clear"></div>

                    <div class="fts-cpt-note"><?php _e('Please <a href="https://www.slickremix.com/my-account/#tab-support" target="_blank">create a ticket</a> if you are experiencing trouble and one of our team members will be happy to assist you.', 'feed-them-social') ?></div>

                </div>

                <div class="clear"></div>

            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {

                //create hash tag in url for tabs
                //  jQuery('.post-type-fts_cpt').on('click', ".button-large", function () {
                //  var myURL = document.location;
                //  document.location = myURL + "&tab=" + jQuery(this).attr('id');
                //      $("#post").attr("action", "post.php/?post=18240&action=edit&tab=ft_layout");

                //  })

                //create hash tag in url for tabs
                jQuery('.fts-cpt-settings-tabs-meta-wrap #tabs').on('click', "label.tabbed", function () {
                    var myURL = document.location;
                    document.location = myURL + "&tab=" + jQuery(this).attr('id');

                })

                // facebook Super Gallery option
                jQuery('#facebook-custom-gallery').bind('change', function (e) {
                    if (jQuery('#facebook-custom-gallery').val() == 'yes') {
                        jQuery('.fts-super-facebook-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap').hide();
                    }
                });

                if (jQuery('#fts_cpt_popup').val() == 'no') {
                    jQuery('.ft-images-sizes-popup').hide();
                    // jQuery('.display-comments-wrap').show();

                }
                //Facebook Display Popup option
                jQuery('#fts_cpt_popup').bind('change', function (e) {
                    if (jQuery('#fts_cpt_popup').val() == 'yes') {
                        jQuery('.ft-images-sizes-popup').show();
                        // jQuery('.display-comments-wrap').show();

                    }
                    else {
                        jQuery('.ft-images-sizes-popup').hide();
                        //  jQuery('.display-comments-wrap').hide();
                    }
                });


                if (jQuery("#fts_cpt_watermark").val() == 'imprint') {
                    jQuery('.ft-watermark-hidden-options').show();
                    jQuery('.ft-watermark-overlay-options, .fts-cpt-watermark-opacity').hide();
                }


                if (jQuery('#fts_cpt_watermark').val() == 'overlay') {
                    jQuery('.ft-watermark-overlay-options, .fts-cpt-watermark-opacity').show();
                    jQuery('.ft-watermark-hidden-options').hide();
                }

                // facebook show load more options
                jQuery('#fts_cpt_watermark').bind('change', function (e) {
                    if (jQuery('#fts_cpt_watermark').val() == 'imprint') {

                        jQuery('.ft-watermark-hidden-options').show();
                        jQuery('.ft-watermark-overlay-options, .fts-cpt-watermark-opacity').hide();
                    }
                    if (jQuery('#fts_cpt_watermark').val() == 'overlay') {
                        jQuery('.ft-watermark-overlay-options, .fts-cpt-watermark-opacity').show();
                        jQuery('.ft-watermark-hidden-options').hide();
                    }

                });

                // show the duplicate image select box for those who want to duplicate the image before watermarking
                jQuery('#ft_watermark_image_-full').change(function () {
                    this.checked ? jQuery('.ft-watermark-duplicate-image').show() : jQuery('.ft-watermark-duplicate-image').hide();
                });
                //if page is loaded and box is checked we show the select box otherwise it is hidden with CSS
                if (jQuery('input#ft_watermark_image_-full').is(':checked')) {
                    jQuery('.ft-watermark-duplicate-image').show()
                }


                // facebook show load more options
                jQuery('#fts_cpt_load_more_option').bind('change', function (e) {
                    if (jQuery('#fts_cpt_load_more_option').val() == 'yes') {

                        if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
                            jQuery('.fts-facebook-load-more-options-wrap').show();
                        }
                        jQuery('.fts-facebook-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                    }
                });


                if (jQuery('#fts_cpt_load_more_option').val() == 'yes') {
                    jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').show();
                    jQuery('.fts-facebook-grid-options-wrap').show();
                }
                if (jQuery('#fts_cpt_grid_option').val() == 'yes') {
                    jQuery('.fts-facebook-grid-options-wrap').show();
                    jQuery(".feed-them-social-admin-input-label:contains('Center Facebook Container?')").parent('div').show();
                }


                if (jQuery('#fts_cpt_type').val() == 'post-in-grid' || jQuery('#fts_cpt_type').val() == 'gallery' || jQuery('#fts_cpt_type').val() == 'gallery-collage') {
                    jQuery('.fb-page-grid-option-hide').show();
                    if (jQuery('#fts_cpt_type').val() == 'gallery') {
                        jQuery('#fts_cpt_height').show();
                        jQuery('.fb-page-columns-option-hide').show();
                        jQuery('.ftg-hide-for-columns').hide();
                    }
                    else {
                        jQuery('.fts_cpt_height').hide();
                        jQuery('.fb-page-columns-option-hide').hide();
                        jQuery('.ftg-hide-for-columns').show();
                    }
                }
                else {
                    jQuery('.fb-page-grid-option-hide, .fts_cpt_height').hide();
                }

                // facebook show grid options
                jQuery('#fts_cpt_type').bind('change', function (e) {
                    if (jQuery('#fts_cpt_type').val() == 'post-in-grid' || jQuery('#fts_cpt_type').val() == 'gallery' || jQuery('#fts_cpt_type').val() == 'gallery-collage') {
                        jQuery('.fb-page-grid-option-hide').show();
                        if (jQuery('#fts_cpt_type').val() == 'gallery') {
                            jQuery('#fts_cpt_height').show();
                            jQuery('.fb-page-columns-option-hide').show();
                            jQuery('.ftg-hide-for-columns').hide();
                        }
                        else {
                            jQuery('.fts_cpt_height').hide();
                            jQuery('.fb-page-columns-option-hide').hide();
                            jQuery('.ftg-hide-for-columns').show();
                        }
                    }
                    else {
                        jQuery('.fb-page-grid-option-hide').hide();
                    }


                });

            });
        </script>

        <div class="clear"></div>
        <?php
        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
            'container' => 'plupload-upload-ui', // will be adjusted per uploader
            'drop_element' => 'drag-drop-area', // will be adjusted per uploader
            'file_data_name' => 'async-upload', // will be adjusted per uploader
            'multiple_queues' => true,
            'max_file_size' => wp_max_upload_size() . 'b',
            'url' => admin_url('admin-ajax.php'),
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multi_selection' => false, // will be added per uploader
            // additional post data to send to our ajax hook
            'multipart_params' => array(
                '_ajax_nonce' => "", // will be added per uploader
                'action' => 'plupload_action', // the ajax action name
                'postID' => $this->parent_post_id,
                'imgid' => 0 // will be added per uploader
            )
        );
        ?>
        <script type="text/javascript">
            var base_plupload_config =<?php echo json_encode($plupload_init); ?>;
        </script>
        <?php
    }

    /**
     * FTS Feed Tab Notice HTML
     *
     * creates notice html for return
     *
     * @since 1.0.0
     */
    function fts_cpt_tab_notice_html() {
        echo '<div class="fts-cpt-notice"></div>';
    }


    /**
     * FTS Feed Create Thumb
     *
     * Create a 150x150 thumbnail for our gallery edit page
     *
     * @param $image_source
     * @since 1.0.0
     */
    function fts_cpt_create_thumb($image_source) {
        $image = $image_source;
        // error_log($image_source . ' Full FILE NAME WITH HTTP<br/><br/>');
        $instance_common = new FTGallery_Create_Image();
        $force_overwrite = true;
        // Generate the new cropped gallery image.
        $instance_common->resize_image($image, '150', '150', false, 'c', '100', false, null, $force_overwrite);
    }

    /**
     * FTS Feed Generate new Attachment Name
     *
     * Generates a new attachment name (used in upload action)
     *
     * @param $gallery_id
     * @param $attachment_ID
     * @since 1.0.0
     */
    function fts_cpt_generate_new_attachment_name($gallery_id, $attachment_ID) {
        $final_title = '';
        //Include Gallery Title
        if (get_option('fts_cpt_attch_title_gallery_name') == '1') {
            $final_title .= get_the_title($gallery_id) . ' ';
        }
        //Include Gallery ID
        if (!empty($gallery_id) && get_option('fts_cpt_attch_title_post_id') == '1') {
            $final_title .= $gallery_id . ' ';
        }
        //include Date Uploaded
        if (isset($_POST['postID']) && get_option('fts_cpt_attch_title_date') == '1') {
            $final_title .= date_i18n('F jS, Y') . ' ';
        }

        $this->fts_cpt_format_attachment_title($final_title . $attachment_ID, $attachment_ID, 'true');
    }

    /**
     * FTS Feed Rename Attachment
     *
     * Renames attachment (used for File Renamin setting option)
     *
     * @param $gallery_id
     * @param $attachment_ID
     * @since 1.0.0
     */
    function fts_cpt_rename_attachment($gallery_id, $attachment_ID) {

        $file = get_attached_file($attachment_ID);
        $path = pathinfo($file);

        $final_filename = '';

        //Include Gallery Title
        if (get_option('fts_cpt_attch_name_gallery_name') == '1') {
            $final_filename .= get_the_title($gallery_id) . '-';
        }
        //Include Gallery ID
        if (!empty($gallery_id) && get_option('fts_cpt_attch_name_post_id') == '1') {
            $final_filename .= $gallery_id . '-';
        }
        //include Date Uploaded
        if (isset($_POST['postID']) && get_option('fts_cpt_attch_name_date') == '1') {
            $final_filename .= date_i18n('F jS, Y') . '-';
        }

        $final_filename = sanitize_file_name($final_filename . $attachment_ID);

        $newfile = $path['dirname'] . '/' . $final_filename . '.' . $path['extension'];

        rename($file, $newfile);
        update_attached_file($attachment_ID, $newfile);
    }


    /**
     * FTS Feed Shortcode Meta Box
     *
     * FTS Feed copy & paste shortcode input box
     *
     * @param $object
     * @since 1.0.0
     */
    public
    function fts_cpt_shortcode_meta_box($object) {
        $meta_box = '<div class="fts-cpt-meta-wrap">';

        $gallery_id = isset($_GET['post']) ? $_GET['post'] : '';

        $screen = get_current_screen();

        if ($screen->parent_file == 'edit.php?post_type=fts_cpt' && $screen->action == 'add') {
            $meta_box .= '<p>';
            $meta_box .= '<label> ' . __('Save or Publish this Gallery to be able to copy this Gallery\'s Shortcode.', 'feed-them-social') . '</label>';
            //$meta_box .= '<input readonly="readonly" disabled value="[feed-them-social id=' . $gallery_id . ']"/>';
            $meta_box .= '</p>';
        } else {
            //Copy Shortcode
            $meta_box .= '<p>';
            $meta_box .= '<label> ' . __('Copy and Paste this shortcode to any page, post or widget.', 'feed-them-social') . '</label>';
            $meta_box .= '<input readonly="readonly" value="[feed-them-social id=' . $gallery_id . ']" onclick="this.select();"/>';
            $meta_box .= '</p>';
        }

        $meta_box .= '</div>';
        // ECHO MetaBox
        echo $meta_box;
    }

    /**
     * FTS Feed Settings HTML Form
     *
     * Used to return settings form fields output for Gallery Options
     *
     * @param $gallery_id
     * @param $section_info
     * @param $required_plugins
     * @return string
     * @since @since 1.0.0
     */
    function fts_cpt_settings_html_form($gallery_id, $section_info, $required_plugins) {
        $output = '';

        //$prem_required_plugins = $this->gallery_options_class->fts_cpt_required_plugins();

        $section_required_prem_plugin = !isset($section_info['required_prem_plugin']) || isset($section_info['required_prem_plugin']) && is_plugin_active($prem_required_plugins[$section_info['required_prem_plugin']]['plugin_url']) ? 'active' : '';

        //Start creation of fields for each Feed
        $output .= '<section class="' . $section_info['section_wrap_class'] . '">';

        //Section Title
        $output .= isset($section_info['section_title']) ? '<h3>' . $section_info['section_title'] . '</h3>' : '';

        //Happens in JS file
        $this->fts_cpt_tab_notice_html();

        //Create settings fields for Feed OPTIONS
        foreach ($section_info['main_options'] as $option) if (!isset($option['no_html']) || isset($option['no_html']) && $option['no_html'] !== 'yes') {

            //Is a premium extension required?
            $required_plugin = !isset($option['req_plugin']) || isset($option['req_plugin']) && is_plugin_active($required_plugins[$option['req_plugin']]['plugin_url']) ? true : false;
            $or_required_plugin = isset($option['or_req_plugin']) && is_plugin_active($required_plugins[$option['or_req_plugin']]['plugin_url']) ? true : false;
            $or_required_plugin_three = isset($option['or_req_plugin_three']) && is_plugin_active($required_plugins[$option['or_req_plugin_three']]['plugin_url']) ? true : false;

            //Sub option output START?
            $output .= isset($option['sub_options']) ? '<div class="' . $option['sub_options']['sub_options_wrap_class'] . (!$required_plugin ? ' not-active-premium-fields' : '') . '">' . (isset($option['sub_options']['sub_options_title']) ? '<h3>' . $option['sub_options']['sub_options_title'] . '</h3>' : '') . (isset($option['sub_options']['sub_options_instructional_txt']) ? '<div class="instructional-text">' . $option['sub_options']['sub_options_instructional_txt'] . '</div>' : '') : '';

            $output .= isset($option['grouped_options_title']) ? '<h3 class="sectioned-options-title">' . $option['grouped_options_title'] . '</h3>' : '';

            //Only on a few options generally
            $output .= isset($option['outer_wrap_class']) || isset($option['outer_wrap_display']) ? '<div ' . (isset($option['outer_wrap_class']) ? 'class="' . $option['outer_wrap_class'] . '"' : '') . ' ' . (isset($option['outer_wrap_display']) && !empty($option['outer_wrap_display']) ? 'style="display:' . $option['outer_wrap_display'] . '"' : '') . '>' : '';
            //Main Input Wrap
            $output .= '<div class="feed-them-social-admin-input-wrap ' . (isset($option['input_wrap_class']) ? $option['input_wrap_class'] : '') . '" ' . (isset($section_info['input_wrap_id']) ? 'id="' . $section_info['input_wrap_id'] . '"' : '') . '>';
            //Instructional Text
            $output .= !empty($option['instructional-text']) && !is_array($option['instructional-text']) ? '<div class="instructional-text ' . (isset($option['instructional-class']) ? $option['instructional-class'] : '') . '">' . $option['instructional-text'] . '</div>' : '';

            if (!empty($option['instructional-text']) && is_array($option['instructional-text'])) {
                foreach ($option['instructional-text'] as $instructional_txt) {
                    //Instructional Text
                    $output .= '<div class="instructional-text ' . (isset($instructional_txt['class']) ? $instructional_txt['class'] : '') . '">' . $instructional_txt['text'] . '</div>';
                }
            }

            //Label Text
            $output .= isset($option['label']) && !is_array($option['label']) ? '<div class="feed-them-social-admin-input-label ' . (isset($option['label_class']) ? $option['label_class'] : '') . '">' . $option['label'] . '</div>' : '';

            if (!empty($option['label']) && is_array($option['label'])) {
                foreach ($option['label'] as $label_txt) {
                    //Label Text
                    $output .= '<div class="feed-them-social-admin-input-label ' . (isset($label_txt['class']) ? $label_txt['class'] : '') . '">' . $label_txt['text'] . '</div>';
                }
            }

            //Post Meta option (non-global)
            $input_value = get_post_meta($gallery_id, $option['name'], true);
            //Post Meta Global checkbox Option
            $global_value = get_post_meta($gallery_id, $this->global_prefix . $option['name'], true);
            //Actual Global Option
            $get_global_option = get_option($this->global_prefix . $option['name']);

            if ($global_value && $global_value == 'true') {
                if (isset($get_global_option)) {
                    $final_value = !empty($get_global_option) ? $get_global_option : !isset($option['default_value']) ? $option['default_value']: '';
                }
            } else {
                $final_value = !empty($input_value) || !isset($input_value) ? $input_value :  !isset($option['default_value']) && !empty($option['default_value']) ? $option['default_value']: '';
            }
            //Post Meta option (non-global)
            $input_value = get_post_meta($gallery_id, $option['name'], true);
            //Post Meta Global checkbox Option
            $global_value = get_post_meta($gallery_id, $this->global_prefix . $option['name'], true);
            //Actual Global Option
            $get_global_option = get_option($this->global_prefix . $option['name']);

            if ($global_value && $global_value == 'true') {
                if (isset($get_global_option)) {
                    $final_value = !empty($get_global_option) ? $get_global_option : !isset($option['default_value']) && !empty($option['default_value'])  ? $option['default_value']: '';
                }
            } else {
                $final_value = !empty($input_value) || !isset($input_value) ? $input_value : !isset($option['default_value']) && !empty($option['default_value'])  ? $option['default_value']: '';
            }
            $input_option = $option['option_type'];

            //$gallery_class = new Gallery();
            $gallery_id = isset($_GET['post']) ? $_GET['post'] : '';
            //$gallery_options_returned = $gallery_class->fts_cpt_get_gallery_options_rest($gallery_id);

            if (isset($input_option)) {
                switch ($input_option) {
                    //Input
                    case 'input':
                        $output .= '<input ' . (isset($section_required_prem_plugin) && $section_required_prem_plugin !== 'active' ? 'disabled ' : '') . 'type="' . $option['type'] . '" name="' . $option['name'] . '" id="' . $option['id'] . '" class="feed-them-social-admin-input ' . (isset($option['class']) ? $option['class'] : '') . '" placeholder="' . (isset($option['placeholder']) ? $option['placeholder'] : '') . '" value="' . $final_value . '"' . (isset($option['autocomplete']) ? ' autocomplete="' . $option['autocomplete'] . '"' : '') . ' />';
                        break;

                    //Select
                    case 'select':
                        $output .= '<select ' . (isset($section_required_prem_plugin) && $section_required_prem_plugin !== 'active' ? 'disabled ' : '') . 'name="' . $option['name'] . '" id="' . $option['id'] . '"  class="feed-them-social-admin-input">';
                        $i = 0;
                        foreach ($option['options'] as $select_option) {
                            $output .= '<option value="' . $select_option['value'] . '" ' . (!empty($final_value) && $final_value == $select_option['value'] || empty($input_value) && $i == 0 ? 'selected="selected"' : '') . '>' . $select_option['label'] . '</option>';
                            $i++;
                        }
                        $output .= '</select>';
                        break;

                    //Checkbox
                    case 'checkbox':
                        $output .= '<input ' . (isset($section_required_prem_plugin) && $section_required_prem_plugin !== 'active' ? 'disabled ' : '') . 'type="checkbox" name="' . $option['name'] . '" id="' . $option['id'] . '" ' . (!empty($final_value) && $final_value == 'true' ? ' checked="checked"' : '') . '/>';
                        break;

                    //Checkbox for image sizes COMMENTING OUT BUT LEAVING FOR FUTURE QUICK USE
                    //   case 'checkbox-image-sizes':
                    // $final_value_images = array('thumbnailzzz','mediummmm', 'large', 'full');
                    //Get Gallery Options via the Rest API
                    //        $final_value_images = $gallery_options_returned['ft_watermark_image_sizes']['image_sizes'];
                    // print_r($final_value_images);
                    //array('thumbnailzzz','mediummmm', 'largeee', 'fullll');
                    //        $output .= '<label for="'. $option['id'] . '"><input type="checkbox" val="' . $option['default_value'] . '" name="ft_watermark_image_sizes[image_sizes][' . $option['default_value'] . ']" id="'.$option['id'] . '" '. ( array_key_exists($option['default_value'], $final_value_images) ? ' checked="checked"' : '') .'/>';
                    //        $output .= '' . $option['default_value'] . '</label>';
                    //        break;


                    //Checkbox for image sizes used so you can check the image sizes you want to be water marked after you save the page.


                    //Repeatable
                    case 'repeatable':
                        echo '<a class="repeatable-add button" href="#">';
                        _e('Add Another design', 'feed-them-social');
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

                }
            }

            //GLOBAL checkbox
//            $output .= '<div class="feed-them-social-admin-global-checkbox ft-global-option-wrap-' . $option['name'] . '">';
//            $output .= '<input type="checkbox" name="' . $this->global_prefix . $option['name'] . '" id="' . $this->global_prefix . $option['id'] . '" ' . (!empty($global_value) && $global_value == 'true' ? ' checked="checked"' : '') . '/>';
//            $output .= '<label for="' . $this->global_prefix . $option['name'] . '"> Use/Set Global Option </label>';
//            $output .= '</div>';

            $output .= '<div class="clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            $output .= isset($option['outer_wrap_class']) || isset($option['outer_wrap_display']) ? '</div>' : '';

            //Sub option output END?
            if (isset($option['sub_options_end'])) {
                $output .= !is_numeric($option['sub_options_end']) ? '</div>' : '';
                //Multiple Div needed?
                if (is_numeric($option['sub_options_end'])) {
                    $x = 1;
                    while ($x <= $option['sub_options_end']) {
                        $output .= '</div>';
                        $x++;
                    }
                }
            }
        }

        $output .= '</section> <!--/Section Wrap Class END -->';

        return $output;
    }

    /**
     * FTS Feed Save Custom Meta Box
     * Save Fields for Feeds
     *
     * @param $post_id
     * @param $post
     * @return string
     * @since 1.0.0
     */
    public
    function fts_cpt_save_custom_meta_box($post_id, $post) {
        if (!isset($_POST['fts-cpts-settings-meta-box-nonce']) || !wp_verify_nonce($_POST['fts-cpts-settings-meta-box-nonce'], basename(__FILE__)))
            return $post_id;
        //Can User Edit Post?
        if (!current_user_can('edit_post', $post_id))
            return $post_id;
        //Autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        //CPT Check
        $slug = 'fts_cpt';
        if ($slug != $post->post_type)
            return $post_id;
        //Save Each Field Function
        foreach ($this->saved_settings_array as $box_array) {
            foreach ($box_array as $box_key => $settings) {
                if ($box_key == 'main_options') {
                    foreach ($settings as $option) {
                        //Global Value?
                        $global_old = get_post_meta($post_id, $this->global_prefix . $option['name'], true);

                        $get_global_option = get_option($this->global_prefix . $option['name']);


                        if ($option['option_type'] == 'checkbox') {
                            $new = isset($_POST[$option['name']]) && $_POST[$option['name']] !== 'false' ? 'true' : 'false';

                        } else {
                            $new = isset($_POST[$option['name']]) ? $_POST[$option['name']] : '';
                        }

                        if (isset($_POST[$this->global_prefix . $option['name']]) && $_POST[$this->global_prefix . $option['name']] !== 'false') {
                            update_post_meta($post_id, $this->global_prefix . $option['name'], 'true');
                            update_option($this->global_prefix . $option['name'], $new);
                        } elseif (isset($global_old) && !isset($_POST[$this->global_prefix . $option['name']])) {
                            update_post_meta($post_id, $this->global_prefix . $option['name'], 'false');
                            update_post_meta($post_id, $option['name'], $new);

                        } else {
                            //Post Meta Field?
                            $old = get_post_meta($post_id, $option['name'], true);

                            if ($option['option_type'] !== 'checkbox') {
                                if ($new && $new != $old) {
                                    update_post_meta($post_id, $option['name'], $new);
                                }
                            } else {
                                update_post_meta($post_id, $option['name'], $new);
                            }
                        }
                    }

                }
            }
        }
        $attach_ID = $this->fts_cpt_get_gallery_attached_media_ids($post_id);
        foreach ($attach_ID as $img_index => $img_id) {
            $a = array(
                'ID' => $img_id,
                'menu_order' => $img_index
            );
            wp_update_post($a);
        }


        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include(FEED_THEM_GALLERY_PREMIUM_PLUGIN_FOLDER_DIR . 'includes/watermark/save.php');
        }
        // end premium

        // Return settings
        return $settings;

    }

    /**
     * FTS Feed Get Gallery Attached Media IDs
     *
     * Get an Array of ID's of attachments for this Gallery.
     *
     * @param $gallery_id
     * @param string $mime_type (leave empty for all types)
     * @return array
     * @since 1.0.0
     */
    function fts_cpt_get_gallery_attached_media_ids($gallery_id, $mime_type = '') {
        $post_attachments = get_attached_media($mime_type, $gallery_id);

        $attachment_ids_array = array();
        foreach ($post_attachments as $attachment) {
            $attachment_ids_array[] = $attachment->ID;
        }

        return $attachment_ids_array;
    }

    /**
     * Get Attachment Info
     * Combines get_post and wp_get_attachment_metadata to create some clean attachment info
     *
     * @param $attachment_id
     * @param bool $include_meta_data (True || False) Default: False
     * @return array
     * @since 1.0.0
     */
    function fts_cpt_get_attachment_info($attachment_id, $include_meta_data = false) {
        //Get all of the Attachment info!
        $attach_array = wp_prepare_attachment_for_js($attachment_id);

        $path_parts = pathinfo($attach_array['filename']);

        $attachment_info = array(
            'ID' => $attach_array['id'],
            'title' => $attach_array['title'],
            'type' => $attach_array['type'],
            'subtype' => $attach_array['type'],
            'alt' => $attach_array['alt'],
            'caption' => $attach_array['caption'],
            'description' => $attach_array['description'],
            'href' => $attach_array['link'],
            'src' => $attach_array['url'],
            'mime-type' => $attach_array['mime'],
            'file' => $attach_array['filename'],
            'slug' => $path_parts['filename'],
            'download_url' => get_permalink($attach_array['uploadedTo']) . '?attachment_name=' . $attach_array['id'] . '&download_file=1',
        );

        //IF Exif data is set to return and is set in Meta Data.
        //  if($include_meta_data){
        $meta_data = wp_get_attachment_metadata($attachment_id);

        $attachment_info['meta_data'] = isset($meta_data) ? $meta_data : '';

        //  }

        return $attachment_info;
    }

    /**
     * FTS Feed Format Attachment Title
     * Format the title for attachments to ensure awesome titles (options on settings page)
     *
     * @param $title
     * @param null $attachment_id
     * @param null $update_post
     * @return mixed|string
     * @since 1.0.0
     */
    function fts_cpt_format_attachment_title($title, $attachment_id = NULL, $update_post = NULL) {

        $options = get_option('fts_cpt_format_attachment_titles_options');
        $cap_options = $options['fts_cpt_cap_options'];

        if (!empty($attachment_id)) {
            $uploaded_post_id = get_post($attachment_id);
            //$title = $uploaded_post_id->post_title;
        }

        /* Update post. */
        $char_array = array();
        if (isset($options['fts_cpt_fat_hyphen']) && $options['fts_cpt_fat_hyphen']) {
            $char_array[] = '-';
        }
        if (isset($options['fts_cpt_fat_underscore']) && $options['fts_cpt_fat_underscore']) {
            $char_array[] = '_';
        }
        if (isset($options['fts_cpt_fat_period']) && $options['fts_cpt_fat_period']) {
            $char_array[] = '.';
        }
        if (isset($options['fts_cpt_fat_tilde']) && $options['fts_cpt_fat_tilde']) {
            $char_array[] = '~';
        }
        if (isset($options['fts_cpt_fat_plus']) && $options['fts_cpt_fat_plus']) {
            $char_array[] = '+';
        }

        /* Replace chars with spaces, if any selected. */
        if (!empty($char_array)) {
            $title = str_replace($char_array, ' ', $title);
        }

        /* Trim multiple spaces between words. */
        $title = preg_replace("/\s+/", " ", $title);

        /* Capitalize Title. */
        switch ($cap_options) {
            case 'cap_all':
                $title = ucwords($title);
                break;
            case 'cap_first':
                $title = ucfirst(strtolower($title));
                break;
            case 'all_lower':
                $title = strtolower($title);
                break;
            case 'all_upper':
                $title = strtoupper($title);
                break;
            case 'dont_alter':
                /* Leave title as it is. */
                break;
        }

        //Return Clean Title otherwise update post!
        if ($update_post !== 'true') {
            return $title;
        }

        // add formatted title to the alt meta field
        if (isset($options['fts_cpt_fat_alt']) && $options['fts_cpt_fat_alt']) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $title);
        }

        // update the post
        $uploaded_post = array(
            'ID' => $attachment_id,
            'post_title' => $title,
        );

        // add formatted title to the description meta field
        if (isset($options['fts_cpt_fat_description']) && $options['fts_cpt_fat_description']) {
            $uploaded_post['post_content'] = $title;
        }

        // add formatted title to the caption meta field
        if (isset($options['fts_cpt_fat_caption']) && $options['fts_cpt_fat_caption']) {
            $uploaded_post['post_excerpt'] = $title;
        }

        wp_update_post($uploaded_post);

        return $title;
    }

    /**
     * FTS Feed ZIP exists check
     * Check if ZIP still exists
     *
     * @param $id_to_check
     * @return bool
     * @since 1.0.0
     */
    public
    function fts_cpt_zip_exists_check($id_to_check) {
        $fts_cpt_zip_status = get_post_status($id_to_check);

        //Check the Status if False or in Trash return false
        return $fts_cpt_zip_status == false || $fts_cpt_zip_status == 'trash' ? 'false' : 'true';
    }


    /**
     * FTS Feed Duplicate Post As Draft
     * Function creates post duplicate as a draft and redirects then to the edit post screen
     *
     * @since 1.0.0
     */
    function fts_cpt_duplicate_post_as_draft() {
        global $wpdb;
        if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'fts_cpt_duplicate_post_as_draft' == $_REQUEST['action']))) {
            wp_die('No post to duplicate has been supplied!');
        }

        /*
         * Nonce verification
         */
        if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
            return;

        /*
         * get the original post id
         */
        $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
        /*
         * and all the original post data then
         */
        $post = get_post($post_id);

        /*
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        /*
         * if post data exists, create the post duplicate
         */
        if (isset($post) && $post != null) {

            /*
             * new post data array
             */
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'post_author' => $new_post_author,
                'post_content' => $post->post_content,
                'post_excerpt' => $post->post_excerpt,
                'post_name' => $post->post_name,
                'post_parent' => $post->post_parent,
                'post_password' => $post->post_password,
                'post_status' => 'draft',
                'post_title' => $post->post_title,
                'post_type' => $post->post_type,
                'to_ping' => $post->to_ping,
                'menu_order' => $post->menu_order
            );

            /*
             * insert the post by wp_insert_post() function
             */
            $new_post_id = wp_insert_post($args);

            /*
             * get all current post terms ad set them to the new post draft
             */
            $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            /*
             * duplicate all post meta just in two SQL queries
             */
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos) != 0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    if ($meta_key == '_wp_old_slug') continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }


            /*
             * finally, redirect to the edit post screen for the new draft
             */
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }

    /**
     * FTS Feed Duplicate Post Link
     * Add the duplicate link to action list for post_row_actions
     *
     * @param $actions
     * @param $post
     * @return mixed
     * @since 1.0.0
     */
    function fts_cpt_duplicate_post_link($actions, $post) {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=fts_cpt_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
        }

        return $actions;
    }


    /**
     * FTS Feed Duplicate Post ADD Duplicate Post Button
     * Add a button in the post/page edit screen to create a clone
     *
     * @since 1.0.0
     */
    function fts_cpt_duplicate_post_add_duplicate_post_button() {
        if (isset($_GET['post'])) {
            $id = $_GET['post'];
            ?>
            <div id="ht-gallery-duplicate-action">
                <a href="<?php echo wp_nonce_url('admin.php?action=fts_cpt_duplicate_post_as_draft&post=' . $id, basename(__FILE__), 'duplicate_nonce') ?>" title="Duplicate this item" rel="permalink">Duplicate
                    Gallery</a>
            </div>
            <?php
        }
    }
} ?>