<?php

class Application_View_Helper_NavStatus extends Zend_View_Helper_Abstract
{

    public function navStatus($mc, $userNav = FALSE)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        $currentMC = "{$request->getModuleName()}/{$request->getControllerName()}";

        return ($mc === $currentMC && $userNav == $this->view->userNav ? 'active' : '');
    }

}

?>
