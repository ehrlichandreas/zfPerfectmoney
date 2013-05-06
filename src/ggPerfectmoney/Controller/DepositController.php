<?php
namespace ggPerfectmoney\Controller;

use Zend\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class DepositController extends AbstractActionController
{
    public function statusAction()
    {
        $testing = false;
        $apiKey = 'ahr2eeNohr7ka8ooToBi';

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if (isset($data['test']) && $data['test']) {
                $testing = true;
            }

            $form = $this->getStatusForm();
            $form->setData($data);

            if ($form->isValid()) {
                $output['status'] = 'OK';
            } else {
                $output['status'] = $form->getMessages();
            }
            $output['data'] = $form->getData();
        }

        return new JsonModel($output);
    }

    public function depositAction()
    {
        // display debugging form
        $form = $this->getPaymentForm();

        // set action
        $form->setAttribute('action', $this->url()->fromRoute('perfectmoney/status'));

        // add fake hash
        $form->add(new Form\Element\Text('V2_HASH'));
        $form->get('V2_HASH')->setValue('test');

        // unhide all elements
        foreach ($form->getElements() as $elem) {
            if ($elem->getAttribute('type') == 'hidden') {
                $elem->removeAttribute('type');
            }
            if ($elem->getAttribute('type') != 'submit') {
                $elem->setLabel($elem->getName());
            }
            // notify that it is testing
            else {
                $elem->setValue('Test');
            }
        }

        // draw amount
        $form->get('PAYMENT_AMOUNT')->setValue('10');

        // mark the form for testing
        $form->add(new Form\Element\Hidden('test'));
        $form->get('test')->setValue('1');

        return array('form' => $form);
    }

    /**
     * @return \ggPerfectmoney\Form\PaymentForm
     */
    public function getPaymentForm()
    {
        return $this->getServiceLocator()->get('ggperfectmoney_payment_form');
    }

    /**
     * @return \ggPerfectmoney\Form\StatusForm
     */
    public function getStatusForm()
    {
        return $this->getServiceLocator()->get('ggperfectmoney_status_form');
    }
}