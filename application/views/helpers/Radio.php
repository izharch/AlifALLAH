<?php

class Application_View_Helper_Radio extends Zend_View_Helper_Abstract
{

    public function radio()
    {
        $radioModel = new Default_Model_Radio();

        $channels = $radioModel->getActiveChannels();

        $default = isset($channels[0]) ? $channels[0]['source'] : FALSE;
        ?>
        <div class="btn-group radio-toggle">
            <?php if ($default) { ?>
                <div id="jquery_jplayer_radio" class="jp-jradio" ancestor="#jp_container_radio" extension="mp3" media="<?php echo $default; ?>"></div>
                <div id="jp_container_radio" class="jp-radio jp-gui display-inline">
                    <a href="javascript:;" class="btn btn-transparent cursor-pointer jp-play" tabindex="1">play</a>
                    <a href="javascript:;" class="btn btn-transparent cursor-pointer jp-pause" tabindex="1">pause</a>
                </div>
            <?php } else { ?>
                <a class="btn btn-transparent cursor-pointer disabled">Radio</a>
            <?php } ?>
            <a data-toggle="dropdown" class="btn btn-transparent dropdown-toggle"><span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php if ($channels->count()) { ?>
                    <?php foreach ($channels as $channel) { ?>
                        <li><a class="jp-jradio-media cursor-pointer" extension="mp3" media="<?php echo $channel['source']; ?>"><?php echo $channel['title']; ?></a></li>
                    <?php } ?>
                <?php } ?>
                <?php if ($this->view->isAdmin()) { ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo $this->view->url(array('controller' => 'radio'), NULL, TRUE); ?>"><i class="icon-cog"></i> Manage</a></li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }

}