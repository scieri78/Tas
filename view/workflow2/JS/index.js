$(document).ready(function () {
  $('#Waiting').hide();
  initDati();
  $('#ShowDettFlusso').hide();
});


var workflowRegistry = {};

function avviaLoadDiv() {
  IdWorkFlow = $('#IdWorkFlow').val();
  // Controlla se esiste già un intervallo per questo vIdLegame
  if (workflowRegistry[IdWorkFlow]) {
    consolelogFunction('Intervallo già attivo per IdWorkFlow:', IdWorkFlow);
    return; // Esci dalla funzione se l'intervallo è già attivo
  }

  // Crea un nuovo intervallo e memorizza l'ID nel registro
  var intervalId = setInterval(function () {

    consolelogFunction("avviaLoadDiv intervalId:" + intervalId);
    consolelogFunction("avviaLoadDiv IdWorkFlow:" + IdWorkFlow);
    setTimeout(function () {
      LoadDiv();
      // printLegend(); 
    }, 500);
  }, 3000);

  // Memorizza l'ID dell'intervallo nel registro
  workflowRegistry[IdWorkFlow] = intervalId;
}

function destroyInterval() {
  if (workflowRegistry[IdWorkFlow]) {
    IdWorkFlow = $('#IdWorkFlow').val();
    clearInterval(workflowRegistry[IdWorkFlow]);
    consolelogFunction("destroyInterval intervalId:" + workflowRegistry[IdWorkFlow]);
    consolelogFunction("destroyInterval IdWorkFlow:" + IdWorkFlow);
    workflowRegistry[IdWorkFlow] = '';
    setTimeout(function () {
      avviaLoadDiv();
    }, 60000);
  }
}

function initDati() {
  $('#ShowDettFlusso').hide();
  var IdProcess = $('#IdProcess').val();
  var IdPeriod = $('#IdPeriod').val();
  var IdWorkFlow = $('#IdWorkFlow').val();
  var ProcMeseEsame = $('#ProcMeseEsameV').val();
  consolelogFunction('initDati IdProcess:' + IdProcess);
  consolelogFunction('initDati IdPeriod:' + IdPeriod);


  if (IdProcess != "" && IdPeriod != "") {
    var SelFlusso = $('#SelFlusso').val();
    var SelNomeFlusso = $('#SelNomeFlusso').val();
    if (SelFlusso != '') {
      consolelogFunction('initDati SelFlusso:' + SelFlusso);
      consolelogFunction('initDati SelNomeFlusso:' + SelNomeFlusso);
      OpenDettFlusso(IdWorkFlow, IdProcess, SelFlusso, SelNomeFlusso, '', ProcMeseEsame, 1);
      OpenStatusFlusso(IdWorkFlow, IdProcess, SelFlusso, ProcMeseEsame);
      //  $('#ShowDettFlusso').show();
    } else {
      //printLegend(1);
      //$('#ShowDettFlusso').show();

      //$('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');
    }
    // AutoRefresh();
    var SelDipendenza = $('#SelDipendenza').val();
    var SelNomeDipendenza = $('#SelNomeDipendenza').val();
    var SelTipo = $('#SelTipo').val();
    RefreshCoda(IdWorkFlow, IdProcess, SelFlusso, SelNomeFlusso, SelDipendenza, SelNomeDipendenza, SelTipo);

    consolelogFunction('initDati setInterval LoadDiv');
    avviaLoadDiv();

    // initDati();
    // $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');

  }

}


function printLegend() {

  var formData = {};
  formData['printLegend'] = 'si';

  // $('#ShowDettFlusso').hide();
  consolelogFunction('printLegend index.php?controller=workflow2&action=Legend');
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=Legend",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      /*   $('#ShowDettFlusso').html(risposta);
         $('#ShowDettFlusso').show();
         if ($('#ShowDettFlusso').is(':empty')) {
            setTimeout(function () {
          //  printLegend();
            $('#ShowDettFlusso').show();
          }, 500);
               }*/

      $("#dialogMail").dialog({ title: "LEGENDA" });
      $("#dialogMail").dialog("open");
      $("#dialogMail").html(risposta);
      //do something      \\

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("printLegend: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
}


function AutoRefresh() {
  consolelogFunction('AutoRefresh');
  for (var i = 1; i < 100; i++)
    window.clearInterval(i);
  consolelogFunction('clearInterval');
  var xtime = 300;
  timerId = setInterval(function () {
    consolelogFunction('setInterval AutoRefresh');
    xtime--;

    $('#countdown').html(xtime + ' secs');

    if (xtime === 0) {
      RefreshPage();
      //  clearInterval(timerId);
    }
  }, 115000);
  // initDati();
}

function changeRegime() {
  //  $('#Waiting').show();
  initDati();
  reloadPage();
}


function OpenLogFileElab(logFile, vaction) {

  var formData = {};

  formData['LOG'] = logFile;
  formData['provenienza'] = 'workflow2';

  consolelogFunction(formData);
  consolelogFunction("OpenLogFileElab: index.php?controller=statoshell&action=" + vaction);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&action=" + vaction,
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div htmlm


      $("#Filedialog2").dialog({ title: logFile });
      $("#Filedialog2").dialog("open");
      $("#Filedialog2").html(risposta);
      //$("textarea").css('height', ($(window).height() - 280));
      //$("textarea").css('width', ($(window).width() - 150));

      var SHELLLOG = "LOG: " + logFile;

      //consolelogFunction(SHELL2);
      $("#Filedialog").dialog({ title: SHELLLOG });
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenLogFileElab: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });


}


