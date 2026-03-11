
$('#Waiting').hide();
$(".selectSearch").select2();

$("#divKambanForm").dialog({
  autoOpen: false,
  width: '90%',
  minHeight: 400,
  height: 'auto',
  modal: false,
  dialogClass: "no-close",
  position: { my: "center top+30", at: "center top", of: window },
  close: function () {
    location.reload();
  },
});

$(function () {
  $(".kanban-column").sortable({
    connectWith: ".kanban-column",
    receive: function (e, ui) {
      var id = ui.item.data("id");
      var stato = $(this).data("stato");
      updateStato(id, stato);
    }
  }).disableSelection();
});

function formatDateAndTimeDifference(dateString) {
    // Parsare la data originale
    const originalDate = new Date(dateString.replace(' ', 'T'));
    
    // Data attuale
    const currentDate = new Date();
    
    // Calcolare la differenza in millisecondi
    const diffMs = currentDate - originalDate;
    
    // Convertire in giorni, ore e minuti
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
    
    // Formattare la data originale in d/m/Y H:i:s
    const day = originalDate.getDate().toString().padStart(2, '0');
    const month = (originalDate.getMonth() + 1).toString().padStart(2, '0');
    const year = originalDate.getFullYear();
    const hours = originalDate.getHours().toString().padStart(2, '0');
    const minutes = originalDate.getMinutes().toString().padStart(2, '0');
    const seconds = originalDate.getSeconds().toString().padStart(2, '0');
    
    const formattedDate = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    
    // Creare il messaggio di output
    return `Sono passati ${diffDays} giorni ${diffHours} ore ${diffMinutes} minuti dalla data ${formattedDate}`;
}


function formTicket(id, username,startDate) {
  console.log('formTicket');
  var formData = {};
  formData['id'] = id;
  formData['formTicket'] = 'formTicket';
  if(startDate)
  {
  // Crea un oggetto Date per la data di partenza
  var startDateObj = new Date(startDate);
  // Crea un oggetto Date per la data odierna
  var currentDateObj = new Date();
  // Calcola la differenza in millisecondi
  var differenceInMillis = currentDateObj - startDateObj;
  // Converti la differenza in giorni
  var daysPassed = Math.floor(differenceInMillis / (1000 * 60 * 60 * 24));
  }
  /*  let obj_form = $("form#FormMain").serializeArray();
    obj_form.forEach(function (input) {
        formData[input.name] = input.value;
    });*/
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=formTicket",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      //visualizzo il contenuto del file nel div htmlm
      if (!$('#divKambanForm').dialog('isOpen')) {
        $("#divKambanForm").html(risposta);
        var strdata = '';
        if(startDate)
        {
        strdata = formatDateAndTimeDifference(startDate);
        }
        var testoTitle = (id) ? username + ': Ticket ' + id + ' - ' + strdata : 'Crea Ticket ';
        $("#divKambanForm").dialog({ title: testoTitle });
        $("#divKambanForm").dialog("open");
      }

    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("openDialogMail: Qualcosa è andato storto!", stato);
    }
  });


}


function addTichet() {
  console.log('addTichet');
  var formData = {};

  let obj_form = $("form#FormAddTicket").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

  formData['descrizione'] = tinymce.get('mytextarea').getContent();
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=create",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //  $("#divKambanForm").html(risposta);
      //  location.reload(); 
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("addTichet: Qualcosa è andato storto!", stato);
    }
  });


}

function updateStato(id, stato) {
  console.log('updateStato');
  var formData = {};

  formData['id'] = id;
  formData['stato'] = stato;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=updateStato",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("updateStato: Qualcosa è andato storto!", stato);
    }
  });


}


function ttassegnato(TTASSEGATO) {
  console.log('ttassegnato');
  var formData = {};

  formData['TTASSEGATO'] = TTASSEGATO;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=ttassegnato",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      // $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ttassegnato: Qualcosa è andato storto!", stato);
    }
  });


}


function ttuser(TTUSER) {
  console.log('ttuser');
  var formData = {};

  formData['TTUSER'] = TTUSER;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=ttuser",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      // $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ttassegnato: Qualcosa è andato storto!", stato);
    }
  });


}


function ttuserAssegnato(TTUSERASSEGNATO) {
  console.log('ttuserAssegnato');
  var formData = {};

  formData['TTUSERASSEGNATO'] = TTUSERASSEGNATO;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=ttuserassegnato",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      // $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ttassegnato: Qualcosa è andato storto!", stato);
    }
  });


}


function ttposizione(TTPOSIZIONE) {
  console.log('ttuser');
  var formData = {};

  formData['TTPOSIZIONE'] = TTPOSIZIONE;
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=ttposizione",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      // $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      location.reload();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("ttassegnato: Qualcosa è andato storto!", stato);
    }
  });


}



function delTicket() {
  console.log('delTicket');
  if (!confirm("Sicuro di voler eliminare questo Tiket?")) { return false }


  var formData = {};
  formData['id'] = $('#idTicket').val();
  //formData['action']='delTicket';
  console.log(formData);
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=tickets&action=delTicket",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      $("#divKambanForm").dialog("close");
      //visualizzo il contenuto del file nel div htmlm
      //   $('#contenitore').html(risposta);
      //    location.reload(); 
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("delTicket: Qualcosa è andato storto!", stato);
    }
  });


}



