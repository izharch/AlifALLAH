$(function() {
    $( ".jquery-ui-tabs" ).tabs();
    $( ".jquery-ui-menu" ).menu();
    
    //Set sidebar height
    setSidebarMinHeight();
    $(window).resize(setSidebarMinHeight);
});

function setSidebarMinHeight(){
    $('.content-outer > .sidebar').css('min-height', 0);
    var height = $('.content-outer').innerHeight();
    $('.content-outer > .sidebar').css('min-height', height);
}