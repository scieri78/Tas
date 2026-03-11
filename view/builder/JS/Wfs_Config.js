$(document).ready(function () {
	$('#Waiting').hide();

	$('#refresh').prop("disabled", true);;
	$('#editworkflow').hide();
	$('#addworkflow').hide();
	$('#removeWorkFlow').hide();

	$('#divSelIdWorkFlow').hide();
	$('#divSelectFlusso').hide();
	$('#crearilascio').hide();
	$('#selrilascio').hide();
	$('#addflusso').hide();
	$(".selectSearch").select2();

});


function CreaRilascio() {
	var re = confirm('Do you want create the release?');
	if (re == true) {
		$('#Azione').val('RIL');
		var formData = {};

		let obj_form = $("form#FormMain").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		var ChkRilascia = []
		$("input[name='ChkRilascia[]']:checked").each(function () {
			ChkRilascia.push(parseInt($(this).val()));
		});
		formData['ChkRilascia'] = ChkRilascia;


		console.log('CreaRilascio action=crearilascio');
		console.log(formData);
		//return false;
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=crearilascio",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//  $("#Filedialog").dialog("open");
				//visualizzo il contenuto del file nel div html
				//$('#Filedialog').html(risposta);
				$('#selectRilascio').val(0);
				$(".selectRilascio :checkbox").prop("checked", false);
				// $("#Filedialog").dialog({title: 'Flusso: '+formData['NomeFlu']});
				// console.log('selIdTeam '+$("#selIdTeam").val());
				// console.log('IdWorkFlow '+formData['IdWorkFlow']);
				//OpenTeam($("#selIdTeam").val(),formData['IdWorkFlow']);
				//OpenWorkFlow(formData['IdWorkFlow']);
				alert("Rilascio effettuato!");

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("CreaRilascio: Qualcosa è andato storto!", stato);
			}
		});
	}
}

function refreshHome() {
	console.log("refreshHome");
	var selIdTeam = $('#selIdTeam').val();
	var selIdWorkFlow = $('#selIdWorkFlow').val();
	// alert("selIdTeam:"+selIdTeam+ " selIdWorkFlow:"+selIdWorkFlow);
	OpenTeam(selIdTeam, selIdWorkFlow);
	OpenWorkFlow(selIdWorkFlow);

	setTimeout(function () {
		if ($('#AreaPreFlussi').html() == '') {
			console.log("no AreaPreFlussi refreshHome");
			refreshHome();
		}
	}, 500);

}



