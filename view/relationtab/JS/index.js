$(document).ready(function () {
			$('#Waiting').hide();
		
$('#idTabella').DataTable({
	//order: [],
	//page:1,
	
	language: {
				"url": "./JSON/italian.json"
			  },
			  "lengthMenu": [ [-1,10, 25, 50,100], ["All",10, 25, 50,100] ]
					
               });	

	$("form#FormTabDdl").submit(function (event) {
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
          url: "index.php?controller=ddltabcreator&action=contentList",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
		//  $("#shellContent").html("");
            $("#contenitore").html(risposta);
			$('#Waiting').hide();
			
			
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("ddltabcreator: Qualcosa è andato storto!", stato);
          }
        }).done(function (data) {
		
     
    });

    event.preventDefault();
  });
  
$(".selectSearch").select2();
$('.selectNoSearch').select2({minimumResultsForSearch: -1});



function OpenFile(vIdSh){
    window.open('./PHP/ApriFile.php?IDSH='+vIdSh);
}


});




function openDialog(vIdSh, SHELL, vaction) {

  console.log('openDialog');
  var formData = {};
  formData['IDSH'] = vIdSh;

 // clearAutoRefresh();
  // formData['action']=vaction;
  // formData['SHELL']=SHELL;
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=statoshell&action=" + vaction,
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div htmlm


      //$("#Filedialog").dialog({title: SHELL});
      $("#Filedialog").dialog("open");
      $("#Filedialog").html(risposta);
      setTimeout(setTextarea, 500);

      var Log = $("#Log").val();
      var idhs = $("#IDHS").val();
      var SHELLLOG = "LOG: (" + idhs + ") " + Log;
      var SHELLFILE = "SHELL: (" + idhs + ") " + Log;
      if (vaction == 'apriFile') {
        SHELL2 = SHELLFILE;
      } else {
        SHELL2 = SHELLLOG;
      }
      console.log(SHELL2);
      $("#Filedialog").dialog({ title: SHELL2 });
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("openDialog: Qualcosa è andato storto!", stato);
    }
  });


}


function gotoDDLPackage(Schema,Object){
  var data = { Sel_PkgSchema: Schema, Sel_PkgName:Object};


openWindowWithPost('./index.php?controller=ddlPackage&action=index', data, Schema+Object);

}



function downloadExport() {

  var formData = {};

  formData['Sel_Schema'] = $('#Sel_Schema').val();
  formData['Sel_Object'] = $('#Sel_Object').val();
  console.log("downloadExport index.php?controller=relationtab&action=downloadfile");
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=relationtab&action=downloadfile",
    // imposto l'azione in caso di successo
    success: function (risposta) {   
      console.log(risposta);         //visualizzo il contenuto del file nel div htmlm
      const downloadFile = JSON.parse(risposta);
      console.log(downloadFile);
      window.open(downloadFile);
    
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

 



	 

