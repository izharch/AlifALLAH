<?php

class Default_Form_Media extends Zend_Form
{

    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Title')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHepler', 'Errors'));
        
        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('Category')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHepler', 'Errors'));
        
        $artist = new Zend_Form_Element_Text('artist');
        $artist->setLabel('Category')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHepler', 'Errors'));
        
        $artist = new Zend_Form_Element_Text('artist');
        $artist->setLabel('Category')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHepler', 'Errors'));
    }

}

