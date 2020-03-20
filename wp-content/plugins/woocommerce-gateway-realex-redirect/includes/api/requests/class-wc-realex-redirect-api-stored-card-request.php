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
 * The Realex API stored card request class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_Stored_Card_Request extends WC_Realex_Redirect_API_Request {


	/** the payer edit request type */
	const TYPE_PAYER_EDIT = 'payer-edit';

	/** the card delete request type */
	const TYPE_CANCEL_CARD = 'card-cancel-card';


	/**
	 * Sets the data for updating an existing payment profile
	 *
	 * @since 2.0.0
	 */
	public function set_update_payer_data( $customer_id, $address ) {

		$address = wp_parse_args( $address, array(
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'country'    => '',
			'postcode'   => '',
			'phone'      => '',
			'email'      => '',
		) );

		$this->request_type = self::TYPE_PAYER_EDIT;

		$country_name = $address['country'];
		$state_name   = $address['state'];

		if ( WC()->countries ) {

			$countries = WC()->countries->get_countries();

			if ( ! empty( $countries[ $country_name ] ) ) {
				$country_name = $countries[ $country_name ];
			}

			$states = WC()->countries->get_states( $address['country'] );

			if ( ! empty( $states[ $state_name ] ) ) {
				$state_name = $states[ $state_name ];
			}
		}

		$this->request_data = array(
			'payer' => array(
				'@attributes' => array(
					'ref'  => $customer_id,
				),
				'firstname' => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['first_name'] ), 30, '' ),
				'surname'   => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['last_name'] ), 50, '' ),
				'company'   => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['company'] ), 50, '' ),
				'email'     => $address['email'],
				'address'   => array(
					'line1'    => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['address_1'] ), 50, '' ),
					'line2'    => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['address_2'] ), 50, '' ),
					'city'     => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $address['city'] ), 50, '' ),
					'county'   => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9 \'".,-_+]*/', '', $state_name ), 50, '' ),
					'postcode' => $address['postcode'],
					'country'  => array(
						'@attributes' => array(
							'code' => $address['country'],
						),
						$country_name,
					),
				),
				'phonenumbers' => array(
					'home' => $address['phone'],
				),
			),
		);
	}

	/**
	 * Sets the data for a credit card authorization.
	 *
	 * @since 2.0.0
	 */
	public function set_remove_data( $token_id, $customer_id ) {

		$this->request_type = self::TYPE_CANCEL_CARD;

		$this->request_data = array(
			'card'  => array(
				'ref'      => $token_id,
				'payerref' => $customer_id,
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

		$values = array();

		switch ( $this->request_type ) {

			case self::TYPE_PAYER_EDIT:

				$values = array(
					'',
					'',
					'',
					$this->request_data['payer']['@attributes']['ref'],
				);

			break;

			case self::TYPE_CANCEL_CARD:

				$values = array(
					$this->request_data['card']['payerref'],
					$this->request_data['card']['ref'],
				);

			break;
		}

		return $values;
	}


}
