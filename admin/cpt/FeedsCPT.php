<?php
/**
 * Feeds CPT Class
 *
 * This class is what initiates the Feed Them Social class
 *
 * @version  1.0.0
 * @package  FeedThemSocial/Core
 * @author   SlickRemix
 */

namespace feedthemsocial\admin\cpt;

// Exit if accessed directly!
use feedthemsocial\admin\cpt\options\additional\FacebookAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\InstagramAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\TwitterAdditionalOptions;
use feedthemsocial\admin\cpt\options\additional\YoutubeAdditionalOptions;

if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Feeds
 *
 * @package FeedThemSocial/Core
 */
class FeedsCPT {

    /**
     * Settings Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $settingsFunctions;

    /**
     * Feed CPT ID
     * used to set Gallery ID
     *
     * @var string
     */
    public $feedCptId = '';

    /**
     * Feed Functions Class
     *
     * initiates Feed Functions object.
     *
     * @var object
     */
    public $feedFunctions;

    /**
     * Feed CPT Option Array
     *
     * An array of Feed Settings. Set in admin/cpt/options/feeds-cpt-options.php
     *
     * @var array
     */
    public $feedCptOptionsArray;

    /**
     * Feed CPT Access Token Options
     *
     * An array of Feed Access Token Settings. Set in admin/cpt/options/feeds-cpt-options.php
     *
     * @var array
     */
    public $feedCptAccessTokenOptions;

    /**
     * Setting Options JS
     *
     * initiates Setting Options JS Class
     *
     * @var object
     */
    public $settingOptionsJs;

    /**
     * Access Token Options
     *
     * initiates Access Token Options object.
     *
     * @var object
     */
    public $accessTokenOptions;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $optionsFunctions;

    /**
     * Metabox Functions Class
     *
     * initiates Metabox Functions object
     *
     * @var string
     */
    public $metaboxFunctions;

    /**
     * FeedsCPT constructor.
     *
     * @param object $feed_cpt_options All options.
     */
    public function __construct( $settingsFunctions, $feedFunctions, $feed_cpt_options, $settingOptionsJs, $metaboxFunctions, $accessTokenOptions, $optionsFunctions) {

        // Add Actions and Filters.
        $this->addActionsFilters();

        $this->settingsFunctions = $settingsFunctions;

        // Set Feed Functions object.
        $this->feedFunctions = $feedFunctions;

        // Feed CPT Options Array.
        $this->feedCptOptionsArray = $feed_cpt_options->getAllOptions( true );

        // Feed CPT Access Token Options.
        $this->feedCptAccessTokenOptions = $feed_cpt_options->getAllTokenOptions();

        // Settings Options JS.
        $this->settingOptionsJs = $settingOptionsJs;

        // Metabox Functions.
        $this->metaboxFunctions = $metaboxFunctions;

        // If Premium add Functionality!
        if ( $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
            //Premium Features here.
            // Not being used atm
        }

        //Access Token Options.
        $this->accessTokenOptions = $accessTokenOptions;

        // Set Feed Functions object.
        $this->optionsFunctions = $optionsFunctions;
    }

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 1.1.8
     */
    public function addActionsFilters() {
        // Register Feed CPT!
        add_action( 'init', array( $this, 'ftsCpt' ) );

        // Add "Add New Feed" as Admin Sub Menu Item.
        add_action( 'admin_menu', array($this, 'addFeedSubMenu') );

        // Remove Default Add New Button from sidebar menu.
        add_action( 'admin_menu', array($this, 'removeDefaultAddNewButton') );

        // When adding new feed redirect to post edit page.
        add_action( 'current_screen', array($this, 'redirectToNewFeed' ) );

        // Response Messages!
        add_filter( 'post_updated_messages', array( $this, 'ftsUpdatedMessages' ) );

        // Feed List function!
        add_filter( 'manage_fts_posts_columns', array( $this, 'ftsSetCustomEditColumns' ) );
        add_action( 'manage_fts_posts_custom_column', array( $this, 'ftsCustomEditColumn' ), 10, 2 );

        // Change Button Text!
        add_filter( 'gettext', array( $this, 'setFeedButtonText' ), 20, 3 );

        // Add Meta Boxes!
        add_action( 'add_meta_boxes', array( $this, 'addFeedMetaboxes' ) );

        // Rename Submenu Item to Feeds!
        add_filter( 'attribute_escape', array( $this, 'ftsRenameSubmenuName' ), 10, 2 );

        // Add Shortcode! Not being used atm.
        //add_shortcode( 'fts_list', array( $this, 'fts_display_list' ) );

        // Set Current Feed CPT ID.
        add_action( 'current_screen', array( $this, 'currentFeedCptId' ) );

        add_action( 'admin_action_ftsDuplicatePostAsDraft', array( $this, 'ftsDuplicatePostAsDraft' ) );
        add_filter( 'page_row_actions', array( $this, 'ftsDuplicatePostLink' ), 10, 2 );
        add_filter( 'fts_row_actions', array( $this, 'ftsDuplicatePostLink' ), 10, 2 );
        add_action( 'post_submitbox_start', array( $this, 'ftsDuplicatePostAddDuplicatePostButton' ) );

        // Remove Edit Menu Links.
        add_filter( 'page_row_actions', array( $this, 'removeEditMenuLinks' ), 10, 2 );

        add_filter('body_class', [$this, 'addCustomBodyClassFrontend']);
    }

