$(document).ready(function () {
	$('#Waiting').hide();
	$(".selectSearch").select2();
});

function loadFlussi(IdWorkFlow, WorkFlow, selectFlusso) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		selectFlusso: selectFlusso,

	};
	console.log(formData);

	//./PHP/Wfs_Gest_LoadFlussi.php'				

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loadflussi",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $('#LoadFls'+IdWorkFlow).html(risposta);
			// $("#dialogMail").dialog({title: 'Flussi Wf: '+WorkFlow});
			$('#LoadFls' + IdWorkFlow).html(risposta);
			//$("#dialogMail").dialog("open");
			//$("#dialogMail").scrollTop(0);
			/*	 $('html, body').animate({
				scrollTop: $('#WFS'+IdWorkFlow).position().top-40 },
				1200
			);*/
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("loadFlussi: Qualcosa è andato storto!", stato);
		}
	});
}


function LoadWf() {
	var formData = {};
	console.log('LoadWf');
	let obj_form = $("#FormAuthorizer").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});
	console.log(formData);
	//./PHP/Wfs_Gest_LoadFlussi.php'
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=LoadWf",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $('#LoadFls'+IdWorkFlow).html(risposta);
			// $("#dialogMail").dialog({title: 'Flussi Wf: '+WorkFlow});
			var IdWorkFlow = $('#selIdWorkFlow').val();
			$('#LoadWf').html(risposta);
			getDett(IdWorkFlow);
			//$("#dialogMail").dialog("open");
			//$("#dialogMail").scrollTop(0);
			/*	 $('html, body').animate({
				scrollTop: $('#WFS'+IdWorkFlow).position().top-40 },
				1200
			);*/
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("loadFlussi: Qualcosa è andato storto!", stato);
		}
	});
}

function addNewGroup(IdWorkFlow, WorkFlow, IdFlusso, Flusso) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso
	};

	console.log(formData);
	//./PHP/Wfs_Gest_LoadFlussi.php'				

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=addgroup",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $('#LoadFls'+IdWorkFlow).html(risposta);
			$("#dialogMail").dialog({ title: 'Flussi Wf: ' + WorkFlow });
			$('#dialogMail').html(risposta);
			$("#dialogMail").dialog("open");
			$("#dialogMail").scrollTop(0);
			/*	 $('html, body').animate({
				scrollTop: $('#WFS'+IdWorkFlow).position().top-40 },
				1200
			);*/
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("addNewGroup: Qualcosa è andato storto!", stato);
		}
	});
}

function newGroup(IdWorkFlow, WorkFlow, IdFlusso, Flusso) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso
	};

	console.log(formData);
	//./PHP/Wfs_Gest_LoadFlussi.php'				

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=addgroupto",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			// $('#LoadFls'+IdWorkFlow).html(risposta);
			$("#dialogMail").dialog({ title: 'Flussi Wf: ' + WorkFlow });
			$('#dialogMail').html(risposta);
			$("#dialogMail").dialog("open");
			$("#dialogMail").scrollTop(0);
			/*	 $('html, body').animate({
				scrollTop: $('#WFS'+IdWorkFlow).position().top-40 },
				1200
			);*/
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("newGroup: Qualcosa è andato storto!", stato);
		}
	});
}


function loadDettWfs(IdWorkFlow, WorkFlow, Azione, DelGr) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		Azione: Azione,
		DelGr: DelGr
	};


	//./PHP/Wfs_Gest_LoadFlussi.php'				

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loaddettwfs",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$('#LoadDett' + IdWorkFlow).html(risposta);


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("loadDettWfs: Qualcosa è andato storto!", stato);
		}
	});
}


function addGroupWfs(IdWorkFlow, WorkFlow, Azione) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		AddGrp: $('#AddGrp').val(),
		Azione: Azione
	};


	//./PHP/Wfs_Gest_LoadFlussi.php'				
	if ($("#AddGrp").val() != '') {
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=loaddettwfs",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$('#LoadDett' + IdWorkFlow).html(risposta);
				$("#dialogMail").dialog("close");

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("addGroupWfs: Qualcosa è andato storto!", stato);
			}
		});
	} else {
		alert("Inserire Nome Gruppo");
	}
}

function LoadUser(IdWorkFlow, WorkFlow, IdGruppo, Gruppo, IdAss, Azione) {
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdGruppo: IdGruppo,
		Gruppo: Gruppo,
		IdAss: IdAss,
		Azione: Azione

	};
	console.log(formData);
	var testoc = '';
	if (Azione == 'DU') {
		testoc = 'Do you want delete this User from ' + Gruppo + ' group ?';
	}
	var re = true;
	if (testoc != '') {
		re = confirm(testoc);
	}

	if (re == false) {
		return false;

	}




	//./PHP/Wfs_Gest_LoadFlussi.php'				

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loaduser",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$('#US_' + IdWorkFlow + '_' + IdGruppo).html(risposta);


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("LoadUser: Qualcosa è andato storto!", stato);
		}
	});
}

