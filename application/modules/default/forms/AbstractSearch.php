<?php

abstract class Default_Form_AbstractSearch extends Zend_Form
{

    public $primary = NULL;

    public function extractFilters($formData)
    {
        $this->isValid($formData);
        $filters = $this->getValues();
        return array_filter($filters);
    }

}