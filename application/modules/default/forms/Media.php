<?php

class Default_Form_Media extends Zend_Form
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

        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('Category')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $artist = new Zend_Form_Element_Text('artist');
        $artist->setLabel('Artist')
                ->setRequired(TRUE)
                ->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setDecorators(array('ViewHelper', 'Errors'));

        $sharedStatus = new Zend_Form_Element_Select('share_status');
        $sharedStatus->setLabel('Shared')
                ->setRequired(TRUE)
                ->addMultiOptions(array(
                    'shared' => 'Shared',
                    'not_shared' => 'Not shared',
                ))
                ->setDecorators(array('ViewHelper', 'Errors'));

        $file = new Zend_Form_Element_File('file');
        $file->setLabel('Media file')
                ->setDescription('Allowed formats: mp3, m4a, webma, oga, fla, wav')
                ->setRequired(TRUE)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 200000000) //200Mb
                ->addValidator('Extension', false, array(
                    'mp3', 'm4a', 'webma', 'oga', 'fla', 'wav'
                ))
                ->setDestination('uploads/media')
                ->setAttrib('accept', '.mp3,.m4a,.webma,.oga,.fla,.wav')
                ->setDecorators(array('File', 'Errors'));

        $thumbnail = new Zend_Form_Element_File('thumbnail');
        $thumbnail->setLabel('Thumbnail')
                ->setDescription('Allowed formats: jpg, jpeg, png, gif<br>Recommented size: 140 x 140')
                ->setRequired(FALSE)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 1000000) //1Mb
                ->addValidator('Extension', false, array(
                    'jpg', 'jpeg', 'png', 'gif'
                ))
                ->setDestination('uploads/thumbnails')
                ->setAttrib('accept', '.jpg,.jpeg,.png,.gif')
                ->setDecorators(array('File', 'Errors'));

        $this->addElements(array($name, $type, $artist, $sharedStatus, $file, $thumbnail));
    }

    public function prepareForEdit()
    {
        $this->getElement('file')->setRequired(FALSE);
    }

}

