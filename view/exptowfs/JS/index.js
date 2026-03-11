$(document).ready(function () {
	$('#Waiting').hide();
	
		$('#idTabella').DataTable({
		
		language: {
					"url": "./JSON/italian.json"
				  },
				  "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ]
						/*  columnDefs: [
					{ orderable: false, targets: [2] }
				  ]*/
                });
	});
	
$(".selectSearch").select2();
$('.selectNoSearch').select2({minimumResultsForSearch: -1});


function openDialogMail(vIdSh,SHELL){
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
          url: "index.php?controller=exptowfs&action=addShMail&id="+vIdSh+"",  
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
	

			
 function SetShWfs(IdSh,flag){
		var mess="";
		if(flag=='Y'){
			mess='Are you sure you want Enable the Wfs to use this Shell?';
			}
		else{ 
			mess='Are you sure you want Remove this Shell from the Wfs?';
		}
			var re = confirm(mess);
         
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
          url: "index.php?controller=exptowfs&action=setshwfs&IdSh="+IdSh+"&flag="+flag,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("SetShWfs: Qualcosa è andato storto!", stato);
          }
        });

		   
		   }
	   }
	   
 function SetBlockWfs(IdSh,flag){
		var mess="";
		if(flag=='Y'){
			
			mess='Are you sure you want enable the readonly from this Shell from the Wfs?';
			}
		else{ 
			mess='Are you sure you want disable the readonly from this Shell from the Wfs?';
		}
			var re = confirm(mess);
         
           if ( re == true) { 
		   var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
		$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	   	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=exptowfs&action=setblockwfs&IdSh="+IdSh+"&flag="+flag,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
			errorMessage("SetBlockWfs: Qualcosa è andato storto!", stato);
          }
        });

		   
		   }
	   }


