$(".selectSearch").select2();
	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
$.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
}; 

	function AddAction(vaction,vIdSh, vType){
		vIdMail=$('#xAddSelMail'+vIdSh+vType).val();
		
		console.log('AddAction');
		var formData = {};
		formData['id'] = vIdSh;
		formData['SelMail'] = vIdSh+';'+vType+';'+vIdMail;
		formData['action'] = vaction;
		console.log(formData);
		console.log('controller=statoshell&action=apriFile');
	  
		$.ajax({
		  type: "POST",
		  data: formData,
		  encode: true, 	  
           
          // specifico la URL della risorsa da contattare
         //url: "index.php?controller=mailconf&action="+vaction+"&id="+vIdSh+"&SelMail="+vIdSh+';'+vType+';'+vIdMail+"",  
          url: "index.php?controller=mailconf&action="+vaction,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			refreshMail();
			//$( "#dialogMail" ).dialog({title: SHELL});
			$("#dialogMail").dialog("open");
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		 
	 
	 }
	 
	 
	 function RemoveMail(vIdSh, vType, vIdMail){
		
		 var re = confirm('Are you sure you want to Remove this Mail?');
           if ( re == true) {
			console.log('RemoveMail');
			var formData = {};
			formData['id'] = vIdSh;
			formData['SelMail'] = vIdSh+';'+vType+';'+vIdMail;
			//formData['action'] = vaction;
			console.log(formData);
			console.log('controller=mailconf&action=RemoveMail');
		  
			$.ajax({
			  type: "POST",
			  data: formData,
			  encode: true,	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=mailconf&action=RemoveMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			refreshMail();
			//$( "#dialogMail" ).dialog({title: SHELL});
			$("#dialogMail").dialog("open");
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		 
}
	 }
	 
	 
	 function UpdateMail(Mval,vIdSh, vType, vIdMail){
		   var mess='';
		   var re ='';
		   if( Mval == 'N')	
		   {
		  re = confirm('Are you sure you want to Disable the Mails?');
		   }
		else{
		   re = confirm('Are you sure you want to Enable the Mails?');
		}
	  if ( re == true) {  
		    console.log('UpdateMail');
			var formData = {};
			formData['mval'] = Mval;
			formData['id'] = vIdSh;
			formData['SelMail'] = vIdSh+';'+vType+';'+vIdMail;
			formData['action'] = 'UpdateMail';
			console.log(formData);
			console.log('controller=statoshell&action=apriFile');
		  
			$.ajax({
			  type: "POST",
			  data: formData,
			  encode: true,		  
           
          // specifico la URL della risorsa da contattare
        //  url: "index.php?controller=mailconf&action=UpdateMail&mval="+Mval+"&id="+vIdSh+"&SelMail="+vIdSh+';'+vType+';'+vIdMail+"",  
          url: "index.php?controller=mailconf&action=UpdateMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			refreshMail();
			//$( "#dialogMail" ).dialog({title: SHELL});
			$("#dialogMail").dialog("open");
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		 
	}
	 }
	   
	   function xRemoveMail(vIdSh, vType, vIdMail){
           var re = confirm('Are you sure you want to Remove this Mail?');
           if ( re == true) {  		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "SelMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMailDati').append($(input));
			var input2 = $("<input>")
				.attr("type", "hidden")
				.attr("name", "action")
				.val("RemoveMail");
				$('#FormMailDati').append($(input2));
			$('#FormMailDati').submit();		   
		   }
	   }


		function xAddAction(vaction,vIdSh, vType){
		    vIdMail=$('#xAddSelMail'+vIdSh+vType).val();
		    if ( vIdMail != '' ){
				var input = $("<input>")
				.attr("type", "hidden")
				.attr("name", "SelMail")
				.val(vIdSh+';'+vType+';'+vIdMail);
				$('#FormMailDati').append($(input));
				var input2 = $("<input>")
				.attr("type", "hidden")
				.attr("name", "action")
				.val(vaction);
				$('#FormMailDati').append($(input2));
				$('#FormMailDati').submit();
			}
	   }	


  function xUpdateMail(Mval,vIdSh, vType, vIdMail){
		   var mess='';
		   var re ='';
		   if( Mval == 'N')	
		   {
		  re = confirm('Are you sure you want to Disable the Mails?');
		   }
		else{
		   re = confirm('Are you sure you want to Enable the Mails?');
		}
           if ( re == true) {  
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "SelMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMailDati').append($(input));
			var input2 = $("<input>")
				.attr("type", "hidden")
				.attr("name", "action")
				.val("UpdateMail");
				$('#FormMailDati').append($(input2));
			var input3 = $("<input>")
				.attr("type", "hidden")
				.attr("name", "mval")
				.val(Mval);
				$('#FormMailDati').append($(input3));
			$('#FormMailDati').submit();
		   }
	   }	

	   function refreshMail() {
		console.log('refreshMail');
		
		$('#Waiting').show();
		  var formData = {};
	
		 
		  formData['refreshMail'] = 'refreshMail';
		  console.log(formData);
	
		  $.ajax({
			type: "POST",
			data: formData,
			encode: true,
	
			// specifico la URL della risorsa da contattare
			url: "index.php?controller=mailconf&action=contentList",
			// imposto l'azione in caso di successo
			success: function(risposta) {
			  //visualizzo il contenuto del file nel div htmlm
			  //  $("#shellContent").html("");
			  $("#contenitore").html(risposta);
			  $('#Waiting').hide();
			 
	
			},
			//imposto l'azione in caso di insuccesso
			error: function(stato) {
			  alert("Qualcosa è andato storto");
			}
		  }).done(function(data) {
	
	
		  });
	
		  //  Refresh();
		
	  }