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

		$deprecated_hook = 'jet-woo-builder-cpb/integration/badges';
		$replacement_hook = 'jwb-custom-product-badges/tools/badges-list';

		_deprecated_hook( $deprecated_hook, '1.1.0', $replacement_hook );

		$badges = apply_filters( $deprecated_hook, $badges );

		return apply_filters( $replacement_hook, $badges );

	}

	/**
	 * Badges for display.
	 *
	 * Return pretty list of badges labels to display them in widgets.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $badges Item badges list.
	 *
	 * @return array
	 */
	public function get_badges_for_display( $badges ) {

		$badges_list = $this->get_badges_list();
		$result    = [];

		foreach ( $badges as $badge ) {
			if ( isset( $badges_list[ $badge ] ) ) {
				$result[ $badge ] = $badges_list[ $badge ];
			}
		}

		return $result;

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

}