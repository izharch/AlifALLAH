<?php

class Default_Model_Library extends Default_Model_Abstract
{

    protected $_name = 'library';

    /* See Application_Model_Abstract::$uploadedFiles */
    protected $uploadFiles = array(
        'file' => 'uploads/library/',
        'thumbnail' => 'uploads/thumbnails/',
    );

    public function getPaginatorQuery($username = NULL, $sharedStatus = NULL, $filters = array())
    { 
        $likeCols = array('likes' => new Zend_Db_Expr('COUNT(l.id)'));

        $user = Zend_Auth::getInstance()->getIdentity();
        if (isset($user->id)) {
            $likeCols['liked'] = new Zend_Db_Expr($this->getAdapter()->quoteInto('BIT_AND(IF(l.user_id = ?, 1, 0))', $user->id));
        }

        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('lb' => $this->_name))
                ->join(array('u' => 'user'), 'lb.added_by = u.id', 'username')
                ->joinLeft(array('l' => 'likes'), 'lb.id = l.entity_id AND l.entity_type = "library"', $likeCols)
                ->group('lb.id')
                ->order('lb.added_at DESC');

        if ($username != NULL) {
            $select->where('u.username = ?', $username);
        }
        if (!empty($sharedStatus)) {
            $select->where('lb.share_status = ?', $sharedStatus);
        }

        return $select;
    }

}

