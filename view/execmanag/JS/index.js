$(document).ready(function () {
$('#Waiting').hide();

function resizedw(){
		console.log('resizedw');
		$("textarea").css('height', '90%');
		$("textarea").css('width', '98%');
		$("#Filedialog").dialog("widget").position( { my: "center top+100", at: "center top", of: window });
	}

 $( "#Filedialog" ).dialog({
        autoOpen: false,	
		position: { my: "center top+100", at: "center top", of: window },
        width: ($(window).width()-100),
        height: (window.innerHeight-150),
        modal: true,
		open: function() {
			console.log('open Filedialog');
			console.log('open height'+window.innerHeight);
			
			 $('html, body').css({
				overflow: 'hidden',
				height: 'auto'
			});
			var doit;
			clearTimeout(doit);
			doit = setTimeout(resizedw, 200);
		},
		 close: function() {
			location.reload();
			},
        dialogClass:"dialog-full-mode"
    });	

$( "#dialogMail" ).dialog({
        autoOpen: false,     
		 
        width: 600,
		minHeight:400,
        minHeight : 600,
        modal: true,   
		
		position: { my: "center top+100", at: "center top", of: window },
        close: function() {
			location.reload();
			},
      
    });

}); 