<?php

namespace zfPerfectmoney\Form;

use Zend\InputFilter;
use Zend\Validator;

/**
 * This form may be displayed to enable debugging.
 *
 */
class StatusForm extends PaymentForm implements InputFilter\InputFilterProviderInterface
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttribute('action', $options['payment']['status_url']);

        $this->add(array(
            'name' => 'PAYER_ACCOUNT',
            'attributes' => array(
                'value' => 'U' . mt_rand()
            )
        ));
        $this->add(array(
            'name' => 'PAYMENT_ID',
            'attributes' => array(
                'value' => 'NULL'
            )
        ));
        $this->add(array(
            'name' => 'PAYMENT_BATCH_NUM',
            'attributes' => array(
                'value' => mt_rand()
            )
        ));
        $this->add(array(
            'name' => 'TIMESTAMPGMT',
            'attributes' => array(
                'value' => time()
            )
        ));
        $this->add(array(
            'name' => 'V2_HASH',
            'attributes' => array(
                'value' => strtoupper(md5(mt_rand()))
            )
        ));

        // display all hidden fields
        foreach ($this->getElements() as $element) {
            if (strtolower($element->getAttribute('type')) == 'hidden') {
                $element->removeAttribute('type');
            }
            if (!$element->getLabel()) {
                $element->setLabel($element->getName());
            }
        }

        // set amount for testing
        $this->get('PAYMENT_AMOUNT')->setValue(100);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'PAYEE_ACCOUNT' => array(
                'required' => true,
                'validators' => array(
                    new Validator\Identical($this->options['merchant']['UAccount'])
                )
            ),
            'PAYMENT_AMOUNT' => array(
                'required' => true,
                'validators' => array(
                    new Validator\GreaterThan(0)
                )
            ),
            'V2_HASH' => array(
                'required' => true,
                'validators' => array(
                    new Validator\Callback(array(
                        'callback' => function ($value, $context, $key) {
                            return $value === static::generateHash($context, $key)
                                || (isset($context['test']) && $context['test']);
                        },
                        'callbackOptions' => $this->options['merchant']['apiKey']
                    ))
                )
            )
        );
    }

    public function isTesting()
    {
        return isset($this->data['test']) && $this->data['test'];
    }

    public static function generateHash($values, $key)
    {
        $hash = strtoupper(md5(implode(':', array(
            $values['PAYMENT_ID'],
            $values['PAYEE_ACCOUNT'],
            $values['PAYMENT_AMOUNT'],
            $values['PAYMENT_UNITS'],
            $values['PAYMENT_BATCH_NUM'],
            $values['PAYER_ACCOUNT'],
            strtoupper(md5($key)),
            $values['TIMESTAMPGMT']
        ))));

        return $hash;
    }
}
