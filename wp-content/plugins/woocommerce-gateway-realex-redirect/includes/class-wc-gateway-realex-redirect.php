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
 * The Realex Redirect gateway class.
 *
 * @since 1.0.0
 */
class WC_Gateway_Realex_Redirect extends Framework\SV_WC_Payment_Gateway_Hosted {


	/** production environment HPP URL */
	const HPP_URL = 'https://pay.realexpayments.com/pay';

	/** test environment HPP URL */
	const HPP_URL_TEST = 'https://pay.sandbox.realexpayments.com/pay';

	/** HPP version number */
	const HPP_VERSION = 2;

	/** HPP iFrame form type */
	const FORM_TYPE_IFRAME = 'iframe';

	/** HPP redirect form type */
	const FORM_TYPE_REDIRECT = 'redirect';

	/** HPP PayPal payment type */
	const PAYMENT_TYPE_PAYPAL = 'paypal';


	/** @var string whether tokenizatin is forced */
	protected $tokenization_forced;

	/** @var string account merchant ID */
	protected $merchant_id;

	/** @var string account shared secret */
	protected $shared_secret;

	/** @var string account rebate password */
	protected $rebate_password;

	/** @var string subaccount */
	protected $subaccount;

	/** @var string test subaccount */
	protected $test_subaccount;

	/** @var string HPP form URL */
	protected $form_url;

	/** @var string test HPP form URL */
	protected $test_form_url;

	/** @var string HPP form type */
	protected $form_type;

	/** @var string HPP form type */
	protected $form_language;

	/** @var string whether AVS should be enabled/checked */
	protected $enable_avs;

