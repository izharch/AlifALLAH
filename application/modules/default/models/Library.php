<?php

class Default_Model_Library extends Application_Model_Abstract
{

    protected $_name = 'library';

    /* See Application_Model_Abstract::$uploadedFiles */
    protected $uploadFiles = array(
        'file' => 'uploads/library/',
        'thumbnail' => 'uploads/thumbnails/',
    );

    public function getPaginatorAdapter($username = NULL)
    { 
        $likeCols = array('likes' => new Zend_Db_Expr('COUNT(l.id)'));

        $user = Zend_Auth::getInstance()->getIdentity();
        if (isset($user->id)) {
            $likeCols['liked'] = new Zend_Db_Expr($this->getAdapter()->quoteInto('BIT_AND(IF(l.user_id = ?, 1, 0))', $user->id));
        }

        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('m' => $this->_name))
                ->join(array('u' => 'user'), 'm.added_by = u.id', 'username')
                ->order('m.added_at DESC');

        if ($username != NULL) {
            $select->where('u.username = ?', $username);
        } else {
            $select->where('m.share_status = "shared"');
        }

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}

