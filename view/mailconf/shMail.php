<form id="FormMailDati" method="POST">	
  <table class="display dataTable">
		<thead class="headerStyle">
			<tr>
		<th>CATEGORY</th>
      <th>EMAIL</th>
      
	 
   </tr> 
  </thead>
   <tbody>
					<?php
					foreach( $MailType as $TYPE ){
						switch($TYPE){
							case 'T_TO': $DescType="DESTINATARI TECNICI";   break;
							case 'T_CC': $DescType="COPIA CARBONE TECNICI"; break;
							case 'R_TO': $DescType="DESTINATARI FINALI";    break;
							case 'R_CC': $DescType="COPIA CARBONE FINALI";  break;							
						}
						?>
						<tr>	
                            <td><B><?php echo $DescType; ?></B></td>						
							<td class="ShLine">
								
								<table>
								<?php
								$ListIdFind=array();
								foreach( ${'Mail_'.$TYPE.'_'.$ID_SH} as $DettMail ){
									$ID_MAIL_ANAG=$DettMail[0];
									array_push($ListIdFind, $ID_MAIL_ANAG); 
									$MAIL=$DettMail[1];
									$MAIL_ENABLE=$DettMail[2];
									
									?><tr>
											<td>
											<?php
											if ( $MAIL_ENABLE == "Y" ){
											  ?><i class="fa-solid fa-check Puls" style="color: green;" onclick="UpdateMail('N','<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"/><?php
								            } else {
											  ?><i class="fa-solid fa-xmark Puls" width="25px" class="closeIconStyle" style="color: red;" onclick="UpdateMail('Y','<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"/><?php
					                        }
											?>
											<?php echo $MAIL; ?>
											
											</td>
											<td><i class="fa-solid fa-trash-can trashIconStyle" width="35px" style="color: black;" onclick="RemoveMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"/>
											
											</td>	
								
								</tr><?php
								}
								?>
								
							    <tr>
									 <td>
										 <select class="selectSearch" id="xAddSelMail<?php echo $ID_SH.$TYPE; ?>" style="width:300px;" 
										onchange="AddAction('AddMail','<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>')">
										
											<option value="" >..</option>
											<?php
											foreach( $DatiListMail as $DettMail){
												$IdMail=$DettMail['ID_MAIL_ANAG'];
												$Mail=$DettMail['MAIL'];
												$NameMail=$DettMail['NAME'];
												$UsrMail=$DettMail['USERNAME'];
												if( ! in_array($IdMail ,$ListIdFind) ){
													?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
											    }
											}
											?>
										 </select>
									 </td>
									 <td><i class="fa-solid fa-square-plus addnewIconStyle"></i>
									 </td>
								 </tr>
							     </table></td>
						</tr>
						<?php
					}
					?>
			
</tbody>
      </table>
				</form>
				
				
				
<script src="./view/mailconf/JS/shmail.js?p=<?php echo rand(1000,9999);?>"></script>