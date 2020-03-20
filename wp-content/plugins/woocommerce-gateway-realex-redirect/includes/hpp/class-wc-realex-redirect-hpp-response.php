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
 * The hosted payment page response class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_HPP_Response implements Framework\SV_WC_Payment_Gateway_API_Payment_Notification_Response {


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param array $data payment response data
	 */
	public function __construct( $data ) {

		unset( $data['wc-api'] );

		$this->data = $data;
	}


	/**
	 * Determines if the transaction was successful.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return '00' === (string) $this->get_status_code();
	}


	/**
	 * Determines if the transaction was held.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_held() {

		return false;
	}


	/**
	 * Determines if the transaction was cancelled.
	 *
	 * Realex HPP transactions are not cancelled.
	 *
	 * @since 2.0.0
	 * @return false
	 */
	public function transaction_cancelled() {

		return false;
	}


	/**
	 * Gets the response transaction ID.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_transaction_id() {

		return $this->get_value( 'PASREF' );
	}


	/**
	 * Gets the authorization code.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_authorization_code() {

		return $this->get_value( 'AUTHCODE' );
	}


	/**
	 * Gets the response status code.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_status_code() {

		return $this->get_value( 'RESULT' );
	}


	/**
	 * Gets the response status message.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_status_message() {

		return $this->get_value( 'MESSAGE' );
	}


	/**
	 * Gets a message appropriate for a frontend user.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_user_message() {

		return '';
	}


	/**
	 * Gets the response payment type.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_type() {

		return '';
	}


	/**
	 * Gets the order number associated with this response.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_order_number() {

		return $this->get_value( 'ORDER_ID' );
	}


	/**
	 * Gets the transaction timestamp.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_timestamp() {

		return $this->get_value( 'TIMESTAMP' );
	}


	/**
	 * Gets the order ID associated with this response.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_order_id() {

		return $this->get_value( 'X_ORDER_ID' );
	}


	/**
	 * Gets the payment amount.
	 *
	 * @since 2.0.0
	 *
	 * @return float
	 */
	public function get_amount() {

		$amount = (int) $this->get_value( 'AMOUNT' ); // in pennies

		return (float) $amount / 100;
	}


	/**
	 * Gets the batch ID for this transaction.
	 *
	 * If this was an authorization only (and therefore has no settlement batch),
	 * "-1" will be returned.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_batch_id() {

		return $this->get_value( 'BATCHID' );
	}


	/**
	 * Gets the merchant ID that generated this response.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_merchant_id() {

		return $this->get_value( 'MERCHANT_ID' );
	}


	/**
	 * Gets the response SHA-1 hash for validation.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_hash() {

		return $this->get_value( 'SHA1HASH' );
	}


	/**
	 * Gets a specific response value for the key.
	 *
	 * @since 2.0.0
	 *
	 * @param string $key data key
	 * @return string
	 */
	protected function get_value( $key ) {

		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : '';
	}


	/**
	 * Gets the string representation of this request.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string() {

		return print_r( $this->data, true );
	}


	/**
	 * Gets the string representation of this request with any and all sensitive elements masked or removed.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string_safe() {

		$string = $this->to_string();

		// mask the SHA-1 hash
		$string = str_replace( $this->get_value( 'SHA1HASH' ), str_repeat( '*', strlen( $this->get_value( 'SHA1HASH' ) ) ), $string );

		return $string;
	}


	/**
	 * Determines if this is an IPN response.
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function is_ipn() {

		return false;
	}


	/** no-op */
	public function get_account_number() {

		return '';
	}


}
