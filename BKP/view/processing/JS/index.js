/**
 * Processing - Main JavaScript
 * Funzioni per la gestione della lista, dettaglio e array processing
 */
//metti al cricametno $('#Waiting').hide(); al caricamento della pagina, così non si vede il messaggio di attesa
$(document).ready(function() {
    $('#Waiting').hide();
});
// ListProcessing.js
function openDetail(idRunSh, button) {
    // Recupera la riga di dettaglio e celle
    var detailRow = document.getElementById('detail-row-' + idRunSh);
    var detailContent = document.getElementById('detailContent-' + idRunSh);
    
    if (detailRow.style.display === 'none' || detailRow.style.display === '') {
        // Apri il dettaglio
        detailRow.style.display = 'table-row';
        detailContent.innerHTML = '<p>Caricamento...</p>';
        button.textContent = 'Chiudi';
        button.classList.add('btn-detail-open');
        
        // Fetch i dati degli shell figli via AJAX
        var dbName = document.querySelector('input[name="db_name"]').value;
        fetch('index.php?controller=processing&action=detailAjax&idRunSh=' + idRunSh + '&db_name=' + dbName)
            .then(response => response.text())
            .then(html => {
                detailContent.innerHTML = '<div style="padding: 20px;">' + html + '</div>';
            })
            .catch(error => {
                console.error('Errore nel caricamento del dettaglio:', error);
                detailContent.innerHTML = '<p>Errore nel caricamento dei dati</p>';
            });
    } else {
        // Chiudi il dettaglio
        detailRow.style.display = 'none';
        detailContent.innerHTML = '';
        button.textContent = 'Dettagli';
        button.classList.remove('btn-detail-open');
    }
}

// DetailProcessing.js
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

// ArrayShell.js
function showLog(log) {
    alert(log);
}

// ArraySql.js
function viewFile(file) {
    window.open('./view/processing/viewFile.php?file=' + encodeURIComponent(file));
}
