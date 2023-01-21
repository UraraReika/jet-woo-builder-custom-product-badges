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

		$data = $request->get_params();

		if ( is_wp_error( $data ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error.', 'jwb-custom-product-badges' ),
			] );
		}

		$settings = get_option( \JWB_CPB\Settings::get_instance()->key, [] );

		foreach ( $data['displayConditions'] as $key => $condition ) {
			if ( $data['processedCondition'] === $condition['_id'] ) {
				$this->handle_products_badges_meta( $condition, $settings['displayConditions'][ $key ] );
			}
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $data );

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
	 * @param array $condition       Currently handling condition.
	 * @param array $stored_condiion Stored handling.
	 *
	 * @return void
	 */
	public function handle_products_badges_meta( $condition, $stored_condiion ) {

		$set             = true;
		$badges          = $condition['badges'] ?? [];
		$operator        = $condition['operator'] ?? 'equal';
		$value           = $condition['value'] ?? [];
		$stored_badges   = $stored_condiion['badges'] ?? [];
		$stored_operator = $stored_condiion['operator'] ?? 'equal';
		$stored_value    = $stored_condiion['value'] ?? [];

		$updated_badge = $this->get_updated_value( $badges, $stored_badges );

		if ( ! empty( $updated_badge ) && count( $badges ) < count( $stored_badges ) ) {
			$set    = false;
			$badges = $updated_badge;
		}

		$updated_value = $this->get_updated_value( $value, $stored_value );

		if ( ! empty( $updated_value ) && count( $value ) < count( $stored_value ) ) {
			$set   = false;
			$value = $updated_value;
		}

		if ( empty( $value ) ) {
			return;
		}

		$operatorSwitched = $operator !== $stored_operator;

		switch ( $condition['parameter'] ) {
			case 'products':
				$is_equal = 'equal' === $operator;

				if ( $is_equal ) {
					$args['include'] = $value;
				} else {
					$args['exclude'] = $value;
				}

				if ( ! $operatorSwitched ) {
					$this->update_products_badges_meta( $args, $badges, $set );
				} else {
					$this->update_products_badges_meta( [ 'include' => $value ], $badges, $is_equal );
					$this->update_products_badges_meta( [ 'exclude' => $value ], $badges, ! $is_equal );
				}

				break;

			default:
				break;
		}

	}

	/**
	 * Get updated value.
	 *
	 * Return list of difference in values.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @param array $value        Request value.
	 * @param array $stored_value Stored settings value.
	 *
	 * @return array
	 */
	public function get_updated_value( $value, $stored_value ) {

		$intersected_value = array_intersect( $value, $stored_value );

		return array_merge( array_diff( $value, $intersected_value ), array_diff( $stored_value, $intersected_value ) );

	}

}