<?php

namespace ggPerfectmoney\Form;

/**
 * This form may be displayed to enable debugging.
 *
 */
class StatusForm extends PaymentForm
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttribute('action', $options['payment']['status_url']);

        $this->add(array(
            'name' => 'PAYMENT_ID',
            'attributes' => array(
                'value' => 'NULL'
            )
        ));
        $this->add(array(
            'name' => 'PAYMENT_BATCH_NUM',
            'attributes' => array(
                'value' => mt_rand(1, PHP_INT_MAX)
            )
        ));
        $this->add(array(
            'name' => 'TIMESTAMPGMT',
            'attributes' => array(
                'value' => time()
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
    }
}