function MostraStorico(IdWorkFlow, IdProcess) {
  $('#Waiting').show();
  var formData = {};


  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;



  consolelogFunction(formData);
  consolelogFunction('index.php?controller=workflow2&action=MostraStorico');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=MostraStorico",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#Filedialog").dialog({ title: "STORICO LANCI" });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("MostraStorico: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}


function MostraParametri(IdWorkFlow, IdProcess) {
  $('#Waiting').show();
  var formData = {};


  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;



  consolelogFunction(formData);
  consolelogFunction('index.php?controller=workflow2&action=MostraParametri');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=MostraParametri",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#Filedialog").dialog({ title: "PARAMETRI" });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("MostraStorico: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}






function TableKo(IdDip, IdProcess, NomeCaricamento) {
  $('#Waiting').show();
  var formData = {};


  formData['IdDip'] = IdDip;
  formData['IdProcess'] = IdProcess;



  consolelogFunction(formData);
  consolelogFunction('index.php?controller=workflow2&action=TableKo');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=TableKo",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#Filedialog").dialog({ title: "Elenco Scarti Caricamento " + NomeCaricamento });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("TableKo: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}




/*
function MostraCoda(IdWorkFlow, IdProcess) {
  $('#Waiting').show();
  var formData = {};


  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;



  consolelogFunction(formData);
  consolelogFunction('index.php?controller=workflow2&action=MostraCoda');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=workflow2&action=MostraCoda",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#Filedialog").dialog({ title: "Modifica Coda" });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      consolelogFunction("MostraCoda: Qualcosa è andato storto!",stato);
      $('#Waiting').hide();
    }
  });

}*/


function MostraFlusso(IdWorkFlow, IdProcess, IdFlusso, ProcMeseEsame) {
  var formData = {
    IdWorkFlow: IdWorkFlow,
    IdProcess: IdProcess,
    IdFlusso: IdFlusso,
    ProcMeseEsame: ProcMeseEsame
  };

  consolelogFunction("MostraFlusso - formData:", formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=Flusso",
    success: function (risposta) {
      //  consolelogFunction("Success - risposta:", risposta);
      $('#Flu' + IdFlusso).remove();
      $('#StatusFlusso' + IdFlusso).html(risposta);
      $('#Waiting').hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error - textStatus:", textStatus, "errorThrown:", errorThrown);
      errorMessage("MostraFlusso: Qualcosa è andato storto!", jqXHR.status);
      $('#Waiting').hide();
    }
  });
}

function nascondiDiv(IdFlusso, Legame, Esito) {
  consolelogFunction("nascondiDiv");
  $('#ImgRefresh' + Legame).hide();
  $('#IcoRefresh' + IdFlusso).hide();
  if (Esito == "I") {
    $('#ImgDip' + Legame).hide();
    $('#IcoSveg' + IdFlusso).hide();
    $('#ImgRun' + Legame).show();
    $('#IcoRun' + IdFlusso).show();
    $('#ImgSveglia' + Legame).hide();
    $('#ElabInCodaWait').hide();
    $('#ElabInCodaLoad').show();
  } else {
    $('#ImgRun' + Legame).hide();
    $('#IcoRun' + IdFlusso).hide();
    $('#IcoSveg' + IdFlusso).show();
    $('#ImgSveglia' + Legame).show();
    $('#ElabInCodaLoad').hide();
    $('#ElabInCodaWait').show();
  }
}
function nascondiDivLegame(Legame) {
  consolelogFunction("nascondiDivLegame");
  $('#ImgRun' + Legame).hide();
  $('#ImgDip' + Legame).hide();
  $('#ImgRefresh' + Legame).show();
}

function nascondiDivFlusso(IdFlusso) {
  consolelogFunction("nascondiDivFlusso");
  $('#ImgDip' + IdFlusso).hide();
  $('#ImgRefresh' + IdFlusso).show();

}
function nascondiActionFlusso(IdFlusso) {
  consolelogFunction("nascondiActionFlusso");
  $('#actionFlusso' + IdFlusso).hide();


}



function visualizzaPulsanti(SetMaxTime, CntProc, CntWfs, InRun) {
  consolelogFunction('visualizzaPulsanti');
  consolelogFunction('SetMaxTime:' + SetMaxTime);

  consolelogFunction('if (CntProc == CntWfs) { = IN CODA:' + CntProc);
  consolelogFunction('CntProc:' + CntProc);
  consolelogFunction('CntWfs:' + CntWfs);
  consolelogFunction('InRun:' + InRun);

  $('#LastTimeCoda').val(SetMaxTime);
  $('#ElabInCodaLoad').hide();
  $('#ElabInCodaWait').hide();
  if (CntProc != 0) {
    $('#ElabInCoda').show();
    if (InRun != 0) {
      $('#ElabInCodaLoad').show();
    } else {
      $('#ElabInCodaWait').show();
    }
  } else {
    $('#ElabInCoda').hide();
  }
  if (CntProc == CntWfs) {

    $('#PulMostraCoda').css('width', '170px');
    $('#FraseCoda').text(CntProc + ' in Coda');

  } else {

    $('#PulMostraCoda').css('width', '170px');
    $('#FraseCoda').text(CntProc + " in Coda su " + CntWfs);

  }

}


function MostraEsito(Img, TMsg, NotaRis) {
  $('#Waiting').show();
  var formData = {};


  formData['Img'] = Img;
  formData['TMsg'] = TMsg;
  formData['NotaRis'] = NotaRis;
  $('#SelDipendenza').val('');

  consolelogFunction(formData);
  consolelogFunction('MostraEsito: index.php?controller=workflow2&action=Esito');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=Esito",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $('#mostraEsito').html(risposta);
      $('#mostraEsito').show();
      //   sleep(8000);
      /*   setTimeout(function () {
           $('#mostraEsito').hide();
         }, 8000);*/

      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("MostraEsito: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}


function chiudiEsito() {
  $('#Waiting').show();
  //$('#MainForm').submit();
  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  consolelogFunction('chiudiEsito');
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);

      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("SaveNewDescr: Qualcosa è andato storto!", stato);
    }
  });

}



function OpenDettFlusso(vIdWorkFlow, vIdProcess, vIdFlusso, vNomeFlusso, vDescFlusso, ProcMeseEsame, flgdet) {
  $('#Waiting').show();
  var formData = {};
  $('#LastTimeCoda').val('');


  var vVal = $('#SelFlusso').val();
  consolelogFunction('OpenDettFlusso vVal: ' + vVal);
  consolelogFunction('OpenDettFlusso vIdFlusso: ' + vIdFlusso);
  consolelogFunction('OpenDettFlusso flgdet: ' + flgdet);

  var action;
  if (flgdet == 1) {
    $('#ShowDettFlusso').show();
    consolelogFunction('ShowDettFlusso simone: ');
    $('#SelFlusso').val(vIdFlusso);
    $('#SelNomeFlusso').val(vNomeFlusso);
    $('#Flu' + vIdFlusso).removeClass('ingrandFlu');
    action = 'LoadFlusso';
    formData = {
      IdWorkFlow: vIdWorkFlow,
      IdProcess: vIdProcess,
      IdFlusso: vIdFlusso,
      DescFlusso: vDescFlusso,
      Flusso: vNomeFlusso,
      ProcMeseEsame: ProcMeseEsame
    };
  } else {
    if (vVal == vIdFlusso) {
      $('#SelFlusso').val('');
      $('#SelNomeFlusso').val('');
      action = '';
      $('#ShowDettFlusso').hide();
      $('#Flu' + vIdFlusso).addClass('ingrandFlu');
      $('#Flu' + vVal).removeClass('ingrandFlu');
      $('#Waiting').hide();
    } else {
       consolelogFunction('else ShowDettFlusso simone: ');
      $('#ShowDettFlusso').show();
      $('#SelFlusso').val(vIdFlusso);
      $('#SelNomeFlusso').val(vNomeFlusso);
      $('#Flu' + vVal).removeClass('ingrandFlu');
      $('#Flu' + vIdFlusso).addClass('ingrandFlu');
      action = 'LoadFlusso';
      formData = {
        IdWorkFlow: vIdWorkFlow,
        IdProcess: vIdProcess,
        IdFlusso: vIdFlusso,
        DescFlusso: vDescFlusso,
        Flusso: vNomeFlusso,
        ProcMeseEsame: ProcMeseEsame
      };
    }
  }

  consolelogFunction('OpenDettFlusso formData:', formData);
  consolelogFunction('OpenDettFlusso URL:', 'index.php?controller=workflow2&action=' + action);
  if (action) {
    $.ajax({
      type: "POST",
      data: formData,
      encode: true,
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=" + action,
      success: function (risposta) {
         consolelogFunction('ShowDettFlusso OpenDettFlusso ');
        //  consolelogFunction("Success - risposta:", risposta);
        $('#ShowDettFlusso').html(risposta).show();
        $('#Waiting').hide();
        var vSel = $('#SelFlusso').val();
        $('#Waiting').hide();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error - textStatus:", textStatus, "errorThrown:", errorThrown);
        errorMessage("OpenDettFlusso: Qualcosa è andato storto!", jqXHR.status);
        $('#Waiting').hide();
      }
    });
  }
}



function MostraDiagramma(IdWorkFlow, IdProcess, WfsName, ProcDescr) {
  $('#Waiting').show();
  var formData = {};


  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['WfsName'] = WfsName;
  formData['ProcDescr'] = ProcDescr;


  consolelogFunction(formData);
  consolelogFunction('MostraDiagramma: index.php?controller=workflow2&action=GeneraDiagram');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=GeneraDiagram",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#Filedialog").dialog("open");
      $("#Filedialog").html(risposta);
      /* $('#ShowDiagram').html(risposta);
       $('#ShowDiagram').show();*/
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("MostraDiagramma: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}
function reloadPage() {
  $('#Waiting').show();
  var fData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    fData[input.name] = input.value;
  });

  consolelogFunction('reloadPage index.php?controller=workflow2&action=contentList');
  consolelogFunction(fData);
  $('#Action').val('');
  // $('#MainForm').submit();
  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#TopScroll').val($('#AreaPreFlussi').scrollTop());
      $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
      initDati();
      $('#Waiting').hide();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("reloadPage: Qualcosa è andato storto!", stato);
    }
  });
  //loadSelect(IdWorkFlow);
}

