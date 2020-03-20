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
class WC_Realex_Redirect_API_HPP_Credit_Card_Response extends WC_Realex_Redirect_API_HPP_Response implements Framework\SV_WC_Payment_Gateway_API_Payment_Notification_Credit_Card_Response {


	/**
	 * Determines if the transaction was successful.
	 *
	 * Mainly checks the result code, and if the fraud result if the fraud
	 * filter is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		$approved = parent::transaction_approved();

		if ( $this->is_fraud_filter_active() && ! $this->fraud_filter_passed() ) {
			$approved = false;
		}

		return $approved;
	}


	/**
	 * Determines if the transaction was held.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_held() {

		$held = parent::transaction_held();

		if ( $this->is_fraud_filter_active() && 'HOLD' === $this->get_fraud_result() ) {
			$held = true;
		}

		return $held;
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

		$message_helper = new Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper();

		$message_id = '';

		// general declines
		if ( Framework\SV_WC_Helper::str_starts_with( $this->get_status_code(), '1' ) ) {
			$message_id = 'decline';
		}

		// display a generic decline if blocked by the fraud filter
		if ( $this->is_fraud_filter_active() && ! $this->fraud_filter_passed() && ! $this->transaction_held() ) {
			$message_id = 'decline';
		}

		return $message_helper->get_user_message( $message_id );
	}


	/** Fraud Methods *********************************************************/


	/**
	 * Gets the result of the AVS postcode check.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_avs_result() {

		return $this->get_value( 'AVSPOSTCODERESULT' );
	}


	/**
	 * Gets the result of the AVS address check.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_avs_address_result() {

		return $this->get_value( 'AVSADDRESSRESULT' );
	}


	/**
	 * Gets the result of the CSC check.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_csc_result() {

		return $this->get_value( 'CVNRESULT' );
	}


	/**
	 * Determines if the CSC check was successful.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function csc_match() {

		return 'M' === $this->get_csc_result();
	}


	/**
	 * Gets the fraud result.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_fraud_result() {

		return $this->get_value( 'HPP_FRAUDFILTER_RESULT' );
	}


	/**
	 * Gets the fraud rule name.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_fraud_rule_name() {

		return $this->get_value( 'HPP_FRAUDFILTER_RULE_NAME' );
	}


	/**
	 * Gets the fraud filter mode.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_fraud_filter_mode() {

		return $this->get_value( 'HPP_FRAUDFILTER_MODE' );
	}


	/**
	 * Determines if the fraud filter is active.
	 *
	 * The result should only be respected if the filter is in active mode and
	 * there was a result.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_fraud_filter_active() {

		return $this->get_fraud_result() && ( 'ACTIVE' === $this->get_fraud_filter_mode() || ! $this->get_fraud_filter_mode() );
	}


	/**
	 * Whether the transaction passed the fraud filter checks, or no result was returned.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function fraud_filter_passed() {

		return in_array( $this->get_fraud_result(), array( 'PASS', 'NOT_EXECUTED' ), true );
	}


	/** 3DSecure Methods ******************************************************/


	/**
	 * Gets the 3DSecure ECI.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_eci() {

		return $this->get_value( 'ECI' );
	}


	/**
	 * Gets the 3DSecure CAVV.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_cavv() {

		return $this->get_value( 'CAVV' );
	}


	/**
	 * Gets the 3DSecure XID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_xid() {

		return $this->get_value( 'XID' );
	}


	/**
	 * Determines if this was a 3DSecure transaction.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_3dsecure() {

		return ( $this->get_eci() );
	}


	/** Dynamic Currency Methods **********************************************/


	/**
	 * Determines if the customer chose currency conversion.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_dcc() {

		return 'Yes' === $this->get_value( 'DCCCHOICE' );
	}


	/**
	 * Gets the DCC amount paid by the cardholder in their currency.
	 *
	 * @since 2.0.0
	 *
	 * @return float
	 */
	public function get_dcc_amount() {

		return (float) $this->get_value( 'DCCCARDHOLDERAMOUNT' ) / 100;
	}


	/**
	 * Gets the DCC currency chosen by the cardholder.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_dcc_currency() {

		return $this->get_value( 'DCCCARDHOLDERCURRENCY' );
	}


	/**
	 * Gets the DCC conversion rate.
	 *
	 * @since 2.0.0
	 *
	 * @return float
	 */
	public function get_dcc_rate() {

		return (float) $this->get_value( 'DCCRATE' );
	}


	/** Credit Card Methods ***************************************************/


	/**
	 * Gets the card last four.
	 *
	 * @since 2.0.0
	 * @return null
	 */
	public function get_account_number() {

		return substr( $this->get_value( 'CARDDIGITS' ), -4 );
	}


	/**
	 * Gets the card type, if available, i.e., 'visa', 'mastercard', etc
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Helper::payment_type_to_name()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_card_type() {

		return Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type( $this->get_value( 'CARDTYPE' ) );
	}


	/**
	 * Gets the card expiration month with leading zero, if available.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_exp_month() {

		$date = $this->get_value( 'EXPDATE' );

		return ! empty( $date ) ? substr( $date, 0, 2 ) : '';
	}


	/**
	 * Gets the card expiration year, if available
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_exp_year() {

		$date = $this->get_value( 'EXPDATE' );

		return ! empty( $date ) ? substr( $date, -2 ) : '';
	}


	/**
	 * Gets the cardholder name.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_cardholder_name() {

		return $this->get_value( 'CARDNAME' );
	}


	/**
	 * Gets the transaction payment type.
	 *
	 * Credit card, in this case.
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

		$string = parent::to_string_safe();

		// mask the saved card digits
		$string = str_replace( $this->get_value( 'CARDDIGITS' ), $this->get_account_number(), $string );

		// mask the saved card expiration
		$string = str_replace( $this->get_value( 'EXPDATE' ), str_repeat( '*', strlen( $this->get_value( 'EXPDATE' ) ) ), $string );

		return $string;
	}


}
