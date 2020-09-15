<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_BankTransfer extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_sepa';

	public function getAdminLabel() {
		return __('PayPro Banktransfer', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Banktransfer', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'banktransfer/sepa';
	}
}
