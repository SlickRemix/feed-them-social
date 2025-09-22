<?php
/**
 * Feed Them Social - Error Handler
 *
 * This class houses some of the errors for FTS
 *
 * @package     feedthemsocial
 * @copyright   Copyright (c) 2012-2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace feedthemsocial\includes;

/**
 * Class FTS Error Handler
 *
 * @package feedthemsocial
 */
class ErrorHandler {

    /**
     * Ouput string
     *
     * @var string
     */
    public $output = '';

    /**
     * Error Code Text.
     *
     * @var string
     */
    const ERROR_CODE_TEXT = 'Error: ';

    /**
     * Solution Text.
     *
     * @var string
     */
    const SOLUTION_TEXT = 'Here are some possible solutions to fix the error.';

    /**
     * Construct
     *
     * Error Handler constructor.
     *
     * @since 1.9.6
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'ftsPluginVersionCheck' ) );
    }

    /**
     * FTS Versions Needed
     *
     * Define minimum premium version allowed to be active with Free Version.
     *
     * @return array
     * @since 1.9.6
     */
    public function ftsVersionsNeeded(): array
    {
        return array(
            'feed-them-premium/feed-them-premium.php' => array(
                'clean_name'     => 'Feed Them Premium',
                'version_needed' => '1.5.3',
            ),
            'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' => array(
                'clean_name'     => 'Feed Them Social Facebook Reviews',
                'version_needed' => '1.0.0',
            ),
            'feed-them-carousel-premium/feed-them-carousel-premium.php' => array(
                'clean_name'     => 'Feed Them Carousel Premium',
                'version_needed' => '1.0.0',
            ),
            'feed-them-social-combined-streams/feed-them-social-combined-streams.php' => array(
                'clean_name'     => 'Feed Them Social Combined Streams',
                'version_needed' => '2.0.5',
            ),
        );
    }

