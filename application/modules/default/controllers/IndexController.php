<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }

    public function likeAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $entityId = $this->_request->getParam('entity_id');
        $entityType = $this->_request->getParam('entity_type');
        $act = $this->_request->getParam('act');
        $userId = $this->_request->getParam('user_id');

        $likesModel = new Default_Model_Likes();

        $liked = $likesModel->isLiked($entityId, $entityType, $userId);

        if ($act == 'like' && !$liked) {
            $likeData = array(
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'user_id' => $userId,
                'added_at' => date('Y-m-d h:i:s'),
            );

            $likesModel->save($likeData);
        } else if ($act == 'dislike' && $liked) {
            $likesModel->removeLike($entityId, $entityType, $userId);
        }

        echo $likesModel->getLikesCount($entityId, $entityType);
    }

}

