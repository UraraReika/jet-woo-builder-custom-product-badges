<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	abstract function get_name();

	/**
	 * API callback
	 *
	 * @param $request
	 *
	 * @return void
	 */
	abstract function callback( $request );

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELETE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Check user access to current end-point
	 *
	 * @return bool
	 */
	public function permission_callback() {
		return true;
	}

	/**
	 * Get query param. Regex with query parameters
	 *
	 * Example:
	 *
	 * (?P<id>[\d]+)/(?P<meta_key>[\w-]+)
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '';
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return [];
	}

}
