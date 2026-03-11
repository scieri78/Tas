<form id="FormModificaEsito" method="POST">
    <div id="FormModFlusso" class="dip_F">
        <input type="hidden" name="ID_LANCIO" id="ID_LANCIO" value="<?php echo $selectLancio; ?>">


        <div class="labelForm"><label form="DESCR">LANCIO</label></div>
        <div class="labelInput">
            <input readonly type="text" id="DESCR" Name="DESCR" style="width: 100%;" class="ModificaField" value="<?php echo $LANCIO; ?>">
        </div>


        <div class="labelForm"><label form="LISTA_ID_CONTR">Lista ID CONTR</label></div>
        <div class="labelInput">
            <input readonly type="text" id="LISTA_ID_CONTR" Name="LISTA_ID_CONTR" style="width: 100%;" class="ModificaField" value="<?php echo $FORZATURA; ?>">
        </div>


        <div><label form="ESITO">ESITO</label></div>
        <div class="labelInput">
            <SELECT id="ESITO" Name="ESITO" class="ModificaField">
                <?php
                foreach ($array_esito as $k => $val) {?>
                <option value="<?php echo $k; ?>"><?php echo $val; ?></option>
                <?php } ?>
            </SELECT>
        </div>

        <div><label form="NOTE">NOTE</label></div>
        <div class="labelInput">
            <textarea id="NOTE" Name="NOTE" rows="3" cols="80"></textarea>
        </div>


        <div class="labelInput">
            <button style="float: right;" id="PulMod" onclick="ModificaDbEsito();return false;" class="btn AggiungiFlusso">

                <i class="fa-solid fa-plus-circle"> </i> Crea Lancio</button>

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