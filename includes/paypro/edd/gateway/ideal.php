<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPro_EDD_Gateway_Ideal extends PayPro_EDD_Gateway_Abstract
{
    protected $id = 'paypro_ideal';

	public function __construct() {
	    parent::__construct();

		add_action('edd_' . $this->id. '_cc_form', [$this, 'form']);
		add_filter('edd_purchase_form_required_fields', [$this, 'requiredFields']);
	}

	public function getAdminLabel() {
		return __('PayPro iDEAL', 'paypro-gateways-edd');
	}

	public function getCheckoutLabel() {
		return __('iDEAL', 'paypro-gateways-edd');
	}

	public function getPayMethod() {
		return $_POST['paypro_gateway_ideal_issuer'];
	}

	public function requiredFields($requiredFields) {
		if (edd_get_chosen_gateway() === 'paypro_ideal') {
			$requiredFields['paypro_gateway_ideal_issuer'] = [
				'error_id' => 'paypro_gateway_ideal_issuer_required',
				'error_message' => __('Please select a bank.', 'paypro-gateways-edd'),
			];
		}

		return $requiredFields;
	}

	public function form() {
		$issuers = PayPro_EDD_Plugin::$paypro_api->getIdealIssuers();

		ob_start(); ?>
		<fieldset>
			<legend><?php _e('Payment information', 'paypro-gateways-edd'); ?></legend>
			<p>
				<label class="edd-label" for="paypro_gateway_ideal_issuer">
					<?php _e('Bank', 'paypro-gateways-edd'); ?>
					<span class="edd-required-indicator">*</span>
				</label>
				<select name="paypro_gateway_ideal_issuer" class="edd-select required" required>
					<option value=""><?php _e('Select your bank', 'paypro-gateways-edd'); ?></option>
					<?php foreach ($issuers['issuers'] as $issuer) { ?>
						<option value="<?php echo $issuer['id']; ?>"><?php echo $issuer['name']; ?></option>
					<?php } ?>
				</select>
			</p>
		</fieldset>
		<?php
		echo ob_get_clean();
	}
}
