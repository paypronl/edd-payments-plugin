<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Mastercard extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_mastercard';

	public function getAdminLabel() {
		return __('PayPro Mastercard', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Mastercard', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'creditcard/mastercard';
	}
}
