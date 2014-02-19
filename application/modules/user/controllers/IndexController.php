<?php

class User_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function loginAction()
    {
        $userRolesModel = new User_Model_UserRoles();
        
        $form = new User_Form_User();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();

                $authAdapter = new Zend_Auth_Adapter_DbTable();
                $authAdapter->setTableName('user')
                        ->setIdentityColumn('username')
                        ->setCredentialColumn('password')
                        ->setCredentialTreatment('MD5(?)')
                        ->setIdentity($formValues['username'])
                        ->setCredential($formValues['password']);

                $authResult = $authAdapter->authenticate();

                if ($authResult->isValid()) {
                    $user = $authAdapter->getResultRowObject();

                    unset($user->password);
                    
                    //Set user roles
                    $userRoles = $userRolesModel->getRolesByUserId($user->id);
                    $user->roles = $userRoles;

                    Zend_Auth::getInstance()->getStorage()->write($user);

                    $this->_redirect('/');
                } else {
                    $this->view->error = TRUE;
                }
            }
        }

        $this->view->form = $form;
        $this->view->sidebar = FALSE;
        
    }

    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        Zend_Auth::getInstance()->clearIdentity();
        
        $this->_redirect('/');
    }

}

