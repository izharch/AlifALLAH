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