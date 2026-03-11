<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ( $TestRest == "1" ){
  ?><CENTER><div style="font-size: 15px;color:red;"><b>ATTENTION!! RESTATEMENT IS ENABLED!!</b></div><CENTER><BR><BR><?php
} 
?> 
<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> 
	<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>LossRess | Chiusura</p> 	
  <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>						
</aside>
    <form id="ShowLRStatus" method="POST">
    <CENTER>    
    <BR><BR>
    <input type="hidden" id="TABSHOW" name="TABSHOW" value="<?php echo $TABSHOW; ?>">   
    <div style="width:800px;" >
      <table  > 
      <tr>
      <td style="color:black;"><div class="TabShow <?php echo "$ClsCand"; ?>" onclick="$('#TABSHOW').val('CAND'); $('#ShowLRStatus').submit();"   >CARICAMENTI CANDIDATI</div></td>
      <td style="color:black;"><div class="TabShow <?php echo "$ClsTutti"; ?>" onclick="$('#TABSHOW').val('TUTTI'); $('#ShowLRStatus').submit();"  >TUTTI I CARICAMENTI</div></td>
      </tr>
      </table>
    </div>
    <BR>
<?php 

if ( $TABSHOW == "TUTTI" ){
?>  
    <div id="LastCand" >

    <div style="font-size: 15px;" ><B>TUTTI CARICAMENTI</B></div>
    <TABLE class="ExcelTable" style="width: 800px; margin: auto;">
    <tr>
    <th style="text-align:center;width:50px;" ></th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){    
    ?><th style="text-align:center;"><?php echo $CodComp; ?></th><?php
    }
    ?>
    </tr>
     <tr id="RigaCont" >
        <th style="text-align:center;width:50px;" >Cont</th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7];
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12];
               $vers=$Run[13];
               $CntVers=$Run[14];
               $Cand=$Run[18];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "" ){
                  ?><td style="background:#a7a7a7;" ><?php
                  ${'TD'.$CodComp.'_'.$RunLob}=1;
                  ${'CntVers'.$RunLob}=0;
               }
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" ){
                  ${'CntVers'.$RunLob}=${'CntVers'.$RunLob}+1;
                  if ( "$Cand" == "0" and "$ShStatus" != "I" ){ $ShStatus=$ShStatus.'N'; }
                  ?>
                  <div id="<?php echo $CodComp.$vers; ?>Cont" class="Lancio Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$vers; ?>Cont" class="ShowDett" hidden >
                      <B><?php echo $CodComp; ?> Cont</B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$vers; ?>Cont').mouseover(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').show();});
                   $('#<?php echo $CodComp.$vers; ?>Cont').mouseleave(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').hide();});
                   $('#Dett<?php echo $CodComp.$vers; ?>Cont').click(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     echo substr($Desc,0,10)."..";
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$IdRunSh" != "" ){ ?><img src="./images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $IdRunSh; ?>);"/><?php }
                  if ( "$File" != ""  ){ ?><img src="./images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="./images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  
                  ?>
                  <div>
                  <?php               
                  $Found=1;
               }
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "1" and "$TotVers" == "${'CntVers'.$RunLob}" ){
                  ?></td><?php
               }
           }
           if ( "$Found" == "0" ){
             ?><td class="EsitoN"></td><?php
           }
        }
        ?>
    </tr>
    <script>
      $('#RigaCont').mouseover(function(){ $('#RigaCont').addClass('InSel'); });
      $('#RigaCont').mouseleave(function(){ $('#RigaCont').removeClass('InSel');});
    </script>
    <?php   
    foreach( $Arr_Lob as $Lob ){
        ?>
        <tr id="Riga<?php echo $Lob;?>" >
        <th style="text-align:center;width:50px;" ><?php echo $Lob; ?></th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7]; 
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12]; 
               $vers=$Run[13]; 
               $CntVers=$Run[14];  
               $Cand=$Run[15];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" and "${'TD'.$CodComp.'_'.$RunLob}" == "" ){
                  ?><td  style="background:#a7a7a7;"  ><?php
                  ${'TD'.$CodComp.'_'.$RunLob}=1;
                  ${'CntVers'.$RunLob}=0;
               }               
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" ){
                  
                  if ( "$Cand" == "0" and "$ShStatus" != "I" ){ $ShStatus=$ShStatus.'N'; }
                  ?>
                  <div id="<?php echo $CodComp.$Lob.$vers; ?>" class="Lancio Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$Lob.$vers; ?>" class="ShowDett" hidden >
                      <B><?php echo $CodComp.' '.$Lob; ?></B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$Lob.$vers; ?>').mouseover(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').show();});
                   $('#<?php echo $CodComp.$Lob.$vers; ?>').mouseleave(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').hide();});
                   $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').click(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     echo substr($Desc,0,10)."..";
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$IdRunSh" != "" ){ ?><img src="./images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $IdRunSh; ?>);" /><?php }
                  if ( "$File" != ""  ){ ?><img src="./images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="./images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </div>
                  <?php
                  
                  if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "1" and "$TotVers" == "${'CntVers'.$RunLob}" ){
                     ?></td><?php
                  }
                  
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             if ( in_array($Lob,${'Arr'.$CodComp}) ){
               ?><td class="EsitoN" style="color:black; font-size:20px;background-color:darkgray !important;"><CENTER>-</CENTER></td><?php
             }else{
               ?><td class="EsitoN"></td><?php
             }
           }
        }
        ?>
          <script>
            $('#Riga<?php echo $Lob;?>').mouseover(function(){ $('#Riga<?php echo $Lob;?>').addClass('InSel'); });
            $('#Riga<?php echo $Lob;?>').mouseleave(function(){ $('#Riga<?php echo $Lob;?>').removeClass('InSel');});
          </script>
        </tr>
        <?php
    }
    ?>
   </TABLE>
  </div> 


