<?php
/**
 * View - Lista Processing
 * Tabella principale dei processing padre
 */

?>

<div class="processing-list-container">
    <h3>Lista Processing</h3>
    
    <table class="processing-table">
        <thead>
            <tr>
                <th>ID SH</th>
                <th>ID RUN SH</th>
                <th>Nome</th>
                <th>Data Inizio</th>
                <th>Data Fine</th>
                <th>Status</th>
                <th>Utente</th>
                <th>RC</th>
                <th>Tags</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (is_array($DatiListProcessing) && count($DatiListProcessing) > 0) {
                    foreach ($DatiListProcessing as $row) {
                        $idSh = $row['ID_SH'];
                        $idRunSh = $row['ID_RUN_SH'];
                        $name = $row['NAME'];
                        $startTime = $row['START_TIME'];
                        $endTime = $row['END_TIME'];
                        $status = $row['STATUS'];
                        $username = $row['USERNAME'];
                        $rc = $row['RC'];
                        $tags = $row['TAGS'];
                        
                        // Calcola il colore dello status\n                        $statusClass = '';
                        switch ($status) {
                            case 'F':
                                $statusClass = 'status-success';
                                $statusText = 'FINE';\n                                break;
                            case 'E':
                                $statusClass = 'status-error';
                                $statusText = 'ERRORE';
                                break;
                            case 'R':
                                $statusClass = 'status-running';
                                $statusText = 'IN ESECUZIONE';
                                break;
                            case 'M':
                                $statusClass = 'status-manual';
                                $statusText = 'MANUALE';
                                break;
                            default:
                                $statusClass = 'status-pending';
                                $statusText = $status;
                                break;
                        }
                        ?>
                        <tr class="processing-row" onclick=\"openDetail(<?php echo $idRunSh; ?>)\" style=\"cursor:pointer;\">\n                            <td><?php echo $idSh; ?></td>\n                            <td><?php echo $idRunSh; ?></td>\n                            <td><?php echo $name; ?></td>\n                            <td><?php echo $startTime; ?></td>\n                            <td><?php echo $endTime; ?></td>\n                            <td><span class=\"<?php echo $statusClass; ?>\"><?php echo $statusText; ?></span></td>\n                            <td><?php echo $username; ?></td>\n                            <td><?php echo $rc; ?></td>\n                            <td><?php echo $tags; ?></td>\n                            <td>\n                                <button type=\"button\" class=\"btn-detail\" onclick=\"openDetail(<?php echo $idRunSh; ?>); event.stopPropagation();\">Dettagli</button>\n                            </td>\n                        </tr>\n                        <?php\n                    }\n                } else {\n                    echo \"<tr><td colspan='10'>Nessun dato disponibile</td></tr>\";\n                }\n            ?>\n        </tbody>\n    </table>\n</div>\n\n<script>\nfunction openDetail(idRunSh) {\n    // Invia il form con l'ID selezionato\n    var form = document.getElementById('formProcessing');\n    var input = document.createElement('input');\n    input.type = 'hidden';\n    input.name = 'idRunSh';\n    input.value = idRunSh;\n    form.appendChild(input);\n    \n    var actionInput = document.createElement('input');\n    actionInput.type = 'hidden';\n    actionInput.name = 'action';\n    actionInput.value = 'detail';\n    form.appendChild(actionInput);\n    \n    form.submit();\n}\n</script>\n"}}]
