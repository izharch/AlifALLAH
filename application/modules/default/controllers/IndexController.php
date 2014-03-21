<?php

class Default_IndexController extends Zend_Controller_Action
{

    private $_user;

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        $actionName = $this->_request->getActionName();

        if (!isset($this->_user->id) && in_array($actionName, array('like', 'share'))) {
            die;
        }
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

        $userId = $this->_user->id;

        $likesModel = new Default_Model_Likes();

        $liked = $likesModel->isLiked($entityId, $entityType, $userId);

        if ($act == 'like' && !$liked) {
            $model = 'Default_Model_' . ucfirst($entityType);
            $model = new $model;

            $entity = $model->getRecordById($entityId);

            $likeData = array(
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'entity_added_by' => $entity->added_by,
                'user_id' => $userId,
                'added_at' => date('Y-m-d h:i:s'),
            );

            $likesModel->save($likeData);
        } else if ($act == 'dislike' && $liked) {
            $likesModel->removeLike($entityId, $entityType, $userId);
        }

        echo $likesModel->getLikesCount($entityId, $entityType);
    }

    public function shareAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $entityId = $this->_request->getParam('entity_id');
        $entityType = $this->_request->getParam('entity_type');
        $act = $this->_request->getParam('act');

        if (!in_array($entityType, array('media', 'library', 'gallery'))) {
            return;
        }

        $model = 'Default_Model_' . ucfirst($entityType);
        $model = new $model;

        $entity = $model->getRecordById($entityId);

        $shareStatus = '';

        if (isset($entity->id)) {
            $shareStatus = $entity->share_status;

            if ($act == 'share' && $entity->share_status === 'not_shared') {
                //Apparently every role in the system has privilege to share
                $shareStatus = empty($this->_user->roles) ? 'pending' : 'shared';
            } else if ($act == 'unshare' && $entity->share_status !== 'disapproved') {
                $shareStatus = 'not_shared';
            } else if ($this->view->isAdmin() && in_array($act, array('approve', 'disapprove'))) {
                $shareStatus = $act == 'disapprove' ? 'disapproved' : 'shared';
            }

            if ($shareStatus !== $entity->share_status) {
                //Status is changed and needs to be saved

                $data = array(
                    'share_status' => $shareStatus,
                    'id' => $entity->id,
                );

                $model->save($data);
            }
        }

        echo $shareStatus;
    }

}

