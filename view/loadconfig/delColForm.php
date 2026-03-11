<form id="FormDelFileCol">
    <input type="hidden" name="I_ID_LOAD_ANAG" id="I_ID_LOAD_ANAG" value="<?php echo $I_ID_LOAD_ANAG; ?>">
    <table class="TabDip">

        <tr>
            <td class="TdDip"><label style="width:200px;text-align:left;">NOME COLONNA DA ELIMINARE</label></td>
            <td class="TdDip">
                <input type="text" id="I_COLNAME" Name="I_COLNAME" class="ModificaField">
            </td>
        </tr>
      
        <tr>

            <td>
                <button id="PulMod" onclick="DelFileCol();return false;" class="btn AggiungiFlusso"><i class="fa-solid fa-floppy-disk"> </i> Salva</button>
            </td>
        </tr>
    </table>
    </div>
</form>