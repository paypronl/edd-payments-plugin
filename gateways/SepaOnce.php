<?php

require_once __DIR__ . '/PayProGateway.php';

class PayProSepaOnceGateway extends PayProGateway {

	protected $id = 'paypro_sepa_once';

	public function getAdminLabel() {
		return __('PayPro Direct Debit', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Direct Debit', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'directdebit/sepa-once';
	}
}