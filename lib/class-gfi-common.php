<?php

/**
 * Common functions for plugins
 *
 * @package GenesisFeaturedImage
 * @since 1.0.0
 */
class Genesis_Featured_Image_Common {

	/**
	 * current plugin version
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public static $version = '1.0.0';


	

	/**
	 * Get the ID of each image dynamically.
	 *
	 * @since 1.2.0
	 *
	 * @author Philip Newcomer
	 * @link   http://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
	 */
	public static function get_image_id( $attachment_url = '' ) {

		$attachment_id = false;

		// as of 2.2.0, if a (new) image id is passed to the function, return it as is.
		if ( is_numeric( $attachment_url ) ) {
			return $attachment_url;
		}

		// If there is no url, return.
		if ( '' == $attachment_url ) {
			return;
		}

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( trailingslashit( $upload_dir_paths['baseurl'] ), '', $attachment_url );

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$url_stripped   = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id  = self::fetch_image_id_query( $url_stripped, $attachment_url );

		}

		return $attachment_id;
	}

}