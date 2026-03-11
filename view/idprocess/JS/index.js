$(document).ready(function () {
	$('#Waiting').hide();
	$('#addIdProcessButtom').hide();
	$('#copyIdProcessButtom').hide();
	$('#removeIdProcessButtom').hide();
	$('#divIdWorkFlow').hide();
	$('.noSearch').select2({ minimumResultsForSearch: -1 });
	
});

var currentInizVal = {};
setCurrentInizVal();

function setCurrentInizVal(){

setTimeout(function () {
						
						
$('[id^="INZVAL_"]').each(function() {
    var $element = $(this);
	 $element.select2();
    var idProcess = $element.attr('id').split('_')[1]; // Estrae l'IdProcess dall'ID
    currentInizVal[idProcess] = $element.val(); // Memorizza il valore attuale
   
	console.log("currentInizVal",idProcess,currentInizVal[idProcess]);
});

}, 1500);
}

function OpenTeam(defwf) {
	console.log('OpenTeam Action=selidworkflow');
	var formData = {
		IdWorkFlow: $('#IdWorkFlow').val(),
		IdTeam: $('#IdTeam').val()
	};
	$('#divIdWorkFlow').hide();

	if ($('#IdTeam').val()) {
		$('#divIdWorkFlow').show();
	}



	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=selidworkflow",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			$('#IdWorkFlow').select2('destroy');
			$('#IdWorkFlow').select2();

			$('#IdWorkFlow option').remove();

			var data = {
				id: '',
				text: 'Seleziona WorkFlow'
			};

			var newOption = new Option(data.text, data.id, false, false);
			$('#IdWorkFlow').append(newOption).trigger('change');
			//$('<option selected>').val('').text('Seleziona WorkFlow').appendTo('#IdWorkFlow').prop('selected', true);


			const dataAsArray = JSON.parse(risposta);
			console.log(dataAsArray);
			var icon;
			var sel;
			$.each(dataAsArray, (index, row) => {
				console.log(row);
				data = {
					id: row['ID_WORKFLOW'],
					text: row['WORKFLOW'] + " (" + row['CONTA'] + ")"
				};
				var newOption = new Option(data.text, data.id, false, false);
				$('#IdWorkFlow').append(newOption);



				//	$('<option>').val(row['ID_WORKFLOW']).text(row['WORKFLOW']).appendTo('#IdWorkFlow').prop('selected', false);
				//$('#IdWorkFlow').selectpicker('refresh');
			});

			$('#IdWorkFlow').val(defwf).trigger("change");

			$('.noSearch').select2({ minimumResultsForSearch: -1 });
			OpenWorkFlow();
			// $("form#FormMain").submit();


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("OpenTeam: Qualcosa è andato storto!", stato);
		}
	});
}


function OpenWorkFlow() {
	console.log('OpenWorkFlow action=addidprocess');
	$('#Waiting').show();
	var formData = {
		IdWorkFlow: $('#IdWorkFlow').val(),
		IdTeam: $('#IdTeam').val()
	};
	$('#refreshButtom').prop("disabled", true);
	if ($('#IdWorkFlow').val()) {
		$('#refreshButtom').prop("disabled", false);
	}



	console.log(formData);
	titolowf = $("#IdWorkFlow option:selected").text();
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=addidprocess",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			//   $("#LoadNewProcess").html(risposta);
			$("#divAddIdProcess").html(risposta);
			//	$( "#Filedialog" ).dialog({title:'Lista IdProcess '+titolowf, dialogClass:"dialog-full-mode"});
			//	$("#Filedialog").dialog("open");
			// $('#AddIdProcess').show();
			addNewIdProcess();
			setCurrentInizVal();
			$('#Waiting').hide();
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("OpenWorkFlow: Qualcosa è andato storto!", stato);
		}
	});


}

function RefreshPage() {
	console.log('RefreshPage');
	OpenTeam($('#IdWorkFlow').val());
	OpenWorkFlow();
	setCurrentInizVal();
}

/*    function OpenWorkFlow(vIdWorkFlow){
		$('#LoadNewProcess').load('./PHP/IdProcessAdd.php',{IdWorkFlow : vIdWorkFlow, IdTeam : $('#IdTeam').val(), FromId : '<?php echo $FromId; ?>', ToId : '<?php echo $ToId; ?>', ShowStatusCopy : '<?php echo $ShowStatusCopy; ?>' });
		$('#AddIdProcess').show();
	}*/

/*	$('#IdTeam').change(function(){
		$('#Waiting').show();
		$('#FormMain').submit();
	}); */




