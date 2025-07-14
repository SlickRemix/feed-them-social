<?php
/**
 * Gallery Options Class
 *
 * This class has the options for building and saving on the Custom Meta Boxes
 *
 * @class    FeedCPTOptions
 * @version  1.0.0
 * @package  FeedThemSocial/Admin
 * @category Class
 * @author   SlickRemix
 */

namespace feedthemsocial\admin\cpt\options;

// Exit if accessed directly!
if ( !\defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class FeedCPTOptions
 */
class FeedCPTOptions
{

    /**
     * All Feed Options
     *
     * @var array
     */
    public $allOptions;

    /**
     * Facebook Additional Options.
     *
     * @since 4.0.0
     * @var array
     */
    public $facebookAdditionalOptions;

    /**
     * Instagram Additional Options.
     *
     * @since 4.0.0
     * @var array
     */
    public $instagramAdditionalOptions;

    /**
     * Twitter Additional Options.
     *
     * @since 4.0.0
     * @var array
     */
    public $twitterAdditionalOptions;

    /**
     * YouTube Additional Options.
     *
     * @since 4.0.0
     * @var array
     */
    public $youtubeAdditionalOptions;

    /**
     * Color Picker Class.
     *
     * @var string
     */
    const COLOR_PICKER_CLASS = 'fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:\'#eee\',pickerFace:3,pickerBorder:0,pickerInsetColor:\'white\'}';

    /**
     * Responsive Desktop Columns Wrap Class.
     *
     * @var string
     */
    const RESPONSIVE_DESKTOP_COLUMNS_WRAP_CLASS = 'fb-page-columns-option-hide responsive-columns-desktop-wrap';

    /**
     * FeedCPTOptions constructor.
     */
    public function __construct ($facebookAdditionalOptions, $instagramAdditionalOptions, $twitterAdditionalOptions, $youtubeAdditionalOptions)
    {
        // Facebook Additional Options.
        $this->facebookAdditionalOptions = $facebookAdditionalOptions->getAllOptions();

        // Instagram Additional Options.
        $this->instagramAdditionalOptions = $instagramAdditionalOptions->getAllOptions();

        // Twitter Additional Options.
        $this->twitterAdditionalOptions = $twitterAdditionalOptions->getAllOptions();

        // YouTube Additional Options.
        $this->youtubeAdditionalOptions = $youtubeAdditionalOptions->getAllOptions();
    }

    const DEFAULT_WIDTH_PLACEHOLDER = '310px ';

    /**
     * Generates the boilerplate array for a settings section.
     *
     * @param array $args The unique arguments for the section.
     * @return array The structured settings array.
     */
    private function generateOptionsArray (array $args): array
    {
        // Set default values for all common keys
        $defaults = [
            'section_attr_key'   => '',
            'section_wrap_class' => 'fts-tab-content',
            'form_wrap_classes'  => 'fb-page-shortcode-form',
            'form_wrap_id'       => 'fts-fb-page-form',
            'options_wrap_class' => 'fts-cpt-additional-options',
            'premium_msg_boxes'  => [],
            'main_options'       => [],
        ];

        // Merge the unique arguments with the defaults, preserving all keys
        return array_replace_recursive( $defaults, $args );
    }

    /**
     * Get All Token Options
     *
     * @return array
     * @since 4.0.0
     */
    public function getAllTokenOptions ()
    {
        $this->twitterTokenOptions();
        $this->facebookTokenOptions();
        $this->instagramTokenOptions();
        $this->instagramBusinessTokenOptions();
        $this->youtubeTokenOptions();
        $this->combineInstagramTokenOptions();
        $this->combineInstagramTokenSelectOptions();
        $this->combineFacebookTokenOptions();
        $this->combineTwitterTokenSelectOptions();
        $this->combineTwitterTokenOptions();
        $this->combineYoutubeTokenSelectOptions();
        $this->combineYoutubeTokenOptions();

        return $this->allOptions;
    }

    /**
     * All Feed Options
     *
     * @return array
     * @since 1.0.0
     */
    public function getAllOptions ($include_additional_options = false)
    {
        $this->feedTypeOptions();
        $this->twitterTokenOptions();
        $this->facebookTokenOptions();
        $this->instagramTokenOptions();
        $this->instagramBusinessTokenOptions();
        $this->youtubeTokenOptions();
        $this->combineInstagramTokenOptions();
        $this->combineInstagramTokenSelectOptions();
        $this->combineFacebookTokenOptions();
        $this->combineTwitterTokenSelectOptions();
        $this->combineTwitterTokenOptions();
        $this->combineYoutubeTokenSelectOptions();
        $this->combineYoutubeTokenOptions();
        $this->facebookOptions();
        $this->instagramOptions();
        $this->twitterOptions();
        $this->youtubeOptions();
        $this->combineOptions();

        if ( $include_additional_options ) {
            $this->allOptions = array_merge( $this->allOptions, $this->facebookAdditionalOptions, $this->instagramAdditionalOptions, $this->twitterAdditionalOptions, $this->youtubeAdditionalOptions );
        }

        return $this->allOptions;
    }

    /**
     * View Decrypted Token
     *
     * @return mixed
     * @since 4.0.0
     */
    public function viewDecryptedToken ()
    {
        ob_start(); ?>
        <div class="fts-copy-decrypted-token fts-decrypted-token">
            <span class="fts-show-token"><?php echo esc_html__( 'View', 'feed-them-social' ) ?></span>
            <span class="fts-hide-token"><?php echo esc_html__( 'Hide', 'feed-them-social' ) ?></span>
            <?php echo esc_html__( 'Decrypted Token', 'feed-them-social' ) ?>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Feed Type
     *
     * @return mixed
     * @since 4.0.0
     */
    public function feedTypeOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'ft-wp-gallery-type',
                'option_type'        => 'select',
                'label'              => esc_html__( 'Feed Type: ', 'feed-them-social' ),
                'type'               => 'text',
                'instructional-text' => \sprintf(
                    esc_html__( '%5$s %10$s %11$sChoose the Social Network you want to create a feed for below.%12$s%11$sClick on the "Login and Get my Access Token" button.%12$s%11$sOnce your Access Token is Valid, you can view your feed and set options from the menu on the left.%12$s%11$sTo view your social feed on the front end of your website, copy the Feed Shortcode from the right sidebar and paste it to any page, post, widget, or page builder. If you are using Gutenberg, Elementor, or Beaver Builder, you can search for the block, widget, or module called Feed Them Social and select your feed from there. %9$sRead More%3$s %12$s%13$s %7$sNote:%8$s You can only choose one social platform per feed. To create an additional feed, click %2$sAdd New Feed%3$s and follow the same 4 steps. Set the cache time and other global options from the %14$sSettings%3$s page of our plugin.%6$s%4$s', 'feed-them-social' ),
                    '<br/>',
                    '<a href="post-new.php?post_type=fts" target="_blank">',
                    '</a>',
                    '<div class="fts-select-social-network-menu">
                            <div class="fts-social-icon-wrap instagram-feed-type" data-fts-feed-type="instagram-feed-type"><img src="' . plugins_url() . '/feed-them-social/metabox/images/instagram-logo-admin.png" class="instagram-feed-type-image" /><span class="fts-instagram"></span><div>Instagram</div></div>
                            <div class="fts-social-icon-wrap facebook-feed-type" data-fts-feed-type="facebook-feed-type"><span class="fts-facebook"></span><div>Facebook</div></div>
                            <div class="fts-social-icon-wrap twitter-feed-type" data-fts-feed-type="twitter-feed-type"><span class="fts-twitter"></span><div>TikTok</div></div>
                            <div class="fts-social-icon-wrap youtube-feed-type" data-fts-feed-type="youtube-feed-type"><span class="fts-youtube"></span><div>YouTube</div></div>
                            <div class="fts-social-icon-wrap combine-streams-feed-type" data-fts-feed-type="combine-streams-feed-type"><span class="fts-combined"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 96C0 78.33 14.33 64 32 64H144.6C164.1 64 182.4 72.84 194.6 88.02L303.4 224H384V176C384 166.3 389.8 157.5 398.8 153.8C407.8 150.1 418.1 152.2 424.1 159L504.1 239C514.3 248.4 514.3 263.6 504.1 272.1L424.1 352.1C418.1 359.8 407.8 361.9 398.8 358.2C389.8 354.5 384 345.7 384 336V288H303.4L194.6 423.1C182.5 439.2 164.1 448 144.6 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H144.6L247 256L144.6 128H32C14.33 128 0 113.7 0 96V96z"/></svg></span><div>Combined</div></div>
                        </div>',
                    '<svg xmlns="http://www.w3.org/2000/svg" class="fts-info-icon" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"/></svg><div class="fts-select-social-network-menu-instructions">',
                    '</div>',
                    '<strong>',
                    '</strong>',
                    '<a href="https://www.slickremix.com/documentation/add-feed-to-a-page-post-or-widget/" target="_blank" >',
                    '<ol class="fts-instructions-list">',
                    '<li>',
                    '</li>',
                    '</ol>',
                    '<a href="edit.php?post_type=fts&page=fts-settings-page" target="_blank">'
                ),
                'id'                 => 'feed_type',
                'name'               => 'feed_type',
                'default_value'      => 'instagram-feed-type',
                'options'            => [
                    ['label' => esc_html__( 'Instagram', 'feed-them-social' ), 'value' => 'instagram-feed-type'],
                    [
                        'label' => esc_html__( 'Instagram Business', 'feed-them-social' ),
                        'value' => 'instagram-business-feed-type'
                    ],
                    ['label' => esc_html__( 'Facebook', 'feed-them-social' ), 'value' => 'facebook-feed-type'],
                    ['label' => esc_html__( 'Twitter', 'feed-them-social' ), 'value' => 'twitter-feed-type'],
                    ['label' => esc_html__( 'YouTube Feed', 'feed-them-social' ), 'value' => 'youtube-feed-type'],
                    [
                        'label' => esc_html__( 'Combine Streams', 'feed-them-social' ),
                        'value' => 'combine-streams-feed-type'
                    ],
                ],
            ],
            [
                'input_wrap_class' => 'fts-shortcode-location',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Shortcode Location', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_shortcode_location',
                'name'             => 'fts_shortcode_location',
                'value'            => 'Not Set',
            ],
        ];

        $this->allOptions['feedTypeOptions'] = $this->generateOptionsArray( [
            'section_attr_key' => 'feed_type_',
            'section_wrap_id'  => 'fts-tab-content',
            'main_options'     => $main_options,
        ] );

        return $this->allOptions['feedTypeOptions'];
    }

    /**
     * Twitter Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function twitterTokenOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'fts-tiktok-user-id',
                'option_type'        => 'input',
                'label'              => esc_html__( 'User ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'fts_tiktok_user_id',
                'name'               => 'fts_tiktok_user_id',
                'instructional-text' => \sprintf( esc_html__( '%1$s %2$s Click the button below to get an access token. This gives the plugin read-only access to get your TikTok posts. Once you have your Access Token you will be able to create a feed.', 'feed-them-social' ), '<strong>', '</strong>', '<br/><br/>' ),
            ],
            [
                'input_wrap_class' => 'fts-tiktok-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Access Token', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_tiktok_access_token',
                'name'             => 'fts_tiktok_access_token'
            ],
            [
                'input_wrap_class' => 'fts-tiktok-saved-time-expires-in fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Saved Time', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_tiktok_saved_time_expires_in',
                'name'             => 'fts_tiktok_saved_time_expires_in'
            ],
            [
                'input_wrap_class' => 'fts-tiktok-expires-in fts-exp-time-wrapper',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Token Expiration', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_tiktok_expires_in',
                'name'             => 'fts_tiktok_expires_in'
            ],
            [
                'input_wrap_class' => 'fts-tiktok-refresh-token fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Refresh Token', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_tiktok_refresh_token',
                'name'             => 'fts_tiktok_refresh_token'
            ],
            [
                'input_wrap_class' => 'fts-tiktok-refresh-expires-in fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Refresh Token Expiration', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_tiktok_refresh_expires_in',
                'name'             => 'fts_tiktok_refresh_expires_in'
            ],
            [
                'input_wrap_class' => 'fts-tiktok-scheduled-event fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Cron Job Scheduled Event', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_scheduled_event',
                'name'             => 'tiktok_scheduled_event'
            ],
        ];

        $this->allOptions['twitterTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'twitter_token_',
            'section_title'      => esc_html__( 'TikTok Access Token', 'feed-them-social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_class' => 'fts-tab-content1-twitter fts-token-wrap',
            'form_wrap_classes'  => 'fb-page-shortcode-form-twitter',
            'form_wrap_id'       => 'fts-fb-page-form-twitter',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['twitterTokenOptions'];
    }

    /**
     * Facebook Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function facebookTokenOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'fts-facebook-custom-access-token',
                'option_type'        => 'input',
                'label'              => esc_html__( 'Page ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'fts_facebook_custom_api_token_user_id',
                'name'               => 'fts_facebook_custom_api_token_user_id',
                'instructional-text' => \sprintf( esc_html__( 'This option is for Facebook Pages and is used to display the feed. This will NOT work for personal profiles or groups. You must be an admin of the page to gain an access token using the button below. Additionally, you can add a Page ID and Access Token then click the "Save Token Manually" button. Tokens are encrypted for additional security before being saved. %1$sClick the button below to get an access token. This gives the plugin read-only access to get your Facebook posts. We will never post or change anything within your Facebook account. Once an Access Token is in place you can create a feed. Please note, use of this plugin is subject to %2$sMeta\'s Platform Terms%3$s%4$s', 'feed-them-social' ), '<p>', '<a href="https://developers.facebook.com/terms/" target="_blank">', '</a>', '</p>' ),
            ],
            [
                'input_wrap_class' => 'fts-facebook-custom-api-token-user-name',
                'option_type'      => 'input',
                'label'            => esc_html__( 'User Name ', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_facebook_custom_api_token_user_name',
                'name'             => 'fts_facebook_custom_api_token_user_name'
            ],
            [
                'input_wrap_class' => 'fts-facebook-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Access Token', 'feed-them-social' ) . $this->viewDecryptedToken(),
                'type'             => 'text',
                'id'               => 'fts_facebook_custom_api_token',
                'name'             => 'fts_facebook_custom_api_token'
            ],
        ];

        $this->allOptions['facebookTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'facebook_token_',
            'section_title'      => esc_html__( 'Facebook Access Token', 'feed-them-social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_class' => 'fts-tab-content1-facebook fts-token-wrap',
            'form_wrap_classes'  => 'fb-page-shortcode-form-facebook',
            'form_wrap_id'       => 'fts-fb-page-form-facebook',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['facebookTokenOptions'];
    }

    /**
     * Instagram Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function instagramTokenOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'fts-instagram-custom-access-token',
                'option_type'        => 'input',
                'label'              => esc_html__( 'Instagram ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'fts_instagram_custom_id',
                'name'               => 'fts_instagram_custom_id',
                'instructional-text' => \sprintf( esc_html__( 'Click the button below to get an access token. This gives the plugin read-only access to get your Instagram posts. We will never post or change anything within your Instagram account. %5$sYour Instagram account must be set to Professional and have the Creator or Business option selected. %1$sRead Instructions%2$s. Once you have an Access Token you will be able to create your feed. Tokens are encrypted for additional security before being saved. Please note, use of this plugin is subject to %3$sMeta\'s Platform Terms%4$s', 'feed-them-social' ), '<a target="_blank" href="https://www.slickremix.com/documentation/connect-instagram-professional-account/">', '</a>', '<a href="https://developers.facebook.com/terms/" target="_blank">', '</a>', '<br/><br/>' ),
            ],
            [
                'input_wrap_class' => 'fts-instagram-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Access Token', 'feed-them-social' ) . $this->viewDecryptedToken(),
                'type'             => 'text',
                'id'               => 'fts_instagram_custom_api_token',
                'name'             => 'fts_instagram_custom_api_token'
            ],
            [
                'input_wrap_class' => 'fts-instagram-custom-api-token-user-name fts-success-class fts-exp-time-wrapper fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Refresh Expire Time', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_instagram_custom_api_token_expires_in',
                'name'             => 'fts_instagram_custom_api_token_expires_in'
            ],
            [
                'input_wrap_class' => 'fts-instagram-business-basic-scheduled-event fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Cron Job Scheduled Event', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_business_basic_scheduled_event',
                'name'             => 'instagram_business_basic_scheduled_event'
            ],
        ];

        $this->allOptions['instagramTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'instagram_token_',
            'section_title'      => esc_html__( 'Instagram Access Token', 'feed-them-social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_class' => 'fts-tab-content1-instagram fts-token-wrap',
            'form_wrap_classes'  => 'fb-page-shortcode-form-instagram',
            'form_wrap_id'       => 'fts-fb-page-form-instagram',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['instagramTokenOptions'];
    }

    /**
     * Instagram Business Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function instagramBusinessTokenOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'fts-facebook-instagram-custom-access-token',
                'option_type'        => 'input',
                'label'              => esc_html__( 'Page ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'fts_facebook_instagram_custom_api_token_user_id',
                'name'               => 'fts_facebook_instagram_custom_api_token_user_id',
                'instructional-text' => \sprintf( esc_html__( 'Click the button below to get an access token. Additionally, you can add a Page ID and Access Token then click the "Save Token Manually" button. This gives the plugin read-only access to get your Instagram posts. We will never post or change anything within your Instagram account. %5$sYour Instagram must be linked to a Facebook Business Page. Once you have your Access Token you will be able to create a feed. Tokens are encrypted for additional security before being saved. %1$sRead Instructions%2$s. Please note, use of this plugin is subject to %3$sMeta\'s Platform Terms%4$s', 'feed-them-social' ), '<a target="_blank" href="https://www.slickremix.com/documentation/connect-instagram-to-facebook/">', '</a>', '<a href="https://developers.facebook.com/terms/" target="_blank">', '</a>', '<br/><br/>' ),
            ],
            [
                'input_wrap_class' => 'fts-facebook-instagram-custom-api-token-user-name',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Instagram Name ', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_facebook_instagram_custom_api_token_user_name',
                'name'             => 'fts_facebook_instagram_custom_api_token_user_name'
            ],
            [
                'input_wrap_class' => 'fts-facebook-instagram-custom-api-token-fb-user-name',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Facebook Name ', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'fts_facebook_instagram_custom_api_token_fb_user_name',
                'name'             => 'fts_facebook_instagram_custom_api_token_fb_user_name'
            ],
            [
                'input_wrap_class' => 'fts-facebook-instagram-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Access Token', 'feed-them-social' ) . $this->viewDecryptedToken(),
                'type'             => 'text',
                'id'               => 'fts_facebook_instagram_custom_api_token',
                'name'             => 'fts_facebook_instagram_custom_api_token'
            ],
        ];

        $this->allOptions['instagramBusinessTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'facebook_instagram_token_',
            'section_title'      => esc_html__( 'Instagram Business Access Token', 'feed-them-social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_class' => 'fts-tab-content1-facebook-instagram fts-token-wrap',
            'form_wrap_classes'  => 'fb-page-shortcode-form-facebook-instagram',
            'form_wrap_id'       => 'fts-fb-page-form-facebook-instagram',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['instagramBusinessTokenOptions'];
    }

    /**
     * YouTube Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function youtubeTokenOptions ()
    {
        $main_options = [
            [
                'input_wrap_class'   => 'fts-youtube-add-all-keys-click-option',
                'option_type'        => 'input',
                'label'              => \sprintf( esc_html__( '%1$sAPI Key%2$s %3$sPress Update to save Key.%4$s', 'feed-them-social' ), '<a href="https://www.slickremix.com/documentation/create-youtube-api-key/" target="_blank">', '</a>', '<small>', '</small><br/>' ),
                'type'               => 'text',
                'id'                 => 'youtube_custom_api_token',
                'name'               => 'youtube_custom_api_token',
                'instructional-text' => \sprintf( esc_html__( 'Click the button below to get an access token. This gives the plugin read-only access to get your YouTube videos. It would be best to %1$sadd your own API Key%2$s in the long run because they allow more API calls. Once you have Access Tokens or have entered an API key, you can create a feed.', 'feed-them-social' ), '<span>', '</span>' ),
            ],
            [
                'input_wrap_class' => 'fts-youtube-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Access Token', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_custom_access_token',
                'name'             => 'youtube_custom_access_token'
            ],
            [
                'input_wrap_class' => 'fts-youtube-refresh-access-token',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Refresh Token', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_custom_refresh_token',
                'name'             => 'youtube_custom_refresh_token'
            ],
            [
                'input_wrap_class' => 'fts-success-class fts-exp-time-wrapper fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Token Expiration', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_custom_token_exp_time',
                'name'             => 'youtube_custom_token_exp_time'
            ],
            [
                'input_wrap_class' => 'fts-youtube-scheduled-event fts-display-none',
                'option_type'      => 'input',
                'label'            => esc_html__( 'Cron Job Scheduled Event', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_scheduled_event',
                'name'             => 'youtube_scheduled_event'
            ],
        ];

        $this->allOptions['youtubeTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'youtube_token_',
            'section_title'      => esc_html__( 'YouTube Access Token', 'feed-them-social' ) . '<span class="fts-valid-text"></span>',
            'section_wrap_class' => 'fts-tab-content1-youtube fts-token-wrap',
            'form_wrap_classes'  => 'fb-page-shortcode-form-youtube',
            'form_wrap_id'       => 'fts-fb-page-form-youtube',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['youtubeTokenOptions'];
    }

    /**
     * Facebook Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function facebookOptions ()
    {
        $main_options = [
            // Feed Type,
            [
                'input_wrap_class' => 'fts-social-selector',
                'option_type'      => 'select',
                'label'            => __( 'Feed Type', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'facebook_page_feed_type',
                'name'             => 'facebook_page_feed_type',
                'options'          => [
                    ['label' => __( 'Page Posts', 'feed-them-social' ), 'value' => 'page'],
                    ['label' => __( 'Album Photos', 'feed-them-social' ), 'value' => 'album_photos'],
                    ['label' => __( 'Album Covers', 'feed-them-social' ), 'value' => 'albums'],
                    ['label' => __( 'Videos', 'feed-them-social' ), 'value' => 'album_videos'],
                    ['label' => __( 'Page Reviews', 'feed-them-social' ), 'value' => 'reviews']
                ]
            ],
            // Album ID
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'fb_album_photos_id',
                'label'            => __( 'Album ID ', 'feed-them-social' ) . '<br/><small>' . __( 'See how to quickly <a href="https://www.slickremix.com/documentation/get-facebook-album-cover-id" target="_blank">get an Album ID</a>', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_album_id',
                'name'             => 'facebook_album_id'
            ],
            // Post Type Visible
            [
                'input_wrap_class' => 'facebook-post-type-visible',
                'option_type'      => 'select',
                'label'            => __( 'Post Type Visible', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'facebook_page_posts_displayed',
                'name'             => 'facebook_page_posts_displayed',
                'options'          => [
                    ['label' => __( 'Display Posts', 'feed-them-social' ), 'value' => 'page_only'],
                    [
                        'label' => __( 'Display Reels & Posts (Beta)', 'feed-them-social' ),
                        'value' => 'page_reels_and_posts'
                    ]
                ]
            ],
            // Number of Posts
            [
                'option_type'   => 'input',
                'label'         => __( 'Number of Posts<div class="fts-paid-extension-required"><small>More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium</a></small></div>', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'facebook_page_post_count',
                'name'          => 'facebook_page_post_count',
                'placeholder'   => __( '6 is the default number', 'feed-them-social' ),
                'default_value' => '6'
            ],
            // Album Covers Since Date
            [
                'input_wrap_class' => 'facebook-album-covers-since-date',
                'option_type'      => 'input',
                'label'            => __( 'Album Since Date <br/>Example: 09-24-2024<br/><small>Add a date to show more recent albums if you have a large collection.</small>', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'facebook_album_covers_since_date',
                'name'             => 'facebook_album_covers_since_date'
            ],
            // Omit Album Covers
            [
                'input_wrap_class' => 'facebook-omit-album-covers',
                'option_type'      => 'input',
                'label'            => __( 'Omit Album Covers <br/><small>ie* 0,4,5 <a target="_blank" href="https://feedthemsocial.com/facebook-album-covers/">Example</a>', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_omit_album_covers',
                'name'             => 'facebook_omit_album_covers'
            ],
            // Fixed Height
            [
                'input_wrap_class' => 'fixed_height_option',
                'option_type'      => 'input',
                'label'            => __( 'Facebook Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Cannot use with Grid', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_page_height',
                'name'             => 'facebook_page_height',
                'placeholder'      => '450px ' . __( 'for example', 'feed-them-social' )
            ],
            // Show Page Title
            [
                'input_wrap_class' => 'fb-page-title-option-hide',
                'option_type'      => 'select',
                'label'            => __( 'Show Page Title', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_page_title',
                'name'             => 'facebook_page_title',
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options'      => ['sub_options_wrap_class' => 'facebook-title-options-wrap']
            ],
            // Align Title
            [
                'input_wrap_class' => 'fb-page-title-align',
                'option_type'      => 'select',
                'label'            => __( 'Align Title', 'feed-them-social' ) . '<br/><small>' . __( 'Left, Center or Right', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_page_title_align',
                'name'             => 'facebook_page_title_align',
                'options'          => [
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left'],
                    ['label' => __( 'Center', 'feed-them-social' ), 'value' => 'center'],
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right']
                ],
                'req_extensions'   => ['feed_them_social_premium']
            ],
            // Show Page Description
            [
                'input_wrap_class' => 'fb-page-description-option-hide',
                'option_type'      => 'select',
                'label'            => __( 'Show Page Description', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_page_description',
                'name'             => 'facebook_page_description',
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options_end'  => true
            ],
            // Show Image/Video
            [
                'input_wrap_class' => 'facebook_show_media',
                'option_type'      => 'select',
                'label'            => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom or Top of Post', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'facebook_show_media',
                'name'             => 'facebook_show_media',
                'req_extensions'   => ['feed_them_social_premium'],
                'options'          => [
                    [
                        'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
                        'value' => 'bottom'
                    ],
                    ['label' => __( 'Above Username, Date & Description', 'feed-them-social' ), 'value' => 'top']
                ]
            ],
            // Show User Thumbnail
            [
                'input_wrap_class' => 'facebook_hide_thumbnail',
                'option_type'      => 'select',
                'label'            => __( 'Show User Thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'show_thumbnail',
                'name'             => 'show_thumbnail',
                'req_extensions'   => ['feed_them_social_premium'],
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Show Username
            [
                'input_wrap_class' => 'facebook_hide_name',
                'option_type'      => 'select',
                'label'            => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'show_name',
                'name'             => 'show_name',
                'req_extensions'   => ['feed_them_social_premium'],
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Show Date
            [
                'input_wrap_class' => 'facebook_hide_date',
                'option_type'      => 'select',
                'label'            => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'show_date',
                'name'             => 'show_date',
                'req_extensions'   => ['feed_them_social_premium'],
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Amount of words
            [
                'option_type'    => 'input',
                'label'          => __( ' # of Words in Post Description', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'facebook_page_word_count',
                'name'           => 'facebook_page_word_count',
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_facebook_reviews']
            ],
            // Image Width
            [
                'option_type'   => 'input',
                'label'         => __( 'Facebook Image Width', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
                'type'          => 'text',
                'id'            => 'facebook_image_width',
                'name'          => 'facebook_image_width',
                'placeholder'   => '250px',
                'default_value' => '250px',
                'sub_options'   => ['sub_options_wrap_class' => 'fts-super-facebook-options-wrap']
            ],
            // Image Height
            [
                'option_type'   => 'input',
                'label'         => __( 'Facebook Image Height', 'feed-them-social' ) . '<br/><small>' . __( 'Max width is 640px', 'feed-them-social' ) . '</small>',
                'type'          => 'text',
                'id'            => 'facebook_image_height',
                'name'          => 'facebook_image_height',
                'placeholder'   => '250px',
                'default_value' => '250px'
            ],
            // Space between photos
            [
                'option_type' => 'input',
                'label'       => __( 'The space between photos', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'facebook_space_between_photos',
                'name'        => 'facebook_space_between_photos',
                'placeholder' => '1px'
            ],
            // Hide Date, Likes and Comments
            [
                'option_type' => 'select',
                'label'       => __( 'Hide Date, Likes and Comments', 'feed-them-social' ),
                'label_note'  => __( 'Good for image sizes under 120px', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'facebook_hide_date_likes_comments',
                'name'        => 'facebook_hide_date_likes_comments',
                'options'     => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Center Facebook Container
            [
                'option_type'     => 'select',
                'label'           => __( 'Center Facebook Container', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'facebook_container_position',
                'name'            => 'facebook_container_position',
                'options'         => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options_end' => true
            ],
            // Image Stacking Animation
            [
                'option_type'     => 'input',
                'label'           => __( 'Image Stacking Animation On', 'feed-them-social' ),
                'label_note'      => __( 'This happens when resizing browser', 'feed-them-social' ),
                'type'            => 'hidden',
                'class'           => 'non-visible',
                'id'              => 'facebook_container_animation',
                'name'            => 'facebook_container_animation',
                'value'           => 'no',
                'sub_options'     => ['sub_options_wrap_class' => 'facebook-image-animation-option-wrap'],
                'sub_options_end' => true
            ],
            // Align Images
            [
                'input_wrap_id'   => 'facebook_align_images_wrapper',
                'option_type'     => 'select',
                'label'           => __( 'Align Images', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'facebook_align_images',
                'name'            => 'facebook_align_images',
                'options'         => [
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left'],
                    ['label' => __( 'Center', 'feed-them-social' ), 'value' => 'center'],
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right']
                ],
                'sub_options'     => ['sub_options_wrap_class' => 'align-images-wrap'],
                'sub_options_end' => true
            ],
            // Overall Rating
            [
                'grouped_options_title' => __( 'Reviews', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Overall Rating above Feed', 'feed-them-social' ) . '<br/><small>' . __( 'More under the Styles Tab', 'feed-them-social' ) . '</small>',
                'type'                  => 'text',
                'id'                    => 'reviews_overall_rating_show',
                'name'                  => 'reviews_overall_rating_show',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_facebook_reviews'],
                'sub_options'           => ['sub_options_wrap_class' => 'facebook-reviews-wrap']
            ],
            // Hide Reviews with no Text
            [
                'option_type'    => 'select',
                'label'          => __( 'Hide Reviews with no description', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'remove_reviews_with_no_description',
                'name'           => 'remove_reviews_with_no_description',
                'options'        => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions' => ['feed_them_social_facebook_reviews']
            ],
            // Hide "See More Reviews"
            [
                'option_type'     => 'select',
                'label'           => __( 'Hide the text "See More Reviews"', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'hide_see_more_reviews_link',
                'name'            => 'hide_see_more_reviews_link',
                'options'         => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'  => ['feed_them_social_facebook_reviews'],
                'sub_options_end' => true
            ],
            // Hide Like Box or Button
            [
                'grouped_options_title' => __( 'Like Box', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Hide Like Box or Button', 'feed-them-social' ) . '<br/><small>' . __( 'More under the Styles Tab', 'feed-them-social' ) . '</small>',
                'type'                  => 'text',
                'id'                    => 'facebook_hide_like_box_button',
                'name'                  => 'facebook_hide_like_box_button',
                'options'               => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options'           => ['sub_options_wrap_class' => 'main-like-box-wrap']
            ],
            // Position of Like Box or Button
            [
                'option_type'    => 'select',
                'label'          => __( 'Position of Like Box or Button', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'facebook_position_likebox',
                'name'           => 'facebook_position_likebox',
                'default_value'  => 'above_title',
                'options'        => [
                    ['label' => __( 'Above Title', 'feed-them-social' ), 'value' => 'above_title'],
                    ['label' => __( 'Below Title', 'feed-them-social' ), 'value' => 'below_title'],
                    ['label' => __( 'Bottom of Feed', 'feed-them-social' ), 'value' => 'bottom']
                ],
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'    => ['sub_options_wrap_class' => 'like-box-wrap']
            ],
            // Align Like Box or Button
            [
                'option_type'    => 'select',
                'label'          => __( 'Align Like Box or Button', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'facebook_align_likebox',
                'name'           => 'facebook_align_likebox',
                'options'        => [
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left'],
                    ['label' => __( 'Center', 'feed-them-social' ), 'value' => 'center'],
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right']
                ],
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_facebook_reviews']
            ],
            // Width of Like Box
            [
                'option_type'     => 'input',
                'label'           => __( 'Width of Like Box', 'feed-them-social' ) . '<br/><small>' . __( 'This only works for the Like Box', 'feed-them-social' ) . '</small>',
                'type'            => 'text',
                'id'              => 'facebook_like_box_width',
                'name'            => 'facebook_like_box_width',
                'placeholder'     => __( '500px max', 'feed-them-social' ),
                'req_extensions'  => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options_end' => 2
            ],
            // Display Photos in Popup
            [
                'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Display Photos in Popup', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'facebook_popup',
                'name'                  => 'facebook_popup',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium'],
                'sub_options'           => ['sub_options_wrap_class' => 'facebook-popup-wrap'],
                'sub_options_end'       => true
            ],
            // Hide Comments in Popup
            [
                'option_type'     => 'select',
                'label'           => __( 'Hide Comments in Popup', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'facebook_popup_comments',
                'name'            => 'facebook_popup_comments',
                'options'         => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'  => ['feed_them_social_premium'],
                'sub_options'     => ['sub_options_wrap_class' => 'display-comments-wrap'],
                'sub_options_end' => true
            ],
            // Load More Button
            [
                'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => sprintf( esc_html( 'Load More Button%1$sDoes not work with Carousel/Slider%2$s', 'feed-them-social' ), '<small>', '</small>' ),
                'type'                  => 'text',
                'id'                    => 'facebook_load_more',
                'name'                  => 'facebook_load_more',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'           => ['sub_options_wrap_class' => 'facebook-loadmore-wrap']
            ],
            // Load More Style
            [
                'option_type'        => 'select',
                'label'              => __( 'Load More Style', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'facebook_load_more_style',
                'name'               => 'facebook_load_more_style',
                'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option displays "Load More Posts" button below feed. The AutoScroll option loads more posts when user reaches the bottom of feed. AutoScroll ONLY works if option is filled in a Fixed Height for feed.', 'feed-them-social' ),
                'options'            => [
                    ['label' => __( 'Button', 'feed-them-social' ), 'value' => 'button'],
                    ['label' => __( 'AutoScroll', 'feed-them-social' ), 'value' => 'autoscroll']
                ],
                'req_extensions'     => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-facebook-load-more-options-wrap'],
                'sub_options_end'    => true
            ],
            // Load more Button Width
            [
                'option_type'    => 'input',
                'label'          => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'facebook_loadmore_button_width',
                'name'           => 'facebook_loadmore_button_width',
                'placeholder'    => '300px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'    => ['sub_options_wrap_class' => 'fts-facebook-load-more-options2-wrap']
            ],
            // Load more Button Margin
            [
                'option_type'     => 'input',
                'label'           => __( 'Load more Button Margin', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'loadmore_button_margin',
                'name'            => 'loadmore_button_margin',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions'  => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options_end' => 2
            ],
            // Display Posts in Grid
            [
                'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
                'input_wrap_class'      => 'fb-posts-in-grid-option-wrap',
                'option_type'           => 'select',
                'label'                 => __( 'Display Posts in Grid', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'facebook_grid',
                'name'                  => 'facebook_grid',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'           => ['sub_options_wrap_class' => 'main-grid-options-wrap']
            ],
            // Grid Column Width
            [
                'option_type'        => 'input',
                'label'              => __( 'Grid Column Width', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'facebook_grid_column_width',
                'name'               => 'facebook_grid_column_width',
                'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define width and space between each post. You must add px after number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/documentation/custom-css-responsive-grid/" target="_blank">', '</a>' ),
                'placeholder'        => '310px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions'     => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-facebook-grid-options-wrap']
            ],
            // Grid Spaces Between Posts
            [
                'option_type'     => 'input',
                'label'           => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'facebook_grid_space_between_posts',
                'name'            => 'facebook_grid_space_between_posts',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions'  => ['feed_them_social_premium', 'feed_them_social_facebook_reviews'],
                'sub_options_end' => 2
            ],
            // Video Play Button
            [
                'grouped_options_title' => __( 'Video Button Options', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Video Play Button', 'feed-them-social' ) . '<br/><small>' . __( 'Displays over Video Thumbnail', 'feed-them-social' ) . '</small>',
                'type'                  => 'text',
                'id'                    => 'facebook_show_video_button',
                'name'                  => 'facebook_show_video_button',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium'],
                'sub_options'           => ['sub_options_wrap_class' => 'fb-video-play-btn-options-wrap']
            ],
            // Size of the Play Button
            [
                'option_type'    => 'input',
                'label'          => __( 'Size of the Play Button', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'facebook_size_video_play_btn',
                'name'           => 'facebook_size_video_play_btn',
                'placeholder'    => '40px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions' => ['feed_them_social_premium'],
                'sub_options'    => ['sub_options_wrap_class' => 'fb-video-play-btn-options-content']
            ],
            // Show Play Button in Front
            [
                'option_type'     => 'select',
                'label'           => __( 'Show Play Button in Front', 'feed-them-social' ) . '<br/><small>' . __( 'Displays before hovering over thumbnail', 'feed-them-social' ) . '</small>',
                'type'            => 'text',
                'id'              => 'facebook_show_video_button_in_front',
                'name'            => 'facebook_show_video_button_in_front',
                'options'         => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'  => ['feed_them_social_premium'],
                'sub_options_end' => 2
            ],
            // Carousel/Slideshow
            [
                'grouped_options_title' => __( 'Carousel/Slider', 'feed-them-social' ),
                'input_wrap_id'         => 'facebook_slider',
                'instructional-text'    => __( 'Create Carousel or Slideshow with these options.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/facebook-carousels-or-sliders/" target="_blank">' . __( 'View Demos', 'feed-them-social' ) . '</a> ',
                'option_type'           => 'select',
                'label'                 => __( 'Carousel/Slideshow', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'fts-slider',
                'name'                  => 'fts-slider',
                'options'               => [
                    ['label' => __( 'Off', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'On', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_carousel_premium'],
                'sub_options'           => ['sub_options_wrap_class' => 'slideshow-wrap']
            ],
            // Carousel/Slideshow Type
            [
                'input_wrap_id'  => 'facebook_scrollhorz_or_carousel',
                'option_type'    => 'select',
                'label'          => __( 'Type', 'feed-them-social' ),
                'type'           => 'text',
                'default_value'  => 'carousel',
                'id'             => 'scrollhorz_or_carousel',
                'name'           => 'scrollhorz_or_carousel',
                'options'        => [
                    ['label' => __( 'Carousel', 'feed-them-social' ), 'value' => 'carousel'],
                    ['label' => __( 'Slideshow', 'feed-them-social' ), 'value' => 'scrollhorz']
                ],
                'req_extensions' => ['feed_them_carousel_premium'],
                'sub_options'    => ['sub_options_wrap_class' => 'slider_options_wrap']
            ],
            // Carousel Slides Visible
            [
                'input_wrap_id'  => 'facebook_slides_visible',
                'option_type'    => 'input',
                'label'          => __( 'Carousel Slides Visible', 'feed-them-social' ) . '<br/><small>' . __( 'Not for Slideshow. Example: 1-500', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'slides_visible',
                'name'           => 'slides_visible',
                'default_value'  => '3',
                'placeholder'    => __( '3 is the default number', 'feed-them-social' ),
                'req_extensions' => ['feed_them_carousel_premium'],
                'sub_options'    => ['sub_options_wrap_class' => 'slider_carousel_wrap']
            ],
            // Spacing in between Slides
            [
                'input_wrap_id'   => 'facebook_slider_spacing',
                'option_type'     => 'input',
                'label'           => __( 'Spacing between Slides', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'slider_spacing',
                'name'            => 'slider_spacing',
                'placeholder'     => __( '2px', 'feed-them-social' ),
                'req_extensions'  => ['feed_them_carousel_premium'],
                'sub_options_end' => true
            ],
            // Slider Speed
            [
                'input_wrap_id'  => 'facebook_slider_speed',
                'option_type'    => 'input',
                'label'          => __( 'Slider Speed', 'feed-them-social' ) . '<br/><small>' . __( 'How fast slides change', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'slider_speed',
                'name'           => 'slider_speed',
                'placeholder'    => __( '0-10000', 'feed-them-social' ),
                'req_extensions' => ['feed_them_carousel_premium']
            ],
            // Slider Timeout
            [
                'input_wrap_id'  => 'facebook_slider_timeout',
                'option_type'    => 'input',
                'label'          => __( 'Slider Timeout', 'feed-them-social' ) . '<br/><small>' . __( 'Amount of Time before next slide.', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'slider_timeout',
                'name'           => 'slider_timeout',
                'placeholder'    => __( '0-10000', 'feed-them-social' ),
                'req_extensions' => ['feed_them_carousel_premium']
            ],
            // Slider Controls
            [
                'input_wrap_id'  => 'facebook_slider_controls',
                'option_type'    => 'select',
                'label'          => __( 'Slider Controls', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'slider_controls',
                'name'           => 'slider_controls',
                'default_value'  => 'dots_arrows_and_numbers_below_feed',
                'options'        => [
                    ['label' => __( 'Dots above Feed', 'feed-them-social' ), 'value' => 'dots_above_feed'],
                    [
                        'label' => __( 'Dots and Arrows above Feed', 'feed-them-social' ),
                        'value' => 'dots_and_arrows_above_feed'
                    ],
                    [
                        'label' => __( 'Dots and Numbers above Feed', 'feed-them-social' ),
                        'value' => 'dots_and_numbers_above_feed'
                    ],
                    [
                        'label' => __( 'Dots, Arrows and Numbers above Feed', 'feed-them-social' ),
                        'value' => 'dots_arrows_and_numbers_above_feed'
                    ],
                    [
                        'label' => __( 'Arrows and Numbers above feed', 'feed-them-social' ),
                        'value' => 'arrows_and_numbers_above_feed'
                    ],
                    ['label' => __( 'Arrows above Feed', 'feed-them-social' ), 'value' => 'arrows_above_feed'],
                    ['label' => __( 'Numbers above Feed', 'feed-them-social' ), 'value' => 'numbers_above_feed'],
                    ['label' => __( 'Dots below Feed', 'feed-them-social' ), 'value' => 'dots_below_feed'],
                    [
                        'label' => __( 'Dots and Arrows below Feed', 'feed-them-social' ),
                        'value' => 'dots_and_arrows_below_feed'
                    ],
                    [
                        'label' => __( 'Dots and Numbers below Feed', 'feed-them-social' ),
                        'value' => 'dots_and_numbers_below_feed'
                    ],
                    [
                        'label' => __( 'Dots, Arrows and Numbers below Feed', 'feed-them-social' ),
                        'value' => 'dots_arrows_and_numbers_below_feed'
                    ],
                    ['label' => __( 'Arrows below Feed', 'feed-them-social' ), 'value' => 'arrows_below_feed'],
                    ['label' => __( 'Numbers Below Feed', 'feed-them-social' ), 'value' => 'numbers_below_feed']
                ],
                'req_extensions' => ['feed_them_carousel_premium']
            ],
            // Slider Controls Text Color
            [
                'input_wrap_id'    => 'facebook_slider_controls_text_color',
                'input_wrap_class' => 'fts-color-picker',
                'option_type'      => 'input',
                'label'            => __( 'Slider Controls Text Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'slider_controls_text_color',
                'name'             => 'slider_controls_text_color',
                'default_value'    => '#828282',
                'placeholder'      => '#FFF',
                'req_extensions'   => ['feed_them_carousel_premium']
            ],
            // Slider Controls Bar Color
            [
                'input_wrap_id'    => 'facebook_slider_controls_bar_color',
                'input_wrap_class' => 'fts-color-picker',
                'option_type'      => 'input',
                'label'            => __( 'Slider Controls Bar Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'slider_controls_bar_color',
                'name'             => 'slider_controls_bar_color',
                'default_value'    => '#f2f2f2',
                'placeholder'      => '#000',
                'req_extensions'   => ['feed_them_carousel_premium']
            ],
            // Slider Controls Max Width
            [
                'input_wrap_id'   => 'facebook_slider_controls_width',
                'option_type'     => 'input',
                'label'           => __( 'Slider Controls Max Width', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'slider_controls_width',
                'name'            => 'slider_controls_width',
                'default_value'   => '320px',
                'placeholder'     => '320px',
                'req_extensions'  => ['feed_them_carousel_premium'],
                'sub_options_end' => 2
            ],
        ];

        $this->allOptions['facebook'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'facebook_',
            'section_wrap_class' => 'fts-facebook_page-shortcode-form',
            'form_wrap_classes'  => 'fts-fb-page-shortcode-form',
            'form_wrap_id'       => 'fts-fb-page-form',
            'premium_msg_boxes'  => [
                'album_videos' => [
                    'req_plugin' => 'feed_them_social_premium',
                    'msg'        => 'The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your WordPress site! <a href="https://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a><br /><br />Additionally if you purchase the Carousel Plugin you can showcase your videos in a slideshow or carousel. Works with your Facebook Photos too! <a href="https://feedthemsocial.com/facebook-carousels/" target="_blank">View Carousel Demo</a>',
                ],
                'reviews'      => [
                    'req_plugin' => 'facebook_reviews',
                    'msg'        => 'The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="https://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a>',
                ],
            ],
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['facebook'];
    }

    /**
     * Instagram Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function instagramOptions ()
    {
        $main_options = [
            // Feed Type
            [
                'option_type'      => 'select',
                'label'            => __( 'Feed Type', 'feed-them-social' ),
                'type'             => 'text',
                'input_wrap_class' => 'instagram_feed_type',
                'id'               => 'instagram_feed_type',
                'name'             => 'instagram_feed_type',
                'options'          => [
                    ['label' => __( 'Basic', 'feed-them-social' ), 'value' => 'basic'],
                    ['label' => __( 'Business', 'feed-them-social' ), 'value' => 'business'],
                    ['label' => __( 'Hashtag', 'feed-them-social' ), 'value' => 'hashtag']
                ]
            ],
            // Hashtag
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'instagram_hashtag',
                'label'              => [
                    [
                        'text'  => __( 'Hashtag', 'feed-them-social' ),
                        'class' => 'instagram-hashtag-option-text'
                    ]
                ],
                'type'               => 'text',
                'id'                 => 'instagram_hashtag',
                'name'               => 'instagram_hashtag',
                'required'           => 'yes',
                'instructional-text' => [
                    [
                        'text'  => __( 'Add your hashtag below. <strong>DO NOT</strong> add the #, just the name. Only one hashtag allowed at this time. Hashtag media only stays on Instagram for 24 hours and the API does not give us a date/time. In order to use the Instagram hashtag feed you must have your Instagram account linked to a Facebook Business Page. <a target="_blank" href="https://www.slickremix.com/docs/link-instagram-account-to-facebook/">Read Instructions.</a>', 'feed-them-social' ),
                        'class' => 'instagram-hashtag-option-text'
                    ]
                ],
                'req_extensions'     => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
            // Hashtag Type
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'instagram_hashtag_type',
                'label'            => __( 'Hashtag Search Type', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_hashtag_type',
                'name'             => 'instagram_hashtag_type',
                'class'            => 'instagram-hashtag-type',
                'options'          => [
                    [
                        'label' => __( 'Top Media (Most Interactions)', 'feed-them-social' ),
                        'value' => 'top-media'
                    ],
                    ['label' => __( 'Recent Media', 'feed-them-social' ), 'value' => 'recent-media']
                ],
                'req_extensions'   => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
            // Pic Count
            [
                'option_type'   => 'input',
                'label'         => __( 'Number of Posts<div class="fts-paid-extension-required"><small>More than 6 requires our <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium</a> or <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-instagram-slider/">Instagram Slider</a> Extension.</small></div>', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'instagram_pics_count',
                'name'          => 'instagram_pics_count',
                'default_value' => '6',
                'placeholder'   => __( '6 is default value', 'feed-them-social' )
            ],
            // Feed Type
            ['option_type' => 'select', 'id' => 'instagram_feed_type', 'no_html' => 'yes'],
            // Width
            [
                'input_wrap_class' => 'instagram_width_option',
                'option_type'      => 'input',
                'label'            => __( 'Gallery Width', 'feed-them-social' ),
                'label_note'       => __( 'Leave blank for auto height', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_page_width',
                'name'             => 'instagram_page_width',
                'placeholder'      => '50% or 450px ' . __( 'for example', 'feed-them-social' )
            ],
            // Fixed Height
            [
                'input_wrap_class' => 'instagram_fixed_height_option',
                'option_type'      => 'input',
                'label'            => __( 'Gallery Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Creates scrolling feed.', 'feed-them-social' ) . '</small>',
                'label_note'       => __( 'Cannot use with Grid', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_page_height',
                'name'             => 'instagram_page_height',
                'placeholder'      => '450px ' . __( 'for example', 'feed-them-social' )
            ],
            // Profile Wrap
            [
                'grouped_options_title' => __( 'Profile', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Show Profile Info', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'instagram_profile_wrap',
                'name'                  => 'instagram_profile_wrap',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'sub_options'           => ['sub_options_wrap_class' => 'main-instagram-profile-options-wrap']
            ],
            // Profile Photo
            [
                'option_type' => 'select',
                'label'       => __( 'Show Profile Photo', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'instagram_profile_photo',
                'name'        => 'instagram_profile_photo',
                'options'     => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'sub_options' => ['sub_options_wrap_class' => 'instagram-profile-options-wrap']
            ],
            // Profile Stats
            [
                'option_type' => 'select',
                'label'       => __( 'Show Profile Stats', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'instagram_profile_stats',
                'name'        => 'instagram_profile_stats',
                'options'     => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Profile Name
            [
                'option_type' => 'select',
                'label'       => __( 'Show Profile Name', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'instagram_profile_name',
                'name'        => 'instagram_profile_name',
                'options'     => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Profile Description
            [
                'option_type'     => 'select',
                'label'           => __( 'Show Profile Description', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'instagram_profile_description',
                'name'            => 'instagram_profile_description',
                'options'         => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'sub_options_end' => 2
            ],
            // Slideshow
            [
                'grouped_options_title' => __( 'Slideshow', 'feed-them-social' ),
                'input_wrap_id'         => 'instagram-slider-wrap',
                'instructional-text'    => __( 'Create a slideshow with these options.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/instagram-sliders/" target="_blank">' . __( 'View Demo', 'feed-them-social' ) . '</a>',
                'option_type'           => 'select',
                'label'                 => __( 'Slideshow', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'instagram_slider',
                'name'                  => 'instagram_slider',
                'options'               => [
                    ['label' => __( 'Off', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'On', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_instagram_slider']
            ],
            // Slider Speed
            [
                'input_wrap_id'  => 'instagram_slider_speed',
                'option_type'    => 'input',
                'label'          => __( 'Slider Speed', 'feed-them-social' ) . '<br/><small>' . __( 'How fast slides change', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'instagram_slider_speed',
                'name'           => 'instagram_slider_speed',
                'placeholder'    => __( '0-10000', 'feed-them-social' ),
                'req_extensions' => ['feed_them_social_instagram_slider'],
                'sub_options'    => ['sub_options_wrap_class' => 'instagram_slider_options_wrap']
            ],
            // Slider Controls
            [
                'input_wrap_id'  => 'instagram_slider_dots_arrows_controls',
                'option_type'    => 'select',
                'label'          => __( 'Slider Controls', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'instagram_slider_controls',
                'name'           => 'instagram_slider_controls',
                'default_value'  => 'navigation_arrows',
                'options'        => [
                    ['label' => __( 'Arrows', 'feed-them-social' ), 'value' => 'navigation_arrows'],
                    ['label' => __( 'Navigation Dots', 'feed-them-social' ), 'value' => 'navigation_dots'],
                    [
                        'label' => __( 'Navigation Dots and Arrows', 'feed-them-social' ),
                        'value' => 'navigation_dots_and_arrows'
                    ]
                ],
                'req_extensions' => ['feed_them_social_instagram_slider']
            ],
            // Arrows Size
            [
                'input_wrap_class' => 'instagram_slider_arrows_size',
                'option_type'      => 'input',
                'label'            => __( 'Arrows Size', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_slider_arrows_size',
                'name'             => 'instagram_slider_arrows_size',
                'placeholder'      => '30',
                'req_extensions'   => ['feed_them_social_instagram_slider']
            ],
            // Navigation Dots Margin
            [
                'input_wrap_class' => 'instagram_dots_margin',
                'option_type'      => 'input',
                'label'            => __( 'Navigation Dots Margin', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_slider_nav_dots_margin',
                'name'             => 'instagram_slider_nav_dots_margin',
                'placeholder'      => '20px 0px 20px 0px',
                'req_extensions'   => ['feed_them_social_instagram_slider']
            ],
            // Edge Padding
            [
                'option_type'    => 'input',
                'label'          => __( 'Edge Padding', 'feed-them-social' ) . '<br/><small>' . __( 'Reveal photos on the right and left of the slideshow if they are not visible.', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'instagram_slider_edge_padding',
                'name'           => 'instagram_slider_edge_padding',
                'placeholder'    => '20',
                'req_extensions' => ['feed_them_social_instagram_slider']
            ],
            // Slideshow Padding
            [
                'option_type'     => 'input',
                'label'           => __( 'Padding', 'feed-them-social' ) . '<br/><small>' . __( 'Add padding around the slideshow.', 'feed-them-social' ) . '</small>',
                'type'            => 'text',
                'id'              => 'instagram_slider_padding',
                'name'            => 'instagram_slider_padding',
                'placeholder'     => '40px',
                'req_extensions'  => ['feed_them_social_instagram_slider'],
                'sub_options_end' => true
            ],
            // Photo Options
            [
                'grouped_options_title' => __( 'Photo Options', 'feed-them-social' ),
                'input_wrap_class'      => 'fb-page-columns-option-hide fts-responsive-options',
                'instructional-text'    => '<strong>' . __( 'NOTE: ', 'feed-them-social' ) . '</strong>' . __( 'Choose the number of photos in a row and the space between each photo.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/instagram-feed-demo/" target="_blank">' . __( 'View demo', 'feed-them-social' ) . '</a>',
                'sub_options'           => ['sub_options_wrap_class' => 'fts-super-instagram-options-wrap fts-responsive-wrap']
            ],
            // Columns - Desktop
            [
                'option_type'      => 'select',
                'input_wrap_class' => self::RESPONSIVE_DESKTOP_COLUMNS_WRAP_CLASS,
                'label'            => __( 'Photos - Desktop', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_columns',
                'name'             => 'instagram_columns',
                'default_value'    => '3',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Columns - Tablet
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'responsive-columns-tablet-wrap',
                'label'            => __( 'Photos - Tablet', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_columns_tablet',
                'name'             => 'instagram_columns_tablet',
                'default_value'    => '2',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Columns - Mobile
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'responsive-columns-mobile-wrap',
                'label'            => __( 'Photos - Mobile', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'instagram_columns_mobile',
                'name'             => 'instagram_columns_mobile',
                'default_value'    => '1',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Image Height
            [
                'option_type' => 'input',
                'label'       => __( 'Height of Image (Portrait)', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank to make image squared', 'feed-them-social' ) . '</small>',
                'label_note'  => __( 'Adjust the height of thumbnail', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'instagram_image_height',
                'name'        => 'instagram_image_height',
                'placeholder' => '120px for example'
            ],
            // Space between Photos
            [
                'option_type' => 'input',
                'label'       => __( 'The space between photos', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'instagram_space_between_photos',
                'name'        => 'instagram_space_between_photos',
                'placeholder' => '1px'
            ],
            // Icon Size
            [
                'option_type'   => 'input',
                'label'         => __( 'Size of Instagram Icon', 'feed-them-social' ),
                'label_note'    => __( 'Visible when hovering over photo', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'instagram_icon_size',
                'name'          => 'instagram_icon_size',
                'default_value' => '65px',
                'placeholder'   => '65px'
            ],
            // Date, Heart & Comment icon
            [
                'option_type'     => 'select',
                'label'           => __( 'Date, Heart & Comment icon', 'feed-them-social' ) . '<br/><small>' . __( 'Heart & Comment counts only work with the Business Feed type.', 'feed-them-social' ) . '</small>',
                'label_note'      => __( 'Good for image sizes under 120px', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'instagram_hide_date_likes_comments',
                'name'            => 'instagram_hide_date_likes_comments',
                'options'         => [
                    ['label' => __( 'Show', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'Hide', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options_end' => true
            ],
            // Load More
            [
                'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
                'input_wrap_class'      => 'fts-instagram-load-more-option',
                'option_type'           => 'select',
                'label'                 => __( 'Load more posts', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'instagram_load_more_option',
                'name'                  => 'instagram_load_more_option',
                'prem_req'              => 'yes',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
            // Load More Style
            [
                'option_type'        => 'select',
                'label'              => __( 'Load more style', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'instagram_load_more_style',
                'name'               => 'instagram_load_more_style',
                'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option displays "Load More Posts" button below feed. The AutoScroll option loads more posts when user reaches the bottom of feed. AutoScroll ONLY works if option is filled in a Fixed Height for feed.', 'feed-them-social' ),
                'options'            => [
                    ['label' => __( 'Button', 'feed-them-social' ), 'value' => 'button'],
                    ['label' => __( 'AutoScroll', 'feed-them-social' ), 'value' => 'autoscroll']
                ],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-instagram-load-more-options-wrap']
            ],
            // Load more Button Width
            [
                'option_type'    => 'input',
                'label'          => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'instagram_loadmore_button_width',
                'name'           => 'instagram_loadmore_button_width',
                'placeholder'    => '300px ' . __( 'for example', 'feed-them-social' ),
                'default_value'  => '300px',
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
            // Load more Button Margin
            [
                'option_type'    => 'input',
                'label'          => __( 'Load more Button Margin', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'instagram_loadmore_button_margin',
                'name'           => 'instagram_loadmore_button_margin',
                'placeholder'    => '10px ' . __( 'for example', 'feed-them-social' ),
                'default_value'  => '10px',
                'req_extensions' => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
            // Load more Count
            [
                'input_wrap_class' => 'fts-instagram-load-more-count-option-wrap',
                'option_type'      => 'select',
                'label'            => __( 'Load more Count', 'feed-them-social' ) . '<br/><small>' . __( 'Display the images loaded and total images on your account. ie* 8 of 200. Only works with the Basic Feed type.', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'instagram_loadmore_count',
                'name'             => 'instagram_loadmore_count',
                'req_extensions'   => ['feed_them_social_premium', 'feed_them_social_instagram_slider'],
                'options'          => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'sub_options_end'  => 1
            ],
            // Popup
            [
                'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Display Photos & Videos', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'instagram_popup_option',
                'name'                  => 'instagram_popup_option',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_premium', 'feed_them_social_instagram_slider']
            ],
        ];

        $this->allOptions['instagram'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'instagram_',
            'section_wrap_class' => 'fts-instagram-shortcode-form',
            'form_wrap_classes'  => 'instagram-shortcode-form',
            'form_wrap_id'       => 'fts-instagram-form',
            'token_check'        => [['option_name' => 'fts_instagram_custom_api_token']],
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['instagram'];
    }

    /**
     * Twitter Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function twitterOptions ()
    {
        $limitforpremium = __( '<div class="fts-paid-extension-required"><small>More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium</a></small></div>', 'feed-them-social' );
        $main_options = [
            // Feed Type
            [
                'option_type'      => 'select',
                'label'            => __( 'Feed Type', 'feed-them-social' ),
                'type'             => 'text',
                'input_wrap_class' => 'twitter-messages-selector',
                'id'               => 'twitter-messages-selector',
                'name'             => 'twitter-messages-selector',
                'options'          => [
                    ['label' => __( 'Responsive Feed', 'feed-them-social' ), 'value' => 'responsive'],
                    ['label' => __( 'Classic Feed', 'feed-them-social' ), 'value' => 'classic']
                ]
            ],
            // Search Name
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'twitter_hashtag_etc_name',
                'label'              => __( 'Twitter Search Name', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'twitter_hashtag_etc_name',
                'name'               => 'twitter_hashtag_etc_name',
                'instructional-text' => sprintf( __( 'You can use %%1$s, %%2$s, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the %%3$s or %%4$s characters. NOTE: Only displays last 7 days worth of Tweets. %%5$s', 'feed-them-social' ), '#hashtag', '@person', 'from:', '%#', '<strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>' ),
                'sub_options'        => [
                    'sub_options_wrap_class' => 'twitter-hashtag-etc-wrap',
                    'sub_options_title'      => __( 'Search', 'feed-them-social' )
                ],
                'sub_options_end'    => true
            ],
            // Tweet Count
            [
                'option_type' => 'input',
                'label'       => __( '# of Videos', 'feed-them-social' ) . $limitforpremium,
                'type'        => 'text',
                'id'          => 'tweets_count',
                'name'        => 'tweets_count',
                'placeholder' => __( '6 is default value', 'feed-them-social' )
            ],
            // Fixed Height
            [
                'option_type' => 'input',
                'label'       => __( 'Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Scroll Feed. Cannot use with Grid', 'feed-them-social' ) . '</small>',
                'type'        => 'text',
                'id'          => 'twitter_height',
                'name'        => 'twitter_height',
                'placeholder' => '450px ' . __( 'for example', 'feed-them-social' )
            ],
            // Amount of words
            [
                'option_type'    => 'input',
                'label'          => __( ' # of Words in Post Description', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'tiktok_word_count',
                'name'           => 'tiktok_word_count',
                'req_extensions' => ['feed_them_social_tiktok_premium']
            ],
            // Responsive Gallery
            [
                'grouped_options_title' => __( 'Responsive Gallery', 'feed-them-social' ),
                'input_wrap_class'      => 'fb-page-columns-option-hide fts-responsive-options',
                'instructional-text'    => '<strong>' . __( 'NOTE: ', 'feed-them-social' ) . '</strong>' . __( 'Choose the Number of Columns and Space between each image below. Please add px after any number.', 'feed-them-social' ) . ' <a href="https://feedthemsocial.com/tiktok-feed-demo/" target="_blank">' . __( 'View demo', 'feed-them-social' ) . '</a>',
                'sub_options'           => ['sub_options_wrap_class' => 'fts-responsive-tiktok-options-wrap fts-responsive-wrap']
            ],
            // Columns - Desktop
            [
                'option_type'      => 'select',
                'input_wrap_class' => self::RESPONSIVE_DESKTOP_COLUMNS_WRAP_CLASS,
                'label'            => __( 'Columns - Desktop', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_columns',
                'name'             => 'tiktok_columns',
                'default_value'    => '3',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Columns - Tablet
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'responsive-columns-tablet-wrap',
                'label'            => __( 'Columns - Tablet', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_columns_tablet',
                'name'             => 'tiktok_columns_tablet',
                'default_value'    => '2',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Columns - Mobile
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'responsive-columns-mobile-wrap',
                'label'            => __( 'Columns - Mobile', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'tiktok_columns_mobile',
                'name'             => 'tiktok_columns_mobile',
                'default_value'    => '1',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6'],
                    ['label' => __( '7', 'feed-them-social' ), 'value' => '7'],
                    ['label' => __( '8', 'feed-them-social' ), 'value' => '8']
                ]
            ],
            // Image Height
            [
                'option_type'   => 'input',
                'label'         => __( 'Height of Image', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank to make image squared', 'feed-them-social' ) . '</small>',
                'label_note'    => __( 'Adjust the height of thumbnail', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'tiktok_image_height',
                'name'          => 'tiktok_image_height',
                'default_value' => '120px',
                'placeholder'   => '120px for example'
            ],
            // Space between Photos
            [
                'option_type' => 'input',
                'label'       => __( 'The space between photos', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'tiktok_space_between_photos',
                'name'        => 'tiktok_space_between_photos',
                'placeholder' => '1px'
            ],
            // Icon Size
            [
                'option_type'   => 'input',
                'label'         => __( 'Size of TikTok Icon', 'feed-them-social' ),
                'label_note'    => __( 'Visible when hovering over photo', 'feed-them-social' ),
                'type'          => 'text',
                'id'            => 'tiktok_icon_size',
                'name'          => 'tiktok_icon_size',
                'default_value' => '65px',
                'placeholder'   => '65px'
            ],
            // Date, Plays, Heart & Comment
            [
                'option_type'     => 'select',
                'label'           => __( 'Date, Plays, Heart & Comment', 'feed-them-social' ),
                'label_note'      => __( 'Good for image sizes under 120px', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'tiktok_hide_date_likes_comments',
                'name'            => 'tiktok_hide_date_likes_comments',
                'options'         => [
                    ['label' => __( 'Show', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'Hide', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options_end' => true
            ],
            // Popup
            [
                'grouped_options_title' => __( 'Popup', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Display Videos in Popup', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'tiktok_popup_option',
                'name'                  => 'tiktok_popup_option',
                'prem_req'              => 'yes',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_tiktok_premium']
            ],
            // Load More
            [
                'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Load More Button', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'tiktok_load_more_option',
                'name'                  => 'tiktok_load_more_option',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'prem_req'              => 'yes',
                'req_extensions'        => ['feed_them_social_tiktok_premium']
            ],
            // Load More Style
            [
                'option_type'        => 'select',
                'label'              => __( 'Load More Style', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'tiktok_load_more_style',
                'name'               => 'tiktok_load_more_style',
                'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option displays "Load More Posts" button below feed. The AutoScroll option loads more posts when user reaches the bottom of feed. AutoScroll ONLY works if option is filled in a Fixed Height for feed.', 'feed-them-social' ),
                'options'            => [
                    ['label' => __( 'Button', 'feed-them-social' ), 'value' => 'button'],
                    ['label' => __( 'AutoScroll', 'feed-them-social' ), 'value' => 'autoscroll']
                ],
                'prem_req'           => 'yes',
                'req_extensions'     => ['feed_them_social_tiktok_premium'],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-twitter-load-more-options-wrap']
            ],
            // Load more Button Width
            [
                'option_type'    => 'input',
                'label'          => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'tiktok_loadmore_button_width',
                'name'           => 'tiktok_loadmore_button_width',
                'placeholder'    => '300px ' . __( 'for example', 'feed-them-social' ),
                'default_value'  => '300px',
                'prem_req'       => 'yes',
                'req_extensions' => ['feed_them_social_tiktok_premium']
            ],
            // Load more Button Margin
            [
                'option_type'     => 'input',
                'label'           => __( 'Load more Button Margin', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'tiktok_loadmore_button_margin',
                'name'            => 'tiktok_loadmore_button_margin',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'value'           => '10px',
                'req_extensions'  => ['feed_them_social_tiktok_premium'],
                'sub_options_end' => 1
            ],
            // Grid
            [
                'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
                'input_wrap_class'      => 'twitter-posts-in-grid-option-wrap',
                'option_type'           => 'select',
                'label'                 => __( 'Display Posts in Grid', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'tiktok-grid-option',
                'name'                  => 'tiktok_grid_option',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions'        => ['feed_them_social_tiktok_premium'],
                'sub_options'           => ['sub_options_wrap_class' => 'main-grid-options-wrap']
            ],
            // Grid Column Width
            [
                'option_type'        => 'input',
                'label'              => __( 'Grid Column Width', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'tiktok_grid_column_width',
                'name'               => 'tiktok_grid_column_width',
                'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . sprintf( __( 'Define width and space between each post. You must add px after number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/documentation/custom-css-responsive-grid/" target="_blank">', '</a>' ),
                'placeholder'        => '310px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions'     => ['feed_them_social_tiktok_premium'],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-twitter-grid-options-wrap']
            ],
            // Grid Spaces Between Posts
            [
                'option_type'     => 'input',
                'label'           => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'tiktok_grid_space_between_posts',
                'name'            => 'tiktok_grid_space_between_posts',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'req_extensions'  => ['feed_them_social_tiktok_premium'],
                'sub_options_end' => 2
            ],
        ];

        $this->allOptions['twitter'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'twitter_',
            'section_wrap_class' => 'fts-twitter-shortcode-form',
            'form_wrap_classes'  => 'twitter-shortcode-form',
            'form_wrap_id'       => 'fts-twitter-form',
            'token_check'        => [['option_name' => 'fts_tiktok_refresh_token']],
            'feed_type_select'   => [
                'label'       => __( 'Feed Type', 'feed-them-social' ),
                'select_name' => 'twitter-messages-selector',
                'select_id'   => 'twitter-messages-selector'
            ],
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['twitter'];
    }

    /**
     * YouTube Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function youtubeOptions ()
    {
        $limitforpremium = __( '<div class="fts-paid-extension-required"><small>More than 6 Requires <a target="_blank" href="https://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium</a></small></div>', 'feed-them-social' );
        $main_options = [
            // Feed Type
            [
                'option_type'      => 'select',
                'label'            => __( 'Feed Type', 'feed-them-social' ),
                'type'             => 'text',
                'input_wrap_class' => 'youtube-messages-selector',
                'id'               => 'youtube-messages-selector',
                'name'             => 'youtube_feed_type',
                'options'          => [
                    ['label' => __( 'Channel Feed', 'feed-them-social' ), 'value' => 'channelID'],
                    ['label' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ), 'value' => 'playlistID'],
                    ['label' => __( 'User\'s Most Recent Videos', 'feed-them-social' ), 'value' => 'username'],
                    ['label' => __( 'User\'s Specific Playlist', 'feed-them-social' ), 'value' => 'userPlaylist'],
                    [
                        'label' => __( 'Single Video with title, date & description', 'feed-them-social' ),
                        'value' => 'singleID'
                    ]
                ]
            ],
            // Username
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'youtube_name',
                'label'              => __( 'YouTube Username', 'feed-them-social' ),
                'instructional-text' => __( 'You must copy your YouTube <strong>Username</strong> url and paste it below.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/user/nationalgeographic" target="_blank">nationalgeographic</a>',
                'type'               => 'text',
                'id'                 => 'youtube_name',
                'name'               => 'youtube_name'
            ],
            // Playlist ID
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'youtube_playlistID',
                'label'              => __( 'YouTube Playlist ID', 'feed-them-social' ),
                'instructional-text' => __( 'Copy your YouTube <strong>Playlist</strong> and <strong>Channel</strong> url link and paste them below. URLs should look similar to Example urls below. <br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN" target="_blank">_-sySjjthB0&list=PL7V-xVyJYY3cI-A9ZHkl6A3r31yiVz0XN</a><br/><strong>' . __( 'Channel ID:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/channel/UCt16NSYjauKclK67LCXvQyA" target="_blank">UCt16NSYjauKclK67LCXvQyA</a>',
                'type'               => 'text',
                'id'                 => 'youtube_playlistID',
                'name'               => 'youtube_playlistID'
            ],
            // Playlist ID2
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'youtube_playlistID2',
                'label'              => __( 'YouTube Playlist ID ', 'feed-them-social' ),
                'instructional-text' => __( 'Copy your YouTube <strong>Playlist</strong> and <strong>Username</strong> url and paste them below. URLs should look similar to Example urls below.<br/><br/><strong>Playlist ID:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=cxrLRbkOwKs&index=10&list=PLivjPDlt6ApS90YoAu-T8VIj6awyflIym" target="_blank">PLivjPDlt6ApS90YoAu-T8VIj6awyflIym</a><br/><strong>' . __( 'Username:', 'feed-them-social' ) . '</strong> <a href="https://www.youtube.com/user/nationalgeographic" target="_blank">nationalgeographic</a>',
                'type'               => 'text',
                'id'                 => 'youtube_playlistID2',
                'name'               => 'youtube_playlistID2'
            ],
            // Username 2
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'youtube_name2',
                'label'            => __( 'YouTube Username<br/><small>For Subscribe button. More on Style Options tab.</small>', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_name2',
                'name'             => 'youtube_name2'
            ],
            // Channel ID
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'youtube_channelID',
                'label'              => __( 'YouTube Channel ID', 'feed-them-social' ),
                'instructional-text' => __( 'Copy your YouTube <strong>Channel</strong> url and paste it below.<br/><strong>Example:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/channel/UCqhnX4jA0A5paNd1v-zEysw" target="_blank">UCqhnX4jA0A5paNd1v-zEysw</a>',
                'type'               => 'text',
                'id'                 => 'youtube_channelID',
                'name'               => 'youtube_channelID'
            ],
            // Channel ID 2
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'youtube_channelID2',
                'label'            => __( 'YouTube Channel ID<br/><small>For Subscribe button. More on Style Options tab.</small>', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_channelID2',
                'name'             => 'youtube_channelID2'
            ],
            // Single Video ID
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'youtube_singleVideoID',
                'label'              => __( 'Single YouTube Video ID', 'feed-them-social' ),
                'instructional-text' => __( 'Copy your <strong>YouTube Video</strong> url link and paste it below.<br/><strong>Video URL:</strong>', 'feed-them-social' ) . ' <a href="https://www.youtube.com/watch?v=_-sySjjthB0" target="_blank">https://www.youtube.com/watch?v=_-sySjjthB0</a>',
                'type'               => 'text',
                'id'                 => 'youtube_singleVideoID',
                'name'               => 'youtube_singleVideoID'
            ],
            // # of videos
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'youtube_vid_count',
                'label'            => __( '# of videos', 'feed-them-social' ) . $limitforpremium,
                'type'             => 'text',
                'id'               => 'youtube_vid_count',
                'name'             => 'youtube_vid_count',
                'default_value'    => '4',
                'placeholder'      => __( '4 is default value', 'feed-them-social' ),
                'sub_options'      => ['sub_options_wrap_class' => 'fts-youtube-first-video-wrap']
            ],
            // Display First video full size
            [
                'grouped_options_title' => __( 'First Video Display', 'feed-them-social' ),
                'input_wrap_class'      => 'youtube_hide_option',
                'option_type'           => 'select',
                'label'                 => __( 'Display First video full size', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'youtube_first_video',
                'name'                  => 'youtube_first_video',
                'options'               => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ],
                'sub_options_end'       => true
            ],
            // Show Large Video Title
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'youtube_hide_option',
                'label'            => __( 'Show Large Video Title', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_large_vid_title',
                'name'             => 'youtube_large_vid_title',
                'options'          => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Show Large Video Description
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'youtube_hide_option',
                'label'            => __( 'Show Large Video Description', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_large_vid_description',
                'name'             => 'youtube_large_vid_description',
                'options'          => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Click thumbnail to play Video
            [
                'grouped_options_title' => __( 'Video Thumbnails', 'feed-them-social' ),
                'input_wrap_class'      => 'youtube_hide_option',
                'option_type'           => 'select',
                'label'                 => __( 'Click thumbnail to play Video', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'youtube_play_thumbs',
                'name'                  => 'youtube_play_thumbs',
                'options'               => [
                    ['label' => __( 'Play on Page', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'Open in YouTube', 'feed-them-social' ), 'value' => 'no'],
                    [
                        'label' => __( 'Open in Popup (Premium Version Required)', 'feed-them-social' ),
                        'value' => 'popup'
                    ]
                ]
            ],
            // Responsive Options
            [
                'input_wrap_class' => 'youtube_hide_option fts-responsive-options',
                'sub_options'      => ['sub_options_wrap_class' => 'fts-super-instagram-options-wrap fts-responsive-wrap']
            ],
            // Videos in each row - Desktop
            [
                'input_wrap_class' => self::RESPONSIVE_DESKTOP_COLUMNS_WRAP_CLASS,
                'option_type'      => 'select',
                'label'            => __( 'Videos in each row - Desktop', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_columns',
                'name'             => 'youtube_columns',
                'default_value'    => '4',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6']
                ]
            ],
            // Videos in each row - Tablet
            [
                'input_wrap_class' => 'fb-page-columns-option-hide  responsive-columns-tablet-wrap',
                'option_type'      => 'select',
                'label'            => __( 'Videos in each row - Tablet', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_columns_tablet',
                'name'             => 'youtube_columns_tablet',
                'default_value'    => '3',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6']
                ]
            ],
            // Videos in each row - Mobile
            [
                'input_wrap_class' => 'fb-page-columns-option-hide responsive-columns-mobile-wrap',
                'option_type'      => 'select',
                'label'            => __( 'Videos in each row - Mobile', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_columns_mobile',
                'name'             => 'youtube_columns_mobile',
                'default_value'    => '2',
                'options'          => [
                    ['label' => __( '1', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( '2', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( '3', 'feed-them-social' ), 'value' => '3'],
                    ['label' => __( '4', 'feed-them-social' ), 'value' => '4'],
                    ['label' => __( '5', 'feed-them-social' ), 'value' => '5'],
                    ['label' => __( '6', 'feed-them-social' ), 'value' => '6']
                ],
                'sub_options_end'  => true
            ],
            // Hide first thumbnail
            [
                'input_wrap_class' => 'youtube_hide_option',
                'option_type'      => 'select',
                'label'            => __( 'Hide first thumbnail', 'feed-them-social' ) . '<br/><small>' . __( 'Useful if playing videos on the page.', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_omit_first_thumbnail',
                'name'             => 'youtube_omit_first_thumbnail',
                'default_value'    => 'no',
                'options'          => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // Space between Vids
            [
                'input_wrap_class' => 'youtube_hide_option',
                'option_type'      => 'input',
                'label'            => __( 'Space between video thumbnails', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_container_margin',
                'name'             => 'youtube_container_margin',
                'placeholder'      => '1px is the default value'
            ],
            // Force thumbnails rows
            [
                'input_wrap_class' => 'youtube_hide_option',
                'option_type'      => 'select',
                'label'            => __( 'Force thumbnails rows', 'feed-them-social' ) . '<br/><small>' . __( 'No, allows video images to be responsive for smaller devices. Yes, forces selected rows.', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_force_columns',
                'name'             => 'youtube_force_columns',
                'default_value'    => 'no',
                'options'          => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ]
            ],
            // High quality thumbnail images
            [
                'input_wrap_class' => 'youtube_hide_option',
                'option_type'      => 'select',
                'label'            => __( 'High quality thumbnail images', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'youtube_maxres_thumbnail_images',
                'name'             => 'youtube_maxres_thumbnail_images',
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Align Thumbs
            [
                'input_wrap_class' => 'youtube_hide_option',
                'option_type'      => 'select',
                'label'            => __( 'Align Thumbs', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Video', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_thumbs_wrap',
                'name'             => 'youtube_thumbs_wrap',
                'options'          => [
                    ['label' => __( 'Below Video', 'feed-them-social' ), 'value' => 'none'],
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right'],
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left']
                ],
                'prem_req'         => 'yes',
                'req_extensions'   => ['feed_them_social_premium']
            ],
            // Align Title, Description etc.
            [
                'input_wrap_class' => 'youtube_align_comments_wrap',
                'option_type'      => 'select',
                'label'            => __( 'Align Title, Description etc.', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default), Right, or left of Video', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_comments_wrap',
                'name'             => 'youtube_comments_wrap',
                'options'          => [
                    ['label' => __( 'Below Video', 'feed-them-social' ), 'value' => 'none'],
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right'],
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left']
                ],
                'prem_req'         => 'yes',
                'req_extensions'   => ['feed_them_social_premium']
            ],
            // Video/Thumbs width options
            [
                'input_wrap_class' => 'youtube_video_thumbs_display',
                'option_type'      => 'select',
                'label'            => __( 'Video/Thumbs width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_video_thumbs_display',
                'name'             => 'youtube_video_thumbs_display',
                'options'          => [
                    ['label' => __( 'None', 'feed-them-social' ), 'value' => 'none'],
                    ['label' => __( 'Option 1 (Video 80%, Thumbs Container 20%)', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( 'Option 1 (Video 60%, Thumbs Container 40%)', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( 'Option 1 (Video 50%, Thumbs Container 50%)', 'feed-them-social' ), 'value' => '3']
                ],
                'prem_req'         => 'yes',
                'req_extensions'   => ['feed_them_social_premium']
            ],
            // Video/Info width options
            [
                'input_wrap_class' => 'youtube_video_single_info_display',
                'option_type'      => 'select',
                'label'            => __( 'Video/Info width options', 'feed-them-social' ) . '<br/><small>' . __( 'Sizes: 80/20, 60/40 or 50/50', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'youtube_video_comments_display',
                'name'             => 'youtube_video_comments_display',
                'options'          => [
                    ['label' => __( 'None', 'feed-them-social' ), 'value' => 'none'],
                    ['label' => __( 'Option 1 (Video 80%, Info Container 20%)', 'feed-them-social' ), 'value' => '1'],
                    ['label' => __( 'Option 1 (Video 60%, Info Container 40%)', 'feed-them-social' ), 'value' => '2'],
                    ['label' => __( 'Option 1 (Video 50%, Info Container 50%)', 'feed-them-social' ), 'value' => '3']
                ],
                'prem_req'         => 'yes',
                'req_extensions'   => ['feed_them_social_premium']
            ],
            // Load More Button
            [
                'input_wrap_class'      => 'youtube_hide_option',
                'grouped_options_title' => __( 'Load More', 'feed-them-social' ),
                'option_type'           => 'select',
                'label'                 => __( 'Load More Button', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'youtube_load_more_option',
                'name'                  => 'youtube_load_more_option',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'prem_req'              => 'yes',
                'req_extensions'        => ['feed_them_social_premium'],
                'sub_options'           => ['sub_options_wrap_class' => 'youtube-loadmore-wrap']
            ],
            // Load More Style
            [
                'option_type'        => 'select',
                'label'              => __( 'Load More Style', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'youtube_load_more_style',
                'name'               => 'youtube_load_more_style',
                'instructional-text' => '<strong>' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . __( 'The Button option displays "Load More Posts" button below feed. The AutoScroll option loads more posts when user reaches the bottom of feed. AutoScroll ONLY works if option is filled in a Fixed Height for feed.', 'feed-them-social' ),
                'options'            => [
                    ['label' => __( 'Button', 'feed-them-social' ), 'value' => 'button'],
                    ['label' => __( 'AutoScroll', 'feed-them-social' ), 'value' => 'autoscroll']
                ],
                'prem_req'           => 'yes',
                'req_extensions'     => ['feed_them_social_premium'],
                'sub_options'        => ['sub_options_wrap_class' => 'fts-youtube-load-more-options2-wrap']
            ],
            // Load more Button Width
            [
                'option_type'    => 'input',
                'label'          => __( 'Load more Button Width', 'feed-them-social' ) . '<br/><small>' . __( 'Leave blank for auto width', 'feed-them-social' ) . '</small>',
                'type'           => 'text',
                'id'             => 'youtube_loadmore_button_width',
                'name'           => 'youtube_loadmore_button_width',
                'placeholder'    => '300px ' . __( 'for example', 'feed-them-social' ),
                'default_value'  => '300px',
                'prem_req'       => 'yes',
                'req_extensions' => ['feed_them_social_premium']
            ],
            // Load more Button Margin
            [
                'option_type'     => 'input',
                'label'           => __( 'Load more Button Margin', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'youtube_loadmore_button_margin',
                'name'            => 'youtube_loadmore_button_margin',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'default_value'   => '10px',
                'req_extensions'  => ['feed_them_social_premium'],
                'sub_options_end' => 2
            ],
            // # of Comments
            [
                'grouped_options_title' => __( 'Comments', 'feed-them-social' ),
                'option_type'           => 'input',
                'label'                 => __( '# of Comments', 'feed-them-social' ) . '<br/><small>' . __( 'Maximum amount is 50. API Key Required.', 'feed-them-social' ) . '</small>',
                'type'                  => 'text',
                'id'                    => 'youtube_comments_count',
                'name'                  => 'youtube_comments_count',
                'req_extensions'        => ['feed_them_social_premium']
            ],
        ];

        $this->allOptions['youtube'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'youtube_',
            'section_wrap_class' => 'fts-youtube-shortcode-form',
            'form_wrap_classes'  => 'youtube-shortcode-form',
            'form_wrap_id'       => 'fts-youtube-form',
            'token_check'        => [['option_name' => 'youtube_custom_api_token']],
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['youtube'];
    }

    /**
     * Combined Options
     *
     * @return mixed
     * @since 1.0.0
     */
    public function combineOptions ()
    {
        $main_options = [
            // Total # of Posts
            [
                'grouped_options_title' => __( 'Combined Streams', 'feed-them-social' ),
                'option_type'           => 'input',
                'input_wrap_class'      => 'combine_post_count',
                'label'                 => __( 'Total # of Posts', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'combine_post_count',
                'name'                  => 'combine_post_count',
                'default_value'         => '6',
                'placeholder'           => __( '6 is the default number', 'feed-them-social' )
            ],
            // # of Posts per Social Network
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'combine_social_network_post_count',
                'label'            => __( '# of Posts per Social Network', 'feed-them-social' ) . '<br/><small>' . __( 'NOT the combined total', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_social_network_post_count',
                'name'             => 'combine_social_network_post_count',
                'default_value'    => '1',
                'placeholder'      => __( '1 is default number', 'feed-them-social' )
            ],
            // # of Words in Post Description
            [
                'option_type' => 'input',
                'label'       => __( '  # of Words in Post Description', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'combine_word_count_option',
                'name'        => 'combine_word_count_option'
            ],
            // Center Feed Container
            [
                'option_type' => 'select',
                'label'       => __( 'Center Feed Container', 'feed-them-social' ),
                'type'        => 'text',
                'id'          => 'combine_container_position',
                'name'        => 'combine_container_position',
                'options'     => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Feed Fixed Height
            [
                'input_wrap_class' => 'combine_height',
                'option_type'      => 'input',
                'label'            => __( 'Feed Fixed Height', 'feed-them-social' ) . '<br/><small>' . __( 'Cannot use with Grid', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_height',
                'name'             => 'combine_height',
                'placeholder'      => '450px ' . __( 'for example', 'feed-them-social' )
            ],
            // Background Color
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'combine_background_color fts-color-picker',
                'label'            => __( 'Background Color', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_background_color',
                'name'             => 'combine_background_color'
            ],
            // Show Social Icon
            [
                'input_wrap_class' => 'combine_show_social_icon',
                'option_type'      => 'select',
                'label'            => __( 'Show Social Icon', 'feed-them-social' ) . '<br/><small>' . __( 'Right, Left or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_show_social_icon',
                'name'             => 'combine_show_social_icon',
                'options'          => [
                    ['label' => __( 'Right', 'feed-them-social' ), 'value' => 'right'],
                    ['label' => __( 'Left', 'feed-them-social' ), 'value' => 'left'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Show Image/Video
            [
                'input_wrap_class' => 'combine_show_media',
                'option_type'      => 'select',
                'label'            => __( 'Show Image/Video', 'feed-them-social' ) . '<br/><small>' . __( 'Bottom (default) or Top of Post', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_show_media',
                'name'             => 'combine_show_media',
                'options'          => [
                    [
                        'label' => __( 'Below Username, Date & Description', 'feed-them-social' ),
                        'value' => 'bottom'
                    ],
                    ['label' => __( 'Above Username, Date & Description', 'feed-them-social' ), 'value' => 'top']
                ]
            ],
            // Show Date
            [
                'input_wrap_class' => 'combine_hide_date',
                'option_type'      => 'select',
                'label'            => __( 'Show Date', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_hide_date',
                'name'             => 'combine_hide_date',
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Show Username
            [
                'input_wrap_class' => 'combine_hide_name',
                'option_type'      => 'select',
                'label'            => __( 'Show Username', 'feed-them-social' ) . '<br/><small>' . __( 'Yes or No', 'feed-them-social' ) . '</small>',
                'type'             => 'text',
                'id'               => 'combine_hide_name',
                'name'             => 'combine_hide_name',
                'options'          => [
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no']
                ]
            ],
            // Padding
            [
                'option_type'      => 'input',
                'input_wrap_class' => 'combine_padding',
                'label'            => __( 'Padding', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_padding',
                'name'             => 'combine_padding',
                'placeholder'      => '5px 10px 0px 10px'
            ],
            // Display Posts in Grid
            [
                'grouped_options_title' => __( 'Grid', 'feed-them-social' ),
                'input_wrap_class'      => 'combine_grid_option',
                'option_type'           => 'select',
                'label'                 => __( 'Display Posts in Grid', 'feed-them-social' ),
                'type'                  => 'text',
                'id'                    => 'combine_grid_option',
                'name'                  => 'combine_grid_option',
                'options'               => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'sub_options'           => ['sub_options_wrap_class' => 'combine-main-grid-options-wrap']
            ],
            // Grid Column Width
            [
                'option_type'        => 'input',
                'label'              => __( 'Grid Column Width', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'combine_grid_column_width',
                'name'               => 'combine_grid_column_width',
                'instructional-text' => '<strong> ' . __( 'NOTE:', 'feed-them-social' ) . '</strong> ' . \sprintf( __( 'Define width and space between each post. You must add px after number. Learn how to make the %1$sgrid responsive%2$s.', 'feed-them-social' ), '<a href="https://www.slickremix.com/documentation/custom-css-responsive-grid/" target="_blank">', '</a>' ),
                'placeholder'        => '310px ' . __( 'for example', 'feed-them-social' ),
                'sub_options'        => ['sub_options_wrap_class' => 'combine-grid-options-wrap']
            ],
            // Grid Spaces Between Posts
            [
                'option_type'     => 'input',
                'label'           => __( 'Grid Spaces Between Posts', 'feed-them-social' ),
                'type'            => 'text',
                'id'              => 'combine_grid_space_between_posts',
                'name'            => 'combine_grid_space_between_posts',
                'placeholder'     => '10px ' . __( 'for example', 'feed-them-social' ),
                'default'         => '10px',
                'sub_options_end' => 2
            ],
        ];

        $this->allOptions['combine'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_',
            'section_wrap_class' => 'fts-combine-streams-shortcode-form',
            'form_wrap_classes'  => 'combine-streams-shortcode-form',
            'form_wrap_id'       => 'fts-combine-streams-form',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combine'];
    }

    /**
     * Combine Instagram Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineInstagramTokenOptions ()
    {
        $main_options = [
            // Combine Instagram
            [
                'option_type'    => 'select',
                'label'          => __( 'Combine Instagram', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'combine_instagram',
                'name'           => 'combine_instagram',
                'options'        => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes']
                ],
                'req_extensions' => ['feed_them_social_combined_streams'],
                'sub_options'    => ['sub_options_wrap_class' => 'main-combine-instagram-wrap']
            ],
        ];

        $this->allOptions['combineInstagramTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_instagram_token_',
            'section_title'      => '<span class="fts-combined-h3-span">' . esc_html__( 'Instagram', 'feed-them-social' ) . '</span>',
            'section_wrap_class' => 'fts-combined-instagram-feed-type',
            'form_wrap_id'       => 'fts-fb-page-form-combine',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combineInstagramTokenOptions'];
    }

    /**
     * Combine Instagram Token Select Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineInstagramTokenSelectOptions ()
    {
        $main_options = [
            // Instagram Type
            [
                'input_wrap_class' => 'combine_instagram_type',
                'option_type'      => 'select',
                'label'            => __( 'Instagram Type', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_instagram_type',
                'name'             => 'combine_instagram_type',
                'options'          => [
                    ['value' => 'basic', 'label' => __( 'Basic Feed', 'feed-them-social' )],
                    ['value' => 'business', 'label' => __( 'Business Feed', 'feed-them-social' )]
                ]
            ],
            // Instagram Hashtag
            [
                'input_wrap_class' => 'combine_instagram_hashtag_select',
                'option_type'      => 'select',
                'label'            => __( 'Instagram Hashtag', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_instagram_hashtag_select',
                'name'             => 'combine_instagram_hashtag_select',
                'options'          => [
                    ['value' => 'no', 'label' => __( 'No', 'feed-them-social' )],
                    ['value' => 'yes', 'label' => __( 'Yes', 'feed-them-social' )]
                ],
                'sub_options'      => ['sub_options_wrap_class' => 'combine-instagram-wrap']
            ],
            // Hashtag
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'combine_instagram_hashtag',
                'label'              => [
                    [
                        'text'  => __( 'Hashtag', 'feed-them-social' ),
                        'class' => 'combine-instagram-hashtag-option-text'
                    ]
                ],
                'type'               => 'text',
                'id'                 => 'combine_instagram_hashtag',
                'name'               => 'combine_instagram_hashtag',
                'required'           => 'yes',
                'instructional-text' => [
                    [
                        'text'  => __( 'Add your hashtag below. <strong>DO NOT</strong> add the #, just the name. Only one hashtag allowed at this time. Hashtag media only stays on Instagram for 24 hours and the API does not give us a date/time. That also means if you decide to combine this feed these media posts will appear before any other posts because we cannot sort them by date. In order to use the Instagram hashtag feed you must have your Instagram account linked to a Facebook Business Page. <a target="_blank" href="https://www.slickremix.com/docs/link-instagram-account-to-facebook/">Read Instructions.</a>', 'feed-them-social' ),
                        'class' => 'combine-instagram-hashtag-option-text'
                    ]
                ]
            ],
            // Hashtag Type
            [
                'option_type'      => 'select',
                'input_wrap_class' => 'combine_instagram_hashtag_type',
                'label'            => __( 'Hashtag Search Type', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_instagram_hashtag_type',
                'name'             => 'combine_instagram_hashtag_type',
                'class'            => 'combine_instagram-hashtag-type',
                'options'          => [
                    ['label' => __( 'Recent Media', 'feed-them-social' ), 'value' => 'recent-media'],
                    ['label' => __( 'Top Media (Most Interactions)', 'feed-them-social' ), 'value' => 'top-media']
                ],
                'sub_options_end'  => 2
            ],
        ];

        $this->allOptions['combineInstagramTokenSelectOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_instagram_token_select_',
            'section_wrap_class' => 'fts-tab-content1-combine fts-instagram-hashtag-combine',
            'form_wrap_id'       => 'fts-fb-page-form-combine-instagram-token-select',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combineInstagramTokenSelectOptions'];
    }

    /**
     * Combine Facebook Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineFacebookTokenOptions ()
    {
        $main_options = [
            // Combine Facebook
            [
                'option_type'    => 'select',
                'label'          => __( 'Combine Facebook', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'combine_facebook',
                'name'           => 'combine_facebook',
                'options'        => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                ],
                'req_extensions' => ['feed_them_social_combined_streams'],
            ],
        ];

        $this->allOptions['combineFacebookTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_facebook_token_',
            'section_title'      => '<span class="fts-combined-h3-span">' . esc_html__( 'Facebook', 'feed-them-social' ) . '</span>',
            'section_wrap_class' => 'fts-combined-facebook-feed-type',
            'form_wrap_id'       => 'fts-fb-page-form-combine-facebook-token-select',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combineFacebookTokenOptions'];
    }

    /**
     * Combine Twitter Token Select Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineTwitterTokenSelectOptions ()
    {
        $main_options = [
            // Combine Twitter
            [
                'option_type'    => 'select',
                'label'          => __( 'Combine Twitter', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'combine_twitter',
                'name'           => 'combine_twitter',
                'req_extensions' => ['feed_them_social_combined_streams'],
                'options'        => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                ],
                'sub_options'    => [
                    'sub_options_wrap_class' => 'main-combine-twitter-wrap',
                ],
            ],
        ];

        $this->allOptions['combineTwitterTokenSelectOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_twitter_token_select_',
            'section_title'      => '<span class="fts-combined-h3-span">' . esc_html__( 'Twitter', 'feed-them-social' ) . '</span>',
            'section_wrap_class' => 'fts-combined-twitter-feed-type ',
            'form_wrap_id'       => 'fts-twitter-page-form-combine',
            'main_options'       => $main_options,
        ] );

       // Commenting this out for now until we use it for TikTok return $this->allOptions['combineTwitterTokenSelectOptions'];
    }

    /**
     * Combine Twitter Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineTwitterTokenOptions ()
    {
        $main_options = [
            // Feed Type Selection
            [
                'option_type'         => 'select',
                'label'               => __( 'Feed Type', 'feed-them-social' ),
                'select_wrap_classes' => 'combine-twitter-gen-selection',
                'select_classes'      => '',
                'name'                => 'combine-twitter-messages-selector',
                'id'                  => 'combine-twitter-messages-selector',
                'options'             => [
                    ['value' => 'user', 'label' => __( 'User Feed', 'feed-them-social' )],
                ],
                'sub_options'         => [
                    'sub_options_wrap_class' => 'combine-twitter-wrap',
                ],
            ],
            // Twitter Name
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'combine_twitter_name',
                'label'              => __( 'Twitter Name', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'combine_twitter_name',
                'name'               => 'combine_twitter_name',
                'instructional-text' => '<span class="must-copy-twitter-name">' . __( 'If you want to use a ', 'feed-them-social' ) . ' <a href="https://www.slickremix.com/how-to-get-your-twitter-name/" target="_blank">' . __( 'Twitter Name', 'feed-them-social' ) . '</a> ' . __( 'other than the one connected with your account currently, paste it below.', 'feed-them-social' ) . '</span>',
                'sub_options_end'    => 2,
            ],
        ];

        $this->allOptions['combineTwitterTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_twitter_token_',
            'section_wrap_id'    => 'fts-twitter-feed-type',
            'section_wrap_class' => 'fts-tab-content1-combine fts-twitter-combine',
            'form_wrap_classes'  => 'twitter-page-form-combine',
            'form_wrap_id'       => 'fts-twitter-page-form-combine',
            'main_options'       => $main_options,
        ] );

       // Remove until we adjust for tiktok return $this->allOptions['combineTwitterTokenOptions'];
    }

    /**
     * Combine YouTube Token Select Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineYoutubeTokenSelectOptions ()
    {
        $main_options = [
            // Combine Youtube
            [
                'option_type'    => 'select',
                'label'          => __( 'Combine Youtube', 'feed-them-social' ),
                'type'           => 'text',
                'id'             => 'combine_youtube',
                'name'           => 'combine_youtube',
                'options'        => [
                    ['label' => __( 'No', 'feed-them-social' ), 'value' => 'no'],
                    ['label' => __( 'Yes', 'feed-them-social' ), 'value' => 'yes'],
                ],
                'req_extensions' => ['feed_them_social_combined_streams'],
                'sub_options'    => [
                    'sub_options_wrap_class' => 'main-combine-youtube-wrap',
                ],
            ],
        ];

        $this->allOptions['combineYoutubeTokenSelectOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_youtube_token_select_',
            'section_title'      => '<span class="fts-combined-h3-span">' . esc_html__( 'YouTube', 'feed-them-social' ) . '</span>',
            'section_wrap_class' => 'fts-combined-youtube-feed-type',
            'form_wrap_classes'  => 'youtube-page-form-combine',
            'form_wrap_id'       => 'fts-fb-page-form-combine',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combineYoutubeTokenSelectOptions'];
    }

    /**
     * Combine YouTube Token Options
     *
     * @return mixed
     * @since 4.0.0
     */
    public function combineYoutubeTokenOptions ()
    {
        $main_options = [
            // YouTube Type
            [
                'input_wrap_class' => 'combine_youtube_type',
                'option_type'      => 'select',
                'label'            => __( 'YouTube Type', 'feed-them-social' ),
                'type'             => 'text',
                'id'               => 'combine_youtube_type',
                'name'             => 'combine_youtube_type',
                'options'          => [
                    ['label' => __( 'Channel Feed', 'feed-them-social' ), 'value' => 'channelID'],
                    ['label' => __( 'Channel\'s Specific Playlist', 'feed-them-social' ), 'value' => 'playlistID'],
                    ['label' => __( 'User\'s Most Recent Videos', 'feed-them-social' ), 'value' => 'username'],
                    ['label' => __( 'User\'s Specific Playlist', 'feed-them-social' ), 'value' => 'userPlaylist'],
                ],
                'sub_options'      => [
                    'sub_options_wrap_class' => 'combine-youtube-wrap',
                ],
            ],
            // YouTube Name
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'combine_youtube_name',
                'label'              => __( 'YouTube Username', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'combine_youtube_name',
                'name'               => 'combine_youtube_name',
                'instructional-text' => 'Copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Username</a> and paste it below.',
            ],
            // YouTube Playlist ID
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'combine_playlist_id',
                'label'              => __( 'YouTube Playlist ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'combine_playlist_id',
                'name'               => 'combine_playlist_id',
                'instructional-text' => 'Copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Playlist ID</a> and paste them below.',
            ],
            // YouTube Channel ID
            [
                'option_type'        => 'input',
                'input_wrap_class'   => 'combine_channel_id',
                'label'              => __( 'YouTube Channel ID', 'feed-them-social' ),
                'type'               => 'text',
                'id'                 => 'combine_channel_id',
                'name'               => 'combine_channel_id',
                'instructional-text' => 'Copy your YouTube <a href="https://www.slickremix.com/how-to-get-your-youtube-name/" target="_blank">Channel ID</a> and paste it below.',
                'sub_options_end'    => 2,
            ],
        ];

        $this->allOptions['combineYoutubeTokenOptions'] = $this->generateOptionsArray( [
            'section_attr_key'   => 'combine_youtube_token_',
            'section_wrap_class' => 'fts-tab-content1-combine fts-youtube-combine',
            'form_wrap_classes'  => 'fb-page-shortcode-form-combine',
            'form_wrap_id'       => 'fts-fb-page-form-combine',
            'main_options'       => $main_options,
        ] );

        return $this->allOptions['combineYoutubeTokenOptions'];
    }
}
