<?php

class Default_Form_Gallery extends Default_Form_Abstract
{

    public function init()
    {
        $this->setEnctype('multipart/form-data');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Title')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $sharedStatus = new Zend_Form_Element_Select('share_status');
        $sharedStatus->setLabel('Shared')
                ->setRequired(TRUE)
                ->addMultiOptions(array(
                    'shared' => 'Shared',
                    'not_shared' => 'Not Shared',
                ))
                ->setDecorators(array('ViewHelper', 'Errors'));


        $file = new Zend_Form_Element_File('file');
        $file->setLabel('Add Image')
                ->setDescription('Allowed Formates: jpg, jpeg, png, gif')
                ->setRequired(TRUE)
                ->addValidator('Count', FALSE, 1)
                ->addValidator('Size', FALSE, 200000000)//200MB
                ->addValidator('Extension', FALSE, array('jpg', 'jpeg', 'png', 'gif'))
                ->setDestination('uploads/gallery')
                ->setAttrib('accept', '.jpg,.jpeg,.png,.gif')
                ->setDecorators(array('File', 'Errors'));

        $this->addElements(array($name, $sharedStatus, $file ));
    }

    public function preparedForEdit()
    {
        $this->getElement('file')->setRequired(FALSE);
    }

}

