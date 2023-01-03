<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Badges_List extends Base {

	public function get_name() {
		return 'badges-list';
	}

	public function get_method() {
		return 'GET';
	}

	public function callback( $request ) {

		$badges = \JWB_CPB\Plugin::instance()->tools->get_badges_list();

		return rest_ensure_response( [
			'status'  => 'success',
			'data' => $badges
		] );

	}

}