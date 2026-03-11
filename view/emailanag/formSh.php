<form id="FormSh">
  <div id="FormModFlusso" class="dip_FL">
    <input type="hidden" name="ID_MAIL_ANAG" id="ID_MAIL_ANAG" value="<?php echo $ID_MAIL_ANAG; ?>">
    <table class="TabDip">
      <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">USERNAME</label></td>
        <td class="TdDip"><input type="text" id="USERNAME" Name="USERNAME" style="width: 100%;" class="ModificaField" value="<?php echo $USERNAME; ?>" /></td>
      </tr>
        <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">NAME</label></td>
        <td class="TdDip"><input type="text" id="NAME" Name="NAME" style="width: 100%;" class="ModificaField" value="<?php echo $NAME; ?>" /></td>
      </tr>
      <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">MAIL</label></td>
        <td class="TdDip"><input type="text" id="MAIL" Name="MAIL" style="width: 100%;" class="ModificaField" value="<?php echo $MAIL; ?>" /></td>
      </tr>     
      <td>
        <button id="PulMod" onclick="modificaSh();return false;" class="btn AggiungiFlusso">
          <i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
      </td>
      </tr>
    </table>
  </div>