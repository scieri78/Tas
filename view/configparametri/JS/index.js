$(document).ready(function () {
    $('#Waiting').hide();
    $('#addGruppo').hide();
    $('#addParametro').hide();
    $('#removeGruppo').hide();

    loadWorkflows();

    $('#SelectWorkflow').change(function () {
        loadGruppi();
    });

    $('#SelectGruppo').change(function () {
        loadParametri();
    });
});

function loadWorkflows() {
    var formData = {};
    formData['loadWorkflows'] = 'loadWorkflows';
    console.log('loadWorkflows');
    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=configparametri&action=getWorkflows",
        success: function (risposta) {
            //set IdWorkFlow  
            var IdWorkFlow = $("#IdWorkFlow").val();
            console.log("loadWorkflows IdWorkFlow", IdWorkFlow);
            $('#SelectWorkflow option').remove();
            $('#SelectWorkflow').append($('<option>', {
                value: '',
                text: 'Seleziona WorkFlow'
            }));

            $('#SelectWorkflow').selectpicker('refresh');
            $('#SelectGruppo').selectpicker('refresh');
            const dataAsArray = JSON.parse(risposta);
            console.log('loadWorkflows', dataAsArray);
            var icon;
            var sel;
            $.each(dataAsArray, (index, row) => {
                console.log(row['ID_WORKFLOW']);
                sel = "";
                if (IdWorkFlow == row['ID_WORKFLOW']) {
                    sel = "selected";
                    loadGruppi(IdWorkFlow);

                };
                $('<option ' + sel + '>').val(row['ID_WORKFLOW']).attr('data-content', row['WORKFLOW']).text(row['WORKFLOW']).appendTo('#SelectWorkflow');
                $('#SelectWorkflow').selectpicker('refresh');
            });

        },
        error: function (stato) {
            errorMessage("loadWorkflows: Qualcosa è andato storto!", stato);
        }
    });
}

function loadGruppi(idWorkflow) {

    var id_workflow = $('#SelectWorkflow').val() ? $('#SelectWorkflow').val() : idWorkflow;
    var ID_PAR_GRUPPO = '';
    if (id_workflow) {
        var formData = {};
        formData['id_workflow'] = id_workflow;
        console.log('loadGruppi');
        $.ajax({
            type: "POST",
            data: formData,
            encode: true,
            url: "index.php?controller=configparametri&action=getGruppi",
            //   data: { id_workflow: id_workflow },
            success: function (risposta) {
                //   $('#SelectGruppo').html(risposta);
                console.log("loadGruppi id_workflow", id_workflow);
                $('#SelectGruppo option').remove();
                $('#SelectGruppo').append($('<option>', {
                    value: '',
                    text: 'Seleziona Gruppo'
                }));

                $('#SelectGruppo').selectpicker('refresh');
                const dataAsArray = JSON.parse(risposta);
                console.log('loadGruppi', dataAsArray);
                var icon;
                var sel;
                $.each(dataAsArray, (index, row) => {
                    console.log(row['ID_WORKFLOW']);
                    sel = "";
                    if (ID_PAR_GRUPPO == row['ID_PAR_GRUPPO']) {
                        sel = "selected";
                        loadParametri(ID_PAR_GRUPPO);

                    };
                    $('<option ' + sel + '>').val(row['ID_PAR_GRUPPO']).attr('data-content', row['LABEL']).text(row['LABEL']).appendTo('#SelectGruppo');
                    $('#SelectGruppo').selectpicker('refresh');
                });


                $('#addGruppo').show();


                //   echo '<option value="' . $gruppo['ID_PAR_GRUPPO'] . '">' . $gruppo['LABEL'] . '</option>';
            },
            error: function (stato) {
                errorMessage("loadGruppi: Qualcosa è andato storto!", stato);
            }
        });
    } else {

    }
}

