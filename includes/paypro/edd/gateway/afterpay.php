<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Afterpay extends PayPro_EDD_Gateway_Abstract
{
    protected $id = 'paypro_afterpay';
    protected $requiredBillingAddress = true;

	public function getAdminLabel() {
		return __('PayPro Afterpay', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Afterpay', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'afterpay/giro';
	}
}