/*
function ChSens() {
  var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "ChSens")
    .val('ChSens');
  $('#MainForm').append($(input));
  //   $('#Waiting').show(); 
  $('#MainForm').submit();
}*/

function ChSens() {
  $('#Waiting').show();
  var fdata = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    fdata[input.name] = input.value;
  });
  //formData['ChSens'] = 'ChSens';
  consolelogFunction('ChSens index.php?controller=workflow2&action=contentList');
  consolelogFunction(fdata);

  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  formData.append("ChSens", "ChSens");
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ChSens: Qualcosa è andato storto!", stato);
    }
  });
  //loadSelect(IdWorkFlow);
}

/*function CambiaStato() {
  var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "CambiaStato")
    .val('CambiaStato');
  $('#MainForm').append($(input));
  //   $('#Waiting').show(); 
  $('#MainForm').submit();
}*/


function CambiaStato() {
  $('#Waiting').show();
  var formData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  formData['CambiaStato'] = 'CambiaStato';
  consolelogFunction('CambiaStato index.php?controller=workflow2&action=contentList');
  consolelogFunction(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("CambiaStato: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
  //loadSelect(IdWorkFlow);
}


/*
function OpenStatusFlusso(vIdWorkFlow, vIdProcess, vIdFlusso) {
  $('#LastTimeCoda').val('');
  $('#StatusFlusso' + vIdFlusso).load('./index.php?controller=workflow2&action=Flusso', {
    IdWorkFlow: vIdWorkFlow,
    IdProcess: vIdProcess,
    IdFlusso: vIdFlusso,
    ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
  });
}*/

function OpenStatusFlusso(IdWorkFlow, IdProcess, IdFlusso, ProcMeseEsame, flg) {
  avviaLoadDiv();
  // $('#Waiting').show();
  consolelogFunction('OpenStatusFlusso ', IdFlusso);
  if (flg == 1) {
    $('#LastTimeCoda').val('');
  }
  if (flg == 2) {
    $('#LastTimeCoda').val('');
    $('.Flusso').each(function () {
      consolelogFunction('OpenStatusFlusso removeClass ingrandFlu');
      $(this).removeClass('ingrandFlu');
    });
  }
  var formData = {};

  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['IdFlusso'] = IdFlusso;
  formData['ProcMeseEsame'] = ProcMeseEsame;
  formData['SelFlusso'] = $('#SelFlusso').val();

  consolelogFunction('OpenStatusFlusso index.php?controller=workflow2&action=Flusso');
  consolelogFunction('OpenStatusFlusso SelFlusso:' + formData['SelFlusso']);
  consolelogFunction('OpenStatusFlusso', formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=Flusso",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      consolelogFunction('OpenStatusFlusso #StatusFlusso' + IdFlusso);
      $('#StatusFlusso' + IdFlusso).empty();

      $('#Flu' + IdFlusso).remove();
      $('#StatusFlusso' + IdFlusso).html(risposta);

      var vSel = $('#SelFlusso').val();
      consolelogFunction('OpenStatusFlusso addClass ingrandFlu');
      $('#Flu' + vSel).addClass('ingrandFlu');
      consolelogFunction('OpenStatusFlusso #Flu' + vSel);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenStatusFlusso: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

function RefreshCoda(IdWorkFlow, IdProcess, SelIdFlu, SelNomeFlu, SelIdDip, SelNomeDip, SelTipo) {
  consolelogFunction('RefreshCoda');
  var vId = $('#SelFlusso').val();
  if (vId != '') { $().show(); }
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['SelIdFlu'] = SelIdFlu;
  formData['SelNomeFlu'] = SelNomeFlu;
  formData['SelIdDip'] = SelIdDip;
  formData['SelNomeDip'] = SelNomeDip;
  formData['SelTipo'] = SelTipo;
  formData['MaxTime'] = $('#LastTimeCoda').val();

  consolelogFunction('RefreshCoda index.php?controller=workflow2&action=RefreshCoda');
  consolelogFunction(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=RefreshCoda",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $('#RefreshCoda').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("RefreshCoda: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

function oldnoAutoRefreshCoda(Flu, Legame, Esito) {

  $('#ImgDip' + Legame).hide();
  $('#ImgRun' + Legame).hide();
  $('#ImgSveglia' + Legame).hide();
  $('#ImgRefresh' + Legame).hide();
  $('#IcoSveg' + Flu).hide();
  $('#IcoRun' + Flu).hide();
  $('#IcoRefresh' + Flu).hide();


  const actions = {
    "I": ['ImgRun', 'IcoRun'],
    "N": ['ImgSveglia', 'IcoSveg'],
    "E": ['ImgRefresh', 'IcoRefresh', 'ImgDip'],
    "W": ['ImgRefresh', 'IcoRefresh', 'ImgDip'],
    "F": ['ImgRefresh', 'IcoRefresh', 'ImgDip']
  };

  if (actions[Esito]) {
    actions[Esito].forEach(id => {
      $(`#${id}${id === 'ImgDip' ? Legame : Flu}`).show();
    });

    if (['E', 'W', 'F'].includes(Esito)) {
      $(`#ImgDip${Legame}`).attr('src', `./images/Esito${Esito}.png`).show();
    }
  }


}

function noAutoRefreshCoda(flu, legame, esito) {
  // Nascondi tutti gli elementi inizialmente
  $('#ImgDip' + legame).hide();
  $('#ImgRun' + legame).hide();
  $('#ImgSveglia' + legame).hide();
  $('#ImgRefresh' + legame).hide();
  $('#IcoSveg' + flu).hide();
  $('#IcoRun' + flu).hide();
  $('#IcoRefresh' + flu).hide();

  // Mappa degli esiti con gli elementi da mostrare
  const esitoMap = {
    'I': ['#ImgRun' + legame, '#IcoRun' + flu],
    'N': ['#ImgSveglia' + legame, '#IcoSveg' + flu],
    'E': ['#ImgRefresh' + legame, '#IcoRefresh' + flu, '#ImgDip' + legame],
    'W': ['#ImgRefresh' + legame, '#IcoRefresh' + flu, '#ImgDip' + legame],
    'F': ['#ImgRefresh' + legame, '#IcoRefresh' + flu, '#ImgDip' + legame]
  };

  

  // Mostra gli elementi in base all'esito
  if (esitoMap[esito]) {
    esitoMap[esito].forEach(function (selector) {
      if (selector === '#ImgDip' + legame) {
        $(selector).attr('src', './images/Status' + esito + '.png').show();
      } else {
        $(selector).show();
      }
    });
  } else {
    // Se nessun esito corrisponde, mostra l'icona di sveglia
    $('#IcoSveg' + flu).show();
    $('#ImgSveglia' + legame).show();
  }
}

/*
$("#RefreshCoda").load('./index.php?controller=workflow2&action=RefreshCoda', {
  IdWorkFlow: <?php echo $IdWorkFlow; ?>,
  IdProcess: <?php echo $IdProcess; ?>,
  SelIdFlu: '<?php echo $SelFlusso; ?>',
  SelNomeFlu: '<?php echo $SelNomeFlusso; ?>',
  SelIdDip: '<?php echo $SelDipendenza; ?>',
  SelNomeDip: '<?php echo $SelNomeDipendenza; ?>',
  SelTipo: '<?php echo $SelTipo; ?>',
  MaxTime: $('#LastTimeCoda').val()
});*/


/*
 function ReOpenDettFlusso(vIdWorkFlow, vIdProcess, vIdFlusso, vNomeFlusso, vDescFlusso) {
      $('#LastTimeCoda').val('');
      $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
      $('#StatusFlusso' + vIdFlusso).load('./index.php?controller=workflow2&action=Flusso', {
        IdWorkFlow: vIdWorkFlow,
        IdProcess: vIdProcess,
        IdFlusso: vIdFlusso,
        DescFlusso: vDescFlusso,
        ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
      });
      //$('.Flusso').each(function(){
      //  $(this).css('border','1px solid white');
      //});
      $('#SelFlusso').val(vIdFlusso);
      $('#SelNomeFlusso').val(vNomeFlusso);
      $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=LoadFlusso', {
        IdWorkFlow: vIdWorkFlow,
        IdProcess: vIdProcess,
        IdFlu: vIdFlusso,
        Flusso: vNomeFlusso,
        DescFlusso: vDescFlusso,
        ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
      });
    }*/

function ReOpenDettFlusso(IdWorkFlow, IdProcess, IdFlusso, NomeFlusso, vDescFlusso, ProcMeseEsame) {
  $('#Waiting').show();
  var formData = {};
  $('#SelFlusso').val(IdFlusso);
  $('#SelNomeFlusso').val(NomeFlusso);
  consolelogFunction('ReOpenDettFlusso addClass ingrandFlu 3');
  // $('#Flu' + IdFlusso).addClass('ingrandFlu');
  var action = 'LoadFlusso';
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['IdFlusso'] = IdFlusso;
  formData['Flusso'] = NomeFlusso;
  formData['ProcMeseEsame'] = ProcMeseEsame;
  formData['DescFlusso'] = vDescFlusso;
  formData['dettList'] = $('#dettList').val();
  /* $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=LoadFlusso', {
     IdWorkFlow: vIdWorkFlow,
     IdProcess: vIdProcess,
     IdFlu: vIdFlusso,
     Flusso: vNomeFlusso,
     DescFlusso: vDescFlusso,
     ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
   });*/


  consolelogFunction('ReOpenDettFlusso');
  consolelogFunction(formData);
  consolelogFunction('OpenDettFlusso: index.php?controller=workflow2&action=LoadFlusso');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=" + action,
    // imposto l'azione in caso di successo
    success: function (risposta) {
      
      $('#ShowDettFlusso').html(risposta);
       consolelogFunction('ReOpenDettFlusso ShowDettFlusso');
      $('#Waiting').hide();
      var vSel = $('#SelFlusso').val();
      consolelogFunction('ReOpenDettFlusso addClass ingrandFlu');
      //  $('#Flu' + vSel).addClass('ingrandFlu');
      $('#Waiting').hide();
      var arrDettList = formData['dettList'].split("#");
      for (i = 0; i <= arrDettList.length; i++) {
        var vIdDett = arrDettList[i];
        if (vIdDett != '') { $("#" + vIdDett).toggle(); }
      }
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {

      errorMessage("ReOpenDettFlusso: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

function RefreshDettFlusso(IdWorkFlow, IdProcess, IdFlusso, NomeFlusso, vDescFlusso, ProcMeseEsame) {
  $('#Waiting').show();
  var formData = {};
  // $('#SelFlusso').val(IdFlusso);
  // $('#SelNomeFlusso').val(NomeFlusso);
  consolelogFunction('RefreshDettFlusso addClass ingrandFlu 3');
  // $('#Flu' + IdFlusso).addClass('ingrandFlu');
  var action = 'LoadFlusso';
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['IdFlusso'] = IdFlusso;
  formData['Flusso'] = NomeFlusso;
  formData['ProcMeseEsame'] = ProcMeseEsame;
  formData['DescFlusso'] = vDescFlusso;
  formData['dettList'] = $('#dettList').val();
  /* $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=LoadFlusso', {
     IdWorkFlow: vIdWorkFlow,
     IdProcess: vIdProcess,
     IdFlu: vIdFlusso,
     Flusso: vNomeFlusso,
     DescFlusso: vDescFlusso,
     ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
   });*/


  consolelogFunction('RefreshDettFlusso');
  consolelogFunction(formData);
  consolelogFunction('RefreshDettFlusso: index.php?controller=workflow2&action=LoadFlusso');

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=" + action,
    // imposto l'azione in caso di successo
    success: function (risposta) {
      if ($('#SelFlusso').val() == IdFlusso) {
        $('#ShowDettFlusso').html(risposta);
        consolelogFunction('RefreshDettFlusso ShowDettFlusso');
      }
      $('#Waiting').hide();
      var vSel = $('#SelFlusso').val();
      consolelogFunction('RefreshDettFlusso addClass ingrandFlu');
      //  $('#Flu' + vSel).addClass('ingrandFlu');
      $('#Waiting').hide();
      var arrDettList = formData['dettList'].split("#");
      for (i = 0; i <= arrDettList.length; i++) {
        var vIdDett = arrDettList[i];
        if (vIdDett != '') { $("#" + vIdDett).toggle(); }
      }
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {

      errorMessage("ReOpenDettFlusso: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}




/** Flusso.php */
function selezionaFlusso(ArrPreFlussi, ArrSucFlussi) {

  consolelogFunction("selezionaFlusso");

  ArrPreFlussi.forEach((IdFlu) => {
    consolelogFunction(IdFlu);
    $('#DipP' + IdFlu).show();
    $('#Flu' + IdFlu).addClass('PreSel');
  });
  ArrSucFlussi.forEach((IdFlu) => {
    consolelogFunction(IdFlu);
    $('#DipS' + IdFlu).show();
    $('#Flu' + IdFlu).addClass('PostSel');
  });


}


function deSelezionaFlusso(ArrPreFlussi, ArrSucFlussi) {

  //consolelogFunction("deSelezionaFlusso");

  ArrPreFlussi.forEach((IdFlu) => {
    //consolelogFunction(IdFlu);
    //$('#DipP' + IdFlu).hide();
    $('#Flu' + IdFlu).removeClass('PreSel');
  });
  ArrSucFlussi.forEach((IdFlu) => {
    //   consolelogFunction(IdFlu);
    //$('#DipS' + IdFlu).hide();
    $('#Flu' + IdFlu).removeClass('PostSel');
  });


}


function ForzaScodatore() {
  $('#Waiting').show();
  var formData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  formData['ForzaScodatore'] = 1;
  consolelogFunction('ForzaScodatore index.php?controller=workflow2&action=contentList');
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ForzaScodatore: Qualcosa è andato storto!", stato);
    }
  });

}



function OpenLinkPage(IdWorkFlow, array_post) {
  $('#Waiting').show();
  var formData = {};
  for (var key in array_post) {
    consolelogFunction(array_post[key]);
    formData[key] = array_post[key];
  }
  formData['IdWorkFlow'] = IdWorkFlow;


  consolelogFunction('OpenLinkPage index.php?controller=workflow2&action=OpenLinkPage');
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=OpenLinkPage",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#OpenLinkPage').html(risposta);
      $('#OpenLinkPage').show();
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenLinkPage: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

function visualizzaPulsIdP(ProcDescr) {
 // console.log("visualizzaPulsIdP ");
  if ($('#DescrIdP').val() == ProcDescr || $.trim($('#DescrIdP').val())=='') {
    $('#PulsIdP').hide();
   // console.log("visualizzaPulsIdP hide");
  } else {
    $('#PulsIdP').show();
   // console.log("visualizzaPulsIdP show");
  }
}


function AggiungiIdP() {

  consolelogFunction('AggiungiIdP');

  var re = confirm('Confermi di voler creare un nuovo IdProcess?');
  if (re == true) {
    $('#Waiting').show();
    var formData = {};
    let obj_form = $("form#MainForm").serializeArray();
    obj_form.forEach(function (input) {
      formData[input.name] = input.value;
    });
    formData['AddIdProcess'] = 1;
    consolelogFunction('AggiungiIdP index.php?controller=workflow2&action=contentList');
    consolelogFunction(formData);

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,
      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div html
        $('#contenitore').html(risposta);
        $('#Waiting').hide();
        initDati();

        //setTimeout(function () {
        $('#infoBox').hide();
        //}, 8000);
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("AggiungiIdP: Qualcosa è andato storto!", stato);
        $('#Waiting').hide();
      }
    });
  }
}


function SaveNewDescr() {
  $('#Waiting').show();
  var re = confirm('Confermi la modifica dalla descrizione?');
  if (re == true) {

    var formData = {};
    let obj_form = $("form#MainForm").serializeArray();
    obj_form.forEach(function (input) {
      formData[input.name] = input.value;
    });
    formData['NewDesc'] = "NewDesc";
    $('#NewDesc').val("NewDesc");
    consolelogFunction('SaveNewDescr index.php?controller=workflow2&action=contentList');
    consolelogFunction(formData);
    $('#Action').val('');
    $("form#MainForm").submit();


    var form = document.getElementById('MainForm');
    var formData = new FormData(form);
    consolelogFunction(formData);
    $.ajax({
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div html
        $('#contenitore').html(risposta);
        initDati();
        $('#Waiting').hide();
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("SaveNewDescr: Qualcosa è andato storto!", stato);
        $('#Waiting').hide();
      }
    });
    $('#NewDesc').val("");

  }
}

function changeIdWorkFlow() {

  $('#Waiting').show();
  $('#SelFlusso').val('');
  $('#SelNomeFlusso').val('');
  // $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');
  $('#IdPeriod').val('');
  $('#IdProcess').val('');
  $('#Action').val('');
  var fdata = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    fdata[input.name] = input.value;
  });

  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  $('#Resetta').val('Resetta');
  fdata['Resetta'] = "Resetta";
  formData.set("Resetta", "Resetta");
  consolelogFunction('changeIdWorkFlow index.php?controller=workflow2&action=contentList');
  consolelogFunction(fdata);
  //$("form#MainForm").submit(); 
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      initDati();
      $('#Waiting').hide();
      // location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("changeIdWorkFlow: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}
/**   $('#IdPeriod').change(function() {
      $('#SelFlusso').val('');
      $('#SelNomeFlusso').val('');
      $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');
      $('#IdProcess').val('');
      $('#MainForm').submit();
    });
    $('#IdProcess').change(function() {
      // $('#Waiting').show();
      $('#TopScroll').val('');
      $('#MainForm').submit();
    }); */


function changeIdPeriod() {
  $('#AreaPreFlussi').html('');
  initDati();
  $('#Waiting').show();
  $('#SelFlusso').val('');
  $('#SelNomeFlusso').val('');
  // $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');
  $('#IdProcess').val('');
  $('#Action').val('');
  $('#Resetta').val('Resetta');
  var fdata = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    fdata[input.name] = input.value;
  });

  consolelogFunction('changeIdPeriod index.php?controller=workflow2&action=contentList');
  consolelogFunction(fdata);
  // $("form#MainForm").submit();
  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      consolelogFunction('changeIdPeriod risposta');
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#ShowDettFlusso').hide();
      $('#SelFlusso').val("");
      
      //location.reload();
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("changeIdPeriod: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });


}

function changeIdProcess() {
  $('#AreaPreFlussi').html('');
  //initDati();
  $('#Waiting').show();
  $('#SelFlusso').val('');
  $('#SelNomeFlusso').val('');
  $('#Action').val('');

  // $('#IdProcess').val('');      
  var fdata = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    fdata[input.name] = input.value;
  });

  consolelogFunction('changeIdProcess index.php?controller=workflow2&action=contentList');
  consolelogFunction(fdata);
  //$("form#MainForm").submit();
  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //  location.reload();
      //visualizzo il contenuto del file nel div html
      $('#contenitore').html(risposta);
      $('#ShowDettFlusso').hide();
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("changeIdProcess: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
  // $('#ShowDettFlusso').empty().load('./index.php?controller=workflow2&action=Legend');

}
//function RefreshPageWF () {
//window.location.reload();
//}

function RefreshPage() {
  $('#Waiting').show();
  $('#TopScroll').val($('#AreaPreFlussi').scrollTop());
  $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
  $('#Action').val('');
  // $('#MainForm').submit();
  var form = document.getElementById('MainForm');
  var formData = new FormData(form);
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      initDati();
      $('#contenitore').html(risposta);
      $('#Waiting').hide();
      // location.reload();

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("Action: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
}


function LoadDiv(IdWorkFlow, IdProcess, SelNomeFlu, SelIdDip, SelNomeDip, SelTipo, ProcMeseEsame) {
  var formData = {};


  formData['IdWorkFlow'] = $('#IdWorkFlow').val();
  formData['IdProcess'] = $('#IdProcess').val();
  formData['SelIdFlu'] = $('#SelFlusso').val();
  formData['SelNomeFlu'] = $('#SelNomeFlusso').val();
  formData['SelIdDip'] = $('#SelDipendenza').val();
  formData['SelNomeDip'] = $('#SelNomeDipendenza').val();
  formData['SelTipo'] = $('#SelTipo').val();
  formData['MaxTime'] = $('#LastTimeCoda').val();
  formData['ProcMeseEsame'] = $('#ProcMeseEsame').val();

  consolelogFunction('LoadDiv index.php?controller=workflow2&action=RefreshCoda');
  consolelogFunction("LoadDiv", formData);
  changeStatoFlussi($('#IdWorkFlow').val(), $('#IdProcess').val(), $('#ProcMeseEsame').val());

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=RefreshCoda",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      consolelogFunction('LoadDiv RefreshCoda');
      $('#RefreshCoda').html(risposta);
      //consolelogFunction('LoadDiv RefreshCoda');
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("LoadDiv: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });


  if ($("#SHdialog").text() != "") {
    MostraCoda($('#IdWorkFlow').val(), $('#IdProcess').val());
  }

}



function setStatoFlussi(IdWorkFlow, IdProcess) {

  consolelogFunction('setStatoFlussi index.php?controller=workflow2&action=setStatoFlussi');
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=setStatoFlussi",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      //  consolelogFunction("Controllo Modificato!");
      const statoFlussi = JSON.parse(risposta);
      consolelogFunction('ChangeStato statoFlussi:');
      consolelogFunction(statoFlussi);

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("setStatoFlussi: Qualcosa è andato storto!", stato);
      $('#message').hide();
    }
  });
}



function svuotaCoda(IdProcess) {

  consolelogFunction('svuotaCoda index.php?controller=workflow2&action=svuotaCoda');
  re = confirm('Sei sicuro di voler svuotare la Coda?');

  if (re == true) {
    var formData = {};

    formData['IdProcess'] = IdProcess;
    consolelogFunction(formData);
    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=svuotaCoda",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        //  consolelogFunction("Controllo Modificato!");
        alert("Coda Svuotata!");
        const statoFlussi = JSON.parse(risposta);
        consolelogFunction('svuotaCoda:');
        consolelogFunction(statoFlussi);

      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("setStatoFlussi: Qualcosa è andato storto!", stato);
        $('#message').hide();
      }
    });
  }
}


function changeStatoFlussi(IdWorkFlow, IdProcess, ProcMeseEsame) {
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['ProcMeseEsame'] = ProcMeseEsame;
  consolelogFunction('ChangeStato getStatoFlussi index.php?controller=workflow2&action=getChangeStato');
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=getChangeStato",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      const change_Stato = JSON.parse(risposta);
      consolelogFunction('change_Stato');
      consolelogFunction('change_Stato', change_Stato);
      if (change_Stato) {
        change_Stato.forEach((IdFlu_change) => {
          OpenStatusFlusso(IdWorkFlow, IdProcess, IdFlu_change, ProcMeseEsame);
          consolelogFunction('IdFlu_change' + IdFlu_change);
          consolelogFunction('RefreshDettFlusso' + IdFlu_change);
          var nomeFlu = $('#NomeFlu' + IdFlu_change).val();
          RefreshDettFlusso(IdWorkFlow, IdProcess, IdFlu_change, nomeFlu, '', ProcMeseEsame)
        });
      }

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("getChangeStato: Qualcosa è andato storto!", stato);
      $('#message').hide();
    }
  });
}


