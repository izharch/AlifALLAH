<?php

class Application_View_Helper_User extends Zend_View_Helper_Abstract
{

    public function user()
    {
        //If user is not logged in
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            return FALSE;
        }
        //If user is logged in
        return Zend_Auth::getInstance()->getIdentity();
    }

}

?>
