/**
 * Processing - Main JavaScript
 * Funzioni per la gestione della lista, dettaglio e array processing
 */
//metti al cricametno $('#Waiting').hide(); al caricamento della pagina, così non si vede il messaggio di attesa
$(document).ready(function() {
    $('#Waiting').hide();

    if ($('.selectSearch').length > 0 && $.fn.select2) {
        $('.selectSearch').select2();
    }

    if ($('#SelAmbito').length > 0 && $.fn.select2) {
        $('#SelAmbito').select2({
            placeholder: 'Seleziona Ambito',
            allowClear: true
        });
    }

    if ($('#AutoRefresh').is(':checked')) {
        setInterval(function () {
            Refresh();
        }, 30000);
    }
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

function resetSession() {
    $('#resetSession').val('1');
    Refresh();
}

function viewFilter() {
    var icon = $('#viewfilter i');
    var hiddenPanel = $('.divHiden, .divshow');
    if (icon.hasClass('fa-eye')) {
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
        hiddenPanel.removeClass('divHiden').addClass('divshow');
        $('#viewFilterH').val('Si');
    } else {
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
        hiddenPanel.removeClass('divshow').addClass('divHiden');
        $('#viewFilterH').val('No');
    }
}

function NumLastSubmit() {
    $('#SelNumPage').val('1');
    Refresh();
}

function NumLastNoSubmit() {
    $('#Waiting').show();
}

function goToPage(page) {
    var targetPage = parseInt(page, 10);
    if (isNaN(targetPage) || targetPage < 1) {
        targetPage = 1;
    }
    $('#SelNumPage').val(targetPage);
    Refresh();
}

function selectSelShell() {
    $('#SelNumPage').val('1');
    Refresh();
}

function selectSelInDate() {
    $('#SelNumPage').val('1');
    Refresh();
}

function selectSelIdProc() {
    $('#SelNumPage').val('1');
    Refresh();
}

function apriLegendProcessing() {
    $.ajax({
        type: 'POST',
        data: { IDSH: 1 },
        encode: true,
        url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=rcLegend',
        success: function (risposta) {
            if ($('#dialogMail').length > 0) {
                $('#dialogMail').dialog({ title: 'Rc legend' });
                $('#dialogMail').dialog('open');
                $('#dialogMail').html(risposta);
            } else {
                $('#Filedialog').dialog({ title: 'Rc legend' });
                $('#Filedialog').dialog('open');
                $('#Filedialog').html(risposta);
            }
        },
        error: function (stato) {
            errorMessage('apriLegendProcessing: andato in errore', stato);
        }
    });
}

function viewLastRunx() {
    clearAutoRefresh();
    $.ajax({
        type: 'POST',
        data: { IDSH: 1 },
        encode: true,
        url: 'index.php?sito=' + getCurrentSito() + '&controller=statoshell&action=lastRun',
        success: function (risposta) {
            if ($('#dialogMail').length > 0) {
                $('#dialogMail').dialog({ title: 'Last Runs in the Month' });
                $('#dialogMail').dialog('open');
                $('#dialogMail').html(risposta);
            } else {
                $('#Filedialog').dialog({ title: 'Last Runs in the Month' });
                $('#Filedialog').dialog('open');
                $('#Filedialog').html(risposta);
            }
            setTimeout(setTextarea, 500);
        },
        error: function (stato) {
            errorMessage('viewLastRunx: andato in errore', stato);
        }
    });
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
            error: function (stato) {
                errorMessage('ManualOk: andato in errore', stato);
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
            error: function (stato) {
                errorMessage('deleteSh: andato in errore', stato);
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
        error: function (stato) {
            errorMessage('openDialog: andato in errore', stato);
        }
    });
}

function OpenSelShell(vId) {
    var form = $('#formProcessing');
    if (form.length === 0) {
        window.open('index.php?sito=' + getCurrentSito() + '&controller=processing&action=index&IDSELEM=' + vId + '&DARETI=' + getValueDARETI());
        return;
    }

    $('#SelInDate').val('ALL_DAY').prop('selected', true);
    $('#NumLast').val(10);

    var shellSelect = $('#SelShell');
    if (shellSelect.length > 0) {
        var vIdStr = String(vId);
        if (shellSelect.find('option[value="' + vIdStr + '"]').length === 0) {
            shellSelect.append($('<option>', { value: vIdStr, text: vIdStr }));
        }
        shellSelect.val(vIdStr);
    }

    form.trigger('submit');
}

function OpenShSel(vId) {
    OpenSelShell(vId);
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
        error: function (stato) {
            errorMessage('openGrafici: andato in errore', stato);
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
        error: function (stato) {
            errorMessage('openRelTab: andato in errore', stato);
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
                detailContent.innerHTML = '<div>' + html + '</div>';
            })
            .catch(stato => {
                console.error('Errore nel caricamento del dettaglio:', stato);
                errorMessage('openDetail: andato in errore', stato);
                detailContent.innerHTML = '<p>openDetail: andato in errore</p>';
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
