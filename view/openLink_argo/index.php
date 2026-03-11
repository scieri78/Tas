<table id="tabSel" class="openLinkTable">
  <tr>
    <td>BestEstimates</td>
    <td>
      <?php if ($legameValido  || $RdOnly==1): ?>
        <?php
        // Trova la descrizione corrispondente al valore di $id_rule
        $descrizione = '';
        foreach ($DataBestEstimates as $row) {
          if ($id_rule == $row['VALUE']) {
            $descrizione = $row['NAME_RULE'];
            break;
          }
        }
        ?>
        <span><?= $descrizione; ?></span>
      <?php else: ?>
        <select id="IdRule" name="IdRule">
          <option value="">..</option>
          <?php foreach ($DataBestEstimates as $row): ?>
            <option value="<?= $row['VALUE']; ?>" <?= ($id_rule == $row['VALUE']) ? 'selected' : ''; ?>><?= $row['NAME_RULE']; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>RiskRule</td>
    <td>
      <?php if ($legameValido  || $RdOnly==1): ?>
        <?php
        // Trova la descrizione corrispondente al valore di $id_rule_risk
        $descrizione_risk = '';
        foreach ($DataRiskAdjustament as $row) {
          if ($id_rule_risk == $row['VALUE']) {
            $descrizione_risk = $row['NAME_RULE'];
            break;
          }
        }
        ?>
        <span><?= $descrizione_risk; ?></span>
      <?php else: ?>
        <select id="IdRiskRule" name="IdRiskRule">
          <option value="">..</option>
          <?php foreach ($DataRiskAdjustament as $row): ?>
            <option value="<?= $row['VALUE']; ?>" <?= ($id_rule_risk == $row['VALUE']) ? 'selected' : ''; ?>><?= $row['NAME_RULE']; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </td>
  </tr>
</table>
<?php if (!$legameValido  && $RdOnly!=1): ?>
  <button onclick="xsaveArgo();return false;" id="saveArgo" class="openLinkBtn btn"><i class="fa-solid fa-save"> </i> Salva</button>
<?php endif; ?>