function SetShWfs(IdSh, flag) {
	console.log('SetShWfs');
	var mess = "";
	if (flag == 'Y') {
		mess = 'Are you sure you want Enable the Wfs to use this Shell?';
	}
	else {
		mess = 'Are you sure you want Remove this Shell from the Wfs?';
	}
	var re = confirm(mess);

	if (re == true) {
		var formData = {};
		let obj_form = $("form#FormMain").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		console.log(formData);


		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=exptowfs&action=setshwfs&IdSh=" + IdSh + "&flag=" + flag,
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$("#contenitore").html(risposta);


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("SetShWfs: Qualcosa è andato storto!", stato);
			}
		});


	}
}

function SetBlockWfs(IdSh, flag) {
	console.log('SetBlockWfs');
	var mess = "";
	if (flag == 'Y') {

		mess = 'Are you sure you want enable the readonly from this Shell from the Wfs?';
	}
	else {
		mess = 'Are you sure you want disable the readonly from this Shell from the Wfs?';
	}
	var re = confirm(mess);

	if (re == true) {
		var formData = {};
		let obj_form = $("form#FormMain").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=exptowfs&action=setblockwfs&IdSh=" + IdSh + "&flag=" + flag,
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$("#contenitore").html(risposta);


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("SetBlockWfs: Qualcosa è andato storto!", stato);
			}
		});


	}
}

function removeReadonly(IdProc) {
	console.log('removeReadonly action=removeReadonly');
	var formData = {};
	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['IdProc'] = IdProc;

	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=removeReadonly",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);
			OpenWorkFlow();
			$("#Filedialog").html(risposta);
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("removeReadonly: Qualcosa è andato storto!", stato);
		}
	});
}
function addNewIdProcess() {
	console.log('addNewIdProcess action=formidprocess');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=formidprocess",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#dialogMail").dialog({title: 'Aggiungi IdProcess'});
			$('#newIdProcContainer').html(risposta);

			// $("#dialogMail").dialog("open");IdWorkFlow
			if ($('#IdWorkFlow').val()) {
				$('#addIdProcessButtom').show();
				$('#copyIdProcessButtom').show();
				$('#removeIdProcessButtom').show();
			} else {
				$('#addIdProcessButtom').hide();
				$('#copyIdProcessButtom').hide();
				$('#removeIdProcessButtom').hide();

			}
			$('#gestioneCancella').hide();
			$('#gestioneCopy').hide();
			$('#gestioneEsercizio').hide();

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("addNewIdProcess: Qualcosa è andato storto!", stato);
		}
	});
}


function AddReadOnly(IdProc) {
	console.log('AddReadOnly action=addReadonly');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['IdProc'] = IdProc;


	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=addReadonly",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);

			OpenWorkFlow();
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AddReadOnly: Qualcosa è andato storto!", stato);
		}
	});

}



function AddSveccIdp(vId) {
	console.log('AddSveccIdp action=insertlist');
	var formData = {};
	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});

	formData['IdProc'] = vId;


	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=insertlist",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);

			OpenWorkFlow();
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AddSveccIdp: Qualcosa è andato storto!", stato);
		}
	});

}

function RemSveccIdp(vId) {
	console.log('RemSveccIdp action=deletelist');
	var formData = {};
	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});

	formData['IdProc'] = vId;


	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=deletelist",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);

			OpenWorkFlow();
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("RemSveccIdp: Qualcosa è andato storto!", stato);
		}
	});

}

function Testsave() {

	console.log('Testsave action=deletelist');
	$('#CopyIdProcess').hide();
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	$('#SaveIdProcess').hide();
	$('#ShowCopy').hide();
	$('#SaveIdProcess').show();
	$('.FieldMandCopy').each(function () {
		$(this).val('');
	});

	$('.FieldMandRem').each(function () {
		$(this).val('');
	});

	var vSave = true;
	$('.FieldMand').each(function () {
		if ($(this).val() == '') { vSave = false; }
	});

	if (vSave) {
		$('#SaveIdProcess').show();
	}

}


function eliminaIdProcess() {
	console.log('eliminaIdProcess action=saveidprocess');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['Azione'] = 'Rimuovi';


	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=saveidprocess",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);
			$("#divAddIdProcess").html(risposta);
			addNewIdProcess();
			OpenTeam(formData['IdWorkFlow']);
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("eliminaIdProcess: Qualcosa è andato storto!", stato);
		}
	});

	/*	
			var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "Azione")
			.val('Rimuovi');
			$('#FormMain').append($(input));        
			$("#Waiting").show();
			$('#FormMain').submit();         */
}

