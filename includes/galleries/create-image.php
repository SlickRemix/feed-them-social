<?php
/**
 *  Watermarking Common Class
 *
 * This class is for water marking common functions
 *
 * @class    FTGallery_Create_Image
 * @version  1.0.0
 * @package  FeedThemSocial/Watermarking
 * @category Class
 * @author  Tim Carr
 */

namespace feedthemsocial;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Common class.
 */
class FTGallery_Create_Image {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'fts_defaults', array( $this, 'defaults' ), 10, 2 );

	}

	/**
	 * Resize Image
	 *
	 * API method for cropping images.
	 *
	 * @since 1.0.0
	 *
	 * @global object $wpdb The $wpdb database object.
	 *
	 * @param string $url      The URL of the image to resize.
	 * @param int    $width    The width for cropping the image.
	 * @param int    $height   The height for cropping the image.
	 * @param bool   $crop     Whether or not to crop the image (default yes).
	 * @param string $align    The crop position alignment.
	 * @param int    $quality  The quality of the image.
	 * @param bool   $retina   Whether or not to make a retina copy of image.
	 * @param array  $data     Array of gallery data (optional).
	 * @param bool   $force_overwrite      Forces an overwrite even if the thumbnail already exists (useful for applying watermarks).
	 * @return WP_Error|string Return WP_Error on error, URL of resized image on success.
	 */
	public function resize_image( $url, $width = null, $height = null, $crop = true, $align = 'c', $quality = 100, $retina = false, $data = array(), $force_overwrite = false ) {

		global $wpdb;

		// Get common vars.
		$args = array( $url, $width, $height, $crop, $align, $quality, $retina, $data );

		// Filter args.
		$args = apply_filters( 'fts_resize_image_args', $args );

		// Don't resize images that don't belong to this site's URL
		// Strip ?lang=fr from blog's URL - WPML adds this on
		// and means our next statement fails.
		if ( is_multisite() ) {
			$blog_id = get_current_blog_id();
			// doesn't use network_site_url because this will be incorrect for remapped domains.
			if ( is_main_site( $blog_id ) ) {
				$site_url = preg_replace( '/\?.*/', '', network_site_url() );
			} else {
				$site_url = preg_replace( '/\?.*/', '', site_url() );
			}
		} else {
			$site_url = preg_replace( '/\?.*/', '', get_bloginfo( 'url' ) );
		}

		// WPML check - if there is a /fr or any domain in the url, then remove that from the $site_url.
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( false !== strpos( $site_url, '/' . ICL_LANGUAGE_CODE ) ) {
				$site_url = str_replace( '/' . ICL_LANGUAGE_CODE, '', $site_url );
			}
		}

		if ( function_exists( 'qtrans_getLanguage' ) ) {

			$lang = qtrans_getLanguage();

			if ( ! empty( $lang ) ) {
				if ( false !== strpos( $site_url, '/' . $lang ) ) {
					$site_url = str_replace( '/' . $lang, '', $site_url );
				}
			}
		}

		if ( strpos( $url, $site_url ) === false ) {
			return $url;
		}

		// Get image info.
		$common = $this->get_image_info( $args );

		// Unpack variables if an array, otherwise return WP_Error.
		if ( is_wp_error( $common ) ) {
			return $common;
		} else {
			extract( $common );
		}

		// If the destination width/height values are the same as the original, don't do anything.
		if ( ! $force_overwrite && $orig_width === $dest_width && $orig_height === $dest_height ) {
			return $url;
		}

		// If the file doesn't exist yet, we need to create it.
		if ( ! file_exists( $dest_file_name ) || ( file_exists( $dest_file_name ) && $force_overwrite ) ) {
			// We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
			$get_attachment = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid='%s'", $url ) );

			// Load the WordPress image editor.
			$editor = wp_get_image_editor( $file_path );

			// If an editor cannot be found, the user needs to have GD or Imagick installed.
			if ( is_wp_error( $editor ) ) {
				return new WP_Error( 'ft-gallery-error-no-editor', __( 'No image editor could be selected. Please verify with your webhost that you have either the GD or Imagick image library compiled with your PHP install on your server.', 'ft-gallery' ) );
			}

			// Set the image editor quality.
			$editor->set_quality( $quality );

			// If cropping, process cropping.
			if ( $crop ) {
				$src_x = 0;
				$src_w = $orig_width;
				$src_h = $orig_height;

				$cmp_x = $orig_width / $dest_width;
				$cmp_y = $orig_height / $dest_height;

				// Calculate x or y coordinate and width or height of source.
				if ( $cmp_x > $cmp_y ) {
					$src_w = round( $orig_width / $cmp_x * $cmp_y );
					$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
				} elseif ( $cmp_y > $cmp_x ) {
					$src_h = round( $orig_height / $cmp_y * $cmp_x );
					$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
				}

				// Positional cropping.
				if ( $align && 'c' !== $align ) {
					if ( false !== strpos( $align, 't' ) || false !== strpos( $align, 'tr' ) || false !== strpos( $align, 'tl' ) ) {
						$src_y = 0;
					}

					if ( false !== strpos( $align, 'b' ) || false !== strpos( $align, 'br' ) || false !== strpos( $align, 'bl' ) ) {
						$src_y = $orig_height - $src_h;
					}

					if ( false !== strpos( $align, 'l' ) ) {
						$src_x = 0;
					}

					if ( false !== strpos( $align, 'r' ) ) {
						$src_x = $orig_width - $src_w;
					}
				}

				// Crop the image.
				$editor->crop( $src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height );
			} else {
				// Just resize the image.
				$editor->resize( $dest_width, $dest_height );
			}

			// Save the image.
			$saved = $editor->save( $dest_file_name );

			// Print possible out of memory errors.
			if ( is_wp_error( $saved ) ) {
				@unlink( $dest_file_name );
				return $saved;
			}

			// Add the resized dimensions and alignment to original image metadata, so the images
			// can be deleted when the original image is delete from the Media Library.
			if ( $get_attachment ) {
				$metadata = wp_get_attachment_metadata( $get_attachment[0]->ID );

				if ( isset( $metadata['image_meta'] ) ) {
					$md = $saved['width'] . 'x' . $saved['height'];

					if ( $crop ) {
						$md .= $align ? "_${align}" : '_c';
					}

					$metadata['image_meta']['resized_images'][] = $md;
					wp_update_attachment_metadata( $get_attachment[0]->ID, $metadata );
				}
			}

			// Set the resized image URL.
			$resized_url = str_replace( basename( $url ), basename( $saved['path'] ), $url );
		} else {
			// Set the resized image URL.
			$resized_url = str_replace( basename( $url ), basename( $dest_file_name ), $url );
		}

		// Return the resized image URL.
		return apply_filters( 'fts_resize_image_resized_url', $resized_url );

	}

	/**
	 * Get Image Info
	 *
	 * Helper method to return common information about an image.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args List of resizing args to expand for gathering info.
	 * @return WP_Error|string Return WP_Error on error, array of data on success.
	 */
	public function get_image_info( $args ) {

		// Unpack arguments.
		list( $url, $width, $height, $crop, $align, $quality, $retina, $data ) = $args;

		// Return an error if no URL is present.
		if ( empty( $url ) ) {
			return new WP_Error( 'ft-gallery-error-no-url', __( 'No image URL specified for cropping.', 'ft-gallery' ) );
		}

		// Get the image file path.
		$urlinfo       = wp_parse_url( $url );
		$wp_upload_dir = wp_upload_dir();

		// Interpret the file path of the image.
		if ( preg_match( '/\/[0-9]{4}\/[0-9]{2}\/.+$/', $urlinfo['path'], $matches ) ) {
			$file_path = $wp_upload_dir['basedir'] . $matches[0];
		} else {
			$pathinfo    = wp_parse_url( $url );
			$uploads_dir = is_multisite() ? '/files/' : '/wp-content/';
			$my_request  = stripslashes_deep( $_SERVER );
			$file_path   = ABSPATH . str_replace( dirname( $my_request['SCRIPT_NAME'] ) . '/', '', strstr( $pathinfo['path'], $uploads_dir ) );
			$file_path   = preg_replace( '/(\/\/)/', '/', $file_path );
		}

		// Attempt to stream and import the image if it does not exist based on URL provided.
		if ( ! file_exists( $file_path ) ) {
			return new WP_Error( 'ft-gallery-error-no-file', __( 'No file could be found for the image URL specified.', 'ft-gallery' ) );
		}

		// Get original image size.
		$size = @getimagesize( $file_path );

		// If no size data obtained, return an error.
		if ( ! $size ) {
			return new WP_Error( 'ft-gallery-error-no-size', __( 'The dimensions of the original image could not be retrieved for cropping.', 'ft-gallery' ) );
		}

		// Set original width and height.
		list( $orig_width, $orig_height, $orig_type ) = $size;

		// Generate width or height if not provided.
		if ( $width && ! $height ) {
			$height = floor( $orig_height * ( $width / $orig_width ) );
		} elseif ( $height && ! $width ) {
			$width = floor( $orig_width * ( $height / $orig_height ) );
		} elseif ( ! $width && ! $height ) {
			return new WP_Error( 'ft-gallery-error-no-size', __( 'The dimensions of the original image could not be retrieved for cropping.', 'ft-gallery' ) );
		}

		// Allow for different retina image sizes.
		$retina = $retina ? ( true == $retina ? 2 : $retina ) : 1;

		// Destination width and height variables.
		$dest_width  = $width * $retina;
		$dest_height = $height * $retina;

		// Some additional info about the image.
		$info = pathinfo( $file_path );
		$dir  = $info['dirname'];
		$ext  = $info['extension'];
		$name = wp_basename( $file_path, ".$ext" );

		// Suffix applied to filename.
		$suffix = "${dest_width}x${dest_height}";

		// Set alignment information on the file.
		if ( $crop ) {
			$suffix .= ( $align ) ? "_${align}" : '_c';
		}

		// Get the destination file name.
		$dest_file_name = "${dir}/${name}-${suffix}.${ext}";

		// Return the info.
		$info = array(
			'dir'            => $dir,
			'name'           => $name,
			'ext'            => $ext,
			'suffix'         => $suffix,
			'orig_width'     => $orig_width,
			'orig_height'    => $orig_height,
			'orig_type'      => $orig_type,
			'dest_width'     => $dest_width,
			'dest_height'    => $dest_height,
			'file_path'      => $file_path,
			'dest_file_name' => $dest_file_name,
		);

		return $info;

	}
	/**
	 * Get Instance Watermarking Common
	 *
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The FTGallery_Create_Image object.
	 */
	public static function get_instance_ftgallery_watermarking_common() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof FTGallery_Create_Image ) ) {
			self::$instance = new FTGallery_Create_Image();
		}

		return self::$instance;

	}
}
// Load the common class.
$ftgallery_watermarking_common = FTGallery_Create_Image::get_instance_ftgallery_watermarking_common();
