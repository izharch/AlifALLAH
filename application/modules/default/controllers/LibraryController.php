<?php

class Default_LibraryController extends Zend_Controller_Action
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
        } else if ($status == 'pending') {
            $this->view->pending = TRUE;
        }

        $libraryModel = new Default_Model_Library();

        $searchForm = new Default_Form_LibrarySearch();
        $filters = $searchForm->extractFilters($this->_request->getParams());

        $paginatorAdapter = $libraryModel->getPaginatorAdapter($username, $status, $filters);

        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $paginator->setCurrentPageNumber($this->getParam('page'));

        $this->view->paginator = $paginator;
        $this->view->searchForm = $searchForm;
        $this->view->advancedSearch = $this->_request->getParams('advanced');
    }

    public function addAction()
    {
        $this->_saveLibrary();
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->view->error = 400;
            return;
        }
        $this->_saveLibrary();
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');

        if (empty($id)) {
            $this->view->error = 400;
            return;
        }

        $libraryModel = new Default_Model_Library();

        $library = $libraryModel->getRecordById($id);

        if (!isset($library->id)) {
            $this->view->error = 404;
            return;
        } elseif ($library->added_by != $this->_user->id) {
            $this->view->error = 403;
            return;
        }

        if ($this->_request->isPost()) {
            $libraryModel->deleteResourceById($id);

            $this->_redirect($this->view->url(array('controller' => 'library', 'user' => $this->_user->username), NULL, TRUE));
        }

        $this->view->library = $library;
    }

    private function _saveLibrary()
    {
        $libraryModel = new Default_Model_Library();
        $commonModel = new Application_Model_Common();

        $form = new Default_Form_Library();

        //Update...
        $id = $this->_request->getParam('id');

        if (!empty($id)) {
            $library = $libraryModel->getRecordById($id);

            if (!isset($library->id)) {
                $this->view->error = 404;
                return;
            } else if ($library->added_by != $this->_user->id) {
                $this->view->error = 403;
                return;
            }

            //Populate form
            $form->populate($library->toArray());

            $this->view->file = $library->file;
            $this->view->thumbnail = $library->thumbnail;
        }
        //...Update

        if ($this->_request->isPost()) {
            //add rename filters
            $commonModel->addRenameFilter($form->file);
            $commonModel->addRenameFilter($form->thumbnail);

            if ($form->isvalid($this->_request->getPost())) {
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
                //Added / updated data
                $data['added_at'] = date('Y-m-d H:i:s');
                $data['added_by'] = $this->_user->id;

                $data['share_status'] = $commonModel->resolveShareStatus($data['share_status']);

                $libraryModel->save($data);

                $this->_redirect($this->view->url(array('controller' => 'library', 'user' => $this->_user->username), NULL, TRUE));
            }
        }

        $this->view->form = $form;
    }

}

?>