function MostraCoda(IdWorkFlow, IdProcess) {
  consolelogFunction('MostraCoda');
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['WfsRdOnly'] = $("#WfsRdOnly").val();
  consolelogFunction('MostraCoda index.php?controller=workflow2&action=MostraCoda');
  consolelogFunction('MostraCoda',formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=MostraCoda",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $("#SHdialog").dialog({ title: "Coda" });
      $("#SHdialog").dialog("open");
      $('#SHdialog').html(risposta);
      $('#Waiting').hide();

      /* Filedialog $('#MostraCoda').html(risposta);
       $('#MostraCoda').show();*/
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("MostraCoda: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
}


function forzaElaborazioniPossibili(IdWorkFlow, IdProcess) {
  $('#Waiting').show();
  consolelogFunction('forzaElaborazioniPossibili');
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  consolelogFunction('forzaElaborazioniPossibili index.php?controller=workflow2&action=forzaElaborazioniPossibili');
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=forzaElaborazioniPossibili",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $("#SHdialog").dialog({ title: "Modifica Coda" });
      $("#SHdialog").dialog("open");
      $('#SHdialog').html(risposta);
      $('#Waiting').hide();

      /* Filedialog $('#MostraCoda').html(risposta);
       $('#MostraCoda').show();*/
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("forzaElaborazioniPossibili: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
}




function OpenDiagram(IdWorkFlow, IdProcess, WfsName, ProcDescr) {
  $('#Waiting').show();
  var formData = {};
  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['WfsName'] = WfsName;
  formData['ProcDescr'] = ProcDescr;

  consolelogFunction('OpenDiagram: index.php?controller=workflow2&action=GeneraDiagram');
  consolelogFunction(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=GeneraDiagram",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog("open");
      $("#Filedialog").html(risposta);
      /* $('#ShowDiagram').html(risposta);
       $('#ShowDiagram').show();*/

      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenDiagram: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

// LoadFlusso.php

function OpenLogFile(vLog) {
  window.open("./index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&ApriLogElab&LOG=" + vLog);
}

function OpenProcessing(vIdRunSh) {
  consolelogFunction('OpenProcessing');
  window.open("./index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&action=index&IDSELEM=" + vIdRunSh);
}

function ShowGraph(vIdRunSh) {
  window.open('./PHP/GraphShell.php?IDSH=' + vIdRunSh);
}


function openGrafici(ShName, ShTags, IdSh) {

  consolelogFunction('openGrafici');

  var formData = {};
  formData['STEP'] = ShName;
  formData['TAGS'] = ShTags;
  formData['IDSH'] = IdSh;
  //formData['SHFILE']=SHFILE;
  //formData['action']='setManual';  
  consolelogFunction(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    //url: "index.php?controller=statoshell&action=apriGrafici&STEP=" + ShName + "&TAGS=" + ShTags + "&IDSH=" + IdSh + "",
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&action=apriGrafici",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      console.log('openGrafici', "index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&action=apriGrafici");
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog2").html(risposta);
      $("#Filedialog2").dialog({ title: "Grafico: " + ShName });
      $("#Filedialog2").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("openGrafici: Qualcosa è andato storto");
    }
  });


}

function OpenLink(IdWorkFlow, IdProcess, LinkIdLegame, LinkPagina, LinkNameDip, LinkBloccato, LinkEsitoDip, idLink,RdOnly) {
  // $('#Waiting').show();


  var formData = {};

  formData['IdWorkFlow'] = IdWorkFlow;
  formData['IdProcess'] = IdProcess;
  formData['LinkPagina'] = LinkPagina;
  formData['LinkIdLegame'] = LinkIdLegame;
  formData['LinkNameDip'] = LinkNameDip;
  formData['LinkBloccato'] = LinkBloccato;
  formData['LinkEsitoDip'] = LinkEsitoDip;
  formData['idLink'] = idLink;
  formData['RdOnly'] = RdOnly;

  var LinkName = LinkPagina.replace(".php", "");
  //var LinkName = LinkName.replace("GiroRIAS1", "GiroRIAS");

  consolelogFunction("OpenLink: index.php?sito=" + getParameterByName('sito') + "&controller=openLink_" + LinkName + "&action=index");
  consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=openLink_" + LinkName + "&action=index",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog({ title: LinkNameDip });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();
      initDati();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("OpenLink: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
}

function Action(IdFlu, NomeFlusso, Action, vMess, OnIdLegame, SelDipendenza, SelNomeDipendenza, SelTipo) {
  avviaLoadDiv();
  var re = false;
  if (vMess != '') {
    var re = confirm(vMess);
  } else {
    re = true
  }
  if (re == true) {

    $('#IdFlu').val(IdFlu);
    $('#Flusso').val(NomeFlusso);
    $('#Action').val(Action);
    $('#OnIdLegame').val(OnIdLegame);
    $('#SelDipendenza').val(SelDipendenza);
    $('#SelNomeDipendenza').val(SelNomeDipendenza);
    $('#SelTipo').val(SelTipo);
    var fdati = {};
    let obj_form = $("form#MainForm").serializeArray();
    obj_form.forEach(function (input) {
      fdati[input.name] = input.value;
    });
    consolelogFunction('Action index.php?sito=' + getParameterByName('sito') + '&controller=workflow2&action=flussiAction');
    // consolelogFunction(fdati);
    //   $("form#MainForm").submit();
    var form = document.getElementById('MainForm');
    var formData = new FormData(form);
    formData.set('IdFlu', IdFlu);
    formData.set('Flusso', NomeFlusso);
    formData.set('Action', Action);
    formData.set('SelDipendenza', SelDipendenza);
    formData.set('SelNomeDipendenza', SelNomeDipendenza);
    formData.set('SelTipo', SelTipo);

    var formDataObject = {};
    formData.forEach((value, key) => {
      formDataObject[key] = value;
    });

    // Stampa l'intero oggetto
    consolelogFunction("Action del FormData:", formDataObject);
    $.ajax({
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=flussiAction",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div html
        $('#contenitore').html(risposta);
        initDati();
        //  const flussi = JSON.parse(risposta);
        MostraFlusso(fdati['IdWorkFlow'], fdati['IdProcess'], IdFlu, fdati['ProcMeseEsame']);
        OpenDettFlusso(fdati['IdWorkFlow'], fdati['IdProcess'], IdFlu, NomeFlusso, '', fdati['ProcMeseEsame'], 1);
        consolelogFunction('Action flussi');
        //  LoadDiv();
        //  setInterval(function () {
        consolelogFunction('Action setInterval LoadDiv');
        // LoadDiv();
        $('#Action').val('');
        //  ReOpenDettFlusso(fdati['IdWorkFlow'], fdati['IdProcess'], IdFlu, NomeFlusso, '',fdati['ProcMeseEsame']);
        setTimeout(() => {
          restSession();
        }, 500);

        //  }, 5000);
        $('#contenitore').html(risposta);
        $('#Waiting').hide();

      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("Action: Qualcosa è andato storto!", stato);
        $('#Waiting').hide();
      }
    });
  }
}

/**
 * MostraCoda
 */
function RimuoviDaCoda(CodaIdFlu, CodaTipo, CodaIdDip) {
  var re = confirm('Confermi la rimozione dalla Coda?');
  var formData = {};
  if (re == true) {

    formData['CodaIdFlu'] = CodaIdFlu;
    formData['CodaTipo'] = CodaTipo;
    formData['CodaIdDip'] = CodaIdDip;
    formData['Action'] = 'CancellaCoda';

    $('#IdFlu').val(CodaIdFlu);
    $('#Tipo').val(CodaTipo);
    $('#IdDip').val(CodaIdDip);
    $('#Action').val('CancellaCoda');

    var IdWorkFlow = $('#IdWorkFlow').val();
    var IdProcess = $('#IdProcess').val();
     formData['IdWorkFlow'] = IdWorkFlow;
      formData['IdProcess'] = IdProcess;
      formData['Tipo'] = CodaTipo;
      formData['IdFlu'] = CodaIdFlu;
      formData['IdDip'] = CodaIdDip;


    consolelogFunction("RimuoviDaCoda index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList");
    consolelogFunction(formData);

    // $("form#MainForm").submit();
    var form = document.getElementById('MainForm');
    var formData = new FormData(form);
    var object = {};
    formData.forEach(function(value, key){
        object[key] = value;
    });
    var json = JSON.stringify(object);
    console.log('RimuoviDaCoda json:',json);
    consolelogFunction(formData);
    $.ajax({
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=contentList",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div html
          $('#contenitore').html(risposta);

        MostraCoda(IdWorkFlow, IdProcess);
       // $("#Filedialog").dialog({ title: "Coda" });
      //  $("#Filedialog").dialog("open");
      //  $('#Bilderdialog2').html(risposta);
       // sleep(500);
         setTimeout(function () {
        $("#SHdialog").dialog("close");
        }, 500);

        $('#Waiting').hide();
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("RimuoviDaCoda: Qualcosa è andato storto!", stato);
        $('#Waiting').hide();        
      }
    });
  }
}

/**
 * MostraStorico 
 */


function OpenProcessing2(vIdRunSh) {
  // Create a form
  var mapForm = document.createElement("form");
  mapForm.target = "_blank";
  mapForm.method = "GET";
  mapForm.action = "./index.php?" + getParameterByName('sito') + "&controller=statoshell&action=index";

  // Create an input
  var mapInput = document.createElement("input");
  mapInput.type = "hidden";
  mapInput.name = "IDSELEM";
  mapInput.value = vIdRunSh;

  // Add the input to the form
  mapForm.appendChild(mapInput);

  // Add the form to dom
  document.body.appendChild(mapForm);

  // Just submit
  mapForm.submit();
}

function refreshLinkInterni() {
  consolelogFunction('refreshLinkInterni');
  var formData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  obj_form = $("form#openLinkPage").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
  consolelogFunction(formData);
  var LinkPagina = $('#openLinkPage #LinkPagina').val()
  var LinkName = LinkPagina.replace(".php", "");
  //var LinkName = LinkName.replace("GiroRIAS1", "GiroRIAS");
  consolelogFunction("refreshLinkInterni: index.php?controller=openLink_" + LinkName + "&action=index");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=openLink_" + LinkName + "&action=index",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog({ title: LinkName });
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("refreshLinkInterni: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });

}

function ActionF(Action, vMess, OnIdLegame, SelDipendenza, SelNomeDipendenza, SelTipo, Flusso) {
  consolelogFunction('ActionF');
  /*var formData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });
consolelogFunction(formData);
  obj_form = $("form#openLinkPage").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });*/
  consolelogFunction(formData);
  /* formData['Action'] = Action;
   formData['OnIdLegame'] = OnIdLegame;
   formData['SelDipendenza'] = SelDipendenza;
   formData['SelTipo'] = SelTipo;
   formData['SelNomeDipendenza'] = SelNomeDipendenza;
   formData['Force'] = 1;
   formData['Flusso'] = Flusso;
   consolelogFunction(formData);*/

  $('#IdFlu').val(IdFlu);
  $('#Flusso').val(Flusso);
  $('#Action').val(Action);
  $('#OnIdLegame').val(OnIdLegame);
  $('#SelDipendenza').val(SelDipendenza);
  $('#SelNomeDipendenza').val(SelNomeDipendenza);
  $('#SelTipo').val(SelTipo);
  /* var fdati = {};
   let obj_form = $("form#MainForm").serializeArray();
   obj_form.forEach(function (input) {
     fdati[input.name] = input.value;
   });*/
  consolelogFunction("controller=openLink_" + LinkName + "&action=index");

  // Crea un oggetto per combinare i dati
  var formData = new FormData();
  var valuesArray = [];
  var form1 = new FormData(document.getElementById('MainForm'));
  var form2 = new FormData(document.getElementById('openLinkPage'));

  // Aggiungi i dati del primo form a formData
  form1.forEach(function (value, key) {
    formData.append(key, value);
  });

  // Aggiungi i dati del secondo form a formData
  form2.forEach(function (value, key) {
    formData.append(key, value);
  });

  formData.append('Force', 1);

  formData.forEach(function (value, key) {
    valuesArray.push({ key: key, value: value });
  });

  // Stampa l'array dei valori nella console
  consolelogFunction('formData Values:', valuesArray);

  // Stampa i dati combinati nella console
  // consolelogFunction('Combined Data:', formData);

  if (vMess != '') {
    var re = confirm(vMess);
  } else {
    re = true
  }
  if (re == true) {
    consolelogFunction(formData);
    var LinkPagina = $('#openLinkPage #LinkPagina').val()
    var LinkName = LinkPagina.replace(".php", "");
    //var LinkName = LinkName.replace("GiroRIAS1", "GiroRIAS");

    consolelogFunction("index.php?controller=openLink_" + LinkName + "&action=index",);
    consolelogFunction(formData);
    $.ajax({
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      // specifico la URL della risorsa da contattare
      url: "index.php?sito=" + getParameterByName('sito') + "&controller=openLink_" + LinkName + "&action=index",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div html
        /* $("#Filedialog").dialog({ title: LinkName });
         $("#Filedialog").dialog("open");
         $('#Filedialog').html(risposta);*/
        $("#Filedialog").dialog("close");
        $('#Waiting').hide();
        //initDati();
        RefreshPage();
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        errorMessage("OpenLink: Qualcosa è andato storto:");
        $('#Waiting').hide();
      }
    });

  }
}



function apriDettaglio(iddett) {
  consolelogFunction('apriDettaglio');
  var dettList = '';
  $('#Dett' + iddett).toggle();
  $('tr[id^="Dett"]').each(function () {
    //consolelogFunction($(this).attr('id'));
    //consolelogFunction($(this).css('display'));
    if ($(this).css('display') != 'none')
      dettList = dettList + $(this).attr('id') + "#";
  });
  consolelogFunction(dettList);
  $('#dettList').val(dettList);

}


function controllaFile(FileSel) {
  consolelogFunction('controllaFile:' + FileSel);
  if (!$("#" + FileSel).val()) {
    alert("Scegliere il File!");
    return false;
  } else { return true; }
}


function AutoPlay() {

  consolelogFunction('AutoPlay');
  var formData = {};

  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  consolelogFunction(formData);
  consolelogFunction("AutoPlay action=AutoPlayForm");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=workflow2&action=AutoPlayForm",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div html


      $("#dialogMail").dialog({ title: "Crea AutoPlay" });
      $("#dialogMail").dialog("open");
      $("#dialogMail").html(risposta);


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("AutoPlay: Qualcosa è andato storto!", stato);
    }
  });
};


