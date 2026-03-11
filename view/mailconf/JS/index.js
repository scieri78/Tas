$(document).ready(function () {
	$('#Waiting').hide();
	});


function setTextarea(){
    console.log('setTextarea');
    $("textarea").css('height', '90%');
    $("textarea").css('width', '98%');
  }

function openDialogMail(vIdSh,SHELL){
  console.log('openDialogMail');
  var formData = {};
  formData['id'] = vIdSh;
  formData['action'] = 'addShMail';
  console.log(formData);
  console.log('controller=mailconf&action=addShMail');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,   
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=mailconf&action=addShMail",  
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
	
	
function openDialogFile(vIdSh,SHELL){
  console.log('openDialogFile');
  var formData = {};
  formData['IDSH'] = vIdSh;
 // formData['action'] = 'apriFile';
  console.log(formData);
  console.log('controller=statoshell&action=apriFile');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true, 
           
          // specifico la URL della risorsa da contattare
         // url: "index.php?controller=statoshell&action=apriFile&IDSH="+vIdSh+"",  
          url: "index.php?controller=statoshell&action=apriFile",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div html  
			$("#Filedialog").dialog({title: SHELL});
			$("#Filedialog").dialog("open");				
      $("#Filedialog").html(risposta);
      setTimeout(setTextarea, 500);
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("openDialogFile: Qualcosa è andato storto!", stato);
          }
        });
		 
	 
	}
	 

    function removeShMail(vIdSh){  
      var re = confirm('Are you sure you want remove this Sh?');
      if ( re == true) {	
        UpdateShMail('N',vIdSh);
      }
   }   
 
	 
function UpdateShMail(Mval,vIdSh){  
var sel = document.getElementById("Sel_EnableShMail");
var seltext = sel.options[sel.selectedIndex].text;

var mess = 'Are you sure you want add '+seltext+'?';
  if(Mval == 'N')
  {
    mess = 'Are you sure you want remove this Sh?';
  }
  var re = confirm(mess);
   if ( re == true) {	
		 var formData = {};
     console.log('UpdateShMail');
		let obj_form = $("form#FormMail").serializeArray();
		obj_form.forEach(function (input) { 
		 formData[input.name] =input.value; 
		});
    formData['vIdSh'] =vIdSh; 
    formData['mval'] =Mval; 
		console.log(formData);
		 $.ajax({
      type: "POST",
		   data: formData,
		   encode: true,	             
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=mailconf&action=updateShMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
          $("#contenitore").html(risposta);		 
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("UpdateShMail: Qualcosa è andato storto!", stato);
          }
        });
	}else  {
    $('#FormMail').submit();
  }  
}   
 

	   

	   

   
			   
 $(".selectSearch").select2();
 $('.selectNoSearch').select2({minimumResultsForSearch: -1});


$(document).on( 'init.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
 
    console.log( 'New DataTable created:', api.table().page() );
} );

$('#idTabella').DataTable({
	//order: [],
	//page:1,
	
	language: {
				"url": "./JSON/italian.json"
			  },
			  "lengthMenu": [ [-1,10, 25, 50,100], ["All",10, 25, 50,100] ],
					  columnDefs: [{ orderable: false, targets: [5] }]
            
               });		
	
