<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Application\Form\LogentryForm;
use Application\Model\Logentry;


class CheckController extends AbstractActionController
{
    public function indexAction()
    {
        $session = new SessionContainer('check_page');
        $visits = (int)$session->offsetGet('visits');
        $session->offsetSet('visits', ++$visits);

        $form = new LogentryForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $logentry = new Logentry();
            $form->setInputFilter($logentry->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $logentry->exchangeArray($form->getData());
                $logger = $this->getServiceLocator()->get('Zend\Log\Logger');
                $logger->info(sprintf("Logentry with INFO prio sent at %s, message: %s", $logentry->date->format("Y-m-d H:i:s"), $logentry->logentry));
                $logger->err(sprintf("Logentry with ERR prio sent at %s, message: %s", $logentry->date->format("Y-m-d H:i:s"), $logentry->logentry));
                return $this->redirect()->toRoute('check');
            }
        }
        return new ViewModel(array(
            'visits' => $visits,
            'form' => $form
        ));
    }
}