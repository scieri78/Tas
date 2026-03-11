$(document).ready(function () {
    $('#Waiting').hide();

});



function getPlanDigitWave(WAVE) {


	var formData = {
		SelAnno: $('#SelAnno').val()		
	};


	console.log('getPlanDigitWave action=getPlanDigitWave ');
	console.log(formData);
	$.ajax({
		type: "POST",
		data: formData,
		encode: true,

		// specifico la URL della risorsa da contattare
		url: "index.php?controller=openLink_SetLobMapPlanDigit&action=getPlanDigitWave",
		// imposto l'azione in caso di successo
		success: function (risposta) {		
		

			const dataAsArray = JSON.parse(risposta);
			console.log(dataAsArray);
			var icon;
			var sel;
            $('#SelWave option').remove();
			$.each(dataAsArray, (index, row) => {
				console.log(row['ID_WORKFLOW']);
              //  p.WAVE, d.DESCR
			//	icon = '<i style="color:white" class="fa-regular fa-circle"></i> ';
			//	if (row['READONLY'] == 'S') icon = '<i style="color:brown" class="fa-regular fa-glasses"></i> ';
				sel = "";
			if (WAVE == row['WAVE']) sel = "selected";
            var decr = (row['DESCR'] == null || row['DESCR'] == undefined) ? row['WAVE'] : row['WAVE'] + " - " + row['DESCR'];
				$('<option ' + sel + '>').val(row['WAVE']).attr('data-content', row['WAVE']).text(decr).appendTo('#SelWave');
			//	$('#WAVE').selectpicker('refresh');
			});


		},
		//imposto l'azione in caso di insuccesso
		error: function (stato) {
			errorMessage("getlegamiflussi: Qualcosa è andato storto!", stato);
		}
	});
}

