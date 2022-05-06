<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tools {

	/**
	 * Predefined badges list.
	 *
	 * Returns list of predefined custom badges.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_predefined_badges_list() {
		return [
			'popular'    => __( 'Popular', 'jet-woo-builder' ),
			'bestseller' => __( 'Bestseller', 'jet-woo-builder' ),
			'new'        => __( 'New', 'jet-woo-builder' ),
		];
	}

	/**
	 * Badges list.
	 *
	 * Returns list of custom badges, than can be extended with hook.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return mixed|void
	 */
	public function get_badges_list() {

		$badges   = [];
		$settings = get_option( Settings::get_instance()->key, [] );

		if ( $settings ) {
			if ( isset( $settings['badgesList'] ) ) {
				foreach ( $settings['badgesList'] as $badge ) {
					$badges[ $badge['value'] ] = $badge['label'];
				}
			}
		}

		return apply_filters( 'jwb-custom-product-badges/tools/badges-list', $badges );

	}

	/**
	 * Localize data.
	 *
	 * Returns plugins settings localized data list.
	 *
	 * @since 1.1.0
	 * @since public
	 *
	 * @return array
	 */
	public function get_localize_data() {

		$badges   = [];
		$settings = get_option( Settings::get_instance()->key, [] );

		if ( $settings ) {
			$badges = $settings['badgesList'];
		}

		return [
			'messages'       => [
				'saveSuccess' => __( 'Saved', 'jet-woo-builder' ),
				'saveError'   => __( 'Error', 'jet-woo-builder' ),
			],
			'settingsApiUrl' => get_rest_url() . 'jwb-custom-product-badges-api/v1/plugin-settings',
			'settingsData'   => [
				'badgesList' => $badges,
				'actionType' => [
					'value' => 'add',
				],
			],
		];
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

		$badges = $this->get_badges_list();
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