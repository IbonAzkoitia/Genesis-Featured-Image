<?php
/**
 * CCreates the plugin front end output.
 *
 * @package   GenesisFeaturedImage
 * @author    Ibon Azkoitia <ibon@kreatidos.com>
 * @license   GPL-2.0+
 * @link      http://www.kreatidos.com
 * @copyright 2015 Ibon Azkoitia
 */
class Genesis_Featured_Image_Output {

	/**
	 * set parameters for scripts, etc. to run.
	 *
	 * @since 1.0.0
	 */
	public function gfi_manage_output() {

		add_action( 'wp_enqueue_scripts', array( $this, 'gfi_load_scripts' ) );

	}

	/**
	 * enqueue plugin styles and scripts.
	 * @return  enqueue
	 *
	 * @since 1.0.0
	 */
	public function gfi_load_scripts() {

		$version = Genesis_Featured_Image_Common::$version;

		$css_file = apply_filters( 'genesis-featured-image_css-file', plugin_dir_url( __FILE__ ) . '/css/genesis-featured-image.css' );
		wp_enqueue_style( 'genesis-featured-image-style', esc_url( $css_file ), array(), $version );


		$displaysetting = get_option( 'genesis-featured-image' );
		$hookorder      = $displaysetting['gfi_hook_order'];

		add_action( 'genesis_after_header', array( $this, 'gfi_show_featured_image' ), $hookorder );
	}

	/**
	 * Featured Image, just after the header
	 * @return image
	 *
	 * @since 1.0.0
	 */
	public function gfi_show_featured_image() {

		$displaysetting = get_option( 'genesis-featured-image');
		$id             = $displaysetting['featured_image'];
		$empty          = wp_get_attachment_image_src( absint( $id ) );

		$displaysetting = get_option( 'genesis-featured-image');
		$fullwidth      = $displaysetting['gfi_full_width'];

		if ( ! empty( $empty ) && ( $fullwidth == false ) ) {
			
			$preview = wp_get_attachment_image_src( absint( $id ), 'full' );
					
			echo '<div class="wrap">';
			echo '<div id="gfi-featured-image">';
			printf( '<img src="%s" />', esc_url( $preview[0] ) );
			echo '</div>';
			echo '</div>';

			

		} elseif ( ! empty( $empty ) && ( $fullwidth == true ) ) {

			$preview = wp_get_attachment_image_src( absint( $id ), 'full' );
					
			echo '<div id="gfi-featured-image">';
			printf( '<img src="%s" />', esc_url( $preview[0] ) );
			echo '</div>';

		} else {}

	}
}