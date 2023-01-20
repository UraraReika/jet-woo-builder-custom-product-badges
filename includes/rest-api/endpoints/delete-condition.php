<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Delete_Condition extends Base {

	public function get_name() {
		return 'delete-condition';
	}

	public function get_method() {
		return 'POST';
	}

	public function callback( $request ) {

		$data     = $request->get_params();
		$settings = get_option( \JWB_CPB\Settings::get_instance()->key, [] );

		if ( is_wp_error( $data ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error.', 'jwb-custom-product-badges' ),
			] );
		}

		$condition_badges = [];

		foreach ( $settings['displayConditions'] as $condition ) {
			if ( $data['processedCondition'] === $condition['_id'] ) {
				$condition_badges = $condition['badges'] ?? [];
			}
		}

		if ( ! empty( $condition_badges ) ) {
			$products = get_posts( [
				'post_type'   => 'product',
				'numberposts' => -1,
			] );

			foreach ( $products as $product ) {
				$badge_meta = get_post_meta( $product->ID, '_jet_woo_builder_badges', true );

				if ( empty( $badge_meta ) ) {
					continue;
				}

				foreach ( $badge_meta as $key => $value ) {
					if ( in_array( $value, $condition_badges ) ) {
						unset( $badge_meta[ $key ] );
					}
				}

				update_post_meta( $product->ID, '_jet_woo_builder_badges', $badge_meta );
			}
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $data );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been deleted.', 'jwb-custom-product-badges' ),
		] );

	}

}