	/** @var \WC_Realex_Redirect_API direct API instance */
	protected $api;


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
			WC_Realex_Redirect::GATEWAY_ID,
			wc_realex_redirect(),
			array(
				'method_title'       => __( 'Realex Payments HPP', 'woocommerce-gateway-realex-redirect' ),
				'method_description' => __( 'Take payments using the Realex Payments Hosted Payment Page (HPP). Maximise conversion with a payment page that adapts to any device with 15+ languages with multiple payment methods and currencies. Minimise fraud with 30+ easy to configure rules and reduce the cost of PCI Compliance with our PCI-DSS v3.2 HPP.', 'woocommerce-gateway-realex-redirect' ),
				'supports' => array(
					self::FEATURE_PRODUCTS,
					self::FEATURE_CARD_TYPES,
					self::FEATURE_CREDIT_CARD_CHARGE,
					self::FEATURE_CREDIT_CARD_CHARGE_VIRTUAL,
					self::FEATURE_CREDIT_CARD_AUTHORIZATION,
					self::FEATURE_CREDIT_CARD_CAPTURE,
					self::FEATURE_CREDIT_CARD_PARTIAL_CAPTURE,
					self::FEATURE_TOKENIZATION,
					self::FEATURE_CUSTOMER_ID,
					self::FEATURE_DETAILED_CUSTOMER_DECLINE_MESSAGES,
					self::FEATURE_REFUNDS,
					self::FEATURE_VOIDS,
					// TODO: Add Payment Method {CW 2017-09-08}
				 ),
				'payment_type' => self::PAYMENT_TYPE_CREDIT_CARD,
				'environments' => array(
					self::ENVIRONMENT_PRODUCTION => __( 'Production', 'woocommerce-gateway-realex-redirect' ),
					self::ENVIRONMENT_TEST       => __( 'Sandbox', 'woocommerce-gateway-realex-redirect' ),
				),
			)
		);

		// encrypt the shared secrete & rebate password on settings save
		add_filter( "woocommerce_settings_api_sanitized_fields_{$this->id}", array( $this, 'encrypt_credentials' ) );

		// add any previous failure notices
		add_action( 'before_woocommerce_pay', array( $this, 'maybe_add_failed_order_notice' ) );

		// remove support for customer payment method changes
		add_action( 'wp_loaded', array( $this, 'set_subscriptions_change_payment_support' ) );

		// require checkout registration if tokenization is forced
		if ( Framework\SV_WC_Plugin_Compatibility::is_wc_version_gte_3_0() ) {
			add_filter( 'woocommerce_checkout_registration_required', array( $this, 'require_checkout_registration' ) );
		} else {
			add_action( 'woocommerce_checkout_init', array( $this, 'require_checkout_registration_legacy' ) );
		}
	}


	/**
	 * Removes support for subscriptions change payment, unless paying for a failed renewal.
	 *
	 * @since 2.0.0
	 */
	public function set_subscriptions_change_payment_support() {

		if ( $this->supports_subscriptions() && Framework\SV_WC_Helper::get_request( 'change_payment_method' ) ) {

			$this->remove_support( array(
				'subscription_payment_method_change_customer',
			) );
		}
	}


	/**
	 * Gets the method form fields.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::init_form_fields()
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_method_form_fields() {

		return array(

			// global fields
			'merchant_id' => array(
				'title' => __( 'Merchant ID', 'woocommerce-gateway-realex-redirect' ),
				'type'  => 'text',
			),
			'shared_secret' => array(
				'title'    => __( 'Shared Secret', 'woocommerce-gateway-realex-redirect' ),
				'type'     => 'password',
				'desc_tip' => __( 'The shared secret for your account, provided by Realex.', 'woocommerce-gateway-realex-redirect' ),
			),
			'rebate_password' => array(
				'title'    => __( 'Rebate Password', 'woocommerce-gateway-realex-redirect' ),
				'type'     => 'password',
				'desc_tip' => __( 'The rebate password for your account, provided by Realex.', 'woocommerce-gateway-realex-redirect' ),
			),

			// environment fields
			'subaccount' => array(
				'title'    => __( 'Default Subaccount', 'woocommerce-gateway-realex-redirect' ),
				'type'     => 'text',
				'class'    => 'environment-field production-field',
			),
			'test_subaccount' => array(
				'title'    => __( 'Default Subaccount', 'woocommerce-gateway-realex-redirect' ),
				'type'     => 'text',
				'class'    => 'environment-field test-field',
			),

			'form_url' => array(
				'title'   => __( 'Form URL', 'woocommerce-gateway-realex-redirect' ),
				'type'    => 'text',
				'class'   => 'environment-field production-field',
				'default' => self::HPP_URL,
			),
			'test_form_url' => array(
				'title'   => __( 'Form URL', 'woocommerce-gateway-realex-redirect' ),
				'type'    => 'text',
				'class'   => 'environment-field test-field',
				'default' => self::HPP_URL_TEST,
			),

			'form_type' => array(
				'title'   => __( 'Form Type', 'woocommerce-gateway-realex-redirect' ),
				'type'    => 'select',
				'options' => array(
					self::FORM_TYPE_IFRAME   => __( 'iFrame', 'woocommerce-gateway-realex-redirect' ),
					self::FORM_TYPE_REDIRECT => __( 'Redirect', 'woocommerce-gateway-realex-redirect' ),
				),
				'default' => 'iframe',
			),

			'form_language' => array(
				'title'       => __( 'Form Language', 'woocommerce-gateway-realex-redirect' ),
				'type'        => 'text',
				'placeholder' => substr( get_locale(), 0, 2 ),
				'description' => sprintf(
					/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
					__( 'Leave this blank to use your site\'s language, configured in %1$sGeneral Settings%2$s.', 'woocommerce-gateway-realex-redirect' ),
					'<a href="' . esc_url( admin_url( 'options-general.php' ) ) . '">', '</a>'
				),
				'desc_tip'    => __( 'Configure to display the payment form in a specific language.', 'woocommerce-gateway-realex-redirect' ),
			),

			'enable_avs' => array(
				'title'   => __( 'Address Verification Service (AVS)', 'woocommerce-gateway-realex-redirect' ),
				'label'   => __( 'Perform an AVS check on customers\' billing addresses', 'woocommerce-gateway-realex-redirect' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),

		);
	}


	/**
	 * Adds the enable Card Security Code form fields.
	 *
	 * Overridden to remove the "Saved Card" CSC setting, as that's not used
	 * for the HPP.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form_fields gateway form fields
	 * @return array
	 */
	protected function add_csc_form_fields( $form_fields ) {

		$form_fields = parent::add_csc_form_fields( $form_fields );

		unset( $form_fields['enable_token_csc'] );

		return $form_fields;
	}


	/**
	 * Adds any tokenization form fields for the settings page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form_fields gateway form fields
	 * @return array
	 */
	protected function add_tokenization_form_fields( $form_fields ) {

		$form_fields = parent::add_tokenization_form_fields( $form_fields );

		$form_fields['tokenization_forced'] = array(
			'title'       => esc_html__( 'Require Tokenization', 'woocommerce-plugin-framework' ),
			'label'       => esc_html__( 'Automatically save customers\' credit card details to their account.', 'woocommerce-gateway-realex-redirect' ),
			'description' => esc_html__( 'If enabled, new customers will be required to create an account at checkout.', 'woocommerce-gateway-realex-redirect' ),
			'type'        => 'checkbox',
			'default'     => 'no',
		);

		return $form_fields;
	}


	/**
	 * Adds some extra JavaScript to show/hide certain settings.
	 *
	 * @since 2.0.0
	 */
	public function admin_options() {

		parent::admin_options();

		// add inline javascript
		ob_start();
		?>
			$( '#woocommerce_<?php echo esc_js( $this->get_id() ); ?>_tokenization' ).change( function() {

				var hidden_setting   = $( '#woocommerce_<?php echo esc_js( $this->get_id() ); ?>_tokenization_forced' ).closest( 'tr' );

				if ( $( this ).is( ':checked' ) ) {
					$( hidden_setting ).show();
				} else {
					$( hidden_setting ).hide();
				}

			} ).change();
		<?php

		wc_enqueue_js( ob_get_clean() );
	}


	/**
	 * Makes registration at checkout required if forced tokenization is enabled.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @param bool $required whether registration is already required
	 * @return bool
	 */
	public function require_checkout_registration( $required ) {

		return $required || ( $this->is_available() && $this->is_tokenization_forced() );
	}


	/**
	 * Makes registration at checkout required if forced tokenization is enabled in WC 2.6
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @param bool $required whether registration is already required
	 * @return bool
	 */
	public function require_checkout_registration_legacy( $checkout ) {

		if ( $this->is_available() && $this->is_tokenization_forced() && ! is_user_logged_in() ) {
			$checkout->enable_guest_checkout = false;
			$checkout->must_create_account   = true;
		}
	}


	/**
	 * Encrypt the Shared Secrete and Rebate Password on settings save.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings gateway settings
	 * @return array
	 */
	public function encrypt_credentials( $settings ) {

		$settings['shared_secret']   = $this->get_plugin()->encrypt_credential( $settings['shared_secret'] );
		$settings['rebate_password'] = $this->get_plugin()->encrypt_credential( $settings['rebate_password'] );

		return $settings;
	}


	/**
	 * Loads the plugin configuration settings.
	 *
	 * Overridden to decrypt the credentials.
	 *
	 * @since 2.0.0
	 */
	public function load_settings() {

		if ( isset( $this->settings['shared_secret'] ) ) {
			$this->settings['shared_secret']   = $this->get_plugin()->decrypt_credential( $this->settings['shared_secret'] );
		}

		if ( isset( $this->settings['rebate_password'] ) ) {
			$this->settings['rebate_password'] = $this->get_plugin()->decrypt_credential( $this->settings['rebate_password'] );
		}

		parent::load_settings();
	}


	/**
	 * Gets the default payment method title.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_default_title() {

		return __( 'Debit or Credit Card', 'woocommerce-gateway-realex-redirect' );
	}


	/**
	 * Gets the default payment method description.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_default_description() {

		return __( 'Pay securely using your debit or credit card.', 'woocommerce-gateway-realex-redirect' );
	}


	/**
	 * Determines if the gateway is properly configured to perform transactions.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	protected function is_configured() {

		return $this->get_merchant_id() && $this->get_shared_secret() && $this->get_hosted_pay_page_url();
	}


	/**
	 * Processes the payment.
	 *
	 * Overridden to add tokenized payment handling.
	 *
	 * @since 2.0.0
	 *
	 * @param int $order_id order ID
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = $this->get_order( $order_id );

		// if the payment has a token, try a direct tokenized payment
		if ( $this->tokenization_enabled() && ! empty( $order->payment->token ) ) {

			if ( $this->perform_credit_card_charge( $order ) ) {
				$response = $this->get_api()->credit_card_charge( $order );
			} else {
				$response = $this->get_api()->credit_card_authorization( $order );
			}

			if ( $response->transaction_approved() || $response->transaction_held() ) {

				$this->add_transaction_data( $order, $response );

				$this->add_payment_gateway_transaction_data( $order, $response );

				// handle the order status, etc...
				$this->complete_payment( $order, $response );

				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);

			} else {

				return $this->do_transaction_failed_result( $order, $response );
			}

		} else {

			return parent::process_payment( $order_id );
		}
	}


	/**
	 * Validates the transaction response data.
	 *
	 * Overridden to calculate and check the SHA-1 hash.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::validate_transaction_response()
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 *
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	protected function validate_transaction_response( $order, $response ) {

		parent::validate_transaction_response( $order, $response );

		$hash = $this->generate_sha1_hash( array(
			$response->get_timestamp(),
			$response->get_merchant_id(),
			$response->get_order_number(),
			$response->get_status_code(),
			$response->get_status_message(),
			$response->get_transaction_id(),
			$response->get_authorization_code(),
		), $this->get_shared_secret() );

		if ( ! hash_equals( $hash, $response->get_hash() ) ) {
			throw new Framework\SV_WC_Payment_Gateway_Exception( 'Invalid response data.' );
		}

		// if an existing customer with saved cards paid for the $0 order, the amount should be $0.02
		// TODO: remove this line and just use $order->payment_total once Realex's OTB bug is fixed {CW 2017-12-06}
		$amount = '0.00' === $order->payment_total && $order->customer_id ? '0.02' : $order->payment_total;

		// check that the response total matches the original order total
		if ( Framework\SV_WC_Helper::number_format( $response->get_amount() ) !== $amount ) {
			throw new Framework\SV_WC_Payment_Gateway_Exception( 'Transaction amount does not match order total.' );
		}

		status_header( 200 );
	}


	/**
	 * Processes a transaction response's token data, if any.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param SV_WC_Payment_Gateway_Payment_Notification_Tokenization_Response $response response object
	 * @return \WC_Order order object
	 */
	protected function process_tokenization_response( \WC_Order $order, $response ) {

		$order = parent::process_tokenization_response( $order, $response );

		// if a new customer ref was created, update its Realex Entry based on order data
		if ( $response->customer_creation_successful() && $order->customer_id ) {

			try {

				$this->get_api()->update_payer( $order->customer_id, array(
					'first_name' => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_first_name' ),
					'last_name'  => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_last_name' ),
					'company'    => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_company' ),
					'address_1'  => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_address_1' ),
					'address_2'  => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_address_2' ),
					'city'       => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_city' ),
					'state'      => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_state' ),
					'country'    => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_country' ),
					'postcode'   => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_postcode' ),
					'phone'      => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_phone' ),
					'email'      => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_email' ),
				) );

			} catch ( SV_WC_API_Exception $e ) {

			}
		}

		return $order;
	}


	/**
	 * Adds the transaction data to the order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	public function add_payment_gateway_transaction_data( $order, $response ) {

		$this->update_order_meta( $order, 'realex_id',    $response->get_order_number() );
		$this->update_order_meta( $order, 'payment_type', $response->get_payment_type() );

		if ( self::PAYMENT_TYPE_CREDIT_CARD === $response->get_payment_type() ) {

			$this->update_order_meta( $order, 'avs_result',         $response->get_avs_result() );
			$this->update_order_meta( $order, 'avs_address_result', $response->get_avs_address_result() );
			$this->update_order_meta( $order, 'csc_result',         $response->get_csc_result() );

			// add 3DSecure data if present
			if ( is_callable( array( $response, 'is_3dsecure' ) ) && $response->is_3dsecure() ) {

				$this->update_order_meta( $order, '3dsecure', 'yes' );

				$this->update_order_meta( $order, '3dsecure_eci',  $response->get_eci() );
				$this->update_order_meta( $order, '3dsecure_cavv', $response->get_cavv() );
				$this->update_order_meta( $order, '3dsecure_xid',  $response->get_xid() );
			}

			// add the DCC data if present
			if ( is_callable( array( $response, 'is_dcc' ) ) && $response->is_dcc() ) {

				$this->update_order_meta( $order, 'dcc_amount',   $response->get_dcc_amount() );
				$this->update_order_meta( $order, 'dcc_currency', $response->get_dcc_currency() );
				$this->update_order_meta( $order, 'dcc_rate',     $response->get_dcc_rate() );
			}
		}

		// set the core transaction ID meta to the Realex "order ID" for easier reference
		if ( $response->get_order_number() ) {
			update_post_meta( Framework\SV_WC_Order_Compatibility::get_prop( $order, 'id' ), '_transaction_id', $response->get_order_number() );
		}
	}


	/**
	 * Gets the order note message for approved credit card transactions.
	 *
	 * This is completely overridden because Realex requires specific information
	 * and formatting for this note.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Credit_Card_Response $response response object
	 * @return string
	 */
	public function get_credit_card_transaction_approved_message( \WC_Order $order, $response ) {

		$message = sprintf(
			/* translators: Placeholders: %1$s - payment method title, %2$s - environment ("Test"), %3$s - transaction type (authorization/charge) */
			__( '%1$s %2$s %3$s Approved', 'woocommerce-plugin-framework' ),
			$this->get_method_title(),
			$this->is_test_environment() ? esc_html_x( 'Test', 'noun, software environment', 'woocommerce-plugin-framework' ) : '',
			$this->perform_credit_card_authorization( $order ) ? esc_html_x( 'Authorization', 'credit card transaction type', 'woocommerce-plugin-framework' ) : esc_html_x( 'Charge', 'noun, credit card transaction type', 'woocommerce-plugin-framework' )
		) . '<br />';

		// add the transaction result codes
		$message .= $this->get_transaction_result_message( $order, $response );

		return $message;
	}


	/**
	 * Gets the order note message for approved eCheck transactions.
	 *
	 * @since 5.0.0-dev.1
	 *
	 * @param \WC_Order $order order object
	 * @param WC_Realex_Redirect_API_HPP_Response $response response object
	 * @return string
	 */
	public function get_paypal_transaction_approved_message( \WC_Order $order, WC_Realex_Redirect_API_HPP_Response $response ) {

		$last_four = ! empty( $order->payment->last_four ) ? $order->payment->last_four : substr( $order->payment->account_number, -4 );

		/* translators: Placeholders: %1$s - payment method title */
		$message = sprintf( __( '%1$s PayPal Transaction Approved', 'woocommerce-gateway-realex-redirect' ), $this->get_method_title() );

		// adds the transaction id (if any) to the order note
		if ( $response->get_transaction_id() ) {
			$message .= ' ' . sprintf( esc_html__( '(Transaction ID %s)', 'woocommerce-gateway-realex-redirect' ), $response->get_transaction_id() );
		}

		/**
		 * Filters the order note message for an approved PayPal transaction.
		 *
		 * @since 2.0.0
		 *
		 * @param string $message order note
		 * @param \WC_Order $order order object
		 * @param \WC_Realex_Redirect_API_HPP_Response $response transaction response object
		 * @param \WC_Gateway_Realex_Redirect $this gateway object
		 */
		return apply_filters( 'wc_' . $this->get_id() . '_paypal_transaction_approved_order_note', $message, $order, $response, $this );
	}


	/**
	 * Handles an approved transaction response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function do_transaction_approved( \WC_Order $order, $response ) {

		if ( is_callable( array( WC()->cart, 'empty_cart' ) ) ) {
			WC()->cart->empty_cart();
		}

		$this->add_transaction_result_order_notes( $order, $response );

		$this->do_transaction_response_result( $response, $this->get_return_url( $order ) );
	}


	/**
	 * Handles a held transaction response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function do_transaction_held( \WC_Order $order, $response ) {

		if ( is_callable( array( WC()->cart, 'empty_cart' ) ) ) {
			WC()->cart->empty_cart();
		}

		$this->add_transaction_result_order_notes( $order, $response );

		$this->do_transaction_response_result( $response, $this->get_return_url( $order ) );
	}


	/**
	 * Handles a cancelled transaction response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function do_transaction_cancelled( \WC_Order $order, $response ) {

		$this->add_transaction_result_order_notes( $order, $response );

		$this->do_transaction_response_result( $response, $order->get_cancel_order_url() );
	}


	/**
	 * Handles a failed transaction response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function do_transaction_failed( \WC_Order $order, $response ) {

		$this->add_failed_order_message( $order, $response );

		$this->add_transaction_result_order_notes( $order, $response );

		$this->do_transaction_response_result( $response, $order->get_checkout_payment_url() );
	}


	/**
	 * Handles an invalid transaction response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function do_invalid_transaction_response( $order = null, $response ) {

		$this->add_failed_order_message( $order, $response );

		$redirect_url = ( $order ) ? $order->get_checkout_payment_url() : home_url( '/' );

		$this->do_transaction_response_result( $response, $redirect_url );
	}


	/**
	 * Adds the transaction result codes as order notes.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 */
	protected function add_transaction_result_order_notes( \WC_Order $order, \WC_Realex_Redirect_API_HPP_Response $response ) {

		if ( self::PAYMENT_TYPE_CREDIT_CARD === $response->get_payment_type() ) {

			// always add the validation (AVS & CSC) result codes
			$message = sprintf(
				/* translators: Placeholders: %s - validation result codes,  */
				__( 'Realex Validation Results:%s', 'woocommerce-gateway-realex-redirect' ),
				'<br />AVS: ' . $response->get_avs_result() . '<br />AVS Address: ' . $response->get_avs_address_result() . '<br />CSC: ' . $response->get_csc_result()
			);

			$order->add_order_note( $message );

			// add an order note for the Fraud Filter result
			if ( is_callable( array( $response, 'get_fraud_result' ) ) ) {

				$message = sprintf(
					/* translators: Placeholders: %s - Fraud filter result code, such as "PASS" */
					__( 'Realex Fraud Filter Result: %s', 'woocommerce-gateway-realex-redirect' ),
					( $response->get_fraud_result() ) ? $response->get_fraud_result() : __( 'N/A', 'woocommerce-gateway-realex-redirect' )
				);

				$order->add_order_note( $message );
			}

			// add 3DSecure data if present
			if ( is_callable( array( $response, 'is_3dsecure' ) ) && $response->is_3dsecure() ) {

				$message = sprintf(
					/* translators: Placeholders: %s - 3DSecure result codes,  */
					__( 'Realex 3DSecure Results:%s', 'woocommerce-gateway-realex-redirect' ),
					'<br />ECI: ' . $response->get_eci() . '<br />CAVV: ' . $response->get_cavv() . '<br />XID: ' . $response->get_xid()
				);

				$order->add_order_note( $message );
			}

			// add the DCC data if present
			if ( is_callable( array( $response, 'is_dcc' ) ) && $response->is_dcc() ) {

				$message = sprintf(
					/* translators: Placeholders: %1$s - payment total, %2$s - currency code, such as USD */
					__( 'Realex customer chose to pay a converted total of %1$s %2$s', 'woocommerce-gateway-realex-redirect' ),
					wc_price( $response->get_dcc_amount(), array( 'currency' => $response->get_dcc_currency() ) ),
					$response->get_dcc_currency()
				);

				$order->add_order_note( $message );
			}
		}
	}


	/**
	 * Adds a failed order message to the session for display later.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \SV_WC_Payment_Gateway_API_Payment_Notification_Response $response response object
	 */
	protected function add_failed_order_message( $order = null, $response ) {

		if ( $order ) {

			$message = $response->get_user_message();

			if ( ! $message || ! $this->is_detailed_customer_decline_messages_enabled() ) {
				$message = __( 'The transaction was unsuccessful, please try again or use an alternate form of payment.', 'woocommerce-gateway-realex-redirect' );
			}

			$this->update_order_meta( $order, 'failed_message', $message );
		}
	}


	/**
	 * Handles the final action after the response is processed.
	 *
	 * For an IPN, this means sending a redirect URL via JSON. For a redirect
	 * response, this means a straight redirect.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Realex_Redirect_API_HPP_Response $response response object
	 * @param string $redirect_url the final destination after processing
	 */
	protected function do_transaction_response_result( WC_Realex_Redirect_API_HPP_Response $response, $redirect_url ) {

		$this->output_response_redirect_js( $redirect_url );
		exit;
	}


	/**
	 * Renders the JS for handling the redirect after a payment response is posted by the HPP.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url the URL to which to redirect
	 */
	protected function output_response_redirect_js( $url ) {

		echo '<script type="text/javascript">window.top.location.href = "' . esc_url_raw( $url ) . '"</script>';
	}


	/**
	 * Adds a failed order notice (such as a decline) if one exists.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 */
	public function maybe_add_failed_order_notice() {

		$order = wc_get_order( $this->get_checkout_pay_page_order_id() );

		if ( ! $order ) {
			return;
		}

		if ( $message = $this->get_order_meta( $order, 'failed_message' ) ) {

			Framework\SV_WC_Helper::wc_add_notice( $message, 'error' );

			$this->delete_order_meta( $order, 'failed_message' );
		}
	}


	/**
	 * Marks the given order as failed and set the order note.
	 *
	 * @since 2.0.0
	 *
	 * @param WC_Order $order the order
	 * @param string $error_message a message to display inside the "Payment Failed" order note
	 * @param SV_WC_Payment_Gateway_API_Response optional $response the transaction response object
	 */
	public function mark_order_as_failed( $order, $error_message, $response = null ) {

		/* translators: Placeholders: %s - payment gateway title */
		$message = sprintf( esc_html__( '%s Payment Failed:', 'woocommerce-plugin-framework' ), $this->get_method_title() ) . '<br />';

		if ( $this->get_transaction_result_message( $order, $response ) ) {
			$message .= $this->get_transaction_result_message( $order, $response );
		} else {
			$message .= $error_message;
		}

		$order->add_order_note( $message );

		$order->update_status( 'failed' );

		$this->add_debug_message( $error_message, 'error' );
	}


	/**
	 * Gets the message listing all of the transaction details.
	 *
	 * This is added to the order note, regardless of the outcome.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \SV_WC_Payment_Gateway_API_Response $response response object
	 * @return string
	 */
	protected function get_transaction_result_message( \WC_Order $order, $response ) {

		$message = '';

		if ( ! $response ) {
			return $message;
		}

		/* translators: Placeholders: %s - Realex order ID */
		$message = sprintf( __( 'Order ID: %s', 'woocommerce-gateway-realex-redirect' ), $response->get_order_number() ) . '<br />';
		/* translators: Placeholders: %s - Realex transaction result code */
		$message .= sprintf( __( 'Result: %s', 'woocommerce-gateway-realex-redirect' ), $response->get_status_code() ) . '<br />';
		/* translators: Placeholders: %s - Realex transaction result message */
		$message .= sprintf( __( 'Message: %s', 'woocommerce-gateway-realex-redirect' ), $response->get_status_message() ) . '<br />';
		/* translators: Placeholders: %s - Realex transaction authorization code */
		$message .= sprintf( __( 'Auth Code: %s', 'woocommerce-gateway-realex-redirect' ), $response->get_authorization_code() ) . '<br />';

		if ( self::PAYMENT_TYPE_CREDIT_CARD === $response->get_payment_type() ) {

			if ( $order->payment->card_type ) {
				/* translators: Placeholders: %s - Credit card type, such as Visa or MasterCard */
				$message .= sprintf( __( 'Card Type: %s', 'woocommerce-gateway-realex-redirect' ), Framework\SV_WC_Payment_Gateway_Helper::payment_type_to_name( $order->payment->card_type ) ) . '<br />';
			}

			if ( $order->payment->account_number ) {
				/* translators: Placeholders: %s - Credit card type, such as Visa or MasterCard */
				$message .= sprintf( __( 'Card Digits: %s', 'woocommerce-gateway-realex-redirect' ), $order->payment->account_number ) . '<br />';
			}

			if ( $response->get_cardholder_name() ) {
				/* translators: Placeholders: %s - Credit card holder's name */
				$message .= sprintf( __( 'Card Name: %s', 'woocommerce-gateway-realex-redirect' ), $response->get_cardholder_name() ) . '<br />';
			}
		}

		return $message;
	}


	/**
	 * Don't empty the cart until successful payment is made.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	protected function empty_cart_before_redirect() {

		return false;
	}


	/**
	 * Renders the iframe pay page form.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param array $request_params key/value request params
	 */
	public function render_pay_page_form( $order, $request_params ) {

		$plugin_id = $this->get_id();

		// attempt to automatically submit the form and load the iframe
		// TODO: standalone JS file
		wc_enqueue_js('

			$( "body" ).block( {
				message: "",
				overlayCSS: {
					background: "#fff",
					opacity: 0.6
				},
			} );

			$( "#wc_' . esc_js( $plugin_id ) . '_iframe_form" ).submit();

			$("#wc_' . esc_js( $plugin_id ) . '_iframe").load( function() {

				$( "html, body" ).animate( {
					scrollTop: $( this ).offset().top
				}, 1000 );

				$( "body" ).unblock();

			} );

			$( window ).on( "message", function( event ) {

				var origin       = $( "<a>" ).prop( "href", event.originalEvent.origin ).prop( "hostname" );
				var valid_origin = $( "<a>" ).prop( "href", "' . esc_js( $this->get_hosted_pay_page_url() ) . '" ).prop( "hostname" );
				var response     = JSON.parse( event.originalEvent.data );

				if ( origin !== valid_origin ) {
					return;
				}

				if ( response && response.iframe ) {
					$( "#wc_' . esc_js( $plugin_id ) . '_iframe" ).attr( "height", response.iframe.height );
				}

			} );
		');

		echo '<form id="wc_' . esc_attr( $plugin_id ) . '_iframe_form" action="' . esc_url( $this->get_hosted_pay_page_url( $order ) ) . '" method="post" target="wc_' . esc_attr( $plugin_id ) . '_iframe">';
			echo $this->get_auto_post_form_params_html( $request_params );
		echo '</form>';

		echo '<iframe id="wc_' . esc_attr( $plugin_id ) . '_iframe" name="wc_' . esc_attr( $plugin_id ) . '_iframe" width="100%" height="360px"></iframe>';

		echo '<a id="wc_' . esc_attr( $plugin_id ) . '_iframe_cancel"class="button cancel" href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Cancel', 'woocommerce-gateway-realex-redirect' ) . '</a>';
	}


	/**
	 * Determines whether to use the auto-post form.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function use_auto_form_post() {
		return parent::use_auto_form_post() && self::FORM_TYPE_IFRAME !== $this->get_form_type();
	}


	/**
	 * Gets the hosted pay page parameters.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	public function get_hosted_pay_page_params( $order ) {

		$params = array(
			'TIMESTAMP'                => date( 'Ymdhis' ),
			'MERCHANT_ID'              => $this->get_merchant_id(),
			'ORDER_ID'                 => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_]*/', '', $order->unique_transaction_ref ), 40, '' ),
			'X_ORDER_ID'               => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'id' ),
			'AMOUNT'                   => $order->payment_total * 100,
			'CURRENCY'                 => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'currency', 'view' ),
			'AUTO_SETTLE_FLAG'         => $this->perform_credit_card_authorization( $order ) && $this->is_partial_capture_enabled() ? 'MULTI' : (int) $this->perform_credit_card_charge( $order ),
			'CHANNEL'                  => 'ECOM',
			'COMMENT1'                 => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', $order->description ), 255 ),
			'BILLING_CO'               => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_country' ),
			'HPP_LANG'                 => $this->get_form_language(),
			'HPP_VERSION'              => self::HPP_VERSION,
			'HPP_DISPLAY_CVN'          => $this->csc_enabled() ? 'true' : 'false', // yes, this must be a string
			'HPP_CUSTOMER_EMAIL'       => Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_email' ),
			'HPP_CUSTOMER_FIRSTNAME'   => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_first_name' ) ), 30, '' ),
			'HPP_CUSTOMER_LASTNAME'    => Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^A-Za-z0-9-_+ \'".,]*/', '', Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_last_name' ) ), 50, '' ),
			'HPP_CUSTOMER_PHONENUMBER' => preg_replace( '/\D/', '', Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_phone' ) ), // digits only
			'MERCHANT_RESPONSE_URL'    => $this->get_transaction_response_handler_url(),
		);

		if ( $subaccount = $this->get_subaccount() ) {
			$params['ACCOUNT'] = $subaccount;
		}

		// add the AVS params if enabled
		if ( $this->is_avs_enabled() ) {
			$params = array_merge( $params, $this->get_hpp_avs_params( $order ) );
		}

		// add the shipping info if needed
		if ( Framework\SV_WC_Order_Compatibility::has_shipping_address( $order ) ) {
			$params['SHIPPING_CODE'] = Framework\SV_WC_Order_Compatibility::get_prop( $order, 'shipping_postcode' );
			$params['SHIPPING_CO']   = Framework\SV_WC_Order_Compatibility::get_prop( $order, 'shipping_country' );
		}

		// add the tokenization params if enabled
		if ( $this->tokenization_enabled() ) {
			$params = array_merge( $params, $this->get_hpp_tokenization_params( $order ) );
		}

		if ( is_user_logged_in() ) {
			$params['CUST_NUM'] = get_current_user_id();
		}

		// if configured to be an iframe, add the response URLs for JS events
		if ( self::FORM_TYPE_IFRAME === $this->get_form_type() ) {
			$params['HPP_POST_DIMENSIONS'] = home_url();
		}

		/**
		 * Filters the button text displayed in the hosted payment form.
		 *
		 * The default for this filter is intentionally empty to let Realex
		 * determine the button text based on the account language defaults.
		 *
		 * @since 2.0.0
		 *
		 * @param string $text button text
		 * @param array $params payment form request parameters
		 * @param \WC_Order $order order object
		 */
		$button_text = apply_filters( 'wc_realex_redirect_hosted_payment_form_button_text', '', $params, $order );

		if ( $button_text ) {
			$params['CARD_PAYMENT_BUTTON'] = $button_text;
		}

		/**
		 * Filters the hosted payment form parameters.
		 *
		 * @since 2.0.0
		 *
		 * @param array $params payment form request parameters
		 * @param \WC_Order $order order object
		 */
		$params = apply_filters( 'wc_realex_redirect_hpp_params', $params, $order );

		$hash_params = array(
			$params['TIMESTAMP'],
			$params['MERCHANT_ID'],
			$params['ORDER_ID'],
			$params['AMOUNT'],
			$params['CURRENCY'],
		);

		if ( $this->tokenization_enabled() && ( isset( $params['CARD_STORAGE_ENABLE'] ) || isset( $params['PAYER_EXIST'] ) ) ) {
			$hash_params[] = isset( $params['HPP_SELECT_STORED_CARD'] ) ? $params['HPP_SELECT_STORED_CARD'] : $params['PAYER_REF'];
			$hash_params[] = ''; // intentionally blank
		}

		$hash_params[] = $params['HPP_DISPLAY_CVN'];

		$params['SHA1HASH'] = $this->generate_sha1_hash( $hash_params, $this->get_shared_secret() );

		return $params;
	}


	/**
	 * Gets the params used for AVS checking.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_hpp_avs_params( \WC_Order $order ) {

		$params = array();

		$supported_countries = array(
			'US',
			'CA',
			'GB',
		);

		$billing_country = Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_country' );

		if ( in_array( $billing_country, $supported_countries, true ) ) {

			// format the postcode, strip all but numbers
			$postcode = preg_replace( '/[^0-9]/', '', Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_postcode' ) );
			$postcode = Framework\SV_WC_Helper::str_truncate( $postcode, 5, '' );

			$address = Framework\SV_WC_Order_Compatibility::get_prop( $order, 'billing_address_1' );

			// Non-US addresses should be numbers only, maximum of 5 characters
			if ( 'US' !== $billing_country ) {
				$address = Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^0-9]/', '', $address ), 5, '' );
			}

			// API max (30) minus the formatted postcode & separator
			$address_max_length = 30 - strlen( $postcode ) - 1;

			$address = Framework\SV_WC_Helper::str_truncate( $address, $address_max_length, '' );

			$params['BILLING_CODE'] = "{$postcode}|{$address}";
		}

		return $params;
	}


	/**
	 * Gets the HPP params used for tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_hpp_tokenization_params( \WC_Order $order ) {

		$order_id = Framework\SV_WC_Order_Compatibility::get_prop( $order, 'id' );
		$params   = array();

		$contains_subscription = $this->get_plugin()->is_subscriptions_active() && wcs_order_contains_subscription( $order_id );
		$contains_pre_order    = $this->get_plugin()->is_pre_orders_active() && \WC_Pre_Orders_Order::order_contains_pre_order( $order_id ) && \WC_Pre_Orders_Product::product_is_charged_upon_release( \WC_Pre_Orders_Order::get_pre_order_product( $order_id ) );

		if ( $order->get_user_id() || $contains_subscription || $contains_pre_order ) {

			$params = array(
				'OFFER_SAVE_CARD' => (int) ! $this->is_tokenization_forced(),
				'PAYER_EXIST'     => 0,
				'PAYER_REF'       => '',
			);

			if ( $order->customer_id ) {
				$params['PAYER_EXIST']            = 1;
				$params['HPP_SELECT_STORED_CARD'] = $order->customer_id;
			} else {
				$params['CARD_STORAGE_ENABLE'] = 1;
			}

			if ( '0.00' === $order->payment_total ) {

				if ( ! empty( $params['PAYER_EXIST'] ) ) { // TODO: remove this check and just set VALIDATE_CARD_ONLY once Realex's OTB bug is fixed {CW 2017-12-06}
					$params['AUTO_SETTLE_FLAG'] = 0;
					$params['AMOUNT']           = 2; // authorize for two pennies
				} else {
					$params['VALIDATE_CARD_ONLY'] = 1;
				}
			}

			if ( $contains_subscription || $contains_pre_order ) {

				$params['OFFER_SAVE_CARD'] = 0;
				$params['PM_METHODS']      = 'cards'; // only allow credit cards for force-tokenized orders
			}
		}

		return $params;
	}


	/**
	 * Generates a SHA-1 hash for some payment params.
	 *
	 * @since 2.0.0
	 *
	 * @param array $params payment params to build the hash
	 * @param string $secret shared account secret
	 */
	public static function generate_sha1_hash( $params, $secret ) {

		$hash = sha1( implode( '.', $params ) );

		return sha1( $hash . '.' . $secret );
	}


	/**
	 * Gets the hosted pay page url to redirect to.
	 *
	 * This method may be called more than once during a single request.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::get_hosted_pay_page_params()
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return string
	 */
	public function get_hosted_pay_page_url( $order = null ) {

		if ( self::ENVIRONMENT_TEST === $this->get_environment() ) {
			$url = ( $this->test_form_url ) ? $this->test_form_url : self::HPP_URL_TEST;
		} else {
			$url = ( $this->form_url ) ? $this->form_url : self::HPP_URL;
		}

		/**
		 * Filters the Hosted Payment Page URL.
		 *
		 * @since 2.0.0
		 *
		 * @param string $url HPP URL
		 * @param \WC_Gateway_Realex_Redirect $gateway gateway object
		 */
		return trim( apply_filters( 'wc_realex_redirect_hpp_endpoint_url', $url, $this ) );
	}


	/**
	 * Gets an API response object based on response request data.
	 *
	 * @since 2.0.0
	 *
	 * @param array $request_response_data request response data
	 * @return Framework\SV_WC_Payment_Gateway_API_Payment_Notification_Response response object
	 */
	protected function get_transaction_response( $request_response_data ) {

		if ( isset( $request_response_data['PAYMENTMETHOD'] ) ) {

			switch ( $request_response_data['PAYMENTMETHOD'] ) {

				case self::PAYMENT_TYPE_PAYPAL:
					$class = 'WC_Realex_Redirect_API_HPP_PayPal_Response';
				break;

				default:
					$class = 'WC_Realex_Redirect_API_HPP_Response';
			}

		} else {

			$class = $this->tokenization_enabled() ? 'WC_Realex_Redirect_API_HPP_Saved_Card_Response' : 'WC_Realex_Redirect_API_HPP_Credit_Card_Response';
		}

		return new $class( $request_response_data );
	}


	/**
	 * Gets the order object with transaction data added.
	 *
	 * @since 2.0.0
	 *
	 * @param int|\WC_Order $order_id order ID or object
	 * @return \WC_Order
	 */
	public function get_order( $order_id ) {

		$order = parent::get_order( $order_id );

		$order->payment->multisettle = $this->is_partial_capture_enabled();

		return $order;
	}


	/** Capture Methods *******************************************************/


	/**
	 * Initializes the capture handler instance.
	 *
	 * @since 2.1.2
	 */
	public function init_capture_handler() {

		require_once( $this->get_plugin()->get_plugin_path() . '/includes/Capture.php' );

		$this->capture_handler = new \SkyVerge\WooCommerce\Realex_HPP\Capture( $this );
	}


	/**
	 * Gets the order object, prepared for a capture transaction.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_order_for_capture()
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order|int $order order being processed
	 * @param float $amount amount to capture
	 * @return \WC_Order
	 */
	public function get_order_for_capture( $order, $amount = null  ) {

		$order = parent::get_order_for_capture( $order, $amount );

		$order->capture->multi              = $this->is_partial_capture_enabled();
		$order->capture->realex_id          = $this->get_order_meta( $order, 'realex_id' );
		$order->capture->authorization_code = $this->get_order_meta( $order, 'authorization_code' );

		return $order;
	}


	/** Refund Methods ********************************************************/


	/**
	 * Gets the order object, prepared for a refund transaction.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_order_for_refund()
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order|int $order order being processed
	 * @param float $amount refund amount
	 * @param string $reason optional refund reason text
	 * @return \WC_Order
	 */
	protected function get_order_for_refund( $order, $amount, $reason ) {

		// only credit card transactions support automatic refunds for now
		if ( self::PAYMENT_TYPE_CREDIT_CARD !== $this->get_order_meta( $order, 'payment_type' ) ) {

			$message = sprintf(
				/* translators: Placeholders: %s - a payment type name, such as Credit Card or PayPal */
				__( 'Automatic refunds are not supported for %s transactions. Please refund manually.', 'woocommerce-gateway-realex-redirect' ),
				Framework\SV_WC_Payment_Gateway_Helper::payment_type_to_name( $this->get_order_meta( $order, 'payment_type' ) )
			);

			return new WP_Error( 'wc_' . $this->get_id() . '_refund_unsupported_payment_type', $message );
		}

		if ( 0 == $amount ) {
			return new WP_Error( 'wc_' . $this->get_id() . '_refund_invalid_amount', __( 'Please enter a valid refund amount.', 'woocommerce-gateway-realex-redirect' ) );
		}

		$order = parent::get_order_for_refund( $order, $amount, $reason );

		$order->refund->realex_id = $this->get_order_meta( $order, 'realex_id' );

		// if this is a multisettle transaction that has been captured, use that transaction ID
		if ( $this->is_partial_capture_enabled() && $capture_ref = $this->get_order_meta( $order, 'capture_trans_id' ) ) {

			$order->refund->trans_id = $capture_ref;

			$order->refund->realex_id = "_multisettle_{$order->refund->realex_id}";
		}

		$order->refund->authorization_code = $this->get_order_meta( $order, 'authorization_code' );
		$order->refund->password           = $this->get_rebate_password();

		return $order;
	}


	/**
	 * Gets the direct API instance.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Realex_Redirect_API
	 */
	public function get_api() {

		if ( ! $this->api ) {

			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-realex-redirect-api.php' );

			// request classes
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/requests/class-wc-realex-redirect-api-request.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/requests/class-wc-realex-redirect-api-payment-request.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/requests/class-wc-realex-redirect-api-transaction-request.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/requests/class-wc-realex-redirect-api-stored-card-request.php' );

			// response classes
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/responses/class-wc-realex-redirect-api-response.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/responses/class-wc-realex-redirect-api-payment-response.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/responses/class-wc-realex-redirect-api-transaction-response.php' );
			require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/responses/class-wc-realex-redirect-api-stored-card-response.php' );

			$this->api = new WC_Realex_Redirect_API( $this->get_environment(), $this->get_merchant_id(), $this->get_subaccount(), $this->get_shared_secret() );
		}

		return $this->api;
	}


	/**
	 * Gets a user's customer ID.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_customer_id()
	 *
	 * @since 2.0.0
	 *
	 * @param int $user_id WordPress user ID
	 * @param array $args arguments
	 * @return string
	 */
	public function get_customer_id( $user_id, $args = array() ) {

		$args['autocreate'] = false;

		return parent::get_customer_id( $user_id, $args );
	}


	/**
	 * Determines whether tokenization is forced.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_tokenization_forced() {

		return $this->tokenization_enabled() && 'yes' === $this->tokenization_forced;
	}


	/**
	 * Gets the configured merchant ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_merchant_id() {

		return $this->merchant_id;
	}


	/**
	 * Gets the configured shared secret.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_shared_secret() {

		return $this->shared_secret;
	}


	/**
	 * Gets the configured rebate password.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_rebate_password() {

		return $this->rebate_password;
	}


	/**
	 * Gets the configured subaccount.
	 *
	 * @since 2.0.0
	 *
	 * @param string $environment_id environment ID
	 * @return string
	 */
	public function get_subaccount( $environment_id = null ) {

		if ( is_null( $environment_id ) ) {
			$environment_id = $this->get_environment();
		}

		$subaccount = self::ENVIRONMENT_TEST === $environment_id ? $this->test_subaccount : $this->subaccount;

		/**
		 * Filters the default subaccount used for Realex API transactions.
		 *
		 * @since 2.0.0
		 *
		 * @param string $subaccount default subaccount
		 * @param \WC_Gateway_Realex_Redirect $gateway gateway object
		 */
		return apply_filters( 'wc_realex_redirect_subaccount', $subaccount, $this );
	}


	/**
	 * Gets the configured form type.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_form_type() {

		return $this->form_type;
	}


	/**
	 * Gets the configured form language.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_form_language() {

		$language = $this->form_language;

		return ! empty( $language ) ? $language : substr( get_locale(), 0, 2 );
	}


	/**
	 * Determines if this gateway uses a form-post from the pay
	 * page to "redirect" to a hosted payment page.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function use_form_post() {

		return true;
	}


	/**
	 * Determines whether AVS is enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_avs_enabled() {

		return 'yes' === $this->enable_avs;
	}


}