    /**
     *  Tab Notice HTML
     *
     * Creates notice html for return
     *
     * @since 1.0.0
     */
    public function ftsTabPremiumMsg() {
        echo \sprintf(
            esc_html__( '%1$sPlease purchase, install and activate %2$sFeed Them Social Premium%3$s for these additional awesome features!%4$s', 'feed-them-social' ),
            '<div class="ft-gallery-premium-mesg">',
            '<a href="' . esc_url( 'https://www.slickremix.com/downloads/feed-them-social/' ) . '" target="_blank">',
            '</a>',
            '</div>'
        );
    }

    /**
     *  Add Custom Body Class Admin
     *
     * Add custom body classes to admin area.
     *
     * @since 4.2.0
     */
    public function addCustomBodyClassAdmin($classes) {

        if ( $this->isFeedThemPremiumActive() || $this->isFeedThemSocialInstagramSliderActive() ) {
            // This is used for the areas we want to hide the text and link for, More than 6 Requires Premium
            $classes .= ' fts-premium-active';
        }
        $powered_by = $this->settingsFunctions->fts_get_option( 'powered_by' );
        if ( $powered_by === '1' ) {
            // This is used for the popup so we can remove the powered by text and a space to start is required.
            $classes .= ' fts-remove-powered-by';
        }

        return $classes;
    }

    /**
     *  Add Custom Body Class Frontend
     *
     * Add custom body classes to frontend of website.
     *
     * @since 4.2.0
     */
    public function addCustomBodyClassFrontend($classes) {

        $powered_by = $this->settingsFunctions->fts_get_option( 'powered_by' );
        if ( $powered_by === '1' ) {
            // This is used for the popup so we can remove the powered by text and NO space to start is required.
            $classes[] = 'fts-remove-powered-by';
        }

        return $classes;
    }

    /**
     * Is Feed Them Premium Active
     *
     * Used to aid in the check to display custom body classes.
     *
     * @since 4.2.0
     */
    private function isFeedThemPremiumActive() {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        return $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' );
    }

    /**
     * Is Feed Them Social Instagram Slider Active
     *
     * Used to aid in the check to display custom body classes.
     *
     * @since 4.2.0
     */
    private function isFeedThemSocialInstagramSliderActive() {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        return $this->feedFunctions->isExtensionActive( 'feed_them_social_instagram_slider' );
    }

    /**
     * Current Feed CPT ID
     *
     * Sets the Feed CPT ID based on the current screens _Get or _Post
     *
     * @since 1.0.0
     */
    public function currentFeedCptId() {
        $current_screen = get_current_screen();

        $current_get  = stripslashes_deep( $_GET );

        // Set Feed CPT ID using _Get or _Post
        if ( $current_screen->post_type === 'fts' && $current_screen->base === 'post' && is_admin() && isset( $current_get['post'] ) ) {

            // Add Custom Body Class.
            add_filter('admin_body_class', [$this, 'addCustomBodyClassAdmin']);
            $this->feedCptId = (int) $current_get['post'];
        }
    }

    /**
     * Create Feed Them Social Custom Post Type
     *
     * Create custom post type.
     *
     * @since 1.0.0
     */
    public function ftsCpt() {
        $responses_cpt_args = array(
            'label'               => esc_html__( 'Feed Them Social', 'feed-them-social' ),
            'labels'              => array(
                'menu_name'          => esc_html__( 'Feeds', 'feed-them-social' ),
                'name'               => esc_html__( 'Feeds', 'feed-them-social' ),
                'singular_name'      => esc_html__( 'Feed', 'feed-them-social' ),
                'add_new'            => esc_html__( 'Add New Feed', 'feed-them-social' ),
                'add_new_item'       => esc_html__( 'Add New Feed', 'feed-them-social' ),
                'edit_item'          => esc_html__( 'Edit Feed', 'feed-them-social' ),
                'new_item'           => esc_html__( 'New Feed', 'feed-them-social' ),
                'view_item'          => esc_html__( 'View Feed', 'feed-them-social' ),
                'search_items'       => esc_html__( 'Search Feeds', 'feed-them-social' ),
                'not_found'          => esc_html__( 'No Feeds Found', 'feed-them-social' ),
                'not_found_in_trash' => esc_html__( 'No Feeds Found In Trash', 'feed-them-social' ),
            ),

            'public'              => false,
            'show_ui'             => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,

            'capabilities'        => array(
                'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
            ),
            'map_meta_cap'        => true, // Allows Users to still edit Payments
            'has_archive'         => true,
            'hierarchical'        => true,
            'query_var'           => 'fts',

            'menu_icon'           => '',
            'supports'            => array( 'title', 'revisions' ),
            'order'               => 'DESC',
            // Set the available taxonomies here
            // 'taxonomies' => array('fts_topics')
        );
        register_post_type( 'fts', $responses_cpt_args );
    }

