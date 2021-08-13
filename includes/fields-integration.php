<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Fields_Integration' ) ) {

	/**
	 * Define Jet_Woo_Builder_Fields_Integration
	 */
	class Jet_Woo_Builder_Fields_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'init', [ $this, 'add_product_meta' ], 99 );
		}

		/**
		 * Initialize template meta fields
		 *
		 * @return void
		 */
		public function add_product_meta() {

			new Cherry_X_Post_Meta( [
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
						'options'     => [
							'youtube'     => __( 'Youtube', 'jet-woo-builder' ),
							'vimeo'       => __( 'Vimeo', 'jet-woo-builder' ),
							'self_hosted' => __( 'Self Hosted', 'jet-woo-builder' ),
						],
						'label'       => __( 'Select Product Budgets:', 'jet-woo-builder' ),
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

			$builder_data = jet_woo_product_gallery()->module_loader->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				[
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				]
			);

		}

		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @since  1.0.0
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

	}

}

if ( ! function_exists( 'jet_woo_builder_fields_integration' ) ) {

	/**
	 * Returns instance of Jet_Woo_Builder_Fields_Integration
	 *
	 * @return object
	 */
	function jet_woo_builder_fields_integration() {
		return Jet_Woo_Builder_Fields_Integration::get_instance();
	}

}

