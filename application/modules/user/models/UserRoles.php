<?php

class User_Model_UserRoles extends Application_Model_Abstract
{

    protected $_name = 'user_roles';

    /**
     * Returns user roles by user id
     * 
     * This method returns an array of all roles assigned to the user whose user
     * id has been specified as input parameter.
     * 
     * @param int $userId the user id
     * 
     * @return array an array containing all roles assigned to the user
     */
    public function getRolesByUserId($userId)
    {
        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('ur' => $this->_name), array())
                ->join(array('r' => 'roles'), 'ur.role_id = r.id', 'role_name')
                ->where('ur.user_id = ?', $userId);

        return $this->getAdapter()->fetchCol($select);
    }

}

