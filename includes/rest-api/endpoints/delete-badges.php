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

		$settings = get_option( \JWB_CPB\Settings::get_instance()->key, [] );
		$posts    = get_posts( [
			'post_type'   => 'product',
			'numberposts' => -1,
		] );

		$reload = false;

		if ( isset( $settings['displayConditions'] ) ) {
			foreach ( $settings['displayConditions'] as $key => $value ) {
				$condition_badges = $value['badges'];

				if ( empty( $condition_badges ) ) {
					continue;
				}

				$new_condition_badges = $this->get_available_badges( $condition_badges );

				if ( count( $condition_badges ) === count( $new_condition_badges ) ) {
					continue;
				}

				$reload = true;

				$settings['displayConditions'][ $key ]['badges'] = $new_condition_badges;
			}

			update_option( \JWB_CPB\Settings::get_instance()->key, $settings );
		}

		foreach ( $posts as $post ) {
			$badge_meta = get_post_meta( $post->ID, '_jet_woo_builder_badges', true );

			if ( empty( $badge_meta ) ) {
				continue;
			}

			$new_badge_meta = $this->get_available_badges( $badge_meta );

			update_post_meta( $post->ID, '_jet_woo_builder_badges', $new_badge_meta );
		}

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Badges have been deleted.', 'jwb-custom-product-badges' ),
			'reload'  => $reload,
		] );

	}

	/**
	 * Get available badges.
	 *
	 * Returns list of currently available badges.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @param array $badges_list Badges list from conditions of posts.
	 *
	 * @return array
	 */
	public function get_available_badges( $badges_list ) {

		$badges          = \JWB_CPB\Plugin::instance()->tools->get_badges_list();
		$new_badges_list = [];

		foreach ( $badges as $key => $value ) {
			if ( in_array( $key, $badges_list ) ) {
				$new_badges_list[] = $key;
			}
		}

		return $new_badges_list;

	}

}