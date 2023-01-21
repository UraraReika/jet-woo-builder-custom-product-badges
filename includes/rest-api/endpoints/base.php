<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base {

	/**
	 * Name.
	 *
	 * Returns route name.
	 *
	 * @since 1.1.0
	 * @abstract
	 *
	 * @return string
	 */
	abstract function get_name();

	/**
	 * Methods.
	 *
	 * Returns endpoint request method - GET/POST/PUT/DELETE.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Callback.
	 *
	 * API callback.
	 *
	 * @since 1.1.0
	 * @abstract
	 *
	 * @param object $request Request parameters.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function callback( $request ) {

		$data = $request->get_params();

		if ( is_wp_error( $data ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error.', 'jwb-custom-product-badges' ),
			] );
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $data );

	}

	/**
	 * Update products badges meta.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @param array $args   List of additional products arguments.
	 * @param array $badges List of badges.
	 * @param bool  $set    Handle type.
	 *
	 * @return void
	 */
	public function update_products_badges_meta( $args, $badges, $set ) {

		$default_args = [
			'post_type'   => 'product',
			'numberposts' => -1,
		];

		if ( ! empty( $args ) ) {
			$default_args = array_merge( $default_args, $args );
		}

		$products = get_posts( $default_args );

		if ( ! $products ) {
			return;
		}

		foreach ( $products as $product ) {
			$badge_meta = get_post_meta( $product->ID, '_jet_woo_builder_badges', true );

			if ( $set ) {
				$badge_meta = empty( $badge_meta ) ? $badges : array_unique( array_merge( $badge_meta, $badges ) );
			} else {
				if ( empty( $badge_meta ) ) {
					continue;
				}

				foreach ( $badge_meta as $key => $value ) {
					if ( in_array( $value, $badges ) ) {
						unset( $badge_meta[ $key ] );
					}
				}
			}

			update_post_meta( $product->ID, '_jet_woo_builder_badges', $badge_meta );
		}

	}

	/**
	 * Permission callback.
	 *
	 * Check user access to current end-point.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public function permission_callback() {
		return true;
	}

	/**
	 * Query parameters.
	 *
	 * Get query param. Regex with query parameters.
	 *
	 * Example:
	 * (?P<id>[\d]+)/(?P<meta_key>[\w-]+)
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '';
	}

	/**
	 * Arguments.
	 *
	 * Returns arguments config.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_args() {
		return [];
	}

}
