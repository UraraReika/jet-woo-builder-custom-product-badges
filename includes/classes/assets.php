<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Assets {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Admin assets.
	 *
	 * Enqueue admin styles and scripts.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function enqueue_admin_assets() {

		$current_screen = get_current_screen();

		if ( 'crocoblock_page_jet-dashboard-settings-page' !== $current_screen->id && 'product' !== $current_screen->post_type ) {
			return;
		}

		wp_enqueue_style(
			'jwbcpb-admin-styles',
			JWB_CUSTOM_PRODUCT_BUDGES_URL . 'assets/css/admin/styles.css',
			[],
			JWB_CUSTOM_PRODUCT_BUDGES_VERSION
		);

	}

}