<?php 
} else {
?>  

    <div id="ElencoCand" >
    <div style="font-size: 15px;" ><B>CARICAMENTI CANDIDATI</B></div>
    <TABLE class="ExcelTable" style="width: 800px; margin: auto;">
    <tr>
    <th style="text-align:center;width:50px;" ></th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){
    ?><th style="text-align:center;"><?php echo $CodComp; ?></th><?php
     ${$CodComp.'TotalTime'}=0;  
    }
    $TotalTime=0;  
    ?>
    </tr>
     <tr>
        <th style="text-align:center;width:50px;" >Cont</th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7];
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12]; 
               $vers=$Run[13];
               $CntVers=$Run[14];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" ){
                  $TotalTime=$TotalTime+$ShDiff;
                  ${$CodComp.'TotalTime'}=${$CodComp.'TotalTime'}+$ShDiff;
                  ?><td id="<?php echo $CodComp; ?>Cont" class="Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp; ?>Cont" class="ShowDett" hidden >
                      <B><?php echo $CodComp; ?> Cont</B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp; ?>Cont').mouseover(function(){ $('#Dett<?php echo $CodComp; ?>Cont').show();});
                   $('#<?php echo $CodComp; ?>Cont').mouseleave(function(){ $('#Dett<?php echo $CodComp; ?>Cont').hide();});
                   $('#Dett<?php echo $CodComp; ?>Cont').click(function(){ $('#Dett<?php echo $CodComp; ?>Cont').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     if ( substr("$Desc",0,10) != "$Desc" ){ echo substr($Desc,0,10).".."; } else {echo $Desc;}
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$File" != ""  ){ ?><img src="./images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="./images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </td><?php
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             ?><td class="EsitoN"></td><?php
           }
        }
        ?>
    </tr>
    <?php   
    foreach( $Arr_Lob as $Lob ){
        ?>
        <tr>
        <th style="text-align:center;width:50px;" ><?php echo $Lob; ?></th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7]; 
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12];
               $vers=$Run[13];
               $CntVers=$Run[14];
               $IdRunSh=$Run[16];   
               $Wait=$Run[17];         
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" ){
                  $TotalTime=$TotalTime+$ShDiff;
                  ${$CodComp.'TotalTime'}=${$CodComp.'TotalTime'}+$ShDiff;                
                  ?><td id="<?php echo $CodComp.$Lob; ?>" class="Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$Lob; ?>" class="ShowDett" hidden >
                      <B><?php echo $CodComp.' '.$Lob; ?></B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$Lob; ?>').mouseover(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').show();});
                   $('#<?php echo $CodComp.$Lob; ?>').mouseleave(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').hide();});
                   $('#Dett<?php echo $CodComp.$Lob; ?>').click(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     if ( substr("$Desc",0,10) != "$Desc" ){ echo substr($Desc,0,10).".."; } else {echo $Desc;}
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$File" != ""  ){ ?><img src="./images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="./images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </td><?php
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             if ( in_array($Lob,${'Arr'.$CodComp}) ){
               ?><td class="EsitoN" style="color:black; font-size:20px;background-color:darkgray !important;"><CENTER>-</CENTER></td><?php
             }else{
               ?><td class="EsitoN"></td><?php
             }
           }
        }
        ?>
        </tr>
        <?php
    }
    ?>
   <th style="text-align:center;width:50px;" >Time Elab</th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){
    ?><th style="text-align:center;"><?php echo gmdate('H:i:s', ${$CodComp.'TotalTime'}); ?></th><?php
    }
    ?>
    </tr>
    <tr>
     <th style="text-align:center;width:50px;"  >Total Time</th>
     <th colspan="7"  style="text-align:center;" ><?php echo gmdate('H:i:s', $TotalTime); ?></th>
    </tr>

   </TABLE>
  </div> 
 <?php  
} 

	 //--------------datiCandidatoDefinitivo-----------------------
	
    $DtCiDInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
   
    
    foreach ($datiCandidatoDefinitivo as $row) {
         $DtCiDInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }	
	
