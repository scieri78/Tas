<form id="FormSh">
  <div id="FormModFlusso" class="dip_FL">
    <input type="hidden" name="ID_SH" id="ID_SH" value="<?php echo $ID_SH; ?>">
    <table class="TabDip">
      <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">Shell Path</label></td>
        <td class="TdDip"><input type="text" id="SHELL_PATH" Name="SHELL_PATH" style="width: 100%;" class="ModificaField" value="<?php echo $SHELL_PATH; ?>" /></td>
      </tr>
      <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">Shell Name</label></td>
        <td class="TdDip"><input type="text" id="SHELL" Name="SHELL" style="width: 100%;" class="ModificaField" value="<?php echo $SHELL; ?>" /></td>
        </tr>
      <tr>
        <td class="TdDip"><label style="width:300px;text-align:left;">Parall</label></td>
        <td class="TdDip">
          <select id="PARALL" name="PARALL" style="width: 100%;" class="ModificaField">
            <option value="Y" <?php echo ($PARALL == 'Y') ? 'selected' : ''; ?>>Sì</option>
            <option value="N" <?php echo ($PARALL == 'N') ? 'selected' : ''; ?>>No</option>
          </select>
        </td>
      </tr>
      <td>
        <button id="PulMod" onclick="modificaSh();return false;" class="btn AggiungiFlusso">
          <i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
      </td>
      </tr>
    </table>
  </div>