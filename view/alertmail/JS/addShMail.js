
$.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
};
	$(".selectSearch").select2();

	  function UpdateSHMail(IDSH,IDANAGMAIL,TYPEMAIL,FLAG){
				
      console.log("UpdateSHMail");
      var formData = {
        id: IDSH,
        IDANAGMAIL: IDANAGMAIL,
        TYPEMAIL: TYPEMAIL,
        FLAG: FLAG
      };
    
      console.log(formData);
    
      $.ajax({
        type: "POST",
        data: formData,
        encode: true,	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=updateShMail",
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		} 


    function refreshMail(){
		
      console.log('refreshMail');
      var formData = {
        refreshMail: 'refreshMail'        
      };
      console.log("ShowAggiungiFlusso");
      console.log(formData);
    
      $.ajax({
        type: "POST",
        data: formData,
        encode: true,	
         // specifico la URL della risorsa da contattare
         url: "index.php?controller=alertmail&action=contentList",  
         // imposto l'azione in caso di successo
         success: function(risposta){
         //visualizzo il contenuto del file nel div htmlm
         $("#contenitore").html(risposta);
         },
         //imposto l'azione in caso di insuccesso
         error: function(stato){
          console.log('ShMultiSend');
         }
       });
    
}
  
		
		function RemoveMail(IDSH,IDANAGMAIL){
      console.log('RemoveMail');
     
      var formData = {
        id: IDSH,
        IDANAGMAIL: IDANAGMAIL       
      };
    
      console.log(formData);
    
      $.ajax({
        type: "POST",
        data: formData,
        encode: true,	   
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=RemoveMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
            refreshMail();

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		} 
		
		
		function AddInNewMail(){
				
			var ID_SH=	$('#AddInShMail').val();
			var ID_MAIL_ANAG=	$('#AddInMail').val();
			var FLG_START=	$('#AddInStart').val();
			var FLG_END=	$('#AddInEnd').val();
			
		  var formData = {
        id: ID_SH,
        ID_MAIL_ANAG: ID_MAIL_ANAG,    
        FLG_START: FLG_START,     
        FLG_END: FLG_END       
      };
    
      console.log(formData);
    
      $.ajax({
        type: "POST",
        data: formData,
        encode: true,	   
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=alertmail&action=addInShMail",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#dialogMail").html(risposta);
			

          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		}
		
