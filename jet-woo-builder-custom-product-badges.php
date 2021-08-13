<?php
/**
 * Plugin Name: JetWooBuilder - Custom Product Badges
 * Plugin URI:
 * Description:
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * Text Domain: jet-woo-builder
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Woo_Builder` doesn't exists yet.
if ( ! class_exists( 'Jet_Woo_Builder_Custom_Product_Badges' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Woo_Builder_Custom_Product_Badges {

		/**
		 * A reference to an instance of this class.
		 *
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin properties
		 */
		public $module_loader;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @return void
		 * @access public
		 */
		public function __construct() {

			define( 'CPB_PLUGIN_URL', __FILE__ );

			// Load the core functions/classes required by the rest of the plugin.
			add_action( 'after_setup_theme', [ $this, 'module_loader' ], -20 );

			// Load files.
			add_action( 'init', [ $this, 'init' ], -999 );

		}

		/**
		 * Load plugin framework
		 */
		public function module_loader() {

			require $this->plugin_path( 'includes/modules/loader.php' );

			$this->module_loader = new Jet_Woo_Builder_Custom_Product_Badges_CX_Loader(
				[
					plugins_url( 'includes/modules/interface-builder/cherry-x-interface-builder.php', CPB_PLUGIN_URL ),
					plugins_url( 'includes/modules/post-meta/cherry-x-post-meta.php', CPB_PLUGIN_URL ),
				]
			);

		}

		/**
		 * Manually init required modules
		 */
		public function init() {
			require $this->plugin_path( 'includes/fields-integration.php' );

			jet_woo_builder_fields_integration()->init();
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param string $path Path inside plugin dir.
		 *
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;

		}

		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @access public
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

if ( ! function_exists( 'jet_woo_builder_cpb' ) ) {

	/**
	 * Returns instance of the plugin class.
	 *
	 * @return object
	 */
	function jet_woo_builder_cpb() {
		return Jet_Woo_Builder_Custom_Product_Badges::get_instance();
	}

}

jet_woo_builder_cpb();
