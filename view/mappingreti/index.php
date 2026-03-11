<?php

    function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } 
?>
<div class= "container-fluid">
	<form id="FormSelectRete" method="POST">
		<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Mapping Reti TWS</p> 
		<button onclick="$('#FormSelectRete').submit();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>		
		<label class="labelFiltro">SCHEMA</label>
				<label class="labelFiltro" for="SET_RETE">Seleziona Rete:</label>
				
					<select class="inputFilter selectSearch" name="SET_RETE" id="Sel_Rete"  onchange="$('#Waiting').show();$('#FormSelectRete').submit();">
								<option value=".." <?php if ( $SetRete == ".." Or $SetRete == "" ){ ?>selected<?php } ?>  >..</option>
								<?php
								
							  
								foreach ($datiRete as $row) {
									$NomeRete=$row['RETE'];
									$NomeReteShow=$row['RETE_SHOW'];
									?><option value="<?php echo $NomeRete; ?>" <?php if ( $SetRete == $NomeRete ){ ?>selected<?php } ?> >
										<?php echo $NomeReteShow; ?>
									</option><?php
								}
								
								$Filenamepart="Mapping".$_SESSION['codname'];
								if ( "${SetRete}"!="" And "${SetRete}"!=".."){
									$Filenamepart="${Filenamepart}${SetRete}";
								}
								if ( "${SelFind}"!="" ){
									$Filenamepart="${Filenamepart}Filtered";
								}
								$Filename=$_SESSION['PSITO']."/TMP/${Filenamepart}.csv";
								?>
					</select>
					<?php
					foreach ($datiRow as $row) {
						$CntRetiUSR=$row['CNT'];
					} 
					?>
				<label class="labelFiltro" for="myCheck1"> Mostra dettagli</label></th>
					<input type="checkbox" id="myCheck1"></td>
			   <label class="labelFiltro"> Enabled</label>
				
				<select class="selectNoSearch" name="SEL_ENABLE_RETE" onchange="$('#Waiting').show();$('#FormSelectRete').submit();">
							<option value="Y" <?php if ( $SelEnableRete == "Y" ) { ?> selected <?php } ?> >Yes</option>
							<option value="N" <?php if ( $SelEnableRete == "N" ) { ?> selected <?php } ?> >No</option>
							<option value="ALL" <?php if ( $SelEnableRete == "ALL" ) { ?> selected <?php } ?> >All</option>
				</select>
				
				
				<?php if (isset($SetRete)){?>
				<button onclick="window.open('<?php echo "./TMP/${Filenamepart}.csv"; ?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download File</button>
	
				<?php }?>
				
		
			<?php if ( $CntRetiUSR >0 ){ ?> 
			<div>
				  <img  style="width:30px;" src="./images/Alert.png" > 
				<?php echo 'Presenti ' . $CntRetiUSR . ' reti sul TASPCUSR (verificare e sistemare la WORK_CORE.CORE_RETI_TWS)'; ?> 
				</div> 
			
			<?php } ?>
			</aside>
	</form>
	
	<form id="FormMappingReti" method="POST">
	<table class="display dataTable" id= "idTabella">
			<thead class="headerStyle">
			<tr>
				 <th>DATABASE</th>
				 <th >NAME_RETE</th>
				 <th></th>
				 <th>PATH_SH</th>
				 				
				 <th>NAME_SH</th> 
				 <th>MAIL</th>
				 <th>VARIABLES</th>
				 <th>TAGS</th>
				 <th>STEP_SH</th>  
				 <th>STEP_DB</th> 
				 <th>TYPE</th>

				 <th >FILE_SQL</th>  
				 
				 <th>LISTA_TABELLE_SH</th>
				 <th>LISTA_TABELLE_SQL</th>
				 <th>LISTA_TABELLE_PK</th>
				 <th>START_TIME_SH</th>
				 <th>END_TIME_SH</th>
				 <th>FREQUENZA</th>
			</tr>
			</thead>
			<tbody>
			<?php    
			$Testo="ENABLE;NAME_RETE;PASSO_RETE;NAME_RETE_STATSIN;DATABASE;PATH_SH;NAME_SH;VARIABLES;MAIL;TAGS;STEP_SH;STEP_DB;TYPE_RUN;FILE_SQL;PACKAGE_SCHEMA;PACKAGE_NAME;PACKAGE_ROUTINE;LISTA_TABELLE_SH;LISTA_TABELLE_SQL;LISTA_TABELLE_PK;START_TIME_SH;END_TIME_SH;CHIAMTE_SUCCESSIVE;FREQUENZA_RETE;FLUSSO_CHIAMANTE";
		
			foreach ($datiTableList as $row) 
			{
					$ENABLE            =$row['ENABLE'];
					$RETE              =$row['RETE'];
					$NAME_RETE_SHOW    =$row['NAME_RETE_SHOW'];
					$NAME_RETE         =$row['NAME_RETE'];
					$NAME_RETE_OLD     =$row['NAME_RETE_OLD'];
					$DATABASE          =$row['DATABASE'];
					$PATH_SH           =$row['PATH_SH'];
					$PATH_SH_FULL      =$row['PATH_SH_FULL'];
					$NAME_SH           =$row['NAME_SH'];
					$VARIABLES_SH      =$row['VARIABLES_SH'];
					$TAGS              =$row['TAGS'];
					$MAIL              =$row['MAIL'];
					$STEP_SH           =$row['STEP_SH'];
					$STEP_DB           =$row['STEP_DB'];
					$TYPE_RUN          =$row['TYPE_RUN'];
					$FILE_SQL          =$row['FILE_SQL'];
					$PACKAGE_SCHEMA    =$row['PACKAGE_SCHEMA'];
					$PACKAGE_NAME      =$row['PACKAGE_NAME'];
					$PACKAGE_ROUTINE   =$row['PACKAGE_ROUTINE'];
					$ROUTINE           =$row['ROUTINE'];
					$LISTA_TABELLE_SH  =$row['LISTA_TABELLE_SH'];
					$LISTA_TABELLE_SQL =$row['LISTA_TABELLE_SQL'];
					$LISTA_TABELLE_PK  =$row['LISTA_TABELLE_PK'];
					$START_TIME_SH     =$row['START_TIME_SH'];
					$END_TIME_SH       =$row['END_TIME_SH'];
					$ID_SH_RETE        =$row['ID_SH_RETE'];
					$ID_RUN_SH_RETE    =$row['ID_RUN_SH_RETE'];
					$ID_SH             =$row['ID_SH'];
					$ID_RUN_SH         =$row['ID_RUN_SH'];
					$ID_RUN_SQL        =$row['ID_RUN_SQL'];
					$ID_RUN_SH_LIV_SUCC=$row['ID_RUN_SH_LIV_SUCC'];
					$WORKFLOW          =$row['WORKFLOW'];
					$FREQUENZA_WORKFLOW=$row['FREQUENZA_WORKFLOW'];
					$FLUSSO_CHIAMANTE  =$row['FLUSSO_CHIAMANTE'];

				 $_modelshell = new statoshell_model();
				 $datiFileInfo = $_modelshell->getFileInfo($ID_SH_RETE);
			 
				$Testo="$Testo\r\n$ENABLE;$RETE;$NAME_RETE;$NAME_RETE_OLD;$DATABASE;$PATH_SH_FULL;$NAME_SH;$VARIABLES_SH;$MAIL;$TAGS;$STEP_SH;$STEP_DB;$TYPE_RUN;$FILE_SQL;$PACKAGE_SCHEMA;$PACKAGE_NAME;$PACKAGE_ROUTINE;$LISTA_TABELLE_SH;$LISTA_TABELLE_SQL;$LISTA_TABELLE_PK;$START_TIME_SH;$END_TIME_SH;";
				$titoloSh = "SHELL: ($ID_SH) ".$datiFileInfo[0]['SHELL_PATH'].'/'.$NAME_RETE_SHOW;
				$titoloFileSh = "SHELL: ($ID_SH) ".$PATH_SH_FULL.'/'.$NAME_SH;

				if ( $ID_RUN_SH_LIV_SUCC != "" ){ 
				  $Testo.="Presenza chiamate successive!;";
				} else {
				  $Testo.=";";
				}
				$Testo.="$FREQUENZA_WORKFLOW;$FLUSSO_CHIAMANTE;";
				file_put_contents($Filename, $Testo );
				  ?>
				  <tr>
				<!-- Database -->
					<td> 
					<?php if ( $DATABASE == "TASPCUSR" ){ echo "USER"; }else { echo "WORK";}?>					  
					</td>
				  <!-- NAME_RETE  -->
					<td class="addMail" title="<?php echo $ID_SH_RETE; ?>" onclick="openFileWRK('<?php echo $ID_SH_RETE; ?>','<?php echo $titoloSh; ?>');" >
					 <?php if ( $ENABLE == "N" ){ ?> <img  style="width:15px;" title="<?php if ( $ENABLE == "Y" ){ echo 'ENABLE'; } else { echo 'DISENABLE';}; ?>" 
					   src="./images/StatusX.png"  class="IconStatusX" > <?php } ?>  
					   <?php echo $NAME_RETE_SHOW; ?>
					  <?php if ( $ID_RUN_SH_LIV_SUCC != "" ){ ?> <img style="width:15px;" title="<?php echo "Presenza chiamate successive!"; ?>" 
					   src="./images/Attention.png"  class="IconAttention" > <?php } ?>  
					</td>
				  <!-- SHELL ICON -->				
					<td class="addMail"> 
					  <img  style="width:30px;" src="./images/Shell.png" title="<?php echo $ID_RUN_SH_RETE; ?>" class="IconFile" onclick="OpenShell('<?php echo $ID_RUN_SH_RETE; ?>','<?php echo $NAME_RETE; ?>');"  > 
					</td>
					<!--  PATH_SH -->				
					<td><?php echo $PATH_SH; ?></td>
					<!-- NAME_SH  -->					
					
					<td class="addMail" title="<?php echo $ID_SH; ?>"
						onclick="<?php if( $DATABASE == "TASPCUSR" ){ ?> OpenFileUsr(' <?php } else { ?> openFileWRK('<?php } echo $ID_SH; ?>', '<?php echo $titoloFileSh; ?>');" >
					  <?php echo $NAME_SH;?> 
					</td>
					<!-- MAIL_ICON -->	
					<td> 
					<?php if ( $MAIL == "Y" ){ ?> 
					  <img style="width:30px;"  src="./images/Mail.png"  class="IconMail" > 
					  <?php } ?>  
					</td>
					<!-- VARIABLES_SH -->	
					<td><?php echo $VARIABLES_SH; ?></td>   
					<!-- TAGS -->								
					<td><?php echo $TAGS; ?></td>
					<!-- STEP_SH -->		
					<td><?php echo $STEP_SH; ?></td>
					<!-- STEP_DB -->
					<td><?php echo $STEP_DB; ?></td>
					<!-- TYPE_RUN -->
					<td><?php echo $TYPE_RUN; ?></td> 
					<!-- FILE_SQL-->					
					<td class="addMail" <?php if ( $ID_RUN_SQL != "" && $FILE_SQL != "Anonymous Block" && $FILE_SQL != "File Anonymous Block"){
							
						$titoloSh = "SQL: ($ID_RUN_SQL) ".$FILE_SQL;
						$PATH_FILE_SQL = $FILE_SQL;
						$FILE_SQL = substr ($FILE_SQL, strrpos($FILE_SQL, '/', 0)+1);
						
						$funOpenSqlFile =( $DATABASE == "TASPCUSR" )?"OpenSqlFileUsr":"openSqlFile"; 
						?> onclick="<?php echo $funOpenSqlFile; ?>('<?php echo $ID_RUN_SQL; ?>','<?php  echo $titoloSh; ?>');"
					  <?php } ?>> 
					  <?php if ( $ID_RUN_SQL != "" && $FILE_SQL != "Anonymous Block" && $FILE_SQL != "File Anonymous Block"){?>
					  <img class="imgFileSQL" title="<?php echo $FILE_SQL; ?>" src="./images/File.png" onclick="<?php echo $funOpenSqlFile; ?>('<?php echo $ID_RUN_SQL; ?>','<?php  echo $titoloSh; ?>');" style="width:25px;">
				     <?php 
					 $SubFILE_SQL = (strlen($FILE_SQL)>30)?"...".substr($FILE_SQL, -30):$FILE_SQL;
					 echo '<span title="'.$FILE_SQL.'">'.$SubFILE_SQL.'</span>';} ?>
					    <?php if (  $FILE_SQL == "Anonymous Block" || $FILE_SQL == "File Anonymous Block"){
							$functionPLSsql = ( $DATABASE == "TASPCUSR") ?"OpenPlsqlUsr":"apriPlsql";?>
					<?php if ( $PACKAGE_SCHEMA != "" ){?>
						<img class="imgFileSQL" title="<?php echo $ROUTINE; ?>" src="./images/File.png" onclick="<?php echo $functionPLSsql; ?>('<?php  echo $PACKAGE_SCHEMA; ?>','<?php echo $PACKAGE_NAME; ?>')" style="width:25px;">
						<div class="addMail" onclick="<?php echo $functionPLSsql; ?>('<?php  echo $PACKAGE_SCHEMA; ?>','<?php echo $PACKAGE_NAME; ?>')" >
						<?php 
						 $SubROUTINE = (strlen($ROUTINE)>30)?"...".substr($ROUTINE,-30):$ROUTINE;
						echo '<span title="'.$ROUTINE.'">'.$SubROUTINE.'</span>';
						?>
						<?php } ?> 
					  <?php } ?> 
					</div>   
					</td>    
					<td><?php echo $LISTA_TABELLE_SH; ?></td>
					<td><?php echo $LISTA_TABELLE_SQL; ?></td>
					<td><?php echo $LISTA_TABELLE_PK; ?></td>
					<td><?php echo $START_TIME_SH; ?></td>
					<td><?php echo $END_TIME_SH; ?></td>
					<td><?php echo $WORKFLOW; ?></td>
				  </tr>

				  <?php
			}
			shell_exec("chmod 774 $Filename");
			?>
			</tbody>
	</table>		
</form>

</div>	