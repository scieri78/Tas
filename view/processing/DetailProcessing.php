<?php
/**
 * View - Detail Processing
 * Visualizza i dettagli con i 4 array: Shell, SQL, Step e View unificata
 */
?>

<div class="detail-processing-container">
    <h3>Dettaglio Processing - ID RUN: <?php echo $idRunSh; ?></h3>
    
    <div class="detail-tabs">
        <ul class="tab-list">
            <li><a href="#tab-show" class="tab-link active" onclick="switchTab(event, 'tab-show')">Timeline</a></li>
            <li><a href="#tab-shell" class="tab-link" onclick="switchTab(event, 'tab-shell')">Shell Figli (<?php echo count($ArrayShell); ?>)</a></li>
            <li><a href="#tab-sql" class="tab-link" onclick="switchTab(event, 'tab-sql')">Query SQL (<?php echo count($ArraySql); ?>)</a></li>
            <li><a href="#tab-step" class="tab-link" onclick="switchTab(event, 'tab-step')">Step (<?php echo count($ArrayStep); ?>)</a></li>
        </ul>
    </div>
    
    <!-- TAB: Timeline (ARRAY_SHOW unificato) -->
    <div id="tab-show" class="tab-content active">
        <h4>Timeline Unificata</h4>
        <?php
            include 'view/processing/ArrayShow.php';
        ?>
    </div>
    
    <!-- TAB: Shell Figli -->
    <div id="tab-shell" class="tab-content">
        <h4>Shell Figli</h4>
        <?php
            include 'view/processing/ArrayShell.php';
        ?>
    </div>
    
    <!-- TAB: Query SQL -->
    <div id="tab-sql" class="tab-content">
        <h4>Query SQL Eseguite</h4>
        <?php
            include 'view/processing/ArraySql.php';
        ?>
    </div>
    
    <!-- TAB: Step -->
    <div id="tab-step" class="tab-content">
        <h4>Step di Elaborazione</h4>
        <?php
            include 'view/processing/ArrayStep.php';
        ?>
    </div>
    
    <div class="detail-actions">
        <button type="button" class="btn-back" onclick="history.back();">Torna alla Lista</button>
    </div>
</div>

<script>
function switchTab(event, tabId) {
    event.preventDefault();
    
    // Nascondi tutti i tab
    var contents = document.querySelectorAll('.tab-content');
    contents.forEach(function(content) {
        content.classList.remove('active');
    });
    
    // Rimuovi classe active da tutti i link
    var links = document.querySelectorAll('.tab-link');
    links.forEach(function(link) {
        link.classList.remove('active');
    });
    
    // Mostra il tab selezionato
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}
</script>
"}}]
