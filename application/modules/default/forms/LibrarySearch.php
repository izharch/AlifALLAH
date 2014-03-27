<?php

class Default_Form_LibrarySearch extends Default_Form_AbstractSearch
{

    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Title')
                ->addFilter('StringTrim')
                ->addFilter('Striptags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('Catagory')
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $author = new Zend_Form_Element_Text('author');
        $author->setLabel('Author')
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Added By')
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $this->addElements(array($name, $type, $author, $username));

        //set primary search element
        $this->primary = $name;
    }

}

