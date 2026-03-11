$(document).ready(function () {
	$('#Waiting').hide();
	});

function openDialogMail(vIdSh,SHELL){
		 var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
			formData['id'] = vIdSh; 				
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	   
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=addShMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			$( "#dialogMail" ).dialog({title: SHELL, dialogClass:"dialog-full-mode"});
			$("#dialogMail").dialog("open");
			
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("openDialogMail: Qualcosa è andato storto!", stato);
          }
        });
		 
	 
	 }
	
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
			
      


function RemoveSh(vIdSh){
	var re = confirm('Are you sure you want remove this Sh?');
	if ( re == true) {	
			
		 var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
					formData['vIdSh'] = vIdSh; 				
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	   	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=removeshmail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("RemoveSh: Qualcosa è andato storto!", stato);
          }
        });
		}
	}
       
function AddNewMail(){
	        console.log("AddNewMail");	
			var ID_SH=	$('#AddShMail').val();
			var ID_MAIL_ANAG=	$('#EnableMail').val();
			var FLG_START=	$('#AddStart').val();
			var FLG_END=	$('#AddEnd').val();
			
		  var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
            formData['ID_SH'] = ID_SH; 
            formData['ID_MAIL_ANAG'] = ID_MAIL_ANAG; 
            formData['FLG_START'] = FLG_START; 
            formData['FLG_END'] = FLG_END;          
			
							
			console.log(formData);					
		if ($('#AddShMail').val() == '' || $('#EnableMail').val() == '' ) {
			alert("Empty fields!");
			return false;
		}
			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	   	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=addmail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("AddNewMail: Qualcosa è andato storto!", stato);
          }
        });
		}

 $(".selectSearch").select2();
 $('.selectNoSearch').select2({minimumResultsForSearch: -1}); 