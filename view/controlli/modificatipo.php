<form id="FormModificaTipo" method="POST">
    <div id="FormModFlusso" class="dip_F">
      
        <div class="labelForm"><label form="LISTA_ID_CONTR">Lista ID CONTR</label></div>
        <div class="labelInput">
            <input readonly type="text" id="LISTA_ID_CONTR" Name="LISTA_ID_CONTR" style="width: 100%;" class="ModificaField" value="<?php echo $FORZATURA; ?>">
        </div>


        <div><label form="TIPO">TIPO</label></div>
        <div class="labelInput">
            <input type="text" id="TIPO" Name="TIPO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['TIPO']; ?>">
        </div>

      


        <div class="labelInput">
            <button style="float: right;" id="PulMod" onclick="ModificaDbTipo();return false;" class="btn AggiungiFlusso">
                <i class="fa-solid fa-plus-circle"> </i> Modifica Validita'</button>
        </div>

</form>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script>
$(function() {

$("#TIPO").autocomplete({
  source: function(request, response) {
    var formData = {};
    formData['term'] = $("#TIPO").val();
    console.log('controller=controlli&action=gettipo"');
    console.log(formData);
    $.ajax({
      
      type: "POST",
      url: 'index.php?controller=controlli&action=gettipo',
      dataType: "json",
      encode: true,
      data: formData,
      
      success: function(data) {
        response(data);
      }
    });
  }
});



});


</script>