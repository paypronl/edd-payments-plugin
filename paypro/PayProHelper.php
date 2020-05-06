<?php

require_once __DIR__ . '/PayProApi.php';
require_once __DIR__ . '/../gateways/Afterpay.php';
require_once __DIR__ . '/../gateways/Bancontact.php';
require_once __DIR__ . '/../gateways/Ideal.php';
require_once __DIR__ . '/../gateways/Mastercard.php';
require_once __DIR__ . '/../gateways/Paypal.php';
require_once __DIR__ . '/../gateways/Sepa.php';
require_once __DIR__ . '/../gateways/SepaOnce.php';
require_once __DIR__ . '/../gateways/Sofort.php';
require_once __DIR__ . '/../gateways/Visa.php';

class PayProHelper
{
	private static $gateways;
	private static $api;

	public static function getGateways() {
		if (!self::$gateways) {
			self::$gateways = [
				new PayProAfterpayGateway(),
				new PayProBancontactGateway(),
				new PayProIdealGateway(),
				new PayProMastercardGateway(),
				new PayProPaypalGateway(),
				new PayProSepaGateway(),
				new PayProSepaOnceGateway(),
				new PayProSofortGateway(),
				new PayProVisaGateway(),
			];
		}

		return self::$gateways;
	}

	public static function getApiInstance() {
		global $edd_options;

		if (!self::$api) {
			self::$api = new PayProApi($edd_options['edd_paypro_gateway_api_key'], $edd_options['edd_paypro_gateway_product_id'], edd_is_test_mode());
		}

		return self::$api;
	}

	public static function processPayment($paymentID) {
		$eddPayment = new EDD_Payment($paymentID);
		if ($eddPayment->ID > 0) {
			if ($eddPayment->status === 'pending') {
				$api = PayProHelper::getApiInstance();
				$payment = $api->getPayment(edd_get_payment_meta($paymentID, 'paypro_payment_hash'));

				if ($payment['current_status'] === 'completed') {
					$eddPayment->update_status('completed');
				} else if ($payment['current_status'] !== 'open') {
					$eddPayment->update_status('failed');
				}
			}

			return $eddPayment;
		}

		return null;
	}

	public static function getLocale() {
		$wpLocale = get_locale();

		if (substr($wpLocale, 0, 2) === 'nl') {
			return 'NL';
		}

		return 'EN';
	}
}
