<?php

abstract class Default_Model_Abstract extends Application_Model_Abstract
{

    /**
     * @return Zend_Db_Table_Select the select element for paginator query
     */
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

        $order = $select->getPart(Zend_Db_Table_Select::ORDER);
        $order = array_map(function($item) {
                    return implode(' ', $item);
                }, $order);

        $order = array_merge(array('likes DESC'), $order);

        $select->reset(Zend_Db_Table_Select::ORDER)
                ->order($order)
                ->limit($limit);

        return $this->fetchAll($select);
    }

}

