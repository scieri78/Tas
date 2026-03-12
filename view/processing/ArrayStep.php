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
                    $pos = $row['POS'];
                    $idRunSh = $row['ID_RUN_SH'];
                    $step = $row['STEP'];
                    $time = $row['TIME'];
                    ?>
                    <tr class="array-step-row">
                        <th>Pos</th><td><?php echo $pos; ?></td>
                        <th>ID Run SH</th><td><?php echo $idRunSh; ?></td>
                        <th>Step</th><td><?php echo $step; ?></td>
                        <th>Data/Ora</th><td><?php echo $time; ?></td>
                        <th>Azioni</th><td><button class="btn-small" onclick="alert('Step: <?php echo htmlspecialchars($step); ?>')">Info</button></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>Nessuno step disponibile</td></tr>";
            }
        ?>
    </tbody>
</table>
