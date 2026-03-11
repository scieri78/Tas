$(document).ready(function () {
$('#Waiting').hide();
var dt =	$('#idTabella').DataTable({
	//columns: [],
	//page:1,
	autoWidth : false,
	language: {
				"url": "./JSON/italian.json"
			  },
			  "lengthMenu": [ [-1,10, 25, 50,100], ["All",10, 25, 50,100] ],
			  responsive: true,
			  columnDefs: [{ width: 400, targets: 11 },{ orderable: false, targets: [2] }] 
});	
	
$(".selectSearch").select2();
$('.selectNoSearch').select2({minimumResultsForSearch: -1});

$('#myCheck1').change(function() {
	dt.columns([3, 5, 6,7,8,9,12,13,14]).visible($(this).is(':checked'), $(this).is(':checked'));
	
	dt.columns.adjust().draw(false);
	
	if($(this).is(':checked'))
	{
	$('.imgFileSQL').hide();
	$('#idTabella').css('padding-right', ('25px')); 
	}else{
		$('.imgFileSQL').show();
		$('#idTabella').css('padding-right', ('0px')); 
	}
});


  dt.columns([3, 5, 6,7,8,9,12,13,14]).visible(false, false);
  
  dt.columns.adjust().draw(false);
  $('#idTabella').css('width', ('100%')); 
});

 
 // adjust column sizing and redraw

function OpenShell(IDSELEM, NAME_RETE){
		 $.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=statoshell&action=contentList&IDSELEM="+IDSELEM,  
          // imposto l'azione in caso di successo
          success: function(risposta){
			 
          //visualizzo il contenuto del file nel div htmlm
            
		
			$("#SHdialog").dialog({title: NAME_RETE});
			$("#SHdialog").dialog("open");	
			$("#SHdialog").html(risposta);
			
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("OpenShell: Qualcosa è andato storto!", stato);
          }
        });
		 
	 
	 }




function openFileWRK(vIdSh,titoloFileSh){
		 $.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=statoshell&action=apriFile&IDSH="+vIdSh+"",  
          // imposto l'azione in caso di successo
          success: function(risposta){
			 
          //visualizzo il contenuto del file nel div htmlm
            
		
			$("#Filedialog").dialog({title: titoloFileSh});
			$("#Filedialog").dialog("open");	
			$("#Filedialog").html(risposta);
			$("textarea").css('height', ($(window).height()-280));
		    $("textarea").css('width', ($(window).width()-220)); 
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("openFileWRK: Qualcosa è andato storto!", stato);
          }
        });
		 
	 
	 }
	 
function openSqlFile(vIdSql,SHFILE){
		$.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=statoshell&action=apriSqlFile&IDSQL="+vIdSql,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#Filedialog").html(risposta);
			$("textarea").css('height', ($(window).height()-280));
			$("textarea").css('width', ($(window).width()-220)); 
			$("#Filedialog").dialog({title: SHFILE});
			$("#Filedialog").dialog("open");	
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("openSqlFile: Qualcosa è andato storto!", stato);
          }
        });
	 
	 
	 }
	 
	 function apriPlsql(vSchema,VPackage){
		 $.ajax({
          method: "get",	  
           
          // specifico la URL della risorsa da contattare
          url: 'index.php?controller=statoshell&action=apriPlsql&SCHEMA='+vSchema+'&PACKAGE='+VPackage,  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
            $("#Filedialog").html(risposta);
			$("textarea").css('height', ($(window).height()-280));
			$("textarea").css('width', ($(window).width()-220)); 
			$("#Filedialog").dialog({title:vSchema+" "+VPackage});
			$("#Filedialog").dialog("open");	
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            errorMessage("apriPlsql: Qualcosa è andato storto!", stato);
          }
        });
		 
	 
	 }	

	
	
	
		
