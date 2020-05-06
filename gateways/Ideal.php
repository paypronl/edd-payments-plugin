<?php

require_once __DIR__ . '/PayProGateway.php';
require_once __DIR__ . '/../paypro/PayProHelper.php';

class PayProIdealGateway extends PayProGateway {

    protected $id = 'paypro_ideal';

	public function __construct() {
	    parent::__construct();

		add_action('edd_' . $this->id. '_cc_form', [$this, 'form']);
		add_filter('edd_purchase_form_required_fields', [$this, 'requiredFields']);
	}

	public function getAdminLabel() {
		return __('PayPro iDEAL', 'edd-paypro-gateway');
	}

	public function getCheckoutLabel() {
		return __('iDEAL', 'edd-paypro-gateway');
	}

	public function getPayMethod() {
		return $_POST['paypro_gateway_ideal_issuer'];
	}

	public function requiredFields($requiredFields) {
		if (edd_get_chosen_gateway() === 'paypro_ideal') {
			$requiredFields['paypro_gateway_ideal_issuer'] = [
				'error_id' => 'paypro_gateway_ideal_issuer_required',
				'error_message' => __('Please select a bank.', 'edd-paypro-gateway'),
			];
		}

		return $requiredFields;
	}

	public function form() {
		$api = PayProHelper::getApiInstance();
		$issuers = $api->getIdealIssuers();

		ob_start(); ?>
		<fieldset>
			<legend><?php _e('Payment information', 'edd-paypro-gateway'); ?></legend>
			<p>
				<label class="edd-label" for="paypro_gateway_ideal_issuer">
					<?php _e('Bank', 'edd-paypro-gateway'); ?>
					<span class="edd-required-indicator">*</span>
				</label>
				<select name="paypro_gateway_ideal_issuer" class="edd-select required" required>
					<option value=""><?php _e('Select your bank', 'edd-paypro-gateway'); ?></option>
					<?php foreach ($issuers as $key => $label) { ?>
						<option value="<?php echo $key; ?>"><?php echo $label; ?></option>
					<?php } ?>
				</select>
			</p>
		</fieldset>
		<?php
		echo ob_get_clean();
	}
}