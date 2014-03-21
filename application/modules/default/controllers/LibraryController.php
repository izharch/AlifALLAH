<?php

/*
  @author ezee
 */

class Default_LibraryController extends Zend_Controller_Action
{

    private $_user;

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        $actionname = $this->_request->getActionName();

        if (!isset($this->_user->id) && in_array($actionname, array('add', 'edit', 'delete'))) {
            $this->_redirect($this->view->url(array('module' => 'user', 'action' => 'login'), NULL, TRUE));
        }
        //Send nav selection information to view
        $username = $this->_request->getParam('user', NULL);
        if ($username != NULL) {

            $this->view->userNav = TRUE;
            $this->view->username = $username;

            if (isset($this->_user->username) && $this->_user->username === $username) {

                $this->view->ownNave = TRUE;
            }
        }
        if (in_array($actionname, array('add', 'edit'))) {

            $this->view->userNav = TRUE;
            $this->view->ownNave = TRUE;
        }
    }

    public function indexAction()
    {
        $username = $this->_request->getParam('user', NULL);

        $librarymodel = new Default_Model_Library();

        $paginator = new Zend_Paginator($librarymodel->getPaginatorAdapter($username));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);

        $this->view->paginator = $paginator;
    }

    public function addAction()
    {

        $this->_saveLibrary();
    }

    public function editAction()
    {

        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->view->error = 404;
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

        if (!isset($library->$id)) {
            $this->view->error = 404;
            return;
        } elseif ($library->added_by != $this->$user->id) {
            $this->view->error = 403;
            return;
        }
        if ($this->_request->ispost()) {
            $libraryModel->deleteResourceById($id);

            $this->redirect($this->view->url(array('controller' => 'Library'), NULL, TRUE));
        }
        $this->view->library = $library;
    }

    private function _saveLibrary()
    {
        $libraryModel = new Default_Model_Library();
        
        $commonModel = new Application_Model_Common();

        $form = new Default_Form_Library();

        //update
        $id = $this->_request->getParam('id');

        if (!empty($id)) {
            $library = $libraryModel->getRecordById($id);

            if (!isset($library->id)) {
                $this->view->error = 404;
                return;
            } elseif ($library->added_by != $this->_user->id) {
                $this->view->error = 403;
                return;
            }

            //populate form

            $form->populate($library->toArray());

            $this->view->file = $library->file;
            $this->view->thumbnail = $library->thumbnail;
        }

        //update
        if ($this->_request->isPost()) {
            //add rename filters
            $commonModel->addRenameFilter($form->file);
            $commonModel->addRenameFilter($form->thumbnail);

            if ($form->isvalid($this->_request->getPost())) {
                $data = $form->getValues();

                //update
                if (!empty($id)) {
                    //If files are not uploaded, don't overwrite existing records in db
                    if (empty($data['file'])) {
                        unset($data['file']);
                    }
                    if (empty($data['thumbnail'])) {
                        unset($data['thumbnail']);
                    }
                }
                
                //update
                //Added / Updated data
                $data['added_at'] = date('y-m-d h-i-s');
                $data['added_by'] = $this->_user->id;
                
                $data['share_status'] = $commonModel->resolveShareStatus($data['share_status']);
                
                $libraryModel->save($data);
                
                $this->_redirect($this->view->url(array('controller' => 'library'),NULL,FALSE));
                
            }
       
            }
       $this->view->form = $form; 
    }

}

?>
