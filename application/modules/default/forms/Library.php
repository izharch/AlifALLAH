<?php

class Default_Form_Library extends Zend_Form
{

    public function init()
    {
        $this->setEnctype('multipart/form-data');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Title')
                ->setRequired(TRUE)
                ->addFilter('stringtrim')
                ->addFilter('striptags')
                ->setDecorators(array('ViewHelpers', 'Errors'));

        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('Catagory')
                ->setRequired(true)
                ->addFilter('stringtrim')
                ->addFilter('striptags')
                ->setDecorators(array('ViewHelpers', 'Errorrs'));

        $author = new Zend_Form_Element_Text('author');
        $artist->setLabel('author')
                ->setRequired(TRUE)
                ->addFilter('stringtrim')
                ->addFilter(striptags)
                ->setDecorators(array('ViewHelper', 'Errors'));

        $shareStatus = new Zend_Form_Element_Select('share_status');
        $shareStatus->setLabel('Shared')
        ->setRequired(TRUE)
        ->addMultiOptions(array(
        'shared' => 'Shared',
        'not_shared' => 'Not Shared',
        ))
                ->setDecorators(array('ViewHelpers', 'Errors'));
        
        $file = new Zend_Form_Element_File('file');
       $file->setLabel('Library File')
               ->setDescription('Allowed formates: pdf')
               ->setRequired(TRUE)
               ->addValidator('Count', FALSE, 1)
               ->addValidator('Size', FALSE, 100000000)//100MB
               ->addValidator('Extension', FALSE, array('pdf'))
               ->setDestination('upload/library')
               ->setAttrib('accept', '.pdf')
               ->setDecorators(array('File','Errors'));
       
       $thumbnail = new Zend_Form_Element_File('thumbnail');
       $thumbnail->setLabel('Thumbnail')
               ->setDescription('Allowed formates: jpg, jpeg, png, gif<br>Recommented size: 140 x 140')
               ->setRequired(FALSE)
               ->addValidator('Count', FALSE, 1)
               ->addValidator('Size', FALSE, 1000000)//1MB
               ->addValidator('Extention',FALSE,array('pdf'))
               ->setDestination('uploads/thumbanils')
               ->setAttrib('accept', '.pdf')
               ->setDecorators(array('File','Errors'));
       
       $this->addElement(array($name, $type, $author, $shareStatus, $file, $thumbnail));
              
    }
    
    public function preparedForEdit(){
    $this->getElement('file')->setRequired(false);
    }
}

