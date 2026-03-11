$(document).ready(function () {
  $('#Waiting').hide();
// autoRefreshWasChecked come variabile globale 


  $('#Filedialog').dialog('option', 'close', function () {
    console.log('autoRefreshWasChecked', autoRefreshWasChecked);
    if (autoRefreshWasChecked) {
      $('#AutoRefresh').prop('checked', true);
    }
    });

  

  /*$("form#FormEserEsame").submit(function (event) {
    $('#Waiting').show();
    var formData = {};

    let obj_form = $(this).serializeArray();
    obj_form.forEach(function (input) {

      formData[input.name] = input.value;
    });

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      url: "index.php?controller=statoshell&action=contentList",
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
  });*/

  if ($(window).width() > 1165) {
    $("#LastRun").show();
  } else {
    $("#LastRun").hide();
  }
  $(window).on("resize", function () {
    if ($(window).width() > 1165) {
      $("#LastRun").show();
    } else {
      $("#LastRun").hide();
    }

  });
  var time;
  time = 30;
  if ($('#AutoRefresh').is(':checked') || $('#AutoRefresh2').is(':checked')) {
    console.log('AutoRefresh isChecked');
    autoRefreshWasChecked = true;
    setInterval(function () {
      //console.log("time: "+time);

      time--;
      if (time > 0) {
        $('#footer').html(time + ' secs');
      }
      if (time === 0) {

        Refresh();
        $('#footer').html('0 secs');
      }

    }, 1000);
    const myTimeout = setTimeout(myGreeting, 30000);

    function myGreeting() {
      // alert(myTimeout);
    }
  }

});

var autoRefreshWasChecked = false;


function viewFilter() {


  if ($('#viewfilter i').attr('class') == 'fa-solid fa-eye') {
    $("#viewfilter i").toggleClass('fa-eye fa-eye-slash');
    $(".divHiden").toggleClass('divHiden divshow');
    $(".divFilters").toggleClass('divFilters divshow');    
    $(".divFilter").toggleClass('divFilter divshow');    
    $("#viewFilterH").val('Si');


  } else {
    $("#viewfilter i").toggleClass('fa-eye-slash fa-eye');
    $(".divshow").toggleClass('divshow divHiden');
    $("#viewFilterH").val('No');

  }

}

/*
isOpen = $( ".selector" ).dialog( "isOpen" );
if(isOpen){$("#Filedialog").dialog("close");}
*/

function setTextarea(){
  console.log('setTextarea');
  $("textarea").css('height', '90%');
  $("textarea").css('width', '98%');
}

function ManualOk(vIdSh) {

  var re = confirm('Are you sure you want to change the status to manual ok?');
  if (re == true) {
    $('#Waiting').show();
    console.log('ManualOk');
    var formData = {};
    formData['ID_RUN_SH'] = vIdSh;
    formData['action'] = 'setManual';
    console.log(formData);

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      //url: "index.php?controller=statoshell&action=setManual&ID_RUN_SH=" + vIdSh,
      url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=setManual",
      // imposto l'azione in caso di successo
      success: function (risposta) {
       // $("#contenitore").html(risposta);
       $('#FormEserEsame').submit();
       // $('#Waiting').hide();
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        alert("Qualcosa è andato storto");
      }
    });
  }

}



function deleteSh(vIdSh) {

  var re = confirm('Are you sure you want to delete this Sh?');
  if (re == true) {
    $('#Waiting').show();
    console.log('ManualOk');
    var formData = {};
    formData['ID_RUN_SH'] = vIdSh;
    //formData['action'] = 'deleteSh';
    console.log(formData);

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      //url: "index.php?controller=statoshell&action=setManual&ID_RUN_SH=" + vIdSh,
      url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=deleteSh",
      // imposto l'azione in caso di successo
      success: function (risposta) {
      //  $("#contenitore").html(risposta);
       // $('#Waiting').hide();
        Refresh();
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        alert("Qualcosa è andato storto");
      }
    });
  }

}


