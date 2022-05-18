<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DB_Upgrader {

	/**
	 * Instance.
	 *
	 * A reference to an instance of this class.
	 *
	 * @since  1.1.0
	 * @access private
	 *
	 * @var    object
	 */
	private static $instance = null;

	public function init() {
		$this->init_upgrader();
	}

	/**
	 * Init upgrader.
	 *
	 * Initialize upgrader module.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function init_upgrader() {
		new \CX_Db_Updater(
			[
				'slug'      => 'jwb-custom-product-badges',
				'version'   => '1.5.0',
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

	}

	/**
	 * Instance.
	 *
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return object
	 */
	public static function instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

}
