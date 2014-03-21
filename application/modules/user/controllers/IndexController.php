<?php

class User_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        $actionName = $this->_request->getActionName();

        if (!isset($this->_user->id) && in_array($actionName, array('index', 'logout'))) {
            $this->_redirect($this->view->url(array('module' => 'user', 'action' => 'login'), NULL, TRUE));
        } else if (isset($this->_user->id) && in_array($actionName, array('login', 'signup', 'activate'))) {
            $this->_redirect();
        }
        
        if($actionName == 'index' && !$this->view->isAdmin()){
            $this->_redirect();
        }
    }

    public function indexAction()
    {
        $userModel = new User_Model_User();

        $paginator = new Zend_Paginator($userModel->getPaginatorAdapter());
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);

        $this->view->paginator = $paginator;
    }

    public function loginAction()
    {
        $userModel = new User_Model_User();
        $userRolesModel = new User_Model_UserRoles();

        $form = new User_Form_User();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();

                $result = $userModel->processLoginData($formValues);

                if ($result['status'] === TRUE) {
                    $this->_redirect('/');
                } else {
                    $this->view->errorMessage = $result['message'];
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

    public function signupAction()
    {
        $userModel = new User_Model_User();
        $commonFunctions = new Application_Model_Common();

        $form = new User_Form_Signup();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formData = $form->getValues();

                $formData->password['md5'];

                unset($formData['confirm_password']);

                $formData['password'] = md5($formData['password']);
                $formData['added_at'] = date('Y-m-d h:i:s');
                $formData['confirmation_token'] = $commonFunctions->getRandomString(32);

                $userModel->save($formData);

                $commonFunctions->sendSignupConfirmationEmail($formData['email'], $formData['confirmation_token']);

                $this->view->successMessage = 'Your account has been created. Please check your email to proceed further.';
            }
        }

        $this->view->form = $form;
        $this->view->sidebar = FALSE;
    }

    public function activateAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $email = $this->_request->getParam('email');
        $token = $this->_request->getParam('token');

        $userModel = new User_Model_User();

        if (empty($email) || empty($token)) {
            $this->view->errorMessage = 'Invalid verification URL';
        }

        $user = $userModel->verifyActivationToken($email, $token);

        if ($user !== FALSE) {
            $userData = array(
                'id' => $user->id,
                'status' => 'active',
            );

            $userModel->save($userData);

            $userModel->processLoginData($user, FALSE);

            $this->_redirect('/');
        } else {
            $this->view->errorMessage = 'Invalid verification URL';
        }

        $this->view->sidebar = FALSE;
    }

}

