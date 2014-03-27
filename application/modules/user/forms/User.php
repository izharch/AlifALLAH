<?php

class User_Form_User extends Zend_Form
{

    public function init()
    {
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')
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

        $this->addElements(array($username, $password));
    }

}