function ShowAggiungiFlusso() {
	var formData = {
		IdWorkFlow: $('#selIdWorkFlow').val(),
		IdTeam: $('#selIdTeam').val()
	};
	console.log("ShowAggiungiFlusso");
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=aggiungiflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html

			$("#dialogMail").dialog({ title: 'Aggiungi nuovo FLUSSO' });
			$('#dialogMail').html(risposta);
			$("#dialogMail").dialog("open");

			//alert("Qualcosa è andato storto");
			return false;

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("refreshHome: Qualcosa è andato storto!", stato);
		}
	});
}
/* 
 function ShowAggiungiFlusso(){
			 $('#AggFlusso').show();
	   }
	   
	   function RemoveFlu(vIdFlu){
			   $('#TopScroll').val($( window ).scrollTop());
			   $('#BodyHeight').val($('body').height());
			   var re = confirm('Do you want remove the Flow?');
			   if ( re == true) {  
				  $('#Azione').val('RAF');

				 var input = $("<input>")
				 .attr("type", "hidden")
				 .attr("name", "IdFlu")
				 .val(vIdFlu);
				 $('#FormMain').append($(input));
				  
				  $("#Waiting").show();
				  $('#FormMain').submit(); 
			   }
	   }                       

	   
	   function CreaRilascio(){
			   var re = confirm('Do you want create the release?');
			   if ( re == true) {  
				  $('#Azione').val('RIL');
				  $("#Waiting").show();
				  $('#FormMain').submit(); 
			   }
	   }                       
	  

	  function DisDip(vIdLegame){
		   $('#TopScroll').val($( window ).scrollTop());
			   $('#BodyHeight').val($('body').height());
			   var re = confirm('Do you want disabled the Dipendency?');
			   if ( re == true) {  
				  $('#Azione').val('RDIS');

				 var input = $("<input>")
				 .attr("type", "hidden")
				 .attr("name", "IdLegame")
				 .val(vIdLegame);
				 $('#FormMain').append($(input));
				  
				  $("#Waiting").show();
				  $('#FormMain').submit(); 
			   }
	   }
	   
	   
	   function ModDip(vIdFlu,vIdDip,vTipo,vTLink){
		   $('#TopScroll').val($( window ).scrollTop());
		   $('#BodyHeight').val($('body').height());
		   $('#InsDip').empty().load('./view/builder/Wfs_ModDip.php', {
				  IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				  IdFlu: vIdFlu,
				  Tipo: vTipo,
				  IdDip: vIdDip,
				  TLink: vTLink
		   }).show();
	   }
	   
	   
	   $('#NomeFlusso').keyup(function(){
		  $(this).val($(this).val().replace(/ /g,"_"));
		  $(this).val($(this).val().toUpperCase());
	   });
	   
	   $('#AggiungiF').click(function(){
		   if ( $("#NomeFlusso").val() == '' ){
			 alert('There are empty input');
		   } else {
			 $('#Azione').val('AF');
			 $("#FormMain").submit();
		   }
	   });
	   
	   $('#ChiudiF').click(function(){
			   $("#AggFlusso").hide();
			   $("#NomeFlusso").val('');               
	   });     
	   
	 function ModFlu(vIdDip,vTipo){
		   $('#TopScroll').val($( window ).scrollTop());
		   $('#BodyHeight').val($('body').height());
		   $('#InsDip').empty().load('./view/builder/Wfs_ModDip.php', {
				  IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				  IdFlu: '<?php echo $IdFlu; ?>',
				  Tipo: vTipo,
				  IdDip: vIdDip
		   }).show();
	   }*/


function DeleteFlu() {
	var re = confirm('Do you want remove the Flow?');
	if (re == true) {

		var formData = {};

		var dialog = '#Filedialog';
		if ($("#paragona").is(':checked')) {
			dialog = "#Bilderdialog" + $("#Bilderdialog").val();
		}

		var ndialog = $("#Bilderdialog").val();
		$(dialog + " #FormDip " + '#AzioneD').val('RAF');
		let obj_form = $(dialog + " #FormDip").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});
		console.log('DeleteFlu action=loadflusso');
		console.log(formData);
		//return false;
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=loadflusso",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				$(dialog).dialog("close");
				//visualizzo il contenuto del file nel div html
				// $('#Filedialog').html(risposta);
				// $("#Filedialog").dialog({title: 'Flusso: '+formData['NomeFlu']});
				console.log('selIdTeam ' + $("#selIdTeam").val());
				console.log('IdWorkFlow ' + formData['IdWorkFlow']);
				//OpenTeam($("#selIdTeam").val(), formData['IdWorkFlow']);
				//OpenWorkFlow(formData['IdWorkFlow']);
				refreshHome();


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("DeleteFlu: Qualcosa è andato storto!", stato);
			}
		});
	}
}


function ModDip(IdWorkFlow, Flusso, IdFlu, IdDip, Tipo, TLink, ndialog, IdLegame) {

	var formData = {
		IdLegame: IdLegame,
		IdWorkFlow: IdWorkFlow,
		Flusso: Flusso,
		IdFlu: IdFlu,
		IdDip: IdDip,
		Tipo: Tipo,
		TLink: TLink,
		IdTeam: $('#selIdTeam').val(),
		ndialog: ndialog
	};
	console.log("ModDip action=modificadip");
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=modificadip",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html

			$('#dialogMail').html(risposta);
			$("#dialogMail").dialog("open");
			$("#dialogMail").dialog({ title: 'Modifica ' + $("#TipoDip").val() + ': ' + $("#NomeDip").val() });
			//alert("Qualcosa è andato storto");


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("ModDip: Qualcosa è andato storto!", stato);
		}
	});


}



