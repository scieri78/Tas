
    <table class="ExcelTable" style="width:98%;">
	<caption>DIPENDENZA <?php echo $Flusso; ?></caption>
    <tr>
      <th><img class="ImgIco" src="./images/Gruppo.png"></th>
    </tr><?php
    foreach ($DatiListDipFlusso as $rowFlusso) { 
      $IdDip=$rowFlusso["ID_DIP"];
      $TipoDip=$rowFlusso["TIPO"];
      $NameDip=$rowFlusso["DIPENDENZA"];     
      $GruppoDip=$rowFlusso["ID_GRUPPO"];

       switch ( $TipoDip ) {
         case "C":
           $ImgTipo="Carica";
           break;
         case "F":
           $ImgTipo="Flusso";
           break;
         case "V":
           $ImgTipo="Valida";
           break;  
         case "E":
           $ImgTipo="Elaborazione";
           break;  
         case "L":
           $ImgTipo="Setting";
           if ( "$DipLinkTipo" == "E" ){
             $ImgTipo="Link";
           }
           break;  
       }
       
      ?>
      <tr class="bordertop" >
    
        <td><img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" >
           <?php echo "$NameDip"; ?>
           <div id="ShowAuthDip_<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" ></div>
		   
	<?php	  
	
	
    foreach ($DatiAutoDip[$IdDip] as $rwFL ) {      
      $IdAut=$rwFL['ID_AUT_DIP'];
      $Gruppo=$rwFL['GRUPPO'];
      ?>
      <div id="PulRemDipGroup<?php echo $IdAut; ?>" name="PulRemDipGroup<?php echo $IdAut; ?>"  class="deleteFlusso Plst Mattone" >
         <?php 
         //if ( $CntFL > 1 ) {
            ?>
			<i onclick="deleteAuthDipFlu( '<?php echo $IdWorkFlow; ?>',
                        '<?php echo $WorkFlow; ?>',
                        '<?php echo $IdFlusso; ?>',
                        '<?php echo $IdDip; ?>',
                        '<?php echo $Flusso; ?>',
                        '<?php echo $IdAut; ?>',
                        'RAUT',
						 '<?php echo $NameDip; ?>')" class="fa-solid fa-trash-can"></i>
			<?php
         //}
         echo $Gruppo; 
         
    }

?>
      </div>
                    
           <div  id="PulAddAthIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" class="Plst Mattone" style="color:red;">		
					<i onclick="AddAthIn('<?php echo $IdWorkFlow; ?>',
                    '<?php echo $WorkFlow; ?>',
                    '<?php echo $IdFlusso; ?>',
                    '<?php echo $Flusso; ?>',
				    '<?php echo $TipoDip; ?>',
                    '<?php echo $IdDip; ?>',
                    '<?php echo $NameDip; ?>')" class="fa-regular fa-square-plus"></i>
					
		</div>
         
        </td>
      </tr>
      <?php
    }
    ?>
    
    </table>

