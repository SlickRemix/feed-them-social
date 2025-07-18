<?php
/**
 * Register Settings.
 *
 * @package     FeedThemSocial
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2024, SlickRemix
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace feedthemsocial\admin\settings;

// Exit if accessed directly
if ( ! \defined( 'ABSPATH' ) ){
    exit;
}

/**
 * Gallery
 *
 * @package FeedThemSocial/Core
 */
class SettingsFunctions {

    /**
     * Settings Function constructor.
     */
    public function __construct(){
        $this->addActionsFilters();
    }

    /**
     * Add Actions & Filters
     *
     * Adds the Actions and filters for the class.
     *
     * @since 1.1.8
     */
    public function addActionsFilters() {

        // Update Options Filter
        add_filter( 'fts_update_option', array( $this, 'fts_update_option' ), 10, 2 );

        // Get Settings Filter
        add_filter( 'fts_get_settings', array( $this, 'fts_get_settings' ), 10, 1 );

        // Sanitize Text Field Filter
        add_filter( 'fts_settings_sanitize_text', array( $this, 'ftsSanitizeTextField' ), 10, 1 );

        // After Setting Output Filter
        add_filter( 'fts_after_setting_output', array( $this, 'ftsAddSettingTooltip' ), 10, 2 );
    }


    /**
     * Get an option
     *
     * Looks to see if the specified setting exists, returns default if not
     *
     * @since    1.0.0
     * @return    mixed
     */
    public function fts_get_option($key = '', $default = false ) {
        $options = get_option( 'fts_settings' );

        $value = ! empty( $options[ $key ] ) ? $options[ $key ] : $default;
        $value = apply_filters( 'fts_get_option', $value, $key, $default );

        return apply_filters( 'fts_get_option_' . $key, $value, $key, $default );
    } // fts_get_option


    /**
     * Update an option
     *
     * Updates a ftg setting value in both the db and the global variable.
     * Warning: Passing in an empty, false or null string value will remove
     *          the key from the fts_options array.
     *
     * @since    1.0.0
     * @param    string            $key    The Key to update
     * @param    string|bool|int   $value  The value to set the key to
     * @return    bool              True if updated, false if not.
     */
    public function fts_update_option( $key = '', $value = false ) {

        // If no key, exit
        if ( empty( $key ) ){
            return false;
        }

        if ( empty( $value ) ) {
            return $this->fts_delete_option( $key );
        }

        // First let's grab the current settings
        $options = get_option( 'fts_settings' );

        // Let's let devs alter that value coming in
        $value = apply_filters( 'fts_update_option', $value, $key );

        // Next let's try to update the value
        $options[ $key ] = $value;
        $did_update = update_option( 'fts_settings', $options );

        // If it updated, let's update the global variable
        if ( $did_update ){
            global $fts_options;
            $fts_options[ $key ] = $value;

        }

        return $did_update;
    } // fts_update_option

    /**
     * Remove an option.
     *
     * Removes a ftg setting value in both the db and the global variable.
     *
     * @since    1.0
     * @param    string        $key    The Key to delete.
     * @return    bool    True if updated, false if not.
     */
    public function fts_delete_option( $key = '' ) {

        // If no key, exit
        if ( empty( $key ) ){
            return false;
        }

        // First let's grab the current settings
        $options = get_option( 'fts_settings' );

        // Next let's try to update the value
        if( isset( $options[ $key ] ) ) {

            unset( $options[ $key ] );

        }

        $did_update = update_option( 'fts_settings', $options );

        // If it updated, let's update the global variable
        if ( $did_update ){
            global $fts_options;
            $fts_options = $options;
        }

        return $did_update;
    } // fts_delete_option

    /**
     * Get Settings.
     *
     * Retrieves all plugin settings.
     *
     * @since    1.0
     * @return    arr        FTG settings.
     */
    public function fts_get_settings() {
        $settings = get_option( 'fts_settings' );

        // If no settings are found create an empty option in database.
        if( empty( $settings ) ) {
            $settings = array();
            update_option( 'fts_settings', $settings );
        }

        return $settings;
    } // fts_get_settings

    /**
     * Sanitize text fields
     *
     * @since    1.0.0
     * @param    string        $input    The field value
     * @return    string        $input    Sanitizied value
     */
    public function ftsSanitizeTextField( $input ) {
        return trim( $input );
    } // ftsSanitizeTextField

