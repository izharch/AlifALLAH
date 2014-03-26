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

    /**
     * Returns an alphanumeric string of random characters of specified length
     * 
     * @param int $length the length of string
     * 
     * @return string the random string
     */
    public function getRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function sendSignupConfirmationEmail($email, $confirmationToken)
    {
        $config = Zend_Registry::get('config');
        if ($config['send_mail'] == 0) {
            return;
        }

        $view = new Zend_View();
        $confirmationLink = $view->url(array('module' => 'user', 'action' => 'activate', 'email' => $email, 'token' => $confirmationToken), NULL, TRUE);

        //From email
        $fromEmail = 'noreply@' . $_SERVER['HTTP_HOST'];

        $content = <<<TEXT
Hi there and Asalamu alaikum!

Thank you for signing up on our portal. You are just one step ahead from being a
junior member of our website. Please visit the link below to activate your
account.


TEXT;
        $contentHTML = nl2br($content);

        $content .= $confirmationLink;
        $contentHTML .= "<a href='$confirmationLink' target='_blank'>$confirmationLink</a>";

        $mail = new Zend_Mail();
        $mail->setBodyHtml($contentHTML)
                ->setBodyText($content)
                ->setFrom($fromEmail, 'Alif 4 Allah')
                ->setSubject('Signup Confirmation')
                ->addTo($email)
                ->send();
    }

    public function getMostLiked($limit = 5)
    {
        $mediaModel = new Default_Model_Media();
        $libraryModel = new Default_Model_Library();

        $mostLikedMedia = $mediaModel->getMostLikedRecords($limit);
        $mostLikedLibrary = $libraryModel->getMostLikedRecords($limit);

        return array(
            'media' => $mostLikedMedia,
            'library' => $mostLikedLibrary,
            'limit' => $limit,
        );
    }

}