function restSession() {
  consolelogFunction('restSession');
  var formData = {};



  consolelogFunction("restSession action=restSession");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=restSession",
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

function selectDipendenza(idFluso, idLegame) {


  var formData = { idFluso: idFluso, idLegame: idLegame };
  consolelogFunction('selectDipendenza action=selectflusso');
  consolelogFunction(formData);

  // consolelogFunction(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da selectDipendenza
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=selectDipendenza",
    // imposto l'azione in caso di successo
    success: function (risposta) {


      var UTILIZZATO = 0;
      const dataAsArray = JSON.parse(risposta);
      consolelogFunction("workflow2 selectDipendenza");
      consolelogFunction(dataAsArray);
      var sel = "";
      $('#ID_LEGAME').empty().select2();
      var opt = $('<option>', {
        'value': '',
      }).text('..');
      $('#ID_LEGAME').append(opt);


      $.each(dataAsArray, (index, row) => {

        //now HERE you construct your html structure, which is so much easier using jQuery
        sel = "";

        var opt = $('<option>', {
          'value': row['ID_LEGAME'],
        }).text(row['DIPENDENZA']);
        $('#ID_LEGAME').append(opt);


      });

      $('#ID_LEGAME').val(idLegame).trigger('change');
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("selectDipendenza: Qualcosa è andato storto!", stato);
    }
  });
}



