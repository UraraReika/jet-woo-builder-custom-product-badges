<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Plugin_Settings extends Base {

	/**
	 * Returns query method
	 *
	 * @return string
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'plugin-settings';
	}

	/**
	 * Returns plugin settings callback
	 *
	 * @param  $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function callback( $request ) {

		$data    = $request->get_params();
		$current = get_option( \JWB_CPB\Settings::get_instance()->key, [] );

		do_action( 'jwb-custom-product-badges/rest-api/endpoints/plugin-settings/before-settings-update', $current );

		if ( is_wp_error( $current ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error', 'jwb-custom-product-badges' ),
			] );
		}

		foreach ( $data as $key => $value ) {
			$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
		}

		update_option( \JWB_CPB\Settings::get_instance()->key, $current );

		do_action( 'jwb-custom-product-badges/rest-api/endpoints/plugin-settings/after-settings-update', $current );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Settings have been saved', 'jwb-custom-product-badges' ),
		] );

	}

}
