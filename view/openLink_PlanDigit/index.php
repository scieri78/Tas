<table id="tabSel" class="openLinkTable">
  <caption>Selezionare Wave per il caricamento</caption>
  <tr>
    <th>Tipo Plan</th>
    <td>
      <?php if ($legameValido || $RdOnly==1): ?>
        <span><?php echo $ConfTipo; ?></span>
      <?php else: ?>
        <select id="Tipo" name="SelTipo">
          <option value="PD">PD</option>
        </select>
      <?php endif; ?>
      <input type="Hidden" name="oldTipo" value="<?php echo $ConfTipo; ?>">
    </td>
  </tr>
  <tr>
    <th>Wave</th>
    <td>
      <?php if ($legameValido || $RdOnly==1): ?>
        <span><?php echo $ConfWave; ?></span>
      <?php else: ?>
        <select id="Wave" name="SelWave">
          <option value="New">New</option>
          <?php foreach ($datiPalnDigitWave as $row): ?>
            <?php
            $TabWave = $row['WAVE'];
            $TabDesc = $row['DESCR'];
            $Vdesc = $TabDesc ? $TabWave . " - " . $TabDesc : $TabWave;
            ?>
            <option value="<?php echo $TabWave; ?>" <?php if ($TabWave == $ConfWave) { ?>Selected<?php } ?>><?php echo $Vdesc; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
      <input type="Hidden" name="oldWave" value="<?php echo $ConfWave; ?>">
      <input type="Hidden" name="oldDesc" value="<?php echo $ConfDesc; ?>">
    </td>
  </tr>
  <tr>
    <th>Descrizione</th>
    <td>
      <?php if ($legameValido  || $RdOnly==1): ?>
        <span><?php echo $ConfDesc; ?></span>
      <?php else: ?>
        <textarea rows="5" cols="50" id="Desc" name="Desc"><?php echo $ConfDesc; ?></textarea>
      <?php endif; ?>
    </td>
  </tr>
</table>
<center>La configurazione verrà salvata durante il lancio di creazione della PLAN DIGIT</center>
<br/>
<?php if (!$legameValido  && $RdOnly!=1): ?>
  <button onclick="ActionF('SalvaPlanDigit', 'Salvare Plan Digit');return false;" id="SalvaChange" class="btn openLinkBtn"><i class="fa-solid fa-save"> </i> Salva</button>
<?php endif; ?>
