<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tools {

	/**
	 * Predefined badges list.
	 *
	 * Returns list of predefined custom badges, than can be extended with hook.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_predefined_badges_list() {
		return apply_filters( 'jwb-custom-product-badges/tools/badges-list', [
			'popular'    => __( 'Popular', 'jet-woo-builder' ),
			'bestseller' => __( 'Bestseller', 'jet-woo-builder' ),
			'new'        => __( 'New', 'jet-woo-builder' ),
		] );
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

		$predefined_badges  = $this->get_predefined_badges_list();
		$transformed_badges = [];

		foreach ( $predefined_badges as $key => $value ) {
			$transformed_badges[] = [
				'value' => $key,
				'label' => $value,
			];
		}

		return [
			'messages'     => [
				'saveSuccess' => __( 'Saved', 'jet-woo-builder' ),
				'saveError'   => __( 'Error', 'jet-woo-builder' ),
			],
			'settingsData' => [
				'badgesList' => $transformed_badges,
			],
		];
	}

}