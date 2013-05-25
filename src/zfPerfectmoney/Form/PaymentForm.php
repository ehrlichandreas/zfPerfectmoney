<?php

namespace zfPerfectmoney\Form;

use Zend\Form;

class PaymentForm extends Form\Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttribute('action', $options['payment']['api_url']);

        $this->add(array(
            'name' => 'PAYEE_ACCOUNT',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => $options['merchant']['UAccount']
            )
        ));
        $this->add(array(
            'name' => 'PAYEE_NAME',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $options['merchant']['name']
            )
        ));
        $this->add(array(
            'name' => 'PAYMENT_UNITS',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => $options['payment']['units']
            )
        ));
        $this->add(array(
            'name' => 'STATUS_URL',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $options['payment']['status_url']
            )
        ));
        $this->add(array(
            'name' => 'PAYMENT_URL',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $options['payment']['payment_url']
            )
        ));
        $this->add(array(
            'name' => 'NOPAYMENT_URL',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $options['payment']['nopayment_url']
            )
        ));
        $this->add(array(
            'name' => 'BAGGAGE_FIELDS',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'PAYMENT_AMOUNT'
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit'
            )
        ));

    }
}