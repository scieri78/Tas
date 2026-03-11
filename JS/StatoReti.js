
$('#myform').submit(function(){
  $("#Waiting").show();
});

 
$(document).ready(function(){
   ridimensiona(); 
   ViewReale();
});

$(window).resize(function(){
   ridimensiona();
});

$("#ViewNoRun").click(function() {
    if ( $('#ViewNoRun').is(':checked')){
        $(".Nasco").removeClass("nascondi");
    } else {
        $(".Nasco").addClass("nascondi");
    }
});

function ridimensiona(){
   if ( $(window).width() < 0) {
    $(window).resizeTo(1106);
    $(html).resizeTo(1106);
   }   
   $("#contenitore").css( "width", $(window).width() ); 
}; 


function ViewReale(){
    if ( $('#ViewReal').is(':checked')){
        $(".real").addClass("nascondi");
        $(".noreal").removeClass("nascondi");
    } else {
        $(".real").removeClass("nascondi");
        $(".noreal").addClass("nascondi");      
    }
};

$("#ViewReal").click(function() {
   ViewReale();
});


$('#RetiStep > ul > li > a').click(function() {
  $('#RetiStep li').removeClass('active');
  $(this).closest('li').addClass('active');  
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }         
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    //$('#RetiStep ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;  
  }    
});

$('#RetiStep > ul > li > ul > li > a').click(function() {
  $('#RetiStep ul li').removeClass('active');
  $(this).closest('li').addClass('active');  
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    //$('#RetiStep ul ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;  
  }    
});    
