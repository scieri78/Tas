<form action="" id="upload" method="post" enctype="multipart/form-data">
  Please upload a compatible xlsx file:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="hidden" name="idgruppo" id="idgruppo" value="<?php echo $idgruppo;?>">
  <button class="btn"><i class="fa-solid fa-file-import"></i> Upload Controlli</button>                           
</form>
<script>
  $(document).ready(function (e) {
 $("#upload").on('submit',(function(e) {
  e.preventDefault();
  $('#Waiting').show();
  $.ajax({
  url: "index.php?controller=controlli&action=insertcontrolli",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   beforeSend : function()
   {
    //$("#preview").fadeOut();
    $("#err").fadeOut();
   },
   success: function(data)
      {
    if(data=='invalid')
    {
      alert('invalid');
     // invalid file format.
    // $("#err").html("Invalid File !").fadeIn();
    }
    else
    {
     // view uploaded file.

     $('#dialogMail').html(data);
     $('#Waiting').hide();
    }
      },
     error: function(e) 
      {
    $("#err").html(e).fadeIn();
      }          
    });
 }));
});
</script>
