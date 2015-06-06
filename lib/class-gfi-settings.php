<?php
/**
 * Creates the plugin admin page
 *
 * @package   GenesisFeaturedImage
 * @author    Ibon Azkoitia <ibon@kreatidos.com>
 * @license   GPL-2.0+
 * @link      http://www.kreatidos.com
 * @copyright 2015 Ibon Azkoitia
 * @since 1.0.0
 */

class Genesis_Featured_Image_Settings {

	/**
	 * variable set for featured image option
	 * @var  option
	 */
	protected $displaysetting;

	/**
	 * add a submenu page under Genesis
	 * @return  submenu Featured Image settings page
	 *
	 * @since 1.0.0
	 */
	public function gfi_do_submenu_page() {
		$my_admin_page = add_submenu_page( 
			'genesis', 
			__( 'Genesis Featured Image Settings', 'genesis-featured-image' ), 
			__( 'Featured Image', 'genesis-featured-image' ), 
			'manage_options',
			'genesis-featured-image',
			array( $this, 'gfi_do_settings_form' )
		);
	
		add_action( 'admin_init', array( $this, 'gfi_register_settings' ) );

		add_action('load-'.$my_admin_page, array( $this, 'gfi_help') );
		add_action('load-'.$my_admin_page, array( $this, 'gfi_thankyou') );

	}

	/**
 	 * Create General settings form
 	 * @return form Genesis Featured Image settings
 	 *
 	 * @since 1.0.0
 	 */
	function gfi_do_settings_form() {
		$page_title = get_admin_page_title();

		echo '<div class="wrap">';
			echo '<h2>' . esc_attr( $page_title ) . '</h2>';
			echo '<form action="options.php" method="post">';
				settings_fields( 'genesis-featured-image' );
				do_settings_sections( 'genesis-featured-image' );
				wp_nonce_field( 'genesis-featured-image_save-settings', 'genesis-featured-image_nonce', false );
				submit_button();
				settings_errors();
			echo '</form>';
		echo '</div>';
	}

	/**
	 * settings for options screen
	 * @return settings for featured image options
	 *
	 * @since 1.0.0
	 */
	public function gfi_register_settings() {

		register_setting( 'genesis-featured-image', 'genesis-featured-image', array( $this, 'gfi_do_validation_things' ) );

		$defaults = array(
			'featured_image' => '',
		);

		$this->displaysetting = get_option( 'genesis-featured-image', $defaults );

		$page 		= 'genesis-featured-image';
		$section 	= 'genesis_featured_image_section';

		add_settings_section(
			$section,
			__( 'Options', 'genesis-featured-image' ),
			array( $this, 'gfi_section_description' ),
			'genesis-featured-image'
		);

		add_settings_field( 
			'genesis-featured-image[featured_image]',
			'<label for="genesis-featured-image[featured_image]">' . __( 'Featured Image', 'genesis-featured-image' ) . '</label>',
			array( $this, 'set_featured_image' ),
			$page, 
			$section
		);
	}

	/**
	 * Section Description
	 * @return  section description
	 *
	 * @since  1.0.0
	 */
	public function gfi_section_description() {
		printf( '<p>' . __( 'You can add here the Featured Image you want to use', 'genesis-featured-image' ) . '</p>' );
	}

	/**
	 * Featured Image uploader
	 * @return  image
	 *
	 * @since  1.0.0
	 */
	public function set_featured_image() {

		$id = '';
		
		if ( ! empty( $this->displaysetting['featured_image'] ) ) {

			$id = $this->displaysetting['featured_image'];
			$preview = wp_get_attachment_image_src( absint( $id ), 'medium' );
			echo '<div id="upload_logo_preview">';
			printf( '<img src="%s" />', esc_url( $preview[0] ) );
			echo '</div>';

		}

		echo '<input type="hidden" class="upload_image_id" name="genesis-featured-image[featured_image]" id="genesis-featured-image[featured_image]" value="' . absint( $id ) . '">';

		printf( '<input type="button" class="upload_default_image button-secondary" value="%s" />',
			__( 'Select Image', 'genesis-featured-image' )
		);

		if ( ! empty( $this->displaysetting['featured_image'] ) ) {
			echo ' ';
			printf( '<input type="button" class="delete_image button-secondary" value="%s" />',
				__( 'Delete Image', 'genesis-featured-image' )
			);
		}
	}

