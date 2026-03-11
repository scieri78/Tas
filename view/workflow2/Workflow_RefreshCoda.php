<?php

foreach ($DatiRichiesteCodaEsito as $row) {
    $Flu=$row['ID_FLU'];
    $Tip=$row['TIPO'];
    $Dip=$row['ID_DIP'];
    $Legame=$row['ID_LEGAME'];
    $Esito=$row['ESITO'];
	
	?>
	<script>
	  $('#StatusFlusso<?php echo $Flu; ?>').load('./index.php?controller=workflow2&action=Flusso.php',{
			 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
			 IdProcess: <?php echo $IdProcess; ?>,
			 IdFlusso: <?php echo $Flu; ?>
	  });   
	</script>       

    <script>        
       $('#ImgRefresh<?php echo $Legame; ?>').hide();
       $('#IcoRefresh<?php echo $Flu; ?>').hide();
       
    <?php if ( "$Esito" == "I" ) { ?>
       $('#ImgDip<?php echo $Legame; ?>').hide();
       $('#IcoSveg<?php echo $Flu; ?>').hide();
       $('#ImgRun<?php echo $Legame; ?>').show();
       $('#IcoRun<?php echo $Flu; ?>').show();
	   $('#ImgSveglia<?php echo $Legame; ?>').hide();
       $('#ElabInCodaWait').hide();
       $('#ElabInCodaLoad').show();
    <?php   } else { ?>    
       $('#ImgRun<?php echo $Legame; ?>').hide();
       $('#IcoRun<?php echo $Flu; ?>').hide();
       $('#IcoSveg<?php echo $Flu; ?>').show();
	   $('#ImgSveglia<?php echo $Legame; ?>').show();
       $('#ElabInCodaLoad').hide();
       $('#ElabInCodaWait').show();
    <?php   } ?>   
    </script>
    <?php
}

if ( "$OldMaxTime" == "" ){
    $OldMaxTime=$Now;
	$SetMaxTime=$OldMaxTime;
}

function RefreshSons($Flu){
    global $_model,$IdWorkFlow,$IdProcess;
    $DatiLegamiFlusso = $_model->getLegamiFlusso($IdWorkFlow,$Flu);
    foreach ($DatiLegamiFlusso as $row) {
        $FluIn=$row['ID_FLU'];
        ?>
        <script>
          $('#StatusFlusso<?php echo $FluIn; ?>').load('./index.php?controller=workflow2&action=Flusso.php',{
                 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
                 IdProcess: <?php echo $IdProcess; ?>,
                 IdFlusso: <?php echo $FluIn; ?>
          });   
        </script>       
        <?php   
        RefreshSons($FluIn);
    } 
}





foreach ($DatiFlusso as $row) {
    $Flu=$row['ID_FLU'];
    $NomeFlu=$row['FLUSSO'];
    $Tip=$row['TIPO'];
    $Dip=$row['ID_DIP'];
    $Legame=$row['ID_LEGAME'];
    $EsitoDip=$row['ESITO'];
    
	
    $SetMaxTime=$row['LAST'];
	
    ?>
    <script>
        $('#StatusFlusso<?php echo $Flu; ?>').load('./index.php?controller=workflow2&action=Flusso.php',{
             IdWorkFlow: <?php echo $IdWorkFlow; ?>,
             IdProcess: <?php echo $IdProcess; ?>,
             IdFlusso: <?php echo $Flu; ?>
        }); 
    </script>       
    <?php   
	
    RefreshSons($Flu);
	
	?>
	<script>
	 $('#ImgRun<?php echo $Legame; ?>').hide();
	 $('#ImgDip<?php echo $Legame; ?>').hide();
	 $('#ImgRefresh<?php echo $Legame; ?>').show();
	</script>
	<?php
    
	if ( "$SelIdFlu" == "$Flu" ){
		  ?>
		  <script>
			  //$('#ShowDettFlusso').empty().load('./PHP/WorkFlow_LoadFlusso.php',{
				//	 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
				//	 IdProcess:  <?php echo $IdProcess; ?>,
				//	 IdFlu:      <?php echo $Flu; ?>,
				//	 Flusso:     '<?php echo $NomeFlu; ?>'
			  //});
			  $('#ImgDip<?php echo $Flu; ?>').hide();
			  $('#ImgRefresh<?php echo $Flu; ?>').show();
		  </script>
		  <?php
	}
	
    if ( "$SelIdFlu" == "$Flu" and "$SelIdDip" == "$Dip" ){
		  
          if ( "$Tip" == "C" or "$Tip" == "E" ){
            if ( "$Tip" == "C" ){
              $NotaRis="Caricamento  $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente";
            }else{
              $NotaRis="Elaborazione $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente"; 
            }       
            $Img="CodaOK";
            if ( "$EsitoDip" != "F" ) {
				 if ( "$EsitoDip" == "W" ) {
					 $TMsg="Warning";
					 $Img="CodaWR"; 
				 }else{
					 $TMsg="Error";
                     $Img="CodaKO"; 
				 }
                 if ( "$Tipo" == "C" ){
                    $NotaRis="$TMsg nel Caricamento $SelNomeDip del Flusso $SelNomeFlu";
                 }else{
                    $NotaRis="$TMsg nell Elaborazione $SelNomeDip del Flusso $SelNomeFlu"; 
                 }
            }
            ?>
            <script>                
               $('#SelDipendenza').val('');
               $('#EsitoUpload').load('./index.php?controller=workflow2&action=Esito.php',{ Img : '<?php echo $Img; ?>', NotaRis : '<?php echo $NotaRis; ?>' }).show();
            </script>
            <?php        
		  }
    } 
}



?>
<script>                
  $('#LastTimeCoda').val('<?php echo $SetMaxTime; ?>');
  $('#ElabInCodaLoad').hide();
  $('#ElabInCodaWait').hide();
  <?php  
     if ( $CntProc != 0 ){
        ?>$('#ElabInCoda').show();<?php
        if ( $InRun != 0 ){
           ?>$('#ElabInCodaLoad').show();<?php  
        } else {
           ?>$('#ElabInCodaWait').show();<?php  
        }
     } else {
        ?>$('#ElabInCoda').hide();<?php
     }
     if ( $CntProc == $CntWfs ){
         ?>
         $('#PulMostraCoda').css('width','170px');
         $('#FraseCoda').text('<?php  echo $CntProc; ?> in Coda');
         <?php
     }else{
         ?>
         $('#PulMostraCoda').css('width','170px');
         $('#FraseCoda').text("<?php  echo $CntProc; ?> in Coda su <?php  echo $CntWfs; ?> ");
         <?php
     }
     
   ?>
</script>

