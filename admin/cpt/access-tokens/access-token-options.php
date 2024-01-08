<?php
/**
 * Feed Them Social - Access_Options
 *
 * This is used to call all of the classes to retrieve Access Tokens from the social networks.
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial;

// Exit if accessed directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Access Options.
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class Access_Options {

	/**
	 * Feed Functions
	 *
	 * The Feed Functions Class
	 *
	 * @var object
	 */
	public $feed_functions;

	/**
	 * Feed CPT Option Array
	 *
	 * An array of Feed Settings. Set in admin/cpt/options/feeds-cpt-options.php
	 *
	 * @var array
	 */
	public $feed_cpt_options_array;

	/**
	 * Data Protection
	 *
	 * Data Protection Class for encryption.
	 *
	 * @var object
	 */
	public $data_protection;

    /**
     * Options Functions
     *
     * The settings Functions class.
     *
     * @var object
     */
    public $options_functions;

	/**
	 * Metabox Functions
	 *
	 * The Metabox Functions class.
	 *
	 * @var object
	 */
	public $metabox_functions;

	/**
	 * Construct
	 *
	 * Access Token Options Page constructor.
	 *
	 * @since 1.9.6
	 */
	public function __construct( $feed_functions, $feed_cpt_options, $metabox_functions, $data_protection, $options_functions ) {

        // Options Functions Class.
        $this->options_functions = $options_functions;

		// Set Feed Functions object.
		$this->feed_functions = $feed_functions;

		// Data Protection.
		$this->data_protection = $data_protection;

        // Metabox Functions.
		$this->metabox_functions = $metabox_functions;

		// Feed CPT Options Array.
		$this->feed_cpt_options_array = $feed_cpt_options->get_all_options();

        add_action( 'wp_ajax_fts_access_token_type_ajax', array( $this, 'fts_access_token_type_ajax' ) );

        // Facebook & Instagram get Access Token.
        add_shortcode( 'fts_fb_page_token', array( $this, 'fts_fb_page_token_func' ) );
    }

	/**
	 * Decrypt Access Token
	 *
	 * Decrypt the access token given.
	 *
	 * @since 4.0.0
	 */
	public function decrypt_access_token( $encrypted_token ) {
        // Was an encrypted token given?
        if( $encrypted_token ){
            // Decrypt the token.
	        return $this->data_protection->decrypt( $encrypted_token );
        }
        // Decryption didn't work.
        return false;
	}

	/**
	 * Get Access Tokens Options
	 *
	 * Get Access Token Options based on Feed Type and Feed CPT ID
	 *
	 * @since 4.0.0
	 */
	public function get_access_token_options( $feed_type, $feed_cpt_id ) {?>

        <?php
		if($feed_type){
			// Determine Feed Type. Call Class. Return Options.
			switch ($feed_type){
				case 'facebook-feed-type':
					// Facebook Access Functions.
					$facebook_access_functions = new Facebook_Access_Functions( $this->feed_functions, $this->data_protection );

                    // Load Facebook Token Option Fields.
                    echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['facebook_token_options'], null, $feed_cpt_id );

                    ?>

                    <div class="facebook-access-token-placeholder">
                        <?php
                            // Get Access button for Facebook.
                            $facebook_access_functions->get_access_token_button( $feed_cpt_id );
                        ?>
                    </div>

                <?php

                    break;

				case 'instagram-feed-type':
					// Instagram Access Functions.
					$instagram_access_functions = new Instagram_Access_Functions( $this->feed_functions, $this->data_protection );

                    // Load Instagram Token Option Fields.
                    echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['instagram_token_options'], null, $feed_cpt_id );

                    // Load the options.
                    $instagram_access_functions->get_access_token_button( $feed_cpt_id );

					break;

                case 'instagram-business-feed-type':
	                // Instagram Business Access Functions.
	                $instagram_business_access_functions = new Instagram_Business_Access_Functions( $this->feed_functions, $this->data_protection );

                    // Load Instagram Business Token Option Fields.
                    echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['instagram_business_token_options'], null, $feed_cpt_id );
                    ?>

                    <div class="instagram-facebook-access-token-placeholder">
                        <?php
                            // Load the options.
                            $instagram_business_access_functions->get_access_token_button( $feed_cpt_id );
                        ?>
                    </div>

                <?php
	               break;

				case 'twitter-feed-type':
					// Twitter Access Functions.
					$twitter_access_functions = new Twitter_Access_Functions( $this->feed_functions, $this->data_protection );

                    // Load Twitter Token Option Fields.
                    echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['twitter_token_options'], null, $feed_cpt_id );

                    // Load the options.
					$twitter_access_functions->get_access_token_button( $feed_cpt_id );

					break;

				case 'youtube-feed-type':
					// YouTube Access Functions.
					$youtube_access_functions = new Youtube_Access_Functions( $this->feed_functions, $this->data_protection );

                    // Load YouTube Token Option Fields.
                    echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['youtube_token_options'], null, $feed_cpt_id );

                    // Load the options.
					$youtube_access_functions->get_access_token_button( $feed_cpt_id );

					break;

                case 'combine-streams-feed-type':
                    ?>
                <div class="combine-streams-feed-wrap">
                    <?php
                        // Load Combine Token Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_instagram_token_options'], null, $feed_cpt_id );

                        ?>
                    <div class="fts-clear"></div>

                        <div class="combine-instagram-access-token-placeholder">
                            <div class="combine-instagram-basic-access-token-placeholder">
                                <?php
                                $instagram_access_functions = new Instagram_Access_Functions( $this->feed_functions, $this->data_protection );

                                // Load Instagram Token Option Fields.
                                echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['instagram_token_options'], null, $feed_cpt_id );

                                // Load the options.
                                $instagram_access_functions->get_access_token_button( $feed_cpt_id );
                                ?>
                            </div>

                            <div class="combine-instagram-business-access-token-placeholder">
                            <?php

                                // Instagram Business Access Functions.
                                $instagram_business_access_functions = new Instagram_Business_Access_Functions( $this->feed_functions, $this->data_protection );

                                // Load Instagram Business Token Option Fields.
                                echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['instagram_business_token_options'], null, $feed_cpt_id );

                                // Load the options.
                                $instagram_business_access_functions->get_access_token_button( $feed_cpt_id );
                            ?>
                            </div>
                        </div>
                    <?php
                        // Load Combine Instagram Token Select Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_instagram_token_select_options'], null, $feed_cpt_id );

                        // Load Combine Facebook Token Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_facebook_token_options'], null, $feed_cpt_id );

                    ?>
                        <div class="combine-facebook-access-token-placeholder">
                                <?php
                        // Facebook Access Functions.
                        $facebook_access_functions = new Facebook_Access_Functions( $this->feed_functions, $this->data_protection );

                        // Load Facebook Token Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['facebook_token_options'], null, $feed_cpt_id );

                        // Get Access button for Facebook.
                        $facebook_access_functions->get_access_token_button( $feed_cpt_id );

                                ?>
                     </div>

                        <?php
                        // Load Combine Twitter Token Select Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_twitter_token_select_options'], null, $feed_cpt_id );

                        ?>

                        <div class="combine-twitter-access-token-placeholder">
                            <?php
                            // Twitter Access Functions.
                            $twitter_access_functions = new Twitter_Access_Functions( $this->feed_functions, $this->data_protection );

                            // Load Twitter Token Option Fields.
                            echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['twitter_token_options'], null, $feed_cpt_id );

                            // Get Access button for Facebook.
                            $twitter_access_functions->get_access_token_button( $feed_cpt_id );

                            ?>
                        </div>
                        <?php

                        // Load Combine Twitter Token Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_twitter_token_options'], null, $feed_cpt_id );


                        // Load Combine YouTube Token Select Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_youtube_token_select_options'], null, $feed_cpt_id );

                        ?>

                        <div class="combine-youtube-access-token-placeholder">
                            <?php
                            // Twitter Access Functions.
                            $youtube_access_functions = new Youtube_Access_Functions( $this->feed_functions, $this->data_protection );

                            // Load YouTube Token Option Fields.
                            echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['youtube_token_options'], null, $feed_cpt_id );

                            // Get Access button for youtube.
                            $youtube_access_functions->get_access_token_button( $feed_cpt_id );

                            ?>
                        </div>
                        <?php

                        // Load Combine YouTube Token Option Fields.
                        echo $this->metabox_functions->options_html_form( $this->feed_cpt_options_array['combine_youtube_token_options'], null, $feed_cpt_id );
                      ?>
                </div>
                    <div class="fts-clear"></div>
                        <?php

                    break;
			}
			// Return Access Options.

		}
	}


    /**
     * FTS FB Options Page Function
     *
     * Display FB Page tokens for users
     *
     * @return mixed
     * @since 2.1.4
     */
    public function fts_fb_page_token_func() {

        $fts_fb_page_token_users_nonce = wp_create_nonce( 'fts-fb-page-token-users-nonce' );

        if ( wp_verify_nonce( $fts_fb_page_token_users_nonce, 'fts-fb-page-token-users-nonce' ) ) {

            // Make sure it's not ajaxing!
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feed_functions->get_random_string() );
            } //End make sure it's not ajaxing!

            ob_start();

            if ( ! isset( $_GET['locations'] ) ) {

                // SRL 4-23-22. Locations: This endpoint is not supported for Pages that have been migrated to the New Pages Experience. So we need to make an exception.
                $fb_url = isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] ? wp_remote_fopen( 'https://graph.facebook.com/me/accounts?fields=locations{name,id,page_username,locations,store_number,store_location_descriptor,access_token},name,id,link,has_transitioned_to_new_page_experience,access_token&access_token=' . $_GET['code'] . '&limit=500' ) : wp_remote_fopen( 'https://graph.facebook.com/me/accounts?fields=instagram_business_account{id,username,profile_picture_url},locations{instagram_business_account{profile_picture_url,id,username},name,id,page_username,locations,store_number,store_location_descriptor,access_token},name,id,link,access_token&access_token=' . $_GET['code'] . '&limit=500' );

                $test_fb_app_token_response = json_decode( $fb_url );

                // SRL 4-23-22. For now we are just going to check for error, if error then that would mean the first object in array is a new page experience.
                // if is new page has_transitioned_to_new_page_experience => 1 This could be expanded in the future by creating a foreach loops to check each page
                // but then you have to run a call for each page and that seems like overkill if you have hundreds of pages. FB should come up with a simpler way.
                if( isset( $test_fb_app_token_response->error ) ){
                    // Possibly the user is on a new page experience so let's run the call without locations.
                    $fb_url = isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] ? wp_remote_fopen( 'https://graph.facebook.com/me/accounts?fields=has_transitioned_to_new_page_experience,name,id,link,access_token&access_token=' . $_GET['code'] . '&limit=500' ) : wp_remote_fopen( 'https://graph.facebook.com/me/accounts?fields=has_transitioned_to_new_page_experience,instagram_business_account{id,username,profile_picture_url},name,id,link,access_token&access_token=' . $_GET['code'] . '&limit=500' );

                }
                if ( isset( $_REQUEST['next_url'] ) && !empty( $_REQUEST['next_url'] ) ) {
                    $next_url_host = parse_url( $_REQUEST['next_url'],  PHP_URL_HOST );
                    if ( 'graph.facebook.com' !== $next_url_host && $next_url_host !== 'graph.instagram.com' ) {
                        wp_die( esc_html__( 'Invalid Facebook URL', 'feed-them-social' ), 403 );
                    }
                }
                $fb_token_response          = isset( $_REQUEST['next_url'] ) ? wp_remote_fopen( esc_url( $_REQUEST['next_url'] ) ) : $fb_url;
                $test_fb_app_token_response = json_decode( $fb_token_response );

                // Test.
                //print_r( $test_fb_app_token_response );

                $_REQUEST['next_url']       = isset( $test_fb_app_token_response->paging->next ) ? esc_url( $test_fb_app_token_response->paging->next ) : '';
            } else {

                if ( isset( $_GET['next_location_url'] ) && !empty( $_GET['next_location_url'] ) ) {
                    $next_location_url_host = parse_url( $_REQUEST['next_location_url'],  PHP_URL_HOST );
                    if ( 'graph.facebook.com' !== $next_location_url_host && $next_url_host !== 'graph.instagram.com' ) {
                        wp_die( esc_html__( 'Invalid Facebook URL', 'feed-them-social' ), 403 );
                    }
                }

                $fb_token_response          = isset( $_REQUEST['next_location_url'] ) ? wp_remote_fopen( esc_url( $_REQUEST['next_location_url'] ) ) : '';
                $test_fb_app_token_response = json_decode( $fb_token_response );
            }

            // IF we still get an error then show a formatted response for the user.
            if( !empty( $test_fb_app_token_response->error ) ){
                echo '<div class="fts-fb-error-message-wrap">';
                echo '<p>';
                echo '<strong>Facebook Response: </strong>';
                echo $test_fb_app_token_response->error->message . ' Code #';
                echo $test_fb_app_token_response->error->code . '. ';
                echo $test_fb_app_token_response->error->error_user_title;
                echo $test_fb_app_token_response->error->error_user_msg;

                if( 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] ){
                    echo '</p> <strong>Helpful Tips:</strong> Make sure you are an admin of the page or pages you are choosing. Next you will see, "What SlickRemix is allowed to do." The 2 options should be, Read content posted on the Page and Show a list of the Pages you manage. Make sure and choose Yes for both.<a href="#" style="display: none" target="_blank">More Tips</a>';
                }
                if( 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] ) {
                    echo '</p> <strong>Helpful Tips:</strong> Make sure you are an admin of the page or pages you are choosing. Next you will see, "What SlickRemix is allowed to do." The 3 options should be, Access profile and posts from the Instagram account connected to your Page, Read content posted on the Page and Show a list of the Pages you manage. Make sure and choose Yes for all 3.<a href="#" style="display: none" target="_blank">More Tips</a>';
                }
                echo '</div>';
                return false;
            }

            // Make sure it's not ajaxing!
            if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                // ******************
                // Load More BUTTON Start
                // ******************
                ?>
                <div class="fts-clear"></div>
                <?php
            } //End make sure it's not ajaxing!

            $build_shortcode = 'fts_fb_page_token';

            // Make sure it's not ajaxing!
        if ( ! isset( $_GET['load_more_ajaxing'] ) ) {

            $reviews_token = isset( $_GET['reviews_token'] ) ? 'yes' : 'no';
            ?>
            <div id="fb-list-wrap">
                <div class="fts-pages-info"> <?php echo esc_html__( 'Click on a page below and click save.', 'feed-them-social' ); ?></div>
                <ul class="fb-page-list fb-page-master-list">
                    <?php
                    } //End make sure it's not ajaxing!

                    foreach ( $test_fb_app_token_response->data as $data ) {

                        // if( !empty( $data->instagram_business_account )  ){
                        $data_id        = isset( $data->instagram_business_account ) && 'facebook' !== $_GET['feed_type'] ? $data->instagram_business_account->id : $data->id;
                        $data_user_name = isset( $data->instagram_business_account ) && 'facebook' !== $_GET['feed_type'] ? '<span class="fts-insta-icon">' . $data->instagram_business_account->username . '</span><span class="fts-arrow-icon"></span><span class="fts-fb-icon">' . $data->name . '</span>' : $data->name;
                        $data_thumbnail = isset( $data->instagram_business_account->profile_picture_url ) && 'facebook' !== $_GET['feed_type'] ? $data->instagram_business_account->profile_picture_url : 'https://graph.facebook.com/' . $data->id . '/picture';
                        ?>
                        <li class="fts-fb-main-page-li">
                            <div class="fb-click-wrapper">
                                <div class="fb-image">
                                    <img border="0" height="50" width="50"
                                         src="<?php echo esc_url( $data_thumbnail ); ?>"/>
                                </div>
                                <div class="fb-name-wrap"><span class="fb-name">
					<?php
                    echo $data_user_name;
                    if ( isset( $data->store_number, $data->store_location_descriptor ) ) {
                        print '(' . $data->store_location_descriptor . ')';
                    }
                    ?>
									</span></div>
                                <div class="fb-other-wrap">
                                    <small>
                                        <?php echo esc_html__( 'ID: ', 'feed-them-social' ); ?>
                                        <span class="fts-api-facebook-id"><?php echo esc_html( $data_id ); ?></span>
                                        <?php echo isset( $data->store_number ) ? esc_html( '| Location: ' . $data->store_number, 'feed-them-social' ) : ''; ?>
                                    </small>
                                </div>
                                <div class="page-token"><?php echo esc_attr( $data->access_token ); ?></div>

                                <div class="feed-them-social-admin-submit-btn fts-token-save">
                                    <?php echo esc_html__( 'Save', 'feed-them-social'); ?>
                                    </div>

                                <div class="fts-clear"></div>
                            </div>
                            <?php
                            $_REQUEST['next_location_url'] = isset( $data->locations->paging->next ) ? esc_url( $data->locations->paging->next ) : '';
                            $remove_class_or_not           = isset( $data->locations->paging->next ) ? 'fb-sublist-page-id-' . esc_attr( $data_id ) : '';
                            if ( isset( $data->locations->data ) ) {
                                $location_count     = count( $data->locations->data );
                                $location_plus_sign = isset( $data->locations->paging->next ) ? '+' : '';
                                $location_text      = 1 === $location_count ? esc_html( $location_count . ' ' . esc_html__( 'Location for', 'feed-them-social' ) ) : esc_html( $location_count . $location_plus_sign . ' ' . esc_html__( 'Locations for', 'feed-them-social' ) );
                                // if the locations equal 3 or less we will set the location container height to auto so the scroll loadmore does not fire.
                                $location_scroll_loadmore_needed_check = $location_count <= 3 ? 'height:auto !important' : 'height: 200px !important;';
                            }

                            if ( ! isset( $_GET['locations'] ) && isset( $data->locations->data ) ) {
                                ?>
                                <div class="fts-fb-location-text-wrap"><?php echo esc_html( $location_text . ' ' . $data->name ); ?></div>
                                <ul class="fb-page-list fb-sublist <?php echo esc_attr( $remove_class_or_not ); ?>"
                                    style="<?php echo esc_attr( $location_scroll_loadmore_needed_check ); ?>">
                                    <?php
                                    foreach ( $data->locations->data as $location ) {

                                        // if ( !empty( $location->instagram_business_account ) ) {
                                        $loc_data_id        = isset( $location->instagram_business_account ) && 'facebook' !== $_GET['feed_type'] ? $location->instagram_business_account->id : $location->id;
                                        $loc_data_user_name = isset( $location->instagram_business_account ) && 'facebook' !== $_GET['feed_type'] ? '<span class="fts-insta-icon"></span>' . $location->instagram_business_account->username . '<span class="fts-arrow-icon"></span><span class="fts-fb-icon"></span>' . $location->name : $location->name;
                                        $loc_data_thumbnail = isset( $location->instagram_business_account->profile_picture_url ) && 'facebook' !== $_GET['feed_type'] ? $location->instagram_business_account->profile_picture_url : 'https://graph.facebook.com/' . $location->id . '/picture';

                                        ?>
                                        <li>
                                            <div class="fb-click-wrapper">
                                                <div class="fb-image">
                                                    <img border="0" height="50" width="50"
                                                         src="<?php echo esc_url( $loc_data_thumbnail ); ?>"/>
                                                </div>
                                                <div class="fb-name-wrap"><span
                                                            class="fb-name"><?php echo $loc_data_user_name; ?>
                                                        <?php
                                                        if ( isset( $location->store_location_descriptor ) ) {
                                                            echo '(' . esc_html( $location->store_location_descriptor ) . ')';
                                                        }
                                                        ?>
													</span></div>
                                                <div class="fb-other-wrap">
                                                    <small>
                                                        <?php echo esc_html__( 'ID: ', 'feed-them-social' ); ?>
                                                        <span class="fts-api-facebook-id"><?php echo esc_html( $loc_data_id ); ?></span>
                                                        <?php
                                                        if ( isset( $location->store_number ) ) {
                                                            print '| ';
                                                            esc_html__( 'Location:', 'feed-them-social' );
                                                            print ' ' . esc_html( $location->store_number );
                                                        }
                                                        ?>
                                                    </small>
                                                </div>

                                                <div class="page-token"><?php echo esc_html( $location->access_token ); ?></div>

                                                    <div class="feed-them-social-admin-submit-btn fts-token-save">
                                                        <?php echo esc_html__( 'Save', 'feed-them-social'); ?>
                                                    </div>

                                                <div class="fts-clear"></div>
                                            </div>
                                        </li>

                                        <?php
                                        // }
                                    }
                                    ?>
                                </ul>

                            <?php
                            // Make sure it's not ajaxing locations!
                            if ( ! isset( $_GET['locations'] ) && isset( $data->locations->paging->next ) ) {
                                echo '<div id="loadMore_' . esc_attr( $data_id ) . '_location" class="fts-fb-load-more" style="background:none !Important;">' . esc_html__( 'Scroll to view more Locations', 'feed-them-instagram' ) . '</div>';
                            }//End Check

                            // Make sure it's not ajaxing locations!
                            if ( ! isset( $_GET['locations'] ) ) {
                            $time       = time();
                            $nonce      = wp_create_nonce( $time . 'load-more-nonce' );
                            $facebook_page_id = $data_id;
                            ?>
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery(".fb-sublist-page-id-<?php echo esc_js( $facebook_page_id ); ?>").bind("scroll", function () {
                                            if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
                                                if (!jQuery('.fts-no-more-locations-<?php echo esc_js( $facebook_page_id ); ?>').length) {
                                                    jQuery("#loadMore_<?php echo esc_js( $facebook_page_id ); ?>_location").addClass('fts-fb-spinner');
                                                    var button = jQuery('#loadMore_<?php echo esc_js( $facebook_page_id ); ?>_location').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                                                    console.log(button);
                                                    var build_shortcode = "<?php echo esc_js( $build_shortcode ); ?>";
                                                    var yes_ajax = "yes";
                                                    var fts_d_name = "<?php echo esc_js( $facebook_page_id ); ?>";
                                                    var fts_security = "<?php echo esc_js( $nonce ); ?>";
                                                    var fts_time = "<?php echo esc_js( $time ); ?>";
                                                    var fts_reviews_feed = "<?php echo esc_js( $reviews_token ); ?>";
                                                    jQuery.ajax({
                                                        data: {
                                                            action: "my_fts_fb_load_more",
                                                            next_location_url: nextURL_location_<?php echo sanitize_key( $facebook_page_id ); ?>,
                                                            fts_dynamic_name: fts_d_name,
                                                            rebuilt_shortcode: build_shortcode,
                                                            load_more_ajaxing: yes_ajax,
                                                            fts_security: fts_security,
                                                            fts_time: fts_time,
                                                            feed_name: build_shortcode,
                                                            fts_reviews_feed: fts_reviews_feed,
                                                            locations: 'yes'
                                                        },
                                                        type: 'GET',
                                                        url: ajaxurl,
                                                        success: function (data) {
                                                            console.log('Well Done and got this from sever: ' + data);
                                                            jQuery('.fb-sublist-page-id-<?php echo esc_js( $facebook_page_id ); ?>').append(data).filter('.fb-sublist-page-id-<?php echo esc_js( $facebook_page_id ); ?>').html();
                                                            jQuery('.fb-sublist-page-id-<?php echo esc_js( $facebook_page_id ); ?>').animate({scrollTop: '+=100px'}, 800); // scroll down a 100px after new items are added



                                                            <?php if ( isset( $data->locations->paging->next ) && $data->locations->paging->next === $_REQUEST['next_location_url'] ) { ?>
                                                            jQuery('#loadMore_<?php echo esc_js( $facebook_page_id ); ?>_location').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb fts-no-more-locations-<?php echo esc_js( $facebook_page_id ); ?>" style="background:none !important"><?php echo esc_html( 'All Locations loaded', 'feed-them-social' ); ?></div>');
                                                            jQuery('#loadMore_<?php echo esc_js( $facebook_page_id ); ?>_location').removeAttr('id');
                                                            <?php } ?>
                                                            jQuery("#loadMore_<?php echo esc_js( $facebook_page_id ); ?>_location").removeClass('fts-fb-spinner');
                                                        }
                                                    }); // end of ajax()
                                                    return false;

                                                } //stop ajax from submitting again if the fts-no-more-locations class is found

                                            }
                                        }); // end of form.submit

                                    }); // end of document.ready
                                </script>
                            <?php
                            } //END Make sure it's not ajaxing locations
                            ?>
                                <script>var nextURL_location_<?php echo sanitize_key( $facebook_page_id ); ?>= "<?php echo isset( $data->locations->paging->next ) ? esc_url( $data->locations->paging->next ) : ''; ?>";</script>
                            <?php } ?>
                        </li>

                        <?php

                        // }
                    }  // foreach loop of locations

                    // Make sure it's not ajaxing!
                    if ( ! isset( $_GET['load_more_ajaxing'] ) ) {
                    ?>
                </ul>
                <div class="fts-clear"></div>
            </div>
            <?php
        } //End make sure it's not ajaxing

            // Make sure it's not ajaxing!
            if ( ! isset( $_GET['load_more_ajaxing'] ) && ! isset( $_GET['locations'] ) ) {
                $fts_dynamic_name = isset( $_REQUEST['fts_dynamic_name'] ) ? sanitize_key( $_REQUEST['fts_dynamic_name'] ) : '';
                $time             = time();
                $nonce            = wp_create_nonce( $time . 'load-more-nonce' );
                ?>
                <script>
                    jQuery(document).ready(function () {

                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").click(function () {

                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
                            var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                            console.log(button);
                            var build_shortcode = "<?php echo esc_js( $build_shortcode ); ?>";
                            var yes_ajax = "yes";
                            var fts_d_name = "<?php echo sanitize_key( $fts_dynamic_name ); ?>";
                            var fts_security = "<?php echo esc_js( $nonce ); ?>";
                            var fts_time = "<?php echo esc_js( $time ); ?>";
                            var fts_reviews_feed = "<?php echo esc_js( $reviews_token ); ?>";
                            jQuery.ajax({
                                data: {
                                    action: "my_fts_fb_load_more",
                                    next_url: nextURL_<?php echo sanitize_key( $fts_dynamic_name ); ?>,
                                    fts_dynamic_name: fts_d_name,
                                    rebuilt_shortcode: build_shortcode,
                                    load_more_ajaxing: yes_ajax,
                                    fts_security: fts_security,
                                    fts_time: fts_time,
                                    feed_name: build_shortcode,
                                    fts_reviews_feed: fts_reviews_feed
                                },
                                type: 'GET',
                                url: ajaxurl,
                                success: function (data) {
                                    console.log('Well Done and got this from sever: ' + data);
                                    jQuery('.fb-page-master-list').append(data).filter('.fb-page-list').html();

                                    jQuery('.post-type-fts .wrap form#post div.fts-token-save').click( function (e) {
                                       // alert('test');
                                        e.preventDefault();
                                        fts_ajax_cpt_save_token();
                                    });

                                    var fb = ".fb-page-list .fb-click-wrapper";
                                    $('#fb-list-wrap').show();
                                    //alert("reviews_token");

                                    $(".feed-them-social-admin-submit-btn").click(function () {
                                        // alert('test');
                                        // Must use esc_url_raw instead of esc_url otherwise it will encode the & and causes the url to not load
                                        // correctly at times.
                                        var newUrl = "<?php echo esc_url_raw( admin_url( 'post.php?post=' .$_GET['post'] . '&action=edit' ) ); ?>";
                                        history.replaceState({}, null, newUrl);
                                    });

                                    $(fb).click(function () {
                                        var facebook_page_id = $(this).find('.fts-api-facebook-id').html();
                                        var token = $(this).find('.page-token').html();
                                        // alert(token);
                                        var name = $(this).find('.fts-insta-icon').html();
                                        var fb_name = $(this).find('.fts-fb-icon').html();

                                        <?php if ( isset( $_GET['feed_type'] ) && 'instagram' === $_GET['feed_type'] ) { ?>
                                            $("#fts_facebook_instagram_custom_api_token").val(token);
                                            $("#fts_facebook_instagram_custom_api_token_user_id").val(facebook_page_id);
                                            $("#fts_facebook_instagram_custom_api_token_user_name").val(name);
                                            $("#fts_facebook_instagram_custom_api_token_fb_user_name").val(fb_name);
                                        <?php }
                                        else {
                                        ?>
                                            $("#fts_facebook_custom_api_token").val(token);
                                            $("#fts_facebook_custom_api_token_user_id").val(facebook_page_id);
                                            $("#fts_facebook_custom_api_token_user_name").val(fb_name);
                                        <?php } ?>

                                        $('.fb-page-list .feed-them-social-admin-submit-btn').hide();
                                        $(this).find('.feed-them-social-admin-submit-btn').toggle();
                                        //   alert(name + token)
                                    });


                                    if (!nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?> || 'no more' === nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>) {
                                        jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php echo esc_js( 'No More Pages', 'feed-them-social' ); ?></div>');
                                        jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').removeAttr('id');
                                    }
                                    jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<?php echo esc_js( 'Load More', 'feed-them-social' ); ?>');
                                    //	jQuery('#loadMore_< ?php echo  $fts_dynamic_name ?>').removeClass('flip360-fts-load-more');
                                    jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").removeClass('fts-fb-spinner');


                                }
                            }); // end of ajax()
                            return false;
                        }); // end of form.submit
                    }); // end of document.ready
                </script>
                <?php

            } //END Make sure it's not ajaxing
            ?>
            <script>
                <?php if ( ! isset( $_GET['locations'] ) ) { ?>
                var nextURL_<?php echo sanitize_key( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_url( $_REQUEST['next_url'] ); ?>";
                // alert('nextURL_<?php //echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>');
                <?php } ?>


                if (document.querySelector('#fts-fb-token-wrap .fts-pages-info') !== null) {
                    jQuery(".fts-successful-api-token.default-token").hide();
                }
                <?php if ( 'yes' === $reviews_token || isset( $_GET['fts_reviews_feed'] ) && 'yes' === $_GET['fts_reviews_feed'] ) { ?>
                if (document.querySelector('.default-token') !== null) {
                    jQuery(".default-token").show();
                }

                <?php } ?>
                        $ = jQuery;
                    $(".feed-them-social-admin-submit-btn").click(function () {
                        // alert('test');
                        // Must use esc_url_raw instead of esc_url otherwise it will encode the & and causes the url to not load
                        // correctly at times.
                        var newUrl = "<?php echo esc_url_raw( admin_url( 'post.php?post=' .$_GET['post'] . '&action=edit' ) ); ?>";
                        history.replaceState({}, null, newUrl);
                    });

                    var fb = ".fb-page-list .fb-click-wrapper";
                    $('#fb-list-wrap').show();
                    //alert("reviews_token");

                    $( fb ).click(function () {
                        var facebook_page_id = $(this).find('.fts-api-facebook-id').html();
                        var token = $(this).find('.page-token').html();
                        var name = $(this).find('.fts-insta-icon').html();


                        // alert(name);
                        <?php if ( isset( $_GET['feed_type'] ) && 'instagram' === $_GET['feed_type'] ) { ?>
                            var fb_name = $(this).find('.fts-fb-icon').html();
                        $("#fts_facebook_instagram_custom_api_token").val(token);
                        $("#fts_facebook_instagram_custom_api_token_user_id").val(facebook_page_id);
                        $("#fts_facebook_instagram_custom_api_token_user_name").val(name);
                            $("#fts_facebook_instagram_custom_api_token_fb_user_name").val(fb_name);
                            <?php }
                            else {
                        ?>
                            var fb_name = $(this).find('.fb-name').html();
                        $("#fts_facebook_custom_api_token").val(token);
                        $("#fts_facebook_custom_api_token_user_id").val(facebook_page_id);
                            $("#fts_facebook_custom_api_token_user_name").val(fb_name);
                        <?php } ?>
                        $('.fb-page-list .feed-them-social-admin-submit-btn').hide();
                        $(this).find('.feed-them-social-admin-submit-btn').toggle();
                        //   alert(name + token)
                    });

            </script>
            <?php
            // Make sure it's not ajaxing!
            if ( ! isset( $_GET['load_more_ajaxing'] ) && isset( $test_fb_app_token_response->paging->next ) && ! isset( $_GET['locations'] ) ) {
                $fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );
                echo '<div class="fts-clear"></div>';

                echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more">' . esc_html( 'Load More', 'feed-them-social' ) . '</div>';
            }//End make sure it's not ajaxing

            // Lastly if we can't find a next url we unset the next url from the page to not let the  Load More be active.
            if ( isset( $_GET['locations'] ) ) {
                unset( $_REQUEST['next_location_url'] );
            } else {
                unset( $_REQUEST['next_url'] );
            }
            return ob_get_clean();
        }
        exit;
    }


    /**
     * FTS Instagram Token Ajax
     *
     * This will save the encrypted version of the token to the database and return the original token to the input field upon page submit.
     *
     * @since 2.9.7.2
     */
    public function fts_access_token_type_ajax() {

        check_ajax_referer( 'fts_update_access_token' );
        
        $feed_type = $_REQUEST['feed_type'];
        $cpt_id    = $_REQUEST['cpt_id'];
        $combined  = $_REQUEST['feed_combined'];

        if( 'false' === $combined  ) {
            // This check is in place because the combine tab can also load the access token options, however we don't want to
            // save the feed_type in this case because we want to remain on the combine tab.
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'feed_type', $feed_type, true, $cpt_id, false );
        }

        if( 'basic' === $combined || 'business' === $combined ) {
            // This check is in place because the combine tab can also load the access token options, however we don't want to
            // save the feed_type in this case because we want to remain on the combine tab.

            // These 2 options are to save the combine instagram type if a user clicks on one of the tabs. The reason we need to do this is so
            // when the user clicks on the get access token button the user is taken away from the site to get the token on, fb, instagram etc.
            // then returned to the users previously selected combine instagram tab with the option selected to yes.
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'combine_instagram_type', $combined, true, $cpt_id, false );
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'combine_instagram', 'yes', true, $cpt_id, false );

        }

        if( 'combined-facebook' === $combined ) {
            // This option is to save the combine facebook type if a user clicks on one of the tabs. The reason we need to do this is so
            // when the user clicks on the get access token button the user is taken away from the site to get the token on, fb, instagram etc.
            // then returned to the users previously selected combine instagram tab with the option selected to yes.
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'combine_facebook', 'yes', true, $cpt_id, false );
        }

        if( 'combined-twitter' === $combined ) {
            // This option is to save the combine twitter type if a user clicks on one of the tabs. The reason we need to do this is so
            // when the user clicks on the get access token button the user is taken away from the site to get the token on, fb, instagram etc.
            // then returned to the users previously selected combine instagram tab with the option selected to yes.
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'combine_twitter', 'yes', true, $cpt_id, false );
        }

        if( 'combined-youtube' === $combined ) {
            // This option is to save the combine youtube type if a user clicks on one of the tabs. The reason we need to do this is so
            // when the user clicks on the get access token button the user is taken away from the site to get the token on, fb, instagram etc.
            // then returned to the users previously selected combine instagram tab with the option selected to yes.
            $this->options_functions->update_single_option( 'fts_feed_options_array', 'combine_youtube', 'yes', true, $cpt_id, false );
        }

        echo $this->get_access_token_options( $feed_type, $cpt_id );

        wp_die();
    }
}//end class