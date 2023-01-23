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

		$settings = get_option( \JWB_CPB\Settings::get_instance()->key, [] );

		foreach ( $settings['displayConditions'] as $condition ) {
			if ( $data['processedCondition'] === $condition['_id'] ) {
				$this->handle_products_badges_meta( $condition );
			}
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $data );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been deleted.', 'jwb-custom-product-badges' ),
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

		$badges   = $condition['badges'] ?? [];
		$operator = $condition['operator'] ?? 'equal';
		$value    = $condition['value'] ?? [];

		if ( empty( $value ) ) {
			return;
		}

		switch ( $condition['parameter'] ) {
			case 'products':
				if ( 'equal' === $operator ) {
					$args['include'] = $value;
				} else {
					$args['exclude'] = $value;
				}

				$this->update_products_badges_meta( $args, $badges, false );

				break;

			default:
				break;
		}

	}

}