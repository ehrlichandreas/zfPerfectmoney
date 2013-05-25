<?php
namespace zfPerfectmoney;

use Zend\Http;
use Zend\Dom;

class Transaction
{
    protected $options;
    protected $payee;
    protected $amount;

    protected $httpClient;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function setPayee($payee)
    {
        $this->payee = $payee;
    }

    public function getPayee()
    {
        return $this->payee;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function request()
    {
        $httpClient = $this->getHttpClient();
        $httpClient->send();

        // DOM will not work here, because PM programmers does not
        // properly escape html values, e.g. they return
        // <input value='Can't login'>
        // which is understood as value="Can"
        $response = array();
        preg_match_all(
            "/<input name='(.*)' type='hidden' value='(.*)'>/",
            $httpClient->getResponse()->getBody(),
            $response,
            PREG_PATTERN_ORDER
        );
        $response = array_combine($response[1], $response[2]);

        if (isset($response['ERROR'])) {
            throw new \RuntimeException($response['ERROR']);
        }

        return $response;
    }

    /**
     * @return \Zend\Http\Client
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Http\Client();
            $this->httpClient->setUri($this->options['transaction']['url']);
            $this->httpClient->setOptions(array(
                'adapter'   => (new Http\Client\Adapter\Curl())->setCurlOption(CURLOPT_SSLVERSION, 3),
                'keepalive' => true
            ));
            $this->httpClient->setParameterGet(array(
                'AccountID'     => $this->options['merchant']['id'],
                'PassPhrase'    => $this->options['merchant']['password'],
                'Payer_Account' => $this->options['merchant']['UAccount'],
                'Payee_Account' => $this->getPayee(),
                'Amount'        => $this->getAmount()
            ));
        }

        return $this->httpClient;
    }
}