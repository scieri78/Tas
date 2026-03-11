
$(document).ready(function () {
    $('#Waiting').hide();

});

function inviaDatiEApriPagina(IdWorkFlow,IdProcess,IdPeriod) {
        // Definisci i dati da inviare 

        $('#IdWorkFlow').val(IdWorkFlow);
        $('#IdProcess').val(IdProcess);
        $('#IdPeriod').val(IdPeriod);
        console.log('inviaDatiEApriPagina',IdWorkFlow,IdProcess,IdPeriod)

      $('#WFForm').submit(); // URL della pagina da aprire
        

    }

function openProcessing(Sel_Esito){ 
 $("#Sel_Esito").val(Sel_Esito);
 $("#FormProcessing").submit();

}
async function openWF(id_workflow, flag_consolidato, workflow) {
    $('#Waiting').show();
    var formData = {
        id_workflow: id_workflow,
        flag_consolidato: flag_consolidato,
        workflow: workflow
    };
    console.log('openWF', formData);

    try {
        const risposta = await $.ajax({
            type: "POST",
            data: formData,
            encode: true,
            url: "index.php?controller=home&action=WfFlussi"
        });
        $("#Filedialog").html(risposta);
        $("#Filedialog").dialog({title: workflow, dialogClass: "dialog-full-mode"});
        $("#Filedialog").dialog("open");
        console.log("openWF terminata");
        $('#Waiting').hide();
    } catch (stato) {
        errorMessage("openWF: Qualcosa è andato storto!", stato);
    }
}
 var WFDBTERMINATO=1;

async function openWFDB(id_workflow, flag_consolidato, workflow) {
  $('#Waiting').hide();
  WFDBTERMINATO=0;
    var formData = {
        id_workflow: id_workflow,
        flag_consolidato: flag_consolidato,
        workflow: workflow
    };
    console.log('openWFDB', formData);

    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=home&action=WfFlussiDB",
        success: function(risposta) {
          WFDBTERMINATO=1;
            console.log("openWFDB terminata");
        },
        error: function(stato) {
            errorMessage("openWFDB: Qualcosa è andato storto!", stato);
        }
    });
}

// Esegui openWF e openWFDB in parallelo
function executeWorkflow(id_workflow, flag_consolidato, workflow) {
    
    openWF(id_workflow, flag_consolidato, workflow); // Esegui openWF e attendi solo il suo completamento
     setTimeout(function () {
      if(WFDBTERMINATO==1){
    openWFDB(id_workflow, flag_consolidato, workflow); // Esegui openWFDB senza attendere
    }
      // printLegend(); 
    }, 200);
   
}

//executeWorkflow(id_workflow, flag_consolidato, workflow);
   function refreshHome()
{
      //$('#Waiting').show();
		// var formData = {};			   
			var id_workflow = $("#id_workflow").val(); 				
			var flag_consolidato =  $("#flag_consolidato").val(); ; 				
			//formData['workflow'] =  $("#workflow").val();
      var workflow =$("#workflow").val();
       executeWorkflow(id_workflow,flag_consolidato,workflow);
			console.log('refreshHome');			 		
/*
			$.ajax({
			 type: "POST",
			  data: formData,
			  encode: true,	   
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=home&action=WfFlussi",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#Filedialog").html(risposta);
			      $( "#Filedialog" ).dialog({title: workflow, dialogClass:"dialog-full-mode"});
			      $("#Filedialog").dialog("open");
             $('#Waiting').hide();
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("openDialogMail: Qualcosa è andato storto!", stato);
          }
        });*/
		 
	 
	 }