function loadFlusso(IdWorkFlow, WorkFlow, IdFlusso, Flusso) {
	console.log('loadFlusso');
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso
	}
	console.log(formData);
	//./PHP/Wfs_Gest_LoadFlussi.php'
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loadflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			$('#LoadFls' + IdWorkFlow).html('');
			//  alert('#ShowFlu_'+IdWorkFlow+IdFlusso);
			//visualizzo il contenuto del file nel div htmlm
			$('#LoadFls' + IdWorkFlow).html(risposta);
			//  $('#ShowFlu_'+IdWorkFlow+IdFlusso).html(risposta);


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("loadFlusso: Qualcosa è andato storto!", stato);
		}
	});
}

function deleteFlusso(IdWorkFlow, WorkFlow, IdFlusso, Flusso, IdAut, AzioneAut) {
	console.log('deleteFlusso');
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso,
		IdAut: IdAut,
		AzioneAut: AzioneAut
	}
	console.log(formData);
	var re = confirm('Do you want delete this Group from ' + Flusso + ' Flow?');
	if (re == true) {
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=loadflusso",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				$('#LoadFls' + IdWorkFlow).html('');
				//  alert('#ShowFlu_'+IdWorkFlow+IdFlusso);
				//visualizzo il contenuto del file nel div htmlm
				//  $('#ShowFlu_'+IdWorkFlow+IdFlusso).html(risposta);
				$('#LoadFls' + IdWorkFlow).html(risposta);

			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("deleteFlusso: Qualcosa è andato storto!", stato);
			}
		});
	}
}



function addFlusso(IdWorkFlow, WorkFlow, IdFlusso, Flusso, AzioneAut) {
	console.log('addFlusso');
	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso,
		IdGroup: $('#AddGroupIn' + IdWorkFlow + IdFlusso).val(),
		AzioneAut: AzioneAut
	}	
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loadflusso",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			$('#LoadFls' + IdWorkFlow).html('');
			//  alert('#ShowFlu_'+IdWorkFlow+IdFlusso);
			//visualizzo il contenuto del file nel div htmlm
			// $('#ShowFlu_'+IdWorkFlow+IdFlusso).html(risposta);
			$('#LoadFls' + IdWorkFlow).html(risposta);
			$("#dialogMail").dialog("close");
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("addFlusso: Qualcosa è andato storto!", stato);
		}
	});

}



function FLoadDett(IdWorkFlow, WorkFlow, titoloWF) {

	var formData = { IdWorkFlow: IdWorkFlow, WorkFlow: WorkFlow, titoloWF: titoloWF };
	console.log(formData);

	/*'./PHP/Wfs_Gest_DettWfs.php'	*/
	if ($("#LoadDett" + IdWorkFlow).html() == '' && $("#LoadFls" + IdWorkFlow).html() == '') {
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=loaddett",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$("#LoadDett" + IdWorkFlow).html(risposta);


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("FLoadDett: Qualcosa è andato storto!", stato);

			}
		});

	} else {
		$('#LoadDett' + IdWorkFlow).empty();
		$('#LoadFls' + IdWorkFlow).empty();
		//$('#InsAuthDip'+IdWorkFlow+IdFlusso).hide();
	}
}



function getDett(IdWorkFlow) {

	var formData = { IdWorkFlow: IdWorkFlow };
	console.log(formData);

	/*'./PHP/Wfs_Gest_DettWfs.php'	*/
	if ($("#LoadDett" + IdWorkFlow).html() == '' && $("#LoadFls" + IdWorkFlow).html() == '') {
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=loaddett",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$("#LoadDett" + IdWorkFlow).html(risposta);


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("FLoadDett: Qualcosa è andato storto!", stato);

			}
		});

	} else {
		$('#LoadDett' + IdWorkFlow).empty();
		$('#LoadFls' + IdWorkFlow).empty();
		//$('#InsAuthDip'+IdWorkFlow+IdFlusso).hide();
	}
}

function AddFlusso(IdWorkFlow, WorkFlow, IdGruppo, Gruppo) {

	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdGruppo: IdGruppo,
		Gruppo: Gruppo
	}


	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=adduser",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#dialogMail").dialog({ title: 'Aggiungi utente a Wf: ' + WorkFlow + ' Gruppo ' + Gruppo });
			$("#dialogMail").dialog("open");
			$("#dialogMail").html(risposta);

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AddFlusso: Qualcosa è andato storto!", stato);
			// alert("Qualcosa è andato storto");
		}
	});

}

