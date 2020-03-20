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
 * The Realex API stored card response class.
 *
 * @since 2.0.0
 */
class WC_Realex_Redirect_API_Stored_Card_Response extends WC_Realex_Redirect_API_Response {


	/**
	 * Determines whether the transaction was approved.
	 *
	 * Also consider a 501 response as approved, as this indicates the token
	 * does not exist remotely, so it should still be removed locally.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return parent::transaction_approved() || '501' === $this->get_status_code();
	}


}
