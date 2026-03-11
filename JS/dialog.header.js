$(document).ready(function () {

	function resizedw() {
		console.log('resizedw');
		$("#Filedialog textarea").css('height', '90%');
		$("#Filedialog textarea").css('width', '98%');
		$("#Filedialog").dialog("widget").position({ my: "center top+100", at: "center top", of: window });
	}

	//	$(".selectSearch").select2();
	//	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
	toolTip = $(".button").tooltip({
		position: { at: "bottom" }
	});
	$("#dialogMail").dialog({
		autoOpen: false,

		width: 600,
		minHeight: 400,
		minHeight: 600,
		modal: true,

		position: { my: "center top+100", at: "center top", of: window }

	});

	$("#Filedialog").dialog({
		autoOpen: false,
		position: { my: "center top+100", at: "center top", of: window },
		width: ($(window).width() - 100),
		height: (window.innerHeight - 150),
		modal: true,
		open: function () {
			console.log('open Filedialog');
			console.log('open height' + window.innerHeight);

			$('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
			var doit;
			clearTimeout(doit);
			//doit = setTimeout(resizedw, 200);
		},
		close: function () {
			console.log('close Filedialog');
			$('html, body').css({
				overflow: 'visible',
				height: 'auto'
			});
			$("#Filedialog").empty();
		},
		dialogClass: "dialog-full-mode"
	});


	$("#Filedialog2").dialog({
		autoOpen: false,


		width: ($(window).width() - 70),
		height: ($(window).height() - 250),
		modal: true,
		open: function () {
			$('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
			var doit;
			clearTimeout(doit);
			doit = setTimeout(resizedw, 200);
		},
		close: function () {
			$('html, body').css({
				overflow: 'visible',
				height: 'auto'
			});
			$("#Filedialog2").empty();
		},
		dialogClass: "dialog-full-mode"
	});


	$("#Bilderdialog1").dialog({
		autoOpen: false,
		position: { my: "left top", at: "left+10 top", of: "body" },

		width: (($(window).width() / 2) - 200),
		height: 'auto',
		modal: false,
		open: function () {
			$('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
			var doit;
			clearTimeout(doit);
			doit = setTimeout(resizedw, 200);
		},
		close: function () {
			$('html, body').css({
				overflow: 'visible',
				height: 'auto'
			});
			$("#Filedialog").empty();
		},
		dialogClass: "dialog-full-mode"
	});


	$("#Bilderdialog2").dialog({
		autoOpen: false,
		position: { my: "left top", at: "left+" + (($(window).width() / 2) - 150) + " top+100", of: "body" },

		width: (($(window).width() / 2) - 200),
		height: 'auto',
		modal: false,
		open: function () {
			$('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
			var doit;
			clearTimeout(doit);
			doit = setTimeout(resizedw, 200);
		},
		close: function () {
			$('html, body').css({
				overflow: 'visible',
				height: 'auto'
			});
			$("#Filedialog").empty();
		},
		dialogClass: "dialog-full-mode"
	});


	$("#SHdialog").dialog({
		autoOpen: false,


		width: ($(window).width() - 100),
		height: ($(window).height() - 150),
		modal: true,
		open: function () {
			$('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
		},
		close: function () {
			$('html, body').css({
				overflow: 'visible',
				height: 'auto'
			});
			$("#SHdialog").empty();
		},
		dialogClass: "dialog-full-mode"
	});


	$(document).dialogfullmode();


	$(window).on("resize", function () {
		console.log('window resize Filedialog');
		var doit;
		clearTimeout(doit);
		doit = setTimeout(resizedw, 500);

		$("#Filedialog").css('height', ($("#Filedialog").parents().height() - 35));
		$("#Filedialog").css('width', ($("#Filedialog").parents().width() - 5));

		/*	$( "#Filedialog" ).dialog( "option", "width", ($(window).width()-100)) ;
			$( "#Filedialog" ).dialog( "option", "height", ($(window).height()-150)) ;	
			$( "#SHdialog" ).dialog( "option", "width", ($(window).width()-100)) ;
			$( "#SHdialog" ).dialog( "option", "height", ($(window).height()-150)) ;*/
	});



	$("button.ui-button-fullscreen").on("click", function () {

		$('.ui-dialog').css('zIndex', 100006);
		var doit;
		clearTimeout(doit);
		doit = setTimeout(resizedw, 200);
	})



});


function setTextarea() {
	console.log('setTextarea');
	$("textarea").css('height', '90%');
	$("textarea").css('width', '98%');
}



function copy() {
	$("textarea").select();
	document.execCommand('copy');

}



function changeUserForm() {
	console.log('changeUserForm');
	console.log('controller=login&action=changeUserForm"');
	var formData = {};
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,
		// specifico la URL della risorsa da contattare
		// url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql,
		url: "index.php?controller=login&action=changeUserForm",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#dialogMail").html(risposta);
			$("#dialogMail").dialog({ title: "Change User" });
			$("#dialogMail").dialog("open");

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("changeUser: Qualcosa è andato storto!", stato);
		}
	});


}
function changeUser() {
	console.log('changeUser');
	console.log('controller=login&action=changeUser"');
	var formData = {};
	let obj_form = $("form#formChangeUser").serializeArray();
	obj_form.forEach(function (input) {
		formData[input.name] = input.value;
	});

	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,
		// specifico la URL della risorsa da contattare
		// url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql,
		url: "index.php?controller=login&action=changeUser",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm

			$("#dialogMail").dialog("close");
			location.reload();

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("changeUser: Qualcosa è andato storto!", stato);
		}
	});


}


