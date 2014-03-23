<?php

class Default_RadioController extends Zend_Controller_Action
{

    private $_user;

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();

        if (!$this->view->isAdmin()) {
            $this->_redirect();
        }
    }

    public function indexAction()
    {
        $radioModel = new Default_Model_Radio();

        $paginator = new Zend_Paginator($radioModel->getPaginatorAdapter());
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $paginator->setCurrentPageNumber($this->getParam('page'));

        $this->view->paginator = $paginator;
    }
    
    public function addAction()
    {
        $this->_saveRadio();
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->view->error = 400;
            return;
        }
        $this->_saveRadio();
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');

        if (empty($id)) {
            $this->view->error = 400;
            return;
        }

        $radioModel = new Default_Model_Radio();

        $radio = $radioModel->getRecordById($id);

        if (!isset($radio->id)) {
            $this->view->error = 404;
            return;
        }

        if ($this->_request->isPost()) {
            $radioModel->deleteResourceById($id);

            $this->_redirect($this->view->url(array('controller' => 'radio'), NULL, TRUE));
        }

        $this->view->radio = $radio;
    }

    private function _saveRadio()
    {
        $radioModel = new Default_Model_Radio();

        $form = new Default_Form_Radio();

        //Update...
        $id = $this->_request->getParam('id');

        if (!empty($id)) {
            $radio = $radioModel->getRecordById($id);

            if (!isset($radio->id)) {
                $this->view->error = 404;
                return;
            }

            //Populate form
            $form->populate($radio->toArray());
        }
        //Update...

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();

                //Update...
                if (!empty($id)) {
                    $data['id'] = $id;
                }
                //...Update
                //Added / updated date
                $data['added_at'] = date('Y-m-d H:i:s');

                $radioModel->save($data);

                $this->_redirect($this->view->url(array('controller' => 'radio'), NULL, TRUE));
            }
        }

        $this->view->form = $form;
    }

}