// $("#BackAmb").click(function(){
// $('#FormMain').prop('action','./view/builder/PgWfsGest.php').submit();
// });

function BackButton(IdWorkFlow) {
	$('#IdWorkFlow').val(IdWorkFlow);
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});

	console.log("ModDip");
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=index",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html
			$('#contenitore').html(risposta);


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("BackButton: Qualcosa è andato storto!", stato);
		}
	});
}

function FLoadDett(IdWorkFlow, WorkFlow, IdFlu, Flusso) {
	//errorMessage("FLoadDett: Qualcosa è andato storto!");	
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlu: IdFlu,
		Flusso: Flusso,
		IdTeam: $('#selIdTeam').val()
	};
	//errorMessage("FLoadDett: Qualcosa è andato storto!",formData);	
	//if ($("#dialogIdFlu").val() != '' && $("#dialogIdFlu").val() != IdFlu) {

	if ($('input[name="xdialog"]:checked').val() == 1) {
		$("#Bilderdialog").val(1);
	} else {
		$("#Bilderdialog").val(2);
	}
	//}
	formData['ndialog'] = $("#Bilderdialog").val();
	console.log('FLoadDett action=loadflusso php Wfs_OpenFlusso.php ');
	console.log(formData);

	/*'./PHP/Wfs_Gest_DettWfs.php'	*/

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=loadflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			// console.log(risposta);
			//visualizzo il contenuto del file nel div htmlm
			// $("#ShowDettFlusso").html(risposta);

			var dialog = '#Filedialog';
			if ($("#paragona").is(':checked')) {
				dialog = "#Bilderdialog" + $("#Bilderdialog").val();
			}
			$(dialog).dialog({ title: 'Flusso: ' + Flusso });
			$(dialog).html(risposta);
			$(dialog).dialog("open");

			var doit;
			clearTimeout(doit);
			doit = setTimeout(BilderOption, 200);

			console.log("IdFlu:" + $(dialog + " #IdFlu").val());
			$("#dialogIdFlu").val($(dialog + " #IdFlu").val());
			// $("#LoadDett"+IdFlu).html(risposta);
			$('#ShowDettFlusso').show();

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("FLoadDett: Qualcosa è andato storto!", stato);

		}
	});


}






function OpenTeam(IdTeam, IdWorkFlow) {

	$('#IdTeam').val(IdTeam);
	$('#addworkflow').show();
	$('#removeWorkFlow').hide();
	$('#selIdWorkFlow').show();
	$('#divSelIdWorkFlow').show();
	$('#divSelectFlusso').hide();
	$('#refresh').prop("disabled", true);;
	$('#crearilascio').hide();
	$('#selrilascio').hide();
	$('#addflusso').hide();
	$('#editworkflow').hide();
	$('#selIdWorkFlow').selectpicker('refresh');
	var selIdWorkFlow = $('#selIdWorkFlow').val();
	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	console.log('OpenTeam');
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=selidworkflow",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html
			// $('body').html(risposta);
			$('#AreaPreFlussi').empty();
			$('#selIdWorkFlow option').remove();
			$('#selectFlusso option').remove();


			if (IdTeam == '') {
				$('#selIdWorkFlow').hide();
				$('#divSelIdWorkFlow').hide();
				$('#addworkflow').hide();
			} else {
				$('#selIdWorkFlow').append($('<option>', {
					value: '',
					text: 'Seleziona WorkFlow'
				}));
				$('#selectFlusso').append($('<option>', {
					value: '',
					text: 'Filtra Flussi/Tutti'
				}));
				$('#selIdWorkFlow').selectpicker('refresh');
				const dataAsArray = JSON.parse(risposta);
				console.log(dataAsArray);
				var icon;
				var sel;
				$.each(dataAsArray, (index, row) => {
					console.log(row['ID_WORKFLOW']);
					icon = '<i style="color:white" class="fa-regular fa-circle"></i> ';
					if (row['READONLY'] == 'S') icon = '<i style="color:brown" class="fa-regular fa-glasses"></i> ';
					sel = "";
					if (IdWorkFlow == row['ID_WORKFLOW']) sel = "selected";
					$('<option ' + sel + '>').val(row['ID_WORKFLOW']).attr('data-content', icon + row['WORKFLOW']).text(row['WORKFLOW']).appendTo('#selIdWorkFlow');
					$('#selIdWorkFlow').selectpicker('refresh');
				});
			}
			// $("form#FormMain").submit();


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("OpenTeam: Qualcosa è andato storto!", stato);
		}
	});
}



