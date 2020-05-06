<?php

require_once __DIR__ . '/PayProGateway.php';

class PayProBancontactGateway extends PayProGateway {

	protected $id = 'paypro_bancontact';

	public function getAdminLabel() {
		return __('PayPro Bancontact', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('Bancontact', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return 'bankcontact/mistercash';
	}
}