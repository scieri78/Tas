$(document).ready(function () {
    $('#Waiting').hide();

});




  function xsaveArgo(){
    console.log('saveArgo');
    var formData = {};
    let obj_form = $("form#MainForm").serializeArray();
    obj_form.forEach(function(input) {
      formData[input.name] = input.value;
    });
   
    obj_form = $("form#openLinkPage").serializeArray();
    obj_form.forEach(function(input) {
      formData[input.name] = input.value;
    });  
   console.log('saveArgo',formData);   
   var LinkNameDip = $('#LinkNameDip').val();
   console.log("index.php?controller=openLink_argo+&action=insertParametri");
   $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=openLink_argo&action=insertParametri",
    // imposto l'azione in caso di successo
    success: function(risposta) {     //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog("close");     
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      errorMessage("saveArgo: Qualcosa è andato storto!", stato);
    }
  });
  
  }