<?php

class Default_Form_GallerySearch extends Default_Form_AbstractSearch
{

    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Title')
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Added by')
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $this->addElements(array($name, $username));

        //set primary search element
        $this->primary = $name;
    }

}

