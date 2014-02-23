<?php

class Default_MediaController extends Zend_Controller_Action
{

    private $_user;

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        //Send nav selection information to view
        $username = $this->_request->getParam('user', NULL);
        if ($username != NULL) {
            //Some user's nav
            $this->view->userNav = TRUE;
            $this->view->username = $username;

            if (isset($this->_user->username) && $this->_user->username === $username) {
                //Logged in user's nav
                $this->view->ownNav = TRUE;
            }
        }
    }

    public function indexAction()
    {
        $username = $this->_request->getParam('user', NULL);

        $mediaModel = new Default_Model_Media();

        $paginator = new Zend_Paginator($mediaModel->getPaginatorAdapter($username));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);

        $this->view->paginator = $paginator;
    }
    
    public function addAction(){
        
    }

}

