<?php

class Default_Form_Abstract extends Zend_Form
{

    public function prepareForEdit()
    {
        $this->getElement('file')->setRequired(FALSE);
        return $this;
    }

}

