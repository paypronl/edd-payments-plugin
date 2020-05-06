<?php

abstract class PayProProductGateway extends PayProGateway {

	public function isAvailable() {
		global $edd_options;

		return parent::isAvailable() && $edd_options['edd_paypro_gateway_product_id'];
	}
}
