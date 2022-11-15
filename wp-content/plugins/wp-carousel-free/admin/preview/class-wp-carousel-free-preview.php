<?php
/**
 * The admin live preview.
 *
 * @link        https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin
 */

/**
 * The admin preview.
 */
class WP_Carousel_Free_Preview {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->wpcf_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function wpcf_preview_action() {
		// admin Preview.
		add_action( 'wp_ajax_sp_wpcf_preview_meta_box', array( $this, 'wpcf_backend_preview' ) );
	}

	/**
	 * Function Backed preview.
	 *
	 * @since 2.0.0
	 */
	public function wpcf_backend_preview() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';// phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'wpcf_metabox_nonce' ) ) {
			return;
		}

		$setting_data = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting_data );

		$post_id            = intval( $setting_data['post_ID'] );
		$upload_data        = $setting_data['sp_wpcp_upload_options'];
		$shortcode_data     = $setting_data['sp_wpcp_shortcode_options'];
		$main_section_title = $setting_data['post_title'];

		echo '<style>';
		$the_wpcf_dynamic_css = '';
		include WPCAROUSELF_PATH . '/public/dynamic-style.php';
		$the_wpcf_dynamic_css .= trim( html_entity_decode( wpcf_get_option( 'wpcp_custom_css' ) ) );
		include WPCAROUSELF_PATH . '/public/responsive.php';
		echo $the_wpcf_dynamic_css;
		echo '</style>';
		WP_Carousel_Free_Shortcode::wpcf_html_show( $upload_data, $shortcode_data, $post_id, $main_section_title );
		die();
	}
}
new WP_Carousel_Free_Preview();
