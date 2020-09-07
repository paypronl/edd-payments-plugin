<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_DirectDebit extends PayPro_EDD_Gateway_Abstract
{
	protected $id = 'paypro_sepa_once';

	public function getAdminLabel() {
		return __('PayPro Direct Debit', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('Direct Debit', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return 'directdebit/sepa-once';
	}
}