    /**
     * FTS Plugin Version Check
     *
     * Make sure plugins are Proper Version if need be.
     *
     * @throws \Exception Don't let old plugins activate.
     * @since 1.9.6
     */
    public function ftsPluginVersionCheck() {
        // return error if no data retrieved!
        try {
            $update_msg = __( 'Please update ALL Premium Extensions for Feed Them Social because they will no longer work with this version of Feed Them Social. We have made some Major Changes to the Core of the plugin to help with plugin conflicts. Please update your extensions from your <a href="https://www.slickremix.com/my-account" target="_blank">My Account</a> page on our website if you are not receiving notifications for updates on the premium extensions. Thanks again for using our plugin!', 'feed-them-social' );

            $plugins = get_plugins();

            if ( ! \function_exists( 'is_plugin_active' ) || ! \function_exists( 'deactivate_plugins' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
            }

            $fts_versions_needed = $this->ftsVersionsNeeded();

            foreach ( $fts_versions_needed as $single_plugin => $plugin_info ) {

                // Check Version Compatibility if Extensions are not a new enough version deactivate them and throw errors!
                if ( isset( $plugins[$single_plugin] ) && $plugins[$single_plugin]['Version'] < $fts_versions_needed[$single_plugin]['version_needed'] && is_plugin_active( $single_plugin ) ) {
                    deactivate_plugins( $single_plugin );

                    // Custom message for Combined Streams plugin
                    if ( $single_plugin === 'feed-them-social-combined-streams/feed-them-social-combined-streams.php' ) {
                        $combined_streams_msg = 'Your <strong>Feed Them Social Combined Streams</strong> plugin version is 2.0.4 or less and needs to be upgraded to version 2.0.5 or higher. Please update your the extension from the plugins page or download the latest version from your <a href="https://www.slickremix.com/my-account/" target="_blank">My Account</a> page on our website.';
                        throw new \Exception( '<div class="error notice"><p>' . $combined_streams_msg . '</p></div>' );
                    }

                    // Don't Let Old Plugins Activate!
                    throw new \Exception( '<div class="error notice"><p>' . $update_msg . '</p></div>' );
                }
            }
        } catch ( \Exception $e ) {
            add_action(
                'admin_notices',
                function () use ( $e ) {
                    echo wp_kses(
                        $e->getMessage(),
                        array(
                            'a'   => array(
                                'href'   => array(),
                                'target' => array(),
                            ),
                            'div' => array(
                                'class' => array(),
                            ),
                            'p' => array(
                            ),
                            'strong' => array(
                            ),
                        )
                    );
                }
            );
            return true;
        }
        return false;
    }

    /**
     * Facebook Error Check
     *
     * @param array $saved_feed_options shortcode.
     * @param string $feed_data feed data.
     * @return array | bool
     * @throws \Exception Don't let old plugins activate.
     * @since 1.9.6
     */
    public function facebookErrorCheck( $saved_feed_options, $feed_data ) {
        // return error if no data retreived!
        try {
            if ( ! isset( $feed_data->data ) || empty( $feed_data->data ) ) {
                // Solution Text!
                $solution_text = self::SOLUTION_TEXT;
                // ID Error!
                if ( isset( $feed_data->error ) && $feed_data->error->code === 803 ) {
                    if ( strpos( $feed_data->error->message, '(#803) Cannot query users by their username' ) !== false || $saved_feed_options['facebook_page_feed_type'] === 'group' ) {
                        throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . '.2 - Cannot query users by their username. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-803-2" target="_blank">' . $solution_text . '</a></div>' );
                    } else {
                        throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - Facebook cannot find this ID. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-803" target="_blank">' . $solution_text . '</a></div>' );
                    }
                } elseif ( isset( $feed_data->error ) && ( $feed_data->error->code === 341 || $feed_data->error->code === 4 || $feed_data->error->code === 17) ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - Too many calls made to Facebook. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-rate-limiting" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( isset( $feed_data->error ) && $feed_data->error->code === 190 ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - Error validating application. Invalid application ID. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-invalid-app-id" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( isset( $feed_data->error ) && $feed_data->error->code === 104 ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - An access token is required to request this resource. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-access-token-required" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( isset( $feed_data->error ) && $feed_data->error->code === 210 ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - This call requires a Page access token. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-access-token-required" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( isset( $feed_data->error ) && $feed_data->error->code === 100 ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - This Page may not be public. <a style="color:red !important;" href="http://www.slickremix.com/docs/facebook-error-messages/#error-100" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( $saved_feed_options['facebook_page_feed_type'] === 'group' && isset( $feed_data->error ) && $feed_data->error->code === 1 ) {
                    $solution_text = 'Please view this link for a temporary solution.';
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - The group feed is experiencing a known error at this time. <a style="color:red !important;" href="http://www.slickremix.com/docs/facebook-error-messages/#group-feed-error-pinned-post" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( $saved_feed_options['facebook_page_feed_type'] === 'reviews' && ( empty( $feed_data->data ) || ! isset( $feed_data->data ) ) ) {
                    // Rate Limit Exceeded!
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">No Reviews Found or You may not have Admin Permissions for this page. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-no-reviews" target="_blank">' . $solution_text . '</a></div>' );
                } elseif ( isset( $feed_data->error ) ) {
                    // If Custom Exception is not needed but still error then throw ugly error.
                    if ( isset( $feed_data->error->message ) ) {
                        $output = self::ERROR_CODE_TEXT . $feed_data->error->message;
                    }
                    if ( isset( $feed_data->error->type ) ) {
                        $output .= '<br />Type: ' . $feed_data->error->type;
                    }
                    if ( isset( $feed_data->error->code ) ) {
                        $output .= '<br />Code: ' . $feed_data->error->code;
                    }
                    if ( isset( $feed_data->error->error_subcode ) ) {
                        $output .= '<br />Subcode:' . $feed_data->error->error_subcode;
                    }
                    // If just code.
                    if ( isset( $feed_data->error_msg ) ) {
                        $output = self::ERROR_CODE_TEXT . $feed_data->error_msg;
                    }
                    if ( isset( $feed_data->error_code ) ) {
                        $output .= '<br />Code: ' . $feed_data->error_code;
                    }
                    throw new \Exception( '<div style="clear:both; padding:15px 0;" class="fts-error-m">' . $output . '</div>' );
                }
            }
        } catch ( \Exception $e ) {
            $fb_hide_error_handler_message = $saved_feed_options['fb_hide_error_handler_message'] && $saved_feed_options['fb_hide_error_handler_message'] === 'yes' ? 'yes' : 'no';
            if ( $fb_hide_error_handler_message === 'no' ) {
                return array( true, $e->getMessage() );
            } else {
                return array( true, '' );
            }
        }

        return false;
    }

    /**
     * YouTube Error Check
     *
     * @param string|array $feed_data feed data.
     * @return string|array
     * @throws \Exception Don't let old plugins activate.
     * @since 1.9.6
     */
    public function youtubeErrorCheck( $feed_data ) {

        // return error if no data retrieved!
        try {
            if ( ! isset( $feed_data->data ) || empty( $feed_data->data ) ) {

                // Solution Text!
                $solution_text = self::SOLUTION_TEXT;
                if ( isset( $feed_data->error ) && 400 === $feed_data->error->code  ) {
                    throw new \Exception( '<div style="clear:both; padding:15px 0;">#' . $feed_data->error->code . ' - A VALID access token is required to request this resource. <a style="color:red !important;" href="https://www.slickremix.com/docs/facebook-error-messages/#error-access-token-required" target="_blank">' . $solution_text . '</a></div>' );
                }
                if ( isset( $feed_data->error ) ) {
                    // If Custom Exception is not needed but still error then throw ugly error.
                    if ( isset( $feed_data->error->message ) ) {
                        $output = self::ERROR_CODE_TEXT . $feed_data->error->message;
                    }
                    if ( isset( $feed_data->error->code ) ) {
                        $output .= '<br />Code: ' . $feed_data->error->code;
                    }

                    throw new \Exception( '<div style="clear:both; padding:15px 0;" class="fts-error-m">' . $output . '</div>' );
                }

                // Keeping this to look into potential issues. so use throw new \Exception( '<div style="clear:both; padding:15px 0;" class="fts-error-m">'.esc_html__('Oops, It appears something is wrong with this YouTube feed. Are there videos posted on the YouTube account?').'</div>' );
            }
        } catch ( \Exception $e ) {
            return array( true, $e->getMessage() );
        }
        return false;
    }

    /**
     * Instagram Error Check
     *
     * @param string|array $feed_data feed data.
     * @return string|array
     * @throws \Exception Don't let old plugins activate.
     * @since 1.9.6
     */
    public function instagramErrorCheck ( $feed_data ) {
        try {
            if ( !isset( $feed_data->data ) || empty( $feed_data->data ) ) {

                $solution_text = self::SOLUTION_TEXT;
                throw new \Exception( '<div style="clear:both; padding:15px 0;">A Valid access token is required to request this resource. <a style="color:red !important;" target="_blank" href="https://www.slickremix.com/docs/instagram-error-messages/#error-access-token-required" target="_blank">' . $solution_text . '</a></div>' );
            }
        } catch (\Exception $e) {
            return array(true, $e->getMessage());
        }

        return null;
    }
}
