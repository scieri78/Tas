<div class= "container-fluid">
	
	<form id="FormMail" method="POST">	
		<input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
		<input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
		<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
		<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
		<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
		<input type="hidden" id="pageTable" name="pageTable" value="" />
		<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />
			
		<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Config | Mail</p> 
			<div class="asideContent">
			    <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>	
				<select  id="Sel_EnableShMail" onchange="UpdateShMail('Y',$(this).val())" class="selectSearch  FieldMand" >
					<option selected disabled value="" >Enable Mail on</option>
					<?php		  
					foreach ($DatiSelEnableShMail as $row ) {?>
					<option value="<?php echo $row['ID_SH']; ?>" ><?php echo $row['SHELL'].' ('.$row['SHELL_PATH'].')'; ?></option><?php
					}?>
				</select>
			</div>	
		</aside>	

		 <table id='idTabella' data-page-length='<?php echo $mailLength;?>' class="display dataTable">
			<thead class="headerStyle">
				<tr>
				 <th style="width:500px" >Path</th>
				 <th>Shell</th>
				<th>Num. Dest. Tec</th>
				 <th>Num. CC. Tec</th>
				 <th>Num. Dest.</th>
				 <th>Num. CC.</th>
				
				 <th>With Attachment</th>
				 <th></th>

				
				</tr>
			</thead>
			 <tbody>
				 <?php
			//	print_r($all_shell);
				foreach( $all_shell as $DettShell){
					$ID_SH=$DettShell[0];
					$SHELL=$DettShell[1];
					$SHELL_PATH=$DettShell[2];
					$SH_DEBUG=$DettShell[3];
					$DB_DEBUG=$DettShell[4];
					$SH_MAIL_ENABLE=$DettShell[5];
					$SH_MULTI_SEND=$DettShell[6];
					$SH_R_TO=$DettShell[7];
					$SH_R_CC=$DettShell[8];
					$SH_T_TO=$DettShell[9];
					$SH_T_CC=$DettShell[10];
					$SH_ATTC=$DettShell[11];
					
					
					if ( $SH_R_TO == 0 ){ $SH_R_TO=""; }
					if ( $SH_R_CC == 0 ){ $SH_R_CC=""; }
					if ( $SH_T_TO == 0 ){ $SH_T_TO=""; }
					if ( $SH_T_CC == 0 ){ $SH_T_CC=""; }
					//if ( $SH_ATTC == 0 ){ $SH_ATTC=""; }
					
					if($SelIdSh==$ID_SH)
						{
						$titoloDilogBox=$SHELL_PATH." -- ".$SHELL;
						}
					
					$SH_ATTC = ( $SH_ATTC == "Y" )?'<i class="fa-solid fa-check Puls" style="color: green;"></i>':'<i class="fa-solid fa-minus Puls" width="25px" style="color: white;"></i>';
					?>
					
					
					
					<tr>
						<td class="addMail" onclick="openDialogMail('<?php echo $ID_SH; ?>','<?php echo $SHELL_PATH." -- ".$SHELL; ?>')"><?php echo $SHELL_PATH; ?></td>
						<td onclick="openDialogFile('<?php echo $ID_SH; ?>','<?php echo $SHELL_PATH." -- ".$SHELL; ?>')" class="addMail" >
							<img src="./images/File.png" class="IconFile"  style="width: 20px;">
							<b><?php echo $SHELL; ?></b>
						</td>
						<td><?php echo $SH_T_TO; ?></td>	
						<td><?php echo $SH_T_CC; ?></td>	
						<td><?php echo $SH_R_TO; ?></td>			
						<td><?php echo $SH_R_CC; ?></td>	
						
						<td><?php echo $SH_ATTC; ?></td>
						<td>
						  <i class="fa-solid fa-trash-can trashIconStyle" class="Puls" onclick="UpdateShMail('N','<?php echo $ID_SH; ?>')"></i>
						</td>		
					   </tr>
					   
					   
					   <?php	
				   ?></tr><?php	
				}
				?>
			</tbody>
		</table>
	</form>
</div>



<script src="./view/mailconf/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>