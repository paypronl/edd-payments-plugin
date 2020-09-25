<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Plugin Name: PayPro Gateways - Easy Digital Downloads
 * Plugin URI: https://www.paypro.nl/
 * Description: With this plugin you easily add all PayPro payment gateways to your Easy Digital Downloads webshop.
 * Version: 1.0.3
 * Author: PayPro
 * Author URI: https://www.paypro.nl/
 * Text Domain: paypro-gateways-edd
 *
 * @author PayPro BV
 */

require_once('vendor/autoload.php');
require_once('includes/paypro/edd/autoload.php');

load_plugin_textdomain('paypro-gateways-edd', false, 'paypro-gateways-edd/languages');

paypro_plugin_init();

/**
 * Checks if Woocommerce is active, autoloads classes and initializes the plugin.
 */
function paypro_plugin_init()
{
    if(in_array('easy-digital-downloads/easy-digital-downloads.php', apply_filters('active_plugins', get_option( 'active_plugins'))) || class_exists('EasyDigitalDownloads'))
    {
        PayPro_EDD_Autoload::register();
        PayPro_EDD_Plugin::init();
    }
}

/**
 * Entry point of the plugin.
 * Handle PayPro callback
 */
function edd_listen_for_paypro_notification()
{
    if (isset($_GET['edd-listener']) && $_GET['edd-listener'] === 'PAYPRO') {
        do_action('edd_paypro_notification');
    }
}

/**
 * Is called when the plugin gets activated.
 * Checks if the the requirments are met and shows errors if not.
 */
function paypro_edd_plugin_activation()
{
    $errors = false;
    $error_list = '<table style="width: 600px;">';

    // Check if OpenSSL is activated
    $error_list .= '<tr>';
    if(function_exists('openssl_sign') && defined('OPENSSL_VERSION_TEXT'))
    {
        $error_list .= '<td>OpenSSL installed</td><td style="color: green;">Ok</td>';
    }
    else
    {
        $error_list .= '<td>OpenSSL not installed</td><td><span style="color: red;">Error</td>';
        $errors = true;
    }
    $error_list .= '</tr>';

    // Check if Curl is activated
    if(function_exists('curl_init'))
    {
        $error_list .= '<td>Curl installed</td><td style="color: green;">Ok</td>';
    }
    else
    {
        $error_list .= '<td>Curl not installed</td><td><span style="color: red;">Error</td>';
        $errors = true;
    }

    // Check if the Easy Digital Downloads plugin is active
    $error_list .= '<tr>';
    if (is_plugin_active('easy-digital-downloads/easy-digital-downloads.php'))
    {
        $error_list .= '<td>Easy Digital Downloads plugin is active</td><td style="color: green;">Ok</td>';

        $error_list .= '</tr><tr>';
    }
    else
    {
        $error_list .= '<td>Easy Digital Downloads plugin is not active</td><td style="color: red;">Error</td>';
        $errors = true;
    }

    $error_list .= '</tr></table>';

    // Show error page if there are errors
    if($errors)
    {
        $title = 'Could not activate plugin Easy Digital Downloads PayPro';
        $content = '<h1><strong>' . $title . '</strong></h1><br />' . $error_list;
        wp_die($content, $title, array('back_link' => true));
        return;
    }
}

register_activation_hook(__FILE__, 'paypro_edd_plugin_activation');

add_action('init', 'edd_listen_for_paypro_notification');
