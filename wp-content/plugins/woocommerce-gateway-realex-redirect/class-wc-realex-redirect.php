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
 * The Realex HPP payment gateway plugin class.
 *
 * This is primarily a hosted gateway with some additional direct API components,
 * such as refunds, captures, tokenization, and Subscriptions/Pre-Orders
 * integrations.
 */
class WC_Realex_Redirect extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** plugin version number */
	const VERSION = '2.1.2';

	/** plugin ID */
	const PLUGIN_ID = 'realex_redirect';

	/** the gateway class name */
	const GATEWAY_CLASS_NAME = 'WC_Gateway_Realex_Redirect';

	/** the gateway ID */
	const GATEWAY_ID = 'realex_redirect';

	/** @var \WC_Realex_Redirect singleton instance of this plugin */
	protected static $instance;


	/**
	 * Constructs the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Plugin::__construct()
	 */
	public function __construct() {

		parent::__construct( self::PLUGIN_ID, self::VERSION, array(
			'gateways' => array(
				self::GATEWAY_ID => self::GATEWAY_CLASS_NAME,
			),
			'supports' => array(
				self::FEATURE_CUSTOMER_ID,
				self::FEATURE_CAPTURE_CHARGE,
				self::FEATURE_MY_PAYMENT_METHODS,
			),
			'text_domain' => 'woocommerce-gateway-realex-redirect',
		) );

		// load the files
		$this->includes();

		// remove certain My Payment Methods token actions
		add_filter( 'wc_' . $this->get_id() . '_my_payment_methods_table_method_actions', array( $this, 'remove_my_payment_methods_actions' ) );
	}


	/**
	 * Requires the necessary files.
	 *
	 * @since 1.2.0
	 */
	public function includes() {

		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-realex-redirect.php' );

		// HPP classes
		require_once( $this->get_plugin_path() . '/includes/hpp/class-wc-realex-redirect-hpp-response.php' );
		require_once( $this->get_plugin_path() . '/includes/hpp/class-wc-realex-redirect-hpp-credit-card-response.php' );
		require_once( $this->get_plugin_path() . '/includes/hpp/class-wc-realex-redirect-hpp-saved-card-response.php' );
		require_once( $this->get_plugin_path() . '/includes/hpp/class-wc-realex-redirect-hpp-paypal-response.php' );
	}


	/**
	 * Removes certain My Payment Methods token actions.
	 *
	 * There is no need for a "Make Default" action, since method selection is
	 * handled on the hosted payment page.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function remove_my_payment_methods_actions( $actions ) {

		unset( $actions['make_default'] );

		return $actions;
	}


	/**
	 * Gets deprecated/removed hooks.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_deprecated_hooks() {

		return array(
			'woocommerce_realex_icon' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_realex_redirect_icon',
				'map'         => true,
			),
			'woocommerce_realex_redirect_card_types' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_realex_redirect_available_card_types',
			),
			'woocommerce_realex_account' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_realex_redirect_subaccount',
				'map'         => true,
			),
			'wc_realex_redirect_order_number_suffix' => array(
				'version' => '2.0.0',
				'removed' => true,
			),
			'wc_realex_redirect_form_params' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_realex_redirect_hpp_params',
				'map'         => true,
			),
			'wc_realex_redirect_endpoint_url' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_realex_redirect_hpp_endpoint_url',
			),
		);
	}


	/**
	 * Adds any admin notices.
	 *
	 * @since 2.0.0
	 */
	public function add_admin_notices() {

		parent::add_admin_notices();

		if ( get_option( 'woocommerce_realex_redirect_settings_upgraded', false ) ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				sprintf(
					__( 'Heads up! You\'ve upgraded to a major new version of %1$s. If you experience any issues with the payment form, please review our %2$sUpgrade Guide%3$s to ensure your merchant account is compatible with this new version.', 'woocommerce-gateway-realex-redirect' ),
					'<strong>' . $this->get_plugin_name() . '</strong>',
					'<a href="' . esc_url( 'https://docs.woocommerce.com/document/realex-redirec-payment-gateway/#upgrading' ) . '">', '</a>'
				),
				self::PLUGIN_ID . '-2-0-upgrade',
				array(
					'always_show_on_settings' => false,
					'notice_class'            => 'notice-warning',
				)
			);
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Encrypts a connection credential for storage.
	 *
	 * @since 2.0.0
	 * @param string $data the credential value
	 * @return string
	 */
	public function encrypt_credential( $data ) {

		$data = trim( $data );

		if ( empty( $data ) ) {
			return '';
		}

		if ( function_exists( 'openssl_encrypt' ) ) {
			$vector = openssl_random_pseudo_bytes( $this->get_encryption_vector_length() );
			$data   = openssl_encrypt( $data, $this->get_encryption_method(), $this->get_encryption_key(), OPENSSL_RAW_DATA, $vector );
		}

		return base64_encode( $vector . $data );
	}


	/**
	 * Decrypts a connection credential for use.
	 *
	 * @since 2.0.0
	 * @param string $data the encrypted credential value
	 * @return string
	 */
	public function decrypt_credential( $data ) {

		if ( empty( $data ) ) {
			return '';
		}

		$data = base64_decode( $data );

		if ( function_exists( 'openssl_decrypt' ) ) {

			$vector_length = $this->get_encryption_vector_length();
			$vector        = substr( $data, 0, $vector_length );
			$data          = substr( $data, $vector_length );
			$data          = openssl_decrypt( $data, $this->get_encryption_method(), $this->get_encryption_key(), OPENSSL_RAW_DATA, $vector );
		}

		return trim( $data );
	}


	/**
	 * Gets the key used to encrypt the connection credentials.
	 *
	 * @return string
	 */
	private function get_encryption_key() {

		return md5( wp_salt(), true );
	}


	/**
	 * Gets the vector length for encrypting credentials.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	private function get_encryption_vector_length() {

		return openssl_cipher_iv_length( $this->get_encryption_method() );
	}


	/**
	 * Gets the method used for encrypting credentials.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	private function get_encryption_method() {

		$available_methods = openssl_get_cipher_methods();
		$preferred_method  = 'AES-128-CBC';

		$method = in_array( $preferred_method, $available_methods, true ) ? $preferred_method : $available_methods[0];

		return $method;
	}


	/**
	 * Gets the singleton instances of this class.
	 *
	 * @since 1.3.0
	 * @see wc_realex_redirect()
	 *
	 * @return \WC_Realex_Redirect plugin object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Gets the plugin documentation url.
	 *
	 * @see Framework\SV_WC_Plugin::get_documentation_url()
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public function get_documentation_url() {
		return 'http://docs.woocommerce.com/document/realex-redirec-payment-gateway/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_support_url()
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Returns the plugin name, localized.
	 *
	 * @see Framework\SV_WC_Plugin::get_plugin_name()
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {

		return __( 'WooCommerce Realex Payments HPP', 'woocommerce-gateway-realex-redirect' );
	}


	/**
	 * Gets the full path and filename of the plugin file.
	 *
	 * @see Framework\SV_WC_Plugin::get_file()
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	protected function get_file() {
		return __FILE__;
	}


	/**
	 * Initializes the lifecycle handler.
	 *
	 * @since 2.1.2
	 */
	protected function init_lifecycle_handler() {

		require_once( $this->get_plugin_path() . '/includes/Lifecycle.php' );

		$this->lifecycle_handler = new \SkyVerge\WooCommerce\Realex_HPP\Lifecycle( $this );
	}


} // end WC_Realex_Redirect


/**
 * Returns the One True Instance of Realex Redirect
 *
 * @since 1.3.0
 * @return WC_Realex_Redirect
 */
function wc_realex_redirect() {
	return WC_Realex_Redirect::instance();
}
