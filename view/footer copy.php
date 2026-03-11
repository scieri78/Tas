<br/><br/><br/><br/><br/>
</div>
</div>
<div id="dialogMail"></div>
<div id="Filedialog"></div>
<div id="SHdialog"></div>
<div id="footer">

<div id="Bilderdialog1" ></div>
<div id="Bilderdialog2" ></div>
<input type="hidden" id="Bilderdialog" name="Bilderdialog" value="1">
<input type="hidden" id="dialogIdFlu" name="dialogIdFlu" value="">

</div>
<SCRIPT>

function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}



$("form#loginForm").submit(function(event) {
  $('#Waiting').show();
  var formData = {};

  let obj_form = $(this).serializeArray();
  obj_form.forEach(function(input) {

    formData[input.name] = input.value;
  });

  $.ajax({
    type: "POST",
    data: formData,
    encode: true,

    // specifico la URL della risorsa da contattare
    url: "index.php?controller=login&action=index",
    // imposto l'azione in caso di successo
    success: function(risposta) {

      $(location).prop('href', 'index.php')
      //visualizzo il contenuto del file nel div htmlm
      //  $("#shellContent").html("");
      // $("#contenitore").html(risposta);			

    },
    //imposto l'azione in caso di insuccesso
    error: function(stato) {
      alert("Qualcosa è andato storto");
    }
  }).done(function(data) {


  });

  event.preventDefault();
});
</SCRIPT>
</body>
</html>
<!-- -->