<form id="FormControllo" method="POST">
    <div id="FormModFlusso" class="dip_F">
        <input type="hidden" name="ID_CONTR" id="ID_CONTR" value="<?php echo $ID_CONTR; ?>">
        <input type="hidden" name="ID_LANCIO" id="ID_LANCIO" value="<?php echo $ID_LANCIO; ?>">
        <input type="hidden" name="ID_GRUPPO" id="ID_GRUPPO" value="<?php echo $ID_GRUPPO; ?>">
        <input type="hidden" name="ID_INPORT" id="ID_INPORT" value="<?php echo $ID_INPORT; ?>">



        <div><label form="SOTTO_GRUPPO">SOTTO_GRUPPO</label></th>
        </div>
        <div>
            <input type="text" id="SOTTO_GRUPPO" Name="SOTTO_GRUPPO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['SOTTO_GRUPPO']; ?>">
        </div>

        <div><label form="CONTROLLO">CONTROLLO</label></div>
        <div>
            <input type="text" id="CONTROLLO" Name="CONTROLLO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['CONTROLLO']; ?>">
        </div>

        <div><label form="TIPO">TIPO</label></div>
        <div>
            <input type="text" id="TIPO" Name="TIPO" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['TIPO']; ?>">
        </div>

        <div><label form="DESCR">DESCRIZIONE</label></div>
        <div>
            <input type="text" id="DESCR" Name="DESCR" style="width: 100%;" class="ModificaField" value="<?php echo $controlli['DESCR']; ?>">
        </div>

        <div><label form="CLASSE">CLASSE</label></div>
        <div>
            <SELECT id="CLASSE" Name="CLASSE" class="ModificaField">
                <?php
                foreach ($array_classe as $k => $val) {
                ?><option value="<?php echo $k; ?>" <?php if ($k ==  $controlli['CLASSE']) { ?>selected<?php } ?>><?php echo $val; ?></option><?php
                                                                                                                                        }
                                                                                                                                            ?>
            </SELECT>
        </div>

        <div><label form="TESTO">QUERY/TESTO</label></div>
        <div>
            <textarea id="TESTO" Name="TESTO" rows="3" cols="80"><?php echo trim($controlli['TESTO']); ?></textarea>
        </div>
        <div>
          <label form="ATTESO">VALORE ATTESO</label></div>
        <div>
        <div>
        <?php $ATTESO = $controlli['ATTESO']?$controlli['ATTESO']:0; ?>
            <input type="text" id="ATTESO" Name="ATTESO" style="width: 100%;" class="ModificaField" value="<?php echo $ATTESO; ?>">
        </div>
        <br>

        <div>
            <button id="PulMod" onclick="modificaDBControllo();return false;" class="btn AggiungiFlusso">
                <?php if ($ID_CONTR) { ?>
                    <i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
        <?php } else { ?>
            <i class="fa-solid fa-plus-circle"> </i> Aggiungi</button>
        <?php } ?>
        </div>

</form>


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

$("#SOTTO_GRUPPO").autocomplete({
  source: function(request, response) {
    var formData = {};
    formData['SOTTO_GRUPPO'] = $("#SOTTO_GRUPPO").val();
    formData['ID_GRUPPO'] = $("#ID_GRUPPO").val();
    formData['ID_INPORT'] = $("#ID_INPORT").val();
    console.log('controller=controlli&action=getsottogruppo"');
    console.log(formData);
    $.ajax({
      
      type: "POST",
      url: 'index.php?controller=controlli&action=getsottogruppo',
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