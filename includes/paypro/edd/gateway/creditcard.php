<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Creditcard extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_creditcard';

	public function getAdminLabel() {
		return __('PayPro Creditcard', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Creditcard', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'creditcard';
	}
}