// NON USATA
function xSaveIdProcess() {
	console.log('xSaveIdProcess action=saveidprocess');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['Azione'] = 'Aggiungi';


	console.log(formData);
	//	alert (IdProc);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=saveidprocess",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#contenitore").html(risposta);
			//$("#divAddIdProcess").html(risposta);
			//addNewIdProcess();
			//RefreshHome();
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');
			//OpenWorkFlow();
			//	OpenTeam(formData['IdWorkFlow']);

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("xSaveIdProcess: Qualcosa è andato storto!", stato);
		}
	});

}
/*
var input = $("<input>")
.attr("type", "hidden")
.attr("name", "Azione")
.val('Aggiungi');
$('#FormMain').append($(input));
$('#FormMain').submit();
		    
$('#Waiting').show();
$('#FormMain').submit(); */


function copyFormIdProcess() {
	console.log('copyFormIdProcess action=copyFormIdProcess');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['Azione'] = 'Copia';

	var FromId = $('#FromId').val();
	var ToId = $('#ToId').val();

	var re = confirm("Sei sicuro di voler sovrascrivere l'id process " + ToId + " di destinazione con i dati del " + FromId + "?");

	if (re == true) {

		console.log(formData);

		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=idprocess&action=showcopy",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				alert("Copia IdProcess effettuata!");
				//visualizzo il contenuto del file nel div htmlm
				// $("#contenitore").html(risposta);
				$("#Filedialog").dialog({ title: 'Lista IdProcess', dialogClass: "dialog-full-mode" });
				$("#Filedialog").dialog("open");
				$('#Filedialog').html(risposta);
				//$(location).prop('href', 'index.php?controller=mailconf&action=index');

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("copyFormIdProcess: Qualcosa è andato storto!", stato);
			}
		});

	}
}

function showDetTable() {



	var formData = {};
	console.log('showDetTable action=showcopy');
	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});


	console.log(formData);
	// alert("showDetTable");

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=showcopy ",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);
			IdTeam = $("#IdTeam option:selected").text();
			IdWorkFlow = $("#IdWorkFlow option:selected").text();

			FromId = $("#FromId option:selected").text();
			ToId = $("#ToId option:selected").text();
			titolodialog = 'Lista IdProcess ' + IdTeam + " " + IdWorkFlow + " Form " + FromId + " To " + ToId;
			$("#Filedialog").dialog({ title: titolodialog, dialogClass: "dialog-full-mode" });
			$("#Filedialog").dialog("open");
			$('#Filedialog').html(risposta);
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("showDetTable: Qualcosa è andato storto!", stato);
		}
	});


}


function RefreshTable() {
	console.log('RefreshTable action=showcopy');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});


	console.log(formData);
	// alert("showDetTable");

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=showcopy ",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $("#contenitore").html(risposta);
			// $( "#Filedialog" ).dialog({title:'Lista IdProcess', dialogClass:"dialog-full-mode"});
			$("#Filedialog").dialog("open");
			//	$('#Filedialog').html(risposta);
			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("RefreshTable: Qualcosa è andato storto!", stato);
		}
	});


}




function AllineaDate(IdProc) {
	console.log('AllineaDate action=AllineaDate');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['IdProc'] = IdProc;


	console.log(formData);
	//	alert (IdProc);
	var re = confirm("Sicuro di voler Allineare la data apertura con l'idprocess?");

	if (re == true) {
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=idprocess&action=AllineaDate",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				// $("#contenitore").html(risposta);

				RefreshHome();
				//$(location).prop('href', 'index.php?controller=mailconf&action=index');

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("AllineaDate: Qualcosa è andato storto!", stato);
			}
		});
	}
}



function RefreshHome() {
	console.log('RefreshHome action=contentList');
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});


	console.log(formData);
	//	alert (IdProc);



	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=idprocess&action=contentList",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#contenitore").html(risposta);


			//$(location).prop('href', 'index.php?controller=mailconf&action=index');

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("RefreshHome: Qualcosa è andato storto!", stato);
		}
	});

}

function changeInitDate(IdProcess) {
	console.log('RefreshHome action=contentList');
	var formData = {
		IdProcess: IdProcess,
		inizVal: $('#INZVAL_' + IdProcess).val()
	};
	console.log(formData);
	//	alert (IdProc);
	var re = confirm("Sicuro di voler modificare Inz.Val.Legami per IdProcess: " + IdProcess);

	if (re == true) {

		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=idprocess&action=UpdateInzVal",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				//$("#contenitore").html(risposta);
				alert("Inz. Val. Legami modificata!");
				RefreshPage();


				//$(location).prop('href', 'index.php?controller=mailconf&action=index');

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("RefreshHome: Qualcosa è andato storto!", stato);
			}
		});

	}else{		
		//alert(currentInizVal[IdProcess]);
		$('#INZVAL_' + IdProcess).select2('destroy'); // Distrugge l'istanza corrente

		$('#INZVAL_' + IdProcess).val(currentInizVal[IdProcess]);
		$('#INZVAL_' + IdProcess).select2();
	}
}






