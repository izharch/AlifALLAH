<?php

class Application_View_Helper_IsHomepage extends Zend_View_Helper_Abstract
{

    public function isHomepage()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();
        
        if($moduleName == 'default' && $controllerName == 'index' && $actionName == 'index'){
            return TRUE;
        }
        return FALSE;
    }

}

?>
