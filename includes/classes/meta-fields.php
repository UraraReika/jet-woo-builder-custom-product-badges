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
	 * Product meta.
	 *
	 * Add custom meta fields for products where you can select specific badges.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_product_meta() {
		new \Cherry_X_Post_Meta( [
			'id'            => 'jet-woo-builder-badge-settings',
			'title'         => __( 'Custom Product Badges', 'jwb-custom-product-badges' ),
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
					'options'     => Plugin::instance()->tools->get_badges_list(),
				],
			],
		] );
	}

	/**
	 * Builder.
	 *
	 * Return UI builder instance.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return CX_Interface_Builder
	 */
	public function get_builder() {

		$builder_data = Plugin::instance()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

		return new \CX_Interface_Builder( [
			'path' => $builder_data['path'],
			'url'  => $builder_data['url'],
		] );

	}

}

