$(document).ready(function () {

  console.log("document ready FLG_REFRESH: " + $('#FLG_REFRESH').val());
  // alert($('#FLG_REFRESH').val());
  if ($('#FLG_REFRESH').val() != 1) {
    console.log("document ready hide");
    $('#Waiting').hide();
    $('#message').hide();


    // here we attach to the scroll, adding the class 'fixed' to the &lt;thead> 
    /*  $(window).scroll(function() {
          var windowTop = $(window).scrollTop();
 
          if (windowTop > $('#idTabella').offset().top) {
            $("thead").css("position","fixed");
            $("thead").css("top","130px");

          }
          else {
            $("thead").css("position","relative");
            $("thead").css("top","0px");
          }
      });*/


  }


  //  $(".selectSearch").select2();
  // $('.selectNoSearch').select2({ minimumResultsForSearch: -1 });
});

$('#idTabella').DataTable({
  //columns: [{ width: '5%' }, { width: '5%' }, { width: '10%' }, { width: '5%' }, { width: '20%' },{ width: '5%' },null],
  language: {
    "url": "./JSON/italian.json"
  },
  "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]],
  columnDefs: [
    { orderable: false, targets: [13, 14, 15] }
  ]
  // responsive: true

});

$('#idTabella2').DataTable({
  //columns: [{ width: '5%' }, { width: '5%' }, { width: '10%' }, { width: '5%' }, { width: '20%' },{ width: '5%' },null],
  language: {
    "url": "./JSON/italian.json"
  },
  "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
  // responsive: true

});

function filtraControlloLancio(ID_LANCIO) {
  var formData = {};
  $('#Waiting').show();
  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  if (ID_LANCIO) {
    formData['selectLancio'] = ID_LANCIO;
  }
  console.log("filtraControlloLancio action=filtralancio");
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    // dataType: "json",
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=filtralancio",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      console.log("loadFileSelect risposta" + risposta);
      const dataAsArray = JSON.parse(risposta);
      console.log("dataAsArray:" + dataAsArray);

      var UTILIZZATO = 0;
      if (dataAsArray) {

        console.log("SOTTOGRUPPO: " + dataAsArray.SOTTO_GRUPPO);
        $('#selectSottoGruppo').val(dataAsArray.SOTTO_GRUPPO);
        $('#selectFile').val(dataAsArray.ID_FILE);


        if (dataAsArray.ID_TIPO !== null) {
          $.each(dataAsArray.ID_TIPO.split(","), function (i, e) {
            $("#selectIdTipo option[value=" + e + "]").prop("selected", true);
          });
        }

        if (dataAsArray.CLASSE !== null) {
          $.each(dataAsArray.CLASSE.split(","), function (i, e) {
            $("#selectClasse option[value=" + e + "]").prop("selected", true);
          });
        }


        // $('#selectIdTipo').val(dataAsArray.ID_TIPO);
        // $('#selectClasse').val(dataAsArray.CLASSE);
        $('#selectGruppo').val(dataAsArray.ID_GRUPPO);
        $('#selectLancio').val(dataAsArray.ID_LANCIO);
        //  $('#selectInport').val(dataAsArray.ID_INPORT);

        $('#selectGruppo').selectpicker('refresh');
        $('#selectFile').selectpicker('refresh');
        $('#selectLancio').selectpicker('refresh');
        $('#selectSottoGruppo').selectpicker('refresh');
        $('#selectIdTipo').selectpicker('refresh');
        $('#selectClasse').selectpicker('refresh');
      }

      filtraControllo();
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("filtraControlloLancio: Qualcosa è andato storto!", stato);
    }
  });
}



