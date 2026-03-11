$(document).ready(function () {
$('#menustat ul li a').click(function() {
    $('#menustat li').removeClass('active'); 
    $(this).closest('li').css('display','block');
    
    var checkElement = $(this).next();
    
    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
      $(this).closest('li').removeClass('active');
      checkElement.slideUp('normal');
    }
    
    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
      $('#menustat ul ul:visible').slideUp('normal');
      checkElement.slideDown('normal');
    }
      
});

$('#menustat ul ul li a').click(function() {
    $('#menustat li').removeClass('active'); 
    $(this).closest('li').css('display','block');
    
    var checkElement = $(this).next();
    
    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
      $(this).closest('li').removeClass('active');
      checkElement.slideUp('normal');
    }
    
    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
      $('#menustat ul ul ul:visible').slideUp('normal');
      checkElement.slideDown('normal');
    }
      
});

 $(".Colleg").click(function(){
     $("#Waiting").show();
     $(".UlColleg").slideUp('normal');
 });
 
} );