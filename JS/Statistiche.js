
function toggleDiv(divId) {
   $("#"+divId).toggle();
}

$(window).resize(function(){
   ridimensiona();
});

function ridimensiona(){
   if ( $(window).width() < 0) {
    $(window).resizeTo(1106);
    $(html).resizeTo(1106);
   }   
   $("#contenitore").css( "width", $(window).width() );  
};   