function _viewLastRun() {
  console.log('viewLastRun');
  var formData = {};
  //formData['ID_RUN_SH']=vIdSh;
  //formData['action']='setManual';  
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=lastRun",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div htmlm


      $("#dialogMail").dialog({ title: 'Last Runs in the Month' });
      $("#dialogMail").dialog("open");
      $("#dialogMail").html(risposta);

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}


function apriLegend() {

  console.log('apriLegend');
  var formData = {};
  formData['IDSH'] = 1;

  clearAutoRefresh();
  // formData['action']=vaction;
  // formData['SHELL']=SHELL;
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    //url: "index.php?controller=statoshell&action=" + vaction,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=rcLegend",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div htmlm


      $("#dialogMail").dialog({ title: 'Rc legend' });
      $("#dialogMail").dialog("open");
      $("#dialogMail").html(risposta);
      setTimeout(setTextarea, 500);

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}


function viewLastRunx() {

  console.log('viewLastRunx');
  var formData = {};
  formData['IDSH'] = 1;

  clearAutoRefresh();
  // formData['action']=vaction;
  // formData['SHELL']=SHELL;
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    //url: "index.php?controller=statoshell&action=" + vaction,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=lastRun",
    // imposto l'azione in caso di successo
    success: function (risposta) {

      //visualizzo il contenuto del file nel div htmlm

      $("#dialogMail").dialog({ title: 'Last Runs in the Month' });
      //$("#Filedialog").dialog({title: SHELL});
      $("#dialogMail").dialog("open");
      $("#dialogMail").html(risposta);
      setTimeout(setTextarea, 500);

     // var Log = $("#Log").val();
     // var idhs = $("#IDHS").val();
     // var SHELLLOG = "LOG: (" + idhs + ") " + Log;
      //var SHELLFILE = "SHELL: (" + idhs + ") " + Log;
     /* if (vaction == 'apriFile') {
        SHELL2 = SHELLFILE;
      } else {
        SHELL2 = SHELLLOG;
      }
      console.log(SHELL2);
      $("#Filedialog").dialog({ title: SHELL2 });*/
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}


function clearAutoRefresh() {
  console.log('clearAutoRefresh');
  for (var i = 1; i < 100; i++)
    window.clearInterval(i); 
  $('#footer').html('');
  $("#AutoRefresh").prop("checked", false);
}



function openDialog(vIdSh, SHELL, vaction) {

  console.log('openDialog');
  var formData = {};
  formData['IDSH'] = vIdSh;
  formData['DARETI'] = getValueDARETI();
  autoRefreshWasChecked = $('#AutoRefresh').is(':checked');
  console.log('openDialog autoRefreshWasChecked',autoRefreshWasChecked);


  clearAutoRefresh();
  // formData['action']=vaction;
  // formData['SHELL']=SHELL;
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=" + vaction,
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
      alert("Qualcosa è andato storto");
    }
  });


}



function openSqlFile(vIdSql, vDescr, SHFILE) {
  if (vDescr != 'Anonymous Block' && vDescr != 'SQL DB2 Block') {
    console.log('openSqlFile');
    clearAutoRefresh();
    var formData = {};
    formData['IDSQL'] = vIdSql;
    formData['Descr'] = vDescr;
    formData['SHFILE'] = SHFILE;
    //formData['action']='setManual';  
    console.log(formData);

    $.ajax({
      type: "POST",
      data: formData,
      encode: true,

      // specifico la URL della risorsa da contattare
      // url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql,
      url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriSqlFile",
      // imposto l'azione in caso di successo
      success: function (risposta) {
        //visualizzo il contenuto del file nel div htmlm
        $("#Filedialog").html(risposta);
       
        $("#Filedialog").dialog({ title: "FILE: " + vDescr });
        $("#Filedialog").dialog("open");
        setTimeout(setTextarea, 500);
      },
      //imposto l'azione in caso di insuccesso
      error: function (stato) {
        alert("Qualcosa è andato storto");
      }
    });
  }

}


