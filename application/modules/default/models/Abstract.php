<?php

abstract class Default_Model_Abstract extends Application_Model_Abstract
{

    abstract public function getPaginatorQuery();

    /**
     * 
     * @param type $username
     * @param type $sharedStatus
     * @param type $filters
     * @return Zend_Paginator_Adapter_DbTableSelect
     */
    public function getPaginatorAdapter($username = NULL, $sharedStatus = NULL, $filters = array())
    {
        $select = $this->getPaginatorQuery();

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

    public function getMostLikedRecords($limit = 5)
    {
        $select = $this->getPaginatorQuery(NULL, 'shared');
        $select->order('likes DESC')
                ->limit($limit);

        return $this->fetchAll($select);
    }

}

