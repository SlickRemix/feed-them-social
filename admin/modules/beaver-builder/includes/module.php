<?php
/**
 * Contains the Beaver Builder Module
 *
 * @since 4.1.6
 *
 */
namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// This function can not be in the class.
function fts_enqueue_custom_scripts() {
    if ( class_exists( '\FLBuilderModel' ) && \FLBuilderModel::is_builder_active() ) {
        wp_enqueue_style( 'fts-beaver-builder-custom-style', plugins_url( '/css/styles.min.css',  __FILE__ ), array(), FTS_CURRENT_VERSION, false );
        wp_enqueue_script( 'fts-beaver-builder-custom-script', plugins_url( '/js/scripts.min.js', __FILE__ ), array( 'jquery' ), FEED_THEM_SOCIAL_VERSION, true );

        $translation_array = array(
            'edit_feed_url' => admin_url('edit.php?post_type=fts'),
            'create_feed_url' => admin_url('edit.php?post_type=fts&amp;page=create-new-feed')
        );
        wp_localize_script( 'fts-beaver-builder-custom-script', 'php_vars', $translation_array );
    }
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\fts_enqueue_custom_scripts' );

/**
 * Beaver Builder Module
 *
 * @since 4.1.6
 */
class FTS_Beaver_Builder_Module extends \FLBuilderModule {

    protected $fts_posts_arr;

    public function __construct() {

        parent::__construct(array(
            'name'            => __( 'Feed Them Social', 'feed-them-social' ),
            'description'     => __( 'A module for embedding social feeds.', 'feed-them-social' ),
            'group'           => __( 'Social Modules', 'feed-them-social' ),
            'category'        => __( 'Social Media', 'feed-them-social' ),
            'dir'             => FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/modules/beaver-builder/',
            'url'             => FEED_THEM_SOCIAL_PLUGIN_FOLDER_DIR . 'admin/modules/beaver-builder/',
            'icon'            => 'layout.svg',
            'editor_export'   => true,
            'enabled'         => true,
        ));

        // Retrieve all 'fts' posts
        $this->fts_posts = get_posts(array(
            'post_type'   => 'fts',
            'numberposts' => -1
        ));

        // Generate associative array with post title and shortcode
        $this->fts_posts_arr = array();
        foreach($this->fts_posts as $post){
            $this->fts_posts_arr[$post->ID] = array(
                'title' => $post->post_title,
                'shortcode' => '[feed_them_social cpt_id=' . $post->ID . ']',
            );
        }
    }

    public function get_form() {
        $form = array(
            'content' => array(
                'title' => __('Content', 'feed-them-social'),
                'sections' => array(
                    'content_section' => array(
                        'title' => __('Select a Feed', 'feed-them-social'),
                        'fields' => array(
                            'feed_select' => array(
                                'type' => 'select',
                                'label' => __( 'Select a Feed', 'feed-them-social' ),
                                'default' => 'no',
                                'options' => array(),
                                'preview' => array(
                                    'type' => 'refresh'
                                )
                            )
                        )
                    ),
                    'action_section' => array(
                        'title' => __('Actions', 'feed-them-social'),
                        'fields' => array(
                            'add_new_feed' => array(
                                'type'    => 'html',
                                'label'   => '<button onclick="ftsEditBB()" id="fts-bb-edit-new-feed" class="fts-beaver-builder-link">'.__( 'Edit Feeds', 'feed-them-social' ).'</button> <button onclick="ftsNewBB()" id="fts-bb-create-new-feed" class="fts-beaver-builder-link" >'.__( 'Create New Feed', 'feed-them-social' ).'</button>',
                                'class'   => 'fts-new-feed-btn',
                                'preview' => array(
                                    'type' => 'none'
                                )
                            )
                        )
                    )
                )
            )
        );

        foreach($this->fts_posts_arr as $id => $data){
            $shortcode = $data['shortcode'];
            $title = $data['title'];
            $form['content']['sections']['content_section']['fields']['feed_select']['options'][$shortcode] = $title;
        }

        return $form;
    }

    public function render() {
        $selected_feed = $this->settings->feed_select;
        echo do_shortcode($selected_feed);
    }
}

// This must be outside of our class to work with Beaver Builder.
add_action('init', function() {
    if (class_exists('FLBuilder')) {
        $fts_bb_module = new \feedthemsocial\FTS_Beaver_Builder_Module();
        \FLBuilder::register_module('\feedthemsocial\FTS_Beaver_Builder_Module', $fts_bb_module->get_form());
    }
});