function filtraControllo(vInput) {
  var formData = {};
  $('#Waiting').show();
  console.log("filtraControllo");
  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  if(MESSAGGIO){
    $('#TitoloLoadLancio').show();
    $('#TitoloLoadLancio').text(MESSAGGIO);
  }


  formData['selectSottoGruppo'] = $('#selectSottoGruppo').val();
  formData['selectFile'] = $('#selectFile').val();
  formData['selectIdTipo'] = $('#selectIdTipo').val();
  formData['selectClasse'] = $('#selectClasse').val();
  formData['selectEsito'] = $('#selectEsito').val();
  formData['FLG_REFRESH'] = 1;
  if (vInput == 1) {
    formData['selectLancio'] = '';
    formData['selectSottoGruppo'] = '';
    formData['selectIdTipo'] = '';
    formData['selectClasse'] = '';
    formData['selectEsito'] = '';
  }
  console.log("filtraControllo action=contentList vInput:" + vInput);
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $('#contenitore').html(risposta);
      $('#selectGruppo').selectpicker('refresh');
      $('#selectFile').selectpicker('refresh');
      $('#selectLancio').selectpicker('refresh');
      $('#selectSottoGruppo').selectpicker('refresh');
      $('#selectIdTipo').selectpicker('refresh');
      $('#selectClasse').selectpicker('refresh');
      $('#selectEsito').selectpicker('refresh');
      if (vInput != 2) {
        setTimeout(function () {

          console.log("filtraControllo hide vInput:" + vInput);
          $('.bs-donebutton').on('click', function () {
            filtraControllo();
          });

          $('#Waiting').hide();
        }, 2000);
      }
      console.log("Fine filtraControllo");
      var Gruppo = $('#selectGruppo option:selected').text();
      $('#TitoloControlli').html(Gruppo+ " | ");
    
      var Lancio = $('#selectLancio option:selected').text();
      if ($('#selectLancio').val() != "") {
      $('#TitoloLancio').html(Lancio+ " | ");
      }
    
      console.log("filtraControllo action=contentList Gruppo:" + Gruppo);
      console.log("filtraControllo action=contentList Lancio:" + Lancio);

      //$('#LoadFls'+IdWorkFlow).html(risposta);
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("filtraControllo: Qualcosa è andato storto!", stato);
    }

  });


}


function visualizzaLancio(idLancio) {
  $('#selectLancio').val(idLancio).change();
  $("#Filedialog").dialog("close");
}


function VisualizzaControlli() {
  console.log("VisualizzaControlli");
  $('#selectSottoGruppo').val("");
  $('#selectIdTipo').val("");
  $('#selectClasse').val("");
  $('#selectEsito').val("");
  $('#selectFile').val("");
  var selectGruppo = $('#selectGruppo').val();
  //  var selectInport = $('#selectInport').val();
  $('#selectLancio').val("");
  $('#selectGruppo').val(selectGruppo).change();
  // $('#selectInport').val(selectInport)

  //$("#Filedialog").dialog("close");
}



function UploadControlli(ID_GRUPPO) {

  var formData = {};


  formData['ID_GRUPPO'] = ID_GRUPPO;
  console.log("modificaWF action=uploadcontrolli");
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=uploadcontrolli",
    // imposto l'azione in caso di successo
    success: function (risposta) {            //visualizzo il contenuto del file nel div htmlm


      $("#dialogMail").dialog({ title: "Upload Controlli" });
      $("#dialogMail").dialog("open");
      $('#dialogMail').html(risposta);
      // $("#dialogMail").dialog("close");
      //$('#LoadFls'+IdWorkFlow).html(risposta);
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("UploadControlli: Qualcosa è andato storto!", stato);
    }

  });


}


function downloadExport(TIPO_DOWNLOAD) {

  var formData = {};
  var formData = {};
  $('#Waiting').show();  
  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['TIPO_DOWNLOAD'] = TIPO_DOWNLOAD;
  formData['selectSottoGruppo'] = $('#selectSottoGruppo').val();
  formData['selectFile'] = $('#selectFile').val();
  formData['selectIdTipo'] = $('#selectIdTipo').val();
  formData['selectClasse'] = $('#selectClasse').val();
  formData['selectEsito'] = $('#selectEsito').val();
 // formData['ID_GRUPPO'] = $('#selectGruppo').val();
  console.log("downloadExport index.php?controller=controlli&action=downloadfile");
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=downloadfile",
    // imposto l'azione in caso di successo
    success: function (risposta) {            //visualizzo il contenuto del file nel div htmlm
      const downloadFile = JSON.parse(risposta);
      window.open(downloadFile);
      console.log(downloadFile);
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

function insertControlli(idgruppo) {

  var form = document.getElementById('upload');
  var formData = new FormData(form);
  formData.append("file", file);
  console.log(formData);
  console.log('controller=controlli&action=insertcontrolli"');
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=insertcontrolli",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      alert('insertControlli success');
      console.log(risposta);
      $('#dialogMail').html(risposta);
      // $("#dialogMail").dialog("close");
      //$('#LoadFls'+IdWorkFlow).html(risposta);
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("insertControlli: Qualcosa è andato storto!", stato);
    }

  });


}



