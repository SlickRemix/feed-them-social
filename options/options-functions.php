<?php
/**
 * Option Functions
 *
 * @package     FeedThemSocial
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2021, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace feedthemsocial;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

/**
 * Option Functions Class.
 */
class Options_Functions {

    public $post_type_name;

	/**
	 * Settings Function constructor.
	 */
	public function __construct( $post_type_name ){
        $this->add_actions_filters();

		// Post Type Name.
		$this->post_type_name = $post_type_name;
	}

	/**
	 * Add Actions & Filters
	 *
	 * Adds the Actions and filters for the class.
	 *
	 * @since 1.1.8
	 */
	public function add_actions_filters() {
        // Update Options Filter
		add_filter( 'fts_update_single_option', array( $this, 'update_single_option' ), 10, 2 );

		// Get Options Array Filter
		add_filter( 'fts_get_options_array', array( $this, 'get_options_array' ), 10, 1 );
    }

	/**
	 * Check User Manage Options.
	 *
	 * Check if the current user can manage options.
	 *
	 * @since	3.0.0
	 */
	function check_user_manage_options() {
        // Can Current User Manage Options? If not Die!
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized user' );
		}
	}

	/**
	 * Check CPT Exists
	 *
	 * Check to see if CPT post exists by post id.
	 *
	 * @return boolean
	 */
	public function check_cpt_exists( $cpt_id ) {

		$cpt_status = is_string( get_post_status( $cpt_id ) );

		// Make sure CPT is an ID and then return status of post existing.
		return $cpt_status;
	}

	/**
	 * Delete Options Array
	 *
	 * Delete Options Array using Array Options Name.
	 *
	 * @return
	 */
	public function delete_options_array( $array_option_name, $is_cpt, $cpt_id ) {
		// If CPT use get_post_meta.
		if( $is_cpt ){
			// Check if CPT ID is set check to see if it actually exists.
			$cpt_exists = $this->check_cpt_exists( $cpt_id );

			// If CPT ID exists Delete post meta
            if( $cpt_exists ){
	            delete_post_meta( $cpt_id, $array_option_name );
            }
		}
		// Delete Option.
		else {
		    delete_option( $array_option_name );
		}

		error_log( print_r( 'Options Array Deleted!', true ) );
		// Return Saved Options Array or false.
		return;
	}

	/**
	 * Get Single Option
	 *
	 * Gets a single option from array saved in database.
	 *
	 * @since	3.0.0
	 * @return	mixed
	 */
	function get_single_option( $array_option_name, $option_name = '', $default = false, $is_cpt = false, $cpt_id = false ) {
		$options = get_option( $array_option_name );

		$value = ! empty( $options[ $option_name ] ) ? $options[ $option_name ] : $default;
		$value = apply_filters( 'fts_get_single_option', $value, $option_name, $default );

		return apply_filters( 'fts_get_single_option_' . $option_name, $value, $option_name, $default );
	}

	/**
	 * Update Single Option
	 *
	 * Updates a single option from the array that is saved in the database.
	 * Warning: Passing in an empty, false or null string value will remove
	 *          the key from the option's array.
	 *
	 * @since	3.0.0
	 * @param	string            $option_name    The Key to update
	 * @param	string|bool|int   $value  The value to set the key to
	 */
	public function update_single_option( $array_option_name, $option_name = '', $value = false, $is_cpt = false, $cpt_id = false ) {

		// Can Current User Manage Options? If not Die!
        $this->check_user_manage_options();

		// If no Option Name, exit!
		if ( empty( $option_name ) ){
			return false;
		}

		// Return Saved options if there are any.
		$saved_options_array = $this->get_saved_options_array( $array_option_name, $is_cpt, $cpt_id);

		//Update Saved Options.
		if( $saved_options_array ){
            // Delete Option if no value is set.
			if ( $saved_options_array[ $option_name ] && empty( $value ) ) {
				$this->unset_array_option( $saved_options_array, $option_name );
				return false;
			}

            // If anything has changed update options!
            $saved_options_array[ $option_name ] = is_array( $value ) ? $value : sanitize_text_field( $value );

			error_log( print_r( $saved_options_array, true ) );

			// Save Options Array based on CPT or Page
			$this->save_options_array( $array_option_name, $saved_options_array, $is_cpt, $cpt_id);
		}
		// Saved Array doesn't exist. Nothing to delete.
		return false;
	}

	/**
	 * Delete Single Option
	 *
	 * Removes a single option from the array that is saved in the database.
	 *
	 * @since	1.0
	 * @param	string		$option_name	The option to delete.
	 */
	public function delete_single_option( $array_option_name, $option_name = '', $is_cpt = false, $cpt_id = false ) {

		// Can Current User Manage Options? If not Die!
		$this->check_user_manage_options();

		// If no Array Option Name or option name isset, exit.
		if ( $array_option_name || empty( $option_name ) ){
			return false;
		}
        
		// Get saved options array if it exists.
		$saved_options_array = $this->get_saved_options_array( $array_option_name, $is_cpt, $cpt_id );

        // Saved array must exist to delete an option.
        if($saved_options_array){
	        // Unset Option to ensure only new option value is saved.
	        $this->unset_array_option( $saved_options_array, $option_name);

	        // Save Options Array based on CPT or Page
	        $this->save_options_array( $array_option_name, $saved_options_array, $is_cpt, $cpt_id);
        }
        // Saved Array doesn't exist. Nothing to delete.
		return false;
	}

	/**
	 * Unset Array Option
	 *
	 * Unsets Option from array. (To delete an option from a saved options array use delete_single_option.)
	 *
	 * @since	3.0.0
     * @param	array	$options_array	The options array.
	 * @param	string	$option_name	The option to delete.
	 * @return	array	True if updated, false if not.
	 */
	public function unset_array_option( $options_array, $option_name ) {
		// Unset Option.
		if( isset( $options_array[ $option_name ] ) ) {
			unset( $options_array[ $option_name ] );
		}

        return $options_array;
	}

	/**
	 * Get Saved Options Array.
	 *
	 * Retrieves Options Array based on options array name.
	 *
	 * @since	3.0.0
	 * @return	array | boolean Saved Options Array or false.
	 */
	public function get_saved_options_array( $array_option_name, $is_cpt = false, $cpt_id = false ) {
        // If CPT use get_post_meta.
        if( $is_cpt ){
	        // If CPT ID is set check to see if it actually exists.
	        $cpt_exists = $this->check_cpt_exists( $cpt_id );

	        // If CPT ID is set and exists use get_post_meta. Otherwise, use get_option.
	        $saved_options_array = $cpt_exists ? get_post_meta( $cpt_id, $array_option_name,true ) : false;
        }
        // Page is being used. Use get_option.
        else{
	        $saved_options_array = get_option( $array_option_name );
        }

        // Return Saved Options Array or false.
		return $saved_options_array;
	}

	/**
	 * Set Options In Array
	 *
	 * Set the options by in an array.
	 *
	 * @since	3.0.0
	 * @return	array | boolean Updated Saved Options Array.
	 */
	public function set_options_in_array( $default_options_array, $set_default = true ) {
		// Go through default options array. (Usually set in an options file)
		foreach ( $default_options_array as $option_section ) {
			// Options section is a group of options.
			foreach ( $option_section as $option_section_key => $main_options ) {
				// Only Load the main options key.
				if ( 'main_options' === $option_section_key ) {
					// Loop through the options array.
					foreach ( $main_options as $option ) {
                        // Option name.
                        $option_name = $option['name'] ?? '';
                        // Option type.
                        $option_type = $option['option_type'] ?? '';

                        if( $set_default ){
                            // Option Default Value.
                            $option_default_value = $option['default_value'] ?? '';

                            // Ensure option name and Default value exists if so set default to new array.
                            if ( ! empty( $option_name ) && ! empty( $option_default_value ) ) {
                                // Set Default_value.
                                $array_to_save[ $option_name ] = $option_default_value;
                            }
                        }

                        // Is the option type a checkbox? If so, set boolean strings.
                        if ( 'checkbox' === $option_type ) {
                            $inputted_value = isset( $_POST[ $option_name ] ) && 'false' !== $_POST[ $option_name ] ? 'true' : 'false';
                        } else {
                            $inputted_value = isset( $_POST[ $option_name ] ) && ! empty( $option_name ) ? wp_unslash( $_POST[ $option_name ] ) : false;
                        }

                        if( $inputted_value ){
                            // If anything has changed update options!
                            $array_to_save[ $option_name ] = is_array( $inputted_value ) ? $inputted_value : sanitize_text_field( $inputted_value );
                        }
					}
				}
			}
		}

		// Return Saved Options Array or false.
		return $array_to_save;
	}

	/**
	 * Update Options Array.
	 *
	 * Retrieves Options Array based on options array name.
	 *
	 * @since	3.0.0
	 */
	public function update_options_array( $array_option_name, $default_options_array, $is_cpt = false, $cpt_id = false ) {
		// Can Current User Manage Options? If not Die!
		$this->check_user_manage_options();

        // Return Saved options if there are any.
		$saved_options_array = $this->get_saved_options_array( $array_option_name, $is_cpt, $cpt_id);

        //Update Saved Options.
        if( $saved_options_array ){
	        $array_to_save = $this->set_options_in_array( $default_options_array, false );
        }
        // Create Initial Options Array.
        else{
	        $array_to_save = $this->create_initial_options_array( $default_options_array );
        }

        // Save Options Array based on CPT or Page
		$this->save_options_array( $array_option_name, $array_to_save, $is_cpt, $cpt_id);
	}

	/**
	 * Create Initial Options Array
	 *
	 * Creates an array based on default options if nothing else is set.
	 *
	 * @return array | boolean
	 */
	public function create_initial_options_array( $default_options_array ) {

		return $this->set_options_in_array( $default_options_array,true );
	}

	/**
	 * Save Options Array.
	 *
	 * Save the options for CPT or Page. (CPT uses update_post_meta and Pages use update_option
	 *
	 * @since	3.0.0
	 * @return	array | boolean Saved Options Array or false.
	 */
	public function save_options_array( $array_option_name,  $array_to_save, $is_cpt = false, $cpt_id = false ) {

		error_log( print_r( 'made it too save_options_array', true ) );

		error_log( print_r( $array_to_save, true));
		// If CPT use get_post_meta.
		if( $is_cpt ){
			// Update the CPT Options Array
			update_post_meta( $cpt_id, $array_option_name, $array_to_save );

			return $array_to_save;
		}
		// Page is being used. Use update_option.
		else{
			// Update options for a page.
			update_option( $array_option_name, $array_to_save );

			// // If Page - then Safe Redirect to page we came from. To make the Coding Standards happy, we have to initialize this.
			if ( ! isset( $_POST['_wp_http_referer'] ) ) {
				$_POST['_wp_http_referer'] = wp_login_url();
			}

			// Sanitize the value of the $_POST collection for the Coding Standards.
			$url = sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) );

			wp_safe_redirect( urldecode( $url ) );
			exit;
		}
	}
}