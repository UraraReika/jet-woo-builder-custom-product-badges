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
	 * Holds integration instance, responsible for JetWooBuilder widgets integration.
	 *
	 * @var Widgets_Integration
	 */
	public $widgets_integration;

	/**
	 * Tools.
	 *
	 * Holds tools class instance.
	 *
	 * @var Tools
	 */
	public $tools;

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

		add_action( 'plugins_loaded', [ $this, 'required_plugins_loaded' ] );

		add_action( 'init', [ $this, 'init' ], -999 );

		register_activation_hook( JWB_CUSTOM_PRODUCT_BUDGES__FILE__, [ $this, 'activation' ] );
		register_deactivation_hook( JWB_CUSTOM_PRODUCT_BUDGES__FILE__, [ $this, 'deactivation' ] );

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

		$this->framework = new \JWB_Custom_Product_Badges_CX_Loader( [
			JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/interface-builder/cherry-x-interface-builder.php',
			JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/post-meta/cherry-x-post-meta.php',
			JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/db-updater/cherry-x-db-updater.php',
			JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'framework/jet-dashboard/jet-dashboard.php',
		] );

	}

	/**
	 * Required plugins loaded.
	 *
	 * Check if required plugins installed and activated.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function required_plugins_loaded() {
		if ( ! function_exists( 'jet_woo_builder' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_jet_woo_builder_plugin' ] );

			return;
		}
	}

	/**
	 * Admin notice.
	 *
	 * Show missing JetWooBuilder plugin notice.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function admin_notice_missing_jet_woo_builder_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			__( '"%s" requires "%s" to be installed and activated.', 'jwb-custom-product-badges' ),
			'<strong>' . __( 'JetWooBuilder - Custom Products Badges', 'jwb-custom-product-badges' ) . '</strong>',
			'<strong>' . __( 'JetWooBuilder', 'jwb-custom-product-badges' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );

	}

	/**
	 * Init.
	 *
	 * Initialize JWB Custom Product Badges Plugin. Initialize JWB Custom Product Badges components.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function init() {
		$this->lang();
		$this->init_components();
		$this->init_dashboard();
	}

	/**
	 * Lang.
	 *
	 * Loads the translation files.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function lang() {
		load_plugin_textdomain( 'jwb-custom-product-badges', false, JWB_CUSTOM_PRODUCT_BUDGES_PATH . '/languages' );
	}

	/**
	 * Init components.
	 *
	 * Initialize JWB Custom Product Badges components. Initialize all the components that run
	 * JWB Custom Product Badges.
	 *
	 * @since  1.1.0
	 * @access private
	 */
	private function init_components() {

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/settings/manager.php';

		if ( is_admin() ) {
			new Settings();

			// Init DB upgrader.
			require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/db-upgrader.php';

			DB_Upgrader::instance()->init();
		}

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/meta-fields.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/widgets-integration.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/tools.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/hooks-handler.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/classes/assets.php';

		$this->meta_fields         = new Meta_Fields();
		$this->widgets_integration = new Widgets_Integration();
		$this->tools               = new Tools();

		new Hooks_Handler();
		new Assets();

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/rest-api.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/endpoints/base.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/endpoints/plugin-settings.php';

		new Rest_Api();

	}

	/**
	 * Init dashboard.
	 *
	 * Initialize JWB Custom Product Badges dashboard.
	 *
	 * @since  1.1.0
	 * @access private
	 */
	private function init_dashboard() {
		if ( is_admin() ) {
			$dashboard_framework_data = $this->framework->get_included_module_data( 'jet-dashboard.php' );
			$dashboard                = \Jet_Dashboard\Dashboard::get_instance();

			$dashboard->init(
				[
					'path'           => $dashboard_framework_data['path'],
					'url'            => $dashboard_framework_data['url'],
					'cx_ui_instance' => [ $this, 'dashboard_ui_instance' ],
					'plugin_data'    => [
						'slug'         => 'jwb-custom-product-badges',
						'file'         => 'jet-woo-builder-custom-product-badges/jet-woo-builder-custom-product-badges.php',
						'version'      => JWB_CUSTOM_PRODUCT_BUDGES_VERSION,
						'plugin_links' => [
							[
								'label'  => __( 'Go to settings', 'jwb-custom-product-badges' ),
								'url'    => add_query_arg(
									[
										'page'    => 'jet-dashboard-settings-page',
										'subpage' => 'jwb-custom-product-badges-general-settings',
									],
									admin_url( 'admin.php' )
								),
								'target' => '_self',
							],
						],
					],
				]
			);
		}
	}

	/**
	 * Dashboard UI instance.
	 *
	 * Get Vue UI Instance for JetDashboard module.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return object
	 */
	public function dashboard_ui_instance() {

		$vue_ui_data = $this->framework->get_included_module_data( 'cherry-x-vue-ui.php' );

		return new CX_Vue_UI( $vue_ui_data );

	}

	/**
	 * Activation.
	 *
	 * Do some stuff on plugin activation.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function activation() {

		$this->init_components();

		$predefined_badges = $this->tools->get_predefined_badges_list();
		$settings          = get_option( Settings::get_instance()->key, [] );

		if ( ! $settings ) {
			$badges = [];

			foreach ( $predefined_badges as $key => $value ) {
				$badges[] = [
					'value' => $key,
					'label' => $value,
				];
			}

			$settings['badgesList'] = $badges;

			add_option( Settings::get_instance()->key, $settings );
		}

	}

	/**
	 * Deactivation.
	 *
	 * Do some stuff on plugin activation.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function deactivation() {
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