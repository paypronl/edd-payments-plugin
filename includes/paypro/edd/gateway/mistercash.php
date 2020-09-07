<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Mistercash extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_bancontact';

	public function getAdminLabel() {
		return __('PayPro Bancontact', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Bancontact', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'bancontact/mrcash';
	}
}
