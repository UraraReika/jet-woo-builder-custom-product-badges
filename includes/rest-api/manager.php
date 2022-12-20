<?php

namespace JWB_CPB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Rest_Api {

	/**
	 * API namespace.
	 *
	 * Contains namespace.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $api_namespace = 'jwb-custom-product-badges-api/v1';

	/**
	 * Endpoints.
	 *
	 * Contains plugins endpoints.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @var null
	 */
	private $_endpoints = null;

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Init endpoints.
	 *
	 * Initialize all JWB Custom Product Badges related Rest API endpoints.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function init_endpoints() {

		$this->_endpoints = [];

		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/endpoints/base.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/endpoints/add-badges.php';
		require JWB_CUSTOM_PRODUCT_BUDGES_PATH . 'includes/rest-api/endpoints/delete-badges.php';

		$this->register_endpoint( new Endpoints\Add_Badges() );
		$this->register_endpoint( new Endpoints\Delete_Badges() );

		do_action( 'jwb-custom-product-badges/rest/init-endpoints', $this );

	}

	/**
	 * Register endpoint.
	 *
	 * Register new endpoint.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param object $endpoint_instance Endpoint instance
	 *
	 * @return void
	 */
	public function register_endpoint( $endpoint_instance = null ) {
		if ( $endpoint_instance ) {
			$this->_endpoints[ $endpoint_instance->get_name() ] = $endpoint_instance;
		}
	}

	/**
	 * Get endpoints.
	 *
	 * Returns all registered API endpoints.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return null
	 */
	public function get_endpoints() {

		if ( null === $this->_endpoints ) {
			$this->init_endpoints();
		}

		return $this->_endpoints;

	}

	/**
	 * Get endpoints url.
	 *
	 * Returns endpoints URLs list.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_endpoints_urls() {

		$result    = [];
		$endpoints = $this->get_endpoints();

		foreach ( $endpoints as $endpoint ) {
			$key            = str_replace( '-', '', ucwords( $endpoint->get_name(), '-' ) );
			$result[ $key ] = get_rest_url( null, $this->api_namespace . '/' . $endpoint->get_name() . '/' . $endpoint->get_query_params(), 'rest' );
		}

		return $result;

	}

	/**
	 * Get routes.
	 *
	 * Returns route to passed endpoint.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $endpoint Endpoint namespace.
	 * @param bool   $full     Path type.
	 *
	 * @return string
	 */
	public function get_route( $endpoint = '', $full = false ) {

		$path = $this->api_namespace . '/' . $endpoint . '/';

		if ( ! $full ) {
			return $path;
		} else {
			return get_rest_url( null, $path );
		}

	}

	/**
	 * Register routes.
	 *
	 * Register all detected routes.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function register_routes() {

		$endpoints = $this->get_endpoints();

		foreach ( $endpoints as $endpoint ) {
			$args = [
				'methods'             => $endpoint->get_method(),
				'callback'            => [ $endpoint, 'callback' ],
				'permission_callback' => [ $endpoint, 'permission_callback' ],
			];

			$endpoint_args = $endpoint->get_args();

			if ( ! empty( $endpoint_args ) ) {
				$args['args'] = $endpoint->get_args();
			}

			$route = '/' . $endpoint->get_name() . '/' . $endpoint->get_query_params();

			register_rest_route( $this->api_namespace, $route, $args );
		}

	}

	/**
	 * Get urls.
	 *
	 * Returns all registered Rest API URLs.
	 *
	 * @since  1.1.1
	 * @access public
	 *
	 * @param bool $full Url type.
	 *
	 * @return array
	 */
	public function get_urls( $full = true ) {
		return [
			'add_badges'    => $this->get_route( 'add-badges', $full ),
			'delete_badges' => $this->get_route( 'delete-badges', $full ),
		];
	}

}

