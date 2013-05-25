<?php
namespace zfPerfectmoney\Controller;

use Zend\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class DepositController extends AbstractActionController
{
    public function statusAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form = $this->getStatusForm();
            $form->setData($data);

            $this->getEventManager()->trigger('payment', $data);

            if ($form->isValid()) {
                // trigger event
                $this->getEventManager()->trigger('payment.success', $form);
                $output['status'] = 'OK';
            } else {
                $this->getResponse()->setStatusCode(500);
                $this->getEventManager()->trigger('payment.error', $form);
                $output['status'] = $form->getMessages();
            }
            $output['data'] = $form->getData();

            return new JsonModel($output);
        } else {
            $this->getResponse()->setStatusCode(404);
        }

    }

    public function depositAction()
    {
        // display debugging form
        $form = $this->getStatusForm();

        // set action
        $form->setAttribute('action', $this->url()->fromRoute('perfectmoney/status'));

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
     * @return \zfPerfectmoney\Form\PaymentForm
     */
    public function getPaymentForm()
    {
        return $this->getServiceLocator()->get('zfPerfectmoney_payment_form');
    }

    /**
     * @return \zfPerfectmoney\Form\StatusForm
     */
    public function getStatusForm()
    {
        return $this->getServiceLocator()->get('zfPerfectmoney_status_form');
    }
}
