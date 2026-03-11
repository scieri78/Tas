$(document).ready(function () {
  $('#Waiting').hide();

});




function saveParametri() {
  console.log('saveParametri');
  var formData = {};
  let obj_form = $("form#MainForm").serializeArray();
  obj_form.forEach(function (input) {
    formData[input.name] = input.value;
  });

 obj_form = $("form#openLinkPage").serializeArray();
obj_form.forEach(function (input) {
  if (formData[input.name]) {
    if (!Array.isArray(formData[input.name])) {
      formData[input.name] = [formData[input.name]];
    }
    formData[input.name].push(input.value);
  } else {
    formData[input.name] = input.value;
  }
});
/*
  $('input[type="checkbox"]:checked').each(function() {
  if (formData[this.name]) {
    if (!Array.isArray(formData[this.name])) {
      formData[this.name] = [formData[this.name]];
    }
    formData[this.name].push(this.value);
  } else {
    formData[this.name] = this.value;
  }
});*/
  console.log('saveParametri', formData);
  var LinkNameDip = $('#LinkNameDip').val();
  console.log("saveParametri: controller=openLink_parametri+&action=insertParametri");
  $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=openLink_parametri&action=insertParametri",
    // imposto l'azione in caso di successo
    success: function (risposta) {
      console.log('saveParametri: OK');
      //visualizzo il contenuto del file nel div html
     $("#Filedialog").dialog("close");
  // $("#Filedialog").html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function (stato) {
      errorMessage("saveArgo: Qualcosa è andato storto!", stato);
    }
  });

}


function validateDecimalPrecision(inputElement, precision) {

  var value = $('#' + inputElement).val();
  var numeroDecimali = countDecimals(value);
  console.log('validateDecimalPrecision', value, precision);
  console.log('validateDecimalPrecision numeroDecimali', numeroDecimali);
  var decimalIndex = value.indexOf('.');

  if (numeroDecimali > precision) {
    // Se ci sono più cifre decimali del consentito, mostra un messaggio di errore
    alert('Puoi inserire al massimo ' + precision + ' cifre decimali.');
    $('#' + inputElement).val(value.substring(0, decimalIndex + precision + 1));
  }

}

function countDecimals(value) {
  var decimalIndex = value.indexOf('.');
  if (decimalIndex === -1) {
    return 0; // Nessun punto decimale, quindi zero cifre decimali
  } else {
    return value.length - decimalIndex - 1;
  }
}


function validaMaxLength(inputElement, maxLength) {
  if (maxLength > 0) {
    if (inputElement.value.length > maxLength) {
      inputElement.value = inputElement.value.substring(0, maxLength);
      alert('Hai superato il limite massimo di ' + maxLength + ' caratteri.');
    }
  }
}


function formatDateTime(value) {
    var date = new Date(value);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}

function updateFormattedDisplay(elementId) {
    var input = document.getElementById(elementId);
    var formattedDateTime = formatDateTime(input.value);
    document.getElementById('formattedDisplay_' + elementId.split('_')[1]).textContent = formattedDateTime;
}
function formatDateTime(value) {
    var date = new Date(value);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}

function updateFormattedDisplay(elementId) {
    var $input = $('#' + elementId);
    var formattedDateTime = formatDateTime($input.val());
    var $display = $('#formattedDisplay_' + elementId.split('_')[1]);
    $display.text(formattedDateTime).hide(); // Nasconde il campo dopo aver aggiornato il testo
}