
<?php


    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
        
    $Azione=$_POST['Azione'];
    $titoloWF=$_POST['titoloWF'];
    $Errore=0;
	$Note="";
if ( "$Azione" != "" ){
		?>
		
<?php } ?>
<table class="ExcelTable LoadDett" >
     <tr>
       <th >
	   <img class="ImgIco" src="./images/Utente.png">
         <div style="float:right" id="PulAddGroup<?php echo $IdWorkFlow; ?>" class="Plst" >
			 
		 
		 <figure onclick="addNewGroup('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>')">
		 <i  class="fa-regular fa-user-group" alt="New Group"></i>New Group
		 		  
		</figure>       
       </th>       
      </tr>
    <?php
    
    foreach ($DatiListGR as $rwGr) {
      $IdGruppo=$rwGr['ID_GRUPPO'];
      $Gruppo=$rwGr['GRUPPO'];
      $Conta=$rwGr['CONTA'];
      ?><tr class="bordertop" >
            
            <td>
                <div id="US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>" ></div>
                <div onclick="AddFlusso('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdGruppo; ?>','<?php echo $Gruppo; ?>')" id="PulAddUser<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>" class="Plst Mattone" style="color:red;">
				<i class="fa-regular fa-square-plus"></i>				
				
				</div>
                <script>
                /*  $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').load('./PHP/Wfs_Gest_LoadUser.php',{
                       IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                       WorkFlow: '<?php echo $WorkFlow; ?>',
                       IdGruppo: '<?php echo $IdGruppo; ?>',
                       Gruppo: '<?php echo $Gruppo; ?>'
                  });*/
				  
				  LoadUser('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdGruppo; ?>','<?php echo $Gruppo; ?>')

                </script>
            </td>
        </tr><?php
    } ?>
</table>


<script>
		/* $('#LoadFls<?php echo $IdWorkFlow; ?>').load('./PHP/Wfs_Gest_LoadFlussi.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});*/
			
		loadFlussi('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>');
        </script>