function updateValiditaControllo(ID_CONTR, FLG_VALIDITA) {

  var formData = {};
  $('#Waiting').show();
  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['ID_CONTR'] = ID_CONTR;
  formData['FLG_VALIDITA'] = FLG_VALIDITA;


  console.log(formData);
  console.log('controller=controlli&action=updatevaliditacontrollo"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=updatevaliditacontrollo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      //$("#contenitore").html(risposta);
      filtraControllo();
      console.log("updateValiditaControllo hide");
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("updateValiditaControllo: Qualcosa è andato storto!", stato);
    }
  });

}




function modificaControllo(ID_CONTR) {

  var formData = {};

  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['ID_CONTR'] = ID_CONTR;



  console.log(formData);
  console.log('controller=controlli&action=modificacontrollo"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificacontrollo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      $("#dialogMail").html(risposta);
      $("#dialogMail").dialog({ title: 'Modifica controllo', dialogClass: "dialog-full-mode" });
      $("#dialogMail").dialog("open");



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modificaControllo: Qualcosa è andato storto!", stato);
    }
  });

}

function modificaLancio(ID_CONTR) {

  var formData = {};

  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['ID_CONTR'] = ID_CONTR;



  console.log(formData);
  console.log('controller=controlli&action=modificalancio"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificalancio",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      $("#dialogMail").html(risposta);
      $("#dialogMail").dialog({ title: 'Modifica lancio', dialogClass: "dialog-full-mode" });
      $("#dialogMail").dialog("open");



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modificaLancio: Qualcosa è andato storto!", stato);
    }
  });

}


function AddControllo() {

  var formData = {};

  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  console.log(formData);
  console.log('controller=controlli&action=addcontrollo"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=addcontrollo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      $("#dialogMail").html(risposta);
      $("#dialogMail").dialog({ title: 'Aggiungi Controllo', dialogClass: "dialog-full-mode" });
      $("#dialogMail").dialog("open");



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("AddControllo: Qualcosa è andato storto!", stato);
    }
  });

}
function storicoDownload(ID_CONTR) {

  var formData = {};

  /*let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  }); */
  formData['ID_CONTR'] = ID_CONTR;

  console.log(formData);
  console.log('controller=controlli&action=storicodownload"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=storicodownload",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: 'Lista Lancio', dialogClass: "dialog-full-mode" });
      $("#Filedialog").dialog("open");



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("storicoDownload: Qualcosa è andato storto!", stato);
    }
  });

}

function listaLanci(ID_CONTR) {

  var formData = {};

  /*let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  }); */
  formData['ID_CONTR'] = ID_CONTR;

  console.log(formData);
  console.log('controller=controlli&action=listalanci"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=listalanci",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm

      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: 'Lista Lancio', dialogClass: "dialog-full-mode" });
      $("#Filedialog").dialog("open");



    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("listaLanci: Qualcosa è andato storto!", stato);
    }
  });

}


function aggionaLancioControllo(ID_CONTR) {

  var formData = {};

  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['ID_CONTR'] = ID_CONTR;



  console.log(formData);
  console.log('controller=controlli&action=aggionalanciocontrollo"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=aggionalanciocontrollo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      filtraControllo();
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("aggionaLancioControllo: Qualcosa è andato storto!", stato);
    }
  });

}




function modificaDBControllo() {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $("form#FormControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  console.log(formData);
  console.log('controller=controlli&action=modificadbcontrollo"');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificadbcontrollo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      // $("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      filtraControllo();
      $('#Waiting').hide();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modificaDBControllo: Qualcosa è andato storto!", stato);
    }
  });

}


function modificaDBLancio() {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $("form#FormControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  console.log(formData);
  console.log('controller=controlli&action=modificadbLancio');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificadbLancio",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      // $("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      filtraControllo();
      $('#Waiting').hide();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("modificaDBLancio: Qualcosa è andato storto!", stato);
    }
  });

}




