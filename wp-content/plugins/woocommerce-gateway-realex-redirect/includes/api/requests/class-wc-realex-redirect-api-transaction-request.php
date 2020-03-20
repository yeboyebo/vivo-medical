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
 * The Realex API transaction management request class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_Transaction_Request extends WC_Realex_Redirect_API_Request {


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
	public function set_capture_data() {

		$this->request_data = array(
			'orderid'  => $this->get_order()->capture->realex_id,
			'pasref'   => $this->get_order()->capture->trans_id,
			'authcode' => $this->get_order()->capture->authorization_code, // this is undocumented, but needs to stay
			'amount'   => $this->get_order()->capture->amount * 100,
			'comments' => array(
				'comment' => array(
					'@attributes' => array(
						'id' => 1,
					),
					Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', $this->get_order()->capture->description ), 255 ),
				),
			),
		);

		$this->request_type = ( $this->get_order()->capture->multi ) ? 'multisettle' : 'settle';
	}


	/**
	 * Sets the data for a credit card charge.
	 *
	 * @since 2.0.0
	 */
	public function set_refund_data() {

		$this->request_type = 'rebate';

		$this->set_refund_void_data();

		$this->request_data['amount'] = array(
			'@attributes' => array(
				'currency' => Framework\SV_WC_Order_Compatibility::get_prop( $this->get_order(), 'currency', 'view' ),
			),
			$this->get_order()->refund->amount * 100,
		);

		$this->request_data['authcode']   = $this->get_order()->refund->authorization_code;
		$this->request_data['refundhash'] = sha1( $this->get_order()->refund->password );
	}


	/**
	 * Sets the data for a credit card charge.
	 *
	 * @since 2.0.0
	 */
	public function set_void_data() {

		$this->request_type = 'void';

		$this->set_refund_void_data();
	}


	/**
	 * Sets the data for a refund or void.
	 *
	 * @since 2.0.0
	 */
	public function set_refund_void_data() {

		$this->request_data = array(
			'orderid'  => $this->get_order()->refund->realex_id,
			'pasref'   => $this->get_order()->refund->trans_id,
			'comments' => array(
				'comment' => array(
					'@attributes' => array(
						'id' => 1,
					),
					Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', $this->get_order()->refund->reason ), 255 ),
				),
			),
		);
	}


	/**
	 * Gets the parameters used to generate the SHA-1 hash.
	 *
	 * TODO: not a fan of this at all {CW 2017-08-12}
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_hash_values() {

		$values = array(
			$this->request_data['orderid'],
		);

		if ( ! empty( $this->request_data['amount'] ) ) {

			if ( is_array( $this->request_data['amount'] ) ) {
				$values[] = $this->request_data['amount'][0];
				$values[] = $this->request_data['amount']['@attributes']['currency'];
			} else {
				$values[] = $this->request_data['amount'];
				$values[] = '';
			}

		} else {

			$values[] = ''; // intentionally blank
			$values[] = ''; // intentionally blank
		}

		$values[] = ''; // intentionally blank

		return $values;
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
