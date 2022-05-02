<?php
/**
 * Plugin Name: JetWooBuilder - Custom Product Badges
 * Plugin URI: https://github.com/UraraReika/jet-woo-builder-custom-product-badges
 * Description: Custom Product Badges has it all: create and manage products badges, output in multiple widgets, style badges separately and more.
 * Version:     1.0.0
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

define( 'JWB_CUSTOM_PRODUCT_BUDGES_VERSION', '1.0.0' );

define( 'JWB_CUSTOM_PRODUCT_BUDGES__FILE__', __FILE__ );
define( 'JWB_CUSTOM_PRODUCT_BUDGES_PLUGIN_BASE', plugin_basename( JWB_CUSTOM_PRODUCT_BUDGES__FILE__ ) );
define( 'JWB_CUSTOM_PRODUCT_BUDGES_PATH', plugin_dir_path( JWB_CUSTOM_PRODUCT_BUDGES__FILE__ ) );
define( 'JWB_CUSTOM_PRODUCT_BUDGES_URL', plugins_url( '/', JWB_CUSTOM_PRODUCT_BUDGES__FILE__ ) );

require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/plugin.php';
