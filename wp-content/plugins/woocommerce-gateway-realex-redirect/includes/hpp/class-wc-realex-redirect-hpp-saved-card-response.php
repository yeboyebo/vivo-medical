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
 * The hosted payment page tokenization response class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_HPP_Saved_Card_Response extends WC_Realex_Redirect_API_HPP_Credit_Card_Response implements Framework\SV_WC_Payment_Gateway_Payment_Notification_Tokenization_Response, Framework\SV_WC_Payment_Gateway_API_Customer_Response {


	/**
	 * Gets the payment token object.
	 *
	 * @since 2.0.0
	 *
	 * @return Framework\SV_WC_Payment_Gateway_Payment_Token
	 */
	public function get_payment_token() {

		$token_id = ( $this->get_value( 'HPP_CHOSEN_PMT_REF' ) ) ? $this->get_value( 'HPP_CHOSEN_PMT_REF' ) : $this->get_value( 'SAVED_PMT_REF' );

		return new Framework\SV_WC_Payment_Gateway_Payment_Token( $token_id, array(
			'type'      => 'credit_card',
			'card_type' => $this->get_card_type(),
			'last_four' => $this->get_account_number(),
			'exp_month' => $this->get_exp_month(),
			'exp_year'  => $this->get_exp_year(),
		) );
	}


	/**
	 * Gets the last four of the credit card number.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_account_number() {

		$last_four = substr( $this->get_value( 'SAVED_PMT_DIGITS' ), -4 );

		return ( $last_four ) ? $last_four : parent::get_account_number();
	}


	/**
	 * Gets the card type, i.e., 'visa', 'mastercard', etc...
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_card_type() {

		$card_type = Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type( $this->get_value( 'SAVED_PMT_TYPE' ) );

		return ( $card_type ) ? $card_type : parent::get_card_type();
	}


	/**
	 * Gets the card expiration month with leading zero.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_exp_month() {

		$date = $this->get_value( 'SAVED_PMT_EXPDATE' );

		return ! empty( $date ) ? substr( $date, 0, 2 ) : parent::get_exp_month();
	}


	/**
	 * Gets the card expiration year with four digits.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_exp_year() {

		$date = $this->get_value( 'SAVED_PMT_EXPDATE' );

		return ! empty( $date ) ? substr( $date, -2 ) : parent::get_exp_year();
	}


	/**
	 * Gets any payment tokens that were edited on the hosted pay page.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_edited_payment_tokens() {

		return explode( ',', $this->get_value( 'HPP_EDITED_PMT_REF' ) );
	}


	/**
	 * Gets any payment tokens that were deleted on the hosted pay page.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_deleted_payment_tokens() {

		return explode( ',', $this->get_value( 'HPP_DELETED_PMT_REF' ) );
	}


	/**
	 * Gets the customer ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_customer_id() {

		return $this->get_value( 'SAVED_PAYER_REF' );
	}


	/**
	 * Gets the overall result message for a new payment method tokenization
	 * and/or customer creation.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_tokenization_message() {

		return trim( $this->get_customer_created_message() . ' ' . $this->get_payment_method_tokenized_message() );
	}


	/**
	 * Gets the result message for a new customer creation.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_customer_created_message() {

		return $this->get_value( 'PAYER_SETUP_MSG' );
	}


	/**
	 * Gets the result message for a new payment method tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_method_tokenized_message() {

		return $this->get_value( 'PMT_SETUP_MSG' );
	}


	/**
	 * Gets the result code for a new customer creation.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_customer_created_code() {

		return $this->get_value( 'PAYER_SETUP' );
	}


	/**
	 * Gets the result code for a new payment method tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_method_tokenized_code() {

		return $this->get_value( 'PMT_SETUP' );
	}


	/**
	 * Determines whether a new customer was created.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function customer_created() {

		return $this->get_customer_created_code();
	}


	/**
	 * Determines whether a new payment method was tokenized.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function payment_method_tokenized() {

		return 1 === (int) $this->get_value( 'REALWALLET_CHOSEN' );
	}


	/**
	 * Determines whether the overall payment tokenization was successful.
	 *
	 * Checks that the payment method was tokenized, and if a new customer was
	 * created, that was successful.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function tokenization_successful() {

		$successful = true;

		if ( $this->customer_created() && ! $this->customer_creation_successful() ) {
			$successful = false;
		}

		return $successful && $this->payment_method_tokenization_successful();
	}


	/**
	 * Determines whether the customer was successfully created.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function customer_creation_successful() {

		return '00' === $this->get_customer_created_code();
	}


	/**
	 * Determines whether the payment method was successfully tokenized.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function payment_method_tokenization_successful() {

		return '00' === $this->get_payment_method_tokenized_code();
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
		$string = str_replace( $this->get_value( 'SAVED_PMT_DIGITS' ), $this->get_account_number(), $string );

		// mask the saved card expiration
		$string = str_replace( $this->get_value( 'SAVED_PMT_EXPDATE' ), str_repeat( '*', strlen( $this->get_value( 'SAVED_PMT_EXPDATE' ) ) ), $string );

		return $string;
	}


}