function ModAutoPlay() {

  consolelogFunction('ModAutoPlay');
  var formData = {};

  let obj_form = $("form#FormAutoPlay").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  consolelogFunction(formData);
  consolelogFunction("ModAutoPlay action=addAutorun");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=addAutorun",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      //	$("#dialogMail").html(risposta);
      $("#dialogMail").dialog("close");
      reloadPage();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ModAutoPlay: Qualcosa è andato storto!", stato);
    }
  });
};


function stopFlusso() {

  consolelogFunction('stopFlusso');
  var formData = {};

  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  consolelogFunction(formData);
  consolelogFunction("stopFlusso action=stopFlusso");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=stopFlusso",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div html
      //	$("#dialogMail").html(risposta);
      // $("#dialogMail").dialog("close");
      reloadPage();


    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ModAutoPlay: Qualcosa è andato storto!", stato);
    }
  });
};


function convertSecondsToHHMMSS(totalSeconds) {
  var days = Math.floor(totalSeconds / 86400);
  var hours = Math.floor(totalSeconds / 3600);
  var minutes = Math.floor((totalSeconds % 3600) / 60);
  var seconds = Math.floor(totalSeconds % 60);

  // Formatta il risultato in hh:mm:ss
  var formattedTime = [
    days.toString().padStart(2, '0') + 'g',
    hours.toString().padStart(2, '0'),
    minutes.toString().padStart(2, '0'),
    seconds.toString().padStart(2, '0')
  ].join(':');

  return formattedTime;
}

