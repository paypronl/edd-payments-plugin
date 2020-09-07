<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

abstract class PayPro_EDD_Gateway_Product extends PayPro_EDD_Gateway_Abstract
{
	public function isAvailable() {
		return parent::isAvailable() && PayPro_EDD_Plugin::$settings->productId();
	}
}
