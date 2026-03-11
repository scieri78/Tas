$(document).ready(function () {
$('#Waiting').hide();

}); 



OpenAuthId($('#SelAgent').val());

	function OpenAuthId(vAgent) {
		$('.AllRighe').each(function() {
			$(this).hide()
		});
		$('.Intest').hide();

		vOld = $('#SelAgent').val();
		$('.Riga' + vOld).each(function() {
			$(this).hide()
		});
		$('.Intest').hide();
		$('#SelAgent').val(vAgent);
		$('.Riga' + vAgent).each(function() {
			$(this).show()
		});
		$('.Intest').show();
	}

	function KillAgent(vAgent) {
        $('#Waiting').show();
        console.log('KillAgent');
		var re = confirm('Are you sure you want kill this agent id?');
		if (re == true) {
            var formData = {};
            formData['KillAgent']=vAgent;
            console.log(formData);
			$.ajax({
                type: "POST",
                 data: formData,
                 encode: true,	  
                 
                // specifico la URL della risorsa da contattare
                url: "index.php?controller=look&action=KillAgent",  
                // imposto l'azione in caso di successo
                success: function(risposta){
                //visualizzo il contenuto del file nel div htmlm
                   $("#contenitore").html(risposta);
                   $('#Waiting').hide();
   
                },
                //imposto l'azione in caso di insuccesso
                error: function(stato){
                   errorMessage("ShMultiSend: Qualcosa è andato storto!", stato);
               }
             });
		}
	}