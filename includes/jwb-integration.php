<?php

namespace JWB_CPB;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class JWB_Integration {

	public function __construct() {

		// Register Elementor controls.
		add_action( 'elementor/element/jet-woo-products/section_general/after_section_end', [ $this, 'register_custom_badge_controls' ] );
		add_action( 'elementor/element/jet-woo-builder-archive-sale-badge/section_badge_content/after_section_end', [ $this, 'register_custom_badge_controls' ] );

		// Handle custom badge output.
		add_filter( 'jet-woo-builder/template-functions/product_sale_flash/injection', [ $this, 'enable_custom_badge_injection' ], 10, 2 );
		add_filter( 'jet-woo-builder/template-functions/product_sale_flash', [ $this, 'get_custom_products_badges' ], 10, 2 );

		// Extend macros settings list.
		add_filter( 'jet-woo-builder/jet-woo-builder-archive-sale-badge/macros-settings', [ $this, 'extend_archive_item_macros_settings' ], 10, 2 );

		// Extend JetSmartFilters providers settings list.
		add_filter( 'jet-smart-filters/providers/jet-woo-products-grid/settings-list', [ $this, 'extend_providers_settings' ] );


	}

	/**
	 * Register controls.
	 *
	 * Register separate style controls for each
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param object $obj Widget instance.
	 */
	public function register_custom_badge_controls( $obj ) {

		$badges = Plugin::instance()->tools->get_predefined_badges_list();

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
					'enable_custom_badges!' => '',
				],
			]
		);

		foreach ( $badges as $key => $value ) {
			$key = strtolower( str_replace( ' ', '-', $key ) );

			$obj->add_control(
				$key . '_hr',
				[
					'type'      => Controls_Manager::DIVIDER,
					'condition' => [
						'enable_custom_badges!' => '',
					],
				]
			);

			$obj->add_control(
				$key . '_styles_heading',
				[
					'label'     => __( $value, 'jet-woo-builder' ),
					'type'      => Controls_Manager::HEADING,
					'condition' => [
						'enable_custom_badges!' => '',
					],
				]
			);

			$obj->add_control(
				$key . '_badge_color',
				[
					'label'     => __( 'Color', 'jet-woo-builder' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} div.jet-woo-product-badge.jet-woo-product-badge__' . $key => 'color: {{VALUE}};',
					],
					'condition' => [
						'enable_custom_badges!' => '',
					],
				]
			);

			$obj->add_control(
				$key . '_badge_bg_color',
				[
					'label'     => __( 'Background color', 'jet-woo-builder' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} div.jet-woo-product-badge.jet-woo-product-badge__' . $key => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'enable_custom_badges!' => '',
					],
				]
			);
		}

		$obj->end_controls_section();

	}

	/**
	 * Enable injections.
	 *
	 * Returns custom badge injections status.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param bool  $status   Injections status.
	 * @param array $settings Widget settings list.
	 *
	 * @return bool
	 */
	public function enable_custom_badge_injection( $status, $settings ) {

		if ( isset( $settings['enable_custom_badges'] ) && filter_var( $settings['enable_custom_badges'], FILTER_VALIDATE_BOOLEAN ) ) {
			return true;
		}

		return $status;

	}

	/**
	 * Custom product badges.
	 *
	 * Add custom badges to products flash badges HTML.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $html     Badges element markup.
	 * @param array  $settings Widget settings list.
	 *
	 * @return mixed|string
	 */
	public function get_custom_products_badges( $html, $settings ) {

		global $product;

		$custom_badges = isset( $settings['enable_custom_badges'] ) ? filter_var( $settings['enable_custom_badges'], FILTER_VALIDATE_BOOLEAN ) : false;
		$default_badge = isset( $settings['enable_default_badge'] ) ? filter_var( $settings['enable_default_badge'], FILTER_VALIDATE_BOOLEAN ) : false;

		if ( ! $custom_badges ) {
			return $html;
		}

		if ( ! $default_badge || ! $product->is_on_sale() ) {
			$html = '';
		}

		$badges = get_post_field( '_jet_woo_builder_badges', $product->get_id() );

		if ( $badges ) {
			foreach ( $badges as $badge ) {
				$html .= sprintf(
					'<div class="jet-woo-product-badge jet-woo-product-badge__%s">%s</div>',
					strtolower( str_replace( ' ', '-', $badge ) ),
					$badge
				);
			}
		}

		return $html;

	}

	/**
	 * Extend macros settings.
	 *
	 * Returns updated macros settings to widget.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $list     Macros settings list.
	 * @param array $settings Widget settings list.
	 *
	 * @return array
	 */
	public function extend_archive_item_macros_settings( $list, $settings ) {

		$list['enable_custom_badges'] = isset( $settings['enable_custom_badges'] ) ? $settings['enable_custom_badges'] : '';
		$list['enable_default_badge'] = isset( $settings['enable_default_badge'] ) ? $settings['enable_default_badge'] : '';

		return $list;

	}

	/**
	 * Extend provider settings.
	 *
	 * Returns extend settings list for proper JetSmartFilters provider.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $list Provider settings list.
	 *
	 * @return array
	 */
	public function extend_providers_settings( $list ) {

		$custom_icon_settings = [
			'enable_custom_badges',
			'enable_default_badge',
		];

		return array_merge( $list, $custom_icon_settings );

	}

}
