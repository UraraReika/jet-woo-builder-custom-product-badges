<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Add_Badges extends Base {

	public function get_name() {
		return 'add-badges';
	}

	public function get_method() {
		return 'POST';
	}

	public function callback( $request ) {

		parent::callback( $request );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Badges have been saved.', 'jwb-custom-product-badges' ),
		] );

	}

}