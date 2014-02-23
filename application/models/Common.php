<?php

class Application_Model_Common
{

    /**
     * Returns the status of the shared medium to be saved
     * 
     * This method accepts a valid value of shared status and returns the value
     * of the status to be saved in database. If the value is not 'shared', it
     * is returned as it is. If it is, then if the user has any role that has
     * privilege to share a medium without pending request, then the returned
     * value is 'shared', otherwise 'pending'.
     * Aparently, right now all roles have permissions so a user with any role
     * will get direct shared medium. Junior users don't have any role.
     * 
     * @param string $shareStatus the value input by the user
     * 
     * @return string the value of shared status to be saved in db
     */
    public function resolveShareStatus($shareStatus)
    {
        if ($shareStatus === 'shared') {
            $user = Zend_Auth::getInstance()->getIdentity();

            if (isset($user->roles) && !empty($user->roles)) {
                return 'shared';
            } else {
                return 'pending';
            }
        } else {
            return $shareStatus;
        }
    }

    /**
     * Adds rename filter to file field
     * 
     * This method adds rename filter to the form's file field so that no files
     * overwrite each other or existing ones while uploading.
     * 
     * @param Zend_Form_Element_File $field the file field
     */
    public function addRenameFilter($field)
    {
        if (!$field instanceof Zend_Form_Element_File) {
            return;
        }

        if ($field->isUploaded()) {
            $fileExt = pathinfo($field->getFileName(), PATHINFO_EXTENSION);

            $field->addFilter('Rename', uniqid() . ".$fileExt");
        }
    }

}

