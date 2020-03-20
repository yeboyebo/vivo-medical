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
class Capture extends Framework\Payment_Gateway\Handlers\Capture {


	/**
	 * Gets the maximum amount that can be captured from an order.
	 *
	 * Realex allows capturing up to 115% of the authorization total.
	 *
	 * @since 2.1.2
	 *
	 * @param \WC_Order $order order object
	 * @return float
	 */
	public function get_order_capture_maximum( \WC_Order $order ) {

		$max = parent::get_order_capture_maximum( $order );

		if ( 'yes' !== $this->get_gateway()->get_order_meta( $order, '3dsecure' ) ) {
			$max *= 1.15; // 115% unless this was a 3DSecure transaction
		}

		return $max;
	}


	/**
	 * Determines if an order is eligible for capture.
	 *
	 * @since 2.1.2
	 *
	 * @param \WC_Order $order order object
	 * @return bool
	 */
	public function order_can_be_captured( \WC_Order $order ) {

		if ( Framework\SV_WC_Payment_Gateway::PAYMENT_TYPE_CREDIT_CARD !== $this->get_gateway()->get_order_meta( $order, 'payment_type' ) ) {
			return false;
		}

		return parent::order_can_be_captured( $order );
	}



}