    /**
     * Sanitize HTML Class Names
     *
     * @since    1.0.0
     * @param    string|array    $class    HTML Class Name(s)
     * @return    string            $class
     */
    public function ftsSanitizeHtmlClass( $class = '' ) {

        if ( \is_string( $class ) )    {
            $class = sanitize_html_class( $class );
        } else if ( \is_array( $class ) )    {
            $class = array_values( array_map( 'sanitize_html_class', $class ) );
            $class = implode( ' ', array_unique( $class ) );
        }

        return $class;

    } // ftsSanitizeHtmlClass

    /**
     * Adds the tooltip after the setting field.
     *
     * @since    1.0.0
     * @param    string        $html    HTML output
     * @param    array        $args    Array containing tooltip title and description
     * @return    string        Filtered HTML output
     */
    public function ftsAddSettingTooltip( $html, $args ) {
        // ! empty( $args['tooltip_title'] ) && ..... <strong>' . $args['tooltip_title'] . '</strong>:  not using html right now, need to find work around to allow it when we do.
        // https://stackoverflow.com/questions/15734105/jquery-ui-tooltip-does-not-support-html-content
        if ( ! empty( $args['tooltip_desc'] ) ) {
            $tooltip_class = ! empty( $args['tooltip_class'] ) ? $args['tooltip_class'] : '';
            $tooltip = '<span alt="f223" class="fts-help-tip dashicons dashicons-editor-help ' . esc_attr( $tooltip_class ) . '" title="' . esc_attr( $args['tooltip_desc'] ) . '"></span>';
            $html .= $tooltip;
        }

        return $html;
    } // ftsAddSettingTooltip

    /**
     * Header Callback
     *
     * Renders the header.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @return    void
     */
    public function ftsHeaderCallback( $args ) {
        echo apply_filters( 'fts_after_setting_output', '', $args );
    } // ftsHeaderCallback

