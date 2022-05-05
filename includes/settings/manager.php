<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Settings {

	/**
	 * Subpage modules.
	 *
	 * Contain modules subpages list.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @var array
	 */
	public $subpage_modules = [];

	public function __construct() {

		$this->subpage_modules = [
			'jwb-custom-product-badges-general-settings' => [
				'class' => '\\JWB_CPB\\Settings\\General',
				'args'  => [],
			],
		];

		add_action( 'init', [ $this, 'register_settings_category' ], 10 );
		add_action( 'init', [ $this, 'init_settings_subpages' ], 10 );

	}

	/**
	 * Register Category.
	 *
	 * Register settings page  main settings category, which holds all settings tabs.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function register_settings_category() {
		\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => __( 'Custom Product Badges', 'jet-woo-builder' ),
			'slug'     => 'jwb-custom-product-badges',
			'priority' => 2,
		) );
	}

	/**
	 * Init settings subpages.
	 *
	 * Initialize plugin settings subpages.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function init_settings_subpages() {

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/settings/subpages/general.php';

		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}

	}

}