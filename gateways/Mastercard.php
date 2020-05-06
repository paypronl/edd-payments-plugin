<?php

require_once __DIR__ . '/PayProProductGateway.php';

class PayProMastercardGateway extends PayProProductGateway {

	protected $id = 'paypro_mastercard';

	public function getAdminLabel() {
		return __('PayPro Mastercard', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Mastercard', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'creditcard/mastercard';
	}
}