    /**
     * Rename Submenu Name
     * Renames the submenu item in the WordPress dashboard's menu
     *
     * @param $safe_text
     * @param $text
     * @return string
     * @since 1.0.0
     */
    public function ftsRenameSubmenuName( $safe_text, $text ) {
        if ( $text !== 'Feeds' ) {
            return $safe_text;
        }
        // We are on the main menu item now. The filter is not needed anymore.
        remove_filter( 'attribute_escape', array( $this, 'ftsRenameSubmenuName' ) );

        return esc_html( 'FT Social', 'feed-them-social' );
    }

    /**
     * Remove Default Add New Button
     *
     * This removes the default add new button.
     *
     * @since 4.0.0
     */
    public function removeDefaultAddNewButton() {
        remove_submenu_page( 'edit.php?post_type=fts', 'post-new.php?post_type=fts' );
    }

    /**
     * Add Feed Sub Menu.
     *
     * This replaces the "Add New" button .
     *
     * @since 4.0.0
     */
    public function addFeedSubMenu() {
        add_submenu_page(
            'edit.php?post_type=fts', // Main Menu Item.
            esc_html__( 'Add New Feed' , 'feed-them-social' ),
            esc_html__( 'Add New Feed' , 'feed-them-social' ),
            'manage_options',
            'create-new-feed',
            array( $this, 'addNewFeed' ),
            1 // Menu Position
        );
    }

    /**
     * Add New Feed
     *
     * This replaces the "Add New" button functionality with a published feed post so we can get Post ID for tokens.
     *
     * @since 4.0.0
     */
    public function addNewFeed() {
        wp_die( esc_html__( 'Oops, Could not create feed.', 'feed-them-social' ) );
    }

    /**
     * Add New Feed
     *
     * This replaces the default "Add New" button functionality with a published feed post so we can get Post ID for tokens.
     *
     * @since 4.0.0
     */
    public function redirectToNewFeed() {
        $current_screen = get_current_screen();
        //Check if is create-new-feed-page or is FTS CPT "Add New" page.
        if( isset( $current_screen->base ) && $current_screen->base === 'fts_page_create-new-feed' || $current_screen->base === 'post' && $current_screen->action === 'add' && $current_screen->post_type === 'fts' ){
            if( current_user_can( 'manage_options' ) ){

                foreach( $this->feedCptAccessTokenOptions as $access_token_option ){
                    // Options section is a group of options.
                    foreach ( $access_token_option as $option_section_key => $main_options ) {
                        // Only Load the main options key.
                        if ( $option_section_key === 'main_options' ) {
                            // Loop through the options array.
                            foreach ( $main_options as $option ) {
                                if ( !empty( $option['name'] ) ) {
                                    // If anything has changed update options!
                                    $access_token_options_array[ $option['name'] ] =  $option['default_value'] ?? '';
                                }
                            }
                        }
                    }
                }

                $new_post_id = wp_insert_post(
                // An array of elements that make up a post to update or insert.
                    array (
                        'post_title'     => 'My Feed',
                        'post_type'      => 'fts',
                        'post_status'    => 'publish',
                        'comment_status' => 'closed',
                        'ping_status'    => 'closed',

                        'meta_input'   => array(
                            FEED_THEM_SOCIAL_OPTION_ARRAY_NAME => $access_token_options_array,
                        ),

                    )
                );

                // Set Default Options for Post.
                //$create_options_status = $this->optionsFunctions->createInitialOptionsArray( 'fts_feed_options_array', $this->feedCptOptionsArray, true, $new_post_id, true );

                // Post was inserted. Redirect to new edit page!
                if( $new_post_id ){
                    wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
                    exit();
                }
                // Feed Creation Failed!
                else{
                    wp_die( esc_html__( 'Oops, Feed was not created.', 'feed-them-social' ) );
                }
            }
            wp_die( esc_html__( 'Not allowed to create feed. User permissions denied.', 'feed-them-social' ) );
        }
    }

