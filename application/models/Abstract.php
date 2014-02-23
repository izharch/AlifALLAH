<?php

abstract class Application_Model_Abstract extends Zend_Db_Table_Abstract
{

    /**
     * Returns row by id
     * 
     * This method returns the row which has the primary key value equal to the
     * id specified.
     * 
     * @param int $id the id of the row
     * 
     * @return Zend_Db_Table_Row
     */
    public function getRecordById($id)
    {
        return $this->fetchRow(array('id = ?' => $id));
    }

    /**
     * Saves the data and returns the id of the row
     * 
     * This method adds a new record if $data doesn't contain an id. If it does,
     * then it updates that id with the data.
     * 
     * @param array $data the data to be saved
     * 
     * @return int the id of the database row
     */
    public function save($data)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $this->update($data, array('id = ?' => $id));
            return $id;
        } else {
            return $this->insert($data);
        }
    }

}

