<?php
namespace feedthemsocial;

class FTS_Steemit_Feed extends feed_them_social_functions
{

    private $SteemApi;
    /**
     * Construct
     * Added Since 9/28/2016 https://dev.twitter.com/overview/api/upcoming-changes-to-tweets
     *
     * Steemit Feed constructor.
     *
     * @since 1.9.6
     */
    function __construct() {

        $this->SteemApi = new SteemApi();

        add_shortcode('fts_steemit', array($this, 'fts_steemit_feed'));
        add_action('wp_enqueue_scripts', array($this, 'fts_steemit_head'));
    }

    /**
     * FTS Steemit Head
     *
     * Add Styles and Scripts functions.
     *
     * @since 1.9.6
     */
    function fts_steemit_head() {
        wp_enqueue_style('fts-feeds', plugins_url('feed-them-social/feeds/css/styles.css'));
    }

    function fts_steemit_feed() {
        $params = array('joeparys','','2018-01-10T12:43:45','5');
        $steemit_posts = $this->SteemApi->getDiscussionsByAuthorBeforeDate( $params,'curl' );

        echo '<pre>';
            print_r($steemit_posts);
        echo '</pre>';

    }


}// FTS_Steemit_Feed END CLASS
new FTS_Steemit_Feed();
?>