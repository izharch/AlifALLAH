<?php

class Application_View_Helper_LikeButton extends Zend_View_Helper_Abstract
{

    public function likeButton($item)
    {
        $user = $this->view->user();

        $loggedIn = isset($user->id);
        $liked = isset($item->liked) && $item->liked == 1;
        ?>
        <button class="btn btn-small like-button <?php echo ($loggedIn ? '' : 'disabled'); ?> <?php echo ($liked ? 'active' : ''); ?>" data-id="<?php echo $item->id; ?>" data-type="<?php echo $item->getTable()->info('name'); ?>">
            <i class="icon-heart"></i>
            <span><?php echo ($liked ? 'Liked' : 'Like'); ?></span>
        </button>
        <span class="like-count"><?php echo $item->likes; ?></span>
        <?php
    }

}
?>
