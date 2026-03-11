$(document).ready(function () {
  $('#Waiting').hide();
  $('#selectFOGLIO').hide();
  $('#labelFile').hide();
  $('#divFoglio').hide();
  $('#label_RIGA_INZ').hide();
  $('#addConfig').hide();
  $('#modConfig').hide();
  $('#divConfig').hide();
  $('#addCheck').hide();

  $('#LabeladdConfig').hide();
  $('#labelFile').hide();




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
        alert("Qualcosa è andato storto");
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
      $('#modConfig').hide();
      $('#divConfig').hide();

      $('#Waiting').hide();
    },
    error: function (stato) {
      alert("CreaConfigurazione: Qualcosa è andato storto");
      $('#Waiting').hide();
    }

  });


}


function selSchema() {

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
      $('#modConfig').hide();
      $('#divConfig').hide();
      $('#addCheck').hide();
    },
    error: function (stato) {
      alert("selSchema: Qualcosa è andato storto");
      $('#Waiting').hide();
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
      $('#labelFile').show();
      $('#divFoglio').hide();
      $('#label_RIGA_INZ').hide();
      $('#addConfig').hide();
      $('#modConfig').show();
      $('#divConfig').show();
      $('#addCheck').show();
      $('#Waiting').hide();
    },
    error: function (stato) {
      alert("CreaConfigurazione: Qualcosa è andato storto");
      $('#Waiting').hide();
    }

  });


}




function OpenFoglio(nomeFoglio) {
  $('#Waiting').show();
  $('#divFoglio').show();
  $('#label_RIGA_INZ').show();
  if ($('#selectConfig').val()) {
    $('#addConfig').hide();
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
      // $('body').html(risposta);
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
      alert("Qualcosa è andato storto");
    }
  });
}





function salvaConfigurazione() {
  $('#Waiting').show();
  var file = $("#FileInput")[0].files[0];
  var form = document.getElementById('FormTabDdl');
  var formData = new FormData(form);
  formData.append("file", file);
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
      $('#modConfig').hide();
      $('#divConfig').hide();
      $('#Waiting').hide();
      $('#addCheck').show();
      $('#LabeladdConfig').show();
      $('#labelFile').show();
      $('#divConfig').show();
      $('#selectConfig').val($('#ID_LOAD_ANAG').val());
      verificaConfigurazione();

      
    },
    error: function (stato) {
      alert("Qualcosa è andato storto");
      $('#Waiting').hide();
    }

  });


}


function SelTabella() {

  $('#labelFile').hide();
  $('#modConfig').hide();
  $('#divFoglio').hide();
  $('#label_RIGA_INZ').hide();
  $('#LabeladdConfig').hide();
  $('#addConfig').hide();
  $('#divConfig').hide();
  $('#addCheck').hide();

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



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("SelTabella: Qualcosa è andato storto");
    }
  })


}

function verificaConfigurazione() {
  $('#ID_LOAD_ANAG').val('');
  if ($('#selectConfig').val() != '') {
    $('#addConfig').hide();
    $('#modConfig').show();
    $('#label_RIGA_INZ').hide();
    $('#labelFile').show();
  } else {
    $('#modConfig').hide();
    $('#labelFile').show();
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
      console.log("Qualcosa è andato storto");
      console.log(stato);

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


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });





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
        alert("Qualcosa è andato storto");
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
      alert("modCheck: Qualcosa è andato storto");
    }
  });

}
