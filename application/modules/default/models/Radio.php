<?php

class Default_Model_Radio extends Application_Model_Abstract
{

    protected $_name = 'radio';

    public function getActiveChannels()
    {
        $select = $this->select()->from($this->_name, array('title', 'source'))
                ->where('status = ?', 'enabled');

        return $this->fetchAll($select);
    }

    public function getPaginatorAdapter()
    {
        $select = $this->select();

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}

