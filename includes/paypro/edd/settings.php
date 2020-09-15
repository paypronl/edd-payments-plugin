<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Settings
{
    /**
     * Returns the API key
     */
    public function apiKey()
    {
        global $edd_options;
        return trim($this->getOption(PayPro_EDD_Plugin::getSettingId('api-key')));
    }

    /**
     * Returns the product id
     */
    public function productId()
    {
        return intval(trim($this->getOption(PayPro_EDD_Plugin::getSettingId('product-id'))));
    }

    private function getOption($key)
    {
        global $edd_options;
        return isset($edd_options[$key]) ? $edd_options[$key] : '';
    }
}