	/**
	 * validate all inputs
	 * @param  string $new_value various settings
	 * @return string 			 url
	 *
	 * @since  1.0.0
	 */
	public function gfi_do_validation_things( $new_value ) {

		if ( empty( $_POST['genesis-featured-image_nonce'] ) ) {
			wp_die( __( 'Something unexpected happened. Please try again.', 'genesis-featured-image' ) );
		}

		check_admin_referer( 'genesis-featured-image_save-settings', 'genesis-featured-image_nonce' );

		$new_value['featured_image']	= $this->validate_image( $new_value['featured_image'] );

		return $new_value;
	}

	/**
	 * Returns previous value for image if not correct file type/size
	 * @param  string $new_value New value
	 * @return string            New or previous value, depending on allowed image size.
	 * @since  1.2.2
	 */
	protected function validate_image( $new_value ) {

		// if the image was selected using the old URL method
		if ( ! is_numeric( $new_value ) ) {
			$new_value = Genesis_Featured_Image_Common::get_image_id( $new_value );
		}
		$new_value = absint( $new_value );
		$source    = wp_get_attachment_image_src( $new_value, 'full' );
		$valid     = $this->is_valid_img_ext( $source[0] );
		$reset     = __( ' The Default Featured Image has been reset to the last valid setting.', 'genesis-featured-image' );

		// ok for field to be empty
		if ( $new_value ) {

			if ( ! $valid ) {
				$message   = __( 'Sorry, that is an invalid file type.', 'genesis-featured-image' );
				$new_value = $this->displaysetting['featured_image'];

				add_settings_error(
					$this->displaysetting['featured_image'],
					esc_attr( 'invalid' ),
					esc_attr( $message . $reset ),
					'error'
				);
			}
		}

		return $new_value;
	}

	/**
	 * returns file extension
	 * @since  1.0.0
	 */
	protected function get_file_ext( $file ) {
		$parsed = @parse_url( $file, PHP_URL_PATH );
		return $parsed ? strtolower( pathinfo( $parsed, PATHINFO_EXTENSION ) ) : false;
	}

	/**
	 * check if file type is image
	 * @return file       check file extension against list
	 * @since  1.0.0
	 */
	protected function is_valid_img_ext( $file ) {
		$file_ext = $this->get_file_ext( $file );

		$this->valid = empty( $this->valid )
			? (array) apply_filters( 'genesis-featured-image_valid_img_types', array( 'jpg', 'jpeg', 'png', 'gif' ) )
			: $this->valid;

		return ( $file_ext && in_array( $file_ext, $this->valid ) );
	}

	/**
	 * Returns a 1 or 0, for all truthy / falsy values.
	 *
	 * Uses double casting. First, we cast to bool, then to integer.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $new_value Should ideally be a 1 or 0 integer passed in
	 * @return integer 1 or 0.
	 */
	protected function one_zero( $new_value ) {
		return (int) (bool) $new_value;
	}

	/**
	 * help tab for plugin settings page
	 * @return help tab with value information for plugin
	 *
	 * @since 1.0.0
	 */
	public function gfi_help() {
		$screen = get_current_screen();

		$featuredimage_help =
			'<h3>' . __( 'Select Image', 'genesis-featured-image' ) . '</h3>' .
			'<p>' . __( 'You may set a large image to be used on full wide after header.', 'genesis-featured-image' ) . '</p>' .
			'<p>' . __( 'Supported file types are: jpg, jpeg, png, and gif.', 'genesis-featured-image' ) . '</p>';

		$screen->add_help_tab( array(
			'id'		=> 'gfi_select_image-help',
			'title'		=> __( 'Select Image', 'genesis-featured-image' ),
			'content'	=> $featuredimage_help,
		) );
	}

	/**
	 * Add rating links to the admin dashboard
	 *
	 * @since	    1.8.5
	 * @global		string $typenow
	 * @param       string $footer_text The existing footer text
	 * @return      string
	 */
	function gfi_thankyou() {

		add_action( 'admin_footer_text', 'gfi_rate_us' );

		function gfi_rate_us( $footer_text ) {

			$gfi_rate_text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">Genesis Featured Image</a>! Please <a href="%2$s" target="_blank">rate us</a> on <a href="%2$s" target="_blank">WordPress.org</a>', 'genesis-featured-image' ),
				'https://wordpress.org/plugins/genesis-featured-image/',
				'https://wordpress.org/support/view/plugin-reviews/genesis-featured-image?filter=5#postform'
			);

			return str_replace( '</span>', '', $footer_text ) . ' | ' . $gfi_rate_text . '</span>';

		}
	}

}