<?php

class PayProApiHelper
{
    /**
     * Paypro api responses
     */
    public const PAYPRO_API_RES_APIKEY_INVALID = "API key not valid";
    public const PAYPRO_API_RES_NOT_SUBSCRIBED = "Not subscribed to money transfer service";

    var $apiKey;
    var $api;

    var $testMode;

    public function __construct() {}

    public function init($apiKey, $testMode = false)
    {
        $this->api = new \PayPro\Client($apiKey);
        $this->testMode = $testMode ? true : false;
    }

    public function getIdealIssuers()
    {
        $this->api->setCommand('get_all_pay_methods');
        $result = $this->execute();

        $result['issuers'] = $result['data']['data']['ideal']['methods'];
        unset($result['data']);
        return $result;
    }

    public function createPayment(array $data)
    {
        $this->api->setCommand('create_payment');
        $this->api->setParams($data);
        return $this->execute();
    }

    public function getSaleStatus($payment_hash)
    {
        $this->api->setCommand('get_sale');
        $this->api->setParam('payment_hash', $payment_hash);
        return $this->execute();
    }

    private function execute()
    {
        if($this->testMode) $this->api->setParam('test_mode', 'true'); else $this->api->setParam('test_mode', 'false');

        try {
            $result = $this->api->execute();

            if ($result['return'] === self::PAYPRO_API_RES_APIKEY_INVALID) $result['errors'] = 'true';

        } catch (\Exception $exception) {
            $result = array('errors' => 'true', 'return' => 'Invalid return from api');
        }

        if(isset($result['errors']))
        {
            if(strcmp($result['errors'], 'false') === 0)
                return array('errors' => false, 'data' => $result['return']);
            else
                return array('errors' => true, 'message' => $result['return']);
        }
        else
        {
            return array('errors' => true, 'message' => 'Invalid return from the API');
        }
    }
}
