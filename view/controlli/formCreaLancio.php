<form id="FormCrealancio" method="POST">
    <div id="FormModFlusso" class="dip_F">
        <input type="hidden" name="ID_GRUPPO" id="ID_GRUPPO" value="<?php echo $selectGruppo; ?>">
        <input type="hidden" name="ID_INPORT" id="ID_INPORT" value="<?php echo $selectInport; ?>">
        <input type="hidden" name="NAME_FILE" id="NAME_FILE" value="">

        <div class="labelForm"><label form="DESCR">LANCIO</label></div>
        <div class="labelInput">
            <input type="text" id="DESCR" Name="DESCR" style="width: 100%;" class="ModificaField" value="<?php echo $LANCIO; ?>">
        </div>






        <div class="labelForm"><label form="FORZATURA">FORZATURA</label></div>
        <div class="labelInput">
            <input type="text" id="FORZATURA" Name="FORZATURA" style="width: 100%;" class="ModificaField" value="<?php echo $FORZATURA; ?>">
        </div>

        <div class="labelForm"><label form="LANCIO_ID_CONTR">Lista ID CONTR</label></div>
        <div class="labelInput">
            <input readonly type="text" id="LANCIO_ID_CONTR" Name="LANCIO_ID_CONTR" style="width: 100%;" class="ModificaField" value="">
        </div>

        <div class="labelForm"><label form="SOTTO GRUPPO">SOTTO GRUPPO</label>
        </div>

        <div class="labelInput">
            <select onchange="loadFileSelect()" class="selectpicker" data-live-search="true" id="SOTTO_GRUPPO" name="SOTTO_GRUPPO" style="width:180px;height:30px;">

                <option value=""> SELEZIONA SOTTO GRUPPO</option>
                <?php
                foreach ($datiSottoGruppi as $row) {
                    $ID = $row['SOTTO_GRUPPO'];
                    $VAL = $row['SOTTO_GRUPPO'];


                ?><option value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                <?php
                }
                ?>
            </select>
        </div>

        <div class="labelForm"><label form="ID_FILE">FILE</label>
        </div>
        <div class="labelInput">
            <button id="PulMod" onclick="$('#ID_FILE').selectpicker('selectAll');return false;" class="btn AggiungiFlusso">
                <i class="fa-solid fa-check-double"></i> Select All</button>
            <button id="PulMod" onclick="$('#ID_FILE').selectpicker('deselectAll');return false;" class="btn AggiungiFlusso">
                <i class="fa-solid fa-check-double"></i> deselect All</button>
        </div>

        <div class="labelInput">
            <select title="SELEZIONA FILE" onchange="selFile()" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="ID_FILE" name="ID_FILE" style="width:180px;height:30px;">
            </select>
        </div>


        <div class="labelForm"><label form="ID_TIPO">TIPO</label>
        </div>
        <div class="labelInput">
            <select title="SELEZIONA TIPO" data-done-button="true" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="ID_TIPO" name="ID_TIPO" style="width:180px;height:30px;">


                <?php
                foreach ($datiTipo as $v) {
                    $ID = $v['ID_TIPO'];
                    $VAL = $v['TIPO'];

                ?><option value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                <?php
                }
                ?>
            </select>

        </div>



        <div class="labelForm"><label form="CLASSE">CLASSE</label>
        </div>
        <div class="labelInput">
            <select title="SELEZIONA CLASSE" data-done-button="true" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="CLASSE" name="CLASSE" class="FieldMand selectSearch" style="width:180px;height:30px;">


                <?php
                foreach ($array_classe as $k => $v) {
                    $ID = $k;
                    $VAL = $v;


                ?><option value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                <?php
                }
                ?>
            </select>
        </div>



        <div class="labelInput">
            <button style="float: right;" id="PulMod" onclick="creaDBLancio();return false;" class="btn AggiungiFlusso">

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