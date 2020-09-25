<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Plugin
{
    const PLUGIN_ID = 'paypro-gateways-edd';
    const PLUGIN_TITLE = 'PayPro Gateways - Easy Digitial Downloads';
    const PLUGIN_VERSION = '1.0.3';

    public static $paypro_api;
    public static $settings;
    private static $gateways;

    private static $initialized = false;

    private function __construct() {}

    /**
     * Initalizes the plugin
     */
    public static function init()
    {
        if(self::$initialized)
            return;

        // Add filters and actions
        add_filter('edd_settings_sections_gateways', array(__CLASS__, 'register_gateway_section'));

        add_filter('edd_settings_gateways', array(__CLASS__, 'register_gateway_settings'));

        add_filter('edd_payment_gateways', array(__CLASS__, 'register_gateway_payment'));

        add_action('edd_paypro_notification', array(__CLASS__,'edd_paypro_notification'));

        // Initialize all PayPro classes we need
        self::$settings = new PayPro_EDD_Settings();
        self::$paypro_api = new PayProApiHelper();
        self::$paypro_api->init(self::$settings->apiKey(), edd_is_test_mode());

        $initialized = true;
    }

    /**
     * Callback function that gets called when PayPro redirects back to the site
     */
    public static function edd_paypro_notification() {
        if (isset($_GET['payment-id'])) {
            $eddPayment = self::getSaleStatusOfPayment(intval($_GET['payment-id']));
            if ($eddPayment) {
                wp_send_json(['success' => true]);
                exit;
            }
        }

        wp_send_json(['success' => false]);
        exit;
    }

    /**
     * Adds PayPro section
     */
    public static function register_gateway_section (array $sections)
    {
        $sections['paypro'] = __('PayPro', 'paypro-gateways-edd');

	    return $sections;
    }

    /**
     * Adds all PayPro gateways to the list of gateways
     */
    public static function register_gateway_payment (array $gateways)
    {
        foreach (self::getGateways() as $gateway) {
            if ($gateway->isAvailable()) {
                $gateways[$gateway->getID()] = [
                    'admin_label' => $gateway->getAdminLabel(),
                    'checkout_label' => $gateway->getCheckoutLabel(),
                ];
            }
        }

        return $gateways;
    }

    /**
     * Adds plugin settings to the checkout options
     */
    public static function register_gateway_settings (array $settings)
    {
        $paypro_settings = array(
            array(
                'id'         => self::getSettingId('title'),
				'name'       => '<strong>' . __('PayPro Settings', 'paypro-gateways-edd') . '</strong>',
				'desc'       => __( 'Configure the gateway', 'paypro-gateways-edd'),
                'type'       => 'header'
            ),
            array(
                'id'         => self::getSettingId('api-key'),
                'name'       => __('API key', 'paypro-gateways-edd'),
                'desc'       => __('Enter your PayPro API Key', 'paypro-gateways-edd'),
                'type'       => 'text',
				'size'       => 'regular'
            ),
            array(
				'id'         => self::getSettingId('product-id-help'),
				'desc'       => __('The Product ID is required when using MasterCard, Visa or Sofort payment methods', 'paypro-gateways-edd'),
				'type'       => 'descriptive_text',
            ),
            array(
                'id'         => self::getSettingId('product-id'),
                'name'       => __('Product ID', 'paypro-gateways-edd'),
                'desc'       => __('Enter your PayPro Product ID', 'paypro-gateways-edd'),
                'type'       => 'text',
				'size'       => 'regular'
            ),
        );

        return array_merge($settings, ['paypro' => $paypro_settings]);
    }

    public static function getGateways() {
        if (!self::$gateways) {
			self::$gateways = array(
                new PayPro_EDD_Gateway_Afterpay(),
                new PayPro_EDD_Gateway_BankTransfer(),
                new PayPro_EDD_Gateway_DirectDebit(),
                new PayPro_EDD_Gateway_Ideal(),
                new PayPro_EDD_Gateway_Mastercard(),
                new PayPro_EDD_Gateway_Mistercash(),
                new PayPro_EDD_Gateway_Paypal(),
                new PayPro_EDD_Gateway_Sofort(),
                new PayPro_EDD_Gateway_Visa(),
            );
        }

        return self::$gateways;
    }

    public static function getSaleStatusOfPayment($paymentID) {
		$eddPayment = new EDD_Payment($paymentID);
		if ($eddPayment->ID > 0) {
			if ($eddPayment->status === 'pending') {
                $payment = self::$paypro_api->getSaleStatus(edd_get_payment_meta($paymentID, 'paypro_payment_hash'));

				if ($payment['data']['current_status'] === 'completed') {
					$eddPayment->update_status('completed');
				} else if ($payment['data']['current_status'] !== 'open') {
					$eddPayment->update_status('failed');
				}
			}

			return $eddPayment;
		}

		return null;
    }

     /**
      * Returns locale
      */
    public static function getLocale() {
		$wpLocale = get_locale();

		if (substr($wpLocale, 0, 2) === 'nl') {
			return 'NL';
		}

		return 'EN';
	}

    /**
     * Returns a setting ID by its name
     */
    public static function getSettingId ($setting)
    {
        return self::PLUGIN_ID . '_' . trim($setting);
    }
}
