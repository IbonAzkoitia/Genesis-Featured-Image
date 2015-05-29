<?php
/**
 * Genesis Featured Image
 *
 * @package   GenesisFeaturedImage
 * @author    Ibon Azkoitia <ibon@kreatidos.com>
 * @link      https://github.com/IbonAzkoitia
 * @copyright 2015 Ibon Azkoitia
 * @license   GPL-2.0+
 */

/**
 * Main plugin class.
 *
 * @package GenesisFeaturedImage
 */
class Genesis_Featured_Image {

	function __construct( $common, $output, $settings ) {
		$this->common 	= $common;
		$this->output 	= $output;
		$this->settings = $settings;
	}

	public function gfi_run() {
		if ( 'genesis' !== basename( get_template_directory() ) ) {
			add_action( 'admin_init', array( $this, 'gfi_deactivate' ) );
			add_action( 'admin_notices', array( $this, 'gfi_error_message' ) );
			return;
		}

		if ( version_compare( $wp_version, '3.8', '>' ) ) {
			add_action ( 'admin_init', array( $this, 'gfi_not_wp_version' ) );
			return;
		}

		add_action( 'admin_notices',			array( $this, 'gfi_activate' ) );
		add_action( 'admin_init',				array( $this, 'gfi_check_settings' ) );
		add_action( 'plugins_loaded', 			array( $this, 'gfi_load_textdomain' ) );
		add_action( 'admin_menu', 				array( $this->settings, 'gfi_do_submenu_page' ) );
		add_action( 'get_header',				array( $this->output, 'gfi_manage_output' ) );
		add_action( 'admin_enqueue_scripts',	array( $this, 'gfi_enqueue_scripts' ) );
		add_action( 'plugins_loaded', 			array( $this, 'gfi_action_links' ) );

	}

	/**
	 * deactivates the plugin if Genesis isn't running
	 *
	 * @since  1.0.0
	 * 
	 */
	public function gfi_deactivate() {
		if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
			deactivate_plugins( plugin_basename( dirname( __DIR__ ) ) . '/genesis-featured-image.php' ); // __DIR__ is a magic constant introduced in PHP 5.3
		}
		else {
			deactivate_plugins( plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/genesis-featured-image.php' );
		}
	}

	/**
	 * error message if we're not using the Genesis Framework
	 *
	 * @since  1.0.0
	 * 
	 */
	public function gfi_error_message() {

		$error = sprintf( __( 'Sorry, Genesis Featured Image works only with the Genesis Framework. It has been deactivated.', 'genesis-featured-image' ), PHP_VERSION 
		);

		if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
			$error = $error . sprintf(
				__( 'But since we\'re talking anyway, did you know that your server is running PHP version %1$s, which is outdated? You should ask your host to update that for you.', 'genesis-featured-image' ),
				PHP_VERSION
			);
		}

		echo '<div class="error"><p>' . esc_attr( $error ) . '</p></div>';

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * error message if the wp_version it's not the minimum needed
	 *
	 * @since 1.0.0
	 */
	function gfi_not_wp_version() {

		wp_die( __( 'This plugin requires WordPress version 3.8 of higher.', 'genesis-featured-image' ) );
	}

	/**
	 * shows an admin message to inform user about the Genesis Featured Imate Settings page
	 * @return admin message
	 *
	 * @since  1.0.0
	 */
	public function gfi_activate() {
		if($_GET['activate'] == true) {

			echo '<div class="updated"><p>';
			echo __( 'Remember to set the Featured Image:', 'genesis-featured-image' );
			echo ' <a href="'. admin_url( "admin.php?page=genesis-featured-image" ) . '" title="Genesis Featured Image Settings Page">';
			echo __( 'Settings Page', 'genesis-featured-image' );
			echo '</a></p></div>';
		}
	}

	/**
	 * check existing settings array to see if a setting is in the array
	 * @return updated setting updates to default (0)
	 * @since  1.0.0
	 */
	public function gfi_check_settings() {

		$displaysetting = get_option( 'genesis-featured-image' );

		//* return early if the option doesn't exist yet
		if ( empty( $displaysetting ) ) {
			return;
		}
	
	}

	/**
	 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $new     New settings. Can be a string, or an array.
	 * @param string       $setting Optional. Settings field name. Default is genesis-featured-image.
	 */
	protected function update_settings( $new = '', $setting = 'genesis-featured-image' ) {
		return update_option( $setting, wp_parse_args( $new, get_option( $setting ) ) );
	}

	/**
	 * set up text domain for translations
	 *
	 * @since 1.0.0
	 * 
	 */
	public function gfi_load_textdomain() {
		$domain = 'genesis-featured-image';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, $domain . '/languages' );
	}

	/**
	 * enqueue admin scripts
	 * @return scripts to use image uploader
	 *
	 * @since  1.0.0
	 * 
	 */
	public function gfi_enqueue_scripts() {

		$version = Genesis_Featured_Image_Common::$version;

		wp_register_script( 'genesis-featured-image-upload', plugins_url( '/lib/js/settings-upload.js', dirname( __FILE__ ) ), array( 'jquery'), $version );

		wp_enqueue_media();
		wp_enqueue_script( 'genesis-featured-image-upload' );
		wp_localize_script( 'genesis-featured-image-upload', 'objectL10n', array(
				'text' => __( 'Select Image', 'genesis-featured-image' ),
			) );
	}

	/**
	 * show settings link in plugins page
	 * @return link to the plugin settings page
	 *
	 * @since  1.0.0
	 */
	function gfi_action_links() {

		add_filter( 'plugin_action_links', 'gfi_do_action_links' );

		function gfi_do_action_links ( $links ) {
			$gfiactionlinks = array( '<a href="admin.php?page=genesis-featured-image" title="Genesis Featured Image Settings Page">' . __( 'Settings', 'genesis-featured-image' ) . '</a>',
			);
			return array_merge( $links, $gfiactionlinks );
		}
 
	}
 

}