function ModificaDbValidita() {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $("form#FormModificaValidita").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  formData['LISTA_ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  console.log(formData);
  console.log('controller=controlli&action=modificadbvaidita');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificadbvaidita",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      //$("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      filtraControllo();
      $('#Waiting').hide();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ModificaDbValidita: Qualcosa è andato storto!", stato);
    }
  });

}



function ModificaDbTipo() {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $("form#FormModificaTipo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  formData['LISTA_ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  console.log(formData);
  console.log('controller=controlli&action=modificadbtipo');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificadbtipo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      //$("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      filtraControllo();
      $('#Waiting').hide();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ModificaDbTipo: Qualcosa è andato storto!", stato);
    }
  });

}






function ModificaDbEsito() {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $("form#FormModificaEsito").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  formData['LISTA_ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  console.log(formData);
  console.log('controller=controlli&action=modificadbesito');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=modificadbesito",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      //$("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      filtraControllo();
      $('#Waiting').hide();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ModificaDbEsito: Qualcosa è andato storto!", stato);
    }
  });

}

function modtipo() {

  var formData = {};


  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  formData['ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  if (formData['ID_CONTR'].length === 0) {
    alert('Selezionare almeno un controllo!!');
  } else {
    console.log("ID_CONTR string", formData['ID_CONTR'].join(", "));
    console.log(formData);
    console.log('controller=controlli&action=modificatipo"');

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=controlli&action=modificatipo",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  alert("Controllo Modificato!");
        $("#dialogMail").dialog({ title: "Modifica Tipo" });
        $("#dialogMail").dialog("open");
        $('#dialogMail').html(risposta);
        $('#ESITO').selectpicker('refresh');

        $('#LISTA_ID_CONTR').val(formData['ID_CONTR'].join(", "));
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("modtipo: Qualcosa è andato storto!", stato);
      }
    });
  }

}

function modificavalidita() {

  var formData = {};


  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  formData['ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  if (formData['ID_CONTR'].length === 0) {
    alert('Selezionare almeno un controllo!!');
  } else {
    console.log("ID_CONTR string", formData['ID_CONTR'].join(", "));
    console.log(formData);
    console.log('controller=controlli&action=modificavalidita"');

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=controlli&action=modificavalidita",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  alert("Controllo Modificato!");
        $("#dialogMail").dialog({ title: "Modifica Validità" });
        $("#dialogMail").dialog("open");
        $('#dialogMail').html(risposta);
        $('#ESITO').selectpicker('refresh');

        $('#LISTA_ID_CONTR').val(formData['ID_CONTR'].join(", "));
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("modificavalidita: Qualcosa è andato storto!", stato);
    }
  });

}
}



function ModificaEsito() {

  var formData = {};


  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  formData['ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  if (formData['ID_CONTR'].length === 0) {
    alert('Selezionare almeno un controllo!!');
  } else {
    console.log("ID_CONTR string", formData['ID_CONTR'].join(", "));
    console.log(formData);
    console.log('controller=controlli&action=modificaesito"');

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=controlli&action=modificaesito",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  alert("Controllo Modificato!");
        $("#dialogMail").dialog({ title: "Modifica Esito" });
        $("#dialogMail").dialog("open");
        $('#dialogMail').html(risposta);
        $('#ESITO').selectpicker('refresh');

        $('#LISTA_ID_CONTR').val(formData['ID_CONTR'].join(", "));
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("ModificaEsito: Qualcosa è andato storto!", stato);
      }
    });
  }

}

function CreaLancio(ID_CONTR) {

  var formData = {};


  let obj_form = $("form#FormFiltriControllo").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });


  formData['ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
    return $(this).val();
  }).get();

  if (ID_CONTR) {
    formData['ID_CONTR'][0] = ID_CONTR;
  }
  console.log("ID_CONTR string", formData['ID_CONTR'].join(", "));


  console.log(formData);
  console.log('controller=controlli&action=formcrealancio"');


  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=formcrealancio",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      $("#dialogMail").dialog({ title: "Crea Lancio" });
      $("#dialogMail").dialog("open");
      $('#dialogMail').html(risposta);
      $('#SOTTO_GRUPPO').selectpicker('refresh');
      $('#ID_TIPO').selectpicker('refresh');
      $('#CLASSE').selectpicker('refresh');
      $('#ID_FILE').selectpicker('refresh');
      $('#LANCIO_ID_CONTR').val(formData['ID_CONTR'].join(", "));
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("CreaLancio: Qualcosa è andato storto!", stato);
    }
  });

}
var STATO_LANCIO=0;


