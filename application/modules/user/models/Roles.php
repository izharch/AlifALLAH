<?php

class User_Model_Roles extends Application_Model_Abstract
{

    protected $_name = 'roles';

    public function getRoleNames($excludeSuper = TRUE)
    {
        $select = $this->select()->from($this->_name, 'role_name');

        $result = $this->getAdapter()->fetchCol($select);

        if ($excludeSuper) {
            unset($result[array_search('super', $result)]);
        }

        return $result;
    }

    public function getRoleIdByName($name)
    {
        $select = $this->select()->from($this->_name, 'id')
                ->where('role_name = ?', $name);

        return $this->getAdapter()->fetchOne($select);
    }

}

