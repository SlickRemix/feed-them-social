<?php namespace feedthemsocial\includes;
/**
 * Feed Shortcode
 *
 * This class determines what feed needs to be displayed and outputs it.
 *
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @author   SlickRemix
 */

// Exit if accessed directly!
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * FeedShortcode
 */
class FeedShortcode {
    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Feed Functions
     *
     * General Feed Functions to be used in most Feeds.
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $optionsFunctions;

    /**
     * Feed Cache.
     *
     * Class used for caching.
     *
     * @var object
     */
    public $feedCache;

    /**
     * Access Options
     *
     * Access Options for tokens.
     *
     * @var object
     */
    public $accessOptions;

    /**
     * Facebook Feed
     *
     * The Facebook feed object.
     *
     * @var object
     */
    public $facebookFeed;

    /**
     * Instagram Feed
     *
     * The Instagram feed object.
     *
     * @var object
     */
    public $instagramFeed;

    /**
     * Twitter Feed
     *
     * The Twitter feed object.
     *
     * @var object
     */
    public $twitterFeed;

    /**
     * YouTube Feed
     *
     * The YouTube feed object.
     *
     * @var object
     */
    public $youtubeFeed;

    /**
     * Combined Streams Feed
     *
     * The Combined Streams feed object.
     *
     * @var object
     */
    public $combinedStreams;


    /**
     * Feed Display Constructor.
     */
    public function __construct( $settingsFunctions, $feedFunctions, $optionsFunctions, $facebookFeed, $instagramFeed, $twitterFeed, $youtubeFeed, $combinedStreams = null ){
        // Add Actions and filters.
        $this->addActionsFilters();

        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        // Set Feed Functions object.
        $this->optionsFunctions = $optionsFunctions;

        // Facebook Feed.
        $this->facebookFeed = $facebookFeed;

        // Instagram Feed.
        $this->instagramFeed = $instagramFeed;

        // Twitter Feed.
        $this->twitterFeed = $twitterFeed;

        // YouTube Feed.
        $this->youtubeFeed = $youtubeFeed;

        // Combined Streams Feed.
        $this->combinedStreams = $combinedStreams ?? null;
    }

    /**
     * Register Frontend Styles and Scripts
     *
     * Adds the Actions and filters for the class.
     *
     * @since 4.0.0
     */
    public function addActionsFilters(){
        // Add Shortcode Filter for displaying a feed.
        add_shortcode( 'feed_them_social', array( $this, 'displayFeedShortcodeFilter' ) );

        // Register Frontend Styles & Scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'registerFrontendStylesScripts' ) );
    }

    /**
     * Register Frontend Styles & Scripts
     *
     * Registers Frontend Styles & Scripts for the Feeds.
     *
     * @since 4.0.0
     */
    public function registerFrontendStylesScripts(){

        // Register Feed Styles.
        wp_register_style( 'fts-feed-styles', plugins_url( 'feed-them-social/includes/feeds/css/styles.min.css' ), false, FTS_CURRENT_VERSION );

        // Masonry snippet in fts-global.js file.
        wp_register_script( 'fts-global-js', plugins_url( 'feed-them-social/includes/feeds/js/fts-global.min.js' ), array( 'jquery' ), FTS_CURRENT_VERSION, false );

        // Register Premium Styles & Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) || $this->feedFunctions->isExtensionActive( 'feed_them_social_combined_streams' ) ) {
            // Masonry Script.
            wp_register_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/includes/feeds/js/masonry.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
            // Images Loaded Script.
            wp_register_script( 'fts-images-loaded', plugins_url( 'feed-them-social/includes/feeds/js/imagesloaded.pkgd.min.js' ), array(), FTS_CURRENT_VERSION, false );
        }

