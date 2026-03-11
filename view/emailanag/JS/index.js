$(document).ready(function () {
	$('#Waiting').hide();
	});

  $('#idTabella').DataTable({
				  order: [],
				  language: {
                    "url": "./JSON/italian.json"
                  },
				  "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ],
				  columnDefs: [
					{ orderable: false, targets: [7] }
				  ]
				      // responsive: true

                });

			 		
	 
function updateMailAnag(vIdSh,filed,uval){
		
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
          url: "index.php?controller=emailanag&action=update&field="+filed+"&id="+vIdSh+"&uval="+uval,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("updateMailAnag: Qualcosa è andato storto!", stato);
          }
        });
		
		}	
		
		
	
function deleteMailAnag(vIdSh,userid){
	
	
	var re = confirm('Are you sure you delete user id '+userid+' ?');	
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
          url: "index.php?controller=emailanag&action=delete&id="+vIdSh,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
			errorMessage("deleteMailAnag: Qualcosa è andato storto!", stato);
          }
        });
		}
	}
		
function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }
			
function addMailAnag(){
		    var AddUsername= $("input[name=AddUsername]").val();
			var AddName = $("input[name=AddName]").val();
			var AddMail = $("input[name=AddMail]").val();
			var formData = {};
			let obj_form = $("form#FormMail").serializeArray();
			obj_form.forEach(function (input) { 
				formData[input.name] =input.value; 
				});	   
				
			console.log(formData);	
			
			if(AddUsername=='' )
			{
				alert("Inserire Username!");
				return false;
			}
			
			if(AddName=='' )
			{
				alert("Inserire Nome e Cognome!");
				return false;
			}
			
			if(AddMail=='' || !IsEmail(AddMail))
			{
				alert("Email non valida!");
				return false;
			}
			
			
			AddUsername = AddUsername.toUpperCase();
			
			
		 $.ajax({
          type: "POST",
		   data: formData,
		   encode: true,  
         
		
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=emailanag&action=addnewmail&AddUsername="+AddUsername+"&AddName="+AddName+"&AddMail="+AddMail,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);	

			$("input[name=AddUsername]").val("");
			$("input[name=AddName]").val("");
			$("input[name=AddMail]").val("");
			//alert("Utente Inserito!");	

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("addMailAnag: Qualcosa è andato storto!", stato);
          }
        });

}

		function modificaSh(){		    
	var re = confirm('Are you sure you want modify this Sh?');
	if ( re == true) {
		 
			   var formData = {};
			   let obj_form = $("form#FormSh").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
							
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	  	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=emailanag&action=modificaSh", 
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
		  $("#dialogMail").dialog("close");
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("removeSh: Qualcosa è andato storto!", stato);
          }
        });
		
		}	
	}	
	 	


			
function formSh(NAME,USERNAME,MAIL,ID_MAIL_ANAG){		    
	
		 
			   var formData = {};
			  formData['NAME']=NAME;
			  formData['USERNAME']=USERNAME;
			  formData['MAIL']=MAIL;
			  formData['ID_MAIL_ANAG']=ID_MAIL_ANAG;
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	  	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=emailanag&action=formSh", 
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $('#dialogMail').html(risposta);
			$("#dialogMail").dialog("open");
			$("#dialogMail").dialog({ title: 'Modifica'});
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("removeSh: Qualcosa è andato storto!", stato);
          }
        });
		
		
	}	
		 
	 
	
				