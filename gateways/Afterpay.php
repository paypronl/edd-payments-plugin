<?php

require_once __DIR__ . '/PayProGateway.php';

class PayProAfterpayGateway extends PayProGateway {

	protected $id = 'paypro_afterpay';
	protected $requiredBillingAddress = true;

	public function getAdminLabel() {
		return __('PayPro Afterpay', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Afterpay', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'afterpay/giro';
	}
}
