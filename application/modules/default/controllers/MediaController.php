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

        if (in_array($actionName, array('add', 'edit'))) {
            $this->view->userNav = TRUE;
            $this->view->ownNav = TRUE;
        }
    }

    public function indexAction()
    {
        $username = $this->_request->getParam('user', NULL);
        $status = $this->_request->getParam('status', NULL);

        if (empty($username) && empty($status)) {
            //Show only shared items
            $status = 'shared';
            $this->view->shared = TRUE;
        } else if ($status == 'pending') {
            $this->view->pending = TRUE;
        }

        $mediaModel = new Default_Model_Media();

        $searchForm = new Default_Form_MediaSearch();
        $filters = $searchForm->extractFilters($this->_request->getParams());

        $paginatorAdapter = $mediaModel->getPaginatorAdapter($username, $status, $filters);

        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $paginator->setCurrentPageNumber($this->getParam('page'));

        $this->view->paginator = $paginator;
        $this->view->searchForm = $searchForm;
        $this->view->advancedSearch = $this->_request->getParam('advanced');
    }

    public function addAction()
    {
        $this->_saveMedia();
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->view->error = 400;
            return;
        }
        $this->_saveMedia();
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');

        if (empty($id)) {
            $this->view->error = 400;
            return;
        }

        $mediaModel = new Default_Model_Media();

        $media = $mediaModel->getRecordById($id);

        if (!isset($media->id)) {
            $this->view->error = 404;
            return;
        } else if ($media->added_by != $this->_user->id) {
            $this->view->error = 403;
            return;
        }

        if ($this->_request->isPost()) {
            $mediaModel->deleteResourceById($id);

            $this->_redirect($this->view->url(array('controller' => 'media', 'user' => $this->_user->username), NULL, TRUE));
        }

        $this->view->media = $media;
    }

    private function _saveMedia()
    {
        $mediaModel = new Default_Model_Media();
        $commonModel = new Application_Model_Common();

        $form = new Default_Form_Media();

        //Update...
        $id = $this->_request->getParam('id');
        $pending = $this->_request->getParam('pending');
        $shared = $this->_request->getParam('shared');

        if (!empty($id)) {
            $media = $mediaModel->getRecordById($id);

            if (!isset($media->id)) {
                $this->view->error = 404;
                return;
            } else if (!$this->view->isAdmin() && $media->added_by != $this->_user->id) {
                $this->view->error = 403;
                return;
            }

            //Populate form
            $form->prepareForEdit()
                    ->populate($media->toArray());

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
                if (empty($id)) {
                    //Added / updated date
                    $data['added_at'] = date('Y-m-d H:i:s');
                    $data['added_by'] = $this->_user->id;
                }

                $data['share_status'] = $commonModel->resolveShareStatus($data['share_status'], $pending);

                $mediaModel->save($data);

                if ($pending == 1) {
                    $redirectKey = 'status';
                    $redirectVal = 'pending';
                } else if ($shared != 1) {
                    $redirectKey = 'user';
                    $redirectVal = $this->_user->username;
                }
                $this->_redirect($this->view->url(array('controller' => 'media', $redirectKey => $redirectVal), NULL, TRUE));
            }
        }

        $this->view->form = $form;
    }

}

