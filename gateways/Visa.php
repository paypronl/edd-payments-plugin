<?php

require_once __DIR__ . '/PayProProductGateway.php';

class PayProVisaGateway extends PayProProductGateway {

	protected $id = 'paypro_visa';

	public function getAdminLabel() {
		return __('PayPro Visa', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Visa', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'creditcard/visa';
	}
}