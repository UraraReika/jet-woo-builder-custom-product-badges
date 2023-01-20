<?php

namespace JWB_CPB\Endpoints;

use Automattic\WooCommerce\Blocks\Package;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Update_Condition extends Base {

	public function get_name() {
		return 'update-condition';
	}

	public function get_method() {
		return 'POST';
	}

	public function callback( $request ) {

		parent::callback( $request );

		$data = $request->get_params();

		foreach ( $data['displayConditions'] as $condition ) {
			if ( $data['processedCondition'] === $condition['_id'] ) {
				$this->handle_products_badges_meta( $condition );
			}
		}

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been updated.', 'jwb-custom-product-badges' ),
		] );

	}

	/**
	 * Handle products badge meta.
	 *
	 * Handle products badges meta for different types of parameter.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @param array $condition Currently handling condition.
	 *
	 * @return void
	 */
	public function handle_products_badges_meta( $condition ) {

		$args = [
			'post_type'   => 'product',
			'numberposts' => -1,
		];

		$condition_value = $condition['value'] ?? [];

		switch ( $condition['parameter'] ) {
			case 'products':
				$condition_products = get_posts( array_merge( $args, [ 'include' => $condition_value ] ) );
				$products           = get_posts( array_merge( $args, [ 'exclude' => $condition_value ] ) );
				$include            = 'equal' === $condition['operator'];

				$this->update_products_badges_meta( $condition_products, $condition, $include );
				$this->update_products_badges_meta( $products, $condition, ! $include );

				break;

			default:
				break;
		}

	}

	/**
	 * Update products badges meta.
	 *
	 * Updates products badges meta depending on operator.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @param array $products  List of products.
	 * @param array $condition Currently handling condition.
	 * @param bool  $include   Update status.
	 *
	 * @return void
	 */
	public function update_products_badges_meta( $products, $condition, $include ) {

		$condition_badges = $condition['badges'] ?? [];

		foreach ( $products as $product ) {
			$badge_meta = get_post_meta( $product->ID, '_jet_woo_builder_badges', true );

			if ( $include ) {
				if ( empty( $badge_meta ) ) {
					$badge_meta = $condition_badges;
				} else {
					$badge_meta = array_unique( array_merge( $badge_meta, $condition_badges ) );
				}
			} else {
				if ( empty( $badge_meta ) ) {
					continue;
				}

				foreach ( $badge_meta as $key => $value ) {
					if ( in_array( $value, $condition_badges ) ) {
						unset( $badge_meta[ $key ] );
					}
				}
			}

			update_post_meta( $product->ID, '_jet_woo_builder_badges', $badge_meta );
		}

	}

}