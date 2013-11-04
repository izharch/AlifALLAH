<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initPaginatorDefaults()
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination/default.phtml');
    }

    protected function _initConfigVar()
    {
        $config = $this->getOptions();
        Zend_Registry::set('config', $config);
    }

}

