<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Visa extends PayPro_EDD_Gateway_Product
{
	protected $id = 'paypro_visa';

	public function getAdminLabel() {
		return __('PayPro Visa', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Visa', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'creditcard/visa';
	}
}
