$(document).ready(function () {
$('#Waiting').hide();


      
var vListP=$('#ListOpenP').val();
var Parray = vListP.split(',');
for ( i=0; i <= Parray.length; i++) {
   var vIdDep = Parray[i];
   if ( vIdDep != '' ) { $('.P'+vIdDep).show(); }
}

function OpenDep(vIdDep){
  if ( $('.P'+vIdDep) != null ){
    if ( $('.P'+vIdDep).is(':visible') ){
      $('.P'+vIdDep).hide();
      var vttx = $('#ListOpenP').val();
      vttx = vttx.replace(','+vIdDep,'');
      $('#ListOpenP').val(vttx);
    }else{
      $('.P'+vIdDep).show();
      var vttx = $('#ListOpenP').val();
      vttx = vttx+','+vIdDep;
      $('#ListOpenP').val(vttx);
    }
  }
}

function OpenCoda(){
	
    $.ajax({
      method: "get",	  
       
      // specifico la URL della risorsa da contattare
        //url: './PHP/ExecManag_MostraStorico.php',
        url: 'index.php?controller=execmanag2&action=codaexec',
      // imposto l'azione in caso di successo
      success: function(risposta){
         
      //visualizzo il contenuto del file nel div htmlm
        
    
        $("#dialogMail").dialog({title: 'IN CODA'});
        $("#dialogMail").dialog("open");	
        $("#dialogMail").html(risposta);
        $('#Waiting').hide();
        //$('#ShowDataElab').hide();
      },
      //imposto l'azione in caso di insuccesso
      error: function(stato){
        alert("Qualcosa è andato storto");
      }
       });
/*  if ( $('#DivCodaExec').css('display') == 'none' ){
  $('#DivCodaExec').empty().load('./view/execmanag/CodaExec.php');
  $('#DivCodaExec').show();
} else {
  $('#DivCodaExec').hide();
}*/
}

$('#DivCodaExec').empty().load('./view/execmanag2/CodaExec.php');




$('#CancelEdit').click(function(){
$('#Waiting').show();
$('#FormScheduler').submit();
});

$('#CancelNew').click(function(){
$('#Waiting').show();
$('#FormScheduler').submit();
});

function Refresh(){
$('#Waiting').show();
$('#FormScheduler').submit();
};

$('#Waiting').hide();

$('#SelDescrGroup').keyup(function(){
var vtext=$('#SelDescrGroup').val();  
vtext=vtext.replace('"','\'\'');
$('#SelDescrGroup').val(vtext);   
});

$('#FormScheduler').submit(function(){
  $('#TopScrollG').val($(window).scrollTop());  
});     

$(window).scrollTop($('#TopScrollG').val());




}); 



function mostraStorico(){
	
	
	$.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
            //url: './PHP/ExecManag_MostraStorico.php',
			url: 'index.php?controller=execmanag2&action=mostrastorico',
          // imposto l'azione in caso di successo
          success: function(risposta){
			 
          //visualizzo il contenuto del file nel div htmlm
            
		
			$("#Filedialog").dialog({title: 'STORICO ULTIMI 60 GIORNI'});
			$("#Filedialog").dialog("open");	
			$("#Filedialog").html(risposta);
			$('#Waiting').hide();
			//$('#ShowDataElab').hide();
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
		   });
    /* if (  $('#MostraStorico').css('display') == 'none' ){
	   $( "#MostraStorico" ).empty().load('./PHP/ExecManag_MostraStorico.php').show();
	 }else{
	   $( "#MostraStorico" ).empty().hide();	 
	 }*/
 }; 


 function DeleteGroup(vIdEmGroup){
    TestNoAction();
    var re = confirm('Are you sure you want Delete this object?');
    if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "DeleteGroup")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
    }
    }
    
    function EditGroup(vIdEmGroup){
    TestNoAction();
    var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "EditGroup")
    .val(vIdEmGroup);
    $('#FormScheduler').append($(input));
    $('#FormScheduler').submit();
    }
    
    function AddGroup(vIdEmGroup){
    TestNoAction();
    var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "AddGroup")
    .val(vIdEmGroup);
    $('#FormScheduler').append($(input));   
    $('#FormScheduler').submit();
    }
    
    function ChangeStatus(vIdEmGroup,vStatus){  
    TestNoAction();
    var re = confirm('Are you sure you want change status of this object?');
    if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "ChangeStatus")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "OldStatus")
        .val(vStatus);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
    
    }
    }
    
    function Power(vIdEmGroup,vStatus){
    TestNoAction();
    var re = confirm('Are you sure you want Enable/Disable of this group?');
    if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Power")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "PowerStatus")
        .val(vStatus);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
    
    }
    }
    
    function DownGroup(vIdEmGroup,vOldPriority,vNewPriority){
    TestNoAction();
    if ( vNewPriority != 0 ){
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ChangePriority")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "OldPriority")
      .val(vOldPriority);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "NewPriority")
      .val(vNewPriority);
      $('#FormScheduler').append($(input));
    
      $('#FormScheduler').submit();
    }
    }
    
    function UpGroup(vIdEmGroup,vOldPriority,vNewPriority){
      TestNoAction();
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ChangePriority")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "OldPriority")
      .val(vOldPriority);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "NewPriority")
      .val(vNewPriority);
      $('#FormScheduler').append($(input));
    
      $('#FormScheduler').submit();
    }
    
    
    function OpenGroup(vIdEmGroup,vNameGroup){
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "IdEmGroup")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "NameGroup")
      .val(vNameGroup);
      $('#FormScheduler').append($(input));
    
      $('#FormScheduler').attr('action','./index.php?controller=execmanag2&action=ExecManag')
    
      $('#FormScheduler').submit();     
    }
    
    function DepRun(vIdEmGroup){
    TestNoAction();
    var re = confirm('Are you sure you want to set all objects in this group to "Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "DepRun")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('N');
      $('#FormScheduler').append($(input));   
      
      $('#FormScheduler').submit(); 
    }
    }
    
    
    function DepNoRun(vIdEmGroup){
    TestNoAction();
    var re = confirm('Are you sure you want to set all objects in this group to "No Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "DepNoRun")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('N');
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('N');
      $('#FormScheduler').append($(input));
      
      $('#FormScheduler').submit(); 
    }
    }
    
    function RTecOn(vIdEmGroup){
    TestNoAction();
    var re = confirm('Are you sure you want to set all recursive objects/groups in this group to "Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecOn")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('Y');
      $('#FormScheduler').append($(input));   
      
      $('#FormScheduler').submit(); 
    }
    }
    
    
    function RTecOff(vIdEmGroup){
    TestNoAction();
    var re = confirm('Are you sure you want to set all recursive objects/groups in this group to "No Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecOff")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('N');
      $('#FormScheduler').append($(input));
    
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      $('#FormScheduler').submit(); 
    }
    }
    