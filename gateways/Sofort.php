<?php

require_once __DIR__ . '/PayProProductGateway.php';

class PayProSofortGateway extends PayProProductGateway {

	protected $id = 'paypro_sofort';

	public function getAdminLabel() {
		return __('PayPro Sofort', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Sofort', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'sofort/digital';
	}
}