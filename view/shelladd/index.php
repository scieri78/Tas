<div class= "container-fluid">
	  
    <form id="FormMail" method="POST">
	<input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
	<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
	<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
	<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
	<input type="hidden" id="pageTable" name="pageTable" value="" />
	<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />

   
<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Config | Add Shell</p> 
	<div class="asideContent">
    <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>	
		<input  name="ShellPath" id="ShellPath" value="/area_staging_TAS/DIR_SHELL" size="100" placeholder="Shell Path">
	   <input  type="text" name="ShellName" id="ShellName" size="50" placeholder="Shell Name">
	   <button id="addworkflow" onclick="addShell();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Add Shell</button>
   </div>
</aside>
   
	<table id='idTabella' class="display dataTable">
	   <thead class="headerStyle">
	<tr>
	  <th>PATH</th>
	  <th>SHELL</th>
      <?php if (!$hideParall) { ?>
	  <th>PARALL</th>
      <?php } ?>
	  <th></th>  
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
        $PARALL=$row['PARALL'];
        
        if ( $ID_SH != $PREC_ID_SH ){
          
            ?>
            <tr>
                <td class="ShLine ">
				 <?php echo $SHELL_PATH; ?></td>
                <td  class="ShLine Puls">
                <div class="addMail" onclick="openDialog('<?php echo $ID_SH; ?>','<?php echo $SHELL; ?>','apriFile')">
									<img src="./images/File.png" class="IconSh" style="width:25px;">
									<?php echo $SHELL; ?></div>                    
                </td>
                                <?php if (!$hideParall) { ?>
                 <td class="ShLine ">
				<?=$PARALL=='Y'?'Si':'No'?></td>
                                <?php } ?>

                <td class="ShLine" >
                  <i class="fa-solid fa-pen-to-square" onclick="formSh('<?php echo $SHELL; ?>','<?php echo $SHELL_PATH; ?>','<?php echo $ID_SH; ?>','<?php echo $PARALL; ?>')"/>
                </td>
				<td class="ShLine" >
                  <i class="fa-solid fa-trash-can trashIconStyle" onclick="removeSh('<?php echo $ID_SH; ?>')"/>
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
    }
   ?>
			</tbody>
    </table>
    <input type="hidden" name="ListDett" id="ListDett" value="<?php echo $ListDett; ?>">
    </form>
</div>


<script src="./view/shelladd/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>