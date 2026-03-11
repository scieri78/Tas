<form id="FormAddFileCol">
    <input type="hidden" name="I_ID_LOAD_ANAG" id="I_ID_LOAD_ANAG" value="<?php echo $I_ID_LOAD_ANAG; ?>">
    <table class="TabDip">

        <tr>
            <td class="TdDip"><label style="width:200px;text-align:left;">PRECCOLNAME</label></td>
            <td class="TdDip">

                <select style="width: 300px;margin: 0 12px;" class="selectSearch" id="I_PRECCOLNAME" name="I_PRECCOLNAME">
                    <option selected value="">PRIMO CAMPO</option>
                    <?php
                    foreach ($DatiColums as $row) {
                        $NAME = $row['COLONNA'];

                        //if( ! in_array($IdMail ,$ListIdFind) ){
                    ?><option value="<?php echo $NAME; ?>"><?php echo $NAME; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="TdDip"><label style="width:200px;text-align:left;">NOME COLONNA DA AGGIUNGERE</label></td>
            <td class="TdDip">
                <input type="text" id="I_COLNAME" Name="I_COLNAME" class="ModificaField">
            </td>
        </tr>
        <tr>

            <td>
                <button id="PulMod" onclick="AddFileCol();return false;" class="btn AggiungiFlusso"><i class="fa-solid fa-floppy-disk"> </i> Salva</button>
            </td>
        </tr>
    </table>
    </div>
</form>

<script>
    $.ui.dialog.prototype._allowInteraction = function(e) {
        return true;
    };
    $('.selectSearch').select2({

    });
</script>