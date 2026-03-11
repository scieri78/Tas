<?php
/**
 * View - Processing List
 * Visualizza la lista di processing
 */

$_modelprocessing = $this->_model;
$datiprocessing = $this->_datiprocessing;

// Recupera i dati di processing
$DatiProcessing = $_modelprocessing->getDataProcessing($datiprocessing);

?>

<div class="processing-list">
    <h3>Lista Processing</h3>
    
    <table class="processing-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Data Inizio</th>
                <th>Data Fine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (is_array($DatiProcessing) && count($DatiProcessing) > 0) {
                    foreach ($DatiProcessing as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['NAME']; ?></td>
                            <td><?php echo $row['STATUS']; ?></td>
                            <td><?php echo $row['START_TIME']; ?></td>
                            <td><?php echo $row['END_TIME']; ?></td>
                            <td>
                                <!-- Azioni -->
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>Nessun dato disponibile</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>
