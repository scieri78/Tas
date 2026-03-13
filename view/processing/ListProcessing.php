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
                    $log = isset($row['LOG']) ? $row['LOG'] : '';
                    $status = $row['STATUS'];
                    $username = $row['USERNAME'];
                    $mail = isset($row['MAIL']) ? $row['MAIL'] : '';
                    $debugDb = isset($row['DEBUG_DB']) ? $row['DEBUG_DB'] : '';
                    $debugSh = isset($row['DEBUG_SH']) ? $row['DEBUG_SH'] : '';
                    $message = isset($row['MESSAGE']) ? $row['MESSAGE'] : '';
                    $rc = $row['RC'];
                    $tags = $row['TAGS'];
                    $esame = isset($row['ESER_ESAME']) ? $row['ESER_ESAME'] : '';
                    //pre il mese prendi solo le ultime 2 cifre per non appesantire la tabella
                    $mese = isset($row['ESER_MESE']) ? substr($row['ESER_MESE'], -2) : '';
                    $ambito = isset($row['AMBITO']) ? $row['AMBITO'] : '');

                    // determina classe colore status
                    $statusClass = '';
                    $statusText = '';
                    switch ($status) {
                        case 'F':
                            $statusClass = 'status-success';
                            $statusText = 'Finished';
                            break;
                        case 'E':
                            $statusClass = 'status-error';
                            $statusText = 'Error';
                            break;
                        case 'R':
                            $statusClass = 'status-running';
                            $statusText = 'Running';
                            break;
                        case 'M':
                            $statusClass = 'status-manual';
                            $statusText = 'Manual';
                            break;
                         case 'W':
                            $statusClass = 'status-warning';
                            $statusText = 'Warning';
                            break;
                        case 'I':
                            $statusClass = 'status-running';
                            $statusText = 'Interrupted';
                            break;
                        default:
                            $statusClass = 'status-pending';
                            $statusText = 'Pending';
                            break;
                    }

                    $jsFileTitle = htmlspecialchars(json_encode('File: ' . $name), ENT_QUOTES, 'UTF-8');
                    $jsLogTitle = htmlspecialchars(json_encode('Log: ' . $name), ENT_QUOTES, 'UTF-8');
                    $jsName = htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8');
                    $jsTags = htmlspecialchars(json_encode((string) $tags), ENT_QUOTES, 'UTF-8');

                    $iconAction = '';
                    if (!empty($idSh)) {
                        $iconAction .= '<img src="./images/File.png" class="IconFile" title="File" onclick="openDialog(' . (int) $idSh . ', ' . $jsFileTitle . ', \'apriFile\')">';
                    }
                    if (!empty($log)) {
                        $iconAction .= '<img src="./images/Log.png" class="IconSh" title="Log" onclick="openDialog(' . (int) $idRunSh . ', ' . $jsLogTitle . ', \'apriLog\')">';
                    }
                    if ($debugSh === 'Y') {
                        $iconAction .= '<img src="./images/DebugSh.png" title="DebugSh" class="IconDebug">';
                    }
                    if ($debugDb === 'Y') {
                        $iconAction .= '<img src="./images/DebugDb.png" title="DebugDb" class="IconDebug">';
                    }
                    $iconAction .= '<img src="./images/Graph.png" title="Grafico" onclick="openGrafici(' . $jsName . ', ' . $jsTags . ', ' . (int) $idSh . ')" class="IconSh">';
                    $iconAction .= '<img src="./images/PlsqlTab.png" title="Relazioni" onclick="openRelTab(' . (int) $idSh . ', ' . (int) $idRunSh . ', \'\', ' . $jsName . ')" class="IconSh">';
                    if ($mail === 'Y') {
                        $iconAction .= '<img src="./images/Mail.png" title="Mail" class="IconDebug">';
                    }

                    // calcola durata
                    $duration = '';
                    $oldTime = '';
                    $meter = '';
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
                    if (isset($row['OLD_TIME']) && $row['OLD_TIME'] !== '') {
                        $oldSeconds = (int) $row['OLD_TIME'];
                        if ($oldSeconds > 0) {
                            $oldTime = gmdate('H:i:s', $oldSeconds);
                        }
                    }
                    $meterHtml = '';
                    if (isset($row['METER']) && $row['METER'] !== '') {
                        $meterVal = (int) $row['METER'];
                        if ($meterVal < 110) {
                            $meterColor = 'meter-green';
                        } elseif ($meterVal < 120) {
                            $meterColor = 'meter-yellow';
                        } elseif ($meterVal <= 200) {
                            $meterColor = 'meter-orange';
                        } else {
                            $meterColor = 'meter-red';
                        }
                        $barWidth = min($meterVal, 200);
                        $meterHtml = '<div class="meter-bar ' . $meterColor . '" style="width:' . $barWidth . '%;">' . $meterVal . '%</div>';
                    }

                    ?> 
                    <tr class="processing-row">
                        <th class="status <?php echo $statusClass; ?>" title="<?php echo $statusText; ?>"></th>
                        <td style="cursor:pointer;" onclick="openDetail(<?php echo $idRunSh; ?>)" class="col-rc" title="<?php echo htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8'); ?>">RC:<?php echo $rc; ?></td>
                        <td style="cursor:pointer;" onclick="openDetail(<?php echo $idRunSh; ?>)" class="col-name"><?php echo $name; ?></td>
                        <td class="col-actions"><?php echo $iconAction; ?></td>
                        <th>EserEsame<br/>
                        EserMese</th><td><?php echo $esame . "<br/>" . $mese; ?></td>

                       

                        <th>Tags<br>Meter</th><td class="col-tags"><?php echo $tags . '<br/>' . $meterHtml; ?></td>


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

    <?php
    $currentPage = isset($pagination['page']) ? (int) $pagination['page'] : 1;
    $totalPages = isset($pagination['totalPages']) ? (int) $pagination['totalPages'] : 1;
    $pageSize = isset($pagination['pageSize']) ? (int) $pagination['pageSize'] : 10;
    $totalRows = isset($pagination['totalRows']) ? (int) $pagination['totalRows'] : 0;
    if ($totalPages > 1) {
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $startPage + 4);
        $startPage = max(1, $endPage - 4);
        ?>
        <div class="processing-pagination" style="margin-top:12px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
            <button type="button" class="btn" onclick="goToPage(<?php echo $currentPage - 1; ?>)" <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>>Prec</button>
            <?php for ($p = $startPage; $p <= $endPage; $p++) { ?>
                <button
                    type="button"
                    class="btn"
                    onclick="goToPage(<?php echo $p; ?>)"
                    <?php echo ($p === $currentPage) ? 'style="font-weight:700; border:1px solid #3f8bfd;"' : ''; ?>
                ><?php echo $p; ?></button>
            <?php } ?>
            <button type="button" class="btn" onclick="goToPage(<?php echo $currentPage + 1; ?>)" <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>>Succ</button>
            <span style="margin-left:8px;">Pagina <?php echo $currentPage; ?> / <?php echo $totalPages; ?> (<?php echo $totalRows; ?> record, <?php echo $pageSize; ?> per pagina)</span>
        </div>
    <?php } ?>
</div>
