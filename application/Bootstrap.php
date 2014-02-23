<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initPaginatorDefaults()
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination/default.phtml');
    }

}