/*function NewTime(vIdFlu, vIdLegame, vInizio) {
  consolelogFunction('NewTime');
  var now = new Date();
var inizio = new Date(vInizio);

var secLast = ( now.getTime() - inizio.getTime() ) / 1000;
  
$('#DivTimeBar'+vIdLegame).html(convertSecondsToHHMMSS(secLast));
}*/


function NewTime(vIdFlu, vIdLegame, vInizio, offsetMillis) {
  consolelogFunction('NewTime');

  // Data corrente del client
  var now = new Date();

  // Sincronizza la data di inizio con l'offset
  var inizioSincronizzato = new Date(new Date(vInizio).getTime() + 0);

  // Calcola il tempo trascorso
  var secLast = (now.getTime() - inizioSincronizzato.getTime()) / 1000;

  $('#DivTimeBar' + vIdLegame).html(convertSecondsToHHMMSS(secLast));
}

var avviaNewTimeId = {};

function avviaNewTime(vIdFlu, vIdLegame, vInizio) {
  if (avviaNewTimeId[vIdLegame]) {
    consolelogFunction('avviaNewTime Intervallo già attivo per vIdLegame:', vIdLegame);
    return;
  }

  // Calcola l'offset tra la data di inizio e la data corrente
  var offsetMillis = new Date().getTime() - new Date(vInizio).getTime() + 1;

  var intervalId = setInterval(function () {
    NewTime(vIdFlu, vIdLegame, vInizio, offsetMillis);
    consolelogFunction("avviaNewTime setInterval intervalId:" + intervalId);
    consolelogFunction("avviaNewTime setInterval vIdLegame:" + vIdLegame);

    if ($("#ImgRun" + vIdLegame).is(':hidden')) {
      consolelogFunction("avviaNewTime clearInterval #ImgRun" + vIdLegame + " intervalId:" + intervalId);
      clearInterval(intervalId);
      delete avviaNewTimeId[vIdLegame];
    }
  }, 1000);

  avviaNewTimeId[vIdLegame] = intervalId;
}
var avviaOpenMeterBarId = {};

