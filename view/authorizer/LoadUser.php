<?php


		 if ( $res and $Errore == 0 ) {
		?>
		<script>
		loadDettWfs('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>');
		/* $('#LoadDett<?php echo $IdWorkFlow; ?>').load('./PHP/Wfs_Gest_DettWfs.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});*/
			loadFlussi('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>');
		 /*$('#LoadFls<?php echo $IdWorkFlow; ?>').load('./PHP/Wfs_Gest_LoadFlussi.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});*/
        </script>
		<?php
		 }
     $Conta=count($DatiUtenti);   
	 if ( $Gruppo != "USER" and $Gruppo != "ADMIN" and $Gruppo != "READ"  and $Conta == "0" ){
               $TGruppo ='<div onclick="deleteGroupWF(\''.$IdWorkFlow.'\',\''.$WorkFlow.'\',\''.$IdGruppo.'\')" id="PulRmGroup<?php echo $IdWorkFlow; ?>" class="Plst" >
			   <i class="fa-solid fa-trash-can"></i>'.$Gruppo.'
			   </div>';
                              
                
            } else {
              $TGruppo = "<b>".$Gruppo." ($Conta)</b>";
            }
			echo  $TGruppo;
    
    foreach ($DatiUtenti as $rwU) {      
      $IdAss=$rwU['ID_ASS'];
      $Nm=$rwU['USERNAME'];
      $Nom=$rwU['NOMINATIVO'];
      $PosDel=false;
      ?><div id="PlRmUsr<?php echo $IdAss; ?>" class="Plst Mattone" >
	  
         <?php 
      /*   if ( $Gruppo == "ADMIN" ){
           //  if ( $CntU > 1 ) {
                ?>
				<i onclick="LoadUser('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdGruppo; ?>','<?php echo $Gruppo; ?>','<?php echo $IdAss; ?>','DU')" class="fa-solid fa-trash-can"></i>
				<?php
                $PosDel=true;
             }
         } else {*/
            ?>
			<i onclick="LoadUser('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdGruppo; ?>','<?php echo $Gruppo; ?>','<?php echo $IdAss; ?>','DU')" class="fa-solid fa-trash-can"></i>
			<?php 
            $PosDel=true;
       /*  }*/
         echo "$Nm - $Nom"; ?>
      </div>
      <?php 
     
    }

?>
