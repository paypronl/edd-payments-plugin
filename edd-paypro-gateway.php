<?php
/**
 * Plugin Name:       Easy Digital Downloads - PayPro Gateway
 * Plugin URI:        https://paypro.nl
 * Description:       Accept payments via PayPro in your EDD store
 * Version:           1.0.0
 * Requires at least: 4.7
 * Requires PHP:      5.6
 * Author:            PayPro
 * Author URI:        https://paypro.nl
 * Text Domain:       edd-paypro-gateway
 * Domain Path:       /languages
 *
 * @param $settings
 *
 * @return array
 */

require_once __DIR__ . '/paypro/PayProHelper.php';

function edd_paypro_gateway_plugins_loaded() {
	load_plugin_textdomain('edd-paypro-gateway', false, basename(dirname( __FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'edd_paypro_gateway_plugins_loaded');

/**
 * @param $sections
 *
 * @return mixed
 */
function edd_paypro_gateway_register_gateway_section($sections) {
	$sections['paypro'] = __('PayPro', 'edd-paypro-gateway');

	return $sections;
}
add_filter('edd_settings_sections_gateways', 'edd_paypro_gateway_register_gateway_section');

/**
 * Register the settings to the Payment Gateways section
 *
 * @param $settings
 *
 * @return array
 */
function edd_paypro_gateway_register_settings($settings) {
	return array_merge($settings, [
		'paypro' => [
			[
				'id' => 'edd_paypro_gateway_settings',
				'name' => '<strong>' . __('PayPro Settings', 'edd-paypro-gateway') . '</strong>',
				'desc' => __( 'Configure the gateway', 'edd-paypro-gateway'),
				'type' => 'header'
			],
			[
				'id' => 'edd_paypro_gateway_api_key',
				'name' => __('API Key', 'edd-paypro-gateway'),
				'desc' => __('Enter your PayPro API Key', 'edd-paypro-gateway'),
				'type' => 'text',
				'size' => 'regular'
			],
			[
				'id' => 'edd_paypro_gateway_product_id_help',
				'desc' => __('The Product ID is required when using MasterCard, Visa or Sofort payment methods', 'edd-paypro-gateway'),
				'type' => 'descriptive_text',
			],
			[
				'id' => 'edd_paypro_gateway_product_id',
				'name' => __('Product ID', 'edd-paypro-gateway'),
				'desc' => __('Enter your PayPro Product ID', 'edd-paypro-gateway'),
				'type' => 'text',
				'size' => 'regular',
			],
		],
	]);
}
add_filter('edd_settings_gateways', 'edd_paypro_gateway_register_settings');

function edd_paypro_gateway_register_gateways($gateways) {
	foreach (PayProHelper::getGateways() as $gateway) {
		if ($gateway->isAvailable()) {
			$gateways[$gateway->getID()] = [
				'admin_label' => $gateway->getAdminLabel(),
				'checkout_label' => $gateway->getCheckoutLabel(),
			];
		}
	}

	return $gateways;
}
add_filter('edd_payment_gateways', 'edd_paypro_gateway_register_gateways');

/**
 * Handle PayPro callback
 */
function edd_paypro_gateway_callback() {
	if (isset($_GET['edd-listener']) && $_GET['edd-listener'] === 'PAYPRO') {
		if (isset($_GET['payment-id'])) {
			$eddPayment = PayProHelper::processPayment(intval($_GET['payment-id']));
			if ($eddPayment) {
				wp_send_json(['success' => true]);
				exit;
			}
		}

		wp_send_json(['success' => false]);
		exit;
	}
}

add_action('init', 'edd_paypro_gateway_callback');
