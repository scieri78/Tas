    <?php
foreach ($datiParametriIdProcess as $row) {
  $TabCampo = $row['CAMPO'];
  $TabValore = $row['VALORE'];
  ${'Conf' . $TabCampo} = $TabValore;
}
?>
<table id="tabSel" class="openLinkTable">
  <caption>Selezionare Wave PLAN DIGIT per LOBMAP</caption>
  <tr>
    <th>Anno</th>
    <td>
      <?php if ($legameValido || $RdOnly==1): ?>
        <span><?= $ConfAnno; ?></span>
      <?php else: ?>
        <select id="SelAnno" name="SelAnno" onchange="getPlanDigitWave()">
          <option value=''>- Seleziona Anno -</option>
          <?php foreach ($datiEserEsame as $row): ?>
            <?php $VAL = $row['ESER_ESAME']; ?>
            <option value="<?= $VAL; ?>" <?= ($VAL == $ConfAnno) ? 'selected' : ''; ?>><?= $VAL; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <th>Wave</th>
    <td>
      <?php if ($legameValido || $RdOnly==1): ?>
        <span><?= $ConfWave; ?></span>
      <?php else: ?>
        <select id="SelWave" name="SelWave"></select>
      <?php endif; ?>
      <input type="Hidden" name="oldAnno" value="<?= $ConfAnno; ?>">
      <input type="Hidden" name="oldWave" value="<?= $ConfWave; ?>">
    </td>
  </tr>
</table>

<?php if (!$legameValido && $RdOnly!=1): ?>
  <button onclick="ActionF('SetLobMapPlanDigit', 'Set Plan Digit');return false;" id="SalvaChange" class="btn openLinkBtn"><i class="fa-solid fa-save"> </i> Salva</button>
<?php endif; ?>

<?php if ($ConfAnno): ?>
  <script>
    getPlanDigitWave('<?= $ConfWave; ?>')
  </script>
<?php endif; ?>
