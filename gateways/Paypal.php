<?php

require_once __DIR__ . '/PayProGateway.php';

class PayProPaypalGateway extends PayProGateway {

	protected $id = 'paypro_paypal';

	public function getAdminLabel() {
		return __('PayPro PayPal', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('PayPal', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'paypal/direct';
	}
}