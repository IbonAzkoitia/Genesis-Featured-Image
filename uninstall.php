<?php
/** 
 * This file runs when plugin is uninstalled
 * 
 * @package    Genesis Featured Image
 * @subpackage uninstall
 * @author     kreatidos
 * @since 	   1.0
 */
 
 // If uninstall not called from WordPress exit
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit ();

delete_option('genesis-featured-image');