function modificaWF(IdWorkFlow) {
	console.log("modificaWF2");
	var inputValue = $('#InpWorkFlow').val();
	if (typeof inputValue === 'undefined' || inputValue === '') {
		alert('Inserire un nome per il WorkFlow');
		return;
	}
	var formData = {};
	$('#IdWorkFlow').val(IdWorkFlow);
	let obj_form = $("form#FormModificaWF").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	console.log("modificaWF action=contentList");
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=contentList",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			//$('#dialogMail').html(risposta);
			$("#dialogMail").dialog("close");
			$('#LoadFls' + IdWorkFlow).html(risposta);
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("modificaWF: Qualcosa è andato storto!", stato);
		}

	});
	OpenTeam($("#IdTeam").val(), IdWorkFlow);
	//OpenWorkFlow(IdWorkFlow);
	var intervalId = setInterval(function () {
		if ($('#AreaPreFlussi').is(':empty')) {
			OpenWorkFlow(IdWorkFlow)
		} else {
			// Interrompi il ciclo quando il contenuto è stato inserito
			clearInterval(intervalId);
		}
	}, 1000);

}


function DeleteWorkFlow(IdWorkFlow) {
	var re = confirm('Do you want remove the WorkFlow?');
	if (re == true) {
		$('#removeWorkFlow').hide();
		$('#selIdWorkFlow').show();
		$('#divSelIdWorkFlow').show();
		$('#divSelectFlusso').hide();
		$('#refresh').prop("disabled", true);;
		$('#crearilascio').hide();
		$('#selrilascio').hide();
		$('#addflusso').hide();
		$('#editworkflow').hide();
		$('#elencoFlussi').html('');
		$('#selIdWorkFlow').selectpicker('refresh');

		var formData = {
			IdWorkFlow: $('#IdWorkFlow').val(),
			Azione: 'Cancella'
		};
		//$('#IdWorkFlow').val(IdWorkFlow);
		/*	let obj_form = $("form#FormModificaWF").serializeArray();
			obj_form.forEach(function (input) { 
			 formData[input.name] =input.value; 
			});*/
		console.log("DeleteWorkFlow action=modificawf");
		console.log(formData);
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=modificawf",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				//$("#dialogMail").dialog("open");
				//$('#dialogMail').html(risposta);
				//$("#dialogMail").dialog("close");
				//$('#LoadFls'+IdWorkFlow).html(risposta);
			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("DeleteWorkFlow: Qualcosa è andato storto!", stato);
			}

		});

		OpenTeam($('#IdTeam').val());

	}
}

function AggiungiFlusso() {
	var formData = {};
	let obj_form = $("form#FormAggiungiFlusso").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	console.log('AggiungiFlusso');
	console.log(formData);


	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=addflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			$("#dialogMail").dialog("close");
			OpenWorkFlow($('#IdWorkFlow').val());

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AggiungiFlusso: Qualcosa è andato storto!", stato);
		}
	});


}




