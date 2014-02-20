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
});

function setSidebarMinHeight(){
    $('.content-outer > .sidebar').css('min-height', 0);
    var height = $('.content-outer').innerHeight();
    $('.content-outer > .sidebar').css('min-height', height);
}

function initCurtains(){
    $('.curtain').each(function(){
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
            animation = {top : (curtain.is('.open') ? -curtain.height(): 0)};
        }
        curtain.toggleClass('open').animate(animation, 300);;
    })
}