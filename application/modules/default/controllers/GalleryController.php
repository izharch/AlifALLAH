<?php

class Default_GalleryController extends Zend_Controller_Action
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
            //some user's nav
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
        } else if ($status == 'pending') {
            $this->view->pending = TRUE;
        }

        $galleryModel = new Default_Model_Gallery();
        
        $searchForm = new Default_Form_GallerySearch();
        $filters = $searchForm->extractFilters($this->_request->getParams());

        $paginatorAdapter = $galleryModel->getPaginatorAdapter($username, $status, $filters);

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
        $this->_saveGallery();
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->view->error = 400;
            return;
        }
        $this->_saveGallery();
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');

        if (empty($id)) {
            $this->view->error = 400;
            return;
        }

        $galleryModel = new Default_Model_Gallery();

        $gallery = $galleryModel->getRecordById($id);

        if (!isset($gallery->id)) {
            $this->view->error = 404;
            return;
        } else if ($gallery->added_by != $this->_user->id) {
            $this->view->error = 403;
            return;
        }

        if ($this->_request->isPost()) {
            $galleryModel->deleteResourceById($id);

            $this->_redirect($this->view->url(array('controller' => 'gallery', 'user' => $this->_user->username), NULL, TRUE));
        }

        $this->view->gallery = $gallery;
    }

    private function _saveGallery()
    {
        $galleryModel = new Default_Model_Gallery();
        $commonModel = new Application_Model_Common();

        $form = new Default_Form_Gallery();

        //Update...
        $id = $this->_request->getParam('id');

        if (!empty($id)) {
            $gallery = $galleryModel->getRecordById($id);

            if (!isset($gallery->id)) {
                $this->view->error = 404;
                return;
            } else if ($gallery->added_by != $this->_user->id) {
                $this->view->error = 403;
                return;
            }

            //Populate form
            $form->populate($gallery->toArray());

            $this->view->file = $gallery->file;
        }
        //Update...

        if ($this->_request->isPost()) {
            //Add rename filters
            $commonModel->addRenameFilter($form->file);

            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();

                //Update...
                if (!empty($id)) {
                    $data['id'] = $id;
                    //If files are not uploaded, don't overwrite existing records in db
                    if (empty($data['file'])) {
                        unset($data['file']);
                    }
                }
                //...Update
                //Added / updated date
                $data['added_at'] = date('Y-m-d H:i:s');
                $data['added_by'] = $this->_user->id;

                $data['share_status'] = $commonModel->resolveShareStatus($data['share_status']);

                $galleryModel->save($data);

                $this->_redirect($this->view->url(array('controller' => 'gallery', 'user' => $this->_user->username), NULL, TRUE));
            }
        }

        $this->view->form = $form;
    }

}