function OpenWorkFlow(IdWorkFlow) {
	$('#IdWorkFlow').val(IdWorkFlow);
	$('#editworkflow').show();

	$('#crearilascio').show();
	$('#refresh').prop("disabled", false);;
	$('#selrilascio').show();
	$('#addflusso').show();
	$('#selectFlusso').show();
	$('#divSelectFlusso').show();
	console.log('OpenWorkFlow action=elencoFlussi');
	//	selezionaRilascio();
	if ($('#selIdWorkFlow').val() == "") {
		$('#removeWorkFlow').hide();
		$('#selIdWorkFlow').show();
		$('#divSelIdWorkFlow').show();
		$('#divSelectFlusso').hide();
		$('#refresh').prop("disabled", true);;
		$('#crearilascio').hide();
		$('#selrilascio').hide();
		$('#addflusso').hide();
		$('#editworkflow').hide();
		$('#elencoFlussi').html('');
		$('#selIdWorkFlow').selectpicker('refresh');

	} else {


		$('#selectFlusso option').remove();
		$('#selectFlusso').append($('<option>', {
			value: '',
			text: 'Filtra Flussi/Tutti'
		}));
		$('#selIdWorkFlow').selectpicker('refresh');
		var formData = {};

		let obj_form = $("form#FormMain").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		console.log(formData);
		console.log('OpenWorkFlow formData');
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=elencoFlussi",
			// imposto l'azione in caso di successo
			success: function (risposta) {

				//visualizzo il contenuto del file nel div html
				$('#AreaPreFlussi').html(risposta);

				// $("form#FormMain").submit();
				$('.fa-caret-left').hide();
				$('.fa-caret-right').hide();
				setRilascio();


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("OpenWorkFlow: Qualcosa è andato storto!", stato);
			}
		});
	}
	loadSelect(IdWorkFlow);


}

function setRilascio() {
	console.log('setRilascio');


	$("#selrilascio i").removeClass("fa-square");
	$("#selrilascio i").addClass('fa-square-check');
	$('#selectAll').html(' Seleziona Tutto');


}

function OpenWorkFlowFlussi(IdWorkFlow) {
	$('#IdWorkFlow').val(IdWorkFlow);

	var formData = {};

	let obj_form = $("form#FormMain").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});

	console.log('OpenWorkFlowFlussi');
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=elencoFlussi",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html
			$('#AreaPreFlussi').html(risposta);
			$('.fa-caret-left').hide();
			$('.fa-caret-right').hide();
			setRilascio();
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("OpenWorkFlowFlussi: Qualcosa è andato storto!", stato);
		}
	});
	//loadSelect(IdWorkFlow);
	//selezionaRilascio();

}


function loadSelect(IdWorkFlow) {


	var formData = { IdWorkFlow: IdWorkFlow };
	console.log('loadSelect action=selectflusso');
	console.log(formData);

	// console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=selectflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {


			var UTILIZZATO = 0;
			const dataAsArray = JSON.parse(risposta);
			console.log("loadSelect");
			console.log(dataAsArray);

			$.each(dataAsArray, (index, row) => {
				UTILIZZATO = UTILIZZATO + row['UTILIZZATO'];
				//now HERE you construct your html structure, which is so much easier using jQuery


				$('#selectFlusso').append($('<option>', {
					value: row['ID_FLU'],
					text: row['FLUSSO']
				}));
				$('#selectFlusso').selectpicker('refresh');
			});
			$('#selectFlusso').selectpicker('refresh');
			if (UTILIZZATO == 0 && IdWorkFlow) {
				$('#removeWorkFlow').show();
			} else {
				$('#removeWorkFlow').hide();
			}
			console.log('UTILIZZATO: ' + UTILIZZATO);
			// $("form#FormMain").submit();


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("loadSelect: Qualcosa è andato storto!", stato);
		}
	});
}



function addWorkFlow() {

	var formData = {
		IdTeam: $('#selIdTeam').val()
	};
	console.log("addWorkFlow: action:modificawf php:Wfs_ModWfs.php");
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=modificawf",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div htmlm


			$("#dialogMail").dialog({ title: "Crea WorkFlow" });
			$("#dialogMail").dialog("open");
			$("#dialogMail").html(risposta);

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("addWorkFlow: Qualcosa è andato storto!", stato);
		}
	});
	//refreshHome();
}


