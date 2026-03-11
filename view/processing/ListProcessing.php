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

                    // Calcola il colore dello status
                    $statusClass = '';
                    switch ($status) {
                        case 'F':
                            $statusClass = 'status-success';
                            $statusText = 'FINE';
                            break;
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
                    <tr class="processing-row" onclick="openDetail(<?php echo $idRunSh; ?>)" style="cursor:pointer;">
                        <td><?php echo $idSh; ?></td>
                        <td><?php echo $idRunSh; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $startTime; ?></td>
                        <td><?php echo $endTime; ?></td>
                        <td><span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $rc; ?></td>
                        <td><?php echo $tags; ?></td>
                        <td>
                            <button type="button" class="btn-detail" onclick="openDetail(<?php echo $idRunSh; ?>); event.stopPropagation();">Dettagli</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>Nessun dato disponibile</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function openDetail(idRunSh) {
    // Invia il form con l'ID selezionato
    var form = document.getElementById('formProcessing');
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'idRunSh';
    input.value = idRunSh;
    form.appendChild(input);

    var actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'detail';
    form.appendChild(actionInput);

    form.submit();
}
</script>
