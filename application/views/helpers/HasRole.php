<?php

class Application_View_Helper_HasRole extends Zend_View_Helper_Abstract
{

    /**
     * Checks if user has current role
     * 
     * This method returns TRUE if there is a logged in user and that user has
     * the role specified by the parameter.
     * 
     * @param string $role the role name to check for
     * 
     * @return boolean TRUE if user has the role, FALSE otherwise
     */
    public function hasRole($role)
    {
        $user = $this->view->user();
        if ($user && in_array($role, $user->roles)) {
            return TRUE;
        }
        return FALSE;
    }

}

?>
