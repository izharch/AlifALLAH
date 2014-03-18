<?php

class Application_View_Helper_IsAdmin extends Zend_View_Helper_Abstract
{

    /**
     * Checks if user is admin
     * 
     * This helper returns true if the logged in user is admin. If the user has
     * roles as super or admin, the return value will be true. If the input flag
     * is true, then true will be returned if and only if the user has super
     * role.
     * 
     * @param bool $super super user flag
     * 
     * @return bool the result of the check
     */
    public function isAdmin($super = FALSE)
    {
        $user = $this->view->user();

        if (!isset($user->roles) || empty($user->roles)) {
            return FALSE;
        }

        $roles = array('super');
        if (!$super) {
            $roles[] = 'admin';
        }

        $hasRoles = array_intersect($user->roles, $roles);

        return !empty($hasRoles);
    }

}

?>
