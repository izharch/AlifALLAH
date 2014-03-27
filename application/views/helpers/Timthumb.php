<?php

class Application_View_Helper_Timthumb extends Zend_View_Helper_Abstract
{

    public function timthumb($src, $w = NULL, $h = NULL, $zc = NULL)
    {
        $params = array(
            "src=$src",
        );
        if ($w !== NULL) {
            $params[] = "w=$w";
        }
        if ($h !== NULL) {
            $params[] = "h=$h";
        }
        if ($zc !== NULL) {
            $params[] = "zc=$zc";
        }

        $params = implode('&', $params);

        return "/plugins/timthumb/timthumb.php?$params";
    }

}

?>
