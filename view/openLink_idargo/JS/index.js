$(document).ready(function () {
	$('#Waiting').hide();


});


function formUpdateArgo(ID_PROCESS,COMPAGNIA){
	console.log('formUpdateArgo');
	var formData = {};

	let obj_form = $("form#formUpdateArgo").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		obj_form = $("form#openLinkPage").serializeArray();
		obj_form.forEach(function(input) {
			formData[input.name] = input.value;
		});  
	
    formData['ID_PROCESS'] = ID_PROCESS;
    formData['COMPAGNIA'] = COMPAGNIA;

	console.log(formData);	
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=openLink_idargo&action=formUpdateArgo",
		// imposto l'azione in caso di successo
		success: function (risposta) {
            $("#dialogMail").dialog({ title: "Form Modifica IdArgo"});
            $("#dialogMail").dialog("open");
            $('#dialogMail').html(risposta);
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("CreaRilascio: Qualcosa è andato storto!",stato);
		}
	});
	
   }

function ModIdArgo(errMess,stato){
	console.log('ModIdArgo');
	var formData = {};

		let obj_form = $("form#formUpdateArgo").serializeArray();
		obj_form.forEach(function (input) {
			formData[input.name] = input.value;
		});

		obj_form = $("form#openLinkPage").serializeArray();
		obj_form.forEach(function(input) {
			formData[input.name] = input.value;
		});  
	
	obj_form = $("form#MainForm").serializeArray();
		obj_form.forEach(function(input) {
		  formData[input.name] = input.value;
		});
    console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=openLink_idargo&action=updateArgo",
		// imposto l'azione in caso di successo
		success: function (risposta) {			
            $("#dialogMail").dialog("close");
            $('#Filedialog').html(risposta);
          
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("CreaRilascio: Qualcosa è andato storto!",stato);
		}
	});
	
   }


   function ValidaLegame(){
	console.log('ValidaLegame');
	var formData = {};
		

		let obj_form = $("form#openLinkPage").serializeArray();
		obj_form.forEach(function(input) {
			formData[input.name] = input.value;
		});  
	
	obj_form = $("form#MainForm").serializeArray();
		obj_form.forEach(function(input) {
		  formData[input.name] = input.value;
		});
    console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=openLink_idargo&action=ValidaLegame",
		// imposto l'azione in caso di successo
		success: function (risposta) {			
            $("#Filedialog").dialog("close");
          //  $('#dialogMail').html(risposta);
		    RefreshPage();
          
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("CreaRilascio: Qualcosa è andato storto!",stato);
		}
	});
	
   }


