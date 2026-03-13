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
                    $idRunSh = isset($row['ID_RUN_SH']) ? $row['ID_RUN_SH'] : '';
                    $idRunSql = isset($row['ID_RUN_SQL']) ? $row['ID_RUN_SQL'] : '';
                    $step = isset($row['STEP']) ? $row['STEP'] : '';
                    $typeRun = isset($row['TYPE_RUN']) ? $row['TYPE_RUN'] : '';
                    $fileSql = isset($row['FILE_SQL']) ? $row['FILE_SQL'] : '';
                    $fileIn = isset($row['FILE_IN']) ? $row['FILE_IN'] : '';
                    $startTime = isset($row['START_TIME']) ? $row['START_TIME'] : '';
                    $endTime = isset($row['END_TIME']) ? $row['END_TIME'] : '';
                    $status = isset($row['STATUS']) ? $row['STATUS'] : '';

                    $stepFormatted = str_replace('.', '. ', $step);

                    $statusClass = 'status-pending';
                    switch ($status) {
                        case 'F':
                            $statusClass = 'status-success';
                            break;
                        case 'E':
                            $statusClass = 'status-error';
                            break;
                        case 'R':
                        case 'I':
                            $statusClass = 'status-running';
                            break;
                        case 'M':
                            $statusClass = 'status-manual';
                            break;
                        case 'W':
                            $statusClass = 'status-warning';
                            break;
                    }

                    $durata = '-';
                    if (!empty($startTime) && !empty($endTime)) {
                        try {
                            $start = new DateTime($startTime);
                            $end = new DateTime($endTime);
                            $seconds = $end->getTimestamp() - $start->getTimestamp();
                            if ($seconds < 0) {
                                $seconds = 0;
                            }
                            $durata = gmdate('H:i:s', $seconds);
                        } catch (Exception $e) {
                            $durata = '-';
                        }
                    }

                    $isAnonymous = ($fileSql === 'Anonymous Block' || $fileSql === 'SQL DB2 Block');
                    $jsStepTitle = htmlspecialchars(json_encode($stepFormatted), ENT_QUOTES, 'UTF-8');
                    $jsFileSql = htmlspecialchars(json_encode($fileSql), ENT_QUOTES, 'UTF-8');

                    $iconAction = '';
                    if ($typeRun === 'PLSQL') {
                      //  $iconAction .= '<img src="./images/LogDb.png" onclick="if (typeof openTabLog === \'function\') { openTabLog(' . (int) $idRunSql . '); }" class="IconSql" title="LogDb">';
                    }
                    if (!$isAnonymous) {
                        $iconAction .= '<img src="./images/File.png" onclick="openSqlFile(' . (int) $idRunSql . ', ' . $jsFileSql . ')" class="IconSql" title="File SQL">';
                        $iconAction .= '<img src="./images/PlsqlTab.png" onclick="openRelTab(\'\', ' . (int) $idRunSh . ', ' . (int) $idRunSql . ', ' . $jsStepTitle . ')" class="IconSql" title="Relazioni">';
                    }
                    ?>
                    <tr class="array-sql-row">
                        <th class="status <?php echo $statusClass; ?>" title="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>"></th>
                        <td><strong><?php echo htmlspecialchars($stepFormatted, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td class="col-actions"><?php echo $iconAction; ?></td>
                        <th>Type</th><td><?php echo htmlspecialchars($typeRun, ENT_QUOTES, 'UTF-8'); ?></td>
                        <th>Sql</th>
                        <td <?php if (!$isAnonymous) { ?>onclick="openSqlFile(<?php echo (int) $idRunSql; ?>, <?php echo $jsFileSql; ?>)" style="cursor:pointer;"<?php } ?>>
                            <?php echo htmlspecialchars($fileSql, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <th>Start</th><td><?php echo htmlspecialchars($startTime, ENT_QUOTES, 'UTF-8'); ?></td>
                        <th>End</th><td><?php echo htmlspecialchars($endTime, ENT_QUOTES, 'UTF-8'); ?></td>
                        <th>Time</th><td><?php echo htmlspecialchars($durata, ENT_QUOTES, 'UTF-8'); ?></td>
                    <!--    <th>File</th><td><?php echo htmlspecialchars($fileIn, ENT_QUOTES, 'UTF-8'); ?></td>-->
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='15'>Nessuna query SQL</td></tr>";
            }
        ?>
    </tbody>
</table>