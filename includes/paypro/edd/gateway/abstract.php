<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

abstract class PayPro_EDD_Gateway_Abstract
{
	protected $id;
	protected $requiredBillingAddress = false;

	public function __construct() {
		add_action('edd_purchase_form_before_cc_form', [$this, 'beforeForm']);
		add_action('edd_' . $this->id . '_cc_form', 'edd_default_cc_address_fields');

		add_action('edd_gateway_' . $this->id, [$this, 'processPayment']);
		add_filter('edd_payment_confirm_' . $this->id, [$this, 'confirmPage']);
	}

	public function getID() {
		return $this->id;
	}

	public function isAvailable() {
		$currency = edd_get_currency();
		return $currency === 'EUR' && PayPro_EDD_Plugin::$settings->apiKey();
	}

	public function beforeForm() {
		if (edd_get_chosen_gateway() === $this->id) {
			add_filter('edd_require_billing_address', $this->requiredBillingAddress ? '__return_true' : '__return_false');
		}
	}

	public function processPayment($purchaseData) {
        $errors = edd_get_errors();
		if (!$errors) {
			$payment = array_merge($purchaseData, [
				'currency'     => edd_get_currency(),
				'status'       => 'pending'
			]);

			$eddPaymentID = edd_insert_payment($payment);
			$redirectUrl = add_query_arg([
				'payment-confirmation' => $purchaseData['gateway'],
				'payment-id' => $eddPaymentID,
			], get_permalink(edd_get_option('success_page', false)));

			$callbackUrl = get_site_url() . '/?edd-listener=PAYPRO&payment-id=' . $eddPaymentID;

			$payment = PayPro_EDD_Plugin::$paypro_api->createPayment([
				'amount' => round($payment['price'], 2) * 100,
				'pay_method' => $this->getPayMethod(),
				'return_url' => $redirectUrl,
				'cancel_url' => $redirectUrl,
				'postback_url' => $callbackUrl,
				'description' => get_bloginfo('name'),
				'locale' => PayPro_EDD_Plugin::getLocale(),
				'custom' => strval($eddPaymentID),
				'consumer_email' => $payment['post_data']['edd_email'],
				'consumer_firstname' => $payment['post_data']['edd_first'],
				'consumer_name' => $payment['post_data']['edd_last'],
				'consumer_address' => trim($payment['post_data']['card_address']. ' ' . $payment['post_data']['card_address_2']),
				'consumer_city' => $payment['post_data']['card_city'],
				'consumer_country' => $payment['post_data']['billing_country'],
				'consumer_postal' => $payment['post_data']['card_zip'],
			]);

			if (isset($payment['data']['payment_hash'])) {
				$paymentHash = $payment['data']['payment_hash'];

				edd_insert_payment_note($eddPaymentID, 'Payment Hash: ' . $paymentHash);
				edd_update_payment_meta($eddPaymentID, 'paypro_payment_hash', $paymentHash);

				EDD()->session->set('edd_resume_payment', $eddPaymentID);
				wp_redirect($payment['data']['payment_url']);
				exit;
			}
		}

		edd_set_error( 'edd_paypro_gateway_error', __('There was an issue processing your payment.', 'paypro-gateways-edd'));
		edd_send_back_to_checkout('?payment-mode=' . $purchaseData['post_data']['edd-gateway']);
	}

	public function confirmPage($content) {
		edd_empty_cart();

		if (isset($_GET['payment-id'])) {
			$payment = PayPro_EDD_Plugin::getSaleStatusOfPayment(intval($_GET['payment-id']));

			if ($payment) {
				ob_start();
				edd_get_template_part('payment', 'processing');
				$content = ob_get_clean();
			}
		}

		return $content;
	}

	abstract public function getAdminLabel();

	abstract public function getCheckoutLabel();

	abstract public function getPayMethod();
}
