<?php
$Salva = $_POST['Salva'];
$oldTipo = $_POST['oldTipo'];
$SelTipo = $_POST['SelTipo'];
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
      <td>Tipo Plan</td>
      <td>
        <select id="Tipo" name="SelTipo">
          <option value="PD">PD</option>
        </select>
        <input type="Hidden" name="oldTipo" value="<?php echo $oldTipo; ?>">
      </td>
    </tr>
    <tr>
      <td>Wave</td>
      <td>
        <select id="Wave" name="SelWave">
          <option value="New">New</option>
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
  <button onclick="ActionF('SalvaPlanDigit', 'Salvare Plan Digit');return false;" id="SalvaChange" class="btn"><i class="fa-solid fa-save"> </i> Salva</button>


</CENTER>