<?php

require_once __DIR__ . '/PayProGateway.php';

class PayProSepaGateway extends PayProGateway {

	protected $id = 'paypro_sepa';

	public function getAdminLabel() {
		return __('PayPro Banktransfer', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Banktransfer', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'banktransfer/sepa-once';
	}
}