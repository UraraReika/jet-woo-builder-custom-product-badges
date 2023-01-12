<?php

namespace JWB_CPB\Endpoints;

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

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been updated.', 'jwb-custom-product-badges' ),
		] );


	}

}