<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Fields_Handling' ) ) {

	/**
	 * Define Jet_Woo_Builder_Fields_Handling
	 */
	class Jet_Woo_Builder_Fields_Handling {

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
			add_filter( 'jet-woo-builder/template-functions/product_sale_flash', [ $this, 'get_custom_products_badges' ] );
		}

		/**
		 * Add custom budges to products flash badges.
		 *
		 * @param $html
		 *
		 * @return mixed|string
		 */
		public function get_custom_products_badges( $html ) {

			global $product;

			$badges = get_post_field( '_jet_woo_builder_badges', $product->get_id() );

			if ( $badges ) {
				foreach ( $badges as $badge ) {
					$html .= sprintf( '<div class="jet-woo-product-badge jet-woo-product-badge__%s">%s</div>', $badge, ucfirst( $badge ) );
				}
			}

			return $html;

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

if ( ! function_exists( 'jet_woo_builder_fields_handling' ) ) {

	/**
	 * Returns instance of Jet_Woo_Builder_Fields_Handling
	 *
	 * @return object
	 */
	function jet_woo_builder_fields_handling() {
		return Jet_Woo_Builder_Fields_Handling::get_instance();
	}

}
