<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Settings {

	/**
	 * Instance.
	 *
	 * A reference to an instance of this class.
	 *
	 * @since  1.1.0
	 * @access public
	 * @static
	 *
	 * @var   object
	 */
	public static $instance = null;

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

	/**
	 * Key
	 *
	 * Contains settings key.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $key = 'jwb-custom-product-badges-settings';

	public function __construct() {

		$this->subpage_modules = [
			'jwb-custom-product-badges-general' => [
				'class' => '\\JWB_CPB\\Settings\\General',
				'args'  => [],
			],
		];

		add_action( 'init', [ $this, 'init_settings_subpages' ], 10 );

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

	/**
	 * Get instance.
	 *
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @static
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

}