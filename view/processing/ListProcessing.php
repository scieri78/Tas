<?php
/**
 * View - Lista Processing
 * Tabella principale dei processing padre
 */
?>

<div class="processing-list-container">
    <!-- header removed to match image style -->

    <table class="processing-table">
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
                    $esame = isset($row['ESER_ESAME']) ? $row['ESER_ESAME'] : '';
                    $mese = isset($row['ESER_MESE']) ? $row['ESER_MESE'] : '';
                    $ambito = isset($row['ID_PROCESS']) ? $row['ID_PROCESS'] : '';

                    // calcola durata
                    $duration = '';
                    if (!empty($startTime) && !empty($endTime)) {
                        try {
                            $dt1 = new DateTime($startTime);
                            $dt2 = new DateTime($endTime);
                            $interval = $dt2->diff($dt1);
                            $duration = $interval->format('%H:%I:%S');
                        } catch (Exception $e) {
                            $duration = '';
                        }
                    }

                    ?>
                    <tr class="processing-row"   >
                        <td style="cursor:pointer;" onclick="openDetail(<?php echo $idRunSh; ?>)" class="col-rc">RC:<?php echo $rc; ?></td>
                        <th style="cursor:pointer;" onclick="openDetail(<?php echo $idRunSh; ?>)">Nome</th>                        
                        <td style="cursor:pointer;" onclick="openDetail(<?php echo $idRunSh; ?>)" class="col-name"><?php echo $name; ?></td>
                        <th></th><td class="col-actions">
                            <i class="fa fa-trash" title="Cancella"></i>
                            <i class="fa fa-folder-open" title="Apri"></i>
                            <i class="fa fa-clock-o" title="Orario"></i>
                            <i class="fa fa-file-text-o" title="Testo"></i>
                            <i class="fa fa-bar-chart" title="Grafico"></i>
                            <i class="fa fa-list-alt" title="Log"></i>
                            <i class="fa fa-file" title="File"></i>
                        </td>
                        <th>EserEsame<br/>
                        EserMese</th><td><?php echo $esame . "<br/>" . $mese; ?></td>

                       

                        <th>Tags</th><td class="col-tags"><?php echo $tags; ?></td>


                        <th>Start<br>End</th><td style="width: 205px;" class="col-start"><?php echo $startTime . "<br/>" . $endTime; ?></td>
                        <th>Time<br>OldTime</th><td class="col-duration"><?php echo $duration . "<br/>" . $oldTime; ?></td>
                        <th>User</th><td class="col-user"><?php echo $username; ?></td>
                        <th>Ambito</th><td class="col-ambito"><?php echo $ambito; ?></td>
                    </tr>
                    <tr class="detail-row" id="detail-row-<?php echo $idRunSh; ?>" style="display:none;">
                        <td colspan="22">
                            <div id="detailContent-<?php echo $idRunSh; ?>" class="detail-content"></div>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='11'>Nessun dato disponibile</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
