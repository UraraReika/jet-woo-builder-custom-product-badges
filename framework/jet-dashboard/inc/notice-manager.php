<?php
namespace Jet_Dashboard;

use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Notice_Manager Class
 */
class Notice_Manager {

	/**
	 * [$registered_notices description]
	 * @var array
	 */
	public $registered_notices = array();

	/**
	 * [get_registered_plugins description]
	 * @return [type] [description]
	 */
	public function get_registered_notices( $page_slug = false ) {

		if ( ! $page_slug ) {
			return $this->registered_notices;
		}

		$page_notices = array_filter( $this->registered_notices, function( $notice ) {
			return $page_slug === $notice['page'];
		} );

		if ( ! empty( $page_notices ) ) {
			return $page_notices;
		}

		return false;
	}

	/**
	 * [get_registered_plugins description]
	 * @return [type] [description]
	 */
	public function register_notice( $notice_args = array() ) {

		/*Dashboard::get_instance()->notice_manager->register_notice( array(
			'id'      => 'alert-notice-1',
			'page'    => 'welcome-page',
			'preset'  => 'alert',
			'type'    => 'info',
			'title'   => 'Info',
			'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
			'buttons' => array(
			),
		) );*/

		$defaults = array(
			'id'       => false,
			'page'     => false,
			'preset'   => 'alert', // alert, notice
			'type'     => 'info', // info, success, danger, error
			'duration' => false,
			'icon'     => false,
			'title'    => '',
			'message'  => '',
			'buttons'  => array(),
		);

		$notice_args = wp_parse_args( $notice_args, $defaults );

		if ( ! $notice_args['id'] || ! $notice_args['page'] || empty( $notice_args['message'] ) ) {
			return false;
		}

		$this->registered_notices[] = $notice_args;
	}

}