?>

<div id="CandInDef" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtCiDInfo; ?>" >Candidato in Definitivo</div>
     <div id="DettCf" class="ShowPostDett" hidden >
         <B>Candidato in Definitivo</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtCiDInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#CandInDef').mouseover(function(){ $('#DettCf').show();});
      $('#CandInDef').mouseleave(function(){ $('#DettCf').hide();});
     </script>     
   <table style="width:800px;" >
   <tr><td style="width:50%;">
   <?php 
   
    //--------------datiCuboApp-----------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
       
    
    foreach ($datiCuboApp as $row) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }
    
    ?><div id="CuboApp" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtInfo; ?>" >Cubo App</div>
     <div id="DettCA" class="ShowPostDett" hidden >
         <B>Cubo App</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#CuboApp').mouseover(function(){ $('#DettCA').show();});
      $('#CuboApp').mouseleave(function(){ $('#DettCA').hide();});
     </script>
  </td> 
  <td rowspan=2 style="width:50%;" >

 <?php  
    //----------------datiGiroSolvency-------------------------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
        
   
    foreach ($datiGiroSolvency as $row) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }
    
    ?><div id="GiroSolvency" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtInfo; ?>" >Giro Solvency
	<?php if ( "$ShEnd" != "") { echo '<BR>'.$ShEnd; } ?></div>
     <div id="DettGS" class="ShowPostDett" hidden >
         <B>Giro Solvency</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#GiroSolvency').mouseover(function(){ $('#DettGS').show();});
      $('#GiroSolvency').mouseleave(function(){ $('#DettGS').hide();});
     </script>  
  </td>
  </tr>
  <tr>
  <td>
    <?php
    //--------------------------------Cubo Solvency------------------------------------------------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
    
    
    foreach ($datiCuboSolvency as $row) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];       
    }
    
    ?><div id="CuboSolvency" class="Esito<?php echo $ShStatus; ?>">Cubo Solvency</div>
     <div id="DettBs" class="ShowPostDett" hidden >
         <B>Cubo Solvency</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>            
     </div>
     <script>
      $('#CuboSolvency').mouseover(function(){ $('#DettBs').show();});
      $('#CuboSolvency').mouseleave(function(){ $('#DettBs').hide();});
     </script>
    </td>
    </tr>
    </table>     
    </CENTER><?php

$NoControl=$_POST['NoControl'];
?>
<script>

$('#ShowLRStatus').submit(function(){
  $('#Waiting').show();
  var input = $("<input>")
  .attr("type", "hidden")
  .attr("name", "NoControl")
  .val('1');
  $('#ShowLRStatus').append($(input));  
});

setInterval(function(){ 
  $('#ShowLRStatus').submit();
}, 30000);



function OpenShSel(vId){
    window.open('./index.php?sito='+getParameterByName('sito')+'&controller=statoshell&action=index&IDSELEM='+vId);
}


</script>
