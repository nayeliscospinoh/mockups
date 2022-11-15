<?php
/**
 *  Enqueue public script for the WP Carousel
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/public
 */

/**
 * The public-facing functionality of the plugin.
 */
class WP_Carousel_Free_Public {

	/**
	 * Script and style suffix
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * The ID of the plugin.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $plugin_name The ID of this plugin
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $version The current version fo the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class sets its properties.
	 *
	 * @since 2.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the plugin.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		if ( wpcf_get_option( 'wpcp_enqueue_swiper_css', true ) ) {
			wp_enqueue_style( 'wpcf-swiper', WPCAROUSELF_URL . 'public/css/swiper-bundle.min.css', array(), $this->version, 'all' );
		}
		if ( wpcf_get_option( 'wpcp_enqueue_fa_css', true ) ) {
			wp_enqueue_style( $this->plugin_name . '-fontawesome', WPCAROUSELF_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name, WPCAROUSELF_URL . 'public/css/wp-carousel-free-public' . $this->suffix . '.css', array(), $this->version, 'all' );

		$wpc_posts = new WP_Query(
			array(
				'post_type'      => 'sp_wp_carousel',
				'posts_per_page' => 500,
				'fields'         => 'ids',
			)
		);

		$carousel_ids         = $wpc_posts->posts;
		$the_wpcf_dynamic_css = '';
		foreach ( $carousel_ids as $post_id ) {
			include WPCAROUSELF_PATH . '/public/dynamic-style.php';
		}
		include WPCAROUSELF_PATH . '/public/responsive.php';
		$the_wpcf_dynamic_css .= trim( html_entity_decode( wpcf_get_option( 'wpcp_custom_css' ) ) );
		wp_add_inline_style( $this->plugin_name, $the_wpcf_dynamic_css );
	}

	/**
	 * Register the JavaScript for the public-facing side of the plugin.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( 'wpcp-preloader', WPCAROUSELF_URL . 'public/js/preloader' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-swiper', WPCAROUSELF_URL . 'public/js/swiper-bundle.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-swiper-config', WPCAROUSELF_URL . 'public/js/wp-carousel-free-public' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Enqueue css and js files for live preview.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_carousel' === $the_current_post_type ) {
			// Enqueue css file.
			if ( wpcf_get_option( 'wpcp_enqueue_swiper_css', true ) ) {
				wp_enqueue_style( 'wpcf-swiper', WPCAROUSELF_URL . 'public/css/swiper-bundle.min.css', array(), $this->version, 'all' );
			}
			if ( wpcf_get_option( 'wpcp_enqueue_fa_css', true ) ) {
				wp_enqueue_style( $this->plugin_name . '-fontawesome', WPCAROUSELF_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
			}
			wp_enqueue_style( $this->plugin_name, WPCAROUSELF_URL . 'public/css/wp-carousel-free-public' . $this->suffix . '.css', array(), $this->version, 'all' );

			// Enqueue js file.
			wp_enqueue_script( 'wpcf-swiper', WPCAROUSELF_URL . 'public/js/swiper-bundle.min.js', array( 'jquery' ), $this->version, true );
		}
	}
}
