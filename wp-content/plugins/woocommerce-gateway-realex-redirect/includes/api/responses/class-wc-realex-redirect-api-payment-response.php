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
 * The Realex API payment response class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_Payment_Response extends WC_Realex_Redirect_API_Response implements Framework\SV_WC_Payment_Gateway_API_Authorization_Response {


	/**
	 * Gets the authorization code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_authorization_code() {

		return $this->authcode;
	}


	/**
	 * Gets the AVS result code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_avs_result() {

		return $this->avspostcoderesponse;
	}


	/**
	 * Gets the result of the AVS address check.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_avs_address_result() {

		return $this->avsaddressresponse;
	}


	/**
	 * Gets the CAVV result code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_csc_result() {

		return $this->cvnresult;
	}


	/**
	 * Determines if the CSC was a match.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function csc_match() {

		return 'M' === $this->get_csc_result();
	}


	/**
	 * Gets the Realex order number.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_order_number() {

		return $this->orderid;
	}


	/**
	 * Gets the cardholder name.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_cardholder_name() {

		return '';
	}


}
