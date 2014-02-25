<?php

abstract class Application_Model_Abstract extends Zend_Db_Table_Abstract
{

    /**
     * An associative array containing col names of files and their locations
     * 
     * This variable is overridden in the extended class to contain the column
     * names of the columns containing the uploaded file names as array keys and
     * their upload directory as value.
     * This array is used to delete the uploaded files when a record is deleted.
     */
    protected $uploadedFiles = NULL;

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

    /**
     * Removes the resource completely from db as well as file system
     * 
     * This method removes the record from db and deletes all related files as
     * well.
     * 
     * @param int $id the resource id
     */
    public function deleteResourceById($id)
    {
        $resource = $this->getRecordById($id);

        if (isset($resource->id)) {
            //Delete record from id
            $this->delete(array('id = ?' => $id));

            //Delete files
            if (!empty($this->uploadedFiles)) {
                foreach ($this->uploadedFiles as $colName => $location) {
                    if (!empty($resource->$colName)) {
                        $fileName = $location . $resource->$colName;

                        if (is_file($fileName)) {
                            unlink($fileName);
                        }
                    }
                }
            }
        }
    }

}

