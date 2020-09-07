<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Paypal extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_paypal';

	public function getAdminLabel() {
		return __('PayPro PayPal', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('PayPal', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'paypal/direct';
	}
}
