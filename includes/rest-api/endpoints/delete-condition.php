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

		parent::callback( $request );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Condition has been deleted.', 'jwb-custom-product-badges' ),
		] );


	}

}