<?php
namespace TheplusAddons\L_WPML;
use L_WPML_Elementor_Module_With_Items;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

class Tp_Scroll_Navigation extends L_WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'scroll_navigation_menu_list';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'tooltip_menu_title'
		);
	}

  	/**
     * @param string $field
	 * 
	 * Get the field title string
     *
     * @return string
     */
	protected function get_title( $field ) {
		switch($field) {
			case 'tooltip_menu_title':
				return esc_html__( 'Scroll Navigation : Tooltip Title', 'tpebl' );
				
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 * 
	 * Get perspective field types.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch($field) {
			case 'tooltip_menu_title':
				return 'LINE';
				
			default:
				return '';
		}
	}

}