function reOpenSqlFile(vIdSql) {
  var ShowVar = 0;
  clearAutoRefresh();
  if ($("input#ShowVar:checked").val() == 1) { ShowVar = 1 };
  console.log('reOpenSqlFile');
  var formData = {};
  formData['IDSQL'] = vIdSql;
  formData['ShowVar'] = ShowVar;
  //formData['SHFILE']=SHFILE;
  //formData['action']='setManual';  
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    // url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql + "&ShowVar=" + ShowVar,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriSqlFile",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      setTimeout(setTextarea, 200);
      //$("#Filedialog").dialog({title:"FILE: "+SHFILE});
      //$("#Filedialog").dialog("open");	
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}


 function Refreshold() {
	console.log('refreshPage');
	
	location.reload();
}

function Refresh() {
  $('#Waiting').show();
  var form =$('#FormEserEsame');
   form.submit();
};

function resetSession() {
  console.log('resetSession');
  $("#resetSession").val("1");
  var form =$('#FormEserEsame');
   form.submit();
}



function OpenShSel(vId) {
var DARETI = getValueDARETI();
//  clearAutoRefresh();
  window.open("index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=index&IDSELEM=" + vId+"&DARETI="+DARETI);
  /*$.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=statoshell&action=contentList&IDSELEM="+vId,  
          // imposto l'azione in caso di successo
          success: function(risposta){
       
          //visualizzo il contenuto del file nel div htmlm
            
  	
      $("#SHdialog").dialog({title: vId});
      $("#SHdialog").dialog("open");	
      $("#SHdialog").html(risposta);
      $('#Waiting').hide();
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });*/


}

const LAST_DAYS = 88;
const LAST_3_DAYS = 99;
const ALL_DAY = 999;


function OpenSelShell(vId) {
  //clearAutoRefresh();
  
   var oldSeInDate = $('#SelInDate').val();
var oldNumLast = $('#NumLast').val();
var SelShelT = $('#SelShelT').val();

  $('#SelInDate').val(ALL_DAY).prop('selected', true); 
  //alert($('#SelShell').val());
    $('#NumLast').val(10);
    $('#SelShelT').val(vId);

  form = $('#FormEserEsame');
        // Crea una nuova finestra        
        // Crea un modulo dinamico
       /* var form = $('<form>', {
            action: 'index.php?controller=statoshell&action=index', // Sostituisci con l'URL della tua pagina
            method: 'POST',
            target: 'NuovaFinestra'+vId // Invia i dati alla finestra appena aperta
        }).appendTo('body'); // Aggiungi il modulo al body
        // Duplica i campi dal modulo esistente
        $('#FormEserEsame').find('input, select, textarea').each(function() {
            var input = $(this);
            if (input.attr('type') === 'checkbox') {
                // Se è un checkbox, crea un campo nascosto per il valore
                $('<input>', {
                    type: 'hidden',
                    name: input.attr('name'),
                    value: '0' // Valore di default per il checkbox non selezionato
                }).appendTo(form);
                // Aggiungi il checkbox solo se selezionato
                if (input.is(':checked')) {
                    $('<input>', {
                        type: 'hidden',
                        name: input.attr('name'),
                        value: '1' // Valore per il checkbox selezionato
                    }).appendTo(form);
                }
            } else {
                // Altri input
                $('<input>', {
                    type: 'hidden', // Usa hidden per inviare senza mostrare
                    name: input.attr('name'),
                    value: input.val()
                }).appendTo(form);
            }
        });*/
        // Invia il modulo
        form.submit();
        // Rimuovi il modulo dal DOM
       
    

                                                                                                    
  // Rimuovi il modulo dal DOM


  //window.open("index.php?controller=statoshell&action=index&SelShell=" + vId,'NuovaFinestra');
}



