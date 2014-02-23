<?php

class Application_View_Helper_JPlayer extends Zend_View_Helper_Abstract
{

    private static $_index = 0;

    public function jPlayer($media, $title)
    {
        $index = self::$_index++;

        $extension = pathinfo($media, PATHINFO_EXTENSION);
        ?>
        <div id="jquery_jplayer_<?php echo $index; ?>" class="jp-jplayer" ancestor="#jp_container_<?php echo $index; ?>" extension="<?php echo $extension; ?>" media="<?php echo $this->view->getResource($media, 'media', 'uploads'); ?>"></div>
        <div id="jp_container_<?php echo $index; ?>" class="jp-audio">
            <div class="jp-type-single">
                <div class="jp-gui jp-interface">
                    <div class="jp-title pull-left">
                        <ul>
                            <li><?php echo $title; ?></li>
                        </ul>
                    </div>
                    <ul class="jp-controls">
                        <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                        <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                        <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                        <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                        <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                        <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
                    </ul>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>

                        </div>
                    </div>
                    <div class="jp-volume-bar">
                        <div class="jp-volume-bar-value"></div>
                    </div>
                    <div class="jp-current-time"></div>
                    <div class="jp-duration"></div>
                    <ul class="jp-toggles">
                        <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                        <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                    </ul>
                </div>
                <div class="jp-no-solution">
                    <span>Update Required</span>
                    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                </div>
            </div>
        </div>
        <?php
    }

}
?>
