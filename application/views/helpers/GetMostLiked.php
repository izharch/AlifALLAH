<?php

class Application_View_Helper_GetMostLiked extends Zend_View_Helper_Abstract
{

    public function getMostLiked($limit = 5)
    {
        if (isset($this->view->mostLiked['limit']) && $this->view->mostLiked['limit'] == $limit) {
            return $this->view->mostLiked;
        } else {
            $commonFunctions = new Application_Model_Common();

            return $commonFunctions->getMostLiked($limit);
        }
    }

}

?>