function avviaOpenMeterBar(vIdFlu, vIdLegame, vInizio, vDipDiff, vOldDiff, vDipEsito) {
  // Controlla se esiste già un intervallo per questo vIdLegame
  if (avviaOpenMeterBarId[vIdLegame]) {
    consolelogFunction('avviaOpenMeterBar -Intervallo già attivo per vIdLegame:', vIdLegame);
    return; // Esci dalla funzione se l'intervallo è già attivo
  }
  var offsetMillis = new Date().getTime() - new Date(vInizio).getTime() + 1;
  // Crea un nuovo intervallo e memorizza l'ID nel registro
  var intervalId = setInterval(function () {
    OpenMeterBar(vIdFlu, vIdLegame, vInizio, vDipDiff, vOldDiff, vDipEsito, offsetMillis);
    consolelogFunction("avviaOpenMeterBar setInterval intervalId:" + intervalId);
    consolelogFunction("avviaOpenMeterBar setInterval vIdLegame:" + vIdLegame);

    if ($("#ImgRun" + vIdLegame).is(':hidden')) {
      consolelogFunction("avviaOpenMeterBar clearInterval #ImgRun" + vIdLegame + " intervalId:" + intervalId);
      clearInterval(intervalId);
      delete avviaOpenMeterBarId[vIdLegame]; // Rimuovi l'ID dal registro quando l'intervallo viene cancellato
    }
  }, 1000);

  // Memorizza l'ID dell'intervallo nel registro
  avviaOpenMeterBarId[vIdLegame] = intervalId;
}



function OpenMeterBar(vIdFlu, vIdLegame, vInizio, vDipDiff, vOldDiff, vDipEsito, offsetMillis) {
  consolelogFunction('OpenMeterBar');
  if (vDipDiff == '0') {
    var now = new Date();
    var inizio = new Date(vInizio);
    offsetMillis = 0;
    var vDipDiff = (now.getTime() - inizio.getTime() - offsetMillis) / 1000;
  }
  $('#DivMeterBar' + vIdLegame).load("./index.php?sito=" + getParameterByName('sito') + "&controller=workflow2&action=MeterBar", {
    IdFLu: vIdFlu,
    IdLegame: vIdLegame,
    Inizio: vInizio,
    SecLast: vDipDiff,
    SecPre: vOldDiff,
    DipEsito: vDipEsito
  });
  /*   
      var secLast = 120; // Esempio di valore
      var secPre = 100; // Esempio di valore
      var dipEsito = "F"; // Esempio di valore
  
      var barraCaricamento = "rgb(21, 140, 240)";
      var barraPeggio = "rgb(165, 108, 185)";
      var barraMeglio = "rgb(104, 162, 111)";
  
      if (secPre === 0) {
          secPre = 1;
      }
      var perc = Math.round((secLast * 100) / secPre);
  
      var sColor;
      if (perc > 100) {
          sColor = barraPeggio;
      } else if (perc <= 100 && dipEsito !== "I") {
          sColor = barraMeglio;
      }
  
      if (dipEsito === "I") {
          sColor = barraCaricamento;
      }
  
      if ((perc > 120 || perc < 90) && (dipEsito === "F" || dipEsito === "W") || dipEsito === "I") {
          var progressBar = $('<div class="progress-bar progress-bar-striped"></div>')
              .css({
                  'width': perc > 100 ? '100%' : perc + '%',
                  'background-color': sColor,
                  'height': '100%',
                  'float': 'left',
                  'border-radius': '5px',
                  'text-align': 'right',
                  'padding-right': '5px',
                  'min-width': '2em'
              })
              .attr('aria-valuenow', perc)
              .attr('aria-valuemin', 0)
              .attr('aria-valuemax', 100);
  
          if (dipEsito === "I") {
              progressBar.addClass('active');
          }
  
          var label = $('<label></label>').css({
              'margin': 0,
              'font-weight': 500
          }).text(perc > 100 ? '+' + (perc - 100) + '%' : (dipEsito !== "I" ? (perc - 100) + '%' : perc + '%'));
  
          progressBar.append(label);
          $('.progress').append(progressBar);
      }
  */
}
