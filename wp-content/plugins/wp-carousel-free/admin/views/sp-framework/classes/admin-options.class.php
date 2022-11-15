<?php
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Options' ) ) {
	/**
	 *
	 * Options Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Options extends SP_WPCF_Abstract {

		// Constants.

		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';

		/**
		 * Notice
		 *
		 * @var string
		 */
		public $notice = '';

		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = 'options';

		/**
		 * Sections
		 *
		 * @var array
		 */
		public $sections = array();

		/**
		 * Options
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Errors
		 *
		 * @var array
		 */
		public $errors = array();

		/**
		 * Pre_tabs
		 *
		 * @var array
		 */
		public $pre_tabs = array();

		/**
		 * Pre_fields
		 *
		 * @var array
		 */
		public $pre_fields = array();

		/**
		 * Pre_sections
		 *
		 * @var array
		 */
		public $pre_sections = array();

		/**
		 * Args
		 *
		 * @var array
		 */
		public $args = array(

			// Framework title.
			'framework_title'         => '',
			'framework_class'         => '',

			// menu settings.
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_capability'         => 'manage_options',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',
			'sub_menu_title'          => '',

			// menu extras.
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_in_network'         => true,
			'show_in_customizer'      => false,

			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'show_form_warning'       => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true,
			'form_action'             => '',

			// admin bar menu settings.
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 50,

			// Footer.
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// Database model.
			'database'                => '', // options, transient, theme_mod, network.
			'transient_time'          => 0,

			// Contextual help.
			'contextual_help'         => array(),
			'contextual_help_sidebar' => '',

			// Typography options.
			'enqueue_webfont'         => false,
			'async_webfont'           => false,

			// Others.
			'output_css'              => true,

			// Theme.
			'nav'                     => 'normal',
			'theme'                   => 'dark',
			'class'                   => '',

			// External default values.
			'defaults'                => array(),

		);

		/**
		 * Run framework construct.
		 *
		 * @param  int   $key key.
		 * @param  array $params params.
		 * @return void
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique   = $key;
			$this->args     = apply_filters( "wpcf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "wpcf_{$this->unique}_sections", $params['sections'], $this );

			// Run only is admin panel options, avoid performance loss.
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_wpcf_' . $this->unique . '_ajax_save', array( $this, 'ajax_save' ) );

			if ( 'network' === $this->args['database'] && ! empty( $this->args['show_in_network'] ) ) {
				add_action( 'network_admin_menu', array( $this, 'add_admin_menu' ) );
			}

			// wp enqueue for typography and output css.
			parent::__construct();

		}

		/**
		 * Instance.
		 *
		 * @param  int   $key key.
		 * @param  array $params params.
		 * @return array
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		/**
		 * Pre_tabs.
		 *
		 * @param array $sections section.
		 * @return section
		 */
		public function pre_tabs( $sections ) {

			$result  = array();
			$parents = array();
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], array( 'priority' => 'ASC' ), 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}

			return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
		}

		/**
		 * Pre_fields
		 *
		 * @param array $sections section.
		 * @return array
		 */
		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		/**
		 * Pre_sections.
		 *
		 * @param array $sections section.
		 * @return array
		 */
		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$sub['ptitle'] = $tab['title'];
						$result[]      = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Add admin bar menu
		 *
		 * @param mixed $wp_admin_bar Admin bar.
		 * @return void
		 */
		public function add_admin_bar_menu( $wp_admin_bar ) {

			if ( is_network_admin() && ( 'network' !== $this->args['database'] || true !== $this->args['show_in_network'] ) ) {
				return;
			}

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="wpcf-ab-icon ab-icon ' . esc_attr( $this->args['admin_bar_menu_icon'] ) . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . esc_attr( $this->args['menu_title'] ),
						'href'  => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ) ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $menu_key => $menu_value ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $menu_key,
								'title'  => $menu_value[0],
								'href'   => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_value[2] ) : admin_url( 'admin.php?page=' . $menu_value[2] ) ),
							)
						);
					}
				}
			}

		}

		/**
		 * Ajax_save.
		 *
		 * @return void
		 */
		public function ajax_save() {

			$result = $this->set_options( true );

			if ( ! $result ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error while saving the changes.', 'wp-carousel-free' ) ) );
			} else {
				wp_send_json_success(
					array(
						'notice' => $this->notice,
						'errors' => $this->errors,
					)
				);
			}

		}

		/**
		 * Get default value.
		 *
		 * @param  mixed $field Field array.
		 * @return array.
		 */
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Save defaults and set new fields value to main options
		 *
		 * @return void
		 */
		public function save_defaults() {

			$tmp_options = $this->options;

			foreach ( $this->pre_fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$this->options[ $field['id'] ] = ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : $this->get_default( $field );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		/**
		 * Set options.
		 *
		 * @param  bool $ajax is ajax.
		 * @return bool
		 */
		public function set_options( $ajax = false ) {

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach.
			$response = ( $ajax && ! empty( $_POST['data'] ) ) ? json_decode( wp_unslash( trim( $_POST['data'] ) ), true ) : $_POST;  // phpcs:ignore -- nonce veri

			// Set variables.
			$data      = array();
			$noncekey  = 'wpcf_options_nonce' . $this->unique;
			$nonce     = ( ! empty( $response[ $noncekey ] ) ) ? $response[ $noncekey ] : '';
			$options   = ( ! empty( $response[ $this->unique ] ) ) ? $response[ $this->unique ] : array();
			$transient = ( ! empty( $response['wpcf_transient'] ) ) ? $response['wpcf_transient'] : array();

			if ( wp_verify_nonce( $nonce, 'wpcf_options_nonce' ) ) {

				$importing  = false;
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				if ( ! $ajax && ! empty( $response['wpcf_import_data'] ) ) {

					// XSS ok.
					// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341.
					$import_data  = json_decode( wp_unslash( trim( $response['wpcf_import_data'] ) ), true );
					$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : array();
					$importing    = true;
					$this->notice = esc_html__( 'Settings successfully imported.', 'wp-carousel-free' );

				}

				if ( ! empty( $transient['reset'] ) ) {

					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$data[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default settings restored.', 'wp-carousel-free' );

				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {

					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {

						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$data[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$data = wp_parse_args( $data, $this->options );

					$this->notice = esc_html__( 'Default settings restored.', 'wp-carousel-free' );

				} else {

					// Sanitize and validate.
					foreach ( $this->pre_fields as $field ) {

						if ( ! empty( $field['id'] ) ) {

							$field_id    = $field['id'];
							$field_value = isset( $options[ $field_id ] ) ? $options[ $field_id ] : '';

							// Ajax and Importing doing wp_unslash already.
							if ( ! $ajax && ! $importing ) {
								$field_value = wp_unslash( $field_value );
							}

							// Sanitize "post" request of field.
							if ( ! isset( $field['sanitize'] ) ) {

								if ( is_array( $field_value ) ) {

									$data[ $field_id ] = wp_kses_post_deep( $field_value );

								} else {

									$data[ $field_id ] = wp_kses_post( $field_value );

								}
							} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {

									$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );

							} else {

								$data[ $field_id ] = $field_value;

							}

							// Validate "post" request of field.
							if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {

								$has_validated = call_user_func( $field['validate'], $field_value );

								if ( ! empty( $has_validated ) ) {

									$data[ $field_id ]         = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';
									$this->errors[ $field_id ] = $has_validated;

								}
							}
						}
					}
				}

				$data = apply_filters( "wpcf_{$this->unique}_save", $data, $this );

				do_action( "wpcf_{$this->unique}_save_before", $data, $this );

				$this->options = $data;

				$this->save_options( $data );

				do_action( "wpcf_{$this->unique}_save_after", $data, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'wp-carousel-free' );
				}

				return true;

			}

			return false;

		}

		/**
		 * Save options database
		 *
		 * @param  mixed $data saving data.
		 * @return void
		 */
		public function save_options( $data ) {

			if ( 'transient' === $this->args['database'] ) {
				set_transient( $this->unique, $data, $this->args['transient_time'] );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				set_theme_mod( $this->unique, $data );
			} elseif ( 'network' === $this->args['database'] ) {
				update_site_option( $this->unique, $data );
			} else {
				update_option( $this->unique, $data );
			}

			do_action( "wpcf_{$this->unique}_saved", $data, $this );

		}

		/**
		 * Get options from database.
		 *
		 * @return array
		 */
		public function get_options() {

			if ( 'transient' === $this->args['database'] ) {
				$this->options = get_transient( $this->unique );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( 'network' === $this->args['database'] ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = array();
			}

			return $this->options;

		}

		/**
		 * Admin menu.
		 *
		 * @return void
		 */
		public function add_admin_menu() {

			extract( $this->args );

			if ( 'submenu' === $menu_type ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $sub_menu_title ) ) {
					call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $sub_menu_title ), esc_attr( $sub_menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ) );
				}

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus.
					foreach ( $this->pre_tabs as $section ) {
						call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $section['title'] ), esc_attr( $section['title'] ), $menu_capability, $menu_slug . '#tab=' . sanitize_title( $section['title'] ), '__return_null' );
					}

					remove_submenu_page( $menu_slug, $menu_slug );

				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, array( $this, 'add_page_on_load' ) );

		}

		/**
		 * Add_page_on_load.
		 *
		 * @return void.
		 */
		public function add_page_on_load() {

			if ( ! empty( $this->args['contextual_help'] ) ) {

				$screen = get_current_screen();

				foreach ( $this->args['contextual_help'] as $tab ) {
					$screen->add_help_tab( $tab );
				}

				if ( ! empty( $this->args['contextual_help_sidebar'] ) ) {
					$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
				}
			}

			// add_filter( 'admin_footer_text', array( $this, 'add_admin_footer_text' ) );
		}


		/**
		 * Error_check.
		 *
		 * @param  mixed $sections section.
		 * @param  mixed $err  error.
		 * @return statement.
		 */
		public function error_check( $sections, $err = '' ) {

			if ( ! $this->args['ajax_save'] ) {

				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="wpcf-label-error">!</span>';
							}
						}
					}
				}

				if ( ! empty( $sections['subs'] ) ) {
					foreach ( $sections['subs'] as $sub ) {
						$err = $this->error_check( $sub, $err );
					}
				}

				if ( ! empty( $sections['id'] ) && array_key_exists( $sections['id'], $this->errors ) ) {
					$err = $this->errors[ $sections['id'] ];
				}
			}

			return $err;
		}

		/**
		 * Option page html output
		 *
		 * @return void
		 */
		public function add_options_html() {

			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_all      = ( ! $has_nav ) ? ' wpcf-show-all' : '';
			$show_buttons  = isset( $this->args['show_buttons'] ) ? $this->args['show_buttons'] : true;
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' wpcf-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' wpcf-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' wpcf-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';
			$nav_type      = ( 'inline' === $this->args['nav'] ) ? 'inline' : 'normal';
			$form_action   = ( $this->args['form_action'] ) ? $this->args['form_action'] : '';

			do_action( 'wpcf_options_before' );

			echo '<div class="wpcf wpcf-options' . esc_attr( $theme . $class . $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';
			$notice_class = ( ! empty( $this->notice ) ) ? 'wpcf-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';
			// echo '<div class="wpcf-form-result wpcf-form-success ' . esc_attr( $notice_class ) . '">' . wp_kses_post( $notice_text ) . '</div>';.

			// echo ( $this->args['show_form_warning'] ) ? '<div class="wpcf-form-result wpcf-form-warning">' . esc_html__( 'You have unsaved changes, save your changes!', 'wp-carousel-free' ) . '</div>' : '';.

			echo '<div class="wpcf-container">';

			echo '<form method="post" action="' . esc_attr( $form_action ) . '" enctype="multipart/form-data" id="wpcf-form" autocomplete="off" novalidate="novalidate">';

			echo '<input type="hidden" class="wpcf-section-id" name="wpcf_transient[section]" value="1">';

			wp_nonce_field( 'wpcf_options_nonce', 'wpcf_options_nonce' . $this->unique );

			echo '<div class="wpcf-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="wpcf-header-inner">';

			echo '<div class="wpcf-header-left">';
			if ( $show_buttons ) {
				echo '<h1><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 709.6 557.7" style="enable-background:new 0 0 709.6 557.7;" xml:space="preserve"> <style type="text/css"> .st0{fill:#1DAEB9;}</style><g><g><polygon class="st0" points="514,317.1 437,221.5 483.7,183.9 560.7,279.5"/><polygon class="st0" points="484,375.3 437.3,337.7 514.2,242.1 560.9,279.7"/></g><g><polygon class="st0" points="196.4,317.1 149.7,279.5 226.7,183.9 273.4,221.5"/><polygon class="st0" points="226.4,375.3 149.5,279.7 196.2,242.1 273.1,337.7"/></g><g><path class="st0" d="M699,547.2H11.4V12H699V547.2z M89.1,469.5h532.2V89.7H89.1V469.5z"/></g></g></svg>' . esc_attr( $this->args['framework_title'] ) . '</h1>';
			} else {
				echo '<h1 class="export-import"><img src="' . WPCAROUSELF_URL . 'admin/img/import-export.svg">' . esc_attr( $this->args['framework_title'] ) . '</h1>';
			}
			echo '</div>';

			echo '<div class="wpcf-header-right">';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="wpcf-expand-all" title="' . esc_html__( 'show all settings', 'wp-carousel-free' ) . '"><i class="fa fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="wpcf-search"><input type="text" name="wpcf-search" placeholder="' . esc_html__( 'Search...', 'wp-carousel-free' ) . '" autocomplete="off" /></div>' : '';
			if ( $show_buttons ) {
				echo '<div class="wpcf-buttons">';
				echo '<input type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary wpcf-top-save wpcf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save Settings', 'wp-carousel-free' ) . '" data-save="' . esc_html__( 'Saving...', 'wp-carousel-free' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="wpcf_transient[reset_section]" class="button button-secondary wpcf-reset-section wpcf-confirm" value="' . esc_html__( 'Reset Tab', 'wp-carousel-free' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all the settings of this tab?', 'wp-carousel-free' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="wpcf_transient[reset]" class="button wpcf-warning-primary wpcf-reset-all wpcf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'wp-carousel-free' ) : esc_html__( 'Reset', 'wp-carousel-free' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'wp-carousel-free' ) . '">' : '';
				echo '</div>';
			}
			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="wpcf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="wpcf-nav wpcf-nav-' . esc_attr( $nav_type ) . ' wpcf-nav-options">';

				echo '<ul>';

				foreach ( $this->pre_tabs as $tab ) {

					$tab_id    = sanitize_title( $tab['title'] );
					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="wpcf-tab-icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="wpcf-tab-item">';

						echo wp_kses_post( '<a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '" class="wpcf-arrow">' . $tab_icon . $tab['title'] . $tab_error . '</a>' );

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_id    = $tab_id . '/' . sanitize_title( $sub['title'] );
							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="wpcf-tab-icon ' . esc_attr( $sub['icon'] ) . '"></i>' : '';

							echo wp_kses_post( '<li><a href="#tab=' . esc_attr( $sub_id ) . '" data-tab-id="' . esc_attr( $sub_id ) . '">' . $sub_icon . $sub['title'] . $sub_error . '</a></li>' );

						}

						echo '</ul>';

						echo '</li>';

					} else {

						echo wp_kses_post( '<li class="wpcf-tab-item"><a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '">' . $tab_icon . $tab['title'] . $tab_error . '</a></li>' );

					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="wpcf-content">';

			echo '<div class="wpcf-sections">';

			foreach ( $this->pre_sections as $section ) {

				$section_onload = ( ! $has_nav ) ? ' wpcf-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="wpcf-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_parent = ( ! empty( $section['ptitle'] ) ) ? sanitize_title( $section['ptitle'] ) . '/' : '';
				$section_slug   = ( ! empty( $section['title'] ) ) ? sanitize_title( $section_title ) : '';

				echo '<div class="wpcf-section hidden' . esc_attr( $section_onload . $section_class ) . '" data-section-id="' . esc_attr( $section_parent . $section_slug ) . '">';
				echo ( $has_nav ) ? wp_kses_post( '<div class="wpcf-section-title"><h3>' . $section_icon . $section_title . '</h3></div>' ) : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="wpcf-field wpcf-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						if ( ! empty( $field['id'] ) ) {
							$field['default'] = $this->get_default( $field );
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						SP_WPCF::field( $field, $value, $this->unique, 'options' );

					}
				} else {

								echo '<div class="wpcf-no-option">' . esc_html__( 'No data available.', 'wp-carousel-free' ) . '</div>';

				}

				echo '</div>';

			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo ( $has_nav && 'normal' === $nav_type ) ? '<div class="wpcf-nav-background"></div>' : '';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {

				echo '<div class="wpcf-footer">';

				echo '<div class="wpcf-buttons">';
				echo '<input type="submit" name="wpcf_transient[save]" class="button button-primary wpcf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'wp-carousel-free' ) . '" data-save="' . esc_html__( 'Saving...', 'wp-carousel-free' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="wpcf_transient[reset_section]" class="button button-secondary wpcf-reset-section wpcf-confirm" value="' . esc_html__( 'Restore', 'wp-carousel-free' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'wp-carousel-free' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="wpcf_transient[reset]" class="button wpcf-warning-primary wpcf-reset-all wpcf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'wp-carousel-free' ) : esc_html__( 'Reset', 'wp-carousel-free' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'wp-carousel-free' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="wpcf-copyright">' . wp_kses_post( $this->args['footer_text'] ) . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';

			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';

			echo '</div>';

			do_action( 'wpcf_options_after' );

		}
	}
}
