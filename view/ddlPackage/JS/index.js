$(document).ready(function () {
	
	
			$('#Waiting').hide();
			$("textarea").css('height', ($(window).height()-350));
		$("textarea").css('width', ($(window).width()-40)); 
		$( window ).on( "resize", function() {
			$("textarea").css('height', ($(window).height()-350));
			$("textarea").css('width', ($(window).width()-40));
} );
		$("form#FormDdl").submit(function (event) {
	  $('#Waiting').show();
	  var formData = {};
   	
	let obj_form = $(this).serializeArray();
	obj_form.forEach(function (input) { 
       
	   formData[input.name] =input.value; 
            });

     $.ajax({
          type: "POST",
		   data: formData,
		   encode: true,	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=ddlPackage&action=contentList",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
		//  $("#shellContent").html("");
            $("#contenitore").html(risposta);
			$('#Waiting').hide();
			
			
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("ddlPackage: Qualcosa è andato storto!", stato);
          }
        }).done(function (data) {
		
     
    });

    event.preventDefault();
  });
	$(".selectSearch").select2();
	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
});


function copy() {
  $("textarea").select();  
    document.execCommand('copy');
  
}