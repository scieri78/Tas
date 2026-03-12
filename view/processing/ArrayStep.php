<?php
/**
 * View - Array Step
 * Lista degli step di elaborazione
 */
?>

<table class="array-step-table">
    <tbody>
        <?php
            if (is_array($ArrayStep) && count($ArrayStep) > 0) {
                foreach ($ArrayStep as $row) {
                    $step = isset($row['STEP']) ? $row['STEP'] : '';
                    $time = isset($row['TIME']) ? $row['TIME'] : '';
                    ?>
                    <tr class="array-step-row">
                        <th class="array-step-marker"></th>
                        <th>Eseguo Shell in WORK :</th><td class="array-step-desc"><?php echo htmlspecialchars($step, ENT_QUOTES, 'UTF-8'); ?></td>
                        <th>TimeStamp</th><td class="array-step-time"><?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>Nessuno step disponibile</td></tr>";
            }
        ?>
    </tbody>
</table>
