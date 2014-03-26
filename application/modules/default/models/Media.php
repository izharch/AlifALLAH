<?php

class Default_Model_Media extends Application_Model_Abstract
{

    protected $_name = 'media';

    /* See Application_Model_Abstract::$uploadedFiles */
    protected $uploadedFiles = array(
        'file' => 'uploads/media/',
        'thumbnail' => 'uploads/thumbnails/',
    );

    public function getPaginatorAdapter($username = NULL, $sharedStatus = NULL, $filters = array())
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
                ->joinLeft(array('l' => 'likes'), 'm.id = l.entity_id AND l.entity_type = "media"', $likeCols)
                ->group('m.id')
                ->order('m.added_at DESC');

        if ($username != NULL) {
            $select->where('u.username = ?', $username);
        }
        if (!empty($sharedStatus)) {
            $select->where('m.share_status = ?', $sharedStatus);
        }
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $select->where("$key LIKE ?", "%$value%");
            }
        }

        return new Zend_Paginator_Adapter_DbTableSelect($select);
    }

}

