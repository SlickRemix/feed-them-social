<?php

namespace feedthemsocial\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

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

        $this->end_controls_section();
    }

    protected function render() {

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {

            // We have to load the scripts and front end styles scripts here because Elementor
            // loads everything through ajax in the edit panel and wp_enqueue_scripts will not work in that method.

            // We need to be able to load the scripts function from the feed-shortcode.php page.
            // Register Frontend Styles & Scripts, line 154 of the feed-shortcode.php file.
            // add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles_scripts' ) );

            // We also need to load the this function too from line 363 of the feed-shortcode.php file.
            // that is the function that looks to see if any custom styles or scripts have been added to our settings page.
            // Load Frontend Styles & Scripts ONLY on Feed pages.
            // $this->load_frontend_styles_scripts();
        }

        $settings = $this->get_settings_for_display();
        echo do_shortcode('[feed_them_social cpt_id=' . $settings['post_id'] . ']');
    }
}