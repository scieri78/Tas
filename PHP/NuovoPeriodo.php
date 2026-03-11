<?php
	
if ( strpos($CodGroup, ',\'2\'') ) {
			
//-----------------------------------------------------------------------------------------------------------
//  PULSANTE NUOVO PERIODO
//-----------------------------------------------------------------------------------------------------------
    
	$SisAmb=$_POST['SisAmb'];
	$ArSisAmb=explode(":",$SisAmb);
	$Sistema=$ArSisAmb[0];
	$Ambiente=$ArSisAmb[1];

    if ( "$Sistema" != "" and "$Ambiente" != "" ) {
	$db2conn1 = db2_connect($db2_conn_string, '', ''); 
      $SqlCaricamento='CALL WEB.CORE.NuovoPeriodo(?,?,?,?)';
      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

      $Note="";
      $Errore=0;
	  db2_bind_param($stmt, 1, "Sistema", DB2_PARAM_IN);
      db2_bind_param($stmt, 2, "Ambiente", DB2_PARAM_IN);
      db2_bind_param($stmt, 3, "Note", DB2_PARAM_OUT);
	  db2_bind_param($stmt, 4, "Errore", DB2_PARAM_OUT);
      db2_execute($stmt);
      db2_close($db2conn1); 
	  
      if ( "$Errore" == "1" ) {
        EsitoRichiesta( $Note ,'KO', '' );
      } else {
        EsitoRichiesta( $Note ,'OK', '' );  
      }   
      unset($_POST['AggPeriodo']);

    } 
	

	?>	   
	<style>
		 #NuovoPeriodo{
			text-align: center;
			height: 200px;
			width: 50%;
			border: 1px solid black;
			background: white;
			position: relative;
			top: 30px;
			margin-left: auto;
			margin-right: auto;
			left:0;
			right:0;
			padding: 10px;
		}
		
		#AmbList{
		    margin:10px;
		}
		
		#PulAggPeriodo {
			position: relative;
			border: 1px solid red;
			top: 5px;
			z-index: 999;
			height: 30px;
			border-radius: 10px;
			cursor: pointer;
			padding-left: 10px;
			padding-right: 10px;
			padding-top: 7px;
		}
		
		
	</style>
	<div id="NuovoPeriodo">

		<form method="POST" id="FormAggPeriodo">
		    <B>CREAZIONE NUOVO PERIODO DI ELABORAZIONE:</B></br>
			<select id="AmbList" name="SisAmb" ><?php
				 $SqlAmbiente="SELECT CONCAT(a.SISTEMA,':',a.AMBIENTE) SISAMB
				   from 
					 WEB.WFS_AMBIENTI a, 
					 WEB.WFS_GRUPPI g	 
				   where 1=1
					AND a.SISTEMA = g.Sistema
					AND a.AMBIENTE = g.AMBIENTE
					AND a.ABILITATO = 'S'
					AND g.GRUPPO = 'ADMIN'
					AND g.GK in ( $CodGroup )
					group by a.SISTEMA,a.AMBIENTE 
					order by a.SISTEMA,a.AMBIENTE";			

				$resAmbiente = mysql_query($SqlAmbiente);
				?><option value="" >Seleziona Ambiente...</option><?php
				while ($rowAmbiente = mysql_fetch_assoc($resAmbiente)) { 
				   $LSisAmb=$rowAmbiente["SISAMB"];   
				   ?><option value="<?php echo "$LSisAmb"; ?>" <?php if ( "$SisAmb" == "$LSisAmb" ) { ?> selected <?php } ?> ><?php echo "$LSisAmb"; ?></option><?php
				} ?>
			</select></br>
			<label id="PulAggPeriodo" >Nuovo</label>
			<script> 
			$("#PulAggPeriodo").click(function(){  
			    var vAmbiente=$("#AmbList").val();
				var r = confirm('Sicuro di voler Aprire un nuovo Periodo per l\'ambiente: '+vAmbiente+'  ?');
				if (r == true) {
				
					var input = $("<input>")
					.attr("type", "hidden")
					.attr("name", "AggPeriodo")
					.val("AggiungiPeriodo");
					$('#FormAggPeriodo').append($(input));				
				
				 $('#FormAggPeriodo').submit();
				}				
			});
			</script>
		</form>
	</div>
	<?php
}

?>		