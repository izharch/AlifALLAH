<?php

class Default_MediaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $username = $this->_request->getParam('user', NULL);

        $mediaModel = new Default_Model_Media();

        $paginator = new Zend_Paginator($mediaModel->getPaginatorAdapter($username));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);

        if($username != NULL){
            $this->view->userNav = TRUE;
        }
        $this->view->paginator = $paginator;
    }

}

