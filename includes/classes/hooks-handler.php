<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Hooks_Handler {

	public function __construct() {
		add_action( 'jwb-custom-product-badges/rest-api/endpoints/plugin-settings/after-settings-update', [ $this, 'handle_plugin_settings' ], 10, 1 );
	}

	/**
	 * Handle settings.
	 *
	 * Handle some actions after plugins settings update.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $settings Plugins settings list.
	 */
	public function handle_plugin_settings( $settings ) {
		if ( 'remove' === $settings['actionType'] ) {
			$this->update_products();
		}
	}

	/**
	 * Update products.
	 *
	 * Updates all products badges meta when budge remove in admin dashboard.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function update_products() {

		$badges = Plugin::instance()->tools->get_badges_list();
		$posts  = get_posts( [
			'post_type'   => 'product',
			'numberposts' => -1,
		] );

		foreach ( $posts as $post ) {
			$badge_meta = get_post_meta( $post->ID, '_jet_woo_builder_badges', true );

			if ( ! empty( $badge_meta ) ) {
				$new_badge_meta = [];

				foreach ( $badges as $key => $value ) {
					if ( in_array( $key, $badge_meta ) ) {
						$new_badge_meta[] = $key;
					}
				}

				update_post_meta( $post->ID, '_jet_woo_builder_badges', $new_badge_meta );
			}
		}

	}

}