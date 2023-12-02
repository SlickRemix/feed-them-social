<?php

namespace feedthemsocial\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

//https://developers.elementor.com/docs/scripts-styles/preview-styles/
add_action('elementor/preview/enqueue_styles', function() {

    wp_enqueue_style( 'fts-feed-styles', plugins_url( 'feed-them-social/includes/feeds/css/styles.min.css' ), false, FTS_CURRENT_VERSION );
    wp_enqueue_style( 'fts-feed-styles' );
});

// https://developers.elementor.com/docs/scripts-styles/preview-scripts/
add_action('elementor/preview/enqueue_scripts', function() {

    // Masonry snippet in fts-global.js file.
    wp_register_script( 'fts-feed-scripts', plugins_url( 'feed-them-social/includes/feeds/js/fts-global.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
    wp_enqueue_script( 'fts-feed-scripts' );

    // Register Premium Styles & Scripts.
    if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
        // Masonry Script.
        wp_register_script( 'fts-feed-scripts-masonry', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
        wp_enqueue_script( 'fts-feed-scripts-masonry' );

        // Images Loaded Script.
        wp_register_script( 'fts-feed-scripts-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array( ), FTS_CURRENT_VERSION, false );
        wp_enqueue_script( 'fts-feed-scripts-images-loaded' );
    }

    // Register Feed Them Carousel Scripts.
    if ( is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) && is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
        wp_register_script( 'fts-feed-scripts-cycle2', plugins_url( 'feed-them-carousel-premium/feeds/js/jquery.cycle2.js' ), array(), FTS_CURRENT_VERSION, false );
        wp_enqueue_script( 'fts-feed-scripts-cycle2' );
    }
});

add_action('elementor/editor/after_enqueue_scripts', function() {

    wp_register_style( 'fts-custom-styles', plugins_url( '/css/styles.min.css', __FILE__ ) );
    wp_enqueue_style( 'fts-custom-styles' );

    wp_register_script( 'fts-custom-scripts', plugins_url( '/js/scripts.min.js', __FILE__ ), array( 'jquery' ), FTS_CURRENT_VERSION, false );
    wp_enqueue_script( 'fts-custom-scripts' );

    $dataToBePassed = array(
        'create_feed_url' => get_admin_url() . 'post-new.php?post_type=fts',
        'edit_feed_url' => get_admin_url() . 'edit.php?post_type=fts'
    );
    wp_localize_script( 'fts-custom-scripts', 'php_vars', $dataToBePassed );
});

class Advertisement extends Widget_Base{

    public function get_name() {
        return 'feed-them-social';
    }

    public function get_title() {
        return __( 'Feed Them Social', 'text-domain' );
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'text-domain' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $options = [];
        $fts_posts = get_posts(['post_type' => 'fts', 'posts_per_page' => -1]);

        foreach ($fts_posts as $post) {
            $options[$post->ID] = $post->post_title;
        }

        $this->add_control(
            'post_id',
            [
                'label' => __( 'Select Post', 'text-domain' ),
                'type' => Controls_Manager::SELECT,
                'default' => current(array_keys($options)),
                'options' => $options,
            ]
        );

        $this->add_control(
            'custom_html',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="fts-edit-el-wrapper"><label>'.__( 'Feeds', 'feed-them-social' ).'</label><button class="fts-elementor-link" onclick="ftsEditEL()">'.__( 'Edit', 'feed-them-social' ).'</button> <button class="fts-elementor-link" onclick="ftsNewEL()">'.__( 'Create New', 'feed-them-social' ).'</button></div>',
                'content_classes' => 'your-custom-class',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        echo do_shortcode('[feed_them_social cpt_id=' . $settings['post_id'] . ']');
    }
}