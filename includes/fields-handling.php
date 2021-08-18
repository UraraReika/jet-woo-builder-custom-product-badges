<?php

// If this file is called directly, abort.
use Elementor\Controls_Manager;

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

			// register controls for Products Grid widget
			add_action( 'elementor/element/jet-woo-products/section_general/after_section_end', [ $this, 'register_custom_badge_controls' ], 10, 2 );
			add_action( 'elementor/element/jet-woo-builder-archive-sale-badge/section_badge_content/after_section_end', [ $this, 'register_custom_badge_controls' ], 10, 2 );
			// handle badge output
			add_filter( 'jet-woo-builder/template-functions/product_sale_flash', [ $this, 'get_custom_products_badges' ], 10, 2 );
			// add custom badges settings to providers settings list
			add_filter( 'jet-smart-filters/providers/jet-woo-products-grid/settings-list', [ $this, 'add_custom_badges_settings_to_list' ] );
			// add custom badges settings to macros settings list
			add_filter( 'jet-woo-builder/jet-woo-builder-archive-sale-badge/macros-settings', [ $this, 'add_custom_badges_settings_to_macros_settings' ], 10, 2 );

		}

		/**
		 * Register custom Add to Cart icon controls
		 *
		 * @param $obj
		 */
		public function register_custom_badge_controls( $obj ) {

			$badges = jet_woo_builder_fields_integration()->get_custom_badges_list();

			$obj->start_controls_section(
				'section_custom_badges',
				[
					'label' => __( 'Custom Badges', 'jet-woo-builder' ),
				]
			);

			$obj->add_control(
				'enable_custom_badges',
				[
					'label' => __( 'Enable Custom Badges', 'jet-woo-builder' ),
					'type'  => Controls_Manager::SWITCHER,
				]
			);

			$obj->add_control(
				'enable_default_badge',
				[
					'label'     => __( 'Enable Default Badge', 'jet-woo-builder' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'condition' => [
						'enable_custom_badges' => 'yes',
					],
				]
			);

			foreach ( $badges as $key => $value ) {
				$obj->add_control(
					$key . '_hr',
					[
						'type'      => Controls_Manager::DIVIDER,
						'condition' => [
							'enable_custom_badges' => 'yes',
						],
					]
				);

				$obj->add_control(
					$key . '_styles_heading',
					[
						'label'     => __( $value, 'jet-woo-builder' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => [
							'enable_custom_badges' => 'yes',
						],
					]
				);

				$obj->add_control(
					$key . '_badge_color',
					[
						'label'     => __( 'Color', 'jet-woo-builder' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jet-woo-product-badge.jet-woo-product-badge__' . $key => 'color: {{VALUE}};',
						],
						'condition' => [
							'enable_custom_badges' => 'yes',
						],
					]
				);

				$obj->add_control(
					$key . '_badge_bg_color',
					[
						'label'     => __( 'Background color', 'jet-woo-builder' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jet-woo-product-badge.jet-woo-product-badge__' . $key => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'enable_custom_badges' => 'yes',
						],
					]
				);
			}

			$obj->end_controls_section();

		}

		/**
		 * Add custom budges to products flash badges.
		 *
		 * @param $html
		 *
		 * @return mixed|string
		 */
		public function get_custom_products_badges( $html, $settings ) {

			if ( 'yes' !== $settings['enable_custom_badges'] ) {
				return $html;
			}

			if ( 'yes' !== $settings['enable_default_badge'] ) {
				$html = '';
			}

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
		 * Returns merged custom icon settings with JetSmartFilters providers settings list
		 *
		 * @param $list
		 *
		 * @return array
		 */
		public function add_custom_badges_settings_to_list( $list ) {

			$custom_icon_settings = [
				'enable_custom_badges',
				'enable_default_badge',
			];

			return array_merge( $list, $custom_icon_settings );

		}

		/**
		 * Returns updated macros settings to widget.
		 *
		 * @param $list
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function add_custom_badges_settings_to_macros_settings( $list, $settings ) {
			$list['enable_custom_badges'] = isset( $settings['enable_custom_badges'] ) ? $settings['enable_custom_badges'] : '';
			$list['enable_default_badge'] = isset( $settings['enable_default_badge'] ) ? $settings['enable_default_badge'] : '';

			return $list;
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