function remWorkFlow() {

	var formData = {
		IdTeam: $('#selIdTeam').val()
	};
	console.log('remWorkFlow');
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=modificawf",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div htmlm


			$("#dialogMail").dialog({ title: "Crea WorkFlow" });
			$("#dialogMail").dialog("open");
			$("#dialogMail").html(risposta);

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("remWorkFlow: Qualcosa è andato storto!", stato);
		}
	});
}


function modWorkFlow() {

	var formData = {
		IdWorkFlow: $('#selIdWorkFlow').val()
		, IdTeam: $('#selIdTeam').val()
		, WorkFlow: $("#selIdWorkFlow option:selected").text()
	};
	console.log('modWorkFlow');
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=modificawf",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div htmlm


			$("#dialogMail").dialog({ title: "Modifica WorkFlow: " + $("#selIdWorkFlow option:selected").text() });
			$("#dialogMail").dialog("open");
			$("#dialogMail").html(risposta);

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("modWorkFlow: Qualcosa è andato storto!", stato);
		}
	});
}

/*
$('#IdTeam').change(function(){
  $('#Waiting').show();
  $('#FormMain').submit();
});*/

function selezionaRilascio() {
	console.log('selezionaRilascio');
	if ($('#selectRilascio').val() == 0) {
		$(".selectRilascio :checkbox").prop("checked", true);
		$('#selectRilascio').val(1);
		$('#selectAll').html(' Deselezione Tutto');
		console.log('selezionaRilascio Deselezione Tutto');
		if ($("#selrilascio i").hasClass("fa-square-check")) {
			$("#selrilascio i").toggleClass('fa-square-check fa-square');
		}

	} else {
		$(".selectRilascio :checkbox").prop("checked", false);
		$('#selectRilascio').val(0);
		$('#selectAll').html(' Seleziona Tutto');
		console.log('selezionaRilascio Seleziona Tutto');
		if ($("#selrilascio i").hasClass("fa-square")) {
			$("#selrilascio i").toggleClass('fa-square fa-square-check');
		}
	}
}

function getlegamiflussi(IdFlusso, MouseEnter) {


	var formData = {
		IdWorkFlow: $('#selIdWorkFlow').val(),
		IdFlusso: IdFlusso
	};


	console.log('getlegamiflussi action=getlegamiflussi ');
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=getlegamiflussi",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			const dataAsArray = JSON.parse(risposta);
			console.log("MouseEnter:" + MouseEnter);
			//	console.log(dataAsArray);
			$.each(dataAsArray.suc, (index, IdFlu) => {
				console.log(IdFlu);
				if (MouseEnter == 1) {
					//console.log('#Flusso' + IdFlu);					
					$('#Flusso' + IdFlu).addClass('legamesuc');
					//		$('#Flusso' + IdFlu + ' .fa-caret-right').show();
				} else {
					$('#Flusso' + IdFlu).removeClass('legamesuc');
					//	$('#Flusso' + IdFlu + ' .fa-caret-right').hide();
				}

			});


			$.each(dataAsArray.pre, (index, IdFlu) => {
				console.log(IdFlu);
				if (MouseEnter == 1) {
					console.log('#Flusso' + IdFlu);
					$('#Flusso' + IdFlu).addClass('legamepre');
					//	$('#Flusso' + IdFlu + ' .fa-caret-left').show();
				} else {
					$('#Flusso' + IdFlu).removeClass('legamepre');
					//	$('#Flusso' + IdFlu + ' .fa-caret-left').hide();
				}

			});



		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("getlegamiflussi: Qualcosa è andato storto!", stato);
		}
	});
}


