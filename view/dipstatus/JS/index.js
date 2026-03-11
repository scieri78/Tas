$(document).ready(function () {
$('#Waiting').hide();
$('#SalvaChange').hide(); 
}); 



function submitForm(){
	var formData = {};
		
		let obj_form = $("form#FormStatus").serializeArray();
		obj_form.forEach(function (input) { 
		 formData[input.name] =input.value; 
		});
		console.log('submitForm action=contentList');
		console.log(formData);
		//return false;
		$.ajax({
		  type: "POST",
		  data: formData,
		  encode: true,	   	   
   
	  // specifico la URL della risorsa da contattare
	  url: "index.php?controller=dipstatus&action=contentList", 
	  // imposto l'azione in caso di successo
	  success: function(risposta){
	  $("#contenitore").html(risposta);	
	  $('#Waiting').hide();	  
	 
	  },
	  //imposto l'azione in caso di insuccesso
	  error: function(stato){
      errorMessage("submitForm: Qualcosa è andato storto!", stato);
	  }
	});	
	
}


  function getWorkflow(){
    $('#Azione').val('');
      $('#SelWkf').val('');
       $('#SelFls').val('');
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');
	 	submitForm();	
	
    } 
	
	
	
	
	
	  function getFlusso(){
		  $('#Azione').val('');
       $('#SelFls').val('');
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');        
       $('#Waiting').show();
		submitForm();	
    };
       
    function getTipo(){
		$('#Azione').val('');
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');           
       $('#Waiting').show();
		submitForm();
    };
       
    function getDipendenza(){
		$('#Azione').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');           
       $('#Waiting').show();
       submitForm();
    };
       
   function getIdProc(){
	   $('#Azione').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');              
       $('#Waiting').show();
       $('#FormStatus').submit();
    };
       
    function getStato(){
	   $('#Azione').val('');
       $('#SelSt').val('');         
       $('#Waiting').show();
       $('#FormStatus').submit();
	   
    };
	
	 function setSave(HideSave){
		
       $('#SalvaChange').hide();
       var vtest=1;
       $('.ModSt').each(function(){
           if ( $(this).val() == '' ){ vtest=0;} 
       });
	   $('#Azione').val('Salva');
     	
	   if ( $('#SelSt').val() == '' ){ vtest=0;} 
       if ( vtest == 1 && HideSave == 'no' ){ $('#SalvaChange').show(); 
	   
	   } 
	   // $('#SalvaChange').val('');
	   
    };