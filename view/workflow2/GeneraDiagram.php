<table>
<tr>
<td><div style="font-size:28px;"><?php echo $WfsName; if ( "$ProcDescr" != "" ) { echo "    ( $ProcDescr )"; } ?></div></td>
<td width="100px" >
<button id="refresh" onclick="MostraDiagramma('<?php echo $IdWorkFlow; ?>',
                    '<?php echo $IdProcess; ?>',
                    '<?php echo $WfsName; ?>',
                    '<?php echo $ProcDescr; ?>');return false;" class="btn"><i class="fa-solid fa-refresh"> REFRESH</i></button> 
</td>
</tr>
</table> 

<div style="width:5000px; height:1500px;">
<svg id="Schemasvg" style="width:4000; height:1500;">
<?php
  
$Assex=10;
$OldLiv="";
$ArrLine=array();
foreach( $ArrLevel as $Liv ){
    
         if ( $Liv <> $OldLiv ) {
              $Assex=$Assex+240;
              $OldLiv=$Liv;
              $Assey=$Liv*30;
          } 
                                    
        foreach( $ArrLegami as $Legame ){
           //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
           if ( $Liv == $Legame[1] ){              
                                         
             $IdFlu=$Legame[2];
             if ( "$IdFlu" != "$OldIdFlu"){
               $OldIdFlu=$IdFlu;
               foreach( $ArrFlussi as $Flusso ){
                 $IdFlusso=$Flusso[0];
                 if ( "$IdFlusso" == "$IdFlu" ){ 
                      $NomeFlusso=$Flusso[1];
                      $DatiCountStatoFlusso = $_model->getCountStatoFlusso($IdProcess,$IdFlusso,$ProcMeseEsame);
                      foreach ($DatiCountStatoFlusso as $row) {
                           $NomeFlusso=$row['FLUSSO']; 
                           $DescFlusso=$row['DESCR'];  
                           $CntKO=$row['KO'];  
                           $CntOK=$row['OK'];  
                           $CntIZ=$row['IZ'];  
                           $CntTD=$row['TD'];  
                           $CntTT=$row['TT'];  
                           $CntTTF=$row['TTF'];
                        }       
                           $Stato='F';
                           if ( $CntTTF != 0 ){
                                        
                               $Errore=0;
                               $Note="";
                               $Stato='N';
                                        
                          //     $res = $_model->callTestSottoFlussi($IdWorkFlow,$IdProcess,$IdFlusso,$Stato,$Errore,$Note);                               
                             
                               
                           }
                        
                            if (  $CntKO != 0 ) { 
                               $Esito="E";
                            } else {
                               if (  $CntOK == $CntTT and $CntTT != 0 and ( "$Stato" == "S" or "$Stato" == "F" ) ){     
                                  $Esito='F';
                               } else {
                                 if (  $CntOK == 0 and $CntIZ == 0  and ( "$Stato" == "N" or "$Stato" == "F" ) ) {    
                                   $Esito='N';
                                 }else{
                                   if (  $CntIZ != 0  ) {
                                      $Esito='I';
                                   } else {
                                      $Esito='C';
                                   }
                                 }
                               }
                            }
                                                   
                           $Color="lightgray";  
                           
                           switch ( $Esito ){
                           case 'E' :
                            $Color='red';
                            break;
                           case 'I':
                            $Color="yellow";
                            break;
                           case 'F':
                            $Color='lightgreen';
                            break;
                           case 'C':
                            $Color="lightblue";
                            break;
                           } 
                        
                            $Assey=$Assey+40;
							$IfFluTrg=$Legame[5];
                           ?>
                           <rect id="RFlu<?php echo $IdFlusso; ?>" width="200" height="30" x="<?php echo $Assex; ?>" y="<?php echo $Assey; ?>" fill="<?php echo $Color; ?>" style="stroke:rgb(0,0,0);" />                                        
                           <text id="TFlu<?php echo $IdFlusso; ?>"  x="<?php echo $Assex+5; ?>" y="<?php echo $Assey+20; ?>" fill="black" font-family="Arial" font-size="15" style="text-align:center;"  ><?php echo $NomeFlusso; ?></text> 
                           <?php
                             if ( in_array($IdFlu, $ArrTest) ){
                                   ?><image xlink:href="./images/Lab.png" x="<?php echo $Assex+175; ?>" y="<?php echo $Assey+2; ?>" width="25px" /><?php
                             }
                        }
                 }
               }
             }
           }
        }

$cnt=0;
foreach( $ArrLegami as $Legame ){
     $IdFlu=$Legame[2];
     $Tipo=$Legame[4];
	 if ( "$Tipo" == "F"){
        $IfFluTrg=$Legame[5];
        ?><line id="Ln<?php echo $IdFlu.'_'.$IfFluTrg; ?>" stroke="black"  /><?php
	 }
}
?>
</svg> 
<script>

  function Posiziona(vDa,vA){
             var altezza = 15;
             var larghezza = 200;
             
             var SchemPos=$("#Schemasvg").position();
             var pos1 = $('#RFlu'+vDa).position();
             var pos2 = $('#RFlu'+vA).position();
			 
			 //alert('FATTO'+vDa+vA);
			 var posx1 = pos1.left - SchemPos.left;
			 var posy1 = pos1.top + altezza - SchemPos.top;
						 
			 var posx2 = pos2.left + larghezza - SchemPos.left;
			 var posy2 = pos2.top + altezza - SchemPos.top;
			 
             $('#Ln'+vDa+'_'+vA)
			 .attr('x1',posx1)
			 .attr('y1',posy1)
			 .attr('x2',posx2)
			 .attr('y2',posy2);

  }
<?php
foreach( $ArrLegami as $Legame ){
     $IdFlu=$Legame[2];
     $Tipo=$Legame[4];
     if ( "$Tipo" == "F"){
        $IfFluTrg=$Legame[5];
        ?>Posiziona(<?php echo $IdFlu; ?>,<?php echo $IfFluTrg; ?>);<?php
     } 
}
?>
</script>
</div>
