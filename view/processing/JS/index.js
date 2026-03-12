/**
 * Processing - Main JavaScript
 * Funzioni per la gestione della lista, dettaglio e array processing
 */
//metti al cricametno $('#Waiting').hide(); al caricamento della pagina, così non si vede il messaggio di attesa
$(document).ready(function() {
    $('#Waiting').hide();
});

function getCurrentSito() {
    return new URLSearchParams(window.location.search).get('sito') || '';
}

function getProcessingForm() {
    return $('#formProcessing');
}

function setTextarea() {
    $('textarea').css('height', '90%');
    $('textarea').css('width', '98%');
}

function clearAutoRefresh() {
    for (var i = 1; i < 100; i++) {
        window.clearInterval(i);
    }
    $('#footer').html('');
    $('#AutoRefresh').prop('checked', false);
}

function getValueDARETI() {
    var element = $('#DARETI');
    if (element.length > 0) {
        return element.val();
    }
    return 0;
}

function Refresh() {
    $('#Waiting').show();
    getProcessingForm().submit();
}

function ManualOk(vIdSh) {
    var re = confirm('Are you sure you want to change the status to manual ok?');
    if (re === true) {
        $('#Waiting').show();
        $.ajax({
            type: 'POST',
            data: { ID_RUN_SH: vIdSh },
            encode: true,
            url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=setManual',
            success: function () {
                Refresh();
            },
            error: function () {
                alert('Qualcosa è andato storto');
            }
        });
    }
}

function deleteSh(vIdSh) {
    var re = confirm('Are you sure you want to delete this Sh?');
    if (re === true) {
        $('#Waiting').show();
        $.ajax({
            type: 'POST',
            data: { ID_RUN_SH: vIdSh },
            encode: true,
            url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=deleteSh',
            success: function () {
                Refresh();
            },
            error: function () {
                alert('Qualcosa è andato storto');
            }
        });
    }
}

function openDialog(vIdSh, SHELL, vaction) {
    clearAutoRefresh();
    $.ajax({
        type: 'POST',
        data: { IDSH: vIdSh, DARETI: getValueDARETI() },
        encode: true,
        url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=' + vaction,
        success: function (risposta) {
            $('#Filedialog').dialog('open');
            $('#Filedialog').html(risposta);
            setTimeout(setTextarea, 500);

            var log = $('#Log').val();
            var idhs = $('#IDHS').val();
            var shellLog = 'LOG: (' + idhs + ') ' + log;
            var shellFile = 'SHELL: (' + idhs + ') ' + log;
            var dialogTitle = vaction === 'apriFile' ? shellFile : shellLog;
            $('#Filedialog').dialog({ title: dialogTitle || SHELL });
        },
        error: function () {
            alert('Qualcosa è andato storto');
        }
    });
}

function OpenShSel(vId) {
    window.open('index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=index&IDSELEM=' + vId + '&DARETI=' + getValueDARETI());
}

function openGrafici(ShName, ShTags, IdSh) {
    clearAutoRefresh();
    $.ajax({
        type: 'POST',
        data: { STEP: ShName, TAGS: ShTags, IDSH: IdSh, DARETI: getValueDARETI() },
        encode: true,
        url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=apriGrafici',
        success: function (risposta) {
            $('#Filedialog').html(risposta);
            $('#Filedialog').dialog({ title: 'Grafico: ' + ShName });
            $('#Filedialog').dialog('open');
        },
        error: function () {
            alert('Qualcosa è andato storto');
        }
    });
}

function openRelTab(IDSH, IDRUNSH, IDRUNSQL, SHFILE) {
    clearAutoRefresh();
    $.ajax({
        type: 'POST',
        data: { IDRUNSH: IDRUNSH, IDRUNSQL: IDRUNSQL, IDSH: IDSH, DARETI: getValueDARETI() },
        encode: true,
        url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=apriRelTab',
        success: function (risposta) {
            $('#Filedialog').html(risposta);
            $('#Filedialog').dialog({ title: 'TABELLE UTILIZZATE DAL FILE: ' + SHFILE });
            $('#Filedialog').dialog('open');
        },
        error: function () {
            alert('Qualcosa è andato storto');
        }
    });
}

function ForceEnd(vIdRunSh) {
    var re = confirm('Are you sure you want to put this Shell in an error state?');
    if (re === true) {
        var form = $('<form>', {
            method: 'POST',
            action: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=index'
        });

        form.append($('<input>', { type: 'hidden', name: 'ForceEnd', value: vIdRunSh }));

        var dbName = $('input[name="db_name"]').first().val();
        if (dbName) {
            form.append($('<input>', { type: 'hidden', name: 'db_name', value: dbName }));
        }

        $('body').append(form);
        form.trigger('submit');
    }
}

// ListProcessing.js
function openDetail(idRunSh, button) {
    // Recupera la riga di dettaglio e celle
    var detailRow = document.getElementById('detail-row-' + idRunSh);
    var detailContent = document.getElementById('detailContent-' + idRunSh);
    
    if (detailRow.style.display === 'none' || detailRow.style.display === '') {
        // Apri il dettaglio
        detailRow.style.display = 'table-row';
        detailContent.innerHTML = '<p>Caricamento...</p>';
        if (button) {
            button.textContent = 'Chiudi';
            button.classList.add('btn-detail-open');
        }
        
        // Fetch i dati degli shell figli via AJAX
        var dbName = document.querySelector('input[name="db_name"]').value;
        fetch('index.php?sito=' + getCurrentSito() + '&controller=processing&action=detailAjax&idRunSh=' + idRunSh + '&db_name=' + dbName)
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
        if (button) {
            button.textContent = 'Dettagli';
            button.classList.remove('btn-detail-open');
        }
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
