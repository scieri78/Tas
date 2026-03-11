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
   var LinkPagina =$('#openLinkPage #LinkPagina').val()
   var LinkName = LinkPagina.replace(".php", "");
   //var LinkName = LinkName.replace("GiroRIAS1", "GiroRIAS");
   console.log("index.php?controller=openLink_"+LinkName+"&action=consolidaGiro");
   $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=openLink_"+LinkName+"&action=consolidaGiro",
    // imposto l'azione in caso di successo
    success: function(risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog({ title: LinkName});
      $("#Filedialog").dialog("close");
      alert("Dati Consolidati! Attendere Completamento azione!");
     // $('#Filedialog').html(risposta);
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      errorMessage("consolidaGiro: Qualcosa è andato storto!", stato);
    }
  });
  
  }


  function validalegame(){
    console.log('validaGiro');
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
   var LinkPagina =$('#openLinkPage #LinkPagina').val()
   var LinkName = LinkPagina.replace(".php", "");
   //var LinkName = LinkName.replace("GiroRIAS1", "GiroRIAS");
   console.log("index.php?controller=openLink_"+LinkName+"&action=validaGiro");
   $.ajax({
    type: "POST",
    data: formData,
    encode: true,
    // specifico la URL della risorsa da contattare
    url: "index.php?controller=openLink_"+LinkName+"&action=validaGiro",
    // imposto l'azione in caso di successo
    success: function(risposta) {
      //visualizzo il contenuto del file nel div html
      $("#Filedialog").dialog({ title: LinkName});
      $("#Filedialog").dialog("close");
      alert("Dati Validati! Attendere Completamento azione!");
    //  $('#Filedialog').html(risposta);
    RefreshPage();
      $('#Waiting').hide();
    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      errorMessage("validalegame: Qualcosa è andato storto!", stato);
    }
  });
  
  }