function loadParametri() {
    var id_par_gruppo = $('#SelectGruppo').val();
    if (id_par_gruppo) {
        $.ajax({
            type: "POST",
            url: "index.php?controller=configparametri&action=getParametri",
            data: { id_par_gruppo: id_par_gruppo },
            success: function (response) {
                $('#ParametriContainer').html(response);
                $('#addParametro').show();
                $('#removeGruppo').show();
            },
            error: function (stato) {
                errorMessage("loadParametri: Qualcosa è andato storto!", stato);
            }
        });
    } else {
        $('#ParametriContainer').html('');
    }
}

function addParametri() {
    var formData = {};
    let obj_form = $("form#FormParametri").serializeArray();
    obj_form.forEach(function (input) {
        formData[input.name] = input.value;
    });

    console.log('addParametri', formData);
    if (verificaParametri(formData)) {
        $.ajax({
            type: "POST",
            data: formData,
            encode: true,
            url: "index.php?controller=configparametri&action=insertLinkParametri",
            success: function (risposta) {
                //  $("#contenitore").html(risposta);
                $("#dialogMail").dialog("close");
                loadParametri();
                /* $('#ShowDiagram').html(risposta);
                $('#ShowDiagram').show();*/

                $('#Waiting').hide();
                //  loadParametri(); // Refresh the parameters list
            },
            error: function (stato) {
                errorMessage("addParametri: Qualcosa è andato storto!", stato);
            }
        });
    }
}

function addParametriGruppo() {
    var formData = {};
    let obj_form = $("form#FormParametriGruppo").serializeArray();
    obj_form.forEach(function (input) {
        formData[input.name] = input.value;
    });

    console.log('addParametriGruppo', formData);

    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=configparametri&action=insertLinkParametriGruppo",
        success: function (risposta) {
             $("#contenitore").html(risposta);
            $("#dialogMail").dialog("close");
            loadGruppi(); // Refresh the groups list
        },
        error: function (stato) {
            errorMessage("addParametriGruppo: Qualcosa è andato storto!", stato);
        }
    });
}

function verificaParametri(formData) {
    var ret = true;
    var mess = "";
    if (!('LABEL' in formData) || formData['LABEL'] == '') {
        mess = mess + "Inserire Label!";
        ret = false;
    }
    if (!('NOME' in formData) || formData['NOME'] == '') {
        mess = mess + "\n\rInserire Nome!";
        ret = false;
    }
    if (!('TIPO_INPUT' in formData) || formData['TIPO_INPUT'] == '') {
        mess = mess + "\n\rInserire Tipo Input!";
        ret = false;
    }
    console.log("verificaParametri", formData);
    if (mess != "") {
        alert(mess);
    }
    return ret;
}

function addParametriLegame() {
    var formData = {};
    let obj_form = $("form#FormParametriLegame").serializeArray();
    obj_form.forEach(function (input) {
        formData[input.name] = input.value;
    });

    console.log('addParametr', iLegameformData);

    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=configparametri&action=insertLinkParametriLegame",
        success: function (risposta) {
            $("#contenitore").html(risposta);
        },
        error: function (stato) {
            errorMessage("addParametriLegame: Qualcosa è andato storto!", stato);
        }
    });
}

function removeParametri(id_par) {
    var re = confirm('Sei sicuro di voler rimuovere questo parametro?');
    if (re == true) {
        var id_par_gruppo = $("#SelectGruppo").val();
        var formData = {};
        formData['id_par_gruppo'] = id_par_gruppo;
        formData['id_par'] = id_par;
        console.log('removeParametri', formData);
        $.ajax({
            type: "POST",
            url: "index.php?controller=configparametri&action=removeParametri",
            data: formData,
            success: function (response) {
                alert(response);
                loadParametri(); // Refresh the parameters list
            },
            error: function (stato) {
                errorMessage("removeParametri: Qualcosa è andato storto!", stato);
            }
        });
    }
}

function removeParametriGruppi(id_par) {

    var re = confirm('Sei sicuro di voler rimuovere questo gruppo?');
    var id_par_gruppo = $("#SelectGruppo").val();
    var id_workflow = $('#SelectWorkflow').val();
    var formData = {};
    formData['id_par_gruppo'] = id_par_gruppo;
    formData['id_workflow'] = id_workflow;
    console.log('removeParametriGruppi', formData);
    if (re == true) {
        $.ajax({
            type: "POST",
            url: "index.php?controller=configparametri&action=removeParametriGruppo",
            data: formData,
            success: function (response) {
                alert("Gruppo Eliminato!");
                loadGruppi(); // Refresh the parameters list
            },
            error: function (stato) {
                errorMessage("removeParametri: Qualcosa è andato storto!", stato);
            }
        });
    }
}

