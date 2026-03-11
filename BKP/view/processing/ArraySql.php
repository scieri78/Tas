<?php
/**
 * View - Array SQL
 * Lista delle query SQL eseguite
 */
?>

<table class="array-sql-table">
    <thead>
        <tr>
            <th>Pos</th>
            <th>ID RUN SQL</th>
            <th>Step</th>
            <th>Type Run</th>
            <th>File SQL</th>
            <th>Data Inizio</th>
            <th>Data Fine</th>
            <th>Durata (sec)</th>
            <th>Status</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($ArraySql) && count($ArraySql) > 0) {
                foreach ($ArraySql as $row) {
                    $pos = $row['POS'];
                    $idRunSql = $row['ID_RUN_SQL'];
                    $step = $row['STEP'];
                    $typeRun = $row['TYPE_RUN'];
                    $fileSql = $row['FILE_SQL'];
                    $startTime = $row['START_TIME'];
                    $endTime = $row['END_TIME'];
                    $status = $row['STATUS'];
                    
                    // Calcola la durata
                    $durata = 'N/A';
                    if ($startTime && $endTime) {
                        $start = new DateTime($startTime);
                        $end = new DateTime($endTime);
                        $durata = $end->getTimestamp() - $start->getTimestamp();
                    }
                    ?>
                    <tr class="array-sql-row">
                        <td><?php echo $pos; ?></td>
                        <td><?php echo $idRunSql; ?></td>
                        <td><?php echo $step; ?></td>
                        <td><?php echo $typeRun; ?></td>
                        <td><?php echo $fileSql; ?></td>
                        <td><?php echo $startTime; ?></td>
                        <td><?php echo $endTime; ?></td>
                        <td><?php echo $durata; ?></td>
                        <td><span class="badge badge-status"><?php echo $status; ?></span></td>
                        <td>
                            <button class="btn-small" onclick="viewFile('<?php echo htmlspecialchars($fileSql); ?>')">View</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>Nessuna query SQL</td></tr>";
            }
        ?>
    </tbody>
</table>