    /**
     * Updated Messages
     *
     * Updates the messages in the admin area so they match plugin.
     *
     * @param $messages
     * @return mixed
     * @since 1.0.0
     */
    public function ftsUpdatedMessages( $messages ) {
        $messages['fts'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Feed updated.', 'feed-them-social' ),
            2  => esc_html__( 'Custom field updated.', 'feed-them-social' ),
            3  => esc_html__( 'Custom field deleted.', 'feed-them-social' ),
            4  => esc_html__( 'Feed updated.', 'feed-them-social' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? \sprintf( esc_html__( 'Feed restored to revision from %s', 'feed_them_social' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Feed created.', 'feed_them_social' ),
            7  => esc_html__( 'Feed saved.', 'feed_them_social' ),
            8  => esc_html__( 'Feed submitted.', 'feed_them_social' ),
            9  => esc_html__( 'Feed scheduled for:', 'feed_them_social' ),
            // translators: Publish box date format, see http://php.net/date
            // date_i18n( ( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => esc_html__( 'Feed draft updated.', 'feed_them_social' ),
        );

        return $messages;
    }

    /**
     * Set Custom Edit Columns
     *
     * Sets the custom admin columns for gallery list page
     *
     * @param $columns
     * @return array
     * @since 1.0.0
     */
    public function ftsSetCustomEditColumns( $columns ) {

        $new = array();

        foreach ( $columns as $key => $value ) {
            // when we find the date column.
            if ( 'title' === $key ) {
                $new[ $key ] = $value;
                $new['feed_shortcode'] = esc_html__( 'Feed Shortcode', 'feed_them_social' );
                $new[ $key ] = $value;
                $new['shortcode_location'] = esc_html__( 'Shortcode Location', 'feed_them_social' );

            } else {
                $new[ $key ] = $value;
            }
        }

        return $new;
    }

    /**
     * Feed Custom Edit Column
     *
     * Put info in matching columns we set
     *
     * @param $column
     * @param $post_id
     * @since 1.0.0
     */
    public function ftsCustomEditColumn( $column, $post_id ) {

        $post_id = (int) $post_id;

        switch ( $column ) {

            // Display the Shortcode.
            case 'feed_shortcode':
                ?>
                <input value="[feed_them_social cpt_id=<?php echo esc_html( $post_id ); ?>]" onclick="this.select()" readonly="readonly" />
                <?php
                break;
            // Display the Shortcode Location.
            case 'shortcode_location':

                // Notes:
                // 1. What if the shortcode is added to a widget not on a page or post?
                // 2. What about page builders. Might have to add condition and options for users to check a custom post type in a list that will apply to the checks below.

                // Take the ID that we store in the fts_shortcode_location post meta key and return the page title and permalink
                // so users can click to the page the shortcode is on and replace it or remove it.
                $shortcode_location_id = $this->feedFunctions->getFeedOption( $post_id, 'fts_shortcode_location' );
                $shortcode_location_id = json_decode( $shortcode_location_id );

                // Check to see if the shortcode_location_id has been set with an ID and if so lets double check that content has a shortcode in it.
                // IF so then we will display a page title and link to it so the user can see where there shortcode is being used.
                if( is_array( $shortcode_location_id ) && !empty( $shortcode_location_id ) ){
                    $location = array();
                    foreach ( $shortcode_location_id as $id ){

                        // Make sure the post id actually exists before running code.
                        if( get_post_status ( $post_id ) ) {

                            $post = get_post( $id );
                            // Get the post content so we can double check to see if it has a specific shortcode.
                            $the_content = $post->post_content ?? '';
                            $shortcode = '[feed_them_social cpt_id=' . esc_attr( $post_id ) . ']';

                            // As Noted: I can see this failing in some instances like page builders or custom post types.
                            if ( $the_content !== null && strpos($the_content, $shortcode) !== false ) {
                                $location[] = '<a href="' . get_the_permalink( $id ) . '" target="_blank">' . get_the_title( $id ) . '</a>';
                            }
                            else {
                                // If an ID is checked and not found the user must have removed the shortcode so we remove the id from the array and re-save it.
                                $array_check = $this->feedFunctions->getFeedOption( $post_id, 'fts_shortcode_location' );
                                $array_check_decode = json_decode( $array_check );

                                // Check to see if the id exists in array and if not then update single option to omit that id from the array.
                                if ( !empty( $array_check_decode ) && false !== ( $key = array_search( $id, $array_check_decode, true ) ) ) {
                                    // unset the key for the the id we are removing.
                                    unset( $array_check_decode[$key] );
                                    // array_values so we can reorder the keys before encoding.
                                    $array_final = array_values( $array_check_decode );

                                    // lets make sure the array is not empty before moving forward.
                                    if( !empty( $array_check_decode ) ){
                                        // encode the final array for db injection.
                                        $encoded = json_encode( $array_final );
                                    }
                                    else {
                                        // Clear the database field now since there are no ids set.
                                        $encoded = '';
                                        echo __( 'Not Set', 'feed-them-social' );
                                    }
                                    // Update the fts_shortcode_location with our newly compiled array has at least one id, or we clear the field.
                                    $this->optionsFunctions->updateSingleOption( 'fts_feed_options_array', 'fts_shortcode_location', $encoded, true, $post_id, false );
                                }
                            }
                        }
                    }
                    // Implode the results so we can add commas to our locations. This is the best approach so that the last location does not get a comma.
                    echo implode(', ', $location );
                }
                else {
                    echo __( 'Not Set', 'feed-them-social' );
                }
                break;
            default:
                break;
        }
    }

    /**
     * Set Feed Button Text
     *
     * Set Edit Post buttons for Feed CPT.
     *
     * @param $translated_text
     * @param $text
     * @param $domain
     * @return mixed
     * @since 1.0.0
     */
    public function setFeedButtonText( $translated_text ) {
        $post_id          = isset( $_GET['post'] ) ? $_GET['post'] : '';
        $custom_post_type = get_post_type( $post_id );
        if ( ! empty( $post_id ) && 'fts_responses' === $custom_post_type ) {
            switch ( $translated_text ) {
                case 'Publish':
                    $translated_text = esc_html__( 'Save Feed', 'feed_them_social' );
                    break;
                case 'Update':
                    $translated_text = esc_html__( 'Update Feed', 'feed_them_social' );
                    break;
                case 'Save Draft':
                    $translated_text = esc_html__( 'Save Feed Draft', 'feed_them_social' );
                    break;
                case 'Edit Payment':
                    $translated_text = esc_html__( 'Edit Feed', 'feed_them_social' );
                    break;
                default:
                    break;
            }
        }

        return $translated_text;
    }

    /**
     * Add Feed Metaboxes
     *
     * Add metaboxes to the Feed edit page.
     *
     * @since 1.0.0
     */
    public function addFeedMetaboxes() {
        global $post;
        // Check we are using Feed Them Social Custom Post type.
        if ( $post->post_type !== 'fts' ) {
            return;
        }

        // Feed Settings Metabox.
        add_meta_box( 'ft-galleries-upload-mb', esc_html__( 'Feed Them Social', 'feed_them_social' ), array( $this, 'ftsTabMenuMetabox' ), 'fts', 'normal', 'high', null );

        // Feed Shortcode Metabox.
        add_meta_box( 'ft-galleries-shortcode-side-mb', esc_html__( 'Feed Shortcode', 'feed_them_social' ), array( $this, 'ftsShortcodeMetaBox' ), 'fts', 'side', 'high', null );

        // Covert Old Shortcode Metabox.
        // add_meta_box( 'ft-galleries-old-shortcode-side-mb', esc_html__( 'Convert Old Shortcode', 'feed_them_social' ), array( $this, 'fts_old_shortcode_meta_box' ), 'fts', 'side', 'high', null );

        // Export/Import feed options
        add_meta_box( 'fts-import-export-feed-options-side-mb', esc_html__( 'Export/Import', 'feed_them_social' ), array( $this, 'ftsImportExportFeedOptionsMetaBox' ), 'fts', 'side', 'low', null );
    }

    /**
     *  Metabox Tabs List
     *
     * The list of tabs Items for settings page metaboxes.
     *
     * @return array
     * @since 1.1.6
     */
    public function metaboxTabsList() {
        return array(
            // Base of each tab! The array keys are the base name and the array value is a list of tab keys.
            'base_tabs' => array(
                'post' => array( 'feed_setup', 'layout', 'colors', 'facebook_feed', 'instagram_feed', 'tiktok_feed', 'youtube_feed', 'combine_streams_feed' ),
            ),
            // Tabs List! The cont_func item is relative the the Function name for that tabs content. The array Keys for each tab are also relative to classes and ID on wraps of displayMetaboxContent function.
            'tabs_list' => array(
                // Images Tab!
                'feed_setup'      => array(
                    'menu_li_class'      => 'tab1',
                    'menu_a_text'        => esc_html__( 'Feed Setup', 'feed_them_social' ),
                    'menu_a_class'       => 'account-tab-highlight',
                    'menu_aria_expanded' => 'true',
                    'cont_wrap_id'       => 'ftg-tab-content1',
                    'cont_func'          => 'tabFeedSetup',
                ),
                // Layout Tab!
                'layout'      => array(
                    'menu_li_class' => 'tab2',
                    'menu_a_text'   => esc_html__( 'Layout', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content2',
                    'cont_func'     => 'tabLayoutContent',
                ),
                // Colors Tab!
                'colors'      => array(
                    'menu_li_class' => 'tab3',
                    'menu_a_text'   => esc_html__( 'Colors', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content3',
                    'cont_func'     => 'tabColorsContent',
                ),
                // Instagram Feed Settings Tab!
                'instagram_feed' => array(
                    'menu_li_class' => 'tab4',
                    'menu_a_text'   => esc_html__( 'Instagram', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content5',
                    'cont_func'     => 'tabInstagramFeed',
                ),
                // Facebook Feed Settings Tab!
                'facebook_feed'        => array(
                    'menu_li_class' => 'tab5',
                    'menu_a_text'   => esc_html__( 'Facebook', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content6',
                    'cont_func'     => 'tabFacebookFeed',
                ),
                // Twitter Feed Settings Tab!
                'tiktok_feed'   => array(
                    'menu_li_class' => 'tab6',
                    'menu_a_text'   => esc_html__( 'Twitter', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content7',
                    'cont_func'     => 'tabTiktokFeed',
                ),
                // YouTube Feed Settings Tab!
                'youtube_feed'  => array(
                    'menu_li_class' => 'tab7',
                    'menu_a_text'   => esc_html__( 'Youtube', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content8',
                    'cont_func'     => 'tabYoutubeFeed',
                ),
                // Combined Streams Feed Settings Tab!
                'combine_streams_feed'        => array(
                    'menu_li_class' => 'tab8',
                    'menu_a_text'   => esc_html__( 'Combined', 'feed_them_social' ),
                    'cont_wrap_id'  => 'ftg-tab-content9',
                    'cont_func'     => 'tabCombineStreamsFeed',
                ),
            ),
        );
    }

    /**
     *  Tab Menu Metabox
     *
     * Creates the Tabs Menu Metabox
     *
     * @since 1.0.0
     */
    public function ftsTabMenuMetabox( ) {

        $this->metaboxFunctions->displayMetaboxContent( $this, $this->metaboxTabsList() );

        if ( ! $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
            ?>
            <script>
                jQuery('#ftg_sorting_options, #ftg_free_download_size').attr('disabled', 'disabled');
                jQuery('#ftg_sorting_options option[value="no"], #ftg_free_download_size option:first').text('Premium Required');
                jQuery('.ftg-pagination-notice-colored').remove();
            </script>
        <?php } ?>


        <div class="clear"></div>

        <?php
    }

    /**
     * Tab Feed Type Content
     *
     * Outputs Feed Type Selection tab's content for metabox.
     *
     * @param $params
     * @since 1.1.6
     */
    public function tabFeedSetup() {

        // Get Feed Type.
        $feed_type = $this->feedFunctions->getFeedType( $this->feedCptId );

        // Feed Type Options Selector.
        echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['feedTypeOptions'], null, $this->feedCptId );

        ?>
        <div class="fts-section-notice">
            <?php
            // Error Notice HTML. Happens in JS file.
            $this->metaboxFunctions->errorNoticeHtml(); ?>

            <script>
                jQuery('.metabox_submit').click(function (e) {
                    e.preventDefault();
                    //  jQuery('#publish').click();
                    jQuery('#post').click();
                });
            </script>
        </div>

        <div class="fts-access-token">
            <?php
            // Get Access Token Options.
            $this->accessTokenOptions->getAccessTokenOptions( $feed_type, $this->feedCptId );
            ?>
        </div>
        <?php
    }

    /**
     *  Tab Layout Content
     *
     * Outputs Layout tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabLayoutContent() {
        $layout = null;
        if ( isset( $this->feedCptOptionsArray, $this->feedCptOptionsArray['layout'] ) ) {
            $layout = $this->feedCptOptionsArray['layout'];
        }
        echo $this->metaboxFunctions->optionsHtmlForm( $layout, null, $this->feedCptId );
    }

    /**
     * Tab Colors Content
     *
     * Outputs Colors tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabColorsContent() {
        $layout = null;
        if ( isset( $this->feedCptOptionsArray, $this->feedCptOptionsArray['colors'] ) ) {
            $layout = $this->feedCptOptionsArray['colors'];
        }
        echo $this->metaboxFunctions->optionsHtmlForm( $layout, null, $this->feedCptId );
    }

    /**
     * Tab Facebook Feed
     *
     * Outputs Feed's settings tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabFacebookFeed() {?>
        <div class="fts-cpt-main-options">
            <?php

                echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['facebook'], null, $this->feedCptId );

            ?>

            <div class="clear"></div>
        </div>

        <div class="fts-cpt-extra-options">
            <?php

            $facebookAdditionalOptions = new FacebookAdditionalOptions();

            $facebook_add_all_options = $facebookAdditionalOptions->getAllOptions();

            //Facebook Like Button or Box Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_like_button_box_options'], null, $this->feedCptId );

            //Facebook Style Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_style_options'], null, $this->feedCptId );

            //Facebook Language Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_languages_options'], null, $this->feedCptId );

            //Facebook Reviews text and styles.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_reviews_text_styles'], null, $this->feedCptId );

            //Facebook Reviews and Overall Ratings styles.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_reviews_overall_rating_styles'], null, $this->feedCptId );

            //Facebook Grid Style Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_grid_style_options'], null, $this->feedCptId );

            //Facebook Loadmore Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_load_more_options'], null, $this->feedCptId );

            //Facebook Error Messages.
            echo $this->metaboxFunctions->optionsHtmlForm( $facebook_add_all_options['facebook_error_messages_options'], null, $this->feedCptId );

            $this->settingOptionsJs->facebookJs();
            ?>

            <div class="clear"></div>
        </div>
        <?php
    }

    /**
     * Tab Instagram Feed
     *
     * Outputs Feed's settings tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabInstagramFeed() { ?>
        <div class="fts-cpt-main-options">
        <?php
            echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['instagram'], null, $this->feedCptId );
            $this->settingOptionsJs->instagramJs();
        ?>
        </div>

        <div class="fts-cpt-extra-options">
            <?php
            $instagramAdditionalOptions = new InstagramAdditionalOptions();

            $instagram_add_all_options = $instagramAdditionalOptions->getAllOptions();

            // Instagram Follow Button Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $instagram_add_all_options['instagram_follow_btn_options'], null, $this->feedCptId );
            // Instagram Slider Navigation Colors
            echo $this->metaboxFunctions->optionsHtmlForm( $instagram_add_all_options['instagram_slider_color_options'], null, $this->feedCptId );
            // Instagram Load more button Options
            echo $this->metaboxFunctions->optionsHtmlForm( $instagram_add_all_options['instagram_load_more_options'], null, $this->feedCptId );
           ?>

            <div class="clear"></div>
        </div>

        <?php
    }

    /**
     * Tab Twitter Feed
     *
     * Outputs Feed's settings tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabTiktokFeed() { ?>
        <div class="fts-cpt-main-options">
            <?php
            echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['twitter'], null, $this->feedCptId );

            //JS for Twitter Options.
            $this->settingOptionsJs->twitterJs();
            ?>
            <div class="clear"></div>
        </div>
        <div class="fts-cpt-extra-options">
            <?php
            $twitterAdditionalOptions = new TwitterAdditionalOptions();

            $twitter_add_all_options = $twitterAdditionalOptions->getAllOptions();

            // Twitter Follow Button Options
            echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_follow_btn_options'], null, $this->feedCptId );
            // TikTok Language Options
            echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_language_options'], null, $this->feedCptId );
            // Twitter Video Player Options
            // echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_video_player_options'], null, $this->feedCptId );
            // Twitter Profile Photo Options
            echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_profile_photo_options'], null, $this->feedCptId );
            // Twitter Style Options
            echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_style_options'], null, $this->feedCptId );

            // FTS Premium ACTIVE
            if ( ! $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
                // Twitter Grid Styles
                echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_grid_style_options'], null, $this->feedCptId );
                // Twitter Load More Button Styles & Options
                 echo $this->metaboxFunctions->optionsHtmlForm( $twitter_add_all_options['twitter_load_more_options'], null, $this->feedCptId );
            }?>

            <div class="clear"></div>
        </div>

        <?php
    }


    /**
     * Tab YouTube Feed
     *
     * Outputs Feed's settings tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabYoutubeFeed() { ?>
        <div class="fts-cpt-main-options">
        <?php
            echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['youtube'], null, $this->feedCptId );

            $this->settingOptionsJs->youtubeJs();
        ?>
            <div class="clear"></div>
        </div>
        <div class="fts-cpt-extra-options">
            <?php
            $youtubeAdditionalOptions = new YoutubeAdditionalOptions();

            $youtube_add_all_options = $youtubeAdditionalOptions->getAllOptions();

            //YouTube Follow Button Options.
            echo $this->metaboxFunctions->optionsHtmlForm( $youtube_add_all_options['youtube_follow_btn_options'], null, $this->feedCptId );

            // FTS Premium ACTIVE
            if ( ! $this->feedFunctions->isExtensionActive( 'feed_them_social_premium' ) ) {
                //YouTube Load More Options.
                echo $this->metaboxFunctions->optionsHtmlForm( $youtube_add_all_options['youtube_load_more_options'], null, $this->feedCptId );
            }?>
            <div class="clear"></div>
        </div>

        <?php
    }

    /**
     * Tab Combined Streams Feed
     *
     * Outputs Feed's settings tab's content for metabox.
     *
     * @since 1.0.0
     */
    public function tabCombineStreamsFeed() { ?>
<div class="fts-cpt-main-options fts-cpt-main-options-combined">
    <?php

        echo $this->metaboxFunctions->optionsHtmlForm( $this->feedCptOptionsArray['combine'], null, $this->feedCptId );
        $this->settingOptionsJs->combineJs();

    ?>
    <div class="clear"></div>
</div>

        <?php
    }


    /**
     *  Export Options Meta Box
     *
     *  Copy Exported Feed Options input box. This is used for support purposes.
     *
     * @param $object
     * @since 1.0.0
     */
    public function ftsImportExportFeedOptionsMetaBox() {
        ?>
        <div class="fts-import-export-tabs">
            <ul class="fts-import-export-tab-nav">
                <li><a href="#fts-import-export-tab1">Export</a></li>
                <li><a href="#fts-import-export-tab2">Import</a></li>
            </ul>
            <div class="fts-import-export-tab-content">
                <div id="fts-import-export-tab1">
                    <div class="fts-export-feed-widget-wrap">
                        <p>
                            <label><?php echo esc_html__( 'Need Support with your feed or want to copy options to new feed?', 'feed-them-social' ); ?>
                                <input readonly="readonly" value="" onclick="this.select();"/>
                            </label>
                        </p>
                        <div class="publishing-action" style="text-align: right;">
                            <a href="javascript:;" id="fts-export-feed-options" class="button button-primary button-large"><?php echo esc_html__( 'Export', 'feed-them-social' ); ?></a>
                        </div>
                    </div>
                </div>
                <div id="fts-import-export-tab2">
                    <div class="fts-import-feed-widget-wrap">
                        <p>
                            <label><?php echo esc_html__( 'Helpful when debugging problems and copying options to another feed.', 'feed-them-social' ); ?>
                                <input value="" onclick="this.select();"/>
                            </label>
                        </p>
                        <div class="publishing-action" style="text-align: right;">
                            <a href="#fts-import-feed-options" id="fts-import-feed-options" class="button button-primary button-large"><?php echo esc_html__( 'Import', 'feed-them-social' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *  Shortcode Meta Box
     *
     *  copy & paste shortcode input box
     *
     * @param $object
     * @since 1.0.0
     */
    public function ftsShortcodeMetaBox() {
        ?>
        <div class="ft-gallery-meta-wrap">
            <?php

            $feed_id = $_GET['post'] ?? '';

            // Copy Shortcode
            ?>
            <p>
                <label><?php echo esc_html__( 'Copy and Paste this shortcode to any page, post or widget.', 'feed_them_social' ); ?>
                    <input readonly="readonly" value="[feed_them_social cpt_id=<?php echo esc_html( $feed_id ); ?>]" onclick="this.select();"/>
                </label>
            </p>
            <?php
            ?>
        </div>
        <?php
    }

    /**
     *  Duplicate Post As Draft
     * Function creates post duplicate as a draft and redirects then to the edit post screen
     *
     * @since 1.0.0
     */
    /**
     * Duplicate Post As Draft
     * Function creates post duplicate as a draft and redirects then to the edit post screen
     *
     * @since 1.0.0
     */
    public function ftsDuplicatePostAsDraft() {
        global $wpdb;

        // Verify the action is correct.
        if ( ! isset( $_REQUEST['action'] ) || 'ftsDuplicatePostAsDraft' !== $_REQUEST['action'] ) {
            wp_die( esc_html__( 'No Feed to duplicate has been supplied!', 'feed_them_social' ) );
        }

        /*
         * Nonce verification
         */
        if ( ! isset( $_GET['duplicate_nonce'] ) || ! wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) ) {
            return;
        }

        /*
         * get the original post id
         */
        $post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
        if ( ! $post_id ) {
            wp_die( esc_html__( 'No Feed to duplicate has been supplied!', 'feed_them_social' ) );
        }

        /**
         * Make sure that the user has the capability to duplicate this feed.
         */
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            wp_die( esc_html__( 'You are not allowed to duplicate this feed.', 'feed_them_social' ) );
        }

        /*
         * and all the original post data then
         */
        $post = get_post( $post_id );

        /*
         * Stop if post data doesn't exist.
         */
        if ( ! $post ) {
            wp_die( esc_html__( 'Gallery duplication failed, could not find original Gallery: ' . esc_html( $post_id ), 'feed_them_social' ) );
        }

        // All checks passed. Proceed with duplication.

        /*
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user    = wp_get_current_user();
        $new_post_author = $current_user->ID;

        /*
         * new post data array
         */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'publish',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order,
        );

        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post( $args );

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ( $taxonomies as $taxonomy ) {
            $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
            wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
        }

        /*
         * duplicate all post meta just in two SQL queries
         */
        $post_meta_results = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d", $post_id ) );

        if ( ! empty( $post_meta_results ) ) {
            foreach ( $post_meta_results as $meta_info ) {
                // Check the meta value and insert if it's not '_wp_old_slug'.
                if ( $meta_info->meta_value !== '_wp_old_slug' ) {
                    $wpdb->query(
                        $wpdb->prepare(
                            "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value ) VALUES ( %d, %s, %s )",
                            $new_post_id,
                            $meta_info->meta_key,
                            $meta_info->meta_value
                        )
                    );
                }
            }
        }

        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    }


    /**
     * Duplicate Post Link
     *
     * Add the duplicate link to action list for post_row_actions
     *
     * @param $actions
     * @param $post
     * @return mixed
     * @since 1.0.0
     */
    public function ftsDuplicatePostLink( $actions, $post ) {
        // make sure we only show the duplicate gallery link on our pages
        if ( current_user_can( 'edit_posts' ) && $_GET['post_type'] === FEED_THEM_SOCIAL_POST_TYPE ) {
            $actions['duplicate'] = '<a id="ft-gallery-duplicate-action" href="' . esc_url( wp_nonce_url( 'admin.php?action=ftsDuplicatePostAsDraft&post=' . $post->ID, basename( __FILE__ ), 'duplicate_nonce' ) ) . '" title="Duplicate this item" rel="permalink">' . esc_html__( 'Duplicate Feed', 'feed_them_social' ) . '</a>';
        }

        return $actions;
    }

    /**
     * Remove Edit Menu Links
     *
     * Remove Edit Menu Links from the edit.php page for FTS CPT.
     *
     * @param $actions
     * @param $post
     * @return mixed
     * @since 1.0.0
     */
    public function removeEditMenuLinks( $actions ) {
        // make sure we only show the duplicate gallery link on our pages
        if ( current_user_can( 'edit_posts' ) && $_GET['post_type'] === FEED_THEM_SOCIAL_POST_TYPE ) {
            // Unset View Link.
            unset($actions['view']);
            // Unset Quick Edit Link.
            unset($actions['inline hide-if-no-js']);
        }

        return $actions;
    }

    /**
     *  Duplicate Post ADD Duplicate Post Button
     *
     *  Add a button in the post/page edit screen to create a clone
     *
     * @since 1.0.0
     */
    public function ftsDuplicatePostAddDuplicatePostButton() {
        $current_screen = get_current_screen();
        $verify         = $_GET['post_type'] ?? '';
        // check to make sure we are not on a new fts post, because what is the point of duplicating a new one until we have published it?
        if ( $current_screen->post_type === FEED_THEM_SOCIAL_POST_TYPE && $verify !== FEED_THEM_SOCIAL_POST_TYPE ) {
            $id = $_GET['post'];
            ?>
            <div id="ft-gallery-duplicate-action">
                <a href="<?php echo esc_url( wp_nonce_url( 'admin.php?action=ftsDuplicatePostAsDraft&post=' . $id, basename( __FILE__ ), 'duplicate_nonce' ) ); ?>"
                   title="Duplicate this item"
                   rel="permalink"><?php esc_html_e( 'Duplicate Feed', 'feed_them_social' ); ?></a>
            </div>
            <?php
        }
    }
}
