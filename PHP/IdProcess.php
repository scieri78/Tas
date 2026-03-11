<style>
.WorkFlow {
    width: 300px;
    left: 0;
    right: 0;
    margin: auto;
    margin-bottom: auto;
    padding: 5px;
    border: 1px solid;
    background: white;
    margin-bottom: 10px;
    text-align:center;
}

#AddIdProcess{
    position:fixed;
    width: 600px;
    height: 450px;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    padding: 5px;
    border: 1px solid;
    background: white;
    text-align:center;
    box-shadow: 0px 0px 10px 0px black;
}

.Titolo{
    font-size:20px;
}

.Bottone {
    color: blue;
    border: 1px solid blue;
    height: 25px;
    text-align: center;
    box-shadow: 0px 0px 7px 1px lightblue inset;
    -o-box-shadow: 0px 0px 7px 1px lightblue inset;
    -moz-box-shadow: 0px 0px 7px 1px lightblue inset;
    border-radius: 5px;
    width: 200px;
    background-color: white;
    padding: 4px;
    left: 0;
    right: 0;
    margin: auto;
    margin-top: auto;
    margin-top: 5px;
    cursor: pointer;
}

</style>
<?php
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {


    $RemoveRO=$_POST['RemoveRO'];	
	if ( "$RemoveRO" != "" ){		
      $CallSql="UPDATE WORK_CORE.ID_PROCESS SET READONLY='N' WHERE ID_PROCESS = $RemoveRO";
      $stmt=db2_prepare($conn, $CallSql);
      $res=db2_execute($stmt);
      
      if ( ! $res) {
          $Note="Exec Calling Error: ".db2_stmt_errormsg();
          $ShowErrore=1;
      }
      $OpenWkf=1;	  
	}
	
    $AddRO=$_POST['AddRO'];	
	if ( "$AddRO" != "" ){		
      $CallSql="UPDATE WORK_CORE.ID_PROCESS SET READONLY='Y' WHERE ID_PROCESS = $AddRO";
      $stmt=db2_prepare($conn, $CallSql);
      $res=db2_execute($stmt);
      
      if ( ! $res) {
          $Note="Exec Calling Error: ".db2_stmt_errormsg();
          $ShowErrore=1;
      }		
	  $OpenWkf=1;	  
	}
    
	$AddSvecc=$_POST['AddSvecc'];	
    if ( "$AddSvecc" != "" ){
      $SqlList="INSERT INTO WORK_CORE.DELETE_LIST_ID_PROCESS (ID_PROCESS, USER) VALUES ($AddSvecc, '$User') ";
      $stmt=db2_prepare($conn, $SqlList);
      $res=db2_execute($stmt);
      if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	  $OpenWkf=1;
    }
    
	$RemSvecc=$_POST['RemSvecc'];	
    if ( "$RemSvecc" != "" ){
      $SqlList="DELETE FROM WORK_CORE.DELETE_LIST_ID_PROCESS WHERE ID_PROCESS = $RemSvecc ";
      $stmt=db2_prepare($conn, $SqlList);
      $res=db2_execute($stmt);
      if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }	
	  $OpenWkf=1;
    }
	
    $Azione=$_POST['Azione'];
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$IdTeam=$_POST['IdTeam'];
	$FromId=$_POST['FromId'];
	$ToId=$_POST['ToId'];
    $ShowStatusCopy=$_POST['ShowStatusCopy'];
    ?>
    <FORM id="FormMain" method="POST" >
    <?php   

    $Errore=0;
    $ShowErrore=0;
    $Note="";
  
    switch ($Azione){
	 case "Rimuovi" :
	  $SvuotaId=$_POST['SvuotaId'];
	  $CancellaId=$_POST['CancellaId'];
	  
	  if ( "$SvuotaId" != "" ){
	     shell_exec("sh $PRGDIR/SvuotaIdP.sh '${SSHUSR}' '${SERVER}' '${SvuotaId}'");
	  }
	  if ( "$CancellaId" != "" ){
	     shell_exec("sh $PRGDIR/RimuoviIdP.sh '${SSHUSR}' '${SERVER}' '${CancellaId}'");
	  }
	  break;  		
	 case "Copia" :
	  $ShowStatusCopy="ShowStatusCopy";
	  shell_exec("sh $PRGDIR/CopiaIdP.sh '${SSHUSR}' '${SERVER}' '${FromId}' '${ToId}'");
	
      break;    
     case "Aggiungi" :
            
      $CallPlSql='CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt=db2_prepare($conn, $CallPlSql);
      
      $Descr=$_POST['Descr'];
      $Esercizio=$_POST['Esercizio'];
	  $Tipo=$_POST['Tipo'];
	  $Tipo='Q';
      
      $Anno=date('Y',strtotime($Esercizio))+0;
      $Mese=date('m',strtotime($Esercizio))+0;
        
      db2_bind_param($stmt, 1, "Anno"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 2, "Mese"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 3, "Descr"      , DB2_PARAM_IN);
	  db2_bind_param($stmt, 4, "Tipo"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 5, "IdTeam"     , DB2_PARAM_IN);
	  db2_bind_param($stmt, 6, "IdWorkFlow" , DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "User"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Errore"     , DB2_PARAM_OUT);
      db2_bind_param($stmt, 9, "Note"       , DB2_PARAM_OUT);
      
      break;    
    }   
    
    if ( "$Azione" != "" ){
      $OpenWkf=1;	  
      $res=db2_execute($stmt);
      
      if ( ! $res) {
          $Note="PLSQL Exec Calling Error: ".db2_stmt_errormsg();
          $ShowErrore=1;
      }
      
      if ( "$Errore" != "0" ) {
          $Note="PLSQL Procedure Calling Error: ".$Note;
          $ShowErrore=1;
      }
      
      if ( $ShowErrore != 0 ) {
        echo $Note;
      }  

    }
    
	
    ?>
    <div id="LoadConfig" >	
			<CENTER>
			<label>Gruppo</label><BR>
			<select id="IdTeam" name="IdTeam"  style="width:150px;height:30px;">
				<option value=""     <?php if ( "$IdTeam" == "" ){?>selected<?php } ?> >..</option>
				<?php
				$sqlLA="SELECT ID_TEAM,TEAM FROM WFS.TEAM ORDER BY TEAM";
				$stmtLA=db2_prepare($conn, $sqlLA);
				$resLA=db2_execute($stmtLA);
				while ($rowLA = db2_fetch_assoc($stmtLA)) {
					$TabIdTeam=$rowLA['ID_TEAM'];
					$TabTeam=$rowLA['TEAM'];
					?><option value="<?php echo $TabIdTeam; ?>" <?php if ( "$IdTeam" == "$TabIdTeam" ){?>selected<?php } ?> ><?php echo $TabTeam; ?></option><?php
				}
				?>
			</select>
			<BR>
			<?php
			if ( "$IdTeam" != "" ){
				?>
				<LABEL class="Titolo" >Seleziona WorkFlow</LABEL></CENTER>
				<?php
				$sqlLA="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR ,
				( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = W.ID_WORKFLOW AND FLAG_STATO != 'C' ) CONTA
				FROM WFS.WORKFLOW W 
				WHERE 1=1
				AND ABILITATO = 'Y' 
				AND ID_TEAM = '$IdTeam' 
				ORDER BY WORKFLOW";
				
				$stmtLA=db2_prepare($conn, $sqlLA);
				$resLA=db2_execute($stmtLA);
				while ($rowLA = db2_fetch_assoc($stmtLA)) {
					$TabIdWorkFlow=$rowLA['ID_WORKFLOW'];
					$WorkFlow=$rowLA['WORKFLOW'];
					$Descr=$rowLA['DESCR'];
					$NumProc=$rowLA['CONTA'];

					?>
					<div class="Plst WorkFlow"  onclick="OpenWorkFlow(<?php echo $TabIdWorkFlow; ?>)" >
					  <div style="color: brown;font-size:15px;"  >
					  <?php echo "$WorkFlow"; if  ( "$Descr" != "" ){ echo " ($Descr)"; }; ?></div>
					  <div class="TotIsProd" >Contiene <B><?php echo  $NumProc; ?></B> Id Process</div>
					</div>
					<?php
				}
								
     		}		
	?>      
	</div> 
    <div id="AddIdProcess" hidden  >
       <div id="LoadNewProcess" ></div>
       <table style="position: absolute;bottom:10px;margin:auto;left:0;right:0;" ><tr>
	   <td><div class="Bottone"  onclick="$('#AddIdProcess').hide()" >Chiudi</div></td>
	   <td><div class="Bottone"  onclick="RefreshPage()" >Refresh</div></td>
	   </tr></table>
    </div>
    <script>
        
		function RefreshPage(){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RefreshPage")
            .val('Refresh');
            $('#FormMain').append($(input));     			
			$('#Waiting').show();
            $('#FormMain').submit();
		}
		
        function OpenWorkFlow(vIdWorkFlow){
            $('#LoadNewProcess').load('../PHP/IdProcessAdd.php',{IdWorkFlow : vIdWorkFlow, IdTeam : $('#IdTeam').val(), FromId : '<?php echo $FromId; ?>', ToId : '<?php echo $ToId; ?>', ShowStatusCopy : '<?php echo $ShowStatusCopy; ?>' });
            $('#AddIdProcess').show();
        }
        
		$('#IdTeam').change(function(){
            $('#Waiting').show();
            $('#FormMain').submit();
		});
        
<?php
  $RefreshPage=$_POST['RefreshPage'];
  if ( "$OpenWkf" == "1" or "$RefreshPage" == "Refresh" or "$ShowStatusCopy" == "ShowStatusCopy" ){	
	  ?>OpenWorkFlow(<?php echo $IdWorkFlow; ?>);<?php
  }
?>		
    </script>
    </FORM>
    <?php
}
db2_close($db2_conn_string);  
?>