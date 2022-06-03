<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DB_Upgrader {

	public function __construct() {

		$db_updater_data = jet_woo_builder()->module_loader->get_included_module_data( 'cherry-x-db-updater.php' );

		new \CX_Db_Updater(
			[
				'path'      => $db_updater_data['path'],
				'url'       => $db_updater_data['url'],
				'slug'      => 'jwb-custom-product-badges',
				'version'   => '1.1.0',
				'callbacks' => [
					'1.1.0' => [
						[ $this, 'update_db_1_1_0' ],
					],
				],
			]
		);

	}

	/**
	 * Update.
	 *
	 * JWB Custom Product Badges 1.1.1 version update. Check if settings option exist and update it if needed.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function update_db_1_1_0() {

		$predefined_badges = Plugin::instance()->tools->get_predefined_badges_list();
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

			update_option( Settings::get_instance()->key, $settings );
		}

		$this->clear_elementor_cache();

	}

	/**
	 * Clear elementor cache.
	 *
	 * Clear elementor plugin editor cache.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function clear_elementor_cache() {
		if ( class_exists( 'Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

}
