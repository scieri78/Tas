$(document).ready(function () {
  $('#Waiting').hide();
  $('#selectFOGLIO').hide();
  $('#labelFile').hide();
  $('#divFoglio').hide();
  $('#label_RIGA_INZ').hide();
  $('#addConfig').hide();
  $('#delConfig').hide();
  $('#modConfig').hide();
  
  //$('#divConfig').hide();
  //$('#LabeladdConfig').hide();
  $('#labelFile').hide();
  $('#addCheck').hide();
  restSession();


  $('#idTabellaInput').DataTable({
    "paging": false,
    "searching": false,

    language: {
      "url": "./JSON/italian.json"
    },
    order: [],
    /* "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ]*/
    columnDefs: [
      { orderable: false, targets: [0] }
    ]
  });

  $('#idTabellaTarget').DataTable({
    "paging": false,
    "searching": false,

    language: {
      "url": "./JSON/italian.json"
    },
    order: [],
    /* "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ]*/
    columnDefs: [
      { orderable: false, targets: [0] }
    ]
  });





  $("form#FormTabDdl").submit(function (event) {
    $('#Waiting').show();
    var formData = {};

    let obj_form = $(this).serializeArray();
    obj_form.forEach(function (input) {

      formData[input.name] = input.value;
    });
    console.log(formData);
    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=loadconfig&action=contentList",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  $("#shellContent").html("");
        $("#contenitore").html(risposta);
        $('#Waiting').hide();


      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("loadconfig: Qualcosa è andato storto!", stato);
      }
    }).done(function (data) {


    });

    event.preventDefault();
  });
  $(".selectSearch").select2();
  $('.selectNoSearch').select2({ minimumResultsForSearch: -1 });
});

function copy() {
  $("textarea").select();
  document.execCommand('copy');

}



function CreaConfigurazione() {

  $('#Waiting').show();
  var file = $("#FileInput")[0].files[0];

  if ($("#FileInput").val()) {
    if ($("#selectFOGLIO").val() == '' || $("#selectRIGA_INZ").val() == '') {
      alert("Seleziona Foglio e Riga!");
      $('#Waiting').hide();
      return false;
    }
  }

  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);
  console.log(formData.get('FileInput_NomeCampo'));
  //formData.delete('FileInput_NomeCampo');
  formData.set("FileInput_NomeCampo", '');
  formData.set("FileInput_Type", '');
  formData.set("selectFOGLIO", $('#selectFOGLIO').val());

  formData.set("Target_NAME", '');
  formData.set("Target_TYPE", '');
  formData.set("Target_FORMULA", '');

  console.log(formData);
  console.log(formData.get('FileInput_NomeCampo'));

  console.log(formData);
  console.log('CreaConfigurazione: controller=loadconfig&action=contentList"');
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    url: "index.php?controller=loadconfig&action=contentList",
    success: function (risposta) {
      $('#contenitore').html(risposta);
      $('#Sel_Object').selectpicker('refresh');
      $('#Sel_Schema').selectpicker('refresh');
      $('#contenitore').html(risposta);
      $('#Sel_Object').selectpicker('refresh');
      $('#Sel_Schema').selectpicker('refresh');
      $('#labelFile').hide();
      $('#divFoglio').hide();
      $('#label_RIGA_INZ').hide();
      $('#addConfig').hide();
      $('#delConfig').hide();
      $('#divConfig').hide();

      $('#Waiting').hide();
    },
    error: function (stato) {
      errorMessage("CreaConfigurazione: Qualcosa è andato storto!", stato);
    }

  });


}


