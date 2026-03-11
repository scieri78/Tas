$(document).ready(function () {
	$('#Waiting').hide();
	
	});

function addShell(){		    
		var SHELL_PATH=$('#ShellPath').val();
		var SHELL=$('#ShellName').val();
	
			   var formData = {};
			   let obj_form = $("form#FormMail").serializeArray();
					obj_form.forEach(function (input) { 
					 formData[input.name] =input.value; 
					});	   
							
			console.log(formData);					
			if($('#ShellName').val()==""){
				alert("Inserire Shell Name!");
				return false;
			}
			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	    
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=shelladd&action=addShell&SHELL_PATH="+SHELL_PATH+"&SHELL="+SHELL, 
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("addShell: Qualcosa è andato storto!", stato);
          }
        });
		
		}
		
function removeSh(ID_SH){		    
	var re = confirm('Are you sure you want remove this Sh?');
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
          url: "index.php?controller=shelladd&action=removeSh&ID_SH="+ID_SH, 
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#contenitore").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("removeSh: Qualcosa è andato storto!", stato);
          }
        });
		
		}	
	}	


		
function modificaSh(){		    
	var re = confirm('Vuoi modificare questo Sh?');
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
          url: "index.php?controller=shelladd&action=modificaSh", 
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
	


			
function formSh(SHELL,SHELL_PATH,ID_SH,PARALL){		    
	
		 
			   var formData = {};
			  formData['SHELL']=SHELL;
			  formData['SHELL_PATH']=SHELL_PATH;
			  formData['ID_SH']=ID_SH;
			  formData['PARALL']=PARALL;			  
			console.log(formData);					

			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	  	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=shelladd&action=formSh", 
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