function DisDip(IdLegame, ndialog) {

	var dialog = '#Filedialog';
	if ($("#paragona").is(':checked')) {
		dialog = "#Bilderdialog" + $("#Bilderdialog").val();
	}


	$(dialog + ' #TopScroll').val($(window).scrollTop());
	$(dialog + ' #BodyHeight').val($('body').height());
	var re = confirm('Do you want disabled the Dipendency?');
	if (re == true) {
		$(dialog + ' #AzioneD').val('RDIS');
		$(dialog + ' #IdLegame').val(IdLegame);

		var formData = {};

		let obj_form = $(dialog + " #FormDip").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});
		formData['ndialog'] = ndialog;
		// console.log(formData);
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=loadflusso",
			// imposto l'azione in caso di successo
			success: function (risposta) {

				//visualizzo il contenuto del file nel div html
				$(dialog).html(risposta);
				OpenTeam($("#selIdTeam").val(), formData['IdWorkFlow']);
				OpenWorkFlow(formData['IdWorkFlow']);
				var doit;
				clearTimeout(doit);
				doit = setTimeout(BilderOption, 500);
				refreshHome();

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("DisDip: Qualcosa è andato storto!", stato);
			}
		});
	}
}





function DelDip(IdDip, Tipo, IdLeg, ndialog) {

	var re = confirm('Do you want remove the Dependence?');
	if (re == true) {
		var dialog = '#Filedialog';
		if ($("#paragona").is(':checked')) {
			dialog = "#Bilderdialog" + $("#Bilderdialog").val();
		}
		$(dialog + ' #AzioneD').val('R' + Tipo);
		var formData = {};
		var ndialog = $("#Bilderdialog").val();
		let obj_form = $(dialog + " #FormDip").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});
		formData['IdDip'] = IdDip;
		formData['IdLeg'] = IdLeg;
		formData['ndialog'] = ndialog;
		console.log('DelDip controller=builder&action=loadflusso');
		console.log('ndialog:' + ndialog);
		console.log(formData);
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=loadflusso",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				$("#dialogMail").dialog("close");
				//visualizzo il contenuto del file nel div html
				//$('#Filedialog').html(risposta);		

				$(dialog).html(risposta);
				var doit;
				clearTimeout(doit);
				doit = setTimeout(BilderOption, 500);

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("DelDip: Qualcosa è andato storto!", stato);
			}
		});
	}
}
function validaformDip() {

	if ($('#ConfPhp').length && $('#ConfPhp').val() == '') {
		alert("Inserisci Destinazione!");
		return false;
	}
	if ($('#Destinazione').length && $('#Destinazione').val() == '') {
		alert("Inserisci Destinazione!");
		return false;
	}

	if ($('#NomeLink').length && $('#NomeLink').val() == '') {
		alert("Inserisci Nome!");
		return false;
	}
	if ($('#SELFLUSSO').length && $('#SELFLUSSO').val() == '') {
		alert("Inserisci Flusso!");
		return false;
	}
	if ($('#SELCARICAMENTO').length && $('#SELCARICAMENTO').val() == '') {
		alert("Inserisci Caricamento!");
		return false;
	}
	if ($('#NomeCar').length && $('#NomeCar').val() == '') {
		alert("Inserisci Nome!");
		return false;
	}
	if ($('#SELELABORAZIONE').length && $('#SELELABORAZIONE').val() == '') {
		alert("Inserisci Shell!");
		return false;
	}
	if ($('#NomeEla').length && $('#NomeEla').val() == '') {
		alert("Inserisci Nome!");
		return false;
	}
	if ($('#NomeVal').length && $('#NomeVal').val() == '') {
		alert("Inserisci Nome!");
		return false;
	}
	if ($('#SELINZVAL').length && $('#SELINZVAL').val() == '') {
		alert("Inserisci Inizio AnnoMese Validita'!");
		return false;
	}
	if ($('#SELFINVAL').length && $('#SELFINVAL').val() == '') {
		alert("Inserisci Fine AnnoMese Validita'!");
		return false;
	}

	if ($("#SELCARICAMENTO").val() == 'CarFile' && $("#TXTCARICAMENTO").val() == '') {
		alert("Inserisci CartCartella Dest!");
		return false;
	}

	if ($("#TipoDip").val() == 'Link' && $("#ConfPhp").val() == 'parametri.php' && $("#ID_PAR_GRUPPO").val() == '') {
		alert("Inserisci Gruppo!");
		return false;
	}

	return true;
}

