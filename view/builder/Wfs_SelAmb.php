<style>
.Titolo{
	font-size: 25px;
    margin: auto;
    left: 0;
    right: 0;
    width: 192px;
    padding-top: 10px;
}
</style>
<?php
include './GESTIONE/sicurezza.php';

if ( $find == "1" )  {
    $TServer="Jiak";
    $Azione=$_POST['Azione'];
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$IdTeam=$_POST['IdTeam'];
    ?>
    <FORM id="FormMain" method="POST" >
	<div class="Titolo" >Workflow Builder</div>
	<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="" >
	<input type="hidden" Name="Azione" id="Azione" value="" >
	<?php	
	
	switch ($Azione){
	 case "Modifica" :
			
	  $CallPlSql='CALL WFS.K_CONFIG.ModifyWorkFlow(?, ?, ?, ?, ?, ?, ?, ?, ? )';
	  $stmt = db2_prepare($conn, $CallPlSql);

	  $Errore=0;
	  $ShowErrore=0;
	  $Note="";
	  
	  $InpWorkFlow=$_POST['InpWorkFlow'];
	  $InpDescr=$_POST['InpDescr'];
	  $InpReadOnly=$_POST['InpReadOnly'];
	  $InpFreq=$_POST['InpFreq'];
	  $InpMulti=$_POST['InpMulti'];
	
	  db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
	  db2_bind_param($stmt, 2, "InpWorkFlow" , DB2_PARAM_IN);
	  db2_bind_param($stmt, 3, "InpDescr"    , DB2_PARAM_IN);
	  db2_bind_param($stmt, 4, "InpFreq"     , DB2_PARAM_IN);
	  db2_bind_param($stmt, 5, "InpMulti"    , DB2_PARAM_IN);
	  db2_bind_param($stmt, 6, "InpReadOnly" , DB2_PARAM_IN);
	  db2_bind_param($stmt, 7, "User"        , DB2_PARAM_IN);
	  db2_bind_param($stmt, 8, "Errore"      , DB2_PARAM_OUT);
	  db2_bind_param($stmt, 9, "Note"        , DB2_PARAM_OUT);
	  
	  break;	
		
	 case "Cancella":
			
	  $CallPlSql='CALL WFS.K_CONFIG.RemoveWorkFlow(?, ?, ?, ?)';
	  $stmt = db2_prepare($conn, $CallPlSql);

	  $Errore=0;
	  $ShowErrore=0;
	  $Note="";
	  
	  db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
	  db2_bind_param($stmt, 2, "User"        , DB2_PARAM_IN);
	  db2_bind_param($stmt, 3, "Errore"      , DB2_PARAM_OUT);
	  db2_bind_param($stmt, 4, "Note"        , DB2_PARAM_OUT);
	  
	  break;	
		
	 case "Aggiungi":
		$InpWorkFlow=$_POST['InpWorkFlow'];
	    $InpDescr=$_POST['InpDescr'];
	    $InpFreq=$_POST['InpFreq'];
	    $InpMulti=$_POST['InpMulti'];
	
		
	  $CallPlSql='CALL WFS.K_CONFIG.CreateWorkFlow(?, ?, ?, ?, ?, ?, ?, ?)';
	  $stmt = db2_prepare($conn, $CallPlSql);

	  $Errore=0;
	  $ShowErrore=0;
	  $Note="";
	  
	  db2_bind_param($stmt, 1, "InpWorkFlow", DB2_PARAM_IN);
	  db2_bind_param($stmt, 2, "InpDescr"   , DB2_PARAM_IN);
	  db2_bind_param($stmt, 3, "IdTeam"     , DB2_PARAM_IN);
	  db2_bind_param($stmt, 4, "InpFreq"    , DB2_PARAM_IN);
	  db2_bind_param($stmt, 5, "InpMulti"   , DB2_PARAM_IN);
	  db2_bind_param($stmt, 6, "User"       , DB2_PARAM_IN);
	  db2_bind_param($stmt, 7, "Errore"     , DB2_PARAM_OUT);
	  db2_bind_param($stmt, 8, "Note"       , DB2_PARAM_OUT);
	  break;
	  	
		
	}	
	if ( $Azione != "" ){
		
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
		<label>Team</label><BR>
		<select id="IdTeam" name="IdTeam"  class="FieldMand" style="width:150px;height:30px;">
			<option value=""     <?php if ( $IdTeam == "" ){?>selected<?php } ?> >..</option>
			<?php
			$sqlLA="SELECT ID_TEAM,TEAM FROM WFS.TEAM ORDER BY TEAM";
			$stmtLA=db2_prepare($conn, $sqlLA);
			$resLA=db2_execute($stmtLA);
			while ($rowLA = db2_fetch_assoc($stmtLA)) {
				$TabIdTeam=$rowLA['ID_TEAM'];
				$TabTeam=$rowLA['TEAM'];
				?><option value="<?php echo $TabIdTeam; ?>" <?php if ( $IdTeam == $TabIdTeam ){?>selected<?php } ?> ><?php echo $TabTeam; ?></option><?php
			}
			?>
		</select>
		<BR>
		<?php
		if ( $IdTeam != "" ){
			?>
			<div><LABEL>Select WorkFlow</LABEL></div>
			<?php
			$sqlLA="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR , READONLY,
			( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = W.ID_WORKFLOW ) CONTA
			, FREQUENZA, MULTI
			FROM WFS.WORKFLOW W WHERE ABILITATO = 'Y'
			AND ID_TEAM = $IdTeam
   		    ORDER BY WORKFLOW";
			$stmtLA=db2_prepare($conn, $sqlLA);
			$resLA=db2_execute($stmtLA);
			while ($rowLA = db2_fetch_assoc($stmtLA)) {
				$TabIdWorkFlow=$rowLA['ID_WORKFLOW'];
				$WorkFlow=$rowLA['WORKFLOW'];
				$Descr=$rowLA['DESCR'];
				$Readonly=$rowLA['READONLY'];
				$NumDip=$rowLA['CONTA'];
				$Freq=$rowLA['FREQUENZA'];
				$Multi=$rowLA['MULTI'];

				?>
				<div class="Plst WorkFlow" >
				  <?php
				  if ( $NumDip == "0" ) {
					 ?><div class="Plst DelWfs" onclick="DeleteWorkFlow(<?php echo $TabIdWorkFlow; ?>)" ><img class="ImgIco" src="./images/Cestino.png"  ></div><?php
				  } 
				  ?>
				  <div class="Plst ModWfs" onclick="ModWorkFlow(<?php echo $TabIdWorkFlow; ?>)" ><img class="ImgIco" src="./images/Matita.png"  ></div>
				  <div style="color: brown;font-size:15px;" onclick="OpenWorkFlow(<?php echo $TabIdWorkFlow; ?>)" title="<?php  if  ( $Descr != "" ){ echo $Descr; }  ?>" >
				  <?php 
				  if ( $Readonly == "S" ){
					?><img class="ImgIco" src="./images/ReadMode.png"  ><?php  
				  }
				  echo "$WorkFlow [$Freq]"; ?>
				  </div>
				</div>
				<?php
			}
			if ( $TServer != "PROD USER" ){
			  ?>	
			  <div class="Plst WorkFlow"  style="display: inline-table;box-shadow: 0px 0px 4px 0px red inset;">
			     <div id="PulCreateWF" style="color:red;" ><img class="ImgIco" src="./images/Aggiungi.png" >CREATE WORKFLOW</div>
			  </div>
			    <?php
		    }
			?>
			<div id="EditWfs" ></div>      
			<?php
		    
		}
		?>
	</div> 
	  <script>

		$("#InpWorkFlow").keyup(function(){
		   $(this).val($(this).val().replace(/ /g,"_"));
		   $(this).val($(this).val().toUpperCase());
		   $("#InpDescr").val($(this).val().toUpperCase());
		   $("#InpDescr").val($("#InpDescr").val().replace(/_/g, " "));
		});

		$("#InpDescr").keyup(function(){
		   $(this).val($(this).val().toUpperCase());
		});


		$("#PulCreateWF").click(function(){
		   $('#EditWfs').load('./PHP/Wfs_ModWfs.php',{IdWorkFlow: null, IdTeam : $('#IdTeam').val()});
		});

		
		function DeleteWorkFlow(vIdWorkFlow){
			var re = confirm('Do you want remove the WorkFlow?');
			if ( re == true) {  
				$('#IdWorkFlow').val(vIdWorkFlow);
				$('#Azione').val('Cancella');
				$('#FormMain').submit();
			}		
		}
		
		function OpenWorkFlow(vIdWorkFlow){
			$('#IdWorkFlow').val(vIdWorkFlow);
			$('#FormMain').prop('action','./PAGE/PgWfsConfig.php').prop('target','_self').submit();
		}
		 
	    function ModWorkFlow(vIdWorkFlow){
		   $('#EditWfs').load('./PHP/Wfs_ModWfs.php',{IdWorkFlow: vIdWorkFlow, IdTeam : $('#IdTeam').val()});	
		}
		  
		$('#IdTeam').change(function(){
            $('#Waiting').show();
            $('#FormMain').submit();
		});
		</script>
	</FORM>
	<?php
}
db2_close($db2_conn_string);  
?>