    /**
     * Checkbox Callback
     *
     * Renders checkboxes.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsCheckboxCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( isset( $args['faux'] ) && $args['faux'] === true ) {
            $name = '';
        } else {
            $name = 'name="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $readonly    = $args['readonly'] === true    ? 'disabled="disabled"' : '';

        $checked = ! empty( $fts_option ) ? checked( 1, $fts_option, false ) : '';
        $html  = '<input type="hidden"' . $name . ' value="-1" />';
        $html .= '<input type="checkbox" ' . $readonly . ' id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"' . $name . ' value="1" ' . $checked . ' class="' . $class . '"/>';
        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> '  . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsCheckboxCallback

    /**
     * Multicheck Callback
     *
     * Renders multiple checkboxes.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsMulticheckCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $html = '';

        if ( ! empty( $args['options'] ) ) {
            foreach( $args['options'] as $key => $option )    {
                if ( isset( $fts_option[ $key ] ) )    {
                    $enabled = $option;
                } else    {
                    $enabled = null;
                }

                $html .= '<input name="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . '][' . $this->ftsSanitizeKey( $key ) . ']" id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . '][' . $this->ftsSanitizeKey( $key ) . ']" class="' . $class . '" type="checkbox" value="' . esc_attr( $option ) . '" ' . checked( $option, $enabled, false ) . '/>&nbsp;';

                $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . '][' . $this->ftsSanitizeKey( $key ) . ']">' . wp_kses_post( $option ) . '</label><br/>';
            }

            $html .= '<p class="description">' . $args['desc'] . '</p>';
        }

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsMulticheckCallback

    /**
     * Radio Callback
     *
     * Renders radio boxes.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsRadioCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        $html = '';

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        foreach ( $args['options'] as $key => $option )    {
            $checked = false;

            if ( $fts_option && $key == $fts_option )    {
                $checked = true;
            } elseif ( isset( $args['std'] ) && $key == $args['std'] && ! $fts_option )    {
                $checked = true;
            }

            $html .= '<input name="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . '][' . $this->ftsSanitizeKey( $key ) . ']" class="' . $class . '" type="radio" value="' . $this->ftsSanitizeKey( $key ) . '" ' . checked( true, $checked, false ) . '/>&nbsp;';

            $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . '][' . $this->ftsSanitizeKey( $key ) . ']">' . esc_html( $option ) . '</label><br/>';
        }

        $html .= '<p class="description">' . apply_filters( 'fts_after_setting_output', wp_kses_post( $args['desc'] ), $args ) . '</p>';

        echo $html;
    } // ftsRadioCallback

    /**
     * Text Callback
     *
     * Renders text fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsTextCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        if ( isset( $args['faux'] ) && true === $args['faux'] ) {
            $args['readonly'] = true;
            $value = isset( $args['std'] ) ? $args['std'] : '';
            $name  = '';
        } else {
            $name = 'name="fts_settings[' . esc_attr( $args['id'] ) . ']"';
        }

        $class       = $this->ftsSanitizeHtmlClass( $args['field_class'] );
        $readonly    = $args['readonly'] === true    ? ' readonly="readonly"' : '';
        $placeholder = isset( $args['placeholder'] ) ? $args['placeholder']   : '';
        $size        = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

        $html = \sprintf(
            '<input type="text" class="%s" id="fts_settings[%s]" %s value="%s" placeholder="%s"%s />',
            $class . ' ' . sanitize_html_class( $size ) . '-text',
            $this->ftsSanitizeKey( $args['id'] ),
            $name,
            esc_attr( stripslashes( $value ) ),
            $placeholder,
            $readonly
        );

        $html .= \sprintf(
            '<label for="fts_settings[%s]"> %s</label>',
            $this->ftsSanitizeKey( $args['id'] ),
            wp_kses_post( $args['desc'] )
        );

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsTextCallback

    /**
     * Number Callback
     *
     * Renders number fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsNumberCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option ) {
            $value = $fts_option;
        } else {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        if ( isset( $args['faux'] ) && true === $args['faux'] ) {
            $args['readonly'] = true;
            $value = isset( $args['std'] ) ? $args['std'] : '';
            $name  = '';
        } else {
            $name = 'name="fts_settings[' . esc_attr( $args['id'] ) . ']"';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $max  = isset( $args['max'] ) ? $args['max'] : 999999;
        $min  = isset( $args['min'] ) ? $args['min'] : 0;
        $step = isset( $args['step'] ) ? $args['step'] : 1;

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"/>';
        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> '  . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsNumberCallback

    /**
     * Textarea Callback
     *
     * Renders textarea fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsTextareaCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );
        $cols  = isset( $args['cols'] ) && ! empty( absint( $args['cols'] ) ) ? absint( $args['cols'] ) : '50';
        $rows  = isset( $args['rows'] ) && ! empty( absint( $args['rows'] ) ) ? absint( $args['rows'] ) : '5';

        $html = \sprintf(
            '<textarea class="%s large-text" cols="%s" rows="%s" id="fts_settings[%s]" name="fts_settings[%s]">%s</textarea>',
            $class,
            $cols,
            $rows,
            $this->ftsSanitizeKey( $args['id'] ),
            esc_attr( $args['id'] ),
            esc_textarea( stripslashes( $value ) )
        );
        $html .= \sprintf(
            '<label for="fts_settings[%s]"> %s</label>',
            $this->ftsSanitizeKey( $args['id'] ),
            wp_kses_post( $args['desc'] )
        );

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsTextareaCallback

    /**
     * Password Callback
     *
     * Renders password fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsPasswordCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="password" class="' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" name="fts_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"/>';
        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsPasswordCallback

    /**
     * Missing Callback
     *
     * If a function is missing for settings callbacks alert the user.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @return    void
     */
    public function ftsMissingCallback($args) {
        printf(
            __( 'The callback function used for the %s setting is missing.', 'feed-them-social' ),
            '<strong>' . esc_html( $args['id'] ) . '</strong>'
        );
    } // ftsMissingCallback

