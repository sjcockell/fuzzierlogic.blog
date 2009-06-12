//Header Bump JS (inactive)
addEventListener("load",function()
{ setTimeout(updateLayout,0);setTimeout(function(){window.scrollTo(0,1);},100);},false);var currentWidth=0;function updateLayout()
{if(window.innerWidth!=currentWidth)
{currentWidth=window.innerWidth;var orient=currentWidth==320?"profile":"portrait";document.body.setAttribute("orient",orient);setTimeout(function()
{window.scrollTo(0,1);},500);}}
setInterval(updateLayout,400);
//Start jQuery stuff
var $wptouch = jQuery.noConflict();
var menu_loaded = 0;
function bnc_load_menu(loc) {
    if (menu_loaded == 0) {
        $wptouch.get(loc, {}, function(data) { $wptouch('#dropmenu').html(data); $wptouch('#dropmenu').slideToggle();  } );
    } else {
        $wptouch('#dropmenu').slideToggle();
    }
    menu_loaded = 1;
}
function bnc_scroll_comment(comment_num) {
     var h = $("body").height();
    var two = parseInt(comment_num)+1;
    var first = $('#comment-num-' + comment_num).offset().top;
    var numbertwo = $('#comment-num-' + two).offset().top;
    var diff = numbertwo - first;
    //alert($(window).scrollTop());
    //alert($(document).scrollTop());
    //alert($('body').scrollTop());
   // $(window).scrollTop(numbertwo - 20);
    $J(document).scrollTop($(document).scrollTop()+diff);  
    //$(window).animate({scrollTop: diff}, 1000)
}
