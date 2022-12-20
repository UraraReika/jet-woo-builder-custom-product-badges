<?php

namespace JWB_CPB\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Delete_Badges extends Base {

	public function get_name() {
		return 'delete-badges';
	}

	public function get_method() {
		return 'POST';
	}

	public function callback( $request ) {

		parent::callback( $request );

		$badges = \JWB_CPB\Plugin::instance()->tools->get_badges_list();
		$posts  = get_posts( [
			'post_type'   => 'product',
			'numberposts' => -1,
		] );

		foreach ( $posts as $post ) {
			$badge_meta = get_post_meta( $post->ID, '_jet_woo_builder_badges', true );

			if ( empty( $badge_meta ) ) {
				continue;
			}

			$new_badge_meta = [];

			foreach ( $badges as $key => $value ) {
				if ( in_array( $key, $badge_meta ) ) {
					$new_badge_meta[] = $key;
				}
			}

			update_post_meta( $post->ID, '_jet_woo_builder_badges', $new_badge_meta );
		}

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Badges have been deleted.', 'jwb-custom-product-badges' ),
		] );

	}

}