function creaDBLancio(ID_CONTR) {
  

  var FILE_NAME_LIST = $('#ID_FILE option:selected').toArray().map(item => item.text);
  var LISTA_ID_FILE = $('#ID_FILE').val();
  STATO_LANCIO=0;

 
    var formData = {};

    let obj_form = $("form#FormCrealancio").serializeArray();
    obj_form.forEach(function (input) {
      formData[input.name] = input.value;
    });
    formData['ID_TIPO'] = $('#ID_TIPO').val();
    formData['CLASSE'] = $('#CLASSE').val();
    formData['LISTA_ID_FILE'] = $('#ID_FILE').val();
    formData['ID_CONTR'] = $("input:checkbox[name=selectIdContr]:checked").map(function () {
      return $(this).val();
    }).get();

    if (ID_CONTR) {
      var formData = {};
      formData['ID_CONTR'][0] = ID_CONTR;
    }
	  formData['N_ID_FILE'] = 0;
	  formData['N_FILE'] = $('#ID_FILE').val().length;
    //$('#Waiting').hide();

    formData['FILE_NAME_LIST'] = FILE_NAME_LIST;

    formData['SUF_DESCR'] = $('#DESCR').val();
    formData['ID_LANCIO'] = "";
    var i = formData['N_ID_FILE'];
    formData['NAME_FILE'] = formData['FILE_NAME_LIST'][i];
  
    lanciaCreaDbLancio(formData);
	
   


}

function lanciaCreaDbLancio(formData){
	var i = formData['N_ID_FILE'];
	formData['DESCR'] = formData['SUF_DESCR'] + "_" + formData['FILE_NAME_LIST'][i];
  formData['ID_FILE'] = formData['LISTA_ID_FILE'][i];
  formData['NAME_FILE'] = formData['FILE_NAME_LIST'][i];
  
  console.log(formData);
  $('#message').show();
  let now = new Date();
  console.log("lanciaCreaDbLancio DataStart :"+now);
  console.log("lanciaCreaDbLancio ID_FILE:"+formData['ID_FILE']);
  console.log("lanciaCreaDbLancio N_FILE:"+formData['N_FILE']);
  console.log("lanciaCreaDbLancio N_ID_FILE:"+formData['N_ID_FILE']);
  console.log("lanciaCreaDbLancio NAME_FILE:"+formData['NAME_FILE']);
	
	console.log('lanciaCreaDbLancio controller=controlli&action=creadblancio"');
	
  //return 1;
    
	$.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=controlli&action=creadblancio",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  alert("Controllo Modificato!");
        const LANCIO = JSON.parse(risposta);
        //$('#dialogMail').html(risposta);
        $("#dialogMail").dialog("close");

        //  $('#selectLancio').val(LANCIO.ID_LANCIO).change();
       // VisualizzaControlli();
       $('#selectLancio').val(LANCIO.ID_LANCIO).change();
       $('#message').show();
       console.log('lanciaCreaDbLancio attendo 15 secondi"');
        setTimeout(function () {         
         
          
          
          console.log("lanciaCreaDbLancio > verificaStatoLancio ID_LANCIO:"+LANCIO[0].ID_LANCIO);
          console.log(LANCIO);
          console.log(formData);
         verificaStatoLancio(LANCIO,formData,0);

        }, 15000);
        
        
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("lanciaCreaDbLancio: Qualcosa è andato storto!", stato);
      }
    });
}
var MESSAGGIO = "";



