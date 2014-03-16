<?php

class Default_Model_Likes extends Application_Model_Abstract
{

    protected $_name = 'likes';

    public function isLiked($entityId, $entityType, $userId)
    {
        $like = $this->fetchRow(
                array(
                    'entity_id = ?' => $entityId,
                    'entity_type = ?' => $entityType,
                    'user_id = ?' => $userId,
                )
        );

        if (isset($like->id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function removeLike($entityId, $entityType, $userId)
    {
        return $this->delete(
                        array(
                            'entity_id = ?' => $entityId,
                            'entity_type = ?' => $entityType,
                            'user_id = ?' => $userId,
                        )
        );
    }

    public function getLikesCount($entityId, $entityType)
    {
        $select = $this->select();
        $select->from($this->_name, new Zend_Db_Expr('COUNT(id)'))
                ->where('entity_id = ?', $entityId)
                ->where('entity_type = ?', $entityType);

        return $this->getAdapter()->fetchOne($select);
    }

}

