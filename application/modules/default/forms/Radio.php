<?php

class Default_Form_Radio extends Zend_Form
{

    public function init()
    {
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $source = new Zend_Form_Element_Text('source');
        $source->setLabel('Source')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $extension = new Zend_Form_Element_Text('extension');
        $extension->setLabel('Extension')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setValue('mp3')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setMultiOptions(array(
                    'enabled' => 'Enabled',
                    'disabled' => 'Disabled',
                ))
                ->setDecorators(array('ViewHelper', 'Errors'));

        $this->addElements(array($title, $source, $extension, $status));
    }

}

