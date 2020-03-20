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
 * The Realex API payment request class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_Payment_Request extends WC_Realex_Redirect_API_Request {


	/** @var \WC_Order order object */
	protected $order;


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function __construct( \WC_Order $order ) {

		$this->order = $order;
	}


	/**
	 * Sets the data for a credit card authorization.
	 *
	 * @since 2.0.0
	 */
	public function set_authorization_data() {

		$this->set_order_data( false );
	}


	/**
	 * Sets the data for a credit card charge.
	 *
	 * @since 2.0.0
	 */
	public function set_charge_data() {

		$this->set_order_data( true );
	}


	/**
	 * Sets the data for a credit card payment based on the order.
	 *
	 * @since 2.0.0
	 *
	 * @param bool $autosettle whether to auto-settle the transaction
	 */
	public function set_order_data( $autosettle = false ) {

		$this->request_type = 'receipt-in';

		if ( ! $autosettle && $this->get_order()->payment->multisettle ) {
			$autosettle = 'MULTI';
		} else {
			$autosettle = (int) $autosettle;
		}

		$this->request_data = array(
			'orderid'       => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_]*/', '', $this->get_order()->unique_transaction_ref ), 40, '' ),
			'amount'        => array(
				'@attributes' => array(
					'currency' => Framework\SV_WC_Order_Compatibility::get_prop( $this->get_order(), 'currency', 'view' ),
				),
				$this->get_order()->payment_total * 100,
			),
			'autosettle'    => array(
				'@attributes' => array(
					'flag' => $autosettle,
				),
			),
			'payerref'      => $this->get_order()->customer_id,
			'paymentmethod' => $this->get_order()->payment->token,
			'comments'      => array(
				'comment' => array(
					'@attributes' => array(
						'id' => 1,
					),
					Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', $this->get_order()->description ), 255 ),
				),
			),
		);
	}


	/**
	 * Gets the parameters used to generate the SHA-1 hash.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_hash_values() {

		return array(
			$this->request_data['orderid'],
			$this->request_data['amount'][0],
			$this->request_data['amount']['@attributes']['currency'],
			$this->request_data['payerref'],
		);
	}


	/**
	 * Gets the order object associated with this request.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Order
	 */
	protected function get_order() {

		return $this->order;
	}


}
