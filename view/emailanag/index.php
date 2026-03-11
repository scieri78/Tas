
<div class="wrapper">
  <div class="container-fluid">
    <div>
      <div class ="dataTables_wrapper">
      
        <form id="FormMail" method="POST">
		<input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
		<input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
		<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
		<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
		<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
		<input type="hidden" id="pageTable" name="pageTable" value="" />
		<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />
		
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Config | Email Anag</p> 
	<div class="asideContent">
  <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>	
		<input name="AddUsername" placeholder="Username" type="text" value="<?php echo $AddUsername; ?>" >
		
			<input name="AddName" placeholder="Nome Cognome"    type="text" value="<?php echo $AddName; ?>" >
			
				<input name="AddMail" placeholder="Email"    type="text" value="<?php echo $AddMail; ?>" >
				
							   <button id="addworkflow" onclick="addMailAnag();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Add User</button>

	</div>	
	</aside>	
		
          <table id='idTabella' class="display dataTable">
            <thead class="headerStyle">
              <tr>
           
                <th>USERNAME</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>TECNIC</th>
                <th>VALID</th>
                <th>RETI TWS</th>
                <th>CCN</th>
				     <th></th>	     
             <th></th>
              </tr>
            </thead>
            <tbody> <?php
      foreach ($datiControlliAnag as $row) {
          $update =array();
          $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
          $USERNAME=$row['USERNAME'];
          $NAME=$row['NAME'];
          $MAIL=$row['MAIL'];
          $update['TECNIC']=$row['TECNIC'];
          $update['VALID']=$row['VALID'];
          $update['CHIUSURA']=$row['CHIUSURA'];
          $update['CCN']=$row['CCN'];
          
      
          ?> <tr>
                
                  <!--  <img src="../images/Cestino.png" width="25px" class="Puls" onclick="RemoveMail('
										<?php echo $ID_MAIL_ANAG; ?>')"></td>
            -->
                <td> <?php echo $USERNAME; ?> </td>
                <td> <?php echo $NAME; ?> </td>
                <td> <?php echo $MAIL; ?> </td> 
	<?php
         foreach ($update as $k=>$v){
        if($v=="Y"){
			$vimage ='<i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;" onclick="UpdateMail(\'N\',\'1299\',\'T_TO\',\'14\')"></i>';
		}else{
			$vimage ='<i class="fa-solid fa-minus Puls" width="25px" class="closeIconStyle" style="color: white;" onclick="UpdateMail(\'N\',\'1299\',\'T_TO\',\'14\')"></i>';
		}
								echo	'<td>
											<div class="Puls">
												<a href="#" onclick="updateMailAnag('.$ID_MAIL_ANAG.',\''.$k.'\',\''.($v=='Y'?'N':'Y').'\')">'.$vimage.'</a>												
											</div>
										</td>';								
			}  ?>
           
            <td class="ShLine" >
                  <i class="fa-solid fa-pen-to-square" onclick="formSh('<?php echo $NAME; ?>','<?php echo $USERNAME; ?>','<?php echo $MAIL; ?>','<?php echo $ID_MAIL_ANAG; ?>')"/>
                </td>
           <td>
                 <i onclick="deleteMailAnag(<?php echo $ID_MAIL_ANAG; ?>,'<?php echo $USERNAME; ?>')" class="fa-solid fa-trash-can trashIconStyle" width="35px" style="color: black;"></i>
                 
			</td> 
			  </tr> <?php }  ?>	
     
							</tbody>    
					</table>
	      </form>
									

<script src="./view/emailanag/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>

