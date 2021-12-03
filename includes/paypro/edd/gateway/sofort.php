<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Sofort extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_sofort';

	public function getAdminLabel() {
		return __('PayPro Sofort', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Sofort', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'sofort/digital';
	}
}
