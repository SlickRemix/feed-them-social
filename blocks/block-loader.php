<?php
/**
 * Contains the feed block class
 * 
 * @since 4.0.7
 * 
 */
namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feeds Block loader class
 * 
 * @since 4.0.7
 */
class BlockLoader {

    public function __construct() {
        
        $this->register_blocks();

    }

    /**
     * Sets the required action hooks.
     * 
     * @since 4.0.7
     */
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

        \add_action(
            'init',
            array(
                __CLASS__,
                'set_script_translations'
            )
        );

    }

    /**
     * Registers the block type
     * 
     * @since 4.0.7
     */
    public static function feeds_block() {

        \register_block_type(
            __DIR__ . '/feeds/block.json',
            array(
                'render_callback' => array(
                    __CLASS__, 'get_rendered_block'
                )
            )
        );

    }

    /**
     * Adds i18n for javascript
     * 
     * @since 4.0.7
     */
    public static function set_script_translations() {

        \wp_set_script_translations(
            'feed-them-social-feeds-editor-script',
            'feed-them-social'
        );

    }

    /**
     * Returns the rendered block content for the dynamic block
     * 
     * @since 4.0.7
     * 
     * @param array $attributes Block attributes.
     * @param string $content Block default content.
     * @return string
     */
    public static function get_rendered_block( $block_attributes, $content ) {

        if ( ! isset( $block_attributes['feed'] ) ) {
            return 'No feed selected';
        }

        $feed_id = (int) $block_attributes['feed'];

        return \do_shortcode( "[feed_them_social cpt_id={$feed_id}]" );

    }

    /**
     * Localizes the available user feeds for the select node on post edit
     * 
     * @since 4.0.7
     * 
     * @global object wpdb
     * 
     * @param WP_Post $post
     */
    public static function localize_post_data( $post ) {

        $current_user_id = \get_current_user_id();

        if ( 0 >= $current_user_id ) {
            return;
        }

        $user_feeds = $GLOBALS['wpdb']->get_results(
            "SELECT `id` AS `ID`, `post_title` AS `Feed` FROM `{$GLOBALS['wpdb']->posts}` WHERE `post_status`='publish' AND `post_author`={$current_user_id} AND `post_type`='fts' ORDER BY `post_date` DESC"
        );

        \wp_localize_script(
            'feed-them-social-feeds-editor-script',
            'feedThemSocialBlockFeeds',
            $user_feeds
        );

    }

}