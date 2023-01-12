<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tools {

	/**
	 * Predefined badges list.
	 *
	 * Returns list of predefined custom badges, that can be extended with hook..
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_predefined_badges_list() {

		$badges = [
			'popular'    => __( 'Popular', 'jwb-custom-product-badges' ),
			'bestseller' => __( 'Bestseller', 'jwb-custom-product-badges' ),
			'new'        => __( 'New', 'jwb-custom-product-badges' ),
		];

		$deprecated_hook  = 'jet-woo-builder-cpb/integration/badges';
		$replacement_hook = 'jwb-custom-product-badges/tools/badges-list';

		return apply_filters_deprecated( $deprecated_hook, [ $badges ], '1.1.0', $replacement_hook );

	}

	/**
	 * Badges list.
	 *
	 * Returns list of custom badges.
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

		return $badges;

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
		$result      = [];

		foreach ( $badges as $badge ) {
			if ( isset( $badges_list[ $badge ] ) ) {
				$result[ $badge ] = $badges_list[ $badge ];
			}
		}

		return $result;

	}

	/**
	 * Get products list.
	 *
	 * Returns list of products for settings page usage.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_products_list() {

		$posts = get_posts( [
			'post_type'           => 'product',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => -1,
			'suppress_filters'    => false,
			'post_status'         => [ 'publish', 'private' ],
		] );

		$result = [];

		if ( empty( $ids ) || in_array( 'all', $ids ) ) {
			$result[] = [
				'value' => 'all',
				'label' => __( 'All', 'jwb-custom-product-badges' ),
			];
		}

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$result[] = [
					'value' => $post->ID,
					'label' => $post->post_title,
				];
			}
		}

		return $result;

	}

	/**
	 * Localize data.
	 *
	 * Returns plugins settings localized data list.
	 *
	 * @since   1.1.0
	 * @since   1.3.0 Added new parameters.
	 * @access  public
	 *
	 * @return array
	 */
	public function get_localize_data() {

		$settings = get_option( Settings::get_instance()->key, [] );

		return [
			'settingsRestAPI'        => Plugin::instance()->rest_api->get_urls(),
			'settingsData'           => [
				'badgesList'        => $settings['badgesList'] ?? [],
				'displayConditions' => $settings['displayConditions'] ?? [],
			],
			'settingsConditionsData' => [
				'productsList' => $this->get_products_list(),
			],
		];

	}

}