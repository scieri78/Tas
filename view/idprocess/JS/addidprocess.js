    function AddSveccIdp(vId){ 
	    var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "AddSvecc")
        .val(vId);
        $('#FormMain').append($(input));     			
		$('#Waiting').show();
        $('#FormMain').submit(); 	
	}
	
    function RemSveccIdp(vId){ 
	    var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "RemSvecc")
        .val(vId);
		$('#FormMain').append($(input));     			
		$('#Waiting').show();
        $('#FormMain').submit(); 	
	}
	
    function Testsave(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
              
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
       $('.FieldMandRem').each(function(){
           $(this).val('');
       });
       
       var vSave=true;
       $('.FieldMand').each(function(){
           if ( $(this).val() == '' ){ vSave=false; }
       });
       
       if ( vSave ){
         $('#SaveIdProcess').show();
       } 
       
    }

    function RimuoviIdProcess(){        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Azione")
        .val('Rimuovi');
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                            
    }

    function SaveIdProcess(){
        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Azione")
        .val('Aggiungi');
        $('#FormMain').append($(input));
        $('#FormMain').submit();
                    
        $('#Waiting').show();
        $('#FormMain').submit();
    }
       
    function CopyIdProcess(){
           
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "Azione")
      .val('Copia');
      $('#FormMain').append($(input));
      $('#FormMain').submit();
                  
      $('#Waiting').show();
      $('#FormMain').submit();
    }   
    
    function ShowCopy(){
           
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ShowStatusCopy")
      .val('ShowStatusCopy');
      $('#FormMain').append($(input));
                 
      $('#Waiting').show();
      $('#FormMain').submit();
      $('#FormMain').submit();
    }   
    
       
    function RemoveReadOnly(vIdProc){        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "RemoveRO")
        .val(vIdProc);
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                            
    }
    
    function AddReadOnly(vIdProc){
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "AddRO")
        .val(vIdProc);
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                 
    }

    
   $('#Esercizio').change(function(){
       $('#Descr').val('Chiusura '+$('#Esercizio').val());
   });

   $('#Tipo').change(function(){
       $('#Descr').val($('#Descr').val()+' ['+$(this).val()+']');
   });
    
   $('.FieldMand').change(function(){
      Testsave();
   });           
   $('.FieldMand').keyup(function(){
      Testsave();
   });     
   
         

    function TestsaveCopy(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
      
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       
       $('.FieldMandRem').each(function(){
           $(this).val('');
       });
       
       var vSave=true;
       var vShow=false;
       $('.FieldMandCopy').each(function(){
           if ( $(this).val() == '' ){ vSave=false; } else { vShow=true; }
       });
       
       if ( $('#FromId').val() == $('#ToId').val() ){
           vSave=false;
       }
       
       if ( vSave ){
         $('#CopyIdProcess').show();
       }
       
       if ( vShow ){
         $('#ShowCopy').show();
       }
    }

   $('.FieldMandCopy').change(function(){
      TestsaveCopy();
   });           
   $('.FieldMandCopy').keyup(function(){
      TestsaveCopy();
   });     
   TestsaveCopy();

   $('#SvuotaId').change(function(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
      $('#CancellaId').val('');
      $('#RimuoviIdProcess').hide();
      if ( $('#SvuotaId').val() != '' ){ $('#RimuoviIdProcess').show(); }
   });

   $('#CancellaId').change(function(){ 
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
      $('#SvuotaId').val('');
      $('#CancellaId').change(function(){ });
      $('#RimuoviIdProcess').hide();
      if ( $('#CancellaId').val() != '' ){ $('#RimuoviIdProcess').show(); }
   });   
   
   $("#Waiting").hide();
   
   $('#idTabella').DataTable({
		"paging": false,
		"searching": false,

		language: {
					"url": "./JSON/italian.json"
				  },
				  order: [], 
				 /* "lengthMenu": [ [-1, 10, 25, 50,100], ["All",10, 25, 50,100] ]*/
						columnDefs: [
					{ orderable: false, targets: [0] }
				  ]
                });
   