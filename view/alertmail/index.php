<div class= "container-fluid">

    <form id="FormMail" method="POST">
   <input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
   
	<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
	<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
	<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
	<input type="hidden" id="pageTable" name="pageTable" value="" />
	<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />
   
   
   <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Config | Alert Mail</p> 
				<div class="asideContent">
	<button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>				
<label for="AddShMail">Shell Path</label>
<select class="selectSearch" name="AddShMail" id="AddShMail" >
	<option selected  value="">..</option>
	<?php				  
	foreach ($datiShList as $row) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SHELL_PATH=$row['SHELL_PATH'];
		?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL_PATH.'/'.$SHELL; ?></option><?php
	}
	?>
</select>	
<br>		   
<label for="EnableMail">Email</label>
	 <select class="selectSearch"  id="EnableMail"  name="EnableMail" >
		<option selected  value="">..</option>
		<?php
		foreach( $ListMail as $DettMail ){
			$IdMail=$DettMail[0];
			$Mail=$DettMail[1];
			$NameMail=$DettMail[2];
			$UsrMail=$DettMail[3];
			//if( ! in_array($IdMail ,$ListIdFind) ){
				?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
			//}
		}
		?>
	 </select>
	 
<label for="AddStart">Start</label>	 
	 <select class="selectNoSearch" id="AddStart" name="AddStart" >
		<option value="Y" >Yes</option>
		<option value="N" >No</option>
	 </select>
	 
	 <label for="AddEnd">End</label>	 
	 <select class="selectNoSearch" id="AddEnd" name="AddEnd" >
		<option value="Y" >Yes</option>
		<option value="N" >No</option>
	 </select> 
	 <button id="addworkflow" onclick="AddNewMail();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Add Shell</button>
</div>
</aside>
			
		
   
			<table id='idTabella' class="display dataTable">
			   <thead class="headerStyle">
			<tr>
			  <th>PATH</th>
			  <th>SHELL</th>
			  <th></th>
		
			</tr> 
			</thead>
		<tbody>			
    
	<?php  
    $PREC_ID_SH=0;
    foreach ($datiTableList as $row) {
        $ID_SH=$row['ID_SH'];
        $SHELL=$row['SHELL'];
        $SHELL_PATH=$row['SHELL_PATH'];
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $FLG_START=$row['FLG_START'];
        $FLG_END=$row['FLG_END'];
        $MAIL=$row['MAIL'];
        
        if ( "$ID_SH" != "$PREC_ID_SH" ){
          
            ?>
            <tr>

                <td class="ShLine addMail" onclick="openDialogMail('<?php echo $ID_SH; ?>','Aggiungi Mail: <?php echo $SHELL; ?>')"><?php echo $SHELL_PATH; ?></td>
                <td  class="ShLine Puls" onclick="openDialogMail('<?php echo $ID_SH; ?>','Aggiungi Mail: <?php echo $SHELL; ?>')">
                    <b><?php echo $SHELL; ?></b>
                </td>
				                <td class="ShLine" >
                  <i class="fa-solid fa-trash-can trashIconStyle" width="35px" style="color: black;" onclick="RemoveSh('<?php echo $ID_SH; ?>')"/>
                </td>
                
            </tr>   
            <?php 
            $PREC_ID_SH=$ID_SH;
        }           
        ?>
      
       <?php 
        $OLD_ID_SH=$row['ID_SH'];
        $OLD_SHELL=$row['SHELL'];
        $OLD_SHELL_PATH=$row['SHELL_PATH'];
        $OLD_ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $OLD_FLG_START=$row['FLG_START'];
        $OLD_FLG_END=$row['FLG_END'];
        $OLD_MAIL=$row['MAIL'];    
    }
   ?>
			</tbody>
    </table>
    <input type="hidden" name="ListDett" id="ListDett" value="<?php echo $ListDett; ?>">
    </form>
</div>
<script src="./view/alertmail/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>