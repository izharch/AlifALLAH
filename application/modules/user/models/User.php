<?php

class User_Model_User extends Application_Model_Abstract
{

    protected $_name = 'user';

    /**
     * Verifies the account activation token
     * 
     * This method checks if there is an account associated with provided email
     * address that is yet unverified and has a token equal to the token
     * provided. If so, it will return the id of that user, FALSE otherwise.
     * 
     * @param string $email the email address of the user
     * @param string $token the confirmation token of the user
     * 
     * @return mixed the id of the user or FALSE
     */
   
 public function verifyActivationToken($email, $token)
    {
        $user = $this->fetchRow(
                array(
                    'email = ?' => $email,
                    'confirmation_token = ?' => $token,
                    'status = ?' => 'unverified'
                )
        );

        return (isset($user->id) ? $user : FALSE);
    }
    
    public function processLoginData($data, $credentialTreatment = TRUE)
    {
        $status = FALSE;
        $message = NULL;

        $authAdapter = new Zend_Auth_Adapter_DbTable();
        $authAdapter->setTableName($this->_name)
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setIdentity($data['username'])
                ->setCredential($data['password']);

        if ($credentialTreatment) {
            $authAdapter->setCredentialTreatment('MD5(?)');
        }

       $authResult = $authAdapter->authenticate();

        if ($authResult->isValid()) {
        $user = $authAdapter->getResultRowObject();
        
        
        if ($user->status == 'active') {
                unset($user->password);

                //Set user roles
                $userRolesModel = new User_Model_UserRoles();

                $userRoles = $userRolesModel->getRolesByUserId($user->id);
                $user->roles = $userRoles;

                Zend_Auth::getInstance()->getStorage()->write($user);

                $status = TRUE;
            } else if ($user->status == 'blocked') {
                $message = 'Your account has been blocked.';
            } else if ($user->status == 'unverified') {
                $message = 'Your account has not been verified yet. Please click the link sent in the email.';
            } else {
                $message = 'Invalid credentials.';
            }
        } else {
            $message = 'Invalid credentials.';
        }
            
            
        
            

        return array(
            'status' => $status,
            'message' => $message,
        );
    }
    
     public function verifyUniqueEmail($email)
    {
        $result = $this->fetchRow(array('email = ?' => $email));

        return (isset($result->id) ? FALSE : TRUE);
    }

    public function verifyUniqueUsername($username)
    {
        $result = $this->fetchRow(array('username = ?' => $username));

        return (isset($result->id) ? FALSE : TRUE);
    }
    public function getPaginatorAdapter($username = NULL, $sharedStatus = NULL)
    {
        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('u' => $this->_name))
                ->joinLeft(array('l' => 'likes'), 'l.entity_added_by = u.id', array('likes' => new Zend_Db_Expr('COUNT(l.id)')))
                ->joinLeft(array('ur' => 'user_roles'), 'ur.user_id = u.id', array())
                ->joinLeft(array('r' => 'roles'), 'r.id = ur.role_id', array('roles' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT(r.role_name) SEPARATOR ", ")')))
                ->group('u.id')
                ->order('u.added_at DESC', '');

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}