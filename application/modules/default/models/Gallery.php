<?php

class Default_Model_Gallery extends Application_Model_Abstract
{

    protected $_name = 'gallery';

    /* See Application_Model_Abstract::$uploadedFiles */
    protected $uploadFiles = array('file' => 'uploads/gallery');

    public function getPaginatorAdapter($username = NULL, $shareStatus = NULL)
    {
        $likeCols = array('likes' => new Zend_Db_Expr('COUNT(l.id)'));

        $user = Zend_Auth::getInstance()->getIdentity();
        if (isset($user->id)) {
            $likeCols['liked'] = new Zend_Db_Expr($this->getAdapter()->quoteInto('BIT_AND(IF(l.user_id = ?, 1, 0))', $user->id));
        }

        $select = $this->select()
                ->setIntegrityCheck(FALSE)
                ->from(array('gl' => $this->_name))
                ->join(array('u' => 'user'), 'gl.added_by = u.id', 'username')
                ->joinLeft(array('l' => 'likes'), 'gl.id = l.entity_id AND l.entity_type = "gallery"', $likeCols)
                ->group('gl.id')
                ->order('gl.added_at DESC');

        if ($username != NULL) {
            $select->where('u.username = ?', $username);
        }
        if (!empty($shareStatus)) {
            $select->where('gl.share_status = ?', $shareStatus);
        }

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}
