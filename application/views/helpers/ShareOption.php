<?php

class Application_View_Helper_ShareOption extends Zend_View_Helper_Abstract
{

    public function shareOption($item)
    {
        if ($this->view->isAdmin() && $item->share_status == 'pending') {
            ?>
            <li>
                <a class="approve-button cursor-pointer" data-id="<?php echo $item->id; ?>" data-type="<?php echo $item->getTable()->info('name'); ?>">Approve</a>
            </li>
            <li>
                <a class="approve-button disapprove cursor-pointer" data-id="<?php echo $item->id; ?>" data-type="<?php echo $item->getTable()->info('name'); ?>">Disapprove</a>
            </li>
            <?php
        }
    }

}
?>
