<?php

class Default_MediaController extends Zend_Controller_Action
{

    private $_user;

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        $actionName = $this->_request->getActionName();

        if (!isset($this->_user->id) && in_array($actionName, array('add', 'edit', 'delete'))) {
            $this->_redirect($this->view->url(array('module' => 'user', 'action' => 'login'), NULL, TRUE));
        }

        //Send nav selection information to view
        $username = $this->_request->getParam('user', NULL);
        if ($username != NULL) {
            //Some user's nav
            $this->view->userNav = TRUE;
            $this->view->username = $username;

            if (isset($this->_user->username) && $this->_user->username === $username) {
                //Logged in user's nav
                $this->view->ownNav = TRUE;
            }
        }
        
        if(in_array($actionName, array('add','edit'))){
            $this->view->userNav = TRUE;
            $this->view->ownNav = TRUE;
        }
    }

    public function indexAction()
    {
        $username = $this->_request->getParam('user', NULL);

        $mediaModel = new Default_Model_Media();

        $paginator = new Zend_Paginator($mediaModel->getPaginatorAdapter($username));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);

        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        $this->_saveMedia();
    }

    public function editAction()
    {
        $this->_saveMedia();
    }

    private function _saveMedia()
    {
        $mediaModel = new Default_Model_Media();
        $commonModel = new Application_Model_Common();

        $form = new Default_Form_Media();

        //Update...
        $id = $this->_request->getParam('id');
        if (!empty($id)) {
            $media = $mediaModel->getRecordById($id);
            if (!isset($media->id)) {
                return;
            } else if ($media->added_by != $this->_user->id) {
                return;
            }

            //Populate form
            $form->populate($media->toArray());

            $this->view->file = $media->file;
            $this->view->thumbnail = $media->thumbnail;
        }
        //Update...

        if ($this->_request->isPost()) {
            //Add rename filters
            $commonModel->addRenameFilter($form->file);
            $commonModel->addRenameFilter($form->thumbnail);

            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();

                //Update...
                if (!empty($id)) {
                    $data['id'] = $id;
                    //If files are not uploaded, don't overwrite existing records in db
                    if (empty($data['file'])) {
                        unset($data['file']);
                    }
                    if (empty($data['thumbnail'])) {
                        unset($data['thumbnail']);
                    }
                }
                //...Update
                //Added / updated date
                $data['added_at'] = date('Y-m-d h:i:s');
                $data['added_by'] = $this->_user->id;

                $data['share_status'] = $commonModel->resolveShareStatus($data['share_status']);

                $mediaModel->save($data);

                $this->_redirect($this->view->url(array('controller' => 'media'), NULL, FALSE));
            }
        }

        $this->view->form = $form;
    }

}

