<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * JWB Custom Product Budges plugin.
 *
 * The main plugin handler class is responsible for initializing JWB Custom Product Budges. The class register all the
 * components required to run the plugin.
 *
 * @since 1.1.0
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Framework.
	 *
	 * Holds the plugin framework instance, responsible for loading framework modules.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @var \JWB_Custom_Product_Badges_CX_Loader
	 */
	public $framework;

	/**
	 * Meta fields.
	 *
	 * Holds meta fields instance, responsible for handling products meta fields and meta data.
	 *
	 * @var Meta_Fields
	 */
	public $meta_fields;

	/**
	 * Integration.
	 *
	 * Holds integration instance, responsible for widgets integration.
	 *
	 * @var Integration
	 */
	public $integration;

	/**
	 * Plugin constructor.
	 *
	 * Initializing JWB Custom Product Budges plugin.
	 *
	 * @since  1.1.0
	 * @access private
	 */
	private function __construct() {

		add_action( 'after_setup_theme', [ $this, 'framework_loader' ], -20 );

		add_action( 'init', [ $this, 'init' ], -999 );

	}

	/**
	 * Framework loader.
	 *
	 * Load JWB Custom Product Badges framework components.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function framework_loader() {

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/loader.php';

		$this->framework = new \JWB_Custom_Product_Badges_CX_Loader(
			[
				JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/interface-builder/cherry-x-interface-builder.php',
				JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/post-meta/cherry-x-post-meta.php',
			]
		);

	}

	public function init() {

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/meta-fields.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/integration.php';

		$this->init_components();

	}

	public function init_components() {

		$this->meta_fields = new Meta_Fields();
		$this->integration = new Integration();

	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

}

Plugin::instance();