function selSchema() {
   restSession(); 

  var file = $("#FileInput")[0].files[0];
  $('#ID_LOAD_ANAG').val('');
  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);
  console.log(formData.get('FileInput_NomeCampo'));
  //formData.delete('FileInput_NomeCampo');
  formData.set("FileInput_NomeCampo", '');
  formData.set("FileInput_Type", '');

  formData.set("Target_NAME", '');
  formData.set("Target_TYPE", '');
  formData.set("Target_FORMULA", '');

  console.log(formData);

  console.log('selSchema: controller=loadconfig&action=contentList"');
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    url: "index.php?controller=loadconfig&action=contentList",
    success: function (risposta) {

      $('#contenitore').html(risposta);
      $('#Sel_Object').selectpicker('refresh');
      $('#Sel_Schema').selectpicker('refresh');
      $('#labelFile').hide();
      $('#divFoglio').hide();
      $('#label_RIGA_INZ').hide();
      $('#addConfig').hide();
      $('#delConfig').hide();
      $('#divConfig').hide();
      $('#addCheck').hide();
      $('#modConfig').hide();
    },
    error: function (stato) {
      errorMessage("selSchema: Qualcosa è andato storto!", stato);
    }

  });


}

function ModificaConfigurazione() {
  $('#Waiting').show();
  var file = $("#FileInput")[0].files[0];
  if ($("#selectConfig").val()) {
    $("#ID_LOAD_ANAG").val($("#selectConfig").val());
    if ($("#FileInput").val()) {
      if ($("#selectFOGLIO").val() == '' || $("#selectRIGA_INZ").val() == '') {
        alert("Seleziona Foglio e Riga!");
        $('#Waiting').hide();
        return false;
      }
    }
  }


  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);
  console.log(formData.get('FileInput_NomeCampo'));
  //formData.delete('FileInput_NomeCampo');
  formData.set("FileInput_NomeCampo", '');
  formData.set("FileInput_Type", '');
  formData.set("Target_NAME", '');
  formData.set("Target_TYPE", '');
  formData.set("Target_FORMULA", '');
  formData.set("selectFOGLIO", $('#selectFOGLIO').val());

  console.log(formData);
  console.log(formData.get('FileInput_NomeCampo'));

  console.log(formData);
  console.log('ModificaConfigurazione: controller=loadconfig&action=modificaconfigurazione"');
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    url: "index.php?controller=loadconfig&action=modificaconfigurazione",
    success: function (risposta) {
      $('#contenitore').html(risposta);
      $('#Sel_Object').selectpicker('refresh');
      $('#Sel_Schema').selectpicker('refresh');
      $('#selectConfig').selectpicker('refresh');
      $('#labelFile').show();
      $('#divFoglio').hide();
      $('#label_RIGA_INZ').hide();
      $('#addConfig').hide();
      $('#delConfig').show();
      $('#divConfig').show();
      $('#addCheck').show();
      $('#Waiting').hide();
       $('#nuovaConfigurazione').hide();
       $('#nuovaConfigurazione2').hide();
       $('#modConfig').hide();
    },
    error: function (stato) {
      errorMessage("ModificaConfigurazione: Qualcosa è andato storto!", stato);
    }

  });


}




function OpenFoglio(nomeFoglio) {
  $('#Waiting').show();
  $('#divFoglio').show();
  $('#label_RIGA_INZ').show();
 if ($('#ID_LOAD_ANAG').val()) {
    $('#modConfig').show();
  } else {

    $('#addConfig').show();
  }
  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);

  console.log('OpenFoglio; controller=loadconfig&action=openfoglio');
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=openfoglio",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div html
       console.log(risposta);
     
   
      $('#selectFOGLIO').empty();
      $('#selectFOGLIO option').remove();
      /* $('#selectFOGLIO').append($('<option>', {
         value: '',
         text: '-- SELEZIONA FOGLIO --'
       }));*/
      $('#selectFOGLIO').selectpicker('refresh');
      const dataAsArray = JSON.parse(risposta);
      console.log(dataAsArray);
      var icon;
      var sel;
      $.each(dataAsArray, (index, row) => {
        console.log(row);

        sel = "";
        if (nomeFoglio == row) sel = "selected";
        $('<option ' + sel + '>').val(row).text(row).appendTo('#selectFOGLIO');
        $('#selectFOGLIO').selectpicker('refresh');
      });
      if (dataAsArray.length === 0 && nomeFoglio) {
        $('<option selected>').val(nomeFoglio).text(nomeFoglio).appendTo('#selectFOGLIO');
        $('#selectFOGLIO').selectpicker('refresh');
      }
      // $("form#FormMain").submit();

      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenFoglio: Qualcosa è andato storto!", stato);
    }
  });
}





