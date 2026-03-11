$(document).ready(function () {
	$('#Waiting').hide();
});


$(".selectSearch").select2();
$('.selectNoSearch').select2({ minimumResultsForSearch: -1 });

function TrDett(vIdSh) {
	if ($('#Dett' + vIdSh).is(':visible')) {
		$('#Dett' + vIdSh).hide();
	} else {
		$('#Dett' + vIdSh).show();
	}
}

function ShDebug(vIdSh, flag, mod) {
	console.log('ShDebug');
	console.log('vIdSh:' + vIdSh);
	console.log('flag:' + flag);
	var re;
	console.log('Sel_EnableDebugSh:' + $("#Sel_EnableDebugSh").val());
	if (mod == 'Y' || $("#Sel_EnableDebugSh").val() != null) {
			if (flag == 'Y') {
			re = confirm('Vuoi abilitare l\'Sh Debug?');
		}
		else {
			re = confirm('Vuoi Disabilitare l\'Sh Debug?');

		}
		console.log('Sel_EnableDebugSh:' + $("#Sel_EnableDebugSh").val());

		if (re == true) {
			var formData = {};
			let obj_form = $("form#FormMail").serializeArray();
			obj_form.forEach(function (input) {
				formData[input.name] = input.value;
			});

			console.log(formData);

			$.ajax({
				type: "POST",
				data: formData,
				encode: true,

				// specifico la URL della risorsa da contattare
				url: "index.php?controller=debug&action=updateShDebug&flag=" + flag + "&idSh=" + vIdSh,
				// imposto l'azione in caso di successo
				success: function (risposta) {
					//visualizzo il contenuto del file nel div htmlm
					$("#contenitore").html(risposta);


				},
				//imposto l'azione in caso di insuccesso
				error: function (stato) {
					errorMessage("ShDebug: Qualcosa è andato storto!", stato);
				}
			});

		} else {
			console.log('else Sel_EnableDebugSh:' + $("#Sel_EnableDebugSh").val());
			$("#Sel_EnableDebugSh").select2("val", "");
		}
	}
}


function DbDebug(vIdSh, flag, mod) {
	console.log('DbDebug');
	console.log('vIdSh:' + vIdSh);
	console.log('flag:' + flag);


	console.log('Sel_EnableDebugDb:' + $("#Sel_EnableDebugDb").val());
	if (mod == 'Y' || $("#Sel_EnableDebugDb").val() != null) {

		var re;
		if (flag == 'Y') {
			re = confirm('Vuoi abilitare il Db Debug?');
		}
		else {
			re = confirm('Vuoi Disabilitare il Db Debug?');

		}

		if (re == true) {
			var formData = {};
			let obj_form = $("form#FormMail").serializeArray();
			obj_form.forEach(function (input) {
				formData[input.name] = input.value;
			});

			console.log(formData);

			$.ajax({
				type: "POST",
				data: formData,
				encode: true,

				// specifico la URL della risorsa da contattare
				url: "index.php?controller=debug&action=updateDbDebug&flag=" + flag + "&idSh=" + vIdSh,
				// imposto l'azione in caso di successo
				success: function (risposta) {
					//visualizzo il contenuto del file nel div htmlm
					$("#contenitore").html(risposta);


				},
				//imposto l'azione in caso di insuccesso
				error: function (stato) {
					errorMessage("DbDebug: Qualcosa è andato storto!", stato);
				}
			});


		} else {
			console.log('else Sel_EnableDebugDb:' + $("#Sel_EnableDebugDb").val());
			$("#Sel_EnableDebugDb").select2("val", "");
		}
	}
}

 function validaDebug() {
    var idSh = $('#ID_SH').val();
    var flagSh = $('#flagSh').is(':checked');
    var flagDb = $('#flagDb').is(':checked');

    if (idSh === ''|| idSh == null) {
      alert('Il campo Shell è obbligatorio.');
      return false;
    }

    if (!flagSh && !flagDb) {
      alert('È obbligatorio selezionare almeno uno tra SH o DB.');
      return false ;
    }
	return true;
    }

function addDebug() {


	
	if (validaDebug()){

	re = confirm('Vuoi abilitare il Debug setting?');

	if ( re == true) {
		var formData = {};
		let obj_form = $("form#FormMail").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		console.log("addDebug",formData);

		$.ajax({
			type: "POST",
			data: formData,
			encode: true,

			// specifico la URL della risorsa da contattare
			url: "index.php?controller=debug&action=updateDb",
			// imposto l'azione in caso di successo
			success: function (risposta) {
				console.log("addDebug","OK");
				//visualizzo il contenuto del file nel div htmlm
				$("#contenitore").html(risposta);


			},
			//imposto l'azione in caso di insuccesso
			error: function (stato) {
				errorMessage("DbDebug: Qualcosa è andato storto!", stato);
			}
		});


	} else {
		return false;
	}
}
}


/*   $('#Sel_EnableDebugSh').change(function(){ 
	  var Val=$('#Sel_EnableDebugSh').val();
	  if ( Val != '' ){
		ShDebug(Val, "Y");
	  }
   });	 
   
   $('#Sel_EnableDebugDb').change(function(){ 
	  var Val=$('#Sel_EnableDebugDb').val();
	  if ( Val != '' ){
		DbDebug(Val, "Y");
	  }
   });   */



$('#idTabella').DataTable({
	order: [],
	language: {
		"url": "./JSON/italian.json"
	},
	"lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
});