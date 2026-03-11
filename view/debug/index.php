<form id="FormMail" method="POST">
<div class= "container-fluid">

<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Config | Debug</p> 
				<div class="asideContent">
				<button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>	
	<select class="selectSearch" id="ID_SH" name="ID_SH">
	    <option selected disabled value="" >Seleziona Shell</option>
		<?php
	  
		foreach ($datiShList as $row) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL.' ('.$SHELL_PATH.')'; ?></option><?php
		}
		?>
	</select>
<label for="flagSh">SH</label>
<input class="custom-checkbox" type="checkbox" name="flagSh" id="flagSh"  value="1"> 
<label for="flagDb">DB</label>
<input class="custom-checkbox" type="checkbox" name="flagDb" id="flagDb" value="1"> 
<button id="addworkflow" onclick="addDebug();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Add Shell</button>
	
</div>	
</aside>

	
	<?php
	
  
    $MailType=array('T_TO','T_CC','R_TO','R_CC');
    $all_shell=array();
    foreach ($datiDebugList as $row) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SHELL_PATH=$row['SHELL_PATH'];
		$SH_DEBUG=$row['DEBUG_SH'];
		$DB_DEBUG=$row['DEBUG_DB'];
		$SH_MAIL_ENABLE=$row['SH_MAIL_ENABLE'];
		$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
		$TYPE=$row['TYPE'];
		$MAIL=$row['MAIL'];
		$MAIL_ENABLE=$row['MAIL_ENABLE'];
		$MULTI_SEND=$row['MULTI_SEND'];
			  
		if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND"),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND"));
		}
	  
		if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
			${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		} else {
			array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		}
		
	}
	
	?>
	
	<input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
		<input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
		<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
		<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
		<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
		<input type="hidden" id="pageTable" name="pageTable" value="" />
		<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />
	<table id='idTabella' class="display dataTable">
		<thead class="headerStyle">
			<tr>
			 <th>Shell</th>
			 <th>Path</th>
			 <th>Debug Sh</th>
			 <th>Debug Db</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach( $all_shell as $DettShell){
			$ID_SH=$DettShell[0];
			$SHELL=$DettShell[1];
			$SHELL_PATH=$DettShell[2];
			$SH_DEBUG=$DettShell[3];
			$DB_DEBUG=$DettShell[4];
			$SH_MAIL_ENABLE=$DettShell[5];
			$SH_MULTI_SEND=$DettShell[6];
			?><tr>
				<td  class="ShLine Puls" onclick="TrDett('<?php echo $ID_SH; ?>')">
					<b><?php echo $SHELL; ?></b>
				</td>
				<td class="ShLine" ><?php echo $SHELL_PATH; ?></td>
				<td class="ShLine" >
				<?php 
				if ( $SH_DEBUG == "Y" ){
					?><i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"  onclick="ShDebug('<?php echo $ID_SH; ?>', 'N','Y')"/><?php
				} else {
					?><i class="fa-solid fa-minus Puls" width="25px" class="closeIconStyle" style="color: grey;"  onclick="ShDebug('<?php echo $ID_SH; ?>', 'Y','Y')"/><?php 
					
				}
				?>
				</td>		
				<td class="ShLine" >
				<?php 
				if ( "$DB_DEBUG" == "Y" ){
					?><i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"  onclick="DbDebug('<?php echo $ID_SH; ?>', 'N','Y')"/><?php
				} else {
					 ?><i class="fa-solid fa-minus Puls" width="25px" class="closeIconStyle" style="color: grey;" onclick="DbDebug('<?php echo $ID_SH; ?>', 'Y','Y')"/><?php 
					
				}
				?>
				</td>				
				</tr>
			   <?php	
		}
		?>
		</tbody>
	</table>
	
</div>

</form>

<script src="./view/debug/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>

