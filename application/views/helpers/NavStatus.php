<?php

class Application_View_Helper_NavStatus extends Zend_View_Helper_Abstract
{

    public function navStatus($mc, $userNav = FALSE)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        $currentMC = "{$request->getModuleName()}/{$request->getControllerName()}";
        $currUserNav = $this->view->userNav;

        return ($mc === $currentMC && $userNav == $currUserNav ? 'active' : '');
    }

}

?>
