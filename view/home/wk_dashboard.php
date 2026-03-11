<p>
<button id="refresh" onclick="refreshHome();return false;" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
</p>
<input type="hidden" name="flag_consolidato" id="flag_consolidato" value="<?= $flag_consolidato ?>">
<input type="hidden" name="id_workflow" id="id_workflow" value="<?= $id_workflow ?>">
<input type="hidden" name="workflow" id="workflow" value="<?= $workflow ?>">

<table id='WFTabella' class="display dataTable">
    <thead class="headerStyle">
        <tr>
            <th>ID Processo</th>
            <th>Flussi</th>
            <th>In Esecuzione</th>
            <th>Eseguiti</th>
            <th>Da Eseguire</th>
            <th>Incompleti</th>
            <th>Utility</th>
            <th>In Errore</th>
            <th>Completamento</th> <!-- Nuova colonna per la percentuale di completamento -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($workflowFlussi as $workflow): ?>
            <?php
                if($workflow['NFlussi']>0 || 1):
                
                // Calcolare la percentuale di completamento
                $totalFlussi = $workflow['NFlussi'];
                $completedFlussi = $workflow['Eseguito'] + $workflow['Utility'];
                $completionPercentage = $totalFlussi? ($completedFlussi / $totalFlussi) * 100:0;

                // Determinare la classe CSS per il riempimento in base alla percentuale di completamento
                if ($completionPercentage >= 90) {
                    $fillClass = 'fill-green';
                } elseif ($completionPercentage >= 70) {
                    $fillClass = 'fill-greenyellow';
                }  elseif ($completionPercentage >= 30) {
                    $fillClass = 'fill-orange';
                } else {
                    $fillClass = 'fill-yellow';
                }

                // Determinare il colore di sfondo della barra in base allo stato di errore
                $backgroundColor = $workflow['InErrore'] > 0 ? 'lightcoral' : 'whitesmoke';
                $completionClass = $workflow['InErrore'] > 0 ? 'completion-white' : 'completion-text';
                $completionClass =  $fillClass == 'fill-greenyellow' ? 'completion-text' : $completionClass;
                $completionClass =  $fillClass == 'fill-green' ? 'completion-white' : $completionClass;
            ?>
            <tr>
                <td>
                    <a href="#" onclick="inviaDatiEApriPagina(<?= $workflow['IdWorkflow'] ?>,<?= $workflow['IdProcess'] ?>,<?= $workflow['IdPeriod'] ?>);return false">
                        <?php echo htmlspecialchars($workflow['IdProcess']); ?>
                    </a>
                </td>
                <td><?php echo $workflow['NFlussi']; ?></td>
                <td><?php echo $workflow['InEsecuzione']; ?></td>
                <td><?php echo $workflow['Eseguito']; ?></td>
                <td><?php echo $workflow['DaEseguire']; ?></td>
                <td><?php echo $workflow['Incompleto']; ?></td>
                <td><?php echo $workflow['Utility']; ?></td>
                <td><?php echo $workflow['InErrore']; ?></td>
                <td>
                    <div class="completion-bar" style="background-color: <?php echo $backgroundColor; ?>;">
                        <div class="completion-fill <?php echo $fillClass; ?>" style="width: <?php echo $completionPercentage; ?>%;"></div>
                        <div class="<?= $completionClass ?>">
                            <?php echo round($completionPercentage, 2) . '%'; ?>
                        </div>
                    </div>
                </td>
            </tr>
        <?php 
        endif;
        endforeach; ?>
    </tbody>
</table>