<?php

namespace JWB_CPB\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class General extends Page_Module_Base {

	/**
	 * Page slug.
	 *
	 * Returns module slug.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_page_slug() {
		return 'jwb-custom-product-badges-general-settings';
	}

	/**
	 * Parent slug.
	 *
	 * Returns parent slug.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * Page name.
	 *
	 * Returns page name.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_page_name() {
		return __( 'General Settings', 'jet-woo-builder' );
	}

	/**
	 * Category.
	 *
	 * Returns category slug.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_category() {
		return 'jwb-custom-product-badges';
	}

	/**
	 * Page link.
	 *
	 * Returns page link.
	 *
	 * @access 1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue assets.
	 *
	 * Enqueue module specific assets.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'jwb-cpb-admin-vue-components',
			JWB_CUSTOM_PRODUCT_BUDGES_URL . 'assets/js/admin/vue-components.js',
			[ 'cx-vue-ui' ],
			JWB_CUSTOM_PRODUCT_BUDGES_VERSION,
			true
		);

		wp_localize_script(
			'jwb-cpb-admin-vue-components',
			'JWBCPBSettingsConfig',
			\JWB_CPB\Plugin::instance()->tools->get_localize_data()
		);

	}

	/**
	 * Page config.
	 *
	 * Modify page config.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $config Configuration list.
	 * @param bool  $page
	 * @param bool  $subpage
	 *
	 * @return array
	 */
	public function page_config( $config = [], $page = false, $subpage = false ) {

		$config['pageModule']    = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;

	}

	/**
	 * Page templates.
	 *
	 * Add page templates.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $templates Templates list.
	 * @param bool  $page
	 * @param bool  $subpage
	 *
	 * @return array
	 */
	public function page_templates( $templates = [], $page = false, $subpage = false ) {

		$templates['jwb-custom-product-badges-general-settings'] = JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'templates/admin/general-settings.php';

		return $templates;

	}

}
