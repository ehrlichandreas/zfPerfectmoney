<?php
namespace ggPerfectmoney\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DepositController extends AbstractActionController
{
    public function statusAction()
    {
        return $this->getResponse();
    }
}