        // Register Feed Them Carousel Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_carousel_premium' ) && $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
            wp_register_script( 'fts-feeds', plugins_url( 'feed-them-carousel-premium/feeds/js/jquery.cycle2.js' ), array(), FTS_CURRENT_VERSION, false );
        }

        // Register Feed Them Instagram Slider Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_instagram_slider' ) ) {
            // Register Feed Styles.
            wp_register_style( 'fts-instagram-slider-styles', plugins_url( 'feed-them-social-instagram-slider/includes/scripts/css/tiny-slider.min.css' ), false, FTS_CURRENT_VERSION );
            // Register Feed Scripts.
            wp_register_script( 'fts-instagram-slider-js', plugins_url( 'feed-them-social-instagram-slider/includes/scripts/js/tiny-slider.min.js' ), array(), FTS_CURRENT_VERSION, false );
        }
    }

    /**
     * Load Frontend Styles & Scripts
     *
     * Load Frontend Styles & Scripts ONLY on pages feed is on.
     *
     * @since 4.0.0
     */
    public function loadFrontendStylesScripts(){
        // Feed Styles.
        wp_enqueue_style('fts-feed-styles');

        // Add Custom CSS to the header if option checked.
        $custom_css_checked_css = $this->settingsFunctions->fts_get_option( 'use_custom_css' );
        if ( $custom_css_checked_css === '1' ) {
            $css = !empty( $this->settingsFunctions->fts_get_option( 'custom_css' ) ) ?  $this->settingsFunctions->fts_get_option( 'custom_css' ) : '';
            wp_register_style( 'fts-feed-custom-styles', false, array( 'fts-feed-styles' ) );
            wp_enqueue_style( 'fts-feed-custom-styles' );
            wp_add_inline_style( 'fts-feed-custom-styles', $css );
        }

        // Add Custom JS to the header if option checked.
        $use_custom_js = $this->settingsFunctions->fts_get_option( 'use_custom_js' );
        if ( $use_custom_js === '1' ) {
            $js = !empty( $this->settingsFunctions->fts_get_option( 'custom_js' ) ) ?  $this->settingsFunctions->fts_get_option( 'custom_js' ) : '';
            wp_register_script( 'fts-feed-custom-script', false, array( 'fts-global-js' ) );
            wp_enqueue_script( 'fts-feed-custom-script' );
            wp_add_inline_script( 'fts-feed-custom-script', $js );
        }

        //Feed Global JS.
        wp_enqueue_script('fts-global-js');

        // Set Powered by JS for FTS!
        $fts_powered_text_options_settings = $this->settingsFunctions->fts_get_option( 'powered_by' );
        if ( '1' !== $fts_powered_text_options_settings ) {
            //Powered By JS.
            wp_enqueue_script('fts-powered-by-js');
        }

        // Premium Feed Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) || $this->feedFunctions->isExtensionActive( 'feed_them_social_combined_streams' ) ) {
            // Masonry Script
            wp_enqueue_script('fts-masonry-pkgd');
            // Images Loaded
            wp_enqueue_script('fts-images-loaded');
        }

        // Carousel Feed Scripts || Instagram Slider Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_carousel_premium' ) && $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' )) {
            wp_enqueue_script( 'fts-feeds');
        }

        // Carousel Feed Scripts || Instagram Slider Scripts.
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_instagram_slider' ) ) {
            wp_enqueue_style( 'fts-instagram-slider-styles' );
            wp_enqueue_script( 'fts-instagram-slider-js');
        }
    }

    /**
     * Shortcode_location
     *
     * When a page containing a shortcode is viewed on the front end we
     * then update the post meta key fts_shortcode_location with the ID of the page.
     *
     * @since 3.0
     */
    public function shortcodeLocation( $cpt_id ) {

        // Make sure we are only updating the fts_shortcode_location option if the user is logged in and an administrator.
        if ( !current_user_can('administrator') ) {
            return;
        }

        global $post;

        // Process: A user saves a shortcode to a page, when they view the page we update the fts_shortcode_location with the id.
        // the fts_shortcode_location option will contain an array of ids, that is all.
        // To see how we remove ids from the array if a user deletes the shortcode go to the
        // feeds-cpt-class.php and search for case 'shortcode_location':
        // that is where we will update the fts_shortcode_location to remove an ids not found. This happens when the user loads the wp-admin/edit.php?post_type=fts page
        if (isset($post) && $post !== null ) {

            // Do not run action if the post type is fts in admin. Because we display the shortcode in the post edit page for the user to preview.
            if ( isset( $post->post_type ) && $post->post_type === 'fts' ) {
                return;
            }

            DebugLog::log( 'FeedShortcode', 'is admin', current_user_can('administrator') );

            $array_check = $this->feedFunctions->getFeedOption( $cpt_id, 'fts_shortcode_location' );
            $array_check_decode = json_decode( $array_check );

            if ( !is_array( $array_check_decode ) ) {
                $encoded = json_encode( array($post->ID) );
                $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_shortcode_location', $encoded, true, $cpt_id, false );
            } elseif ( is_array( $array_check_decode ) ) {
                if ( !\in_array( $post->ID, $array_check_decode, true ) ) {
                    $add_id = array_merge( $array_check_decode, array($post->ID) );
                    $encoded = json_encode( $add_id );
                    $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_shortcode_location', $encoded, true, $cpt_id, false );
                }
            }
        }
    }

    /**
     * Shortcode Feed ID Exists
     *
     * Check to see Feed ID exists in shortcode
     *
     * @param array $atts
     * @return array|bool
     * @since 4.0.0
     */
    public function shortcodeFeedIdExists( $atts ) {
        // Make sure attributes contain cpt_id which is the Feed's CPT Post ID.
        if ( \is_array( $atts ) && isset( $atts['cpt_id'] ) && ! empty( $atts['cpt_id'] ) ) {
            return $atts['cpt_id'];
        }
        // If it doesn't exists return false.
        return false;
    }

    /**
     * Display Feed
     *
     * Display the feed by Feed Type.
     *
     * @param array $feed_post_id Feed Post ID.
     * @param array $feed_type Feed Type for a Feed.
     * @since 4.0.0
     */
    public function displayFeed( $feed_post_id, $feed_type ) {
        if ( $feed_type ){
            //Filter by Feed Type
            switch ( $feed_type ){
                // Facebook Feed
                case 'facebook-feed-type':
                    // Display Facebook Feed!
                    echo $this->facebookFeed->displayFacebook( $feed_post_id );
                    break;
                // Instagram Feed.
                case 'instagram-business-feed-type':
                case 'instagram-feed-type':
                    // Display the Instagram Feed!
                    echo $this->instagramFeed->displayInstagram( $feed_post_id );
                    break;
                // Twitter Feed.
                case 'twitter-feed-type':
                    // Display Twitter Feed!
                    echo $this->twitterFeed->displayTiktok( $feed_post_id );
                    break;
                // YouTube Feed.
                case 'youtube-feed-type':
                    // Display YouTube Feed!
                    echo $this->youtubeFeed->displayYoutube( $feed_post_id );
                    break;
                // Combine Streams Feed.
                case 'combine-streams-feed-type':
                    // Display Combined Streams Feed.
                    if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_combined_streams' ) ) {

                        echo $this->combinedStreams->display_combined_streams( $feed_post_id );
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Display Feed ShortCode Filter
     *
     * The Shortcode filter to display a Feed based on the Feed Post ID and Feed Post Type. Also Loads scripts for feed only
     * only on page feed is loading.
     *
     * @param array $atts Attributes from the shortcode.
     * @since 4.0.0
     */
    public function displayFeedShortcodeFilter( $atts ){
        // Feed Post ID that exists in the shortcode.
        $feed_post_id = $this->shortcodeFeedIdExists( $atts );

        // If Feed Post ID exists then outputting what is needed.
        if( $feed_post_id ){
            // Get Feed Type.
            $feed_type = $this->feedFunctions->getFeedType( $feed_post_id );

            // Load Frontend Styles & Scripts ONLY on Feed pages.
            $this->loadFrontendStylesScripts();

            // Shortcode Location.
            $this->shortcodeLocation( $feed_post_id );

            // Prevent the shortcode from appearing above page content.
            ob_start();
            // Display Feed by the Feed Post ID and Feed Type.
            $this->displayFeed( $feed_post_id, $feed_type);

            return ob_get_clean();
        }
    }
}
