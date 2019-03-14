<?php
namespace feedthemsocial;

/**
 * Class Feed Them Social Functions
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class feed_them_social_functions
{

    /**
     * Construct
     *
     * Functions constructor.
     *
     * @since 1.9.6
     */
    public function __construct ()
    {
        $root_file = plugin_dir_path( dirname( __FILE__ ) );
        $this->premium = str_replace( 'feed-them-social/', 'feed-them-premium/', $root_file );
        $this->facebook_carousel_premium = str_replace( 'feed-them-social/', 'feed-them-carousel-premium/', $root_file );
        $this->facebook_reviews = str_replace( 'feed-them-social/', 'feed-them-social-facebook-reviews/', $root_file );

        // FTS Activation Function. Commenting out for future use. SRL!
        register_deactivation_hook( __FILE__, array($this, 'fts_get_check_plugin_version') );
        // Widget Code!
        add_filter( 'widget_text', 'do_shortcode' );
        // This is for the fts_clear_cache_ajax submission!
        if ( 'show-admin-bar-menu' === get_option( 'fts_admin_bar_menu' ) ) {
            add_action( 'init', array($this, 'fts_clear_cache_script') );
            add_action( 'wp_head', array($this, 'my_fts_ajaxurl') );
            add_action( 'wp_ajax_fts_clear_cache_ajax', array($this, 'fts_clear_cache_ajax') );
        }
        add_action( 'wp_ajax_fts_refresh_token_ajax', array($this, 'fts_refresh_token_ajax') );
        add_action( 'wp_ajax_fts_instagram_token_ajax', array($this, 'fts_instagram_token_ajax') );

        if ( is_admin() || is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) || is_plugin_active( 'fts-bar/fts-bar.php' ) ) {
            // Load More Options!
            add_action( 'wp_ajax_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more') );
            add_action( 'wp_ajax_nopriv_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more') );
            add_action( 'wp_ajax_my_fts_fb_options_page_load_more', array($this, 'my_fts_fb_options_page_load_more') );
        }

        add_shortcode( 'fts_fb_page_token', array($this, 'fts_fb_page_token_func') );
    }

    /**
     * Init
     *
     * For Loading in the Admin.
     *
     * @since 1.9.6
     */
    public function init ()
    {
        if ( is_admin() ) {
            // Register Settings!
            add_action( 'admin_init', array($this, 'fts_settings_page_register_settings') );
            add_action( 'admin_init', array($this, 'fts_facebook_style_options_page') );
            add_action( 'admin_init', array($this, 'fts_twitter_style_options_page') );
            add_action( 'admin_init', array($this, 'fts_instagram_style_options_page') );
            add_action( 'admin_init', array($this, 'fts_pinterest_style_options_page') );
            add_action( 'admin_init', array($this, 'fts_youtube_style_options_page') );

            // Adds setting page to FTS menu!
            add_action( 'admin_menu', array($this, 'feed_them_main_menu') );
            add_action( 'admin_menu', array($this, 'feed_them_submenu_pages') );
            // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA!
            add_action( 'admin_enqueue_scripts', array($this, 'feed_them_admin_css') );
            // Main Settings Page!
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-twitter-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-pinterest-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-youtube-feed-styles-submenu-page' === $_GET['page'] ) {
                add_action( 'admin_enqueue_scripts', array($this, 'feed_them_settings') );
            }
            // System Info Page!
            if ( isset( $_GET['page'] ) && 'fts-system-info-submenu-page' === $_GET['page'] ) {
                add_action( 'admin_enqueue_scripts', array($this, 'feed_them_system_info_css') );
            }
            // FTS License Page!
            if ( isset( $_GET['page'] ) && 'fts-license-page' === $_GET['page'] ) {
                add_action( 'admin_footer', array($this, 'fts_plugin_license') );
            }
        }

        // FTS Admin Bar!
        add_action( 'wp_before_admin_bar_render', array($this, 'fts_admin_bar_menu'), 999 );
        // Settings option. Add Custom CSS to the header of FTS pages only!
        $fts_include_custom_css_checked_css = get_option( 'fts-color-options-settings-custom-css' );
        if ( '1' === $fts_include_custom_css_checked_css ) {
            add_action( 'wp_enqueue_scripts', array($this, 'fts_color_options_head_css') );
        }
        // Facebook Settings option. Add Custom CSS to the header of FTS pages only!
        $fts_include_fb_custom_css_checked_css = '1';
        if ( '1' === $fts_include_fb_custom_css_checked_css ) {
            add_action( 'wp_enqueue_scripts', array($this, 'fts_fb_color_options_head_css') );
        }
        // Settings option. Custom Powered by Feed Them Social Option!
        $fts_powered_text_options_settings = get_option( 'fts-powered-text-options-settings' );
        if ( '1' !== $fts_powered_text_options_settings ) {
            add_action( 'wp_enqueue_scripts', array($this, 'fts_powered_by_js') );
        }

        if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
            add_filter( 'jetpack_photon_skip_image', array($this, 'fts_jetpack_photon_exception'), 10, 3 );
        }
    }

    /**
     * FTS Instagram Token Ajax
     *
     * This will save the returned token to the database.
     *
     * @since 2.3.3
     */
    public function fts_instagram_token_ajax ()
    {

        $fts_refresh_token_nonce = wp_create_nonce( 'fts_token_nonce' );
        $access_token = $_REQUEST['access_token'];
        if ( wp_verify_nonce( $fts_refresh_token_nonce, 'fts_token_nonce' ) ) {
            if ( isset( $access_token ) ) {
                update_option( 'fts_instagram_custom_api_token', sanitize_text_field( $access_token ) );
                $insta_id = substr( $access_token, 0, strpos( $access_token, "." ) );
                update_option( 'fts_instagram_custom_id', sanitize_text_field( $insta_id ) );
            }
        }
        die;
    }

    /**
     * Feed Them Instagram Save Token
     *
     * FTS Check and Save Instagram Token Validity.
     *
     * @return bool
     * @since 2.6.1
     */
    public function feed_them_instagram_save_token ()
    {

        $fts_refresh_token_nonce = wp_create_nonce( 'access_token' );

        if ( wp_verify_nonce( $fts_refresh_token_nonce, 'access_token' ) ) {

            $auth_obj = $_GET['access_token'];

            if ( isset( $auth_obj ) ) {
                ?>
                <script>
                    jQuery(document).ready(function () {
                        var access_token = '<?php echo sanitize_text_field( $auth_obj ) ?>';
                        jQuery.ajax({
                            data: {
                                action: 'fts_instagram_token_ajax',
                                access_token: access_token,
                            },
                            type: 'POST',
                            url: ftsAjax.ajaxurl,
                            success: function (response) {
                                <?php
                                $auth_obj = $_GET['access_token'];
                                $insta_url = esc_url( 'https://api.instagram.com/v1/users/self/?access_token=' . $auth_obj );
                                // Get Data for Instagram to check for errors
                                $response = wp_remote_fopen( $insta_url );
                                $test_app_token_response = json_decode( $response );

                                // if the combined streams plugin is active we won't allow the settings page link to open up the Instagram Feed, instead we'll remove the #feed_type=instagram and just let the user manually select the combined streams or single instagram feed.
                                if ( is_plugin_active( 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) ) {
                                    $custom_instagram_link_hash = '';
                                } else {
                                    $custom_instagram_link_hash = '#feed_type=instagram';
                                }
                                if ( !isset( $test_app_token_response->meta->error_message ) && !isset( $test_app_token_response->error_message ) || isset( $test_app_token_response->meta->error_message ) && 'This client has not been approved to access this resource.' === $test_app_token_response->meta->error_message ) {
                                $fts_instagram_message = sprintf(
                                    esc_html( '%1$sYour access token is working! Generate your shortcode on the %2$sSettings Page%3$s', 'feed-them-social' ),
                                    '<div class="fts-successful-api-token">',
                                    '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' . $custom_instagram_link_hash ) . '">',
                                    '</a></div>'
                                );
                                ?>
                                jQuery('.fts-failed-api-token').hide();
                                if (!jQuery('.fts-successful-api-token').length) {
                                    jQuery('.fts-instagram-last-row').append('<?php echo $fts_instagram_message; ?>');
                                }
                                jQuery('.fts-success').show();
                                <?php
                                } elseif ( isset( $test_app_token_response->meta->error_message ) || isset( $test_app_token_response->error_message ) ) {
                                $text = isset( $test_app_token_response->meta->error_message ) ? $test_app_token_response->meta->error_message : $test_app_token_response->error_message;
                                $fts_instagram_message = sprintf(
                                    esc_html( '%1$sOh No something\'s wrong. %2$s. Please try clicking the button again to get a new access token. If you need additional assistance please email us at support@slickremix.com %3$s.', 'feed-them-social' ),
                                    '<div class="fts-failed-api-token">',
                                    esc_html( $text ),
                                    '</div>'
                                );
                                ?>
                                if (jQuery('.fts-failed-api-token').length) {
                                    jQuery('.fts-failed-api-token').hide();
                                    jQuery('.fts-instagram-last-row').append('<?php echo $fts_instagram_message; ?>');
                                }
                                <?php
                                }
                                ?>
                            }
                        }); // end of ajax()
                        return false;
                    }); // end of document.ready
                </script>
                <?php
            }
        }
    }

    /**
     * FTS JetPack Photon Option Exception
     *
     * This function resolves issues with images and JetPack
     *
     * @param string $val value.
     * @param string $src source.
     * @param string $tag tag.
     * @return bool
     * @since @since 1.9.6
     */
    public function fts_jetpack_photon_exception ($val, $src, $tag)
    {
        if ( strpos( $src, 'fbcdn.net' ) ) {
            return true;
        }
        return $val;
    }

    /**
     * FTS Share Option
     *
     * @param string $fb_link link for social network.
     * @param string $description description field for some of the social networks.
     * @since
     */
    public function fts_share_option ($fb_link, $description)
    {

        $hide_share = get_option( 'fts_disable_share_button', true ) ? get_option( 'fts_disable_share_button', true ) : '';

        if ( isset( $hide_share ) && '1' !== $hide_share ) {
            // Social media sharing URLs
            $link = $fb_link;
            $description = wp_strip_all_tags( $description );
            $ft_gallery_share_linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link;
            $ft_gallery_share_email = 'mailto:?subject=Shared Link&body=' . $link . ' - ' . $description;
            $ft_gallery_share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $link;
            $ft_gallery_share_twitter = 'https://twitter.com/intent/tweet?text=' . $link . '+' . $description;
            $ft_gallery_share_google = 'https://plus.google.com/share?url=' . $link;

            // The share wrap and links
            $output = '<div class="fts-share-wrap">';
            $output .= '<a href="javascript:;" class="ft-gallery-link-popup">' . esc_html( '', 'feed-them-social' ) . '</a>';
            $output .= '<div class="ft-gallery-share-wrap">';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_facebook ) . '" target="_blank" class="ft-galleryfacebook-icon"><i class="fa fa-facebook-square"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_twitter ) . '" target="_blank" class="ft-gallerytwitter-icon"><i class="fa fa-twitter"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_google ) . '" target="_blank" class="ft-gallerygoogle-icon"><i class="fa fa-google-plus"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_linkedin ) . '" target="_blank" class="ft-gallerylinkedin-icon"><i class="fa fa-linkedin"></i></a>';
            $output .= '<a href="' . esc_attr( $ft_gallery_share_email ) . '" target="_blank" class="ft-galleryemail-icon"><i class="fa fa-envelope"></i></a>';
            $output .= '</div>';
            $output .= '</div>';
            return $output;
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
    public function fts_fb_page_token_func ()
    {
        $fts_fb_page_token_users_nonce = wp_create_nonce( 'fts-fb-page-token-users-nonce' );

        if ( wp_verify_nonce( $fts_fb_page_token_users_nonce, 'fts-fb-page-token-users-nonce' ) ) {

            // Make sure it's not ajaxing!
            if ( !isset( $_GET['load_more_ajaxing'] ) ) {
                $_REQUEST['fts_dynamic_name'] = sanitize_key( $this->feed_them_social_rand_string() );
            } //End make sure it's not ajaxing!

            ob_start();

            if ( !isset( $_GET['locations'] ) ) {
                $fb_token_response = isset( $_REQUEST['next_url'] ) ? wp_remote_fopen( esc_url_raw( $_REQUEST['next_url'] ) ) : wp_remote_fopen( 'https://graph.facebook.com/me/accounts?fields=locations{name,id,page_username,locations,store_number,store_location_descriptor,access_token},name,id,link,access_token&access_token=' . $_GET['access_token'] . '&limit=25' );
                $test_fb_app_token_response = json_decode( $fb_token_response );
                $_REQUEST['next_url'] = isset( $test_fb_app_token_response->paging->next ) ? esc_url_raw( $test_fb_app_token_response->paging->next ) : '';
            } else {
                $fb_token_response = isset( $_REQUEST['next_location_url'] ) ? wp_remote_fopen( esc_url_raw( $_REQUEST['next_location_url'] ) ) : '';
                $test_fb_app_token_response = json_decode( $fb_token_response );
            }

            // echo '<pre>';
            // echo _r($test_fb_app_token_response);
            // echo '</pre>';
            // Make sure it's not ajaxing!
            if ( !isset( $_GET['load_more_ajaxing'] ) ) {
                // ******************
                // Load More BUTTON Start
                // ******************
                ?>
                <div class="fts-clear"></div>
                <?php
            } //End make sure it's not ajaxing!

            $build_shortcode = 'fts_fb_page_token';

            // Make sure it's not ajaxing!
        if (!isset( $_GET['load_more_ajaxing'] )) {

            $reviews_token = isset( $_GET['reviews_token'] ) ? 'yes' : 'no';
            ?>
            <div id="fb-list-wrap">
                <div class="fts-pages-info"> <?php echo esc_html( 'Click on a page in the list below and it will add the Page ID and Access Token above, then click save.', 'feed-them-social' ); ?></div>
                <ul class="fb-page-list fb-page-master-list">
                    <?php
                    } //End make sure it's not ajaxing!

                    foreach ( $test_fb_app_token_response->data as $data ) {
                        ?>
                        <li class="fts-fb-main-page-li">
                            <div class="fb-click-wrapper">
                                <div class="fb-image">
                                    <img border="0" height="50" width="50"
                                         src="<?php echo esc_url( 'https://graph.facebook.com/' . $data->id . '/picture' ); ?>"/>
                                </div>
                                <div class="fb-name-wrap"><span class="fb-name">
							<?php
                            echo esc_html( $data->name );
                            if ( isset( $data->store_number, $data->store_location_descriptor ) ) {
                                print '(' . $data->store_location_descriptor . ')';
                            }
                            ?>
									</span></div>
                                <div class="fb-other-wrap">
                                    <small>
                                        <?php echo esc_html( 'ID: ', 'feed-them-social' ); ?>
                                        <span class="fts-api-facebook-id"><?php echo esc_html( $data->id ); ?></span>
                                        <?php echo isset( $data->store_number ) ? esc_html( '| Location: ' . $data->store_number, 'feed-them-social' ) : ''; ?>
                                    </small>
                                </div>
                                <div class="page-token"><?php echo esc_attr( $data->access_token ); ?></div>
                                <?php
                                $facebook_input_token = get_option( 'fts_facebook_custom_api_token' );
                                $facebook_access_token = $data->access_token;
                                if ( $facebook_input_token === $facebook_access_token ) {
                                    ?>
                                    <div class="feed-them-social-admin-submit-btn " style="display: block !important;">
                                        Active
                                    </div>
                                <?php } else { ?>
                                    <div class="feed-them-social-admin-submit-btn fts-token-save">Save</div>
                                <?php } ?>
                                <div class="fts-clear"></div>
                            </div>
                            <?php
                            $_REQUEST['next_location_url'] = isset( $data->locations->paging->next ) ? esc_url_raw( $data->locations->paging->next ) : '';
                            $remove_class_or_not = isset( $data->locations->paging->next ) ? 'fb-sublist-page-id-' . esc_attr( $data->id ) : '';
                            if ( isset( $data->locations->data ) ) {
                                $location_count = count( $data->locations->data );
                                $location_plus_sign = isset( $data->locations->paging->next ) ? '+' : '';
                                $location_text = 1 === $location_count ? esc_html( $location_count . ' ' . esc_html( 'Location for', 'feed-them-social' ) ) : esc_html( $location_count . $location_plus_sign . ' ' . esc_html( 'Locations for', 'feed-them-social' ) );
                                // if the locations equal 3 or less we will set the location container height to auto so the scroll loadmore does not fire.
                                $location_scroll_loadmore_needed_check = $location_count <= 3 ? 'height:auto !important' : 'height: 200px !important;';
                            }

                            if ( !isset( $_GET['locations'] ) && isset( $data->locations->data ) ) {
                                ?>
                                <div class="fts-fb-location-text-wrap"><?php echo esc_html( $location_text . ' ' . $data->name ); ?></div>
                                <ul class="fb-page-list fb-sublist <?php echo esc_attr( $remove_class_or_not ); ?>"
                                    style="<?php echo esc_attr( $location_scroll_loadmore_needed_check ); ?>">
                                    <?php foreach ( $data->locations->data as $location ) { ?>
                                        <li>
                                            <div class="fb-click-wrapper">
                                                <div class="fb-image">
                                                    <img border="0" height="50" width="50"
                                                         src="<?php echo esc_url( 'https://graph.facebook.com/' . $location->id . '/picture' ); ?>"/>
                                                </div>
                                                <div class="fb-name-wrap"><span
                                                            class="fb-name"><?php echo esc_html( $location->name ); ?>
                                                        <?php
                                                        if ( isset( $location->store_location_descriptor ) ) {
                                                            echo '(' . esc_html( $location->store_location_descriptor ) . ')';
                                                        }
                                                        ?>
											</span></div>
                                                <div class="fb-other-wrap">
                                                    <small>
                                                        <?php echo esc_html( 'ID: ', 'feed-them-social' ); ?>
                                                        <span class="fts-api-facebook-id"><?php echo esc_html( $location->id ); ?></span>
                                                        <?php
                                                        if ( isset( $location->store_number ) ) {
                                                            print '| ';
                                                            esc_html( 'Location:', 'feed-them-social' );
                                                            print ' ' . esc_html( $location->store_number );
                                                        }
                                                        ?>
                                                    </small>
                                                </div>

                                                <div class="page-token"><?php echo esc_html( $location->access_token ); ?></div>
                                                <?php
                                                $facebook_input_token = get_option( 'fts_facebook_custom_api_token' );
                                                $facebook_access_token = $location->access_token;
                                                if ( $facebook_input_token === $facebook_access_token ) {
                                                    ?>
                                                    <div class="feed-them-social-admin-submit-btn "
                                                         style="display: block !important;">Active
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="feed-them-social-admin-submit-btn fts-token-save">Save
                                                    </div>
                                                <?php } ?>
                                                <div class="fts-clear"></div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>

                            <?php
                            // Make sure it's not ajaxing locations!
                            if ( !isset( $_GET['locations'] ) && isset( $data->locations->paging->next ) ) {
                                echo '<div id="loadMore_' . esc_attr( $data->id ) . '_location" class="fts-fb-load-more" style="background:none !Important;">' . esc_html( 'Scroll to view more Locations', 'feed-them-instagram' ) . '</div>';
                            }//End Check

                            // Make sure it's not ajaxing locations!
                            if (!isset( $_GET['locations'] )) {
                            $time = time();
                            $nonce = wp_create_nonce( $time . 'load-more-nonce' );
                            $fb_page_id = $data->id;
                            ?>
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery(".fb-sublist-page-id-<?php echo esc_js( $fb_page_id ); ?>").bind("scroll", function () {
                                            if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
                                                if (!jQuery('.fts-no-more-locations-<?php echo esc_js( $fb_page_id ); ?>').length) {
                                                    jQuery("#loadMore_<?php echo esc_js( $fb_page_id ); ?>_location").addClass('fts-fb-spinner');
                                                    var button = jQuery('#loadMore_<?php echo esc_js( $fb_page_id ); ?>_location').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                                                    console.log(button);
                                                    var build_shortcode = "<?php echo esc_js( $build_shortcode ); ?>";
                                                    var yes_ajax = "yes";
                                                    var fts_d_name = "<?php echo esc_js( $fb_page_id ); ?>";
                                                    var fts_security = "<?php echo esc_js( $nonce ); ?>";
                                                    var fts_time = "<?php echo esc_js( $time ); ?>";
                                                    var fts_reviews_feed = "<?php echo esc_js( $reviews_token ); ?>";
                                                    jQuery.ajax({
                                                        data: {
                                                            action: "my_fts_fb_load_more",
                                                            next_location_url: nextURL_location_<?php echo esc_js( $fb_page_id ); ?>,
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
                                                            jQuery('.fb-sublist-page-id-<?php echo esc_js( $fb_page_id ); ?>').append(data).filter('.fb-sublist-page-id-<?php echo esc_js( $fb_page_id ); ?>').html();
                                                            jQuery('.fb-sublist-page-id-<?php echo esc_js( $fb_page_id ); ?>').animate({scrollTop: '+=100px'}, 800); // scroll down a 100px after new items are added



                                                            <?php if ( isset( $data->locations->paging->next ) && $data->locations->paging->next === $_REQUEST['next_location_url'] ) { ?>
                                                            jQuery('#loadMore_<?php echo esc_js( $fb_page_id ); ?>_location').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb fts-no-more-locations-<?php echo esc_js( $fb_page_id ); ?>" style="background:none !important"><?php echo esc_html( 'All Locations loaded', 'feed-them-social' ); ?></div>');
                                                            jQuery('#loadMore_<?php echo esc_js( $fb_page_id ); ?>_location').removeAttr('id');
                                                            <?php } ?>
                                                            jQuery("#loadMore_<?php echo esc_js( $fb_page_id ); ?>_location").removeClass('fts-fb-spinner');
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
                                <script>var nextURL_location_<?php echo esc_js( $fb_page_id ); ?>= "<?php echo isset( $data->locations->paging->next ) ? esc_url_raw( $data->locations->paging->next ) : ''; ?>";</script>
                            <?php } ?>
                        </li>

                        <?php
                    }  // foreach loop of locations

                    // Make sure it's not ajaxing!
                    if (!isset( $_GET['load_more_ajaxing'] )) {
                    ?>
                </ul>
                <div class="fts-clear"></div>
            </div>
            <?php
        } //End make sure it's not ajaxing

            // Make sure it's not ajaxing!
            if ( !isset( $_GET['load_more_ajaxing'] ) && !isset( $_GET['locations'] ) ) {
                $fts_dynamic_name = isset( $_REQUEST['fts_dynamic_name'] ) ? sanitize_key( $_REQUEST['fts_dynamic_name'] ) : '';
                $time = time();
                $nonce = wp_create_nonce( $time . 'load-more-nonce' );
                ?>
                <script>
                    jQuery(document).ready(function () {

                        jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").click(function () {

                            jQuery("#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>").addClass('fts-fb-spinner');
                            var button = jQuery('#loadMore_<?php echo esc_js( $fts_dynamic_name ); ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                            console.log(button);
                            var build_shortcode = "<?php echo esc_js( $build_shortcode ); ?>";
                            var yes_ajax = "yes";
                            var fts_d_name = "<?php echo esc_js( $fts_dynamic_name ); ?>";
                            var fts_security = "<?php echo esc_js( $nonce ); ?>";
                            var fts_time = "<?php echo esc_js( $time ); ?>";
                            var fts_reviews_feed = "<?php echo esc_js( $reviews_token ); ?>";
                            jQuery.ajax({
                                data: {
                                    action: "my_fts_fb_load_more",
                                    next_url: nextURL_<?php echo esc_js( $fts_dynamic_name ); ?>,
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

                                    if (!nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?> || 'no more' === nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>) {
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
                <?php if ( !isset( $_GET['locations'] ) ) { ?>
                var nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>= "<?php echo esc_url_raw( $_REQUEST['next_url'] ); ?>";
                // alert('nextURL_<?php echo esc_js( sanitize_text_field( wp_unslash( $_REQUEST['fts_dynamic_name'] ) ) ); ?>');
                <?php } ?>


                if (document.querySelector('#fts-fb-token-wrap .fts-pages-info') !== null) {
                    jQuery(".fts-successful-api-token.default-token").hide();
                }
                <?php if ( 'yes' === $reviews_token || isset( $_GET['fts_reviews_feed'] ) && 'yes' === $_GET['fts_reviews_feed'] ) { ?>
                if (document.querySelector('.default-token') !== null) {
                    jQuery(".default-token").show();
                }

                <?php } ?>

                jQuery(document).ready(function ($) {
                    $(".feed-them-social-admin-submit-btn").click(function () {
                        // alert('test');
                        var newUrl = "<?php echo esc_url( admin_url( 'admin.php?page=fts-facebook-feed-styles-submenu-page/' ) ); ?>";
                        history.replaceState({}, null, newUrl);
                        $("#fts-facebook-feed-options-form").submit();
                    });

                    var fb = ".fb-page-list .fb-click-wrapper";
                    $('#fb-list-wrap').show();
                    //alert("reviews_token");

                    $(fb).click(function () {
                        var fb_page_id = $(this).find('.fts-api-facebook-id').html();
                        var token = $(this).find('.page-token').html();
                        // alert(token);
                        var name = $(this).find('.fb-name').html();
                        <?php if ( 'no' === $reviews_token || isset( $_GET['fts_reviews_feed'] ) && 'no' === $_GET['fts_reviews_feed'] ) { ?>
                        $("#fts_facebook_custom_api_token").val(token);
                        $("#fts_facebook_custom_api_token_user_id").val(fb_page_id);
                        $("#fts_facebook_custom_api_token_user_name").val(name);
                        <?php } else { ?>
                        $("#fts_facebook_custom_api_token_biz").val(token);
                        $("#fts_facebook_custom_api_token_user_id_biz").val(fb_page_id);
                        $("#fts_facebook_custom_api_token_user_name_biz").val(name);
                        <?php } ?>
                        $('.fb-page-list .feed-them-social-admin-submit-btn').hide();
                        $(this).find('.feed-them-social-admin-submit-btn').toggle();
                        //   alert(name + token)
                    })
                });
            </script>
            <?php
            // Make sure it's not ajaxing!
            if ( !isset( $_GET['load_more_ajaxing'] ) && isset( $test_fb_app_token_response->paging->next ) && !isset( $_GET['locations'] ) ) {
                $fts_dynamic_name = sanitize_key( $_REQUEST['fts_dynamic_name'] );
                echo '<div class="fts-clear"></div>';

                echo '<div id="loadMore_' . esc_attr( $fts_dynamic_name ) . '" class="fts-fb-load-more">' . esc_html( 'Load More', 'feed-them-social' ) . '</div>';
            }//End make sure it's not ajaxing

            // Lastly if we can't find a next url we unset the next url from the page to not let the loadmore button be active.
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
     * My FTS Plugin License
     *
     * Put in place to only show the Activate Plugin license if the input has a value
     *
     * @since 2.1.4
     */
    public function fts_plugin_license ()
    {
        wp_enqueue_script( 'jquery' );
        ?>
        <style>.fts-license-master-form th {
                background: #f9f9f9;
                padding: 14px;
                border-bottom: 1px solid #ccc;
                margin: -14px -14px 20px;
                width: 100%;
                display: block
            }

            .fts-license-master-form .form-table tr {
                float: left;
                margin: 0 15px 15px 0;
                background: #fff;
                border: 1px solid #ccc;
                width: 30.5%;
                max-width: 350px;
                padding: 14px;
                min-height: 220px;
                position: relative;
                box-sizing: border-box
            }

            .fts-license-master-form .form-table td {
                padding: 0;
                display: block
            }

            .fts-license-master-form td input.regular-text {
                margin: 0 0 8px;
                width: 100%
            }

            .fts-license-master-form .edd-license-data[class*=edd-license-] {
                position: absolute;
                background: #fafafa;
                padding: 14px;
                border-top: 1px solid #eee;
                margin: 20px -14px -14px;
                min-height: 67px;
                width: 100%;
                bottom: 14px;
                box-sizing: border-box
            }

            .fts-license-master-form .edd-license-data p {
                font-size: 13px;
                margin-top: 0
            }

            .fts-license-master-form tr {
                display: none
            }

            .fts-license-master-form tr.fts-license-wrap {
                display: block
            }

            .fts-license-master-form .edd-license-msg-error {
                background: rgba(255, 0, 0, 0.49)
            }

            .fts-license-master-form tr.fts-license-wrap {
                display: block
            }

            .fts-license-master-form .edd-license-msg-error {
                background: #e24e4e !important;
                color: #FFF
            }

            .fts-license-wrap .edd-license-data p {
                color: #1e981e
            }

            .edd-license-msg-error p {
                color: #FFF !important
            }

            .feed-them_page_fts-license-page .button-secondary {
                display: none;
            }</style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if (jQuery('#feed_them_social_premium_license_key').val() !== '') {
                    jQuery('#feed_them_social_premium_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed_them_social_combined_streams_license_key').val() !== '') {
                    jQuery('#feed_them_social_combined_streams_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed-them-social-facebook-reviews_license_key').val() !== '') {
                    jQuery('#feed-them-social-facebook-reviews_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#fts_bar_license_key').val() !== '') {
                    jQuery('#fts_bar_license_key').next('label').find('.button-secondary').show()
                }
                if (jQuery('#feed_them_carousel_premium_license_key').val() !== '') {
                    jQuery('#feed_them_carousel_premium_license_key').next('label').find('.button-secondary').show()
                }
            });
        </script>
        <?php
    }

    /**
     * My FTS Ajaxurl
     *
     * Ajax var on front end for twitter videos and loadmore button (if premium active.
     *
     * @since 1.9.6
     */
    public function my_fts_ajaxurl ()
    {
        wp_enqueue_script( 'jquery' );
    }

    /**
     * My FTS FB Load More
     *
     * This function is being called from the fb feed... it calls the ajax in this case.
     *
     * @since 1.9.6
     * @updated 2.1.4 (fts_fb_page_token)
     */
    public function my_fts_fb_load_more ()
    {
        if ( isset( $_REQUEST['fts_security'], $_REQUEST['fts_time'] ) && !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['fts_security'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['fts_time'] ) ) . 'load-more-nonce' ) ) {
            exit( 'Sorry, You can\'t do that!' );
        } else {

            if ( isset( $_REQUEST['feed_name'] ) && 'fts_fb_page_token' === $_REQUEST['feed_name'] ) {
                if ( isset( $_REQUEST['next_url'] ) && false === strpos( sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ), 'https://graph.facebook.com/v3.1' ) ||
                    isset( $_REQUEST['next_location_url'] ) && false === strpos( sanitize_text_field( wp_unslash( $_REQUEST['next_location_url'] ) ), 'https://graph.facebook.com/v3.1' ) ||
                    isset( $_REQUEST['next_url'] ) && sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) !== sanitize_text_field( wp_unslash( $_REQUEST['next_url'] ) ) ||
                    isset( $_REQUEST['next_location_url'] ) && sanitize_text_field( wp_unslash( $_REQUEST['next_location_url'] ) ) !== sanitize_text_field( wp_unslash( $_REQUEST['next_location_url'] ) ) ) {

                    exit( 'That is not an FTS shortcode!' );
                }
            }

            if ( isset( $_REQUEST['feed_name'] ) && 'fts_fb_page_token' === $_REQUEST['feed_name'] ||
                isset( $_REQUEST['feed_name'] ) && 'fts_twitter' === $_REQUEST['feed_name'] ||
                isset( $_REQUEST['feed_name'] ) && 'fts_youtube' === $_REQUEST['feed_name'] ||
                isset( $_REQUEST['feed_name'] ) && 'fts_facebook' === $_REQUEST['feed_name'] ||
                isset( $_REQUEST['feed_name'] ) && 'fts_facebookbiz' === $_REQUEST['feed_name'] ||
                isset( $_REQUEST['feed_name'] ) && 'fts_instagram' === $_REQUEST['feed_name'] ) {

                $feed_atts = isset( $_REQUEST['feed_attributes'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['feed_attributes'] ) ) : '';

                $build_shortcode = '[' . sanitize_text_field( wp_unslash( $_REQUEST['feed_name'] ) ) . '';
                foreach ( $feed_atts as $attribute => $value ) {
                    $build_shortcode .= ' ' . $attribute . '=' . $value;
                }

                if ( 'fts_twitter' === $_REQUEST['feed_name'] ) {
                    $loadmore_count = isset( $_REQUEST['loadmore_count'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['loadmore_count'] ) ) : '';
                    $build_shortcode .= ' ' . $loadmore_count . ']';
                } elseif ( 'fts_youtube' === $_REQUEST['feed_name'] ) {
                    $loadmore_count = isset( $_REQUEST['loadmore_count'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['loadmore_count'] ) ) : '';
                    $build_shortcode .= ' ' . $loadmore_count . ']';
                } else {
                    $build_shortcode .= ' ]';
                }

                echo do_shortcode( $build_shortcode );

            } else {
                exit( esc_html( 'That is not an FTS shortcode!' ) );
            }
        }
        die();
    }

    /**
     * FTS Clear Cache Script
     *
     * This is for the fts_clear_cache_ajax submission.
     *
     * @since 1.9.6
     */
    public function fts_clear_cache_script ()
    {

        $fts_admin_activation_clear_cache = get_option( 'Feed_Them_Social_Activated_Plugin' );
        $fts_dev_mode_cache = get_option( 'fts_clear_cache_developer_mode' );
        if ( '1' === $fts_dev_mode_cache || 'feed-them-social' === $fts_admin_activation_clear_cache ) {
            wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/developer-admin.js' ), array('jquery'), FTS_CURRENT_VERSION, false );
            wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array('ajaxurl' => admin_url( 'admin-ajax.php' )) );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'fts_clear_cache_script' );
        }
        if ( 'hide-admin-bar-menu' !== $fts_dev_mode_cache && '1' !== $fts_dev_mode_cache ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/admin.js' ), array(), FTS_CURRENT_VERSION, false );
            wp_enqueue_script( 'fts_clear_cache_script', plugins_url( 'feed-them-social/admin/js/developer-admin.js' ), array('jquery'), FTS_CURRENT_VERSION, false );
            wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array('ajaxurl' => admin_url( 'admin-ajax.php' )) );
            wp_enqueue_script( 'fts_clear_cache_script' );
        }

        // we delete this option if found so we only empty the cache once when the plugin is ever activated or updated!
        delete_option( 'Feed_Them_Social_Activated_Plugin' );
    }

    /**
     * Feed Them Main Menu
     *
     * Admin Submenu buttons // Add the word Settings in place of the default menu page name 'Feed Them'.
     *
     * @since 1.9.6
     */
    public function feed_them_main_menu ()
    {
        // Main Settings Page!
        $main_settings_page = new FTS_Settings_Page();
        add_menu_page( 'Feed Them Social', 'Feed Them', 'manage_options', 'feed-them-settings-page', array($main_settings_page, 'feed_them_settings_page'), '' );
        add_submenu_page( 'feed-them-settings-page', esc_html( 'Settings', 'feed-them-social' ), esc_html( 'Settings', 'feed-them-social' ), 'manage_options', 'feed-them-settings-page' );
    }

    /**
     * Feed Them Submenu Pages
     *
     * @since 1.9.6
     */
    public function feed_them_submenu_pages ()
    {

        // Facebook Options Page!
        $facebook_options_page = new FTS_Facebook_Options_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'Facebook Options', 'feed-them-social' ),
            esc_html( 'Facebook Options', 'feed-them-social' ),
            'manage_options',
            'fts-facebook-feed-styles-submenu-page',
            array($facebook_options_page, 'feed_them_facebook_options_page')
        );
        // Instagram Options Page!
        $instagram_options_page = new FTS_Instagram_Options_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'Instagram Options', 'feed-them-social' ),
            esc_html( 'Instagram Options', 'feed-them-social' ),
            'manage_options',
            'fts-instagram-feed-styles-submenu-page',
            array($instagram_options_page, 'feed_them_instagram_options_page')
        );
        // Twitter Options Page!
        $twitter_options_page = new FTS_Twitter_Options_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'Twitter Options', 'feed-them-social' ),
            esc_html( 'Twitter Options', 'feed-them-social' ),
            'manage_options',
            'fts-twitter-feed-styles-submenu-page',
            array($twitter_options_page, 'feed_them_twitter_options_page')
        );
        // Pinterest Options Page!
        $pinterest_options_page = new FTS_Pinterest_Options_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'Pinterest Options', 'feed-them-social' ),
            esc_html( 'Pinterest Options', 'feed-them-social' ),
            'manage_options',
            'fts-pinterest-feed-styles-submenu-page',
            array($pinterest_options_page, 'feed_them_pinterest_options_page')
        );
        // Youtube Options Page!
        $youtube_options_page = new FTS_Youtube_Options_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'YouTube Options', 'feed-them-social' ),
            esc_html( 'YouTube Options', 'feed-them-social' ),
            'manage_options',
            'fts-youtube-feed-styles-submenu-page',
            array($youtube_options_page, 'feed_them_youtube_options_page')
        );
        // System Info!
        $system_info_page = new FTS_System_Info_Page();
        add_submenu_page(
            'feed-them-settings-page',
            esc_html( 'System Info', 'feed-them-social' ),
            esc_html( 'System Info', 'feed-them-social' ),
            'manage_options',
            'fts-system-info-submenu-page',
            array($system_info_page, 'feed_them_system_info_page')
        );
    }

    /**
     * Feed Them Admin CSS
     *
     * Admin CSS.
     *
     * @since 1.9.6
     */
    public function feed_them_admin_css ()
    {
        wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
        wp_enqueue_style( 'feed_them_admin' );
    }

    /**
     * Feed Them System Info CSS
     *
     * Admin System Info CSS.
     *
     * @since 1.9.6
     */
    public function feed_them_system_info_css ()
    {
        wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION );
        wp_enqueue_style( 'fts-settings-admin-css' );
    }

    /**
     * Feed Them Settings
     *
     * Admin Settings Scripts and CSS.
     *
     * @since 1.9.6
     */
    public function feed_them_settings ()
    {
        $fts_functions_load_settings_nonce = wp_create_nonce( 'fts-functions-load-settings-nonce' );

        if ( wp_verify_nonce( $fts_functions_load_settings_nonce, 'fts-functions-load-settings-nonce' ) ) {
            wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.css', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION, false );
            wp_enqueue_style( 'feed_them_settings_css' );
            if ( isset( $_GET['page'] ) && 'fts-youtube-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-instagram-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-facebook-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-twitter-feed-styles-submenu-page' === $_GET['page'] || isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] || isset( $_GET['page'] ) && 'fts-pinterest-feed-styles-submenu-page' === $_GET['page'] ) {
                wp_enqueue_script( 'feed_them_style_options_color_js', plugins_url( 'admin/js/jscolor/jscolor.js', dirname( __FILE__ ) ), array(), FTS_CURRENT_VERSION, false );
            }
        }
    }

    /**
     * Need FTS Premium Fields
     *
     * Admin Premium Settings Fields.
     *
     * @param array $fields settings fields to display premium notice for.
     * @since 1.9.6
     */
    public function need_fts_premium_fields ($fields)
    {
        foreach ( $fields as $key => $label ) {
            $output = '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( $label ) . '</div>';
            $output .= '<div class="feed-them-social-admin-input-default">';
            $output .= sprintf(
                esc_html( 'Must have %1$sPremium Extension%3$s to edit.', 'feed-them-social' ),
                '<a href="' . esc_url( 'https://www.slickremix.com/downloads/feed-them-social-premium-extension/' ) . '" target="_blank">',
                '</a>'
            );
            $output .= '</div>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        }//END Foreach

        return $output;
    }


    /**
     * Register Settings
     *
     * Generic Register Settings function.
     *
     * @param string $settings_name settings name.
     * @param array $settings settings parameters.
     * @since
     */
    public function register_settings ($settings_name, $settings)
    {
        foreach ( $settings as $key => $setting ) {
            register_setting( $settings_name, $setting );
        }
    }

    /**
     * FTS Facebook Style Options Page
     *
     * Register Facebook Style Options.
     *
     * @since 1.9.6
     */
    public function fts_facebook_style_options_page ()
    {
        $fb_style_options = array(
            'fb_like_btn_color',
            'fb_language',
            'fb_show_follow_btn',
            'fb_show_follow_like_box_cover',
            'fb_show_follow_btn_where',
            'fb_header_extra_text_color',
            'fb_text_color',
            'fb_link_color',
            'fb_link_color_hover',
            'fb_feed_width',
            'fb_feed_margin',
            'fb_feed_padding',
            'fb_feed_background_color',
            'fb_post_background_color',
            'fb_grid_border_bottom_color',
            'fb_grid_posts_background_color',
            'fb_border_bottom_color',
            'fts_facebook_custom_api_token',
            'fb_event_title_color',
            'fb_event_title_size',
            'fb_event_maplink_color',
            'fb_events_title_color',
            'fb_events_title_size',
            'fb_events_map_link_color',
            'fb_hide_shared_by_etc_text',
            'fts_facebook_custom_api_token_biz',
            'fb_reviews_text_color',
            'fb_reviews_backg_color',
            'fb_reviews_star_language',
            'fb_reviews_recommended_language',
            'fb_reviews_see_more_reviews_language',
            'fb_reviews_see_more_reviews_language',
            'fb_reviews_overall_rating_background_border_hide',
            'fb_reviews_overall_rating_background_color',
            'fb_reviews_overall_rating_border_color',
            'fb_reviews_overall_rating_text_color',
            'fb_reviews_overall_rating_background_padding',
            'fb_reviews_remove_see_reviews_link',
            'fb_reviews_overall_rating_of_5_stars_text',
            'fb_reviews_overall_rating_reviews_text',
            'fb_max_image_width',
            'fb_hide_images_in_posts',
            'fb_hide_error_handler_message',
            'fb_count_offset',
            'fb_hide_no_posts_message',
            'fts_facebook_custom_api_token_user_id',
            'fts_facebook_custom_api_token_user_name',
            'fts_facebook_custom_api_token_user_id_biz',
            'fts_facebook_custom_api_token_user_name_biz',
            'fb_loadmore_background_color',
            'fb_loadmore_text_color',
            'fb_load_more_text',
            'fb_no_more_posts_text',
            'fb_no_more_photos_text',
            'fb_no_more_videos_text',
            'fb_no_more_reviews_text',
            'fb_text_size',
            'fb_view_on_fb_fts',
        );
        $this->register_settings( 'fts-facebook-feed-style-options', $fb_style_options );
    }

    /**
     * FTS Twitter Style Options Page
     *
     * Register Twitter Style Options.
     *
     * @since 1.9.6
     */
    public function fts_twitter_style_options_page ()
    {
        $twitter_style_options = array(
            'twitter_show_follow_btn',
            'twitter_show_follow_count',
            'twitter_show_follow_btn_where',
            'twitter_allow_videos',
            'twitter_allow_shortlink_conversion',
            'twitter_full_width',
            'twitter_text_color',
            'twitter_link_color',
            'twitter_link_color_hover',
            'twitter_feed_width',
            'twitter_feed_margin',
            'twitter_feed_padding',
            'twitter_feed_background_color',
            'twitter_border_bottom_color',
            'twitter_grid_posts_background_color',
            'twitter_grid_border_bottom_color',
            'fts_twitter_custom_consumer_key',
            'fts_twitter_custom_consumer_secret',
            'fts_twitter_custom_access_token',
            'fts_twitter_custom_access_token_secret',
            'fts_twitter_hide_images_in_posts',
            'twitter_max_image_width',
            'twitter_loadmore_background_color',
            'twitter_loadmore_text_color',
            'twitter_load_more_text',
            'twitter_no_more_tweets_text',
            'twitter_text_size',
            'twitter_load_more_text',
            'fts_twitter_custom_tokens',
        );
        $this->register_settings( 'fts-twitter-feed-style-options', $twitter_style_options );
    }

    /**
     * FTS Instagram Style Options Page
     *
     * Register Instagram Options.
     *
     * @since 1.9.6
     */
    public function fts_instagram_style_options_page ()
    {
        $instagram_style_options = array(
            'fts_instagram_custom_api_token',
            'fts_instagram_custom_id',
            'instagram_show_follow_btn',
            'instagram_show_follow_btn_where',
            'instagram_loadmore_background_color',
            'instagram_loadmore_text_color',
            'instagram_load_more_text',
            'instagram_no_more_photos_text',
        );
        $this->register_settings( 'fts-instagram-feed-style-options', $instagram_style_options );
    }

    /**
     * FTS Pinterest Style Options Page
     *
     * Register Pinterest Options.
     *
     * @since 1.9.6
     */
    public function fts_pinterest_style_options_page ()
    {
        $pinterest_style_options = array(
            'fts_pinterest_custom_api_token',
            'pinterest_show_follow_btn',
            'pinterest_show_follow_btn_where',
            'pinterest_board_title_color',
            'pinterest_board_title_size',
            'pinterest_board_backg_hover_color',
        );
        $this->register_settings( 'fts-pinterest-feed-style-options', $pinterest_style_options );
    }

    /**
     * FTS Youtube Style Options Page
     *
     * Register YouTube Options.
     *
     * @since 1.9.6
     */
    public function fts_youtube_style_options_page ()
    {
        $youtube_style_options = array(
            'youtube_show_follow_btn',
            'youtube_show_follow_btn_where',
            'youtube_custom_api_token',
            'youtube_loadmore_background_color',
            'youtube_loadmore_text_color',
            'youtube_load_more_text',
            'youtube_no_more_videos_text',
            'youtube_custom_refresh_token',
            'youtube_custom_access_token',
            'youtube_custom_token_exp_time',
        );
        $this->register_settings( 'fts-youtube-feed-style-options', $youtube_style_options );
    }

    /**
     * FTS Settings Page Register Settings
     *
     * Register Free Version Settings.
     *
     * @since 1.9.6
     */
    public function fts_settings_page_register_settings ()
    {
        $settings = array(
            'fts_admin_bar_menu',
            'fts_clear_cache_developer_mode',
            'fts-date-and-time-format',
            'fts-timezone',
            'fts_fix_magnific',
            'fts-color-options-settings-custom-css',
            'fts-color-options-main-wrapper-css-input',
            'fts-powered-text-options-settings',
            'fts-slicker-instagram-icon-center',
            'fts-slicker-instagram-container-image-size',
            'fts-slicker-instagram-container-hide-date-likes-comments',
            'fts-slicker-instagram-container-position',
            'fts-slicker-instagram-container-animation',
            'fts-slicker-instagram-container-margin',
            'fts_fix_loadmore',
            'fts_curl_option',
            'fts-custom-date',
            'fts-custom-time',
            'fts_twitter_time_offset',
            'fts_language_second',
            'fts_language_seconds',
            'fts_language_minute',
            'fts_language_minutes',
            'fts_language_hour',
            'fts_language_hours',
            'fts_language_day',
            'fts_language_days',
            'fts_language_week',
            'fts_language_weeks',
            'fts_language_month',
            'fts_language_months',
            'fts_language_year',
            'fts_language_years',
            'fts_language_ago',
            'fts_disable_share_button',
            'fts_social_icons_color',
            'fts_social_icons_hover_color',
            'fts_social_icons_back_color',
            'fts_slick_rating_notice_waiting',
            'fts_slick_rating_notice',
            'fts_slick_ignore_rating_notice_nag',
        );
        $this->register_settings( 'feed-them-social-settings', $settings );
    }

    /**
     * Social Follow Buttons
     *
     * @param string $feed feed type.
     * @param string $user_id user id.
     * @param null $access_token access token.
     * @param null $fb_shortcode shortcode attribute.
     * @since 1.9.6
     */
    public function social_follow_button ($feed, $user_id, $access_token = null, $fb_shortcode = null)
    {
        $fts_social_follow_nonce = wp_create_nonce( 'fts-social-follow-nonce' );

        if ( wp_verify_nonce( $fts_social_follow_nonce, 'fts-social-follow-nonce' ) ) {

            global $channel_id, $playlist_id, $username_subscribe_btn, $username;
            switch ($feed) {
                case 'facebook':
                    // Facebook settings options for follow button!
                    $fb_show_follow_btn = get_option( 'fb_show_follow_btn' );
                    $fb_show_follow_like_box_cover = get_option( 'fb_show_follow_like_box_cover' );
                    $language_option_check = get_option( 'fb_language' );

                    if ( isset( $language_option_check ) && 'Please Select Option' !== $language_option_check ) {
                        $language_option = get_option( 'fb_language', 'en_US' );
                    } else {
                        $language_option = 'en_US';
                    }
                    $fb_like_btn_color = get_option( 'fb_like_btn_color', 'light' );
                    $show_faces = 'like-button-share-faces' === $fb_show_follow_btn || 'like-button-faces' === $fb_show_follow_btn || 'like-box-faces' === $fb_show_follow_btn ? 'true' : 'false';
                    $share_button = 'like-button-share-faces' === $fb_show_follow_btn || 'like-button-share' === $fb_show_follow_btn ? 'true' : 'false';
                    $page_cover = 'fb_like_box_cover-yes' === $fb_show_follow_like_box_cover ? 'true' : 'false';
                    if ( !isset( $_POST['fts_facebook_script_loaded'] ) ) {
                        echo '<div id="fb-root"></div>
							<script>jQuery(".fb-page").hide(); (function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/' . esc_html( $language_option ) . '/sdk.js#xfbml=1&appId=&version=v3.1";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, "script", "facebook-jssd"));</script>';
                        $_POST['fts_facebook_script_loaded'] = 'yes';
                    }

                    // Page Box!
                    if ( 'like-box' === $fb_show_follow_btn || 'like-box-faces' === $fb_show_follow_btn ) {

                        $like_box_width = isset( $fb_shortcode['like_box_width'] ) && '' !== $fb_shortcode['like_box_width'] ? $fb_shortcode['like_box_width'] : '500px';

                        echo '<div class="fb-page" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-hide-cover="' . esc_html( $page_cover ) . '" data-width="' . esc_html( $like_box_width ) . '"  data-show-facepile="' . esc_html( $show_faces ) . '" data-show-posts="false"></div>';
                    } else {
                        echo '<div class="fb-like" data-href="' . esc_url( 'https://www.facebook.com/' . $user_id ) . '" data-layout="standard" data-action="like" data-colorscheme="' . esc_html( $fb_like_btn_color ) . '" data-show-faces="' . esc_html( $show_faces ) . '" data-share="' . esc_html( $share_button ) . '" data-width:"100%"></div>';
                    }
                    break;
                case 'instagram':
                    echo '<a href="' . esc_url( 'https://instagram.com/' . $user_id . '/' ) . '" target="_blank">' . esc_html( 'Follow on Instagram', 'feed-them-social' ) . '</a>';
                    break;
                case 'twitter':
                    if ( !isset( $_POST['fts_twitter_script_loaded'] ) ) {
                        echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
                        $_POST['fts_twitter_script_loaded'] = 'yes';
                    }
                    // CAN't ESCAPE Twitter link because then JS doesn't work!
                    echo '<a class="twitter-follow-button" href="' . ' https://twitter.com/' . $user_id . ' " data-show-count="false" data-lang="en"> Follow @' . esc_html( $user_id ) . '</a>';
                    break;
                case 'pinterest':
                    if ( !isset( $_POST['fts_pinterest_script_loaded'] ) ) {
                        echo '<script>jQuery(function () {jQuery.getScript("//assets.pinterest.com/js/pinit.js");});</script>';
                        $_POST['fts_pinterest_script_loaded'] = 'yes';
                    }
                    // we return this one until we echo out the pinterest feed instead of $output.=.
                    return '<a data-pin-do="buttonFollow" href="https://www.pinterest.com/' . esc_html( $user_id ) . '/">' . esc_html( $user_id ) . '</a>';
                    break;
                case 'youtube':
                    if ( !isset( $_POST['fts_youtube_script_loaded'] ) ) {
                        echo '<script src="' . esc_url( 'https://apis.google.com/js/platform.js' ) . '"></script>';
                        $_POST['fts_youtube_script_loaded'] = 'yes';
                    }
                    if ( '' === $channel_id && '' === $playlist_id && '' !== $username || '' !== $playlist_id && '' !== $username_subscribe_btn ) {

                        if ( '' !== $username_subscribe_btn ) {
                            echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $username_subscribe_btn ) . '" data-layout="full" data-count="default"></div>';
                        } else {
                            echo '<div class="g-ytsubscribe" data-channel="' . esc_html( $user_id ) . '" data-layout="full" data-count="default"></div>';
                        }
                    } elseif ( '' !== $channel_id && '' !== $playlist_id || '' !== $channel_id ) {
                        echo '<div class="g-ytsubscribe" data-channelid="' . esc_html( $channel_id ) . '" data-layout="full" data-count="default"></div>';
                    }
                    break;
            }
        }
    }

    /**
     * FTS Color Options Head CSS
     *
     * @since 1.9.6
     */
    public function fts_color_options_head_css ()
    {
        ?>
        <style type="text/css"><?php echo get_option( 'fts-color-options-main-wrapper-css-input' ); ?></style>
        <?php
    }

    /**
     * FTS FB Color Options Head CSS
     *
     * Color Options CSS for Facebook.
     *
     * @since 1.9.6
     */
    public function fts_fb_color_options_head_css ()
    {
        $fb_hide_no_posts_message = get_option( 'fb_hide_no_posts_message' );
        $fb_header_extra_text_color = get_option( 'fb_header_extra_text_color' );
        $fb_text_color = get_option( 'fb_text_color' );
        $fb_link_color = get_option( 'fb_link_color' );
        $fb_link_color_hover = get_option( 'fb_link_color_hover' );
        $fb_feed_width = get_option( 'fb_feed_width' );
        $fb_feed_margin = get_option( 'fb_feed_margin' );
        $fb_feed_padding = get_option( 'fb_feed_padding' );
        $fb_feed_background_color = get_option( 'fb_feed_background_color' );
        $fb_post_background_color = get_option( 'fb_post_background_color' );
        $fb_grid_posts_background_color = get_option( 'fb_grid_posts_background_color' );
        $fb_grid_border_bottom_color = get_option( 'fb_grid_border_bottom_color' );
        $fb_loadmore_background_color = get_option( 'fb_loadmore_background_color' );
        $fb_loadmore_text_color = get_option( 'fb_loadmore_text_color' );
        $fb_border_bottom_color = get_option( 'fb_border_bottom_color' );
        $fb_grid_posts_background_color = get_option( 'fb_grid_posts_background_color' );
        $fb_reviews_backg_color = get_option( 'fb_reviews_backg_color' );
        $fb_reviews_text_color = get_option( 'fb_reviews_text_color' );

        $fb_reviews_overall_rating_background_color = get_option( 'fb_reviews_overall_rating_background_color' );
        $fb_reviews_overall_rating_border_color = get_option( 'fb_reviews_overall_rating_border_color' );
        $fb_reviews_overall_rating_text_color = get_option( 'fb_reviews_overall_rating_text_color' );
        $fb_reviews_overall_rating_background_padding = get_option( 'fb_reviews_overall_rating_background_padding' );

        $fb_max_image_width = get_option( 'fb_max_image_width' );

        $fb_events_title_color = get_option( 'fb_events_title_color' );
        $fb_events_title_size = get_option( 'fb_events_title_size' );
        $fb_events_maplink_color = get_option( 'fb_events_map_link_color' );

        $twitter_hide_profile_photo = get_option( 'twitter_hide_profile_photo' );
        $twitter_text_color = get_option( 'twitter_text_color' );
        $twitter_link_color = get_option( 'twitter_link_color' );
        $twitter_link_color_hover = get_option( 'twitter_link_color_hover' );
        $twitter_feed_width = get_option( 'twitter_feed_width' );
        $twitter_feed_margin = get_option( 'twitter_feed_margin' );
        $twitter_feed_padding = get_option( 'twitter_feed_padding' );
        $twitter_feed_background_color = get_option( 'twitter_feed_background_color' );
        $twitter_border_bottom_color = get_option( 'twitter_border_bottom_color' );
        $twitter_max_image_width = get_option( 'twitter_max_image_width' );
        $twitter_grid_border_bottom_color = get_option( 'twitter_grid_border_bottom_color' );
        $twitter_grid_posts_background_color = get_option( 'twitter_grid_posts_background_color' );
        $twitter_loadmore_background_color = get_option( 'twitter_loadmore_background_color' );
        $twitter_loadmore_text_color = get_option( 'twitter_loadmore_text_color' );

        $instagram_loadmore_background_color = get_option( 'instagram_loadmore_background_color' );
        $instagram_loadmore_text_color = get_option( 'instagram_loadmore_text_color' );

        $pinterest_board_title_color = get_option( 'pinterest_board_title_color' );
        $pinterest_board_title_size = get_option( 'pinterest_board_title_size' );
        $pinterest_board_backg_hover_color = get_option( 'pinterest_board_backg_hover_color' );

        $fts_social_icons_color = get_option( 'fts_social_icons_color' );
        $fts_social_icons_hover_color = get_option( 'fts_social_icons_hover_color' );
        $fts_social_icons_back_color = get_option( 'fts_social_icons_back_color' );

        $youtube_loadmore_background_color = get_option( 'youtube_loadmore_background_color' );
        $youtube_loadmore_text_color = get_option( 'youtube_loadmore_text_color' );

        $fb_text_size = get_option( 'fb_text_size' );
        $twitter_text_size = get_option( 'twitter_text_size' );
        ?>
        <style type="text/css"><?php if ( ! empty( $fb_header_extra_text_color ) ) { ?>

            <?php }if ( ! empty( $fb_hide_no_posts_message ) && 'yes' === $fb_hide_no_posts_message ) { ?>
            .fts-facebook-add-more-posts-notice {
                display: none !important;
            }

            .fts-jal-single-fb-post .fts-jal-fb-user-name {
                color: <?php echo esc_html( $fb_header_extra_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_loadmore_background_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $fb_loadmore_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_loadmore_text_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_loadmore_text_color ) ) { ?>
            .fts-fb-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $fb_loadmore_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_text_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post,
            .fts-simple-fb-wrapper .fts-jal-fb-description-wrap,
            .fts-simple-fb-wrapper .fts-jal-fb-post-time,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post,
            .fts-slicker-facebook-posts .fts-jal-fb-description-wrap,
            .fts-slicker-facebook-posts .fts-jal-fb-post-time {
                color: <?php echo esc_html( $fb_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_link_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post .fts-review-name,
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a,
            .fts-jal-fb-group-header-desc a {
                color: <?php echo esc_html( $fb_link_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_link_color_hover ) ) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover,
            .fts-simple-fb-wrapper .fts-fb-load-more:hover,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover,
            .fts-slicker-facebook-posts .fts-fb-load-more:hover,
            .fts-jal-fb-group-header-desc a:hover {
                color: <?php echo esc_html( $fb_link_color_hover ); ?> !important;
            }

            <?php }if ( ! empty( $fb_feed_width ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                max-width: <?php echo esc_html( $fb_feed_width ); ?> !important;
            }

            <?php }if ( ! empty( $fb_max_image_width ) ) { ?>
            .fts-fb-large-photo, .fts-jal-fb-vid-picture, .fts-jal-fb-picture, .fts-fluid-videoWrapper-html5 {
                max-width: <?php echo esc_html( $fb_max_image_width ); ?> !important;
                float: left;
            }

            <?php }if ( ! empty( $fb_events_title_color ) ) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                color: <?php echo esc_html( $fb_events_title_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_events_title_size ) ) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                font-size: <?php echo esc_html( $fb_events_title_size ); ?> !important;
                line-height: <?php echo esc_html( $fb_events_title_size ); ?> !important;
            }

            <?php }if ( ! empty( $fb_events_maplink_color ) ) { ?>
            .fts-simple-fb-wrapper a.fts-fb-get-directions {
                color: <?php echo esc_html( $fb_events_maplink_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_feed_margin ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                margin: <?php echo esc_html( $fb_feed_margin ); ?> !important;
            }

            <?php }if ( ! empty( $fb_feed_padding ) ) { ?>
            .fts-simple-fb-wrapper {
                padding: <?php echo esc_html( $fb_feed_padding ); ?> !important;
            }

            <?php }if ( ! empty( $fb_feed_background_color ) ) { ?>
            .fts-simple-fb-wrapper, .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $fb_feed_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_post_background_color ) ) { ?>
            .fts-mashup-media-top .fts-jal-single-fb-post {
                background: <?php echo esc_html( $fb_post_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_grid_posts_background_color ) ) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                background: <?php echo esc_html( $fb_grid_posts_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_border_bottom_color ) ) { ?>
            .fts-jal-single-fb-post {
                border-bottom-color: <?php echo esc_html( $fb_border_bottom_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_grid_border_bottom_color ) ) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                border-bottom-color: <?php echo esc_html( $fb_grid_border_bottom_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_grid_posts_background_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                background: <?php echo esc_html( $twitter_grid_posts_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_grid_border_bottom_color ) ) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                border-bottom-color: <?php echo esc_html( $twitter_grid_border_bottom_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_loadmore_background_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $twitter_loadmore_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_loadmore_text_color ) ) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $twitter_loadmore_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_backg_color ) ) { ?>
            .fts-review-star {
                background: <?php echo esc_html( $fb_reviews_backg_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_overall_rating_background_color ) ) { ?>
            .fts-review-details-master-wrap {
                background: <?php echo esc_html( $fb_reviews_overall_rating_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_overall_rating_border_color ) ) { ?>
            .fts-review-details-master-wrap {
                border-bottom-color: <?php echo esc_html( $fb_reviews_overall_rating_border_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_overall_rating_background_padding ) ) { ?>
            .fts-review-details-master-wrap {
                padding: <?php echo esc_html( $fb_reviews_overall_rating_background_padding ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_overall_rating_text_color ) ) { ?>
            .fts-review-details-master-wrap {
                color: <?php echo esc_html( $fb_reviews_overall_rating_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $fb_reviews_text_color ) ) { ?>
            .fts-review-star {
                color: <?php echo esc_html( $fb_reviews_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_text_color ) ) { ?>
            .tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before, a span.fts-video-loading-notice {
                color: <?php echo esc_html( $twitter_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_link_color ) ) { ?>
            .tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a, .twitter-followers-fts a, body.fts-twitter-reply-wrap a {
                color: <?php echo esc_html( $twitter_link_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_link_color_hover ) ) { ?>
            .tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply, body.fts-twitter-reply-wrap a:hover {
                color: <?php echo esc_html( $twitter_link_color_hover ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_feed_width ) ) { ?>
            .fts-twitter-div {
                max-width: <?php echo esc_html( $twitter_feed_width ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_feed_margin ) ) { ?>
            .fts-twitter-div {
                margin: <?php echo esc_html( $twitter_feed_margin ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_feed_padding ) ) { ?>
            .fts-twitter-div {
                padding: <?php echo esc_html( $twitter_feed_padding ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_feed_background_color ) ) { ?>
            .fts-twitter-div {
                background: <?php echo esc_html( $twitter_feed_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_border_bottom_color ) ) { ?>
            .tweeter-info {
                border-bottom: 1px solid <?php echo esc_html( $twitter_border_bottom_color ); ?> !important;
            }

            <?php }if ( ! empty( $twitter_max_image_width ) ) { ?>
            .fts-twitter-link-image {
                max-width: <?php echo esc_html( $twitter_max_image_width ); ?> !important;
                display: block;
            }

            <?php }if ( ! empty( $instagram_loadmore_background_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $instagram_loadmore_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important;
            }

            <?php }if ( ! empty( $instagram_loadmore_text_color ) ) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $instagram_loadmore_text_color ); ?> !important;
            }

            <?php } if ( ! empty( $pinterest_board_backg_hover_color ) ) { ?>
            a.fts-pin-board-wrap:hover {
                background: <?php echo esc_html( $pinterest_board_backg_hover_color ); ?> !important;
            }

            <?php } if ( ! empty( $pinterest_board_title_color ) ) { ?>
            body h3.fts-pin-board-board_title {
                color: <?php echo esc_html( $pinterest_board_title_color ); ?> !important;
            }

            <?php } if ( ! empty( $pinterest_board_title_size ) ) { ?>
            body h3.fts-pin-board-board_title {
                font-size: <?php echo esc_html( $pinterest_board_title_size ); ?> !important;
            }

            <?php
}
if ( ! empty( $fts_social_icons_color ) ) {
	?>
            .ft-gallery-share-wrap a.ft-galleryfacebook-icon, .ft-gallery-share-wrap a.ft-gallerytwitter-icon, .ft-gallery-share-wrap a.ft-gallerygoogle-icon, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon, .ft-gallery-share-wrap a.ft-galleryemail-icon {
                color: <?php echo esc_html( $fts_social_icons_color ); ?> !important;
            }

            <?php
}
if ( ! empty( $fts_social_icons_hover_color ) ) {
	?>
            .ft-gallery-share-wrap a.ft-galleryfacebook-icon:hover, .ft-gallery-share-wrap a.ft-gallerytwitter-icon:hover, .ft-gallery-share-wrap a.ft-gallerygoogle-icon:hover, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon:hover, .ft-gallery-share-wrap a.ft-galleryemail-icon:hover {
                color: <?php echo esc_html( $fts_social_icons_hover_color ); ?> !important;
            }

            <?php
}
if ( ! empty( $fts_social_icons_back_color ) ) {
	?>
            .ft-gallery-share-wrap {
                background: <?php echo esc_html( $fts_social_icons_back_color ); ?> !important;
            }

            <?php
}
if ( ! empty( $twitter_text_size ) ) {
	?>
            span.fts-twitter-text {
                font-size: <?php echo esc_html( $twitter_text_size ); ?> !important;
            }

            <?php
}
if ( ! empty( $fb_text_size ) ) {
	?>
            .fts-jal-fb-group-display .fts-jal-fb-message, .fts-jal-fb-group-display .fts-jal-fb-message p, .fts-jal-fb-group-header-desc, .fts-jal-fb-group-header-desc p, .fts-jal-fb-group-header-desc a {
                font-size: <?php echo esc_html( $fb_text_size ); ?> !important;
            }

            <?php
}
if ( ! empty( $youtube_loadmore_background_color ) ) {
	?>
            .fts-youtube-load-more-wrapper .fts-fb-load-more {
                background: <?php echo esc_html( $youtube_loadmore_background_color ); ?> !important;
            }

            <?php }if ( ! empty( $youtube_loadmore_text_color ) ) { ?>
            .fts-youtube-load-more-wrapper .fts-fb-load-more {
                color: <?php echo esc_html( $youtube_loadmore_text_color ); ?> !important;
            }

            <?php
}
if ( ! empty( $youtube_loadmore_text_color ) ) {
	?>
            .fts-youtube-load-more-wrapper .fts-fb-spinner > div {
                background: <?php echo esc_html( $youtube_loadmore_text_color ); ?> !important;
            }

            <?php } ?>

        </style>
        <?php
    }

    /**
     * FTS Powered By JS
     *
     * @since 1.9.6
     */
    public function fts_powered_by_js ()
    {
        wp_enqueue_script( 'fts_powered_by_js', plugins_url( 'feeds/js/powered-by.js', dirname( __FILE__ ) ), array('jquery'), FTS_CURRENT_VERSION, false );
    }

    /**
     * Required Premium Field
     *
     * Admin Required Premium Settings Fields.
     *
     * @param array $fields_info fields info.
     * @since 2.0.7
     */
    public function need_fts_premium_plugin_field ($fields_info)
    {
        $output = '<div class="feed-them-social-admin-input-default">' . wp_kses(
                $fields_info['no_active_msg'],
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array(),
                        'target' => array(),
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            ) . '</div>';

        return $output;
    }

    /**
     * Settings Form Fields Output
     *
     * @param bool $save_options save options.
     * @param array $feed_settings_array feed settings information.
     * @param array $required_plugins The plugins that are required for this form.
     * @since 2.0.8
     */
    public function fts_settings_html_form ($save_options = false, $feed_settings_array, $required_plugins)
    {

        $output = '';

        // Start creation of fields for each Feed!
        foreach ( $feed_settings_array as $section => $section_info ) {
            $output .= '<div class="' . esc_attr( $section_info['section_wrap_class'] ) . '">';
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form ' . esc_attr( $section_info['form_wrap_classes'] ) . '" id="' . esc_attr( $section_info['form_wrap_id'] ) . '">';

            // Check to see if token is in place otherwise show a message letting person no what they need to do!
            if ( isset( $section_info['token_check'] ) ) {
                foreach ( $section_info['token_check'] as $token_key => $token_info ) {
                    if ( !isset( $token_info['req_plugin'] ) || isset( $token_info['req_plugin'] ) && is_plugin_active( $required_plugins[$token_info['req_plugin']]['plugin_url'] ) ) {
                        $token_check = get_option( $token_info['option_name'] ) ? 'Yes' : 'No';
                        $output .= isset( $token_check ) && 'No' !== $token_check ? "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">' . wp_kses(
                                $token_info['no_token_msg'],
                                array(
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array(),
                                        'target' => array(),
                                    ),
                                    'br' => array(),
                                    'em' => array(),
                                    'strong' => array(),
                                    'small' => array(),
                                )
                            ) . '</div>' . "\n";
                    }
                }
            }
            // Section Title!
            $output .= isset( $section_info['section_title'] ) ? '<h2>' . esc_html( $section_info['section_title'] ) . '</h2>' : '';
            // Feed Types select!
            if ( isset( $section_info['feeds_types'] ) ) {
                $output .= '<div class="feed-them-social-admin-input-wrap ' . esc_attr( $section_info['feed_type_select']['select_wrap_classes'] ) . '">';
                $output .= '<div class="feed-them-social-admin-input-label">' . wp_kses(
                        $section_info['feed_type_select']['label'],
                        array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                                'target' => array(),
                            ),
                            'br' => array(),
                            'em' => array(),
                            'strong' => array(),
                        )
                    ) . '</div>';
                $output .= '<select name="' . esc_attr( $section_info['feed_type_select']['select_name'] ) . '" id="' . esc_attr( $section_info['feed_type_select']['select_id'] ) . '" class="feed-them-social-admin-input ' . esc_attr( $section_info['feed_type_select']['select_classes'] ) . '">';
                foreach ( $section_info['feeds_types'] as $feed_type_name => $feed_type ) {
                    if ( 'main_options' !== $feed_type_name ) {
                        $output .= '<option value="' . esc_attr( $feed_type['value'] ) . '">' . esc_html( $feed_type['title'] ) . '</option>';
                    }
                }
                $output .= '</select>';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/Feed Types Select Div Wrap-->';
            }

            // Conversion Input!
            if ( isset( $section_info['conversion_input'] ) ) {
                $output .= '<div class="' . esc_attr( $section_info['conversion_input']['main_wrap_class'] ) . '">';
                $output .= '<h2>' . esc_html( $section_info['conversion_input']['conv_section_title'] ) . '</h2>';
                $output .= '<div class="feed-them-social-admin-input-wrap ' . esc_attr( $section_info['conversion_input']['input_wrap_class'] ) . '">';
                // Instructional Text!
                $output .= '<div class="instructional-text">' . esc_html( $section_info['conversion_input']['instructional-text'] ) . '</div>';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( $section_info['conversion_input']['label'] ) . '</div>';
                // Input!
                $output .= '<input type="input" name="' . esc_attr( $section_info['conversion_input']['name'] ) . '" id="' . esc_attr( $section_info['conversion_input']['id'] ) . '" class="feed-them-social-admin-input ' . (isset( $section_info['conversion_input']['class'] ) ? esc_attr( $section_info['conversion_input']['class'] ) : '') . '" value="" />';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/Conversion Input Wrap-->';

                $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="' . esc_attr( $section_info['conversion_input']['btn-value'] ) . '" onclick="' . esc_js( $section_info['conversion_input']['onclick'] ) . '" tabindex="4" style="margin-right:1em;" />';
                $output .= '</div>';

            }

            $output .= '</form>';

            // Feed Options!
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form ' . esc_attr( $section_info['form_wrap_classes'] ) . ' ' . esc_attr( $section ) . '_options_wrap">';

            // Create settings fields for Feed OPTIONS!
            foreach ( $section_info['main_options'] as $option ) {
                if ( !isset( $option['no_html'] ) || isset( $option['no_html'] ) && 'yes' !== $option['no_html'] ) {

                    // Is a premium extension required?
                    $required_plugin = (!isset( $option['req_plugin'] ) || isset( $option['req_plugin'] ) && is_plugin_active( $required_plugins[$option['req_plugin']]['plugin_url'] ));
                    $or_required_plugin = (isset( $option['or_req_plugin'] ) && is_plugin_active( $required_plugins[$option['or_req_plugin']]['plugin_url'] ));
                    $or_required_plugin_three = (isset( $option['or_req_plugin_three'] ) && is_plugin_active( $required_plugins[$option['or_req_plugin_three']]['plugin_url'] ));

                    // Sub option output START?
                    $output .= isset( $option['sub_options'] ) ? '<div class="' . esc_attr( $option['sub_options']['sub_options_wrap_class'] ) . (!$required_plugin ? ' not-active-premium-fields' : '') . '">' . (isset( $option['sub_options']['sub_options_title'] ) ? '<h3>' . esc_html( $option['sub_options']['sub_options_title'] ) . '</h3>' : '') . (isset( $option['sub_options']['sub_options_instructional_txt'] ) ? '<div class="instructional-text">' . esc_html( $option['sub_options']['sub_options_instructional_txt'] ) . '</div>' : '') : '';

                    $output .= isset( $option['grouped_options_title'] ) ? '<h3 class="sectioned-options-title">' . esc_html( $option['grouped_options_title'] ) . '</h3>' : '';

                    // Only on a few options generally!
                    $output .= isset( $option['outer_wrap_class'] ) || isset( $option['outer_wrap_display'] ) ? '<div ' . (isset( $option['outer_wrap_class'] ) ? 'class="' . esc_attr( $option['outer_wrap_class'] ) . '"' : '') . ' ' . (isset( $option['outer_wrap_display'] ) && !empty( $option['outer_wrap_display'] ) ? 'style="display:' . esc_attr( $option['outer_wrap_display'] ) . '"' : '') . '>' : '';
                    // Main Input Wrap!
                    $output .= '<div class="feed-them-social-admin-input-wrap ' . (isset( $option['input_wrap_class'] ) ? esc_attr( $option['input_wrap_class'] ) : '') . '" ' . (isset( $section_info['input_wrap_id'] ) ? 'id="' . esc_attr( $section_info['input_wrap_id'] ) . '"' : '') . '>';
                    // Instructional Text!
                    $output .= !empty( $option['instructional-text'] ) && !is_array( $option['instructional-text'] ) ? '<div class="instructional-text ' . (isset( $option['instructional-class'] ) ? esc_attr( $option['instructional-class'] ) : '') . '">' . wp_kses(
                            $option['instructional-text'],
                            array(
                                'a' => array(
                                    'href' => array(),
                                    'title' => array(),
                                    'target' => array(),
                                ),
                                'br' => array(),
                                'em' => array(),
                                'strong' => array(),
                            )
                        ) . '</div>' : '';

                    if ( !empty( $option['instructional-text'] ) && is_array( $option['instructional-text'] ) ) {
                        foreach ( $option['instructional-text'] as $instructional_txt ) {
                            // Instructional Text!
                            $output .= '<div class="instructional-text ' . (isset( $instructional_txt['class'] ) ? esc_attr( $instructional_txt['class'] ) : '') . '">' . wp_kses(
                                    $instructional_txt['text'],
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                            'title' => array(),
                                            'target' => array(),
                                        ),
                                        'br' => array(),
                                        'em' => array(),
                                        'strong' => array(),
                                    )
                                ) . '</div>';
                        }
                    }

                    // Label Text!
                    $output .= isset( $option['label'] ) && !is_array( $option['label'] ) ? '<div class="feed-them-social-admin-input-label ' . (isset( $option['label_class'] ) ? esc_attr( $option['label_class'] ) : '') . '">' . wp_kses(
                            $option['label'],
                            array(
                                'a' => array(
                                    'href' => array(),
                                    'title' => array(),
                                    'target' => array(),
                                ),
                                'br' => array(),
                                'em' => array(),
                                'strong' => array(),
                                'small' => array(),
                            )
                        ) . '</div>' : '';

                    if ( !empty( $option['label'] ) && is_array( $option['label'] ) ) {
                        foreach ( $option['label'] as $label_txt ) {
                            // Label Text!
                            $output .= '<div class="feed-them-social-admin-input-label ' . (isset( $label_txt['class'] ) ? esc_attr( $label_txt['class'] ) : '') . '">' . wp_kses(
                                    $label_txt['text'],
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                            'title' => array(),
                                            'target' => array(),
                                        ),
                                        'br' => array(),
                                        'em' => array(),
                                        'strong' => array(),
                                        'small' => array(),
                                    )
                                ) . '</div>';
                        }
                    }

                    if ( $required_plugin || $or_required_plugin || $or_required_plugin_three ) {
                        // Option_Type = INPUT!
                        $output .= isset( $option['option_type'] ) && 'input' === $option['option_type'] ? '<input type="' . esc_attr( $option['type'] ) . '" name="' . esc_attr( $option['name'] ) . '" id="' . esc_attr( $option['id'] ) . '" class="' . (isset( $option['color_picker'] ) && 'yes' === $option['color_picker'] ? 'color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'} ' : '') . 'feed-them-social-admin-input ' . (isset( $option['class'] ) ? esc_attr( $option['class'] ) : '') . '" placeholder="' . (isset( $option['placeholder'] ) ? esc_attr( $option['placeholder'] ) : '') . '" value="' . (isset( $option['value'] ) ? '' . esc_html( $option['value'] ) : '') . '" />' : '';

                        // Option_Type = Select!
                        if ( isset( $option['option_type'] ) && 'select' === $option['option_type'] ) {
                            $output .= '<select name="' . esc_attr( $option['name'] ) . '" id="' . esc_attr( $option['id'] ) . '"  class="feed-them-social-admin-input">';
                            foreach ( $option['options'] as $select_option ) {
                                $output .= '<option value="' . esc_html( $select_option['value'] ) . '"' . (isset( $option['default_value'] ) && $option['default_value'] === $select_option['value'] ? ' selected' : '') . '>' . esc_html( $select_option['label'] ) . '</option>';
                            }
                            $output .= '</select>';
                        }
                    } else {
                        // Create Required Plugin fields!
                        $output .= $this->need_fts_premium_plugin_field( $required_plugins[$option['req_plugin']] );
                    }
                    $output .= '<div class="fts-clear"></div>';
                    $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

                    $output .= isset( $option['outer_wrap_class'] ) || isset( $option['outer_wrap_display'] ) ? '</div>' : '';

                    // Sub option output END?
                    if ( isset( $option['sub_options_end'] ) ) {
                        $output .= !is_numeric( $option['sub_options_end'] ) ? '</div>' : '';
                        // Multiple Div needed?
                        if ( is_numeric( $option['sub_options_end'] ) ) {
                            $x = 1;
                            while ($x <= $option['sub_options_end']) {
                                $output .= '</div>';
                                $x++;
                            }
                        }
                    }
                }
            }
            $output .= $this->generate_shortcode( 'updateTextArea_' . $section . '();', $section_info['generator_title'], $section_info['generator_class'] );
            $output .= '</form>';

            $output .= '</div> <!--/Section Wrap Class END (Main-Section-Div)-->';

            // Premium Message Boxes!
            if ( isset( $section_info['premium_msg_boxes'] ) ) {
                foreach ( $section_info['premium_msg_boxes'] as $key => $premium_msg ) {
                    if ( !is_plugin_active( $required_plugins[$premium_msg['req_plugin']]['plugin_url'] ) ) {
                        $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message" id="not_active_' . esc_attr( $key ) . '"><a class="not-active-title" href="' . esc_url( $required_plugins[$premium_msg['req_plugin']]['slick_url'] ) . '" target="_blank">' .

                            wp_kses(
                                $required_plugins[$premium_msg['req_plugin']]['name'],
                                array(
                                    'h3' => array(),
                                )
                            ) .

                            '</a>' . wp_kses(
                                $premium_msg['msg'],
                                array(
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array(),
                                        'target' => array(),
                                    ),
                                    'br' => array(),
                                    'em' => array(),
                                    'strong' => array(),
                                    'small' => array(),
                                )
                            ) . '</div>';
                    }
                }
            }
        }
        return $output;
    }

    /**
     * FTS Facebook Page Form
     *
     * @param bool $save_options save options.
     * @since 1.9.6
     */
    public function fts_facebook_page_form ($save_options = false)
    {
        $fts_fb_page_form_nonce = wp_create_nonce( 'fts-facebook-page-form-nonce' );

        if ( wp_verify_nonce( $fts_fb_page_form_nonce, 'fts-facebook-page-form-nonce' ) ) {
            if ( $save_options ) {
                $fb_page_id_option = get_option( 'fb_page_id' );
                $fb_page_posts_displayed_option = get_option( 'fb_page_posts_displayed' );
                $fb_page_post_count_option = get_option( 'fb_page_post_count' );
                $fb_page_title_option = get_option( 'fb_page_title_option' );
                $fb_page_description_option = get_option( 'fb_page_description_option' );
                $fb_page_word_count_option = get_option( 'fb_page_word_count_option' );
                $fts_bar_fb_prefix = 'fb_page_';
                $fb_load_more_option = get_option( 'fb_page_fb_load_more_option' );
                $fb_load_more_style = get_option( 'fb_page_fb_load_more_style' );
                $facebook_popup = get_option( 'fb_page_facebook_popup' );
            }
            $output = '<div class="fts-facebook_page-shortcode-form">';
            if ( false === $save_options ) {
                $output = '<form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">';

                // Check to see if token is in place otherwise show a message letting person no what they need to do!
                $facebook_options = get_option( 'fts_facebook_custom_api_token' ) ? 'Yes' : 'No';
                $output .= isset( $facebook_options ) && 'No' !== $facebook_options ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please get your Access Token on our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page.</div>' . "\n";
                // end custom message for requiring token!
                if ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
                    $facebook_options2 = get_option( 'fts_facebook_custom_api_token_biz' ) ? 'Yes' : 'No';
                    // Check to see if token is in place otherwise show a message letting person no what they need to do
                    // $output .= isset($facebook_options2) && $facebook_options2 !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a Facebook Page Reviews API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before trying to view your Facebook Reviews feed.</div>' . "\n";
                    // end custom message for requiring token!
                }

                $output .= '<h2>' . esc_html( 'Facebook Page Shortcode Generator', 'feed-them-social' ) . '</h2>';
            }
            $fb_page_id_option = isset( $fb_page_id_option ) ? $fb_page_id_option : '';
            // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR!
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                // FACEBOOK FEED TYPE!
                $output .= '<div class="feed-them-social-admin-input-wrap" id="fts-social-selector">';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Feed Type', 'feed-them-social' ) . '</div>';
                $output .= '<select name="facebook-messages-selector" id="facebook-messages-selector" class="feed-them-social-admin-input">';
                $output .= '<option value="page">' . esc_html( 'Facebook Page', 'feed-them-social' ) . '</option>';
                $output .= '<option value="events">' . esc_html( 'Facebook Page List of Events', 'feed-them-social' ) . '</option>';
                $output .= '<option value="event">' . esc_html( 'Facebook Page Single Event Posts', 'feed-them-social' ) . '</option>';
                $output .= '<option value="group">' . esc_html( 'Facebook Group', 'feed-them-social' ) . '</option>';
                $output .= '<option value="album_photos">' . esc_html( 'Facebook Album Photos', 'feed-them-social' ) . '</option>';
                $output .= '<option value="albums">' . esc_html( 'Facebook Album Covers', 'feed-them-social' ) . '</option>';
                $output .= '<option value="album_videos">' . esc_html( 'Facebook Videos', 'feed-them-social' ) . '</option>';
                $output .= '<option value="reviews">' . esc_html( 'Facebook Page Reviews', 'feed-them-social' ) . '</option>';
                $output .= '</select>';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            };
            // INSTRUCTIONAL TEXT FOR FACEBOOK TYPE SELECTION. PAGE, GROUP, EVENT, ALBUMS, ALBUM COVERS AND HASH TAGS!
            $output .= '<div class="instructional-text facebook-message-generator page inst-text-facebook-page" style="display:block;">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . esc_html( 'Facebook Page ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below. You cannot use Personal Profiles it must be a Facebook Page. If your page ID looks something like, My-Page-Name-50043151918, only use the number portion, 50043151918.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/?feedID=50043151918" target="_blank">' . esc_html( 'Test your Page ID on our demo', 'feed-them-social' ) . '</a></div>
			<div class="instructional-text facebook-message-generator group inst-text-facebook-group">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-group-id/" target="_blank">' . esc_html( 'Facebook Group ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</div>
			<div class="instructional-text facebook-message-generator event-list inst-text-facebook-event-list">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . esc_html( 'Facebook Event ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below. PLEASE NOTE: This will only work with Facebook Page Events and you cannot have more than 25 events on Facebook.', 'feed-them-social' ) . '</div>
			<div class="instructional-text facebook-message-generator event inst-text-facebook-event">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . esc_html( 'Facebook Event ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</div>
			<div class="instructional-text facebook-message-generator album_photos inst-text-facebook-album-photos">' . esc_html( 'To show a specific Album copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . esc_html( 'Facebook Album ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the second input below. If you want to show all your uploaded photos leave the Album ID input blank.', 'feed-them-social' ) . '</div>
			<div class="instructional-text facebook-message-generator albums inst-text-facebook-albums">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . esc_html( 'Facebook Album Covers ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</div>
			<div class="instructional-text facebook-message-generator video inst-text-facebook-video">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-id-and-video-gallery-id" target="_blank">' . esc_html( 'Facebook ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</div>';
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                // this is for the facebook videos!
                $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium Version Required</a><br/>The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your WordPress site!<br/><a href="https://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br/><br/>Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too!<br/><a href="https://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a> </div>';
                // this is for the facebook reviews!
                $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message2" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-facebook-reviews/">Facebook Reviews Required</a><br/>The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="https://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a></div>';
            }
            // FACEBOOK PAGE ID!
            if ( isset( $_GET['page'] ) && 'fts-bar-settings-page' !== $_GET['page'] ) {
                $output .= '<div class="fb-options-wrap">';
            }
            $output .= '<div class="feed-them-social-admin-input-wrap fb_page_id ">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Facebook ID (required)', 'feed-them-social' ) . '</div>';
            $output .= '<input type="text" name="fb_page_id" id="fb_page_id" class="feed-them-social-admin-input" value="' . esc_html( $fb_page_id_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            // FACEBOOK ALBUM PHOTOS ID!
            $output .= '<div class="feed-them-social-admin-input-wrap fb_album_photos_id" style="display:none;">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Album ID ', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Leave blank to show all uploaded photos', 'feed-them-social' ) . '</small></div>';
            $output .= '<input type="text" name="fb_album_id" id="fb_album_id" class="feed-them-social-admin-input" value="" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $fb_page_posts_displayed_option = isset( $fb_page_posts_displayed_option ) ? $fb_page_posts_displayed_option : '';
            // FACEBOOK PAGE POST TYPE VISIBLE!
            $output .= '<div class="feed-them-social-admin-input-wrap facebook-post-type-visible">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Post Type Visible', 'feed-them-social' ) . '</div>';
            $output .= '<select name="fb_page_posts_displayed" id="fb_page_posts_displayed" class="feed-them-social-admin-input">';
            $output .= '<option ' . selected( $fb_page_posts_displayed_option, 'page_only', false ) . ' value="page_only">' . esc_html( 'Display Posts made by Page only', 'feed-them-social' ) . '</option>';
            $output .= '<option ' . selected( $fb_page_posts_displayed_option, 'page_and_others', false ) . ' value="page_and_others">' . esc_html( 'Display Posts made by Page and Others', 'feed-them-social' ) . '</option>';
            $output .= '</select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            $fb_page_post_count_option = isset( $fb_page_post_count_option ) ? $fb_page_post_count_option : '';
            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( '# of Posts', 'feed-them-premium' );

            if ( !is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) || !is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {
                $output .= '<br/><small>' . esc_html( 'More than 6 Requires <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-premium' ) . '</small>';
            }
            $output .= '</div>';
            $output .= '<input type="text" name="fb_page_post_count" id="fb_page_post_count" class="feed-them-social-admin-input" value="' . esc_html( $fb_page_post_count_option ) . '" placeholder="5 ' . esc_html( 'is the default number', 'feed-them-premium' ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR!
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                // FACEBOOK HEIGHT OPTION!
                $output .= '<div class="feed-them-social-admin-input-wrap twitter_name fixed_height_option">';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Facebook Fixed Height', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Leave blank for auto height', 'feed-them-social' ) . '</small></div>';
                $output .= '<input type="text" name="facebook_page_height" id="facebook_page_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . esc_html( 'for example', 'feed-them-social' ) . '" />';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            }

            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && !is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {

                include $this->premium . 'admin/facebook-page-settings-fields.php';
                if ( isset( $_GET['page'] ) && 'fts-bar-settings-page' === $_GET['page'] ) {
                    // PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR!
                    include $this->premium . 'admin/facebook-loadmore-settings-fields.php';
                }
            } elseif ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) ) {

                // these are the new options for reviews only!
                include $this->facebook_reviews . 'admin/facebook-review-settings-fields.php';

                include $this->premium . 'admin/facebook-page-settings-fields.php';
                if ( isset( $_GET['page'] ) && 'fts-bar-settings-page' === $_GET['page'] ) {
                    // PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR!
                    include $this->premium . 'admin/facebook-loadmore-settings-fields.php';
                }
            } elseif ( is_plugin_active( 'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' ) && !is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                // include($this->facebook_reviews.'admin/facebook-page-settings-fields.php');
                // these are the new options for reviews only!
                include $this->facebook_reviews . 'admin/facebook-review-settings-fields.php';

                // these are the additional options only for reviews from premium!
                include $this->facebook_reviews . 'admin/facebook-loadmore-settings-fields.php';

                // Create Need Premium Fields!
                $fields = array(
                    esc_html( 'Show the Page Title', 'feed-them-social' ),
                    esc_html( 'Align Title', 'feed-them-social' ),
                    esc_html( 'Show the Page Description', 'feed-them-social' ),
                    esc_html( 'Amount of words per post', 'feed-them-social' ),
                    esc_html( 'Load More Posts', 'feed-them-social' ),
                    esc_html( 'Display Photos in Popup', 'feed-them-social' ),
                    esc_html( 'Display Posts in Grid', 'feed-them-social' ),
                    esc_html( 'Center Grid', 'feed-them-social' ),
                    esc_html( 'Grid Stack Animation', 'feed-them-social' ),
                    esc_html( 'Hide Like Button or Box', 'feed-them-social' ),
                    esc_html( 'Like Box Width', 'feed-them-social' ),
                    esc_html( 'Position Like Box', 'feed-them-social' ),
                    esc_html( 'Align Like Button or Box', 'feed-them-social' ),
                );
                $output .= '<div class="need-for-premium-fields-wrap">';
                $output .= $this->need_fts_premium_fields( $fields );
                $output .= '</div>';
            } else {

                // Create Need Premium Fields!
                $fields = array(
                    esc_html( 'Show the Page Title', 'feed-them-social' ),
                    esc_html( 'Align Title', 'feed-them-social' ),
                    esc_html( 'Show the Page Description', 'feed-them-social' ),
                    esc_html( 'Amount of words per post', 'feed-them-social' ),
                    esc_html( 'Load More Posts', 'feed-them-social' ),
                    esc_html( 'Display Photos in Popup', 'feed-them-social' ),
                    esc_html( 'Display Posts in Grid', 'feed-them-social' ),
                    esc_html( 'Center Grid', 'feed-them-social' ),
                    esc_html( 'Grid Stack Animation', 'feed-them-social' ),
                    esc_html( 'Hide Like Button or Box', 'feed-them-social' ),
                    esc_html( 'Like Box Width', 'feed-them-social' ),
                    esc_html( 'Position Like Box', 'feed-them-social' ),
                    esc_html( 'Align Like Button or Box', 'feed-them-social' ),
                );
                $output .= $this->need_fts_premium_fields( $fields );
            }
            // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR!
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                // SUPER FACEBOOK GALLERY OPTIONS!
                $output .= '<div class="fts-super-facebook-options-wrap" style="display:none">';
                // FACEBOOK IMAGE HEIGHT!
                $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Facebook Image Width', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Max width is 640px', 'feed-them-social' ) . '</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-width" id="fts-slicker-facebook-container-image-width" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="fts-clear"></div> </div>';
                // FACEBOOK IMAGE WIDTH!
                $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Facebook Image Height', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Max width is 640px', 'feed-them-social' ) . '</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-height" id="fts-slicker-facebook-container-image-height" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="fts-clear"></div> </div>';
                // FACEBOOK SPACE BETWEEN PHOTOS!
                $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'The space between photos', 'feed-them-social' ) . '</div>
	           <input type="text" name="fts-slicker-facebook-container-margin" id="fts-slicker-facebook-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
	           <div class="fts-clear"></div></div>';
                // HIDE DATES, LIKES AND COMMENTS ETC!
                $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Hide Date, Likes and Comments', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Good for image sizes under 120px', 'feed-them-social' ) . '</small></div>
	       		 <select id="fts-slicker-facebook-container-hide-date-likes-comments" name="fts-slicker-facebook-container-hide-date-likes-comments" class="feed-them-social-admin-input">
	        	  <option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select><div class="fts-clear"></div></div>';

                // CENTER THE FACEBOOK CONTAINER!
                $output .= '<div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_container"><div class="feed-them-social-admin-input-label">' . esc_html( 'Center Facebook Container', 'feed-them-social' ) . '</div>
	        	<select id="fts-slicker-facebook-container-position" name="fts-slicker-facebook-container-position" class="feed-them-social-admin-input"><option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select><div class="fts-clear"></div></div>';
                // ANIMATE PHOTO POSITIONING!
                $output .= ' <div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_animate"><div class="feed-them-social-admin-input-label">' . esc_html( 'Image Stacking Animation On', 'feed-them-social' ) . '<br/><small>' . esc_html( 'This happens when resizing browsert', 'feed-them-social' ) . '</small></div>
	        	 <select id="fts-slicker-facebook-container-animation" name="fts-slicker-facebook-container-animation" class="feed-them-social-admin-input"><option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select><div class="fts-clear"></div></div>';
                // POSITION IMAGE LEFT RIGHT!
                $output .= '<div class="instructional-text" style="display: block;">' . esc_html( 'These options allow you to make the thumbnail larger if you do not want to see black bars above or below your photos.', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/docs/fit-thumbnail-on-facebook-galleries/" target="_blank">' . esc_html( 'View Examples', 'feed-them-social' ) . '</a> ' . esc_html( 'and simple details or leave default options.', 'feed-them-social' ) . '</div>
			<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Make photo larger', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Helps with blackspace', 'feed-them-social' ) . '</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-lr" name="fts-slicker-facebook-image-position-lr" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -50%. -0% ' . esc_html( 'is default', 'feed-them-social' ) . '">
	           <div class="fts-clear"></div></div>';
                // POSITION IMAGE TOP!
                $output .= ' <div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Image Position Top', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Helps center image', 'feed-them-social' ) . '</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-top" name="fts-slicker-facebook-image-position-top" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -10%. -0% ' . esc_html( 'is default', 'feed-them-social' ) . '">
				<div class="fts-clear"></div></div>';
                $output .= '</div><!--fts-super-facebook-options-wrap-->';

                if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                    // PREMIUM LOAD MORE SETTINGS!
                    include $this->premium . 'admin/facebook-loadmore-settings-fields.php';
                }

                // Slideshow Carousel Options!
                $output .= '<div class="slideshow-wrap" style="display: none;">';
                $output .= '<div class="instructional-text" style="display: block;">' . esc_html( 'Create a Carousel or Slideshow with these options.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . esc_html( 'View Demos', 'feed-them-social' ) . '</a> ' . esc_html( 'and copy easy to use shortcode examples.', 'feed-them-social' ) . '</div>';

                if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) && is_plugin_active( 'feed-them-carousel-premium/feed-them-carousel-premium.php' ) ) {
                    include $this->facebook_carousel_premium . 'admin/facebook-carousel-options-settings-page.php';
                } else {
                    // if slider plugin is active!
                    $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . esc_html( 'Carousel or Slideshow', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Many more options when active', 'feed-them-social' ) . '</small></div>
				<div class="feed-them-social-admin-input-default" style="display: block !important;">' . esc_html( 'Must have ', 'feed-them-social' ) . ' <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">' . esc_html( 'premium', 'feed-them-social' ) . '</a> ' . esc_html( 'and', 'feed-them-social' ) . ' <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-carousel-premium/">' . esc_html( 'carousel', 'feed-them-social' ) . '</a> ' . esc_html( 'plugin ', 'feed-them-social' ) . '</a> ' . esc_html( 'to edit.', 'feed-them-social' ) . '</div> <div class="fts-clear"></div></div>';
                }

                // end slideshow wrap!
                $output .= '</div>';

            }
            if ( $save_options ) {
                $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
            } else {
                $output .= $this->generate_shortcode( 'updateTextArea_fb_page();', 'Facebook Page Feed Shortcode', 'facebook-page-final-shortcode' );
                if ( isset( $_GET['page'] ) && 'fts-bar-settings-page' !== $_GET['page'] ) {
                    $output .= '</div>';
                    // END fb-options-wrap!
                }
                $output .= '</form>';
            }
            $output .= '</div><!--/fts-facebook_page-shortcode-form-->';
        }
        return $output;
    }

    /**
     * FTS Twitter Form
     *
     * @param bool $save_options save options.
     * @since 1.9.6
     */
    public function fts_twitter_form ($save_options = false)
    {
        $fts_twitter_form_nonce = wp_create_nonce( 'fts-twitter-form-nonce' );

        if ( wp_verify_nonce( $fts_twitter_form_nonce, 'fts-twitter-form-nonce' ) ) {

            if ( $save_options ) {
                $twitter_name_option = get_option( 'twitter_name' );
                $tweets_count_option = get_option( 'tweets_count' );
                $twitter_popup_option = get_option( 'twitter_popup_option' );
                $twitter_hashtag_etc_name = get_option( 'twitter_hashtag_etc_name' );
                $twitter_load_more_option = get_option( 'twitter_load_more_option' );
            }

            $twitter_name_option = isset( $twitter_name_option ) ? $twitter_name_option : '';
            $tweets_count_option = isset( $tweets_count_option ) ? $tweets_count_option : '';
            $twitter_hashtag_etc_name = isset( $twitter_hashtag_etc_name ) ? $twitter_hashtag_etc_name : '';
            $output = '<div class="fts-twitter-shortcode-form">';
            if ( false === $save_options ) {
                $output .= '<form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">';

                // Check to see if token is in place otherwise show a message letting person no what they need to do!
                $twitter_options4 = get_option( 'fts_twitter_custom_access_token_secret' ) ? 'Yes' : 'No';
                $output .= isset( $twitter_options4 ) && 'No' !== $twitter_options4 ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before trying to view your feed.</div>' . "\n";
                // end custom message for requiring token!
                $output .= '<h2>' . esc_html( 'Twitter Shortcode Generator', 'feed-them-social' ) . '</h2>';
            }

            // TWITTER FEED TYPE!
            $output .= '<div class="feed-them-social-admin-input-wrap twitter-gen-selection">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Feed Type', 'feed-them-social' ) . '</div>';
            $output .= '<select name="twitter-messages-selector" id="twitter-messages-selector" class="feed-them-social-admin-input">';
            $output .= '<option value="user">' . esc_html( 'User Feed', 'feed-them-social' ) . '</option>';
            $output .= '<option value="hashtag">' . esc_html( '#hashtag, @person, or single words', 'feed-them-social' ) . '</option>';
            $output .= '</select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            $output .= '<div class="twitter-hashtag-etc-wrap">';
            $output .= '<h3>' . esc_html( 'Twitter Search', 'feed-them-social' ) . '</h3>';
            $output .= '<div class="instructional-text">';
            $output .= sprintf(
                esc_html( 'You can use #hashtag, @person, or single words. For example, weather or weather-channel.%1$sIf you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. %2$sfrom:user_name%#YourHashtag%3$s', 'feed-them-social' ),
                '<br/><br/>',
                '<strong style="color:#225DE2;">',
                '</strong>'
            );
            $output .= '</div>';
            $output .= '<div class="feed-them-social-admin-input-wrap twitter_hashtag_etc_name">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Twitter Search Name (required)', 'feed-them-social' ) . '</div>';
            $output .= '<input type="text" name="twitter_hashtag_etc_name" id="twitter_hashtag_etc_name" class="feed-them-social-admin-input" value="' . esc_html( $twitter_hashtag_etc_name ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $output .= '</div><!--/twitter-hashtag-etc-wrap-->';

            $output .= '<div class="instructional-text"><span class="hashtag-option-small-text">' . esc_html( 'Twitter Name is only required if you want to show a', 'feed-them-social' ) . ' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">' . esc_html( 'Follow Button', 'feed-them-social' ) . '</a>.</span><span class="must-copy-twitter-name">' . esc_html( 'You must copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . esc_html( 'Twitter Name', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</span></div>';
            $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Twitter Name', 'feed-them-social' ) . ' <span class="hashtag-option-not-required">' . esc_html( '(required)', 'feed-them-social' ) . '</span></div>';
            $output .= '<input type="text" name="twitter_name" id="twitter_name" class="feed-them-social-admin-input" value="' . esc_html( $twitter_name_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( '# of Tweets (optional)', 'feed-them-premium' );
            if ( !is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                $output .= sprintf(
                    esc_html( '%1$s More than 6 Requires the %2$sPremium Extension%3$s', 'feed-them-social' ),
                    '<br/><small>',
                    '<a target="_blank" href="' . esc_url( 'http://www.slickremix.com/downloads/feed-them-social-premium-extension/' ) . '">',
                    '</a></small>'
                );
            }
            $output .= '</div>';
            $output .= '<input type="text" name="tweets_count" id="tweets_count" placeholder="5 is the default number" class="feed-them-social-admin-input" value="' . esc_html( $tweets_count_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                $output .= '<div class="feed-them-social-admin-input-wrap">';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Twitter Fixed Height', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Leave blank for auto height', 'feed-them-social' ) . '</small></div>';
                $output .= '<input type="text" name="twitter_height" id="twitter_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . esc_html( 'for example', 'feed-them-social' ) . '" />';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            }

            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Show Retweets', 'feed-them-social' ) . '</div>';
            $output .= '<select name="twitter-show-retweets" id="twitter-show-retweets" class="feed-them-social-admin-input">';
            $output .= '<option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option>';
            $output .= '<option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option>';
            $output .= '</select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                include $this->premium . 'admin/twitter-settings-fields.php';
            } else {
                // Create Need Premium Fields!
                $fields = array(
                    __( 'Display Photos in Popup', 'feed-them-social' ),
                );
                $output .= $this->need_fts_premium_fields( $fields );
            }
            if ( $save_options ) {
                $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . esc_html( 'Save Changes', 'feed-them-social' ) . '" />';
            } else {
                $output .= $this->generate_shortcode( 'updateTextArea_twitter();', 'Twitter Feed Shortcode', 'twitter-final-shortcode' );
                $output .= '</form>';
            }
            $output .= '</div><!--/fts-twitter-shortcode-form-->';
        }

        return $output;
    }

    /**
     * FTS Instagram Form
     *
     * @param bool $save_options save options.
     * @since 1.9.7
     */
    public function fts_instagram_form ($save_options = false)
    {
        $fts_instagram_form_nonce = wp_create_nonce( 'fts-instagram-form-nonce' );

        if ( wp_verify_nonce( $fts_instagram_form_nonce, 'fts-instagram-form-nonce' ) ) {

            if ( $save_options ) {
                $instagram_name_option = get_option( 'convert_instagram_username' );
                $instagram_id_option = get_option( 'instagram_id' );
                $pics_count_option = get_option( 'pics_count' );
                $instagram_popup_option = get_option( 'instagram_popup_option' );
                $instagram_load_more_option = get_option( 'instagram_load_more_option' );
            }
            $output = '<div class="fts-instagram-shortcode-form">';
            if ( false === $save_options ) {
                $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">';

                // Check to see if token is in place otherwise show a message letting person no what they need to do!
                $instagram_options = get_option( 'fts_instagram_custom_api_token' ) ? 'Yes' : 'No';
                $output .= isset( $instagram_options ) && 'No' !== $instagram_options ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please get your Access Token on the <a href="admin.php?page=fts-instagram-feed-styles-submenu-page">Instagram Options</a> page or you won\'t be able to view your photos.</div>' . "\n";
                // end custom message for requiring token!
                // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR!
                if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                    // INSTAGRAM FEED TYPE!
                    $output .= '<h2>' . esc_html( 'Instagram Shortcode Generator', 'feed-them-social' ) . '</h2><div class="feed-them-social-admin-input-wrap instagram-gen-selection">';
                    $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Feed Type', 'feed-them-social' ) . '</div>';
                    $output .= '<select name="instagram-messages-selector" id="instagram-messages-selector" class="feed-them-social-admin-input">';
                    $output .= '<option value="user">' . esc_html( 'User Feed', 'feed-them-social' ) . '</option>';
                    $output .= '<option value="hashtag">' . esc_html( 'Hashtag Feed', 'feed-them-social' ) . '</option>';
                    $output .= '</select>';
                    $output .= '<div class="fts-clear"></div>';
                    $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
                };
                $output .= '<div class="instagram-id-option-wrap">';
                $output .= '<h3>' . esc_html( 'Convert Instagram Name to ID', 'feed-them-social' ) . '</h3>';
            }
            $instagram_name_option = isset( $instagram_name_option ) ? $instagram_name_option : '';
            $instagram_id_option = isset( $instagram_id_option ) ? $instagram_id_option : '';
            $output .= '<div class="instructional-text">' . esc_html( 'You must copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">' . esc_html( 'Instagram Name', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below', 'feed-them-social' ) . '</div>';
            $output .= '<div class="feed-them-social-admin-input-wrap convert_instagram_username">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Instagram Name (required)', 'feed-them-social' ) . '</div>';
            $output .= '<input type="text" id="convert_instagram_username" name="convert_instagram_username" class="feed-them-social-admin-input" value="' . esc_html( $instagram_name_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="' . esc_html( 'Convert Instagram Username', 'feed-them-social' ) . '" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />';
            // ONLY THIS DIV IF ON OUR SETTINGS PAGE!
            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {
                $output .= '</div><!--instagram-id-option-wrap-->';
            };
            if ( false === $save_options ) {
                $output .= '</form>';
            }
            if ( false === $save_options ) {
                $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">';
            }
            $output .= '<div class="instructional-text instagram-user-option-text" style="margin-top:12px;"><div class="fts-insta-info-plus-wrapper">' . esc_html( 'Choose a different ID if yours is not the first name below after clicking Convert Instagram Username button.', 'feed-them-social' ) . '</div><!-- the li list comes from an ajax call after looking up the user ID --><ul id="fts-instagram-username-picker-wrap" class="fts-instagram-username-picker-wrap"></ul></div>';
            $output .= '<div class="instructional-text instagram-hashtag-option-text" style="display:none;margin-top:12px;">' . esc_html( 'Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social' ) . '</div>';
            $output .= '<div class="feed-them-social-admin-input-wrap instagram_name">';
            $output .= '<div class="feed-them-social-admin-input-label instagram-user-option-text">' . esc_html( 'Instagram ID # (required)', 'feed-them-social' ) . '</div>';
            $output .= '<div class="feed-them-social-admin-input-label instagram-hashtag-option-text" style="display:none;">' . esc_html( 'Hashtag (required)', 'feed-them-social' ) . '</div>';
            $output .= '<input type="text" name="instagram_id" id="instagram_id" class="feed-them-social-admin-input" value="' . esc_html( $instagram_id_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            // Super Instagram Options!
            $pics_count_option = isset( $pics_count_option ) ? $pics_count_option : '';
            // Pic Count Option!
            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( '# of Pics (optional)', 'feed-them-premium' );
            if ( !is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                $output .= sprintf(
                    esc_html( '%1$s More than 6 Requires the %2$sPremium Extension%3$s', 'feed-them-social' ),
                    '<br/><small>',
                    '<a target="_blank" href="' . esc_url( 'http://www.slickremix.com/downloads/feed-them-social-premium-extension/' ) . '">',
                    '</a></small>'
                );
            }
            $output .= '</div>';
            $output .= '<input type="text" name="pics_count" id="pics_count" class="feed-them-social-admin-input" value="' . esc_html( $pics_count_option ) . '" placeholder="6 is the default number" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

            if ( isset( $_GET['page'] ) && 'feed-them-settings-page' === $_GET['page'] ) {

                $output .= '<div class="feed-them-social-admin-input-wrap">';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Super Instagram Gallery', 'feed-them-social' ) . '</div>';
                $output .= '<select id="instagram-custom-gallery" name="instagram-custom-gallery" class="feed-them-social-admin-input"><option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select>';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
                $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'Instagram Image Size', 'feed-them-social' ) . '<br/><small><a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . esc_html( 'View demo', 'feed-them-social' ) . '</a></small></div>
           <input type="text" name="fts-slicker-instagram-container-image-size" id="fts-slicker-instagram-container-image-size" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="fts-clear"></div> </div>';
                $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'Size of the Instagram Icon', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Visible when you hover over photo', 'feed-them-social' ) . '</small></div>
           <input type="text" name="fts-slicker-instagram-icon-center" id="fts-slicker-instagram-icon-center" class="feed-them-social-admin-input" value="65px" placeholder="">
           <div class="fts-clear"></div></div>';
                $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'The space between photos', 'feed-them-social' ) . '</div>
           <input type="text" name="fts-slicker-instagram-container-margin" id="fts-slicker-instagram-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
           <div class="fts-clear"></div></div>';
                $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'Hide Date, Likes and comments', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Good for image sizes under 120px', 'feed-them-social' ) . '</small></div>
       		 <select id="fts-slicker-instagram-container-hide-date-likes-comments" name="fts-slicker-instagram-container-hide-date-likes-comments" class="feed-them-social-admin-input">
        	  <option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select><div class="fts-clear"></div></div>';
                $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'Center Instagram Container', 'feed-them-social' ) . '</div>
        	<select id="fts-slicker-instagram-container-position" name="fts-slicker-instagram-container-position" class="feed-them-social-admin-input"><option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select>
           <div class="fts-clear"></div></div>';
                $output .= ' <div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . esc_html( 'Image Stacking Animation On', 'feed-them-social' ) . '<br/><small>' . esc_html( 'This happens when resizing browser', 'feed-them-social' ) . '</small></div>
        	 <select id="fts-slicker-instagram-container-animation" name="fts-slicker-instagram-container-animation" class="feed-them-social-admin-input"><option value="no">' . esc_html( 'No', 'feed-them-social' ) . '</option><option value="yes">' . esc_html( 'Yes', 'feed-them-social' ) . '</option></select><div class="fts-clear"></div></div>';

                // INSTAGRAM HEIGHT OPTION!
                $output .= '<div class="feed-them-social-admin-input-wrap instagram_fixed_height_option">';
                $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Instagram Fixed Height', 'feed-them-social' ) . '<br/><small>' . esc_html( 'Leave blank for auto height', 'feed-them-social' ) . '</small></div>';
                $output .= '<input type="text" name="instagram_page_height" id="instagram_page_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . esc_html( 'for example', 'feed-them-social' ) . '" />';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

                $output .= '</div><!--fts-super-instagram-options-wrap-->';

            }

            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {

                include $this->premium . 'admin/instagram-settings-fields.php';

            } else {
                // Create Need Premium Fields!
                $fields = array(
                    __( 'Display Photos & Videos in Popup', 'feed-them-social' ),
                    __( 'Load More Posts', 'feed-them-social' ),
                );
                $output .= $this->need_fts_premium_fields( $fields );
            }
            if ( $save_options ) {
                $output .= '<input type="submit" class="feed-them-social-admin-submit-btn instagram-submit" value="' . esc_html( 'Save Changes', 'feed-them-social' ) . '" />';
            } else {
                $output .= $this->generate_shortcode( 'updateTextArea_instagram();', 'Instagram Feed Shortcode', 'instagram-final-shortcode' );
                $output .= '</form>';
            }
            $output .= '</div> <!--/fts-instagram-shortcode-form-->';
        }
        return $output;
    }

    /**
     * FTS Youtube Form
     *
     * @param bool $save_options save options.
     * @since 1.9.6
     */
    public function fts_youtube_form ($save_options = false)
    {
        $fts_youtube_form_nonce = wp_create_nonce( 'fts-youtube-form-nonce' );

        if ( wp_verify_nonce( $fts_youtube_form_nonce, 'fts-youtube-form-nonce' ) ) {
            if ( $save_options ) {
                $youtube_name_option = get_option( 'youtube_name' );
                $youtube_vid_count_option = get_option( 'youtube_vid_count' );
                $youtube_columns_option = get_option( 'youtube_columns' );
                $youtube_first_video_option = get_option( 'youtube_first_video' );
            }
            $output = '<div class="fts-youtube-shortcode-form">';
            if ( false === $save_options ) {
                $output .= '<form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">';

                // Check to see if token is in place otherwise show a message letting person no what they need to do!
                $youtube_options = get_option( 'youtube_custom_api_token' ) || get_option( 'youtube_custom_access_token' ) && get_option( 'youtube_custom_refresh_token' ) && get_option( 'youtube_custom_token_exp_time' ) ? 'Yes' : 'No';
                $output .= isset( $youtube_options ) && 'No' !== $youtube_options ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a YouTube API Key to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">YouTube Options</a> page before trying to view your feed.</div>' . "\n";
                // end custom message for requiring token!
                $output .= '<h2>' . esc_html( 'YouTube Shortcode Generator', 'feed-them-social' ) . '</h2>';
            }
            $output .= '<div class="instructional-text">' . esc_html( 'You must copy your YouTube ', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">' . esc_html( 'Username, Channel ID and or Playlist ID', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it below.', 'feed-them-social' ) . '</div>';
            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                include $this->premium . 'admin/youtube-settings-fields.php';
            } else {
                // Create Need Premium Fields!
                $fields = array(
                    __( 'YouTube Name', 'feed-them-social' ),
                    __( '# of videos', 'feed-them-social' ),
                    __( '# of videos in each row', 'feed-them-social' ),
                    __( 'Display First video full size', 'feed-them-social' ),
                );
                $output .= $this->need_fts_premium_fields( $fields );
                $output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:inline-block; text-decoration:none !important;">' . esc_html( 'Click to see Premium Version', 'feed-them-social' ) . '</a>';
                $output .= '</form>';
            }
            $output .= '</div><!--/fts-youtube-shortcode-form-->';
        }
        return $output;
    }

    /**
     * FTS Pinterest Form
     *
     * @param bool $save_options save options.
     * @since 1.9.6
     */
    public function fts_pinterest_form ($save_options = false)
    {
        $fts_pinterest_form_nonce = wp_create_nonce( 'fts-pinterest-form-nonce' );

        if ( wp_verify_nonce( $fts_pinterest_form_nonce, 'fts-pinterest-form-nonce' ) ) {
            if ( $save_options ) {
                $pinterest_name_option = get_option( 'pinterest_name' );
                $boards_count_option = get_option( 'boards_count' );
            }
            $output = '<div class="fts-pinterest-shortcode-form">';
            if ( false === $save_options ) {
                $output = '<form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">';
            }
            // Pinterest FEED TYPE!
            $output .= '<h2>' . esc_html( 'Pinterest Shortcode Generator', 'feed-them-social' ) . '</h2><div class="feed-them-social-admin-input-wrap pinterest-gen-selection">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Feed Type', 'feed-them-social' ) . '</div>';
            $output .= '<select name="pinterest-messages-selector" id="pinterest-messages-selector" class="feed-them-social-admin-input">';
            $output .= '<option value="boards_list">' . esc_html( 'Board List', 'feed-them-social' ) . '</option>';
            $output .= '<option value="single_board_pins">' . esc_html( 'Pins From a Specific Board', 'feed-them-social' ) . '</option>';
            $output .= '<option value="pins_from_user">' . esc_html( 'Latest Pins from a User', 'feed-them-social' ) . '</option>';
            $output .= '</select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $output .= '<h3>' . esc_html( 'Pinterest Feed', 'feed-them-social' ) . '</h3><div class="instructional-text pinterest-name-text">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . esc_html( 'Pinterest Name', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste it in the first input below.', 'feed-them-social' ) . '</div>';
            $output .= '<div class="instructional-text pinterest-board-and-name-text" style="display:none;">' . esc_html( 'Copy your', 'feed-them-social' ) . ' <a href="http://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . esc_html( 'Pinterest and Board Name', 'feed-them-social' ) . '</a> ' . esc_html( 'and paste them below.', 'feed-them-social' ) . '</div>';
            $pinterest_name_option = isset( $pinterest_name_option ) ? $pinterest_name_option : '';
            $boards_count_option = isset( $boards_count_option ) ? $boards_count_option : '';
            $output .= '<div class="feed-them-social-admin-input-wrap pinterest_name">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Pinterest Username (required)', 'feed-them-social' ) . '</div>';
            $output .= '<input type="text" name="pinterest_name" id="pinterest_name" class="feed-them-social-admin-input" value="' . esc_html( $pinterest_name_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $output .= '<div class="feed-them-social-admin-input-wrap board-name" style="display:none;">';
            $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( 'Pinterest Board Name (required)', 'feed-them-premium' ) . '</div>';
            $output .= '<input type="text" name="pinterest_board_name" id="pinterest_board_name" class="feed-them-social-admin-input" value="' . esc_html( $pinterest_name_option ) . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            if ( is_plugin_active( 'feed-them-premium/feed-them-premium.php' ) ) {
                include $this->premium . 'admin/pinterest-settings-fields.php';
            } else {
                // Create Need Premium Fields!
                $fields = array(
                    esc_html( '# of Boards (default 6)', 'feed-them-social' ),
                    esc_html( '# of Pins (default 6)', 'feed-them-social' ),
                );
                $output .= $this->need_fts_premium_fields( $fields );
            }
            if ( $save_options ) {
                $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . esc_html( 'Save Changes', 'feed-them-social' ) . '" />';
            } else {
                $output .= $this->generate_shortcode( 'updateTextArea_pinterest();', '' . esc_html( 'Pinterest Feed Shortcode', 'feed-them-social' ) . '', 'pinterest-final-shortcode' );
                $output .= '</form>';
            }
            $output .= '</div><!--/fts-pinterest-shortcode-form-->';
        }
        return $output;
    }

    /**
     * Generate Shortcode
     *
     * Generate Shortcode Button and Input for FTS settings Page.
     *
     * @param string $onclick onclick.
     * @param string $label label.
     * @param string $input_class input class.
     * @since 1.9.6
     */
    public function generate_shortcode ($onclick, $label, $input_class)
    {
        $output = '<input type="button" class="feed-them-social-admin-submit-btn" value="' . esc_html( 'Generate Shortcode', 'feed-them-social' ) . '" onclick="' . esc_js( $onclick ) . '" tabindex="4" style="margin-right:1em;" />';
        $output .= '<div class="feed-them-social-admin-input-wrap final-shortcode-textarea">';
        $output .= '<h4>' . esc_html( 'Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social' ) . '</h4>';
        $output .= '<div class="feed-them-social-admin-input-label">' . esc_html( $label ) . '</div>';
        $output .= '<input class="copyme ' . esc_html( $input_class ) . ' feed-them-social-admin-input" value="" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        return $output;
    }

    /**
     * FTS Get Feed json
     *
     * Generate Get Json (includes MultiCurl).
     *
     * @param array $feeds_mulit_data feeds data info.
     * @return array
     * @since 1.9.6
     */
    public function fts_get_feed_json ($feeds_mulit_data)
    {
        // Make Multiple Requests from array with more than 2 keys!
        if ( is_array( $feeds_mulit_data ) && count( $feeds_mulit_data ) > 1 ) {
            $new_feeds_mulit_data = array();

            foreach ( $feeds_mulit_data as $key => $url ) {
                $new_feeds_mulit_data[$key]['url'] = $url;
                $new_feeds_mulit_data[$key]['type'] = 'GET';
            }
            // Fetch Multiple Requests!
            $responses = \Requests::request_multiple( $new_feeds_mulit_data );

            $data = array();
            foreach ( $responses as $key => $response ) {

                if ( is_a( $response, 'Requests_Response' ) ) {
                    $data[$key] = $response->body;
                }
            }
        } else {
            // Make Single Requests from array with 1 keys!
            if ( is_array( $feeds_mulit_data ) ) {
                foreach ( $feeds_mulit_data as $key => $url ) {

                    $single_response = \Requests::get( $url );

                    $data = array();
                    if ( is_a( $single_response, 'Requests_Response' ) ) {
                        $data[$key] = $single_response->body;
                    }
                }
            } else {
                // Make Single request from just url!
                $single_response_url = $feeds_mulit_data;

                if ( !empty( $single_response_url ) ) {
                    $single_response = \Requests::get( $single_response_url );

                    if ( is_a( $single_response, 'Requests_Response' ) ) {
                        $data['data'] = $single_response->body;
                    }
                }
            }
        }
        // Do nothing if Curl was Successful!
        return $data;
    }

    /**
     * FTS Create Feed Cache
     *
     * @param string $transient_name transient name.
     * @param array $response Data returned from response.
     * @since 1.9.6
     */
    public function fts_create_feed_cache ($transient_name, $response)
    {
        $cache_time_limit = true === get_option( 'fts_clear_cache_developer_mode' ) && '1' !== get_option( 'fts_clear_cache_developer_mode' ) ? get_option( 'fts_clear_cache_developer_mode' ) : '900';
        set_transient( 'fts_' . $transient_name, $response, $cache_time_limit );
    }

    /**
     * FTS Get Feed Cache
     *
     * @param string $transient_name transient name.
     * @return mixed
     * @since 1.9.6
     */
    public function fts_get_feed_cache ($transient_name)
    {
        $returned_cache_data = get_transient( 'fts_' . $transient_name );
        return $returned_cache_data;
    }

    /**
     * FTS Check Feed Cache Exists
     *
     * @param string $transient_name transient name.
     * @return bool
     * @since 1.9.6
     */
    public function fts_check_feed_cache_exists ($transient_name)
    {
        if ( false === get_transient( 'fts_' . $transient_name ) ) {
            return false;
        }
        return true;
    }

    /**
     * FTS Clear Cache Ajax
     *
     * @since 1.9.6
     */
    public function fts_clear_cache_ajax ()
    {
        global $wpdb;
        // Clear UnExpired!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%' ) );
        // Clear Exired Transients!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%' ) );
        wp_reset_query();
    }

    /**
     * Feed Them Clear Cache
     *
     * Clear Cache Folder.
     *
     * @return string
     * @since 1.9.6
     */
    public function feed_them_clear_cache ()
    {
        global $wpdb;
        // Clear UnExpired!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%' ) );
        // Clear Exired Transients!
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%' ) );
        wp_reset_query();
        return 'Cache for all FTS Feeds cleared!';
    }

    /**
     * FTS Admin Bar Menu
     *
     * Create our custom menu in the admin bar.
     *
     * @since 1.9.6
     */
    public function fts_admin_bar_menu ()
    {
        global $wp_admin_bar;

        $fts_admin_bar_menu = get_option( 'fts_admin_bar_menu' );
        $fts_dev_mode_cache = get_option( 'fts_clear_cache_developer_mode' );
        if ( !is_super_admin() || !is_admin_bar_showing() || 'hide-admin-bar-menu' === $fts_admin_bar_menu ) {
            return;
        }
        $wp_admin_bar->add_menu(
            array(
                'id' => 'feed_them_social_admin_bar',
                'title' => __( 'Feed Them Social', 'feed-them-social' ),
                'href' => false,
            )
        );
        if ( '1' === $fts_dev_mode_cache ) {
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'feed_them_social_admin_bar_clear_cache',
                    'parent' => 'feed_them_social_admin_bar',
                    'title' => __( 'Cache clears on page refresh now', 'feed-them-social' ),
                    'href' => false,
                )
            );
        } else {
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'feed_them_social_admin_set_cache',
                    'parent' => 'feed_them_social_admin_bar',
                    'title' => __( 'Clear Cache', 'feed-them-social' ),
                    'href' => '#',
                )
            );
        }
        $wp_admin_bar->add_menu(
            array(
                'id' => 'feed_them_social_admin_bar_set_cache',
                'parent' => 'feed_them_social_admin_bar',
                'title' => sprintf(
                    __( 'Set Cache Time %1$s%2$s%3$s', 'feed-them-social' ),
                    '<span>',
                    $this->fts_cachetime_amount( get_option( 'fts_clear_cache_developer_mode' ) ),
                    '</span>'
                ),
                'href' => admin_url( 'admin.php?page=feed-them-settings-page&tab=global_options' ),

            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id' => 'feed_them_social_admin_bar_settings',
                'parent' => 'feed_them_social_admin_bar',
                'title' => __( 'Settings', 'feed-them-social' ),
                'href' => admin_url( 'admin.php?page=feed-them-settings-page' ),
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'id' => 'feed_them_social_admin_bar_global_options',
                'parent' => 'feed_them_social_admin_bar',
                'title' => __( 'Global Options', 'feed-them-social' ),
                'href' => admin_url( 'admin.php?page=feed-them-settings-page&tab=global_options' ),
            )
        );
    }

    /**
     * FTS Cachetime amount
     *
     * @param string $fts_cachetime Cache time.
     * @return mixed
     * @since
     */
    public function fts_cachetime_amount ($fts_cachetime)
    {
        switch ($fts_cachetime) {
            case '1':
                $fts_display_cache_time = __( 'Clear cache on every page load', 'feed-them-social' );
                break;
            default:
            case '86400':
                $fts_display_cache_time = __( '1 Day (Default)', 'feed-them-social' );
                break;
            case '172800':
                $fts_display_cache_time = __( '2 Days', 'feed-them-social' );
                break;
            case '259200':
                $fts_display_cache_time = __( '3 Days', 'feed-them-social' );
                break;
            case '604800':
                $fts_display_cache_time = __( '1 Week', 'feed-them-social' );
                break;
            case '1209600':
                $fts_display_cache_time = __( '2 Weeks', 'feed-them-social' );
                break;
        }
        return $fts_display_cache_time;
    }

    /**
     * XML json Parse
     *
     * @param string $url string to parse the content for.
     * @return mixed
     * @since 1.9.6
     */
    public function xml_json_parse ($url)
    {
        $url_to_get['url'] = $url;
        $file_contents_returned = $this->fts_get_feed_json( $url_to_get );
        $file_contents = $file_contents_returned['url'];
        $file_contents = str_replace( array("\n", "\r", "\t"), '', $file_contents );
        $file_contents = trim( str_replace( '"', "'", $file_contents ) );
        $simple_xml = simplexml_load_string( $file_contents );
        $encoded_json = json_encode( $simple_xml );

        return $encoded_json;
    }

    /**
     * FTS Ago
     *
     * Create date format like fb and twitter. Thanks: http://php.quicoto.com/how-to-calculate-relative-time-like-facebook/ .
     *
     * @param string $timestamp Timestamp!
     * @return string
     * @since 1.9.6
     */
    function fts_ago ($timestamp)
    {
        // not setting isset'ing anything because you have to save the settings page to even enable this feature
        $fts_language_second = get_option( 'fts_language_second' );
        if ( empty( $fts_language_second ) ) {
            $fts_language_second = 'second';
        }
        $fts_language_seconds = get_option( 'fts_language_seconds' );
        if ( empty( $fts_language_seconds ) ) {
            $fts_language_seconds = 'seconds';
        }
        $fts_language_minute = get_option( 'fts_language_minute' );
        if ( empty( $fts_language_minute ) ) {
            $fts_language_minute = 'minute';
        }
        $fts_language_minutes = get_option( 'fts_language_minutes' );
        if ( empty( $fts_language_minute ) ) {
            $fts_language_minute = 'minutes';
        }
        $fts_language_hour = get_option( 'fts_language_hour' );
        if ( empty( $fts_language_hour ) ) {
            $fts_language_hour = 'hour';
        }
        $fts_language_hours = get_option( 'fts_language_hours' );
        if ( empty( $fts_language_hours ) ) {
            $fts_language_hours = 'hours';
        }
        $fts_language_day = get_option( 'fts_language_day' );
        if ( empty( $fts_language_day ) ) {
            $fts_language_day = 'day';
        }
        $fts_language_days = get_option( 'fts_language_days' );
        if ( empty( $fts_language_days ) ) {
            $fts_language_days = 'days';
        }
        $fts_language_week = get_option( 'fts_language_week' );
        if ( empty( $fts_language_week ) ) {
            $fts_language_week = 'week';
        }
        $fts_language_weeks = get_option( 'fts_language_weeks' );
        if ( empty( $fts_language_weeks ) ) {
            $fts_language_weeks = 'weeks';
        }
        $fts_language_month = get_option( 'fts_language_month' );
        if ( empty( $fts_language_month ) ) {
            $fts_language_month = 'month';
        }
        $fts_language_months = get_option( 'fts_language_months' );
        if ( empty( $fts_language_months ) ) {
            $fts_language_months = 'months';
        }
        $fts_language_year = get_option( 'fts_language_year' );
        if ( empty( $fts_language_year ) ) {
            $fts_language_year = 'year';
        }
        $fts_language_years = get_option( 'fts_language_years' );
        if ( empty( $fts_language_years ) ) {
            $fts_language_years = 'years';
        }
        $fts_language_ago = get_option( 'fts_language_ago' );
        if ( empty( $fts_language_ago ) ) {
            $fts_language_ago = 'ago';
        }

        // $periods = array( "sec", "min", "hour", "day", "week", "month", "years", "decade" );.
        $periods = array($fts_language_second, $fts_language_minute, $fts_language_hour, $fts_language_day, $fts_language_week, $fts_language_month, $fts_language_year, 'decade');
        $periods_plural = array($fts_language_seconds, $fts_language_minutes, $fts_language_hours, $fts_language_days, $fts_language_weeks, $fts_language_months, $fts_language_years, 'decades');

        if ( !is_numeric( $timestamp ) ) {
            $timestamp = strtotime( $timestamp );
            if ( !is_numeric( $timestamp ) ) {
                return '';
            }
        }
        $difference = time() - $timestamp;
        // Customize in your own language. Why thank-you I will.
        $lengths = array('60', '60', '24', '7', '4.35', '12', '10');

        if ( $difference > 0 ) {
            // this was in the past
            $ending = $fts_language_ago;
        } else {
            // this was in the future
            $difference = -$difference;
            // not doing dates in the future for posts
            $ending = 'to go';
        }
        for ( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths ) - 1; $j++ ) {
            $difference /= $lengths[$j];
        }

        $difference = round( $difference );

        if ( $difference > 1 ) {
            $periods[$j] = $periods_plural[$j];
        }

        return "$difference $periods[$j] $ending";
    }

    /**
     * FTS Custom Date
     *
     * @param string $created_time Created time.
     * @param string $feed_type Feed type.
     * @return string
     * @since 1.9.6
     */
    public function fts_custom_date ($created_time, $feed_type)
    {
        $fts_custom_date = get_option( 'fts-custom-date' );
        $fts_custom_time = get_option( 'fts-custom-time' );
        $custom_date_check = get_option( 'fts-date-and-time-format' );
        $fts_twitter_offset_time = get_option( 'fts_twitter_time_offset' );
        $fts_timezone = get_option( 'fts-timezone' );

        if ( '' === $fts_custom_date && '' === $fts_custom_time ) {
            $custom_date_check = $custom_date_check;
        } elseif ( '' !== $fts_custom_date || '' !== $fts_custom_time ) {
            $custom_date_check = $fts_custom_date . ' ' . $fts_custom_time;
        } else {
            $custom_date_check = 'F jS, Y \a\t g:ia';
        }
        if ( !empty( $fts_timezone ) ) {
            date_default_timezone_set( $fts_timezone );
        }
        // Twitter date time!
        if ( 'twitter' === $feed_type ) {

            $fts_twitter_offset_time_final = 1 === $fts_twitter_offset_time ? strtotime( $created_time ) : strtotime( $created_time ) - 3 * 3600;

            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, $fts_twitter_offset_time_final ) : $this->fts_ago( $created_time );
            }
        }
        // Instagram date time!
        if ( 'instagram' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, $created_time ) : $this->fts_ago( $created_time );
            }
        }
        // Youtube and Pinterest date time!
        if ( 'pinterest' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
            }
        }
        // WP Gallery and Pinterest date time!
        if ( 'wp_gallery' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
            }
        }
        // Facebook date time!
        if ( 'facebook' === $feed_type ) {
            $time_set = $fts_timezone;
            $time_set_check = isset( $time_set ) ? $time_set : 'America/New_York';
            date_default_timezone_set( $time_set_check );

            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, $created_time ) : $this->fts_ago( $created_time );
            }
        }
        // Instagram date time!
        if ( 'youtube' === $feed_type ) {
            if ( 'one-day-ago' === $custom_date_check ) {
                $u_time = $this->fts_ago( $created_time );
            } else {
                $u_time = !empty( $custom_date_check ) ? date_i18n( $custom_date_check, strtotime( $created_time ) ) : $this->fts_ago( $created_time );
            }
        }
        // Return the time!
        return $u_time;
    }

    /**
     * Random String generator For All Feeds
     *
     * @param int $length Random string length.
     * @return string
     * @since 2.0.7
     */
    public function feed_them_social_rand_string ($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen( $characters );
        $random_string = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $random_string .= $characters[wp_rand( 0, $characters_length - 1 )];
        }
        return $random_string;
    }


    /**
     * FTS Refresh YouTube Token
     *
     * @since 2.3.3
     */
    public function fts_refresh_token_ajax ()
    {

        $fts_refresh_token_nonce = wp_create_nonce( 'fts_refresh_token_nonce' );

        if ( wp_verify_nonce( $fts_refresh_token_nonce, 'fts_refresh_token_nonce' ) ) {
            if ( isset( $_REQUEST['refresh_token'], $_REQUEST['button_pushed'] ) && 'yes' === $_REQUEST['button_pushed'] ) {
                update_option( 'youtube_custom_refresh_token', sanitize_text_field( wp_unslash( $_REQUEST['refresh_token'] ) ) );
            }
            if ( isset( $_REQUEST['access_token'] ) ) {
                update_option( 'youtube_custom_access_token', sanitize_text_field( wp_unslash( $_REQUEST['access_token'] ) ) );
            }
            $startoftime = isset( $_REQUEST['expires_in'] ) ? strtotime( '+' . sanitize_text_field( wp_unslash( $_REQUEST['expires_in'] ) ) . ' seconds' ) : '';
            $start_of_time_final = false !== $startoftime ? sanitize_key( $startoftime ) : '';
            update_option( 'youtube_custom_token_exp_time', $start_of_time_final );

            // This only happens if the token is expired on the YouTube Options page and you go to re-save or refresh the page for some reason. It will also run this function if the cache is emptied and the token is found to be expired.
            if ( 'no' === $_REQUEST['button_pushed'] ) {
                return 'Token Refreshed';
                // $output .= do_shortcode('[fts _youtube vid_count=3 large_vid=no large_vid_title=no large_vid_description=no thumbs_play_in_iframe=popup vids_in_row=3 space_between_videos=1px force_columns=yes maxres_thumbnail_images=yes thumbs_wrap_color=#000 wrap=none video_wrap_display=none comments_count=12 channel_id=UCqhnX4jA0A5paNd1v-zEysw loadmore=button loadmore_count=5 loadmore_btn_maxwidth=300px loadmore_btn_margin=10px]');
            }
        }

    }

    /**
     * FTS Check YouTube Token Validity
     *
     * @since 2.3.3
     */
    public function feed_them_youtube_refresh_token ()
    {

        $fts_refresh_token_nonce = wp_create_nonce( 'fts_refresh_token_nonce' );

        if ( wp_verify_nonce( $fts_refresh_token_nonce, 'fts_refresh_token_nonce' ) ) {

            // Used some methods from this link http://ieg.wnet.org/2015/09/using-oauth-in-wordpress-plugins-part-2-persistence/
            // save all 3 get options: happens when clicking the get access token button on the youtube options page!
            if ( isset( $_GET['refresh_token'], $_GET['access_token'] ) && isset( $_GET['expires_in'] ) ) {
                $button_pushed = 'yes';
                $clienttoken_post['refresh_token'] = sanitize_text_field( wp_unslash( $_GET['refresh_token'] ) );
                $auth_obj['access_token'] = sanitize_text_field( wp_unslash( $_GET['access_token'] ) );
                $auth_obj['expires_in'] = sanitize_key( wp_unslash( $_GET['expires_in'] ) );
            } else {
                // refresh token!
                $button_pushed = 'no';
                $oauth2token_url = 'https://accounts.google.com/o/oauth2/token';
                $clienttoken_post = array(
                    'client_id' => '802796800957-6nannpdq8h8l720ls430ahnnq063n22u.apps.googleusercontent.com',
                    'client_secret' => 'CbieVhgOudjrpya1IDpv3uRa',
                );
                // The "refresh token" grant type is to use a refresh token to get a new access token!
                $clienttoken_post['refresh_token'] = get_option( 'youtube_custom_refresh_token' );
                $clienttoken_post['grant_type'] = 'refresh_token';

                $postargs = array(
                    'body' => $clienttoken_post,
                );
                $response = wp_remote_post( $oauth2token_url, $postargs );
                $auth_obj = json_decode( wp_remote_retrieve_body( $response ), true );
            }
            ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery.ajax({
                        data: {
                            action: "fts_refresh_token_ajax",
                            refresh_token: '<?php echo esc_js( $clienttoken_post['refresh_token'] ); ?>',
                            access_token: '<?php echo esc_js( $auth_obj['access_token'] ); ?>',
                            expires_in: '<?php echo esc_js( $auth_obj['expires_in'] ); ?>',
                            button_pushed: '<?php echo esc_js( $button_pushed ); ?>'
                        },
                        type: 'POST',
                        url: ftsAjax.ajaxurl,
                        success: function (response) {
                            console.log(response);
                            <?php
                            if ( isset( $_GET['page'] ) && 'fts-youtube-feed-styles-submenu-page' === $_GET['page'] ) {
                            foreach ( $auth_obj as $user_id ) {
                                if ( !isset( $user_id->error->errors[0]->reason ) ) {
                                    $type_of_key = __( 'API key', 'feed-them-social' );
                                } elseif ( !isset( $user_id->error->errors[0]->reason ) && !empty( $youtube_access_token ) ) {
                                    $type_of_key = __( 'Access Token', 'feed-them-social' );
                                }

                                // Error Check!
                                if ( !isset( $auth_obj->error->errors[0]->reason ) ) {
                                    $fts_youtube_message = sprintf(
                                        esc_html( '%1$s Your %2$s is working! Generate your shortcode on the %3$s settings page.%4$s %5$s', 'feed-them-social' ),
                                        '<div class="fts-successful-api-token">',
                                        esc_html( $type_of_key ),
                                        '<a href="' . esc_url( 'admin.php?page=feed-them-settings-page' ) . '">',
                                        '</a>',
                                        '</div><div class="clear"></div>'
                                    );
                                } elseif ( isset( $user_id->error->errors[0]->reason ) ) {
                                    $fts_youtube_message = sprintf(
                                        esc_html( '%1$s This %2$s does not appear to be valid. YouTube responded with: %3$s %4$s ', 'feed-them-social' ),
                                        '<div class="fts-failed-api-token">',
                                        esc_html( $type_of_key ),
                                        esc_html( $user_id->errors[0]->reason ),
                                        '</div><div class="clear"></div>'
                                    );
                                }

                                break;
                            }

                            ?>
                            jQuery('#youtube_custom_access_token, #youtube_custom_token_exp_time').val('');

                            <?php if ( isset( $_GET['refresh_token'], $_GET['access_token'] ) && isset( $_GET['expires_in'] ) ) { ?>
                            jQuery('#youtube_custom_refresh_token').val(jQuery('#youtube_custom_refresh_token').val() + '<?php echo esc_js( $clienttoken_post['refresh_token'] ); ?>');
                            jQuery('.fts-failed-api-token').hide();

                            if (!jQuery('.fts-successful-api-token').length) {
                                jQuery('.fts-youtube-last-row').append('<?php echo $fts_youtube_message; ?>');
                            }
                            <?php
                            } else {
                            ?>
                            if (jQuery('.fts-failed-api-token').length) {
                                jQuery('.fts-youtube-last-row').append('<?php echo $fts_youtube_message; ?>');
                                jQuery('.fts-failed-api-token').hide();
                            }
                            <?php } ?>

                            jQuery('#youtube_custom_access_token').val(jQuery('#youtube_custom_access_token').val() + '<?php echo esc_js( $auth_obj['access_token'] ); ?>');
                            jQuery('#youtube_custom_token_exp_time').val(jQuery('#youtube_custom_token_exp_time').val() + '<?php echo esc_js( strtotime( '+' . $auth_obj['expires_in'] . ' seconds' ) ); ?>');
                            jQuery('<div class="fa fa-check-circle fa-3x fa-fw fts-success"></div>').insertBefore('.hide-button-tokens-options .feed-them-social-admin-input-wrap .fts-clear');
                            jQuery('.fts-success').fadeIn('slow');
                            <?php } ?>
                            return false;
                        }
                    }); // end of ajax()
                    return false;
                }); // end of document.ready
            </script>
            <?php
            return $auth_obj['access_token'];
        }
    }


    /**
     * FTS YouTube Link Filter
     *
     * @param string $youtube_description youtube description.
     * @return string
     * @since 1.9.6
     */
    public function fts_youtube_link_filter ($youtube_description)
    {
        // Converts URLs to Links!
        $youtube_description = preg_replace( '@(?!(?!.*?<a)[^<]*<\/a>)(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-‌​Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0" target="_blank">\0</a>', $youtube_description );

        $splitano = explode( 'www', $youtube_description );
        $count = count( $splitano );
        $return_value = '';

        for ( $i = 0; $i < $count; $i++ ) {
            if ( 'href=' === substr( $splitano[$i], -6, 5 ) ) {
                $return_value .= $splitano[$i] . 'http://www';
            } elseif ( $i < $count - 1 ) {
                $return_value .= $splitano[$i] . 'www';
            } else {
                $return_value .= $splitano[$i];
            }
        }
        return $return_value;
    }

    /**
     * FTS Youtube Video and Wrap
     *
     * @param object $post_data post data.
     * @param string $username username.
     * @param string $playlist_id playlist id.
     * @since 1.9.6
     */
    public function fts_youtube_video_and_wrap ($post_data, $username, $playlist_id)
    {
        $ssl = is_ssl() ? 'https' : 'http';
        $youtube_video_user_or_playlist_url = isset( $post_data->snippet->resourceId->videoId ) ? $post_data->snippet->resourceId->videoId : '';
        $youtube_video_channel_url = isset( $post_data->id->videoId ) ? $post_data->id->videoId : '';

        if ( '' !== $username || '' !== $playlist_id ) {
            $youtube_video_iframe = '<div class="fts-fluid-videoWrapper"><iframe src="' . esc_url( $ssl . '://www.youtube.com/embed/' . $youtube_video_user_or_playlist_url ) . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';

        } else {
            $youtube_video_iframe = '<div class="fts-fluid-videoWrapper"><iframe src="' . esc_url( $ssl . '://www.youtube.com/embed/' . $youtube_video_channel_url ) . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        }
        return $youtube_video_iframe;
    }


    /**
     * Youtube Description
     *
     * @param object $post_data post data.
     * @return string
     * @since 1.9.6
     */
    public function fts_youtube_description ($post_data)
    {

        $pinterest_description = isset( $post_data->snippet->description ) ? $post_data->snippet->description : '';
        return $pinterest_description;
    }

    /**
     * Youtube Title
     *
     * @param object $post_data post data.
     * @return string
     * @since 1.9.6
     */
    public function fts_youtube_title ($post_data)
    {
        $youtube_post_title = isset( $post_data->snippet->title ) ? $post_data->snippet->title : '';
        return $youtube_post_title;
    }

    /**
     * FTS Facebook Group Form
     *
     * @param bool $save_options Save Options.
     * @since 1.9.6
     */
    public function fts_facebook_group_form ($save_options = false)
    {
        // DEPRECIATED!
    }

    public function fts_facebook_event_form ($save_options = false)
    {
        // DEPRECIATED!
    }

} // end class
?>