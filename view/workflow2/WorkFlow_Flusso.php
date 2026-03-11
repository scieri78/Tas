
	<div id="Flu<?php echo $IdFlusso; ?>" class="Flusso <?php echo $classFlusso; ?>"
	 onclick="OpenDettFlusso(<?php echo $IdWorkFlow; ?>,
							<?php echo $IdProcess; ?>,
							<?php echo $IdFlusso; ?>,
							'<?php echo $NomeFlusso; ?>',
							'<?php echo $DescFlusso; ?>',
							'<?php echo $ProcMeseEsame; ?>')" >
		<img src="./images/PreStep.png"   id="DipP<?php echo $IdFlusso; ?>" class="LinkDipP" hidden>
		<img src="./images/PostStep.png" id="DipS<?php echo $IdFlusso; ?>" class="LinkDipS" hidden>	
		<div class="TitFlu <?php echo "Tit".$classFlusso; ?>" title="<?php echo $IdFlusso; if ( "$DescFlusso" != "" ){ echo " - $DescFlusso"; }	 ?>" ><B style="color:<?php echo $ColTit; ?>" ><?php echo $NomeFlusso; ?></B></div>		
        <img src="./images/<?php echo $Img; ?>.png" class="ImageEsito">
		<div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="MostraFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>)"></div>
		<?php
		
		if ( "$CntLab" != "0" ){
		  ?><img src="./images/Lab.png" title="Laboratorio"  class="ImgSveglia" ><?php
		}
		$BlkCons=false; 
		$NotBlkCons=false;
		if ( "${BlockCons}" == "Y" and "${PeriodCons}" != "0" ){ $BlkCons=true; }
		if ( $Permesso == 0 and "${PeriodCons}" != "0"  ){ $BlkCons=true; }
	    if ( "${BlockCons}" == "S" and "${PeriodCons}" != "0" and $RdOnly == 0 ){ $NotBlkCons=true; }
		if ( $ARich != 0 ){
		  ?><img src="./images/Arichiesta.png" title="A Richiesta"   class="ImgSveglia" ><?php
		}
        if ( "$NomeFlusso" != "UTILITY" ){		
          if ( $BlkCons ){
		   ?><img src="./images/Lock.png"    class="LockMode" ><?php
		  } else {			   
		       if ( $RdOnly != 0 or $Permesso == 0 or $WfsRdOnly != 0 or $BlkCons ){
		        if ( ! $NotBlkCons ){
		  		if ( "${PeriodCons}" != "0" ){ 
		            ?><img src="./images/Lock.png"        id="FlussoReadOnly" class="LockMode" ><?php
		  		}else{
		  		  ?><img src="./images/ReadMode.png"    id="FlussoReadOnly" class="FlussoReadMode" ><?php
		  		}
		  	  }
		       }	
		  }
		}
		
		if ( $StManual != 0  ){
		 ?><img src="./images/hand.png" title="Manual" class="ImgSveglia" ><?php
		}
		if ( $CntTCD != 0  ){
		 ?><img src="./images/ConfermoDato.png" class="ImgSveglia" ><?php
		}
		
		if ( $CntWar != 0  ){
		 ?><img src="./images/Warning.png" title="Warning" class="ImgSveglia" ><?php
		}
		
		if ( $CntCD != 0 ){
		 
		  if ( $CntIZ != 0 ){
		    ?><img id="IcoRun<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/Loading.gif" ><?php
		  } else {
			?><img id="IcoSveg<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/Sveglia.png" onclick="ForzaScodatore()" style="coursor:pointer;" ><?php  
		  }
		  
		  ?><img id="IcoRefresh<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/refresh.png" hidden ><?php
		}
		
		if ( "$Warning" == "-1" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgIco" src="./images/Alert.png" title="Strato Rimpiazzato"></label><?php }
		if ( "$Warning" > "0" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgSveglia" src="./images/Attention.png" title="<?php if ( "$Warning" > "1" ){ echo $Warning; }  ?>" ></label><?php }
		?>
		
	</div>

<script>
    var vSel = $('#SelFlusso').val();
    //$('#Flu'+vSel).css('border','1px solid white').addClass('ingrandFlu');
	$('#Flu'+vSel).addClass('ingrandFlu');
	
	  $('#Flu<?php echo $IdFlusso; ?>').mouseenter(function(){ <?php
			foreach( $ArrPreFlussi as $IdFlu ){
			  ?>$('#DipP<?php echo $IdFlu; ?>').show();
			  $('#Flu<?php echo $IdFlu; ?>').addClass('PreSel');
			  <?php
			}
			
			foreach( $ArrSucFlussi as $IdFlu ){
			  ?>$('#DipS<?php echo $IdFlu; ?>').show();
			  $('#Flu<?php echo $IdFlu; ?>').addClass('PostSel');
			  <?php
			}
		?>
	  });

	  $('#Flu<?php echo $IdFlusso; ?>').mouseleave(function(){ <?php
			foreach( $ArrPreFlussi as $IdFlu ){
			  ?>$('#DipP<?php echo $IdFlu; ?>').hide();
			  $('#Flu<?php echo $IdFlu; ?>').removeClass('PreSel');
			  <?php
			}
			
			foreach( $ArrSucFlussi as $IdFlu ){
			  ?>$('#DipS<?php echo $IdFlu; ?>').hide();
			  $('#Flu<?php echo $IdFlu; ?>').removeClass('PostSel');
			  <?php
			}
		?>
	 });
     function ForzaScodatore(){
                   var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "ForzaScodatore")
                  .val(1);
                  $('#MainForm').append($(input));
                  
                //   $("#Waiting").show();
                   $('#MainForm').submit();    		 
	 }
</script>