function openGrafici(ShName, ShTags, IdSh) {
 
  console.log('openGrafici');
  clearAutoRefresh();
  var formData = {};
  formData['STEP'] = ShName;
  formData['TAGS'] = ShTags;
  formData['IDSH'] = IdSh;
  formData['DARETI'] = getValueDARETI();
  //formData['SHFILE']=SHFILE;
  //formData['action']='setManual';  
  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    //url: "index.php?controller=statoshell&action=apriGrafici&STEP=" + ShName + "&TAGS=" + ShTags + "&IDSH=" + IdSh + "",
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriGrafici",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: "Grafico: " + ShName });
      $("#Filedialog").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}

function openTabLog(vIdSql, SHFILE) {
  
  console.log('openTabLog');
  clearAutoRefresh();
  var formData = {};
  formData['IDSQL'] = vIdSql;

  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    // url: "index.php?controller=statoshell&action=apriTabLog&IDSQL=" + vIdSql,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriTabLog",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: "TABELLE UTILIZZATE DAL FILE: " + SHFILE });
      $("#Filedialog").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });
}

function getValueDARETI() {
    var element = $('#DARETI');
    if (element.length > 0) {
        return element.val();
    } else {
        return 0;
    }
}


function openRelTab(IDSH, IDRUNSH, IDRUNSQL, SHFILE) {
  console.log('openRelTab');
  clearAutoRefresh();
  var formData = {};
  formData['IDRUNSH'] = IDRUNSH;
  formData['IDRUNSQL'] = IDRUNSQL;
  formData['IDSH'] = IDSH;
  formData['DARETI'] = getValueDARETI();

  console.log(formData);

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    // url: "index.php?controller=statoshell&action=apriRelTab&IDRUNSH=" + IDRUNSH + "&IDRUNSQL=" + IDRUNSQL + "&IDSH=" + IDSH + "",
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriRelTab",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: "TABELLE UTILIZZATE DAL FILE: " + SHFILE });
      $("#Filedialog").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}

function apriTabPlsql(vSchema, VPackage) {
  console.log('apriTabPlsql');
  clearAutoRefresh();
  var formData = {};
  formData['SCHEMA'] = vSchema;
  formData['PACKAGE'] = VPackage;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    // url: 'index.php?controller=statoshell&action=apriTabPlsql&SCHEMA=' + vSchema + '&PACKAGE=' + VPackage,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriTabPlsql",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      $("#Filedialog").dialog({ title: "TABELLE UTILIZZATE DAL PACKAGE SCHEMA:" + vSchema + " ,PACKAGE:" + VPackage });
      $("#Filedialog").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}

function apriPlsql(vSchema, VPackage) {
  console.log('apriPlsql');
  clearAutoRefresh();
  var formData = {};
  formData['SCHEMA'] = vSchema;
  formData['PACKAGE'] = VPackage;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    // url: 'index.php?controller=statoshell&action=apriPlsql&SCHEMA=' + vSchema + '&PACKAGE=' + VPackage,
    url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=apriPlsql",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      $("#Filedialog").html(risposta);
      setTimeout(setTextarea, 500);
      $("#Filedialog").dialog({ title: vSchema + " " + VPackage });
      $("#Filedialog").dialog("open");
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      alert("Qualcosa è andato storto");
    }
  });


}



$(".selectSearch").select2();
$('.selectNoSearch').select2({ minimumResultsForSearch: -1 });




function ChangePage(vPage) {
  $('#SelNumPage').val(vPage);
  $('#CambioPag').val('1');
  Refresh();
}



function ForceEnd(vIdRunSh) {
  var re = confirm('Are you sure you want to put this Shell in an error state?');
  if (re == true) {
    var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ForceEnd")
      .val(vIdRunSh);
    $('#FormEserEsame').append($(input));
    $('#FormEserEsame').submit();

  }
}

function rcLegndAction() {
  console.log('apriLegend');
  if ($('#RCLegendH').val()=='0') {
    apriLegend();    
    $('#RCLegendH').val()='1';
  } else {
    $("#dialogMail").dialog("close");
  $('#RCLegendH').val()='0';
  }
return false;
}




