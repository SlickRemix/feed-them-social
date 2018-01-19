<?php
namespace feedthemsocial;
/**
 * Class FTS Pinterest Options Page
 * 
 * @package feedthemsocial
 * @since 1.9.6
 */
class FTS_pinterest_options_page {
	/**
	 * FTS_pinterest_options_page constructor.
     */
	function __construct() {
	}
	
	/**
	 * Feed Them Pinterest Options Page
	 *
	 * @since 1.9.6
     */
	function feed_them_pinterest_options_page() {
	$fts_functions = new feed_them_social_functions();
		$fts_pinterest_access_token = get_option('fts_pinterest_custom_api_token');
		$fts_pinterest_show_follow_btn = get_option('pinterest_show_follow_btn');
		$fts_pinterest_show_follow_btn_where = get_option('pinterest_show_follow_btn_where');
		
?>
	<div class="feed-them-social-admin-wrap">
	  <h1>
	    <?php _e('Pinterest Feed Options', 'feed-them-social'); ?>
	  </h1>
	  <div class="use-of-plugin">
	    <?php _e('Add a follow button and position it using the options below. This option will not work for combined feeds.', 'feed-them-social'); ?>
        <?php _e('', 'feed-them-social'); ?>
	  </div>

	      
	  <!-- custom option for padding -->
	  <form method="post" class="fts-pinterest-feed-options-form" action="options.php">
	  
	    	 	<?php settings_fields('fts-pinterest-feed-style-options'); ?>








          <div class="feed-them-social-admin-input-wrap" style="padding-top: 0px"><div class="fts-title-description-settings-page" >
                  <h3>
                      <?php _e('Pinterest Access Token', 'feed-them-social'); ?>
                  </h3><p>
                      <?php _e('This is required to make the feed work. Just click the button below and it will connect to your Pinterest account to get an access token, and it will return it in the input below. Then just click the save button and you will now be able to generate your Pinterest feed. If you are having troubles with the button you can also get your Access Token <a href="http://www.slickremix.com/docs/how-to-create-a-pinterest-access-token/" target="_blank">here.</a> ', 'feed-them-social'); ?>
                  </p>
                  <p><a href="https://api.pinterest.com/oauth/?response_type=token&redirect_uri=https://www.slickremix.com/pinterest-token-plugin/&client_id=4852080225414031681&scope=read_public&state=<?php echo admin_url('admin.php?page=fts-pinterest-feed-styles-submenu-page');?>" class="fts-pinterest-get-access-token">
                          <?php _e('Log in and get my Access Token'); ?>
                      </a></p>
              </div>





              <div class="fts-clear"></div>

              <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0px;">
                  <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
                      <?php _e('Access Token Required', 'feed-them-social'); ?>
                  </div>
                  <script>
                      jQuery(document).ready(function ($) {
                          function getQueryString(Param) {
                              return decodeURI(
                                  (RegExp('[#|&]' + Param + '=' + '(.+?)(&|$)').exec(location.hash) || [, null])[1]
                              );
                          }

                          if (window.location.hash) {
                              $('#fts_pinterest_custom_api_token').val('');
                              $('#fts_pinterest_custom_api_token').val($('#fts_pinterest_custom_api_token').val() + getQueryString('access_token'));
                          }
                      });
                  </script>
                  <input type="text" name="fts_pinterest_custom_api_token" class="feed-them-social-admin-input" id="fts_pinterest_custom_api_token" value="<?php echo get_option('fts_pinterest_custom_api_token'); ?>"/>
                  <div class="fts-clear"></div>
              </div>



              <?php


              //Get Data for Instagram
              $response = wp_remote_fopen('https://api.pinterest.com/v1/me/?access_token='.$fts_pinterest_access_token.'&id');
              //Error Check
              $test_app_token_response = json_decode($response);

              //	echo'<pre>';
              //	 print_r($test_app_token_response);
              //	echo'</pre>';

              // Error Check
              if (!isset($test_app_token_response->status) && !empty($fts_pinterest_access_token) ) {
                  echo '<div class="fts-successful-api-token">' . __('Your access token is working! Generate your shortcode on the <a href="admin.php?page=feed-them-settings-page">settings page</a>.', 'feed-them-social') . '</div>';
              } elseif (isset($test_app_token_response->status) && !empty($fts_pinterest_access_token)) {
                  echo '<div class="fts-failed-api-token">' . __('Oh No something\'s wrong.', 'feed-them-social') . ' ' . $test_app_token_response->message . __('Please try again, if you are still having troulbes please contact us on our Support Forum. Make sure to include screenshots of the browser page that may come up with any errors. <a href="http://www.slickremix.com/support-forum/forum/feed-them-social-2/">http://www.slickremix.com/support-forum/forum/feed-them-social-2/</a>', 'feed-them-social') .'</div>';
              }
              if(empty($fts_pinterest_access_token)){
                  echo'<div class="fts-failed-api-token">'. __('You are required to get an access token to view your any of the Pinterest Feeds. Click Save all Changes after getting your Access Token.', 'feed-them-social').'</div>';
              }
              ?>

              <div class="fts-clear"></div>
          </div>
          <!--/fts-pinterest-feed-styles-input-wrap-->


	
	   <div class="feed-them-social-admin-input-wrap">
           <div class="fts-title-description-settings-page" >
               <h3>
                   <?php _e('Follow Button Options', 'feed-them-social'); ?>
               </h3>
               <?php _e('This will only show on regular feeds not combined feeds.', 'feed-them-social'); ?>
           </div>
	           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Show Follow Button', 'feed-them-social'); ?></div>
	    
	    <select name="pinterest_show_follow_btn" id="pinterest-show-follow-btn" class="feed-them-social-admin-input">
			  <option '<?php echo selected($fts_pinterest_show_follow_btn, 'no', false ) ?>' value="no"><?php _e('No', 'feed-them-social'); ?></option>
	  		  <option '<?php echo selected($fts_pinterest_show_follow_btn, 'yes', false ) ?>' value="yes"><?php _e('Yes', 'feed-them-social'); ?></option>
	    </select>
	
	      <div class="fts-clear"></div>
	 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
	      
	      
	      <div class="feed-them-social-admin-input-wrap">
	           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Placement of the Buttons', 'feed-them-social'); ?></div>
	    	
	    <select name="pinterest_show_follow_btn_where" id="pinterest-show-follow-btn-where" class="feed-them-social-admin-input">
			  <option ><?php _e('Please Select Option', 'feed-them-social'); ?></option>
			  <option '<?php echo selected($fts_pinterest_show_follow_btn_where, 'pinterest-follow-above', false ) ?>' value="pinterest-follow-above"><?php _e('Show Above Feed', 'feed-them-social'); ?></option>
	  		  <option '<?php echo selected($fts_pinterest_show_follow_btn_where, 'pinterest-follow-below', false ) ?>' value="pinterest-follow-below"><?php _e('Show Below Feed', 'feed-them-social'); ?></option>
	    </select>
	
	      <div class="fts-clear"></div>
	 	  </div><!--/fts-twitter-feed-styles-input-wrap-->



		  <div class="feed-them-social-admin-input-wrap">
			  <div class="fts-title-description-settings-page">
				  <h3>
					  <?php _e('Boards List Style Options', 'feed-them-social'); ?>
				  </h3>
				  <?php _e('These styles are for the list of Boards type feed, <a href="http://feedthemsocial.com/pinterest/">as seen here</a>.', 'feed-them-social'); ?>
			  </div>
			  <div class="feed-them-social-admin-input-label fts-fb-text-color-label">
				  <?php _e('Board Title Color', 'feed-them-social'); ?>
			  </div>
			  <input type="text" name="pinterest_board_title_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="pinterest_board_title_color" placeholder="#555555" value="<?php echo get_option('pinterest_board_title_color'); ?>"/>
			  <div class="fts-clear"></div>
		  </div>
		  <!--/fts-facebook-feed-styles-input-wrap-->
		  <div class="feed-them-social-admin-input-wrap">
			  <div class="feed-them-social-admin-input-label fts-fb-text-color-label">
				  <?php _e('Board Title Size', 'feed-them-social'); ?>
			  </div>
			  <input type="text" name="pinterest_board_title_size" class="feed-them-social-admin-input" placeholder="16px" value="<?php echo get_option('pinterest_board_title_size'); ?>"/>
			  <div class="fts-clear"></div>
		  </div>
		  <!--/fts-facebook-feed-styles-input-wrap-->
		  <div class="feed-them-social-admin-input-wrap">
			  <div class="feed-them-social-admin-input-label fts-fb-link-color-label">
				  <?php _e('Background on Hover', 'feed-them-social'); ?>
			  </div>
			  <input type="text" name="pinterest_board_backg_hover_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}" id="pinterest_board_backg_hover_color" placeholder="#FFF" value="<?php echo get_option('pinterest_board_backg_hover_color'); ?>"/>
			  <div class="fts-clear"></div>
		  </div>
		  <!--/fts-facebook-feed-styles-input-wrap-->


		
	     
	    <div class="fts-clear"></div>
	    <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
	  </form>
	  <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
	<!--/feed-them-social-admin-wrap-->

<?php } 
}//END Class