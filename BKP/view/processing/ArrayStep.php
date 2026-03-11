<?php
/**
 * View - Array Step
 * Lista degli step di elaborazione
 */
?>

<table class="array-step-table">
    <thead>
        <tr>
            <th>Pos</th>
            <th>ID RUN SH</th>
            <th>Step</th>
            <th>Data/Ora</th>
            <th>Azioni</th>
        </tr>
    </thead>
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
                        <td><?php echo $pos; ?></td>
                        <td><?php echo $idRunSh; ?></td>
                        <td><?php echo $step; ?></td>
                        <td><?php echo $time; ?></td>
                        <td>
                            <button class="btn-small" onclick="alert('Step: <?php echo htmlspecialchars($step); ?>')">Info</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>Nessuno step disponibile</td></tr>";
            }
        ?>
    </tbody>
</table>
