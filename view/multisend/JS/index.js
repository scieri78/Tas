$(document).ready(function () {
	$('#Waiting').hide();
	});

function DisableShMultiSend(vIdSh){
           var re = confirm('Are you sure you want Disable Multi Send File Input setting?');
           if ( re == true) { 		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShMultiSend")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   
function ShMultiSend(vIdSh, flag,mod){
	console.log('ShMultiSend');
	console.log('Sel_EnableMultiSend:'+$("#Sel_EnableMultiSend").val());
	  if (mod=='Y' || $("#Sel_EnableMultiSend").val()!=null) { 
		   var re;
			if (flag == 'Y'){
			re = confirm('Are you sure you want Enable Multi Send File Input setting?');	
			}
		   else{
			re = confirm('Are you sure you want Disable Multi Send File Input setting?');

		   }
   if ( re == true) { 
			   var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
							
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	  
			  
			 // specifico la URL della risorsa da contattare
			 url: "index.php?controller=multisend&action=addMultiSend&flag="+flag+"&idSh="+vIdSh,  
			 // imposto l'azione in caso di successo
			 success: function(risposta){
			 //visualizzo il contenuto del file nel div htmlm
				$("#contenitore").html(risposta);
				

			 },
			 //imposto l'azione in caso di insuccesso
			 error: function(stato){
				errorMessage("ShMultiSend: Qualcosa è andato storto!", stato);
			}
		  });
		}else{
			console.log('else Sel_EnableMultiSend:'+$("#Sel_EnableMultiSend").val());
			$("#Sel_EnableMultiSend").select2("val", "");
		}
	}
}	



	   
   
	   $('#Sel_EnableMultiSend').change(function(){ 
	      var Val=$('#Sel_EnableMultiSend').val();
		  if ( Val != '' ){
		    ShMultiSend(Val, "Y");
		  }
	   });	
	   


		// $('#idTabella').DataTable({
						  // language: {
							// "url": "./JSON/italian.json"
						  // }
						// });
						
$('#idTabella').DataTable({
				  order: [],
				  language: {
                    "url": "./JSON/italian.json"
                  },
				  "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ],
				  columnDefs: [
					{ orderable: false, targets: [2] }
				  ]
                });
				
 $(".selectSearch").select2();
 $('.selectNoSearch').select2({minimumResultsForSearch: -1});