if ($('#PLSSHOWDETT').is(":checked")) {
  $('.ClsDett').each(function () {
    $(this).show();
  });
} else {
  $('.ClsDett').each(function () {
    $(this).hide();
  });
}

$('#PLSSHOWDETT').change(function () {
  if ($('#PLSSHOWDETT').is(":checked")) {
    $('.ClsDett').each(function () {
      $(this).show();
    });
  } else {
    $('.ClsDett').each(function () {
      $(this).hide();
    });
  }
});

$('.ReloadForm').change(function () {
  if ($(this).val() != 'SkipUnify') {
    $('#SelNumPage').val('1');
  }
  $('#Waiting').show();
  $('#FormEserEsame').submit();
});

function selectSelShell(){
  console.log('selectSelShell');
  $('#SelInDate').val(ALL_DAY).prop('selected', true); 
  //alert($('#SelShell').val());
  
  	if ( $('#SelShell').val() == '' ){
	  $('#SelInDate').val(LAST_DAYS).prop('selected', true); 
	}

	if ( $('#SelMeseElab').val() != '%' ){
	  $('#NumLast').val(10);
	}
	$('#SelNumPage').val('1'); 
	$('#SelShelT').val(''); 
	
  if($('#SelShell').val()==''){
    $('#NumLast').val(10);
  }
  
  $('#FormEserEsame').submit()
}


function selectSelInDate(){
  $('#SelNumPage').val('1'); 
  if ($('#SelInDate').val() != ALL_DAY) {
    $('#NumLast').val(10);
  }else if($("SelShell").val()!=undefined){
    $('#NumLast').val(10);
  }
  $('#FormEserEsame').submit();
  
}

function selectSelIdProc(){
  $('#SelNumPage').val('1'); 
  $('#NumLast').val(10);
  $('#FormEserEsame').submit();
 
}

function selAmbito(){
  console.log('selAmbito'); 	


  $('#FormEserEsame').submit()
}

function NumLastNoSubmit(){
  console.log('NumLastNoSubmit'); 
  	


  if($('#SelMeseElab').val() == '%' &&  $('#SelInDate').val() == ALL_DAY && $('#NumLast').val() ==0 && $('#SelShell').val()==''){
    $('#NumLast').val(30);
   //   $('#FormEserEsame').submit();

  }
 // alert ("NumLast");
  return true;
  
 
}

/*	function ShowGraph(vStep,vTags,vIdSh){
    window.open('./PHP/GraphStep.php?STEP='+vStep+'&TAGS='+vTags+'&IDSH='+vIdSh);
  };

  function OpenFile(vIdSh){
    window.open('./PHP/ApriFile.php?IDSH='+vIdSh);
  }
  function OpenSqlFile(vIdSql,vDescr){
    if ( vDescr != 'Anonymous Block' && vDescr != 'SQL DB2 Block' ){
      window.open('./PHP/ApriSqlFile.php?IDSQL='+vIdSql);
    }
  }
  function OpenLog(vIdRunSh){
    window.open('./PHP/ApriLog.php?IDSH='+vIdRunSh);
  }

  function OpenTab(vIdSql){
    window.open('./PHP/ApriTabLog.php?IDSQL='+vIdSql);
  }
  function OpenPlsql(vSchema,VPackage){
    window.open('./PHP/ApriPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage);
  }

  function OpenTabPlsql(vSchema,VPackage){
    window.open('./PHP/ApriTabPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage,'TablePackage','width=500,height=500');
  }

  function OpenTabFile(vIdSh,vIdRunSh,vIdRunSql){
    window.open('./PHP/ApriRelTab.php?IDSH='+vIdSh+'&IDRUNSH='+vIdRunSh+'&IDRUNSQL='+vIdRunSql,'RelTable','width=700,height=500');
  }  */
