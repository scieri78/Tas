$(document).ready(function () {
			$('#Waiting').hide();
		

});
//searchcollInvia
function searchcollInvia(event) {
            // Controlla se il tasto premuto è "Enter"
            if (event.key === 'Enter') {
                searchcoll();
            }
        }

function searchcoll(){

  console.log('searchcoll');
  var formData = {};
      let obj_form = $("form#Formsearchcoll").serializeArray();
       obj_form.forEach(function (input) { 
        formData[input.name] =input.value; 
       });	   
   			
   console.log(formData);					

   $.ajax({
    type: "POST",
     data: formData,
     encode: true,	   
        
       // specifico la URL della risorsa da contattare
       url: "index.php?controller=searchcoll&action=contentList",  
       // imposto l'azione in caso di successo
       success: function(risposta){
        $("#contenitore").html(risposta);
			$('#Waiting').hide();
   
       },
       //imposto l'azione in caso di insuccesso
       error: function(stato){
         errorMessage("searchcoll: Qualcosa è andato storto!", stato);
       }
     });
  

}


function gotorelationtab(Schema,Object){
  var data = { Sel_Schema: Schema, Sel_Object:Object};
  openWindowWithPost('./index.php?controller=relationtab&action=index', data, Schema+Object);
}


function downloadExport() {

  var formData = {};

  formData['COLNAME'] = $('#COLNAME').val();
 // formData['ID_GRUPPO'] = $('#selectGruppo').val();
  console.log("downloadExport index.php?controller=searchcoll&action=downloadfile");
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=searchcoll&action=downloadList",
    // imposto l'azione in caso di successo
    success: function (risposta) {   
      console.log(risposta);         //visualizzo il contenuto del file nel div htmlm
      const downloadFile = JSON.parse(risposta);
      console.log(downloadFile);
      window.open("./TMP/"+downloadFile+ ".xlsx");
    
     //  $("#dialogMail").dialog("open");
     // $('#dialogMail').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("downloadExport: Qualcosa è andato storto!", stato);
    }

  });
  
}

 





	 