// function RemoveReadOnly(vIdProc){        

// var input = $("<input>")
// .attr("type", "hidden")
// .attr("name", "RemoveRO")
// .val(vIdProc);
// $('#FormMain').append($(input));        
// $("#Waiting").show();
// $('#FormMain').submit();                  
// }

// function AddReadOnly(vIdProc){

// var input = $("<input>")
// .attr("type", "hidden")
// .attr("name", "AddRO")
// .val(vIdProc);
// $('#FormMain').append($(input));        
// $("#Waiting").show();
// $('#FormMain').submit();                 
// }


function setDecrizione() {
	console.log('setDecrizione');
	$('#CopyIdProcess').hide();
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	$('#SaveIdProcess').hide();
	$('#ShowCopy').hide();
	$('#SaveIdProcess').hide();
	if ($('#Esercizio').val()) {
		$('#Descr').val('Chiusura ' + $('#Esercizio').val());
		$('#SaveIdProcess').show();
	} else {
		$('#Descr').val('');
	}



}

$('#Tipo').change(function () {
	$('#Descr').val($('#Descr').val() + ' [' + $(this).val() + ']');
});

$('.FieldMand').change(function () {
	Testsave();
});
$('.FieldMand').keyup(function () {
	Testsave();
});



function TestsaveCopy(valore) {
	console.log("TestsaveCopy CopyIdProcess hide ");
	$('#Esercizio').val("");
	$('#Descr').val("");
	$('#CopyIdProcess').hide();
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	$('#SaveIdProcess').hide();
	$('#ShowCopy').hide();

	$('.FieldMand').each(function () {
		// $(this).val('');
	});

	$('.FieldMandRem').each(function () {
		//    $(this).val('');
	});
	console.log("TestsaveCopy this val: " + valore);

	var vSave = true;
	var vShow = false;

	console.log("TestsaveCopy FromId: " + $('#FromId').val());
	console.log("TestsaveCopy ToId: " + $('#ToId').val());
	if ($('#FromId').val() == $('#ToId').val()) {
		vSave = false;
	}
	if ($('#FromId').val() || $('#ToId').val()) {
		vShow = true;
	}

	if (!$('#FromId').val() || !$('#ToId').val()) {
		vSave = false;
	}

	if (vSave) {
		console.log("TestsaveCopy CopyIdProcess show ");
		$('#CopyIdProcess').show();
	}

	if (vShow) {
		$('#ShowCopy').show();
	}
}
/*
   $('.FieldMandCopy').change(function(){
	  TestsaveCopy();
   });           
   $('.FieldMandCopy').keyup(function(){
	  TestsaveCopy();
   });     
   TestsaveCopy();
*/
function SvuotaIdProcess() {
	console.log("SvuotaIdProcess");
	$('#CopyIdProcess').hide();
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	$('#SaveIdProcess').hide();
	$('.FieldMand').each(function () {
		$(this).val('');
	});
	$('.FieldMandCopy').each(function () {
		$(this).val('');
	});

	$('#CancellaId').val('');
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	if ($('#SvuotaId').val() != '') {
		$('#svuotaIdProcess').show();
	}
};

function CancellaIdProcess() {
	console.log("CancellaIdProcess");
	$('#CopyIdProcess').hide();
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	$('#SaveIdProcess').hide();
	$('#ShowCopy').hide();
	$('.FieldMand').each(function () {
		$(this).val('');
	});
	$('.FieldMandCopy').each(function () {
		$(this).val('');
	});

	$('#SvuotaId').val('');
	$('#CancellaId').change(function () { });
	$('#svuotaIdProcess').hide();
	$('#cancellaIdProcess').hide();
	if ($('#CancellaId').val() != '') { $('#cancellaIdProcess').show(); }
};


function viewAddIdProcess() {
	console.log('viewAddIdProcess');
	$('#gestioneCancella').hide();
	$('#gestioneCopy').hide();
	$('#gestioneEsercizio').show();
}

function viewCopyIdProcess() {
	console.log('viewCopyIdProcess');
	$('#gestioneCancella').hide();
	$('#gestioneCopy').show();
	$('#gestioneEsercizio').hide();
}

function viewRemoveIdProcess() {
	console.log('viewRemoveIdProcess');
	$('#gestioneCancella').show();
	$('#gestioneCopy').hide();
	$('#gestioneEsercizio').hide();
}
