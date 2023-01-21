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

		$data = $request->get_params();

		if ( is_wp_error( $data ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error.', 'jwb-custom-product-badges' ),
			] );
		}

		$settings         = get_option( \JWB_CPB\Settings::get_instance()->key, [] );
		$condition_badges = [];

		foreach ( $settings['displayConditions'] as $condition ) {
			if ( $data['processedCondition'] === $condition['_id'] ) {
				$condition_badges = $condition['badges'] ?? [];
			}
		}

		if ( ! empty( $condition_badges ) ) {
			$this->update_products_badges_meta( [], $condition_badges, false );
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $data );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been deleted.', 'jwb-custom-product-badges' ),
		] );

	}

}