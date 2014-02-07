$(function() {
    //jQuery UI
    $( ".jquery-ui-tabs" ).tabs();
    $( ".jquery-ui-menu" ).menu();

    //Bootsrtap
    $('.bootstrap-carousel').carousel();

    //Set sidebar height
    setSidebarMinHeight();
    $(window).resize(setSidebarMinHeight);
});

function setSidebarMinHeight(){
    $('.content-outer > .sidebar').css('min-height', 0);
    var height = $('.content-outer').innerHeight();
    $('.content-outer > .sidebar').css('min-height', height);
}