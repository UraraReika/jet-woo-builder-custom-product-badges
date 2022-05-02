<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Meta_Fields {

	public function __construct() {
		add_action( 'init', [ $this, 'add_product_meta' ], 99 );
	}

	/**
	 * Initialize template meta fields
	 *
	 * @return void
	 */
	public function add_product_meta() {

		new \Cherry_X_Post_Meta( [
			'id'            => 'jet-woo-builder-badge-settings',
			'title'         => esc_html__( 'JetWooBuilder Badges', 'jet-woo-builder' ),
			'page'          => [ 'product' ],
			'context'       => 'side',
			'priority'      => 'low',
			'callback_args' => false,
			'builder_cb'    => [ $this, 'get_builder' ],
			'fields'        => [
				'_jet_woo_builder_badges' => [
					'type'        => 'select',
					'multiple'    => true,
					'value'       => '',
					'placeholder' => null,
					'element'     => 'control',
					'options'     => $this->get_custom_badges_list(),
					'label'       => __( 'Select Product Badges:', 'jet-woo-builder' ),
				],
			],
		] );

	}

	/**
	 * Return UI builder instance
	 *
	 * @return CX_Interface_Builder
	 */
	public function get_builder() {

		$builder_data = Plugin::instance()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

		return new \CX_Interface_Builder(
			[
				'path' => $builder_data['path'],
				'url'  => $builder_data['url'],
			]
		);

	}

	/**
	 * Returns list of badges
	 *
	 * @return mixed|void
	 */
	public function get_custom_badges_list() {

		return apply_filters( 'jet-woo-builder-cpb/integration/badges', [
			'popular'    => __( 'Popular', 'jet-woo-builder' ),
			'bestseller' => __( 'Bestseller', 'jet-woo-builder' ),
			'new'        => __( 'New', 'jet-woo-builder' ),
		] );

	}

}

