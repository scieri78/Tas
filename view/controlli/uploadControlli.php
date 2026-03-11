<form action="" id="upload" method="post" enctype="multipart/form-data">
  <input type="hidden" name="ID_GRUPPO" id="ID_GRUPPO" value="<?php echo $ID_GRUPPO; ?>">
  <div><label form="INPUT">Nome Import</label></div>
  <div>
    <input type="text" id="INPORT" name="INPORT" style="width: 100%;" class="ModificaField" value="IMPORT">
  </div>
  <div><label form="fileToUpload">Seleziona file xlsx per inserire i controlli</label></div>

  <div>
    <input type="file" name="fileToUpload" id="fileToUpload">
  </div>

  <br><br>
  <div>

    <button class="btn"><i class="fa-solid fa-file-import"></i> Import Controlli</button>
  </div>
</form>
<script>
  $(document).ready(function(e) {
    $("#upload").on('submit', (function(e) {
      e.preventDefault();
      $('#Waiting').show();
      console.log("uploadFile controller=controlli&action=inportcontrolli");
      $.ajax({
        url: "index.php?controller=controlli&action=inportcontrolli",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          //$("#preview").fadeOut();
          $("#err").fadeOut();
        },
        success: function(data) {
          if (data == 'invalid') {
            alert('invalid');
            // invalid file format.
            // $("#err").html("Invalid File !").fadeIn();
          } else {
            $("#dialogMail").dialog("close");
          //  $("#dialogMail").html(data);
            /*const ID_INPORT = JSON.parse(data);
            console.log("ID_INPORT:" + ID_INPORT);*/
            filtraControllo();            
          /*  setTimeout(function() {
              $('#selectInport').val(ID_INPORT).change();
            }, 5000);*/
            //filtraControllo();            
            
          }
        },
        error: function(e) {
          $("#err").html(e).fadeIn();
        }
      });
    }));
  });
</script>