function salvaConfigurazione(nuovo) {
  $('#Waiting').show();
  var file = $("#FileInput")[0].files[0];
  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);
  nuovo = typeof nuovo === 'undefined' ? 0 : 1;
  formData.append("file", file);
  formData.append("nuovo", nuovo);
  console.log('salvaConfigurazione');
  console.log(formData);
  console.log('controller=loadconfig&action=insertconfig"');
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    url: "index.php?controller=loadconfig&action=insertconfig",
    success: function (risposta) {
      $('#contenitore').html(risposta);
      /*  $('#Sel_Object').val('');
        $('#Sel_Schema').val('');*/
      $('#Sel_Object').selectpicker('refresh');
      $('#Sel_Schema').selectpicker('refresh');
      $('#FOGLIO').selectpicker('refresh');
      $('#message').html("Configurazione Inserita Correttamente!");
      $('#alert').show();
      window.scrollTo(0, 0);
      // $('#contenitore').html('');
      $('#labelFile').hide();
      $('#divFoglio').hide();
      $('#label_RIGA_INZ').hide();
      $('#addConfig').hide();
      $('#delConfig').hide();
      $('#divConfig').hide();
      $('#Waiting').hide();
      $('#addCheck').show();
      $('#LabeladdConfig').show();
      $('#labelFile').show();
      $('#divConfig').show();
      $('#selectConfig').val($('#ID_LOAD_ANAG').val());
     //  verificaConfigurazione();
      ModificaConfigurazione();
      /*setTimeout(function () {
        $('#alert').hide()
      }, 3000);*/
    },
    error: function (stato) {
      errorMessage("salvaConfigurazione: Qualcosa è andato storto!", stato);
    }

  });


}


function restSession() {
  consolelogFunction('restSession');
  var formData = {};



  consolelogFunction("restSession action=restSession");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito="+getParameterByName('sito')+"&controller=workflow2&action=restSession",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div html




    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("AutoPlay: Qualcosa è andato storto!", stato);
    }
  });


}


function SelTabella() {

  $('#labelFile').hide();
  $('#delConfig').hide();
  $('#divFoglio').hide();
  $('#label_RIGA_INZ').hide();
  $('#LabeladdConfig').hide();
  $('#addConfig').hide();
  $('#divConfig').hide();
  $('#addCheck').hide();
 
   restSession(); 

  $('#TAB_TARGET').val('');
  $('#ID_LOAD_ANAG').val('');

  //var form = document.getElementById('FormTabDdl');
  var formData = {};
  $('#Waiting').show();
  console.log("SelTabella");
  let obj_form = $("form#FormTabDdl").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  console.log('SelTabella: controller=loadconfig&action=getconfigurazione');
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    //  processData: false,
    // contentType: false,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=getconfigurazione",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //$('#message').html(risposta);
      //  return false;
      //visualizzo il contenuto del file nel div html
      // $('body').html(risposta);
      $('#selectConfig').empty();
      $('#selectConfig option').remove();
      $('#selectConfig').append($('<option>', {
        value: '',
        text: '-- SELEZIONA CONFIGURAZIONE --'
      }));
      $('#selectConfig').selectpicker('refresh');
      const dataAsArray = JSON.parse(risposta);
      console.log(dataAsArray.length);
      if (dataAsArray.length != 0) {
        $('#divConfig').show();
        var icon;
        var sel;
        $.each(dataAsArray, (index, row) => {
          console.log(row);
          sel = "";
          $('<option>').val(row['ID_LOAD_ANAG']).text(row['FLUSSO']).appendTo('#selectConfig');
          $('#selectConfig').selectpicker('refresh');
        });

        // $("form#FormMain").submit();
      } else {
        $('#divConfig').hide();
      }
      if ($('#Sel_Object').val() != "") {
        $('#labelFile').show();
      }
      $('#Waiting').hide();
      $('#actionSave').hide();
      $('#risultato').hide();
      $('#modConfig').hide();



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("SelTabella: Qualcosa è andato storto!", stato);
    }
  })


}