function verificaStatoLancio(LANCIO,formDataLancio,n_cicli) {
  $('#message').show();
  var N_ID_FILE = formDataLancio['N_ID_FILE'];
  var ID_LANCIO = LANCIO[N_ID_FILE].ID_LANCIO;
  console.log("RICARICO verificaStatoLancio ID_LANCIO:"+ID_LANCIO);
  console.log("RICARICO verificaStatoLancio n_cicli:"+n_cicli);
  
  formDataLancio['ID_LANCIO'] = ID_LANCIO;
  console.log(formDataLancio);
  console.log("verificaStatoLancio:controller=controlli&action=getstatolancio");

  //if ($('#selectLancio').val() != ID_LANCIO) {
  //$('#selectLancio').val(ID_LANCIO).change();

  console.log("verificaStatoLancio selectLancio");
  //}
  $.ajax({
    type: "POST",
    data: formDataLancio,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=getstatolancio",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  alert("Controllo Modificato!");
      console.log("verificaStatoLancio risposta");
      console.log("verificaStatoLancio formDataLancio");
      console.log(formDataLancio);
      //$('#selectLancio').val(ID_LANCIO).change();
      //$('#dialogMail').html(risposta);
      const STATO = JSON.parse(risposta);
      console.log("verificaStatoLancio STATO:" + STATO);
      MESSAGGIO = " | CARICAMENTO LANCIO: " + formDataLancio['DESCR']+' N Cicli: '+n_cicli+'';
      //filtraControllo(2);
     
      if (STATO == 2) {
       console.log('lanciaCreaDbLancio STATO == 2 Attendo 15 sec"');
      // $('#TitoloLoadLancio').text(MESSAGGIO);        
       $('#message').show();
        setTimeout(function () {
         // $('#selectLancio').val(ID_LANCIO).change();
          console.log("reload verificaStatoLancio");
          verificaStatoLancio(LANCIO,formDataLancio,n_cicli+1);
          
          $('#TitoloLoadLancio').show();
          $('#TitoloLoadLancio').text(MESSAGGIO);
          $('#selectLancio').val(ID_LANCIO).change();  
        }, 15000);
      } else {
        console.log('lanciaCreaDbLancio STATO == 1"');
        $('#selectLancio').val(ID_LANCIO).change();
      console.log(formDataLancio);
     var  N_ID_FILE = formDataLancio['N_ID_FILE'];
		 formDataLancio['N_ID_FILE'] = N_ID_FILE+1;
     let now = new Date();
     console.log("lanciaCreaDbLancio DataEnd :"+now);
     console.log("RICARICO verificaStatoLancio N_FILE:"+formDataLancio['N_FILE']);
     console.log("RICARICO verificaStatoLancio N_ID_FILE:"+formDataLancio['N_ID_FILE']);
		 if(formDataLancio['N_FILE']>formDataLancio['N_ID_FILE'])
		 {  
      setTimeout(function () {    
      console.log("RICARICO lanciaCreaDbLancio N_ID_FILE:"+formDataLancio['N_ID_FILE']);
      console.log("RICARICO lanciaCreaDbLancio N_FILE:"+formDataLancio['N_FILE']);
      verificaStatoLancio(LANCIO,formDataLancio,0);
		  //lanciaCreaDbLancio(formDataLancio);
    }, 15000);
		 }else{
      $('#message').hide();
     }
        
      }

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("verificaStatoLancio: Qualcosa è andato storto!", stato);
    }
  });

}


function loadFileSelect() {


  var formData = {};

  let obj_form = $("form#FormCrealancio").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  console.log(formData);
  console.log('controller=controlli&action=selectfile"');
  // console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    // dataType: "json",
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=controlli&action=selectfile",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      console.log("loadFileSelect risposta");
      var UTILIZZATO = 0;
      const dataAsArray = JSON.parse(risposta);

      console.log(risposta);
      $('#ID_FILE option').remove();
      $('#ID_FILE').selectpicker('refresh');
      if (formData['SOTTO_GRUPPO'] != '') {

        $.each(dataAsArray, (index, row) => {


          $('#ID_FILE').append($('<option>', {
            value: row['ID_FILE'],
            text: row['INPUT_FILE']
          }));
          $('#ID_FILE').selectpicker('refresh');

        });
      }


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("loadFileSelect: Qualcosa è andato storto!", stato);
    }
  });
}

function selFile() {
  NAME_FILE = $("#ID_FILE option:selected").text();
  $("#NAME_FILE").val(NAME_FILE);
}

function selectAllContr() {
  if ($('#selectAllContr').hasClass('fa-regular fa-square-check')) {
    //do something

    $(".checkbox_selector").prop("checked", true);
    $("#selectAllContr").addClass("fa-square").removeClass("fa-square-check");
  } else {
    $(".checkbox_selector").prop("checked", false);
    $("#selectAllContr").addClass("fa-square-check").removeClass("fa-square");
  }
}
