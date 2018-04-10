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
     * @var string
     */
    public $output = "";

    /**
     * Construct
     *
     * Functions constructor.
     *
     * @since 1.9.6
     */
    function __construct() {
        $root_file = plugin_dir_path(dirname(__FILE__));
        $this->premium = str_replace('feed-them-social/', 'feed-them-premium/', $root_file);
        $this->facebook_carousel_premium = str_replace('feed-them-social/', 'feed-them-carousel-premium/', $root_file);
        $this->facebook_reviews = str_replace('feed-them-social/', 'feed-them-social-facebook-reviews/', $root_file);

        //FTS Activation Function. Commenting out for future use. SRL
        // register_activation_hook( __FILE__ , array( $this, 'fts_plugin_activation'));

        //$load_fts->fts_get_check_plugin_version('feed-them-premium.php', '1.3.0');
        register_deactivation_hook(__FILE__, array($this, 'fts_get_check_plugin_version'));
        // Widget Code
        add_filter('widget_text', 'do_shortcode');
        // This is for the fts_clear_cache_ajax submission
        if(get_option('fts_admin_bar_menu') == 'show-admin-bar-menu'){
            add_action('init', array($this, 'fts_clear_cache_script'));
            add_action('wp_head', array($this, 'my_fts_ajaxurl'));
            add_action('wp_ajax_fts_clear_cache_ajax', array($this, 'fts_clear_cache_ajax'));
        }

        add_action('wp_ajax_fts_refresh_token_ajax', array($this, 'fts_refresh_token_ajax'));

        // If Premium is actuive
        // is_admin for the loadmore on fb options page
        if (is_admin() || is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || is_plugin_active('fts-bar/fts-bar.php')) {
            // Load More Options
            //	add_action( 'init', array($this, 'my_fts_fb_script_enqueuer'));
            add_action('wp_ajax_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more'));
            add_action('wp_ajax_nopriv_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more'));
            add_action('wp_ajax_my_fts_fb_options_page_load_more', array($this, 'my_fts_fb_options_page_load_more'));

        }//END if premium

        add_shortcode('fts_fb_page_token', array($this, 'fts_fb_page_token_func'));
    }



     function fts_ajax_check() {

     }

    /**
     * Init
     *
     * For Loading in the Admin.
     *
     * @since 1.9.6
     */
    function init() {
        if (is_admin()) {
            // Register Settings
            add_action('admin_init', array($this, 'fts_settings_page_register_settings'));
            add_action('admin_init', array($this, 'fts_facebook_style_options_page'));
            add_action('admin_init', array($this, 'fts_twitter_style_options_page'));
            add_action('admin_init', array($this, 'fts_instagram_style_options_page'));
            add_action('admin_init', array($this, 'fts_pinterest_style_options_page'));
            add_action('admin_init', array($this, 'fts_youtube_style_options_page'));

            // Adds setting page to FTS menu
            add_action('admin_menu', array($this, 'Feed_Them_Main_Menu'));
            add_action('admin_menu', array($this, 'Feed_Them_Submenu_Pages'));
            // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
            add_action('admin_enqueue_scripts', array($this, 'feed_them_admin_css'));
            //Main Settings Page
            if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page' or isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-instagram-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-pinterest-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-youtube-feed-styles-submenu-page') {
                add_action('admin_enqueue_scripts', array($this, 'feed_them_settings'));
            }
            //System Info Page
            if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page') {
                add_action('admin_enqueue_scripts', array($this, 'feed_them_system_info_css'));
            }
            //FTS License Page
            if (isset($_GET['page']) && $_GET['page'] == 'fts-license-page') {
                add_action('admin_footer', array($this, 'fts_plugin_license'));
            }
        }//end if admin
        //FTS Admin Bar
        add_action('wp_before_admin_bar_render', array($this, 'fts_admin_bar_menu'), 999);
        //Settings option. Add Custom CSS to the header of FTS pages only
        $fts_include_custom_css_checked_css = get_option('fts-color-options-settings-custom-css');
        if ($fts_include_custom_css_checked_css == '1') {
            add_action('wp_enqueue_scripts', array($this, 'fts_color_options_head_css'));
        }
        //Facebook Settings option. Add Custom CSS to the header of FTS pages only
        $fts_include_fb_custom_css_checked_css = '1'; //get_option( 'fts-color-options-settings-custom-css' );
        if ($fts_include_fb_custom_css_checked_css == '1') {
            add_action('wp_enqueue_scripts', array($this, 'fts_fb_color_options_head_css'));
        }
        //Settings option. Custom Powered by Feed Them Social Option
        $fts_powered_text_options_settings = get_option('fts-powered-text-options-settings');
        if ($fts_powered_text_options_settings != '1') {
            add_action('wp_enqueue_scripts', array($this, 'fts_powered_by_js'));
        }

        if (is_plugin_active('jetpack/jetpack.php')) {
            add_filter('jetpack_photon_skip_image', array($this, 'fts_jetpack_photon_exception'), 10, 3);
        }

    }

    function fts_jetpack_photon_exception($val, $src, $tag) {
        if (strpos($src, 'fbcdn.net')) {
            return true;
        }
        return $val;
    }



    function fts_share_option($FBlink, $description) {
        //Social media sharing URLs
        $link = $FBlink;
        $description = strip_tags($description);
        // $media = strip_tags($media);
        // $image_description = $this->ft_gallery_trim_words(isset($description) ? $description : '', $words, $more);

        $ft_gallery_share_linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link;
        $ft_gallery_share_email = 'mailto:?subject=Shared Link&body=' . $link . ' - ' . $description;
        $ft_gallery_share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $link;
        $ft_gallery_share_twitter = 'https://twitter.com/intent/tweet?text=' . $link .'+'. $description;
        //  $ft_gallery_share_pinterest = 'http://pinterest.com/pin/create/bookmarklet/?media='.$media.'&url='.$link.'&is_video=false&description='.$description;
        $ft_gallery_share_google = 'https://plus.google.com/share?url=' . $link;

        $hide_share = get_option('fts_disable_share_button') ? get_option('fts_disable_share_button') : '';

        if (isset($hide_share) && $hide_share !== '1') {
            $output = '<div class="fts-share-wrap">';

            $output .= '<a href="javascript:;" class="ft-gallery-link-popup">'. __("", "feed-them-gallery") .'</a>';
            // this part is hidden until the user clicks the share link/icon
            $output .= '<div class="ft-gallery-share-wrap">';
            $output .= '<a href="'.$ft_gallery_share_facebook.'" target="_blank" class="ft-galleryfacebook-icon"><i class="fa fa-facebook-square"></i></a>';
            $output .= '<a href="'.$ft_gallery_share_twitter.'" target="_blank" class="ft-gallerytwitter-icon"><i class="fa fa-twitter"></i></a>';
            //   $output .= '<a href="'.$ft_gallery_share_pinterest.'" target="_blank" class="ft-gallerypinterest-icon"><i class="fa fa-pinterest-plus"></i></a>';
            $output .= '<a href="'.$ft_gallery_share_google.'" target="_blank" class="ft-gallerygoogle-icon"><i class="fa fa-google-plus"></i></a>';
            $output .= '<a href="'.$ft_gallery_share_linkedin.'" target="_blank" class="ft-gallerylinkedin-icon"><i class="fa fa-linkedin"></i></a>';
            $output .= '<a href="'.$ft_gallery_share_email.'" target="_blank" class="ft-galleryemail-icon"><i class="fa fa-envelope"></i></a>';
            $output .= '</div>';

            $output .= ' </div>';

            return $output;
        }

    }


    /**
     * FTS FB Options Page Function
     *
     * Display FB Page tokens for users
     *
     * @param $atts
     * @return mixed
     * @since 2.1.4
     */
    function fts_fb_page_token_func() {

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            $_REQUEST['fts_dynamic_name'] = trim($this->feed_them_social_rand_string());
        }

        ob_start();

        $fb_token_response = isset($_REQUEST['next_url']) ? wp_remote_fopen($_REQUEST['next_url']) : wp_remote_fopen('https://graph.facebook.com/me/accounts?access_token=' . $_GET['access_token'] . '&limit=25');
        $test_fb_app_token_response = json_decode($fb_token_response);

        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing'])) {
            //******************
            //Load More BUTTON Start
            //******************
            ?>
            <div class="fts-clear"></div>
            <div id="reviews-fb-list-wrap"></div>
        <?php }

        $build_shortcode = 'fts_fb_page_token';
        $_REQUEST['next_url'] = isset($test_fb_app_token_response->paging->next) ? $test_fb_app_token_response->paging->next : '';
         //  echo'<pre>';
         //  print_r($test_fb_app_token_response);
         // echo'</pre>';

        //Make sure it's not ajaxing
    if (!isset($_GET['load_more_ajaxing'])) {

        $reviews_token = isset($_GET['reviews_token']) ? 'yes' : 'no';

        ?>
        <div id="fb-list-wrap">
            <div class="fts-pages-info"> <?php _e('Click a page to add the access token above, then click save.', 'feed-them-social'); ?></div>
            <ul class="fb-page-list">
                <?php }

                foreach ($test_fb_app_token_response->data as $data) { ?>
                    <li>
                        <div class="fb-image">
                            <div class="fts-fb-id"><?php print $data->id ?></div>
                            <img border="0" height="50" width="50" src="https://graph.facebook.com/<?php print $data->id ?>/picture"/>
                        </div>
                        <div class="fb-name"><?php print $data->name ?></div>
                        <div class="page-token"><?php print $data->access_token ?></div>

                        <?php
                        $facebook_input_token = get_option('fts_facebook_custom_api_token');
                        $facebook_access_token = $data->access_token;
                        if ($facebook_input_token == $facebook_access_token) {
                            ?>
                            <div class="feed-them-social-admin-submit-btn " style="display: block !important;">Active
                            </div>
                        <?php } else { ?>
                            <div class="feed-them-social-admin-submit-btn fts-token-save">Save</div>
                        <?php } ?>
                        <div class="fts-clear"></div>
                    </li>
                <?php }

                if (!isset($_GET['load_more_ajaxing'])) { ?>

            </ul>
            <div class="fts-clear"></div>

        </div>

    <?php }
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts'])) {
            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
            $time = time();
            $nonce = wp_create_nonce($time . "load-more-nonce");
            ?>
            <script>
                jQuery(document).ready(function () {

                    jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").click(function () {

                        jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").addClass('fts-fb-spinner');
                        var button = jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
                        console.log(button);
                        var build_shortcode = "<?php print $build_shortcode;?>";
                        var yes_ajax = "yes";
                        var fts_d_name = "<?php echo $fts_dynamic_name;?>";
                        var fts_security = "<?php echo $nonce;?>";
                        var fts_time = "<?php echo $time;?>";
                        var fts_reviews_feed = "<?php print $reviews_token;?>";
                        jQuery.ajax({
                            data: {
                                action: "my_fts_fb_load_more",
                                next_url: nextURL_<?php echo $fts_dynamic_name ?>,
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
                                jQuery('.fb-page-list').append(data).filter('.fb-page-list').html();

                                if (!nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> || nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more') {
                                    jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php _e('No More Pages', 'feed-them-social') ?></div>');
                                    jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
                                }
                                jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<?php _e('Load More', 'feed-them-social') ?>');
                                //	jQuery('#loadMore_< ?php echo $fts_dynamic_name ?>').removeClass('flip360-fts-load-more');
                                jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").removeClass('fts-fb-spinner');

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
            var nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?>= "<?php echo $_REQUEST['next_url']; ?>";

            if (document.querySelector('#fts-fb-token-wrap .fts-pages-info') !== null) {
                jQuery(".fts-successful-api-token.default-token").hide();
            }
            <?php if ($reviews_token == 'yes' || isset($_GET['fts_reviews_feed']) && $_GET['fts_reviews_feed'] == 'yes'){?>
            if (document.querySelector('.default-token') !== null) {
                jQuery(".default-token").show();
            }

            <?php } ?>

            jQuery(document).ready(function ($) {
                $(".feed-them-social-admin-submit-btn").click(function () {
                    // alert('test');
                    var newUrl = "<?php echo admin_url('admin.php?page=fts-facebook-feed-styles-submenu-page/'); ?>";
                    history.replaceState({}, null, newUrl);
                    $("#fts-facebook-feed-options-form").submit();
                });

                <?php if ($reviews_token == 'no' || isset($_GET['fts_reviews_feed']) && $_GET['fts_reviews_feed'] == 'no'){?>

                var fb = ".fb-page-list li";
                $('#fb-list-wrap').show();
                //alert("not set");
                <?php } else { ?>
                var fb = "#reviews-fb-list-wrap .fb-page-list li";
                $('#fb-list-wrap').appendTo('#reviews-fb-list-wrap');
                $('#fts-fb-reviews-wrap #fb-list-wrap').show();
                $('.fts-failed-api-token.get-started-message').hide();
                //alert("reviews_token");
                <?php } ?>

                $(fb).click(function () {
                    var fb_page_id = $(this).find('.fts-fb-id').html();
                    var token = $(this).find('.page-token').html();
                    // alert(token);
                    var name = $(this).find('.fb-name').html();
                    <?php if ($reviews_token == 'no' || isset($_GET['fts_reviews_feed']) && $_GET['fts_reviews_feed'] == 'no'){?>

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
        //Make sure it's not ajaxing
        if (!isset($_GET['load_more_ajaxing']) && isset($test_fb_app_token_response->paging->next)) {
            $fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
            // this div returns outputs our ajax request via jquery append html from above

            print '<div class="fts-clear"></div>';
            print '<div id="output_' . $fts_dynamic_name . '" class="fts-hide"></div>';

            print '<div class="fts-clear"></div>';

            //  print '<div class="fts-fb-load-more-wrapper">';
            print '<div id="loadMore_' . $fts_dynamic_name . '" class="fts-fb-load-more">' . __('Load More', 'feed-them-instagram') . '</div>';
            //  print '</div>';

        }//End Check
        unset($_REQUEST['next_url']);

        return ob_get_clean();
    }


    /**
     * My FTS Plugin License
     *
     * Put in place to only show the Activate Plugin license if the input has a value
     *
     * @since 2.1.4
     */
    function fts_plugin_license() {
        wp_enqueue_script('jquery'); ?>
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
    function my_fts_ajaxurl() {
        wp_enqueue_script('jquery');
       // <script type="text/javascript">
       //     var myAjaxFTS = '<?php echo admin_url('admin-ajax.php'); ';
       // </script>
    }

    /**
     * My FTS FB Load More
     *
     * This function is being called from the fb feed... it calls the ajax in this case.
     *
     * @since 1.9.6
     * @updated 2.1.4 (fts_fb_page_token)
     */
    function my_fts_fb_load_more() {
        if (!wp_verify_nonce($_REQUEST['fts_security'], $_REQUEST['fts_time'] . 'load-more-nonce')) {
            exit('Sorry, You can\'t do that!');
        } else {

         if (
             $_REQUEST['feed_name'] == 'fts_fb_page_token' ||
             $_REQUEST['feed_name'] == 'fts_fb_page_token' ||
             $_REQUEST['feed_name'] == 'fts_twitter'  ||
             $_REQUEST['feed_name'] == 'fts_youtube'  ||
             $_REQUEST['feed_name'] == 'fts_facebook' ||
             $_REQUEST['feed_name'] == 'fts_facebookbiz' ||
             $_REQUEST['feed_name'] == 'fts_instagram') {

                $feed_atts = $_REQUEST['feed_attributes'];

                // error_log('feed atts:' .var_dump($feed_atts, true));

                $build_shortcode = '['.$_REQUEST['feed_name'].'';
                foreach ($feed_atts as $attribute => $value) {
                    $build_shortcode .= ' ' . $attribute. '=' . $value;
                }

                if($_REQUEST['feed_name'] == 'fts_twitter'){
                    $loadmore_count = $_REQUEST['loadmore_count'] ? $_REQUEST['loadmore_count'] : '' ;
                    $build_shortcode .= ' '.$loadmore_count.']';
                }
                 elseif($_REQUEST['feed_name'] == 'fts_youtube'){
                     $loadmore_count = $_REQUEST['loadmore_count'] ? $_REQUEST['loadmore_count'] : '' ;
                     $build_shortcode .= ' '.$loadmore_count.']';
                 }
                else {
                    $build_shortcode .= ' ]';
                }

             // $object = $build_shortcode;
               $object = do_shortcode($build_shortcode);
                echo $object;
            } else {
                exit('That is not an FTS shortcode!');
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
    function fts_clear_cache_script() {

        isset($ftsDevModeCache) ? $ftsDevModeCache : "";
        isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
        $ftsAdminActivationClearCache = get_option('Feed_Them_Social_Activated_Plugin');
        $ftsAdminBarMenu = get_option('fts_admin_bar_menu');
        $ftsDevModeCache = get_option('fts_clear_cache_developer_mode');
        if ($ftsDevModeCache == '1' || $ftsAdminActivationClearCache == 'feed-them-social') {
            wp_enqueue_script('fts_clear_cache_script', plugins_url('feed-them-social/admin/js/developer-admin.js'), array('jquery'));
            wp_localize_script('fts_clear_cache_script', 'ftsAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('jquery');
            wp_enqueue_script('fts_clear_cache_script');
        }
        if ($ftsDevModeCache !== 'hide-admin-bar-menu' && $ftsDevModeCache !== '1') {
            wp_enqueue_script('jquery');
            wp_enqueue_script('fts_clear_cache_script', plugins_url('feed-them-social/admin/js/admin.js'));
            wp_enqueue_script('fts_clear_cache_script', plugins_url('feed-them-social/admin/js/developer-admin.js'), array('jquery'));
            wp_localize_script('fts_clear_cache_script', 'ftsAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('fts_clear_cache_script');
        }

        // we delete this option if found so we only empty the cache once when the plugin is ever activated or updated
        delete_option( 'Feed_Them_Social_Activated_Plugin' );
    }

    /**
     * Feed Them Main Menu
     *
     * Admin Submenu buttons // Add the word Settings in place of the default menu page name 'Feed Them'.
     *
     * @since 1.9.6
     */
    function Feed_Them_Main_Menu() {
        //Main Settings Page
        $main_settings_page = new FTS_settings_page();
        add_menu_page('Feed Them Social', 'Feed Them', 'manage_options', 'feed-them-settings-page', array($main_settings_page, 'feed_them_settings_page'), '');
        add_submenu_page('feed-them-settings-page', __('Settings', 'feed-them-social'), __('Settings', 'feed-them-social'), 'manage_options', 'feed-them-settings-page');
    }

    /**
     * Feed Them Submenu Pages
     *
     * @since 1.9.6
     */
    function Feed_Them_Submenu_Pages() {

        //Facebook Options Page
        $facebook_options_page = new FTS_facebook_options_page();
        add_submenu_page(
            'feed-them-settings-page',
            __('Facebook Options', 'feed-them-social'),
            __('Facebook Options', 'feed-them-social'),
            'manage_options',
            'fts-facebook-feed-styles-submenu-page',
            array($facebook_options_page, 'feed_them_facebook_options_page')
        );
        //Twitter Options Page
        $twitter_options_page = new FTS_twitter_options_page();
        add_submenu_page(
            'feed-them-settings-page',
            __('Twitter Options', 'feed-them-social'),
            __('Twitter Options', 'feed-them-social'),
            'manage_options',
            'fts-twitter-feed-styles-submenu-page',
            array($twitter_options_page, 'feed_them_twitter_options_page')
        );
        //Pinterest Options Page
        $pinterest_options_page = new FTS_pinterest_options_page();
        add_submenu_page(
            'feed-them-settings-page',
            __('Pinterest Options', 'feed-them-social'),
            __('Pinterest Options', 'feed-them-social'),
            'manage_options',
            'fts-pinterest-feed-styles-submenu-page',
            array($pinterest_options_page, 'feed_them_pinterest_options_page')
        );
            //Youtube Options Page
            $youtube_options_page = new FTS_youtube_options_page();
            add_submenu_page(
                'feed-them-settings-page',
                __('YouTube Options', 'feed-them-social'),
                __('YouTube Options', 'feed-them-social'),
                'manage_options',
                'fts-youtube-feed-styles-submenu-page',
                array($youtube_options_page, 'feed_them_youtube_options_page')
            );

        //Instagram Options Page
        $instagram_options_page = new FTS_instagram_options_page();
        add_submenu_page(
            'feed-them-settings-page',
            __('Instagram Options', 'feed-them-social'),
            __('Instagram Options', 'feed-them-social'),
            'manage_options',
            'fts-instagram-feed-styles-submenu-page',
            array($instagram_options_page, 'feed_them_instagram_options_page')
        );
        //System Info
        $system_info_page = new FTS_system_info_page();
        add_submenu_page(
            'feed-them-settings-page',
            __('System Info', 'feed-them-social'),
            __('System Info', 'feed-them-social'),
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
    function feed_them_admin_css() {
        wp_register_style('feed_them_admin', plugins_url('admin/css/admin.css', dirname(__FILE__)));
        wp_enqueue_style('feed_them_admin');
    }

    /**
     * Feed Them System Info CSS
     *
     * Admin System Info CSS.
     *
     * @since 1.9.6
     */
    function feed_them_system_info_css() {
        wp_register_style('fts-settings-admin-css', plugins_url('admin/css/admin-settings.css', dirname(__FILE__)));
        wp_enqueue_style('fts-settings-admin-css');
    }

    /**
     * Feed Them Settings
     *
     * Admin Settings Scripts and CSS.
     *
     * @since 1.9.6
     */
    function feed_them_settings() {
        wp_register_style('feed_them_settings_css', plugins_url('admin/css/settings-page.css', dirname(__FILE__)));
        wp_enqueue_style('feed_them_settings_css');
        if (isset($_GET['page']) && $_GET['page'] == 'fts-youtube-feed-styles-submenu-page' || isset($_GET['page']) && $_GET['page'] == 'fts-instagram-feed-styles-submenu-page' || isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page' || isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page' || isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page' || isset($_GET['page']) && $_GET['page'] == 'fts-pinterest-feed-styles-submenu-page') {
            wp_enqueue_script('feed_them_style_options_color_js', plugins_url('admin/js/jscolor/jscolor.js', dirname(__FILE__)));
        }
    }

    /**
     * Need FTS Premium Fields
     *
     * Admin Premium Settings Fields.
     *
     * @param $fields
     * @return string
     * @since 1.9.6
     */
    function need_fts_premium_fields($fields) {
        $output = isset($output) ? $output : "";
        foreach ($fields as $key => $label) {
            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . $label . '</div>';
            $output .= '<div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.</div>';
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
     * @param $settings_name
     * @param $settings
     * @since 1.9.6
     */
    function register_settings($settings_name, $settings) {
        foreach ($settings as $key => $setting) {
            register_setting($settings_name, $setting);
        }
    }

    /**
     * FTS Facebook Style Options Page
     *
     * Register Facebook Style Options.
     *
     * @since 1.9.6
     */
    function fts_facebook_style_options_page() {
        $fb_style_options = array(
            'fb_app_ID',
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
        );
        $this->register_settings('fts-facebook-feed-style-options', $fb_style_options);
    }

    /**
     * FTS Twitter Style Options Page
     *
     * Register Twitter Style Options.
     *
     * @since 1.9.6
     */
    function fts_twitter_style_options_page() {
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
            //'twitter_replies_offset',
            'twitter_text_size',
            'twitter_load_more_text',
            'fts_twitter_custom_tokens'
        );
        $this->register_settings('fts-twitter-feed-style-options', $twitter_style_options);
    }

    /**
     * FTS Instagram Style Options Page
     *
     * Register Instagram Options.
     *
     * @since 1.9.6
     */
    function fts_instagram_style_options_page() {
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
        $this->register_settings('fts-instagram-feed-style-options', $instagram_style_options);
    }

    /**
     * FTS Pinterest Style Options Page
     *
     * Register Pinterest Options.
     *
     * @since 1.9.6
     */
    function fts_pinterest_style_options_page() {
        $pinterest_style_options = array(
            'fts_pinterest_custom_api_token',
            'pinterest_show_follow_btn',
            'pinterest_show_follow_btn_where',
            'pinterest_board_title_color',
            'pinterest_board_title_size',
            'pinterest_board_backg_hover_color',
        );
        $this->register_settings('fts-pinterest-feed-style-options', $pinterest_style_options);
    }

    /**
     * FTS Youtube Style Options Page
     *
     * Register YouTube Options.
     *
     * @since 1.9.6
     */
    function fts_youtube_style_options_page() {
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
        $this->register_settings('fts-youtube-feed-style-options', $youtube_style_options);
    }

    /**
     * FTS Settings Page Register Settings
     *
     * Register Free Version Settings.
     *
     * @since 1.9.6
     */
    function fts_settings_page_register_settings() {
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
        );
        $this->register_settings('feed-them-social-settings', $settings);
    }

    /**
     * Social Follow Buttons
     *
     * @param $feed
     * @param $user_id
     * @param null $access_token
     * @return string
     * @since 1.9.6
     */
    function social_follow_button($feed, $user_id, $access_token = NULL, $FB_Shortcode = NULL) {

        global $channel_id, $playlist_id, $username_subscribe_btn, $username;
        $output = '';
        switch ($feed) {
            case 'facebook':
                //Facebook settings options for follow button
                $fb_show_follow_btn = get_option('fb_show_follow_btn');
                $fb_show_follow_like_box_cover = get_option('fb_show_follow_like_box_cover');
                $language_option_check = get_option('fb_language');
                $fb_app_ID = get_option('fb_app_ID');

                if (isset($language_option_check) && $language_option_check !== 'Please Select Option') {
                    $language_option = get_option('fb_language', 'en_US');
                } else {
                    $language_option = 'en_US';
                }
                $fb_like_btn_color = get_option('fb_like_btn_color', 'light');
                //	var_dump( $fb_like_btn_color ); /* outputs 'default_value' */

                $show_faces = $fb_show_follow_btn == 'like-button-share-faces' || $fb_show_follow_btn == 'like-button-faces' || $fb_show_follow_btn == 'like-box-faces' ? 'true' : 'false';
                $share_button = $fb_show_follow_btn == 'like-button-share-faces' || $fb_show_follow_btn == 'like-button-share' ? 'true' : 'false';
                $page_cover = $fb_show_follow_like_box_cover == 'fb_like_box_cover-yes' ? 'true' : 'false';
                if (!isset($_POST['fts_facebook_script_loaded'])) {
                    $output .= '<div id="fb-root"></div>
							<script>jQuery(".fb-page").hide(); (function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/' . $language_option . '/sdk.js#xfbml=1&appId=' . $fb_app_ID . '&version=v2.6";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, "script", "facebook-jssd"));</script>';
                    $_POST['fts_facebook_script_loaded'] = 'yes';
                }
                //Page Box
                if ($fb_show_follow_btn == 'like-box' || $fb_show_follow_btn == 'like-box-faces') {

                    $like_box_width = isset($FB_Shortcode['like_box_width']) && $FB_Shortcode['like_box_width'] !== '' ? $FB_Shortcode['like_box_width'] : '500px';

                    $output .= '<div class="fb-page" data-href="https://www.facebook.com/' . $user_id . '" data-hide-cover="' . $page_cover . '" data-width="' . $like_box_width . '"  data-show-facepile="' . $show_faces . '" data-show-posts="false"></div>';
                } //Like Button
                else {
                    $output .= '<div class="fb-like" data-href="https://www.facebook.com/' . $user_id . '" data-layout="standard" data-action="like" data-colorscheme="' . $fb_like_btn_color . '" data-show-faces="' . $show_faces . '" data-share="' . $share_button . '" data-width:"100%"></div>';
                }
                return $output;
                break;
            case
            'instagram':
                $output .= '<a href="https://instagram.com/' . $user_id . '/" target="_blank">Follow on Instagram</a>';
                print $output;
                break;
            case 'twitter':
                if (!isset($_POST['fts_twitter_script_loaded'])) {
                    $output .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
                    $_POST['fts_twitter_script_loaded'] = 'yes';
                }
                $output .= '<a class="twitter-follow-button" href="https://twitter.com/' . $user_id . '" data-show-count="false" data-lang="en"> Follow @' . $user_id . '</a>';
                print $output;
                break;
            case 'pinterest':
                if (!isset($_POST['fts_pinterest_script_loaded'])) {
                    $output .= '
					<script> 
						jQuery(function () {
						   	//then load the JavaScript file
						    jQuery.getScript("//assets.pinterest.com/js/pinit.js");
						});
					</script>
					';
                    $_POST['fts_pinterest_script_loaded'] = 'yes';
                }

                $output .= '<a data-pin-do="buttonFollow" href="http://www.pinterest.com/' . $user_id . '/">' . $user_id . '</a>';

                return $output;
                break;
            case 'youtube':
                if (!isset($_POST['fts_youtube_script_loaded'])) {
                    $output .= '<script src="https://apis.google.com/js/platform.js"></script>';
                    $_POST['fts_youtube_script_loaded'] = 'yes';
                }
                if ($channel_id == '' && $playlist_id == '' && $username !== '' || $playlist_id !== '' && $username_subscribe_btn !== '') {

                    if ($username_subscribe_btn !== '') {
                        $output .= '<div class="g-ytsubscribe" data-channel="' . $username_subscribe_btn . '" data-layout="full" data-count="default"></div>';
                    } else {
                        $output .= '<div class="g-ytsubscribe" data-channel="' . $user_id . '" data-layout="full" data-count="default"></div>';
                    }

                } elseif ($channel_id !== '' && $playlist_id !== '' || $channel_id !== '') {
                    $output .= '<div class="g-ytsubscribe" data-channelid="' . $channel_id . '" data-layout="full" data-count="default"></div>';
                }
                print $output;
                break;
        }
    }

    /**
     * FTS Color Options Head CSS
     *
     * @since 1.9.6
     */
    function fts_color_options_head_css() { ?>
        <style type="text/css"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></style>
        <?php
    }

    /**
     * FTS FB Color Options Head CSS
     *
     * Color Options CSS for Facebook.
     *
     * @since 1.9.6
     */
    function fts_fb_color_options_head_css() {
        $fb_hide_no_posts_message = get_option('fb_hide_no_posts_message');
        $fb_header_extra_text_color = get_option('fb_header_extra_text_color');
        $fb_text_color = get_option('fb_text_color');
        $fb_link_color = get_option('fb_link_color');
        $fb_link_color_hover = get_option('fb_link_color_hover');
        $fb_feed_width = get_option('fb_feed_width');
        $fb_feed_margin = get_option('fb_feed_margin');
        $fb_feed_padding = get_option('fb_feed_padding');
        $fb_feed_background_color = get_option('fb_feed_background_color');
        $fb_post_background_color = get_option('fb_post_background_color');
        $fb_grid_posts_background_color = get_option('fb_grid_posts_background_color');
        $fb_grid_border_bottom_color = get_option('fb_grid_border_bottom_color');
        $fb_loadmore_background_color = get_option('fb_loadmore_background_color');
        $fb_loadmore_text_color = get_option('fb_loadmore_text_color');
        $fb_border_bottom_color = get_option('fb_border_bottom_color');
        $fb_grid_posts_background_color = get_option('fb_grid_posts_background_color');
        $fb_reviews_backg_color = get_option('fb_reviews_backg_color');
        $fb_reviews_text_color = get_option('fb_reviews_text_color');

        $fb_reviews_overall_rating_background_color = get_option('fb_reviews_overall_rating_background_color');
        $fb_reviews_overall_rating_border_color = get_option('fb_reviews_overall_rating_border_color');
        $fb_reviews_overall_rating_text_color = get_option('fb_reviews_overall_rating_text_color');
        $fb_reviews_overall_rating_background_padding = get_option('fb_reviews_overall_rating_background_padding');

        $fb_max_image_width = get_option('fb_max_image_width');

        $fb_events_title_color = get_option('fb_events_title_color');
        $fb_events_title_size = get_option('fb_events_title_size');
        $fb_events_maplink_color = get_option('fb_events_map_link_color');

        $twitter_hide_profile_photo = get_option('twitter_hide_profile_photo');
        $twitter_text_color = get_option('twitter_text_color');
        $twitter_link_color = get_option('twitter_link_color');
        $twitter_link_color_hover = get_option('twitter_link_color_hover');
        $twitter_feed_width = get_option('twitter_feed_width');
        $twitter_feed_margin = get_option('twitter_feed_margin');
        $twitter_feed_padding = get_option('twitter_feed_padding');
        $twitter_feed_background_color = get_option('twitter_feed_background_color');
        $twitter_border_bottom_color = get_option('twitter_border_bottom_color');
        $twitter_max_image_width = get_option('twitter_max_image_width');
        $twitter_grid_border_bottom_color = get_option('twitter_grid_border_bottom_color');
        $twitter_grid_posts_background_color = get_option('twitter_grid_posts_background_color');
        $twitter_loadmore_background_color = get_option('twitter_loadmore_background_color');
        $twitter_loadmore_text_color = get_option('twitter_loadmore_text_color');

        $instagram_loadmore_background_color = get_option('instagram_loadmore_background_color');
        $instagram_loadmore_text_color = get_option('instagram_loadmore_text_color');

        $pinterest_board_title_color = get_option('pinterest_board_title_color');
        $pinterest_board_title_size = get_option('pinterest_board_title_size');
        $pinterest_board_backg_hover_color = get_option('pinterest_board_backg_hover_color');


        $fts_social_icons_color = get_option('fts_social_icons_color');
        $fts_social_icons_hover_color = get_option('fts_social_icons_hover_color');
        $fts_social_icons_back_color = get_option('fts_social_icons_back_color');

        $youtube_loadmore_background_color = get_option('youtube_loadmore_background_color');
        $youtube_loadmore_text_color = get_option('youtube_loadmore_text_color');

        $fb_text_size = get_option('fb_text_size');
        $twitter_text_size = get_option('twitter_text_size'); ?>
        <style type="text/css"><?php if (!empty($fb_header_extra_text_color)) { ?>

            <?php }if (!empty($fb_hide_no_posts_message) && $fb_hide_no_posts_message == 'yes') { ?>
            .fts-facebook-add-more-posts-notice {
                display: none !important;
            }

            .fts-jal-single-fb-post .fts-jal-fb-user-name {
                color: <?php echo $fb_header_extra_text_color ?> !important;
            }

            <?php }if (!empty($fb_loadmore_background_color)) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo $fb_loadmore_background_color ?> !important;
            }

            <?php }if (!empty($fb_loadmore_text_color)) { ?>
            .fts-fb-load-more-wrapper .fts-fb-load-more {
                color: <?php echo $fb_loadmore_text_color ?> !important;
            }

            <?php }if (!empty($fb_loadmore_text_color)) { ?>
            .fts-fb-load-more-wrapper .fts-fb-spinner>div {
                background: <?php echo $fb_loadmore_text_color ?> !important;
            }

            <?php }if (!empty($fb_text_color)) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post,
            .fts-simple-fb-wrapper .fts-jal-fb-description-wrap,
            .fts-simple-fb-wrapper .fts-jal-fb-post-time,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post,
            .fts-slicker-facebook-posts .fts-jal-fb-description-wrap,
            .fts-slicker-facebook-posts .fts-jal-fb-post-time {
                color: <?php echo $fb_text_color ?> !important;
            }

            <?php }if (!empty($fb_link_color)) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a,
            .fts-jal-fb-group-header-desc a {
                color: <?php echo $fb_link_color ?> !important;
            }

            <?php }if (!empty($fb_link_color_hover)) { ?>
            .fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover,
            .fts-simple-fb-wrapper .fts-fb-load-more:hover,
            .fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover,
            .fts-slicker-facebook-posts .fts-fb-load-more:hover,
            .fts-jal-fb-group-header-desc a:hover{
                color: <?php echo $fb_link_color_hover ?> !important;
            }

            <?php }if (!empty($fb_feed_width)) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                max-width: <?php echo $fb_feed_width ?> !important;
            }

            <?php }if (!empty($fb_max_image_width)) { ?>
            .fts-fb-large-photo, .fts-jal-fb-vid-picture, .fts-jal-fb-picture, .fts-fluid-videoWrapper-html5 {
                max-width: <?php echo $fb_max_image_width ?> !important;
                float: left;
            }

            <?php }if (!empty($fb_events_title_color)) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                color: <?php echo $fb_events_title_color ?> !important;
            }

            <?php }if (!empty($fb_events_title_size)) { ?>
            .fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name {
                font-size: <?php echo $fb_events_title_size ?> !important;
                line-height: <?php echo $fb_events_title_size ?> !important;
            }

            <?php }if (!empty($fb_events_maplink_color)) { ?>
            .fts-simple-fb-wrapper a.fts-fb-get-directions {
                color: <?php echo $fb_events_maplink_color ?> !important;
            }

            <?php }if (!empty($fb_feed_margin)) { ?>
            .fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper, .fts-jal-fb-header, .fb-social-btn-top, .fb-social-btn-bottom, .fb-social-btn-below-description {
                margin: <?php echo $fb_feed_margin ?> !important;
            }

            <?php }if (!empty($fb_feed_padding)) { ?>
            .fts-simple-fb-wrapper {
                padding: <?php echo $fb_feed_padding ?> !important;
            }

            <?php }if (!empty($fb_feed_background_color)) { ?>
            .fts-simple-fb-wrapper, .fts-fb-load-more-wrapper .fts-fb-load-more {
                background: <?php echo $fb_feed_background_color ?> !important;
            }

            <?php }if (!empty($fb_post_background_color)) { ?>
            .fts-mashup-media-top .fts-jal-single-fb-post  {
                background: <?php echo $fb_post_background_color ?> !important;
            }

            <?php }if (!empty($fb_grid_posts_background_color)) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                background: <?php echo $fb_grid_posts_background_color ?> !important;
            }

            <?php }if (!empty($fb_border_bottom_color)) { ?>
            .fts-jal-single-fb-post {
                border-bottom-color: <?php echo $fb_border_bottom_color ?> !important;
            }

            <?php }if (!empty($fb_grid_border_bottom_color)) { ?>
            .fts-slicker-facebook-posts .fts-jal-single-fb-post {
                border-bottom-color: <?php echo $fb_grid_border_bottom_color ?> !important;
            }

            <?php }if (!empty($twitter_grid_posts_background_color)) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap  {
                background: <?php echo $twitter_grid_posts_background_color ?> !important;
            }

            <?php }if (!empty($twitter_grid_border_bottom_color)) { ?>
            .fts-slicker-twitter-posts .fts-tweeter-wrap {
                border-bottom-color: <?php echo $twitter_grid_border_bottom_color ?> !important;
            }

            <?php }if (!empty($twitter_loadmore_background_color)) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                background: <?php echo $twitter_loadmore_background_color ?> !important;
            }

            <?php }if (!empty($twitter_loadmore_text_color)) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-load-more {
                color: <?php echo $twitter_loadmore_text_color ?> !important;
            }

            <?php }if (!empty($twitter_loadmore_text_color)) { ?>
            .fts-twitter-load-more-wrapper .fts-fb-spinner>div {
                background: <?php echo $twitter_loadmore_text_color ?> !important;
            }

            <?php }if (!empty($fb_reviews_backg_color)) { ?>
            .fts-review-star {
                background: <?php echo $fb_reviews_backg_color ?> !important;
            }

            <?php }if (!empty($fb_reviews_overall_rating_background_color)) { ?>
            .fts-review-details-master-wrap {
                background: <?php echo $fb_reviews_overall_rating_background_color ?> !important;
            }

            <?php }if (!empty($fb_reviews_overall_rating_border_color)) { ?>
            .fts-review-details-master-wrap {
                border-bottom-color: <?php echo $fb_reviews_overall_rating_border_color ?> !important;
            }

            <?php }if (!empty($fb_reviews_overall_rating_background_padding)) { ?>
            .fts-review-details-master-wrap {
                padding: <?php echo $fb_reviews_overall_rating_background_padding ?> !important;
            }

            <?php }if (!empty($fb_reviews_overall_rating_text_color)) { ?>
            .fts-review-details-master-wrap {
                color: <?php echo $fb_reviews_overall_rating_text_color ?> !important;
            }

            <?php }if (!empty($fb_reviews_text_color)) { ?>
            .fts-review-star {
                color: <?php echo $fb_reviews_text_color ?> !important;
            }

            <?php }if (!empty($twitter_text_color)) { ?>
            .tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before, a span.fts-video-loading-notice {
                color: <?php echo $twitter_text_color ?> !important;
            }

            <?php }if (!empty($twitter_link_color)) { ?>
            .tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a, .twitter-followers-fts a, body.fts-twitter-reply-wrap a {
                color: <?php echo $twitter_link_color ?> !important;
            }

            <?php }if (!empty($twitter_link_color_hover)) { ?>
            .tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply, body.fts-twitter-reply-wrap a:hover {
                color: <?php echo $twitter_link_color_hover ?> !important;
            }

            <?php }if (!empty($twitter_feed_width)) { ?>
            .fts-twitter-div {
                max-width: <?php echo $twitter_feed_width ?> !important;
            }

            <?php }if (!empty($twitter_feed_margin)) { ?>
            .fts-twitter-div {
                margin: <?php echo $twitter_feed_margin ?> !important;
            }

            <?php }if (!empty($twitter_feed_padding)) { ?>
            .fts-twitter-div {
                padding: <?php echo $twitter_feed_padding ?> !important;
            }

            <?php }if (!empty($twitter_feed_background_color)) { ?>
            .fts-twitter-div {
                background: <?php echo $twitter_feed_background_color ?> !important;
            }

            <?php }if (!empty($twitter_border_bottom_color)) { ?>
            .tweeter-info {
                border-bottom: 1px solid <?php echo $twitter_border_bottom_color ?> !important;
            }

            <?php }if (!empty($twitter_max_image_width)) { ?>
            .fts-twitter-link-image {
                max-width: <?php echo $twitter_max_image_width ?> !important;
                display: block;
            }

            <?php }if (!empty($instagram_loadmore_background_color)) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                background: <?php echo $instagram_loadmore_background_color ?> !important;
            }

            <?php }if (!empty($instagram_loadmore_text_color)) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-load-more {
                color: <?php echo $instagram_loadmore_text_color ?> !important;
            }

            <?php }if (!empty($instagram_loadmore_text_color)) { ?>
            .fts-instagram-load-more-wrapper .fts-fb-spinner>div {
                background: <?php echo $instagram_loadmore_text_color ?> !important;
            }

            <?php } if (!empty($pinterest_board_backg_hover_color)) { ?>
            a.fts-pin-board-wrap:hover {
                background: <?php echo $pinterest_board_backg_hover_color ?> !important;
            }

            <?php } if (!empty($pinterest_board_title_color)) { ?>
            body h3.fts-pin-board-board_title {
                color: <?php echo $pinterest_board_title_color ?> !important;
            }

            <?php } if (!empty($pinterest_board_title_size)) { ?>
            body h3.fts-pin-board-board_title {
                font-size: <?php echo $pinterest_board_title_size ?> !important;
            }

            <?php }
            if (!empty($fts_social_icons_color)) { ?>
                .ft-gallery-share-wrap a.ft-galleryfacebook-icon, .ft-gallery-share-wrap a.ft-gallerytwitter-icon, .ft-gallery-share-wrap a.ft-gallerygoogle-icon, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon, .ft-gallery-share-wrap a.ft-galleryemail-icon {
                    color: <?php echo $fts_social_icons_color ?> !important;
                }
            <?php }
            if (!empty($fts_social_icons_hover_color)) { ?>
            .ft-gallery-share-wrap a.ft-galleryfacebook-icon:hover, .ft-gallery-share-wrap a.ft-gallerytwitter-icon:hover, .ft-gallery-share-wrap a.ft-gallerygoogle-icon:hover, .ft-gallery-share-wrap a.ft-gallerylinkedin-icon:hover, .ft-gallery-share-wrap a.ft-galleryemail-icon:hover {
                color: <?php echo $fts_social_icons_hover_color ?> !important;
            }

            <?php }
            if (!empty($fts_social_icons_back_color)) { ?>
            .ft-gallery-share-wrap {
                background: <?php echo $fts_social_icons_back_color ?> !important;
            }

            <?php }
           if (!empty($twitter_text_size)) { ?>
            span.fts-twitter-text {
                font-size: <?php echo $twitter_text_size ?> !important;
            }

            <?php }
           if (!empty($fb_text_size)) { ?>
            .fts-jal-fb-group-display .fts-jal-fb-message, .fts-jal-fb-group-display .fts-jal-fb-message p, .fts-jal-fb-group-header-desc, .fts-jal-fb-group-header-desc p, .fts-jal-fb-group-header-desc a {
                font-size: <?php echo $fb_text_size ?> !important;
            }

            <?php }
            if (!empty($youtube_loadmore_background_color)) { ?>
                .fts-youtube-load-more-wrapper .fts-fb-load-more {
                    background: <?php echo $youtube_loadmore_background_color ?> !important;
                }
    
                <?php }if (!empty($youtube_loadmore_text_color)) { ?>
                .fts-youtube-load-more-wrapper .fts-fb-load-more {
                    color: <?php echo $youtube_loadmore_text_color ?> !important;
                }
            <?php }
            if (!empty($youtube_loadmore_text_color)) { ?>
            .fts-youtube-load-more-wrapper .fts-fb-spinner>div {
                background: <?php echo $youtube_loadmore_text_color ?> !important;
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
    function fts_powered_by_js() {
        wp_enqueue_script('fts_powered_by_js', plugins_url('feeds/js/powered-by.js', dirname(__FILE__)), array('jquery')
        );
    }

    /**
     * Required Premium Field
     *
     * Admin Required Premium Settings Fields.
     *
     * @param $fields_info
     * @return string
     * @since 2.0.7
     */
    function need_fts_premium_plugin_field($fields_info) {
        $output = '<div class="feed-them-social-admin-input-default">' . $fields_info['no_active_msg'] . '</div>';
        return $output;
    }

    /**
     * Settings Form Fields Output
     *
     * @param bool $save_options
     * @param $feed_settings_array
     * @param $required_plugins
     * @return string
     * @since 2.0.8
     */
    function fts_settings_html_form($save_options = false, $feed_settings_array, $required_plugins) {
        $output = '';
        //Start creation of fields for each Feed
        foreach ($feed_settings_array as $section => $section_info) {
            $output .= '<div class="' . $section_info['section_wrap_class'] . '">';
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form ' . $section_info['form_wrap_classes'] . '" id="' . $section_info['form_wrap_id'] . '">';

            //Check to see if token is in place otherwise show a message letting person no what they need to do
            if (isset($section_info['token_check'])) {
                foreach ($section_info['token_check'] as $token_key => $token_info) {
                    if (!isset($token_info['req_plugin']) || isset($option['req_plugin']) && is_plugin_active($required_plugins[$option['req_plugin']]['plugin_url'])) {
                        $token_check = get_option($token_info['option_name']) ? 'Yes' : 'No';
                        $output .= isset($token_check) && $token_check !== 'No' ? "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">' . $token_info['no_token_msg'] . '</div>' . "\n";
                    }
                }
            }
            //Section Title
            $output .= isset($section_info['section_title']) ? '<h2>' . $section_info['section_title'] . '</h2>' : '';
            //Feed Types select
            if (isset($section_info['feeds_types'])) {
                $output .= '<div class="feed-them-social-admin-input-wrap ' . $section_info['feed_type_select']['select_wrap_classes'] . '">';
                $output .= '<div class="feed-them-social-admin-input-label">' . $section_info['feed_type_select']['label'] . '</div>';
                $output .= '<select name="' . $section_info['feed_type_select']['select_name'] . '" id="' . $section_info['feed_type_select']['select_id'] . '" class="feed-them-social-admin-input ' . $section_info['feed_type_select']['select_classes'] . '">';
                foreach ($section_info['feeds_types'] as $feed_type_name => $feed_type) if ($feed_type_name !== 'main_options') {
                    $output .= '<option value="' . $feed_type['value'] . '">' . $feed_type['title'] . '</option>';
                }
                $output .= '</select>';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/Feed Types Select Div Wrap-->';
            }

            //Conversion Input
            if (isset($section_info['conversion_input'])) {
                $output .= '<div class="' . $section_info['conversion_input']['main_wrap_class'] . '">';
                $output .= '<h2>' . $section_info['conversion_input']['conv_section_title'] . '</h2>';
                $output .= '<div class="feed-them-social-admin-input-wrap ' . $section_info['conversion_input']['input_wrap_class'] . '">';
                //Instructional Text
                $output .= '<div class="instructional-text">' . $section_info['conversion_input']['instructional-text'] . '</div>';
                $output .= '<div class="feed-them-social-admin-input-label">' . $section_info['conversion_input']['label'] . '</div>';
                //Input
                $output .= '<input type="input" name="' . $section_info['conversion_input']['name'] . '" id="' . $section_info['conversion_input']['id'] . '" class="feed-them-social-admin-input ' . (isset($section_info['conversion_input']['class']) ? $section_info['conversion_input']['class'] : '') . '" value="" />';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/Conversion Input Wrap-->';

                $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="' . $section_info['conversion_input']['btn-value'] . '" onclick="' . $section_info['conversion_input']['onclick'] . '" tabindex="4" style="margin-right:1em;" />';
                $output .= '</div>';

            }

            $output .= '</form>';

            //Feed Options
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form ' . $section_info['form_wrap_classes'] . ' ' . $section . '_options_wrap">';

            //Create settings fields for Feed OPTIONS
            foreach ($section_info['main_options'] as $option) if (!isset($option['no_html']) || isset($option['no_html']) && $option['no_html'] !== 'yes') {

                //Is a premium extension required?
                $required_plugin = !isset($option['req_plugin']) || isset($option['req_plugin']) && is_plugin_active($required_plugins[$option['req_plugin']]['plugin_url']) ? true : false;
                $or_required_plugin = isset($option['or_req_plugin']) && is_plugin_active($required_plugins[$option['or_req_plugin']]['plugin_url']) ? true : false;
                $or_required_plugin_three = isset($option['or_req_plugin_three']) && is_plugin_active($required_plugins[$option['or_req_plugin_three']]['plugin_url']) ? true : false;

                //Sub option output START?
                $output .= isset($option['sub_options']) ? '<div class="' . $option['sub_options']['sub_options_wrap_class'] . (!$required_plugin ? ' not-active-premium-fields' : '') . '">' . (isset($option['sub_options']['sub_options_title']) ? '<h3>' . $option['sub_options']['sub_options_title'] . '</h3>' : '') . (isset($option['sub_options']['sub_options_instructional_txt']) ? '<div class="instructional-text">' . $option['sub_options']['sub_options_instructional_txt'] . '</div>' : '') : '';

                $output .= isset($option['grouped_options_title']) ? '<h3 class="sectioned-options-title">' . $option['grouped_options_title'] . '</h3>' : '';

                //Only on a few options generally
                $output .= isset($option['outer_wrap_class']) || isset($option['outer_wrap_display']) ? '<div ' . (isset($option['outer_wrap_class']) ? 'class="' . $option['outer_wrap_class'] . '"' : '') . ' ' . (isset($option['outer_wrap_display']) && !empty($option['outer_wrap_display']) ? 'style="display:' . $option['outer_wrap_display'] . '"' : '') . '>' : '';
                //Main Input Wrap
                $output .= '<div class="feed-them-social-admin-input-wrap ' . (isset($option['input_wrap_class']) ? $option['input_wrap_class'] : '') . '" ' . (isset($section_info['input_wrap_id']) ? 'id="' . $section_info['input_wrap_id'] . '"' : '') . '>';
                //Instructional Text
                $output .= !empty($option['instructional-text']) && !is_array($option['instructional-text']) ? '<div class="instructional-text ' . (isset($option['instructional-class']) ? $option['instructional-class'] : '') . '">' . $option['instructional-text'] . '</div>' : '';

                if (!empty($option['instructional-text']) && is_array($option['instructional-text'])) {
                    foreach ($option['instructional-text'] as $instructional_txt) {
                        //Instructional Text
                        $output .= '<div class="instructional-text ' . (isset($instructional_txt['class']) ? $instructional_txt['class'] : '') . '">' . $instructional_txt['text'] . '</div>';
                    }
                }

                //Label Text
                $output .= isset($option['label']) && !is_array($option['label']) ? '<div class="feed-them-social-admin-input-label ' . (isset($option['label_class']) ? $option['label_class'] : '') . '">' . $option['label'] . '</div>' : '';

                if (!empty($option['label']) && is_array($option['label'])) {
                    foreach ($option['label'] as $label_txt) {
                        //Label Text
                        $output .= '<div class="feed-them-social-admin-input-label ' . (isset($label_txt['class']) ? $label_txt['class'] : '') . '">' . $label_txt['text'] . '</div>';
                    }
                }

                if ($required_plugin || $or_required_plugin || $or_required_plugin_three) {
                    //Option_Type = INPUT
                    $output .= isset($option['option_type']) && $option['option_type'] == 'input' ? '<input type="' . $option['type'] . '" name="' . $option['name'] . '" id="' . $option['id'] . '" class="' . (isset($option['color_picker']) && $option['color_picker'] == 'yes' ? 'color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'} ' : '') . 'feed-them-social-admin-input ' . (isset($option['class']) ? $option['class'] : '') . '" placeholder="' . (isset($option['placeholder']) ? $option['placeholder'] : '') . '" value="' . (isset($option['value']) ? '' . $option['value'] : '') . '" />' : '';

                    //Option_Type = Select
                    if (isset($option['option_type']) && $option['option_type'] == 'select') {
                        $output .= '<select name="' . $option['name'] . '" id="' . $option['id'] . '"  class="feed-them-social-admin-input">';
                        foreach ($option['options'] as $select_option) {
                            $output .= '<option value="' . $select_option['value'] . '"'.(isset($option['default_value']) && $option['default_value'] == $select_option['value'] ? ' selected': '') .'>' . $select_option['label'] . '</option>';
                        }
                        $output .= '</select>';
                    }
                } else {
                    //Create Required Plugin fields
                    $output .= $this->need_fts_premium_plugin_field($required_plugins[$option['req_plugin']]);
                }
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

                $output .= isset($option['outer_wrap_class']) || isset($option['outer_wrap_display']) ? '</div>' : '';

                //Sub option output END?
                if (isset($option['sub_options_end'])) {
                    $output .= !is_numeric($option['sub_options_end']) ? '</div>' : '';
                    //Multiple Div needed?
                    if (is_numeric($option['sub_options_end'])) {
                        $x = 1;
                        while ($x <= $option['sub_options_end']) {
                            $output .= '</div>';
                            $x++;
                        }
                    }
                }
            }
            $output .= $this->generate_shortcode('updateTextArea_' . $section . '();', $section_info['generator_title'], $section_info['generator_class']) . '</form>';

            $output .= '</div> <!--/Section Wrap Class END (Main-Section-Div)-->';

            //Premium Message Boxes
            if (isset($section_info['premium_msg_boxes'])) {
                foreach ($section_info['premium_msg_boxes'] as $key => $premium_msg) if (!is_plugin_active($required_plugins[$premium_msg['req_plugin']]['plugin_url'])) {
                    $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message" id="not_active_' . $key . '"><a class="not-active-title" href="' . $required_plugins[$premium_msg['req_plugin']]['slick_url'] . '" target="_blank">' . $required_plugins[$premium_msg['req_plugin']]['name'] . '</a>' . $premium_msg['msg'] . '</div>';
                }
            }
        }

        return $output;
    }

    /**
     * Facebook List of Events Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_list_of_events_form($save_options = false) {
        if ($save_options) {
            $fb_event_id_option = get_option('fb_event_id');
            $fb_event_post_count_option = get_option('fb_event_post_count');
            $fb_event_title_option = get_option('fb_event_title_option');
            $fb_event_description_option = get_option('fb_event_description_option');
            $fb_event_word_count_option = get_option('fb_event_word_count_option');
            $fts_bar_fb_prefix = 'fb_event_';
            $fb_load_more_option = get_option('fb_event_fb_load_more_option');
            $fb_load_more_style = get_option('fb_event_fb_load_more_style');
            $facebook_popup = get_option('fb_event_facebook_popup');
        }
        $fb_event_id_option = isset($fb_event_id_option) ? $fb_event_id_option : "";
        $output = '<div class="fts-facebook_event-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
            $output .= '<h2>' . __('Facebook List of Events Shortcode Generator', 'feed-them-social') . '</h2>';
        }
        $output .= '<div class="instructional-text inst-text-facebook-page">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __('Facebook Page ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap fb_page_list_of_events_id">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Event ID (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="fb_page_list_of_events_id" id="fb_page_list_of_events_id" class="feed-them-social-admin-input" value="' . $fb_event_id_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        // Facebook Height Option
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="facebook_event_height" id="facebook_event_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . 'e" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/facebook-event-settings-fields.php');
            if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
                //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }
        } else {
            $fields = array(
                __('Show the Event Title', 'feed-them-social'),
                __('Show the Event Description', 'feed-them-social'),
                __('Amount of words per post', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
                __('Display Photos in Popup', 'feed-them-social'),
                __('Display Posts in Grid', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_fb_list_of_events();', 'Facebook List of Events Feed Shortcode', 'facebook-event-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Facebook Event Form
     *
     * Single Event.
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_event_form($save_options = false) {
        if ($save_options) {
            $fb_event_id_option = get_option('fb_event_id');
            $fb_event_post_count_option = get_option('fb_event_post_count');
            $fb_event_title_option = get_option('fb_event_title_option');
            $fb_event_description_option = get_option('fb_event_description_option');
            $fb_event_word_count_option = get_option('fb_event_word_count_option');
            $fts_bar_fb_prefix = 'fb_event_';
            $fb_load_more_option = get_option('fb_event_fb_load_more_option');
            $fb_load_more_style = get_option('fb_event_fb_load_more_style');
            $facebook_popup = get_option('fb_event_facebook_popup');
        }
        $fb_event_id_option = isset($fb_event_id_option) ? $fb_event_id_option : "";
        $output = '<div class="fts-facebook_event-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
            $output .= '<h2>' . __('Facebook Event Shortcode Generator', 'feed-them-social') . '</h2>';
        }
        $output .= '<div class="instructional-text inst-text-facebook-page">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . __('Facebook Page Event ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap fb_event_id">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Event ID (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="fb_event_id" id="fb_event_id" class="feed-them-social-admin-input" value="' . $fb_event_id_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        // Facebook Height Option
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="facebook_event_height" id="facebook_event_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . 'e" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/facebook-event-settings-fields.php');
            if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
                //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }
        } else {
            $fields = array(
                __('Show the Event Title', 'feed-them-social'),
                __('Show the Event Description', 'feed-them-social'),
                __('Amount of words per post', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
                __('Display Photos in Popup', 'feed-them-social'),
                __('Display Posts in Grid', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_fb_event();', 'Facebook Event Feed Shortcode', 'facebook-list-of-events-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Facebook Group Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_group_form($save_options = false) {
        if ($save_options) {
            $fb_group_id_option = get_option('fb_group_id');
            $fb_group_post_count_option = get_option('fb_group_post_count');
            $fb_group_title_option = get_option('fb_group_title_option');
            $fb_group_description_option = get_option('fb_group_description_option');
            $fb_group_word_count_option = get_option('fb_group_word_count_option');
            $fts_bar_fb_prefix = 'fb_group_';
            $fb_load_more_option = get_option('fb_group_fb_load_more_option');
            $fb_load_more_style = get_option('fb_group_fb_load_more_style');
            $facebook_popup = get_option('fb_group_facebook_popup');
        }
        $fb_group_id_option = isset($fb_group_id_option) ? $fb_group_id_option : "";
        $output = '<div class="fts-facebook_group-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">';
            $output .= '<h2>' . __('Facebook Group Shortcode Generator', 'feed-them-social') . '</h2>';
        }
        $output .= '<div class="instructional-text">' . __('You must copy your ', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-group-id/" target="_blank">' . __('Facebook Group ID ', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap fb_group_id">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Group ID (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="fb_group_id" id="fb_group_id" class="feed-them-social-admin-input" value="' . $fb_group_id_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        // Facebook Height Option
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="facebook_group_height" id="facebook_group_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        //  $output .= '<!-- Using this for a future update <div class="feed-them-social-admin-input-wrap">
        //   <div class="feed-them-social-admin-input-label">'.__('Customized Group Name', 'feed-them-social').'</div>
        //  <select id="fb_group_custom_name" class="feed-them-social-admin-input">
        //   <option selected="selected" value="yes">'.__('My group name is custom', 'feed-them-social').'</option>
        //  <option value="no">'.__('My group name is number based', 'feed-them-social').'</option>
        // </select>
        // <div class="fts-clear"></div>
        // </div>
        // /feed-them-social-admin-input-wrap-->';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/facebook-group-settings-fields.php');
            if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
                //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }
        } else {
            //Create Need Premium Fields
            $fields = array(
                __('Show the Group Title', 'feed-them-social'),
                __('Show the Group Description', 'feed-them-social'),
                __('Amount of words per post', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
                __('Display Photos in Popup', 'feed-them-social'),
                __('Display Posts in Grid', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_fb_group();', 'Facebook Group Feed Shortcode', 'facebook-group-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Facebook Page Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_facebook_page_form($save_options = false) {
        if ($save_options) {
            $fb_page_id_option = get_option('fb_page_id');
            $fb_page_posts_displayed_option = get_option('fb_page_posts_displayed');
            $fb_page_post_count_option = get_option('fb_page_post_count');
            $fb_page_title_option = get_option('fb_page_title_option');
            $fb_page_description_option = get_option('fb_page_description_option');
            $fb_page_word_count_option = get_option('fb_page_word_count_option');
            $fts_bar_fb_prefix = 'fb_page_';
            $fb_load_more_option = get_option('fb_page_fb_load_more_option');
            $fb_load_more_style = get_option('fb_page_fb_load_more_style');
            $facebook_popup = get_option('fb_page_facebook_popup');
        }
        $output = '<div class="fts-facebook_page-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">';

            // Check to see if token is in place otherwise show a message letting person no what they need to do
            $facebookOptions = get_option('fts_facebook_custom_api_token') ? 'Yes' : 'No';
            $output .= isset($facebookOptions) && $facebookOptions !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please get your Access Token on our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page.</div>' . "\n";
            // end custom message for requiring token


            if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                $facebookOptions2 = get_option('fts_facebook_custom_api_token_biz') ? 'Yes' : 'No';
                // Check to see if token is in place otherwise show a message letting person no what they need to do
              //  $output .= isset($facebookOptions2) && $facebookOptions2 !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a Facebook Page Reviews API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before trying to view your Facebook Reviews feed.</div>' . "\n";
                // end custom message for requiring token
            }


            $output .= '<h2>' . __('Facebook Page Shortcode Generator', 'feed-them-social') . '</h2>';
        }
        $fb_page_id_option = isset($fb_page_id_option) ? $fb_page_id_option : "";
        // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            // FACEBOOK FEED TYPE
            $output .= '<div class="feed-them-social-admin-input-wrap" id="fts-social-selector">';
            $output .= '<div class="feed-them-social-admin-input-label">' . __('Feed Type', 'feed-them-social') . '</div>';
            $output .= '<select name="facebook-messages-selector" id="facebook-messages-selector" class="feed-them-social-admin-input">';
            $output .= '<option value="page">' . __('Facebook Page', 'feed-them-social') . '</option>';
            $output .= '<option value="events">' . __('Facebook Page List of Events', 'feed-them-social') . '</option>';
            $output .= '<option value="event">' . __('Facebook Page Single Event Posts', 'feed-them-social') . '</option>';
            $output .= '<option value="group">' . __('Facebook Group', 'feed-them-social') . '</option>';
            $output .= '<option value="album_photos">' . __('Facebook Album Photos', 'feed-them-social') . '</option>';
            $output .= '<option value="albums">' . __('Facebook Album Covers', 'feed-them-social') . '</option>';
            $output .= '<option value="album_videos">' . __('Facebook Videos', 'feed-them-social') . '</option>';
            $output .= '<option value="reviews">' . __('Facebook Page Reviews', 'feed-them-social') . '</option>';
            $output .= '</select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        };
        // INSTRUCTIONAL TEXT FOR FACEBOOK TYPE SELECTION. PAGE, GROUP, EVENT, ALBUMS, ALBUM COVERS AND HASH TAGS
        $output .= '<div class="instructional-text facebook-message-generator page inst-text-facebook-page" style="display:block;">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-page-vanity-url/" target="_blank">' . __('Facebook Page ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below. You cannot use Personal Profiles it must be a Facebook Page. If your page ID looks something like, My-Page-Name-50043151918, only use the number portion, 50043151918.', 'feed-them-social') . ' <a href="http://feedthemsocial.com/?feedID=50043151918" target="_blank">' . __('Test your Page ID on our demo', 'feed-them-social') . '</a></div>
			<div class="instructional-text facebook-message-generator group inst-text-facebook-group">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-group-id/" target="_blank">' . __('Facebook Group ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>
			<div class="instructional-text facebook-message-generator event-list inst-text-facebook-event-list">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . __('Facebook Event ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below. PLEASE NOTE: This will only work with Facebook Page Events and you cannot have more than 25 events on Facebook.', 'feed-them-social') . '</div>
			<div class="instructional-text facebook-message-generator event inst-text-facebook-event">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-facebook-event-id/" target="_blank">' . __('Facebook Event ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>
			<div class="instructional-text facebook-message-generator album_photos inst-text-facebook-album-photos">' . __('To show a specific Album copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . __('Facebook Album ID', 'feed-them-social') . '</a> ' . __('and paste it in the second input below. If you want to show all your uploaded photos leave the Album ID input blank.', 'feed-them-social') . '</div>
			<div class="instructional-text facebook-message-generator albums inst-text-facebook-albums">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">' . __('Facebook Album Covers ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>
			<div class="instructional-text facebook-message-generator video inst-text-facebook-video">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-id-and-video-gallery-id" target="_blank">' . __('Facebook ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>';
        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            // this is for the facebook videos
            $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium Version Required</a><br/>The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your wordpress site!<br/><a href="http://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br/><br/>Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too!<br/><a href="http://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a> </div>';
            // this is for the facebook reviews
            $output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message2" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-facebook-reviews/">Facebook Reviews Required</a><br/>The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="http://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a></div>';
        }
        // FACEBOOK PAGE ID
        if (isset($_GET['page']) && $_GET['page'] !== 'fts-bar-settings-page') {
            $output .= '<div class="fb-options-wrap">';
        }
        $output .= '<div class="feed-them-social-admin-input-wrap fb_page_id ">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook ID (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="fb_page_id" id="fb_page_id" class="feed-them-social-admin-input" value="' . $fb_page_id_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        // FACEBOOK ALBUM PHOTOS ID
        $output .= '<div class="feed-them-social-admin-input-wrap fb_album_photos_id" style="display:none;">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Album ID ', 'feed-them-social') . '<br/><small>' . __('Leave blank to show all uploaded photos', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="fb_album_id" id="fb_album_id" class="feed-them-social-admin-input" value="" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        $fb_page_posts_displayed_option = isset($fb_page_posts_displayed_option) ? $fb_page_posts_displayed_option : "";
        // FACEBOOK PAGE POST TYPE VISIBLE
        $output .= '<div class="feed-them-social-admin-input-wrap facebook-post-type-visible">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Post Type Visible', 'feed-them-social') . '</div>';
        $output .= '<select name="fb_page_posts_displayed" id="fb_page_posts_displayed" class="feed-them-social-admin-input">';
        $output .= '<option ' . selected($fb_page_posts_displayed_option, 'page_only', false) . ' value="page_only">' . __('Display Posts made by Page only', 'feed-them-social') . '</option>';
        $output .= '<option ' . selected($fb_page_posts_displayed_option, 'page_and_others', false) . ' value="page_and_others">' . __('Display Posts made by Page and Others', 'feed-them-social') . '</option>';
        $output .= '</select>';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';


        $fb_page_post_count_option = isset($fb_page_post_count_option) ? $fb_page_post_count_option : "";
        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('# of Posts', 'feed-them-premium');

        if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
        } else {
            $output .= '<br/><small>' . __('More than 6 Requires <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-premium') . '</small>';
        }
        $output .= '</div>';
        $output .= '<input type="text" name="fb_page_post_count" id="fb_page_post_count" class="feed-them-social-admin-input" value="' . $fb_page_post_count_option . '" placeholder="5 ' . __('is the default number', 'feed-them-premium') . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            // FACEBOOK HEIGHT OPTION
            $output .= '<div class="feed-them-social-admin-input-wrap twitter_name fixed_height_option">';
            $output .= '<div class="feed-them-social-admin-input-label">' . __('Facebook Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
            $output .= '<input type="text" name="facebook_page_height" id="facebook_page_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        }

        if (is_plugin_active('feed-them-premium/feed-them-premium.php') && !is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {

            include($this->premium . 'admin/facebook-page-settings-fields.php');
            if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
                //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }

        } elseif (is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {

            // these are the new options for reviews only
            include($this->facebook_reviews . 'admin/facebook-review-settings-fields.php');

            include($this->premium . 'admin/facebook-page-settings-fields.php');
            if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
                //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }
        } elseif (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            // include($this->facebook_reviews.'admin/facebook-page-settings-fields.php');

            // these are the new options for reviews only
            include($this->facebook_reviews . 'admin/facebook-review-settings-fields.php');

            // these are the additional options only for reviews from premium
            include($this->facebook_reviews . 'admin/facebook-loadmore-settings-fields.php');

            //Create Need Premium Fields
            $fields = array(
                __('Show the Page Title', 'feed-them-social'),
                __('Align Title', 'feed-them-social'),
                __('Show the Page Description', 'feed-them-social'),
                __('Amount of words per post', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
                __('Display Photos in Popup', 'feed-them-social'),
                __('Display Posts in Grid', 'feed-them-social'),
                __('Center Grid', 'feed-them-social'),
                __('Grid Stack Animation', 'feed-them-social'),
                __('Hide Like Button or Box', 'feed-them-social'),
                __('Like Box Width', 'feed-them-social'),
                __('Position Like Box', 'feed-them-social'),
                __('Align Like Button or Box', 'feed-them-social'),
            );
            $output .= '<div class="need-for-premium-fields-wrap">' . $this->need_fts_premium_fields($fields) . '</div>';
        } else {

            //Create Need Premium Fields
            $fields = array(
                __('Show the Page Title', 'feed-them-social'),
                __('Align Title', 'feed-them-social'),
                __('Show the Page Description', 'feed-them-social'),
                __('Amount of words per post', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
                __('Display Photos in Popup', 'feed-them-social'),
                __('Display Posts in Grid', 'feed-them-social'),
                __('Center Grid', 'feed-them-social'),
                __('Grid Stack Animation', 'feed-them-social'),
                __('Hide Like Button or Box', 'feed-them-social'),
                __('Like Box Width', 'feed-them-social'),
                __('Position Like Box', 'feed-them-social'),
                __('Align Like Button or Box', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            // FACEBOOK super gallery
            // $output .= '<div class="feed-them-social-admin-input-wrap facebook_name" style="display:none">';
            // $output .= '<div class="feed-them-social-admin-input-label">Super Facebook Gallery</div>';
            // $output .= '<select id="facebook-custom-gallery" name="facebook-custom-gallery" class="feed-them-social-admin-input"><option value="no" >No</option><option value="yes" >Yes. See Super Facebook Gallery Options below.</option></select>';
            // $output .= '<div class="fts-clear"></div>';
            // $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            // These options are only for FB album photos and covers
            // SUPER FACEBOOK GALLERY OPTIONS
            $output .= '<div class="fts-super-facebook-options-wrap" style="display:none">';
            // FACEBOOK IMAGE HEIGHT
            $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Facebook Image Width', 'feed-them-social') . '<br/><small>' . __('Max width is 640px', 'feed-them-social') . '</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-width" id="fts-slicker-facebook-container-image-width" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="fts-clear"></div> </div>';
            // FACEBOOK IMAGE WIDTH
            $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Facebook Image Height', 'feed-them-social') . '<br/><small>' . __('Max width is 640px', 'feed-them-social') . '</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-height" id="fts-slicker-facebook-container-image-height" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="fts-clear"></div> </div>';
            // FACEBOOK SPACE BETWEEN PHOTOS
            $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('The space between photos', 'feed-them-social') . '</div>
	           <input type="text" name="fts-slicker-facebook-container-margin" id="fts-slicker-facebook-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
	           <div class="fts-clear"></div></div>';
            // HIDE DATES, LIKES AND COMMENTS ETC
            $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Hide Date, Likes and Comments', 'feed-them-social') . '<br/><small>' . __('Good for image sizes under 120px', 'feed-them-social') . '</small></div>
	       		 <select id="fts-slicker-facebook-container-hide-date-likes-comments" name="fts-slicker-facebook-container-hide-date-likes-comments" class="feed-them-social-admin-input">
	        	  <option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select><div class="fts-clear"></div></div>';

            // CENTER THE FACEBOOK CONTAINER
            $output .= '<div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_container"><div class="feed-them-social-admin-input-label">' . __('Center Facebook Container', 'feed-them-social') . '</div>
	        	<select id="fts-slicker-facebook-container-position" name="fts-slicker-facebook-container-position" class="feed-them-social-admin-input"><option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select><div class="fts-clear"></div></div>';
            // ANIMATE PHOTO POSITIONING
            $output .= ' <div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_animate"><div class="feed-them-social-admin-input-label">' . __('Image Stacking Animation On', 'feed-them-social') . '<br/><small>' . __('This happens when resizing browsert', 'feed-them-social') . '</small></div>
	        	 <select id="fts-slicker-facebook-container-animation" name="fts-slicker-facebook-container-animation" class="feed-them-social-admin-input"><option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select><div class="fts-clear"></div></div>';
            // POSITION IMAGE LEFT RIGHT
            $output .= '<div class="instructional-text" style="display: block;">' . __('These options allow you to make the thumbnail larger if you do not want to see black bars above or below your photos.', 'feed-them-social') . ' <a href="http://www.slickremix.com/docs/fit-thumbnail-on-facebook-galleries/" target="_blank">' . __('View Examples', 'feed-them-social') . '</a> ' . __('and simple details or leave default options.', 'feed-them-social') . '</div>
			<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Make photo larger', 'feed-them-social') . '<br/><small>' . __('Helps with blackspace', 'feed-them-social') . '</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-lr" name="fts-slicker-facebook-image-position-lr" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -50%. -0% ' . __('is default', 'feed-them-social') . '">
	           <div class="fts-clear"></div></div>';
            // POSITION IMAGE TOP
            $output .= ' <div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Image Position Top', 'feed-them-social') . '<br/><small>' . __('Helps center image', 'feed-them-social') . '</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-top" name="fts-slicker-facebook-image-position-top" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -10%. -0% ' . __('is default', 'feed-them-social') . '">
				<div class="fts-clear"></div></div>';
            $output .= '</div><!--fts-super-facebook-options-wrap-->';

            if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                //PREMIUM LOAD MORE SETTINGS
                include($this->premium . 'admin/facebook-loadmore-settings-fields.php');
            }


            // Slideshow Carousel Options
            $output .= '<div class="slideshow-wrap" style="display: none;">';
            $output .= '<div class="instructional-text" style="display: block;">' . __('Create a Carousel or Slideshow with these options.', 'feed-them-social') . ' <a href="http://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . __('View Demos', 'feed-them-social') . '</a> ' . __('and copy easy to use shortcode examples.', 'feed-them-social') . '</div>';

            if (is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php')) {
                include($this->facebook_carousel_premium . 'admin/facebook-carousel-options-settings-page.php');
            } // if slider plugin is active
            else {
                $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">' . __('Carousel or Slideshow', 'feed-them-social') . '<br/><small>' . __('Many more options when active', 'feed-them-social') . '</small></div>
				<div class="feed-them-social-admin-input-default" style="display: block !important;">' . __('Must have ', 'feed-them-social') . ' <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">' . __('premium', 'feed-them-social') . '</a> ' . __('and', 'feed-them-social') . ' <a href="http://www.slickremix.com/downloads/feed-them-carousel-premium/">' . __('carousel', 'feed-them-social') . '</a> ' . __('plugin ', 'feed-them-social') . '</a> ' . __('to edit.', 'feed-them-social') . '</div> <div class="fts-clear"></div></div>';
            }

            // end slideshow wrap
            $output .= '</div>';


        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_fb_page();', 'Facebook Page Feed Shortcode', 'facebook-page-final-shortcode');
            if (isset($_GET['page']) && $_GET['page'] !== 'fts-bar-settings-page') {
                $output .= '</div>'; // END fb-options-wrap
            }
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
        }
        $output .= '</div><!--/fts-facebook_page-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Twitter Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_twitter_form($save_options = false) {
        if ($save_options) {
            $twitter_name_option = get_option('twitter_name');
            $tweets_count_option = get_option('tweets_count');
            $twitter_popup_option = get_option('twitter_popup_option');
            $twitter_hashtag_etc_name = get_option('twitter_hashtag_etc_name');
            $twitter_load_more_option = get_option('twitter_load_more_option');
        }

        $twitter_name_option = isset($twitter_name_option) ? $twitter_name_option : "";
        $tweets_count_option = isset($tweets_count_option) ? $tweets_count_option : "";
        $twitter_hashtag_etc_name = isset($twitter_hashtag_etc_name) ? $twitter_hashtag_etc_name : "";
        $output = '<div class="fts-twitter-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">';

            // Check to see if token is in place otherwise show a message letting person no what they need to do
            $twitterOptions4 = get_option('fts_twitter_custom_access_token_secret') ? 'Yes' : 'No';
            $output .= isset($twitterOptions4) && $twitterOptions4 !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before trying to view your feed.</div>' . "\n";
            // end custom message for requiring token


            $output .= '<h2>' . __('Twitter Shortcode Generator', 'feed-them-social') . '</h2>';
        }


        // TWITTER FEED TYPE
        $output .= '<div class="feed-them-social-admin-input-wrap twitter-gen-selection">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Feed Type', 'feed-them-social') . '</div>';
        $output .= '<select name="twitter-messages-selector" id="twitter-messages-selector" class="feed-them-social-admin-input">';
        $output .= '<option value="user">' . __('User Feed', 'feed-them-social') . '</option>';
        $output .= '<option value="hashtag">' . __('#hashtag, @person, or single words', 'feed-them-social') . '</option>';
        //$output .= '<option value="hashtag">Facebook Hashtag</option>';
        $output .= '</select>';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';


        $output .= '<div class="twitter-hashtag-etc-wrap">';
        $output .= '<h3>' . __('Twitter Search', 'feed-them-social') . '</h3>';
        $output .= '<div class="instructional-text">' . __('You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_hashtag_etc_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Twitter Search Name (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="twitter_hashtag_etc_name" id="twitter_hashtag_etc_name" class="feed-them-social-admin-input" value="' . $twitter_hashtag_etc_name . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        $output .= '</div><!--/twitter-hashtag-etc-wrap-->';


        $output .= '<div class="instructional-text"><span class="hashtag-option-small-text">' . __('Twitter Name is only required if you want to show a', 'feed-them-social') . ' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">' . __('Follow Button', 'feed-them-social') . '</a>.</span><span class="must-copy-twitter-name">' . __('You must copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __('Twitter Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</span></div>';
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Twitter Name', 'feed-them-social') . ' <span class="hashtag-option-not-required">' . __('(required)', 'feed-them-social') . '</span></div>';
        $output .= '<input type="text" name="twitter_name" id="twitter_name" class="feed-them-social-admin-input" value="' . $twitter_name_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';


        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('# of Tweets (optional)', 'feed-them-premium');
        if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            $output .= '<br/><small>' . __('More than 6 Requires <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-premium') . '</small>';
        }
        $output .= '</div>';
        $output .= '<input type="text" name="tweets_count" id="tweets_count" placeholder="5 is the default number" class="feed-them-social-admin-input" value="' . $tweets_count_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . __('Twitter Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
            $output .= '<input type="text" name="twitter_height" id="twitter_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        }

        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Show Retweets', 'feed-them-social') . '</div>';
        $output .= '<select name="twitter-show-retweets" id="twitter-show-retweets" class="feed-them-social-admin-input">';
        $output .= '<option value="yes">' . __('Yes', 'feed-them-social') . '</option>';
        $output .= '<option value="no">' . __('No', 'feed-them-social') . '</option>';
        $output .= '</select>';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';



        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/twitter-settings-fields.php');
        } else {
            //Create Need Premium Fields
            $fields = array(
                __('Display Photos in Popup', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_twitter();', 'Twitter Feed Shortcode', 'twitter-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div><!--/fts-twitter-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Vine Form
     *
     * @return string
     * @since 1.9.6
     */
    function fts_vine_form() {
        $output = '<div class="fts-vine-shortcode-form">';
        $output .= '<form class="feed-them-social-admin-form shortcode-generator-form vine-shortcode-form" id="fts-vine-form">';
        $output .= '<h2>' . __('Vine Shortcode Generator', 'feed-them-social') . '</h2>';

        $output .= '<div class="instructional-text">' . __('You can copy any', 'feed-them-social') . ' <a href="http://www.slickremix.com/docs/get-your-vine-video-id" target="_blank">' . __('Vine Video ID', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.<br/>Add more videos by adding a comma(,) after each id, there is no limit.<br/>For example: ee59033wulP,eBVBFTUzUHY', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap vine_id">';

        $output .= '<div class="feed-them-social-admin-input-label">' . __('Video ID or IDs (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="vine_id" id="vine_id" class="feed-them-social-admin-input" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Max width of thumbnail', 'feed-them-social') . '<br/><small>' . __('480px is max suggested', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="vine_maxwidth" id="vine_maxwidth" class="feed-them-social-admin-input" value="" placeholder="200px ' . __('for example', 'feed-them-social') . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Space between thumbnails', 'feed-them-social') . '<br/><small>' . __('Leave blank for default none', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="space_between_photos" id="space_between_photos" class="feed-them-social-admin-input" value="" placeholder="4px ' . __('for example', 'feed-them-social') . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Rounded Thumb Corner Amount', 'feed-them-social') . '<br/><small>' . __('Leave blank for none', 'feed-them-social') . '</small></div>';
        $output .= '<input type="text" name="round_thumb_corner_size" id="round_thumb_corner_size" class="feed-them-social-admin-input" value="" placeholder="3px ' . __('for example', 'feed-them-social') . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';


        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            // Check the premium version contains these new settings othwerise the settings page will show errors
            if (!file_exists($this->premium . 'admin/vine-settings-fields.php')) {
                $output .= '<div class="error feed-them-social-admin-input-wrap" style="margin:0px;"><p>' . __('Warning: You will need to upgrade the Premium Version of FTS to at least 1.5.0 to see the new premium settings.', 'feed-them-social') . '</p></div>';
            } else {
                include($this->premium . 'admin/vine-settings-fields.php');
            }
        } else {
            //Create Need Premium Fields
            $fields = array(
                __('Hide Title and Text', 'feed-them-social'),
                __('Vine Logo Size', 'feed-them-social'),
                __('Hide Vine Logo', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }

        $output .= $this->generate_shortcode('updateTextArea_vine();', 'Vine Feed Shortcode', 'vine-final-shortcode');
        $output .= '</form>';
        $output .= '</div><!--/fts-vine-shortcode-form-->';
        return $output;

    }

    /**
     * FTS Instagram Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.7
     */
    function fts_instagram_form($save_options = false) {
        if ($save_options) {
            $instagram_name_option = get_option('convert_instagram_username');
            $instagram_id_option = get_option('instagram_id');
            $pics_count_option = get_option('pics_count');
            $instagram_popup_option = get_option('instagram_popup_option');
            $instagram_load_more_option = get_option('instagram_load_more_option');
        }
        $output = '<div class="fts-instagram-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">';

            // Check to see if token is in place otherwise show a message letting person no what they need to do
            $instagramOptions = get_option('fts_instagram_custom_api_token') ? 'Yes' : 'No';
            $output .= isset($instagramOptions) && $instagramOptions !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please get your Access Token on the <a href="admin.php?page=fts-instagram-feed-styles-submenu-page">Instagram Options</a> page or you won\'t be able to view your photos.</div>' . "\n";
            // end custom message for requiring token

            // ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
            if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
                // INSTAGRAM FEED TYPE
                $output .= '<h2>' . __('Instagram Shortcode Generator', 'feed-them-social') . '</h2><div class="feed-them-social-admin-input-wrap instagram-gen-selection">';
                $output .= '<div class="feed-them-social-admin-input-label">' . __('Feed Type', 'feed-them-social') . '</div>';
                $output .= '<select name="instagram-messages-selector" id="instagram-messages-selector" class="feed-them-social-admin-input">';
                $output .= '<option value="user">' . __('User Feed', 'feed-them-social') . '</option>';
                $output .= '<option value="hashtag">' . __('Hashtag Feed', 'feed-them-social') . '</option>';
                $output .= '</select>';
                $output .= '<div class="fts-clear"></div>';
                $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            };
            $output .= '<div class="instagram-id-option-wrap">';
            $output .= '<h3>' . __('Convert Instagram Name to ID', 'feed-them-social') . '</h3>';
        }
        $instagram_name_option = isset($instagram_name_option) ? $instagram_name_option : "";
        $instagram_id_option = isset($instagram_id_option) ? $instagram_id_option : "";
        $output .= '<div class="instructional-text">' . __('You must copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">' . __('Instagram Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap convert_instagram_username">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Instagram Name (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" id="convert_instagram_username" name="convert_instagram_username" class="feed-them-social-admin-input" value="' . $instagram_name_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="' . __('Convert Instagram Username', 'feed-them-social') . '" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />';
        // ONLY THIS DIV IF ON OUR SETTINGS PAGE
        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
            $output .= '</div><!--instagram-id-option-wrap-->';
        };
        if ($save_options == false) {
            $output .= '</form>';
        }
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">';
        }
        $output .= '<div class="instructional-text instagram-user-option-text" style="margin-top:12px;"><div class="fts-insta-info-plus-wrapper">' . __('Choose a different ID if yours is not the first name below after clicking Convert Instagram Username button.', 'feed-them-social') . '</div><!-- the li list comes from an ajax call after looking up the user ID --><ul id="fts-instagram-username-picker-wrap" class="fts-instagram-username-picker-wrap"></ul></div>';
        $output .= '<div class="instructional-text instagram-hashtag-option-text" style="display:none;margin-top:12px;">' . __('Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap instagram_name">';
        $output .= '<div class="feed-them-social-admin-input-label instagram-user-option-text">' . __('Instagram ID # (required)', 'feed-them-social') . '</div>';
        $output .= '<div class="feed-them-social-admin-input-label instagram-hashtag-option-text" style="display:none;">' . __('Hashtag (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="instagram_id" id="instagram_id" class="feed-them-social-admin-input" value="' . $instagram_id_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        // Super Instagram Options

        $pics_count_option = isset($pics_count_option) ? $pics_count_option : "";
        //Pic Count Option
        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('# of Pics (optional)', 'feed-them-premium');
        if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            $output .= '<br/><small>' . __('More than 6 Requires <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium version</a>', 'feed-them-premium') . '</small>';
        }
        $output .= '</div>';
        $output .= '<input type="text" name="pics_count" id="pics_count" class="feed-them-social-admin-input" value="' . $pics_count_option . '" placeholder="6 is the default number" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';

        if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {

            $output .= '<div class="feed-them-social-admin-input-wrap">';
            $output .= '<div class="feed-them-social-admin-input-label">' . __('Super Instagram Gallery', 'feed-them-social') . '</div>';
            $output .= '<select id="instagram-custom-gallery" name="instagram-custom-gallery" class="feed-them-social-admin-input"><option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select>';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('Instagram Image Size', 'feed-them-social') . '<br/><small><a href="http://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __('View demo', 'feed-them-social') . '</a></small></div>
           <input type="text" name="fts-slicker-instagram-container-image-size" id="fts-slicker-instagram-container-image-size" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="fts-clear"></div> </div>';
            $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('Size of the Instagram Icon', 'feed-them-social') . '<br/><small>' . __('Visible when you hover over photo', 'feed-them-social') . '</small></div>
           <input type="text" name="fts-slicker-instagram-icon-center" id="fts-slicker-instagram-icon-center" class="feed-them-social-admin-input" value="65px" placeholder="">
           <div class="fts-clear"></div></div>';
            $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('The space between photos', 'feed-them-social') . '</div>
           <input type="text" name="fts-slicker-instagram-container-margin" id="fts-slicker-instagram-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
           <div class="fts-clear"></div></div>';
            $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('Hide Date, Likes and comments', 'feed-them-social') . '<br/><small>' . __('Good for image sizes under 120px', 'feed-them-social') . '</small></div>
       		 <select id="fts-slicker-instagram-container-hide-date-likes-comments" name="fts-slicker-instagram-container-hide-date-likes-comments" class="feed-them-social-admin-input">
        	  <option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select><div class="fts-clear"></div></div>';
            $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('Center Instagram Container', 'feed-them-social') . '</div>
        	<select id="fts-slicker-instagram-container-position" name="fts-slicker-instagram-container-position" class="feed-them-social-admin-input"><option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select>
           <div class="fts-clear"></div></div>';
            $output .= ' <div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">' . __('Image Stacking Animation On', 'feed-them-social') . '<br/><small>' . __('This happens when resizing browser', 'feed-them-social') . '</small></div>
        	 <select id="fts-slicker-instagram-container-animation" name="fts-slicker-instagram-container-animation" class="feed-them-social-admin-input"><option value="no">' . __('No', 'feed-them-social') . '</option><option value="yes">' . __('Yes', 'feed-them-social') . '</option></select><div class="fts-clear"></div></div>';


            // INSTAGRAM HEIGHT OPTION
            $output .= '<div class="feed-them-social-admin-input-wrap instagram_fixed_height_option">';
            $output .= '<div class="feed-them-social-admin-input-label">' . __('Instagram Fixed Height', 'feed-them-social') . '<br/><small>' . __('Leave blank for auto height', 'feed-them-social') . '</small></div>';
            $output .= '<input type="text" name="instagram_page_height" id="instagram_page_height" class="feed-them-social-admin-input" value="" placeholder="450px ' . __('for example', 'feed-them-social') . '" />';
            $output .= '<div class="fts-clear"></div>';
            $output .= '</div><!--/feed-them-social-admin-input-wrap-->';


            $output .= '</div><!--fts-super-instagram-options-wrap-->';


        }

        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {

            include($this->premium . 'admin/instagram-settings-fields.php');

        } else {
            //Create Need Premium Fields
            $fields = array(
                __('Display Photos & Videos in Popup', 'feed-them-social'),
                __('Load More Posts', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if ($save_options == false) {
            $output .= $this->generate_shortcode('updateTextArea_instagram();', 'Instagram Feed Shortcode', 'instagram-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn instagram-submit" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div> <!--/fts-instagram-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Youtube Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_youtube_form($save_options = false) {
        if ($save_options) {
            $youtube_name_option = get_option('youtube_name');
            $youtube_vid_count_option = get_option('youtube_vid_count');
            $youtube_columns_option = get_option('youtube_columns');
            $youtube_first_video_option = get_option('youtube_first_video');
        }
        $output = '<div class="fts-youtube-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">';

            // Check to see if token is in place otherwise show a message letting person no what they need to do
            $youtubeOptions = get_option('youtube_custom_api_token') || get_option('youtube_custom_access_token') && get_option('youtube_custom_refresh_token') && get_option('youtube_custom_token_exp_time') ? 'Yes' : 'No';
            $output .= isset($youtubeOptions) && $youtubeOptions !== 'No' ? '' . "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a YouTube API Key to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">YouTube Options</a> page before trying to view your feed.</div>' . "\n";
            // end custom message for requiring token

            $output .= '<h2>' . __('YouTube Shortcode Generator', 'feed-them-social') . '</h2>';
        }
        $output .= '<div class="instructional-text">' . __('You must copy your YouTube ', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">' . __('Username, Channel ID and or Playlist ID', 'feed-them-social') . '</a> ' . __('and paste it below.', 'feed-them-social') . '</div>';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/youtube-settings-fields.php');
        } else {
            //Create Need Premium Fields
            $fields = array(
                __('YouTube Name', 'feed-them-social'),
                __('# of videos', 'feed-them-social'),
                __('# of videos in each row', 'feed-them-social'),
                __('Display First video full size', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
            $output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:inline-block; text-decoration:none !important;">' . __('Click to see Premium Version', 'feed-them-social') . '</a>';
            $output .= '</form>';
        }
        $output .= '</div><!--/fts-youtube-shortcode-form-->';
        return $output;
    }

    /**
     * FTS Pinterest Form
     *
     * @param bool $save_options
     * @return string
     * @since 1.9.6
     */
    function fts_pinterest_form($save_options = false) {
        if ($save_options) {
            $pinterest_name_option = get_option('pinterest_name');
            $boards_count_option = get_option('boards_count');
        }
        $output = '<div class="fts-pinterest-shortcode-form">';
        if ($save_options == false) {
            $output .= '<form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">';
        }
        // Pinterest FEED TYPE
        $output .= '<h2>' . __('Pinterest Shortcode Generator', 'feed-them-social') . '</h2><div class="feed-them-social-admin-input-wrap pinterest-gen-selection">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Feed Type', 'feed-them-social') . '</div>';
        $output .= '<select name="pinterest-messages-selector" id="pinterest-messages-selector" class="feed-them-social-admin-input">';
        $output .= '<option value="boards_list">' . __('Board List', 'feed-them-social') . '</option>';
        $output .= '<option value="single_board_pins">' . __('Pins From a Specific Board', 'feed-them-social') . '</option>';
        $output .= '<option value="pins_from_user">' . __('Latest Pins from a User', 'feed-them-social') . '</option>';
        $output .= '</select>';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        $output .= '<h3>' . __('Pinterest Feed', 'feed-them-social') . '</h3><div class="instructional-text pinterest-name-text">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __('Pinterest Name', 'feed-them-social') . '</a> ' . __('and paste it in the first input below.', 'feed-them-social') . '</div>';
        $output .= '<div class="instructional-text pinterest-board-and-name-text" style="display:none;">' . __('Copy your', 'feed-them-social') . ' <a href="http://www.slickremix.com/how-to-get-your-pinterest-name/" target="_blank">' . __('Pinterest and Board Name', 'feed-them-social') . '</a> ' . __('and paste them below.', 'feed-them-social') . '</div>';
        $pinterest_name_option = isset($pinterest_name_option) ? $pinterest_name_option : "";
        $boards_count_option = isset($boards_count_option) ? $boards_count_option : "";
        $output .= '<div class="feed-them-social-admin-input-wrap pinterest_name">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Pinterest Username (required)', 'feed-them-social') . '</div>';
        $output .= '<input type="text" name="pinterest_name" id="pinterest_name" class="feed-them-social-admin-input" value="' . $pinterest_name_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        $output .= '<div class="feed-them-social-admin-input-wrap board-name" style="display:none;">';
        $output .= '<div class="feed-them-social-admin-input-label">' . __('Pinterest Board Name (required)', 'feed-them-premium') . '</div>';
        $output .= '<input type="text" name="pinterest_board_name" id="pinterest_board_name" class="feed-them-social-admin-input" value="' . $pinterest_name_option . '" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
            include($this->premium . 'admin/pinterest-settings-fields.php');
        } else {
            //Create Need Premium Fields
            $fields = array(
                __('# of Boards (default 6)', 'feed-them-social'),
                __('# of Pins (default 6)', 'feed-them-social'),
            );
            $output .= $this->need_fts_premium_fields($fields);
        }
        if (!$save_options) {
            $output .= $this->generate_shortcode('updateTextArea_pinterest();', '' . __('Pinterest Feed Shortcode', 'feed-them-social') . '', 'pinterest-final-shortcode');
            $output .= '</form>';
        } else {
            $output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="' . __('Save Changes', 'feed-them-social') . '" />';
        }
        $output .= '</div><!--/fts-pinterest-shortcode-form-->';
        return $output;
    }

    /**
     * Generate Shortcode
     *
     * Generate Shortcode Button and Input for FTS settings Page.
     *
     * @param $onclick
     * @param $label
     * @param $input_class
     * @return string
     * @since 1.9.6
     */
    function generate_shortcode($onclick, $label, $input_class) {
        $output = '<input type="button" class="feed-them-social-admin-submit-btn" value="' . __('Generate Shortcode', 'feed-them-social') . '" onclick="' . $onclick . '" tabindex="4" style="margin-right:1em;" />';
        $output .= '<div class="feed-them-social-admin-input-wrap final-shortcode-textarea">';
        $output .= '<h4>' . __('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social') . '</h4>';
        $output .= '<div class="feed-them-social-admin-input-label">' . $label . '</div>';
        $output .= '<input class="copyme ' . $input_class . ' feed-them-social-admin-input" value="" />';
        $output .= '<div class="fts-clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        return $output;
    }

    /**
     * FTS Get Feed json
     *
     * Generate Get Json (includes MultiCurl).
     *
     * @param $feeds_mulit_data
     * @return array
     * @since 1.9.6
     */
    function fts_get_feed_json($feeds_mulit_data) {
        // data to be returned
        $response = array();
        $curl_success = true;
        if (is_callable('curl_init')) {
            if (is_array($feeds_mulit_data)) {
                //Single Curl Loop
                $fts_curl_option = get_option('fts_curl_option') ? get_option('fts_curl_option') : '';
                if ($fts_curl_option == '') {
                    // array of curl handles
                    $curly = array();
                    // multi handle
                    $mh = curl_multi_init();
                    // loop through $data and create curl handles
                    // then add them to the multi-handle
                    foreach ($feeds_mulit_data as $id => $d) {
                        $curly[$id] = curl_init();
                        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
                        curl_setopt($curly[$id], CURLOPT_URL, $url);
                        curl_setopt($curly[$id], CURLOPT_HEADER, 0);
                        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
                        // post?
                        if (is_array($d)) {
                            if (!empty($d['post'])) {
                                curl_setopt($curly[$id], CURLOPT_POST, 1);
                                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
                            }
                        }
                        // extra options?
                        if (!empty($options)) {
                            curl_setopt_array($curly[$id], $options);
                        }
                        curl_multi_add_handle($mh, $curly[$id]);
                    }
                    // execute the handles
                    $running = null;
                    do {
                        $curl_status = curl_multi_exec($mh, $running);
                        // Check for errors
                        $info = curl_multi_info_read($mh);
                        if (false !== $info) {
                            // Add connection info to info array:
                            if (!$info['result']) {
                                //$multi_info[(integer) $info['handle']]['error'] = 'OK';
                            } else {
                                $multi_info[(integer)$info['handle']]['error'] = curl_error($info['handle']);
                                $curl_success = false;
                            }
                        }
                    } while ($running > 0);
                    // get content and remove handles
                    foreach ($curly as $id => $c) {
                        $response[$id] = curl_multi_getcontent($c);
                        curl_multi_remove_handle($mh, $c);
                    }
                    curl_multi_close($mh);
                } //Multi Curl Loop
                else {
                    // array of curl handles
                    $curly = array();
                    // loop through $data and create curl handles
                    // then add them to the multi-handle
                    foreach ($feeds_mulit_data as $id => $d) {
                        $curly[$id] = curl_init();
                        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
                        curl_setopt($curly[$id], CURLOPT_URL, $url);
                        curl_setopt($curly[$id], CURLOPT_HEADER, 0);
                        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
                        // post?
                        if (is_array($d)) {
                            if (!empty($d['post'])) {
                                curl_setopt($curly[$id], CURLOPT_POST, 1);
                                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
                            }
                        }
                        // extra options?
                        if (!empty($options)) {
                            curl_setopt_array($curly[$id], $options);
                        }
                        $response[$id] = curl_exec($curly[$id]);
                        curl_close($curly[$id]);
                    }

                }
            }//END Is_ARRAY
            //NOT ARRAY SINGLE CURL
            else {
                $ch = curl_init($feeds_mulit_data);
                curl_setopt_array($ch, array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => 0,
                    CURLOPT_POST => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0
                ));
                $response = curl_exec($ch);
                curl_close($ch);
            }

        }
        //File_Get_Contents if Curl doesn't work
        if (!$curl_success && ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE) {
            foreach ($feeds_mulit_data as $id => $d) {
                $response[$id] = @file_get_contents($d);
            }
        } else {
            //If nothing else use wordpress http API
            if (!$curl_success && !class_exists('WP_Http')) {
                include_once(ABSPATH . WPINC . '/class-http.php');
                $wp_http_class = new WP_Http;
                foreach ($feeds_mulit_data as $id => $d) {
                    $wp_http_result = $wp_http_class->request($d);
                    $response[$id] = $wp_http_result['body'];
                }
            }
            //Do nothing if Curl was Successful
        }
        return $response;
    }

    /**
     * FTS Create Feed Cache
     *
     * @param $transient_name
     * @param $response
     * @since 1.9.6
     */
    function fts_create_feed_cache($transient_name, $response) {
        $cacheTimeLimit = get_option('fts_clear_cache_developer_mode') == TRUE && get_option('fts_clear_cache_developer_mode') !== '1' ? get_option('fts_clear_cache_developer_mode') : '900';
        set_transient('fts_' . $transient_name, $response, $cacheTimeLimit);
    }

    /**
     * FTS Get Feed Cache
     *
     * @param $transient_name
     * @return mixed
     * @since 1.9.6
     */
    function fts_get_feed_cache($transient_name) {
        $returned_cache_data = get_transient('fts_' . $transient_name);
        return $returned_cache_data;
    }

    /**
     * FTS Check Feed Cache Exists
     *
     * @param $transient_name
     * @return bool
     * @since 1.9.6
     */
    function fts_check_feed_cache_exists($transient_name) {
        if (false === ($special_query_results = get_transient('fts_' . $transient_name))) {
            return false;
        }
        return true;
    }

    /**
     * FTS Clear Cache Ajax
     *
     * @since 1.9.6
     */
    function fts_clear_cache_ajax() {
        global $wpdb;
        $not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%'));
        $expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%'));
        wp_reset_query();
        return;
    }

    /**
     * Feed Them Clear Cache
     *
     * Clear Cache Folder.
     *
     * @return string
     * @since 1.9.6
     */
    function feed_them_clear_cache() {
        global $wpdb;
        $not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%'));
        $expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%'));
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
    function fts_admin_bar_menu() {
        global $wp_admin_bar;
        isset($ftsDevModeCache) ? $ftsDevModeCache : "";
        isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
        $ftsAdminBarMenu = get_option('fts_admin_bar_menu');
        $ftsDevModeCache = get_option('fts_clear_cache_developer_mode');
        if (!is_super_admin() || !is_admin_bar_showing() || $ftsAdminBarMenu == 'hide-admin-bar-menu')
            return;
        $wp_admin_bar->add_menu(array(
            'id' => 'feed_them_social_admin_bar',
            'title' => __('Feed Them Social', 'feed-them-social'),
            'href' => FALSE));
        if ($ftsDevModeCache == '1') {
            $wp_admin_bar->add_menu(array(
                    'id' => 'feed_them_social_admin_bar_clear_cache',
                    'parent' => 'feed_them_social_admin_bar',
                    'title' => __('Cache clears on page refresh now', 'feed-them-social'),
                    'href' => FALSE)
            );
        } else {
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'feed_them_social_admin_set_cache',
                    'parent' => 'feed_them_social_admin_bar',
                    'title' => __('Clear Cache', 'feed-them-social'),
                    'href' => '#')
            );
        }
        $wp_admin_bar->add_menu(
            array(
                'id' => 'feed_them_social_admin_bar_set_cache',
                'parent' => 'feed_them_social_admin_bar',
                'title' => __('Set Cache Time<span>'.$this->fts_cachetime_amount(get_option('fts_clear_cache_developer_mode')), 'feed-them-social').'</span>',
                'href' => admin_url('admin.php?page=feed-them-settings-page&tab=global_options'))
        );
        $wp_admin_bar->add_menu(array(
                'id' => 'feed_them_social_admin_bar_settings',
                'parent' => 'feed_them_social_admin_bar',
                'title' => __('Settings', 'feed-them-social'),
                'href' => admin_url('admin.php?page=feed-them-settings-page'))
        );
        $wp_admin_bar->add_menu(array(
                'id' => 'feed_them_social_admin_bar_global_options',
                'parent' => 'feed_them_social_admin_bar',
                'title' => __('Global Options', 'feed-them-social'),
                'href' => admin_url('admin.php?page=feed-them-settings-page&tab=global_options'))
        );
    }


    function fts_cachetime_amount($fts_cachetime) {
        Switch ($fts_cachetime) {
            case '1':
                $fts_display_cache_time = __('Clear cache on every page load', 'feed-them-social');
                break;
            case '10':
                $fts_display_cache_time = __('10 Seconds (for testing only)', 'feed-them-social');
                break;
            case '300':
                $fts_display_cache_time = __('5 Minutes', 'feed-them-social');
                break;
            case '600':
                $fts_display_cache_time = __('10 Minutes', 'feed-them-social');
                break;
            case '900':
                $fts_display_cache_time = __('15 Minutes', 'feed-them-social');
                break;
            case '1200':
                $fts_display_cache_time = __('20 Minutes', 'feed-them-social');
                break;
            case '1800':
                $fts_display_cache_time = __('30 Minutes', 'feed-them-social');
                break;
            case '3600':
                $fts_display_cache_time = __('60 Minutes', 'feed-them-social');
                break;
            case '86400':
                $fts_display_cache_time = __('1 Day (Default)', 'feed-them-social');
                break;
            case '604800':
                $fts_display_cache_time = __('1 Week', 'feed-them-social');
                break;
            case '1209600':
                $fts_display_cache_time = __('2 Weeks', 'feed-them-social');
                break;
        }
        return $fts_display_cache_time;
    }

    /**
     * XML json Parse
     *
     * @param $url
     * @return mixed
     * @since 1.9.6
     */
    function xml_json_parse($url) {
        $url_to_get['url'] = $url;
        $fileContents_returned = $this->fts_get_feed_json($url_to_get);
        $fileContents = $fileContents_returned['url'];
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);

        return $json;
    }

    /**
     * FTS Ago
     *
     * Create date format like fb and twitter. Thanks: http://php.quicoto.com/how-to-calculate-relative-time-like-facebook/ .
     *
     * @param $timestamp
     * @return string
     * @since 1.9.6
     */
    function fts_ago($timestamp) {
        // not setting isset'ing anything because you have to save the settings page to even enable this feature
        $fts_language_second = get_option('fts_language_second');
        if (empty($fts_language_second)) $fts_language_second = 'second';
        $fts_language_seconds = get_option('fts_language_seconds');
        if (empty($fts_language_seconds)) $fts_language_seconds = 'seconds';
        $fts_language_minute = get_option('fts_language_minute');
        if (empty($fts_language_minute)) $fts_language_minute = 'minute';
        $fts_language_minutes = get_option('fts_language_minutes');
        if (empty($fts_language_minute)) $fts_language_minute = 'minutes';
        $fts_language_hour = get_option('fts_language_hour');
        if (empty($fts_language_hour)) $fts_language_hour = 'hour';
        $fts_language_hours = get_option('fts_language_hours');
        if (empty($fts_language_hours)) $fts_language_hours = 'hours';
        $fts_language_day = get_option('fts_language_day');
        if (empty($fts_language_day)) $fts_language_day = 'day';
        $fts_language_days = get_option('fts_language_days');
        if (empty($fts_language_days)) $fts_language_days = 'days';
        $fts_language_week = get_option('fts_language_week');
        if (empty($fts_language_week)) $fts_language_week = 'week';
        $fts_language_weeks = get_option('fts_language_weeks');
        if (empty($fts_language_weeks)) $fts_language_weeks = 'weeks';
        $fts_language_month = get_option('fts_language_month');
        if (empty($fts_language_month)) $fts_language_month = 'month';
        $fts_language_months = get_option('fts_language_months');
        if (empty($fts_language_months)) $fts_language_months = 'months';
        $fts_language_year = get_option('fts_language_year');
        if (empty($fts_language_year)) $fts_language_year = 'year';
        $fts_language_years = get_option('fts_language_years');
        if (empty($fts_language_years)) $fts_language_years = 'years';
        $fts_language_ago = get_option('fts_language_ago');
        if (empty($fts_language_ago)) $fts_language_ago = 'ago';

        //	$periods = array( "sec", "min", "hour", "day", "week", "month", "years", "decade" );
        $periods = array($fts_language_second, $fts_language_minute, $fts_language_hour, $fts_language_day, $fts_language_week, $fts_language_month, $fts_language_year, "decade");
        $periods_plural = array($fts_language_seconds, $fts_language_minutes, $fts_language_hours, $fts_language_days, $fts_language_weeks, $fts_language_months, $fts_language_years, "decades");

        if (!is_numeric($timestamp)) {
            $timestamp = strtotime($timestamp);
            if (!is_numeric($timestamp)) {
                return "";
            }
        }
        $difference = time() - $timestamp;
        // Customize in your own language. Why thank-you I will.
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($difference > 0) { // this was in the past
            $ending = $fts_language_ago;
        } else { // this was in the future
            $difference = -$difference;
            //not doing dates in the future for posts
            $ending = "to go";
        }
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] = $periods_plural[$j];
        }
        $text = "$difference $periods[$j] $ending";
        return $text;
    }

    function fts_custom_date($created_time, $feed_type) {
        $ftsCustomDate = get_option('fts-custom-date');
        $ftsCustomTime = get_option('fts-custom-time');
        $CustomDateCheck = get_option('fts-date-and-time-format');
        $fts_twitter_offset_time = get_option('fts_twitter_time_offset');
        $fts_timezone = get_option('fts-timezone');

        if ($ftsCustomDate == '' && $ftsCustomTime == '') {
            $CustomDateFormat = $CustomDateCheck;
        } elseif ($ftsCustomDate !== '' || $ftsCustomTime !== '') {
            $CustomDateFormat = $ftsCustomDate . ' ' . $ftsCustomTime;
        } else {
            $CustomDateFormat = 'F jS, Y \a\t g:ia';
        }
        if(!empty($fts_timezone)){
            date_default_timezone_set($fts_timezone);
        }
        // Twitter date time
        if ($feed_type == 'twitter') {
            if ($fts_twitter_offset_time == 1) {
                $fts_twitter_offset_time_final = strtotime($created_time);
            } else {
                $fts_twitter_offset_time_final = strtotime($created_time) - 3 * 3600;
            }
            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            } else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, $fts_twitter_offset_time_final) : $this->fts_ago($created_time);
            }
        }
        // Instagram date time
        if ($feed_type == 'instagram') {
            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            }
            else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, $created_time) : $this->fts_ago($created_time);
            }
        }
        // Youtube and Pinterest date time
        if ($feed_type == 'pinterest') {
            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            }
            else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, strtotime($created_time)) : $this->fts_ago($created_time);
            }
        }
        // WP Gallery and Pinterest date time
        if ($feed_type == 'wp_gallery') {
            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            }
            else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, strtotime($created_time)) : $this->fts_ago($created_time);
            }
        }
        // Facebook date time
        if ($feed_type == 'facebook') {
            $timeSet = $fts_timezone;
            $timeSetCheck = isset($timeSet) ? $timeSet : 'America/New_York';
            date_default_timezone_set($timeSetCheck);

            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            }
            else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, $created_time) : $this->fts_ago($created_time);
            }
        }
        // Instagram date time
        if ($feed_type == 'youtube') {
            if ($CustomDateCheck == 'one-day-ago') {
                $uTime = $this->fts_ago($created_time);
            }
            else {
                $uTime = !empty($CustomDateCheck) ? date_i18n($CustomDateFormat, strtotime($created_time)) : $this->fts_ago($created_time);
            }
        }
        //Return the time
        return $uTime;
    }

    function fts_youtube_link_filter($youtube_description) {
        //Converts URLs to Links
        $youtube_description = preg_replace('@(?!(?!.*?<a)[^<]*<\/a>)(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-‌​Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0" target="_blank">\0</a>', $youtube_description);

        $splitano = explode("www", $youtube_description);
        $count = count($splitano);
        $returnValue = "";

        for ($i = 0; $i < $count; $i++) {
            if (substr($splitano[$i], -6, 5) == "href=") {
                $returnValue .= $splitano[$i] . "http://www";
            } else if ($i < $count - 1) {
                $returnValue .= $splitano[$i] . "www";
            } else {
                $returnValue .= $splitano[$i];
            }
        }
        return $returnValue;
    }

    function fts_youtube_video_and_wrap($post_data, $username, $playlist_id) {
        $ssl = is_ssl() ? 'https' : 'http';
        $youtube_video_user_or_playlist_url = isset($post_data->snippet->resourceId->videoId) ? $post_data->snippet->resourceId->videoId : '';
        $youtube_video_channel_url = isset($post_data->id->videoId) ? $post_data->id->videoId : '';

        if ($username !== '' || $playlist_id !== '') {
            $youtube_video_iframe = '<div class="fts-fluid-videoWrapper"><iframe src="' . $ssl . '://www.youtube.com/embed/' . $youtube_video_user_or_playlist_url . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';

        } else {
            $youtube_video_iframe = '<div class="fts-fluid-videoWrapper"><iframe src="' . $ssl . '://www.youtube.com/embed/' . $youtube_video_channel_url . '?wmode=transparent&HD=0&rel=0&showinfo=0&controls=1&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        }

        return $youtube_video_iframe;
    }

    function fts_youtube_description($post_data) {

        $PinterestDescription = isset($post_data->snippet->description) ? $post_data->snippet->description : '';
        return $PinterestDescription;
    }

    function fts_youtube_title($post_data) {
        $youtube_post_title = isset($post_data->snippet->title) ? $post_data->snippet->title : "";
        return $youtube_post_title;
    }

    /**
     * Random String generator For All Feeds
     *
     * @param int $length
     * @return string
     * @since 2.0.7
     */
    function feed_them_social_rand_string($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * FTS Refresh YouTube Token
     *
     * @since 2.3.3
     */
    function fts_refresh_token_ajax() {
        if($_REQUEST['button_pushed'] == 'yes') {
            update_option('youtube_custom_refresh_token',  $_REQUEST['refresh_token']);
            print 'Save New Tokens';
        }
       update_option('youtube_custom_access_token',  $_REQUEST['access_token']);
       update_option('youtube_custom_token_exp_time',  strtotime("+" . $_REQUEST['expires_in'] . " seconds"));
        // This only happens if the token is expired on the YouTube Options page and you go to resave or refresh the page for some reason. It will also run this function if the cache is emptied and the token is found to be expired.
        if($_REQUEST['button_pushed'] == 'no') {
            print 'Token Refreshed';
          //  print do_shortcode('[fts _youtube vid_count=3 large_vid=no large_vid_title=no large_vid_description=no thumbs_play_in_iframe=popup vids_in_row=3 space_between_videos=1px force_columns=yes maxres_thumbnail_images=yes thumbs_wrap_color=#000 wrap=none video_wrap_display=none comments_count=12 channel_id=UCqhnX4jA0A5paNd1v-zEysw loadmore=button loadmore_count=5 loadmore_btn_maxwidth=300px loadmore_btn_margin=10px]');
        }
    }

    /**
     * FTS Check YouTube Token Validity
     *
     * @since 2.3.3
     */
    function feed_them_youtube_refresh_token() {
        // Used some methods from this link http://ieg.wnet.org/2015/09/using-oauth-in-wordpress-plugins-part-2-persistence/

        // save all 3 get options: happens when clicking the get access token button on the youtube options page
        if(isset($_GET['refresh_token']) && isset($_GET['access_token']) && isset($_GET['expires_in'])) {
            $button_pushed = 'yes';
            $clienttoken_post["refresh_token"] = $_GET['refresh_token'];
            $authObj['access_token'] = $_GET['access_token'];
            $authObj['expires_in'] = $_GET['expires_in'];
        }
        // refresh token
        else {
          //  print 'helloooo';
            $button_pushed = 'no';
            $oauth2token_url = "https://accounts.google.com/o/oauth2/token";
            $clienttoken_post = array(
                "client_id" => '802796800957-6nannpdq8h8l720ls430ahnnq063n22u.apps.googleusercontent.com',
                "client_secret" => 'CbieVhgOudjrpya1IDpv3uRa',
            );
            // The "refresh token" grant type is to use a refresh token to get a new access token
            $clienttoken_post["refresh_token"] = get_option('youtube_custom_refresh_token');
            $clienttoken_post["grant_type"] = "refresh_token";

            $postargs = array(
                'body' => $clienttoken_post
            );
            $response = wp_remote_post($oauth2token_url, $postargs );
            $authObj = json_decode(wp_remote_retrieve_body( $response ), true);
            //  echo'<pre>';
            //  print_r($authObj);
            //  echo'</pre>';
        }
        ?>
        <script>
            jQuery(document).ready(function() {
                jQuery.ajax({
                    data: {
                        action: "fts_refresh_token_ajax",
                        refresh_token: '<?php echo $clienttoken_post["refresh_token"] ?>',
                        access_token: '<?php echo $authObj['access_token'] ?>',
                        expires_in: '<?php echo $authObj['expires_in'] ?>',
                        button_pushed: '<?php echo $button_pushed ?>'
                    },
                    type: 'POST',
                    url: ftsAjax.ajaxurl,
                    success: function( response ) {
                        console.log(response);
                        <?php if(isset($_GET['page']) && $_GET['page'] == 'fts-youtube-feed-styles-submenu-page'){
                        $sucess_message = '<div class="fts-successful-api-token">' . __('Your Access Token is working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div><div class="fts-clear"></div>';
                        ?>
                        jQuery('#youtube_custom_access_token, #youtube_custom_token_exp_time').val('');

                            <?php  if(isset($_GET['refresh_token']) && isset($_GET['access_token']) && isset($_GET['expires_in'])) {?>
                                    jQuery('#youtube_custom_refresh_token').val(jQuery('#youtube_custom_refresh_token').val() + '<?php echo $clienttoken_post["refresh_token"] ?>');
                                    jQuery('.fts-failed-api-token').hide();

                                    if(!jQuery('.fts-successful-api-token').length) {
                                        jQuery('.fts-youtube-last-row').append('<?php echo $sucess_message ?>');
                                    }
                                <?php }
                                else { ?>
                                    if(jQuery('.fts-failed-api-token').length) {
                                        jQuery('.fts-youtube-last-row').append('<?php echo $sucess_message ?>');
                                        jQuery('.fts-failed-api-token').hide();
                                    }
                        <?php }?>

                        jQuery('#youtube_custom_access_token').val(jQuery('#youtube_custom_access_token').val() + '<?php echo $authObj['access_token'] ?>');
                        jQuery('#youtube_custom_token_exp_time').val(jQuery('#youtube_custom_token_exp_time').val() + '<?php echo strtotime("+" . $authObj['expires_in'] . " seconds") ?>');
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
        return $authObj['access_token'];
    }
}//END Class
?>