function PulModDip(ndialog) {
	//$('#IdWorkFlow').val(IdWorkFlow);
	if (validaformDip()) {

		$("#Waiting").show();
		var formData = {};

		let obj_form = $("form#FormDipS").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});
		formData['ndialog'] = ndialog;

		console.log('PulModDip action=loadflusso php=Wfs_OpenFlusso.php');
		console.log(formData);
		console.log("ndialog" + ndialog);



		//return false;
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=builder&action=loadflusso",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				$("#dialogMail").dialog("close");
				//visualizzo il contenuto del file nel div html
				var dialog = '#Filedialog';
				if ($("#paragona").is(':checked')) {
					dialog = "#Bilderdialog" + $("#Bilderdialog").val();
				}
				$(dialog).html(risposta);
				if (formData['NomeFlu']) {
					console.log("NomeFlu:" + formData['NomeFlu']);
					$(dialog).dialog({ title: 'Flusso: ' + formData['NomeFlu'] });
				}
				OpenTeam($("#selIdTeam").val(), formData['IdWorkFlow']);
				OpenWorkFlow(formData['IdWorkFlow']);

				var doit;
				clearTimeout(doit);
				doit = setTimeout(BilderOption, 500);

				$("#Waiting").hide();
				refreshHome();
			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("PulModDip: Qualcosa è andato storto!", stato);

			}
		});
	}

}


function BilderOption() {
	$("#Bilderdialog1").dialog("option", "position", { my: "left top", at: "left+10 top+100", of: "body" });
}

function refreshOpen(ndialog) {
	$("#Waiting").show();
	var formData = {};
	var dialog = '#Filedialog';
	if ($("#paragona").is(':checked')) {
		dialog = "#Bilderdialog" + $("#Bilderdialog").val();
	}

	let obj_form = $(dialog + " #FormDip").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	formData['ndialog'] = ndialog;
	console.log('refreshOpen action=loadflusso php=Wfs_OpenFlusso.php');
	console.log('ndialog:' + ndialog);
	console.log(formData);
	//return false;
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=loadflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div html			
			$(dialog).html(risposta);
			OpenTeam($("#selIdTeam").val(), formData['IdWorkFlow']);
			OpenWorkFlow(formData['IdWorkFlow']);
			var doit;
			clearTimeout(doit);
			doit = setTimeout(BilderOption, 500);
			refreshHome();

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("refreshOpen: Qualcosa è andato storto!", stato);

		}
	});
}




function aggiungiDipendenza(IdWorkFlow, IdFlu, Tipo, ndialog) {


	var formData = {
		IdWorkFlow: IdWorkFlow,
		IdFlu: IdFlu,
		Tipo: Tipo,
		ndialog: ndialog
	};

	/*let obj_form = $("form#FormDip").serializeArray();
	obj_form.forEach(function (input) { 
	 formData[input.name] =input.value; 
	});*/

	console.log(formData);
	console.log("aggiungidipendenza action=aggiungidipendenza");
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=builder&action=aggiungidipendenza",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html


			$("#dialogMail").dialog({ title: "Crea Dipendenza" });
			$("#dialogMail").dialog("open");
			$("#dialogMail").html(risposta);


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("aggiungiDipendenza: Qualcosa è andato storto!", stato);
		}
	});




};


function SpacesToUnderscore(inputElement) {
	// Ottieni il valore corrente dell'input
	var inputValue = inputElement.value.trim();

	// Trasforma gli spazi in underscore
	var transformedValue = inputValue.replace(/ /g, '_');

	// Imposta il nuovo valore nell'input
	inputElement.value = transformedValue;
}