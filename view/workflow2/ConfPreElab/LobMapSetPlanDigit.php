<?php
$Salva = $_POST['Salva'];
$oldWave = $_POST['oldWave'];
$SelWave = $_POST['SelWave'];

foreach ($datiParametriIdProcess as $row) {
  $TabCampo = $row['CAMPO'];
  $TabValore = $row['VALORE'];
  ${'Conf' . $TabCampo} = $TabValore;
}
?>
<CENTER>
  <table id="tabSel" class="ExcelTable">
    <tr>
      <td>Wave</td>
      <td>
        <select id="Wave" name="SelWave">
          <?php          
          foreach ($datiPalnDigitWave as $row) {
            $TabWave = $row['WAVE'];
          ?><option value="<?php echo $TabWave; ?>" <?php if ($TabWave == $ConfWave) { ?>Selected<?php } ?>><?php echo $TabWave; ?></option><?php
          }
        ?>
        </select>
        <input type="Hidden" name="oldWave" value="<?php echo $oldWave; ?>">
      </td>
    </tr>
  </table>
  <button onclick="ActionF('SetLobMapPlanDigit', 'Set LobMap Plan Digit');return false;" id="SalvaChange" class="btn"><i class="fa-solid fa-save"> </i> Salva</button>


</CENTER>