    /**
     * Select Callback
     *
     * Renders select fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public  function ftsSelectCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        if ( isset( $args['placeholder'] ) ) {
            $placeholder = $args['placeholder'];
        } else {
            $placeholder = '';
        }

        if ( ! empty( $args['multiple'] ) ) {
            $multiple   = ' MULTIPLE';
            $name_array = '[]';
        } else {
            $multiple   = '';
            $name_array = '';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        if ( isset( $args['select2'] ) ) {
            $class .= ' ftg-select2';
        }

        $readonly    = $args['readonly'] === true    ? 'disabled="disabled"' : '';

            $html = '<select id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" ' . $readonly . ' name="fts_settings[' . esc_attr( $args['id'] ) . ']' . $name_array . '" class="' . $class . '"' . $multiple . ' data-placeholder="' . esc_html( $placeholder ) . '" />';
            foreach ( $args['options'] as $option => $name ) {
                if ( ! empty( $multiple ) && is_array( $value ) ) {
                    $selected = selected( true, in_array( $option, $value ), false );
                } else    {
                    $selected = selected( $option, $value, false );
                }
                $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
            }

            $html .= '</select>';

        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsSelectCallback

    /**
     * Color select Callback
     *
     * Renders color select fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options Array of all the FTG Options
     * @return    void
     */
    public function ftsColorSelectCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $html = '<select id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" class="' . $class . '" name="fts_settings[' . esc_attr( $args['id'] ) . ']"/>';

        foreach ( $args['options'] as $option => $color ) {
            $selected = selected( $option, $value, false );
            $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( $color['label'] ) . '</option>';
        }

        $html .= '</select>';
        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> '  . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsColorSelectCallback

