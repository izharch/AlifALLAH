<?php

class Application_View_Helper_LikeButton extends Zend_View_Helper_Abstract
{

    public function likeButton($item)
    {
        ?>
        <button class="btn btn-small like-button <?php echo ($item->liked == 1 ? 'active' : ''); ?>" data-id="<?php echo $item->id; ?>" data-type="media">
            <i class="icon-heart"></i>
            <span><?php echo ($item->liked == 1 ? 'Liked' : 'Like'); ?></span>
        </button>
        <span class="like-count"><?php echo $item->likes; ?></span>
        <?php
    }

}
?>
