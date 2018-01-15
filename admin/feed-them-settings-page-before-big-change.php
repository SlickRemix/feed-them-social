<?php
namespace feedthemsocial;
/**
 * Class FTS Settings Page
 *
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_settings_page
{
    /**
     * Construct
     *
     * FTS_settings_page constructor.
     *
     * @since 1.9.6
     */
    function __construct()
    {
    }

    /**
     * Feed Them Settings Page
     *
     * Main Settings Page.
     *
     * @since 1.9.6
     */
    function feed_them_settings_page()
    {
        $fts_functions = new feed_them_social_functions();

        if (!function_exists('curl_init')) {
            print '<div class="error"><p>' . __('Warning: cURL is not installed on this server. It is required to use this plugin. Please contact your host provider to install this.', 'feed-them-social') . '</p></div>';
        } ?>

        <div class="feed-them-social-admin-wrap">
            <div class="fts-backg"></div>
            <div class="fts-content">
                <h1><?php _e('Feed Them Social', 'feed-them-social'); ?></h1>
                <div class="use-of-plugin"><?php _e('Please select what type of feed you would like to see. Then you can copy and paste the shortcode to a page, post or widget.', 'feed-them-social'); ?></div>
                <div class="feed-them-icon-wrap">
                    <a href="javascript:;" class="youtube-icon"></a>
                    <a href="javascript:;" class="vine-icon"></a>
                    <a href="javascript:;" class="twitter-icon"></a>
                    <a href="javascript:;" class="facebook-icon"></a>
                    <a href="javascript:;" class="instagram-icon"></a>
                    <a href="javascript:;" class="pinterest-icon"></a>
                    <?php
                    //show the js for the discount option under social icons on the settings page
                    if (!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
                        <div id="discount-for-review"><?php _e('15% off Premium Version', 'feed-them-social'); ?></div>
                        <div class="discount-review-text">
                            <a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank"><?php _e('Share here', 'feed-them-social'); ?></a> <?php _e('and receive 15% OFF your total order.', 'feed-them-social'); ?>
                        </div>
                    <?php } ?>
                </div>
                <form class="feed-them-social-admin-form">
                    <select id="shortcode-form-selector">
                        <option value=""><?php _e('Select a social network to get started', 'feed-them-social'); ?> </option>
                        <option value="fb-page-shortcode-form"><?php _e('Facebook Feed', 'feed-them-social'); ?></option>
                        <option value="twitter-shortcode-form"><?php _e('Twitter Feed', 'feed-them-social'); ?></option>
                        <option value="vine-shortcode-form"><?php _e('Vine Feed', 'feed-them-social'); ?></option>
                        <option value="instagram-shortcode-form"><?php _e('Instagram Feed', 'feed-them-social'); ?></option>
                        <option value="youtube-shortcode-form"><?php _e('YouTube Feed'); ?></option>
                        <option value="pinterest-shortcode-form"><?php _e('Pinterest Feed', 'feed-them-social'); ?></option>
                    </select>
                </form><!--/feed-them-social-admin-form-->
                <?php
                //Add Facebook Event Form
                echo $fts_functions->fts_facebook_group_form(false);
                //Add Facebook Page Form
                echo $fts_functions->fts_facebook_page_form(false);
                //Add Facebook List of Events Form
                echo $fts_functions->fts_facebook_list_of_events_form(false);
                //Add Facebook Single Event Form
                echo $fts_functions->fts_facebook_event_form(false);
                //Add Twitter Form
                echo $fts_functions->fts_twitter_form();
                //Add Vine Form
                echo $fts_functions->fts_vine_form(false);
                //Add Instagram Form
                echo $fts_functions->fts_instagram_form(false);
                //Add Youtube Form
                echo $fts_functions->fts_youtube_form(false);
                //Add Pinterest Form
                echo $fts_functions->fts_pinterest_form(false);
                ?>
                <div class="fts-clear"></div>
                <div class="feed-them-clear-cache">
                    <h2><?php _e('Clear All Cache Options', 'feed-them-social'); ?></h2>
                    <div class="use-of-plugin"><?php _e('Please Clear Cache if you have changed a Feed Them Social Shortcode. This will Allow you to see the changes right away.', 'feed-them-social'); ?></div>
                    <?php if (isset($_GET['cache']) && $_GET['cache'] == 'clearcache') {
                        echo '<div class="feed-them-clear-cache-text">' . $fts_functions->feed_them_clear_cache() . '</div>';
                    }
                    isset($ftsDevModeCache) ? $ftsDevModeCache : "";
                    isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
                    $ftsDevModeCache = get_option('fts_clear_cache_developer_mode');
                    $ftsAdminBarMenu = get_option('fts_admin_bar_menu');
                    ?>

                    <form method="post" action="?page=feed-them-settings-page&cache=clearcache">
                        <input class="feed-them-social-admin-submit-btn" type="submit" value="<?php _e('Clear All FTS Feeds Cache', 'feed-them-social'); ?>"/>
                    </form>
                </div><!--/feed-them-clear-cache-->
                <!-- custom option for padding -->
                <form method="post" class="fts-color-settings-admin-form" action="options.php">
                    <p>
                        <input name="fts_clear_cache_developer_mode" class="fts-color-settings-admin-input fts_clear_cache_developer_mode" type="checkbox" id="fts-color-options-settings-custom-css" value="1" <?php echo checked('1', get_option('fts_clear_cache_developer_mode')); ?>/>
                        <?php
                        if (get_option('fts_clear_cache_developer_mode') == '1') { ?>
                            <?php _e('Cache will clear on every page load now', 'feed-them-social'); ?><?php
                        } else { ?>
                            <?php _e('Developer Mode: Clear cache on every page load', 'feed-them-social'); ?><?php
                        }
                        ?>
                    </p>
                    <select id="fts_admin_bar_menu" name="fts_admin_bar_menu">
                        <option value="show-admin-bar-menu" <?php if ($ftsAdminBarMenu == 'show-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Show Admin Bar Menu', 'feed-them-social'); ?></option>
                        <option value="hide-admin-bar-menu" <?php if ($ftsAdminBarMenu == 'hide-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Hide Admin Bar Menu', 'feed-them-social'); ?></option>
                    </select>
                    <div class="feed-them-custom-css">
                        <?php // get our registered settings from the fts functions
                        settings_fields('feed-them-social-settings'); ?>
                        <?php
                        isset($ftsDateTimeFormat) ? $ftsDateTimeFormat : "";
                        isset($ftsTimezone) ? $ftsTimezone : "";
                        isset($ftsCustomDate) ? $ftsCustomDate : "";
                        isset($ftsCustomTime) ? $ftsCustomTime : "";
                        $ftsDateTimeFormat = get_option('fts-date-and-time-format');
                        $ftsTimezone = get_option('fts-timezone');
                        $ftsCustomDate = get_option('date_format');
                        $ftsCustomTime = get_option('time_format');
                        $ftsCustomTimezone = get_option('fts-timezone') ? get_option('fts-timezone') : "America/Los_Angeles";
                        date_default_timezone_set($ftsCustomTimezone);

                        ?>
                        <div style="float:left; max-width:400px; margin-right:30px;">
                            <h2><?php _e('FaceBook & Twitter Date Format', 'feed-them-social'); ?></h2>

                            <fieldset>
                                <select id="fts-date-and-time-format" name="fts-date-and-time-format">
                                    <option value="l, F jS, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'l, F jS, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('l, F jS, Y \a\t g:ia'); ?></option>
                                    <option value="F j, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('F j, Y \a\t g:ia'); ?></option>
                                    <option value="F j, Y g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y g:ia') echo 'selected="selected"'; ?>><?php echo date('F j, Y g:ia'); ?></option>
                                    <option value="F, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F, Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('F, Y \a\t g:ia'); ?></option>
                                    <option value="M j, Y @ g:ia" <?php if ($ftsDateTimeFormat == 'M j, Y @ g:ia') echo 'selected="selected"'; ?>><?php echo date('M j, Y @ g:ia'); ?></option>
                                    <option value="M j, Y @ G:i" <?php if ($ftsDateTimeFormat == 'M j, Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('M j, Y @ G:i'); ?></option>
                                    <option value="m/d/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'm/d/Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('m/d/Y \a\t g:ia'); ?></option>
                                    <option value="m/d/Y @ G:i" <?php if ($ftsDateTimeFormat == 'm/d/Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('m/d/Y @ G:i'); ?></option>
                                    <option value="d/m/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'd/m/Y \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('d/m/Y \a\t g:ia'); ?></option>
                                    <option value="d/m/Y @ G:i" <?php if ($ftsDateTimeFormat == 'd/m/Y @ G:i') echo 'selected="selected"'; ?>><?php echo date('d/m/Y @ G:i'); ?></option>
                                    <option value="Y/m/d \a\t g:ia" <?php if ($ftsDateTimeFormat == 'Y/m/d \a\t g:ia') echo 'selected="selected"'; ?>><?php echo date('Y/m/d \a\t g:ia'); ?></option>
                                    <option value="Y/m/d @ G:i" <?php if ($ftsDateTimeFormat == 'Y/m/d @ G:i') echo 'selected="selected"'; ?>><?php echo date('Y/m/d @ G:i'); ?></option>
                                    <option value="one-day-ago" <?php if ($ftsDateTimeFormat == 'one-day-ago') echo 'selected="selected"'; ?>><?php _e('1 day ago', 'feed-them-social'); ?></option>
                                    <option value="fts-custom-date" <?php if ($ftsDateTimeFormat == 'fts-custom-date') echo 'selected="selected"'; ?>><?php _e('Use Custom Date and Time Option Below', 'feed-them-social'); ?></option>
                                </select>
                            </fieldset>

                            <?php
                            //Date translate
                            $fts_language_second = get_option('fts_language_second', 'second');
                            $fts_language_seconds = get_option('fts_language_seconds', 'seconds');
                            $fts_language_minute = get_option('fts_language_minute', 'minute');
                            $fts_language_minutes = get_option('fts_language_minutes', 'minutes');
                            $fts_language_hour = get_option('fts_language_hour', 'hour');
                            $fts_language_hours = get_option('fts_language_hours', 'hours');
                            $fts_language_day = get_option('fts_language_day', 'day');
                            $fts_language_days = get_option('fts_language_days', 'days');
                            $fts_language_week = get_option('fts_language_week', 'week');
                            $fts_language_weeks = get_option('fts_language_weeks', 'weeks');
                            $fts_language_month = get_option('fts_language_month', 'month');
                            $fts_language_months = get_option('fts_language_months', 'months');
                            $fts_language_year = get_option('fts_language_year', 'year');
                            $fts_language_years = get_option('fts_language_years', 'years');
                            $fts_language_ago = get_option('fts_language_ago', 'ago');
                            ?>

                            <div class="custom_time_ago_wrap" style="display:none;">
                                <h2><?php _e('Translate words for 1 day ago option.', 'feed-them-social'); ?></h2>
                                <label for="fts_language_second"><?php _e("second"); ?></label>
                                <input name="fts_language_second" type="text" value="<?php echo stripslashes(esc_attr($fts_language_second)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_seconds"><?php _e("seconds"); ?></label>
                                <input name="fts_language_seconds" type="text" value="<?php echo stripslashes(esc_attr($fts_language_seconds)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_minute"><?php _e("minute"); ?></label>
                                <input name="fts_language_minute" type="text" value="<?php echo stripslashes(esc_attr($fts_language_minute)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_minutes"><?php _e("minutes"); ?></label>
                                <input name="fts_language_minutes" type="text" value="<?php echo stripslashes(esc_attr($fts_language_minutes)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_hour"><?php _e("hour"); ?></label>
                                <input name="fts_language_hour" type="text" value="<?php echo stripslashes(esc_attr($fts_language_hour)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_hours"><?php _e("hours"); ?></label>
                                <input name="fts_language_hours" type="text" value="<?php echo stripslashes(esc_attr($fts_language_hours)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_day"><?php _e("day"); ?></label>
                                <input name="fts_language_day" type="text" value="<?php echo stripslashes(esc_attr($fts_language_day)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_days"><?php _e("days"); ?></label>
                                <input name="fts_language_days" type="text" value="<?php echo stripslashes(esc_attr($fts_language_days)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_week"><?php _e("week"); ?></label>
                                <input name="fts_language_week" type="text" value="<?php echo stripslashes(esc_attr($fts_language_week)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_weeks"><?php _e("weeks"); ?></label>
                                <input name="fts_language_weeks" type="text" value="<?php echo stripslashes(esc_attr($fts_language_weeks)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_month"><?php _e("month"); ?></label>
                                <input name="fts_language_month" type="text" value="<?php echo stripslashes(esc_attr($fts_language_month)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_months"><?php _e("months"); ?></label>
                                <input name="fts_language_months" type="text" value="<?php echo stripslashes(esc_attr($fts_language_months)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_year"><?php _e("year"); ?></label>
                                <input name="fts_language_year" type="text" value="<?php echo stripslashes(esc_attr($fts_language_year)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_years"><?php _e("years"); ?></label>
                                <input name="fts_language_years" type="text" value="<?php echo stripslashes(esc_attr($fts_language_years)); ?>" size="25"/>
                                <br/>
                                <label for="fts_language_ago"><?php _e("ago"); ?></label>
                                <input name="fts_language_ago" type="text" value="<?php echo stripslashes(esc_attr($fts_language_ago)); ?>" size="25"/>

                            </div>
                            <script>
                                // change the feed type 'how to' message when a feed type is selected

                                <?php if ($ftsDateTimeFormat == 'one-day-ago'){ ?>
                                jQuery('.custom_time_ago_wrap').show();
                                <?php    } ?>
                                jQuery('#fts-date-and-time-format').change(function () {

                                    var ftsTimeAgo = jQuery("select#fts-date-and-time-format").val();
                                    if (ftsTimeAgo == 'one-day-ago') {
                                        jQuery('.custom_time_ago_wrap').show();
                                    }
                                    else {
                                        jQuery('.custom_time_ago_wrap').hide();
                                    }

                                });

                            </script>
                            <h2 style="border-top:0px; margin-bottom:4px !important;"><?php _e('Custom Date and Time', 'feed-them-social'); ?></h2>
                            <div><?php if ($ftsCustomDate !== '' || $ftsCustomTime !== '') {
                                    echo date(get_option('fts-custom-date') . ' ' . get_option('fts-custom-time'));
                                } ?></div>
                            <p style="margin:12px 0 !important;">
                                <input name="fts-custom-date" style="max-width:105px;" class="fts-color-settings-admin-input" id="fts-custom-date" placeholder="<?php _e('Date', 'feed-them-social'); ?>" value="<?php echo get_option('fts-custom-date'); ?>"/>
                                <input name="fts-custom-time" style="max-width:75px;" class="fts-color-settings-admin-input" id="fts-custom-time" placeholder="<?php _e('Time', 'feed-them-social'); ?>" value="<?php echo get_option('fts-custom-time'); ?>"/>
                            </p>
                            <div><?php _e('This will override the date and time format above.', 'feed-them-social'); ?>
                                <br/><a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e('Options for custom date and time formatting.', 'feed-them-social'); ?></a>
                            </div>
                        </div>
                        <div style="float:left; max-width:330px; margin-right: 30px;"><h2><?php _e('TimeZone', 'feed-them-social'); ?></h2>
                            <fieldset>
                                <select id="fts-timezone" name="fts-timezone">
                                    <option value="Kwajalein" <?php if ($ftsTimezone == "Kwajalein") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-12:00'); ?>
                                    </option>
                                    <option value="Pacific/Midway" <?php if ($ftsTimezone == "Pacific/Midway") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-11:00'); ?>
                                    </option>
                                    <option value="Pacific/Honolulu" <?php if ($ftsTimezone == "Pacific/Honolulu") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-10:00'); ?>
                                    </option>
                                    <option value="America/Anchorage" <?php if ($ftsTimezone == "America/Anchorage") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-09:00'); ?>
                                    </option>
                                    <option value="America/Los_Angeles" <?php if ($ftsTimezone == "America/Los_Angeles") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-08:00'); ?>
                                    </option>
                                    <option value="America/Denver" <?php if ($ftsTimezone == "America/Denver") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-07:00'); ?>
                                    </option>
                                    <option value="America/Chicago" <?php if ($ftsTimezone == "America/Chicago") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-06:00'); ?>
                                    </option>
                                    <option value="America/New_York" <?php if ($ftsTimezone == "America/New_York") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-05:00'); ?>
                                    </option>
                                    <option value="America/Caracas" <?php if ($ftsTimezone == "America/Caracas") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-04:30'); ?>
                                    </option>
                                    <option value="America/Halifax" <?php if ($ftsTimezone == "America/Halifax") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-04:00'); ?>
                                    </option>
                                    <option value="America/St_Johns" <?php if ($ftsTimezone == "America/St_Johns") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-03:30'); ?>
                                    </option>
                                    <option value="America/Sao_Paulo" <?php if ($ftsTimezone == "America/Sao_Paulo") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-03:00'); ?>
                                    </option>
                                    <option value="America/Noronha" <?php if ($ftsTimezone == "America/Noronha") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-02:00'); ?>
                                    </option>
                                    <option value="Atlantic/Cape_Verde" <?php if ($ftsTimezone == "Atlantic/Cape_Verde") echo 'selected="selected"' ?> >
                                        <?php _e('UTC-01:00'); ?>
                                    </option>
                                    <option value="Europe/Belfast" <?php if ($ftsTimezone == "Europe/Belfast") echo 'selected="selected"' ?> >
                                        <?php _e('UTC'); ?>
                                    <option value="Europe/Amsterdam" <?php if ($ftsTimezone == "Europe/Amsterdam") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+01:00'); ?>
                                    </option>
                                    <option value="Asia/Beirut" <?php if ($ftsTimezone == "Asia/Beirut") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+02:00'); ?>
                                    </option>
                                    <option value="Europe/Moscow" <?php if ($ftsTimezone == "Europe/Moscow") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+03:00'); ?>
                                    </option>
                                    <option value="Asia/Tehran" <?php if ($ftsTimezone == "Asia/Tehran") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+03:30'); ?>
                                    </option>
                                    <option value="Asia/Yerevan" <?php if ($ftsTimezone == "Asia/Yerevan") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+04:00'); ?>
                                    </option>
                                    <option value="Asia/Kabul" <?php if ($ftsTimezone == "Asia/Kabul") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+04:30'); ?>
                                    </option>
                                    <option value="Asia/Tashkent" <?php if ($ftsTimezone == "Asia/Tashkent") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+05:00'); ?>
                                    </option>
                                    <option value="Asia/Kolkata" <?php if ($ftsTimezone == "Asia/Kolkata") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+05:30'); ?>
                                    </option>
                                    <option value="Asia/Katmandu" <?php if ($ftsTimezone == "Asia/Katmandu") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+05:45'); ?>
                                    </option>
                                    <option value="Asia/Dhaka" <?php if ($ftsTimezone == "Asia/Dhaka") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+06:00'); ?>
                                    </option>
                                    <option value="Asia/Novosibirsk" <?php if ($ftsTimezone == "Asia/Novosibirsk") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+06:00'); ?>
                                    </option>
                                    <option value="Asia/Rangoon" <?php if ($ftsTimezone == "Asia/Rangoon") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+06:30'); ?>
                                    </option>
                                    <option value="Asia/Bangkok" <?php if ($ftsTimezone == "Asia/Bangkok") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+07:00'); ?>
                                    </option>
                                    <option value="Australia/Perth" <?php if ($ftsTimezone == "Australia/Perth") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+08:00'); ?>
                                    </option>
                                    <option value="Australia/Eucla" <?php if ($ftsTimezone == "Australia/Eucla") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+08:45'); ?>
                                    </option>
                                    <option value="Asia/Tokyo" <?php if ($ftsTimezone == "Asia/Tokyo") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+09:00'); ?>
                                    </option>
                                    <option value="Australia/Adelaide" <?php if ($ftsTimezone == "Australia/Adelaide") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+09:30'); ?>
                                    </option>
                                    <option value="Australia/Hobart" <?php if ($ftsTimezone == "Australia/Hobart") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+10:00'); ?>
                                    </option>
                                    <option value="Australia/Lord_Howe" <?php if ($ftsTimezone == "Australia/Lord_Howe") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+10:30'); ?>
                                    </option>
                                    <option value="Asia/Magadan" <?php if ($ftsTimezone == "Asia/Magadan") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+11:00'); ?>
                                    </option>
                                    <option value="Pacific/Norfolk" <?php if ($ftsTimezone == "Pacific/Norfolk") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+11:30'); ?>
                                    </option>
                                    <option value="Asia/Anadyr" <?php if ($ftsTimezone == "Asia/Anadyr") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+12:00'); ?>
                                    </option>
                                    <option value="Pacific/Chatham" <?php if ($ftsTimezone == "Pacific/Chatham") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+12:45'); ?>
                                    </option>
                                    <option value="Pacific/Tongatapu" <?php if ($ftsTimezone == "Pacific/Tongatapu") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+13:00'); ?>
                                    </option>
                                    <option value="Pacific/Kiritimati" <?php if ($ftsTimezone == "Pacific/Kiritimati") echo 'selected="selected"' ?> >
                                        <?php _e('UTC+14:00'); ?>
                                    </option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="fts-clear"></div>

                        <br/>
                        <h2><?php _e('Custom CSS Option', 'feed-them-social'); ?></h2>
                        <p>
                            <input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox" id="fts-color-options-settings-custom-css" value="1" <?php echo checked('1', get_option('fts-color-options-settings-custom-css')); ?>/>
                            <?php
                            if (get_option('fts-color-options-settings-custom-css') == '1') { ?>
                                <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('Custom CSS option is being used now.', 'feed-them-social'); ?><?php
                            } else { ?>
                                <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong> <?php _e('You are using the default CSS.', 'feed-them-social'); ?><?php
                            }
                            ?>
                        </p>
                        <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'feed-them-social'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'feed-them-social'); ?></span> <?php _e('custom CSS', 'feed-them-social'); ?>
                        </label>
                        <div class="fts-clear"></div>
                        <div class="fts-custom-css-text"><?php _e('Thanks for using our plugin :) Add your custom CSS additions or overrides below.', 'feed-them-social'); ?></div>
                        <textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></textarea>
                    </div><!--/feed-them-custom-css-->


                    <div class="feed-them-custom-logo-css">
                        <h2><?php _e('Disable Magnific Popup CSS', 'feed-them-social'); ?></h2>
                        <p>
                            <input name="fts_fix_magnific" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_fix_magnific" value="1" <?php echo checked('1', get_option('fts_fix_magnific')); ?>/> <?php _e('Check this if you are experiencing problems with your theme(s) or other plugin(s) popups.', 'feed-them-social'); ?>
                        </p>
                        <br/>


                        <h2><?php _e('Fix Twitter Time', 'feed-them-social'); ?></h2>
                        <p>
                            <input name="fts_twitter_time_offset" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_twitter_time_offset" value="1" <?php echo checked('1', get_option('fts_twitter_time_offset')); ?>/> <?php _e('Check this if the Twitter time is still off by 3 hours after setting the TimeZone above.', 'feed-them-social'); ?>
                        </p>
                        <br/>

                        <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                            <h2><?php _e('Fix Load More Error', 'feed-them-social'); ?></h2>
                            <p>
                                <input name="fts_fix_loadmore" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_fix_loadmore" value="1" <?php echo checked('1', get_option('fts_fix_loadmore')); ?>/> <?php _e('Check this if you are using the loadmore button for Facebook or Instagram and are seeing a bunch of code under it.', 'feed-them-social'); ?>
                            </p>
                            <br/>
                        <?php } ?>

                        <h2><?php _e('Fix Internal Server Error', 'feed-them-social'); ?></h2>
                        <p>
                            <input name="fts_curl_option" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts_curl_option" value="1" <?php echo checked('1', get_option('fts_curl_option')); ?>/> <?php _e('Check this option if you are getting a 500 Internal Server Error when trying to load a page with our feed on it.', 'feed-them-social'); ?>
                        </p>
                        <br/>

                        <h2><?php _e('Powered by Text', 'feed-them-social'); ?></h2>
                        <p>
                            <input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked('1', get_option('fts-powered-text-options-settings')); ?>/>
                            <?php
                            if (get_option('fts-powered-text-options-settings') == '1') { ?>
                                <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('You are not showing the Powered by Logo.', 'feed-them-social'); ?><?php
                            } else { ?>
                                <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong><?php _e('The Powered by text will appear in the site. Awesome! Thanks so much for sharing.', 'feed-them-social'); ?><?php
                            }
                            ?>
                        </p>
                        <br/>
                        <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes', 'feed-them-social') ?>"/>
                        <div class="fts-clear"></div>
                    </div><!--/feed-them-custom-logo-css-->
                </form>
            </div><!--/font-content-->
        </div><!--/feed-them-social-admin-wrap-->

        <h1 class="plugin-author-note"><?php _e('Plugin Authors Note', 'feed-them-social'); ?></h1>
        <div class="fts-plugin-reviews">
            <div class="fts-plugin-reviews-rate"><?php _e(' Feed Them Social was created by 2 Brothers, Spencer and Justin Labadie. That’s it, 2 people! We spend all our time creating and supporting this plugin. Show us some love if you like our plugin and leave a quick review for us, it will make our day!', 'feed-them-social'); ?>
                <a href="https://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank"><?php _e('Leave us a Review', 'feed-them-social'); ?>
                    ★★★★★</a>
            </div>
            <div class="fts-plugin-reviews-support"><?php _e('If you\'re having troubles getting setup please contact us. We will respond within 24hrs, but usually within 1-6hrs.', 'feed-them-social'); ?>
                <a href="http://www.slickremix.com/support-forum/forum/feed-them-social-2" target="_blank"><?php _e('Support Forum', 'feed-them-social'); ?></a>
                <div class="fts-text-align-center">
                    <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
                </div>
            </div>
        </div>

        <script>
            jQuery(function () {


                jQuery(".feed-them-social-admin-submit-btn").click(function () {

                    // Facebook
                    var isPXpresent = jQuery('#facebook_page_height').val();
                    // This is in place to auto add the px if a specific input is missing it.
                    if (jQuery('#facebook_page_height').val().indexOf('px') <= 0 && isPXpresent !== "") {
                        jQuery('#facebook_page_height').val(jQuery('#facebook_page_height').val() + 'px');
                    }
                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                    var isPXpresent2 = jQuery('#facebook_grid_colmn_width').val();
                    if (jQuery('#facebook_grid_colmn_width').val().indexOf('px') <= 0 && isPXpresent2 !== "") {
                        jQuery('#facebook_grid_colmn_width').val(jQuery('#facebook_grid_colmn_width').val() + 'px');
                    }
                    var isPXpresent3 = jQuery('#facebook_grid_space_between_posts').val();
                    if (jQuery('#facebook_grid_space_between_posts').val().indexOf('px') <= 0 && isPXpresent3 !== "") {
                        jQuery('#facebook_grid_space_between_posts').val(jQuery('#facebook_grid_space_between_posts').val() + 'px');
                    }
                    var isPXpresent4 = jQuery('#loadmore_button_width').val();
                    if (jQuery('#loadmore_button_width').val().indexOf('px') <= 0 && isPXpresent4 !== "") {
                        jQuery('#loadmore_button_width').val(jQuery('#loadmore_button_width').val() + 'px');
                    }
                    var isPXpresent5 = jQuery('#loadmore_button_margin').val();
                    if (jQuery('#loadmore_button_margin').val().indexOf('px') <= 0 && isPXpresent5 !== "") {
                        jQuery('#loadmore_button_margin').val(jQuery('#loadmore_button_margin').val() + 'px');
                    }
                    <?php } ?>
                    // Twitter
                    var isPXpresent6 = jQuery('#twitter_height').val();
                    if (jQuery('#twitter_height').val().indexOf('px') <= 0 && isPXpresent6 !== "") {
                        jQuery('#twitter_height').val(jQuery('#twitter_height').val() + 'px');
                    }
                    // Vine
                    var isPXpresent7 = jQuery('#vine_maxwidth').val();
                    if (jQuery('#vine_maxwidth').val().indexOf('px') <= 0 && isPXpresent7 !== "") {
                        jQuery('#vine_maxwidth').val(jQuery('#vine_maxwidth').val() + 'px');
                    }
                    var isPXpresent8 = jQuery('#space_between_photos').val();
                    if (jQuery('#space_between_photos').val().indexOf('px') <= 0 && isPXpresent8 !== "") {
                        jQuery('#space_between_photos').val(jQuery('#space_between_photos').val() + 'px');
                    }
                    var isPXpresent9 = jQuery('#round_thumb_corner_size').val();
                    if (jQuery('#round_thumb_corner_size').val().indexOf('px') <= 0 && isPXpresent9 !== "") {
                        jQuery('#round_thumb_corner_size').val(jQuery('#round_thumb_corner_size').val() + 'px');
                    }
                    <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
                    var isPXpresent10 = jQuery('#vine_logo_size').val();
                    if (jQuery('#vine_logo_size').val().indexOf('px') <= 0 && isPXpresent10 !== "") {
                        jQuery('#vine_logo_size').val(jQuery('#vine_logo_size').val() + 'px');
                    }
                    <?php } ?>
                    // Instagram
                    var isPXpresent11 = jQuery('#instagram_page_height').val();
                    if (jQuery('#instagram_page_height').val().indexOf('px') <= 0 && isPXpresent11 !== "") {
                        jQuery('#instagram_page_height').val(jQuery('#instagram_page_height').val() + 'px');
                    }

                });


                // Master feed selector
                jQuery('#shortcode-form-selector').change(function () {
                    jQuery('.shortcode-generator-form').hide();
                    jQuery('.' + jQuery(this).val()).fadeIn('fast');

                });

                jQuery('#fb_hide_like_box_button').change(function () {
                    jQuery('.fb_align_likebox').toggle();
                });

                jQuery('#facebook_show_video_button').change(function () {
                    jQuery('.fb-video-play-btn-options-content').toggle();
                });


                // change the feed type 'how to' message when a feed type is selected
                jQuery('#facebook-messages-selector').change(function () {
                    jQuery('.facebook-message-generator').hide();
                    jQuery('.' + jQuery(this).val()).fadeIn('fast');
                    // if the facebook type select is changed we hide the shortcode code so not to confuse people
                    jQuery('.final-shortcode-textarea').hide();
                    // only show the Super Gallery Options if the facebook ablum or album covers feed type is selected
                    var facebooktype = jQuery("select#facebook-messages-selector").val();

                    //		if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'page'  || facebooktype == 'group' || facebooktype == 'event' || facebooktype == 'events') {
                    //
                    //		}

                    <?php    if (is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

                    // This is to show all option when prem active if you selected the Facebook Page reviews if not active. Otherwise all other fb-options-wraps are hidden when selecting another fb feed from settings page drop down.
                    jQuery('.fb-options-wrap').show();
                    jQuery('body .fb_album_photos_id').hide();
                    if (facebooktype == 'album_videos') {
                        jQuery('.fts-premium-options-message, .fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').hide();
                        jQuery('.video, .fb-video-play-btn-options-wrap, #facebook_video_align_images_wrapper').show();
                        //  jQuery(".feed-them-social-admin-input-label:contains('Album')").html("<?php // _e('Video Album ID (required)', 'feed-them-social') ?>");
                        jQuery(".feed-them-social-admin-input-label:contains('# of Posts')").html("<?php _e('# of Videos', 'feed-them-social') ?>");
                    }
                    else {
                        jQuery('.video, .fb-video-play-btn-options-wrap, #facebook_video_align_images_wrapper').hide();
                        jQuery('.fts-photos-popup, #facebook_super_gallery_container, #facebook_super_gallery_animate').show();
                        //  jQuery(".feed-them-social-admin-input-label:contains('Video Album ID (required)')").html("<?php // _e('Album ID', 'feed-them-social') ?><br/><small><?php // _e('Leave Blank to show all photos.', 'feed-them-social') ?></small>");
                        jQuery(".feed-them-social-admin-input-label:contains('# of Videos')").html("<?php _e('# of Posts', 'feed-them-social') ?>");
                    }
                    <?php    }
                    else { ?>
                    if (facebooktype == 'album_videos') {
                        // we are hiding all fields in the free verison and adding am upgrade message, much easier this way as the options add up.
                        jQuery('.fb-options-wrap').hide();
                        jQuery('.fts-premium-options-message').show();

                    }
                    else {
                        jQuery('.fb-options-wrap').show();
                        jQuery('.fts-premium-options-message, .video').hide();
                    }
                    <?php } ?>

                    if (facebooktype == 'page') {
                        jQuery('.inst-text-facebook-page').show();
                    }
                    else {
                        jQuery('.inst-text-facebook-page').hide();
                    }

                    if (facebooktype == 'events') {
                        jQuery('.inst-text-facebook-event-list').show();
                    }
                    else {
                        jQuery('.inst-text-facebook-event-list').hide();
                    }


                    <?php    if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>

                    if (facebooktype == 'reviews') {
                        jQuery('.fb-reviews, .inst-text-facebook-page, .reviews-options').show();
                        jQuery('.fb-page-title-option-hide, .fb-page-description-option-hide, .need-for-premium-fields-wrap').hide();
                    } else {
                        jQuery('.fb-reviews, .reviews-options').hide();
                        jQuery('.fb-page-title-option-hide, .fb-page-description-option-hide, .need-for-premium-fields-wrap').show();
                    }        <?php } else { ?>
                    if (facebooktype == 'reviews') {
                        // we are hiding all fields in the free verison and adding am upgrade message, much easier this way as the options add up.
                        jQuery('.reviews-copy-text, .fb-options-wrap').hide();
                        jQuery('.fts-premium-options-message2').show();
                    } else {
                        jQuery('.fts-premium-options-message2').hide();
                    }
                    <?php } ?>


                    if (facebooktype == 'albums' || facebooktype == 'album_photos' || facebooktype == 'album_videos') {
                        jQuery('.fts-super-facebook-options-wrap').show();
                        jQuery('.fixed_height_option').hide();
                        jQuery('.fb-posts-in-grid-option-wrap').hide();
                        jQuery('.fixed_height_option').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').hide();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap').hide();
                        jQuery('.fixed_height_option').show();
                        jQuery('.fb-posts-in-grid-option-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').show();


                    }
                    // only show the post type visible if the facebook page feed type is selected
                    jQuery('.facebook-post-type-visible').hide();
                    if (facebooktype == 'page') {
                        jQuery('.facebook-post-type-visible').show();
                    }
                    var fb_feed_type_option = jQuery("select#facebook-messages-selector").val();
                    if (fb_feed_type_option == 'album_photos') {
                        jQuery('.fb_album_photos_id').show();
                    }
                    else {
                        jQuery('.fb_album_photos_id').hide();
                    }
                });
                // Instagram Super Gallery option
                jQuery('#instagram-custom-gallery').bind('change', function (e) {
                    if (jQuery('#instagram-custom-gallery').val() == 'yes') {
                        jQuery('.fts-super-instagram-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-super-instagram-options-wrap').hide();
                    }
                });
                jQuery('#instagram-messages-selector').bind('change', function (e) {
                    if (jQuery('#instagram-messages-selector').val() == 'hashtag') {
                        jQuery(".instagram-id-option-wrap").hide();
                        jQuery(".instagram-hashtag-option-text").show();
                        jQuery(".instagram-user-option-text").hide();
                    }
                    else {
                        jQuery(".instagram-id-option-wrap").show();
                        jQuery(".instagram-hashtag-option-text").hide();
                        jQuery(".instagram-user-option-text").show();
                    }
                });

                jQuery('#twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#twitter-messages-selector').val() == 'hashtag') {
                        jQuery(".hashtag-option-small-text").show();
                        jQuery(".twitter-hashtag-etc-wrap").show();
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").hide();
                    }
                    else {
                        jQuery(".hashtag-option-not-required, .must-copy-twitter-name").show();
                        jQuery(".twitter-hashtag-etc-wrap").hide();
                        jQuery(".hashtag-option-small-text").hide();
                    }
                });


                // facebook show grid options
                jQuery('#fb-grid-option').bind('change', function (e) {
                    if (jQuery('#fb-grid-option').val() == 'yes') {
                        jQuery('.fts-facebook-grid-options-wrap').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Center Facebook Container?', 'feed-them-social'); ?>')").parent('div').show();
                    }
                    else {
                        jQuery('.fts-facebook-grid-options-wrap').hide();
                    }
                });
                // facebook Super Gallery option
                jQuery('#facebook-custom-gallery').bind('change', function (e) {
                    if (jQuery('#facebook-custom-gallery').val() == 'yes') {
                        jQuery('.fts-super-facebook-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-super-facebook-options-wrap').hide();
                    }
                });
                // facebook show load more options
                jQuery('#fb_load_more_option').bind('change', function (e) {
                    if (jQuery('#fb_load_more_option').val() == 'yes') {

                        if (jQuery('#facebook-messages-selector').val() !== 'album_videos') {
                            jQuery('.fts-facebook-load-more-options-wrap').show();
                        }
                        jQuery('.fts-facebook-load-more-options2-wrap').show();
                    }

                    else {
                        jQuery('.fts-facebook-load-more-options-wrap, .fts-facebook-load-more-options2-wrap').hide();
                    }
                });
                // Instagram show load more options
                jQuery('#instagram_load_more_option').bind('change', function (e) {
                    if (jQuery('#instagram_load_more_option').val() == 'yes') {
                        jQuery('.fts-instagram-load-more-options-wrap').show();
                    }
                    else {
                        jQuery('.fts-instagram-load-more-options-wrap').hide();
                    }
                });
                // Pinterest options
                // hide this div till needed for free version
                jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').hide();
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'boards_list') {
                        jQuery('.number-of-boards, .pinterest-name-text').show();
                        jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').show();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').hide();
                    }
                });
                // Pinterest options
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'single_board_pins') {
                        jQuery('.board-name, .show-pins-amount, .pinterest-board-and-name-text').show();
                        jQuery('.number-of-boards, .pinterest-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').show();
                    }
                })
                // Pinterest options
                jQuery('#pinterest-messages-selector').bind('change', function (e) {
                    if (jQuery('#pinterest-messages-selector').val() == 'pins_from_user') {
                        jQuery('.show-pins-amount, .pinterest-name-text').show();
                        jQuery('.number-of-boards, .board-name, .pinterest-board-and-name-text').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Boards', 'feed-them-social'); ?>')").parent('div').hide();
                        jQuery(".feed-them-social-admin-input-label:contains('<?php _e('# of Pins', 'feed-them-social'); ?>')").parent('div').show();
                    }
                });

            });
            // JS
            function updateTextArea_pinterest() {
                var pinterest_name = ' pinterest_name=' + jQuery("input#pinterest_name").val();
                if (pinterest_name == " pinterest_name=") {
                    jQuery(".pinterest_name").addClass('fts-empty-error');
                    jQuery("input#pinterest_name").focus();
                    return false;
                }
                if (pinterest_name != " pinterest_name=") {
                    jQuery(".pinterest_name").removeClass('fts-empty-error');
                }
                var pinterest_board_name = ' board_id=' + jQuery("input#pinterest_board_name").val();
                if (pinterest_board_name == " board_id=" && jQuery("select#pinterest-messages-selector").val() == "single_board_pins") {
                    jQuery(".board-name").addClass('fts-empty-error');
                    jQuery("input#pinterest_board_name").focus();
                    return false;
                }
                if (pinterest_board_name != " board_id=") {
                    jQuery(".board-name").removeClass('fts-empty-error');
                }
                if (pinterest_board_name == " board_id=") {
                    var pinterest_board_name_final = '';
                }
                if (pinterest_board_name != " board_id=") {
                    var pinterest_board_name_final = pinterest_board_name;
                }
                var type = ' type=' + jQuery("select#pinterest-messages-selector").val();
                if (type == " type=") {
                    var type_final = '';
                }
                if (type != " type=") {
                    var type_final = type;
                }
                <?php
                //Premium Plugin
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/pinterest-settings-js.js');
            }
                else    { ?>
                //Generate Pinterest Shortcode
                if (jQuery("select#pinterest-messages-selector").val() == "pins_from_user") {
                    var final_pinterest_shorcode = '[fts_pinterest' + pinterest_name + type_final + ']';
                }
                else if (jQuery("select#pinterest-messages-selector").val() == "single_board_pins") {
                    var final_pinterest_shorcode = '[fts_pinterest' + pinterest_name + pinterest_board_name_final + type_final + ']';
                }
                else {
                    var final_pinterest_shorcode = '[fts_pinterest' + pinterest_name + type_final + ']';
                }
                <?php } ?>
                jQuery('.pinterest-final-shortcode').val(final_pinterest_shorcode);
                jQuery('.pinterest-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //End Pinterest
            function updateTextArea_fb_page() {
                var fb_feed_type = ' type=' + jQuery("select#facebook-messages-selector").val();
                var fb_page_id = ' id=' + jQuery("input#fb_page_id").val();
                if (jQuery("input#fb_album_id").val() == '') {
                    var fb_album_id = ' album_id=photo_stream';
                }
                else {
                    var fb_album_id = ' album_id=' + jQuery("input#fb_album_id").val();
                }
                var fb_page_posts_displayed = ' posts_displayed=' + jQuery("select#fb_page_posts_displayed").val();
                var fb_page_post_count_final_check = jQuery("input#fb_page_post_count").val();
                if (fb_page_post_count_final_check !== '') {
                    var fb_page_post_count_final = ' posts=' + jQuery("input#fb_page_post_count").val();
                } else {
                    var fb_page_post_count_final = ' posts=5';
                }
                var facebook_height = jQuery("input#facebook_page_height").val();
                // var super_gallery = ' super_gallery=' + jQuery("select#facebook-custom-gallery").val();
                var image_width = ' image_width=' + jQuery("input#fts-slicker-facebook-container-image-width").val();
                var image_height = ' image_height=' + jQuery("input#fts-slicker-facebook-container-image-height").val();
                var space_between_photos = ' space_between_photos=' + jQuery("input#fts-slicker-facebook-container-margin").val();
                var hide_date_likes_comments = ' hide_date_likes_comments=' + jQuery("select#fts-slicker-facebook-container-hide-date-likes-comments").val();
                var center_container = ' center_container=' + jQuery("select#fts-slicker-facebook-container-position").val();
                var image_stack_animation = ' image_stack_animation=' + jQuery("select#fts-slicker-facebook-container-animation").val();
                var position_lr = ' image_position_lr=' + jQuery("input#fts-slicker-facebook-image-position-lr").val();
                var position_top = ' image_position_top=' + jQuery("input#fts-slicker-facebook-image-position-top").val();
                if (fb_page_id == " id=") {
                    jQuery(".fb_page_id").addClass('fts-empty-error');
                    jQuery("input#fb_page_id").focus();
                    return false;
                }
                if (fb_page_id != " id=") {
                    jQuery(".fb_page_id").removeClass('fts-empty-error');
                }
                if (fb_album_id == " album_id=" && fb_feed_type == " type=album_videos") {
                    jQuery(".fb_album_photos_id").addClass('fts-empty-error');
                    jQuery("input#fb_album_id").focus();
                    return false;
                }
                if (facebook_height) {
                    var facebook_height_final = ' height=' + jQuery("input#facebook_page_height").val();
                }
                else {
                    var facebook_height_final = '';
                }
                var super_gallery_option = jQuery("select#facebook-custom-gallery").val();
                var albums_photos_option = jQuery("select#facebook-messages-selector").val();
                <?php
                // IF PREMIUM PLUGIN IS ACTIVE AND REVIEWS IS ACTIVE
                if(is_plugin_active('feed-them-premium/feed-them-premium.php') && !is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php') || is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/facebook-page-settings-js.js'); ?>

                        if (albums_photos_option == "album_photos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_album_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_position_likebox + like_box_width + ']';
                        }
                        else if (albums_photos_option == "album_videos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + ' video_album=yes' + fb_album_id + ' type=album_photos' + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + image_width + image_height + space_between_photos + fts_play_btn + fts_play_btn_size + fts_play_btn_visible + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + ' popup=yes' + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fts_images_align + fb_position_likebox + like_box_width + ']';
                        }
                        else if (albums_photos_option == "albums") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_position_likebox + like_box_width + ']';
                        }
                        else if (albums_photos_option == "page") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_posts_displayed + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + facebook_height_final + fb_feed_type + load_more_posts_style_final + facebook_grid + facebook_grid_space_between_posts + facebook_grid_colmn_width + center_container + image_stack_animation + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_page_title_align + fb_position_likebox + like_box_width + ']';
                        }
                        <?php
                        //Premium Plugin
                        if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                            include(WP_CONTENT_DIR . '/plugins/feed-them-social-facebook-reviews/admin/js/facebook-page-reviews-js.js');
                        } ?>

                        else {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + facebook_height_final + fb_feed_type + load_more_posts_style_final + facebook_grid + facebook_grid_space_between_posts + facebook_grid_colmn_width + center_container + image_stack_animation + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + ']';
                        }
                        <?php
                }
                // IF PREMIUM PLUGIN IS ACTIVE AND CAROUSEL AND REVIEWS IS ACTIVE ETC...
                        elseif(is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php') || is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-carousel-premium/feed-them-carousel-premium.php') && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                        include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/facebook-page-settings-js.js'); ?>

                        if (albums_photos_option == "album_photos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_album_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_position_likebox + like_box_width + slider + scrollhorz_or_carousel + slides_visible + slider_spacing + slider_margin + slider_speed + slider_timeout + slider_controls + slider_controls_text_color + slider_controls_bar_color + slider_controls_width + ']';
                        }
                        else if (albums_photos_option == "album_videos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + ' video_album=yes' + fb_album_id + ' type=album_photos' + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + image_width + image_height + space_between_photos + fts_play_btn + fts_play_btn_size + fts_play_btn_visible + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + ' popup=yes' + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fts_images_align + fb_position_likebox + like_box_width + slider + scrollhorz_or_carousel + slides_visible + slider_spacing + slider_margin + slider_speed + slider_timeout + slider_controls + slider_controls_text_color + slider_controls_bar_color + slider_controls_width + ']';
                        }
                        else if (albums_photos_option == "albums") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + load_more_posts_style_final + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_position_likebox + like_box_width + slider + scrollhorz_or_carousel + slides_visible + slider_spacing + slider_margin + slider_speed + slider_timeout + slider_controls + slider_controls_text_color + slider_controls_bar_color + slider_controls_width + ']';
                        }
                        else if (albums_photos_option == "page") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_posts_displayed + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + facebook_height_final + fb_feed_type + load_more_posts_style_final + facebook_grid + facebook_grid_space_between_posts + facebook_grid_colmn_width + center_container + image_stack_animation + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + fb_page_title_align + fb_position_likebox + like_box_width + ']';
                        }
                        <?php
                            //Premium Plugin
                            if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                                include(WP_CONTENT_DIR . '/plugins/feed-them-social-facebook-reviews/admin/js/facebook-page-reviews-js.js');
                            } ?>

                            else {
                                var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option + facebook_height_final + fb_feed_type + load_more_posts_style_final + facebook_grid + facebook_grid_space_between_posts + facebook_grid_colmn_width + center_container + image_stack_animation + facebook_popup + fts_hide_like_option + fts_like_option_align + fts_loadmore_button_width + fts_loadmore_btn_margin + fb_page_title_align + ']';
                            }
                        <?php
                        }

                 // IF NO PREMIUM PLUGINS ACTIVE
                elseif(is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-premium/feed-them-premium.php'))    { ?>
                        // Free version shortcodes for Facebook
                        if (albums_photos_option == "album_photos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_album_id + fb_page_post_count_final + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + ']';
                        }
                        else if (albums_photos_option == "albums") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + ']';
                        }
                        else if (albums_photos_option == "page") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_posts_displayed + facebook_height_final + fb_feed_type + ']';
                        }
                         <?php
                            //Premium Plugin
                            if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
                                include(WP_CONTENT_DIR . '/plugins/feed-them-social-facebook-reviews/admin/js/facebook-page-reviews-js.js');
                            } ?>

                        else {
                            // This is the group feed in free version.
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + facebook_height_final + fb_feed_type + ']';
            }
                <?php } 

                // IF NO PREMIUM PLUGINS ACTIVE
                else    { ?>
                        // Free version shortcodes for Facebook
                        if (albums_photos_option == "album_photos") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_album_id + fb_page_post_count_final + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + ']';
                        }
                        else if (albums_photos_option == "albums") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top + ']';
                        }
                        else if (albums_photos_option == "page") {
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + fb_page_posts_displayed + facebook_height_final + fb_feed_type + ']';
                        }
                        else {
                            // This is the group feed in free version.
                            var final_fb_page_shortcode = '[fts_facebook' + fb_page_id + fb_page_post_count_final + facebook_height_final + fb_feed_type + ']';
                        }
                <?php } ?>


                jQuery('.facebook-page-final-shortcode').val(final_fb_page_shortcode);
                jQuery('.fb-page-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Facebook Page//
            //START Facebook Group//
            function updateTextArea_fb_group() {
                var fb_group_id = ' id=' + jQuery("input#fb_group_id").val();
                // var fb_group_custom_name = ' custom_name=' + jQuery("select#fb_group_custom_name").val();
                var facebook_height = jQuery("input#facebook_group_height").val();
                if (fb_group_id == " id=") {
                    jQuery(".fb_group_id").addClass('fts-empty-error');
                    jQuery("input#fb_group_id").focus();
                    return false;
                }
                if (fb_group_id != " id=") {
                    jQuery(".fb_group_id").removeClass('fts-empty-error');
                }
                if (facebook_height) {
                    var facebook_height_final = ' height=' + jQuery("input#facebook_group_height").val();
                }
                else {
                    var facebook_height_final = '';
                }
                <?php
                //Premium Plugin
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/facebook-group-settings-js.js');
            }
                else    {
                ?>
                var final_fb_group_shorcode = '[fts_facebook_group' + fb_group_id + facebook_height_final + ' type=group]';
                <?php } ?>
                jQuery('.facebook-group-final-shortcode').val(final_fb_group_shorcode);
                jQuery('.fb-group-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Facebook Group//
            //START Facebook List of Events//
            function updateTextArea_fb_list_of_events() {
                var fb_event_id = ' id=' + jQuery("input#fb_page_list_of_events_id").val();
                var facebook_height = jQuery("input#facebook_event_height").val();
                if (fb_event_id == " id=") {
                    jQuery(".fb_page_list_of_events_id").addClass('fts-empty-error');
                    jQuery("input#fb_page_list_of_events_id").focus();
                    return false;
                }
                if (fb_event_id != " id=") {
                    jQuery(".fb_page_list_of_events_id").removeClass('fts-empty-error');
                }
                if (facebook_height) {
                    var facebook_height_final = ' height=' + jQuery("input#facebook_event_height").val();
                }
                else {
                    var facebook_height_final = '';
                }
                <?php
                //Premium Plugin
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/facebook-event-settings-js.js');
            }
                else    {
                ?>
                var final_fb_event_shorcode = '[fts_facebook_event' + fb_event_id + facebook_height_final + ' type=event]';
                <?php } ?>
                jQuery('.facebook-list-of-events-final-shortcode').val(final_fb_event_shorcode);
                jQuery('.fb-event-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Facebook List of Events//
            //START Facebook Single Event//
            function updateTextArea_fb_event() {
                var fb_event_id = ' id=' + jQuery("input#fb_event_id").val();
                var facebook_height = jQuery("input#facebook_event_height").val();
                if (fb_event_id == " id=") {
                    jQuery(".fb_event_id").addClass('fts-empty-error');
                    jQuery("input#fb_event_id").focus();
                    return false;
                }
                if (fb_event_id != " id=") {
                    jQuery(".fb_event_id").removeClass('fts-empty-error');
                }
                if (facebook_height) {
                    var facebook_height_final = ' height=' + jQuery("input#facebook_event_height").val();
                }
                else {
                    var facebook_height_final = '';
                }
                <?php
                //Premium Plugin
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/facebook-event-settings-js.js');
            }
                else    {
                ?>
                var final_fb_event_shorcode = '[fts_facebook_event' + fb_event_id + facebook_height_final + ' type=event]';
                <?php } ?>
                jQuery('.facebook-event-final-shortcode').val(final_fb_event_shorcode);
                jQuery('.fb-event-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Facebook Single Event//
            //START Twitter//
            function updateTextArea_twitter() {
                var twitter_name = jQuery("input#twitter_name").val();
                var twitter_search = jQuery("input#twitter_hashtag_etc_name").val();
                var twitter_height = jQuery("input#twitter_height").val();
                var tweets_count = jQuery("input#tweets_count").val();
                var show_retweets = jQuery("select#twitter-show-retweets").val();
                if (tweets_count !== '') {
                    var tweets_count_final = ' tweets_count=' + jQuery("input#tweets_count").val();
                } else {
                    var tweets_count_final = ' tweets_count=5';
                }
                if (twitter_height) {
                    var twitter_height_final = ' twitter_height=' + jQuery("input#twitter_height").val();
                } else {
                    var twitter_height_final = ' twitter_height=auto';
                }

                jQuery('#twitter-messages-selector').bind('change', function (e) {
                    if (jQuery('#twitter-messages-selector').val() == 'hashtag') {
                        jQuery(".twitter_name").removeClass('fts-empty-error');
                    }
                });


                if (twitter_name == "" && jQuery('#twitter-messages-selector').val() !== 'hashtag') {
                    jQuery(".twitter_name").addClass('fts-empty-error');
                    jQuery("input#twitter_name").focus();
                    return false;
                }
                else if (twitter_name != "") {
                    jQuery(".twitter_name").removeClass('fts-empty-error');
                    var twitter_name = ' twitter_name=' + jQuery("input#twitter_name").val();
                }
                if (twitter_search == "" && jQuery('#twitter-messages-selector').val() == 'hashtag') {
                    jQuery(".twitter_hashtag_etc_name").addClass('fts-empty-error');
                    jQuery("input#twitter_hashtag_etc_name").focus();
                    return false;
                }
                else if (twitter_search != "" && jQuery('#twitter-messages-selector').val() == 'hashtag') {
                    jQuery(".twitter_search").removeClass('fts-empty-error');
                    var twitter_search_final = ' search=' + jQuery("input#twitter_hashtag_etc_name").val();
                }
                else {
                    var twitter_search_final = '';
                }
                if (twitter_height) {
                    var twitter_height_final = ' twitter_height=' + jQuery("input#twitter_height").val();
                }
                else {
                    var twitter_height_final = '';
                }
                if (show_retweets) {
                    var show_retweets = ' show_retweets=' + jQuery("select#twitter-show-retweets").val();
                }
                else {
                    var show_retweets = '';
                }
                <?php
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/twitter-settings-js.js');
            }
                else    { ?>
                var final_twitter_shorcode = '[fts_twitter' + twitter_name + twitter_search_final + tweets_count_final + twitter_height_final + show_retweets + ']';
                <?php } ?>
                jQuery('.twitter-final-shortcode').val(final_twitter_shorcode);
                jQuery('.twitter-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Twitter//
            //START Vine//
            function updateTextArea_vine() {
                var vine_id = ' id=' + jQuery("input#vine_id").val();
                var vine_maxwidth = jQuery("input#vine_maxwidth").val();
                var space_between_photos = jQuery("input#space_between_photos").val();
                var round_thumb_corner_size = jQuery("input#round_thumb_corner_size").val();

                if (vine_id == " id=") {
                    jQuery(".vine_id").addClass('fts-empty-error');
                    jQuery("input#vine_id").focus();
                    return false;
                }
                if (vine_id != " id=") {
                    jQuery(".vine_id").removeClass('fts-empty-error');
                }
                if (vine_maxwidth) {
                    var vine_maxwidth = ' maxwidth=' + jQuery("input#vine_maxwidth").val();
                }
                else {
                    var vine_maxwidth = ' maxwidth=200px';
                }
                if (space_between_photos) {
                    var space_between_photos = ' space_between_photos=' + jQuery("input#space_between_photos").val();
                }
                else {
                    var round_thumb_corner_size = '';
                }
                if (round_thumb_corner_size) {
                    var round_thumb_corner_size = ' round_thumb_corner_size=' + jQuery("input#round_thumb_corner_size").val();
                }
                else {
                    var round_thumb_corner_size = '';
                }
                <?php
                if(is_plugin_active('feed-them-premium/feed-them-premium.php') && file_exists(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/vine-settings-js.js')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/vine-settings-js.js');
            }
                else    { ?>
                var final_vine_shorcode = '[fts_vine' + vine_id + vine_maxwidth + space_between_photos + round_thumb_corner_size + ']';
                <?php } ?>
                jQuery('.vine-final-shortcode').val(final_vine_shorcode);
                jQuery('.vine-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Vine//
            //START Instagram//
            function updateTextArea_instagram() {
                var instagram_id = ' instagram_id=' + jQuery("input#instagram_id").val();
                var instagram_page_height = jQuery("input#instagram_page_height").val();
                var super_gallery = ' super_gallery=' + jQuery("select#instagram-custom-gallery").val();
                var image_size = ' image_size=' + jQuery("input#fts-slicker-instagram-container-image-size").val();
                var icon_size = ' icon_size=' + jQuery("input#fts-slicker-instagram-icon-center").val();
                var space_between_photos = ' space_between_photos=' + jQuery("input#fts-slicker-instagram-container-margin").val();
                var hide_date_likes_comments = ' hide_date_likes_comments=' + jQuery("select#fts-slicker-instagram-container-hide-date-likes-comments").val();
                var center_container = ' center_container=' + jQuery("select#fts-slicker-instagram-container-position").val();
                var image_stack_animation = ' image_stack_animation=' + jQuery("select#fts-slicker-instagram-container-animation").val();
                var instagram_feed_type = ' type=' + jQuery("select#instagram-messages-selector").val();
                var instagram_popup_option = ' popup=' + jQuery("select#instagram-popup-option").val();
                var pics_count = jQuery("input#pics_count").val();
                if (pics_count !== '') {
                    var pics_count_final = ' pics_count=' + jQuery("input#pics_count").val();
                } else {
                    var pics_count_final = ' pics_count=5';
                }
                if (instagram_id == " instagram_id=") {
                    jQuery(".instagram_name").addClass('fts-empty-error');
                    jQuery("input#instagram_id").focus();
                    return false;
                }
                if (instagram_id != " instagram_id=") {
                    jQuery(".instagram_name").removeClass('fts-empty-error');
                }
                if (instagram_page_height != "") {
                    var instagram_page_height_final = ' height=' + jQuery("input#instagram_page_height").val();
                }
                else {
                    var instagram_page_height_final = '';
                }
                <?php
                //Premium Plugin
                if(is_plugin_active('feed-them-premium/feed-them-premium.php') && WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/vine-settings-js.js') {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/instagram-settings-js.js');
            }//end if Premium version
                else    { ?>
                if (jQuery("select#instagram-custom-gallery").val() == "no") {
                    var final_instagram_shorcode = '[fts_instagram' + instagram_id + pics_count_final + instagram_feed_type + instagram_page_height_final + ']';
                }
                else {
                    var final_instagram_shorcode = '[fts_instagram' + instagram_id + pics_count_final + super_gallery + image_size + icon_size + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + instagram_feed_type + instagram_page_height_final + ']';
                }
                <?php } ?>
                jQuery('.instagram-final-shortcode').val(final_instagram_shorcode);
                jQuery('.instagram-shortcode-form .final-shortcode-textarea').slideDown();
            }
            //END Instagram//
            //START convert Instagram name to id//
            function converter_instagram_username() {
                var convert_instagram_username = jQuery("input#convert_instagram_username").val();
                if (convert_instagram_username == "") {
                    jQuery(".convert_instagram_username").addClass('fts-empty-error');
                    jQuery("input#convert_instagram_username").focus();
                    return false;
                }
                if (convert_instagram_username != "") {
                    jQuery(".convert_instagram_username").removeClass('fts-empty-error');
                    var username = jQuery("input#convert_instagram_username").val();
                    console.log(username);

                    <?php $fts_instagram_tokens_array = array('9844495a8c4c4c51a7c519d0e7e8f293', '9844495a8c4c4c51a7c519d0e7e8f293');
                    $fts_instagram_access_token = $fts_instagram_tokens_array[array_rand($fts_instagram_tokens_array, 1)];
                    ?>
                    jQuery.getJSON("https://api.instagram.com/v1/users/search?q=" + username + "&client_id=<?php echo $fts_instagram_access_token; ?>&access_token=258559306.da06fb6.c222db6f1a794dccb7a674fec3f0941f&callback=?",

                        {
                            format: "json"
                        },
                        function (data) {
                            console.log(data);
                            var final_instagram_us_id = data.data[0].id;
                            jQuery('#instagram_id').val(final_instagram_us_id);
                            jQuery('.final-instagram-user-id-textarea').slideDown();
                        });
                }
            }
            //select all
            jQuery(".copyme").focus(function () {
                var jQuerythis = jQuery(this);

                // we call this function again on copy me to append any values that did not get a px on them
                updateTextArea_fb_page();
                updateTextArea_twitter();
                updateTextArea_vine();
                updateTextArea_instagram();

                jQuerythis.select();
                // Work around Chrome's little problem
                jQuerythis.mouseup(function () {
                    // Prevent further mouseup intervention
                    jQuerythis.unbind("mouseup");
                    return false;
                });
            });
            jQuery(document).ready(function () {
                jQuery(".toggle-custom-textarea-show").click(function () {
                    jQuery('textarea#fts-color-options-main-wrapper-css-input').slideToggle();
                    jQuery('.toggle-custom-textarea-show span').toggle();
                    jQuery('.fts-custom-css-text').toggle();
                });

                // START: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.
                jQuery("#fb_page_id").change(function () {
                    var feedID = jQuery("input#fb_page_id").val();
                    if (feedID.indexOf('facebook.com') != -1 || feedID.indexOf('facebook.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#fb_page_id').val(newfeedID);
                        return;
                    }
                });

                jQuery("#twitter_name").change(function () {
                    var feedID = jQuery("input#twitter_name").val();
                    if (feedID.indexOf('twitter.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#twitter_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#convert_instagram_username").change(function () {
                    var feedID = jQuery("input#convert_instagram_username").val();
                    if (feedID.indexOf('instagram.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#convert_instagram_username').val(newfeedID);
                        return;
                    }
                });

                jQuery("#pinterest_board_name").change(function () {
                    var feedID = jQuery("input#pinterest_board_name").val();
                    if (feedID.indexOf('pinterest.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#pinterest_board_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#pinterest_name").change(function () {
                    var feedID = jQuery("input#pinterest_name").val();
                    if (feedID.indexOf('pinterest.com') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#pinterest_name').val(newfeedID);
                        return;
                    }
                });

                <?php
                //show the js for the discount option under social icons on the settings page
                // also show the youtube feed on change events
                if(is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>

                jQuery("#youtube_name").change(function () {
                    var feedID = jQuery("input#youtube_name").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_name2").change(function () {
                    var feedID = jQuery("input#youtube_name2").val();
                    if (feedID.indexOf('youtube.com/user') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_name2').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_channelID").change(function () {
                    var feedID = jQuery("input#youtube_channelID").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_channelID2").change(function () {
                    var feedID = jQuery("input#youtube_channelID2").val();
                    if (feedID.indexOf('youtube.com/channel') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('/') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_channelID2').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_playlistID").change(function () {
                    var feedID = jQuery("input#youtube_playlistID").val();
                    if (feedID.indexOf('youtube.com/playlist?list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID').val(newfeedID);
                        return;
                    }
                });

                jQuery("#youtube_playlistID2").change(function () {
                    var feedID = jQuery("input#youtube_playlistID2").val();
                    if (feedID.indexOf('youtube.com/playlist?list=') != -1) {
                        feedID = feedID.replace(/\/$/, '');
                        feedID = feedID.substr(feedID.lastIndexOf('=') + 1);
                        var newfeedID = feedID;
                        jQuery('#youtube_playlistID2').val(newfeedID);
                        return;
                    }
                });
                // END: Fix issues when people enter the full url instead of just the ID or Name. We'll truncate this at a later date.
                <?php    }
                else { ?>
                jQuery("#discount-for-review").click(function () {
                    jQuery('.discount-review-text').slideToggle();
                });
                <?php } ?>
            }); //end document ready

            <?php
            //Premium JS
            if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
                include(WP_CONTENT_DIR . '/plugins/feed-them-premium/admin/js/youtube-settings-js.js');
            }
            ?>


            jQuery('#fb_hide_like_box_button_reviews').change(function () {
                jQuery('.fb_align_likebox_reviews').toggle();

            });

            // Like box/button Options Premium Content
            jQuery('#facebook-messages-selector').change(function () {
                if (jQuery("select#facebook-messages-selector").val() == "group" || jQuery("select#facebook-messages-selector").val() == "event" || jQuery("select#facebook-messages-selector").val() == "events" || jQuery("select#facebook-messages-selector").val() == "reviews") {
                    jQuery('.like-box-wrap').hide();
                    // alert(jQuery("select#facebook-messages-selector").val());
                }
                else {
                    jQuery('.like-box-wrap').show();
                }
            });

            // Carousel and Slideshow Premium Content
            jQuery('#facebook-messages-selector').change(function () {
                if (jQuery("select#facebook-messages-selector").val() == "album_photos" || jQuery("select#facebook-messages-selector").val() == "album_videos") {
                    jQuery('.slideshow-wrap').show();
                    // alert(jQuery("select#facebook-messages-selector").val());
                }
                else {
                    jQuery('.slideshow-wrap').hide();
                }
            });
            jQuery('#scrollhorz_or_carousel').change(function () {
                jQuery('.slider_carousel_wrap').toggle();
            });
            jQuery('#fts-slider').change(function () {
                jQuery('.slider_options_wrap').toggle();
            });
        </script>
    <?php }
}//END Class