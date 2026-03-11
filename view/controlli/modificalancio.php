<form id="FormControllo" method="POST">
    <div id="FormModFlusso" class="dip_F">
        <input type="hidden" name="ID_CONTR" id="ID_CONTR" value="<?php echo $ID_CONTR; ?>">
        <input type="hidden" name="ID_LANCIO" id="ID_LANCIO" value="<?php echo $ID_LANCIO; ?>">
        <input type="hidden" name="ID_GRUPPO" id="ID_GRUPPO" value="<?php echo $ID_GRUPPO; ?>">



        <div><label form="SOTTO_GRUPPO">SOTTO GRUPPO</label></th>
        </div>
        <div>
            <input disabled type="text" id="SOTTO_GRUPPO" Name="SOTTO_GRUPPO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['SOTTO_GRUPPO_ANAG']; ?>">
        </div>

        <div><label  form="CONTROLLO">CONTROLLO</label></div>
        <div>
            <input disabled type="text" id="CONTROLLO" Name="CONTROLLO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['CONTROLLO']; ?>">
        </div>

        <div><label form="TIPO">TIPO</label></div>
        <div>
            <input disabled type="text" id="TIPO" Name="TIPO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['TIPO']; ?>">
        </div>

        <div><label form="DESCR">DESCRIZIONE</label></div>
        <div>
            <input disabled type="text" id="DESCR" Name="DESCR" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['DESCR']; ?>">
        </div>

        <div><label form="CLASSE">CLASSE</label></div>
        <div>
            <SELECT disabled id="CLASSE" Name="CLASSE" class="ModificaField">
                <?php
                foreach ($array_classe as $k => $val) {
                ?><option value="<?php echo $k; ?>" <?php if ($k ==  $controlli['CLASSE']) { ?>selected<?php } ?>><?php echo $val; ?></option><?php
                                                                                                                                        }
                                                                                                                                            ?>
            </SELECT>
        </div>

        <div><label form="QUERY">QUERY/TESTO</label></div>
        <div>
            <textarea disabled id="QUERY" Name="QUERY" rows="3" cols="80"><?php echo trim($controlli['LANCIO']); ?></textarea>
        </div>


        <div><label form="FORZATURA">FORZATURA</label></div>
        <div>
            <input disabled type="text" id="FORZATURA" Name="FORZATURA" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['FORZATURA']; ?>">
        </div>

        <div>
          <label form="ATTESO">VALORE ATTESO</label></div>
        <div>
        <div>
        <?php $ATTESO = $controlli['ATTESO']?$controlli['ATTESO']:0; ?>
            <input disabled type="text" id="ATTESO" Name="ATTESO" style="width: 100%;" class="ModificaField" value="<?php echo $ATTESO; ?>">
        </div>


        <div><label form="ESITO">ESITO</label></div>
        <div>
            <SELECT id="ESITO" Name="ESITO" class="ModificaField">
                <?php
                 foreach ($array_esito as $k => $val) {
                ?><option value="<?php echo $k; ?>" <?php if ($k == $controlli['ESITO']) { ?>selected<?php } ?>><?php echo $val; ?></option><?php
                                                                                                                                        }
                                                                                                                                            ?>
            </SELECT>
        </div>

        <div><label form="RISULTATO">RISULTATO</label></div>
        <div>
            <input type="text" id="RISULTATO" Name="RISULTATO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['RISULTATO']; ?>">
        </div>

       

        <div><label form="NOTE">NOTE</label></div>
        <div>
            <textarea id="NOTE" Name="NOTE" rows="3" cols="80"><?php echo trim($controlli['NOTE']); ?></textarea>
        </div>
       
        <div>
           
                    <button id="PulMod" onclick="modificaDBLancio();return false;" class="btn AggiungiFlusso">
                    <i class="fa-solid fa-pencil-square-o"> </i> Modifica Lancio</button>
                    <button id="PulMod" onclick="modificaControllo(<?php echo $ID_CONTR; ?>);return false;" class="btn AggiungiFlusso">
                    <i class="fa-solid fa-pencil-square-o"> </i> Modifica Controllo</button>
       
        </div>

</form>