function AddUserFlusso(IdWorkFlow, WorkFlow, IdGruppo, Gruppo) {

	if (!$('#SelUser').val()) {
		alert("Seleziona utente!");
		return false;
	}

	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdGruppo: IdGruppo,
		Gruppo: Gruppo,
		IdUsr: $('#SelUser').val(),
		Azione: 'AU'

	};
	console.log(formData);



	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=loaduser",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$('#US_' + IdWorkFlow + '_' + IdGruppo).html(risposta);

			$("#dialogMail").dialog("close");
		},

		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AddUserFlusso: Qualcosa è andato storto!", stato);
		}
	});

}
$("#AddUsr").keyup(function () {
	$(this).val($(this).val().replace(/ /g, "_"));
	$(this).val($(this).val().toUpperCase());
});


function AddNewUserFlusso(IdWorkFlow, WorkFlow, IdGruppo, Gruppo, Azione) {
	var AddUserName = $('#AddUserName').val();
	var AddNome = $('#AddNome').val();
	var AddCognome = $('#AddCognome').val();
	if (AddUserName == '' || AddNome == '' || AddCognome == '') {
		alert('There are empty input!');

	} else {
		var formData = {
			IdWorkFlow: IdWorkFlow,
			WorkFlow: WorkFlow,
			IdGruppo: IdGruppo,
			Gruppo: Gruppo,
			AddUserName: AddUserName,
			AddNome: AddNome,
			AddCognome: AddCognome,
			Azione: Azione
		};
		console.log(formData);



		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=loaduser",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div html
				$('#US_' + IdWorkFlow + '_' + IdGruppo).html(risposta);
				console.log(risposta);

				$("#dialogMail").dialog("close");
			},

			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("AddNewUserFlusso: Qualcosa è andato storto!", stato);
			}
		});

	}
}



function ModAuthDipFlu(IdWorkFlow, WorkFlow, Flusso, IdFlusso) {

	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso
	};
	console.log(formData);

	if ($('#LoadAutDip' + IdWorkFlow + IdFlusso).children().length == 0) {

		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=modauthdipflu",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$('#LoadAutDip' + IdWorkFlow + IdFlusso).html(risposta);
				$('#InsAuthDip' + IdWorkFlow + IdFlusso).show();

			},

			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("ModAuthDipFlu: Qualcosa è andato storto!", stato);
			}
		});

	} else {
		$('#LoadAutDip' + IdWorkFlow + IdFlusso).empty();
		$('#InsAuthDip' + IdWorkFlow + IdFlusso).hide();
	}

}
function deleteAuthDipFlu(IdWorkFlow, WorkFlow, IdFlusso, IdDip, Flusso, IdAut, AzioneDipAut, Dipendenza) {

	var re = confirm('deleteAuthDipFlu Do you want delete this Group from the ' + Dipendenza + ' Flow?');
	console.log('#LoadAutDip' + IdWorkFlow + IdFlusso);
	if (re == true) {
		var formData = {
			IdWorkFlow: IdWorkFlow,
			WorkFlow: WorkFlow,
			IdFlusso: IdFlusso,
			IdDip: IdDip,
			Flusso: Flusso,
			IdAut: IdAut,
			AzioneDipAut: AzioneDipAut
		};
		console.log(formData);
		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=authorizer&action=modauthdipflu",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				//visualizzo il contenuto del file nel div htmlm
				$('#LoadAutDip' + IdWorkFlow + IdFlusso).empty();
				$('#LoadAutDip' + IdWorkFlow + IdFlusso).html(risposta);
				// $('#InsAuthDip'+IdWorkFlow+IdFlusso).show();

			},

			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("deleteAuthDipFlu: Qualcosa è andato storto!", stato);
			}
		});
	}
}

function addAuthDipFlu(IdWorkFlow,
	WorkFlow,
	IdFlusso,
	TipoDip,
	IdDip,
	Dipendenza,
	Flusso,

	AzioneDipAut) {


	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		TipoDip: TipoDip,
		IdDip: IdDip,
		Dipendenza: Dipendenza,
		Flusso: Flusso,
		IdGroup: $('#AddGroupIn' + IdWorkFlow + IdFlusso + IdDip).val(),
		AzioneDipAut: AzioneDipAut
	};
	console.log(formData);
	console.log('#AddGroupIn' + IdWorkFlow + IdFlusso + IdDip + ": " + $('#AddGroupIn' + IdWorkFlow + IdFlusso + IdDip).val());
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=modauthdipflu",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm

			$('#LoadAutDip' + IdWorkFlow + IdFlusso).html(risposta);
			$('#InsAuthDip' + IdWorkFlow + IdFlusso).show();

			// $('#InsAuthDip'+IdWorkFlow+IdFlusso).show();
			$("#dialogMail").dialog("close");
		},

		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("addAuthDipFlu: Qualcosa è andato storto!", stato);
		}
	});

}



