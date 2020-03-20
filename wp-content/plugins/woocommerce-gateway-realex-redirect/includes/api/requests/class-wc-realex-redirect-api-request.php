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
 * The Realex API base request class.
 *
 * @since 2.0.0
 */
abstract class WC_Realex_Redirect_API_Request extends Framework\SV_WC_API_XML_Request implements Framework\SV_WC_Payment_Gateway_API_Request {


	/** @var string API channel */
	protected $channel = 'ECOM';

	/** @var string request type */
	protected $request_type;

	/** @var string Realex merchant ID */
	protected $merchant_id;

	/** @var subaccount for processing */
	protected $subaccount;

	/** @var hash secret */
	protected $secret;


	/** Setter Methods ********************************************************/


	/**
	 * Sets the merchant ID for this request.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value merchant ID to set
	 */
	public function set_merchant_id( $value ) {

		$this->merchant_id = $value;
	}


	/**
	 * Sets the subaccount to use for this request.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value subaccount to set
	 */
	public function set_subaccount( $value ) {

		$this->subaccount = $value;
	}


	/**
	 * Sets the secret to use for this request.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value secret to set
	 */
	public function set_secret( $value ) {

		$this->secret = $value;
	}


	/** Getter Methods ********************************************************/


	/**
	 * Get the request data to be converted to XML.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_data() {

		$timestamp = date( 'Ymdhis' );

		// required for every transaction
		$auth_data = array(
			'@attributes' => array(
				'type'      => $this->get_type(),
				'timestamp' => $timestamp,
			),
			'merchantid' => $this->get_merchant_id(),
		);

		// add the subaccount if set
		// refund transactions omit the subaccount, as requested by Realex
		if ( $this->get_subaccount() && 'rebate' !== $this->get_type() ) {
			$auth_data['account'] = $this->get_subaccount();
		}

		// add required request data
		$this->request_data = array_merge( $auth_data, $this->request_data );

		/**
		 * Filters the API request data.
		 *
		 * @since 2.0.0
		 *
		 * @param array $data request data to be filtered
		 * @param \WC_Realex_Redirect_API_Request $request API request object
		 */
		$this->request_data = apply_filters( 'wc_realex_redirect_api_request_data', $this->request_data, $this );

		$hash_values = array(
			$timestamp,
			$this->get_merchant_id(),
		);

		$hash_values = array_merge( $hash_values, $this->get_hash_values() );

		// add the SHA-1 hash
		$this->request_data['sha1hash'] = WC_Gateway_Realex_Redirect::generate_sha1_hash( $hash_values, $this->get_secret() );

		// add the root element
		$this->request_data = array(
			$this->get_root_element() => $this->request_data,
		);

		return $this->request_data;
	}


	/**
	 * Gets the request type.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_type() {

		return $this->request_type;
	}


	/**
	 * Gets the merchant ID for this request.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_merchant_id() {

		return $this->merchant_id;
	}


	/**
	 * Gets the subaccount to use for this request.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_subaccount() {

		return $this->subaccount;
	}


	/**
	 * Gets the shared secret, used for generating the SHA-1 hash.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_secret() {

		return $this->secret;
	}


	/**
	 * Gets the parameters used to generate the SHA-1 hash.
	 *
	 * Child classes should override this to return an array of parameter values
	 * based on Realex's requirements for the request type.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	abstract protected function get_hash_values();


	/**
	 * Gets the XML document's root element.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_root_element() {

		return 'request';
	}


	/** Helper Methods ********************************************************/


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
		if ( isset( $this->request_data[ $this->get_root_element() ]['sha1hash'] ) && $hash = $this->request_data[ $this->get_root_element() ]['sha1hash'] ) {
			$string = str_replace( $hash, str_repeat( '*', strlen( $hash ) ), $string );
		}

		return $string;
	}


}
