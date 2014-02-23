<?php

class Default_Model_Media extends Zend_Db_Table_Abstract
{

    protected $_name = 'media';

    public function getPaginatorAdapter($username = NULL)
    {
        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('m' => $this->_name))
                ->join(array('u' => 'user'), 'm.added_by = u.id', 'username');

        if ($username != NULL) {
            $select->where('u.username = ?', $username);
        }

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}

