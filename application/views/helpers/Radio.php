<?php

class Application_View_Helper_Radio extends Zend_View_Helper_Abstract
{

    public function radio()
    {
        $radioModel = new Default_Model_Radio();

        $channels = $radioModel->getActiveChannels();

        $default = $channels->count() ? $channels[0]['source'] : FALSE;
        ?>
        <div class="btn-group radio-toggle">
            <?php if ($default) { ?>
                <div id="jquery_jplayer_radio" class="jp-jradio" ancestor="#jp_container_radio" extension="mp3" media="<?php echo $default; ?>"></div>
                <div id="jp_container_radio" class="jp-radio jp-gui display-inline">
                    <a class="btn btn-transparent cursor-pointer jp-play" title="Play Radio"><img src="<?php echo $this->view->getResource('radio-paused.png'); ?>"/></a>
                    <a class="btn btn-transparent cursor-pointer jp-pause" title="Pause Radio"><img src="<?php echo $this->view->getResource('radio-playing.png'); ?>"/></a>
                </div>
            <?php } else { ?>
                <a class="btn btn-transparent cursor-pointer jp-play" title="Radio Unavailable"><img src="<?php echo $this->view->getResource('radio-unavailable.png'); ?>"/></a>
            <?php } ?>
            <a data-toggle="dropdown" class="btn btn-transparent dropdown-toggle"><span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php if ($default) { ?>
                    <?php foreach ($channels as $channel) { ?>
                        <li><a class="jp-jradio-media cursor-pointer" extension="mp3" media="<?php echo $channel['source']; ?>"><?php echo $channel['title']; ?></a></li>
                    <?php } ?>
                <?php } ?>
                <?php if ($this->view->isAdmin()) { ?>
                    <?php if ($default) { ?>
                        <li class="divider"></li>
                    <?php } ?>
                    <li><a href="<?php echo $this->view->url(array('controller' => 'radio'), NULL, TRUE); ?>"><i class="icon-cog"></i> Manage</a></li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }

}