function modificaParametri(id_par) {
    var re = confirm('Sei sicuro di voler modificare questo parametro?');
    if (re == true) {
        var formData = {};
        let obj_form = $("form#FormParametri").serializeArray();
        obj_form.forEach(function (input) {
            formData[input.name] = input.value;
        });

        formData['id_par'] = id_par;
        console.log('modificaParametri', formData);
        if (verificaParametri(formData)) {
            console.log('modificaParametri', formData);

            $.ajax({
                type: "POST",
                data: formData,
                encode: true,
                url: "index.php?controller=configparametri&action=updateLinkParametri",
                success: function (response) {
                    alert(response);
                    loadParametri(); // Refresh the parameters list
                },
                error: function (stato) {
                    errorMessage("modificaParametri: Qualcosa è andato storto!", stato);
                }
            });
        }
    }
}

function selectTipoImput() {
    $(".number").hide();
    $(".text").hide();
     $(".valueText").hide();
     $(".valueSelect").hide();
    if ($('#ParamTipoInput').val() == 'text') {
        $(".text").show();
        $(".valueText").show();
    }
    if ($('#ParamTipoInput').val() == 'number') {
        $(".number").show();
        $(".valueText").show();
    }
     if ($('#ParamTipoInput').val() == 'datetime') {
        $(".valueText").show();
    }
      if ($('#ParamTipoInput').val() == 'textarea') {
        $(".valueText").show();
    }
     if ($('#ParamTipoInput').val() == 'radio'  || $('#ParamTipoInput').val() == 'checkbox' || $('#ParamTipoInput').val() == 'select') {
      $(".valueSelect").show();
    }



}

function showFormParametri(id_par) {
    var id_par_gruppo = $("#SelectGruppo").val();
    var id_workflow = $('#SelectWorkflow').val();
    var formData = {};
    formData['ID_PAR_GRUPPO'] = id_par_gruppo;
    formData['ID_WORKFLOW'] = id_workflow;
    formData['id_par'] = id_par;
    console.log('showFormParametri', formData);
    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=configparametri&action=formParametri",
        success: function (risposta) {
            $("#dialogMail").dialog("open");

            var selectedText = $('#SelectGruppo option:selected').text();
            $("#dialogMail").dialog({ title: "Aggiungi parametri al gruppo: " + selectedText });
            $("#dialogMail").html(risposta);
            
            //  $('#FormContainer').html(response);
        },
        error: function (stato) {
            errorMessage("showFormParametri: Qualcosa è andato storto!", stato);
        }
    });
}

function showFormParametriGruppo() {
    var id_workflow = $('#SelectWorkflow').val();
    var formData = {};
    formData['id_workflow'] = id_workflow;
    console.log('showFormParametriGruppo', formData);

    $.ajax({
        type: "POST",
        data: formData,
        encode: true,
        url: "index.php?controller=configparametri&action=formParametriGruppo",
        success: function (risposta) {
            var selectedText = $('#SelectWorkflow option:selected').text();
            $("#dialogMail").dialog("open");
            $("#dialogMail").dialog({ title: "Aggiungi Gruppo per WorkFlow: " + selectedText });
            $("#dialogMail").html(risposta);
        },
        error: function (stato) {
            errorMessage("showFormParametriGruppo: Qualcosa è andato storto!", stato);
        }
    });
}

function showFormParametriLegame() {
    $.ajax({
        url: "view/configparametri/form_parametri_legame.php",
        success: function (response) {
            $('#FormContainer').html(response);
        },
        error: function (stato) {
            errorMessage("showFormParametriLegame: Qualcosa è andato storto!", stato);
        }
    });
}

function errorMessage(message, stato) {
    console.error(message, stato);
    alert(message);
}
