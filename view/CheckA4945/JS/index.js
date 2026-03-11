$(document).ready(function () {
    $('#Waiting').hide();

});


function refreshLinkEsterni(){
    console.log('refreshLink');
    var formData = {};
    let obj_form = $("form#MainForm").serializeArray();
    obj_form.forEach(function(input) {
      formData[input.name] = input.value;
    });
   
    obj_form = $("form#openLinkPage").serializeArray();
    obj_form.forEach(function(input) {
      formData[input.name] = input.value;
    });  
   console.log(formData);
   var LinkNameDip = $('#LinkNameDip').val();
   $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=elaborazioni&action=contentList",
    // imposto l'azione in caso di successo
    success: function(risposta) {
      //visualizzo il contenuto del file nel $("#Filedialog").dialog("open");
      $('#contenitore').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      errorMessage("refreshLinkEsterni: Qualcosa è andato storto!", stato);
      $('#Waiting').hide();
    }
  });
  
  }