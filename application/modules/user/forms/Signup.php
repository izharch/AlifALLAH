<?php

class User_Form_Signup extends Zend_Form
{

    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $userName = new Zend_Form_Element_Text('username');
        $userName->setLabel('Username')
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors'));
                echo md5($password);
            
        $confirmPassword = new Zend_Form_Element_Password('confirm_password');
        $confirmPassword->setLabel('Confirm Password')
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $this->addElements(array($email, $userName, $password, $confirmPassword));
    }

    public function isValid($data)
    {
        $valid = parent::isValid($data);

        $userModel = new User_Model_User();

        if (!$this->password->hasErrors() && !$this->confirm_password->hasErrors()) {
            if ($data['password'] !== $data['confirm_password']) {
                $this->confirm_password->addError('Passwords do not match');
                $valid = FALSE;
            }
        }

        if (!$this->email->hasErrors()) {
            $email = $this->email->getValue();

            $result = $userModel->verifyUniqueEmail($email);

            if ($result === FALSE) {
                $this->email->addError('Email address already exists');
                $valid = FALSE;
            }
        }

        if (!$this->username->hasErrors()) {
            $username = $this->username->getValue();

            $result = $userModel->verifyUniqueUsername($username);

            if ($result === FALSE) {
                $this->username->addError('User name already exists');
                $valid = FALSE;
            }
        }

        return $valid;
    }

}

