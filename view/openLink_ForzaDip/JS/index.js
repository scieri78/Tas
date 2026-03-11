$(document).ready(function () {
    $('#Waiting').hide();

});



  
function consolidaGiro(){
    console.log('consolidaGiro');
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
   console.log("index.php?controller=openLink_"+LinkNameDip+"&action=consolidaGiro");
   $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=openLink_"+LinkNameDip+"&action=consolidaGiro",
    // imposto l'azione in caso di successo
    success: function(risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog({ title: LinkNameDip});
      $("#Filedialog").dialog("open");
      $('#Filedialog').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      errorMessage("consolidaGiro: Qualcosa è andato storto!", stato);
    }
  });
  
  }