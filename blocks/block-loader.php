<?php
/**
 * 
 */
namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BlockLoader {

    public function __construct() {
        
        $this->register_blocks();

    }

    private function register_blocks() {

        \add_action(
            'init',
            array(
                __CLASS__,
                'feeds_block'
            )
        );

        \add_action(
            'the_post',
            array(
                __CLASS__,
                'localize_post_data'
            )
        );

    }

    public function feeds_block() {

        \register_block_type(
            __DIR__ . '/feeds/block.json',
            array(
                'render_callback' => array(
                    __CLASS__, 'get_rendered_block'
                )
            )
        );

    }

    public function get_rendered_block( $block_attributes, $content ) {

        if ( ! isset( $block_attributes['feed'] ) ) {
            return 'No feed selected';
        }

        $feed_id = (int) $block_attributes['feed'];

        return \do_shortcode( "[feed_them_social cpt_id={$feed_id}]" );

    }

    public function localize_post_data( $post ) {

        $current_user_id = \get_current_user_id();

        if ( 0 >= $current_user_id ) {
            return;
        }

        $user_feeds = $GLOBALS['wpdb']->get_results(
            "SELECT `id` AS `ID`, `post_title` AS `Feed` FROM `{$GLOBALS['wpdb']->posts}` WHERE `post_status`='publish' AND `post_author`={$current_user_id} AND `post_type`='fts'"
        );

        \wp_localize_script(
            'feed-them-social-feeds-editor-script',
            'feedThemSocialBlockFeeds',
            $user_feeds
        );

    }

}