function verificaConfigurazione() {
     restSession();
  $('#ID_LOAD_ANAG').val('');
  if ($('#selectConfig').val() != '') {
    $('#addConfig').hide();
     $('#delConfig').show();
    $('#label_RIGA_INZ').hide();
    $('#labelFile').show();
    ModificaConfigurazione();    
    $('#nuovaConfigurazione').hide();
    $('#nuovaConfigurazione2').hide();
  } else {
    $('#delConfig').hide();
    $('#labelFile').show();
    $('#addCheck').hide();
    $('#nuovaConfigurazione').hide();
    $('#nuovaConfigurazione2').hide();
     $('#actionSave').hide();
      $('#risultato').hide();
      $('#modConfig').hide();

  }
}

function verificaNomeFlusso() {
    var flusso = $('#FLUSSO').val();
    var oldFlusso = $('#oldFLUSSO').val();

    if (flusso !== oldFlusso) {
        $('#nuovaConfigurazione').show();
        $('#nuovaConfigurazione2').show();
    } else {
        $('#nuovaConfigurazione').hide();
        $('#nuovaConfigurazione2').hide();
    }
}


function check(ID_LOAD_ANAG) {

  var formData = { ID_LOAD_ANAG: ID_LOAD_ANAG };
  console.log('check action=addcheck php check.php ');
  console.log(formData);

  /*'./PHP/Wfs_Gest_DettWfs.php'	*/

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=viewCheck",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      // console.log(risposta);
      //visualizzo il contenuto del file nel div htmlm
      // $("#ShowDettFlusso").html(risposta);
      $("#Filedialog").dialog({ title: 'Lista Check' });
      $('#Filedialog').html(risposta);
      $("#Filedialog").dialog("open");
      // $("#LoadDett"+IdFlu).html(risposta);
      $('#ShowDettFlusso').show();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("verificaConfigurazione: Qualcosa è andato storto!", stato);

    }
  });


}

function AggiungiCheck(ID_LOAD_ANAG, ID_LOAD_CHECK) {
  var formData = {};
  var formData = {
    ID_LOAD_ANAG: ID_LOAD_ANAG,
    ID_LOAD_CHECK: ID_LOAD_CHECK
  };
  console.log('AggiungiCheck index.php?controller=builder&action=addfcheck');
  console.log(formData);


  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=addfcheck",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#dialogMail").dialog({ title: 'Aggiungi Check' });
      $("#dialogMail").dialog("open");
      $('#dialogMail').html(risposta);
      $("#LABEL").trigger("focus");


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("AggiungiCheck: Qualcosa è andato storto!", stato);
    }
  });





}


function EliminaConfigurazione() {

  var formData = {};
  var formData = {
    ID_LOAD_ANAG: $('#ID_LOAD_ANAG').val()
   
  };
  var re = confirm('Vuoi eliminare questa configurazione?');
  if (re == true) {
    console.log('delCheck: controller=loadconfig&action=EliminaConfigurazione');
    console.log(formData);
    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=loadconfig&action=EliminaConfigurazione",
      // imposto l'azione in caso di successo
      success: function (risposta) {
      SelTabella();

    //    $('#Filedialog').html(risposta);
    
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("delCheck: Qualcosa è andato storto!", stato);
      }
    });

  }
}


function delCheck(ID_LOAD_ANAG, ID_LOAD_CHECK) {

  var formData = {};
  var formData = {
    ID_LOAD_ANAG: ID_LOAD_ANAG,
    ID_LOAD_CHECK: ID_LOAD_CHECK
  };
  var re = confirm('Vuoi eliminare questo Check?');
  if (re == true) {
    console.log('delCheck: controller=loadconfig&action=delCheck');
    console.log(formData);
    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=loadconfig&action=delCheck",
      // imposto l'azione in caso di successo
      success: function (risposta) {


        $('#Filedialog').html(risposta);
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("delCheck: Qualcosa è andato storto!", stato);
      }
    });

  }
}



function modCheck() {

  var form = document.getElementById('FormAddCheck');
  var formData = new FormData(form);

  console.log('modCheck: controller=loadconfig&action=modCheck');
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=modCheck",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      $("#dialogMail").dialog("close");
      $('#Filedialog').html(risposta);
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modCheck: Qualcosa è andato storto!", stato);
    }
  });

}



