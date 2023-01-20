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
