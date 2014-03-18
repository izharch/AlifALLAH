<?php

class Application_View_Helper_ShareButton extends Zend_View_Helper_Abstract
{

    public function shareButton($item)
    {
        $user = $this->view->user();

        $loggedIn = isset($user->id);
        $shared = in_array($item->share_status, array('pending', 'shared'));
        $admin = $this->view->isAdmin();
        $owner = isset($user->id) && $user->id == $item->added_by;

        $enabled =
                //User should be logged in to share
                $loggedIn
                //item's status should not be disapproved
                && $item->share_status !== 'disapproved'
                //only owner and admins can unshare
                && (!$shared || $admin || $owner);
        ?>
        <button class="btn btn-small share-button <?php echo ($enabled ? '' : 'disabled'); ?> <?php echo ($shared ? 'active' : ''); ?>" data-id="<?php echo $item->id; ?>" data-type="<?php echo $item->getTable()->info('name'); ?>">
            <i class="icon-share"></i>
            <span><?php echo ($shared ? 'Shared' : 'Share'); ?></span>
        </button>
        <?php
    }

}
?>
