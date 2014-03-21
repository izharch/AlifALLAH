$(function() {
    //jQuery UI
    $( ".jquery-ui-tabs" ).tabs();
    $( ".jquery-ui-menu" ).menu();

    //Bootsrtap
    $('.bootstrap-carousel').carousel();

    //Set sidebar height
    setSidebarMinHeight();
    $(window).resize(setSidebarMinHeight);
    
    //Curtains
    initCurtains();
    
    //jPlayer
    initJPlayer();
    
    //back button
    initLinkToBack();
    
    //like button
    if(App.userId != null){
        initLikeButton();
    }
    
    //share button
    if(App.userId != null){
        initShareButton();
    }
    
    //approve button
    if(App.userId != null){
        initApproveButton();
    }
});

function setSidebarMinHeight(){
    $('.content-outer > .sidebar').css('min-height', 0);
    var height = $('.content-outer').innerHeight();
    $('.content-outer > .sidebar').css('min-height', height);
}

function initCurtains(){
    $('.curtain:not(.open)').each(function(){
        var curtain = $(this);
        if(curtain.is('.curtain-top-down')){
            curtain.css('top', -curtain.height());
        }
    });
    //Click
    $('.curtain .curtain-puller').on('click', function(){
        var curtain = $(this).parents('.curtain');
        var animation = null;
        if(curtain.is('.curtain-top-down')){
            animation = {
                top : (curtain.is('.open') ? -curtain.height(): 0)
            };
        }
        curtain.toggleClass('open').animate(animation, 300);;
    })
}

function initJPlayer(){
    var swfPath = AlifAllah.basePath + '/resources/plugins/jplayer';
    $('.jp-jplayer').each(function(){
        var player = $(this),
        playerId = player.attr('id'),
        extension = player.attr('extension'),
        ancestor = player.attr('ancestor');

        $('#'+playerId).jPlayer({
            swfPath: swfPath,
            solution: 'html, flash',
            supplied: extension,
            preload: 'metadata',
            volume: 0.8,
            muted: false,
            backgroundColor: '#000000',
            cssSelectorAncestor: ancestor,
            cssSelector: {
                videoPlay: '.jp-video-play',
                play: '.jp-play',
                pause: '.jp-pause',
                stop: '.jp-stop',
                seekBar: '.jp-seek-bar',
                playBar: '.jp-play-bar',
                mute: '.jp-mute',
                unmute: '.jp-unmute',
                volumeBar: '.jp-volume-bar',
                volumeBarValue: '.jp-volume-bar-value',
                volumeMax: '.jp-volume-max',
                playbackRateBar: '.jp-playback-rate-bar',
                playbackRateBarValue: '.jp-playback-rate-bar-value',
                currentTime: '.jp-current-time',
                duration: '.jp-duration',
                fullScreen: '.jp-full-screen',
                restoreScreen: '.jp-restore-screen',
                repeat: '.jp-repeat',
                repeatOff: '.jp-repeat-off',
                gui: '.jp-gui',
                noSolution: '.jp-no-solution'
            },
            errorAlerts: true,
            warningAlerts: true,
            ready: function () {
                var player = $(this);
                var media = {};
                media[player.attr('extension')] = player.attr('media');
                player.jPlayer( "setMedia", media);
            /*$(this).jPlayer("setMedia", {
                    mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3"
                });*/
            }
        });
    });
}

function initLinkToBack(){
    $('.link-to-back').one('click', function(){
        history.back();
    });
}

function initLikeButton(){
    $('.like-button').on('click', function(){
        var $this = $(this);
        
        if($this.hasClass('disabled')){
            return;
        }
        
        $this.addClass('disabled');
        
        var entityId = $this.attr('data-id'),
        entityType = $this.attr('data-type'),
        action = $this.hasClass('active') ? 'dislike' : 'like';
        
        $.ajax({
            url     : App.likeUrl,
            data    : {
                entity_id   : entityId, 
                entity_type : entityType, 
                act         : action
            },
            success : function(data){
                data = parseInt(data);

                $this.next('.like-count').html(data);
                $this.toggleClass('active');
                $this.find('span').html(action == 'like' ? 'Liked' : 'Like');
                $this.removeClass('disabled');
            }
        });
    });
}

function initShareButton(){
    $('.share-button').on('click', function(){
        var $this = $(this);
        
        if($this.hasClass('disabled')){
            return;
        }
        
        $this.addClass('disabled');
        
        var entityId = $this.attr('data-id'),
        entityType = $this.attr('data-type'),
        action = $this.hasClass('active') ? 'unshare' : 'share';
        
        $.ajax({
            url     : App.shareUrl,
            data    : {
                entity_id   : entityId, 
                entity_type : entityType, 
                act         : action
            },
            success : function(shareStatus){
                var shared = false,
                disabled = false;
                switch(shareStatus){
                    case 'not_shared':
                        shared = false;
                        disabled = false;
                        break;
                    case 'pending':
                    case 'shared':
                        shared = true;
                        disabled = false;
                        break;
                    case 'disapproved':
                        shared = false;
                        disabled = true;
                        break;
                    default:
                        shared = false;
                        disabled = true;
                }

                if(shared){
                    $this.addClass('active');
                }else{
                    $this.removeClass('active');
                }
                
                $this.find('span').html(shared ? 'Shared' : 'Share');
                if(!disabled){
                    $this.removeClass('disabled');
                }
            }
        });
    });
}

function initApproveButton(){
    $('.approve-button').on('click', function(){
        var $this = $(this);
        
        var approveButtons = $this.parent().parent().find('.approve-button');
        
        approveButtons.addClass('disabled');
        
        var entityId = $this.attr('data-id'),
        entityType = $this.attr('data-type'),
        action = $this.hasClass('disapprove') ? 'disapprove' : 'approve';
        
        $.ajax({
            url     : App.shareUrl,
            data    : {
                entity_id   : entityId, 
                entity_type : entityType, 
                act         : action
            },
            success : function(shareStatus){
                window.location.reload();
            }
        });
    });
}