<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Messages
 *
 * @author Muhammad Adeel Nawaz
 */
class Application_View_Helper_Messages extends Zend_View_Helper_Abstract
{

    public function messages()
    {
        if ($this->view->errorMessage) {
            ?>
            <div class="alert alert-error margin-top-15">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->view->errorMessage; ?>
            </div>
            <?php
        }
        if ($this->view->successMessage) {
            ?>
            <div class="alert alert-success margin-top-15">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->view->successMessage; ?>
            </div>
            <?php
        }
    }

}
?>
