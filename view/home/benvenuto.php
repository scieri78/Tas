<div id="divieto">
    <p></p>
    <center>
        <h4>Benvenuto <b><?php echo $nomeUtente; ?></b></h4>
    </center>
    <p></p>
    <?php if (!$isAdmin): ?>
        <br>
        <center><img class="SfondoStat" src="./images/SfondoStat.jpg" alt="SfondoStat" height="auto" width="60%" style="border:1px solid;">
            <c /enter>
</div>

<?php else: ?>
    <?php
        // Simulazione dei dati per la dashboard
        $processingErrors = ['Errore 1', 'Warning 1', 'Errore 2'];
        /*
$workflowData = [
    ['name' => 'Workflow 1', 'total' => 50, 'successful' => 40, 'failed' => 10],
    ['name' => 'Workflow 2', 'total' => 30, 'successful' => 25, 'failed' => 5],
    ['name' => 'Workflow 3', 'total' => 20, 'successful' => 15, 'failed' => 5]
];

$tickets = [
    'assignedToMe' => [
        'aperto' => 2,
        'in_lavorazione' => 3,
        'richiesta_info' => 1,
        'test' => 2,
        'risolto' => 1
    ],
    'openedByMe' => [
        'aperto' => 1,
        'in_lavorazione' => 2,
        'richiesta_info' => 0,
        'test' => 1,
        'risolto' => 3
    ]
];*/

        // Simulazione di una variabile per determinare se l'utente è un amministratore
        //$isAdmin = true; // Cambia a false per testare il comportamento non amministratore
    ?>

 <?php if ($sito=="user"): ?>
    <div class="dashboard">
        <form id="WFForm" action="index.php?sito=user&controller=workflow2&action=index" method="post" style="display:none;" target="_blank">
            <input type="hidden" id="IdWorkFlow" name="IdWorkFlow" value="1">
            <input type="hidden" id="IdProcess" name="IdProcess" value="">
            <input type="hidden" id="IdPeriod" name="IdPeriod" value="">
            <input type="hidden" id="Resetta" name="Resetta" value="">


        </form>

        <div class="workflow">
            <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
                <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WorkFlow</p>
                <div class="asideContent">
                    <table id='WFTabella' class="display dataTable">
                        <thead class="headerStyle">
                            <tr>
                                <th>Workflow</th>
                                <th>Consolidati</th>
                                <th>Aperti</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workflowData as $workflow): ?>
                                <tr>
                                    <td> <?php echo htmlspecialchars($workflow['name']); ?></td>
                                    <td><?php if ($workflow['consolidati']): ?><a href="#" onclick="executeWorkflow(<?= $workflow['id'] ?>,1,'<?= $workflow['name'] ?>');return false"><?= $workflow['consolidati'] ?></a>
                                            <?php else: ?>0<?php endif; ?>
                                    </td>
                                    <td><?php if ($workflow['aperti']): ?><a href="#" onclick="executeWorkflow(<?= $workflow['id'] ?>,0,'<?= $workflow['name'] ?>');return false"><?= $workflow['aperti'] ?></a>
                                            <?php else: ?>0<?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </aside>


        </div>
    <?php endif;?>

        <form id="FormProcessing" action="index.php?sito=<?= $sito ?>&controller=statoshell&action=index" method="post" style="display:none;" target="_blank">
            <input type="hidden" id="SelInDate" name="SelInDate" value="99">
            <input type="hidden" id="Sel_Esito" name="Sel_Esito" value="E">
            <input type="hidden" id="NumLast" name="NumLast" value="0">
        </form>
        
        <div class="processing">
             <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
        <p class="starlight-aside__title" aria-hidden="true">
            <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Processing
        </p>
        <div class="asideContent">
            <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
         <table id='WFTabella' class="display dataTable">
    <thead class="headerStyle">
        <tr>
            <th>Period</th>
            <th>Status</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($StatusCounts as $row): ?>
            <?php
                // Determina la classe CSS in base al periodo
                $class = '';
                switch ($row['PERIOD']) {
                    case 'Last 3 Days':
                        $class = 'last-3-days';
                        break;
                    case 'Last 6 Months':
                        $class = 'last-6-months';
                        break;
                    case '2025':
                        $class = 'year-2025';
                        break;
                    case '2024':
                        $class = 'year-2024';
                        break;
                }
            ?>
            <tr class="<?= $class ?>">
                <td><?= htmlspecialchars($row['PERIOD']) ?></td>
                <td><?= htmlspecialchars($row['STATUS_DESCRIPTION']) ?></td>
                <?php if ($row['PERIOD'] == 'Last 3 Days'): ?>
                    <td>
                        <a href="#" onclick="openProcessing('<?= htmlspecialchars($row['STATUS']) ?>')">
                            <?= htmlspecialchars($row['COUNT']) ?>
                        </a>
                    </td>
                <?php else: ?>
                    <td><?= htmlspecialchars($row['COUNT']) ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($row['PERCENTAGE']) ?>%</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
    </aside>
        </div>
         <?php if ($sito=="user"): ?>
        <div class="ticket">
            <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
                <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Ticket</p>
                <div class="asideContent">
                   <table id='TTTabella' class="display dataTable">
                <thead class="headerStyle">
                    <tr>
                        <th>Stato</th>
                        <?php if ($isAdmin): ?>
                            <th>Assegnati a Me</th>
                        <?php endif; ?>
                        <th>Aperti da Me</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets['openedByMe'] as $status => $count): ?>
                        <tr>
                            <td><?php echo ucfirst(str_replace('_', ' ', $status)); ?></td>
                            <?php if ($isAdmin): ?>
                                <td>
                                    <?php $countToMe = $tickets['assignedToMe'][$status]; ?>
                                    <?= $countToMe ? '<a href="index.php?sito=user&amp;controller=tickets&amp;action=index">' . $countToMe . '</a>' : 0 ?>
                                </td>
                            <?php endif; ?>
                            <td>
                                <?= $count ? '<a href="index.php?sito=user&amp;controller=tickets&amp;action=index">' . $count . '</a>' : 0 ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </aside>
        </div>
          <?php endif;?>
    </div>

<?php endif; ?>