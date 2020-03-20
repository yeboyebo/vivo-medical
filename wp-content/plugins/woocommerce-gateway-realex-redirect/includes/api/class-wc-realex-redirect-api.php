<?php
/**
 * WooCommerce Realex Redirect
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Realex Redirect to newer
 * versions in the future. If you wish to customize WooCommerce Realex Redirect for your
 * needs please refer to http://docs.woocommerce.com/document/realex-redirec-payment-gateway/ for more information.
 *
 * @package     WC-Gateway-Realex-Redirect
 * @author      SkyVerge
 * @copyright   Copyright (c) 2012-2018, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_3_0 as Framework;

/**
 * The Realex API base class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API extends Framework\SV_WC_API_Base implements Framework\SV_WC_Payment_Gateway_API {


	/** production endpoint URL */
	const ENDPOINT_URL = 'https://api.realexpayments.com/epage-remote.cgi';

	/** test endpoint URL */
	const ENDPOINT_URL_TEST = 'https://api.sandbox.realexpayments.com/epage-remote.cgi';

	/** payment request type */
	const REQUEST_TYPE_PAYMENT = 'payment';

	/** transaction management request type */
	const REQUEST_TYPE_TRANSACTION = 'transaction';

	/** stored card request type */
	const REQUEST_TYPE_STORED_CARD = 'stored-card';


	/** @var string Realex merchant account ID */
	protected $merchant_id;

	/** @var string Realex merchant account ID */
	protected $subaccount;

	/** @var string Realex merchant account hash secret */
	protected $secret;

	/** @var \WC_Order order object */
	protected $order;


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param string $environment current environment
	 */
	public function __construct( $environment, $merchant_id, $subaccount, $secret ) {

		$this->request_uri = $environment === WC_Gateway_Realex_Redirect::ENVIRONMENT_TEST ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL;

		$this->merchant_id = $merchant_id;
		$this->subaccount  = $subaccount;
		$this->secret      = $secret;
	}


	/**
	 * Performs a credit card authorization for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Realex_Redirect_API_Payment_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function credit_card_authorization( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_PAYMENT,
		) );

		$request->set_authorization_data();

		return $this->perform_request( $request );
	}


	/**
	 * Performs a credit card charge for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Realex_Redirect_API_Payment_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function credit_card_charge( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_PAYMENT,
		) );

		$request->set_charge_data();

		return $this->perform_request( $request );
	}


	/**
	 * Performs a credit card capture for a given authorized order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Realex_Redirect_API_Transaction_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function credit_card_capture( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_TRANSACTION,
		) );

		$request->set_capture_data();

		return $this->perform_request( $request );
	}


	/**
	 * Performs a refund for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Realex_Redirect_API_Transaction_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function refund( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_TRANSACTION,
		) );

		$request->set_refund_data();

		return $this->perform_request( $request );
	}


	/**
	 * Performs a void for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Realex_Redirect_API_Transaction_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function void( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_TRANSACTION,
		) );

		$request->set_void_data();

		return $this->perform_request( $request );
	}


	/**
	 * Updates a payer profile.
	 *
	 * @since 2.0.0
	 *
	 * @param string $customer_id Realex payer ref
	 * @param array $address customer address
	 * @return \WC_Realex_Redirect_API_Token_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function update_payer( $customer_id, $address ) {

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_STORED_CARD,
		) );

		$request->set_update_payer_data( $customer_id, $address );

		return $this->perform_request( $request );
	}


	/**
	 * Removes a tokenized payment method.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token payment method token
	 * @param string $customer_id unique customer ID
	 * @return \WC_Realex_Redirect_API_Token_Response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	public function remove_tokenized_payment_method( $token, $customer_id ) {

		$request = $this->get_new_request( array(
			'type' => self::REQUEST_TYPE_STORED_CARD,
		) );

		$request->set_remove_data( $token, $customer_id );

		return $this->perform_request( $request );
	}


	/**
	 * Determines if this API supports a "remove tokenized payment method"
	 * request.
	 *
	 * Technically supported, but handled differently by \WC_Gateway_Realex_Redirect
	 * since it's a hosted gateway.
	 *
	 * @since 2.1.2
	 *
	 * @return bool
	 */
	public function supports_update_tokenized_payment_method() {

		return false;
	}


	/**
	 * Determines if this API supports a "remove tokenized payment method"
	 * request.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function supports_remove_tokenized_payment_method() {

		return true;
	}


	/**
	 * Gets a new request object.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args request arguments
	 * @return \WC_Realex_Redirect_API_Request
	 */
	protected function get_new_request( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'type' => '',
		) );

		switch ( $args['type'] ) {

			case self::REQUEST_TYPE_PAYMENT:

				$request = new WC_Realex_Redirect_API_Payment_Request( $this->get_order() );

				$response_handler = 'WC_Realex_Redirect_API_Payment_Response';

			break;

			case self::REQUEST_TYPE_TRANSACTION:

				$request = new WC_Realex_Redirect_API_Transaction_Request( $this->get_order() );

				$response_handler = 'WC_Realex_Redirect_API_Transaction_Response';

			break;

			case self::REQUEST_TYPE_STORED_CARD:

				$request = new WC_Realex_Redirect_API_Stored_Card_Request();

				$response_handler = 'WC_Realex_Redirect_API_Stored_Card_Response';

			break;

			default:
				throw new Framework\SV_WC_API_Exception( 'Invalid request type.' );
		}

		// set the universal authorization data
		$request->set_merchant_id( $this->get_merchant_id() );
		$request->set_subaccount( $this->get_subaccount() );
		$request->set_secret( $this->get_secret() );

		$this->set_response_handler( $response_handler );

		return $request;
	}


	/**
	 * Gets the merchant ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_merchant_id() {

		return $this->merchant_id;
	}


	/**
	 * Gets the subaccount ID to use.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_subaccount() {

		return $this->subaccount;
	}


	/**
	 * Gets the hash secret.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_secret() {

		return $this->secret;
	}


	/**
	 * Gets the order object associated with the request, if any.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Order
	 */
	public function get_order() {

		return $this->order;
	}


	/**
	 * Gets the plugin class instance associated with this API.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Realex_Redirect
	 */
	protected function get_plugin() {

		return wc_realex_redirect();
	}


	/** No-Op Methods *********************************************************/


	/**
	 * Performs an eCheck debit (ACH transaction) for the given order.
	 *
	 * No-op: Realex does not support eChecks.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return null
	 */
	public function check_debit( \WC_Order $order ) {}


	/**
	 * Creates a payment token for the given order.
	 *
	 * No-op: Realex Rediret does not support direct tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return null
	 */
	public function tokenize_payment_method( \WC_Order $order ) {}


	/**
	 * Updates a payment token for the given order.
	 *
	 * @see self::supports_update_tokenized_payment_method()
	 *
	 * @since 2.1.2
	 *
	 * @param \WC_Order $order order object
	 */
	public function update_tokenized_payment_method( \WC_Order $order ) {}


	/**
	 * Gets all tokenized payment methods for a customer.
	 *
	 * No-op: Realex does not support getting all tokens.
	 *
	 * @since 2.0.0
	 *
	 * @param string $customer_id unique customer ID
	 * @return null
	 */
	public function get_tokenized_payment_methods( $customer_id ) {}


	/**
	 * Determines if the API supports getting tokenized payment methods for a
	 * customer.
	 *
	 * No-op: Realex does not support getting all tokens.
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function supports_get_tokenized_payment_methods() {

		return false;
	}


}