    /**
     * Color picker Callback
     *
     * Renders color picker fields.
     *
     * @since    1.0.0
     * @param    array    $args    Arguments passed by the setting
     * @return    void
     */
    public function ftsColorCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option ) {
            $value = $fts_option;
        } else {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $default     = isset( $args['std'] )         ? $args['std']         : '';
        $placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
        $class       = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $html = \sprintf(
            '<input type="text" class="%s ftg-color-picker" id="fts_settings[%s]" name="fts_settings[%s]" value="%s" data-default-color="%s" placeholder="%s" />',
            $class,
            $this->ftsSanitizeKey( $args['id'] ),
            esc_attr( $args['id'] ),
            esc_attr( $value ),
            esc_attr( $default ),
            esc_attr( $placeholder )
        );

        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> '  . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsColorCallback

    /**
     * Rich Editor Callback
     *
     * Renders rich editor fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @global    $wp_version        WordPress Version
     */
    public function ftsRichEditorCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;

            if ( empty( $args['allow_blank'] ) && empty( $value ) )    {
                $value = isset( $args['std'] ) ? $args['std'] : '';
            }
        } else    {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $rows = isset( $args['size'] ) ? $args['size'] : 20;

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        ob_start();
        wp_editor(
            stripslashes( $value ),
            'fts_settings_' . esc_attr( $args['id'] ),
            array(
                'textarea_name' => 'fts_settings[' . esc_attr( $args['id'] ) . ']',
                'textarea_rows' => absint( $rows ),
                'editor_class'  => $class
            )
        );
        $html = ob_get_clean();

        $html .= '<br/><label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsRichEditorCallback

    /**
     * Upload Callback
     *
     * Renders upload fields.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @global    $fts_options    Array of all the FTG Options
     * @return    void
     */
    public function ftsUploadCallback( $args ) {
        $fts_option = $this->fts_get_option( $args['id'] );

        if ( $fts_option )    {
            $value = $fts_option;
        } else    {
            $value = isset($args['std']) ? $args['std'] : '';
        }

        $class = $this->ftsSanitizeHtmlClass( $args['field_class'] );

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" "' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']" name="fts_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
        $html .= '<span>&nbsp;<input type="button" class="fts_settings_upload_button button-secondary" value="' . __( 'Upload File', 'feed_them_social' ) . '"/></span>';
        $html .= '<label for="fts_settings[' . $this->ftsSanitizeKey( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsUploadCallback

    /**
     * Descriptive text callback.
     *
     * Renders descriptive text onto the settings field.
     *
     * @since    1.0
     * @param    arr        $args    Arguments passed by the setting
     * @return    void
     */
    public function ftsDescriptiveTextCallback( $args ) {
        $html = wp_kses_post( $args['desc'] );

        echo apply_filters( 'fts_after_setting_output', $html, $args );
    } // ftsDescriptiveTextCallback

    /**
     * Sanitizes a string key for FTG Settings
     *
     * Keys are used as internal identifiers. Alphanumeric characters, dashes, underscores, stops, colons and slashes are allowed
     *
     * @since     1.0.0
     * @param    string        $key    String key
     * @return    string        Sanitized key
     */
    public function ftsSanitizeKey( $key ) {
        $raw_key = $key;
        $key = preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );

        /**
         * Filter a sanitized key string.
         *
         * @since  1.0.0
         * @param  string  $key     Sanitized key.
         * @param  string  $raw_key The key prior to sanitization.
         */
        return apply_filters( 'ftsSanitizeKey', $key, $raw_key );
    } // ftsSanitizeKey

    /**
     * Get timezone options.
     *
     * @since    1.3.4
     * @return    array    Array of timezone options
     */
    public function ftsGetTimezoneSettingOptions()    {

        $timezones = array(
            'Pacific/Midway'                 => __( '(GMT-11:00) Midway Island, Samoa', 'feed_them_social' ),
            'America/Adak'                   => __( '(GMT-10:00) Hawaii-Aleutian', 'feed_them_social' ),
            'Etc/GMT+10'                     => __( '(GMT-10:00) Hawaii', 'feed_them_social' ),
            'Pacific/Marquesas'              => __( '(GMT-09:30) Marquesas Islands', 'feed_them_social' ),
            'Pacific/Gambier'                => __( '(GMT-09:00) Gambier Islands', 'feed_them_social' ),
            'America/Anchorage'              => __( '(GMT-09:00) Alaska', 'feed_them_social' ),
            'America/Ensenada'               => __( '(GMT-08:00) Tijuana, Baja California', 'feed_them_social' ),
            'Etc/GMT+8'                      => __( '(GMT-08:00) Pitcairn Islands', 'feed_them_social' ),
            'America/Los_Angeles'            => __( '(GMT-08:00) Pacific Time (US & Canada)', 'feed_them_social' ),
            'America/Denver'                 => __( '(GMT-07:00) Mountain Time (US & Canada)', 'feed_them_social' ),
            'America/Chihuahua'              => __( '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'feed_them_social' ),
            'America/Dawson_Creek'           => __( '(GMT-07:00) Arizona', 'feed_them_social' ),
            'America/Belize'                 => __( '(GMT-06:00) Saskatchewan', 'feed_them_social' ),
            'America/Cancun'                 => __( '(GMT-06:00) Guadalajara, Mexico City', 'feed_them_social' ),
            'Chile/EasterIsland'             => __( '(GMT-06:00) Easter Island', 'feed_them_social' ),
            'America/Chicago'                => __( '(GMT-06:00) Central Time (US & Canada)', 'feed_them_social' ),
            'America/New_York'               => __( '(GMT-05:00) Eastern Time (US & Canada)', 'feed_them_social' ),
            'America/Havana'                 => __( '(GMT-05:00) Cuba', 'feed_them_social' ),
            'America/Bogota'                 => __( '(GMT-05:00) Bogota, Lima, Quito, Rio Branco', 'feed_them_social' ),
            'America/Caracas'                => __( '(GMT-04:30) Caracas', 'feed_them_social' ),
            'America/Santiago'               => __( '(GMT-04:00) Santiago', 'feed_them_social' ),
            'America/La_Paz'                 => __( '(GMT-04:00) La Paz', 'feed_them_social' ),
            'Atlantic/Stanley'               => __( '(GMT-04:00) Falkland Islands', 'feed_them_social' ),
            'America/Goose_Bay'              => __( '(GMT-04:00) Atlantic Time (Goose Bay)', 'feed_them_social' ),
            'America/Glace_Bay'              => __( '(GMT-04:00) Atlantic Time (Canada)', 'feed_them_social' ),
            'America/St_Johns'               => __( '(GMT-03:30) Newfoundland', 'feed_them_social' ),
            'America/Araguaina'              => __( '(GMT-03:00) UTC-3', 'feed_them_social' ),
            'America/Montevideo'             => __( '(GMT-03:00) Montevideo', 'feed_them_social' ),
            'America/Miquelon'               => __( '(GMT-03:00) Miquelon, St. Pierre', 'feed_them_social' ),
            'America/Godthab'                => __( '(GMT-03:00) Greenland', 'feed_them_social' ),
            'America/Argentina/Buenos_Aires' => __( '(GMT-03:00) Buenos Aires', 'feed_them_social' ),
            'America/Sao_Paulo'              => __( '(GMT-03:00) Brasilia', 'feed_them_social' ),
            'AAmerica/Noronha'               => __( '(GMT-02:00) Mid-Atlantic', 'feed_them_social' ),
            'Atlantic/Cape_Verde'            => __( '(GMT-01:00) Cape Verde Is.', 'feed_them_social' ),
            'Atlantic/Azores'                => __( '(GMT-01:00) Azores', 'feed_them_social' ),
            'Europe/Belfast'                 => __( '(GMT) Greenwich Mean Time : Belfast', 'feed_them_social' ),
            'Europe/Dublin'                  => __( '(GMT) Greenwich Mean Time : Dublin', 'feed_them_social' ),
            'Europe/Lisbon'                  => __( '(GMT) Greenwich Mean Time : Lisbon', 'feed_them_social' ),
            'Europe/London'                  => __( '(GMT) Greenwich Mean Time : London', 'feed_them_social' ),
            'Africa/Abidjan'                 => __( '(GMT) Monrovia, Reykjavik', 'feed_them_social' ),
            'Europe/Amsterdam'               => __( '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'feed_them_social' ),
            'Europe/Belgrade'                => __( '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'feed_them_social' ),
            'Africa/Algiers'                 => __( '(GMT+01:00) West Central Africa', 'feed_them_social' ),
            'Africa/Windhoek'                => __( '(GMT+01:00) Windhoek', 'feed_them_social' ),
            'Asia/Beirut'                    => __( '(GMT+02:00) Beirut', 'feed_them_social' ),
            'Africa/Cairo'                   => __( '(GMT+02:00) Cairo', 'feed_them_social' ),
            'Asia/Gaza'                      => __( '(GMT+02:00) Gaza', 'feed_them_social' ),
            'Africa/Blantyre'                => __( '(GMT+02:00) Harare, Pretoria', 'feed_them_social' ),
            'Asia/Jerusalem'                 => __( '(GMT+02:00) Jerusalem', 'feed_them_social' ),
            'Europe/Minsk'                   => __( '(GMT+02:00) Minsk', 'feed_them_social' ),
            'Asia/Damascus'                  => __( '(GMT+02:00) Syria', 'feed_them_social' ),
            'Europe/Moscow'                  => __( '(GMT+03:00) Moscow, St. Petersburg, Volgograd', 'feed_them_social' ),
            'Africa/Addis_Ababa'             => __( '(GMT+03:00) Nairobi', 'feed_them_social' ),
            'Asia/Tehran'                    => __( '(GMT+03:30) Tehran', 'feed_them_social' ),
            'Asia/Dubai'                     => __( '(GMT+04:00) Abu Dhabi, Muscat', 'feed_them_social' ),
            'Asia/Yerevan'                   => __( '(GMT+04:00) Yerevan', 'feed_them_social' ),
            'Asia/Kabul'                     => __( '(GMT+04:30) Kabul', 'feed_them_social' ),
            'Asia/Yekaterinburg'             => __( '(GMT+05:00) Ekaterinburg', 'feed_them_social' ),
            'Asia/Tashkent'                  => __( '(GMT+05:00) Tashkent', 'feed_them_social' ),
            'Asia/Kolkata'                   => __( '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'feed_them_social' ),
            'Asia/Katmandu'                  => __( '(GMT+05:45) Kathmandu', 'feed_them_social' ),
            'Asia/Dhaka'                     => __( '(GMT+06:00) Astana, Dhaka', 'feed_them_social' ),
            'Asia/Novosibirsk'               => __( '(GMT+06:00) Novosibirsk', 'feed_them_social' ),
            'Asia/Rangoon'                   => __( '(GMT+06:30) Yangon (Rangoon)', 'feed_them_social' ),
            'Asia/Bangkok'                   => __( '(GMT+07:00) Bangkok, Hanoi, Jakarta', 'feed_them_social' ),
            'Asia/Krasnoyarsk'               => __( '(GMT+07:00) Krasnoyarsk', 'feed_them_social' ),
            'Asia/Hong_Kong'                 => __( '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 'feed_them_social' ),
            'Asia/Irkutsk'                   => __( '(GMT+08:00) Irkutsk, Ulaan Bataar', 'feed_them_social' ),
            'Australia/Perth'                => __( '(GMT+08:00) Perth', 'feed_them_social' ),
            'Australia/Eucla'                => __( '(GMT+08:45) Eucla', 'feed_them_social' ),
            'Asia/Tokyo'                     => __( '(GMT+09:00) Osaka, Sapporo, Tokyo', 'feed_them_social' ),
            'Asia/Seoul'                     => __( '(GMT+09:00) Seoul', 'feed_them_social' ),
            'Asia/Yakutsk'                   => __( '(GMT+09:00) Yakutsk', 'feed_them_social' ),
            'Australia/Darwin'               => __( '(GMT+09:30) Darwin', 'feed_them_social' ),
            'Australia/Brisbane'             => __( '(GMT+10:00) Brisbane', 'feed_them_social' ),
            'Australia/Hobart'               => __( '(GMT+10:00) Sydney', 'feed_them_social' ),
            'Asia/Vladivostok'               => __( '(GMT+10:00) Vladivostok', 'feed_them_social' ),
            'Australia/Lord_Howe'            => __( '(GMT+10:30) Lord Howe Island', 'feed_them_social' ),
            'Etc/GMT-11'                     => __( '(GMT+11:00) Solomon Is., New Caledonia', 'feed_them_social' ),
            'Asia/Magadan'                   => __( '(GMT+11:00) Magadan', 'feed_them_social' ),
            'Pacific/Norfolk'                => __( '(GMT+11:30) Norfolk Island', 'feed_them_social' ),
            'Asia/Anadyr'                    => __( '(GMT+12:00) Anadyr, Kamchatka', 'feed_them_social' ),
            'Pacific/Auckland'               => __( '(GMT+12:00) Auckland, Wellington', 'feed_them_social' ),
            'Etc/GMT-12'                     => __( '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'feed_them_social' ),
            'Pacific/Chatham'                => __( '(GMT+12:45) Chatham Islands', 'feed_them_social' ),
            'Pacific/Tongatapu'              => __( '(GMT+13:00) Nuku\'alofa', 'feed_them_social' ),
            'Pacific/Kiritimati'             => __( '(GMT+14:00) Kiritimati', 'feed_them_social' )
        );

        return $timezones;
    } // ftg_get_timezone_setting_options

    /**
     * Get date format options.
     *
     * @since    1.3.4
     * @return    array    Array of date format options
     */
    public function ftsGetDateFormatSettingOptions()    {

        // Set your custom timezone string
        $timezone_set = $this->fts_get_option( 'timezone', 'America/Los_Angeles' );

        // Create a new DateTimeZone object
        $timezone = \in_array( $timezone_set, timezone_identifiers_list(), true ) ? new \DateTimeZone($timezone_set) : null;

        $formats = array(
            'one-day-ago'          => __( '1 day ago', 'feed_them_social' ),
            'fts-custom-date'      => __( 'Custom Date and Time', 'feed_them_social' ),
            'l, F jS, Y \a\t g:ia' => wp_date( 'l, F jS, Y \a\t g:ia', null, $timezone ),
            'F j, Y \a\t g:ia'     => wp_date( 'F j, Y \a\t g:ia', null, $timezone ),
            'F j, Y g:ia'          => wp_date( 'F j, Y g:ia', null, $timezone ),
            'F, Y \a\t g:ia'       => wp_date( 'F, Y \a\t g:ia', null, $timezone ),
            'M j, Y @ g:ia'        => wp_date( 'M j, Y @ g:ia', null, $timezone ),
            'M j, Y @ G:i'         => wp_date( 'M j, Y @ G:i', null, $timezone ),
            'm/d/Y \a\t g:ia'      => wp_date( 'm/d/Y \a\t g:ia', null, $timezone ),
            'm/d/Y @ G:i'          => wp_date( 'm/d/Y @ G:i', null, $timezone ),
            'd/m/Y \a\t g:ia'      => wp_date( 'd/m/Y \a\t g:ia', null, $timezone ),
            'd/m/Y @ G:i'          => wp_date( 'd/m/Y @ G:i', null, $timezone ),
            'Y/m/d \a\t g:ia'      => wp_date( 'Y/m/d \a\t g:ia', null, $timezone ),
            'Y/m/d @ G:i'          => wp_date( 'Y/m/d @ G:i', null, $timezone ),
        );

        return $formats;
    } // ftg_get_date_format_setting_options
}
