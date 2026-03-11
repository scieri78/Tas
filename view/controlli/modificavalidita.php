<form id="FormModificaValidita" method="POST">
    <div id="FormModFlusso" class="dip_F">
      
        <div class="labelForm"><label form="LISTA_ID_CONTR">Lista ID CONTR</label></div>
        <div class="labelInput">
            <input readonly type="text" id="LISTA_ID_CONTR" Name="LISTA_ID_CONTR" style="width: 100%;" class="ModificaField" value="<?php echo $FORZATURA; ?>">
        </div>


        <div><label form="VALIDO">Validita</label></div>
        <div class="labelInput">
            <SELECT id="VALIDO" Name="VALIDO" class="ModificaField"> 
                <option value="1">SI</option>
                <option value="0">NO</option>
            </SELECT>
        </div>

      


        <div class="labelInput">
            <button style="float: right;" id="PulMod" onclick="ModificaDbValidita();return false;" class="btn AggiungiFlusso">
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script>
    $(function() {

        $("#FORZATURA").autocomplete({
            source: function(request, response) {
                var formData = {};
                formData['term'] = $("#FORZATURA").val();
                console.log('controller=controlli&action=getforzatura"');
                console.log(formData);
                $.ajax({

                    type: "POST",
                    url: 'index.php?controller=controlli&action=getforzatura',
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