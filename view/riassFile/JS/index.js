$(document).ready(function () {
$('#Waiting').hide();

}); 

 function Ricarica(vAnnoMese,vId,vTipo){
		var re = confirm('Are you sure you want Reload this file?');
        if ( re == true) {
            alert('Ricarico '+vAnnoMese+' '+vId+' '+vTipo);	 
	 
	        var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RicPeriod")
            .val(vAnnoMese);
            $('#FormSapBw').append($(input));
			
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RicId")
            .val(vId);
            $('#FormSapBw').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RicTipo")
            .val(vTipo);
            $('#FormSapBw').append($(input));

            $('#FormSapBw').submit();
		}
 }
 