function addColForm() {
  var formData = {};
  let obj_form = $("form#FormTabDdl").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  console.log('AggiungiCheck index.php?controller=builder&action=addfcheck');
  console.log(formData);


  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=addColForm",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#dialogMail").dialog({ title: 'Aggiungi Check' });
      $("#dialogMail").dialog("open");
      $('#dialogMail').html(risposta);
      //  $( "#LABEL" ).trigger( "focus" );


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("addColForm: Qualcosa è andato storto!", stato);
    }
  });
}


function AddFileCol() {

  var form = document.getElementById('FormAddFileCol');
  var formData = new FormData(form);

  console.log('modCheck: controller=loadconfig&action=AddFileCol');
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=AddFileCol",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      $("#dialogMail").dialog("close");
     // $('#contenitore').html(risposta);
     ModificaConfigurazione();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modCheck: Qualcosa è andato storto!", stato);
    }
  });

}


function DelFileCol(I_ID_LOAD_ANAG,I_COLNAME) {


  var re = confirm('Are you sure you want Delete Colum "'+I_COLNAME+'"?');
  if ( re == true) {


  console.log('DelFileCol: controller=loadconfig&action=DelFileCol');
  var formData = { I_ID_LOAD_ANAG: I_ID_LOAD_ANAG,  I_COLNAME: I_COLNAME};
  console.log('check action=addcheck php check.php ');
  console.log(formData);

  /*'./PHP/Wfs_Gest_DettWfs.php'	*/

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=loadconfig&action=DelFileCol",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      $("#dialogMail").dialog("close");
     // $('#contenitore').html(risposta);
     ModificaConfigurazione();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("DelFileCol: Qualcosa è andato storto!", stato);
    }
  });

}
}



function checkAlias(alias) {
  
  console.log('checkAlias');
  console.log(alias);
 // return false;



  const tname = $.map($('input[type=text][name="Target_NAME[]"]'), function (el) { return el.value; });
  console.log(tname);



      $.each(tname, function( index, value ) { 
        if(alias==value) 
        {
          console.log(index);
    //   alert( index2 + ": " + value2 );
    if(!$('#Target_FORMULA_'+index).val())
       $('#Target_FORMULA_'+index).val(alias);
        }
      });



 
}
function Reset() {
   restSession();
window.location.replace('index.php?controller=loadconfig&action=index');
}

function RefreshPageLoad() {
  restSession();
if($('#actionSave').is(':visible'))
    {
      $('#Waiting').show();
      var file = $("#FileInput")[0].files[0];
      var form = document.getElementById('FormTabDdl');
      var formData = new FormData(form);
      formData.append("file", file);
      console.log('RefreshPageLoad');
      console.log(formData);
      console.log('controller=loadconfig&action=contentList"');
      $.ajax({
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        url: "index.php?controller=loadconfig&action=contentList",
        success: function (risposta) {
          $('#contenitore').html(risposta);
          /*  $('#Sel_Object').val('');
            $('#Sel_Schema').val('');*/
          $('#Sel_Object').selectpicker('refresh');
          $('#Sel_Schema').selectpicker('refresh');
          $('#FOGLIO').selectpicker('refresh');
                 $('#alert').show();
          window.scrollTo(0, 0);
          // $('#contenitore').html('');
          $('#labelFile').hide();
          $('#divFoglio').hide();
          $('#label_RIGA_INZ').hide();
          $('#addConfig').hide();
          $('#delConfig').hide();
          $('#divConfig').hide();
          $('#Waiting').hide();
          $('#addCheck').show();
          $('#LabeladdConfig').show();
          $('#labelFile').show();
          $('#divConfig').show();
          $('#selectConfig').val($('#ID_LOAD_ANAG').val());         
    
          setTimeout(function () {
            $('#alert').hide()
          }, 3000);
        },
        error: function (stato) {
          errorMessage("salvaConfigurazione: Qualcosa è andato storto!", stato);
        }
    
      });
    }
}
