<?php
/**
 * View - Array SQL
 * Lista delle query SQL eseguite
 */
?>

<table class="array-sql-table">
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
                        <th>Pos</th><td><?php echo $pos; ?></td>
                        <th>ID Run SQL</th><td><?php echo $idRunSql; ?></td>
                        <th>Step</th><td><?php echo $step; ?></td>
                        <th>Type Run</th><td><?php echo $typeRun; ?></td>
                        <th>File SQL</th><td><?php echo $fileSql; ?></td>
                        <th>Start<br>End</th><td><?php echo $startTime . '<br/>' . $endTime; ?></td>
                        <th>Durata</th><td><?php echo $durata; ?></td>
                        <th>Status</th><td><span class="badge badge-status"><?php echo $status; ?></span></td>
                        <th>Azioni</th><td><button class="btn-small" onclick="viewFile('<?php echo htmlspecialchars($fileSql); ?>')">View</button></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='20'>Nessuna query SQL</td></tr>";
            }
        ?>
    </tbody>
</table>