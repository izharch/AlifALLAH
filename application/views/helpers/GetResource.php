<?php


class Application_View_Helper_GetResource extends Zend_View_Helper_Abstract {

    public function getResource($resourceName, $resourceType = 'img', $dir = 'resources') {
        return "{$this->view->baseUrl()}/$dir/$resourceType/$resourceName";
    }

}

?>
