<?php
/**
 * Genesis Featured Image / add a featured image to your site
 *
 * @package   GenesisFeaturedImage
 * @author    Ibon Azkoitia <ibon@kreatidos.com>
 * @link      https://github.com/IbonAzkoitia
 * @copyright 2015 Ibon Azkoitia
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: 	Genesis Featured Image
 * Plugin URI: 		https://github.com/IbonAzkoitia/Genesis-Featured-Image
 * Description: 	Allows you to add a Featured Image after header in Genesis Framework
 * Version: 		1.0.1
 * Author: 			Ibon Azkoitia
 * Author URI: 		http://github.com/IbonAzkoitia
 * License:			GPLv2
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
*/

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

function genesis_featured_image_require() {
	$files = array(
		'class-gfi',
		'class-gfi-common',
		'class-gfi-output',
		'class-gfi-settings',
	);

	foreach( $files as $file ) {
		require plugin_dir_path( __FILE__ ) . 'lib/' . $file . '.php';
	}
}
genesis_featured_image_require();

// Instantiate dependent classes
$genesisfeaturedimage_common		= new Genesis_Featured_Image_Common();
$genesisfeaturedimage_output		= new Genesis_Featured_Image_Output();
$genesisfeaturedimage_settings		= new Genesis_Featured_Image_Settings();

$genesisfeaturedimage = new Genesis_Featured_Image(
	$genesisfeaturedimage_common,
	$genesisfeaturedimage_output,
	$genesisfeaturedimage_settings
);

$genesisfeaturedimage->gfi_run();