function exitUser() {
	console.log('exitUser');
	console.log('controller=login&action=exitUser"');
	var formData = {};


	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,
		// specifico la URL della risorsa da contattare
		// url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql,
		url: "index.php?controller=login&action=exitUser",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm

			//$("#dialogMail").dialog("close");
			location.reload();

		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("changeUser: Qualcosa è andato storto!", stato);
		}
	});


}
function getParameterByName(name) {
	var url = window.location.href;
	// Crea un oggetto URL
	var urlObj = new URL(url);
	// Ottieni i parametri della query string
	var params = new URLSearchParams(urlObj.search);
	// Esempio: ottieni il valore di una variabile GET chiamata "id"
	var parameter = params.get(name);
	return parameter;
}


function downloadfile(filename, $filedir) {
	var ShowVar = 0;
	//clearAutoRefresh();
	if ($("input#ShowVar:checked").val() == 1) { ShowVar = 1 };
	console.log('downloadfile');
	var formData = {};
	formData['filename'] = filename;
	//formData['SHFILE']=SHFILE;
	//formData['action']='setManual';  
	console.log(formData);
	window.open("index.php?sito=" + getParameterByName('sito') + "&controller=statoshell&action=downloadfile&filename=" + filename + "&filedir=" + $filedir);
}


function openSqlFile(vIdSql, SHFILE) {

	console.log('openSqlFile');
	// clearAutoRefresh();
	var formData = {};
	formData['IDSQL'] = vIdSql;
	//formData['Descr'] = vDescr;
	formData['SHFILE'] = SHFILE;
	//formData['action']='setManual';  
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		// url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql,
		url: "index.php?controller=statoshell&action=apriSqlFile",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#Filedialog").html(risposta);

			$("#Filedialog").dialog({ title: "FILE: " + SHFILE });
			$("#Filedialog").dialog("open");
			setTimeout(setTextarea, 500);
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("openSqlFile: Qualcosa è andato storto!", stato);
		}
	});


}





function reOpenSqlFile(vIdSql) {
	var ShowVar = 0;
	//clearAutoRefresh();
	if ($("input#ShowVar:checked").val() == 1) { ShowVar = 1 };
	console.log('reOpenSqlFile');
	var formData = {};
	formData['IDSQL'] = vIdSql;
	formData['ShowVar'] = ShowVar;
	//formData['SHFILE']=SHFILE;
	//formData['action']='setManual';  
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		// url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL=" + vIdSql + "&ShowVar=" + ShowVar,
		url: "index.php?controller=statoshell&action=apriSqlFile",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			//visualizzo il contenuto del file nel div htmlm
			$("#Filedialog").html(risposta);
			setTimeout(setTextarea, 200);
			//$("#Filedialog").dialog({title:"FILE: "+SHFILE});
			//$("#Filedialog").dialog("open");	
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("reOpenSqlFile: Qualcosa è andato storto!", stato);
		}
	});


}




function errorMessage(errMess, stato) {
	console.log('errorMessage 2a');
	var formData = {};
	formData['stato'] = stato;
	formData['errMess'] = errMess;
	console.log(formData);
	// $('#MainForm').submit();
	/*$.ajax({
		type: "POST",
		data: formData,
		encode: true,
 
		// specifico la URL della risorsa da contattare
		url: "index.php?controller=home&action=errorLog",
		// imposto l'azione in caso di successo
		success: function (risposta) {
			alert(risposta);
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("CreaRilascio: Qualcosa è andato storto!",stato);
		}
	});*/
	location.reload();
}


function refreshPage() {
	console.log('refreshPage');

	location.reload();
}

function consolelogFunction(mess) {
	if ($("#server_name").val()!='PROD')
	{console.log(mess);}
		
}

function openWindowWithPost(url, data, windowName) {
	var form = $('<form>')
		.attr('method', 'POST')
		.attr('action', url + "&sito=" + getParameterByName('sito'))
		.attr('target', windowName);

	$.each(data, function (key, value) {
		$('<input>')
			.attr('type', 'hidden')
			.attr('name', key)
			.attr('value', value)
			.appendTo(form);
	});

	// Aggiungi il modulo al body
	form.appendTo('body');

	// Apri una nuova finestra
	window.open('', windowName);

	// Invia il modulo
	form.submit();

	// Rimuovi il modulo dal DOM
	form.remove();
}


function openDialog(vIdSh, SHELL, vaction) {

	console.log('openDialog');
	var formData = {};
	formData['IDSH'] = vIdSh;

	// clearAutoRefresh();
	// formData['action']=vaction;
	// formData['SHELL']=SHELL;
	console.log(formData);

	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=statoshell&action=" + vaction,
		// imposto l'azione in caso di successo
		success: function (risposta) {

			//visualizzo il contenuto del file nel div htmlm


			//$("#Filedialog").dialog({title: SHELL});
			$("#Filedialog").dialog("open");
			$("#Filedialog").html(risposta);
			setTimeout(setTextarea, 500);

			var Log = $("#Log").val();
			var idhs = $("#IDHS").val();
			var SHELLLOG = "LOG: (" + idhs + ") " + Log;
			var SHELLFILE = "SHELL: (" + idhs + ") " + Log;
			if (vaction == 'apriFile') {
				SHELL2 = SHELLFILE;
			} else {
				SHELL2 = SHELLLOG;
			}
			console.log(SHELL2);
			$("#Filedialog").dialog({ title: SHELL2 });
		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("openDialog: Qualcosa è andato storto!", stato);
		}
	});


}
