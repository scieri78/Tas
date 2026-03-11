<?php
/**
 * View - Array Show
 * Vista unificata con timeline di tutti gli eventi
 */
?>

<table class="array-show-table">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Data/Ora</th>
            <th>Posizione</th>
            <th>Dettagli</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($ArrayShow) && count($ArrayShow) > 0) {
                foreach ($ArrayShow as $row) {
                    $tipo = $row['TIPO'];
                    $startTime = $row['START_TIME'];
                    $pos = $row['POS'];
                    
                    // Recupera il dettaglio in base al tipo
                    $dettaglio = '';
                    $arrayRef = null;
                    
                    switch ($tipo) {
                        case 'ARRAY_STEP':
                            $arrayRef = $ArrayStep;
                            break;
                        case 'ARRAY_SHELL':
                            $arrayRef = $ArrayShell;
                            break;
                        case 'ARRAY_SQL':
                            $arrayRef = $ArraySql;
                            break;
                    }
                    
                    // Estrae il record dalla posizione
                    if ($arrayRef && isset($arrayRef[$pos - 1])) {
                        $record = $arrayRef[$pos - 1];
                        
                        switch ($tipo) {
                            case 'ARRAY_STEP':
                                $dettaglio = "Step: " . $record['STEP'];
                                break;
                            case 'ARRAY_SHELL':
                                $dettaglio = "Shell: " . $record['NAME'] . " (Status: " . $record['STATUS'] . ")";
                                break;
                            case 'ARRAY_SQL':
                                $dettaglio = "SQL Step: " . $record['STEP'] . " (Type: " . $record['TYPE_RUN'] . ")";
                                break;
                        }
                    }
                    ?>
                    <tr class="array-show-row">
                        <td><span class="badge badge-<?php echo strtolower($tipo); ?>"><?php echo str_replace('ARRAY_', '', $tipo); ?></span></td>
                        <td><?php echo $startTime; ?></td>
                        <td><?php echo $pos; ?></td>
                        <td><?php echo $dettaglio; ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='4'>Nessun evento disponibile</td></tr>";
            }
        ?>
    </tbody>
</table>
"}}]
