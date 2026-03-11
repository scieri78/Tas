
<form id="FormMail" method="POST">
	 <input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
	<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
	<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
	<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
	<input type="hidden" id="pageTable" name="pageTable" value="" />
	<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />
		<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WFS Admin | Exp To Wfs</p> 
	<div class="asideContent">
		<select class="selectSearch FieldMand" id="Sel_EnableWfs" onchange="SetShWfs(this.value,'Y')" style="width:650px;height:30px;">
	    <option selected disabled value="" >Enable Wfs on</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH	FROM WORK_CORE.CORE_SH_ANAG  WHERE WFS ='N' ORDER BY SHELL";

		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		
		if ( ! $result ){
			echo "ERROR DB2";
		}
		
		db2_close($db2_conn_string);
	  
		while ($row = db2_fetch_assoc($stmt)) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL.' ('.$SHELL_PATH.')'; ?></option><?php
		}
		?>
	</select>
		</div>	
		</aside>	

	<?php

  
  
    $all_shell=array();
    foreach ($DatiEleborazioni as $row ) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SHELL_PATH=$row['SHELL_PATH'];
		$WFS=$row['WFS'];
		$WFSUSED=$row['WFSUSED'];
		$BLOCKWFS=$row['BLOCKWFS'];
		
			  
		if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$WFS","$WFSUSED","$BLOCKWFS"),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$WFS","$WFSUSED","$BLOCKWFS"));
		}
	  		
	}
	
	?>
	
	<table id="idTabella" class="display dataTable">
	  <thead class="headerStyle">
	<tr>
	 <th>Shell</th>
	 <th>Path</th>
	 <th>Wfs</th>
	 <th>BlockWfs</th>
	</tr>
	</thead>
		<tbody>
	<?php
	foreach( $all_shell as $DettShell){
		$ID_SH=$DettShell[0];
		$SHELL=$DettShell[1];
		$SHELL_PATH=$DettShell[2];
		$WFS=$DettShell[3];
		$WFSUSED=$DettShell[4];
		$BLOCKWFS=$DettShell[5];
	    ?><tr>
		    <td onclick="TrDett('<?php echo $ID_SH; ?>')">
				<b><?php echo $SHELL; ?></b>
			</td>
			<td> <?php echo $SHELL_PATH; ?></td>
			<td>
			<?php 
			if ( "$WFSUSED" == "0" ){
				?><i class="fa-solid fa-trash-can trashIconStyle" class="Puls" onclick="SetShWfs('<?php echo $ID_SH; ?>','N')"></i><?php
			} else {
				?>Used<?php
			}
			?>
			</td>
			<td onclick="BlockWfs('<?php echo $ID_SH; ?>')" >
			<?php 
			  if ( "$BLOCKWFS" == "Y" ){
				 ?><i class="fa-solid fa-trash-can trashIconStyle" class="Puls" onclick="SetBlockWfs('<?php echo $ID_SH; ?>','N')"></i><?php  
			  }else{
				 ?><i class="fa-solid fa-square-plus addnewIconStyle" class="Puls" onclick="SetBlockWfs('<?php echo $ID_SH; ?>','Y')"></i><?php 
			  }
			?>
			</td>
		   </tr>
		   <?php	
	}
	?>
	</tbody>
	</table>
	</form>

	 <script src="./view/exptowfs/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
	 <script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>
	 

