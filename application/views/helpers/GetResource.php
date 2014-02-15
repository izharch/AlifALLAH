<?php


class Application_View_Helper_GetResource extends Zend_View_Helper_Abstract {

    public function getResource($resourceName, $resourceType = 'img') {
        return "{$this->view->baseUrl()}/resources/$resourceType/$resourceName";
    }

}

?>
