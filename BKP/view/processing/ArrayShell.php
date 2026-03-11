<?php
/**
 * View - Array Shell Figli
 * Lista dei shell figli con dettagli e confronto con lancio precedente
 */
?>

<table class="array-shell-table">
    <thead>
        <tr>
            <th>Pos</th>
            <th>ID SH</th>
            <th>Nome</th>
            <th>Data Inizio</th>
            <th>Data Fine</th>
            <th>Durata (sec)</th>
            <th>Status</th>
            <th>Utente</th>
            <th>RC</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($ArrayShell) && count($ArrayShell) > 0) {
                foreach ($ArrayShell as $row) {
                    $pos = $row['POS'];
                    $idSh = $row['ID_SH'];
                    $name = $row['NAME'];
                    $startTime = $row['START_TIME'];
                    $endTime = $row['END_TIME'];
                    $status = $row['STATUS'];
                    $username = $row['USERNAME'];
                    $rc = $row['RC'];
                    $tags = $row['TAGS'];
                    $log = $row['LOG'];
                    
                    // Calcola la durata
                    $durata = 'N/A';
                    if ($startTime && $endTime) {
                        $start = new DateTime($startTime);
                        $end = new DateTime($endTime);
                        $durata = $end->getTimestamp() - $start->getTimestamp();
                    }
                    ?>
                    <tr class="array-shell-row">
                        <td><?php echo $pos; ?></td>
                        <td><?php echo $idSh; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $startTime; ?></td>
                        <td><?php echo $endTime; ?></td>
                        <td><?php echo $durata; ?></td>
                        <td><span class="badge badge-status"><?php echo $status; ?></span></td>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $rc; ?></td>
                        <td>
                            <button class="btn-small" onclick="showLog('<?php echo htmlspecialchars($log); ?>')">Log</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>Nessuno shell figlio</td></tr>";
            }
        ?>
    </tbody>
</table>
