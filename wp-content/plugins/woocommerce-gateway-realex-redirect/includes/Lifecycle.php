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

namespace SkyVerge\WooCommerce\Realex_HPP;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_3_0 as Framework;

/**
 * The Realex Redirect plugin lifecycle handler.
 *
 * @since 2.1.2
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Performs any install tasks.
	 *
	 * @see Framework\SV_WC_Plugin::install()
	 *
	 * @since 2.1.2
	 */
	protected function install() {

		// check for a pre 1.1.1 version
		$legacy_settings = get_option( 'woocommerce_realex_redirect_settings' );

		if ( $legacy_settings ) {

			// upgrading from the pre-versioned version, need to adjust the settings array

			// form_submission_method => 'yes'  In version 1.1.1 of the plugin we added the option to redirect
			//  from the checkout page to the hosted payment page, and made it the default behavior.  Unfortunately
			//  all the existing customers will have whitelisted the pay page url /checkout/pay/ with Realex so
			//  we can't go willy-nilly changing this on them so we'll default them to keeping their current
			//  behavior
			if ( ! isset( $legacy_settings['form_submission_method'] ) ) {
				$legacy_settings['form_submission_method'] = 'yes';
			}

			// log -> debug_mode
			if ( ! isset( $legacy_settings['log'] ) || 'no' == $legacy_settings['log'] ) {
				$legacy_settings['debug_mode'] = 'off';
			} elseif ( isset( $legacy_settings['log'] ) && 'yes' == $legacy_settings['log'] ) {
				$legacy_settings['debug_mode'] = 'log';
			}
			unset( $legacy_settings['log'] );

			// set the updated options array
			update_option( 'woocommerce_realex_redirect_settings', $legacy_settings );

			// upgrade path
			$this->upgrade( '1.0.0' );

			// and we're done
			return;
		}
	}


	/**
	 * Performs any required upgrade tasks.
	 *
	 * @see Framework\SV_WC_Plugin::upgrade()
	 *
	 * @since 2.1.2
	 *
	 * @param string $installed_version the currently installed version
	 */
	protected function upgrade( $installed_version ) {

		if ( version_compare( $installed_version, '2.0.0', '<' ) ) {

			$this->get_plugin()->log( 'Upgrading to version 2.0.0' );

			$legacy_settings   = get_option( 'woocommerce_realex_redirect_settings', array() );
			$settings_upgraded = get_option( 'woocommerce_realex_redirect_settings_upgraded', false );

			if ( ! empty( $legacy_settings ) && ! $settings_upgraded ) {

				// back up the settings, just in case someone wants to downgrade
				update_option( 'woocommerce_realex_redirect_settings_legacy', $legacy_settings );

				$this->get_plugin()->log( 'Upgrading settings' );

				$legacy_settings = wp_parse_args( $legacy_settings, array(
					'testmode'               => 'yes',
					'debug_mode'             => 'off',
					'settlement'             => 'yes',
					'cardtypes'              => array( 'VISA', 'MC', 'AMEX', 'LASER', 'SWITCH', 'DINERS', 'cartebleue', 'maestro', ),
					'merchantid'             => '',
					'sharedsecret'           => '',
					'accounttest'            => '',
					'accountlive'            => '',
					'enable_avs'             => 'yes',
				) );

				$upgraded_settings = array(
					'enabled'          => $legacy_settings['enabled'],
					'title'            => $legacy_settings['title'],
					'description'      => $legacy_settings['description'],
					'transaction_type' => 'no' === $legacy_settings['settlement'] ? \WC_Gateway_Realex_Redirect::TRANSACTION_TYPE_AUTHORIZATION : \WC_Gateway_Realex_Redirect::TRANSACTION_TYPE_CHARGE,
					'card_types'       => $legacy_settings['cardtypes'],
					'debug_mode'       => $legacy_settings['debug_mode'],
					'environment'      => 'yes' === $legacy_settings['testmode'] ? \WC_Gateway_Realex_Redirect::ENVIRONMENT_TEST : \WC_Gateway_Realex_Redirect::ENVIRONMENT_PRODUCTION, // TODO: don't check after testing
					'merchant_id'      => $legacy_settings['merchantid'],
					'shared_secret'    => $this->get_plugin()->encrypt_credential( $legacy_settings['sharedsecret'] ),
					'subaccount'       => $legacy_settings['accountlive'],
					'test_subaccount'  => $legacy_settings['accounttest'],
					'form_type'        => 'redirect',
					'enable_avs'       => $legacy_settings['enable_avs'],
				);

				if ( update_option( 'woocommerce_realex_redirect_settings', $upgraded_settings ) ) {

					update_option( 'woocommerce_realex_redirect_settings_upgraded', true );

					$this->get_plugin()->log( 'Settings successfully upgraded' );

				} else {

					$this->get_plugin()->log( 'Error upgrading settings' );
				}
			}

			// TODO: upgrade routine for direct gateway

			$this->get_plugin()->log( 'Finished upgrading to version 2.0.0' );
		}
	}


}
