<?php
/**
 * Plugin Name: JetWooBuilder - Custom Product Badges
 * Plugin URI: https://github.com/UraraReika/jet-woo-builder-custom-product-badges
 * Description: Custom Product Badges has it all: create and manage products badges, output in multiple widgets, style badges separately and more.
 * Version:     1.1.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * Text Domain: jwb-custom-product-badges
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'JWB_CUSTOM_PRODUCT_BUDGES_VERSION', '1.1.0' );

define( 'JWB_CUSTOM_PRODUCT_BUDGES__FILE__', __FILE__ );
define( 'JWB_CUSTOM_PRODUCT_BUDGES_PATH', plugin_dir_path( JWB_CUSTOM_PRODUCT_BUDGES__FILE__ ) );
define( 'JWB_CUSTOM_PRODUCT_BUDGES_URL', plugins_url( '/', JWB_CUSTOM_PRODUCT_BUDGES__FILE__ ) );

add_action( 'plugins_loaded', 'jwbcpb_plugin_init' );
add_action( 'plugins_loaded', 'jwbcpb_load_plugin_textdomain' );

/**
 * Plugin init.
 *
 * Plugin initialization with required plugins installed and activated check.
 *
 * @since  1.1.0
 *
 * @return void
 */
function jwbcpb_plugin_init() {
	if ( ! function_exists( 'jet_woo_builder' ) ) {
		add_action( 'admin_notices', 'jwbcpb_required_plugins_admin_notice' );
	} else {
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/plugin.php';
	}
}

/**
 * Required plugins admin notice.
 *
 * Show missing plugins admin notice.
 *
 * @since  1.1.0
 *
 * @return void
 */
function jwbcpb_required_plugins_admin_notice() {

	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	$jwb_link = sprintf(
		'<a href="%s">%s</a>',
		admin_url() . 'admin.php?page=jet-dashboard-license-page',
		'<strong>' . __( 'JetWooBuilder', 'jwb-custom-product-badges' ) . '</strong>'
	);

	$message = sprintf(
		__( '"%s" requires "%s" to be installed and activated.', 'jwb-custom-product-badges' ),
		'<strong>' . __( 'JetWooBuilder - Custom Products Badges', 'jwb-custom-product-badges' ) . '</strong>',
		$jwb_link
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );

}

/**
 * Load plugin text domain.
 *
 * Load gettext translate for JetWooBuilder - Custom Product Badges text domain.
 *
 * @since 1.1.0
 *
 * @return void
 */
function jwbcpb_load_plugin_textdomain() {
	load_plugin_textdomain( 'jwb-custom-product-badges', false, JWB_CUSTOM_PRODUCT_BUDGES_PATH . '/languages' );
}