function AddAthIn(IdWorkFlow,
	WorkFlow,
	IdFlusso,
	Flusso,
	Tipo,
	IdDip,
	Dipendenza) {


	var formData = {
		IdWorkFlow: IdWorkFlow,
		WorkFlow: WorkFlow,
		IdFlusso: IdFlusso,
		Flusso: Flusso,
		Tipo: Tipo,
		IdDip: IdDip,
		Dipendenza: Dipendenza
	};
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=authorizer&action=addgrouptodip",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$('#dialogMail').html(risposta);
			// $('#InsAuthDip'+IdWorkFlow+IdFlusso).show();
			$("#dialogMail").dialog("open");
		},

		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("AddAthIn: Qualcosa è andato storto!", stato);
		}
	});

}




function deleteGroupWF(IdWorkFlow, WorkFlow, IdGruppo) {
	var re = confirm('Do you want delete this Group from ' + WorkFlow + ' Flow?');
	if (re == true) {
		loadDettWfs(IdWorkFlow, WorkFlow, 'RG', IdGruppo);
		/*  $('#LoadDett<?php echo $IdWorkFlow; ?>').load('./PHP/Wfs_Gest_DettWfs.php',{
				 IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				 WorkFlow: '<?php echo $WorkFlow; ?>',
				 Azione: 'RG',
				 DelGr: '<?php echo $IdGruppo; ?>'
		  });*/
	}
};


function AddGroupIn(GroupId) {
	if ($('#AddGroupIn' + GroupId).val() != '') {
		$("#AddGrp" + GroupId).show();
	} else {
		$("#AddGrp" + GroupId).hide();
	}
};

function OpenTeam() {

	var formData = {};

	let obj_form = $("form#FormAuthorizer").serializeArray();
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
		url: "index.php?controller=authorizer&action=getAuthorizer",
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div html
			// $('body').html(risposta);
			$('#selIdWorkFlow').select2('destroy');
			$('#selIdWorkFlow').val("");
			$('#LoadWf').html("");

// Ricrea Select2
			$('#selIdWorkFlow').select2();
			$('#selIdWorkFlow option').remove();
			
			$('#selIdWorkFlow').append($('<option>', {
				value: '',
				text: 'Seleziona WorkFlow'
			}));			
			//$('#selIdWorkFlow').selectpicker('refresh');
			const dataAsArray = JSON.parse(risposta);
			console.log(dataAsArray);
			//return false
			var icon;
			var sel;
			$.each(dataAsArray, (index, row) => {
				console.log(row['ID_WORKFLOW']);
				icon = '<i style="color:white" class="fa-regular fa-circle"></i> ';
				if (row['READONLY'] == 'S') icon = '<i style="color:brown" class="fa-regular fa-glasses"></i> ';
				sel = "";
			//	if (IdWorkFlow == row['ID_WORKFLOW']) sel = "selected";
				$('<option ' + sel + '>').val(row['ID_WORKFLOW']).attr('data-content', icon + row['WORKFLOW']).text(row['WORKFLOW']).appendTo('#selIdWorkFlow');
				//$('#selIdWorkFlow').selectpicker('refresh');
			});
			// $("form#FormMain").submit();


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("OpenTeam: Qualcosa è andato storto!", stato);
		}
	});
}



/*
function ModAuthDipFlu(vIdFlu,vFlusso){
		if ( $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).children().length == 0) {
			  $('#TopScroll').val($( window ).scrollTop());
			  $('#BodyHeight').val($('body').height());
			  $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).empty().load('./PHP/Wfs_Gest_LoadAuthDipFlusso.php', {
				IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				WorkFlow: '<?php echo $WorkFlow; ?>',
				IdFlusso: vIdFlu,
				Flusso: vFlusso
			  });
			  $('#InsAuthDip<?php echo $IdWorkFlow; ?>'+vIdFlu).show();
		} else {
			  $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).empty();
			  $('#InsAuthDip<?php echo $IdWorkFlow; ?>'+vIdFlu).hide();
		}
	  }	 */
/* $("#PulAddUser<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>").click(function(){
				 $('#ADDDiv').empty().load('./PHP/Wfs_Gest_AddUser.php',{
					  IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
					  WorkFlow: '<?php echo $WorkFlow; ?>',
					  IdGruppo: '<?php echo $IdGruppo; ?>',
					  Gruppo: '<?php echo $Gruppo; ?>'
				 }).show();
			 }); */





/* $('#PlsAddUserExits').click(function(){
  $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').empty().load('./PHP/Wfs_Gest_LoadUser.php',{
	   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
	   WorkFlow: '<?php echo $WorkFlow; ?>',
	   IdGruppo: '<?php echo $IdGruppo; ?>',
	   IdUsr: $('#SelUser').val(),
	   Azione: 'AU'
  });
  $('#ADDDiv').empty().hide();
});*/