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
 * The Realex API base response class.
 *
 * @since 2.0.0
 */
abstract class WC_Realex_Redirect_API_Response extends Framework\SV_WC_API_XML_Response implements Framework\SV_WC_Payment_Gateway_API_Response {


	/**
	 * Determines whether the transaction was approved.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return '00' === $this->get_status_code();
	}


	/**
	 * Determines whether the transaction was held.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_held() {

		return false;
	}


	/**
	 * Gets the result message.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_status_message() {

		return $this->message;
	}


	/**
	 * Gets the result code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_status_code() {

		return $this->result;
	}


	/**
	 * Gets the transaction ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_transaction_id() {

		return $this->pasref;
	}


	/**
	 * Gets the customer-friendly message.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_user_message() {

		return '';
	}

	/**
	 * Gets the response hash.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_hash() {

		return $this->sha1hash;
	}


	/** Helper Methods ********************************************************/


	/**
	 * Gets the payment type for this response.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_type() {

		return WC_Gateway_Realex_Redirect::PAYMENT_TYPE_CREDIT_CARD;
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
		$string = str_replace( $this->get_hash(), str_repeat( '*', strlen( $this->get_hash() ) ), $string );

		return $string;
	}


}
